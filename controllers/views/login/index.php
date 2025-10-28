<?php
// views/login/index.php
// Esta es una página completa, NO usa el template.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CAMBIO 1: El título de la pestaña del navegador usará el nuevo nombre -->
    <title>Login - <?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background-color: #ffffff;
        }
        .login-card .form-floating:first-of-type {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .login-card .form-floating:last-of-type {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body>
    
    <main class="login-card">
        <form action="<?php echo APP_URL; ?>/login/auth" method="POST">
            <div class="text-center mb-4">
                
                <!-- CAMBIO 2: Ruta del logo. CAMBIA 'logo.png' por el nombre de tu imagen -->
                <img class.mb-2 src="<?php echo APP_URL; ?>/assets/img/logo.jpg" alt="Logo" width="72" height="72" style="border-radius: 50%;">
                
                <!-- CAMBIO 3: El título h1 usará el nuevo nombre -->
                <h1 class="h3 mb-3 fw-normal"><?php echo SITE_NAME; ?></h1>
                
                <p>Sistema de Atenciones</p>
            </div>

            <?php
            // Mostrar mensaje de error si existe
            if (isset($_SESSION['error_login'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_login'] . '</div>';
                unset($_SESSION['error_login']); // Limpiar el error después de mostrarlo
            }
            ?>

            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required autofocus>
                <label for="username">Nombre de Usuario</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                <label for="password">Contraseña</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Ingresar</button>
            <p class="mt-4 mb-3 text-muted text-center">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?></p>
        </form>
    </main>

</body>
</html>