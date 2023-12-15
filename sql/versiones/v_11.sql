/*########################### ATENCIÓN ###########################*/
/*############### CREAR NUEVO ESQUEMA DE HISTORIAL ###############*/
/*#### SI LA BASE DE HISTORIAL NO SE LLAMA "sigarhu_historial"####*/
/*####### SE DEBERÁ CAMBIAR EN LOS SCRIPTS DE LOS TRIGGERS #######*/
/*###################### EN SCRIPT 20190916_unidad_retributiva.sql #######################*/
USE  sigarhu_historial;
SET FOREIGN_KEY_CHECKS = 0;

#DROP TABLE IF EXISTS `convenio_unidades_retributivas`;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_unidades_retributivas` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_unidades_retributivas` int(11),
  `id_nivel` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `maximo` int(10) unsigned NOT NULL,
  `minimo` int(10) unsigned DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `index_ur_2` (`id_usuario`,`id_nivel`,`id_grado`,`borrado`,`fecha_inicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#DROP TABLE IF EXISTS `convenio_ur_montos`;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_ur_montos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_ur_montos` int(11),
  `id_nivel` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `monto` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `index_ur_monto_2` (`id_usuario`,`id_nivel`,`id_grado`,`borrado`,`fecha_inicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `empleado_escalafon` 
ADD COLUMN `unidad_retributiva` INT NULL AFTER `exc_art_14`;


SET FOREIGN_KEY_CHECKS = 1;
