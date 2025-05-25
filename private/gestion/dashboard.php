<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container mt-5">
    <h1>Bienvenido</h1>
    <p>Hola <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>, estás dentro del área de administración.</p>
</div>

<div class="b-example-divider"></div>

<div class="container px-4 py-5" id="hanging-icons"> <h2 class="pb-2 border-bottom">Panel de gestión</h2> <div class="row g-4 py-5 row-cols-1 row-cols-lg-3"> <div class="col d-flex align-items-start"> <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3"> <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#toggles2"></use></svg> </div> <div> <h3 class="fs-2 text-body-emphasis">Descargar facturas</h3> <p>Acceso a facturas de alquileres de bicicletas.</p> <a href="#" class="btn btn-primary">
Facturas
</a> </div> </div> <div class="col d-flex align-items-start"> <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3"> <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#cpu-fill"></use></svg> </div> <div> <h3 class="fs-2 text-body-emphasis">Establecimientos Colaboradores</h3> <p>Gestionar las direcciones de establiciemientos colaboradores.</p> <a href="alojamientos.php" class="btn btn-primary">
Establecimientos
</a> </div> </div> <div class="col d-flex align-items-start"> <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3"> <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#tools"></use></svg> </div> <div> <h3 class="fs-2 text-body-emphasis">Métodos de pago</h3> <p>Gestionar métodos de pago aceptados.</p> <a href="metodos_pago.php" class="btn btn-primary">
Configuración de pago
</a> </div> </div> <div class="col d-flex align-items-start"> <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3"> <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#toggles2"></use></svg> </div> <div> <h3 class="fs-2 text-body-emphasis">Categorías</h3> <p>Gestión de categorías de bicicletas.</p> <a href="categorias.php" class="btn btn-primary">
Categorías
</a> </div> </div> <div class="col d-flex align-items-start"> <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3"> <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#cpu-fill"></use></svg> </div> <div> <h3 class="fs-2 text-body-emphasis">Usuarios/Tipos de bicicletas</h3> <p>Gestionar usuarios/tipo para asignar bicicleta adecuada.</p> <a href="tipos.php" class="btn btn-primary">
Tipo de usuario
</a> </div> </div> <div class="col d-flex align-items-start"> <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3"> <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#cpu-fill"></use></svg> </div> <div> <h3 class="fs-2 text-body-emphasis">Accesorios para bicicletas</h3> <p>Permite gestionar accesorios diversos para que una experiencia más completa.</p> <a href="accesorios.php" class="btn btn-primary">
Accesorios
</a> </div> </div> </div> </div>

<div class="b-example-divider"></div>

<?php include '../includes/footer.php'; ?>