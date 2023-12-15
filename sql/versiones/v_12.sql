ALTER TABLE `empleado_horarios` 
DROP FOREIGN KEY `fk_empleado_horarios_1`;
ALTER TABLE `empleado_horarios` 
DROP INDEX `id_empleado_UNIQUE`;

ALTER TABLE `empleado_horarios` 
ADD INDEX `fk_empleado_horarios_1_idx` (`id_empleado` ASC);
;
ALTER TABLE `empleado_horarios` 
ADD CONSTRAINT `fk_empleado_horarios_1`
  FOREIGN KEY (`id_empleado`)
  REFERENCES `empleados` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

CREATE TABLE IF NOT EXISTS `convenio_unidades_retributivas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nivel` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `maximo` int(10) unsigned NOT NULL,
  `minimo` int(10) unsigned DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_nivel` (`id_nivel`,`id_grado`,`borrado`,`fecha_inicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `convenio_ur_montos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nivel` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `monto` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_nivel` (`id_nivel`,`id_grado`,`borrado`,`fecha_inicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `empleado_escalafon` 
ADD COLUMN `unidad_retributiva` INT(11) NULL DEFAULT NULL AFTER `exc_art_14`;


/*########################### ATENCIÓN ###########################*/
/*#### SI LA BASE DE HISTORIAL NO SE LLAMA "sigarhu_historial"####*/
/*####### SE DEBERÁ CAMBIAR EN LOS SCRIPTS DE LOS TRIGGERS #######*/
/*################################################################*/

DELIMITER $$
DROP TRIGGER IF EXISTS convenio_unidades_retributivas_alta$$
CREATE TRIGGER `convenio_unidades_retributivas_alta` AFTER INSERT ON `convenio_unidades_retributivas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_unidades_retributivas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_unidades_retributivas`,
	`id_nivel`,
	`id_grado`,
	`maximo`,
	`minimo`,
	`fecha_inicio`,
	`fecha_fin`,
	`borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_nivel,
	NEW.id_grado,
	NEW.maximo,
	NEW.minimo,
	NEW.fecha_inicio,
	NEW.fecha_fin,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS convenio_unidades_retributivas_modificacion$$
CREATE TRIGGER `convenio_unidades_retributivas_modificacion` AFTER UPDATE ON `convenio_unidades_retributivas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_unidades_retributivas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_unidades_retributivas`,
	`id_nivel`,
	`id_grado`,
	`maximo`,
	`minimo`,
	`fecha_inicio`,
	`fecha_fin`,
	`borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_nivel,
	NEW.id_grado,
	NEW.maximo,
	NEW.minimo,
	NEW.fecha_inicio,
	NEW.fecha_fin,
    NEW.borrado
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS convenio_ur_montos_alta$$
CREATE TRIGGER `convenio_ur_montos_alta` AFTER INSERT ON `convenio_ur_montos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_ur_montos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_ur_montos`,
	`id_nivel`,
	`id_grado`,
	`monto`,
	`fecha_inicio`,
	`fecha_fin`,
	`borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_nivel,
	NEW.id_grado,
	NEW.monto,
	NEW.fecha_inicio,
	NEW.fecha_fin,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS convenio_ur_montos_modificacion$$
CREATE TRIGGER `convenio_ur_montos_modificacion` AFTER UPDATE ON `convenio_ur_montos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_ur_montos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_ur_montos`,
	`id_nivel`,
	`id_grado`,
	`monto`,
	`fecha_inicio`,
	`fecha_fin`,
	`borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_nivel,
	NEW.id_grado,
	NEW.monto,
	NEW.fecha_inicio,
	NEW.fecha_fin,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `empleado_escalafon_tg_alta`$$
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
    `ultimo_cambio_nivel`,
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
    NEW.ultimo_cambio_nivel,
    NEW.exc_art_14,
	NEW.unidad_retributiva
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS `empleado_escalafon_tg_modificacion`$$
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
    NEW.ultimo_cambio_nivel <> OLD.ultimo_cambio_nivel OR
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
        `ultimo_cambio_nivel`,
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
        NEW.ultimo_cambio_nivel,
        NEW.exc_art_14,
        NEW.unidad_retributiva
        );
END IF;
END$$
DELIMITER ;


-- =========
-- nivel
-- 35 Asesor
-- 36 Asistente
-- 37 Consultor
-- 38 Asistente Operador
-- =========
-- grado
-- 33	I
-- 34	II

INSERT INTO `convenio_unidades_retributivas` 
(`id_nivel`,`id_grado`, `minimo`, `maximo`,`fecha_inicio`) VALUES 
('35', '33', '1200', '1500', '2018-01-01'),   -- 35 Asesor
('35', '34', '1600', '2000', '2018-01-01'),  -- 35 Asesor
('36', '33', '400', '550', '2018-01-01'), -- 36 Asistente
('36', '34', '800', '1000', '2018-01-01'), -- 36 Asistente
('37', '33', '2400', '3000', '2018-01-01'), -- 37 Consultor
('37', '34', '3000', '3750', '2018-01-01'), -- 37 Consultor
('38', '33', '400', '550', '2018-01-01'), -- 38 Asistente Operador
('38', '34', '800', '1000', '2018-01-01'); -- 38 Asistente Operador

INSERT INTO `convenio_ur_montos` 
(`id_nivel`,`id_grado`,`monto`,`fecha_inicio`, `fecha_fin`) VALUES 
('35', '33', '31.71', '2018-02-20', '2019-06-21'),
('35', '34', '31.71', '2018-02-20', '2019-06-21'),
('36', '33', '31.71', '2018-02-20', '2019-06-21'),
('36', '34', '31.71', '2018-02-20', '2019-06-21'),
('37', '33', '31.71', '2018-02-20', '2019-06-21'),
('37', '34', '31.71', '2018-02-20', '2019-06-21'),
('38', '33', '31.71', '2018-02-20', '2019-06-21'),
('38', '34', '31.71', '2018-02-20', '2019-06-21');

INSERT INTO `convenio_ur_montos` 
(`id_nivel`,`id_grado`,`monto`,`fecha_inicio`) VALUES 
('35', '33', '36.47', '2019-06-21'),
('35', '34', '36.47', '2019-06-21'),
('36', '33', '36.47', '2019-06-21'),
('36', '34', '36.47', '2019-06-21'),
('37', '33', '36.47', '2019-06-21'),
('37', '34', '36.47', '2019-06-21'),
('38', '33', '36.47', '2019-06-21'),
('38', '34', '36.47', '2019-06-21');


INSERT INTO db_version VALUES('12.0', now());
