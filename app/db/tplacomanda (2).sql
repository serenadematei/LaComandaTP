-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2022 a las 14:03:39
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tplacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `id` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `idSocio` int(11) NOT NULL,
  `idMozo` int(11) NOT NULL,
  `codigoPedido` varchar(100) NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`id`, `idMesa`, `idSocio`, `idMozo`, `codigoPedido`, `foto`) VALUES
(10, 1, 1, 6, 'A0100', '/MesaNumero1.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `estado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `estado`) VALUES
(1, 'Cerrada'),
(2, 'Disponible'),
(3, 'Disponible'),
(4, 'Disponible'),
(5, 'Disponible'),
(6, 'Disponible'),
(7, 'Disponible'),
(8, 'Disponible'),
(9, 'Disponible'),
(10, 'Disponible'),
(11, 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `nombreCliente` varchar(100) NOT NULL,
  `idMozo` int(11) NOT NULL,
  `idSocio` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `codigoPedido` varchar(100) NOT NULL,
  `tipoProducto` varchar(100) DEFAULT NULL,
  `tiempoInicio` varchar(100) DEFAULT NULL,
  `tiempoFinalizacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `estado`, `nombreCliente`, `idMozo`, `idSocio`, `idEmpleado`, `idProducto`, `idMesa`, `codigoPedido`, `tipoProducto`, `tiempoInicio`, `tiempoFinalizacion`) VALUES
(22, 'Pago', 'Juan Cruz', 6, 1, 12, 1, 1, 'A0100', 'Comida', '13:54:06', '14:27:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productopedido`
--

CREATE TABLE `productopedido` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(100) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productopedido`
--

INSERT INTO `productopedido` (`id`, `codigoPedido`, `idProducto`, `idEmpleado`) VALUES
(22, 'A0100', 1, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `minutosPreparacion` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `tipoProducto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `minutosPreparacion`, `precio`, `tipoProducto`) VALUES
(1, 'Taco de verduras', 33, 460, 'Comida'),
(2, 'Daikiri', 10, 200, 'Bebida'),
(3, 'Papas fritas', 30, 350, 'Comida'),
(4, 'Quilmes', 5, 210, 'Cerveza'),
(5, 'Stella Artois', 5, 260, 'Cerveza'),
(6, 'Heineken', 5, 180, 'Cerveza'),
(7, 'Corona', 5, 250, 'Cerveza'),
(8, 'Milanesa a caballo', 40, 800, 'Comida'),
(9, 'Hamburguesa de garbanzo', 42, 850, 'Comida'),
(10, 'Sprite', 10, 150, 'Bebida'),
(11, 'Coca Cola', 10, 150, 'Bebida'),
(12, 'Gin tonic', 10, 150, 'Bebida'),
(14, 'Pizza Napolitana', 35, 1100, 'Comida'),
(15, 'Risotto', 42, 850, 'Comida'),
(16, 'Picada', 15, 750, 'Comida'),
(17, 'Blue coracao', 10, 300, 'Bebida'),
(18, 'Latte Vainilla', 10, 155, 'Bebida'),
(19, 'Patagonia', 12, 470, 'Cerveza'),
(21, 'Empanada capresse', 32, 150, 'Comida'),
(33, 'Empanada de carne', 36, 150, 'Comida'),
(34, 'Empanada de pollo', 37, 150, 'Comida'),
(36, 'Ensalada de frutas', 20, 300, 'Comida'),
(37, 'Vino rosado', 4, 690, 'Bebida'),
(38, 'Vino blanco', 4, 630, 'Bebida'),
(39, 'Champagne', 5, 900, 'Bebida'),
(40, 'Champagne', 5, 900, 'Bebida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `perfil` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `perfil`, `usuario`, `clave`) VALUES
(1, 'socio', 'Mariano', '$2y$10$J5bD3vGmt/oyJgUDlnAAeeazkX.FC4qlhDgP.MxDFwoD71T2m5ZI2'),
(2, 'socio', 'Franco', '$2y$10$PkBiVjL8wsXsm7IkM1tW6uFl3rYecWW9Jo3TxQqJ3CVPbLJ6yThyC'),
(3, 'socio', 'Silvina', '$2y$10$vy44mxOdTK7Mikva4xG27ORtW1ThKwWi0C2c5uaDxENdJwr/14rnK'),
(4, 'mozo', 'Francisco', '$2y$10$ZLUBgG81e2wmAWFLS/q5wOQB/w1WiFRIJw/Oy8ujfynwWX8pe723i'),
(5, 'mozo', 'Lucas', '$2y$10$zlOgvRiSlhPLkg6TEej50e4J4HQ4Ge7e9APvQBNIYGhGxVVU.eULq'),
(6, 'mozo', 'Serena', '$2y$10$8kvVji/G0Wwo1Bhsp6iKsuZtKcot98FFIzYoChaYFbfeZCfw.s2Ui'),
(7, 'bartender', 'Martina', '$2y$10$l/G6wQ8yGc2KSq/DSj6Os.1VPGboDsP6DdZZRFt.HbSSxraqDmSCy'),
(8, 'bartender', 'Gerardo', '$2y$10$1M7r6/H8UutyRbEdDwQ8nOBk2ZEj2kC3efTY1BGscK6J64C04tOhi'),
(9, 'bartender', 'Lucia', '$2y$10$NT4ztUkpZTUNsPjXiNYca.XZfhrhPLrmr77auuo9F7r9jKfGEkuXO'),
(10, 'cocinero', 'Rosa', '$2y$10$7DiE6dFo1y18ttVCdeg3g.qJdEJbzvg3jeaynr4JdoprGETcW5k.2'),
(11, 'cocinero', 'Raul', '$2y$10$Lbin0L.8U6X6Xl1vA4oT4.naZg7j0a/bVgLNFXDtPjypwMdGOWpvy'),
(12, 'cocinero', 'Fiona', '$2y$10$Jqpp0gBlb567OXh1VGBXVeFWFkdmxwQgC64sSQvLGEH8qM5LPPAl.'),
(13, 'cervecero', 'Tomas', '$2y$10$u/w/V5pUUp4S7IuxfPzCiegRM67.3nFDtIrCAVPDrqrQjMMi1mVwC'),
(14, 'cervecero', 'Valentino', '$2y$10$05Sf.L9YuLUCIaooybYvse.M7VsrtWquZlfQa4WrPZH1RzrMWsa/u'),
(15, 'cervecero', 'Manuel', '$2y$10$0KrYGSRZBHF0KN1tn54UL.zFb3uS7rJe.csGZjH4F4MmtPRBvkk.W');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
