<?php
session_start();
require __DIR__ . '/../config.php';
require __DIR__ . '/../conexion.php';

// Verificar permisos de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['mensaje_error'] = "Acceso denegado";
    header("Location: " . BASE_URL . "login.php");
    exit;
}

// Verificar que se proporcionó un ID válido
if (!isset($_GET['id'])) {
    $_SESSION['mensaje_error'] = "ID de artículo no proporcionado";
    header("Location: " . BASE_URL . "admin/dashboard.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['mensaje_error'] = "ID de artículo inválido";
    header("Location: " . BASE_URL . "admin/dashboard.php");
    exit;
}

// Obtener el artículo a editar
try {
    $stmt = $pdo->prepare("
        SELECT a.*, c.nombre AS categoria_nombre 
        FROM articulos a
        LEFT JOIN categorias c ON a.id_categoria = c.id
        WHERE a.id = ?
    ");
    $stmt->execute([$id]);
    $articulo = $stmt->fetch();

    if (!$articulo) {
        $_SESSION['mensaje_error'] = "Artículo no encontrado";
        header("Location: " . BASE_URL . "admin/dashboard.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error al obtener artículo: " . $e->getMessage());
}

// Obtener todas las categorías para el select
$categorias = $pdo->query("SELECT id, nombre FROM categorias")->fetchAll();

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['mensaje_error'] = "Token de seguridad inválido";
        header("Location: " . BASE_URL . "admin/editar_articulo.php?id=" . $id);
        exit;
    }

    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');
    $categoria = $_POST['categoria'] ?? null;
    $estado = $_POST['estado'] ?? 'borrador';

    // Validación básica
    if (empty($titulo) || empty($contenido)) {
        $_SESSION['mensaje_error'] = "Título y contenido son obligatorios";
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE articulos 
                SET titulo = ?, contenido = ?, id_categoria = ?, estado = ?, fecha_actualizacion = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$titulo, $contenido, $categoria, $estado, $id]);
            
            $_SESSION['mensaje_exito'] = "Artículo actualizado correctamente";
            header("Location: " . BASE_URL . "admin/dashboard.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['mensaje_error'] = "Error al actualizar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="form-container">
        <h1>Editar Artículo</h1>
        
        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="mensaje-error"><?= $_SESSION['mensaje_error'] ?></div>
            <?php unset($_SESSION['mensaje_error']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label for="titulo">Título*</label>
                <input type="text" id="titulo" name="titulo" required 
                       value="<?= htmlspecialchars($articulo['titulo'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="contenido">Contenido*</label>
                <textarea id="contenido" name="contenido" rows="10" required><?= 
                    htmlspecialchars($articulo['contenido'] ?? '') 
                ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" 
                            <?= ($articulo['id_categoria'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado*</label>
                <select id="estado" name="estado" required>
                    <option value="publicado" <?= ($articulo['estado'] == 'publicado') ? 'selected' : '' ?>>Publicado</option>
                    <option value="borrador" <?= ($articulo['estado'] == 'borrador') ? 'selected' : '' ?>>Borrador</option>
                </select>
            </div>
            
            <button type="submit" class="boton">Guardar Cambios</button>
            <a href="<?= BASE_URL ?>admin/dashboard.php" class="boton boton-secundario">Cancelar</a>
        </form>
    </main>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- Opcional: Editor de texto enriquecido -->
    <script src="https://cdn.tiny.cloud/1/tu-api-key/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#contenido',
            plugins: 'link lists code',
            toolbar: 'bold italic | alignleft aligncenter alignright | bullist numlist | link code',
            menubar: false
        });
    </script>
</body>
<script src="https://cdn.tiny.cloud/1/d2alr66ill3to9j4xasg0l901brjc3x2ffm4p44t6u7xjgzc/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#contenido',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: '//www.tiny.cloud/css/codepen.min.css',
        height: 400,
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
});
</script>
</html>