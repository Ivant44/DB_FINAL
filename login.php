<?php
session_start();
require 'conexion.php';

// Verificar si ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['nombre_usuario']);
    $password = $_POST['password'];
    
    // Validar campos vacíos
    if (empty($usuario) || empty($password)) {
        $error = "Por favor complete todos los campos";
    } else {
        // Buscar usuario por nombre de usuario o email
        $stmt = $pdo->prepare("SELECT id, nombre_usuario, password, rol FROM usuarios 
                              WHERE nombre_usuario = ? OR email = ?");
        $stmt->execute([$usuario, $usuario]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Autenticación exitosa
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_usuario'];
            $_SESSION['user_role'] = $user['rol'];
            
            // Redirección segura
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Mi Sitio Web</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="login-container">
        <h1 class="login-title">Iniciar Sesión</h1>
        
        <?php if (!empty($error)): ?>
            <div class="login-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['registro']) && $_GET['registro'] == 'exito'): ?>
            <div class="login-success">¡Registro exitoso! Por favor inicia sesión.</div>
        <?php endif; ?>
        
        <form class="login-form" method="POST" action="login.php">
            <div class="form-group">
                <label for="nombre_usuario">Usuario o Email</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" 
                       value="<?= isset($_POST['nombre_usuario']) ? htmlspecialchars($_POST['nombre_usuario']) : '' ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">Ingresar</button>
        </form>
        
        <div class="login-links">
            <a href="registro.php">¿No tienes cuenta? Regístrate</a><br>
            <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>