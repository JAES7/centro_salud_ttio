<?php
// views/pacientes/reporte_pdf.php (VISTA PARA IMPRIMIR)

$paciente = $paciente ?? null;
$atenciones_historicas = $atenciones_historicas ?? [];

if (!$paciente) { die("Error: Paciente no especificado."); }

// Configuración general
$nombreCentro = SITE_NAME ?? 'Centro de Salud Ttio';
$fechaReporte = date('Y-m-d H:i');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Historial Médico'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 16pt; margin: 0; }
        .paciente-info { margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; border-radius: 5px; }
        .atencion-card { border: 1px solid #000; margin-bottom: 20px; break-inside: avoid; }
        .atencion-card h5 { background-color: #f0f0f0; padding: 5px; margin: 0; border-bottom: 1px solid #000; }
        .triaje-data { background-color: #f8f9fa; padding: 10px; margin-top: 10px; border-top: 1px dashed #ccc; }
        .triaje-data p { margin: 0; line-height: 1.5; }
        .servicio-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .servicio-table th, .servicio-table td { border: 1px solid #ccc; padding: 5px; text-align: left; font-size: 9pt; }
        .servicio-table th { background-color: #e9ecef; }
        
        /* Media para impresión (PDF) */
        @media print {
            body { font-size: 10pt; }
            .header { border-bottom: 1px solid #000; }
            .no-print { display: none; }
            .atencion-card { page-break-inside: avoid; }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir / Guardar PDF</button>
        <a href="<?php echo APP_URL; ?>/paciente" class="btn btn-secondary">Volver</a>
    </div>

    <div class="header">
        <h1>HISTORIAL DE ATENCIONES</h1>
        <h4><?php echo htmlspecialchars($nombreCentro); ?></h4>
        <small>Reporte generado el: <?php echo $fechaReporte; ?></small>
    </div>

    <div class="paciente-info">
        <h5>Datos del Paciente</h5>
        <p><strong>DNI:</strong> <?php echo htmlspecialchars($paciente->dni); ?></p>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($paciente->nombre_completo); ?></p>
        <p><strong>Primera Atención:</strong> <?php echo count($atenciones_historicas) > 0 ? date('Y-m-d', strtotime($atenciones_historicas[count($atenciones_historicas) - 1]->fecha_hora)) : 'N/A'; ?></p>
    </div>

    <?php if (empty($atenciones_historicas)) : ?>
        <div class="alert alert-info">Este paciente no tiene registros de atención en el sistema.</div>
    <?php else : ?>
        
        <?php foreach ($atenciones_historicas as $atencion) : ?>
            <div class="atencion-card">
                <h5>Cita #<?php echo $atencion->id_atencion; ?> (Ticket Nro: <?php echo $atencion->numero_ticket_diario; ?>)</h5>
                <div class="p-3">
                    <p><strong>Fecha/Hora Cita:</strong> <?php echo date('Y-m-d H:i', strtotime($atencion->fecha_hora)); ?></p>
                    <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($atencion->especialidad); ?></p>
                    <p><strong>Profesional:</strong> <?php echo htmlspecialchars($atencion->profesional); ?></p>
                    <p><strong>Total de Pago:</strong> S/ <?php echo number_format($atencion->total_atencion, 2); ?></p>

                    <!-- Datos de Triaje -->
                    <div class="triaje-data">
                        <h6>Datos de Triaje</h6>
                        <?php if ($atencion->temperatura) : ?>
                            <p>Temp: <?php echo htmlspecialchars($atencion->temperatura); ?>°C | 
                                Peso: <?php echo htmlspecialchars($atencion->peso); ?> kg | 
                                Talla: <?php echo htmlspecialchars($atencion->talla); ?> cm | 
                                PA: <?php echo htmlspecialchars($atencion->presion_arterial); ?> | 
                                O2 (SpO2): <?php echo htmlspecialchars($atencion->oxigenacion); ?> % | 
                                FC: <?php echo htmlspecialchars($atencion->frecuencia_cardiaca); ?>
                            </p>
                        <?php else : ?>
                            <p class="text-danger">Triaje Pendiente de Registro.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Detalle de Servicios -->
                    <h6 class="mt-3">Detalle de Servicios</h6>
                    <table class="servicio-table">
                        <thead>
                            <tr><th>Servicio</th><th>Cant.</th><th>P. Unit.</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($atencion->servicios_detalle as $servicio) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($servicio->descripcion); ?></td>
                                <td><?php echo $servicio->cantidad; ?></td>
                                <td><?php echo number_format($servicio->precio_unitario, 2); ?></td>
                                <td><?php echo number_format($servicio->subtotal, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</body>
</html>