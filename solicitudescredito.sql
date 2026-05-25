/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.16-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: solicitudescredito
-- ------------------------------------------------------
-- Server version	10.11.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `cliente_id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_identificacion` int(11) NOT NULL,
  `nombre_completo` varchar(250) NOT NULL COMMENT 'Nombres y apellidos completos de quien solicita el credito',
  `telefono` varchar(30) NOT NULL COMMENT 'Número telefónico de contacto',
  PRIMARY KEY (`cliente_id`),
  UNIQUE KEY `nro_identificacion` (`nro_identificacion`),
  UNIQUE KEY `index_nro_identificacion` (`nro_identificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla en donde se alojan el listado de clientes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES
(1,203026718,'Jhon doe','3226534487'),
(3,29114652,'Alex Campos','53565444'),
(4,3203032,'Kenny Doe','3222334487'),
(5,67203032,'Martha Wayne','3222334487'),
(6,67930032,'Isabel C','3222334487');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla de estado para referenciar con la tabla solicitudes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados`
--

LOCK TABLES `estados` WRITE;
/*!40000 ALTER TABLE `estados` DISABLE KEYS */;
INSERT INTO `estados` VALUES
(1,'Creada','','2026-05-24 00:02:40','2026-05-24 00:02:40'),
(2,'En revision','','2026-05-24 00:02:40','2026-05-24 00:02:40'),
(3,'Aprobada','','2026-05-24 00:02:40','2026-05-24 00:02:40'),
(4,'Rechazada','','2026-05-24 00:02:40','2026-05-24 00:02:40'),
(5,'Desembolsada','','2026-05-24 00:02:40','2026-05-24 00:02:40');
/*!40000 ALTER TABLE `estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logSolicitud`
--

DROP TABLE IF EXISTS `logSolicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logSolicitud` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id identificativo del historico de las solicitudes',
  `id_solicitud` int(11) NOT NULL,
  `nombre_proceso` varchar(30) NOT NULL COMMENT 'nombre del proceso creado, si es un CREATE (primera vez) o UPDATE dependiendo del proceso',
  `informacion` text NOT NULL COMMENT 'informacion guardada que fue modificada',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='historico de logs en donde se almacena cuando se hacen el cambio de estado de las solicitudes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logSolicitud`
--

LOCK TABLES `logSolicitud` WRITE;
/*!40000 ALTER TABLE `logSolicitud` DISABLE KEYS */;
INSERT INTO `logSolicitud` VALUES
(1,1,'CREATE','{\"numero_credito\":\"66714571738\",\"cliente_id\":3,\"valor_solicitado\":4444444,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 01:59:55','2026-05-25 01:59:55'),
(2,1,'UPDATE','{\"estado_id\":1,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 03:48:25','2026-05-25 03:48:25'),
(3,2,'CREATE','{\"numero_credito\":\"66714571732\",\"cliente_id\":3,\"valor_solicitado\":4444444,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 03:51:28','2026-05-25 03:51:28'),
(4,1,'UPDATE','{\"estado_id\":2,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 03:52:03','2026-05-25 03:52:03'),
(5,1,'UPDATE','{\"estado_id\":3,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 03:52:09','2026-05-25 03:52:09'),
(6,1,'UPDATE','{\"estado_id\":1,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 03:52:12','2026-05-25 03:52:12'),
(7,1,'UPDATE','{\"id_estado\":1,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:52:18','2026-05-25 04:52:18'),
(8,1,'UPDATE','{\"id_estado\":1,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:52:29','2026-05-25 04:52:29'),
(9,1,'UPDATE','{\"id_estado\":1,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:52:34','2026-05-25 04:52:34'),
(10,1,'UPDATE','{\"estado_id\":2,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:58:05','2026-05-25 04:58:05'),
(11,1,'UPDATE','{\"estado_id\":1,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:58:11','2026-05-25 04:58:11'),
(12,1,'UPDATE','{\"estado_id\":3,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:58:16','2026-05-25 04:58:16'),
(13,1,'UPDATE','{\"estado_id\":4,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:58:19','2026-05-25 04:58:19'),
(14,1,'UPDATE','{\"estado_id\":5,\"id_solicitud\":1,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 04:58:23','2026-05-25 04:58:23'),
(15,2,'UPDATE','{\"estado_id\":4,\"id_solicitud\":2,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 15:13:51','2026-05-25 15:13:51'),
(16,3,'CREATE','{\"numero_credito\":\"11714571732\",\"cliente_id\":6,\"valor_solicitado\":4444444,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:24:22','2026-05-25 15:24:22'),
(17,4,'CREATE','{\"numero_credito\":\"12714571732\",\"cliente_id\":6,\"valor_solicitado\":4444444,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:24:26','2026-05-25 15:24:26'),
(18,0,'CREATE','{\"numero_credito\":7811390306961966,\"cliente_id\":6,\"valor_solicitado\":7958859275626079,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:24:49','2026-05-25 15:24:49'),
(19,5,'CREATE','{\"numero_credito\":5796972526074529,\"cliente_id\":6,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:03','2026-05-25 15:25:03'),
(20,6,'CREATE','{\"numero_credito\":8571427975852702,\"cliente_id\":6,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:06','2026-05-25 15:25:06'),
(21,7,'CREATE','{\"numero_credito\":1537229793183012,\"cliente_id\":6,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:07','2026-05-25 15:25:07'),
(22,8,'CREATE','{\"numero_credito\":6747919565078559,\"cliente_id\":6,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:08','2026-05-25 15:25:08'),
(23,9,'CREATE','{\"numero_credito\":4209452047906643,\"cliente_id\":6,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:08','2026-05-25 15:25:08'),
(24,10,'CREATE','{\"numero_credito\":2737946087975396,\"cliente_id\":6,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:09','2026-05-25 15:25:09'),
(25,11,'CREATE','{\"numero_credito\":777488002858843,\"cliente_id\":6,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:09','2026-05-25 15:25:09'),
(26,12,'CREATE','{\"numero_credito\":915030322785087,\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:14','2026-05-25 15:25:14'),
(27,13,'CREATE','{\"numero_credito\":6643940662861267,\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:15','2026-05-25 15:25:15'),
(28,14,'CREATE','{\"numero_credito\":3167307590214201,\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:15','2026-05-25 15:25:15'),
(29,15,'CREATE','{\"numero_credito\":706049346637379,\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 15:25:16','2026-05-25 15:25:16'),
(30,16,'CREATE','{\"numero_credito\":1282988419263368,\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 17:17:06','2026-05-25 17:17:06'),
(31,17,'CREATE','{\"numero_credito\":4207032721201851,\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 17:17:12','2026-05-25 17:17:12'),
(32,0,'CREATE','{\"numero_credito\":4207032721201851,\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 17:17:14','2026-05-25 17:17:14'),
(33,19,'CREATE','{\"numero_credito\":\"4207032721201852\",\"cliente_id\":3,\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 17:19:17','2026-05-25 17:19:17'),
(34,20,'CREATE','{\"numero_credito\":\"2207032721201852\",\"cliente_id\":\"1\",\"valor_solicitado\":434344421,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 17:21:11','2026-05-25 17:21:11'),
(35,0,'CREATE','{\"numero_credito\":\"2307032721201852\",\"cliente_id\":1,\"valor_solicitado\":434344421,\"asesor_id\":4444444,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 17:22:21','2026-05-25 17:22:21'),
(36,22,'CREATE','{\"numero_credito\":\"2307032721201852\",\"cliente_id\":1,\"valor_solicitado\":434344421,\"asesor_id\":\"24552\",\"auxiliar_id\":\"5344\",\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}','2026-05-25 17:38:42','2026-05-25 17:38:42'),
(37,2,'UPDATE','{\"estado_id\":5,\"id_solicitud\":2,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 17:39:55','2026-05-25 17:39:55'),
(38,4,'UPDATE','{\"estado_id\":5,\"id_solicitud\":4,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 17:40:21','2026-05-25 17:40:21'),
(39,5,'UPDATE','{\"estado_id\":5,\"id_solicitud\":5,\"observacion\":\"Solicitud aprobada por validaci\\u00f3n documental\"}','2026-05-25 17:40:25','2026-05-25 17:40:25');
/*!40000 ALTER TABLE `logSolicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id primario identificativo del rol',
  `nombre_rol` varchar(100) NOT NULL COMMENT 'nombre del rol',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='listado de roles';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Auxiliar','2026-05-23 19:27:46','2026-05-23 19:27:46'),
(2,'Asesor','2026-05-23 19:27:47','2026-05-23 19:27:47');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id autoincrementable que representa la solicitud creada',
  `numero_credito` varchar(250) NOT NULL,
  `valor_solicitado` int(11) DEFAULT NULL,
  `asesor_id` int(11) NOT NULL COMMENT 'id identificativo del asesor',
  `auxiliar_id` int(11) NOT NULL COMMENT 'id identificativo del auxiliar',
  `estado_id` int(11) NOT NULL COMMENT 'estado en el cual se encuentra el proceso',
  `cliente_id` int(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_solicitud`),
  UNIQUE KEY `numero_credito` (`numero_credito`),
  KEY `fk_id_cliente` (`cliente_id`),
  KEY `fk_id_auxiliar` (`auxiliar_id`),
  KEY `fk_id_asesor` (`asesor_id`),
  KEY `fk_id_estado` (`estado_id`),
  CONSTRAINT `fk_id_asesor` FOREIGN KEY (`asesor_id`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_id_auxiliar` FOREIGN KEY (`auxiliar_id`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_id_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla en donde almacenamos las solicitudes creadas';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes`
--

LOCK TABLES `solicitudes` WRITE;
/*!40000 ALTER TABLE `solicitudes` DISABLE KEYS */;
INSERT INTO `solicitudes` VALUES
(1,'66714571738',4444444,5344,24552,5,3,'2026-05-25 01:59:55','2026-05-25 04:58:23'),
(2,'66714571732',4444444,5344,24552,5,3,'2026-05-25 03:51:28','2026-05-25 17:39:55'),
(3,'11714571732',4444444,5344,24552,1,6,'2026-05-25 15:24:22','2026-05-25 15:24:22'),
(4,'12714571732',4444444,5344,24552,5,6,'2026-05-25 15:24:26','2026-05-25 17:40:21'),
(5,'5796972526074529',434344421,5344,24552,5,6,'2026-05-25 15:25:03','2026-05-25 17:40:25'),
(6,'8571427975852702',434344421,5344,24552,1,6,'2026-05-25 15:25:06','2026-05-25 15:25:06'),
(7,'1537229793183012',434344421,5344,24552,1,6,'2026-05-25 15:25:07','2026-05-25 15:25:07'),
(8,'6747919565078559',434344421,5344,24552,1,6,'2026-05-25 15:25:08','2026-05-25 15:25:08'),
(9,'4209452047906643',434344421,5344,24552,1,6,'2026-05-25 15:25:08','2026-05-25 15:25:08'),
(10,'2737946087975396',434344421,5344,24552,1,6,'2026-05-25 15:25:09','2026-05-25 15:25:09'),
(11,'777488002858843',434344421,5344,24552,1,6,'2026-05-25 15:25:09','2026-05-25 15:25:09'),
(12,'915030322785087',434344421,5344,24552,1,3,'2026-05-25 15:25:14','2026-05-25 15:25:14'),
(13,'6643940662861267',434344421,5344,24552,1,3,'2026-05-25 15:25:15','2026-05-25 15:25:15'),
(14,'3167307590214201',434344421,5344,24552,1,3,'2026-05-25 15:25:15','2026-05-25 15:25:15'),
(15,'706049346637379',434344421,5344,24552,1,3,'2026-05-25 15:25:16','2026-05-25 15:25:16'),
(16,'1282988419263368',434344421,5344,24552,1,3,'2026-05-25 17:17:06','2026-05-25 17:17:06'),
(17,'4207032721201851',434344421,5344,24552,1,3,'2026-05-25 17:17:12','2026-05-25 17:17:12'),
(19,'4207032721201852',434344421,5344,24552,1,3,'2026-05-25 17:19:17','2026-05-25 17:19:17'),
(20,'2207032721201852',434344421,5344,24552,1,1,'2026-05-25 17:21:11','2026-05-25 17:21:11'),
(22,'2307032721201852',434344421,24552,5344,1,1,'2026-05-25 17:38:42','2026-05-25 17:38:42');
/*!40000 ALTER TABLE `solicitudes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL COMMENT 'id primario representativo del usuario',
  `nombre_completo` varchar(250) NOT NULL COMMENT 'Nombre completo del usuario',
  `id_rol` int(11) NOT NULL COMMENT 'Id que representa que tipo de rol tiene el usuario, si es auxiliar o asesor',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  KEY `fk_id_rol` (`id_rol`),
  CONSTRAINT `fk_id_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla que almacena los usuarios que interactuan con el sistema';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(5344,'Ana',2,'2026-05-23 19:27:47','2026-05-23 19:27:47'),
(24552,'Isabella',1,'2026-05-23 19:27:47','2026-05-23 19:27:47'),
(24932,'Paola',1,'2026-05-23 19:27:47','2026-05-23 19:27:47'),
(35555,'William',2,'2026-05-23 19:27:47','2026-05-23 19:27:47'),
(42213,'Mateo',2,'2026-05-23 19:27:47','2026-05-23 19:27:47'),
(63444,'Alonso',1,'2026-05-23 19:27:47','2026-05-23 19:27:47'),
(66433,'Fernando',2,'2026-05-23 19:27:47','2026-05-23 19:27:47');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-25 13:05:36
