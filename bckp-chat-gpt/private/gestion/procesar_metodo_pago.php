<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

//Este script ahora comprobará si el método ya existe antes de insertarlo. Si existe, redirige con un mensaje.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nuevo_metodo'])) {
    require '../../config/db.php';

    $tipo_pago = trim($_POST['nuevo_metodo']);

    if ($tipo_pago !== '') {
        // Verificar si ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tipo_pagos WHERE tipo_pago = :tipo_pago");
        $stmt->execute(['tipo_pago' => $tipo_pago]);

        if ($stmt->fetchColumn() > 0) {
            // Ya existe: redirigir con error
            header('Location: metodos_pago.php?error=existe');
            exit;
        }

        // Insertar si no existe
        $stmt = $pdo->prepare("INSERT INTO tipo_pagos (tipo_pago) VALUES (:tipo_pago)");
        $stmt->execute(['tipo_pago' => $tipo_pago]);
    }
}

header('Location: metodos_pago.php');
exit;