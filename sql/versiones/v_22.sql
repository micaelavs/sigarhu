#SIEMPRE SE ACTUALIZARA LA VERSIÓN DE LAS DOS DBs AUNQUE NO SE HAGAN CAMBIOS.
#REMPLAZAR ANTES DE EJECUTAR
# {{{user_mysql}}}  = REEMPLAZAR POR NOMBRE USER QUE EJECUTA.
# {{{db_log}}}      = REEMPLAZAR POR NOMBRE DB LOG/HISTORIAL.
# {{{db_app}}}      = REEMPLAZAR POR NOMBRE DB APP.

USE `{{{db_app}}}`;
SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `empleado_cursos` 
ADD COLUMN `tipo_promocion` TINYINT(1) NULL DEFAULT 1 AFTER `fecha`;


DROP TRIGGER IF EXISTS `empleado_cursos_AFTER_INSERT`;

DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_cursos_AFTER_INSERT` AFTER INSERT ON `empleado_cursos` FOR EACH ROW BEGIN
INSERT INTO {{{db_log}}}.empleado_cursos
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

DROP TRIGGER IF EXISTS `empleado_cursos_AFTER_UPDATE`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_cursos_AFTER_UPDATE` AFTER UPDATE ON `empleado_cursos` FOR EACH ROW BEGIN
INSERT INTO {{{db_log}}}.empleado_cursos
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



CREATE TABLE `empleado_historial_creditos` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL, INDEX `index_empleado` (`id_empleado` ASC),
  `id_tabla` BIGINT(20) NOT NULL, INDEX `index_id_tabla` (`id_tabla` ASC),
  `tabla_nombre` VARCHAR(45) NOT NULL, INDEX `index_tabla` (`tabla_nombre` ASC),
  `creditos_agregados` INT(50) UNSIGNED NULL DEFAULT 0,
  `creditos_descontados` INT(50) UNSIGNED NULL DEFAULT 0,
  `creditos_disponibles` INT(50) UNSIGNED NULL DEFAULT 0,
  `porcentaje` INT(50) UNSIGNED NULL DEFAULT 0,
  `fecha_considerada` DATE NULL DEFAULT NULL, INDEX `index_fecha` (`fecha_considerada` ASC),
  `fecha_operacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo_promocion` TINYINT(1) NULL DEFAULT 1,
  `borrado` TINYINT(1) NULL DEFAULT 0, INDEX `index_borrado` (`borrado` ASC),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));


DROP TRIGGER IF EXISTS `empleado_historial_creditos_AFTER_INSERT`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%`  TRIGGER `empleado_historial_creditos_AFTER_INSERT` AFTER INSERT ON `empleado_historial_creditos`
FOR EACH ROW BEGIN

INSERT INTO {{{db_log}}}.empleado_historial_creditos
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_historial_creditos`,
    `id_empleado`,
    `id_tabla`,
    `tabla_nombre`,
    `creditos_agregados`,
    `creditos_descontados`,
    `creditos_disponibles`,
    `porcentaje`,
    `fecha_considerada`,
    `fecha_operacion_historial_creditos`,
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
    NEW.id_tabla,
    NEW.tabla_nombre,
    NEW.creditos_agregados,
    NEW.creditos_descontados,
    NEW.creditos_disponibles,
    NEW.porcentaje,
    NEW.fecha_considerada,
    NEW.fecha_operacion,
    NEW.tipo_promocion,
    NEW.borrado
);
END$$
DELIMITER ;


DROP TRIGGER IF EXISTS `empleado_historial_creditos_AFTER_UPDATE`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_historial_creditos_AFTER_UPDATE` AFTER UPDATE ON `empleado_historial_creditos` FOR EACH ROW BEGIN
INSERT INTO {{{db_log}}}.empleado_historial_creditos
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_historial_creditos`,
    `id_empleado`,
    `id_tabla`,
    `tabla_nombre`,
    `creditos_agregados`,
    `creditos_descontados`,
    `creditos_disponibles`,
    `porcentaje`,
    `fecha_considerada`,
    `fecha_operacion_historial_creditos`,
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
    NEW.id_tabla,
    NEW.tabla_nombre,
    NEW.creditos_agregados,
    NEW.creditos_descontados,
    NEW.creditos_disponibles,
    NEW.porcentaje,
    NEW.fecha_considerada,
    NEW.fecha_operacion,
    NEW.tipo_promocion,
    NEW.borrado
);
END$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `promocion_creditos` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`fecha_desde` DATE NOT NULL, INDEX `IDX_promocion_creditos_fecha_desde` (`fecha_desde` ASC),
	`fecha_hasta` DATE NULL, INDEX `IDX_promocion_creditos_fecha_hasta` (`fecha_hasta` ASC),
    `id_nivel` int(11) NOT NULL,
    `id_tramo` int(11) NOT NULL,
	`creditos` INT NOT NULL,
    `borrado` TINYINT(1) DEFAULT 0, INDEX `IDX_promocion_creditos_borrado` (`borrado` ASC),
PRIMARY KEY (`id`),
KEY `fk_promocion_creditos_1_idx` (`id_nivel`),
KEY `fk_promocion_creditos_2_idx` (`id_tramo`),
CONSTRAINT `fk_promocion_creditos_1` FOREIGN KEY (`id_nivel`) REFERENCES `convenio_niveles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT `fk_promocion_creditos_2` FOREIGN KEY (`id_tramo`) REFERENCES `convenio_tramos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER $$
DROP TRIGGER IF EXISTS promocion_creditos_tg_alta$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `promocion_creditos_tg_alta` AFTER INSERT ON `promocion_creditos` FOR EACH ROW
BEGIN
INSERT INTO {{{db_log}}}.promocion_creditos
    (
    `id_promocion_creditos`,
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
  	`fecha_desde`,
	`fecha_hasta`,
	`id_nivel`,
	`id_tramo`,
	`creditos`
    )
VALUES
    (
    NEW.id,
    @id_usuario,
    NOW(),
    "A",
    NEW.fecha_desde,
	NEW.fecha_hasta,
	NEW.id_nivel,
	NEW.id_tramo,
	NEW.creditos
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS promocion_creditos_tg_modificacion$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `promocion_creditos_tg_modificacion` AFTER UPDATE ON `promocion_creditos` FOR EACH ROW
BEGIN
INSERT INTO {{{db_log}}}.promocion_creditos
    (
    `id_promocion_creditos`,
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `fecha_desde`,
	`fecha_hasta`,
	`id_nivel`,
	`id_tramo`,
	`creditos`
    )
VALUES
    (
    OLD.id,
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B", "M"),
    NEW.fecha_desde,
	NEW.fecha_hasta,
	NEW.id_nivel,
	NEW.id_tramo,
	NEW.creditos
    );
END$$
DELIMITER ;




CREATE OR REPLACE ALGORITHM = UNDEFINED  DEFINER = `{{{user_mysql}}}`@`%` SQL SECURITY DEFINER
VIEW `tv_empleado_curso_historial_tmp` AS
    SELECT 
        `c_snc`.`creditos` AS `creditos`,
        `ec`.`id_empleado` AS `id_empleado`,
        `ec`.`id` AS `id_tabla`,
        `ec`.`fecha` AS `fecha`,
        `ec`.`tipo_promocion` AS `tipo_promocion`
    FROM
        (`empleado_cursos` `ec`
        JOIN `cursos_snc` `c_snc` ON ((`ec`.`id_curso` = `c_snc`.`id`)))
    WHERE
        ((`ec`.`borrado` = 0)
            AND (`c_snc`.`borrado` = 0)
            AND (NOT (`ec`.`id` IN (SELECT 
                `empleado_historial_creditos`.`id_tabla`
            FROM
                `empleado_historial_creditos`
            WHERE
                (((`empleado_historial_creditos`.`porcentaje` = 0) OR (`empleado_historial_creditos`.`porcentaje` IS NULL))
                    AND (`empleado_historial_creditos`.`borrado` = 0))))));


DROP procedure IF EXISTS `UpdateCreditoCursosHistorico`;
DELIMITER $$
CREATE PROCEDURE UpdateCreditoCursosHistorico()
BEGIN
  DECLARE finished INTEGER DEFAULT 0;
  DECLARE creditos INT(50) DEFAULT 0;
  DECLARE id_empleado BIGINT(20);
  DECLARE id_tabla BIGINT(20);
  DECLARE fecha DATE;
  DECLARE tipo_promocion TINYINT(1);

  DECLARE curCreditos CURSOR FOR select * FROM tv_empleado_curso_historial_tmp;

  DECLARE CONTINUE HANDLER FOR 02 SET finished = 1;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;

  OPEN curCreditos;
  BEGIN
  getCreditos: LOOP
    FETCH curCreditos INTO creditos, id_empleado, id_tabla, fecha, tipo_promocion;
    IF finished = 1 THEN 
      LEAVE getCreditos;
    END IF;
        insert into empleado_historial_creditos (
			`creditos_agregados`,
            `creditos_disponibles`,
            `id_empleado`, 
            `id_tabla`,
            `fecha_considerada`, 
            `tabla_nombre`,
            `tipo_promocion`
		) values (
			creditos, 
            0, 
            id_empleado, 
            id_tabla, 
            fecha, 
            'empleado_cursos', 
            tipo_promocion
		);
    END LOOP getCreditos;
   END;
  CLOSE curCreditos;
END$$
DELIMITER ;


CREATE OR REPLACE ALGORITHM = UNDEFINED DEFINER = `{{{user_mysql}}}`@`%` SQL SECURITY DEFINER
VIEW `tv_empleado_porcentaje_historial_tmp` AS
    SELECT 
        `ptc`.`creditos` AS `creditos`,
        `emp`.`id` AS `empleado_id`,
        `ptc`.`id` AS `id`,
        `ptc`.`fecha` AS `fecha`
    FROM
        (`persona_titulo_creditos` `ptc`
        JOIN `empleados` `emp` ON ((`ptc`.`id_persona` = `emp`.`id_persona`)))
    WHERE
        ((`ptc`.`borrado` = 0)
            AND (NOT (`ptc`.`id` IN (SELECT 
                `empleado_historial_creditos`.`id_tabla`
            FROM
                `empleado_historial_creditos`
            WHERE
                ((`empleado_historial_creditos`.`tabla_nombre` = 'persona_titulo_creditos')
                    AND (`empleado_historial_creditos`.`borrado` = 0))))));

DROP procedure IF EXISTS `UpdatePorcentajeTituloCreditosHistorico`;
DELIMITER $$
CREATE PROCEDURE UpdatePorcentajeTituloCreditosHistorico()
BEGIN
  DECLARE finished INTEGER DEFAULT 0;
  DECLARE porcentaje INT(50) DEFAULT 0;
    DECLARE id_empleado BIGINT(20);
    DECLARE id_tabla BIGINT(20);
    DECLARE fecha DATE;
  -- declaro cursor para creditos de los empleados
  DECLARE curCreditos CURSOR FOR select * FROM tv_empleado_porcentaje_historial_tmp;

  -- declaro NOT FOUND handler
  DECLARE CONTINUE HANDLER FOR 02 SET finished = 1;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
  
  OPEN curCreditos;
  getCreditos: LOOP
    FETCH curCreditos INTO porcentaje, id_empleado, id_tabla, fecha;
    IF finished = 1 THEN 
      LEAVE getCreditos;
    END IF;
    -- inserto creditos en la tabla empleados_historial_creditos
        insert into empleado_historial_creditos (porcentaje, id_empleado, id_tabla, fecha_considerada, tabla_nombre) values (porcentaje, id_empleado, id_tabla, fecha, 'persona_titulo_creditos');
    END LOOP getCreditos;
  CLOSE curCreditos;

END$$
DELIMITER ;

DROP TABLE IF EXISTS `empleado_simulacion_promocion_grado_tmp`;
CREATE TABLE `empleado_simulacion_promocion_grado_tmp` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_empleado` INT NOT NULL,
  `id_empleado_escalafon` INT NOT NULL COMMENT 'Ultimo registro vigente a la fecha',
  `fecha_ultima_promocion` DATE NULL DEFAULT NULL COMMENT 'Ultimo registro vigente a la fecha',
  `grupo_incremental` INT NULL DEFAULT NULL COMMENT 'Es un identificador incremental, que indica el grupo al cual pertence cada registro.',
  `id_motivo` INT NULL DEFAULT NULL COMMENT 'El ID es una constante dentro del codigo',
  `anio` INT NOT NULL COMMENT 'Es un anio de referencia calculado de forma incremental a partir de la fecha de ultima promocion.',
  `id_empleado_evaluacion` INT NULL DEFAULT NULL,
  `bonificado` TINYINT(1) NULL DEFAULT NULL COMMENT '\'0\' NO | \'1\' SI | \'null\' No aplica\n',
  `id_calificacion` INT NULL DEFAULT NULL COMMENT 'El ID es una constante dentro del codigo',
  `grado_analisis` INT NULL DEFAULT NULL COMMENT 'Se incrementa por cada cambio de grupo ',
  `creditos_requeridos` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Valor proveniente de ABM',
  `creditos_reconocidos` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Un porcentaje de los requeridos',
  `porcentaje_reconocido` INT UNSIGNED NULL DEFAULT NULL,
  `total_periodo` INT(11) UNSIGNED NULL DEFAULT 0,
  `creditos_disponibles` INT(11) UNSIGNED NULL DEFAULT 0,
  `creditos_subtotal` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Recuento de creditos aplicados a la fecha de la evaluacion o heredados',
  `id_situacion_revista` INT NULL DEFAULT NULL COMMENT 'Situacion de revista correspondiente al anio de la evaluacion.',
  `id_nivel` INT NULL DEFAULT NULL COMMENT 'Nivel correspondiente al anio de la evaluacion.',
  `id_tramo` INT NULL DEFAULT NULL COMMENT 'Tramo correspondiente al anio de la evaluacion.',
  `id_grado` INT NULL DEFAULT NULL COMMENT 'Grado correspondiente al anio de la evaluacion.',
  `aplica_promocion` TINYINT(1) NULL DEFAULT '0',
  `borrado` TINYINT(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (`id`),
  INDEX `fk_empleado_simulacion_promocion_grado_tmp_1_idx` (`id_empleado` ASC),
  INDEX `fk_empleado_simulacion_promocion_grado_tmp_2_idx` (`id_empleado_escalafon` ASC),
  INDEX `fk_empleado_simulacion_promocion_grado_tmp_3_idx` (`grupo_incremental` ASC, `id_motivo` ASC, `anio` ASC, `borrado` ASC, `id_situacion_revista` ASC, `id_nivel` ASC, `id_grado` ASC),
  INDEX `fk_empleado_simulacion_promocion_grado_tmp_3_idx1` (`id_empleado_evaluacion` ASC),
  CONSTRAINT `fk_empleado_simulacion_promocion_grado_tmp_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_simulacion_promocion_grado_tmp_2`
    FOREIGN KEY (`id_empleado_escalafon`)
    REFERENCES `empleado_escalafon` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_simulacion_promocion_grado_tmp_3`
    FOREIGN KEY (`id_empleado_evaluacion`)
    REFERENCES `empleado_evaluaciones` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

DROP procedure IF EXISTS `truncate_empleado_simulacion_promocion_grado_tmp`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` PROCEDURE `truncate_empleado_simulacion_promocion_grado_tmp` ()
BEGIN
	TRUNCATE `empleado_simulacion_promocion_grado_tmp`;
END$$
DELIMITER ;


CREATE TABLE IF NOT EXISTS `empleado_promociones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) DEFAULT NULL,
  `periodo_inicio` INT(11) DEFAULT NULL,
  `periodo_fin` INT(11) DEFAULT NULL,
  `id_empleado_escalafon` INT(11) DEFAULT NULL,
  `id_grado` INT(11) NOT NULL,
  `id_tipo_promocion` INT(11) DEFAULT '1',
  `creditos_descontados` INT(11) DEFAULT NULL,
  `creditos_reconocidos` INT(11) DEFAULT NULL,
  `creditos_requeridos` INT(11) DEFAULT NULL,
  `numero_expediente` VARCHAR(255) DEFAULT NULL,
  `acto_administrativo` VARCHAR(255) DEFAULT NULL,
  `fecha_promocion` DATE NOT NULL,
  `id_motivo` INT(11) DEFAULT NULL,
  `archivo` VARCHAR(255) DEFAULT NULL,
  `borrado` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `fk_empleado_promociones_1_idx` (`id_empleado` ASC, `borrado` ASC),
  INDEX `fk_empleado_promociones_2_idx` (`id_motivo` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TRIGGER IF EXISTS `empleado_cursos_AFTER_INSERT`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_promocioness_AFTER_INSERT` AFTER INSERT ON `empleado_promociones` FOR EACH ROW BEGIN
INSERT INTO {{{db_log}}}.empleado_promociones
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_promociones`,
    `id_empleado`,
    `periodo_inicio`,
    `periodo_fin`,
    `id_empleado_escalafon`,
    `id_grado`,
    `id_tipo_promocion`,
    `creditos_descontados`,
    `creditos_reconocidos`,
    `creditos_requeridos`,
    `numero_expediente`,
    `acto_administrativo`,
    `fecha_promocion`,
    `id_motivo`,
    `archivo`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.id_empleado,
    NEW.periodo_inicio,
    NEW.periodo_fin,
    NEW.id_empleado_escalafon,
    NEW.id_grado,
    NEW.id_tipo_promocion,
    NEW.creditos_descontados,
    NEW.creditos_reconocidos,
    NEW.creditos_requeridos,
    NEW.numero_expediente,
    NEW.acto_administrativo,
    NEW.fecha_promocion,
    NEW.id_motivo,
    NEW.archivo,
    NEW.borrado
);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `empleado_promociones_AFTER_UPDATE`;
DELIMITER $$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `empleado_promociones_AFTER_UPDATE` AFTER UPDATE ON `empleado_promociones` FOR EACH ROW BEGIN
INSERT INTO {{{db_log}}}.empleado_promociones
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_promociones`,
    `id_empleado`,
    `periodo_inicio`,
    `periodo_fin`,
    `id_empleado_escalafon`,
    `id_grado`,
    `id_tipo_promocion`,
    `creditos_descontados`,
    `creditos_reconocidos`,
    `creditos_requeridos`,
    `numero_expediente`,
    `acto_administrativo`,
    `fecha_promocion`,
    `id_motivo`,
    `archivo`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.periodo_inicio,
    NEW.periodo_fin,
    NEW.id_empleado_escalafon,
    NEW.id_grado,
    NEW.id_tipo_promocion,
    NEW.creditos_descontados,
    NEW.creditos_reconocidos,
    NEW.creditos_requeridos,
    NEW.numero_expediente,
    NEW.acto_administrativo,
    NEW.fecha_promocion,
    NEW.id_motivo,
    NEW.archivo,
    NEW.borrado
);
END$$
DELIMITER ;

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

INSERT INTO db_version VALUES('22.0', now());

SET @id_usuario := 9;
INSERT INTO `promocion_creditos`(fecha_desde,fecha_hasta,id_nivel,id_tramo,creditos,borrado) VALUES 
-- Tramo GENERAL
('2008-12-03',NULL,1,1,40,0),
('2008-12-03',NULL,2,1,40,0),
('2008-12-03',NULL,3,1,40,0),
('2008-12-03',NULL,4,1,40,0),
('2008-12-03',NULL,7,1,40,0),
('2008-12-03',NULL,8,1,40,0),
('2008-12-03',NULL,9,1,40,0),
('2008-12-03',NULL,10,1,40,0),
('2008-12-03',NULL,11,1,40,0),
('2008-12-03',NULL,12,1,40,0),
('2008-12-03',NULL,13,1,40,0),
('2008-12-03',NULL,14,1,40,0),
('2008-12-03',NULL,15,1,40,0),
('2008-12-03',NULL,16,1,40,0),
('2008-12-03',NULL,5,1,35,0),
('2008-12-03',NULL,6,1,35,0),
('2008-12-03',NULL,53,1,35,0),
-- Tramo Intermedio
('2008-12-03',NULL,1,2,56,0),
('2008-12-03',NULL,2,2,56,0),
('2008-12-03',NULL,3,2,56,0),
('2008-12-03',NULL,4,2,56,0),
('2008-12-03',NULL,7,2,56,0),
('2008-12-03',NULL,8,2,56,0),
('2008-12-03',NULL,9,2,56,0),
('2008-12-03',NULL,10,2,56,0),
('2008-12-03',NULL,11,2,56,0),
('2008-12-03',NULL,12,2,56,0),
('2008-12-03',NULL,13,2,56,0),
('2008-12-03',NULL,14,2,56,0),
('2008-12-03',NULL,15,2,56,0),
('2008-12-03',NULL,16,2,56,0),
('2008-12-03',NULL,5,2,40,0),
('2008-12-03',NULL,6,2,40,0),
('2008-12-03',NULL,53,2,40,0),
-- Tramo Avanzado
('2008-12-03',NULL,1,3,72,0),
('2008-12-03',NULL,2,3,72,0),
('2008-12-03',NULL,3,3,72,0),
('2008-12-03',NULL,4,3,72,0),
('2008-12-03',NULL,7,3,72,0),
('2008-12-03',NULL,8,3,72,0),
('2008-12-03',NULL,9,3,72,0),
('2008-12-03',NULL,10,3,72,0),
('2008-12-03',NULL,11,3,72,0),
('2008-12-03',NULL,12,3,72,0),
('2008-12-03',NULL,13,3,72,0),
('2008-12-03',NULL,14,3,72,0),
('2008-12-03',NULL,15,3,72,0),
('2008-12-03',NULL,16,3,72,0),
('2008-12-03',NULL,5,3,48,0),
('2008-12-03',NULL,6,3,48,0),
('2008-12-03',NULL,53,3,48,0);