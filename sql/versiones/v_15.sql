ALTER TABLE `sigarhu_historial`.`persona_telefono`  CHANGE COLUMN `telefono` `telefono` VARCHAR(255) NULL DEFAULT NULL ;

INSERT INTO db_version VALUES('15.0', now());