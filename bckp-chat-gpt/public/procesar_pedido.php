<?php
session_start();
require '../config/db.php';
require '../includes/factura_pdf.php';

if (isset($_SESSION['factura_generada'])) {
    header('Location: gracias.php?factura=' . urlencode($_SESSION['factura_generada']));
    exit;
}

if (empty($_SESSION['carrito']) || empty($_SESSION['datos_cliente'])) {
    header('Location: seleccionar_bicicleta.php');
    exit;
}

$carrito = $_SESSION['carrito'];
$datos = $_SESSION['datos_cliente'];

$nombre = trim($datos['nombre']);
$apellidos = trim($datos['apellidos']);
$dni = trim($datos['dni']);
$telefono = trim($datos['telefono']);
$email = trim($datos['email'] ?? '');
$alojamiento_id = intval($datos['alojamiento_id']);
$entrega = $datos['entrega'];
$recogida = $datos['recogida'];
$hora_entrega = $datos['hora_entrega'];
$hora_recogida = $datos['hora_recogida'];
$accesorios_seleccionados = $datos['accesorios'] ?? [];

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
foreach ($accesorios_seleccionados as $id => $datosAcc) {
    if (isset($datosAcc['seleccionado'])) {
        $modo = $datosAcc['modo'];
        $cantidad = intval($datosAcc['cantidad']);
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

$stmt = $pdo->prepare("SELECT CONCAT(direccion, ', ', codigo_postal, ' ', localidad, ', ', provincia, ', ', pais) AS direccion FROM alojamientos WHERE id_alojamiento = ?");
$stmt->execute([$alojamiento_id]);
$direccion_alojamiento = $stmt->fetchColumn();

$nombre_archivo = 'factura_' . date('Ymd_His') . '_' . rand(1000, 9999) . '.pdf';
$ruta_archivo = __DIR__ . '/../private/facturas/' . $nombre_archivo;

$datos_factura = [
    'cliente' => "$nombre $apellidos",
    'dni' => $dni,
    'telefono' => $telefono,
    'email' => $email,
    'hora_entrega' => $hora_entrega,
    'hora_recogida' => $hora_recogida,
    'alojamiento_id' => $alojamiento_id,
    'direccion_alojamiento' => $direccion_alojamiento,
    'lineas' => $lineas,
    'servicios' => $servicios,
    'accesorios' => $accesorios,
    'subtotal' => $total,
    'iva' => $iva,
    'total' => $total_con_iva
];

generarFacturaPDF($datos_factura, $ruta_archivo);

$stmt = $pdo->prepare("INSERT INTO facturas (nombre_cliente, apellidos_cliente, dni, telefono, email, id_alojamiento, hora_entrega, hora_recogida, subtotal, iva, total, archivo_pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$nombre, $apellidos, $dni, $telefono, $email, $alojamiento_id, $hora_entrega, $hora_recogida, $total, $iva, $total_con_iva, $nombre_archivo]);
$id_factura = $pdo->lastInsertId();

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

$_SESSION['factura_generada'] = $nombre_archivo;
unset($_SESSION['carrito']);
unset($_SESSION['datos_cliente']);

header('Location: gracias.php?factura=' . urlencode($nombre_archivo));
exit;
