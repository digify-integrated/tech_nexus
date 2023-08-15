-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2023 at 11:24 AM
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteAllMenuItemRoleAccess` (IN `p_menu_item_id` INT)   BEGIN
	DELETE FROM menu_item_access_right
    WHERE menu_item_id = p_menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteAllSystemActionRoleAccess` (IN `p_system_action_id` INT)   BEGIN
	DELETE FROM system_action_access_rights
    WHERE system_action_id = p_system_action_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedFileExtension` (IN `p_file_type_id` INT)   BEGIN
	DELETE FROM file_extension
    WHERE file_type_id = p_file_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedMenuItem` (IN `p_menu_group_id` INT)   BEGIN
    DELETE FROM menu_item WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedPasswordHistory` (IN `p_user_id` INT)   BEGIN
    DELETE FROM password_history
    WHERE user_id = p_user_id;
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
    DECLARE p_value VARCHAR(1000);
    
    SELECT interface_setting_name, interface_setting_description, value
    INTO p_interface_setting_name, p_interface_setting_description, p_value
    FROM interface_setting 
    WHERE interface_setting_id = p_interface_setting_id;
    
    INSERT INTO interface_setting (interface_setting_name, interface_setting_description, value, last_log_by) 
    VALUES(p_interface_setting_name, p_interface_setting_description, p_value, p_last_log_by);
    
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
(1, 'menu_group', 1, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 1', '1', '2023-08-01 09:19:26'),
(2, 'role', 1, 'Role created. <br/><br/>Role Name: Super Admin<br/>Role Description: This role has the highest level of access and full control over the entire system. Super Admins can perform all actions, including managing other user accounts, configuring system settings, and access', '1', '2023-08-01 09:28:19'),
(3, 'role', 2, 'Role created. <br/><br/>Role Name: Administrator<br/>Role Description: Full access to all features and data within the system. This role have similar access levels to the Admin but is not as powerful as the Super Admin.<br/>Assignable: 1', '1', '2023-08-01 09:28:19'),
(4, 'role', 3, 'Role created. <br/><br/>Role Name: Manager<br/>Role Description: Access to manage specific aspects of the system or resources related to their teams or departments.<br/>Assignable: 1', '1', '2023-08-01 09:28:19'),
(5, 'role', 4, 'Role created. <br/><br/>Role Name: Employee<br/>Role Description: The typical user account with standard access to use the system features and functionalities.<br/>Assignable: 1', '1', '2023-08-01 09:28:19'),
(6, 'menu_item', 1, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 50', '1', '2023-08-01 09:28:46'),
(7, 'menu_item', 2, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 1<br/>URL: menu-group.php<br/>Parent ID: 1<br/>Order Sequence: 1', '1', '2023-08-01 09:28:46'),
(8, 'menu_item', 3, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 1<br/>URL: menu-item.php<br/>Parent ID: 1<br/>Order Sequence: 2', '1', '2023-08-01 09:28:46'),
(9, 'menu_item', 4, 'Menu item created. <br/><br/>Menu Item Name: Administration<br/>Menu Group ID: 1<br/>Menu Item Icon: shield<br/>Order Sequence: 1', '1', '2023-08-01 09:28:46'),
(10, 'menu_item', 5, 'Menu item created. <br/><br/>Menu Item Name: System Action<br/>Menu Group ID: 1<br/>URL: system-action.php<br/>Parent ID: 4<br/>Order Sequence: 15', '1', '2023-08-01 09:28:46'),
(11, 'menu_item', 6, 'Menu item created. <br/><br/>Menu Item Name: Role Configuration<br/>Menu Group ID: 1<br/>URL: role-configuration.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-08-01 09:28:46'),
(12, 'menu_item_access_right', 1, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '1', '2023-08-01 09:29:00'),
(13, 'menu_item_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-08-01 09:29:00'),
(14, 'menu_item_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-08-01 09:29:00'),
(15, 'menu_item_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '1', '2023-08-01 09:29:00'),
(16, 'menu_item_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-08-01 09:29:00'),
(17, 'menu_item_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-08-01 09:29:00'),
(18, 'system_action', 1, 'System action created. <br/><br/>System Action Name: Update Menu Item Role Access', '1', '2023-08-01 09:30:38'),
(19, 'system_action', 2, 'System action created. <br/><br/>System Action Name: Delete Menu Item Role Access', '1', '2023-08-01 09:30:38'),
(20, 'system_action', 3, 'System action created. <br/><br/>System Action Name: Update System Action Role Access', '1', '2023-08-01 09:30:38'),
(21, 'system_action', 4, 'System action created. <br/><br/>System Action Name: Delete System Action Role Access', '1', '2023-08-01 09:30:38'),
(22, 'system_action', 5, 'System action created. <br/><br/>System Action Name: Assign User Account To Role', '1', '2023-08-01 09:30:38'),
(23, 'system_action', 6, 'System action created. <br/><br/>System Action Name: Delete User Account To Role', '1', '2023-08-01 09:30:38'),
(24, 'menu_item_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:57:16'),
(25, 'menu_item_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:57:16'),
(26, 'menu_item_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:57:16'),
(27, 'menu_item_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:57:16'),
(28, 'menu_item_access_right', 1, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:57:16'),
(29, 'menu_item_access_right', 4, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:18'),
(30, 'menu_item_access_right', 2, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:19'),
(31, 'menu_item_access_right', 2, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:20'),
(32, 'menu_item_access_right', 2, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:20'),
(33, 'menu_item_access_right', 2, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:21'),
(34, 'menu_item_access_right', 2, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:21'),
(35, 'menu_item_access_right', 3, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:22'),
(36, 'menu_item_access_right', 3, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:23'),
(37, 'menu_item_access_right', 3, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:23'),
(38, 'menu_item_access_right', 3, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:24'),
(39, 'menu_item_access_right', 3, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:25'),
(40, 'menu_item_access_right', 5, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:26'),
(41, 'menu_item_access_right', 5, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:27'),
(42, 'menu_item_access_right', 5, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:28'),
(43, 'menu_item_access_right', 5, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:28'),
(44, 'menu_item_access_right', 5, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:29'),
(45, 'menu_item_access_right', 1, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 10:57:30'),
(46, 'system_action_access_rights', 5, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:57:51'),
(47, 'system_action_access_rights', 2, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:59:12'),
(48, 'system_action_access_rights', 4, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:59:12'),
(49, 'system_action_access_rights', 6, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:59:12'),
(50, 'system_action_access_rights', 1, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:59:12'),
(51, 'system_action_access_rights', 3, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:59:12'),
(52, 'system_action_access_rights', 5, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 10:59:15'),
(53, 'system_action_access_rights', 2, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 10:59:16'),
(54, 'system_action_access_rights', 4, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 10:59:17'),
(55, 'system_action_access_rights', 6, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 10:59:17'),
(56, 'system_action_access_rights', 6, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 10:59:17'),
(57, 'system_action_access_rights', 6, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 10:59:17'),
(58, 'system_action_access_rights', 1, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 10:59:18'),
(59, 'system_action_access_rights', 3, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 10:59:18'),
(60, 'users', 2, 'User created. <br/><br/>File As: Employee<br/>Email: employee@encorefinancials.com<br/>Is Active: 1<br/>Password Expiry Date: 2023-12-30<br/>2-Factor Authentication: 1', '1', '2023-08-01 13:46:28'),
(61, 'system_action_access_rights', 2, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-01 14:48:16'),
(62, 'system_action_access_rights', 2, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-01 14:48:16'),
(63, 'menu_item', 7, 'Menu item created. <br/><br/>Menu Item Name: Role<br/>Menu Group ID: 1<br/>URL: role.php<br/>Parent ID: 4<br/>Order Sequence: 9', '1', '2023-08-01 14:59:09'),
(64, 'menu_item_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 14:59:19'),
(65, 'menu_item_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 4', '1', '2023-08-01 14:59:19'),
(66, 'menu_item_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-01 14:59:22'),
(67, 'menu_item_access_right', 7, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:35'),
(68, 'menu_item_access_right', 7, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:36'),
(69, 'menu_item_access_right', 7, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:37'),
(70, 'menu_item_access_right', 7, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:37'),
(71, 'menu_item_access_right', 7, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:38'),
(72, 'menu_item_access_right', 7, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:38'),
(73, 'menu_item_access_right', 7, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:39'),
(74, 'menu_item_access_right', 7, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:39'),
(75, 'menu_item_access_right', 7, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:40'),
(76, 'menu_item_access_right', 7, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-01 14:59:40'),
(77, 'menu_item', 8, 'Menu item created. <br/><br/>Menu Item Name: User Account<br/>Menu Group ID: 1<br/>URL: user-account.php<br/>Parent ID: 4<br/>Order Sequence: 8', '1', '2023-08-01 15:00:06'),
(78, 'menu_item_access_right', 8, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-01 15:00:16'),
(79, 'menu_item_access_right', 8, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-01 15:00:16'),
(80, 'menu_item_access_right', 8, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:29'),
(81, 'menu_item_access_right', 8, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:29'),
(82, 'menu_item_access_right', 8, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:30'),
(83, 'menu_item_access_right', 8, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:30'),
(84, 'menu_item_access_right', 8, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:31'),
(85, 'menu_item_access_right', 8, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:31'),
(86, 'menu_item_access_right', 8, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:32'),
(87, 'menu_item_access_right', 8, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:32'),
(88, 'menu_item_access_right', 8, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:33'),
(89, 'menu_item_access_right', 8, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-01 15:00:33'),
(90, 'menu_item_access_right', 8, 'Role ID: 2<br/>Write Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:48'),
(91, 'menu_item_access_right', 8, 'Role ID: 1<br/>Write Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:48'),
(92, 'menu_item_access_right', 8, 'Role ID: 2<br/>Create Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:49'),
(93, 'menu_item_access_right', 8, 'Role ID: 2<br/>Delete Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:50'),
(94, 'menu_item_access_right', 8, 'Role ID: 1<br/>Delete Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:50'),
(95, 'menu_item_access_right', 8, 'Role ID: 1<br/>Create Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:51'),
(96, 'menu_item_access_right', 8, 'Role ID: 2<br/>Duplicate Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:51'),
(97, 'menu_item_access_right', 8, 'Role ID: 1<br/>Duplicate Access: 1 -> 0<br/>', '1', '2023-08-01 15:36:52'),
(98, 'menu_item', 8, 'Menu Item Name: User Account -> Configurations<br/>URL: user-account.php -> <br/>Parent ID: 4 -> 0<br/>Menu Item Icon:  -> settings<br/>Order Sequence: 8 -> 2<br/>', '1', '2023-08-01 15:36:58'),
(99, 'menu_item', 7, 'Menu Item Name: Role -> User Account<br/>URL: role.php -> user-account.php<br/>Order Sequence: 9 -> 1<br/>', '1', '2023-08-01 15:42:21'),
(100, 'menu_item', 7, 'Menu Item Name: User Account -> Role<br/>URL: user-account.php -> role.php<br/>Order Sequence: 1 -> 9<br/>', '1', '2023-08-01 15:49:27'),
(101, 'menu_item', 8, 'Menu Item Name: Configurations -> User Account<br/>URL:  -> user-account.php<br/>Parent ID: 0 -> 4<br/>Menu Item Icon: settings -> <br/>Order Sequence: 2 -> 1<br/>', '1', '2023-08-01 15:50:07'),
(102, 'menu_item_access_right', 7, 'Role ID: 1<br/>Write Access: 1 -> 0<br/>', '1', '2023-08-01 16:38:37'),
(103, 'menu_item_access_right', 7, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-01 16:38:38'),
(104, 'menu_item_access_right', 7, 'Role ID: 1<br/>Create Access: 1 -> 0<br/>', '1', '2023-08-01 16:38:39'),
(105, 'menu_item_access_right', 7, 'Role ID: 1<br/>Delete Access: 1 -> 0<br/>', '1', '2023-08-01 16:38:40'),
(106, 'menu_item_access_right', 7, 'Role ID: 1<br/>Duplicate Access: 1 -> 0<br/>', '1', '2023-08-01 16:38:41'),
(107, 'menu_item_access_right', 7, 'Role ID: 2<br/>Create Access: 1 -> 0<br/>', '1', '2023-08-01 16:38:51'),
(108, 'menu_item_access_right', 7, 'Role ID: 2<br/>Delete Access: 1 -> 0<br/>', '1', '2023-08-01 16:38:52'),
(109, 'menu_item_access_right', 7, 'Role ID: 2<br/>Duplicate Access: 1 -> 0<br/>', '1', '2023-08-01 16:38:54'),
(110, 'users', 1, 'Last Connection Date: 2023-08-01 08:54:35 -> 2023-08-02 08:36:30<br/>', '1', '2023-08-02 08:36:30'),
(111, 'role', 3, 'Assignable: 1 -> 0<br/>', '1', '2023-08-02 10:40:22'),
(112, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-08-02 15:36:53'),
(113, 'users', 1, 'Last Connection Date: 2023-08-02 08:36:30 -> 2023-08-04 09:28:30<br/>', '1', '2023-08-04 09:28:30'),
(114, 'users', 1, 'Last Connection Date: 2023-08-04 09:28:30 -> 2023-08-04 19:18:16<br/>', '1', '2023-08-04 19:18:16'),
(115, 'users', 1, 'Remember Token: 49ecad48f2f15e3ba7ba7579a041b590 -> c710dbf6d124ac37c70debbe48eb09f2<br/>', '1', '2023-08-04 19:18:16'),
(116, 'users', 3, 'User created. <br/><br/>File As: nexus<br/>Email: nexus@encorefinancials.com', '1', '2023-08-07 15:06:32'),
(117, 'users', 4, 'User created. <br/><br/>File As: nexus2<br/>Email: nexus@encorefinancials.com', '1', '2023-08-07 15:30:04'),
(118, 'users', 5, 'User created. <br/><br/>File As: nexus<br/>Email: nexus@encorefinancials.com', '1', '2023-08-07 16:27:06'),
(119, 'users', 6, 'User created. <br/><br/>File As: nexus<br/>Email: nexus@encorefinancials.com', '1', '2023-08-07 16:31:49'),
(120, 'users', 7, 'User created. <br/><br/>File As: nexus<br/>Email: nexus@encorefinancials.com', '1', '2023-08-07 16:32:16'),
(121, 'users', 8, 'User created. <br/><br/>File As: text<br/>Email: nexus2@encorefinancials.com', '1', '2023-08-07 17:00:40'),
(122, 'users', 9, 'User created. <br/><br/>File As: test<br/>Email: test2@encorefinancials.com<br/>Password Expiry Date: 2024-02-07', '1', '2023-08-07 17:05:29'),
(123, 'users', 10, 'User created. <br/><br/>File As: test3<br/>Email: test3@encorefinancials.com<br/>Password Expiry Date: 2024-02-07', '1', '2023-08-07 17:08:19'),
(124, 'users', 11, 'User created. <br/><br/>File As: lmicayas<br/>Email: lmicayas@encorefinancials.com<br/>Password Expiry Date: 2023-02-07', '1', '2023-08-07 17:11:13'),
(125, 'users', 1, 'Last Connection Date: 2023-08-04 19:18:16 -> 2023-08-08 08:44:43<br/>', '1', '2023-08-08 08:44:43'),
(126, 'users', 1, 'Remember Token: c710dbf6d124ac37c70debbe48eb09f2 -> 9b4194ac29018de6c72f2c7ea296eac3<br/>', '1', '2023-08-08 08:44:43'),
(127, 'users', 10, 'File As: test3 -> test34<br/>', '1', '2023-08-08 11:13:26'),
(128, 'users', 10, 'Email: test3@encorefinancials.com -> test34@encorefinancials.com<br/>', '1', '2023-08-08 11:13:34'),
(129, 'users', 10, 'Account Lock Duration: 0 -> 9999999<br/>', '1', '2023-08-08 14:01:31'),
(130, 'users', 1, 'Last Connection Date: 2023-08-08 08:44:43 -> 2023-08-08 14:46:56<br/>', '1', '2023-08-08 14:46:56'),
(131, 'system_action', 7, 'System action created. <br/><br/>System Action Name: Assign Role To User Account', '1', '2023-08-08 15:21:56'),
(132, 'system_action_access_rights', 7, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-08 15:22:02'),
(133, 'system_action_access_rights', 7, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-08 15:22:03'),
(134, 'system_action_access_rights', 7, 'System action access rights created. <br/><br/>Role ID: 4', '1', '2023-08-08 15:22:10'),
(135, 'system_action_access_rights', 7, 'System action access rights created. <br/><br/>Role ID: 4', '1', '2023-08-08 15:22:10'),
(136, 'system_action_access_rights', 7, 'Role ID: 4<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-08 15:22:10'),
(137, 'system_action_access_rights', 7, 'Role ID: 4<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-08 15:22:10'),
(138, 'system_action_access_rights', 7, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-08 15:22:21'),
(139, 'system_action_access_rights', 7, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-08 15:22:21'),
(140, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-08-08 15:35:19'),
(141, 'system_action', 8, 'System action created. <br/><br/>System Action Name: Delete Role To User Account', '1', '2023-08-08 15:52:08'),
(142, 'system_action_access_rights', 8, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-08 16:04:12'),
(143, 'system_action_access_rights', 8, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-08 16:04:12'),
(144, 'system_action_access_rights', 8, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-08 16:04:13'),
(145, 'system_action_access_rights', 8, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-08 16:04:13'),
(146, 'users', 1, 'Last Connection Date: 2023-08-08 14:46:56 -> 2023-08-09 09:31:58<br/>', '1', '2023-08-09 09:31:58'),
(147, 'system_action', 9, 'System action created. <br/><br/>System Action Name: Activate User Account', '1', '2023-08-09 09:54:03'),
(148, 'system_action_access_rights', 9, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 09:54:09'),
(149, 'system_action_access_rights', 9, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 09:54:09'),
(150, 'system_action_access_rights', 9, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 09:54:11'),
(151, 'system_action_access_rights', 9, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:11'),
(152, 'system_action_access_rights', 9, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:11'),
(153, 'system_action_access_rights', 9, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 09:54:11'),
(154, 'system_action_access_rights', 9, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:11'),
(155, 'system_action_access_rights', 9, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:11'),
(156, 'system_action', 10, 'System action created. <br/><br/>System Action Name: Deactivate User Account', '1', '2023-08-09 09:54:19'),
(157, 'system_action_access_rights', 10, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 09:54:23'),
(158, 'system_action_access_rights', 10, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 09:54:23'),
(159, 'system_action_access_rights', 10, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 09:54:24'),
(160, 'system_action_access_rights', 10, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:24'),
(161, 'system_action_access_rights', 10, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:24'),
(162, 'system_action_access_rights', 10, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 09:54:25'),
(163, 'system_action_access_rights', 10, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:25'),
(164, 'system_action_access_rights', 10, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 09:54:25'),
(165, 'users', 11, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 11:41:19'),
(166, 'users', 3, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 11:41:19'),
(167, 'users', 11, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 11:51:32'),
(168, 'users', 3, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 11:51:35'),
(169, 'system_action', 11, 'System action created. <br/><br/>System Action Name: Lock User Account', '1', '2023-08-09 11:51:55'),
(170, 'system_action_access_rights', 11, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 11:51:59'),
(171, 'system_action_access_rights', 11, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 11:51:59'),
(172, 'system_action_access_rights', 11, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 11:52:00'),
(173, 'system_action_access_rights', 11, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:00'),
(174, 'system_action_access_rights', 11, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:00'),
(175, 'system_action_access_rights', 11, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 11:52:01'),
(176, 'system_action_access_rights', 11, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:01'),
(177, 'system_action_access_rights', 11, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:01'),
(178, 'system_action', 12, 'System action created. <br/><br/>System Action Name: Unlock User Account', '1', '2023-08-09 11:52:16'),
(179, 'system_action_access_rights', 12, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 11:52:21'),
(180, 'system_action_access_rights', 12, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 11:52:21'),
(181, 'system_action_access_rights', 12, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-09 11:52:23'),
(182, 'system_action_access_rights', 12, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:23'),
(183, 'system_action_access_rights', 12, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:23'),
(184, 'system_action_access_rights', 12, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-09 11:52:23'),
(185, 'system_action_access_rights', 12, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:23'),
(186, 'system_action_access_rights', 12, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-09 11:52:23'),
(187, 'users', 11, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 11:54:48'),
(188, 'users', 3, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 11:54:48'),
(189, 'users', 11, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 11:55:28'),
(190, 'users', 3, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 11:55:28'),
(191, 'users', 11, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 12:38:34'),
(192, 'users', 3, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 12:38:34'),
(193, 'users', 11, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 12:38:40'),
(194, 'users', 3, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 12:38:40'),
(195, 'users', 2, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 13:25:29'),
(196, 'users', 2, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 13:28:02'),
(197, 'users', 11, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 13:28:02'),
(198, 'users', 11, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 2147483647<br/>', '1', '2023-08-09 14:08:14'),
(199, 'users', 3, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 2147483647<br/>', '1', '2023-08-09 14:26:33'),
(200, 'users', 5, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 2147483647<br/>', '1', '2023-08-09 14:26:33'),
(201, 'users', 11, 'Is Locked: 1 -> 0<br/>Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-08-09 14:26:41'),
(202, 'users', 3, 'Is Locked: 1 -> 0<br/>Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-08-09 14:26:41'),
(203, 'users', 5, 'Is Locked: 1 -> 0<br/>Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-08-09 14:26:41'),
(204, 'users', 3, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 14:30:33'),
(205, 'users', 5, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 14:30:33'),
(206, 'users', 11, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 14:30:42'),
(207, 'users', 3, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 14:30:42'),
(208, 'users', 5, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 14:30:42'),
(209, 'users', 11, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 14:39:39'),
(210, 'users', 3, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 14:39:39'),
(211, 'users', 5, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 14:39:39'),
(212, 'users', 2, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 14:58:35'),
(213, 'users', 2, 'Is Active: 0 -> 1<br/>', '1', '2023-08-09 14:58:39'),
(214, 'users', 2, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 2147483647<br/>', '1', '2023-08-09 14:58:44'),
(215, 'users', 2, 'Is Locked: 1 -> 0<br/>Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-08-09 14:58:51'),
(216, 'users', 2, 'Is Active: 1 -> 0<br/>', '1', '2023-08-09 16:23:35'),
(217, 'menu_item_access_right', 4, 'Role ID: 2<br/>Read Access: 1 -> 0<br/>', '1', '2023-08-09 17:00:52'),
(218, 'menu_item_access_right', 4, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-09 17:00:53'),
(219, 'system_action', 13, 'System action created. <br/><br/>System Action Name: Change User Account Password', '1', '2023-08-10 09:42:02'),
(220, 'system_action_access_rights', 13, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-10 09:42:08'),
(221, 'system_action_access_rights', 13, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-10 09:42:08'),
(222, 'system_action_access_rights', 13, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-10 09:42:09'),
(223, 'system_action_access_rights', 13, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 09:42:09'),
(224, 'system_action_access_rights', 13, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 09:42:09'),
(225, 'system_action_access_rights', 13, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-10 09:42:10'),
(226, 'system_action_access_rights', 13, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 09:42:10'),
(227, 'system_action_access_rights', 13, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 09:42:10'),
(228, 'users', 1, 'Last Connection Date: 2023-08-09 09:31:58 -> 2023-08-10 11:12:12<br/>', '1', '2023-08-10 11:12:12'),
(229, 'users', 1, 'Password Expiry Date: 2023-12-30 -> 2024-02-10<br/>', '1', '2023-08-10 11:12:24'),
(230, 'users', 2, 'Password Expiry Date: 2023-12-30 -> 2024-02-10<br/>', '1', '2023-08-10 11:12:24'),
(231, 'users', 3, 'Password Expiry Date: 0000-00-00 -> 2024-02-10<br/>', '1', '2023-08-10 11:12:24'),
(232, 'users', 5, 'Password Expiry Date: 0000-00-00 -> 2024-02-10<br/>', '1', '2023-08-10 11:12:24'),
(233, 'users', 11, 'Password Expiry Date: 2023-02-07 -> 2024-02-10<br/>', '1', '2023-08-10 11:12:24'),
(234, 'users', 1, 'Last Password Change: 2023-08-10 11:12:24 -> 2023-08-10 11:13:10<br/>', '1', '2023-08-10 11:13:10'),
(235, 'users', 2, 'Last Password Change: 2023-08-10 11:12:24 -> 2023-08-10 11:13:10<br/>', '1', '2023-08-10 11:13:10'),
(236, 'users', 3, 'Last Password Change: 2023-08-10 11:12:24 -> 2023-08-10 11:13:10<br/>', '1', '2023-08-10 11:13:10'),
(237, 'users', 5, 'Last Password Change: 2023-08-10 11:12:24 -> 2023-08-10 11:13:10<br/>', '1', '2023-08-10 11:13:10'),
(238, 'users', 11, 'Last Password Change: 2023-08-10 11:12:24 -> 2023-08-10 11:13:10<br/>', '1', '2023-08-10 11:13:10'),
(239, 'users', 1, 'Last Password Change: 2023-08-10 11:13:10 -> 2023-08-10 11:13:31<br/>', '1', '2023-08-10 11:13:31'),
(240, 'users', 2, 'Last Password Change: 2023-08-10 11:13:10 -> 2023-08-10 11:13:31<br/>', '1', '2023-08-10 11:13:31'),
(241, 'users', 3, 'Last Password Change: 2023-08-10 11:13:10 -> 2023-08-10 11:13:31<br/>', '1', '2023-08-10 11:13:31'),
(242, 'users', 5, 'Last Password Change: 2023-08-10 11:13:10 -> 2023-08-10 11:13:31<br/>', '1', '2023-08-10 11:13:31'),
(243, 'users', 11, 'Last Password Change: 2023-08-10 11:13:10 -> 2023-08-10 11:13:31<br/>', '1', '2023-08-10 11:13:31'),
(244, 'users', 1, 'Last Password Change: 2023-08-10 11:13:31 -> 2023-08-10 11:14:51<br/>', '1', '2023-08-10 11:14:51'),
(245, 'users', 2, 'Last Password Change: 2023-08-10 11:13:31 -> 2023-08-10 11:14:51<br/>', '1', '2023-08-10 11:14:51'),
(246, 'users', 3, 'Last Password Change: 2023-08-10 11:13:31 -> 2023-08-10 11:14:51<br/>', '1', '2023-08-10 11:14:51'),
(247, 'users', 5, 'Last Password Change: 2023-08-10 11:13:31 -> 2023-08-10 11:14:51<br/>', '1', '2023-08-10 11:14:51'),
(248, 'users', 11, 'Last Password Change: 2023-08-10 11:13:31 -> 2023-08-10 11:14:51<br/>', '1', '2023-08-10 11:14:51'),
(249, 'system_action', 14, 'System action created. <br/><br/>System Action Name: Change User Account Profile Picture', '1', '2023-08-10 11:19:16'),
(250, 'system_action_access_rights', 14, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-10 11:19:20'),
(251, 'system_action_access_rights', 14, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-10 11:19:20'),
(252, 'system_action_access_rights', 14, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-10 11:19:21'),
(253, 'system_action_access_rights', 14, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 11:19:21'),
(254, 'system_action_access_rights', 14, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 11:19:21'),
(255, 'system_action_access_rights', 14, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-10 11:19:21'),
(256, 'system_action_access_rights', 14, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 11:19:21'),
(257, 'system_action_access_rights', 14, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-10 11:19:21'),
(258, 'menu_item', 9, 'Menu item created. <br/><br/>Menu Item Name: Configurations<br/>Menu Group ID: 1<br/>Menu Item Icon: settings<br/>Order Sequence: 30', '1', '2023-08-10 11:26:43'),
(259, 'menu_item_access_right', 9, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-10 11:26:52'),
(260, 'menu_item_access_right', 9, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-10 11:26:52'),
(261, 'menu_item_access_right', 9, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:26:53'),
(262, 'menu_item_access_right', 9, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:26:54'),
(263, 'menu_item_access_right', 9, 'Role ID: 2<br/>Read Access: 1 -> 0<br/>', '1', '2023-08-10 11:27:28'),
(264, 'menu_item_access_right', 9, 'Role ID: 1<br/>Read Access: 1 -> 0<br/>', '1', '2023-08-10 11:27:29'),
(265, 'menu_item_access_right', 9, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:27:32'),
(266, 'menu_item_access_right', 9, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:27:33'),
(267, 'menu_item', 10, 'Menu item created. <br/><br/>Menu Item Name: File Type<br/>Menu Group ID: 1<br/>URL: file-type.php<br/>Parent ID: 9<br/>Order Sequence: 30', '1', '2023-08-10 11:28:02'),
(268, 'menu_item_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-10 11:28:07'),
(269, 'menu_item_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-10 11:28:07'),
(270, 'menu_item_access_right', 10, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:08'),
(271, 'menu_item_access_right', 10, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:10'),
(272, 'menu_item_access_right', 10, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:11'),
(273, 'menu_item_access_right', 10, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:11'),
(274, 'menu_item_access_right', 10, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:12'),
(275, 'menu_item_access_right', 10, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:12'),
(276, 'menu_item_access_right', 10, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:12'),
(277, 'menu_item_access_right', 10, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:13'),
(278, 'menu_item_access_right', 10, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:14'),
(279, 'menu_item_access_right', 10, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:14'),
(280, 'menu_item', 11, 'Menu item created. <br/><br/>Menu Item Name: File Extension<br/>Menu Group ID: 1<br/>URL: file-extension.php<br/>Parent ID: 9<br/>Order Sequence: 31', '1', '2023-08-10 11:28:49'),
(281, 'menu_item_access_right', 11, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-10 11:28:55'),
(282, 'menu_item_access_right', 11, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-10 11:28:55'),
(283, 'menu_item_access_right', 11, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:56'),
(284, 'menu_item_access_right', 11, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:57'),
(285, 'menu_item_access_right', 11, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:57'),
(286, 'menu_item_access_right', 11, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:58'),
(287, 'menu_item_access_right', 11, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:58'),
(288, 'menu_item_access_right', 11, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-10 11:28:59'),
(289, 'menu_item_access_right', 11, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-10 11:29:03'),
(290, 'menu_item_access_right', 11, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-10 11:29:03'),
(291, 'menu_item_access_right', 11, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-10 11:29:04'),
(292, 'menu_item_access_right', 11, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-10 11:29:04'),
(293, 'file_type', 1, 'System action created. <br/><br/>File Type Name: test', '1', '2023-08-10 14:18:28'),
(294, 'file_type', 1, 'File Type Name: test -> test2<br/>', '1', '2023-08-10 14:18:34'),
(295, 'file_type', 2, 'System action created. <br/><br/>File Type Name: test', '1', '2023-08-10 14:18:43'),
(296, 'file_type', 3, 'System action created. <br/><br/>File Type Name: test', '1', '2023-08-10 14:18:46'),
(297, 'file_type', 4, 'System action created. <br/><br/>File Type Name: test', '1', '2023-08-10 14:18:50'),
(298, 'file_extension', 1, 'File extension created. <br/><br/>File Exension Name: test<br/>File Type ID: 2', '1', '2023-08-10 16:35:57'),
(299, 'file_extension', 2, 'File extension created. <br/><br/>File Exension Name: asd<br/>File Type ID: 2', '1', '2023-08-10 16:36:59'),
(300, 'file_extension', 2, 'File Exension Name: asd -> asd2<br/>', '1', '2023-08-10 16:38:23'),
(301, 'file_extension', 3, 'File extension created. <br/><br/>File Exension Name: test<br/>File Type ID: 2', '1', '2023-08-10 16:40:29'),
(302, 'file_extension', 4, 'File extension created. <br/><br/>File Exension Name: test<br/>File Type ID: 2', '1', '2023-08-10 16:40:33'),
(303, 'file_type', 2, 'File Type Name: test -> test2<br/>', '1', '2023-08-10 16:40:49'),
(304, 'file_type', 5, 'System action created. <br/><br/>File Type Name: test', '1', '2023-08-10 17:19:15'),
(305, 'file_extension', 5, 'File extension created. <br/><br/>File Type ID: 5', '1', '2023-08-10 17:25:43'),
(306, 'file_extension', 6, 'File extension created. <br/><br/>File Exension Name: test<br/>File Type ID: 5', '1', '2023-08-10 17:26:18'),
(307, 'file_extension', 6, 'File Exension Name: test -> test2<br/>', '1', '2023-08-10 17:28:59'),
(308, 'file_extension', 6, 'File Exension Name: test2 -> test23<br/>', '1', '2023-08-10 17:29:35'),
(309, 'file_extension', 7, 'File extension created. <br/><br/>File Exension Name: test23<br/>File Type ID: 5', '1', '2023-08-10 17:29:38'),
(310, 'file_extension', 8, 'File extension created. <br/><br/>File Exension Name: test23<br/>File Type ID: 5', '1', '2023-08-10 17:29:41'),
(311, 'menu_item', 12, 'Menu item created. <br/><br/>Menu Item Name: Upload Settings<br/>Menu Group ID: 1<br/>URL: upload-settings.php<br/>Parent ID: 9<br/>Order Sequence: 29', '1', '2023-08-11 09:30:00'),
(312, 'menu_item_access_right', 12, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-11 09:30:06'),
(313, 'menu_item_access_right', 12, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-11 09:30:06'),
(314, 'menu_item_access_right', 12, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:08'),
(315, 'menu_item_access_right', 12, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:08'),
(316, 'menu_item_access_right', 12, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:09'),
(317, 'menu_item_access_right', 12, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:10'),
(318, 'menu_item_access_right', 12, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:10'),
(319, 'menu_item_access_right', 12, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:11'),
(320, 'menu_item_access_right', 12, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:11'),
(321, 'menu_item_access_right', 12, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:12'),
(322, 'menu_item_access_right', 12, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:12'),
(323, 'menu_item_access_right', 12, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-11 09:30:13'),
(324, 'menu_item', 12, 'Menu Item Name: Upload Settings -> Upload Setting<br/>URL: upload-settings.php -> upload-setting.php<br/>', '1', '2023-08-11 12:00:12'),
(325, 'upload_setting', 1, 'Uploan setting created. <br/><br/>Upload Setting Name: asd<br/>Upload Setting Description: asd<br/>Max File Size: 1', '1', '2023-08-11 15:37:28'),
(326, 'upload_setting', 1, 'Upload Setting Name: asd -> asd2<br/>Upload Setting Description: asd -> asd2<br/>Max File Size: 1 -> 3<br/>', '1', '2023-08-11 15:43:13'),
(327, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-08-12 17:45:42'),
(328, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:45:42 -> 2023-08-12 17:45:48<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-08-12 17:45:48'),
(329, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:45:48 -> 2023-08-12 17:45:49<br/>Failed Login Attempts: 2 -> 3<br/>', '1', '2023-08-12 17:45:49'),
(330, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:45:49 -> 2023-08-12 17:45:51<br/>Failed Login Attempts: 3 -> 4<br/>', '1', '2023-08-12 17:45:51'),
(331, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:45:51 -> 2023-08-12 17:45:53<br/>Failed Login Attempts: 4 -> 5<br/>', '1', '2023-08-12 17:45:53'),
(332, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:45:53 -> 2023-08-12 17:46:12<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-08-12 17:46:12'),
(333, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-08-12 17:46:12'),
(334, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:46:12 -> 2023-08-12 17:46:58<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-08-12 17:46:58'),
(335, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-08-12 17:46:58'),
(336, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:46:58 -> 2023-08-12 17:47:00<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-08-12 17:47:00'),
(337, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-08-12 17:47:00'),
(338, 'users', 1, 'Failed Login Attempts: 8 -> 9<br/>', '1', '2023-08-12 17:47:00'),
(339, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-08-12 17:47:00'),
(340, 'users', 1, 'Failed Login Attempts: 9 -> 10<br/>', '1', '2023-08-12 17:47:00'),
(341, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-08-12 17:47:00'),
(342, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:47:00 -> 2023-08-12 17:47:01<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-08-12 17:47:01'),
(343, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-08-12 17:47:01'),
(344, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:47:01 -> 2023-08-12 17:47:02<br/>Failed Login Attempts: 11 -> 12<br/>', '1', '2023-08-12 17:47:02'),
(345, 'users', 1, 'Account Lock Duration: 320 -> 640<br/>', '1', '2023-08-12 17:47:02'),
(346, 'users', 1, 'Last Failed Login Attempt: 2023-08-12 17:47:02 -> 2023-08-12 17:47:03<br/>Failed Login Attempts: 12 -> 13<br/>', '1', '2023-08-12 17:47:03'),
(347, 'users', 1, 'Account Lock Duration: 640 -> 1280<br/>', '1', '2023-08-12 17:47:03'),
(348, 'users', 1, 'Is Locked: 1 -> 0<br/>', '1', '2023-08-12 17:47:45'),
(349, 'users', 1, 'Failed Login Attempts: 13 -> 0<br/>', '1', '2023-08-12 17:47:51'),
(350, 'users', 1, 'Account Lock Duration: 1280 -> 0<br/>', '1', '2023-08-12 17:47:59'),
(351, 'users', 1, 'Last Connection Date: 2023-08-10 11:12:12 -> 2023-08-12 17:48:03<br/>', '1', '2023-08-12 17:48:03'),
(352, 'system_action', 15, 'System action created. <br/><br/>System Action Name: Assign File Extension To Upload Setting', '1', '2023-08-12 17:55:07'),
(353, 'system_action_access_rights', 15, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-12 17:55:13'),
(354, 'system_action_access_rights', 15, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-12 17:55:13'),
(355, 'system_action_access_rights', 15, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-12 17:55:14'),
(356, 'system_action_access_rights', 15, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:14'),
(357, 'system_action_access_rights', 15, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:14'),
(358, 'system_action_access_rights', 15, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-12 17:55:14'),
(359, 'system_action_access_rights', 15, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:14'),
(360, 'system_action_access_rights', 15, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:14'),
(361, 'system_action', 16, 'System action created. <br/><br/>System Action Name: Assign File Extension To Upload Setting', '1', '2023-08-12 17:55:26'),
(362, 'system_action', 16, 'System Action Name: Assign File Extension To Upload Setting -> Delete File Extension To Upload Setting<br/>', '1', '2023-08-12 17:55:33'),
(363, 'system_action_access_rights', 16, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-12 17:55:37'),
(364, 'system_action_access_rights', 16, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-12 17:55:37'),
(365, 'system_action_access_rights', 16, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-12 17:55:38'),
(366, 'system_action_access_rights', 16, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:38'),
(367, 'system_action_access_rights', 16, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:38'),
(368, 'system_action_access_rights', 16, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-12 17:55:39'),
(369, 'system_action_access_rights', 16, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:39'),
(370, 'system_action_access_rights', 16, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-12 17:55:39'),
(371, 'file_extension', 6, 'File Exension Name: test23 -> png<br/>', '1', '2023-08-14 11:18:30'),
(372, 'file_extension', 9, 'File extension created. <br/><br/>File Exension Name: jpg<br/>File Type ID: 5', '1', '2023-08-14 11:18:39'),
(373, 'file_extension', 10, 'File extension created. <br/><br/>File Exension Name: jpeg<br/>File Type ID: 5', '1', '2023-08-14 11:18:45'),
(374, 'file_type', 5, 'File Type Name: test -> Image<br/>', '1', '2023-08-14 11:18:54'),
(375, 'upload_setting', 1, 'Upload Setting Name: asd2 -> Profile Image<br/>Upload Setting Description: asd2 -> Profile Image<br/>', '1', '2023-08-14 11:19:47'),
(376, 'system_action', 17, 'System action created. <br/><br/>System Action Name: Send Reset Password Instructrions', '1', '2023-08-14 14:34:53'),
(377, 'system_action_access_rights', 17, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-14 14:34:58'),
(378, 'system_action_access_rights', 17, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-14 14:34:58'),
(379, 'system_action_access_rights', 17, 'System action access rights created. <br/><br/>Role ID: 2', '1', '2023-08-14 14:34:59'),
(380, 'system_action_access_rights', 17, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-14 14:34:59'),
(381, 'system_action_access_rights', 17, 'Role ID: 2<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-14 14:34:59'),
(382, 'system_action_access_rights', 17, 'System action access rights created. <br/><br/>Role ID: 1', '1', '2023-08-14 14:34:59'),
(383, 'system_action_access_rights', 17, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-14 14:34:59'),
(384, 'system_action_access_rights', 17, 'Role ID: 1<br/>Role Access: 0 -> 1<br/>', '1', '2023-08-14 14:34:59'),
(385, 'system_action', 17, 'System Action Name: Send Reset Password Instructrions -> Send Reset Password Instructions<br/>', '1', '2023-08-14 14:38:01'),
(386, 'menu_item', 13, 'Menu item created. <br/><br/>Menu Item Name: Interface Setting<br/>Menu Group ID: 1<br/>URL: interface-setting.php<br/>Parent ID: 1<br/>Order Sequence: 3', '1', '2023-08-14 17:16:50'),
(387, 'menu_item_access_right', 13, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-14 17:16:55'),
(388, 'menu_item_access_right', 13, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-14 17:16:55'),
(389, 'menu_item_access_right', 13, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:56'),
(390, 'menu_item_access_right', 13, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:56'),
(391, 'menu_item_access_right', 13, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:57'),
(392, 'menu_item_access_right', 13, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:57'),
(393, 'menu_item_access_right', 13, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:58'),
(394, 'menu_item_access_right', 13, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:58'),
(395, 'menu_item_access_right', 13, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:59'),
(396, 'menu_item_access_right', 13, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-14 17:16:59'),
(397, 'menu_item_access_right', 13, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-14 17:17:00'),
(398, 'menu_item_access_right', 13, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-14 17:17:01'),
(399, 'menu_item', 14, 'Menu item created. <br/><br/>Menu Item Name: System Setting<br/>Menu Group ID: 1<br/>URL: system-setting.php<br/>Parent ID: 4<br/>Order Sequence: 16', '1', '2023-08-14 17:18:05'),
(400, 'menu_item_access_right', 14, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-08-14 17:18:12'),
(401, 'menu_item_access_right', 14, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-08-14 17:18:12'),
(402, 'menu_item_access_right', 14, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:14'),
(403, 'menu_item_access_right', 14, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:14'),
(404, 'menu_item_access_right', 14, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:15'),
(405, 'menu_item_access_right', 14, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:15'),
(406, 'menu_item_access_right', 14, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:15'),
(407, 'menu_item_access_right', 14, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:16'),
(408, 'menu_item_access_right', 14, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:17'),
(409, 'menu_item_access_right', 14, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:17'),
(410, 'menu_item_access_right', 14, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:19'),
(411, 'menu_item_access_right', 14, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-08-14 17:18:20'),
(412, 'upload_setting', 2, 'Upload setting created. <br/><br/>Upload Setting Name: Interface Setting<br/>Upload Setting Description: Interface Setting<br/>Max File Size: 5', '1', '2023-08-15 10:12:00'),
(413, 'system_setting', 1, 'System setting created. <br/><br/>System Setting Name: test<br/>System Setting Description: test<br/>Value: test', '1', '2023-08-15 13:21:49'),
(414, 'system_setting', 1, 'System Setting Name: test -> test2<br/>System Setting Description: test -> test2<br/>Value: test -> test2<br/>', '1', '2023-08-15 13:29:04'),
(415, 'system_setting', 2, 'System setting created. <br/><br/>System Setting Name: test2<br/>System Setting Description: test2<br/>Value: test2', '1', '2023-08-15 13:29:11');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(416, 'system_setting', 3, 'System setting created. <br/><br/>System Setting Name: test2<br/>System Setting Description: test2<br/>Value: test2', '1', '2023-08-15 13:29:17'),
(417, 'system_setting', 4, 'System setting created. <br/><br/>System Setting Name: test2<br/>System Setting Description: test2<br/>Value: test2', '1', '2023-08-15 13:29:55'),
(418, 'interface_setting', 1, 'Interface setting created. <br/><br/>Interface Setting Name: test<br/>Interface Setting Description: test', '1', '2023-08-15 14:18:50'),
(419, 'system_setting', 1, 'System Setting Name: test2 -> test23<br/>System Setting Description: test2 -> test23<br/>Value: test2 -> test23<br/>', '1', '2023-08-15 16:07:33'),
(420, 'system_setting', 5, 'System setting created. <br/><br/>System Setting Name: test23<br/>System Setting Description: test23<br/>Value: test23', '1', '2023-08-15 16:07:36'),
(421, 'system_setting', 6, 'System setting created. <br/><br/>System Setting Name: test23<br/>System Setting Description: test23<br/>Value: test23', '1', '2023-08-15 16:07:43'),
(422, 'system_setting', 7, 'System setting created. <br/><br/>System Setting Name: test23<br/>System Setting Description: test23<br/>Value: test23', '1', '2023-08-15 16:07:45');

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
(6, 'png', 5, 1),
(9, 'jpg', 5, 1),
(10, 'jpeg', 5, 1);

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
(5, 'Image', 1);

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
(1, 'test', 'test', NULL, 1);

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

    IF NEW.value <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Value: ", NEW.value);
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

    IF NEW.value <> OLD.value THEN
        SET audit_log = CONCAT(audit_log, "Value: ", OLD.value, " -> ", NEW.value, "<br/>");
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
(1, 'Technical', 1, 1);

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
(1, 'User Interface', 1, '', 0, 'sidebar', 50, 1),
(2, 'Menu Group', 1, 'menu-group.php', 1, '', 1, 1),
(3, 'Menu Item', 1, 'menu-item.php', 1, '', 2, 1),
(4, 'Administration', 1, '', 0, 'shield', 1, 1),
(5, 'System Action', 1, 'system-action.php', 4, '', 15, 1),
(6, 'Role Configuration', 1, 'role-configuration.php', 4, '', 10, 1),
(7, 'Role', 1, 'role.php', 4, '', 9, 1),
(8, 'User Account', 1, 'user-account.php', 4, '', 1, 1),
(9, 'Configurations', 1, '', 0, 'settings', 30, 1),
(10, 'File Type', 1, 'file-type.php', 9, '', 30, 1),
(11, 'File Extension', 1, 'file-extension.php', 9, '', 31, 1),
(12, 'Upload Setting', 1, 'upload-setting.php', 9, '', 29, 1),
(13, 'Interface Setting', 1, 'interface-setting.php', 1, '', 3, 1),
(14, 'System Setting', 1, 'system-setting.php', 4, '', 16, 1);

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
(1, 1, 1, 0, 0, 0, 0, 1),
(2, 1, 1, 1, 1, 1, 1, 1),
(3, 1, 1, 1, 1, 1, 1, 1),
(4, 1, 1, 0, 0, 0, 0, 1),
(5, 1, 1, 1, 1, 1, 1, 1),
(6, 1, 1, 1, 1, 1, 1, 1),
(4, 2, 1, 0, 0, 0, 0, 1),
(1, 2, 1, 0, 0, 0, 0, 1),
(7, 2, 1, 1, 0, 0, 0, 1),
(7, 1, 1, 1, 0, 0, 0, 1),
(8, 2, 1, 0, 0, 0, 0, 1),
(8, 1, 1, 0, 0, 0, 0, 1),
(9, 2, 1, 0, 0, 0, 0, 1),
(9, 1, 1, 0, 0, 0, 0, 1),
(10, 2, 1, 1, 1, 1, 1, 1),
(10, 1, 1, 1, 1, 1, 1, 1),
(11, 2, 1, 1, 1, 1, 1, 1),
(11, 1, 1, 1, 1, 1, 1, 1),
(12, 2, 1, 1, 1, 1, 1, 1),
(12, 1, 1, 1, 1, 1, 1, 1),
(13, 2, 1, 1, 1, 1, 1, 1),
(13, 1, 1, 1, 1, 1, 1, 1),
(14, 2, 1, 1, 1, 1, 1, 1),
(14, 1, 1, 1, 1, 1, 1, 1);

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
(1, 'Super Admin', 'This role has the highest level of access and full control over the entire system. Super Admins can perform all actions, including managing other user accounts, configuring system settings, and access', 0, 1),
(2, 'Administrator', 'Full access to all features and data within the system. This role have similar access levels to the Admin but is not as powerful as the Super Admin.', 1, 1),
(3, 'Manager', 'Access to manage specific aspects of the system or resources related to their teams or departments.', 0, 1),
(4, 'Employee', 'The typical user account with standard access to use the system features and functionalities.', 1, 1);

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
(1, 1, 1),
(2, 1, 1);

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
(1, 'Update Menu Item Role Access', 1),
(2, 'Delete Menu Item Role Access', 1),
(3, 'Update System Action Role Access', 1),
(4, 'Delete System Action Role Access', 1),
(5, 'Assign User Account To Role', 1),
(6, 'Delete User Account To Role', 1),
(7, 'Assign Role To User Account', 1),
(8, 'Delete Role To User Account', 1),
(9, 'Activate User Account', 1),
(10, 'Deactivate User Account', 1),
(11, 'Lock User Account', 1),
(12, 'Unlock User Account', 1),
(13, 'Change User Account Password', 1),
(14, 'Change User Account Profile Picture', 1),
(15, 'Assign File Extension To Upload Setting', 1),
(16, 'Delete File Extension To Upload Setting', 1),
(17, 'Send Reset Password Instructions', 1);

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
(1, 1, 1, NULL),
(3, 1, 1, NULL),
(4, 1, 1, NULL),
(5, 1, 1, NULL),
(6, 1, 1, NULL),
(5, 2, 1, 1),
(2, 2, 1, 1),
(4, 2, 1, 1),
(6, 2, 1, 1),
(1, 2, 1, 1),
(3, 2, 1, 1),
(6, 2, 1, 1),
(2, 1, 1, 1),
(7, 2, 1, 1),
(7, 1, 1, 1),
(8, 2, 1, 1),
(8, 1, 1, 1),
(9, 2, 1, 1),
(9, 1, 1, 1),
(9, 2, 1, 1),
(9, 1, 1, 1),
(10, 2, 1, 1),
(10, 1, 1, 1),
(10, 2, 1, 1),
(10, 1, 1, 1),
(11, 2, 1, 1),
(11, 1, 1, 1),
(11, 2, 1, 1),
(11, 1, 1, 1),
(12, 2, 1, 1),
(12, 1, 1, 1),
(12, 2, 1, 1),
(12, 1, 1, 1),
(13, 2, 1, 1),
(13, 1, 1, 1),
(13, 2, 1, 1),
(13, 1, 1, 1),
(14, 2, 1, 1),
(14, 1, 1, 1),
(14, 2, 1, 1),
(14, 1, 1, 1),
(15, 2, 1, 1),
(15, 1, 1, 1),
(15, 2, 1, 1),
(15, 1, 1, 1),
(16, 2, 1, 1),
(16, 1, 1, 1),
(16, 2, 1, 1),
(16, 1, 1, 1),
(17, 2, 1, 1),
(17, 1, 1, 1),
(17, 2, 1, 1),
(17, 1, 1, 1);

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
(1, 'test23', 'test23', 'test23', 1);

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
(1, 1, 'false', 'true', 'preset-1', 'light', 'false', 'false', 1);

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
(1, 'Profile Image', 'Profile Image', 3, 1),
(2, 'Interface Setting', 'Interface Setting', 5, 1);

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
(1, 10, 1),
(1, 9, 1),
(1, 6, 1),
(2, 10, 1),
(2, 9, 1),
(2, 6, 1);

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
(1, 'Administrator', 'ldagulto@encorefinancials.com', 'RYHObc8sNwIxdPDNJwCsO8bXKZJXYx7RjTgEWMC17FY%3D', './assets/images/user/profile_picture/G38V.png', 0, 1, NULL, 0, '2023-08-12 17:48:03', '2024-02-10', 'APcMMnEZ9sUa8wpUaOXsyFHJb6h7aK3ipqJ90r4GRaI%3D', '2023-08-14 15:54:48', 0, 0, 'ZLryvTiuBbP20aocMKrt5sFyV%2FU1buhYN9soR3XUZ3w%3D', '2023-07-19 08:57:46', 0, '2023-08-10 11:14:51', 0, NULL, 0, 'b60334dfed2c0359183db37ab9a59b52', 1);

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
  MODIFY `audit_log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;

--
-- AUTO_INCREMENT for table `file_extension`
--
ALTER TABLE `file_extension`
  MODIFY `file_extension_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `file_type`
--
ALTER TABLE `file_type`
  MODIFY `file_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `interface_setting`
--
ALTER TABLE `interface_setting`
  MODIFY `interface_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_group`
--
ALTER TABLE `menu_group`
  MODIFY `menu_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `password_history`
--
ALTER TABLE `password_history`
  MODIFY `password_history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `system_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
