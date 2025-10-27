<?php
// views/pacientes/reporte_todos.php (LISTADO GENERAL PARA IMPRESIÓN)

$pacientes = $pacientes ?? [];
$nombreCentro = SITE_NAME ?? 'Centro de Salud Ttio';
$fechaReporte = date('Y-m-d H:i');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Listado General'; ?></title>
    <!-- Incluir Bootstrap para un formato básico -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 14pt; margin: 0; }
        .reporte-table th, .reporte-table td { font-size: 10pt; padding: 5px; border: 1px solid #ddd; }
        .reporte-table th { background-color: #f0f0f0; }
        
        @media print {
            .no-print { display: none; }
            .reporte-table { width: 100%; border-collapse: collapse; }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir / Guardar PDF</button>
        <a href="<?php echo APP_URL; ?>/paciente" class="btn btn-secondary">Volver</a>
    </div>

    <div class="header">
        <h1>LISTADO GENERAL DE PACIENTES ACTIVOS</h1>
        <h4><?php echo htmlspecialchars($nombreCentro); ?></h4>
        <small>Fecha del Reporte: <?php echo $fechaReporte; ?></small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped reporte-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombre Completo</th>
                    <th>Última Visita</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pacientes)) : ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay pacientes activos para mostrar.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($pacientes as $paciente) : ?>
                        <tr>
                            <td><?php echo $paciente->id_paciente; ?></td>
                            <td><?php echo htmlspecialchars($paciente->dni); ?></td>
                            <td><?php echo htmlspecialchars($paciente->nombre_completo); ?></td>
                            <td><?php echo $paciente->ultima_visita ? date('Y-m-d', strtotime($paciente->ultima_visita)) : 'N/A'; ?></td>
                            <td><?php echo $paciente->eliminado_suavemente ? 'INACTIVO' : 'ACTIVO'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>