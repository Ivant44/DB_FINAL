<?php
session_start();
require 'conexion.php';

// Obtener ID de categoría de la URL
$categoria_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener información de la categoría
$stmt = $pdo->prepare("SELECT nombre, descripcion FROM categorias WHERE id = ?");
$stmt->execute([$categoria_id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    header("Location: index.php");
    exit();
}

// Obtener artículos de esta categoría
$stmt = $pdo->prepare("
    SELECT a.id, a.titulo, a.fecha_publicacion, a.contenido, 
           u.nombre_usuario AS autor, COUNT(c.id) AS total_comentarios
    FROM articulos a
    JOIN usuarios u ON a.id_autor = u.id
    LEFT JOIN comentarios c ON a.id = c.id_articulo AND c.aprobado = 1
    WHERE a.id_categoria = ? AND a.estado = 'publicado'
    GROUP BY a.id
    ORDER BY a.fecha_publicacion DESC
");
$stmt->execute([$categoria_id]);
$articulos = $stmt->fetchAll();

// Obtener todas las categorías para el menú
$categorias = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categoria['nombre']) ?> - Mi Sitio Web</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1><a href="index.php">Mi Sitio Web</a></h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Hola, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="dashboard.php">Panel</a>
                <a href="logout.php">Salir</a>
            <?php else: ?>
                <a href="login.php">Ingresar</a>
                <a href="registro.php">Registrarse</a>
            <?php endif; ?>
            
            <div class="categorias-menu">
                <?php foreach ($categorias as $cat): ?>
                    <a href="categoria.php?id=<?= $cat['id'] ?>" 
                       <?= $cat['id'] == $categoria_id ? 'class="active"' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </nav>
    </header>

    <main>
        <h2>Categoría: <?= htmlspecialchars($categoria['nombre']) ?></h2>
        <p class="descripcion-categoria"><?= htmlspecialchars($categoria['descripcion']) ?></p>
        
        <?php if (!empty($articulos)): ?>
            <div class="articulos-categoria">
                <?php foreach ($articulos as $articulo): ?>
                    <article class="articulo">
                        <h3>
                            <a href="articulo.php?id=<?= $articulo['id'] ?>">
                                <?= htmlspecialchars($articulo['titulo']) ?>
                            </a>
                        </h3>
                        <p class="meta">
                            Por <?= htmlspecialchars($articulo['autor']) ?> | 
                            <?= date('d/m/Y', strtotime($articulo['fecha_publicacion'])) ?> | 
                            <?= $articulo['total_comentarios'] ?> comentarios
                        </p>
                        <p><?= nl2br(htmlspecialchars(substr($articulo['contenido'], 0, 250))) ?>...</p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-articulos">No hay artículos publicados en esta categoría aún.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Mi Sitio Web. Todos los derechos reservados.</p>
    </footer>
</body>
</html>