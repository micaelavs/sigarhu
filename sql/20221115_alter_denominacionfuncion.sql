USE `{{{db_app}}}`;
ALTER TABLE denominacion_funcion MODIFY COLUMN nombre varchar(120) NOT NULL;

USE `{{{db_log}}}`;
ALTER TABLE denominacion_funcion MODIFY COLUMN nombre varchar(120) DEFAULT NULL NULL;