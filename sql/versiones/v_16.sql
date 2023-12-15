#############################################################
# En SIGARHU
#############################################################

CREATE TABLE `empleado_ultimos_cambios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_tipo` tinyint(1) DEFAULT NULL,
  `id_convenios` int(11) DEFAULT NULL,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_ultimos_cambios_1_idx` (`id_empleado`),
  CONSTRAINT `fk_empleado_ultimos_cambios_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TRIGGER IF EXISTS `sigarhu`.`empleado_ultimos_cambios_alta`;
DELIMITER $$
USE `sigarhu`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_ultimos_cambios_alta` AFTER INSERT ON `empleado_ultimos_cambios` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_ultimos_cambios
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_ultimos_cambios`,
    `id_empleado`,  
    `id_tipo`,  
    `id_convenios`,
    `fecha_desde`, 
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_tipo,
    NEW.id_convenios,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;
DROP TRIGGER IF EXISTS `sigarhu`.`empleado_ultimos_cambios_modificacion`;

DELIMITER $$
USE `sigarhu`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_ultimos_cambios_modificacion` AFTER UPDATE ON `empleado_ultimos_cambios` FOR EACH ROW
BEGIN
IF NEW.id_convenios <> OLD.id_convenios OR NEW.fecha_desde <> OLD.fecha_desde OR NEW.fecha_hasta <> OLD.fecha_hasta
THEN
    INSERT INTO sigarhu_historial.empleado_ultimos_cambios
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_empleado_ultimos_cambios`,
        `id_empleado`,
        `id_tipo`,
        `id_convenios`,
        `fecha_desde`,
        `fecha_hasta`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        "M",
        OLD.id,
        NEW.id_empleado,
        NEW.id_tipo,
        NEW.id_convenios,
        NEW.fecha_desde,
        NEW.fecha_hasta
        );
END IF;
END$$
DELIMITER ;


ALTER TABLE `empleado_escalafon` DROP `ultimo_cambio_nivel`;

DROP TRIGGER IF EXISTS `sigarhu`.`empleados_tg_alta`;
DELIMITER $$
USE `sigarhu`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleados_tg_alta` AFTER INSERT ON `empleados` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleados
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleados`,
    `id_persona`,  
    `cuit`,  
    `email`,
    `planilla_reloj`,
    `en_comision`,  
    `credencial`,
    `borrado`,
    `antiguedad_adm_publica`,  
    `id_sindicato`,
    `fecha_vigencia_mandato`,  
    `estado`, 
    `id_motivo`, 
    `fecha_baja`, 
    `fecha_vencimiento`,
    `veterano_guerra` 
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_persona,
    NEW.cuit,
    NEW.email,
    NEW.planilla_reloj,
    NEW.en_comision,
    NEW.credencial,
    NEW.borrado,
    NEW.antiguedad_adm_publica,
    NEW.id_sindicato,
    NEW.fecha_vigencia_mandato,
    NEW.estado,
    NEW.id_motivo,
    NEW.fecha_baja,
    NEW.fecha_vencimiento,
    NEW.veterano_guerra
    );
END$$
DELIMITER ;
DROP TRIGGER IF EXISTS `sigarhu`.`empleados_tg_modificacion`;

DELIMITER $$
USE `sigarhu`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleados_tg_modificacion` AFTER UPDATE ON `empleados` FOR EACH ROW
BEGIN
IF NEW.cuit <> OLD.cuit OR NEW.email <> OLD.email OR NEW.planilla_reloj <> OLD.planilla_reloj OR
    NEW.en_comision <> OLD.en_comision OR NEW.credencial <> OLD.credencial OR
    NEW.antiguedad_adm_publica <> OLD.antiguedad_adm_publica OR NEW.id_sindicato <> OLD.id_sindicato OR
    NEW.fecha_vigencia_mandato <> OLD.fecha_vigencia_mandato OR NEW.estado <> OLD.estado OR
    NEW.id_motivo <> OLD.id_motivo OR NEW.fecha_baja <> OLD.fecha_baja OR 
    NEW.fecha_vencimiento <> OLD.fecha_vencimiento OR NEW.veterano_guerra <> OLD.veterano_guerra
THEN
    INSERT INTO sigarhu_historial.empleados
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_empleados`,
        `id_persona`,  
        `cuit`,  
        `email`,
        `planilla_reloj`,
        `en_comision`, 
        `credencial`,
        `borrado`,
        `antiguedad_adm_publica`,  
        `id_sindicato`,
        `fecha_vigencia_mandato`,  
        `estado`, 
        `id_motivo`, 
        `fecha_baja`, 
        `fecha_vencimiento`,
        `veterano_guerra`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M", "B"),
        OLD.id,
        NEW.id_persona,
        NEW.cuit,
        NEW.email,
        NEW.planilla_reloj,
        NEW.en_comision,
        NEW.credencial,
        NEW.borrado,
        NEW.antiguedad_adm_publica,
        NEW.id_sindicato,
        NEW.fecha_vigencia_mandato,
        NEW.estado,
        NEW.id_motivo,
        NEW.fecha_baja,
        NEW.fecha_vencimiento,
        NEW.veterano_guerra
        );
END IF;
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `sigarhu`.`empleado_dep_informales_tg_alta`;

DELIMITER $$
USE `sigarhu`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_dep_informales_tg_alta` AFTER INSERT ON `empleado_dep_informales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_dep_informales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_dep_informales`,
    `id_empleado`,
    `id_dep_informal`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_dep_informal,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;
DROP TRIGGER IF EXISTS `sigarhu`.`empleado_dep_informales_tg_modificacion`;

DELIMITER $$
USE `sigarhu`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_dep_informales_tg_modificacion` AFTER UPDATE ON `empleado_dep_informales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_dep_informales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_dep_informales`,
    `id_empleado`,
    `id_dep_informal`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0 , "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_dep_informal,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_alta$$
CREATE TRIGGER `empleado_escalafon_tg_alta` AFTER INSERT ON `empleado_escalafon` FOR EACH ROW
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
    `exc_art_14`
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
    NEW.exc_art_14
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_modificacion$$
CREATE TRIGGER `empleado_escalafon_tg_modificacion` AFTER UPDATE ON `empleado_escalafon` FOR EACH ROW
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
    NEW.exc_art_14 <> OLD.exc_art_14
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
        `exc_art_14` 
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
        NEW.exc_art_14
        );
END IF;
END$$
DELIMITER ;


DROP TABLE IF EXISTS `tipo_documento`;
SET character_set_client = utf8 ;
CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

LOCK TABLES `tipo_documento` WRITE;
INSERT INTO `tipo_documento` (nombre,borrado) VALUES ('S/D',0),('Resolución designación',0),('Situación de revista',0),('Forma de ingreso',0),('Curriculum vitae',0),('Fotocopia de titulo certificado',0),('Examen Psicofísico',0),('Certificado de antecendentes penales',0),('Retención de salario legales o judiciales',0),('Documentación respaldatoria designación',0),('Fotocopia DNI',0),('Fotocopia CUIT',0),('Obra social',0),('Dec. Adm. 894/01 DDJJ de cargos',0),('Formulario licencias utilizadas',0),('Constancia de alumno regular',0),('Comprobante de examen',0),('Licencia por enfermedad contancia',0),('Fecha de asignaciones familiares',0),('Comprobante que el conyugue no cobra asig. fam.',0),('Constancia Seguro de vida obligatorio',0),('Constancia ART',0),('AFIP - Alta temprana',0),('Calificaciones anuales',0),('DDJJ Patrimonial',0),('Copias de los actos adm.por sumarios',0),('Resolución adm. varias',0),('Baja efectiva',0);
UNLOCK TABLES;

DELIMITER $$

DROP TRIGGER IF EXISTS tipo_documento_AFTER_INSERT$$
CREATE DEFINER=CURRENT_USER TRIGGER `tipo_documento_AFTER_INSERT` AFTER INSERT ON `tipo_documento` FOR EACH ROW
BEGIN

INSERT INTO sigarhu_historial.tipo_documento
(
    `id_tipo_documento`,
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,    
    `nombre`,
    `borrado`
)
VALUES
(
    NEW.id,
    @id_usuario,
    NOW(),
    'A',    
    NEW.nombre,
    NEW.borrado
    
);

END$$
DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS tipo_documento_AFTER_UPDATE$$

CREATE DEFINER = CURRENT_USER TRIGGER `tipo_documento_AFTER_UPDATE` AFTER UPDATE ON `tipo_documento` FOR EACH ROW
BEGIN
DECLARE estado CHAR(1);
SET estado = 'M';
IF (NEW.borrado = 1) THEN 
    SET estado = 'B';
END IF;

	INSERT INTO sigarhu_historial.tipo_documento
	(
		`id_tipo_documento`,
		`id_usuario`,
		`fecha_operacion`,
		`tipo_operacion`,    
		`nombre`,
		`borrado`
	)
	VALUES
	(
		NEW.id,
		@id_usuario,
		NOW(),
		estado,    
		NEW.nombre,
		NEW.borrado
	);

END$$

ALTER TABLE `empleado_documentos` 
ADD COLUMN `id_tipo` INT(11) NOT NULL DEFAULT 1;

ALTER TABLE `empleado_documentos` 
CHANGE COLUMN `id_tipo` `id_tipo` INT(11) NOT NULL DEFAULT '1' AFTER `id_usuario`;
ALTER TABLE `empleado_documentos` 
ADD CONSTRAINT `fk_empleado_documentos_2`
  FOREIGN KEY (`id_tipo`)
  REFERENCES `tipo_documento` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `empleado_documentos` 
DROP COLUMN `id_bloque`;


DELIMITER $$

DROP TRIGGER IF EXISTS empleado_documentos_tg_alta$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_documentos_tg_alta` AFTER INSERT ON `empleado_documentos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_documentos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_documentos`,
    `id_empleado`,
    `id_tipo`,
    `nombre_archivo`,
    `fecha_reg`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_tipo,
    NEW.nombre_archivo,
    NEW.fecha_reg,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS empleado_documentos_tg_modificacion$$
CREATE DEFINER=`root`@`localhost` TRIGGER `empleado_documentos_tg_modificacion` AFTER UPDATE ON `empleado_documentos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_documentos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_documentos`,
    `id_empleado`,
    `id_tipo`,
    `nombre_archivo`,
    `fecha_reg`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_tipo,
    NEW.nombre_archivo,
    NEW.fecha_reg,
    NEW.borrado
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS persona_otros_conocimientos_tg_modificacion$$

CREATE DEFINER=`root`@`localhost` TRIGGER `persona_otros_conocimientos_tg_modificacion` AFTER UPDATE ON `persona_otros_conocimientos` FOR EACH ROW
BEGIN

	INSERT INTO sigarhu_historial.persona_otros_conocimientos
	(
		`id_persona_otros_conocimientos`,
		`id_usuario`,
		`fecha_operacion`,
		`tipo_operacion`,    
		`id_persona`,
		`fecha`,
		`descripcion`
	)
	VALUES
	(
		NEW.id,
		@id_usuario,
		NOW(),
		'M',    
		NEW.id_persona,
		NEW.fecha,
		NEW.descripcion
	);

END$$
DELIMITER ;


CREATE TABLE `otros_organismos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `tipo` TINYINT(1) NOT NULL,
  `jurisdiccion` TINYINT(1) NOT NULL DEFAULT '0',
  `borrado` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

DELIMITER $$
DROP TRIGGER IF EXISTS otros_organismos_AFTER_INSERT$$

CREATE DEFINER = CURRENT_USER TRIGGER `otros_organismos_AFTER_INSERT` AFTER INSERT ON `otros_organismos` FOR EACH ROW
BEGIN

INSERT INTO sigarhu_historial.otros_organismos
(
    
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_otros_organismos`,
    `nombre`,
    `tipo`,
    `jurisdiccion`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.nombre,
    NEW.tipo,
    NEW.jurisdiccion,
    NEW.borrado
);


END$$
DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS otros_organismos_AFTER_UPDATE$$

CREATE DEFINER = CURRENT_USER TRIGGER `otros_organismos_AFTER_UPDATE` AFTER UPDATE ON `otros_organismos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.otros_organismos
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_otros_organismos`,
    `nombre`,
    `tipo`,
    `jurisdiccion`,
    `borrado`
)
VALUES
(
   
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.nombre,
    NEW.tipo,
    NEW.jurisdiccion,
    NEW.borrado
);

END$$
DELIMITER ;

CREATE TABLE `persona_experiencia_laboral` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_persona` INT(11) NOT NULL,
  `id_entidad` INT(11) NOT NULL,
  `fecha_desde` DATE NOT NULL,
  `fecha_hasta` DATE NULL DEFAULT NULL,
  `borrado` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `persona_experiencia_laboral` 
ADD INDEX `fk_persona_experiencia_laboral_1_idx` (`id_persona` ASC),
ADD INDEX `fk_persona_experiencia_laboral_2_idx` (`id_entidad` ASC);
ALTER TABLE `persona_experiencia_laboral` 
ADD CONSTRAINT `fk_persona_experiencia_laboral_1`
  FOREIGN KEY (`id_persona`)
  REFERENCES `personas` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_persona_experiencia_laboral_2`
  FOREIGN KEY (`id_entidad`)
  REFERENCES `otros_organismos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

DELIMITER $$
DROP TRIGGER IF EXISTS persona_experiencia_laboral_AFTER_INSERT$$

CREATE DEFINER = CURRENT_USER TRIGGER `persona_experiencia_laboral_AFTER_INSERT` AFTER INSERT ON `persona_experiencia_laboral` FOR EACH ROW
BEGIN

INSERT INTO sigarhu_historial.persona_experiencia_laboral
(
    
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_experiencia_laboral`,
    `id_persona`,
    `id_entidad`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
)
VALUES
(
    @id_usuario,
    NOW(),
    'A',
    NEW.id,
    NEW.id_persona,
    NEW.id_entidad,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
);


END$$
DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS persona_experiencia_laboral_AFTER_UPDATE$$

CREATE DEFINER = CURRENT_USER TRIGGER `persona_experiencia_laboral_AFTER_UPDATE` AFTER UPDATE ON `persona_experiencia_laboral` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_experiencia_laboral
(
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_experiencia_laboral`,
    `id_persona`,
    `id_entidad`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
)
VALUES
(
   
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    NEW.id,
    NEW.id_persona,
    NEW.id_entidad,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
);

END$$
DELIMITER ;

INSERT INTO db_version VALUES('16.0', now());