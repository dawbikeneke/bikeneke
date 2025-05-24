<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
session_start();
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['datos'])) {
    $datos = $_POST['datos'];

    // Validación básica
    if (empty($datos['email']) || empty($datos['password'])) {
        header('Location: ../index.php?error=Rellena todos los campos');
        exit;
    }

    $email = trim($datos['email']);
    $password = $datos['password'];

    // Buscar el usuario
    $sql = "SELECT * FROM administracion WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        // Login correcto
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre_usuario'],
            'email' => $usuario['email']
        ];
        header('Location: ../gestion/dashboard.php');
        exit;
    } else {
        // Usuario o contraseña incorrecta
        header('Location: ../index.php?error=Credenciales incorrectas');
        exit;
    }

/*
if ($usuario) {
    echo "<pre>";
    echo "Contraseña introducida: " . $password . "\n";
    echo "Hash de la BD: " . $usuario['password_hash'] . "\n";
    echo "</pre>";

    if (password_verify($password, $usuario['password_hash'])) {
        echo "Coincide la contraseña.";
    } else {
        echo "NO coincide la contraseña.";
    }
    exit;
}
    */

} else {
    // Acceso no permitido directamente
    header('Location: ../index.php');
    exit;
}
