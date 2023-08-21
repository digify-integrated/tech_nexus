-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2023 at 11:26 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nexusdb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `activateUserAccount` (IN `p_user_id` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET is_active = 1, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buildMenuGroup` (IN `p_user_id` INT)   BEGIN
    SELECT DISTINCT(mg.menu_group_id) as menu_group_id, mg.menu_group_name
    FROM menu_group mg
    JOIN menu_item mi ON mi.menu_group_id = mg.menu_group_id
    WHERE EXISTS (
        SELECT 1
        FROM menu_item_access_right mar
        WHERE mar.menu_item_id = mi.menu_item_id
        AND mar.read_access = 1
        AND mar.role_id IN (
            SELECT role_id
            FROM role_users
            WHERE user_id = p_user_id
        )
    )
    ORDER BY mg.order_sequence;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buildMenuItem` (IN `p_user_id` INT, IN `p_menu_group_id` INT)   BEGIN
    SELECT mi.menu_item_id, mi.menu_item_name, mi.menu_group_id, mi.menu_item_url, mi.parent_id, mi.menu_item_icon
    FROM menu_item AS mi
    INNER JOIN menu_item_access_right AS mar ON mi.menu_item_id = mar.menu_item_id
    INNER JOIN role_users AS ru ON mar.role_id = ru.role_id
    WHERE mar.read_access = 1 AND ru.user_id = p_user_id AND mi.menu_group_id = p_menu_group_id
    ORDER BY mi.order_sequence;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkFileExtensionExist` (IN `p_file_extension_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM file_extension
    WHERE file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkFileTypeExist` (IN `p_file_type_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM file_type
    WHERE file_type_id = p_file_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkInterfaceSettingExist` (IN `p_interface_setting_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkMenuGroupExist` (IN `p_menu_group_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM menu_group
    WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkMenuItemAccessRights` (IN `p_user_id` INT, IN `p_menu_item_id` INT, IN `p_access_type` VARCHAR(10))   BEGIN
	IF p_access_type = 'read' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where read_access = 1 AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'write' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where write_access = 1 AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'create' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where create_access = 1 AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'delete' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where delete_access = 1 AND menu_item_id = menu_item_id);
    ELSE
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where duplicate_access = 1 AND menu_item_id = menu_item_id);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkMenuItemExist` (IN `p_menu_item_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM menu_item
    WHERE menu_item_id = p_menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkRoleExist` (IN `p_role_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM role
    WHERE role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkRoleMenuAccessExist` (IN `p_menu_item_id` INT, IN `p_role_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM menu_item_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkRoleSystemActionAccessExist` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
	SELECT COUNT(*) AS total 
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkRoleUserExist` (IN `p_user_id` INT, IN `p_role_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM role_users
    WHERE user_id = p_user_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkSystemActionAccessRights` (IN `p_user_id` INT, IN `p_system_action_id` INT)   BEGIN
    SELECT COUNT(role_id) AS total
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_access = 1 AND role_id IN (SELECT role_id FROM role_users WHERE user_id = p_user_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkSystemActionExist` (IN `p_system_action_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM system_action
    WHERE system_action_id = p_system_action_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkSystemActionRoleExist` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
	SELECT COUNT(*) AS total 
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkSystemSettingExist` (IN `p_system_setting_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM system_setting
    WHERE system_setting_id = p_system_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkUICustomizationSettingExist` (IN `p_user_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM ui_customization_setting
	WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkUploadSettingExist` (IN `p_upload_setting_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM upload_setting
    WHERE upload_setting_id = p_upload_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkUploadSettingFileExtensionExist` (IN `p_upload_setting_id` INT, IN `p_file_extension_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id AND file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkUserEmailExist` (IN `p_email` VARCHAR(255))   BEGIN
	SELECT COUNT(*) AS total
    FROM users
    WHERE email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkUserExist` (IN `p_user_id` INT, IN `p_email` VARCHAR(255))   BEGIN
	SELECT COUNT(*) AS total
    FROM users
    WHERE user_id = p_user_id OR email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkUserIDExist` (IN `p_user_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM users
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deactivateUserAccount` (IN `p_user_id` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET is_active = 0, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteFileExtension` (IN `p_file_extension_id` INT)   BEGIN
	DELETE FROM file_extension
    WHERE file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteFileType` (IN `p_file_type_id` INT)   BEGIN
	DELETE FROM file_type
    WHERE file_type_id = p_file_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteInterfaceSetting` (IN `p_interface_setting_id` INT)   BEGIN
	DELETE FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedMenuItemAccessRight` (IN `p_menu_item_id` INT, IN `p_role_id` INT)   BEGIN
    DECLARE query TEXT;

	SET query = 'DELETE FROM menu_item_access_right';
	
	IF p_menu_item_id IS NOT NULL THEN
        SET query = CONCAT(query, ' WHERE menu_item_id = ');
        SET query = CONCAT(query, QUOTE(p_menu_item_id));
	ELSE
        SET query = CONCAT(query, ' WHERE role_id = ');
        SET query = CONCAT(query, QUOTE(p_role_id));
    END IF;

    SET query = CONCAT(query, ';');    

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedPasswordHistory` (IN `p_user_id` INT)   BEGIN
    DELETE FROM password_history
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedRoleUser` (IN `p_user_id` INT, IN `p_role_id` INT)   BEGIN
    DECLARE query TEXT;

	SET query = 'DELETE FROM role_users';
	
	IF p_user_id IS NOT NULL THEN
        SET query = CONCAT(query, ' WHERE user_id = ');
        SET query = CONCAT(query, QUOTE(p_user_id));
	ELSE
        SET query = CONCAT(query, ' WHERE role_id = ');
        SET query = CONCAT(query, QUOTE(p_role_id));
    END IF;

    SET query = CONCAT(query, ';');    

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedSystemActionAccessRight` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
    DECLARE query TEXT;

	SET query = 'DELETE FROM system_action_access_rights';
	
	IF p_system_action_id IS NOT NULL THEN
        SET query = CONCAT(query, ' WHERE system_action_id = ');
        SET query = CONCAT(query, QUOTE(p_system_action_id));
	ELSE
        SET query = CONCAT(query, ' WHERE role_id = ');
        SET query = CONCAT(query, QUOTE(p_role_id));
    END IF;

    SET query = CONCAT(query, ';');    

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedUICustomization` (IN `p_user_id` INT)   BEGIN
	DELETE FROM ui_customization_setting
	WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedUploadSettingFileExtension` (IN `p_upload_setting_id` INT, IN `p_file_extension_id` INT)   BEGIN
    DECLARE query TEXT;

	SET query = 'DELETE FROM upload_setting_file_extension';
	
	IF p_upload_setting_id IS NOT NULL THEN
        SET query = CONCAT(query, ' WHERE upload_setting_id = ');
        SET query = CONCAT(query, QUOTE(p_upload_setting_id));
	ELSE
        SET query = CONCAT(query, ' WHERE file_extension_id = ');
        SET query = CONCAT(query, QUOTE(p_file_extension_id));
    END IF;

    SET query = CONCAT(query, ';');    

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuGroup` (IN `p_menu_group_id` INT)   BEGIN
	DELETE FROM menu_group
    WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuItem` (IN `p_menu_item_id` INT)   BEGIN
	DELETE FROM menu_item
    WHERE menu_item_id = p_menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuItemRoleAccess` (IN `p_menu_item_id` INT, IN `p_role_id` INT)   BEGIN
	DELETE FROM menu_item_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRole` (IN `p_role_id` INT)   BEGIN
	DELETE FROM role
    WHERE role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRoleUser` (IN `p_user_id` INT, IN `p_role_id` INT)   BEGIN
	DELETE FROM role_users
    WHERE user_id = p_user_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteSystemAction` (IN `p_system_action_id` INT)   BEGIN
	DELETE FROM system_action
    WHERE system_action_id = p_system_action_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteSystemActionRoleAccess` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
	DELETE FROM system_action_access_rights
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteSystemSetting` (IN `p_system_setting_id` INT)   BEGIN
	DELETE FROM system_setting
    WHERE system_setting_id = p_system_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteUploadSetting` (IN `p_upload_setting_id` INT)   BEGIN
	DELETE FROM upload_setting
    WHERE upload_setting_id = p_upload_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteUploadSettingFileExtension` (IN `p_upload_setting_id` INT, IN `p_file_extension_id` INT)   BEGIN
	DELETE FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id AND file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteUserAccount` (IN `p_user_id` INT)   BEGIN
	DELETE FROM users
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateFileExtension` (IN `p_file_extension_id` INT, IN `p_last_log_by` INT, OUT `p_new_file_extension_id` INT)   BEGIN
    DECLARE p_file_extension_name VARCHAR(100);
    DECLARE p_file_type_id INT;
    DECLARE p_order_sequence TINYINT(10);
    
    SELECT file_extension_name, file_type_id 
    INTO p_file_extension_name, p_file_type_id
    FROM file_extension 
    WHERE file_extension_id = p_file_extension_id;
    
    INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) 
    VALUES(p_file_extension_name, p_file_type_id, p_last_log_by);
    
    SET p_new_file_extension_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateFileType` (IN `p_file_type_id` INT, IN `p_last_log_by` INT, OUT `p_new_file_type_id` INT)   BEGIN
    DECLARE p_file_type_name VARCHAR(100);
    
    SELECT file_type_name 
    INTO p_file_type_name
    FROM file_type 
    WHERE file_type_id = p_file_type_id;
    
    INSERT INTO file_type (file_type_name, last_log_by) 
    VALUES(p_file_type_name, p_last_log_by);
    
    SET p_new_file_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateInterfaceSetting` (IN `p_interface_setting_id` INT, IN `p_last_log_by` INT, OUT `p_new_interface_setting_id` INT)   BEGIN
    DECLARE p_interface_setting_name VARCHAR(100);
    DECLARE p_interface_setting_description VARCHAR(200);
    
    SELECT interface_setting_name, interface_setting_description
    INTO p_interface_setting_name, p_interface_setting_description
    FROM interface_setting 
    WHERE interface_setting_id = p_interface_setting_id;
    
    INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) 
    VALUES(p_interface_setting_name, p_interface_setting_description, p_last_log_by);
    
    SET p_new_interface_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateMenuGroup` (IN `p_menu_group_id` INT, IN `p_last_log_by` INT, OUT `p_new_menu_group_id` INT)   BEGIN
    DECLARE p_menu_group_name VARCHAR(100);
    DECLARE p_order_sequence TINYINT(10);
    
    SELECT menu_group_name, order_sequence 
    INTO p_menu_group_name, p_order_sequence 
    FROM menu_group 
    WHERE menu_group_id = p_menu_group_id;
    
    INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) 
    VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
    
    SET p_new_menu_group_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateMenuItem` (IN `p_menu_item_id` INT, IN `p_last_log_by` INT, OUT `p_new_menu_item_id` INT)   BEGIN
    DECLARE p_menu_item_name VARCHAR(100);
    DECLARE p_menu_group_id INT;
    DECLARE p_menu_item_url VARCHAR(50);
    DECLARE p_parent_id INT;
    DECLARE p_menu_item_icon VARCHAR(150);
    DECLARE p_order_sequence TINYINT(10);
    
    SELECT menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence 
    INTO p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence
    FROM menu_item 
    WHERE menu_item_id = p_menu_item_id;
    
    INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) 
    VALUES(p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
    
    SET p_new_menu_item_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateRole` (IN `p_role_id` INT, IN `p_last_log_by` INT, OUT `p_new_role_id` INT)   BEGIN
    DECLARE p_role_name VARCHAR(100);
    DECLARE p_role_description VARCHAR(200);
    DECLARE p_assignable TINYINT(1);
    
    SELECT role_name, role_description, assignable
    INTO p_role_name, p_role_description, p_assignable
    FROM role 
    WHERE role_id = p_role_id;
    
    INSERT INTO role (role_name, role_description, assignable, last_log_by) 
    VALUES(p_role_name, p_role_description, p_assignable, p_last_log_by);
    
    SET p_new_role_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateSystemAction` (IN `p_system_action_id` INT, IN `p_last_log_by` INT, OUT `p_new_system_action_id` INT)   BEGIN
    DECLARE p_system_action_name VARCHAR(100);
    
    SELECT system_action_name 
    INTO p_system_action_name
    FROM system_action 
    WHERE system_action_id = p_system_action_id;
    
    INSERT INTO system_action (system_action_name, last_log_by) 
    VALUES(p_system_action_name, p_last_log_by);
    
    SET p_new_system_action_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateSystemSetting` (IN `p_system_setting_id` INT, IN `p_last_log_by` INT, OUT `p_new_system_setting_id` INT)   BEGIN
    DECLARE p_system_setting_name VARCHAR(100);
    DECLARE p_system_setting_description VARCHAR(200);
    DECLARE p_value VARCHAR(1000);
    
    SELECT system_setting_name, system_setting_description, value
    INTO p_system_setting_name, p_system_setting_description, p_value
    FROM system_setting 
    WHERE system_setting_id = p_system_setting_id;
    
    INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) 
    VALUES(p_system_setting_name, p_system_setting_description, p_value, p_last_log_by);
    
    SET p_new_system_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateUploadSetting` (IN `p_upload_setting_id` INT, IN `p_last_log_by` INT, OUT `p_new_upload_setting_id` INT)   BEGIN
    DECLARE p_upload_setting_name VARCHAR(100);
    DECLARE p_upload_setting_description VARCHAR(200);
    DECLARE p_max_file_size DOUBLE;
    
    SELECT upload_setting_name, upload_setting_description, max_file_size
    INTO p_upload_setting_name, p_upload_setting_description, p_max_file_size
    FROM upload_setting 
    WHERE upload_setting_id = p_upload_setting_id;
    
    INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) 
    VALUES(p_upload_setting_name, p_upload_setting_description, p_max_file_size, p_last_log_by);
    
    SET p_new_upload_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddMenuItemRoleAccessTable` (IN `p_menu_item_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM menu_item_access_right WHERE menu_item_id = p_menu_item_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddRoleMenuItemAccessTable` (IN `p_role_id` INT)   BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    WHERE menu_item_id NOT IN (SELECT menu_item_id FROM menu_item_access_right WHERE role_id = p_role_id)
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddRoleMenuItemTable` (IN `p_role_id` INT)   BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    WHERE menu_item_id NOT IN (SELECT menu_item_id FROM menu_item_access_right WHERE role_id = p_role_id)
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddRoleSystemActionAccessTable` (IN `p_role_id` INT)   BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id NOT IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddRoleSystemActionTable` (IN `p_role_id` INT)   BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id NOT IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddRoleUserAccountTable` (IN `p_role_id` INT)   BEGIN
	SELECT user_id, file_as, email, last_connection_date FROM users
    WHERE user_id NOT IN (SELECT user_id FROM role_users WHERE role_id = p_role_id)
    ORDER BY file_as;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddSystemActionRoleAccessTable` (IN `p_system_action_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM system_action_access_rights WHERE system_action_id = p_system_action_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddUploadSettingFileExensionTable` (IN `p_upload_setting_id` INT)   BEGIN
	SELECT file_extension_id, file_extension_name 
    FROM file_extension
    WHERE file_extension_id NOT IN (SELECT file_extension_id FROM upload_setting_file_extension WHERE upload_setting_id = p_upload_setting_id)
    ORDER BY file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddUserAccountRoleTable` (IN `p_user_account_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM role_users WHERE user_id = p_user_account_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateFileExtensionOptions` ()   BEGIN
	SELECT file_extension_id, file_extension_name FROM file_extension
	ORDER BY file_extension_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateFileExtensionTable` ()   BEGIN
	SELECT file_extension_id, file_extension_name, file_type_id 
    FROM file_extension
    ORDER BY file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateFileTypeFileExensionTable` (IN `p_file_type_id` INT)   BEGIN
	SELECT file_extension_id, file_extension_name 
    FROM file_extension
    WHERE file_type_id = p_file_type_id 
    ORDER BY file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateFileTypeOptions` ()   BEGIN
	SELECT file_type_id, file_type_name FROM file_type
	ORDER BY file_type_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateFileTypeTable` ()   BEGIN
	SELECT file_type_id, file_type_name 
    FROM file_type
    ORDER BY file_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateInterfaceSettingTable` ()   BEGIN
	SELECT interface_setting_id, interface_setting_name, interface_setting_description, value
    FROM interface_setting
    ORDER BY interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateLogNotes` (IN `p_table_name` VARCHAR(255), IN `p_reference_id` INT)   BEGIN
	SELECT log, changed_by, changed_at FROM audit_log
    WHERE table_name = p_table_name AND reference_id = p_reference_id 
    ORDER BY changed_at DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuGroupMenuItemTable` (IN `p_menu_group_id` INT)   BEGIN
	SELECT menu_item_id, menu_item_name, parent_id, order_sequence 
    FROM menu_item
    WHERE menu_group_id = p_menu_group_id 
    ORDER BY menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuGroupOptions` ()   BEGIN
	SELECT menu_group_id, menu_group_name FROM menu_group
	ORDER BY menu_group_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuGroupTable` ()   BEGIN
	SELECT menu_group_id, menu_group_name, order_sequence 
    FROM menu_group 
    ORDER BY menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuItemAccessTable` ()   BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuItemOptions` ()   BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
	ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuItemRoleAccessTable` (IN `p_menu_item_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM menu_item_access_right WHERE menu_item_id = p_menu_item_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuItemTable` ()   BEGIN
	SELECT menu_item_id, menu_item_name, menu_group_id, parent_id, order_sequence 
    FROM menu_item
    ORDER BY menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRoleConfigurationTable` ()   BEGIN
	SELECT role_id, role_name, role_description, assignable
    FROM role 
    ORDER BY role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRoleMenuItemAccessTable` (IN `p_role_id` INT)   BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    WHERE menu_item_id IN (SELECT menu_item_id FROM menu_item_access_right WHERE role_id = p_role_id)
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRoleSystemActionAccessTable` (IN `p_role_id` INT)   BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRoleSystemActionTable` (IN `p_role_id` INT)   BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRoleTable` ()   BEGIN
	SELECT role_id, role_name, role_description
    FROM role
    WHERE assignable = 1
    ORDER BY role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRoleUserAccountTable` (IN `p_role_id` INT)   BEGIN
	SELECT user_id, file_as, email, last_connection_date FROM users
    WHERE user_id IN (SELECT user_id FROM role_users WHERE role_id = p_role_id)
    ORDER BY file_as;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateShortcutMenuItemRoleTable` ()   BEGIN
	SELECT role_id, role_name FROM role
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSubMenuItemTable` (IN `p_parent_id` INT)   BEGIN
	SELECT menu_item_name, menu_group_id, order_sequence 
    FROM menu_item
    WHERE parent_id = p_parent_id
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSystemActionRoleAccessTable` (IN `p_system_action_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM system_action_access_rights WHERE system_action_id = p_system_action_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSystemActionTable` ()   BEGIN
	SELECT system_action_id, system_action_name 
    FROM system_action
    ORDER BY system_action_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSystemSettingTable` ()   BEGIN
	SELECT system_setting_id, system_setting_name, system_setting_description, value
    FROM system_setting
    ORDER BY system_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateUploadSettingFileExensionTable` (IN `p_upload_setting_id` INT)   BEGIN
	SELECT file_extension_id, file_extension_name 
    FROM file_extension
    WHERE file_extension_id IN (SELECT file_extension_id FROM upload_setting_file_extension WHERE upload_setting_id = p_upload_setting_id)
    ORDER BY file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateUploadSettingTable` ()   BEGIN
	SELECT upload_setting_id, upload_setting_name, upload_setting_description, max_file_size
    FROM upload_setting
    ORDER BY upload_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateUserAccountRoleTable` (IN `p_user_account_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM role_users WHERE user_id = p_user_account_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateUserAccountTable` (IN `p_is_active` ENUM('active','inactive','all'), IN `p_is_locked` ENUM('yes','no','all'), IN `p_password_expiry_date_start_date` DATE, IN `p_password_expiry_date_end_date` DATE, IN `p_filter_last_connection_date_start_date` DATE, IN `p_filter_last_connection_date_end_date` DATE, IN `p_filter_last_password_reset_start_date` DATE, IN `p_filter_last_password_reset_end_date` DATE, IN `p_filter_last_failed_login_attempt_start_date` DATE, IN `p_filter_last_failed_login_attempt_end_date` DATE)   BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT * FROM users';
    
    SET conditionList = ' WHERE 1';

    IF p_is_active IS NOT NULL THEN
        IF p_is_active = 'active' THEN
            SET conditionList = CONCAT(conditionList, ' AND is_active = 1');
        ELSEIF p_is_active = 'inactive' THEN
            SET conditionList = CONCAT(conditionList, ' AND is_active = 0');
        END IF;
    END IF;

    IF p_is_locked IS NOT NULL THEN
        IF p_is_locked = 'yes' THEN
            SET conditionList = CONCAT(conditionList, ' AND is_locked = 1');
        ELSEIF p_is_locked = 'no' THEN
            SET conditionList = CONCAT(conditionList, ' AND is_locked = 0');
        END IF;
    END IF;

    IF p_password_expiry_date_start_date IS NOT NULL AND p_password_expiry_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND password_expiry_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_password_expiry_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_password_expiry_date_end_date));
    END IF;

    IF p_filter_last_connection_date_start_date IS NOT NULL AND p_filter_last_connection_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND last_connection_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_last_connection_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_last_connection_date_end_date));
    END IF;

    IF p_filter_last_password_reset_start_date IS NOT NULL AND p_filter_last_password_reset_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND last_password_reset BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_last_password_reset_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_last_password_reset_end_date));
    END IF;

    IF p_filter_last_failed_login_attempt_start_date IS NOT NULL AND p_filter_last_failed_login_attempt_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND last_failed_login_attempt BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_last_failed_login_attempt_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_last_failed_login_attempt_end_date));
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY file_as;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFileExtension` (IN `p_file_extension_id` INT)   BEGIN
	SELECT * FROM file_extension
	WHERE file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFileType` (IN `p_file_type_id` INT)   BEGIN
	SELECT * FROM file_type
    WHERE file_type_id = p_file_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getInterfaceSetting` (IN `p_interface_setting_id` INT)   BEGIN
	SELECT * FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMenuGroup` (IN `p_menu_group_id` INT)   BEGIN
	SELECT * FROM menu_group
	WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMenuItem` (IN `p_menu_item_id` INT)   BEGIN
	SELECT * FROM menu_item
	WHERE menu_item_id = p_menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getPasswordHistory` (IN `p_user_id` INT, IN `p_email` VARCHAR(255))   BEGIN
	SELECT * FROM password_history
	WHERE p_user_id = p_user_id OR email = BINARY p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRole` (IN `p_role_id` INT)   BEGIN
	SELECT * FROM role
    WHERE role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRoleMenuAccess` (IN `p_menu_item_id` INT, IN `p_role_id` INT)   BEGIN
    SELECT read_access, write_access, create_access, delete_access, duplicate_access
    FROM menu_item_access_right 
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRoleSystemActionAccess` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
    SELECT role_access
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getSystemAction` (IN `p_system_action_id` INT)   BEGIN
	SELECT * FROM system_action
    WHERE system_action_id = p_system_action_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getSystemSetting` (IN `p_system_setting_id` INT)   BEGIN
	SELECT * FROM system_setting
    WHERE system_setting_id = p_system_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUICustomizationSetting` (IN `p_user_id` INT)   BEGIN
	SELECT * FROM ui_customization_setting
	WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUploadSetting` (IN `p_upload_setting_id` INT)   BEGIN
	SELECT * FROM upload_setting
    WHERE upload_setting_id = p_upload_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUploadSettingFileExtension` (IN `p_upload_setting_id` INT)   BEGIN
	SELECT * FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserByEmail` (IN `p_email` VARCHAR(255))   BEGIN
	SELECT * FROM users
	WHERE email = BINARY p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserByID` (IN `p_user_id` INT)   BEGIN
	SELECT * FROM users
	WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserByRememberToken` (IN `p_remember_token` VARCHAR(255))   BEGIN
	SELECT * FROM users
	WHERE remember_token = p_remember_token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertFileExtension` (IN `p_file_extension_name` VARCHAR(100), IN `p_file_type_id` INT, IN `p_last_log_by` INT, OUT `p_file_extension_id` INT)   BEGIN
    INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) 
	VALUES(p_file_extension_name, p_file_type_id, p_last_log_by);
	
    SET p_file_extension_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertFileType` (IN `p_file_type_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_file_type_id` INT)   BEGIN
    INSERT INTO file_type (file_type_name, last_log_by) 
	VALUES(p_file_type_name, p_last_log_by);
	
    SET p_file_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertInterfaceSetting` (IN `p_interface_setting_name` VARCHAR(100), IN `p_interface_setting_description` VARCHAR(200), IN `p_last_log_by` INT, OUT `p_interface_setting_id` INT)   BEGIN
    INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) 
	VALUES(p_interface_setting_name, p_interface_setting_description, p_last_log_by);
	
    SET p_interface_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertMenuGroup` (IN `p_menu_group_name` VARCHAR(100), IN `p_order_sequence` TINYINT(10), IN `p_last_log_by` INT, OUT `p_menu_group_id` INT)   BEGIN
    INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) 
	VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
	
    SET p_menu_group_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertMenuItem` (IN `p_menu_item_name` VARCHAR(100), IN `p_menu_group_id` INT, IN `p_menu_item_url` VARCHAR(50), IN `p_parent_id` INT, IN `p_menu_item_icon` VARCHAR(150), IN `p_order_sequence` TINYINT(10), IN `p_last_log_by` INT, OUT `p_menu_item_id` INT)   BEGIN
    INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) 
	VALUES(p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
	
    SET p_menu_item_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertPasswordHistory` (IN `p_user_id` INT, IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_last_password_change` DATETIME)   BEGIN
    INSERT INTO password_history (user_id, email, password, password_change_date) 
    VALUES (p_user_id, p_email, p_password, p_last_password_change);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertRole` (IN `p_role_name` VARCHAR(100), IN `p_role_description` VARCHAR(200), IN `p_assignable` TINYINT(1), IN `p_last_log_by` INT, OUT `p_role_id` INT)   BEGIN
    INSERT INTO role (role_name, role_description, assignable, last_log_by) 
	VALUES(p_role_name, p_role_description, p_assignable, p_last_log_by);
	
    SET p_role_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertRoleMenuAccess` (IN `p_menu_item_id` INT, IN `p_role_id` INT, IN `p_last_log_by` INT)   BEGIN
    INSERT INTO menu_item_access_right (menu_item_id, role_id, last_log_by) 
	VALUES(p_menu_item_id, p_role_id, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertRoleSystemActionAccess` (IN `p_system_action_id` INT, IN `p_role_id` INT, IN `p_last_log_by` INT)   BEGIN
    INSERT INTO system_action_access_rights (system_action_id, role_id, last_log_by) 
	VALUES(p_system_action_id, p_role_id, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertRoleUser` (IN `p_user_id` INT, IN `p_role_id` INT, IN `p_last_log_by` INT)   BEGIN
    INSERT INTO role_users (user_id, role_id, last_log_by) 
	VALUES(p_user_id, p_role_id, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertSystemAction` (IN `p_system_action_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_system_action_id` INT)   BEGIN
    INSERT INTO system_action (system_action_name, last_log_by) 
	VALUES(p_system_action_name, p_last_log_by);
	
    SET p_system_action_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertSystemSetting` (IN `p_system_setting_name` VARCHAR(100), IN `p_system_setting_description` VARCHAR(200), IN `p_value` VARCHAR(1000), IN `p_last_log_by` INT, OUT `p_system_setting_id` INT)   BEGIN
    INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) 
	VALUES(p_system_setting_name, p_system_setting_description, p_value, p_last_log_by);
	
    SET p_system_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertUICustomizationSetting` (IN `p_user_id` INT, IN `p_type` VARCHAR(30), IN `p_customization_value` VARCHAR(15), IN `p_last_log_by` INT(10))   BEGIN
	IF p_type = 'theme contrast' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, theme_contrast, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'caption show' THEN
        INSERT INTO ui_customization_setting (user_id, caption_show, last_log_by) 
	    VALUES(p_user_id, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'preset theme' THEN
        INSERT INTO ui_customization_setting (user_id, preset_theme, last_log_by) 
	    VALUES(p_user_id, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'dark layout' THEN
        INSERT INTO ui_customization_setting (user_id, dark_layout, last_log_by) 
	    VALUES(p_user_id, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'rtl layout' THEN
        INSERT INTO ui_customization_setting (user_id, rtl_layout, last_log_by) 
	    VALUES(p_user_id, p_customization_value, p_last_log_by);
    ELSE
        INSERT INTO ui_customization_setting (user_id, box_container, last_log_by) 
	    VALUES(p_user_id, p_customization_value, p_last_log_by);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertUploadSetting` (IN `p_upload_setting_name` VARCHAR(100), IN `p_upload_setting_description` VARCHAR(200), IN `p_max_file_size` DOUBLE, IN `p_last_log_by` INT, OUT `p_upload_setting_id` INT)   BEGIN
    INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) 
	VALUES(p_upload_setting_name, p_upload_setting_description, p_max_file_size, p_last_log_by);
	
    SET p_upload_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertUploadSettingFileExtension` (IN `p_upload_setting_id` INT, IN `p_file_extension_id` INT, IN `p_last_log_by` INT)   BEGIN
    INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) 
	VALUES(p_upload_setting_id, p_file_extension_id, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertUserAccount` (IN `p_file_as` VARCHAR(300), IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_password_expiry_date` DATE, IN `p_last_log_by` INT, OUT `p_user_account_id` INT)   BEGIN
    INSERT INTO users (file_as, email, password, password_expiry_date, last_log_by) 
	VALUES(p_file_as, p_email, p_password, p_password_expiry_date, p_last_log_by);
	
    SET p_user_account_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `lockUserAccount` (IN `p_user_id` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET is_locked = 1, account_lock_duration = CAST(4294967295 AS SIGNED), last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `unlockUserAccount` (IN `p_user_id` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET is_locked = 0, account_lock_duration = 0, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateAccountLock` (IN `p_user_id` INT, IN `p_is_locked` TINYINT(1), IN `p_account_lock_duration` INT)   BEGIN
	UPDATE users 
    SET is_locked = p_is_locked, account_lock_duration = p_account_lock_duration 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateFailedOTPAttempts` (IN `p_user_id` INT, IN `p_failed_otp_attempts` INT)   BEGIN
	UPDATE users 
    SET failed_otp_attempts = p_failed_otp_attempts
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateFileExtension` (IN `p_file_extension_id` INT, IN `p_file_extension_name` VARCHAR(100), IN `p_file_type_id` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE file_extension
    SET file_extension_name = p_file_extension_name,
    file_type_id = p_file_type_id,
    last_log_by = p_last_log_by
    WHERE file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateFileType` (IN `p_file_type_id` INT, IN `p_file_type_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE file_type
    SET file_type_name = p_file_type_name,
    last_log_by = p_last_log_by
    WHERE file_type_id = p_file_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateInterfaceSetting` (IN `p_interface_setting_id` INT, IN `p_interface_setting_name` VARCHAR(100), IN `p_interface_setting_description` VARCHAR(200), IN `p_last_log_by` INT)   BEGIN
	UPDATE interface_setting
    SET interface_setting_name = p_interface_setting_name,
    interface_setting_description = p_interface_setting_description,
    last_log_by = p_last_log_by
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateInterfaceSettingValue` (IN `p_interface_setting_id` INT, IN `p_value` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
	UPDATE interface_setting
    SET value = p_value,
    last_log_by = p_last_log_by
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateLastConnection` (IN `p_user_id` INT, IN `p_last_connection_date` DATETIME)   BEGIN
	UPDATE users 
    SET last_connection_date = p_last_connection_date
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateLoginAttempt` (IN `p_user_id` INT, IN `p_failed_login_attempts` INT, IN `p_last_failed_login_attempt` DATETIME)   BEGIN
	UPDATE users 
    SET failed_login_attempts = p_failed_login_attempts, last_failed_login_attempt = p_last_failed_login_attempt
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateMenuGroup` (IN `p_menu_group_id` INT, IN `p_menu_group_name` VARCHAR(100), IN `p_order_sequence` TINYINT(10), IN `p_last_log_by` INT)   BEGIN
	UPDATE menu_group
    SET menu_group_name = p_menu_group_name,
    order_sequence = p_order_sequence,
    last_log_by = p_last_log_by
    WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateMenuItem` (IN `p_menu_item_id` INT, IN `p_menu_item_name` VARCHAR(100), IN `p_menu_group_id` INT, IN `p_menu_item_url` VARCHAR(50), IN `p_parent_id` INT, IN `p_menu_item_icon` VARCHAR(150), IN `p_order_sequence` TINYINT(10), IN `p_last_log_by` INT)   BEGIN
	UPDATE menu_item
    SET menu_item_name = p_menu_item_name,
    menu_group_id = p_menu_group_id,
    menu_item_url = p_menu_item_url,
    parent_id = p_parent_id,
    menu_item_icon = p_menu_item_icon,
    order_sequence = p_order_sequence,
    last_log_by = p_last_log_by
    WHERE menu_item_id = p_menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateNotificationSetting` (IN `p_user_id` INT, IN `p_receive_notification` TINYINT(1), IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET receive_notification = p_receive_notification, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateOTP` (IN `p_user_id` INT, IN `p_otp` VARCHAR(255), IN `p_otp_expiry_date` DATETIME, IN `p_remember_me` TINYINT(1))   BEGIN
	UPDATE users 
    SET otp = p_otp, otp_expiry_date = p_otp_expiry_date, remember_me = p_remember_me, failed_otp_attempts = 0
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateOTPAsExpired` (IN `p_user_id` INT, IN `p_otp_expiry_date` DATETIME)   BEGIN
	UPDATE users 
    SET otp_expiry_date = p_otp_expiry_date
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateRememberToken` (IN `p_user_id` INT, IN `p_remember_token` VARCHAR(255))   BEGIN
	UPDATE users 
    SET remember_token = p_remember_token
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateResetToken` (IN `p_user_id` INT, IN `p_reset_token` VARCHAR(255), IN `p_reset_token_expiry_date` DATETIME)   BEGIN
	UPDATE users 
    SET reset_token = p_reset_token, reset_token_expiry_date = p_reset_token_expiry_date
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateRole` (IN `p_role_id` INT, IN `p_role_name` VARCHAR(100), IN `p_role_description` VARCHAR(200), IN `p_assignable` TINYINT(1), IN `p_last_log_by` INT)   BEGIN
	UPDATE role
    SET role_name = p_role_name,
    role_name = p_role_name,
    role_description = p_role_description,
    assignable = p_assignable,
    last_log_by = p_last_log_by
    WHERE role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateRoleMenuAccess` (IN `p_menu_item_id` INT, IN `p_role_id` INT, IN `p_access_type` VARCHAR(10), IN `p_access` TINYINT(1), IN `p_last_log_by` INT)   BEGIN
    IF p_access_type = 'read' THEN
        UPDATE menu_item_access_right
        SET read_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'write' THEN
        UPDATE menu_item_access_right
        SET write_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'create' THEN
        UPDATE menu_item_access_right
        SET create_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'delete' THEN
      UPDATE menu_item_access_right
        SET delete_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSE
        UPDATE menu_item_access_right
        SET duplicate_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateRoleSystemActionAccess` (IN `p_system_action_id` INT, IN `p_role_id` INT, IN `p_access` TINYINT(1), IN `p_last_log_by` INT)   BEGIN
    UPDATE system_action_access_rights
    SET role_access = p_access,
    last_log_by = p_last_log_by
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateSystemAction` (IN `p_system_action_id` INT, IN `p_system_action_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE system_action
    SET system_action_name = p_system_action_name,
    last_log_by = p_last_log_by
    WHERE system_action_id = p_system_action_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateSystemSetting` (IN `p_system_setting_id` INT, IN `p_system_setting_name` VARCHAR(100), IN `p_system_setting_description` VARCHAR(200), IN `p_value` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
	UPDATE system_setting
    SET system_setting_name = p_system_setting_name,
    system_setting_description = p_system_setting_description,
    value = p_value,
    last_log_by = p_last_log_by
    WHERE system_setting_id = p_system_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateTwoFactorAuthentication` (IN `p_user_id` INT, IN `p_two_factor_auth` TINYINT(1), IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET two_factor_auth = p_two_factor_auth, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUICustomizationSetting` (IN `p_user_id` INT, IN `p_type` VARCHAR(30), IN `p_customization_value` VARCHAR(15), IN `p_last_log_by` INT(10))   BEGIN
	IF p_type = 'theme contrast' THEN
        UPDATE ui_customization_setting
        SET theme_contrast = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id;
    ELSEIF p_type = 'caption show' THEN
        UPDATE ui_customization_setting
        SET caption_show = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id;
    ELSEIF p_type = 'preset theme' THEN
        UPDATE ui_customization_setting
        SET preset_theme = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id;
    ELSEIF p_type = 'dark layout' THEN
        UPDATE ui_customization_setting
        SET dark_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id;
    ELSEIF p_type = 'rtl layout' THEN
        UPDATE ui_customization_setting
        SET rtl_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id;
    ELSE
        UPDATE ui_customization_setting
        SET box_container = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUploadSetting` (IN `p_upload_setting_id` INT, IN `p_upload_setting_name` VARCHAR(100), IN `p_upload_setting_description` VARCHAR(200), IN `p_max_file_size` DOUBLE, IN `p_last_log_by` INT)   BEGIN
	UPDATE upload_setting
    SET upload_setting_name = p_upload_setting_name,
    upload_setting_description = p_upload_setting_description,
    max_file_size = p_max_file_size,
    last_log_by = p_last_log_by
    WHERE upload_setting_id = p_upload_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUserAccount` (IN `p_user_id` INT, IN `p_file_as` VARCHAR(300), IN `p_email` VARCHAR(255), IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET file_as = p_file_as, email = p_email, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUserPassword` (IN `p_user_id` INT, IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_password_expiry_date` DATE, IN `p_last_password_change` DATETIME)   BEGIN
	UPDATE users 
    SET password = p_password, password_expiry_date = p_password_expiry_date, last_password_change = p_last_password_change, is_locked = 0, failed_login_attempts = 0, account_lock_duration = 0
    WHERE p_user_id = p_user_id OR email = BINARY p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUserProfilePicture` (IN `p_user_id` INT, IN `p_profile_picture` VARCHAR(500), IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET profile_picture = p_profile_picture, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `audit_log_id` int(10) UNSIGNED NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `log` text NOT NULL,
  `changed_by` varchar(255) NOT NULL,
  `changed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(1, 'users', 1, 'User created. <br/><br/>File As: Administrator<br/>Email: ldagulto@encorefinancials.com<br/>Is Active: 1<br/>Password Expiry Date: 2023-12-30<br/>2-Factor Authentication: 1', '0', '2023-08-21 16:06:40'),
(2, 'role', 1, 'Role created. <br/><br/>Role Name: Super Admin<br/>Role Description: This role has the highest level of access and full control over the entire system. Super Admins can perform all actions, including managing other user accounts, configuring system settings, and access', '0', '2023-08-21 16:06:40'),
(3, 'role', 2, 'Role created. <br/><br/>Role Name: Administrator<br/>Role Description: Full access to all features and data within the system. This role have similar access levels to the Admin but is not as powerful as the Super Admin.<br/>Assignable: 1', '0', '2023-08-21 16:06:40'),
(4, 'role', 3, 'Role created. <br/><br/>Role Name: Manager<br/>Role Description: Access to manage specific aspects of the system or resources related to their teams or departments.<br/>Assignable: 1', '0', '2023-08-21 16:06:40'),
(5, 'role', 4, 'Role created. <br/><br/>Role Name: Employee<br/>Role Description: The typical user account with standard access to use the system features and functionalities.<br/>Assignable: 1', '0', '2023-08-21 16:06:40'),
(6, 'menu_group', 1, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 100', '0', '2023-08-21 16:06:40'),
(7, 'menu_group', 2, 'Menu group created. <br/><br/>Menu Group Name: Human Resources<br/>Order Sequence: 50', '0', '2023-08-21 16:06:40'),
(8, 'menu_item', 1, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 50', '0', '2023-08-21 16:06:40'),
(9, 'menu_item', 2, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 1<br/>URL: menu-group.php<br/>Parent ID: 1<br/>Order Sequence: 1', '0', '2023-08-21 16:06:40'),
(10, 'menu_item', 3, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 1<br/>URL: menu-item.php<br/>Parent ID: 1<br/>Order Sequence: 2', '0', '2023-08-21 16:06:40'),
(11, 'menu_item', 4, 'Menu item created. <br/><br/>Menu Item Name: Administration<br/>Menu Group ID: 1<br/>Menu Item Icon: shield<br/>Order Sequence: 1', '0', '2023-08-21 16:06:40'),
(12, 'menu_item', 5, 'Menu item created. <br/><br/>Menu Item Name: System Action<br/>Menu Group ID: 1<br/>URL: system-action.php<br/>Parent ID: 4<br/>Order Sequence: 15', '0', '2023-08-21 16:06:40'),
(13, 'menu_item', 6, 'Menu item created. <br/><br/>Menu Item Name: Role Configuration<br/>Menu Group ID: 1<br/>URL: role-configuration.php<br/>Parent ID: 4<br/>Order Sequence: 10', '0', '2023-08-21 16:06:40'),
(14, 'menu_item', 7, 'Menu item created. <br/><br/>Menu Item Name: Role<br/>Menu Group ID: 1<br/>URL: role.php<br/>Parent ID: 4<br/>Order Sequence: 9', '0', '2023-08-21 16:06:40'),
(15, 'menu_item', 8, 'Menu item created. <br/><br/>Menu Item Name: User Account<br/>Menu Group ID: 1<br/>URL: user-account.php<br/>Parent ID: 4<br/>Order Sequence: 1', '0', '2023-08-21 16:06:40'),
(16, 'menu_item', 9, 'Menu item created. <br/><br/>Menu Item Name: Configurations<br/>Menu Group ID: 1<br/>Menu Item Icon: settings<br/>Order Sequence: 30', '0', '2023-08-21 16:06:40'),
(17, 'menu_item', 10, 'Menu item created. <br/><br/>Menu Item Name: File Type<br/>Menu Group ID: 1<br/>URL: file-type.php<br/>Parent ID: 9<br/>Order Sequence: 30', '0', '2023-08-21 16:06:40'),
(18, 'menu_item', 11, 'Menu item created. <br/><br/>Menu Item Name: File Extension<br/>Menu Group ID: 1<br/>URL: file-extension.php<br/>Parent ID: 9<br/>Order Sequence: 31', '0', '2023-08-21 16:06:40'),
(19, 'menu_item', 12, 'Menu item created. <br/><br/>Menu Item Name: Upload Setting<br/>Menu Group ID: 1<br/>URL: upload-setting.php<br/>Parent ID: 9<br/>Order Sequence: 29', '0', '2023-08-21 16:06:40'),
(20, 'menu_item', 13, 'Menu item created. <br/><br/>Menu Item Name: Interface Setting<br/>Menu Group ID: 1<br/>URL: interface-setting.php<br/>Parent ID: 1<br/>Order Sequence: 3', '0', '2023-08-21 16:06:40'),
(21, 'menu_item', 14, 'Menu item created. <br/><br/>Menu Item Name: System Setting<br/>Menu Group ID: 1<br/>URL: system-setting.php<br/>Parent ID: 4<br/>Order Sequence: 16', '0', '2023-08-21 16:06:40'),
(22, 'menu_item_access_right', 1, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-08-21 16:06:40'),
(23, 'menu_item_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(24, 'menu_item_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(25, 'menu_item_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-08-21 16:06:40'),
(26, 'menu_item_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(27, 'menu_item_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(28, 'menu_item_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1', '0', '2023-08-21 16:06:40'),
(29, 'menu_item_access_right', 8, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-08-21 16:06:40'),
(30, 'menu_item_access_right', 9, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-08-21 16:06:40'),
(31, 'menu_item_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(32, 'menu_item_access_right', 11, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(33, 'menu_item_access_right', 12, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(34, 'menu_item_access_right', 13, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(35, 'menu_item_access_right', 14, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-08-21 16:06:40'),
(36, 'system_action', 1, 'System action created. <br/><br/>System Action Name: Update Menu Item Role Access', '0', '2023-08-21 16:06:40'),
(37, 'system_action', 2, 'System action created. <br/><br/>System Action Name: Delete Menu Item Role Access', '0', '2023-08-21 16:06:40'),
(38, 'system_action', 3, 'System action created. <br/><br/>System Action Name: Update System Action Role Access', '0', '2023-08-21 16:06:40'),
(39, 'system_action', 4, 'System action created. <br/><br/>System Action Name: Delete System Action Role Access', '0', '2023-08-21 16:06:40'),
(40, 'system_action', 5, 'System action created. <br/><br/>System Action Name: Assign User Account To Role', '0', '2023-08-21 16:06:40'),
(41, 'system_action', 6, 'System action created. <br/><br/>System Action Name: Delete User Account To Role', '0', '2023-08-21 16:06:40'),
(42, 'system_action', 7, 'System action created. <br/><br/>System Action Name: Assign Role To User Account', '0', '2023-08-21 16:06:40'),
(43, 'system_action', 8, 'System action created. <br/><br/>System Action Name: Delete Role To User Account', '0', '2023-08-21 16:06:40'),
(44, 'system_action', 9, 'System action created. <br/><br/>System Action Name: Activate User Account', '0', '2023-08-21 16:06:40'),
(45, 'system_action', 10, 'System action created. <br/><br/>System Action Name: Deactivate User Account', '0', '2023-08-21 16:06:40'),
(46, 'system_action', 11, 'System action created. <br/><br/>System Action Name: Lock User Account', '0', '2023-08-21 16:06:40'),
(47, 'system_action', 12, 'System action created. <br/><br/>System Action Name: Unlock User Account', '0', '2023-08-21 16:06:40'),
(48, 'system_action', 13, 'System action created. <br/><br/>System Action Name: Change User Account Password', '0', '2023-08-21 16:06:40'),
(49, 'system_action', 14, 'System action created. <br/><br/>System Action Name: Change User Account Profile Picture', '0', '2023-08-21 16:06:40'),
(50, 'system_action', 15, 'System action created. <br/><br/>System Action Name: Assign File Extension To Upload Setting', '0', '2023-08-21 16:06:40'),
(51, 'system_action', 16, 'System action created. <br/><br/>System Action Name: Delete File Extension To Upload Setting', '0', '2023-08-21 16:06:40'),
(52, 'system_action', 17, 'System action created. <br/><br/>System Action Name: Send Reset Password Instructions', '0', '2023-08-21 16:06:40'),
(53, 'system_action_access_rights', 1, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(54, 'system_action_access_rights', 2, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(55, 'system_action_access_rights', 3, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(56, 'system_action_access_rights', 4, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(57, 'system_action_access_rights', 5, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(58, 'system_action_access_rights', 6, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(59, 'system_action_access_rights', 7, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(60, 'system_action_access_rights', 8, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(61, 'system_action_access_rights', 9, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:40'),
(62, 'system_action_access_rights', 10, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(63, 'system_action_access_rights', 11, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(64, 'system_action_access_rights', 12, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(65, 'system_action_access_rights', 13, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(66, 'system_action_access_rights', 14, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(67, 'system_action_access_rights', 15, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(68, 'system_action_access_rights', 16, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(69, 'system_action_access_rights', 17, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-08-21 16:06:41'),
(70, 'file_type', 1, 'System action created. <br/><br/>File Type Name: Audio', '0', '2023-08-21 16:06:41'),
(71, 'file_type', 2, 'System action created. <br/><br/>File Type Name: Compressed', '0', '2023-08-21 16:06:41'),
(72, 'file_type', 3, 'System action created. <br/><br/>File Type Name: Disk and Media', '0', '2023-08-21 16:06:41'),
(73, 'file_type', 4, 'System action created. <br/><br/>File Type Name: Data and Database', '0', '2023-08-21 16:06:41'),
(74, 'file_type', 5, 'System action created. <br/><br/>File Type Name: Email', '0', '2023-08-21 16:06:41'),
(75, 'file_type', 6, 'System action created. <br/><br/>File Type Name: Executable', '0', '2023-08-21 16:06:41'),
(76, 'file_type', 7, 'System action created. <br/><br/>File Type Name: Font', '0', '2023-08-21 16:06:41'),
(77, 'file_type', 8, 'System action created. <br/><br/>File Type Name: Image', '0', '2023-08-21 16:06:41'),
(78, 'file_type', 9, 'System action created. <br/><br/>File Type Name: Internet Related', '0', '2023-08-21 16:06:41'),
(79, 'file_type', 10, 'System action created. <br/><br/>File Type Name: Presentation', '0', '2023-08-21 16:06:41'),
(80, 'file_type', 11, 'System action created. <br/><br/>File Type Name: Spreadsheet', '0', '2023-08-21 16:06:41'),
(81, 'file_type', 12, 'System action created. <br/><br/>File Type Name: System Related', '0', '2023-08-21 16:06:41'),
(82, 'file_type', 13, 'System action created. <br/><br/>File Type Name: Video', '0', '2023-08-21 16:06:41'),
(83, 'file_type', 14, 'System action created. <br/><br/>File Type Name: Word Processor', '0', '2023-08-21 16:06:41'),
(84, 'file_extension', 1, 'File extension created. <br/><br/>File Exension Name: aif<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(85, 'file_extension', 2, 'File extension created. <br/><br/>File Exension Name: cda<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(86, 'file_extension', 3, 'File extension created. <br/><br/>File Exension Name: mid<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(87, 'file_extension', 4, 'File extension created. <br/><br/>File Exension Name: midi<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(88, 'file_extension', 5, 'File extension created. <br/><br/>File Exension Name: mp3<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(89, 'file_extension', 6, 'File extension created. <br/><br/>File Exension Name: mpa<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(90, 'file_extension', 7, 'File extension created. <br/><br/>File Exension Name: ogg<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(91, 'file_extension', 8, 'File extension created. <br/><br/>File Exension Name: wav<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(92, 'file_extension', 9, 'File extension created. <br/><br/>File Exension Name: wma<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(93, 'file_extension', 10, 'File extension created. <br/><br/>File Exension Name: wpl<br/>File Type ID: 1', '0', '2023-08-21 16:06:41'),
(94, 'file_extension', 11, 'File extension created. <br/><br/>File Exension Name: 7z<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(95, 'file_extension', 12, 'File extension created. <br/><br/>File Exension Name: arj<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(96, 'file_extension', 13, 'File extension created. <br/><br/>File Exension Name: deb<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(97, 'file_extension', 14, 'File extension created. <br/><br/>File Exension Name: pkg<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(98, 'file_extension', 15, 'File extension created. <br/><br/>File Exension Name: rar<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(99, 'file_extension', 16, 'File extension created. <br/><br/>File Exension Name: rpm<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(100, 'file_extension', 17, 'File extension created. <br/><br/>File Exension Name: tar.gz<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(101, 'file_extension', 18, 'File extension created. <br/><br/>File Exension Name: z<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(102, 'file_extension', 19, 'File extension created. <br/><br/>File Exension Name: zip<br/>File Type ID: 2', '0', '2023-08-21 16:06:41'),
(103, 'file_extension', 20, 'File extension created. <br/><br/>File Exension Name: bin<br/>File Type ID: 3', '0', '2023-08-21 16:06:41'),
(104, 'file_extension', 21, 'File extension created. <br/><br/>File Exension Name: dmg<br/>File Type ID: 3', '0', '2023-08-21 16:06:41'),
(105, 'file_extension', 22, 'File extension created. <br/><br/>File Exension Name: iso<br/>File Type ID: 3', '0', '2023-08-21 16:06:41'),
(106, 'file_extension', 23, 'File extension created. <br/><br/>File Exension Name: toast<br/>File Type ID: 3', '0', '2023-08-21 16:06:41'),
(107, 'file_extension', 24, 'File extension created. <br/><br/>File Exension Name: vcd<br/>File Type ID: 3', '0', '2023-08-21 16:06:41'),
(108, 'file_extension', 25, 'File extension created. <br/><br/>File Exension Name: csv<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(109, 'file_extension', 26, 'File extension created. <br/><br/>File Exension Name: dat<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(110, 'file_extension', 27, 'File extension created. <br/><br/>File Exension Name: db<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(111, 'file_extension', 28, 'File extension created. <br/><br/>File Exension Name: dbf<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(112, 'file_extension', 29, 'File extension created. <br/><br/>File Exension Name: log<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(113, 'file_extension', 30, 'File extension created. <br/><br/>File Exension Name: mdb<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(114, 'file_extension', 31, 'File extension created. <br/><br/>File Exension Name: sav<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(115, 'file_extension', 32, 'File extension created. <br/><br/>File Exension Name: sql<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(116, 'file_extension', 33, 'File extension created. <br/><br/>File Exension Name: tar<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(117, 'file_extension', 34, 'File extension created. <br/><br/>File Exension Name: xml<br/>File Type ID: 4', '0', '2023-08-21 16:06:41'),
(118, 'file_extension', 35, 'File extension created. <br/><br/>File Exension Name: email<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(119, 'file_extension', 36, 'File extension created. <br/><br/>File Exension Name: eml<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(120, 'file_extension', 37, 'File extension created. <br/><br/>File Exension Name: emlx<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(121, 'file_extension', 38, 'File extension created. <br/><br/>File Exension Name: msg<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(122, 'file_extension', 39, 'File extension created. <br/><br/>File Exension Name: oft<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(123, 'file_extension', 40, 'File extension created. <br/><br/>File Exension Name: ost<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(124, 'file_extension', 41, 'File extension created. <br/><br/>File Exension Name: pst<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(125, 'file_extension', 42, 'File extension created. <br/><br/>File Exension Name: vcf<br/>File Type ID: 5', '0', '2023-08-21 16:06:41'),
(126, 'file_extension', 43, 'File extension created. <br/><br/>File Exension Name: apk<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(127, 'file_extension', 44, 'File extension created. <br/><br/>File Exension Name: bat<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(128, 'file_extension', 45, 'File extension created. <br/><br/>File Exension Name: bin<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(129, 'file_extension', 46, 'File extension created. <br/><br/>File Exension Name: cgi<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(130, 'file_extension', 47, 'File extension created. <br/><br/>File Exension Name: pl<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(131, 'file_extension', 48, 'File extension created. <br/><br/>File Exension Name: com<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(132, 'file_extension', 49, 'File extension created. <br/><br/>File Exension Name: exe<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(133, 'file_extension', 50, 'File extension created. <br/><br/>File Exension Name: gadget<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(134, 'file_extension', 51, 'File extension created. <br/><br/>File Exension Name: jar<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(135, 'file_extension', 52, 'File extension created. <br/><br/>File Exension Name: wsf<br/>File Type ID: 6', '0', '2023-08-21 16:06:41'),
(136, 'file_extension', 53, 'File extension created. <br/><br/>File Exension Name: fnt<br/>File Type ID: 7', '0', '2023-08-21 16:06:41'),
(137, 'file_extension', 54, 'File extension created. <br/><br/>File Exension Name: fon<br/>File Type ID: 7', '0', '2023-08-21 16:06:41'),
(138, 'file_extension', 55, 'File extension created. <br/><br/>File Exension Name: otf<br/>File Type ID: 7', '0', '2023-08-21 16:06:41'),
(139, 'file_extension', 56, 'File extension created. <br/><br/>File Exension Name: ttf<br/>File Type ID: 7', '0', '2023-08-21 16:06:41'),
(140, 'file_extension', 57, 'File extension created. <br/><br/>File Exension Name: ai<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(141, 'file_extension', 58, 'File extension created. <br/><br/>File Exension Name: bmp<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(142, 'file_extension', 59, 'File extension created. <br/><br/>File Exension Name: gif<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(143, 'file_extension', 60, 'File extension created. <br/><br/>File Exension Name: ico<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(144, 'file_extension', 61, 'File extension created. <br/><br/>File Exension Name: jpg<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(145, 'file_extension', 62, 'File extension created. <br/><br/>File Exension Name: jpeg<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(146, 'file_extension', 63, 'File extension created. <br/><br/>File Exension Name: png<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(147, 'file_extension', 64, 'File extension created. <br/><br/>File Exension Name: ps<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(148, 'file_extension', 65, 'File extension created. <br/><br/>File Exension Name: psd<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(149, 'file_extension', 66, 'File extension created. <br/><br/>File Exension Name: svg<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(150, 'file_extension', 67, 'File extension created. <br/><br/>File Exension Name: tif<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(151, 'file_extension', 68, 'File extension created. <br/><br/>File Exension Name: tiff<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(152, 'file_extension', 69, 'File extension created. <br/><br/>File Exension Name: webp<br/>File Type ID: 8', '0', '2023-08-21 16:06:41'),
(153, 'file_extension', 70, 'File extension created. <br/><br/>File Exension Name: asp<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(154, 'file_extension', 71, 'File extension created. <br/><br/>File Exension Name: aspx<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(155, 'file_extension', 72, 'File extension created. <br/><br/>File Exension Name: cer<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(156, 'file_extension', 73, 'File extension created. <br/><br/>File Exension Name: cfm<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(157, 'file_extension', 74, 'File extension created. <br/><br/>File Exension Name: cgi<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(158, 'file_extension', 75, 'File extension created. <br/><br/>File Exension Name: pl<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(159, 'file_extension', 76, 'File extension created. <br/><br/>File Exension Name: css<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(160, 'file_extension', 77, 'File extension created. <br/><br/>File Exension Name: htm<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(161, 'file_extension', 78, 'File extension created. <br/><br/>File Exension Name: html<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(162, 'file_extension', 79, 'File extension created. <br/><br/>File Exension Name: js<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(163, 'file_extension', 80, 'File extension created. <br/><br/>File Exension Name: jsp<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(164, 'file_extension', 81, 'File extension created. <br/><br/>File Exension Name: part<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(165, 'file_extension', 82, 'File extension created. <br/><br/>File Exension Name: php<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(166, 'file_extension', 83, 'File extension created. <br/><br/>File Exension Name: py<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(167, 'file_extension', 84, 'File extension created. <br/><br/>File Exension Name: rss<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(168, 'file_extension', 85, 'File extension created. <br/><br/>File Exension Name: xhtml<br/>File Type ID: 9', '0', '2023-08-21 16:06:41'),
(169, 'file_extension', 86, 'File extension created. <br/><br/>File Exension Name: key<br/>File Type ID: 10', '0', '2023-08-21 16:06:41'),
(170, 'file_extension', 87, 'File extension created. <br/><br/>File Exension Name: odp<br/>File Type ID: 10', '0', '2023-08-21 16:06:41'),
(171, 'file_extension', 88, 'File extension created. <br/><br/>File Exension Name: pps<br/>File Type ID: 10', '0', '2023-08-21 16:06:41'),
(172, 'file_extension', 89, 'File extension created. <br/><br/>File Exension Name: ppt<br/>File Type ID: 10', '0', '2023-08-21 16:06:41'),
(173, 'file_extension', 90, 'File extension created. <br/><br/>File Exension Name: pptx<br/>File Type ID: 10', '0', '2023-08-21 16:06:41'),
(174, 'file_extension', 91, 'File extension created. <br/><br/>File Exension Name: ods<br/>File Type ID: 11', '0', '2023-08-21 16:06:41'),
(175, 'file_extension', 92, 'File extension created. <br/><br/>File Exension Name: xls<br/>File Type ID: 11', '0', '2023-08-21 16:06:41'),
(176, 'file_extension', 93, 'File extension created. <br/><br/>File Exension Name: xlsm<br/>File Type ID: 11', '0', '2023-08-21 16:06:41'),
(177, 'file_extension', 94, 'File extension created. <br/><br/>File Exension Name: xlsx<br/>File Type ID: 11', '0', '2023-08-21 16:06:41'),
(178, 'file_extension', 95, 'File extension created. <br/><br/>File Exension Name: bak<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(179, 'file_extension', 96, 'File extension created. <br/><br/>File Exension Name: cab<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(180, 'file_extension', 97, 'File extension created. <br/><br/>File Exension Name: cfg<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(181, 'file_extension', 98, 'File extension created. <br/><br/>File Exension Name: cpl<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(182, 'file_extension', 99, 'File extension created. <br/><br/>File Exension Name: cur<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(183, 'file_extension', 100, 'File extension created. <br/><br/>File Exension Name: dll<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(184, 'file_extension', 101, 'File extension created. <br/><br/>File Exension Name: dmp<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(185, 'file_extension', 102, 'File extension created. <br/><br/>File Exension Name: drv<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(186, 'file_extension', 103, 'File extension created. <br/><br/>File Exension Name: icns<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(187, 'file_extension', 104, 'File extension created. <br/><br/>File Exension Name: ini<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(188, 'file_extension', 105, 'File extension created. <br/><br/>File Exension Name: lnk<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(189, 'file_extension', 106, 'File extension created. <br/><br/>File Exension Name: msi<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(190, 'file_extension', 107, 'File extension created. <br/><br/>File Exension Name: sys<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(191, 'file_extension', 108, 'File extension created. <br/><br/>File Exension Name: tmp<br/>File Type ID: 12', '0', '2023-08-21 16:06:41'),
(192, 'file_extension', 109, 'File extension created. <br/><br/>File Exension Name: 3g2<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(193, 'file_extension', 110, 'File extension created. <br/><br/>File Exension Name: 3gp<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(194, 'file_extension', 111, 'File extension created. <br/><br/>File Exension Name: avi<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(195, 'file_extension', 112, 'File extension created. <br/><br/>File Exension Name: flv<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(196, 'file_extension', 113, 'File extension created. <br/><br/>File Exension Name: h264<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(197, 'file_extension', 114, 'File extension created. <br/><br/>File Exension Name: m4v<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(198, 'file_extension', 115, 'File extension created. <br/><br/>File Exension Name: mkv<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(199, 'file_extension', 116, 'File extension created. <br/><br/>File Exension Name: mov<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(200, 'file_extension', 117, 'File extension created. <br/><br/>File Exension Name: mp4<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(201, 'file_extension', 118, 'File extension created. <br/><br/>File Exension Name: mpg<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(202, 'file_extension', 119, 'File extension created. <br/><br/>File Exension Name: mpeg<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(203, 'file_extension', 120, 'File extension created. <br/><br/>File Exension Name: rm<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(204, 'file_extension', 121, 'File extension created. <br/><br/>File Exension Name: swf<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(205, 'file_extension', 122, 'File extension created. <br/><br/>File Exension Name: vob<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(206, 'file_extension', 123, 'File extension created. <br/><br/>File Exension Name: webm<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(207, 'file_extension', 124, 'File extension created. <br/><br/>File Exension Name: wmv<br/>File Type ID: 13', '0', '2023-08-21 16:06:41'),
(208, 'file_extension', 125, 'File extension created. <br/><br/>File Exension Name: doc<br/>File Type ID: 14', '0', '2023-08-21 16:06:41'),
(209, 'file_extension', 126, 'File extension created. <br/><br/>File Exension Name: docx<br/>File Type ID: 14', '0', '2023-08-21 16:06:41'),
(210, 'file_extension', 127, 'File extension created. <br/><br/>File Exension Name: pdf<br/>File Type ID: 14', '0', '2023-08-21 16:06:41'),
(211, 'file_extension', 128, 'File extension created. <br/><br/>File Exension Name: rtf<br/>File Type ID: 14', '0', '2023-08-21 16:06:41'),
(212, 'file_extension', 129, 'File extension created. <br/><br/>File Exension Name: tex<br/>File Type ID: 14', '0', '2023-08-21 16:06:41'),
(213, 'file_extension', 130, 'File extension created. <br/><br/>File Exension Name: txt<br/>File Type ID: 14', '0', '2023-08-21 16:06:41'),
(214, 'file_extension', 131, 'File extension created. <br/><br/>File Exension Name: wpd<br/>File Type ID: 14', '0', '2023-08-21 16:06:41'),
(215, 'interface_setting', 1, 'Interface setting created. <br/><br/>Interface Setting Name: Login Backgroud<br/>Interface Setting Description: Interface setting for background image on login page.', '0', '2023-08-21 16:06:41'),
(216, 'interface_setting', 2, 'Interface setting created. <br/><br/>Interface Setting Name: Login Logo<br/>Interface Setting Description: Interface setting for logo on login page.', '0', '2023-08-21 16:06:41'),
(217, 'interface_setting', 3, 'Interface setting created. <br/><br/>Interface Setting Name: Navbar Logo<br/>Interface Setting Description: Interface setting for logo on navbar.', '0', '2023-08-21 16:06:41'),
(218, 'system_setting', 1, 'System setting created. <br/><br/>System Setting Name: Max Failed Login Attempt<br/>System Setting Description: This sets the maximum failed login attempt before the user is locked-out.<br/>Value: 5', '0', '2023-08-21 16:07:04'),
(219, 'ui_customization_setting', 1, 'UI Customization created. <br/><br/>Theme Contrast: false<br/>Caption Show: true<br/>Preset Theme: preset-1<br/>Dark Layout: false<br/>RTL Layout: false<br/>Box Container: false', '1', '2023-08-21 16:10:23'),
(220, 'ui_customization_setting', 1, 'Box Container: false -> true<br/>', '1', '2023-08-21 16:10:34'),
(221, 'ui_customization_setting', 1, 'Box Container: true -> false<br/>', '1', '2023-08-21 16:10:39'),
(222, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '0', '2023-08-21 16:18:39'),
(223, 'users', 1, 'Last Failed Login Attempt: 2023-08-21 16:18:39 -> 2023-08-21 16:18:40<br/>Failed Login Attempts: 1 -> 2<br/>', '0', '2023-08-21 16:18:40'),
(224, 'users', 1, 'Failed Login Attempts: 2 -> 3<br/>', '0', '2023-08-21 16:18:40'),
(225, 'users', 1, 'Last Failed Login Attempt: 2023-08-21 16:18:40 -> 2023-08-21 16:18:41<br/>Failed Login Attempts: 3 -> 4<br/>', '0', '2023-08-21 16:18:41'),
(226, 'users', 1, 'Last Failed Login Attempt: 2023-08-21 16:18:41 -> 2023-08-21 16:18:42<br/>Failed Login Attempts: 4 -> 5<br/>', '0', '2023-08-21 16:18:42'),
(227, 'users', 1, 'Failed Login Attempts: 5 -> 6<br/>', '0', '2023-08-21 16:18:42'),
(228, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '0', '2023-08-21 16:18:42'),
(229, 'users', 1, 'Last Failed Login Attempt: 2023-08-21 16:18:42 -> 2023-08-21 16:18:43<br/>Failed Login Attempts: 6 -> 7<br/>', '0', '2023-08-21 16:18:43'),
(230, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '0', '2023-08-21 16:18:43'),
(231, 'users', 1, 'Last Failed Login Attempt: 2023-08-21 16:18:43 -> 2023-08-21 16:18:49<br/>Failed Login Attempts: 7 -> 8<br/>', '0', '2023-08-21 16:18:49'),
(232, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '0', '2023-08-21 16:18:49'),
(233, 'users', 1, 'Last Failed Login Attempt: 2023-08-21 16:18:49 -> 2023-08-21 16:18:50<br/>Failed Login Attempts: 8 -> 9<br/>', '0', '2023-08-21 16:18:50'),
(234, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '0', '2023-08-21 16:18:50'),
(235, 'users', 1, 'Last Failed Login Attempt: 2023-08-21 16:18:50 -> 2023-08-21 16:18:51<br/>Failed Login Attempts: 9 -> 10<br/>', '0', '2023-08-21 16:18:51'),
(236, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '0', '2023-08-21 16:18:51'),
(237, 'system_setting', 2, 'System setting created. <br/><br/>System Setting Name: Max Failed OTP Attempt<br/>System Setting Description: This sets the maximum failed OTP attempt before the user is needs a new OTP code.<br/>Value: 5', '0', '2023-08-21 16:21:59'),
(238, 'interface_setting', 4, 'Interface setting created. <br/><br/>Interface Setting Name: System Icon<br/>Interface Setting Description: Interface setting for system icon.', '0', '2023-08-21 16:37:29'),
(239, 'users', 1, 'Is Locked: 1 -> 0<br/>', '0', '2023-08-21 16:37:47'),
(240, 'users', 1, 'Failed Login Attempts: 10 -> 0<br/>', '0', '2023-08-21 16:37:52'),
(241, 'users', 1, 'Account Lock Duration: 160 -> 0<br/>', '0', '2023-08-21 16:38:02'),
(242, 'upload_setting', 1, 'Upload setting created. <br/><br/>Upload Setting Name: Profile Image<br/>Upload Setting Description: Sets the upload setting when uploading user account profile image.<br/>Max File Size: 5', '0', '2023-08-21 16:58:34'),
(243, 'upload_setting', 2, 'Upload setting created. <br/><br/>Upload Setting Name: Interface Setting<br/>Upload Setting Description: Sets the upload setting when uploading interface setting image.<br/>Max File Size: 5', '0', '2023-08-21 16:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `file_extension`
--

CREATE TABLE `file_extension` (
  `file_extension_id` int(10) UNSIGNED NOT NULL,
  `file_extension_name` varchar(100) NOT NULL,
  `file_type_id` int(10) UNSIGNED NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_extension`
--

INSERT INTO `file_extension` (`file_extension_id`, `file_extension_name`, `file_type_id`, `last_log_by`) VALUES
(1, 'aif', 1, 0),
(2, 'cda', 1, 0),
(3, 'mid', 1, 0),
(4, 'midi', 1, 0),
(5, 'mp3', 1, 0),
(6, 'mpa', 1, 0),
(7, 'ogg', 1, 0),
(8, 'wav', 1, 0),
(9, 'wma', 1, 0),
(10, 'wpl', 1, 0),
(11, '7z', 2, 0),
(12, 'arj', 2, 0),
(13, 'deb', 2, 0),
(14, 'pkg', 2, 0),
(15, 'rar', 2, 0),
(16, 'rpm', 2, 0),
(17, 'tar.gz', 2, 0),
(18, 'z', 2, 0),
(19, 'zip', 2, 0),
(20, 'bin', 3, 0),
(21, 'dmg', 3, 0),
(22, 'iso', 3, 0),
(23, 'toast', 3, 0),
(24, 'vcd', 3, 0),
(25, 'csv', 4, 0),
(26, 'dat', 4, 0),
(27, 'db', 4, 0),
(28, 'dbf', 4, 0),
(29, 'log', 4, 0),
(30, 'mdb', 4, 0),
(31, 'sav', 4, 0),
(32, 'sql', 4, 0),
(33, 'tar', 4, 0),
(34, 'xml', 4, 0),
(35, 'email', 5, 0),
(36, 'eml', 5, 0),
(37, 'emlx', 5, 0),
(38, 'msg', 5, 0),
(39, 'oft', 5, 0),
(40, 'ost', 5, 0),
(41, 'pst', 5, 0),
(42, 'vcf', 5, 0),
(43, 'apk', 6, 0),
(44, 'bat', 6, 0),
(45, 'bin', 6, 0),
(46, 'cgi', 6, 0),
(47, 'pl', 6, 0),
(48, 'com', 6, 0),
(49, 'exe', 6, 0),
(50, 'gadget', 6, 0),
(51, 'jar', 6, 0),
(52, 'wsf', 6, 0),
(53, 'fnt', 7, 0),
(54, 'fon', 7, 0),
(55, 'otf', 7, 0),
(56, 'ttf', 7, 0),
(57, 'ai', 8, 0),
(58, 'bmp', 8, 0),
(59, 'gif', 8, 0),
(60, 'ico', 8, 0),
(61, 'jpg', 8, 0),
(62, 'jpeg', 8, 0),
(63, 'png', 8, 0),
(64, 'ps', 8, 0),
(65, 'psd', 8, 0),
(66, 'svg', 8, 0),
(67, 'tif', 8, 0),
(68, 'tiff', 8, 0),
(69, 'webp', 8, 0),
(70, 'asp', 9, 0),
(71, 'aspx', 9, 0),
(72, 'cer', 9, 0),
(73, 'cfm', 9, 0),
(74, 'cgi', 9, 0),
(75, 'pl', 9, 0),
(76, 'css', 9, 0),
(77, 'htm', 9, 0),
(78, 'html', 9, 0),
(79, 'js', 9, 0),
(80, 'jsp', 9, 0),
(81, 'part', 9, 0),
(82, 'php', 9, 0),
(83, 'py', 9, 0),
(84, 'rss', 9, 0),
(85, 'xhtml', 9, 0),
(86, 'key', 10, 0),
(87, 'odp', 10, 0),
(88, 'pps', 10, 0),
(89, 'ppt', 10, 0),
(90, 'pptx', 10, 0),
(91, 'ods', 11, 0),
(92, 'xls', 11, 0),
(93, 'xlsm', 11, 0),
(94, 'xlsx', 11, 0),
(95, 'bak', 12, 0),
(96, 'cab', 12, 0),
(97, 'cfg', 12, 0),
(98, 'cpl', 12, 0),
(99, 'cur', 12, 0),
(100, 'dll', 12, 0),
(101, 'dmp', 12, 0),
(102, 'drv', 12, 0),
(103, 'icns', 12, 0),
(104, 'ini', 12, 0),
(105, 'lnk', 12, 0),
(106, 'msi', 12, 0),
(107, 'sys', 12, 0),
(108, 'tmp', 12, 0),
(109, '3g2', 13, 0),
(110, '3gp', 13, 0),
(111, 'avi', 13, 0),
(112, 'flv', 13, 0),
(113, 'h264', 13, 0),
(114, 'm4v', 13, 0),
(115, 'mkv', 13, 0),
(116, 'mov', 13, 0),
(117, 'mp4', 13, 0),
(118, 'mpg', 13, 0),
(119, 'mpeg', 13, 0),
(120, 'rm', 13, 0),
(121, 'swf', 13, 0),
(122, 'vob', 13, 0),
(123, 'webm', 13, 0),
(124, 'wmv', 13, 0),
(125, 'doc', 14, 0),
(126, 'docx', 14, 0),
(127, 'pdf', 14, 0),
(128, 'rtf', 14, 0),
(129, 'tex', 14, 0),
(130, 'txt', 14, 0),
(131, 'wpd', 14, 0);

--
-- Triggers `file_extension`
--
DELIMITER $$
CREATE TRIGGER `file_extension_trigger_insert` AFTER INSERT ON `file_extension` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'File extension created. <br/>';

    IF NEW.file_extension_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File Exension Name: ", NEW.file_extension_name);
    END IF;

    IF NEW.file_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File Type ID: ", NEW.file_type_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('file_extension', NEW.file_extension_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `file_extension_trigger_update` AFTER UPDATE ON `file_extension` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.file_extension_name <> OLD.file_extension_name THEN
        SET audit_log = CONCAT(audit_log, "File Exension Name: ", OLD.file_extension_name, " -> ", NEW.file_extension_name, "<br/>");
    END IF;

    IF NEW.file_type_id <> OLD.file_type_id THEN
        SET audit_log = CONCAT(audit_log, "File Type ID: ", OLD.file_type_id, " -> ", NEW.file_type_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('file_extension', NEW.file_extension_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `file_type`
--

CREATE TABLE `file_type` (
  `file_type_id` int(10) UNSIGNED NOT NULL,
  `file_type_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_type`
--

INSERT INTO `file_type` (`file_type_id`, `file_type_name`, `last_log_by`) VALUES
(1, 'Audio', 0),
(2, 'Compressed', 0),
(3, 'Disk and Media', 0),
(4, 'Data and Database', 0),
(5, 'Email', 0),
(6, 'Executable', 0),
(7, 'Font', 0),
(8, 'Image', 0),
(9, 'Internet Related', 0),
(10, 'Presentation', 0),
(11, 'Spreadsheet', 0),
(12, 'System Related', 0),
(13, 'Video', 0),
(14, 'Word Processor', 0);

--
-- Triggers `file_type`
--
DELIMITER $$
CREATE TRIGGER `file_type_trigger_insert` AFTER INSERT ON `file_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'System action created. <br/>';

    IF NEW.file_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File Type Name: ", NEW.file_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('file_type', NEW.file_type_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `file_type_trigger_update` AFTER UPDATE ON `file_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.file_type_name <> OLD.file_type_name THEN
        SET audit_log = CONCAT(audit_log, "File Type Name: ", OLD.file_type_name, " -> ", NEW.file_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('file_type', NEW.file_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `interface_setting`
--

CREATE TABLE `interface_setting` (
  `interface_setting_id` int(10) UNSIGNED NOT NULL,
  `interface_setting_name` varchar(100) NOT NULL,
  `interface_setting_description` varchar(200) NOT NULL,
  `value` varchar(1000) DEFAULT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interface_setting`
--

INSERT INTO `interface_setting` (`interface_setting_id`, `interface_setting_name`, `interface_setting_description`, `value`, `last_log_by`) VALUES
(1, 'Login Backgroud', 'Interface setting for background image on login page.', NULL, 0),
(2, 'Login Logo', 'Interface setting for logo on login page.', NULL, 0),
(3, 'Navbar Logo', 'Interface setting for logo on navbar.', NULL, 0),
(4, 'System Icon', 'Interface setting for system icon.', NULL, 0);

--
-- Triggers `interface_setting`
--
DELIMITER $$
CREATE TRIGGER `interface_setting_trigger_insert` AFTER INSERT ON `interface_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Interface setting created. <br/>';

    IF NEW.interface_setting_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Interface Setting Name: ", NEW.interface_setting_name);
    END IF;

    IF NEW.interface_setting_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Interface Setting Description: ", NEW.interface_setting_description);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('interface_setting', NEW.interface_setting_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `interface_setting_trigger_update` AFTER UPDATE ON `interface_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.interface_setting_name <> OLD.interface_setting_name THEN
        SET audit_log = CONCAT(audit_log, "Interface Setting Name: ", OLD.interface_setting_name, " -> ", NEW.interface_setting_name, "<br/>");
    END IF;

    IF NEW.interface_setting_description <> OLD.interface_setting_description THEN
        SET audit_log = CONCAT(audit_log, "Interface Setting Description: ", OLD.interface_setting_description, " -> ", NEW.interface_setting_description, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('interface_setting', NEW.interface_setting_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_group`
--

CREATE TABLE `menu_group` (
  `menu_group_id` int(10) UNSIGNED NOT NULL,
  `menu_group_name` varchar(100) NOT NULL,
  `order_sequence` tinyint(10) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_group`
--

INSERT INTO `menu_group` (`menu_group_id`, `menu_group_name`, `order_sequence`, `last_log_by`) VALUES
(1, 'Technical', 100, 0),
(2, 'Human Resources', 50, 0);

--
-- Triggers `menu_group`
--
DELIMITER $$
CREATE TRIGGER `menu_group_trigger_insert` AFTER INSERT ON `menu_group` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Menu group created. <br/>';

    IF NEW.menu_group_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Menu Group Name: ", NEW.menu_group_name);
    END IF;

    IF NEW.order_sequence <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Order Sequence: ", NEW.order_sequence);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('menu_group', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `menu_group_trigger_update` AFTER UPDATE ON `menu_group` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.menu_group_name <> OLD.menu_group_name THEN
        SET audit_log = CONCAT(audit_log, "Menu Group Name: ", OLD.menu_group_name, " -> ", NEW.menu_group_name, "<br/>");
    END IF;

    IF NEW.order_sequence <> OLD.order_sequence THEN
        SET audit_log = CONCAT(audit_log, "Order Sequence: ", OLD.order_sequence, " -> ", NEW.order_sequence, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('menu_group', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `menu_item_id` int(10) UNSIGNED NOT NULL,
  `menu_item_name` varchar(100) NOT NULL,
  `menu_group_id` int(10) UNSIGNED NOT NULL,
  `menu_item_url` varchar(50) DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `menu_item_icon` varchar(150) DEFAULT NULL,
  `order_sequence` tinyint(10) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`menu_item_id`, `menu_item_name`, `menu_group_id`, `menu_item_url`, `parent_id`, `menu_item_icon`, `order_sequence`, `last_log_by`) VALUES
(1, 'User Interface', 1, '', 0, 'sidebar', 50, 0),
(2, 'Menu Group', 1, 'menu-group.php', 1, '', 1, 0),
(3, 'Menu Item', 1, 'menu-item.php', 1, '', 2, 0),
(4, 'Administration', 1, '', 0, 'shield', 1, 0),
(5, 'System Action', 1, 'system-action.php', 4, '', 15, 0),
(6, 'Role Configuration', 1, 'role-configuration.php', 4, '', 10, 0),
(7, 'Role', 1, 'role.php', 4, '', 9, 0),
(8, 'User Account', 1, 'user-account.php', 4, '', 1, 0),
(9, 'Configurations', 1, '', 0, 'settings', 30, 0),
(10, 'File Type', 1, 'file-type.php', 9, '', 30, 0),
(11, 'File Extension', 1, 'file-extension.php', 9, '', 31, 0),
(12, 'Upload Setting', 1, 'upload-setting.php', 9, '', 29, 0),
(13, 'Interface Setting', 1, 'interface-setting.php', 1, '', 3, 0),
(14, 'System Setting', 1, 'system-setting.php', 4, '', 16, 0);

--
-- Triggers `menu_item`
--
DELIMITER $$
CREATE TRIGGER `menu_item_trigger_insert` AFTER INSERT ON `menu_item` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `menu_item_trigger_update` AFTER UPDATE ON `menu_item` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.menu_item_name <> OLD.menu_item_name THEN
        SET audit_log = CONCAT(audit_log, "Menu Item Name: ", OLD.menu_item_name, " -> ", NEW.menu_item_name, "<br/>");
    END IF;

    IF NEW.menu_group_id <> OLD.menu_group_id THEN
        SET audit_log = CONCAT(audit_log, "Menu Group ID: ", OLD.menu_group_id, " -> ", NEW.menu_group_id, "<br/>");
    END IF;

    IF NEW.menu_item_url <> OLD.menu_item_url THEN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_access_right`
--

CREATE TABLE `menu_item_access_right` (
  `menu_item_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `read_access` tinyint(1) NOT NULL,
  `write_access` tinyint(1) NOT NULL,
  `create_access` tinyint(1) NOT NULL,
  `delete_access` tinyint(1) NOT NULL,
  `duplicate_access` tinyint(1) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item_access_right`
--

INSERT INTO `menu_item_access_right` (`menu_item_id`, `role_id`, `read_access`, `write_access`, `create_access`, `delete_access`, `duplicate_access`, `last_log_by`) VALUES
(1, 1, 1, 0, 0, 0, 0, 0),
(2, 1, 1, 1, 1, 1, 1, 0),
(3, 1, 1, 1, 1, 1, 1, 0),
(4, 1, 1, 0, 0, 0, 0, 0),
(5, 1, 1, 1, 1, 1, 1, 0),
(6, 1, 1, 1, 1, 1, 1, 0),
(7, 1, 1, 1, 0, 0, 0, 0),
(8, 1, 1, 0, 0, 0, 0, 0),
(9, 1, 1, 0, 0, 0, 0, 0),
(10, 1, 1, 1, 1, 1, 1, 0),
(11, 1, 1, 1, 1, 1, 1, 0),
(12, 1, 1, 1, 1, 1, 1, 0),
(13, 1, 1, 1, 1, 1, 1, 0),
(14, 1, 1, 1, 1, 1, 1, 0);

--
-- Triggers `menu_item_access_right`
--
DELIMITER $$
CREATE TRIGGER `menu_item_access_right_insert` AFTER INSERT ON `menu_item_access_right` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Menu item access rights created. <br/>';

    IF NEW.role_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Role ID: ", NEW.role_id);
    END IF;

    IF NEW.read_access <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Read Access: ", NEW.read_access);
    END IF;

    IF NEW.write_access <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Write Access: ", NEW.write_access);
    END IF;

    IF NEW.create_access <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Create Access: ", NEW.create_access);
    END IF;

    IF NEW.delete_access <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Delete Access: ", NEW.delete_access);
    END IF;

    IF NEW.duplicate_access <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Duplicate Access: ", NEW.duplicate_access);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('menu_item_access_right', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `menu_item_access_right_update` AFTER UPDATE ON `menu_item_access_right` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    SET audit_log = CONCAT(audit_log, "Role ID: ", OLD.role_id, "<br/>");

    IF NEW.read_access <> OLD.read_access THEN
        SET audit_log = CONCAT(audit_log, "Read Access: ", OLD.read_access, " -> ", NEW.read_access, "<br/>");
    END IF;

    IF NEW.write_access <> OLD.write_access THEN
        SET audit_log = CONCAT(audit_log, "Write Access: ", OLD.write_access, " -> ", NEW.write_access, "<br/>");
    END IF;

    IF NEW.create_access <> OLD.create_access THEN
        SET audit_log = CONCAT(audit_log, "Create Access: ", OLD.create_access, " -> ", NEW.create_access, "<br/>");
    END IF;

    IF NEW.delete_access <> OLD.delete_access THEN
        SET audit_log = CONCAT(audit_log, "Delete Access: ", OLD.delete_access, " -> ", NEW.delete_access, "<br/>");
    END IF;

    IF NEW.duplicate_access <> OLD.duplicate_access THEN
        SET audit_log = CONCAT(audit_log, "Duplicate Access: ", OLD.duplicate_access, " -> ", NEW.duplicate_access, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('menu_item_access_right', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `password_history`
--

CREATE TABLE `password_history` (
  `password_history_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_change_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_description` varchar(200) NOT NULL,
  `assignable` tinyint(1) NOT NULL DEFAULT 1,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_description`, `assignable`, `last_log_by`) VALUES
(1, 'Super Admin', 'This role has the highest level of access and full control over the entire system. Super Admins can perform all actions, including managing other user accounts, configuring system settings, and access', 0, 0),
(2, 'Administrator', 'Full access to all features and data within the system. This role have similar access levels to the Admin but is not as powerful as the Super Admin.', 1, 0),
(3, 'Manager', 'Access to manage specific aspects of the system or resources related to their teams or departments.', 1, 0),
(4, 'Employee', 'The typical user account with standard access to use the system features and functionalities.', 1, 0);

--
-- Triggers `role`
--
DELIMITER $$
CREATE TRIGGER `role_trigger_insert` AFTER INSERT ON `role` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `role_trigger_update` AFTER UPDATE ON `role` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

CREATE TABLE `role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_users`
--

INSERT INTO `role_users` (`role_id`, `user_id`, `last_log_by`) VALUES
(1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `system_action`
--

CREATE TABLE `system_action` (
  `system_action_id` int(10) UNSIGNED NOT NULL,
  `system_action_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_action`
--

INSERT INTO `system_action` (`system_action_id`, `system_action_name`, `last_log_by`) VALUES
(1, 'Update Menu Item Role Access', 0),
(2, 'Delete Menu Item Role Access', 0),
(3, 'Update System Action Role Access', 0),
(4, 'Delete System Action Role Access', 0),
(5, 'Assign User Account To Role', 0),
(6, 'Delete User Account To Role', 0),
(7, 'Assign Role To User Account', 0),
(8, 'Delete Role To User Account', 0),
(9, 'Activate User Account', 0),
(10, 'Deactivate User Account', 0),
(11, 'Lock User Account', 0),
(12, 'Unlock User Account', 0),
(13, 'Change User Account Password', 0),
(14, 'Change User Account Profile Picture', 0),
(15, 'Assign File Extension To Upload Setting', 0),
(16, 'Delete File Extension To Upload Setting', 0),
(17, 'Send Reset Password Instructions', 0);

--
-- Triggers `system_action`
--
DELIMITER $$
CREATE TRIGGER `system_action_trigger_insert` AFTER INSERT ON `system_action` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'System action created. <br/>';

    IF NEW.system_action_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>System Action Name: ", NEW.system_action_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('system_action', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `system_action_trigger_update` AFTER UPDATE ON `system_action` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.system_action_name <> OLD.system_action_name THEN
        SET audit_log = CONCAT(audit_log, "System Action Name: ", OLD.system_action_name, " -> ", NEW.system_action_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('system_action', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `system_action_access_rights`
--

CREATE TABLE `system_action_access_rights` (
  `system_action_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_access` tinyint(1) NOT NULL,
  `last_log_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_action_access_rights`
--

INSERT INTO `system_action_access_rights` (`system_action_id`, `role_id`, `role_access`, `last_log_by`) VALUES
(1, 1, 1, 0),
(2, 1, 1, 0),
(3, 1, 1, 0),
(4, 1, 1, 0),
(5, 1, 1, 0),
(6, 1, 1, 0),
(7, 1, 1, 0),
(8, 1, 1, 0),
(9, 1, 1, 0),
(10, 1, 1, 0),
(11, 1, 1, 0),
(12, 1, 1, 0),
(13, 1, 1, 0),
(14, 1, 1, 0),
(15, 1, 1, 0),
(16, 1, 1, 0),
(17, 1, 1, 0);

--
-- Triggers `system_action_access_rights`
--
DELIMITER $$
CREATE TRIGGER `system_action_access_rights_insert` AFTER INSERT ON `system_action_access_rights` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'System action access rights created. <br/>';

    IF NEW.role_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Role ID: ", NEW.role_id);
    END IF;

    IF NEW.role_access <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Role Access: ", NEW.role_access);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('system_action_access_rights', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `system_action_access_rights_update` AFTER UPDATE ON `system_action_access_rights` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    SET audit_log = CONCAT(audit_log, "Role ID: ", OLD.role_id, "<br/>");

    IF NEW.role_access <> OLD.role_access THEN
        SET audit_log = CONCAT(audit_log, "Role Access: ", OLD.role_access, " -> ", NEW.role_access, "<br/>");
    END IF;

    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('system_action_access_rights', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `system_setting`
--

CREATE TABLE `system_setting` (
  `system_setting_id` int(10) UNSIGNED NOT NULL,
  `system_setting_name` varchar(100) NOT NULL,
  `system_setting_description` varchar(200) NOT NULL,
  `value` varchar(1000) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_setting`
--

INSERT INTO `system_setting` (`system_setting_id`, `system_setting_name`, `system_setting_description`, `value`, `last_log_by`) VALUES
(1, 'Max Failed Login Attempt', 'This sets the maximum failed login attempt before the user is locked-out.', '5', 0),
(2, 'Max Failed OTP Attempt', 'This sets the maximum failed OTP attempt before the user is needs a new OTP code.', '5', 0);

--
-- Triggers `system_setting`
--
DELIMITER $$
CREATE TRIGGER `system_setting_trigger_insert` AFTER INSERT ON `system_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'System setting created. <br/>';

    IF NEW.system_setting_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>System Setting Name: ", NEW.system_setting_name);
    END IF;

    IF NEW.system_setting_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>System Setting Description: ", NEW.system_setting_description);
    END IF;

    IF NEW.value <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Value: ", NEW.value);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('system_setting', NEW.system_setting_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `system_setting_trigger_update` AFTER UPDATE ON `system_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.system_setting_name <> OLD.system_setting_name THEN
        SET audit_log = CONCAT(audit_log, "System Setting Name: ", OLD.system_setting_name, " -> ", NEW.system_setting_name, "<br/>");
    END IF;

    IF NEW.system_setting_description <> OLD.system_setting_description THEN
        SET audit_log = CONCAT(audit_log, "System Setting Description: ", OLD.system_setting_description, " -> ", NEW.system_setting_description, "<br/>");
    END IF;

    IF NEW.value <> OLD.value THEN
        SET audit_log = CONCAT(audit_log, "Value: ", OLD.value, " -> ", NEW.value, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('system_setting', NEW.system_setting_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ui_customization_setting`
--

CREATE TABLE `ui_customization_setting` (
  `ui_customization_setting_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `theme_contrast` varchar(15) NOT NULL DEFAULT 'false',
  `caption_show` varchar(15) NOT NULL DEFAULT 'true',
  `preset_theme` varchar(15) NOT NULL DEFAULT 'preset-1',
  `dark_layout` varchar(15) NOT NULL DEFAULT 'false',
  `rtl_layout` varchar(15) NOT NULL DEFAULT 'false',
  `box_container` varchar(15) NOT NULL DEFAULT 'false',
  `last_log_by` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ui_customization_setting`
--

INSERT INTO `ui_customization_setting` (`ui_customization_setting_id`, `user_id`, `theme_contrast`, `caption_show`, `preset_theme`, `dark_layout`, `rtl_layout`, `box_container`, `last_log_by`) VALUES
(1, 1, 'false', 'true', 'preset-1', 'false', 'false', 'false', 1);

--
-- Triggers `ui_customization_setting`
--
DELIMITER $$
CREATE TRIGGER `uiCustomizationSettingTriggerInsert` AFTER INSERT ON `ui_customization_setting` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `uiCustomizationSettingTriggerUpdate` AFTER UPDATE ON `ui_customization_setting` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `upload_setting`
--

CREATE TABLE `upload_setting` (
  `upload_setting_id` int(10) UNSIGNED NOT NULL,
  `upload_setting_name` varchar(100) NOT NULL,
  `upload_setting_description` varchar(200) NOT NULL,
  `max_file_size` double NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upload_setting`
--

INSERT INTO `upload_setting` (`upload_setting_id`, `upload_setting_name`, `upload_setting_description`, `max_file_size`, `last_log_by`) VALUES
(1, 'Profile Image', 'Sets the upload setting when uploading user account profile image.', 5, 0),
(2, 'Interface Setting', 'Sets the upload setting when uploading interface setting image.', 5, 0);

--
-- Triggers `upload_setting`
--
DELIMITER $$
CREATE TRIGGER `upload_setting_trigger_insert` AFTER INSERT ON `upload_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Upload setting created. <br/>';

    IF NEW.upload_setting_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Upload Setting Name: ", NEW.upload_setting_name);
    END IF;

    IF NEW.upload_setting_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Upload Setting Description: ", NEW.upload_setting_description);
    END IF;

    IF NEW.max_file_size <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Max File Size: ", NEW.max_file_size);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('upload_setting', NEW.upload_setting_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `upload_setting_trigger_update` AFTER UPDATE ON `upload_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.upload_setting_name <> OLD.upload_setting_name THEN
        SET audit_log = CONCAT(audit_log, "Upload Setting Name: ", OLD.upload_setting_name, " -> ", NEW.upload_setting_name, "<br/>");
    END IF;

    IF NEW.upload_setting_description <> OLD.upload_setting_description THEN
        SET audit_log = CONCAT(audit_log, "Upload Setting Description: ", OLD.upload_setting_description, " -> ", NEW.upload_setting_description, "<br/>");
    END IF;

    IF NEW.max_file_size <> OLD.max_file_size THEN
        SET audit_log = CONCAT(audit_log, "Max File Size: ", OLD.max_file_size, " -> ", NEW.max_file_size, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('upload_setting', NEW.upload_setting_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `upload_setting_file_extension`
--

CREATE TABLE `upload_setting_file_extension` (
  `upload_setting_id` int(10) UNSIGNED NOT NULL,
  `file_extension_id` int(10) UNSIGNED NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upload_setting_file_extension`
--

INSERT INTO `upload_setting_file_extension` (`upload_setting_id`, `file_extension_id`, `last_log_by`) VALUES
(1, 61, 0),
(1, 62, 0),
(1, 63, 0),
(1, 66, 0),
(1, 69, 0),
(2, 61, 0),
(2, 62, 0),
(2, 63, 0),
(2, 66, 0),
(2, 69, 0),
(2, 60, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `file_as` varchar(300) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(500) DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `last_failed_login_attempt` datetime DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `last_connection_date` datetime DEFAULT NULL,
  `password_expiry_date` date NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry_date` datetime DEFAULT NULL,
  `receive_notification` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_auth` tinyint(1) NOT NULL DEFAULT 0,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expiry_date` datetime DEFAULT NULL,
  `failed_otp_attempts` int(11) NOT NULL DEFAULT 0,
  `last_password_change` datetime DEFAULT NULL,
  `account_lock_duration` int(11) NOT NULL DEFAULT 0,
  `last_password_reset` datetime DEFAULT NULL,
  `remember_me` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(255) DEFAULT NULL,
  `last_log_by` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `file_as`, `email`, `password`, `profile_picture`, `is_locked`, `is_active`, `last_failed_login_attempt`, `failed_login_attempts`, `last_connection_date`, `password_expiry_date`, `reset_token`, `reset_token_expiry_date`, `receive_notification`, `two_factor_auth`, `otp`, `otp_expiry_date`, `failed_otp_attempts`, `last_password_change`, `account_lock_duration`, `last_password_reset`, `remember_me`, `remember_token`, `last_log_by`) VALUES
(1, 'Administrator', 'ldagulto@encorefinancials.com', 'RYHObc8sNwIxdPDNJwCsO8bXKZJXYx7RjTgEWMC17FY%3D', NULL, 0, 1, NULL, 0, '2023-08-21 16:38:23', '2023-12-30', NULL, NULL, 0, 1, 'dfokdssojyS5vtwW53Op%2BHXop4gSh4zGIg68fcpb9KM%3D', '2023-08-21 16:43:04', 0, NULL, 0, NULL, 0, NULL, 0);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `userTriggerInsert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'User created. <br/>';

    IF NEW.file_as <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File As: ", NEW.file_as);
    END IF;

    IF NEW.email <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email: ", NEW.email);
    END IF;

    IF NEW.is_locked <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Is Locked: ", NEW.is_locked);
    END IF;

    IF NEW.is_active <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Is Active: ", NEW.is_active);
    END IF;

    IF NEW.last_failed_login_attempt <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Failed Login Attempt: ", NEW.last_failed_login_attempt);
    END IF;

    IF NEW.failed_login_attempts <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Failed Login Attempts: ", NEW.failed_login_attempts);
    END IF;

    IF NEW.last_connection_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Connection Date: ", NEW.last_connection_date);
    END IF;

    IF NEW.password_expiry_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Password Expiry Date: ", NEW.password_expiry_date);
    END IF;

    IF NEW.receive_notification <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Receive Notification: ", NEW.receive_notification);
    END IF;

    IF NEW.two_factor_auth <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>2-Factor Authentication: ", NEW.two_factor_auth);
    END IF;

    IF NEW.last_password_change <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Password Change: ", NEW.last_password_change);
    END IF;

    IF NEW.account_lock_duration <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Account Lock Duration: ", NEW.account_lock_duration);
    END IF;

    IF NEW.last_password_reset <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Password Reset: ", NEW.last_password_reset);
    END IF;

    IF NEW.remember_me <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remember Me: ", NEW.remember_me);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('users', NEW.user_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `userTriggerUpdate` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.file_as <> OLD.file_as THEN
        SET audit_log = CONCAT(audit_log, "File As: ", OLD.file_as, " -> ", NEW.file_as, "<br/>");
    END IF;

    IF NEW.email <> OLD.email THEN
        SET audit_log = CONCAT(audit_log, "Email: ", OLD.email, " -> ", NEW.email, "<br/>");
    END IF;

    IF NEW.is_locked <> OLD.is_locked THEN
        SET audit_log = CONCAT(audit_log, "Is Locked: ", OLD.is_locked, " -> ", NEW.is_locked, "<br/>");
    END IF;

    IF NEW.is_active <> OLD.is_active THEN
        SET audit_log = CONCAT(audit_log, "Is Active: ", OLD.is_active, " -> ", NEW.is_active, "<br/>");
    END IF;

    IF NEW.last_failed_login_attempt <> OLD.last_failed_login_attempt THEN
        SET audit_log = CONCAT(audit_log, "Last Failed Login Attempt: ", OLD.last_failed_login_attempt, " -> ", NEW.last_failed_login_attempt, "<br/>");
    END IF;

    IF NEW.failed_login_attempts <> OLD.failed_login_attempts THEN
        SET audit_log = CONCAT(audit_log, "Failed Login Attempts: ", OLD.failed_login_attempts, " -> ", NEW.failed_login_attempts, "<br/>");
    END IF;

    IF NEW.last_connection_date <> OLD.last_connection_date THEN
        SET audit_log = CONCAT(audit_log, "Last Connection Date: ", OLD.last_connection_date, " -> ", NEW.last_connection_date, "<br/>");
    END IF;

    IF NEW.password_expiry_date <> OLD.password_expiry_date THEN
        SET audit_log = CONCAT(audit_log, "Password Expiry Date: ", OLD.password_expiry_date, " -> ", NEW.password_expiry_date, "<br/>");
    END IF;

    IF NEW.receive_notification <> OLD.receive_notification THEN
        SET audit_log = CONCAT(audit_log, "Receive Notification: ", OLD.receive_notification, " -> ", NEW.receive_notification, "<br/>");
    END IF;

    IF NEW.two_factor_auth <> OLD.two_factor_auth THEN
        SET audit_log = CONCAT(audit_log, "2-Factor Authentication: ", OLD.two_factor_auth, " -> ", NEW.two_factor_auth, "<br/>");
    END IF;

    IF NEW.last_password_change <> OLD.last_password_change THEN
        SET audit_log = CONCAT(audit_log, "Last Password Change: ", OLD.last_password_change, " -> ", NEW.last_password_change, "<br/>");
    END IF;

    IF NEW.account_lock_duration <> OLD.account_lock_duration THEN
        SET audit_log = CONCAT(audit_log, "Account Lock Duration: ", OLD.account_lock_duration, " -> ", NEW.account_lock_duration, "<br/>");
    END IF;

    IF NEW.last_password_reset <> OLD.last_password_reset THEN
        SET audit_log = CONCAT(audit_log, "Last Password Reset: ", OLD.last_password_reset, " -> ", NEW.last_password_reset, "<br/>");
    END IF;

    IF NEW.remember_me <> OLD.remember_me THEN
        SET audit_log = CONCAT(audit_log, "Remember Me: ", OLD.remember_me, " -> ", NEW.remember_me, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('users', NEW.user_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`audit_log_id`),
  ADD KEY `audit_log_index_external_id` (`audit_log_id`),
  ADD KEY `audit_log_index_table_name` (`table_name`),
  ADD KEY `audit_log_index_reference_id` (`reference_id`);

--
-- Indexes for table `file_extension`
--
ALTER TABLE `file_extension`
  ADD PRIMARY KEY (`file_extension_id`),
  ADD KEY `file_extension_index_file_extension_id` (`file_extension_id`),
  ADD KEY `file_type_id` (`file_type_id`);

--
-- Indexes for table `file_type`
--
ALTER TABLE `file_type`
  ADD PRIMARY KEY (`file_type_id`),
  ADD KEY `file_type_index_file_type_id` (`file_type_id`);

--
-- Indexes for table `interface_setting`
--
ALTER TABLE `interface_setting`
  ADD PRIMARY KEY (`interface_setting_id`),
  ADD KEY `interface_setting_index_interface_setting_id` (`interface_setting_id`);

--
-- Indexes for table `menu_group`
--
ALTER TABLE `menu_group`
  ADD PRIMARY KEY (`menu_group_id`),
  ADD KEY `menu_group_index_menu_group_id` (`menu_group_id`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`menu_item_id`),
  ADD KEY `menu_item_index_menu_item_id` (`menu_item_id`),
  ADD KEY `menu_group_id` (`menu_group_id`);

--
-- Indexes for table `password_history`
--
ALTER TABLE `password_history`
  ADD PRIMARY KEY (`password_history_id`),
  ADD KEY `password_history_index_password_history_id` (`password_history_id`),
  ADD KEY `password_history_index_user_id` (`user_id`),
  ADD KEY `password_history_index_email` (`email`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `role_index_role_id` (`role_id`);

--
-- Indexes for table `role_users`
--
ALTER TABLE `role_users`
  ADD KEY `role_users_index_role_id` (`role_id`),
  ADD KEY `role_users_index_user_id` (`user_id`);

--
-- Indexes for table `system_action`
--
ALTER TABLE `system_action`
  ADD PRIMARY KEY (`system_action_id`),
  ADD KEY `system_action_index_system_action_id` (`system_action_id`);

--
-- Indexes for table `system_setting`
--
ALTER TABLE `system_setting`
  ADD PRIMARY KEY (`system_setting_id`),
  ADD KEY `system_setting_index_system_setting_id` (`system_setting_id`);

--
-- Indexes for table `ui_customization_setting`
--
ALTER TABLE `ui_customization_setting`
  ADD PRIMARY KEY (`ui_customization_setting_id`),
  ADD KEY `ui_customization_setting_index_ui_customization_setting_id` (`ui_customization_setting_id`),
  ADD KEY `ui_customization_setting_index_user_id` (`user_id`);

--
-- Indexes for table `upload_setting`
--
ALTER TABLE `upload_setting`
  ADD PRIMARY KEY (`upload_setting_id`),
  ADD KEY `upload_setting_index_upload_setting_id` (`upload_setting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `users_index_user_id` (`user_id`),
  ADD KEY `users_index_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `audit_log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `file_extension`
--
ALTER TABLE `file_extension`
  MODIFY `file_extension_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `file_type`
--
ALTER TABLE `file_type`
  MODIFY `file_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `interface_setting`
--
ALTER TABLE `interface_setting`
  MODIFY `interface_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_group`
--
ALTER TABLE `menu_group`
  MODIFY `menu_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `password_history`
--
ALTER TABLE `password_history`
  MODIFY `password_history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_action`
--
ALTER TABLE `system_action`
  MODIFY `system_action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `system_setting`
--
ALTER TABLE `system_setting`
  MODIFY `system_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ui_customization_setting`
--
ALTER TABLE `ui_customization_setting`
  MODIFY `ui_customization_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `upload_setting`
--
ALTER TABLE `upload_setting`
  MODIFY `upload_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_extension`
--
ALTER TABLE `file_extension`
  ADD CONSTRAINT `file_extension_ibfk_1` FOREIGN KEY (`file_type_id`) REFERENCES `file_type` (`file_type_id`);

--
-- Constraints for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`menu_group_id`) REFERENCES `menu_group` (`menu_group_id`);

--
-- Constraints for table `password_history`
--
ALTER TABLE `password_history`
  ADD CONSTRAINT `password_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `password_history_ibfk_2` FOREIGN KEY (`email`) REFERENCES `users` (`email`);

--
-- Constraints for table `ui_customization_setting`
--
ALTER TABLE `ui_customization_setting`
  ADD CONSTRAINT `ui_customization_setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
