<?php
session_start();

// Validar que venga del formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: verificar_pedido.php');
    exit;
}

// Validar que exista carrito
if (empty($_SESSION['carrito'])) {
    header('Location: seleccionar_bicicleta.php');
    exit;
}

// Guardar los datos del formulario en sesión
$_SESSION['datos_cliente'] = $_POST;

// Redirigir a la simulación de pago
header('Location: simular_pago.php');
exit;
