USE `sigarhu`;
SET @id_empleado := 1132;
SET @id_persona := 1132;
SET @id_usuario := 99999;

INSERT INTO `persona_titulo` (`id_persona`,`id_tipo_titulo`,`id_estado_titulo`,`id_titulo`,`fecha`,`principal`,`borrado`)
VALUES(@id_persona,3,2,25,'2012-03-10',0,0);


SELECT id INTO @id_titulo FROM persona_titulo WHERE id_persona = @id_persona AND borrado = 0 AND id_estado_titulo = 2 order by id desc limit 1;

INSERT INTO `persona_titulo_creditos` (`id_persona_titulo`, `id_persona`, `fecha`, `acto_administrativo`, `creditos`, `estado_titulo`, `borrado`) 
VALUES 
(@id_titulo, @id_persona, '2012-03-10', 'ASDFASD123', '100', '2', '0');

INSERT INTO `empleado_escalafon` (`id_empleado`,`id_modalidad_vinculacion`,`id_situacion_revista`,`id_nivel`,`id_grado`,`id_tramo`,`id_agrupamiento`,`id_funcion_ejecutiva`,`compensacion_geografica`,`compensacion_transitoria`,`fecha_inicio`,`fecha_fin`,`exc_art_14`,`unidad_retributiva`)
VALUES
(@id_empleado,1,1,4,42,3,1,NULL,NULL,NULL,'2019-10-29','2020-08-04','',NULL),
(@id_empleado,6,13,4,42,3,1,NULL,NULL,NULL,'2020-08-04','2020-08-04','',NULL),
(@id_empleado,1,1,1,1,1,1,NULL,NULL,NULL,'2020-08-04',NULL,'',NULL);

INSERT INTO `empleado_ultimos_cambios` (`id_empleado`,`id_tipo`,`id_convenios`,`fecha_desde`,`fecha_hasta`) VALUES 
(@id_empleado,1,1,'2010-01-01',NULL),
(@id_empleado,2,1,'2010-01-01',NULL);

INSERT INTO `empleado_cursos` (`id_empleado`,`id_curso`,`fecha`,`tipo_promocion`,`borrado`) VALUES
(@id_empleado,1,'2010-02-01',1,0),
(@id_empleado,2,'2010-03-01',1,0),
(@id_empleado,3,'2010-04-01',1,0),
(@id_empleado,4,'2011-02-01',1,0),
(@id_empleado,5,'2012-02-01',1,0),
(@id_empleado,6,'2013-02-01',1,0),
(@id_empleado,7,'2014-02-01',1,0),
(@id_empleado,8,'2015-02-01',1,0),
(@id_empleado,9,'2016-02-01',1,0),
(@id_empleado,10,'2017-02-01',1,0),
(@id_empleado,11,'2018-02-01',1,0),
(@id_empleado,12,'2018-02-01',NULL,0),
(@id_empleado,15,'2019-02-01',NULL,0),
(@id_empleado,17,'2020-02-01',NULL,0);

SELECT id INTO @id_perfil FROM empleado_perfil WHERE id_empleado = @id_empleado AND fecha_hasta IS NULL order by id desc limit 1;

INSERT INTO `empleado_evaluaciones` (`id_perfil`,`id_empleado`,`acto_administrativo`,`evaluacion`,`anio`,`archivo`,`fecha_evaluacion`,`formulario`,`puntaje`,`bonificado`,`borrado`) VALUES
(id_perfil,@id_empleado,'asdasd123',2,2010,NULL,'2020-08-04',1,10,0,0),
(id_perfil,@id_empleado,'ASD234',6,2011,NULL,'2020-08-04',1,1,0,0),
(id_perfil,@id_empleado,'ASD123',2,2013,NULL,'2020-08-04',1,20,0,0),
(id_perfil,@id_empleado,'asdsad3214',3,2014,NULL,'2020-08-04',5,20,0,0),
(id_perfil,@id_empleado,'asd23466',3,2015,NULL,'2020-08-04',2,20,0,0),
(id_perfil,@id_empleado,'asdasd1234',3,2016,NULL,'2020-08-04',6,20,0,0),
(id_perfil,@id_empleado,'ASDASD679809',2,2017,NULL,'2020-08-04',2,20,0,0),
(id_perfil,@id_empleado,'ASDFGNVBN9',2,2018,NULL,'2020-08-04',2,30,1,0),
(id_perfil,@id_empleado,'BNMBNBNM7890',2,2019,NULL,'2020-08-04',4,20,0,0);
