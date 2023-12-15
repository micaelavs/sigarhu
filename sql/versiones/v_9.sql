/*########################### ATENCIÓN ###########################*/
/*#### SI LA BASE DE HISTORIAL NO SE LLAMA "sigarhu_historial"####*/
/*####### SE DEBERÁ CAMBIAR EN LOS SCRIPTS DE LOS TRIGGERS #######*/
/*################################################################*/

DELIMITER $$
DROP TRIGGER IF EXISTS comisiones_tg_alta$$
CREATE TRIGGER `comisiones_tg_alta` AFTER INSERT ON `comisiones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.comisiones
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_comisiones`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS comisiones_tg_modificacion$$
CREATE TRIGGER `comisiones_tg_modificacion` AFTER UPDATE ON `comisiones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.comisiones
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_comisiones`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS dependencias_tg_alta$$
CREATE TRIGGER `dependencias_tg_alta` AFTER INSERT ON `dependencias` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.dependencias
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_dependencias`,
    `nombre`,
    `codep`,
    `id_padre`,
    `fecha_desde`,
    `fecha_hasta`,
    `nivel`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.codep,
    NEW.id_padre,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.nivel
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS dependencias_tg_modificacion$$
CREATE TRIGGER `dependencias_tg_modificacion` AFTER UPDATE ON `dependencias` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.dependencias
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_dependencias`,
    `nombre`,
    `codep`,
    `id_padre`,
    `fecha_desde`,
    `fecha_hasta`,
    `nivel`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B", "M"),
    OLD.id,
    NEW.nombre,
    NEW.codep,
    NEW.id_padre,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.nivel
    );
END$$
DELIMITER ;





DELIMITER $$
DROP TRIGGER IF EXISTS dependencias_informales_tg_alta$$
CREATE TRIGGER `dependencias_informales_tg_alta` AFTER INSERT ON `dependencias_informales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.dependencias_informales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_dependencias_informales`,
    `id_dependencia`,
    `nombre`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_dependencia,
    NEW.nombre,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS dependencias_informales_tg_modificacion$$
CREATE TRIGGER `dependencias_informales_tg_modificacion` AFTER UPDATE ON `dependencias_informales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.dependencias_informales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_dependencias_informales`,
    `id_dependencia`,
    `nombre`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B", "M"),
    OLD.id,
    NEW.id_dependencia,
    NEW.nombre,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS embargos_tg_alta$$
CREATE TRIGGER `embargos_tg_alta` AFTER INSERT ON `embargos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.embargos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_embargos`,
    `id_empleado`,
    `tipo_embargo`,
    `autos`,
    `fecha_alta`,
    `fecha_cancelacion`,
    `monto`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.tipo_embargo,
    NEW.autos,
    NEW.fecha_alta,
    NEW.fecha_cancelacion,
    NEW.monto,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS embargos_tg_modificacion$$
CREATE TRIGGER `embargos_tg_modificacion` AFTER UPDATE ON `embargos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.embargos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_embargos`,
    `id_empleado`,
    `tipo_embargo`,
    `autos`,
    `fecha_alta`,
    `fecha_cancelacion`,
    `monto`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.tipo_embargo,
    NEW.autos,
    NEW.fecha_alta,
    NEW.fecha_cancelacion,
    NEW.monto,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS empleado_comision_tg_alta$$
CREATE TRIGGER `empleado_comision_tg_alta` AFTER INSERT ON `empleado_comision` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_comision
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_comision`,
    `id_empleado`,
    `id_comision_origen`,
    `id_comision_destino`,
    `fecha_inicio`,
    `fecha_fin`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_comision_origen,
    NEW.id_comision_destino,
    NEW.fecha_inicio,
    NEW.fecha_fin
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_comision_tg_modificacion$$
CREATE TRIGGER `empleado_comision_tg_modificacion` AFTER UPDATE ON `empleado_comision` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_comision
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_comision`,
    `id_empleado`,
    `id_comision_origen`,
    `id_comision_destino`,
    `fecha_inicio`,
    `fecha_fin`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_fin <> "", "B","M"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_comision_origen,
    NEW.id_comision_destino,
    NEW.fecha_inicio,
    NEW.fecha_fin
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS empleado_dependencia_tg_alta$$
CREATE TRIGGER `empleado_dependencia_tg_alta` AFTER INSERT ON `empleado_dependencia` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_dependencia
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_dependencia`,
    `id_empleado`,
    `id_dependencia`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_dependencia,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_dependencia_tg_modificacion$$
CREATE TRIGGER `empleado_dependencia_tg_modificacion` AFTER UPDATE ON `empleado_dependencia` FOR EACH ROW
BEGIN
IF NEW.fecha_desde <> OLD.fecha_desde OR NEW.fecha_hasta <> "" THEN
    INSERT INTO sigarhu_historial.empleado_dependencia
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_empleado_dependencia`,
        `id_empleado`,
        `id_dependencia`,
        `fecha_desde`,
        `fecha_hasta`,
        `borrado`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0 , "M","B"),
        OLD.id,
        NEW.id_empleado,
        NEW.id_dependencia,
        NEW.fecha_desde,
        NEW.fecha_hasta,
        NEW.borrado
        );
END IF;
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_dep_informales_tg_alta$$
CREATE TRIGGER `empleado_dep_informales_tg_alta` AFTER INSERT ON `empleado_dep_informales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_dep_informales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_dep_informales`,
    `id_empleado`,
    `id_dep_informal`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_dep_informal,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_dep_informales_tg_modificacion$$
CREATE TRIGGER `empleado_dep_informales_tg_modificacion` AFTER UPDATE ON `empleado_dep_informales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_dep_informales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_dep_informales`,
    `id_empleado`,
    `id_dep_informal`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0 , "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_dep_informal,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleado_documentos_tg_alta$$
CREATE TRIGGER `empleado_documentos_tg_alta` AFTER INSERT ON `empleado_documentos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_documentos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_documentos`,
    `id_empleado`,
    `id_bloque`,
    `nombre_archivo`,
    `fecha_reg`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_bloque,
    NEW.nombre_archivo,
    NEW.fecha_reg,
    NEW.borrado
    );
END$$
DELIMITER ;





DELIMITER $$
DROP TRIGGER IF EXISTS empleado_documentos_tg_modificacion$$
CREATE TRIGGER `empleado_documentos_tg_modificacion` AFTER UPDATE ON `empleado_documentos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_documentos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_documentos`,
    `id_empleado`,
    `id_bloque`,
    `nombre_archivo`,
    `fecha_reg`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_bloque,
    NEW.nombre_archivo,
    NEW.fecha_reg,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_alta$$
CREATE TRIGGER `empleado_escalafon_tg_alta` AFTER INSERT ON `empleado_escalafon` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_escalafon
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
    `ultimo_cambio_nivel`,
    `exc_art_14`
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
    NEW.ultimo_cambio_nivel,
    NEW.exc_art_14
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_escalafon_tg_modificacion$$
CREATE TRIGGER `empleado_escalafon_tg_modificacion` AFTER UPDATE ON `empleado_escalafon` FOR EACH ROW
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
    NEW.ultimo_cambio_nivel <> OLD.ultimo_cambio_nivel OR
    NEW.exc_art_14 <> OLD.exc_art_14
THEN
    INSERT INTO sigarhu_historial.empleado_escalafon
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
        `ultimo_cambio_nivel`,
        `exc_art_14` 
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
        NEW.ultimo_cambio_nivel,
        NEW.exc_art_14
        );
END IF;
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleado_horarios_tg_alta$$
CREATE TRIGGER `empleado_horarios_tg_alta` AFTER INSERT ON `empleado_horarios` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_horarios
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_horarios`,
    `id_empleado`,
    `horarios`,
    `id_turno`,
    `fecha_inicio`,
    `fecha_fin`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.horarios,
    NEW.id_turno,
    NEW.fecha_inicio,
    NEW.fecha_fin,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_horarios_tg_modificacion$$
CREATE TRIGGER `empleado_horarios_tg_modificacion` AFTER UPDATE ON `empleado_horarios` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_horarios
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_horarios`,
    `id_empleado`,
    `horarios`,
    `id_turno`,
    `fecha_inicio`,
    `fecha_fin`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_fin <> "", "B","M"),
    OLD.id,
    NEW.id_empleado,
    NEW.horarios,
    NEW.id_turno,
    NEW.fecha_inicio,
    NEW.fecha_fin,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_horas_extras_tg_alta$$
CREATE TRIGGER `empleado_horas_extras_tg_alta` AFTER INSERT ON `empleado_horas_extras` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_horas_extras
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_horas_extras`,
    `id_empleado`,
    `anio`,
    `mes`,
    `acto_administrativo`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.anio,
    NEW.mes,
    NEW.acto_administrativo,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_horas_extras_tg_modificacion$$
CREATE TRIGGER `empleado_horas_extras_tg_modificacion` AFTER UPDATE ON `empleado_horas_extras` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_horas_extras
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_horas_extras`,
    `id_empleado`,
    `anio`,
    `mes`,
    `acto_administrativo`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.anio,
    NEW.mes,
    NEW.acto_administrativo,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleados_lic_especiales_tg_alta$$
CREATE TRIGGER `empleados_lic_especiales_tg_alta` AFTER INSERT ON `empleados_lic_especiales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleados_lic_especiales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleados_lic_especiales`,
    `id_empleado`,
    `id_licencia`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_licencia,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleados_lic_especiales_tg_modificacion$$
CREATE TRIGGER `empleados_lic_especiales_tg_modificacion` AFTER UPDATE ON `empleados_lic_especiales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleados_lic_especiales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleados_lic_especiales`,
    `id_empleado`,
    `id_licencia`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_licencia,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS empleado_perfil_tg_alta$$
CREATE TRIGGER `empleado_perfil_tg_alta` AFTER INSERT ON `empleado_perfil` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_perfil
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_perfil`,
    `id_empleado`,
    `denominacion_funcion`,
    `denominacion_puesto`,
    `objetivo_gral`,
    `objetivo_especifico`,
    `estandares`,
    `fecha_obtencion_result`,
    `nivel_destreza`,
    `nombre_puesto`,
    `puesto_supervisa`,
    `nivel_complejidad`,
    `fecha_desde`,
    `fecha_hasta`,
    `familia_de_puestos`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.denominacion_funcion,
    NEW.denominacion_puesto,
    NEW.objetivo_gral,
    NEW.objetivo_especifico,
    NEW.estandares,
    NEW.fecha_obtencion_result,
    NEW.nivel_destreza,
    NEW.nombre_puesto,
    NEW.puesto_supervisa,
    NEW.nivel_complejidad,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.familia_de_puestos
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_perfil_tg_modificacion$$
CREATE TRIGGER `empleado_perfil_tg_modificacion` AFTER UPDATE ON `empleado_perfil` FOR EACH ROW
BEGIN
IF NEW.denominacion_funcion <> OLD.denominacion_funcion OR NEW.denominacion_puesto <> OLD.denominacion_puesto OR
    NEW.objetivo_gral <> OLD.objetivo_gral OR NEW.objetivo_especifico <> OLD.objetivo_especifico OR 
    NEW.estandares <> OLD.estandares OR NEW.nivel_destreza <> OLD.nivel_destreza OR 
    NEW.nombre_puesto <> OLD.nombre_puesto OR NEW.puesto_supervisa <> OLD.puesto_supervisa OR 
    NEW.nivel_complejidad <> OLD.nivel_complejidad OR NEW.familia_de_puestos <> OLD.familia_de_puestos OR
    NEW.fecha_obtencion_result <> OLD.fecha_obtencion_result
THEN
    INSERT INTO sigarhu_historial.empleado_perfil
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_empleado_perfil`,
        `id_empleado`,
        `denominacion_funcion`,
        `denominacion_puesto`,
        `objetivo_gral`,
        `objetivo_especifico`,
        `estandares`,
        `fecha_obtencion_result`,
        `nivel_destreza`,
        `nombre_puesto`,
        `puesto_supervisa`,
        `nivel_complejidad`,
        `fecha_desde`,
        `fecha_hasta`,
        `familia_de_puestos`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        "M",
        OLD.id,
        NEW.id_empleado,
        NEW.denominacion_funcion,
        NEW.denominacion_puesto,
        NEW.objetivo_gral,
        NEW.objetivo_especifico,
        NEW.estandares,
        NEW.fecha_obtencion_result,
        NEW.nivel_destreza,
        NEW.nombre_puesto,
        NEW.puesto_supervisa,
        NEW.nivel_complejidad,
        NEW.fecha_desde,
        NEW.fecha_hasta,
        NEW.familia_de_puestos
        );
END IF;
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS perfil_actividades_tg_alta$$
CREATE TRIGGER `perfil_actividades_tg_alta` AFTER INSERT ON `perfil_actividades` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.perfil_actividades
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_perfil_actividades`,
    `id_perfil`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_perfil,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS perfil_actividades_tg_modificacion$$
CREATE TRIGGER `perfil_actividades_tg_modificacion` AFTER UPDATE ON `perfil_actividades` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.perfil_actividades
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_perfil_actividades`,
    `id_perfil`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_perfil,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS perfil_resultado_parc_final_tg_alta$$
CREATE TRIGGER `perfil_resultado_parc_final_tg_alta` AFTER INSERT ON `perfil_resultado_parc_final` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.perfil_resultado_parc_final
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_perfil_resultado_parc_final`,
    `id_perfil`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_perfil,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS perfil_resultado_parc_final_tg_modificacion$$
CREATE TRIGGER `perfil_resultado_parc_final_tg_modificacion` AFTER UPDATE ON `perfil_resultado_parc_final` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.perfil_resultado_parc_final
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_perfil_resultado_parc_final`,
    `id_perfil`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_perfil,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleado_presupuesto_tg_alta$$
CREATE TRIGGER `empleado_presupuesto_tg_alta` AFTER INSERT ON `empleado_presupuesto` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_presupuesto
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_presupuesto`,
    `id_empleado`,
    `id_presupuesto`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_presupuesto,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_presupuesto_tg_modificacion$$
CREATE TRIGGER `empleado_presupuesto_tg_modificacion` AFTER UPDATE ON `empleado_presupuesto` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_presupuesto
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_presupuesto`,
    `id_empleado`,
    `id_presupuesto`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B","M"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_presupuesto,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleado_salud_tg_alta$$
CREATE TRIGGER `empleado_salud_tg_alta` AFTER INSERT ON `empleado_salud` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_salud
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_salud`,
    `id_empleado`,
    `id_obra_social`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_obra_social,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_salud_tg_modificacion$$
CREATE TRIGGER `empleado_salud_tg_modificacion` AFTER UPDATE ON `empleado_salud` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_salud
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_salud`,
    `id_empleado`,
    `id_obra_social`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B", "M"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_obra_social,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleado_sindicatos_tg_alta$$
CREATE TRIGGER `empleado_sindicatos_tg_alta` AFTER INSERT ON `empleado_sindicatos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_sindicatos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_sindicatos`,
    `id_empleado`,
    `id_sindicato`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_sindicato,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleado_sindicatos_tg_modificacion$$
CREATE TRIGGER `empleado_sindicatos_tg_modificacion` AFTER UPDATE ON `empleado_sindicatos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_sindicatos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_sindicatos`,
    `id_empleado`,
    `id_sindicato`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B","M"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_sindicato,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS empleados_tg_alta$$
CREATE TRIGGER `empleados_tg_alta` AFTER INSERT ON `empleados` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleados
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleados`,
    `id_persona`,  
    `cuit`,  
    `email`,
    `planilla_reloj`,
    `en_comision`,  
    `credencial`,
    `borrado`,
    `antiguedad_adm_publica`,  
    `id_sindicato`,
    `fecha_vigencia_mandato`,  
    `estado`, 
    `id_motivo`, 
    `fecha_baja`, 
    `fecha_vencimiento`,
    `veterano_guerra` 
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_persona,
    NEW.cuit,
    NEW.email,
    NEW.planilla_reloj,
    NEW.en_comision,
    NEW.credencial,
    NEW.borrado,
    NEW.antiguedad_adm_publica,
    NEW.id_sindicato,
    NEW.fecha_vigencia_mandato,
    NEW.estado,
    NEW.id_motivo,
    NEW.fecha_baja,
    NEW.fecha_vencimiento,
    NEW.veterano_guerra
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleados_tg_modificacion$$
CREATE TRIGGER `empleados_tg_modificacion` AFTER UPDATE ON `empleados` FOR EACH ROW
BEGIN
IF NEW.cuit <> OLD.cuit OR NEW.email <> OLD.email OR NEW.planilla_reloj <> OLD.planilla_reloj OR
    NEW.en_comision <> OLD.en_comision OR NEW.credencial <> OLD.credencial OR
    NEW.antiguedad_adm_publica <> OLD.antiguedad_adm_publica OR NEW.id_sindicato <> OLD.id_sindicato OR
    NEW.fecha_vigencia_mandato <> OLD.fecha_vigencia_mandato OR NEW.estado <> OLD.estado OR
    NEW.id_motivo <> OLD.id_motivo OR NEW.fecha_baja <> OLD.fecha_baja OR 
    NEW.fecha_vencimiento <> OLD.fecha_vencimiento OR NEW.veterano_guerra <> OLD.veterano_guerra
THEN
    INSERT INTO sigarhu_historial.empleados
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_empleados`,
        `id_persona`,  
        `cuit`,  
        `email`,
        `planilla_reloj`,
        `en_comision`, 
        `credencial`,
        `borrado`,
        `antiguedad_adm_publica`,  
        `id_sindicato`,
        `fecha_vigencia_mandato`,  
        `estado`, 
        `id_motivo`, 
        `fecha_baja`, 
        `fecha_vencimiento`,
        `veterano_guerra`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M", "B"),
        OLD.id,
        NEW.id_persona,
        NEW.cuit,
        NEW.email,
        NEW.planilla_reloj,
        NEW.en_comision,
        NEW.credencial,
        NEW.borrado,
        NEW.antiguedad_adm_publica,
        NEW.id_sindicato,
        NEW.fecha_vigencia_mandato,
        NEW.estado,
        NEW.id_motivo,
        NEW.fecha_baja,
        NEW.fecha_vencimiento,
        NEW.veterano_guerra
        );
END IF;
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS empleados_x_ubicacion_tg_alta$$
CREATE TRIGGER `empleados_x_ubicacion_tg_alta` AFTER INSERT ON `empleados_x_ubicacion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleados_x_ubicacion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleados_x_ubicacion`,
    `id_empleado`,
    `id_ubicacion`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_ubicacion,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS empleados_x_ubicacion_tg_modificacion$$
CREATE TRIGGER `empleados_x_ubicacion_tg_modificacion` AFTER UPDATE ON `empleados_x_ubicacion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleados_x_ubicacion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleados_x_ubicacion`,
    `id_empleado`,
    `id_ubicacion`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B","M"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_ubicacion,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS grupo_familiar_tg_alta$$
CREATE TRIGGER `grupo_familiar_tg_alta` AFTER INSERT ON `grupo_familiar` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.grupo_familiar
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_grupo_familiar`,
    `id_empleado`,
    `parentesco`,
    `nombre`,
    `apellido`,
    `fecha_nacimiento`,
    `nacionalidad`,
    `tipo_documento`,
    `documento`,
    `nivel_educativo`,
    `reintegro_guarderia`,
    `discapacidad`,
    `desgrava_afip`,
    `fecha_desde`,
    `fecha_hasta`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.parentesco,
    NEW.nombre,
    NEW.apellido,
    NEW.fecha_nacimiento,
    NEW.nacionalidad,
    NEW.tipo_documento,
    NEW.documento,
    NEW.nivel_educativo,
    NEW.reintegro_guarderia,
    NEW.discapacidad,
    NEW.desgrava_afip,
    NEW.fecha_desde,
    NEW.fecha_hasta,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS grupo_familiar_tg_modificacion$$
CREATE TRIGGER `grupo_familiar_tg_modificacion` AFTER UPDATE ON `grupo_familiar` FOR EACH ROW
BEGIN
IF NEW.parentesco <> OLD.parentesco OR NEW.nombre <> OLD.nombre OR NEW.apellido <> OLD.apellido OR
    NEW.fecha_nacimiento <> OLD.fecha_nacimiento OR NEW.nacionalidad <> OLD.nacionalidad OR
    NEW.tipo_documento <> OLD.tipo_documento OR NEW.documento <> OLD.documento OR
    NEW.nivel_educativo <> OLD.nivel_educativo OR NEW.reintegro_guarderia <> "" OR
    NEW.discapacidad <> "" OR NEW.desgrava_afip <> OLD.desgrava_afip OR 
    NEW.fecha_desde <> OLD.fecha_desde OR NEW.fecha_hasta <> OLD.fecha_hasta OR NEW.borrado <> OLD.borrado
THEN
    INSERT INTO sigarhu_historial.grupo_familiar
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_grupo_familiar`,
        `id_empleado`,
        `parentesco`,
        `nombre`,
        `apellido`,
        `fecha_nacimiento`,
        `nacionalidad`,
        `tipo_documento`,
        `documento`,
        `nivel_educativo`,
        `reintegro_guarderia`,
        `discapacidad`,
        `desgrava_afip`,
        `fecha_desde`,
        `fecha_hasta`,
        `borrado`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M","B"),
        OLD.id,
        NEW.id_empleado,
        NEW.parentesco,
        NEW.nombre,
        NEW.apellido,
        NEW.fecha_nacimiento,
        NEW.nacionalidad,
        NEW.tipo_documento,
        NEW.documento,
        NEW.nivel_educativo,
        NEW.reintegro_guarderia,
        NEW.discapacidad,
        NEW.desgrava_afip,
        NEW.fecha_desde,
        NEW.fecha_hasta,
        NEW.borrado
        );
END IF;
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS familiar_discapacidad_tg_alta$$
CREATE TRIGGER `familiar_discapacidad_tg_alta` AFTER INSERT ON `familiar_discapacidad` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.familiar_discapacidad
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_familiar_discapacidad`,
    `id_familiar`,
    `id_tipo_discapacidad`,
    `cud`,
    `fecha_alta`,
    `fecha_vencimiento`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_familiar,
    NEW.id_tipo_discapacidad,
    NEW.cud,
    NEW.fecha_alta,
    NEW.fecha_vencimiento,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS familiar_discapacidad_tg_modificacion$$
CREATE TRIGGER `familiar_discapacidad_tg_modificacion` AFTER UPDATE ON `familiar_discapacidad` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.familiar_discapacidad
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_familiar_discapacidad`,
    `id_familiar`,
    `id_tipo_discapacidad`,
    `cud`,
    `fecha_alta`,
    `fecha_vencimiento`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_familiar,
    NEW.id_tipo_discapacidad,
    NEW.cud,
    NEW.fecha_alta,
    NEW.fecha_vencimiento,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS licencias_especiales_tg_alta$$
CREATE TRIGGER `licencias_especiales_tg_alta` AFTER INSERT ON `licencias_especiales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.licencias_especiales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_licencias_especiales`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS licencias_especiales_tg_modificacion$$
CREATE TRIGGER `licencias_especiales_tg_modificacion` AFTER UPDATE ON `licencias_especiales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.licencias_especiales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_licencias_especiales`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS motivo_baja_tg_alta$$
CREATE TRIGGER `motivo_baja_tg_alta` AFTER INSERT ON `motivo_baja` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.motivo_baja
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_motivo_baja`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS motivo_baja_tg_modificacion$$
CREATE TRIGGER `motivo_baja_tg_modificacion` AFTER UPDATE ON `motivo_baja` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.motivo_baja
    (  
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_motivo_baja`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS nivel_educativo_tg_alta$$
CREATE TRIGGER `nivel_educativo_tg_alta` AFTER INSERT ON `nivel_educativo` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.nivel_educativo
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_nivel_educativo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS nivel_educativo_tg_modificacion$$
CREATE TRIGGER `nivel_educativo_tg_modificacion` AFTER UPDATE ON `nivel_educativo` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.nivel_educativo
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_nivel_educativo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS obras_sociales_tg_alta$$
CREATE TRIGGER `obras_sociales_tg_alta` AFTER INSERT ON `obras_sociales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.obras_sociales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_obras_sociales`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS obras_sociales_tg_modificacion$$
CREATE TRIGGER `obras_sociales_tg_modificacion` AFTER UPDATE ON `obras_sociales` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.obras_sociales
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_obras_sociales`,
    `codigo`,  
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS persona_discapacidad_tg_alta$$
CREATE TRIGGER `persona_discapacidad_tg_alta` AFTER INSERT ON `persona_discapacidad` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_discapacidad
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_discapacidad`,
    `id_persona`,
    `id_tipo_discapacidad`,
    `cud`,
    `fecha_vencimiento`,
    `observaciones`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_persona,
    NEW.id_tipo_discapacidad,
    NEW.cud,
    NEW.fecha_vencimiento,
    NEW.observaciones,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS persona_discapacidad_tg_modificacion$$
CREATE TRIGGER `persona_discapacidad_tg_modificacion` AFTER UPDATE ON `persona_discapacidad` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_discapacidad
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_discapacidad`,
    `id_persona`,
    `id_tipo_discapacidad`,
    `cud`,
    `fecha_vencimiento`,
    `observaciones`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_persona,
    NEW.id_tipo_discapacidad,
    NEW.cud,
    NEW.fecha_vencimiento,
    NEW.observaciones,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS persona_domicilio_tg_alta$$
CREATE TRIGGER `persona_domicilio_tg_alta` AFTER INSERT ON `persona_domicilio` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_domicilio
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_domicilio`,
    `id_persona`,
    `calle`,
    `numero`,
    `piso`,
    `depto`,
    `cod_postal`,
    `id_provincia`,
    `id_localidad`,
    `fecha_alta`,
    `fecha_baja`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_persona,
    NEW.calle,
    NEW.numero,
    NEW.piso,
    NEW.depto,
    NEW.cod_postal,
    NEW.id_provincia,
    NEW.id_localidad,
    NEW.fecha_alta,
    NEW.fecha_baja
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS persona_domicilio_tg_modificacion$$
CREATE TRIGGER `persona_domicilio_tg_modificacion` AFTER UPDATE ON `persona_domicilio` FOR EACH ROW
BEGIN
IF NEW.id_persona <> OLD.id_persona OR NEW.calle <> OLD.calle OR NEW.numero <> OLD.numero OR 
    NEW.piso <> OLD.piso OR NEW.depto <> OLD.depto OR NEW.cod_postal <> OLD.cod_postal OR 
    NEW.id_provincia <> OLD.id_provincia OR NEW.id_localidad <> OLD.id_localidad OR
    NEW.fecha_alta <> OLD.fecha_alta OR NEW.fecha_baja <> ""
THEN
    INSERT INTO sigarhu_historial.persona_domicilio
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_persona_domicilio`,
        `id_persona`,
        `calle`,
        `numero`,
        `piso`,
        `depto`,
        `cod_postal`,
        `id_provincia`,
        `id_localidad`,
        `fecha_alta`,
        `fecha_baja`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.fecha_baja <> "", "B", "M"),
        OLD.id,
        NEW.id_persona,
        NEW.calle,
        NEW.numero,
        NEW.piso,
        NEW.depto,
        NEW.cod_postal,
        NEW.id_provincia,
        NEW.id_localidad,
        NEW.fecha_alta,
        NEW.fecha_baja
        );
END IF;
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS personas_tg_alta$$
CREATE TRIGGER `personas_tg_alta` AFTER INSERT ON `personas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.personas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_personas`,
    `tipo_documento`,  
    `documento`,  
    `nombre`,
    `apellido`,
    `fecha_nac`,  
    `genero`,
    `nacionalidad`,  
    `estado_civil`,
    `email`,  
    `foto_persona`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.tipo_documento,
    NEW.documento,
    NEW.nombre,
    NEW.apellido,
    NEW.fecha_nac,
    NEW.genero,
    NEW.nacionalidad,
    NEW.estado_civil,
    NEW.email,
    NEW.foto_persona,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS personas_tg_modificacion$$
CREATE TRIGGER `personas_tg_modificacion` AFTER UPDATE ON `personas` FOR EACH ROW
BEGIN
IF NEW.tipo_documento <> OLD.tipo_documento OR NEW.documento <> OLD.documento OR
    NEW.nombre <> OLD.nombre OR NEW.apellido <> OLD.apellido OR NEW.fecha_nac <> OLD.fecha_nac OR
    NEW.genero <> OLD.genero OR NEW.nacionalidad <> OLD.nacionalidad OR
    NEW.estado_civil <> OLD.estado_civil OR NEW.email <> OLD.email OR 
    NEW.foto_persona <> OLD.foto_persona
THEN
    INSERT INTO sigarhu_historial.personas
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_personas`,
        `tipo_documento`,  
        `documento`,  
        `nombre`,
        `apellido`,
        `fecha_nac`,  
        `genero`,
        `nacionalidad`,  
        `estado_civil`,
        `email`,  
        `foto_persona`,
        `borrado`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M", "B"),
        OLD.id,
        NEW.tipo_documento,
        NEW.documento,
        NEW.nombre,
        NEW.apellido,
        NEW.fecha_nac,
        NEW.genero,
        NEW.nacionalidad,
        NEW.estado_civil,
        NEW.email,
        NEW.foto_persona,
        NEW.borrado
        );
END IF;
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS persona_telefono_tg_alta$$
CREATE TRIGGER `persona_telefono_tg_alta` AFTER INSERT ON `persona_telefono` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_telefono
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_telefono`,
    `id_persona`,
    `id_tipo_telefono`,
    `telefono`,
    `fecha_alta`,
    `fecha_baja`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_persona,
    NEW.id_tipo_telefono,
    NEW.telefono,
    NEW.fecha_alta,
    NEW.fecha_baja
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS persona_telefono_tg_modificacion$$
CREATE TRIGGER `persona_telefono_tg_modificacion` AFTER UPDATE ON `persona_telefono` FOR EACH ROW
BEGIN
IF NEW.id_persona <> OLD.id_persona OR NEW.id_tipo_telefono <> OLD.id_tipo_telefono OR
    NEW.telefono <> OLD.telefono OR NEW.fecha_alta <> OLD.fecha_alta OR NEW.fecha_baja <> ""
THEN
    INSERT INTO sigarhu_historial.persona_telefono
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_persona_telefono`,
        `id_persona`,
        `id_tipo_telefono`,
        `telefono`,
        `fecha_alta`,
        `fecha_baja`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.fecha_baja <> "", "B", "M"),
        OLD.id,
        NEW.id_persona,
        NEW.id_tipo_telefono,
        NEW.telefono,
        NEW.fecha_alta,
        NEW.fecha_baja
        );
END IF;
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS persona_titulo_tg_alta$$
CREATE TRIGGER `persona_titulo_tg_alta` AFTER INSERT ON `persona_titulo` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_titulo
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_titulo`,
    `id_persona`,
    `id_tipo_titulo`,
    `id_estado_titulo`,
    `id_titulo`,
    `fecha`,
    `principal`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_persona,
    NEW.id_tipo_titulo,
    NEW.id_estado_titulo,
    NEW.id_titulo,
    NEW.fecha,
    NEW.principal,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS persona_titulo_tg_modificacion$$
CREATE TRIGGER `persona_titulo_tg_modificacion` AFTER UPDATE ON `persona_titulo` FOR EACH ROW
BEGIN
IF NEW.id_tipo_titulo <> OLD.id_tipo_titulo OR NEW.id_estado_titulo <> OLD.id_estado_titulo OR
    NEW.id_titulo <> OLD.id_titulo OR NEW.fecha <> OLD.fecha OR NEW.principal <> OLD.principal OR 
    NEW.borrado = 1
THEN
    INSERT INTO sigarhu_historial.persona_titulo
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_persona_titulo`,
        `id_persona`,
        `id_tipo_titulo`,
        `id_estado_titulo`,
        `id_titulo`,
        `fecha`,
        `principal`,
        `borrado`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M","B"),
        OLD.id,
        NEW.id_persona,
        NEW.id_tipo_titulo,
        NEW.id_estado_titulo,
        NEW.id_titulo,
        NEW.fecha,
        NEW.principal,
        NEW.borrado
        );
END IF;
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS persona_otros_conocimientos_tg_alta$$
CREATE TRIGGER `persona_otros_conocimientos_tg_alta` AFTER INSERT ON `persona_otros_conocimientos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.persona_otros_conocimientos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_persona_otros_conocimientos`,
    `id_persona`,
    `id_tipo`,
    `fecha`,
    `descripcion`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_persona,
    NEW.id_tipo,
    NEW.fecha,
    NEW.descripcion,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS persona_otros_conocimientos_tg_modificacion$$
CREATE TRIGGER `persona_otros_conocimientos_tg_modificacion` AFTER UPDATE ON `persona_otros_conocimientos` FOR EACH ROW
BEGIN
IF NEW.id_tipo <> OLD.id_tipo OR NEW.fecha <> OLD.fecha OR NEW.descripcion <> OLD.descripcion OR NEW.borrado = 1
THEN
    INSERT INTO sigarhu_historial.persona_otros_conocimientos
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_persona_otros_conocimientos`,
        `id_persona`,
        `id_tipo`,
        `fecha`,
        `descripcion`,
        `borrado`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M","B"),
        OLD.id,
        NEW.id_persona,
        NEW.id_tipo,
        NEW.fecha,
        NEW.descripcion,
        NEW.borrado
        );
END IF;
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS seguro_vida_tg_alta$$
CREATE TRIGGER `seguro_vida_tg_alta` AFTER INSERT ON `seguro_vida` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.seguro_vida
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_seguro_vida`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS seguro_vida_tg_modificacion$$
CREATE TRIGGER `seguro_vida_tg_modificacion` AFTER UPDATE ON `seguro_vida` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.seguro_vida
    (  
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_seguro_vida`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS sindicatos_tg_alta$$
CREATE TRIGGER `sindicatos_tg_alta` AFTER INSERT ON `sindicatos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.sindicatos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_sindicatos`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS sindicatos_tg_modificacion$$
CREATE TRIGGER `sindicatos_tg_modificacion` AFTER UPDATE ON `sindicatos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.sindicatos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_sindicatos`,
    `codigo`,  
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS anticorrupcion_tg_alta$$
CREATE TRIGGER `anticorrupcion_tg_alta` AFTER INSERT ON `anticorrupcion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.anticorrupcion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_anticorrupcion`,
    `id_empleado`,
    `fecha_designacion`,
    `fecha_publicacion_designacion`,
    `fecha_aceptacion_renuncia`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.fecha_designacion,
    NEW.fecha_publicacion_designacion,
    NEW.fecha_aceptacion_renuncia,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS anticorrupcion_tg_modificacion$$
CREATE TRIGGER `anticorrupcion_tg_modificacion` AFTER UPDATE ON `anticorrupcion` FOR EACH ROW
BEGIN
IF NEW.fecha_designacion <> OLD.fecha_designacion OR 
    NEW.fecha_publicacion_designacion <> OLD.fecha_publicacion_designacion OR 
    NEW.fecha_aceptacion_renuncia <> OLD.fecha_aceptacion_renuncia OR 
    NEW.borrado <> OLD.borrado
THEN
    INSERT INTO sigarhu_historial.anticorrupcion
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_anticorrupcion`,
        `id_empleado`,
        `fecha_designacion`,
        `fecha_publicacion_designacion`,
        `fecha_aceptacion_renuncia`,
        `borrado`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M","B"),
        OLD.id,
        NEW.id_empleado,
        NEW.fecha_designacion,
        NEW.fecha_publicacion_designacion,
        NEW.fecha_aceptacion_renuncia,
        NEW.borrado
        );
END IF;
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS anticorrupcion_presentacion_tg_alta$$
CREATE TRIGGER `anticorrupcion_presentacion_tg_alta` AFTER INSERT ON `anticorrupcion_presentacion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.anticorrupcion_presentacion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_anticorrupcion_presentacion`,
    `id_anticorrupcion`,
    `tipo_presentacion`,
    `fecha_presentacion`,
    `periodo`,
    `nro_transaccion`,
    `archivo`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_anticorrupcion,
    NEW.tipo_presentacion,
    NEW.fecha_presentacion,
    NEW.periodo,
    NEW.nro_transaccion,
    NEW.archivo,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS anticorrupcion_presentacion_tg_modificacion$$
CREATE TRIGGER `anticorrupcion_presentacion_tg_modificacion` AFTER UPDATE ON `anticorrupcion_presentacion` FOR EACH ROW
BEGIN
IF NEW.tipo_presentacion <> OLD.tipo_presentacion OR NEW.fecha_presentacion <> OLD.fecha_presentacion OR 
    NEW.periodo <> OLD.periodo OR NEW.nro_transaccion <> OLD.nro_transaccion OR
    NEW.archivo <> OLD.archivo OR NEW.borrado <> OLD.borrado
THEN
    INSERT INTO sigarhu_historial.anticorrupcion_presentacion
        (
        `id_usuario`,
        `fecha_operacion`,
        `tipo_operacion`,
        `id_anticorrupcion_presentacion`,
        `id_anticorrupcion`,
        `tipo_presentacion`,
        `fecha_presentacion`,
        `periodo`,
        `nro_transaccion`,
        `archivo`,
        `borrado`
        )
    VALUES
        (
        @id_usuario,
        NOW(),
        IF(NEW.borrado = 0, "M","B"),
        OLD.id,
        NEW.id_anticorrupcion,
        NEW.tipo_presentacion,
        NEW.fecha_presentacion,
        NEW.periodo,
        NEW.nro_transaccion,
        NEW.archivo,
        NEW.borrado
        );
END IF;
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS modalidad_vinculacion_tg_alta$$
CREATE TRIGGER `modalidad_vinculacion_tg_alta` AFTER INSERT ON `convenio_modalidad_vinculacion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_modalidad_vinculacion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_modalidad_vinculacion`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS modalidad_vinculacion_tg_modificacion$$
CREATE TRIGGER `modalidad_vinculacion_tg_modificacion` AFTER UPDATE ON `convenio_modalidad_vinculacion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_modalidad_vinculacion
    (  
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_modalidad_vinculacion`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS situacion_revista_tg_alta$$
CREATE TRIGGER `situacion_revista_tg_alta` AFTER INSERT ON `convenio_situacion_revista` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_situacion_revista
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_situacion_revista`,
    `id_modalidad_vinculacion`,  
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_modalidad_vinculacion,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS situacion_revista_tg_modificacion$$
CREATE TRIGGER `situacion_revista_tg_modificacion` AFTER UPDATE ON `convenio_situacion_revista` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_situacion_revista
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_situacion_revista`,
    `id_modalidad_vinculacion`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_modalidad_vinculacion,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS convenio_agrupamientos_tg_alta$$
CREATE TRIGGER `convenio_agrupamientos_tg_alta` AFTER INSERT ON `convenio_agrupamientos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_agrupamientos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_agrupamientos`,
    `id_modalidad_vinculacion`,  
    `id_situacion_revista`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_modalidad_vinculacion,
    NEW.id_situacion_revista,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS convenio_agrupamientos_tg_modificacion$$
CREATE TRIGGER `convenio_agrupamientos_tg_modificacion` AFTER UPDATE ON `convenio_agrupamientos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_agrupamientos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_agrupamientos`,
    `id_modalidad_vinculacion`,
    `id_situacion_revista`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_modalidad_vinculacion,
    NEW.id_situacion_revista,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;





DELIMITER $$
DROP TRIGGER IF EXISTS convenio_tramos_tg_alta$$
CREATE TRIGGER `convenio_tramos_tg_alta` AFTER INSERT ON `convenio_tramos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_tramos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_tramos`,
    `id_modalidad_vinculacion`,  
    `id_situacion_revista`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_modalidad_vinculacion,
    NEW.id_situacion_revista,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;





DELIMITER $$
DROP TRIGGER IF EXISTS convenio_tramos_tg_modificacion$$
CREATE TRIGGER `convenio_tramos_tg_modificacion` AFTER UPDATE ON `convenio_tramos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_tramos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_tramos`,
    `id_modalidad_vinculacion`,
    `id_situacion_revista`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_modalidad_vinculacion,
    NEW.id_situacion_revista,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS convenio_niveles_tg_alta$$
CREATE TRIGGER `convenio_niveles_tg_alta` AFTER INSERT ON `convenio_niveles` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_niveles
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_niveles`,
    `id_agrupamiento`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_agrupamiento,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS convenio_niveles_tg_modificacion$$
CREATE TRIGGER `convenio_niveles_tg_modificacion` AFTER UPDATE ON `convenio_niveles` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_niveles
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_niveles`,
    `id_agrupamiento`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_agrupamiento,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS convenio_grados_tg_alta$$
CREATE TRIGGER `convenio_grados_tg_alta` AFTER INSERT ON `convenio_grados` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_grados
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_grados`,
    `id_tramo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_tramo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS convenio_grados_tg_modificacion$$
CREATE TRIGGER `convenio_grados_tg_modificacion` AFTER UPDATE ON `convenio_grados` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_grados
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_grados`,
    `id_tramo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_tramo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS convenio_funciones_ejecutivas_tg_alta$$
CREATE TRIGGER `convenio_funciones_ejecutivas_tg_alta` AFTER INSERT ON `convenio_funciones_ejecutivas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_funciones_ejecutivas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_funciones_ejecutivas`,
    `id_modalidad_vinculacion`,
    `id_situacion_revista`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_modalidad_vinculacion,
    NEW.id_situacion_revista,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS convenio_funciones_ejecutivas_tg_modificacion$$
CREATE TRIGGER `convenio_funciones_ejecutivas_tg_modificacion` AFTER UPDATE ON `convenio_funciones_ejecutivas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.convenio_funciones_ejecutivas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_convenio_funciones_ejecutivas`,
    `id_modalidad_vinculacion`,
    `id_situacion_revista`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_modalidad_vinculacion,
    NEW.id_situacion_revista,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_saf_tg_alta$$
CREATE TRIGGER `presupuesto_saf_tg_alta` AFTER INSERT ON `presupuesto_saf` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_saf
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_saf`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_saf_tg_modificacion$$
CREATE TRIGGER `presupuesto_saf_tg_modificacion` AFTER UPDATE ON `presupuesto_saf` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_saf
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_saf`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_jurisdicciones_tg_alta$$
CREATE TRIGGER `presupuesto_jurisdicciones_tg_alta` AFTER INSERT ON `presupuesto_jurisdicciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_jurisdicciones
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_jurisdicciones`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_jurisdicciones_tg_modificacion$$
CREATE TRIGGER `presupuesto_jurisdicciones_tg_modificacion` AFTER UPDATE ON `presupuesto_jurisdicciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_jurisdicciones
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_jurisdicciones`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_ubicaciones_geograficas_tg_alta$$
CREATE TRIGGER `presupuesto_ubicaciones_geograficas_tg_alta` AFTER INSERT ON `presupuesto_ubicaciones_geograficas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_ubicaciones_geograficas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_ubicaciones_geograficas`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_ubicaciones_geograficas_tg_modificacion$$
CREATE TRIGGER `presupuesto_ubicaciones_geograficas_tg_modificacion` AFTER UPDATE ON `presupuesto_ubicaciones_geograficas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_ubicaciones_geograficas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_ubicaciones_geograficas`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_programas_tg_alta$$
CREATE TRIGGER `presupuesto_programas_tg_alta` AFTER INSERT ON `presupuesto_programas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_programas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_programas`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_programas_tg_modificacion$$
CREATE TRIGGER `presupuesto_programas_tg_modificacion` AFTER UPDATE ON `presupuesto_programas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_programas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_programas`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_subprogramas_tg_alta$$
CREATE TRIGGER `presupuesto_subprogramas_tg_alta` AFTER INSERT ON `presupuesto_subprogramas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_subprogramas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_subprogramas`,
    `id_programa`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_programa,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_subprogramas_tg_modificacion$$
CREATE TRIGGER `presupuesto_subprogramas_tg_modificacion` AFTER UPDATE ON `presupuesto_subprogramas` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_subprogramas
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_subprogramas`,
    `id_programa`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_programa,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_proyectos_tg_alta$$
CREATE TRIGGER `presupuesto_proyectos_tg_alta` AFTER INSERT ON `presupuesto_proyectos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_proyectos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_proyectos`,
    `id_programa`,
    `id_subprograma`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_programa,
    NEW.id_subprograma,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_proyectos_tg_modificacion$$
CREATE TRIGGER `presupuesto_proyectos_tg_modificacion` AFTER UPDATE ON `presupuesto_proyectos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_proyectos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_proyectos`,
    `id_programa`,
    `id_subprograma`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_programa,
    NEW.id_subprograma,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_actividades_tg_alta$$
CREATE TRIGGER `presupuesto_actividades_tg_alta` AFTER INSERT ON `presupuesto_actividades` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_actividades
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_actividades`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_actividades_tg_modificacion$$
CREATE TRIGGER `presupuesto_actividades_tg_modificacion` AFTER UPDATE ON `presupuesto_actividades` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_actividades
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_actividades`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_obras_tg_alta$$
CREATE TRIGGER `presupuesto_obras_tg_alta` AFTER INSERT ON `presupuesto_obras` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_obras
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_obras`,
    `id_proyecto`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_proyecto,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuesto_obras_tg_modificacion$$
CREATE TRIGGER `presupuesto_obras_tg_modificacion` AFTER UPDATE ON `presupuesto_obras` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuesto_obras
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuesto_obras`,
    `id_proyecto`,
    `codigo`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_proyecto,
    NEW.codigo,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuestos_tg_alta$$
CREATE TRIGGER `presupuestos_tg_alta` AFTER INSERT ON `presupuestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuestos`,
    `id_saf`,
    `id_jurisdiccion`,
    `id_ubicacion_geografica`,
    `id_programa`,
    `id_subprograma`,
    `id_proyecto`,
    `id_actividad`,
    `id_obra`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_saf,
    NEW.id_jurisdiccion,
    NEW.id_ubicacion_geografica,
    NEW.id_programa,
    NEW.id_subprograma,
    NEW.id_proyecto,
    NEW.id_actividad,
    NEW.id_obra,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS presupuestos_tg_modificacion$$
CREATE TRIGGER `presupuestos_tg_modificacion` AFTER UPDATE ON `presupuestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.presupuestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_presupuestos`,
    `id_saf`,
    `id_jurisdiccion`,
    `id_ubicacion_geografica`,
    `id_programa`,
    `id_subprograma`,
    `id_proyecto`,
    `id_actividad`,
    `id_obra`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_saf,
    NEW.id_jurisdiccion,
    NEW.id_ubicacion_geografica,
    NEW.id_programa,
    NEW.id_subprograma,
    NEW.id_proyecto,
    NEW.id_actividad,
    NEW.id_obra,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS tipo_discapacidad_tg_alta$$
CREATE TRIGGER `tipo_discapacidad_tg_alta` AFTER INSERT ON `tipo_discapacidad` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.tipo_discapacidad
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_tipo_discapacidad`,
    `nombre`,
    `descripcion`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.descripcion,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS tipo_discapacidad_tg_modificacion$$
CREATE TRIGGER `tipo_discapacidad_tg_modificacion` AFTER UPDATE ON `tipo_discapacidad` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.tipo_discapacidad
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_tipo_discapacidad`,
    `nombre`,
    `descripcion`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.descripcion,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS titulo_tg_alta$$
CREATE TRIGGER `titulo_tg_alta` AFTER INSERT ON `titulo` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.titulo
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_titulo`,
    `id_tipo_titulo`,
    `nombre`,
    `abreviatura`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_tipo_titulo,
    NEW.nombre,
    NEW.abreviatura,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS titulo_tg_modificacion$$
CREATE TRIGGER `titulo_tg_modificacion` AFTER UPDATE ON `titulo` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.titulo
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_titulo`,
    `id_tipo_titulo`,
    `nombre`,
    `abreviatura`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_tipo_titulo,
    NEW.nombre,
    NEW.abreviatura,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS ubicacion_edificios_tg_alta$$
CREATE TRIGGER `ubicacion_edificios_tg_alta` AFTER INSERT ON `ubicacion_edificios` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.ubicacion_edificios
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_ubicacion_edificios`,
    `nombre`,
    `calle`,
    `numero`,
    `id_localidad`,
    `id_provincia`,
    `cod_postal`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.calle,
    NEW.numero,
    NEW.id_localidad,
    NEW.id_provincia,
    NEW.cod_postal,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS ubicacion_edificios_tg_modificacion$$
CREATE TRIGGER `ubicacion_edificios_tg_modificacion` AFTER UPDATE ON `ubicacion_edificios` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.ubicacion_edificios
    (  
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_ubicacion_edificios`,
    `nombre`,
    `calle`,
    `numero`,
    `id_localidad`,
    `id_provincia`,
    `cod_postal`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.calle,
    NEW.numero,
    NEW.id_localidad,
    NEW.id_provincia,
    NEW.cod_postal,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS ubicaciones_tg_alta$$
CREATE TRIGGER `ubicaciones_tg_alta` AFTER INSERT ON `ubicaciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.ubicaciones
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_ubicaciones`,
    `id_edificio`,
    `id_organismo`,
    `piso`,
    `oficina`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_edificio,
    NEW.id_organismo,
    NEW.piso,
    NEW.oficina,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS ubicaciones_tg_modificacion$$
CREATE TRIGGER `ubicaciones_tg_modificacion` AFTER UPDATE ON `ubicaciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.ubicaciones
    (  
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_ubicaciones`,
    `id_edificio`,
    `id_organismo`,
    `piso`,
    `oficina`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_edificio,
    NEW.id_organismo,
    NEW.piso,
    NEW.oficina,
    NEW.borrado
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS empleado_seguros_tg_alta$$
CREATE TRIGGER `empleado_seguros_tg_alta` AFTER INSERT ON `empleado_seguros` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_seguros
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_seguros`,
    `id_empleado`,
    `id_seguro`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_seguro,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS empleado_seguros_tg_modificacion$$
CREATE TRIGGER `empleado_seguros_tg_modificacion` AFTER UPDATE ON `empleado_seguros` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.empleado_seguros
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_empleado_seguros`,
    `id_empleado`,
    `id_seguro`,
    `fecha_desde`,
    `fecha_hasta`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.fecha_hasta <> "", "B", "M"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_seguro,
    NEW.fecha_desde,
    NEW.fecha_hasta
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS denominacion_funcion_tg_alta$$
CREATE TRIGGER `denominacion_funcion_tg_alta` AFTER INSERT ON `denominacion_funcion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.denominacion_funcion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_denominacion_funcion`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS denominacion_funcion_tg_modificacion$$
CREATE TRIGGER `denominacion_funcion_tg_modificacion` AFTER UPDATE ON `denominacion_funcion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.denominacion_funcion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_denominacion_funcion`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;
DELIMITER $$
DROP TRIGGER IF EXISTS denominacion_funcion_tg_alta$$
CREATE TRIGGER `denominacion_funcion_tg_alta` AFTER INSERT ON `denominacion_funcion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.denominacion_funcion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_denominacion_funcion`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS denominacion_funcion_tg_modificacion$$
CREATE TRIGGER `denominacion_funcion_tg_modificacion` AFTER UPDATE ON `denominacion_funcion` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.denominacion_funcion
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_denominacion_funcion`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS denominacion_puesto_tg_alta$$
CREATE TRIGGER `denominacion_puesto_tg_alta` AFTER INSERT ON `denominacion_puesto` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.denominacion_puesto
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_denominacion_puesto`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS denominacion_puesto_tg_modificacion$$
CREATE TRIGGER `denominacion_puesto_tg_modificacion` AFTER UPDATE ON `denominacion_puesto` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.denominacion_puesto
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_denominacion_puesto`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS observaciones_tg_alta$$
CREATE TRIGGER `observaciones_tg_alta` AFTER INSERT ON `observaciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.observaciones
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_observaciones`,
    `id_empleado`,
    `id_usuario_observaciones`,
    `id_bloque`,
    `fecha`,
    `descripcion`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_usuario,
    NEW.id_bloque,
    NEW.fecha,
    NEW.descripcion,
    NEW.borrado
    );
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS observaciones_tg_modificacion$$
CREATE TRIGGER `observaciones_tg_modificacion` AFTER UPDATE ON `observaciones` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.observaciones
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_observaciones`,
    `id_empleado`,
    `id_usuario_observaciones`,
    `id_bloque`,
    `fecha`,
    `descripcion`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M","B"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_usuario,
    NEW.id_bloque,
    NEW.fecha,
    NEW.descripcion,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS plantilla_horarios_tg_alta$$
CREATE TRIGGER `plantilla_horarios_tg_alta` AFTER INSERT ON `plantilla_horarios` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.plantilla_horarios
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_plantilla_horarios`,
    `nombre`,
    `horario`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.horario,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS plantilla_horarios_tg_modificacion$$
CREATE TRIGGER `plantilla_horarios_tg_modificacion` AFTER UPDATE ON `plantilla_horarios` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.plantilla_horarios
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_plantilla_horarios`,
    `nombre`,
    `horario`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.horario,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS puestos_tg_alta$$
CREATE TRIGGER `puestos_tg_alta` AFTER INSERT ON `puestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.puestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_puestos`,
    `id_subfamilia`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_subfamilia,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS puestos_tg_modificacion$$
CREATE TRIGGER `puestos_tg_modificacion` AFTER UPDATE ON `puestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.puestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_puestos`,
    `id_subfamilia`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_subfamilia,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS responsables_contrato_tg_alta$$
CREATE TRIGGER `responsables_contrato_tg_alta` AFTER INSERT ON `responsables_contrato` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.responsables_contrato
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_responsables_contrato`,
    `id_empleado`,
    `id_dependencia`,
    `id_tipo`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_empleado,
    NEW.id_dependencia,
    NEW.id_tipo,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS responsables_contrato_tg_modificacion$$
CREATE TRIGGER `responsables_contrato_tg_modificacion` AFTER UPDATE ON `responsables_contrato` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.responsables_contrato
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_responsables_contrato`,
    `id_empleado`,
    `id_dependencia`,
    `id_tipo`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_empleado,
    NEW.id_dependencia,
    NEW.id_tipo,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS subfamilia_puestos_tg_alta$$
CREATE TRIGGER `subfamilia_puestos_tg_alta` AFTER INSERT ON `subfamilia_puestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.subfamilia_puestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_subfamilia_puestos`,
    `id_familia`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.id_familia,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS subfamilia_puestos_tg_modificacion$$
CREATE TRIGGER `subfamilia_puestos_tg_modificacion` AFTER UPDATE ON `subfamilia_puestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.subfamilia_puestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_subfamilia_puestos`,
    `id_familia`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.id_familia,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS familia_puestos_tg_alta$$
CREATE TRIGGER `familia_puestos_tg_alta` AFTER INSERT ON `familia_puestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.familia_puestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_familia_puestos`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    "A",
    NEW.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS familia_puestos_tg_modificacion$$
CREATE TRIGGER `familia_puestos_tg_modificacion` AFTER UPDATE ON `familia_puestos` FOR EACH ROW
BEGIN
INSERT INTO sigarhu_historial.familia_puestos
    (
    `id_usuario`,
    `fecha_operacion`,
    `tipo_operacion`,
    `id_familia_puestos`,
    `nombre`,
    `borrado`
    )
VALUES
    (
    @id_usuario,
    NOW(),
    IF(NEW.borrado = 0, "M", "B"),
    OLD.id,
    NEW.nombre,
    NEW.borrado
    );
END$$
DELIMITER ;

INSERT INTO db_version VALUES('9.0', now());