<?php
session_start();
require '../config/db.php';

// Recogida de datos POST
$tipo_id = intval($_POST['tipo_id']);
$categoria_id = intval($_POST['categoria_id']);
$cantidad = intval($_POST['cantidad']);
$duracion = $_POST['duracion']; // 'hora' o 'dia'
$unidades = intval($_POST['unidades_tiempo']);

// Datos de tipo y categoría
$stmtTipo = $pdo->prepare("SELECT tipo, precio_hora, precio_dia FROM tipos WHERE id_tipo = ?");
$stmtTipo->execute([$tipo_id]);
$tipo = $stmtTipo->fetch();

$stmtCat = $pdo->prepare("SELECT categoria, suplemento FROM categorias WHERE id_categoria = ?");
$stmtCat->execute([$categoria_id]);
$categoria = $stmtCat->fetch();

// Cálculo del precio unitario
$precio_base = $duracion === 'hora' ? $tipo['precio_hora'] : $tipo['precio_dia'];
$precio_unitario = ($precio_base + $categoria['suplemento']) * $unidades;
$total_linea = $precio_unitario * $cantidad;

// Guardar en sesión
$_SESSION['carrito'][] = [
    'tipo_id' => $tipo_id,
    'tipo_nombre' => $tipo['tipo'],
    'categoria_id' => $categoria_id,
    'categoria_nombre' => $categoria['categoria'],
    'precio_unitario' => $precio_unitario,
    'cantidad' => $cantidad,
    'duracion' => $duracion,
    'unidades_tiempo' => $unidades,
    'total_linea' => $total_linea
];

header('Location: seleccionar_bicicleta.php');
exit;
