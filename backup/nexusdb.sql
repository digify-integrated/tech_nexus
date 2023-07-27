-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2023 at 11:35 AM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `buildMenuGroup` (IN `p_user_id` INT)   BEGIN
    SELECT DISTINCT(mg.menu_group_id) as menu_group_id, mg.menu_group_name
    FROM menu_group mg
    JOIN menu_item mi ON mi.menu_group_id = mg.menu_group_id
    WHERE EXISTS (
        SELECT 1
        FROM menu_access_right mar
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
    INNER JOIN menu_access_right AS mar ON mi.menu_item_id = mar.menu_item_id
    INNER JOIN role_users AS ru ON mar.role_id = ru.role_id
    WHERE mar.read_access = 1 AND ru.user_id = p_user_id AND mi.menu_group_id = p_menu_group_id
    ORDER BY mi.order_sequence;
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
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where read_access = 1 AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'write' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where write_access = 1 AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'create' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where create_access = 1 AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'delete' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where delete_access = 1 AND menu_item_id = menu_item_id);
    ELSE
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where duplicate_access = 1 AND menu_item_id = menu_item_id);
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
    FROM menu_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkRoleSystemActionAccessExist` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
	SELECT COUNT(*) AS total 
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkUICustomizationSettingExist` (IN `p_user_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM ui_customization_setting
	WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteLinkedMenuItem` (IN `p_menu_group_id` INT)   BEGIN
    DELETE FROM menu_item WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuGroup` (IN `p_menu_group_id` INT)   BEGIN
	DELETE FROM menu_group
    WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuItem` (IN `p_menu_item_id` INT)   BEGIN
	DELETE FROM menu_item
    WHERE menu_item_id = p_menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRole` (IN `p_role_id` INT)   BEGIN
	DELETE FROM role
    WHERE role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRoleMenuAccess` (IN `p_menu_item_id` INT, IN `p_role_id` INT)   BEGIN
	DELETE FROM menu_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRoleSystemActionAccess` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
	DELETE FROM system_action_access_rights
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteSystemAction` (IN `p_system_action_id` INT)   BEGIN
	DELETE FROM system_action
    WHERE system_action_id = p_system_action_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddMenuItemRoleTable` (IN `p_menu_item_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM menu_access_right WHERE menu_item_id = p_menu_item_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddRoleMenuItemTable` (IN `p_role_id` INT)   BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    WHERE menu_item_id NOT IN (SELECT menu_item_id FROM menu_access_right WHERE role_id = p_role_id)
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddRoleSystemActionTable` (IN `p_role_id` INT)   BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id NOT IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAddSystemActionRoleTable` (IN `p_system_action_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM system_action_access_rights WHERE system_action_id = p_system_action_id)
    ORDER BY role_name;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuItemRoleTable` (IN `p_menu_item_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM menu_access_right WHERE menu_item_id = p_menu_item_id)
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
    WHERE menu_item_id IN (SELECT role_id FROM menu_access_right WHERE role_id = p_role_id)
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRoleSystemActionTable` (IN `p_role_id` INT)   BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateShortcutMenuItemRoleTable` ()   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM menu_access_right)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSubMenuItemTable` (IN `p_parent_id` INT)   BEGIN
	SELECT menu_item_name, menu_group_id, order_sequence 
    FROM menu_item
    WHERE parent_id = p_parent_id
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSystemActionRoleTable` (IN `p_system_action_id` INT)   BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM system_action_access_rights WHERE system_action_id = p_system_action_id)
    ORDER BY role_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSystemActionTable` ()   BEGIN
	SELECT system_action_id, system_action_name 
    FROM system_action
    ORDER BY system_action_id;
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
    FROM menu_access_right 
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUICustomizationSetting` (IN `p_user_id` INT)   BEGIN
	SELECT * FROM ui_customization_setting
	WHERE user_id = p_user_id;
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
    INSERT INTO menu_access_right (menu_item_id, role_id, last_log_by) 
	VALUES(p_menu_item_id, p_role_id, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertRoleSystemActionAccess` (IN `p_system_action_id` INT, IN `p_role_id` INT, IN `p_last_log_by` INT)   BEGIN
    INSERT INTO system_action_access_rights (system_action_id, role_id, last_log_by) 
	VALUES(p_system_action_id, p_role_id, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertSystemAction` (IN `p_system_action_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_system_action_id` INT)   BEGIN
    INSERT INTO system_action (system_action_name, last_log_by) 
	VALUES(p_system_action_name, p_last_log_by);
	
    SET p_system_action_id = LAST_INSERT_ID();
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
        UPDATE menu_access_right
        SET read_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'write' THEN
        UPDATE menu_access_right
        SET write_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'create' THEN
        UPDATE menu_access_right
        SET create_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'delete' THEN
      UPDATE menu_access_right
        SET delete_access = p_access,
        last_log_by = p_last_log_by
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSE
        UPDATE menu_access_right
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUserPassword` (IN `p_user_id` INT, IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_password_expiry_date` DATE, IN `p_last_password_change` DATETIME)   BEGIN
	UPDATE users 
    SET password = p_password, password_expiry_date = p_password_expiry_date, last_password_change = p_last_password_change, is_locked = 0, failed_login_attempts = 0, account_lock_duration = 0
    WHERE p_user_id = p_user_id OR email = BINARY p_email;
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
(1, 'menu_group', 1, 'Order Sequence: 1 -> 2<br/>', '1', '2023-07-01 12:18:02'),
(2, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-07-01 12:51:13'),
(3, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-07-01 12:51:15'),
(4, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-07-01 12:54:45'),
(5, 'users', 1, 'Failed Login Attempts: 1 -> 0<br/>', '1', '2023-07-01 12:54:48'),
(6, 'users', 1, 'Last Connection Date: 2023-07-01 11:37:18 -> 2023-07-01 12:54:48<br/>', '1', '2023-07-01 12:54:48'),
(7, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-07-01 12:54:53'),
(8, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-07-01 12:54:54'),
(9, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-07-01 12:54:54'),
(10, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-07-01 12:54:58'),
(11, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-07-01 12:54:59'),
(12, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-07-01 12:55:00'),
(13, 'menu_group', 14, 'Menu Group Name: Administration -> Administrations<br/>', '1', '2023-07-01 12:56:58'),
(14, 'menu_group', 14, 'Menu Group Name: Administrations -> Administration<br/>Order Sequence: 1 -> 2<br/>', '1', '2023-07-01 12:57:06'),
(15, 'menu_group', 14, 'Menu Group Name: Administration -> Administrations<br/>Order Sequence: 2 -> 1<br/>', '1', '2023-07-01 12:58:05'),
(16, 'menu_group', 14, 'Menu Group Name: Administrations -> Administration<br/>', '1', '2023-07-01 12:58:17'),
(17, 'menu_group', 1, 'Order Sequence: 2 -> 1<br/>', '1', '2023-07-01 12:59:00'),
(18, 'users', 1, 'Last Connection Date: 2023-07-01 12:54:48 -> 2023-07-01 18:03:21<br/>', '1', '2023-07-01 18:03:21'),
(19, 'users', 1, 'Remember Token: 2c300c9bb4332919325314dc9de9351c -> f232915da9163c3b163cf317ba03906a<br/>', '1', '2023-07-01 18:03:21'),
(20, 'menu_item', 2, 'URL: menu-group.php -> menu-group.php<br/>', '1', '2023-07-01 19:37:49'),
(21, 'menu_item', 2, 'URL: menu-group.php -> menu-group.php<br/>', '1', '2023-07-01 19:37:57'),
(22, 'menu_item', 3, 'URL: menu-item.php -> menu-item.php<br/>', '1', '2023-07-01 19:39:15'),
(23, 'menu_item', 3, 'URL: menu-item.php -> menu-item.php<br/>', '1', '2023-07-01 19:39:39'),
(24, 'menu_item', 3, 'Menu Item Name: Menu Item -> Roles<br/>URL: menu-item.php -> <br/>Parent ID: 1 -> 0<br/>Order Sequence: 2 -> 3<br/>', '1', '2023-07-01 19:53:07'),
(25, 'menu_item', 3, 'Parent ID: 0 -> 1<br/>', '1', '2023-07-01 19:57:01'),
(26, 'menu_item', 3, 'Menu Item Name: Roles -> Menu Item<br/>URL:  -> menu-item.php<br/>', '1', '2023-07-01 19:57:28'),
(27, 'menu_item', 4, 'Menu item created. <br/><br/>Menu Item Name: Role<br/>Menu Group ID: 1<br/>Parent ID: 1<br/>Order Sequence: 5', '1', '2023-07-01 20:02:43'),
(28, 'menu_item', 4, 'URL:  -> <br/>Order Sequence: 5 -> 6<br/>', '1', '2023-07-01 20:02:48'),
(29, 'menu_item', 5, 'Menu item created. <br/><br/>Menu Item Name: roles<br/>Menu Group ID: 1<br/>Parent ID: 2<br/>Order Sequence: 6', '1', '2023-07-01 20:13:35'),
(30, 'menu_item', 5, 'URL:  -> <br/>', '1', '2023-07-01 20:13:43'),
(31, 'menu_item', 6, 'Menu item created. <br/><br/>Menu Item Name: Test<br/>Menu Group ID: 14<br/>Order Sequence: 1', '1', '2023-07-02 08:20:40'),
(32, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-02 12:53:42'),
(33, 'menu_access_right', 6, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-02 12:53:42'),
(34, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 12:53:45'),
(35, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 12:54:55'),
(36, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 12:55:04'),
(37, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:19'),
(38, 'menu_access_right', 6, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-02 13:00:19'),
(39, 'menu_access_right', 6, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-02 13:00:19'),
(40, 'menu_access_right', 6, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-02 13:00:19'),
(41, 'menu_access_right', 6, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-07-02 13:00:19'),
(42, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-02 13:00:19'),
(43, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:19'),
(44, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:19'),
(45, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:19'),
(46, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:19'),
(47, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:19'),
(48, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:27'),
(49, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:27'),
(50, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:27'),
(51, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:27'),
(52, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:27'),
(53, 'menu_access_right', 6, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-02 13:00:27'),
(54, 'menu_access_right', 6, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-02 13:00:27'),
(55, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:27'),
(56, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:27'),
(57, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:27'),
(58, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:32'),
(59, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:32'),
(60, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:32'),
(61, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:32'),
(62, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-02 13:00:32'),
(63, 'menu_access_right', 6, 'Role ID: 2<br/>Read Access: 1 -> 0<br/>', '1', '2023-07-02 13:00:32'),
(64, 'menu_access_right', 6, 'Role ID: 2<br/>Write Access: 1 -> 0<br/>', '1', '2023-07-02 13:00:32'),
(65, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:32'),
(66, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:32'),
(67, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-02 13:00:32'),
(68, 'menu_group', 15, 'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1', '1', '2023-07-02 13:01:59'),
(69, 'users', 1, 'Last Connection Date: 2023-07-01 18:03:21 -> 2023-07-04 09:02:55<br/>', '1', '2023-07-04 09:02:55'),
(70, 'menu_access_right', 1, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-04 09:54:39'),
(71, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-04 09:54:39'),
(72, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-04 09:54:39'),
(73, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-04 09:54:39'),
(74, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-04 09:54:39'),
(75, 'menu_access_right', 1, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-04 09:54:39'),
(76, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-04 09:54:39'),
(77, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-04 09:54:39'),
(78, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-04 09:54:39'),
(79, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-04 09:54:39'),
(80, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-04 09:54:39'),
(81, 'ui_customization_setting', 1, 'Preset Theme: preset-10 -> preset-1<br/>', '1', '2023-07-04 14:00:22'),
(82, 'menu_item', 7, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 2', '1', '2023-07-04 16:31:37'),
(83, 'menu_item', 8, 'Menu item created. <br/><br/>Menu Item Name: test2<br/>Menu Group ID: 1<br/>Order Sequence: 2', '1', '2023-07-04 16:34:18'),
(84, 'users', 1, 'Last Connection Date: 2023-07-04 09:02:55 -> 2023-07-05 11:38:03<br/>', '1', '2023-07-05 11:38:03'),
(85, 'users', 1, 'Remember Token: f232915da9163c3b163cf317ba03906a -> 3bc368b66a3959677f2c3e05aa8f3817<br/>', '1', '2023-07-05 11:38:03'),
(86, 'menu_item', 3, 'URL: menu-item.php -> menu-items.php<br/>', '1', '2023-07-05 11:57:24'),
(87, 'menu_item', 3, 'URL: menu-items.php -> menu-item.php<br/>', '1', '2023-07-05 11:57:30'),
(88, 'menu_item', 3, 'URL: menu-item.php -> menu-item.php<br/>', '1', '2023-07-05 11:57:37'),
(89, 'menu_item', 3, 'URL: menu-item.php -> menu-item.php<br/>', '1', '2023-07-05 11:58:36'),
(90, 'menu_item', 9, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 2', '1', '2023-07-05 12:31:55'),
(91, 'menu_item', 10, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 2', '1', '2023-07-05 12:32:03'),
(92, 'menu_item', 11, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>URL: test.php<br/>Order Sequence: 2', '1', '2023-07-05 13:15:56'),
(93, 'menu_item', 11, 'Parent ID: 0 -> 1<br/>', '1', '2023-07-05 13:16:06'),
(94, 'menu_access_right', 11, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-05 13:33:41'),
(95, 'menu_access_right', 11, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(96, 'menu_access_right', 11, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(97, 'menu_access_right', 11, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(98, 'menu_access_right', 11, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(99, 'menu_access_right', 11, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(100, 'menu_access_right', 11, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-05 13:33:41'),
(101, 'menu_access_right', 11, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(102, 'menu_access_right', 11, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(103, 'menu_access_right', 11, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(104, 'menu_access_right', 11, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(105, 'menu_access_right', 11, 'Role ID: 2<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-07-05 13:33:41'),
(106, 'menu_access_right', 11, 'Role ID: 1<br/>', '1', '2023-07-05 13:33:49'),
(107, 'menu_access_right', 11, 'Role ID: 1<br/>', '1', '2023-07-05 13:33:49'),
(108, 'menu_access_right', 11, 'Role ID: 1<br/>', '1', '2023-07-05 13:33:49'),
(109, 'menu_access_right', 11, 'Role ID: 1<br/>', '1', '2023-07-05 13:33:49'),
(110, 'menu_access_right', 11, 'Role ID: 1<br/>', '1', '2023-07-05 13:33:49'),
(111, 'menu_access_right', 11, 'Role ID: 2<br/>Read Access: 1 -> 0<br/>', '1', '2023-07-05 13:33:49'),
(112, 'menu_access_right', 11, 'Role ID: 2<br/>Write Access: 1 -> 0<br/>', '1', '2023-07-05 13:33:49'),
(113, 'menu_access_right', 11, 'Role ID: 2<br/>Create Access: 1 -> 0<br/>', '1', '2023-07-05 13:33:49'),
(114, 'menu_access_right', 11, 'Role ID: 2<br/>Delete Access: 1 -> 0<br/>', '1', '2023-07-05 13:33:49'),
(115, 'menu_access_right', 11, 'Role ID: 2<br/>Duplicate Access: 1 -> 0<br/>', '1', '2023-07-05 13:33:49'),
(116, 'users', 1, 'Last Connection Date: 2023-07-05 11:38:03 -> 2023-07-07 09:16:36<br/>', '1', '2023-07-07 09:16:36'),
(117, 'menu_item', 3, 'Order Sequence: 3 -> 2<br/>', '1', '2023-07-07 11:24:04'),
(118, 'menu_item', 7, 'Order Sequence: 2 -> 1<br/>', '1', '2023-07-07 11:24:18'),
(119, 'menu_item', 11, 'Order Sequence: 2 -> 99<br/>', '1', '2023-07-07 11:24:44'),
(120, 'menu_item', 11, 'Parent ID: 1 -> 3<br/>', '1', '2023-07-07 11:24:53'),
(121, 'menu_item', 11, 'Parent ID: 3 -> 1<br/>', '1', '2023-07-07 11:25:03'),
(122, 'menu_item', 1, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 50', '1', '2023-07-07 11:33:44'),
(123, 'menu_item', 2, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 1<br/>URL: menu-group.php<br/>Parent ID: 1<br/>Order Sequence: 1', '1', '2023-07-07 11:33:44'),
(124, 'menu_item', 3, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 1<br/>URL: menu-item.php<br/>Parent ID: 1<br/>Order Sequence: 2', '1', '2023-07-07 11:33:44'),
(125, 'users', 1, 'Last Connection Date: 2023-07-07 09:16:36 -> 2023-07-07 17:07:15<br/>', '1', '2023-07-07 17:07:15'),
(126, 'users', 1, 'Remember Token: 3bc368b66a3959677f2c3e05aa8f3817 -> 8dbc4dc2764fce151cd28f97a48a7aff<br/>', '1', '2023-07-07 17:07:15'),
(127, 'menu_group', 16, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 1', '1', '2023-07-07 18:08:18'),
(128, 'menu_group', 17, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 1', '1', '2023-07-07 18:09:13'),
(129, 'menu_group', 1, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 1', '1', '2023-07-07 18:10:48'),
(130, 'menu_item', 1, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 50', '1', '2023-07-07 18:10:48'),
(131, 'menu_item', 2, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 1<br/>URL: menu-group.php<br/>Parent ID: 1<br/>Order Sequence: 1', '1', '2023-07-07 18:10:48'),
(132, 'menu_item', 3, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 1<br/>URL: menu-item.php<br/>Parent ID: 1<br/>Order Sequence: 2', '1', '2023-07-07 18:10:48'),
(133, 'menu_item', 4, 'Menu item created. <br/><br/>Menu Item Name: Administration<br/>Menu Group ID: 1<br/>Menu Item Icon: shield<br/>Order Sequence: 1', '1', '2023-07-07 18:24:17'),
(134, 'menu_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-07 18:24:27'),
(135, 'menu_access_right', 4, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-07 18:24:27'),
(136, 'menu_access_right', 4, 'Role ID: 1<br/>', '1', '2023-07-07 18:24:27'),
(137, 'menu_access_right', 4, 'Role ID: 1<br/>', '1', '2023-07-07 18:24:27'),
(138, 'menu_access_right', 4, 'Role ID: 1<br/>', '1', '2023-07-07 18:24:27'),
(139, 'menu_access_right', 4, 'Role ID: 1<br/>', '1', '2023-07-07 18:24:27'),
(140, 'menu_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-07 18:24:27'),
(141, 'menu_access_right', 4, 'Role ID: 2<br/>', '1', '2023-07-07 18:24:27'),
(142, 'menu_access_right', 4, 'Role ID: 2<br/>', '1', '2023-07-07 18:24:27'),
(143, 'menu_access_right', 4, 'Role ID: 2<br/>', '1', '2023-07-07 18:24:27'),
(144, 'menu_access_right', 4, 'Role ID: 2<br/>', '1', '2023-07-07 18:24:27'),
(145, 'menu_access_right', 4, 'Role ID: 2<br/>', '1', '2023-07-07 18:24:27'),
(146, 'menu_item', 5, 'Menu item created. <br/><br/>Menu Item Name: System Action<br/>Menu Group ID: 1<br/>URL: system-action.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-07-07 18:25:16'),
(147, 'menu_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-07 18:25:27'),
(148, 'menu_access_right', 5, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-07 18:25:27'),
(149, 'menu_access_right', 5, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-07 18:25:27'),
(150, 'menu_access_right', 5, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-07 18:25:27'),
(151, 'menu_access_right', 5, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-07 18:25:27'),
(152, 'menu_access_right', 5, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-07-07 18:25:27'),
(153, 'menu_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-07 18:25:27'),
(154, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-07 18:25:27'),
(155, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-07 18:25:27'),
(156, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-07 18:25:27'),
(157, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-07 18:25:27'),
(158, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-07 18:25:27'),
(159, 'system_action', 2, 'System action created. <br/><br/>System Action Name: Assign System Action Role Access', '1', '2023-07-08 10:00:19'),
(160, 'system_action', 3, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-09 10:57:07'),
(161, 'system_action', 4, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-09 11:13:38'),
(162, 'users', 1, 'Last Connection Date: 2023-07-07 17:07:15 -> 2023-07-10 15:07:07<br/>', '1', '2023-07-10 15:07:07'),
(163, 'system_action', 5, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-10 15:07:16'),
(164, 'menu_item', 6, 'Menu item created. <br/><br/>Menu Item Name: Role<br/>Menu Group ID: 1<br/>URL: role.php<br/>Parent ID: 4<br/>Order Sequence: 2', '1', '2023-07-10 15:29:44'),
(165, 'system_action', 6, 'System action created. <br/><br/>System Action Name: Assign System Action Role Access', '1', '2023-07-10 15:32:27'),
(166, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-07-10 15:47:28'),
(167, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-07-10 15:47:29'),
(168, 'users', 1, 'Last Connection Date: 2023-07-10 15:07:07 -> 2023-07-12 09:48:29<br/>', '1', '2023-07-12 09:48:29'),
(169, 'users', 1, 'Remember Token: 8dbc4dc2764fce151cd28f97a48a7aff -> 458284e66026ca9f6d8b96a5d4d58207<br/>', '1', '2023-07-12 09:48:29'),
(170, 'menu_item', 6, 'Menu Item Name: Role -> Role Configuration<br/>', '1', '2023-07-12 16:59:34'),
(171, 'menu_item', 6, 'URL: role.php -> role-configuration.php<br/>', '1', '2023-07-12 16:59:42'),
(172, 'role', 3, 'Role created. <br/><br/>Role Name: test<br/>Role Description: test<br/>Assignable: 1', '1', '2023-07-13 16:40:35'),
(173, 'role', 4, 'Role created. <br/><br/>Role Name: test<br/>Role Description: test<br/>Assignable: 1', '1', '2023-07-13 16:41:05'),
(174, 'role', 4, 'Role Name: test -> test2<br/>Role Description: test -> test2<br/>Assignable: 1 -> 0<br/>', '1', '2023-07-13 16:42:00'),
(175, 'role', 5, 'Role created. <br/><br/>Role Name: test<br/>Role Description: test<br/>Assignable: 1', '1', '2023-07-13 16:48:42'),
(176, 'role', 6, 'Role created. <br/><br/>Role Name: teste<br/>Role Description: test<br/>Assignable: 1', '1', '2023-07-13 16:48:58'),
(177, 'role', 6, 'Role Name: teste -> Test ROle<br/>', '1', '2023-07-14 16:16:59'),
(178, 'system_action', 7, 'System action created. <br/><br/>System Action Name: Assign System Action Role Access', '1', '2023-07-14 17:05:12'),
(179, 'users', 1, 'Last Connection Date: 2023-07-12 09:48:29 -> 2023-07-15 13:42:18<br/>', '1', '2023-07-15 13:42:18'),
(180, 'users', 1, 'Last Connection Date: 2023-07-15 13:42:18 -> 2023-07-16 11:02:59<br/>', '1', '2023-07-16 11:02:59'),
(181, 'users', 1, 'Remember Token: 458284e66026ca9f6d8b96a5d4d58207 -> d4e1caeaf2aaccada9b28e2071d643ba<br/>', '1', '2023-07-16 11:02:59'),
(182, 'menu_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-16 12:24:47'),
(183, 'role', 7, 'Role created. <br/><br/>Role Name: test2<br/>Role Description: test<br/>Assignable: 1', '1', '2023-07-16 12:25:12'),
(184, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-16 12:27:52'),
(185, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 7', '1', '2023-07-16 12:27:52'),
(186, 'menu_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-16 12:29:25'),
(187, 'menu_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-16 12:33:50'),
(188, 'menu_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 7', '1', '2023-07-16 12:37:04'),
(189, 'menu_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-16 12:40:32'),
(190, 'menu_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-16 12:52:39'),
(191, 'menu_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 7', '1', '2023-07-16 12:57:38'),
(192, 'menu_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 7', '1', '2023-07-16 13:03:51'),
(193, 'menu_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-16 13:04:33'),
(194, 'menu_access_right', 2, 'Menu item access rights created. <br/>', '1', '2023-07-16 13:06:32'),
(195, 'menu_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 7', '1', '2023-07-16 13:08:13'),
(196, 'users', 1, 'Last Connection Date: 2023-07-16 11:02:59 -> 2023-07-17 11:41:29<br/>', '1', '2023-07-17 11:41:29'),
(197, 'users', 1, '2-Factor Authentication: 0 -> 1<br/>', '1', '2023-07-17 11:41:33'),
(198, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-07-17 11:41:43'),
(199, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:43 -> 2023-07-17 11:41:44<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-07-17 11:41:44'),
(200, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:44 -> 2023-07-17 11:41:45<br/>Failed Login Attempts: 2 -> 3<br/>', '1', '2023-07-17 11:41:45'),
(201, 'users', 1, 'Failed Login Attempts: 3 -> 4<br/>', '1', '2023-07-17 11:41:45'),
(202, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:45 -> 2023-07-17 11:41:46<br/>Failed Login Attempts: 4 -> 5<br/>', '1', '2023-07-17 11:41:46'),
(203, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:46 -> 2023-07-17 11:41:47<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-07-17 11:41:47'),
(204, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-07-17 11:41:47'),
(205, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:47 -> 2023-07-17 11:41:49<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-07-17 11:41:49'),
(206, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-07-17 11:41:49'),
(207, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:49 -> 2023-07-17 11:41:51<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-07-17 11:41:51'),
(208, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-07-17 11:41:51'),
(209, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:51 -> 2023-07-17 11:41:52<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-07-17 11:41:52'),
(210, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-07-17 11:41:52'),
(211, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:52 -> 2023-07-17 11:41:54<br/>Failed Login Attempts: 9 -> 10<br/>', '1', '2023-07-17 11:41:54'),
(212, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-07-17 11:41:54'),
(213, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:54 -> 2023-07-17 11:41:56<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-07-17 11:41:56'),
(214, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-07-17 11:41:56'),
(215, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:56 -> 2023-07-17 11:41:57<br/>Failed Login Attempts: 11 -> 12<br/>', '1', '2023-07-17 11:41:57'),
(216, 'users', 1, 'Account Lock Duration: 320 -> 640<br/>', '1', '2023-07-17 11:41:57'),
(217, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:57 -> 2023-07-17 11:41:58<br/>Failed Login Attempts: 12 -> 13<br/>', '1', '2023-07-17 11:41:58'),
(218, 'users', 1, 'Account Lock Duration: 640 -> 1280<br/>', '1', '2023-07-17 11:41:58'),
(219, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:58 -> 2023-07-17 11:41:59<br/>Failed Login Attempts: 13 -> 14<br/>', '1', '2023-07-17 11:41:59'),
(220, 'users', 1, 'Account Lock Duration: 1280 -> 2560<br/>', '1', '2023-07-17 11:41:59'),
(221, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:41:59 -> 2023-07-17 11:42:01<br/>Failed Login Attempts: 14 -> 15<br/>', '1', '2023-07-17 11:42:01'),
(222, 'users', 1, 'Account Lock Duration: 2560 -> 5120<br/>', '1', '2023-07-17 11:42:01'),
(223, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:42:01 -> 2023-07-17 11:43:07<br/>Failed Login Attempts: 15 -> 16<br/>', '1', '2023-07-17 11:43:07'),
(224, 'users', 1, 'Account Lock Duration: 5120 -> 10240<br/>', '1', '2023-07-17 11:43:07'),
(225, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:07 -> 2023-07-17 11:43:08<br/>Failed Login Attempts: 16 -> 17<br/>', '1', '2023-07-17 11:43:08'),
(226, 'users', 1, 'Account Lock Duration: 10240 -> 20480<br/>', '1', '2023-07-17 11:43:08'),
(227, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:08 -> 2023-07-17 11:43:09<br/>Failed Login Attempts: 17 -> 18<br/>', '1', '2023-07-17 11:43:09'),
(228, 'users', 1, 'Account Lock Duration: 20480 -> 40960<br/>', '1', '2023-07-17 11:43:09'),
(229, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:09 -> 2023-07-17 11:43:10<br/>Failed Login Attempts: 18 -> 19<br/>', '1', '2023-07-17 11:43:10'),
(230, 'users', 1, 'Account Lock Duration: 40960 -> 81920<br/>', '1', '2023-07-17 11:43:10'),
(231, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:10 -> 2023-07-17 11:43:11<br/>Failed Login Attempts: 19 -> 20<br/>', '1', '2023-07-17 11:43:11'),
(232, 'users', 1, 'Account Lock Duration: 81920 -> 163840<br/>', '1', '2023-07-17 11:43:11'),
(233, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:11 -> 2023-07-17 11:43:12<br/>Failed Login Attempts: 20 -> 21<br/>', '1', '2023-07-17 11:43:12'),
(234, 'users', 1, 'Account Lock Duration: 163840 -> 327680<br/>', '1', '2023-07-17 11:43:12'),
(235, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:12 -> 2023-07-17 11:43:13<br/>Failed Login Attempts: 21 -> 22<br/>', '1', '2023-07-17 11:43:13'),
(236, 'users', 1, 'Account Lock Duration: 327680 -> 655360<br/>', '1', '2023-07-17 11:43:13'),
(237, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:13 -> 2023-07-17 11:43:23<br/>Failed Login Attempts: 22 -> 23<br/>', '1', '2023-07-17 11:43:23'),
(238, 'users', 1, 'Account Lock Duration: 655360 -> 1310720<br/>', '1', '2023-07-17 11:43:23'),
(239, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:23 -> 2023-07-17 11:43:25<br/>Failed Login Attempts: 23 -> 24<br/>', '1', '2023-07-17 11:43:25'),
(240, 'users', 1, 'Account Lock Duration: 1310720 -> 2621440<br/>', '1', '2023-07-17 11:43:25'),
(241, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:25 -> 2023-07-17 11:43:31<br/>Failed Login Attempts: 24 -> 25<br/>', '1', '2023-07-17 11:43:31'),
(242, 'users', 1, 'Account Lock Duration: 2621440 -> 5242880<br/>', '1', '2023-07-17 11:43:31'),
(243, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:31 -> 2023-07-17 11:43:32<br/>Failed Login Attempts: 25 -> 26<br/>', '1', '2023-07-17 11:43:32'),
(244, 'users', 1, 'Account Lock Duration: 5242880 -> 10485760<br/>', '1', '2023-07-17 11:43:32'),
(245, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:32 -> 2023-07-17 11:43:33<br/>Failed Login Attempts: 26 -> 27<br/>', '1', '2023-07-17 11:43:33'),
(246, 'users', 1, 'Account Lock Duration: 10485760 -> 20971520<br/>', '1', '2023-07-17 11:43:33'),
(247, 'users', 1, 'Failed Login Attempts: 27 -> 28<br/>', '1', '2023-07-17 11:43:33'),
(248, 'users', 1, 'Account Lock Duration: 20971520 -> 41943040<br/>', '1', '2023-07-17 11:43:33'),
(249, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:33 -> 2023-07-17 11:43:35<br/>Failed Login Attempts: 28 -> 29<br/>', '1', '2023-07-17 11:43:35'),
(250, 'users', 1, 'Account Lock Duration: 41943040 -> 83886080<br/>', '1', '2023-07-17 11:43:35'),
(251, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:35 -> 2023-07-17 11:43:36<br/>Failed Login Attempts: 29 -> 30<br/>', '1', '2023-07-17 11:43:36'),
(252, 'users', 1, 'Account Lock Duration: 83886080 -> 167772160<br/>', '1', '2023-07-17 11:43:36'),
(253, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:36 -> 2023-07-17 11:43:42<br/>Failed Login Attempts: 30 -> 31<br/>', '1', '2023-07-17 11:43:42'),
(254, 'users', 1, 'Account Lock Duration: 167772160 -> 335544320<br/>', '1', '2023-07-17 11:43:42'),
(255, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:42 -> 2023-07-17 11:43:43<br/>Failed Login Attempts: 31 -> 32<br/>', '1', '2023-07-17 11:43:43'),
(256, 'users', 1, 'Account Lock Duration: 335544320 -> 671088640<br/>', '1', '2023-07-17 11:43:43'),
(257, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:43 -> 2023-07-17 11:43:45<br/>Failed Login Attempts: 32 -> 33<br/>', '1', '2023-07-17 11:43:45'),
(258, 'users', 1, 'Account Lock Duration: 671088640 -> 1342177280<br/>', '1', '2023-07-17 11:43:45'),
(259, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:45 -> 2023-07-17 11:43:48<br/>Failed Login Attempts: 33 -> 34<br/>', '1', '2023-07-17 11:43:48'),
(260, 'users', 1, 'Account Lock Duration: 1342177280 -> 2147483647<br/>', '1', '2023-07-17 11:43:48'),
(261, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:48 -> 2023-07-17 11:43:49<br/>Failed Login Attempts: 34 -> 35<br/>', '1', '2023-07-17 11:43:49'),
(262, 'users', 1, 'Failed Login Attempts: 35 -> 36<br/>', '1', '2023-07-17 11:43:49'),
(263, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:49 -> 2023-07-17 11:43:50<br/>Failed Login Attempts: 36 -> 37<br/>', '1', '2023-07-17 11:43:50'),
(264, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:50 -> 2023-07-17 11:43:51<br/>Failed Login Attempts: 37 -> 38<br/>', '1', '2023-07-17 11:43:51'),
(265, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:51 -> 2023-07-17 11:43:52<br/>Failed Login Attempts: 38 -> 39<br/>', '1', '2023-07-17 11:43:52'),
(266, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:52 -> 2023-07-17 11:43:53<br/>Failed Login Attempts: 39 -> 40<br/>', '1', '2023-07-17 11:43:53'),
(267, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:53 -> 2023-07-17 11:43:56<br/>Failed Login Attempts: 40 -> 41<br/>', '1', '2023-07-17 11:43:56'),
(268, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:56 -> 2023-07-17 11:43:57<br/>Failed Login Attempts: 41 -> 42<br/>', '1', '2023-07-17 11:43:57'),
(269, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:57 -> 2023-07-17 11:43:58<br/>Failed Login Attempts: 42 -> 43<br/>', '1', '2023-07-17 11:43:58'),
(270, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:58 -> 2023-07-17 11:43:59<br/>Failed Login Attempts: 43 -> 44<br/>', '1', '2023-07-17 11:43:59'),
(271, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:43:59 -> 2023-07-17 11:44:00<br/>Failed Login Attempts: 44 -> 45<br/>', '1', '2023-07-17 11:44:00'),
(272, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:44:00 -> 2023-07-17 11:44:49<br/>Failed Login Attempts: 45 -> 46<br/>', '1', '2023-07-17 11:44:49'),
(273, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:44:49 -> 2023-07-17 11:47:32<br/>Failed Login Attempts: 46 -> 47<br/>', '1', '2023-07-17 11:47:32'),
(274, 'users', 1, 'Reset Token: FoL0D0dploLRggOHQpGyHDSQB%2BNOD4az3BbtGJI86Js%3D -> HFsuSN%2BDWDS9yaUFIwOCNmg%2BXXGkROO7Nr2P2CQJYJQ%3D<br/>Reset Token Expiry Date: 2023-06-27 14:15:10 -> 2023-07-17 11:57:42<br/>', '1', '2023-07-17 11:47:42'),
(275, 'users', 1, 'Is Locked: 1 -> 0<br/>Failed Login Attempts: 47 -> 0<br/>Password Expiry Date: 2023-12-27 -> 2024-01-17<br/>Last Password Change: 2023-06-27 14:05:38 -> 2023-07-17 11:48:16<br/>Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-07-17 11:48:16'),
(276, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 11:47:32 -> 2023-07-17 11:48:35<br/>Failed Login Attempts: 0 -> 1<br/>', '1', '2023-07-17 11:48:35'),
(277, 'users', 1, 'Failed Login Attempts: 1 -> 0<br/>', '1', '2023-07-17 11:48:37'),
(278, 'users', 1, 'OTP: uoJ04qcrOcuN3ykmxi3ur%2B4wUyS0%2FMONdUXrcAs%2Bv1M%3D -> OfNZEkcyEDQAknmAhqlIac7K8rDhPD%2FCaRmCtye%2FplU%3D<br/>OTP Expiry Date: 2023-06-29 10:58:26 -> 2023-07-17 11:53:37<br/>', '1', '2023-07-17 11:48:37'),
(279, 'users', 1, 'Last Connection Date: 2023-07-17 11:41:29 -> 2023-07-17 11:48:54<br/>', '1', '2023-07-17 11:48:54'),
(280, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-07-17 11:55:14'),
(281, 'users', 1, 'Failed Login Attempts: 1 -> 0<br/>', '1', '2023-07-17 11:55:16'),
(282, 'users', 1, 'OTP: OfNZEkcyEDQAknmAhqlIac7K8rDhPD%2FCaRmCtye%2FplU%3D -> T4%2BTTNxh%2FzK9o0%2ByeMLQKOcP1OZZp2Lp%2BWmVDyP8OBg%3D<br/>OTP Expiry Date: 2023-07-17 11:53:37 -> 2023-07-17 12:00:16<br/>', '1', '2023-07-17 11:55:16'),
(283, 'users', 1, 'Last Connection Date: 2023-07-17 11:48:54 -> 2023-07-17 11:55:30<br/>', '1', '2023-07-17 11:55:30'),
(284, 'users', 1, 'Password Expiry Date: 2024-01-17 -> 2022-01-17<br/>', '1', '2023-07-17 11:55:39'),
(285, 'users', 1, 'Reset Token: HFsuSN%2BDWDS9yaUFIwOCNmg%2BXXGkROO7Nr2P2CQJYJQ%3D -> d6%2FnavnZUhhKyQA3cJ%2BdaGnrS4gMKLG7jN%2BXTTBcS3A%3D<br/>Reset Token Expiry Date: 2023-07-17 11:57:42 -> 2023-07-17 12:08:51<br/>', '1', '2023-07-17 11:58:51'),
(286, 'users', 1, 'Password Expiry Date: 2022-01-17 -> 2024-01-17<br/>Last Password Change: 2023-07-17 11:48:16 -> 2023-07-17 11:59:30<br/>', '1', '2023-07-17 11:59:30'),
(287, 'users', 1, 'OTP: T4%2BTTNxh%2FzK9o0%2ByeMLQKOcP1OZZp2Lp%2BWmVDyP8OBg%3D -> yKRrNdpCzTM3yOrwjCyogVtJkMPgxkpyW5eniUNxexM%3D<br/>OTP Expiry Date: 2023-07-17 12:00:16 -> 2023-07-17 12:04:44<br/>', '1', '2023-07-17 11:59:44'),
(288, 'users', 1, 'Last Connection Date: 2023-07-17 11:55:30 -> 2023-07-17 11:59:57<br/>', '1', '2023-07-17 11:59:57'),
(289, 'users', 1, '2-Factor Authentication: 1 -> 0<br/>', '1', '2023-07-17 12:00:00'),
(290, 'users', 1, 'Receive Notification: 1 -> 0<br/>', '1', '2023-07-17 12:00:05'),
(291, 'users', 1, 'Last Password Change: 2023-07-17 11:59:30 -> 2023-07-17 12:01:53<br/>', '1', '2023-07-17 12:01:53'),
(292, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-07-17 15:55:46'),
(293, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:55:46 -> 2023-07-17 15:55:49<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-07-17 15:55:49'),
(294, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:55:49 -> 2023-07-17 15:55:52<br/>Failed Login Attempts: 2 -> 3<br/>', '1', '2023-07-17 15:55:52'),
(295, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:55:52 -> 2023-07-17 15:55:54<br/>Failed Login Attempts: 3 -> 4<br/>', '1', '2023-07-17 15:55:54'),
(296, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:55:54 -> 2023-07-17 15:55:56<br/>Failed Login Attempts: 4 -> 5<br/>', '1', '2023-07-17 15:55:56'),
(297, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:55:56 -> 2023-07-17 15:56:02<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-07-17 15:56:02'),
(298, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-07-17 15:56:02'),
(299, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:56:02 -> 2023-07-17 15:56:06<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-07-17 15:56:06'),
(300, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-07-17 15:56:06'),
(301, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:56:06 -> 2023-07-17 15:56:08<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-07-17 15:56:08'),
(302, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-07-17 15:56:08'),
(303, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:56:08 -> 2023-07-17 15:56:10<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-07-17 15:56:10'),
(304, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-07-17 15:56:10'),
(305, 'users', 1, 'Failed Login Attempts: 9 -> 10<br/>', '1', '2023-07-17 15:56:10'),
(306, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-07-17 15:56:10'),
(307, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:56:10 -> 2023-07-17 15:56:13<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-07-17 15:56:13'),
(308, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-07-17 15:56:13'),
(309, 'users', 1, 'User created. <br/><br/>File As: Administrator<br/>Email: ldagulto@encorefinancials.com<br/>Is Active: 1<br/>Password Expiry Date: 2023-12-30<br/>2-Factor Authentication: 1', '1', '2023-07-17 15:58:48'),
(310, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-07-17 15:58:51'),
(311, 'users', 1, 'Last Failed Login Attempt: 2023-07-17 15:58:51 -> 2023-07-17 15:58:55<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-07-17 15:58:55'),
(312, 'users', 1, 'Failed Login Attempts: 2 -> 0<br/>', '1', '2023-07-17 15:58:56'),
(313, 'users', 1, 'Remember Me: 0 -> 1<br/>', '1', '2023-07-17 15:58:56'),
(314, 'menu_group', 1, 'Order Sequence: 1 -> 2<br/>', '1', '2023-07-17 16:18:17'),
(315, 'menu_group', 2, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 2', '1', '2023-07-17 16:18:23'),
(316, 'menu_group', 2, 'Menu Group Name: Technical -> Technical2<br/>', '1', '2023-07-17 16:18:26'),
(317, 'menu_group', 3, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 2', '1', '2023-07-17 16:18:45'),
(318, 'menu_group', 4, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 2', '1', '2023-07-17 16:18:51'),
(319, 'menu_item', 1, 'Order Sequence: 50 -> 1<br/>', '1', '2023-07-17 16:34:50'),
(320, 'menu_item', 1, 'Order Sequence: 1 -> 51<br/>', '1', '2023-07-17 16:34:55'),
(321, 'menu_item', 7, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 51', '1', '2023-07-17 16:35:14'),
(322, 'menu_item', 8, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 51', '1', '2023-07-17 16:35:17'),
(323, 'menu_item', 9, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 51', '1', '2023-07-17 16:35:20'),
(324, 'menu_item', 9, 'Order Sequence: 51 -> 2<br/>', '1', '2023-07-17 16:35:28'),
(325, 'menu_access_right', 9, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-17 16:35:34'),
(326, 'system_action', 8, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-17 16:35:59'),
(327, 'system_action', 9, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-17 16:36:04'),
(328, 'system_action', 10, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-17 16:36:07'),
(329, 'system_action', 10, 'System Action Name: test -> test2<br/>', '1', '2023-07-17 16:36:14'),
(330, 'menu_access_right', 0, 'Menu item access rights created. <br/>', '1', '2023-07-17 16:42:28'),
(331, 'menu_access_right', 0, 'Role ID: 0<br/>', '1', '2023-07-17 16:42:28'),
(332, 'menu_access_right', 1, 'Menu item access rights created. <br/>', '1', '2023-07-17 17:13:18'),
(333, 'menu_access_right', 1, 'Role ID: 0<br/>', '1', '2023-07-17 17:13:18'),
(334, 'menu_access_right', 1, 'Role ID: 0<br/>', '1', '2023-07-17 17:16:54'),
(335, 'menu_access_right', 1, 'Role ID: 0<br/>', '1', '2023-07-17 17:24:07'),
(336, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:01'),
(337, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:01'),
(338, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:01'),
(339, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:01'),
(340, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:01'),
(341, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:01'),
(342, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:01'),
(343, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:01'),
(344, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:01'),
(345, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:01'),
(346, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:04'),
(347, 'menu_access_right', 1, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-17 17:25:04'),
(348, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:04'),
(349, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:04'),
(350, 'menu_access_right', 1, 'Role ID: 1<br/>', '1', '2023-07-17 17:25:04'),
(351, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:04'),
(352, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:04'),
(353, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:04'),
(354, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:04'),
(355, 'menu_access_right', 1, 'Role ID: 2<br/>', '1', '2023-07-17 17:25:04'),
(356, 'menu_access_right', 1, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-18 14:33:22'),
(357, 'menu_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 14:33:22'),
(358, 'menu_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 14:33:22'),
(359, 'menu_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-18 14:33:22'),
(360, 'menu_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 14:33:22'),
(361, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 14:33:22'),
(362, 'menu_item', 1, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 50', '1', '2023-07-18 14:33:39'),
(363, 'menu_item', 2, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 1<br/>URL: menu-group.php<br/>Parent ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 14:33:39'),
(364, 'menu_item', 3, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 1<br/>URL: menu-item.php<br/>Parent ID: 1<br/>Order Sequence: 2', '1', '2023-07-18 14:33:39'),
(365, 'menu_item', 4, 'Menu item created. <br/><br/>Menu Item Name: Administration<br/>Menu Group ID: 1<br/>Menu Item Icon: shield<br/>Order Sequence: 1', '1', '2023-07-18 14:33:39'),
(366, 'menu_item', 5, 'Menu item created. <br/><br/>Menu Item Name: System Action<br/>Menu Group ID: 1<br/>URL: system-action.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-07-18 14:33:39'),
(367, 'menu_item', 6, 'Menu item created. <br/><br/>Menu Item Name: Role Configuration<br/>Menu Group ID: 1<br/>URL: role-configuration.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-07-18 14:33:39'),
(368, 'menu_group', 5, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2', '1', '2023-07-18 14:48:39'),
(369, 'menu_group', 6, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 1', '1', '2023-07-18 14:48:51'),
(370, 'menu_group', 6, 'Menu Group Name: test -> test2<br/>', '1', '2023-07-18 14:48:57'),
(371, 'menu_group', 7, 'Menu group created. <br/><br/>Menu Group Name: test2<br/>Order Sequence: 1', '1', '2023-07-18 14:49:00'),
(372, 'menu_group', 8, 'Menu group created. <br/><br/>Menu Group Name: test2<br/>Order Sequence: 1', '1', '2023-07-18 14:49:06'),
(373, 'menu_item', 7, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 8<br/>Order Sequence: 1', '1', '2023-07-18 14:49:12'),
(374, 'menu_item', 7, 'Menu Item Name: test -> test2<br/>', '1', '2023-07-18 14:49:15'),
(375, 'menu_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-18 15:00:28'),
(376, 'menu_access_right', 7, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-18 15:00:28'),
(377, 'menu_access_right', 7, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-18 15:00:28'),
(378, 'menu_access_right', 7, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-18 15:00:28'),
(379, 'menu_access_right', 7, 'Role ID: 1<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-18 15:00:28'),
(380, 'menu_access_right', 7, 'Role ID: 1<br/>Duplicate Access: 0 -> 1<br/>', '1', '2023-07-18 15:00:28'),
(381, 'menu_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-18 15:00:28'),
(382, 'menu_access_right', 7, 'Role ID: 2<br/>', '1', '2023-07-18 15:00:28'),
(383, 'menu_access_right', 7, 'Role ID: 2<br/>', '1', '2023-07-18 15:00:28'),
(384, 'menu_access_right', 7, 'Role ID: 2<br/>', '1', '2023-07-18 15:00:28'),
(385, 'menu_access_right', 7, 'Role ID: 2<br/>', '1', '2023-07-18 15:00:28'),
(386, 'menu_access_right', 7, 'Role ID: 2<br/>', '1', '2023-07-18 15:00:28'),
(387, 'menu_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-18 15:00:28'),
(388, 'menu_access_right', 7, 'Role ID: 6<br/>', '1', '2023-07-18 15:00:28'),
(389, 'menu_access_right', 7, 'Role ID: 6<br/>', '1', '2023-07-18 15:00:28'),
(390, 'menu_access_right', 7, 'Role ID: 6<br/>', '1', '2023-07-18 15:00:28'),
(391, 'menu_access_right', 7, 'Role ID: 6<br/>', '1', '2023-07-18 15:00:28'),
(392, 'menu_access_right', 7, 'Role ID: 6<br/>', '1', '2023-07-18 15:00:28'),
(393, 'menu_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 7', '1', '2023-07-18 15:00:28'),
(394, 'menu_access_right', 7, 'Role ID: 7<br/>', '1', '2023-07-18 15:00:28'),
(395, 'menu_access_right', 7, 'Role ID: 7<br/>', '1', '2023-07-18 15:00:28'),
(396, 'menu_access_right', 7, 'Role ID: 7<br/>', '1', '2023-07-18 15:00:28'),
(397, 'menu_access_right', 7, 'Role ID: 7<br/>', '1', '2023-07-18 15:00:28'),
(398, 'menu_access_right', 7, 'Role ID: 7<br/>', '1', '2023-07-18 15:00:28'),
(399, 'menu_item', 8, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 15:06:49'),
(400, 'menu_item', 9, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 15:06:52'),
(401, 'menu_item', 10, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 15:07:02'),
(402, 'menu_item', 11, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 15:07:08'),
(403, 'users', 1, 'OTP: 58phScmBJeTnfploDORIAt0T8t4XuA17VYzdkZrJzPc%3D -> BeKaVyhXg9gdo5XXiA6J7GwRWclnPsblDci6Ppsuwvs%3D<br/>OTP Expiry Date: 2023-07-17 16:03:56 -> 2023-07-18 15:17:45<br/>Remember Me: 1 -> 0<br/>', '1', '2023-07-18 15:12:45'),
(404, 'users', 1, 'Last Connection Date: 2023-07-17 15:59:19 -> 2023-07-18 15:13:04<br/>', '1', '2023-07-18 15:13:04'),
(405, 'menu_item', 12, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 50', '1', '2023-07-18 15:14:10'),
(406, 'menu_item', 13, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 1<br/>URL: menu-group.php<br/>Parent ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 15:14:10'),
(407, 'menu_item', 14, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 1<br/>URL: menu-item.php<br/>Parent ID: 1<br/>Order Sequence: 2', '1', '2023-07-18 15:14:10'),
(408, 'menu_item', 15, 'Menu item created. <br/><br/>Menu Item Name: Administration<br/>Menu Group ID: 1<br/>Menu Item Icon: shield<br/>Order Sequence: 1', '1', '2023-07-18 15:14:10'),
(409, 'menu_item', 16, 'Menu item created. <br/><br/>Menu Item Name: System Action<br/>Menu Group ID: 1<br/>URL: system-action.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-07-18 15:14:10'),
(410, 'menu_item', 17, 'Menu item created. <br/><br/>Menu Item Name: Role Configuration<br/>Menu Group ID: 1<br/>URL: role-configuration.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-07-18 15:14:10'),
(411, 'menu_access_right', 1, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-18 15:14:36'),
(412, 'menu_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 15:14:36'),
(413, 'menu_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 15:14:36'),
(414, 'menu_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-18 15:14:36'),
(415, 'menu_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 15:14:36'),
(416, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-07-18 15:14:36'),
(417, 'menu_item', 1, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 1<br/>Menu Item Icon: sidebar<br/>Order Sequence: 50', '1', '2023-07-18 15:14:59'),
(418, 'menu_item', 2, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 1<br/>URL: menu-group.php<br/>Parent ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 15:14:59'),
(419, 'menu_item', 3, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 1<br/>URL: menu-item.php<br/>Parent ID: 1<br/>Order Sequence: 2', '1', '2023-07-18 15:14:59'),
(420, 'menu_item', 4, 'Menu item created. <br/><br/>Menu Item Name: Administration<br/>Menu Group ID: 1<br/>Menu Item Icon: shield<br/>Order Sequence: 1', '1', '2023-07-18 15:14:59'),
(421, 'menu_item', 5, 'Menu item created. <br/><br/>Menu Item Name: System Action<br/>Menu Group ID: 1<br/>URL: system-action.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-07-18 15:14:59'),
(422, 'menu_item', 6, 'Menu item created. <br/><br/>Menu Item Name: Role Configuration<br/>Menu Group ID: 1<br/>URL: role-configuration.php<br/>Parent ID: 4<br/>Order Sequence: 10', '1', '2023-07-18 15:14:59'),
(423, 'menu_item', 7, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 1', '1', '2023-07-18 15:15:14'),
(424, 'menu_item', 8, 'Menu item created. <br/><br/>Menu Item Name: 1<br/>Menu Group ID: 1<br/>Order Sequence: 2', '1', '2023-07-18 15:47:23');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(425, 'menu_item', 9, 'Menu item created. <br/><br/>Menu Item Name: 1<br/>Menu Group ID: 1<br/>Order Sequence: 2', '1', '2023-07-18 15:47:28'),
(426, 'system_action', 11, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-18 15:52:53'),
(427, 'system_action', 11, 'System Action Name: test -> test2<br/>', '1', '2023-07-18 15:52:56'),
(428, 'system_action', 12, 'System action created. <br/><br/>System Action Name: test2', '1', '2023-07-18 15:52:59'),
(429, 'system_action', 13, 'System action created. <br/><br/>System Action Name: test2', '1', '2023-07-18 15:53:03'),
(430, 'system_action', 14, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-18 16:09:14'),
(431, 'system_action', 15, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-18 16:09:18'),
(432, 'system_action', 16, 'System action created. <br/><br/>System Action Name: test', '1', '2023-07-18 16:09:22'),
(433, 'system_action', 16, 'System Action Name: test -> test2<br/>', '1', '2023-07-18 16:09:25'),
(434, 'role', 8, 'Role created. <br/><br/>Role Name: test<br/>Role Description: test<br/>Assignable: 1', '1', '2023-07-18 16:09:49'),
(435, 'role', 8, 'Role Name: test -> test2<br/>Role Description: test -> test2<br/>', '1', '2023-07-18 16:09:53'),
(436, 'role', 9, 'Role created. <br/><br/>Role Name: test2<br/>Role Description: test2<br/>Assignable: 1', '1', '2023-07-18 16:09:56'),
(437, 'role', 10, 'Role created. <br/><br/>Role Name: test2<br/>Role Description: test2<br/>Assignable: 1', '1', '2023-07-18 16:10:24'),
(438, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(439, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(440, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(441, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(442, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(443, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(444, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(445, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(446, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(447, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(448, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(449, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(450, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(451, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(452, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:36'),
(453, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:36'),
(454, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:36'),
(455, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:36'),
(456, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:36'),
(457, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:36'),
(458, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:36'),
(459, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:36'),
(460, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:36'),
(461, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:36'),
(462, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:36'),
(463, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(464, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(465, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(466, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(467, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(468, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(469, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(470, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(471, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(472, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(473, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(474, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(475, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(476, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(477, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:15:52'),
(478, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:52'),
(479, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:52'),
(480, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:52'),
(481, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:52'),
(482, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:15:52'),
(483, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:52'),
(484, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:52'),
(485, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:52'),
(486, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:52'),
(487, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:15:52'),
(488, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(489, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(490, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(491, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(492, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(493, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(494, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(495, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(496, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(497, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(498, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(499, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(500, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(501, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(502, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:16:27'),
(503, 'menu_access_right', 6, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-18 17:16:27'),
(504, 'menu_access_right', 6, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-18 17:16:27'),
(505, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:16:27'),
(506, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:16:27'),
(507, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:16:27'),
(508, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:16:27'),
(509, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:16:27'),
(510, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:16:27'),
(511, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:16:27'),
(512, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:16:27'),
(513, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(514, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(515, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(516, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(517, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(518, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(519, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(520, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(521, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(522, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(523, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(524, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(525, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(526, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(527, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:10'),
(528, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:10'),
(529, 'menu_access_right', 6, 'Role ID: 2<br/>Write Access: 1 -> 0<br/>', '1', '2023-07-18 17:17:10'),
(530, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:10'),
(531, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:10'),
(532, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:10'),
(533, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:10'),
(534, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:10'),
(535, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:10'),
(536, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:10'),
(537, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:10'),
(538, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(539, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(540, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(541, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(542, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(543, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(544, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(545, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(546, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(547, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(548, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(549, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(550, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(551, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(552, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:13'),
(553, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:13'),
(554, 'menu_access_right', 6, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-18 17:17:13'),
(555, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:13'),
(556, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:13'),
(557, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:13'),
(558, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:13'),
(559, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:13'),
(560, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:13'),
(561, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:13'),
(562, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:13'),
(563, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(564, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(565, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(566, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(567, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(568, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(569, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(570, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(571, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(572, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(573, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(574, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(575, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(576, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(577, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:22'),
(578, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:22'),
(579, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:22'),
(580, 'menu_access_right', 6, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-18 17:17:22'),
(581, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:22'),
(582, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:22'),
(583, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:22'),
(584, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:22'),
(585, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:22'),
(586, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:22'),
(587, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:22'),
(588, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(589, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(590, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(591, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(592, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(593, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(594, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(595, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(596, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(597, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(598, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(599, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(600, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(601, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(602, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:17:43'),
(603, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:43'),
(604, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:43'),
(605, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:43'),
(606, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:43'),
(607, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:17:43'),
(608, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:43'),
(609, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:43'),
(610, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:43'),
(611, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:43'),
(612, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:17:43'),
(613, 'role', 11, 'Role created. <br/><br/>Role Name: test2<br/>Role Description: test<br/>Assignable: 1', '1', '2023-07-18 17:20:34'),
(614, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 11', '1', '2023-07-18 17:20:50'),
(615, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(616, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(617, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(618, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(619, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(620, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(621, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(622, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(623, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(624, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(625, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(626, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(627, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(628, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(629, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-18 17:20:54'),
(630, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:20:54'),
(631, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:20:54'),
(632, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:20:54'),
(633, 'menu_access_right', 6, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-18 17:20:54'),
(634, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-18 17:20:54'),
(635, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:20:54'),
(636, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:20:54'),
(637, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:20:54'),
(638, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:20:54'),
(639, 'menu_access_right', 6, 'Role ID: 6<br/>', '1', '2023-07-18 17:20:54'),
(640, 'menu_access_right', 6, 'Role ID: 11<br/>', '1', '2023-07-18 17:20:54'),
(641, 'menu_access_right', 6, 'Role ID: 11<br/>', '1', '2023-07-18 17:20:54'),
(642, 'menu_access_right', 6, 'Role ID: 11<br/>', '1', '2023-07-18 17:20:54'),
(643, 'menu_access_right', 6, 'Role ID: 11<br/>', '1', '2023-07-18 17:20:54'),
(644, 'menu_access_right', 6, 'Role ID: 11<br/>', '1', '2023-07-18 17:20:54'),
(645, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-07-19 08:41:44'),
(646, 'users', 1, 'Failed Login Attempts: 1 -> 0<br/>', '1', '2023-07-19 08:41:49'),
(647, 'users', 1, 'OTP: BeKaVyhXg9gdo5XXiA6J7GwRWclnPsblDci6Ppsuwvs%3D -> xzaYdoh%2BjTVL7A8mvJM182pWYRKZC%2FtBqCr0%2FxYmrSE%3D<br/>OTP Expiry Date: 2023-07-18 15:17:45 -> 2023-07-19 08:46:49<br/>Remember Me: 0 -> 1<br/>', '1', '2023-07-19 08:41:49'),
(648, 'users', 1, 'OTP: xzaYdoh%2BjTVL7A8mvJM182pWYRKZC%2FtBqCr0%2FxYmrSE%3D -> ZLryvTiuBbP20aocMKrt5sFyV%2FU1buhYN9soR3XUZ3w%3D<br/>OTP Expiry Date: 2023-07-19 08:46:49 -> 2023-07-19 08:57:46<br/>Remember Me: 1 -> 0<br/>', '1', '2023-07-19 08:52:46'),
(649, 'users', 1, 'Last Connection Date: 2023-07-18 15:13:04 -> 2023-07-19 08:53:07<br/>', '1', '2023-07-19 08:53:07'),
(650, 'users', 1, '2-Factor Authentication: 1 -> 0<br/>', '1', '2023-07-19 08:53:11'),
(651, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(652, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(653, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(654, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(655, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(656, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(657, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(658, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(659, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(660, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(661, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(662, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(663, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(664, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(665, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:50'),
(666, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:50'),
(667, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:50'),
(668, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:50'),
(669, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:50'),
(670, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:50'),
(671, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:50'),
(672, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:50'),
(673, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:50'),
(674, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:50'),
(675, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:50'),
(676, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(677, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(678, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(679, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(680, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(681, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(682, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(683, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(684, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(685, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(686, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(687, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(688, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(689, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(690, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:53'),
(691, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:53'),
(692, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:53'),
(693, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:53'),
(694, 'menu_access_right', 5, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-19 09:34:53'),
(695, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:53'),
(696, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:53'),
(697, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:53'),
(698, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:53'),
(699, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:53'),
(700, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:53'),
(701, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(702, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(703, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(704, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(705, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(706, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(707, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(708, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(709, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(710, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(711, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(712, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(713, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(714, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(715, 'menu_access_right', 5, 'Role ID: 1<br/>', '1', '2023-07-19 09:34:56'),
(716, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:56'),
(717, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:56'),
(718, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:56'),
(719, 'menu_access_right', 5, 'Role ID: 2<br/>Delete Access: 1 -> 0<br/>', '1', '2023-07-19 09:34:56'),
(720, 'menu_access_right', 5, 'Role ID: 2<br/>', '1', '2023-07-19 09:34:56'),
(721, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:56'),
(722, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:56'),
(723, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:56'),
(724, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:56'),
(725, 'menu_access_right', 5, 'Role ID: 6<br/>', '1', '2023-07-19 09:34:56'),
(726, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(727, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(728, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(729, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(730, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(731, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(732, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(733, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(734, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(735, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(736, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(737, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(738, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(739, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(740, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:38:49'),
(741, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:38:49'),
(742, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:38:49'),
(743, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:38:49'),
(744, 'menu_access_right', 6, 'Role ID: 2<br/>Delete Access: 1 -> 0<br/>', '1', '2023-07-19 11:38:49'),
(745, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:38:49'),
(746, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-19 11:43:45'),
(747, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(748, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(749, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(750, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(751, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(752, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(753, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(754, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(755, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(756, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(757, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(758, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(759, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(760, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(761, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 11:44:13'),
(762, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:44:13'),
(763, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:44:13'),
(764, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:44:13'),
(765, 'menu_access_right', 6, 'Role ID: 2<br/>Delete Access: 0 -> 1<br/>', '1', '2023-07-19 11:44:13'),
(766, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 11:44:13'),
(767, 'menu_access_right', 0, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-19 17:10:44'),
(768, 'menu_access_right', 0, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-19 17:10:44'),
(769, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(770, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(771, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(772, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(773, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(774, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(775, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(776, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(777, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(778, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(779, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(780, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(781, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(782, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(783, 'menu_access_right', 6, 'Role ID: 1<br/>', '1', '2023-07-19 17:12:37'),
(784, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 17:12:37'),
(785, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 17:12:37'),
(786, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 17:12:37'),
(787, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 17:12:37'),
(788, 'menu_access_right', 6, 'Role ID: 2<br/>', '1', '2023-07-19 17:12:37'),
(789, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 11', '1', '2023-07-19 17:12:40'),
(790, 'menu_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-19 17:13:32'),
(791, 'users', 1, 'Last Connection Date: 2023-07-19 08:53:07 -> 2023-07-20 10:13:47<br/>', '1', '2023-07-20 10:13:47'),
(792, 'menu_access_right', 0, 'Role ID: 1<br/>', '1', '2023-07-20 13:32:36'),
(793, 'menu_access_right', 0, 'Role ID: 2<br/>', '1', '2023-07-20 13:32:36'),
(794, 'menu_access_right', 0, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-20 13:32:36'),
(795, 'menu_access_right', 0, 'Role ID: 6<br/>', '1', '2023-07-20 13:32:36'),
(796, 'menu_access_right', 0, 'Menu item access rights created. <br/><br/>Role ID: 11', '1', '2023-07-20 13:32:36'),
(797, 'menu_access_right', 0, 'Role ID: 11<br/>', '1', '2023-07-20 13:32:36'),
(798, 'menu_access_right', 0, 'Role ID: 1<br/>', '1', '2023-07-20 14:17:46'),
(799, 'menu_access_right', 0, 'Role ID: 2<br/>', '1', '2023-07-20 14:17:46'),
(800, 'menu_access_right', 0, 'Role ID: 6<br/>', '1', '2023-07-20 14:17:46'),
(801, 'menu_access_right', 0, 'Role ID: 11<br/>', '1', '2023-07-20 14:17:46'),
(802, 'users', 1, 'Last Connection Date: 2023-07-20 10:13:47 -> 2023-07-21 14:07:54<br/>', '1', '2023-07-21 14:07:54'),
(803, 'users', 1, 'Last Connection Date: 2023-07-21 14:07:54 -> 2023-07-24 08:56:36<br/>', '1', '2023-07-24 08:56:36'),
(804, 'users', 1, 'Last Connection Date: 2023-07-24 08:56:36 -> 2023-07-25 10:23:55<br/>', '1', '2023-07-25 10:23:55'),
(805, 'users', 1, 'Last Connection Date: 2023-07-25 10:23:55 -> 2023-07-26 13:24:21<br/>', '1', '2023-07-26 13:24:21'),
(806, 'users', 1, 'Last Connection Date: 2023-07-26 13:24:21 -> 2023-07-27 12:00:27<br/>', '1', '2023-07-27 12:00:27'),
(807, 'menu_item', 10, 'Menu item created. <br/><br/>Menu Item Name: test<br/>Menu Group ID: 1<br/>Order Sequence: 12', '1', '2023-07-27 13:13:13'),
(808, 'menu_access_right', 10, 'Menu item access rights created. <br/>', '1', '2023-07-27 15:25:35'),
(809, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-27 15:28:54'),
(810, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-27 15:28:54'),
(811, 'menu_access_right', 10, 'Role ID: 0<br/>', '1', '2023-07-27 15:37:05'),
(812, 'menu_access_right', 10, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-27 15:37:35'),
(813, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:37:35'),
(814, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:37:35'),
(815, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:37:35'),
(816, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:37:35'),
(817, 'menu_access_right', 10, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-27 15:37:35'),
(818, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:37:35'),
(819, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:37:35'),
(820, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:37:35'),
(821, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:37:35'),
(822, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:47'),
(823, 'menu_access_right', 10, 'Role ID: 1<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-27 15:40:47'),
(824, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:47'),
(825, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:47'),
(826, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:47'),
(827, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:47'),
(828, 'menu_access_right', 10, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-27 15:40:47'),
(829, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:47'),
(830, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:47'),
(831, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:47'),
(832, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:54'),
(833, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:54'),
(834, 'menu_access_right', 10, 'Role ID: 1<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-27 15:40:54'),
(835, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:54'),
(836, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:40:54'),
(837, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:54'),
(838, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:54'),
(839, 'menu_access_right', 10, 'Role ID: 2<br/>Create Access: 0 -> 1<br/>', '1', '2023-07-27 15:40:54'),
(840, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:54'),
(841, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:40:54'),
(842, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-27 15:43:26'),
(843, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-27 15:43:26'),
(844, 'menu_access_right', 10, 'Role ID: 1<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-27 15:43:29'),
(845, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:43:29'),
(846, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:43:29'),
(847, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:43:29'),
(848, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 15:43:29'),
(849, 'menu_access_right', 10, 'Role ID: 2<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-27 15:43:29'),
(850, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:43:29'),
(851, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:43:29'),
(852, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:43:29'),
(853, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 15:43:29'),
(854, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-27 15:45:57'),
(855, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-27 15:45:57'),
(856, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 6', '1', '2023-07-27 15:45:57'),
(857, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 11', '1', '2023-07-27 15:45:57'),
(858, 'menu_access_right', 10, 'Role ID: 6<br/>Read Access: 0 -> 1<br/>', '1', '2023-07-27 16:13:02'),
(859, 'menu_access_right', 10, 'Role ID: 6<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-27 16:13:02'),
(860, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:02'),
(861, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:02'),
(862, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:02'),
(863, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:06'),
(864, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:06'),
(865, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:06'),
(866, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:06'),
(867, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:06'),
(868, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:32'),
(869, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:32'),
(870, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:32'),
(871, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:32'),
(872, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:13:32'),
(873, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:10'),
(874, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:10'),
(875, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:10'),
(876, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:10'),
(877, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:10'),
(878, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 1', '1', '2023-07-27 16:19:13'),
(879, 'menu_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 2', '1', '2023-07-27 16:19:13'),
(880, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 16:19:17'),
(881, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 16:19:17'),
(882, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 16:19:17'),
(883, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 16:19:17'),
(884, 'menu_access_right', 10, 'Role ID: 1<br/>', '1', '2023-07-27 16:19:17'),
(885, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 16:19:17'),
(886, 'menu_access_right', 10, 'Role ID: 2<br/>Write Access: 0 -> 1<br/>', '1', '2023-07-27 16:19:17'),
(887, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 16:19:17'),
(888, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 16:19:17'),
(889, 'menu_access_right', 10, 'Role ID: 2<br/>', '1', '2023-07-27 16:19:17'),
(890, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:17'),
(891, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:17'),
(892, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:17'),
(893, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:17'),
(894, 'menu_access_right', 10, 'Role ID: 6<br/>', '1', '2023-07-27 16:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `menu_access_right`
--

CREATE TABLE `menu_access_right` (
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
-- Dumping data for table `menu_access_right`
--

INSERT INTO `menu_access_right` (`menu_item_id`, `role_id`, `read_access`, `write_access`, `create_access`, `delete_access`, `duplicate_access`, `last_log_by`) VALUES
(1, 1, 1, 1, 0, 0, 0, 1),
(2, 1, 1, 1, 1, 1, 1, 1),
(3, 1, 1, 1, 1, 1, 1, 1),
(6, 1, 1, 1, 1, 1, 1, 1),
(6, 2, 1, 1, 1, 1, 0, 1),
(1, 2, 0, 0, 0, 0, 0, 1),
(11, 1, 1, 1, 1, 1, 1, 1),
(11, 2, 0, 0, 0, 0, 0, 1),
(4, 1, 1, 0, 0, 0, 0, 1),
(4, 2, 0, 0, 0, 0, 0, 1),
(5, 1, 1, 1, 1, 1, 1, 1),
(5, 2, 0, 0, 0, 0, 0, 1),
(4, 6, 0, 0, 0, 0, 0, 1),
(6, 7, 0, 0, 0, 0, 0, 1),
(2, 2, 0, 0, 0, 0, 0, 1),
(2, 6, 0, 0, 0, 0, 0, 1),
(2, 7, 0, 0, 0, 0, 0, 1),
(3, 2, 0, 0, 0, 0, 0, 1),
(3, 6, 0, 0, 0, 0, 0, 1),
(3, 7, 0, 0, 0, 0, 0, 1),
(4, 7, 0, 0, 0, 0, 0, 1),
(5, 6, 0, 0, 0, 0, 0, 1),
(2, 0, 0, 0, 0, 0, 0, 1),
(5, 7, 0, 0, 0, 0, 0, 1),
(9, 1, 0, 0, 0, 0, 0, 1),
(0, 0, 0, 0, 0, 0, 0, 1),
(1, 0, 0, 0, 0, 0, 0, 1),
(1, 1, 0, 0, 0, 0, 0, 1),
(2, 1, 1, 1, 1, 1, 1, 1),
(3, 1, 1, 1, 1, 1, 1, 1),
(4, 1, 0, 0, 0, 0, 0, 1),
(5, 1, 1, 1, 1, 1, 1, 1),
(6, 1, 1, 1, 1, 1, 1, 1),
(7, 1, 1, 1, 1, 1, 1, 1),
(7, 2, 0, 0, 0, 0, 0, 1),
(7, 6, 0, 0, 0, 0, 0, 1),
(7, 7, 0, 0, 0, 0, 0, 1),
(1, 1, 0, 0, 0, 0, 0, 1),
(2, 1, 1, 1, 1, 1, 1, 1),
(3, 1, 1, 1, 1, 1, 1, 1),
(4, 1, 0, 0, 0, 0, 0, 1),
(5, 1, 1, 1, 1, 1, 1, 1),
(6, 1, 1, 1, 1, 1, 1, 1),
(0, 1, 0, 0, 0, 0, 0, 1),
(0, 2, 0, 0, 0, 0, 0, 1),
(6, 11, 0, 0, 0, 0, 0, 1),
(6, 6, 0, 0, 0, 0, 0, 1),
(0, 11, 0, 0, 0, 0, 0, 1),
(10, 0, 0, 0, 0, 0, 0, 1),
(10, 2, 0, 1, 0, 0, 0, 1);

--
-- Triggers `menu_access_right`
--
DELIMITER $$
CREATE TRIGGER `menu_access_right_insert` AFTER INSERT ON `menu_access_right` FOR EACH ROW BEGIN
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
    VALUES ('menu_access_right', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `menu_access_right_update` AFTER UPDATE ON `menu_access_right` FOR EACH ROW BEGIN
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
        VALUES ('menu_access_right', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
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
(1, 'Technical', 2, 1),
(5, 'test', 2, 1);

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
(5, 'System Action', 1, 'system-action.php', 4, '', 10, 1),
(6, 'Role Configuration', 1, 'role-configuration.php', 4, '', 10, 1),
(10, 'test', 1, '', 0, '', 12, 1);

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
-- Table structure for table `password_history`
--

CREATE TABLE `password_history` (
  `password_history_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_change_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_history`
--

INSERT INTO `password_history` (`password_history_id`, `user_id`, `email`, `password`, `password_change_date`) VALUES
(1, 1, 'ldagulto@encorefinancials.com', '%2FnHtFs4nssZsrx%2F%2BhCyTDkBV%2FHMyu8%2BloCp8YRzuzw4%3D', '2023-06-27 14:05:38'),
(2, 1, 'ldagulto@encorefinancials.com', 'TxI5JjK%2FjzjB98YXfnZHcAjzC%2FmJZdoxXlehRTFalYo%3D', '2023-07-17 11:48:16'),
(3, 1, 'ldagulto@encorefinancials.com', '1eaFA0XFjt6nCT4qoy%2BDTKH4M2pz22YZn7uXE0S9Jkc%3D', '2023-07-17 11:59:30'),
(4, 1, 'ldagulto@encorefinancials.com', 'x3mFjsCr1HLOsLYu2D0ESIHlGa2WAub%2FxI9IaMRE5Ag%3D', '2023-07-17 12:01:53');

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
(1, 'Administrator', 'Administrator', 1, 1),
(2, 'Employee', 'Employee', 1, 1),
(6, 'Test Role', 'test', 1, 1),
(11, 'test2', 'test', 1, 1);

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
(1, 1, 1);

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
(4, 'Delete System Action Role Access', 1);

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
(2, 1, 1, NULL),
(3, 1, 1, NULL),
(4, 1, 1, 1),
(0, 1, 0, NULL),
(4, 2, 0, 1);

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `file_as` varchar(300) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
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

INSERT INTO `users` (`user_id`, `file_as`, `email`, `password`, `is_locked`, `is_active`, `last_failed_login_attempt`, `failed_login_attempts`, `last_connection_date`, `password_expiry_date`, `reset_token`, `reset_token_expiry_date`, `receive_notification`, `two_factor_auth`, `otp`, `otp_expiry_date`, `failed_otp_attempts`, `last_password_change`, `account_lock_duration`, `last_password_reset`, `remember_me`, `remember_token`, `last_log_by`) VALUES
(1, 'Administrator', 'ldagulto@encorefinancials.com', 'RYHObc8sNwIxdPDNJwCsO8bXKZJXYx7RjTgEWMC17FY%3D', 0, 1, NULL, 0, '2023-07-27 12:00:27', '2023-12-30', NULL, NULL, 0, 0, 'ZLryvTiuBbP20aocMKrt5sFyV%2FU1buhYN9soR3XUZ3w%3D', '2023-07-19 08:57:46', 0, NULL, 0, NULL, 0, '49ecad48f2f15e3ba7ba7579a041b590', 1);

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

    IF NEW.reset_token <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reset Token: ", NEW.reset_token);
    END IF;

    IF NEW.reset_token_expiry_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reset Token Expiry Date: ", NEW.reset_token_expiry_date);
    END IF;

    IF NEW.receive_notification <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Receive Notification: ", NEW.receive_notification);
    END IF;

    IF NEW.two_factor_auth <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>2-Factor Authentication: ", NEW.two_factor_auth);
    END IF;

    IF NEW.otp <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>OTP: ", NEW.otp);
    END IF;

    IF NEW.otp_expiry_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>OTP Expiry Date: ", NEW.otp_expiry_date);
    END IF;

    IF NEW.failed_otp_attempts <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Failed OTP Attempts: ", NEW.failed_otp_attempts);
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

    IF NEW.remember_token <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remember Token: ", NEW.remember_token);
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

    IF NEW.reset_token <> OLD.reset_token THEN
        SET audit_log = CONCAT(audit_log, "Reset Token: ", OLD.reset_token, " -> ", NEW.reset_token, "<br/>");
    END IF;

    IF NEW.reset_token_expiry_date <> OLD.reset_token_expiry_date THEN
        SET audit_log = CONCAT(audit_log, "Reset Token Expiry Date: ", OLD.reset_token_expiry_date, " -> ", NEW.reset_token_expiry_date, "<br/>");
    END IF;

    IF NEW.receive_notification <> OLD.receive_notification THEN
        SET audit_log = CONCAT(audit_log, "Receive Notification: ", OLD.receive_notification, " -> ", NEW.receive_notification, "<br/>");
    END IF;

    IF NEW.two_factor_auth <> OLD.two_factor_auth THEN
        SET audit_log = CONCAT(audit_log, "2-Factor Authentication: ", OLD.two_factor_auth, " -> ", NEW.two_factor_auth, "<br/>");
    END IF;

    IF NEW.otp <> OLD.otp THEN
        SET audit_log = CONCAT(audit_log, "OTP: ", OLD.otp, " -> ", NEW.otp, "<br/>");
    END IF;

    IF NEW.otp_expiry_date <> OLD.otp_expiry_date THEN
        SET audit_log = CONCAT(audit_log, "OTP Expiry Date: ", OLD.otp_expiry_date, " -> ", NEW.otp_expiry_date, "<br/>");
    END IF;

    IF NEW.failed_otp_attempts <> OLD.failed_otp_attempts THEN
        SET audit_log = CONCAT(audit_log, "Failed OTP Attempts: ", OLD.failed_otp_attempts, " -> ", NEW.failed_otp_attempts, "<br/>");
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

    IF NEW.remember_token <> OLD.remember_token THEN
        SET audit_log = CONCAT(audit_log, "Remember Token: ", OLD.remember_token, " -> ", NEW.remember_token, "<br/>");
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
-- Indexes for table `menu_access_right`
--
ALTER TABLE `menu_access_right`
  ADD KEY `role_id` (`role_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

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
-- Indexes for table `ui_customization_setting`
--
ALTER TABLE `ui_customization_setting`
  ADD PRIMARY KEY (`ui_customization_setting_id`),
  ADD KEY `ui_customization_setting_index_ui_customization_setting_id` (`ui_customization_setting_id`),
  ADD KEY `ui_customization_setting_index_user_id` (`user_id`);

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
  MODIFY `audit_log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=895;

--
-- AUTO_INCREMENT for table `menu_group`
--
ALTER TABLE `menu_group`
  MODIFY `menu_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `password_history`
--
ALTER TABLE `password_history`
  MODIFY `password_history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `system_action`
--
ALTER TABLE `system_action`
  MODIFY `system_action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ui_customization_setting`
--
ALTER TABLE `ui_customization_setting`
  MODIFY `ui_customization_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

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
