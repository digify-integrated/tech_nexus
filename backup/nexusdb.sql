-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2023 at 11:27 AM
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkSystemActionAccessRights` (IN `p_user_id` INT, IN `p_system_action_id` INT)   BEGIN
    SELECT role_id 
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_id IN (SELECT role_id FROM role_users WHERE user_id = p_user_id);
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteAllRoleSystemActionAccessRights` (IN `p_system_action_id` INT)   BEGIN
	DELETE FROM system_action_access_rights
    WHERE system_action_id = p_system_action_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSubMenuItemTable` (IN `p_parent_id` INT)   BEGIN
	SELECT menu_item_name, menu_group_id, order_sequence 
    FROM menu_item
    WHERE parent_id = p_parent_id
    ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateSystemActionRoleTable` ()   BEGIN
	SELECT role_id, role_name FROM role
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertRoleSystemActionAccessRights` (IN `p_system_action_id` INT, IN `p_role_id` INT)   BEGIN
    INSERT INTO system_action_access_rights (system_action_id, role_id) 
	VALUES(p_system_action_id, p_role_id);
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
(178, 'system_action', 7, 'System action created. <br/><br/>System Action Name: Assign System Action Role Access', '1', '2023-07-14 17:05:12');

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
(1, 1, 1, 0, 0, 0, 0, 1),
(2, 1, 1, 1, 1, 1, 1, 1),
(3, 1, 1, 1, 1, 1, 1, 1),
(6, 1, 1, 1, 1, 1, 1, 1),
(6, 2, 0, 0, 0, 0, 0, 1),
(1, 2, 0, 0, 0, 0, 0, 1),
(11, 1, 1, 1, 1, 1, 1, 1),
(11, 2, 0, 0, 0, 0, 0, 1),
(4, 1, 1, 0, 0, 0, 0, 1),
(4, 2, 0, 0, 0, 0, 0, 1),
(5, 1, 1, 1, 1, 1, 1, 1),
(5, 2, 0, 0, 0, 0, 0, 1);

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
(5, 'System Action', 1, 'system-action.php', 4, '', 10, 1),
(6, 'Role Configuration', 1, 'role-configuration.php', 4, '', 2, 1);

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
(1, 1, 'ldagulto@encorefinancials.com', '%2FnHtFs4nssZsrx%2F%2BhCyTDkBV%2FHMyu8%2BloCp8YRzuzw4%3D', '2023-06-27 14:05:38');

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
(6, 'Test Role', 'test', 1, 1);

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
(1, 'Assign Menu Item Role Access', 1),
(2, 'Assign System Action Role Access', 1),
(6, 'Assign System Action Role Access', 1);

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
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_action_access_rights`
--

INSERT INTO `system_action_access_rights` (`system_action_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(5, 1);

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
(1, 'Administrator', 'ldagulto@encorefinancials.com', '%2FnHtFs4nssZsrx%2F%2BhCyTDkBV%2FHMyu8%2BloCp8YRzuzw4%3D', 0, 1, NULL, 0, '2023-07-12 09:48:29', '2023-12-27', 'FoL0D0dploLRggOHQpGyHDSQB%2BNOD4az3BbtGJI86Js%3D', '2023-06-27 14:15:10', 1, 0, 'uoJ04qcrOcuN3ykmxi3ur%2B4wUyS0%2FMONdUXrcAs%2Bv1M%3D', '2023-06-29 10:58:26', 0, '2023-06-27 14:05:38', 0, NULL, 0, '458284e66026ca9f6d8b96a5d4d58207', 1);

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
-- Indexes for table `system_action_access_rights`
--
ALTER TABLE `system_action_access_rights`
  ADD KEY `role_id` (`role_id`),
  ADD KEY `system_action_id` (`system_action_id`);

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
  MODIFY `audit_log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `menu_group`
--
ALTER TABLE `menu_group`
  MODIFY `menu_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `password_history`
--
ALTER TABLE `password_history`
  MODIFY `password_history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_action`
--
ALTER TABLE `system_action`
  MODIFY `system_action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
