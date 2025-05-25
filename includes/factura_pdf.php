<?php
require_once '../includes/libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

function generarFacturaPDF(array $datos, string $ruta_archivo): void {
    $dompdf = new Dompdf();

    ob_start();
    ?>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: sans-serif; font-size: 14px; }
            h2 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
            th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
            th { background: #f4f4f4; }
        </style>
    </head>
    <body>
        <h2>Factura: </h2><?= $datos['numero_factura'] ?>
        

        <p>
            <strong>Emitido por:</strong><br>
            Bicicletas Bike Ñeke<br>
            Plaça Badajoz, 2, 17300 Blanes, Girona. España.<br>
            +34 655000000<br>
            delete@standardinet.com
        </p>

        <p>
            <strong>Cliente:</strong> <?= htmlspecialchars($datos['cliente']) ?><br>
            <strong>DNI:</strong> <?= htmlspecialchars($datos['dni']) ?><br>
            <strong>Teléfono:</strong> <?= htmlspecialchars($datos['telefono']) ?><br>
            <strong>Entrega:</strong> <?= htmlspecialchars($datos['hora_entrega']) ?> — 
            <strong>Recogida:</strong> <?= htmlspecialchars($datos['hora_recogida']) ?><br>
            <?php if (!empty($datos['alojamiento_direccion'])): ?>
                <strong>Alojamiento:</strong> <?= htmlspecialchars($datos['alojamiento_direccion']) ?>
            <?php endif; ?>
        </p>

        <h3>Alquiler de bicicletas</h3>
        <table>
            <thead>
                <tr><th>Tipo</th><th>Categoría</th><th>Duración</th><th>Cant.</th><th>Precio ud.</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($datos['lineas'] as $linea): ?>
                    <tr>
                        <td><?= htmlspecialchars($linea['tipo']) ?></td>
                        <td><?= htmlspecialchars($linea['categoria']) ?></td>
                        <td><?= $linea['unidades_tiempo'] . ' ' . ($linea['duracion'] === 'hora' ? 'hora(s)' : 'día(s)') ?></td>
                        <td><?= $linea['cantidad'] ?></td>
                        <td><?= number_format($linea['precio_unitario'], 2) ?> €</td>
                        <td><?= number_format($linea['subtotal'], 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!empty($datos['accesorios'])): ?>
            <h3>Accesorios</h3>
            <table>
                <thead>
                    <tr><th>Accesorio</th><th>Modo</th><th>Cant.</th><th>Precio ud.</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['accesorios'] as $acc): ?>
                        <tr>
                            <td><?= htmlspecialchars($acc['nombre']) ?></td>
                            <td><?= $acc['modo'] === 'hora' ? 'Hora(s)' : 'Día(s)' ?></td>
                            <td><?= $acc['cantidad'] ?></td>
                            <td><?= number_format($acc['precio_unitario'], 2) ?> €</td>
                            <td><?= number_format($acc['subtotal'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h3>Servicios adicionales</h3>
        <table>
            <thead><tr><th>Servicio</th><th>Precio</th></tr></thead>
            <tbody>
                <?php foreach ($datos['servicios'] as $serv): ?>
                    <tr>
                        <td><?= htmlspecialchars($serv['nombre']) ?></td>
                        <td><?= number_format($serv['precio'], 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Resumen</h3>
        <p>
            Subtotal: <?= number_format($datos['subtotal'], 2) ?> €<br>
            IVA (21%): <?= number_format($datos['iva'], 2) ?> €<br>
            <strong>Total: <?= number_format($datos['total'], 2) ?> €</strong>
        </p>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    file_put_contents($ruta_archivo, $dompdf->output());
}
