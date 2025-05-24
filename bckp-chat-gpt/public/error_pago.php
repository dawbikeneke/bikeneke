<?php
session_start();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5 text-center">
    <h2 class="text-danger">Transacción fallida</h2>
    <p>Ha ocurrido un error al procesar el pago. Por favor, inténtalo de nuevo.</p>

    <div class="mt-4 d-flex justify-content-center gap-3">
        <a href="simular_pago.php" class="btn btn-primary">Volver a intentar</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>