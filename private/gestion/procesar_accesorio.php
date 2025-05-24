
<?php
session_start();
require '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $precio_hora = floatval($_POST['precio_hora']);
    $precio_dia = floatval($_POST['precio_dia']);
    $imagen = null;

    // Manejar subida de imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaDestino = '../../public/img/accesorios/' . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = $nombreArchivo;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO accesorios (nombre, precio_hora, precio_dia, imagen) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $precio_hora, $precio_dia, $imagen]);
}

header('Location: accesorios.php');
exit;