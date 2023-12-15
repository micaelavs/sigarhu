-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: sigarhu
-- ------------------------------------------------------
-- Server version	5.5.62-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


--
-- Table structure for table `contratante`
--

DROP TABLE IF EXISTS `contratante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` varchar(45) DEFAULT NULL,
  `trd` varchar(45) DEFAULT NULL,
  `tr2` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `convenio_agrupamientos`
--

DROP TABLE IF EXISTS `convenio_agrupamientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convenio_agrupamientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modalidad_vinculacion` int(11) NOT NULL,
  `id_situacion_revista` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `convenio_agrupamientos_borrado_IDX` (`borrado`) USING BTREE,
  KEY `convenio_agrupamientos_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE,
  KEY `convenio_agrupamientos_id_situacion_revista_IDX` (`id_situacion_revista`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio_agrupamientos`
--

LOCK TABLES `convenio_agrupamientos` WRITE;
/*!40000 ALTER TABLE `convenio_agrupamientos` DISABLE KEYS */;
INSERT INTO `convenio_agrupamientos` VALUES (1,1,1,'General',0),(2,1,1,'Profesional',0),(3,1,1,'Cientifica Tecnica',0),(4,1,1,'Especialista',0),(5,1,2,'Operativa',0),(6,1,2,'Terciario',0),(7,1,2,'Universitario',0);
/*!40000 ALTER TABLE `convenio_agrupamientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenio_funciones_ejecutivas`
--

DROP TABLE IF EXISTS `convenio_funciones_ejecutivas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convenio_funciones_ejecutivas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modalidad_vinculacion` int(11) NOT NULL,
  `id_situacion_revista` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `convenio_funciones_ejecutivas_borrado_IDX` (`borrado`) USING BTREE,
  KEY `convenio_funciones_ejecutivas_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE,
  KEY `convenio_funciones_ejecutivas_id_situacion_revista_IDX` (`id_situacion_revista`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio_funciones_ejecutivas`
--

LOCK TABLES `convenio_funciones_ejecutivas` WRITE;
/*!40000 ALTER TABLE `convenio_funciones_ejecutivas` DISABLE KEYS */;
INSERT INTO `convenio_funciones_ejecutivas` VALUES (1,1,1,'I',0),(2,1,1,'II',0),(3,1,1,'III',0),(4,1,1,'IV',0);
/*!40000 ALTER TABLE `convenio_funciones_ejecutivas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenio_modalidad_vinculacion`
--

DROP TABLE IF EXISTS `convenio_modalidad_vinculacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convenio_modalidad_vinculacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `convenio_modalidad_vinculacion_id_IDX` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio_modalidad_vinculacion`
--

LOCK TABLES `convenio_modalidad_vinculacion` WRITE;
/*!40000 ALTER TABLE `convenio_modalidad_vinculacion` DISABLE KEYS */;
INSERT INTO `convenio_modalidad_vinculacion` VALUES (1,'SINEP',0),(2,'Prestacion de Servicios',0),(3,'Personal Embarcado',0),(4,'Otra',0),(5,'Extraescalafonario',0),(6,'Autoridad Superior',0);
/*!40000 ALTER TABLE `convenio_modalidad_vinculacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenio_niveles`
--

DROP TABLE IF EXISTS `convenio_niveles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convenio_niveles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_agrupamiento` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `convenio_niveles_borrado_IDX` (`borrado`) USING BTREE,
  KEY `convenio_niveles_id_agrupamiento_IDX` (`id_agrupamiento`) USING BTREE,
  CONSTRAINT `fk_convenio_niveles_1` FOREIGN KEY (`id_agrupamiento`) REFERENCES `convenio_agrupamientos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio_niveles`
--

LOCK TABLES `convenio_niveles` WRITE;
/*!40000 ALTER TABLE `convenio_niveles` DISABLE KEYS */;
INSERT INTO `convenio_niveles` VALUES (1,1,'A',0),(2,1,'B',0),(3,1,'C',0),(4,1,'D',0),(5,1,'E',0),(6,1,'F',0),(7,2,'A',0),(8,2,'B',0),(9,2,'C',0),(10,2,'D',0),(11,3,'A',0),(12,3,'B',0),(13,3,'C',0),(14,3,'D',0),(15,4,'A',0),(16,4,'B',0),(17,5,'A',0),(18,5,'B',0),(19,5,'C',0),(20,5,'D',0),(21,5,'E',0),(22,5,'F',0),(23,6,'A',0),(24,6,'B',0),(25,6,'C',0),(26,6,'D',0),(27,6,'E',0),(28,6,'F',0),(29,7,'A',0),(30,7,'B',0),(31,7,'C',0),(32,7,'D',0),(33,7,'E',0),(34,7,'F',0);
/*!40000 ALTER TABLE `convenio_niveles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenio_situacion_revista`
--

DROP TABLE IF EXISTS `convenio_situacion_revista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convenio_situacion_revista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modalidad_vinculacion` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `convenio_situacion_revista_id_IDX` (`id`) USING BTREE,
  KEY `convenio_situacion_revista_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE,
  CONSTRAINT `fk_convenio_situacion_revista_1` FOREIGN KEY (`id_modalidad_vinculacion`) REFERENCES `convenio_modalidad_vinculacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio_situacion_revista`
--

LOCK TABLES `convenio_situacion_revista` WRITE;
/*!40000 ALTER TABLE `convenio_situacion_revista` DISABLE KEYS */;
INSERT INTO `convenio_situacion_revista` VALUES (1,1,'Planta Permanente',0),(2,1,'Ley Marco',0),(3,1,'Designacion Transitoria en Cargo de Planta Permanente con Funcion Ejecutiva',0),(4,1,'Planta Permanente con Designacion Transitoria',0),(5,2,'1109/17',0),(6,2,'1109/17 con Financimiento Externo',0),(7,2,'Asistencia Tecnica',0),(8,3,'CLM',0),(9,3,'Planta Permanente',0),(10,4,'Comision Servicios',0),(11,4,'Planta Permanente con Designación Transitoria',0),(12,4,'UR',0);
/*!40000 ALTER TABLE `convenio_situacion_revista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenio_tramos`
--

DROP TABLE IF EXISTS `convenio_tramos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convenio_tramos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modalidad_vinculacion` int(11) NOT NULL,
  `id_situacion_revista` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `convenio_tramos_borrado_IDX` (`borrado`) USING BTREE,
  KEY `convenio_tramos_id_modalidad_vinculacion_IDX` (`id_modalidad_vinculacion`) USING BTREE,
  KEY `convenio_tramos_id_situacion_revista_IDX` (`id_situacion_revista`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio_tramos`
--

LOCK TABLES `convenio_tramos` WRITE;
/*!40000 ALTER TABLE `convenio_tramos` DISABLE KEYS */;
INSERT INTO `convenio_tramos` VALUES (1,1,1,'Adicional Tramo General',0),(2,1,1,'Adicional Tramo Intermedio',0),(3,1,1,'Adicional Tramo Avanzado',0),(4,1,2,'Sin Tramo',0);
/*!40000 ALTER TABLE `convenio_tramos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenio_grados`
--

DROP TABLE IF EXISTS `convenio_grados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convenio_grados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tramo` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `convenio_grados_borrado_IDX` (`borrado`) USING BTREE,
  KEY `convenio_grados_id_tramo_IDX` (`id_tramo`) USING BTREE,
  CONSTRAINT `fk_convenio_grados_1` FOREIGN KEY (`id_tramo`) REFERENCES `convenio_tramos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio_grados`
--

LOCK TABLES `convenio_grados` WRITE;
/*!40000 ALTER TABLE `convenio_grados` DISABLE KEYS */;
INSERT INTO `convenio_grados` VALUES (1,1,'0',0),(2,1,'1',0),(3,1,'2',0),(4,1,'3',0),(5,2,'4',0),(6,2,'5',0),(7,2,'6',0),(8,2,'7',0),(9,3,'8',0),(10,3,'9',0),(11,3,'10',0),(12,4,'0',0),(13,4,'1',0),(14,4,'2',0),(15,4,'3',0),(16,4,'4',0),(17,4,'5',0),(18,4,'6',0),(19,4,'7',0),(20,4,'8',0),(21,4,'9',0),(22,4,'10',0);
/*!40000 ALTER TABLE `convenio_grados` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `denominacion_funcion`
--

DROP TABLE IF EXISTS `denominacion_funcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `denominacion_funcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `denominacion_puesto`
--

DROP TABLE IF EXISTS `denominacion_puesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `denominacion_puesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `dependencias`
--

DROP TABLE IF EXISTS `dependencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dependencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `codep` varchar(10) DEFAULT NULL,
  `id_padre` int(8) NOT NULL,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `nivel` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`id_padre`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencias`
--

LOCK TABLES `dependencias` WRITE;
/*!40000 ALTER TABLE `dependencias` DISABLE KEYS */;
INSERT INTO `dependencias` VALUES (1,'Unidad Ministro','',0,'2018-07-18',NULL,1),(2,'Secretaria de Planificación de Transporte','',1,'2018-07-18',NULL,2),(3,'Secretaria de Gestión de Transporte','',1,'2018-07-18',NULL,2),(4,'Secretaria de Obras de Transporte','',1,'2018-07-18',NULL,2),(5,'Unidad de Coordinación General','',1,'2018-07-18',NULL,2),(6,'Subsecretaría de Coordinación Administrativa','SSCA',1,'2018-07-18',NULL,3),(7,'Unidad Ejecutora Central(UED)','',1,'2018-07-18',NULL,7),(8,'Instituto Argentino de Transporte (d)','',1,'2018-07-18',NULL,7),(9,'Agencia de Transporte Metropolitano','',1,'2018-07-18',NULL,7),(10,'Comisión de Evaluación, Coordinación y Seguimiento de los Procedimientos de Re d','',1,'2018-07-18',NULL,7),(11,'Subsecretaría de Movilidad Urbana','',2,'2018-07-18',NULL,3),(12,'Subsecretaría de Planificación y Coordinación de Transporte','',2,'2018-07-18',NULL,3),(13,'Dirección Nacional de Planificación de Proyectos Estratégicos de la Región Metro','',2,'2018-07-18',NULL,4),(14,'Dirección Nacional de Regulación Normativa de Transporte','',3,'2018-07-18',NULL,4),(15,'Subsecretaría de Gestión Administrativa de Transporte','',3,'2018-07-18',NULL,3),(16,'Subsecretaría de Transporte Ferroviario','',3,'2018-07-18',NULL,3),(17,'Subsecretaría de Transporte Automotor','',3,'2018-07-18',NULL,3),(18,'Subsecretaría de Puertos, Vías Navegables y Marina Mercante','',3,'2018-07-18',NULL,3),(19,'Dirección de Gestión Técnica de Transporte','',3,'2018-07-18',NULL,5),(20,'Unidad Ejecutora de la Obra de Sorretamiento del Corredor Ferroviario Caballito-Moreno de la línea Sarmiento (d) ','',4,'2018-07-18',NULL,7),(21,'Programa de Apoyo para Obras de Infraestructura de Transporte y de Movilidad Sus','',4,'2018-07-18',NULL,4),(22,'Subsecretaría de Contratación y Ejecución de Obras','',4,'2018-07-18',NULL,3),(23,'Subsecretaría de Supervisión y Control de Obras','',4,'2018-07-18',NULL,3),(24,'Dirección General de Programas y Proyectos Sectoriales y Especiales','',4,'2018-07-18',NULL,4),(25,'Dirección General de Relaciones Institucionales','',5,'2018-07-18',NULL,4),(26,'Coordinación de Ceremonial y Protocolo','',5,'2018-07-18',NULL,6),(27,'Coordinación de Contenidos','',5,'2018-07-18',NULL,6),(28,'Coordinación de Medios','',5,'2018-07-18',NULL,6),(29,'Dirección de Gestión Documental','',6,'2018-07-18',NULL,5),(30,'Dirección de Información al Público','DIP',6,'2018-07-11',NULL,5),(31,'Dirección de Sumarios','',6,'2018-07-18',NULL,5),(32,'Dirección de Enlace Operativo y Control de Gestión','',6,'2018-07-18',NULL,5),(33,'Coordinación de Higiene Laboral y Seguridad en el Trabajo','',6,'2018-07-18',NULL,6),(34,'Dirección General Técnica y Administrativa','',6,'2018-07-18',NULL,4),(35,'Dirección General del Servicio de Administración Financiera','',6,'2018-07-18',NULL,4),(36,'Dirección General de Recursos Humanos','',6,'2018-07-18',NULL,4),(37,'Dirección General de Asuntos Jurídicos','',6,'2018-07-18',NULL,4),(38,'Dirección Nacional de Transporte Urbano Sustentable','',11,'2018-07-18',NULL,4),(39,'Dirección Nacional de Transporte No Motorizado','',11,'2018-07-18',NULL,4),(40,'Dirección Nacional de Transporte Interurbano e Internacional de Pasajeros','',12,'2018-07-18',NULL,4),(41,'Dirección Nacional de Planificación de Transporte de Cargas y Logística','',12,'2018-07-18',NULL,4),(42,'Dirección Nacional de Planificación Estratégica y Coordinación de Transporte','',12,'2018-07-18',NULL,4),(43,'Dirección de Implementación y Seguimiento del Sistema Único Boleto Electrónico (','',15,'2018-07-18',NULL,5),(44,'Dirección Nacional de Gestión de Fondos Fiduciarios','',15,'2018-07-18',NULL,4),(45,'Dirección de Supervisión y Control Financiero de Transporte','',15,'2018-07-18',NULL,5),(46,'Dirección Nacional Técnica de Transporte Ferroviario','',16,'2018-07-18',NULL,4),(47,'Dirección Nacional Gestión del Sistema Ferroviario','',16,'2018-07-18',NULL,4),(48,'Dirección de Gestión Económica de Transporte Automotor','',17,'2018-07-18',NULL,5),(49,'Dirección Nacional de Transporte Automotor de Pasajeros','',17,'2018-07-18',NULL,4),(50,'Dirección Nacional de Transporte Automotor de Cargas','',17,'2018-07-18',NULL,4),(51,'Comisión Nacional de Transito y Seguridad Vial','',17,'2018-07-18',NULL,7),(52,'Dirección de Logística y Operaciones','',18,'2018-07-18',NULL,5),(53,'Dirección Nacional de Política Naviera y Portuaria','',18,'2018-07-18',NULL,4),(54,'Dirección Nacional de Control de Puertos y Vías Navegables','',18,'2018-07-18',NULL,4),(55,'Consejo Federal Portuario','',18,'2018-07-18',NULL,5),(56,'Dirección Nacional de Inspección de Obras','',20,'2018-07-18',NULL,4),(57,'Dirección Nacional de Asesoramiento Legal, Administrativo y Financiero','',20,'2018-07-18',NULL,4),(58,'Dirección Nacional de Articulación Institucional','',20,'2018-07-18',NULL,4),(59,'Dirección Nacional de Contrataciones','',22,'2018-07-18',NULL,4),(60,'Dirección Nacional de Ejecución de Obras','',22,'2018-07-18',NULL,4),(61,'Dirección Nacional de Supervisión','',23,'2018-07-18',NULL,4),(62,'Dirección Nacional de Monitoreo y Evaluación de Obras','',23,'2018-07-18',NULL,4),(63,'Coordinación de Relaciones Internacionales','',25,'2018-07-18',NULL,6),(64,'Coordinación de Asuntos Públicos','',25,'2018-07-18',NULL,6),(65,'Dirección Administrativa de Bienes','',34,'2018-07-18',NULL,5),(66,'Dirección de Contratación de Bienes y Servicios','',34,'2018-07-18',NULL,5),(67,'Dirección de Coop. Técnica y Administrativa de Obras Públicas de Transporte','',34,'2018-07-18',NULL,5),(68,'Dirección de Informática','',34,'2018-07-18',NULL,5),(69,'Dirección de Integración de Sistemas','DIS',34,'2018-07-18',NULL,5),(70,'Dirección de Servicios Generales y Racionalización de Espacios Físicos','',34,'2018-07-18',NULL,5),(71,'Dirección de Contabilidad','',35,'2018-07-18',NULL,5),(72,'Dirección de Presupuesto','',35,'2018-07-18',NULL,5),(73,'Coordinación de Tesorería','',35,'2018-07-18',NULL,6),(74,'Dirección de Administración de Recursos Humanos','',36,'2018-07-18',NULL,5),(75,'Dirección de Desarrollo de Recursos Humanos','',36,'2018-07-18',NULL,5),(76,'Dirección de Control de Gestión y Planificación de Recursos Humanos','',36,'2018-07-18',NULL,5),(77,'Dirección de Asistencia Técnica-Jurídica','',37,'2018-07-18',NULL,5),(78,'Dirección de Dictámenes','',37,'2018-07-18',NULL,5),(79,'Dirección de Asuntos Legales y Judiciales','',37,'2018-07-18',NULL,5),(80,'Dirección de Proyectos de Transporte Público Urbano','',38,'2018-07-18',NULL,5),(81,'Dirección de Proyectos Viales','',38,'2018-07-18',NULL,5),(82,'Dirección de Logística Urbana','',38,'2018-07-18',NULL,5),(83,'Dirección de Proyectos de Transporte no Motorizado','',39,'2018-07-18',NULL,5),(84,'Dirección de Planes de Transporte Interurbano e Internacional de Pasajeros','',40,'2018-07-18',NULL,5),(85,'Dirección de Estudios de Transporte Interurbano e Internacional de Pasajeros','',40,'2018-07-18',NULL,5),(86,'Dirección de Estudios para la Planificación del Transporte de Cargas','',41,'2018-07-18',NULL,5),(87,'Dirección de Proyectos de Transporte de Cargas Interurbano e Internacional','',41,'2018-07-18',NULL,5),(88,'Dirección de Observatorio, Estudios y Sistemas','',42,'2018-07-18',NULL,5),(89,'Dirección de Planificación y Coordinación Territorial','',42,'2018-07-18',NULL,5),(90,'Dirección de Ingeniería Civil','',13,'2018-07-18',NULL,5),(91,'Dirección de Arquitectura','',13,'2018-07-18',NULL,5),(92,'Dirección de Políticas Regulatorias de Transporte','',14,'2018-07-18',NULL,5),(93,'Dirección de Asuntos Contractuales y Finanzas','',14,'2018-07-18',NULL,5),(94,'Dirección de Subsidios al Transporte','',44,'2018-07-18',NULL,5),(95,'Dirección de Fondos Fiduciarios','',44,'2018-07-18',NULL,5),(96,'Dir. de Asuntos Administrativos de Transporte Ferroviario','',46,'2018-07-18',NULL,5),(97,'Dir. de Coordinación de Mejoras del Sistema de Transporte Ferroviario','',46,'2018-07-18',NULL,5),(98,'Dir. de Coordinación con Empresas Ferroviarias','',47,'2018-07-18',NULL,5),(99,'Dir. de Gestión de Permisos de Transporte Automotor de Pasajeros','',49,'2018-07-18',NULL,5),(100,'Dir. de Evaluación de Políticas de Transporte Automotor de Pasajeros','',49,'2018-07-18',NULL,5),(101,'Dir. de Gestión de Permisos de Transporte Automotor de Cargas','',50,'2018-07-18',NULL,5),(102,'Dir. de Evaluación de Políticas de Transporte Automotor de Cargas','',50,'2018-07-18',NULL,5),(103,'Dpto. de Distritos y Capitanía de Armamento','',52,'2018-07-18',NULL,6),(104,'Dirección de Planificación y Control','',53,'2018-07-18',NULL,5),(105,'Dirección de Estudios y Estadísticas','',53,'2018-07-18',NULL,5),(106,'Dirección de Control Técnico y Concesiones','',54,'2018-07-18',NULL,5),(107,'Dirección de Control Legal, Contable, Patrimonial de Concesiones','',54,'2018-07-18',NULL,5),(108,'Dirección de Control Documental y Habilitaciones','',54,'2018-07-18',NULL,5),(109,'Dirección de Computo y Presupuesto de Obras','',59,'2018-07-18',NULL,5),(110,'Dirección Técnico Legal de Obras','',59,'2018-07-18',NULL,5),(111,'Dirección de Administración de Obras','',60,'2018-07-18',NULL,5),(112,'Dirección de Seguimiento de Obras','',60,'2018-07-18',NULL,5),(113,'Dirección de Supervisión de Obras','',61,'2018-07-18',NULL,5),(114,'Dirección de Supervisión de Proyectos','',61,'2018-07-18',NULL,5),(115,'Dirección de Monitoreo de Obras y Tablero de Control','',62,'2018-07-18',NULL,5),(116,'Dirección de Evaluación de Gestión de Obras','',62,'2018-07-18',NULL,5),(117,'Dirección de Gestión y Monitoreo de Programas y Proyectos Sectoriales y Especial','',24,'2018-07-18',NULL,5),(118,'Dirección de Administración Financiera y Presupuestaria','',24,'2018-07-18',NULL,5),(119,'METROBUS','',4,'2018-10-12',NULL,4),(120,'Unidad de Auditoría Interna',NULL,1,'2019-01-10',NULL,4),(121,'Auditoría Interna Adjunta',NULL,120,'2019-01-10',NULL,5),(122,'Modernización de la Red de Colectivos del área Metropolitana de Bs. As. (AMBA)',NULL,5,'2019-01-10',NULL,3),(123,'Red de Expresos Regionales de la Región Metropolitana de Buenos Aires (RER)',NULL,4,'2019-01-10',NULL,4);
/*!40000 ALTER TABLE `dependencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_documento` tinyint(1) DEFAULT NULL,
  `documento` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(64) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `genero` tinyint(1) DEFAULT NULL,
  `nacionalidad` varchar(4) DEFAULT NULL,
  `estado_civil` tinyint(1) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `documento` (`documento`) USING BTREE,
  KEY `personas_borrado_IDX` (`borrado`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `cuit` decimal(11,0) NOT NULL,
  `email` varchar(60) NOT NULL,
  `planilla_reloj` tinyint(1) DEFAULT '1',
  `en_comision` tinyint(1) DEFAULT '0',
  `credencial` tinyint(1) DEFAULT '0',
  `fecha_vencimiento` date DEFAULT NULL,
  `antiguedad_adm_publica` varchar(45) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_motivo` int(11) DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `empleados_borrado_IDX` (`borrado`) USING BTREE,
  KEY `empleados_persona_id_IDX` (`id_persona`) USING BTREE,
  CONSTRAINT `fk_empleados_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_comision`
--

DROP TABLE IF EXISTS `empleado_comision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado_comision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_comision_origen` int(11) NOT NULL,
  `id_comision_destino` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empleado_comision_id_comision_origen_IDX` (`id_comision_origen`),
  KEY `empleado_comision_id_comision_destino_IDX` (`id_comision_destino`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleado_dependencia`
--

DROP TABLE IF EXISTS `empleado_dependencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado_dependencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_dependencia` int(11) NOT NULL,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `empleado_index` (`id_empleado`),
  KEY `dep_prin_index` (`id_dependencia`),
  KEY `fechdes_index` (`fecha_desde`),
  KEY `fech_has_index` (`fecha_hasta`),
  CONSTRAINT `fk_empleado_dependencia_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_dependencia_2` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_escalafon`
--

DROP TABLE IF EXISTS `empleado_escalafon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado_escalafon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_modalidad_vinculacion` int(11) DEFAULT NULL,
  `id_situacion_revista` int(11) DEFAULT NULL,
  `id_nivel` int(11) DEFAULT NULL,
  `id_grado` int(11) DEFAULT NULL,
  `id_tramo` int(11) DEFAULT NULL,
  `id_agrupamiento` int(11) DEFAULT NULL,
  `id_funcion_ejecutiva` int(11) DEFAULT NULL,
  `compensacion_geografica` tinyint(1) DEFAULT '0',
  `compensacion_transitoria` tinyint(1) DEFAULT '0',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_escalafon_1_idx` (`id_modalidad_vinculacion`),
  KEY `fk_empleado_escalafon_2_idx` (`id_situacion_revista`),
  KEY `fk_empleado_escalafon_3_idx` (`id_nivel`),
  KEY `fk_empleado_escalafon_4_idx` (`id_grado`),
  KEY `fk_empleado_escalafon_5_idx` (`id_tramo`),
  KEY `fk_empleado_escalafon_6_idx` (`id_agrupamiento`),
  KEY `fk_empleado_escalafon_7_idx` (`id_funcion_ejecutiva`),
  KEY `fk_empleado_escalafon_8_idx` (`id_empleado`),
  CONSTRAINT `fk_empleado_escalafon_1` FOREIGN KEY (`id_modalidad_vinculacion`) REFERENCES `convenio_modalidad_vinculacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_escalafon_2` FOREIGN KEY (`id_situacion_revista`) REFERENCES `convenio_situacion_revista` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_escalafon_3` FOREIGN KEY (`id_nivel`) REFERENCES `convenio_niveles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_escalafon_4` FOREIGN KEY (`id_grado`) REFERENCES `convenio_grados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_escalafon_5` FOREIGN KEY (`id_tramo`) REFERENCES `convenio_tramos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_escalafon_6` FOREIGN KEY (`id_agrupamiento`) REFERENCES `convenio_agrupamientos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_escalafon_7` FOREIGN KEY (`id_funcion_ejecutiva`) REFERENCES `convenio_funciones_ejecutivas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_escalafon_8` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleado_horarios`
--

DROP TABLE IF EXISTS `empleado_horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado_horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `horarios` varchar(250) CHARACTER SET armscii8 NOT NULL,
  `id_turno` tinyint(1) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `borrado` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_empleado_UNIQUE` (`id_empleado`),
  CONSTRAINT `fk_empleado_horarios_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `empleado_perfil`
--

DROP TABLE IF EXISTS `empleado_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado_perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `denominacion_funcion` int(11) DEFAULT NULL,
  `denominacion_puesto` int(11) DEFAULT NULL,
  `objetivo_gral` text,
  `objetivo_especifico` varchar(45) DEFAULT NULL,
  `estandares` varchar(45) DEFAULT NULL,
  `fecha_obtencion_result` date DEFAULT NULL,
  `familia_de_puestos` int(11) DEFAULT NULL,
  `nivel_destreza` tinyint(4) DEFAULT NULL,
  `nombre_puesto` int(11) DEFAULT NULL,
  `puesto_supervisa` tinyint(4) DEFAULT NULL,
  `nivel_complejidad` tinyint(4) DEFAULT NULL,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_perfil_1_idx` (`id_empleado`),
  CONSTRAINT `fk_perfil_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `licencias_especiales`
--

DROP TABLE IF EXISTS `licencias_especiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licencias_especiales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleados_lic_especiales`
--

DROP TABLE IF EXISTS `empleados_lic_especiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleados_lic_especiales` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_licencia` int(11) NOT NULL,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_empleados_licencias_1_idx` (`id_empleado`),
  KEY `fk_empleados_licencias_2_idx` (`id_licencia`),
  CONSTRAINT `fk_empleados_licencias_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleados_licencias_2` FOREIGN KEY (`id_licencia`) REFERENCES `licencias_especiales` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ubicaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_edificio` int(11) NOT NULL,
  `id_organismo` int(8) DEFAULT '1',
  `piso` varchar(8) DEFAULT NULL,
  `oficina` varchar(10) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_edificio` (`id_edificio`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicaciones`
--

LOCK TABLES `ubicaciones` WRITE;
/*!40000 ALTER TABLE `ubicaciones` DISABLE KEYS */;
INSERT INTO `ubicaciones` VALUES (1,1,1,'0','1',0),(2,2,1,'0','1',0),(3,2,1,'0','0',0),(4,3,1,'12','1',0),(5,3,1,'15','1',0),(6,3,1,'16','1',0),(7,4,1,'0','1',0),(8,5,1,'0','1',0),(9,6,1,'0','1',0),(10,7,1,'0','1',0),(11,8,1,'0','1',0),(12,9,1,'0','1',0),(13,10,1,'0','1',0),(14,11,1,'0','1',0),(15,12,1,'0','1',0),(16,13,1,'3','1',0);
/*!40000 ALTER TABLE `ubicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados_x_ubicacion`
--

DROP TABLE IF EXISTS `empleados_x_ubicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleados_x_ubicacion` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_ubicacion` int(11) NOT NULL,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empleados_x_ubicacion_id_empleado_IDX` (`id_empleado`) USING BTREE,
  KEY `fk_empleados_x_ubicacion_1_idx` (`id_ubicacion`),
  CONSTRAINT `fk_empleados_x_ubicacion_1` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleados_x_ubicacion_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `familia_de_puestos`
--

DROP TABLE IF EXISTS `familia_de_puestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familia_de_puestos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;





--
-- Table structure for table `lote`
--

DROP TABLE IF EXISTS `lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_lote` int(11) NOT NULL,
  `id_tipo_contrato` int(11) NOT NULL,
  `id_dependencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('sin publicar','nuevo','en curso','aprobado','rechazado') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lote_1_idx` (`id_dependencia`),
  KEY `fk_lote_2_idx` (`id_empleado`),
  CONSTRAINT `fk_lote_1` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lote_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `lote_cuit`
--

DROP TABLE IF EXISTS `lote_cuit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lote_cuit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) NOT NULL,
  `cuit` decimal(11,0) NOT NULL,
  `fecha_inicio_contrato` date NOT NULL,
  `fecha_fin_contrato` date NOT NULL,
  `aprobacion_administracion` enum('SIN REVISION','APROBADO','RECHAZADO') NOT NULL DEFAULT 'SIN REVISION',
  `aprobacion_desarrollo` enum('SIN REVISION','APROBADO','RECHAZADO') NOT NULL DEFAULT 'SIN REVISION',
  `aprobacion_control` enum('SIN REVISION','APROBADO','RECHAZADO') NOT NULL DEFAULT 'SIN REVISION',
  `aprobacion_liquidacion` enum('SIN REVISION','APROBACION','RECHAZADO') NOT NULL DEFAULT 'SIN REVISION',
  `comentario` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lote_cuit_1_idx` (`id_lote`),
  CONSTRAINT `fk_lote_cuit_1` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `motivo_baja`
--

DROP TABLE IF EXISTS `motivo_baja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `motivo_baja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfil_actividades`
--

DROP TABLE IF EXISTS `perfil_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil_actividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `perfil_resultado_parc_final`
--

DROP TABLE IF EXISTS `perfil_resultado_parc_final`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil_resultado_parc_final` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `perfil_tarea`
--

DROP TABLE IF EXISTS `perfil_tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil_tarea` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `tipo_discapacidad`
--

DROP TABLE IF EXISTS `tipo_discapacidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_discapacidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_discapacidad`
--

LOCK TABLES `tipo_discapacidad` WRITE;
/*!40000 ALTER TABLE `tipo_discapacidad` DISABLE KEYS */;
INSERT INTO `tipo_discapacidad` VALUES (1,'enfermito','Hecho polvo',0);
/*!40000 ALTER TABLE `tipo_discapacidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona_discapacidad`
--

DROP TABLE IF EXISTS `persona_discapacidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona_discapacidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) DEFAULT NULL,
  `id_tipo_discapacidad` int(11) NOT NULL,
  `cud` varchar(45) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `observaciones` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_persona_dis_1_idx` (`id_persona`),
  KEY `fk_persona_discapacidad_1_idx` (`id_tipo_discapacidad`),
  CONSTRAINT `fk_persona_discapacidad_1` FOREIGN KEY (`id_tipo_discapacidad`) REFERENCES `tipo_discapacidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_persona_dis_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `persona_domicilio`
--

DROP TABLE IF EXISTS `persona_domicilio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona_domicilio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `calle` varchar(50) NOT NULL,
  `numero` varchar(6) NOT NULL,
  `piso` varchar(8) DEFAULT NULL,
  `depto` varchar(4) DEFAULT NULL,
  `cod_postal` varchar(8) DEFAULT NULL,
  `id_provincia` int(11) NOT NULL,
  `id_localidad` int(11) NOT NULL,
  `fecha_alta` date NOT NULL,
  `fecha_baja` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_persona_domicilio_1_idx` (`id_persona`),
  CONSTRAINT `fk_persona_domicilio_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `persona_otros_conocimientos`
--

DROP TABLE IF EXISTS `persona_otros_conocimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona_otros_conocimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_tipo` tinyint(2) DEFAULT '1',
  `fecha` date DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL,
  `borrado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_otros_conocimientos_1_idx` (`id_persona`),
  CONSTRAINT `fk_otros_conocimientos_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona_telefono`
--

DROP TABLE IF EXISTS `persona_telefono`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona_telefono` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_tipo_telefono` tinyint(4) NOT NULL,
  `telefono` int(11) NOT NULL,
  `fecha_alta` date NOT NULL,
  `fecha_baja` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_persona` (`id_persona`),
  KEY `id_persona_2` (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `persona_titulo`
--

DROP TABLE IF EXISTS `persona_titulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona_titulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_persona` int(11) NOT NULL,
  `id_tipo_titulo` tinyint(3) NOT NULL,
  `id_estado_titulo` tinyint(1) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `principal` tinyint(4) DEFAULT '0',
  `borrado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_titulos_1_idx` (`id_persona`),
  CONSTRAINT `fk_titulos_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;




--
-- Table structure for table `plantilla_horarios`
--

DROP TABLE IF EXISTS `plantilla_horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_horarios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `horario` varchar(250) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_horarios`
--

LOCK TABLES `plantilla_horarios` WRITE;
/*!40000 ALTER TABLE `plantilla_horarios` DISABLE KEYS */;
INSERT INTO `plantilla_horarios` VALUES (1,'GENERICO','[[],[\"09:00\",\"18:00\"],[\"09:00\",\"18:00\"],[\"09:00\",\"18:00\"],[\"09:00\",\"18:00\"],[\"09:00\",\"18:00\"],[]]',0),(2,'LEY MARCO','[[],[\"10:00\",\"17:00\"],[\"10:00\",\"17:00\"],[\"10:00\",\"17:00\"],[\"10:00\",\"17:00\"],[\"10:00\",\"17:00\"],[]]',0);
/*!40000 ALTER TABLE `plantilla_horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_actividades`
--

DROP TABLE IF EXISTS `presupuesto_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_actividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_actividades`
--

LOCK TABLES `presupuesto_actividades` WRITE;
/*!40000 ALTER TABLE `presupuesto_actividades` DISABLE KEYS */;
INSERT INTO `presupuesto_actividades` VALUES (1,1,'Conducción Superior',0),(2,2,'Apoyo al Sistema Único de Boleto electrónico',0),(3,3,'Planificación de Transporte',0),(4,4,'Acciones de Seguridad Vial',0),(5,5,'Administración de Obras del Transporte',0),(6,6,'Apoyo al desarrollo de la Poítica de la Industria Naval',0),(7,8,'Hidrovía Paraguay - Paraná',0),(8,9,'Analisis de las Políticas de Transporte automotor',0),(9,10,'Transferencias para la Ejecución del Dragado y Balizamiento',0),(10,11,'Supervisión, Gestión y Control de Políticas Portuarias y Navieras',0),(11,12,'Formulación de Políticas Portuarias y Naviera y Control de Transporte Fluvial',0),(12,13,'Logística y Operaciones',0),(13,15,'Fortalecimiento Instititucional CAF N3192',0),(14,18,'Implementación del Régimen de Compensación del Transporte Automotor',0),(15,42,'Apoyo a la Infraestructura del Sistema Único de Boleto Electrónico',0),(16,50,'Apoyo para la Construcción de Pasos Bajo Nivel en Municipios',0),(17,55,'Rehabilitación Integral de Trenes de Pasajeros AMBA',0),(18,56,'Rehabilitación Integral de Trenes de Pasajeros AMBA - Frenado Automático de Trenes (JBIC S/N)',0),(19,58,'Adquisición de material rodante Línea Roca',0),(20,64,'Investigación de las Políticas de Transporte',0),(21,1,'Consultorías y Estudios del Proyecto Red de Expresos Regionales',0),(22,1,'Administración del Proyecto - Ferrocarril San Martín',0),(23,1,'Mejoramiento para Infraestructura Transporte de Carga',0),(24,1,'Gestión de Transporte',0),(25,1,'Conducción y Coordinación',0),(26,2,'Ejecución y Control del Dragado y Balizamiento',0),(27,5,'Soterramiento Ferrocarril Sarmiento',0),(28,1,'Conducción de la Subsecretaría de Gestión Administrativa del Transporte',0),(29,1,'Conducción y Administración',0),(30,13,'Implementación de la Tarifa Social en el Transporte Automotor de Pasajeros',0),(31,2,'Apoyo al Transporte Ferroviario de Pasajeros',0),(32,3,'Acciones para Optimización del Sistema de Transporte Ferroviario',0),(33,3,'Fortalecimiento Institucional para Atributo Social',0),(34,0,'Conducción de la Subsecretaría de Gestión Administrativa del Transporte',0);
/*!40000 ALTER TABLE `presupuesto_actividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_jurisdicciones`
--

DROP TABLE IF EXISTS `presupuesto_jurisdicciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_jurisdicciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_jurisdicciones`
--

LOCK TABLES `presupuesto_jurisdicciones` WRITE;
/*!40000 ALTER TABLE `presupuesto_jurisdicciones` DISABLE KEYS */;
INSERT INTO `presupuesto_jurisdicciones` VALUES (1,57,'Ministerio de Transporte',0);
/*!40000 ALTER TABLE `presupuesto_jurisdicciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_programas`
--

DROP TABLE IF EXISTS `presupuesto_programas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_programas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_programas`
--

LOCK TABLES `presupuesto_programas` WRITE;
/*!40000 ALTER TABLE `presupuesto_programas` DISABLE KEYS */;
INSERT INTO `presupuesto_programas` VALUES (1,1,'Actividades Centrales',0),(2,2,'Actividades Comunes a los Programas 61 y 62',0),(3,4,'Actividades Comunes a los Programas 61, 62 y 91',0),(4,61,'Coordinación de Políticas de Transporte Vial',0),(5,62,'Modernización de la Red de Transporte Ferroviario',0),(6,66,'Infraestructura de Obras de Transporte',0),(7,91,'Coordinación de Políticas de Transporte Fluvial y Marítimo',0);
/*!40000 ALTER TABLE `presupuesto_programas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_subprogramas`
--

DROP TABLE IF EXISTS `presupuesto_subprogramas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_subprogramas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_programa` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_presupuesto_subprogramas_1_idx` (`id_programa`),
  CONSTRAINT `fk_presupuesto_subprogramas_1` FOREIGN KEY (`id_programa`) REFERENCES `presupuesto_programas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_subprogramas`
--

LOCK TABLES `presupuesto_subprogramas` WRITE;
/*!40000 ALTER TABLE `presupuesto_subprogramas` DISABLE KEYS */;
INSERT INTO `presupuesto_subprogramas` VALUES (1,6,1,'Infraestructura de Transporte',0),(2,6,2,'Infraestructura de Ferroviaria de Cargas',0),(3,6,3,'Infraestructura de Transporte Aéreo',0);
/*!40000 ALTER TABLE `presupuesto_subprogramas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_proyectos`
--

DROP TABLE IF EXISTS `presupuesto_proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_proyectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_programa` int(11) NOT NULL,
  `id_subprograma` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_presupuesto_proyectos_1_idx` (`id_programa`),
  KEY `fk_presupuesto_proyectos_2_idx` (`id_subprograma`),
  CONSTRAINT `fk_presupuesto_proyectos_1` FOREIGN KEY (`id_programa`) REFERENCES `presupuesto_programas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_presupuesto_proyectos_2` FOREIGN KEY (`id_subprograma`) REFERENCES `presupuesto_subprogramas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_proyectos`
--

LOCK TABLES `presupuesto_proyectos` WRITE;
/*!40000 ALTER TABLE `presupuesto_proyectos` DISABLE KEYS */;
INSERT INTO `presupuesto_proyectos` VALUES (1,6,1,1,'Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos (BIRF Nº 8700 / BIRF Nº 8894)',0),(2,6,1,2,'Mejora del Transporte en Áereas Metropolitanas',0),(3,6,1,4,'Renovación Integral del Ramal M FFCC Belgrano Sur - Tramo Tapiales - Marinos del Crucero General Belgrano (CAF S/N)',0),(4,6,1,5,'Soterramiento Ferrocarril Sarmiento',0),(5,6,1,8,'Infraestructura Transporte Masivo (Pragrama Nacional de Transporte Masivo) - CAF S/Nº)',0),(6,6,1,18,'Red de Expresos Regionales (R.E.R)',0),(7,6,1,20,'Administración del Proyecto - Ferrocarril San Martín',0),(8,6,1,24,'Mejora del Transporte en Áreas Metropolitanas II',0),(9,6,3,1,'Construcción de Torre de Control Aéreo, Edificio de Centro de Control de Área, Accesos y Estacionamiento - Aeropuerto Internacional Ministro Pistarini - Ezeiza - Provincia de Buenos Aires',0);
/*!40000 ALTER TABLE `presupuesto_proyectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_obras`
--

DROP TABLE IF EXISTS `presupuesto_obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_obras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_presupuesto_obras_1_idx` (`id_proyecto`),
  CONSTRAINT `fk_presupuesto_obras_1` FOREIGN KEY (`id_proyecto`) REFERENCES `presupuesto_proyectos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_obras`
--

LOCK TABLES `presupuesto_obras` WRITE;
/*!40000 ALTER TABLE `presupuesto_obras` DISABLE KEYS */;
INSERT INTO `presupuesto_obras` VALUES (1,1,51,'Obra de Infraestructura Centro de Transbordo Estación Sáenz (BIRF N° 8700 / BIRF N°8894)',0),(2,3,51,'Renovación Integral del Ramal M FFCC Belgrano Sur - Tramo Tapiales - Marinos del Crucero General Belgrano (CAF S/N)',0),(3,4,51,'Soterramiento Ferrocarril Sarmiento',0),(4,5,51,'Infraestructura Transporte Masivo (Programa Nacional de Transporte Masivo) - CAF S/N°',0),(5,8,51,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos - VAP II',0),(6,9,51,'Construcción de Torre de Control Aéreo, Edificio de Centro de Control de Área, Accesos y Estacionamiento - Aeropuerto Internacional Ministro Pistarini - Ezeiza - Provincia de Buenos Aires',0),(7,2,52,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos - Metrobus Morón - Provincia de Buenos Aires',0),(8,8,52,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos - Refugios y Mobiliarios Urbano II',0),(9,8,53,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos - VAP Interior',0),(10,2,54,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos  Metrobus Mar del Plata',0),(11,2,54,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos - Metrobus Neuquén - Provincia de Neuquén',0),(12,8,54,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos - Metrobus AMBA',0),(13,8,55,'Desarrollo del Sistema de Ómnibus de Tránsito Rápido y Carriles Exclusivos - Metrobus Interior',0);
/*!40000 ALTER TABLE `presupuesto_obras` ENABLE KEYS */;
UNLOCK TABLES;



--
-- Table structure for table `presupuesto_saf`
--

DROP TABLE IF EXISTS `presupuesto_saf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_saf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_saf`
--

LOCK TABLES `presupuesto_saf` WRITE;
/*!40000 ALTER TABLE `presupuesto_saf` DISABLE KEYS */;
INSERT INTO `presupuesto_saf` VALUES (1,327,'Ministerio de Transporte (Gastos Propios)',0);
/*!40000 ALTER TABLE `presupuesto_saf` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `presupuesto_ubicaciones_geograficas`
--

DROP TABLE IF EXISTS `presupuesto_ubicaciones_geograficas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuesto_ubicaciones_geograficas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_ubicaciones_geograficas`
--

LOCK TABLES `presupuesto_ubicaciones_geograficas` WRITE;
/*!40000 ALTER TABLE `presupuesto_ubicaciones_geograficas` DISABLE KEYS */;
INSERT INTO `presupuesto_ubicaciones_geograficas` VALUES (1,2,'Ciudad Autónoma de Buenos Aires',0),(2,6,'Provincia de Buenos Aires',0),(3,18,'Provincia de Corrientes',0),(4,30,'Provincia de Entre Ríos',0),(5,58,'Provincia de Neuquén',0),(6,82,'Provincia de Santa Fe',0),(7,96,'Interprovincial',0),(8,97,'Nacional',0);
/*!40000 ALTER TABLE `presupuesto_ubicaciones_geograficas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuestos`
--

DROP TABLE IF EXISTS `presupuestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuestos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_saf` int(11) DEFAULT NULL,
  `id_jurisdiccion` int(11) DEFAULT NULL,
  `id_ubicacion_geografica` int(11) DEFAULT NULL,
  `id_programa` int(11) DEFAULT NULL,
  `id_subprograma` int(11) DEFAULT NULL,
  `id_proyecto` int(11) DEFAULT NULL,
  `id_actividad` int(11) DEFAULT NULL,
  `id_obra` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_presupuestos_2_idx` (`id_programa`),
  KEY `fk_presupuestos_3_idx` (`id_actividad`),
  KEY `fk_presupuestos_4_idx` (`id_subprograma`),
  KEY `fk_presupuestos_5_idx` (`id_proyecto`),
  KEY `fk_presupuestos_6_idx` (`id_saf`),
  KEY `fk_presupuestos_7_idx` (`id_jurisdiccion`),
  KEY `fk_presupuestos_8_idx` (`id_obra`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuestos`
--

LOCK TABLES `presupuestos` WRITE;
/*!40000 ALTER TABLE `presupuestos` DISABLE KEYS */;
INSERT INTO `presupuestos` VALUES (4,1,1,1,1,NULL,NULL,1,NULL),(5,1,1,1,1,NULL,NULL,3,NULL),(6,1,1,1,1,NULL,NULL,20,NULL),(16,1,1,1,2,NULL,NULL,28,NULL),(17,1,1,1,2,NULL,NULL,2,NULL),(18,1,1,1,2,NULL,NULL,15,NULL),(19,1,1,1,3,NULL,NULL,24,NULL),(20,1,1,1,4,NULL,NULL,29,NULL),(21,1,1,1,4,NULL,NULL,4,NULL),(22,1,1,1,4,NULL,NULL,8,NULL),(23,1,1,1,4,NULL,NULL,30,NULL),(24,1,1,1,4,NULL,NULL,14,NULL),(25,1,1,1,5,NULL,NULL,29,NULL),(26,1,1,1,5,NULL,NULL,31,NULL),(27,1,1,1,5,NULL,NULL,32,NULL),(28,1,1,7,5,NULL,NULL,17,NULL),(29,1,1,7,5,NULL,NULL,18,NULL),(30,1,1,7,5,NULL,NULL,19,NULL),(31,1,1,1,6,NULL,NULL,33,NULL),(32,1,1,7,6,NULL,NULL,33,NULL),(33,1,1,1,6,NULL,NULL,5,NULL),(34,1,1,2,6,NULL,NULL,5,NULL),(35,1,1,7,6,NULL,NULL,5,NULL),(36,1,1,1,6,NULL,NULL,13,NULL),(37,1,1,6,6,NULL,NULL,13,NULL),(38,1,1,7,6,NULL,NULL,16,NULL),(39,1,1,1,6,1,1,NULL,1),(40,1,1,2,6,1,2,NULL,7),(41,1,1,2,6,1,2,NULL,10),(42,1,1,5,6,1,2,NULL,11),(43,1,1,2,6,1,3,NULL,2),(44,1,1,7,6,1,4,NULL,3),(45,1,1,7,6,1,5,NULL,4),(46,1,1,1,6,1,6,21,0),(47,1,1,7,6,1,7,22,NULL),(48,1,1,7,6,1,8,NULL,5),(49,1,1,2,6,1,8,NULL,8),(50,1,1,7,6,1,8,NULL,9),(51,1,1,2,6,1,8,NULL,12),(52,1,1,7,6,1,8,NULL,12),(53,1,1,8,6,1,8,NULL,13),(54,1,1,7,6,2,NULL,23,NULL),(55,1,1,2,6,3,9,NULL,6),(56,1,1,1,7,NULL,NULL,25,NULL),(57,1,1,2,7,NULL,NULL,25,NULL),(58,1,1,3,7,NULL,NULL,25,NULL),(59,1,1,4,7,NULL,NULL,25,NULL),(60,1,1,6,7,NULL,NULL,25,NULL),(61,1,1,2,7,NULL,NULL,26,NULL),(62,1,1,6,7,NULL,NULL,26,NULL),(63,1,1,2,7,NULL,NULL,6,NULL),(64,1,1,2,7,NULL,NULL,7,NULL),(65,1,1,1,7,NULL,NULL,9,NULL),(66,1,1,1,7,NULL,NULL,10,NULL),(67,1,1,1,7,NULL,NULL,11,NULL),(68,1,1,2,7,NULL,NULL,11,NULL),(69,1,1,1,7,NULL,NULL,12,NULL),(71,1,1,1,6,NULL,NULL,27,NULL);
/*!40000 ALTER TABLE `presupuestos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado_presupuesto`
--

DROP TABLE IF EXISTS `empleado_presupuesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado_presupuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_presupuesto` int(11) NOT NULL,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_empleado_presupuesto_1_idx` (`id_presupuesto`),
  KEY `fk_empleado_presupuesto_2_idx` (`id_empleado`),
  CONSTRAINT `fk_empleado_presupuesto_1` FOREIGN KEY (`id_presupuesto`) REFERENCES `presupuestos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_presupuesto_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `puestos`
--

DROP TABLE IF EXISTS `puestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `puestos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `contrato`
--

DROP TABLE IF EXISTS `contrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) DEFAULT NULL,
  `id_presupuesto` int(11) DEFAULT NULL,
  `id_contratante` int(11) DEFAULT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_tipo_contrato` int(11) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `honorarios` varchar(8) DEFAULT NULL,
  `id_forma_pago` tinyint(8) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `id_titulo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empleado_contrato_1_idx` (`id_empleado`),
  KEY `fk_empleado_contrato_2_idx` (`id_contratante`),
  KEY `fk_empleado_contrato_3_idx` (`id_presupuesto`),
  KEY `fk_empleado_contrato_4_idx` (`id_lote`),
  KEY `fk_empleado_contrato_5_idx` (`id_titulo`),
  KEY `index7` (`fecha_fin`),
  CONSTRAINT `fk_empleado_contrato_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_contrato_2` FOREIGN KEY (`id_contratante`) REFERENCES `contratante` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_contrato_3` FOREIGN KEY (`id_presupuesto`) REFERENCES `presupuestos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_contrato_4` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_empleado_contrato_5` FOREIGN KEY (`id_titulo`) REFERENCES `persona_titulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `anticorrupcion`
--

DROP TABLE IF EXISTS `anticorrupcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anticorrupcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `fecha_designacion` date DEFAULT NULL,
  `fecha_publicacion_designacion` date DEFAULT NULL,
  `fecha_aceptacion_renuncia` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`id_empleado`),
  CONSTRAINT `fk_anticorrupcion_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `anticorrupcion_presentacion`
--

DROP TABLE IF EXISTS `anticorrupcion_presentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anticorrupcion_presentacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_anticorrupcion` int(11) NOT NULL,
  `tipo_presentacion` tinyint(1) NOT NULL,
  `fecha_presentacion` date DEFAULT NULL,
  `periodo` varchar(4) DEFAULT NULL,
  `nro_transaccion` varchar(20) DEFAULT NULL,
  `nombre_archivo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_anticorrupcion_presentacion_1` (`id_anticorrupcion`),
  CONSTRAINT `fk_anticorrupcion_presentacion_1` FOREIGN KEY (`id_anticorrupcion`) REFERENCES `anticorrupcion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `comisiones`
--

DROP TABLE IF EXISTS `comisiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comisiones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `borrado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comisiones`
--

LOCK TABLES `comisiones` WRITE;
/*!40000 ALTER TABLE `comisiones` DISABLE KEYS */;
INSERT INTO `comisiones` VALUES (1,'MINISTERIO DE TRANSPORTE',0),(2,'HONORABLE CAMARA DEL SENADO DE LA NACIÓN (HCS)',0),(3,'HONORABLE CAMARA DE DIPUTADOS DE LA NACIÓN (HCD)',0),(4,'COMISIÓN NACIONAL DE REGULACIÓN DEL TRANSPORTE (CNRT)',0),(5,'INSTITUTO NACIONAL DE TECNOLOGÍA AGROPECUARIA (INTA)',0);
/*!40000 ALTER TABLE `comisiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado_documentos`
--

DROP TABLE IF EXISTS `empleado_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_bloque` tinyint(1) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_archivo` varchar(50) NOT NULL,
  `fecha_reg` date NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_documentos_empleados_1_idx` (`id_empleado`),
  CONSTRAINT `fk_documentos_empleados_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `observaciones`
--

DROP TABLE IF EXISTS `observaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_bloque` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_observaciones_1_idx` (`id_empleado`),
  CONSTRAINT `fk_observaciones_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ubicacion_edificios`
--

DROP TABLE IF EXISTS `ubicacion_edificios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ubicacion_edificios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `calle` varchar(255) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `id_localidad` int(11) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  `cod_postal` varchar(8) DEFAULT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_localidad` (`id_localidad`,`id_provincia`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicacion_edificios`
--

LOCK TABLES `ubicacion_edificios` WRITE;
/*!40000 ALTER TABLE `ubicacion_edificios` DISABLE KEYS */;
INSERT INTO `ubicacion_edificios` VALUES (1,'Hacienda','Hipólito Yrigoyen',250,NULL,NULL,NULL,0),(2,'PC 315','Paseo Colón',315,NULL,NULL,NULL,0),(3,'Maipu 255','Maipu',255,NULL,NULL,NULL,0),(4,'Esmeralda','Esmeralda',117,NULL,NULL,NULL,0),(5,'Av España 2221 CABA','Av España',2221,NULL,NULL,NULL,0),(6,'Almte.Brown y Juan De Garay Quequen - BS.AS','Almte.Brown y Juan De Garay Quequen',1,NULL,NULL,NULL,0),(7,'Av Belgrano y 27 de Febrero Rosario - Santa Fe','Av Belgrano y 27 de Febrero',1,NULL,NULL,NULL,0),(8,'Av San Martin 1301 - Corrientes','Av San Martin',1301,NULL,NULL,NULL,0),(9,'Jordana 750 Concepcion del Uruguay - Entre Rios','Jordana',750,NULL,NULL,NULL,0),(10,'Lavaisse 1600 - CABA','Lavaisse',1600,NULL,NULL,NULL,0),(11,'Liniers 395 Puerto Nuevo Parana - Entre Rios','Liniers',395,NULL,NULL,NULL,0),(12,'Puerto Ingeniero White - Bahia Blanca','Puerto Ingeniero White',1,NULL,NULL,NULL,0),(13,'Moreno','Moreno',653,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `ubicacion_edificios` ENABLE KEYS */;
UNLOCK TABLES;


CREATE TABLE `db_version` (
  `version` MEDIUMINT(5) UNSIGNED NOT NULL,
  `fecha` DATETIME NOT NULL,
  PRIMARY KEY (`version`));

INSERT INTO db_version VALUES('1.0', now());



/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-01 13:06:47
