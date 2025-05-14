<?php
require 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idArticulo = $_GET['id'];

// Consulta para obtener un artículo específico
$stmt = $pdo->prepare("
    SELECT a.*, u.nombre_usuario AS autor, c.nombre AS categoria 
    FROM articulos a
    JOIN usuarios u ON a.id_autor = u.id
    LEFT JOIN categorias c ON a.id_categoria = c.id
    WHERE a.id = ? AND a.estado = 'publicado'
");
$stmt->execute([$idArticulo]);
$articulo = $stmt->fetch();

if (!$articulo) {
    header("Location: index.php");
    exit();
}

// Incrementar el contador de vistas
$pdo->prepare("UPDATE articulos SET vistas = vistas + 1 WHERE id = ?")->execute([$idArticulo]);

// Consulta para obtener comentarios aprobados
$stmtComentarios = $pdo->prepare("
    SELECT c.*, u.nombre_usuario 
    FROM comentarios c
    JOIN usuarios u ON c.id_usuario = u.id
    WHERE c.id_articulo = ? AND c.aprobado = 1
    ORDER BY c.fecha_comentario DESC
");
$stmtComentarios->execute([$idArticulo]);
$comentarios = $stmtComentarios->fetchAll();
?>

<!-- HTML para mostrar el artículo -->
<h1><?= htmlspecialchars($articulo['titulo']) ?></h1>
<!-- Resto del contenido del artículo -->

<!-- Sección de comentarios -->
<div class="comentarios">
    <h3>Comentarios (<?= count($comentarios) ?>)</h3>
    <?php foreach ($comentarios as $comentario): ?>
        <div class="comentario">
            <strong><?= htmlspecialchars($comentario['nombre_usuario']) ?></strong>
            <small><?= date('d/m/Y H:i', strtotime($comentario['fecha_comentario'])) ?></small>
            <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
        </div>
    <?php endforeach; ?>
</div>