/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: mysql-25e8a0dd-inetegradora-aiven.a.aivencloud.com    Database: Nairobi_2026
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `animales`
--

DROP TABLE IF EXISTS `animales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `animales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visibilidad` enum('visible','invisible') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visible',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animales`
--

LOCK TABLES `animales` WRITE;
/*!40000 ALTER TABLE `animales` DISABLE KEYS */;
INSERT INTO `animales` VALUES
(1,'Perro','visible',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32'),
(2,'Gato','visible',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32'),
(3,'Iguana','visible',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32'),
(4,'Tortuga','visible',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32'),
(5,'Camaleón','visible',NULL,'2026-03-18 09:46:33','2026-03-18 09:46:33'),
(6,'Conejo','visible',NULL,'2026-03-18 09:46:33','2026-03-18 09:46:33'),
(7,'Hámster','visible',NULL,'2026-03-18 09:46:33','2026-03-18 09:46:33'),
(8,'Cuy','visible',NULL,'2026-03-18 09:46:33','2026-03-18 09:46:33'),
(9,'Hurón','visible',NULL,'2026-03-18 09:46:33','2026-03-18 09:46:33'),
(10,'Perico','visible',NULL,'2026-03-18 09:46:33','2026-03-18 09:46:33'),
(11,'Loro','visible',NULL,'2026-03-18 09:46:33','2026-03-18 09:46:33'),
(12,'Canario','visible',NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(13,'Serpiente','visible',NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(14,'Erizo','visible',NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(15,'Oveja','visible',NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(16,'Vaca','visible',NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(17,'Cerdo','invisible',NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(18,'hola','visible',NULL,'2026-03-18 11:46:21','2026-03-18 11:46:21');
/*!40000 ALTER TABLE `animales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `citas`
--

DROP TABLE IF EXISTS `citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `citas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `estado` enum('agendada','realizada','cancelada','en_proceso') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'agendada',
  `fecha` date NOT NULL,
  `tipo` enum('medico','estetico','mixto') COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `mascota_id` bigint unsigned NOT NULL,
  `horario_trabajador_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cita_trabajador_fecha` (`horario_trabajador_id`,`fecha`),
  KEY `citas_mascota_id_foreign` (`mascota_id`),
  CONSTRAINT `citas_horario_trabajador_id_foreign` FOREIGN KEY (`horario_trabajador_id`) REFERENCES `horario_trabajador` (`id`),
  CONSTRAINT `citas_mascota_id_foreign` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citas`
--

LOCK TABLES `citas` WRITE;
/*!40000 ALTER TABLE `citas` DISABLE KEYS */;
INSERT INTO `citas` VALUES
(1,'agendada','2026-02-20','medico','Primera consulta de control anual',1,1,'2026-03-18 09:46:45','2026-03-18 09:46:45'),
(2,'en_proceso','2026-02-21','medico','El perro presenta herida en la pata',2,5,'2026-03-18 09:46:46','2026-03-18 09:46:46'),
(3,'realizada','2026-02-22','medico','Seguimiento de tratamiento',1,10,'2026-03-18 09:46:46','2026-03-18 09:46:46'),
(4,'cancelada','2026-02-23','medico','Chequeo general',2,15,'2026-03-18 09:46:46','2026-03-18 09:46:46'),
(5,'agendada','2026-02-24','medico','Vacunación',1,20,'2026-03-18 09:46:46','2026-03-18 09:46:46');
/*!40000 ALTER TABLE `citas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `municipio` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colonia` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_postal` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calle` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_exterior` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientes_user_id_foreign` (`user_id`),
  CONSTRAINT `clientes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES
(1,'Ciudad de México','Centro','06000','Av. Reforma','123',3,'2026-03-18 09:46:39','2026-03-18 09:46:39'),
(2,'Ciudad de México','Centro','06000','Av. Reforma','123',4,'2026-03-18 09:46:39','2026-03-18 09:46:39');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consulta_servicios`
--

DROP TABLE IF EXISTS `consulta_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `consulta_servicios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `detalles_servicio` text COLLATE utf8mb4_unicode_ci,
  `precio_servicio` double NOT NULL,
  `precio_producto` double NOT NULL,
  `total` double NOT NULL,
  `consulta_id` bigint unsigned NOT NULL,
  `servicio_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consulta_servicios_consulta_id_foreign` (`consulta_id`),
  KEY `consulta_servicios_servicio_id_foreign` (`servicio_id`),
  CONSTRAINT `consulta_servicios_consulta_id_foreign` FOREIGN KEY (`consulta_id`) REFERENCES `consultas` (`id`),
  CONSTRAINT `consulta_servicios_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consulta_servicios`
--

LOCK TABLES `consulta_servicios` WRITE;
/*!40000 ALTER TABLE `consulta_servicios` DISABLE KEYS */;
/*!40000 ALTER TABLE `consulta_servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consulta_servicios_productos`
--

DROP TABLE IF EXISTS `consulta_servicios_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `consulta_servicios_productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cantidad_usada` double NOT NULL,
  `consulta_servicio_id` bigint unsigned NOT NULL,
  `tipo_venta` enum('Total','Fraccionado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `subtotal` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consulta_servicios_productos_consulta_servicio_id_foreign` (`consulta_servicio_id`),
  KEY `consulta_servicios_productos_producto_id_foreign` (`producto_id`),
  CONSTRAINT `consulta_servicios_productos_consulta_servicio_id_foreign` FOREIGN KEY (`consulta_servicio_id`) REFERENCES `consulta_servicios` (`id`),
  CONSTRAINT `consulta_servicios_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consulta_servicios_productos`
--

LOCK TABLES `consulta_servicios_productos` WRITE;
/*!40000 ALTER TABLE `consulta_servicios_productos` DISABLE KEYS */;
/*!40000 ALTER TABLE `consulta_servicios_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pre_diagnostico` text COLLATE utf8mb4_unicode_ci,
  `descripcion_consulta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicaciones` text COLLATE utf8mb4_unicode_ci,
  `cita_id` bigint unsigned NOT NULL,
  `total_servicios` double DEFAULT NULL,
  `total_producto` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultas_cita_id_foreign` (`cita_id`),
  CONSTRAINT `consultas_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES
(1,'Estado de salud general bueno','Se realizó revisión general, vacunas al día','Continuar con alimentación balanceada, ejercicio diario',1,0,0,0,'2026-03-18 09:46:46','2026-03-18 09:46:46'),
(2,'Herida superficial en miembro posterior','Se limpió la herida y se aplicó desinfectante','Cambio de vendaje cada 24 horas, antibiótico vía oral por 5 días',2,150,80,230,'2026-03-18 09:46:46','2026-03-18 09:46:46'),
(3,'Mejoría notable del paciente','Seguimiento post-tratamiento, la herida está sanando correctamente','Continuar con los medicamentos, regresar en una semana',3,100,50,150,'2026-03-18 09:46:46','2026-03-18 09:46:46'),
(4,'Sin anomalías detectadas','Chequeo general preventivo','Vacunación aplicada, desparasitación cada 3 meses',4,200,100,300,'2026-03-18 09:46:47','2026-03-18 09:46:47'),
(5,'Paciente sano','Revisión de rutina, peso y temperatura normales','Mantener higiene, próxima cita en 6 meses',5,150,0,150,'2026-03-18 09:46:47','2026-03-18 09:46:47');
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horario_trabajador`
--

DROP TABLE IF EXISTS `horario_trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `horario_trabajador` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asignado` enum('asignado','desasignado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'asignado',
  `horario_id` bigint unsigned NOT NULL,
  `trabajador_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_horario_trabajador` (`horario_id`,`trabajador_id`),
  KEY `horario_trabajador_trabajador_id_foreign` (`trabajador_id`),
  CONSTRAINT `horario_trabajador_horario_id_foreign` FOREIGN KEY (`horario_id`) REFERENCES `horarios` (`id`),
  CONSTRAINT `horario_trabajador_trabajador_id_foreign` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajadores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horario_trabajador`
--

LOCK TABLES `horario_trabajador` WRITE;
/*!40000 ALTER TABLE `horario_trabajador` DISABLE KEYS */;
INSERT INTO `horario_trabajador` VALUES
(1,'asignado',1,1,NULL,NULL),
(2,'asignado',2,1,NULL,NULL),
(3,'asignado',3,1,NULL,NULL),
(4,'asignado',4,1,NULL,NULL),
(5,'asignado',5,1,NULL,NULL),
(6,'asignado',6,1,NULL,NULL),
(7,'asignado',7,1,NULL,NULL),
(8,'asignado',8,1,NULL,NULL),
(9,'asignado',9,1,NULL,NULL),
(10,'asignado',10,1,NULL,NULL),
(11,'asignado',11,1,NULL,NULL),
(12,'asignado',12,1,NULL,NULL),
(13,'asignado',13,1,NULL,NULL),
(14,'asignado',14,1,NULL,NULL),
(15,'asignado',15,1,NULL,NULL),
(16,'asignado',16,1,NULL,NULL),
(17,'asignado',17,1,NULL,NULL),
(18,'asignado',18,1,NULL,NULL),
(19,'asignado',19,1,NULL,NULL),
(20,'asignado',20,1,NULL,NULL),
(21,'asignado',1,2,NULL,NULL),
(22,'asignado',2,2,NULL,NULL),
(23,'asignado',3,2,NULL,NULL),
(24,'asignado',4,2,NULL,NULL),
(25,'asignado',5,2,NULL,NULL),
(26,'asignado',6,2,NULL,NULL),
(27,'asignado',7,2,NULL,NULL),
(28,'asignado',8,2,NULL,NULL),
(29,'asignado',9,2,NULL,NULL),
(30,'asignado',10,2,NULL,NULL),
(31,'asignado',11,2,NULL,NULL),
(32,'asignado',12,2,NULL,NULL),
(33,'asignado',13,2,NULL,NULL),
(34,'asignado',14,2,NULL,NULL),
(35,'asignado',15,2,NULL,NULL),
(36,'asignado',16,2,NULL,NULL),
(37,'asignado',17,2,NULL,NULL),
(38,'asignado',18,2,NULL,NULL),
(39,'asignado',19,2,NULL,NULL),
(40,'asignado',20,2,NULL,NULL);
/*!40000 ALTER TABLE `horario_trabajador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios`
--

DROP TABLE IF EXISTS `horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inicio_hora` time NOT NULL,
  `final_hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
INSERT INTO `horarios` VALUES
(1,'09:00:00','09:30:00','2026-03-18 09:46:10','2026-03-18 09:46:10'),
(2,'09:30:00','10:00:00','2026-03-18 09:46:10','2026-03-18 09:46:10'),
(3,'10:00:00','10:30:00','2026-03-18 09:46:10','2026-03-18 09:46:10'),
(4,'10:30:00','11:00:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(5,'11:00:00','11:30:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(6,'11:30:00','12:00:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(7,'12:00:00','12:30:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(8,'12:30:00','13:00:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(9,'13:00:00','13:30:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(10,'13:30:00','14:00:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(11,'14:00:00','14:30:00','2026-03-18 09:46:11','2026-03-18 09:46:11'),
(12,'14:30:00','15:00:00','2026-03-18 09:46:12','2026-03-18 09:46:12'),
(13,'15:00:00','15:30:00','2026-03-18 09:46:12','2026-03-18 09:46:12'),
(14,'15:30:00','16:00:00','2026-03-18 09:46:12','2026-03-18 09:46:12'),
(15,'16:00:00','16:30:00','2026-03-18 09:46:12','2026-03-18 09:46:12'),
(16,'16:30:00','17:00:00','2026-03-18 09:46:12','2026-03-18 09:46:12'),
(17,'17:00:00','17:30:00','2026-03-18 09:46:12','2026-03-18 09:46:12'),
(18,'17:30:00','18:00:00','2026-03-18 09:46:12','2026-03-18 09:46:12'),
(19,'18:00:00','18:30:00','2026-03-18 09:46:13','2026-03-18 09:46:13'),
(20,'18:30:00','19:00:00','2026-03-18 09:46:13','2026-03-18 09:46:13');
/*!40000 ALTER TABLE `horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mascotas`
--

DROP TABLE IF EXISTS `mascotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mascotas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` enum('macho','hembra') COLLATE utf8mb4_unicode_ci NOT NULL,
  `peso` double DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `visibilidad` enum('visible','invisible') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visible',
  `raza_id` bigint unsigned NOT NULL,
  `cliente_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mascotas_raza_id_foreign` (`raza_id`),
  KEY `mascotas_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `mascotas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `mascotas_raza_id_foreign` FOREIGN KEY (`raza_id`) REFERENCES `razas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mascotas`
--

LOCK TABLES `mascotas` WRITE;
/*!40000 ALTER TABLE `mascotas` DISABLE KEYS */;
INSERT INTO `mascotas` VALUES
(1,'Firulais','macho',NULL,'Un perro muy amigable','2020-01-01','visible',2,1,NULL,'2026-03-18 09:46:40','2026-03-18 09:49:28'),
(2,'sapito de la suertesilla','macho',NULL,'Un perro muy amigable','2020-01-01','visible',1,2,NULL,'2026-03-18 09:46:40','2026-03-18 11:56:28'),
(3,'<h1>peggo</h1>','macho',12,'no hay front','2026-03-18','visible',7,1,'2026-03-18 11:50:06','2026-03-18 11:48:15','2026-03-18 11:50:06'),
(4,'asdasd','macho',123123,'132123123','2026-03-18','visible',6,1,NULL,'2026-03-18 11:51:05','2026-03-18 11:51:05');
/*!40000 ALTER TABLE `mascotas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(118,'0000_02_16_000001_create_roles_table',1),
(119,'0001_01_01_000000_create_users_table',1),
(120,'0001_01_01_000001_create_cache_table',1),
(121,'0001_01_01_000002_create_jobs_table',1),
(122,'2026_02_12_145457_create_personal_access_tokens_table',1),
(123,'2026_02_16_000003_create_trabajadores_table',1),
(124,'2026_02_16_000004_create_clientes_table',1),
(125,'2026_02_16_000005_create_horarios_table',1),
(126,'2026_02_16_000006_create_horario_trabajador_table',1),
(127,'2026_02_16_000007_create_animales_table',1),
(128,'2026_02_16_000008_create_razas_table',1),
(129,'2026_02_16_000009_create_mascotas_table',1),
(130,'2026_02_16_000010_create_productos_table',1),
(131,'2026_02_16_000011_create_servicios_table',1),
(132,'2026_02_16_000012_create_citas_table',1),
(133,'2026_02_16_000013_create_consultas_table',1),
(134,'2026_02_16_000014_create_consulta_servicios_table',1),
(135,'2026_02_16_000015_create_consulta_servicios_productos_table',1),
(136,'2026_02_16_000016_create_reportes_consultas_table',1),
(137,'2026_03_08_172037_create_tokens_users_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` double NOT NULL,
  `precio_compra` double NOT NULL,
  `precio_venta` double NOT NULL,
  `cantidad` double NOT NULL,
  `visibilidad` enum('visible','invisible') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visible',
  `medida` enum('gramos','kilos','mililitros','litros','unidad') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES
(1,'o8oeIyOgxluXUZgEfWf9','RH0N1mUxKB',7,2,2,2,'invisible','mililitros',NULL,'2026-03-18 09:46:17','2026-03-18 09:46:17'),
(2,'ItcLyUiAhVsFPOq5xiKu','VKwu9v8GR6',3,2,7,7,'visible','litros',NULL,'2026-03-18 09:46:17','2026-03-18 09:46:17'),
(3,'68YZbNFUSDqor2O15iRK','YJtY2DRJZT',5,6,6,6,'invisible','mililitros',NULL,'2026-03-18 09:46:17','2026-03-18 09:46:17'),
(4,'vH8rlP64cdvG4XEYvvH0','DF4rHXqTHk',1,2,3,8,'invisible','mililitros',NULL,'2026-03-18 09:46:18','2026-03-18 09:46:18'),
(5,'T3QyMbeJW7DMaa6pDJfR','XpSO6Votmt',1,1,1,9,'visible','mililitros',NULL,'2026-03-18 09:46:18','2026-03-18 09:46:18'),
(6,'LtVzltQZq4179JYQJcW8','OBFZ0DYjkt',9,6,3,5,'visible','unidad',NULL,'2026-03-18 09:46:18','2026-03-18 09:46:18'),
(7,'Kw2P7HmxvhLXGeKk5f4t','byDQyhnM5q',3,6,3,7,'invisible','kilos',NULL,'2026-03-18 09:46:18','2026-03-18 09:46:18'),
(8,'XkowRtTvbDNgbocx79M2','TNrVUF3wm2',2,2,4,2,'invisible','litros',NULL,'2026-03-18 09:46:18','2026-03-18 09:46:18'),
(9,'bjehUzf6JksoLpNHRM6i','V6QU1JjP0B',5,9,9,2,'invisible','litros',NULL,'2026-03-18 09:46:19','2026-03-18 09:46:19'),
(10,'YcqhL8KSjQF2snmliaV2','cfHXlmfXMm',6,4,8,6,'visible','kilos',NULL,'2026-03-18 09:46:19','2026-03-18 09:46:19'),
(11,'VvhW11tR8pUDc1t30rtT','KZ2Yc9CVlg',6,5,8,8,'visible','unidad',NULL,'2026-03-18 09:46:19','2026-03-18 09:46:19'),
(12,'LK4dzgWWQFiTYx5N7D0M','6FYEcYzmQP',3,5,9,4,'visible','gramos',NULL,'2026-03-18 09:46:19','2026-03-18 09:46:19'),
(13,'tCVPrLopxKW9bb6iX5oJ','XMcUtCUUeH',9,5,1,6,'visible','gramos',NULL,'2026-03-18 09:46:19','2026-03-18 09:46:19'),
(14,'jk4053QCYWyouwLKFTSR','Ln4iL1HaoH',8,2,5,3,'invisible','kilos',NULL,'2026-03-18 09:46:19','2026-03-18 09:46:19'),
(15,'HK9ftw5lJGziPPjE5zs3','hix34pjFPr',5,9,4,1,'invisible','litros',NULL,'2026-03-18 09:46:19','2026-03-18 09:46:19'),
(16,'WwfCuys3Yi2mHLV5RLNH','BdRla28jpl',4,5,1,6,'invisible','mililitros',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(17,'XhW83Z2H9p0IWW155dyQ','qmnyIGkzSL',9,7,1,9,'visible','litros',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(18,'whgSgjtBKzXGjSlG5VvJ','c7WkJIXTCQ',4,7,1,5,'visible','gramos',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(19,'FxnTi9d0Rwq1GwR3GElk','x4NDK403zy',2,5,5,3,'invisible','unidad',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(20,'5y0raGy465WrY8tc3cVV','SYFWlnlk68',5,7,5,9,'visible','unidad',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(21,'so2zKhpX03VH1Ow0SNJB','OmqFIHfvJd',1,8,6,6,'invisible','kilos',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(22,'pQBFeeKGlgY8EkgZh6fv','Pxig5fbOql',2,9,3,2,'invisible','gramos',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(23,'zHDvD8x5xHQq0HM4fYsQ','bYmqAMJTQW',2,6,7,6,'visible','kilos',NULL,'2026-03-18 09:46:20','2026-03-18 09:46:20'),
(24,'9dExgk02GLCStqpcySMv','70ctJ3WCAF',4,3,1,3,'invisible','litros',NULL,'2026-03-18 09:46:21','2026-03-18 09:46:21'),
(25,'mRm42ZMANpQtub4QE8E7','QArV2aRUVc',9,2,6,3,'invisible','gramos',NULL,'2026-03-18 09:46:21','2026-03-18 09:46:21'),
(26,'T1myKcjY0wTy1DoUEvcb','gDlMCX80re',8,4,7,9,'visible','unidad',NULL,'2026-03-18 09:46:21','2026-03-18 09:46:21'),
(27,'dlfrFesPKb0q0Nf8hnDA','42Yb5ItEwQ',3,4,4,5,'visible','unidad',NULL,'2026-03-18 09:46:21','2026-03-18 09:46:21'),
(28,'r6tDZqfOujIOjzIRNjOj','XKDAqlR4QS',5,2,4,5,'visible','gramos',NULL,'2026-03-18 09:46:21','2026-03-18 09:46:21'),
(29,'bRhaslZWQYbwsEPwS6eK','puzhM5DAu1',2,4,2,1,'visible','gramos',NULL,'2026-03-18 09:46:21','2026-03-18 09:46:21'),
(30,'dYQh060rBWsVIeaV2deS','46GZuXfnKE',4,2,3,7,'invisible','litros',NULL,'2026-03-18 09:46:21','2026-03-18 09:46:21'),
(31,'gnckoneuDzwAcJKf2GLD','nubbWImY76',5,1,1,7,'invisible','gramos',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(32,'jhQJA3CtmhBONWrd49qu','Bv17I87mkH',7,6,5,3,'invisible','gramos',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(33,'BcUHWPSp4JEo22BSFa1N','tOsGnZWFpW',3,9,9,2,'visible','mililitros',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(34,'WJCFHxVLmXUEj1evAqQv','6rSn4sj2WX',5,7,7,8,'visible','mililitros',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(35,'sCq9eQmITiH3ssljhDXn','I1fXqiNDAj',3,7,5,2,'invisible','litros',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(36,'lixxezcGeahkYDpRPOgS','O1CbjZSNIA',2,4,6,7,'visible','gramos',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(37,'kbdjmDM1p7oIlTIb1Zpp','MGalBOa9lw',9,2,6,9,'invisible','mililitros',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(38,'gLB3GaS8q1KjnxZ48cEq','WNd2ODWHPk',2,5,5,2,'invisible','litros',NULL,'2026-03-18 09:46:22','2026-03-18 09:46:22'),
(39,'WurJAgJDP4FiKvHF0hod','9U6ljfiP2d',5,6,1,7,'visible','litros',NULL,'2026-03-18 09:46:23','2026-03-18 09:46:23'),
(40,'LGPAE26DISkovLT0pAsR','UUgM9cw1UV',5,1,7,1,'invisible','unidad',NULL,'2026-03-18 09:46:23','2026-03-18 09:46:23'),
(41,'EhTucshK83CIZm9CZYTF','JT8dvPlSmD',2,9,3,2,'visible','litros',NULL,'2026-03-18 09:46:23','2026-03-18 09:46:23'),
(42,'q2lo7wIa6b9n1E8OKXAu','6Y4BFd8qDE',5,1,4,1,'invisible','unidad',NULL,'2026-03-18 09:46:23','2026-03-18 09:46:23'),
(43,'dga7AcDeGgJwwIJ5VNWF','X20cWNozhf',3,3,3,2,'invisible','unidad',NULL,'2026-03-18 09:46:23','2026-03-18 09:46:23'),
(44,'m88V2eJfkPyXuEbAkBHt','aVmlM7mPx3',3,5,8,8,'visible','litros',NULL,'2026-03-18 09:46:23','2026-03-18 09:46:23'),
(45,'D8AoCUyqjiYRD7LB8n6I','efXfQla7tJ',4,8,5,9,'invisible','mililitros',NULL,'2026-03-18 09:46:23','2026-03-18 09:46:23'),
(46,'KuOxV9tdddP05ewu6t4m','va7irrWX3M',3,6,8,3,'visible','kilos',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(47,'84oYwutJqGimdCxZq8Zy','ind9r4aZzP',6,4,9,9,'visible','litros',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(48,'F7wEBeX2568XsL0Bz2dC','fONzmVwJFy',9,3,6,7,'visible','litros',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(49,'3zq5Iq5SygWAfsaHXkRg','vf9FHIvqyj',2,1,2,5,'visible','kilos',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(50,'KhZOPi3l0xRLKocVXioz','a1B9XCRknF',4,6,6,6,'invisible','gramos',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(51,'r6rypclEIiw6jYc0Z7Ay','0UrOEyCDpB',4,2,6,2,'invisible','litros',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(52,'yXXRdo6EFiLchyGR28Ig','w2VKnDXxHs',1,8,8,9,'visible','unidad',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(53,'US0bSUP6x661dQEfYsiM','fD3m28RIWU',2,9,5,3,'invisible','unidad',NULL,'2026-03-18 09:46:24','2026-03-18 09:46:24'),
(54,'MG7CdzBon50rhEalU7MI','8pHWHHfD7f',8,7,5,7,'invisible','kilos',NULL,'2026-03-18 09:46:25','2026-03-18 09:46:25'),
(55,'dgbR5xmB3FaxOGXGwp6i','6ZkndjQVy3',6,9,8,8,'visible','unidad',NULL,'2026-03-18 09:46:25','2026-03-18 09:46:25'),
(56,'1HnKbTkRDlK8IUd4T6eT','fc2WJNKzAD',2,4,2,6,'visible','litros',NULL,'2026-03-18 09:46:25','2026-03-18 09:46:25'),
(57,'QnmOFVhLX81nLC0vBCTd','PRrN58Qwcs',2,1,7,8,'visible','kilos',NULL,'2026-03-18 09:46:25','2026-03-18 09:46:25'),
(58,'DF0bq6Pk6iPUgz9FmxmZ','vQDx92mJk7',9,8,9,5,'visible','mililitros',NULL,'2026-03-18 09:46:25','2026-03-18 09:46:25'),
(59,'RbcAzvy5M4RYszofwc70','IWAtlxnDv4',8,2,5,3,'invisible','kilos',NULL,'2026-03-18 09:46:25','2026-03-18 09:46:25'),
(60,'CRAhyKcHcm5yvHKHYf40','y4xgy4WLBv',2,5,3,2,'visible','mililitros',NULL,'2026-03-18 09:46:25','2026-03-18 09:46:25'),
(61,'kOXvCGjEOUIABD9MBOB8','jxRvGHYfjD',1,4,8,3,'visible','kilos',NULL,'2026-03-18 09:46:26','2026-03-18 09:46:26'),
(62,'jRyWOyelOnvZtl7KkWK6','9I59lylqoG',5,7,3,8,'visible','kilos',NULL,'2026-03-18 09:46:26','2026-03-18 09:46:26'),
(63,'UL4qCi3bBG9vtEClohrZ','vUYexTbm5t',3,8,7,6,'invisible','mililitros',NULL,'2026-03-18 09:46:26','2026-03-18 09:46:26'),
(64,'cQyd8wD9DP40oQf7asYc','ioxqbtDCIW',2,3,6,4,'visible','litros',NULL,'2026-03-18 09:46:26','2026-03-18 09:46:26'),
(65,'QbM6DtjpNzYGcwn9Y6J7','boYO6Xf6TG',6,7,7,1,'visible','kilos',NULL,'2026-03-18 09:46:26','2026-03-18 09:46:26'),
(66,'0PbytI0IZSl3e3fs2sUW','wJmXmFKxrV',2,1,5,3,'visible','mililitros',NULL,'2026-03-18 09:46:26','2026-03-18 09:46:26'),
(67,'bAJFUldopJF65oAlbrCq','dDcGoPistP',4,5,6,3,'visible','kilos',NULL,'2026-03-18 09:46:26','2026-03-18 09:46:26'),
(68,'eoV0HlL0QHHUan5IKxeP','95blnSSCyP',4,5,5,5,'invisible','kilos',NULL,'2026-03-18 09:46:27','2026-03-18 09:46:27'),
(69,'iqliORltPQiXl2GIlkbI','cXNs6BEL4q',1,2,5,8,'visible','litros',NULL,'2026-03-18 09:46:27','2026-03-18 09:46:27'),
(70,'2q1VvM6v77AdFOUVg6xh','63dJkuOwnp',8,8,8,1,'invisible','mililitros',NULL,'2026-03-18 09:46:27','2026-03-18 09:46:27'),
(71,'LjVvKR6Qe81IbIZ7e3WH','0DhrScw3nY',6,6,5,1,'invisible','litros',NULL,'2026-03-18 09:46:27','2026-03-18 09:46:27'),
(72,'M5QaSDw7TbMGt0fuXOcc','uYHjtf553s',5,8,8,2,'invisible','unidad',NULL,'2026-03-18 09:46:27','2026-03-18 09:46:27'),
(73,'MUHhFgT75NHQqIN74myQ','klc2h9GjFv',2,4,4,8,'visible','mililitros',NULL,'2026-03-18 09:46:27','2026-03-18 09:46:27'),
(74,'VPHX5pzqpMcw0HMYF0cx','AOiEGAe8Ev',5,7,6,9,'invisible','gramos',NULL,'2026-03-18 09:46:27','2026-03-18 09:46:27'),
(75,'BVS7NI4OyjfZaXLS9lS1','25EbETBWR5',2,4,9,2,'invisible','litros',NULL,'2026-03-18 09:46:28','2026-03-18 09:46:28'),
(76,'iQfT17vYYiUfVp9s167L','WXSyzEXxvV',4,6,8,1,'visible','litros',NULL,'2026-03-18 09:46:28','2026-03-18 09:46:28'),
(77,'5hbSGQuoRukgSwRuekfF','UgxMlIz4bx',5,3,4,1,'visible','kilos',NULL,'2026-03-18 09:46:28','2026-03-18 09:46:28'),
(78,'KwUT0pb8LN8ZEuPohmdC','aghW41hxiL',9,8,1,5,'invisible','gramos',NULL,'2026-03-18 09:46:29','2026-03-18 09:46:29'),
(79,'5wHCEr4E03Y6fUJoL8o0','GUMx5vcTw5',9,6,9,3,'visible','mililitros',NULL,'2026-03-18 09:46:29','2026-03-18 09:46:29'),
(80,'AZgDtvexupjUYAiQYh1r','KwEzZsvcHM',1,1,6,8,'visible','gramos',NULL,'2026-03-18 09:46:29','2026-03-18 09:46:29'),
(81,'RRU7y35pHRtUYRC5HzXS','mZMd8CHkDW',6,6,6,1,'visible','litros',NULL,'2026-03-18 09:46:29','2026-03-18 09:46:29'),
(82,'cyQtUTuRT9KTjnjBXqrt','QtnUOUVagk',3,6,1,6,'visible','mililitros',NULL,'2026-03-18 09:46:29','2026-03-18 09:46:29'),
(83,'61ZXV12Dd8ACPwe4eMTZ','8bYAnWS694',4,1,4,4,'invisible','mililitros',NULL,'2026-03-18 09:46:30','2026-03-18 09:46:30'),
(84,'syqo1YRGf7TMLrovJXuR','cd3HwxCy7g',1,1,3,4,'invisible','gramos',NULL,'2026-03-18 09:46:30','2026-03-18 09:46:30'),
(85,'TsIkgealIlSkQpom7F8w','pTtUmHHyys',5,1,6,4,'visible','mililitros',NULL,'2026-03-18 09:46:30','2026-03-18 09:46:30'),
(86,'CaQaxO0QfYxPUBNBW0Xb','vVDt2CrqKk',2,3,4,9,'visible','gramos',NULL,'2026-03-18 09:46:30','2026-03-18 09:46:30'),
(87,'MqGRHPNkPD5Aqz12pGgN','mwLyte33pO',8,7,9,6,'visible','mililitros',NULL,'2026-03-18 09:46:30','2026-03-18 09:46:30'),
(88,'6TNmO0x5pcasB14EXQ2q','247abSgu4V',8,3,6,6,'invisible','mililitros',NULL,'2026-03-18 09:46:30','2026-03-18 09:46:30'),
(89,'Tb4MP3EWWCWHz1ep0XOR','bbjIz2zreI',8,6,4,5,'invisible','kilos',NULL,'2026-03-18 09:46:30','2026-03-18 09:46:30'),
(90,'YGLuDDBax3yuj9q4mqRA','MFDIWfB9oF',1,4,8,3,'visible','kilos',NULL,'2026-03-18 09:46:31','2026-03-18 09:46:31'),
(91,'v2FdZUD6SBgnissMASIe','WImzaKTHsH',8,4,9,4,'invisible','kilos',NULL,'2026-03-18 09:46:31','2026-03-18 09:46:31'),
(92,'KY5fCZUobAklooYU2cvy','mPKftomn3b',2,6,4,2,'visible','kilos',NULL,'2026-03-18 09:46:31','2026-03-18 09:46:31'),
(93,'3TL3J2virzQQhQDooeKu','GLO03TBlKX',5,8,6,4,'invisible','litros',NULL,'2026-03-18 09:46:31','2026-03-18 09:46:31'),
(94,'vqiWQsFSUqTuJhsy0g8G','SXzno7A9LJ',3,2,8,6,'invisible','mililitros',NULL,'2026-03-18 09:46:31','2026-03-18 09:46:31'),
(95,'gpJmy6QLs5r8167zh7NM','d3mR6glw27',7,1,6,1,'visible','litros',NULL,'2026-03-18 09:46:31','2026-03-18 09:46:31'),
(96,'SRf3tgjD0nXtPEK9TvNo','TyaQk3mAOc',4,8,2,5,'invisible','gramos',NULL,'2026-03-18 09:46:31','2026-03-18 09:46:31'),
(97,'7hYygdrQmhtMTqWTDiug','JfGrx4AC61',6,6,9,6,'visible','kilos',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32'),
(98,'Lrlbs7Clipy74ydcOsi7','B7W4IVpPdi',4,2,7,3,'visible','unidad',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32'),
(99,'D7K3EhxEJ8tQBinKQpsU','KK28rYv1CN',2,2,5,1,'invisible','litros',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32'),
(100,'wkvjaUGLifVwPs9yEFy7','KD8uNVHQMs',9,9,7,7,'visible','unidad',NULL,'2026-03-18 09:46:32','2026-03-18 09:46:32');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `razas`
--

DROP TABLE IF EXISTS `razas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `razas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visibilidad` enum('visible','invisible') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visible',
  `animal_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `razas_nombre_unique` (`nombre`),
  KEY `razas_animal_id_foreign` (`animal_id`),
  CONSTRAINT `razas_animal_id_foreign` FOREIGN KEY (`animal_id`) REFERENCES `animales` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `razas`
--

LOCK TABLES `razas` WRITE;
/*!40000 ALTER TABLE `razas` DISABLE KEYS */;
INSERT INTO `razas` VALUES
(1,'Labrador Retriever','visible',1,NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(2,'Bulldog','visible',1,NULL,'2026-03-18 09:46:34','2026-03-18 09:46:34'),
(3,'Golden Retriever','visible',1,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(4,'Pastor Alemán','visible',1,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(5,'Poodle','visible',1,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(6,'Beagle','visible',1,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(7,'Chihuahua','visible',1,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(8,'Persa','visible',2,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(9,'Siamés','visible',2,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(10,'Maine Coon','visible',2,NULL,'2026-03-18 09:46:35','2026-03-18 09:46:35'),
(11,'British Shorthair','visible',2,NULL,'2026-03-18 09:46:36','2026-03-18 09:46:36'),
(12,'Ragdoll','visible',2,NULL,'2026-03-18 09:46:36','2026-03-18 09:46:36'),
(13,'Holland Lop','visible',6,NULL,'2026-03-18 09:46:36','2026-03-18 09:46:36'),
(14,'Mini Rex','visible',6,NULL,'2026-03-18 09:46:36','2026-03-18 09:46:36'),
(15,'Hurón Estándar','invisible',9,NULL,'2026-03-18 09:46:36','2026-03-18 11:11:22'),
(16,'Perico Australiano','visible',10,NULL,'2026-03-18 09:46:36','2026-03-18 09:46:36'),
(17,'Loro Gris','visible',11,NULL,'2026-03-18 09:46:36','2026-03-18 09:46:36'),
(18,'Canario Amarillo','visible',12,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(19,'Iguana Verde','visible',3,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(20,'Tortuga Terrestre','visible',4,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(21,'Camaleón Velado','visible',5,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(22,'Serpiente Maíz','visible',13,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(23,'Hámster Sirio','visible',7,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(24,'Cuy Peruano','visible',8,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(25,'Erizo Africano','visible',14,NULL,'2026-03-18 09:46:37','2026-03-18 09:46:37'),
(26,'Oveja Suffolk','visible',15,NULL,'2026-03-18 09:46:38','2026-03-18 09:46:38'),
(27,'Vaca Holstein','visible',16,NULL,'2026-03-18 09:46:38','2026-03-18 09:46:38');
/*!40000 ALTER TABLE `razas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes_consultas`
--

DROP TABLE IF EXISTS `reportes_consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportes_consultas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `precio_servicios` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio_productos` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `consulta_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reportes_consultas_consulta_id_unique` (`consulta_id`),
  CONSTRAINT `reportes_consultas_consulta_id_foreign` FOREIGN KEY (`consulta_id`) REFERENCES `consultas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes_consultas`
--

LOCK TABLES `reportes_consultas` WRITE;
/*!40000 ALTER TABLE `reportes_consultas` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportes_consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'cliente','2026-03-18 09:46:09','2026-03-18 09:46:09'),
(2,'trabajador','2026-03-18 09:46:10','2026-03-18 09:46:10'),
(3,'administrador','2026-03-18 09:46:10','2026-03-18 09:46:10');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `visibilidad` enum('visible','invisible') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visible',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `servicios_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES
(1,'Consulta General','Revisión rutinaria del animal, chequeo vitales y diagnóstico básico.','visible',NULL,'2026-03-18 09:46:13','2026-03-18 09:46:13'),
(2,'Vacunación','Aplicación de vacunas según calendario.','visible',NULL,'2026-03-18 09:46:13','2026-03-18 09:46:13'),
(3,'Desparasitación','Eliminación de parásitos internos y externos.','visible',NULL,'2026-03-18 09:46:14','2026-03-18 09:46:14'),
(4,'Esterilización','Cirugía de castración o esterilización.','visible',NULL,'2026-03-18 09:46:14','2026-03-18 09:46:14'),
(5,'Análisis de Sangre','Estudios hematológicos y bioquímicos.','visible',NULL,'2026-03-18 09:46:14','2026-03-18 09:46:14'),
(6,'Radiografía','Estudio radiológico para diagnóstico.','visible',NULL,'2026-03-18 09:46:14','2026-03-18 09:46:14'),
(7,'Ecografía','Ultrasonido para órganos internos.','visible',NULL,'2026-03-18 09:46:15','2026-03-18 09:46:15'),
(8,'Cirugía Menor','Procedimientos quirúrgicos ambulatorios.','visible',NULL,'2026-03-18 09:46:15','2026-03-18 09:46:15'),
(9,'Baño y Grooming','Limpieza profesional y estética.','visible',NULL,'2026-03-18 09:46:15','2026-03-18 09:46:15'),
(10,'Corte de Uñas','Poda de uñas del animal.','visible',NULL,'2026-03-18 09:46:16','2026-03-18 09:46:16'),
(11,'Limpieza Dental','Profilaxis dental y raspado.','visible',NULL,'2026-03-18 09:46:16','2026-03-18 09:46:16'),
(12,'Control Pulgas/Garrapatas','Tratamiento antiparasitario externo.','visible',NULL,'2026-03-18 09:46:16','2026-03-18 09:46:16'),
(13,'Terapia Física','Rehabilitación y fisioterapia.','visible',NULL,'2026-03-18 09:46:16','2026-03-18 09:46:16'),
(14,'Consulta Nutricional','Asesoría dietética personalizada.','visible',NULL,'2026-03-18 09:46:17','2026-03-18 09:46:17'),
(15,'Atención de Urgencias','Servicio 24/7 para emergencias.','visible',NULL,'2026-03-18 09:46:17','2026-03-18 09:46:17');
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tokens_users`
--

DROP TABLE IF EXISTS `tokens_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tokens_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('reclamar','confirmar','recuperar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` datetime NOT NULL,
  `state` enum('expired','used','process') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'process',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tokens_users_token_unique` (`token`),
  KEY `tokens_users_user_id_foreign` (`user_id`),
  CONSTRAINT `tokens_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens_users`
--

LOCK TABLES `tokens_users` WRITE;
/*!40000 ALTER TABLE `tokens_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `tokens_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajadores`
--

DROP TABLE IF EXISTS `trabajadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `trabajadores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trabajadores_user_id_foreign` (`user_id`),
  CONSTRAINT `trabajadores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajadores`
--

LOCK TABLES `trabajadores` WRITE;
/*!40000 ALTER TABLE `trabajadores` DISABLE KEYS */;
INSERT INTO `trabajadores` VALUES
(1,1,NULL,'2026-03-18 09:46:40','2026-03-18 09:46:40'),
(2,2,NULL,'2026-03-18 09:46:40','2026-03-18 09:46:40');
/*!40000 ALTER TABLE `trabajadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('registrado','pendiente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rol_id` bigint unsigned NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_rol_id_foreign` (`rol_id`),
  CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin','0000000000','registrado','admin@example.com',NULL,'$2y$12$0PSH8dqdTkUnalVy5jYjTe8VbUkO72jruL5uq66MnY7jvTOyfHd5i',3,NULL,NULL,'2026-03-18 09:46:38','2026-03-18 09:46:38'),
(2,'Trabajador','0000000001','registrado','trabajador@example.com',NULL,'$2y$12$Nokw/QJKVl/Bb4vFxJ8U.u31PaAEqSfOVvf1J7cpldHvdShJBT4k.',2,NULL,NULL,'2026-03-18 09:46:38','2026-03-18 09:46:38'),
(3,'Cliente','0000000002','registrado','cliente@example.com',NULL,'$2y$12$YROUZ.thrSjtibOEcwCFPe0T3tibpJ4pOW2I/Bc/y4nXWVikEn9ru',1,NULL,NULL,'2026-03-18 09:46:39','2026-03-18 09:46:39'),
(4,'Cliente2','0000000003','registrado','cliente2@example.com',NULL,'$2y$12$eervsKSa6uREscPXrHjZeubdSM9ETSLmbxSx9UXOEtHNOENTZfebS',1,NULL,NULL,'2026-03-18 09:46:39','2026-03-18 09:46:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-18 12:40:20
