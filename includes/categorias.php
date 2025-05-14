<?php
require 'conexion.php';

// Consulta para obtener todas las categorías con conteo de artículos
$categorias = $pdo->query("
    SELECT c.id, c.nombre, c.descripcion, 
           COUNT(a.id) AS total_articulos
    FROM categorias c
    LEFT JOIN articulos a ON c.id = a.id_categoria AND a.estado = 'publicado'
    GROUP BY c.id
    ORDER BY c.nombre
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas las Categorías | Mi Sitio Web</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <h1 class="titulo-principal">Explora Nuestras Categorías</h1>
        
        <div class="grid-categorias">
            <?php foreach ($categorias as $categoria): ?>
                <div class="categoria-card">
                    <div class="categoria-header">
                        <h2><?= htmlspecialchars($categoria['nombre']) ?></h2>
                    </div>
                    <div class="categoria-body">
                        <p><?= htmlspecialchars($categoria['descripcion']) ?></p>
                        <span class="contador-articulos"><?= $categoria['total_articulos'] ?> artículos</span>
                        <a href="categoria.php?id=<?= $categoria['id'] ?>" class="boton">Ver Artículos</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>