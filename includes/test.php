<?php
require 'conexion.php';
$stmt = $pdo->query("SELECT 1");
echo "Conexión exitosa. Resultado: " . $stmt->fetchColumn();