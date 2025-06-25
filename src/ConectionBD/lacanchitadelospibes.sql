-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-06-2025 a las 01:55:55
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
  `precio` int(10) NOT NULL,
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
  `cancelado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cancha`
--

INSERT INTO `cancha` (`id_cancha`, `nombreCancha`, `precio`, `idUpdate`, `idCreate`, `habilitado`, `cancelado`) VALUES
(1, 'monumental', 100000, '2025-06-24 23:52:18', '2025-05-04 20:03:13', 1, 0),
(2, 'bombonera', 100, '2025-06-24 23:52:29', '2025-05-04 20:03:13', 1, 0),
(3, 'Fortin', 90000, '2025-06-24 23:53:10', '2025-05-04 20:03:28', 1, 0),
(4, 'Cilindro', 80000, '2025-06-24 23:53:28', '2025-05-04 20:03:28', 1, 0),
(5, 'Gasometro', 70000, '2025-06-24 23:53:41', '2025-05-04 20:05:32', 1, 0),
(6, 'Palacio', 60000, '2025-06-24 23:54:21', '2025-05-04 20:05:56', 1, 0);

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
(1, 1, 4, 1, 1, 0, '2025-05-15 23:04:25', '2025-05-15 23:04:25'),
(2, 1, 5, 2, 0, 1, '2025-05-15 23:09:28', '2025-05-17 23:41:38'),
(3, 5, 6, 3, 1, 0, '2025-05-15 23:44:42', '2025-06-10 21:49:15'),
(4, 1, 9, 6, 0, 1, '2025-05-17 18:07:34', '2025-05-22 20:07:27'),
(5, 4, 10, 7, 0, 1, '2025-05-17 23:41:27', '2025-06-10 21:49:26'),
(6, 1, 11, 8, 0, 1, '2025-05-18 21:54:49', '2025-06-17 01:11:31'),
(7, 1, 12, 9, 0, 1, '2025-05-24 23:50:46', '2025-05-28 01:27:08'),
(8, 1, 13, 10, 0, 1, '2025-05-28 01:27:45', '2025-05-28 22:02:18'),
(9, 2, 14, 11, 0, 1, '2025-06-08 23:02:15', '2025-06-17 00:53:19'),
(10, 2, 18, 15, 0, 1, '2025-06-10 01:20:03', '2025-06-10 21:45:17'),
(11, 1, 18, 15, 0, 1, '2025-06-10 01:20:03', '2025-06-10 20:02:11'),
(12, 3, 19, 16, 0, 1, '2025-06-10 20:04:35', '2025-06-17 01:24:16'),
(13, 2, 20, 17, 1, 0, '2025-06-10 21:52:25', '2025-06-22 21:26:23'),
(14, 1, 20, 17, 0, 1, '2025-06-10 21:52:25', '2025-06-17 00:57:55'),
(15, 1, 21, 18, 1, 0, '2025-06-22 21:27:47', '2025-06-22 21:27:47'),
(16, 6, 22, 19, 1, 0, '2025-06-22 21:43:54', '2025-06-22 21:43:54'),
(17, 6, 23, 20, 1, 0, '2025-06-22 22:46:58', '2025-06-22 22:46:58'),
(18, 6, 24, 21, 1, 0, '2025-06-22 22:48:23', '2025-06-22 22:48:23'),
(19, 6, 25, 22, 1, 0, '2025-06-22 22:58:29', '2025-06-22 22:58:29'),
(20, 6, 26, 23, 1, 0, '2025-06-23 20:04:49', '2025-06-23 20:04:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha`
--

CREATE TABLE `fecha` (
  `id_fecha` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `habilitado` int(11) NOT NULL DEFAULT 1,
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
  `habilitado` int(11) NOT NULL DEFAULT 1,
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
(1, '', '', '', '', '', 1, 0, '2025-05-15 22:51:26', '2025-05-15 22:51:26'),
(2, '', '', '', '', '', 1, 0, '2025-05-15 22:52:19', '2025-05-15 22:52:19'),
(3, '', '', '', '', '', 1, 0, '2025-05-15 23:02:26', '2025-05-15 23:02:26'),
(4, 'Dueño', 'Dueño', '52', '52365478', '1125896325', 1, 0, '2025-05-15 23:04:24', '2025-05-22 20:07:11'),
(5, 'mino', 'seba', '46', '26589756', '1156321455', 1, 0, '2025-05-15 23:09:28', '2025-05-15 23:09:28'),
(6, 'mino', 'seba', '56', '25879023', '3354658955', 1, 0, '2025-05-15 23:44:42', '2025-05-15 23:44:42'),
(7, 'dueño', 'dueño', '54', '123654789', '1125647896', 1, 0, '2025-05-17 17:55:34', '2025-05-17 17:55:34'),
(8, 'mio', 'mio', '25', '32145698', '1123654789', 1, 0, '2025-05-17 17:58:30', '2025-05-17 17:58:30'),
(9, 'mio2', 'mio2', '25', '26145896', '1164563258', 1, 0, '2025-05-17 18:07:34', '2025-05-17 18:07:34'),
(10, 'mio3', 'mio3', '65', '456231456', '1123654789', 1, 0, '2025-05-17 23:41:27', '2025-05-28 01:27:27'),
(11, 'pablo', 'pablo', '56', '23258796', '1126456987', 1, 0, '2025-05-18 21:54:49', '2025-05-18 21:54:49'),
(12, 'apellido', 'nombre', '56', '32589632', '1145698745', 1, 0, '2025-05-24 23:50:45', '2025-05-24 23:50:45'),
(13, 'nuevo', 'nuevo', '56', '23654789', '1163258965', 1, 0, '2025-05-28 01:27:45', '2025-05-28 01:28:14'),
(14, 'hash', 'hash', '56', '23654789', '1163258964', 1, 0, '2025-06-08 23:02:14', '2025-06-08 23:02:14'),
(15, 'seba', 'seba', '23', '32589654', '1156478963', 1, 0, '2025-06-09 22:32:45', '2025-06-09 22:32:45'),
(16, 'nueva', 'otra', '23', '65258741', '1116589654', 1, 0, '2025-06-09 23:29:56', '2025-06-09 23:29:56'),
(17, 'guarda', 'guarda', '25', '32564789', '1123654785', 1, 0, '2025-06-10 01:03:57', '2025-06-10 01:03:57'),
(18, 'ahora', 'ahora', '23', '25698741', '1123654789', 1, 0, '2025-06-10 01:20:03', '2025-06-10 01:20:03'),
(19, 'admin', 'admin', '23', '12258965', '1123654785', 1, 0, '2025-06-10 20:04:35', '2025-06-10 20:04:35'),
(20, 'hola', 'hola', '23', '25365478', '1123654785', 1, 0, '2025-06-10 21:52:25', '2025-06-10 21:52:25'),
(21, 'dueño', 'dueño', '45', '12258746', '1165284563', 1, 0, '2025-06-22 21:27:47', '2025-06-22 21:27:47'),
(22, 'cliente', 'cliente', '', '1125325896', '1126547896', 1, 0, '2025-06-22 21:43:54', '2025-06-22 21:43:54'),
(23, 'otro', 'otro', '524', '', '1123652365', 1, 0, '2025-06-22 22:46:58', '2025-06-22 22:46:58'),
(24, 'casa', 'casa', '253', '25365478', '1125874569', 1, 0, '2025-06-22 22:48:23', '2025-06-23 20:04:16'),
(25, 'ultimo', 'ultimo', '23', '25852456', '1165874569', 1, 0, '2025-06-22 22:58:29', '2025-06-23 20:03:43'),
(26, 'ver', 'ver', '', '52365478', '2251123654', 1, 0, '2025-06-23 20:04:49', '2025-06-23 20:04:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cancha` int(11) NOT NULL,
  `id_fecha` int(11) NOT NULL,
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
(0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(1, '2025-05-15 00:54:58', '2025-05-15 00:54:58', 1, 0, 'Dueño'),
(2, '2025-05-15 01:04:06', '2025-05-25 02:04:08', 1, 0, 'Adminstrador'),
(3, '0000-00-00 00:00:00', '2025-05-15 01:06:18', 1, 0, 'Bar'),
(4, '2025-05-15 01:05:25', '2025-05-15 01:05:25', 1, 0, 'Alquiler'),
(5, '0000-00-00 00:00:00', '2025-05-15 01:07:47', 1, 0, 'Estacionamiento'),
(6, '2025-06-10 01:13:04', '2025-06-10 01:15:06', 1, 0, 'Cliente');

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
(1, 'capo@gmail.com', '12345678', 4, '2025-05-24 23:48:10', '2025-05-15 23:04:25', 1, 0),
(2, 'sebastianminotti@gma', '$2y$10$l', 5, '2025-05-15 23:09:28', '2025-05-15 23:09:28', 1, 0),
(3, 'ifts12@gmail.com', '$2y$10$Iqze/hz.32WJEtIcr/6ruurPog3pw3QLl6bG7DRNQ1LQQ.sN/mkFG', 6, '2025-06-10 21:49:15', '2025-05-15 23:44:42', 1, 0),
(4, 'dueno@gmial.com', '$2y$10$DbBO1J1yBRuogUmC5.kBU.hJ8klUQpfKHBVJUR20rM./M2rRg61Oe', 7, '2025-05-17 17:55:35', '2025-05-17 17:55:35', 1, 0),
(5, 'mio@hotmail.com', '$2y$10$ufsoyrJLDMHij7wVPDUQQeezzfYK4CD8zIv1a.OHL1A7w/OU5UwXm', 8, '2025-05-17 17:58:30', '2025-05-17 17:58:30', 1, 0),
(6, 'mio2@yahoo.com', '$2y$10$v9zMaawsOOUu0Dz6.Z.fuOSFrgIZOYOpNP5uKV3MzisaalPjmHH/u', 9, '2025-05-17 18:07:34', '2025-05-17 18:07:34', 1, 0),
(7, 'mio3@hotmail.com', '$2y$10$aeTF3vODDmQR8xWwUaO1KubTfL10oJU2IkZd9p8mwkV7kViDfZQDS', 10, '2025-05-17 23:41:27', '2025-05-17 23:41:27', 1, 0),
(8, 'pablo@gmail.com', '$2y$10$ZK0M9hr/P22w.Qh29vRgAeO.YyfeYgE9CwKdkvVaRRN4t17HUHKYC', 11, '2025-05-18 21:54:49', '2025-05-18 21:54:49', 1, 0),
(9, 'desdeListado@yahoo.c', '$2y$10$AU0iAL4czqN4zC9EhbQR3OOnC97QlwKIHQ3ZcsFfemA2zO18a3Qi.', 12, '2025-05-24 23:50:46', '2025-05-24 23:50:46', 1, 0),
(10, 'nuevo@yahoo.com', '$2y$10$D3Yp9pMFYdBvepgO0dRuOeH7nrg9rYg.DiMQ7MKFcvfj771AyMr.W', 13, '2025-05-28 01:27:45', '2025-05-28 01:27:45', 1, 0),
(11, 'hash@gmail.com', '$2y$10$jZ24drtD0VMuGv5QN7YAx.KW/DexYw/pXqsC9n.YRNBJC/.h38nJm', 14, '2025-06-09 23:51:21', '2025-06-08 23:02:15', 1, 0),
(12, 'pruebacasa@gmail.com', '$2y$10$pfIrcjuU2efdUlNMv3xJ7e2Vp7PCWRSI8d9yjTcu9LicqMEzbHPmK', 15, '2025-06-09 22:32:45', '2025-06-09 22:32:45', 1, 0),
(13, 'otranueva@yahoo.com', '$2y$10$MsXn6aX3nzEdbVv1qjqh2.OyuGaWCyqPbhLflFMbdqEFSI3a8DXnC', 16, '2025-06-09 23:29:56', '2025-06-09 23:29:56', 1, 0),
(14, 'guarda@gmail.com', '$2y$10$RIzTrTtqPOPjfmaLo.RuGe9y/Fh5RVB/mPQ7olOG8u.F4DGccx1zC', 17, '2025-06-10 01:03:57', '2025-06-10 01:03:57', 1, 0),
(15, 'ahora@gmail.com', '$2y$10$AQIP2WH8ot17m9S8gDt9xOclJrDl7M63l4N9YaGlhzuByula/RVHe', 18, '2025-06-10 20:02:04', '2025-06-10 01:20:03', 1, 0),
(16, 'admin@gmail.com', '$2y$10$uFZagF6TaMsm4Z1SvYYxPuMsJi.Pdr.XDnIL6639veAIF25bIpVXq', 19, '2025-06-10 21:45:32', '2025-06-10 20:04:35', 1, 0),
(17, 'paraprobar@yahoo.com', '$2y$10$9AeHzQ.73iazqkePaq15vOT6vgIHpcT6OjHWzpe9uypYAaiLHXO8C', 20, '2025-06-10 21:52:25', '2025-06-10 21:52:25', 1, 0),
(18, 'usuario-dueno@gmail.', '$2y$10$VfSTHiEopZhiWY7xPLRyM.ROnTpwCEYVURCtlJKme5ExF7yerGgFO', 21, '2025-06-22 21:27:47', '2025-06-22 21:27:47', 1, 0),
(19, 'cliente@gmail.com', '$2y$10$XDjJ1tu1vsu/Bdnn4EnPKuc7ueS3IPogiHokHFaFDEJFlTFuTI9ue', 22, '2025-06-22 21:43:54', '2025-06-22 21:43:54', 1, 0),
(20, 'otro@gmail.com', '$2y$10$gcBJxVPxFptFa7uzFjJHpOr.ko.JWlfdWAPrg6A9YDvuO3iSGxqwO', 23, '2025-06-22 22:46:58', '2025-06-22 22:46:58', 1, 0),
(21, 'casa@gmail.com', '$2y$10$jPkhBQXFlOeQclbTd5Pynuj/3N146CxBa6zUiPMwHR6d5yuDRHFqW', 24, '2025-06-22 22:48:23', '2025-06-22 22:48:23', 1, 0),
(22, 'ultimo@yahoo.com', '$2y$10$KiA48lmNZuQK7G2m9oLMoOAaPBdz4ssEkRKbPFwQxUkHIklmFS4VK', 25, '2025-06-22 22:58:29', '2025-06-22 22:58:29', 1, 0),
(23, 'ver@yahoo.com', '$2y$10$jBr18sq8.p8vvDQ9ZGr3CukfaA9XYEMYd/hH/xNY8gZCH/sMe35Cm', 26, '2025-06-23 20:04:49', '2025-06-23 20:04:49', 1, 0);

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
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cancha` (`id_cancha`),
  ADD KEY `id_fecha` (`id_fecha`),
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
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `id_usuario` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  ADD CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_4` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_5` FOREIGN KEY (`id_cancha`) REFERENCES `cancha` (`id_cancha`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
