<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

require '../../config/db.php';

$stmt = $pdo->query("SELECT id_pago, tipo_pago FROM tipo_pagos ORDER BY tipo_pago ASC");
$metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Gestión de métodos de pago</h2>

    <!-- Formulario de nuevo método -->
    <form id="formMetodoPago" method="post" action="procesar_metodo_pago.php">
        <div class="mb-3 d-flex align-items-center gap-2">
            <input type="text" name="nuevo_metodo" class="form-control" placeholder="Añadir nuevo método de pago..." required>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmarModal">Guardar</button>
        </div>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="confirmarModalLabel">Confirmar inserción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                ¿Estás seguro de que quieres añadir este nuevo método de pago?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Sí, añadir</button>
              </div>
            </div>
          </div>
        </div>
    </form>

    <!-- Modal de error -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'existe'): ?>
        <div class="modal fade show" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-modal="true" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <a href="metodos_pago.php" class="btn-close"></a>
            </div>
            <div class="modal-body">
                El método de pago que intentas añadir ya existe.
            </div>
            <div class="modal-footer">
                <a href="metodos_pago.php" class="btn btn-secondary">Cerrar</a>
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

    <!-- Listado de métodos existentes -->
    <form id="formEliminarMetodos" method="post" action="eliminar_metodos_pago.php">
        <h4 class="mt-5">Métodos configurados:</h4>
        <ul class="list-group mb-3">
            <?php foreach ($metodos as $metodo): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($metodo['tipo_pago']); ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="eliminar[]" value="<?php echo $metodo['id_pago']; ?>" id="check-<?php echo $metodo['id_pago']; ?>">
                        <label class="form-check-label" for="check-<?php echo $metodo['id_pago']; ?>">
                            Eliminar
                        </label>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal">Eliminar seleccionados</button>
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
            ¿Estás seguro de que deseas eliminar los métodos seleccionados?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formEliminarMetodos" class="btn btn-danger">Sí, eliminar</button>
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
            <h5 class="modal-title">Métodos eliminados</h5>
            <a href="metodos_pago.php" class="btn-close"></a>
          </div>
          <div class="modal-body">
            Los métodos seleccionados se han eliminado correctamente.
          </div>
          <div class="modal-footer">
            <a href="metodos_pago.php" class="btn btn-success">Cerrar</a>
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