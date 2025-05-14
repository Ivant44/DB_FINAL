<?php
session_start();
require __DIR__ . '/../conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $contenido = trim($_POST['contenido']);
    $categoria = $_POST['categoria'];
    $autor_id = $_SESSION['user_id'];
    
    // Validación básica
    if (empty($titulo) || empty($contenido)) {
        $error = "Título y contenido son obligatorios";
    } else {
        // Insertar en la base de datos
        $stmt = $pdo->prepare("
            INSERT INTO articulos (titulo, contenido, id_autor, id_categoria, estado, fecha_publicacion) 
            VALUES (?, ?, ?, ?, 'borrador', NOW())
        ");
        $stmt->execute([$titulo, $contenido, $autor_id, $categoria]);
        
        header('Location: ver_articulo.php?id=' . $pdo->lastInsertId());
        exit;
    }
}

// Obtener categorías para el select
$categorias = $pdo->query("SELECT id, nombre FROM categorias")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nuevo Artículo</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php require __DIR__ . '/../includes/header.php';?>
    
    <main class="form-container">
        <h1>Crear Nuevo Artículo</h1>
        
        <?php if (isset($error)): ?>
            <div class="mensaje error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            
            <div class="form-group">
                <label for="contenido">Contenido:</label>
                <textarea id="contenido" name="contenido" rows="10" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="boton">Publicar Artículo</button>
        </form>
    </main>
    
    
</body>
</html>