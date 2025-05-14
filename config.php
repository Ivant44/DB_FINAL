
<?php
// config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/PROYECTOFINAL/');
define('BASE_PATH', __DIR__ . '/'); // Ahora apunta al directorio actual de config.php

// Configuración de la base de datos (opcional)
define('DB_HOST', 'localhost');
define('DB_NAME', 'nombre_bd');
define('DB_USER', 'usuario');
define('DB_PASS', 'contraseña');

// Seguridad - Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Configuración de errores (solo para desarrollo)
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
?>