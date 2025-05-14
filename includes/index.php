<?php
session_start();
require 'conexion.php';

// Verificar sesión
$usuarioLogueado = isset($_SESSION['user_id']);

// Obtener datos del usuario
if ($usuarioLogueado) {
    $stmt = $pdo->prepare("SELECT nombre_usuario, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch();
}

// Consulta para artículos destacados (siempre visible)
$destacados = $pdo->query("
    SELECT a.id, a.titulo, a.fecha_publicacion, c.nombre AS categoria, 
           u.nombre_usuario AS autor, LEFT(a.contenido, 150) AS resumen
    FROM articulos a
    JOIN usuarios u ON a.id_autor = u.id
    LEFT JOIN categorias c ON a.id_categoria = c.id
    WHERE a.estado = 'publicado'
    ORDER BY a.vistas DESC, a.fecha_publicacion DESC
    LIMIT 3
")->fetchAll();

// Consulta para últimos artículos (solo si está logueado)
$ultimosArticulos = [];
if ($usuarioLogueado) {
    $ultimosArticulos = $pdo->query("
        SELECT a.id, a.titulo, a.fecha_publicacion, c.nombre AS categoria,
               u.nombre_usuario AS autor, LEFT(a.contenido, 150) AS resumen
        FROM articulos a
        JOIN usuarios u ON a.id_autor = u.id
        LEFT JOIN categorias c ON a.id_categoria = c.id
        WHERE a.estado = 'publicado'
        ORDER BY a.fecha_publicacion DESC
        LIMIT 5
    ")->fetchAll();
}

// Consulta para categorías principales (Diseño, Programación, Tecnología)
$categoriasPrincipales = $pdo->query("
    SELECT id, nombre FROM categorias 
    WHERE nombre IN ('Diseño', 'Programación', 'Tecnología')
    ORDER BY nombre
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Sitio Web</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Bienvenido a Mi Sitio Web</h1>
        <nav>
            <?php if ($usuarioLogueado): ?>
                <span>Hola, <?= htmlspecialchars($usuario['nombre_usuario']) ?> (<?= $usuario['rol'] ?>)</span>
                <!-- <a href="dashboard.php">Panel de Control</a> -->
                <?php if ($usuario['rol'] === 'admin'): ?>
                    <a href="admin/">Administración</a>
                <?php endif; ?>
                <a href="logout.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php">Iniciar Sesión</a>
                <a href="registro.php">Registrarse</a>
            <?php endif; ?>
            
            <!-- Menú de categorías principales -->
            <div class="categorias-menu">
                <?php foreach ($categoriasPrincipales as $cat): ?>
                    <a href="categoria.php?id=<?= $cat['id'] ?>" 
                       title="Ver artículos de <?= htmlspecialchars($cat['nombre']) ?>">
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </a>
                <?php endforeach; ?>
                <!-- Enlace para ver todas las categorías -->
                <a href="categorias.php">Todas las categorías</a>
            </div>
        </nav>
    </header>

    <!-- El resto de tu código permanece igual -->
    <main>
        <!-- Barra de búsqueda (visible para todos) -->
        <section class="busqueda">
            <form action="buscar.php" method="get">
                <input type="text" name="q" placeholder="Buscar artículos...">
                <button type="submit">Buscar</button>
            </form>
        </section>

        <!-- Contenido PÚBLICO (siempre visible) -->
        <section class="contenido-publico">
            <h2>Artículos destacados</h2>
            
            <?php if (!empty($destacados)): ?>
                <div class="destacados-grid">
                    <?php foreach ($destacados as $item): ?>
                        <article class="destacado-card">
                            <h3>
                                <a href="articulo.php?id=<?= $item['id'] ?>">
                                    <?= htmlspecialchars($item['titulo']) ?>
                                </a>
                            </h3>
                            <p class="meta">
                                <span><?= htmlspecialchars($item['autor']) ?></span> | 
                                <span><?= htmlspecialchars($item['categoria']) ?></span> | 
                                <span><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></span>
                            </p>
                            <p><?= htmlspecialchars($item['resumen']) ?>...</p>
                            <!-- Enlace a la categoría desde el artículo -->
                            <a href="categoria.php?id=<?= $item['id_categoria'] ?>" class="categoria-link">
                                Ver más de <?= htmlspecialchars($item['categoria']) ?>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No hay artículos destacados disponibles.</p>
            <?php endif; ?>
        </section>

        <!-- Resto de tu código... -->
    </main>
</body>
</html>