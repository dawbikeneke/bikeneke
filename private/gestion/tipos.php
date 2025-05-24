<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

require '../../config/db.php';

$stmt = $pdo->query("SELECT id_tipo, tipo, precio_hora, precio_dia FROM tipos ORDER BY tipo ASC");
$tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Gestión de tipos de bicicleta</h2>

    <!-- Formulario para añadir nuevo tipo -->
    <form id="formTipo" method="post" action="procesar_tipo.php">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="nuevo_tipo" class="form-control" placeholder="Nombre del tipo" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="precio_hora" class="form-control" placeholder="€/hora" min="0" step="0.01" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="precio_dia" class="form-control" placeholder="€/día" min="0" step="0.01" required>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-success">Añadir tipo</button>
            </div>
        </div>
    </form>

    <!-- Formulario de eliminación -->
    <form id="formEliminarTipos" method="post" action="eliminar_tipos.php">
        <h4 class="mt-5">Tipos registrados:</h4>
        <ul class="list-group mb-3">
            <?php foreach ($tipos as $tipo): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <?php echo htmlspecialchars($tipo['tipo']); ?> —
                        <small><?php echo number_format($tipo['precio_hora'], 2); ?> €/h, <?php echo number_format($tipo['precio_dia'], 2); ?> €/día</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="eliminar[]" value="<?php echo $tipo['id_tipo']; ?>" id="check-<?php echo $tipo['id_tipo']; ?>">
                        <label class="form-check-label" for="check-<?php echo $tipo['id_tipo']; ?>">Eliminar</label>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal">
                Eliminar seleccionados
            </button>
        </div>
    </form>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="confirmarEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de que deseas eliminar los tipos seleccionados?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formEliminarTipos" class="btn btn-danger">Sí, eliminar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de éxito si se ha eliminado -->
    <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] === 'ok'): ?>
    <div class="modal fade show" id="eliminadoModal" tabindex="-1" aria-modal="true" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">Tipos eliminados</h5>
            <a href="tipos.php" class="btn-close"></a>
          </div>
          <div class="modal-body">
            Los tipos seleccionados se han eliminado correctamente.
          </div>
          <div class="modal-footer">
            <a href="tipos.php" class="btn btn-success">Cerrar</a>
          </div>
        </div>
      </div>
    </div>
    <script>
      document.body.classList.add('modal-open');
      document.body.style.paddingRight = '0';
    </script>
    <div class="modal-backdrop fade show"></div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

