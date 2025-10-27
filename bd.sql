-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2025 a las 15:19:27
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
-- Base de datos: `centro_salud_ttio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atenciones`
--

CREATE TABLE `atenciones` (
  `id_atencion` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_profesional` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `id_usuario_caja` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `turno` enum('MAÑANA','TARDE') NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `numero_ticket_diario` int(11) NOT NULL,
  `hora_turno_estimada` time DEFAULT NULL,
  `estado` enum('PENDIENTE','REALIZADA','CANCELADA') NOT NULL DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atencion_servicios`
--

CREATE TABLE `atencion_servicios` (
  `id_atencion` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `consultorio` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`, `consultorio`, `descripcion`, `activo`) VALUES
(1, 'Psicología', '302', NULL, 1),
(2, 'Obstetricia Planificación Familiar', '301', NULL, 1),
(3, 'Obstetricia Materno', '202', NULL, 1),
(4, 'Odontología', '304', NULL, 1),
(5, 'Terapia Física y Rehabilitación', '306', NULL, 1),
(6, 'Laboratorio', '208', NULL, 1),
(7, 'Tópico', '105', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `ultima_visita` datetime DEFAULT NULL,
  `eliminado_suavemente` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesionales`
--

CREATE TABLE `profesionales` (
  `id_profesional` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesionales`
--

INSERT INTO `profesionales` (`id_profesional`, `id_especialidad`, `dni`, `nombre_completo`, `telefono`, `activo`) VALUES
(1, 4, 'DNI11111111', 'Vilma Vargas Contreras', NULL, 1),
(2, 4, 'DNI22222222', 'Heidi Guia Abarca', NULL, 1),
(3, 4, 'DNI33333333', 'Sadith Rosa Cruz', NULL, 1),
(4, 4, 'DNI44444444', 'Leo O. Martiarena Huayhua', NULL, 1),
(5, 3, 'DNI55555555', 'Cesar Laura Mamani (G)', NULL, 1),
(6, 2, 'DNI66666666', 'Jackeline Astete Berdejo', NULL, 1),
(7, 2, 'DNI77777777', 'Cristal Flores Olave', NULL, 1),
(8, 2, 'DNI88888888', 'Marisol Condori Pezo', NULL, 1),
(9, 2, 'DNI99999999', 'Lisbet Jordán Palacios', NULL, 1),
(10, 2, 'DNI10101010', 'Tania Zapata Carreño', NULL, 1),
(11, 2, 'DNI12121212', 'Shirai E. Ortiz Marocho', NULL, 1),
(12, 1, 'DNI13131313', 'Evelyn Molina Navarrete', NULL, 1),
(13, 1, 'DNI14141414', 'Wendy M. Ramirez Ramirez (SERUMS)', NULL, 1),
(14, 5, 'DNI15151515', 'Maribel Jauja Gomez (SERUMS)', NULL, 1),
(15, 5, 'DNI16161616', 'Daniel E. Mandujano Vergara (SERUMS)', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `id_especialidad`, `descripcion`, `precio_unitario`, `activo`) VALUES
(1, 6, 'MAMOGRAFIA', 20.00, 1),
(2, 6, 'INFORME LEGAL DE RAYOS X', 15.00, 1),
(3, 6, 'INS. PROVEEDOR DE SERVICIO', 30.00, 1),
(4, 6, 'INSCRIP. PROVEEDORES GRAL.', 30.00, 1),
(5, 6, 'ACIDO URICO', 8.00, 1),
(6, 6, 'AGLUTINACIONES WIDALL', 8.00, 1),
(7, 6, 'AMILASA SERICA', 5.00, 1),
(8, 6, 'ANTIESTREPTOLISINAS ASO', 15.00, 1),
(9, 6, 'ANTIGENO AUSTRALIANO', 15.00, 1),
(10, 6, 'AZUL DE METILENO', 4.00, 1),
(11, 6, 'BENEDICT', 4.00, 1),
(12, 6, 'BILIRRUBINAS TOTAL Y FRAC', 8.00, 1),
(13, 6, 'BK EN ESPUTO,ORINA,ETC', 0.00, 1),
(14, 6, 'CALCIO SERICO', 6.00, 1),
(15, 6, 'COLESTEROL HDL', 8.00, 1),
(16, 6, 'COLESTEROL LDL', 8.00, 1),
(17, 6, 'COLESTEROL TOTAL', 8.00, 1),
(18, 6, 'COLORACION GRAM', 5.00, 1),
(19, 6, 'CONSTANTES CORPUSCULARES', 5.00, 1),
(20, 6, 'COPROFUNCIONAL', 30.00, 1),
(21, 6, 'CREATININA', 8.00, 1),
(22, 6, 'DEPURACION DE CREATININA', 6.00, 1),
(23, 6, 'DUPLIC. RESULTADOS LAB.', 0.50, 1),
(24, 6, 'EXAMEN COMPLETO DE ORINA', 8.00, 1),
(25, 6, 'EXAMEN DIR. (TRICHOMONAS)', 5.00, 1),
(26, 6, 'EXAMEN DIR. HONGOS KOH', 6.00, 1),
(27, 6, 'EXAMEN DE SECRECION + GRAM', 13.00, 1),
(28, 6, 'FACTOR REUMATOIDEO', 8.00, 1),
(29, 6, 'FENOMENO LE', 9.00, 1),
(30, 6, 'FOSFATASA ACIDA PROSTAT.', 6.00, 1),
(31, 6, 'FOSFATASA ALCALINA', 8.00, 1),
(32, 6, 'GLOBULOS ROJOS', 4.00, 1),
(33, 6, 'GLUCOCINTA', 4.00, 1),
(34, 6, 'GLUCOSA', 8.00, 1),
(35, 6, 'GOTA GRUESA', 0.00, 1),
(36, 6, 'GRUPO SANGUINEO Y RH', 7.00, 1),
(37, 6, 'HEMATOCRITO', 5.00, 1),
(38, 6, 'HEMOGLOBINA', 5.00, 1),
(39, 6, 'HEMOGLOBINA GLICOSILADA', 30.00, 1),
(40, 6, 'HEMOGRAMA COMPLETO', 10.00, 1),
(41, 6, 'HIV ( MET. DE ELISA)', 15.00, 1),
(42, 6, 'INDIGENCIA LABORAT', 0.00, 1),
(43, 6, 'LAMINA PERIFERICA', 11.00, 1),
(44, 6, 'METODO DE FAUST', 6.00, 1),
(45, 6, 'MOCO FECAL', 5.00, 1),
(46, 6, 'PARASITOLOGIA SERIADO', 10.00, 1),
(47, 6, 'PARASITOLOGIA SIMPLE', 5.00, 1),
(48, 6, 'PERFIL HEPATICO', 18.00, 1),
(49, 6, 'PROTEINA C REACTIVA', 8.00, 1),
(50, 6, 'PROTEINA TOTAL', 0.00, 1),
(51, 6, 'PROTEINAS T Y F', 15.00, 1),
(52, 6, 'PROTEINURIA 24 HRS', 8.00, 1),
(53, 6, 'PRUEBA DE EMB. SANG. Y OR', 12.00, 1),
(54, 6, 'RECUENTO DE PLAQUETAS', 6.00, 1),
(55, 6, 'RECUENTO DE RETICULOSITOS', 6.00, 1),
(56, 6, 'RECUENTO EOSINOFILOS', 4.00, 1),
(57, 6, 'SECRECION VAGINAL', 5.00, 1),
(58, 6, 'SEDIMIENTO URINARIO', 4.00, 1),
(59, 6, 'SEROLOGÍA VDRL', 6.00, 1),
(60, 6, 'SUDAN III', 4.00, 1),
(61, 6, 'T. DE CUAGULACION DE Y SANGRE', 6.00, 1),
(62, 6, 'TEST DE GRAHAM (OXIUROS)', 5.00, 1),
(63, 6, 'THEVENON ( SANGRE EN HECES)', 4.00, 1),
(64, 6, 'TIEMPO DE PROTOMBINA', 9.00, 1),
(65, 6, 'TOLERANCIA DE GLUCOSA', 25.00, 1),
(66, 6, 'TOMA DE MUESTRA LAB DOM', 5.00, 1),
(67, 6, 'TRANSAMINAS GOT – GTP', 15.00, 1),
(68, 6, 'TRIGLICERIDOS', 10.00, 1),
(69, 6, 'UREA', 8.00, 1),
(70, 3, 'CONSULTA DOMICILIARIA OBS', 10.00, 1),
(71, 3, 'CONTROL OBSTETRICO', 6.00, 1),
(72, 3, 'CONTROL OBSTETRICO (GRATUITO)', 0.00, 1),
(73, 3, 'INDIGENCIA OBSTETRICA', 0.00, 1),
(74, 3, 'INTERCONSULTA OBSTETRIZ', 0.00, 1),
(75, 3, 'IVVA', 5.00, 1),
(76, 3, 'PAPANICOLAO', 5.00, 1),
(77, 2, 'PLANIFICACION FAMILIAR', 0.00, 1),
(78, 3, 'SIS-CONS-EXTERNA GEST.', 0.00, 1),
(79, 7, 'ADMINISTRACION DE OXIGENO', 10.00, 1),
(80, 7, 'APLICACIÓN DE ENEMA', 5.00, 1),
(81, 7, 'CATETERISMO VENOSO', 7.00, 1),
(82, 7, 'CIR. MENOR (SUT. X1 PUNT)', 3.00, 1),
(83, 7, 'CURACION SIMPLE', 5.00, 1),
(84, 7, 'CURACION SIS', 0.00, 1),
(85, 7, 'CURACION X PUNTO C/ SUTURA', 2.00, 1),
(86, 7, 'EXTIRP. LIPOMA Y OTROS', 30.00, 1),
(87, 7, 'EXTIRP. QUISTE SEBACEO', 15.00, 1),
(88, 7, 'INYECTABLE ENDOVENOSO', 4.00, 1),
(89, 7, 'INYECTABLES', 1.50, 1),
(90, 7, 'INYECTABLES A DOMICILIO', 5.00, 1),
(91, 7, 'LAVADO OIDO TOPICO 1', 10.00, 1),
(92, 7, 'MORDEDURA DE ANIMAL', 2.00, 1),
(93, 7, 'NEBULIZACION 10 MIN', 10.00, 1),
(94, 7, 'OBSERVACION POR 1 HORA', 10.00, 1),
(95, 7, 'OCLUSION DE OJO', 3.00, 1),
(96, 7, 'QUEMADURA COMPLICADA', 10.00, 1),
(97, 7, 'RETIRO DE CUERPO EXTRAÑO', 12.00, 1),
(98, 7, 'RETIRO DE UÑERO (7.00)', 7.00, 1),
(99, 7, 'RETIRO DE UÑERO (15.00)', 15.00, 1),
(100, 7, 'SMI-ATENCION TOPICO', 0.00, 1),
(101, 7, 'TAPONAMIENTO NASAL', 5.00, 1),
(102, 7, 'TOMA PRESION ARTERIAL', 2.00, 1),
(103, 7, 'VENOCLISIS', 15.00, 1),
(104, 4, 'CONSULTA DENTAL', 8.00, 1),
(105, 4, 'CONSULTA DENTAL CAMPAÑA', 3.00, 1),
(106, 4, 'CONSULTA DENTAL CONTI.', 4.00, 1),
(107, 4, 'CONSULTA DENTAL GESTANTE', 0.00, 1),
(108, 4, 'CONTROL DENTAL', 1.00, 1),
(109, 4, 'CONTROL ODONTOLOGICO', 0.00, 1),
(110, 4, 'EXAMEN DENTAL GESTANTE', 0.00, 1),
(111, 4, 'EXTRACCION CAMPAÑA', 0.00, 1),
(112, 4, 'EXTRACCION DENTAL', 7.50, 1),
(113, 4, 'EXTRACCION GESTANTE', 0.00, 1),
(114, 4, 'FLUORIZACION', 10.00, 1),
(115, 4, 'FRENECTOMIA', 30.00, 1),
(116, 4, 'INDIGENCIA ODONTOLOGICA', 0.00, 1),
(117, 4, 'NITRATO DE PLATA', 5.00, 1),
(118, 4, 'OBTURACION CON AMALGAMA', 12.00, 1),
(119, 4, 'OBTURACION GESTANTE', 0.00, 1),
(120, 4, 'OBTURACION IONOMERO', 10.00, 1),
(121, 4, 'PROFILAXIS', 10.00, 1),
(122, 4, 'SELLANTES', 10.00, 1),
(123, 4, 'SIS-CONSULTA DENTAL', 0.00, 1),
(124, 4, 'SIS-EXTRACCION DENTAL', 0.00, 1),
(125, 4, 'SIS-OBT. CURACION COMPUES', 0.00, 1),
(126, 4, 'SIS-OBT. CURACION SIMPLE', 0.00, 1),
(127, 4, 'SIS-TERMINAR DE CURAR DEN', 0.00, 1),
(128, 4, 'TERMIRAR DE CURAR GESTANTE', 0.00, 1),
(129, 4, 'TERMINAR DE CURAR ( DENTAL', 0.00, 1),
(130, 4, 'TRATAMIENTO ODONTOLOGICO', 0.00, 1),
(131, 1, 'CERTIFICADO SALUD MENTAL', 25.00, 1),
(132, 1, 'CERTIFICADO PSICOLOGICO', 15.00, 1),
(133, 1, 'CONTROL PSICOLOGICO', 0.00, 1),
(134, 1, 'INDIGENCIA PSICOLOGICA', 0.00, 1),
(135, 1, 'PSICOLOGIA', 8.00, 1),
(136, 1, 'PSCIOLOGIA CONTROL', 0.00, 1),
(137, 5, 'SESION DE TERAPIA FISICA', 20.00, 1),
(138, 5, 'SESION TERAPIA - SIS', 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `triaje`
--

CREATE TABLE `triaje` (
  `id_triaje` int(11) NOT NULL,
  `id_atencion` int(11) NOT NULL,
  `id_usuario_triaje` int(11) DEFAULT NULL,
  `temperatura` decimal(4,2) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `talla` decimal(4,2) DEFAULT NULL,
  `presion_arterial` varchar(20) DEFAULT NULL,
  `oxigenacion` decimal(4,2) DEFAULT NULL,
  `frecuencia_cardiaca` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `rol` enum('admin','caja','soporte','triaje') NOT NULL DEFAULT 'caja',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `cambiar_password` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password_hash`, `nombre_completo`, `rol`, `activo`, `cambiar_password`, `fecha_creacion`) VALUES
(1, 'admin', '123456', 'Administrador de Sistema', 'admin', 1, 0, '2025-10-27 14:18:06'),
(2, 'caja', '123456', 'Cajero Principal', 'caja', 1, 1, '2025-10-27 14:18:06'),
(3, 'soporte', '123456', 'Soporte Tecnico', 'soporte', 1, 0, '2025-10-27 14:18:06'),
(4, 'triaje', '123456', 'Personal de Triaje', 'triaje', 1, 1, '2025-10-27 14:18:06');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atenciones`
--
ALTER TABLE `atenciones`
  ADD PRIMARY KEY (`id_atencion`),
  ADD UNIQUE KEY `uk_ticket_diario` (`id_profesional`,`fecha_hora`,`numero_ticket_diario`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_especialidad` (`id_especialidad`),
  ADD KEY `id_usuario_caja` (`id_usuario_caja`);

--
-- Indices de la tabla `atencion_servicios`
--
ALTER TABLE `atencion_servicios`
  ADD PRIMARY KEY (`id_atencion`,`id_servicio`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `profesionales`
--
ALTER TABLE `profesionales`
  ADD PRIMARY KEY (`id_profesional`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `triaje`
--
ALTER TABLE `triaje`
  ADD PRIMARY KEY (`id_triaje`),
  ADD UNIQUE KEY `id_atencion` (`id_atencion`),
  ADD KEY `id_usuario_triaje` (`id_usuario_triaje`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atenciones`
--
ALTER TABLE `atenciones`
  MODIFY `id_atencion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesionales`
--
ALTER TABLE `profesionales`
  MODIFY `id_profesional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT de la tabla `triaje`
--
ALTER TABLE `triaje`
  MODIFY `id_triaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `atenciones`
--
ALTER TABLE `atenciones`
  ADD CONSTRAINT `atenciones_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `atenciones_ibfk_2` FOREIGN KEY (`id_profesional`) REFERENCES `profesionales` (`id_profesional`),
  ADD CONSTRAINT `atenciones_ibfk_3` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`),
  ADD CONSTRAINT `atenciones_ibfk_4` FOREIGN KEY (`id_usuario_caja`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `atencion_servicios`
--
ALTER TABLE `atencion_servicios`
  ADD CONSTRAINT `atencion_servicios_ibfk_1` FOREIGN KEY (`id_atencion`) REFERENCES `atenciones` (`id_atencion`) ON DELETE CASCADE,
  ADD CONSTRAINT `atencion_servicios_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`);

--
-- Filtros para la tabla `profesionales`
--
ALTER TABLE `profesionales`
  ADD CONSTRAINT `profesionales_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `triaje`
--
ALTER TABLE `triaje`
  ADD CONSTRAINT `triaje_ibfk_1` FOREIGN KEY (`id_atencion`) REFERENCES `atenciones` (`id_atencion`) ON DELETE CASCADE,
  ADD CONSTRAINT `triaje_ibfk_2` FOREIGN KEY (`id_usuario_triaje`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
