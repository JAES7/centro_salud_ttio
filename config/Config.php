<?php
// config/Config.php (CORREGIDO)

// --- CONFIGURACIÓN DE BASE DE DATOS (PARA XAMPP) ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Sin contraseña en XAMPP por defecto
define('DB_NAME', 'centro_salud_ttio');
define('DB_CHARSET', 'utf8mb4');


// --- CONFIGURACIÓN DE LA APLICACIÓN ---

// URL Raíz de la aplicación (¡MUY IMPORTANTE!)
define('APP_URL', 'http://localhost/centro_salud_ttio');

// Nombre del sitio
define('SITE_NAME', 'Centro de salud de Ttio'); // <-- AQUÍ ESTÁ EL CAMBIO

?>