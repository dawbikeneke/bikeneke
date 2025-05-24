<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}

require '../../config/db.php';

// Obtener tipos de alojamiento
$stmtTipos = $pdo->query("SELECT id_tipo_alojamiento, descripcion FROM tipo_alojamiento ORDER BY descripcion ASC");
$tipos = $stmtTipos->fetchAll(PDO::FETCH_ASSOC);

// Obtener alojamientos existentes
$stmtAlojamientos = $pdo->query("SELECT a.*, t.descripcion AS tipo 
                                 FROM alojamientos a 
                                 JOIN tipo_alojamiento t ON a.id_tipo_alojamiento = t.id_tipo_alojamiento 
                                 ORDER BY a.nombre ASC");
$alojamientos = $stmtAlojamientos->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Gestión de alojamientos colaboradores</h2>

    <!-- Formulario para añadir nuevo alojamiento -->
    <form id="formAlojamiento" method="post" action="procesar_alojamiento.php">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre del alojamiento" required>
            </div>
            <div class="col-md-6">
                <select name="id_tipo_alojamiento" class="form-select" required>
                    <option value="" disabled selected>Tipo de alojamiento</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?php echo $tipo['id_tipo_alojamiento']; ?>"><?php echo htmlspecialchars($tipo['descripcion']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="direccion" class="form-control" placeholder="Dirección" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="codigo_postal" class="form-control" placeholder="Código postal" value="17300">
            </div>
            <div class="col-md-3">
                <input type="text" name="localidad" class="form-control" placeholder="Localidad" value="Blanes">
            </div>
            <div class="col-md-6">
                <input type="text" name="provincia" class="form-control" placeholder="Provincia" value="Girona">
            </div>
            <div class="col-md-6">
                <input type="text" name="pais" class="form-control" placeholder="País" value="España">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmarModal">Guardar</button>
            </div>
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
                ¿Confirmas que deseas añadir este nuevo alojamiento?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Sí, añadir</button>
              </div>
            </div>
          </div>
        </div>
    </form>

    <!-- Listado + formulario de eliminación -->
    <form id="formEliminarAlojamientos" method="post" action="eliminar_alojamientos.php">
        <h4 class="mt-5">Alojamientos registrados:</h4>
        <ul class="list-group mb-3">
            <?php foreach ($alojamientos as $aloj): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row">
                    <div>
                        <strong><?php echo htmlspecialchars($aloj['nombre']); ?></strong><br>
                        <small><?php echo htmlspecialchars($aloj['direccion']) . ', ' . htmlspecialchars($aloj['codigo_postal']) . ' ' . htmlspecialchars($aloj['localidad']); ?></small><br>
                        <small><?php echo htmlspecialchars($aloj['provincia']) . ', ' . htmlspecialchars($aloj['pais']); ?> — <em><?php echo htmlspecialchars($aloj['tipo']); ?></em></small>
                    </div>
                    <div class="form-check mt-2 mt-md-0">
                        <input class="form-check-input" type="checkbox" name="eliminar[]" value="<?php echo $aloj['id_alojamiento']; ?>" id="check-<?php echo $aloj['id_alojamiento']; ?>">
                        <label class="form-check-label" for="check-<?php echo $aloj['id_alojamiento']; ?>">
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
            ¿Estás seguro de que deseas eliminar los alojamientos seleccionados?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formEliminarAlojamientos" class="btn btn-danger">Sí, eliminar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de éxito -->
    <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] === 'ok'): ?>
    <div class="modal fade show" id="eliminadoModal" tabindex="-1" aria-modal="true" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">Alojamientos eliminados</h5>
            <a href="alojamientos.php" class="btn-close"></a>
          </div>
          <div class="modal-body">
            Los alojamientos seleccionados se han eliminado correctamente.
          </div>
          <div class="modal-footer">
            <a href="alojamientos.php" class="btn btn-success">Cerrar</a>
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