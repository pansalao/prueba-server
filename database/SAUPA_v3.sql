CREATE TABLE `calendario_academico` (
  `id_calendario_academico` integer PRIMARY KEY,
  `semana_lapso_uno_calendario_academico` int,
  `semana_lapso_dos_calendario_academico` int,
  `semana_lapso_uno_introductorio_calendario_academico` int,
  `semana_lapso_dos_introductorio_calendario_academico` int,
  `semana_intensibo_introductorio_calendario_academico` int,
  `semana_per_uno_calendario_academico` int,
  `semana_per_dos_calendario_academico` int,
  `dia_inicio_calendario_academico` date,
  `dia_fin_calendario_academico` date,
  `estatus` enum(1,2,3,4)
);

CREATE TABLE `detalle_evento` (
  `id_detalle_evento` integer PRIMARY KEY,
  `id_evento` integer,
  `id_calendario_academico` integer,
  `dia_inicio_detalle_evento` date,
  `dia_fin_detalle_evento` date,
  `semana_detalle_evento` integer,
  `estatus` enum(1,2,3)
);

CREATE TABLE `especial_evento` (
  `id_especial_evento` integer PRIMARY KEY,
  `especial_evento_name` varchar(255),
  `estatus` enum(1,2,3)
);

CREATE TABLE `evento` (
  `id_evento` integer PRIMARY KEY,
  `codigo_color_evento` varchar(255),
  `nombre_evento` varchar(100),
  `tipo_evento` enum(1,2,3,4,5),
  `id_especial_evento` integer,
  `is_laborable_evento` boolean,
  `is_repetible_evento` boolean,
  `is_cantidad_dias_evento` boolean,
  `is_independiente_evento` boolean,
  `is_superponible_evento` boolean,
  `is_semana_evento` boolean,
  `is_dia_evento` boolean,
  `semana_evento` json,
  `cantidad_dias_evento` int,
  `dia_evento` date,
  `estatus` enum(1,2,3)
);

CREATE TABLE `tema_unidad` (
  `id_tema_unidad` integer PRIMARY KEY,
  `id_unidad_curricular` varchar(7),
  `titulo_tema` text,
  `unidad_tema` enum(1,2,3,4),
  `estatus` enum(1,2,3)
);

CREATE TABLE `contenido` (
  `id_contenido` integer PRIMARY KEY,
  `titulo_contenido` text,
  `estatus` enum(1,2,3)
);

CREATE TABLE `detalle_objetivo` (
  `id_detalle_objetivo` integer PRIMARY KEY,
  `id_contenido` int,
  `id_objetivo` int,
  `estatus` enum(1,2,3)
);

CREATE TABLE `detalle_contenido` (
  `id_detalle_contenido` integer PRIMARY KEY,
  `id_unidad_corte` int,
  `id_contenido` int,
  `estatus` enum(1,2,3)
);

CREATE TABLE `firma` (
  `id_firma` integer PRIMARY KEY,
  `id_usuario` int,
  `foto_firma` blob,
  `estatus` enum(1,2,3)
);

CREATE TABLE `permiso` (
  `id_permiso` integer PRIMARY KEY,
  `nombre_permiso` tinytext,
  `estatus` enum(1,2,3)
);

CREATE TABLE `rol_permiso` (
  `id_rol_permiso` integer PRIMARY KEY,
  `id_permiso` integer,
  `id_rol` integer,
  `estatus` enum(1,2,3)
);

CREATE TABLE `unidad_corte` (
  `id_unidad_corte` integer PRIMARY KEY,
  `id_planificacion` integer,
  `numero_unidad_corte` enum(1,2,3,4),
  `indicador_logro_unidad_corte` text,
  `descripcion_actividad_unidad_corte` text,
  `descripcion_motivo_rechazo_unidad_corte` text,
  `id_tecnica_actividad` int,
  `estatus` enum(1,2,3,4,5)
);

CREATE TABLE `instrumento` (
  `id_instrumento` integer PRIMARY KEY,
  `nombre_instrumento` varchar(255),
  `estatus` enum(1,2,3)
);

CREATE TABLE `tecnica_actividad` (
  `id_tecnica_actividad` integer PRIMARY KEY,
  `nombre_tecnica_actividad` varchar(255),
  `estatus` enum(1,2,3)
);

CREATE TABLE `objetivo` (
  `id_objetivo` integer PRIMARY KEY,
  `titulo_objetivo` varchar(255),
  `id_tema_unidad` int,
  `estatus` enum(1,2,3)
);

CREATE TABLE `planificacion` (
  `id_planificacion` integer PRIMARY KEY,
  `id_profesor_asignado` int,
  `aceptado_coordinador` int,
  `tipo_planificacion` text,
  `archivo_contrato` VARCHAR,
  `notificado` TINYINT,
  `estatus` enum(1,2,3,4),
  `proposito_unidad` text
);

CREATE TABLE `vocero` (
  `id_vocero` integer PRIMARY KEY,
  `id_estudiante` varchar(255),
  `id_seccion` int(11),
  `id_pnf` int(11),
  `id_coordinador` int(11),
  `tipo_vocero` tinyint(4),
  `estatus` enum(1,2,3)
);

CREATE TABLE `bibliografia` (
  `id_bibliografia` integer PRIMARY KEY,
  `nombre_bibliografia` text,
  `estatus` enum(1,2,3)
);

CREATE TABLE `detalle_bibliografia` (
  `id_detalle_bibliografia` integer PRIMARY KEY,
  `id_unidad_corte` integer,
  `id_bibliografia` integer,
  `estatus` enum(1,2,3)
);

CREATE TABLE `tipo_evaluacion` (
  `id_tipo_evaluacion` integer PRIMARY KEY,
  `nombre_tipo_evaluacion` text,
  `estatus` enum(1,2,3)
);

CREATE TABLE `tecnica_evaluacion` (
  `id_tecnica_evaluacion` integer PRIMARY KEY,
  `nombre_tecnica_evaluacion` text,
  `estatus` enum(1,2,3)
);

CREATE TABLE `detalle_evaluacion` (
  `id_detalle_evaluacion` integer PRIMARY KEY,
  `id_tipo_evaluacion` integer,
  `id_tecnica_evaluacion` integer,
  `id_instrumento` integer,
  `ponderacion_detalle_evaluacion` float,
  `integrantes_detalle_evaluacion` int,
  `id_unidad_corte` int,
  `fecha_evaluacion_detalle_evaluacion` date,
  `forma_participacion_detalle_evaluacion` enum(1,2),
  `estatus` enum(1,2,3,4)
);

CREATE TABLE `recurso` (
  `id_recurso` integer PRIMARY KEY,
  `nombre_recurso` varchar(255),
  `estatus` enum(1,2,3)
);

CREATE TABLE `detalle_recurso` (
  `id_detalle_recurso` integer PRIMARY KEY,
  `id_recurso` int,
  `id_unidad_corte` int,
  `estatus` enum(1,2,3)
);

CREATE TABLE `bitacora` (
  `id_bitacora` integer PRIMARY KEY AUTO_INCREMENT,
  `id_usuario` integer NOT NULL,
  `modulo_afectado_bitacora` varchar(255),
  `tabla_afectada_bitacora` text,
  `id_registro_afectado_bitacora` text,
  `accion_bitacora` enum(CREAR,MODIFICAR,MOSTRAR,ELIMINAR,LOGIN,LOGOUT,REPORTE),
  `valores_anteriores_bitacora` json,
  `valores_nuevos_bitacora` json,
  `ip_origen_bitacora` varchar(45),
  `fecha_creacion` timestamp DEFAULT (now()),
  `estatus` enum(1,2,3)
);

ALTER TABLE `detalle_evaluacion` ADD FOREIGN KEY (`id_tipo_evaluacion`) REFERENCES `tipo_evaluacion` (`id_tipo_evaluacion`);

ALTER TABLE `detalle_evaluacion` ADD FOREIGN KEY (`id_tecnica_evaluacion`) REFERENCES `tecnica_evaluacion` (`id_tecnica_evaluacion`);

ALTER TABLE `detalle_recurso` ADD FOREIGN KEY (`id_recurso`) REFERENCES `recurso` (`id_recurso`);

ALTER TABLE `detalle_bibliografia` ADD FOREIGN KEY (`id_bibliografia`) REFERENCES `bibliografia` (`id_bibliografia`);

ALTER TABLE `rol_permiso` ADD FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id_permiso`);

ALTER TABLE `unidad_corte` ADD FOREIGN KEY (`id_planificacion`) REFERENCES `planificacion` (`id_planificacion`);

ALTER TABLE `detalle_evaluacion` ADD FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

ALTER TABLE `detalle_recurso` ADD FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

ALTER TABLE `detalle_bibliografia` ADD FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

ALTER TABLE `detalle_evaluacion` ADD FOREIGN KEY (`id_instrumento`) REFERENCES `instrumento` (`id_instrumento`);

ALTER TABLE `objetivo` ADD FOREIGN KEY (`id_tema_unidad`) REFERENCES `tema_unidad` (`id_tema_unidad`);

ALTER TABLE `detalle_objetivo` ADD FOREIGN KEY (`id_contenido`) REFERENCES `contenido` (`id_contenido`);

ALTER TABLE `detalle_objetivo` ADD FOREIGN KEY (`id_objetivo`) REFERENCES `objetivo` (`id_objetivo`);

ALTER TABLE `detalle_contenido` ADD FOREIGN KEY (`id_unidad_corte`) REFERENCES `unidad_corte` (`id_unidad_corte`);

ALTER TABLE `detalle_contenido` ADD FOREIGN KEY (`id_contenido`) REFERENCES `contenido` (`id_contenido`);

ALTER TABLE `unidad_corte` ADD FOREIGN KEY (`id_tecnica_actividad`) REFERENCES `tecnica_actividad` (`id_tecnica_actividad`);

ALTER TABLE `detalle_evento` ADD FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`);

ALTER TABLE `detalle_evento` ADD FOREIGN KEY (`id_detalle_evento`) REFERENCES `calendario_academico` (`id_calendario_academico`);

ALTER TABLE `especial_evento` ADD FOREIGN KEY (`id_especial_evento`) REFERENCES `evento` (`id_especial_evento`);
