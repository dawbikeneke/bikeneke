<?php
session_start();
require '../config/db.php';

if (empty($_SESSION['carrito'])) {
    header('Location: seleccionar_bicicleta.php');
    exit;
}

// Obtener alojamientos
$stmt = $pdo->query("SELECT id_alojamiento, nombre FROM alojamientos ORDER BY nombre ASC");
$alojamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener accesorios
$stmt2 = $pdo->query("SELECT * FROM accesorios ORDER BY nombre ASC");
$accesorios = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$carrito = $_SESSION['carrito'];

// Calcular hora sugerida de entrega (ahora +1h) y recogida (según duración mayor)
$ahora = new DateTime('+1 hour');
$hora_entrega = $ahora->format('H:i');

$max_duracion_minutos = 0;
foreach ($carrito as $item) {
    $unidad = $item['duracion'] === 'hora' ? 60 : 1440;
    $duracion_total = $unidad * intval($item['unidades_tiempo']);
    if ($duracion_total > $max_duracion_minutos) {
        $max_duracion_minutos = $duracion_total;
    }
}

$hora_recogida = (clone $ahora)->modify("+{$max_duracion_minutos} minutes")->format('H:i');

// Si viene de pago_error.php y hay datos guardados
$datos_previos = $_SESSION['datos_cliente'] ?? [];
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Verificar pedido</h2>

    <h4>Bicicletas seleccionadas</h4>
    <ul class="list-group mb-4">
        <?php foreach ($carrito as $item): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row">
                <div>
                    <?= $item['cantidad'] ?> x <strong><?= htmlspecialchars($item['tipo_nombre']) ?></strong> —
                    <em><?= htmlspecialchars($item['categoria_nombre']) ?></em><br>
                    <small>Duración: <?= $item['unidades_tiempo'] ?> <?= $item['duracion'] === 'hora' ? 'hora(s)' : 'día(s)' ?></small>
                </div>
                <div>
                    <span class="badge bg-primary rounded-pill">
                        <?= number_format($item['precio_unitario'], 2) ?> €/ud — 
                        Total: <?= number_format($item['total_linea'], 2) ?> €
                    </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <form action="guardar_datos_y_redirigir.php" method="post">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required value="<?= $datos_previos['nombre'] ?? '' ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" required value="<?= $datos_previos['apellidos'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" required value="<?= $datos_previos['dni'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" required value="<?= $datos_previos['telefono'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="<?= $datos_previos['email'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Dirección/Alojamiento</label>
                <select name="alojamiento_id" class="form-select" required>
                    <option disabled selected>Selecciona alojamiento</option>
                    <?php foreach ($alojamientos as $aloj): ?>
                        <option value="<?= $aloj['id_alojamiento'] ?>" <?= (isset($datos_previos['alojamiento_id']) && $datos_previos['alojamiento_id'] == $aloj['id_alojamiento']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($aloj['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Hora de entrega</label>
                <input type="time" name="hora_entrega" class="form-control" required value="<?= $datos_previos['hora_entrega'] ?? $hora_entrega ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Hora de recogida</label>
                <input type="time" name="hora_recogida" class="form-control" required value="<?= $datos_previos['hora_recogida'] ?? $hora_recogida ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Entrega del pedido</label><br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="entrega" value="alojamiento" id="entregaAlojamiento" <?= (!isset($datos_previos['entrega']) || $datos_previos['entrega'] === 'alojamiento') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="entregaAlojamiento">Entregar en el alojamiento</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="entrega" value="tienda" id="entregaTienda" <?= (isset($datos_previos['entrega']) && $datos_previos['entrega'] === 'tienda') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="entregaTienda">Recoger en tienda</label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Recogida del pedido</label><br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recogida" value="alojamiento" id="recogidaAlojamiento" <?= (!isset($datos_previos['recogida']) || $datos_previos['recogida'] === 'alojamiento') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="recogidaAlojamiento">Recoger en el alojamiento</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recogida" value="tienda" id="recogidaTienda" <?= (isset($datos_previos['recogida']) && $datos_previos['recogida'] === 'tienda') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="recogidaTienda">Dejar en tienda</label>
                </div>
            </div>

            <?php if (!empty($accesorios)): ?>
                <div class="col-12 mt-4">
                    <h4>Accesorios opcionales</h4>
                    <div class="row">
                        <?php foreach ($accesorios as $acc): ?>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="accesorios[<?= $acc['id_accesorio'] ?>][seleccionado]" id="acc<?= $acc['id_accesorio'] ?>">
                                    <label class="form-check-label" for="acc<?= $acc['id_accesorio'] ?>">
                                        <?= htmlspecialchars($acc['nombre']) ?> 
                                        <small>(<?= number_format($acc['precio_hora'], 2) ?> €/h - <?= number_format($acc['precio_dia'], 2) ?> €/día)</small>
                                    </label>
                                </div>
                                <div class="ms-4 mb-3">
                                    <select name="accesorios[<?= $acc['id_accesorio'] ?>][modo]" class="form-select form-select-sm w-auto d-inline">
                                        <option value="hora">Por horas</option>
                                        <option value="dia">Por días</option>
                                    </select>
                                    <input type="number" name="accesorios[<?= $acc['id_accesorio'] ?>][cantidad]" class="form-control form-control-sm w-auto d-inline" min="1" value="1">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-12 d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">Confirmar y pagar</button>
            </div>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>