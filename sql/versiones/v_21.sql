#SIEMPRE SE ACTUALIZARA LA VERSIÓN DE LAS DOS DBs AUNQUE NO SE HAGAN CAMBIOS.
#REMPLAZAR ANTES DE EJECUTAR
# {{{user_mysql}}}  = REEMPLAZAR POR NOMBRE USER QUE EJECUTA.
# {{{db_log}}}      = REEMPLAZAR POR NOMBRE DB LOG.
# {{{db_app}}}      = REEMPLAZAR POR NOMBRE DB APP.

USE `{{{db_log}}}`;
SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `empleado_cursos` 
ADD COLUMN `tipo_promocion` TINYINT(1) NULL DEFAULT 1 AFTER `fecha`;

CREATE TABLE `empleado_historial_creditos` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) NOT NULL,
  `fecha_operacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo_operacion` CHAR(1) NOT NULL,
  `id_empleado_historial_creditos` INT(11) NOT NULL,
  `id_empleado` INT(11) NOT NULL,
  `id_tabla` BIGINT(20) NOT NULL,
  `tabla_nombre` VARCHAR(50) NOT NULL,
  `creditos_agregados` INT(50) NULL DEFAULT 0,
  `creditos_descontados` INT(50) NULL DEFAULT 0,
  `creditos_disponibles` INT(50) NULL DEFAULT 0,
  `porcentaje` INT(50) NULL DEFAULT 0,
  `fecha_considerada` DATE NOT NULL,
  `fecha_operacion_historial_creditos` DATETIME NOT NULL,
  `tipo_promocion` TINYINT(1) NULL DEFAULT 1,
  `borrado` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));


CREATE TABLE `promocion_creditos` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_promocion_creditos` int(11) NOT NULL ,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`tipo_operacion` CHAR(1) NOT NULL,
  `fecha_desde` DATE NOT NULL,
	`fecha_hasta` DATE NULL,
  `id_nivel` int(11) NOT NULL,
  `id_tramo` int(11) NOT NULL,
	`creditos` INT NOT NULL,
  `borrado` TINYINT(1) DEFAULT 0,
PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `empleado_promociones` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) NOT NULL,
  `fecha_operacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo_operacion` CHAR(1) NOT NULL,

  `id_empleado_promociones` INT(11) NULL DEFAULT NULL,
  `id_empleado` INT(11) DEFAULT NULL,
  `periodo_inicio` INT(11) DEFAULT NULL,
  `periodo_fin` INT(11) DEFAULT NULL,
  `id_empleado_escalafon` INT(11) NULL DEFAULT NULL,
  `id_grado` INT(11) NULL DEFAULT NULL,
  `id_tipo_promocion` INT(11) DEFAULT NULL,
  `creditos_descontados` INT(11) DEFAULT NULL,
  `creditos_reconocidos` INT(11) DEFAULT NULL,
  `creditos_requeridos` INT(11) DEFAULT NULL,
  `numero_expediente` VARCHAR(255) DEFAULT NULL,
  `acto_administrativo` VARCHAR(255) DEFAULT NULL,
  `fecha_promocion` DATE DEFAULT NULL,
  `id_motivo` INT(11) DEFAULT NULL,
  `archivo` VARCHAR(255) DEFAULT NULL,
  `borrado` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER $$
DROP TRIGGER IF EXISTS `empleado_promociones_tg_insert`$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_promociones_tg_insert` AFTER INSERT ON `empleado_promociones` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`, `id_empleado`) 
  VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_promociones',NEW.id_empleado);
END $$
DELIMITER ;


CREATE 
     OR REPLACE ALGORITHM = UNDEFINED 
    DEFINER = `{{{user_mysql}}}`@`%` 
    SQL SECURITY DEFINER
VIEW `_vt_registros_historial` AS
    SELECT 
        `_rl`.`id` AS `id`,
        `_rl`.`id_usuario` AS `id_usuario`,
        `_rl`.`fecha_operacion` AS `fecha_operacion`,
        (CASE `_rl`.`tipo_operacion`
            WHEN 'M' THEN 'modificacion'
            WHEN 'B' THEN 'baja'
            WHEN 'A' THEN 'alta'
        END) AS `tipo_operacion`,
        `_rl`.`id_tabla` AS `id_tabla`,
        `_rl`.`tabla_nombre` AS `tabla_nombre`,
        `_rl`.`id_empleado` AS `id_empleado`,
        CONCAT(`pe`.`nombre`, ' ', `pe`.`apellido`) AS `empleado_nombre`,
        `em`.`cuit` AS `empleado_cuit`,
        '' AS `usuario_nombre`,
        'legajo' AS `tipo_registro`
    FROM
        ((`_registros_legajos` `_rl`
        JOIN `{{{db_app}}}`.`empleados` `em` ON ((`em`.`id` = `_rl`.`id_empleado`)))
        JOIN `{{{db_app}}}`.`personas` `pe` ON ((`pe`.`id` = `em`.`id_persona`)))
    ORDER BY `_rl`.`fecha_operacion` DESC;

DROP FUNCTION IF EXISTS getPanelUsuarioNombre;


CREATE TABLE IF NOT EXISTS `empleado_creditos_iniciales` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`tipo_operacion` CHAR(1) NOT NULL,
  `id_empleado_creditos_iniciales` int(11) NOT NULL ,

  `id_empleado` INT(11) NULL,
  `fecha_considerada` DATE NOT NULL,
  `creditos` INT(11) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `borrado` TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`),
UNIQUE INDEX `id_UNIQUE` (`id` ASC)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER $$
DROP TRIGGER IF EXISTS `empleado_creditos_iniciales_tg_insert`$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_creditos_iniciales_tg_insert` AFTER INSERT ON `empleado_creditos_iniciales` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`, `id_empleado`) 
  VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_creditos_iniciales',NEW.id_empleado);
END $$
DELIMITER ;

ALTER TABLE `_registros_legajos` 
CHANGE COLUMN `tabla_nombre` `tabla_nombre` ENUM('anticorrupcion_presentacion', 'anticorrupcion', 'contratante', 'embargos', 'empleado_comision', 'empleado_dep_informales', 'empleado_dependencia', 'empleado_documentos', 'empleado_escalafon', 'empleado_horarios', 'empleado_horas_extras', 'empleado_perfil', 'empleado_presupuesto', 'empleado_salud', 'empleado_seguros', 'empleado_sindicatos', 'empleados', 'empleados_lic_especiales', 'empleados_x_ubicacion', 'familiar_discapacidad', 'grupo_familiar', 'observaciones', 'persona_discapacidad', 'persona_domicilio', 'persona_otros_conocimientos', 'persona_telefono', 'persona_titulo', 'personas', 'responsables_contrato', 'perfil_actividades', 'perfil_resultado_parc_final', 'persona_experiencia_laboral', 'empleado_ultimos_cambios', 'empleado_evaluaciones', 'designacion_transitoria', 'persona_titulo_creditos', 'empleado_cursos', 'empleado_promociones', 'empleado_creditos_iniciales')  NULL DEFAULT NULL ;



INSERT INTO db_version VALUES('21.0', now());