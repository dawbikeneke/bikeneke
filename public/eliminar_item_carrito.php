<?php
session_start();

// Verificamos que haya un índice válido enviado
if (isset($_POST['index']) && is_numeric($_POST['index'])) {
    $index = intval($_POST['index']);

    // Si el índice existe en el carrito, lo eliminamos
    if (isset($_SESSION['carrito'][$index])) {
        unset($_SESSION['carrito'][$index]);

        // Reindexamos el array para evitar saltos en los índices
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// Volver a la página de selección
header('Location: seleccionar_bicicleta.php');
exit;
