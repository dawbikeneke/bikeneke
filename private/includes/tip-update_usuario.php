<?php
require_once __DIR__ . '/../../config/db.php';

$email = 'gestion@bikeneke.com';
$nombre = 'Gestor';
$password = 'Morato123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO administracion (email, nombre_usuario, password_hash)
            VALUES (:email, :nombre_usuario, :password_hash)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':nombre_usuario' => $nombre,
        ':password_hash' => $hash
    ]);
    echo "✅ Usuario insertado correctamente.";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
