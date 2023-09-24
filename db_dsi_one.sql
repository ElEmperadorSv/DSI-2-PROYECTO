-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-09-2023 a las 08:47:37
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
-- Estructura de tabla para la tabla `categorias_dsi`
--

CREATE TABLE `categorias_dsi` (
  `id_cat` int(3) NOT NULL,
  `nombre_cat` varchar(20) NOT NULL,
  `descripcion_cat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_dsi`
--

INSERT INTO `categorias_dsi` (`id_cat`, `nombre_cat`, `descripcion_cat`) VALUES
(1, 'Sala', 'Muebles de Sala'),
(2, 'Dormitorio', 'Muebles de dormitorio'),
(3, 'Comedor', 'Muebles de cocina'),
(4, 'Oficina', 'Muebles de oficina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_dsi`
--

CREATE TABLE `clientes_dsi` (
  `id_ct` int(11) NOT NULL,
  `dui_ct` int(11) NOT NULL,
  `nombre_ct` varchar(30) NOT NULL,
  `apellido_ct` varchar(30) NOT NULL,
  `nombre_completo_ct` varchar(60) NOT NULL,
  `fecha_nac_ct` date NOT NULL,
  `telefono_ct` int(8) NOT NULL,
  `email_ct` varchar(30) NOT NULL,
  `direccion_ct` varchar(50) NOT NULL,
  `estado_ct` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes_dsi`
--

INSERT INTO `clientes_dsi` (`id_ct`, `dui_ct`, `nombre_ct`, `apellido_ct`, `nombre_completo_ct`, `fecha_nac_ct`, `telefono_ct`, `email_ct`, `direccion_ct`, `estado_ct`) VALUES
(1, 123456789, 'Jorge', 'Lopez', 'Jorge Lopez', '2000-07-13', 68958360, 'jorgelopz@example.com', 'Barrio El Centro, Soyapango', 'ACTIVO'),
(2, 293060489, 'Antonio L.', 'Rivera', 'Antonio L. Rivera', '1998-04-17', 79958502, 'antonio@example.com', 'Barrio El Centro, Sonsonate', 'INACTIVO'),
(3, 293060488, 'Juan ', 'Lopez', 'Juan  Lopez', '2002-07-26', 68958502, 'juan@example.com', 'Barrio El Calvario, Sonsonate', 'ACTIVO'),
(4, 330604452, 'Dennys', 'Garcia', 'Dennys Garcia', '1999-06-30', 78958963, 'dennys@example.com', 'Miralvalle, San Salvador', 'ACTIVO'),
(5, 103060412, 'Luis', 'Moran', 'Luis Moran', '2002-05-29', 72694502, 'luismoran@example.com', 'Barrio El Calvario, La Libertad', 'ACTIVO'),
(6, 512060454, 'Juan ', 'Rivera', 'Juan  Rivera', '1998-08-14', 78950002, 'juanrive@example.com', 'Lolotique, San Miguel', 'ACTIVO'),
(7, 503012336, 'Maria', 'Torres', 'Maria Torres', '1998-04-10', 72958112, 'mariatorres@example.com', 'Barrio El Calvario, La Union', 'ACTIVO'),
(8, 896060461, 'Sarai', 'Guerrero', 'Sarai Guerrero', '1994-11-18', 71958566, 'sarai@example.com', 'Barrio El Centro, San Luis', 'ACTIVO'),
(9, 503130412, 'Veronica', 'Marmol', 'Veronica Marmol', '1995-12-29', 74958508, 'veronicam@example.com', 'Bosques Del Rio, Soyapango', 'ACTIVO'),
(10, 306090836, 'Mauri', 'Rivera', 'Mauri Rivera', '1997-11-20', 78609633, 'karenrivera@example.com', 'California, Usulutan', 'ACTIVO'),
(11, 561859556, 'Joel Alejandro', 'Garcia', 'Joel Alejandro Garcia', '2023-01-02', 75755050, 'joel@example.com', 'Colonia Bella Vista, Santa Ana', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditos_dsi`
--

CREATE TABLE `creditos_dsi` (
  `id` int(11) NOT NULL,
  `dui_ct` int(11) NOT NULL,
  `cliente` varchar(30) NOT NULL,
  `num_credito` varchar(10) NOT NULL,
  `producto` varchar(30) NOT NULL,
  `cantidad_producto` int(4) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `interes` decimal(10,2) NOT NULL,
  `plazo` int(11) NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `monto_pendiente` decimal(10,2) NOT NULL,
  `tipo_pago` enum('Mensual','','') NOT NULL DEFAULT 'Mensual',
  `fecha_ini` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado_credito` enum('ACTIVO','FINALIZADO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `creditos_dsi`
--

INSERT INTO `creditos_dsi` (`id`, `dui_ct`, `cliente`, `num_credito`, `producto`, `cantidad_producto`, `monto`, `interes`, `plazo`, `monto_total`, `monto_pendiente`, `tipo_pago`, `fecha_ini`, `fecha_fin`, `estado_credito`) VALUES
(8, 123456789, 'Jorge Lopez', 'DSI-00008', 'Ropero', 1, 100.00, 0.05, 1, 105.00, 105.00, 'Mensual', '2023-09-21', '2023-10-21', 'FINALIZADO');

--
-- Disparadores `creditos_dsi`
--
DELIMITER $$
CREATE TRIGGER `actualiza_estado_credito` BEFORE UPDATE ON `creditos_dsi` FOR EACH ROW BEGIN
    IF NEW.monto_pendiente = 0 THEN
        SET NEW.estado_credito = 'FINALIZADO';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_dsi`
--

CREATE TABLE `pagos_dsi` (
  `id_pago` int(11) NOT NULL,
  `num_credito` varchar(20) NOT NULL,
  `cuota` int(4) NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pago` decimal(10,2) NOT NULL,
  `estado_pago` enum('PENDIENTE','REALIZADO','EN MORA') NOT NULL DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_dsi`
--

INSERT INTO `pagos_dsi` (`id_pago`, `num_credito`, `cuota`, `fecha_pago`, `monto_pago`, `estado_pago`) VALUES
(1, 'DSI-00008', 0, '2023-06-13', 116.57, ''),
(2, 'DSI-00008', 0, '2023-06-13', 116.67, ''),
(3, 'DSI-00008', 0, '2023-06-13', 116.67, ''),
(4, 'DSI-00008', 0, '2023-06-13', 116.67, ''),
(5, 'DSI-00008', 0, '2023-06-13', 103.00, ''),
(6, 'DSI-00008', 0, '2023-06-14', 262.50, ''),
(7, 'DSI-00008', 0, '2023-06-14', 262.50, ''),
(8, 'DSI-00008', 0, '2023-06-13', 262.50, ''),
(9, 'DSI-00008', 0, '2023-06-15', 62.50, ''),
(10, 'DSI-00008', 0, '2023-06-15', 25.75, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_dsi`
--

CREATE TABLE `productos_dsi` (
  `id_pd` int(4) NOT NULL,
  `nombre_pd` varchar(30) NOT NULL,
  `descripcion_pd` varchar(50) NOT NULL,
  `stock_pd` int(4) NOT NULL DEFAULT 1,
  `categoria_pd` varchar(10) NOT NULL,
  `precio_pd` decimal(8,2) DEFAULT NULL,
  `estado_pd` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `imagen` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_dsi`
--

INSERT INTO `productos_dsi` (`id_pd`, `nombre_pd`, `descripcion_pd`, `stock_pd`, `categoria_pd`, `precio_pd`, `estado_pd`, `imagen`) VALUES
(1, 'Ropero', 'Guardar Ropa xd', 1, 'Oficina', 100.00, 'ACTIVO', '../Complementos/Imagenes/1ea4a0ce-eaa0-4bfd-9ea9-e04e6540b1f2.jpg'),
(4, 'PRUEBA', 'ds', 1, 'Dormitorio', 0.00, 'ACTIVO', '../Complementos/Imagenes/6a49b04ae7be5990a242b173a514e22c.jpg'),
(5, 'Escritorio', 'mamalon xd', 3, 'Oficina', 100.89, 'ACTIVO', '../Complementos/Imagenes/6a49b04ae7be5990a242b173a514e22c.jpg'),
(6, 'silla', 'dsad', 1, 'Oficina', 5.00, 'ACTIVO', '../Complementos/Imagenes/6a49b04ae7be5990a242b173a514e22c.jpg'),
(7, 'dasd', 'dsad', 1, 'Dormitorio', 1.00, 'ACTIVO', '../Complementos/Imagenes/6a49b04ae7be5990a242b173a514e22c.jpg'),
(8, 'silla', 'da', 1, 'Oficina', 0.05, 'ACTIVO', '../Complementos/Imagenes/1ea4a0ce-eaa0-4bfd-9ea9-e04e6540b1f2.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_usuarios`
--

CREATE TABLE `roles_usuarios` (
  `id` int(3) NOT NULL,
  `nombre_rol` varchar(25) NOT NULL,
  `descripcion_rol` varchar(50) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_usuarios`
--

INSERT INTO `roles_usuarios` (`id`, `nombre_rol`, `descripcion_rol`, `estado`) VALUES
(1, 'Administrador', 'prueba', 'INACTIVO'),
(2, 'Depto. de Contabilidad', 'Rol que permite ver el detalle de créditos, produc', 'ACTIVO'),
(3, 'Cajero', 'Gestiona los créditos, usuarios, etc', 'ACTIVO'),
(7, 'Prueba', 'Prueba', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_dsi`
--

CREATE TABLE `usuarios_dsi` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(25) NOT NULL,
  `carne` varchar(10) NOT NULL,
  `email` varchar(25) NOT NULL,
  `codigo_recuperacion` varchar(6) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_dsi`
--

INSERT INTO `usuarios_dsi` (`id`, `username`, `password`, `carne`, `email`, `codigo_recuperacion`, `estado`, `rol`) VALUES
(1, 'admin', 'admin', 'ADMIN', 'admin@admin', NULL, 'ACTIVO', 'Administrador'),
(2, 'hgarcia', 'GB15026', 'GB15026', 'gb15026@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(3, 'rongonza', 'AG00026', 'AG00026', 'ag00026@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(4, 'jenherrera', 'HR08032', 'HR08032', 'hr08032@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(5, 'dangarcia', 'GT19011', 'GT19011', 'gt19011@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(7, 'ejemplo', 'ejemplo', 'pruebe', 'ejemplo@gmail.com', NULL, 'ACTIVO', 'Cajero');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_dsi`
--
ALTER TABLE `categorias_dsi`
  ADD PRIMARY KEY (`id_cat`),
  ADD KEY `nombre_cat` (`nombre_cat`);

--
-- Indices de la tabla `clientes_dsi`
--
ALTER TABLE `clientes_dsi`
  ADD PRIMARY KEY (`id_ct`),
  ADD UNIQUE KEY `telefono` (`telefono_ct`),
  ADD UNIQUE KEY `email` (`email_ct`),
  ADD UNIQUE KEY `dui_ct` (`dui_ct`),
  ADD KEY `nombre_completo_ct` (`nombre_completo_ct`),
  ADD KEY `estado_ct` (`estado_ct`),
  ADD KEY `dui_ct_2` (`dui_ct`);

--
-- Indices de la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_num_credito` (`num_credito`),
  ADD KEY `producto` (`producto`),
  ADD KEY `cantidad_producto` (`cantidad_producto`),
  ADD KEY `monto` (`monto`),
  ADD KEY `plazo` (`plazo`),
  ADD KEY `monto_total` (`monto_total`),
  ADD KEY `monto_pendiente` (`monto_pendiente`),
  ADD KEY `tipo_pago` (`tipo_pago`),
  ADD KEY `fecha_ini` (`fecha_ini`),
  ADD KEY `fecha_fin` (`fecha_fin`),
  ADD KEY `estado_credito` (`estado_credito`),
  ADD KEY `cliente` (`cliente`),
  ADD KEY `dui_ct` (`dui_ct`);

--
-- Indices de la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `num_credito` (`num_credito`),
  ADD KEY `cuota` (`cuota`),
  ADD KEY `fecha_pago` (`fecha_pago`),
  ADD KEY `monto_pago` (`monto_pago`),
  ADD KEY `estado_pago` (`estado_pago`);

--
-- Indices de la tabla `productos_dsi`
--
ALTER TABLE `productos_dsi`
  ADD PRIMARY KEY (`id_pd`),
  ADD KEY `nombre_pd` (`nombre_pd`),
  ADD KEY `stock_pd` (`stock_pd`),
  ADD KEY `categoria_pd` (`categoria_pd`),
  ADD KEY `precio_pd` (`precio_pd`),
  ADD KEY `estado_pd` (`estado_pd`);

--
-- Indices de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombre_rol` (`nombre_rol`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carne` (`carne`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `estado` (`estado`),
  ADD KEY `username` (`username`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_dsi`
--
ALTER TABLE `categorias_dsi`
  MODIFY `id_cat` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes_dsi`
--
ALTER TABLE `clientes_dsi`
  MODIFY `id_ct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `productos_dsi`
--
ALTER TABLE `productos_dsi`
  MODIFY `id_pd` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  ADD CONSTRAINT `creditos_dsi_ibfk_1` FOREIGN KEY (`producto`) REFERENCES `productos_dsi` (`nombre_pd`),
  ADD CONSTRAINT `creditos_dsi_ibfk_2` FOREIGN KEY (`dui_ct`) REFERENCES `clientes_dsi` (`dui_ct`),
  ADD CONSTRAINT `creditos_dsi_ibfk_3` FOREIGN KEY (`cliente`) REFERENCES `clientes_dsi` (`nombre_completo_ct`);

--
-- Filtros para la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  ADD CONSTRAINT `pagos_dsi_ibfk_1` FOREIGN KEY (`num_credito`) REFERENCES `creditos_dsi` (`num_credito`);

--
-- Filtros para la tabla `productos_dsi`
--
ALTER TABLE `productos_dsi`
  ADD CONSTRAINT `productos_dsi_ibfk_1` FOREIGN KEY (`categoria_pd`) REFERENCES `categorias_dsi` (`nombre_cat`);

--
-- Filtros para la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  ADD CONSTRAINT `usuarios_dsi_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles_usuarios` (`nombre_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
