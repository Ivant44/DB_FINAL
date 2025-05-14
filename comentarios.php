<?php
session_start();
require '../conexion.php';

// Verificar permisos
if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
    header("Location: ../index.php");
    exit();
}

// Cambiar estado de comentario
if (isset($_POST['accion']) && isset($_POST['id_comentario'])) {
    $accion = $_POST['accion'];
    $id = $_POST['id_comentario'];
    
    if ($accion === 'aprobar') {
        $pdo->prepare("UPDATE comentarios SET aprobado = 1 WHERE id = ?")->execute([$id]);
    } elseif ($accion === 'eliminar') {
        $pdo->prepare("DELETE FROM comentarios WHERE id = ?")->execute([$id]);
    }
    
    header("Location: comentarios.php");
    exit();
}

// Consulta para comentarios pendientes
$comentariosPendientes = $pdo->query("
    SELECT c.*, u.nombre_usuario, a.titulo AS articulo
    FROM comentarios c
    JOIN usuarios u ON c.id_usuario = u.id
    JOIN articulos a ON c.id_articulo = a.id
    WHERE c.aprobado = 0
    ORDER BY c.fecha_comentario DESC
")->fetchAll();
?>

<h2>Comentarios Pendientes de Aprobación</h2>

<?php foreach ($comentariosPendientes as $comentario): ?>
    <div class="comentario">
        <p><strong><?= htmlspecialchars($comentario['nombre_usuario']) ?></strong> en 
           <em><?= htmlspecialchars($comentario['articulo']) ?></em></p>
        <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
        <form method="POST">
            <input type="hidden" name="id_comentario" value="<?= $comentario['id'] ?>">
            <button type="submit" name="accion" value="aprobar">Aprobar</button>
            <button type="submit" name="accion" value="eliminar">Eliminar</button>
        </form>
    </div>
<?php endforeach; ?>