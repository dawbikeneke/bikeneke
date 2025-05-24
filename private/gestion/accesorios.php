<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

require '../../config/db.php';

// Obtener accesorios
$stmt = $pdo->query("SELECT * FROM accesorios ORDER BY nombre ASC");
$accesorios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Gestión de accesorios</h2>

    <!-- Formulario para añadir nuevo accesorio -->
    <form id="formAccesorio" method="post" action="procesar_accesorio.php" enctype="multipart/form-data">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre del accesorio" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="precio_hora" class="form-control" placeholder="Precio/hora" value="0">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="precio_dia" class="form-control" placeholder="Precio/día" value="0">
            </div>
            <div class="col-md-3">
                <input type="file" name="imagen" class="form-control" accept="image/*">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </form>

    <!-- Listado de accesorios existentes -->
    <form method="post" action="eliminar_accesorios.php" id="formEliminarAccesorios">
        <h4 class="mt-5">Accesorios disponibles:</h4>
        <ul class="list-group mb-3">
            <?php foreach ($accesorios as $acc): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <?php if ($acc['imagen']): ?>
                            <img src="../../public/img/accesorios/<?= htmlspecialchars($acc['imagen']) ?>" alt="img" width="40" height="40">
                        <?php endif; ?>
                        <div>
                            <strong><?= htmlspecialchars($acc['nombre']) ?></strong><br>
                            <small><?= number_format($acc['precio_hora'], 2) ?>€/h — <?= number_format($acc['precio_dia'], 2) ?>€/día</small>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="eliminar[]" value="<?= $acc['id_accesorio'] ?>" id="check-<?= $acc['id_accesorio'] ?>">
                        <label class="form-check-label" for="check-<?= $acc['id_accesorio'] ?>">Eliminar</label>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal">Eliminar seleccionados</button>
        </div>
    </form>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="confirmarEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de que deseas eliminar los accesorios seleccionados?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formEliminarAccesorios" class="btn btn-danger">Sí, eliminar</button>
          </div>
        </div>
      </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>