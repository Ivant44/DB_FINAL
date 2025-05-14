<?php
// Configuración
$host = getenv('MYSQLHOST') ?: 'shortline.proxy.rlwy.net';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: 'tu_contraseña'; // Reemplaza esto
$db   = getenv('MYSQLDATABASE') ?: 'railway';
$port = getenv('MYSQLPORT') ?: 12201;
$sql_file = 'db_proyectofinalu.sql'; // Asegúrate que esté en la misma carpeta

// Conexión
$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

// Importación
$sql = file_get_contents($sql_file);
if ($conn->multi_query($sql)) {
    echo "✅ Importación exitosa!";
} else {
    echo "❌ Error: " . $conn->error;
}

$conn->close();
?>