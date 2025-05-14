-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 06:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_proyecto_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `articulos`
--

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_autor` int(11) NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `estado` enum('borrador','publicado','archivado') NOT NULL DEFAULT 'borrador',
  `vistas` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `articulos`
--

INSERT INTO `articulos` (`id`, `titulo`, `contenido`, `id_categoria`, `id_autor`, `fecha_publicacion`, `fecha_actualizacion`, `ultima_actualizacion`, `estado`, `vistas`) VALUES
(1, 'Principios del Diseño Moderno', '<p>Se caracteriza por su minimalismo y funcionalidad. En este articulo exploramos los principios fundamentales que todo diseñador deberia conocer.</p>', 3, 1, '2025-05-10 18:01:59', '2025-05-12 20:26:18', '2025-05-12 14:27:40', 'publicado', 0),
(2, 'Color Theory for Designers', 'La teoría del color es esencial para crear diseños impactantes. Aprende cómo combinar colores efectivamente para tus proyectos.', 1, 1, '2025-05-10 18:01:59', NULL, NULL, 'publicado', 0),
(3, 'UI/UX Best Practices', 'Las mejores prácticas de interfaz de usuario y experiencia de usuario que están definiendo el 2023.', 1, 1, '2025-05-10 18:01:59', NULL, NULL, 'publicado', 0),
(4, 'Introducción a PHP 8', 'PHP 8 trae numerosas mejoras de rendimiento y nuevas características. En este artículo cubrimos las más importantes.', 2, 1, '2025-05-10 18:01:59', NULL, NULL, 'publicado', 0),
(5, 'JavaScript Moderno', 'De ES6 a las últimas características: cómo escribir JavaScript moderno y eficiente.', 2, 1, '2025-05-10 18:01:59', NULL, NULL, 'publicado', 0),
(6, 'Patrones de Diseño en Python', 'Los patrones de diseño son soluciones probadas a problemas comunes. Aprende a implementarlos en Python.', 2, 1, '2025-05-10 18:01:59', NULL, NULL, 'publicado', 0),
(7, 'Avances en Inteligencia Artificial', 'Cómo los últimos desarrollos en IA están cambiando diferentes industrias.', 3, 1, '2025-05-10 18:01:59', NULL, NULL, 'publicado', 0),
(8, 'Realidad Virtual en 2025', 'El estado actual de la tecnología de realidad virtual y sus aplicaciones prácticas.', 3, 1, '2025-05-10 18:01:59', NULL, '2025-05-10 18:04:32', 'publicado', 0),
(9, 'Blockchain más allá de las Criptomonedas', 'Usos innovadores de la tecnología blockchain en sectores como salud, logística y más.', 3, 1, '2025-05-10 18:01:59', NULL, NULL, 'publicado', 0),
(13, 'La evolucion de los Smartphones', 'Los smartphones han transformado radicualmente nuestra forma de vivir, comunicarnos y trabajar...', 1, 1, '2025-05-12 14:39:04', NULL, NULL, 'borrador', 0),
(14, 'La computacion cuantica', 'la computacion cuantica promete resolver problemas imposibles para los ordenadores tradicionales...', 1, 1, '2025-05-12 22:43:54', NULL, NULL, 'borrador', 0),
(15, 'La red 5G', 'La llegada del 5G ha mejorado drasticamente la velocidad y capacidad de las redes moviles...', 1, 1, '2025-05-12 22:45:01', NULL, NULL, 'borrador', 0),
(16, 'Los autos Electricos', 'Los autos electricos estan ganando terrenog gracias a mejoras en autonomia, reduccion de costos y...', 1, 1, '2025-05-12 22:45:59', NULL, NULL, 'borrador', 0),
(17, 'Python y su uso en la ciencia', 'Python se mantiene como uno de los lenguajes mas populares por su sintaxis...', 2, 1, '2025-05-12 22:47:00', NULL, NULL, 'borrador', 0),
(18, 'Programacion Orientada a Objetos', 'La programacion orientada a objetos (OOP)es un paradigma que organiza el codigo en clases...', 2, 1, '2025-05-12 22:47:56', NULL, NULL, 'borrador', 0),
(19, 'Git y Github como herramienta', 'Git y Github son herramientas funcamentales para el control de proyectos...', 2, 1, '2025-05-12 22:48:46', NULL, NULL, 'borrador', 0),
(20, 'Diseno Responsive', 'El diseno responsive permite que un sitio web se adapte a diferentes pantallas...', 3, 1, '2025-05-12 22:49:52', NULL, NULL, 'borrador', 0),
(21, 'Teoria del Color', '<p>La teoria del color influye en la percepcion emocional del usuario...</p>', 3, 1, '2025-05-12 22:50:34', '2025-05-13 04:51:20', '2025-05-12 22:51:20', 'publicado', 0);

-- --------------------------------------------------------

--
-- Table structure for table `articulos_etiquetas`
--

CREATE TABLE `articulos_etiquetas` (
  `id_articulo` int(11) NOT NULL,
  `id_etiqueta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `creado_por`, `fecha_creacion`) VALUES
(1, 'Tecnología', 'Artículos sobre tecnología y gadgets', NULL, '2025-05-10 15:15:43'),
(2, 'Programación', 'Tutoriales y artículos sobre desarrollo de software', NULL, '2025-05-10 15:15:43'),
(3, 'Diseño', 'Consejos y tendencias en diseño web y gráfico', NULL, '2025-05-10 15:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_comentario` datetime NOT NULL DEFAULT current_timestamp(),
  `aprobado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `etiquetas`
--

CREATE TABLE `etiquetas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultimo_login` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `rol` enum('admin','usuario','editor') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `email`, `password`, `fecha_registro`, `ultimo_login`, `activo`, `rol`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-10 15:14:17', '2025-05-10 17:07:19', 1, 'admin'),
(2, 'usuario_prueba', 'prueba@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-10 16:17:11', '2025-05-10 18:28:47', 1, 'usuario'),
(3, 'usuario2', 'ivan.torres4804@alumnos.udg.mx', '$2y$10$ZHuM5ALL4V.siol4Znb9heAusy/MwdT0xXBE9c24.QoGxqWWYIu.O', '2025-05-10 20:33:20', NULL, 1, 'usuario');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_autor` (`id_autor`);

--
-- Indexes for table `articulos_etiquetas`
--
ALTER TABLE `articulos_etiquetas`
  ADD PRIMARY KEY (`id_articulo`,`id_etiqueta`),
  ADD KEY `id_etiqueta` (`id_etiqueta`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indexes for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_articulo` (`id_articulo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `articulos_ibfk_2` FOREIGN KEY (`id_autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `articulos_etiquetas`
--
ALTER TABLE `articulos_etiquetas`
  ADD CONSTRAINT `articulos_etiquetas_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulos_etiquetas_ibfk_2` FOREIGN KEY (`id_etiqueta`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
