-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-11-2026 a las 04:20:59
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
-- Base de datos: `gool`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio1` int(11) NOT NULL,
  `precio2` int(11) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `url_3d` varchar(255) NOT NULL,
  `imagen_qr` varchar(255) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio1`, `precio2`, `imagen`, `url_3d`, `imagen_qr`, `cantidad`) VALUES
(6, 'Huawei Freebuds 3 Pro', 3000, 2699, 'imgg/Freebuds 3 pro.png', 'https://mywebar.com/p/Project_2_pkcmaeiks8', 'imgg/qr/Freebuds 3pro.png', 8),
(7, 'Airpods Pro 2', 2200, 1999, 'imgg/Airpods pro 2.png', 'https://mywebar.com/p/Project_4_np1wlvirbu', 'imgg/qr/Air pods2.png', 15),
(8, 'Samsung Buds Pro 3', 3500, 3200, 'imgg/Buds pro 3.png', 'https://mywebar.com/p/Project_1_13ngeecmx0', 'imgg/qr/Samsung buds pro 3.png', 20),
(9, 'Fundas en Pareja', 700, 500, 'imgg/Fundas pareja.png', 'https://mywebar.com/p/Project_4_s82sfb5kly', 'imgg/qr/Fundas en parajea.png', 16),
(10, 'Funda Transparente ', 400, 350, 'imgg/Funda transparente.png', 'https://mywebar.com/p/Project_0_8y1ome6eo2', 'imgg/qr/Funda transparente.png', 27),
(12, 'Chip Movistar con Saldo', 100, 100, 'imgg/Chip Movistar.png', 'https://mywebar.com/p/Project_3_oacl6eqvh2', 'imgg/qr/Chip Movistar.png', 31),
(14, 'Reloj Azul', 700, 650, 'imgg/sirblue.png', 'https://mywebar.com/p/Project_4_iqjed1cax', 'imgg/qr/Reloj Azul.png', 20),
(23, 'Relog Azul Marino', 990, 800, 'imgg/knocknap.png', 'https://mywebar.com/p/Project_0_jwqmulezxs', 'imgg/qr/Reloj Negro.png.jpg', 29),
(24, 'Relog Oscuro', 990, 900, 'imgg/lanight.png', 'https://mywebar.com/p/Project_1_n89rpluz0h', 'imgg/qr/Reloj Negro oscuro.jpg', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id_promocion` int(11) NOT NULL,
  `nombre_promocion` varchar(50) NOT NULL,
  `precio_original` float NOT NULL,
  `precio_descuento` float NOT NULL,
  `imag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`id_promocion`, `nombre_promocion`, `precio_original`, `precio_descuento`, `imag`) VALUES
(1, 'Funfas 2x1', 700, 600, 'imgg/IMG_3666.PNG'),
(2, 'Audifonos Sony ', 750, 550, 'imgg/IMG_3659.PNG'),
(3, 'Cargador Iphone', 650, 500, 'imgg/IMG_3672.PNG'),
(4, 'Audifonos Beats azules', 1300, 800, 'imgg/ph3.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `huella_dactilar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `huella_dactilar`) VALUES
(1, 'admin', 'admin123', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id_promocion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
