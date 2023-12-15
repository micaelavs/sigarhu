USE `{{{db_log}}}`;

DROP TABLE IF EXISTS `ubicaciones_api`;

CREATE TABLE `ubicaciones_api` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) NOT NULL,
  `fecha_operacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `tipo_operacion` VARCHAR(1) NOT NULL,
  `id_ubicacion_api_id` INT(11) NOT NULL,
  `id_ubicacion_api` INT(11) NOT NULL,
  `id_ubicacion` INT(11) NULL DEFAULT NULL,
  `nombre_api` VARCHAR(50) NOT NULL,
  `calle` VARCHAR(150) NOT NULL,
  `fecha` TIMESTAMP NOT NULL,
  `borrado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`));


DROP TRIGGER IF EXISTS `{{{db_log}}}`.`ubicaciones_api_tg_insert`;

DELIMITER $$
USE `{{{db_log}}}`$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `ubicaciones_api_tg_insert` AFTER INSERT ON `ubicaciones_api` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'ubicaciones_api');
END$$
DELIMITER ;

ALTER TABLE `{{{db_log}}}`.`_registros_abm` 
CHANGE COLUMN `tabla_nombre` `tabla_nombre` ENUM('comisiones', 'convenio_agrupamientos', 'convenio_funciones_ejecutivas', 'convenio_grados', 'convenio_modalidad_vinculacion', 'convenio_niveles', 'convenio_situacion_revista', 'convenio_tramos', 'convenio_unidades_retributivas', 'convenio_ur_montos', 'denominacion_funcion', 'denominacion_puesto', 'dependencias', 'dependencias_informales', 'licencias_especiales', 'motivo_baja', 'nivel_educativo', 'obras_sociales', 'perfil_tarea', 'plantilla_horarios', 'presupuesto_actividades', 'presupuesto_jurisdicciones', 'presupuesto_obras', 'presupuesto_proyectos', 'presupuesto_programas', 'presupuesto_subprogramas', 'presupuesto_saf', 'presupuesto_ubicaciones_geograficas', 'presupuestos', 'puestos', 'seguro_vida', 'sindicatos', 'subfamilia_puestos', 'tipo_discapacidad', 'titulo', 'ubicacion_edificios', 'ubicaciones', 'dependencias_historicas', 'cursos_snc', 'ubicaciones_api') NULL DEFAULT NULL ;


USE `{{{db_app}}}`;

DROP TABLE IF EXISTS `ubicaciones_api`;

CREATE TABLE `ubicaciones_api` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_ubicacion_api` INT(11) NOT NULL,
  `id_ubicacion` INT(11) NULL DEFAULT NULL,
  `nombre_api` VARCHAR(50) NULL DEFAULT NULL,
  `calle` VARCHAR(150) NULL DEFAULT NULL,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `borrado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`));

DROP TRIGGER IF EXISTS `{{{db_app}}}`.`ubicaciones_api_alta`;

DELIMITER $$
USE `{{{db_app}}}`$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `ubicaciones_api_alta` AFTER INSERT ON `ubicaciones_api` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_aud.ubicaciones_api
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_ubicacion_api_id`,
    `id_ubicacion_api`,
    `id_ubicacion`,
    `nombre_api`,
    `calle`,
    `fecha`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_ubicacion_api,
    NEW.id_ubicacion,
    NEW.nombre_api,
    NEW.calle,
    NEW.fecha,
    NEW.borrado
    );
END$$
DELIMITER ;


DROP TRIGGER IF EXISTS `{{{db_app}}}`.`ubicaciones_api_tg_modificacion`;

DELIMITER $$
USE `{{{db_app}}}`$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `ubicaciones_api_tg_modificacion` AFTER UPDATE ON `ubicaciones_api` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_aud.ubicaciones_api (
	`id_usuario`,
	`fecha_operacion`,
	`tipo_operacion`,
	`id_ubicacion_api_id`,
	`id_ubicacion_api`,
	`id_ubicacion`,
	`nombre_api`,
	`calle`,
	`fecha`,
	`borrado`)
VALUES (
	@id_usuario,
	CURRENT_TIMESTAMP(),
	IF(NEW.borrado = 1, "B", "M"),
	OLD.id,
	NEW.id_ubicacion_api,
	NEW.id_ubicacion,
	NEW.nombre_api,
	NEW.calle,
	NEW.fecha,
	NEW.borrado);
END$$
DELIMITER ;


--cambios en db_log

USE `{{{db_log}}}`;

ALTER TABLE `ubicaciones_api` 
ADD COLUMN `id_localidad` INT(11) NULL DEFAULT NULL AFTER `calle`,
ADD COLUMN `id_region` INT(11) NULL DEFAULT NULL AFTER `id_localidad`;


/*cambios en db*/
USE `{{{db_app}}}`;

ALTER TABLE `ubicaciones_api` 
ADD COLUMN `id_localidad` INT(11) NULL DEFAULT NULL AFTER `calle`,
ADD COLUMN `id_region` INT(11) NULL DEFAULT NULL AFTER `id_localidad`;

DROP TRIGGER IF EXISTS `{{{db_app}}}`.`ubicaciones_api_alta`;

DELIMITER $$
USE `{{{db_app}}}`$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `ubicaciones_api_alta` AFTER INSERT ON `ubicaciones_api` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_aud.ubicaciones_api
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_ubicacion_api_id`,
    `id_ubicacion_api`,
    `id_ubicacion`,
    `nombre_api`,
    `calle`,
    `id_localidad`,
    `id_region`,
    `fecha`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_ubicacion_api,
    NEW.id_ubicacion,
    NEW.nombre_api,
    NEW.calle,
    NEW.id_localidad,
    NEW.id_region,
    NEW.fecha,
    NEW.borrado
    );
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `{{{db_app}}}`.`ubicaciones_api_tg_modificacion`;

DELIMITER $$
USE `{{{db_app}}}`$$
CREATE DEFINER=`{{{user_mysql}}}`@`%` TRIGGER `ubicaciones_api_tg_modificacion` AFTER UPDATE ON `ubicaciones_api` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_aud.ubicaciones_api (
  `id_usuario`,
  `fecha_operacion`,
  `tipo_operacion`,
  `id_ubicacion_api_id`,
  `id_ubicacion_api`,
  `id_ubicacion`,
  `nombre_api`,
  `calle`,
  `id_localidad`,
  `id_region`,
  `fecha`,
  `borrado`)
VALUES (
  @id_usuario,
  CURRENT_TIMESTAMP(),
  IF(NEW.borrado = 1, "B", "M"),
  OLD.id,
  NEW.id_ubicacion_api,
  NEW.id_ubicacion,
  NEW.nombre_api,
  NEW.calle,
  NEW.id_localidad,
  NEW.id_region,
  NEW.fecha,
  NEW.borrado);
END$$
DELIMITER ;




USE `{{{db_app}}}`;

--1) Inserto las ubicaciones provenientes de locaciones en la tabla para comparar

set @id_usuario = 99999;
insert  into `ubicaciones_api`(`id`,`id_ubicacion_api`,`id_ubicacion`,`nombre_api`,`calle`,`id_localidad`,`id_region`,`fecha`,`borrado`) values 
(1,8,12,'Bahia Blanca','Guillermo Torres',12,2,'2023-01-17 12:05:49',0),
(2,1,15,'PC 315','Av. Paseo Colon',1,3,'2023-01-17 12:05:49',0),
(3,2,1,'Hacienda','Av. Hipólito Yrigoyen',1,3,'2023-01-17 12:05:49',0),
(4,3,5,'España','Av. España',1,3,'2023-01-17 12:05:49',0),
(5,4,13,'Moreno','Moreno',1,3,'2023-01-17 12:05:49',0),
(6,5,28,'Constitución','Brasil',1,3,'2023-01-17 12:05:49',0),
(7,6,NULL,'Isla Demarchi','Benjamin Juan Lavaisse',1,3,'2023-01-17 12:05:49',0),
(8,7,22,'AGP Base Logistica','Benjamin Juan Lavaisse',1,3,'2023-01-17 12:05:49',0),
(9,11,20,'Paraná Medio','Liniers',880,5,'2023-01-17 12:05:49',0),
(10,13,23,'Concepción del Uruguay','Jordana',812,5,'2023-01-17 12:05:49',0),
(11,10,21,'Paraná Inferior','Blvd. 27 de Febrero',1776,17,'2023-01-17 12:05:49',0),
(12,12,18,'Paraná Superior','Av. SanMartin',609,21,'2023-01-17 12:05:49',0),
(13,9,6,'Quequen','Av. Juan de Garay',2065,2,'2023-01-17 12:05:49',0);

