-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: zgroupztrack
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `usuarios1`
--

DROP TABLE IF EXISTS `usuarios1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) DEFAULT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `nombres` varchar(255) DEFAULT NULL,
  `estado` int DEFAULT '1',
  `permiso` int DEFAULT '2',
  `correo` varchar(255) DEFAULT 'prueba@gmail.com',
  `password` varchar(255) DEFAULT NULL,
  `ultimo_acceso` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios1`
--

LOCK TABLES `usuarios1` WRITE;
/*!40000 ALTER TABLE `usuarios1` DISABLE KEYS */;
INSERT INTO `usuarios1` VALUES (1,'zgroup','Central','Zgroup',1,1,'admin@zgroup.pe','$2y$10$vS5ajG877UWuW1dFDbA2ZehRJk0MKD8G/5gnlfauELXVYOQ6pk5y6','2023-02-21 06:21:25','2023-02-21 06:21:25','2023-02-28 01:24:59'),(2,'prueba','permisos','sin ',0,2,'permiso@p.com','$2y$10$WhTYwPzN39/.HrgMQzOqFeQ5xZ4Z7tt/9veujPN2p1Z0SpfV/KtYm','2023-03-31 13:37:25','2023-03-31 13:37:25','2023-03-31 13:37:25'),(4,'NAZCA','Central','Nazca',1,2,'nazca@zgrup.com','$2y$10$vXmYTCsZS/cwqDgzy3/jb.hS8OmAGRsx8uaL4pqjID.NIuZ7N4zCu','2023-04-04 22:57:09','2023-04-04 22:57:09','2023-04-04 22:57:09'),(5,'CENCOSUD','Cencosud','Cencosud',1,2,'cencosud@zgroup.com','$2y$10$EX3yKP1bPwBRWFwBxp.QyeNw3K2ClAqvghATUYl/JeA3rr71PA/R2','2023-04-18 14:04:34','2023-04-18 14:04:34','2023-04-18 14:04:34'),(6,'PLAZAVENTANILLA','VENTANILLA','PLAZA VEA',1,2,'plazaveaventanilla@zgroup.pe','$2y$10$3RTlV9vgmhjo3SvgVwUscOeOay7XqD0zvb2NKI2enEZzCLSVrEZj.','2023-04-21 22:25:26','2023-04-21 22:25:26','2023-04-21 22:26:34'),(7,'RIPENER','PRUEBA','RIPENER',1,2,'ripener@zgroup.com','$2y$10$/6ukaQynWxgxckHEHAh9buOr1hx.En8xnxe6vRJl9UXVtIGssdRHK','2023-05-04 23:04:54','2023-05-04 23:04:54','2023-05-04 23:04:54'),(8,'APEEL','Central','APEEL',1,2,'apeel@zgroup.com.pe','$2y$10$EtGtDyyTLpFfMW7RWb7Pm.V0pXd7XH60V1lUt/NquzUx6KAah1QUu','2023-05-08 21:44:13','2023-05-08 21:44:13','2023-05-08 21:44:13'),(11,'wonderful','CENTRAL','WONDERFUL',1,2,'wonderful@zgroup.com','$2y$10$ReoaaSlEg1VIo1.fTJjNv.ETwkPQ9byiEt7vCPQPpCbkS8VQy/.ym','2023-05-19 22:38:11','2023-05-19 22:38:11','2023-05-19 22:38:11'),(12,'BEFROST','CENTRAL','BEFROST',1,2,'befrost@zgroup.com.pe','$2y$10$OI5zG/f5zmVBfCFbQbpSJO6ENFxBxxuZ7xUrJlhlTRGSCd4G6/cLa','2023-05-21 23:02:21','2023-05-21 23:02:21','2023-05-21 23:02:21'),(13,'cliente13','CENTRAL','CLIENTE13',1,2,'cliente13@zgroup.com','$2y$10$4xUIwt2R5y1mVt67r20u.u82r7zlDi9AkQY9SsGthp8PoQKhlP6lK','2023-05-26 09:28:43','2023-05-26 09:28:43','2023-05-26 09:28:43'),(15,'MULTILOG','Multilog','Multilog',1,2,'multilog@zgroup.com.pe','$2y$10$2tl3cAygKzmeTT0w.bwWj./TlaQTs7y8DO7ygnlivN.XydTMb9wdi','2023-06-01 18:49:57','2023-06-01 18:49:57','2023-06-01 18:49:57'),(16,'JOCYMAS ','Jocymas','Jocymas',1,2,'jocymas@zgroup.com.pe','$2y$10$z8mqY2IKgs1ZCY27cbIXlOyWAYhrFBy/VpXQf4tXMFu1IUmlmxo8K','2023-06-06 16:12:23','2023-06-06 16:12:23','2023-06-06 16:12:23'),(17,'TRANSPORTE','CENTRAL','ZGROUP',1,2,'transporte@zgroup.com.pe','$2y$10$Or5GtcavfoZBdKvYD20E1Om9h3eNXV5CS0c/IEl/3sxOSMapXVR2i','2023-06-24 17:14:13','2023-06-24 17:14:13','2023-06-24 17:15:17'),(18,'SUPERUSUARIO','CENTRAL','ZGROUP',1,1,'super@zgroup.com.pe','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918','2023-06-26 20:55:01','2023-06-26 20:55:01','2023-06-26 20:55:01'),(19,'SENATI_INDP','SENATI','SENATI',1,2,'senati_indp@zgroup.com.pe','$2y$10$43l/jIm5/ZkgU7JsTq2Qmub3NIwALWVz8lV5GRlTj6lj/RwyRorne','2023-07-05 16:36:04','2023-07-05 16:36:04','2023-07-05 16:36:04'),(20,'PACIFIC_IQF','CHINCHA','PACIFIC IQF',1,2,'pacific_iqf@zgroup.com.pe','$2y$10$KUc2PVSEnZ3XoS.CeaQBCuBAUbzQGizSUGtIbBDZ4fbkzOjgn8sG6','2023-07-11 16:09:32','2023-07-11 16:09:32','2023-07-11 16:09:32'),(21,'CAMPOSOL_CHAO','CHAO','CAMPOSOL',1,2,'camposol_chao@zgroup.com.pe','$2y$10$MU/M0BUJjEXQSiNHaLAUTerKQ812oDluxYzTnfILgQTLcbPkX1kcm','2023-07-13 13:40:21','2023-07-13 13:40:21','2023-07-13 13:40:21'),(22,'brokmar','Transporte','Brokmar',1,2,'vramirez@brokmartransporte.com','$2y$10$cYou.7qDFGW0.g6qOmkw8ODnPpgg82kdBpDxNQ1WRodc/z4KDVLMe','2023-08-14 19:59:18','2023-08-14 19:59:18','2023-08-14 19:59:18'),(23,'APF','APF','APF',1,2,'apf@zgroup.com.pe','$2y$10$oW9ILo70DJ6rtSGlLMVZFuRAD/tKYeluKjH722NV4Q7Wvcz54Ol3q','2023-09-23 15:07:34','2023-09-23 15:07:34','2023-09-23 15:07:34'),(24,'INKAPACKING','Perú','PLANTA INKA-PACKING',1,2,'inkapacking@zgroup.com.pe','$2y$10$ZVvL..uTtyjBj8GRGaHp9OjPy/lNZb/mYZW6doeGCoFZPHIVgs4yy','2023-10-31 19:03:19','2023-10-31 19:03:19','2023-11-28 15:21:07'),(25,'PACHAMAMA','FARMS','PACHAMAMA',1,2,'pachamama@zgroup.com.pe','$2y$10$vw8/cZnQGYLOaftZuFJrw.Mk3kxu3DTXMTbwp9eP3SRhyPKZEmXV.','2023-11-02 22:16:54','2023-11-02 22:16:54','2023-11-02 22:16:54'),(26,'SAASA','PERÚ','SAASA',1,2,'saasa@zgroup.com.pe','$2y$10$gzYD8/0/aV96FUvdfraq8e4Cfep0F8B.uj5IVYDcVgel3.XZjgbUq','2023-11-13 13:51:51','2023-11-13 13:51:51','2023-11-13 13:51:51'),(27,'CORPORACIONR','DE RESTAURANTES','CORPORACION PERUANA',1,2,'corporacionr@zgroup.com.pe','$2y$10$5KYiQF7kFAUQBmsTMqPhm.jlAW1WSg1Al67xSaKZBU4su75N3k62C','2023-11-21 15:24:35','2023-11-21 15:24:35','2023-11-21 15:24:35');
/*!40000 ALTER TABLE `usuarios1` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-10 16:14:44
