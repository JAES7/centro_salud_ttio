<?php
// views/login/cambio_forzado.php

$username = $_SESSION['username'] ?? 'Usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña Requerido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        html, body { height: 100%; }
        body { display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; }
        .card-change { width: 100%; max-width: 500px; padding: 25px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background-color: #ffffff; }
    </style>
</head>
<body>
    
    <main class="card-change">
        <div class="text-center mb-4">
            <h1 class="h3 mb-3 fw-normal">Cambio de Contraseña Requerido</h1>
            <p class="text-danger"><i class="fas fa-lock me-1"></i> Su cuenta debe actualizar la contraseña por motivos de seguridad.</p>
        </div>

        <?php
        $error = $_SESSION['error_cambio'] ?? null;
        if ($error) {
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            unset($_SESSION['error_cambio']);
        }
        ?>

        <form action="<?php echo APP_URL; ?>/login/actualizarPassword" method="POST">
            <div class="alert alert-info">
                Usuario: <strong><?php echo htmlspecialchars($username); ?></strong>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
            </div>

            <button class="w-100 btn btn-lg btn-success mt-3" type="submit">
                <i class="fas fa-save me-2"></i> Actualizar y Continuar
            </button>
        </form>
    </main>
</body>
</html>