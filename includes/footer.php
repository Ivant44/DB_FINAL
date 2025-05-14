<?php
// Verificar si la variable $year no está definida para establecer el año actual
if (!isset($year)) {
    $year = date('Y');
}
?>

<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Sobre Nosotros</h3>
            <p>Tu sitio web de noticias y artículos sobre diseño, programación y tecnología.</p>
        </div>
        
        <div class="footer-section">
            <h3>Enlaces Rápidos</h3>
            <ul class="footer-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="categorias.php">Categorías</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Contacto</h3>
            <p>Email: info@misitio.com</p>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?= $year ?> Mi Sitio Web. Todos los derechos reservados.</p>
    </div>
</footer>

<!-- Scripts comunes -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="js/main.js"></script>