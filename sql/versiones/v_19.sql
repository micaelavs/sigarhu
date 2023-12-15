USE `sigarhu_historial`;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `perfil_evaluaciones`;
CREATE TABLE IF NOT EXISTS `empleado_evaluaciones` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` DATETIME NOT NULL,
	`tipo_operacion` CHAR(1) NOT NULL,
  	`id_empleado_evaluaciones` INT(11) NOT NULL,
  	`id_perfil` INT(11) NOT NULL,
  	`id_empleado` INT(11) NOT NULL,
  	`acto_administrativo` VARCHAR(255),
  	`evaluacion` TINYINT(1) NOT NULL,
  	`anio` INT(4) NOT NULL,
  	`archivo` VARCHAR(255) NULL,
  	`fecha_evaluacion` DATE NULL DEFAULT NULL,
  	`formulario` INT(4) NOT NULL,
  	`puntaje` INT(3) NOT NULL,
  	`bonificado` TINYINT(1) NULL DEFAULT '0',
  	`borrado` TINYINT(1) NULL DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

DELIMITER $$
DROP TRIGGER IF EXISTS `perfil_evaluaciones_tg_insert`$$
DROP TRIGGER IF EXISTS `empleado_evaluaciones_tg_insert`$$
CREATE TRIGGER `empleado_evaluaciones_tg_insert` AFTER INSERT ON `empleado_evaluaciones` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`, `id_empleado`) 
  VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_evaluaciones',NEW.id_empleado);
END $$
DELIMITER ;


CREATE TABLE IF NOT EXISTS `designacion_transitoria` (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`id_usuario` INT(11) NOT NULL,
`fecha_operacion` DATETIME NOT NULL,
`tipo_operacion` CHAR(1) NOT NULL,
`id_designacion_transitoria` INT(11) NOT NULL,
`id_empleado` INT(11) NOT NULL,
`fecha_desde` DATE NULL DEFAULT NULL,
`fecha_hasta` DATE NULL DEFAULT NULL,
`archivo` VARCHAR(100) NULL DEFAULT NULL,
`tipo` TINYINT(1) NOT NULL DEFAULT '1',
`borrado` TINYINT(1) NOT NULL DEFAULT '0' ,
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

DELIMITER $$

DROP TRIGGER IF EXISTS designacion_transitoria_tg_insert$$
CREATE DEFINER=`root`@`localhost` TRIGGER `designacion_transitoria_tg_insert` AFTER INSERT ON `designacion_transitoria` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`, `id_empleado`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario, NEW.id_empleado, NEW.fecha_operacion,NEW.tipo_operacion,'designacion_transitoria');
END$$

DROP TRIGGER IF EXISTS persona_experiencia_laboral_tg_insert$$
CREATE DEFINER=`root`@`localhost` TRIGGER `persona_experiencia_laboral_tg_insert` AFTER INSERT ON `persona_experiencia_laboral` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_experiencia_laboral',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1));
END$$
DELIMITER ;


CREATE TABLE IF NOT EXISTS `cursos_snc` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` DATETIME NOT NULL,
	`tipo_operacion` CHAR(1) NOT NULL,
	`id_cursos_snc` int(11) NOT NULL,
	`codigo` VARCHAR(45) NOT NULL,
	`nombre_curso` VARCHAR(450) NOT NULL,
	`creditos` INT NOT NULL,
	`borrado` TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `empleado_cursos` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` DATETIME NOT NULL,
	`tipo_operacion` CHAR(1) NOT NULL,
	`id_empleado_cursos` int(11) NOT NULL,
	`id_empleado` INT(11) NOT NULL,
	`id_curso` INT(11) NOT NULL,
	`fecha` DATE NULL DEFAULT NULL,
	`tipo_promocion` TINYINT(1) NULL DEFAULT 1,
	`borrado` TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

DELIMITER $$

DROP TRIGGER IF EXISTS empleado_cursos_tg_insert$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_cursos_tg_insert` AFTER INSERT ON `empleado_cursos` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`, `id_empleado`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario, NEW.id_empleado, NEW.fecha_operacion,NEW.tipo_operacion,'empleado_cursos');
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS cursos_snc_tg_insert$$
CREATE DEFINER=`root`@`localhost` TRIGGER `cursos_snc_tg_insert` AFTER INSERT ON `cursos_snc` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario, NEW.fecha_operacion,NEW.tipo_operacion,'cursos_snc');
END$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `persona_titulo_creditos` (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`id_usuario` INT(11) NOT NULL,
`fecha_operacion` DATETIME NOT NULL,
`tipo_operacion` CHAR(1) NOT NULL,
`id_persona_titulo_creditos` INT(11) NOT NULL,
`id_persona_titulo` INT(11) NOT NULL,
`id_persona` INT(11) NOT NULL,
`fecha` DATE NOT NULL,
`acto_administrativo` varchar(45) NOT NULL,
`creditos` varchar(45) NOT NULL,
`archivo` varchar(45) DEFAULT NULL,
`estado_titulo` tinyint(1) NOT NULL,
`borrado` tinyint(1) DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

DELIMITER $$

DROP TRIGGER IF EXISTS persona_titulo_creditos_tg_insert$$
CREATE DEFINER=`root`@`localhost` TRIGGER `persona_titulo_creditos_tg_insert` AFTER INSERT ON `persona_titulo_creditos` FOR EACH ROW BEGIN
    INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,  `fecha_operacion`, `tipo_operacion`, `tabla_nombre`, `id_persona`,`id_empleado`) 
    VALUES (NEW.id,NEW.id_usuario, NEW.fecha_operacion,NEW.tipo_operacion,'persona_titulo_creditos' ,NEW.id_persona , (SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1));
END$$
DELIMITER ;

ALTER TABLE `_registros_legajos` 
CHANGE COLUMN `fecha_operacion` `fecha_operacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
CHANGE COLUMN `tabla_nombre` `tabla_nombre` ENUM('anticorrupcion_presentacion', 'anticorrupcion', 'contratante', 'embargos', 'empleado_comision', 'empleado_dep_informales', 'empleado_dependencia', 'empleado_documentos', 'empleado_escalafon', 'empleado_horarios', 'empleado_horas_extras', 'empleado_perfil', 'empleado_presupuesto', 'empleado_salud', 'empleado_seguros', 'empleado_sindicatos', 'empleados', 'empleados_lic_especiales', 'empleados_x_ubicacion', 'familiar_discapacidad', 'grupo_familiar', 'observaciones', 'persona_discapacidad', 'persona_domicilio', 'persona_otros_conocimientos', 'persona_telefono', 'persona_titulo', 'personas', 'responsables_contrato', 'perfil_actividades', 'perfil_resultado_parc_final', 'persona_experiencia_laboral', 'empleado_ultimos_cambios', 'empleado_evaluaciones', 'designacion_transitoria', 'persona_titulo_creditos', 'empleado_cursos')  NULL DEFAULT NULL ;

ALTER TABLE `_registros_abm` 
CHANGE COLUMN `tabla_nombre` `tabla_nombre` ENUM('comisiones', 'convenio_agrupamientos', 'convenio_funciones_ejecutivas', 'convenio_grados', 'convenio_modalidad_vinculacion', 'convenio_niveles', 'convenio_situacion_revista', 'convenio_tramos', 'convenio_unidades_retributivas', 'convenio_ur_montos', 'denominacion_funcion', 'denominacion_puesto', 'dependencias', 'dependencias_informales', 'licencias_especiales', 'motivo_baja', 'nivel_educativo', 'obras_sociales', 'perfil_tarea', 'plantilla_horarios', 'presupuesto_actividades', 'presupuesto_jurisdicciones', 'presupuesto_obras', 'presupuesto_proyectos', 'presupuesto_programas', 'presupuesto_subprogramas', 'presupuesto_saf', 'presupuesto_ubicaciones_geograficas', 'presupuestos', 'puestos', 'seguro_vida', 'sindicatos', 'subfamilia_puestos', 'tipo_discapacidad', 'titulo', 'ubicacion_edificios', 'ubicaciones', 'dependencias_historicas', 'cursos_snc') NULL DEFAULT NULL ;

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS `db_version` (
  `version` mediumint(5) unsigned NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO db_version VALUES('19.0', now());
SET FOREIGN_KEY_CHECKS=1;