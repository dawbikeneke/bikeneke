<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nueva_categoria'])) {
    require '../../config/db.php';

    $categoria = trim($_POST['nueva_categoria']);
    $suplemento = floatval($_POST['suplemento']);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM categorias WHERE categoria = :categoria");
    $stmt->execute(['categoria' => $categoria]);

    if ($stmt->fetchColumn() > 0) {
        header('Location: categorias.php?error=existe');
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO categorias (categoria, suplemento) VALUES (:categoria, :suplemento)");
    $stmt->execute([
        'categoria' => $categoria,
        'suplemento' => $suplemento
    ]);
}

header('Location: categorias.php');
exit;
