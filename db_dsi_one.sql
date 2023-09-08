-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-09-2023 a las 23:27:51
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_dsi_one`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_dsi`
--

CREATE TABLE `clientes_dsi` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `dui` varchar(10) NOT NULL,
  `fecha_nac` date NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `direccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes_dsi`
--

INSERT INTO `clientes_dsi` (`id`, `nombre`, `apellido`, `dui`, `fecha_nac`, `telefono`, `email`, `direccion`) VALUES
(1, 'Jorge', 'Lopez', '05034604-8', '2000-07-13', '6895-8360', 'jorgelopz@example.com', 'Barrio El Centro, Soyapango'),
(2, 'Antonio', 'Rivera', '02930604-9', '1998-04-17', '7995-8502', 'antonio@example.com', 'Barrio El Centro, Sonsonate'),
(3, 'Juan ', 'Lopez', '05030604-9', '2002-07-26', '6895-8502', 'juan@example.com', 'Barrio El Calvario, Sonsonate'),
(4, 'Dennys', 'Garcia', '00330604-9', '1999-06-30', '7895-8963', 'dennys@example.com', 'Miralvalle, San Salvador'),
(5, 'Luis', 'Moran', '01030604-9', '2002-05-29', '7269-8502', 'luismoran@example.com', 'Barrio El Calvario, La Libertad'),
(6, 'Juan ', 'Rivera', '05120604-7', '1998-08-14', '7895-0002', 'juanrive@example.com', 'Lolotique, San Miguel'),
(7, 'Maria', 'Torres', '05030123-6', '1998-04-10', '7295-8112', 'mariatorres@example.com', 'Barrio El Calvario, La Union'),
(8, 'Sarai', 'Guerrero', '08960604-9', '1994-11-18', '7195-8502', 'sarai@example.com', 'Barrio El Centro, San Luis'),
(9, 'Veronica', 'Marmol', '05031304-6', '1995-12-29', '7495-8508', 'veronicam@example.com', 'Bosques Del Rio, Soyapango'),
(12, 'Karen', 'Rivera', '03060908-9', '1997-11-20', '7860-9633', 'karenrivera@example.com', 'California, Usulutan');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditos_dsi`
--

CREATE TABLE `creditos_dsi` (
  `id` int(11) NOT NULL,
  `num_credito` varchar(10) DEFAULT NULL,
  `dui` varchar(11) DEFAULT NULL,
  `nombre_completo` varchar(255) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `tipo_pago` varchar(255) DEFAULT NULL,
  `fecha_ini` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `plazo` int(11) DEFAULT NULL,
  `interes` decimal(10,2) DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `monto_pendiente` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `creditos_dsi`
--

INSERT INTO `creditos_dsi` (`id`, `num_credito`, `dui`, `nombre_completo`, `monto`, `tipo_pago`, `fecha_ini`, `fecha_fin`, `plazo`, `interes`, `monto_total`, `monto_pendiente`) VALUES
(1, 'DSI-00001', '02930604-9', 'Antonio Rivera', '1000.00', 'mensual', '2023-06-08', '0000-00-00', 9, '0.05', '1050.00', '116.67'),
(2, 'DSI-00002', '01030604-9', 'Luis Moran', '600.00', 'quincenal', '2023-06-08', '0000-00-00', 3, '0.03', '618.00', '103.00'),
(4, 'DSI-00003', '05031304-6', 'Veronica Marmol', '6000.00', 'quincenal', '2023-06-09', '0000-00-00', 12, '0.05', '6300.00', '262.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_dsi`
--

CREATE TABLE `pagos_dsi` (
  `id` int(11) NOT NULL,
  `num_credito` varchar(10) DEFAULT NULL,
  `dui` varchar(11) DEFAULT NULL,
  `nombre_completo` varchar(255) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `monto_pago` decimal(10,2) DEFAULT NULL,
  `monto_pendiente` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_dsi`
--

INSERT INTO `pagos_dsi` (`id`, `num_credito`, `dui`, `nombre_completo`, `fecha_pago`, `monto_pago`, `monto_pendiente`) VALUES
(1, 'DSI-00001', '02930604-9', 'Antonio', '2023-06-13', '116.57', '116.67'),
(2, 'DSI-00001', '02930604-9', 'Antonio', '2023-06-13', '116.67', '116.67'),
(3, 'DSI-00001', '02930604-9', 'Antonio', '2023-06-13', '116.67', '116.67'),
(4, 'DSI-00001', '02930604-9', 'Antonio', '2023-06-13', '116.67', '116.67'),
(5, 'DSI-00002', '01030604-9', 'Luis', '2023-06-13', '103.00', '103.00'),
(6, 'DSI-00003', '05031304-6', 'Veronica', '2023-06-14', '262.50', '262.50'),
(7, 'DSI-00003', '05031304-6', 'Veronica', '2023-06-14', '262.50', '262.50'),
(8, 'DSI-00003', '05031304-6', 'Veronica', '2023-06-13', '262.50', '262.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_dsi`
--

CREATE TABLE `usuarios_dsi` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(25) NOT NULL,
  `correo` varchar(100) NOT NULL DEFAULT '',
  `codigo_recuperacion` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_dsi`
--

INSERT INTO `usuarios_dsi` (`id`, `username`, `password`, `correo`, `codigo_recuperacion`) VALUES
(1, 'hgarcia', 'GB15026', 'gb15026@ues.edu.sv', NULL),
(2, 'rongonza', 'AG00026', 'ag00026@ues.edu.sv', NULL),
(3, 'jenherrera', '123456', 'hr08032@ues.edu.sv', 'fcda5e'),
(4, 'dangarcia', 'GT19011', 'gt19011@ues.edu.sv', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes_dsi`
--
ALTER TABLE `clientes_dsi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dui` (`dui`);

--
-- Indices de la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_num_credito` (`num_credito`);

--
-- Indices de la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes_dsi`
--
ALTER TABLE `clientes_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
