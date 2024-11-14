-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2024 a las 16:39:49
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pensionsync`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gt_documentos`
--

CREATE TABLE `gt_documentos` (
  `id_documento` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(150) DEFAULT NULL,
  `descripcion` varchar(1000) DEFAULT NULL COMMENT 'nombre del archivo real',
  `tamanio` int(10) UNSIGNED DEFAULT NULL,
  `ruta` varchar(150) DEFAULT NULL,
  `nombre_archivo` varchar(255) DEFAULT NULL COMMENT 'nombre archivo en servidor',
  `usuario` varchar(30) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `gt_documentos`
--

INSERT INTO `gt_documentos` (`id_documento`, `tipo`, `descripcion`, `tamanio`, `ruta`, `nombre_archivo`, `usuario`, `id_empleado`) VALUES
(1, 'application/pdf', 'Solicitud.CDP.Inversion (6).pdf', 135071, 'adjuntos/doc_6730bdd0c1d1b4.03546228.pdf', 'doc_6730bdd0c1d1b4.03546228.pdf', 'admin', 1),
(2, 'application/pdf', 'Solicitud.CDP.Inversion (1).pdf', 10608, 'adjuntos/doc_6730bde2918b20.69855523.pdf', 'doc_6730bde2918b20.69855523.pdf', 'admin', 8),
(3, 'application/pdf', 'certificado.RETENCIONES.2022.TRABAJADORES.SIN.FRONTERAS.pdf', 27814, 'adjuntos/doc_6730be093d6965.92230693.pdf', 'doc_6730be093d6965.92230693.pdf', 'admin', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gt_empleado`
--

CREATE TABLE `gt_empleado` (
  `id` int(11) NOT NULL,
  `id_tipo_identificacion` varchar(2) NOT NULL,
  `identificacion` varchar(20) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `sexo` enum('M','F','O','') NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `id_riesgo_arl` int(11) DEFAULT NULL,
  `id_eps` int(11) DEFAULT NULL,
  `id_fondo_pension` int(11) DEFAULT NULL,
  `id_fondo_cesantias` int(11) DEFAULT NULL,
  `id_arl` int(11) DEFAULT NULL,
  `id_caja_compensacion` int(11) DEFAULT NULL,
  `id_Empleado_sustituto` int(11) DEFAULT NULL,
  `id_tipo_empleado` int(11) DEFAULT NULL,
  `fcha_cumpleano` date DEFAULT NULL,
  `dato_pension` varchar(50) DEFAULT NULL,
  `fecha_pension` date DEFAULT NULL,
  `fecha_sustitucion` date DEFAULT NULL,
  `porcentaje_sustitucion` decimal(5,2) DEFAULT NULL,
  `estado` int(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `gt_empleado`
--

INSERT INTO `gt_empleado` (`id`, `id_tipo_identificacion`, `identificacion`, `primer_apellido`, `segundo_apellido`, `primer_nombre`, `segundo_nombre`, `nombre_completo`, `sexo`, `telefono`, `direccion`, `correo_electronico`, `id_cargo`, `id_riesgo_arl`, `id_eps`, `id_fondo_pension`, `id_fondo_cesantias`, `id_arl`, `id_caja_compensacion`, `id_Empleado_sustituto`, `id_tipo_empleado`, `fcha_cumpleano`, `dato_pension`, `fecha_pension`, `fecha_sustitucion`, `porcentaje_sustitucion`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, 'CC', '10824974716', 'Pernett', 'Retamozo', 'Juliofff', 'Jose', 'Juliofff Jose Pernett Retamozo', 'F', '3185218253', 'CALLE 7B 23 - 27', 'jjpernett2008@gmail.com', 33, 1, 1, 3, 2, 5, 11, NULL, 1, '1993-09-16', 'PRUEBAS', '2024-11-09', NULL, NULL, 1, 'admin', '2024-11-10 07:02:05'),
(8, 'CC', '57415225', 'Retamozo', 'Cardenas', 'Osiris', 'Janeth', 'Osiris Janeth Retamozo Cardenas', 'F', '31261252140', 'caaaa 7b', 'osiyan@gormail.com', 2, 6, 12, 3, 2, 16, 15, 1, 3, '2024-10-24', NULL, NULL, '2024-10-14', '70.00', 1, 'admin', '2024-10-29 05:46:02'),
(9, 'CE', '1221971570', 'Pernettaaaa', 'Retamozoza', 'Jose ', 'Policarpoa', 'Jose  Policarpoa Pernettaaaa Retamozoza', 'O', '31261252140', 'WWSSSSKJH', 'osiyan@gormail.com', 2, 1, 1, 3, 2, 5, 15, NULL, 2, '1993-09-16', NULL, NULL, NULL, NULL, 1, 'admin', '2024-11-10 19:36:32'),
(10, 'CC', '4252573245', 'POLICARPO', 'CUETO', 'JOSE', 'PERNET', 'JOSE PERNET POLICARPO CUETO', 'M', '0', 'CALLE 7B', 'jjpernett2008@gmail.com', 2, 2, 1, 3, 2, 5, 11, NULL, 2, '2024-10-28', NULL, NULL, NULL, NULL, 1, 'admin', '2024-10-28 19:49:57'),
(11, 'CC', '45278687', 'POLICARPO', 'CUETO', 'JOSE', 'PERNET', 'JOSE PERNET POLICARPO CUETO', 'F', '3571414', 'CALLE 7B', 'jjpernett2008@gmail.com', 11, 1, 1, 3, 2, 5, 11, NULL, 1, '2024-10-28', 'daojhfodisjs', '2024-10-28', NULL, NULL, 1, 'admin', '2024-10-28 19:55:14'),
(15, 'CE', '1864165416546', 'qihiuhiuh', 'iuhniujnhu', 'jgdfdfg', 'sfdgvzxcgv', 'jgdfdfg sfdgvzxcgv qihiuhiuh iuhniujnhu', 'F', '654961651651', 'CALLE 7B', 'jjpernett2008@gmail.com', 33, 1, 12, 3, 13, 5, 15, NULL, 2, '2024-11-09', NULL, NULL, NULL, NULL, 1, 'admin', '2024-11-10 02:51:11'),
(16, 'CE', '894651654165', 'POLICARPO', 'iuhniujnhu', 'JOSE', '45,khjmg', 'JOSE 45,khjmg POLICARPO iuhniujnhu', 'M', '168416541', 'CALLE 7B', 'jjpernett2008@gmail.com', 11, NULL, 12, 3, NULL, NULL, NULL, NULL, 2, '2024-11-09', NULL, NULL, NULL, NULL, 1, 'admin', '2024-11-10 03:47:28'),
(17, 'CC', '1221574154', 'dsfgd', 'dfgd', 'sfdd', 'hfgd', 'sfdd hfgd dsfgd dfgd', 'F', '3185218253', 'CALLE 7B', 'jjpernett2008@gmail.com', 33, NULL, 1, 14, NULL, NULL, NULL, NULL, 1, '2024-11-08', 'daojhfodisjs', '2024-11-09', NULL, NULL, 1, 'admin', '2024-11-10 03:48:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gt_novedades`
--

CREATE TABLE `gt_novedades` (
  `id` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_concepto` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `Valor` decimal(14,2) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `resolucion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `gt_novedades`
--

INSERT INTO `gt_novedades` (`id`, `id_empleado`, `id_concepto`, `id_periodo`, `Valor`, `estado`, `usuario_registro`, `fecha_registro`, `resolucion_id`) VALUES
(1, 9, 3, 1, '258000000.00', 0, 'admin', '2024-11-13 23:49:11', NULL),
(2, 8, 3, 1, '15000000.00', 1, 'admin', '2024-11-14 16:18:24', 2),
(3, 8, 4, 2, '50000.00', 1, 'admin', '2024-11-14 00:37:19', 2),
(4, 10, 5, 1, '5000000.00', 1, 'admin', '2024-11-09 17:07:53', 6),
(5, 11, 4, 9, '4710000.00', 1, 'admin', '2024-11-09 17:11:36', 1),
(7, 15, 5, 1, '500000000.00', 1, 'admin', '2024-11-13 23:41:03', 2),
(8, 17, 3, 1, '15000000.00', 1, 'admin', '2024-11-13 23:45:47', NULL),
(9, 1, 2, 1, '8000000.00', 1, 'admin', '2024-11-13 23:48:59', NULL),
(10, 15, 2, 1, '897987451651.00', 1, 'admin', '2024-11-13 23:50:22', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gt_resoluciones`
--

CREATE TABLE `gt_resoluciones` (
  `id` int(11) NOT NULL,
  `numero` varchar(10) NOT NULL COMMENT 'numero de resolucion',
  `detalle` varchar(1000) NOT NULL COMMENT 'detalle de la resolucion, generalmente es un texto "por medio de la cual se ... se resuelve recurso...',
  `fecha_resolucion` date NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_registro` varchar(30) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `gt_resoluciones`
--

INSERT INTO `gt_resoluciones` (`id`, `numero`, `detalle`, `fecha_resolucion`, `fecha_registro`, `usuario_registro`, `estado`) VALUES
(1, '170', 'expedida por la Dirección Distrital de Liquidaciones, por medio del cual se ordena la inclusión en nómina a la señora RUDYS ESTHER ROBLES VALERA identificada con CC 22.567.865 en condición de compañera permanente del jubilado fallecido Francisco Linero Martes (q.e.p.d.) el 20% retroactivo se autoriza pagarlo a favor del apoderado Dr. Euclides Puello Sarmiento CC 7.441.953 TP 63.725, según declaración jurada que se encuentra relacionada en la resolución antes relacionada', '2023-11-30', '2024-11-06 06:59:03', 'admin', 1),
(2, '013', 'expedida por la Dirección Distrital de liquidaciones, por medio de la cual se ordedna la inclusión en la nomina de jubilados de la extinta EDT en cumplimiento a una sentencia judicial al señor ERNESTO ENRIQUE ESCORCIA GOELKEL identificado con cedula de ciudadanpia No. 8.681.010', '2024-01-16', '2024-11-04 08:39:21', 'admin', 1),
(3, '333', 'esta es una prueba', '2024-11-05', '2024-11-06 07:11:36', 'admin', 1),
(6, '333D', 'PRUEBAS', '2024-11-05', '2024-11-06 07:13:39', 'admin', 1),
(7, '684', 'resolucion de pruebas', '2024-11-14', '2024-11-14 21:38:59', 'admin', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `id_novedad` int(11) DEFAULT NULL,
  `fecha_notificacion` date DEFAULT NULL,
  `descripcion_notificacion` varchar(100) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_cargos`
--

CREATE TABLE `pm_cargos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pm_cargos`
--

INSERT INTO `pm_cargos` (`id`, `descripcion`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, 'cargo 1 editado fn', 0, 'admin', '2024-10-27 05:43:32'),
(2, 'cargo 2 pruebasa', 1, 'admin', '2024-10-17 22:23:34'),
(11, 'sdsda', 1, 'administrador2', '2024-10-17 14:21:07'),
(33, 'pension', 1, 'admin', '2024-10-18 03:07:11'),
(34, 'pensionasasa', 0, 'admin', '2024-10-18 03:06:22'),
(35, 'pensionasasaa', 0, 'admin', '2024-10-18 03:06:11'),
(36, 'aaaa', 1, 'admin', '2024-10-17 19:20:40'),
(37, 'eeeeeee', 1, 'admin', '2024-10-17 19:24:47'),
(38, 'pruebasssss', 0, 'admin', '2024-10-18 03:12:24'),
(39, 'esto es una pruebas', 0, 'admin', '2024-10-18 03:15:02'),
(42, '', 1, 'admin', '2024-10-24 00:54:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_conceptos`
--

CREATE TABLE `pm_conceptos` (
  `id` int(11) NOT NULL,
  `Codigo` varchar(10) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `Tipo_Movimiento` enum('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1: Devengos, 2: Descuentos, 3: Total Devengos, 4: Total Descuento, 5: Valor Neto, 6: Informativo, 7: Aporte Patrono, 8: PARAFISCALES, 9: Provision',
  `Tipo_concepto` enum('1','2','3') NOT NULL COMMENT '1: Pensionado, 2: Activo, 3: Sustituto',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pm_conceptos`
--

INSERT INTO `pm_conceptos` (`id`, `Codigo`, `descripcion`, `Tipo_Movimiento`, `Tipo_concepto`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, 'SAL2', 'Desc  salud empleado s', '6', '1', 1, 'admin', '2024-11-03 00:27:19'),
(2, 'SAL1', 'Desc salud pensionado ed', '2', '1', 1, 'admin', '2024-11-02 22:58:40'),
(3, 'BRU1', 'SALARIO BRUTO ACTIVOS', '1', '2', 1, 'ADMIN_JP', '2024-11-02 15:18:04'),
(4, 'BRU2', 'Este concepto fuecreado por la vista', '1', '1', 1, 'admin', '2024-11-03 00:27:03'),
(5, 'PRUE01', 'PRUEBA', '1', '3', 1, 'admin', '2024-11-03 00:47:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_definiciones`
--

CREATE TABLE `pm_definiciones` (
  `id` int(11) NOT NULL,
  `codigo` varchar(6) NOT NULL COMMENT 'CODIGO',
  `descripcion` varchar(100) NOT NULL COMMENT 'descripcion de la definicion',
  `valor` varchar(200) NOT NULL COMMENT 'valor de la definicion',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_registro` varchar(30) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pm_definiciones`
--

INSERT INTO `pm_definiciones` (`id`, `codigo`, `descripcion`, `valor`, `fecha_registro`, `usuario_registro`) VALUES
(1, 'FRNPRY', 'FIRMA REPORTE NOMINA (PROYECTÓ)', 'CCera', '2024-11-03 13:03:00', 'admin'),
(2, 'FRNRV1', 'FIRMA REPORTE NOMINA (REVISION 1)', 'ACastaño', '2024-11-03 13:04:15', 'admin'),
(3, 'FRNRV2', 'FIRMA REPORTE NOMINA (REVISION 2)\r\n', 'EMiranda', '2024-11-03 13:04:51', 'admin'),
(4, 'FRNRV3', 'FIRMA REPORTE NOMINA (REVISION 3)\r\n', 'JTorregroza', '2024-11-03 13:05:26', 'admin'),
(5, 'FRNRV4', 'FIRMA REPORTE NOMINA (REVISION 4)\r\n', 'ADelahoz', '2024-11-03 13:06:02', 'admin'),
(6, 'DIREC1', 'DIRECCIÓN PARTE 1', 'Calle 34 No. 43 - 79 | Barranquilla, Colombia', '2024-11-03 13:08:50', 'admin'),
(7, 'DIREC2', 'DIRECCION PARTE 2', 'Edificio BCH Piso 5 | teléfono: 3707833', '2024-11-03 13:09:13', 'admin'),
(8, 'PAGWEB', 'PAGINA WEB ', 'www.Dirliquidaciones.gov.co', '2024-11-03 13:09:58', 'admin'),
(9, 'CARGDI', 'Cargo director DDL', 'Director Dirección Distrital de Liquidaciones', '2024-11-03 13:12:41', 'admin'),
(10, 'NOMDIR', 'NOMBRE DIRECTOR DDL', 'CARLOS CASTELLANOS COLLANTE', '2024-11-03 13:13:20', 'admin'),
(11, 'DESENT', 'Descripción entidad', 'Entidad facultada para administrar el Pasivo Pensional de la extinta EDT en liquidación', '2024-11-13 21:36:28', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_entidades`
--

CREATE TABLE `pm_entidades` (
  `id` int(11) NOT NULL,
  `nit` bigint(20) NOT NULL,
  `dv` int(1) NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_entidad` int(11) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla entidades de salud, pension , arl, caja de compensacio';

--
-- Volcado de datos para la tabla `pm_entidades`
--

INSERT INTO `pm_entidades` (`id`, `nit`, `dv`, `nombre`, `tipo_entidad`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, 900800788, 1, 'salud total prueba de edicion', 3, 0, 'admin', '2024-10-24 08:02:06'),
(2, 900800778, 1, 'colfondos CESANTIAS', 2, 1, 'admin', '2024-10-24 03:50:20'),
(3, 900800778, 0, 'colfondos PENSIONES', 1, 1, 'administrador', '2024-10-22 23:32:20'),
(5, 800547412, 8, 'entidad de ARL', 4, 1, 'admin', '2024-10-24 03:54:03'),
(11, 541214587, 5, 'cajacopi', 5, 1, 'admin_jp', '2024-10-25 19:50:51'),
(12, 78541254, 0, 'COOMEVA', 3, 1, 'admin_jp', '2024-10-25 19:54:26'),
(13, 14587712, 2, 'FNA', 2, 1, 'administrador', '2024-10-25 19:54:44'),
(14, 547821452, 9, 'COLPENSIONES', 1, 1, 'admin_jp', '2024-10-25 19:55:12'),
(15, 87451246, 8, 'CAJAMAG', 5, 1, 'admin_jp', '2024-10-25 19:55:34'),
(16, 654123369, 9, 'POSITIVA', 4, 1, 'admin_jp', '2024-10-25 19:55:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_periodo`
--

CREATE TABLE `pm_periodo` (
  `id` int(11) NOT NULL,
  `codigo` varchar(8) NOT NULL COMMENT 'Fecha del periodo en formato YYYYMMDD, 01 si es primera quincena, 02 si es segunda quincena',
  `tipo` enum('M','Q') NOT NULL COMMENT 'M: para mensual, Q_ para quincenal',
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL,
  `mes` varchar(30) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) NOT NULL DEFAULT 'administrador',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pm_periodo`
--

INSERT INTO `pm_periodo` (`id`, `codigo`, `tipo`, `fecha_inicial`, `fecha_final`, `mes`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, '202401M', 'M', '2024-01-01', '2024-01-31', 'Enero', 1, 'administrador', '2024-10-24 19:49:23'),
(2, '202402M', 'M', '2024-02-01', '2024-02-29', 'Febrero', 1, 'administrador', '2024-10-24 19:49:23'),
(3, '202403M', 'M', '2024-03-01', '2024-03-31', 'Marzo', 1, 'administrador', '2024-10-24 19:49:23'),
(4, '202404M', 'M', '2024-04-01', '2024-04-30', 'Abril', 1, 'administrador', '2024-10-24 19:49:23'),
(5, '202405M', 'M', '2024-05-01', '2024-05-31', 'Mayo', 1, 'administrador', '2024-10-24 19:49:23'),
(6, '202406M', 'M', '2024-06-01', '2024-06-30', 'Junio', 1, 'administrador', '2024-10-24 19:49:23'),
(7, '202407M', 'M', '2024-07-01', '2024-07-31', 'Julio', 1, 'administrador', '2024-10-24 19:49:23'),
(8, '202408M', 'M', '2024-08-01', '2024-08-31', 'Agosto', 1, 'administrador', '2024-10-24 19:49:23'),
(9, '202409M', 'M', '2024-09-01', '2024-09-30', 'Septiembre', 1, 'administrador', '2024-10-24 19:49:23'),
(10, '202410M', 'M', '2024-10-01', '2024-10-31', 'Octubre', 1, 'administrador', '2024-10-24 19:49:23'),
(11, '202411M', 'M', '2024-11-01', '2024-11-30', 'Noviembre', 1, 'administrador', '2024-10-24 19:49:23'),
(12, '202412M', 'M', '2024-12-01', '2024-12-31', 'Diciembre', 1, 'administrador', '2024-10-24 19:49:23'),
(13, '202501M', 'M', '2025-01-01', '2025-01-31', 'Enero', 1, 'administrador', '2024-10-24 19:49:23'),
(14, '202502M', 'M', '2025-02-01', '2025-02-28', 'Febrero', 1, 'administrador', '2024-10-24 19:49:23'),
(15, '202503M', 'M', '2025-03-01', '2025-03-31', 'Marzo', 1, 'administrador', '2024-10-24 19:49:23'),
(16, '202504M', 'M', '2025-04-01', '2025-04-30', 'Abril', 1, 'administrador', '2024-10-24 19:49:23'),
(17, '202505M', 'M', '2025-05-01', '2025-05-31', 'Mayo', 1, 'administrador', '2024-10-24 19:49:23'),
(18, '202506M', 'M', '2025-06-01', '2025-06-30', 'Junio', 1, 'administrador', '2024-10-24 19:49:23'),
(19, '202507M', 'M', '2025-07-01', '2025-07-31', 'Julio', 1, 'administrador', '2024-10-24 19:49:23'),
(20, '202508M', 'M', '2025-08-01', '2025-08-31', 'Agosto', 1, 'administrador', '2024-10-24 19:49:23'),
(21, '202509M', 'M', '2025-09-01', '2025-09-30', 'Septiembre', 1, 'administrador', '2024-10-24 19:49:23'),
(22, '202510M', 'M', '2025-10-01', '2025-10-31', 'Octubre', 1, 'administrador', '2024-10-24 19:49:23'),
(23, '202511M', 'M', '2025-11-01', '2025-11-30', 'Noviembre', 1, 'administrador', '2024-10-24 19:49:23'),
(24, '202512M', 'M', '2025-12-01', '2025-12-31', 'Diciembre', 1, 'administrador', '2024-10-24 19:49:23'),
(25, '202601M', 'M', '2026-01-01', '2026-01-31', 'Enero', 1, 'administrador', '2024-10-24 19:49:23'),
(26, '202602M', 'M', '2026-02-01', '2026-02-28', 'Febrero', 1, 'administrador', '2024-10-24 19:49:23'),
(27, '202603M', 'M', '2026-03-01', '2026-03-31', 'Marzo', 1, 'administrador', '2024-10-24 19:49:23'),
(28, '202604M', 'M', '2026-04-01', '2026-04-30', 'Abril', 1, 'administrador', '2024-10-24 19:49:23'),
(29, '202605M', 'M', '2026-05-01', '2026-05-31', 'Mayo', 1, 'administrador', '2024-10-24 19:49:23'),
(30, '202606M', 'M', '2026-06-01', '2026-06-30', 'Junio', 1, 'administrador', '2024-10-24 19:49:23'),
(31, '202607M', 'M', '2026-07-01', '2026-07-31', 'Julio', 1, 'administrador', '2024-10-24 19:49:23'),
(32, '202608M', 'M', '2026-08-01', '2026-08-31', 'Agosto', 1, 'administrador', '2024-10-24 19:49:23'),
(33, '202609M', 'M', '2026-09-01', '2026-09-30', 'Septiembre', 1, 'administrador', '2024-10-24 19:49:23'),
(34, '202610M', 'M', '2026-10-01', '2026-10-31', 'Octubre', 1, 'administrador', '2024-10-24 19:49:23'),
(35, '202611M', 'M', '2026-11-01', '2026-11-30', 'Noviembre', 1, 'administrador', '2024-10-24 19:49:23'),
(36, '202612M', 'M', '2026-12-01', '2026-12-31', 'Diciembre', 1, 'administrador', '2024-10-24 19:49:23'),
(37, '202701M', 'M', '2027-01-01', '2027-01-31', 'Enero', 1, 'administrador', '2024-10-24 19:49:23'),
(38, '202702M', 'M', '2027-02-01', '2027-02-28', 'Febrero', 1, 'administrador', '2024-10-24 19:49:23'),
(39, '202703M', 'M', '2027-03-01', '2027-03-31', 'Marzo', 1, 'administrador', '2024-10-24 19:49:23'),
(40, '202704M', 'M', '2027-04-01', '2027-04-30', 'Abril', 1, 'administrador', '2024-10-24 19:49:23'),
(41, '202705M', 'M', '2027-05-01', '2027-05-31', 'Mayo', 1, 'administrador', '2024-10-24 19:49:23'),
(42, '202706M', 'M', '2027-06-01', '2027-06-30', 'Junio', 1, 'administrador', '2024-10-24 19:49:23'),
(43, '202707M', 'M', '2027-07-01', '2027-07-31', 'Julio', 1, 'administrador', '2024-10-24 19:49:23'),
(44, '202708M', 'M', '2027-08-01', '2027-08-31', 'Agosto', 1, 'administrador', '2024-10-24 19:49:23'),
(45, '202709M', 'M', '2027-09-01', '2027-09-30', 'Septiembre', 1, 'administrador', '2024-10-24 19:49:23'),
(46, '202710M', 'M', '2027-10-01', '2027-10-31', 'Octubre', 1, 'administrador', '2024-10-24 19:49:23'),
(47, '202711M', 'M', '2027-11-01', '2027-11-30', 'Noviembre', 1, 'administrador', '2024-10-24 19:49:23'),
(48, '202712M', 'M', '2027-12-01', '2027-12-31', 'Diciembre', 1, 'administrador', '2024-10-24 19:49:23'),
(49, '202801M', 'M', '2028-01-01', '2028-01-31', 'Enero', 1, 'administrador', '2024-10-24 19:49:23'),
(50, '202802M', 'M', '2028-02-01', '2028-02-29', 'Febrero', 1, 'administrador', '2024-10-24 19:49:23'),
(51, '202803M', 'M', '2028-03-01', '2028-03-31', 'Marzo', 1, 'administrador', '2024-10-24 19:49:23'),
(52, '202804M', 'M', '2028-04-01', '2028-04-30', 'Abril', 1, 'administrador', '2024-10-24 19:49:23'),
(53, '202805M', 'M', '2028-05-01', '2028-05-31', 'Mayo', 1, 'administrador', '2024-10-24 19:49:23'),
(54, '202806M', 'M', '2028-06-01', '2028-06-30', 'Junio', 1, 'administrador', '2024-10-24 19:49:23'),
(55, '202807M', 'M', '2028-07-01', '2028-07-31', 'Julio', 1, 'administrador', '2024-10-24 19:49:23'),
(56, '202808M', 'M', '2028-08-01', '2028-08-31', 'Agosto', 1, 'administrador', '2024-10-24 19:49:23'),
(57, '202809M', 'M', '2028-09-01', '2028-09-30', 'Septiembre', 1, 'administrador', '2024-10-24 19:49:23'),
(58, '202810M', 'M', '2028-10-01', '2028-10-31', 'Octubre', 1, 'administrador', '2024-10-24 19:49:23'),
(59, '202811M', 'M', '2028-11-01', '2028-11-30', 'Noviembre', 1, 'administrador', '2024-10-24 19:49:23'),
(60, '202812M', 'M', '2028-12-01', '2028-12-31', 'Diciembre', 1, 'administrador', '2024-10-24 19:49:23'),
(61, '202901M', 'M', '2029-01-01', '2029-01-31', 'Enero', 1, 'administrador', '2024-10-24 19:49:23'),
(62, '202902M', 'M', '2029-02-01', '2029-02-28', 'Febrero', 1, 'administrador', '2024-10-24 19:49:23'),
(63, '202903M', 'M', '2029-03-01', '2029-03-31', 'Marzo', 1, 'administrador', '2024-10-24 19:49:23'),
(64, '202904M', 'M', '2029-04-01', '2029-04-30', 'Abril', 1, 'administrador', '2024-10-24 19:49:23'),
(65, '202905M', 'M', '2029-05-01', '2029-05-31', 'Mayo', 1, 'administrador', '2024-10-24 19:49:23'),
(66, '202906M', 'M', '2029-06-01', '2029-06-30', 'Junio', 1, 'administrador', '2024-10-24 19:49:23'),
(67, '202907M', 'M', '2029-07-01', '2029-07-31', 'Julio', 1, 'administrador', '2024-10-24 19:49:23'),
(68, '202908M', 'M', '2029-08-01', '2029-08-31', 'Agosto', 1, 'administrador', '2024-10-24 19:49:23'),
(69, '202909M', 'M', '2029-09-01', '2029-09-30', 'Septiembre', 1, 'administrador', '2024-10-24 19:49:23'),
(70, '202910M', 'M', '2029-10-01', '2029-10-31', 'Octubre', 1, 'administrador', '2024-10-24 19:49:23'),
(71, '202911M', 'M', '2029-11-01', '2029-11-30', 'Noviembre', 1, 'administrador', '2024-10-24 19:49:23'),
(72, '202912M', 'M', '2029-12-01', '2029-12-31', 'Diciembre', 1, 'administrador', '2024-10-24 19:49:23'),
(73, '203001M', 'M', '2030-01-01', '2030-01-31', 'Enero', 1, 'administrador', '2024-10-24 19:49:23'),
(74, '203002M', 'M', '2030-02-01', '2030-02-28', 'Febrero', 1, 'administrador', '2024-10-24 19:49:23'),
(75, '203003M', 'M', '2030-03-01', '2030-03-31', 'Marzo', 1, 'administrador', '2024-10-24 19:49:23'),
(76, '203004M', 'M', '2030-04-01', '2030-04-30', 'Abril', 1, 'administrador', '2024-10-24 19:49:23'),
(77, '203005M', 'M', '2030-05-01', '2030-05-31', 'Mayo', 1, 'administrador', '2024-10-24 19:49:23'),
(78, '203006M', 'M', '2030-06-01', '2030-06-30', 'Junio', 1, 'administrador', '2024-10-24 19:49:23'),
(79, '203007M', 'M', '2030-07-01', '2030-07-31', 'Julio', 1, 'administrador', '2024-10-24 19:49:23'),
(80, '203008M', 'M', '2030-08-01', '2030-08-31', 'Agosto', 1, 'administrador', '2024-10-24 19:49:23'),
(81, '203009M', 'M', '2030-09-01', '2030-09-30', 'Septiembre', 1, 'administrador', '2024-10-24 19:49:23'),
(82, '203010M', 'M', '2030-10-01', '2030-10-31', 'Octubre', 1, 'administrador', '2024-10-24 19:49:23'),
(83, '203011M', 'M', '2030-11-01', '2030-11-30', 'Noviembre', 1, 'administrador', '2024-10-24 19:49:23'),
(84, '203012M', 'M', '2030-12-01', '2030-12-31', 'Diciembre', 1, 'administrador', '2024-10-24 19:49:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_tipo_empleado`
--

CREATE TABLE `pm_tipo_empleado` (
  `id` int(2) NOT NULL,
  `codigo` varchar(2) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pm_tipo_empleado`
--

INSERT INTO `pm_tipo_empleado` (`id`, `codigo`, `descripcion`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, 'PE', 'PENSIONADO', 1, 'administrador', '2024-10-16 00:00:00'),
(2, 'AC', 'ACTIVO', 1, 'administrador', '2024-10-16 14:17:54'),
(3, 'SU', 'SUSTITUTO', 1, 'administrador', '2024-10-16 14:18:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_tipo_entidad`
--

CREATE TABLE `pm_tipo_entidad` (
  `id` int(2) NOT NULL,
  `codigo` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla tipos de entidades';

--
-- Volcado de datos para la tabla `pm_tipo_entidad`
--

INSERT INTO `pm_tipo_entidad` (`id`, `codigo`, `descripcion`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, 'PE', 'Pensión', 1, 'administrador', '2024-10-16 00:00:00'),
(2, 'CE', 'Cesantias', 1, 'administrador', '2024-10-16 00:00:00'),
(3, 'SA', 'Salud', 1, 'administrador', '2024-10-16 14:08:40'),
(4, 'AR', 'Arl', 1, 'administrador', '2024-10-16 14:08:58'),
(5, 'CA', 'Caja de compensación', 1, 'administrador', '2024-10-16 14:09:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_tipo_identificacion`
--

CREATE TABLE `pm_tipo_identificacion` (
  `tipo_identificacion` varchar(2) NOT NULL,
  `descripcion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pm_tipo_identificacion`
--

INSERT INTO `pm_tipo_identificacion` (`tipo_identificacion`, `descripcion`) VALUES
('CC', 'Cedula de ciudadania'),
('CE', 'Cedula de extranjeria'),
('NI', 'Nit'),
('PA', 'Pasaporte'),
('TI', 'Tarjeta de Identidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pm_tipo_riesgo`
--

CREATE TABLE `pm_tipo_riesgo` (
  `id` int(1) NOT NULL,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `porcentaje` float NOT NULL COMMENT 'Porcentaje de Cotización',
  `estado` int(1) NOT NULL DEFAULT 1,
  `usuario_registro` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pm_tipo_riesgo`
--

INSERT INTO `pm_tipo_riesgo` (`id`, `descripcion`, `porcentaje`, `estado`, `usuario_registro`, `fecha_registro`) VALUES
(1, 'Clase I: Riesgo Mínimo', 0.522, 1, 'administrador', '2024-10-16 19:55:31'),
(2, 'Clase II: Riesgo Bajo', 1.044, 1, 'administrador', '2024-10-16 19:56:16'),
(3, 'Clase III: Riesgo Medio', 2.436, 1, 'administrador', '2024-10-16 19:56:34'),
(4, 'Clase IV: Riesgo Alto', 4.35, 1, 'administrador', '2024-10-16 19:57:06'),
(5, 'Clase V: Riesgo Máximo', 6.96, 1, 'administrador', '2024-10-16 19:57:41'),
(6, 'SIN RIESGOS', 0, 1, 'administrador', '2024-10-25 19:51:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `firstname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  `date_added` datetime NOT NULL,
  `perfil` enum('Administrador','Gerente','Empleado','Usuario') COLLATE utf8_unicode_ci DEFAULT 'Usuario',
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identificacion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_persona` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `user_name`, `user_password_hash`, `user_email`, `date_added`, `perfil`, `direccion`, `telefono`, `identificacion`, `tipo_persona`, `tipo_id`) VALUES
(1, 'Administrador', 'Sistema', 'admin', '$2y$10$MPVHzZ2ZPOWmtUUGCq3RXu31OTB.jo7M9LZ7PmPQYmgETSNn19ejO', 'prueba@gmail.com', '2016-05-21 15:06:00', 'Administrador', NULL, NULL, NULL, 'Natural', 'CC'),
(102, 'Andres', 'Damiana', 'admin1235', '$2y$10$nc/y2TYXes5lVo.JGrs5dudkXRBwTchGYpXGmTxdIRJvM49leQLLu', 'ddddejllll@gmail.com', '2023-01-19 16:33:10', 'Administrador', 'CARRERA 12 N 23 117 Jorge Eliecer Gaitán', '3168270783', '23456', 'Natural', 'CC'),
(109, 'administrador', 'administrador', 'administrador', '$2y$10$djJ/kNpaOouUIc4uDuMrvOrAfe6Scn4KL/.f2X3mdi1h1GjCaAV2G', 'administrador@email.com', '2023-03-13 18:04:43', 'Gerente', '', '', '', 'Natural', 'CC'),
(126, 'jose', 'pernet', 'josepernett', '$2y$10$DsYsIB1b5f1VTDdS7ACQNOzN.oAbTv7mudzg9H9LbgmjIkVjjbY6y', 'jjpernetr2005@gmail.com', '2024-10-17 16:14:35', 'Gerente', '', '', '', '', ''),
(127, 'jose', 'pernet', 'webmasters', '$2y$10$E3lenIjuaypgENFPI0CcpOTZ94nuc3bM/jMqV.m9v6pfhYdNOizry', 'jjpernetr20a05@gmail.com', '2024-10-17 16:32:19', 'Gerente', '', '', '', '', ''),
(128, 'prueba', 'pernet', 'aaaaaaaaa', '$2y$10$gj3lwzqgBpN4sCg2A1dVp.gcByVerDrosMgjVnkKGBWMNwr2R61Ti', 'jjpernetr200s5@gmail.com', '2024-10-17 16:34:38', 'Gerente', '', '', '', '', ''),
(129, 'jose', 'perneta', 'josepernettaaa', '$2y$10$PsN2Ovv/JD5lQQp1P6crDuZKBVOH4dlFsiUndmL/HjvsPH1Zrk/cO', 'jjpernetr20aa05@gmail.com', '2024-10-17 17:37:20', 'Administrador', '', '', '', '', ''),
(130, 'jose', 'retamozo', 'juliopernettss', '$2y$10$XPq9.XjES0ZfcKVg9/gD9.saX95/AuSHWv6PW8BmNMnyQ3Gt1IYIG', 'josejretamozo@gmail.com', '2024-10-29 00:49:49', 'Gerente', NULL, NULL, NULL, '', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `gt_documentos`
--
ALTER TABLE `gt_documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `idx_doc_rot` (`id_empleado`);

--
-- Indices de la tabla `gt_empleado`
--
ALTER TABLE `gt_empleado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_IDENTIFICACION` (`identificacion`,`id_tipo_identificacion`),
  ADD KEY `fk_tipo_identificacion` (`id_tipo_identificacion`),
  ADD KEY `fk_id_cargo` (`id_cargo`),
  ADD KEY `fk_id_riesgo_arl` (`id_riesgo_arl`),
  ADD KEY `fk_id_eps` (`id_eps`),
  ADD KEY `fk_id_fondo_pension` (`id_fondo_pension`),
  ADD KEY `fk_id_fondo_cesantias` (`id_fondo_cesantias`),
  ADD KEY `fk_id_arl` (`id_arl`),
  ADD KEY `fk_id_caja_compensacion` (`id_caja_compensacion`),
  ADD KEY `fk_id_tipo_empleado` (`id_tipo_empleado`),
  ADD KEY `fk_id_Empleado_sustituto` (`id_Empleado_sustituto`);

--
-- Indices de la tabla `gt_novedades`
--
ALTER TABLE `gt_novedades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_concepto` (`id_concepto`),
  ADD KEY `id_periodo` (`id_periodo`),
  ADD KEY `fk_resolucion` (`resolucion_id`);

--
-- Indices de la tabla `gt_resoluciones`
--
ALTER TABLE `gt_resoluciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_RESOL_FECHA` (`numero`,`fecha_resolucion`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `idx_notificacion_fk_idx` (`id_novedad`);

--
-- Indices de la tabla `pm_cargos`
--
ALTER TABLE `pm_cargos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pm_conceptos`
--
ALTER TABLE `pm_conceptos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_codigo_concepto` (`Codigo`);

--
-- Indices de la tabla `pm_definiciones`
--
ALTER TABLE `pm_definiciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `pm_entidades`
--
ALTER TABLE `pm_entidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pm_entidades_uq` (`nit`,`tipo_entidad`),
  ADD KEY `fk_tipo_entidad` (`tipo_entidad`);

--
-- Indices de la tabla `pm_periodo`
--
ALTER TABLE `pm_periodo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pm_tipo_empleado`
--
ALTER TABLE `pm_tipo_empleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pm_tipo_entidad`
--
ALTER TABLE `pm_tipo_entidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pm_tipo_identificacion`
--
ALTER TABLE `pm_tipo_identificacion`
  ADD PRIMARY KEY (`tipo_identificacion`);

--
-- Indices de la tabla `pm_tipo_riesgo`
--
ALTER TABLE `pm_tipo_riesgo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `gt_documentos`
--
ALTER TABLE `gt_documentos`
  MODIFY `id_documento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `gt_empleado`
--
ALTER TABLE `gt_empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `gt_novedades`
--
ALTER TABLE `gt_novedades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `gt_resoluciones`
--
ALTER TABLE `gt_resoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pm_cargos`
--
ALTER TABLE `pm_cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `pm_conceptos`
--
ALTER TABLE `pm_conceptos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pm_definiciones`
--
ALTER TABLE `pm_definiciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pm_entidades`
--
ALTER TABLE `pm_entidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `pm_periodo`
--
ALTER TABLE `pm_periodo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de la tabla `pm_tipo_empleado`
--
ALTER TABLE `pm_tipo_empleado`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pm_tipo_entidad`
--
ALTER TABLE `pm_tipo_entidad`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pm_tipo_riesgo`
--
ALTER TABLE `pm_tipo_riesgo`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index', AUTO_INCREMENT=131;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gt_documentos`
--
ALTER TABLE `gt_documentos`
  ADD CONSTRAINT `idx_doc_rot` FOREIGN KEY (`id_empleado`) REFERENCES `gt_empleado` (`id`);

--
-- Filtros para la tabla `gt_empleado`
--
ALTER TABLE `gt_empleado`
  ADD CONSTRAINT `fk_id_Empleado_sustituto` FOREIGN KEY (`id_Empleado_sustituto`) REFERENCES `gt_empleado` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_arl` FOREIGN KEY (`id_arl`) REFERENCES `pm_entidades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_caja_compensacion` FOREIGN KEY (`id_caja_compensacion`) REFERENCES `pm_entidades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `pm_cargos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_eps` FOREIGN KEY (`id_eps`) REFERENCES `pm_entidades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_fondo_cesantias` FOREIGN KEY (`id_fondo_cesantias`) REFERENCES `pm_entidades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_fondo_pension` FOREIGN KEY (`id_fondo_pension`) REFERENCES `pm_entidades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_riesgo_arl` FOREIGN KEY (`id_riesgo_arl`) REFERENCES `pm_tipo_riesgo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_tipo_empleado` FOREIGN KEY (`id_tipo_empleado`) REFERENCES `pm_tipo_empleado` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tipo_identificacion` FOREIGN KEY (`id_tipo_identificacion`) REFERENCES `pm_tipo_identificacion` (`tipo_identificacion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gt_novedades`
--
ALTER TABLE `gt_novedades`
  ADD CONSTRAINT `fk_resolucion` FOREIGN KEY (`resolucion_id`) REFERENCES `gt_resoluciones` (`id`),
  ADD CONSTRAINT `gt_novedades_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `gt_empleado` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gt_novedades_ibfk_2` FOREIGN KEY (`id_concepto`) REFERENCES `pm_conceptos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gt_novedades_ibfk_3` FOREIGN KEY (`id_periodo`) REFERENCES `pm_periodo` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pm_entidades`
--
ALTER TABLE `pm_entidades`
  ADD CONSTRAINT `fk_tipo_entidad` FOREIGN KEY (`tipo_entidad`) REFERENCES `pm_tipo_entidad` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
