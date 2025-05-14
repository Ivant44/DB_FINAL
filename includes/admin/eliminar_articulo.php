<?php
// Activar mostrar errores (solo para desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/../config.php';
require __DIR__ . '/../conexion.php';

// Verificar permisos
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['mensaje_error'] = "Acceso denegado";
    header("Location: " . BASE_URL . "login.php");
    exit;
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['mensaje_error'] = "Token de seguridad inválido";
    header("Location: " . BASE_URL . "admin/dashboard.php");
    exit;
}

// Verificar ID
if (!isset($_POST['id'])) {
    $_SESSION['mensaje_error'] = "ID no proporcionado";
    header("Location: " . BASE_URL . "admin/dashboard.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['mensaje_error'] = "ID inválido";
    header("Location: " . BASE_URL . "admin/dashboard.php");
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();
    
    // Eliminar comentarios primero (si existe esta relación)
    if ($pdo->query("SHOW TABLES LIKE 'comentarios'")->rowCount() > 0) {
        $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id_articulo = ?");
        $stmt->execute([$id]);
    }
    
    // Eliminar artículo
    $stmt = $pdo->prepare("DELETE FROM articulos WHERE id = ?");
    $stmt->execute([$id]);
    
    $pdo->commit();
    
    $_SESSION['mensaje_exito'] = "Artículo eliminado correctamente";
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['mensaje_error'] = "Error al eliminar: " . $e->getMessage();
    error_log("Error al eliminar artículo: " . $e->getMessage()); // Registrar error
}

header("Location: " . BASE_URL . "admin/dashboard.php");
exit;
?>