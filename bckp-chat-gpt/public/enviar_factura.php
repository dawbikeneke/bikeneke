<?php
//session_start();
require '../config/db.php';
require_once '../includes/factura_pdf.php';

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

$datos_factura = [
    'cliente' => "$nombre $apellidos",
    'dni' => $dni,
    'telefono' => $telefono,
    'hora_entrega' => $hora_entrega,
    'hora_recogida' => $hora_recogida,
    'alojamiento_id' => $alojamiento_id,
    'lineas' => $lineas,
    'servicios' => $servicios,
    'accesorios' => $accesorios,
    'subtotal' => $total,
    'iva' => $iva,
    'total' => $total_con_iva
];

$nombre_archivo = 'factura_' . date('Ymd_His') . '_' . rand(1000, 9999) . '.pdf';
$ruta_archivo = __DIR__ . '/../private/facturas/' . $nombre_archivo;

generarFacturaPDF($datos_factura, $ruta_archivo);

// Enviar por correo si se indicó email
if (!empty($email)) {
    $_POST['factura'] = $nombre_archivo;
    $_POST['nombre'] = $nombre;
    $_POST['email'] = $email;
    include 'enviar_factura.php';
}

unset($_SESSION['carrito']);
header('Location: gracias.php?factura=' . urlencode($nombre_archivo));
exit;
