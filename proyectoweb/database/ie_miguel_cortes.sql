-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 06:48:14
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ie_miguel_cortes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_accesos`
--

CREATE TABLE `auditoria_accesos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_acceso` timestamp NOT NULL DEFAULT current_timestamp(),
  `direccion_ip` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `accion` varchar(20) DEFAULT NULL,
  `exito` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `auditoria_accesos`
--

INSERT INTO `auditoria_accesos` (`id`, `usuario_id`, `fecha_acceso`, `direccion_ip`, `user_agent`, `accion`, `exito`) VALUES
(1, NULL, '2025-04-30 17:27:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(2, NULL, '2025-04-30 17:28:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(3, NULL, '2025-04-30 17:46:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(4, NULL, '2025-04-30 17:49:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', 'logout', 1),
(5, NULL, '2025-04-30 17:50:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(6, NULL, '2025-04-30 17:50:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', 'logout', 1),
(7, NULL, '2025-04-30 17:51:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 0),
(8, NULL, '2025-04-30 17:52:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(9, NULL, '2025-04-30 17:52:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', 'logout', 1),
(10, NULL, '2025-04-30 17:52:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(11, NULL, '2025-04-30 17:52:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', 'logout', 1),
(12, NULL, '2025-04-30 17:56:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(13, NULL, '2025-04-30 17:56:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', 'logout', 1),
(14, NULL, '2025-04-30 17:59:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(15, NULL, '2025-05-01 21:25:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 0),
(16, NULL, '2025-05-01 21:25:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', NULL, 1),
(17, NULL, '2025-05-09 19:44:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', NULL, 1),
(18, NULL, '2025-05-09 19:56:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', NULL, 1),
(19, NULL, '2025-05-16 16:15:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', NULL, 1),
(20, NULL, '2025-05-16 16:33:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'logout', 1),
(21, NULL, '2025-05-16 16:33:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', NULL, 0),
(22, 3, '2025-05-23 01:11:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(23, 3, '2025-05-23 01:18:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'logout', 1),
(24, NULL, '2025-05-26 14:40:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(25, NULL, '2025-05-26 14:40:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'logout', 1),
(26, 3, '2025-05-27 13:37:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(27, 3, '2025-05-27 13:52:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'logout', 1),
(28, 3, '2025-05-27 13:53:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(29, 3, '2025-05-27 14:05:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(30, 3, '2025-05-27 14:11:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(31, 3, '2025-05-27 14:14:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(32, 3, '2025-05-29 14:58:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(33, 3, '2025-05-30 00:15:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(34, 3, '2025-05-30 00:22:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 0),
(35, 3, '2025-05-30 00:22:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(36, 3, '2025-05-30 00:28:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'logout', 1),
(37, 3, '2025-05-30 00:42:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(38, 3, '2025-05-30 00:45:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'logout', 1),
(39, 3, '2025-05-30 00:59:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(40, 3, '2025-05-30 02:56:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(41, 3, '2025-05-30 03:41:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(42, 3, '2025-05-30 04:23:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'logout', 1),
(43, NULL, '2025-05-30 04:27:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1),
(44, NULL, '2025-05-30 04:36:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'logout', 1),
(45, 5, '2025-05-30 04:37:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos_tecnologicos`
--

CREATE TABLE `equipos_tecnologicos` (
  `id` int(11) NOT NULL,
  `numero_orden` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `denominacion` varchar(100) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `color` varchar(30) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `estado_conservacion` enum('excelente','bueno','regular','malo','pésimo') NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_registro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos_tecnologicos`
--

INSERT INTO `equipos_tecnologicos` (`id`, `numero_orden`, `cantidad`, `denominacion`, `marca`, `modelo`, `color`, `serie`, `estado_conservacion`, `observaciones`, `fecha_registro`, `usuario_registro`) VALUES
(2, '2', 1, 'Proyector', 'SONY', 'AX90977', 'Blanco', '5083833663', 'bueno', 'se ve colores bajos', '2025-05-30 04:21:15', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('Baja','Media','Alta') NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `equipo_id` int(11) DEFAULT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `titulo`, `descripcion`, `prioridad`, `categoria`, `equipo_id`, `ubicacion`, `usuario_id`, `estado`, `fecha_creacion`) VALUES
(1, 'Pc malograda', 'no funciona la placa madre', 'Media', 'Software', NULL, 'aula 01', 3, 'Pendiente', '2025-05-29 15:12:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion_contrasena`
--

CREATE TABLE `recuperacion_contrasena` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `utilizado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'estudiante', 'Usuario con permisos básicos de estudiante'),
(2, 'profesor', 'Usuario con permisos de profesor'),
(3, 'administrador', 'Usuario con permisos de administración del sistema');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `token_verificacion` varchar(100) DEFAULT NULL,
  `fecha_verificacion` timestamp NULL DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `email`, `nombre_usuario`, `contrasena_hash`, `fecha_registro`, `ultimo_acceso`, `activo`, `token_verificacion`, `fecha_verificacion`, `foto_perfil`) VALUES
(3, 'Alexis Josue', 'alopezsa6@ucvvirtual.edu.pe', 'alexlop02', '$2y$10$lXq9mt45b060TaAKBzrM9uj97MDl8KM88TStQ4ALIpp3SUTqpDaBu', '2025-05-23 01:10:47', NULL, 1, '26945b82066436ac56751f80a3df6475e73445bffd09efe0eff6452c903e53e4', NULL, 'uploads/perfil_3_1748568559.jpg'),
(5, 'Daniel Cabrera Palma', 'cpalma@gmail.com', 'palma22', '$2y$10$ncBeRvq8jGMe.KAe52po4OSJTP0nLAsd7Yg02HUdA8ix87R3t.6mu', '2025-05-30 04:37:16', NULL, 1, '1f26a2d34283b2b3a400d120ca54a5b42aa4be5d689f04965c2cb8b1f28f64d0', NULL, 'uploads/perfil_5_1748580446.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_roles`
--

INSERT INTO `usuario_roles` (`usuario_id`, `rol_id`) VALUES
(3, 1),
(5, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_accesos`
--
ALTER TABLE `auditoria_accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `equipos_tecnologicos`
--
ALTER TABLE `equipos_tecnologicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_registro` (`usuario_registro`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- Indices de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD PRIMARY KEY (`usuario_id`,`rol_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria_accesos`
--
ALTER TABLE `auditoria_accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `equipos_tecnologicos`
--
ALTER TABLE `equipos_tecnologicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria_accesos`
--
ALTER TABLE `auditoria_accesos`
  ADD CONSTRAINT `auditoria_accesos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `equipos_tecnologicos`
--
ALTER TABLE `equipos_tecnologicos`
  ADD CONSTRAINT `equipos_tecnologicos_ibfk_1` FOREIGN KEY (`usuario_registro`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `incidencias_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos_tecnologicos` (`id`),
  ADD CONSTRAINT `incidencias_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  ADD CONSTRAINT `recuperacion_contrasena_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD CONSTRAINT `usuario_roles_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_roles_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
