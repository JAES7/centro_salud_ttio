<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $data['titulo']; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active"><?php echo $data['titulo']; ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-hand-holding-medical me-1"></i>
            Listado de Servicios
            <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                <i class="fas fa-plus"></i> Nuevo Servicio
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio (S/)</th>
                        <th>Especialidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Se asume que $data['servicios'] contiene objetos (stdClass) con propiedades: 
                    // id_servicio, descripcion, monto, id_especialidad, nombre_especialidad
                    foreach ($data['servicios'] as $item) : 
                    ?>
                        <tr>
                            <td><?php echo $item->id_servicio; ?></td>
                            <td><?php echo htmlspecialchars($item->descripcion); ?></td>
                            <td>S/ <?php echo number_format($item->monto, 2); ?></td>
                            <td><?php echo htmlspecialchars($item->nombre_especialidad); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditar"
                                        data-bs-id="<?php echo $item->id_servicio; ?>" 
                                        data-bs-descripcion="<?php echo htmlspecialchars($item->descripcion); ?>"
                                        data-bs-monto="<?php echo $item->monto; ?>"
                                        data-bs-especialidad="<?php echo $item->id_especialidad; ?>">
                                    Editar
                                </button>
                                <a href="<?php echo APP_URL; ?>/servicio/eliminar/<?php echo $item->id_servicio; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Está seguro de que desea eliminar este servicio?');">
                                    Eliminar
                                </a>
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
                <h5 class="modal-title" id="modalAgregarLabel">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo APP_URL; ?>/servicio/guardar" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Servicio (Descripción)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio (Monto S/)</label>
                         <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_especialidad" class="form-label">Especialidad</label>
                        <select class="form-select" id="id_especialidad" name="id_especialidad">
                            <option value="">(Ninguna)</option>
                            <?php 
                            // Se asume que $data['especialidades'] contiene objetos con propiedades: id, nombre
                            foreach ($data['especialidades'] as $especialidad) : 
                            ?>
                                <option value="<?php echo $especialidad->id; ?>">
                                    <?php echo htmlspecialchars($especialidad->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditarLabel">Editar Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditar" action="<?php echo APP_URL; ?>/servicio/actualizar" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_id_servicio" name="id_servicio">
                    
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre del Servicio (Descripción)</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_precio" class="form-label">Precio (Monto S/)</label>
                        <input type="number" class="form-control" id="edit_precio" name="precio" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_especialidad" class="form-label">Especialidad</label>
                        <select class="form-select" id="edit_id_especialidad" name="id_especialidad">
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
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEditar = document.getElementById('modalEditar');
        if (modalEditar) {
            modalEditar.addEventListener('show.bs.modal', function (event) {
                // Botón que disparó el modal (el botón 'Editar')
                const button = event.relatedTarget;
                
                // Extraer datos de los atributos data-*
                const id_servicio = button.getAttribute('data-bs-id');
                const descripcion = button.getAttribute('data-bs-descripcion');
                const monto = button.getAttribute('data-bs-monto');
                const id_especialidad = button.getAttribute('data-bs-especialidad');

                // Llenar los campos del modal de Edición
                document.getElementById('edit_id_servicio').value = id_servicio;
                document.getElementById('edit_nombre').value = descripcion;
                document.getElementById('edit_precio').value = monto;
                document.getElementById('edit_id_especialidad').value = id_especialidad;
            });
        }
    });
</script>