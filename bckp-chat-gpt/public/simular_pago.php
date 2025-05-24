<?php
session_start();

if (empty($_SESSION['carrito']) || empty($_SESSION['datos_cliente'])) {
    header('Location: seleccionar_bicicleta.php');
    exit;
}

$carrito = $_SESSION['carrito'];
$datos = $_SESSION['datos_cliente'];
require_once '../config/db.php';

$accesorios_seleccionados = $datos['accesorios'] ?? [];
$lista_accesorios = [];

foreach ($accesorios_seleccionados as $id => $info) {
    if (!isset($info['seleccionado'])) continue;

    $modo = $info['modo'] ?? 'hora';
    $cantidad = intval($info['cantidad'] ?? 1);

    $stmt = $pdo->prepare("SELECT nombre FROM accesorios WHERE id_accesorio = ?");
    $stmt->execute([$id]);
    $acc = $stmt->fetch();

    if ($acc) {
        $lista_accesorios[] = [
            'nombre' => $acc['nombre'],
            'modo' => $modo,
            'cantidad' => $cantidad
        ];
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Simulación de pasarela de pago</h2>

    <h5>Resumen del pedido:</h5>
    <ul class="list-group mb-4">
        <?php foreach ($carrito as $item): ?>
            <li class="list-group-item">
                <?= $item['cantidad'] ?> x <strong><?= htmlspecialchars($item['tipo_nombre']) ?></strong> — <?= htmlspecialchars($item['categoria_nombre']) ?><br>
                <small>Duración: <?= $item['unidades_tiempo'] ?> <?= $item['duracion'] === 'hora' ? 'hora(s)' : 'día(s)' ?> | <?= number_format($item['precio_unitario'], 2) ?> €/ud</small>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="mb-4">
        <h6><strong>Datos del cliente:</strong></h6>
        <p><?= htmlspecialchars($datos['nombre']) . ' ' . htmlspecialchars($datos['apellidos']) ?><br>
        DNI: <?= htmlspecialchars($datos['dni']) ?><br>
        Tel: <?= htmlspecialchars($datos['telefono']) ?><br>
        Email: <?= htmlspecialchars($datos['email']) ?><br>
        Hora entrega: <?= htmlspecialchars($datos['hora_entrega']) ?><br>
        Hora recogida: <?= htmlspecialchars($datos['hora_recogida']) ?><br>
        Entrega: <?= htmlspecialchars($datos['entrega']) ?> — Recogida: <?= htmlspecialchars($datos['recogida']) ?>
        </p>
    </div>

    <?php if (!empty($lista_accesorios)): ?>
        <div class="mb-4">
            <h6><strong>Accesorios seleccionados:</strong></h6>
            <ul class="list-group">
                <?php foreach ($lista_accesorios as $acc): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($acc['nombre']) ?> —
                        <?= $acc['cantidad'] ?> ×
                        <?= $acc['modo'] === 'hora' ? 'hora(s)' : 'día(s)' ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="d-flex gap-3">
        <form action="procesar_pedido.php" method="post">
            <button type="submit" class="btn btn-success">Transacción correcta</button>
        </form>
        <form action="error_pago.php" method="get">
            <button type="submit" class="btn btn-danger">Transacción errónea</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
