CREATE TABLE `grupo_familiar` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `parentesco` TINYINT(1) NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellido` VARCHAR(50) NOT NULL,
  `fecha_nacimiento` DATE NULL,
  `nacionalidad` VARCHAR(4) NULL,
  `tipo_documento` TINYINT(1) NOT NULL,
  `documento` VARCHAR(10) NOT NULL,
  `nivel_educativo` TINYINT(1) NULL,
  `reintegro_guarderia` TINYINT(1) DEFAULT 0,
  `discapacidad` TINYINT(1) DEFAULT 0,
  `desgrava_afip` INT(2) NULL,
  `fecha_desde` DATE NULL,
  `fecha_hasta` DATE NULL,
  `borrado` TINYINT(1) NULL DEFAULT 0,
PRIMARY KEY (`id`),
  KEY `fk_grupo_familiar_1_idx` (`id_empleado`),
  CONSTRAINT `fk_grupo_familiar_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `embargos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `tipo_embargo` TINYINT(1),
  `autos` VARCHAR(255) NULL,
  `fecha_alta` DATE NULL DEFAULT NULL,
  `fecha_cancelacion` DATE NULL DEFAULT NULL,
  `monto` VARCHAR(45) NULL,
  `borrado` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `index1` (`id_empleado` ASC),
  CONSTRAINT `fk_embargos_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE `familiar_discapacidad` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_familiar` INT(11) NOT NULL,
  `id_tipo_discapacidad` INT(11) NOT NULL,
  `cud` varchar(45) DEFAULT NULL,
  `fecha_alta` DATE NULL,
  `fecha_vencimiento` DATE NULL,
  `borrado` TINYINT(1) NULL DEFAULT 0,
PRIMARY KEY (`id`),
  KEY `fk_familiar_discapacidad_1_idx` (`id_familiar`),
  CONSTRAINT `fk_familiar_discapacidad_1` FOREIGN KEY (`id_familiar`) REFERENCES `grupo_familiar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `obras_sociales`
--
CREATE TABLE `obras_sociales` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(6) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `borrado` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('1', '000000', 'SIN OBRA SOCIAL');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('2', '000406', 'O.S.P.O.C.E.');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('3', '000901', 'O.S. ACT. SEGUROS Y REA');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('4', '001201', 'O.S.MINIST.ECONOM.Y S.P');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('5', '002402', 'OS PERSONAL JERARQUIC');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('6', '002501', 'O.S. MINISTROS, SECRETA');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('7', '003009', 'O.S.PERS.A.A.M.SANCOR');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('8', '003108', 'O.S.INM.ESPAÑOLESYDESC.');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('9', '100304', 'O.S.TEC.VUELOS LIN. AER');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('10', '100502', 'O.S. PERS. AERONAUTICO');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('11', '100601', 'O.S.P.AERON.ENTES PRIV.');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('12', '100809', 'O.S. AERONAVEGANTES');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('13', '100908', 'O.S.EMP.AGEN.INFORMES');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('14', '101604', 'O.S.PERSONAL DEL A.C.A.');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('15', '102904', 'OS PERSONAL DE BARRAC');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('16', '104306', 'O.S. ACTIV. CERVECERA Y');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('17', '106609', 'O.S. ELECTRICISTAS NAVA');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('18', '111407', 'O.S.CAP.ULTRAMAR Y OFIC');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('19', '111506', 'O.S. DE CAP BAQ FLUV');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('20', '111803', 'O S PERSONAL MARITIMO');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('21', '111902', 'OBRA SOCIAL DEL SINDI');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('22', '112004', 'O.S. PERS.SUP. M.BENZ');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('23', '112202', 'O.S. S.I.M.R.A.');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('24', '112608', 'O.S. PERSONAL IND. MO');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('25', '113304', 'OS DE JEFES Y OFICIAL');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('26', '113809', 'O.S. COMISARIOS NAVALES');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('27', '114505', 'O.S. PAT.CAB.RIOS Y PUE');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('28', '115300', 'O.S. DE PETROLEROS');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('29', '116105', 'O.S. CAP.ESTIB.PORTUARI');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('30', '118606', 'O.S. PERS.DE PUBLICIDAD');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('31', '119203', 'O.S. RELOJEROS, JOYEROS');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('32', '119906', 'O.S. SERENOS DE BUQUES');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('33', '121705', 'O.S.PERS.ACTIV.TURF');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('34', '123305', 'O.S. PERS.SOC.AUTORES');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('35', '125707', 'UNION PERSONAL');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('36', '125905', 'O.S. ARBITROS DEP. R.A.');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('37', '126205', 'O.S. EMPLEADOS COMERCIO');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('38', '127208', 'O S MANDOS MEDIOS TELE');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('39', '400800', 'O.S. DE EJECUTIVOS Y DE');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('40', '400909', 'O.S.ACC.SOC.EMPRESARIOS');
INSERT INTO `obras_sociales` (`id`, `codigo`, `nombre`) VALUES ('41', '401209', 'O.S. DIR. IND. METALURG');


--
-- Table structure for table `sindicatos`
--
CREATE TABLE `sindicatos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `borrado` TINYINT(1) NULL DEFAULT 0,
PRIMARY KEY (`id`))
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `sindicatos` (`id`, `codigo`, `nombre`) VALUES ('1', 'ATE', 'Asociación de Trabajadores del Estado');
INSERT INTO `sindicatos` (`id`, `codigo`, `nombre`) VALUES ('2', 'UPCN', 'Unión del Personal Civil de la Nación');
INSERT INTO `sindicatos` (`id`, `codigo`, `nombre`) VALUES ('3', 'SIPEDYB', 'Sindicato de Dragado y Balizamiento');

--
-- Table structure for table `seguro_vida`
--
CREATE TABLE `seguro_vida` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `borrado` TINYINT(1) NULL DEFAULT 0,
PRIMARY KEY (`id`))
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `seguro_vida` (`id`, `nombre`) VALUES ('1', 'Seguro de vida Obligatorio');
INSERT INTO `seguro_vida` (`id`, `nombre`) VALUES ('2', 'Seguro de vida Colectivo Optativo');
INSERT INTO `seguro_vida` (`id`, `nombre`) VALUES ('3', 'Seguro de vida Colectivo Adherente');
INSERT INTO `seguro_vida` (`id`, `nombre`) VALUES ('4', 'Seguro de vida Adicional');

--
-- Table structure for table `empleado_salud`
--
CREATE TABLE `empleado_salud` (
  `id` INT(11)  NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `id_obra_social` INT(11) NULL,
  `id_seguro_vida` INT(11) NULL,
  `borrado` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_salud_1_idx` (`id_empleado`),
  CONSTRAINT `fk_empleado_salud_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `empleado_sindicatos`
--
CREATE TABLE `empleado_sindicatos` (
  `id` INT(11)  NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `id_sindicato` INT(11) NULL,
  `fecha_desde` DATE NOT NULL,
  `fecha_hasta` DATE  NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_sindicato_1_idx` (`id_empleado`),
  CONSTRAINT `fk_empleado_sindicato_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `empleados` 
ADD COLUMN `veterano_guerra` TINYINT(1) NULL DEFAULT 0 AFTER `fecha_vencimiento`;

ALTER TABLE `personas` ADD COLUMN foto_persona VARCHAR(100) NULL AFTER email;


ALTER TABLE `empleados` 
ADD COLUMN `id_sindicato` INT(11) NULL DEFAULT NULL AFTER `antiguedad_adm_publica`,
ADD COLUMN `fecha_vigencia_mandato` DATE NULL DEFAULT NULL AFTER `id_sindicato`;

CREATE TABLE `empleado_horas_extras` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` INT(11) NOT NULL,
  `anio` VARCHAR(4) NOT NULL,
  `mes` VARCHAR(2) NOT NULL,
  `acto_administrativo` VARCHAR(45) NOT NULL,
  `borrado` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `fk_empleado_horas_extras_1_idx` (`id_empleado` ASC),
  CONSTRAINT `fk_empleado_horas_extras_1`
    FOREIGN KEY (`id_empleado`)
    REFERENCES `empleados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `empleado_escalafon` 
ADD COLUMN `ultimo_cambio_nivel` DATE NULL DEFAULT NULL AFTER `fecha_fin`,
ADD COLUMN `exc_art_14` VARCHAR(45) NULL DEFAULT NULL AFTER `ultimo_cambio_nivel`;

INSERT INTO db_version VALUES('3.0', now());