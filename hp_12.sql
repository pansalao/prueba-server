-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-04-2026 a las 20:31:45
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
-- Base de datos: `hp_11`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bibliografia`
--

CREATE TABLE `bibliografia` (
  `id_bibliografia` int(11) NOT NULL,
  `nombre_bibliografia` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-24 04:25:12', '1'),
(2, 0, 'Seguridad', 'users', '43327', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-03-24 04:33:06', '1'),
(3, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-24 04:33:30', '1'),
(4, 0, 'Seguridad', 'users', '43327', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-03-24 04:53:04', '1'),
(5, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-24 04:53:18', '1'),
(6, 43327, 'Roles (Permisos)', 'rol_permiso', '1', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"69\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.184932Z\",\"id_rol_permiso\":1}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(7, 43327, 'Roles (Permisos)', 'rol_permiso', '2', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"45\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.194625Z\",\"id_rol_permiso\":2}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(8, 43327, 'Roles (Permisos)', 'rol_permiso', '3', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"46\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.201197Z\",\"id_rol_permiso\":3}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(9, 43327, 'Roles (Permisos)', 'rol_permiso', '4', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"44\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.203839Z\",\"id_rol_permiso\":4}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(10, 43327, 'Roles (Permisos)', 'rol_permiso', '5', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"47\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.205939Z\",\"id_rol_permiso\":5}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(11, 43327, 'Roles (Permisos)', 'rol_permiso', '6', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"61\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.208523Z\",\"id_rol_permiso\":6}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(12, 43327, 'Roles (Permisos)', 'rol_permiso', '7', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"14\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.210598Z\",\"id_rol_permiso\":7}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(13, 43327, 'Roles (Permisos)', 'rol_permiso', '8', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"15\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.212852Z\",\"id_rol_permiso\":8}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(14, 43327, 'Roles (Permisos)', 'rol_permiso', '9', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"13\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.214954Z\",\"id_rol_permiso\":9}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(15, 43327, 'Roles (Permisos)', 'rol_permiso', '10', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"18\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.217130Z\",\"id_rol_permiso\":10}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(16, 43327, 'Roles (Permisos)', 'rol_permiso', '11', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"17\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.219119Z\",\"id_rol_permiso\":11}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(17, 43327, 'Roles (Permisos)', 'rol_permiso', '12', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"16\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.221056Z\",\"id_rol_permiso\":12}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(18, 43327, 'Roles (Permisos)', 'rol_permiso', '13', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"62\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.223203Z\",\"id_rol_permiso\":13}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(19, 43327, 'Roles (Permisos)', 'rol_permiso', '14', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"49\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.225107Z\",\"id_rol_permiso\":14}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(20, 43327, 'Roles (Permisos)', 'rol_permiso', '15', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"50\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.227054Z\",\"id_rol_permiso\":15}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(21, 43327, 'Roles (Permisos)', 'rol_permiso', '16', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"48\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.228848Z\",\"id_rol_permiso\":16}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(22, 43327, 'Roles (Permisos)', 'rol_permiso', '17', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"19\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.230626Z\",\"id_rol_permiso\":17}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(23, 43327, 'Roles (Permisos)', 'rol_permiso', '18', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"51\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-24T00:58:45.232526Z\",\"id_rol_permiso\":18}', '127.0.0.1', '2026-03-24 04:58:45', '1'),
(24, 43327, 'CalendarioAcademico', 'calendario_academico', '1', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-03-01\",\"dia_fin_calendario_academico\":\"2026-03-31\",\"fecha_creacion\":\"2026-03-24T01:05:02.999740Z\",\"estatus\":\"1\",\"id_calendario_academico\":1}', '127.0.0.1', '2026-03-24 05:05:03', '1'),
(25, 43327, 'Evento', 'evento', '1', 'CREAR', NULL, '{\"id_calendario\":1,\"dia_inicio_evento\":\"2026-03-18\",\"dia_fin_evento\":\"2026-03-21\",\"descripcion_evento\":\"dsd\",\"tipo_evento\":\"1\",\"fecha_creacion\":\"2026-03-24T01:05:46.510227Z\",\"estatus\":\"1\",\"id_evento\":1}', '127.0.0.1', '2026-03-24 05:05:46', '1'),
(26, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-25 05:23:50', '1'),
(27, 43327, 'Roles (Permisos)', 'rol_permiso', '19', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"61\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-25T01:30:31.447882Z\",\"id_rol_permiso\":19}', '127.0.0.1', '2026-03-25 05:30:31', '1'),
(28, 43327, 'Roles (Permisos)', 'rol_permiso', '20', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"14\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-25T01:30:31.453328Z\",\"id_rol_permiso\":20}', '127.0.0.1', '2026-03-25 05:30:31', '1'),
(29, 43327, 'Roles (Permisos)', 'rol_permiso', '21', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"15\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-25T01:30:31.455904Z\",\"id_rol_permiso\":21}', '127.0.0.1', '2026-03-25 05:30:31', '1'),
(30, 43327, 'Roles (Permisos)', 'rol_permiso', '22', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"13\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-25T01:30:31.463826Z\",\"id_rol_permiso\":22}', '127.0.0.1', '2026-03-25 05:30:31', '1'),
(31, 43327, 'Roles (Permisos)', 'rol_permiso', '23', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"18\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-25T01:30:31.470426Z\",\"id_rol_permiso\":23}', '127.0.0.1', '2026-03-25 05:30:31', '1'),
(32, 43327, 'Roles (Permisos)', 'rol_permiso', '24', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"17\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-25T01:30:31.472387Z\",\"id_rol_permiso\":24}', '127.0.0.1', '2026-03-25 05:30:31', '1'),
(33, 43327, 'Roles (Permisos)', 'rol_permiso', '25', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"16\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-25T01:30:31.474118Z\",\"id_rol_permiso\":25}', '127.0.0.1', '2026-03-25 05:30:31', '1'),
(34, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-28 15:26:21', '1'),
(35, 43327, 'Roles (Permisos)', 'rol_permiso', '26', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"62\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-28T11:50:50.467915Z\",\"id_rol_permiso\":26}', '127.0.0.1', '2026-03-28 15:50:50', '1'),
(36, 43327, 'Roles (Permisos)', 'rol_permiso', '27', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"49\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-28T11:50:50.473816Z\",\"id_rol_permiso\":27}', '127.0.0.1', '2026-03-28 15:50:50', '1'),
(37, 43327, 'Roles (Permisos)', 'rol_permiso', '28', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"50\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-28T11:50:50.477336Z\",\"id_rol_permiso\":28}', '127.0.0.1', '2026-03-28 15:50:50', '1'),
(38, 43327, 'Roles (Permisos)', 'rol_permiso', '29', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"48\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-28T11:50:50.481293Z\",\"id_rol_permiso\":29}', '127.0.0.1', '2026-03-28 15:50:50', '1'),
(39, 43327, 'Roles (Permisos)', 'rol_permiso', '30', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"19\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-28T11:50:50.488747Z\",\"id_rol_permiso\":30}', '127.0.0.1', '2026-03-28 15:50:50', '1'),
(40, 43327, 'Roles (Permisos)', 'rol_permiso', '31', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"51\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-28T11:50:50.492067Z\",\"id_rol_permiso\":31}', '127.0.0.1', '2026-03-28 15:50:50', '1'),
(41, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-07 14:58:22', '1'),
(42, 43325, 'Roles (Permisos)', 'rol_permiso', '19', 'MODIFICAR', '{\"id_rol_permiso\":19,\"id_permiso\":61,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:32:43.844281Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:32:43', '1'),
(43, 43325, 'Roles (Permisos)', 'rol_permiso', '20', 'MODIFICAR', '{\"id_rol_permiso\":20,\"id_permiso\":14,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:32:43.860660Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:32:43', '1'),
(44, 43325, 'Roles (Permisos)', 'rol_permiso', '21', 'MODIFICAR', '{\"id_rol_permiso\":21,\"id_permiso\":15,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:32:43.863778Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:32:43', '1'),
(45, 43325, 'Roles (Permisos)', 'rol_permiso', '23', 'MODIFICAR', '{\"id_rol_permiso\":23,\"id_permiso\":18,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:32:43.866415Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:32:43', '1'),
(46, 43325, 'Roles (Permisos)', 'rol_permiso', '24', 'MODIFICAR', '{\"id_rol_permiso\":24,\"id_permiso\":17,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:32:43.869147Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:32:43', '1'),
(47, 43325, 'Roles (Permisos)', 'rol_permiso', '1', 'MODIFICAR', '{\"id_rol_permiso\":1,\"id_permiso\":69,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.743673Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(48, 43325, 'Roles (Permisos)', 'rol_permiso', '2', 'MODIFICAR', '{\"id_rol_permiso\":2,\"id_permiso\":45,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.749532Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(49, 43325, 'Roles (Permisos)', 'rol_permiso', '3', 'MODIFICAR', '{\"id_rol_permiso\":3,\"id_permiso\":46,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.752910Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(50, 43325, 'Roles (Permisos)', 'rol_permiso', '4', 'MODIFICAR', '{\"id_rol_permiso\":4,\"id_permiso\":44,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.755523Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(51, 43325, 'Roles (Permisos)', 'rol_permiso', '5', 'MODIFICAR', '{\"id_rol_permiso\":5,\"id_permiso\":47,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.758395Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(52, 43325, 'Roles (Permisos)', 'rol_permiso', '13', 'MODIFICAR', '{\"id_rol_permiso\":13,\"id_permiso\":62,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.761837Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(53, 43325, 'Roles (Permisos)', 'rol_permiso', '14', 'MODIFICAR', '{\"id_rol_permiso\":14,\"id_permiso\":49,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.764721Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(54, 43325, 'Roles (Permisos)', 'rol_permiso', '15', 'MODIFICAR', '{\"id_rol_permiso\":15,\"id_permiso\":50,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.767064Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(55, 43325, 'Roles (Permisos)', 'rol_permiso', '16', 'MODIFICAR', '{\"id_rol_permiso\":16,\"id_permiso\":48,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.775474Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(56, 43325, 'Roles (Permisos)', 'rol_permiso', '17', 'MODIFICAR', '{\"id_rol_permiso\":17,\"id_permiso\":19,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.778080Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(57, 43325, 'Roles (Permisos)', 'rol_permiso', '18', 'MODIFICAR', '{\"id_rol_permiso\":18,\"id_permiso\":51,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-04-07T11:36:33.780579Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-04-07 15:36:33', '1'),
(58, 43325, 'Roles (Permisos)', 'rol_permiso', '32', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"66\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.292787Z\",\"id_rol_permiso\":32}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(59, 43325, 'Roles (Permisos)', 'rol_permiso', '33', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"33\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.303988Z\",\"id_rol_permiso\":33}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(60, 43325, 'Roles (Permisos)', 'rol_permiso', '34', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"34\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.321373Z\",\"id_rol_permiso\":34}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(61, 43325, 'Roles (Permisos)', 'rol_permiso', '35', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"32\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.323443Z\",\"id_rol_permiso\":35}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(62, 43325, 'Roles (Permisos)', 'rol_permiso', '36', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"35\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.332366Z\",\"id_rol_permiso\":36}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(63, 43325, 'Roles (Permisos)', 'rol_permiso', '37', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"29\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.335411Z\",\"id_rol_permiso\":37}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(64, 43325, 'Roles (Permisos)', 'rol_permiso', '38', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"30\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.338325Z\",\"id_rol_permiso\":38}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(65, 43325, 'Roles (Permisos)', 'rol_permiso', '39', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"28\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.341128Z\",\"id_rol_permiso\":39}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(66, 43325, 'Roles (Permisos)', 'rol_permiso', '40', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"65\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.344028Z\",\"id_rol_permiso\":40}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(67, 43325, 'Roles (Permisos)', 'rol_permiso', '41', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"31\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.346087Z\",\"id_rol_permiso\":41}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(68, 43325, 'Roles (Permisos)', 'rol_permiso', '42', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"67\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.348046Z\",\"id_rol_permiso\":42}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(69, 43325, 'Roles (Permisos)', 'rol_permiso', '43', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"37\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.349876Z\",\"id_rol_permiso\":43}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(70, 43325, 'Roles (Permisos)', 'rol_permiso', '44', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"38\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.351858Z\",\"id_rol_permiso\":44}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(71, 43325, 'Roles (Permisos)', 'rol_permiso', '45', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"36\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.354758Z\",\"id_rol_permiso\":45}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(72, 43325, 'Roles (Permisos)', 'rol_permiso', '46', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"39\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.357789Z\",\"id_rol_permiso\":46}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(73, 43325, 'Roles (Permisos)', 'rol_permiso', '47', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"68\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.360815Z\",\"id_rol_permiso\":47}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(74, 43325, 'Roles (Permisos)', 'rol_permiso', '48', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"41\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.363821Z\",\"id_rol_permiso\":48}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(75, 43325, 'Roles (Permisos)', 'rol_permiso', '49', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"42\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.366669Z\",\"id_rol_permiso\":49}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(76, 43325, 'Roles (Permisos)', 'rol_permiso', '50', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"40\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.370071Z\",\"id_rol_permiso\":50}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(77, 43325, 'Roles (Permisos)', 'rol_permiso', '51', 'CREAR', NULL, '{\"id_rol\":\"3\",\"id_permiso\":\"43\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:38:53.372520Z\",\"id_rol_permiso\":51}', '127.0.0.1', '2026-04-07 15:38:53', '1'),
(78, 43325, 'Roles (Permisos)', 'rol_permiso', '52', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"16\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:40:35.038237Z\",\"id_rol_permiso\":52}', '127.0.0.1', '2026-04-07 15:40:35', '1'),
(79, 43325, 'Roles (Permisos)', 'rol_permiso', '53', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"13\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:40:35.049715Z\",\"id_rol_permiso\":53}', '127.0.0.1', '2026-04-07 15:40:35', '1'),
(80, 43325, 'Roles (Permisos)', 'rol_permiso', '54', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"59\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:49:15.867736Z\",\"id_rol_permiso\":54}', '127.0.0.1', '2026-04-07 15:49:15', '1'),
(81, 43325, 'Roles (Permisos)', 'rol_permiso', '55', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"8\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:49:15.874932Z\",\"id_rol_permiso\":55}', '127.0.0.1', '2026-04-07 15:49:15', '1'),
(82, 43325, 'Roles (Permisos)', 'rol_permiso', '56', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"9\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:49:15.878413Z\",\"id_rol_permiso\":56}', '127.0.0.1', '2026-04-07 15:49:15', '1'),
(83, 43325, 'Roles (Permisos)', 'rol_permiso', '57', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"7\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:49:15.881815Z\",\"id_rol_permiso\":57}', '127.0.0.1', '2026-04-07 15:49:15', '1'),
(84, 43325, 'Roles (Permisos)', 'rol_permiso', '58', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"10\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T11:49:15.885198Z\",\"id_rol_permiso\":58}', '127.0.0.1', '2026-04-07 15:49:15', '1'),
(85, 43325, 'Recurso', 'recurso', '1', 'CREAR', NULL, '{\"nombre_recurso\":\"dsdd\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T12:01:56.314873Z\",\"fecha_actualizacion\":\"2026-04-07T12:01:56.314888Z\",\"id_recurso\":1}', '127.0.0.1', '2026-04-07 16:01:56', '1'),
(86, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-08 00:57:12', '1'),
(87, 43325, 'Roles (Permisos)', 'rol_permiso', '59', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"62\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T21:12:40.632940Z\",\"id_rol_permiso\":59}', '127.0.0.1', '2026-04-08 01:12:40', '1'),
(88, 43325, 'Roles (Permisos)', 'rol_permiso', '60', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"49\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T21:12:40.639109Z\",\"id_rol_permiso\":60}', '127.0.0.1', '2026-04-08 01:12:40', '1'),
(89, 43325, 'Roles (Permisos)', 'rol_permiso', '61', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"50\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T21:12:40.641954Z\",\"id_rol_permiso\":61}', '127.0.0.1', '2026-04-08 01:12:40', '1'),
(90, 43325, 'Roles (Permisos)', 'rol_permiso', '62', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"48\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T21:12:40.644626Z\",\"id_rol_permiso\":62}', '127.0.0.1', '2026-04-08 01:12:40', '1'),
(91, 43325, 'Roles (Permisos)', 'rol_permiso', '63', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"19\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T21:12:40.647520Z\",\"id_rol_permiso\":63}', '127.0.0.1', '2026-04-08 01:12:40', '1'),
(92, 43325, 'Roles (Permisos)', 'rol_permiso', '64', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"51\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-07T21:12:40.650259Z\",\"id_rol_permiso\":64}', '127.0.0.1', '2026-04-08 01:12:40', '1'),
(93, 43325, 'CalendarioAcademico', 'calendario_academico', '2', 'CREAR', NULL, '{\"semana_calendario_academico\":1,\"dia_inicio_calendario_academico\":\"2026-04-07\",\"dia_fin_calendario_academico\":\"2026-04-08\",\"fecha_creacion\":\"2026-04-07T21:28:48.644100Z\",\"estatus\":\"1\",\"id_calendario_academico\":2}', '127.0.0.1', '2026-04-08 01:28:48', '1'),
(94, 43325, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-03-01\",\"dia_fin_calendario_academico\":\"2026-03-31\",\"fecha_creacion\":\"2026-03-24 01:05:02\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-04-08 01:30:59', '1'),
(95, 43325, 'CalendarioAcademico', 'calendario_academico', '2', 'MODIFICAR', '{\"id_calendario_academico\":2,\"semana_calendario_academico\":1,\"dia_inicio_calendario_academico\":\"2026-04-07\",\"dia_fin_calendario_academico\":\"2026-04-08\",\"fecha_creacion\":\"2026-04-07 21:28:48\",\"estatus\":\"1\"}', '{\"dia_fin_calendario_academico\":\"2026-04-07\"}', '127.0.0.1', '2026-04-08 01:37:04', '1'),
(96, 43325, 'CalendarioAcademico', 'calendario_academico', '1', 'MOSTRAR', '{\"id_calendario_academico\":1,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-03-01\",\"dia_fin_calendario_academico\":\"2026-03-31\",\"fecha_creacion\":\"2026-03-24 01:05:02\",\"estatus\":\"3\"}', NULL, '127.0.0.1', '2026-04-08 01:51:28', '1'),
(97, 43325, 'CalendarioAcademico', 'calendario_academico', '2', 'MOSTRAR', '{\"id_calendario_academico\":2,\"semana_calendario_academico\":1,\"dia_inicio_calendario_academico\":\"2026-04-07\",\"dia_fin_calendario_academico\":\"2026-04-07\",\"fecha_creacion\":\"2026-04-07 21:28:48\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-08 01:51:34', '1'),
(98, 43325, 'CalendarioAcademico', 'calendario_academico', '2', 'MOSTRAR', '{\"id_calendario_academico\":2,\"semana_calendario_academico\":1,\"dia_inicio_calendario_academico\":\"2026-04-07\",\"dia_fin_calendario_academico\":\"2026-04-07\",\"fecha_creacion\":\"2026-04-07 21:28:48\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-08 01:51:44', '1'),
(99, 43325, 'CalendarioAcademico', 'calendario_academico', '2', 'MOSTRAR', '{\"id_calendario_academico\":2,\"semana_calendario_academico\":1,\"dia_inicio_calendario_academico\":\"2026-04-07\",\"dia_fin_calendario_academico\":\"2026-04-07\",\"fecha_creacion\":\"2026-04-07 21:28:48\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-08 02:01:31', '1'),
(100, 43325, 'CalendarioAcademico', 'calendario_academico', '2', 'MOSTRAR', '{\"id_calendario_academico\":2,\"semana_calendario_academico\":1,\"dia_inicio_calendario_academico\":\"2026-04-07\",\"dia_fin_calendario_academico\":\"2026-04-07\",\"fecha_creacion\":\"2026-04-07 21:28:48\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-08 02:01:54', '1'),
(101, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-08 03:20:59', '1'),
(102, 1, 'CalendarioAcademico', 'calendario_academico', '3', 'CREAR', NULL, '{\"semana_calendario_academico\":4,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-22\",\"fecha_creacion\":\"2026-04-07T23:33:01.199930Z\",\"estatus\":\"1\",\"id_calendario_academico\":3}', '127.0.0.1', '2026-04-08 03:33:01', '1'),
(103, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-08 03:33:23', '1'),
(104, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-08 03:37:13', '1'),
(105, 1, 'CalendarioAcademico', 'calendario_academico', '4', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-02\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"fecha_creacion\":\"2026-04-08T00:58:18.820138Z\",\"estatus\":\"1\",\"id_calendario_academico\":4}', '127.0.0.1', '2026-04-08 04:58:18', '1'),
(106, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-08 04:58:20', '1'),
(107, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-10 19:40:57', '1'),
(108, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-11 01:50:29', '1'),
(109, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-11 02:51:20', '1'),
(110, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-11 02:52:17', '1'),
(111, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-11 02:52:21', '1'),
(112, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-11 02:52:55', '1'),
(113, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-11 02:53:07', '1'),
(114, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-11 02:53:44', '1'),
(115, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-11 02:53:58', '1'),
(116, 39195, 'Roles (Permisos)', 'rol_permiso', '19', 'MODIFICAR', '{\"id_rol_permiso\":19,\"id_permiso\":61,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":\"2026-04-07 11:32:43\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-10T22:57:37.900176Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-11 02:57:37', '1'),
(117, 39195, 'Roles (Permisos)', 'rol_permiso', '20', 'MODIFICAR', '{\"id_rol_permiso\":20,\"id_permiso\":14,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":\"2026-04-07 11:32:43\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-10T22:57:37.909357Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-11 02:57:37', '1'),
(118, 39195, 'Roles (Permisos)', 'rol_permiso', '21', 'MODIFICAR', '{\"id_rol_permiso\":21,\"id_permiso\":15,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":\"2026-04-07 11:32:43\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-10T22:57:37.911821Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-11 02:57:37', '1'),
(119, 39195, 'Roles (Permisos)', 'rol_permiso', '24', 'MODIFICAR', '{\"id_rol_permiso\":24,\"id_permiso\":17,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":\"2026-04-07 11:32:43\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-10T22:57:37.914544Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-11 02:57:37', '1'),
(120, 39195, 'Roles (Permisos)', 'rol_permiso', '23', 'MODIFICAR', '{\"id_rol_permiso\":23,\"id_permiso\":18,\"id_rol\":4,\"fecha_creacion\":\"2026-03-25 01:30:31\",\"fecha_actualizacion\":\"2026-04-07 11:32:43\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-10T22:57:37.922834Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-11 02:57:37', '1'),
(121, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-15 19:23:48', '1'),
(122, 39195, 'Roles (Permisos)', 'rol_permiso', '1', 'MODIFICAR', '{\"id_rol_permiso\":1,\"id_permiso\":69,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":\"2026-04-07 11:36:33\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-15T15:29:02.549441Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-15 19:29:02', '1'),
(123, 39195, 'Roles (Permisos)', 'rol_permiso', '2', 'MODIFICAR', '{\"id_rol_permiso\":2,\"id_permiso\":45,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":\"2026-04-07 11:36:33\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-15T15:29:02.555147Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-15 19:29:02', '1'),
(124, 39195, 'Roles (Permisos)', 'rol_permiso', '3', 'MODIFICAR', '{\"id_rol_permiso\":3,\"id_permiso\":46,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":\"2026-04-07 11:36:33\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-15T15:29:02.558046Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-15 19:29:02', '1'),
(125, 39195, 'Roles (Permisos)', 'rol_permiso', '4', 'MODIFICAR', '{\"id_rol_permiso\":4,\"id_permiso\":44,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":\"2026-04-07 11:36:33\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-15T15:29:02.560967Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-15 19:29:02', '1'),
(126, 39195, 'Roles (Permisos)', 'rol_permiso', '5', 'MODIFICAR', '{\"id_rol_permiso\":5,\"id_permiso\":47,\"id_rol\":3,\"fecha_creacion\":\"2026-03-24 00:58:45\",\"fecha_actualizacion\":\"2026-04-07 11:36:33\",\"estatus\":\"3\"}', '{\"fecha_actualizacion\":\"2026-04-15T15:29:02.563877Z\",\"estatus\":\"1\"}', '127.0.0.1', '2026-04-15 19:29:02', '1'),
(127, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-15 22:07:16', '1'),
(128, 0, 'Seguridad', 'users', '43327', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-15 22:38:59', '1'),
(129, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-15 22:40:23', '1'),
(130, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-15 22:41:47', '1'),
(131, 0, 'Seguridad', 'users', '43327', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-15 22:42:49', '1'),
(132, 0, 'Seguridad', 'users', '43327', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-15 22:43:28', '1'),
(133, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-15 22:48:11', '1'),
(134, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-17 21:27:00', '1'),
(135, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-17 21:42:29', '1'),
(136, 1, 'CalendarioAcademico', 'calendario_academico', '5', 'CREAR', NULL, '{\"semana_calendario_academico\":15,\"dia_inicio_calendario_academico\":\"2026-05-06\",\"dia_fin_calendario_academico\":\"2026-08-14\",\"fecha_creacion\":\"2026-04-17T19:02:39.033421Z\",\"estatus\":\"1\",\"id_calendario_academico\":5}', '127.0.0.1', '2026-04-17 23:02:39', '1'),
(137, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-17 23:02:46', '1'),
(138, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-17 23:03:50', '1'),
(139, 1, 'CalendarioAcademico', 'calendario_academico', '6', 'CREAR', NULL, '{\"semana_calendario_academico\":29,\"dia_inicio_calendario_academico\":\"2026-02-03\",\"dia_fin_calendario_academico\":\"2026-08-22\",\"fecha_creacion\":\"2026-04-17T19:10:52.401487Z\",\"estatus\":\"1\",\"id_calendario_academico\":6}', '127.0.0.1', '2026-04-17 23:10:52', '1'),
(140, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-17 23:10:54', '1'),
(141, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-17 23:52:53', '1'),
(142, 0, 'Seguridad', 'users', '39161', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-17 23:54:34', '1'),
(143, 0, 'Seguridad', 'users', '39161', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-17 23:54:34', '1'),
(144, 0, 'Seguridad', 'users', '39161', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-17 23:58:33', '1'),
(145, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-17 23:58:57', '1'),
(146, 43325, 'Roles (Permisos)', 'rol_permiso', '65', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"58\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-17T20:07:08.605792Z\",\"id_rol_permiso\":65}', '127.0.0.1', '2026-04-18 00:07:08', '1'),
(147, 43325, 'Roles (Permisos)', 'rol_permiso', '66', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"4\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-17T20:07:08.611190Z\",\"id_rol_permiso\":66}', '127.0.0.1', '2026-04-18 00:07:08', '1'),
(148, 43325, 'Roles (Permisos)', 'rol_permiso', '67', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"5\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-17T20:07:08.616339Z\",\"id_rol_permiso\":67}', '127.0.0.1', '2026-04-18 00:07:08', '1'),
(149, 43325, 'Roles (Permisos)', 'rol_permiso', '68', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"3\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-17T20:07:08.619030Z\",\"id_rol_permiso\":68}', '127.0.0.1', '2026-04-18 00:07:08', '1'),
(150, 43325, 'Roles (Permisos)', 'rol_permiso', '69', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"6\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-17T20:07:08.621165Z\",\"id_rol_permiso\":69}', '127.0.0.1', '2026-04-18 00:07:08', '1'),
(151, 43325, 'Tema', 'tema_unidad', '1', 'CREAR', NULL, '{\"id_unidad_curricular\":\"2\",\"titulo_tema\":\"ahasdhais\",\"unidad_tema\":\"1\",\"fecha_creacion\":\"2026-04-17T20:07:30.020037Z\",\"fecha_actualizacion\":null,\"estatus\":\"1\",\"id_tema_unidad\":1}', '127.0.0.1', '2026-04-18 00:07:30', '1'),
(152, 43325, 'Contenido', 'contenido', '1', 'CREAR', NULL, '{\"titulo_contenido\":\"kfhfihfajiaiiad\",\"fecha_creacion\":\"2026-04-17T20:07:49.623648Z\",\"estatus\":\"1\",\"id_contenido\":1}', '127.0.0.1', '2026-04-18 00:07:49', '1'),
(153, 43325, 'Estrategia', 'tecnica_actividad', '1', 'CREAR', NULL, '{\"nombre_tecnica_actividad\":\"pppppppppp\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-17T20:14:28.418775Z\",\"fecha_actualizacion\":\"2026-04-17T20:14:28.418792Z\",\"id_tecnica_actividad\":1}', '127.0.0.1', '2026-04-18 00:14:28', '1'),
(154, 1, 'CalendarioAcademico', 'calendario_academico', '7', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"fecha_creacion\":\"2026-04-19T12:51:01.555631Z\",\"estatus\":\"1\",\"id_calendario_academico\":7}', '127.0.0.1', '2026-04-19 16:51:01', '1'),
(155, 1, 'CalendarioAcademico', 'calendario_academico', '8', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"fecha_creacion\":\"2026-04-19T14:18:38.725505Z\",\"estatus\":\"1\",\"id_calendario_academico\":8}', '127.0.0.1', '2026-04-19 18:18:38', '1'),
(156, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-19 18:18:40', '1'),
(157, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-19 20:13:02', '1'),
(158, 1, 'CalendarioAcademico', 'calendario_academico', '9', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"fecha_creacion\":\"2026-04-19T16:32:22.010781Z\",\"estatus\":\"1\",\"id_calendario_academico\":9}', '127.0.0.1', '2026-04-19 20:32:22', '1'),
(159, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-19 20:32:30', '1'),
(160, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-19 20:34:19', '1'),
(161, 1, 'CalendarioAcademico', 'calendario_academico', '10', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"fecha_creacion\":\"2026-04-19T16:50:51.424871Z\",\"estatus\":\"1\",\"id_calendario_academico\":10}', '127.0.0.1', '2026-04-19 20:50:51', '1'),
(162, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-19 20:50:54', '1'),
(163, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-19 20:52:25', '1'),
(164, 1, 'CalendarioAcademico', 'calendario_academico', '10', 'MODIFICAR', '{\"id_calendario_academico\":10,\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"fecha_creacion\":\"2026-04-19 16:50:51\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-19 20:52:28', '1'),
(165, 1, 'CalendarioAcademico', 'calendario_academico', '11', 'CREAR', NULL, '{\"semana_calendario_academico\":5,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-04-30\",\"fecha_creacion\":\"2026-04-19T16:53:07.041000Z\",\"estatus\":\"1\",\"id_calendario_academico\":11}', '127.0.0.1', '2026-04-19 20:53:07', '1'),
(166, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-19 20:53:08', '1'),
(167, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-19 20:55:00', '1'),
(168, 1, 'CalendarioAcademico', 'calendario_academico', '12', 'CREAR', NULL, '{\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"fecha_creacion\":\"2026-04-19T16:55:42.564150Z\",\"estatus\":\"1\",\"id_calendario_academico\":12}', '127.0.0.1', '2026-04-19 20:55:42', '1'),
(169, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-19 20:55:51', '1'),
(170, 43325, 'Roles (Permisos)', 'rol_permiso', '70', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"69\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-19T16:57:03.936785Z\",\"id_rol_permiso\":70}', '127.0.0.1', '2026-04-19 20:57:03', '1'),
(171, 43325, 'Roles (Permisos)', 'rol_permiso', '71', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"45\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-19T16:57:03.940581Z\",\"id_rol_permiso\":71}', '127.0.0.1', '2026-04-19 20:57:03', '1'),
(172, 43325, 'Roles (Permisos)', 'rol_permiso', '72', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"46\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-19T16:57:03.942966Z\",\"id_rol_permiso\":72}', '127.0.0.1', '2026-04-19 20:57:03', '1'),
(173, 43325, 'Roles (Permisos)', 'rol_permiso', '73', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"44\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-19T16:57:03.950172Z\",\"id_rol_permiso\":73}', '127.0.0.1', '2026-04-19 20:57:03', '1'),
(174, 43325, 'Roles (Permisos)', 'rol_permiso', '74', 'CREAR', NULL, '{\"id_rol\":\"11\",\"id_permiso\":\"47\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-04-19T16:57:03.952366Z\",\"id_rol_permiso\":74}', '127.0.0.1', '2026-04-19 20:57:03', '1'),
(175, 43325, 'CalendarioAcademico', 'calendario_academico', '12', 'MOSTRAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"fecha_creacion\":\"2026-04-19 16:55:42\",\"estatus\":\"1\"}', NULL, '127.0.0.1', '2026-04-19 20:57:15', '1'),
(176, 1, 'CalendarioAcademico', 'calendario_academico', '12', 'MODIFICAR', '{\"id_calendario_academico\":12,\"semana_calendario_academico\":9,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-05-31\",\"fecha_creacion\":\"2026-04-19 16:55:42\",\"estatus\":\"1\"}', '{\"estatus\":\"3\"}', '127.0.0.1', '2026-04-19 20:59:41', '1'),
(177, 0, 'Seguridad', 'users', '43325', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-04-19 20:59:44', '1'),
(178, 1, 'CalendarioAcademico', 'calendario_academico', '13', 'CREAR', NULL, '{\"semana_calendario_academico\":13,\"dia_inicio_calendario_academico\":\"2026-04-01\",\"dia_fin_calendario_academico\":\"2026-06-30\",\"fecha_creacion\":\"2026-04-19T17:00:31.648598Z\",\"estatus\":\"1\",\"id_calendario_academico\":13}', '127.0.0.1', '2026-04-19 21:00:31', '1'),
(179, 1, 'Evento', 'evento', '2', 'CREAR', NULL, '{\"id_calendario\":13,\"dia_inicio_evento\":\"2026-04-01\",\"dia_fin_evento\":\"2026-04-09\",\"descripcion_evento\":\"pop\",\"tipo_evento\":\"Cierre de Notas\",\"fecha_creacion\":\"2026-04-19T17:00:31.658766Z\",\"estatus\":\"1\",\"id_evento\":2}', '127.0.0.1', '2026-04-19 21:00:31', '1'),
(180, 1, 'Evento', 'evento', '3', 'CREAR', NULL, '{\"id_calendario\":13,\"dia_inicio_evento\":\"2026-05-15\",\"dia_fin_evento\":\"2026-05-15\",\"descripcion_evento\":\"ki\",\"tipo_evento\":\"Otro\",\"fecha_creacion\":\"2026-04-19T17:00:31.667185Z\",\"estatus\":\"1\",\"id_evento\":3}', '127.0.0.1', '2026-04-19 21:00:31', '1'),
(181, 0, 'Seguridad', 'users', '43325', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-04-19 21:00:33', '1');

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
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario_academico`
--

CREATE TABLE `calendario_academico` (
  `id_calendario_academico` int(11) NOT NULL,
  `semana_calendario_academico` int(11) DEFAULT NULL,
  `dia_inicio_calendario_academico` date DEFAULT NULL,
  `dia_fin_calendario_academico` date DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calendario_academico`
--

INSERT INTO `calendario_academico` (`id_calendario_academico`, `semana_calendario_academico`, `dia_inicio_calendario_academico`, `dia_fin_calendario_academico`, `fecha_creacion`, `estatus`) VALUES
(12, 9, '2026-04-01', '2026-05-31', '2026-04-19 20:55:42', '3'),
(13, 13, '2026-04-01', '2026-06-30', '2026-04-19 21:00:31', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido`
--

CREATE TABLE `contenido` (
  `id_contenido` int(11) NOT NULL,
  `titulo_contenido` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contenido`
--

INSERT INTO `contenido` (`id_contenido`, `titulo_contenido`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 'kfhfihfajiaiiad', '2026-04-18 00:07:49', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_bibliografia`
--

CREATE TABLE `detalle_bibliografia` (
  `id_detalle_bibliografia` int(11) NOT NULL,
  `id_unidad_corte` int(11) DEFAULT NULL,
  `id_bibliografia` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3','4') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_objetivo`
--

CREATE TABLE `detalle_objetivo` (
  `id_detalle_objetivo` int(11) NOT NULL,
  `id_contenido` int(11) DEFAULT NULL,
  `id_objetivo` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_objetivo`
--

INSERT INTO `detalle_objetivo` (`id_detalle_objetivo`, `id_contenido`, `id_objetivo`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 1, 1, '2026-04-18 00:07:49', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_recurso`
--

CREATE TABLE `detalle_recurso` (
  `id_detalle_recurso` int(11) NOT NULL,
  `id_recurso` int(11) DEFAULT NULL,
  `id_unidad_corte` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL,
  `id_calendario` int(11) DEFAULT NULL,
  `dia_inicio_evento` date DEFAULT NULL,
  `dia_fin_evento` date DEFAULT NULL,
  `semana_evento` int(11) DEFAULT NULL,
  `descripcion_evento` varchar(100) DEFAULT NULL,
  `tipo_evento` enum('1','2','3') DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id_evento`, `id_calendario`, `dia_inicio_evento`, `dia_fin_evento`, `semana_evento`, `descripcion_evento`, `tipo_evento`, `fecha_creacion`, `estatus`) VALUES
(2, 13, '2026-04-01', '2026-04-09', NULL, 'pop', '', '2026-04-19 21:00:31', '1'),
(3, 13, '2026-05-15', '2026-05-15', NULL, 'ki', '', '2026-04-19 21:00:31', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instrumento`
--

CREATE TABLE `instrumento` (
  `id_instrumento` int(11) NOT NULL,
  `nombre_instrumento` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetivo`
--

CREATE TABLE `objetivo` (
  `id_objetivo` int(11) NOT NULL,
  `titulo_objetivo` varchar(255) DEFAULT NULL,
  `id_tema_unidad` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetivo`
--

INSERT INTO `objetivo` (`id_objetivo`, `titulo_objetivo`, `id_tema_unidad`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 'asdasidjiasjd', 1, '2026-04-18 00:07:30', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` tinytext DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `nombre_permiso`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 'Index de Perfil', '2026-03-24 04:55:38', NULL, '1'),
(2, 'Index de Seleccionar Rol', '2026-03-24 04:55:38', NULL, '1'),
(3, 'Listar de Contenido', '2026-03-24 04:55:38', NULL, '1'),
(4, 'Crear de Contenido', '2026-03-24 04:55:38', NULL, '1'),
(5, 'Editar de Contenido', '2026-03-24 04:55:38', NULL, '1'),
(6, 'Ver Detalles de Contenido', '2026-03-24 04:55:38', NULL, '1'),
(7, 'Listar de Tema', '2026-03-24 04:55:38', NULL, '1'),
(8, 'Crear de Tema', '2026-03-24 04:55:38', NULL, '1'),
(9, 'Editar de Tema', '2026-03-24 04:55:38', NULL, '1'),
(10, 'Ver Detalles de Tema', '2026-03-24 04:55:38', NULL, '1'),
(11, 'Crear de Usuarios', '2026-03-24 04:55:38', NULL, '1'),
(12, 'Listar de Usuarios', '2026-03-24 04:55:38', NULL, '1'),
(13, 'Listar de Planificacion', '2026-03-24 04:55:38', NULL, '1'),
(14, 'Crear de Planificacion', '2026-03-24 04:55:38', NULL, '1'),
(15, 'Editar de Planificacion', '2026-03-24 04:55:38', NULL, '1'),
(16, 'Ver Detalles de Planificacion', '2026-03-24 04:55:38', NULL, '1'),
(17, 'Reporte General de Planificacion', '2026-03-24 04:55:38', NULL, '1'),
(18, 'Reporte Detallado de Planificacion', '2026-03-24 04:55:38', NULL, '1'),
(19, 'Reporte de Calendario', '2026-03-24 04:55:38', NULL, '1'),
(20, 'Listar de Indicador Logro', '2026-03-24 04:55:38', NULL, '1'),
(21, 'Crear de Indicador Logro', '2026-03-24 04:55:38', NULL, '1'),
(22, 'Editar de Indicador Logro', '2026-03-24 04:55:38', NULL, '1'),
(23, 'Ver Detalles de Indicador Logro', '2026-03-24 04:55:38', NULL, '1'),
(24, 'Listar de Bibliografia', '2026-03-24 04:55:38', NULL, '1'),
(25, 'Crear de Bibliografia', '2026-03-24 04:55:38', NULL, '1'),
(26, 'Editar de Bibliografia', '2026-03-24 04:55:38', NULL, '1'),
(27, 'Ver Detalles de Bibliografia', '2026-03-24 04:55:38', NULL, '1'),
(28, 'Listar de Recurso', '2026-03-24 04:55:38', NULL, '1'),
(29, 'Crear de Recurso', '2026-03-24 04:55:38', NULL, '1'),
(30, 'Editar de Recurso', '2026-03-24 04:55:38', NULL, '1'),
(31, 'Ver Detalles de Recurso', '2026-03-24 04:55:38', NULL, '1'),
(32, 'Listar de Estrategia', '2026-03-24 04:55:38', NULL, '1'),
(33, 'Crear de Estrategia', '2026-03-24 04:55:38', NULL, '1'),
(34, 'Editar de Estrategia', '2026-03-24 04:55:38', NULL, '1'),
(35, 'Ver Detalles de Estrategia', '2026-03-24 04:55:38', NULL, '1'),
(36, 'Listar de Tecnica Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(37, 'Crear de Tecnica Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(38, 'Editar de Tecnica Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(39, 'Ver Detalles de Tecnica Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(40, 'Listar de Tipo Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(41, 'Crear de Tipo Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(42, 'Editar de Tipo Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(43, 'Ver Detalles de Tipo Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(44, 'Listar de Evento', '2026-03-24 04:55:38', NULL, '1'),
(45, 'Crear de Evento', '2026-03-24 04:55:38', NULL, '1'),
(46, 'Editar de Evento', '2026-03-24 04:55:38', NULL, '1'),
(47, 'Ver Detalles de Evento', '2026-03-24 04:55:38', NULL, '1'),
(48, 'Listar de Calendario', '2026-03-24 04:55:38', NULL, '1'),
(49, 'Crear de Calendario', '2026-03-24 04:55:38', NULL, '1'),
(50, 'Editar de Calendario', '2026-03-24 04:55:38', NULL, '1'),
(51, 'Ver Detalles de Calendario', '2026-03-24 04:55:38', NULL, '1'),
(52, 'Listar de Rol', '2026-03-24 04:55:38', '2026-04-15 18:18:35', '3'),
(53, 'Editar de Rol', '2026-03-24 04:55:38', '2026-04-15 18:18:35', '3'),
(54, 'Listar de Bitacora', '2026-03-24 04:55:38', NULL, '1'),
(55, 'Ver Detalles de Bitacora', '2026-03-24 04:55:38', NULL, '1'),
(56, 'Cambiar Estatus de Perfil', '2026-03-24 04:55:38', NULL, '1'),
(57, 'Cambiar Estatus de Seleccionar Rol', '2026-03-24 04:55:38', NULL, '1'),
(58, 'Cambiar Estatus de Contenido', '2026-03-24 04:55:38', NULL, '1'),
(59, 'Cambiar Estatus de Tema', '2026-03-24 04:55:38', NULL, '1'),
(60, 'Cambiar Estatus de Usuarios', '2026-03-24 04:55:38', NULL, '1'),
(61, 'Cambiar Estatus de Planificacion', '2026-03-24 04:55:38', NULL, '1'),
(62, 'Cambiar Estatus de Calendario', '2026-03-24 04:55:38', NULL, '1'),
(63, 'Cambiar Estatus de Indicador Logro', '2026-03-24 04:55:38', NULL, '1'),
(64, 'Cambiar Estatus de Bibliografia', '2026-03-24 04:55:38', NULL, '1'),
(65, 'Cambiar Estatus de Recurso', '2026-03-24 04:55:38', NULL, '1'),
(66, 'Cambiar Estatus de Estrategia', '2026-03-24 04:55:38', NULL, '1'),
(67, 'Cambiar Estatus de Tecnica Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(68, 'Cambiar Estatus de Tipo Evaluacion', '2026-03-24 04:55:38', NULL, '1'),
(69, 'Cambiar Estatus de Evento', '2026-03-24 04:55:38', NULL, '1'),
(70, 'Cambiar Estatus de Rol', '2026-03-24 04:55:38', '2026-04-15 18:18:35', '3'),
(71, 'Cambiar Estatus de Bitacora', '2026-03-24 04:55:38', NULL, '1'),
(72, 'Listar de Permiso', '2026-04-15 22:18:35', NULL, '1'),
(73, 'Editar de Permiso', '2026-04-15 22:18:35', NULL, '1'),
(74, 'Cambiar Estatus de Permiso', '2026-04-15 22:18:35', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planificacion`
--

CREATE TABLE `planificacion` (
  `id_planificacion` int(11) NOT NULL,
  `id_profesor_asignado` int(11) DEFAULT NULL,
  `aceptado_vocero` int(11) DEFAULT NULL,
  `aceptado_coordinador` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3','4') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recurso`
--

CREATE TABLE `recurso` (
  `id_recurso` int(11) NOT NULL,
  `nombre_recurso` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recurso`
--

INSERT INTO `recurso` (`id_recurso`, `nombre_recurso`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 'dsdd', '2026-04-07 16:01:56', '2026-04-07 16:01:56', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol_permiso` int(11) NOT NULL,
  `id_permiso` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id_rol_permiso`, `id_permiso`, `id_rol`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 69, 3, '2026-03-24 04:58:45', '2026-04-15 19:29:02', '1'),
(2, 45, 3, '2026-03-24 04:58:45', '2026-04-15 19:29:02', '1'),
(3, 46, 3, '2026-03-24 04:58:45', '2026-04-15 19:29:02', '1'),
(4, 44, 3, '2026-03-24 04:58:45', '2026-04-15 19:29:02', '1'),
(5, 47, 3, '2026-03-24 04:58:45', '2026-04-15 19:29:02', '1'),
(6, 61, 3, '2026-03-24 04:58:45', NULL, '1'),
(7, 14, 3, '2026-03-24 04:58:45', NULL, '1'),
(8, 15, 3, '2026-03-24 04:58:45', NULL, '1'),
(9, 13, 3, '2026-03-24 04:58:45', NULL, '1'),
(10, 18, 3, '2026-03-24 04:58:45', NULL, '1'),
(11, 17, 3, '2026-03-24 04:58:45', NULL, '1'),
(12, 16, 3, '2026-03-24 04:58:45', NULL, '1'),
(13, 62, 3, '2026-03-24 04:58:45', '2026-04-07 15:36:33', '3'),
(14, 49, 3, '2026-03-24 04:58:45', '2026-04-07 15:36:33', '3'),
(15, 50, 3, '2026-03-24 04:58:45', '2026-04-07 15:36:33', '3'),
(16, 48, 3, '2026-03-24 04:58:45', '2026-04-07 15:36:33', '3'),
(17, 19, 3, '2026-03-24 04:58:45', '2026-04-07 15:36:33', '3'),
(18, 51, 3, '2026-03-24 04:58:45', '2026-04-07 15:36:33', '3'),
(19, 61, 4, '2026-03-25 05:30:31', '2026-04-11 02:57:37', '1'),
(20, 14, 4, '2026-03-25 05:30:31', '2026-04-11 02:57:37', '1'),
(21, 15, 4, '2026-03-25 05:30:31', '2026-04-11 02:57:37', '1'),
(22, 13, 4, '2026-03-25 05:30:31', NULL, '1'),
(23, 18, 4, '2026-03-25 05:30:31', '2026-04-11 02:57:37', '1'),
(24, 17, 4, '2026-03-25 05:30:31', '2026-04-11 02:57:37', '1'),
(25, 16, 4, '2026-03-25 05:30:31', NULL, '1'),
(26, 62, 4, '2026-03-28 15:50:50', NULL, '1'),
(27, 49, 4, '2026-03-28 15:50:50', NULL, '1'),
(28, 50, 4, '2026-03-28 15:50:50', NULL, '1'),
(29, 48, 4, '2026-03-28 15:50:50', NULL, '1'),
(30, 19, 4, '2026-03-28 15:50:50', NULL, '1'),
(31, 51, 4, '2026-03-28 15:50:50', NULL, '1'),
(32, 66, 3, '2026-04-07 15:38:53', NULL, '1'),
(33, 33, 3, '2026-04-07 15:38:53', NULL, '1'),
(34, 34, 3, '2026-04-07 15:38:53', NULL, '1'),
(35, 32, 3, '2026-04-07 15:38:53', NULL, '1'),
(36, 35, 3, '2026-04-07 15:38:53', NULL, '1'),
(37, 29, 3, '2026-04-07 15:38:53', NULL, '1'),
(38, 30, 3, '2026-04-07 15:38:53', NULL, '1'),
(39, 28, 3, '2026-04-07 15:38:53', NULL, '1'),
(40, 65, 3, '2026-04-07 15:38:53', NULL, '1'),
(41, 31, 3, '2026-04-07 15:38:53', NULL, '1'),
(42, 67, 3, '2026-04-07 15:38:53', NULL, '1'),
(43, 37, 3, '2026-04-07 15:38:53', NULL, '1'),
(44, 38, 3, '2026-04-07 15:38:53', NULL, '1'),
(45, 36, 3, '2026-04-07 15:38:53', NULL, '1'),
(46, 39, 3, '2026-04-07 15:38:53', NULL, '1'),
(47, 68, 3, '2026-04-07 15:38:53', NULL, '1'),
(48, 41, 3, '2026-04-07 15:38:53', NULL, '1'),
(49, 42, 3, '2026-04-07 15:38:53', NULL, '1'),
(50, 40, 3, '2026-04-07 15:38:53', NULL, '1'),
(51, 43, 3, '2026-04-07 15:38:53', NULL, '1'),
(52, 16, 11, '2026-04-07 15:40:35', NULL, '1'),
(53, 13, 11, '2026-04-07 15:40:35', NULL, '1'),
(54, 59, 11, '2026-04-07 15:49:15', NULL, '1'),
(55, 8, 11, '2026-04-07 15:49:15', NULL, '1'),
(56, 9, 11, '2026-04-07 15:49:15', NULL, '1'),
(57, 7, 11, '2026-04-07 15:49:15', NULL, '1'),
(58, 10, 11, '2026-04-07 15:49:15', NULL, '1'),
(59, 62, 11, '2026-04-08 01:12:40', NULL, '1'),
(60, 49, 11, '2026-04-08 01:12:40', NULL, '1'),
(61, 50, 11, '2026-04-08 01:12:40', NULL, '1'),
(62, 48, 11, '2026-04-08 01:12:40', NULL, '1'),
(63, 19, 11, '2026-04-08 01:12:40', NULL, '1'),
(64, 51, 11, '2026-04-08 01:12:40', NULL, '1'),
(65, 58, 11, '2026-04-18 00:07:08', NULL, '1'),
(66, 4, 11, '2026-04-18 00:07:08', NULL, '1'),
(67, 5, 11, '2026-04-18 00:07:08', NULL, '1'),
(68, 3, 11, '2026-04-18 00:07:08', NULL, '1'),
(69, 6, 11, '2026-04-18 00:07:08', NULL, '1'),
(70, 69, 11, '2026-04-19 20:57:03', NULL, '1'),
(71, 45, 11, '2026-04-19 20:57:03', NULL, '1'),
(72, 46, 11, '2026-04-19 20:57:03', NULL, '1'),
(73, 44, 11, '2026-04-19 20:57:03', NULL, '1'),
(74, 47, 11, '2026-04-19 20:57:03', NULL, '1');

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
('2Sb0OoMeRGLYhbGv9tsObmWmR2dXgXMR98twyPBT', 43325, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWW1nQXFxNExRODRpczhrc3pCQmRVVzE1NHBHUU9MTGJVTVQ4NGRLYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ldmVudG8vbGlzdCI7fXM6MTE6ImFjdGl2ZV9yb2xlIjtpOjExO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQzMzI1O30=', 1776618074);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnica_actividad`
--

CREATE TABLE `tecnica_actividad` (
  `id_tecnica_actividad` int(11) NOT NULL,
  `nombre_tecnica_actividad` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnica_actividad`
--

INSERT INTO `tecnica_actividad` (`id_tecnica_actividad`, `nombre_tecnica_actividad`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 'pppppppppp', '2026-04-18 00:14:28', '2026-04-18 00:14:28', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnica_evaluacion`
--

CREATE TABLE `tecnica_evaluacion` (
  `id_tecnica_evaluacion` int(11) NOT NULL,
  `nombre_tecnica_evaluacion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tema_unidad`
--

CREATE TABLE `tema_unidad` (
  `id_tema_unidad` int(11) NOT NULL,
  `id_unidad_curricular` varchar(7) DEFAULT NULL,
  `titulo_tema` text DEFAULT NULL,
  `unidad_tema` enum('1','2','3','4') DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tema_unidad`
--

INSERT INTO `tema_unidad` (`id_tema_unidad`, `id_unidad_curricular`, `titulo_tema`, `unidad_tema`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, '2', 'ahasdhais', '1', '2026-04-18 00:07:30', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_evaluacion`
--

CREATE TABLE `tipo_evaluacion` (
  `id_tipo_evaluacion` int(11) NOT NULL,
  `nombre_tipo_evaluacion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `calendario_academico`
--
ALTER TABLE `calendario_academico`
  ADD PRIMARY KEY (`id_calendario_academico`);

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
  ADD KEY `fk_detbibliografia_unidadcorte` (`id_unidad_corte`),
  ADD KEY `fk_detbibliografia_bibliografia` (`id_bibliografia`);

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
  ADD KEY `fk_deteval_instrumento` (`id_instrumento`),
  ADD KEY `fk_deteval_unidadcorte` (`id_unidad_corte`);

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
  ADD KEY `fk_evento_calendario` (`id_calendario`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id_bibliografia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT de la tabla `calendario_academico`
--
ALTER TABLE `calendario_academico`
  MODIFY `id_calendario_academico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `contenido`
--
ALTER TABLE `contenido`
  MODIFY `id_contenido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT de la tabla `detalle_objetivo`
--
ALTER TABLE `detalle_objetivo`
  MODIFY `id_detalle_objetivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_recurso`
--
ALTER TABLE `detalle_recurso`
  MODIFY `id_detalle_recurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `objetivo`
--
ALTER TABLE `objetivo`
  MODIFY `id_objetivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

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
  MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `tecnica_actividad`
--
ALTER TABLE `tecnica_actividad`
  MODIFY `id_tecnica_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tecnica_evaluacion`
--
ALTER TABLE `tecnica_evaluacion`
  MODIFY `id_tecnica_evaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tema_unidad`
--
ALTER TABLE `tema_unidad`
  MODIFY `id_tema_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_evaluacion`
--
ALTER TABLE `tipo_evaluacion`
  MODIFY `id_tipo_evaluacion` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_evento_calendario` FOREIGN KEY (`id_calendario`) REFERENCES `calendario_academico` (`id_calendario_academico`);

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
