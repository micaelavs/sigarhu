ALTER TABLE `empleado_documentos` CHANGE COLUMN `nombre_archivo` `nombre_archivo` VARCHAR(150) NOT NULL ;

INSERT INTO db_version VALUES('10.0', now());