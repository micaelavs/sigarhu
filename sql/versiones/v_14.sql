CREATE TABLE `cola_tareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(255) NOT NULL,
  `parametros` text,
  `en_ejecucion` tinyint(1) NOT NULL DEFAULT '0',
  `pendiente` tinyint(1) NOT NULL DEFAULT '0',
  `md5_sum` varchar(33) NOT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_finish` datetime DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cola_tareas-en_ejecucion` (`en_ejecucion`),
  KEY `cola_tareas-pendiente` (`pendiente`),
  KEY `cola_tareas-borrado` (`borrado`),
  KEY `cola_tareas-md5_sum` (`md5_sum`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `persona_telefono`  CHANGE COLUMN `telefono` `telefono` VARCHAR(255) NULL DEFAULT NULL ;

INSERT INTO db_version VALUES('14.0', now());