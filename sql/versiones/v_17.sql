#############################################################
# En SIGARHU_HISTORIAL
#############################################################
USE sigarhu_historial;


ALTER TABLE `empleado_escalafon` DROP `ultimo_cambio_nivel`;


-- ---------------------------
-- Corregir tambien los Triggers de la base principal -- NO TE OLVIDES
-- ---------------------------
ALTER TABLE
  `sigarhu_historial`.`empleados` CHANGE COLUMN `id_empleado` `id_empleados` INT(11) NULL DEFAULT NULL;
ALTER TABLE
  `sigarhu_historial`.`empleado_dep_informales` CHANGE COLUMN `id_empleado_dep_informal` `id_empleado_dep_informales` INT(11) NULL DEFAULT NULL;


-- -----------------------------------------------------
-- Table `otros_organismos`
-- -----------------------------------------------------
CREATE TABLE `otros_organismos` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` DATETIME NOT NULL,
	`tipo_operacion` CHAR(1) NOT NULL,
	`id_otros_organismos` int(11) NOT NULL,
	`nombre` VARCHAR(255) NOT NULL,
	`tipo` TINYINT(1) NOT NULL,
	`jurisdiccion` TINYINT(1) NOT NULL,
	`borrado` TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `experiencia_laboral`
-- -----------------------------------------------------
CREATE TABLE `persona_experiencia_laboral` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` DATETIME NOT NULL,
	`tipo_operacion` CHAR(1) NOT NULL,
	`id_persona_experiencia_laboral` int(11) NOT NULL,
	`id_persona` INT(11) NOT NULL,
	`id_entidad` INT(11) NOT NULL,
	`fecha_desde` DATE NOT NULL,
	`fecha_hasta` DATE NULL DEFAULT NULL,
	`borrado` TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `tipo_documento`
-- -----------------------------------------------------
CREATE TABLE `tipo_documento` (
`id` BIGINT NOT NULL AUTO_INCREMENT,
  -- Campos Historial
  `id_usuario` INT(11) NOT NULL,
  `fecha_operacion` DATETIME NOT NULL,
  `tipo_operacion` CHAR(1) NOT NULL,
  `id_tipo_documento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `empleado_documentos`
-- -----------------------------------------------------
ALTER TABLE `empleado_documentos` 
CHANGE COLUMN `id_bloque` `id_tipo` INT NULL DEFAULT 1 ;



#DROP TABLE IF EXISTS `empleado_ultimos_cambios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_ultimos_cambios` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_ultimos_cambios` int(11),
  `id_empleado` int(11),
  `id_tipo` tinyint(1),
  `id_convenios` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,  
  PRIMARY KEY (`id`),
  KEY `empleado_ultimos_cambios_id_IDX` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




-- ---------------------------
-- Estructura de Registros --
-- ---------------------------
DROP TABLE IF EXISTS _registros_legajos;
CREATE TABLE `_registros_legajos` (
	`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) DEFAULT NULL,
	`fecha_operacion` TIMESTAMP,
	`tipo_operacion` CHAR(1) DEFAULT NULL,
	`id_tabla` BIGINT(20) NOT NULL,
	`id_empleado` INT(11) DEFAULT NULL,
	`tabla_nombre` ENUM('anticorrupcion_presentacion','anticorrupcion','contratante','embargos','empleado_comision','empleado_dep_informales','empleado_dependencia','empleado_documentos','empleado_escalafon','empleado_horarios','empleado_horas_extras','empleado_perfil','empleado_presupuesto','empleado_salud','empleado_seguros','empleado_sindicatos','empleados','empleados_lic_especiales','empleados_x_ubicacion','familiar_discapacidad','grupo_familiar','observaciones','persona_discapacidad','persona_domicilio','persona_otros_conocimientos','persona_telefono','persona_titulo','personas','responsables_contrato','perfil_actividades','perfil_resultado_parc_final') DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `fecha_operacion` (`fecha_operacion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS _registros_abm;
CREATE TABLE `_registros_abm` (
	`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) DEFAULT NULL,
	`fecha_operacion` TIMESTAMP,
	`tipo_operacion` CHAR(1) DEFAULT NULL,
	`id_tabla` BIGINT(20) NOT NULL,
	`tabla_nombre` ENUM('comisiones','convenio_agrupamientos','convenio_funciones_ejecutivas','convenio_grados','convenio_modalidad_vinculacion','convenio_niveles','convenio_situacion_revista','convenio_tramos','convenio_unidades_retributivas','convenio_ur_montos','denominacion_funcion','denominacion_puesto','dependencias','dependencias_informales','licencias_especiales','motivo_baja','nivel_educativo','obras_sociales','perfil_tarea','plantilla_horarios','presupuesto_actividades','presupuesto_jurisdicciones','presupuesto_obras','presupuesto_proyectos','presupuesto_programas','presupuesto_subprogramas','presupuesto_saf','presupuesto_ubicaciones_geograficas','presupuestos','puestos','seguro_vida','sindicatos','subfamilia_puestos','tipo_discapacidad','titulo','ubicacion_edificios','ubicaciones') DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `fecha_operacion` (`fecha_operacion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- --------------------------------
-- Insertar registros de LEGAJO --
-- --------------------------------
INSERT INTO _registros_legajos(id_tabla, id_usuario, fecha_operacion, id_empleado, tipo_operacion, tabla_nombre)
(SELECT antpre.`id` AS id_tabla , antpre.`id_usuario`, antpre.`fecha_operacion`, ant.`id_empleado`, antpre.`tipo_operacion`, CONCAT('anticorrupcion_presentacion') AS `tabla_nombre` FROM `anticorrupcion_presentacion` AS antpre
INNER JOIN anticorrupcion AS ant ON (antpre.id_anticorrupcion = ant.id_anticorrupcion))
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('anticorrupcion') AS `tabla_nombre` FROM `anticorrupcion`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('contratante') AS `tabla_nombre` FROM `contratante`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('embargos') AS `tabla_nombre` FROM `embargos`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_comision') AS `tabla_nombre` FROM `empleado_comision`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_dep_informales') AS `tabla_nombre` FROM `empleado_dep_informales`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_dependencia') AS `tabla_nombre` FROM `empleado_dependencia`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_documentos') AS `tabla_nombre` FROM `empleado_documentos`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_escalafon') AS `tabla_nombre` FROM `empleado_escalafon`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_horarios') AS `tabla_nombre` FROM `empleado_horarios`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_horas_extras') AS `tabla_nombre` FROM `empleado_horas_extras`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_perfil') AS `tabla_nombre` FROM `empleado_perfil`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_presupuesto') AS `tabla_nombre` FROM `empleado_presupuesto`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_salud') AS `tabla_nombre` FROM `empleado_salud`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_seguros') AS `tabla_nombre` FROM `empleado_seguros`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_sindicatos') AS `tabla_nombre` FROM `empleado_sindicatos`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleados` AS id_empleado , `tipo_operacion`, CONCAT('empleados') AS `tabla_nombre` FROM `empleados`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleados_lic_especiales') AS `tabla_nombre` FROM `empleados_lic_especiales`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleados_x_ubicacion') AS `tabla_nombre` FROM `empleados_x_ubicacion`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('empleado_ultimos_cambios') AS `tabla_nombre` FROM `empleado_ultimos_cambios`)
UNION 
(SELECT famdis.`id` AS id_tabla, famdis.`id_usuario`, famdis.`fecha_operacion`, grfam.`id_empleado`, famdis.`tipo_operacion`, CONCAT('familiar_discapacidad') AS `tabla_nombre` FROM `familiar_discapacidad` AS famdis
INNER JOIN grupo_familiar AS grfam ON (grfam.id_grupo_familiar = famdis.id_familiar))
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('grupo_familiar') AS `tabla_nombre` FROM `grupo_familiar`)
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('observaciones') AS `tabla_nombre` FROM `observaciones`)
UNION 
(SELECT pt.`id` AS id_tabla, pt.`id_usuario`, pt.`fecha_operacion`, e.`id_empleados` AS id_empleado, pt.`tipo_operacion`, CONCAT('persona_discapacidad') AS `tabla_nombre` FROM `persona_discapacidad` AS pt
INNER JOIN empleados AS e ON (e.id_persona = pt.id_persona))
UNION 
(SELECT pt.`id` AS id_tabla, pt.`id_usuario`, pt.`fecha_operacion`, `e`.`id_empleados` AS id_empleado, pt.`tipo_operacion`, CONCAT('persona_domicilio') AS `tabla_nombre` FROM `persona_domicilio` AS pt
INNER JOIN empleados AS e ON (e.id_persona = pt.id_persona))
UNION 
(SELECT pt.`id` AS id_tabla, pt.`id_usuario`, pt.`fecha_operacion`, e.`id_empleados` AS id_empleado, pt.`tipo_operacion`, CONCAT('persona_otros_conocimientos') AS `tabla_nombre` FROM `persona_otros_conocimientos` AS pt
INNER JOIN empleados AS e ON (e.id_persona = pt.id_persona))
UNION 
(SELECT pt.`id` AS id_tabla, pt.`id_usuario`, pt.`fecha_operacion`, e.`id_empleados` AS id_empleado, pt.`tipo_operacion`, CONCAT('persona_telefono') AS `tabla_nombre` FROM `persona_telefono` AS pt
INNER JOIN empleados AS e ON (e.id_persona = pt.id_persona))
UNION 
(SELECT pt.`id` AS id_tabla, pt.`id_usuario`, pt.`fecha_operacion`, e.`id_empleados` AS id_empleado, pt.`tipo_operacion`, CONCAT('persona_titulo') AS `tabla_nombre` FROM `persona_titulo` AS pt
INNER JOIN empleados AS e ON (e.id_persona = pt.id_persona))
UNION 
(SELECT pt.`id` AS id_tabla, pt.`id_usuario`, pt.`fecha_operacion`, e.`id_empleados` AS id_empleado, pt.`tipo_operacion`, CONCAT('personas') AS `tabla_nombre` FROM `personas` AS pt
INNER JOIN empleados AS e ON (e.id_persona = pt.id_personas))
UNION 
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`, `id_empleado`, `tipo_operacion`, CONCAT('responsables_contrato') AS `tabla_nombre` FROM `responsables_contrato`)
UNION
(SELECT pf.`id` AS id_tabla, pf.`id_usuario`, pf.`fecha_operacion`, empf.`id_empleado` AS id_empleado, pf.`tipo_operacion`, CONCAT('perfil_actividades') AS `tabla_nombre` FROM `perfil_actividades` AS pf INNER JOIN empleado_perfil AS empf ON empf.id_empleado_perfil = pf.id_perfil)
UNION
(SELECT pf.`id` AS id_tabla, pf.`id_usuario`, pf.`fecha_operacion`, empf.`id_empleado` AS id_empleado, pf.`tipo_operacion`, CONCAT('perfil_resultado_parc_final') AS `tabla_nombre` FROM `perfil_resultado_parc_final` AS pf INNER JOIN empleado_perfil AS empf ON empf.id_empleado_perfil = pf.id_perfil)
ORDER BY fecha_operacion ASC;

USE `sigarhu_historial`;
-- --------------------------------
-- Insertar registros de ABMs --
-- --------------------------------
INSERT INTO _registros_abm(id_tabla, id_usuario, fecha_operacion, tipo_operacion, tabla_nombre)
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('comisiones') AS `tabla_nombre` FROM `comisiones`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_agrupamientos') AS `tabla_nombre` FROM `convenio_agrupamientos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_funciones_ejecutivas') AS `tabla_nombre` FROM `convenio_funciones_ejecutivas`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_grados') AS `tabla_nombre` FROM `convenio_grados`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_modalidad_vinculacion') AS `tabla_nombre` FROM `convenio_modalidad_vinculacion`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_niveles') AS `tabla_nombre` FROM `convenio_niveles`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_situacion_revista') AS `tabla_nombre` FROM `convenio_situacion_revista`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_tramos') AS `tabla_nombre` FROM `convenio_tramos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_unidades_retributivas') AS `tabla_nombre` FROM `convenio_unidades_retributivas`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('convenio_ur_montos') AS `tabla_nombre` FROM `convenio_ur_montos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('denominacion_funcion') AS `tabla_nombre` FROM `denominacion_funcion`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('denominacion_puesto') AS `tabla_nombre` FROM `denominacion_puesto`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('dependencias') AS `tabla_nombre` FROM `dependencias`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('dependencias_informales') AS `tabla_nombre` FROM `dependencias_informales`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('licencias_especiales') AS `tabla_nombre` FROM `licencias_especiales`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('motivo_baja') AS `tabla_nombre` FROM `motivo_baja`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('nivel_educativo') AS `tabla_nombre` FROM `nivel_educativo`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('obras_sociales') AS `tabla_nombre` FROM `obras_sociales`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('plantilla_horarios') AS `tabla_nombre` FROM `plantilla_horarios`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_actividades') AS `tabla_nombre` FROM `presupuesto_actividades`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_jurisdicciones') AS `tabla_nombre` FROM `presupuesto_jurisdicciones`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_obras') AS `tabla_nombre` FROM `presupuesto_obras`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_proyectos') AS `tabla_nombre` FROM `presupuesto_proyectos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_programas') AS `tabla_nombre` FROM `presupuesto_programas`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_subprogramas') AS `tabla_nombre` FROM `presupuesto_subprogramas`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_saf') AS `tabla_nombre` FROM `presupuesto_saf`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuesto_ubicaciones_geograficas') AS `tabla_nombre` FROM `presupuesto_ubicaciones_geograficas`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('presupuestos') AS `tabla_nombre` FROM `presupuestos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('puestos') AS `tabla_nombre` FROM `puestos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('seguro_vida') AS `tabla_nombre` FROM `seguro_vida`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('sindicatos') AS `tabla_nombre` FROM `sindicatos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('subfamilia_puestos') AS `tabla_nombre` FROM `subfamilia_puestos`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('tipo_discapacidad') AS `tabla_nombre` FROM `tipo_discapacidad`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('titulo') AS `tabla_nombre` FROM `titulo`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('ubicacion_edificios') AS `tabla_nombre` FROM `ubicacion_edificios`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('ubicaciones') AS `tabla_nombre` FROM `ubicaciones`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('tipo_documento') AS `tabla_nombre` FROM `tipo_documento`)
UNION
(SELECT `id` AS id_tabla, `id_usuario`, `fecha_operacion`,`tipo_operacion`, CONCAT('otros_organismos') AS `tabla_nombre` FROM `otros_organismos`)
ORDER BY fecha_operacion ASC;


-- --------------------------------
-- Trigger para registrar ABMs --
-- --------------------------------
DELIMITER $$
DROP TRIGGER IF EXISTS `comisiones_tg_insert`$$
CREATE TRIGGER `comisiones_tg_insert` AFTER INSERT ON `comisiones` FOR EACH ROW BEGIN
    INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'comisiones');
END $$
DROP TRIGGER IF EXISTS `convenio_agrupamientos_tg_insert`$$
CREATE TRIGGER `convenio_agrupamientos_tg_insert` AFTER INSERT ON `convenio_agrupamientos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_agrupamientos');
END $$
DROP TRIGGER IF EXISTS `convenio_funciones_ejecutivas_tg_insert`$$
CREATE TRIGGER `convenio_funciones_ejecutivas_tg_insert` AFTER INSERT ON `convenio_funciones_ejecutivas` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_funciones_ejecutivas');
END $$
DROP TRIGGER IF EXISTS `convenio_grados_tg_insert`$$
CREATE TRIGGER `convenio_grados_tg_insert` AFTER INSERT ON `convenio_grados` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_grados');
END $$
DROP TRIGGER IF EXISTS `convenio_modalidad_vinculacion_tg_insert`$$
CREATE TRIGGER `convenio_modalidad_vinculacion_tg_insert` AFTER INSERT ON `convenio_modalidad_vinculacion` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_modalidad_vinculacion');
END $$
DROP TRIGGER IF EXISTS `convenio_niveles_tg_insert`$$
CREATE TRIGGER `convenio_niveles_tg_insert` AFTER INSERT ON `convenio_niveles` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_niveles');
END $$
DROP TRIGGER IF EXISTS `convenio_situacion_revista_tg_insert`$$
CREATE TRIGGER `convenio_situacion_revista_tg_insert` AFTER INSERT ON `convenio_situacion_revista` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_situacion_revista');
END $$
DROP TRIGGER IF EXISTS `convenio_tramos_tg_insert`$$
CREATE TRIGGER `convenio_tramos_tg_insert` AFTER INSERT ON `convenio_tramos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_tramos');
END $$
DROP TRIGGER IF EXISTS `convenio_unidades_retributivas_tg_insert`$$
CREATE TRIGGER `convenio_unidades_retributivas_tg_insert` AFTER INSERT ON `convenio_unidades_retributivas` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_unidades_retributivas');
END $$
DROP TRIGGER IF EXISTS `convenio_ur_montos_tg_insert`$$
CREATE TRIGGER `convenio_ur_montos_tg_insert` AFTER INSERT ON `convenio_ur_montos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'convenio_ur_montos');
END $$
DROP TRIGGER IF EXISTS `denominacion_funcion_tg_insert`$$
CREATE TRIGGER `denominacion_funcion_tg_insert` AFTER INSERT ON `denominacion_funcion` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'denominacion_funcion');
END $$
DROP TRIGGER IF EXISTS `denominacion_puesto_tg_insert`$$
CREATE TRIGGER `denominacion_puesto_tg_insert` AFTER INSERT ON `denominacion_puesto` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'denominacion_puesto');
END $$
DROP TRIGGER IF EXISTS `dependencias_tg_insert`$$
CREATE TRIGGER `dependencias_tg_insert` AFTER INSERT ON `dependencias` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'dependencias');
END $$
DROP TRIGGER IF EXISTS `dependencias_informales_tg_insert`$$
CREATE TRIGGER `dependencias_informales_tg_insert` AFTER INSERT ON `dependencias_informales` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'dependencias_informales');
END $$
DROP TRIGGER IF EXISTS `licencias_especiales_tg_insert`$$
CREATE TRIGGER `licencias_especiales_tg_insert` AFTER INSERT ON `licencias_especiales` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'licencias_especiales');
END $$
DROP TRIGGER IF EXISTS `motivo_baja_tg_insert`$$
CREATE TRIGGER `motivo_baja_tg_insert` AFTER INSERT ON `motivo_baja` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'motivo_baja');
END $$
DROP TRIGGER IF EXISTS `nivel_educativo_tg_insert`$$
CREATE TRIGGER `nivel_educativo_tg_insert` AFTER INSERT ON `nivel_educativo` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'nivel_educativo');
END $$
DROP TRIGGER IF EXISTS `obras_sociales_tg_insert`$$
CREATE TRIGGER `obras_sociales_tg_insert` AFTER INSERT ON `obras_sociales` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'obras_sociales');
END $$
DROP TRIGGER IF EXISTS `plantilla_horarios_tg_insert`$$
CREATE TRIGGER `plantilla_horarios_tg_insert` AFTER INSERT ON `plantilla_horarios` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'plantilla_horarios');
END $$
DROP TRIGGER IF EXISTS `presupuesto_actividades_tg_insert`$$
CREATE TRIGGER `presupuesto_actividades_tg_insert` AFTER INSERT ON `presupuesto_actividades` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_actividades');
END $$
DROP TRIGGER IF EXISTS `presupuesto_jurisdicciones_tg_insert`$$
CREATE TRIGGER `presupuesto_jurisdicciones_tg_insert` AFTER INSERT ON `presupuesto_jurisdicciones` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_jurisdicciones');
END $$
DROP TRIGGER IF EXISTS `presupuesto_obras_tg_insert`$$
CREATE TRIGGER `presupuesto_obras_tg_insert` AFTER INSERT ON `presupuesto_obras` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_obras');
END $$
DROP TRIGGER IF EXISTS `presupuesto_proyectos_tg_insert`$$
CREATE TRIGGER `presupuesto_proyectos_tg_insert` AFTER INSERT ON `presupuesto_proyectos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_proyectos');
END $$
DROP TRIGGER IF EXISTS `presupuesto_programas_tg_insert`$$
CREATE TRIGGER `presupuesto_programas_tg_insert` AFTER INSERT ON `presupuesto_programas` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_programas');
END $$
DROP TRIGGER IF EXISTS `presupuesto_subprogramas_tg_insert`$$
CREATE TRIGGER `presupuesto_subprogramas_tg_insert` AFTER INSERT ON `presupuesto_subprogramas` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_subprogramas');
END $$
DROP TRIGGER IF EXISTS `presupuesto_saf_tg_insert`$$
CREATE TRIGGER `presupuesto_saf_tg_insert` AFTER INSERT ON `presupuesto_saf` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_saf');
END $$
DROP TRIGGER IF EXISTS `presupuesto_ubicaciones_geograficas_tg_insert`$$
CREATE TRIGGER `presupuesto_ubicaciones_geograficas_tg_insert` AFTER INSERT ON `presupuesto_ubicaciones_geograficas` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuesto_ubicaciones_geograficas');
END $$
DROP TRIGGER IF EXISTS `presupuestos_tg_insert`$$
CREATE TRIGGER `presupuestos_tg_insert` AFTER INSERT ON `presupuestos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'presupuestos');
END $$
DROP TRIGGER IF EXISTS `puestos_tg_insert`$$
CREATE TRIGGER `puestos_tg_insert` AFTER INSERT ON `puestos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'puestos');
END $$
DROP TRIGGER IF EXISTS `seguro_vida_tg_insert`$$
CREATE TRIGGER `seguro_vida_tg_insert` AFTER INSERT ON `seguro_vida` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'seguro_vida');
END $$
DROP TRIGGER IF EXISTS `sindicatos_tg_insert`$$
CREATE TRIGGER `sindicatos_tg_insert` AFTER INSERT ON `sindicatos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'sindicatos');
END $$
DROP TRIGGER IF EXISTS `subfamilia_puestos_tg_insert`$$
CREATE TRIGGER `subfamilia_puestos_tg_insert` AFTER INSERT ON `subfamilia_puestos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'subfamilia_puestos');
END $$
DROP TRIGGER IF EXISTS `tipo_discapacidad_tg_insert`$$
CREATE TRIGGER `tipo_discapacidad_tg_insert` AFTER INSERT ON `tipo_discapacidad` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'tipo_discapacidad');
END $$
DROP TRIGGER IF EXISTS `titulo_tg_insert`$$
CREATE TRIGGER `titulo_tg_insert` AFTER INSERT ON `titulo` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'titulo');
END $$
DROP TRIGGER IF EXISTS `ubicacion_edificios_tg_insert`$$
CREATE TRIGGER `ubicacion_edificios_tg_insert` AFTER INSERT ON `ubicacion_edificios` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'ubicacion_edificios');
END $$
DROP TRIGGER IF EXISTS `ubicaciones_tg_insert`$$
CREATE TRIGGER `ubicaciones_tg_insert` AFTER INSERT ON `ubicaciones` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'ubicaciones');
END $$
DROP TRIGGER IF EXISTS `tipo_documento_tg_insert`$$
CREATE TRIGGER `tipo_documento_tg_insert` AFTER INSERT ON `tipo_documento` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'tipo_documento');
END $$
DROP TRIGGER IF EXISTS `otros_organismos_tg_insert`$$
CREATE TRIGGER `otros_organismos_tg_insert` AFTER INSERT ON `otros_organismos` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'otros_organismos');
END $$
DELIMITER ;


-- -----------------------------------
-- Triggers para Registrar Legajos --
-- -----------------------------------

DELIMITER $$
DROP TRIGGER IF EXISTS `anticorrupcion_presentacion_tg_insert`$$
CREATE TRIGGER `anticorrupcion_presentacion_tg_insert` AFTER INSERT ON `anticorrupcion_presentacion` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'anticorrupcion_presentacion',
	(SELECT id_empleado FROM sigarhu.anticorrupcion WHERE id =  NEW.id_anticorrupcion LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `anticorrupcion_tg_insert`$$
CREATE TRIGGER `anticorrupcion_tg_insert` AFTER INSERT ON `anticorrupcion` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'anticorrupcion');
END $$
DROP TRIGGER IF EXISTS `contratante_tg_insert`$$
CREATE TRIGGER `contratante_tg_insert` AFTER INSERT ON `contratante` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'contratante');
END $$
DROP TRIGGER IF EXISTS `embargos_tg_insert`$$
CREATE TRIGGER `embargos_tg_insert` AFTER INSERT ON `embargos` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'embargos');
END $$
DROP TRIGGER IF EXISTS `empleado_comision_tg_insert`$$
CREATE TRIGGER `empleado_comision_tg_insert` AFTER INSERT ON `empleado_comision` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_comision');
END $$
DROP TRIGGER IF EXISTS `empleado_dep_informales_tg_insert`$$
CREATE TRIGGER `empleado_dep_informales_tg_insert` AFTER INSERT ON `empleado_dep_informales` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_dep_informales');
END $$
DROP TRIGGER IF EXISTS `empleado_dependencia_tg_insert`$$
CREATE TRIGGER `empleado_dependencia_tg_insert` AFTER INSERT ON `empleado_dependencia` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_dependencia');
END $$
DROP TRIGGER IF EXISTS `empleado_documentos_tg_insert`$$
CREATE TRIGGER `empleado_documentos_tg_insert` AFTER INSERT ON `empleado_documentos` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_documentos');
END $$
DROP TRIGGER IF EXISTS `empleado_escalafon_tg_insert`$$
CREATE TRIGGER `empleado_escalafon_tg_insert` AFTER INSERT ON `empleado_escalafon` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_escalafon');
END $$
DROP TRIGGER IF EXISTS `empleado_horarios_tg_insert`$$
CREATE TRIGGER `empleado_horarios_tg_insert` AFTER INSERT ON `empleado_horarios` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_horarios');
END $$
DROP TRIGGER IF EXISTS `empleado_horas_extras_tg_insert`$$
CREATE TRIGGER `empleado_horas_extras_tg_insert` AFTER INSERT ON `empleado_horas_extras` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_horas_extras');
END $$
DROP TRIGGER IF EXISTS `empleado_perfil_tg_insert`$$
CREATE TRIGGER `empleado_perfil_tg_insert` AFTER INSERT ON `empleado_perfil` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_perfil');
END $$
DROP TRIGGER IF EXISTS `empleado_presupuesto_tg_insert`$$
CREATE TRIGGER `empleado_presupuesto_tg_insert` AFTER INSERT ON `empleado_presupuesto` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_presupuesto');
END $$
DROP TRIGGER IF EXISTS `empleado_salud_tg_insert`$$
CREATE TRIGGER `empleado_salud_tg_insert` AFTER INSERT ON `empleado_salud` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_salud');
END $$
DROP TRIGGER IF EXISTS `empleado_seguros_tg_insert`$$
CREATE TRIGGER `empleado_seguros_tg_insert` AFTER INSERT ON `empleado_seguros` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_seguros');
END $$
DROP TRIGGER IF EXISTS `empleado_sindicatos_tg_insert`$$
CREATE TRIGGER `empleado_sindicatos_tg_insert` AFTER INSERT ON `empleado_sindicatos` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_sindicatos');
END $$
DROP TRIGGER IF EXISTS `empleados_tg_insert`$$
CREATE TRIGGER `empleados_tg_insert` AFTER INSERT ON `empleados` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleados,NEW.fecha_operacion,NEW.tipo_operacion,'empleados');
END $$
DROP TRIGGER IF EXISTS `empleados_lic_especiales_tg_insert`$$
CREATE TRIGGER `empleados_lic_especiales_tg_insert` AFTER INSERT ON `empleados_lic_especiales` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleados_lic_especiales');
END $$
DROP TRIGGER IF EXISTS `empleados_x_ubicacion_tg_insert`$$
CREATE TRIGGER `empleados_x_ubicacion_tg_insert` AFTER INSERT ON `empleados_x_ubicacion` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleados_x_ubicacion');
END $$
DROP TRIGGER IF EXISTS `empleado_ultimos_cambios_tg_insert`$$
CREATE TRIGGER `empleado_ultimos_cambios_tg_insert` AFTER INSERT ON `empleado_ultimos_cambios` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'empleado_ultimos_cambios');
END $$
DROP TRIGGER IF EXISTS `familiar_discapacidad_tg_insert`$$
CREATE TRIGGER `familiar_discapacidad_tg_insert` AFTER INSERT ON `familiar_discapacidad` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'familiar_discapacidad',
	(SELECT id_empleado FROM sigarhu.grupo_familiar WHERE id =  NEW.id_familiar LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `grupo_familiar_tg_insert`$$
CREATE TRIGGER `grupo_familiar_tg_insert` AFTER INSERT ON `grupo_familiar` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'grupo_familiar');
END $$
DROP TRIGGER IF EXISTS `observaciones_tg_insert`$$
CREATE TRIGGER `observaciones_tg_insert` AFTER INSERT ON `observaciones` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'observaciones');
END $$
DROP TRIGGER IF EXISTS `persona_discapacidad_tg_insert`$$
CREATE TRIGGER `persona_discapacidad_tg_insert` AFTER INSERT ON `persona_discapacidad` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_discapacidad',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `persona_domicilio_tg_insert`$$
CREATE TRIGGER `persona_domicilio_tg_insert` AFTER INSERT ON `persona_domicilio` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_domicilio',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `persona_otros_conocimientos_tg_insert`$$
CREATE TRIGGER `persona_otros_conocimientos_tg_insert` AFTER INSERT ON `persona_otros_conocimientos` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_otros_conocimientos',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `persona_telefono_tg_insert`$$
CREATE TRIGGER `persona_telefono_tg_insert` AFTER INSERT ON `persona_telefono` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_telefono',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `persona_titulo_tg_insert`$$
CREATE TRIGGER `persona_titulo_tg_insert` AFTER INSERT ON `persona_titulo` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_titulo',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `personas_tg_insert`$$
CREATE TRIGGER `personas_tg_insert` AFTER INSERT ON `personas` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'personas',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_personas LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `responsables_contrato_tg_insert`$$
CREATE TRIGGER `responsables_contrato_tg_insert` AFTER INSERT ON `responsables_contrato` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleado,NEW.fecha_operacion,NEW.tipo_operacion,'responsables_contrato');
END $$
DROP TRIGGER IF EXISTS `perfil_actividades_tg_insert`$$
CREATE TRIGGER `perfil_actividades_tg_insert` AFTER INSERT ON `perfil_actividades` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`, `id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'perfil_actividades',
	(SELECT id_empleado FROM sigarhu.empleado_perfil WHERE id = NEW.id_perfil LIMIT 1));
END $$
DROP TRIGGER IF EXISTS `perfil_resultado_parc_final_tg_insert`$$
CREATE TRIGGER `perfil_resultado_parc_final_tg_insert` AFTER INSERT ON `perfil_resultado_parc_final` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`, `id_empleado`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'perfil_resultado_parc_final',
	(SELECT id_empleado FROM sigarhu.empleado_perfil WHERE id = NEW.id_perfil LIMIT 1));
END $$
DELIMITER ;


#####################################################################################################
# ATENCIÓN : LOS NOMBRES DE LAS BASES ESTAN DEFINIDAS ESTATICAMENTE, EN CASO DE QUE, EN EL ENTORNO  #
# EN EL QUE SE CORRA ESTA SCRIPT, SE LLAMEN DE OTRO MANERA DEBERA EDITARSE.                         #
#####################################################################################################


DELIMITER $$
DROP FUNCTION IF EXISTS getPanelUsuarioNombre $$
CREATE FUNCTION getPanelUsuarioNombre(id_usuario INT(11)) RETURNS VARCHAR(255) DETERMINISTIC BEGIN
	DECLARE _nombre VARCHAR(255);

	SELECT CONCAT(nombre,' ',apellido) 	INTO _nombre
	FROM 	_mt_paneldecontrol.usuarios
	WHERE 	_mt_paneldecontrol.usuarios.idUsuario = id_usuario;
    RETURN(_nombre);
END $$
DELIMITER ;

CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW _vt_registros_historial AS 
SELECT 
    _rl.id,
    id_usuario,
    fecha_operacion,
    CASE tipo_operacion WHEN 'M' THEN 'modificacion' WHEN 'B' THEN 'baja' WHEN 'A' THEN 'alta' END tipo_operacion,
    id_tabla,
    tabla_nombre,
    id_empleado,
    CONCAT(pe.nombre,' ',pe.apellido) AS empleado_nombre,
    em.cuit AS empleado_cuit,
    getPanelUsuarioNombre(_rl.id_usuario) AS usuario_nombre,
	'legajo' AS tipo_registro
FROM
    sigarhu_historial._registros_legajos _rl
    INNER JOIN sigarhu.empleados em ON (em.id = _rl.id_empleado)
    INNER JOIN sigarhu.personas pe ON (pe.id = em.id_persona)
UNION
SELECT 
    _rl.id,
    id_usuario,
    fecha_operacion,
    CASE tipo_operacion WHEN 'M' THEN 'modificacion' WHEN 'B' THEN 'baja' WHEN 'A' THEN 'alta' END tipo_operacion,
    id_tabla,
    tabla_nombre,
    NULL AS id_empleado,
    NULL AS empleado_nombre,
	NULL AS empleado_cuit,
    getPanelUsuarioNombre(_rl.id_usuario) AS usuario_nombre,
	'abm' AS tipo_registro
FROM
    sigarhu_historial._registros_abm _rl
ORDER BY fecha_operacion DESC;


USE `sigarhu_historial`;

CREATE TABLE `dependencias_historicas` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`id_usuario` INT(11) NOT NULL,
	`fecha_operacion` DATETIME NOT NULL,
	`tipo_operacion` CHAR(1) NOT NULL,
	`id_dependencias_historicas` int(11) NOT NULL,
	`id_dependencia` INT(11) NOT NULL,
	`id_padre` INT(11) NOT NULL,
	`fecha_desde` DATE NOT NULL,
	`fecha_hasta` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
 )ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER $$

DROP TRIGGER IF EXISTS sigarhu_historial.dependencias_historicas_tg_insert$$
USE `sigarhu_historial`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `dependencias_historicas_tg_insert` AFTER INSERT ON `dependencias_historicas` FOR EACH ROW BEGIN
	INSERT INTO _registros_abm(`id_tabla`, `id_usuario`, `fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'dependencias_historicas');
END$$
DELIMITER ;


#####################################################################################################
#####################################################################################################

ALTER TABLE `sigarhu_historial`.`_registros_legajos` 
ADD COLUMN `id_persona` INT(11) NULL DEFAULT NULL AFTER `id_empleado`,
ADD INDEX `id_empleado` (`id_persona` ASC, `id_empleado` ASC, `tabla_nombre` ASC, `id_tabla` ASC);


USE `sigarhu_historial`;
DROP procedure IF EXISTS `updateRegistroLegajo`;

DELIMITER $$
USE `sigarhu_historial`$$
CREATE PROCEDURE `updateRegistroLegajo` ()
BEGIN
	UPDATE _registros_legajos _rl INNER JOIN empleados em ON (_rl.id_persona = em.id_persona AND _rl.id_empleado IS NULL) SET _rl.id_empleado = em.id_empleados WHERE _rl.id_empleado IS NULL AND _rl.id_persona IS NOT NULL;
END$$

DELIMITER ;

DROP TRIGGER IF EXISTS `sigarhu_historial`.`empleados_tg_insert`;

DELIMITER $$
USE `sigarhu_historial`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleados_tg_insert` AFTER INSERT ON `empleados` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`id_empleado`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`) VALUES (NEW.id,NEW.id_usuario,NEW.id_empleados,NEW.fecha_operacion,NEW.tipo_operacion,'empleados');
	CALL updateRegistroLegajo();
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `sigarhu_historial`.`persona_domicilio_tg_insert`;

DELIMITER $$
USE `sigarhu_historial`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `persona_domicilio_tg_insert` AFTER INSERT ON `persona_domicilio` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`, `id_persona`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_domicilio',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1), IF (NEW.fecha_operacion = 'A', NEW.id_persona, null));
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `sigarhu_historial`.`persona_telefono_tg_insert`;

DELIMITER $$
USE `sigarhu_historial`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `persona_telefono_tg_insert` AFTER INSERT ON `persona_telefono` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`, `id_persona`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'persona_telefono',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_persona LIMIT 1), IF (NEW.fecha_operacion = 'A', NEW.id_persona, null));
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `sigarhu_historial`.`personas_tg_insert`;

DELIMITER $$
USE `sigarhu_historial`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `personas_tg_insert` AFTER INSERT ON `personas` FOR EACH ROW BEGIN
	INSERT INTO _registros_legajos(`id_tabla`, `id_usuario`,`fecha_operacion`, `tipo_operacion`, `tabla_nombre`,`id_empleado`, `id_persona`) VALUES (NEW.id,NEW.id_usuario,NEW.fecha_operacion,NEW.tipo_operacion,'personas',
	(SELECT id FROM sigarhu.empleados WHERE id_persona =  NEW.id_personas LIMIT 1), IF (NEW.fecha_operacion = 'A', NEW.id_personas, null));
END$$
DELIMITER ;



INSERT INTO db_version VALUES('17.0', now());
