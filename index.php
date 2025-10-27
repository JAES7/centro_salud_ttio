<?php
// index.php (Limpio y sin Deprecated)

// FORZAR A MOSTRAR ERRORES (¡MUY IMPORTANTE!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// --- ¡AQUÍ ESTÁ EL CAMBIO! ---
// Mostramos todos los errores excepto DEPRECATED y NOTICE
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE); 
// --- FIN DEL CAMBIO ---

// Iniciar la sesión
session_start();

// Definir la ruta raíz de la aplicación
define('APP_ROOT', dirname(__FILE__));

// Cargar la configuración y el "motor" (Core)
require_once APP_ROOT . '/config/Config.php';

require_once APP_ROOT . '/core/App.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Model.php';

// Iniciar la aplicación
$app = new App();


