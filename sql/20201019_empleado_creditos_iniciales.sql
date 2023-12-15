#SIEMPRE SE ACTUALIZARA LA VERSIÓN DE LAS DOS DBs AUNQUE NO SE HAGAN CAMBIOS.
#REMPLAZAR ANTES DE EJECUTAR
# {{{user_mysql}}}  = REEMPLAZAR POR NOMBRE USER QUE EJECUTA.
# {{{db_log}}}      = REEMPLAZAR POR NOMBRE DB LOG.
# {{{db_app}}}      = REEMPLAZAR POR NOMBRE DB APP.

USE `{{{db_log}}}`;

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


USE `{{{db_app}}}`;
CREATE TABLE IF NOT EXISTS`empleado_creditos_iniciales` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NULL,
  `fecha_considerada` DATE NOT NULL,
  `creditos` INT(11) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `borrado` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_empleado_creditos_iniciales_1_IDX` (`id_empleado` ASC),
  INDEX `empleado_creditos_iniciales_2_IDX` (`borrado` ASC),
  CONSTRAINT `fk_empleado_creditos_iniciales_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

DROP TRIGGER IF EXISTS `empleado_creditos_iniciales_AFTER_INSERT`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_creditos_iniciales_AFTER_INSERT` AFTER INSERT ON `empleado_creditos_iniciales` FOR EACH ROW BEGIN
INSERT INTO {{{db_log}}}.empleado_creditos_iniciales
(
    
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_creditos_iniciales`,
    `id_empleado`,
    `fecha_considerada`,
    `creditos`,
    `descripcion`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.id_empleado,
    NEW.fecha_considerada,
    NEW.creditos,
    NEW.descripcion,
    NEW.borrado
);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `empleado_creditos_iniciales_AFTER_UPDATE`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_creditos_iniciales_AFTER_UPDATE` AFTER UPDATE ON `empleado_creditos_iniciales` FOR EACH ROW BEGIN
INSERT INTO {{{db_log}}}.empleado_creditos_iniciales
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_creditos_iniciales`,
    `id_empleado`,
    `fecha_considerada`,
    `creditos`,
	  `descripcion`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.id_empleado,
    NEW.fecha_considerada,
    NEW.creditos,
	  NEW.descripcion,
    NEW.borrado
);
END$$
DELIMITER ;