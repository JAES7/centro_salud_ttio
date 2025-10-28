<?php
// views/template.php (CON RESTRICCIONES DE MENÚ Y NUEVO ROL 'SOPORTE' COMO SUPERVISOR)
$rol = $_SESSION['rol'] ?? 'guest'; 

// Definición de acceso a módulos (¡AJUSTADA!)
$esAdmin = ($rol == 'admin');
$esCaja = ($rol == 'caja' || $esAdmin || $rol == 'soporte'); // Soporte puede ver/usar Caja
$esTriaje = ($rol == 'triaje' || $esAdmin || $rol == 'soporte'); // Soporte puede ver/usar Triaje
$esSoporte = ($rol == 'soporte' || $esAdmin || $rol == 'caja' || $rol == 'triaje'); // Catálogos y Reportes (Soporte)
$esSuperAdmin = $esAdmin; // Usuarios es solo para el rol 'admin' puro

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistema de Atenciones C.S. Ttio" />
    <meta name="author" content="Tu Nombre" />
    
    <title><?php echo $titulo ?? SITE_NAME; ?> - <?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome (para iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos CSS (del arreglo anterior) -->
    <style>
        body { background-color: #f8f9fa; }
        .navbar { position: fixed; top: 0; width: 100%; z-index: 1030; }
        #layoutSidenav { padding-top: 56px; }
        #layoutSidenav_nav { position: fixed; top: 56px; left: 0; width: 225px; height: calc(100vh - 56px); background-color: #343a40; z-index: 1000; overflow-y: auto; }
        #layoutSidenav_content { position: relative; padding-left: 225px; flex-grow: 1; }
        footer.py-4 { background-color: #e9ecef; }
        .sb-sidenav-dark { color: #fff; }
        .sb-sidenav-dark .sb-sidenav-menu-heading { padding: 1.75rem 1rem 0.75rem; font-size: 0.75rem; font-weight: bold; color: #8a8a8a; text-transform: uppercase; }
        .sb-sidenav-dark .nav-link { display: flex; align-items: center; padding-top: 0.75rem; padding-bottom: 0.75rem; position: relative; color: rgba(255, 255, 255, 0.75); }
        .sb-sidenav-dark .nav-link:hover { color: #fff; }
        .sb-sidenav-dark .sb-nav-link-icon { width: 20px; margin-right: 0.5rem; text-align: center; }
        .sb-sidenav-dark .sb-sidenav-footer { padding: 0.75rem 1rem; font-size: 0.9rem; background-color: rgba(0,0,0,0.2); }
    </style>

    <!-- Pasa la URL de PHP a JavaScript -->
    <script>
        window.APP_URL = '<?php echo APP_URL; ?>';
    </script>
</head>
<body class="sb-nav-fixed">
    
    <!-- 1. BARRA DE NAVEGACIÓN SUPERIOR -->
    <nav class="navbar navbar-expand navbar-dark bg-primary">
        <a class="navbar-brand ps-3" href="<?php echo APP_URL; ?>/dashboard"><?php echo SITE_NAME; ?></a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars text-white"></i></button>
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></div>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i> <?php echo $_SESSION['username'] ?? 'Usuario'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Mi Perfil (pronto)</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/login/logout">Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- 2. CONTENIDO (Menú Lateral y Página) -->
    <div id="layoutSidenav">
        
        <!-- 2.1. MENÚ LATERAL -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Principal</div>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/dashboard">
                            <i class="fas fa-tachometer-alt sb-nav-link-icon"></i>
                            Dashboard
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Módulos</div>
                        
                        <?php if ($esCaja) : // CAJA, ADMIN, SOPORTE ?>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/caja">
                            <i class="fas fa-cash-register sb-nav-link-icon"></i>
                            Caja (Atenciones)
                        </a>
                        <?php endif; ?>

                        <?php if ($esTriaje) : // TRIAJE, ADMIN, SOPORTE ?>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/triaje">
                            <i class="fas fa-heart-pulse sb-nav-link-icon"></i>
                            Triaje
                        </a>
                        <?php endif; ?>

                        <?php if ($esCaja || $esTriaje) : // PACIENTES (visible para todos menos soporte puro) ?>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/paciente">
                            <i class="fas fa-users sb-nav-link-icon"></i>
                            Pacientes
                        </a>
                        <?php endif; ?>

                        <?php if ($esSoporte) : // SOPORTE Y ADMIN: Reportes y Catálogos ?>
                        <div class="sb-sidenav-menu-heading">Administración</div>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/especialidades">
                            <i class="fas fa-star sb-nav-link-icon"></i>
                            Especialidades
                        </a>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/profesionales">
                            <i class="fas fa-user-doctor sb-nav-link-icon"></i>
                            Profesionales
                        </a>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/servicios">
                            <i class="fas fa-hand-holding-medical sb-nav-link-icon"></i>
                            Servicios
                        </a>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/reporte">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-export"></i></div>
                            Reportes
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($esAdmin) : // SOLO ADMIN: Usuarios ?>
                        <a class="nav-link" href="<?php echo APP_URL; ?>/usuarios">
                            <i class="fas fa-user-shield sb-nav-link-icon"></i>
                            Usuarios
                        </a>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Sesión iniciada como:</div>
                    <?php echo $rol; ?>
                </div>
            </nav>
        </div>

        <!-- 2.2. CONTENIDO DE LA PÁGINA -->
        <div id="layoutSidenav_content">
            
            <main>
                <?php
                if (isset($contentViewPath) && file_exists($contentViewPath)) {
                    require_once $contentViewPath;
                } else {
                    echo '<div class="container-fluid px-4"><h1 class="mt-4">Error al cargar vista</h1><p>El controlador no pudo cargar el archivo de contenido.</p></div>';
                }
                ?>
            </main>

            <!-- Pie de página -->
            <footer class="py-4 bg-light mt-auto">
                <!-- ... (Footer sin cambios) ... -->
            </footer>
        </div>
    </div> <!-- Fin de #layoutSidenav -->

    <!-- Bootstrap JS y caja.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/caja.js"></script>
</body>
</html>