<?php
// views/triaje/index.php (BOTÓN DE EXPORTACIÓN ACTUALIZADO)

// Verificar si se encontró una atención
$atencion = $atencion_encontrada ?? null;
$busqueda = $busqueda ?? '';
$mensaje = $mensaje ?? '';
$id_atencion = $atencion->id_atencion ?? 0;

// Variables de Triaje (para rellenar si se edita en el futuro)
$temperatura = '';
$peso = '';
$talla = '';
$presion_arterial = '';
$oxigenacion = '';
$frecuencia_cardiaca = '';

?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Triaje</li>
    </ol>

    <!-- Sección de Búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-search me-1"></i>
            Buscar Paciente Pendiente de Triaje
        </div>
        <div class="card-body">
            <form action="<?php echo APP_URL; ?>/triaje/index" method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control form-control-lg" name="q" placeholder="Buscar por DNI o Nombre del Paciente" value="<?php echo htmlspecialchars($busqueda); ?>" required>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-lg me-2">Buscar</button>
                    <!-- ¡BOTÓN DE EXPORTACIÓN ACTUALIZADO! -->
                    <a href="<?php echo APP_URL; ?>/triaje/exportarExcelTodo" class="btn btn-success btn-lg">Descargar Reporte (Excel)</a>
                </div>
            </form>

            <?php if (!empty($mensaje) && !$atencion) : ?>
                <div class="alert alert-warning mt-3" role="alert"><?php echo $mensaje; ?></div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Sección de Registro de Triaje -->
    <?php if ($atencion) : ?>
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <i class="fas fa-file-medical me-1"></i>
            Registro de Triaje
        </div>
        <div class="card-body">
            <p><strong>Paciente:</strong> <?php echo htmlspecialchars($atencion->paciente_nombre); ?></p>
            <p><strong>Profesional (Cita):</strong> <?php echo htmlspecialchars($atencion->profesional_nombre); ?></p>
            <p><strong>Ticket Atención:</strong> <?php echo $atencion->numero_ticket_diario; ?></p>
            
            <hr>

            <form action="<?php echo APP_URL; ?>/triaje/guardar" method="POST">
                <!-- Campo oculto para saber a qué atención estamos guardando el triaje -->
                <input type="hidden" name="id_atencion" value="<?php echo $id_atencion; ?>">
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="temperatura" class="form-label">Temperatura (°C)</label>
                        <input type="number" step="0.1" class="form-control" id="temperatura" name="temperatura" value="<?php echo $temperatura; ?>" placeholder="Ej: 36.5" required>
                    </div>
                    <div class="col-md-4">
                        <label for="peso" class="form-label">Peso (kg)</label>
                        <input type="number" step="0.1" class="form-control" id="peso" name="peso" value="<?php echo $peso; ?>" placeholder="Ej: 70.2" required>
                    </div>
                    <div class="col-md-4">
                        <label for="talla" class="form-label">Talla (cm)</label>
                        <input type="number" step="1" class="form-control" id="talla" name="talla" value="<?php echo $talla; ?>" placeholder="Ej: 175" required>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <label for="presion_arterial" class="form-label">Presión Arterial (PA)</label>
                        <input type="text" class="form-control" id="presion_arterial" name="presion_arterial" value="<?php echo $presion_arterial; ?>" placeholder="Ej: 120/80" required>
                    </div>
                    <div class="col-md-4">
                        <label for="oxigenacion" class="form-label">Oxigenación (SpO2) (%)</label>
                        <input type="number" step="1" class="form-control" id="oxigenacion" name="oxigenacion" value="<?php echo $oxigenacion; ?>" placeholder="Ej: 98" required>
                    </div>
                    <div class="col-md-4">
                        <label for="frecuencia_cardiaca" class="form-label">Frecuencia Cardíaca (FC)</label>
                        <input type="number" step="1" class="form-control" id="frecuencia_cardiaca" name="frecuencia_cardiaca" value="<?php echo $frecuencia_cardiaca; ?>" placeholder="Ej: 80" required>
                    </div>
                </div>
                
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success btn-lg me-2">
                        <i class="fas fa-save me-2"></i>Guardar Triaje
                    </button>
                    <!-- Botón para imprimir ticket del triaje (la misma función que caja) -->
                    <a href="<?php echo APP_URL; ?>/caja/imprimir/<?php echo $id_atencion; ?>" target="_blank" class="btn btn-info btn-lg">
                        <i class="fas fa-print me-2"></i>Imprimir Ticket
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Muestra mensajes de éxito/error de la sesión -->
    <?php
    if (isset($_SESSION['mensaje_exito'])) {
        echo '<div class="alert alert-success mt-3" role="alert">' . $_SESSION['mensaje_exito'] . '</div>';
        unset($_SESSION['mensaje_exito']);
    }
    if (isset($_SESSION['mensaje_error'])) {
        echo '<div class="alert alert-danger mt-3" role="alert">' . $_SESSION['mensaje_error'] . '</div>';
        unset($_SESSION['mensaje_error']);
    }
    ?>

</div>