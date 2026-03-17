-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-03-2026 a las 19:54:18
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
-- Base de datos: `emulacion_sogac_2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `est_codigo` char(2) NOT NULL,
  `est_nombre` varchar(40) NOT NULL,
  `est_estatus` char(1) DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `est_cedula` char(12) NOT NULL,
  `est_fecha_ingreso` date DEFAULT '2014-01-01',
  `est_privado_libertad` char(1) DEFAULT NULL,
  `est_becado` char(1) DEFAULT '2' COMMENT '1 -> Si, 2 -> No',
  `est_ira` decimal(10,2) DEFAULT NULL,
  `est_posicion_graduado_tsu` int(11) DEFAULT NULL,
  `est_posicion_graduado_ing` int(11) DEFAULT NULL,
  `est_bachiller_ano_egreso` char(4) DEFAULT NULL,
  `est_tsu_titulo` varchar(40) DEFAULT NULL,
  `est_tsu_instituto` varchar(150) DEFAULT NULL,
  `est_tsu_ano_egreso` char(4) DEFAULT NULL,
  `est_cod_modalidad_ingreso` int(11) NOT NULL,
  `est_cod_condicion_residencia` int(11) NOT NULL,
  `est_cod_sede` int(11) NOT NULL,
  `est_cod_turno` int(11) NOT NULL,
  `est_cod_malla_ingreso` int(11) NOT NULL,
  `est_cod_malla_egreso` int(11) DEFAULT NULL,
  `est_cod_localidad_bachillerato` char(10) DEFAULT NULL,
  `est_cod_seb` int(11) DEFAULT NULL,
  `est_cod_reb` int(11) DEFAULT NULL,
  `est_cod_mencion_honorifica` int(11) DEFAULT 4,
  `est_cod_promocion_tsu` int(11) DEFAULT 0,
  `est_cod_promocion_ing` int(11) DEFAULT NULL,
  `est_estatus` char(1) DEFAULT 'A',
  `est_cod_cohorte_sede_egreso` int(11) DEFAULT NULL,
  `est_cod_cohorte_sede_ingreso` int(11) DEFAULT NULL,
  `est_condicion_ingreso` char(1) DEFAULT 'B' COMMENT 'B: Bachiller, T: TSU',
  `est_condicion` char(1) NOT NULL DEFAULT 'A' COMMENT 'A: Activo, T: Retiro Temp, P: Perm, G: Grad, E: Espera',
  `est_cod_programa` int(11) DEFAULT NULL,
  `est_ultima_seccion` char(8) DEFAULT NULL,
  `est_cod_lapso_academico_ingreso` varchar(10) DEFAULT NULL,
  `est_cod_lapso_academico_egreso` varchar(10) DEFAULT NULL,
  `est_tsu_cod_dependencia` int(11) DEFAULT NULL,
  `est_bachiller_tipo_instituto` int(11) DEFAULT NULL,
  `est_cod_condicion_inscrito` int(11) DEFAULT NULL,
  `est_cod_mencion_honorifica_tsu` int(11) DEFAULT 4,
  `est_solvencia_bib` char(1) DEFAULT 'S',
  `est_cerrado` char(1) NOT NULL DEFAULT 'N',
  `est_condicion2` char(1) NOT NULL DEFAULT 'S',
  `est_aspirante` char(1) NOT NULL DEFAULT 'N',
  `est_trayecto` char(10) DEFAULT NULL,
  `est_semestre` char(10) DEFAULT NULL,
  `est_cod_condicion_inscrito_int` char(1) DEFAULT 'N',
  `est_solvencia_aca` char(1) NOT NULL DEFAULT 'N',
  `est_solvencia_adm` char(1) NOT NULL DEFAULT 'N',
  `est_tipo_aspirante` char(1) DEFAULT NULL,
  `est_culminacion_aca` char(1) DEFAULT 'N',
  `est_tipo_culminacion` char(1) DEFAULT 'N',
  `est_convalidado` char(1) DEFAULT 'N',
  `est_becado_interno` char(1) DEFAULT NULL,
  `est_organismo_beca` varchar(100) DEFAULT NULL,
  `est_sancion_universitaria` char(1) DEFAULT 'N',
  `est_cantidad_sancionado` int(11) DEFAULT 0,
  `est_observacion_sancion` text DEFAULT NULL,
  `est_sni` varchar(15) DEFAULT NULL,
  `est_confirmacion_aspirante` char(1) DEFAULT 'N',
  `est_fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `est_fecha_actualizacion` date DEFAULT '2017-11-22'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`est_cedula`, `est_fecha_ingreso`, `est_privado_libertad`, `est_becado`, `est_ira`, `est_posicion_graduado_tsu`, `est_posicion_graduado_ing`, `est_bachiller_ano_egreso`, `est_tsu_titulo`, `est_tsu_instituto`, `est_tsu_ano_egreso`, `est_cod_modalidad_ingreso`, `est_cod_condicion_residencia`, `est_cod_sede`, `est_cod_turno`, `est_cod_malla_ingreso`, `est_cod_malla_egreso`, `est_cod_localidad_bachillerato`, `est_cod_seb`, `est_cod_reb`, `est_cod_mencion_honorifica`, `est_cod_promocion_tsu`, `est_cod_promocion_ing`, `est_estatus`, `est_cod_cohorte_sede_egreso`, `est_cod_cohorte_sede_ingreso`, `est_condicion_ingreso`, `est_condicion`, `est_cod_programa`, `est_ultima_seccion`, `est_cod_lapso_academico_ingreso`, `est_cod_lapso_academico_egreso`, `est_tsu_cod_dependencia`, `est_bachiller_tipo_instituto`, `est_cod_condicion_inscrito`, `est_cod_mencion_honorifica_tsu`, `est_solvencia_bib`, `est_cerrado`, `est_condicion2`, `est_aspirante`, `est_trayecto`, `est_semestre`, `est_cod_condicion_inscrito_int`, `est_solvencia_aca`, `est_solvencia_adm`, `est_tipo_aspirante`, `est_culminacion_aca`, `est_tipo_culminacion`, `est_convalidado`, `est_becado_interno`, `est_organismo_beca`, `est_sancion_universitaria`, `est_cantidad_sancionado`, `est_observacion_sancion`, `est_sni`, `est_confirmacion_aspirante`, `est_fecha_registro`, `est_fecha_actualizacion`) VALUES
('31009367', '2023-01-12', '2', '1', 16.45, NULL, NULL, '2022', NULL, NULL, NULL, 1, 1, 1, 3, 25, 25, '180801001', 1, 1, 4, 0, 0, 'A', 445, 445, 'B', '', 4, '631', '2023-I', '2026-I', 0, 0, 1, 4, 'S', 'N', 'C', 'N', 'III', 'VI', 'N', 'N', 'N', NULL, 'S', 'T', 'N', NULL, NULL, 'N', 0, NULL, NULL, 'S', '2023-01-12 13:24:03', '2025-10-08'),
('31114131', '2023-01-11', '2', '2', 15.89, NULL, NULL, '2022', NULL, NULL, NULL, 1, 1, 1, 3, 25, 25, '180801001', 1, 1, 4, 0, 0, 'A', 445, 445, 'B', '', 4, '631', '2023-I', '2026-I', 0, 2, 1, 4, 'S', 'N', 'C', 'N', 'III', 'VI', 'N', 'N', 'N', NULL, 'S', 'T', 'N', NULL, NULL, 'N', 0, NULL, NULL, 'S', '2023-01-11 13:24:58', '2025-10-08'),
('31659136', '2023-01-15', '2', '2', 14.19, NULL, NULL, '2022', NULL, NULL, NULL, 8, 1, 1, 3, 25, 25, '180801001', 5, 10, 4, 0, 0, 'A', 445, 445, 'B', '', 4, '631', '2023-I', '2026-I', 0, 2, 1, 4, 'S', 'N', 'C', 'N', 'III', 'VI', 'N', 'N', 'N', NULL, 'S', 'T', 'N', NULL, NULL, 'N', 0, NULL, NULL, 'S', '2023-01-16 01:16:49', '2025-10-08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `ins_codigo` int(11) NOT NULL,
  `ins_cedula` char(12) NOT NULL,
  `ins_cod_seccion_unidad_docente` int(11) NOT NULL,
  `ins_nota_final_100` double DEFAULT NULL,
  `ins_nota_final_20` int(11) DEFAULT NULL,
  `ins_cod_condicion_inscrito` int(11) NOT NULL,
  `ins_estatus` char(1) DEFAULT 'A',
  `ins_nota_final_hito_100` int(11) DEFAULT 0,
  `ins_nota_final_hito_20` int(11) DEFAULT 0,
  `ins_observacion` text DEFAULT NULL,
  `ins_observacion_inactiva` text DEFAULT NULL,
  `ins_tipo` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`ins_codigo`, `ins_cedula`, `ins_cod_seccion_unidad_docente`, `ins_nota_final_100`, `ins_nota_final_20`, `ins_cod_condicion_inscrito`, `ins_estatus`, `ins_nota_final_hito_100`, `ins_nota_final_hito_20`, `ins_observacion`, `ins_observacion_inactiva`, `ins_tipo`) VALUES
(748762, '31009367', 60992, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(748763, '31009367', 60993, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(748764, '31009367', 60994, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(748765, '31009367', 60995, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(748766, '31009367', 60996, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(753341, '31659136', 60992, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(753342, '31659136', 60993, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(753343, '31659136', 60994, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(753344, '31659136', 60995, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(753345, '31659136', 60996, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(758551, '31114131', 60992, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(758552, '31114131', 60993, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(758553, '31114131', 60994, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(758554, '31114131', 60995, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N'),
(758555, '31114131', 60996, NULL, NULL, 0, 'A', 0, 0, NULL, NULL, 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lapso_academico`
--

CREATE TABLE `lapso_academico` (
  `lap_codigo` int(11) NOT NULL,
  `lap_nombre` varchar(15) DEFAULT NULL,
  `lap_fecha_inicio` date DEFAULT '2014-01-01',
  `lap_fecha_fin` date DEFAULT '2014-01-01',
  `lap_cod_tipo_lapso` int(11) NOT NULL,
  `lap_cod_universidad` int(11) NOT NULL,
  `lap_condicion` char(1) DEFAULT NULL,
  `lap_estatus` char(1) DEFAULT 'A',
  `lap_cerrado` char(1) NOT NULL DEFAULT 'N',
  `lap_nota` char(1) DEFAULT 'I'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lapso_academico`
--

INSERT INTO `lapso_academico` (`lap_codigo`, `lap_nombre`, `lap_fecha_inicio`, `lap_fecha_fin`, `lap_cod_tipo_lapso`, `lap_cod_universidad`, `lap_condicion`, `lap_estatus`, `lap_cerrado`, `lap_nota`) VALUES
(64, '2023-INT', '2023-08-01', '2023-09-21', 1, 1, NULL, 'A', 'S', 'I'),
(65, '2023-II', '2023-05-10', '2023-12-05', 1, 1, NULL, 'A', 'S', 'I'),
(66, '2024-I', '2024-01-15', '2024-06-28', 1, 1, NULL, 'A', 'S', 'I'),
(67, '2024-II', '2024-06-17', '2024-11-22', 1, 1, NULL, 'A', 'S', 'I'),
(68, '2024-INT', '2024-08-05', '2024-09-06', 1, 1, NULL, 'A', 'S', 'I'),
(69, '2025-I', '2025-02-10', '2025-06-27', 1, 1, NULL, 'A', 'S', 'I'),
(70, '2025-INT', '2025-07-07', '2025-09-26', 1, 1, NULL, 'A', 'S', 'I'),
(71, '2025-II', '2025-09-15', '2026-01-30', 1, 1, NULL, 'A', 'S', 'I'),
(72, '2026-I', '2026-03-02', '2026-07-14', 1, 1, NULL, 'A', 'N', 'I');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `malla`
--

CREATE TABLE `malla` (
  `mal_codigo` int(11) NOT NULL,
  `mal_nombre` varchar(20) NOT NULL,
  `mal_cod_programa` int(11) NOT NULL,
  `mal_cod_uce` int(11) DEFAULT NULL,
  `mal_cod_trayecto` int(11) DEFAULT NULL,
  `mal_estatus` char(1) DEFAULT 'A',
  `mal_total_credito_tsu` int(11) DEFAULT NULL,
  `mal_total_credito_ing` int(11) DEFAULT NULL,
  `mal_total_horas_tsu` int(11) DEFAULT 0,
  `mal_total_horas_ing` int(11) DEFAULT 0,
  `mal_total_unidades_tsu` int(11) DEFAULT 0,
  `mal_total_unidades_ing` int(11) DEFAULT 0,
  `mal_total_credito_prosecucion` int(11) DEFAULT 0,
  `mal_total_horas_prosecucion` int(11) DEFAULT 0,
  `mal_total_unidades_prosecucion` int(11) DEFAULT 0,
  `mal_cod_semestre_salida_tsu` int(11) DEFAULT 5,
  `mal_cod_semestre_salida_ing` int(11) DEFAULT 9
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `malla`
--

INSERT INTO `malla` (`mal_codigo`, `mal_nombre`, `mal_cod_programa`, `mal_cod_uce`, `mal_cod_trayecto`, `mal_estatus`, `mal_total_credito_tsu`, `mal_total_credito_ing`, `mal_total_horas_tsu`, `mal_total_horas_ing`, `mal_total_unidades_tsu`, `mal_total_unidades_ing`, `mal_total_credito_prosecucion`, `mal_total_horas_prosecucion`, `mal_total_unidades_prosecucion`, `mal_cod_semestre_salida_tsu`, `mal_cod_semestre_salida_ing`) VALUES
(25, 'Malla 25', 4, NULL, NULL, 'A', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 5, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `mun_codigo` char(4) NOT NULL,
  `mun_nombre` varchar(100) DEFAULT NULL,
  `mun_cod_estado` char(2) DEFAULT NULL,
  `mun_estatus` char(1) DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `pai_codigo` int(11) NOT NULL,
  `pai_nombre` varchar(70) DEFAULT NULL,
  `pai_estatus` char(1) DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`pai_codigo`, `pai_nombre`, `pai_estatus`) VALUES
(138, 'País Ficticio B (Cod: 138)', 'A'),
(296, 'País Ficticio A (Cod: 296)', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquia`
--

CREATE TABLE `parroquia` (
  `par_codigo` char(6) NOT NULL,
  `par_nombre` varchar(100) NOT NULL,
  `par_cod_municipio` char(4) DEFAULT NULL,
  `par_estatus` char(1) DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `per_cedula` char(12) NOT NULL,
  `per_documento` char(1) NOT NULL,
  `per_apellidos` varchar(50) NOT NULL,
  `per_nombres` varchar(50) NOT NULL,
  `per_genero` char(1) NOT NULL,
  `per_fecha_nacimiento` date DEFAULT '2014-01-01',
  `per_lugar_nacimiento` varchar(50) DEFAULT NULL,
  `per_discapacidad` char(1) NOT NULL,
  `per_tipo_discapacidad` varchar(30) DEFAULT NULL,
  `per_edo_civil` char(1) NOT NULL,
  `per_direccion` varchar(250) DEFAULT NULL,
  `per_telefono_fijo` varchar(12) DEFAULT NULL,
  `per_telefono_movil` varchar(12) DEFAULT NULL,
  `per_telefono_trabajo` varchar(12) DEFAULT NULL,
  `per_email` varchar(50) DEFAULT NULL,
  `per_foto` varchar(50) DEFAULT NULL,
  `per_cod_pais` int(11) NOT NULL,
  `per_cod_localidad_nacimiento` char(10) DEFAULT '0000000000',
  `per_cod_localidad_residencia` char(10) NOT NULL,
  `per_cod_etnia` int(11) NOT NULL,
  `per_estatus` char(1) DEFAULT 'A',
  `per_nacionalidad` char(1) DEFAULT 'V',
  `per_antecedentes_penales` char(1) DEFAULT 'N',
  `per_estado_nacimiento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`per_cedula`, `per_documento`, `per_apellidos`, `per_nombres`, `per_genero`, `per_fecha_nacimiento`, `per_lugar_nacimiento`, `per_discapacidad`, `per_tipo_discapacidad`, `per_edo_civil`, `per_direccion`, `per_telefono_fijo`, `per_telefono_movil`, `per_telefono_trabajo`, `per_email`, `per_foto`, `per_cod_pais`, `per_cod_localidad_nacimiento`, `per_cod_localidad_residencia`, `per_cod_etnia`, `per_estatus`, `per_nacionalidad`, `per_antecedentes_penales`, `per_estado_nacimiento`) VALUES
('21564176', 'C', 'INOSTROZA REYES', 'CLAUDIA MARCELA', '1', '1965-04-20', 'SANTIAGO', '2', NULL, 'C', 'URB. LOS NARANJOS, AVDA LOS CLAVELES NO 45', '0255-6239114', '0416-6521931', NULL, 'CLAUDIMEAD@GMAIL.COM', NULL, 138, '', '180801001', 54, 'A', 'V', 'N', NULL),
('31009367', 'C', 'FENOMENO MENDOZA', 'ALEJANDRO DAVID', '0', '2005-02-14', 'ACARIGUA', '2', NULL, 'S', 'SANTA RITA CALLE 3', '', '0424-5898710', NULL, 'ALEJANDROFENOMENO72@GMAIL.COM', 'imagenes/fotos/31009367.jpeg', 296, '180801001', '180801001', 54, 'A', 'V', 'N', NULL),
('31114131', 'C', 'SALAS ADANS', 'ENMANUEL GABRIEL', '0', '2005-11-09', 'ACARIGUA', '2', NULL, 'S', 'FUNDACION MENDOZA AV 31CASA E-47', '0255-6214394', '0426-4839111', NULL, 'ENMANUELSALAS0911@GMAIL.COM', 'imagenes/fotos/31114131.jpeg', 296, '180801001', '180801001', 54, 'A', 'V', 'N', NULL),
('31659136', 'C', 'RODRIGUEZ OJEDA', 'MAIKOL DAVID', '0', '2005-10-18', 'ACARIGUA', '2', NULL, 'S', 'PARQUE RECIDENCIAL LOS ROBLES ZONA SUR', '', '0412-4497072', NULL, 'MAIKOLDAVID1810@GMAIL.COM', 'imagenes/fotos/31659136.jpeg', 296, '180801001', '180801001', 1, 'A', 'V', 'N', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `pro_codigo` int(11) NOT NULL,
  `pro_codigo_ministerio` char(10) NOT NULL,
  `pro_nombre` varchar(200) NOT NULL,
  `pro_siglas` varchar(6) NOT NULL,
  `pro_intermedia` char(1) NOT NULL,
  `pro_gaceta` int(11) NOT NULL,
  `pro_resolucion` int(11) NOT NULL,
  `pro_fecha_gaceta` date DEFAULT '2014-01-01',
  `pro_fecha_resolucion` date DEFAULT '2014-01-01',
  `pro_arancel` double NOT NULL,
  `pro_comentario` varchar(200) NOT NULL,
  `pro_tipo` char(1) DEFAULT 'C',
  `pro_cod_universidad` int(11) NOT NULL,
  `pro_cod_regimen` int(11) NOT NULL,
  `pro_cod_mes` int(11) NOT NULL,
  `pro_cod_turno` int(11) NOT NULL,
  `pro_cod_grado_academico` int(11) NOT NULL,
  `pro_cod_requerimiento` int(11) NOT NULL,
  `pro_cod_eme` int(11) NOT NULL,
  `pro_estatus` char(1) DEFAULT 'A',
  `pro_nombre_carnet` varchar(100) DEFAULT NULL,
  `pro_color_fondo` char(7) DEFAULT NULL,
  `pro_color_texto` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`pro_codigo`, `pro_codigo_ministerio`, `pro_nombre`, `pro_siglas`, `pro_intermedia`, `pro_gaceta`, `pro_resolucion`, `pro_fecha_gaceta`, `pro_fecha_resolucion`, `pro_arancel`, `pro_comentario`, `pro_tipo`, `pro_cod_universidad`, `pro_cod_regimen`, `pro_cod_mes`, `pro_cod_turno`, `pro_cod_grado_academico`, `pro_cod_requerimiento`, `pro_cod_eme`, `pro_estatus`, `pro_nombre_carnet`, `pro_color_fondo`, `pro_color_texto`) VALUES
(4, 'PNF-04', 'P.N.F. EN INFORMÁTICA', 'PNFI', 'S', 1, 1, '2014-01-01', '2014-01-01', 0, '', 'C', 1, 1, 1, 1, 1, 1, 1, 'A', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rol_codigo` int(11) NOT NULL,
  `rol_nombre` varchar(30) NOT NULL,
  `rol_estatus` char(1) DEFAULT 'A',
  `rol_cod_programa` int(11) DEFAULT 0,
  `rol_cod_sede` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_codigo`, `rol_nombre`, `rol_estatus`, `rol_cod_programa`, `rol_cod_sede`) VALUES
(3, 'DOCENTE', 'A', 0, 0),
(4, 'ESTUDIANTE', 'A', 0, 0),
(11, 'COORDINADOR PNFINF', 'A', 0, 0),
(28, 'ÁREA ACADÉMICA', 'A', 0, 0),
(30, 'COORDINADOR PNFDYL', 'A', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion`
--

CREATE TABLE `seccion` (
  `sec_codigo` int(11) NOT NULL,
  `sec_nombre` char(8) DEFAULT NULL,
  `sec_cod_lapso_academico` int(11) NOT NULL,
  `sec_cod_tipo_seccion` int(11) NOT NULL,
  `sec_cod_semestre` int(11) NOT NULL,
  `sec_cod_turno` int(11) NOT NULL,
  `sec_cod_cohorte_sede` int(11) NOT NULL,
  `sec_cod_malla` int(11) NOT NULL,
  `sec_estatus` char(1) DEFAULT 'A',
  `sec_cod_sede` int(11) DEFAULT NULL,
  `sec_cod_escala_medicion` int(11) DEFAULT 1,
  `sec_capacidad` int(11) DEFAULT 35,
  `sec_inscritos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seccion`
--

INSERT INTO `seccion` (`sec_codigo`, `sec_nombre`, `sec_cod_lapso_academico`, `sec_cod_tipo_seccion`, `sec_cod_semestre`, `sec_cod_turno`, `sec_cod_cohorte_sede`, `sec_cod_malla`, `sec_estatus`, `sec_cod_sede`, `sec_cod_escala_medicion`, `sec_capacidad`, `sec_inscritos`) VALUES
(631, '631', 72, 1, 6, 3, 445, 25, 'A', NULL, 1, 35, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion_unidad_docente`
--

CREATE TABLE `seccion_unidad_docente` (
  `sud_codigo` int(11) NOT NULL,
  `sud_capacidad` int(11) NOT NULL,
  `sud_nro_inscritos` int(11) DEFAULT 0,
  `sud_cod_seccion` int(11) NOT NULL,
  `sud_ced_docente` char(12) DEFAULT '------------',
  `sud_cod_unidad` int(11) NOT NULL,
  `sud_estatus` char(1) DEFAULT 'A',
  `sud_condicion` char(1) DEFAULT 'A',
  `sud_nameplan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seccion_unidad_docente`
--

INSERT INTO `seccion_unidad_docente` (`sud_codigo`, `sud_capacidad`, `sud_nro_inscritos`, `sud_cod_seccion`, `sud_ced_docente`, `sud_cod_unidad`, `sud_estatus`, `sud_condicion`, `sud_nameplan`) VALUES
(60992, 35, 0, 631, '------------', 5, 'A', 'A', NULL),
(60993, 35, 0, 631, '------------', 4, 'A', 'A', NULL),
(60994, 35, 0, 631, '------------', 3, 'A', 'A', NULL),
(60995, 35, 0, 631, '------------', 2, 'A', 'A', NULL),
(60996, 35, 0, 631, '------------', 1, 'A', 'A', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semestre`
--

CREATE TABLE `semestre` (
  `sem_codigo` int(11) NOT NULL,
  `sem_nombre` varchar(10) DEFAULT NULL,
  `sem_estatus` char(1) DEFAULT 'A',
  `sem_cod_trayecto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `semestre`
--

INSERT INTO `semestre` (`sem_codigo`, `sem_nombre`, `sem_estatus`, `sem_cod_trayecto`) VALUES
(6, 'Semestre 6', 'A', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trayecto`
--

CREATE TABLE `trayecto` (
  `tra_codigo` int(11) NOT NULL,
  `tra_nombre` varchar(10) DEFAULT NULL,
  `tra_estatus` char(1) DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trayecto`
--

INSERT INTO `trayecto` (`tra_codigo`, `tra_nombre`, `tra_estatus`) VALUES
(3, 'Trayecto 3', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_curricular`
--

CREATE TABLE `unidad_curricular` (
  `ucu_codigo` int(11) NOT NULL,
  `ucu_siglas` char(12) NOT NULL,
  `ucu_nombre` varchar(200) DEFAULT NULL,
  `ucu_unidad_credito` int(11) DEFAULT NULL,
  `ucu_thte` int(11) DEFAULT NULL,
  `ucu_htea` int(11) DEFAULT NULL,
  `ucu_htei` int(11) DEFAULT NULL,
  `ucu_nota_aprobatoria` int(11) DEFAULT NULL,
  `ucu_duracion_semestre` char(1) DEFAULT NULL,
  `ucu_cod_tuc` int(11) NOT NULL,
  `ucu_cod_malla` int(11) NOT NULL,
  `ucu_cod_semestre` int(11) NOT NULL,
  `ucu_estatus` char(1) DEFAULT 'A',
  `ucu_cod_eje` int(11) DEFAULT 1,
  `ucu_cod_sistema_viejo` char(9) DEFAULT NULL,
  `ucu_prelacion` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidad_curricular`
--

INSERT INTO `unidad_curricular` (`ucu_codigo`, `ucu_siglas`, `ucu_nombre`, `ucu_unidad_credito`, `ucu_thte`, `ucu_htea`, `ucu_htei`, `ucu_nota_aprobatoria`, `ucu_duracion_semestre`, `ucu_cod_tuc`, `ucu_cod_malla`, `ucu_cod_semestre`, `ucu_estatus`, `ucu_cod_eje`, `ucu_cod_sistema_viejo`, `ucu_prelacion`) VALUES
(1, 'PST-III', 'PROYECTO SOCIO TECNOLÓGICO III', NULL, NULL, NULL, NULL, NULL, NULL, 1, 25, 6, 'A', 1, NULL, 'N'),
(2, 'ELEC-III', 'ELECTIVA III', NULL, NULL, NULL, NULL, NULL, NULL, 1, 25, 6, 'A', 1, NULL, 'N'),
(3, 'AA-VI', 'ACTIVIDADES ACREDITABLES VI', NULL, NULL, NULL, NULL, NULL, NULL, 1, 25, 6, 'A', 1, NULL, 'N'),
(4, 'MAT-AP', 'MATEMÁTICA APLICADA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 25, 6, 'A', 1, NULL, 'N'),
(5, 'ING-SW-II', 'INGENIERÍA DEL SOFTWARE II', NULL, NULL, NULL, NULL, NULL, NULL, 1, 25, 6, 'A', 1, NULL, 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usu_codigo` int(11) NOT NULL,
  `usu_nombre` varchar(30) NOT NULL,
  `usu_pegunta_1` varchar(100) DEFAULT '-',
  `usu_pegunta_2` varchar(100) DEFAULT '-',
  `usu_respuesta_1` varchar(100) DEFAULT '-',
  `usu_respuesta_2` varchar(100) DEFAULT '-',
  `usu_cod_rol` int(11) NOT NULL,
  `usu_estatus` char(1) DEFAULT 'A',
  `usu_cedula` char(12) NOT NULL,
  `usu_clave` varchar(100) NOT NULL,
  `usu_intento_inicio` int(11) DEFAULT 0,
  `usu_fecha_intento_inicio` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usu_codigo`, `usu_nombre`, `usu_pegunta_1`, `usu_pegunta_2`, `usu_respuesta_1`, `usu_respuesta_2`, `usu_cod_rol`, `usu_estatus`, `usu_cedula`, `usu_clave`, `usu_intento_inicio`, `usu_fecha_intento_inicio`) VALUES
(420, '21564176', 'Nombre de su abuela materna', 'Nombre de su mascota', '42af0f0ba2c2b29f6bc29a95b842b1135708cf2df2c7632bb6fdbc8db92f0e0a', '72632ed44e223f4279a97001cbccd6f76cb6efe1a19bf5275ab68fc9e63e3fc2', 3, 'A', '21564176', '$2y$10$qUTnyjIfD4Wllb2K08ZIJ.5V1tOaGv0T8wEGFJXyjUBT7oU6xuVG.', 0, '2026-02-01'),
(22692, '21564176AREAA', 'Cuál es su postre favorito', 'Nombre de su mascota', 'fdbf21e91c1ed24f44f7fba798cd072c5b6c8306', '3faa3870c447b43e169f9a11aea6165b0fd6c9fa', 28, 'I', '21564176', '132d66a77a07ce004d7e70d4f7c1f3b59f36a240', 0, '2018-01-09'),
(28238, '21564176PNFDYL', 'Lugar de nacimiento del abuelo materno', 'Nombre de su primer hijo(a)', 'c0d597a0155ea0f14872850dadb99cff1d268f80', '63e7438a5ef373db5478478e571d5e760a306829', 30, 'I', '21564176', 'ee0dc9754e503b32f72a4bb22848a47be7fbbbfe', 0, NULL),
(39114, '31659136', 'Cuál es su mejor amigo de la infancia', 'Cuál es su lugar favorito', 'e03c402eb711c9f259e8bedc4d923c2ac02b0910a04cda97ad407de9e2e570ca', 'f36a259aa7248d1e630a2086f9cbfc49065c0601a4a77fc0705fca5ce67bf196', 4, 'A', '31659136', '$2y$10$AoE37ZtTL9RXMsF8t.kmo.zqlrUbeLclk4Zr4Gt/3yAI3XXwFAaxW', 0, '2025-06-11'),
(39161, '31114131', 'Cuál es su postre favorito', 'Nombre de su mascota', 'b47907a8021e3730a6ba0d6681bcdbd4f29c9de3ac8c3668cf35e4b078ed98b8', 'b47907a8021e3730a6ba0d6681bcdbd4f29c9de3ac8c3668cf35e4b078ed98b8', 4, 'A', '31114131', '$2y$10$1aU6afels2wF4ufRTJ7QJ.JCOvdriP0EgwN6ef3AHlk7znAu3wdY.', 0, '2025-03-24'),
(39195, '31009367', 'Cuál es su mejor amigo de la infancia', 'Nombre de su mascota', '426d9b35f1c46ee8fceaae333c10c03ec5c34c0be862e0aadb81458f1572a7ec', '520246a74b5b5f941facfda1772e30345528a6f62ebd203614bb2cf457bc07ea', 4, 'A', '31009367', '$2y$10$62c3DZWg5nnUs05KS9mm.O9Xyk5qkKI859C1w.prjaTq7twAEnQNW', 0, '2026-02-05'),
(43324, '21564176PNFINF', 'Nombre de su abuela materna', 'Nombre de su mascota', '42af0f0ba2c2b29f6bc29a95b842b1135708cf2df2c7632bb6fdbc8db92f0e0a', '72632ed44e223f4279a97001cbccd6f76cb6efe1a19bf5275ab68fc9e63e3fc2', 11, 'A', '21564176', '$2y$10$2D7zaCCNeND3yPVYPxuUsuMfIUuAHAWtOTufMF0IyhR6JXydvVj6O', 0, '2026-02-20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`est_codigo`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`est_cedula`),
  ADD KEY `est_cod_malla_ingreso` (`est_cod_malla_ingreso`),
  ADD KEY `est_cod_programa` (`est_cod_programa`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`ins_codigo`),
  ADD KEY `ins_cedula` (`ins_cedula`),
  ADD KEY `ins_cod_seccion_unidad_docente` (`ins_cod_seccion_unidad_docente`);

--
-- Indices de la tabla `lapso_academico`
--
ALTER TABLE `lapso_academico`
  ADD PRIMARY KEY (`lap_codigo`);

--
-- Indices de la tabla `malla`
--
ALTER TABLE `malla`
  ADD PRIMARY KEY (`mal_codigo`),
  ADD KEY `mal_cod_programa` (`mal_cod_programa`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`mun_codigo`),
  ADD KEY `mun_cod_estado` (`mun_cod_estado`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`pai_codigo`);

--
-- Indices de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  ADD PRIMARY KEY (`par_codigo`),
  ADD KEY `par_cod_municipio` (`par_cod_municipio`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`per_cedula`),
  ADD KEY `per_cod_pais` (`per_cod_pais`);

--
-- Indices de la tabla `programa`
--
ALTER TABLE `programa`
  ADD PRIMARY KEY (`pro_codigo`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rol_codigo`);

--
-- Indices de la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD PRIMARY KEY (`sec_codigo`),
  ADD KEY `sec_cod_lapso_academico` (`sec_cod_lapso_academico`),
  ADD KEY `sec_cod_semestre` (`sec_cod_semestre`),
  ADD KEY `sec_cod_malla` (`sec_cod_malla`);

--
-- Indices de la tabla `seccion_unidad_docente`
--
ALTER TABLE `seccion_unidad_docente`
  ADD PRIMARY KEY (`sud_codigo`),
  ADD KEY `sud_cod_seccion` (`sud_cod_seccion`),
  ADD KEY `sud_cod_unidad` (`sud_cod_unidad`);

--
-- Indices de la tabla `semestre`
--
ALTER TABLE `semestre`
  ADD PRIMARY KEY (`sem_codigo`),
  ADD KEY `sem_cod_trayecto` (`sem_cod_trayecto`);

--
-- Indices de la tabla `trayecto`
--
ALTER TABLE `trayecto`
  ADD PRIMARY KEY (`tra_codigo`);

--
-- Indices de la tabla `unidad_curricular`
--
ALTER TABLE `unidad_curricular`
  ADD PRIMARY KEY (`ucu_codigo`),
  ADD KEY `ucu_cod_malla` (`ucu_cod_malla`),
  ADD KEY `ucu_cod_semestre` (`ucu_cod_semestre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usu_codigo`),
  ADD KEY `usu_cod_rol` (`usu_cod_rol`),
  ADD KEY `usu_cedula` (`usu_cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `ins_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=758556;

--
-- AUTO_INCREMENT de la tabla `lapso_academico`
--
ALTER TABLE `lapso_academico`
  MODIFY `lap_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `malla`
--
ALTER TABLE `malla`
  MODIFY `mal_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `pai_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT de la tabla `programa`
--
ALTER TABLE `programa`
  MODIFY `pro_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rol_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `seccion`
--
ALTER TABLE `seccion`
  MODIFY `sec_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=632;

--
-- AUTO_INCREMENT de la tabla `seccion_unidad_docente`
--
ALTER TABLE `seccion_unidad_docente`
  MODIFY `sud_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60997;

--
-- AUTO_INCREMENT de la tabla `semestre`
--
ALTER TABLE `semestre`
  MODIFY `sem_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `trayecto`
--
ALTER TABLE `trayecto`
  MODIFY `tra_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `unidad_curricular`
--
ALTER TABLE `unidad_curricular`
  MODIFY `ucu_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usu_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43325;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`est_cedula`) REFERENCES `persona` (`per_cedula`),
  ADD CONSTRAINT `estudiante_ibfk_2` FOREIGN KEY (`est_cod_malla_ingreso`) REFERENCES `malla` (`mal_codigo`),
  ADD CONSTRAINT `estudiante_ibfk_3` FOREIGN KEY (`est_cod_programa`) REFERENCES `programa` (`pro_codigo`);

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`ins_cedula`) REFERENCES `estudiante` (`est_cedula`),
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`ins_cod_seccion_unidad_docente`) REFERENCES `seccion_unidad_docente` (`sud_codigo`);

--
-- Filtros para la tabla `malla`
--
ALTER TABLE `malla`
  ADD CONSTRAINT `malla_ibfk_1` FOREIGN KEY (`mal_cod_programa`) REFERENCES `programa` (`pro_codigo`);

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `municipio_ibfk_1` FOREIGN KEY (`mun_cod_estado`) REFERENCES `estado` (`est_codigo`);

--
-- Filtros para la tabla `parroquia`
--
ALTER TABLE `parroquia`
  ADD CONSTRAINT `parroquia_ibfk_1` FOREIGN KEY (`par_cod_municipio`) REFERENCES `municipio` (`mun_codigo`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`per_cod_pais`) REFERENCES `pais` (`pai_codigo`);

--
-- Filtros para la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD CONSTRAINT `seccion_ibfk_1` FOREIGN KEY (`sec_cod_lapso_academico`) REFERENCES `lapso_academico` (`lap_codigo`),
  ADD CONSTRAINT `seccion_ibfk_2` FOREIGN KEY (`sec_cod_semestre`) REFERENCES `semestre` (`sem_codigo`),
  ADD CONSTRAINT `seccion_ibfk_3` FOREIGN KEY (`sec_cod_malla`) REFERENCES `malla` (`mal_codigo`);

--
-- Filtros para la tabla `seccion_unidad_docente`
--
ALTER TABLE `seccion_unidad_docente`
  ADD CONSTRAINT `seccion_unidad_docente_ibfk_1` FOREIGN KEY (`sud_cod_seccion`) REFERENCES `seccion` (`sec_codigo`),
  ADD CONSTRAINT `seccion_unidad_docente_ibfk_2` FOREIGN KEY (`sud_cod_unidad`) REFERENCES `unidad_curricular` (`ucu_codigo`);

--
-- Filtros para la tabla `semestre`
--
ALTER TABLE `semestre`
  ADD CONSTRAINT `semestre_ibfk_1` FOREIGN KEY (`sem_cod_trayecto`) REFERENCES `trayecto` (`tra_codigo`);

--
-- Filtros para la tabla `unidad_curricular`
--
ALTER TABLE `unidad_curricular`
  ADD CONSTRAINT `unidad_curricular_ibfk_1` FOREIGN KEY (`ucu_cod_malla`) REFERENCES `malla` (`mal_codigo`),
  ADD CONSTRAINT `unidad_curricular_ibfk_2` FOREIGN KEY (`ucu_cod_semestre`) REFERENCES `semestre` (`sem_codigo`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`usu_cod_rol`) REFERENCES `rol` (`rol_codigo`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`usu_cedula`) REFERENCES `persona` (`per_cedula`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
