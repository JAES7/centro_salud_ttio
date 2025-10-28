<?php
// views/ticket/boleta_termica.php (CON FORZADO DE ERRORES Y SINTAXIS REVISADA)

// --- ¡NUEVO! Forzar mostrar errores ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- FIN FORZADO DE ERRORES ---

// Verificar si $data existe y tiene los datos esperados
if (!isset($data) || !isset($data['datos']) || !isset($data['datos']['atencion'])) {
    die("Error crítico: Faltan datos necesarios para generar la boleta.");
}

$atencion = $data['datos']['atencion'];
$servicios = $data['datos']['servicios'] ?? []; // Usar array vacío si no hay servicios
$codigoBarrasBase64 = $data['codigo_barras_base64'] ?? null;
$errorCodigoBarras = $data['error_codigo_barras'] ?? null;

// Formateamos la fecha y hora de emisión (HH:MM)
try {
    // Es crucial que $atencion->fecha_hora exista y sea válido
    if (empty($atencion->fecha_hora)) throw new Exception('fecha_hora vacía o nula');
    $fechaHora = new DateTime($atencion->fecha_hora, new DateTimeZone('America/Lima'));
} catch (Exception $e) {
    error_log("Error creando DateTime para fecha_hora: " . $e->getMessage() . " - Valor recibido: " . ($atencion->fecha_hora ?? 'N/A'));
    $fechaHora = new DateTime("now", new DateTimeZone('America/Lima')); // Fallback
}
$fechaEmision = $fechaHora->format('Y-m-d');
$horaEmision = $fechaHora->format('H:i');

// Formateamos la hora del turno (HH:MM)
$horaTurnoEstimadaTexto = "N/A";
if (!empty($atencion->hora_turno_estimada)) { // Comprobar si no está vacío
    try {
        $horaTurnoObj = new DateTime($atencion->hora_turno_estimada); // No necesita zona horaria, ya es solo HH:MM:SS
        $horaTurnoEstimadaTexto = $horaTurnoObj->format('H:i');
    } catch (Exception $e) {
        error_log("Error formateando hora_turno_estimada: " . $atencion->hora_turno_estimada . " - Error: " . $e->getMessage());
    }
} elseif (isset($atencion->especialidad_nombre)) {
     $esp = strtolower(trim($atencion->especialidad_nombre));
     $esp = str_replace(['á','é','í','ó','ú','ñ'],['a','e','i','o','u','n'],$esp);
     if(in_array($esp, ['topico', 'laboratorio'])){
          $horaTurnoEstimadaTexto = "Sin hora estimada";
     }
}

// Número de boleta
$numeroBoleta = 'B001-' . str_pad($atencion->id_atencion ?? '0', 8, '0', STR_PAD_LEFT);

// Datos del Centro de Salud
$nombreCentro = SITE_NAME ?? 'Centro de Salud'; // Usar fallback
$rucCentro = "20171092345";
$lugarCentro = "Av. 28 de Julio s/n";
$telefonoCentro = "239673";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Boleta'; ?></title> <!-- Usar fallback -->
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 10pt; line-height: 1.3; width: 280px; margin: 0 auto; padding: 10px; background-color: #fff; color: #000; }
        .header, .footer { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 12pt; }
        .item-list { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; }
        .item-list th, .item-list td { padding: 2px 0; vertical-align: top; }
        .item-list th { text-align: left; border-bottom: 1px dashed #000; }
        .item-list .qty, .item-list .price, .item-list .subtotal { text-align: right; white-space: nowrap; }
        .item-list .desc { text-align: left; }
        .totals { margin-top: 10px; text-align: right; }
        .totals span { display: inline-block; min-width: 80px; }
        .barcode-container { text-align: center; margin-top: 15px; margin-bottom: 15px; }
        .barcode-container img { max-width: 100%; height: 50px; }
        hr { border: none; border-top: 1px dashed #000; margin: 10px 0; }
        @media print {
            body * { visibility: hidden; }
            .printable-ticket, .printable-ticket * { visibility: visible; }
            .printable-ticket { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
        }
    </style>
</head>
<body onload="window.print();">

<div class="printable-ticket">
    <div class="header">
        <h1><?php echo htmlspecialchars($nombreCentro); ?></h1>
        <?php if ($rucCentro) : ?> RUC: <?php echo htmlspecialchars($rucCentro); ?><br> <?php endif; ?>
        <?php if ($lugarCentro) : ?> <?php echo htmlspecialchars($lugarCentro); ?><br> <?php endif; ?>
        <?php if ($telefonoCentro) : ?> Teléfono: <?php echo htmlspecialchars($telefonoCentro); ?><br> <?php endif; ?>
    </div>

    <hr>
    Fecha: <?php echo htmlspecialchars($fechaEmision); ?> Hora: <?php echo htmlspecialchars($horaEmision); ?><br>
    Boleta Electrónica: <?php echo htmlspecialchars($numeroBoleta); ?><br>
    Cajero: <?php echo htmlspecialchars($atencion->cajero_nombre ?? 'N/A'); ?><br>
    Turno: <?php echo htmlspecialchars($atencion->turno ?? 'N/A'); ?><br>
    Nro. Ticket Atención: <?php echo htmlspecialchars($atencion->numero_ticket_diario ?? 'N/A'); ?><br>
    Hora Turno Estimada: <?php echo htmlspecialchars($horaTurnoEstimadaTexto); ?><br>
    <hr>
    DNI: <?php echo htmlspecialchars($atencion->paciente_dni ?? 'N/A'); ?><br>
    Cliente: <?php echo htmlspecialchars($atencion->paciente_nombre ?? 'N/A'); ?><br>
    Profesional: <?php echo htmlspecialchars($atencion->profesional_nombre ?? 'N/A'); ?><br>
    Especialidad: <?php echo htmlspecialchars($atencion->especialidad_nombre ?? 'N/A'); ?><br>
    Consultorio: <?php echo htmlspecialchars($atencion->especialidad_consultorio ?? 'N/A'); ?><br>
    <hr>

    <table class="item-list">
        <thead>
            <tr>
                <th class="qty">Cant</th>
                <th class="desc">Descripción</th>
                <th class="price">P.Unit</th>
                <th class="subtotal">Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($servicios)) : ?>
                <?php foreach ($servicios as $servicio) : ?>
                    <tr>
                        <td class="qty"><?php echo number_format($servicio->cantidad ?? 0, 0); ?></td>
                        <td class="desc"><?php echo htmlspecialchars($servicio->descripcion ?? 'N/A'); ?></td>
                        <td class="price"><?php echo number_format($servicio->precio_unitario ?? 0, 2); ?></td>
                        <td class="subtotal"><?php echo number_format($servicio->subtotal ?? 0, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="4">(No se encontraron servicios)</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr>
    <div class="totals">
        <span>TOTAL: S/</span><strong><?php echo number_format($atencion->total ?? 0, 2); ?></strong>
    </div>

    <div class="barcode-container">
        <?php if ($codigoBarrasBase64) : ?>
            <img src="<?php echo $codigoBarrasBase64; ?>" alt="Código de Barras">
        <?php elseif ($errorCodigoBarras) : ?>
            <p style="font-size: 8pt; color: red;">(Error Barcode: <?php echo htmlspecialchars($errorCodigoBarras); ?>)</p>
        <?php else : ?>
            <p>(No se pudo generar código de barras)</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        Gracias por su visita.
    </div>
</div>

</body>
</html>