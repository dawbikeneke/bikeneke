<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../../config/db.php';

    $data = [
        'nombre' => trim($_POST['nombre']),
        'id_tipo_alojamiento' => intval($_POST['id_tipo_alojamiento']),
        'direccion' => trim($_POST['direccion']),
        'codigo_postal' => trim($_POST['codigo_postal']),
        'localidad' => trim($_POST['localidad']),
        'provincia' => trim($_POST['provincia']),
        'pais' => trim($_POST['pais']),
    ];

    // Verifica duplicado por nombre + dirección
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM alojamientos WHERE nombre = :nombre AND direccion = :direccion");
    $stmt->execute(['nombre' => $data['nombre'], 'direccion' => $data['direccion']]);

    if ($stmt->fetchColumn() > 0) {
        header('Location: alojamientos.php?error=existe');
        exit;
    }

    // Insertar
    $stmt = $pdo->prepare("INSERT INTO alojamientos 
        (nombre, id_tipo_alojamiento, direccion, codigo_postal, localidad, provincia, pais)
        VALUES 
        (:nombre, :id_tipo_alojamiento, :direccion, :codigo_postal, :localidad, :provincia, :pais)");

    $stmt->execute($data);
}

header('Location: alojamientos.php');
exit;
