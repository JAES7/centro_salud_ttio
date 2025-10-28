-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2025 a las 02:42:26
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
  `fecha_atencion` datetime NOT NULL,
  `turno` enum('Manana','Tarde') NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `numero_ticket_diario` int(11) DEFAULT NULL,
  `hora_turno_estimada` time DEFAULT NULL,
  `ticket_dia` int(11) NOT NULL COMMENT 'Número de ticket para ese profesional en ese día',
  `monto_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado_atencion` enum('Pendiente','Triado','Finalizado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `id_usuario_caja` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Visible, 0=Borrado suave'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `atenciones`
--

INSERT INTO `atenciones` (`id_atencion`, `id_paciente`, `id_profesional`, `id_especialidad`, `fecha_atencion`, `turno`, `total`, `numero_ticket_diario`, `hora_turno_estimada`, `ticket_dia`, `monto_total`, `estado_atencion`, `id_usuario_caja`, `fecha_hora`, `visible`) VALUES
(1, 3, 1, 4, '0000-00-00 00:00:00', 'Manana', 10.00, 1, NULL, 0, 0.00, 'Triado', 1, '2025-10-25 23:41:00', 1),
(2, 4, 2, 4, '0000-00-00 00:00:00', 'Tarde', 30.00, 1, NULL, 0, 0.00, 'Pendiente', 1, '2025-10-26 00:02:00', 1),
(3, 5, 2, 4, '0000-00-00 00:00:00', 'Manana', 0.00, 2, NULL, 0, 0.00, 'Triado', 1, '2025-10-26 00:10:00', 1),
(4, 6, 2, 4, '0000-00-00 00:00:00', 'Manana', 0.00, 3, NULL, 0, 0.00, 'Pendiente', 1, '2025-10-26 00:11:00', 1),
(5, 7, 1, 4, '0000-00-00 00:00:00', 'Manana', 10.00, 1, NULL, 0, 0.00, 'Pendiente', 1, '2025-10-26 00:12:00', 1),
(6, 8, 3, 4, '0000-00-00 00:00:00', 'Manana', 12.00, 1, '07:30:00', 0, 0.00, 'Triado', 1, '2025-10-26 00:47:00', 1),
(7, 9, 3, 4, '0000-00-00 00:00:00', 'Manana', 30.00, 2, '08:15:00', 0, 0.00, 'Triado', 1, '2025-10-26 00:47:00', 1),
(8, 10, 7, 2, '0000-00-00 00:00:00', 'Manana', 0.00, 1, NULL, 0, 0.00, 'Pendiente', 1, '2025-10-27 20:22:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atencion_servicios`
--

CREATE TABLE `atencion_servicios` (
  `id_atencion_servicio` int(11) NOT NULL,
  `id_atencion` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `atencion_servicios`
--

INSERT INTO `atencion_servicios` (`id_atencion_servicio`, `id_atencion`, `id_servicio`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 119, 1, 10.00, 10.00),
(2, 2, 120, 1, 30.00, 30.00),
(3, 3, 121, 1, 0.00, 0.00),
(4, 4, 116, 1, 0.00, 0.00),
(5, 5, 126, 1, 10.00, 10.00),
(6, 6, 123, 1, 12.00, 12.00),
(7, 7, 120, 1, 30.00, 30.00),
(8, 8, 74, 1, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `consultorio` varchar(50) DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`, `consultorio`, `activa`) VALUES
(1, 'Psicología', '302', 1),
(2, 'Obstetricia Materno', '202', 1),
(3, 'Obstetricia planificación familiar', '301', 1),
(4, 'Odontología', '304', 1),
(5, 'Terapia Física', '306', 1),
(6, 'Laboratorio', '208', 1),
(7, 'Tópico', '105', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `ultima_visita` datetime DEFAULT NULL,
  `eliminado_suavemente` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Visible, 0=Borrado suave'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `dni`, `nombre_completo`, `ultima_visita`, `eliminado_suavemente`, `fecha_nacimiento`, `telefono`, `visible`) VALUES
(3, '73029913', 'Jairo Facundo Vilchez lucho', '2025-10-25 23:41:00', 0, NULL, NULL, 1),
(4, '23763612', 'Jairo Mateo carrasco pilares', '2025-10-26 00:02:00', 0, NULL, NULL, 1),
(5, '12563214', 'Lorenzo Mateo Verglorio Cusi', '2025-10-26 00:10:00', 0, NULL, NULL, 1),
(6, '56784356', 'Lorenzo cutipa carrasco', '2025-10-26 00:11:00', 0, NULL, NULL, 1),
(7, '67932513', 'Siempre unidos sf', '2025-10-26 00:12:00', 0, NULL, NULL, 1),
(8, '45879214', 'nueva nace carajo', '2025-10-26 00:47:00', 0, NULL, NULL, 1),
(9, '34578676', 'queeti imorta', '2025-10-26 00:47:00', 0, NULL, NULL, 1),
(10, '54754756', 'ojala funcione carajod', '2025-10-27 20:22:00', 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesionales`
--

CREATE TABLE `profesionales` (
  `id_profesional` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesionales`
--

INSERT INTO `profesionales` (`id_profesional`, `id_especialidad`, `nombre_completo`, `activo`) VALUES
(1, 4, 'Vilma Vargas Contreras', 1),
(2, 4, 'Heidi Guia Abarca', 1),
(3, 4, 'Sadith Rosa Cruz', 1),
(4, 4, 'Leo O. Martiarena Huayhua', 1),
(5, 2, 'Cesar Laura Mamani (G)', 1),
(7, 2, 'Jackeline Astete Berdejo', 1),
(9, 2, 'Cristal Flores Olave', 1),
(11, 2, 'Marisol Condori Pezo', 1),
(13, 2, 'Lisbet Jordán Palacios', 1),
(15, 2, 'Tania Zapata Carreño', 1),
(17, 2, 'Shirai E. Ortiz Marocho', 1),
(19, 1, 'Evelyn Molina Navarrete', 1),
(20, 1, 'Wendy M. Ramirez Ramirez (SERUMS)', 1),
(21, 5, 'Maribel Jauja Gomez (SERUMS)', 1),
(22, 5, 'Daniel E. Mandujano Vergara (SERUMS)', 1),
(23, 3, 'Cesar Laura Mamani (G)', 1),
(24, 3, 'Jackeline Astete Berdejo', 1),
(25, 3, 'Cristal Flores Olave', 1),
(26, 3, 'Marisol Condori Pezo', 1),
(27, 3, 'Lisbet Jordán Palacios', 1),
(28, 3, 'Tania Zapata Carreño', 1),
(29, 3, 'Shirai E. Ortiz Marocho', 1),
(30, 6, 'Profesional de Turno', 1),
(31, 7, 'Profesional de Turno', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `id_especialidad`, `descripcion`, `monto`, `activo`) VALUES
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
(68, 6, 'TRIGLICERIDOS ', 10.00, 1),
(69, 6, 'UREA', 8.00, 1),
(70, 2, 'CONSULTA DOMICILIARIA OBS', 10.00, 1),
(72, 2, 'CONTROL OBSTETRICO', 6.00, 1),
(74, 2, 'CONTROL OBSTETRICO', 0.00, 1),
(76, 2, 'INDIGENCIA OBSTETRICA', 0.00, 1),
(78, 2, 'INTERCONSULTA OBSTETRIZ', 0.00, 1),
(80, 2, 'IVVA', 5.00, 1),
(81, 2, 'PAPANICOLAO', 5.00, 1),
(82, 3, 'PLANIFICACION FAMILIAR', 0.00, 1),
(83, 2, 'SIS-CONS-EXTERNA GEST.', 0.00, 1),
(84, 7, 'ADMINISTRACION DE OXIGENO', 10.00, 1),
(85, 7, 'APLICACIÓN DE ENEMA', 5.00, 1),
(86, 7, 'CATETERISMO VENOSO', 7.00, 1),
(87, 7, 'CIR. MENOR (SUT. X1 PUNT)', 3.00, 1),
(88, 7, 'CURACION SIMPLE', 5.00, 1),
(89, 7, 'CURACION SIS', 0.00, 1),
(90, 7, 'CURACION X PUNTO C/ SUTURA', 2.00, 1),
(91, 7, 'EXTIRP. LIPOMA Y OTROS', 30.00, 1),
(92, 7, 'EXTIRP. QUISTE SEBACEO', 15.00, 1),
(93, 7, 'INYECTABLE ENDOVENOSO', 4.00, 1),
(94, 7, 'INYECTABLES', 1.50, 1),
(95, 7, 'INYECTABLES A DOMICILIO', 5.00, 1),
(96, 7, 'LAVADO OIDO TOPICO 1', 10.00, 1),
(97, 7, 'MORDEDURA DE ANIMAL', 2.00, 1),
(98, 7, 'NEBULIZACION 10 MIN', 10.00, 1),
(99, 7, 'OBSERVACION POR 1 HORA', 10.00, 1),
(100, 7, 'OCLUSION DE OJO', 3.00, 1),
(101, 7, 'QUEMADURA COMPLICADA', 10.00, 1),
(102, 7, 'RETIRO DE CUERPO EXTRAÑO', 12.00, 1),
(103, 7, 'RETIRO DE UÑERO', 7.00, 1),
(104, 7, 'RETIRO DE   UÑERO', 15.00, 1),
(105, 7, 'SMI-ATENCION TOPICO', 0.00, 1),
(106, 7, 'TAPONAMIENTO NASAL', 5.00, 1),
(107, 7, 'TOMA PRESION ARTERIAL', 2.00, 1),
(108, 7, 'VENOCLISIS', 15.00, 1),
(109, 4, 'CONSULTA DENTAL', 8.00, 1),
(110, 4, 'CONSULTA DENTAL CAMPAÑA', 3.00, 1),
(111, 4, 'CONSULTA DENTAL CONTI.', 4.00, 1),
(112, 4, 'CONSULTA DENTAL GESTANTE', 0.00, 1),
(113, 4, 'CONTROL DENTAL', 1.00, 1),
(114, 4, 'CONTROL ODONTOLOGICO', 0.00, 1),
(115, 4, 'EXAMEN DENTAL GESTANTE', 0.00, 1),
(116, 4, 'EXTRACCION CAMPAÑA ', 0.00, 1),
(117, 4, 'EXTRACCION DENTAL', 7.50, 1),
(118, 4, 'EXTRACCION GESTANTE', 0.00, 1),
(119, 4, 'FLUORIZACION', 10.00, 1),
(120, 4, 'FRENECTOMIA', 30.00, 1),
(121, 4, 'INDIGENCIA ODONTOLOGICA', 0.00, 1),
(122, 4, 'NITRATO DE PLATA', 5.00, 1),
(123, 4, 'OBTURACION CON AMALGAMA', 12.00, 1),
(124, 4, 'OBTURACION GESTANTE', 0.00, 1),
(125, 4, 'OBTURACION IONOMERO', 10.00, 1),
(126, 4, 'PROFILAXIS', 10.00, 1),
(127, 4, 'SELLANTES', 10.00, 1),
(128, 4, 'SIS-CONSULTA DENTAL', 0.00, 1),
(129, 4, 'SIS-EXTRACCION DENTAL', 0.00, 1),
(130, 4, 'SIS-OBT. CURACION COMPUES', 0.00, 1),
(131, 4, 'SIS-OBT. CURACION SIMPLE', 0.00, 1),
(132, 4, 'SIS-TERMINAR DE CURAR DEN', 0.00, 1),
(133, 4, 'TERMIRAR DE CURAR GESTANTE', 0.00, 1),
(134, 4, 'TERMINAR DE CURAR ( DENTAL', 0.00, 1),
(135, 4, 'TRATAMIENTO ODONTOLOGICO', 0.00, 1),
(136, 1, 'CERTIFICADO SALUD MENTAL', 25.00, 1),
(137, 1, 'CERTIFICADO PSICOLOGICO', 15.00, 1),
(138, 1, 'CONTROL PSICOLOGICO', 0.00, 1),
(139, 1, 'INDIGENCIA PSICOLOGICA', 0.00, 1),
(140, 1, 'PSICOLOGIA', 8.00, 1),
(141, 1, 'PSCIOLOGIA CONTROL', 0.00, 1),
(142, 5, 'SESION DE TERAPIA FISICA', 20.00, 1),
(143, 5, 'SESION TERAPIA - SIS', 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `triaje`
--

CREATE TABLE `triaje` (
  `id_triaje` int(11) NOT NULL,
  `id_atencion` int(11) NOT NULL,
  `id_usuario_triaje` int(11) NOT NULL,
  `fecha_triaje` datetime NOT NULL,
  `temperatura` decimal(4,1) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `talla` decimal(3,2) DEFAULT NULL,
  `presion_arterial` varchar(20) DEFAULT NULL,
  `oxigenacion` int(11) DEFAULT NULL,
  `frecuencia_cardiaca` int(11) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `triaje`
--

INSERT INTO `triaje` (`id_triaje`, `id_atencion`, `id_usuario_triaje`, `fecha_triaje`, `temperatura`, `peso`, `talla`, `presion_arterial`, `oxigenacion`, `frecuencia_cardiaca`, `visible`) VALUES
(3, 7, 1, '0000-00-00 00:00:00', 36.0, 70.00, 9.99, '120/80', 98, 80, 1),
(4, 1, 1, '0000-00-00 00:00:00', 36.0, 71.00, 9.99, '120/80', 98, 80, 1),
(5, 3, 1, '0000-00-00 00:00:00', 36.0, 72.00, 9.99, '120/80', 97, 80, 1),
(6, 6, 1, '0000-00-00 00:00:00', 36.0, 70.00, 9.99, '120/80', 98, 80, 1);

--
-- Disparadores `triaje`
--
DELIMITER $$
CREATE TRIGGER `trg_actualizar_estado_triaje` AFTER INSERT ON `triaje` FOR EACH ROW BEGIN
    UPDATE atenciones
    SET estado_atencion = 'Triado'
    WHERE id_atencion = NEW.id_atencion;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `rol` enum('admin','caja','triaje','soporte') NOT NULL,
  `cambiar_password` tinyint(1) NOT NULL DEFAULT 1,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password_hash`, `nombre_completo`, `rol`, `cambiar_password`, `activo`) VALUES
(1, 'admin', 'admin', 'Administrador General', 'admin', 0, 1),
(2, 'soporte', 'admin', 'Usuario Soporte', 'soporte', 0, 1),
(3, 'cajero1', 'jairo', 'Cajero Principal', 'caja', 0, 1),
(4, 'triaje1', 'jairo', 'Enfermera Triaje', 'triaje', 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atenciones`
--
ALTER TABLE `atenciones`
  ADD PRIMARY KEY (`id_atencion`),
  ADD KEY `fk_atencion_paciente` (`id_paciente`),
  ADD KEY `fk_atencion_profesional` (`id_profesional`),
  ADD KEY `fk_atencion_especialidad` (`id_especialidad`),
  ADD KEY `fk_atencion_usuario` (`id_usuario_caja`);

--
-- Indices de la tabla `atencion_servicios`
--
ALTER TABLE `atencion_servicios`
  ADD PRIMARY KEY (`id_atencion_servicio`),
  ADD KEY `fk_as_atencion` (`id_atencion`),
  ADD KEY `fk_as_servicio` (`id_servicio`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

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
  ADD KEY `fk_profesional_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `fk_servicio_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `triaje`
--
ALTER TABLE `triaje`
  ADD PRIMARY KEY (`id_triaje`),
  ADD KEY `fk_triaje_atencion` (`id_atencion`),
  ADD KEY `fk_triaje_usuario` (`id_usuario_triaje`);

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
  MODIFY `id_atencion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `atencion_servicios`
--
ALTER TABLE `atencion_servicios`
  MODIFY `id_atencion_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `profesionales`
--
ALTER TABLE `profesionales`
  MODIFY `id_profesional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT de la tabla `triaje`
--
ALTER TABLE `triaje`
  MODIFY `id_triaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `fk_atencion_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`),
  ADD CONSTRAINT `fk_atencion_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `fk_atencion_profesional` FOREIGN KEY (`id_profesional`) REFERENCES `profesionales` (`id_profesional`),
  ADD CONSTRAINT `fk_atencion_usuario` FOREIGN KEY (`id_usuario_caja`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `atencion_servicios`
--
ALTER TABLE `atencion_servicios`
  ADD CONSTRAINT `fk_as_atencion` FOREIGN KEY (`id_atencion`) REFERENCES `atenciones` (`id_atencion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_as_servicio` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`);

--
-- Filtros para la tabla `profesionales`
--
ALTER TABLE `profesionales`
  ADD CONSTRAINT `fk_profesional_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `fk_servicio_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `triaje`
--
ALTER TABLE `triaje`
  ADD CONSTRAINT `fk_triaje_atencion` FOREIGN KEY (`id_atencion`) REFERENCES `atenciones` (`id_atencion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_triaje_usuario` FOREIGN KEY (`id_usuario_triaje`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
