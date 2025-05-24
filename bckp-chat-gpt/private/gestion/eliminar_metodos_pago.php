<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar']) && is_array($_POST['eliminar'])) {
    require '../../config/db.php';

    // Eliminar múltiples ids
    $ids = array_map('intval', $_POST['eliminar']);  // Seguridad extra
    $in = str_repeat('?,', count($ids) - 1) . '?';

    $stmt = $pdo->prepare("DELETE FROM tipo_pagos WHERE id_pago IN ($in)");
    $stmt->execute($ids);
}

header('Location: metodos_pago.php');
exit;
