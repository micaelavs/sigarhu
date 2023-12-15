CREATE TABLE `nivel_educativo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `borrado` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nivel_educativo` (`nombre`) VALUES ('S/D');
INSERT INTO `nivel_educativo` (`nombre`) VALUES ('Primario');
INSERT INTO `nivel_educativo` (`nombre`) VALUES ('Secundario');
INSERT INTO `nivel_educativo` (`nombre`) VALUES ('Terciario');
INSERT INTO `nivel_educativo` (`nombre`) VALUES ('Universitario');
INSERT INTO `nivel_educativo` (`nombre`) VALUES ('Postgrado');

INSERT INTO db_version VALUES('5.0', now());