<?php
session_start();
require '../config/db.php';

// Validación mínima
if (!isset($_POST['index']) || !is_numeric($_POST['index'])) {
    header('Location: seleccionar_bicicleta.php');
    exit;
}

$index = intval($_POST['index']);
$carrito = $_SESSION['carrito'] ?? [];

if (!isset($carrito[$index])) {
    header('Location: seleccionar_bicicleta.php');
    exit;
}

// Datos que se pueden modificar
$cantidad = max(1, intval($_POST['cantidad']));
$duracion = ($_POST['duracion'] === 'dia') ? 'dia' : 'hora';
$unidades = max(1, intval($_POST['unidades_tiempo']));

// Reobtener precios actuales desde BD
$tipo_id = $carrito[$index]['tipo_id'];
$categoria_id = $carrito[$index]['categoria_id'];

$stmtTipo = $pdo->prepare("SELECT precio_hora, precio_dia FROM tipos WHERE id_tipo = ?");
$stmtTipo->execute([$tipo_id]);
$tipo = $stmtTipo->fetch();

$stmtCat = $pdo->prepare("SELECT suplemento FROM categorias WHERE id_categoria = ?");
$stmtCat->execute([$categoria_id]);
$categoria = $stmtCat->fetch();

$precio_base = $duracion === 'hora' ? $tipo['precio_hora'] : $tipo['precio_dia'];
$precio_unitario = ($precio_base + $categoria['suplemento']) * $unidades;
$total_linea = $precio_unitario * $cantidad;

// Actualizar línea
$_SESSION['carrito'][$index]['cantidad'] = $cantidad;
$_SESSION['carrito'][$index]['duracion'] = $duracion;
$_SESSION['carrito'][$index]['unidades_tiempo'] = $unidades;
$_SESSION['carrito'][$index]['precio_unitario'] = $precio_unitario;
$_SESSION['carrito'][$index]['total_linea'] = $total_linea;

// Redirigir de vuelta
header('Location: seleccionar_bicicleta.php');
exit;
