<?php
require 'conexion.php';

$termino = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($termino)) {
    $stmt = $pdo->prepare("
        SELECT a.*, u.nombre_usuario AS autor 
        FROM articulos a
        JOIN usuarios u ON a.id_autor = u.id
        WHERE a.estado = 'publicado' 
        AND (a.titulo LIKE ? OR a.contenido LIKE ?)
        ORDER BY a.fecha_publicacion DESC
    ");
    $terminoBusqueda = "%$termino%";
    $stmt->execute([$terminoBusqueda, $terminoBusqueda]);
    $resultados = $stmt->fetchAll();
}
?>

<form method="GET" action="buscar.php">
    <input type="text" name="q" placeholder="Buscar artículos..." value="<?= htmlspecialchars($termino) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if (!empty($termino)): ?>
    <h2>Resultados para "<?= htmlspecialchars($termino) ?>"</h2>
    
    <?php if (count($resultados) > 0): ?>
        <div class="resultados">
            <?php foreach ($resultados as $articulo): ?>
    <div class="articulo">
        <h3><?= htmlspecialchars($articulo['titulo']) ?></h3>
        <p>Autor: <?= htmlspecialchars($articulo['autor']) ?></p>
        <p><?= substr(htmlspecialchars($articulo['contenido']), 0, 200) ?>...</p>
        <a href="articulo.php?id=<?= $articulo['id'] ?>">Leer más</a>
    </div>
<?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No se encontraron artículos que coincidan con tu búsqueda.</p>
    <?php endif; ?>
<?php endif; ?>