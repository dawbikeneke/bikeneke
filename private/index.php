<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: gestion/dashboard.php');
    exit;
}
?>

<?php include '../includes/header.php'; ?>


<body>

<div class="container mt-5">
    <h2 class="mb-3">BIKE ÑEKE / Identificación</h2>

    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form action="includes/procesa_login.php" method="post">
        <div class="mb-3">
            <label for="email">Correo electrónico</label>
            <input type="email" name="datos[email]" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password">Contraseña</label>
            <input type="password" name="datos[password]" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
