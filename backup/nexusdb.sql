-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2023 at 11:35 AM
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkSystemActionAccessRights` (IN `p_user_id` INT, IN `p_system_action_id` INT)   BEGIN
	SELECT COUNT(role_id) AS TOTAL
    FROM role_users
    WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM system_action_access_rights where system_action_id = p_system_action_id);
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateMenuGroup` (IN `p_menu_group_id` INT(10), IN `p_last_log_by` INT(10), OUT `p_new_menu_group_id` INT(10))   BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMenuItemOptions` ()   BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
	ORDER BY menu_item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMenuGroup` (IN `p_menu_group_id` INT)   BEGIN
	SELECT * FROM menu_group
	WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getPasswordHistory` (IN `p_user_id` INT, IN `p_email` VARCHAR(255))   BEGIN
	SELECT * FROM password_history
	WHERE p_user_id = p_user_id OR email = BINARY p_email;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertMenuGroup` (IN `p_menu_group_name` VARCHAR(100), IN `p_order_sequence` TINYINT(10), IN `p_last_log_by` INT, OUT `p_menu_group_id` INT(10))   BEGIN
    INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) 
	VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
	
    SET p_menu_group_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertPasswordHistory` (IN `p_user_id` INT, IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_last_password_change` DATETIME)   BEGIN
    INSERT INTO password_history (user_id, email, password, password_change_date) 
    VALUES (p_user_id, p_email, p_password, p_last_password_change);
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
  `reference_id` int(10) NOT NULL,
  `log` text NOT NULL,
  `changed_by` varchar(255) NOT NULL,
  `changed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(1, 'users', 1, 'Failed OTP Attempts: 0 -> 1<br/>', '1', '2023-06-24 15:54:03'),
(2, 'users', 1, 'Failed OTP Attempts: 1 -> 2<br/>', '1', '2023-06-24 15:54:03'),
(3, 'users', 1, 'Failed OTP Attempts: 2 -> 3<br/>', '1', '2023-06-24 15:54:04'),
(4, 'users', 1, 'Failed OTP Attempts: 3 -> 4<br/>', '1', '2023-06-24 15:54:05'),
(5, 'users', 1, 'Failed OTP Attempts: 4 -> 5<br/>', '1', '2023-06-24 15:54:05'),
(6, 'users', 1, 'OTP Expiry Date: 2023-06-24 15:58:48 -> 2023-05-24 15:54:06<br/>', '1', '2023-06-24 15:54:06'),
(7, 'users', 1, 'OTP: V91lUWCTqpwVAht1q9H1r1R9cfPbRwI%2F4BsMfrIkvfw%3D -> qfj4mxyAFRcLPPRsUyn38JIyUqVhhsEC%2F%2BNN30o%2FAUQ%3D<br/>OTP Expiry Date: 2023-05-24 15:54:06 -> 2023-06-24 15:59:17<br/>Failed OTP Attempts: 5 -> 0<br/>', '1', '2023-06-24 15:54:17'),
(8, 'users', 1, 'Receive Notification: 0 -> 1<br/>', '1', '2023-06-24 16:00:46'),
(9, 'users', 1, 'OTP: qfj4mxyAFRcLPPRsUyn38JIyUqVhhsEC%2F%2BNN30o%2FAUQ%3D -> x%2Ff197XrsSLolm%2B5XH6P2TLqgZn64uy%2F1cFLdtyBomM%3D<br/>OTP Expiry Date: 2023-06-24 15:59:17 -> 2023-06-24 16:07:58<br/>', '1', '2023-06-24 16:02:58'),
(10, 'users', 1, 'Last Connection Date: 2023-06-24 15:55:01 -> 2023-06-24 16:03:32<br/>', '1', '2023-06-24 16:03:32'),
(11, 'users', 1, 'OTP: x%2Ff197XrsSLolm%2B5XH6P2TLqgZn64uy%2F1cFLdtyBomM%3D -> E0xmNhot8Cna0dyusq95Xb1V8UGm%2BOsmr8ECUY1x5gY%3D<br/>OTP Expiry Date: 2023-06-24 16:07:58 -> 2023-06-25 13:29:22<br/>', '1', '2023-06-25 13:24:22'),
(12, 'users', 1, 'Last Connection Date: 2023-06-24 16:03:32 -> 2023-06-25 13:24:57<br/>', '1', '2023-06-25 13:24:57'),
(13, 'users', 1, 'OTP: E0xmNhot8Cna0dyusq95Xb1V8UGm%2BOsmr8ECUY1x5gY%3D -> 75okAGo5Rm52Ud0lxU4XuLolYef4r6%2F95MFiuYNjV7s%3D<br/>OTP Expiry Date: 2023-06-25 13:29:22 -> 2023-06-25 19:05:29<br/>Remember Me: 0 -> 1<br/>', '1', '2023-06-25 19:00:29'),
(14, 'users', 1, 'Last Connection Date: 2023-06-25 13:24:57 -> 2023-06-25 19:01:03<br/>', '1', '2023-06-25 19:01:03'),
(15, 'ui_customization_setting', 1, 'UI Customization created. <br/><br/>Theme Contrast: false<br/>Caption Show: true<br/>Preset Theme: preset-1<br/>Dark Layout: light<br/>RTL Layout: false<br/>Box Container: false', '1', '2023-06-25 20:24:33'),
(16, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-25 20:25:41'),
(17, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-06-25 20:25:46'),
(18, 'ui_customization_setting', 1, 'Caption Show: true -> false<br/>', '1', '2023-06-25 20:25:48'),
(19, 'ui_customization_setting', 1, 'Caption Show: false -> true<br/>', '1', '2023-06-25 20:25:49'),
(20, 'ui_customization_setting', 1, 'Box Container: false -> true<br/>', '1', '2023-06-25 20:25:50'),
(21, 'ui_customization_setting', 1, 'Box Container: true -> false<br/>', '1', '2023-06-25 20:25:52'),
(22, 'ui_customization_setting', 1, 'Box Container: false -> true<br/>', '1', '2023-06-25 20:25:54'),
(23, 'ui_customization_setting', 1, 'Box Container: true -> false<br/>', '1', '2023-06-25 20:25:54'),
(24, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-06-25 20:25:58'),
(25, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-06-25 20:25:59'),
(26, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-25 20:26:01'),
(27, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-25 20:34:31'),
(28, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-06-25 20:35:08'),
(29, 'ui_customization_setting', 1, 'Preset Theme: preset-1 -> preset-5<br/>', '1', '2023-06-25 20:35:09'),
(30, 'ui_customization_setting', 1, 'Caption Show: true -> false<br/>', '1', '2023-06-25 20:35:11'),
(31, 'ui_customization_setting', 1, 'Box Container: false -> true<br/>', '1', '2023-06-25 20:35:12'),
(32, 'ui_customization_setting', 1, 'RTL Layout: false -> true<br/>', '1', '2023-06-25 20:36:18'),
(33, 'ui_customization_setting', 1, 'RTL Layout: true -> false<br/>', '1', '2023-06-25 20:36:25'),
(34, 'ui_customization_setting', 1, 'Preset Theme: preset-5 -> preset-9<br/>', '1', '2023-06-25 20:36:26'),
(35, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-06-25 20:36:27'),
(36, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-06-25 20:40:05'),
(37, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-25 20:40:06'),
(38, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-25 20:40:10'),
(39, 'ui_customization_setting', 1, 'Preset Theme: preset-9 -> preset-10<br/>', '1', '2023-06-25 20:41:56'),
(40, 'ui_customization_setting', 1, 'Preset Theme: preset-10 -> preset-5<br/>', '1', '2023-06-25 20:41:57'),
(41, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-25 20:42:01'),
(42, 'users', 1, 'OTP: 75okAGo5Rm52Ud0lxU4XuLolYef4r6%2F95MFiuYNjV7s%3D -> kJSx1OyXpxh%2B%2FnpA%2BsEiI4lhXuZW61aflsDKwKlyNjY%3D<br/>OTP Expiry Date: 2023-06-25 19:05:29 -> 2023-06-26 08:53:00<br/>', '1', '2023-06-26 08:48:00'),
(43, 'users', 1, 'Remember Token: f8232c8830a8a9c8bb55378efb62e351 -> 5dfdbd03f3ac82bd990dc73cca634b11<br/>', '1', '2023-06-26 08:49:18'),
(44, 'users', 1, 'Last Connection Date: 2023-06-25 19:01:03 -> 2023-06-26 08:49:18<br/>', '1', '2023-06-26 08:49:18'),
(45, 'ui_customization_setting', 1, 'Preset Theme: preset-5 -> preset-1<br/>', '1', '2023-06-26 08:49:35'),
(46, 'ui_customization_setting', 1, 'Preset Theme: preset-1 -> preset-10<br/>', '1', '2023-06-26 08:49:40'),
(47, 'ui_customization_setting', 1, 'Preset Theme: preset-10 -> preset-9<br/>', '1', '2023-06-26 08:49:41'),
(48, 'ui_customization_setting', 1, 'Preset Theme: preset-9 -> preset-6<br/>', '1', '2023-06-26 08:49:47'),
(49, 'ui_customization_setting', 1, 'Preset Theme: preset-6 -> preset-1<br/>', '1', '2023-06-26 08:49:48'),
(50, 'ui_customization_setting', 1, 'Preset Theme: preset-1 -> preset-7<br/>', '1', '2023-06-26 08:49:50'),
(51, 'ui_customization_setting', 1, 'Preset Theme: preset-7 -> preset-6<br/>', '1', '2023-06-26 08:49:53'),
(52, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-26 08:50:10'),
(53, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-06-26 08:50:23'),
(54, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-06-26 08:50:24'),
(55, 'ui_customization_setting', 1, 'RTL Layout: false -> true<br/>', '1', '2023-06-26 08:50:46'),
(56, 'ui_customization_setting', 1, 'RTL Layout: true -> false<br/>', '1', '2023-06-26 08:50:48'),
(57, 'users', 1, '2-Factor Authentication: 1 -> 0<br/>', '1', '2023-06-26 09:29:11'),
(58, 'users', 1, 'Receive Notification: 1 -> 0<br/>', '1', '2023-06-26 09:30:07'),
(59, 'users', 1, 'Receive Notification: 0 -> 1<br/>', '1', '2023-06-26 09:30:12'),
(60, 'users', 1, '2-Factor Authentication: 0 -> 1<br/>', '1', '2023-06-26 09:30:12'),
(61, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-26 09:31:24'),
(62, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-26 09:31:25'),
(63, 'users', 1, 'Receive Notification: 1 -> 0<br/>', '1', '2023-06-26 09:31:27'),
(64, 'users', 1, 'Receive Notification: 0 -> 1<br/>', '1', '2023-06-26 09:31:32'),
(65, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-26 09:31:46'),
(66, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-26 10:35:47'),
(67, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-26 10:35:51'),
(68, 'users', 1, 'Receive Notification: 1 -> 0<br/>', '1', '2023-06-26 10:35:53'),
(69, 'users', 1, '2-Factor Authentication: 1 -> 0<br/>', '1', '2023-06-26 10:35:58'),
(70, 'users', 1, 'Receive Notification: 0 -> 1<br/>', '1', '2023-06-26 10:36:02'),
(71, 'users', 1, '2-Factor Authentication: 0 -> 1<br/>', '1', '2023-06-26 10:36:02'),
(72, 'users', 1, 'OTP: kJSx1OyXpxh%2B%2FnpA%2BsEiI4lhXuZW61aflsDKwKlyNjY%3D -> %2FotseVtIu5cZAdX20cW8lVp1xntHkXJ7FnrbkwGQZ9Y%3D<br/>OTP Expiry Date: 2023-06-26 08:53:00 -> 2023-06-26 10:56:28<br/>', '1', '2023-06-26 10:51:28'),
(73, 'users', 1, 'Remember Token: 5dfdbd03f3ac82bd990dc73cca634b11 -> bbac5da5b473c8b99d2f5db413e15a0b<br/>', '1', '2023-06-26 10:51:40'),
(74, 'users', 1, 'Last Connection Date: 2023-06-26 08:49:18 -> 2023-06-26 10:51:40<br/>', '1', '2023-06-26 10:51:40'),
(75, 'users', 1, 'OTP: %2FotseVtIu5cZAdX20cW8lVp1xntHkXJ7FnrbkwGQZ9Y%3D -> UhmDpAkVgnwdy%2BDDdJhnO4xu7X5S4aHpU2uxcgdUynw%3D<br/>OTP Expiry Date: 2023-06-26 10:56:28 -> 2023-06-26 11:00:29<br/>', '1', '2023-06-26 10:55:29'),
(76, 'users', 1, 'Remember Token: bbac5da5b473c8b99d2f5db413e15a0b -> 8dd2bf3b9bdc3137bc8cd6ace846f6dc<br/>', '1', '2023-06-26 10:57:58'),
(77, 'users', 1, 'Last Connection Date: 2023-06-26 10:51:40 -> 2023-06-26 10:57:58<br/>', '1', '2023-06-26 10:57:58'),
(78, 'users', 1, 'Password Expiry Date: 2023-12-30 -> 2023-12-26<br/>', '1', '2023-06-26 11:14:15'),
(79, 'users', 1, 'Last Password Change: 2023-06-26 11:14:15 -> 2023-06-26 11:20:09<br/>', '1', '2023-06-26 11:20:09'),
(80, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-26 11:20:43'),
(81, 'users', 1, 'Last Password Change: 2023-06-26 11:20:09 -> 2023-06-26 11:21:00<br/>', '1', '2023-06-26 11:21:00'),
(82, 'users', 1, 'OTP: UhmDpAkVgnwdy%2BDDdJhnO4xu7X5S4aHpU2uxcgdUynw%3D -> 9d7ou1%2FgH%2F6gPc4uVzF5vmYGLQT%2BCyzFUJSm50gflzc%3D<br/>OTP Expiry Date: 2023-06-26 11:00:29 -> 2023-06-26 11:26:22<br/>Remember Me: 1 -> 0<br/>', '1', '2023-06-26 11:21:22'),
(83, 'users', 1, 'Last Connection Date: 2023-06-26 10:57:58 -> 2023-06-26 11:21:42<br/>', '1', '2023-06-26 11:21:42'),
(84, 'ui_customization_setting', 1, 'Caption Show: false -> true<br/>', '1', '2023-06-26 11:27:32'),
(85, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-26 11:27:36'),
(86, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-26 11:27:41'),
(87, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-26 11:27:43'),
(88, 'ui_customization_setting', 1, 'Preset Theme: preset-6 -> preset-5<br/>', '1', '2023-06-26 11:29:01'),
(89, 'ui_customization_setting', 1, 'Preset Theme: preset-5 -> preset-3<br/>', '1', '2023-06-26 11:29:03'),
(90, 'ui_customization_setting', 1, 'Preset Theme: preset-3 -> preset-1<br/>', '1', '2023-06-26 11:29:04'),
(91, 'ui_customization_setting', 1, 'Preset Theme: preset-1 -> preset-9<br/>', '1', '2023-06-26 11:29:06'),
(92, 'ui_customization_setting', 1, 'Box Container: true -> false<br/>', '1', '2023-06-26 15:48:00'),
(93, 'ui_customization_setting', 1, 'Box Container: false -> true<br/>', '1', '2023-06-26 15:48:01'),
(94, 'ui_customization_setting', 1, 'Box Container: true -> false<br/>', '1', '2023-06-26 15:48:02'),
(95, 'ui_customization_setting', 1, 'Box Container: false -> true<br/>', '1', '2023-06-26 15:48:04'),
(96, 'ui_customization_setting', 1, 'Box Container: true -> false<br/>', '1', '2023-06-26 15:48:09'),
(97, 'users', 1, 'OTP: 9d7ou1%2FgH%2F6gPc4uVzF5vmYGLQT%2BCyzFUJSm50gflzc%3D -> VsRYccMvvZqSm4CAssIXuL%2F7%2BLS03fcIgJ6U%2Fj%2BcCGo%3D<br/>OTP Expiry Date: 2023-06-26 11:26:22 -> 2023-06-27 09:44:50<br/>Remember Me: 0 -> 1<br/>', '1', '2023-06-27 09:39:50'),
(98, 'users', 1, 'Remember Token: 8dd2bf3b9bdc3137bc8cd6ace846f6dc -> 2c300c9bb4332919325314dc9de9351c<br/>', '1', '2023-06-27 09:40:23'),
(99, 'users', 1, 'Last Connection Date: 2023-06-26 11:21:42 -> 2023-06-27 09:40:23<br/>', '1', '2023-06-27 09:40:23'),
(100, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-06-27 13:27:27'),
(101, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:27:27 -> 2023-06-27 13:27:29<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-06-27 13:27:29'),
(102, 'users', 1, 'Failed Login Attempts: 2 -> 0<br/>', '1', '2023-06-27 13:31:16'),
(103, 'users', 1, 'OTP: VsRYccMvvZqSm4CAssIXuL%2F7%2BLS03fcIgJ6U%2Fj%2BcCGo%3D -> CXUxrJDCsxX1LOkzJQSZXK3s7tJvhEdGt9E1C4jQRyo%3D<br/>OTP Expiry Date: 2023-06-27 09:44:50 -> 2023-06-27 13:36:16<br/>Remember Me: 1 -> 0<br/>', '1', '2023-06-27 13:31:16'),
(104, 'users', 1, 'Last Connection Date: 2023-06-27 09:40:23 -> 2023-06-27 13:31:50<br/>', '1', '2023-06-27 13:31:50'),
(105, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-06-27 13:39:40'),
(106, 'users', 1, 'Failed Login Attempts: 1 -> 2<br/>', '1', '2023-06-27 13:39:40'),
(107, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:40 -> 2023-06-27 13:39:41<br/>Failed Login Attempts: 2 -> 3<br/>', '1', '2023-06-27 13:39:41'),
(108, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:41 -> 2023-06-27 13:39:42<br/>Failed Login Attempts: 3 -> 4<br/>', '1', '2023-06-27 13:39:42'),
(109, 'users', 1, 'Failed Login Attempts: 4 -> 5<br/>', '1', '2023-06-27 13:39:42'),
(110, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:42 -> 2023-06-27 13:39:43<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-06-27 13:39:43'),
(111, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 6<br/>', '1', '2023-06-27 13:39:43'),
(112, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:43 -> 2023-06-27 13:39:46<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-06-27 13:39:46'),
(113, 'users', 1, 'Account Lock Duration: 6 -> 13<br/>', '1', '2023-06-27 13:39:46'),
(114, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:46 -> 2023-06-27 13:39:47<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-06-27 13:39:47'),
(115, 'users', 1, 'Account Lock Duration: 13 -> 21<br/>', '1', '2023-06-27 13:39:47'),
(116, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:47 -> 2023-06-27 13:39:50<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-06-27 13:39:50'),
(117, 'users', 1, 'Account Lock Duration: 21 -> 30<br/>', '1', '2023-06-27 13:39:50'),
(118, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:50 -> 2023-06-27 13:39:51<br/>Failed Login Attempts: 9 -> 10<br/>', '1', '2023-06-27 13:39:51'),
(119, 'users', 1, 'Account Lock Duration: 30 -> 40<br/>', '1', '2023-06-27 13:39:51'),
(120, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:51 -> 2023-06-27 13:39:52<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-06-27 13:39:52'),
(121, 'users', 1, 'Account Lock Duration: 40 -> 51<br/>', '1', '2023-06-27 13:39:52'),
(122, 'users', 1, 'Failed Login Attempts: 11 -> 12<br/>', '1', '2023-06-27 13:39:52'),
(123, 'users', 1, 'Account Lock Duration: 51 -> 63<br/>', '1', '2023-06-27 13:39:52'),
(124, 'users', 1, 'Failed Login Attempts: 12 -> 13<br/>', '1', '2023-06-27 13:39:52'),
(125, 'users', 1, 'Account Lock Duration: 63 -> 76<br/>', '1', '2023-06-27 13:39:52'),
(126, 'users', 1, 'Failed Login Attempts: 13 -> 14<br/>', '1', '2023-06-27 13:39:52'),
(127, 'users', 1, 'Account Lock Duration: 76 -> 90<br/>', '1', '2023-06-27 13:39:52'),
(128, 'users', 1, 'Failed Login Attempts: 14 -> 15<br/>', '1', '2023-06-27 13:39:52'),
(129, 'users', 1, 'Account Lock Duration: 90 -> 105<br/>', '1', '2023-06-27 13:39:52'),
(130, 'users', 1, 'Failed Login Attempts: 15 -> 16<br/>', '1', '2023-06-27 13:39:52'),
(131, 'users', 1, 'Account Lock Duration: 105 -> 121<br/>', '1', '2023-06-27 13:39:52'),
(132, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:52 -> 2023-06-27 13:39:53<br/>Failed Login Attempts: 16 -> 17<br/>', '1', '2023-06-27 13:39:53'),
(133, 'users', 1, 'Account Lock Duration: 121 -> 138<br/>', '1', '2023-06-27 13:39:53'),
(134, 'users', 1, 'Failed Login Attempts: 17 -> 18<br/>', '1', '2023-06-27 13:39:53'),
(135, 'users', 1, 'Account Lock Duration: 138 -> 156<br/>', '1', '2023-06-27 13:39:53'),
(136, 'users', 1, 'Failed Login Attempts: 18 -> 19<br/>', '1', '2023-06-27 13:39:53'),
(137, 'users', 1, 'Account Lock Duration: 156 -> 175<br/>', '1', '2023-06-27 13:39:53'),
(138, 'users', 1, 'Failed Login Attempts: 19 -> 20<br/>', '1', '2023-06-27 13:39:53'),
(139, 'users', 1, 'Account Lock Duration: 175 -> 195<br/>', '1', '2023-06-27 13:39:53'),
(140, 'users', 1, 'Failed Login Attempts: 20 -> 21<br/>', '1', '2023-06-27 13:39:53'),
(141, 'users', 1, 'Account Lock Duration: 195 -> 216<br/>', '1', '2023-06-27 13:39:53'),
(142, 'users', 1, 'Failed Login Attempts: 21 -> 22<br/>', '1', '2023-06-27 13:39:53'),
(143, 'users', 1, 'Account Lock Duration: 216 -> 238<br/>', '1', '2023-06-27 13:39:53'),
(144, 'users', 1, 'Failed Login Attempts: 22 -> 23<br/>', '1', '2023-06-27 13:39:53'),
(145, 'users', 1, 'Account Lock Duration: 238 -> 261<br/>', '1', '2023-06-27 13:39:53'),
(146, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:53 -> 2023-06-27 13:39:54<br/>Failed Login Attempts: 23 -> 24<br/>', '1', '2023-06-27 13:39:54'),
(147, 'users', 1, 'Account Lock Duration: 261 -> 285<br/>', '1', '2023-06-27 13:39:54'),
(148, 'users', 1, 'Failed Login Attempts: 24 -> 25<br/>', '1', '2023-06-27 13:39:54'),
(149, 'users', 1, 'Account Lock Duration: 285 -> 310<br/>', '1', '2023-06-27 13:39:54'),
(150, 'users', 1, 'Failed Login Attempts: 25 -> 26<br/>', '1', '2023-06-27 13:39:54'),
(151, 'users', 1, 'Account Lock Duration: 310 -> 336<br/>', '1', '2023-06-27 13:39:54'),
(152, 'users', 1, 'Failed Login Attempts: 26 -> 27<br/>', '1', '2023-06-27 13:39:54'),
(153, 'users', 1, 'Account Lock Duration: 336 -> 363<br/>', '1', '2023-06-27 13:39:54'),
(154, 'users', 1, 'Failed Login Attempts: 27 -> 28<br/>', '1', '2023-06-27 13:39:54'),
(155, 'users', 1, 'Account Lock Duration: 363 -> 391<br/>', '1', '2023-06-27 13:39:54'),
(156, 'users', 1, 'Failed Login Attempts: 28 -> 29<br/>', '1', '2023-06-27 13:39:54'),
(157, 'users', 1, 'Account Lock Duration: 391 -> 420<br/>', '1', '2023-06-27 13:39:54'),
(158, 'users', 1, 'Failed Login Attempts: 29 -> 30<br/>', '1', '2023-06-27 13:39:54'),
(159, 'users', 1, 'Account Lock Duration: 420 -> 450<br/>', '1', '2023-06-27 13:39:54'),
(160, 'users', 1, 'Failed Login Attempts: 30 -> 31<br/>', '1', '2023-06-27 13:39:54'),
(161, 'users', 1, 'Account Lock Duration: 450 -> 481<br/>', '1', '2023-06-27 13:39:54'),
(162, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:54 -> 2023-06-27 13:39:55<br/>Failed Login Attempts: 31 -> 32<br/>', '1', '2023-06-27 13:39:55'),
(163, 'users', 1, 'Account Lock Duration: 481 -> 513<br/>', '1', '2023-06-27 13:39:55'),
(164, 'users', 1, 'Failed Login Attempts: 32 -> 33<br/>', '1', '2023-06-27 13:39:55'),
(165, 'users', 1, 'Account Lock Duration: 513 -> 546<br/>', '1', '2023-06-27 13:39:55'),
(166, 'users', 1, 'Failed Login Attempts: 33 -> 34<br/>', '1', '2023-06-27 13:39:55'),
(167, 'users', 1, 'Account Lock Duration: 546 -> 580<br/>', '1', '2023-06-27 13:39:55'),
(168, 'users', 1, 'Failed Login Attempts: 34 -> 35<br/>', '1', '2023-06-27 13:39:55'),
(169, 'users', 1, 'Account Lock Duration: 580 -> 615<br/>', '1', '2023-06-27 13:39:55'),
(170, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:39:55 -> 2023-06-27 13:43:06<br/>Failed Login Attempts: 35 -> 36<br/>', '1', '2023-06-27 13:43:06'),
(171, 'users', 1, 'Account Lock Duration: 615 -> 2147483647<br/>', '1', '2023-06-27 13:43:06'),
(172, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:43:06 -> 2023-06-27 13:43:07<br/>Failed Login Attempts: 36 -> 37<br/>', '1', '2023-06-27 13:43:07'),
(173, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:43:07 -> 2023-06-27 13:43:08<br/>Failed Login Attempts: 37 -> 38<br/>', '1', '2023-06-27 13:43:08'),
(174, 'users', 1, 'Failed Login Attempts: 38 -> 0<br/>', '1', '2023-06-27 13:43:37'),
(175, 'users', 1, 'Is Locked: 1 -> 0<br/>', '1', '2023-06-27 13:43:42'),
(176, 'users', 1, 'Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-06-27 13:44:01'),
(177, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:43:08 -> 2023-06-27 13:44:20<br/>Failed Login Attempts: 0 -> 1<br/>', '1', '2023-06-27 13:44:20'),
(178, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:20 -> 2023-06-27 13:44:21<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-06-27 13:44:21'),
(179, 'users', 1, 'Failed Login Attempts: 2 -> 3<br/>', '1', '2023-06-27 13:44:21'),
(180, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:21 -> 2023-06-27 13:44:22<br/>Failed Login Attempts: 3 -> 4<br/>', '1', '2023-06-27 13:44:22'),
(181, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:22 -> 2023-06-27 13:44:23<br/>Failed Login Attempts: 4 -> 5<br/>', '1', '2023-06-27 13:44:23'),
(182, 'users', 1, 'Failed Login Attempts: 5 -> 6<br/>', '1', '2023-06-27 13:44:23'),
(183, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-06-27 13:44:23'),
(184, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:23 -> 2023-06-27 13:44:26<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-06-27 13:44:26'),
(185, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-06-27 13:44:26'),
(186, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:26 -> 2023-06-27 13:44:27<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-06-27 13:44:27'),
(187, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-06-27 13:44:27'),
(188, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:27 -> 2023-06-27 13:44:28<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-06-27 13:44:28'),
(189, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-06-27 13:44:28'),
(190, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:28 -> 2023-06-27 13:44:31<br/>Failed Login Attempts: 9 -> 10<br/>', '1', '2023-06-27 13:44:31'),
(191, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-06-27 13:44:31'),
(192, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:31 -> 2023-06-27 13:44:32<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-06-27 13:44:32'),
(193, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-06-27 13:44:32'),
(194, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:32 -> 2023-06-27 13:44:33<br/>Failed Login Attempts: 11 -> 12<br/>', '1', '2023-06-27 13:44:33'),
(195, 'users', 1, 'Account Lock Duration: 320 -> 640<br/>', '1', '2023-06-27 13:44:33'),
(196, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:44:33 -> 2023-06-27 13:44:35<br/>Failed Login Attempts: 12 -> 13<br/>', '1', '2023-06-27 13:44:35'),
(197, 'users', 1, 'Account Lock Duration: 640 -> 1280<br/>', '1', '2023-06-27 13:44:35'),
(198, 'users', 1, 'Is Locked: 1 -> 0<br/>', '1', '2023-06-27 13:45:19'),
(199, 'users', 1, 'Failed Login Attempts: 13 -> 0<br/>', '1', '2023-06-27 13:45:21'),
(200, 'users', 1, 'Account Lock Duration: 1280 -> 0<br/>', '1', '2023-06-27 13:45:32'),
(201, 'users', 1, 'OTP: CXUxrJDCsxX1LOkzJQSZXK3s7tJvhEdGt9E1C4jQRyo%3D -> Xb9wJQO3U9wpSscvj99ZsQpIfZDfOqjlijzRE2EDDw0%3D<br/>OTP Expiry Date: 2023-06-27 13:36:16 -> 2023-06-27 13:50:45<br/>', '1', '2023-06-27 13:45:45'),
(202, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-06-27 13:46:01'),
(203, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:01 -> 2023-06-27 13:46:02<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-06-27 13:46:02'),
(204, 'users', 1, 'Failed Login Attempts: 2 -> 3<br/>', '1', '2023-06-27 13:46:02'),
(205, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:02 -> 2023-06-27 13:46:03<br/>Failed Login Attempts: 3 -> 4<br/>', '1', '2023-06-27 13:46:03'),
(206, 'users', 1, 'Failed Login Attempts: 4 -> 5<br/>', '1', '2023-06-27 13:46:03'),
(207, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:03 -> 2023-06-27 13:46:04<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-06-27 13:46:04'),
(208, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-06-27 13:46:04'),
(209, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:04 -> 2023-06-27 13:46:05<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-06-27 13:46:05'),
(210, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-06-27 13:46:05'),
(211, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:05 -> 2023-06-27 13:46:10<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-06-27 13:46:10'),
(212, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-06-27 13:46:10'),
(213, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:10 -> 2023-06-27 13:46:12<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-06-27 13:46:12'),
(214, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-06-27 13:46:12'),
(215, 'users', 1, 'Failed Login Attempts: 9 -> 10<br/>', '1', '2023-06-27 13:46:12'),
(216, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-06-27 13:46:13'),
(217, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:12 -> 2023-06-27 13:46:13<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-06-27 13:46:13'),
(218, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-06-27 13:46:13'),
(219, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:13 -> 2023-06-27 13:46:14<br/>Failed Login Attempts: 11 -> 12<br/>', '1', '2023-06-27 13:46:14'),
(220, 'users', 1, 'Account Lock Duration: 320 -> 640<br/>', '1', '2023-06-27 13:46:14'),
(221, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:14 -> 2023-06-27 13:46:15<br/>Failed Login Attempts: 12 -> 13<br/>', '1', '2023-06-27 13:46:15'),
(222, 'users', 1, 'Account Lock Duration: 640 -> 1280<br/>', '1', '2023-06-27 13:46:15'),
(223, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:15 -> 2023-06-27 13:46:16<br/>Failed Login Attempts: 13 -> 14<br/>', '1', '2023-06-27 13:46:16'),
(224, 'users', 1, 'Account Lock Duration: 1280 -> 2560<br/>', '1', '2023-06-27 13:46:16'),
(225, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:16 -> 2023-06-27 13:46:18<br/>Failed Login Attempts: 14 -> 15<br/>', '1', '2023-06-27 13:46:18'),
(226, 'users', 1, 'Account Lock Duration: 2560 -> 5120<br/>', '1', '2023-06-27 13:46:18'),
(227, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:18 -> 2023-06-27 13:46:19<br/>Failed Login Attempts: 15 -> 16<br/>', '1', '2023-06-27 13:46:19'),
(228, 'users', 1, 'Account Lock Duration: 5120 -> 10240<br/>', '1', '2023-06-27 13:46:19'),
(229, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:19 -> 2023-06-27 13:46:21<br/>Failed Login Attempts: 16 -> 17<br/>', '1', '2023-06-27 13:46:21'),
(230, 'users', 1, 'Account Lock Duration: 10240 -> 20480<br/>', '1', '2023-06-27 13:46:21'),
(231, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:21 -> 2023-06-27 13:46:23<br/>Failed Login Attempts: 17 -> 18<br/>', '1', '2023-06-27 13:46:23'),
(232, 'users', 1, 'Account Lock Duration: 20480 -> 40960<br/>', '1', '2023-06-27 13:46:23'),
(233, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:23 -> 2023-06-27 13:46:24<br/>Failed Login Attempts: 18 -> 19<br/>', '1', '2023-06-27 13:46:24'),
(234, 'users', 1, 'Account Lock Duration: 40960 -> 81920<br/>', '1', '2023-06-27 13:46:24'),
(235, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:24 -> 2023-06-27 13:46:26<br/>Failed Login Attempts: 19 -> 20<br/>', '1', '2023-06-27 13:46:26'),
(236, 'users', 1, 'Account Lock Duration: 81920 -> 163840<br/>', '1', '2023-06-27 13:46:26'),
(237, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:26 -> 2023-06-27 13:46:27<br/>Failed Login Attempts: 20 -> 21<br/>', '1', '2023-06-27 13:46:27'),
(238, 'users', 1, 'Account Lock Duration: 163840 -> 327680<br/>', '1', '2023-06-27 13:46:27'),
(239, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:46:27 -> 2023-06-27 13:49:06<br/>Failed Login Attempts: 21 -> 22<br/>', '1', '2023-06-27 13:49:06'),
(240, 'users', 1, 'Account Lock Duration: 327680 -> 655360<br/>', '1', '2023-06-27 13:49:06'),
(241, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:06 -> 2023-06-27 13:49:08<br/>Failed Login Attempts: 22 -> 23<br/>', '1', '2023-06-27 13:49:08'),
(242, 'users', 1, 'Account Lock Duration: 655360 -> 1310720<br/>', '1', '2023-06-27 13:49:08'),
(243, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:08 -> 2023-06-27 13:49:09<br/>Failed Login Attempts: 23 -> 24<br/>', '1', '2023-06-27 13:49:09'),
(244, 'users', 1, 'Account Lock Duration: 1310720 -> 2621440<br/>', '1', '2023-06-27 13:49:09'),
(245, 'users', 1, 'Account Lock Duration: 2621440 -> 0<br/>', '1', '2023-06-27 13:49:16'),
(246, 'users', 1, 'Is Locked: 1 -> 0<br/>', '1', '2023-06-27 13:49:21'),
(247, 'users', 1, 'Failed Login Attempts: 24 -> 0<br/>', '1', '2023-06-27 13:49:25'),
(248, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:09 -> 2023-06-27 13:49:27<br/>Failed Login Attempts: 0 -> 1<br/>', '1', '2023-06-27 13:49:27'),
(249, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:27 -> 2023-06-27 13:49:31<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-06-27 13:49:31'),
(250, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:31 -> 2023-06-27 13:49:32<br/>Failed Login Attempts: 2 -> 3<br/>', '1', '2023-06-27 13:49:32'),
(251, 'users', 1, 'Failed Login Attempts: 3 -> 4<br/>', '1', '2023-06-27 13:49:32'),
(252, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:32 -> 2023-06-27 13:49:33<br/>Failed Login Attempts: 4 -> 5<br/>', '1', '2023-06-27 13:49:33'),
(253, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:33 -> 2023-06-27 13:49:34<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-06-27 13:49:34'),
(254, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-06-27 13:49:34'),
(255, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:34 -> 2023-06-27 13:49:36<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-06-27 13:49:36'),
(256, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-06-27 13:49:36'),
(257, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:36 -> 2023-06-27 13:49:37<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-06-27 13:49:37'),
(258, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-06-27 13:49:37'),
(259, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:37 -> 2023-06-27 13:49:38<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-06-27 13:49:38'),
(260, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-06-27 13:49:38'),
(261, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:38 -> 2023-06-27 13:49:40<br/>Failed Login Attempts: 9 -> 10<br/>', '1', '2023-06-27 13:49:40'),
(262, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-06-27 13:49:40'),
(263, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:40 -> 2023-06-27 13:49:42<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-06-27 13:49:42'),
(264, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-06-27 13:49:42'),
(265, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:42 -> 2023-06-27 13:49:43<br/>Failed Login Attempts: 11 -> 12<br/>', '1', '2023-06-27 13:49:43'),
(266, 'users', 1, 'Account Lock Duration: 320 -> 640<br/>', '1', '2023-06-27 13:49:43'),
(267, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:43 -> 2023-06-27 13:49:44<br/>Failed Login Attempts: 12 -> 13<br/>', '1', '2023-06-27 13:49:44'),
(268, 'users', 1, 'Account Lock Duration: 640 -> 1280<br/>', '1', '2023-06-27 13:49:44'),
(269, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:44 -> 2023-06-27 13:49:45<br/>Failed Login Attempts: 13 -> 14<br/>', '1', '2023-06-27 13:49:45'),
(270, 'users', 1, 'Account Lock Duration: 1280 -> 2560<br/>', '1', '2023-06-27 13:49:45'),
(271, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:45 -> 2023-06-27 13:49:46<br/>Failed Login Attempts: 14 -> 15<br/>', '1', '2023-06-27 13:49:46'),
(272, 'users', 1, 'Account Lock Duration: 2560 -> 5120<br/>', '1', '2023-06-27 13:49:46'),
(273, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:46 -> 2023-06-27 13:49:50<br/>Failed Login Attempts: 15 -> 16<br/>', '1', '2023-06-27 13:49:50'),
(274, 'users', 1, 'Account Lock Duration: 5120 -> 10240<br/>', '1', '2023-06-27 13:49:50'),
(275, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:50 -> 2023-06-27 13:49:52<br/>Failed Login Attempts: 16 -> 17<br/>', '1', '2023-06-27 13:49:52'),
(276, 'users', 1, 'Account Lock Duration: 10240 -> 20480<br/>', '1', '2023-06-27 13:49:52'),
(277, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:52 -> 2023-06-27 13:49:54<br/>Failed Login Attempts: 17 -> 18<br/>', '1', '2023-06-27 13:49:54'),
(278, 'users', 1, 'Account Lock Duration: 20480 -> 40960<br/>', '1', '2023-06-27 13:49:54'),
(279, 'users', 1, 'Failed Login Attempts: 18 -> 19<br/>', '1', '2023-06-27 13:49:54'),
(280, 'users', 1, 'Account Lock Duration: 40960 -> 81920<br/>', '1', '2023-06-27 13:49:54'),
(281, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:54 -> 2023-06-27 13:49:55<br/>Failed Login Attempts: 19 -> 20<br/>', '1', '2023-06-27 13:49:55'),
(282, 'users', 1, 'Account Lock Duration: 81920 -> 163840<br/>', '1', '2023-06-27 13:49:55'),
(283, 'users', 1, 'Failed Login Attempts: 20 -> 21<br/>', '1', '2023-06-27 13:49:55'),
(284, 'users', 1, 'Account Lock Duration: 163840 -> 327680<br/>', '1', '2023-06-27 13:49:55'),
(285, 'users', 1, 'Failed Login Attempts: 21 -> 22<br/>', '1', '2023-06-27 13:49:55'),
(286, 'users', 1, 'Account Lock Duration: 327680 -> 655360<br/>', '1', '2023-06-27 13:49:55'),
(287, 'users', 1, 'Failed Login Attempts: 22 -> 23<br/>', '1', '2023-06-27 13:49:55'),
(288, 'users', 1, 'Account Lock Duration: 655360 -> 1310720<br/>', '1', '2023-06-27 13:49:55'),
(289, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:55 -> 2023-06-27 13:49:56<br/>Failed Login Attempts: 23 -> 24<br/>', '1', '2023-06-27 13:49:56'),
(290, 'users', 1, 'Account Lock Duration: 1310720 -> 2621440<br/>', '1', '2023-06-27 13:49:56'),
(291, 'users', 1, 'Failed Login Attempts: 24 -> 25<br/>', '1', '2023-06-27 13:49:56'),
(292, 'users', 1, 'Account Lock Duration: 2621440 -> 5242880<br/>', '1', '2023-06-27 13:49:56'),
(293, 'users', 1, 'Failed Login Attempts: 25 -> 26<br/>', '1', '2023-06-27 13:49:56'),
(294, 'users', 1, 'Account Lock Duration: 5242880 -> 10485760<br/>', '1', '2023-06-27 13:49:56'),
(295, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:49:56 -> 2023-06-27 13:50:47<br/>Failed Login Attempts: 26 -> 27<br/>', '1', '2023-06-27 13:50:47'),
(296, 'users', 1, 'Account Lock Duration: 10485760 -> 20971520<br/>', '1', '2023-06-27 13:50:47'),
(297, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:50:47 -> 2023-06-27 13:50:50<br/>Failed Login Attempts: 27 -> 28<br/>', '1', '2023-06-27 13:50:50'),
(298, 'users', 1, 'Account Lock Duration: 20971520 -> 41943040<br/>', '1', '2023-06-27 13:50:50'),
(299, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:50:50 -> 2023-06-27 13:50:51<br/>Failed Login Attempts: 28 -> 29<br/>', '1', '2023-06-27 13:50:51'),
(300, 'users', 1, 'Account Lock Duration: 41943040 -> 83886080<br/>', '1', '2023-06-27 13:50:51'),
(301, 'users', 1, 'Failed Login Attempts: 29 -> 30<br/>', '1', '2023-06-27 13:50:51'),
(302, 'users', 1, 'Account Lock Duration: 83886080 -> 167772160<br/>', '1', '2023-06-27 13:50:51'),
(303, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:50:51 -> 2023-06-27 13:50:52<br/>Failed Login Attempts: 30 -> 31<br/>', '1', '2023-06-27 13:50:52'),
(304, 'users', 1, 'Account Lock Duration: 167772160 -> 335544320<br/>', '1', '2023-06-27 13:50:52'),
(305, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:50:52 -> 2023-06-27 13:50:53<br/>Failed Login Attempts: 31 -> 32<br/>', '1', '2023-06-27 13:50:53'),
(306, 'users', 1, 'Account Lock Duration: 335544320 -> 671088640<br/>', '1', '2023-06-27 13:50:53'),
(307, 'users', 1, 'Failed Login Attempts: 32 -> 33<br/>', '1', '2023-06-27 13:50:53'),
(308, 'users', 1, 'Account Lock Duration: 671088640 -> 1342177280<br/>', '1', '2023-06-27 13:50:53'),
(309, 'users', 1, 'Failed Login Attempts: 33 -> 34<br/>', '1', '2023-06-27 13:50:53'),
(310, 'users', 1, 'Account Lock Duration: 1342177280 -> 2147483647<br/>', '1', '2023-06-27 13:50:53'),
(311, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:50:53 -> 2023-06-27 13:50:54<br/>Failed Login Attempts: 34 -> 35<br/>', '1', '2023-06-27 13:50:54'),
(312, 'users', 1, 'Failed Login Attempts: 35 -> 36<br/>', '1', '2023-06-27 13:50:54'),
(313, 'users', 1, 'Failed Login Attempts: 36 -> 37<br/>', '1', '2023-06-27 13:50:54'),
(314, 'users', 1, 'Failed Login Attempts: 37 -> 38<br/>', '1', '2023-06-27 13:50:54'),
(315, 'users', 1, 'Failed Login Attempts: 38 -> 39<br/>', '1', '2023-06-27 13:50:54'),
(316, 'users', 1, 'Is Locked: 1 -> 0<br/>', '1', '2023-06-27 13:50:59'),
(317, 'users', 1, 'Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-06-27 13:51:04'),
(318, 'users', 1, 'Failed Login Attempts: 39 -> 0<br/>', '1', '2023-06-27 13:51:13'),
(319, 'users', 1, 'OTP: Xb9wJQO3U9wpSscvj99ZsQpIfZDfOqjlijzRE2EDDw0%3D -> ymecq%2B%2FvOdQXLttC9hPFuTi3wZZ8goUar%2FJ4tPj2SfY%3D<br/>OTP Expiry Date: 2023-06-27 13:50:45 -> 2023-06-27 13:56:34<br/>', '1', '2023-06-27 13:51:34'),
(320, 'users', 1, 'Failed Login Attempts: 0 -> 1<br/>', '1', '2023-06-27 13:51:51'),
(321, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:51:51 -> 2023-06-27 13:51:52<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-06-27 13:51:52'),
(322, 'users', 1, 'Failed Login Attempts: 2 -> 3<br/>', '1', '2023-06-27 13:51:52'),
(323, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:51:52 -> 2023-06-27 13:51:53<br/>Failed Login Attempts: 3 -> 4<br/>', '1', '2023-06-27 13:51:53'),
(324, 'users', 1, 'Failed Login Attempts: 4 -> 5<br/>', '1', '2023-06-27 13:51:53'),
(325, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:51:53 -> 2023-06-27 13:51:54<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-06-27 13:51:54'),
(326, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-06-27 13:51:54'),
(327, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:51:54 -> 2023-06-27 13:51:56<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-06-27 13:51:56'),
(328, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-06-27 13:51:56'),
(329, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:51:56 -> 2023-06-27 13:51:58<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-06-27 13:51:58'),
(330, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-06-27 13:51:58'),
(331, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:51:58 -> 2023-06-27 13:51:59<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-06-27 13:51:59'),
(332, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-06-27 13:51:59'),
(333, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:51:59 -> 2023-06-27 13:52:00<br/>Failed Login Attempts: 9 -> 10<br/>', '1', '2023-06-27 13:52:00'),
(334, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-06-27 13:52:00'),
(335, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:00 -> 2023-06-27 13:52:01<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-06-27 13:52:01'),
(336, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-06-27 13:52:01'),
(337, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:01 -> 2023-06-27 13:52:02<br/>Failed Login Attempts: 11 -> 12<br/>', '1', '2023-06-27 13:52:02'),
(338, 'users', 1, 'Account Lock Duration: 320 -> 640<br/>', '1', '2023-06-27 13:52:02'),
(339, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:02 -> 2023-06-27 13:52:03<br/>Failed Login Attempts: 12 -> 13<br/>', '1', '2023-06-27 13:52:03'),
(340, 'users', 1, 'Account Lock Duration: 640 -> 1280<br/>', '1', '2023-06-27 13:52:03'),
(341, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:03 -> 2023-06-27 13:52:04<br/>Failed Login Attempts: 13 -> 14<br/>', '1', '2023-06-27 13:52:04'),
(342, 'users', 1, 'Account Lock Duration: 1280 -> 2560<br/>', '1', '2023-06-27 13:52:04'),
(343, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:04 -> 2023-06-27 13:52:06<br/>Failed Login Attempts: 14 -> 15<br/>', '1', '2023-06-27 13:52:06'),
(344, 'users', 1, 'Account Lock Duration: 2560 -> 5120<br/>', '1', '2023-06-27 13:52:06'),
(345, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:06 -> 2023-06-27 13:52:07<br/>Failed Login Attempts: 15 -> 16<br/>', '1', '2023-06-27 13:52:07'),
(346, 'users', 1, 'Account Lock Duration: 5120 -> 10240<br/>', '1', '2023-06-27 13:52:07'),
(347, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:07 -> 2023-06-27 13:52:08<br/>Failed Login Attempts: 16 -> 17<br/>', '1', '2023-06-27 13:52:08'),
(348, 'users', 1, 'Account Lock Duration: 10240 -> 20480<br/>', '1', '2023-06-27 13:52:08'),
(349, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:08 -> 2023-06-27 13:52:10<br/>Failed Login Attempts: 17 -> 18<br/>', '1', '2023-06-27 13:52:10'),
(350, 'users', 1, 'Account Lock Duration: 20480 -> 40960<br/>', '1', '2023-06-27 13:52:10'),
(351, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:10 -> 2023-06-27 13:52:11<br/>Failed Login Attempts: 18 -> 19<br/>', '1', '2023-06-27 13:52:11'),
(352, 'users', 1, 'Account Lock Duration: 40960 -> 81920<br/>', '1', '2023-06-27 13:52:11'),
(353, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:11 -> 2023-06-27 13:52:13<br/>Failed Login Attempts: 19 -> 20<br/>', '1', '2023-06-27 13:52:13'),
(354, 'users', 1, 'Account Lock Duration: 81920 -> 163840<br/>', '1', '2023-06-27 13:52:13'),
(355, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:13 -> 2023-06-27 13:52:15<br/>Failed Login Attempts: 20 -> 21<br/>', '1', '2023-06-27 13:52:15'),
(356, 'users', 1, 'Account Lock Duration: 163840 -> 327680<br/>', '1', '2023-06-27 13:52:15'),
(357, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:15 -> 2023-06-27 13:52:17<br/>Failed Login Attempts: 21 -> 22<br/>', '1', '2023-06-27 13:52:17'),
(358, 'users', 1, 'Account Lock Duration: 327680 -> 655360<br/>', '1', '2023-06-27 13:52:17'),
(359, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:17 -> 2023-06-27 13:52:19<br/>Failed Login Attempts: 22 -> 23<br/>', '1', '2023-06-27 13:52:19'),
(360, 'users', 1, 'Account Lock Duration: 655360 -> 1310720<br/>', '1', '2023-06-27 13:52:19'),
(361, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:19 -> 2023-06-27 13:52:21<br/>Failed Login Attempts: 23 -> 24<br/>', '1', '2023-06-27 13:52:21'),
(362, 'users', 1, 'Account Lock Duration: 1310720 -> 2621440<br/>', '1', '2023-06-27 13:52:21'),
(363, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:21 -> 2023-06-27 13:52:22<br/>Failed Login Attempts: 24 -> 25<br/>', '1', '2023-06-27 13:52:22'),
(364, 'users', 1, 'Account Lock Duration: 2621440 -> 5242880<br/>', '1', '2023-06-27 13:52:22'),
(365, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:22 -> 2023-06-27 13:52:23<br/>Failed Login Attempts: 25 -> 26<br/>', '1', '2023-06-27 13:52:23'),
(366, 'users', 1, 'Account Lock Duration: 5242880 -> 10485760<br/>', '1', '2023-06-27 13:52:23'),
(367, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:23 -> 2023-06-27 13:52:24<br/>Failed Login Attempts: 26 -> 27<br/>', '1', '2023-06-27 13:52:24'),
(368, 'users', 1, 'Account Lock Duration: 10485760 -> 20971520<br/>', '1', '2023-06-27 13:52:24'),
(369, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:24 -> 2023-06-27 13:52:25<br/>Failed Login Attempts: 27 -> 28<br/>', '1', '2023-06-27 13:52:25'),
(370, 'users', 1, 'Account Lock Duration: 20971520 -> 41943040<br/>', '1', '2023-06-27 13:52:25'),
(371, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:25 -> 2023-06-27 13:52:26<br/>Failed Login Attempts: 28 -> 29<br/>', '1', '2023-06-27 13:52:26'),
(372, 'users', 1, 'Account Lock Duration: 41943040 -> 83886080<br/>', '1', '2023-06-27 13:52:26'),
(373, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:26 -> 2023-06-27 13:52:27<br/>Failed Login Attempts: 29 -> 30<br/>', '1', '2023-06-27 13:52:27'),
(374, 'users', 1, 'Account Lock Duration: 83886080 -> 167772160<br/>', '1', '2023-06-27 13:52:27'),
(375, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:27 -> 2023-06-27 13:52:28<br/>Failed Login Attempts: 30 -> 31<br/>', '1', '2023-06-27 13:52:28'),
(376, 'users', 1, 'Account Lock Duration: 167772160 -> 335544320<br/>', '1', '2023-06-27 13:52:28'),
(377, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:28 -> 2023-06-27 13:52:29<br/>Failed Login Attempts: 31 -> 32<br/>', '1', '2023-06-27 13:52:29'),
(378, 'users', 1, 'Account Lock Duration: 335544320 -> 671088640<br/>', '1', '2023-06-27 13:52:29'),
(379, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:29 -> 2023-06-27 13:52:30<br/>Failed Login Attempts: 32 -> 33<br/>', '1', '2023-06-27 13:52:30'),
(380, 'users', 1, 'Account Lock Duration: 671088640 -> 1342177280<br/>', '1', '2023-06-27 13:52:30'),
(381, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:30 -> 2023-06-27 13:52:32<br/>Failed Login Attempts: 33 -> 34<br/>', '1', '2023-06-27 13:52:32'),
(382, 'users', 1, 'Account Lock Duration: 1342177280 -> 2147483647<br/>', '1', '2023-06-27 13:52:32'),
(383, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:32 -> 2023-06-27 13:52:33<br/>Failed Login Attempts: 34 -> 35<br/>', '1', '2023-06-27 13:52:33'),
(384, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:33 -> 2023-06-27 13:52:34<br/>Failed Login Attempts: 35 -> 36<br/>', '1', '2023-06-27 13:52:34'),
(385, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:34 -> 2023-06-27 13:52:41<br/>Failed Login Attempts: 36 -> 37<br/>', '1', '2023-06-27 13:52:41'),
(386, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:41 -> 2023-06-27 13:52:44<br/>Failed Login Attempts: 37 -> 38<br/>', '1', '2023-06-27 13:52:44'),
(387, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:52:44 -> 2023-06-27 13:58:34<br/>Failed Login Attempts: 38 -> 39<br/>', '1', '2023-06-27 13:58:34'),
(388, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:58:34 -> 2023-06-27 13:58:38<br/>Failed Login Attempts: 39 -> 40<br/>', '1', '2023-06-27 13:58:38'),
(389, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:58:38 -> 2023-06-27 13:58:40<br/>Failed Login Attempts: 40 -> 41<br/>', '1', '2023-06-27 13:58:40'),
(390, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:58:40 -> 2023-06-27 13:58:43<br/>Failed Login Attempts: 41 -> 42<br/>', '1', '2023-06-27 13:58:43'),
(391, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 13:58:43 -> 2023-06-27 14:00:05<br/>Failed Login Attempts: 42 -> 43<br/>', '1', '2023-06-27 14:00:05'),
(392, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:05 -> 2023-06-27 14:00:07<br/>Failed Login Attempts: 43 -> 44<br/>', '1', '2023-06-27 14:00:07'),
(393, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:07 -> 2023-06-27 14:00:08<br/>Failed Login Attempts: 44 -> 45<br/>', '1', '2023-06-27 14:00:08'),
(394, 'users', 1, 'Failed Login Attempts: 45 -> 46<br/>', '1', '2023-06-27 14:00:08'),
(395, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:08 -> 2023-06-27 14:00:09<br/>Failed Login Attempts: 46 -> 47<br/>', '1', '2023-06-27 14:00:09'),
(396, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:09 -> 2023-06-27 14:00:10<br/>Failed Login Attempts: 47 -> 48<br/>', '1', '2023-06-27 14:00:10'),
(397, 'users', 1, 'Is Locked: 1 -> 0<br/>', '1', '2023-06-27 14:00:16'),
(398, 'users', 1, 'Failed Login Attempts: 48 -> 0<br/>', '1', '2023-06-27 14:00:19'),
(399, 'users', 1, 'Account Lock Duration: 2147483647 -> 0<br/>', '1', '2023-06-27 14:00:23'),
(400, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:10 -> 2023-06-27 14:00:26<br/>Failed Login Attempts: 0 -> 1<br/>', '1', '2023-06-27 14:00:26'),
(401, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:26 -> 2023-06-27 14:00:28<br/>Failed Login Attempts: 1 -> 2<br/>', '1', '2023-06-27 14:00:28'),
(402, 'users', 1, 'Failed Login Attempts: 2 -> 3<br/>', '1', '2023-06-27 14:00:28'),
(403, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:28 -> 2023-06-27 14:00:29<br/>Failed Login Attempts: 3 -> 4<br/>', '1', '2023-06-27 14:00:29'),
(404, 'users', 1, 'Failed Login Attempts: 4 -> 5<br/>', '1', '2023-06-27 14:00:29'),
(405, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:29 -> 2023-06-27 14:00:32<br/>Failed Login Attempts: 5 -> 6<br/>', '1', '2023-06-27 14:00:32'),
(406, 'users', 1, 'Is Locked: 0 -> 1<br/>Account Lock Duration: 0 -> 10<br/>', '1', '2023-06-27 14:00:32'),
(407, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:32 -> 2023-06-27 14:00:34<br/>Failed Login Attempts: 6 -> 7<br/>', '1', '2023-06-27 14:00:34'),
(408, 'users', 1, 'Account Lock Duration: 10 -> 20<br/>', '1', '2023-06-27 14:00:34'),
(409, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:34 -> 2023-06-27 14:00:35<br/>Failed Login Attempts: 7 -> 8<br/>', '1', '2023-06-27 14:00:35'),
(410, 'users', 1, 'Account Lock Duration: 20 -> 40<br/>', '1', '2023-06-27 14:00:35'),
(411, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:35 -> 2023-06-27 14:00:36<br/>Failed Login Attempts: 8 -> 9<br/>', '1', '2023-06-27 14:00:36'),
(412, 'users', 1, 'Account Lock Duration: 40 -> 80<br/>', '1', '2023-06-27 14:00:36'),
(413, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:36 -> 2023-06-27 14:00:37<br/>Failed Login Attempts: 9 -> 10<br/>', '1', '2023-06-27 14:00:37'),
(414, 'users', 1, 'Account Lock Duration: 80 -> 160<br/>', '1', '2023-06-27 14:00:37'),
(415, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:37 -> 2023-06-27 14:00:38<br/>Failed Login Attempts: 10 -> 11<br/>', '1', '2023-06-27 14:00:38'),
(416, 'users', 1, 'Account Lock Duration: 160 -> 320<br/>', '1', '2023-06-27 14:00:38'),
(417, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:38 -> 2023-06-27 14:00:39<br/>Failed Login Attempts: 11 -> 12<br/>', '1', '2023-06-27 14:00:39'),
(418, 'users', 1, 'Account Lock Duration: 320 -> 640<br/>', '1', '2023-06-27 14:00:39'),
(419, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:39 -> 2023-06-27 14:00:40<br/>Failed Login Attempts: 12 -> 13<br/>', '1', '2023-06-27 14:00:40'),
(420, 'users', 1, 'Account Lock Duration: 640 -> 1280<br/>', '1', '2023-06-27 14:00:40'),
(421, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:40 -> 2023-06-27 14:00:41<br/>Failed Login Attempts: 13 -> 14<br/>', '1', '2023-06-27 14:00:41'),
(422, 'users', 1, 'Account Lock Duration: 1280 -> 2560<br/>', '1', '2023-06-27 14:00:41'),
(423, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:41 -> 2023-06-27 14:00:43<br/>Failed Login Attempts: 14 -> 15<br/>', '1', '2023-06-27 14:00:43'),
(424, 'users', 1, 'Account Lock Duration: 2560 -> 5120<br/>', '1', '2023-06-27 14:00:43'),
(425, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:43 -> 2023-06-27 14:00:44<br/>Failed Login Attempts: 15 -> 16<br/>', '1', '2023-06-27 14:00:44'),
(426, 'users', 1, 'Account Lock Duration: 5120 -> 10240<br/>', '1', '2023-06-27 14:00:44'),
(427, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:44 -> 2023-06-27 14:00:46<br/>Failed Login Attempts: 16 -> 17<br/>', '1', '2023-06-27 14:00:46');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(428, 'users', 1, 'Account Lock Duration: 10240 -> 20480<br/>', '1', '2023-06-27 14:00:46'),
(429, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:46 -> 2023-06-27 14:00:47<br/>Failed Login Attempts: 17 -> 18<br/>', '1', '2023-06-27 14:00:47'),
(430, 'users', 1, 'Account Lock Duration: 20480 -> 40960<br/>', '1', '2023-06-27 14:00:47'),
(431, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:47 -> 2023-06-27 14:00:48<br/>Failed Login Attempts: 18 -> 19<br/>', '1', '2023-06-27 14:00:48'),
(432, 'users', 1, 'Account Lock Duration: 40960 -> 81920<br/>', '1', '2023-06-27 14:00:48'),
(433, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:48 -> 2023-06-27 14:00:49<br/>Failed Login Attempts: 19 -> 20<br/>', '1', '2023-06-27 14:00:49'),
(434, 'users', 1, 'Account Lock Duration: 81920 -> 163840<br/>', '1', '2023-06-27 14:00:49'),
(435, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:49 -> 2023-06-27 14:00:50<br/>Failed Login Attempts: 20 -> 21<br/>', '1', '2023-06-27 14:00:50'),
(436, 'users', 1, 'Account Lock Duration: 163840 -> 327680<br/>', '1', '2023-06-27 14:00:50'),
(437, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:50 -> 2023-06-27 14:00:52<br/>Failed Login Attempts: 21 -> 22<br/>', '1', '2023-06-27 14:00:52'),
(438, 'users', 1, 'Account Lock Duration: 327680 -> 655360<br/>', '1', '2023-06-27 14:00:52'),
(439, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:52 -> 2023-06-27 14:00:53<br/>Failed Login Attempts: 22 -> 23<br/>', '1', '2023-06-27 14:00:53'),
(440, 'users', 1, 'Account Lock Duration: 655360 -> 1310720<br/>', '1', '2023-06-27 14:00:53'),
(441, 'users', 1, 'Failed Login Attempts: 23 -> 24<br/>', '1', '2023-06-27 14:00:53'),
(442, 'users', 1, 'Account Lock Duration: 1310720 -> 2621440<br/>', '1', '2023-06-27 14:00:53'),
(443, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:53 -> 2023-06-27 14:00:54<br/>Failed Login Attempts: 24 -> 25<br/>', '1', '2023-06-27 14:00:54'),
(444, 'users', 1, 'Account Lock Duration: 2621440 -> 5242880<br/>', '1', '2023-06-27 14:00:54'),
(445, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:54 -> 2023-06-27 14:00:56<br/>Failed Login Attempts: 25 -> 26<br/>', '1', '2023-06-27 14:00:56'),
(446, 'users', 1, 'Account Lock Duration: 5242880 -> 10485760<br/>', '1', '2023-06-27 14:00:56'),
(447, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:56 -> 2023-06-27 14:00:58<br/>Failed Login Attempts: 26 -> 27<br/>', '1', '2023-06-27 14:00:58'),
(448, 'users', 1, 'Account Lock Duration: 10485760 -> 20971520<br/>', '1', '2023-06-27 14:00:58'),
(449, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:58 -> 2023-06-27 14:00:59<br/>Failed Login Attempts: 27 -> 28<br/>', '1', '2023-06-27 14:00:59'),
(450, 'users', 1, 'Account Lock Duration: 20971520 -> 41943040<br/>', '1', '2023-06-27 14:00:59'),
(451, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:00:59 -> 2023-06-27 14:01:00<br/>Failed Login Attempts: 28 -> 29<br/>', '1', '2023-06-27 14:01:00'),
(452, 'users', 1, 'Account Lock Duration: 41943040 -> 83886080<br/>', '1', '2023-06-27 14:01:00'),
(453, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:01:00 -> 2023-06-27 14:01:02<br/>Failed Login Attempts: 29 -> 30<br/>', '1', '2023-06-27 14:01:02'),
(454, 'users', 1, 'Account Lock Duration: 83886080 -> 167772160<br/>', '1', '2023-06-27 14:01:02'),
(455, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:01:02 -> 2023-06-27 14:01:03<br/>Failed Login Attempts: 30 -> 31<br/>', '1', '2023-06-27 14:01:03'),
(456, 'users', 1, 'Account Lock Duration: 167772160 -> 335544320<br/>', '1', '2023-06-27 14:01:03'),
(457, 'users', 1, 'Last Failed Login Attempt: 2023-06-27 14:01:03 -> 2023-06-27 14:01:04<br/>Failed Login Attempts: 31 -> 32<br/>', '1', '2023-06-27 14:01:04'),
(458, 'users', 1, 'Account Lock Duration: 335544320 -> 671088640<br/>', '1', '2023-06-27 14:01:04'),
(459, 'users', 1, 'Reset Token: JCM9O4VOd0RNaH%2FgMGdcLh3g%2FpGYivtqWrgpgJdON5o%3D -> FoL0D0dploLRggOHQpGyHDSQB%2BNOD4az3BbtGJI86Js%3D<br/>Reset Token Expiry Date: 2023-06-27 13:40:35 -> 2023-06-27 14:15:10<br/>', '1', '2023-06-27 14:05:10'),
(460, 'users', 1, 'Is Locked: 1 -> 0<br/>Failed Login Attempts: 32 -> 0<br/>Password Expiry Date: 2023-12-26 -> 2023-12-27<br/>Last Password Change: 2023-06-26 11:21:00 -> 2023-06-27 14:05:38<br/>Account Lock Duration: 671088640 -> 0<br/>', '1', '2023-06-27 14:05:38'),
(461, 'users', 1, 'OTP: ymecq%2B%2FvOdQXLttC9hPFuTi3wZZ8goUar%2FJ4tPj2SfY%3D -> OvJEe5tpqCK4051QMygslZG7ABuUiIQOA6EFjWTwZ24%3D<br/>OTP Expiry Date: 2023-06-27 13:56:34 -> 2023-06-27 14:10:53<br/>', '1', '2023-06-27 14:05:53'),
(462, 'users', 1, 'Failed OTP Attempts: 0 -> 1<br/>', '1', '2023-06-27 14:06:18'),
(463, 'users', 1, 'Failed OTP Attempts: 1 -> 2<br/>', '1', '2023-06-27 14:06:18'),
(464, 'users', 1, 'Failed OTP Attempts: 2 -> 3<br/>', '1', '2023-06-27 14:06:19'),
(465, 'users', 1, 'Failed OTP Attempts: 3 -> 4<br/>', '1', '2023-06-27 14:06:20'),
(466, 'users', 1, 'Failed OTP Attempts: 4 -> 5<br/>', '1', '2023-06-27 14:06:21'),
(467, 'users', 1, 'OTP Expiry Date: 2023-06-27 14:10:53 -> 2023-05-27 14:06:21<br/>', '1', '2023-06-27 14:06:21'),
(468, 'users', 1, 'OTP: OvJEe5tpqCK4051QMygslZG7ABuUiIQOA6EFjWTwZ24%3D -> bpQ0d9bMHYV4vT9ZEX558WpMRP53XG0IOdo6KynF4Ls%3D<br/>OTP Expiry Date: 2023-05-27 14:06:21 -> 2023-06-27 14:13:53<br/>Failed OTP Attempts: 5 -> 0<br/>', '1', '2023-06-27 14:08:53'),
(469, 'users', 1, 'Last Connection Date: 2023-06-27 13:31:50 -> 2023-06-27 14:09:32<br/>', '1', '2023-06-27 14:09:32'),
(470, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-27 14:09:35'),
(471, 'users', 1, 'OTP: bpQ0d9bMHYV4vT9ZEX558WpMRP53XG0IOdo6KynF4Ls%3D -> FhI0EI9Agk0THmxGZrYuyVQ1YxVUoKdURAGI5DhJkIE%3D<br/>OTP Expiry Date: 2023-06-27 14:13:53 -> 2023-06-27 14:14:49<br/>', '1', '2023-06-27 14:09:49'),
(472, 'users', 1, 'Last Connection Date: 2023-06-27 14:09:32 -> 2023-06-27 14:10:09<br/>', '1', '2023-06-27 14:10:09'),
(473, 'users', 1, '2-Factor Authentication: 1 -> 0<br/>', '1', '2023-06-27 14:10:15'),
(474, 'users', 1, 'Last Connection Date: 2023-06-27 14:10:09 -> 2023-06-27 14:10:25<br/>', '1', '2023-06-27 14:10:25'),
(475, 'users', 1, '2-Factor Authentication: 0 -> 1<br/>', '1', '2023-06-27 14:10:28'),
(476, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-27 14:11:54'),
(477, 'users', 1, 'OTP: FhI0EI9Agk0THmxGZrYuyVQ1YxVUoKdURAGI5DhJkIE%3D -> nt8nqyDovz%2FHk9Qeu5QXnEcqMTesGgGFkAesc1zdrWs%3D<br/>OTP Expiry Date: 2023-06-27 14:14:49 -> 2023-06-28 17:02:06<br/>', '1', '2023-06-28 16:57:06'),
(478, 'users', 1, 'Last Connection Date: 2023-06-27 14:10:25 -> 2023-06-28 16:57:33<br/>', '1', '2023-06-28 16:57:33'),
(479, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-28 16:57:37'),
(480, 'ui_customization_setting', 1, 'Preset Theme: preset-9 -> preset-6<br/>', '1', '2023-06-28 16:59:05'),
(481, 'ui_customization_setting', 1, 'Preset Theme: preset-6 -> preset-5<br/>', '1', '2023-06-28 16:59:07'),
(482, 'ui_customization_setting', 1, 'Preset Theme: preset-5 -> preset-10<br/>', '1', '2023-06-28 16:59:08'),
(483, 'ui_customization_setting', 1, 'Preset Theme: preset-10 -> preset-1<br/>', '1', '2023-06-28 16:59:11'),
(484, 'ui_customization_setting', 1, 'Theme Contrast: false -> true<br/>', '1', '2023-06-28 16:59:11'),
(485, 'ui_customization_setting', 1, 'Theme Contrast: true -> false<br/>', '1', '2023-06-28 16:59:12'),
(486, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-28 16:59:13'),
(487, 'ui_customization_setting', 1, 'Dark Layout: light -> dark<br/>', '1', '2023-06-28 16:59:14'),
(488, 'users', 1, 'OTP: nt8nqyDovz%2FHk9Qeu5QXnEcqMTesGgGFkAesc1zdrWs%3D -> uoJ04qcrOcuN3ykmxi3ur%2B4wUyS0%2FMONdUXrcAs%2Bv1M%3D<br/>OTP Expiry Date: 2023-06-28 17:02:06 -> 2023-06-29 10:58:26<br/>', '1', '2023-06-29 10:53:26'),
(489, 'users', 1, 'Last Connection Date: 2023-06-28 16:57:33 -> 2023-06-29 10:53:55<br/>', '1', '2023-06-29 10:53:55'),
(490, 'users', 1, '2-Factor Authentication: 1 -> 0<br/>', '1', '2023-06-29 10:54:00'),
(491, 'ui_customization_setting', 1, 'Dark Layout: dark -> light<br/>', '1', '2023-06-29 11:52:34'),
(492, 'users', 1, 'Last Connection Date: 2023-06-29 10:53:55 -> 2023-06-29 14:53:22<br/>', '1', '2023-06-29 14:53:22'),
(493, 'users', 1, 'Last Connection Date: 2023-06-29 14:53:22 -> 2023-06-29 14:54:03<br/>', '1', '2023-06-29 14:54:03'),
(494, 'menu_group', 2, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2', '1', '2023-06-29 17:30:41'),
(495, 'menu_group', 3, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2', '1', '2023-06-29 17:31:15'),
(496, 'menu_group', 4, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2', '1', '2023-06-29 17:31:41'),
(497, 'menu_group', 5, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2', '1', '2023-06-29 17:32:05'),
(498, 'menu_group', 6, 'Menu group created. <br/><br/>Menu Group Name: Test2<br/>Order Sequence: 2', '1', '2023-06-29 17:35:00'),
(499, 'menu_group', 7, 'Menu group created. <br/><br/>Menu Group Name: asd<br/>Order Sequence: 2', '1', '2023-06-29 17:35:33'),
(500, 'menu_group', 8, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 3', '1', '2023-06-29 17:35:50'),
(501, 'users', 1, 'Last Connection Date: 2023-06-29 14:54:03 -> 2023-06-30 09:48:55<br/>', '1', '2023-06-30 09:48:55'),
(502, 'menu_group', 9, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 2', '1', '2023-06-30 09:54:26'),
(503, 'menu_group', 10, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 23', '1', '2023-06-30 09:57:07'),
(504, 'menu_group', 11, 'Menu group created. <br/><br/>Menu Group Name: test<br/>Order Sequence: 24', '1', '2023-06-30 10:06:47'),
(505, 'ui_customization_setting', 1, 'Preset Theme: preset-1 -> preset-5<br/>', '1', '2023-06-30 10:06:53'),
(506, 'ui_customization_setting', 1, 'Preset Theme: preset-5 -> preset-9<br/>', '1', '2023-06-30 10:06:56'),
(507, 'ui_customization_setting', 1, 'Preset Theme: preset-9 -> preset-6<br/>', '1', '2023-06-30 10:07:55'),
(508, 'ui_customization_setting', 1, 'Preset Theme: preset-6 -> preset-1<br/>', '1', '2023-06-30 10:07:58'),
(509, 'ui_customization_setting', 1, 'Preset Theme: preset-1 -> preset-10<br/>', '1', '2023-06-30 10:08:01'),
(510, 'menu_group', 12, 'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1', '1', '2023-06-30 17:07:43'),
(511, 'menu_group', 13, 'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1', '1', '2023-06-30 17:08:45'),
(512, 'menu_group', 14, 'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 1', '1', '2023-06-30 17:09:29');

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
(1, 1, 0, 0, 0, 0, 0, 1),
(2, 1, 1, 1, 1, 1, 1, 1),
(3, 1, 1, 1, 1, 1, 1, 1);

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
(1, 'Administration', 1, 1),
(14, 'Administration', 1, 1);

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
(3, 'Menu Item', 1, 'menu-item.php', 1, '', 2, 1);

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
(2, 'Employee', 'Employee', 1, 1);

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
(1, 'Assign Menu Item Role Access', 1);

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
(1, 1);

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
(1, 1, 'false', 'true', 'preset-10', 'light', 'false', 'false', 1);

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
(1, 'Administrator', 'ldagulto@encorefinancials.com', '%2FnHtFs4nssZsrx%2F%2BhCyTDkBV%2FHMyu8%2BloCp8YRzuzw4%3D', 0, 1, NULL, 0, '2023-06-30 09:48:55', '2023-12-27', 'FoL0D0dploLRggOHQpGyHDSQB%2BNOD4az3BbtGJI86Js%3D', '2023-06-27 14:15:10', 1, 0, 'uoJ04qcrOcuN3ykmxi3ur%2B4wUyS0%2FMONdUXrcAs%2Bv1M%3D', '2023-06-29 10:58:26', 0, '2023-06-27 14:05:38', 0, NULL, 0, '2c300c9bb4332919325314dc9de9351c', 1);

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
  MODIFY `audit_log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=513;

--
-- AUTO_INCREMENT for table `menu_group`
--
ALTER TABLE `menu_group`
  MODIFY `menu_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `password_history`
--
ALTER TABLE `password_history`
  MODIFY `password_history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_action`
--
ALTER TABLE `system_action`
  MODIFY `system_action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `menu_access_right`
--
ALTER TABLE `menu_access_right`
  ADD CONSTRAINT `menu_access_right_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`),
  ADD CONSTRAINT `menu_access_right_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`menu_item_id`);

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
-- Constraints for table `system_action_access_rights`
--
ALTER TABLE `system_action_access_rights`
  ADD CONSTRAINT `system_action_access_rights_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`),
  ADD CONSTRAINT `system_action_access_rights_ibfk_2` FOREIGN KEY (`system_action_id`) REFERENCES `system_action` (`system_action_id`);

--
-- Constraints for table `ui_customization_setting`
--
ALTER TABLE `ui_customization_setting`
  ADD CONSTRAINT `ui_customization_setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
