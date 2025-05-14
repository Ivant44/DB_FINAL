<?php
session_start();
require __DIR__ . '/../config.php';
require BASE_PATH . 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL . "login.php");
    exit();
}


// Obtener estadísticas
try {
    $totalArticulos = $pdo->query("SELECT COUNT(*) FROM articulos")->fetchColumn();
    $totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $totalComentarios = $pdo->query("SELECT COUNT(*) FROM comentarios")->fetchColumn();

    // Obtener últimos artículos
    $ultimosArticulos = $pdo->query("
        SELECT a.id, a.titulo, a.estado, u.nombre_usuario AS autor
        FROM articulos a
        JOIN usuarios u ON a.id_autor = u.id
        ORDER BY a.fecha_publicacion DESC
        LIMIT 5
    ")->fetchAll();
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | Admin</title>
    <!-- Usar ruta absoluta para los estilos -->
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>css/styles.css">
</head>
<body>
    <?php include BASE_PATH . 'includes/header.php'; ?>
    
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Panel de Control</h1>
            <p>Bienvenido, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrador') ?></p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Artículos</h3>
                <p><?= $totalArticulos ?></p>
            </div>
            <div class="stat-card">
                <h3>Usuarios</h3>
                <p><?= $totalUsuarios ?></p>
            </div>
            <div class="stat-card">
                <h3>Comentarios</h3>
                <p><?= $totalComentarios ?></p>
            </div>
        </div>
        
        <div class="seccion-dashboard">
            <h2>Últimos Artículos</h2>
            <table class="tabla-dashboard">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimosArticulos as $articulo): ?>
                        <tr>
                            <td><?= htmlspecialchars($articulo['titulo']) ?></td>
                            <td><?= htmlspecialchars($articulo['autor']) ?></td>
                            <td><?= ucfirst($articulo['estado']) ?></td>
                            <td class="acciones">
    <a href="<?= BASE_URL ?>admin/editar_articulo.php?id=<?= $articulo['id'] ?>" 
       class="boton-dashboard boton-accion">Editar</a>
    <form method="POST" action="<?= BASE_URL ?>admin/eliminar_articulo.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= $articulo['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="boton-dashboard boton-eliminar">
            Eliminar
        </button>
    </form>
</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include BASE_PATH . 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script>
        // Función para confirmar eliminación
        function confirmarEliminacion(e) {
            if (!confirm('¿Estás seguro de eliminar este artículo?')) {
                e.preventDefault();
            }
        }
        
        // Asignar eventos a los botones de eliminar
        document.querySelectorAll('.boton-eliminar').forEach(btn => {
            btn.addEventListener('click', confirmarEliminacion);
        });
    </script>
</body>
</html>