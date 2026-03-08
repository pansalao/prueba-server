-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-03-2026 a las 02:11:24
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
-- Base de datos: `hp_10`
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

--
-- Volcado de datos para la tabla `bibliografia`
--

INSERT INTO `bibliografia` (`id_bibliografia`, `nombre_bibliografia`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 'pppppppppp', '2026-03-08 05:02:11', '2026-03-08 05:03:28', '3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_bitacora` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL COMMENT 'Clave foránea de sogac:',
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
(1, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-07 16:27:41', '1'),
(2, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-07 16:39:43', '1'),
(3, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-07 17:40:36', '1'),
(4, 39195, 'Roles (Permisos)', 'rol_permiso', '1', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"53\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:01.092528Z\",\"id_rol_permiso\":1}', '127.0.0.1', '2026-03-07 17:50:01', '1'),
(5, 39195, 'Roles (Permisos)', 'rol_permiso', '2', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"18\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:01.103908Z\",\"id_rol_permiso\":2}', '127.0.0.1', '2026-03-07 17:50:01', '1'),
(6, 39195, 'Roles (Permisos)', 'rol_permiso', '3', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"19\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:01.111350Z\",\"id_rol_permiso\":3}', '127.0.0.1', '2026-03-07 17:50:01', '1'),
(7, 39195, 'Roles (Permisos)', 'rol_permiso', '4', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"17\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:01.113733Z\",\"id_rol_permiso\":4}', '127.0.0.1', '2026-03-07 17:50:01', '1'),
(8, 39195, 'Roles (Permisos)', 'rol_permiso', '5', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"20\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:01.116056Z\",\"id_rol_permiso\":5}', '127.0.0.1', '2026-03-07 17:50:01', '1'),
(9, 39195, 'Roles (Permisos)', 'rol_permiso', '6', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"54\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:26.425117Z\",\"id_rol_permiso\":6}', '127.0.0.1', '2026-03-07 17:50:26', '1'),
(10, 39195, 'Roles (Permisos)', 'rol_permiso', '7', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"22\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:26.428645Z\",\"id_rol_permiso\":7}', '127.0.0.1', '2026-03-07 17:50:26', '1'),
(11, 39195, 'Roles (Permisos)', 'rol_permiso', '8', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"23\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:26.431360Z\",\"id_rol_permiso\":8}', '127.0.0.1', '2026-03-07 17:50:26', '1'),
(12, 39195, 'Roles (Permisos)', 'rol_permiso', '9', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"21\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:26.440013Z\",\"id_rol_permiso\":9}', '127.0.0.1', '2026-03-07 17:50:26', '1'),
(13, 39195, 'Roles (Permisos)', 'rol_permiso', '10', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"24\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T13:50:26.442491Z\",\"id_rol_permiso\":10}', '127.0.0.1', '2026-03-07 17:50:26', '1'),
(14, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-07 19:26:21', '1'),
(15, 39195, 'Roles (Permisos)', 'rol_permiso', '11', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"47\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T15:53:33.297376Z\",\"id_rol_permiso\":11}', '127.0.0.1', '2026-03-07 19:53:33', '1'),
(16, 39195, 'Roles (Permisos)', 'rol_permiso', '12', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"48\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-07T15:53:33.326600Z\",\"id_rol_permiso\":12}', '127.0.0.1', '2026-03-07 19:53:33', '1'),
(17, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-03-07 20:44:07', '1'),
(18, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-07 22:51:07', '1'),
(19, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-03-07 22:51:13', '1'),
(20, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-07 23:57:03', '1'),
(21, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-03-07 23:57:17', '1'),
(22, 0, 'Seguridad', 'users', '39195', 'LOGIN', NULL, NULL, '127.0.0.1', '2026-03-08 04:54:45', '1'),
(23, 39195, 'Bibliografia', 'bibliografia', '1', 'CREAR', NULL, '{\"nombre_bibliografia\":\"pppppppppp\",\"fecha_creacion\":\"2026-03-08T01:02:11.678935Z\",\"estatus\":\"1\",\"id_bibliografia\":1}', '127.0.0.1', '2026-03-08 05:02:11', '1'),
(24, 39195, 'Roles (Permisos)', 'rol_permiso', '8', 'MODIFICAR', '{\"id_rol_permiso\":8,\"id_permiso\":23,\"id_rol\":4,\"fecha_creacion\":\"2026-03-07 13:50:26\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-03-08T01:02:52.525083Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-03-08 05:02:52', '1'),
(25, 39195, 'Bibliografia', 'bibliografia', '1', 'MODIFICAR', '{\"id_bibliografia\":1,\"nombre_bibliografia\":\"pppppppppp\",\"fecha_creacion\":\"2026-03-08 01:02:11\",\"fecha_actualizacion\":null,\"estatus\":\"1\"}', '{\"fecha_actualizacion\":\"2026-03-08T01:03:28.998982Z\",\"estatus\":\"3\"}', '127.0.0.1', '2026-03-08 05:03:29', '1'),
(26, 39195, 'Roles (Permisos)', 'rol_permiso', '13', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"50\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:03:48.989413Z\",\"id_rol_permiso\":13}', '127.0.0.1', '2026-03-08 05:03:48', '1'),
(27, 39195, 'Roles (Permisos)', 'rol_permiso', '14', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"8\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:03:48.995012Z\",\"id_rol_permiso\":14}', '127.0.0.1', '2026-03-08 05:03:48', '1'),
(28, 39195, 'Roles (Permisos)', 'rol_permiso', '15', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"6\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:03:49.001604Z\",\"id_rol_permiso\":15}', '127.0.0.1', '2026-03-08 05:03:49', '1'),
(29, 39195, 'Roles (Permisos)', 'rol_permiso', '16', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"7\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:03:49.003834Z\",\"id_rol_permiso\":16}', '127.0.0.1', '2026-03-08 05:03:49', '1'),
(30, 39195, 'Roles (Permisos)', 'rol_permiso', '17', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"5\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:03:49.010865Z\",\"id_rol_permiso\":17}', '127.0.0.1', '2026-03-08 05:03:49', '1'),
(31, 39195, 'Tema', 'tema_unidad', '1', 'CREAR', NULL, '{\"id_unidad_curricular\":\"241\",\"titulo_tema\":\"prueba\",\"unidad_tema\":\"2\",\"fecha_creacion\":\"2026-03-08T01:04:18.160348Z\",\"fecha_actualizacion\":null,\"estatus\":\"1\",\"id_tema_unidad\":1}', '127.0.0.1', '2026-03-08 05:04:18', '1'),
(32, 39195, 'Roles (Permisos)', 'rol_permiso', '18', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"49\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:04:29.888643Z\",\"id_rol_permiso\":18}', '127.0.0.1', '2026-03-08 05:04:29', '1'),
(33, 39195, 'Roles (Permisos)', 'rol_permiso', '19', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"2\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:04:29.891691Z\",\"id_rol_permiso\":19}', '127.0.0.1', '2026-03-08 05:04:29', '1'),
(34, 39195, 'Roles (Permisos)', 'rol_permiso', '20', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"3\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:04:29.893659Z\",\"id_rol_permiso\":20}', '127.0.0.1', '2026-03-08 05:04:29', '1'),
(35, 39195, 'Roles (Permisos)', 'rol_permiso', '21', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"1\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:04:29.900814Z\",\"id_rol_permiso\":21}', '127.0.0.1', '2026-03-08 05:04:29', '1'),
(36, 39195, 'Roles (Permisos)', 'rol_permiso', '22', 'CREAR', NULL, '{\"id_rol\":\"4\",\"id_permiso\":\"4\",\"estatus\":\"1\",\"fecha_creacion\":\"2026-03-08T01:04:29.907710Z\",\"id_rol_permiso\":22}', '127.0.0.1', '2026-03-08 05:04:29', '1'),
(37, 39195, 'Contenido', 'contenido', '1', 'CREAR', NULL, '{\"titulo_contenido\":\"asjdsd \",\"fecha_creacion\":\"2026-03-08T01:04:56.068689Z\",\"estatus\":\"1\",\"id_contenido\":1}', '127.0.0.1', '2026-03-08 05:04:56', '1'),
(38, 0, 'Seguridad', 'users', '39195', 'LOGOUT', NULL, NULL, '127.0.0.1', '2026-03-08 05:09:08', '1');

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
(1, 'asjdsd ', '2026-03-08 05:04:56', NULL, '1');

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
(1, 1, 1, '2026-03-08 05:04:56', NULL, '1');

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
  `id_lapso` int(11) DEFAULT NULL COMMENT 'Clave foránea de sogac:',
  `dia_inicio_evento` date DEFAULT NULL,
  `dia_fin_evento` date DEFAULT NULL,
  `descripcion_evento` varchar(100) DEFAULT NULL,
  `tipo_evento` enum('1','2','3') DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 2),
(3, '2026_02_12_181809_create_sessions_table', 3);

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
(1, 'ppop', 1, '2026-03-08 05:04:18', NULL, '1');

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
(1, 'Listar de Contenido', '2026-03-07 16:39:52', NULL, '1'),
(2, 'Crear de Contenido', '2026-03-07 16:39:52', NULL, '1'),
(3, 'Editar de Contenido', '2026-03-07 16:39:52', NULL, '1'),
(4, 'Ver Detalles de Contenido', '2026-03-07 16:39:52', NULL, '1'),
(5, 'Listar de Tema', '2026-03-07 16:39:52', NULL, '1'),
(6, 'Crear de Tema', '2026-03-07 16:39:52', NULL, '1'),
(7, 'Editar de Tema', '2026-03-07 16:39:52', NULL, '1'),
(8, 'Ver Detalles de Tema', '2026-03-07 16:39:52', NULL, '1'),
(9, 'Crear de Usuarios', '2026-03-07 16:39:52', NULL, '1'),
(10, 'Listar de Usuarios', '2026-03-07 16:39:52', NULL, '1'),
(11, 'Listar de Planificacion', '2026-03-07 16:39:52', NULL, '1'),
(12, 'Crear de Planificacion', '2026-03-07 16:39:52', NULL, '1'),
(13, 'Editar de Planificacion', '2026-03-07 16:39:52', NULL, '1'),
(14, 'Ver Detalles de Planificacion', '2026-03-07 16:39:52', NULL, '1'),
(15, 'Reporte General de Planificacion', '2026-03-07 16:39:52', NULL, '1'),
(16, 'Reporte Detallado de Planificacion', '2026-03-07 16:39:52', NULL, '1'),
(17, 'Listar de Indicador Logro', '2026-03-07 16:39:52', NULL, '1'),
(18, 'Crear de Indicador Logro', '2026-03-07 16:39:52', NULL, '1'),
(19, 'Editar de Indicador Logro', '2026-03-07 16:39:52', NULL, '1'),
(20, 'Ver Detalles de Indicador Logro', '2026-03-07 16:39:52', NULL, '1'),
(21, 'Listar de Bibliografia', '2026-03-07 16:39:52', NULL, '1'),
(22, 'Crear de Bibliografia', '2026-03-07 16:39:52', NULL, '1'),
(23, 'Editar de Bibliografia', '2026-03-07 16:39:52', NULL, '1'),
(24, 'Ver Detalles de Bibliografia', '2026-03-07 16:39:52', NULL, '1'),
(25, 'Listar de Recurso', '2026-03-07 16:39:52', NULL, '1'),
(26, 'Crear de Recurso', '2026-03-07 16:39:52', NULL, '1'),
(27, 'Editar de Recurso', '2026-03-07 16:39:52', NULL, '1'),
(28, 'Ver Detalles de Recurso', '2026-03-07 16:39:52', NULL, '1'),
(29, 'Listar de Estrategia', '2026-03-07 16:39:52', NULL, '1'),
(30, 'Crear de Estrategia', '2026-03-07 16:39:52', NULL, '1'),
(31, 'Editar de Estrategia', '2026-03-07 16:39:52', NULL, '1'),
(32, 'Ver Detalles de Estrategia', '2026-03-07 16:39:52', NULL, '1'),
(33, 'Listar de Tecnica Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(34, 'Crear de Tecnica Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(35, 'Editar de Tecnica Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(36, 'Ver Detalles de Tecnica Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(37, 'Listar de Tipo Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(38, 'Crear de Tipo Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(39, 'Editar de Tipo Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(40, 'Ver Detalles de Tipo Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(41, 'Listar de Evento', '2026-03-07 16:39:52', NULL, '1'),
(42, 'Crear de Evento', '2026-03-07 16:39:52', NULL, '1'),
(43, 'Editar de Evento', '2026-03-07 16:39:52', NULL, '1'),
(44, 'Ver Detalles de Evento', '2026-03-07 16:39:52', NULL, '1'),
(45, 'Listar de Rol', '2026-03-07 16:39:52', NULL, '1'),
(46, 'Editar de Rol', '2026-03-07 16:39:52', NULL, '1'),
(47, 'Listar de Bitacora', '2026-03-07 16:39:52', NULL, '1'),
(48, 'Ver Detalles de Bitacora', '2026-03-07 16:39:52', NULL, '1'),
(49, 'Cambiar Estatus de Contenido', '2026-03-07 16:39:52', NULL, '1'),
(50, 'Cambiar Estatus de Tema', '2026-03-07 16:39:52', NULL, '1'),
(51, 'Cambiar Estatus de Usuarios', '2026-03-07 16:39:52', NULL, '1'),
(52, 'Cambiar Estatus de Planificacion', '2026-03-07 16:39:52', NULL, '1'),
(53, 'Cambiar Estatus de Indicador Logro', '2026-03-07 16:39:52', NULL, '1'),
(54, 'Cambiar Estatus de Bibliografia', '2026-03-07 16:39:52', NULL, '1'),
(55, 'Cambiar Estatus de Recurso', '2026-03-07 16:39:52', NULL, '1'),
(56, 'Cambiar Estatus de Estrategia', '2026-03-07 16:39:52', NULL, '1'),
(57, 'Cambiar Estatus de Tecnica Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(58, 'Cambiar Estatus de Tipo Evaluacion', '2026-03-07 16:39:52', NULL, '1'),
(59, 'Cambiar Estatus de Evento', '2026-03-07 16:39:52', NULL, '1'),
(60, 'Cambiar Estatus de Rol', '2026-03-07 16:39:52', NULL, '1'),
(61, 'Cambiar Estatus de Bitacora', '2026-03-07 16:39:52', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planificacion`
--

CREATE TABLE `planificacion` (
  `id_planificacion` int(11) NOT NULL,
  `id_profesor_asignado` int(11) DEFAULT NULL COMMENT 'Clave foránea de sogac:',
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol_permiso` int(11) NOT NULL,
  `id_permiso` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL COMMENT 'Clave foránea de sogac:',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `estatus` enum('1','2','3') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id_rol_permiso`, `id_permiso`, `id_rol`, `fecha_creacion`, `fecha_actualizacion`, `estatus`) VALUES
(1, 53, 4, '2026-03-07 17:50:01', NULL, '1'),
(2, 18, 4, '2026-03-07 17:50:01', NULL, '1'),
(3, 19, 4, '2026-03-07 17:50:01', NULL, '1'),
(4, 17, 4, '2026-03-07 17:50:01', NULL, '1'),
(5, 20, 4, '2026-03-07 17:50:01', NULL, '1'),
(6, 54, 4, '2026-03-07 17:50:26', NULL, '1'),
(7, 22, 4, '2026-03-07 17:50:26', NULL, '1'),
(8, 23, 4, '2026-03-07 17:50:26', '2026-03-08 05:02:52', '3'),
(9, 21, 4, '2026-03-07 17:50:26', NULL, '1'),
(10, 24, 4, '2026-03-07 17:50:26', NULL, '1'),
(11, 47, 4, '2026-03-07 19:53:33', NULL, '1'),
(12, 48, 4, '2026-03-07 19:53:33', NULL, '1'),
(13, 50, 4, '2026-03-08 05:03:48', NULL, '1'),
(14, 8, 4, '2026-03-08 05:03:48', NULL, '1'),
(15, 6, 4, '2026-03-08 05:03:49', NULL, '1'),
(16, 7, 4, '2026-03-08 05:03:49', NULL, '1'),
(17, 5, 4, '2026-03-08 05:03:49', NULL, '1'),
(18, 49, 4, '2026-03-08 05:04:29', NULL, '1'),
(19, 2, 4, '2026-03-08 05:04:29', NULL, '1'),
(20, 3, 4, '2026-03-08 05:04:29', NULL, '1'),
(21, 1, 4, '2026-03-08 05:04:29', NULL, '1'),
(22, 4, 4, '2026-03-08 05:04:29', NULL, '1');

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
('UANaZkh8cg2ZsqRn7hDFj0EexMTAz2vB2WaqpWw5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOXE0MTVKQmxrS3ljN1ZuQ2Q4NGNPVmdKeldQUWVLZG1OVUViQlFOOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO31zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZGFzaGJvYXJkIjt9fQ==', 1772932165),
('XsH5vZEmnadVpSJ90ahAudBsELkIpQXA5L4oSdi8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidUJWV09ieUpPdnRIN0U5QkZGa05GTGNSdGVmaFQyazBpZ0VmOVdpZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1772931950);

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
  `id_unidad_curricular` varchar(7) DEFAULT NULL COMMENT 'Clave foránea de sogac:',
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
(1, '241', 'prueba', '2', '2026-03-08 05:04:18', NULL, '1');

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
  ADD PRIMARY KEY (`id_evento`);

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
  MODIFY `id_bibliografia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `objetivo`
--
ALTER TABLE `objetivo`
  MODIFY `id_objetivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `planificacion`
--
ALTER TABLE `planificacion`
  MODIFY `id_planificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recurso`
--
ALTER TABLE `recurso`
  MODIFY `id_recurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `tecnica_actividad`
--
ALTER TABLE `tecnica_actividad`
  MODIFY `id_tecnica_actividad` int(11) NOT NULL AUTO_INCREMENT;

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
