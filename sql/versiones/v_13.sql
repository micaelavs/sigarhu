CREATE VIEW `tv_persona_titulos` AS
    SELECT 
        `__pt`.`id_persona` AS `id_persona`,
        `__pt`.`id_tipo_titulo` AS `id_tipo_titulo`,
        `__pt`.`id_estado_titulo` AS `id_estado_titulo`,
        `__t`.`nombre` AS `nombre`,
        `__pt`.`fecha` AS `fecha`,
        `__pt`.`principal` AS `principal`
    FROM
        `persona_titulo` `__pt`
    JOIN `titulo` `__t` ON `__t`.`id` = `__pt`.`id_titulo` AND `__pt`.`borrado` = 0
    ORDER BY `__pt`.`id_persona`,`__pt`.`principal` DESC , `__pt`.`id_tipo_titulo` DESC;


CREATE VIEW `tv_persona_otros_estudios` AS
    SELECT 
        `persona_otros_conocimientos`.`id_persona` AS `id_persona`,
        `persona_otros_conocimientos`.`id_tipo` AS `id_tipo`,
        GROUP_CONCAT('["',
            `persona_otros_conocimientos`.`descripcion`,
            '","',
            DATE_FORMAT(`persona_otros_conocimientos`.`fecha`,
                    '%d/%m/%Y'),
            '"]'
            SEPARATOR ',') AS `otros_e_c`
    FROM
        `persona_otros_conocimientos`
    WHERE
        (`persona_otros_conocimientos`.`borrado` = 0)
    GROUP BY `persona_otros_conocimientos`.`id_persona` , `persona_otros_conocimientos`.`id_tipo`;



CREATE VIEW `tv_grupo_familiar` AS
    SELECT 
        `grupo_familiar`.`id_empleado` AS `id_empleado`,
        GROUP_CONCAT('["',
            REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`grupo_familiar`.`parentesco`,
                                        1,
                                        'HIJO/A'),
                                    2,
                                    'ESPOSO/A'),
                                3,
                                'MADRE'),
                            4,
                            'PADRE'),
                        5,
                        'CÃ“NYUGE'),
                    6,
                    'CONVIVIENTE'),
                7,
                'OTROS'),
            '":"',
            CONCAT(`grupo_familiar`.`nombre`,
                    ' ',
                    `grupo_familiar`.`apellido`),
            '",',
            '"NACIMIENTO":"',
            DATE_FORMAT(`grupo_familiar`.`fecha_nacimiento`,
                    '%d/%m/%Y'),
            '","',
            REPLACE(REPLACE(`grupo_familiar`.`tipo_documento`,
                    1,
                    'DNI'),
                2,
                'DU'),
            '":"',
            `grupo_familiar`.`documento`,
            '","',
            '"ESTUDIO":"',
            REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`grupo_familiar`.`nivel_educativo`,
                                        1,
                                        'S/D'),
                                    2,
                                    'Primario'),
                                3,
                                'Secundario'),
                            4,
                            'Terciario'),
                        5,
                        'Universitario'),
                    6,
                    'Postgrado'),
                0,
                'S/D'),
            '",',
            '"REINTEGRO GUARDERIA":"',
            IF((`grupo_familiar`.`reintegro_guarderia` = 1),
                'SI',
                'NO'),
            '",',
            '"DISCAPACIDAD":"',
            IF((`grupo_familiar`.`discapacidad` = 1),
                'SI',
                'NO'),
            '"]'
            SEPARATOR ',') AS `familiares`
    FROM
        `grupo_familiar`
    WHERE
        (`grupo_familiar`.`borrado` = 0)
    GROUP BY `grupo_familiar`.`id_empleado`;




CREATE VIEW `tv_empleado_embargos` AS
    SELECT 
        `embargos`.`id_empleado` AS `id_empleado`,
        GROUP_CONCAT('["TIPO":"',
            REPLACE(REPLACE(`embargos`.`tipo_embargo`,
                    1,
                    'Ejecutivo'),
                2,
                'Familiar'),
            '",',
            '"AUTOS":"',
            `embargos`.`autos`,
            '",',
            '"ALTA":"',
            DATE_FORMAT(`embargos`.`fecha_alta`, '%d/%m/%Y'),
            '",',
            '"CANCELACION":"',
            DATE_FORMAT(`embargos`.`fecha_cancelacion`,
                    '%d/%m/%Y'),
            '",',
            '"MONTO":"',
            IF((`embargos`.`tipo_embargo` = 1),
                '$',
                '%'),
            `embargos`.`monto`,
            '"]'
            SEPARATOR ',') AS `embargos`
    FROM
        `embargos`
    WHERE
        ((`embargos`.`borrado` = 0)
            AND (`embargos`.`fecha_cancelacion` > NOW()))
    GROUP BY `embargos`.`id_empleado`;


CREATE VIEW `tv_empleado_sindicatos` AS
    SELECT 
        `__es`.`id_empleado` AS `id_empleado`,
        GROUP_CONCAT('["', `__s`.`nombre`, '"]'
            SEPARATOR ',') AS `nombres`
    FROM
        (`empleado_sindicatos` `__es`
        JOIN `sindicatos` `__s` ON (((`__s`.`id` = `__es`.`id_sindicato`)
            AND (`__s`.`borrado` = 0))))
    WHERE
        ISNULL(`__es`.`fecha_hasta`)
    GROUP BY `__es`.`id_empleado`; 


CREATE VIEW `tv_empleado_anticorrupcion` AS
    SELECT 
        `a`.`id` AS `id`,
        `a`.`id_empleado` AS `id_empleado`,
        `a`.`fecha_designacion` AS `fecha_designacion`,
        `a`.`fecha_publicacion_designacion` AS `fecha_publicacion_designacion`,
        `a`.`fecha_aceptacion_renuncia` AS `fecha_aceptacion_renuncia`,
        `ap`.`id` AS `id_presentacion`,
        REPLACE(REPLACE(REPLACE(`ap`.`tipo_presentacion`,
                    2,
                    'Anual'),
                1,
                'Inicial'),
            3,
            'Baja') AS `tipo_presentacion`,
        `ap`.`fecha_presentacion` AS `fecha_presentacion`,
        `ap`.`periodo` AS `periodo`,
        `ap`.`nro_transaccion` AS `nro_transaccion`
    FROM
        (`anticorrupcion` `a`
        LEFT JOIN `anticorrupcion_presentacion` `ap` ON (((`a`.`id` = `ap`.`id_anticorrupcion`)
        AND (`a`.fecha_aceptacion_renuncia IS NULL) AND (`ap`.`borrado` = 0))))
    WHERE
        (`a`.`borrado` = 0)
    ORDER BY `ap`.`tipo_presentacion` DESC , `ap`.`periodo` DESC;


    CREATE VIEW `tv_empleado_seguros_vida` AS
    SELECT 
        `esg`.`id_empleado` AS `id_empleado`,
        CONCAT('[ ',
                GROUP_CONCAT(`sv`.`nombre`
                    SEPARATOR ' ] , [ '),
                ' ]') AS `seguro_vida`
    FROM
        (`empleado_seguros` `esg`
        LEFT JOIN `seguro_vida` `sv` ON (((`sv`.`id` = `esg`.`id_seguro`)
            AND ISNULL(`esg`.`fecha_hasta`))))
    GROUP BY `esg`.`id_empleado`;


    CREATE VIEW `tv_empleado_resultados` AS
    SELECT 
        `perfil_resultado_parc_final`.`id_perfil` AS `id_perfil`,
        GROUP_CONCAT('["',
            `perfil_resultado_parc_final`.`nombre`,
            '"]'
            SEPARATOR ',') AS `resultados`
    FROM
        `perfil_resultado_parc_final`
    WHERE
        (`perfil_resultado_parc_final`.`borrado` = 0)
    GROUP BY `perfil_resultado_parc_final`.`id_perfil`;


    CREATE VIEW `tv_empleado_actividades` AS
    SELECT 
        `perfil_actividades`.`id_perfil` AS `id_perfil`,
        GROUP_CONCAT('["',
            `perfil_actividades`.`nombre`,
            '"]'
            SEPARATOR ',') AS `actividades`
    FROM
        `perfil_actividades`
    WHERE
        (`perfil_actividades`.`borrado` = 0)
    GROUP BY `perfil_actividades`.`id_perfil`;


    CREATE VIEW `tv_persona_telefonos` AS
    SELECT 
        `persona_telefono`.`id_persona` AS `id_persona`,
        GROUP_CONCAT('["',
            REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`persona_telefono`.`id_tipo_telefono`,
                                1,
                                'MOVIL'),
                            2,
                            'FIJO'),
                        3,
                        'LABORAL'),
                    4,
                    'LABORAL MOVIL'),
                5,
                'OTROS'),
            '":"',
            `persona_telefono`.`telefono`,
            '"]'
            SEPARATOR ',') AS `telefonos`
    FROM
        `persona_telefono`
    WHERE
        ISNULL(`persona_telefono`.`fecha_baja`)
    GROUP BY `persona_telefono`.`id_persona`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS lote;
DROP TABLE IF EXISTS lote_cuit;
DROP TABLE IF EXISTS contrato;

SET FOREIGN_KEY_CHECKS = 1;      

INSERT INTO db_version VALUES('13.0', now());
