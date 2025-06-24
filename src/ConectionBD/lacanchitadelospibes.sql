-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2025 a las 17:12:03
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
-- Base de datos: `lacanchitadelospibes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancha`
--

CREATE TABLE `cancha` (
  `id_cancha` int(4) NOT NULL,
  `nombreCancha` varchar(20) NOT NULL,
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cancha`
--

INSERT INTO `cancha` (`id_cancha`, `nombreCancha`, `idUpdate`, `idCreate`, `habilitado`, `cancelado`) VALUES
(1, 'monumental', '2025-05-04 23:03:13', '2025-05-04 23:03:13', 1, 0),
(2, 'bombonera', '2025-05-04 23:03:13', '2025-05-04 23:03:13', 1, 0),
(3, 'palacio', '2025-06-24 12:53:28', '2025-05-04 23:03:28', 1, 0),
(4, 'cilindro', '2025-06-24 12:51:50', '2025-05-04 23:03:28', 1, 0),
(5, 'gasometro', '2025-05-04 23:05:32', '2025-05-04 23:05:32', 1, 0),
(6, 'fortin', '2025-06-24 12:50:50', '2025-05-04 23:05:56', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `id_rol`, `id_persona`, `id_usuario`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, 1, 4, 1, 0, 1, '2025-05-16 02:04:25', '2025-06-17 18:16:01'),
(2, 1, 5, 2, 1, 0, '2025-05-16 02:09:28', '2025-05-16 02:09:28'),
(3, 1, 6, 3, 1, 0, '2025-05-16 02:44:42', '2025-06-13 17:35:08'),
(5, 6, 8, 5, 1, 0, '2025-06-17 18:35:03', '2025-06-17 18:35:03'),
(6, 6, 9, 6, 1, 0, '2025-06-18 11:30:33', '2025-06-18 11:30:33'),
(7, 6, 10, 7, 1, 0, '2025-06-23 13:52:17', '2025-06-23 13:52:17'),
(8, 6, 11, 8, 1, 0, '2025-06-23 14:33:11', '2025-06-23 14:33:11'),
(9, 6, 12, 9, 1, 0, '2025-06-23 14:47:41', '2025-06-23 14:47:41'),
(10, 6, 11, 8, 1, 0, '2025-06-23 17:28:17', '2025-06-23 17:28:17'),
(11, 6, 10, 7, 1, 0, '2025-06-23 17:48:09', '2025-06-23 17:48:09'),
(12, 6, 10, 7, 1, 0, '2025-06-23 17:48:23', '2025-06-23 17:48:23'),
(13, 6, 11, 8, 1, 0, '2025-06-23 17:49:18', '2025-06-23 17:49:18'),
(14, 6, 12, 9, 1, 0, '2025-06-23 17:49:53', '2025-06-23 17:49:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha`
--

CREATE TABLE `fecha` (
  `id_fecha` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habiltado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id_horario` int(11) NOT NULL,
  `horario` time NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habiltado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `edad` varchar(3) NOT NULL,
  `dni` varchar(10) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 1,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `apellido`, `nombre`, `edad`, `dni`, `telefono`, `habilitado`, `cancelado`, `idCreate`, `idUpdate`) VALUES
(1, '', '', '', '', '', 1, 0, '2025-05-16 01:51:26', '2025-05-16 01:51:26'),
(2, '', '', '', '', '', 1, 0, '2025-05-16 01:52:19', '2025-05-16 01:52:19'),
(3, '', '', '', '', '', 1, 0, '2025-05-16 02:02:26', '2025-05-16 02:02:26'),
(4, '', '', '', '', '', 1, 0, '2025-05-16 02:04:24', '2025-05-16 02:04:24'),
(5, 'mino', 'seba', '46', '26589756', '1156321455', 1, 0, '2025-05-16 02:09:28', '2025-05-16 02:09:28'),
(6, 'mino', 'seba', '56', '25879023', '3354658955', 1, 0, '2025-05-16 02:44:42', '2025-05-16 02:44:42'),
(7, 'minotti', 'seba', '112', '26325897', '12345678', 1, 0, '2025-06-17 18:26:25', '2025-06-17 18:26:25'),
(8, 'si', 'ahora', '236', '12345678', '1125658963', 1, 0, '2025-06-17 18:35:03', '2025-06-17 18:35:03'),
(9, 'mino', 'seba', '523', '12345678', '2253698521', 1, 0, '2025-06-18 11:30:33', '2025-06-18 11:30:33'),
(10, 'timer', 'timer', '65', '25365425', '1159874563', 1, 0, '2025-06-23 13:52:16', '2025-06-23 17:54:50'),
(11, 'migue', 'migue', '37', '12586236', '1153256982', 1, 0, '2025-06-23 14:33:11', '2025-06-23 17:55:17'),
(12, 'noe', 'noe', '25', '25365258', '1156325489', 1, 0, '2025-06-23 14:47:41', '2025-06-23 17:54:38'),
(13, 'migue', 'migue', '', '12586236', '1153256982', 1, 0, '2025-06-23 17:28:17', '2025-06-23 17:28:17'),
(14, 'timer', 'timer', '56', '25365425', '1159874563', 1, 0, '2025-06-23 17:48:09', '2025-06-23 17:48:09'),
(15, 'timer', 'timer', '56', '25365425', '1159874563', 1, 0, '2025-06-23 17:48:23', '2025-06-23 17:48:23'),
(16, 'migue', 'migue', '33', '12586236', '1153256982', 1, 0, '2025-06-23 17:49:18', '2025-06-23 17:49:18'),
(17, 'noe', 'noe', '25', '25365258', '1156325489', 1, 0, '2025-06-23 17:49:53', '2025-06-23 17:49:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precio`
--

CREATE TABLE `precio` (
  `id_precio` int(6) NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habiltado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `precio` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cancha` int(11) NOT NULL,
  `id_fecha` int(11) NOT NULL,
  `id_precio` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_roles` int(11) NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_roles`, `idCreate`, `idUpdate`, `habilitado`, `cancelado`, `rol`) VALUES
(1, '2025-05-15 03:54:58', '2025-05-15 03:54:58', 1, 0, 'Dueño'),
(2, '2025-05-15 04:04:06', '2025-06-13 17:35:32', 1, 0, 'Adminstrador'),
(3, '0000-00-00 00:00:00', '2025-05-15 04:06:18', 1, 0, 'Bar'),
(4, '2025-05-15 04:05:25', '2025-05-15 04:05:25', 1, 0, 'Alquiler'),
(5, '0000-00-00 00:00:00', '2025-05-15 04:07:47', 1, 0, 'Estacionamiento'),
(6, '2025-06-17 18:32:24', '2025-06-17 18:32:24', 1, 0, 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(4) NOT NULL,
  `email` varchar(20) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `clave`, `id_persona`, `idUpdate`, `idCreate`, `habilitado`, `cancelado`) VALUES
(1, '', '$2y$10$R', 4, '2025-05-16 02:04:25', '2025-05-16 02:04:25', 1, 0),
(2, 'sebastianminotti@gma', '$2y$10$l', 5, '2025-05-16 02:09:28', '2025-05-16 02:09:28', 1, 0),
(3, 'ifts12@gmail.com', '$2y$10$9mNLL2PTd1EdRdXMtnZRRuw.iI6/sxpOF2tcURI3iYgQQLE8hGH82', 6, '2025-05-16 02:44:42', '2025-05-16 02:44:42', 1, 0),
(4, 'sebaminotti@gmail.co', '$2y$10$B9ed.7vpX6jGUVsk2uvr6ePSV25xt4fNMKA0zMPapkxPMbCmHZy2W', 7, '2025-06-17 18:26:25', '2025-06-17 18:26:25', 1, 0),
(5, 'ahorasi@gmail.com', '$2y$10$wiqY.b1CEKKqZpEyTmD/LO30aiuhIjOfEWdDcCNTcIbbPFVuCBw46', 8, '2025-06-17 18:35:03', '2025-06-17 18:35:03', 1, 0),
(6, 'sem@gmail.com', '$2y$10$zW0dmMWkN2Da1KLxFg58ceguki.Cq8OqjX5dpuRPA1ncUbR3.gmv.', 9, '2025-06-18 11:30:33', '2025-06-18 11:30:33', 1, 0),
(7, 'timer@gmail.com', '$2y$10$Mjbd9at6qCPH4WZdUKbiX.Pnr.lThX4SlD7gTfVf5TAUwv3JYSGc6', 10, '2025-06-23 13:52:17', '2025-06-23 13:52:17', 1, 0),
(8, 'migue@yahoo.com', '$2y$10$bYkA0.wHjuTX8Xlb.u23KucWUY42wcReqEBpX69pYoOMVxD3YN3jm', 11, '2025-06-23 14:33:11', '2025-06-23 14:33:11', 1, 0),
(9, 'noe@gmail.com', '$2y$10$EumntS6j/g5P32jJXs05wuO1kZEOcr7qc7nMyo7IyqZ6Mc7cCpgw2', 12, '2025-06-23 14:47:41', '2025-06-23 14:47:41', 1, 0),
(10, 'migue@yahoo.com', '$2y$10$bYkA0.wHjuTX8Xlb.u23KucWUY42wcReqEBpX69pYoOMVxD3YN3jm', 11, '2025-06-23 17:28:17', '2025-06-23 17:28:17', 1, 0),
(11, 'timer@gmail.com', '$2y$10$Mjbd9at6qCPH4WZdUKbiX.Pnr.lThX4SlD7gTfVf5TAUwv3JYSGc6', 10, '2025-06-23 17:48:09', '2025-06-23 17:48:09', 1, 0),
(12, 'timer@gmail.com', '$2y$10$Mjbd9at6qCPH4WZdUKbiX.Pnr.lThX4SlD7gTfVf5TAUwv3JYSGc6', 10, '2025-06-23 17:48:23', '2025-06-23 17:48:23', 1, 0),
(13, 'migue@yahoo.com', '$2y$10$bYkA0.wHjuTX8Xlb.u23KucWUY42wcReqEBpX69pYoOMVxD3YN3jm', 11, '2025-06-23 17:49:18', '2025-06-23 17:49:18', 1, 0),
(14, 'noe@gmail.com', '$2y$10$EumntS6j/g5P32jJXs05wuO1kZEOcr7qc7nMyo7IyqZ6Mc7cCpgw2', 12, '2025-06-23 17:49:53', '2025-06-23 17:49:53', 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD PRIMARY KEY (`id_cancha`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `id_rol` (`id_rol`,`id_persona`,`id_usuario`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `fecha`
--
ALTER TABLE `fecha`
  ADD PRIMARY KEY (`id_fecha`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `precio`
--
ALTER TABLE `precio`
  ADD PRIMARY KEY (`id_precio`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cancha` (`id_cancha`),
  ADD KEY `id_fecha` (`id_fecha`),
  ADD KEY `id_precio` (`id_precio`),
  ADD KEY `id_horario` (`id_horario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_roles`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_persona` (`id_persona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cancha`
--
ALTER TABLE `cancha`
  MODIFY `id_cancha` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `fecha`
--
ALTER TABLE `fecha`
  MODIFY `id_fecha` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `precio`
--
ALTER TABLE `precio`
  MODIFY `id_precio` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_roles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_fecha`) REFERENCES `fecha` (`id_fecha`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_precio`) REFERENCES `precio` (`id_precio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_4` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_5` FOREIGN KEY (`id_cancha`) REFERENCES `cancha` (`id_cancha`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
