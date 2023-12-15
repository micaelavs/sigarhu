USE `{{{db_app}}}`;
ALTER TABLE denominacion_funcion MODIFY COLUMN nombre varchar(120) NOT NULL;

USE `{{{db_log}}}`;
ALTER TABLE denominacion_funcion MODIFY COLUMN nombre varchar(120) DEFAULT NULL NULL;

SET FOREIGN_KEY_CHECKS=0;
USE `{{{db_app}}}`;
ALTER TABLE empleado_escalafon ADD id_grado_liquidacion INT(11) DEFAULT NULL NULL;
USE `{{{db_log}}}`;
ALTER TABLE empleado_escalafon ADD id_grado_liquidacion INT(11) DEFAULT NULL NULL;
SET FOREIGN_KEY_CHECKS=1;

USE `{{{db_app}}}`;
SET FOREIGN_KEY_CHECKS=0;

DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_alta$$
CREATE  TRIGGER `empleado_escalafon_tg_alta` AFTER INSERT ON `empleado_escalafon` FOR EACH ROW
BEGIN
INSERT INTO {{{db_log}}}.empleado_escalafon
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
    `exc_art_14`,
    `unidad_retributiva`,
    `id_grado_liquidacion`
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
    NEW.exc_art_14,
    NEW.unidad_retributiva,
    NEW.id_grado_liquidacion
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_modificacion$$
CREATE  TRIGGER `empleado_escalafon_tg_modificacion` AFTER UPDATE ON `empleado_escalafon` FOR EACH ROW
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
    NEW.exc_art_14 <> OLD.exc_art_14 OR
    NEW.unidad_retributiva <> OLD.unidad_retributiva OR
    NEW.id_grado_liquidacion <> OLD.id_grado_liquidacion 
THEN
    INSERT INTO {{{db_log}}}.empleado_escalafon
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
        `exc_art_14`,
		`unidad_retributiva`,
        `id_grado_liquidacion`
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
        NEW.exc_art_14,
        NEW.unidad_retributiva,
        NEW.id_grado_liquidacion
        );
END IF;
END$$
DELIMITER ;

USE `{{{db_app}}}`;
UPDATE empleado_escalafon 
set id_grado_liquidacion  = id_grado;

INSERT INTO db_version VALUES('23.0', now());