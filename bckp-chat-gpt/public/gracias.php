<?php
$factura = isset($_GET['factura']) ? basename($_GET['factura']) : null;
$ruta_factura = '../private/facturas/' . $factura;
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">¡Gracias por tu pedido!</h2>

    <p>Hemos recibido tu solicitud de alquiler. Estamos preparando tus bicicletas para la entrega en el horario indicado.</p>

    <?php if ($factura && file_exists($ruta_factura)): ?>
        <div class="alert alert-success">
            Tu factura ha sido generada correctamente. Puedes descargarla aquí:
            <a href="<?= htmlspecialchars($ruta_factura) ?>" download class="btn btn-sm btn-outline-primary ms-2">Descargar factura</a>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Tu factura se ha generado, pero no se pudo encontrar el archivo en el sistema.
        </div>
    <?php endif; ?>

    <a href="../public/index.php" class="btn btn-primary mt-4">Volver a la página principal</a>
</div>

<?php include '../includes/footer.php'; ?>