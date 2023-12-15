ALTER TABLE `presupuestos` 
ADD COLUMN `borrado` TINYINT(1) NULL DEFAULT 0 AFTER `id_obra`;

CREATE TABLE `responsables_contrato` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NULL DEFAULT NULL,
  `id_dependencia` INT(11) NULL DEFAULT NULL,
  `id_tipo` TINYINT(1) NULL DEFAULT NULL,
  `borrado` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `index_id_empleado` (`id_empleado` ASC),
  INDEX `index_contratante` (`id_dependencia` ASC, `id_tipo` ASC),
  CONSTRAINT `fk_responsables_contrato_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_responsables_contrato_2`
    FOREIGN KEY (`id_dependencia`)
    REFERENCES `dependencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `familia_puestos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `borrado` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `familia_puestos` (`nombre`) VALUES ('Administración y Servicio de Oficina');
INSERT INTO `familia_puestos` (`nombre`) VALUES ('Administración Presupuestaria');
INSERT INTO `familia_puestos` (`nombre`) VALUES ('Asuntos Jurídicos y Normativos');
INSERT INTO `familia_puestos` (`nombre`) VALUES ('Comunicación');
INSERT INTO `familia_puestos` (`nombre`) VALUES ('Control Interno y Auditoría');
INSERT INTO `familia_puestos` (`nombre`) VALUES ('Mantenimiento y Servicios Generales');
INSERT INTO `familia_puestos` (`nombre`) VALUES ('Recursos Humanos');
INSERT INTO `familia_puestos` (`nombre`) VALUES ('TICS');


CREATE TABLE `subfamilia_puestos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_familia` INT(11) NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `borrado` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `subfamilia_puestos` 
ADD INDEX `index_id_familia` (`id_familia` ASC);
ALTER TABLE `subfamilia_puestos` 
ADD CONSTRAINT `fk_subfamilia_puestos_1`
  FOREIGN KEY (`id_familia`)
  REFERENCES `familia_puestos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
  
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('1', 'Atención al ciudadano');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('1', 'Soporte Administrativo');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('2', 'Compras y Contrataciones');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('2', 'Contabilidad');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('2', 'Tesorería');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('2', 'Presupuesto y Finanzas');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('3', 'Asuntos Jurídicos');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('3', 'C.A.E.');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('4', 'Ceremonial y Protocolo');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('4', 'Comunicación y Contenido Institucional');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('5', 'Auditoría');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('6', 'Mantenimiento');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('6', 'Servicios');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('7', 'Administración y Gestión de Personal');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('7', 'Carrera');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('7', 'Salud y Seguridad Ocupacional');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('8', 'Seguridad Informática y Ciberseguridad');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('8', 'Arquitectura de Servicios');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('8', 'Desarrollo');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('8', 'Implementación de Soluciones y Soporte');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('8', 'Gestión de Aplicaciones');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('8', 'Gestión de Infraestructura');
INSERT INTO `subfamilia_puestos` (`id_familia`, `nombre`) VALUES ('8', 'Gestión de Operaciones');


ALTER TABLE `puestos` 
CHANGE COLUMN `nombre` `nombre` VARCHAR(255) NOT NULL ,
ADD COLUMN `id_subfamilia` INT(11) NOT NULL AFTER `id`,
ADD INDEX `index2` (`id_subfamilia` ASC);
ALTER TABLE `puestos` 
ADD CONSTRAINT `fk_puestos_1`
  FOREIGN KEY (`id_subfamilia`)
  REFERENCES `subfamilia_puestos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('1', 'Referente de Atención al Ciudadano');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('1', 'Analista de Atención al Ciudadano');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('1', 'Asistente de Atención al Ciudadano');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('2', 'Secretaria/o');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('2', 'Referente de Soporte Administrativo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('2', 'Analista de Soporte Administrativo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('2', 'Asistente de Soporte Administrativo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('2', 'Bibliotecario');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('3', 'Referente de Compras y Contrataciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('3', 'Analista de Compras y Contrataciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('3', 'Asistente de Compras y Contrataciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('4', 'Referente contable');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('4', 'Analista Contable');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('4', 'Asistente Contable');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('5', 'Referente de Tesorería');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('5', 'Analista de Tesorería');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('5', 'Asistente de Tesorería');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('6', 'Referente de Presupuesto y Finanzas');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('6', 'Analista de Presupuesto y Finanzas');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('6', 'Asistente de Presupuesto y Finanzas');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('7', 'Asesor Legal');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('7', 'Asistente de Asuntos Jurídicos');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('8', 'Abogado Dictaminante');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('8', 'Abogado Litigante');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('8', 'Abogado Sumariante');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('9', 'Referente de Ceremonial y Protocolo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('9', 'Analista de Ceremonial y Protocolo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('9', 'Locutor');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('9', 'Asistente de Ceremonial y Protocolo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('10', 'Referente de Comunicación y Contenido Institucional');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('10', 'Analista de Comunicación y Contenido Institucional');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('10', 'Diseñador de Comunicación y Contenido Institucional');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('10', 'Fotografo/Audiovisual');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('10', 'Asistente de Comunicación y Contenido Institucional');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('11', 'Referente de Control Interno');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('11', 'Analista de Control Interno');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('11', 'Asitente de Control Interno');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('12', 'Referente de Mantenimiento');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('12', 'Asistente de Mantenimiento');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('13', 'Referente de Servicios');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('13', 'Asitente de Servicios');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('13', 'Chofer');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('13', 'Cocinero');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('13', 'Mozo/Camarero');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('14', 'Referente de Administración y Gestión de Personal');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('14', 'Analista de Administración y Gestión de Personal');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('14', 'Asistente de Administración y Gestión de Personal');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('15', 'Referente de Carrera');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('15', 'Analista de Capacitación');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('15', 'Analista de Desarrollo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('15', 'Asistente de Capacitación');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('15', 'Asistente de Desarrollo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('16', 'Médico Laboral');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('16', 'Psicólogo Laboral');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('16', 'Enfermero Laboral');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('16', 'Bombero');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('16', 'Analista de Higiene y Seguridad');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('16', 'Asistente de Higiene y Seguridad');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('16', 'Asistente Materno Infantil');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('17', 'Referente de Seguridad Informática y Ciberseguridad');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('17', 'Analista de Seguridad Informática y Ciberseguridad');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('17', 'Asistente de Seguridad Informática y Ciberseguridad');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('18', 'Referente de Arquitectura y Servicios');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('18', 'Analista de Aquitectura y Servicios');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('18', 'Asistente de Arquitectura y Servicios');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('19', 'Referente de Desarrollo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('19', 'Analista de Desarrollo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('19', 'Asistente de Desarrollo');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('20', 'Referente de Implementaciones de Soluciones y Soporte');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('20', 'Analista de Implementaciones de Soluciones y Soporte');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('20', 'Asistente de Implementaciones de Soluciones y Soporte');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('21', 'Referente de Gestión de Aplicaciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('21', 'Analista de Gestión de Aplicaciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('21', 'Asistente de Gestión de Aplicaciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('22', 'Referente de Gestión de Infraestructura');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('22', 'Analista de Gestión de Infraestructura');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('22', 'Asistente de Gestión de Infraestructura');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('23', 'Referente de Gestión de Operaciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('23', 'Analista de Gestión de Operaciones');
INSERT INTO `puestos` (`id_subfamilia`, `nombre`) VALUES ('23', 'Asistente de Gestión de Operaciones');


INSERT INTO `convenio_agrupamientos` (`id_modalidad_vinculacion`, `id_situacion_revista`, `nombre`) VALUES ('3', '9', 'Sin Agrupamiento');


INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '1');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '2');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '3');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '4');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '5');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '6');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '7');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '8');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '9');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '10');
INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`) VALUES ('12', '11');

INSERT INTO db_version VALUES('6.0', now());