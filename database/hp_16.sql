-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2026 a las 17:39:15
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
-- Base de datos: `hp_16`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bibliografia`
--

CREATE TABLE `bibliografia` (
  `id_bibliografia` int(11) NOT NULL,
  `nombre_bibliografia` text DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bibliografia`
--

INSERT INTO `bibliografia` (`id_bibliografia`, `nombre_bibliografia`, `estatus`) VALUES
(1, 'llll', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_bitacora` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `modulo_afectado_bitacora` varchar(255) DEFAULT NULL,
  `tabla_afectada_bitacora` text DEFAULT NULL,
  `id_registro_afectado_bitacora` text DEFAULT NULL,
  `accion_bitacora` enum('CREAR','MODIFICAR','MOSTRAR','ELIMINAR','LOGIN','LOGOUT','REPORTE') DEFAULT NULL,
  `valores_anteriores_bitacora` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_anteriores_bitacora`)),
  `valores_nuevos_bitacora` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_nuevos_bitacora`)),
  `ip_origen_bitacora` varchar(45) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_bitacora`, `id_usuario`, `modulo_afectado_bitacora`, `tabla_afectada_bitacora`, `id_registro_afectado_bitacora`, `accion_bitacora`, `valores_anteriores_bitacora`, `valores_nuevos_bitacora`, `ip_origen_bitacora`, `fecha_creacion`, `estatus`) VALUES
(5, 1, 'CalendarioAcademico', 'calendario_academico', '5', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"estatus\":\"1\",\"id_calendario_academico\":5}', '127.0.0.1', '2026-04-23 04:53:10', '1'),
(6, 1, 'Evento', 'evento', '1', 'CREAR', NULL, '{\"leyenda_evento\":\"Actividad Administrativa\",\"tipo_evento\":\"2\",\"estatus\":\"1\",\"id_evento\":1}', '127.0.0.1', '2026-04-23 04:53:11', '1'),
(7, 1, 'DetalleEvento', 'detalle_evento', '1', 'CREAR', NULL, '{\"id_evento\":1,\"id_calendario_academico\":5,\"dia_inicio_detalle_evento\":\"2026-04-01\",\"dia_fin_detalle_evento\":\"2026-04-02\",\"semana_detalle_evento\":null,\"estatus\":\"1\",\"id_detalle_evento\":1}', '127.0.0.1', '2026-04-23 04:53:11', '1'),
(8, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-23 04:53:17', '1'),
(9, 39195, 'Roles (Permisos)', 'rol_permiso', '1', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"62\",\"estatus\":\"1\",\"id_rol_permiso\":1}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(10, 39195, 'Roles (Permisos)', 'rol_permiso', '2', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"25\",\"estatus\":\"1\",\"id_rol_permiso\":2}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(11, 39195, 'Roles (Permisos)', 'rol_permiso', '3', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"26\",\"estatus\":\"1\",\"id_rol_permiso\":3}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(12, 39195, 'Roles (Permisos)', 'rol_permiso', '4', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"24\",\"estatus\":\"1\",\"id_rol_permiso\":4}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(13, 39195, 'Roles (Permisos)', 'rol_permiso', '5', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"27\",\"estatus\":\"1\",\"id_rol_permiso\":5}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(14, 39195, 'Roles (Permisos)', 'rol_permiso', '6', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"69\",\"estatus\":\"1\",\"id_rol_permiso\":6}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(15, 39195, 'Roles (Permisos)', 'rol_permiso', '7', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"52\",\"estatus\":\"1\",\"id_rol_permiso\":7}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(16, 39195, 'Roles (Permisos)', 'rol_permiso', '8', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"53\",\"estatus\":\"1\",\"id_rol_permiso\":8}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(17, 39195, 'Roles (Permisos)', 'rol_permiso', '9', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"60\",\"estatus\":\"1\",\"id_rol_permiso\":9}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(18, 39195, 'Roles (Permisos)', 'rol_permiso', '10', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"48\",\"estatus\":\"1\",\"id_rol_permiso\":10}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(19, 39195, 'Roles (Permisos)', 'rol_permiso', '11', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"47\",\"estatus\":\"1\",\"id_rol_permiso\":11}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(20, 39195, 'Roles (Permisos)', 'rol_permiso', '12', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"19\",\"estatus\":\"1\",\"id_rol_permiso\":12}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(21, 39195, 'Roles (Permisos)', 'rol_permiso', '13', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"49\",\"estatus\":\"1\",\"id_rol_permiso\":13}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(22, 39195, 'Roles (Permisos)', 'rol_permiso', '14', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"56\",\"estatus\":\"1\",\"id_rol_permiso\":14}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(23, 39195, 'Roles (Permisos)', 'rol_permiso', '15', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"4\",\"estatus\":\"1\",\"id_rol_permiso\":15}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(24, 39195, 'Roles (Permisos)', 'rol_permiso', '16', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"5\",\"estatus\":\"1\",\"id_rol_permiso\":16}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(25, 39195, 'Roles (Permisos)', 'rol_permiso', '17', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"3\",\"estatus\":\"1\",\"id_rol_permiso\":17}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(26, 39195, 'Roles (Permisos)', 'rol_permiso', '18', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"6\",\"estatus\":\"1\",\"id_rol_permiso\":18}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(27, 39195, 'Roles (Permisos)', 'rol_permiso', '19', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"64\",\"estatus\":\"1\",\"id_rol_permiso\":19}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(28, 39195, 'Roles (Permisos)', 'rol_permiso', '20', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"33\",\"estatus\":\"1\",\"id_rol_permiso\":20}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(29, 39195, 'Roles (Permisos)', 'rol_permiso', '21', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"34\",\"estatus\":\"1\",\"id_rol_permiso\":21}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(30, 39195, 'Roles (Permisos)', 'rol_permiso', '22', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"32\",\"estatus\":\"1\",\"id_rol_permiso\":22}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(31, 39195, 'Roles (Permisos)', 'rol_permiso', '23', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"35\",\"estatus\":\"1\",\"id_rol_permiso\":23}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(32, 39195, 'Roles (Permisos)', 'rol_permiso', '24', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"67\",\"estatus\":\"1\",\"id_rol_permiso\":24}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(33, 39195, 'Roles (Permisos)', 'rol_permiso', '25', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"45\",\"estatus\":\"1\",\"id_rol_permiso\":25}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(34, 39195, 'Roles (Permisos)', 'rol_permiso', '26', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"44\",\"estatus\":\"1\",\"id_rol_permiso\":26}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(35, 39195, 'Roles (Permisos)', 'rol_permiso', '27', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"46\",\"estatus\":\"1\",\"id_rol_permiso\":27}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(36, 39195, 'Roles (Permisos)', 'rol_permiso', '28', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"61\",\"estatus\":\"1\",\"id_rol_permiso\":28}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(37, 39195, 'Roles (Permisos)', 'rol_permiso', '29', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"21\",\"estatus\":\"1\",\"id_rol_permiso\":29}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(38, 39195, 'Roles (Permisos)', 'rol_permiso', '30', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"22\",\"estatus\":\"1\",\"id_rol_permiso\":30}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(39, 39195, 'Roles (Permisos)', 'rol_permiso', '31', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"20\",\"estatus\":\"1\",\"id_rol_permiso\":31}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(40, 39195, 'Roles (Permisos)', 'rol_permiso', '32', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"23\",\"estatus\":\"1\",\"id_rol_permiso\":32}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(41, 39195, 'Roles (Permisos)', 'rol_permiso', '33', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"54\",\"estatus\":\"1\",\"id_rol_permiso\":33}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(42, 39195, 'Roles (Permisos)', 'rol_permiso', '34', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"1\",\"estatus\":\"1\",\"id_rol_permiso\":34}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(43, 39195, 'Roles (Permisos)', 'rol_permiso', '35', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"68\",\"estatus\":\"1\",\"id_rol_permiso\":35}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(44, 39195, 'Roles (Permisos)', 'rol_permiso', '36', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"51\",\"estatus\":\"1\",\"id_rol_permiso\":36}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(45, 39195, 'Roles (Permisos)', 'rol_permiso', '37', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"50\",\"estatus\":\"1\",\"id_rol_permiso\":37}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(46, 39195, 'Roles (Permisos)', 'rol_permiso', '38', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"18\",\"estatus\":\"1\",\"id_rol_permiso\":38}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(47, 39195, 'Roles (Permisos)', 'rol_permiso', '39', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"59\",\"estatus\":\"1\",\"id_rol_permiso\":39}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(48, 39195, 'Roles (Permisos)', 'rol_permiso', '40', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"14\",\"estatus\":\"1\",\"id_rol_permiso\":40}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(49, 39195, 'Roles (Permisos)', 'rol_permiso', '41', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"15\",\"estatus\":\"1\",\"id_rol_permiso\":41}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(50, 39195, 'Roles (Permisos)', 'rol_permiso', '42', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"13\",\"estatus\":\"1\",\"id_rol_permiso\":42}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(51, 39195, 'Roles (Permisos)', 'rol_permiso', '43', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"17\",\"estatus\":\"1\",\"id_rol_permiso\":43}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(52, 39195, 'Roles (Permisos)', 'rol_permiso', '44', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"16\",\"estatus\":\"1\",\"id_rol_permiso\":44}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(53, 39195, 'Roles (Permisos)', 'rol_permiso', '45', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"63\",\"estatus\":\"1\",\"id_rol_permiso\":45}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(54, 39195, 'Roles (Permisos)', 'rol_permiso', '46', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"29\",\"estatus\":\"1\",\"id_rol_permiso\":46}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(55, 39195, 'Roles (Permisos)', 'rol_permiso', '47', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"30\",\"estatus\":\"1\",\"id_rol_permiso\":47}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(56, 39195, 'Roles (Permisos)', 'rol_permiso', '48', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"28\",\"estatus\":\"1\",\"id_rol_permiso\":48}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(57, 39195, 'Roles (Permisos)', 'rol_permiso', '49', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"31\",\"estatus\":\"1\",\"id_rol_permiso\":49}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(58, 39195, 'Roles (Permisos)', 'rol_permiso', '50', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"55\",\"estatus\":\"1\",\"id_rol_permiso\":50}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(59, 39195, 'Roles (Permisos)', 'rol_permiso', '51', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"2\",\"estatus\":\"1\",\"id_rol_permiso\":51}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(60, 39195, 'Roles (Permisos)', 'rol_permiso', '52', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"65\",\"estatus\":\"1\",\"id_rol_permiso\":52}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(61, 39195, 'Roles (Permisos)', 'rol_permiso', '53', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"37\",\"estatus\":\"1\",\"id_rol_permiso\":53}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(62, 39195, 'Roles (Permisos)', 'rol_permiso', '54', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"38\",\"estatus\":\"1\",\"id_rol_permiso\":54}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(63, 39195, 'Roles (Permisos)', 'rol_permiso', '55', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"36\",\"estatus\":\"1\",\"id_rol_permiso\":55}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(64, 39195, 'Roles (Permisos)', 'rol_permiso', '56', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"39\",\"estatus\":\"1\",\"id_rol_permiso\":56}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(65, 39195, 'Roles (Permisos)', 'rol_permiso', '57', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"57\",\"estatus\":\"1\",\"id_rol_permiso\":57}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(66, 39195, 'Roles (Permisos)', 'rol_permiso', '58', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"8\",\"estatus\":\"1\",\"id_rol_permiso\":58}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(67, 39195, 'Roles (Permisos)', 'rol_permiso', '59', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"9\",\"estatus\":\"1\",\"id_rol_permiso\":59}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(68, 39195, 'Roles (Permisos)', 'rol_permiso', '60', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"7\",\"estatus\":\"1\",\"id_rol_permiso\":60}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(69, 39195, 'Roles (Permisos)', 'rol_permiso', '61', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"10\",\"estatus\":\"1\",\"id_rol_permiso\":61}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(70, 39195, 'Roles (Permisos)', 'rol_permiso', '62', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"66\",\"estatus\":\"1\",\"id_rol_permiso\":62}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(71, 39195, 'Roles (Permisos)', 'rol_permiso', '63', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"41\",\"estatus\":\"1\",\"id_rol_permiso\":63}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(72, 39195, 'Roles (Permisos)', 'rol_permiso', '64', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"42\",\"estatus\":\"1\",\"id_rol_permiso\":64}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(73, 39195, 'Roles (Permisos)', 'rol_permiso', '65', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"40\",\"estatus\":\"1\",\"id_rol_permiso\":65}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(74, 39195, 'Roles (Permisos)', 'rol_permiso', '66', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"43\",\"estatus\":\"1\",\"id_rol_permiso\":66}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(75, 39195, 'Roles (Permisos)', 'rol_permiso', '67', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"58\",\"estatus\":\"1\",\"id_rol_permiso\":67}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(76, 39195, 'Roles (Permisos)', 'rol_permiso', '68', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"11\",\"estatus\":\"1\",\"id_rol_permiso\":68}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(77, 39195, 'Roles (Permisos)', 'rol_permiso', '69', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"12\",\"estatus\":\"1\",\"id_rol_permiso\":69}', '127.0.0.1', '2026-04-23 04:54:16', '1'),
(78, 39195, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 04:54:50', '1'),
(79, 39195, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 04:54:55', '1'),
(80, 39195, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 04:58:27', '1'),
(81, 39195, 'Evento', 'evento', '1', 'MOSTRAR', '{\"id_evento\":1,\"id_color\":null,\"leyenda_evento\":\"Actividad Administrativa\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 05:01:57', '1'),
(82, 39195, 'Evento', 'evento', '2', 'CREAR', NULL, '{\"leyenda_evento\":\"dsd\",\"tipo_evento\":null,\"estatus\":\"1\",\"id_evento\":2}', '127.0.0.1', '2026-04-23 05:02:52', '1'),
(83, 39195, 'DetalleEvento', 'detalle_evento', '2', 'CREAR', NULL, '{\"id_evento\":2,\"id_calendario_academico\":5,\"dia_inicio_detalle_evento\":\"2026-04-01\",\"dia_fin_detalle_evento\":\"2026-04-16\",\"semana_detalle_evento\":null,\"estatus\":\"1\",\"id_detalle_evento\":2}', '127.0.0.1', '2026-04-23 05:02:52', '1'),
(84, 39195, 'Evento', 'evento', '1', 'MOSTRAR', '{\"id_evento\":1,\"id_color\":null,\"leyenda_evento\":\"Actividad Administrativa\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 05:03:07', '1'),
(85, 39195, 'Evento', 'evento', '2', 'MOSTRAR', '{\"id_evento\":2,\"id_color\":null,\"leyenda_evento\":\"dsd\",\"tipo_evento\":null,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 05:03:10', '1'),
(86, 39195, 'Evento', 'evento', '2', 'MOSTRAR', '{\"id_evento\":2,\"id_color\":null,\"nombre_evento\":\"dsd\",\"tipo_evento\":null,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 05:25:22', '1'),
(87, 39195, 'Evento', 'evento', '3', 'CREAR', NULL, '{\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"id_color\":1,\"estatus\":\"1\",\"id_evento\":3}', '127.0.0.1', '2026-04-23 06:00:19', '1'),
(88, 39195, 'DetalleEvento', 'detalle_evento', '3', 'CREAR', NULL, '{\"id_evento\":3,\"id_calendario_academico\":5,\"dia_inicio_detalle_evento\":\"2026-04-01\",\"dia_fin_detalle_evento\":\"2026-04-01\",\"semana_detalle_evento\":null,\"estatus\":\"1\",\"id_detalle_evento\":3}', '127.0.0.1', '2026-04-23 06:00:19', '1'),
(89, 39195, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":1,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 06:00:32', '1'),
(90, 39195, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":1,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 06:01:16', '1'),
(91, 39195, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":1,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 06:01:17', '1'),
(92, 39195, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":1,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 06:03:33', '1'),
(93, 39195, 'Roles (Permisos)', 'rol_permiso', '70', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"70\",\"estatus\":\"1\",\"id_rol_permiso\":70}', '127.0.0.1', '2026-04-23 06:19:20', '1'),
(94, 39195, 'Evento', 'evento', '2', 'MOSTRAR', '{\"id_evento\":2,\"id_color\":null,\"nombre_evento\":\"dsd\",\"tipo_evento\":null,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 06:19:35', '1'),
(95, 39195, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":1,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 06:19:40', '1'),
(96, 39195, 'Evento', 'evento', '3', 'MODIFICAR', '{\"id_evento\":3,\"id_color\":1,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":3}', '127.0.0.1', '2026-04-23 06:20:05', '1'),
(97, 39195, 'DetalleEvento', 'detalle_evento', '3', 'MODIFICAR', '{\"id_detalle_evento\":3,\"id_evento\":3,\"id_calendario_academico\":5,\"dia_inicio_detalle_evento\":\"2026-04-01\",\"dia_fin_detalle_evento\":\"2026-04-01\",\"semana_detalle_evento\":null,\"estatus\":\"1\"}', '{\"dia_fin_detalle_evento\":\"2026-04-02\"}', '127.0.0.1', '2026-04-23 06:20:05', '1'),
(98, 39195, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 06:20:09', '1'),
(99, 39195, 'Evento', 'evento', '4', 'CREAR', NULL, '{\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"id_color\":5,\"estatus\":\"1\",\"id_evento\":4}', '127.0.0.1', '2026-04-23 06:26:37', '1'),
(100, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-23 20:17:11', '1'),
(101, 43325, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 20:17:27', '1'),
(102, 43325, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 20:17:30', '1'),
(103, 43325, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 20:18:03', '1'),
(104, 43325, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 20:18:11', '1'),
(105, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 20:29:11', '1'),
(106, 43325, 'TecnicaEvaluacion', 'tecnica_evaluacion', '1', 'CREAR', NULL, '{\"nombre_tecnica_evaluacion\":\"\\u00f1\\u00f1\\u00f1\\u00f1\",\"estatus\":\"1\",\"id_tecnica_evaluacion\":1}', '127.0.0.1', '2026-04-23 20:29:54', '1'),
(107, 43325, 'TecnicaEvaluacion', 'tecnica_evaluacion', '1', 'MOSTRAR', '{\"id_tecnica_evaluacion\":1,\"nombre_tecnica_evaluacion\":\"\\u00f1\\u00f1\\u00f1\\u00f1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 20:42:34', '1'),
(108, 1, 'CalendarioAcademico', 'calendario_academico', '5', 'MODIFICAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-23 20:44:07', '1'),
(109, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-23 20:44:25', '1'),
(110, 1, 'CalendarioAcademico', 'calendario_academico', '6', 'CREAR', NULL, '{\"semana_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-07-30\",\"estatus\":\"1\",\"id_calendario_academico\":6}', '127.0.0.1', '2026-04-23 21:00:48', '1'),
(111, 1, 'Evento', 'evento', '5', 'CREAR', NULL, '{\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"id_color\":null,\"estatus\":\"1\",\"id_evento\":5}', '127.0.0.1', '2026-04-23 21:00:48', '1'),
(112, 1, 'Evento', 'evento', '6', 'CREAR', NULL, '{\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"id_color\":null,\"estatus\":\"1\",\"id_evento\":6}', '127.0.0.1', '2026-04-23 21:00:48', '1'),
(113, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-23 21:00:51', '1'),
(114, 1, 'CalendarioAcademico', 'calendario_academico', '6', 'MODIFICAR', '{\"id_calendario_academico\":6,\"semana_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-07-30\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-23 21:44:42', '1'),
(115, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-23 21:44:48', '1'),
(116, 1, 'CalendarioAcademico', 'calendario_academico', '7', 'CREAR', NULL, '{\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"1\",\"id_calendario_academico\":7}', '127.0.0.1', '2026-04-23 21:45:17', '1'),
(117, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-23 21:45:18', '1'),
(118, 43325, 'Estrategia', 'tecnica_actividad', '1', 'CREAR', NULL, '{\"nombre_tecnica_actividad\":\"pppppppppp\",\"estatus\":\"1\",\"id_tecnica_actividad\":1}', '127.0.0.1', '2026-04-23 21:48:55', '1'),
(119, 43325, 'Bibliografia', 'bibliografia', '1', 'CREAR', NULL, '{\"nombre_bibliografia\":\"llll\",\"estatus\":\"1\",\"id_bibliografia\":1}', '127.0.0.1', '2026-04-23 21:51:42', '1'),
(120, 43325, 'TipoEvaluacion', 'tipo_evaluacion', '1', 'CREAR', NULL, '{\"nombre_tipo_evaluacion\":\"dasdsa\",\"estatus\":\"1\",\"id_tipo_evaluacion\":1}', '127.0.0.1', '2026-04-23 21:51:59', '1'),
(121, 43325, 'Recurso', 'recurso', '1', 'CREAR', NULL, '{\"nombre_recurso\":\"23,\",\"estatus\":\"1\",\"id_recurso\":1}', '127.0.0.1', '2026-04-23 21:54:27', '1'),
(122, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-23 21:55:14', '1'),
(123, 1, 'CalendarioAcademico', 'calendario_academico', '7', 'MODIFICAR', '{\"id_calendario_academico\":7,\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-23 21:55:47', '1'),
(124, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-23 21:56:46', '1'),
(125, 1, 'CalendarioAcademico', 'calendario_academico', '8', 'CREAR', NULL, '{\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"1\",\"id_calendario_academico\":8}', '127.0.0.1', '2026-04-23 22:09:28', '1'),
(126, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-23 22:09:29', '1'),
(127, 1, 'CalendarioAcademico', 'calendario_academico', '8', 'MODIFICAR', '{\"id_calendario_academico\":8,\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-23 22:11:53', '1'),
(128, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-23 22:12:00', '1'),
(129, 1, 'CalendarioAcademico', 'calendario_academico', '9', 'CREAR', NULL, '{\"semana_calendario_academico\":22,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-08-31\",\"estatus\":\"1\",\"id_calendario_academico\":9}', '127.0.0.1', '2026-04-23 22:12:44', '1'),
(130, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-23 22:12:46', '1'),
(131, 1, 'CalendarioAcademico', 'calendario_academico', '9', 'MODIFICAR', '{\"id_calendario_academico\":9,\"semana_calendario_academico\":22,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-08-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-25 20:49:33', '1'),
(132, 1, 'CalendarioAcademico', 'calendario_academico', '10', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"estatus\":\"1\",\"id_calendario_academico\":10}', '127.0.0.1', '2026-04-25 20:50:33', '1'),
(133, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-25 20:50:35', '1'),
(134, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-26 22:16:02', '1'),
(135, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-26 22:42:52', '1'),
(136, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-27 19:57:15', '1'),
(137, 1, 'CalendarioAcademico', 'calendario_academico', '10', 'MODIFICAR', '{\"id_calendario_academico\":10,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-27 20:05:12', '1'),
(138, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-27 20:05:18', '1'),
(139, 1, 'CalendarioAcademico', 'calendario_academico', '11', 'CREAR', NULL, '{\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"1\",\"id_calendario_academico\":11}', '127.0.0.1', '2026-04-27 20:06:05', '1'),
(140, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-27 20:06:07', '1'),
(141, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-27 20:07:10', '1'),
(142, 1, 'CalendarioAcademico', 'calendario_academico', '11', 'MODIFICAR', '{\"id_calendario_academico\":11,\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-27 20:07:15', '1'),
(143, 1, 'CalendarioAcademico', 'calendario_academico', '12', 'CREAR', NULL, '{\"semana_calendario_academico\":6,\"dia_inicio_calendario_academico\":\"2026-04-10\",\"dia_fin_calendario_academico\":\"2026-05-21\",\"estatus\":\"1\",\"id_calendario_academico\":12}', '127.0.0.1', '2026-04-27 22:24:04', '1'),
(144, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-27 22:24:42', '1'),
(145, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:24:51', '1'),
(146, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:26:04', '1'),
(147, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:28:46', '1'),
(148, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:28:53', '1'),
(149, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:28:56', '1'),
(150, 43325, 'Evento', 'evento', '6', 'MOSTRAR', '{\"id_evento\":6,\"id_color\":null,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:29:23', '1'),
(151, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:29:27', '1'),
(152, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:32:26', '1'),
(153, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:32:27', '1'),
(154, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:33:04', '1'),
(155, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:33:56', '1'),
(156, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:33:57', '1'),
(157, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:34:05', '1'),
(158, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:34:05', '1'),
(159, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:34:07', '1'),
(160, 43325, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:34:07', '1'),
(161, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:36:24', '1'),
(162, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:36:28', '1'),
(163, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:36:42', '1'),
(164, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-27 22:36:44', '1'),
(165, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 00:31:54', '1'),
(166, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 00:31:57', '1'),
(167, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:04:43', '1'),
(168, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:04:45', '1'),
(169, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:08:42', '1'),
(170, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:08:45', '1'),
(171, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:11:31', '1'),
(172, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:15:32', '1'),
(173, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:15:34', '1'),
(174, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:15:36', '1'),
(175, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:15:37', '1'),
(176, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:16:40', '1'),
(177, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:16:54', '1'),
(178, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:20:34', '1'),
(179, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:21:10', '1'),
(180, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:21:13', '1'),
(181, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:21:14', '1'),
(182, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:21:16', '1'),
(183, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:21:17', '1'),
(184, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:21:23', '1'),
(185, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:23:07', '1'),
(186, 43325, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-28 01:23:14', '1'),
(187, 1, 'CalendarioAcademico', 'calendario_academico', '12', 'MODIFICAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":6,\"dia_inicio_calendario_academico\":\"2026-04-10\",\"dia_fin_calendario_academico\":\"2026-05-21\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-28 03:08:29', '1'),
(188, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-28 03:08:37', '1'),
(189, 1, 'CalendarioAcademico', 'calendario_academico', '13', 'CREAR', NULL, '{\"semana_calendario_academico\":257,\"dia_inicio_calendario_academico\":\"2025-01-01\",\"dia_fin_calendario_academico\":\"2029-12-01\",\"estatus\":\"1\",\"id_calendario_academico\":13}', '127.0.0.1', '2026-04-29 01:06:06', '1'),
(190, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-29 01:06:08', '1'),
(191, 1, 'CalendarioAcademico', 'calendario_academico', '13', 'MODIFICAR', '{\"id_calendario_academico\":13,\"semana_calendario_academico\":257,\"dia_inicio_calendario_academico\":\"2025-01-01\",\"dia_fin_calendario_academico\":\"2029-12-01\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-29 01:39:41', '1'),
(192, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-29 01:39:49', '1'),
(193, 1, 'CalendarioAcademico', 'calendario_academico', '14', 'CREAR', NULL, '{\"semana_calendario_academico\":58,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"1\",\"id_calendario_academico\":14}', '127.0.0.1', '2026-04-29 02:46:40', '1'),
(194, 1, 'CalendarioAcademico', 'calendario_academico', '14', 'MODIFICAR', '{\"id_calendario_academico\":14,\"semana_calendario_academico\":58,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-29 02:46:53', '1'),
(195, 1, 'CalendarioAcademico', 'calendario_academico', '15', 'CREAR', NULL, '{\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-05-02\",\"dia_fin_calendario_academico\":\"2026-06-30\",\"estatus\":\"1\",\"id_calendario_academico\":15}', '127.0.0.1', '2026-05-02 20:16:33', '1'),
(196, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-02 20:16:38', '1'),
(197, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-03 00:28:00', '1'),
(198, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-03 00:29:45', '1'),
(199, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-03 00:29:56', '1'),
(200, 31009367, 'Recurso', 'recurso', '1', 'MOSTRAR', '{\"id_recurso\":1,\"nombre_recurso\":\"23,\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-03 00:30:18', '1'),
(201, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-03 00:40:38', '1'),
(202, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-03 00:41:17', '1'),
(203, 1, 'CalendarioAcademico', 'calendario_academico', '15', 'MODIFICAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-05-02\",\"dia_fin_calendario_academico\":\"2026-06-30\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 06:58:57', '1'),
(204, 1, 'CalendarioAcademico', 'calendario_academico', '16', 'CREAR', NULL, '{\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-06-30\",\"estatus\":\"1\",\"id_calendario_academico\":16}', '127.0.0.1', '2026-05-06 07:03:31', '1'),
(205, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 07:03:33', '1'),
(206, 31009367, 'Roles (Permisos)', 'rol_permiso', '71', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"67\",\"estatus\":\"1\",\"id_rol_permiso\":71}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(207, 31009367, 'Roles (Permisos)', 'rol_permiso', '72', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"45\",\"estatus\":\"1\",\"id_rol_permiso\":72}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(208, 31009367, 'Roles (Permisos)', 'rol_permiso', '73', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"70\",\"estatus\":\"1\",\"id_rol_permiso\":73}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(209, 31009367, 'Roles (Permisos)', 'rol_permiso', '74', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"44\",\"estatus\":\"1\",\"id_rol_permiso\":74}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(210, 31009367, 'Roles (Permisos)', 'rol_permiso', '75', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"46\",\"estatus\":\"1\",\"id_rol_permiso\":75}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(211, 31009367, 'Roles (Permisos)', 'rol_permiso', '76', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"69\",\"estatus\":\"1\",\"id_rol_permiso\":76}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(212, 31009367, 'Roles (Permisos)', 'rol_permiso', '77', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"52\",\"estatus\":\"1\",\"id_rol_permiso\":77}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(213, 31009367, 'Roles (Permisos)', 'rol_permiso', '78', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"53\",\"estatus\":\"1\",\"id_rol_permiso\":78}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(214, 31009367, 'Roles (Permisos)', 'rol_permiso', '79', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"60\",\"estatus\":\"1\",\"id_rol_permiso\":79}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(215, 31009367, 'Roles (Permisos)', 'rol_permiso', '80', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"48\",\"estatus\":\"1\",\"id_rol_permiso\":80}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(216, 31009367, 'Roles (Permisos)', 'rol_permiso', '81', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"47\",\"estatus\":\"1\",\"id_rol_permiso\":81}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(217, 31009367, 'Roles (Permisos)', 'rol_permiso', '82', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"19\",\"estatus\":\"1\",\"id_rol_permiso\":82}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(218, 31009367, 'Roles (Permisos)', 'rol_permiso', '83', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"49\",\"estatus\":\"1\",\"id_rol_permiso\":83}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(219, 31009367, 'Roles (Permisos)', 'rol_permiso', '84', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"68\",\"estatus\":\"1\",\"id_rol_permiso\":84}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(220, 31009367, 'Roles (Permisos)', 'rol_permiso', '85', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"51\",\"estatus\":\"1\",\"id_rol_permiso\":85}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(221, 31009367, 'Roles (Permisos)', 'rol_permiso', '86', 'CREAR', NULL, '{\"id_rol\":\"31\",\"id_permiso\":\"50\",\"estatus\":\"1\",\"id_rol_permiso\":86}', '127.0.0.1', '2026-05-06 07:37:09', '1'),
(222, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:43:54', '1'),
(223, 31009367, 'Evento', 'evento', '17', 'MODIFICAR', '{\"id_evento\":17,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":2}', '127.0.0.1', '2026-05-06 07:44:02', '1'),
(224, 31009367, 'Evento', 'evento', '2', 'MODIFICAR', '{\"id_evento\":2,\"id_color\":null,\"nombre_evento\":\"dsd\",\"tipo_evento\":null,\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 07:44:10', '1'),
(225, 31009367, 'Evento', 'evento', '1', 'MODIFICAR', '{\"id_evento\":1,\"id_color\":null,\"nombre_evento\":\"Actividad Administrativa\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 07:44:12', '1'),
(226, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:44:14', '1'),
(227, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:44:23', '1'),
(228, 31009367, 'Evento', 'evento', '16', 'MODIFICAR', '{\"id_evento\":16,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":1}', '127.0.0.1', '2026-05-06 07:44:28', '1'),
(229, 31009367, 'Evento', 'evento', '15', 'MOSTRAR', '{\"id_evento\":15,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN N\\u00daCLEO ACAD\\u00c9MICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:44:31', '1'),
(230, 31009367, 'Evento', 'evento', '15', 'MODIFICAR', '{\"id_evento\":15,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN N\\u00daCLEO ACAD\\u00c9MICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":3}', '127.0.0.1', '2026-05-06 07:44:36', '1'),
(231, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:44:39', '1'),
(232, 31009367, 'Evento', 'evento', '14', 'MODIFICAR', '{\"id_evento\":14,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":4}', '127.0.0.1', '2026-05-06 07:44:46', '1'),
(233, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":null,\"nombre_evento\":\"DECLARACION DE LA INDEPENDENCIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:44:50', '1'),
(234, 31009367, 'Evento', 'evento', '13', 'MODIFICAR', '{\"id_evento\":13,\"id_color\":null,\"nombre_evento\":\"DECLARACION DE LA INDEPENDENCIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":5}', '127.0.0.1', '2026-05-06 07:44:55', '1'),
(235, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":null,\"nombre_evento\":\"JUEVES y VIERNES SANTO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:45:56', '1'),
(236, 31009367, 'Evento', 'evento', '12', 'MODIFICAR', '{\"id_evento\":12,\"id_color\":null,\"nombre_evento\":\"JUEVES y VIERNES SANTO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":6}', '127.0.0.1', '2026-05-06 07:46:02', '1'),
(237, 31009367, 'Evento', 'evento', '11', 'MOSTRAR', '{\"id_evento\":11,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR UNIVERSITARIO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:46:08', '1'),
(238, 31009367, 'Evento', 'evento', '11', 'MODIFICAR', '{\"id_evento\":11,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR UNIVERSITARIO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":7}', '127.0.0.1', '2026-05-06 07:46:14', '1'),
(239, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:46:19', '1'),
(240, 31009367, 'Evento', 'evento', '10', 'MODIFICAR', '{\"id_evento\":10,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":8}', '127.0.0.1', '2026-05-06 07:46:24', '1'),
(241, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":null,\"nombre_evento\":\"CARNAVAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:46:29', '1'),
(242, 31009367, 'Evento', 'evento', '9', 'MODIFICAR', '{\"id_evento\":9,\"id_color\":null,\"nombre_evento\":\"CARNAVAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":9}', '127.0.0.1', '2026-05-06 07:46:35', '1'),
(243, 31009367, 'Evento', 'evento', '8', 'MOSTRAR', '{\"id_evento\":8,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRACIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:46:40', '1'),
(244, 31009367, 'Evento', 'evento', '8', 'MODIFICAR', '{\"id_evento\":8,\"id_color\":null,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRACIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":10}', '127.0.0.1', '2026-05-06 07:46:46', '1'),
(245, 31009367, 'Evento', 'evento', '3', 'MODIFICAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 07:47:07', '1'),
(246, 31009367, 'Evento', 'evento', '4', 'MODIFICAR', '{\"id_evento\":4,\"id_color\":5,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 07:47:10', '1'),
(247, 31009367, 'Evento', 'evento', '5', 'MODIFICAR', '{\"id_evento\":5,\"id_color\":null,\"nombre_evento\":\"dsdp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 07:47:13', '1'),
(248, 31009367, 'Evento', 'evento', '6', 'MODIFICAR', '{\"id_evento\":6,\"id_color\":null,\"nombre_evento\":\"dsdpp\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 07:47:14', '1'),
(249, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":null,\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:47:22', '1'),
(250, 31009367, 'Evento', 'evento', '7', 'MODIFICAR', '{\"id_evento\":7,\"id_color\":null,\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"id_color\":11}', '127.0.0.1', '2026-05-06 07:47:27', '1');
INSERT INTO `bitacora` (`id_bitacora`, `id_usuario`, `modulo_afectado_bitacora`, `tabla_afectada_bitacora`, `id_registro_afectado_bitacora`, `accion_bitacora`, `valores_anteriores_bitacora`, `valores_nuevos_bitacora`, `ip_origen_bitacora`, `fecha_creacion`, `estatus`) VALUES
(251, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:47:34', '1'),
(252, 31009367, 'Evento', 'evento', '17', 'MODIFICAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:47:40', '1'),
(253, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:47:46', '1'),
(254, 31009367, 'Evento', 'evento', '16', 'MODIFICAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:47:51', '1'),
(255, 31009367, 'Evento', 'evento', '15', 'MOSTRAR', '{\"id_evento\":15,\"id_color\":3,\"nombre_evento\":\"D\\u00cdA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN N\\u00daCLEO ACAD\\u00c9MICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:47:57', '1'),
(256, 31009367, 'Evento', 'evento', '15', 'MODIFICAR', '{\"id_evento\":15,\"id_color\":3,\"nombre_evento\":\"D\\u00cdA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN N\\u00daCLEO ACAD\\u00c9MICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:48:01', '1'),
(257, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":4,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:48:05', '1'),
(258, 31009367, 'Evento', 'evento', '14', 'MODIFICAR', '{\"id_evento\":14,\"id_color\":4,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:48:09', '1'),
(259, 31009367, 'Evento', 'evento', '15', 'MOSTRAR', '{\"id_evento\":15,\"id_color\":3,\"nombre_evento\":\"D\\u00cdA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN N\\u00daCLEO ACAD\\u00c9MICO TUREN\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:48:12', '1'),
(260, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":4,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:48:19', '1'),
(261, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":5,\"nombre_evento\":\"DECLARACION DE LA INDEPENDENCIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:48:25', '1'),
(262, 31009367, 'Evento', 'evento', '13', 'MODIFICAR', '{\"id_evento\":13,\"id_color\":5,\"nombre_evento\":\"DECLARACION DE LA INDEPENDENCIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:48:30', '1'),
(263, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":6,\"nombre_evento\":\"JUEVES y VIERNES SANTO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:48:35', '1'),
(264, 31009367, 'Evento', 'evento', '12', 'MODIFICAR', '{\"id_evento\":12,\"id_color\":6,\"nombre_evento\":\"JUEVES y VIERNES SANTO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:48:39', '1'),
(265, 31009367, 'Evento', 'evento', '11', 'MOSTRAR', '{\"id_evento\":11,\"id_color\":7,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR UNIVERSITARIO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:48:43', '1'),
(266, 31009367, 'Evento', 'evento', '11', 'MODIFICAR', '{\"id_evento\":11,\"id_color\":7,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR UNIVERSITARIO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:48:47', '1'),
(267, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":8,\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:48:51', '1'),
(268, 31009367, 'Evento', 'evento', '10', 'MODIFICAR', '{\"id_evento\":10,\"id_color\":8,\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:48:56', '1'),
(269, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":9,\"nombre_evento\":\"CARNAVAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:49:04', '1'),
(270, 31009367, 'Evento', 'evento', '9', 'MODIFICAR', '{\"id_evento\":9,\"id_color\":9,\"nombre_evento\":\"CARNAVAL\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:49:08', '1'),
(271, 31009367, 'Evento', 'evento', '8', 'MOSTRAR', '{\"id_evento\":8,\"id_color\":10,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRACIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:49:13', '1'),
(272, 31009367, 'Evento', 'evento', '8', 'MODIFICAR', '{\"id_evento\":8,\"id_color\":10,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRACIA\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:49:17', '1'),
(273, 31009367, 'Evento', 'evento', '8', 'MOSTRAR', '{\"id_evento\":8,\"id_color\":10,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRACIA\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:49:21', '1'),
(274, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":9,\"nombre_evento\":\"CARNAVAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:49:25', '1'),
(275, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":8,\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:49:30', '1'),
(276, 31009367, 'Evento', 'evento', '11', 'MOSTRAR', '{\"id_evento\":11,\"id_color\":7,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR UNIVERSITARIO\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:49:35', '1'),
(277, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":6,\"nombre_evento\":\"JUEVES y VIERNES SANTO\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:49:40', '1'),
(278, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":11,\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 07:50:06', '1'),
(279, 31009367, 'Evento', 'evento', '7', 'MODIFICAR', '{\"id_evento\":7,\"id_color\":11,\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\"}', '127.0.0.1', '2026-05-06 07:50:10', '1'),
(280, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 07:50:24', '1'),
(281, 1, 'CalendarioAcademico', 'calendario_academico', '16', 'MODIFICAR', '{\"id_calendario_academico\":16,\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-06-30\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 07:51:18', '1'),
(282, 1, 'CalendarioAcademico', 'calendario_academico', '17', 'CREAR', NULL, '{\"semana_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-08-31\",\"estatus\":\"1\",\"id_calendario_academico\":17}', '127.0.0.1', '2026-05-06 07:56:21', '1'),
(283, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 07:56:23', '1'),
(284, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-06 07:56:35', '1'),
(285, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 08:00:48', '1'),
(286, 1, 'CalendarioAcademico', 'calendario_academico', '17', 'MODIFICAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-08-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 08:00:52', '1'),
(287, 1, 'CalendarioAcademico', 'calendario_academico', '18', 'CREAR', NULL, '{\"semana_calendario_academico\":14,\"dia_inicio_calendario_academico\":\"2026-04-30\",\"dia_fin_calendario_academico\":\"2026-07-31\",\"estatus\":\"1\",\"id_calendario_academico\":18}', '127.0.0.1', '2026-05-06 08:05:47', '1'),
(288, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 08:05:50', '1'),
(289, 31009367, 'Evento', 'evento', '16', 'MODIFICAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 08:06:25', '1'),
(290, 31009367, 'Evento', 'evento', '10', 'MODIFICAR', '{\"id_evento\":10,\"id_color\":8,\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 08:06:39', '1'),
(291, 31009367, 'Evento', 'evento', '15', 'MODIFICAR', '{\"id_evento\":15,\"id_color\":3,\"nombre_evento\":\"D\\u00cdA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN N\\u00daCLEO ACAD\\u00c9MICO TUREN\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 08:06:49', '1'),
(292, 31009367, 'Evento', 'evento', '17', 'MODIFICAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 08:06:58', '1'),
(293, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 08:07:09', '1'),
(294, 1, 'CalendarioAcademico', 'calendario_academico', '18', 'MODIFICAR', '{\"id_calendario_academico\":18,\"semana_calendario_academico\":14,\"dia_inicio_calendario_academico\":\"2026-04-30\",\"dia_fin_calendario_academico\":\"2026-07-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 08:07:13', '1'),
(295, 1, 'CalendarioAcademico', 'calendario_academico', '19', 'CREAR', NULL, '{\"semana_calendario_academico\":14,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-07-31\",\"estatus\":\"1\",\"id_calendario_academico\":19}', '127.0.0.1', '2026-05-06 08:08:26', '1'),
(296, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 08:08:31', '1'),
(297, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-06 08:09:02', '1'),
(298, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-06 08:09:42', '1'),
(299, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-06 08:09:48', '1'),
(300, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 08:10:14', '1'),
(301, 1, 'CalendarioAcademico', 'calendario_academico', '19', 'MODIFICAR', '{\"id_calendario_academico\":19,\"semana_calendario_academico\":14,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-07-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 08:10:57', '1'),
(302, 1, 'CalendarioAcademico', 'calendario_academico', '20', 'CREAR', NULL, '{\"semana_calendario_academico\":29,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-07-17\",\"estatus\":\"1\",\"id_calendario_academico\":20}', '127.0.0.1', '2026-05-06 08:16:16', '1'),
(303, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 08:16:21', '1'),
(304, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-06 08:16:36', '1'),
(305, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-06 08:19:13', '1'),
(306, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-06 08:20:00', '1'),
(307, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":4,\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-06 08:20:08', '1'),
(308, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-06 08:20:24', '1'),
(309, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 08:20:50', '1'),
(310, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 14:34:08', '1'),
(311, 1, 'CalendarioAcademico', 'calendario_academico', '20', 'MODIFICAR', '{\"id_calendario_academico\":20,\"semana_calendario_academico\":29,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-07-17\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 14:35:29', '1'),
(312, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 14:35:32', '1'),
(313, 1, 'CalendarioAcademico', 'calendario_academico', '21', 'CREAR', NULL, '{\"semana_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-08-31\",\"estatus\":\"1\",\"id_calendario_academico\":21}', '127.0.0.1', '2026-05-06 15:54:37', '1'),
(314, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 15:54:46', '1'),
(315, 1, 'CalendarioAcademico', 'calendario_academico', '21', 'MODIFICAR', '{\"id_calendario_academico\":21,\"semana_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-08-31\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-06 16:01:28', '1'),
(316, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 16:01:31', '1'),
(317, 1, 'CalendarioAcademico', 'calendario_academico', '22', 'CREAR', NULL, '{\"semana_calendario_academico\":59,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2027-02-12\",\"estatus\":\"1\",\"id_calendario_academico\":22}', '127.0.0.1', '2026-05-06 19:30:57', '1'),
(318, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 19:31:04', '1'),
(319, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-06 19:39:37', '1'),
(320, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-06 19:42:15', '1'),
(321, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-07 05:30:27', '1'),
(322, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-07 18:24:18', '1'),
(323, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-07 18:27:28', '1'),
(324, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-07 18:40:33', '1'),
(325, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-07 22:58:40', '1'),
(326, 31009367, 'CalendarioAcademico', 'calendario_academico', '23', 'CREAR', NULL, '{\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-28\",\"estatus\":\"1\",\"id_calendario_academico\":23}', '127.0.0.1', '2026-05-07 23:21:50', '1'),
(327, 31009367, 'CalendarioAcademico', 'calendario_academico', '24', 'CREAR', NULL, '{\"semana_calendario_academico\":75,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-10-07\",\"estatus\":\"1\",\"id_calendario_academico\":24}', '127.0.0.1', '2026-05-07 23:37:27', '1'),
(328, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-07 23:37:33', '1'),
(329, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-08 04:07:52', '1'),
(330, 31009367, 'CalendarioAcademico', 'calendario_academico', '25', 'CREAR', NULL, '{\"semana_calendario_academico\":22,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"1\",\"id_calendario_academico\":25}', '127.0.0.1', '2026-05-08 04:45:57', '1'),
(331, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-08 04:46:23', '1'),
(332, 31009367, 'CalendarioAcademico', 'calendario_academico', '26', 'CREAR', NULL, '{\"semana_calendario_academico\":59,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2027-02-11\",\"estatus\":\"1\",\"id_calendario_academico\":26}', '127.0.0.1', '2026-05-08 04:48:36', '1'),
(333, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-08 04:49:13', '1'),
(334, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 02:59:02', '1'),
(335, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-10 02:59:13', '1'),
(336, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 03:10:12', '1'),
(337, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-10 03:11:12', '1'),
(338, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 03:17:04', '1'),
(339, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-10 03:20:57', '1'),
(340, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 03:21:10', '1'),
(341, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-10 03:24:15', '1'),
(342, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 03:25:02', '1'),
(343, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 15:16:17', '1'),
(344, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:32:23', '1'),
(345, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:33:25', '1'),
(346, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:38:54', '1'),
(347, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:39:03', '1'),
(348, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:39:04', '1'),
(349, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:41:38', '1'),
(350, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:41:47', '1'),
(351, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:41:49', '1'),
(352, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:43:42', '1'),
(353, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:46:08', '1'),
(354, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:46:16', '1'),
(355, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:48:22', '1'),
(356, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:52:31', '1'),
(357, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:53:37', '1'),
(358, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:55:39', '1'),
(359, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:57:46', '1'),
(360, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 15:57:50', '1'),
(361, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:00:04', '1'),
(362, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:02:20', '1'),
(363, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:02:22', '1'),
(364, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:06:05', '1'),
(365, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:06:16', '1'),
(366, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:06:19', '1'),
(367, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:08:23', '1'),
(368, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:14:34', '1'),
(369, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:15:57', '1'),
(370, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:18:15', '1'),
(371, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:21:32', '1'),
(372, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:22:37', '1'),
(373, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:23:42', '1'),
(374, 31009367, 'CalendarioAcademico', 'calendario_academico', '27', 'CREAR', NULL, '{\"semana_calendario_academico\":41,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"1\",\"id_calendario_academico\":27}', '127.0.0.1', '2026-05-10 16:25:15', '1'),
(375, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:25:20', '1'),
(376, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:27:55', '1'),
(377, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:28:26', '1'),
(378, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:28:55', '1'),
(379, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:30:19', '1'),
(380, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:30:48', '1'),
(381, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:32:24', '1'),
(382, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:32:39', '1'),
(383, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:34:47', '1'),
(384, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:35:18', '1'),
(385, 31009367, 'CalendarioAcademico', 'calendario_academico', '28', 'CREAR', NULL, '{\"semana_calendario_academico\":41,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"1\",\"id_calendario_academico\":28}', '127.0.0.1', '2026-05-10 16:36:45', '1'),
(386, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:36:58', '1'),
(387, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:37:24', '1'),
(388, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:44:35', '1'),
(389, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:45:58', '1'),
(390, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:47:21', '1'),
(391, 31009367, 'Evento', 'evento', '17', 'MOSTRAR', '{\"id_evento\":17,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-05-10 16:47:22', '1'),
(392, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:47:31', '1'),
(393, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:58:40', '1'),
(394, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 16:58:55', '1'),
(395, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 20:15:33', '1'),
(396, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 20:16:33', '1'),
(397, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 20:16:41', '1'),
(398, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 20:19:19', '1'),
(399, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 20:19:49', '1'),
(400, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 20:20:27', '1'),
(401, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-10 20:20:38', '1'),
(402, 31009367, 'Evento', 'evento', '16', 'MODIFICAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"3\"}', '{\"estatus\":\"1\"}', '127.0.0.1', '2026-05-10 21:17:07', '1'),
(403, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:17:53', '1'),
(404, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:22:41', '1'),
(405, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:22:46', '1'),
(406, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:23:02', '1'),
(407, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:23:07', '1'),
(408, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:24:04', '1'),
(409, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:25:45', '1'),
(410, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:25:50', '1'),
(411, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:26:00', '1'),
(412, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:26:46', '1'),
(413, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:28:31', '1'),
(414, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:28:39', '1'),
(415, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:28:55', '1'),
(416, 31009367, 'Evento', 'evento', '16', 'MOSTRAR', '{\"id_evento\":16,\"id_color\":1,\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-10 21:29:11', '1'),
(417, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-10 23:22:21', '1'),
(418, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 23:23:22', '1'),
(419, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-10 23:23:26', '1'),
(420, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-10 23:26:36', '1'),
(421, 31009367, 'Evento', 'evento', '1', 'CREAR', NULL, '{\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"1\",\"id_color\":1,\"estatus\":\"1\",\"id_evento\":1}', '127.0.0.1', '2026-05-10 23:52:12', '1'),
(422, 31009367, 'Evento', 'evento', '2', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRAC\\u00cdA\",\"tipo_evento\":\"1\",\"id_color\":2,\"estatus\":\"1\",\"id_evento\":2}', '127.0.0.1', '2026-05-10 23:53:33', '1'),
(423, 31009367, 'Evento', 'evento', '3', 'CREAR', NULL, '{\"nombre_evento\":\"CARNAVAL\",\"tipo_evento\":\"1\",\"id_color\":3,\"estatus\":\"1\",\"id_evento\":3}', '127.0.0.1', '2026-05-10 23:53:45', '1'),
(424, 31009367, 'Evento', 'evento', '4', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN \",\"tipo_evento\":\"1\",\"id_color\":4,\"estatus\":\"1\",\"id_evento\":4}', '127.0.0.1', '2026-05-10 23:55:08', '1'),
(425, 31009367, 'Evento', 'evento', '5', 'CREAR', NULL, '{\"nombre_evento\":\"JUEVES y VIERNES SANTO\",\"tipo_evento\":\"1\",\"id_color\":5,\"estatus\":\"1\",\"id_evento\":5}', '127.0.0.1', '2026-05-10 23:59:42', '1'),
(426, 31009367, 'Evento', 'evento', '6', 'CREAR', NULL, '{\"nombre_evento\":\"DECLARACION DE LA INDEPENDENCIA\",\"tipo_evento\":\"1\",\"id_color\":6,\"estatus\":\"1\",\"id_evento\":6}', '127.0.0.1', '2026-05-11 00:02:43', '1'),
(427, 31009367, 'Evento', 'evento', '7', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR\",\"tipo_evento\":\"1\",\"id_color\":7,\"estatus\":\"1\",\"id_evento\":7}', '127.0.0.1', '2026-05-11 00:02:59', '1'),
(428, 31009367, 'Evento', 'evento', '8', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN N\\u00daCLEO ACAD\\u00c9MICO TUREN\",\"tipo_evento\":\"1\",\"id_color\":8,\"estatus\":\"1\",\"id_evento\":8}', '127.0.0.1', '2026-05-11 00:03:09', '1'),
(429, 31009367, 'Evento', 'evento', '9', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL\",\"tipo_evento\":\"1\",\"id_color\":9,\"estatus\":\"1\",\"id_evento\":9}', '127.0.0.1', '2026-05-11 00:03:18', '1'),
(430, 31009367, 'Evento', 'evento', '10', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"id_color\":10,\"estatus\":\"1\",\"id_evento\":10}', '127.0.0.1', '2026-05-11 00:03:27', '1'),
(431, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":10,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 00:26:35', '1'),
(432, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":10,\"nombre_evento\":\"D\\u00cdA DE P\\u00c1EZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL\",\"tipo_evento\":\"1\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 00:26:42', '1'),
(433, 31009367, 'CalendarioAcademico', 'calendario_academico', '1', 'CREAR', NULL, '{\"semana_calendario_academico\":51,\"dia_inicio_calendario_academico\":\"2026-01-31\",\"dia_fin_calendario_academico\":\"2027-01-22\",\"estatus\":\"1\",\"id_calendario_academico\":1}', '127.0.0.1', '2026-05-11 00:28:39', '1'),
(434, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 00:28:44', '1'),
(435, 31009367, 'Evento', 'evento', '11', 'CREAR', NULL, '{\"nombre_evento\":\"Correcci\\u00f3n de notas\",\"tipo_evento\":\"2\",\"id_color\":11,\"estatus\":\"1\",\"id_evento\":11}', '127.0.0.1', '2026-05-11 00:37:55', '1'),
(436, 31009367, 'Evento', 'evento', '11', 'MOSTRAR', '{\"id_evento\":11,\"id_color\":11,\"nombre_evento\":\"Correcci\\u00f3n de notas\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 00:38:25', '1'),
(437, 31009367, 'Evento', 'evento', '11', 'MODIFICAR', '{\"id_evento\":11,\"id_color\":11,\"nombre_evento\":\"Correcci\\u00f3n de notas\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-05-11 00:38:32', '1'),
(438, 31009367, 'Evento', 'evento', '11', 'MODIFICAR', '{\"id_evento\":11,\"id_color\":11,\"nombre_evento\":\"Correcci\\u00f3n de notas\",\"tipo_evento\":\"2\",\"estatus\":\"3\"}', '{\"estatus\":\"1\"}', '127.0.0.1', '2026-05-11 00:38:35', '1'),
(439, 31009367, 'Evento', 'evento', '11', 'MOSTRAR', '{\"id_evento\":11,\"id_color\":11,\"nombre_evento\":\"Correcci\\u00f3n de notas\",\"tipo_evento\":\"2\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 00:38:37', '1'),
(440, 31009367, 'Evento', 'evento', '12', 'CREAR', NULL, '{\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"1\",\"id_color\":12,\"is_laborable\":false,\"is_repetible\":false,\"estatus\":\"1\",\"id_evento\":12}', '127.0.0.1', '2026-05-11 03:11:05', '1'),
(441, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:11:40', '1'),
(442, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:11:47', '1'),
(443, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:11:54', '1'),
(444, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:11:59', '1'),
(445, 31009367, 'Evento', 'evento', '12', 'MODIFICAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"1\",\"is_laborable\":false,\"is_repetible\":false,\"estatus\":\"1\"}', '{\"tipo_evento\":\"2\",\"is_laborable\":true}', '127.0.0.1', '2026-05-11 03:12:09', '1'),
(446, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:12:13', '1'),
(447, 31009367, 'Evento', 'evento', '12', 'MODIFICAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":true,\"is_repetible\":false,\"estatus\":\"1\"}', '{\"is_laborable\":false,\"is_repetible\":true}', '127.0.0.1', '2026-05-11 03:12:17', '1'),
(448, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:12:22', '1'),
(449, 31009367, 'Evento', 'evento', '12', 'MODIFICAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":false,\"is_repetible\":true,\"estatus\":\"1\"}', '{\"is_repetible\":false}', '127.0.0.1', '2026-05-11 03:12:25', '1'),
(450, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:12:32', '1'),
(451, 31009367, 'Evento', 'evento', '12', 'MODIFICAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":false,\"is_repetible\":false,\"estatus\":\"1\"}', '{\"is_repetible\":true}', '127.0.0.1', '2026-05-11 03:12:36', '1'),
(452, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:12:43', '1'),
(453, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:12:49', '1'),
(454, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:14:18', '1'),
(455, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:14:24', '1'),
(456, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:14:31', '1'),
(457, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:17:01', '1'),
(458, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:17:07', '1'),
(459, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:18:03', '1'),
(460, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:18:07', '1'),
(461, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:18:54', '1'),
(462, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:18:56', '1'),
(463, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:19:13', '1'),
(464, 31009367, 'Evento', 'evento', '12', 'MODIFICAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"2\",\"is_laborable\":false,\"is_repetible\":true,\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\",\"is_repetible\":false}', '127.0.0.1', '2026-05-11 03:19:17', '1'),
(465, 31009367, 'Evento', 'evento', '12', 'MOSTRAR', '{\"id_evento\":12,\"id_color\":12,\"nombre_evento\":\"Batalla de Carabobo\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:19:20', '1'),
(466, 31009367, 'Evento', 'evento', '13', 'CREAR', NULL, '{\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"id_color\":14,\"is_laborable\":true,\"is_repetible\":true,\"estatus\":\"1\",\"id_evento\":13}', '127.0.0.1', '2026-05-11 03:32:51', '1'),
(467, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:33:12', '1'),
(468, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:33:21', '1'),
(469, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:33:26', '1'),
(470, 31009367, 'Evento', 'evento', '13', 'MODIFICAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":true,\"is_repetible\":true,\"estatus\":\"1\"}', '{\"is_repetible\":false}', '127.0.0.1', '2026-05-11 03:33:30', '1');
INSERT INTO `bitacora` (`id_bitacora`, `id_usuario`, `modulo_afectado_bitacora`, `tabla_afectada_bitacora`, `id_registro_afectado_bitacora`, `accion_bitacora`, `valores_anteriores_bitacora`, `valores_nuevos_bitacora`, `ip_origen_bitacora`, `fecha_creacion`, `estatus`) VALUES
(471, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:33:51', '1'),
(472, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 03:34:05', '1'),
(473, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:15:39', '1'),
(474, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:15:46', '1'),
(475, 31009367, 'Evento', 'evento', '13', 'MODIFICAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":true,\"is_repetible\":false,\"estatus\":\"1\"}', '{\"tipo_evento\":\"1\",\"is_laborable\":false}', '127.0.0.1', '2026-05-11 04:15:52', '1'),
(476, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:15:56', '1'),
(477, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:51:38', '1'),
(478, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"1\",\"is_laborable\":0,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:51:45', '1'),
(479, 31009367, 'Evento', 'evento', '13', 'MODIFICAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"1\",\"is_laborable\":false,\"is_repetible\":false,\"estatus\":\"1\"}', '{\"tipo_evento\":\"2\",\"is_repetible\":true}', '127.0.0.1', '2026-05-11 04:51:53', '1'),
(480, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:52:58', '1'),
(481, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:57:16', '1'),
(482, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:57:24', '1'),
(483, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable\":0,\"is_repetible\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:57:28', '1'),
(484, 31009367, 'Evento', 'evento', '2', 'MOSTRAR', '{\"id_evento\":2,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRAC\\u00cdA\",\"tipo_evento\":\"1\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:59:28', '1'),
(485, 31009367, 'Evento', 'evento', '2', 'MODIFICAR', '{\"id_evento\":2,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRAC\\u00cdA\",\"tipo_evento\":\"1\",\"is_laborable\":true,\"is_repetible\":false,\"estatus\":\"1\"}', '{\"tipo_evento\":\"3\"}', '127.0.0.1', '2026-05-11 04:59:32', '1'),
(486, 31009367, 'Evento', 'evento', '2', 'MOSTRAR', '{\"id_evento\":2,\"id_color\":2,\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRAC\\u00cdA\",\"tipo_evento\":\"3\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:59:38', '1'),
(487, 31009367, 'Evento', 'evento', '1', 'MOSTRAR', '{\"id_evento\":1,\"id_color\":1,\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"1\",\"is_laborable\":1,\"is_repetible\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-11 04:59:39', '1'),
(488, 31009367, 'Evento', 'evento', '1', 'MODIFICAR', '{\"id_evento\":1,\"id_color\":1,\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"1\",\"is_laborable\":true,\"is_repetible\":false,\"estatus\":\"1\"}', '{\"tipo_evento\":\"3\"}', '127.0.0.1', '2026-05-11 04:59:46', '1'),
(489, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 04:59:52', '1'),
(490, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:00:32', '1'),
(491, 31009367, 'CalendarioAcademico', 'calendario_academico', '2', 'CREAR', NULL, '{\"semana_calendario_academico\":54,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"1\",\"id_calendario_academico\":2}', '127.0.0.1', '2026-05-11 05:01:52', '1'),
(492, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:01:56', '1'),
(493, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:04:11', '1'),
(494, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:04:23', '1'),
(495, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:07:06', '1'),
(496, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:08:23', '1'),
(497, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:08:36', '1'),
(498, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:10:39', '1'),
(499, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:10:56', '1'),
(500, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:13:24', '1'),
(501, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:13:53', '1'),
(502, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:14:09', '1'),
(503, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:16:14', '1'),
(504, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:16:29', '1'),
(505, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:18:16', '1'),
(506, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:18:26', '1'),
(507, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:20:08', '1'),
(508, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:20:20', '1'),
(509, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:28:03', '1'),
(510, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:33:48', '1'),
(511, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:36:00', '1'),
(512, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:36:39', '1'),
(513, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:37:08', '1'),
(514, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:37:40', '1'),
(515, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:38:21', '1'),
(516, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:38:38', '1'),
(517, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:40:12', '1'),
(518, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:42:07', '1'),
(519, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:44:23', '1'),
(520, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:46:30', '1'),
(521, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:48:11', '1'),
(522, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:50:11', '1'),
(523, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:53:27', '1'),
(524, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:56:04', '1'),
(525, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-11 05:57:11', '1'),
(526, 31009367, 'CalendarioAcademico', 'calendario_academico', '3', 'CREAR', NULL, '{\"semana_calendario_academico\":41,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\",\"id_calendario_academico\":3}', '127.0.0.1', '2026-05-11 06:27:28', '1'),
(527, 31009367, 'CalendarioAcademico', 'calendario_academico', '3', 'MOSTRAR', '{\"id_calendario_academico\":3,\"semana_calendario_academico\":41,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 06:27:35', '1'),
(528, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-11 15:34:57', '1'),
(529, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'CREAR', NULL, '{\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\",\"id_calendario_academico\":4}', '127.0.0.1', '2026-05-11 15:35:44', '1'),
(530, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:39:11', '1'),
(531, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:42:49', '1'),
(532, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:43:00', '1'),
(533, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:43:48', '1'),
(534, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:43:50', '1'),
(535, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:44:08', '1'),
(536, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:44:09', '1'),
(537, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:44:30', '1'),
(538, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:44:39', '1'),
(539, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:45:15', '1'),
(540, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:46:57', '1'),
(541, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:46:59', '1'),
(542, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:47:11', '1'),
(543, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:47:13', '1'),
(544, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:49:07', '1'),
(545, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-05\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:49:09', '1'),
(546, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2026-11-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:49:40', '1'),
(547, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2026-11-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:50:09', '1'),
(548, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2026-11-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:51:01', '1'),
(549, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2026-11-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:51:07', '1'),
(550, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2026-11-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:51:08', '1'),
(551, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2026-11-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 15:59:52', '1'),
(552, 31009367, 'CalendarioAcademico', 'calendario_academico', '4', 'MOSTRAR', '{\"id_calendario_academico\":4,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-11-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-11 16:00:49', '1'),
(553, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-11 20:58:24', '1'),
(554, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-12 03:13:50', '1'),
(555, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'CREAR', NULL, '{\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\",\"id_calendario_academico\":5}', '127.0.0.1', '2026-05-12 03:18:30', '1'),
(556, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 03:19:32', '1'),
(557, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 03:20:15', '1'),
(558, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 05:11:01', '1'),
(559, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 05:15:09', '1'),
(560, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 05:17:04', '1'),
(561, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-12 05:18:34', '1'),
(562, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-12 05:19:52', '1'),
(563, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 05:21:01', '1'),
(564, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 05:21:22', '1'),
(565, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-12 05:23:29', '1'),
(566, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-13 00:19:43', '1'),
(567, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-13 00:19:57', '1'),
(568, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-13 00:30:02', '1'),
(569, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-13 00:30:06', '1'),
(570, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-13 00:33:06', '1'),
(571, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-13 01:06:11', '1'),
(572, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-13 01:06:17', '1'),
(573, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-13 01:06:27', '1'),
(574, 31009367, 'Evento', 'evento', '13', 'MOSTRAR', '{\"id_evento\":13,\"id_color\":14,\"nombre_evento\":\"NATALICIO DEL LIBERTADOR SIM\\u00d3N BOL\\u00cdVAR\",\"tipo_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-13 01:12:42', '1'),
(575, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-03\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 01:34:40', '1'),
(576, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-13 01:46:47', '1'),
(577, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-13 01:48:08', '1'),
(578, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:07:03', '1'),
(579, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:07:19', '1'),
(580, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:07:29', '1'),
(581, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:11:11', '1'),
(582, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:11:13', '1'),
(583, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:11:22', '1'),
(584, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:11:46', '1'),
(585, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:11:49', '1'),
(586, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:11:50', '1'),
(587, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:12:12', '1'),
(588, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:12:16', '1'),
(589, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:12:25', '1'),
(590, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:12:29', '1'),
(591, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:12:51', '1'),
(592, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:12:53', '1'),
(593, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:12:55', '1'),
(594, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:13:31', '1'),
(595, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:13:34', '1'),
(596, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:13:51', '1'),
(597, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:13:52', '1'),
(598, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:14:34', '1'),
(599, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:14:41', '1'),
(600, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:14:43', '1'),
(601, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:15:36', '1'),
(602, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:16:02', '1'),
(603, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 02:16:06', '1'),
(604, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":44,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-02-28\",\"estatus\":\"4\"}', NULL, '127.0.0.1', '2026-05-13 02:18:58', '1'),
(605, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":44,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-02-28\",\"estatus\":\"4\"}', NULL, '127.0.0.1', '2026-05-13 02:18:59', '1'),
(606, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-13 03:14:30', '1'),
(607, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-13 03:20:29', '1'),
(608, 31009367, 'CalendarioAcademico', 'calendario_academico', '14', 'MOSTRAR', '{\"id_calendario_academico\":14,\"semana_calendario_academico\":52,\"dia_inicio_calendario_academico\":\"2026-02-12\",\"dia_fin_calendario_academico\":\"2027-02-04\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:30:40', '1'),
(609, 31009367, 'CalendarioAcademico', 'calendario_academico', '14', 'MOSTRAR', '{\"id_calendario_academico\":14,\"semana_calendario_academico\":52,\"dia_inicio_calendario_academico\":\"2026-02-12\",\"dia_fin_calendario_academico\":\"2027-02-04\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:30:50', '1'),
(610, 31009367, 'CalendarioAcademico', 'calendario_academico', '14', 'MOSTRAR', '{\"id_calendario_academico\":14,\"semana_calendario_academico\":52,\"dia_inicio_calendario_academico\":\"2026-02-12\",\"dia_fin_calendario_academico\":\"2027-02-04\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:31:19', '1'),
(611, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-13 03:31:55', '1'),
(612, 31009367, 'CalendarioAcademico', 'calendario_academico', '14', 'MOSTRAR', '{\"id_calendario_academico\":14,\"semana_calendario_academico\":52,\"dia_inicio_calendario_academico\":\"2026-02-12\",\"dia_fin_calendario_academico\":\"2027-02-04\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-13 03:31:57', '1'),
(613, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-13 03:32:07', '1'),
(614, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:32:49', '1'),
(615, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:35:33', '1'),
(616, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:36:10', '1'),
(617, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:36:15', '1'),
(618, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:40:41', '1'),
(619, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:41:27', '1'),
(620, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:41:34', '1'),
(621, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:41:35', '1'),
(622, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:42:01', '1'),
(623, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:42:04', '1'),
(624, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:42:13', '1'),
(625, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:42:22', '1'),
(626, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:42:29', '1'),
(627, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:42:41', '1'),
(628, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:44:06', '1'),
(629, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 03:44:09', '1'),
(630, 31009367, 'Seguridad', 'users', '31009367', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-05-13 04:32:17', '1'),
(631, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-13 04:33:51', '1'),
(632, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:34:28', '1'),
(633, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:36:18', '1'),
(634, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:43:43', '1'),
(635, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:44:26', '1'),
(636, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:45:09', '1'),
(637, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:45:52', '1'),
(638, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:46:35', '1'),
(639, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:47:18', '1'),
(640, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:48:01', '1'),
(641, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:51:49', '1'),
(642, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:52:11', '1'),
(643, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:52:32', '1'),
(644, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:55:53', '1'),
(645, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:56:36', '1'),
(646, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:56:57', '1'),
(647, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:57:41', '1'),
(648, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:58:46', '1'),
(649, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:59:08', '1'),
(650, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 04:59:29', '1'),
(651, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:07:35', '1'),
(652, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:10:44', '1'),
(653, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:11:27', '1'),
(654, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:12:09', '1'),
(655, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:12:52', '1'),
(656, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:13:13', '1'),
(657, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:14:17', '1'),
(658, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:18:18', '1'),
(659, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:21:15', '1'),
(660, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:21:37', '1'),
(661, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:23:15', '1'),
(662, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:29:25', '1'),
(663, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:29:46', '1'),
(664, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:30:50', '1');
INSERT INTO `bitacora` (`id_bitacora`, `id_usuario`, `modulo_afectado_bitacora`, `tabla_afectada_bitacora`, `id_registro_afectado_bitacora`, `accion_bitacora`, `valores_anteriores_bitacora`, `valores_nuevos_bitacora`, `ip_origen_bitacora`, `fecha_creacion`, `estatus`) VALUES
(665, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:32:26', '1'),
(666, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:36:22', '1'),
(667, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:36:43', '1'),
(668, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:37:04', '1'),
(669, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:37:26', '1'),
(670, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:40:21', '1'),
(671, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:40:42', '1'),
(672, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:41:04', '1'),
(673, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:42:03', '1'),
(674, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:42:25', '1'),
(675, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:42:46', '1'),
(676, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:43:08', '1'),
(677, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:43:29', '1'),
(678, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:43:51', '1'),
(679, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:44:12', '1'),
(680, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:44:34', '1'),
(681, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:46:29', '1'),
(682, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:47:12', '1'),
(683, 31009367, 'CalendarioAcademico', 'calendario_academico', '15', 'MOSTRAR', '{\"id_calendario_academico\":15,\"semana_calendario_academico\":35,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:51:42', '1'),
(684, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-13 05:52:50', '1'),
(685, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:53:35', '1'),
(686, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 05:58:36', '1'),
(687, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:00:26', '1'),
(688, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:00:47', '1'),
(689, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:01:08', '1'),
(690, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:01:30', '1'),
(691, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:01:52', '1'),
(692, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:02:13', '1'),
(693, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:02:35', '1'),
(694, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:02:57', '1'),
(695, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:03:18', '1'),
(696, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:05:13', '1'),
(697, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:05:35', '1'),
(698, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:05:57', '1'),
(699, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:06:18', '1'),
(700, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:07:09', '1'),
(701, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:08:20', '1'),
(702, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:09:13', '1'),
(703, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:09:49', '1'),
(704, 31009367, 'Evento', 'evento', '15', 'MOSTRAR', '{\"id_evento\":15,\"id_color\":15,\"nombre_evento\":\"LLL\",\"tipo_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-13 06:11:33', '1'),
(705, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:13:28', '1'),
(706, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:15:25', '1'),
(707, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:15:47', '1'),
(708, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:16:08', '1'),
(709, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:16:31', '1'),
(710, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:16:53', '1'),
(711, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:20:16', '1'),
(712, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:27:03', '1'),
(713, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:27:24', '1'),
(714, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:27:45', '1'),
(715, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:28:08', '1'),
(716, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:30:15', '1'),
(717, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:30:36', '1'),
(718, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:31:27', '1'),
(719, 31009367, 'CalendarioAcademico', 'calendario_academico', '17', 'MOSTRAR', '{\"id_calendario_academico\":17,\"semana_calendario_academico\":56,\"dia_inicio_calendario_academico\":\"2026-02-04\",\"dia_fin_calendario_academico\":\"2027-02-25\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-13 06:32:39', '1'),
(720, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-13 14:43:05', '1'),
(721, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-14 01:40:43', '1'),
(722, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-14 22:13:33', '1'),
(723, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-15 18:58:39', '1'),
(724, 31009367, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2027-02-03\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 19:01:43', '1'),
(725, 31009367, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2027-02-03\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 19:04:14', '1'),
(726, 31009367, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":53,\"dia_inicio_calendario_academico\":\"2026-02-01\",\"dia_fin_calendario_academico\":\"2027-02-03\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 19:04:36', '1'),
(727, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 19:24:43', '1'),
(728, 31009367, 'CalendarioAcademico', 'calendario_academico', '5', 'MOSTRAR', '{\"id_calendario_academico\":5,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 19:24:48', '1'),
(729, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 19:25:05', '1'),
(730, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 19:28:09', '1'),
(731, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 19:28:14', '1'),
(732, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 19:30:26', '1'),
(733, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 19:30:32', '1'),
(734, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 19:31:07', '1'),
(735, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 19:33:58', '1'),
(736, 31009367, 'Evento', 'evento', '17', 'CREAR', NULL, '{\"nombre_evento\":\"mondey\",\"tipo_evento\":\"5\",\"id_color\":17,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"estatus\":\"1\",\"id_evento\":17}', '127.0.0.1', '2026-05-15 20:10:43', '1'),
(737, 31009367, 'Evento', 'evento', '1', 'CREAR', NULL, '{\"nombre_evento\":\"A\\u00d1O NUEVO\",\"tipo_evento\":\"1\",\"id_color\":1,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"estatus\":\"1\",\"id_evento\":1}', '127.0.0.1', '2026-05-15 20:20:40', '1'),
(738, 31009367, 'Evento', 'evento', '2', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DE LA DEMOCRAC\\u00cdA\",\"tipo_evento\":\"1\",\"id_color\":2,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"estatus\":\"1\",\"id_evento\":2}', '127.0.0.1', '2026-05-15 20:20:54', '1'),
(739, 31009367, 'Evento', 'evento', '3', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DE TUR\\u00c9N - NO LABORABLE SOLO N\\u00daCLEO ACADEMICO TUREN \",\"tipo_evento\":\"2\",\"id_color\":3,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"estatus\":\"1\",\"id_evento\":3}', '127.0.0.1', '2026-05-15 20:21:14', '1'),
(740, 31009367, 'Evento', 'evento', '4', 'CREAR', NULL, '{\"nombre_evento\":\"D\\u00cdA DEL TRABAJADOR UNIVERSITARIO \",\"tipo_evento\":\"4\",\"id_color\":5,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"estatus\":\"1\",\"id_evento\":4}', '127.0.0.1', '2026-05-15 20:21:35', '1'),
(741, 31009367, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 20:24:03', '1'),
(742, 31009367, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 20:24:10', '1'),
(743, 31009367, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2026-05-29\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 20:24:11', '1'),
(744, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 20:24:36', '1'),
(745, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 20:24:47', '1'),
(746, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 20:25:07', '1'),
(747, 31009367, 'Evento', 'evento', '6', 'CREAR', NULL, '{\"nombre_evento\":\"es obligatorio\",\"tipo_evento\":\"3\",\"id_color\":13,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_obligatorio_evento\":true,\"estatus\":\"1\",\"id_evento\":6}', '127.0.0.1', '2026-05-15 20:48:44', '1'),
(748, 31009367, 'Evento', 'evento', '5', 'MOSTRAR', '{\"id_evento\":5,\"id_color\":4,\"nombre_evento\":\"MADURO\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"is_obligatorio_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-15 20:49:36', '1'),
(749, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-15 21:00:35', '1'),
(750, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-15 21:00:41', '1'),
(751, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-15 21:07:29', '1'),
(752, 31009367, 'Evento', 'evento', '7', 'MODIFICAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_obligatorio_evento\":true,\"estatus\":\"1\"}', '{\"is_obligatorio_evento\":false}', '127.0.0.1', '2026-05-15 21:07:32', '1'),
(753, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":0,\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-05-15 21:08:03', '1'),
(754, 31009367, 'Evento', 'evento', '7', 'MODIFICAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_obligatorio_evento\":false,\"estatus\":\"1\"}', '{\"is_obligatorio_evento\":true}', '127.0.0.1', '2026-05-15 21:08:06', '1'),
(755, 31009367, 'CalendarioAcademico', 'calendario_academico', '9', 'MOSTRAR', '{\"id_calendario_academico\":9,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-07\",\"dia_fin_calendario_academico\":\"2026-05-28\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 21:09:06', '1'),
(756, 31009367, 'CalendarioAcademico', 'calendario_academico', '9', 'MOSTRAR', '{\"id_calendario_academico\":9,\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-05-07\",\"dia_fin_calendario_academico\":\"2026-05-28\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 21:09:11', '1'),
(757, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 21:09:22', '1'),
(758, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 21:10:57', '1'),
(759, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 21:11:00', '1'),
(760, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 22:16:18', '1'),
(761, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 22:17:34', '1'),
(762, 31009367, 'CalendarioAcademico', 'calendario_academico', '8', 'MOSTRAR', '{\"id_calendario_academico\":8,\"semana_calendario_academico\":41,\"dia_inicio_calendario_academico\":\"2026-05-01\",\"dia_fin_calendario_academico\":\"2027-02-10\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-15 22:19:23', '1'),
(763, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 22:19:33', '1'),
(764, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 22:21:20', '1'),
(765, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-15 22:23:28', '1'),
(766, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 22:52:47', '1'),
(767, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 22:52:52', '1'),
(768, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:00:16', '1'),
(769, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:00:21', '1'),
(770, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:05:40', '1'),
(771, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:05:46', '1'),
(772, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:05:47', '1'),
(773, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:05:51', '1'),
(774, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:06:06', '1'),
(775, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:06:28', '1'),
(776, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:06:37', '1'),
(777, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:07:04', '1'),
(778, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:07:14', '1'),
(779, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-15 23:08:39', '1'),
(780, 31009367, 'Evento', 'evento', '7', 'MODIFICAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_obligatorio_evento\":true,\"estatus\":\"1\",\"is_rango_dias_evento\":false,\"rango_dias_evento\":null}', '{\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"6\"}', '127.0.0.1', '2026-05-15 23:12:00', '1'),
(781, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-15 23:19:02', '1'),
(782, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-15 23:19:07', '1'),
(783, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-16 05:01:05', '1'),
(784, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 05:02:08', '1'),
(785, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 05:03:55', '1'),
(786, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 05:04:17', '1'),
(787, 31009367, 'Evento', 'evento', '7', 'MODIFICAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_obligatorio_evento\":true,\"estatus\":\"1\",\"is_rango_dias_evento\":true,\"rango_dias_evento\":6}', '{\"rango_dias_evento\":\"23\"}', '127.0.0.1', '2026-05-16 05:05:38', '1'),
(788, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:06:10', '1'),
(789, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:07:14', '1'),
(790, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:07:36', '1'),
(791, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:07:57', '1'),
(792, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:09:45', '1'),
(793, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:13:07', '1'),
(794, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:13:29', '1'),
(795, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:13:50', '1'),
(796, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:17:22', '1'),
(797, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:18:20', '1'),
(798, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:18:42', '1'),
(799, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"is_obligatorio_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:22:07', '1'),
(800, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:22:29', '1'),
(801, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:22:50', '1'),
(802, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:23:12', '1'),
(803, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 05:23:33', '1'),
(804, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-16 15:30:27', '1'),
(805, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:30:37', '1'),
(806, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:36:16', '1'),
(807, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:36:23', '1'),
(808, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:42:25', '1'),
(809, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:42:33', '1'),
(810, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:42:51', '1'),
(811, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:42:57', '1'),
(812, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:43:03', '1'),
(813, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:43:26', '1'),
(814, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:43:31', '1'),
(815, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:44:19', '1'),
(816, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:44:20', '1'),
(817, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:44:57', '1'),
(818, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:46:23', '1'),
(819, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:46:35', '1'),
(820, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:46:44', '1'),
(821, 31009367, 'Evento', 'evento', '7', 'MOSTRAR', '{\"id_evento\":7,\"id_color\":6,\"nombre_evento\":\"JUDA\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":23}', NULL, '127.0.0.1', '2026-05-16 15:46:51', '1'),
(822, 31009367, 'Evento', 'evento', '8', 'MOSTRAR', '{\"id_evento\":8,\"id_color\":7,\"nombre_evento\":\"KLKL\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:48:42', '1'),
(823, 31009367, 'Evento', 'evento', '8', 'MOSTRAR', '{\"id_evento\":8,\"id_color\":7,\"nombre_evento\":\"KLKL\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:50:29', '1'),
(824, 31009367, 'Evento', 'evento', '8', 'MOSTRAR', '{\"id_evento\":8,\"id_color\":7,\"nombre_evento\":\"KLKL\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:51:04', '1'),
(825, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:51:59', '1'),
(826, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:53:37', '1'),
(827, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:53:38', '1'),
(828, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:53:56', '1'),
(829, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:55:24', '1'),
(830, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 15:55:36', '1'),
(831, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:15:12', '1'),
(832, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:15:19', '1'),
(833, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:15:25', '1'),
(834, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:15:28', '1'),
(835, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:15:31', '1'),
(836, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:16:00', '1'),
(837, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:16:07', '1');
INSERT INTO `bitacora` (`id_bitacora`, `id_usuario`, `modulo_afectado_bitacora`, `tabla_afectada_bitacora`, `id_registro_afectado_bitacora`, `accion_bitacora`, `valores_anteriores_bitacora`, `valores_nuevos_bitacora`, `ip_origen_bitacora`, `fecha_creacion`, `estatus`) VALUES
(838, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:18:02', '1'),
(839, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:18:10', '1'),
(840, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:20:51', '1'),
(841, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 16:21:00', '1'),
(842, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:04:43', '1'),
(843, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:05:35', '1'),
(844, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:05:38', '1'),
(845, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:05:45', '1'),
(846, 31009367, 'Evento', 'evento', '9', 'MOSTRAR', '{\"id_evento\":9,\"id_color\":8,\"nombre_evento\":\"L\\u00d1\\u00d1\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:05:55', '1'),
(847, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":10,\"nombre_evento\":\"RANGO\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:07:12', '1'),
(848, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":10,\"nombre_evento\":\"RANGO\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:09:14', '1'),
(849, 31009367, 'Evento', 'evento', '10', 'MOSTRAR', '{\"id_evento\":10,\"id_color\":10,\"nombre_evento\":\"RANGO\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-16 17:09:28', '1'),
(850, 31009367, 'Evento', 'evento', '11', 'MOSTRAR', '{\"id_evento\":11,\"id_color\":9,\"nombre_evento\":\"HGH\",\"tipo_evento\":\"3\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":78}', NULL, '127.0.0.1', '2026-05-16 17:11:43', '1'),
(851, 31009367, 'Evento', 'evento', '11', 'MODIFICAR', '{\"id_evento\":11,\"id_color\":9,\"nombre_evento\":\"HGH\",\"tipo_evento\":\"3\",\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"estatus\":\"1\",\"is_rango_dias_evento\":true,\"rango_dias_evento\":78}', '{\"rango_dias_evento\":\"7\"}', '127.0.0.1', '2026-05-16 17:11:48', '1'),
(852, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:23:22', '1'),
(853, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:44:06', '1'),
(854, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:44:12', '1'),
(855, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:47:18', '1'),
(856, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:47:35', '1'),
(857, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:47:41', '1'),
(858, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:47:49', '1'),
(859, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:51:01', '1'),
(860, 31009367, 'Evento', 'evento', '14', 'MOSTRAR', '{\"id_evento\":14,\"id_color\":14,\"nombre_evento\":\"JK\",\"tipo_evento\":\"4\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":6}', NULL, '127.0.0.1', '2026-05-16 17:51:07', '1'),
(861, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-17 07:05:40', '1'),
(862, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-17 16:40:03', '1'),
(863, 31009367, 'Evento', 'evento', '3', 'CREAR', NULL, '{\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"id_color\":3,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"estatus\":\"1\",\"id_evento\":3}', '127.0.0.1', '2026-05-17 17:16:17', '1'),
(864, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:16:30', '1'),
(865, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:16:36', '1'),
(866, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:16:46', '1'),
(867, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:16:51', '1'),
(868, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:17:47', '1'),
(869, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:17:56', '1'),
(870, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:18:03', '1'),
(871, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:18:14', '1'),
(872, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:18:27', '1'),
(873, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:18:34', '1'),
(874, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:18:42', '1'),
(875, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:18:47', '1'),
(876, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:19:59', '1'),
(877, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:20:07', '1'),
(878, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:20:12', '1'),
(879, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 17:21:02', '1'),
(880, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:25:00', '1'),
(881, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:39:40', '1'),
(882, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:39:48', '1'),
(883, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:39:53', '1'),
(884, 31009367, 'Evento', 'evento', '3', 'MODIFICAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"1\",\"especial_evento\":\"2\",\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"estatus\":\"1\",\"is_rango_dias_evento\":false,\"rango_dias_evento\":null}', '{\"tipo_evento\":\"5\",\"is_repetible_evento\":true}', '127.0.0.1', '2026-05-17 18:41:21', '1'),
(885, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:42:02', '1'),
(886, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:43:17', '1'),
(887, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:43:29', '1'),
(888, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:46:11', '1'),
(889, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:46:17', '1'),
(890, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:46:27', '1'),
(891, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:48:58', '1'),
(892, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:49:03', '1'),
(893, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:49:07', '1'),
(894, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:06', '1'),
(895, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:12', '1'),
(896, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:27', '1'),
(897, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:30', '1'),
(898, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:32', '1'),
(899, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:34', '1'),
(900, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:35', '1'),
(901, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 18:52:45', '1'),
(902, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 19:00:17', '1'),
(903, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 19:00:24', '1'),
(904, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 19:01:06', '1'),
(905, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 19:01:09', '1'),
(906, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-17 19:01:35', '1'),
(907, 31009367, 'Evento', 'evento', '5', 'CREAR', NULL, '{\"nombre_evento\":\"kiko\",\"tipo_evento\":\"3\",\"especial_evento\":null,\"id_color\":5,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"estatus\":\"1\",\"id_evento\":5}', '127.0.0.1', '2026-05-17 19:15:11', '1'),
(908, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-18 00:32:28', '1'),
(909, 31009367, 'Evento', 'evento', '7', 'CREAR', NULL, '{\"nombre_evento\":\"fin del lapso\",\"tipo_evento\":\"4\",\"especial_evento\":\"3\",\"id_color\":6,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"estatus\":\"1\",\"id_evento\":7}', '127.0.0.1', '2026-05-18 02:00:50', '1'),
(910, 31009367, 'Evento', 'evento', '9', 'CREAR', NULL, '{\"nombre_evento\":\"ioo\",\"tipo_evento\":\"4\",\"especial_evento\":null,\"id_color\":8,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":9}', '127.0.0.1', '2026-05-18 03:22:36', '1'),
(911, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-18 17:24:00', '1'),
(912, 31009367, 'Evento', 'evento', '10', 'CREAR', NULL, '{\"nombre_evento\":\"vacaciones colectivas\",\"tipo_evento\":\"5\",\"especial_evento\":\"1\",\"id_color\":9,\"is_laborable_evento\":false,\"is_repetible_evento\":true,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":60,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":10}', '127.0.0.1', '2026-05-18 17:25:06', '1'),
(913, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-18 22:35:48', '1'),
(914, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-19 02:44:55', '1'),
(915, 31009367, 'Evento', 'evento', '3', 'MOSTRAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":0,\"rango_dias_evento\":null,\"is_independiente_evento\":0,\"cantidad_dias_evento\":0}', NULL, '127.0.0.1', '2026-05-19 06:25:29', '1'),
(916, 31009367, 'Evento', 'evento', '3', 'MODIFICAR', '{\"id_evento\":3,\"id_color\":3,\"nombre_evento\":\"GGGi\",\"tipo_evento\":\"5\",\"especial_evento\":\"2\",\"is_laborable_evento\":false,\"is_repetible_evento\":true,\"estatus\":\"1\",\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"is_independiente_evento\":false,\"cantidad_dias_evento\":0}', '{\"nombre_evento\":\"Inicio del lapso\",\"tipo_evento\":\"4\",\"is_laborable_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"is_independiente_evento\":true,\"cantidad_dias_evento\":null}', '127.0.0.1', '2026-05-19 06:26:34', '1'),
(917, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-19 15:57:49', '1'),
(918, 31009367, 'Evento', 'evento', '11', 'CREAR', NULL, '{\"nombre_evento\":\"semana santa\",\"tipo_evento\":\"1\",\"especial_evento\":\"4\",\"id_color\":10,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":11}', '127.0.0.1', '2026-05-19 16:41:39', '1'),
(919, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-20 05:08:04', '1'),
(920, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-20 15:57:21', '1'),
(921, 31009367, 'Evento', 'evento', '1', 'CREAR', NULL, '{\"nombre_evento\":\"vacaciones\",\"tipo_evento\":\"5\",\"especial_evento\":\"1\",\"id_color\":1,\"is_laborable_evento\":false,\"is_repetible_evento\":true,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":60,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":1}', '127.0.0.1', '2026-05-20 22:44:52', '1'),
(922, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-21 02:34:06', '1'),
(923, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-21 02:34:53', '1'),
(924, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-21 17:50:24', '1'),
(925, 31009367, 'Evento', 'evento', '2', 'CREAR', NULL, '{\"nombre_evento\":\"peron\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"id_color\":2,\"is_laborable_evento\":false,\"is_repetible_evento\":true,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":false,\"id_evento\":2}', '127.0.0.1', '2026-05-21 18:08:38', '1'),
(926, 31009367, 'Evento', 'evento', '3', 'CREAR', NULL, '{\"nombre_evento\":\"dias canel\",\"tipo_evento\":\"4\",\"especial_evento\":\"2\",\"id_color\":4,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":3}', '127.0.0.1', '2026-05-21 18:36:47', '1'),
(927, 31009367, 'Evento', 'evento', '4', 'CREAR', NULL, '{\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"id_color\":3,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"2\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":4}', '127.0.0.1', '2026-05-21 18:37:16', '1'),
(928, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:35:30', '1'),
(929, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:35:35', '1'),
(930, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:40:08', '1'),
(931, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:42:26', '1'),
(932, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:42:42', '1'),
(933, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:42:49', '1'),
(934, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:43:09', '1'),
(935, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:45:14', '1'),
(936, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":2,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:45:26', '1'),
(937, 31009367, 'Evento', 'evento', '4', 'MODIFICAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"6\",\"especial_evento\":null,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"estatus\":\"1\",\"is_rango_dias_evento\":true,\"rango_dias_evento\":2,\"is_independiente_evento\":true,\"cantidad_dias_evento\":null}', '{\"tipo_evento\":\"5\",\"is_repetible_evento\":true,\"rango_dias_evento\":\"76\"}', '127.0.0.1', '2026-05-21 19:46:51', '1'),
(938, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:46:56', '1'),
(939, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:47:10', '1'),
(940, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:56:12', '1'),
(941, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 19:59:51', '1'),
(942, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:07:13', '1'),
(943, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:07:33', '1'),
(944, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:07:38', '1'),
(945, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:07:52', '1'),
(946, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:08:19', '1'),
(947, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:09:05', '1'),
(948, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:09:08', '1'),
(949, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:09:47', '1'),
(950, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:10:45', '1'),
(951, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:10:52', '1'),
(952, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:16:33', '1'),
(953, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:19:56', '1'),
(954, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:20:10', '1'),
(955, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:21:08', '1'),
(956, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:21:26', '1'),
(957, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 20:21:30', '1'),
(958, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 22:15:37', '1'),
(959, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 22:18:13', '1'),
(960, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 22:18:56', '1'),
(961, 31009367, 'Evento', 'evento', '4', 'MOSTRAR', '{\"id_evento\":4,\"id_color\":3,\"nombre_evento\":\"navidad\",\"tipo_evento\":\"5\",\"especial_evento\":null,\"is_laborable_evento\":0,\"is_repetible_evento\":1,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":76,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-21 22:19:17', '1'),
(962, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-22 01:58:08', '1'),
(963, 31009367, 'Evento', 'evento', '8', 'CREAR', NULL, '{\"nombre_evento\":\"vacaciones colectivas\",\"tipo_evento\":\"5\",\"especial_evento\":\"1\",\"id_color\":1,\"is_laborable_evento\":false,\"is_repetible_evento\":true,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":60,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":8}', '127.0.0.1', '2026-05-22 02:49:57', '1'),
(964, 31009367, 'Evento', 'evento', '9', 'CREAR', NULL, '{\"nombre_evento\":\"Inicio del lapso\",\"tipo_evento\":\"4\",\"especial_evento\":\"2\",\"id_color\":2,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":9}', '127.0.0.1', '2026-05-22 02:50:22', '1'),
(965, 31009367, 'Evento', 'evento', '10', 'CREAR', NULL, '{\"nombre_evento\":\"Fin del lapso academico\",\"tipo_evento\":\"4\",\"especial_evento\":\"3\",\"id_color\":3,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":10}', '127.0.0.1', '2026-05-22 02:50:47', '1'),
(966, 31009367, 'Evento', 'evento', '11', 'CREAR', NULL, '{\"nombre_evento\":\"semana santa\",\"tipo_evento\":\"6\",\"especial_evento\":\"4\",\"id_color\":4,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":11}', '127.0.0.1', '2026-05-22 02:51:09', '1'),
(967, 31009367, 'Evento', 'evento', '14', 'CREAR', NULL, '{\"nombre_evento\":\"carnaval\",\"tipo_evento\":\"6\",\"especial_evento\":\"5\",\"id_color\":5,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":14}', '127.0.0.1', '2026-05-22 03:01:31', '1'),
(968, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":18,\"semana_lapso_dos_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 03:08:02', '1'),
(969, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":18,\"semana_lapso_dos_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 03:08:14', '1'),
(970, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":18,\"semana_lapso_dos_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 03:12:22', '1'),
(971, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":18,\"semana_lapso_dos_calendario_academico\":18,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 03:13:00', '1'),
(972, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 03:56:06', '1'),
(973, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 03:57:49', '1'),
(974, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:10:59', '1'),
(975, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:11:15', '1'),
(976, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:12:16', '1'),
(977, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:12:44', '1'),
(978, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:13:52', '1'),
(979, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:14:06', '1'),
(980, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:14:12', '1');
INSERT INTO `bitacora` (`id_bitacora`, `id_usuario`, `modulo_afectado_bitacora`, `tabla_afectada_bitacora`, `id_registro_afectado_bitacora`, `accion_bitacora`, `valores_anteriores_bitacora`, `valores_nuevos_bitacora`, `ip_origen_bitacora`, `fecha_creacion`, `estatus`) VALUES
(981, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:15:55', '1'),
(982, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:24:35', '1'),
(983, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:24:37', '1'),
(984, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:24:56', '1'),
(985, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:25:00', '1'),
(986, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:30:51', '1'),
(987, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:31:00', '1'),
(988, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:31:04', '1'),
(989, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:31:14', '1'),
(990, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:31:50', '1'),
(991, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:31:59', '1'),
(992, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:35:13', '1'),
(993, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:35:21', '1'),
(994, 31009367, 'Evento', 'evento', '17', 'CREAR', NULL, '{\"nombre_evento\":\"Inicio del lapso introductorio\",\"tipo_evento\":\"4\",\"especial_evento\":\"7\",\"id_color\":7,\"is_laborable_evento\":true,\"is_repetible_evento\":false,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":17}', '127.0.0.1', '2026-05-22 04:39:19', '1'),
(995, 31009367, 'Evento', 'evento', '18', 'CREAR', NULL, '{\"nombre_evento\":\"fin del lapso introductorio\",\"tipo_evento\":\"4\",\"especial_evento\":\"8\",\"id_color\":9,\"is_laborable_evento\":true,\"is_repetible_evento\":false,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":18}', '127.0.0.1', '2026-05-22 04:39:40', '1'),
(996, 31009367, 'Evento', 'evento', '19', 'CREAR', NULL, '{\"nombre_evento\":\"inicio del lapso intensivo\",\"tipo_evento\":\"4\",\"especial_evento\":\"9\",\"id_color\":10,\"is_laborable_evento\":true,\"is_repetible_evento\":false,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":19}', '127.0.0.1', '2026-05-22 04:39:57', '1'),
(997, 31009367, 'Evento', 'evento', '20', 'CREAR', NULL, '{\"nombre_evento\":\"fin del lapso intensivo\",\"tipo_evento\":\"4\",\"especial_evento\":\"10\",\"id_color\":11,\"is_laborable_evento\":true,\"is_repetible_evento\":false,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":20}', '127.0.0.1', '2026-05-22 04:40:13', '1'),
(998, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:40:16', '1'),
(999, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:46:27', '1'),
(1000, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:47:06', '1'),
(1001, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:48:03', '1'),
(1002, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:48:34', '1'),
(1003, 31009367, 'CalendarioAcademico', 'calendario_academico', '6', 'MOSTRAR', '{\"id_calendario_academico\":6,\"semana_lapso_uno_calendario_academico\":0,\"semana_lapso_dos_calendario_academico\":0,\"semana_lapso_introductorio_calendario_academico\":null,\"semana_intensibo_introductorio_calendario_academico\":null,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-22 04:50:23', '1'),
(1004, 31009367, 'Seguridad', 'users', '31009367', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-05-22 22:10:32', '1'),
(1005, 31009367, 'Evento', 'evento', '18', 'MOSTRAR', '{\"id_evento\":18,\"id_color\":9,\"nombre_evento\":\"fin del lapso introductorio\",\"tipo_evento\":\"4\",\"especial_evento\":\"8\",\"is_laborable_evento\":1,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":1,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-22 23:42:56', '1'),
(1006, 31009367, 'Evento', 'evento', '18', 'MOSTRAR', '{\"id_evento\":18,\"id_color\":9,\"nombre_evento\":\"fin del lapso introductorio\",\"tipo_evento\":\"4\",\"especial_evento\":\"8\",\"is_laborable_evento\":1,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":1,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-22 23:43:01', '1'),
(1007, 31009367, 'Evento', 'evento', '18', 'MOSTRAR', '{\"id_evento\":18,\"id_color\":9,\"nombre_evento\":\"fin del lapso introductorio\",\"tipo_evento\":\"4\",\"especial_evento\":\"8\",\"is_laborable_evento\":1,\"is_repetible_evento\":0,\"estatus\":\"1\",\"is_rango_dias_evento\":1,\"rango_dias_evento\":1,\"is_independiente_evento\":1,\"cantidad_dias_evento\":null}', NULL, '127.0.0.1', '2026-05-22 23:44:03', '1'),
(1008, 31009367, 'Evento', 'evento', '21', 'CREAR', NULL, '{\"nombre_evento\":\"Vacaciones Colectivas\",\"tipo_evento\":\"5\",\"especial_evento\":\"1\",\"id_color\":1,\"is_laborable_evento\":false,\"is_repetible_evento\":true,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":60,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":21}', '127.0.0.1', '2026-05-23 00:05:00', '1'),
(1009, 31009367, 'Evento', 'evento', '22', 'CREAR', NULL, '{\"nombre_evento\":\"Inicio del Lapso Acad\\u00e9mico\",\"tipo_evento\":\"4\",\"especial_evento\":\"2\",\"id_color\":2,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":22}', '127.0.0.1', '2026-05-23 00:05:15', '1'),
(1010, 31009367, 'Evento', 'evento', '23', 'CREAR', NULL, '{\"nombre_evento\":\"Fin del Lapso Acad\\u00e9mico\",\"tipo_evento\":\"4\",\"especial_evento\":\"3\",\"id_color\":3,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":23}', '127.0.0.1', '2026-05-23 00:05:23', '1'),
(1011, 31009367, 'Evento', 'evento', '24', 'CREAR', NULL, '{\"nombre_evento\":\"Semana Santa\",\"tipo_evento\":\"6\",\"especial_evento\":\"4\",\"id_color\":4,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":24}', '127.0.0.1', '2026-05-23 00:05:33', '1'),
(1012, 31009367, 'Evento', 'evento', '25', 'CREAR', NULL, '{\"nombre_evento\":\"Carnaval\",\"tipo_evento\":\"6\",\"especial_evento\":\"5\",\"id_color\":5,\"is_laborable_evento\":false,\"is_repetible_evento\":false,\"is_rango_dias_evento\":false,\"rango_dias_evento\":null,\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":25}', '127.0.0.1', '2026-05-23 00:05:43', '1'),
(1013, 31009367, 'Evento', 'evento', '26', 'CREAR', NULL, '{\"nombre_evento\":\"Inicio del Lapso Introductorio\",\"tipo_evento\":\"4\",\"especial_evento\":\"7\",\"id_color\":6,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":26}', '127.0.0.1', '2026-05-23 00:05:53', '1'),
(1014, 31009367, 'Evento', 'evento', '27', 'CREAR', NULL, '{\"nombre_evento\":\"Fin del Lapso Introductorio\",\"tipo_evento\":\"4\",\"especial_evento\":\"8\",\"id_color\":7,\"is_laborable_evento\":true,\"is_repetible_evento\":true,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":27}', '127.0.0.1', '2026-05-23 00:06:02', '1'),
(1015, 31009367, 'Evento', 'evento', '28', 'CREAR', NULL, '{\"nombre_evento\":\"Inicio del Curso Intensivo\",\"tipo_evento\":\"4\",\"especial_evento\":\"9\",\"id_color\":8,\"is_laborable_evento\":true,\"is_repetible_evento\":false,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":28}', '127.0.0.1', '2026-05-23 00:06:19', '1'),
(1016, 31009367, 'Evento', 'evento', '29', 'CREAR', NULL, '{\"nombre_evento\":\"Fin del Curso Intensivo\",\"tipo_evento\":\"4\",\"especial_evento\":\"10\",\"id_color\":9,\"is_laborable_evento\":true,\"is_repetible_evento\":false,\"is_rango_dias_evento\":true,\"rango_dias_evento\":\"1\",\"cantidad_dias_evento\":null,\"estatus\":\"1\",\"is_independiente_evento\":true,\"id_evento\":29}', '127.0.0.1', '2026-05-23 00:06:26', '1'),
(1017, 31009367, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_lapso_uno_calendario_academico\":4,\"semana_lapso_dos_calendario_academico\":5,\"semana_lapso_uno_introductorio_calendario_academico\":7,\"semana_lapso_dos_introductorio_calendario_academico\":6,\"semana_intensibo_introductorio_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-01-01\",\"dia_fin_calendario_academico\":\"2026-12-31\",\"estatus\":\"2\"}', NULL, '127.0.0.1', '2026-05-23 00:31:28', '1'),
(1018, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-23 00:31:47', '1'),
(1019, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-23 00:40:11', '1'),
(1020, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-23 00:40:54', '1'),
(1021, 31009367, 'Calendario', 'calendario', 'reporte', 'REPORTE', NULL, NULL, '127.0.0.1', '2026-05-23 00:46:46', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario_academico`
--

CREATE TABLE `calendario_academico` (
  `id_calendario_academico` int(11) NOT NULL,
  `semana_lapso_uno_calendario_academico` int(11) DEFAULT NULL,
  `semana_lapso_dos_calendario_academico` int(11) DEFAULT NULL,
  `semana_lapso_uno_introductorio_calendario_academico` int(11) DEFAULT NULL,
  `semana_lapso_dos_introductorio_calendario_academico` int(11) DEFAULT NULL,
  `semana_intensibo_introductorio_calendario_academico` int(11) DEFAULT NULL,
  `dia_inicio_calendario_academico` date DEFAULT NULL,
  `dia_fin_calendario_academico` date DEFAULT NULL,
  `estatus` enum('1','2','3','4') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calendario_academico`
--

INSERT INTO `calendario_academico` (`id_calendario_academico`, `semana_lapso_uno_calendario_academico`, `semana_lapso_dos_calendario_academico`, `semana_lapso_uno_introductorio_calendario_academico`, `semana_lapso_dos_introductorio_calendario_academico`, `semana_intensibo_introductorio_calendario_academico`, `dia_inicio_calendario_academico`, `dia_fin_calendario_academico`, `estatus`) VALUES
(12, 4, 5, 7, 6, 4, '2026-01-01', '2026-12-31', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE `color` (
  `id_color` int(11) NOT NULL,
  `nombre_color` varchar(255) DEFAULT NULL,
  `codigo_color` varchar(20) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`id_color`, `nombre_color`, `codigo_color`, `estatus`) VALUES
(1, 'Rojo', '#DC3545', '1'),
(2, 'Azul', '#007BFF', '1'),
(3, 'Verde', '#28A745', '1'),
(4, 'Amarillo', '#FFC107', '1'),
(5, 'Naranja', '#FD7E14', '1'),
(6, 'Morado', '#6F42C1', '1'),
(7, 'Rosa', '#E83E8C', '1'),
(8, 'Cian', '#17A2B8', '1'),
(9, 'Índigo', '#6610F2', '1'),
(10, 'Gris Oscuro', '#343A40', '1'),
(11, 'Marrón', '#795548', '1'),
(12, 'Morado Claro', '#6F42C1', '1'),
(13, 'Rosa Suave', '#E83E8C', '1'),
(14, 'Cian Brillante', '#17A2B8', '1'),
(15, 'Índigo Oscuro', '#6610F2', '1'),
(16, 'Antracita', '#343A40', '1'),
(17, 'Café', '#795548', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido`
--

CREATE TABLE `contenido` (
  `id_contenido` int(11) NOT NULL,
  `titulo_contenido` text DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_bibliografia`
--

CREATE TABLE `detalle_bibliografia` (
  `id_detalle_bibliografia` int(11) NOT NULL,
  `id_unidad_corte` int(11) DEFAULT NULL,
  `id_bibliografia` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_contenido`
--

CREATE TABLE `detalle_contenido` (
  `id_detalle_contenido` int(11) NOT NULL,
  `id_unidad_corte` int(11) DEFAULT NULL,
  `id_contenido` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_evaluacion`
--

CREATE TABLE `detalle_evaluacion` (
  `id_detalle_evaluacion` int(11) NOT NULL,
  `id_tipo_evaluacion` int(11) DEFAULT NULL,
  `id_tecnica_evaluacion` int(11) DEFAULT NULL,
  `id_instrumento` int(11) DEFAULT NULL,
  `ponderacion_detalle_evaluacion` float DEFAULT NULL,
  `integrantes_detalle_evaluacion` int(11) DEFAULT NULL,
  `id_unidad_corte` int(11) DEFAULT NULL,
  `fecha_evaluacion_detalle_evaluacion` date DEFAULT NULL,
  `forma_participacion_detalle_evaluacion` enum('1','2') DEFAULT NULL,
  `estatus` enum('1','2','3','4') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_evento`
--

CREATE TABLE `detalle_evento` (
  `id_detalle_evento` int(11) NOT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `id_calendario_academico` int(11) DEFAULT NULL,
  `dia_inicio_detalle_evento` date DEFAULT NULL,
  `dia_fin_detalle_evento` date DEFAULT NULL,
  `semana_detalle_evento` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_evento`
--

INSERT INTO `detalle_evento` (`id_detalle_evento`, `id_evento`, `id_calendario_academico`, `dia_inicio_detalle_evento`, `dia_fin_detalle_evento`, `semana_detalle_evento`, `estatus`) VALUES
(27, 21, 12, '2026-07-01', '2026-07-03', NULL, '1'),
(28, 21, 12, '2026-07-06', '2026-07-10', NULL, '1'),
(29, 21, 12, '2026-07-13', '2026-07-17', NULL, '1'),
(30, 21, 12, '2026-07-20', '2026-07-24', NULL, '1'),
(31, 21, 12, '2026-07-27', '2026-07-30', NULL, '1'),
(32, 21, 12, '2026-09-01', '2026-09-04', NULL, '1'),
(33, 21, 12, '2026-09-07', '2026-09-11', NULL, '1'),
(34, 21, 12, '2026-09-14', '2026-09-18', NULL, '1'),
(35, 21, 12, '2026-09-21', '2026-09-25', NULL, '1'),
(36, 21, 12, '2026-09-28', '2026-09-30', NULL, '1'),
(37, 21, 12, '2026-08-04', '2026-08-07', NULL, '1'),
(38, 21, 12, '2026-08-10', '2026-08-14', NULL, '1'),
(39, 21, 12, '2026-08-17', '2026-08-21', NULL, '1'),
(40, 21, 12, '2026-08-24', '2026-08-25', NULL, '1'),
(41, 22, 12, '2026-01-01', '2026-01-01', NULL, '1'),
(42, 22, 12, '2026-02-23', '2026-02-23', NULL, '1'),
(43, 23, 12, '2026-01-30', '2026-01-30', NULL, '1'),
(44, 23, 12, '2026-04-03', '2026-04-03', NULL, '1'),
(45, 24, 12, '2026-01-05', '2026-01-05', NULL, '1'),
(46, 25, 12, '2026-03-10', '2026-03-10', NULL, '1'),
(47, 26, 12, '2026-01-02', '2026-01-02', NULL, '1'),
(48, 26, 12, '2026-03-02', '2026-03-02', NULL, '1'),
(49, 27, 12, '2026-02-20', '2026-02-20', NULL, '1'),
(50, 27, 12, '2026-04-17', '2026-04-17', NULL, '1'),
(51, 28, 12, '2026-08-03', '2026-08-03', NULL, '1'),
(52, 29, 12, '2026-08-28', '2026-08-28', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_objetivo`
--

CREATE TABLE `detalle_objetivo` (
  `id_detalle_objetivo` int(11) NOT NULL,
  `id_contenido` int(11) DEFAULT NULL,
  `id_objetivo` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_recurso`
--

CREATE TABLE `detalle_recurso` (
  `id_detalle_recurso` int(11) NOT NULL,
  `id_recurso` int(11) DEFAULT NULL,
  `id_unidad_corte` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL,
  `id_color` int(11) DEFAULT NULL,
  `nombre_evento` varchar(100) DEFAULT NULL,
  `tipo_evento` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1',
  `especial_evento` enum('1','2','3','4','5','6','7','8','9','10') DEFAULT NULL,
  `is_laborable_evento` tinyint(1) DEFAULT 1,
  `is_repetible_evento` tinyint(1) DEFAULT 0,
  `estatus` enum('1','2','3') DEFAULT '1',
  `is_rango_dias_evento` tinyint(1) DEFAULT 0,
  `rango_dias_evento` int(11) DEFAULT NULL,
  `is_independiente_evento` tinyint(1) DEFAULT 0,
  `cantidad_dias_evento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id_evento`, `id_color`, `nombre_evento`, `tipo_evento`, `especial_evento`, `is_laborable_evento`, `is_repetible_evento`, `estatus`, `is_rango_dias_evento`, `rango_dias_evento`, `is_independiente_evento`, `cantidad_dias_evento`) VALUES
(21, 1, 'Vacaciones Colectivas', '5', '1', 0, 1, '1', 0, NULL, 1, 60),
(22, 2, 'Inicio del Lapso Académico', '4', '2', 1, 1, '1', 1, 1, 1, NULL),
(23, 3, 'Fin del Lapso Académico', '4', '3', 1, 1, '1', 1, 1, 1, NULL),
(24, 4, 'Semana Santa', '6', '4', 0, 0, '1', 0, NULL, 1, NULL),
(25, 5, 'Carnaval', '6', '5', 0, 0, '1', 0, NULL, 1, NULL),
(26, 6, 'Inicio del Lapso Introductorio', '4', '7', 1, 1, '1', 1, 1, 1, NULL),
(27, 7, 'Fin del Lapso Introductorio', '4', '8', 1, 1, '1', 1, 1, 1, NULL),
(28, 8, 'Inicio del Curso Intensivo', '4', '9', 1, 0, '1', 1, 1, 1, NULL),
(29, 9, 'Fin del Curso Intensivo', '4', '10', 1, 0, '1', 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instrumento`
--

CREATE TABLE `instrumento` (
  `id_instrumento` int(11) NOT NULL,
  `nombre_instrumento` varchar(255) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetivo`
--

CREATE TABLE `objetivo` (
  `id_objetivo` int(11) NOT NULL,
  `titulo_objetivo` varchar(255) DEFAULT NULL,
  `id_tema_unidad` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` tinytext DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `nombre_permiso`, `estatus`) VALUES
(1, 'Index de Perfil', '1'),
(2, 'Index de Seleccionar Rol', '1'),
(3, 'Listar de Contenido', '1'),
(4, 'Crear de Contenido', '1'),
(5, 'Editar de Contenido', '1'),
(6, 'Ver Detalles de Contenido', '1'),
(7, 'Listar de Tema', '1'),
(8, 'Crear de Tema', '1'),
(9, 'Editar de Tema', '1'),
(10, 'Ver Detalles de Tema', '1'),
(11, 'Crear de Usuarios', '1'),
(12, 'Listar de Usuarios', '1'),
(13, 'Listar de Planificacion', '1'),
(14, 'Crear de Planificacion', '1'),
(15, 'Editar de Planificacion', '1'),
(16, 'Ver Detalles de Planificacion', '1'),
(17, 'Reporte General de Planificacion', '1'),
(18, 'Reporte Detallado de Planificacion', '1'),
(19, 'Reporte de Calendario', '1'),
(20, 'Listar de Indicador Logro', '1'),
(21, 'Crear de Indicador Logro', '1'),
(22, 'Editar de Indicador Logro', '1'),
(23, 'Ver Detalles de Indicador Logro', '1'),
(24, 'Listar de Bibliografia', '1'),
(25, 'Crear de Bibliografia', '1'),
(26, 'Editar de Bibliografia', '1'),
(27, 'Ver Detalles de Bibliografia', '1'),
(28, 'Listar de Recurso', '1'),
(29, 'Crear de Recurso', '1'),
(30, 'Editar de Recurso', '1'),
(31, 'Ver Detalles de Recurso', '1'),
(32, 'Listar de Estrategia', '1'),
(33, 'Crear de Estrategia', '1'),
(34, 'Editar de Estrategia', '1'),
(35, 'Ver Detalles de Estrategia', '1'),
(36, 'Listar de Tecnica Evaluacion', '1'),
(37, 'Crear de Tecnica Evaluacion', '1'),
(38, 'Editar de Tecnica Evaluacion', '1'),
(39, 'Ver Detalles de Tecnica Evaluacion', '1'),
(40, 'Listar de Tipo Evaluacion', '1'),
(41, 'Crear de Tipo Evaluacion', '1'),
(42, 'Editar de Tipo Evaluacion', '1'),
(43, 'Ver Detalles de Tipo Evaluacion', '1'),
(44, 'Listar de Evento', '1'),
(45, 'Crear de Evento', '1'),
(46, 'Ver Detalles de Evento', '1'),
(47, 'Listar de Calendario', '1'),
(48, 'Crear de Calendario', '1'),
(49, 'Ver Detalles de Calendario', '1'),
(50, 'Listar de Permiso', '1'),
(51, 'Editar de Permiso', '1'),
(52, 'Listar de Bitacora', '1'),
(53, 'Ver Detalles de Bitacora', '1'),
(54, 'Cambiar Estatus de Perfil', '1'),
(55, 'Cambiar Estatus de Seleccionar Rol', '1'),
(56, 'Cambiar Estatus de Contenido', '1'),
(57, 'Cambiar Estatus de Tema', '1'),
(58, 'Cambiar Estatus de Usuarios', '1'),
(59, 'Cambiar Estatus de Planificacion', '1'),
(60, 'Cambiar Estatus de Calendario', '1'),
(61, 'Cambiar Estatus de Indicador Logro', '1'),
(62, 'Cambiar Estatus de Bibliografia', '1'),
(63, 'Cambiar Estatus de Recurso', '1'),
(64, 'Cambiar Estatus de Estrategia', '1'),
(65, 'Cambiar Estatus de Tecnica Evaluacion', '1'),
(66, 'Cambiar Estatus de Tipo Evaluacion', '1'),
(67, 'Cambiar Estatus de Evento', '1'),
(68, 'Cambiar Estatus de Permiso', '1'),
(69, 'Cambiar Estatus de Bitacora', '1'),
(70, 'Editar de Evento', '1'),
(71, 'Acuerdo Aprendizaje de Planificacion', '1'),
(72, 'Editar de Calendario', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planificacion`
--

CREATE TABLE `planificacion` (
  `id_planificacion` int(11) NOT NULL,
  `id_profesor_asignado` int(11) DEFAULT NULL,
  `aceptado_vocero` int(11) DEFAULT NULL,
  `aceptado_coordinador` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3','4') DEFAULT '1',
  `tipo_planificacion` text DEFAULT NULL,
  `archivo_contrato` varchar(255) DEFAULT NULL,
  `notificado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recurso`
--

CREATE TABLE `recurso` (
  `id_recurso` int(11) NOT NULL,
  `nombre_recurso` varchar(255) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recurso`
--

INSERT INTO `recurso` (`id_recurso`, `nombre_recurso`, `estatus`) VALUES
(1, '23,', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol_permiso` int(11) NOT NULL,
  `id_permiso` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id_rol_permiso`, `id_permiso`, `id_rol`, `estatus`) VALUES
(1, 62, 11, '1'),
(2, 25, 11, '1'),
(3, 26, 11, '1'),
(4, 24, 11, '1'),
(5, 27, 11, '1'),
(6, 69, 11, '1'),
(7, 52, 11, '1'),
(8, 53, 11, '1'),
(9, 60, 11, '1'),
(10, 48, 11, '1'),
(11, 47, 11, '1'),
(12, 19, 11, '1'),
(13, 49, 11, '1'),
(14, 56, 11, '1'),
(15, 4, 11, '1'),
(16, 5, 11, '1'),
(17, 3, 11, '1'),
(18, 6, 11, '1'),
(19, 64, 11, '1'),
(20, 33, 11, '1'),
(21, 34, 11, '1'),
(22, 32, 11, '1'),
(23, 35, 11, '1'),
(24, 67, 11, '1'),
(25, 45, 11, '1'),
(26, 44, 11, '1'),
(27, 46, 11, '1'),
(28, 61, 11, '1'),
(29, 21, 11, '1'),
(30, 22, 11, '1'),
(31, 20, 11, '1'),
(32, 23, 11, '1'),
(33, 54, 11, '1'),
(34, 1, 11, '1'),
(35, 68, 11, '1'),
(36, 51, 11, '1'),
(37, 50, 11, '1'),
(38, 18, 11, '1'),
(39, 59, 11, '1'),
(40, 14, 11, '1'),
(41, 15, 11, '1'),
(42, 13, 11, '1'),
(43, 17, 11, '1'),
(44, 16, 11, '1'),
(45, 63, 11, '1'),
(46, 29, 11, '1'),
(47, 30, 11, '1'),
(48, 28, 11, '1'),
(49, 31, 11, '1'),
(50, 55, 11, '1'),
(51, 2, 11, '1'),
(52, 65, 11, '1'),
(53, 37, 11, '1'),
(54, 38, 11, '1'),
(55, 36, 11, '1'),
(56, 39, 11, '1'),
(57, 57, 11, '1'),
(58, 8, 11, '1'),
(59, 9, 11, '1'),
(60, 7, 11, '1'),
(61, 10, 11, '1'),
(62, 66, 11, '1'),
(63, 41, 11, '1'),
(64, 42, 11, '1'),
(65, 40, 11, '1'),
(66, 43, 11, '1'),
(67, 58, 11, '1'),
(68, 11, 11, '1'),
(69, 12, 11, '1'),
(70, 70, 11, '1'),
(71, 67, 31, '1'),
(72, 45, 31, '1'),
(73, 70, 31, '1'),
(74, 44, 31, '1'),
(75, 46, 31, '1'),
(76, 69, 31, '1'),
(77, 52, 31, '1'),
(78, 53, 31, '1'),
(79, 60, 31, '1'),
(80, 48, 31, '1'),
(81, 47, 31, '1'),
(82, 19, 31, '1'),
(83, 49, 31, '1'),
(84, 68, 31, '1'),
(85, 51, 31, '1'),
(86, 50, 31, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ginsXHuRaMoiGo5JO3GZrcRFeZavhTeWSgWzyBtQ', 43331, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoib2Rpa1R6STFWbGpOQk5vYXZKRUxzMUFndjBkZnFqUDZidGlINDZodCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYWxlbmRhcmlvL3JlcG9ydGUvMTIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjExOiJhY3RpdmVfcm9sZSI7aTozMTtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0MzMzMTtzOjExOiJ0ZW1wX2NlZHVsYSI7czo4OiIzMTAwOTM2NyI7fQ==', 1779482806);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnica_actividad`
--

CREATE TABLE `tecnica_actividad` (
  `id_tecnica_actividad` int(11) NOT NULL,
  `nombre_tecnica_actividad` varchar(255) DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnica_actividad`
--

INSERT INTO `tecnica_actividad` (`id_tecnica_actividad`, `nombre_tecnica_actividad`, `estatus`) VALUES
(1, 'pppppppppp', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnica_evaluacion`
--

CREATE TABLE `tecnica_evaluacion` (
  `id_tecnica_evaluacion` int(11) NOT NULL,
  `nombre_tecnica_evaluacion` text DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnica_evaluacion`
--

INSERT INTO `tecnica_evaluacion` (`id_tecnica_evaluacion`, `nombre_tecnica_evaluacion`, `estatus`) VALUES
(1, 'ññññ', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tema_unidad`
--

CREATE TABLE `tema_unidad` (
  `id_tema_unidad` int(11) NOT NULL,
  `id_unidad_curricular` varchar(7) DEFAULT NULL,
  `titulo_tema` text DEFAULT NULL,
  `unidad_tema` enum('1','2','3','4') DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_evaluacion`
--

CREATE TABLE `tipo_evaluacion` (
  `id_tipo_evaluacion` int(11) NOT NULL,
  `nombre_tipo_evaluacion` text DEFAULT NULL,
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_evaluacion`
--

INSERT INTO `tipo_evaluacion` (`id_tipo_evaluacion`, `nombre_tipo_evaluacion`, `estatus`) VALUES
(1, 'dasdsa', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_corte`
--

CREATE TABLE `unidad_corte` (
  `id_unidad_corte` int(11) NOT NULL,
  `id_planificacion` int(11) DEFAULT NULL,
  `numero_unidad_corte` enum('1','2','3','4') DEFAULT NULL,
  `indicador_logro_unidad_corte` text DEFAULT NULL,
  `descripcion_actividad_unidad_corte` text DEFAULT NULL,
  `descripcion_motivo_rechazo_unidad_corte` text DEFAULT NULL,
  `id_tecnica_actividad` int(11) DEFAULT NULL,
  `estatus` enum('1','2','3','4','5') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bibliografia`
--
ALTER TABLE `bibliografia`
  ADD PRIMARY KEY (`id_bibliografia`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_bitacora`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `calendario_academico`
--
ALTER TABLE `calendario_academico`
  ADD PRIMARY KEY (`id_calendario_academico`);

--
-- Indices de la tabla `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id_color`);

--
-- Indices de la tabla `contenido`
--
ALTER TABLE `contenido`
  ADD PRIMARY KEY (`id_contenido`);

--
-- Indices de la tabla `detalle_bibliografia`
--
ALTER TABLE `detalle_bibliografia`
  ADD PRIMARY KEY (`id_detalle_bibliografia`),
  ADD KEY `fk_detbibliografia_bibliografia` (`id_bibliografia`),
  ADD KEY `fk_detbibliografia_unidadcorte` (`id_unidad_corte`);

--
-- Indices de la tabla `detalle_contenido`
--
ALTER TABLE `detalle_contenido`
  ADD PRIMARY KEY (`id_detalle_contenido`),
  ADD KEY `fk_detcontenido_unidadcorte` (`id_unidad_corte`),
  ADD KEY `fk_detcontenido_contenido` (`id_contenido`);

--
-- Indices de la tabla `detalle_evaluacion`
--
ALTER TABLE `detalle_evaluacion`
  ADD PRIMARY KEY (`id_detalle_evaluacion`),
  ADD KEY `fk_deteval_tipoeval` (`id_tipo_evaluacion`),
  ADD KEY `fk_deteval_tecnicaeval` (`id_tecnica_evaluacion`),
  ADD KEY `fk_deteval_unidadcorte` (`id_unidad_corte`),
  ADD KEY `fk_deteval_instrumento` (`id_instrumento`);

--
-- Indices de la tabla `detalle_evento`
--
ALTER TABLE `detalle_evento`
  ADD PRIMARY KEY (`id_detalle_evento`),
  ADD KEY `fk_detalle_evento_evento` (`id_evento`),
  ADD KEY `fk_detalle_evento_calendario` (`id_calendario_academico`);

--
-- Indices de la tabla `detalle_objetivo`
--
ALTER TABLE `detalle_objetivo`
  ADD PRIMARY KEY (`id_detalle_objetivo`),
  ADD KEY `fk_detobjetivo_contenido` (`id_contenido`),
  ADD KEY `fk_detobjetivo_objetivo` (`id_objetivo`);

--
-- Indices de la tabla `detalle_recurso`
--
ALTER TABLE `detalle_recurso`
  ADD PRIMARY KEY (`id_detalle_recurso`),
  ADD KEY `fk_detrecurso_recurso` (`id_recurso`),
  ADD KEY `fk_detrecurso_unidadcorte` (`id_unidad_corte`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id_evento`),
  ADD UNIQUE KEY `unique_especial_evento` (`especial_evento`),
  ADD KEY `fk_evento_color` (`id_color`);

--
-- Indices de la tabla `instrumento`
--
ALTER TABLE `instrumento`
  ADD PRIMARY KEY (`id_instrumento`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `objetivo`
--
ALTER TABLE `objetivo`
  ADD PRIMARY KEY (`id_objetivo`),
  ADD KEY `fk_objetivo_temaunidad` (`id_tema_unidad`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `planificacion`
--
ALTER TABLE `planificacion`
  ADD PRIMARY KEY (`id_planificacion`);

--
-- Indices de la tabla `recurso`
--
ALTER TABLE `recurso`
  ADD PRIMARY KEY (`id_recurso`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id_rol_permiso`),
  ADD KEY `fk_rolpermiso_permiso` (`id_permiso`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tecnica_actividad`
--
ALTER TABLE `tecnica_actividad`
  ADD PRIMARY KEY (`id_tecnica_actividad`);

--
-- Indices de la tabla `tecnica_evaluacion`
--
ALTER TABLE `tecnica_evaluacion`
  ADD PRIMARY KEY (`id_tecnica_evaluacion`);

--
-- Indices de la tabla `tema_unidad`
--
ALTER TABLE `tema_unidad`
  ADD PRIMARY KEY (`id_tema_unidad`);

--
-- Indices de la tabla `tipo_evaluacion`
--
ALTER TABLE `tipo_evaluacion`
  ADD PRIMARY KEY (`id_tipo_evaluacion`);

--
-- Indices de la tabla `unidad_corte`
--
ALTER TABLE `unidad_corte`
  ADD PRIMARY KEY (`id_unidad_corte`),
  ADD KEY `fk_unidadcorte_planificacion` (`id_planificacion`),
  ADD KEY `fk_unidadcorte_tecnicaactividad` (`id_tecnica_actividad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bibliografia`
--
ALTER TABLE `bibliografia`
  MODIFY `id_bibliografia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1022;

--
-- AUTO_INCREMENT de la tabla `calendario_academico`
--
ALTER TABLE `calendario_academico`
  MODIFY `id_calendario_academico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `id_color` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `contenido`
--
ALTER TABLE `contenido`
  MODIFY `id_contenido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_bibliografia`
--
ALTER TABLE `detalle_bibliografia`
  MODIFY `id_detalle_bibliografia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_contenido`
--
ALTER TABLE `detalle_contenido`
  MODIFY `id_detalle_contenido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_evaluacion`
--
ALTER TABLE `detalle_evaluacion`
  MODIFY `id_detalle_evaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_evento`
--
ALTER TABLE `detalle_evento`
  MODIFY `id_detalle_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `detalle_objetivo`
--
ALTER TABLE `detalle_objetivo`
  MODIFY `id_detalle_objetivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_recurso`
--
ALTER TABLE `detalle_recurso`
  MODIFY `id_detalle_recurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `instrumento`
--
ALTER TABLE `instrumento`
  MODIFY `id_instrumento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `objetivo`
--
ALTER TABLE `objetivo`
  MODIFY `id_objetivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `planificacion`
--
ALTER TABLE `planificacion`
  MODIFY `id_planificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recurso`
--
ALTER TABLE `recurso`
  MODIFY `id_recurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de la tabla `tecnica_actividad`
--
ALTER TABLE `tecnica_actividad`
  MODIFY `id_tecnica_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tecnica_evaluacion`
--
ALTER TABLE `tecnica_evaluacion`
  MODIFY `id_tecnica_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tema_unidad`
--
ALTER TABLE `tema_unidad`
  MODIFY `id_tema_unidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_evaluacion`
--
ALTER TABLE `tipo_evaluacion`
  MODIFY `id_tipo_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `unidad_corte`
--
ALTER TABLE `unidad_corte`
  MODIFY `id_unidad_corte` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_bibliografia`
--
ALTER TABLE `detalle_bibliografia`
  ADD CONSTRAINT `fk_detbibliografia_bibliografia` FOREIGN KEY (`id_bibliografia`) REFERENCES `bibliografia` (`id_bibliografia`),
  ADD CONSTRAINT `fk_detbibliografia_unidadcorte` FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

--
-- Filtros para la tabla `detalle_contenido`
--
ALTER TABLE `detalle_contenido`
  ADD CONSTRAINT `fk_detcontenido_contenido` FOREIGN KEY (`id_contenido`) REFERENCES `contenido` (`id_contenido`),
  ADD CONSTRAINT `fk_detcontenido_unidadcorte` FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

--
-- Filtros para la tabla `detalle_evaluacion`
--
ALTER TABLE `detalle_evaluacion`
  ADD CONSTRAINT `fk_deteval_instrumento` FOREIGN KEY (`id_instrumento`) REFERENCES `instrumento` (`id_instrumento`),
  ADD CONSTRAINT `fk_deteval_tecnicaeval` FOREIGN KEY (`id_tecnica_evaluacion`) REFERENCES `tecnica_evaluacion` (`id_tecnica_evaluacion`),
  ADD CONSTRAINT `fk_deteval_tipoeval` FOREIGN KEY (`id_tipo_evaluacion`) REFERENCES `tipo_evaluacion` (`id_tipo_evaluacion`),
  ADD CONSTRAINT `fk_deteval_unidadcorte` FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

--
-- Filtros para la tabla `detalle_evento`
--
ALTER TABLE `detalle_evento`
  ADD CONSTRAINT `fk_detalle_evento_calendario` FOREIGN KEY (`id_calendario_academico`) REFERENCES `calendario_academico` (`id_calendario_academico`),
  ADD CONSTRAINT `fk_detalle_evento_evento` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`);

--
-- Filtros para la tabla `detalle_objetivo`
--
ALTER TABLE `detalle_objetivo`
  ADD CONSTRAINT `fk_detobjetivo_contenido` FOREIGN KEY (`id_contenido`) REFERENCES `contenido` (`id_contenido`),
  ADD CONSTRAINT `fk_detobjetivo_objetivo` FOREIGN KEY (`id_objetivo`) REFERENCES `objetivo` (`id_objetivo`);

--
-- Filtros para la tabla `detalle_recurso`
--
ALTER TABLE `detalle_recurso`
  ADD CONSTRAINT `fk_detrecurso_recurso` FOREIGN KEY (`id_recurso`) REFERENCES `recurso` (`id_recurso`),
  ADD CONSTRAINT `fk_detrecurso_unidadcorte` FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `fk_evento_color` FOREIGN KEY (`id_color`) REFERENCES `color` (`id_color`);

--
-- Filtros para la tabla `objetivo`
--
ALTER TABLE `objetivo`
  ADD CONSTRAINT `fk_objetivo_temaunidad` FOREIGN KEY (`id_tema_unidad`) REFERENCES `tema_unidad` (`id_tema_unidad`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `fk_rolpermiso_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id_permiso`);

--
-- Filtros para la tabla `unidad_corte`
--
ALTER TABLE `unidad_corte`
  ADD CONSTRAINT `fk_unidadcorte_planificacion` FOREIGN KEY (`id_planificacion`) REFERENCES `planificacion` (`id_planificacion`),
  ADD CONSTRAINT `fk_unidadcorte_tecnicaactividad` FOREIGN KEY (`id_tecnica_actividad`) REFERENCES `tecnica_actividad` (`id_tecnica_actividad`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
