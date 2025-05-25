-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-05-2025 a las 16:39:14
-- Versión del servidor: 10.6.22-MariaDB-0ubuntu0.22.04.1
-- Versión de PHP: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bikeneke`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesorios`
--

CREATE TABLE `accesorios` (
  `id_accesorio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_hora` decimal(6,2) DEFAULT 0.00,
  `precio_dia` decimal(6,2) DEFAULT 0.00,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `accesorios`
--

INSERT INTO `accesorios` (`id_accesorio`, `nombre`, `precio_hora`, `precio_dia`, `imagen`) VALUES
(1, 'Casco', 0.00, 0.00, 'casco.jpg'),
(2, 'Cesta', 0.00, 0.00, 'cesta.jpg'),
(10, 'Remolque', 2.00, 12.00, 'remolque.jpg'),
(11, 'Portabebé', 3.00, 15.00, 'portabebe1.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administracion`
--

CREATE TABLE `administracion` (
  `id_admin` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administracion`
--

INSERT INTO `administracion` (`id_admin`, `nombre_usuario`, `email`, `password_hash`, `creado_en`) VALUES
(3, 'Gestor', 'gestion@bikeneke.com', '$2y$10$nRfzS7.F68XT27z/yqmDGu/j7P/Q/5urAH0ZANMz2XvKHXd81bCQ.', '2025-05-20 15:07:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alojamientos`
--

CREATE TABLE `alojamientos` (
  `id_alojamiento` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `id_tipo_alojamiento` int(11) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `codigo_postal` varchar(10) DEFAULT '17300',
  `localidad` varchar(100) DEFAULT 'Blanes',
  `provincia` varchar(100) DEFAULT 'Girona',
  `pais` varchar(100) DEFAULT 'España'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alojamientos`
--

INSERT INTO `alojamientos` (`id_alojamiento`, `nombre`, `id_tipo_alojamiento`, `direccion`, `codigo_postal`, `localidad`, `provincia`, `pais`) VALUES
(1, 'Hotel Beverly Park & Spa', 1, 'Merce Rodoreda, 7', '17300', 'Blanes', 'Girona', 'España'),
(2, 'Hotel Horitzo by Pierre & Vacances', 1, 'Paseo Marítimo S´Abanell 11', '17300', 'Blanes', 'Girona', 'España'),
(3, 'Hotel Blaucel', 1, 'Avenida Villa de Madrid, 27', '17300', 'Blanes', 'Girona', 'España'),
(4, 'Hotel Costa Brava', 1, 'Anselm Clavé, 48', '17300', 'Blanes', 'Girona', 'España'),
(5, 'Hotel Stella Maris', 1, 'Avenida Vila de Madrid, 18', '17300', 'Blanes', 'Girona', 'España'),
(6, 'Hotel Pimar & Spa', 1, 'Paseo S\'Abanell 8', '17300', 'Blanes', 'Girona', 'España'),
(7, 'Hostal Miranda', 1, 'Josep Tarradellas, 50', '17300', 'Blanes', 'Girona', 'España'),
(8, 'Hotel Boix Mar', 1, 'Enric Morera, 5', '17300', 'Blanes', 'Girona', 'España'),
(10, 'Hotel Esplendid', 1, 'Avenida Mediterrani, 17', '17300', 'Blanes', 'Girona', 'España'),
(11, 'Camping Bella Terra', 2, 'Avinguda Vila de Madrid, s/n', '17300', 'Blanes', 'Girona', 'España'),
(12, 'Camping Blanes', 2, 'Carrer Cristòfor Colom, 48', '17300', 'Blanes', 'Girona', 'España'),
(13, 'Camping La Masia', 2, 'Carrer Colom, 44', '17300', 'Blanes', 'Girona', 'España'),
(14, 'Apartaments AR Blavamar - San Marcos', 3, 'Carrer Josep Tarradellas, 2', '17300', 'Blanes', 'Girona', 'España'),
(15, 'Apartamentos Europa Sun', 3, 'Av. Mediterrani, 6', '17300', 'Blanes', 'Girona', 'España');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `suplemento` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `categoria`, `suplemento`) VALUES
(6, 'Montaña', 3.00),
(7, 'Tándem', 2.00),
(9, 'Paseo', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `apellidos_cliente` varchar(100) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_alojamiento` int(11) DEFAULT NULL,
  `hora_entrega` time NOT NULL,
  `hora_recogida` time NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `archivo_pdf` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `numero_factura` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id_factura`, `nombre_cliente`, `apellidos_cliente`, `dni`, `telefono`, `email`, `id_alojamiento`, `hora_entrega`, `hora_recogida`, `subtotal`, `iva`, `total`, `archivo_pdf`, `fecha`, `numero_factura`) VALUES
(1, 'Vladimir', 'Vostok Gorbachov', '34567856R', '622313245', 'delete@standardinet.com', 3, '11:17:00', '15:17:00', 252.00, 52.92, 304.92, 'factura_20250523_101747_1686.pdf', '2025-05-23 10:17:47', NULL),
(2, 'Vladimir', 'Vostok Gorbachov', '34567856R', '622313245', 'delete@standardinet.com', 11, '11:20:00', '15:20:00', 256.00, 53.76, 309.76, 'factura_20250523_102023_2358.pdf', '2025-05-23 10:20:23', NULL),
(3, 'Vladimir', 'Vostok Gorbachov', '34567856R', '622313245', 'delete@standardinet.com', 8, '11:27:00', '15:27:00', 252.00, 52.92, 304.92, 'factura_20250523_102814_5021.pdf', '2025-05-23 10:28:14', NULL),
(4, 'Vladimir', 'Vostok Gorbachov', '34567856R', '622313245', 'delete@standardinet.com', 12, '11:36:00', '15:36:00', 256.00, 53.76, 309.76, 'factura_20250523_103635_7363.pdf', '2025-05-23 10:36:35', NULL),
(5, 'Susana', 'Amore Amore', '12345678E', '622313245', 'saludos@standardinet.com', 15, '12:33:00', '16:33:00', 223.00, 46.83, 269.83, 'factura_20250523_113440_1894.pdf', '2025-05-23 11:34:40', 'FA-2025-0005'),
(6, 'Susana', 'Amore Amore', '12345678E', '622313245', 'saludos@standardinet.com', 1, '13:02:00', '14:02:00', 15.00, 3.15, 18.15, 'factura_20250523_120236_9235.pdf', '2025-05-23 12:02:36', 'FA-2025-0006'),
(7, 'Susana', 'Amore Amore', '12345678E', '622313245', 'saludos@standardinet.com', 3, '11:53:00', '11:53:00', 43.00, 9.03, 52.03, 'factura_20250524_110258_7497.pdf', '2025-05-24 11:02:58', NULL),
(8, 'Susana', 'Amore Amore', '12345678E', '622313245', 'saludos@standardinet.com', 3, '11:53:00', '11:53:00', 43.00, 9.03, 52.03, 'factura_20250524_110312_8845.pdf', '2025-05-24 11:03:12', NULL),
(9, 'Susana', 'Amore Amore', '12345678E', '622313245', 'saludos@standardinet.com', 3, '11:53:00', '11:53:00', 43.00, 9.03, 52.03, 'factura_20250524_110641_3153.pdf', '2025-05-24 11:06:41', NULL),
(10, 'Susana', 'Amore Amore', '12345678E', '622313245', 'saludos@standardinet.com', 3, '11:53:00', '11:53:00', 58.00, 12.18, 70.18, 'factura_20250524_111351_9627.pdf', '2025-05-24 11:13:51', NULL),
(11, 'Vladimir', 'Vostok Gorbachov', '12345678E', '622313245', 'saludos@standardinet.com', 8, '15:00:00', '15:00:00', 59.00, 12.39, 71.39, 'factura_20250524_140323_9829.pdf', '2025-05-24 14:03:23', NULL),
(12, 'Vladimir', 'Vostok Gorbachov', '12345678E', '622313245', 'saludos@standardinet.com', 8, '15:00:00', '15:00:00', 138.00, 28.98, 166.98, 'factura_20250524_140803_4277.pdf', '2025-05-24 14:08:03', NULL),
(13, 'Vladimir', 'Vostok Gorbachov', '12345678E', '622313245', 'saludos@standardinet.com', 8, '15:00:00', '15:00:00', 539.00, 113.19, 652.19, 'factura_20250524_141032_7066.pdf', '2025-05-24 14:10:32', NULL),
(14, 'Vladimir', 'Vostok Gorbachov', '12345678E', '622313245', 'saludos@standardinet.com', 4, '15:00:00', '15:00:00', 784.00, 164.64, 948.64, 'factura_20250524_142345_4786.pdf', '2025-05-24 14:23:45', NULL),
(15, 'Vladimir', 'Vostok Gorbachov', '12345678E', '622313245', 'saludos@standardinet.com', 4, '15:00:00', '15:00:00', 130.00, 27.30, 157.30, 'factura_20250524_142926_9025.pdf', '2025-05-24 14:29:26', NULL),
(16, 'Vladimir', 'Vostok Gorbachov', '12345678E', '622313245', 'saludos@standardinet.com', 4, '15:00:00', '15:00:00', 709.00, 148.89, 857.89, 'factura_20250524_143348_6713.pdf', '2025-05-24 14:33:48', NULL),
(17, 'Manolo', 'Escobar', '16580444R', '622313299', 'saludos@standardinet.com', 5, '15:00:00', '15:00:00', 276.00, 57.96, 333.96, 'factura_20250524_145357_9114.pdf', '2025-05-24 14:53:57', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_accesorio`
--

CREATE TABLE `factura_accesorio` (
  `id_accesorio_factura` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `modo` varchar(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura_accesorio`
--

INSERT INTO `factura_accesorio` (`id_accesorio_factura`, `id_factura`, `nombre`, `modo`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 'Casco', 'hora', 5, 0.00, 0.00),
(2, 2, 'Casco', 'hora', 5, 0.00, 0.00),
(3, 3, 'Casco', 'hora', 5, 0.00, 0.00),
(4, 4, 'Casco', 'hora', 5, 0.00, 0.00),
(5, 5, 'Casco', 'hora', 4, 0.00, 0.00),
(6, 5, 'Portabebé', 'hora', 5, 3.00, 15.00),
(7, 10, 'Portabebé', 'dia', 1, 15.00, 15.00),
(8, 11, 'Portabebé', 'hora', 1, 3.00, 3.00),
(9, 12, 'Cesta', 'dia', 2, 0.00, 0.00),
(10, 13, 'Portabebé', 'dia', 5, 15.00, 75.00),
(11, 13, 'Remolque', 'dia', 5, 12.00, 60.00),
(12, 14, 'Cesta', 'dia', 5, 0.00, 0.00),
(13, 14, 'Portabebé', 'dia', 5, 15.00, 75.00),
(14, 14, 'Remolque', 'dia', 5, 12.00, 60.00),
(15, 15, 'Remolque', 'hora', 5, 2.00, 10.00),
(16, 16, 'Portabebé', 'dia', 5, 15.00, 75.00),
(17, 16, 'Remolque', 'dia', 5, 12.00, 60.00),
(18, 17, 'Portabebé', 'hora', 2, 3.00, 6.00),
(19, 17, 'Remolque', 'dia', 2, 12.00, 24.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_servicio`
--

CREATE TABLE `factura_servicio` (
  `id_servicio` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura_servicio`
--

INSERT INTO `factura_servicio` (`id_servicio`, `id_factura`, `nombre`, `precio`) VALUES
(1, 1, 'Entrega en tienda', 0.00),
(2, 1, 'Recogida en tienda', 0.00),
(3, 2, 'Entrega en tienda', 0.00),
(4, 2, 'Recogida en alojamiento', 4.00),
(5, 3, 'Entrega en tienda', 0.00),
(6, 3, 'Recogida en tienda', 0.00),
(7, 4, 'Entrega en tienda', 0.00),
(8, 4, 'Recogida en alojamiento', 4.00),
(9, 5, 'Entrega en tienda', 0.00),
(10, 5, 'Recogida en alojamiento', 4.00),
(11, 6, 'Entrega en tienda', 0.00),
(12, 6, 'Recogida en tienda', 0.00),
(13, 7, 'Entrega en tienda', 0.00),
(14, 7, 'Recogida en tienda', 0.00),
(15, 8, 'Entrega en tienda', 0.00),
(16, 8, 'Recogida en tienda', 0.00),
(17, 9, 'Entrega en tienda', 0.00),
(18, 9, 'Recogida en tienda', 0.00),
(19, 10, 'Entrega en tienda', 0.00),
(20, 10, 'Recogida en tienda', 0.00),
(21, 11, 'Entrega en tienda', 0.00),
(22, 11, 'Recogida en tienda', 0.00),
(23, 12, 'Entrega en tienda', 0.00),
(24, 12, 'Recogida en tienda', 0.00),
(25, 13, 'Entrega en alojamiento', 4.00),
(26, 13, 'Recogida en tienda', 0.00),
(27, 14, 'Entrega en tienda', 0.00),
(28, 14, 'Recogida en alojamiento', 4.00),
(29, 15, 'Entrega en tienda', 0.00),
(30, 15, 'Recogida en tienda', 0.00),
(31, 16, 'Entrega en alojamiento', 4.00),
(32, 16, 'Recogida en tienda', 0.00),
(33, 17, 'Entrega en alojamiento', 4.00),
(34, 17, 'Recogida en alojamiento', 4.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas_factura`
--

CREATE TABLE `lineas_factura` (
  `id_linea` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `duracion` varchar(10) NOT NULL,
  `unidades` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lineas_factura`
--

INSERT INTO `lineas_factura` (`id_linea`, `id_factura`, `tipo`, `categoria`, `duracion`, `unidades`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 'Niño', 'Montaña', 'hora', 4, 2, 36.00, 72.00),
(2, 1, 'Adulto', 'Montaña', 'hora', 4, 3, 60.00, 180.00),
(3, 2, 'Niño', 'Montaña', 'hora', 4, 2, 36.00, 72.00),
(4, 2, 'Adulto', 'Montaña', 'hora', 4, 3, 60.00, 180.00),
(5, 3, 'Niño', 'Montaña', 'hora', 4, 2, 36.00, 72.00),
(6, 3, 'Adulto', 'Montaña', 'hora', 4, 3, 60.00, 180.00),
(7, 4, 'Niño', 'Montaña', 'hora', 4, 2, 36.00, 72.00),
(8, 4, 'Adulto', 'Montaña', 'hora', 4, 3, 60.00, 180.00),
(9, 5, 'Niño', 'Montaña', 'hora', 4, 1, 36.00, 36.00),
(10, 5, 'Teenager', 'Montaña', 'hora', 4, 1, 48.00, 48.00),
(11, 5, 'Adulto', 'Montaña', 'hora', 4, 2, 60.00, 120.00),
(12, 6, 'Adulto', 'Montaña', 'hora', 1, 1, 15.00, 15.00),
(13, 7, 'Adulto', 'Montaña', 'dia', 1, 1, 43.00, 43.00),
(14, 8, 'Adulto', 'Montaña', 'dia', 1, 1, 43.00, 43.00),
(15, 9, 'Adulto', 'Montaña', 'dia', 1, 1, 43.00, 43.00),
(16, 10, 'Adulto', 'Montaña', 'dia', 1, 1, 43.00, 43.00),
(17, 11, 'Niño', 'Montaña', 'dia', 2, 1, 56.00, 56.00),
(18, 12, 'Niño', 'Tándem', 'dia', 2, 1, 54.00, 54.00),
(19, 12, 'Adulto', 'Tándem', 'dia', 2, 1, 84.00, 84.00),
(20, 13, 'Adulto', 'Paseo', 'dia', 5, 2, 200.00, 400.00),
(21, 14, 'Adulto', 'Montaña', 'dia', 5, 3, 215.00, 645.00),
(22, 15, 'Teenager', 'Montaña', 'hora', 5, 2, 60.00, 120.00),
(23, 16, 'Adulto', 'Montaña', 'dia', 5, 2, 215.00, 430.00),
(24, 16, 'Niño', 'Montaña', 'dia', 5, 1, 140.00, 140.00),
(25, 17, 'Teenager', 'Montaña', 'dia', 2, 1, 66.00, 66.00),
(26, 17, 'Adulto', 'Montaña', 'dia', 2, 2, 86.00, 172.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `id_tipo` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `precio_hora` decimal(5,2) NOT NULL DEFAULT 0.00,
  `precio_dia` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos`
--

INSERT INTO `tipos` (`id_tipo`, `tipo`, `precio_hora`, `precio_dia`) VALUES
(6, 'Teenager', 9.00, 30.00),
(7, 'Adulto', 12.00, 40.00),
(9, 'Niño', 6.00, 25.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_alojamiento`
--

CREATE TABLE `tipo_alojamiento` (
  `id_tipo_alojamiento` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_alojamiento`
--

INSERT INTO `tipo_alojamiento` (`id_tipo_alojamiento`, `descripcion`) VALUES
(1, 'Hotel'),
(2, 'Camping'),
(3, 'Aparthotel'),
(4, 'Motel'),
(5, 'Hostal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pagos`
--

CREATE TABLE `tipo_pagos` (
  `id_pago` int(11) NOT NULL,
  `tipo_pago` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_pagos`
--

INSERT INTO `tipo_pagos` (`id_pago`, `tipo_pago`) VALUES
(7, 'Bizum'),
(8, 'Tarjeta débito'),
(10, 'Efectivo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesorios`
--
ALTER TABLE `accesorios`
  ADD PRIMARY KEY (`id_accesorio`);

--
-- Indices de la tabla `administracion`
--
ALTER TABLE `administracion`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  ADD PRIMARY KEY (`id_alojamiento`),
  ADD KEY `id_tipo_alojamiento` (`id_tipo_alojamiento`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `id_alojamiento` (`id_alojamiento`);

--
-- Indices de la tabla `factura_accesorio`
--
ALTER TABLE `factura_accesorio`
  ADD PRIMARY KEY (`id_accesorio_factura`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `factura_servicio`
--
ALTER TABLE `factura_servicio`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `lineas_factura`
--
ALTER TABLE `lineas_factura`
  ADD PRIMARY KEY (`id_linea`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipo_alojamiento`
--
ALTER TABLE `tipo_alojamiento`
  ADD PRIMARY KEY (`id_tipo_alojamiento`);

--
-- Indices de la tabla `tipo_pagos`
--
ALTER TABLE `tipo_pagos`
  ADD PRIMARY KEY (`id_pago`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesorios`
--
ALTER TABLE `accesorios`
  MODIFY `id_accesorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `administracion`
--
ALTER TABLE `administracion`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  MODIFY `id_alojamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `factura_accesorio`
--
ALTER TABLE `factura_accesorio`
  MODIFY `id_accesorio_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `factura_servicio`
--
ALTER TABLE `factura_servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `lineas_factura`
--
ALTER TABLE `lineas_factura`
  MODIFY `id_linea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tipo_alojamiento`
--
ALTER TABLE `tipo_alojamiento`
  MODIFY `id_tipo_alojamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_pagos`
--
ALTER TABLE `tipo_pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  ADD CONSTRAINT `alojamientos_ibfk_1` FOREIGN KEY (`id_tipo_alojamiento`) REFERENCES `tipo_alojamiento` (`id_tipo_alojamiento`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_alojamiento`) REFERENCES `alojamientos` (`id_alojamiento`);

--
-- Filtros para la tabla `factura_accesorio`
--
ALTER TABLE `factura_accesorio`
  ADD CONSTRAINT `factura_accesorio_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE;

--
-- Filtros para la tabla `factura_servicio`
--
ALTER TABLE `factura_servicio`
  ADD CONSTRAINT `factura_servicio_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lineas_factura`
--
ALTER TABLE `lineas_factura`
  ADD CONSTRAINT `lineas_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
