<?php
session_start();
require '../config/db.php';

$tipos = $pdo->query("SELECT * FROM tipos ORDER BY tipo ASC")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY categoria ASC")->fetchAll(PDO::FETCH_ASSOC);

$carrito = $_SESSION['carrito'] ?? [];
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <?php if (!empty($carrito)): ?>
        <h2 class="mb-4">Mi garaje:</h2>
        <ul class="list-group mb-4">
            <?php foreach ($carrito as $i => $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row">
                    <div>
                        <?= $item['cantidad'] ?> x <strong><?= htmlspecialchars($item['tipo_nombre']) ?></strong> —
                        <em><?= htmlspecialchars($item['categoria_nombre']) ?></em><br>
                        <small>Duración: <?= $item['unidades_tiempo'] ?> <?= $item['duracion'] === 'hora' ? 'hora(s)' : 'día(s)' ?></small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary rounded-pill">
                            <?= number_format($item['precio_unitario'], 2) ?> €/ud — 
                            Total: <?= number_format($item['total_linea'], 2) ?> €
                        </span>
                        <form action="eliminar_item_carrito.php" method="post" class="m-0">
                            <input type="hidden" name="index" value="<?= $i ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="d-flex justify-content-end">
            <a href="verificar_pedido.php" class="btn btn-success">Realizar pedido</a>
        </div>
    <?php endif; ?>

    <h2 class="mb-4">Selección de bicicletas:</h2>

    <form id="formSeleccion" action="añadir_al_carrito.php" method="post">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="tipo_id" class="form-label">Destinatario:</label>
                <select name="tipo_id" id="tipo_id" class="form-select" required>
                    <option disabled selected>Elige:</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= $tipo['id_tipo'] ?>">
                            <?= htmlspecialchars($tipo['tipo']) ?> 
                            (<?= number_format($tipo['precio_hora'], 2) ?> €/h - <?= number_format($tipo['precio_dia'], 2) ?> €/día)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="categoria_id" class="form-label">Clase de bicicleta:</label>
                <select name="categoria_id" id="categoria_id" class="form-select" required>
                    <option disabled selected>Elige:</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>">
                            <?= htmlspecialchars($cat['categoria']) ?> (+<?= number_format($cat['suplemento'], 2) ?> €)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" value="1" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Alquiler por hora/s o día/s:</label>
                <div class="input-group">
                    <select name="duracion" class="form-select" required>
                        <option value="hora">Por horas</option>
                        <option value="dia">Por días</option>
                    </select>
                    <input type="number" name="unidades_tiempo" class="form-control" min="1" value="1" required>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmarModal">
                    Añadir al carrito
                </button>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="confirmarModalLabel">Confirmar selección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                ¿Estás seguro de que quieres añadir esta selección al carrito?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Sí, añadir</button>
              </div>
            </div>
          </div>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>