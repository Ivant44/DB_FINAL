<?php
// Definir BASE_PATH si no está definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', '/PROYECTOFINAL/');
}
?>
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="mensaje-exito">
        <?= $_SESSION['mensaje_exito'] ?>
        <?php unset($_SESSION['mensaje_exito']); // Limpiar el mensaje después de mostrarlo ?>
    </div>
<?php endif; ?>
<nav>
    <!-- ... otros elementos ... -->
    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="<?php echo BASE_PATH; ?>admin/dashboard.php" class="nav-link">Panel de Control</a>
         
         <a href="<?php echo BASE_PATH; ?>admin/agregar_articulo.php" class="boton">Nuevo Artículo</a>
    <?php endif; ?>
</nav>