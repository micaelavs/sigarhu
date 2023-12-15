/*########################### ATENCIÓN ###########################*/
/*############### CREAR NUEVO ESQUEMA DE HISTORIAL ###############*/
/*#### SI LA BASE DE HISTORIAL NO SE LLAMA "sigarhu_historial"####*/
/*####### SE DEBERÁ CAMBIAR EN LOS SCRIPTS DE LOS TRIGGERS #######*/
/*###################### EN SCRIPT V_9.sql #######################*/

SET FOREIGN_KEY_CHECKS = 0;

--
-- Table structure for table `anticorrupcion`
--

#DROP TABLE IF EXISTS `anticorrupcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `anticorrupcion` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_anticorrupcion` int(11),
  `id_empleado` int(11),
  `fecha_designacion` date,
  `fecha_publicacion_designacion` date,
  `fecha_aceptacion_renuncia` date,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `index2` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `anticorrupcion_presentacion`
--

#DROP TABLE IF EXISTS `anticorrupcion_presentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `anticorrupcion_presentacion` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_anticorrupcion_presentacion` int(11),
  `id_anticorrupcion` int(11),
  `tipo_presentacion` tinyint(1),
  `fecha_presentacion` date,
  `periodo` varchar(4),
  `nro_transaccion` varchar(20),
  `archivo` varchar(45),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_anticorrupcion_presentacion_1` (`id_anticorrupcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comisiones`
--

#DROP TABLE IF EXISTS `comisiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `comisiones` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),  
  `id_comisiones` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(4) ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `convenio_agrupamientos`
--

#DROP TABLE IF EXISTS `convenio_agrupamientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_agrupamientos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_agrupamientos` int(11),
  `id_modalidad_vinculacion` int(11),
  `id_situacion_revista` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `convenio_agrupamientos_borrado_IDX` (`id_usuario`) USING BTREE,
  KEY `convenio_agrupamientos_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE,
  KEY `convenio_agrupamientos_id_situacion_revista_IDX` (`id_situacion_revista`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `convenio_funciones_ejecutivas`
--

#DROP TABLE IF EXISTS `convenio_funciones_ejecutivas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_funciones_ejecutivas` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_funciones_ejecutivas` int(11),
  `id_modalidad_vinculacion` int(11),
  `id_situacion_revista` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `convenio_funciones_ejecutivas_borrado_IDX` (`id_usuario`) USING BTREE,
  KEY `convenio_funciones_ejecutivas_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE,
  KEY `convenio_funciones_ejecutivas_id_situacion_revista_IDX` (`id_situacion_revista`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `convenio_grados`
--

#DROP TABLE IF EXISTS `convenio_grados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_grados` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_grados` int(11),
  `id_tramo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `convenio_grados_borrado_IDX` (`id_usuario`) USING BTREE,
  KEY `convenio_grados_id_tramo_IDX` (`id_tramo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `convenio_modalidad_vinculacion`
--

#DROP TABLE IF EXISTS `convenio_modalidad_vinculacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_modalidad_vinculacion` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_modalidad_vinculacion` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `convenio_modalidad_vinculacion_id_IDX` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `convenio_situacion_revista`
--

#DROP TABLE IF EXISTS `convenio_situacion_revista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_situacion_revista` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_situacion_revista` int(11),
  `id_modalidad_vinculacion` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `convenio_situacion_revista_id_IDX` (`id`) USING BTREE,
  KEY `convenio_situacion_revista_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `convenio_niveles`
--

#DROP TABLE IF EXISTS `convenio_niveles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_niveles` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_niveles` int(11),
  `id_agrupamiento` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `convenio_niveles_borrado_IDX` (`id_usuario`) USING BTREE,
  KEY `convenio_niveles_id_agrupamiento_IDX` (`id_agrupamiento`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `convenio_tramos`
--

#DROP TABLE IF EXISTS `convenio_tramos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `convenio_tramos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_convenio_tramos` int(11),
  `id_modalidad_vinculacion` int(11),
  `id_situacion_revista` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `convenio_tramos_borrado_IDX` (`id_usuario`) USING BTREE,
  KEY `convenio_tramos_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE,
  KEY `convenio_tramos_id_situacion_revista_IDX` (`id_situacion_revista`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `denominacion_funcion`
--

#DROP TABLE IF EXISTS `denominacion_funcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `denominacion_funcion` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),  
  `id_denominacion_funcion` int(11),
  `nombre` varchar(60),
  `borrado` tinyint(4),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `denominacion_puesto`
--

#DROP TABLE IF EXISTS `denominacion_puesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `denominacion_puesto` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),  
  `id_denominacion_puesto` int(11),
  `nombre` varchar(60),
  `borrado` tinyint(4),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `dependencias`
--

#DROP TABLE IF EXISTS `dependencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dependencias` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_dependencias` int(11),
  `nombre` varchar(255),
  `codep` varchar(10),
  `id_padre` int(8),
  `fecha_desde` date,
  `fecha_hasta` date,
  `nivel` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `id_padre` (`id_padre`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dependencias_informales`
--

#DROP TABLE IF EXISTS `dependencias_informales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dependencias_informales` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_dependencias_informales` int(11),
  `id_dependencia` int(11),
  `nombre` varchar(225),
  `fecha_desde` date,
  `fecha_hasta` date,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `documentos_empleados`
--

#DROP TABLE IF EXISTS `empleado_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_documentos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_documentos` int(11),
  `id_empleado` int(11),
  `id_bloque` tinyint(1),
  `nombre_archivo` varchar(150),
  `fecha_reg` date,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_documentos_empleados_1_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_comision`
--

#DROP TABLE IF EXISTS `empleado_comision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_comision` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_comision` int(11),
  `id_empleado` int(11),
  `id_comision_origen` int(11),
  `id_comision_destino` int(11),
  `fecha_inicio` date,
  `fecha_fin` date,
  PRIMARY KEY (`id`),
  KEY `empleado_comision_id_comision_origen_IDX` (`id_comision_origen`),
  KEY `empleado_comision_id_comision_destino_IDX` (`id_comision_destino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `embargos`
--

#DROP TABLE IF EXISTS `embargos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `embargos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_embargos` int(11),
  `id_empleado` int(11),
  `tipo_embargo` tinyint(1),
  `autos` varchar(255),
  `fecha_alta` date,
  `fecha_cancelacion` date,
  `monto` varchar(45),
  `borrado` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `index1` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_dependencia`
--

#DROP TABLE IF EXISTS `empleado_dependencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_dependencia` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_dependencia` int(11),
  `id_empleado` int(11),
  `id_dependencia` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,
  `borrado` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `empleado_index` (`id_empleado`),
  KEY `dep_prin_index` (`id_dependencia`),
  KEY `fechdes_index` (`fecha_desde`),
  KEY `fech_has_index` (`fecha_hasta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_dep_informales`
--

#DROP TABLE IF EXISTS `empleado_dep_informales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_dep_informales` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_dep_informal` int(11),
  `id_empleado` int(11),
  `id_dep_informal` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,
  `borrado` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `empleado_index` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_escalafon`
--

#DROP TABLE IF EXISTS `empleado_escalafon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_escalafon` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_escalafon` int(11),
  `id_empleado` int(11),
  `id_modalidad_vinculacion` int(11),
  `id_situacion_revista` int(11),
  `id_nivel` int(11),
  `id_grado` int(11),
  `id_tramo` int(11),
  `id_agrupamiento` int(11),
  `id_funcion_ejecutiva` int(11),
  `compensacion_geografica` tinyint(1) ,
  `compensacion_transitoria` tinyint(1) ,
  `fecha_inicio` date,
  `fecha_fin` date,
  `ultimo_cambio_nivel` date,
  `exc_art_14` varchar(45),
  PRIMARY KEY (`id`),
  KEY `empleado_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_horarios`
--

#DROP TABLE IF EXISTS `empleado_horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_horarios` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_horarios` int(11),
  `id_empleado` int(11),
  `horarios` varchar(250),
  `id_turno` tinyint(1),
  `fecha_inicio` date,
  `fecha_fin` date,
  `borrado` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `empleado_horarios_idx` (`id_empleado`)
) ENGINE=MEMORY DEFAULT CHARSET=armscii8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_horas_extras`
--

#DROP TABLE IF EXISTS `empleado_horas_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_horas_extras` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_horas_extras` int(11),
  `id_empleado` int(11),
  `anio` varchar(4),
  `mes` varchar(2),
  `acto_administrativo` varchar(45),
  `borrado` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_horas_extras_1_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleado_perfil`
--

#DROP TABLE IF EXISTS `empleado_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_perfil` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_perfil` int(11),
  `id_empleado` int(11),
  `denominacion_funcion` int(11),
  `denominacion_puesto` int(11),
  `objetivo_gral` text,
  `objetivo_especifico` varchar(45),
  `estandares` varchar(45),
  `fecha_obtencion_result` date,
  `nivel_destreza` tinyint(4),
  `nombre_puesto` int(11),
  `puesto_supervisa` tinyint(4),
  `nivel_complejidad` tinyint(4),
  `fecha_desde` date,
  `fecha_hasta` date,
  `familia_de_puestos` int(11),
  PRIMARY KEY (`id`),
  KEY `fk_perfil_1_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleados`
--

#DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleados` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado` int(11),
  `id_persona` int(11),
  `cuit` decimal(11,0),
  `email` varchar(60),
  `planilla_reloj` tinyint(1),
  `en_comision` tinyint(1) ,
  `credencial` tinyint(1) ,
  `borrado` tinyint(1) ,
  `antiguedad_adm_publica` varchar(45),
  `id_sindicato` int(11),
  `fecha_vigencia_mandato` date,
  `estado` tinyint(1),
  `id_motivo` int(11),
  `fecha_baja` date,
  `fecha_vencimiento` date,
  `veterano_guerra` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `empleados_id_IDX` (`id_empleado`) USING BTREE,
  KEY `empleados_persona_id_IDX` (`id_persona`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleado_presupuesto`
--

#DROP TABLE IF EXISTS `empleado_presupuesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_presupuesto` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_presupuesto` int(11),
  `id_empleado` int(11),
  `id_presupuesto` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_empleado_presupuesto_1_idx` (`id_presupuesto`),
  KEY `fk_empleado_presupuesto_2_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_salud`
--

#DROP TABLE IF EXISTS `empleado_salud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_salud` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_salud` int(11),
  `id_empleado` int(11),
  `id_obra_social` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_salud_1_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleados_seguros`
--

CREATE TABLE `empleado_seguros` (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`id_usuario` int(11),
`fecha_operacion` datetime,
`tipo_operacion` char(1),
`id_empleado_seguros` int(11),
`id_empleado` INT(11) NOT NULL,
`id_seguro` INT(11) NULL,
`fecha_desde` DATE NOT NULL,
`fecha_hasta` DATE  NULL,
PRIMARY KEY (`id`),
KEY `fk_empleado_seguros_1_idx` (`id_empleado`))
ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `a_sindicatos`
--

#DROP TABLE IF EXISTS `empleado_sindicatos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleado_sindicatos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleado_sindicatos` int(11),
  `id_empleado` int(11),
  `id_sindicato` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_sindicato_1_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleados_lic_especiales`
--

#DROP TABLE IF EXISTS `empleados_lic_especiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleados_lic_especiales` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleados_lic_especiales` int(11),
  `id_empleado` int(11),
  `id_licencia` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,
  `borrado` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `fk_empleados_licencias_1_idx` (`id_empleado`),
  KEY `fk_empleados_licencias_2_idx` (`id_licencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleados_x_ubicacion`
--

#DROP TABLE IF EXISTS `empleados_x_ubicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empleados_x_ubicacion` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_empleados_x_ubicacion` int(11),
  `id_empleado` int(11),
  `id_ubicacion` int(11),
  `fecha_desde` date,
  `fecha_hasta` date,
  PRIMARY KEY (`id`),
  KEY `empleados_x_ubicacion_id_empleado_IDX` (`id_empleado`) USING BTREE,
  KEY `fk_empleados_x_ubicacion_1_idx` (`id_ubicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `familiar_discapacidad`
--

#DROP TABLE IF EXISTS `familiar_discapacidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `familiar_discapacidad` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_familiar_discapacidad` int(11),
  `id_familiar` int(11),
  `id_tipo_discapacidad` int(11),
  `cud` varchar(45),
  `fecha_alta` date,
  `fecha_vencimiento` date,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_familiar_discapacidad_1_idx` (`id_familiar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `familia_de_puestos`
--

#DROP TABLE IF EXISTS `familia_puestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `familia_puestos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_familia_puestos` int(11),
  `nombre` varchar(60),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_familiar_puestos_idx` (`id_familia_puestos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `licencias_especiales`
--

#DROP TABLE IF EXISTS `licencias_especiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `licencias_especiales` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_licencias_especiales` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupo_familiar`
--

#DROP TABLE IF EXISTS `grupo_familiar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `grupo_familiar` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_grupo_familiar` int(11),
  `id_empleado` int(11),
  `parentesco` tinyint(1),
  `nombre` varchar(50),
  `apellido` varchar(50),
  `fecha_nacimiento` date,
  `nacionalidad` varchar(4),
  `tipo_documento` tinyint(1),
  `documento` varchar(10),
  `nivel_educativo` tinyint(1),
  `reintegro_guarderia` tinyint(1) ,
  `discapacidad` tinyint(1) ,
  `desgrava_afip` int(2),
  `fecha_desde` date,
  `fecha_hasta` date,
  `borrado` tinyint(1) ,
  PRIMARY KEY (`id`),
  KEY `fk_grupo_familiar_1_idx` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `nivel_educativo`
--

#DROP TABLE IF EXISTS `nivel_educativo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `nivel_educativo` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_nivel_educativo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `id_nivel_educativo_idx` (`id_nivel_educativo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `motivo_baja`
--

#DROP TABLE IF EXISTS `motivo_baja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `motivo_baja` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_motivo_baja` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `id_motivo_baja_idx` (`id_motivo_baja`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;






--
-- Table structure for table `perfil_actividades`
--

#DROP TABLE IF EXISTS `perfil_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `perfil_actividades` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_perfil_actividades` int(11),
  `id_perfil` int(11),
  `nombre` text,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `perfil_actividades_idx` (`id_perfil_actividades`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `obras_sociales`
--

#DROP TABLE IF EXISTS `obras_sociales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `obras_sociales` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_obras_sociales` int(11),
  `codigo` varchar(6),
  `nombre` varchar(100),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `obras_sociales_idx` (`id_obras_sociales`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

#DROP TABLE IF EXISTS `observaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `observaciones` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),  
  `id_observaciones` int(11),
  `id_empleado` int(11),
  `id_usuario_observaciones` int(11),
  `id_bloque` int(11),
  `fecha` date,
  `descripcion` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `observaciones_idx` (`id_observaciones`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfil_resultado_parc_final`
--

#DROP TABLE IF EXISTS `perfil_resultado_parc_final`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `perfil_resultado_parc_final` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_perfil_resultado_parc_final` int(11),
  `id_perfil` int(11),
  `nombre` text,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `perfil_resultado_parc_final_idx` (`id_perfil_resultado_parc_final`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfil_tarea`
--

#DROP TABLE IF EXISTS `perfil_tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `perfil_tarea` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_perfil_tarea` int(11),
  `nombre` text,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `perfil_tarea_idx` (`id_perfil_tarea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona_discapacidad`
--

#DROP TABLE IF EXISTS `persona_discapacidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `persona_discapacidad` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_persona_discapacidad` int(11),
  `id_persona` int(11),
  `id_tipo_discapacidad` int(11),
  `cud` varchar(45),
  `fecha_vencimiento` date,
  `observaciones` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_persona_dis_1_idx` (`id_persona`),
  KEY `fk_persona_discapacidad_1_idx` (`id_tipo_discapacidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona_domicilio`
--

#DROP TABLE IF EXISTS `persona_domicilio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `persona_domicilio` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_persona_domicilio` int(11),
  `id_persona` int(11),
  `calle` varchar(50),
  `numero` varchar(6),
  `piso` varchar(8),
  `depto` varchar(4),
  `cod_postal` varchar(8),
  `id_provincia` int(11),
  `id_localidad` int(11),
  `fecha_alta` date,
  `fecha_baja` date,
  PRIMARY KEY (`id`),
  KEY `fk_persona_domicilio_1_idx` (`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona_otros_conocimientos`
--

#DROP TABLE IF EXISTS `persona_otros_conocimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `persona_otros_conocimientos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_persona_otros_conocimientos` int(11),
  `id_persona` int(11),
  `id_tipo` tinyint(1),
  `fecha` date,
  `descripcion` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_otros_conocimientos_1_idx` (`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personas`
--

#DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `personas` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_personas` int(11),
  `tipo_documento` tinyint(1),
  `documento` varchar(10),
  `nombre` varchar(100),
  `apellido` varchar(64),
  `fecha_nac` date,
  `genero` tinyint(1),
  `nacionalidad` varchar(4),
  `estado_civil` tinyint(1),
  `email` varchar(100),
  `foto_persona` varchar(100),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `documento` (`documento`) USING BTREE,
  KEY `personas_borrado_IDX` (`id_personas`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona_titulo`
--

#DROP TABLE IF EXISTS `persona_titulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `persona_titulo` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_persona_titulo` int(11),
  `id_persona` int(11),
  `id_tipo_titulo` tinyint(3),
  `id_estado_titulo` tinyint(1),
  `id_titulo` int(11),
  `abreviatura` varchar(10),
  `fecha` date,
  `principal` tinyint(4) ,
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `fk_titulos_1_idx` (`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona_telefono`
--

#DROP TABLE IF EXISTS `persona_telefono`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `persona_telefono` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_persona_telefono` int(11),
  `id_persona` int(11),
  `id_tipo_telefono` tinyint(4),
  `telefono` int(11),
  `fecha_alta` date,
  `fecha_baja` date,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_actividades`
--

#DROP TABLE IF EXISTS `presupuesto_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_actividades` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_actividades` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_actividades_idx` (`id_presupuesto_actividades`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plantilla_horarios`
--

#DROP TABLE IF EXISTS `plantilla_horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `plantilla_horarios` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_plantilla_horarios` int(11) unsigned,
  `nombre` varchar(80),
  `horario` varchar(250),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `plantilla_horarios_idx` (`id_plantilla_horarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_jurisdicciones`
--

#DROP TABLE IF EXISTS `presupuesto_jurisdicciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_jurisdicciones` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_jurisdicciones` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_jurisdicciones_idx` (`id_presupuesto_jurisdicciones`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_obras`
--

#DROP TABLE IF EXISTS `presupuesto_obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_obras` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_obras` int(11),
  `id_proyecto` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_obras_idx` (`id_presupuesto_obras`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_programas`
--

#DROP TABLE IF EXISTS `presupuesto_programas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_programas` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_programas` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_programas_idx` (`id_presupuesto_programas`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_proyectos`
--

#DROP TABLE IF EXISTS `presupuesto_proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_proyectos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_proyectos` int(11),
  `id_programa` int(11),
  `id_subprograma` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_proyectos_idx` (`id_presupuesto_proyectos`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuestos`
--

#DROP TABLE IF EXISTS `presupuestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuestos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuestos` int(11),
  `id_saf` int(11),
  `id_jurisdiccion` int(11),
  `id_ubicacion_geografica` int(11),
  `id_programa` int(11),
  `id_subprograma` int(11),
  `id_proyecto` int(11),
  `id_actividad` int(11),
  `id_obra` int(11),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuestos_idx` (`id_presupuestos`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_saf`
--

#DROP TABLE IF EXISTS `presupuesto_saf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_saf` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_saf` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_saf_idx` (`id_presupuesto_saf`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_ubicaciones_geograficas`
--

#DROP TABLE IF EXISTS `presupuesto_ubicaciones_geograficas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_ubicaciones_geograficas` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_ubicaciones_geograficas` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_ubicaciones_geograficas_idx` (`id_presupuesto_ubicaciones_geograficas`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `presupuesto_subprogramas`
--

#DROP TABLE IF EXISTS `presupuesto_subprogramas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `presupuesto_subprogramas` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_presupuesto_subprogramas` int(11),
  `id_programa` int(11),
  `codigo` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `presupuesto_subprogramas_idx` (`id_presupuesto_subprogramas`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sindicatos`
--

#DROP TABLE IF EXISTS `sindicatos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `sindicatos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_sindicatos` int(11),
  `codigo` varchar(20),
  `nombre` varchar(100),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `sindicatos_idx` (`id_sindicatos`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `puestos`
--

#DROP TABLE IF EXISTS `puestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `puestos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_puestos` int(11),
  `id_subfamilia` int(11),
  `nombre` varchar(60),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `puestos_idx` (`id_puestos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

#DROP TABLE IF EXISTS `responsables_contrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `responsables_contrato` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_responsables_contrato` int(11),
  `id_empleado` int(11),
  `id_dependencia` int(11),
  `id_tipo` tinyint(1),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `index_id_dependencia` (`id_dependencia`),
  KEY `index_contratante` (`id_dependencia`,`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `seguro_vida`
--

#DROP TABLE IF EXISTS `seguro_vida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `seguro_vida` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_seguro_vida` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `seguro_vida_idx` (`id_seguro_vida`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


#DROP TABLE IF EXISTS `subfamilia_puestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `subfamilia_puestos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_subfamilia_puestos` int(11),
  `id_familia` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `subfamilia_puestos_idx` (`id_subfamilia_puestos`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


#DROP TABLE IF EXISTS `subfamilia_puestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `subfamilia_puestos` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_subfamilia_puestos` int(11),
  `id_familia` int(11),
  `nombre` varchar(255),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `subfamilia_puestos_idx` (`id_subfamilia_puestos`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `tipo_discapacidad`
--

#DROP TABLE IF EXISTS `tipo_discapacidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tipo_discapacidad` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_tipo_discapacidad` int(11),
  `nombre` varchar(45),
  `descripcion` varchar(100),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `tipo_discapacidad_idx` (`id_tipo_discapacidad`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `titulo`
--

#DROP TABLE IF EXISTS `titulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `titulo` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_titulo` int(11),
  `id_tipo_titulo` tinyint(1),
  `nombre` varchar(150),
  `abreviatura` varchar(30),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `titulo_idx` (`id_titulo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ubicacion_edificios`
--

#DROP TABLE IF EXISTS `ubicacion_edificios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ubicacion_edificios` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_ubicacion_edificios` int(11),
  `nombre` varchar(255),
  `calle` varchar(255),
  `numero` int(11),
  `id_localidad` int(11),
  `id_provincia` int(11),
  `cod_postal` varchar(8),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `ubicacion_edificios` (`id_ubicacion_edificios`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ubicaciones`
--

#DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ubicaciones` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_ubicaciones` int(11),
  `id_edificio` int(11),
  `id_organismo` int(8),
  `piso` varchar(8),
  `oficina` varchar(10),
  `borrado` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `id_ubicaciones` (`id_ubicaciones`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `contratante`
--

#DROP TABLE IF EXISTS `contratante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratante` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11),
  `fecha_operacion` datetime,
  `tipo_operacion` char(1),
  `id_contratante` int(11),
  `id_empleado` varchar(45) DEFAULT NULL,
  `id_dependencia` int(11) DEFAULT NULL,
  `cuit` decimal(11,0) DEFAULT NULL,
  `trd` varchar(45) DEFAULT NULL,
  `tr2` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO db_version VALUES('8.0', now());