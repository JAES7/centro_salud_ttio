<?php
// views/caja/index.php (CON MENSAJES Y BOTÓN DE IMPRESIÓN)

// Obtenemos la fecha y hora actual en la zona horaria de Perú
$fechaActual = new DateTime("now", new DateTimeZone('America/Lima'));
$fechaFormateada = $fechaActual->format('Y-m-d\TH:i');

// Verificamos si hay un ID de ticket para imprimir
$idUltimoTicket = $_SESSION['ultimo_id_atencion'] ?? null;
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Caja</li>
    </ol>

    <!-- === ¡NUEVO! MOSTRAR MENSAJES DE ALERTA === -->
    <?php
    if (isset($_SESSION['mensaje_exito'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        echo '<i class="fas fa-check-circle me-2"></i>' . $_SESSION['mensaje_exito'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        // Limpiamos el mensaje para que no vuelva a aparecer
        unset($_SESSION['mensaje_exito']);
    }
    if (isset($_SESSION['mensaje_error'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '<i class="fas fa-exclamation-triangle me-2"></i>' . $_SESSION['mensaje_error'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        // Limpiamos el mensaje
        unset($_SESSION['mensaje_error']);
    }
    ?>
    <!-- === FIN DE MENSAJES DE ALERTA === -->


    <!-- Formulario Principal de Atención -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Registrar Nueva Atención
        </div>
        <div class="card-body">
            
            <!-- Fila 1: Fecha, Turno, Especialidad, Profesional -->
            <form id="formAtencion" method="POST" action="<?php echo APP_URL; ?>/caja/guardar">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="fecha_hora" name="fecha_hora" value="<?php echo $fechaFormateada; ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="turno" class="form-label">Turno</label>
                        <select id="turno" name="turno" class="form-select" required>
                            <option value="Mañana" <?php echo (date('H') < 13) ? 'selected' : ''; ?>>Mañana</option>
                            <option value="Tarde" <?php echo (date('H') >= 13) ? 'selected' : ''; ?>>Tarde</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="id_especialidad" class="form-label">Especialidad</label>
                        <select id="id_especialidad" name="id_especialidad" class="form-select" required>
                            <option value="">-- Elija --</option>
                            <?php foreach ($especialidades as $esp) : ?>
                                <option value="<?php echo $esp->id_especialidad; ?>"><?php echo $esp->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="id_profesional" class="form-label">Profesional</label>
                        <select id="id_profesional" name="id_profesional" class="form-select" required>
                            <option value="">-- Elija especialidad primero --</option>
                            <?php foreach ($profesionales as $prof) : ?>
                                <option value="<?php echo $prof->id_profesional; ?>"><?php echo $prof->nombre_completo; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Fila 2: Paciente y DNI -->
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label for="nombre_paciente" class="form-label">Nombre del Paciente</label>
                        <input type="text" class="form-control" id="nombre_paciente" name="nombre_paciente" placeholder="Escriba el nombre completo" required>
                    </div>

                    <div class="col-md-6">
                        <label for="dni_paciente" class="form-label">DNI del Paciente</label>
                        <input type="text" class="form-control" id="dni_paciente" name="dni_paciente" placeholder="Escriba el DNI (8 dígitos)" maxlength="8" required>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Fila 3: Servicios -->
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="select_servicio" class="form-label">Servicios</label>
                        <select id="select_servicio" class="form-select">
                            <option value="">-- Elija servicio --</option>
                            <?php foreach ($servicios as $serv) : ?>
                                <option value="<?php echo $serv->id_servicio; ?>" data-precio="<?php echo $serv->monto; ?>"><?php echo $serv->descripcion; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="servicio_cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="servicio_cantidad" value="1" min="1">
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="btnAgregarServicio" class="btn btn-secondary w-100">
                            <i class="fas fa-plus me-2"></i>Agregar Servicio
                        </button>
                    </div>
                </div>

                <!-- Fila 4: Tabla de Servicios Agregados -->
                <div class="table-responsive mt-3">
                    <table id="tablaServicios" class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Servicio</th>
                                <th style="width: 100px;">Cantidad</th>
                                <th style="width: 120px;">P. Unit.</th>
                                <th style="width: 120px;">Subtotal</th>
                                <th style="width: 80px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyServicios">
                            <!-- Los servicios se agregarán aquí con JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th colspan="2">
                                    <input type="text" class="form-control form-control-lg" id="totalGeneral" name="totalGeneral" value="0.00" readonly style="font-weight: bold; font-size: 1.25rem;">
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Fila 5: Botones de Guardar -->
                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-danger btn-lg me-2">
                        <i class="fas fa-times me-2"></i>Borrar Campos
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Guardar Atención
                    </button>
                    
                    <!-- === ¡BOTÓN ACTUALIZADO! === -->
                    <button type="button" id="btnImprimirTicket" class="btn btn-success btn-lg ms-2" 
                        <?php if ($idUltimoTicket) : ?>
                            data-id-ticket="<?php echo $idUltimoTicket; ?>"
                        <?php else : ?>
                            disabled
                        <?php endif; ?>
                    >
                        <i class="fas fa-print me-2"></i>Imprimir Último Ticket
                    </button>
                    <!-- === FIN DE BOTÓN ACTUALIZADO === -->

                </div>
            </form>

        </div>
    </div>

    <!-- Tabla de Últimas Atenciones -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clock-rotate-left me-1"></i>
            Últimas Atenciones del Día
        </div>
        <div class="card-body">
            <!-- Aquí pondremos la tabla de últimas atenciones -->
        </div>
    </div>

</div>