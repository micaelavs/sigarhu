INSERT INTO `convenio_situacion_revista` (`id_modalidad_vinculacion`, `nombre`, `borrado`) VALUES ('6', 'No aplica', '0');
INSERT INTO `convenio_funciones_ejecutivas` (`id_modalidad_vinculacion`, `id_situacion_revista`, `nombre`, `borrado`) VALUES ('6', '13', 'Ad-Honorem', '0');	
INSERT INTO `convenio_funciones_ejecutivas` (`id_modalidad_vinculacion`, `id_situacion_revista`, `nombre`, `borrado`) VALUES ('1', '3', 'Ad-Honorem', '0');
INSERT INTO `convenio_situacion_revista` (`id_modalidad_vinculacion`, `nombre`, `borrado`) VALUES ('5', 'No aplica', '0');

INSERT INTO `convenio_agrupamientos` (`id_modalidad_vinculacion`, `id_situacion_revista`, `nombre`, `borrado`) VALUES ('5', '14', 'No aplica', '0');

INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`, `borrado`) VALUES ('13', 'A', '0');

INSERT INTO `convenio_funciones_ejecutivas` (`id_modalidad_vinculacion`, `id_situacion_revista`, `nombre`, `borrado`) VALUES ('5', '14', 'Nivel I', '0');

INSERT INTO `convenio_agrupamientos` (`id_modalidad_vinculacion`, `id_situacion_revista`, `nombre`, `borrado`) VALUES ('1', '3', 'No aplica', '0');

INSERT INTO `convenio_niveles` (`id_agrupamiento`, `nombre`, `borrado`) VALUES ('14', 'Ad-Honorem', '0');



INSERT INTO VALUES ('120', 'Unidad de Auditoría Interna', NULL, '1', '2019-01-10', NULL, '4');
INSERT INTO VALUES ('121', 'Auditoría Interna Adjunta', NULL, '120', '2019-01-10', NULL, '6');
INSERT INTO VALUES ('122', 'Modernización de la Red de Colectivos del área Metropolitana de Bs. As. (AMBA)', NULL, '5', '2019-01-10', NULL, '3');
INSERT INTO VALUES ('123', 'Red de Expresos Regionales de la Región Metropolitana de Buenos Aires (RER)', NULL, '4', '2019-01-10', NULL, '4');
INSERT INTO VALUES ('124', 'Auditoría Operativa', NULL, '121', '2019-07-19', '0000-00-00', '7');
INSERT INTO VALUES ('125', 'Auditoría Contable Presupuestaria y Financiera', NULL, '121', '2019-07-19', NULL, '7');
INSERT INTO VALUES ('126', 'Auditoría de Sistemas y Tenología de la Información', NULL, '121', '2019-07-19', NULL, '7');
INSERT INTO VALUES ('127', 'Auditoría Legal', NULL, '121', '2019-07-19', NULL, '7');
INSERT INTO VALUES ('128', 'Auditoría de Fondos Fiduciarios', NULL, '121', '2019-07-19', NULL, '7');

INSERT INTO `familia_puestos` (`id`, `nombre`) VALUES ('9', 'Sin Definir');

UPDATE `seguro_vida` SET `nombre` = 'Seguro Social Obligatorio' WHERE (`id` = '1');
UPDATE `seguro_vida` SET `nombre` = 'Seguro Obligatorio del Personal del Estado' WHERE (`id` = '2');
UPDATE `seguro_vida` SET `nombre` = 'Seguro de Vida Colectivo' WHERE (`id` = '3');
UPDATE `seguro_vida` SET `nombre` = 'Seguro de Vida Colectivo Adherente' WHERE (`id` = '4');

INSERT INTO db_version VALUES('7.0', now());