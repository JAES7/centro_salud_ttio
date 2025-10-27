<?php 
// views/catalogo/profesionales.php
// Asumo que esta vista es llamada desde el CatalogoController::profesionales()

// La data enviada desde el controlador está disponible en $data
$profesionales = $data['profesionales'] ?? [];
$especialidades = $data['especialidades'] ?? [];
?>

<div class="container-fluid mt-4">
    <h2>Gestión de Profesionales</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-md"></i> Agregar Nuevo Profesional
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo APP_URL; ?>/catalogo/guardarProfesional"> 
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nombre_completo">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required placeholder="Ej: Dr. Juan Pérez">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dni">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" required placeholder="Ej: 45678912">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_especialidad">Especialidad</label>
                        <select class="form-control" id="id_especialidad" name="id_especialidad" required>
                            <option value="">Seleccione una Especialidad...</option>
                            <?php foreach ($especialidades as $esp): ?>
                                <option value="<?php echo htmlspecialchars($esp->id_especialidad); ?>">
                                    <?php echo htmlspecialchars($esp->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-plus"></i> Guardar Profesional
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-info text-white">
            <i class="fas fa-list"></i> Lista de Profesionales
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre Completo</th>
                            <th>Especialidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($profesionales)): ?>
                            <?php foreach ($profesionales as $prof): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prof->id_profesional); ?></td>
                                    <td><?php echo htmlspecialchars($prof->dni); ?></td>
                                    <td><?php echo htmlspecialchars($prof->nombre_completo); ?></td>
                                    <td><?php echo htmlspecialchars($prof->nombre_especialidad); ?></td>
                                    <td>
                                        <?php if ($prof->activo == 1): ?>
                                            <span class="badge badge-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning">Editar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                             <tr><td colspan="6" class="text-center">No hay profesionales registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>