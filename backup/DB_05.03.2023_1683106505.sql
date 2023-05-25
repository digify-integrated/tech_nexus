-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: nexusdb
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

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
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_log` (
  `audit_log_id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) NOT NULL,
  `reference_id` int(10) NOT NULL,
  `log` text NOT NULL,
  `changed_by` varchar(255) NOT NULL,
  `changed_at` datetime NOT NULL,
  PRIMARY KEY (`audit_log_id`),
  KEY `audit_log_index_audit_log_id` (`audit_log_id`),
  KEY `audit_log_index_table_name` (`table_name`),
  KEY `audit_log_index_reference_id` (`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_log`
--

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;
INSERT INTO `audit_log` VALUES (1,'users',2,'user_status: Active -> Inactive<br/>','1','2023-04-08 20:33:17'),(2,'users',5,'User created.<br/>email_address: lmicayas@encorefinancials.com<br/>file_as: Administrator<br/>user_status: Active<br/>password_expiry_date: 2022-12-30','1','2023-04-08 21:53:20'),(3,'users',6,'User created.<br/>email_address: lmicayas@encorefinancials.com<br/>file_as: Administrator<br/>user_status: Active<br/>password_expiry_date: 2022-12-30','1','2023-04-08 22:04:22'),(4,'users',7,'User created.<br/>email_address: lmicayas@encorefinancials.com<br/>file_as: Administrator<br/>user_status: Active<br/>password_expiry_date: 2022-12-30','1','2023-04-08 22:07:26'),(5,'users',8,'User created.<br/>email_address: lmicayas@encorefinancials.com<br/>file_as: Administrator<br/>user_status: Active<br/>password_expiry_date: 2022-12-30','1','2023-04-08 22:21:47'),(6,'users',8,'The user 8 has been deleted','1','2023-04-08 22:22:05'),(7,'users',2,'password_expiry_date: 2023-10-07 -> 2022-10-07<br/>','1','2023-04-09 16:49:49'),(8,'users',2,'user_status: Inactive -> Active<br/>','1','2023-04-09 16:50:22'),(9,'users',2,'password_expiry_date: 2022-10-07 -> 2023-10-09<br/>','1','2023-04-09 16:50:33'),(10,'users',2,'Password history created.<br/>user id: 2<br/>email address: ldagulto@encorefinancials.com<br/>password change date: 2023-04-09 16:50:33','0','2023-04-09 16:50:33'),(11,'users',2,'last connection date: 2023-04-08 20:21:32 -> 2023-04-09 17:22:35<br/>','1','2023-04-09 17:22:35'),(12,'users',2,'User created.<br/>dark layout: true','0','2023-04-09 17:22:39'),(13,'users',2,'theme contrast: true -> false<br/>','0','2023-04-09 17:25:32'),(14,'users',2,'theme contrast: false -> true<br/>','0','2023-04-09 17:25:40'),(15,'users',2,'theme contrast: true -> false<br/>','0','2023-04-09 17:25:54'),(16,'users',2,'preset theme: preset-5 -> preset-7<br/>','0','2023-04-09 17:26:14'),(17,'users',2,'preset theme: preset-7 -> preset-1<br/>','0','2023-04-09 17:26:26'),(18,'users',2,'preset theme: preset-1 -> preset-8<br/>','0','2023-04-09 17:26:33'),(19,'users',2,'preset theme: preset-8 -> preset-9<br/>','0','2023-04-09 17:26:35'),(20,'users',2,'preset theme: preset-9 -> preset-8<br/>','0','2023-04-09 17:26:44'),(21,'users',2,'dark layout: true -> false<br/>','0','2023-04-09 17:26:45'),(22,'users',2,'preset theme: preset-8 -> preset-6<br/>','0','2023-04-09 17:26:56'),(23,'users',2,'caption show: false -> true<br/>','0','2023-04-09 17:27:09'),(24,'users',2,'preset theme: preset-6 -> preset-8<br/>','0','2023-04-09 17:40:09'),(25,'users',2,'dark layout: false -> true<br/>','0','2023-04-09 17:40:10'),(26,'users',2,'dark layout: true -> false<br/>','2','2023-04-09 19:00:28'),(27,'users',2,'UI Customization created. <br/><br/>dark layout: true','2','2023-04-09 19:00:55'),(28,'users',2,'caption show: false -> true<br/>','2','2023-04-09 19:21:41'),(29,'users',1,'last connection date: 2023-04-07 20:16:07 -> 2023-04-10 13:19:31<br/>','1','2023-04-10 13:19:31'),(30,'ui_customization_setting',3,'UI Customization created. <br/><br/>dark layout: true','1','2023-04-10 13:22:59'),(31,'ui_customization_setting',3,'dark layout: true -> false<br/>','1','2023-04-10 13:23:30'),(32,'menu_groups',1,'Menu group created. <br/><br/>menu group name: Menu Group<br/>order sequence: 1','1','2023-04-10 14:05:13'),(33,'users',1,'last connection date: 2023-04-10 13:19:31 -> 2023-04-10 18:10:39<br/>','1','2023-04-10 18:10:39'),(34,'menu_groups',2,'Menu group created. <br/><br/>menu group name: Administration','1','2023-04-10 19:22:52'),(35,'menu_groups',2,'The menu group \'2\' has been deleted.','1','2023-04-10 19:23:14'),(36,'menu_groups',1,'The menu group \'1\' has been deleted.','1','2023-04-10 19:23:16'),(37,'menu',1,'Menu created. <br/><br/>menu name: Menu Group<br/>menu group id: 1<br/>order sequence: 1','1','2023-04-10 20:24:47'),(38,'menu',2,'Menu created. <br/><br/>menu name: Menu Item<br/>menu group id: 1<br/>order sequence: 2','1','2023-04-10 20:24:47'),(39,'users',2,'last connection date: 2023-04-09 17:22:35 -> 2023-04-11 08:55:29<br/>','1','2023-04-11 08:55:29'),(40,'ui_customization_setting',2,'dark layout: true -> false<br/>','2','2023-04-11 08:56:32'),(41,'ui_customization_setting',2,'dark layout: false -> true<br/>','2','2023-04-11 08:58:47'),(42,'ui_customization_setting',2,'dark layout: true -> false<br/>','2','2023-04-11 08:58:57'),(43,'ui_customization_setting',2,'dark layout: false -> true<br/>','2','2023-04-11 08:59:00'),(44,'users',2,'password expiry date: 2023-10-09 -> 2022-10-09<br/>','1','2023-04-11 09:03:15'),(45,'users',2,'password expiry date: 2022-10-09 -> 2023-10-11<br/>','1','2023-04-11 09:03:37'),(46,'users',2,'password expiry date: 2023-10-11 -> 1899-12-31<br/>','1','2023-04-11 09:03:43'),(47,'users',2,'password expiry date: 1899-12-31 -> 2023-10-11<br/>','1','2023-04-11 09:04:49'),(48,'users',2,'failed login: 0 -> 1<br/>last failed login: 2023-04-07 19:57:52 -> 2023-04-11 09:05:45<br/>','1','2023-04-11 09:05:45'),(49,'users',2,'failed login: 1 -> 0<br/>','1','2023-04-11 09:05:48'),(50,'users',2,'last connection date: 2023-04-11 08:55:29 -> 2023-04-11 09:05:48<br/>','1','2023-04-11 09:05:48'),(51,'users',1,'failed login: 0 -> 1<br/>','1','2023-04-11 11:08:53'),(52,'users',1,'failed login: 1 -> 0<br/>','1','2023-04-11 11:08:57'),(53,'users',1,'last connection date: 2023-04-10 18:10:39 -> 2023-04-11 11:08:57<br/>','1','2023-04-11 11:08:57'),(54,'ui_customization_setting',3,'dark layout: false -> true<br/>','1','2023-04-11 11:09:02'),(55,'ui_customization_setting',3,'theme contrast: true -> false<br/>','1','2023-04-11 11:09:05'),(56,'ui_customization_setting',3,'theme contrast: false -> true<br/>','1','2023-04-11 11:09:06'),(57,'users',1,'failed login: 0 -> 1<br/>last failed login: 2023-04-11 11:08:53 -> 2023-04-12 09:13:12<br/>','1','2023-04-12 09:13:12'),(58,'users',1,'failed login: 1 -> 0<br/>','1','2023-04-12 09:13:16'),(59,'users',1,'last connection date: 2023-04-11 11:08:57 -> 2023-04-12 09:13:16<br/>','1','2023-04-12 09:13:16'),(60,'ui_customization_setting',3,'dark layout: true -> false<br/>','1','2023-04-12 09:23:17'),(61,'ui_customization_setting',3,'dark layout: false -> true<br/>','1','2023-04-12 09:32:05'),(62,'ui_customization_setting',3,'dark layout: true -> false<br/>','1','2023-04-12 09:32:06'),(63,'ui_customization_setting',3,'dark layout: false -> true<br/>','1','2023-04-12 09:40:40'),(64,'ui_customization_setting',3,'dark layout: true -> false<br/>','1','2023-04-12 09:40:41'),(65,'ui_customization_setting',3,'theme contrast: true -> false<br/>','1','2023-04-12 10:38:01'),(66,'ui_customization_setting',3,'theme contrast: false -> true<br/>','1','2023-04-12 10:38:08'),(67,'ui_customization_setting',3,'theme contrast: true -> false<br/>','1','2023-04-12 10:38:08'),(68,'ui_customization_setting',3,'dark layout: false -> true<br/>','1','2023-04-12 10:38:16'),(69,'ui_customization_setting',3,'theme contrast: false -> true<br/>','1','2023-04-12 10:38:17'),(70,'ui_customization_setting',3,'theme contrast: true -> false<br/>','1','2023-04-12 10:38:18'),(71,'ui_customization_setting',3,'dark layout: true -> false<br/>','1','2023-04-12 10:41:29'),(72,'users',1,'last connection date: 2023-04-12 09:13:16 -> 2023-04-12 13:56:44<br/>','1','2023-04-12 13:56:44'),(73,'ui_customization_setting',3,'preset theme: preset-5 -> preset-8<br/>','1','2023-04-12 15:38:52'),(74,'ui_customization_setting',3,'preset theme: preset-8 -> preset-9<br/>','1','2023-04-12 16:26:41'),(75,'users',1,'failed login: 0 -> 1<br/>last failed login: 2023-04-12 09:13:12 -> 2023-04-13 14:06:37<br/>','1','2023-04-13 14:06:38'),(76,'users',1,'failed login: 1 -> 0<br/>','1','2023-04-13 14:06:44'),(77,'users',1,'last connection date: 2023-04-12 13:56:44 -> 2023-04-13 14:06:44<br/>','1','2023-04-13 14:06:44'),(78,'users',2,'failed login: 0 -> 1<br/>last failed login: 2023-04-11 09:05:45 -> 2023-04-13 15:07:02<br/>','1','2023-04-13 15:07:02'),(79,'users',2,'failed login: 1 -> 2<br/>last failed login: 2023-04-13 15:07:02 -> 2023-04-13 15:07:06<br/>','1','2023-04-13 15:07:06'),(80,'users',1,'last connection date: 2023-04-13 14:06:44 -> 2023-04-13 15:07:13<br/>','1','2023-04-13 15:07:13'),(81,'ui_customization_setting',3,'preset theme: preset-9 -> preset-5<br/>','1','2023-04-13 15:11:09'),(82,'ui_customization_setting',3,'preset theme: preset-5 -> preset-1<br/>','1','2023-04-13 15:11:11'),(83,'users',1,'last connection date: 2023-04-13 15:07:13 -> 2023-04-14 09:34:16<br/>','1','2023-04-14 09:34:16'),(84,'ui_customization_setting',3,'dark layout: false -> true<br/>','1','2023-04-14 09:51:47'),(85,'ui_customization_setting',3,'dark layout: true -> false<br/>','1','2023-04-14 09:51:53'),(86,'users',1,'last connection date: 2023-04-14 09:34:16 -> 2023-04-17 15:15:22<br/>','1','2023-04-17 15:15:22'),(87,'users',1,'failed login: 0 -> 1<br/>last failed login: 2023-04-13 14:06:37 -> 2023-04-18 10:42:44<br/>','1','2023-04-18 10:42:44'),(88,'users',1,'failed login: 1 -> 0<br/>','1','2023-04-18 10:42:49'),(89,'users',1,'last connection date: 2023-04-17 15:15:22 -> 2023-04-18 10:42:49<br/>','1','2023-04-18 10:42:49'),(90,'menu_groups',2,'Menu group created. <br/><br/>menu group name: Human Resources<br/>order sequence: 1','1','2023-04-18 10:54:49'),(91,'menu_groups',2,'order sequence: 1 -> 2<br/>','1','2023-04-18 11:07:54'),(92,'menu_groups',2,'order sequence: 2 -> 1<br/>','1','2023-04-18 11:08:11'),(93,'menu_groups',2,'order sequence: 1 -> 3<br/>','1','2023-04-18 11:08:20'),(94,'menu_groups',2,'menu group name: Human Resources -> Human Resources Module<br/>order sequence: 3 -> 1<br/>','1','2023-04-18 11:08:28'),(95,'menu_groups',2,'Menu Group Name: Human Resources Module -> Human Resources<br/>Order Sequence: 1 -> 2<br/>','1','2023-04-18 15:14:02'),(96,'users',1,'Failed Login: 0 -> 1<br/>Last Failed Login: 2023-04-18 10:42:44 -> 2023-04-18 15:14:13<br/>','1','2023-04-18 15:14:13'),(97,'users',1,'Failed Login: 1 -> 0<br/>','1','2023-04-18 15:14:17'),(98,'users',1,'Last Connection Date: 2023-04-18 10:42:49 -> 2023-04-18 15:14:17<br/>','1','2023-04-18 15:14:17'),(99,'users',1,'Last Connection Date: 2023-04-18 15:14:17 -> 2023-04-19 11:37:42<br/>','1','2023-04-19 11:37:42'),(100,'users',1,'Last Connection Date: 2023-04-19 11:37:42 -> 2023-04-19 15:55:04<br/>','1','2023-04-19 15:55:04'),(101,'users',1,'Last Connection Date: 2023-04-19 15:55:04 -> 2023-04-20 10:26:10<br/>','1','2023-04-20 10:26:10'),(102,'menu_groups',3,'Menu group created. <br/><br/>Menu Group Name: admin<br/>Order Sequence: 1','1','2023-04-20 11:55:42'),(103,'menu_groups',4,'Menu group created. <br/><br/>Menu Group Name: Admin<br/>Order Sequence: 3','1','2023-04-20 12:59:31'),(104,'menu_groups',5,'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 5','1','2023-04-20 13:00:32'),(105,'menu_groups',6,'Menu group created. <br/><br/>Menu Group Name: asd<br/>Order Sequence: 5','1','2023-04-20 13:14:15'),(106,'menu_groups',7,'Menu group created. <br/><br/>Menu Group Name: asd2<br/>Order Sequence: 5','1','2023-04-20 13:14:23'),(107,'users',1,'Last Connection Date: 2023-04-20 10:26:10 -> 2023-04-21 10:56:42<br/>','1','2023-04-21 10:56:42'),(108,'menu_groups',8,'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 4','1','2023-04-21 12:34:59'),(109,'menu_groups',9,'Menu group created. <br/><br/>Menu Group Name: Test<br/>Order Sequence: 1','1','2023-04-21 12:43:59'),(110,'menu_groups',10,'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2','1','2023-04-21 12:44:50'),(111,'menu_groups',11,'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2','1','2023-04-21 12:51:45'),(112,'menu_groups',12,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:02:54'),(113,'menu_groups',13,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:03:03'),(114,'menu_groups',14,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:13:58'),(115,'menu_groups',15,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:21:46'),(116,'menu_groups',16,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:22:32'),(117,'menu_groups',17,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:29:02'),(118,'menu_groups',18,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:29:54'),(119,'menu_groups',19,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:30:26'),(120,'menu_groups',20,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1','1','2023-04-21 14:30:30'),(121,'menu_groups',1,'Menu group created. <br/><br/>Menu Group Name: Administrator<br/>Order Sequence: 1','1','2023-04-21 14:55:24'),(122,'menu_groups',1,'Order Sequence: 1 -> 2<br/>','1','2023-04-21 14:58:36'),(123,'menu_groups',1,'Menu Group Name: Administrator -> Administrators<br/>Order Sequence: 2 -> 1<br/>','1','2023-04-21 22:44:01'),(124,'users',1,'Last Connection Date: 2023-04-21 10:56:42 -> 2023-04-22 09:14:52<br/>','1','2023-04-22 09:14:52'),(125,'users',1,'Last Connection Date: 2023-04-22 09:14:52 -> 2023-04-22 11:18:22<br/>','1','2023-04-22 11:18:22'),(126,'users',1,'Last Connection Date: 2023-04-22 11:18:22 -> 2023-04-24 11:17:24<br/>','1','2023-04-24 11:17:24'),(127,'menu_groups',1,'Order Sequence: 1 -> 2<br/>','1','2023-04-25 16:11:32'),(128,'menu_groups',1,'Order Sequence: 2 -> 3<br/>','1','2023-04-25 16:12:46'),(129,'menu_groups',1,'Order Sequence: 3 -> 4<br/>','1','2023-04-25 16:15:34'),(130,'menu_groups',1,'Order Sequence: 4 -> 5<br/>','1','2023-04-25 16:16:52'),(131,'menu_groups',1,'Order Sequence: 5 -> 6<br/>','1','2023-04-25 16:21:45'),(132,'menu_groups',1,'Order Sequence: 6 -> 7<br/>','1','2023-04-25 16:35:17'),(133,'users',2,'Failed Login: 2 -> 3<br/>Last Failed Login: 2023-04-13 15:07:06 -> 2023-04-29 18:29:11<br/>','1','2023-04-29 18:29:11'),(134,'users',1,'Last Connection Date: 2023-04-24 11:17:24 -> 2023-05-01 08:42:11<br/>','1','2023-05-01 08:42:11'),(135,'users',2,'Failed Login: 3 -> 4<br/>Last Failed Login: 2023-04-29 18:29:11 -> 2023-05-01 08:42:32<br/>','1','2023-05-01 08:42:32'),(136,'users',2,'Failed Login: 4 -> 5<br/>Last Failed Login: 2023-05-01 08:42:32 -> 2023-05-01 08:42:36<br/>','1','2023-05-01 08:42:36'),(137,'users',1,'Failed Login: 0 -> 1<br/>Last Failed Login: 2023-04-18 15:14:13 -> 2023-05-01 08:50:59<br/>','1','2023-05-01 08:50:59'),(138,'users',1,'Failed Login: 1 -> 0<br/>','1','2023-05-01 08:51:03'),(139,'users',1,'Last Connection Date: 2023-05-01 08:42:11 -> 2023-05-01 08:51:03<br/>','1','2023-05-01 08:51:03'),(140,'menu_item',1,'Menu created. <br/><br/>Menu Item Name: Test<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 10:01:09'),(141,'menu_item',2,'Menu created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 11:20:11'),(142,'menu_item',3,'Menu created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 11:20:23'),(143,'menu_item',4,'Menu created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 11:21:11'),(144,'menu_item',5,'Menu created. <br/><br/>Menu Item Name: asd<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 11:25:04'),(145,'menu_item',6,'Menu created. <br/><br/>Menu Item Name: etes<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 11:32:47'),(146,'menu_item',7,'Menu created. <br/><br/>Menu Item Name: Test<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 11:35:15'),(147,'users',1,'Last Connection Date: 2023-05-01 08:51:03 -> 2023-05-01 13:07:21<br/>','1','2023-05-01 13:07:21'),(148,'menu_item',8,'Menu created. <br/><br/>Menu Item Name: Test<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 14:33:13'),(149,'menu_item',9,'Menu created. <br/><br/>Menu Item Name: Test<br/>Menu Group ID: 1<br/>Order Sequence: 2','1','2023-05-01 14:33:16'),(150,'menu_item',10,'Menu created. <br/><br/>Menu Item Name: Test<br/>Menu Group ID: 1<br/>Order Sequence: 3','1','2023-05-01 14:33:23'),(151,'menu_item',1,'Menu Item Name: Test -> 1<br/>Menu Group ID: 1 -> 0<br/>Order Sequence: 1 -> 8<br/>','1','2023-05-01 15:04:27'),(152,'menu_item',2,'Menu Item Name: test -> 1<br/>Menu Group ID: 1 -> 0<br/>Order Sequence: 1 -> 2<br/>','1','2023-05-01 15:05:16'),(153,'menu_item',1,'Menu Group ID: 0 -> 1<br/>','1','2023-05-01 15:07:15'),(154,'menu_item',2,'Menu Group ID: 0 -> 1<br/>','1','2023-05-01 15:07:18'),(155,'menu_item',1,'Menu Item Name: 1 -> test<br/>','1','2023-05-01 15:07:32'),(156,'menu_item',2,'Menu Item Name: 1 -> test2<br/>','1','2023-05-01 15:07:39'),(157,'menu_item',1,'Order Sequence: 8 -> 10<br/>','1','2023-05-01 15:07:43'),(158,'menu_groups',1,'Order Sequence: 7 -> 1<br/>','1','2023-05-01 15:46:42'),(159,'ui_customization_setting',3,'Box Container: true -> false<br/>','1','2023-05-01 15:50:50'),(160,'ui_customization_setting',3,'Caption Show: false -> true<br/>','1','2023-05-01 15:50:54'),(161,'ui_customization_setting',3,'RTL Layout: true -> false<br/>','1','2023-05-01 15:50:59'),(162,'ui_customization_setting',3,'Preset Theme: preset-1 -> preset-8<br/>','1','2023-05-01 15:51:03'),(163,'ui_customization_setting',3,'Theme Contrast: false -> true<br/>','1','2023-05-01 15:51:09'),(164,'menu_groups',1,'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 127','1','2023-05-01 16:12:43'),(165,'ui_customization_setting',3,'Theme Contrast: true -> false<br/>','1','2023-05-01 16:49:59'),(166,'menu_item',1,'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Order Sequence: 1','1','2023-05-01 17:48:56'),(167,'menu_item',1,'URL:  -> <br/>Parent ID: 1 -> 0<br/>','1','2023-05-01 19:34:26'),(168,'users',1,'Last Connection Date: 2023-05-01 13:07:21 -> 2023-05-02 09:35:57<br/>','1','2023-05-02 09:35:57'),(169,'ui_customization_setting',3,'Preset Theme: preset-8 -> preset-9<br/>','1','2023-05-02 09:36:02'),(170,'ui_customization_setting',3,'Preset Theme: preset-9 -> preset-5<br/>','1','2023-05-02 09:36:03'),(171,'ui_customization_setting',3,'Preset Theme: preset-5 -> preset-1<br/>','1','2023-05-02 09:36:05'),(172,'menu_item',1,'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: &lt;i data-feather=&quot;sidebar&quot;&gt;&lt;/i&gt;<br/>Order Sequence: 1','1','2023-05-02 11:58:11'),(173,'menu_item',1,'Menu Item Icon: &lt;i data-feather=&quot;sidebar&quot;&gt;&lt;/i&gt; -> sidebar<br/>','1','2023-05-02 11:58:33'),(174,'menu_item',1,'Menu Item Icon: sidebar -> &lt;i data-feather=&quot;sidebar&quot;&gt;&lt;/i&gt;<br/>','1','2023-05-02 11:59:50'),(175,'menu_item',1,'Menu Item Icon: &lt;i data-feather=&quot;sidebar&quot;&gt;&lt;/i&gt; -> &lt;svg class=&quot;pc-icon&quot;&gt;&lt;use xlink:href=&quot;#custom-row-vertical&quot;&gt;&lt;/use&gt;&lt;/svg&gt;<br/>','1','2023-05-02 12:39:40'),(176,'menu_item',1,'Menu Item Icon: &lt;svg class=&quot;pc-icon&quot;&gt;&lt;use xlink:href=&quot;#custom-row-vertical&quot;&gt;&lt;/use&gt;&lt;/svg&gt; -> &lt;i data-feather=&quot;sidebar&quot;&gt;&lt;/i&gt;<br/>','1','2023-05-02 12:39:53'),(177,'users',1,'Failed Login: 0 -> 1<br/>Last Failed Login: 2023-05-01 08:50:59 -> 2023-05-03 09:22:39<br/>','1','2023-05-03 09:22:39'),(178,'users',1,'Failed Login: 1 -> 0<br/>','1','2023-05-03 09:22:42'),(179,'users',1,'Last Connection Date: 2023-05-02 09:35:57 -> 2023-05-03 09:22:42<br/>','1','2023-05-03 09:22:42'),(180,'role',2,'Role created. <br/><br/>Role Name: Employee<br/>Role Description: Employee<br/>Assignable: 1','1','2023-05-03 15:28:15');
/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_access_right`
--

DROP TABLE IF EXISTS `menu_access_right`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_access_right` (
  `menu_item_id` int(10) NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `read_access` tinyint(1) NOT NULL,
  `write_access` tinyint(1) NOT NULL,
  `create_access` tinyint(1) NOT NULL,
  `delete_access` tinyint(1) NOT NULL,
  `last_log_by` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_access_right`
--

LOCK TABLES `menu_access_right` WRITE;
/*!40000 ALTER TABLE `menu_access_right` DISABLE KEYS */;
INSERT INTO `menu_access_right` VALUES (1,1,1,1,1,1,1),(2,1,1,1,1,1,1);
/*!40000 ALTER TABLE `menu_access_right` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_groups`
--

DROP TABLE IF EXISTS `menu_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_groups` (
  `menu_group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_group_name` varchar(100) NOT NULL,
  `order_sequence` tinyint(10) NOT NULL,
  `last_log_by` int(10) NOT NULL,
  PRIMARY KEY (`menu_group_id`),
  KEY `menu_groups_index_menu_group_id` (`menu_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_groups`
--

LOCK TABLES `menu_groups` WRITE;
/*!40000 ALTER TABLE `menu_groups` DISABLE KEYS */;
INSERT INTO `menu_groups` VALUES (1,'Administration',127,1);
/*!40000 ALTER TABLE `menu_groups` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER menu_groups_trigger_insert
AFTER INSERT ON menu_groups
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Menu group created. <br/>';

    IF NEW.menu_group_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Menu Group Name: ", NEW.menu_group_name);
    END IF;

    IF NEW.order_sequence <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Order Sequence: ", NEW.order_sequence);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('menu_groups', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER menu_groups_trigger_update
AFTER UPDATE ON menu_groups
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.menu_group_name <> OLD.menu_group_name THEN
        SET audit_log = CONCAT(audit_log, "Menu Group Name: ", OLD.menu_group_name, " -> ", NEW.menu_group_name, "<br/>");
    END IF;

    IF NEW.order_sequence <> OLD.order_sequence THEN
        SET audit_log = CONCAT(audit_log, "Order Sequence: ", OLD.order_sequence, " -> ", NEW.order_sequence, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('menu_groups', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `menu_item`
--

DROP TABLE IF EXISTS `menu_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_item` (
  `menu_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_item_name` varchar(100) NOT NULL,
  `menu_group_id` int(10) unsigned NOT NULL,
  `menu_item_url` varchar(50) DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `menu_item_icon` varchar(150) DEFAULT NULL,
  `order_sequence` tinyint(10) NOT NULL,
  `last_log_by` int(10) NOT NULL,
  PRIMARY KEY (`menu_item_id`),
  KEY `menu_item_index_menu_item_id` (`menu_item_id`),
  KEY `menu_group_id` (`menu_group_id`),
  CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`menu_group_id`) REFERENCES `menu_groups` (`menu_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_item`
--

LOCK TABLES `menu_item` WRITE;
/*!40000 ALTER TABLE `menu_item` DISABLE KEYS */;
INSERT INTO `menu_item` VALUES (1,'User Interface',1,'',0,'&lt;i data-feather=&quot;sidebar&quot;&gt;&lt;/i&gt;',1,1);
/*!40000 ALTER TABLE `menu_item` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER menu_item_trigger_insert
AFTER INSERT ON menu_item
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Menu item created. <br/>';

    IF NEW.menu_item_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Menu Item Name: ", NEW.menu_item_name);
    END IF;

    IF NEW.menu_group_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Menu Group ID: ", NEW.menu_group_id);
    END IF;

    IF NEW.menu_item_url <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>URL: ", NEW.menu_item_url);
    END IF;

    IF NEW.parent_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Parent ID: ", NEW.parent_id);
    END IF;

    IF NEW.menu_item_icon <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Menu Item Icon: ", NEW.menu_item_icon);
    END IF;

    IF NEW.order_sequence <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Order Sequence: ", NEW.order_sequence);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('menu_item', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER menu_item_trigger_update
AFTER UPDATE ON menu_item
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.menu_item_name <> OLD.menu_item_name THEN
        SET audit_log = CONCAT(audit_log, "Menu Item Name: ", OLD.menu_item_name, " -> ", NEW.menu_item_name, "<br/>");
    END IF;

    IF NEW.menu_group_id <> OLD.menu_group_id THEN
        SET audit_log = CONCAT(audit_log, "Menu Group ID: ", OLD.menu_group_id, " -> ", NEW.menu_group_id, "<br/>");
    END IF;

    IF NEW.menu_item_url <> OLD.parent_id THEN
        SET audit_log = CONCAT(audit_log, "URL: ", OLD.menu_item_url, " -> ", NEW.menu_item_url, "<br/>");
    END IF;

    IF NEW.parent_id <> OLD.parent_id THEN
        SET audit_log = CONCAT(audit_log, "Parent ID: ", OLD.parent_id, " -> ", NEW.parent_id, "<br/>");
    END IF;

    IF NEW.menu_item_icon <> OLD.menu_item_icon THEN
        SET audit_log = CONCAT(audit_log, "Menu Item Icon: ", OLD.menu_item_icon, " -> ", NEW.menu_item_icon, "<br/>");
    END IF;

    IF NEW.order_sequence <> OLD.order_sequence THEN
        SET audit_log = CONCAT(audit_log, "Order Sequence: ", OLD.order_sequence, " -> ", NEW.order_sequence, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('menu_item', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `password_history`
--

DROP TABLE IF EXISTS `password_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_history` (
  `password_history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `password_change_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`password_history_id`),
  KEY `password_history_index_password_history_id` (`password_history_id`),
  KEY `password_history_index_user_id` (`user_id`),
  KEY `password_history_index_email_address` (`email_address`),
  CONSTRAINT `password_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `password_history_ibfk_2` FOREIGN KEY (`email_address`) REFERENCES `users` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_history`
--

LOCK TABLES `password_history` WRITE;
/*!40000 ALTER TABLE `password_history` DISABLE KEYS */;
INSERT INTO `password_history` VALUES (1,2,'ldagulto@encorefinancials.com','HtRcNvyfnd4lXmZmkOihaFZ1c2QMlupV04mk1KEl%2Bj4%3D','2023-04-11 09:03:37'),(2,2,'ldagulto@encorefinancials.com','GgukHef0woUl4E8Dso5NUoA%2FW9Mnae5LVVBj6oPj8WQ%3D','2023-04-11 09:04:49');
/*!40000 ALTER TABLE `password_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) NOT NULL,
  `role_description` varchar(200) NOT NULL,
  `assignable` tinyint(1) NOT NULL,
  `last_log_by` int(10) NOT NULL,
  PRIMARY KEY (`role_id`),
  KEY `role_index_role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Administrator','Administrator',1,1),(2,'Employee','Employee',1,1);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER role_trigger_insert
AFTER INSERT ON role
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Role created. <br/>';

    IF NEW.role_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Role Name: ", NEW.role_name);
    END IF;

    IF NEW.role_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Role Description: ", NEW.role_description);
    END IF;

    IF NEW.assignable <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Assignable: ", NEW.assignable);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('role', NEW.role_id, audit_log, NEW.last_log_by, NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER role_trigger_update
AFTER UPDATE ON role
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.role_name <> OLD.role_name THEN
        SET audit_log = CONCAT(audit_log, "Role Name: ", OLD.role_name, " -> ", NEW.role_name, "<br/>");
    END IF;

    IF NEW.role_description <> OLD.role_description THEN
        SET audit_log = CONCAT(audit_log, "Role Description: ", OLD.role_description, " -> ", NEW.role_description, "<br/>");
    END IF;

    IF NEW.assignable <> OLD.assignable THEN
        SET audit_log = CONCAT(audit_log, "Assignable: ", OLD.assignable, " -> ", NEW.assignable, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('role', NEW.role_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_users` (
  `role_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `last_log_by` int(10) NOT NULL,
  KEY `role_users_index_role_id` (`role_id`),
  KEY `role_users_index_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_users`
--

LOCK TABLES `role_users` WRITE;
/*!40000 ALTER TABLE `role_users` DISABLE KEYS */;
INSERT INTO `role_users` VALUES (1,1,1);
/*!40000 ALTER TABLE `role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_action`
--

DROP TABLE IF EXISTS `system_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_action` (
  `system_action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_action_name` varchar(100) NOT NULL,
  `last_log_by` int(10) NOT NULL,
  PRIMARY KEY (`system_action_id`),
  KEY `system_action_index_system_action_id` (`system_action_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_action`
--

LOCK TABLES `system_action` WRITE;
/*!40000 ALTER TABLE `system_action` DISABLE KEYS */;
INSERT INTO `system_action` VALUES (1,'Assign Menu Item Role Access',1);
/*!40000 ALTER TABLE `system_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_action_access_rights`
--

DROP TABLE IF EXISTS `system_action_access_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_action_access_rights` (
  `system_action_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_action_access_rights`
--

LOCK TABLES `system_action_access_rights` WRITE;
/*!40000 ALTER TABLE `system_action_access_rights` DISABLE KEYS */;
INSERT INTO `system_action_access_rights` VALUES (1,1);
/*!40000 ALTER TABLE `system_action_access_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ui_customization_setting`
--

DROP TABLE IF EXISTS `ui_customization_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ui_customization_setting` (
  `ui_customization_setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `theme_contrast` varchar(15) DEFAULT NULL,
  `caption_show` varchar(15) DEFAULT NULL,
  `preset_theme` varchar(15) DEFAULT NULL,
  `dark_layout` varchar(15) DEFAULT NULL,
  `rtl_layout` varchar(15) DEFAULT NULL,
  `box_container` varchar(15) DEFAULT NULL,
  `last_log_by` int(10) NOT NULL,
  PRIMARY KEY (`ui_customization_setting_id`),
  KEY `ui_customization_setting_index_ui_customization_setting_id` (`ui_customization_setting_id`),
  KEY `ui_customization_setting_index_user_id` (`user_id`),
  KEY `ui_customization_setting_index_email_address` (`email_address`),
  CONSTRAINT `ui_customization_setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `ui_customization_setting_ibfk_2` FOREIGN KEY (`email_address`) REFERENCES `users` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ui_customization_setting`
--

LOCK TABLES `ui_customization_setting` WRITE;
/*!40000 ALTER TABLE `ui_customization_setting` DISABLE KEYS */;
INSERT INTO `ui_customization_setting` VALUES (2,2,'ldagulto@encorefinancials.com','true','true',NULL,'true',NULL,NULL,2),(3,1,'admin@encorefinancials.com','false','true','preset-1','false','false','false',1);
/*!40000 ALTER TABLE `ui_customization_setting` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER ui_customization_setting_trigger_insert
AFTER INSERT ON ui_customization_setting
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'UI Customization created. <br/>';

    IF NEW.theme_contrast <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Theme Contrast: ", NEW.theme_contrast);
    END IF;

    IF NEW.caption_show <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Caption Show: ", NEW.caption_show);
    END IF;

    IF NEW.preset_theme <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Preset Theme: ", NEW.preset_theme);
    END IF;

    IF NEW.dark_layout <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Dark Layout: ", NEW.dark_layout);
    END IF;

    IF NEW.rtl_layout <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>RTL Layout: ", NEW.rtl_layout);
    END IF;

    IF NEW.box_container <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Box Container: ", NEW.box_container);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('ui_customization_setting', NEW.ui_customization_setting_id, audit_log, NEW.last_log_by, NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER ui_customization_setting_trigger_update
AFTER UPDATE ON ui_customization_setting
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.theme_contrast <> OLD.theme_contrast THEN
        SET audit_log = CONCAT(audit_log, "Theme Contrast: ", OLD.theme_contrast, " -> ", NEW.theme_contrast, "<br/>");
    END IF;

    IF NEW.caption_show <> OLD.caption_show THEN
        SET audit_log = CONCAT(audit_log, "Caption Show: ", OLD.caption_show, " -> ", NEW.caption_show, "<br/>");
    END IF;

    IF NEW.preset_theme <> OLD.preset_theme THEN
        SET audit_log = CONCAT(audit_log, "Preset Theme: ", OLD.preset_theme, " -> ", NEW.preset_theme, "<br/>");
    END IF;

    IF NEW.dark_layout <> OLD.dark_layout THEN
        SET audit_log = CONCAT(audit_log, "Dark Layout: ", OLD.dark_layout, " -> ", NEW.dark_layout, "<br/>");
    END IF;

    IF NEW.rtl_layout <> OLD.rtl_layout THEN
        SET audit_log = CONCAT(audit_log, "RTL Layout: ", OLD.rtl_layout, " -> ", NEW.rtl_layout, "<br/>");
    END IF;

    IF NEW.box_container <> OLD.box_container THEN
        SET audit_log = CONCAT(audit_log, "Box Container: ", OLD.box_container, " -> ", NEW.box_container , "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('ui_customization_setting', NEW.ui_customization_setting_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_address` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `file_as` varchar(300) NOT NULL,
  `user_status` char(10) NOT NULL,
  `password_expiry_date` date NOT NULL,
  `failed_login` tinyint(1) NOT NULL DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `last_connection_date` datetime DEFAULT NULL,
  `last_log_by` int(10) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_address` (`email_address`),
  KEY `users_index_email_address` (`email_address`),
  KEY `users_index_user_status` (`user_status`),
  KEY `users_index_password_expiry_date` (`password_expiry_date`),
  KEY `users_index_last_connection_date` (`last_connection_date`),
  KEY `users_index_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@encorefinancials.com','W5hvx4P278F8q50uZe2YFif%2ByRDeSeNaainzl5K9%2BQM%3D','Administrator','Active','2023-10-06',0,'2023-05-03 09:22:39','2023-05-03 09:22:42',1),(2,'ldagulto@encorefinancials.com','GgukHef0woUl4E8Dso5NUoA%2FW9Mnae5LVVBj6oPj8WQ%3D','Lawrence D. Agulto','Active','2023-10-11',5,'2023-05-01 08:42:36','2023-04-11 09:05:48',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER users_trigger_insert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'User created. <br/>';

    IF NEW.email_address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Address: ", NEW.email_address);
    END IF;

    IF NEW.file_as <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File As: ", NEW.file_as);
    END IF;

    IF NEW.user_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>User Status: ", NEW.user_status);
    END IF;

    IF NEW.password_expiry_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Password Expiry Date: ", NEW.password_expiry_date);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('users', NEW.user_id, audit_log, NEW.last_log_by, NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER users_trigger_update
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.user_status <> OLD.user_status THEN
        SET audit_log = CONCAT(audit_log, "User Status: ", OLD.user_status, " -> ", NEW.user_status, "<br/>");
    END IF;

    IF NEW.password_expiry_date <> OLD.password_expiry_date THEN
        SET audit_log = CONCAT(audit_log, "Password Expiry Date: ", OLD.password_expiry_date, " -> ", NEW.password_expiry_date, "<br/>");
    END IF;

    IF NEW.failed_login <> OLD.failed_login THEN
        SET audit_log = CONCAT(audit_log, "Failed Login: ", OLD.failed_login, " -> ", NEW.failed_login, "<br/>");
    END IF;

    IF NEW.last_failed_login <> OLD.last_failed_login THEN
        SET audit_log = CONCAT(audit_log, "Last Failed Login: ", OLD.last_failed_login, " -> ", NEW.last_failed_login, "<br/>");
    END IF;

    IF NEW.last_connection_date <> OLD.last_connection_date THEN
        SET audit_log = CONCAT(audit_log, "Last Connection Date: ", OLD.last_connection_date, " -> ", NEW.last_connection_date, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('users', NEW.user_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping routines for database 'nexusdb'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_menu_access_rights` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_menu_access_rights`(IN p_user_id INT(10), IN p_menu_item_id INT(10), IN p_access_type VARCHAR(10))
BEGIN
	IF p_access_type = 'read' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where read_access = '1' AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'write' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where write_access = '1' AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'create' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where create_access = '1' AND menu_item_id = menu_item_id);
    ELSE
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where delete_access = '1' AND menu_item_id = menu_item_id);
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_menu_groups_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_menu_groups_exist`(IN p_menu_group_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM menu_groups
    WHERE menu_group_id = p_menu_group_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_menu_item_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_menu_item_exist`(IN p_menu_item_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM menu_item
    WHERE menu_item_id = p_menu_item_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_system_action_access_rights` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_system_action_access_rights`(IN p_user_id INT(10), IN p_system_action_id INT(10))
BEGIN
	SELECT COUNT(role_id) AS TOTAL
    FROM role_users
    WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM system_action_access_rights where system_action_id = p_system_action_id);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_ui_customization_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_ui_customization_setting_exist`(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT COUNT(*) AS total
    FROM ui_customization_setting
    WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_user_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_user_exist`(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT COUNT(*) AS total
    FROM users
    WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_menu_groups` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_menu_groups`(IN p_menu_group_id INT(10))
BEGIN
    DELETE FROM menu_groups WHERE menu_group_id = p_menu_group_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_menu_item` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_menu_item`(IN p_menu_item_id INT(10))
BEGIN
    DELETE FROM menu_item WHERE menu_item_id = p_menu_item_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_user`(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
	DELETE FROM users 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `duplicate_menu_groups` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicate_menu_groups`(IN p_menu_group_id INT(10), IN p_last_log_by INT(10), OUT p_new_menu_group_id INT(10))
BEGIN
    DECLARE p_menu_group_name VARCHAR(255);
    DECLARE p_order_sequence INT(10);
    
    SELECT menu_group_name, order_sequence 
    INTO p_menu_group_name, p_order_sequence 
    FROM menu_groups 
    WHERE menu_group_id = p_menu_group_id;
    
    INSERT INTO menu_groups (menu_group_name, order_sequence, last_log_by) 
    VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
    
    SET p_new_menu_group_id = LAST_INSERT_ID();
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `duplicate_menu_item` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicate_menu_item`(IN `p_menu_item_id` INT(10), IN `p_last_log_by` INT(10), OUT `p_new_menu_item_id` INT(10))
BEGIN
    DECLARE p_menu_item_name VARCHAR(255);
    DECLARE p_menu_group_id INT(10);
    DECLARE p_menu_item_url VARCHAR(50);
    DECLARE p_parent_id INT(10);
    DECLARE p_menu_item_icon VARCHAR(150);
    DECLARE p_order_sequence TINYINT(10);
    
    SELECT menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence 
    INTO p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence 
    FROM menu_item 
    WHERE menu_item_id = p_menu_item_id;
    
    INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) 
    VALUES(p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
    
    SET p_new_menu_item_id = LAST_INSERT_ID();
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_menu_groups_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_menu_groups_details`(IN p_menu_group_id INT(10))
BEGIN
    SELECT menu_group_name, order_sequence, last_log_by
	FROM menu_groups 
	WHERE menu_group_id = p_menu_group_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_menu_item_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_menu_item_details`(IN p_menu_item_id INT(10))
BEGIN
    SELECT menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by
	FROM menu_item 
	WHERE menu_item_id = p_menu_item_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_role_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_role_details`(IN p_role_id INT(10))
BEGIN
    SELECT role_name, role_description, assignable, last_log_by
	FROM role 
	WHERE role_id = p_role_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_role_menu_access_rights` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_role_menu_access_rights`(IN p_menu_item_id INT(10), IN p_role_id INT(10))
BEGIN
    SELECT read_access, write_access, create_access, delete_access
    FROM menu_access_right 
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ui_customization_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ui_customization_setting_details`(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT ui_customization_setting_id, user_id, email_address, theme_contrast, caption_show, preset_theme, dark_layout, rtl_layout, box_container, last_log_by
	FROM ui_customization_setting 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_user_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_details`(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
	SELECT user_id, email_address, password, file_as, user_status, password_expiry_date, failed_login, last_failed_login, last_connection_date, last_log_by
	FROM users 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_user_password_history_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_password_history_details`(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT password 
	FROM password_history 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_menu_groups` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_menu_groups`(IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10), OUT p_menu_group_id INT(10))
BEGIN
    INSERT INTO menu_groups (menu_group_name, order_sequence, last_log_by) 
	VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
	
    SET p_menu_group_id = LAST_INSERT_ID();
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_menu_item` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_menu_item`(IN `p_menu_item_name` VARCHAR(100), IN `p_menu_group_id` INT(10), IN `p_menu_item_url` VARCHAR(50), IN `p_parent_id` INT(10), IN `p_menu_item_icon` VARCHAR(150), IN `p_order_sequence` TINYINT(10), IN `p_last_log_by` INT(10), OUT `p_menu_item_id` INT(10))
BEGIN
    INSERT INTO menu_item (menu_item_name, menu_group_id, parent_id, menu_item_icon, order_sequence, last_log_by) 
	VALUES(p_menu_item_name, p_menu_group_id, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
	
    SET p_menu_item_id = LAST_INSERT_ID();
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_password_history` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_password_history`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_password VARCHAR(500))
BEGIN
	INSERT INTO password_history (user_id, email_address, password) 
	VALUES(p_user_id, p_email_address, p_password);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_ui_customization_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_ui_customization_setting`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
BEGIN
	IF p_type = 'theme_contrast' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, theme_contrast, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'caption_show' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, caption_show, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'preset_theme' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, preset_theme, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'dark_layout' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, dark_layout, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'rtl_layout' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, rtl_layout, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSE
        INSERT INTO ui_customization_setting (user_id, email_address, box_container, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user`(IN p_email_address VARCHAR(100), IN p_password VARCHAR(500), IN p_file_as VARCHAR (300), IN p_password_expiry_date DATE)
BEGIN
	INSERT INTO users (email_address, password, file_as, user_status, password_expiry_date, failed_login) 
	VALUES(p_email_address, p_password, p_file_as, "Inactive", p_password_expiry_date, 0);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_menu_groups` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_menu_groups`(IN p_menu_group_id INT(10), IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10))
BEGIN
	UPDATE menu_groups
        SET menu_group_name = p_menu_group_name,
        order_sequence = p_order_sequence,
        last_log_by = p_last_log_by
       	WHERE menu_group_id = p_menu_group_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_menu_item` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_menu_item`(IN `p_menu_item_id` INT(10), IN `p_menu_item_name` VARCHAR(100), IN `p_menu_group_id` INT(10), IN `p_menu_item_url` VARCHAR(50), IN `p_parent_id` INT(10), IN `p_menu_item_icon` VARCHAR(150), IN `p_order_sequence` TINYINT(10), IN `p_last_log_by` INT(10))
BEGIN
	UPDATE menu_item
        SET menu_item_name = p_menu_item_name,
        menu_group_id = p_menu_group_id,
        menu_item_url = p_menu_item_url,
        parent_id = p_parent_id,
        menu_item_icon = p_menu_item_icon,
        order_sequence = p_order_sequence,
        last_log_by = p_last_log_by
       	WHERE menu_item_id = p_menu_item_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_ui_customization_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_ui_customization_setting`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
BEGIN
	IF p_type = 'theme_contrast' THEN
        UPDATE ui_customization_setting
        SET theme_contrast = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'caption_show' THEN
        UPDATE ui_customization_setting
        SET caption_show = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'preset_theme' THEN
        UPDATE ui_customization_setting
        SET preset_theme = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'dark_layout' THEN
        UPDATE ui_customization_setting
        SET dark_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'rtl_layout' THEN
        UPDATE ui_customization_setting
        SET rtl_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE ui_customization_setting
        SET box_container = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_password VARCHAR(500), IN p_file_as VARCHAR (300), IN p_password_expiry_date DATE)
BEGIN
	IF p_password IS NOT NULL AND p_password <> '' THEN
        UPDATE users
        SET file_as = p_file_as, password = p_password, password_expiry_date = p_password_expiry_date
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE users
        SET file_as = p_file_as
      	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_last_connection` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_last_connection`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), p_last_connection_date DATETIME)
BEGIN
	UPDATE users 
	SET last_connection_date = p_last_connection_date
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_login_attempt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_login_attempt`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_login_attempt TINYINT(1), IN p_last_failed_attempt_date DATETIME)
BEGIN
    IF p_login_attempt > 0 THEN
        UPDATE users
        SET failed_login = p_login_attempt,
            last_failed_login = p_last_failed_attempt_date
        WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE users
        SET failed_login = p_login_attempt
        WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_password` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_password`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), p_password VARCHAR(500), p_password_expiry_date DATE)
BEGIN
	UPDATE users 
	SET PASSWORD = p_password, PASSWORD_EXPIRY_DATE = p_password_expiry_date
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-03 17:35:05
