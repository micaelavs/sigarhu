SET FOREIGN_KEY_CHECKS=0;
ALTER TABLE `sigarhu`.`empleado_escalafon` CHANGE COLUMN `exc_art_14` `exc_art_14` MEDIUMTEXT NULL DEFAULT NULL ;
ALTER TABLE `sigarhu_historial`.`empleado_escalafon` CHANGE COLUMN `exc_art_14` `exc_art_14` MEDIUMTEXT NULL DEFAULT NULL ;
SET FOREIGN_KEY_CHECKS=1;

USE `sigarhu`;
SET FOREIGN_KEY_CHECKS=0;


DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_alta$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_escalafon_tg_alta` AFTER INSERT ON `empleado_escalafon` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_escalafon
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_escalafon`,
    `id_empleado`,  
    `id_modalidad_vinculacion`,  
    `id_situacion_revista`,
    `id_nivel`,
    `id_grado`,  
    `id_tramo`,
    `id_agrupamiento`,  
    `id_funcion_ejecutiva`,
    `compensacion_geografica`,  
    `compensacion_transitoria`, 
    `fecha_inicio`, 
    `fecha_fin`, 
    `exc_art_14`,
    `unidad_retributiva`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_modalidad_vinculacion,
    NEW.id_situacion_revista,
    NEW.id_nivel,
    NEW.id_grado,
    NEW.id_tramo,
    NEW.id_agrupamiento,
    NEW.id_funcion_ejecutiva,
    NEW.compensacion_geografica,
    NEW.compensacion_transitoria,
    NEW.fecha_inicio,
    NEW.fecha_fin,
    NEW.exc_art_14,
    NEW.unidad_retributiva
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_modificacion$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_escalafon_tg_modificacion` AFTER UPDATE ON `empleado_escalafon` FOR EACH ROW
BEGIN
IF NEW.id_modalidad_vinculacion <> OLD.id_modalidad_vinculacion OR 
    NEW.id_situacion_revista <> OLD.id_situacion_revista OR
    NEW.id_nivel <> OLD.id_nivel OR
    NEW.id_grado <> OLD.id_grado OR
    NEW.id_tramo <> OLD.id_tramo OR
    NEW.id_agrupamiento <> OLD.id_agrupamiento OR
    NEW.id_funcion_ejecutiva <> OLD.id_funcion_ejecutiva OR
    NEW.compensacion_geografica <> OLD.compensacion_geografica OR
    NEW.compensacion_transitoria <> OLD.compensacion_transitoria OR
    NEW.fecha_inicio <> OLD.fecha_inicio OR
    NEW.fecha_fin <> OLD.fecha_fin OR
    NEW.exc_art_14 <> OLD.exc_art_14 OR
    NEW.unidad_retributiva <> OLD.unidad_retributiva
THEN
    INSERT INTO sigarhu_historial.empleado_escalafon
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_empleado_escalafon`,
        `id_empleado`,  
        `id_modalidad_vinculacion`,  
        `id_situacion_revista`,
        `id_nivel`,
        `id_grado`,  
        `id_tramo`,
        `id_agrupamiento`,  
        `id_funcion_ejecutiva`,
        `compensacion_geografica`,  
        `compensacion_transitoria`, 
        `fecha_inicio`, 
        `fecha_fin`, 
        `exc_art_14`,
		`unidad_retributiva`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.fecha_fin <> "", "B", "M"),
        OLD.id,
        NEW.id_empleado,
        NEW.id_modalidad_vinculacion,
        NEW.id_situacion_revista,
        NEW.id_nivel,
        NEW.id_grado,
        NEW.id_tramo,
        NEW.id_agrupamiento,
        NEW.id_funcion_ejecutiva,
        NEW.compensacion_geografica,
        NEW.compensacion_transitoria,
        NEW.fecha_inicio,
        NEW.fecha_fin,
        NEW.exc_art_14,
        NEW.unidad_retributiva
        );
END IF;
END$$
DELIMITER ;

DROP TABLE IF EXISTS `perfil_evaluaciones`;
CREATE TABLE IF NOT EXISTS `empleado_evaluaciones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`),
    INDEX `fk_evaluaciones_2_idx` (`id_empleado` ASC, `id_perfil` ASC, `anio` ASC),
  CONSTRAINT `fk_evaluaciones_2`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


DELIMITER $$
DROP TRIGGER IF EXISTS perfil_evaluaciones_AFTER_INSERT$$
DROP TRIGGER IF EXISTS empleado_evaluaciones_AFTER_INSERT$$
CREATE DEFINER = CURRENT_USER TRIGGER `empleado_evaluaciones_AFTER_INSERT` AFTER INSERT ON `empleado_evaluaciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_evaluaciones
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_evaluaciones`,
    `id_perfil`,
    `id_empleado`,
    `acto_administrativo`,
    `evaluacion` ,
    `anio` ,
    `archivo` ,
    `fecha_evaluacion` ,
    `formulario` ,
    `puntaje` ,
    `bonificado` ,
    `borrado` 
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.id_perfil,
    NEW.id_empleado,
    NEW.acto_administrativo,
    NEW.evaluacion,
    NEW.anio,
    NEW.archivo,
    NEW.fecha_evaluacion,
    NEW.formulario,
    NEW.puntaje,
    NEW.bonificado,
    NEW.borrado
);
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS perfil_evaluaciones_AFTER_UPDATE$$
DROP TRIGGER IF EXISTS empleado_evaluaciones_AFTER_UPDATE$$
CREATE DEFINER = CURRENT_USER TRIGGER `empleado_evaluaciones_AFTER_UPDATE` AFTER UPDATE ON `empleado_evaluaciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_evaluaciones
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_evaluaciones`,
    `id_perfil`,
    `id_empleado`,
    `acto_administrativo`,
    `evaluacion` ,
    `anio` ,
    `archivo` ,
    `fecha_evaluacion` ,
    `formulario` ,
    `puntaje` ,
    `bonificado` ,
    `borrado` 
)
VALUES
(
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.id_perfil,
    NEW.id_empleado,
    NEW.acto_administrativo,
    NEW.evaluacion,
    NEW.anio,
    NEW.archivo,
    NEW.fecha_evaluacion,
    NEW.formulario,
    NEW.puntaje,
    NEW.bonificado,
    NEW.borrado
);
END$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `designacion_transitoria` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`id_empleado` INT(11) NOT NULL,
`fecha_desde` DATE NULL DEFAULT NULL,
`fecha_hasta` DATE NULL DEFAULT NULL,
`archivo` VARCHAR(100) NULL DEFAULT NULL,
`tipo` TINYINT(1) NOT NULL DEFAULT '1',
`borrado` TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


DELIMITER $$
DROP TRIGGER IF EXISTS designacion_transitoria_AFTER_INSERT$$

CREATE DEFINER = CURRENT_USER TRIGGER `designacion_transitoria_AFTER_INSERT` AFTER INSERT ON `designacion_transitoria` FOR EACH ROW
BEGIN

INSERT INTO sigarhu_historial.designacion_transitoria
(
    
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_designacion_transitoria`,
    `id_empleado`,
    `fecha_desde`,
    `fecha_hasta`,
    `archivo`,
    `tipo`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.id_empleado,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.archivo,
    NEW.tipo,
    NEW.borrado
);


END$$
DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS designacion_transitoria_AFTER_UPDATE$$

CREATE DEFINER = CURRENT_USER TRIGGER `designacion_transitoria_AFTER_UPDATE` AFTER UPDATE ON `designacion_transitoria` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.designacion_transitoria
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_designacion_transitoria`,
    `id_empleado`,
    `fecha_desde`,
    `fecha_hasta`,
    `archivo`,
    `tipo`,
    `borrado`
)
VALUES
(
   
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.id_empleado,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.archivo,
    NEW.tipo,
    NEW.borrado
);

END$$
DELIMITER ;


CREATE TABLE IF NOT EXISTS `cursos_snc` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(45) NOT NULL,
  `nombre_curso` VARCHAR(450) NOT NULL,
  `creditos` INT NOT NULL,
  `borrado` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`));


CREATE TABLE IF NOT EXISTS `empleado_cursos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `id_curso` INT(11) NOT NULL,
  `fecha` DATE NOT NULL,
  `tipo_promocion` TINYINT(1) NULL DEFAULT 1,
  `borrado` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_empleado_cursos_1_idx` (`id_empleado` ASC),
  INDEX `fk_empleado_cursos_2_idx` (`id_curso` ASC),
  CONSTRAINT `fk_empleado_cursos_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_cursos_2`
    FOREIGN KEY (`id_curso`)
    REFERENCES `cursos_snc` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER $$
DROP TRIGGER IF EXISTS empleado_cursos_AFTER_INSERT$$

CREATE DEFINER = CURRENT_USER TRIGGER `empleado_cursos_AFTER_INSERT` AFTER INSERT ON `empleado_cursos` FOR EACH ROW
BEGIN

INSERT INTO sigarhu_historial.empleado_cursos
(
    
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_cursos`,
    `id_empleado`,
    `id_curso`,
    `fecha`,
    `tipo_promocion`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.id_empleado,
    NEW.id_curso,
    NEW.fecha,
    NEW.tipo_promocion,
    NEW.borrado
);


END$$
DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS empleado_cursos_AFTER_UPDATE$$

CREATE DEFINER = CURRENT_USER TRIGGER `empleado_cursos_AFTER_UPDATE` AFTER UPDATE ON `empleado_cursos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_cursos
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_cursos`,
    `id_empleado`,
    `id_curso`,
    `fecha`,
    `tipo_promocion`,
    `borrado`
)
VALUES
(
   
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.id_empleado,
    NEW.id_curso,
    NEW.fecha,
    NEW.tipo_promocion,
    NEW.borrado
);

END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS cursos_snc_AFTER_INSERT$$

CREATE DEFINER = CURRENT_USER TRIGGER `cursos_snc_AFTER_INSERT` AFTER INSERT ON `cursos_snc` FOR EACH ROW
BEGIN

INSERT INTO sigarhu_historial.cursos_snc
(
    
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_cursos_snc`,
    `codigo`,
    `nombre_curso`,
    `creditos`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.codigo,
    NEW.nombre_curso,
    NEW.creditos,
    NEW.borrado
);


END$$
DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS cursos_snc_AFTER_UPDATE$$

CREATE DEFINER = CURRENT_USER TRIGGER `cursos_snc_AFTER_UPDATE` AFTER UPDATE ON `cursos_snc` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.cursos_snc
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_cursos_snc`,
    `codigo`,
    `nombre_curso`,
    `creditos`,
    `borrado`
)
VALUES
(
   
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.codigo,
    NEW.nombre_curso,
    NEW.creditos,
    NEW.borrado
);

END$$
DELIMITER ;


CREATE OR REPLACE ALGORITHM = UNDEFINED DEFINER = `root`@`localhost` SQL SECURITY DEFINER
VIEW `tv_empleado_cursos` AS
    SELECT 
        `__ec`.`id_empleado` AS `id_empleado`,
        GROUP_CONCAT('["Nombre":"',
            `__c`.`nombre_curso`,'","Créditos":"',
            `__c`.`creditos`,'","Fecha":"',
            DATE_FORMAT(`__ec`.`fecha`, '%d/%m/%Y'), '"]'
            SEPARATOR ',') AS 'cursos'
    FROM
        (`empleado_cursos` `__ec`
        JOIN `cursos_snc` `__c` ON (((`__c`.`id` = `__ec`.`id_curso`)
            AND (`__c`.`borrado` = 0))))
    WHERE
        (`__ec`.`borrado` = 0)
    GROUP BY `__ec`.`id_empleado`;


CREATE TABLE IF NOT EXISTS `persona_titulo_creditos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona_titulo` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `acto_administrativo` varchar(255) NOT NULL,
  `creditos` varchar(45) NOT NULL,
  `archivo` varchar(45) DEFAULT NULL,
  `estado_titulo` tinyint(1) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE=InnoDB  
DEFAULT CHARACTER SET = utf8;


DELIMITER $$
DROP TRIGGER IF EXISTS persona_titulo_creditos_AFTER_INSERT$$

CREATE DEFINER = CURRENT_USER TRIGGER `persona_titulo_creditos_AFTER_INSERT` AFTER INSERT ON `persona_titulo_creditos` FOR EACH ROW
BEGIN

INSERT INTO sigarhu_historial.persona_titulo_creditos
(
    
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_titulo_creditos`,
    `id_persona_titulo`,
    `id_persona`,
    `fecha`,
    `acto_administrativo`,
    `creditos`,
    `archivo`,
    `estado_titulo`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.id_persona_titulo,
    NEW.id_persona,
    NEW.fecha,
    NEW.acto_administrativo,
    NEW.creditos,
    NEW.archivo,
    NEW.estado_titulo,
    NEW.borrado
);


END$$
DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS persona_titulo_creditos_AFTER_UPDATE$$

CREATE DEFINER = CURRENT_USER TRIGGER `persona_titulo_creditos_AFTER_UPDATE` AFTER UPDATE ON `persona_titulo_creditos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_titulo_creditos
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_titulo_creditos`,
    `id_persona_titulo`,
    `id_persona`,
    `fecha`,
    `acto_administrativo`,
    `creditos`,
    `archivo`,
    `estado_titulo`,
    `borrado`
)
VALUES
(
   
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.id_persona_titulo,
    NEW.id_persona,
    NEW.fecha,
    NEW.acto_administrativo,
    NEW.creditos,
    NEW.archivo,
    NEW.estado_titulo,
    NEW.borrado
);

END$$
DELIMITER ;

CREATE OR REPLACE ALGORITHM = UNDEFINED DEFINER = `root`@`localhost` SQL SECURITY DEFINER
VIEW `tv_designacion_transitoria` AS
    SELECT 
        `dt`.`id` AS `id`,
        `dt`.`id_empleado` AS `id_empleado`,
        `dt`.`fecha_desde` AS `fecha_desde`,
        `dt`.`fecha_hasta` AS `fecha_hasta`,
        REPLACE(REPLACE(REPLACE(`dt`.`tipo`, 2, 'Prorroga'),
                1,
                'Transitoria'),
            3,
            'Ninguna') AS `tipo`
    FROM
        (`designacion_transitoria` `dt`
        LEFT JOIN `empleados` `e` ON (((`e`.`id` = `dt`.`id_empleado`)
            AND (`e`.`borrado` = 0))))
    WHERE
        (`dt`.`borrado` = 0)
    ORDER BY `dt`.`fecha_desde` DESC;

CREATE OR REPLACE ALGORITHM = UNDEFINED DEFINER = `root`@`localhost` SQL SECURITY DEFINER
VIEW `tv_persona_experiencia_laboral` AS
   SELECT `__pel`.`id_persona` AS `id_persona`,
       GROUP_CONCAT('["Entidad":"',
            `__oo`.`nombre`,'","Periodo":',
            CONCAT(DATE_FORMAT(`__pel`.`fecha_desde`,'%d/%m/%Y'),'-', DATE_FORMAT(`__pel`.`fecha_hasta`,'%d/%m/%Y')),
            '"]' SEPARATOR ',') AS `experiencia` 
    FROM (`persona_experiencia_laboral` `__pel` 
        JOIN `otros_organismos` `__oo` ON (`__oo`.`id` = `__pel`.`id_entidad` AND `__pel`.`borrado` = 0)) 
    GROUP BY `__pel`.`id_persona` ;

CREATE OR REPLACE ALGORITHM = UNDEFINED  DEFINER = `root`@`localhost` SQL SECURITY DEFINER
VIEW `sigarhu`.`tv_persona_otros_estudios` AS
    SELECT 
        `persona_otros_conocimientos`.`id_persona` AS `id_persona`,
        `persona_otros_conocimientos`.`id_tipo` AS `id_tipo`,
        GROUP_CONCAT('["',
            `persona_otros_conocimientos`.`descripcion`,
            '","',
            IF(`persona_otros_conocimientos`.`fecha` IS NOT NULL,
                DATE_FORMAT(`persona_otros_conocimientos`.`fecha`,
                        '%d/%m/%Y'),
                ''),
            '"]'
            SEPARATOR ',') AS `otros_e_c`
    FROM `persona_otros_conocimientos`
    WHERE (`persona_otros_conocimientos`.`borrado` = 0)
    GROUP BY `persona_otros_conocimientos`.`id_tipo` , `persona_otros_conocimientos`.`id_persona`;

CREATE 
    OR REPLACE ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `sigarhu`.`tv_empleado_anticorrupcion_max_fecha_presentacion` AS
    SELECT 
        MAX(`_ap`.`fecha_presentacion`) AS `max_fecha_presentacion`,
            `_ap`.`id_anticorrupcion` AS `id_anticorrupcion`
    FROM
        `sigarhu`.`anticorrupcion_presentacion` AS `_ap`
    WHERE
        (`_ap`.`borrado` = 0)
    GROUP BY `_ap`.`id_anticorrupcion`;

CREATE 
    OR REPLACE ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `sigarhu`.`tv_empleado_anticorrupcion` AS
    SELECT 
        `a`.`id` AS `id`,
        `a`.`id_empleado` AS `id_empleado`,
        `a`.`fecha_designacion` AS `fecha_designacion`,
        `a`.`fecha_publicacion_designacion` AS `fecha_publicacion_designacion`,
        `a`.`fecha_aceptacion_renuncia` AS `fecha_aceptacion_renuncia`,
        `ap`.`id` AS `id_presentacion`,
        REPLACE(REPLACE(REPLACE(`ap`.`tipo_presentacion`,
                    2,
                    'Anual'),
                1,
                'Inicial'),
            3,
            'Baja') AS `tipo_presentacion`,
        `ap`.`fecha_presentacion` AS `fecha_presentacion`,
        `ap`.`periodo` AS `periodo`,
        `ap`.`nro_transaccion` AS `nro_transaccion`
    FROM
        ((`sigarhu`.`anticorrupcion` `a`
        LEFT JOIN `sigarhu`.`tv_empleado_anticorrupcion_max_fecha_presentacion` `__ap` ON ((`__ap`.`id_anticorrupcion` = `a`.`id`)))
        LEFT JOIN `sigarhu`.`anticorrupcion_presentacion` `ap` ON (((`a`.`id` = `ap`.`id_anticorrupcion`)
            AND (`ap`.`borrado` = 0)
            AND (`ap`.`fecha_presentacion` = `__ap`.`max_fecha_presentacion`))))
    WHERE
        ((`a`.`borrado` = 0))
    ORDER BY `ap`.`tipo_presentacion` DESC , `ap`.`periodo` DESC;

SET FOREIGN_KEY_CHECKS=0;
INSERT INTO db_version VALUES('20.0', now());
SET FOREIGN_KEY_CHECKS=1;