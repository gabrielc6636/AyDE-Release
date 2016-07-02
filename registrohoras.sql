-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-06-2016 a las 21:35:47
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registrohoras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion`
--

CREATE TABLE `asignacion` (
  `id_asignacion` int(11) NOT NULL,
  `id_proyecto` int(2) NOT NULL,
  `id_usuario` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `asignacion`
--

INSERT INTO `asignacion` (`id_asignacion`, `id_proyecto`, `id_usuario`) VALUES
(4, 1, 8),
(5, 2, 8),
(7, 1, 1),
(9, 2, 1),
(11, 1, 9),
(12, 2, 9),
(14, 1, 10),
(15, 2, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargahoras`
--

CREATE TABLE `cargahoras` (
  `id_cargahoras` int(11) NOT NULL,
  `id_proyecto` int(3) NOT NULL,
  `id_usuario` int(3) NOT NULL,
  `id_semana` int(2) NOT NULL,
  `horas` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id_proyecto` int(11) NOT NULL,
  `proyecto` varchar(255) NOT NULL,
  `inactivo` tinyint(1) DEFAULT NULL,
  `proyecto_id_tipo` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id_proyecto`, `proyecto`, `inactivo`, `proyecto_id_tipo`) VALUES
(1, 'AUSENCIA', NULL, 2),
(2, 'INTERNO', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semanas`
--

CREATE TABLE `semanas` (
  `id_semana` int(11) NOT NULL,
  `semana` varchar(3) NOT NULL,
  `horas_habiles` int(2) NOT NULL,
  `mes` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `semanas`
--

INSERT INTO `semanas` (`id_semana`, `semana`, `horas_habiles`, `mes`) VALUES
(1, 'S1', 40, 1),
(2, 'S2', 40, 1),
(3, 'S3', 40, 1),
(4, 'S4', 40, 1),
(5, 'S5', 40, 2),
(6, 'S6', 40, 2),
(7, 'S7', 40, 2),
(8, 'S8', 40, 2),
(9, 'S9', 40, 3),
(11, 'S11', 40, 3),
(12, 'S12', 40, 3),
(13, 'S13', 40, 3),
(14, 'S14', 40, 4),
(15, 'S15', 40, 4),
(16, 'S16', 40, 4),
(17, 'S17', 40, 4),
(18, 'S18', 40, 5),
(19, 'S19', 40, 5),
(20, 'S20', 40, 5),
(21, 'S21', 40, 5),
(22, 'S22', 40, 6),
(23, 'S23', 40, 6),
(24, 'S24', 40, 6),
(25, 'S25', 40, 6),
(26, 'S26', 40, 6),
(27, 'S27', 40, 7),
(28, 'S28', 40, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proyecto`
--

CREATE TABLE `tipo_proyecto` (
  `id_tipo` int(11) NOT NULL,
  `tipo_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_proyecto`
--

INSERT INTO `tipo_proyecto` (`id_tipo`, `tipo_description`) VALUES
(1, 'Interno'),
(2, 'Ausencias'),
(4, 'Desarrollo'),
(5, 'Capacitacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `user_rol` int(11) NOT NULL DEFAULT '2',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT 'root',
  `sueldo` decimal(5,0) NOT NULL,
  `costo` int(5) NOT NULL,
  `costo_semanal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `user_rol`, `email`, `password`, `sueldo`, `costo`, `costo_semanal`) VALUES
(1, 'desarrollador1', 2, 'desarrollador1@gmail.com', 'desarrollador1', '1000', 1408, 352),
(2, 'administrativo', 9, 'administrativo@gmail.com', 'administrativo', '1000', 1408, 352),
(3, 'pmo', 5, 'pmo@gmail.com', 'pmo', '1200', 1690, 423);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`id_asignacion`);

--
-- Indices de la tabla `cargahoras`
--
ALTER TABLE `cargahoras`
  ADD PRIMARY KEY (`id_cargahoras`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id_proyecto`);

--
-- Indices de la tabla `semanas`
--
ALTER TABLE `semanas`
  ADD PRIMARY KEY (`id_semana`);

--
-- Indices de la tabla `tipo_proyecto`
--
ALTER TABLE `tipo_proyecto`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `cargahoras`
--
ALTER TABLE `cargahoras`
  MODIFY `id_cargahoras` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id_proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `semanas`
--
ALTER TABLE `semanas`
  MODIFY `id_semana` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT de la tabla `tipo_proyecto`
--
ALTER TABLE `tipo_proyecto`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
