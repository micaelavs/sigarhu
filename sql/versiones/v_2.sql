CREATE TABLE `dependencias_informales` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_dependencia` INT(11) NOT NULL,
  `nombre` VARCHAR(225) NOT NULL,
  `fecha_desde` DATE NULL DEFAULT NULL,
  `fecha_hasta` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE `empleado_dep_informales` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `id_dep_informal` INT(11) NOT NULL,
  `fecha_desde` DATE NOT NULL,
  `fecha_hasta` DATE NULL DEFAULT NULL,
  `borrado` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


ALTER TABLE `presupuestos` 
ADD INDEX `fk_presupuestos_1_idx` (`id_ubicacion_geografica` ASC);

ALTER TABLE `persona_discapacidad` 
CHANGE COLUMN `observaciones` `observaciones` VARCHAR(255) NULL DEFAULT NULL ;

INSERT INTO db_version VALUES('2.0', now());