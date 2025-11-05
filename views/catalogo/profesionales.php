<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $data['titulo']; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active"><?php echo $data['titulo']; ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-doctor me-1"></i>
            Listado de Profesionales
            <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                <i class="fas fa-plus"></i> Nuevo Profesional
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombres y Apellidos</th>
                        <th>Especialidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['profesionales'] as $profesional) : ?>
                        <tr>
                            <td><?php echo $profesional->id_profesional; ?></td>
                            <td><?php echo htmlspecialchars($profesional->nombre_completo); ?></td>
                            <td><?php echo htmlspecialchars($profesional->nombre_especialidad); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm">Editar</button>
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAgregarLabel">Nuevo Profesional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo APP_URL; ?>/profesional/guardar" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" maxlength="8" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cmp" class="form-label">CMP</label>
                            <input type="text" class="form-control" id="cmp" name="cmp" maxlength="6">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="id_especialidad" class="form-label">Especialidad</label>
                        <select class="form-select" id="id_especialidad" name="id_especialidad">
                            <option value="">(Ninguna)</option>
                            <?php foreach ($data['especialidades'] as $especialidad) : ?>
                                <option value="<?php echo $especialidad->id; ?>">
                                    <?php echo htmlspecialchars($especialidad->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Profesional</button>
                </div>
            </form>
        </div>
    </div>
</div>