SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE  `empleados` ADD INDEX  `empleados_cuit_IDX` (  `cuit` ) USING BTREE;

ALTER TABLE  `contratante` 
ADD  `id_dependencia` INT NULL AFTER  `id_empleado` ,
ADD  `cuit` DECIMAL( 11, 0 ) NULL AFTER  `id_dependencia` ,
ADD INDEX (  `id_dependencia` ,  `cuit` ) ;


DROP TABLE IF EXISTS `lote`;
CREATE TABLE IF NOT EXISTS `lote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_lote` int(11) NOT NULL,
  `id_dependencia` int(11) NOT NULL,
  `fecha_apertura` date NOT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `estado` enum('sin_publicar','publicado','en_curso','pre_aprobado','aprobado','rechazado','cerrado') DEFAULT NULL,
  `id_modalidad_vinculacion` int(11) DEFAULT NULL,
  `id_situacion_revista` int(11) DEFAULT NULL,
  `id_contratante` int(11) DEFAULT NULL,
  `id_firmante` int(11) DEFAULT NULL,
  `fecha_inicio_contrato` date DEFAULT NULL,
  `acto_administrativo` varchar(255) DEFAULT NULL,
  `fecha_acto_administrativo` date DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_lote_1_idx` (`id_dependencia`),
  KEY `id_modalidad_vinculacion` (`id_modalidad_vinculacion`,`id_situacion_revista`,`id_contratante`,`id_firmante`,`borrado`, `estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `lote_cuit`;
CREATE TABLE IF NOT EXISTS `lote_cuit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) NOT NULL,
  `cuit` decimal(11,0) NOT NULL,
  `nombre_apellido` varchar(255) DEFAULT NULL,
  `tipo_titulo` varchar(255) DEFAULT NULL,
  `titulo_nombre` varchar(255) DEFAULT NULL,
  `nivel` varchar(255) DEFAULT NULL,
  `pago_monto` float DEFAULT NULL,
  `pago_cuotas` int(11) DEFAULT NULL,
  `fecha_fin_contrato` date DEFAULT NULL,
  `aprobacion_administracion` enum('SIN_REVISION','APROBADO','RECHAZADO') DEFAULT NULL,
  `aprobacion_desarrollo` enum('SIN_REVISION','APROBADO','RECHAZADO') DEFAULT NULL,
  `aprobacion_control` enum('SIN_REVISION','APROBADO','RECHAZADO') DEFAULT NULL,
  `aprobacion_liquidacion` enum('SIN_REVISION','APROBADO','RECHAZADO') DEFAULT NULL,
  `aprobacion_convenios` enum('SIN_REVISION','APROBADO','RECHAZADO') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lote_cuit_1_idx` (`id_lote`),
  KEY `fk_lote_cuit_2_idx` (`cuit`, `aprobacion_administracion`, `aprobacion_desarrollo`, `aprobacion_control`, `aprobacion_liquidacion`, `aprobacion_convenios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `observaciones_lotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) NOT NULL,
  `id_lote_cuit` int(11) NULL,
  `id_usuario` int(11) NULL,
  `id_motivo` int(11) NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_observaciones_1_idx` (`id_lote_cuit`, `id_lote`, `id_usuario`),
  CONSTRAINT `fk_observaciones_lotes_1` FOREIGN KEY (`id_lote_cuit`) REFERENCES `lote_cuit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_observaciones_lotes_2` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `contrato`;
DROP TABLE IF EXISTS `contratos`;
CREATE TABLE IF NOT EXISTS `contratos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `id_lote` INT(11) NOT NULL,
  `cuit` DECIMAL(11,0) NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NOT NULL,
  `acto_administrativo` VARCHAR(255) NOT NULL,
  `fecha_acto_administrativo` DATE NOT NULL,
  `pago_monto` DECIMAL( 10, 2 ) NULL,
  `pago_cuotas` INT(11) NULL,
  `id_historial_lote` BIGINT NULL,
  `id_historial_lote_cuit` BIGINT NULL,
  `id_historial_contratante` BIGINT NULL,
  `id_historial_firmante` BIGINT NULL,
  `id_historial_empleados` BIGINT NULL,
  `id_historial_e_lic_especiales` BIGINT NULL,
  `id_historial_e_x_ubicacion` BIGINT NULL,
  `id_historial_e_comision` BIGINT NULL,
  `id_historial_e_dependencia` BIGINT NULL,
  `id_historial_e_dep_informales` BIGINT NULL,
  `id_historial_e_escalafon` BIGINT NULL,
  `id_historial_e_horarios` BIGINT NULL,
  `id_historial_e_perfil` BIGINT NULL,
  `id_historial_e_presupuesto` BIGINT NULL,
  `id_historial_personas` BIGINT NULL,
  `id_historial_p_discapacidad` BIGINT NULL,
  `id_historial_p_domicilio` BIGINT NULL,
  `id_historial_p_titulo` BIGINT NULL,
  `borrado` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `contrato_id_empleado_IDX` USING BTREE (`id_empleado` ASC),
  INDEX `contrato_id_lote_IDX` USING BTREE (`id_lote` ASC),
  INDEX `contrato_cuit_IDX` USING BTREE (`cuit` ASC),
  INDEX `contrato_id_historial_lote_IDX` USING BTREE (`id_historial_lote` ASC),
  INDEX `contrato_id_historial_lote_cuit_IDX` USING BTREE (`id_historial_lote_cuit` ASC),
  INDEX `contrato_id_historial_empleados_IDX` USING BTREE (`id_historial_empleados` ASC),
  INDEX `contrato_id_historial_contratante_IDX` USING BTREE (`id_historial_contratante` ASC),
  INDEX `contrato_id_historial_firmante_IDX` USING BTREE (`id_historial_firmante` ASC),
  INDEX `contrato_id_historial_e_lic_especiales_IDX` USING BTREE (`id_historial_e_lic_especiales` ASC),
  INDEX `contrato_id_historial_e_x_ubicacion_IDX` USING BTREE (`id_historial_e_x_ubicacion` ASC),
  INDEX `contrato_id_historial_e_comision_IDX` USING BTREE (`id_historial_e_comision` ASC),
  INDEX `contrato_id_historial_e_dependencia_IDX` USING BTREE (`id_historial_e_dependencia` ASC),
  INDEX `contrato_id_historial_e_dep_informales_IDX` USING BTREE (`id_historial_e_dep_informales` ASC),
  INDEX `contrato_id_historial_e_escalafon_IDX` USING BTREE (`id_historial_e_escalafon` ASC),
  INDEX `contrato_id_historial_e_horarios_IDX` USING BTREE (`id_historial_e_horarios` ASC),
  INDEX `contrato_id_historial_e_perfil_IDX` USING BTREE (`id_historial_e_perfil` ASC),
  INDEX `contrato_id_historial_e_presupuesto_IDX` USING BTREE (`id_historial_e_presupuesto` ASC),
  INDEX `contrato_id_historial_personas_IDX` USING BTREE (`id_historial_personas` ASC),
  INDEX `contrato_id_historial_p_discapacidad_IDX` USING BTREE (`id_historial_p_discapacidad` ASC),
  INDEX `contrato_id_historial_p_domicilio_IDX` USING BTREE (`id_historial_p_domicilio` ASC),
  INDEX `contrato_id_historial_p_titulo_IDX` USING BTREE (`id_historial_p_titulo` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

ALTER TABLE  `lote_cuit` ADD  `borrado` TINYINT( 1 ) NULL DEFAULT  '0',
ADD INDEX (  `borrado` ) ;

ALTER TABLE  `lote_cuit` CHANGE  `pago_monto`  `pago_monto` DECIMAL( 10, 2 ) NULL DEFAULT NULL ;

ALTER TABLE `lote_cuit`
  DROP `tipo_titulo`,
  DROP `titulo_nombre`,
  DROP `nivel`;

ALTER TABLE  `empleados` CHANGE  `estado`  `estado` TINYINT( 9 ) NULL DEFAULT  '1';

ALTER TABLE `lote` ADD INDEX `lote_estado_idx` (`estado` ASC);

DROP TABLE IF EXISTS `denominacion_funcion`;
CREATE TABLE `denominacion_funcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
LOCK TABLES `denominacion_funcion` WRITE;
INSERT INTO `denominacion_funcion` VALUES (1,'Profesional Avanzado',0),(2,'Jefe De Departamento Impositivo',0),(3,'Auxiliar Tecnico De Servicios',0),(4,'Supervisor De Control De Servicios',0),(5,'Oficial Soldador',0),(6,'Asistente Tecnico Vehicular',0),(7,'Responsable De Equipamiento Y Deposito',0),(8,'Asistente Administrativo',0),(9,'Asistente Servicios Generales',0),(10,'Responsable Automotor',0),(11,'Asistente Técnico',0),(12,'Tecnico',0),(13,'Asesora En Sig',0),(14,'Responsable Administrativo',0),(15,'Asesor',0),(16,'Asistente Tecnico Administrativo',0),(17,'Tecnico En Capacitación',0),(18,'Asesora Legal',0),(19,'Asistente Administrativo En Capacitación',0),(20,'Asesora Experta',0),(21,'Auxiliar Administrativo',0),(22,'Asistente Contable Administrativo',0),(23,'Tecnica Administrativa',0),(24,'Asesora',0),(25,'Asesor Legal Especializado En Derecho Administrativo',0),(26,'Profesional Especializado En Consolidación De Deuda',0),(27,'Responsable Tecnico Administrativo Especializado En Recursos',0),(28,'Soldador',0),(29,'Tecnico Administrativo',0),(30,'Responsable De Embargos',0),(31,'Consultor Tecnico Administrativo',0),(32,'Asesor Legal',0),(33,'Responsable Tecnico Administrativo',0),(34,'Responsable Administrativo Contable',0),(35,'Chofer',0),(36,'Coordinador Administrativo',0),(37,'Asesor Tecnico Administrativo De Planificacion Y Control De ',0),(38,'Asistente En Conservacion Patrimonial',0),(39,'Analista Administrativo',0),(40,'Supervisor',0),(41,'Responsable Tecnico Administrativo Especializado En Recursos',0),(42,'Analista Profesional',0),(43,'Asistente De Mantenimiento Y Servicios Generales',0),(44,'Asesor Tecnico-administrativo Especializado',0),(45,'Asesor Experto En Derecho Administrativo',0),(46,'Asesor Especializado En Rrhh',0),(47,'Asistente Electricista',0),(48,'Auxiliar Soldador',0),(49,'Asesor Administrativo Especializado',0),(50,'Analista Tecnico',0),(51,'Asesor Especializado En Fideicomiso',0),(52,'Asesor Experto',0),(53,'Oficial En Cerrajeria Y Mantenimiento',0),(54,'Supervisor De Bienes De Consumo Y Mobiliario',0),(55,'Capacitadora Y Analista Programadora De Sig',0),(56,'Asesor Experto En Liquidacion De Haberes',0),(57,'Asesora Especializada',0),(58,'Asesora Legal Especializada',0),(59,'Asesor Experto En Derecho Administrativo De Gestion De Compr',0),(60,'Asistente De Servicios Generales',0),(61,'Coordinador Profesional',0),(62,'Asesora Contable Especializada',0),(63,'Responsable De Viaticos Y Movilidad',0),(64,'Coordinador',0),(65,'Asesor En Rrhh',0),(66,'Asistente En Procesos Administrativos Informaticos',0),(67,'Administrativo',0),(68,'Asesor Especializado Economico Financiero',0),(69,'Asesora Legal Y Juridica',0),(70,'Supervisora Tecnica De Proyectos De Centros De Trasbordo',0),(71,'Asesora Especializada En Transporte',0),(72,'Asesor Tecnico',0),(73,'Asesor De La Dirección De Administración Financiera',0),(74,'Oficial De Manteniemiento',0),(75,'Auxiliar Tecnico Administrativo',0),(76,'Asesora Especializada Legal',0),(77,'Asesor Experto Juridico Contable',0),(78,'Mecanico Naval',0),(79,'Asesor Experto Contable',0),(80,'Tecnico En Oferta Libre Y Turismo',0),(81,'Asesora Especializada En Liquidación De Sueldos',0),(82,'Tecnico Profesional',0),(83,'Auxiliar En Conservacion Patrimonial',0),(84,'Coordinador De Legajos, Certificaciones Y Asistencias',0),(85,'Asistente De Logistica Y Administracion',0),(86,'Asistnte Administrativo',0),(87,'Profesional Especializado',0),(88,'Coordinadora Del Plan Estrategico De Transporte',0),(89,'Supervisor Y Control De Servicis',0),(90,'Asesor Jurídico Experto En Gestión Administrativa',0),(91,'Tecnico Enlace Internacional',0),(92,'Asesor Experto En Planificación Y Comunicación',0),(93,'Asesora Especializada Legal Y Juridica',0),(94,'Asesor Experto En Contratacion De Bienes Y Servicios',0),(95,'Responsable De La Mesa De Entradas De La Oficina Privada De ',0),(96,'Asesor En Capacitación Y Desarrollo De Carrera',0),(97,'Asesor Especializado',0),(98,'Asesor Tecnico Experto',0),(99,'Asesor Experto En Comunicación Y Contenido',0),(100,'Coordinadora De Comunicación Directa Y Activaciones',0),(101,'Analista Juridico',0),(102,'Supervisor De Comunicaciones De Vialidad Y Movilidad Urbana',0),(103,'Analista Principal',0),(104,'Asesor Legal Experto En Gestion Administrativa',0),(105,'Profesional Especializado En Presupuesto',0),(106,'Asistente En Servicios Generales',0),(107,'Asesor Especializado Adminisitrativo',0),(108,'Auxiliar Tecnico Vehicular',0),(109,'Tecnico Mecanico',0),(110,'Asistente Adminitrativo',0),(111,'Asesor El Area De Proyecto De Arquitectura',0),(112,'Asesora Técnica Avanzada',0),(113,'Asesora Especializada En Gestion De Transporte Automotor Int',0),(114,'Asistente Automotor Avanzaod',0),(115,'Asesor Especializado En Contratación De Bienes Y Servicios',0),(116,'Asesora Experta En Recursos Humanos',0),(117,'Asesor Juridico',0),(118,'Responsable Tecnico Administrativo Especializado En Recursos',0),(119,'Responsable Experto De Equipo De Convenios',0),(120,'Responsable Tecnica Administrativa Especializada En Recursos',0),(121,'Asistente En Procesos Administrativo - Informáticos',0),(122,'Asesor Especializado En Derecho Administrativo',0);
UNLOCK TABLES;

DROP TABLE IF EXISTS `denominacion_puesto`;
CREATE TABLE `denominacion_puesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
LOCK TABLES `denominacion_puesto` WRITE;
INSERT INTO `denominacion_puesto` VALUES (1,'Analista',0),(2,'Asesor',0),(3,'Auxiliar',0),(4,'Asistente',0),(5,'Experto',0),(6,'Inspector',0),(7,'otros',0);
UNLOCK TABLES;

DROP TABLE IF EXISTS `familia_de_puestos`;
CREATE TABLE `familia_de_puestos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
LOCK TABLES `familia_de_puestos` WRITE;
INSERT INTO `familia_de_puestos` VALUES (1,'Legal',0),(2,'Tareas Técnicas o Sustantivas propias del Organismo',0),(3,'Contable, Presupuestario, Compras y Contrataciones',0),(4,'Administración y Gestión de Personal',0),(5,'Servicios Generales y Mantenimiento',0),(6,'Administración',0),(7,'Gestión Gubernamental',0),(8,'Tecnologías de la Información y las Comunicaciones',0),(9,'Inspección, Verificación y Auditoría',0),(10,'Atencion al publico',0),(11,'Recursos Humanos',0),(12,'Difusión y Ceremonial',0),(13,'Asuntos Jurídicos',0),(14,'Asesor Obras',0);
UNLOCK TABLES;


INSERT INTO convenio_tramos (id, id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('', '1', '1', 'Extraordinario');

INSERT INTO convenio_grados (id_tramo, nombre) VALUES ('5', '11'),('5', '12'),('5', '13'),('5', '14'),('5', '15'),('5', '16'),('5', '17'),('5', '18'),('5', '19'),('5', '20');

ALTER TABLE `empleado_perfil` 
CHANGE COLUMN `objetivo_especifico` `objetivo_especifico` TEXT NULL DEFAULT NULL,
CHANGE COLUMN `estandares` `estandares` TEXT NULL DEFAULT NULL;

ALTER TABLE persona_titulo 
CHANGE COLUMN descripcion id_titulo INT(11) NOT NULL;

CREATE TABLE `titulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `abreviatura` varchar(8) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index2` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `empleado_comision` 
ADD INDEX `fk_empleado_comision_1_idx` (`id_empleado` ASC);
ALTER TABLE `empleado_comision` 
ADD CONSTRAINT `fk_empleado_comision_1`
  FOREIGN KEY (`id_empleado`)
  REFERENCES `empleados` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `empleado_dep_informales` 
ADD INDEX `fk_empleado_dep_informales_1_idx` (`id_empleado` ASC);
ALTER TABLE `empleado_dep_informales` 
ADD CONSTRAINT `fk_empleado_dep_informales_1`
  FOREIGN KEY (`id_empleado`)
  REFERENCES `empleados` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


INSERT INTO convenio_tramos (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('2', '5', 'Sin Tramo');

INSERT INTO convenio_grados (id_tramo, nombre) VALUES ('6', '1');
INSERT INTO convenio_grados (id_tramo, nombre) VALUES ('6', '2');

INSERT INTO convenio_agrupamientos (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('2', '5', '1109');
INSERT INTO convenio_agrupamientos (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('1', '3', 'Especial');
INSERT INTO convenio_agrupamientos (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('1', '4', 'Especial');

INSERT INTO convenio_niveles (id_agrupamiento, nombre) VALUES ('8', 'Asesor');
INSERT INTO convenio_niveles (id_agrupamiento, nombre) VALUES ('8', 'Asistente');
INSERT INTO convenio_niveles (id_agrupamiento, nombre) VALUES ('8', 'Consultor');
INSERT INTO convenio_niveles (id_agrupamiento, nombre) VALUES ('8', 'Asistente Operador');
INSERT INTO convenio_niveles (id_agrupamiento, nombre) VALUES ('9', 'A');
INSERT INTO convenio_niveles (id_agrupamiento, nombre) VALUES ('9', 'B');
INSERT INTO convenio_niveles (id_agrupamiento, nombre) VALUES ('10', 'A');

INSERT INTO convenio_funciones_ejecutivas (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('1', '3', 'Nivel I');
INSERT INTO convenio_funciones_ejecutivas (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('1', '3', 'Nivel II');
INSERT INTO convenio_funciones_ejecutivas (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('1', '3', 'Nivel III');
INSERT INTO convenio_funciones_ejecutivas (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('1', '3', 'Nivel IV');
INSERT INTO convenio_funciones_ejecutivas (id_modalidad_vinculacion, id_situacion_revista, nombre) VALUES ('1', '4', 'Nivel I');

ALTER TABLE `empleado_escalafon` 
CHANGE COLUMN `compensacion_geografica` `compensacion_geografica` VARCHAR(20) NULL DEFAULT '0' ,
CHANGE COLUMN `compensacion_transitoria` `compensacion_transitoria` VARCHAR(20) NULL DEFAULT '0' ;

ALTER TABLE `anticorrupcion` 
ADD COLUMN `borrado` TINYINT(1) NOT NULL DEFAULT 0 AFTER `fecha_aceptacion_renuncia`;

ALTER TABLE `anticorrupcion_presentacion` 
CHANGE COLUMN `nombre_archivo` `archivo` VARCHAR(100) NULL DEFAULT NULL ;

ALTER TABLE `anticorrupcion_presentacion` 
ADD COLUMN `borrado` TINYINT(1) NOT NULL DEFAULT 0 AFTER `archivo`;

ALTER TABLE `titulo` ADD COLUMN `id_tipo_titulo` INT(11) NULL AFTER `id`;

SET FOREIGN_KEY_CHECKS=1;

INSERT INTO db_version VALUES('4.0', now());