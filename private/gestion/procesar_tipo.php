<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nuevo_tipo'])) {
    require '../../config/db.php';

    $tipo = trim($_POST['nuevo_tipo']);
    $precio_hora = floatval($_POST['precio_hora']);
    $precio_dia = floatval($_POST['precio_dia']);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tipos WHERE tipo = :tipo");
    $stmt->execute(['tipo' => $tipo]);

    if ($stmt->fetchColumn() > 0) {
        header('Location: tipos.php?error=existe');
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO tipos (tipo, precio_hora, precio_dia) VALUES (:tipo, :precio_hora, :precio_dia)");
    $stmt->execute([
        'tipo' => $tipo,
        'precio_hora' => $precio_hora,
        'precio_dia' => $precio_dia
    ]);
}

header('Location: tipos.php');
exit;
