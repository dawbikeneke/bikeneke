<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

require '../../config/db.php';

$stmt = $pdo->query("SELECT id_categoria, categoria, suplemento FROM categorias ORDER BY categoria ASC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Gestión de categorías de bicicleta</h2>

    <!-- Formulario para añadir nueva categoría -->
    <form id="formCategoria" method="post" action="procesar_categoria.php">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="nueva_categoria" class="form-control" placeholder="Nombre de la categoría" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="suplemento" class="form-control" placeholder="Suplemento €" min="0" step="0.01" required>
            </div>
            <div class="col-md-3 d-flex justify-content-end">
                <button type="submit" class="btn btn-success">Añadir categoría</button>
            </div>
        </div>
    </form>

    <!-- Formulario para eliminar categorías -->
    <form id="formEliminarCategorias" method="post" action="eliminar_categorias.php">
        <h4 class="mt-5">Categorías registradas:</h4>
        <ul class="list-group mb-3">
            <?php foreach ($categorias as $cat): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <?php echo htmlspecialchars($cat['categoria']); ?> —
                        <small>Suplemento: <?php echo number_format($cat['suplemento'], 2); ?> €</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="eliminar[]" value="<?php echo $cat['id_categoria']; ?>" id="check-cat-<?php echo $cat['id_categoria']; ?>">
                        <label class="form-check-label" for="check-cat-<?php echo $cat['id_categoria']; ?>">Eliminar</label>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal">
                Eliminar seleccionadas
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
            ¿Estás seguro de que deseas eliminar las categorías seleccionadas?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formEliminarCategorias" class="btn btn-danger">Sí, eliminar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de éxito por eliminación -->
    <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] === 'ok'): ?>
    <div class="modal fade show" id="eliminadoModal" tabindex="-1" aria-modal="true" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">Categorías eliminadas</h5>
            <a href="categorias.php" class="btn-close"></a>
          </div>
          <div class="modal-body">
            Las categorías seleccionadas se han eliminado correctamente.
          </div>
          <div class="modal-footer">
            <a href="categorias.php" class="btn btn-success">Cerrar</a>
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
