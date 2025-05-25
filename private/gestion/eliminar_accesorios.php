// eliminar_accesorios.php
<?php
session_start();
require '../../config/db.php';

if (!empty($_POST['eliminar'])) {
    $ids = $_POST['eliminar'];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("DELETE FROM accesorios WHERE id_accesorio IN ($placeholders)");
    $stmt->execute($ids);
}

header('Location: accesorios.php?eliminado=ok');
exit;
