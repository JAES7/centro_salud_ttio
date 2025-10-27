<?php
// views/reportes/index.php

// $data['titulo'] viene del controlador
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Reportes</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-file-excel me-1"></i>
            Exportar Datos de Citas y Triaje
        </div>
        <div class="card-body">
            <p>
                Este reporte consolidará la información de todas las atenciones registradas, incluyendo los datos biométricos de triaje y el detalle de los servicios consumidos, en un solo archivo de Excel (formato XLSX).
            </p>
            <p>
                La exportación solo es visible para usuarios con rol de **Administrador**.
            </p>
            
            <a href="<?php echo APP_URL; ?>/reporte/exportarExcel" class="btn btn-success btn-lg mt-3">
                <i class="fas fa-download me-2"></i>Generar Reporte Completo (Excel)
            </a>
            
        </div>
    </div>
</div>