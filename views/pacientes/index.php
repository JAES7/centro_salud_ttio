<?php
// views/pacientes/index.php (LISTADO CRUD - CON BOTONES ACTUALIZADOS)

// Recuperar variables del controlador
$pacientes = $pacientes ?? [];
$busqueda = $busqueda ?? '';
$incluir_eliminados = $incluir_eliminados ?? false;

// Determinar si el usuario logueado es admin para mostrar opciones de borrado
$esAdmin = ($_SESSION['rol'] ?? '') == 'admin';

?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Pacientes</li>
    </ol>

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

    <!-- Sección de Búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Listado de Pacientes
        </div>
        <div class="card-body">
            <form action="<?php echo APP_URL; ?>/paciente/index" method="GET" class="row g-3 mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="q" placeholder="Buscar por DNI o Nombre" value="<?php echo htmlspecialchars($busqueda); ?>">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <?php if ($esAdmin) : ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="e" id="incluirEliminados" value="1" <?php echo $incluir_eliminados ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="incluirEliminados">
                            Mostrar Eliminados
                        </label>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="<?php echo APP_URL; ?>/paciente" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
            
            <!-- Botón de Imprimir Todos -->
            <a href="<?php echo APP_URL; ?>/paciente/imprimirTodos" target="_blank" class="btn btn-success mt-2">
                <i class="fas fa-print me-2"></i>Imprimir Todos los Pacientes
            </a>
            
            <!-- Botón de Exportar General (CSV Mejorado) -->
            <?php if ($esAdmin) : ?>
                <a href="<?php echo APP_URL; ?>/reporte/exportarExcel" class="btn btn-info mt-2">
                    <i class="fas fa-file-csv me-2"></i>Exportar Citas/Triaje (CSV)
                </a>
            <?php endif; ?>

        </div>
    </div>

    <!-- Tabla de Resultados -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre Completo</th>
                            <th>Última Visita</th>
                            <th style="width: 150px;">Estado</th>
                            <th style="width: 320px;">Acciones</th> <!-- Ancho ajustado -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pacientes)) : ?>
                            <tr>
                                <td colspan="6" class="text-center">No se encontraron pacientes que coincidan con la búsqueda.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($pacientes as $paciente) : 
                                $esEliminado = $paciente->eliminado_suavemente;
                                $id = $paciente->id_paciente;
                                $nombre = htmlspecialchars($paciente->nombre_completo);
                            ?>
                                <tr class="<?php echo $esEliminado ? 'table-danger' : ''; ?>">
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo htmlspecialchars($paciente->dni); ?></td>
                                    <td><?php echo $nombre; ?></td>
                                    <td><?php echo $paciente->ultima_visita ? date('Y-m-d', strtotime($paciente->ultima_visita)) : 'N/A'; ?></td>
                                    <td>
                                        <?php if ($esEliminado) : ?>
                                            <span class="badge bg-danger">INACTIVO (Borrado Suave)</span>
                                        <?php else : ?>
                                            <span class="badge bg-success">ACTIVO</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Botón de Edición -->
                                        <a href="<?php echo APP_URL; ?>/paciente/editar/<?php echo $id; ?>" class="btn btn-sm btn-info me-1" title="Editar Información">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Botones de Exportación -->
                                        <a href="<?php echo APP_URL; ?>/paciente/imprimirPaciente/<?php echo $id; ?>" target="_blank" class="btn btn-sm btn-primary me-1" title="Exportar Historial (PDF)">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        
                                        <!-- Botón Borrado Suave/Restaurar -->
                                        <?php if ($esAdmin) : ?>
                                            <?php if (!$esEliminado) : ?>
                                                <button class="btn btn-sm btn-warning me-1 btn-soft-delete" 
                                                        data-id="<?php echo $id; ?>"
                                                        data-nombre="<?php echo $nombre; ?>"
                                                        title="Borrar Suave (Ocultar)">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            <?php else : ?>
                                                <!-- Opción Restaurar -->
                                                <a href="<?php echo APP_URL; ?>/paciente/restore/<?php echo $id; ?>" class="btn btn-sm btn-success me-1" title="Restaurar">
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                                <!-- Opción Borrado Permanente (Requiere confirmación extra) -->
                                                <button class="btn btn-sm btn-danger btn-hard-delete" 
                                                        data-id="<?php echo $id; ?>"
                                                        data-nombre="<?php echo $nombre; ?>"
                                                        title="Eliminar Permanentemente">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                            
                                        <?php else : ?>
                                            <span class="text-muted">Acciones Admin</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación (sin cambios) -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>¿Está seguro que desea realizar la acción de eliminación para el paciente <strong><span id="modalPacienteNombre"></span></strong> (DNI: <span id="modalPacienteId"></span>)?</p>
        <div id="modalOpcionesDelete">
            <!-- Opciones se cargarán aquí por JS -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmModalElement = document.getElementById('confirmModal');
    if (!confirmModalElement) return;
    
    const confirmModal = new bootstrap.Modal(confirmModalElement);
    const modalOpcionesDelete = document.getElementById('modalOpcionesDelete');
    const modalPacienteNombre = document.getElementById('modalPacienteNombre');
    const modalPacienteId = document.getElementById('modalPacienteId');
    const APP_URL = '<?php echo APP_URL; ?>';

    // Manejar clics en los botones de Borrado Suave
    document.querySelectorAll('.btn-soft-delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            
            modalPacienteNombre.textContent = nombre;
            modalPacienteId.textContent = id;
            
            // Cargar opciones para Borrado Suave
            modalOpcionesDelete.innerHTML = `
                <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Opción: Desea que el paciente se guarde, pero no aparezca en el sistema (Borrado Suave).</p>
                <a href="${APP_URL}/paciente/softDelete/${id}" class="btn btn-warning w-100"><i class="fas fa-eye-slash"></i> Confirmar Borrado Suave</a>
            `;
            confirmModal.show();
        });
    });

    // Manejar clics en los botones de Borrado Permanente
    document.querySelectorAll('.btn-hard-delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            
            modalPacienteNombre.textContent = nombre;
            modalPacienteId.textContent = id;

            // Cargar opciones para Borrado Permanente (Advertencia de que es irreversible)
            modalOpcionesDelete.innerHTML = `
                <p class="text-danger"><i class="fas fa-exclamation-circle"></i> Advertencia: Esta acción es irreversible. Se borrarán todos los registros de citas, triaje y el paciente.</p>
                <a href="${APP_URL}/paciente/softDelete/${id}" class="btn btn-warning me-2"><i class="fas fa-eye-slash"></i> Borrado Suave (Guardar pero ocultar)</a>
                <a href="${APP_URL}/paciente/hardDelete/${id}" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Eliminar Permanentemente</a>
            `;
            confirmModal.show();
        });
    });
});
</script>