<?php
// views/pacientes/form.php (FORMULARIO DE EDICIÓN)

// Recuperar variables del controlador
$paciente = $paciente ?? null;

// Asegurar que el paciente existe
if (!$paciente) {
    echo '<div class="alert alert-danger mt-4">Error: No se puede cargar el formulario. Paciente no encontrado.</div>';
    return;
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/paciente">Pacientes</a></li>
        <li class="breadcrumb-item active">Editar</li>
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

    <!-- Formulario de Edición -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-edit me-1"></i>
            Datos del Paciente ID: <?php echo $paciente->id_paciente; ?>
        </div>
        <div class="card-body">
            <form action="<?php echo APP_URL; ?>/paciente/actualizar" method="POST">
                <!-- Campo oculto para el ID -->
                <input type="hidden" name="id_paciente" value="<?php echo htmlspecialchars($paciente->id_paciente); ?>">

                <div class="mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" class="form-control" id="dni" name="dni" value="<?php echo htmlspecialchars($paciente->dni); ?>" maxlength="8" required>
                </div>
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($paciente->nombre_completo); ?>" required>
                </div>
                
                <div class="mt-4 text-end">
                    <a href="<?php echo APP_URL; ?>/paciente" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>