<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_usuario']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validaciones básicas
    if ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE nombre_usuario = ? OR email = ?");
        $stmt->execute([$nombre, $email]);
        
        if ($stmt->fetch()) {
            $error = "El nombre de usuario o email ya está en uso";
        } else {
            // Hash de la contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, email, password, fecha_registro) VALUES (?, ?, ?, NOW())");
            if ($stmt->execute([$nombre, $email, $hash])) {
                header("Location: login.php?registro=exito");
                exit();
            } else {
                $error = "Error al registrar el usuario";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <div>
            <label>Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Confirmar Contraseña:</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit">Registrarse</button>
    </form>
    
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html>