<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../config/db.php';
require '../includes/factura_pdf.php';
require '../includes/libs/phpmailer/src/Exception.php';
require '../includes/libs/phpmailer/src/PHPMailer.php';
require '../includes/libs/phpmailer/src/SMTP.php';

/* Usamor phpmailer para enviar las facturas a bikeneke y al cliente y doompdf para generar la factura*/

use PHPMailer\PHPMailer\PHPMailer;

function enviarFacturaPorEmail($destinatario, $nombre, $archivo_pdf) {
    $mail = new PHPMailer(true);
    $ruta_pdf = '../private/facturas/' . basename($archivo_pdf);

    if (!file_exists($ruta_pdf)) return;

    try {
        $mail->isMail();
        $mail->setFrom('no-reply@bikeneke.com', 'Bikeneke');
        $mail->addAddress($destinatario, $nombre);
        $mail->Subject = 'Factura de alquiler - Bikeneke';
        $mail->Body = "Hola $nombre,\n\nGracias por alquilar con Bikeneke. Adjuntamos tu factura.\n\nUn saludo.";
        $mail->addAttachment($ruta_pdf);
        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar a $destinatario: " . $mail->ErrorInfo);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['carrito'])) {
    header('Location: seleccionar_bicicleta.php');
    exit;
}

$carrito = $_SESSION['carrito'];
$nombre = trim($_POST['nombre']);
$apellidos = trim($_POST['apellidos']);
$dni = trim($_POST['dni']);
$telefono = trim($_POST['telefono']);
$email = trim($_POST['email'] ?? '');
$alojamiento_id = intval($_POST['alojamiento_id']);
$entrega = $_POST['entrega'];
$recogida = $_POST['recogida'];
$hora_entrega = $_POST['hora_entrega'];
$hora_recogida = $_POST['hora_recogida'];
$accesorios_seleccionados = $_POST['accesorios'] ?? [];

$total = 0;
$lineas = [];

foreach ($carrito as $item) {
    $lineas[] = [
        'cantidad' => $item['cantidad'],
        'tipo' => $item['tipo_nombre'],
        'categoria' => $item['categoria_nombre'],
        'duracion' => $item['duracion'],
        'unidades_tiempo' => $item['unidades_tiempo'],
        'precio_unitario' => $item['precio_unitario'],
        'subtotal' => $item['total_linea']
    ];
    $total += $item['total_linea'];
}

$servicios = [];
if ($entrega === 'alojamiento') {
    $servicios[] = ['nombre' => 'Entrega en alojamiento', 'precio' => 4.00];
    $total += 4.00;
} else {
    $servicios[] = ['nombre' => 'Entrega en tienda', 'precio' => 0.00];
}

if ($recogida === 'alojamiento') {
    $servicios[] = ['nombre' => 'Recogida en alojamiento', 'precio' => 4.00];
    $total += 4.00;
} else {
    $servicios[] = ['nombre' => 'Recogida en tienda', 'precio' => 0.00];
}

$accesorios = [];
foreach ($accesorios_seleccionados as $id => $datos) {
    if (isset($datos['seleccionado'])) {
        $modo = $datos['modo'];
        $cantidad = intval($datos['cantidad']);
        $stmt = $pdo->prepare("SELECT nombre, precio_hora, precio_dia FROM accesorios WHERE id_accesorio = ?");
        $stmt->execute([$id]);
        $acc = $stmt->fetch();

        $precio_base = ($modo === 'hora') ? $acc['precio_hora'] : $acc['precio_dia'];
        $subtotal = $cantidad * $precio_base;
        $total += $subtotal;

        $accesorios[] = [
            'nombre' => $acc['nombre'],
            'cantidad' => $cantidad,
            'modo' => $modo,
            'precio_unitario' => $precio_base,
            'subtotal' => $subtotal
        ];
    }
}

$iva = round($total * 0.21, 2);
$total_con_iva = round($total + $iva, 2);

// Obtener dirección completa del alojamiento
$stmt = $pdo->prepare("SELECT direccion, codigo_postal, localidad, provincia, pais FROM alojamientos WHERE id_alojamiento = ?");
$stmt->execute([$alojamiento_id]);
$alojamiento = $stmt->fetch();
$direccion_completa = $alojamiento
    ? "{$alojamiento['direccion']}, {$alojamiento['codigo_postal']} {$alojamiento['localidad']}, {$alojamiento['provincia']}, {$alojamiento['pais']}"
    : '';

$nombre_archivo = 'factura_' . date('Ymd_His') . '_' . rand(1000, 9999) . '.pdf';
$ruta_archivo = __DIR__ . '/../private/facturas/' . $nombre_archivo;

// Insertar factura (temporalmente sin número)
$stmt = $pdo->prepare("INSERT INTO facturas (nombre_cliente, apellidos_cliente, dni, telefono, email, id_alojamiento, hora_entrega, hora_recogida, subtotal, iva, total, archivo_pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$nombre, $apellidos, $dni, $telefono, $email, $alojamiento_id, $hora_entrega, $hora_recogida, $total, $iva, $total_con_iva, $nombre_archivo]);
$id_factura = $pdo->lastInsertId();

// Generar número de factura
$anio = date('Y');
$secuencia = str_pad($id_factura, 4, '0', STR_PAD_LEFT);
$numero_factura = "FA-$anio-$secuencia";
$pdo->prepare("UPDATE facturas SET numero_factura = ? WHERE id_factura = ?")
    ->execute([$numero_factura, $id_factura]);

// Preparar datos para PDF
$datos_factura = [
    'numero_factura' => $numero_factura,
    'cliente' => "$nombre $apellidos",
    'dni' => $dni,
    'telefono' => $telefono,
    'hora_entrega' => $hora_entrega,
    'hora_recogida' => $hora_recogida,
    'alojamiento_id' => $alojamiento_id,
    'alojamiento_direccion' => $direccion_completa,
    'lineas' => $lineas,
    'servicios' => $servicios,
    'accesorios' => $accesorios,
    'subtotal' => $total,
    'iva' => $iva,
    'total' => $total_con_iva
];

generarFacturaPDF($datos_factura, $ruta_archivo);

foreach ($lineas as $linea) {
    $stmt = $pdo->prepare("INSERT INTO lineas_factura (id_factura, tipo, categoria, duracion, unidades, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_factura, $linea['tipo'], $linea['categoria'], $linea['duracion'], $linea['unidades_tiempo'], $linea['cantidad'], $linea['precio_unitario'], $linea['subtotal']]);
}

foreach ($servicios as $serv) {
    $stmt = $pdo->prepare("INSERT INTO factura_servicio (id_factura, nombre, precio) VALUES (?, ?, ?)");
    $stmt->execute([$id_factura, $serv['nombre'], $serv['precio']]);
}

foreach ($accesorios as $acc) {
    $stmt = $pdo->prepare("INSERT INTO factura_accesorio (id_factura, nombre, modo, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_factura, $acc['nombre'], $acc['modo'], $acc['cantidad'], $acc['precio_unitario'], $acc['subtotal']]);
}

// Enviar facturas por email
if (!empty($email)) {
    enviarFacturaPorEmail($email, $nombre, $nombre_archivo);
}

enviarFacturaPorEmail('delete@standardinet.com', "$nombre $apellidos", $nombre_archivo);

unset($_SESSION['carrito']);
header('Location: gracias.php?factura=' . urlencode($nombre_archivo));
exit;
