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
  `field_name` varchar(255) NOT NULL,
  `old_value` text NOT NULL,
  `new_value` text NOT NULL,
  `changed_by` varchar(255) NOT NULL,
  `changed_at` datetime NOT NULL,
  PRIMARY KEY (`audit_log_id`),
  KEY `audit_log_index_audit_log_id` (`audit_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_log`
--

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;
INSERT INTO `audit_log` VALUES (1,'users','file_as','Lawrence De Vera Agulto','Lawrence D. Agulto','root@localhost','2023-04-07 23:59:32'),(2,'users','file_as','Lawrence D. Agulto','Lawrence DV. Agulto','root@localhost','2023-04-08 00:03:28'),(3,'users','file_as','Lawrence DV. Agulto','Lawrence D. Agulto','nexus@localhost','2023-04-08 00:06:11');
/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;

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
  KEY `password_history_index_user_id` (`user_id`),
  KEY `password_history_index_email_address` (`email_address`),
  KEY `password_history_index_password_history_id` (`password_history_id`),
  CONSTRAINT `password_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `password_history_ibfk_2` FOREIGN KEY (`email_address`) REFERENCES `users` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_history`
--

LOCK TABLES `password_history` WRITE;
/*!40000 ALTER TABLE `password_history` DISABLE KEYS */;
INSERT INTO `password_history` VALUES (1,2,'ldagulto@encorefinancials.com','h9fWAirIuxth1Ns2%2BVH3tT8nwBiogeMVJxEhzhS0hqM%3D','2023-04-07 19:57:40');
/*!40000 ALTER TABLE `password_history` ENABLE KEYS */;
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
  PRIMARY KEY (`ui_customization_setting_id`),
  KEY `ui_customization_setting_index_ui_customization_setting_id` (`ui_customization_setting_id`),
  KEY `ui_customization_setting_index_user_id` (`user_id`),
  KEY `ui_customization_setting_index_email_address` (`email_address`),
  CONSTRAINT `ui_customization_setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `ui_customization_setting_ibfk_2` FOREIGN KEY (`email_address`) REFERENCES `users` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ui_customization_setting`
--

LOCK TABLES `ui_customization_setting` WRITE;
/*!40000 ALTER TABLE `ui_customization_setting` DISABLE KEYS */;
INSERT INTO `ui_customization_setting` VALUES (1,1,'admin@encorefinancials.com','false','false','preset-5','false','false','true'),(2,2,'ldagulto@encorefinancials.com','false','true','preset-1','true','false','false');
/*!40000 ALTER TABLE `ui_customization_setting` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_address` (`email_address`),
  KEY `users_index_email_address` (`email_address`),
  KEY `users_index_user_status` (`user_status`),
  KEY `users_index_password_expiry_date` (`password_expiry_date`),
  KEY `users_index_last_connection_date` (`last_connection_date`),
  KEY `users_index_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@encorefinancials.com','W5hvx4P278F8q50uZe2YFif%2ByRDeSeNaainzl5K9%2BQM%3D','Administrator','Active','2023-10-06',0,NULL,'2023-04-07 20:16:07'),(2,'ldagulto@encorefinancials.com','h9fWAirIuxth1Ns2%2BVH3tT8nwBiogeMVJxEhzhS0hqM%3D','Lawrence D. Agulto','Active','2023-10-07',0,'2023-04-07 19:57:52','2023-04-07 22:23:50');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_after_update_users` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    IF NOT (OLD.email_address <=> NEW.email_address) THEN
        INSERT INTO audit_log (table_name, field_name, old_value, new_value, changed_by, changed_at)
        VALUES ('users', 'email_address', OLD.email_address, NEW.email_address, USER(), NOW());
    END IF;
    
    IF NOT (OLD.file_as <=> NEW.file_as) THEN
        INSERT INTO audit_log (table_name, field_name, old_value, new_value, changed_by, changed_at)
        VALUES ('users', 'file_as', OLD.file_as, NEW.file_as, USER(), NOW());
    END IF;

    IF NOT (OLD.user_status <=> NEW.user_status) THEN
        INSERT INTO audit_log (table_name, field_name, old_value, new_value, changed_by, changed_at)
        VALUES ('users', 'user_status', OLD.user_status, NEW.user_status, USER(), NOW());
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
    SELECT ui_customization_setting_id, user_id, email_address, theme_contrast, caption_show, preset_theme, dark_layout, rtl_layout, box_container
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
	SELECT user_id, email_address, password, file_as, user_status, password_expiry_date, failed_login, last_failed_login, last_connection_date 
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_ui_customization_setting`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15))
BEGIN
	IF p_type = 'theme_contrast' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, theme_contrast) 
	    VALUES(p_user_id, p_email_address, p_customization_value);
    ELSEIF p_type = 'caption_show' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, caption_show) 
	    VALUES(p_user_id, p_email_address, p_customization_value);
    ELSEIF p_type = 'preset_theme' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, preset_theme) 
	    VALUES(p_user_id, p_email_address, p_customization_value);
    ELSEIF p_type = 'dark_layout' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, dark_layout) 
	    VALUES(p_user_id, p_email_address, p_customization_value);
    ELSEIF p_type = 'rtl_layout' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, rtl_layout) 
	    VALUES(p_user_id, p_email_address, p_customization_value);
    ELSE
        INSERT INTO ui_customization_setting (user_id, email_address, box_container) 
	    VALUES(p_user_id, p_email_address, p_customization_value);
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
/*!50003 DROP PROCEDURE IF EXISTS `update_ui_customization_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_ui_customization_setting`(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15))
BEGIN
	IF p_type = 'theme_contrast' THEN
        UPDATE ui_customization_setting
        SET theme_contrast = p_customization_value
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'caption_show' THEN
        UPDATE ui_customization_setting
        SET caption_show = p_customization_value
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'preset_theme' THEN
        UPDATE ui_customization_setting
        SET preset_theme = p_customization_value
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'dark_layout' THEN
        UPDATE ui_customization_setting
        SET dark_layout = p_customization_value
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'rtl_layout' THEN
        UPDATE ui_customization_setting
        SET rtl_layout = p_customization_value
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE ui_customization_setting
        SET box_container = p_customization_value
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

-- Dump completed on 2023-04-08  0:09:41
