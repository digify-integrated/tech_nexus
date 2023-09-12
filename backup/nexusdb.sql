-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2023 at 11:19 AM
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkBankExist` (IN `p_bank_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM bank
    WHERE bank_id = p_bank_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkBloodTypeExist` (IN `p_blood_type_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM blood_type
    WHERE blood_type_id = p_blood_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkBranchExist` (IN `p_branch_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM branch
    WHERE branch_id = p_branch_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkCityExist` (IN `p_city_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM city
    WHERE city_id = p_city_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkCivilStatusExist` (IN `p_civil_status_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM civil_status
    WHERE civil_status_id = p_civil_status_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkCompanyExist` (IN `p_company_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM company
    WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkCountryExist` (IN `p_country_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM country
    WHERE country_id = p_country_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkCurrencyExist` (IN `p_currency_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM currency
    WHERE currency_id = p_currency_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkDepartmentExist` (IN `p_department_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM department
    WHERE department_id = p_department_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkDepartureReasonExist` (IN `p_departure_reason_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM departure_reason
    WHERE departure_reason_id = p_departure_reason_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkEmailSettingExist` (IN `p_email_setting_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM email_setting
    WHERE email_setting_id = p_email_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkEmployeeTypeExist` (IN `p_employee_type_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM employee_type
    WHERE employee_type_id = p_employee_type_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkGenderExist` (IN `p_gender_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM gender
    WHERE gender_id = p_gender_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkHolidayTypeExist` (IN `p_holiday_type_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM holiday_type
    WHERE holiday_type_id = p_holiday_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkIDTypeExist` (IN `p_id_type_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM id_type
    WHERE id_type_id = p_id_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkInterfaceSettingExist` (IN `p_interface_setting_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkJobLevelExist` (IN `p_job_level_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM job_level
    WHERE job_level_id = p_job_level_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkJobPositionExist` (IN `p_job_position_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM job_position
    WHERE job_position_id = p_job_position_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkJobPositionQualificationExist` (IN `p_job_position_qualification_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM job_position_qualification
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkJobPositionRequirementExist` (IN `p_job_position_requirement_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM job_position_requirement
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkJobPositionResponsibilityExist` (IN `p_job_position_responsibility_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM job_position_responsibility
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkNationalityExist` (IN `p_nationality_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM nationality
    WHERE nationality_id = p_nationality_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkNotificationSettingExist` (IN `p_notification_setting_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkRelationExist` (IN `p_relation_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM relation
    WHERE relation_id = p_relation_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkReligionExist` (IN `p_religion_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM religion
    WHERE religion_id = p_religion_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkStateExist` (IN `p_state_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM state
    WHERE state_id = p_state_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkWorkScheduleTypeExist` (IN `p_work_schedule_type_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM work_schedule_type
    WHERE work_schedule_type_id = p_work_schedule_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkZoomAPIExist` (IN `p_zoom_api_id` INT)   BEGIN
	SELECT COUNT(*) AS total
    FROM zoom_api
    WHERE zoom_api_id = zoom_api_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteBank` (IN `p_bank_id` INT)   BEGIN
    DELETE FROM bank WHERE bank_id = p_bank_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteBloodType` (IN `p_blood_type_id` INT)   BEGIN
    DELETE FROM blood_type WHERE blood_type_id = p_blood_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteBranch` (IN `p_branch_id` INT)   BEGIN
	DELETE FROM branch
    WHERE branch_id = p_branch_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCity` (IN `p_city_id` INT)   BEGIN
	DELETE FROM city
    WHERE city_id = p_city_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCivilStatus` (IN `p_civil_status_id` INT)   BEGIN
    DELETE FROM civil_status WHERE civil_status_id = p_civil_status_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCompany` (IN `p_company_id` INT)   BEGIN
	DELETE FROM company
    WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCountry` (IN `p_country_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM city WHERE state_id IN (SELECT state_id FROM state WHERE country_id = p_country_id);
    DELETE FROM state WHERE country_id = p_country_id;
	DELETE FROM country WHERE country_id = p_country_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCurrency` (IN `p_currency_id` INT)   BEGIN
	DELETE FROM currency
    WHERE currency_id = p_currency_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteDepartment` (IN `p_department_id` INT)   BEGIN
	DELETE FROM department
    WHERE department_id = p_department_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteDepartureReason` (IN `p_departure_reason_id` INT)   BEGIN
    DELETE FROM departure_reason WHERE departure_reason_id = p_departure_reason_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteEmailSetting` (IN `p_email_setting_id` INT)   BEGIN
	DELETE FROM email_setting
    WHERE email_setting_id = p_email_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteEmployeeType` (IN `p_employee_type_id` INT)   BEGIN
    DELETE FROM employee_type WHERE employee_type_id = p_employee_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteFileExtension` (IN `p_file_extension_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	DELETE FROM upload_setting_file_extension WHERE file_extension_id = p_file_extension_id;
    DELETE FROM file_extension WHERE file_extension_id = p_file_extension_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteFileType` (IN `p_file_type_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM file_extension WHERE file_type_id = p_file_type_id;
    DELETE FROM file_type WHERE file_type_id = p_file_type_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteGender` (IN `p_gender_id` INT)   BEGIN
    DELETE FROM gender WHERE gender_id = p_gender_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteHolidayType` (IN `p_holiday_type_id` INT)   BEGIN
    DELETE FROM holiday_type WHERE holiday_type_id = p_holiday_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteIDType` (IN `p_id_type_id` INT)   BEGIN
    DELETE FROM id_type WHERE id_type_id = p_id_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteInterfaceSetting` (IN `p_interface_setting_id` INT)   BEGIN
	DELETE FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteJobLevel` (IN `p_job_level_id` INT)   BEGIN
    DELETE FROM job_level WHERE job_level_id = p_job_level_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteJobPosition` (IN `p_job_position_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	DELETE FROM job_position_responsibility WHERE job_position_id = p_job_position_id;
	DELETE FROM job_position_requirement WHERE job_position_id = p_job_position_id;
	DELETE FROM job_position_qualification WHERE job_position_id = p_job_position_id;
    DELETE FROM job_position WHERE job_position_id = p_job_position_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteJobPositionQualification` (IN `p_job_position_qualification_id` INT)   BEGIN
	DELETE FROM job_position_qualification
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteJobPositionRequirement` (IN `p_job_position_requirement_id` INT)   BEGIN
	DELETE FROM job_position_requirement
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteJobPositionResponsibility` (IN `p_job_position_responsibility_id` INT)   BEGIN
	DELETE FROM job_position_responsibility
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuGroup` (IN `p_menu_group_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM menu_item_access_right WHERE menu_item_id IN (SELECT menu_item_id FROM menu_item WHERE menu_group_id = p_menu_group_id);
    DELETE FROM menu_item WHERE menu_group_id = p_menu_group_id;
    DELETE FROM menu_group WHERE menu_group_id = p_menu_group_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuItem` (IN `p_menu_item_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM menu_item_access_right WHERE menu_item_id = p_menu_item_id;
    DELETE FROM menu_item WHERE menu_item_id = p_menu_item_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMenuItemRoleAccess` (IN `p_menu_item_id` INT, IN `p_role_id` INT)   BEGIN
	DELETE FROM menu_item_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteNationality` (IN `p_nationality_id` INT)   BEGIN
    DELETE FROM nationality WHERE nationality_id = p_nationality_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteNotificationSetting` (IN `p_notification_setting_id` INT)   BEGIN
	DELETE FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRelation` (IN `p_relation_id` INT)   BEGIN
    DELETE FROM relation WHERE relation_id = p_relation_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteReligion` (IN `p_religion_id` INT)   BEGIN
    DELETE FROM religion WHERE religion_id = p_religion_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRole` (IN `p_role_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM role_users WHERE role_id = p_role_id;
    DELETE FROM menu_item_access_right WHERE role_id = p_role_id;
    DELETE FROM system_action_access_rights WHERE role_id = p_role_id;
    DELETE FROM role WHERE role_id = p_role_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRoleUser` (IN `p_user_id` INT, IN `p_role_id` INT)   BEGIN
	DELETE FROM role_users
    WHERE user_id = p_user_id AND role_id = p_role_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteState` (IN `p_state_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM city WHERE state_id = p_state_id;
    DELETE FROM state WHERE state_id = p_state_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteSystemAction` (IN `p_system_action_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM system_action_access_rights WHERE system_action_id = p_system_action_id;
    DELETE FROM system_action WHERE system_action_id = p_system_action_id;

    COMMIT;
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
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	DELETE FROM upload_setting_file_extension WHERE upload_setting_id = p_upload_setting_id;
    DELETE FROM upload_setting WHERE upload_setting_id = p_upload_setting_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteUploadSettingFileExtension` (IN `p_upload_setting_id` INT, IN `p_file_extension_id` INT)   BEGIN
	DELETE FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id AND file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteUserAccount` (IN `p_user_id` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	DELETE FROM password_history WHERE user_id = p_user_id;
	DELETE FROM ui_customization_setting WHERE user_id = p_user_id;
	DELETE FROM role_users WHERE user_id = p_user_id;
    DELETE FROM users WHERE user_id = p_user_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteWorkScheduleType` (IN `p_work_schedule_type_id` INT)   BEGIN
    DELETE FROM work_schedule_type WHERE work_schedule_type_id = p_work_schedule_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteZoomAPI` (IN `p_zoom_api_id` INT)   BEGIN
	DELETE FROM zoom_api
    WHERE zoom_api_id = p_zoom_api_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateBank` (IN `p_bank_id` INT, IN `p_last_log_by` INT, OUT `p_new_bank_id` INT)   BEGIN
    DECLARE p_bank_name VARCHAR(100);
    DECLARE p_bank_identifier_code VARCHAR(100);
    
    SELECT bank_name, bank_identifier_code
    INTO p_bank_name, p_bank_identifier_code
    FROM bank 
    WHERE bank_id = p_bank_id;
    
    INSERT INTO bank (bank_name, bank_identifier_code, last_log_by) 
    VALUES(p_bank_name, p_bank_identifier_code, p_last_log_by);
    
    SET p_new_bank_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateBloodType` (IN `p_blood_type_id` INT, IN `p_last_log_by` INT, OUT `p_new_blood_type_id` INT)   BEGIN
    DECLARE p_blood_type_name VARCHAR(100);
    
    SELECT blood_type_name
    INTO p_blood_type_name
    FROM blood_type 
    WHERE blood_type_id = p_blood_type_id;
    
    INSERT INTO blood_type (blood_type_name, last_log_by) 
    VALUES(p_blood_type_name, p_last_log_by);
    
    SET p_new_blood_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateBranch` (IN `p_branch_id` INT, IN `p_last_log_by` INT, OUT `p_new_branch_id` INT)   BEGIN
    DECLARE p_branch_name VARCHAR(100);
    DECLARE p_address VARCHAR(1000);
    DECLARE p_city_id INT;
    DECLARE p_phone VARCHAR(20);
    DECLARE p_mobile VARCHAR(20);
    DECLARE p_telephone VARCHAR(20);
    DECLARE p_email VARCHAR(100);
    DECLARE p_website VARCHAR(500);
    
    SELECT branch_name, address, city_id, phone, mobile, telephone, email, website
    INTO p_branch_name, p_address, p_city_id, p_phone, p_mobile, p_telephone, p_email, p_website
    FROM branch 
    WHERE branch_id = p_branch_id;
    
    INSERT INTO branch (branch_name, address, city_id, phone, mobile, telephone, email, website, last_log_by) 
    VALUES(p_branch_name, p_address, p_city_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
    
    SET p_new_branch_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateCity` (IN `p_city_id` INT, IN `p_last_log_by` INT, OUT `p_new_city_id` INT)   BEGIN
    DECLARE p_city_name VARCHAR(100);
    DECLARE p_state_id INT;
    
    SELECT city_name, state_id
    INTO p_city_name, p_state_id
    FROM city 
    WHERE city_id = p_city_id;
    
    INSERT INTO city (city_name, state_id, last_log_by) 
    VALUES(p_city_name, p_state_id, p_last_log_by);
    
    SET p_new_city_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateCivilStatus` (IN `p_civil_status_id` INT, IN `p_last_log_by` INT, OUT `p_new_civil_status_id` INT)   BEGIN
    DECLARE p_civil_status_name VARCHAR(100);
    
    SELECT civil_status_name
    INTO p_civil_status_name
    FROM civil_status 
    WHERE civil_status_id = p_civil_status_id;
    
    INSERT INTO civil_status (civil_status_name, last_log_by) 
    VALUES(p_civil_status_name, p_last_log_by);
    
    SET p_new_civil_status_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateCompany` (IN `p_company_id` INT, IN `p_last_log_by` INT, OUT `p_new_company_id` INT)   BEGIN
    DECLARE p_company_name VARCHAR(100);
    DECLARE p_address VARCHAR(1000);
    DECLARE p_city_id INT;
    DECLARE p_tax_id VARCHAR(500);
    DECLARE p_currency_id INT;
    DECLARE p_phone VARCHAR(20);
    DECLARE p_mobile VARCHAR(20);
    DECLARE p_telephone VARCHAR(20);
    DECLARE p_email VARCHAR(100);
    DECLARE p_website VARCHAR(500);
    
    SELECT company_name, address, city_id, tax_id, currency_id, phone, mobile, telephone, email, website
    INTO p_company_name, p_address, p_city_id, p_tax_id, p_currency_id, p_phone, p_mobile, p_telephone, p_email, p_website
    FROM company 
    WHERE company_id = p_company_id;
    
    INSERT INTO company (company_name, address, city_id, tax_id, currency_id, phone, mobile, telephone, email, website, last_log_by) 
    VALUES(p_company_name, p_address, p_city_id, p_tax_id, p_currency_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
    
    SET p_new_company_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateCountry` (IN `p_country_id` INT, IN `p_last_log_by` INT, OUT `p_new_country_id` INT)   BEGIN
    DECLARE p_country_name VARCHAR(100);
    DECLARE p_country_code VARCHAR(5);
    DECLARE p_phone_code VARCHAR(20);
    
    SELECT country_name, country_code, phone_code
    INTO p_country_name, p_country_code, p_phone_code
    FROM country 
    WHERE country_id = p_country_id;
    
    INSERT INTO country (country_name, country_code, phone_code, last_log_by) 
    VALUES(p_country_name, p_country_code, p_phone_code, p_last_log_by);
    
    SET p_new_country_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateCurrency` (IN `p_currency_id` INT, IN `p_last_log_by` INT, OUT `p_new_currency_id` INT)   BEGIN
    DECLARE p_currency_name VARCHAR(100);
    DECLARE p_symbol VARCHAR(10);
    DECLARE p_shorthand VARCHAR(10);
    
    SELECT currency_name, symbol, shorthand
    INTO p_currency_name, p_symbol, p_shorthand
    FROM currency 
    WHERE currency_id = p_currency_id;
    
    INSERT INTO currency (currency_name, symbol, shorthand, last_log_by) 
    VALUES(p_currency_name, p_symbol, p_shorthand, p_last_log_by);
    
    SET p_new_currency_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateDepartment` (IN `p_department_id` INT, IN `p_last_log_by` INT, OUT `p_new_department_id` INT)   BEGIN
    DECLARE p_department_name VARCHAR(100);
    DECLARE p_parent_department INT;
    DECLARE p_manager INT;
    
    SELECT department_name, parent_department, Manager
    INTO p_department_name, p_parent_department, p_manager
    FROM department 
    WHERE department_id = p_department_id;
    
    INSERT INTO department (department_name, parent_department, Manager, last_log_by) 
    VALUES(p_department_name, p_parent_department, p_manager, p_last_log_by);
    
    SET p_new_department_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateDepartureReason` (IN `p_departure_reason_id` INT, IN `p_last_log_by` INT, OUT `p_new_departure_reason_id` INT)   BEGIN
    DECLARE p_departure_reason_name VARCHAR(100);
    
    SELECT departure_reason_name
    INTO p_departure_reason_name
    FROM departure_reason 
    WHERE departure_reason_id = p_departure_reason_id;
    
    INSERT INTO departure_reason (departure_reason_name, last_log_by) 
    VALUES(p_departure_reason_name, p_last_log_by);
    
    SET p_new_departure_reason_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateEmailSetting` (IN `p_email_setting_id` INT, IN `p_last_log_by` INT, OUT `p_new_email_setting_id` INT)   BEGIN
    DECLARE p_email_setting_name VARCHAR(100);
    DECLARE p_email_setting_description VARCHAR(200);
    DECLARE p_mail_host VARCHAR(100);
    DECLARE p_port INT;
    DECLARE p_smtp_auth INT(1);
    DECLARE p_smtp_auto_tls INT(1);
    DECLARE p_mail_username VARCHAR(200);
    DECLARE p_mail_password VARCHAR(250);
    DECLARE p_mail_encryption VARCHAR(20);
    DECLARE p_mail_from_name VARCHAR(200);
    DECLARE p_mail_from_email VARCHAR(200);
    
    SELECT email_setting_name, email_setting_description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_password, mail_encryption, mail_from_name, mail_from_email
    INTO p_email_setting_name, p_email_setting_description, p_mail_host, p_port, p_smtp_auth, p_smtp_auto_tls, p_mail_username, p_mail_password, p_mail_encryption, p_mail_from_name, p_mail_from_email
    FROM email_setting 
    WHERE email_setting_id = p_email_setting_id;
    
    INSERT INTO email_setting (email_setting_name, email_setting_description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_password, mail_encryption, mail_from_name, mail_from_email, last_log_by) 
    VALUES(p_email_setting_name, p_email_setting_description, p_mail_host, p_port, p_smtp_auth, p_smtp_auto_tls, p_mail_username, p_mail_password, p_mail_encryption, p_mail_from_name, p_mail_from_email, p_last_log_by);
    
    SET p_new_email_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateEmployeeType` (IN `p_employee_type_id` INT, IN `p_last_log_by` INT, OUT `p_new_employee_type_id` INT)   BEGIN
    DECLARE p_employee_type_name VARCHAR(100);
    
    SELECT employee_type_name
    INTO p_employee_type_name
    FROM employee_type 
    WHERE employee_type_id = p_employee_type_id;
    
    INSERT INTO employee_type (employee_type_name, last_log_by) 
    VALUES(p_employee_type_name, p_last_log_by);
    
    SET p_new_employee_type_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateGender` (IN `p_gender_id` INT, IN `p_last_log_by` INT, OUT `p_new_gender_id` INT)   BEGIN
    DECLARE p_gender_name VARCHAR(100);
    
    SELECT gender_name
    INTO p_gender_name
    FROM gender 
    WHERE gender_id = p_gender_id;
    
    INSERT INTO gender (gender_name, last_log_by) 
    VALUES(p_gender_name, p_last_log_by);
    
    SET p_new_gender_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateHolidayType` (IN `p_holiday_type_id` INT, IN `p_last_log_by` INT, OUT `p_new_holiday_type_id` INT)   BEGIN
    DECLARE p_holiday_type_name VARCHAR(100);
    
    SELECT holiday_type_name
    INTO p_holiday_type_name
    FROM holiday_type 
    WHERE holiday_type_id = p_holiday_type_id;
    
    INSERT INTO holiday_type (holiday_type_name, last_log_by) 
    VALUES(p_holiday_type_name, p_last_log_by);
    
    SET p_new_holiday_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateIDType` (IN `p_id_type_id` INT, IN `p_last_log_by` INT, OUT `p_new_id_type_id` INT)   BEGIN
    DECLARE p_id_type_name VARCHAR(100);
    
    SELECT id_type_name
    INTO p_id_type_name
    FROM id_type 
    WHERE id_type_id = p_id_type_id;
    
    INSERT INTO id_type (id_type_name, last_log_by) 
    VALUES(p_id_type_name, p_last_log_by);
    
    SET p_new_id_type_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateJobLevel` (IN `p_job_level_id` INT, IN `p_last_log_by` INT, OUT `p_new_job_level_id` INT)   BEGIN
    DECLARE p_current_level VARCHAR(10);
    DECLARE p_rank VARCHAR(100);
    DECLARE p_functional_level VARCHAR(100);
    
    SELECT current_level, rank, functional_level
    INTO p_current_level, p_rank, p_functional_level
    FROM job_level 
    WHERE job_level_id = p_job_level_id;
    
    INSERT INTO job_level (current_level, rank, functional_level, last_log_by) 
    VALUES(p_current_level, p_rank, p_functional_level, p_last_log_by);
    
    SET p_new_job_level_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateJobPosition` (IN `p_job_position_id` INT, IN `p_last_log_by` INT, OUT `p_new_job_position_id` INT)   BEGIN
    DECLARE p_job_position_name VARCHAR(100);
    DECLARE p_job_position_description VARCHAR(2000);
    DECLARE p_recruitment_status TINYINT(1);
    DECLARE p_department_id INT;
    DECLARE p_expected_new_employees INT;
    
    SELECT job_position_name, job_position_description, recruitment_status, department_id, expected_new_employees
    INTO p_job_position_name, p_job_position_description, p_recruitment_status, p_department_id, p_expected_new_employees
    FROM job_position 
    WHERE job_position_id = p_job_position_id;
    
    INSERT INTO job_position (job_position_name, job_position_description, recruitment_status, department_id, expected_new_employees, last_log_by) 
    VALUES(p_job_position_name, p_job_position_description, p_recruitment_status, p_department_id, p_expected_new_employees, p_last_log_by);
    
    SET p_new_job_position_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateNationality` (IN `p_nationality_id` INT, IN `p_last_log_by` INT, OUT `p_new_nationality_id` INT)   BEGIN
    DECLARE p_nationality_name VARCHAR(100);
    
    SELECT nationality_name
    INTO p_nationality_name
    FROM nationality 
    WHERE nationality_id = p_nationality_id;
    
    INSERT INTO nationality (nationality_name, last_log_by) 
    VALUES(p_nationality_name, p_last_log_by);
    
    SET p_new_nationality_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateNotificationSetting` (IN `p_notification_setting_id` INT, IN `p_last_log_by` INT, OUT `p_new_notification_setting_id` INT)   BEGIN
    DECLARE p_notification_setting_name VARCHAR(100);
    DECLARE p_notification_setting_description VARCHAR(200);
    DECLARE p_system_notification INT(1);
    DECLARE p_email_notification INT(1);
    DECLARE p_sms_notification INT(1);
    DECLARE p_system_notification_title VARCHAR(200);
	DECLARE p_system_notification_message VARCHAR(200);
	DECLARE p_email_notification_subject VARCHAR(200);
	DECLARE p_email_notification_body LONGTEXT;
	DECLARE p_sms_notification_message VARCHAR(500);
    
    SELECT notification_setting_name, notification_setting_description, system_notification, email_notification, sms_notification, system_notification_title, system_notification_message, email_notification_subject, email_notification_body, sms_notification_message
    INTO p_notification_setting_name, p_notification_setting_description, p_system_notification, p_email_notification, p_sms_notification, p_system_notification_title, p_system_notification_message, p_email_notification_subject, p_email_notification_body, p_sms_notification_message
    FROM notification_setting 
    WHERE notification_setting_id = p_notification_setting_id;
    
    INSERT INTO notification_setting (notification_setting_name, notification_setting_description, system_notification, email_notification, sms_notification, system_notification_title, system_notification_message, email_notification_subject, email_notification_body, sms_notification_message, last_log_by) 
    VALUES(p_notification_setting_name, p_notification_setting_description, p_system_notification, p_email_notification, p_sms_notification, p_system_notification_title, p_system_notification_message, p_email_notification_subject, p_email_notification_body, p_sms_notification_message, p_last_log_by);
    
    SET p_new_notification_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateRelation` (IN `p_relation_id` INT, IN `p_last_log_by` INT, OUT `p_new_relation_id` INT)   BEGIN
    DECLARE p_relation_name VARCHAR(100);
    
    SELECT relation_name
    INTO p_relation_name
    FROM relation 
    WHERE relation_id = p_relation_id;
    
    INSERT INTO relation (relation_name, last_log_by) 
    VALUES(p_relation_name, p_last_log_by);
    
    SET p_new_relation_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateReligion` (IN `p_religion_id` INT, IN `p_last_log_by` INT, OUT `p_new_religion_id` INT)   BEGIN
    DECLARE p_religion_name VARCHAR(100);
    
    SELECT religion_name
    INTO p_religion_name
    FROM religion 
    WHERE religion_id = p_religion_id;
    
    INSERT INTO religion (religion_name, last_log_by) 
    VALUES(p_religion_name, p_last_log_by);
    
    SET p_new_religion_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateState` (IN `p_state_id` INT, IN `p_last_log_by` INT, OUT `p_new_state_id` INT)   BEGIN
    DECLARE p_state_name VARCHAR(100);
    DECLARE p_country_id INT;
    DECLARE p_state_code VARCHAR(5);
    
    SELECT state_name, country_id, state_code
    INTO p_state_name, p_country_id, p_state_code
    FROM state 
    WHERE state_id = p_state_id;
    
    INSERT INTO state (state_name, country_id, state_code, last_log_by) 
    VALUES(p_state_name, p_country_id, p_state_code, p_last_log_by);
    
    SET p_new_state_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateWorkScheduleType` (IN `p_work_schedule_type_id` INT, IN `p_last_log_by` INT, OUT `p_new_work_schedule_type_id` INT)   BEGIN
    DECLARE p_work_schedule_type_name VARCHAR(100);
    
    SELECT work_schedule_type_name
    INTO p_work_schedule_type_name
    FROM work_schedule_type 
    WHERE work_schedule_type_id = p_work_schedule_type_id;
    
    INSERT INTO work_schedule_type (work_schedule_type_name, last_log_by) 
    VALUES(p_work_schedule_type_name, p_last_log_by);
    
    SET p_new_work_schedule_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplicateZoomAPI` (IN `p_zoom_api_id` INT, IN `p_last_log_by` INT, OUT `p_new_zoom_api_id` INT)   BEGIN
    DECLARE p_zoom_api_name VARCHAR(100);
    DECLARE p_zoom_api_description VARCHAR(200);
    DECLARE p_api_key VARCHAR(1000);
    DECLARE p_api_secret VARCHAR(1000);
    
    SELECT zoom_api_name, zoom_api_description, api_key, api_secret
    INTO p_zoom_api_name, p_zoom_api_description, p_api_key, p_api_secret
    FROM zoom_api 
    WHERE zoom_api_id = p_zoom_api_id;
    
    INSERT INTO zoom_api (zoom_api_name, zoom_api_description, api_key, api_secret, last_log_by) 
    VALUES(p_zoom_api_name, p_zoom_api_description, p_api_key, p_api_secret, p_last_log_by);
    
    SET p_new_zoom_api_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateBankOptions` ()   BEGIN
	SELECT bank_id, bank_name FROM bank
	ORDER BY bank_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateBankTable` ()   BEGIN
    SELECT bank_id, bank_name
    FROM bank
    ORDER BY bank_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateBloodTypeOptions` ()   BEGIN
	SELECT blood_type_id, blood_type_name FROM blood_type
	ORDER BY blood_type_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateBloodTypeTable` ()   BEGIN
    SELECT blood_type_id, blood_type_name
    FROM blood_type
    ORDER BY blood_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateBranchOptions` ()   BEGIN
	SELECT branch_id, branch_name FROM branch
	ORDER BY branch_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateBranchTable` ()   BEGIN
    SELECT branch_id, branch_name, address, city_id
    FROM branch
    ORDER BY branch_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCityOptions` ()   BEGIN
	SELECT 
        ct.city_id AS city_id, 
        ct.city_name AS city_name,
        cy.country_name AS country_name,
        s.state_name AS state_name
    FROM city ct
    INNER JOIN state s ON s.state_id = ct.state_id
    INNER JOIN country cy ON cy.country_id = s.country_id
	ORDER BY city_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCityTable` (IN `p_state_id` INT)   BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT city_id, city_name, state_id FROM city';
    
    SET conditionList = ' WHERE 1';

    IF p_state_id > 0 THEN
        SET conditionList = CONCAT(conditionList, ' AND state_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_state_id));
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY city_id');
    
    IF p_state_id = 0 THEN
        SET query = CONCAT(query, ' LIMIT 500');
    END IF;
    
    SET query = CONCAT(query, ';');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCivilStatusOptions` ()   BEGIN
	SELECT civil_status_id, civil_status_name FROM civil_status
	ORDER BY civil_status_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCivilStatusTable` ()   BEGIN
    SELECT civil_status_id, civil_status_name
    FROM civil_status
    ORDER BY civil_status_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCompanyTable` ()   BEGIN
    SELECT company_id, company_name, company_logo, address, city_id
    FROM company
    ORDER BY company_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCountryOptions` ()   BEGIN
	SELECT country_id, country_name FROM country
	ORDER BY country_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCountryTable` ()   BEGIN
	SELECT country_id, country_name, country_code
    FROM country
    ORDER BY country_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCurrencyOptions` ()   BEGIN
	SELECT currency_id, currency_name, shorthand
    FROM currency
	ORDER BY currency_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateCurrencyTable` ()   BEGIN
    SELECT currency_id, currency_name, symbol, shorthand
    FROM currency
    ORDER BY currency_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateDepartmentOptions` ()   BEGIN
	SELECT department_id, department_name FROM department
	ORDER BY department_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateDepartmentTable` ()   BEGIN
    SELECT department_id, department_name, manager, parent_department
    FROM department
    ORDER BY department_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateDepartureReasonOptions` ()   BEGIN
	SELECT departure_reason_id, departure_reason_name FROM departure_reason
	ORDER BY departure_reason_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateDepartureReasonTable` ()   BEGIN
    SELECT departure_reason_id, departure_reason_name
    FROM departure_reason
    ORDER BY departure_reason_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateEmailSettingTable` ()   BEGIN
	SELECT email_setting_id, email_setting_name, email_setting_description
    FROM email_setting
    ORDER BY email_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateEmployeeTypeOptions` ()   BEGIN
	SELECT employee_type_id, employee_type_name FROM employee_type
	ORDER BY employee_type_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateEmployeeTypeTable` ()   BEGIN
    SELECT employee_type_id, employee_type_name
    FROM employee_type
    ORDER BY employee_type_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateGenderOptions` ()   BEGIN
	SELECT gender_id, gender_name FROM gender
	ORDER BY gender_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateGenderTable` ()   BEGIN
    SELECT gender_id, gender_name
    FROM gender
    ORDER BY gender_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateHolidayTypeOptions` ()   BEGIN
	SELECT holiday_type_id, holiday_type_name FROM holiday_type
	ORDER BY holiday_type_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateHolidayTypeTable` ()   BEGIN
    SELECT holiday_type_id, holiday_type_name
    FROM holiday_type
    ORDER BY holiday_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateIDTypeOptions` ()   BEGIN
	SELECT id_type_id, id_type_name FROM id_type
	ORDER BY id_type_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateIDTypeTable` ()   BEGIN
    SELECT id_type_id, id_type_name
    FROM id_type
    ORDER BY id_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateInterfaceSettingTable` ()   BEGIN
	SELECT interface_setting_id, interface_setting_name, interface_setting_description, value
    FROM interface_setting
    ORDER BY interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateJobLevelOptions` ()   BEGIN
	SELECT job_level_id, current_level, rank FROM job_level
	ORDER BY current_level;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateJobLevelTable` ()   BEGIN
    SELECT job_level_id, current_level, rank, functional_level
    FROM job_level
    ORDER BY job_level_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateJobPositionOptions` ()   BEGIN
	SELECT job_position_id, job_position_name FROM job_position
	ORDER BY job_position_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateJobPositionQualificationTable` (IN `p_job_position_id` INT)   BEGIN
	SELECT job_position_qualification_id, qualification 
    FROM job_position_qualification
    WHERE job_position_id = p_job_position_id 
    ORDER BY job_position_qualification_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateJobPositionRequirementTable` (IN `p_job_position_id` INT)   BEGIN
	SELECT job_position_requirement_id, requirement 
    FROM job_position_requirement
    WHERE job_position_id = p_job_position_id 
    ORDER BY job_position_requirement_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateJobPositionResponsibilityTable` (IN `p_job_position_id` INT)   BEGIN
	SELECT job_position_responsibility_id, responsibility 
    FROM job_position_responsibility
    WHERE job_position_id = p_job_position_id 
    ORDER BY job_position_responsibility_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateJobPositionTable` (IN `p_filter_recruitment_status` ENUM('active','inactive','all'), IN `p_department_id` INT)   BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT job_position_id, job_position_name, job_position_description, recruitment_status, department_id FROM job_position';
    
    SET conditionList = ' WHERE 1';

    IF p_filter_recruitment_status <> "" OR p_filter_recruitment_status = "all" THEN
        IF p_filter_recruitment_status = 'active' THEN
            SET conditionList = CONCAT(conditionList, ' AND recruitment_status = 1');
        ELSEIF p_filter_recruitment_status = 'inactive' THEN
            SET conditionList = CONCAT(conditionList, ' AND recruitment_status = 0');
        END IF;
    END IF;

    IF p_department_id <> "" THEN
        SET conditionList = CONCAT(conditionList, ' AND department_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_department_id));
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY job_position_name;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateNationalityOptions` ()   BEGIN
	SELECT nationality_id, nationality_name FROM nationality
	ORDER BY nationality_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateNationalityTable` ()   BEGIN
    SELECT nationality_id, nationality_name
    FROM nationality
    ORDER BY nationality_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateNotificationSettingTable` ()   BEGIN
	SELECT notification_setting_id, notification_setting_name, notification_setting_description, system_notification, email_notification, sms_notification
    FROM notification_setting
    ORDER BY notification_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRelationOptions` ()   BEGIN
	SELECT relation_id, relation_name FROM relation
	ORDER BY relation_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateRelationTable` ()   BEGIN
    SELECT relation_id, relation_name
    FROM relation
    ORDER BY relation_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateReligionOptions` ()   BEGIN
	SELECT religion_id, religion_name FROM religion
	ORDER BY religion_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateReligionTable` ()   BEGIN
    SELECT religion_id, religion_name
    FROM religion
    ORDER BY religion_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateStateOptions` ()   BEGIN
	SELECT
        s.state_id AS state_id,
        s.state_name AS state_name,
        c.country_name AS country_name
    FROM state s
    INNER JOIN country c ON s.country_id = c.country_id
	ORDER BY s.state_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateStateTable` (IN `p_country_id` INT)   BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT state_id, state_name, country_id FROM state';
    
    SET conditionList = ' WHERE 1';

    IF p_country_id > 0 THEN
        SET conditionList = CONCAT(conditionList, ' AND country_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_country_id));
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY state_id');
    
    IF p_country_id = 0 THEN
        SET query = CONCAT(query, ' LIMIT 500');
    END IF;

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateUpdateNotificationChannelTable` (IN `p_notification_setting_id` INT)   BEGIN
	SELECT system_notification, email_notification, sms_notification
    FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id
    ORDER BY notification_setting_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateWorkScheduleTypeOptions` ()   BEGIN
	SELECT work_schedule_type_id, work_schedule_type_name FROM work_schedule_type
	ORDER BY work_schedule_type_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateWorkScheduleTypeTable` ()   BEGIN
    SELECT work_schedule_type_id, work_schedule_type_name
    FROM work_schedule_type
    ORDER BY work_schedule_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateZoomAPITable` ()   BEGIN
	SELECT zoom_api_id, zoom_api_name, zoom_api_description
    FROM zoom_api
    ORDER BY zoom_api_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBank` (IN `p_bank_id` INT)   BEGIN
	SELECT * FROM bank
    WHERE bank_id = p_bank_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBloodType` (IN `p_blood_type_id` INT)   BEGIN
	SELECT * FROM blood_type
    WHERE blood_type_id = p_blood_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBranch` (IN `p_branch_id` INT)   BEGIN
	SELECT * FROM branch
    WHERE branch_id = p_branch_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCity` (IN `p_city_id` INT)   BEGIN
	SELECT * FROM city
    WHERE city_id = p_city_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCivilStatus` (IN `p_civil_status_id` INT)   BEGIN
	SELECT * FROM civil_status
    WHERE civil_status_id = p_civil_status_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCompany` (IN `p_company_id` INT)   BEGIN
	SELECT * FROM company
    WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCountry` (IN `p_country_id` INT)   BEGIN
	SELECT * FROM country
    WHERE country_id = p_country_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCurrency` (IN `p_currency_id` INT)   BEGIN
	SELECT * FROM currency
    WHERE currency_id = p_currency_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getDepartment` (IN `p_department_id` INT)   BEGIN
	SELECT * FROM department
    WHERE department_id = p_department_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getDepartureReason` (IN `p_departure_reason_id` INT)   BEGIN
	SELECT * FROM departure_reason
    WHERE departure_reason_id = p_departure_reason_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEmailSetting` (IN `p_email_setting_id` INT)   BEGIN
	SELECT * FROM email_setting
    WHERE email_setting_id = p_email_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEmployeeType` (IN `p_employee_type_id` INT)   BEGIN
	SELECT * FROM employee_type
    WHERE employee_type_id = p_employee_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFileExtension` (IN `p_file_extension_id` INT)   BEGIN
	SELECT * FROM file_extension
	WHERE file_extension_id = p_file_extension_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFileType` (IN `p_file_type_id` INT)   BEGIN
	SELECT * FROM file_type
    WHERE file_type_id = p_file_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getGender` (IN `p_gender_id` INT)   BEGIN
	SELECT * FROM gender
    WHERE gender_id = p_gender_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getHolidayType` (IN `p_holiday_type_id` INT)   BEGIN
	SELECT * FROM holiday_type
    WHERE holiday_type_id = p_holiday_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getIDType` (IN `p_id_type_id` INT)   BEGIN
	SELECT * FROM id_type
    WHERE id_type_id = p_id_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getInterfaceSetting` (IN `p_interface_setting_id` INT)   BEGIN
	SELECT * FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getJobLevel` (IN `p_job_level_id` INT)   BEGIN
	SELECT * FROM job_level
    WHERE job_level_id = p_job_level_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getJobPosition` (IN `p_job_position_id` INT)   BEGIN
	SELECT * FROM job_position
    WHERE job_position_id = p_job_position_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getJobPositionQualification` (IN `p_job_position_qualification_id` INT)   BEGIN
	SELECT * FROM job_position_qualification
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getJobPositionRequirement` (IN `p_job_position_requirement_id` INT)   BEGIN
	SELECT * FROM job_position_requirement
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getJobPositionResponsibility` (IN `p_job_position_responsibility_id` INT)   BEGIN
	SELECT * FROM job_position_responsibility
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getLinkedJobPositionQualification` (IN `p_job_position_id` INT)   BEGIN
	SELECT * FROM job_position_qualification
    WHERE job_position_id = p_job_position_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getLinkedJobPositionRequirement` (IN `p_job_position_id` INT)   BEGIN
	SELECT * FROM job_position_requirement
    WHERE job_position_id = p_job_position_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getLinkedJobPositionResponsibility` (IN `p_job_position_id` INT)   BEGIN
	SELECT * FROM job_position_responsibility
    WHERE job_position_id = p_job_position_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMenuGroup` (IN `p_menu_group_id` INT)   BEGIN
	SELECT * FROM menu_group
	WHERE menu_group_id = p_menu_group_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMenuItem` (IN `p_menu_item_id` INT)   BEGIN
	SELECT * FROM menu_item
	WHERE menu_item_id = p_menu_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getNationality` (IN `p_nationality_id` INT)   BEGIN
	SELECT * FROM nationality
    WHERE nationality_id = p_nationality_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getNotificationSetting` (IN `p_notification_setting_id` INT)   BEGIN
	SELECT * FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getPasswordHistory` (IN `p_user_id` INT, IN `p_email` VARCHAR(255))   BEGIN
	SELECT * FROM password_history
	WHERE p_user_id = p_user_id OR email = BINARY p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRelation` (IN `p_relation_id` INT)   BEGIN
	SELECT * FROM relation
    WHERE relation_id = p_relation_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getReligion` (IN `p_religion_id` INT)   BEGIN
	SELECT * FROM religion
    WHERE religion_id = p_religion_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `getState` (IN `p_state_id` INT)   BEGIN
	SELECT * FROM state
    WHERE state_id = p_state_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `getWorkScheduleType` (IN `p_work_schedule_type_id` INT)   BEGIN
	SELECT * FROM work_schedule_type
    WHERE work_schedule_type_id = p_work_schedule_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getZoomAPI` (IN `p_zoom_api_id` INT)   BEGIN
	SELECT * FROM zoom_api
    WHERE zoom_api_id = p_zoom_api_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertBank` (IN `p_bank_name` VARCHAR(100), IN `p_bank_identifier_code` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_bank_id` INT)   BEGIN
    INSERT INTO bank (bank_name, bank_identifier_code, last_log_by) 
	VALUES(p_bank_name, p_bank_identifier_code, p_last_log_by);
	
    SET p_bank_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertBloodType` (IN `p_blood_type_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_blood_type_id` INT)   BEGIN
    INSERT INTO blood_type (blood_type_name, last_log_by) 
	VALUES(p_blood_type_name, p_last_log_by);
	
    SET p_blood_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertBranch` (IN `p_branch_name` VARCHAR(100), IN `p_address` VARCHAR(1000), IN `p_city_id` INT, IN `p_phone` VARCHAR(20), IN `p_mobile` VARCHAR(20), IN `p_telephone` VARCHAR(20), IN `p_email` VARCHAR(100), IN `p_website` VARCHAR(500), IN `p_last_log_by` INT, OUT `p_branch_id` INT)   BEGIN
    INSERT INTO branch (branch_name, address, city_id, phone, mobile, telephone, email, website, last_log_by) 
	VALUES(p_branch_name, p_address, p_city_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
	
    SET p_branch_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertCity` (IN `p_city_name` VARCHAR(100), IN `p_state_id` INT, IN `p_last_log_by` INT, OUT `p_city_id` INT)   BEGIN
    INSERT INTO city (city_name, state_id, last_log_by) 
	VALUES(p_city_name, p_state_id, p_last_log_by);
	
    SET p_city_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertCivilStatus` (IN `p_civil_status_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_civil_status_id` INT)   BEGIN
    INSERT INTO civil_status (civil_status_name, last_log_by) 
	VALUES(p_civil_status_name, p_last_log_by);
	
    SET p_civil_status_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertCompany` (IN `p_company_name` VARCHAR(100), IN `p_address` VARCHAR(1000), IN `p_city_id` INT, IN `p_tax_id` VARCHAR(500), IN `p_currency_id` INT, IN `p_phone` VARCHAR(20), IN `p_mobile` VARCHAR(20), IN `p_telephone` VARCHAR(20), IN `p_email` VARCHAR(100), IN `p_website` VARCHAR(500), IN `p_last_log_by` INT, OUT `p_company_id` INT)   BEGIN
    INSERT INTO company (company_name, address, city_id, tax_id, currency_id, phone, mobile, telephone, email, website, last_log_by) 
	VALUES(p_company_name, p_address, p_city_id, p_tax_id, p_currency_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
	
    SET p_company_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertCountry` (IN `p_country_name` VARCHAR(100), IN `p_country_code` VARCHAR(5), IN `p_phone_code` VARCHAR(20), IN `p_last_log_by` INT, OUT `p_country_id` INT)   BEGIN
    INSERT INTO country (country_name, country_code, phone_code, last_log_by) 
	VALUES(p_country_name, p_country_code, p_phone_code, p_last_log_by);
	
    SET p_country_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertCurrency` (IN `p_currency_name` VARCHAR(100), IN `p_symbol` VARCHAR(10), IN `p_shorthand` VARCHAR(10), IN `p_last_log_by` INT, OUT `p_currency_id` INT)   BEGIN
    INSERT INTO currency (currency_name, symbol, shorthand, last_log_by) 
	VALUES(p_currency_name, p_symbol, p_shorthand, p_last_log_by);
	
    SET p_currency_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertDepartment` (IN `p_department_name` VARCHAR(100), IN `p_parent_department` INT, IN `p_manager` INT, IN `p_last_log_by` INT, OUT `p_department_id` INT)   BEGIN
    INSERT INTO department (department_name, parent_department, manager, last_log_by) 
	VALUES(p_department_name, p_parent_department, p_manager, p_last_log_by);
	
    SET p_department_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertDepartureReason` (IN `p_departure_reason_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_departure_reason_id` INT)   BEGIN
    INSERT INTO departure_reason (departure_reason_name, last_log_by) 
	VALUES(p_departure_reason_name, p_last_log_by);
	
    SET p_departure_reason_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertEmailSetting` (IN `p_email_setting_name` VARCHAR(100), IN `p_email_setting_description` VARCHAR(200), IN `p_mail_host` VARCHAR(100), IN `p_port` INT, IN `p_smtp_auth` INT(1), IN `p_smtp_auto_tls` INT(1), IN `p_mail_username` VARCHAR(200), IN `p_mail_encryption` VARCHAR(20), IN `p_mail_from_name` VARCHAR(200), IN `p_mail_from_email` VARCHAR(200), IN `p_last_log_by` INT, OUT `p_email_setting_id` INT)   BEGIN
    INSERT INTO email_setting (email_setting_name, email_setting_description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_encryption, mail_from_name, mail_from_email, last_log_by) 
	VALUES(p_email_setting_name, p_email_setting_description, p_mail_host, p_port, p_smtp_auth, p_smtp_auto_tls, p_mail_username, p_mail_encryption, p_mail_from_name, p_mail_from_email, p_last_log_by);
	
    SET p_email_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertEmployeeType` (IN `p_employee_type_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_employee_type_id` INT)   BEGIN
    INSERT INTO employee_type (employee_type_name, last_log_by) 
	VALUES(p_employee_type_name, p_last_log_by);
	
    SET p_employee_type_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertGender` (IN `p_gender_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_gender_id` INT)   BEGIN
    INSERT INTO gender (gender_name, last_log_by) 
	VALUES(p_gender_name, p_last_log_by);
	
    SET p_gender_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertHolidayType` (IN `p_holiday_type_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_holiday_type_id` INT)   BEGIN
    INSERT INTO holiday_type (holiday_type_name, last_log_by) 
	VALUES(p_holiday_type_name, p_last_log_by);
	
    SET p_holiday_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertIDType` (IN `p_id_type_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_id_type_id` INT)   BEGIN
    INSERT INTO id_type (id_type_name, last_log_by) 
	VALUES(p_id_type_name, p_last_log_by);
	
    SET p_id_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertInterfaceSetting` (IN `p_interface_setting_name` VARCHAR(100), IN `p_interface_setting_description` VARCHAR(200), IN `p_last_log_by` INT, OUT `p_interface_setting_id` INT)   BEGIN
    INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) 
	VALUES(p_interface_setting_name, p_interface_setting_description, p_last_log_by);
	
    SET p_interface_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertJobLevel` (IN `p_current_level` VARCHAR(10), IN `p_rank` VARCHAR(100), IN `p_functional_level` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_job_level_id` INT)   BEGIN
    INSERT INTO job_level (current_level, rank, functional_level, last_log_by) 
	VALUES(p_current_level, p_rank, p_functional_level, p_last_log_by);
	
    SET p_job_level_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertJobPosition` (IN `p_job_position_name` VARCHAR(100), IN `p_job_position_description` VARCHAR(2000), IN `p_department_id` INT, IN `p_last_log_by` INT, OUT `p_job_position_id` INT)   BEGIN
    INSERT INTO job_position (job_position_name, job_position_description, department_id, last_log_by) 
	VALUES(p_job_position_name, p_job_position_description, p_department_id, p_last_log_by);
	
    SET p_job_position_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertJobPositionQualification` (IN `p_job_position_id` INT, IN `p_qualification` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
    INSERT INTO job_position_qualification (job_position_id, qualification, last_log_by) 
	VALUES(p_job_position_id, p_qualification, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertJobPositionRequirement` (IN `p_job_position_id` INT, IN `p_requirement` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
    INSERT INTO job_position_requirement (job_position_id, requirement, last_log_by) 
	VALUES(p_job_position_id, p_requirement, p_last_log_by);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertJobPositionResponsibility` (IN `p_job_position_id` INT, IN `p_responsibility` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
    INSERT INTO job_position_responsibility (job_position_id, responsibility, last_log_by) 
	VALUES(p_job_position_id, p_responsibility, p_last_log_by);
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertNationality` (IN `p_nationality_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_nationality_id` INT)   BEGIN
    INSERT INTO nationality (nationality_name, last_log_by) 
	VALUES(p_nationality_name, p_last_log_by);
	
    SET p_nationality_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertNotificationSetting` (IN `p_notification_setting_name` VARCHAR(100), IN `p_notification_setting_description` VARCHAR(200), IN `p_last_log_by` INT, OUT `p_notification_setting_id` INT)   BEGIN
    INSERT INTO notification_setting (notification_setting_name, notification_setting_description, last_log_by) 
	VALUES(p_notification_setting_name, p_notification_setting_description, p_last_log_by);
	
    SET p_notification_setting_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertPasswordHistory` (IN `p_user_id` INT, IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_last_password_change` DATETIME)   BEGIN
    INSERT INTO password_history (user_id, email, password, password_change_date) 
    VALUES (p_user_id, p_email, p_password, p_last_password_change);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertRelation` (IN `p_relation_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_relation_id` INT)   BEGIN
    INSERT INTO relation (relation_name, last_log_by) 
	VALUES(p_relation_name, p_last_log_by);
	
    SET p_relation_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertReligion` (IN `p_religion_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_religion_id` INT)   BEGIN
    INSERT INTO religion (religion_name, last_log_by) 
	VALUES(p_religion_name, p_last_log_by);
	
    SET p_religion_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertState` (IN `p_state_name` VARCHAR(100), IN `p_country_id` INT, IN `p_state_code` VARCHAR(5), IN `p_last_log_by` INT, OUT `p_state_id` INT)   BEGIN
    INSERT INTO state (state_name, country_id, state_code, last_log_by) 
	VALUES(p_state_name, p_country_id, p_state_code, p_last_log_by);
	
    SET p_state_id = LAST_INSERT_ID();
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
        INSERT INTO ui_customization_setting (user_id, theme_contrast, last_log_by) 
	    VALUES(p_user_id, p_customization_value, p_last_log_by);
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertWorkScheduleType` (IN `p_work_schedule_type_name` VARCHAR(100), IN `p_last_log_by` INT, OUT `p_work_schedule_type_id` INT)   BEGIN
    INSERT INTO work_schedule_type (work_schedule_type_name, last_log_by) 
	VALUES(p_work_schedule_type_name, p_last_log_by);
	
    SET p_work_schedule_type_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertZoomAPI` (IN `p_zoom_api_name` VARCHAR(100), IN `p_zoom_api_description` VARCHAR(200), IN `p_api_key` VARCHAR(1000), IN `p_api_secret` VARCHAR(1000), IN `p_last_log_by` INT, OUT `p_zoom_api_id` INT)   BEGIN
    INSERT INTO zoom_api (zoom_api_name, zoom_api_description, api_key, api_secret, last_log_by) 
	VALUES(p_zoom_api_name, p_zoom_api_description, p_api_key, p_api_secret, p_last_log_by);
	
    SET p_zoom_api_id = LAST_INSERT_ID();
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBank` (IN `p_bank_id` INT, IN `p_bank_name` VARCHAR(100), IN `p_bank_identifier_code` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE bank
    SET bank_name = p_bank_name,
    bank_identifier_code = p_bank_identifier_code,
    last_log_by = p_last_log_by
    WHERE bank_id = p_bank_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBloodType` (IN `p_blood_type_id` INT, IN `p_blood_type_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE blood_type
    SET blood_type_name = p_blood_type_name,
    last_log_by = p_last_log_by
    WHERE blood_type_id = p_blood_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBranch` (IN `p_branch_id` INT, IN `p_branch_name` VARCHAR(100), IN `p_address` VARCHAR(1000), IN `p_city_id` INT, IN `p_phone` VARCHAR(20), IN `p_mobile` VARCHAR(20), IN `p_telephone` VARCHAR(20), IN `p_email` VARCHAR(100), IN `p_website` VARCHAR(500), IN `p_last_log_by` INT)   BEGIN
	UPDATE branch
    SET branch_name = p_branch_name,
    branch_name = p_branch_name,
    address = p_address,
    city_id = p_city_id,
    phone = p_phone,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    website = p_website,
    last_log_by = p_last_log_by
    WHERE branch_id = p_branch_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCity` (IN `p_city_id` INT, IN `p_city_name` VARCHAR(100), IN `p_state_id` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE city
    SET city_name = p_city_name,
    state_id = p_state_id,
    last_log_by = p_last_log_by
    WHERE city_id = p_city_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCivilStatus` (IN `p_civil_status_id` INT, IN `p_civil_status_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE civil_status
    SET civil_status_name = p_civil_status_name,
    last_log_by = p_last_log_by
    WHERE civil_status_id = p_civil_status_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCompany` (IN `p_company_id` INT, IN `p_company_name` VARCHAR(100), IN `p_address` VARCHAR(1000), IN `p_city_id` INT, IN `p_tax_id` VARCHAR(500), IN `p_currency_id` INT, IN `p_phone` VARCHAR(20), IN `p_mobile` VARCHAR(20), IN `p_telephone` VARCHAR(20), IN `p_email` VARCHAR(100), IN `p_website` VARCHAR(500), IN `p_last_log_by` INT)   BEGIN
	UPDATE company
    SET company_name = p_company_name,
    company_name = p_company_name,
    address = p_address,
    city_id = p_city_id,
    tax_id = p_tax_id,
    currency_id = p_currency_id,
    phone = p_phone,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    website = p_website,
    last_log_by = p_last_log_by
    WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCompanyLogo` (IN `p_company_id` INT, IN `p_company_logo` VARCHAR(500), IN `p_last_log_by` INT)   BEGIN
	UPDATE company
    SET company_logo = p_company_logo,
    last_log_by = p_last_log_by
    WHERE company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCountry` (IN `p_country_id` INT, IN `p_country_name` VARCHAR(100), IN `p_country_code` VARCHAR(5), IN `p_phone_code` VARCHAR(20), IN `p_last_log_by` INT)   BEGIN
	UPDATE country
    SET country_name = p_country_name,
    country_code = p_country_code,
    phone_code = p_phone_code,
    last_log_by = p_last_log_by
    WHERE country_id = p_country_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCurrency` (IN `p_currency_id` INT, IN `p_currency_name` VARCHAR(100), IN `p_symbol` VARCHAR(10), IN `p_shorthand` VARCHAR(10), IN `p_last_log_by` INT)   BEGIN
	UPDATE currency
    SET currency_name = p_currency_name,
    symbol = p_symbol,
    shorthand = p_shorthand,
    last_log_by = p_last_log_by
    WHERE currency_id = p_currency_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateDepartment` (IN `p_department_id` INT, IN `p_department_name` VARCHAR(100), IN `p_parent_department` INT, IN `p_manager` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE department
    SET department_name = p_department_name,
    department_name = p_department_name,
    parent_department = p_parent_department,
    manager = p_manager,
    last_log_by = p_last_log_by
    WHERE department_id = p_department_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateDepartureReason` (IN `p_departure_reason_id` INT, IN `p_departure_reason_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE departure_reason
    SET departure_reason_name = p_departure_reason_name,
    last_log_by = p_last_log_by
    WHERE departure_reason_id = p_departure_reason_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateEmailSetting` (IN `p_email_setting_id` INT, IN `p_email_setting_name` VARCHAR(100), IN `p_email_setting_description` VARCHAR(200), IN `p_mail_host` VARCHAR(100), IN `p_port` INT, IN `p_smtp_auth` INT(1), IN `p_smtp_auto_tls` INT(1), IN `p_mail_username` VARCHAR(200), IN `p_mail_encryption` VARCHAR(20), IN `p_mail_from_name` VARCHAR(200), IN `p_mail_from_email` VARCHAR(200), IN `p_last_log_by` INT)   BEGIN
	UPDATE email_setting
    SET email_setting_name = p_email_setting_name,
    email_setting_description = p_email_setting_description,
    mail_host = p_mail_host,
    port = p_port,
    smtp_auth = p_smtp_auth,
    smtp_auto_tls = p_smtp_auto_tls,
    mail_username = p_mail_username,
    mail_encryption = p_mail_encryption,
    mail_from_name = p_mail_from_name,
    mail_from_email = p_mail_from_email,
    last_log_by = p_last_log_by
    WHERE email_setting_id = p_email_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateEmployeeType` (IN `p_employee_type_id` INT, IN `p_employee_type_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE employee_type
    SET employee_type_name = p_employee_type_name,
    last_log_by = p_last_log_by
    WHERE employee_type_id = p_employee_type_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateGender` (IN `p_gender_id` INT, IN `p_gender_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE gender
    SET gender_name = p_gender_name,
    last_log_by = p_last_log_by
    WHERE gender_id = p_gender_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateHolidayType` (IN `p_holiday_type_id` INT, IN `p_holiday_type_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE holiday_type
    SET holiday_type_name = p_holiday_type_name,
    last_log_by = p_last_log_by
    WHERE holiday_type_id = p_holiday_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateIDType` (IN `p_id_type_id` INT, IN `p_id_type_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE id_type
    SET id_type_name = p_id_type_name,
    last_log_by = p_last_log_by
    WHERE id_type_id = p_id_type_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateJobLevel` (IN `p_job_level_id` INT, IN `p_current_level` VARCHAR(10), IN `p_rank` VARCHAR(100), IN `p_functional_level` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE job_level
    SET current_level = p_current_level,
    rank = p_rank,
    functional_level = p_functional_level,
    last_log_by = p_last_log_by
    WHERE job_level_id = p_job_level_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateJobPosition` (IN `p_job_position_id` INT, IN `p_job_position_name` VARCHAR(100), IN `p_job_position_description` VARCHAR(2000), IN `p_department_id` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE job_position
    SET job_position_name = p_job_position_name,
    job_position_description = p_job_position_description,
    department_id = p_department_id,
    last_log_by = p_last_log_by
    WHERE job_position_id = p_job_position_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateJobPositionQualification` (IN `p_job_position_qualification_id` INT, IN `p_qualification` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
	UPDATE job_position_qualification
    SET qualification = p_qualification,
    last_log_by = p_last_log_by
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateJobPositionRecruitmentStatus` (IN `p_job_position_id` INT, IN `p_recruitment_status` TINYINT(1), IN `p_expected_new_employees` INT, IN `p_last_log_by` INT)   BEGIN
	UPDATE job_position
    SET recruitment_status = p_recruitment_status,
    expected_new_employees = p_expected_new_employees,
    last_log_by = p_last_log_by
    WHERE job_position_id = p_job_position_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateJobPositionRequirement` (IN `p_job_position_requirement_id` INT, IN `p_requirement` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
	UPDATE job_position_requirement
    SET requirement = p_requirement,
    last_log_by = p_last_log_by
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateJobPositionResponsibility` (IN `p_job_position_responsibility_id` INT, IN `p_responsibility` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
	UPDATE job_position_responsibility
    SET responsibility = p_responsibility,
    last_log_by = p_last_log_by
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateMailPassword` (IN `p_email_setting_id` INT, IN `p_mail_password` VARCHAR(250), IN `p_last_log_by` INT)   BEGIN
	UPDATE email_setting
    SET mail_password = p_mail_password,
    last_log_by = p_last_log_by
    WHERE email_setting_id = p_email_setting_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateNationality` (IN `p_nationality_id` INT, IN `p_nationality_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE nationality
    SET nationality_name = p_nationality_name,
    last_log_by = p_last_log_by
    WHERE nationality_id = p_nationality_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateNotificationChannelStatus` (IN `p_notification_setting_id` INT, IN `p_channel` VARCHAR(10), IN `p_status` TINYINT(1), IN `p_last_log_by` INT)   BEGIN
    IF p_channel = 'system' THEN
        UPDATE notification_setting
        SET system_notification = p_status,
        last_log_by = p_last_log_by
        WHERE notification_setting_id = p_notification_setting_id;
    ELSEIF p_channel = 'email' THEN
        UPDATE notification_setting
        SET email_notification = p_status,
        last_log_by = p_last_log_by
        WHERE notification_setting_id = p_notification_setting_id;
    ELSE
        UPDATE notification_setting
        SET sms_notification = p_status,
        last_log_by = p_last_log_by
        WHERE notification_setting_id = p_notification_setting_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateNotificationSetting` (IN `p_notification_setting_id` INT, IN `p_notification_setting_name` VARCHAR(100), IN `p_notification_setting_description` VARCHAR(200), IN `p_last_log_by` INT)   BEGIN
	UPDATE notification_setting
    SET notification_setting_name = p_notification_setting_name,
    notification_setting_description = p_notification_setting_description,
    last_log_by = p_last_log_by
    WHERE notification_setting_id = p_notification_setting_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateNotificationSettingTemplate` (IN `p_notification_setting_id` INT, IN `p_system_notification_title` VARCHAR(200), IN `p_system_notification_message` VARCHAR(200), IN `p_email_notification_subject` VARCHAR(200), IN `p_email_notification_body` LONGTEXT, IN `p_sms_notification_message` VARCHAR(500), IN `p_last_log_by` INT)   BEGIN

    IF p_system_notification_title IS NOT NULL AND p_system_notification_message IS NOT NULL THEN
        UPDATE notification_setting
        SET system_notification_title = p_system_notification_title,
        system_notification_message = p_system_notification_message,
        last_log_by = p_last_log_by
        WHERE notification_setting_id = p_notification_setting_id;
    ELSEIF p_email_notification_subject IS NOT NULL AND p_email_notification_body IS NOT NULL THEN
        UPDATE notification_setting
        SET email_notification_subject = p_email_notification_subject,
        email_notification_body = p_email_notification_body,
        last_log_by = p_last_log_by
        WHERE notification_setting_id = p_notification_setting_id;
    ELSE
       UPDATE notification_setting
        SET sms_notification_message = p_sms_notification_message,
        last_log_by = p_last_log_by
        WHERE notification_setting_id = p_notification_setting_id;
    END IF;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateRelation` (IN `p_relation_id` INT, IN `p_relation_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE relation
    SET relation_name = p_relation_name,
    last_log_by = p_last_log_by
    WHERE relation_id = p_relation_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateReligion` (IN `p_religion_id` INT, IN `p_religion_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE religion
    SET religion_name = p_religion_name,
    last_log_by = p_last_log_by
    WHERE religion_id = p_religion_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateState` (IN `p_state_id` INT, IN `p_state_name` VARCHAR(100), IN `p_country_id` INT, IN `p_state_code` VARCHAR(5), IN `p_last_log_by` INT)   BEGIN
	UPDATE state
    SET state_name = p_state_name,
    country_id = p_country_id,
    state_code = p_state_code,
    last_log_by = p_last_log_by
    WHERE state_id = p_state_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUserNotificationSetting` (IN `p_user_id` INT, IN `p_receive_notification` TINYINT(1), IN `p_last_log_by` INT)   BEGIN
	UPDATE users 
    SET receive_notification = p_receive_notification, last_log_by = p_last_log_by 
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateWorkScheduleType` (IN `p_work_schedule_type_id` INT, IN `p_work_schedule_type_name` VARCHAR(100), IN `p_last_log_by` INT)   BEGIN
	UPDATE work_schedule_type
    SET work_schedule_type_name = p_work_schedule_type_name,
    last_log_by = p_last_log_by
    WHERE work_schedule_type_id = p_work_schedule_type_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateZoomAPI` (IN `p_zoom_api_id` INT, IN `p_zoom_api_name` VARCHAR(100), IN `p_zoom_api_description` VARCHAR(200), IN `p_api_key` VARCHAR(1000), IN `p_api_secret` VARCHAR(1000), IN `p_last_log_by` INT)   BEGIN
	UPDATE zoom_api
    SET zoom_api_name = p_zoom_api_name,
    zoom_api_description = p_zoom_api_description,
    api_key = p_api_key,
    api_secret = p_api_secret,
    last_log_by = p_last_log_by
    WHERE zoom_api_id = p_zoom_api_id;
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
(1, 'users', 1, 'User created. <br/><br/>File As: Administrator<br/>Email: ldagulto@encorefinancials.com<br/>Is Active: 1<br/>Password Expiry Date: 2023-12-30', '0', '2023-09-11 16:57:40'),
(2, 'role', 1, 'Role created. <br/><br/>Role Name: Super Admin<br/>Role Description: This role has the highest level of access and full control over the entire system. Super Admins can perform all actions, including managing other user accounts, configuring system settings, and access', '0', '2023-09-11 16:57:40'),
(3, 'role', 2, 'Role created. <br/><br/>Role Name: Administrator<br/>Role Description: Full access to all features and data within the system. This role have similar access levels to the Admin but is not as powerful as the Super Admin.<br/>Assignable: 1', '0', '2023-09-11 16:57:40'),
(4, 'role', 3, 'Role created. <br/><br/>Role Name: Manager<br/>Role Description: Access to manage specific aspects of the system or resources related to their teams or departments.<br/>Assignable: 1', '0', '2023-09-11 16:57:40'),
(5, 'role', 4, 'Role created. <br/><br/>Role Name: Employee<br/>Role Description: The typical user account with standard access to use the system features and functionalities.<br/>Assignable: 1', '0', '2023-09-11 16:57:40'),
(6, 'menu_group', 1, 'Menu group created. <br/><br/>Menu Group Name: Human Resources<br/>Order Sequence: 40', '0', '2023-09-11 16:57:40'),
(7, 'menu_group', 2, 'Menu group created. <br/><br/>Menu Group Name: Administration<br/>Order Sequence: 90', '0', '2023-09-11 16:57:40'),
(8, 'menu_group', 3, 'Menu group created. <br/><br/>Menu Group Name: Technical<br/>Order Sequence: 100', '0', '2023-09-11 16:57:40'),
(9, 'menu_item', 1, 'Menu item created. <br/><br/>Menu Item Name: Users & Companies<br/>Menu Group ID: 2<br/>Menu Item Icon: users<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(10, 'menu_item', 2, 'Menu item created. <br/><br/>Menu Item Name: Company<br/>Menu Group ID: 2<br/>URL: company.php<br/>Parent ID: 1<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(11, 'menu_item', 3, 'Menu item created. <br/><br/>Menu Item Name: User Account<br/>Menu Group ID: 2<br/>URL: user-account.php<br/>Parent ID: 1<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(12, 'menu_item_access_right', 1, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-09-11 16:57:40'),
(13, 'menu_item_access_right', 2, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(14, 'menu_item_access_right', 3, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(15, 'menu_item', 4, 'Menu item created. <br/><br/>Menu Item Name: Roles<br/>Menu Group ID: 2<br/>Menu Item Icon: shield<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(16, 'menu_item', 5, 'Menu item created. <br/><br/>Menu Item Name: Role<br/>Menu Group ID: 2<br/>URL: role.php<br/>Parent ID: 4<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(17, 'menu_item', 6, 'Menu item created. <br/><br/>Menu Item Name: Role Configuration<br/>Menu Group ID: 2<br/>URL: role-configuration.php<br/>Parent ID: 4<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(18, 'menu_item_access_right', 4, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-09-11 16:57:40'),
(19, 'menu_item_access_right', 5, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(20, 'menu_item_access_right', 6, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(21, 'menu_item', 7, 'Menu item created. <br/><br/>Menu Item Name: User Interface<br/>Menu Group ID: 3<br/>Menu Item Icon: layout<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(22, 'menu_item', 8, 'Menu item created. <br/><br/>Menu Item Name: Menu Group<br/>Menu Group ID: 3<br/>URL: menu-group.php<br/>Parent ID: 7<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(23, 'menu_item', 9, 'Menu item created. <br/><br/>Menu Item Name: Menu Item<br/>Menu Group ID: 3<br/>URL: menu-item.php<br/>Parent ID: 7<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(24, 'menu_item', 10, 'Menu item created. <br/><br/>Menu Item Name: System Action<br/>Menu Group ID: 3<br/>URL: system-action.php<br/>Parent ID: 7<br/>Order Sequence: 3', '0', '2023-09-11 16:57:40'),
(25, 'menu_item_access_right', 7, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-09-11 16:57:40'),
(26, 'menu_item_access_right', 8, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(27, 'menu_item_access_right', 9, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(28, 'menu_item_access_right', 10, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(29, 'menu_item', 11, 'Menu item created. <br/><br/>Menu Item Name: Configurations<br/>Menu Group ID: 3<br/>Menu Item Icon: settings<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(30, 'menu_item', 12, 'Menu item created. <br/><br/>Menu Item Name: Email Setting<br/>Menu Group ID: 3<br/>URL: email-setting.php<br/>Parent ID: 11<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(31, 'menu_item', 13, 'Menu item created. <br/><br/>Menu Item Name: File Type<br/>Menu Group ID: 3<br/>URL: file-type.php<br/>Parent ID: 11<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(32, 'menu_item', 14, 'Menu item created. <br/><br/>Menu Item Name: File Extension<br/>Menu Group ID: 3<br/>URL: file-extension.php<br/>Parent ID: 11<br/>Order Sequence: 3', '0', '2023-09-11 16:57:40'),
(33, 'menu_item', 15, 'Menu item created. <br/><br/>Menu Item Name: Interface Setting<br/>Menu Group ID: 3<br/>URL: interface-setting.php<br/>Parent ID: 11<br/>Order Sequence: 4', '0', '2023-09-11 16:57:40'),
(34, 'menu_item', 16, 'Menu item created. <br/><br/>Menu Item Name: Notification Setting<br/>Menu Group ID: 3<br/>URL: notification-setting.php<br/>Parent ID: 11<br/>Order Sequence: 5', '0', '2023-09-11 16:57:40'),
(35, 'menu_item', 17, 'Menu item created. <br/><br/>Menu Item Name: System Setting<br/>Menu Group ID: 3<br/>URL: system-setting.php<br/>Parent ID: 11<br/>Order Sequence: 6', '0', '2023-09-11 16:57:40'),
(36, 'menu_item', 18, 'Menu item created. <br/><br/>Menu Item Name: Upload Setting<br/>Menu Group ID: 3<br/>URL: upload-setting.php<br/>Parent ID: 11<br/>Order Sequence: 7', '0', '2023-09-11 16:57:40'),
(37, 'menu_item', 19, 'Menu item created. <br/><br/>Menu Item Name: Zoom API<br/>Menu Group ID: 3<br/>URL: zoom-api.php<br/>Parent ID: 11<br/>Order Sequence: 8', '0', '2023-09-11 16:57:40'),
(38, 'menu_item_access_right', 11, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-09-11 16:57:40'),
(39, 'menu_item_access_right', 12, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(40, 'menu_item_access_right', 13, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(41, 'menu_item_access_right', 14, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '1', '2023-09-11 16:57:40'),
(42, 'menu_item_access_right', 15, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(43, 'menu_item_access_right', 16, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(44, 'menu_item_access_right', 17, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(45, 'menu_item_access_right', 18, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(46, 'menu_item_access_right', 19, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(47, 'menu_item', 20, 'Menu item created. <br/><br/>Menu Item Name: Localization<br/>Menu Group ID: 3<br/>Menu Item Icon: globe<br/>Order Sequence: 3', '0', '2023-09-11 16:57:40'),
(48, 'menu_item', 21, 'Menu item created. <br/><br/>Menu Item Name: City<br/>Menu Group ID: 3<br/>URL: city.php<br/>Parent ID: 20<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(49, 'menu_item', 22, 'Menu item created. <br/><br/>Menu Item Name: Country<br/>Menu Group ID: 3<br/>URL: country.php<br/>Parent ID: 20<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(50, 'menu_item', 23, 'Menu item created. <br/><br/>Menu Item Name: Currency<br/>Menu Group ID: 3<br/>URL: currency.php<br/>Parent ID: 20<br/>Order Sequence: 3', '0', '2023-09-11 16:57:40'),
(51, 'menu_item', 24, 'Menu item created. <br/><br/>Menu Item Name: State<br/>Menu Group ID: 3<br/>URL: state.php<br/>Parent ID: 20<br/>Order Sequence: 4', '0', '2023-09-11 16:57:40'),
(52, 'menu_item_access_right', 20, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-09-11 16:57:40'),
(53, 'menu_item_access_right', 21, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(54, 'menu_item_access_right', 22, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(55, 'menu_item_access_right', 23, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(56, 'menu_item_access_right', 24, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(57, 'menu_item', 25, 'Menu item created. <br/><br/>Menu Item Name: Configurations<br/>Menu Group ID: 1<br/>Menu Item Icon: settings<br/>Order Sequence: 20', '0', '2023-09-11 16:57:40'),
(58, 'menu_item', 26, 'Menu item created. <br/><br/>Menu Item Name: Branch<br/>Menu Group ID: 1<br/>URL: branch.php<br/>Parent ID: 25<br/>Order Sequence: 1', '0', '2023-09-11 16:57:40'),
(59, 'menu_item', 27, 'Menu item created. <br/><br/>Menu Item Name: Department<br/>Menu Group ID: 1<br/>URL: department.php<br/>Parent ID: 25<br/>Order Sequence: 2', '0', '2023-09-11 16:57:40'),
(60, 'menu_item', 28, 'Menu item created. <br/><br/>Menu Item Name: Job Position<br/>Menu Group ID: 1<br/>URL: job-position.php<br/>Parent ID: 25<br/>Order Sequence: 3', '0', '2023-09-11 16:57:40'),
(61, 'menu_item_access_right', 25, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1', '0', '2023-09-11 16:57:40'),
(62, 'menu_item_access_right', 26, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(63, 'menu_item_access_right', 27, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(64, 'menu_item_access_right', 28, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-11 16:57:40'),
(65, 'system_action', 1, 'System action created. <br/><br/>System Action Name: Update Menu Item Role Access', '0', '2023-09-11 16:57:40'),
(66, 'system_action', 2, 'System action created. <br/><br/>System Action Name: Delete Menu Item Role Access', '0', '2023-09-11 16:57:40'),
(67, 'system_action', 3, 'System action created. <br/><br/>System Action Name: Update System Action Role Access', '0', '2023-09-11 16:57:40'),
(68, 'system_action', 4, 'System action created. <br/><br/>System Action Name: Delete System Action Role Access', '0', '2023-09-11 16:57:40'),
(69, 'system_action', 5, 'System action created. <br/><br/>System Action Name: Assign User Account To Role', '0', '2023-09-11 16:57:40'),
(70, 'system_action', 6, 'System action created. <br/><br/>System Action Name: Delete User Account To Role', '0', '2023-09-11 16:57:40'),
(71, 'system_action', 7, 'System action created. <br/><br/>System Action Name: Assign Role To User Account', '0', '2023-09-11 16:57:40'),
(72, 'system_action', 8, 'System action created. <br/><br/>System Action Name: Delete Role To User Account', '0', '2023-09-11 16:57:40'),
(73, 'system_action', 9, 'System action created. <br/><br/>System Action Name: Activate User Account', '0', '2023-09-11 16:57:40'),
(74, 'system_action', 10, 'System action created. <br/><br/>System Action Name: Deactivate User Account', '0', '2023-09-11 16:57:40'),
(75, 'system_action', 11, 'System action created. <br/><br/>System Action Name: Lock User Account', '0', '2023-09-11 16:57:40'),
(76, 'system_action', 12, 'System action created. <br/><br/>System Action Name: Unlock User Account', '0', '2023-09-11 16:57:40'),
(77, 'system_action', 13, 'System action created. <br/><br/>System Action Name: Change User Account Password', '0', '2023-09-11 16:57:40'),
(78, 'system_action', 14, 'System action created. <br/><br/>System Action Name: Change User Account Profile Picture', '0', '2023-09-11 16:57:40'),
(79, 'system_action', 15, 'System action created. <br/><br/>System Action Name: Assign File Extension To Upload Setting', '0', '2023-09-11 16:57:40'),
(80, 'system_action', 16, 'System action created. <br/><br/>System Action Name: Delete File Extension To Upload Setting', '0', '2023-09-11 16:57:40'),
(81, 'system_action', 17, 'System action created. <br/><br/>System Action Name: Send Reset Password Instructions', '0', '2023-09-11 16:57:40'),
(82, 'system_action', 18, 'System action created. <br/><br/>System Action Name: Add Job Position Responsibility', '0', '2023-09-11 16:57:40'),
(83, 'system_action', 19, 'System action created. <br/><br/>System Action Name: Update Job Position Responsibility', '0', '2023-09-11 16:57:40'),
(84, 'system_action', 20, 'System action created. <br/><br/>System Action Name: Delete Job Position Responsibility', '0', '2023-09-11 16:57:40'),
(85, 'system_action', 21, 'System action created. <br/><br/>System Action Name: Add Job Position Requirement', '0', '2023-09-11 16:57:40'),
(86, 'system_action', 22, 'System action created. <br/><br/>System Action Name: Update Job Position Requirement', '0', '2023-09-11 16:57:40'),
(87, 'system_action', 23, 'System action created. <br/><br/>System Action Name: Delete Job Position Requirement', '0', '2023-09-11 16:57:40'),
(88, 'system_action', 24, 'System action created. <br/><br/>System Action Name: Add Job Position Qualification', '0', '2023-09-11 16:57:40'),
(89, 'system_action', 25, 'System action created. <br/><br/>System Action Name: Update Job Position Qualification', '0', '2023-09-11 16:57:40'),
(90, 'system_action', 26, 'System action created. <br/><br/>System Action Name: Delete Job Position Qualification', '0', '2023-09-11 16:57:40'),
(91, 'system_action', 27, 'System action created. <br/><br/>System Action Name: Start Job Position Recruitment', '0', '2023-09-11 16:57:40'),
(92, 'system_action', 28, 'System action created. <br/><br/>System Action Name: Stop Job Position Recruitment', '0', '2023-09-11 16:57:40'),
(93, 'system_action_access_rights', 1, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(94, 'system_action_access_rights', 2, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(95, 'system_action_access_rights', 3, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(96, 'system_action_access_rights', 4, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(97, 'system_action_access_rights', 5, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(98, 'system_action_access_rights', 6, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(99, 'system_action_access_rights', 7, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(100, 'system_action_access_rights', 8, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(101, 'system_action_access_rights', 9, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(102, 'system_action_access_rights', 10, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(103, 'system_action_access_rights', 11, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(104, 'system_action_access_rights', 12, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(105, 'system_action_access_rights', 13, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(106, 'system_action_access_rights', 14, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(107, 'system_action_access_rights', 15, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(108, 'system_action_access_rights', 16, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(109, 'system_action_access_rights', 17, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(110, 'system_action_access_rights', 18, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(111, 'system_action_access_rights', 19, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(112, 'system_action_access_rights', 20, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(113, 'system_action_access_rights', 21, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(114, 'system_action_access_rights', 22, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(115, 'system_action_access_rights', 23, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(116, 'system_action_access_rights', 24, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(117, 'system_action_access_rights', 25, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(118, 'system_action_access_rights', 26, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(119, 'system_action_access_rights', 27, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(120, 'system_action_access_rights', 28, 'System action access rights created. <br/><br/>Role ID: 1<br/>Role Access: 1', '0', '2023-09-11 16:57:40'),
(121, 'file_type', 1, 'System action created. <br/><br/>File Type Name: Audio', '0', '2023-09-11 16:57:40'),
(122, 'file_type', 2, 'System action created. <br/><br/>File Type Name: Compressed', '0', '2023-09-11 16:57:40'),
(123, 'file_type', 3, 'System action created. <br/><br/>File Type Name: Disk and Media', '0', '2023-09-11 16:57:40'),
(124, 'file_type', 4, 'System action created. <br/><br/>File Type Name: Data and Database', '0', '2023-09-11 16:57:40'),
(125, 'file_type', 5, 'System action created. <br/><br/>File Type Name: Email', '0', '2023-09-11 16:57:40'),
(126, 'file_type', 6, 'System action created. <br/><br/>File Type Name: Executable', '0', '2023-09-11 16:57:40'),
(127, 'file_type', 7, 'System action created. <br/><br/>File Type Name: Font', '0', '2023-09-11 16:57:40'),
(128, 'file_type', 8, 'System action created. <br/><br/>File Type Name: Image', '0', '2023-09-11 16:57:40'),
(129, 'file_type', 9, 'System action created. <br/><br/>File Type Name: Internet Related', '0', '2023-09-11 16:57:40'),
(130, 'file_type', 10, 'System action created. <br/><br/>File Type Name: Presentation', '0', '2023-09-11 16:57:40'),
(131, 'file_type', 11, 'System action created. <br/><br/>File Type Name: Spreadsheet', '0', '2023-09-11 16:57:40'),
(132, 'file_type', 12, 'System action created. <br/><br/>File Type Name: System Related', '0', '2023-09-11 16:57:40'),
(133, 'file_type', 13, 'System action created. <br/><br/>File Type Name: Video', '0', '2023-09-11 16:57:40'),
(134, 'file_type', 14, 'System action created. <br/><br/>File Type Name: Word Processor', '0', '2023-09-11 16:57:40'),
(135, 'file_extension', 1, 'File extension created. <br/><br/>File Exension Name: aif<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(136, 'file_extension', 2, 'File extension created. <br/><br/>File Exension Name: cda<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(137, 'file_extension', 3, 'File extension created. <br/><br/>File Exension Name: mid<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(138, 'file_extension', 4, 'File extension created. <br/><br/>File Exension Name: midi<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(139, 'file_extension', 5, 'File extension created. <br/><br/>File Exension Name: mp3<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(140, 'file_extension', 6, 'File extension created. <br/><br/>File Exension Name: mpa<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(141, 'file_extension', 7, 'File extension created. <br/><br/>File Exension Name: ogg<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(142, 'file_extension', 8, 'File extension created. <br/><br/>File Exension Name: wav<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(143, 'file_extension', 9, 'File extension created. <br/><br/>File Exension Name: wma<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(144, 'file_extension', 10, 'File extension created. <br/><br/>File Exension Name: wpl<br/>File Type ID: 1', '0', '2023-09-11 16:57:40'),
(145, 'file_extension', 11, 'File extension created. <br/><br/>File Exension Name: 7z<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(146, 'file_extension', 12, 'File extension created. <br/><br/>File Exension Name: arj<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(147, 'file_extension', 13, 'File extension created. <br/><br/>File Exension Name: deb<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(148, 'file_extension', 14, 'File extension created. <br/><br/>File Exension Name: pkg<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(149, 'file_extension', 15, 'File extension created. <br/><br/>File Exension Name: rar<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(150, 'file_extension', 16, 'File extension created. <br/><br/>File Exension Name: rpm<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(151, 'file_extension', 17, 'File extension created. <br/><br/>File Exension Name: tar.gz<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(152, 'file_extension', 18, 'File extension created. <br/><br/>File Exension Name: z<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(153, 'file_extension', 19, 'File extension created. <br/><br/>File Exension Name: zip<br/>File Type ID: 2', '0', '2023-09-11 16:57:40'),
(154, 'file_extension', 20, 'File extension created. <br/><br/>File Exension Name: bin<br/>File Type ID: 3', '0', '2023-09-11 16:57:40'),
(155, 'file_extension', 21, 'File extension created. <br/><br/>File Exension Name: dmg<br/>File Type ID: 3', '0', '2023-09-11 16:57:40'),
(156, 'file_extension', 22, 'File extension created. <br/><br/>File Exension Name: iso<br/>File Type ID: 3', '0', '2023-09-11 16:57:40'),
(157, 'file_extension', 23, 'File extension created. <br/><br/>File Exension Name: toast<br/>File Type ID: 3', '0', '2023-09-11 16:57:40'),
(158, 'file_extension', 24, 'File extension created. <br/><br/>File Exension Name: vcd<br/>File Type ID: 3', '0', '2023-09-11 16:57:40'),
(159, 'file_extension', 25, 'File extension created. <br/><br/>File Exension Name: csv<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(160, 'file_extension', 26, 'File extension created. <br/><br/>File Exension Name: dat<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(161, 'file_extension', 27, 'File extension created. <br/><br/>File Exension Name: db<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(162, 'file_extension', 28, 'File extension created. <br/><br/>File Exension Name: dbf<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(163, 'file_extension', 29, 'File extension created. <br/><br/>File Exension Name: log<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(164, 'file_extension', 30, 'File extension created. <br/><br/>File Exension Name: mdb<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(165, 'file_extension', 31, 'File extension created. <br/><br/>File Exension Name: sav<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(166, 'file_extension', 32, 'File extension created. <br/><br/>File Exension Name: sql<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(167, 'file_extension', 33, 'File extension created. <br/><br/>File Exension Name: tar<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(168, 'file_extension', 34, 'File extension created. <br/><br/>File Exension Name: xml<br/>File Type ID: 4', '0', '2023-09-11 16:57:40'),
(169, 'file_extension', 35, 'File extension created. <br/><br/>File Exension Name: email<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(170, 'file_extension', 36, 'File extension created. <br/><br/>File Exension Name: eml<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(171, 'file_extension', 37, 'File extension created. <br/><br/>File Exension Name: emlx<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(172, 'file_extension', 38, 'File extension created. <br/><br/>File Exension Name: msg<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(173, 'file_extension', 39, 'File extension created. <br/><br/>File Exension Name: oft<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(174, 'file_extension', 40, 'File extension created. <br/><br/>File Exension Name: ost<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(175, 'file_extension', 41, 'File extension created. <br/><br/>File Exension Name: pst<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(176, 'file_extension', 42, 'File extension created. <br/><br/>File Exension Name: vcf<br/>File Type ID: 5', '0', '2023-09-11 16:57:40'),
(177, 'file_extension', 43, 'File extension created. <br/><br/>File Exension Name: apk<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(178, 'file_extension', 44, 'File extension created. <br/><br/>File Exension Name: bat<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(179, 'file_extension', 45, 'File extension created. <br/><br/>File Exension Name: bin<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(180, 'file_extension', 46, 'File extension created. <br/><br/>File Exension Name: cgi<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(181, 'file_extension', 47, 'File extension created. <br/><br/>File Exension Name: pl<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(182, 'file_extension', 48, 'File extension created. <br/><br/>File Exension Name: com<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(183, 'file_extension', 49, 'File extension created. <br/><br/>File Exension Name: exe<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(184, 'file_extension', 50, 'File extension created. <br/><br/>File Exension Name: gadget<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(185, 'file_extension', 51, 'File extension created. <br/><br/>File Exension Name: jar<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(186, 'file_extension', 52, 'File extension created. <br/><br/>File Exension Name: wsf<br/>File Type ID: 6', '0', '2023-09-11 16:57:40'),
(187, 'file_extension', 53, 'File extension created. <br/><br/>File Exension Name: fnt<br/>File Type ID: 7', '0', '2023-09-11 16:57:40'),
(188, 'file_extension', 54, 'File extension created. <br/><br/>File Exension Name: fon<br/>File Type ID: 7', '0', '2023-09-11 16:57:40'),
(189, 'file_extension', 55, 'File extension created. <br/><br/>File Exension Name: otf<br/>File Type ID: 7', '0', '2023-09-11 16:57:40'),
(190, 'file_extension', 56, 'File extension created. <br/><br/>File Exension Name: ttf<br/>File Type ID: 7', '0', '2023-09-11 16:57:40'),
(191, 'file_extension', 57, 'File extension created. <br/><br/>File Exension Name: ai<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(192, 'file_extension', 58, 'File extension created. <br/><br/>File Exension Name: bmp<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(193, 'file_extension', 59, 'File extension created. <br/><br/>File Exension Name: gif<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(194, 'file_extension', 60, 'File extension created. <br/><br/>File Exension Name: ico<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(195, 'file_extension', 61, 'File extension created. <br/><br/>File Exension Name: jpg<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(196, 'file_extension', 62, 'File extension created. <br/><br/>File Exension Name: jpeg<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(197, 'file_extension', 63, 'File extension created. <br/><br/>File Exension Name: png<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(198, 'file_extension', 64, 'File extension created. <br/><br/>File Exension Name: ps<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(199, 'file_extension', 65, 'File extension created. <br/><br/>File Exension Name: psd<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(200, 'file_extension', 66, 'File extension created. <br/><br/>File Exension Name: svg<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(201, 'file_extension', 67, 'File extension created. <br/><br/>File Exension Name: tif<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(202, 'file_extension', 68, 'File extension created. <br/><br/>File Exension Name: tiff<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(203, 'file_extension', 69, 'File extension created. <br/><br/>File Exension Name: webp<br/>File Type ID: 8', '0', '2023-09-11 16:57:40'),
(204, 'file_extension', 70, 'File extension created. <br/><br/>File Exension Name: asp<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(205, 'file_extension', 71, 'File extension created. <br/><br/>File Exension Name: aspx<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(206, 'file_extension', 72, 'File extension created. <br/><br/>File Exension Name: cer<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(207, 'file_extension', 73, 'File extension created. <br/><br/>File Exension Name: cfm<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(208, 'file_extension', 74, 'File extension created. <br/><br/>File Exension Name: cgi<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(209, 'file_extension', 75, 'File extension created. <br/><br/>File Exension Name: pl<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(210, 'file_extension', 76, 'File extension created. <br/><br/>File Exension Name: css<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(211, 'file_extension', 77, 'File extension created. <br/><br/>File Exension Name: htm<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(212, 'file_extension', 78, 'File extension created. <br/><br/>File Exension Name: html<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(213, 'file_extension', 79, 'File extension created. <br/><br/>File Exension Name: js<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(214, 'file_extension', 80, 'File extension created. <br/><br/>File Exension Name: jsp<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(215, 'file_extension', 81, 'File extension created. <br/><br/>File Exension Name: part<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(216, 'file_extension', 82, 'File extension created. <br/><br/>File Exension Name: php<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(217, 'file_extension', 83, 'File extension created. <br/><br/>File Exension Name: py<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(218, 'file_extension', 84, 'File extension created. <br/><br/>File Exension Name: rss<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(219, 'file_extension', 85, 'File extension created. <br/><br/>File Exension Name: xhtml<br/>File Type ID: 9', '0', '2023-09-11 16:57:40'),
(220, 'file_extension', 86, 'File extension created. <br/><br/>File Exension Name: key<br/>File Type ID: 10', '0', '2023-09-11 16:57:40'),
(221, 'file_extension', 87, 'File extension created. <br/><br/>File Exension Name: odp<br/>File Type ID: 10', '0', '2023-09-11 16:57:40'),
(222, 'file_extension', 88, 'File extension created. <br/><br/>File Exension Name: pps<br/>File Type ID: 10', '0', '2023-09-11 16:57:40'),
(223, 'file_extension', 89, 'File extension created. <br/><br/>File Exension Name: ppt<br/>File Type ID: 10', '0', '2023-09-11 16:57:40'),
(224, 'file_extension', 90, 'File extension created. <br/><br/>File Exension Name: pptx<br/>File Type ID: 10', '0', '2023-09-11 16:57:40'),
(225, 'file_extension', 91, 'File extension created. <br/><br/>File Exension Name: ods<br/>File Type ID: 11', '0', '2023-09-11 16:57:40'),
(226, 'file_extension', 92, 'File extension created. <br/><br/>File Exension Name: xls<br/>File Type ID: 11', '0', '2023-09-11 16:57:40'),
(227, 'file_extension', 93, 'File extension created. <br/><br/>File Exension Name: xlsm<br/>File Type ID: 11', '0', '2023-09-11 16:57:40'),
(228, 'file_extension', 94, 'File extension created. <br/><br/>File Exension Name: xlsx<br/>File Type ID: 11', '0', '2023-09-11 16:57:40'),
(229, 'file_extension', 95, 'File extension created. <br/><br/>File Exension Name: bak<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(230, 'file_extension', 96, 'File extension created. <br/><br/>File Exension Name: cab<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(231, 'file_extension', 97, 'File extension created. <br/><br/>File Exension Name: cfg<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(232, 'file_extension', 98, 'File extension created. <br/><br/>File Exension Name: cpl<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(233, 'file_extension', 99, 'File extension created. <br/><br/>File Exension Name: cur<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(234, 'file_extension', 100, 'File extension created. <br/><br/>File Exension Name: dll<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(235, 'file_extension', 101, 'File extension created. <br/><br/>File Exension Name: dmp<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(236, 'file_extension', 102, 'File extension created. <br/><br/>File Exension Name: drv<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(237, 'file_extension', 103, 'File extension created. <br/><br/>File Exension Name: icns<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(238, 'file_extension', 104, 'File extension created. <br/><br/>File Exension Name: ini<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(239, 'file_extension', 105, 'File extension created. <br/><br/>File Exension Name: lnk<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(240, 'file_extension', 106, 'File extension created. <br/><br/>File Exension Name: msi<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(241, 'file_extension', 107, 'File extension created. <br/><br/>File Exension Name: sys<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(242, 'file_extension', 108, 'File extension created. <br/><br/>File Exension Name: tmp<br/>File Type ID: 12', '0', '2023-09-11 16:57:40'),
(243, 'file_extension', 109, 'File extension created. <br/><br/>File Exension Name: 3g2<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(244, 'file_extension', 110, 'File extension created. <br/><br/>File Exension Name: 3gp<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(245, 'file_extension', 111, 'File extension created. <br/><br/>File Exension Name: avi<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(246, 'file_extension', 112, 'File extension created. <br/><br/>File Exension Name: flv<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(247, 'file_extension', 113, 'File extension created. <br/><br/>File Exension Name: h264<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(248, 'file_extension', 114, 'File extension created. <br/><br/>File Exension Name: m4v<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(249, 'file_extension', 115, 'File extension created. <br/><br/>File Exension Name: mkv<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(250, 'file_extension', 116, 'File extension created. <br/><br/>File Exension Name: mov<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(251, 'file_extension', 117, 'File extension created. <br/><br/>File Exension Name: mp4<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(252, 'file_extension', 118, 'File extension created. <br/><br/>File Exension Name: mpg<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(253, 'file_extension', 119, 'File extension created. <br/><br/>File Exension Name: mpeg<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(254, 'file_extension', 120, 'File extension created. <br/><br/>File Exension Name: rm<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(255, 'file_extension', 121, 'File extension created. <br/><br/>File Exension Name: swf<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(256, 'file_extension', 122, 'File extension created. <br/><br/>File Exension Name: vob<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(257, 'file_extension', 123, 'File extension created. <br/><br/>File Exension Name: webm<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(258, 'file_extension', 124, 'File extension created. <br/><br/>File Exension Name: wmv<br/>File Type ID: 13', '0', '2023-09-11 16:57:40'),
(259, 'file_extension', 125, 'File extension created. <br/><br/>File Exension Name: doc<br/>File Type ID: 14', '0', '2023-09-11 16:57:40'),
(260, 'file_extension', 126, 'File extension created. <br/><br/>File Exension Name: docx<br/>File Type ID: 14', '0', '2023-09-11 16:57:40'),
(261, 'file_extension', 127, 'File extension created. <br/><br/>File Exension Name: pdf<br/>File Type ID: 14', '0', '2023-09-11 16:57:40'),
(262, 'file_extension', 128, 'File extension created. <br/><br/>File Exension Name: rtf<br/>File Type ID: 14', '0', '2023-09-11 16:57:40'),
(263, 'file_extension', 129, 'File extension created. <br/><br/>File Exension Name: tex<br/>File Type ID: 14', '0', '2023-09-11 16:57:40'),
(264, 'file_extension', 130, 'File extension created. <br/><br/>File Exension Name: txt<br/>File Type ID: 14', '0', '2023-09-11 16:57:40'),
(265, 'file_extension', 131, 'File extension created. <br/><br/>File Exension Name: wpd<br/>File Type ID: 14', '0', '2023-09-11 16:57:40'),
(266, 'interface_setting', 1, 'Interface setting created. <br/><br/>Interface Setting Name: Login Background<br/>Interface Setting Description: Interface setting for background image on login page.', '0', '2023-09-11 16:57:40'),
(267, 'interface_setting', 2, 'Interface setting created. <br/><br/>Interface Setting Name: Login Logo<br/>Interface Setting Description: Interface setting for logo on login page.', '0', '2023-09-11 16:57:40'),
(268, 'interface_setting', 3, 'Interface setting created. <br/><br/>Interface Setting Name: Navbar Logo<br/>Interface Setting Description: Interface setting for logo on navbar.', '0', '2023-09-11 16:57:40'),
(269, 'interface_setting', 4, 'Interface setting created. <br/><br/>Interface Setting Name: System Icon<br/>Interface Setting Description: Interface setting for system icon.', '0', '2023-09-11 16:57:40'),
(270, 'system_setting', 1, 'System setting created. <br/><br/>System Setting Name: Max Failed Login Attempt<br/>System Setting Description: This sets the maximum failed login attempt before the user is locked-out.<br/>Value: 5', '0', '2023-09-11 16:57:40'),
(271, 'system_setting', 2, 'System setting created. <br/><br/>System Setting Name: Max Failed OTP Attempt<br/>System Setting Description: This sets the maximum failed OTP attempt before the user is needs a new OTP code.<br/>Value: 5', '0', '2023-09-11 16:57:40'),
(272, 'system_setting', 3, 'System setting created. <br/><br/>System Setting Name: Default Forgot Password Link<br/>System Setting Description: This sets the default forgot password link.<br/>Value: http://localhost/tech_nexus/password-reset.php?id=', '0', '2023-09-11 16:57:40'),
(273, 'upload_setting', 1, 'Upload setting created. <br/><br/>Upload Setting Name: Profile Image<br/>Upload Setting Description: Sets the upload setting when uploading user account profile image.<br/>Max File Size: 5', '0', '2023-09-11 16:57:40'),
(274, 'upload_setting', 2, 'Upload setting created. <br/><br/>Upload Setting Name: Interface Setting<br/>Upload Setting Description: Sets the upload setting when uploading interface setting image.<br/>Max File Size: 5', '0', '2023-09-11 16:57:40'),
(275, 'upload_setting', 3, 'Upload setting created. <br/><br/>Upload Setting Name: Company Logo<br/>Upload Setting Description: Sets the upload setting when uploading company logo.<br/>Max File Size: 5', '0', '2023-09-11 16:57:40'),
(276, 'country', 1, 'Country created. <br/><br/>Country Name: Afghanistan<br/>Country Code: AFG<br/>Phone Code: 93', '0', '2023-09-11 16:57:40'),
(277, 'country', 2, 'Country created. <br/><br/>Country Name: Aland Islands<br/>Country Code: ALA<br/>Phone Code: 340', '0', '2023-09-11 16:57:40'),
(278, 'country', 3, 'Country created. <br/><br/>Country Name: Albania<br/>Country Code: ALB<br/>Phone Code: 355', '0', '2023-09-11 16:57:40'),
(279, 'country', 4, 'Country created. <br/><br/>Country Name: Algeria<br/>Country Code: DZA<br/>Phone Code: 213', '0', '2023-09-11 16:57:40'),
(280, 'country', 5, 'Country created. <br/><br/>Country Name: American Samoa<br/>Country Code: ASM<br/>Phone Code: -683', '0', '2023-09-11 16:57:40'),
(281, 'country', 6, 'Country created. <br/><br/>Country Name: Andorra<br/>Country Code: AND<br/>Phone Code: 376', '0', '2023-09-11 16:57:40'),
(282, 'country', 7, 'Country created. <br/><br/>Country Name: Angola<br/>Country Code: AGO<br/>Phone Code: 244', '0', '2023-09-11 16:57:40'),
(283, 'country', 8, 'Country created. <br/><br/>Country Name: Anguilla<br/>Country Code: AIA<br/>Phone Code: -263', '0', '2023-09-11 16:57:40'),
(284, 'country', 9, 'Country created. <br/><br/>Country Name: Antarctica<br/>Country Code: ATA<br/>Phone Code: 672', '0', '2023-09-11 16:57:40'),
(285, 'country', 10, 'Country created. <br/><br/>Country Name: Antigua And Barbuda<br/>Country Code: ATG<br/>Phone Code: -267', '0', '2023-09-11 16:57:40'),
(286, 'country', 11, 'Country created. <br/><br/>Country Name: Argentina<br/>Country Code: ARG<br/>Phone Code: 54', '0', '2023-09-11 16:57:40'),
(287, 'country', 12, 'Country created. <br/><br/>Country Name: Armenia<br/>Country Code: ARM<br/>Phone Code: 374', '0', '2023-09-11 16:57:40'),
(288, 'country', 13, 'Country created. <br/><br/>Country Name: Aruba<br/>Country Code: ABW<br/>Phone Code: 297', '0', '2023-09-11 16:57:40'),
(289, 'country', 14, 'Country created. <br/><br/>Country Name: Australia<br/>Country Code: AUS<br/>Phone Code: 61', '0', '2023-09-11 16:57:40'),
(290, 'country', 15, 'Country created. <br/><br/>Country Name: Austria<br/>Country Code: AUT<br/>Phone Code: 43', '0', '2023-09-11 16:57:40'),
(291, 'country', 16, 'Country created. <br/><br/>Country Name: Azerbaijan<br/>Country Code: AZE<br/>Phone Code: 994', '0', '2023-09-11 16:57:40'),
(292, 'country', 17, 'Country created. <br/><br/>Country Name: Bahrain<br/>Country Code: BHR<br/>Phone Code: 973', '0', '2023-09-11 16:57:40'),
(293, 'country', 18, 'Country created. <br/><br/>Country Name: Bangladesh<br/>Country Code: BGD<br/>Phone Code: 880', '0', '2023-09-11 16:57:40'),
(294, 'country', 19, 'Country created. <br/><br/>Country Name: Barbados<br/>Country Code: BRB<br/>Phone Code: -245', '0', '2023-09-11 16:57:40'),
(295, 'country', 20, 'Country created. <br/><br/>Country Name: Belarus<br/>Country Code: BLR<br/>Phone Code: 375', '0', '2023-09-11 16:57:40'),
(296, 'country', 21, 'Country created. <br/><br/>Country Name: Belgium<br/>Country Code: BEL<br/>Phone Code: 32', '0', '2023-09-11 16:57:40'),
(297, 'country', 22, 'Country created. <br/><br/>Country Name: Belize<br/>Country Code: BLZ<br/>Phone Code: 501', '0', '2023-09-11 16:57:40'),
(298, 'country', 23, 'Country created. <br/><br/>Country Name: Benin<br/>Country Code: BEN<br/>Phone Code: 229', '0', '2023-09-11 16:57:40'),
(299, 'country', 24, 'Country created. <br/><br/>Country Name: Bermuda<br/>Country Code: BMU<br/>Phone Code: -440', '0', '2023-09-11 16:57:40'),
(300, 'country', 25, 'Country created. <br/><br/>Country Name: Bhutan<br/>Country Code: BTN<br/>Phone Code: 975', '0', '2023-09-11 16:57:40'),
(301, 'country', 26, 'Country created. <br/><br/>Country Name: Bolivia<br/>Country Code: BOL<br/>Phone Code: 591', '0', '2023-09-11 16:57:40'),
(302, 'country', 27, 'Country created. <br/><br/>Country Name: Bonaire, Sint Eustatius and Saba<br/>Country Code: BES<br/>Phone Code: 599', '0', '2023-09-11 16:57:40'),
(303, 'country', 28, 'Country created. <br/><br/>Country Name: Bosnia and Herzegovina<br/>Country Code: BIH<br/>Phone Code: 387', '0', '2023-09-11 16:57:40'),
(304, 'country', 29, 'Country created. <br/><br/>Country Name: Botswana<br/>Country Code: BWA<br/>Phone Code: 267', '0', '2023-09-11 16:57:40'),
(305, 'country', 30, 'Country created. <br/><br/>Country Name: Bouvet Island<br/>Country Code: BVT<br/>Phone Code: 55', '0', '2023-09-11 16:57:40'),
(306, 'country', 31, 'Country created. <br/><br/>Country Name: Brazil<br/>Country Code: BRA<br/>Phone Code: 55', '0', '2023-09-11 16:57:40'),
(307, 'country', 32, 'Country created. <br/><br/>Country Name: British Indian Ocean Territory<br/>Country Code: IOT<br/>Phone Code: 246', '0', '2023-09-11 16:57:40'),
(308, 'country', 33, 'Country created. <br/><br/>Country Name: Brunei<br/>Country Code: BRN<br/>Phone Code: 673', '0', '2023-09-11 16:57:40'),
(309, 'country', 34, 'Country created. <br/><br/>Country Name: Bulgaria<br/>Country Code: BGR<br/>Phone Code: 359', '0', '2023-09-11 16:57:40'),
(310, 'country', 35, 'Country created. <br/><br/>Country Name: Burkina Faso<br/>Country Code: BFA<br/>Phone Code: 226', '0', '2023-09-11 16:57:40'),
(311, 'country', 36, 'Country created. <br/><br/>Country Name: Burundi<br/>Country Code: BDI<br/>Phone Code: 257', '0', '2023-09-11 16:57:40'),
(312, 'country', 37, 'Country created. <br/><br/>Country Name: Cambodia<br/>Country Code: KHM<br/>Phone Code: 855', '0', '2023-09-11 16:57:40'),
(313, 'country', 38, 'Country created. <br/><br/>Country Name: Cameroon<br/>Country Code: CMR<br/>Phone Code: 237', '0', '2023-09-11 16:57:40'),
(314, 'country', 39, 'Country created. <br/><br/>Country Name: Canada<br/>Country Code: CAN<br/>Phone Code: 1', '0', '2023-09-11 16:57:40'),
(315, 'country', 40, 'Country created. <br/><br/>Country Name: Cape Verde<br/>Country Code: CPV<br/>Phone Code: 238', '0', '2023-09-11 16:57:40'),
(316, 'country', 41, 'Country created. <br/><br/>Country Name: Cayman Islands<br/>Country Code: CYM<br/>Phone Code: -344', '0', '2023-09-11 16:57:40'),
(317, 'country', 42, 'Country created. <br/><br/>Country Name: Central African Republic<br/>Country Code: CAF<br/>Phone Code: 236', '0', '2023-09-11 16:57:40'),
(318, 'country', 43, 'Country created. <br/><br/>Country Name: Chad<br/>Country Code: TCD<br/>Phone Code: 235', '0', '2023-09-11 16:57:40'),
(319, 'country', 44, 'Country created. <br/><br/>Country Name: Chile<br/>Country Code: CHL<br/>Phone Code: 56', '0', '2023-09-11 16:57:40'),
(320, 'country', 45, 'Country created. <br/><br/>Country Name: China<br/>Country Code: CHN<br/>Phone Code: 86', '0', '2023-09-11 16:57:40'),
(321, 'country', 46, 'Country created. <br/><br/>Country Name: Christmas Island<br/>Country Code: CXR<br/>Phone Code: 61', '0', '2023-09-11 16:57:40'),
(322, 'country', 47, 'Country created. <br/><br/>Country Name: Cocos (Keeling) Islands<br/>Country Code: CCK<br/>Phone Code: 61', '0', '2023-09-11 16:57:40'),
(323, 'country', 48, 'Country created. <br/><br/>Country Name: Colombia<br/>Country Code: COL<br/>Phone Code: 57', '0', '2023-09-11 16:57:40'),
(324, 'country', 49, 'Country created. <br/><br/>Country Name: Comoros<br/>Country Code: COM<br/>Phone Code: 269', '0', '2023-09-11 16:57:40'),
(325, 'country', 50, 'Country created. <br/><br/>Country Name: Congo<br/>Country Code: COG<br/>Phone Code: 242', '0', '2023-09-11 16:57:40');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(326, 'country', 51, 'Country created. <br/><br/>Country Name: Cook Islands<br/>Country Code: COK<br/>Phone Code: 682', '0', '2023-09-11 16:57:40'),
(327, 'country', 52, 'Country created. <br/><br/>Country Name: Costa Rica<br/>Country Code: CRI<br/>Phone Code: 506', '0', '2023-09-11 16:57:40'),
(328, 'country', 53, 'Country created. <br/><br/>Country Name: Cote D Ivoire (Ivory Coast)<br/>Country Code: CIV<br/>Phone Code: 225', '0', '2023-09-11 16:57:40'),
(329, 'country', 54, 'Country created. <br/><br/>Country Name: Croatia<br/>Country Code: HRV<br/>Phone Code: 385', '0', '2023-09-11 16:57:40'),
(330, 'country', 55, 'Country created. <br/><br/>Country Name: Cuba<br/>Country Code: CUB<br/>Phone Code: 53', '0', '2023-09-11 16:57:40'),
(331, 'country', 56, 'Country created. <br/><br/>Country Name: CuraÃ§ao<br/>Country Code: CUW<br/>Phone Code: 599', '0', '2023-09-11 16:57:40'),
(332, 'country', 57, 'Country created. <br/><br/>Country Name: Cyprus<br/>Country Code: CYP<br/>Phone Code: 357', '0', '2023-09-11 16:57:40'),
(333, 'country', 58, 'Country created. <br/><br/>Country Name: Czech Republic<br/>Country Code: CZE<br/>Phone Code: 420', '0', '2023-09-11 16:57:40'),
(334, 'country', 59, 'Country created. <br/><br/>Country Name: Democratic Republic of the Congo<br/>Country Code: COD<br/>Phone Code: 243', '0', '2023-09-11 16:57:40'),
(335, 'country', 60, 'Country created. <br/><br/>Country Name: Denmark<br/>Country Code: DNK<br/>Phone Code: 45', '0', '2023-09-11 16:57:40'),
(336, 'country', 61, 'Country created. <br/><br/>Country Name: Djibouti<br/>Country Code: DJI<br/>Phone Code: 253', '0', '2023-09-11 16:57:40'),
(337, 'country', 62, 'Country created. <br/><br/>Country Name: Dominica<br/>Country Code: DMA<br/>Phone Code: -766', '0', '2023-09-11 16:57:40'),
(338, 'country', 63, 'Country created. <br/><br/>Country Name: Dominican Republic<br/>Country Code: DOM<br/>Phone Code: +1-809 and 1-829', '0', '2023-09-11 16:57:40'),
(339, 'country', 64, 'Country created. <br/><br/>Country Name: East Timor<br/>Country Code: TLS<br/>Phone Code: 670', '0', '2023-09-11 16:57:40'),
(340, 'country', 65, 'Country created. <br/><br/>Country Name: Ecuador<br/>Country Code: ECU<br/>Phone Code: 593', '0', '2023-09-11 16:57:40'),
(341, 'country', 66, 'Country created. <br/><br/>Country Name: Egypt<br/>Country Code: EGY<br/>Phone Code: 20', '0', '2023-09-11 16:57:41'),
(342, 'country', 67, 'Country created. <br/><br/>Country Name: El Salvador<br/>Country Code: SLV<br/>Phone Code: 503', '0', '2023-09-11 16:57:41'),
(343, 'country', 68, 'Country created. <br/><br/>Country Name: Equatorial Guinea<br/>Country Code: GNQ<br/>Phone Code: 240', '0', '2023-09-11 16:57:41'),
(344, 'country', 69, 'Country created. <br/><br/>Country Name: Eritrea<br/>Country Code: ERI<br/>Phone Code: 291', '0', '2023-09-11 16:57:41'),
(345, 'country', 70, 'Country created. <br/><br/>Country Name: Estonia<br/>Country Code: EST<br/>Phone Code: 372', '0', '2023-09-11 16:57:41'),
(346, 'country', 71, 'Country created. <br/><br/>Country Name: Ethiopia<br/>Country Code: ETH<br/>Phone Code: 251', '0', '2023-09-11 16:57:41'),
(347, 'country', 72, 'Country created. <br/><br/>Country Name: Falkland Islands<br/>Country Code: FLK<br/>Phone Code: 500', '0', '2023-09-11 16:57:41'),
(348, 'country', 73, 'Country created. <br/><br/>Country Name: Faroe Islands<br/>Country Code: FRO<br/>Phone Code: 298', '0', '2023-09-11 16:57:41'),
(349, 'country', 74, 'Country created. <br/><br/>Country Name: Fiji Islands<br/>Country Code: FJI<br/>Phone Code: 679', '0', '2023-09-11 16:57:41'),
(350, 'country', 75, 'Country created. <br/><br/>Country Name: Finland<br/>Country Code: FIN<br/>Phone Code: 358', '0', '2023-09-11 16:57:41'),
(351, 'country', 76, 'Country created. <br/><br/>Country Name: France<br/>Country Code: FRA<br/>Phone Code: 33', '0', '2023-09-11 16:57:41'),
(352, 'country', 77, 'Country created. <br/><br/>Country Name: French Guiana<br/>Country Code: GUF<br/>Phone Code: 594', '0', '2023-09-11 16:57:41'),
(353, 'country', 78, 'Country created. <br/><br/>Country Name: French Polynesia<br/>Country Code: PYF<br/>Phone Code: 689', '0', '2023-09-11 16:57:41'),
(354, 'country', 79, 'Country created. <br/><br/>Country Name: French Southern Territories<br/>Country Code: ATF<br/>Phone Code: 262', '0', '2023-09-11 16:57:41'),
(355, 'country', 80, 'Country created. <br/><br/>Country Name: Gabon<br/>Country Code: GAB<br/>Phone Code: 241', '0', '2023-09-11 16:57:41'),
(356, 'country', 81, 'Country created. <br/><br/>Country Name: Gambia The<br/>Country Code: GMB<br/>Phone Code: 220', '0', '2023-09-11 16:57:41'),
(357, 'country', 82, 'Country created. <br/><br/>Country Name: Georgia<br/>Country Code: GEO<br/>Phone Code: 995', '0', '2023-09-11 16:57:41'),
(358, 'country', 83, 'Country created. <br/><br/>Country Name: Germany<br/>Country Code: DEU<br/>Phone Code: 49', '0', '2023-09-11 16:57:41'),
(359, 'country', 84, 'Country created. <br/><br/>Country Name: Ghana<br/>Country Code: GHA<br/>Phone Code: 233', '0', '2023-09-11 16:57:41'),
(360, 'country', 85, 'Country created. <br/><br/>Country Name: Gibraltar<br/>Country Code: GIB<br/>Phone Code: 350', '0', '2023-09-11 16:57:41'),
(361, 'country', 86, 'Country created. <br/><br/>Country Name: Greece<br/>Country Code: GRC<br/>Phone Code: 30', '0', '2023-09-11 16:57:41'),
(362, 'country', 87, 'Country created. <br/><br/>Country Name: Greenland<br/>Country Code: GRL<br/>Phone Code: 299', '0', '2023-09-11 16:57:41'),
(363, 'country', 88, 'Country created. <br/><br/>Country Name: Grenada<br/>Country Code: GRD<br/>Phone Code: -472', '0', '2023-09-11 16:57:41'),
(364, 'country', 89, 'Country created. <br/><br/>Country Name: Guadeloupe<br/>Country Code: GLP<br/>Phone Code: 590', '0', '2023-09-11 16:57:41'),
(365, 'country', 90, 'Country created. <br/><br/>Country Name: Guam<br/>Country Code: GUM<br/>Phone Code: -670', '0', '2023-09-11 16:57:41'),
(366, 'country', 91, 'Country created. <br/><br/>Country Name: Guatemala<br/>Country Code: GTM<br/>Phone Code: 502', '0', '2023-09-11 16:57:41'),
(367, 'country', 92, 'Country created. <br/><br/>Country Name: Guernsey and Alderney<br/>Country Code: GGY<br/>Phone Code: -1437', '0', '2023-09-11 16:57:41'),
(368, 'country', 93, 'Country created. <br/><br/>Country Name: Guinea<br/>Country Code: GIN<br/>Phone Code: 224', '0', '2023-09-11 16:57:41'),
(369, 'country', 94, 'Country created. <br/><br/>Country Name: Guinea-Bissau<br/>Country Code: GNB<br/>Phone Code: 245', '0', '2023-09-11 16:57:41'),
(370, 'country', 95, 'Country created. <br/><br/>Country Name: Guyana<br/>Country Code: GUY<br/>Phone Code: 592', '0', '2023-09-11 16:57:41'),
(371, 'country', 96, 'Country created. <br/><br/>Country Name: Haiti<br/>Country Code: HTI<br/>Phone Code: 509', '0', '2023-09-11 16:57:41'),
(372, 'country', 97, 'Country created. <br/><br/>Country Name: Heard Island and McDonald Islands<br/>Country Code: HMD<br/>Phone Code: 672', '0', '2023-09-11 16:57:41'),
(373, 'country', 98, 'Country created. <br/><br/>Country Name: Honduras<br/>Country Code: HND<br/>Phone Code: 504', '0', '2023-09-11 16:57:41'),
(374, 'country', 99, 'Country created. <br/><br/>Country Name: Hong Kong S.A.R.<br/>Country Code: HKG<br/>Phone Code: 852', '0', '2023-09-11 16:57:41'),
(375, 'country', 100, 'Country created. <br/><br/>Country Name: Hungary<br/>Country Code: HUN<br/>Phone Code: 36', '0', '2023-09-11 16:57:41'),
(376, 'country', 101, 'Country created. <br/><br/>Country Name: Iceland<br/>Country Code: ISL<br/>Phone Code: 354', '0', '2023-09-11 16:57:41'),
(377, 'country', 102, 'Country created. <br/><br/>Country Name: India<br/>Country Code: IND<br/>Phone Code: 91', '0', '2023-09-11 16:57:41'),
(378, 'country', 103, 'Country created. <br/><br/>Country Name: Indonesia<br/>Country Code: IDN<br/>Phone Code: 62', '0', '2023-09-11 16:57:41'),
(379, 'country', 104, 'Country created. <br/><br/>Country Name: Iran<br/>Country Code: IRN<br/>Phone Code: 98', '0', '2023-09-11 16:57:41'),
(380, 'country', 105, 'Country created. <br/><br/>Country Name: Iraq<br/>Country Code: IRQ<br/>Phone Code: 964', '0', '2023-09-11 16:57:41'),
(381, 'country', 106, 'Country created. <br/><br/>Country Name: Ireland<br/>Country Code: IRL<br/>Phone Code: 353', '0', '2023-09-11 16:57:41'),
(382, 'country', 107, 'Country created. <br/><br/>Country Name: Israel<br/>Country Code: ISR<br/>Phone Code: 972', '0', '2023-09-11 16:57:41'),
(383, 'country', 108, 'Country created. <br/><br/>Country Name: Italy<br/>Country Code: ITA<br/>Phone Code: 39', '0', '2023-09-11 16:57:41'),
(384, 'country', 109, 'Country created. <br/><br/>Country Name: Jamaica<br/>Country Code: JAM<br/>Phone Code: -875', '0', '2023-09-11 16:57:41'),
(385, 'country', 110, 'Country created. <br/><br/>Country Name: Japan<br/>Country Code: JPN<br/>Phone Code: 81', '0', '2023-09-11 16:57:41'),
(386, 'country', 111, 'Country created. <br/><br/>Country Name: Jersey<br/>Country Code: JEY<br/>Phone Code: -1490', '0', '2023-09-11 16:57:41'),
(387, 'country', 112, 'Country created. <br/><br/>Country Name: Jordan<br/>Country Code: JOR<br/>Phone Code: 962', '0', '2023-09-11 16:57:41'),
(388, 'country', 113, 'Country created. <br/><br/>Country Name: Kazakhstan<br/>Country Code: KAZ<br/>Phone Code: 7', '0', '2023-09-11 16:57:41'),
(389, 'country', 114, 'Country created. <br/><br/>Country Name: Kenya<br/>Country Code: KEN<br/>Phone Code: 254', '0', '2023-09-11 16:57:41'),
(390, 'country', 115, 'Country created. <br/><br/>Country Name: Kiribati<br/>Country Code: KIR<br/>Phone Code: 686', '0', '2023-09-11 16:57:41'),
(391, 'country', 116, 'Country created. <br/><br/>Country Name: Kosovo<br/>Country Code: XKX<br/>Phone Code: 383', '0', '2023-09-11 16:57:41'),
(392, 'country', 117, 'Country created. <br/><br/>Country Name: Kuwait<br/>Country Code: KWT<br/>Phone Code: 965', '0', '2023-09-11 16:57:41'),
(393, 'country', 118, 'Country created. <br/><br/>Country Name: Kyrgyzstan<br/>Country Code: KGZ<br/>Phone Code: 996', '0', '2023-09-11 16:57:41'),
(394, 'country', 119, 'Country created. <br/><br/>Country Name: Laos<br/>Country Code: LAO<br/>Phone Code: 856', '0', '2023-09-11 16:57:41'),
(395, 'country', 120, 'Country created. <br/><br/>Country Name: Latvia<br/>Country Code: LVA<br/>Phone Code: 371', '0', '2023-09-11 16:57:41'),
(396, 'country', 121, 'Country created. <br/><br/>Country Name: Lebanon<br/>Country Code: LBN<br/>Phone Code: 961', '0', '2023-09-11 16:57:41'),
(397, 'country', 122, 'Country created. <br/><br/>Country Name: Lesotho<br/>Country Code: LSO<br/>Phone Code: 266', '0', '2023-09-11 16:57:41'),
(398, 'country', 123, 'Country created. <br/><br/>Country Name: Liberia<br/>Country Code: LBR<br/>Phone Code: 231', '0', '2023-09-11 16:57:41'),
(399, 'country', 124, 'Country created. <br/><br/>Country Name: Libya<br/>Country Code: LBY<br/>Phone Code: 218', '0', '2023-09-11 16:57:41'),
(400, 'country', 125, 'Country created. <br/><br/>Country Name: Liechtenstein<br/>Country Code: LIE<br/>Phone Code: 423', '0', '2023-09-11 16:57:41'),
(401, 'country', 126, 'Country created. <br/><br/>Country Name: Lithuania<br/>Country Code: LTU<br/>Phone Code: 370', '0', '2023-09-11 16:57:41'),
(402, 'country', 127, 'Country created. <br/><br/>Country Name: Luxembourg<br/>Country Code: LUX<br/>Phone Code: 352', '0', '2023-09-11 16:57:41'),
(403, 'country', 128, 'Country created. <br/><br/>Country Name: Macau S.A.R.<br/>Country Code: MAC<br/>Phone Code: 853', '0', '2023-09-11 16:57:41'),
(404, 'country', 129, 'Country created. <br/><br/>Country Name: Madagascar<br/>Country Code: MDG<br/>Phone Code: 261', '0', '2023-09-11 16:57:41'),
(405, 'country', 130, 'Country created. <br/><br/>Country Name: Malawi<br/>Country Code: MWI<br/>Phone Code: 265', '0', '2023-09-11 16:57:41'),
(406, 'country', 131, 'Country created. <br/><br/>Country Name: Malaysia<br/>Country Code: MYS<br/>Phone Code: 60', '0', '2023-09-11 16:57:41'),
(407, 'country', 132, 'Country created. <br/><br/>Country Name: Maldives<br/>Country Code: MDV<br/>Phone Code: 960', '0', '2023-09-11 16:57:41'),
(408, 'country', 133, 'Country created. <br/><br/>Country Name: Mali<br/>Country Code: MLI<br/>Phone Code: 223', '0', '2023-09-11 16:57:41'),
(409, 'country', 134, 'Country created. <br/><br/>Country Name: Malta<br/>Country Code: MLT<br/>Phone Code: 356', '0', '2023-09-11 16:57:41'),
(410, 'country', 135, 'Country created. <br/><br/>Country Name: Man (Isle of)<br/>Country Code: IMN<br/>Phone Code: -1580', '0', '2023-09-11 16:57:41'),
(411, 'country', 136, 'Country created. <br/><br/>Country Name: Marshall Islands<br/>Country Code: MHL<br/>Phone Code: 692', '0', '2023-09-11 16:57:41'),
(412, 'country', 137, 'Country created. <br/><br/>Country Name: Martinique<br/>Country Code: MTQ<br/>Phone Code: 596', '0', '2023-09-11 16:57:41'),
(413, 'country', 138, 'Country created. <br/><br/>Country Name: Mauritania<br/>Country Code: MRT<br/>Phone Code: 222', '0', '2023-09-11 16:57:41'),
(414, 'country', 139, 'Country created. <br/><br/>Country Name: Mauritius<br/>Country Code: MUS<br/>Phone Code: 230', '0', '2023-09-11 16:57:41'),
(415, 'country', 140, 'Country created. <br/><br/>Country Name: Mayotte<br/>Country Code: MYT<br/>Phone Code: 262', '0', '2023-09-11 16:57:41'),
(416, 'country', 141, 'Country created. <br/><br/>Country Name: Mexico<br/>Country Code: MEX<br/>Phone Code: 52', '0', '2023-09-11 16:57:41'),
(417, 'country', 142, 'Country created. <br/><br/>Country Name: Micronesia<br/>Country Code: FSM<br/>Phone Code: 691', '0', '2023-09-11 16:57:41'),
(418, 'country', 143, 'Country created. <br/><br/>Country Name: Moldova<br/>Country Code: MDA<br/>Phone Code: 373', '0', '2023-09-11 16:57:41'),
(419, 'country', 144, 'Country created. <br/><br/>Country Name: Monaco<br/>Country Code: MCO<br/>Phone Code: 377', '0', '2023-09-11 16:57:41'),
(420, 'country', 145, 'Country created. <br/><br/>Country Name: Mongolia<br/>Country Code: MNG<br/>Phone Code: 976', '0', '2023-09-11 16:57:41'),
(421, 'country', 146, 'Country created. <br/><br/>Country Name: Montenegro<br/>Country Code: MNE<br/>Phone Code: 382', '0', '2023-09-11 16:57:41'),
(422, 'country', 147, 'Country created. <br/><br/>Country Name: Montserrat<br/>Country Code: MSR<br/>Phone Code: -663', '0', '2023-09-11 16:57:41'),
(423, 'country', 148, 'Country created. <br/><br/>Country Name: Morocco<br/>Country Code: MAR<br/>Phone Code: 212', '0', '2023-09-11 16:57:41'),
(424, 'country', 149, 'Country created. <br/><br/>Country Name: Mozambique<br/>Country Code: MOZ<br/>Phone Code: 258', '0', '2023-09-11 16:57:41'),
(425, 'country', 150, 'Country created. <br/><br/>Country Name: Myanmar<br/>Country Code: MMR<br/>Phone Code: 95', '0', '2023-09-11 16:57:41'),
(426, 'country', 151, 'Country created. <br/><br/>Country Name: Namibia<br/>Country Code: NAM<br/>Phone Code: 264', '0', '2023-09-11 16:57:41'),
(427, 'country', 152, 'Country created. <br/><br/>Country Name: Nauru<br/>Country Code: NRU<br/>Phone Code: 674', '0', '2023-09-11 16:57:41'),
(428, 'country', 153, 'Country created. <br/><br/>Country Name: Nepal<br/>Country Code: NPL<br/>Phone Code: 977', '0', '2023-09-11 16:57:41'),
(429, 'country', 154, 'Country created. <br/><br/>Country Name: Netherlands<br/>Country Code: NLD<br/>Phone Code: 31', '0', '2023-09-11 16:57:41'),
(430, 'country', 155, 'Country created. <br/><br/>Country Name: New Caledonia<br/>Country Code: NCL<br/>Phone Code: 687', '0', '2023-09-11 16:57:41'),
(431, 'country', 156, 'Country created. <br/><br/>Country Name: New Zealand<br/>Country Code: NZL<br/>Phone Code: 64', '0', '2023-09-11 16:57:41'),
(432, 'country', 157, 'Country created. <br/><br/>Country Name: Nicaragua<br/>Country Code: NIC<br/>Phone Code: 505', '0', '2023-09-11 16:57:41'),
(433, 'country', 158, 'Country created. <br/><br/>Country Name: Niger<br/>Country Code: NER<br/>Phone Code: 227', '0', '2023-09-11 16:57:41'),
(434, 'country', 159, 'Country created. <br/><br/>Country Name: Nigeria<br/>Country Code: NGA<br/>Phone Code: 234', '0', '2023-09-11 16:57:41'),
(435, 'country', 160, 'Country created. <br/><br/>Country Name: Niue<br/>Country Code: NIU<br/>Phone Code: 683', '0', '2023-09-11 16:57:41'),
(436, 'country', 161, 'Country created. <br/><br/>Country Name: Norfolk Island<br/>Country Code: NFK<br/>Phone Code: 672', '0', '2023-09-11 16:57:41'),
(437, 'country', 162, 'Country created. <br/><br/>Country Name: North Korea<br/>Country Code: PRK<br/>Phone Code: 850', '0', '2023-09-11 16:57:41'),
(438, 'country', 163, 'Country created. <br/><br/>Country Name: North Macedonia<br/>Country Code: MKD<br/>Phone Code: 389', '0', '2023-09-11 16:57:41'),
(439, 'country', 164, 'Country created. <br/><br/>Country Name: Northern Mariana Islands<br/>Country Code: MNP<br/>Phone Code: -669', '0', '2023-09-11 16:57:41'),
(440, 'country', 165, 'Country created. <br/><br/>Country Name: Norway<br/>Country Code: NOR<br/>Phone Code: 47', '0', '2023-09-11 16:57:41'),
(441, 'country', 166, 'Country created. <br/><br/>Country Name: Oman<br/>Country Code: OMN<br/>Phone Code: 968', '0', '2023-09-11 16:57:41'),
(442, 'country', 167, 'Country created. <br/><br/>Country Name: Pakistan<br/>Country Code: PAK<br/>Phone Code: 92', '0', '2023-09-11 16:57:41'),
(443, 'country', 168, 'Country created. <br/><br/>Country Name: Palau<br/>Country Code: PLW<br/>Phone Code: 680', '0', '2023-09-11 16:57:41'),
(444, 'country', 169, 'Country created. <br/><br/>Country Name: Palestinian Territory Occupied<br/>Country Code: PSE<br/>Phone Code: 970', '0', '2023-09-11 16:57:41'),
(445, 'country', 170, 'Country created. <br/><br/>Country Name: Panama<br/>Country Code: PAN<br/>Phone Code: 507', '0', '2023-09-11 16:57:41'),
(446, 'country', 171, 'Country created. <br/><br/>Country Name: Papua new Guinea<br/>Country Code: PNG<br/>Phone Code: 675', '0', '2023-09-11 16:57:41'),
(447, 'country', 172, 'Country created. <br/><br/>Country Name: Paraguay<br/>Country Code: PRY<br/>Phone Code: 595', '0', '2023-09-11 16:57:41'),
(448, 'country', 173, 'Country created. <br/><br/>Country Name: Peru<br/>Country Code: PER<br/>Phone Code: 51', '0', '2023-09-11 16:57:41'),
(449, 'country', 174, 'Country created. <br/><br/>Country Name: Philippines<br/>Country Code: PHL<br/>Phone Code: 63', '0', '2023-09-11 16:57:41'),
(450, 'country', 175, 'Country created. <br/><br/>Country Name: Pitcairn Island<br/>Country Code: PCN<br/>Phone Code: 870', '0', '2023-09-11 16:57:41'),
(451, 'country', 176, 'Country created. <br/><br/>Country Name: Poland<br/>Country Code: POL<br/>Phone Code: 48', '0', '2023-09-11 16:57:41'),
(452, 'country', 177, 'Country created. <br/><br/>Country Name: Portugal<br/>Country Code: PRT<br/>Phone Code: 351', '0', '2023-09-11 16:57:41'),
(453, 'country', 178, 'Country created. <br/><br/>Country Name: Puerto Rico<br/>Country Code: PRI<br/>Phone Code: +1-787 and 1-939', '0', '2023-09-11 16:57:41'),
(454, 'country', 179, 'Country created. <br/><br/>Country Name: Qatar<br/>Country Code: QAT<br/>Phone Code: 974', '0', '2023-09-11 16:57:41'),
(455, 'country', 180, 'Country created. <br/><br/>Country Name: Reunion<br/>Country Code: REU<br/>Phone Code: 262', '0', '2023-09-11 16:57:41'),
(456, 'country', 181, 'Country created. <br/><br/>Country Name: Romania<br/>Country Code: ROU<br/>Phone Code: 40', '0', '2023-09-11 16:57:41'),
(457, 'country', 182, 'Country created. <br/><br/>Country Name: Russia<br/>Country Code: RUS<br/>Phone Code: 7', '0', '2023-09-11 16:57:41'),
(458, 'country', 183, 'Country created. <br/><br/>Country Name: Rwanda<br/>Country Code: RWA<br/>Phone Code: 250', '0', '2023-09-11 16:57:41'),
(459, 'country', 184, 'Country created. <br/><br/>Country Name: Saint Helena<br/>Country Code: SHN<br/>Phone Code: 290', '0', '2023-09-11 16:57:41'),
(460, 'country', 185, 'Country created. <br/><br/>Country Name: Saint Kitts And Nevis<br/>Country Code: KNA<br/>Phone Code: -868', '0', '2023-09-11 16:57:41'),
(461, 'country', 186, 'Country created. <br/><br/>Country Name: Saint Lucia<br/>Country Code: LCA<br/>Phone Code: -757', '0', '2023-09-11 16:57:41'),
(462, 'country', 187, 'Country created. <br/><br/>Country Name: Saint Pierre and Miquelon<br/>Country Code: SPM<br/>Phone Code: 508', '0', '2023-09-11 16:57:41'),
(463, 'country', 188, 'Country created. <br/><br/>Country Name: Saint Vincent And The Grenadines<br/>Country Code: VCT<br/>Phone Code: -783', '0', '2023-09-11 16:57:41'),
(464, 'country', 189, 'Country created. <br/><br/>Country Name: Saint-Barthelemy<br/>Country Code: BLM<br/>Phone Code: 590', '0', '2023-09-11 16:57:41'),
(465, 'country', 190, 'Country created. <br/><br/>Country Name: Saint-Martin (French part)<br/>Country Code: MAF<br/>Phone Code: 590', '0', '2023-09-11 16:57:41'),
(466, 'country', 191, 'Country created. <br/><br/>Country Name: Samoa<br/>Country Code: WSM<br/>Phone Code: 685', '0', '2023-09-11 16:57:41'),
(467, 'country', 192, 'Country created. <br/><br/>Country Name: San Marino<br/>Country Code: SMR<br/>Phone Code: 378', '0', '2023-09-11 16:57:41'),
(468, 'country', 193, 'Country created. <br/><br/>Country Name: Sao Tome and Principe<br/>Country Code: STP<br/>Phone Code: 239', '0', '2023-09-11 16:57:41'),
(469, 'country', 194, 'Country created. <br/><br/>Country Name: Saudi Arabia<br/>Country Code: SAU<br/>Phone Code: 966', '0', '2023-09-11 16:57:41'),
(470, 'country', 195, 'Country created. <br/><br/>Country Name: Senegal<br/>Country Code: SEN<br/>Phone Code: 221', '0', '2023-09-11 16:57:41'),
(471, 'country', 196, 'Country created. <br/><br/>Country Name: Serbia<br/>Country Code: SRB<br/>Phone Code: 381', '0', '2023-09-11 16:57:41'),
(472, 'country', 197, 'Country created. <br/><br/>Country Name: Seychelles<br/>Country Code: SYC<br/>Phone Code: 248', '0', '2023-09-11 16:57:41'),
(473, 'country', 198, 'Country created. <br/><br/>Country Name: Sierra Leone<br/>Country Code: SLE<br/>Phone Code: 232', '0', '2023-09-11 16:57:41'),
(474, 'country', 199, 'Country created. <br/><br/>Country Name: Singapore<br/>Country Code: SGP<br/>Phone Code: 65', '0', '2023-09-11 16:57:41'),
(475, 'country', 200, 'Country created. <br/><br/>Country Name: Sint Maarten (Dutch part)<br/>Country Code: SXM<br/>Phone Code: 1721', '0', '2023-09-11 16:57:41'),
(476, 'country', 201, 'Country created. <br/><br/>Country Name: Slovakia<br/>Country Code: SVK<br/>Phone Code: 421', '0', '2023-09-11 16:57:41'),
(477, 'country', 202, 'Country created. <br/><br/>Country Name: Slovenia<br/>Country Code: SVN<br/>Phone Code: 386', '0', '2023-09-11 16:57:41'),
(478, 'country', 203, 'Country created. <br/><br/>Country Name: Solomon Islands<br/>Country Code: SLB<br/>Phone Code: 677', '0', '2023-09-11 16:57:41'),
(479, 'country', 204, 'Country created. <br/><br/>Country Name: Somalia<br/>Country Code: SOM<br/>Phone Code: 252', '0', '2023-09-11 16:57:41'),
(480, 'country', 205, 'Country created. <br/><br/>Country Name: South Africa<br/>Country Code: ZAF<br/>Phone Code: 27', '0', '2023-09-11 16:57:41'),
(481, 'country', 206, 'Country created. <br/><br/>Country Name: South Georgia<br/>Country Code: SGS<br/>Phone Code: 500', '0', '2023-09-11 16:57:41'),
(482, 'country', 207, 'Country created. <br/><br/>Country Name: South Korea<br/>Country Code: KOR<br/>Phone Code: 82', '0', '2023-09-11 16:57:41'),
(483, 'country', 208, 'Country created. <br/><br/>Country Name: South Sudan<br/>Country Code: SSD<br/>Phone Code: 211', '0', '2023-09-11 16:57:41'),
(484, 'country', 209, 'Country created. <br/><br/>Country Name: Spain<br/>Country Code: ESP<br/>Phone Code: 34', '0', '2023-09-11 16:57:41'),
(485, 'country', 210, 'Country created. <br/><br/>Country Name: Sri Lanka<br/>Country Code: LKA<br/>Phone Code: 94', '0', '2023-09-11 16:57:41'),
(486, 'country', 211, 'Country created. <br/><br/>Country Name: Sudan<br/>Country Code: SDN<br/>Phone Code: 249', '0', '2023-09-11 16:57:41'),
(487, 'country', 212, 'Country created. <br/><br/>Country Name: Suriname<br/>Country Code: SUR<br/>Phone Code: 597', '0', '2023-09-11 16:57:41'),
(488, 'country', 213, 'Country created. <br/><br/>Country Name: Svalbard And Jan Mayen Islands<br/>Country Code: SJM<br/>Phone Code: 47', '0', '2023-09-11 16:57:41'),
(489, 'country', 214, 'Country created. <br/><br/>Country Name: Swaziland<br/>Country Code: SWZ<br/>Phone Code: 268', '0', '2023-09-11 16:57:41'),
(490, 'country', 215, 'Country created. <br/><br/>Country Name: Sweden<br/>Country Code: SWE<br/>Phone Code: 46', '0', '2023-09-11 16:57:41'),
(491, 'country', 216, 'Country created. <br/><br/>Country Name: Switzerland<br/>Country Code: CHE<br/>Phone Code: 41', '0', '2023-09-11 16:57:41'),
(492, 'country', 217, 'Country created. <br/><br/>Country Name: Syria<br/>Country Code: SYR<br/>Phone Code: 963', '0', '2023-09-11 16:57:41'),
(493, 'country', 218, 'Country created. <br/><br/>Country Name: Taiwan<br/>Country Code: TWN<br/>Phone Code: 886', '0', '2023-09-11 16:57:41'),
(494, 'country', 219, 'Country created. <br/><br/>Country Name: Tajikistan<br/>Country Code: TJK<br/>Phone Code: 992', '0', '2023-09-11 16:57:41'),
(495, 'country', 220, 'Country created. <br/><br/>Country Name: Tanzania<br/>Country Code: TZA<br/>Phone Code: 255', '0', '2023-09-11 16:57:41'),
(496, 'country', 221, 'Country created. <br/><br/>Country Name: Thailand<br/>Country Code: THA<br/>Phone Code: 66', '0', '2023-09-11 16:57:41'),
(497, 'country', 222, 'Country created. <br/><br/>Country Name: The Bahamas<br/>Country Code: BHS<br/>Phone Code: -241', '0', '2023-09-11 16:57:41'),
(498, 'country', 223, 'Country created. <br/><br/>Country Name: Togo<br/>Country Code: TGO<br/>Phone Code: 228', '0', '2023-09-11 16:57:41'),
(499, 'country', 224, 'Country created. <br/><br/>Country Name: Tokelau<br/>Country Code: TKL<br/>Phone Code: 690', '0', '2023-09-11 16:57:41'),
(500, 'country', 225, 'Country created. <br/><br/>Country Name: Tonga<br/>Country Code: TON<br/>Phone Code: 676', '0', '2023-09-11 16:57:41'),
(501, 'country', 226, 'Country created. <br/><br/>Country Name: Trinidad And Tobago<br/>Country Code: TTO<br/>Phone Code: -867', '0', '2023-09-11 16:57:41'),
(502, 'country', 227, 'Country created. <br/><br/>Country Name: Tunisia<br/>Country Code: TUN<br/>Phone Code: 216', '0', '2023-09-11 16:57:41'),
(503, 'country', 228, 'Country created. <br/><br/>Country Name: Turkey<br/>Country Code: TUR<br/>Phone Code: 90', '0', '2023-09-11 16:57:41'),
(504, 'country', 229, 'Country created. <br/><br/>Country Name: Turkmenistan<br/>Country Code: TKM<br/>Phone Code: 993', '0', '2023-09-11 16:57:41'),
(505, 'country', 230, 'Country created. <br/><br/>Country Name: Turks And Caicos Islands<br/>Country Code: TCA<br/>Phone Code: -648', '0', '2023-09-11 16:57:41'),
(506, 'country', 231, 'Country created. <br/><br/>Country Name: Tuvalu<br/>Country Code: TUV<br/>Phone Code: 688', '0', '2023-09-11 16:57:41'),
(507, 'country', 232, 'Country created. <br/><br/>Country Name: Uganda<br/>Country Code: UGA<br/>Phone Code: 256', '0', '2023-09-11 16:57:41'),
(508, 'country', 233, 'Country created. <br/><br/>Country Name: Ukraine<br/>Country Code: UKR<br/>Phone Code: 380', '0', '2023-09-11 16:57:41'),
(509, 'country', 234, 'Country created. <br/><br/>Country Name: United Arab Emirates<br/>Country Code: ARE<br/>Phone Code: 971', '0', '2023-09-11 16:57:41'),
(510, 'country', 235, 'Country created. <br/><br/>Country Name: United Kingdom<br/>Country Code: GBR<br/>Phone Code: 44', '0', '2023-09-11 16:57:41'),
(511, 'country', 236, 'Country created. <br/><br/>Country Name: United States<br/>Country Code: USA<br/>Phone Code: 1', '0', '2023-09-11 16:57:41'),
(512, 'country', 237, 'Country created. <br/><br/>Country Name: United States Minor Outlying Islands<br/>Country Code: UMI<br/>Phone Code: 1', '0', '2023-09-11 16:57:41'),
(513, 'country', 238, 'Country created. <br/><br/>Country Name: Uruguay<br/>Country Code: URY<br/>Phone Code: 598', '0', '2023-09-11 16:57:41'),
(514, 'country', 239, 'Country created. <br/><br/>Country Name: Uzbekistan<br/>Country Code: UZB<br/>Phone Code: 998', '0', '2023-09-11 16:57:41'),
(515, 'country', 240, 'Country created. <br/><br/>Country Name: Vanuatu<br/>Country Code: VUT<br/>Phone Code: 678', '0', '2023-09-11 16:57:41'),
(516, 'country', 241, 'Country created. <br/><br/>Country Name: Vatican City State (Holy See)<br/>Country Code: VAT<br/>Phone Code: 379', '0', '2023-09-11 16:57:41'),
(517, 'country', 242, 'Country created. <br/><br/>Country Name: Venezuela<br/>Country Code: VEN<br/>Phone Code: 58', '0', '2023-09-11 16:57:41'),
(518, 'country', 243, 'Country created. <br/><br/>Country Name: Vietnam<br/>Country Code: VNM<br/>Phone Code: 84', '0', '2023-09-11 16:57:41'),
(519, 'country', 244, 'Country created. <br/><br/>Country Name: Virgin Islands (British)<br/>Country Code: VGB<br/>Phone Code: -283', '0', '2023-09-11 16:57:41'),
(520, 'country', 245, 'Country created. <br/><br/>Country Name: Virgin Islands (US)<br/>Country Code: VIR<br/>Phone Code: -339', '0', '2023-09-11 16:57:41'),
(521, 'country', 246, 'Country created. <br/><br/>Country Name: Wallis And Futuna Islands<br/>Country Code: WLF<br/>Phone Code: 681', '0', '2023-09-11 16:57:41'),
(522, 'country', 247, 'Country created. <br/><br/>Country Name: Western Sahara<br/>Country Code: ESH<br/>Phone Code: 212', '0', '2023-09-11 16:57:41'),
(523, 'country', 248, 'Country created. <br/><br/>Country Name: Yemen<br/>Country Code: YEM<br/>Phone Code: 967', '0', '2023-09-11 16:57:41'),
(524, 'country', 249, 'Country created. <br/><br/>Country Name: Zambia<br/>Country Code: ZMB<br/>Phone Code: 260', '0', '2023-09-11 16:57:41'),
(525, 'country', 250, 'Country created. <br/><br/>Country Name: Zimbabwe<br/>Country Code: ZWE<br/>Phone Code: 263', '0', '2023-09-11 16:57:41'),
(526, 'state', 1, 'State created. <br/><br/>State Name: Metro Manila<br/>Country ID: 174<br/>State Code: 00', '0', '2023-09-11 16:57:41'),
(527, 'state', 2, 'State created. <br/><br/>State Name: Ilocos Norte<br/>Country ID: 174<br/>State Code: ILN', '0', '2023-09-11 16:57:41'),
(528, 'state', 3, 'State created. <br/><br/>State Name: Ilocos Sur<br/>Country ID: 174<br/>State Code: ILS', '0', '2023-09-11 16:57:41'),
(529, 'state', 4, 'State created. <br/><br/>State Name: La Union<br/>Country ID: 174<br/>State Code: LUN', '0', '2023-09-11 16:57:41'),
(530, 'state', 5, 'State created. <br/><br/>State Name: Pangasinan<br/>Country ID: 174<br/>State Code: PAN', '0', '2023-09-11 16:57:41'),
(531, 'state', 6, 'State created. <br/><br/>State Name: Batanes<br/>Country ID: 174<br/>State Code: BTN', '0', '2023-09-11 16:57:41'),
(532, 'state', 7, 'State created. <br/><br/>State Name: Cagayan<br/>Country ID: 174<br/>State Code: CAG', '0', '2023-09-11 16:57:41'),
(533, 'state', 8, 'State created. <br/><br/>State Name: Isabela<br/>Country ID: 174<br/>State Code: ISA', '0', '2023-09-11 16:57:41'),
(534, 'state', 9, 'State created. <br/><br/>State Name: Nueva Vizcaya<br/>Country ID: 174<br/>State Code: NSA', '0', '2023-09-11 16:57:41'),
(535, 'state', 10, 'State created. <br/><br/>State Name: Quirino<br/>Country ID: 174<br/>State Code: QUI', '0', '2023-09-11 16:57:41'),
(536, 'state', 11, 'State created. <br/><br/>State Name: Bataan<br/>Country ID: 174<br/>State Code: BAN', '0', '2023-09-11 16:57:41'),
(537, 'state', 12, 'State created. <br/><br/>State Name: Bulacan<br/>Country ID: 174<br/>State Code: BUL', '0', '2023-09-11 16:57:41'),
(538, 'state', 13, 'State created. <br/><br/>State Name: Nueva Ecija<br/>Country ID: 174<br/>State Code: NE', '0', '2023-09-11 16:57:41'),
(539, 'state', 14, 'State created. <br/><br/>State Name: Pampanga<br/>Country ID: 174<br/>State Code: PAM', '0', '2023-09-11 16:57:41'),
(540, 'state', 15, 'State created. <br/><br/>State Name: Tarlac<br/>Country ID: 174<br/>State Code: TAR', '0', '2023-09-11 16:57:41'),
(541, 'state', 16, 'State created. <br/><br/>State Name: Zambales<br/>Country ID: 174<br/>State Code: ZMB', '0', '2023-09-11 16:57:41'),
(542, 'state', 17, 'State created. <br/><br/>State Name: Aurora<br/>Country ID: 174<br/>State Code: AUR', '0', '2023-09-11 16:57:41'),
(543, 'state', 18, 'State created. <br/><br/>State Name: Batangas<br/>Country ID: 174<br/>State Code: BTG', '0', '2023-09-11 16:57:41'),
(544, 'state', 19, 'State created. <br/><br/>State Name: Cavite<br/>Country ID: 174<br/>State Code: CAV', '0', '2023-09-11 16:57:41'),
(545, 'state', 20, 'State created. <br/><br/>State Name: Laguna<br/>Country ID: 174<br/>State Code: LAG', '0', '2023-09-11 16:57:41'),
(546, 'state', 21, 'State created. <br/><br/>State Name: Quezon<br/>Country ID: 174<br/>State Code: QUE', '0', '2023-09-11 16:57:41'),
(547, 'state', 22, 'State created. <br/><br/>State Name: Rizal<br/>Country ID: 174<br/>State Code: RIZ', '0', '2023-09-11 16:57:41'),
(548, 'state', 23, 'State created. <br/><br/>State Name: Marinduque<br/>Country ID: 174<br/>State Code: MAD', '0', '2023-09-11 16:57:41'),
(549, 'state', 24, 'State created. <br/><br/>State Name: Occidental Mindoro<br/>Country ID: 174<br/>State Code: NUE', '0', '2023-09-11 16:57:41'),
(550, 'state', 25, 'State created. <br/><br/>State Name: Oriental Mindoro<br/>Country ID: 174<br/>State Code: NUV', '0', '2023-09-11 16:57:41'),
(551, 'state', 26, 'State created. <br/><br/>State Name: Palawan<br/>Country ID: 174<br/>State Code: PLW', '0', '2023-09-11 16:57:41'),
(552, 'state', 27, 'State created. <br/><br/>State Name: Romblon<br/>Country ID: 174<br/>State Code: ROM', '0', '2023-09-11 16:57:41'),
(553, 'state', 28, 'State created. <br/><br/>State Name: Albay<br/>Country ID: 174<br/>State Code: ALB', '0', '2023-09-11 16:57:41'),
(554, 'state', 29, 'State created. <br/><br/>State Name: Camarines Norte<br/>Country ID: 174<br/>State Code: CAN', '0', '2023-09-11 16:57:41'),
(555, 'state', 30, 'State created. <br/><br/>State Name: Camarines Sur<br/>Country ID: 174<br/>State Code: CAS', '0', '2023-09-11 16:57:41'),
(556, 'state', 31, 'State created. <br/><br/>State Name: Catanduanes<br/>Country ID: 174<br/>State Code: CAT', '0', '2023-09-11 16:57:41'),
(557, 'state', 32, 'State created. <br/><br/>State Name: Masbate<br/>Country ID: 174<br/>State Code: MAS', '0', '2023-09-11 16:57:41'),
(558, 'state', 33, 'State created. <br/><br/>State Name: Sorsogon<br/>Country ID: 174<br/>State Code: SOR', '0', '2023-09-11 16:57:41'),
(559, 'state', 34, 'State created. <br/><br/>State Name: Aklan<br/>Country ID: 174<br/>State Code: AKL', '0', '2023-09-11 16:57:41'),
(560, 'state', 35, 'State created. <br/><br/>State Name: Antique<br/>Country ID: 174<br/>State Code: ANT', '0', '2023-09-11 16:57:41'),
(561, 'state', 36, 'State created. <br/><br/>State Name: Capiz<br/>Country ID: 174<br/>State Code: CAP', '0', '2023-09-11 16:57:41'),
(562, 'state', 37, 'State created. <br/><br/>State Name: Iloilo<br/>Country ID: 174<br/>State Code: ILI', '0', '2023-09-11 16:57:41'),
(563, 'state', 38, 'State created. <br/><br/>State Name: Negros Occidental<br/>Country ID: 174<br/>State Code: MSR', '0', '2023-09-11 16:57:41'),
(564, 'state', 39, 'State created. <br/><br/>State Name: Guimaras<br/>Country ID: 174<br/>State Code: GUI', '0', '2023-09-11 16:57:41'),
(565, 'state', 40, 'State created. <br/><br/>State Name: Bohol<br/>Country ID: 174<br/>State Code: BOH', '0', '2023-09-11 16:57:41'),
(566, 'state', 41, 'State created. <br/><br/>State Name: Cebu<br/>Country ID: 174<br/>State Code: CEB', '0', '2023-09-11 16:57:41'),
(567, 'state', 42, 'State created. <br/><br/>State Name: Negros Oriental<br/>Country ID: 174<br/>State Code: MOU', '0', '2023-09-11 16:57:41'),
(568, 'state', 43, 'State created. <br/><br/>State Name: Siquijor<br/>Country ID: 174<br/>State Code: SIG', '0', '2023-09-11 16:57:41'),
(569, 'state', 44, 'State created. <br/><br/>State Name: Eastern Samar<br/>Country ID: 174<br/>State Code: EAS', '0', '2023-09-11 16:57:41'),
(570, 'state', 45, 'State created. <br/><br/>State Name: Leyte<br/>Country ID: 174<br/>State Code: LEY', '0', '2023-09-11 16:57:41'),
(571, 'state', 46, 'State created. <br/><br/>State Name: Northern Samar<br/>Country ID: 174<br/>State Code: NEC', '0', '2023-09-11 16:57:41'),
(572, 'state', 47, 'State created. <br/><br/>State Name: Samar<br/>Country ID: 174<br/>State Code: WSA', '0', '2023-09-11 16:57:41'),
(573, 'state', 48, 'State created. <br/><br/>State Name: Southern Leyte<br/>Country ID: 174<br/>State Code: SLE', '0', '2023-09-11 16:57:41'),
(574, 'state', 49, 'State created. <br/><br/>State Name: Biliran<br/>Country ID: 174<br/>State Code: BIL', '0', '2023-09-11 16:57:41'),
(575, 'state', 50, 'State created. <br/><br/>State Name: Zamboanga del Norte<br/>Country ID: 174<br/>State Code: ZAN', '0', '2023-09-11 16:57:41'),
(576, 'state', 51, 'State created. <br/><br/>State Name: Zamboanga del Sur<br/>Country ID: 174<br/>State Code: ZAS', '0', '2023-09-11 16:57:41'),
(577, 'state', 52, 'State created. <br/><br/>State Name: Zamboanga Sibugay<br/>Country ID: 174<br/>State Code: ZSI', '0', '2023-09-11 16:57:41'),
(578, 'state', 53, 'State created. <br/><br/>State Name: Bukidnon<br/>Country ID: 174<br/>State Code: BUK', '0', '2023-09-11 16:57:41'),
(579, 'state', 54, 'State created. <br/><br/>State Name: Camiguin<br/>Country ID: 174<br/>State Code: CAM', '0', '2023-09-11 16:57:41'),
(580, 'state', 55, 'State created. <br/><br/>State Name: Lanao del Norte<br/>Country ID: 174<br/>State Code: LAN', '0', '2023-09-11 16:57:41'),
(581, 'state', 56, 'State created. <br/><br/>State Name: Misamis Occidental<br/>Country ID: 174<br/>State Code: MDC', '0', '2023-09-11 16:57:41'),
(582, 'state', 57, 'State created. <br/><br/>State Name: Misamis Oriental<br/>Country ID: 174<br/>State Code: MDR', '0', '2023-09-11 16:57:41'),
(583, 'state', 58, 'State created. <br/><br/>State Name: Davao del Norte<br/>Country ID: 174<br/>State Code: DAV', '0', '2023-09-11 16:57:41'),
(584, 'state', 59, 'State created. <br/><br/>State Name: Davao del Sur<br/>Country ID: 174<br/>State Code: DAS', '0', '2023-09-11 16:57:41'),
(585, 'state', 60, 'State created. <br/><br/>State Name: Davao Oriental<br/>Country ID: 174<br/>State Code: DAO', '0', '2023-09-11 16:57:41'),
(586, 'state', 61, 'State created. <br/><br/>State Name: Davao de Oro<br/>Country ID: 174<br/>State Code: COM', '0', '2023-09-11 16:57:41'),
(587, 'state', 62, 'State created. <br/><br/>State Name: Davao Occidental<br/>Country ID: 174<br/>State Code: DVO', '0', '2023-09-11 16:57:41'),
(588, 'state', 63, 'State created. <br/><br/>State Name: Cotabato<br/>Country ID: 174<br/>State Code: COM', '0', '2023-09-11 16:57:41'),
(589, 'state', 64, 'State created. <br/><br/>State Name: South Cotabato<br/>Country ID: 174<br/>State Code: SCO', '0', '2023-09-11 16:57:41'),
(590, 'state', 65, 'State created. <br/><br/>State Name: Sultan Kudarat<br/>Country ID: 174<br/>State Code: SUK', '0', '2023-09-11 16:57:41'),
(591, 'state', 66, 'State created. <br/><br/>State Name: Sarangani<br/>Country ID: 174<br/>State Code: SAR', '0', '2023-09-11 16:57:41'),
(592, 'state', 67, 'State created. <br/><br/>State Name: Abra<br/>Country ID: 174<br/>State Code: ABR', '0', '2023-09-11 16:57:41'),
(593, 'state', 68, 'State created. <br/><br/>State Name: Benguet<br/>Country ID: 174<br/>State Code: BEN', '0', '2023-09-11 16:57:41'),
(594, 'state', 69, 'State created. <br/><br/>State Name: Ifugao<br/>Country ID: 174<br/>State Code: IFU', '0', '2023-09-11 16:57:41'),
(595, 'state', 70, 'State created. <br/><br/>State Name: Kalinga<br/>Country ID: 174<br/>State Code: KAL', '0', '2023-09-11 16:57:41'),
(596, 'state', 71, 'State created. <br/><br/>State Name: Mountain Province<br/>Country ID: 174<br/>State Code: MSC', '0', '2023-09-11 16:57:41'),
(597, 'state', 72, 'State created. <br/><br/>State Name: Apayao<br/>Country ID: 174<br/>State Code: APA', '0', '2023-09-11 16:57:41'),
(598, 'state', 73, 'State created. <br/><br/>State Name: Basilan<br/>Country ID: 174<br/>State Code: BAS', '0', '2023-09-11 16:57:41'),
(599, 'state', 74, 'State created. <br/><br/>State Name: Lanao del Sur<br/>Country ID: 174<br/>State Code: LAS', '0', '2023-09-11 16:57:41'),
(600, 'state', 75, 'State created. <br/><br/>State Name: Maguindanao<br/>Country ID: 174<br/>State Code: MAG', '0', '2023-09-11 16:57:41'),
(601, 'state', 76, 'State created. <br/><br/>State Name: Sulu<br/>Country ID: 174<br/>State Code: SLU', '0', '2023-09-11 16:57:41'),
(602, 'state', 77, 'State created. <br/><br/>State Name: Tawi-Tawi<br/>Country ID: 174<br/>State Code: TAW', '0', '2023-09-11 16:57:41'),
(603, 'state', 78, 'State created. <br/><br/>State Name: Agusan del Norte<br/>Country ID: 174<br/>State Code: AGN', '0', '2023-09-11 16:57:41'),
(604, 'state', 79, 'State created. <br/><br/>State Name: Agusan del Sur<br/>Country ID: 174<br/>State Code: AGS', '0', '2023-09-11 16:57:41'),
(605, 'state', 80, 'State created. <br/><br/>State Name: Surigao del Norte<br/>Country ID: 174<br/>State Code: SUN', '0', '2023-09-11 16:57:41'),
(606, 'state', 81, 'State created. <br/><br/>State Name: Surigao del Sur<br/>Country ID: 174<br/>State Code: SUR', '0', '2023-09-11 16:57:41'),
(607, 'state', 82, 'State created. <br/><br/>State Name: Dinagat Islands<br/>Country ID: 174<br/>State Code: DIN', '0', '2023-09-11 16:57:41'),
(608, 'city', 1, 'City created. <br/><br/>City Name: Adams<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(609, 'city', 2, 'City created. <br/><br/>City Name: Bacarra<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(610, 'city', 3, 'City created. <br/><br/>City Name: Badoc<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(611, 'city', 4, 'City created. <br/><br/>City Name: Bangui<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(612, 'city', 5, 'City created. <br/><br/>City Name: City of Batac<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(613, 'city', 6, 'City created. <br/><br/>City Name: Burgos<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(614, 'city', 7, 'City created. <br/><br/>City Name: Carasi<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(615, 'city', 8, 'City created. <br/><br/>City Name: Currimao<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(616, 'city', 9, 'City created. <br/><br/>City Name: Dingras<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(617, 'city', 10, 'City created. <br/><br/>City Name: Dumalneg<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(618, 'city', 11, 'City created. <br/><br/>City Name: Banna<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(619, 'city', 12, 'City created. <br/><br/>City Name: City of Laoag<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(620, 'city', 13, 'City created. <br/><br/>City Name: Marcos<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(621, 'city', 14, 'City created. <br/><br/>City Name: Nueva Era<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(622, 'city', 15, 'City created. <br/><br/>City Name: Pagudpud<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(623, 'city', 16, 'City created. <br/><br/>City Name: Paoay<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(624, 'city', 17, 'City created. <br/><br/>City Name: Pasuquin<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(625, 'city', 18, 'City created. <br/><br/>City Name: Piddig<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(626, 'city', 19, 'City created. <br/><br/>City Name: Pinili<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(627, 'city', 20, 'City created. <br/><br/>City Name: San Nicolas<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(628, 'city', 21, 'City created. <br/><br/>City Name: Sarrat<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(629, 'city', 22, 'City created. <br/><br/>City Name: Solsona<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(630, 'city', 23, 'City created. <br/><br/>City Name: Vintar<br/>State ID: 2', '0', '2023-09-11 16:57:41'),
(631, 'city', 24, 'City created. <br/><br/>City Name: Alilem<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(632, 'city', 25, 'City created. <br/><br/>City Name: Banayoyo<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(633, 'city', 26, 'City created. <br/><br/>City Name: Bantay<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(634, 'city', 27, 'City created. <br/><br/>City Name: Burgos<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(635, 'city', 28, 'City created. <br/><br/>City Name: Cabugao<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(636, 'city', 29, 'City created. <br/><br/>City Name: City of Candon<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(637, 'city', 30, 'City created. <br/><br/>City Name: Caoayan<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(638, 'city', 31, 'City created. <br/><br/>City Name: Cervantes<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(639, 'city', 32, 'City created. <br/><br/>City Name: Galimuyod<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(640, 'city', 33, 'City created. <br/><br/>City Name: Gregorio del Pilar<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(641, 'city', 34, 'City created. <br/><br/>City Name: Lidlidda<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(642, 'city', 35, 'City created. <br/><br/>City Name: Magsingal<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(643, 'city', 36, 'City created. <br/><br/>City Name: Nagbukel<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(644, 'city', 37, 'City created. <br/><br/>City Name: Narvacan<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(645, 'city', 38, 'City created. <br/><br/>City Name: Quirino<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(646, 'city', 39, 'City created. <br/><br/>City Name: Salcedo<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(647, 'city', 40, 'City created. <br/><br/>City Name: San Emilio<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(648, 'city', 41, 'City created. <br/><br/>City Name: San Esteban<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(649, 'city', 42, 'City created. <br/><br/>City Name: San Ildefonso<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(650, 'city', 43, 'City created. <br/><br/>City Name: San Juan<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(651, 'city', 44, 'City created. <br/><br/>City Name: San Vicente<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(652, 'city', 45, 'City created. <br/><br/>City Name: Santa<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(653, 'city', 46, 'City created. <br/><br/>City Name: Santa Catalina<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(654, 'city', 47, 'City created. <br/><br/>City Name: Santa Cruz<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(655, 'city', 48, 'City created. <br/><br/>City Name: Santa Lucia<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(656, 'city', 49, 'City created. <br/><br/>City Name: Santa Maria<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(657, 'city', 50, 'City created. <br/><br/>City Name: Santiago<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(658, 'city', 51, 'City created. <br/><br/>City Name: Santo Domingo<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(659, 'city', 52, 'City created. <br/><br/>City Name: Sigay<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(660, 'city', 53, 'City created. <br/><br/>City Name: Sinait<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(661, 'city', 54, 'City created. <br/><br/>City Name: Sugpon<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(662, 'city', 55, 'City created. <br/><br/>City Name: Suyo<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(663, 'city', 56, 'City created. <br/><br/>City Name: Tagudin<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(664, 'city', 57, 'City created. <br/><br/>City Name: City of Vigan<br/>State ID: 3', '0', '2023-09-11 16:57:41'),
(665, 'city', 58, 'City created. <br/><br/>City Name: Agoo<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(666, 'city', 59, 'City created. <br/><br/>City Name: Aringay<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(667, 'city', 60, 'City created. <br/><br/>City Name: Bacnotan<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(668, 'city', 61, 'City created. <br/><br/>City Name: Bagulin<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(669, 'city', 62, 'City created. <br/><br/>City Name: Balaoan<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(670, 'city', 63, 'City created. <br/><br/>City Name: Bangar<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(671, 'city', 64, 'City created. <br/><br/>City Name: Bauang<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(672, 'city', 65, 'City created. <br/><br/>City Name: Burgos<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(673, 'city', 66, 'City created. <br/><br/>City Name: Caba<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(674, 'city', 67, 'City created. <br/><br/>City Name: Luna<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(675, 'city', 68, 'City created. <br/><br/>City Name: Naguilian<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(676, 'city', 69, 'City created. <br/><br/>City Name: Pugo<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(677, 'city', 70, 'City created. <br/><br/>City Name: Rosario<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(678, 'city', 71, 'City created. <br/><br/>City Name: City of San Fernando<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(679, 'city', 72, 'City created. <br/><br/>City Name: San Gabriel<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(680, 'city', 73, 'City created. <br/><br/>City Name: San Juan<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(681, 'city', 74, 'City created. <br/><br/>City Name: Santo Tomas<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(682, 'city', 75, 'City created. <br/><br/>City Name: Santol<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(683, 'city', 76, 'City created. <br/><br/>City Name: Sudipen<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(684, 'city', 77, 'City created. <br/><br/>City Name: Tubao<br/>State ID: 4', '0', '2023-09-11 16:57:41'),
(685, 'city', 78, 'City created. <br/><br/>City Name: Agno<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(686, 'city', 79, 'City created. <br/><br/>City Name: Aguilar<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(687, 'city', 80, 'City created. <br/><br/>City Name: City of Alaminos<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(688, 'city', 81, 'City created. <br/><br/>City Name: Alcala<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(689, 'city', 82, 'City created. <br/><br/>City Name: Anda<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(690, 'city', 83, 'City created. <br/><br/>City Name: Asingan<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(691, 'city', 84, 'City created. <br/><br/>City Name: Balungao<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(692, 'city', 85, 'City created. <br/><br/>City Name: Bani<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(693, 'city', 86, 'City created. <br/><br/>City Name: Basista<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(694, 'city', 87, 'City created. <br/><br/>City Name: Bautista<br/>State ID: 5', '0', '2023-09-11 16:57:41');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(695, 'city', 88, 'City created. <br/><br/>City Name: Bayambang<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(696, 'city', 89, 'City created. <br/><br/>City Name: Binalonan<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(697, 'city', 90, 'City created. <br/><br/>City Name: Binmaley<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(698, 'city', 91, 'City created. <br/><br/>City Name: Bolinao<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(699, 'city', 92, 'City created. <br/><br/>City Name: Bugallon<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(700, 'city', 93, 'City created. <br/><br/>City Name: Burgos<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(701, 'city', 94, 'City created. <br/><br/>City Name: Calasiao<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(702, 'city', 95, 'City created. <br/><br/>City Name: City of Dagupan<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(703, 'city', 96, 'City created. <br/><br/>City Name: Dasol<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(704, 'city', 97, 'City created. <br/><br/>City Name: Infanta<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(705, 'city', 98, 'City created. <br/><br/>City Name: Labrador<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(706, 'city', 99, 'City created. <br/><br/>City Name: Lingayen<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(707, 'city', 100, 'City created. <br/><br/>City Name: Mabini<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(708, 'city', 101, 'City created. <br/><br/>City Name: Malasiqui<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(709, 'city', 102, 'City created. <br/><br/>City Name: Manaoag<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(710, 'city', 103, 'City created. <br/><br/>City Name: Mangaldan<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(711, 'city', 104, 'City created. <br/><br/>City Name: Mangatarem<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(712, 'city', 105, 'City created. <br/><br/>City Name: Mapandan<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(713, 'city', 106, 'City created. <br/><br/>City Name: Natividad<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(714, 'city', 107, 'City created. <br/><br/>City Name: Pozorrubio<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(715, 'city', 108, 'City created. <br/><br/>City Name: Rosales<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(716, 'city', 109, 'City created. <br/><br/>City Name: City of San Carlos<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(717, 'city', 110, 'City created. <br/><br/>City Name: San Fabian<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(718, 'city', 111, 'City created. <br/><br/>City Name: San Jacinto<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(719, 'city', 112, 'City created. <br/><br/>City Name: San Manuel<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(720, 'city', 113, 'City created. <br/><br/>City Name: San Nicolas<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(721, 'city', 114, 'City created. <br/><br/>City Name: San Quintin<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(722, 'city', 115, 'City created. <br/><br/>City Name: Santa Barbara<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(723, 'city', 116, 'City created. <br/><br/>City Name: Santa Maria<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(724, 'city', 117, 'City created. <br/><br/>City Name: Santo Tomas<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(725, 'city', 118, 'City created. <br/><br/>City Name: Sison<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(726, 'city', 119, 'City created. <br/><br/>City Name: Sual<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(727, 'city', 120, 'City created. <br/><br/>City Name: Tayug<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(728, 'city', 121, 'City created. <br/><br/>City Name: Umingan<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(729, 'city', 122, 'City created. <br/><br/>City Name: Urbiztondo<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(730, 'city', 123, 'City created. <br/><br/>City Name: City of Urdaneta<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(731, 'city', 124, 'City created. <br/><br/>City Name: Villasis<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(732, 'city', 125, 'City created. <br/><br/>City Name: Laoac<br/>State ID: 5', '0', '2023-09-11 16:57:41'),
(733, 'city', 126, 'City created. <br/><br/>City Name: Basco<br/>State ID: 6', '0', '2023-09-11 16:57:41'),
(734, 'city', 127, 'City created. <br/><br/>City Name: Itbayat<br/>State ID: 6', '0', '2023-09-11 16:57:41'),
(735, 'city', 128, 'City created. <br/><br/>City Name: Ivana<br/>State ID: 6', '0', '2023-09-11 16:57:41'),
(736, 'city', 129, 'City created. <br/><br/>City Name: Mahatao<br/>State ID: 6', '0', '2023-09-11 16:57:41'),
(737, 'city', 130, 'City created. <br/><br/>City Name: Sabtang<br/>State ID: 6', '0', '2023-09-11 16:57:41'),
(738, 'city', 131, 'City created. <br/><br/>City Name: Uyugan<br/>State ID: 6', '0', '2023-09-11 16:57:41'),
(739, 'city', 132, 'City created. <br/><br/>City Name: Abulug<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(740, 'city', 133, 'City created. <br/><br/>City Name: Alcala<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(741, 'city', 134, 'City created. <br/><br/>City Name: Allacapan<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(742, 'city', 135, 'City created. <br/><br/>City Name: Amulung<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(743, 'city', 136, 'City created. <br/><br/>City Name: Aparri<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(744, 'city', 137, 'City created. <br/><br/>City Name: Baggao<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(745, 'city', 138, 'City created. <br/><br/>City Name: Ballesteros<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(746, 'city', 139, 'City created. <br/><br/>City Name: Buguey<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(747, 'city', 140, 'City created. <br/><br/>City Name: Calayan<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(748, 'city', 141, 'City created. <br/><br/>City Name: Camalaniugan<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(749, 'city', 142, 'City created. <br/><br/>City Name: Claveria<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(750, 'city', 143, 'City created. <br/><br/>City Name: Enrile<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(751, 'city', 144, 'City created. <br/><br/>City Name: Gattaran<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(752, 'city', 145, 'City created. <br/><br/>City Name: Gonzaga<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(753, 'city', 146, 'City created. <br/><br/>City Name: Iguig<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(754, 'city', 147, 'City created. <br/><br/>City Name: Lal-Lo<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(755, 'city', 148, 'City created. <br/><br/>City Name: Lasam<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(756, 'city', 149, 'City created. <br/><br/>City Name: Pamplona<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(757, 'city', 150, 'City created. <br/><br/>City Name: Peñablanca<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(758, 'city', 151, 'City created. <br/><br/>City Name: Piat<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(759, 'city', 152, 'City created. <br/><br/>City Name: Rizal<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(760, 'city', 153, 'City created. <br/><br/>City Name: Sanchez-Mira<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(761, 'city', 154, 'City created. <br/><br/>City Name: Santa Ana<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(762, 'city', 155, 'City created. <br/><br/>City Name: Santa Praxedes<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(763, 'city', 156, 'City created. <br/><br/>City Name: Santa Teresita<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(764, 'city', 157, 'City created. <br/><br/>City Name: Santo Niño<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(765, 'city', 158, 'City created. <br/><br/>City Name: Solana<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(766, 'city', 159, 'City created. <br/><br/>City Name: Tuao<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(767, 'city', 160, 'City created. <br/><br/>City Name: Tuguegarao City<br/>State ID: 7', '0', '2023-09-11 16:57:41'),
(768, 'city', 161, 'City created. <br/><br/>City Name: Alicia<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(769, 'city', 162, 'City created. <br/><br/>City Name: Angadanan<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(770, 'city', 163, 'City created. <br/><br/>City Name: Aurora<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(771, 'city', 164, 'City created. <br/><br/>City Name: Benito Soliven<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(772, 'city', 165, 'City created. <br/><br/>City Name: Burgos<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(773, 'city', 166, 'City created. <br/><br/>City Name: Cabagan<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(774, 'city', 167, 'City created. <br/><br/>City Name: Cabatuan<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(775, 'city', 168, 'City created. <br/><br/>City Name: City of Cauayan<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(776, 'city', 169, 'City created. <br/><br/>City Name: Cordon<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(777, 'city', 170, 'City created. <br/><br/>City Name: Dinapigue<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(778, 'city', 171, 'City created. <br/><br/>City Name: Divilacan<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(779, 'city', 172, 'City created. <br/><br/>City Name: Echague<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(780, 'city', 173, 'City created. <br/><br/>City Name: Gamu<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(781, 'city', 174, 'City created. <br/><br/>City Name: City of Ilagan<br/>State ID: 8', '0', '2023-09-11 16:57:41'),
(782, 'city', 175, 'City created. <br/><br/>City Name: Jones<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(783, 'city', 176, 'City created. <br/><br/>City Name: Luna<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(784, 'city', 177, 'City created. <br/><br/>City Name: Maconacon<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(785, 'city', 178, 'City created. <br/><br/>City Name: Delfin Albano<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(786, 'city', 179, 'City created. <br/><br/>City Name: Mallig<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(787, 'city', 180, 'City created. <br/><br/>City Name: Naguilian<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(788, 'city', 181, 'City created. <br/><br/>City Name: Palanan<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(789, 'city', 182, 'City created. <br/><br/>City Name: Quezon<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(790, 'city', 183, 'City created. <br/><br/>City Name: Quirino<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(791, 'city', 184, 'City created. <br/><br/>City Name: Ramon<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(792, 'city', 185, 'City created. <br/><br/>City Name: Reina Mercedes<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(793, 'city', 186, 'City created. <br/><br/>City Name: Roxas<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(794, 'city', 187, 'City created. <br/><br/>City Name: San Agustin<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(795, 'city', 188, 'City created. <br/><br/>City Name: San Guillermo<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(796, 'city', 189, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(797, 'city', 190, 'City created. <br/><br/>City Name: San Manuel<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(798, 'city', 191, 'City created. <br/><br/>City Name: San Mariano<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(799, 'city', 192, 'City created. <br/><br/>City Name: San Mateo<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(800, 'city', 193, 'City created. <br/><br/>City Name: San Pablo<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(801, 'city', 194, 'City created. <br/><br/>City Name: Santa Maria<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(802, 'city', 195, 'City created. <br/><br/>City Name: City of Santiago<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(803, 'city', 196, 'City created. <br/><br/>City Name: Santo Tomas<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(804, 'city', 197, 'City created. <br/><br/>City Name: Tumauini<br/>State ID: 8', '0', '2023-09-11 16:57:42'),
(805, 'city', 198, 'City created. <br/><br/>City Name: Ambaguio<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(806, 'city', 199, 'City created. <br/><br/>City Name: Aritao<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(807, 'city', 200, 'City created. <br/><br/>City Name: Bagabag<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(808, 'city', 201, 'City created. <br/><br/>City Name: Bambang<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(809, 'city', 202, 'City created. <br/><br/>City Name: Bayombong<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(810, 'city', 203, 'City created. <br/><br/>City Name: Diadi<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(811, 'city', 204, 'City created. <br/><br/>City Name: Dupax del Norte<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(812, 'city', 205, 'City created. <br/><br/>City Name: Dupax del Sur<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(813, 'city', 206, 'City created. <br/><br/>City Name: Kasibu<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(814, 'city', 207, 'City created. <br/><br/>City Name: Kayapa<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(815, 'city', 208, 'City created. <br/><br/>City Name: Quezon<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(816, 'city', 209, 'City created. <br/><br/>City Name: Santa Fe<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(817, 'city', 210, 'City created. <br/><br/>City Name: Solano<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(818, 'city', 211, 'City created. <br/><br/>City Name: Villaverde<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(819, 'city', 212, 'City created. <br/><br/>City Name: Alfonso Castaneda<br/>State ID: 9', '0', '2023-09-11 16:57:42'),
(820, 'city', 213, 'City created. <br/><br/>City Name: Aglipay<br/>State ID: 10', '0', '2023-09-11 16:57:42'),
(821, 'city', 214, 'City created. <br/><br/>City Name: Cabarroguis<br/>State ID: 10', '0', '2023-09-11 16:57:42'),
(822, 'city', 215, 'City created. <br/><br/>City Name: Diffun<br/>State ID: 10', '0', '2023-09-11 16:57:42'),
(823, 'city', 216, 'City created. <br/><br/>City Name: Maddela<br/>State ID: 10', '0', '2023-09-11 16:57:42'),
(824, 'city', 217, 'City created. <br/><br/>City Name: Saguday<br/>State ID: 10', '0', '2023-09-11 16:57:42'),
(825, 'city', 218, 'City created. <br/><br/>City Name: Nagtipunan<br/>State ID: 10', '0', '2023-09-11 16:57:42'),
(826, 'city', 219, 'City created. <br/><br/>City Name: Abucay<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(827, 'city', 220, 'City created. <br/><br/>City Name: Bagac<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(828, 'city', 221, 'City created. <br/><br/>City Name: City of Balanga<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(829, 'city', 222, 'City created. <br/><br/>City Name: Dinalupihan<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(830, 'city', 223, 'City created. <br/><br/>City Name: Hermosa<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(831, 'city', 224, 'City created. <br/><br/>City Name: Limay<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(832, 'city', 225, 'City created. <br/><br/>City Name: Mariveles<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(833, 'city', 226, 'City created. <br/><br/>City Name: Morong<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(834, 'city', 227, 'City created. <br/><br/>City Name: Orani<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(835, 'city', 228, 'City created. <br/><br/>City Name: Orion<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(836, 'city', 229, 'City created. <br/><br/>City Name: Pilar<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(837, 'city', 230, 'City created. <br/><br/>City Name: Samal<br/>State ID: 11', '0', '2023-09-11 16:57:42'),
(838, 'city', 231, 'City created. <br/><br/>City Name: Angat<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(839, 'city', 232, 'City created. <br/><br/>City Name: Balagtas<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(840, 'city', 233, 'City created. <br/><br/>City Name: Baliuag<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(841, 'city', 234, 'City created. <br/><br/>City Name: Bocaue<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(842, 'city', 235, 'City created. <br/><br/>City Name: Bulacan<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(843, 'city', 236, 'City created. <br/><br/>City Name: Bustos<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(844, 'city', 237, 'City created. <br/><br/>City Name: Calumpit<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(845, 'city', 238, 'City created. <br/><br/>City Name: Guiguinto<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(846, 'city', 239, 'City created. <br/><br/>City Name: Hagonoy<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(847, 'city', 240, 'City created. <br/><br/>City Name: City of Malolos<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(848, 'city', 241, 'City created. <br/><br/>City Name: Marilao<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(849, 'city', 242, 'City created. <br/><br/>City Name: City of Meycauayan<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(850, 'city', 243, 'City created. <br/><br/>City Name: Norzagaray<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(851, 'city', 244, 'City created. <br/><br/>City Name: Obando<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(852, 'city', 245, 'City created. <br/><br/>City Name: Pandi<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(853, 'city', 246, 'City created. <br/><br/>City Name: Paombong<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(854, 'city', 247, 'City created. <br/><br/>City Name: Plaridel<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(855, 'city', 248, 'City created. <br/><br/>City Name: Pulilan<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(856, 'city', 249, 'City created. <br/><br/>City Name: San Ildefonso<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(857, 'city', 250, 'City created. <br/><br/>City Name: City of San Jose Del Monte<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(858, 'city', 251, 'City created. <br/><br/>City Name: San Miguel<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(859, 'city', 252, 'City created. <br/><br/>City Name: San Rafael<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(860, 'city', 253, 'City created. <br/><br/>City Name: Santa Maria<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(861, 'city', 254, 'City created. <br/><br/>City Name: Doña Remedios Trinidad<br/>State ID: 12', '0', '2023-09-11 16:57:42'),
(862, 'city', 255, 'City created. <br/><br/>City Name: Aliaga<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(863, 'city', 256, 'City created. <br/><br/>City Name: Bongabon<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(864, 'city', 257, 'City created. <br/><br/>City Name: City of Cabanatuan<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(865, 'city', 258, 'City created. <br/><br/>City Name: Cabiao<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(866, 'city', 259, 'City created. <br/><br/>City Name: Carranglan<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(867, 'city', 260, 'City created. <br/><br/>City Name: Cuyapo<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(868, 'city', 261, 'City created. <br/><br/>City Name: Gabaldon<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(869, 'city', 262, 'City created. <br/><br/>City Name: City of Gapan<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(870, 'city', 263, 'City created. <br/><br/>City Name: General Mamerto Natividad<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(871, 'city', 264, 'City created. <br/><br/>City Name: General Tinio<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(872, 'city', 265, 'City created. <br/><br/>City Name: Guimba<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(873, 'city', 266, 'City created. <br/><br/>City Name: Jaen<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(874, 'city', 267, 'City created. <br/><br/>City Name: Laur<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(875, 'city', 268, 'City created. <br/><br/>City Name: Licab<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(876, 'city', 269, 'City created. <br/><br/>City Name: Llanera<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(877, 'city', 270, 'City created. <br/><br/>City Name: Lupao<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(878, 'city', 271, 'City created. <br/><br/>City Name: Science City of Muñoz<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(879, 'city', 272, 'City created. <br/><br/>City Name: Nampicuan<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(880, 'city', 273, 'City created. <br/><br/>City Name: City of Palayan<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(881, 'city', 274, 'City created. <br/><br/>City Name: Pantabangan<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(882, 'city', 275, 'City created. <br/><br/>City Name: Peñaranda<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(883, 'city', 276, 'City created. <br/><br/>City Name: Quezon<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(884, 'city', 277, 'City created. <br/><br/>City Name: Rizal<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(885, 'city', 278, 'City created. <br/><br/>City Name: San Antonio<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(886, 'city', 279, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(887, 'city', 280, 'City created. <br/><br/>City Name: San Jose City<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(888, 'city', 281, 'City created. <br/><br/>City Name: San Leonardo<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(889, 'city', 282, 'City created. <br/><br/>City Name: Santa Rosa<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(890, 'city', 283, 'City created. <br/><br/>City Name: Santo Domingo<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(891, 'city', 284, 'City created. <br/><br/>City Name: Talavera<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(892, 'city', 285, 'City created. <br/><br/>City Name: Talugtug<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(893, 'city', 286, 'City created. <br/><br/>City Name: Zaragoza<br/>State ID: 13', '0', '2023-09-11 16:57:42'),
(894, 'city', 287, 'City created. <br/><br/>City Name: City of Angeles<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(895, 'city', 288, 'City created. <br/><br/>City Name: Apalit<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(896, 'city', 289, 'City created. <br/><br/>City Name: Arayat<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(897, 'city', 290, 'City created. <br/><br/>City Name: Bacolor<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(898, 'city', 291, 'City created. <br/><br/>City Name: Candaba<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(899, 'city', 292, 'City created. <br/><br/>City Name: Floridablanca<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(900, 'city', 293, 'City created. <br/><br/>City Name: Guagua<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(901, 'city', 294, 'City created. <br/><br/>City Name: Lubao<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(902, 'city', 295, 'City created. <br/><br/>City Name: Mabalacat City<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(903, 'city', 296, 'City created. <br/><br/>City Name: Macabebe<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(904, 'city', 297, 'City created. <br/><br/>City Name: Magalang<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(905, 'city', 298, 'City created. <br/><br/>City Name: Masantol<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(906, 'city', 299, 'City created. <br/><br/>City Name: Mexico<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(907, 'city', 300, 'City created. <br/><br/>City Name: Minalin<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(908, 'city', 301, 'City created. <br/><br/>City Name: Porac<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(909, 'city', 302, 'City created. <br/><br/>City Name: City of San Fernando<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(910, 'city', 303, 'City created. <br/><br/>City Name: San Luis<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(911, 'city', 304, 'City created. <br/><br/>City Name: San Simon<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(912, 'city', 305, 'City created. <br/><br/>City Name: Santa Ana<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(913, 'city', 306, 'City created. <br/><br/>City Name: Santa Rita<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(914, 'city', 307, 'City created. <br/><br/>City Name: Santo Tomas<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(915, 'city', 308, 'City created. <br/><br/>City Name: Sasmuan<br/>State ID: 14', '0', '2023-09-11 16:57:42'),
(916, 'city', 309, 'City created. <br/><br/>City Name: Anao<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(917, 'city', 310, 'City created. <br/><br/>City Name: Bamban<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(918, 'city', 311, 'City created. <br/><br/>City Name: Camiling<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(919, 'city', 312, 'City created. <br/><br/>City Name: Capas<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(920, 'city', 313, 'City created. <br/><br/>City Name: Concepcion<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(921, 'city', 314, 'City created. <br/><br/>City Name: Gerona<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(922, 'city', 315, 'City created. <br/><br/>City Name: La Paz<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(923, 'city', 316, 'City created. <br/><br/>City Name: Mayantoc<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(924, 'city', 317, 'City created. <br/><br/>City Name: Moncada<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(925, 'city', 318, 'City created. <br/><br/>City Name: Paniqui<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(926, 'city', 319, 'City created. <br/><br/>City Name: Pura<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(927, 'city', 320, 'City created. <br/><br/>City Name: Ramos<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(928, 'city', 321, 'City created. <br/><br/>City Name: San Clemente<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(929, 'city', 322, 'City created. <br/><br/>City Name: San Manuel<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(930, 'city', 323, 'City created. <br/><br/>City Name: Santa Ignacia<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(931, 'city', 324, 'City created. <br/><br/>City Name: City of Tarlac<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(932, 'city', 325, 'City created. <br/><br/>City Name: Victoria<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(933, 'city', 326, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 15', '0', '2023-09-11 16:57:42'),
(934, 'city', 327, 'City created. <br/><br/>City Name: Botolan<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(935, 'city', 328, 'City created. <br/><br/>City Name: Cabangan<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(936, 'city', 329, 'City created. <br/><br/>City Name: Candelaria<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(937, 'city', 330, 'City created. <br/><br/>City Name: Castillejos<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(938, 'city', 331, 'City created. <br/><br/>City Name: Iba<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(939, 'city', 332, 'City created. <br/><br/>City Name: Masinloc<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(940, 'city', 333, 'City created. <br/><br/>City Name: City of Olongapo<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(941, 'city', 334, 'City created. <br/><br/>City Name: Palauig<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(942, 'city', 335, 'City created. <br/><br/>City Name: San Antonio<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(943, 'city', 336, 'City created. <br/><br/>City Name: San Felipe<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(944, 'city', 337, 'City created. <br/><br/>City Name: San Marcelino<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(945, 'city', 338, 'City created. <br/><br/>City Name: San Narciso<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(946, 'city', 339, 'City created. <br/><br/>City Name: Santa Cruz<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(947, 'city', 340, 'City created. <br/><br/>City Name: Subic<br/>State ID: 16', '0', '2023-09-11 16:57:42'),
(948, 'city', 341, 'City created. <br/><br/>City Name: Baler<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(949, 'city', 342, 'City created. <br/><br/>City Name: Casiguran<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(950, 'city', 343, 'City created. <br/><br/>City Name: Dilasag<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(951, 'city', 344, 'City created. <br/><br/>City Name: Dinalungan<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(952, 'city', 345, 'City created. <br/><br/>City Name: Dingalan<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(953, 'city', 346, 'City created. <br/><br/>City Name: Dipaculao<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(954, 'city', 347, 'City created. <br/><br/>City Name: Maria Aurora<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(955, 'city', 348, 'City created. <br/><br/>City Name: San Luis<br/>State ID: 17', '0', '2023-09-11 16:57:42'),
(956, 'city', 349, 'City created. <br/><br/>City Name: Agoncillo<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(957, 'city', 350, 'City created. <br/><br/>City Name: Alitagtag<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(958, 'city', 351, 'City created. <br/><br/>City Name: Balayan<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(959, 'city', 352, 'City created. <br/><br/>City Name: Balete<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(960, 'city', 353, 'City created. <br/><br/>City Name: Batangas City<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(961, 'city', 354, 'City created. <br/><br/>City Name: Bauan<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(962, 'city', 355, 'City created. <br/><br/>City Name: Calaca<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(963, 'city', 356, 'City created. <br/><br/>City Name: Calatagan<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(964, 'city', 357, 'City created. <br/><br/>City Name: Cuenca<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(965, 'city', 358, 'City created. <br/><br/>City Name: Ibaan<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(966, 'city', 359, 'City created. <br/><br/>City Name: Laurel<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(967, 'city', 360, 'City created. <br/><br/>City Name: Lemery<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(968, 'city', 361, 'City created. <br/><br/>City Name: Lian<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(969, 'city', 362, 'City created. <br/><br/>City Name: City of Lipa<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(970, 'city', 363, 'City created. <br/><br/>City Name: Lobo<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(971, 'city', 364, 'City created. <br/><br/>City Name: Mabini<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(972, 'city', 365, 'City created. <br/><br/>City Name: Malvar<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(973, 'city', 366, 'City created. <br/><br/>City Name: Mataasnakahoy<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(974, 'city', 367, 'City created. <br/><br/>City Name: Nasugbu<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(975, 'city', 368, 'City created. <br/><br/>City Name: Padre Garcia<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(976, 'city', 369, 'City created. <br/><br/>City Name: Rosario<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(977, 'city', 370, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(978, 'city', 371, 'City created. <br/><br/>City Name: San Juan<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(979, 'city', 372, 'City created. <br/><br/>City Name: San Luis<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(980, 'city', 373, 'City created. <br/><br/>City Name: San Nicolas<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(981, 'city', 374, 'City created. <br/><br/>City Name: San Pascual<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(982, 'city', 375, 'City created. <br/><br/>City Name: Santa Teresita<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(983, 'city', 376, 'City created. <br/><br/>City Name: City of Sto. Tomas<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(984, 'city', 377, 'City created. <br/><br/>City Name: Taal<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(985, 'city', 378, 'City created. <br/><br/>City Name: Talisay<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(986, 'city', 379, 'City created. <br/><br/>City Name: City of Tanauan<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(987, 'city', 380, 'City created. <br/><br/>City Name: Taysan<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(988, 'city', 381, 'City created. <br/><br/>City Name: Tingloy<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(989, 'city', 382, 'City created. <br/><br/>City Name: Tuy<br/>State ID: 18', '0', '2023-09-11 16:57:42'),
(990, 'city', 383, 'City created. <br/><br/>City Name: Alfonso<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(991, 'city', 384, 'City created. <br/><br/>City Name: Amadeo<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(992, 'city', 385, 'City created. <br/><br/>City Name: City of Bacoor<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(993, 'city', 386, 'City created. <br/><br/>City Name: Carmona<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(994, 'city', 387, 'City created. <br/><br/>City Name: City of Cavite<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(995, 'city', 388, 'City created. <br/><br/>City Name: City of Dasmariñas<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(996, 'city', 389, 'City created. <br/><br/>City Name: General Emilio Aguinaldo<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(997, 'city', 390, 'City created. <br/><br/>City Name: City of General Trias<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(998, 'city', 391, 'City created. <br/><br/>City Name: City of Imus<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(999, 'city', 392, 'City created. <br/><br/>City Name: Indang<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1000, 'city', 393, 'City created. <br/><br/>City Name: Kawit<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1001, 'city', 394, 'City created. <br/><br/>City Name: Magallanes<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1002, 'city', 395, 'City created. <br/><br/>City Name: Maragondon<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1003, 'city', 396, 'City created. <br/><br/>City Name: Mendez<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1004, 'city', 397, 'City created. <br/><br/>City Name: Naic<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1005, 'city', 398, 'City created. <br/><br/>City Name: Noveleta<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1006, 'city', 399, 'City created. <br/><br/>City Name: Rosario<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1007, 'city', 400, 'City created. <br/><br/>City Name: Silang<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1008, 'city', 401, 'City created. <br/><br/>City Name: City of Tagaytay<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1009, 'city', 402, 'City created. <br/><br/>City Name: Tanza<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1010, 'city', 403, 'City created. <br/><br/>City Name: Ternate<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1011, 'city', 404, 'City created. <br/><br/>City Name: City of Trece Martires<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1012, 'city', 405, 'City created. <br/><br/>City Name: Gen. Mariano Alvarez<br/>State ID: 19', '0', '2023-09-11 16:57:42'),
(1013, 'city', 406, 'City created. <br/><br/>City Name: Alaminos<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1014, 'city', 407, 'City created. <br/><br/>City Name: Bay<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1015, 'city', 408, 'City created. <br/><br/>City Name: City of Biñan<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1016, 'city', 409, 'City created. <br/><br/>City Name: City of Cabuyao<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1017, 'city', 410, 'City created. <br/><br/>City Name: City of Calamba<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1018, 'city', 411, 'City created. <br/><br/>City Name: Calauan<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1019, 'city', 412, 'City created. <br/><br/>City Name: Cavinti<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1020, 'city', 413, 'City created. <br/><br/>City Name: Famy<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1021, 'city', 414, 'City created. <br/><br/>City Name: Kalayaan<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1022, 'city', 415, 'City created. <br/><br/>City Name: Liliw<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1023, 'city', 416, 'City created. <br/><br/>City Name: Los Baños<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1024, 'city', 417, 'City created. <br/><br/>City Name: Luisiana<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1025, 'city', 418, 'City created. <br/><br/>City Name: Lumban<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1026, 'city', 419, 'City created. <br/><br/>City Name: Mabitac<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1027, 'city', 420, 'City created. <br/><br/>City Name: Magdalena<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1028, 'city', 421, 'City created. <br/><br/>City Name: Majayjay<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1029, 'city', 422, 'City created. <br/><br/>City Name: Nagcarlan<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1030, 'city', 423, 'City created. <br/><br/>City Name: Paete<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1031, 'city', 424, 'City created. <br/><br/>City Name: Pagsanjan<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1032, 'city', 425, 'City created. <br/><br/>City Name: Pakil<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1033, 'city', 426, 'City created. <br/><br/>City Name: Pangil<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1034, 'city', 427, 'City created. <br/><br/>City Name: Pila<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1035, 'city', 428, 'City created. <br/><br/>City Name: Rizal<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1036, 'city', 429, 'City created. <br/><br/>City Name: City of San Pablo<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1037, 'city', 430, 'City created. <br/><br/>City Name: City of San Pedro<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1038, 'city', 431, 'City created. <br/><br/>City Name: Santa Cruz<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1039, 'city', 432, 'City created. <br/><br/>City Name: Santa Maria<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1040, 'city', 433, 'City created. <br/><br/>City Name: City of Santa Rosa<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1041, 'city', 434, 'City created. <br/><br/>City Name: Siniloan<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1042, 'city', 435, 'City created. <br/><br/>City Name: Victoria<br/>State ID: 20', '0', '2023-09-11 16:57:42'),
(1043, 'city', 436, 'City created. <br/><br/>City Name: Agdangan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1044, 'city', 437, 'City created. <br/><br/>City Name: Alabat<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1045, 'city', 438, 'City created. <br/><br/>City Name: Atimonan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1046, 'city', 439, 'City created. <br/><br/>City Name: Buenavista<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1047, 'city', 440, 'City created. <br/><br/>City Name: Burdeos<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1048, 'city', 441, 'City created. <br/><br/>City Name: Calauag<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1049, 'city', 442, 'City created. <br/><br/>City Name: Candelaria<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1050, 'city', 443, 'City created. <br/><br/>City Name: Catanauan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1051, 'city', 444, 'City created. <br/><br/>City Name: Dolores<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1052, 'city', 445, 'City created. <br/><br/>City Name: General Luna<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1053, 'city', 446, 'City created. <br/><br/>City Name: General Nakar<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1054, 'city', 447, 'City created. <br/><br/>City Name: Guinayangan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1055, 'city', 448, 'City created. <br/><br/>City Name: Gumaca<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1056, 'city', 449, 'City created. <br/><br/>City Name: Infanta<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1057, 'city', 450, 'City created. <br/><br/>City Name: Jomalig<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1058, 'city', 451, 'City created. <br/><br/>City Name: Lopez<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1059, 'city', 452, 'City created. <br/><br/>City Name: Lucban<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1060, 'city', 453, 'City created. <br/><br/>City Name: City of Lucena<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1061, 'city', 454, 'City created. <br/><br/>City Name: Macalelon<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1062, 'city', 455, 'City created. <br/><br/>City Name: Mauban<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1063, 'city', 456, 'City created. <br/><br/>City Name: Mulanay<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1064, 'city', 457, 'City created. <br/><br/>City Name: Padre Burgos<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1065, 'city', 458, 'City created. <br/><br/>City Name: Pagbilao<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1066, 'city', 459, 'City created. <br/><br/>City Name: Panukulan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1067, 'city', 460, 'City created. <br/><br/>City Name: Patnanungan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1068, 'city', 461, 'City created. <br/><br/>City Name: Perez<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1069, 'city', 462, 'City created. <br/><br/>City Name: Pitogo<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1070, 'city', 463, 'City created. <br/><br/>City Name: Plaridel<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1071, 'city', 464, 'City created. <br/><br/>City Name: Polillo<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1072, 'city', 465, 'City created. <br/><br/>City Name: Quezon<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1073, 'city', 466, 'City created. <br/><br/>City Name: Real<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1074, 'city', 467, 'City created. <br/><br/>City Name: Sampaloc<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1075, 'city', 468, 'City created. <br/><br/>City Name: San Andres<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1076, 'city', 469, 'City created. <br/><br/>City Name: San Antonio<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1077, 'city', 470, 'City created. <br/><br/>City Name: San Francisco<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1078, 'city', 471, 'City created. <br/><br/>City Name: San Narciso<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1079, 'city', 472, 'City created. <br/><br/>City Name: Sariaya<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1080, 'city', 473, 'City created. <br/><br/>City Name: Tagkawayan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1081, 'city', 474, 'City created. <br/><br/>City Name: City of Tayabas<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1082, 'city', 475, 'City created. <br/><br/>City Name: Tiaong<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1083, 'city', 476, 'City created. <br/><br/>City Name: Unisan<br/>State ID: 21', '0', '2023-09-11 16:57:42'),
(1084, 'city', 477, 'City created. <br/><br/>City Name: Angono<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1085, 'city', 478, 'City created. <br/><br/>City Name: City of Antipolo<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1086, 'city', 479, 'City created. <br/><br/>City Name: Baras<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1087, 'city', 480, 'City created. <br/><br/>City Name: Binangonan<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1088, 'city', 481, 'City created. <br/><br/>City Name: Cainta<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1089, 'city', 482, 'City created. <br/><br/>City Name: Cardona<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1090, 'city', 483, 'City created. <br/><br/>City Name: Jala-Jala<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1091, 'city', 484, 'City created. <br/><br/>City Name: Rodriguez<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1092, 'city', 485, 'City created. <br/><br/>City Name: Morong<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1093, 'city', 486, 'City created. <br/><br/>City Name: Pililla<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1094, 'city', 487, 'City created. <br/><br/>City Name: San Mateo<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1095, 'city', 488, 'City created. <br/><br/>City Name: Tanay<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1096, 'city', 489, 'City created. <br/><br/>City Name: Taytay<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1097, 'city', 490, 'City created. <br/><br/>City Name: Teresa<br/>State ID: 22', '0', '2023-09-11 16:57:42'),
(1098, 'city', 491, 'City created. <br/><br/>City Name: Boac<br/>State ID: 23', '0', '2023-09-11 16:57:42'),
(1099, 'city', 492, 'City created. <br/><br/>City Name: Buenavista<br/>State ID: 23', '0', '2023-09-11 16:57:42'),
(1100, 'city', 493, 'City created. <br/><br/>City Name: Gasan<br/>State ID: 23', '0', '2023-09-11 16:57:42'),
(1101, 'city', 494, 'City created. <br/><br/>City Name: Mogpog<br/>State ID: 23', '0', '2023-09-11 16:57:42'),
(1102, 'city', 495, 'City created. <br/><br/>City Name: Santa Cruz<br/>State ID: 23', '0', '2023-09-11 16:57:42'),
(1103, 'city', 496, 'City created. <br/><br/>City Name: Torrijos<br/>State ID: 23', '0', '2023-09-11 16:57:42'),
(1104, 'city', 497, 'City created. <br/><br/>City Name: Abra De Ilog<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1105, 'city', 498, 'City created. <br/><br/>City Name: Calintaan<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1106, 'city', 499, 'City created. <br/><br/>City Name: Looc<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1107, 'city', 500, 'City created. <br/><br/>City Name: Lubang<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1108, 'city', 501, 'City created. <br/><br/>City Name: Magsaysay<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1109, 'city', 502, 'City created. <br/><br/>City Name: Mamburao<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1110, 'city', 503, 'City created. <br/><br/>City Name: Paluan<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1111, 'city', 504, 'City created. <br/><br/>City Name: Rizal<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1112, 'city', 505, 'City created. <br/><br/>City Name: Sablayan<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1113, 'city', 506, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1114, 'city', 507, 'City created. <br/><br/>City Name: Santa Cruz<br/>State ID: 24', '0', '2023-09-11 16:57:42'),
(1115, 'city', 508, 'City created. <br/><br/>City Name: Baco<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1116, 'city', 509, 'City created. <br/><br/>City Name: Bansud<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1117, 'city', 510, 'City created. <br/><br/>City Name: Bongabong<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1118, 'city', 511, 'City created. <br/><br/>City Name: Bulalacao<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1119, 'city', 512, 'City created. <br/><br/>City Name: City of Calapan<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1120, 'city', 513, 'City created. <br/><br/>City Name: Gloria<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1121, 'city', 514, 'City created. <br/><br/>City Name: Mansalay<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1122, 'city', 515, 'City created. <br/><br/>City Name: Naujan<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1123, 'city', 516, 'City created. <br/><br/>City Name: Pinamalayan<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1124, 'city', 517, 'City created. <br/><br/>City Name: Pola<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1125, 'city', 518, 'City created. <br/><br/>City Name: Puerto Galera<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1126, 'city', 519, 'City created. <br/><br/>City Name: Roxas<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1127, 'city', 520, 'City created. <br/><br/>City Name: San Teodoro<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1128, 'city', 521, 'City created. <br/><br/>City Name: Socorro<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1129, 'city', 522, 'City created. <br/><br/>City Name: Victoria<br/>State ID: 25', '0', '2023-09-11 16:57:42'),
(1130, 'city', 523, 'City created. <br/><br/>City Name: Aborlan<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1131, 'city', 524, 'City created. <br/><br/>City Name: Agutaya<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1132, 'city', 525, 'City created. <br/><br/>City Name: Araceli<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1133, 'city', 526, 'City created. <br/><br/>City Name: Balabac<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1134, 'city', 527, 'City created. <br/><br/>City Name: Bataraza<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1135, 'city', 528, 'City created. <br/><br/>City Name: Brooke S Point<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1136, 'city', 529, 'City created. <br/><br/>City Name: Busuanga<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1137, 'city', 530, 'City created. <br/><br/>City Name: Cagayancillo<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1138, 'city', 531, 'City created. <br/><br/>City Name: Coron<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1139, 'city', 532, 'City created. <br/><br/>City Name: Cuyo<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1140, 'city', 533, 'City created. <br/><br/>City Name: Dumaran<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1141, 'city', 534, 'City created. <br/><br/>City Name: El Nido<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1142, 'city', 535, 'City created. <br/><br/>City Name: Linapacan<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1143, 'city', 536, 'City created. <br/><br/>City Name: Magsaysay<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1144, 'city', 537, 'City created. <br/><br/>City Name: Narra<br/>State ID: 26', '0', '2023-09-11 16:57:42');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(1145, 'city', 538, 'City created. <br/><br/>City Name: City of Puerto Princesa<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1146, 'city', 539, 'City created. <br/><br/>City Name: Quezon<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1147, 'city', 540, 'City created. <br/><br/>City Name: Roxas<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1148, 'city', 541, 'City created. <br/><br/>City Name: San Vicente<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1149, 'city', 542, 'City created. <br/><br/>City Name: Taytay<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1150, 'city', 543, 'City created. <br/><br/>City Name: Kalayaan<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1151, 'city', 544, 'City created. <br/><br/>City Name: Culion<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1152, 'city', 545, 'City created. <br/><br/>City Name: Rizal<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1153, 'city', 546, 'City created. <br/><br/>City Name: Sofronio Española<br/>State ID: 26', '0', '2023-09-11 16:57:42'),
(1154, 'city', 547, 'City created. <br/><br/>City Name: Alcantara<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1155, 'city', 548, 'City created. <br/><br/>City Name: Banton<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1156, 'city', 549, 'City created. <br/><br/>City Name: Cajidiocan<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1157, 'city', 550, 'City created. <br/><br/>City Name: Calatrava<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1158, 'city', 551, 'City created. <br/><br/>City Name: Concepcion<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1159, 'city', 552, 'City created. <br/><br/>City Name: Corcuera<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1160, 'city', 553, 'City created. <br/><br/>City Name: Looc<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1161, 'city', 554, 'City created. <br/><br/>City Name: Magdiwang<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1162, 'city', 555, 'City created. <br/><br/>City Name: Odiongan<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1163, 'city', 556, 'City created. <br/><br/>City Name: Romblon<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1164, 'city', 557, 'City created. <br/><br/>City Name: San Agustin<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1165, 'city', 558, 'City created. <br/><br/>City Name: San Andres<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1166, 'city', 559, 'City created. <br/><br/>City Name: San Fernando<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1167, 'city', 560, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1168, 'city', 561, 'City created. <br/><br/>City Name: Santa Fe<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1169, 'city', 562, 'City created. <br/><br/>City Name: Ferrol<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1170, 'city', 563, 'City created. <br/><br/>City Name: Santa Maria<br/>State ID: 27', '0', '2023-09-11 16:57:42'),
(1171, 'city', 564, 'City created. <br/><br/>City Name: Bacacay<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1172, 'city', 565, 'City created. <br/><br/>City Name: Camalig<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1173, 'city', 566, 'City created. <br/><br/>City Name: Daraga<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1174, 'city', 567, 'City created. <br/><br/>City Name: Guinobatan<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1175, 'city', 568, 'City created. <br/><br/>City Name: Jovellar<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1176, 'city', 569, 'City created. <br/><br/>City Name: City of Legazpi<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1177, 'city', 570, 'City created. <br/><br/>City Name: Libon<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1178, 'city', 571, 'City created. <br/><br/>City Name: City of Ligao<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1179, 'city', 572, 'City created. <br/><br/>City Name: Malilipot<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1180, 'city', 573, 'City created. <br/><br/>City Name: Malinao<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1181, 'city', 574, 'City created. <br/><br/>City Name: Manito<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1182, 'city', 575, 'City created. <br/><br/>City Name: Oas<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1183, 'city', 576, 'City created. <br/><br/>City Name: Pio Duran<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1184, 'city', 577, 'City created. <br/><br/>City Name: Polangui<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1185, 'city', 578, 'City created. <br/><br/>City Name: Rapu-Rapu<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1186, 'city', 579, 'City created. <br/><br/>City Name: Santo Domingo<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1187, 'city', 580, 'City created. <br/><br/>City Name: City of Tabaco<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1188, 'city', 581, 'City created. <br/><br/>City Name: Tiwi<br/>State ID: 28', '0', '2023-09-11 16:57:42'),
(1189, 'city', 582, 'City created. <br/><br/>City Name: Basud<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1190, 'city', 583, 'City created. <br/><br/>City Name: Capalonga<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1191, 'city', 584, 'City created. <br/><br/>City Name: Daet<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1192, 'city', 585, 'City created. <br/><br/>City Name: San Lorenzo Ruiz<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1193, 'city', 586, 'City created. <br/><br/>City Name: Jose Panganiban<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1194, 'city', 587, 'City created. <br/><br/>City Name: Labo<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1195, 'city', 588, 'City created. <br/><br/>City Name: Mercedes<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1196, 'city', 589, 'City created. <br/><br/>City Name: Paracale<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1197, 'city', 590, 'City created. <br/><br/>City Name: San Vicente<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1198, 'city', 591, 'City created. <br/><br/>City Name: Santa Elena<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1199, 'city', 592, 'City created. <br/><br/>City Name: Talisay<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1200, 'city', 593, 'City created. <br/><br/>City Name: Vinzons<br/>State ID: 29', '0', '2023-09-11 16:57:42'),
(1201, 'city', 594, 'City created. <br/><br/>City Name: Baao<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1202, 'city', 595, 'City created. <br/><br/>City Name: Balatan<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1203, 'city', 596, 'City created. <br/><br/>City Name: Bato<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1204, 'city', 597, 'City created. <br/><br/>City Name: Bombon<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1205, 'city', 598, 'City created. <br/><br/>City Name: Buhi<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1206, 'city', 599, 'City created. <br/><br/>City Name: Bula<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1207, 'city', 600, 'City created. <br/><br/>City Name: Cabusao<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1208, 'city', 601, 'City created. <br/><br/>City Name: Calabanga<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1209, 'city', 602, 'City created. <br/><br/>City Name: Camaligan<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1210, 'city', 603, 'City created. <br/><br/>City Name: Canaman<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1211, 'city', 604, 'City created. <br/><br/>City Name: Caramoan<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1212, 'city', 605, 'City created. <br/><br/>City Name: Del Gallego<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1213, 'city', 606, 'City created. <br/><br/>City Name: Gainza<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1214, 'city', 607, 'City created. <br/><br/>City Name: Garchitorena<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1215, 'city', 608, 'City created. <br/><br/>City Name: Goa<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1216, 'city', 609, 'City created. <br/><br/>City Name: City of Iriga<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1217, 'city', 610, 'City created. <br/><br/>City Name: Lagonoy<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1218, 'city', 611, 'City created. <br/><br/>City Name: Libmanan<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1219, 'city', 612, 'City created. <br/><br/>City Name: Lupi<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1220, 'city', 613, 'City created. <br/><br/>City Name: Magarao<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1221, 'city', 614, 'City created. <br/><br/>City Name: Milaor<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1222, 'city', 615, 'City created. <br/><br/>City Name: Minalabac<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1223, 'city', 616, 'City created. <br/><br/>City Name: Nabua<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1224, 'city', 617, 'City created. <br/><br/>City Name: City of Naga<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1225, 'city', 618, 'City created. <br/><br/>City Name: Ocampo<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1226, 'city', 619, 'City created. <br/><br/>City Name: Pamplona<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1227, 'city', 620, 'City created. <br/><br/>City Name: Pasacao<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1228, 'city', 621, 'City created. <br/><br/>City Name: Pili<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1229, 'city', 622, 'City created. <br/><br/>City Name: Presentacion<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1230, 'city', 623, 'City created. <br/><br/>City Name: Ragay<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1231, 'city', 624, 'City created. <br/><br/>City Name: Sagñay<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1232, 'city', 625, 'City created. <br/><br/>City Name: San Fernando<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1233, 'city', 626, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1234, 'city', 627, 'City created. <br/><br/>City Name: Sipocot<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1235, 'city', 628, 'City created. <br/><br/>City Name: Siruma<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1236, 'city', 629, 'City created. <br/><br/>City Name: Tigaon<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1237, 'city', 630, 'City created. <br/><br/>City Name: Tinambac<br/>State ID: 30', '0', '2023-09-11 16:57:42'),
(1238, 'city', 631, 'City created. <br/><br/>City Name: Bagamanoc<br/>State ID: 31', '0', '2023-09-11 16:57:42'),
(1239, 'city', 632, 'City created. <br/><br/>City Name: Baras<br/>State ID: 31', '0', '2023-09-11 16:57:42'),
(1240, 'city', 633, 'City created. <br/><br/>City Name: Bato<br/>State ID: 31', '0', '2023-09-11 16:57:42'),
(1241, 'city', 634, 'City created. <br/><br/>City Name: Caramoran<br/>State ID: 31', '0', '2023-09-11 16:57:42'),
(1242, 'city', 635, 'City created. <br/><br/>City Name: Gigmoto<br/>State ID: 31', '0', '2023-09-11 16:57:42'),
(1243, 'city', 636, 'City created. <br/><br/>City Name: Pandan<br/>State ID: 31', '0', '2023-09-11 16:57:43'),
(1244, 'city', 637, 'City created. <br/><br/>City Name: Panganiban<br/>State ID: 31', '0', '2023-09-11 16:57:43'),
(1245, 'city', 638, 'City created. <br/><br/>City Name: San Andres<br/>State ID: 31', '0', '2023-09-11 16:57:43'),
(1246, 'city', 639, 'City created. <br/><br/>City Name: San Miguel<br/>State ID: 31', '0', '2023-09-11 16:57:43'),
(1247, 'city', 640, 'City created. <br/><br/>City Name: Viga<br/>State ID: 31', '0', '2023-09-11 16:57:43'),
(1248, 'city', 641, 'City created. <br/><br/>City Name: Virac<br/>State ID: 31', '0', '2023-09-11 16:57:43'),
(1249, 'city', 642, 'City created. <br/><br/>City Name: Aroroy<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1250, 'city', 643, 'City created. <br/><br/>City Name: Baleno<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1251, 'city', 644, 'City created. <br/><br/>City Name: Balud<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1252, 'city', 645, 'City created. <br/><br/>City Name: Batuan<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1253, 'city', 646, 'City created. <br/><br/>City Name: Cataingan<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1254, 'city', 647, 'City created. <br/><br/>City Name: Cawayan<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1255, 'city', 648, 'City created. <br/><br/>City Name: Claveria<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1256, 'city', 649, 'City created. <br/><br/>City Name: Dimasalang<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1257, 'city', 650, 'City created. <br/><br/>City Name: Esperanza<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1258, 'city', 651, 'City created. <br/><br/>City Name: Mandaon<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1259, 'city', 652, 'City created. <br/><br/>City Name: City of Masbate<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1260, 'city', 653, 'City created. <br/><br/>City Name: Milagros<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1261, 'city', 654, 'City created. <br/><br/>City Name: Mobo<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1262, 'city', 655, 'City created. <br/><br/>City Name: Monreal<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1263, 'city', 656, 'City created. <br/><br/>City Name: Palanas<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1264, 'city', 657, 'City created. <br/><br/>City Name: Pio V. Corpuz<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1265, 'city', 658, 'City created. <br/><br/>City Name: Placer<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1266, 'city', 659, 'City created. <br/><br/>City Name: San Fernando<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1267, 'city', 660, 'City created. <br/><br/>City Name: San Jacinto<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1268, 'city', 661, 'City created. <br/><br/>City Name: San Pascual<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1269, 'city', 662, 'City created. <br/><br/>City Name: Uson<br/>State ID: 32', '0', '2023-09-11 16:57:43'),
(1270, 'city', 663, 'City created. <br/><br/>City Name: Barcelona<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1271, 'city', 664, 'City created. <br/><br/>City Name: Bulan<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1272, 'city', 665, 'City created. <br/><br/>City Name: Bulusan<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1273, 'city', 666, 'City created. <br/><br/>City Name: Casiguran<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1274, 'city', 667, 'City created. <br/><br/>City Name: Castilla<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1275, 'city', 668, 'City created. <br/><br/>City Name: Donsol<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1276, 'city', 669, 'City created. <br/><br/>City Name: Gubat<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1277, 'city', 670, 'City created. <br/><br/>City Name: Irosin<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1278, 'city', 671, 'City created. <br/><br/>City Name: Juban<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1279, 'city', 672, 'City created. <br/><br/>City Name: Magallanes<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1280, 'city', 673, 'City created. <br/><br/>City Name: Matnog<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1281, 'city', 674, 'City created. <br/><br/>City Name: Pilar<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1282, 'city', 675, 'City created. <br/><br/>City Name: Prieto Diaz<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1283, 'city', 676, 'City created. <br/><br/>City Name: Santa Magdalena<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1284, 'city', 677, 'City created. <br/><br/>City Name: City of Sorsogon<br/>State ID: 33', '0', '2023-09-11 16:57:43'),
(1285, 'city', 678, 'City created. <br/><br/>City Name: Altavas<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1286, 'city', 679, 'City created. <br/><br/>City Name: Balete<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1287, 'city', 680, 'City created. <br/><br/>City Name: Banga<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1288, 'city', 681, 'City created. <br/><br/>City Name: Batan<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1289, 'city', 682, 'City created. <br/><br/>City Name: Buruanga<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1290, 'city', 683, 'City created. <br/><br/>City Name: Ibajay<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1291, 'city', 684, 'City created. <br/><br/>City Name: Kalibo<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1292, 'city', 685, 'City created. <br/><br/>City Name: Lezo<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1293, 'city', 686, 'City created. <br/><br/>City Name: Libacao<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1294, 'city', 687, 'City created. <br/><br/>City Name: Madalag<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1295, 'city', 688, 'City created. <br/><br/>City Name: Makato<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1296, 'city', 689, 'City created. <br/><br/>City Name: Malay<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1297, 'city', 690, 'City created. <br/><br/>City Name: Malinao<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1298, 'city', 691, 'City created. <br/><br/>City Name: Nabas<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1299, 'city', 692, 'City created. <br/><br/>City Name: New Washington<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1300, 'city', 693, 'City created. <br/><br/>City Name: Numancia<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1301, 'city', 694, 'City created. <br/><br/>City Name: Tangalan<br/>State ID: 34', '0', '2023-09-11 16:57:43'),
(1302, 'city', 695, 'City created. <br/><br/>City Name: Anini-Y<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1303, 'city', 696, 'City created. <br/><br/>City Name: Barbaza<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1304, 'city', 697, 'City created. <br/><br/>City Name: Belison<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1305, 'city', 698, 'City created. <br/><br/>City Name: Bugasong<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1306, 'city', 699, 'City created. <br/><br/>City Name: Caluya<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1307, 'city', 700, 'City created. <br/><br/>City Name: Culasi<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1308, 'city', 701, 'City created. <br/><br/>City Name: Tobias Fornier<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1309, 'city', 702, 'City created. <br/><br/>City Name: Hamtic<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1310, 'city', 703, 'City created. <br/><br/>City Name: Laua-An<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1311, 'city', 704, 'City created. <br/><br/>City Name: Libertad<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1312, 'city', 705, 'City created. <br/><br/>City Name: Pandan<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1313, 'city', 706, 'City created. <br/><br/>City Name: Patnongon<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1314, 'city', 707, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1315, 'city', 708, 'City created. <br/><br/>City Name: San Remigio<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1316, 'city', 709, 'City created. <br/><br/>City Name: Sebaste<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1317, 'city', 710, 'City created. <br/><br/>City Name: Sibalom<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1318, 'city', 711, 'City created. <br/><br/>City Name: Tibiao<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1319, 'city', 712, 'City created. <br/><br/>City Name: Valderrama<br/>State ID: 35', '0', '2023-09-11 16:57:43'),
(1320, 'city', 713, 'City created. <br/><br/>City Name: Cuartero<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1321, 'city', 714, 'City created. <br/><br/>City Name: Dao<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1322, 'city', 715, 'City created. <br/><br/>City Name: Dumalag<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1323, 'city', 716, 'City created. <br/><br/>City Name: Dumarao<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1324, 'city', 717, 'City created. <br/><br/>City Name: Ivisan<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1325, 'city', 718, 'City created. <br/><br/>City Name: Jamindan<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1326, 'city', 719, 'City created. <br/><br/>City Name: Ma-Ayon<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1327, 'city', 720, 'City created. <br/><br/>City Name: Mambusao<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1328, 'city', 721, 'City created. <br/><br/>City Name: Panay<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1329, 'city', 722, 'City created. <br/><br/>City Name: Panitan<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1330, 'city', 723, 'City created. <br/><br/>City Name: Pilar<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1331, 'city', 724, 'City created. <br/><br/>City Name: Pontevedra<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1332, 'city', 725, 'City created. <br/><br/>City Name: President Roxas<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1333, 'city', 726, 'City created. <br/><br/>City Name: City of Roxas<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1334, 'city', 727, 'City created. <br/><br/>City Name: Sapi-An<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1335, 'city', 728, 'City created. <br/><br/>City Name: Sigma<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1336, 'city', 729, 'City created. <br/><br/>City Name: Tapaz<br/>State ID: 36', '0', '2023-09-11 16:57:43'),
(1337, 'city', 730, 'City created. <br/><br/>City Name: Ajuy<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1338, 'city', 731, 'City created. <br/><br/>City Name: Alimodian<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1339, 'city', 732, 'City created. <br/><br/>City Name: Anilao<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1340, 'city', 733, 'City created. <br/><br/>City Name: Badiangan<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1341, 'city', 734, 'City created. <br/><br/>City Name: Balasan<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1342, 'city', 735, 'City created. <br/><br/>City Name: Banate<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1343, 'city', 736, 'City created. <br/><br/>City Name: Barotac Nuevo<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1344, 'city', 737, 'City created. <br/><br/>City Name: Barotac Viejo<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1345, 'city', 738, 'City created. <br/><br/>City Name: Batad<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1346, 'city', 739, 'City created. <br/><br/>City Name: Bingawan<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1347, 'city', 740, 'City created. <br/><br/>City Name: Cabatuan<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1348, 'city', 741, 'City created. <br/><br/>City Name: Calinog<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1349, 'city', 742, 'City created. <br/><br/>City Name: Carles<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1350, 'city', 743, 'City created. <br/><br/>City Name: Concepcion<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1351, 'city', 744, 'City created. <br/><br/>City Name: Dingle<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1352, 'city', 745, 'City created. <br/><br/>City Name: Dueñas<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1353, 'city', 746, 'City created. <br/><br/>City Name: Dumangas<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1354, 'city', 747, 'City created. <br/><br/>City Name: Estancia<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1355, 'city', 748, 'City created. <br/><br/>City Name: Guimbal<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1356, 'city', 749, 'City created. <br/><br/>City Name: Igbaras<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1357, 'city', 750, 'City created. <br/><br/>City Name: City of Iloilo<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1358, 'city', 751, 'City created. <br/><br/>City Name: Janiuay<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1359, 'city', 752, 'City created. <br/><br/>City Name: Lambunao<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1360, 'city', 753, 'City created. <br/><br/>City Name: Leganes<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1361, 'city', 754, 'City created. <br/><br/>City Name: Lemery<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1362, 'city', 755, 'City created. <br/><br/>City Name: Leon<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1363, 'city', 756, 'City created. <br/><br/>City Name: Maasin<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1364, 'city', 757, 'City created. <br/><br/>City Name: Miagao<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1365, 'city', 758, 'City created. <br/><br/>City Name: Mina<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1366, 'city', 759, 'City created. <br/><br/>City Name: New Lucena<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1367, 'city', 760, 'City created. <br/><br/>City Name: Oton<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1368, 'city', 761, 'City created. <br/><br/>City Name: City of Passi<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1369, 'city', 762, 'City created. <br/><br/>City Name: Pavia<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1370, 'city', 763, 'City created. <br/><br/>City Name: Pototan<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1371, 'city', 764, 'City created. <br/><br/>City Name: San Dionisio<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1372, 'city', 765, 'City created. <br/><br/>City Name: San Enrique<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1373, 'city', 766, 'City created. <br/><br/>City Name: San Joaquin<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1374, 'city', 767, 'City created. <br/><br/>City Name: San Miguel<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1375, 'city', 768, 'City created. <br/><br/>City Name: San Rafael<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1376, 'city', 769, 'City created. <br/><br/>City Name: Santa Barbara<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1377, 'city', 770, 'City created. <br/><br/>City Name: Sara<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1378, 'city', 771, 'City created. <br/><br/>City Name: Tigbauan<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1379, 'city', 772, 'City created. <br/><br/>City Name: Tubungan<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1380, 'city', 773, 'City created. <br/><br/>City Name: Zarraga<br/>State ID: 37', '0', '2023-09-11 16:57:43'),
(1381, 'city', 774, 'City created. <br/><br/>City Name: City of Bacolod<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1382, 'city', 775, 'City created. <br/><br/>City Name: City of Bago<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1383, 'city', 776, 'City created. <br/><br/>City Name: Binalbagan<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1384, 'city', 777, 'City created. <br/><br/>City Name: City of Cadiz<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1385, 'city', 778, 'City created. <br/><br/>City Name: Calatrava<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1386, 'city', 779, 'City created. <br/><br/>City Name: Candoni<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1387, 'city', 780, 'City created. <br/><br/>City Name: Cauayan<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1388, 'city', 781, 'City created. <br/><br/>City Name: Enrique B. Magalona<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1389, 'city', 782, 'City created. <br/><br/>City Name: City of Escalante<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1390, 'city', 783, 'City created. <br/><br/>City Name: City of Himamaylan<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1391, 'city', 784, 'City created. <br/><br/>City Name: Hinigaran<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1392, 'city', 785, 'City created. <br/><br/>City Name: Hinoba-an<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1393, 'city', 786, 'City created. <br/><br/>City Name: Ilog<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1394, 'city', 787, 'City created. <br/><br/>City Name: Isabela<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1395, 'city', 788, 'City created. <br/><br/>City Name: City of Kabankalan<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1396, 'city', 789, 'City created. <br/><br/>City Name: City of La Carlota<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1397, 'city', 790, 'City created. <br/><br/>City Name: La Castellana<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1398, 'city', 791, 'City created. <br/><br/>City Name: Manapla<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1399, 'city', 792, 'City created. <br/><br/>City Name: Moises Padilla<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1400, 'city', 793, 'City created. <br/><br/>City Name: Murcia<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1401, 'city', 794, 'City created. <br/><br/>City Name: Pontevedra<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1402, 'city', 795, 'City created. <br/><br/>City Name: Pulupandan<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1403, 'city', 796, 'City created. <br/><br/>City Name: City of Sagay<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1404, 'city', 797, 'City created. <br/><br/>City Name: City of San Carlos<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1405, 'city', 798, 'City created. <br/><br/>City Name: San Enrique<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1406, 'city', 799, 'City created. <br/><br/>City Name: City of Silay<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1407, 'city', 800, 'City created. <br/><br/>City Name: City of Sipalay<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1408, 'city', 801, 'City created. <br/><br/>City Name: City of Talisay<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1409, 'city', 802, 'City created. <br/><br/>City Name: Toboso<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1410, 'city', 803, 'City created. <br/><br/>City Name: Valladolid<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1411, 'city', 804, 'City created. <br/><br/>City Name: City of Victorias<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1412, 'city', 805, 'City created. <br/><br/>City Name: Salvador Benedicto<br/>State ID: 38', '0', '2023-09-11 16:57:43'),
(1413, 'city', 806, 'City created. <br/><br/>City Name: Buenavista<br/>State ID: 39', '0', '2023-09-11 16:57:43'),
(1414, 'city', 807, 'City created. <br/><br/>City Name: Jordan<br/>State ID: 39', '0', '2023-09-11 16:57:43'),
(1415, 'city', 808, 'City created. <br/><br/>City Name: Nueva Valencia<br/>State ID: 39', '0', '2023-09-11 16:57:43'),
(1416, 'city', 809, 'City created. <br/><br/>City Name: San Lorenzo<br/>State ID: 39', '0', '2023-09-11 16:57:43'),
(1417, 'city', 810, 'City created. <br/><br/>City Name: Sibunag<br/>State ID: 39', '0', '2023-09-11 16:57:43'),
(1418, 'city', 811, 'City created. <br/><br/>City Name: Alburquerque<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1419, 'city', 812, 'City created. <br/><br/>City Name: Alicia<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1420, 'city', 813, 'City created. <br/><br/>City Name: Anda<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1421, 'city', 814, 'City created. <br/><br/>City Name: Antequera<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1422, 'city', 815, 'City created. <br/><br/>City Name: Baclayon<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1423, 'city', 816, 'City created. <br/><br/>City Name: Balilihan<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1424, 'city', 817, 'City created. <br/><br/>City Name: Batuan<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1425, 'city', 818, 'City created. <br/><br/>City Name: Bilar<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1426, 'city', 819, 'City created. <br/><br/>City Name: Buenavista<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1427, 'city', 820, 'City created. <br/><br/>City Name: Calape<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1428, 'city', 821, 'City created. <br/><br/>City Name: Candijay<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1429, 'city', 822, 'City created. <br/><br/>City Name: Carmen<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1430, 'city', 823, 'City created. <br/><br/>City Name: Catigbian<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1431, 'city', 824, 'City created. <br/><br/>City Name: Clarin<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1432, 'city', 825, 'City created. <br/><br/>City Name: Corella<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1433, 'city', 826, 'City created. <br/><br/>City Name: Cortes<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1434, 'city', 827, 'City created. <br/><br/>City Name: Dagohoy<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1435, 'city', 828, 'City created. <br/><br/>City Name: Danao<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1436, 'city', 829, 'City created. <br/><br/>City Name: Dauis<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1437, 'city', 830, 'City created. <br/><br/>City Name: Dimiao<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1438, 'city', 831, 'City created. <br/><br/>City Name: Duero<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1439, 'city', 832, 'City created. <br/><br/>City Name: Garcia Hernandez<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1440, 'city', 833, 'City created. <br/><br/>City Name: Guindulman<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1441, 'city', 834, 'City created. <br/><br/>City Name: Inabanga<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1442, 'city', 835, 'City created. <br/><br/>City Name: Jagna<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1443, 'city', 836, 'City created. <br/><br/>City Name: Getafe<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1444, 'city', 837, 'City created. <br/><br/>City Name: Lila<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1445, 'city', 838, 'City created. <br/><br/>City Name: Loay<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1446, 'city', 839, 'City created. <br/><br/>City Name: Loboc<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1447, 'city', 840, 'City created. <br/><br/>City Name: Loon<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1448, 'city', 841, 'City created. <br/><br/>City Name: Mabini<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1449, 'city', 842, 'City created. <br/><br/>City Name: Maribojoc<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1450, 'city', 843, 'City created. <br/><br/>City Name: Panglao<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1451, 'city', 844, 'City created. <br/><br/>City Name: Pilar<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1452, 'city', 845, 'City created. <br/><br/>City Name: Pres. Carlos P. Garcia<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1453, 'city', 846, 'City created. <br/><br/>City Name: Sagbayan<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1454, 'city', 847, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1455, 'city', 848, 'City created. <br/><br/>City Name: San Miguel<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1456, 'city', 849, 'City created. <br/><br/>City Name: Sevilla<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1457, 'city', 850, 'City created. <br/><br/>City Name: Sierra Bullones<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1458, 'city', 851, 'City created. <br/><br/>City Name: Sikatuna<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1459, 'city', 852, 'City created. <br/><br/>City Name: City of Tagbilaran<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1460, 'city', 853, 'City created. <br/><br/>City Name: Talibon<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1461, 'city', 854, 'City created. <br/><br/>City Name: Trinidad<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1462, 'city', 855, 'City created. <br/><br/>City Name: Tubigon<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1463, 'city', 856, 'City created. <br/><br/>City Name: Ubay<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1464, 'city', 857, 'City created. <br/><br/>City Name: Valencia<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1465, 'city', 858, 'City created. <br/><br/>City Name: Bien Unido<br/>State ID: 40', '0', '2023-09-11 16:57:43'),
(1466, 'city', 859, 'City created. <br/><br/>City Name: Alcantara<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1467, 'city', 860, 'City created. <br/><br/>City Name: Alcoy<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1468, 'city', 861, 'City created. <br/><br/>City Name: Alegria<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1469, 'city', 862, 'City created. <br/><br/>City Name: Aloguinsan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1470, 'city', 863, 'City created. <br/><br/>City Name: Argao<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1471, 'city', 864, 'City created. <br/><br/>City Name: Asturias<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1472, 'city', 865, 'City created. <br/><br/>City Name: Badian<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1473, 'city', 866, 'City created. <br/><br/>City Name: Balamban<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1474, 'city', 867, 'City created. <br/><br/>City Name: Bantayan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1475, 'city', 868, 'City created. <br/><br/>City Name: Barili<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1476, 'city', 869, 'City created. <br/><br/>City Name: City of Bogo<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1477, 'city', 870, 'City created. <br/><br/>City Name: Boljoon<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1478, 'city', 871, 'City created. <br/><br/>City Name: Borbon<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1479, 'city', 872, 'City created. <br/><br/>City Name: City of Carcar<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1480, 'city', 873, 'City created. <br/><br/>City Name: Carmen<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1481, 'city', 874, 'City created. <br/><br/>City Name: Catmon<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1482, 'city', 875, 'City created. <br/><br/>City Name: City of Cebu<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1483, 'city', 876, 'City created. <br/><br/>City Name: Compostela<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1484, 'city', 877, 'City created. <br/><br/>City Name: Consolacion<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1485, 'city', 878, 'City created. <br/><br/>City Name: Cordova<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1486, 'city', 879, 'City created. <br/><br/>City Name: Daanbantayan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1487, 'city', 880, 'City created. <br/><br/>City Name: Dalaguete<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1488, 'city', 881, 'City created. <br/><br/>City Name: Danao City<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1489, 'city', 882, 'City created. <br/><br/>City Name: Dumanjug<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1490, 'city', 883, 'City created. <br/><br/>City Name: Ginatilan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1491, 'city', 884, 'City created. <br/><br/>City Name: City of Lapu-Lapu<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1492, 'city', 885, 'City created. <br/><br/>City Name: Liloan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1493, 'city', 886, 'City created. <br/><br/>City Name: Madridejos<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1494, 'city', 887, 'City created. <br/><br/>City Name: Malabuyoc<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1495, 'city', 888, 'City created. <br/><br/>City Name: City of Mandaue<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1496, 'city', 889, 'City created. <br/><br/>City Name: Medellin<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1497, 'city', 890, 'City created. <br/><br/>City Name: Minglanilla<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1498, 'city', 891, 'City created. <br/><br/>City Name: Moalboal<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1499, 'city', 892, 'City created. <br/><br/>City Name: City of Naga<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1500, 'city', 893, 'City created. <br/><br/>City Name: Oslob<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1501, 'city', 894, 'City created. <br/><br/>City Name: Pilar<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1502, 'city', 895, 'City created. <br/><br/>City Name: Pinamungajan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1503, 'city', 896, 'City created. <br/><br/>City Name: Poro<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1504, 'city', 897, 'City created. <br/><br/>City Name: Ronda<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1505, 'city', 898, 'City created. <br/><br/>City Name: Samboan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1506, 'city', 899, 'City created. <br/><br/>City Name: San Fernando<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1507, 'city', 900, 'City created. <br/><br/>City Name: San Francisco<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1508, 'city', 901, 'City created. <br/><br/>City Name: San Remigio<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1509, 'city', 902, 'City created. <br/><br/>City Name: Santa Fe<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1510, 'city', 903, 'City created. <br/><br/>City Name: Santander<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1511, 'city', 904, 'City created. <br/><br/>City Name: Sibonga<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1512, 'city', 905, 'City created. <br/><br/>City Name: Sogod<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1513, 'city', 906, 'City created. <br/><br/>City Name: Tabogon<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1514, 'city', 907, 'City created. <br/><br/>City Name: Tabuelan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1515, 'city', 908, 'City created. <br/><br/>City Name: City of Talisay<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1516, 'city', 909, 'City created. <br/><br/>City Name: City of Toledo<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1517, 'city', 910, 'City created. <br/><br/>City Name: Tuburan<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1518, 'city', 911, 'City created. <br/><br/>City Name: Tudela<br/>State ID: 41', '0', '2023-09-11 16:57:43'),
(1519, 'city', 912, 'City created. <br/><br/>City Name: Amlan<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1520, 'city', 913, 'City created. <br/><br/>City Name: Ayungon<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1521, 'city', 914, 'City created. <br/><br/>City Name: Bacong<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1522, 'city', 915, 'City created. <br/><br/>City Name: City of Bais<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1523, 'city', 916, 'City created. <br/><br/>City Name: Basay<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1524, 'city', 917, 'City created. <br/><br/>City Name: City of Bayawan<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1525, 'city', 918, 'City created. <br/><br/>City Name: Bindoy<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1526, 'city', 919, 'City created. <br/><br/>City Name: City of Canlaon<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1527, 'city', 920, 'City created. <br/><br/>City Name: Dauin<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1528, 'city', 921, 'City created. <br/><br/>City Name: City of Dumaguete<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1529, 'city', 922, 'City created. <br/><br/>City Name: City of Guihulngan<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1530, 'city', 923, 'City created. <br/><br/>City Name: Jimalalud<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1531, 'city', 924, 'City created. <br/><br/>City Name: La Libertad<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1532, 'city', 925, 'City created. <br/><br/>City Name: Mabinay<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1533, 'city', 926, 'City created. <br/><br/>City Name: Manjuyod<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1534, 'city', 927, 'City created. <br/><br/>City Name: Pamplona<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1535, 'city', 928, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1536, 'city', 929, 'City created. <br/><br/>City Name: Santa Catalina<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1537, 'city', 930, 'City created. <br/><br/>City Name: Siaton<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1538, 'city', 931, 'City created. <br/><br/>City Name: Sibulan<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1539, 'city', 932, 'City created. <br/><br/>City Name: City of Tanjay<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1540, 'city', 933, 'City created. <br/><br/>City Name: Tayasan<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1541, 'city', 934, 'City created. <br/><br/>City Name: Valencia<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1542, 'city', 935, 'City created. <br/><br/>City Name: Vallehermoso<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1543, 'city', 936, 'City created. <br/><br/>City Name: Zamboanguita<br/>State ID: 42', '0', '2023-09-11 16:57:43'),
(1544, 'city', 937, 'City created. <br/><br/>City Name: Enrique Villanueva<br/>State ID: 43', '0', '2023-09-11 16:57:43'),
(1545, 'city', 938, 'City created. <br/><br/>City Name: Larena<br/>State ID: 43', '0', '2023-09-11 16:57:43'),
(1546, 'city', 939, 'City created. <br/><br/>City Name: Lazi<br/>State ID: 43', '0', '2023-09-11 16:57:43'),
(1547, 'city', 940, 'City created. <br/><br/>City Name: Maria<br/>State ID: 43', '0', '2023-09-11 16:57:43'),
(1548, 'city', 941, 'City created. <br/><br/>City Name: San Juan<br/>State ID: 43', '0', '2023-09-11 16:57:43'),
(1549, 'city', 942, 'City created. <br/><br/>City Name: Siquijor<br/>State ID: 43', '0', '2023-09-11 16:57:43'),
(1550, 'city', 943, 'City created. <br/><br/>City Name: Arteche<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1551, 'city', 944, 'City created. <br/><br/>City Name: Balangiga<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1552, 'city', 945, 'City created. <br/><br/>City Name: Balangkayan<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1553, 'city', 946, 'City created. <br/><br/>City Name: City of Borongan<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1554, 'city', 947, 'City created. <br/><br/>City Name: Can-Avid<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1555, 'city', 948, 'City created. <br/><br/>City Name: Dolores<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1556, 'city', 949, 'City created. <br/><br/>City Name: General Macarthur<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1557, 'city', 950, 'City created. <br/><br/>City Name: Giporlos<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1558, 'city', 951, 'City created. <br/><br/>City Name: Guiuan<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1559, 'city', 952, 'City created. <br/><br/>City Name: Hernani<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1560, 'city', 953, 'City created. <br/><br/>City Name: Jipapad<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1561, 'city', 954, 'City created. <br/><br/>City Name: Lawaan<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1562, 'city', 955, 'City created. <br/><br/>City Name: Llorente<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1563, 'city', 956, 'City created. <br/><br/>City Name: Maslog<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1564, 'city', 957, 'City created. <br/><br/>City Name: Maydolong<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1565, 'city', 958, 'City created. <br/><br/>City Name: Mercedes<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1566, 'city', 959, 'City created. <br/><br/>City Name: Oras<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1567, 'city', 960, 'City created. <br/><br/>City Name: Quinapondan<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1568, 'city', 961, 'City created. <br/><br/>City Name: Salcedo<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1569, 'city', 962, 'City created. <br/><br/>City Name: San Julian<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1570, 'city', 963, 'City created. <br/><br/>City Name: San Policarpo<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1571, 'city', 964, 'City created. <br/><br/>City Name: Sulat<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1572, 'city', 965, 'City created. <br/><br/>City Name: Taft<br/>State ID: 44', '0', '2023-09-11 16:57:43'),
(1573, 'city', 966, 'City created. <br/><br/>City Name: Abuyog<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1574, 'city', 967, 'City created. <br/><br/>City Name: Alangalang<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1575, 'city', 968, 'City created. <br/><br/>City Name: Albuera<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1576, 'city', 969, 'City created. <br/><br/>City Name: Babatngon<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1577, 'city', 970, 'City created. <br/><br/>City Name: Barugo<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1578, 'city', 971, 'City created. <br/><br/>City Name: Bato<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1579, 'city', 972, 'City created. <br/><br/>City Name: City of Baybay<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1580, 'city', 973, 'City created. <br/><br/>City Name: Burauen<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1581, 'city', 974, 'City created. <br/><br/>City Name: Calubian<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1582, 'city', 975, 'City created. <br/><br/>City Name: Capoocan<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1583, 'city', 976, 'City created. <br/><br/>City Name: Carigara<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1584, 'city', 977, 'City created. <br/><br/>City Name: Dagami<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1585, 'city', 978, 'City created. <br/><br/>City Name: Dulag<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1586, 'city', 979, 'City created. <br/><br/>City Name: Hilongos<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1587, 'city', 980, 'City created. <br/><br/>City Name: Hindang<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1588, 'city', 981, 'City created. <br/><br/>City Name: Inopacan<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1589, 'city', 982, 'City created. <br/><br/>City Name: Isabel<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1590, 'city', 983, 'City created. <br/><br/>City Name: Jaro<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1591, 'city', 984, 'City created. <br/><br/>City Name: Javier<br/>State ID: 45', '0', '2023-09-11 16:57:43');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(1592, 'city', 985, 'City created. <br/><br/>City Name: Julita<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1593, 'city', 986, 'City created. <br/><br/>City Name: Kananga<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1594, 'city', 987, 'City created. <br/><br/>City Name: La Paz<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1595, 'city', 988, 'City created. <br/><br/>City Name: Leyte<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1596, 'city', 989, 'City created. <br/><br/>City Name: Macarthur<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1597, 'city', 990, 'City created. <br/><br/>City Name: Mahaplag<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1598, 'city', 991, 'City created. <br/><br/>City Name: Matag-Ob<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1599, 'city', 992, 'City created. <br/><br/>City Name: Matalom<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1600, 'city', 993, 'City created. <br/><br/>City Name: Mayorga<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1601, 'city', 994, 'City created. <br/><br/>City Name: Merida<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1602, 'city', 995, 'City created. <br/><br/>City Name: Ormoc City<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1603, 'city', 996, 'City created. <br/><br/>City Name: Palo<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1604, 'city', 997, 'City created. <br/><br/>City Name: Palompon<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1605, 'city', 998, 'City created. <br/><br/>City Name: Pastrana<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1606, 'city', 999, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1607, 'city', 1000, 'City created. <br/><br/>City Name: San Miguel<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1608, 'city', 1001, 'City created. <br/><br/>City Name: Santa Fe<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1609, 'city', 1002, 'City created. <br/><br/>City Name: Tabango<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1610, 'city', 1003, 'City created. <br/><br/>City Name: Tabontabon<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1611, 'city', 1004, 'City created. <br/><br/>City Name: City of Tacloban<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1612, 'city', 1005, 'City created. <br/><br/>City Name: Tanauan<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1613, 'city', 1006, 'City created. <br/><br/>City Name: Tolosa<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1614, 'city', 1007, 'City created. <br/><br/>City Name: Tunga<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1615, 'city', 1008, 'City created. <br/><br/>City Name: Villaba<br/>State ID: 45', '0', '2023-09-11 16:57:43'),
(1616, 'city', 1009, 'City created. <br/><br/>City Name: Allen<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1617, 'city', 1010, 'City created. <br/><br/>City Name: Biri<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1618, 'city', 1011, 'City created. <br/><br/>City Name: Bobon<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1619, 'city', 1012, 'City created. <br/><br/>City Name: Capul<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1620, 'city', 1013, 'City created. <br/><br/>City Name: Catarman<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1621, 'city', 1014, 'City created. <br/><br/>City Name: Catubig<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1622, 'city', 1015, 'City created. <br/><br/>City Name: Gamay<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1623, 'city', 1016, 'City created. <br/><br/>City Name: Laoang<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1624, 'city', 1017, 'City created. <br/><br/>City Name: Lapinig<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1625, 'city', 1018, 'City created. <br/><br/>City Name: Las Navas<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1626, 'city', 1019, 'City created. <br/><br/>City Name: Lavezares<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1627, 'city', 1020, 'City created. <br/><br/>City Name: Mapanas<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1628, 'city', 1021, 'City created. <br/><br/>City Name: Mondragon<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1629, 'city', 1022, 'City created. <br/><br/>City Name: Palapag<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1630, 'city', 1023, 'City created. <br/><br/>City Name: Pambujan<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1631, 'city', 1024, 'City created. <br/><br/>City Name: Rosario<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1632, 'city', 1025, 'City created. <br/><br/>City Name: San Antonio<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1633, 'city', 1026, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1634, 'city', 1027, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1635, 'city', 1028, 'City created. <br/><br/>City Name: San Roque<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1636, 'city', 1029, 'City created. <br/><br/>City Name: San Vicente<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1637, 'city', 1030, 'City created. <br/><br/>City Name: Silvino Lobos<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1638, 'city', 1031, 'City created. <br/><br/>City Name: Victoria<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1639, 'city', 1032, 'City created. <br/><br/>City Name: Lope De Vega<br/>State ID: 46', '0', '2023-09-11 16:57:43'),
(1640, 'city', 1033, 'City created. <br/><br/>City Name: Almagro<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1641, 'city', 1034, 'City created. <br/><br/>City Name: Basey<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1642, 'city', 1035, 'City created. <br/><br/>City Name: City of Calbayog<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1643, 'city', 1036, 'City created. <br/><br/>City Name: Calbiga<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1644, 'city', 1037, 'City created. <br/><br/>City Name: City of Catbalogan<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1645, 'city', 1038, 'City created. <br/><br/>City Name: Daram<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1646, 'city', 1039, 'City created. <br/><br/>City Name: Gandara<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1647, 'city', 1040, 'City created. <br/><br/>City Name: Hinabangan<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1648, 'city', 1041, 'City created. <br/><br/>City Name: Jiabong<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1649, 'city', 1042, 'City created. <br/><br/>City Name: Marabut<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1650, 'city', 1043, 'City created. <br/><br/>City Name: Matuguinao<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1651, 'city', 1044, 'City created. <br/><br/>City Name: Motiong<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1652, 'city', 1045, 'City created. <br/><br/>City Name: Pinabacdao<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1653, 'city', 1046, 'City created. <br/><br/>City Name: San Jose De Buan<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1654, 'city', 1047, 'City created. <br/><br/>City Name: San Sebastian<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1655, 'city', 1048, 'City created. <br/><br/>City Name: Santa Margarita<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1656, 'city', 1049, 'City created. <br/><br/>City Name: Santa Rita<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1657, 'city', 1050, 'City created. <br/><br/>City Name: Santo Niño<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1658, 'city', 1051, 'City created. <br/><br/>City Name: Talalora<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1659, 'city', 1052, 'City created. <br/><br/>City Name: Tarangnan<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1660, 'city', 1053, 'City created. <br/><br/>City Name: Villareal<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1661, 'city', 1054, 'City created. <br/><br/>City Name: Paranas<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1662, 'city', 1055, 'City created. <br/><br/>City Name: Zumarraga<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1663, 'city', 1056, 'City created. <br/><br/>City Name: Tagapul-An<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1664, 'city', 1057, 'City created. <br/><br/>City Name: San Jorge<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1665, 'city', 1058, 'City created. <br/><br/>City Name: Pagsanghan<br/>State ID: 47', '0', '2023-09-11 16:57:43'),
(1666, 'city', 1059, 'City created. <br/><br/>City Name: Anahawan<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1667, 'city', 1060, 'City created. <br/><br/>City Name: Bontoc<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1668, 'city', 1061, 'City created. <br/><br/>City Name: Hinunangan<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1669, 'city', 1062, 'City created. <br/><br/>City Name: Hinundayan<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1670, 'city', 1063, 'City created. <br/><br/>City Name: Libagon<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1671, 'city', 1064, 'City created. <br/><br/>City Name: Liloan<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1672, 'city', 1065, 'City created. <br/><br/>City Name: City of Maasin<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1673, 'city', 1066, 'City created. <br/><br/>City Name: Macrohon<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1674, 'city', 1067, 'City created. <br/><br/>City Name: Malitbog<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1675, 'city', 1068, 'City created. <br/><br/>City Name: Padre Burgos<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1676, 'city', 1069, 'City created. <br/><br/>City Name: Pintuyan<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1677, 'city', 1070, 'City created. <br/><br/>City Name: Saint Bernard<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1678, 'city', 1071, 'City created. <br/><br/>City Name: San Francisco<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1679, 'city', 1072, 'City created. <br/><br/>City Name: San Juan<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1680, 'city', 1073, 'City created. <br/><br/>City Name: San Ricardo<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1681, 'city', 1074, 'City created. <br/><br/>City Name: Silago<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1682, 'city', 1075, 'City created. <br/><br/>City Name: Sogod<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1683, 'city', 1076, 'City created. <br/><br/>City Name: Tomas Oppus<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1684, 'city', 1077, 'City created. <br/><br/>City Name: Limasawa<br/>State ID: 48', '0', '2023-09-11 16:57:43'),
(1685, 'city', 1078, 'City created. <br/><br/>City Name: Almeria<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1686, 'city', 1079, 'City created. <br/><br/>City Name: Biliran<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1687, 'city', 1080, 'City created. <br/><br/>City Name: Cabucgayan<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1688, 'city', 1081, 'City created. <br/><br/>City Name: Caibiran<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1689, 'city', 1082, 'City created. <br/><br/>City Name: Culaba<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1690, 'city', 1083, 'City created. <br/><br/>City Name: Kawayan<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1691, 'city', 1084, 'City created. <br/><br/>City Name: Maripipi<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1692, 'city', 1085, 'City created. <br/><br/>City Name: Naval<br/>State ID: 49', '0', '2023-09-11 16:57:43'),
(1693, 'city', 1086, 'City created. <br/><br/>City Name: City of Dapitan<br/>State ID: 50', '0', '2023-09-11 16:57:43'),
(1694, 'city', 1087, 'City created. <br/><br/>City Name: City of Dipolog<br/>State ID: 50', '0', '2023-09-11 16:57:43'),
(1695, 'city', 1088, 'City created. <br/><br/>City Name: Katipunan<br/>State ID: 50', '0', '2023-09-11 16:57:43'),
(1696, 'city', 1089, 'City created. <br/><br/>City Name: La Libertad<br/>State ID: 50', '0', '2023-09-11 16:57:43'),
(1697, 'city', 1090, 'City created. <br/><br/>City Name: Labason<br/>State ID: 50', '0', '2023-09-11 16:57:43'),
(1698, 'city', 1091, 'City created. <br/><br/>City Name: Liloy<br/>State ID: 50', '0', '2023-09-11 16:57:43'),
(1699, 'city', 1092, 'City created. <br/><br/>City Name: Manukan<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1700, 'city', 1093, 'City created. <br/><br/>City Name: Mutia<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1701, 'city', 1094, 'City created. <br/><br/>City Name: Piñan<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1702, 'city', 1095, 'City created. <br/><br/>City Name: Polanco<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1703, 'city', 1096, 'City created. <br/><br/>City Name: Pres. Manuel A. Roxas<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1704, 'city', 1097, 'City created. <br/><br/>City Name: Rizal<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1705, 'city', 1098, 'City created. <br/><br/>City Name: Salug<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1706, 'city', 1099, 'City created. <br/><br/>City Name: Sergio Osmeña Sr.<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1707, 'city', 1100, 'City created. <br/><br/>City Name: Siayan<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1708, 'city', 1101, 'City created. <br/><br/>City Name: Sibuco<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1709, 'city', 1102, 'City created. <br/><br/>City Name: Sibutad<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1710, 'city', 1103, 'City created. <br/><br/>City Name: Sindangan<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1711, 'city', 1104, 'City created. <br/><br/>City Name: Siocon<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1712, 'city', 1105, 'City created. <br/><br/>City Name: Sirawai<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1713, 'city', 1106, 'City created. <br/><br/>City Name: Tampilisan<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1714, 'city', 1107, 'City created. <br/><br/>City Name: Jose Dalman<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1715, 'city', 1108, 'City created. <br/><br/>City Name: Gutalac<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1716, 'city', 1109, 'City created. <br/><br/>City Name: Baliguian<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1717, 'city', 1110, 'City created. <br/><br/>City Name: Godod<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1718, 'city', 1111, 'City created. <br/><br/>City Name: Bacungan<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1719, 'city', 1112, 'City created. <br/><br/>City Name: Kalawit<br/>State ID: 50', '0', '2023-09-11 16:57:44'),
(1720, 'city', 1113, 'City created. <br/><br/>City Name: Aurora<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1721, 'city', 1114, 'City created. <br/><br/>City Name: Bayog<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1722, 'city', 1115, 'City created. <br/><br/>City Name: Dimataling<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1723, 'city', 1116, 'City created. <br/><br/>City Name: Dinas<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1724, 'city', 1117, 'City created. <br/><br/>City Name: Dumalinao<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1725, 'city', 1118, 'City created. <br/><br/>City Name: Dumingag<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1726, 'city', 1119, 'City created. <br/><br/>City Name: Kumalarang<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1727, 'city', 1120, 'City created. <br/><br/>City Name: Labangan<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1728, 'city', 1121, 'City created. <br/><br/>City Name: Lapuyan<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1729, 'city', 1122, 'City created. <br/><br/>City Name: Mahayag<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1730, 'city', 1123, 'City created. <br/><br/>City Name: Margosatubig<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1731, 'city', 1124, 'City created. <br/><br/>City Name: Midsalip<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1732, 'city', 1125, 'City created. <br/><br/>City Name: Molave<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1733, 'city', 1126, 'City created. <br/><br/>City Name: City of Pagadian<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1734, 'city', 1127, 'City created. <br/><br/>City Name: Ramon Magsaysay<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1735, 'city', 1128, 'City created. <br/><br/>City Name: San Miguel<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1736, 'city', 1129, 'City created. <br/><br/>City Name: San Pablo<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1737, 'city', 1130, 'City created. <br/><br/>City Name: Tabina<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1738, 'city', 1131, 'City created. <br/><br/>City Name: Tambulig<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1739, 'city', 1132, 'City created. <br/><br/>City Name: Tukuran<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1740, 'city', 1133, 'City created. <br/><br/>City Name: City of Zamboanga<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1741, 'city', 1134, 'City created. <br/><br/>City Name: Lakewood<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1742, 'city', 1135, 'City created. <br/><br/>City Name: Josefina<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1743, 'city', 1136, 'City created. <br/><br/>City Name: Pitogo<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1744, 'city', 1137, 'City created. <br/><br/>City Name: Sominot<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1745, 'city', 1138, 'City created. <br/><br/>City Name: Vincenzo A. Sagun<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1746, 'city', 1139, 'City created. <br/><br/>City Name: Guipos<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1747, 'city', 1140, 'City created. <br/><br/>City Name: Tigbao<br/>State ID: 51', '0', '2023-09-11 16:57:44'),
(1748, 'city', 1141, 'City created. <br/><br/>City Name: Alicia<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1749, 'city', 1142, 'City created. <br/><br/>City Name: Buug<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1750, 'city', 1143, 'City created. <br/><br/>City Name: Diplahan<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1751, 'city', 1144, 'City created. <br/><br/>City Name: Imelda<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1752, 'city', 1145, 'City created. <br/><br/>City Name: Ipil<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1753, 'city', 1146, 'City created. <br/><br/>City Name: Kabasalan<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1754, 'city', 1147, 'City created. <br/><br/>City Name: Mabuhay<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1755, 'city', 1148, 'City created. <br/><br/>City Name: Malangas<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1756, 'city', 1149, 'City created. <br/><br/>City Name: Naga<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1757, 'city', 1150, 'City created. <br/><br/>City Name: Olutanga<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1758, 'city', 1151, 'City created. <br/><br/>City Name: Payao<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1759, 'city', 1152, 'City created. <br/><br/>City Name: Roseller Lim<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1760, 'city', 1153, 'City created. <br/><br/>City Name: Siay<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1761, 'city', 1154, 'City created. <br/><br/>City Name: Talusan<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1762, 'city', 1155, 'City created. <br/><br/>City Name: Titay<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1763, 'city', 1156, 'City created. <br/><br/>City Name: Tungawan<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1764, 'city', 1157, 'City created. <br/><br/>City Name: City of Isabela<br/>State ID: 52', '0', '2023-09-11 16:57:44'),
(1765, 'city', 1158, 'City created. <br/><br/>City Name: Baungon<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1766, 'city', 1159, 'City created. <br/><br/>City Name: Damulog<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1767, 'city', 1160, 'City created. <br/><br/>City Name: Dangcagan<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1768, 'city', 1161, 'City created. <br/><br/>City Name: Don Carlos<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1769, 'city', 1162, 'City created. <br/><br/>City Name: Impasug-ong<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1770, 'city', 1163, 'City created. <br/><br/>City Name: Kadingilan<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1771, 'city', 1164, 'City created. <br/><br/>City Name: Kalilangan<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1772, 'city', 1165, 'City created. <br/><br/>City Name: Kibawe<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1773, 'city', 1166, 'City created. <br/><br/>City Name: Kitaotao<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1774, 'city', 1167, 'City created. <br/><br/>City Name: Lantapan<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1775, 'city', 1168, 'City created. <br/><br/>City Name: Libona<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1776, 'city', 1169, 'City created. <br/><br/>City Name: City of Malaybalay<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1777, 'city', 1170, 'City created. <br/><br/>City Name: Malitbog<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1778, 'city', 1171, 'City created. <br/><br/>City Name: Manolo Fortich<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1779, 'city', 1172, 'City created. <br/><br/>City Name: Maramag<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1780, 'city', 1173, 'City created. <br/><br/>City Name: Pangantucan<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1781, 'city', 1174, 'City created. <br/><br/>City Name: Quezon<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1782, 'city', 1175, 'City created. <br/><br/>City Name: San Fernando<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1783, 'city', 1176, 'City created. <br/><br/>City Name: Sumilao<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1784, 'city', 1177, 'City created. <br/><br/>City Name: Talakag<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1785, 'city', 1178, 'City created. <br/><br/>City Name: City of Valencia<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1786, 'city', 1179, 'City created. <br/><br/>City Name: Cabanglasan<br/>State ID: 53', '0', '2023-09-11 16:57:44'),
(1787, 'city', 1180, 'City created. <br/><br/>City Name: Catarman<br/>State ID: 54', '0', '2023-09-11 16:57:44'),
(1788, 'city', 1181, 'City created. <br/><br/>City Name: Guinsiliban<br/>State ID: 54', '0', '2023-09-11 16:57:44'),
(1789, 'city', 1182, 'City created. <br/><br/>City Name: Mahinog<br/>State ID: 54', '0', '2023-09-11 16:57:44'),
(1790, 'city', 1183, 'City created. <br/><br/>City Name: Mambajao<br/>State ID: 54', '0', '2023-09-11 16:57:44'),
(1791, 'city', 1184, 'City created. <br/><br/>City Name: Sagay<br/>State ID: 54', '0', '2023-09-11 16:57:44'),
(1792, 'city', 1185, 'City created. <br/><br/>City Name: Bacolod<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1793, 'city', 1186, 'City created. <br/><br/>City Name: Baloi<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1794, 'city', 1187, 'City created. <br/><br/>City Name: Baroy<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1795, 'city', 1188, 'City created. <br/><br/>City Name: City of Iligan<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1796, 'city', 1189, 'City created. <br/><br/>City Name: Kapatagan<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1797, 'city', 1190, 'City created. <br/><br/>City Name: Sultan Naga Dimaporo<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1798, 'city', 1191, 'City created. <br/><br/>City Name: Kauswagan<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1799, 'city', 1192, 'City created. <br/><br/>City Name: Kolambugan<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1800, 'city', 1193, 'City created. <br/><br/>City Name: Lala<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1801, 'city', 1194, 'City created. <br/><br/>City Name: Linamon<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1802, 'city', 1195, 'City created. <br/><br/>City Name: Magsaysay<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1803, 'city', 1196, 'City created. <br/><br/>City Name: Maigo<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1804, 'city', 1197, 'City created. <br/><br/>City Name: Matungao<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1805, 'city', 1198, 'City created. <br/><br/>City Name: Munai<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1806, 'city', 1199, 'City created. <br/><br/>City Name: Nunungan<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1807, 'city', 1200, 'City created. <br/><br/>City Name: Pantao Ragat<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1808, 'city', 1201, 'City created. <br/><br/>City Name: Poona Piagapo<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1809, 'city', 1202, 'City created. <br/><br/>City Name: Salvador<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1810, 'city', 1203, 'City created. <br/><br/>City Name: Sapad<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1811, 'city', 1204, 'City created. <br/><br/>City Name: Tagoloan<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1812, 'city', 1205, 'City created. <br/><br/>City Name: Tangcal<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1813, 'city', 1206, 'City created. <br/><br/>City Name: Tubod<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1814, 'city', 1207, 'City created. <br/><br/>City Name: Pantar<br/>State ID: 55', '0', '2023-09-11 16:57:44'),
(1815, 'city', 1208, 'City created. <br/><br/>City Name: Aloran<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1816, 'city', 1209, 'City created. <br/><br/>City Name: Baliangao<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1817, 'city', 1210, 'City created. <br/><br/>City Name: Bonifacio<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1818, 'city', 1211, 'City created. <br/><br/>City Name: Calamba<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1819, 'city', 1212, 'City created. <br/><br/>City Name: Clarin<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1820, 'city', 1213, 'City created. <br/><br/>City Name: Concepcion<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1821, 'city', 1214, 'City created. <br/><br/>City Name: Jimenez<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1822, 'city', 1215, 'City created. <br/><br/>City Name: Lopez Jaena<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1823, 'city', 1216, 'City created. <br/><br/>City Name: City of Oroquieta<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1824, 'city', 1217, 'City created. <br/><br/>City Name: City of Ozamiz<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1825, 'city', 1218, 'City created. <br/><br/>City Name: Panaon<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1826, 'city', 1219, 'City created. <br/><br/>City Name: Plaridel<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1827, 'city', 1220, 'City created. <br/><br/>City Name: Sapang Dalaga<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1828, 'city', 1221, 'City created. <br/><br/>City Name: Sinacaban<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1829, 'city', 1222, 'City created. <br/><br/>City Name: City of Tangub<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1830, 'city', 1223, 'City created. <br/><br/>City Name: Tudela<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1831, 'city', 1224, 'City created. <br/><br/>City Name: Don Victoriano Chiongbian<br/>State ID: 56', '0', '2023-09-11 16:57:44'),
(1832, 'city', 1225, 'City created. <br/><br/>City Name: Alubijid<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1833, 'city', 1226, 'City created. <br/><br/>City Name: Balingasag<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1834, 'city', 1227, 'City created. <br/><br/>City Name: Balingoan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1835, 'city', 1228, 'City created. <br/><br/>City Name: Binuangan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1836, 'city', 1229, 'City created. <br/><br/>City Name: City of Cagayan De Oro<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1837, 'city', 1230, 'City created. <br/><br/>City Name: Claveria<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1838, 'city', 1231, 'City created. <br/><br/>City Name: City of El Salvador<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1839, 'city', 1232, 'City created. <br/><br/>City Name: City of Gingoog<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1840, 'city', 1233, 'City created. <br/><br/>City Name: Gitagum<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1841, 'city', 1234, 'City created. <br/><br/>City Name: Initao<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1842, 'city', 1235, 'City created. <br/><br/>City Name: Jasaan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1843, 'city', 1236, 'City created. <br/><br/>City Name: Kinoguitan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1844, 'city', 1237, 'City created. <br/><br/>City Name: Lagonglong<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1845, 'city', 1238, 'City created. <br/><br/>City Name: Laguindingan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1846, 'city', 1239, 'City created. <br/><br/>City Name: Libertad<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1847, 'city', 1240, 'City created. <br/><br/>City Name: Lugait<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1848, 'city', 1241, 'City created. <br/><br/>City Name: Magsaysay<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1849, 'city', 1242, 'City created. <br/><br/>City Name: Manticao<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1850, 'city', 1243, 'City created. <br/><br/>City Name: Medina<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1851, 'city', 1244, 'City created. <br/><br/>City Name: Naawan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1852, 'city', 1245, 'City created. <br/><br/>City Name: Opol<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1853, 'city', 1246, 'City created. <br/><br/>City Name: Salay<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1854, 'city', 1247, 'City created. <br/><br/>City Name: Sugbongcogon<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1855, 'city', 1248, 'City created. <br/><br/>City Name: Tagoloan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1856, 'city', 1249, 'City created. <br/><br/>City Name: Talisayan<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1857, 'city', 1250, 'City created. <br/><br/>City Name: Villanueva<br/>State ID: 57', '0', '2023-09-11 16:57:44'),
(1858, 'city', 1251, 'City created. <br/><br/>City Name: Asuncion<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1859, 'city', 1252, 'City created. <br/><br/>City Name: Carmen<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1860, 'city', 1253, 'City created. <br/><br/>City Name: Kapalong<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1861, 'city', 1254, 'City created. <br/><br/>City Name: New Corella<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1862, 'city', 1255, 'City created. <br/><br/>City Name: City of Panabo<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1863, 'city', 1256, 'City created. <br/><br/>City Name: Island Garden City of Samal<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1864, 'city', 1257, 'City created. <br/><br/>City Name: Santo Tomas<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1865, 'city', 1258, 'City created. <br/><br/>City Name: City of Tagum<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1866, 'city', 1259, 'City created. <br/><br/>City Name: Talaingod<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1867, 'city', 1260, 'City created. <br/><br/>City Name: Braulio E. Dujali<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1868, 'city', 1261, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 58', '0', '2023-09-11 16:57:44'),
(1869, 'city', 1262, 'City created. <br/><br/>City Name: Bansalan<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1870, 'city', 1263, 'City created. <br/><br/>City Name: City of Davao<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1871, 'city', 1264, 'City created. <br/><br/>City Name: City of Digos<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1872, 'city', 1265, 'City created. <br/><br/>City Name: Hagonoy<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1873, 'city', 1266, 'City created. <br/><br/>City Name: Kiblawan<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1874, 'city', 1267, 'City created. <br/><br/>City Name: Magsaysay<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1875, 'city', 1268, 'City created. <br/><br/>City Name: Malalag<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1876, 'city', 1269, 'City created. <br/><br/>City Name: Matanao<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1877, 'city', 1270, 'City created. <br/><br/>City Name: Padada<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1878, 'city', 1271, 'City created. <br/><br/>City Name: Santa Cruz<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1879, 'city', 1272, 'City created. <br/><br/>City Name: Sulop<br/>State ID: 59', '0', '2023-09-11 16:57:44'),
(1880, 'city', 1273, 'City created. <br/><br/>City Name: Baganga<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1881, 'city', 1274, 'City created. <br/><br/>City Name: Banaybanay<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1882, 'city', 1275, 'City created. <br/><br/>City Name: Boston<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1883, 'city', 1276, 'City created. <br/><br/>City Name: Caraga<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1884, 'city', 1277, 'City created. <br/><br/>City Name: Cateel<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1885, 'city', 1278, 'City created. <br/><br/>City Name: Governor Generoso<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1886, 'city', 1279, 'City created. <br/><br/>City Name: Lupon<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1887, 'city', 1280, 'City created. <br/><br/>City Name: Manay<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1888, 'city', 1281, 'City created. <br/><br/>City Name: City of Mati<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1889, 'city', 1282, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1890, 'city', 1283, 'City created. <br/><br/>City Name: Tarragona<br/>State ID: 60', '0', '2023-09-11 16:57:44'),
(1891, 'city', 1284, 'City created. <br/><br/>City Name: Compostela<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1892, 'city', 1285, 'City created. <br/><br/>City Name: Laak<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1893, 'city', 1286, 'City created. <br/><br/>City Name: Mabini<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1894, 'city', 1287, 'City created. <br/><br/>City Name: Maco<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1895, 'city', 1288, 'City created. <br/><br/>City Name: Maragusan<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1896, 'city', 1289, 'City created. <br/><br/>City Name: Mawab<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1897, 'city', 1290, 'City created. <br/><br/>City Name: Monkayo<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1898, 'city', 1291, 'City created. <br/><br/>City Name: Montevista<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1899, 'city', 1292, 'City created. <br/><br/>City Name: Nabunturan<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1900, 'city', 1293, 'City created. <br/><br/>City Name: New Bataan<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1901, 'city', 1294, 'City created. <br/><br/>City Name: Pantukan<br/>State ID: 61', '0', '2023-09-11 16:57:44'),
(1902, 'city', 1295, 'City created. <br/><br/>City Name: Don Marcelino<br/>State ID: 62', '0', '2023-09-11 16:57:44'),
(1903, 'city', 1296, 'City created. <br/><br/>City Name: Jose Abad Santos<br/>State ID: 62', '0', '2023-09-11 16:57:44'),
(1904, 'city', 1297, 'City created. <br/><br/>City Name: Malita<br/>State ID: 62', '0', '2023-09-11 16:57:44'),
(1905, 'city', 1298, 'City created. <br/><br/>City Name: Santa Maria<br/>State ID: 62', '0', '2023-09-11 16:57:44'),
(1906, 'city', 1299, 'City created. <br/><br/>City Name: Sarangani<br/>State ID: 62', '0', '2023-09-11 16:57:44'),
(1907, 'city', 1300, 'City created. <br/><br/>City Name: Alamada<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1908, 'city', 1301, 'City created. <br/><br/>City Name: Carmen<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1909, 'city', 1302, 'City created. <br/><br/>City Name: Kabacan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1910, 'city', 1303, 'City created. <br/><br/>City Name: City of Kidapawan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1911, 'city', 1304, 'City created. <br/><br/>City Name: Libungan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1912, 'city', 1305, 'City created. <br/><br/>City Name: Magpet<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1913, 'city', 1306, 'City created. <br/><br/>City Name: Makilala<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1914, 'city', 1307, 'City created. <br/><br/>City Name: Matalam<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1915, 'city', 1308, 'City created. <br/><br/>City Name: Midsayap<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1916, 'city', 1309, 'City created. <br/><br/>City Name: M Lang<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1917, 'city', 1310, 'City created. <br/><br/>City Name: Pigkawayan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1918, 'city', 1311, 'City created. <br/><br/>City Name: Pikit<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1919, 'city', 1312, 'City created. <br/><br/>City Name: President Roxas<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1920, 'city', 1313, 'City created. <br/><br/>City Name: Tulunan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1921, 'city', 1314, 'City created. <br/><br/>City Name: Antipas<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1922, 'city', 1315, 'City created. <br/><br/>City Name: Banisilan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1923, 'city', 1316, 'City created. <br/><br/>City Name: Aleosan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1924, 'city', 1317, 'City created. <br/><br/>City Name: Arakan<br/>State ID: 63', '0', '2023-09-11 16:57:44'),
(1925, 'city', 1318, 'City created. <br/><br/>City Name: Banga<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1926, 'city', 1319, 'City created. <br/><br/>City Name: City of General Santos<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1927, 'city', 1320, 'City created. <br/><br/>City Name: City of Koronadal<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1928, 'city', 1321, 'City created. <br/><br/>City Name: Norala<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1929, 'city', 1322, 'City created. <br/><br/>City Name: Polomolok<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1930, 'city', 1323, 'City created. <br/><br/>City Name: Surallah<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1931, 'city', 1324, 'City created. <br/><br/>City Name: Tampakan<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1932, 'city', 1325, 'City created. <br/><br/>City Name: Tantangan<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1933, 'city', 1326, 'City created. <br/><br/>City Name: T Boli<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1934, 'city', 1327, 'City created. <br/><br/>City Name: Tupi<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1935, 'city', 1328, 'City created. <br/><br/>City Name: Santo Niño<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1936, 'city', 1329, 'City created. <br/><br/>City Name: Lake Sebu<br/>State ID: 64', '0', '2023-09-11 16:57:44'),
(1937, 'city', 1330, 'City created. <br/><br/>City Name: Bagumbayan<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1938, 'city', 1331, 'City created. <br/><br/>City Name: Columbio<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1939, 'city', 1332, 'City created. <br/><br/>City Name: Esperanza<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1940, 'city', 1333, 'City created. <br/><br/>City Name: Isulan<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1941, 'city', 1334, 'City created. <br/><br/>City Name: Kalamansig<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1942, 'city', 1335, 'City created. <br/><br/>City Name: Lebak<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1943, 'city', 1336, 'City created. <br/><br/>City Name: Lutayan<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1944, 'city', 1337, 'City created. <br/><br/>City Name: Lambayong<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1945, 'city', 1338, 'City created. <br/><br/>City Name: Palimbang<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1946, 'city', 1339, 'City created. <br/><br/>City Name: President Quirino<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1947, 'city', 1340, 'City created. <br/><br/>City Name: City of Tacurong<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1948, 'city', 1341, 'City created. <br/><br/>City Name: Sen. Ninoy Aquino<br/>State ID: 65', '0', '2023-09-11 16:57:44'),
(1949, 'city', 1342, 'City created. <br/><br/>City Name: Alabel<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1950, 'city', 1343, 'City created. <br/><br/>City Name: Glan<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1951, 'city', 1344, 'City created. <br/><br/>City Name: Kiamba<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1952, 'city', 1345, 'City created. <br/><br/>City Name: Maasim<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1953, 'city', 1346, 'City created. <br/><br/>City Name: Maitum<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1954, 'city', 1347, 'City created. <br/><br/>City Name: Malapatan<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1955, 'city', 1348, 'City created. <br/><br/>City Name: Malungon<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1956, 'city', 1349, 'City created. <br/><br/>City Name: Cotabato City<br/>State ID: 66', '0', '2023-09-11 16:57:44'),
(1957, 'city', 1350, 'City created. <br/><br/>City Name: Manila<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1958, 'city', 1351, 'City created. <br/><br/>City Name: Mandaluyong City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1959, 'city', 1352, 'City created. <br/><br/>City Name: Marikina City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1960, 'city', 1353, 'City created. <br/><br/>City Name: Pasig City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1961, 'city', 1354, 'City created. <br/><br/>City Name: Quezon City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1962, 'city', 1355, 'City created. <br/><br/>City Name: San Juan City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1963, 'city', 1356, 'City created. <br/><br/>City Name: Caloocan City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1964, 'city', 1357, 'City created. <br/><br/>City Name: Malabon City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1965, 'city', 1358, 'City created. <br/><br/>City Name: Navotas City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1966, 'city', 1359, 'City created. <br/><br/>City Name: Valenzuela City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1967, 'city', 1360, 'City created. <br/><br/>City Name: Las Piñas City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1968, 'city', 1361, 'City created. <br/><br/>City Name: Makati City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1969, 'city', 1362, 'City created. <br/><br/>City Name: Muntinlupa City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1970, 'city', 1363, 'City created. <br/><br/>City Name: Parañaque City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1971, 'city', 1364, 'City created. <br/><br/>City Name: Pasay City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1972, 'city', 1365, 'City created. <br/><br/>City Name: Pateros<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1973, 'city', 1366, 'City created. <br/><br/>City Name: Taguig City<br/>State ID: 1', '0', '2023-09-11 16:57:44'),
(1974, 'city', 1367, 'City created. <br/><br/>City Name: Bangued<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1975, 'city', 1368, 'City created. <br/><br/>City Name: Boliney<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1976, 'city', 1369, 'City created. <br/><br/>City Name: Bucay<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1977, 'city', 1370, 'City created. <br/><br/>City Name: Bucloc<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1978, 'city', 1371, 'City created. <br/><br/>City Name: Daguioman<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1979, 'city', 1372, 'City created. <br/><br/>City Name: Danglas<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1980, 'city', 1373, 'City created. <br/><br/>City Name: Dolores<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1981, 'city', 1374, 'City created. <br/><br/>City Name: La Paz<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1982, 'city', 1375, 'City created. <br/><br/>City Name: Lacub<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1983, 'city', 1376, 'City created. <br/><br/>City Name: Lagangilang<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1984, 'city', 1377, 'City created. <br/><br/>City Name: Lagayan<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1985, 'city', 1378, 'City created. <br/><br/>City Name: Langiden<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1986, 'city', 1379, 'City created. <br/><br/>City Name: Licuan-Baay<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1987, 'city', 1380, 'City created. <br/><br/>City Name: Luba<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1988, 'city', 1381, 'City created. <br/><br/>City Name: Malibcong<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1989, 'city', 1382, 'City created. <br/><br/>City Name: Manabo<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1990, 'city', 1383, 'City created. <br/><br/>City Name: Peñarrubia<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1991, 'city', 1384, 'City created. <br/><br/>City Name: Pidigan<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1992, 'city', 1385, 'City created. <br/><br/>City Name: Pilar<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1993, 'city', 1386, 'City created. <br/><br/>City Name: Sallapadan<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1994, 'city', 1387, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1995, 'city', 1388, 'City created. <br/><br/>City Name: San Juan<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1996, 'city', 1389, 'City created. <br/><br/>City Name: San Quintin<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1997, 'city', 1390, 'City created. <br/><br/>City Name: Tayum<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1998, 'city', 1391, 'City created. <br/><br/>City Name: Tineg<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(1999, 'city', 1392, 'City created. <br/><br/>City Name: Tubo<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(2000, 'city', 1393, 'City created. <br/><br/>City Name: Villaviciosa<br/>State ID: 67', '0', '2023-09-11 16:57:44'),
(2001, 'city', 1394, 'City created. <br/><br/>City Name: Atok<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2002, 'city', 1395, 'City created. <br/><br/>City Name: City of Baguio<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2003, 'city', 1396, 'City created. <br/><br/>City Name: Bakun<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2004, 'city', 1397, 'City created. <br/><br/>City Name: Bokod<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2005, 'city', 1398, 'City created. <br/><br/>City Name: Buguias<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2006, 'city', 1399, 'City created. <br/><br/>City Name: Itogon<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2007, 'city', 1400, 'City created. <br/><br/>City Name: Kabayan<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2008, 'city', 1401, 'City created. <br/><br/>City Name: Kapangan<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2009, 'city', 1402, 'City created. <br/><br/>City Name: Kibungan<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2010, 'city', 1403, 'City created. <br/><br/>City Name: La Trinidad<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2011, 'city', 1404, 'City created. <br/><br/>City Name: Mankayan<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2012, 'city', 1405, 'City created. <br/><br/>City Name: Sablan<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2013, 'city', 1406, 'City created. <br/><br/>City Name: Tuba<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2014, 'city', 1407, 'City created. <br/><br/>City Name: Tublay<br/>State ID: 68', '0', '2023-09-11 16:57:44'),
(2015, 'city', 1408, 'City created. <br/><br/>City Name: Banaue<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2016, 'city', 1409, 'City created. <br/><br/>City Name: Hungduan<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2017, 'city', 1410, 'City created. <br/><br/>City Name: Kiangan<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2018, 'city', 1411, 'City created. <br/><br/>City Name: Lagawe<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2019, 'city', 1412, 'City created. <br/><br/>City Name: Lamut<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2020, 'city', 1413, 'City created. <br/><br/>City Name: Mayoyao<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2021, 'city', 1414, 'City created. <br/><br/>City Name: Alfonso Lista<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2022, 'city', 1415, 'City created. <br/><br/>City Name: Aguinaldo<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2023, 'city', 1416, 'City created. <br/><br/>City Name: Hingyon<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2024, 'city', 1417, 'City created. <br/><br/>City Name: Tinoc<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2025, 'city', 1418, 'City created. <br/><br/>City Name: Asipulo<br/>State ID: 69', '0', '2023-09-11 16:57:44'),
(2026, 'city', 1419, 'City created. <br/><br/>City Name: Balbalan<br/>State ID: 70', '0', '2023-09-11 16:57:44'),
(2027, 'city', 1420, 'City created. <br/><br/>City Name: Lubuagan<br/>State ID: 70', '0', '2023-09-11 16:57:44'),
(2028, 'city', 1421, 'City created. <br/><br/>City Name: Pasil<br/>State ID: 70', '0', '2023-09-11 16:57:44'),
(2029, 'city', 1422, 'City created. <br/><br/>City Name: Pinukpuk<br/>State ID: 70', '0', '2023-09-11 16:57:44'),
(2030, 'city', 1423, 'City created. <br/><br/>City Name: Rizal<br/>State ID: 70', '0', '2023-09-11 16:57:44'),
(2031, 'city', 1424, 'City created. <br/><br/>City Name: City of Tabuk<br/>State ID: 70', '0', '2023-09-11 16:57:44'),
(2032, 'city', 1425, 'City created. <br/><br/>City Name: Tanudan<br/>State ID: 70', '0', '2023-09-11 16:57:44'),
(2033, 'city', 1426, 'City created. <br/><br/>City Name: Tinglayan<br/>State ID: 70', '0', '2023-09-11 16:57:44');
INSERT INTO `audit_log` (`audit_log_id`, `table_name`, `reference_id`, `log`, `changed_by`, `changed_at`) VALUES
(2034, 'city', 1427, 'City created. <br/><br/>City Name: Barlig<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2035, 'city', 1428, 'City created. <br/><br/>City Name: Bauko<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2036, 'city', 1429, 'City created. <br/><br/>City Name: Besao<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2037, 'city', 1430, 'City created. <br/><br/>City Name: Bontoc<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2038, 'city', 1431, 'City created. <br/><br/>City Name: Natonin<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2039, 'city', 1432, 'City created. <br/><br/>City Name: Paracelis<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2040, 'city', 1433, 'City created. <br/><br/>City Name: Sabangan<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2041, 'city', 1434, 'City created. <br/><br/>City Name: Sadanga<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2042, 'city', 1435, 'City created. <br/><br/>City Name: Sagada<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2043, 'city', 1436, 'City created. <br/><br/>City Name: Tadian<br/>State ID: 71', '0', '2023-09-11 16:57:44'),
(2044, 'city', 1437, 'City created. <br/><br/>City Name: Calanasan<br/>State ID: 72', '0', '2023-09-11 16:57:44'),
(2045, 'city', 1438, 'City created. <br/><br/>City Name: Conner<br/>State ID: 72', '0', '2023-09-11 16:57:44'),
(2046, 'city', 1439, 'City created. <br/><br/>City Name: Flora<br/>State ID: 72', '0', '2023-09-11 16:57:44'),
(2047, 'city', 1440, 'City created. <br/><br/>City Name: Kabugao<br/>State ID: 72', '0', '2023-09-11 16:57:44'),
(2048, 'city', 1441, 'City created. <br/><br/>City Name: Luna<br/>State ID: 72', '0', '2023-09-11 16:57:44'),
(2049, 'city', 1442, 'City created. <br/><br/>City Name: Pudtol<br/>State ID: 72', '0', '2023-09-11 16:57:44'),
(2050, 'city', 1443, 'City created. <br/><br/>City Name: Santa Marcela<br/>State ID: 72', '0', '2023-09-11 16:57:44'),
(2051, 'city', 1444, 'City created. <br/><br/>City Name: City of Lamitan<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2052, 'city', 1445, 'City created. <br/><br/>City Name: Lantawan<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2053, 'city', 1446, 'City created. <br/><br/>City Name: Maluso<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2054, 'city', 1447, 'City created. <br/><br/>City Name: Sumisip<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2055, 'city', 1448, 'City created. <br/><br/>City Name: Tipo-Tipo<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2056, 'city', 1449, 'City created. <br/><br/>City Name: Tuburan<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2057, 'city', 1450, 'City created. <br/><br/>City Name: Akbar<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2058, 'city', 1451, 'City created. <br/><br/>City Name: Al-Barka<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2059, 'city', 1452, 'City created. <br/><br/>City Name: Hadji Mohammad Ajul<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2060, 'city', 1453, 'City created. <br/><br/>City Name: Ungkaya Pukan<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2061, 'city', 1454, 'City created. <br/><br/>City Name: Hadji Muhtamad<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2062, 'city', 1455, 'City created. <br/><br/>City Name: Tabuan-Lasa<br/>State ID: 73', '0', '2023-09-11 16:57:44'),
(2063, 'city', 1456, 'City created. <br/><br/>City Name: Bacolod-Kalawi<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2064, 'city', 1457, 'City created. <br/><br/>City Name: Balabagan<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2065, 'city', 1458, 'City created. <br/><br/>City Name: Balindong<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2066, 'city', 1459, 'City created. <br/><br/>City Name: Bayang<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2067, 'city', 1460, 'City created. <br/><br/>City Name: Binidayan<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2068, 'city', 1461, 'City created. <br/><br/>City Name: Bubong<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2069, 'city', 1462, 'City created. <br/><br/>City Name: Butig<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2070, 'city', 1463, 'City created. <br/><br/>City Name: Ganassi<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2071, 'city', 1464, 'City created. <br/><br/>City Name: Kapai<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2072, 'city', 1465, 'City created. <br/><br/>City Name: Lumba-Bayabao<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2073, 'city', 1466, 'City created. <br/><br/>City Name: Lumbatan<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2074, 'city', 1467, 'City created. <br/><br/>City Name: Madalum<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2075, 'city', 1468, 'City created. <br/><br/>City Name: Madamba<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2076, 'city', 1469, 'City created. <br/><br/>City Name: Malabang<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2077, 'city', 1470, 'City created. <br/><br/>City Name: Marantao<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2078, 'city', 1471, 'City created. <br/><br/>City Name: City of Marawi<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2079, 'city', 1472, 'City created. <br/><br/>City Name: Masiu<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2080, 'city', 1473, 'City created. <br/><br/>City Name: Mulondo<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2081, 'city', 1474, 'City created. <br/><br/>City Name: Pagayawan<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2082, 'city', 1475, 'City created. <br/><br/>City Name: Piagapo<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2083, 'city', 1476, 'City created. <br/><br/>City Name: Poona Bayabao<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2084, 'city', 1477, 'City created. <br/><br/>City Name: Pualas<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2085, 'city', 1478, 'City created. <br/><br/>City Name: Ditsaan-Ramain<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2086, 'city', 1479, 'City created. <br/><br/>City Name: Saguiaran<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2087, 'city', 1480, 'City created. <br/><br/>City Name: Tamparan<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2088, 'city', 1481, 'City created. <br/><br/>City Name: Taraka<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2089, 'city', 1482, 'City created. <br/><br/>City Name: Tubaran<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2090, 'city', 1483, 'City created. <br/><br/>City Name: Tugaya<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2091, 'city', 1484, 'City created. <br/><br/>City Name: Wao<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2092, 'city', 1485, 'City created. <br/><br/>City Name: Marogong<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2093, 'city', 1486, 'City created. <br/><br/>City Name: Calanogas<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2094, 'city', 1487, 'City created. <br/><br/>City Name: Buadiposo-Buntong<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2095, 'city', 1488, 'City created. <br/><br/>City Name: Maguing<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2096, 'city', 1489, 'City created. <br/><br/>City Name: Picong<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2097, 'city', 1490, 'City created. <br/><br/>City Name: Lumbayanague<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2098, 'city', 1491, 'City created. <br/><br/>City Name: Amai Manabilang<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2099, 'city', 1492, 'City created. <br/><br/>City Name: Tagoloan Ii<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2100, 'city', 1493, 'City created. <br/><br/>City Name: Kapatagan<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2101, 'city', 1494, 'City created. <br/><br/>City Name: Sultan Dumalondong<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2102, 'city', 1495, 'City created. <br/><br/>City Name: Lumbaca-Unayan<br/>State ID: 74', '0', '2023-09-11 16:57:44'),
(2103, 'city', 1496, 'City created. <br/><br/>City Name: Ampatuan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2104, 'city', 1497, 'City created. <br/><br/>City Name: Buldon<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2105, 'city', 1498, 'City created. <br/><br/>City Name: Buluan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2106, 'city', 1499, 'City created. <br/><br/>City Name: Datu Paglas<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2107, 'city', 1500, 'City created. <br/><br/>City Name: Datu Piang<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2108, 'city', 1501, 'City created. <br/><br/>City Name: Datu Odin Sinsuat<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2109, 'city', 1502, 'City created. <br/><br/>City Name: Shariff Aguak<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2110, 'city', 1503, 'City created. <br/><br/>City Name: Matanog<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2111, 'city', 1504, 'City created. <br/><br/>City Name: Pagalungan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2112, 'city', 1505, 'City created. <br/><br/>City Name: Parang<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2113, 'city', 1506, 'City created. <br/><br/>City Name: Sultan Kudarat<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2114, 'city', 1507, 'City created. <br/><br/>City Name: Sultan Sa Barongis<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2115, 'city', 1508, 'City created. <br/><br/>City Name: Kabuntalan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2116, 'city', 1509, 'City created. <br/><br/>City Name: Upi<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2117, 'city', 1510, 'City created. <br/><br/>City Name: Talayan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2118, 'city', 1511, 'City created. <br/><br/>City Name: South Upi<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2119, 'city', 1512, 'City created. <br/><br/>City Name: Barira<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2120, 'city', 1513, 'City created. <br/><br/>City Name: Gen. S.K. Pendatun<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2121, 'city', 1514, 'City created. <br/><br/>City Name: Mamasapano<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2122, 'city', 1515, 'City created. <br/><br/>City Name: Talitay<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2123, 'city', 1516, 'City created. <br/><br/>City Name: Pagagawan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2124, 'city', 1517, 'City created. <br/><br/>City Name: Paglat<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2125, 'city', 1518, 'City created. <br/><br/>City Name: Sultan Mastura<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2126, 'city', 1519, 'City created. <br/><br/>City Name: Guindulungan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2127, 'city', 1520, 'City created. <br/><br/>City Name: Datu Saudi-Ampatuan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2128, 'city', 1521, 'City created. <br/><br/>City Name: Datu Unsay<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2129, 'city', 1522, 'City created. <br/><br/>City Name: Datu Abdullah Sangki<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2130, 'city', 1523, 'City created. <br/><br/>City Name: Rajah Buayan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2131, 'city', 1524, 'City created. <br/><br/>City Name: Datu Blah T. Sinsuat<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2132, 'city', 1525, 'City created. <br/><br/>City Name: Datu Anggal Midtimbang<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2133, 'city', 1526, 'City created. <br/><br/>City Name: Mangudadatu<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2134, 'city', 1527, 'City created. <br/><br/>City Name: Pandag<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2135, 'city', 1528, 'City created. <br/><br/>City Name: Northern Kabuntalan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2136, 'city', 1529, 'City created. <br/><br/>City Name: Datu Hoffer Ampatuan<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2137, 'city', 1530, 'City created. <br/><br/>City Name: Datu Salibo<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2138, 'city', 1531, 'City created. <br/><br/>City Name: Shariff Saydona Mustapha<br/>State ID: 75', '0', '2023-09-11 16:57:44'),
(2139, 'city', 1532, 'City created. <br/><br/>City Name: Indanan<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2140, 'city', 1533, 'City created. <br/><br/>City Name: Jolo<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2141, 'city', 1534, 'City created. <br/><br/>City Name: Kalingalan Caluang<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2142, 'city', 1535, 'City created. <br/><br/>City Name: Luuk<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2143, 'city', 1536, 'City created. <br/><br/>City Name: Maimbung<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2144, 'city', 1537, 'City created. <br/><br/>City Name: Hadji Panglima Tahil<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2145, 'city', 1538, 'City created. <br/><br/>City Name: Old Panamao<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2146, 'city', 1539, 'City created. <br/><br/>City Name: Pangutaran<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2147, 'city', 1540, 'City created. <br/><br/>City Name: Parang<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2148, 'city', 1541, 'City created. <br/><br/>City Name: Pata<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2149, 'city', 1542, 'City created. <br/><br/>City Name: Patikul<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2150, 'city', 1543, 'City created. <br/><br/>City Name: Siasi<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2151, 'city', 1544, 'City created. <br/><br/>City Name: Talipao<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2152, 'city', 1545, 'City created. <br/><br/>City Name: Tapul<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2153, 'city', 1546, 'City created. <br/><br/>City Name: Tongkil<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2154, 'city', 1547, 'City created. <br/><br/>City Name: Panglima Estino<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2155, 'city', 1548, 'City created. <br/><br/>City Name: Lugus<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2156, 'city', 1549, 'City created. <br/><br/>City Name: Pandami<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2157, 'city', 1550, 'City created. <br/><br/>City Name: Omar<br/>State ID: 76', '0', '2023-09-11 16:57:44'),
(2158, 'city', 1551, 'City created. <br/><br/>City Name: Panglima Sugala<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2159, 'city', 1552, 'City created. <br/><br/>City Name: Bongao (Capital)<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2160, 'city', 1553, 'City created. <br/><br/>City Name: Mapun<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2161, 'city', 1554, 'City created. <br/><br/>City Name: Simunul<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2162, 'city', 1555, 'City created. <br/><br/>City Name: Sitangkai<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2163, 'city', 1556, 'City created. <br/><br/>City Name: South Ubian<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2164, 'city', 1557, 'City created. <br/><br/>City Name: Tandubas<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2165, 'city', 1558, 'City created. <br/><br/>City Name: Turtle Islands<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2166, 'city', 1559, 'City created. <br/><br/>City Name: Languyan<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2167, 'city', 1560, 'City created. <br/><br/>City Name: Sapa-Sapa<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2168, 'city', 1561, 'City created. <br/><br/>City Name: Sibutu<br/>State ID: 77', '0', '2023-09-11 16:57:45'),
(2169, 'city', 1562, 'City created. <br/><br/>City Name: Buenavista<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2170, 'city', 1563, 'City created. <br/><br/>City Name: City of Butuan<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2171, 'city', 1564, 'City created. <br/><br/>City Name: City of Cabadbaran<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2172, 'city', 1565, 'City created. <br/><br/>City Name: Carmen<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2173, 'city', 1566, 'City created. <br/><br/>City Name: Jabonga<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2174, 'city', 1567, 'City created. <br/><br/>City Name: Kitcharao<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2175, 'city', 1568, 'City created. <br/><br/>City Name: Las Nieves<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2176, 'city', 1569, 'City created. <br/><br/>City Name: Magallanes<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2177, 'city', 1570, 'City created. <br/><br/>City Name: Nasipit<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2178, 'city', 1571, 'City created. <br/><br/>City Name: Santiago<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2179, 'city', 1572, 'City created. <br/><br/>City Name: Tubay<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2180, 'city', 1573, 'City created. <br/><br/>City Name: Remedios T. Romualdez<br/>State ID: 78', '0', '2023-09-11 16:57:45'),
(2181, 'city', 1574, 'City created. <br/><br/>City Name: City of Bayugan<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2182, 'city', 1575, 'City created. <br/><br/>City Name: Bunawan<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2183, 'city', 1576, 'City created. <br/><br/>City Name: Esperanza<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2184, 'city', 1577, 'City created. <br/><br/>City Name: La Paz<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2185, 'city', 1578, 'City created. <br/><br/>City Name: Loreto<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2186, 'city', 1579, 'City created. <br/><br/>City Name: Prosperidad<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2187, 'city', 1580, 'City created. <br/><br/>City Name: Rosario<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2188, 'city', 1581, 'City created. <br/><br/>City Name: San Francisco<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2189, 'city', 1582, 'City created. <br/><br/>City Name: San Luis<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2190, 'city', 1583, 'City created. <br/><br/>City Name: Santa Josefa<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2191, 'city', 1584, 'City created. <br/><br/>City Name: Talacogon<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2192, 'city', 1585, 'City created. <br/><br/>City Name: Trento<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2193, 'city', 1586, 'City created. <br/><br/>City Name: Veruela<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2194, 'city', 1587, 'City created. <br/><br/>City Name: Sibagat<br/>State ID: 79', '0', '2023-09-11 16:57:45'),
(2195, 'city', 1588, 'City created. <br/><br/>City Name: Alegria<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2196, 'city', 1589, 'City created. <br/><br/>City Name: Bacuag<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2197, 'city', 1590, 'City created. <br/><br/>City Name: Burgos<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2198, 'city', 1591, 'City created. <br/><br/>City Name: Claver<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2199, 'city', 1592, 'City created. <br/><br/>City Name: Dapa<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2200, 'city', 1593, 'City created. <br/><br/>City Name: Del Carmen<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2201, 'city', 1594, 'City created. <br/><br/>City Name: General Luna<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2202, 'city', 1595, 'City created. <br/><br/>City Name: Gigaquit<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2203, 'city', 1596, 'City created. <br/><br/>City Name: Mainit<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2204, 'city', 1597, 'City created. <br/><br/>City Name: Malimono<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2205, 'city', 1598, 'City created. <br/><br/>City Name: Pilar<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2206, 'city', 1599, 'City created. <br/><br/>City Name: Placer<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2207, 'city', 1600, 'City created. <br/><br/>City Name: San Benito<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2208, 'city', 1601, 'City created. <br/><br/>City Name: San Francisco<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2209, 'city', 1602, 'City created. <br/><br/>City Name: San Isidro<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2210, 'city', 1603, 'City created. <br/><br/>City Name: Santa Monica<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2211, 'city', 1604, 'City created. <br/><br/>City Name: Sison<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2212, 'city', 1605, 'City created. <br/><br/>City Name: Socorro<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2213, 'city', 1606, 'City created. <br/><br/>City Name: City of Surigao<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2214, 'city', 1607, 'City created. <br/><br/>City Name: Tagana-An<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2215, 'city', 1608, 'City created. <br/><br/>City Name: Tubod<br/>State ID: 80', '0', '2023-09-11 16:57:45'),
(2216, 'city', 1609, 'City created. <br/><br/>City Name: Barobo<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2217, 'city', 1610, 'City created. <br/><br/>City Name: Bayabas<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2218, 'city', 1611, 'City created. <br/><br/>City Name: City of Bislig<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2219, 'city', 1612, 'City created. <br/><br/>City Name: Cagwait<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2220, 'city', 1613, 'City created. <br/><br/>City Name: Cantilan<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2221, 'city', 1614, 'City created. <br/><br/>City Name: Carmen<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2222, 'city', 1615, 'City created. <br/><br/>City Name: Carrascal<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2223, 'city', 1616, 'City created. <br/><br/>City Name: Cortes<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2224, 'city', 1617, 'City created. <br/><br/>City Name: Hinatuan<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2225, 'city', 1618, 'City created. <br/><br/>City Name: Lanuza<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2226, 'city', 1619, 'City created. <br/><br/>City Name: Lianga<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2227, 'city', 1620, 'City created. <br/><br/>City Name: Lingig<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2228, 'city', 1621, 'City created. <br/><br/>City Name: Madrid<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2229, 'city', 1622, 'City created. <br/><br/>City Name: Marihatag<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2230, 'city', 1623, 'City created. <br/><br/>City Name: San Agustin<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2231, 'city', 1624, 'City created. <br/><br/>City Name: San Miguel<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2232, 'city', 1625, 'City created. <br/><br/>City Name: Tagbina<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2233, 'city', 1626, 'City created. <br/><br/>City Name: Tago<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2234, 'city', 1627, 'City created. <br/><br/>City Name: City of Tandag<br/>State ID: 81', '0', '2023-09-11 16:57:45'),
(2235, 'city', 1628, 'City created. <br/><br/>City Name: Basilisa<br/>State ID: 82', '0', '2023-09-11 16:57:45'),
(2236, 'city', 1629, 'City created. <br/><br/>City Name: Cagdianao<br/>State ID: 82', '0', '2023-09-11 16:57:45'),
(2237, 'city', 1630, 'City created. <br/><br/>City Name: Dinagat<br/>State ID: 82', '0', '2023-09-11 16:57:45'),
(2238, 'city', 1631, 'City created. <br/><br/>City Name: Libjo<br/>State ID: 82', '0', '2023-09-11 16:57:45'),
(2239, 'city', 1632, 'City created. <br/><br/>City Name: Loreto<br/>State ID: 82', '0', '2023-09-11 16:57:45'),
(2240, 'city', 1633, 'City created. <br/><br/>City Name: San Jose<br/>State ID: 82', '0', '2023-09-11 16:57:45'),
(2241, 'city', 1634, 'City created. <br/><br/>City Name: Tubajon<br/>State ID: 82', '0', '2023-09-11 16:57:45'),
(2242, 'currency', 1, 'Currency created. <br/><br/>Currency Name: Philippine Peso<br/>Symbol: ₱<br/>Shorthand: PHP', '0', '2023-09-11 16:57:45'),
(2243, 'currency', 2, 'Currency created. <br/><br/>Currency Name: United States Dollar<br/>Symbol: $<br/>Shorthand: USD', '0', '2023-09-11 16:57:45'),
(2244, 'currency', 3, 'Currency created. <br/><br/>Currency Name: Japanese Yen<br/>Symbol: ¥<br/>Shorthand: JPY', '0', '2023-09-11 16:57:45'),
(2245, 'currency', 4, 'Currency created. <br/><br/>Currency Name: South Korean Won<br/>Symbol: ₩<br/>Shorthand: KRW', '0', '2023-09-11 16:57:45'),
(2246, 'currency', 5, 'Currency created. <br/><br/>Currency Name: Euro<br/>Symbol: €<br/>Shorthand: EUR', '0', '2023-09-11 16:57:45'),
(2247, 'currency', 6, 'Currency created. <br/><br/>Currency Name: Pound Sterling<br/>Symbol: £<br/>Shorthand: GBP', '0', '2023-09-11 16:57:45'),
(2248, 'email_setting', 1, 'Email setting created. <br/><br/>Email Setting Name: Security Email Setting<br/>Email Setting Description: \r\nEmail setting for security emails.<br/>Mail Host: smtp.hostinger.com<br/>Port: 465<br/>SMTP Auth: 1<br/>Mail Username: encore-noreply@encorefinancials.com<br/>Mail Encryption: ssl<br/>Mail From Name: encore-noreply@encorefinancials.com<br/>Mail From Email: encore-noreply@encorefinancials.com', '0', '2023-09-11 16:57:45'),
(2249, 'notification_setting', 1, 'Notification setting created. <br/><br/>Notification Setting Name: Login OTP<br/>Notification Setting Description: Notification setting for Login OTP received by the users.<br/>Email Notification: 1<br/>Email Notification Subject: Login OTP - Secure Access to Your Account<br/>Email Notification Body: <p>To ensure the security of your account, we have generated a unique One-Time Password (OTP) for you to use during the login process. Please use the following OTP to access your account:</p>\r\n<p>OTP: <strong>{OTP_CODE}</strong></p>\r\n<p>Please note that this OTP is valid for &lt;strong&gt;5 minutes&lt;/strong&gt;. Once you have logged in successfully, we recommend enabling two-factor authentication for an added layer of security.</p>\r\n<p>If you did not initiate this login or believe it was sent to you in error, please disregard this email and delete it immediately. Your account\'s security remains our utmost priority.</p>\r\n<p>&nbsp;</p>\r\n<p>Note: This is an automatically generated email. Please do not reply to this address.</p>', '1', '2023-09-11 16:57:45'),
(2250, 'notification_setting', 2, 'Notification setting created. <br/><br/>Notification Setting Name: Forgot Password<br/>Notification Setting Description: Notification setting when the user initiates forgot password.<br/>Email Notification: 1<br/>Email Notification Subject: Password Reset Request - Action Required<br/>Email Notification Body: <p>We have received a request to reset your password. To ensure the security of your account, please follow the instructions below:</p>\r\n<p>1. Click on the link below to reset your password:</p>\r\n<p><a href=\"{RESET_LINK}\"><strong>Reset Password</strong></a></p>\r\n<p>2. If the button does not work, you can copy and paste the following link into your browser\'s address bar:</p>\r\n<p><strong>{RESET_LINK}</strong></p>\r\n<p>Please note that this link is time-sensitive and will expire after <strong>10 minutes</strong>. If you do not reset your password within this timeframe, you may need to request another password reset.</p>\r\n<p>If you did not initiate this password reset request or believe it was sent to you in error, please disregard this email and delete it immediately. Your account\'s security remains our utmost priority.</p>\r\n<p>&nbsp;</p>\r\n<p>Note: This is an automatically generated email. Please do not reply to this address.</p>', '1', '2023-09-11 16:57:45'),
(2251, 'job_position', 1, 'Job position created. <br/><br/>Job Position Name: tes<br/>Job Position Description: test', '1', '2023-09-11 17:00:02'),
(2252, 'job_position_responsibility', 1, 'Job position responsibility created. <br/><br/>Responsibility: test', '1', '2023-09-11 17:00:05'),
(2253, 'job_position_requirement', 1, 'Job position requirement created. <br/><br/>Requirement: test', '1', '2023-09-11 17:00:07'),
(2254, 'job_position_qualification', 1, 'Job position qualification created. <br/><br/>Qualification: test', '1', '2023-09-11 17:00:10'),
(2255, 'job_position', 2, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:26:31'),
(2256, 'job_position_responsibility', 2, 'Job position responsibility created. <br/><br/>Responsibility: test', '1', '2023-09-11 17:26:35'),
(2257, 'job_position_requirement', 2, 'Job position requirement created. <br/><br/>Requirement: test', '1', '2023-09-11 17:26:38'),
(2258, 'job_position_qualification', 2, 'Job position qualification created. <br/><br/>Qualification: test', '1', '2023-09-11 17:26:41'),
(2259, 'job_position', 3, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:26:45'),
(2260, 'job_position', 4, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:27:04'),
(2261, 'job_position', 5, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:28:16'),
(2262, 'job_position', 6, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:29:35'),
(2263, 'job_position', 7, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:31:13'),
(2264, 'job_position_qualification', 3, 'Job position qualification created. <br/><br/>Qualification: test', '1', '2023-09-11 17:31:13'),
(2265, 'job_position', 8, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:32:01'),
(2266, 'job_position_responsibility', 3, 'Job position responsibility created. <br/><br/>Responsibility: test', '1', '2023-09-11 17:32:01'),
(2267, 'job_position_requirement', 3, 'Job position requirement created. <br/><br/>Requirement: test', '1', '2023-09-11 17:32:01'),
(2268, 'job_position_qualification', 4, 'Job position qualification created. <br/><br/>Qualification: test', '1', '2023-09-11 17:32:01'),
(2269, 'job_position', 9, 'Job position created. <br/><br/>Job Position Name: test<br/>Job Position Description: test', '1', '2023-09-11 17:32:30'),
(2270, 'job_position_responsibility', 4, 'Job position responsibility created. <br/><br/>Responsibility: test', '1', '2023-09-11 17:32:30'),
(2271, 'job_position_requirement', 4, 'Job position requirement created. <br/><br/>Requirement: test', '1', '2023-09-11 17:32:30'),
(2272, 'job_position_qualification', 5, 'Job position qualification created. <br/><br/>Qualification: test', '1', '2023-09-11 17:32:30'),
(2273, 'menu_item', 29, 'Menu item created. <br/><br/>Menu Item Name: Job Level<br/>Menu Group ID: 1<br/>URL: job-level.php<br/>Parent ID: 25<br/>Order Sequence: 3', '0', '2023-09-12 09:46:17'),
(2274, 'menu_item_access_right', 29, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 09:46:17'),
(2275, 'job_level', 1, 'Job level created. <br/><br/>Current Level: I<br/>Rank: Trainee<br/>Functional Level: Unskilled', '1', '2023-09-12 10:33:49'),
(2276, 'job_level', 1, 'Current Level: I -> II<br/>Rank: Trainee -> Trainees<br/>Functional Level: Unskilled -> Unskilleds<br/>', '1', '2023-09-12 10:37:39'),
(2277, 'job_level', 2, 'Job level created. <br/><br/>Current Level: II<br/>Rank: Trainees<br/>Functional Level: Unskilleds', '1', '2023-09-12 10:38:27'),
(2278, 'job_level', 3, 'Job level created. <br/><br/>Current Level: II<br/>Rank: Trainees<br/>Functional Level: Unskilleds', '1', '2023-09-12 10:38:29'),
(2279, 'menu_item', 30, 'Menu item created. <br/><br/>Menu Item Name: Employee Type<br/>Menu Group ID: 1<br/>URL: employee-type.php<br/>Parent ID: 25<br/>Order Sequence: 3', '0', '2023-09-12 10:39:52'),
(2280, 'menu_item_access_right', 30, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 10:39:52'),
(2281, 'employee_type', 1, 'Employee type created. <br/><br/>Employee Type Name: test', '1', '2023-09-12 11:19:11'),
(2282, 'employee_type', 1, 'Employee Type Name: test -> test2<br/>', '1', '2023-09-12 11:19:15'),
(2283, 'employee_type', 2, 'Employee type created. <br/><br/>Employee Type Name: test2', '1', '2023-09-12 11:19:19'),
(2284, 'employee_type', 3, 'Employee type created. <br/><br/>Employee Type Name: test2', '1', '2023-09-12 11:19:21'),
(2285, 'menu_item', 31, 'Menu item created. <br/><br/>Menu Item Name: Departure Reason<br/>Menu Group ID: 1<br/>URL: departure-reason.php<br/>Parent ID: 25<br/>Order Sequence: 4', '0', '2023-09-12 11:26:42'),
(2286, 'menu_item_access_right', 31, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 11:26:42'),
(2287, 'departure_reason', 1, 'Departure reason created. <br/><br/>Departure Reason Name: test', '1', '2023-09-12 11:38:18'),
(2288, 'departure_reason', 1, 'Departure Reason Name: test -> test2<br/>', '1', '2023-09-12 11:38:21'),
(2289, 'departure_reason', 2, 'Departure reason created. <br/><br/>Departure Reason Name: test2', '1', '2023-09-12 11:38:25'),
(2290, 'departure_reason', 3, 'Departure reason created. <br/><br/>Departure Reason Name: test2', '1', '2023-09-12 11:38:28'),
(2291, 'menu_item', 32, 'Menu item created. <br/><br/>Menu Item Name: ID Type<br/>Menu Group ID: 1<br/>URL: id-type.php<br/>Parent ID: 25<br/>Order Sequence: 9', '0', '2023-09-12 11:39:54'),
(2292, 'menu_item_access_right', 32, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 11:39:54'),
(2293, 'id_type', 1, 'ID type created. <br/><br/>ID Type Name: test', '1', '2023-09-12 11:57:39'),
(2294, 'id_type', 1, 'ID Type Name: test -> test2<br/>', '1', '2023-09-12 11:58:06'),
(2295, 'id_type', 2, 'ID type created. <br/><br/>ID Type Name: test2', '1', '2023-09-12 11:58:08'),
(2296, 'id_type', 3, 'ID type created. <br/><br/>ID Type Name: test2', '1', '2023-09-12 11:58:11'),
(2297, 'menu_item', 33, 'Menu item created. <br/><br/>Menu Item Name: Gender<br/>Menu Group ID: 1<br/>URL: gender.php<br/>Parent ID: 25<br/>Order Sequence: 7', '0', '2023-09-12 12:28:33'),
(2298, 'menu_item_access_right', 33, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 12:28:33'),
(2299, 'gender', 1, 'Gender created. <br/><br/>Departure Reason Name: test', '1', '2023-09-12 13:26:34'),
(2300, 'gender', 1, 'Departure Reason Name: test -> test2<br/>', '1', '2023-09-12 13:27:26'),
(2301, 'gender', 2, 'Gender created. <br/><br/>Departure Reason Name: test2', '1', '2023-09-12 13:27:29'),
(2302, 'gender', 3, 'Gender created. <br/><br/>Departure Reason Name: test2', '1', '2023-09-12 13:27:32'),
(2303, 'menu_item', 34, 'Menu item created. <br/><br/>Menu Item Name: Religion<br/>Menu Group ID: 1<br/>URL: gender.php<br/>Parent ID: 25<br/>Order Sequence: 18', '0', '2023-09-12 13:29:00'),
(2304, 'menu_item_access_right', 34, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 13:29:00'),
(2305, 'menu_item', 34, 'URL: gender.php -> religion.php<br/>', '0', '2023-09-12 13:35:52'),
(2306, 'religion', 1, 'Religion created. <br/><br/>Religion Name: test', '1', '2023-09-12 13:35:58'),
(2307, 'religion', 1, 'Religion Name: test -> test2<br/>', '1', '2023-09-12 13:36:03'),
(2308, 'religion', 2, 'Religion created. <br/><br/>Religion Name: test2', '1', '2023-09-12 13:36:06'),
(2309, 'religion', 3, 'Religion created. <br/><br/>Religion Name: test2', '1', '2023-09-12 13:36:08'),
(2310, 'menu_item', 35, 'Menu item created. <br/><br/>Menu Item Name: Nationality<br/>Menu Group ID: 1<br/>URL: nationality.php<br/>Parent ID: 25<br/>Order Sequence: 14', '0', '2023-09-12 13:43:28'),
(2311, 'menu_item_access_right', 35, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 13:43:28'),
(2312, 'nationality', 1, 'Nationality created. <br/><br/>Nationality Name: test', '1', '2023-09-12 13:50:37'),
(2313, 'nationality', 1, 'Nationality Name: test -> test2<br/>', '1', '2023-09-12 13:50:50'),
(2314, 'nationality', 2, 'Nationality created. <br/><br/>Nationality Name: test2', '1', '2023-09-12 13:50:52'),
(2315, 'nationality', 3, 'Nationality created. <br/><br/>Nationality Name: test2', '1', '2023-09-12 13:50:55'),
(2316, 'menu_item', 36, 'Menu item created. <br/><br/>Menu Item Name: Relation<br/>Menu Group ID: 1<br/>URL: relation.php<br/>Parent ID: 25<br/>Order Sequence: 18', '0', '2023-09-12 13:53:34'),
(2317, 'menu_item_access_right', 36, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 13:53:34'),
(2318, 'relation', 1, 'Relation created. <br/><br/>Relation Name: test', '1', '2023-09-12 13:58:20'),
(2319, 'relation', 1, 'Relation Name: test -> test2<br/>', '1', '2023-09-12 13:58:25'),
(2320, 'relation', 2, 'Relation created. <br/><br/>Relation Name: test2', '1', '2023-09-12 13:58:30'),
(2321, 'relation', 3, 'Relation created. <br/><br/>Relation Name: test2', '1', '2023-09-12 13:58:33'),
(2322, 'menu_item', 37, 'Menu item created. <br/><br/>Menu Item Name: Civil Status<br/>Menu Group ID: 1<br/>URL: civil-status.php<br/>Parent ID: 25<br/>Order Sequence: 3', '0', '2023-09-12 14:01:11'),
(2323, 'menu_item_access_right', 37, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 14:01:11'),
(2324, 'civil_status', 1, 'Civil status created. <br/><br/>Civil Status Name: test', '1', '2023-09-12 14:44:05'),
(2325, 'civil_status', 1, 'Civil Status Name: test -> test2<br/>', '1', '2023-09-12 14:44:08'),
(2326, 'civil_status', 2, 'Civil status created. <br/><br/>Civil Status Name: test2', '1', '2023-09-12 14:44:14'),
(2327, 'civil_status', 3, 'Civil status created. <br/><br/>Civil Status Name: test2', '1', '2023-09-12 14:44:16'),
(2328, 'menu_item', 38, 'Menu item created. <br/><br/>Menu Item Name: Blood Type<br/>Menu Group ID: 1<br/>URL: blood-type.php<br/>Parent ID: 25<br/>Order Sequence: 2', '0', '2023-09-12 14:45:48'),
(2329, 'menu_item_access_right', 38, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 14:45:52'),
(2330, 'blood_type', 1, 'Blood type created. <br/><br/>Blood Type Name: test', '1', '2023-09-12 15:02:54'),
(2331, 'blood_type', 1, 'Blood Type Name: test -> test2<br/>', '1', '2023-09-12 15:02:58'),
(2332, 'blood_type', 2, 'Blood type created. <br/><br/>Blood Type Name: test2', '1', '2023-09-12 15:03:01'),
(2333, 'blood_type', 3, 'Blood type created. <br/><br/>Blood Type Name: test2', '1', '2023-09-12 15:03:03'),
(2334, 'menu_item', 39, 'Menu item created. <br/><br/>Menu Item Name: Bank<br/>Menu Group ID: 3<br/>URL: bank.php<br/>Parent ID: 11<br/>Order Sequence: 2', '0', '2023-09-12 15:20:35'),
(2335, 'menu_item_access_right', 39, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 15:20:35'),
(2336, 'bank', 1, 'Bank created. <br/><br/>Bank Name: test', '1', '2023-09-12 15:43:22'),
(2337, 'bank', 1, 'Bank Name: test -> testtest<br/>Bank Identifier Code:  -> test<br/>', '1', '2023-09-12 15:43:26'),
(2338, 'bank', 2, 'Bank created. <br/><br/>Bank Name: test<br/>Bank Identifier Code: test', '1', '2023-09-12 15:43:31'),
(2339, 'bank', 3, 'Bank created. <br/><br/>Bank Name: test<br/>Bank Identifier Code: test', '1', '2023-09-12 15:43:33'),
(2340, 'bank', 4, 'Bank created. <br/><br/>Bank Name: test<br/>Bank Identifier Code: test', '1', '2023-09-12 15:43:36'),
(2341, 'menu_item', 40, 'Menu item created. <br/><br/>Menu Item Name: Holiday Type<br/>Menu Group ID: 1<br/>URL: blood-type.php<br/>Parent ID: 25<br/>Order Sequence: 2', '0', '2023-09-12 15:46:15'),
(2342, 'menu_item_access_right', 40, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 15:46:15'),
(2343, 'menu_item', 40, 'Order Sequence: 2 -> 8<br/>', '0', '2023-09-12 15:46:49'),
(2344, 'menu_item', 40, 'URL: blood-type.php -> holiday-type.php<br/>', '0', '2023-09-12 15:46:55'),
(2345, 'holiday_type', 1, 'Holiday type created. <br/><br/>Holiday Type Name: national', '1', '2023-09-12 16:06:18'),
(2346, 'holiday_type', 1, 'Holiday Type Name: national -> local<br/>', '1', '2023-09-12 16:06:21'),
(2347, 'holiday_type', 2, 'Holiday type created. <br/><br/>Holiday Type Name: local', '1', '2023-09-12 16:06:29'),
(2348, 'holiday_type', 3, 'Holiday type created. <br/><br/>Holiday Type Name: local', '1', '2023-09-12 16:06:34'),
(2349, 'menu_item', 41, 'Menu item created. <br/><br/>Menu Item Name: Work Schedule Type<br/>Menu Group ID: 1<br/>URL: work-schedule-type.php<br/>Parent ID: 25<br/>Order Sequence: 23', '0', '2023-09-12 16:18:09'),
(2350, 'menu_item_access_right', 41, 'Menu item access rights created. <br/><br/>Role ID: 1<br/>Read Access: 1<br/>Write Access: 1<br/>Create Access: 1<br/>Delete Access: 1<br/>Duplicate Access: 1', '0', '2023-09-12 16:18:09'),
(2351, 'work_schedule_type', 1, 'Work schedule type created. <br/><br/>Work Schedule Type Name: test', '1', '2023-09-12 17:18:54'),
(2352, 'work_schedule_type', 1, 'Work Schedule Type Name: test -> test2<br/>', '1', '2023-09-12 17:18:57'),
(2353, 'work_schedule_type', 2, 'Work schedule type created. <br/><br/>Work Schedule Type Name: test2', '1', '2023-09-12 17:19:00'),
(2354, 'work_schedule_type', 3, 'Work schedule type created. <br/><br/>Work Schedule Type Name: test2', '1', '2023-09-12 17:19:02');

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `bank_id` int(10) UNSIGNED NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_identifier_code` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `bank`
--
DELIMITER $$
CREATE TRIGGER `bank_trigger_insert` AFTER INSERT ON `bank` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Bank created. <br/>';

    IF NEW.bank_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Name: ", NEW.bank_name);
    END IF;

    IF NEW.bank_identifier_code <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Identifier Code: ", NEW.bank_identifier_code);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('bank', NEW.bank_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `bank_trigger_update` AFTER UPDATE ON `bank` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.bank_name <> OLD.bank_name THEN
        SET audit_log = CONCAT(audit_log, "Bank Name: ", OLD.bank_name, " -> ", NEW.bank_name, "<br/>");
    END IF;

    IF NEW.bank_identifier_code <> OLD.bank_identifier_code THEN
        SET audit_log = CONCAT(audit_log, "Bank Identifier Code: ", OLD.bank_identifier_code, " -> ", NEW.bank_identifier_code, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('bank', NEW.bank_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `blood_type`
--

CREATE TABLE `blood_type` (
  `blood_type_id` int(10) UNSIGNED NOT NULL,
  `blood_type_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `blood_type`
--
DELIMITER $$
CREATE TRIGGER `blood_type_trigger_insert` AFTER INSERT ON `blood_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Blood type created. <br/>';

    IF NEW.blood_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Blood Type Name: ", NEW.blood_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('blood_type', NEW.blood_type_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `blood_type_trigger_update` AFTER UPDATE ON `blood_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.blood_type_name <> OLD.blood_type_name THEN
        SET audit_log = CONCAT(audit_log, "Blood Type Name: ", OLD.blood_type_name, " -> ", NEW.blood_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('blood_type', NEW.blood_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branch_id` int(10) UNSIGNED NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `city_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(500) DEFAULT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `branch`
--
DELIMITER $$
CREATE TRIGGER `branch_trigger_insert` AFTER INSERT ON `branch` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Branch created. <br/>';

    IF NEW.branch_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Branch Name: ", NEW.branch_name);
    END IF;

    IF NEW.address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Address: ", NEW.address);
    END IF;

    IF NEW.city_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>City ID: ", NEW.city_id);
    END IF;

    IF NEW.phone <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Phone: ", NEW.phone);
    END IF;

    IF NEW.mobile <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mobile: ", NEW.mobile);
    END IF;

    IF NEW.telephone <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Telephone: ", NEW.telephone);
    END IF;

    IF NEW.email <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email: ", NEW.email);
    END IF;

    IF NEW.website <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Website: ", NEW.website);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('branch', NEW.branch_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `branch_trigger_update` AFTER UPDATE ON `branch` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.branch_name <> OLD.branch_name THEN
        SET audit_log = CONCAT(audit_log, "Branch Name: ", OLD.branch_name, " -> ", NEW.branch_name, "<br/>");
    END IF;

    IF NEW.address <> OLD.address THEN
        SET audit_log = CONCAT(audit_log, "Address: ", OLD.address, " -> ", NEW.address, "<br/>");
    END IF;

    IF NEW.city_id <> OLD.city_id THEN
        SET audit_log = CONCAT(audit_log, "City ID: ", OLD.city_id, " -> ", NEW.city_id, "<br/>");
    END IF;

    IF NEW.phone <> OLD.phone THEN
        SET audit_log = CONCAT(audit_log, "Phone: ", OLD.phone, " -> ", NEW.phone, "<br/>");
    END IF;

    IF NEW.mobile <> OLD.mobile THEN
        SET audit_log = CONCAT(audit_log, "Mobile: ", OLD.mobile, " -> ", NEW.mobile, "<br/>");
    END IF;

    IF NEW.telephone <> OLD.telephone THEN
        SET audit_log = CONCAT(audit_log, "Telephone: ", OLD.telephone, " -> ", NEW.telephone, "<br/>");
    END IF;

    IF NEW.email <> OLD.email THEN
        SET audit_log = CONCAT(audit_log, "Email: ", OLD.email, " -> ", NEW.email, "<br/>");
    END IF;

    IF NEW.website <> OLD.website THEN
        SET audit_log = CONCAT(audit_log, "Website: ", OLD.website, " -> ", NEW.website, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('branch', NEW.branch_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(10) UNSIGNED NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `state_id` int(11) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `city_name`, `state_id`, `last_log_by`) VALUES
(1, 'Adams', 2, 0),
(2, 'Bacarra', 2, 0),
(3, 'Badoc', 2, 0),
(4, 'Bangui', 2, 0),
(5, 'City of Batac', 2, 0),
(6, 'Burgos', 2, 0),
(7, 'Carasi', 2, 0),
(8, 'Currimao', 2, 0),
(9, 'Dingras', 2, 0),
(10, 'Dumalneg', 2, 0),
(11, 'Banna', 2, 0),
(12, 'City of Laoag', 2, 0),
(13, 'Marcos', 2, 0),
(14, 'Nueva Era', 2, 0),
(15, 'Pagudpud', 2, 0),
(16, 'Paoay', 2, 0),
(17, 'Pasuquin', 2, 0),
(18, 'Piddig', 2, 0),
(19, 'Pinili', 2, 0),
(20, 'San Nicolas', 2, 0),
(21, 'Sarrat', 2, 0),
(22, 'Solsona', 2, 0),
(23, 'Vintar', 2, 0),
(24, 'Alilem', 3, 0),
(25, 'Banayoyo', 3, 0),
(26, 'Bantay', 3, 0),
(27, 'Burgos', 3, 0),
(28, 'Cabugao', 3, 0),
(29, 'City of Candon', 3, 0),
(30, 'Caoayan', 3, 0),
(31, 'Cervantes', 3, 0),
(32, 'Galimuyod', 3, 0),
(33, 'Gregorio del Pilar', 3, 0),
(34, 'Lidlidda', 3, 0),
(35, 'Magsingal', 3, 0),
(36, 'Nagbukel', 3, 0),
(37, 'Narvacan', 3, 0),
(38, 'Quirino', 3, 0),
(39, 'Salcedo', 3, 0),
(40, 'San Emilio', 3, 0),
(41, 'San Esteban', 3, 0),
(42, 'San Ildefonso', 3, 0),
(43, 'San Juan', 3, 0),
(44, 'San Vicente', 3, 0),
(45, 'Santa', 3, 0),
(46, 'Santa Catalina', 3, 0),
(47, 'Santa Cruz', 3, 0),
(48, 'Santa Lucia', 3, 0),
(49, 'Santa Maria', 3, 0),
(50, 'Santiago', 3, 0),
(51, 'Santo Domingo', 3, 0),
(52, 'Sigay', 3, 0),
(53, 'Sinait', 3, 0),
(54, 'Sugpon', 3, 0),
(55, 'Suyo', 3, 0),
(56, 'Tagudin', 3, 0),
(57, 'City of Vigan', 3, 0),
(58, 'Agoo', 4, 0),
(59, 'Aringay', 4, 0),
(60, 'Bacnotan', 4, 0),
(61, 'Bagulin', 4, 0),
(62, 'Balaoan', 4, 0),
(63, 'Bangar', 4, 0),
(64, 'Bauang', 4, 0),
(65, 'Burgos', 4, 0),
(66, 'Caba', 4, 0),
(67, 'Luna', 4, 0),
(68, 'Naguilian', 4, 0),
(69, 'Pugo', 4, 0),
(70, 'Rosario', 4, 0),
(71, 'City of San Fernando', 4, 0),
(72, 'San Gabriel', 4, 0),
(73, 'San Juan', 4, 0),
(74, 'Santo Tomas', 4, 0),
(75, 'Santol', 4, 0),
(76, 'Sudipen', 4, 0),
(77, 'Tubao', 4, 0),
(78, 'Agno', 5, 0),
(79, 'Aguilar', 5, 0),
(80, 'City of Alaminos', 5, 0),
(81, 'Alcala', 5, 0),
(82, 'Anda', 5, 0),
(83, 'Asingan', 5, 0),
(84, 'Balungao', 5, 0),
(85, 'Bani', 5, 0),
(86, 'Basista', 5, 0),
(87, 'Bautista', 5, 0),
(88, 'Bayambang', 5, 0),
(89, 'Binalonan', 5, 0),
(90, 'Binmaley', 5, 0),
(91, 'Bolinao', 5, 0),
(92, 'Bugallon', 5, 0),
(93, 'Burgos', 5, 0),
(94, 'Calasiao', 5, 0),
(95, 'City of Dagupan', 5, 0),
(96, 'Dasol', 5, 0),
(97, 'Infanta', 5, 0),
(98, 'Labrador', 5, 0),
(99, 'Lingayen', 5, 0),
(100, 'Mabini', 5, 0),
(101, 'Malasiqui', 5, 0),
(102, 'Manaoag', 5, 0),
(103, 'Mangaldan', 5, 0),
(104, 'Mangatarem', 5, 0),
(105, 'Mapandan', 5, 0),
(106, 'Natividad', 5, 0),
(107, 'Pozorrubio', 5, 0),
(108, 'Rosales', 5, 0),
(109, 'City of San Carlos', 5, 0),
(110, 'San Fabian', 5, 0),
(111, 'San Jacinto', 5, 0),
(112, 'San Manuel', 5, 0),
(113, 'San Nicolas', 5, 0),
(114, 'San Quintin', 5, 0),
(115, 'Santa Barbara', 5, 0),
(116, 'Santa Maria', 5, 0),
(117, 'Santo Tomas', 5, 0),
(118, 'Sison', 5, 0),
(119, 'Sual', 5, 0),
(120, 'Tayug', 5, 0),
(121, 'Umingan', 5, 0),
(122, 'Urbiztondo', 5, 0),
(123, 'City of Urdaneta', 5, 0),
(124, 'Villasis', 5, 0),
(125, 'Laoac', 5, 0),
(126, 'Basco', 6, 0),
(127, 'Itbayat', 6, 0),
(128, 'Ivana', 6, 0),
(129, 'Mahatao', 6, 0),
(130, 'Sabtang', 6, 0),
(131, 'Uyugan', 6, 0),
(132, 'Abulug', 7, 0),
(133, 'Alcala', 7, 0),
(134, 'Allacapan', 7, 0),
(135, 'Amulung', 7, 0),
(136, 'Aparri', 7, 0),
(137, 'Baggao', 7, 0),
(138, 'Ballesteros', 7, 0),
(139, 'Buguey', 7, 0),
(140, 'Calayan', 7, 0),
(141, 'Camalaniugan', 7, 0),
(142, 'Claveria', 7, 0),
(143, 'Enrile', 7, 0),
(144, 'Gattaran', 7, 0),
(145, 'Gonzaga', 7, 0),
(146, 'Iguig', 7, 0),
(147, 'Lal-Lo', 7, 0),
(148, 'Lasam', 7, 0),
(149, 'Pamplona', 7, 0),
(150, 'Peñablanca', 7, 0),
(151, 'Piat', 7, 0),
(152, 'Rizal', 7, 0),
(153, 'Sanchez-Mira', 7, 0),
(154, 'Santa Ana', 7, 0),
(155, 'Santa Praxedes', 7, 0),
(156, 'Santa Teresita', 7, 0),
(157, 'Santo Niño', 7, 0),
(158, 'Solana', 7, 0),
(159, 'Tuao', 7, 0),
(160, 'Tuguegarao City', 7, 0),
(161, 'Alicia', 8, 0),
(162, 'Angadanan', 8, 0),
(163, 'Aurora', 8, 0),
(164, 'Benito Soliven', 8, 0),
(165, 'Burgos', 8, 0),
(166, 'Cabagan', 8, 0),
(167, 'Cabatuan', 8, 0),
(168, 'City of Cauayan', 8, 0),
(169, 'Cordon', 8, 0),
(170, 'Dinapigue', 8, 0),
(171, 'Divilacan', 8, 0),
(172, 'Echague', 8, 0),
(173, 'Gamu', 8, 0),
(174, 'City of Ilagan', 8, 0),
(175, 'Jones', 8, 0),
(176, 'Luna', 8, 0),
(177, 'Maconacon', 8, 0),
(178, 'Delfin Albano', 8, 0),
(179, 'Mallig', 8, 0),
(180, 'Naguilian', 8, 0),
(181, 'Palanan', 8, 0),
(182, 'Quezon', 8, 0),
(183, 'Quirino', 8, 0),
(184, 'Ramon', 8, 0),
(185, 'Reina Mercedes', 8, 0),
(186, 'Roxas', 8, 0),
(187, 'San Agustin', 8, 0),
(188, 'San Guillermo', 8, 0),
(189, 'San Isidro', 8, 0),
(190, 'San Manuel', 8, 0),
(191, 'San Mariano', 8, 0),
(192, 'San Mateo', 8, 0),
(193, 'San Pablo', 8, 0),
(194, 'Santa Maria', 8, 0),
(195, 'City of Santiago', 8, 0),
(196, 'Santo Tomas', 8, 0),
(197, 'Tumauini', 8, 0),
(198, 'Ambaguio', 9, 0),
(199, 'Aritao', 9, 0),
(200, 'Bagabag', 9, 0),
(201, 'Bambang', 9, 0),
(202, 'Bayombong', 9, 0),
(203, 'Diadi', 9, 0),
(204, 'Dupax del Norte', 9, 0),
(205, 'Dupax del Sur', 9, 0),
(206, 'Kasibu', 9, 0),
(207, 'Kayapa', 9, 0),
(208, 'Quezon', 9, 0),
(209, 'Santa Fe', 9, 0),
(210, 'Solano', 9, 0),
(211, 'Villaverde', 9, 0),
(212, 'Alfonso Castaneda', 9, 0),
(213, 'Aglipay', 10, 0),
(214, 'Cabarroguis', 10, 0),
(215, 'Diffun', 10, 0),
(216, 'Maddela', 10, 0),
(217, 'Saguday', 10, 0),
(218, 'Nagtipunan', 10, 0),
(219, 'Abucay', 11, 0),
(220, 'Bagac', 11, 0),
(221, 'City of Balanga', 11, 0),
(222, 'Dinalupihan', 11, 0),
(223, 'Hermosa', 11, 0),
(224, 'Limay', 11, 0),
(225, 'Mariveles', 11, 0),
(226, 'Morong', 11, 0),
(227, 'Orani', 11, 0),
(228, 'Orion', 11, 0),
(229, 'Pilar', 11, 0),
(230, 'Samal', 11, 0),
(231, 'Angat', 12, 0),
(232, 'Balagtas', 12, 0),
(233, 'Baliuag', 12, 0),
(234, 'Bocaue', 12, 0),
(235, 'Bulacan', 12, 0),
(236, 'Bustos', 12, 0),
(237, 'Calumpit', 12, 0),
(238, 'Guiguinto', 12, 0),
(239, 'Hagonoy', 12, 0),
(240, 'City of Malolos', 12, 0),
(241, 'Marilao', 12, 0),
(242, 'City of Meycauayan', 12, 0),
(243, 'Norzagaray', 12, 0),
(244, 'Obando', 12, 0),
(245, 'Pandi', 12, 0),
(246, 'Paombong', 12, 0),
(247, 'Plaridel', 12, 0),
(248, 'Pulilan', 12, 0),
(249, 'San Ildefonso', 12, 0),
(250, 'City of San Jose Del Monte', 12, 0),
(251, 'San Miguel', 12, 0),
(252, 'San Rafael', 12, 0),
(253, 'Santa Maria', 12, 0),
(254, 'Doña Remedios Trinidad', 12, 0),
(255, 'Aliaga', 13, 0),
(256, 'Bongabon', 13, 0),
(257, 'City of Cabanatuan', 13, 0),
(258, 'Cabiao', 13, 0),
(259, 'Carranglan', 13, 0),
(260, 'Cuyapo', 13, 0),
(261, 'Gabaldon', 13, 0),
(262, 'City of Gapan', 13, 0),
(263, 'General Mamerto Natividad', 13, 0),
(264, 'General Tinio', 13, 0),
(265, 'Guimba', 13, 0),
(266, 'Jaen', 13, 0),
(267, 'Laur', 13, 0),
(268, 'Licab', 13, 0),
(269, 'Llanera', 13, 0),
(270, 'Lupao', 13, 0),
(271, 'Science City of Muñoz', 13, 0),
(272, 'Nampicuan', 13, 0),
(273, 'City of Palayan', 13, 0),
(274, 'Pantabangan', 13, 0),
(275, 'Peñaranda', 13, 0),
(276, 'Quezon', 13, 0),
(277, 'Rizal', 13, 0),
(278, 'San Antonio', 13, 0),
(279, 'San Isidro', 13, 0),
(280, 'San Jose City', 13, 0),
(281, 'San Leonardo', 13, 0),
(282, 'Santa Rosa', 13, 0),
(283, 'Santo Domingo', 13, 0),
(284, 'Talavera', 13, 0),
(285, 'Talugtug', 13, 0),
(286, 'Zaragoza', 13, 0),
(287, 'City of Angeles', 14, 0),
(288, 'Apalit', 14, 0),
(289, 'Arayat', 14, 0),
(290, 'Bacolor', 14, 0),
(291, 'Candaba', 14, 0),
(292, 'Floridablanca', 14, 0),
(293, 'Guagua', 14, 0),
(294, 'Lubao', 14, 0),
(295, 'Mabalacat City', 14, 0),
(296, 'Macabebe', 14, 0),
(297, 'Magalang', 14, 0),
(298, 'Masantol', 14, 0),
(299, 'Mexico', 14, 0),
(300, 'Minalin', 14, 0),
(301, 'Porac', 14, 0),
(302, 'City of San Fernando', 14, 0),
(303, 'San Luis', 14, 0),
(304, 'San Simon', 14, 0),
(305, 'Santa Ana', 14, 0),
(306, 'Santa Rita', 14, 0),
(307, 'Santo Tomas', 14, 0),
(308, 'Sasmuan', 14, 0),
(309, 'Anao', 15, 0),
(310, 'Bamban', 15, 0),
(311, 'Camiling', 15, 0),
(312, 'Capas', 15, 0),
(313, 'Concepcion', 15, 0),
(314, 'Gerona', 15, 0),
(315, 'La Paz', 15, 0),
(316, 'Mayantoc', 15, 0),
(317, 'Moncada', 15, 0),
(318, 'Paniqui', 15, 0),
(319, 'Pura', 15, 0),
(320, 'Ramos', 15, 0),
(321, 'San Clemente', 15, 0),
(322, 'San Manuel', 15, 0),
(323, 'Santa Ignacia', 15, 0),
(324, 'City of Tarlac', 15, 0),
(325, 'Victoria', 15, 0),
(326, 'San Jose', 15, 0),
(327, 'Botolan', 16, 0),
(328, 'Cabangan', 16, 0),
(329, 'Candelaria', 16, 0),
(330, 'Castillejos', 16, 0),
(331, 'Iba', 16, 0),
(332, 'Masinloc', 16, 0),
(333, 'City of Olongapo', 16, 0),
(334, 'Palauig', 16, 0),
(335, 'San Antonio', 16, 0),
(336, 'San Felipe', 16, 0),
(337, 'San Marcelino', 16, 0),
(338, 'San Narciso', 16, 0),
(339, 'Santa Cruz', 16, 0),
(340, 'Subic', 16, 0),
(341, 'Baler', 17, 0),
(342, 'Casiguran', 17, 0),
(343, 'Dilasag', 17, 0),
(344, 'Dinalungan', 17, 0),
(345, 'Dingalan', 17, 0),
(346, 'Dipaculao', 17, 0),
(347, 'Maria Aurora', 17, 0),
(348, 'San Luis', 17, 0),
(349, 'Agoncillo', 18, 0),
(350, 'Alitagtag', 18, 0),
(351, 'Balayan', 18, 0),
(352, 'Balete', 18, 0),
(353, 'Batangas City', 18, 0),
(354, 'Bauan', 18, 0),
(355, 'Calaca', 18, 0),
(356, 'Calatagan', 18, 0),
(357, 'Cuenca', 18, 0),
(358, 'Ibaan', 18, 0),
(359, 'Laurel', 18, 0),
(360, 'Lemery', 18, 0),
(361, 'Lian', 18, 0),
(362, 'City of Lipa', 18, 0),
(363, 'Lobo', 18, 0),
(364, 'Mabini', 18, 0),
(365, 'Malvar', 18, 0),
(366, 'Mataasnakahoy', 18, 0),
(367, 'Nasugbu', 18, 0),
(368, 'Padre Garcia', 18, 0),
(369, 'Rosario', 18, 0),
(370, 'San Jose', 18, 0),
(371, 'San Juan', 18, 0),
(372, 'San Luis', 18, 0),
(373, 'San Nicolas', 18, 0),
(374, 'San Pascual', 18, 0),
(375, 'Santa Teresita', 18, 0),
(376, 'City of Sto. Tomas', 18, 0),
(377, 'Taal', 18, 0),
(378, 'Talisay', 18, 0),
(379, 'City of Tanauan', 18, 0),
(380, 'Taysan', 18, 0),
(381, 'Tingloy', 18, 0),
(382, 'Tuy', 18, 0),
(383, 'Alfonso', 19, 0),
(384, 'Amadeo', 19, 0),
(385, 'City of Bacoor', 19, 0),
(386, 'Carmona', 19, 0),
(387, 'City of Cavite', 19, 0),
(388, 'City of Dasmariñas', 19, 0),
(389, 'General Emilio Aguinaldo', 19, 0),
(390, 'City of General Trias', 19, 0),
(391, 'City of Imus', 19, 0),
(392, 'Indang', 19, 0),
(393, 'Kawit', 19, 0),
(394, 'Magallanes', 19, 0),
(395, 'Maragondon', 19, 0),
(396, 'Mendez', 19, 0),
(397, 'Naic', 19, 0),
(398, 'Noveleta', 19, 0),
(399, 'Rosario', 19, 0),
(400, 'Silang', 19, 0),
(401, 'City of Tagaytay', 19, 0),
(402, 'Tanza', 19, 0),
(403, 'Ternate', 19, 0),
(404, 'City of Trece Martires', 19, 0),
(405, 'Gen. Mariano Alvarez', 19, 0),
(406, 'Alaminos', 20, 0),
(407, 'Bay', 20, 0),
(408, 'City of Biñan', 20, 0),
(409, 'City of Cabuyao', 20, 0),
(410, 'City of Calamba', 20, 0),
(411, 'Calauan', 20, 0),
(412, 'Cavinti', 20, 0),
(413, 'Famy', 20, 0),
(414, 'Kalayaan', 20, 0),
(415, 'Liliw', 20, 0),
(416, 'Los Baños', 20, 0),
(417, 'Luisiana', 20, 0),
(418, 'Lumban', 20, 0),
(419, 'Mabitac', 20, 0),
(420, 'Magdalena', 20, 0),
(421, 'Majayjay', 20, 0),
(422, 'Nagcarlan', 20, 0),
(423, 'Paete', 20, 0),
(424, 'Pagsanjan', 20, 0),
(425, 'Pakil', 20, 0),
(426, 'Pangil', 20, 0),
(427, 'Pila', 20, 0),
(428, 'Rizal', 20, 0),
(429, 'City of San Pablo', 20, 0),
(430, 'City of San Pedro', 20, 0),
(431, 'Santa Cruz', 20, 0),
(432, 'Santa Maria', 20, 0),
(433, 'City of Santa Rosa', 20, 0),
(434, 'Siniloan', 20, 0),
(435, 'Victoria', 20, 0),
(436, 'Agdangan', 21, 0),
(437, 'Alabat', 21, 0),
(438, 'Atimonan', 21, 0),
(439, 'Buenavista', 21, 0),
(440, 'Burdeos', 21, 0),
(441, 'Calauag', 21, 0),
(442, 'Candelaria', 21, 0),
(443, 'Catanauan', 21, 0),
(444, 'Dolores', 21, 0),
(445, 'General Luna', 21, 0),
(446, 'General Nakar', 21, 0),
(447, 'Guinayangan', 21, 0),
(448, 'Gumaca', 21, 0),
(449, 'Infanta', 21, 0),
(450, 'Jomalig', 21, 0),
(451, 'Lopez', 21, 0),
(452, 'Lucban', 21, 0),
(453, 'City of Lucena', 21, 0),
(454, 'Macalelon', 21, 0),
(455, 'Mauban', 21, 0),
(456, 'Mulanay', 21, 0),
(457, 'Padre Burgos', 21, 0),
(458, 'Pagbilao', 21, 0),
(459, 'Panukulan', 21, 0),
(460, 'Patnanungan', 21, 0),
(461, 'Perez', 21, 0),
(462, 'Pitogo', 21, 0),
(463, 'Plaridel', 21, 0),
(464, 'Polillo', 21, 0),
(465, 'Quezon', 21, 0),
(466, 'Real', 21, 0),
(467, 'Sampaloc', 21, 0),
(468, 'San Andres', 21, 0),
(469, 'San Antonio', 21, 0),
(470, 'San Francisco', 21, 0),
(471, 'San Narciso', 21, 0),
(472, 'Sariaya', 21, 0),
(473, 'Tagkawayan', 21, 0),
(474, 'City of Tayabas', 21, 0),
(475, 'Tiaong', 21, 0),
(476, 'Unisan', 21, 0),
(477, 'Angono', 22, 0),
(478, 'City of Antipolo', 22, 0),
(479, 'Baras', 22, 0),
(480, 'Binangonan', 22, 0),
(481, 'Cainta', 22, 0),
(482, 'Cardona', 22, 0),
(483, 'Jala-Jala', 22, 0),
(484, 'Rodriguez', 22, 0),
(485, 'Morong', 22, 0),
(486, 'Pililla', 22, 0),
(487, 'San Mateo', 22, 0),
(488, 'Tanay', 22, 0),
(489, 'Taytay', 22, 0),
(490, 'Teresa', 22, 0),
(491, 'Boac', 23, 0),
(492, 'Buenavista', 23, 0),
(493, 'Gasan', 23, 0),
(494, 'Mogpog', 23, 0),
(495, 'Santa Cruz', 23, 0),
(496, 'Torrijos', 23, 0),
(497, 'Abra De Ilog', 24, 0),
(498, 'Calintaan', 24, 0),
(499, 'Looc', 24, 0),
(500, 'Lubang', 24, 0),
(501, 'Magsaysay', 24, 0),
(502, 'Mamburao', 24, 0),
(503, 'Paluan', 24, 0),
(504, 'Rizal', 24, 0),
(505, 'Sablayan', 24, 0),
(506, 'San Jose', 24, 0),
(507, 'Santa Cruz', 24, 0),
(508, 'Baco', 25, 0),
(509, 'Bansud', 25, 0),
(510, 'Bongabong', 25, 0),
(511, 'Bulalacao', 25, 0),
(512, 'City of Calapan', 25, 0),
(513, 'Gloria', 25, 0),
(514, 'Mansalay', 25, 0),
(515, 'Naujan', 25, 0),
(516, 'Pinamalayan', 25, 0),
(517, 'Pola', 25, 0),
(518, 'Puerto Galera', 25, 0),
(519, 'Roxas', 25, 0),
(520, 'San Teodoro', 25, 0),
(521, 'Socorro', 25, 0),
(522, 'Victoria', 25, 0),
(523, 'Aborlan', 26, 0),
(524, 'Agutaya', 26, 0),
(525, 'Araceli', 26, 0),
(526, 'Balabac', 26, 0),
(527, 'Bataraza', 26, 0),
(528, 'Brooke S Point', 26, 0),
(529, 'Busuanga', 26, 0),
(530, 'Cagayancillo', 26, 0),
(531, 'Coron', 26, 0),
(532, 'Cuyo', 26, 0),
(533, 'Dumaran', 26, 0),
(534, 'El Nido', 26, 0),
(535, 'Linapacan', 26, 0),
(536, 'Magsaysay', 26, 0),
(537, 'Narra', 26, 0),
(538, 'City of Puerto Princesa', 26, 0),
(539, 'Quezon', 26, 0),
(540, 'Roxas', 26, 0),
(541, 'San Vicente', 26, 0),
(542, 'Taytay', 26, 0),
(543, 'Kalayaan', 26, 0),
(544, 'Culion', 26, 0),
(545, 'Rizal', 26, 0),
(546, 'Sofronio Española', 26, 0),
(547, 'Alcantara', 27, 0),
(548, 'Banton', 27, 0),
(549, 'Cajidiocan', 27, 0),
(550, 'Calatrava', 27, 0),
(551, 'Concepcion', 27, 0),
(552, 'Corcuera', 27, 0),
(553, 'Looc', 27, 0),
(554, 'Magdiwang', 27, 0),
(555, 'Odiongan', 27, 0),
(556, 'Romblon', 27, 0),
(557, 'San Agustin', 27, 0),
(558, 'San Andres', 27, 0),
(559, 'San Fernando', 27, 0),
(560, 'San Jose', 27, 0),
(561, 'Santa Fe', 27, 0),
(562, 'Ferrol', 27, 0),
(563, 'Santa Maria', 27, 0),
(564, 'Bacacay', 28, 0),
(565, 'Camalig', 28, 0),
(566, 'Daraga', 28, 0),
(567, 'Guinobatan', 28, 0),
(568, 'Jovellar', 28, 0),
(569, 'City of Legazpi', 28, 0),
(570, 'Libon', 28, 0),
(571, 'City of Ligao', 28, 0),
(572, 'Malilipot', 28, 0),
(573, 'Malinao', 28, 0),
(574, 'Manito', 28, 0),
(575, 'Oas', 28, 0),
(576, 'Pio Duran', 28, 0),
(577, 'Polangui', 28, 0),
(578, 'Rapu-Rapu', 28, 0),
(579, 'Santo Domingo', 28, 0),
(580, 'City of Tabaco', 28, 0),
(581, 'Tiwi', 28, 0),
(582, 'Basud', 29, 0),
(583, 'Capalonga', 29, 0),
(584, 'Daet', 29, 0),
(585, 'San Lorenzo Ruiz', 29, 0),
(586, 'Jose Panganiban', 29, 0),
(587, 'Labo', 29, 0),
(588, 'Mercedes', 29, 0),
(589, 'Paracale', 29, 0),
(590, 'San Vicente', 29, 0),
(591, 'Santa Elena', 29, 0),
(592, 'Talisay', 29, 0),
(593, 'Vinzons', 29, 0),
(594, 'Baao', 30, 0),
(595, 'Balatan', 30, 0),
(596, 'Bato', 30, 0),
(597, 'Bombon', 30, 0),
(598, 'Buhi', 30, 0),
(599, 'Bula', 30, 0),
(600, 'Cabusao', 30, 0),
(601, 'Calabanga', 30, 0),
(602, 'Camaligan', 30, 0),
(603, 'Canaman', 30, 0),
(604, 'Caramoan', 30, 0),
(605, 'Del Gallego', 30, 0),
(606, 'Gainza', 30, 0),
(607, 'Garchitorena', 30, 0),
(608, 'Goa', 30, 0),
(609, 'City of Iriga', 30, 0),
(610, 'Lagonoy', 30, 0),
(611, 'Libmanan', 30, 0),
(612, 'Lupi', 30, 0),
(613, 'Magarao', 30, 0),
(614, 'Milaor', 30, 0),
(615, 'Minalabac', 30, 0),
(616, 'Nabua', 30, 0),
(617, 'City of Naga', 30, 0),
(618, 'Ocampo', 30, 0),
(619, 'Pamplona', 30, 0),
(620, 'Pasacao', 30, 0),
(621, 'Pili', 30, 0),
(622, 'Presentacion', 30, 0),
(623, 'Ragay', 30, 0),
(624, 'Sagñay', 30, 0),
(625, 'San Fernando', 30, 0),
(626, 'San Jose', 30, 0),
(627, 'Sipocot', 30, 0),
(628, 'Siruma', 30, 0),
(629, 'Tigaon', 30, 0),
(630, 'Tinambac', 30, 0),
(631, 'Bagamanoc', 31, 0),
(632, 'Baras', 31, 0),
(633, 'Bato', 31, 0),
(634, 'Caramoran', 31, 0),
(635, 'Gigmoto', 31, 0),
(636, 'Pandan', 31, 0),
(637, 'Panganiban', 31, 0),
(638, 'San Andres', 31, 0),
(639, 'San Miguel', 31, 0),
(640, 'Viga', 31, 0),
(641, 'Virac', 31, 0),
(642, 'Aroroy', 32, 0),
(643, 'Baleno', 32, 0),
(644, 'Balud', 32, 0),
(645, 'Batuan', 32, 0),
(646, 'Cataingan', 32, 0),
(647, 'Cawayan', 32, 0),
(648, 'Claveria', 32, 0),
(649, 'Dimasalang', 32, 0),
(650, 'Esperanza', 32, 0),
(651, 'Mandaon', 32, 0),
(652, 'City of Masbate', 32, 0),
(653, 'Milagros', 32, 0),
(654, 'Mobo', 32, 0),
(655, 'Monreal', 32, 0),
(656, 'Palanas', 32, 0),
(657, 'Pio V. Corpuz', 32, 0),
(658, 'Placer', 32, 0),
(659, 'San Fernando', 32, 0),
(660, 'San Jacinto', 32, 0),
(661, 'San Pascual', 32, 0),
(662, 'Uson', 32, 0),
(663, 'Barcelona', 33, 0),
(664, 'Bulan', 33, 0),
(665, 'Bulusan', 33, 0),
(666, 'Casiguran', 33, 0),
(667, 'Castilla', 33, 0),
(668, 'Donsol', 33, 0),
(669, 'Gubat', 33, 0),
(670, 'Irosin', 33, 0),
(671, 'Juban', 33, 0),
(672, 'Magallanes', 33, 0),
(673, 'Matnog', 33, 0),
(674, 'Pilar', 33, 0),
(675, 'Prieto Diaz', 33, 0),
(676, 'Santa Magdalena', 33, 0),
(677, 'City of Sorsogon', 33, 0),
(678, 'Altavas', 34, 0),
(679, 'Balete', 34, 0),
(680, 'Banga', 34, 0),
(681, 'Batan', 34, 0),
(682, 'Buruanga', 34, 0),
(683, 'Ibajay', 34, 0),
(684, 'Kalibo', 34, 0),
(685, 'Lezo', 34, 0),
(686, 'Libacao', 34, 0),
(687, 'Madalag', 34, 0),
(688, 'Makato', 34, 0),
(689, 'Malay', 34, 0),
(690, 'Malinao', 34, 0),
(691, 'Nabas', 34, 0),
(692, 'New Washington', 34, 0),
(693, 'Numancia', 34, 0),
(694, 'Tangalan', 34, 0),
(695, 'Anini-Y', 35, 0),
(696, 'Barbaza', 35, 0),
(697, 'Belison', 35, 0),
(698, 'Bugasong', 35, 0),
(699, 'Caluya', 35, 0),
(700, 'Culasi', 35, 0),
(701, 'Tobias Fornier', 35, 0),
(702, 'Hamtic', 35, 0),
(703, 'Laua-An', 35, 0),
(704, 'Libertad', 35, 0),
(705, 'Pandan', 35, 0),
(706, 'Patnongon', 35, 0),
(707, 'San Jose', 35, 0),
(708, 'San Remigio', 35, 0),
(709, 'Sebaste', 35, 0),
(710, 'Sibalom', 35, 0),
(711, 'Tibiao', 35, 0),
(712, 'Valderrama', 35, 0),
(713, 'Cuartero', 36, 0),
(714, 'Dao', 36, 0),
(715, 'Dumalag', 36, 0),
(716, 'Dumarao', 36, 0),
(717, 'Ivisan', 36, 0),
(718, 'Jamindan', 36, 0),
(719, 'Ma-Ayon', 36, 0),
(720, 'Mambusao', 36, 0),
(721, 'Panay', 36, 0),
(722, 'Panitan', 36, 0),
(723, 'Pilar', 36, 0),
(724, 'Pontevedra', 36, 0),
(725, 'President Roxas', 36, 0),
(726, 'City of Roxas', 36, 0),
(727, 'Sapi-An', 36, 0),
(728, 'Sigma', 36, 0),
(729, 'Tapaz', 36, 0),
(730, 'Ajuy', 37, 0),
(731, 'Alimodian', 37, 0),
(732, 'Anilao', 37, 0),
(733, 'Badiangan', 37, 0),
(734, 'Balasan', 37, 0),
(735, 'Banate', 37, 0),
(736, 'Barotac Nuevo', 37, 0),
(737, 'Barotac Viejo', 37, 0),
(738, 'Batad', 37, 0),
(739, 'Bingawan', 37, 0),
(740, 'Cabatuan', 37, 0),
(741, 'Calinog', 37, 0),
(742, 'Carles', 37, 0),
(743, 'Concepcion', 37, 0),
(744, 'Dingle', 37, 0),
(745, 'Dueñas', 37, 0),
(746, 'Dumangas', 37, 0),
(747, 'Estancia', 37, 0),
(748, 'Guimbal', 37, 0),
(749, 'Igbaras', 37, 0),
(750, 'City of Iloilo', 37, 0),
(751, 'Janiuay', 37, 0),
(752, 'Lambunao', 37, 0),
(753, 'Leganes', 37, 0),
(754, 'Lemery', 37, 0),
(755, 'Leon', 37, 0),
(756, 'Maasin', 37, 0),
(757, 'Miagao', 37, 0),
(758, 'Mina', 37, 0),
(759, 'New Lucena', 37, 0),
(760, 'Oton', 37, 0),
(761, 'City of Passi', 37, 0),
(762, 'Pavia', 37, 0),
(763, 'Pototan', 37, 0),
(764, 'San Dionisio', 37, 0),
(765, 'San Enrique', 37, 0),
(766, 'San Joaquin', 37, 0),
(767, 'San Miguel', 37, 0),
(768, 'San Rafael', 37, 0),
(769, 'Santa Barbara', 37, 0),
(770, 'Sara', 37, 0),
(771, 'Tigbauan', 37, 0),
(772, 'Tubungan', 37, 0),
(773, 'Zarraga', 37, 0),
(774, 'City of Bacolod', 38, 0),
(775, 'City of Bago', 38, 0),
(776, 'Binalbagan', 38, 0),
(777, 'City of Cadiz', 38, 0),
(778, 'Calatrava', 38, 0),
(779, 'Candoni', 38, 0),
(780, 'Cauayan', 38, 0),
(781, 'Enrique B. Magalona', 38, 0),
(782, 'City of Escalante', 38, 0),
(783, 'City of Himamaylan', 38, 0),
(784, 'Hinigaran', 38, 0),
(785, 'Hinoba-an', 38, 0),
(786, 'Ilog', 38, 0),
(787, 'Isabela', 38, 0),
(788, 'City of Kabankalan', 38, 0),
(789, 'City of La Carlota', 38, 0),
(790, 'La Castellana', 38, 0),
(791, 'Manapla', 38, 0),
(792, 'Moises Padilla', 38, 0),
(793, 'Murcia', 38, 0),
(794, 'Pontevedra', 38, 0),
(795, 'Pulupandan', 38, 0),
(796, 'City of Sagay', 38, 0),
(797, 'City of San Carlos', 38, 0),
(798, 'San Enrique', 38, 0),
(799, 'City of Silay', 38, 0),
(800, 'City of Sipalay', 38, 0),
(801, 'City of Talisay', 38, 0),
(802, 'Toboso', 38, 0),
(803, 'Valladolid', 38, 0),
(804, 'City of Victorias', 38, 0),
(805, 'Salvador Benedicto', 38, 0),
(806, 'Buenavista', 39, 0),
(807, 'Jordan', 39, 0),
(808, 'Nueva Valencia', 39, 0),
(809, 'San Lorenzo', 39, 0),
(810, 'Sibunag', 39, 0),
(811, 'Alburquerque', 40, 0),
(812, 'Alicia', 40, 0),
(813, 'Anda', 40, 0),
(814, 'Antequera', 40, 0),
(815, 'Baclayon', 40, 0),
(816, 'Balilihan', 40, 0),
(817, 'Batuan', 40, 0),
(818, 'Bilar', 40, 0),
(819, 'Buenavista', 40, 0),
(820, 'Calape', 40, 0),
(821, 'Candijay', 40, 0),
(822, 'Carmen', 40, 0),
(823, 'Catigbian', 40, 0),
(824, 'Clarin', 40, 0),
(825, 'Corella', 40, 0),
(826, 'Cortes', 40, 0),
(827, 'Dagohoy', 40, 0),
(828, 'Danao', 40, 0),
(829, 'Dauis', 40, 0),
(830, 'Dimiao', 40, 0),
(831, 'Duero', 40, 0),
(832, 'Garcia Hernandez', 40, 0),
(833, 'Guindulman', 40, 0),
(834, 'Inabanga', 40, 0),
(835, 'Jagna', 40, 0),
(836, 'Getafe', 40, 0),
(837, 'Lila', 40, 0),
(838, 'Loay', 40, 0),
(839, 'Loboc', 40, 0),
(840, 'Loon', 40, 0),
(841, 'Mabini', 40, 0),
(842, 'Maribojoc', 40, 0),
(843, 'Panglao', 40, 0),
(844, 'Pilar', 40, 0),
(845, 'Pres. Carlos P. Garcia', 40, 0),
(846, 'Sagbayan', 40, 0),
(847, 'San Isidro', 40, 0),
(848, 'San Miguel', 40, 0),
(849, 'Sevilla', 40, 0),
(850, 'Sierra Bullones', 40, 0),
(851, 'Sikatuna', 40, 0),
(852, 'City of Tagbilaran', 40, 0),
(853, 'Talibon', 40, 0),
(854, 'Trinidad', 40, 0),
(855, 'Tubigon', 40, 0),
(856, 'Ubay', 40, 0),
(857, 'Valencia', 40, 0),
(858, 'Bien Unido', 40, 0),
(859, 'Alcantara', 41, 0),
(860, 'Alcoy', 41, 0),
(861, 'Alegria', 41, 0),
(862, 'Aloguinsan', 41, 0),
(863, 'Argao', 41, 0),
(864, 'Asturias', 41, 0),
(865, 'Badian', 41, 0),
(866, 'Balamban', 41, 0),
(867, 'Bantayan', 41, 0),
(868, 'Barili', 41, 0),
(869, 'City of Bogo', 41, 0),
(870, 'Boljoon', 41, 0),
(871, 'Borbon', 41, 0),
(872, 'City of Carcar', 41, 0),
(873, 'Carmen', 41, 0),
(874, 'Catmon', 41, 0),
(875, 'City of Cebu', 41, 0),
(876, 'Compostela', 41, 0),
(877, 'Consolacion', 41, 0),
(878, 'Cordova', 41, 0),
(879, 'Daanbantayan', 41, 0),
(880, 'Dalaguete', 41, 0),
(881, 'Danao City', 41, 0),
(882, 'Dumanjug', 41, 0),
(883, 'Ginatilan', 41, 0),
(884, 'City of Lapu-Lapu', 41, 0),
(885, 'Liloan', 41, 0),
(886, 'Madridejos', 41, 0),
(887, 'Malabuyoc', 41, 0),
(888, 'City of Mandaue', 41, 0),
(889, 'Medellin', 41, 0),
(890, 'Minglanilla', 41, 0),
(891, 'Moalboal', 41, 0),
(892, 'City of Naga', 41, 0),
(893, 'Oslob', 41, 0),
(894, 'Pilar', 41, 0),
(895, 'Pinamungajan', 41, 0),
(896, 'Poro', 41, 0),
(897, 'Ronda', 41, 0),
(898, 'Samboan', 41, 0),
(899, 'San Fernando', 41, 0),
(900, 'San Francisco', 41, 0),
(901, 'San Remigio', 41, 0),
(902, 'Santa Fe', 41, 0),
(903, 'Santander', 41, 0),
(904, 'Sibonga', 41, 0),
(905, 'Sogod', 41, 0),
(906, 'Tabogon', 41, 0),
(907, 'Tabuelan', 41, 0),
(908, 'City of Talisay', 41, 0),
(909, 'City of Toledo', 41, 0),
(910, 'Tuburan', 41, 0),
(911, 'Tudela', 41, 0),
(912, 'Amlan', 42, 0),
(913, 'Ayungon', 42, 0),
(914, 'Bacong', 42, 0),
(915, 'City of Bais', 42, 0),
(916, 'Basay', 42, 0),
(917, 'City of Bayawan', 42, 0),
(918, 'Bindoy', 42, 0),
(919, 'City of Canlaon', 42, 0),
(920, 'Dauin', 42, 0),
(921, 'City of Dumaguete', 42, 0),
(922, 'City of Guihulngan', 42, 0),
(923, 'Jimalalud', 42, 0),
(924, 'La Libertad', 42, 0),
(925, 'Mabinay', 42, 0),
(926, 'Manjuyod', 42, 0),
(927, 'Pamplona', 42, 0),
(928, 'San Jose', 42, 0),
(929, 'Santa Catalina', 42, 0),
(930, 'Siaton', 42, 0),
(931, 'Sibulan', 42, 0),
(932, 'City of Tanjay', 42, 0),
(933, 'Tayasan', 42, 0),
(934, 'Valencia', 42, 0),
(935, 'Vallehermoso', 42, 0),
(936, 'Zamboanguita', 42, 0),
(937, 'Enrique Villanueva', 43, 0),
(938, 'Larena', 43, 0),
(939, 'Lazi', 43, 0),
(940, 'Maria', 43, 0),
(941, 'San Juan', 43, 0),
(942, 'Siquijor', 43, 0),
(943, 'Arteche', 44, 0),
(944, 'Balangiga', 44, 0),
(945, 'Balangkayan', 44, 0),
(946, 'City of Borongan', 44, 0),
(947, 'Can-Avid', 44, 0),
(948, 'Dolores', 44, 0),
(949, 'General Macarthur', 44, 0),
(950, 'Giporlos', 44, 0),
(951, 'Guiuan', 44, 0),
(952, 'Hernani', 44, 0),
(953, 'Jipapad', 44, 0),
(954, 'Lawaan', 44, 0),
(955, 'Llorente', 44, 0),
(956, 'Maslog', 44, 0),
(957, 'Maydolong', 44, 0),
(958, 'Mercedes', 44, 0),
(959, 'Oras', 44, 0),
(960, 'Quinapondan', 44, 0),
(961, 'Salcedo', 44, 0),
(962, 'San Julian', 44, 0),
(963, 'San Policarpo', 44, 0),
(964, 'Sulat', 44, 0),
(965, 'Taft', 44, 0),
(966, 'Abuyog', 45, 0),
(967, 'Alangalang', 45, 0),
(968, 'Albuera', 45, 0),
(969, 'Babatngon', 45, 0),
(970, 'Barugo', 45, 0),
(971, 'Bato', 45, 0),
(972, 'City of Baybay', 45, 0),
(973, 'Burauen', 45, 0),
(974, 'Calubian', 45, 0),
(975, 'Capoocan', 45, 0),
(976, 'Carigara', 45, 0),
(977, 'Dagami', 45, 0),
(978, 'Dulag', 45, 0),
(979, 'Hilongos', 45, 0),
(980, 'Hindang', 45, 0),
(981, 'Inopacan', 45, 0),
(982, 'Isabel', 45, 0),
(983, 'Jaro', 45, 0),
(984, 'Javier', 45, 0),
(985, 'Julita', 45, 0),
(986, 'Kananga', 45, 0),
(987, 'La Paz', 45, 0),
(988, 'Leyte', 45, 0),
(989, 'Macarthur', 45, 0),
(990, 'Mahaplag', 45, 0),
(991, 'Matag-Ob', 45, 0),
(992, 'Matalom', 45, 0),
(993, 'Mayorga', 45, 0),
(994, 'Merida', 45, 0),
(995, 'Ormoc City', 45, 0),
(996, 'Palo', 45, 0),
(997, 'Palompon', 45, 0),
(998, 'Pastrana', 45, 0),
(999, 'San Isidro', 45, 0),
(1000, 'San Miguel', 45, 0),
(1001, 'Santa Fe', 45, 0),
(1002, 'Tabango', 45, 0),
(1003, 'Tabontabon', 45, 0),
(1004, 'City of Tacloban', 45, 0),
(1005, 'Tanauan', 45, 0),
(1006, 'Tolosa', 45, 0),
(1007, 'Tunga', 45, 0),
(1008, 'Villaba', 45, 0),
(1009, 'Allen', 46, 0),
(1010, 'Biri', 46, 0),
(1011, 'Bobon', 46, 0),
(1012, 'Capul', 46, 0),
(1013, 'Catarman', 46, 0),
(1014, 'Catubig', 46, 0),
(1015, 'Gamay', 46, 0),
(1016, 'Laoang', 46, 0),
(1017, 'Lapinig', 46, 0),
(1018, 'Las Navas', 46, 0),
(1019, 'Lavezares', 46, 0),
(1020, 'Mapanas', 46, 0),
(1021, 'Mondragon', 46, 0),
(1022, 'Palapag', 46, 0),
(1023, 'Pambujan', 46, 0),
(1024, 'Rosario', 46, 0),
(1025, 'San Antonio', 46, 0),
(1026, 'San Isidro', 46, 0),
(1027, 'San Jose', 46, 0),
(1028, 'San Roque', 46, 0),
(1029, 'San Vicente', 46, 0),
(1030, 'Silvino Lobos', 46, 0),
(1031, 'Victoria', 46, 0),
(1032, 'Lope De Vega', 46, 0),
(1033, 'Almagro', 47, 0),
(1034, 'Basey', 47, 0),
(1035, 'City of Calbayog', 47, 0),
(1036, 'Calbiga', 47, 0),
(1037, 'City of Catbalogan', 47, 0),
(1038, 'Daram', 47, 0),
(1039, 'Gandara', 47, 0),
(1040, 'Hinabangan', 47, 0),
(1041, 'Jiabong', 47, 0),
(1042, 'Marabut', 47, 0),
(1043, 'Matuguinao', 47, 0),
(1044, 'Motiong', 47, 0),
(1045, 'Pinabacdao', 47, 0),
(1046, 'San Jose De Buan', 47, 0),
(1047, 'San Sebastian', 47, 0),
(1048, 'Santa Margarita', 47, 0),
(1049, 'Santa Rita', 47, 0),
(1050, 'Santo Niño', 47, 0),
(1051, 'Talalora', 47, 0),
(1052, 'Tarangnan', 47, 0),
(1053, 'Villareal', 47, 0),
(1054, 'Paranas', 47, 0),
(1055, 'Zumarraga', 47, 0),
(1056, 'Tagapul-An', 47, 0),
(1057, 'San Jorge', 47, 0),
(1058, 'Pagsanghan', 47, 0),
(1059, 'Anahawan', 48, 0),
(1060, 'Bontoc', 48, 0),
(1061, 'Hinunangan', 48, 0),
(1062, 'Hinundayan', 48, 0),
(1063, 'Libagon', 48, 0),
(1064, 'Liloan', 48, 0),
(1065, 'City of Maasin', 48, 0),
(1066, 'Macrohon', 48, 0),
(1067, 'Malitbog', 48, 0),
(1068, 'Padre Burgos', 48, 0),
(1069, 'Pintuyan', 48, 0),
(1070, 'Saint Bernard', 48, 0),
(1071, 'San Francisco', 48, 0),
(1072, 'San Juan', 48, 0),
(1073, 'San Ricardo', 48, 0),
(1074, 'Silago', 48, 0),
(1075, 'Sogod', 48, 0),
(1076, 'Tomas Oppus', 48, 0),
(1077, 'Limasawa', 48, 0),
(1078, 'Almeria', 49, 0),
(1079, 'Biliran', 49, 0),
(1080, 'Cabucgayan', 49, 0),
(1081, 'Caibiran', 49, 0),
(1082, 'Culaba', 49, 0),
(1083, 'Kawayan', 49, 0),
(1084, 'Maripipi', 49, 0),
(1085, 'Naval', 49, 0),
(1086, 'City of Dapitan', 50, 0),
(1087, 'City of Dipolog', 50, 0),
(1088, 'Katipunan', 50, 0),
(1089, 'La Libertad', 50, 0),
(1090, 'Labason', 50, 0),
(1091, 'Liloy', 50, 0),
(1092, 'Manukan', 50, 0),
(1093, 'Mutia', 50, 0),
(1094, 'Piñan', 50, 0),
(1095, 'Polanco', 50, 0),
(1096, 'Pres. Manuel A. Roxas', 50, 0),
(1097, 'Rizal', 50, 0),
(1098, 'Salug', 50, 0),
(1099, 'Sergio Osmeña Sr.', 50, 0),
(1100, 'Siayan', 50, 0),
(1101, 'Sibuco', 50, 0),
(1102, 'Sibutad', 50, 0),
(1103, 'Sindangan', 50, 0),
(1104, 'Siocon', 50, 0),
(1105, 'Sirawai', 50, 0),
(1106, 'Tampilisan', 50, 0),
(1107, 'Jose Dalman', 50, 0),
(1108, 'Gutalac', 50, 0),
(1109, 'Baliguian', 50, 0),
(1110, 'Godod', 50, 0),
(1111, 'Bacungan', 50, 0),
(1112, 'Kalawit', 50, 0),
(1113, 'Aurora', 51, 0),
(1114, 'Bayog', 51, 0),
(1115, 'Dimataling', 51, 0),
(1116, 'Dinas', 51, 0),
(1117, 'Dumalinao', 51, 0),
(1118, 'Dumingag', 51, 0),
(1119, 'Kumalarang', 51, 0),
(1120, 'Labangan', 51, 0),
(1121, 'Lapuyan', 51, 0),
(1122, 'Mahayag', 51, 0),
(1123, 'Margosatubig', 51, 0),
(1124, 'Midsalip', 51, 0),
(1125, 'Molave', 51, 0),
(1126, 'City of Pagadian', 51, 0),
(1127, 'Ramon Magsaysay', 51, 0),
(1128, 'San Miguel', 51, 0),
(1129, 'San Pablo', 51, 0),
(1130, 'Tabina', 51, 0),
(1131, 'Tambulig', 51, 0),
(1132, 'Tukuran', 51, 0),
(1133, 'City of Zamboanga', 51, 0),
(1134, 'Lakewood', 51, 0),
(1135, 'Josefina', 51, 0),
(1136, 'Pitogo', 51, 0),
(1137, 'Sominot', 51, 0),
(1138, 'Vincenzo A. Sagun', 51, 0),
(1139, 'Guipos', 51, 0),
(1140, 'Tigbao', 51, 0),
(1141, 'Alicia', 52, 0),
(1142, 'Buug', 52, 0),
(1143, 'Diplahan', 52, 0),
(1144, 'Imelda', 52, 0),
(1145, 'Ipil', 52, 0),
(1146, 'Kabasalan', 52, 0),
(1147, 'Mabuhay', 52, 0),
(1148, 'Malangas', 52, 0),
(1149, 'Naga', 52, 0),
(1150, 'Olutanga', 52, 0),
(1151, 'Payao', 52, 0),
(1152, 'Roseller Lim', 52, 0),
(1153, 'Siay', 52, 0),
(1154, 'Talusan', 52, 0),
(1155, 'Titay', 52, 0),
(1156, 'Tungawan', 52, 0),
(1157, 'City of Isabela', 52, 0),
(1158, 'Baungon', 53, 0),
(1159, 'Damulog', 53, 0),
(1160, 'Dangcagan', 53, 0),
(1161, 'Don Carlos', 53, 0),
(1162, 'Impasug-ong', 53, 0),
(1163, 'Kadingilan', 53, 0),
(1164, 'Kalilangan', 53, 0),
(1165, 'Kibawe', 53, 0),
(1166, 'Kitaotao', 53, 0),
(1167, 'Lantapan', 53, 0),
(1168, 'Libona', 53, 0),
(1169, 'City of Malaybalay', 53, 0),
(1170, 'Malitbog', 53, 0),
(1171, 'Manolo Fortich', 53, 0),
(1172, 'Maramag', 53, 0),
(1173, 'Pangantucan', 53, 0),
(1174, 'Quezon', 53, 0),
(1175, 'San Fernando', 53, 0),
(1176, 'Sumilao', 53, 0),
(1177, 'Talakag', 53, 0),
(1178, 'City of Valencia', 53, 0),
(1179, 'Cabanglasan', 53, 0),
(1180, 'Catarman', 54, 0),
(1181, 'Guinsiliban', 54, 0),
(1182, 'Mahinog', 54, 0),
(1183, 'Mambajao', 54, 0),
(1184, 'Sagay', 54, 0),
(1185, 'Bacolod', 55, 0),
(1186, 'Baloi', 55, 0),
(1187, 'Baroy', 55, 0),
(1188, 'City of Iligan', 55, 0),
(1189, 'Kapatagan', 55, 0),
(1190, 'Sultan Naga Dimaporo', 55, 0),
(1191, 'Kauswagan', 55, 0),
(1192, 'Kolambugan', 55, 0),
(1193, 'Lala', 55, 0),
(1194, 'Linamon', 55, 0),
(1195, 'Magsaysay', 55, 0),
(1196, 'Maigo', 55, 0),
(1197, 'Matungao', 55, 0),
(1198, 'Munai', 55, 0),
(1199, 'Nunungan', 55, 0),
(1200, 'Pantao Ragat', 55, 0),
(1201, 'Poona Piagapo', 55, 0),
(1202, 'Salvador', 55, 0),
(1203, 'Sapad', 55, 0),
(1204, 'Tagoloan', 55, 0),
(1205, 'Tangcal', 55, 0),
(1206, 'Tubod', 55, 0),
(1207, 'Pantar', 55, 0),
(1208, 'Aloran', 56, 0),
(1209, 'Baliangao', 56, 0),
(1210, 'Bonifacio', 56, 0),
(1211, 'Calamba', 56, 0),
(1212, 'Clarin', 56, 0),
(1213, 'Concepcion', 56, 0),
(1214, 'Jimenez', 56, 0),
(1215, 'Lopez Jaena', 56, 0),
(1216, 'City of Oroquieta', 56, 0),
(1217, 'City of Ozamiz', 56, 0),
(1218, 'Panaon', 56, 0),
(1219, 'Plaridel', 56, 0),
(1220, 'Sapang Dalaga', 56, 0),
(1221, 'Sinacaban', 56, 0),
(1222, 'City of Tangub', 56, 0),
(1223, 'Tudela', 56, 0),
(1224, 'Don Victoriano Chiongbian', 56, 0),
(1225, 'Alubijid', 57, 0),
(1226, 'Balingasag', 57, 0),
(1227, 'Balingoan', 57, 0),
(1228, 'Binuangan', 57, 0),
(1229, 'City of Cagayan De Oro', 57, 0),
(1230, 'Claveria', 57, 0),
(1231, 'City of El Salvador', 57, 0),
(1232, 'City of Gingoog', 57, 0),
(1233, 'Gitagum', 57, 0),
(1234, 'Initao', 57, 0),
(1235, 'Jasaan', 57, 0),
(1236, 'Kinoguitan', 57, 0),
(1237, 'Lagonglong', 57, 0),
(1238, 'Laguindingan', 57, 0),
(1239, 'Libertad', 57, 0),
(1240, 'Lugait', 57, 0),
(1241, 'Magsaysay', 57, 0),
(1242, 'Manticao', 57, 0),
(1243, 'Medina', 57, 0),
(1244, 'Naawan', 57, 0),
(1245, 'Opol', 57, 0),
(1246, 'Salay', 57, 0),
(1247, 'Sugbongcogon', 57, 0),
(1248, 'Tagoloan', 57, 0),
(1249, 'Talisayan', 57, 0),
(1250, 'Villanueva', 57, 0),
(1251, 'Asuncion', 58, 0),
(1252, 'Carmen', 58, 0),
(1253, 'Kapalong', 58, 0),
(1254, 'New Corella', 58, 0),
(1255, 'City of Panabo', 58, 0),
(1256, 'Island Garden City of Samal', 58, 0),
(1257, 'Santo Tomas', 58, 0),
(1258, 'City of Tagum', 58, 0),
(1259, 'Talaingod', 58, 0),
(1260, 'Braulio E. Dujali', 58, 0),
(1261, 'San Isidro', 58, 0),
(1262, 'Bansalan', 59, 0),
(1263, 'City of Davao', 59, 0),
(1264, 'City of Digos', 59, 0),
(1265, 'Hagonoy', 59, 0),
(1266, 'Kiblawan', 59, 0),
(1267, 'Magsaysay', 59, 0),
(1268, 'Malalag', 59, 0),
(1269, 'Matanao', 59, 0),
(1270, 'Padada', 59, 0),
(1271, 'Santa Cruz', 59, 0),
(1272, 'Sulop', 59, 0),
(1273, 'Baganga', 60, 0),
(1274, 'Banaybanay', 60, 0),
(1275, 'Boston', 60, 0),
(1276, 'Caraga', 60, 0),
(1277, 'Cateel', 60, 0),
(1278, 'Governor Generoso', 60, 0),
(1279, 'Lupon', 60, 0),
(1280, 'Manay', 60, 0),
(1281, 'City of Mati', 60, 0),
(1282, 'San Isidro', 60, 0),
(1283, 'Tarragona', 60, 0),
(1284, 'Compostela', 61, 0),
(1285, 'Laak', 61, 0),
(1286, 'Mabini', 61, 0),
(1287, 'Maco', 61, 0),
(1288, 'Maragusan', 61, 0),
(1289, 'Mawab', 61, 0),
(1290, 'Monkayo', 61, 0),
(1291, 'Montevista', 61, 0),
(1292, 'Nabunturan', 61, 0),
(1293, 'New Bataan', 61, 0),
(1294, 'Pantukan', 61, 0),
(1295, 'Don Marcelino', 62, 0),
(1296, 'Jose Abad Santos', 62, 0),
(1297, 'Malita', 62, 0),
(1298, 'Santa Maria', 62, 0),
(1299, 'Sarangani', 62, 0),
(1300, 'Alamada', 63, 0),
(1301, 'Carmen', 63, 0),
(1302, 'Kabacan', 63, 0),
(1303, 'City of Kidapawan', 63, 0),
(1304, 'Libungan', 63, 0),
(1305, 'Magpet', 63, 0),
(1306, 'Makilala', 63, 0),
(1307, 'Matalam', 63, 0),
(1308, 'Midsayap', 63, 0),
(1309, 'M Lang', 63, 0),
(1310, 'Pigkawayan', 63, 0),
(1311, 'Pikit', 63, 0),
(1312, 'President Roxas', 63, 0),
(1313, 'Tulunan', 63, 0),
(1314, 'Antipas', 63, 0),
(1315, 'Banisilan', 63, 0),
(1316, 'Aleosan', 63, 0),
(1317, 'Arakan', 63, 0),
(1318, 'Banga', 64, 0),
(1319, 'City of General Santos', 64, 0),
(1320, 'City of Koronadal', 64, 0),
(1321, 'Norala', 64, 0),
(1322, 'Polomolok', 64, 0),
(1323, 'Surallah', 64, 0),
(1324, 'Tampakan', 64, 0),
(1325, 'Tantangan', 64, 0),
(1326, 'T Boli', 64, 0),
(1327, 'Tupi', 64, 0),
(1328, 'Santo Niño', 64, 0),
(1329, 'Lake Sebu', 64, 0),
(1330, 'Bagumbayan', 65, 0),
(1331, 'Columbio', 65, 0),
(1332, 'Esperanza', 65, 0),
(1333, 'Isulan', 65, 0),
(1334, 'Kalamansig', 65, 0),
(1335, 'Lebak', 65, 0),
(1336, 'Lutayan', 65, 0),
(1337, 'Lambayong', 65, 0),
(1338, 'Palimbang', 65, 0),
(1339, 'President Quirino', 65, 0),
(1340, 'City of Tacurong', 65, 0),
(1341, 'Sen. Ninoy Aquino', 65, 0),
(1342, 'Alabel', 66, 0),
(1343, 'Glan', 66, 0),
(1344, 'Kiamba', 66, 0),
(1345, 'Maasim', 66, 0),
(1346, 'Maitum', 66, 0),
(1347, 'Malapatan', 66, 0),
(1348, 'Malungon', 66, 0),
(1349, 'Cotabato City', 66, 0),
(1350, 'Manila', 1, 0),
(1351, 'Mandaluyong City', 1, 0),
(1352, 'Marikina City', 1, 0),
(1353, 'Pasig City', 1, 0),
(1354, 'Quezon City', 1, 0),
(1355, 'San Juan City', 1, 0),
(1356, 'Caloocan City', 1, 0),
(1357, 'Malabon City', 1, 0),
(1358, 'Navotas City', 1, 0),
(1359, 'Valenzuela City', 1, 0),
(1360, 'Las Piñas City', 1, 0),
(1361, 'Makati City', 1, 0),
(1362, 'Muntinlupa City', 1, 0),
(1363, 'Parañaque City', 1, 0),
(1364, 'Pasay City', 1, 0),
(1365, 'Pateros', 1, 0),
(1366, 'Taguig City', 1, 0),
(1367, 'Bangued', 67, 0),
(1368, 'Boliney', 67, 0),
(1369, 'Bucay', 67, 0),
(1370, 'Bucloc', 67, 0),
(1371, 'Daguioman', 67, 0),
(1372, 'Danglas', 67, 0),
(1373, 'Dolores', 67, 0),
(1374, 'La Paz', 67, 0),
(1375, 'Lacub', 67, 0),
(1376, 'Lagangilang', 67, 0),
(1377, 'Lagayan', 67, 0),
(1378, 'Langiden', 67, 0),
(1379, 'Licuan-Baay', 67, 0),
(1380, 'Luba', 67, 0),
(1381, 'Malibcong', 67, 0),
(1382, 'Manabo', 67, 0),
(1383, 'Peñarrubia', 67, 0),
(1384, 'Pidigan', 67, 0),
(1385, 'Pilar', 67, 0),
(1386, 'Sallapadan', 67, 0),
(1387, 'San Isidro', 67, 0),
(1388, 'San Juan', 67, 0),
(1389, 'San Quintin', 67, 0),
(1390, 'Tayum', 67, 0),
(1391, 'Tineg', 67, 0),
(1392, 'Tubo', 67, 0),
(1393, 'Villaviciosa', 67, 0),
(1394, 'Atok', 68, 0),
(1395, 'City of Baguio', 68, 0),
(1396, 'Bakun', 68, 0),
(1397, 'Bokod', 68, 0),
(1398, 'Buguias', 68, 0),
(1399, 'Itogon', 68, 0),
(1400, 'Kabayan', 68, 0),
(1401, 'Kapangan', 68, 0),
(1402, 'Kibungan', 68, 0),
(1403, 'La Trinidad', 68, 0),
(1404, 'Mankayan', 68, 0),
(1405, 'Sablan', 68, 0),
(1406, 'Tuba', 68, 0),
(1407, 'Tublay', 68, 0),
(1408, 'Banaue', 69, 0),
(1409, 'Hungduan', 69, 0),
(1410, 'Kiangan', 69, 0),
(1411, 'Lagawe', 69, 0),
(1412, 'Lamut', 69, 0),
(1413, 'Mayoyao', 69, 0),
(1414, 'Alfonso Lista', 69, 0),
(1415, 'Aguinaldo', 69, 0),
(1416, 'Hingyon', 69, 0),
(1417, 'Tinoc', 69, 0),
(1418, 'Asipulo', 69, 0),
(1419, 'Balbalan', 70, 0),
(1420, 'Lubuagan', 70, 0),
(1421, 'Pasil', 70, 0),
(1422, 'Pinukpuk', 70, 0),
(1423, 'Rizal', 70, 0),
(1424, 'City of Tabuk', 70, 0),
(1425, 'Tanudan', 70, 0),
(1426, 'Tinglayan', 70, 0),
(1427, 'Barlig', 71, 0),
(1428, 'Bauko', 71, 0),
(1429, 'Besao', 71, 0),
(1430, 'Bontoc', 71, 0),
(1431, 'Natonin', 71, 0),
(1432, 'Paracelis', 71, 0),
(1433, 'Sabangan', 71, 0),
(1434, 'Sadanga', 71, 0),
(1435, 'Sagada', 71, 0),
(1436, 'Tadian', 71, 0),
(1437, 'Calanasan', 72, 0),
(1438, 'Conner', 72, 0),
(1439, 'Flora', 72, 0),
(1440, 'Kabugao', 72, 0),
(1441, 'Luna', 72, 0),
(1442, 'Pudtol', 72, 0),
(1443, 'Santa Marcela', 72, 0),
(1444, 'City of Lamitan', 73, 0),
(1445, 'Lantawan', 73, 0),
(1446, 'Maluso', 73, 0),
(1447, 'Sumisip', 73, 0),
(1448, 'Tipo-Tipo', 73, 0),
(1449, 'Tuburan', 73, 0),
(1450, 'Akbar', 73, 0),
(1451, 'Al-Barka', 73, 0),
(1452, 'Hadji Mohammad Ajul', 73, 0),
(1453, 'Ungkaya Pukan', 73, 0),
(1454, 'Hadji Muhtamad', 73, 0),
(1455, 'Tabuan-Lasa', 73, 0),
(1456, 'Bacolod-Kalawi', 74, 0),
(1457, 'Balabagan', 74, 0),
(1458, 'Balindong', 74, 0),
(1459, 'Bayang', 74, 0),
(1460, 'Binidayan', 74, 0),
(1461, 'Bubong', 74, 0),
(1462, 'Butig', 74, 0),
(1463, 'Ganassi', 74, 0),
(1464, 'Kapai', 74, 0),
(1465, 'Lumba-Bayabao', 74, 0),
(1466, 'Lumbatan', 74, 0),
(1467, 'Madalum', 74, 0),
(1468, 'Madamba', 74, 0),
(1469, 'Malabang', 74, 0),
(1470, 'Marantao', 74, 0),
(1471, 'City of Marawi', 74, 0),
(1472, 'Masiu', 74, 0),
(1473, 'Mulondo', 74, 0),
(1474, 'Pagayawan', 74, 0),
(1475, 'Piagapo', 74, 0),
(1476, 'Poona Bayabao', 74, 0),
(1477, 'Pualas', 74, 0),
(1478, 'Ditsaan-Ramain', 74, 0),
(1479, 'Saguiaran', 74, 0),
(1480, 'Tamparan', 74, 0),
(1481, 'Taraka', 74, 0),
(1482, 'Tubaran', 74, 0),
(1483, 'Tugaya', 74, 0),
(1484, 'Wao', 74, 0),
(1485, 'Marogong', 74, 0),
(1486, 'Calanogas', 74, 0),
(1487, 'Buadiposo-Buntong', 74, 0),
(1488, 'Maguing', 74, 0),
(1489, 'Picong', 74, 0),
(1490, 'Lumbayanague', 74, 0),
(1491, 'Amai Manabilang', 74, 0),
(1492, 'Tagoloan Ii', 74, 0),
(1493, 'Kapatagan', 74, 0),
(1494, 'Sultan Dumalondong', 74, 0),
(1495, 'Lumbaca-Unayan', 74, 0),
(1496, 'Ampatuan', 75, 0),
(1497, 'Buldon', 75, 0),
(1498, 'Buluan', 75, 0),
(1499, 'Datu Paglas', 75, 0),
(1500, 'Datu Piang', 75, 0),
(1501, 'Datu Odin Sinsuat', 75, 0),
(1502, 'Shariff Aguak', 75, 0),
(1503, 'Matanog', 75, 0),
(1504, 'Pagalungan', 75, 0),
(1505, 'Parang', 75, 0),
(1506, 'Sultan Kudarat', 75, 0),
(1507, 'Sultan Sa Barongis', 75, 0),
(1508, 'Kabuntalan', 75, 0),
(1509, 'Upi', 75, 0),
(1510, 'Talayan', 75, 0),
(1511, 'South Upi', 75, 0),
(1512, 'Barira', 75, 0),
(1513, 'Gen. S.K. Pendatun', 75, 0),
(1514, 'Mamasapano', 75, 0),
(1515, 'Talitay', 75, 0),
(1516, 'Pagagawan', 75, 0),
(1517, 'Paglat', 75, 0),
(1518, 'Sultan Mastura', 75, 0),
(1519, 'Guindulungan', 75, 0),
(1520, 'Datu Saudi-Ampatuan', 75, 0),
(1521, 'Datu Unsay', 75, 0),
(1522, 'Datu Abdullah Sangki', 75, 0),
(1523, 'Rajah Buayan', 75, 0),
(1524, 'Datu Blah T. Sinsuat', 75, 0),
(1525, 'Datu Anggal Midtimbang', 75, 0),
(1526, 'Mangudadatu', 75, 0),
(1527, 'Pandag', 75, 0),
(1528, 'Northern Kabuntalan', 75, 0),
(1529, 'Datu Hoffer Ampatuan', 75, 0),
(1530, 'Datu Salibo', 75, 0),
(1531, 'Shariff Saydona Mustapha', 75, 0),
(1532, 'Indanan', 76, 0),
(1533, 'Jolo', 76, 0),
(1534, 'Kalingalan Caluang', 76, 0),
(1535, 'Luuk', 76, 0),
(1536, 'Maimbung', 76, 0),
(1537, 'Hadji Panglima Tahil', 76, 0),
(1538, 'Old Panamao', 76, 0),
(1539, 'Pangutaran', 76, 0),
(1540, 'Parang', 76, 0),
(1541, 'Pata', 76, 0),
(1542, 'Patikul', 76, 0),
(1543, 'Siasi', 76, 0),
(1544, 'Talipao', 76, 0),
(1545, 'Tapul', 76, 0),
(1546, 'Tongkil', 76, 0),
(1547, 'Panglima Estino', 76, 0),
(1548, 'Lugus', 76, 0),
(1549, 'Pandami', 76, 0),
(1550, 'Omar', 76, 0),
(1551, 'Panglima Sugala', 77, 0),
(1552, 'Bongao (Capital)', 77, 0),
(1553, 'Mapun', 77, 0),
(1554, 'Simunul', 77, 0),
(1555, 'Sitangkai', 77, 0),
(1556, 'South Ubian', 77, 0),
(1557, 'Tandubas', 77, 0),
(1558, 'Turtle Islands', 77, 0),
(1559, 'Languyan', 77, 0),
(1560, 'Sapa-Sapa', 77, 0),
(1561, 'Sibutu', 77, 0),
(1562, 'Buenavista', 78, 0),
(1563, 'City of Butuan', 78, 0),
(1564, 'City of Cabadbaran', 78, 0),
(1565, 'Carmen', 78, 0),
(1566, 'Jabonga', 78, 0),
(1567, 'Kitcharao', 78, 0),
(1568, 'Las Nieves', 78, 0),
(1569, 'Magallanes', 78, 0),
(1570, 'Nasipit', 78, 0),
(1571, 'Santiago', 78, 0),
(1572, 'Tubay', 78, 0),
(1573, 'Remedios T. Romualdez', 78, 0),
(1574, 'City of Bayugan', 79, 0),
(1575, 'Bunawan', 79, 0),
(1576, 'Esperanza', 79, 0),
(1577, 'La Paz', 79, 0),
(1578, 'Loreto', 79, 0),
(1579, 'Prosperidad', 79, 0),
(1580, 'Rosario', 79, 0),
(1581, 'San Francisco', 79, 0),
(1582, 'San Luis', 79, 0),
(1583, 'Santa Josefa', 79, 0),
(1584, 'Talacogon', 79, 0),
(1585, 'Trento', 79, 0),
(1586, 'Veruela', 79, 0),
(1587, 'Sibagat', 79, 0),
(1588, 'Alegria', 80, 0),
(1589, 'Bacuag', 80, 0),
(1590, 'Burgos', 80, 0),
(1591, 'Claver', 80, 0),
(1592, 'Dapa', 80, 0),
(1593, 'Del Carmen', 80, 0),
(1594, 'General Luna', 80, 0),
(1595, 'Gigaquit', 80, 0),
(1596, 'Mainit', 80, 0),
(1597, 'Malimono', 80, 0),
(1598, 'Pilar', 80, 0),
(1599, 'Placer', 80, 0),
(1600, 'San Benito', 80, 0),
(1601, 'San Francisco', 80, 0),
(1602, 'San Isidro', 80, 0),
(1603, 'Santa Monica', 80, 0),
(1604, 'Sison', 80, 0),
(1605, 'Socorro', 80, 0),
(1606, 'City of Surigao', 80, 0),
(1607, 'Tagana-An', 80, 0),
(1608, 'Tubod', 80, 0),
(1609, 'Barobo', 81, 0),
(1610, 'Bayabas', 81, 0),
(1611, 'City of Bislig', 81, 0),
(1612, 'Cagwait', 81, 0),
(1613, 'Cantilan', 81, 0),
(1614, 'Carmen', 81, 0),
(1615, 'Carrascal', 81, 0),
(1616, 'Cortes', 81, 0),
(1617, 'Hinatuan', 81, 0),
(1618, 'Lanuza', 81, 0),
(1619, 'Lianga', 81, 0),
(1620, 'Lingig', 81, 0),
(1621, 'Madrid', 81, 0),
(1622, 'Marihatag', 81, 0),
(1623, 'San Agustin', 81, 0),
(1624, 'San Miguel', 81, 0),
(1625, 'Tagbina', 81, 0),
(1626, 'Tago', 81, 0),
(1627, 'City of Tandag', 81, 0),
(1628, 'Basilisa', 82, 0),
(1629, 'Cagdianao', 82, 0),
(1630, 'Dinagat', 82, 0),
(1631, 'Libjo', 82, 0),
(1632, 'Loreto', 82, 0),
(1633, 'San Jose', 82, 0),
(1634, 'Tubajon', 82, 0);

--
-- Triggers `city`
--
DELIMITER $$
CREATE TRIGGER `city_trigger_insert` AFTER INSERT ON `city` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'City created. <br/>';

    IF NEW.city_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>City Name: ", NEW.city_name);
    END IF;

    IF NEW.state_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>State ID: ", NEW.state_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('city', NEW.city_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `city_trigger_update` AFTER UPDATE ON `city` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.city_name <> OLD.city_name THEN
        SET audit_log = CONCAT(audit_log, "City Name: ", OLD.city_name, " -> ", NEW.city_name, "<br/>");
    END IF;

    IF NEW.state_id <> OLD.state_id THEN
        SET audit_log = CONCAT(audit_log, "State ID: ", OLD.state_id, " -> ", NEW.state_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('city', NEW.city_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `civil_status`
--

CREATE TABLE `civil_status` (
  `civil_status_id` int(10) UNSIGNED NOT NULL,
  `civil_status_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `civil_status`
--
DELIMITER $$
CREATE TRIGGER `civil_status_trigger_insert` AFTER INSERT ON `civil_status` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Civil status created. <br/>';

    IF NEW.civil_status_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Civil Status Name: ", NEW.civil_status_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('civil_status', NEW.civil_status_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `civil_status_trigger_update` AFTER UPDATE ON `civil_status` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.civil_status_name <> OLD.civil_status_name THEN
        SET audit_log = CONCAT(audit_log, "Civil Status Name: ", OLD.civil_status_name, " -> ", NEW.civil_status_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('civil_status', NEW.civil_status_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_logo` varchar(500) DEFAULT NULL,
  `address` varchar(1000) NOT NULL,
  `city_id` int(11) NOT NULL,
  `tax_id` varchar(500) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(500) DEFAULT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `company`
--
DELIMITER $$
CREATE TRIGGER `company_trigger_insert` AFTER INSERT ON `company` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Company created. <br/>';

    IF NEW.company_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Company Name: ", NEW.company_name);
    END IF;

    IF NEW.address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Address: ", NEW.address);
    END IF;

    IF NEW.city_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>City ID: ", NEW.city_id);
    END IF;

    IF NEW.tax_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Tax ID: ", NEW.tax_id);
    END IF;

    IF NEW.company_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Currency ID: ", NEW.company_id);
    END IF;

    IF NEW.phone <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Phone: ", NEW.phone);
    END IF;

    IF NEW.mobile <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mobile: ", NEW.mobile);
    END IF;

    IF NEW.telephone <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Telephone: ", NEW.telephone);
    END IF;

    IF NEW.email <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email: ", NEW.email);
    END IF;

    IF NEW.website <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Website: ", NEW.website);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('company', NEW.company_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `company_trigger_update` AFTER UPDATE ON `company` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.company_name <> OLD.company_name THEN
        SET audit_log = CONCAT(audit_log, "Company Name: ", OLD.company_name, " -> ", NEW.company_name, "<br/>");
    END IF;

    IF NEW.address <> OLD.address THEN
        SET audit_log = CONCAT(audit_log, "Address: ", OLD.address, " -> ", NEW.address, "<br/>");
    END IF;

    IF NEW.city_id <> OLD.city_id THEN
        SET audit_log = CONCAT(audit_log, "City ID: ", OLD.city_id, " -> ", NEW.city_id, "<br/>");
    END IF;

    IF NEW.tax_id <> OLD.tax_id THEN
        SET audit_log = CONCAT(audit_log, "Tax ID: ", OLD.tax_id, " -> ", NEW.tax_id, "<br/>");
    END IF;

    IF NEW.currency_id <> OLD.currency_id THEN
        SET audit_log = CONCAT(audit_log, "Currency ID: ", OLD.currency_id, " -> ", NEW.currency_id, "<br/>");
    END IF;

    IF NEW.phone <> OLD.phone THEN
        SET audit_log = CONCAT(audit_log, "Phone: ", OLD.phone, " -> ", NEW.phone, "<br/>");
    END IF;

    IF NEW.mobile <> OLD.mobile THEN
        SET audit_log = CONCAT(audit_log, "Mobile: ", OLD.mobile, " -> ", NEW.mobile, "<br/>");
    END IF;

    IF NEW.telephone <> OLD.telephone THEN
        SET audit_log = CONCAT(audit_log, "Telephone: ", OLD.telephone, " -> ", NEW.telephone, "<br/>");
    END IF;

    IF NEW.email <> OLD.email THEN
        SET audit_log = CONCAT(audit_log, "Email: ", OLD.email, " -> ", NEW.email, "<br/>");
    END IF;

    IF NEW.website <> OLD.website THEN
        SET audit_log = CONCAT(audit_log, "Website: ", OLD.website, " -> ", NEW.website, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('company', NEW.company_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `country_id` int(10) UNSIGNED NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `country_code` varchar(5) DEFAULT NULL,
  `phone_code` varchar(20) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `country_name`, `country_code`, `phone_code`, `last_log_by`) VALUES
(1, 'Afghanistan', 'AFG', '93', 0),
(2, 'Aland Islands', 'ALA', '340', 0),
(3, 'Albania', 'ALB', '355', 0),
(4, 'Algeria', 'DZA', '213', 0),
(5, 'American Samoa', 'ASM', '-683', 0),
(6, 'Andorra', 'AND', '376', 0),
(7, 'Angola', 'AGO', '244', 0),
(8, 'Anguilla', 'AIA', '-263', 0),
(9, 'Antarctica', 'ATA', '672', 0),
(10, 'Antigua And Barbuda', 'ATG', '-267', 0),
(11, 'Argentina', 'ARG', '54', 0),
(12, 'Armenia', 'ARM', '374', 0),
(13, 'Aruba', 'ABW', '297', 0),
(14, 'Australia', 'AUS', '61', 0),
(15, 'Austria', 'AUT', '43', 0),
(16, 'Azerbaijan', 'AZE', '994', 0),
(17, 'Bahrain', 'BHR', '973', 0),
(18, 'Bangladesh', 'BGD', '880', 0),
(19, 'Barbados', 'BRB', '-245', 0),
(20, 'Belarus', 'BLR', '375', 0),
(21, 'Belgium', 'BEL', '32', 0),
(22, 'Belize', 'BLZ', '501', 0),
(23, 'Benin', 'BEN', '229', 0),
(24, 'Bermuda', 'BMU', '-440', 0),
(25, 'Bhutan', 'BTN', '975', 0),
(26, 'Bolivia', 'BOL', '591', 0),
(27, 'Bonaire, Sint Eustatius and Saba', 'BES', '599', 0),
(28, 'Bosnia and Herzegovina', 'BIH', '387', 0),
(29, 'Botswana', 'BWA', '267', 0),
(30, 'Bouvet Island', 'BVT', '55', 0),
(31, 'Brazil', 'BRA', '55', 0),
(32, 'British Indian Ocean Territory', 'IOT', '246', 0),
(33, 'Brunei', 'BRN', '673', 0),
(34, 'Bulgaria', 'BGR', '359', 0),
(35, 'Burkina Faso', 'BFA', '226', 0),
(36, 'Burundi', 'BDI', '257', 0),
(37, 'Cambodia', 'KHM', '855', 0),
(38, 'Cameroon', 'CMR', '237', 0),
(39, 'Canada', 'CAN', '1', 0),
(40, 'Cape Verde', 'CPV', '238', 0),
(41, 'Cayman Islands', 'CYM', '-344', 0),
(42, 'Central African Republic', 'CAF', '236', 0),
(43, 'Chad', 'TCD', '235', 0),
(44, 'Chile', 'CHL', '56', 0),
(45, 'China', 'CHN', '86', 0),
(46, 'Christmas Island', 'CXR', '61', 0),
(47, 'Cocos (Keeling) Islands', 'CCK', '61', 0),
(48, 'Colombia', 'COL', '57', 0),
(49, 'Comoros', 'COM', '269', 0),
(50, 'Congo', 'COG', '242', 0),
(51, 'Cook Islands', 'COK', '682', 0),
(52, 'Costa Rica', 'CRI', '506', 0),
(53, 'Cote D Ivoire (Ivory Coast)', 'CIV', '225', 0),
(54, 'Croatia', 'HRV', '385', 0),
(55, 'Cuba', 'CUB', '53', 0),
(56, 'CuraÃ§ao', 'CUW', '599', 0),
(57, 'Cyprus', 'CYP', '357', 0),
(58, 'Czech Republic', 'CZE', '420', 0),
(59, 'Democratic Republic of the Congo', 'COD', '243', 0),
(60, 'Denmark', 'DNK', '45', 0),
(61, 'Djibouti', 'DJI', '253', 0),
(62, 'Dominica', 'DMA', '-766', 0),
(63, 'Dominican Republic', 'DOM', '+1-809 and 1-829', 0),
(64, 'East Timor', 'TLS', '670', 0),
(65, 'Ecuador', 'ECU', '593', 0),
(66, 'Egypt', 'EGY', '20', 0),
(67, 'El Salvador', 'SLV', '503', 0),
(68, 'Equatorial Guinea', 'GNQ', '240', 0),
(69, 'Eritrea', 'ERI', '291', 0),
(70, 'Estonia', 'EST', '372', 0),
(71, 'Ethiopia', 'ETH', '251', 0),
(72, 'Falkland Islands', 'FLK', '500', 0),
(73, 'Faroe Islands', 'FRO', '298', 0),
(74, 'Fiji Islands', 'FJI', '679', 0),
(75, 'Finland', 'FIN', '358', 0),
(76, 'France', 'FRA', '33', 0),
(77, 'French Guiana', 'GUF', '594', 0),
(78, 'French Polynesia', 'PYF', '689', 0),
(79, 'French Southern Territories', 'ATF', '262', 0),
(80, 'Gabon', 'GAB', '241', 0),
(81, 'Gambia The', 'GMB', '220', 0),
(82, 'Georgia', 'GEO', '995', 0),
(83, 'Germany', 'DEU', '49', 0),
(84, 'Ghana', 'GHA', '233', 0),
(85, 'Gibraltar', 'GIB', '350', 0),
(86, 'Greece', 'GRC', '30', 0),
(87, 'Greenland', 'GRL', '299', 0),
(88, 'Grenada', 'GRD', '-472', 0),
(89, 'Guadeloupe', 'GLP', '590', 0),
(90, 'Guam', 'GUM', '-670', 0),
(91, 'Guatemala', 'GTM', '502', 0),
(92, 'Guernsey and Alderney', 'GGY', '-1437', 0),
(93, 'Guinea', 'GIN', '224', 0),
(94, 'Guinea-Bissau', 'GNB', '245', 0),
(95, 'Guyana', 'GUY', '592', 0),
(96, 'Haiti', 'HTI', '509', 0),
(97, 'Heard Island and McDonald Islands', 'HMD', '672', 0),
(98, 'Honduras', 'HND', '504', 0),
(99, 'Hong Kong S.A.R.', 'HKG', '852', 0),
(100, 'Hungary', 'HUN', '36', 0),
(101, 'Iceland', 'ISL', '354', 0),
(102, 'India', 'IND', '91', 0),
(103, 'Indonesia', 'IDN', '62', 0),
(104, 'Iran', 'IRN', '98', 0),
(105, 'Iraq', 'IRQ', '964', 0),
(106, 'Ireland', 'IRL', '353', 0),
(107, 'Israel', 'ISR', '972', 0),
(108, 'Italy', 'ITA', '39', 0),
(109, 'Jamaica', 'JAM', '-875', 0),
(110, 'Japan', 'JPN', '81', 0),
(111, 'Jersey', 'JEY', '-1490', 0),
(112, 'Jordan', 'JOR', '962', 0),
(113, 'Kazakhstan', 'KAZ', '7', 0),
(114, 'Kenya', 'KEN', '254', 0),
(115, 'Kiribati', 'KIR', '686', 0),
(116, 'Kosovo', 'XKX', '383', 0),
(117, 'Kuwait', 'KWT', '965', 0),
(118, 'Kyrgyzstan', 'KGZ', '996', 0),
(119, 'Laos', 'LAO', '856', 0),
(120, 'Latvia', 'LVA', '371', 0),
(121, 'Lebanon', 'LBN', '961', 0),
(122, 'Lesotho', 'LSO', '266', 0),
(123, 'Liberia', 'LBR', '231', 0),
(124, 'Libya', 'LBY', '218', 0),
(125, 'Liechtenstein', 'LIE', '423', 0),
(126, 'Lithuania', 'LTU', '370', 0),
(127, 'Luxembourg', 'LUX', '352', 0),
(128, 'Macau S.A.R.', 'MAC', '853', 0),
(129, 'Madagascar', 'MDG', '261', 0),
(130, 'Malawi', 'MWI', '265', 0),
(131, 'Malaysia', 'MYS', '60', 0),
(132, 'Maldives', 'MDV', '960', 0),
(133, 'Mali', 'MLI', '223', 0),
(134, 'Malta', 'MLT', '356', 0),
(135, 'Man (Isle of)', 'IMN', '-1580', 0),
(136, 'Marshall Islands', 'MHL', '692', 0),
(137, 'Martinique', 'MTQ', '596', 0),
(138, 'Mauritania', 'MRT', '222', 0),
(139, 'Mauritius', 'MUS', '230', 0),
(140, 'Mayotte', 'MYT', '262', 0),
(141, 'Mexico', 'MEX', '52', 0),
(142, 'Micronesia', 'FSM', '691', 0),
(143, 'Moldova', 'MDA', '373', 0),
(144, 'Monaco', 'MCO', '377', 0),
(145, 'Mongolia', 'MNG', '976', 0),
(146, 'Montenegro', 'MNE', '382', 0),
(147, 'Montserrat', 'MSR', '-663', 0),
(148, 'Morocco', 'MAR', '212', 0),
(149, 'Mozambique', 'MOZ', '258', 0),
(150, 'Myanmar', 'MMR', '95', 0),
(151, 'Namibia', 'NAM', '264', 0),
(152, 'Nauru', 'NRU', '674', 0),
(153, 'Nepal', 'NPL', '977', 0),
(154, 'Netherlands', 'NLD', '31', 0),
(155, 'New Caledonia', 'NCL', '687', 0),
(156, 'New Zealand', 'NZL', '64', 0),
(157, 'Nicaragua', 'NIC', '505', 0),
(158, 'Niger', 'NER', '227', 0),
(159, 'Nigeria', 'NGA', '234', 0),
(160, 'Niue', 'NIU', '683', 0),
(161, 'Norfolk Island', 'NFK', '672', 0),
(162, 'North Korea', 'PRK', '850', 0),
(163, 'North Macedonia', 'MKD', '389', 0),
(164, 'Northern Mariana Islands', 'MNP', '-669', 0),
(165, 'Norway', 'NOR', '47', 0),
(166, 'Oman', 'OMN', '968', 0),
(167, 'Pakistan', 'PAK', '92', 0),
(168, 'Palau', 'PLW', '680', 0),
(169, 'Palestinian Territory Occupied', 'PSE', '970', 0),
(170, 'Panama', 'PAN', '507', 0),
(171, 'Papua new Guinea', 'PNG', '675', 0),
(172, 'Paraguay', 'PRY', '595', 0),
(173, 'Peru', 'PER', '51', 0),
(174, 'Philippines', 'PHL', '63', 0),
(175, 'Pitcairn Island', 'PCN', '870', 0),
(176, 'Poland', 'POL', '48', 0),
(177, 'Portugal', 'PRT', '351', 0),
(178, 'Puerto Rico', 'PRI', '+1-787 and 1-939', 0),
(179, 'Qatar', 'QAT', '974', 0),
(180, 'Reunion', 'REU', '262', 0),
(181, 'Romania', 'ROU', '40', 0),
(182, 'Russia', 'RUS', '7', 0),
(183, 'Rwanda', 'RWA', '250', 0),
(184, 'Saint Helena', 'SHN', '290', 0),
(185, 'Saint Kitts And Nevis', 'KNA', '-868', 0),
(186, 'Saint Lucia', 'LCA', '-757', 0),
(187, 'Saint Pierre and Miquelon', 'SPM', '508', 0),
(188, 'Saint Vincent And The Grenadines', 'VCT', '-783', 0),
(189, 'Saint-Barthelemy', 'BLM', '590', 0),
(190, 'Saint-Martin (French part)', 'MAF', '590', 0),
(191, 'Samoa', 'WSM', '685', 0),
(192, 'San Marino', 'SMR', '378', 0),
(193, 'Sao Tome and Principe', 'STP', '239', 0),
(194, 'Saudi Arabia', 'SAU', '966', 0),
(195, 'Senegal', 'SEN', '221', 0),
(196, 'Serbia', 'SRB', '381', 0),
(197, 'Seychelles', 'SYC', '248', 0),
(198, 'Sierra Leone', 'SLE', '232', 0),
(199, 'Singapore', 'SGP', '65', 0),
(200, 'Sint Maarten (Dutch part)', 'SXM', '1721', 0),
(201, 'Slovakia', 'SVK', '421', 0),
(202, 'Slovenia', 'SVN', '386', 0),
(203, 'Solomon Islands', 'SLB', '677', 0),
(204, 'Somalia', 'SOM', '252', 0),
(205, 'South Africa', 'ZAF', '27', 0),
(206, 'South Georgia', 'SGS', '500', 0),
(207, 'South Korea', 'KOR', '82', 0),
(208, 'South Sudan', 'SSD', '211', 0),
(209, 'Spain', 'ESP', '34', 0),
(210, 'Sri Lanka', 'LKA', '94', 0),
(211, 'Sudan', 'SDN', '249', 0),
(212, 'Suriname', 'SUR', '597', 0),
(213, 'Svalbard And Jan Mayen Islands', 'SJM', '47', 0),
(214, 'Swaziland', 'SWZ', '268', 0),
(215, 'Sweden', 'SWE', '46', 0),
(216, 'Switzerland', 'CHE', '41', 0),
(217, 'Syria', 'SYR', '963', 0),
(218, 'Taiwan', 'TWN', '886', 0),
(219, 'Tajikistan', 'TJK', '992', 0),
(220, 'Tanzania', 'TZA', '255', 0),
(221, 'Thailand', 'THA', '66', 0),
(222, 'The Bahamas', 'BHS', '-241', 0),
(223, 'Togo', 'TGO', '228', 0),
(224, 'Tokelau', 'TKL', '690', 0),
(225, 'Tonga', 'TON', '676', 0),
(226, 'Trinidad And Tobago', 'TTO', '-867', 0),
(227, 'Tunisia', 'TUN', '216', 0),
(228, 'Turkey', 'TUR', '90', 0),
(229, 'Turkmenistan', 'TKM', '993', 0),
(230, 'Turks And Caicos Islands', 'TCA', '-648', 0),
(231, 'Tuvalu', 'TUV', '688', 0),
(232, 'Uganda', 'UGA', '256', 0),
(233, 'Ukraine', 'UKR', '380', 0),
(234, 'United Arab Emirates', 'ARE', '971', 0),
(235, 'United Kingdom', 'GBR', '44', 0),
(236, 'United States', 'USA', '1', 0),
(237, 'United States Minor Outlying Islands', 'UMI', '1', 0),
(238, 'Uruguay', 'URY', '598', 0),
(239, 'Uzbekistan', 'UZB', '998', 0),
(240, 'Vanuatu', 'VUT', '678', 0),
(241, 'Vatican City State (Holy See)', 'VAT', '379', 0),
(242, 'Venezuela', 'VEN', '58', 0),
(243, 'Vietnam', 'VNM', '84', 0),
(244, 'Virgin Islands (British)', 'VGB', '-283', 0),
(245, 'Virgin Islands (US)', 'VIR', '-339', 0),
(246, 'Wallis And Futuna Islands', 'WLF', '681', 0),
(247, 'Western Sahara', 'ESH', '212', 0),
(248, 'Yemen', 'YEM', '967', 0),
(249, 'Zambia', 'ZMB', '260', 0),
(250, 'Zimbabwe', 'ZWE', '263', 0);

--
-- Triggers `country`
--
DELIMITER $$
CREATE TRIGGER `country_trigger_insert` AFTER INSERT ON `country` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Country created. <br/>';

    IF NEW.country_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Country Name: ", NEW.country_name);
    END IF;

    IF NEW.country_code <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Country Code: ", NEW.country_code);
    END IF;

    IF NEW.phone_code <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Phone Code: ", NEW.phone_code);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('country', NEW.country_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `country_trigger_update` AFTER UPDATE ON `country` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.country_name <> OLD.country_name THEN
        SET audit_log = CONCAT(audit_log, "Country Name: ", OLD.country_name, " -> ", NEW.country_name, "<br/>");
    END IF;

    IF NEW.country_code <> OLD.country_code THEN
        SET audit_log = CONCAT(audit_log, "Country Code: ", OLD.country_code, " -> ", NEW.country_code, "<br/>");
    END IF;

    IF NEW.phone_code <> OLD.phone_code THEN
        SET audit_log = CONCAT(audit_log, "Phone Code: ", OLD.phone_code, " -> ", NEW.phone_code, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('country', NEW.country_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_id` int(10) UNSIGNED NOT NULL,
  `currency_name` varchar(100) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `shorthand` varchar(10) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_id`, `currency_name`, `symbol`, `shorthand`, `last_log_by`) VALUES
(1, 'Philippine Peso', '₱', 'PHP', 0),
(2, 'United States Dollar', '$', 'USD', 0),
(3, 'Japanese Yen', '¥', 'JPY', 0),
(4, 'South Korean Won', '₩', 'KRW', 0),
(5, 'Euro', '€', 'EUR', 0),
(6, 'Pound Sterling', '£', 'GBP', 0);

--
-- Triggers `currency`
--
DELIMITER $$
CREATE TRIGGER `currency_trigger_insert` AFTER INSERT ON `currency` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Currency created. <br/>';

    IF NEW.currency_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Currency Name: ", NEW.currency_name);
    END IF;

    IF NEW.symbol <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Symbol: ", NEW.symbol);
    END IF;

    IF NEW.shorthand <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Shorthand: ", NEW.shorthand);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('currency', NEW.currency_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `currency_trigger_update` AFTER UPDATE ON `currency` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.currency_name <> OLD.currency_name THEN
        SET audit_log = CONCAT(audit_log, "Currency Name: ", OLD.currency_name, " -> ", NEW.currency_name, "<br/>");
    END IF;

    IF NEW.symbol <> OLD.symbol THEN
        SET audit_log = CONCAT(audit_log, "Symbol: ", OLD.symbol, " -> ", NEW.symbol, "<br/>");
    END IF;

    IF NEW.shorthand <> OLD.shorthand THEN
        SET audit_log = CONCAT(audit_log, "Shorthand: ", OLD.shorthand, " -> ", NEW.shorthand, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('currency', NEW.currency_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `parent_department` int(11) DEFAULT NULL,
  `manager` int(11) DEFAULT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `department`
--
DELIMITER $$
CREATE TRIGGER `department_trigger_insert` AFTER INSERT ON `department` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Department created. <br/>';

    IF NEW.department_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Department Name: ", NEW.department_name);
    END IF;

    IF NEW.parent_department <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Parent Department: ", NEW.parent_department);
    END IF;

    IF NEW.manager <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Manager: ", NEW.manager);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('department', NEW.department_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `department_trigger_update` AFTER UPDATE ON `department` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.department_name <> OLD.department_name THEN
        SET audit_log = CONCAT(audit_log, "Department Name: ", OLD.department_name, " -> ", NEW.department_name, "<br/>");
    END IF;

    IF NEW.parent_department <> OLD.parent_department THEN
        SET audit_log = CONCAT(audit_log, "Parent Department: ", OLD.parent_department, " -> ", NEW.parent_department, "<br/>");
    END IF;

    IF NEW.manager <> OLD.manager THEN
        SET audit_log = CONCAT(audit_log, "Manager: ", OLD.manager, " -> ", NEW.manager, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('department', NEW.department_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `departure_reason`
--

CREATE TABLE `departure_reason` (
  `departure_reason_id` int(10) UNSIGNED NOT NULL,
  `departure_reason_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `departure_reason`
--
DELIMITER $$
CREATE TRIGGER `departure_reason_trigger_insert` AFTER INSERT ON `departure_reason` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Departure reason created. <br/>';

    IF NEW.departure_reason_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Departure Reason Name: ", NEW.departure_reason_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('departure_reason', NEW.departure_reason_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `departure_reason_trigger_update` AFTER UPDATE ON `departure_reason` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.departure_reason_name <> OLD.departure_reason_name THEN
        SET audit_log = CONCAT(audit_log, "Departure Reason Name: ", OLD.departure_reason_name, " -> ", NEW.departure_reason_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('departure_reason', NEW.departure_reason_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_id` int(10) UNSIGNED NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `city_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `district`
--
DELIMITER $$
CREATE TRIGGER `district_trigger_insert` AFTER INSERT ON `district` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'District created. <br/>';

    IF NEW.district_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>District Name: ", NEW.district_name);
    END IF;

    IF NEW.city_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>City ID: ", NEW.city_id);
    END IF;

    IF NEW.state_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>State ID: ", NEW.state_id);
    END IF;

    IF NEW.country_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Country ID: ", NEW.country_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('district', NEW.district_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `district_trigger_update` AFTER UPDATE ON `district` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.district_name <> OLD.district_name THEN
        SET audit_log = CONCAT(audit_log, "District Name: ", OLD.district_name, " -> ", NEW.district_name, "<br/>");
    END IF;

    IF NEW.city_id <> OLD.city_id THEN
        SET audit_log = CONCAT(audit_log, "City ID: ", OLD.city_id, " -> ", NEW.city_id, "<br/>");
    END IF;

    IF NEW.state_id <> OLD.state_id THEN
        SET audit_log = CONCAT(audit_log, "State ID: ", OLD.state_id, " -> ", NEW.state_id, "<br/>");
    END IF;

    IF NEW.country_id <> OLD.country_id THEN
        SET audit_log = CONCAT(audit_log, "Country ID: ", OLD.country_id, " -> ", NEW.country_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('district', NEW.district_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `email_setting`
--

CREATE TABLE `email_setting` (
  `email_setting_id` int(10) UNSIGNED NOT NULL,
  `email_setting_name` varchar(100) NOT NULL,
  `email_setting_description` varchar(200) NOT NULL,
  `mail_host` varchar(100) NOT NULL,
  `port` int(11) NOT NULL,
  `smtp_auth` int(1) NOT NULL,
  `smtp_auto_tls` int(1) NOT NULL,
  `mail_username` varchar(200) NOT NULL,
  `mail_password` varchar(250) NOT NULL,
  `mail_encryption` varchar(20) DEFAULT NULL,
  `mail_from_name` varchar(200) DEFAULT NULL,
  `mail_from_email` varchar(200) DEFAULT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_setting`
--

INSERT INTO `email_setting` (`email_setting_id`, `email_setting_name`, `email_setting_description`, `mail_host`, `port`, `smtp_auth`, `smtp_auto_tls`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from_name`, `mail_from_email`, `last_log_by`) VALUES
(1, 'Security Email Setting', '\r\nEmail setting for security emails.', 'smtp.hostinger.com', 465, 1, 0, 'encore-noreply@encorefinancials.com', 'UsDpF0dYRC6M9v0tT3MHq%2BlrRJu01%2Fb95Dq%2BAeCfu2Y%3D', 'ssl', 'encore-noreply@encorefinancials.com', 'encore-noreply@encorefinancials.com', 0);

--
-- Triggers `email_setting`
--
DELIMITER $$
CREATE TRIGGER `email_setting_trigger_insert` AFTER INSERT ON `email_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Email setting created. <br/>';

    IF NEW.email_setting_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Setting Name: ", NEW.email_setting_name);
    END IF;

    IF NEW.email_setting_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Setting Description: ", NEW.email_setting_description);
    END IF;

    IF NEW.mail_host <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mail Host: ", NEW.mail_host);
    END IF;

    IF NEW.port <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Port: ", NEW.port);
    END IF;

    IF NEW.smtp_auth <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>SMTP Auth: ", NEW.smtp_auth);
    END IF;

    IF NEW.smtp_auto_tls <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>SMTP Auto TLS: ", NEW.smtp_auto_tls);
    END IF;

    IF NEW.mail_username <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mail Username: ", NEW.mail_username);
    END IF;

    IF NEW.mail_encryption <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mail Encryption: ", NEW.mail_encryption);
    END IF;

    IF NEW.mail_from_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mail From Name: ", NEW.mail_from_name);
    END IF;

    IF NEW.mail_from_email <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mail From Email: ", NEW.mail_from_email);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('email_setting', NEW.email_setting_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `email_setting_trigger_update` AFTER UPDATE ON `email_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.email_setting_name <> OLD.email_setting_name THEN
        SET audit_log = CONCAT(audit_log, "Email Setting Name: ", OLD.email_setting_name, " -> ", NEW.email_setting_name, "<br/>");
    END IF;

    IF NEW.email_setting_description <> OLD.email_setting_description THEN
        SET audit_log = CONCAT(audit_log, "Email Setting Description: ", OLD.email_setting_description, " -> ", NEW.email_setting_description, "<br/>");
    END IF;

    IF NEW.mail_host <> OLD.mail_host THEN
        SET audit_log = CONCAT(audit_log, "Mail Host: ", OLD.mail_host, " -> ", NEW.mail_host, "<br/>");
    END IF;

    IF NEW.port <> OLD.port THEN
        SET audit_log = CONCAT(audit_log, "Port: ", OLD.port, " -> ", NEW.port, "<br/>");
    END IF;

    IF NEW.smtp_auth <> OLD.smtp_auth THEN
        SET audit_log = CONCAT(audit_log, "SMTP Auth: ", OLD.smtp_auth, " -> ", NEW.smtp_auth, "<br/>");
    END IF;

    IF NEW.smtp_auto_tls <> OLD.smtp_auto_tls THEN
        SET audit_log = CONCAT(audit_log, "SMTP Auto TLS: ", OLD.smtp_auto_tls, " -> ", NEW.smtp_auto_tls, "<br/>");
    END IF;

    IF NEW.mail_username <> OLD.mail_username THEN
        SET audit_log = CONCAT(audit_log, "Mail Username: ", OLD.mail_username, " -> ", NEW.mail_username, "<br/>");
    END IF;

    IF NEW.mail_encryption <> OLD.mail_encryption THEN
        SET audit_log = CONCAT(audit_log, "Mail Encryption: ", OLD.mail_encryption, " -> ", NEW.mail_encryption, "<br/>");
    END IF;

    IF NEW.mail_from_name <> OLD.mail_from_name THEN
        SET audit_log = CONCAT(audit_log, "Mail From Name: ", OLD.mail_from_name, " -> ", NEW.mail_from_name, "<br/>");
    END IF;

    IF NEW.mail_from_email <> OLD.mail_from_email THEN
        SET audit_log = CONCAT(audit_log, "Mail From Email: ", OLD.mail_from_email, " -> ", NEW.mail_from_email, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('email_setting', NEW.email_setting_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_type`
--

CREATE TABLE `employee_type` (
  `employee_type_id` int(10) UNSIGNED NOT NULL,
  `employee_type_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `employee_type`
--
DELIMITER $$
CREATE TRIGGER `employee_type_trigger_insert` AFTER INSERT ON `employee_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Employee type created. <br/>';

    IF NEW.employee_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Employee Type Name: ", NEW.employee_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('employee_type', NEW.employee_type_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `employee_type_trigger_update` AFTER UPDATE ON `employee_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.employee_type_name <> OLD.employee_type_name THEN
        SET audit_log = CONCAT(audit_log, "Employee Type Name: ", OLD.employee_type_name, " -> ", NEW.employee_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('employee_type', NEW.employee_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

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
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `gender_id` int(10) UNSIGNED NOT NULL,
  `gender_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `gender`
--
DELIMITER $$
CREATE TRIGGER `gender_trigger_insert` AFTER INSERT ON `gender` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Gender created. <br/>';

    IF NEW.gender_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Gender Name: ", NEW.gender_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('gender', NEW.gender_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `gender_trigger_update` AFTER UPDATE ON `gender` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.gender_name <> OLD.gender_name THEN
        SET audit_log = CONCAT(audit_log, "Gender Name: ", OLD.gender_name, " -> ", NEW.gender_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('gender', NEW.gender_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `holiday_type`
--

CREATE TABLE `holiday_type` (
  `holiday_type_id` int(10) UNSIGNED NOT NULL,
  `holiday_type_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `holiday_type`
--
DELIMITER $$
CREATE TRIGGER `holiday_type_trigger_insert` AFTER INSERT ON `holiday_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Holiday type created. <br/>';

    IF NEW.holiday_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Holiday Type Name: ", NEW.holiday_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('holiday_type', NEW.holiday_type_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `holiday_type_trigger_update` AFTER UPDATE ON `holiday_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.holiday_type_name <> OLD.holiday_type_name THEN
        SET audit_log = CONCAT(audit_log, "Holiday Type Name: ", OLD.holiday_type_name, " -> ", NEW.holiday_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('holiday_type', NEW.holiday_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `id_type`
--

CREATE TABLE `id_type` (
  `id_type_id` int(10) UNSIGNED NOT NULL,
  `id_type_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `id_type`
--
DELIMITER $$
CREATE TRIGGER `id_type_trigger_insert` AFTER INSERT ON `id_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'ID type created. <br/>';

    IF NEW.id_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>ID Type Name: ", NEW.id_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('id_type', NEW.id_type_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `id_type_trigger_update` AFTER UPDATE ON `id_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.id_type_name <> OLD.id_type_name THEN
        SET audit_log = CONCAT(audit_log, "ID Type Name: ", OLD.id_type_name, " -> ", NEW.id_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('id_type', NEW.id_type_id, audit_log, NEW.last_log_by, NOW());
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
(1, 'Login Background', 'Interface setting for background image on login page.', NULL, 0),
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
-- Table structure for table `job_level`
--

CREATE TABLE `job_level` (
  `job_level_id` int(10) UNSIGNED NOT NULL,
  `current_level` varchar(10) NOT NULL,
  `rank` varchar(100) NOT NULL,
  `functional_level` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `job_level`
--
DELIMITER $$
CREATE TRIGGER `job_level_trigger_insert` AFTER INSERT ON `job_level` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job level created. <br/>';

    IF NEW.current_level <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Current Level: ", NEW.current_level);
    END IF;

    IF NEW.rank <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Rank: ", NEW.rank);
    END IF;

    IF NEW.functional_level <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Functional Level: ", NEW.functional_level);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_level', NEW.job_level_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `job_level_trigger_update` AFTER UPDATE ON `job_level` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.current_level <> OLD.current_level THEN
        SET audit_log = CONCAT(audit_log, "Current Level: ", OLD.current_level, " -> ", NEW.current_level, "<br/>");
    END IF;

    IF NEW.rank <> OLD.rank THEN
        SET audit_log = CONCAT(audit_log, "Rank: ", OLD.rank, " -> ", NEW.rank, "<br/>");
    END IF;

    IF NEW.functional_level <> OLD.functional_level THEN
        SET audit_log = CONCAT(audit_log, "Functional Level: ", OLD.functional_level, " -> ", NEW.functional_level, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_level', NEW.job_level_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `job_position`
--

CREATE TABLE `job_position` (
  `job_position_id` int(10) UNSIGNED NOT NULL,
  `job_position_name` varchar(100) NOT NULL,
  `job_position_description` varchar(2000) NOT NULL,
  `recruitment_status` tinyint(1) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `expected_new_employees` int(11) NOT NULL DEFAULT 0,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `job_position`
--
DELIMITER $$
CREATE TRIGGER `job_position_trigger_insert` AFTER INSERT ON `job_position` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job position created. <br/>';

    IF NEW.job_position_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Job Position Name: ", NEW.job_position_name);
    END IF;

    IF NEW.job_position_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Job Position Description: ", NEW.job_position_description);
    END IF;

    IF NEW.recruitment_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Recruitment Status: ", NEW.recruitment_status);
    END IF;

    IF NEW.department_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Department ID: ", NEW.department_id);
    END IF;

    IF NEW.expected_new_employees <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Expected New Employees: ", NEW.expected_new_employees);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_position', NEW.job_position_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `job_position_trigger_update` AFTER UPDATE ON `job_position` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.job_position_name <> OLD.job_position_name THEN
        SET audit_log = CONCAT(audit_log, "Job Position Name: ", OLD.job_position_name, " -> ", NEW.job_position_name, "<br/>");
    END IF;

    IF NEW.job_position_description <> OLD.job_position_description THEN
        SET audit_log = CONCAT(audit_log, "Job Position Description: ", OLD.job_position_description, " -> ", NEW.job_position_description, "<br/>");
    END IF;

    IF NEW.recruitment_status <> OLD.recruitment_status THEN
        SET audit_log = CONCAT(audit_log, "Recruitment Status: ", OLD.recruitment_status, " -> ", NEW.recruitment_status, "<br/>");
    END IF;

    IF NEW.department_id <> OLD.department_id THEN
        SET audit_log = CONCAT(audit_log, "Department ID: ", OLD.department_id, " -> ", NEW.department_id, "<br/>");
    END IF;

    IF NEW.expected_new_employees <> OLD.expected_new_employees THEN
        SET audit_log = CONCAT(audit_log, "Expected New Employees: ", OLD.expected_new_employees, " -> ", NEW.expected_new_employees, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_position', NEW.job_position_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `job_position_qualification`
--

CREATE TABLE `job_position_qualification` (
  `job_position_qualification_id` int(10) UNSIGNED NOT NULL,
  `job_position_id` int(10) UNSIGNED NOT NULL,
  `qualification` varchar(1000) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `job_position_qualification`
--
DELIMITER $$
CREATE TRIGGER `job_position_qualification_trigger_insert` AFTER INSERT ON `job_position_qualification` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job position qualification created. <br/>';

    IF NEW.qualification <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Qualification: ", NEW.qualification);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_position_qualification', NEW.job_position_qualification_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `job_position_qualification_trigger_update` AFTER UPDATE ON `job_position_qualification` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.qualification <> OLD.qualification THEN
        SET audit_log = CONCAT(audit_log, "Qualification: ", OLD.qualification, " -> ", NEW.qualification, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_position_qualification', NEW.job_position_qualification_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `job_position_requirement`
--

CREATE TABLE `job_position_requirement` (
  `job_position_requirement_id` int(10) UNSIGNED NOT NULL,
  `job_position_id` int(10) UNSIGNED NOT NULL,
  `requirement` varchar(1000) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `job_position_requirement`
--
DELIMITER $$
CREATE TRIGGER `job_position_requirement_trigger_insert` AFTER INSERT ON `job_position_requirement` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job position requirement created. <br/>';

    IF NEW.requirement <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Requirement: ", NEW.requirement);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_position_requirement', NEW.job_position_requirement_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `job_position_requirement_trigger_update` AFTER UPDATE ON `job_position_requirement` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.requirement <> OLD.requirement THEN
        SET audit_log = CONCAT(audit_log, "Requirement: ", OLD.requirement, " -> ", NEW.requirement, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_position_requirement', NEW.job_position_requirement_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `job_position_responsibility`
--

CREATE TABLE `job_position_responsibility` (
  `job_position_responsibility_id` int(10) UNSIGNED NOT NULL,
  `job_position_id` int(10) UNSIGNED NOT NULL,
  `responsibility` varchar(1000) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `job_position_responsibility`
--
DELIMITER $$
CREATE TRIGGER `job_position_responsibility_trigger_insert` AFTER INSERT ON `job_position_responsibility` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job position responsibility created. <br/>';

    IF NEW.responsibility <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Responsibility: ", NEW.responsibility);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_position_responsibility', NEW.job_position_responsibility_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `job_position_responsibility_trigger_update` AFTER UPDATE ON `job_position_responsibility` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.responsibility <> OLD.responsibility THEN
        SET audit_log = CONCAT(audit_log, "Responsibility: ", OLD.responsibility, " -> ", NEW.responsibility, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_position_responsibility', NEW.job_position_responsibility_id, audit_log, NEW.last_log_by, NOW());
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
(1, 'Human Resources', 40, 0),
(2, 'Administration', 90, 0),
(3, 'Technical', 100, 0);

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
(1, 'Users & Companies', 2, '', 0, 'users', 1, 0),
(2, 'Company', 2, 'company.php', 1, '', 1, 0),
(3, 'User Account', 2, 'user-account.php', 1, '', 2, 0),
(4, 'Roles', 2, '', 0, 'shield', 2, 0),
(5, 'Role', 2, 'role.php', 4, '', 1, 0),
(6, 'Role Configuration', 2, 'role-configuration.php', 4, '', 2, 0),
(7, 'User Interface', 3, '', 0, 'layout', 1, 0),
(8, 'Menu Group', 3, 'menu-group.php', 7, '', 1, 0),
(9, 'Menu Item', 3, 'menu-item.php', 7, '', 2, 0),
(10, 'System Action', 3, 'system-action.php', 7, '', 3, 0),
(11, 'Configurations', 3, '', 0, 'settings', 2, 0),
(12, 'Email Setting', 3, 'email-setting.php', 11, '', 1, 0),
(13, 'File Type', 3, 'file-type.php', 11, '', 2, 0),
(14, 'File Extension', 3, 'file-extension.php', 11, '', 3, 0),
(15, 'Interface Setting', 3, 'interface-setting.php', 11, '', 4, 0),
(16, 'Notification Setting', 3, 'notification-setting.php', 11, '', 5, 0),
(17, 'System Setting', 3, 'system-setting.php', 11, '', 6, 0),
(18, 'Upload Setting', 3, 'upload-setting.php', 11, '', 7, 0),
(19, 'Zoom API', 3, 'zoom-api.php', 11, '', 8, 0),
(20, 'Localization', 3, '', 0, 'globe', 3, 0),
(21, 'City', 3, 'city.php', 20, '', 1, 0),
(22, 'Country', 3, 'country.php', 20, '', 2, 0),
(23, 'Currency', 3, 'currency.php', 20, '', 3, 0),
(24, 'State', 3, 'state.php', 20, '', 4, 0),
(25, 'Configurations', 1, '', 0, 'settings', 20, 0),
(26, 'Branch', 1, 'branch.php', 25, '', 1, 0),
(27, 'Department', 1, 'department.php', 25, '', 2, 0),
(28, 'Job Position', 1, 'job-position.php', 25, '', 3, 0),
(29, 'Job Level', 1, 'job-level.php', 25, '', 3, 0),
(30, 'Employee Type', 1, 'employee-type.php', 25, '', 3, 0),
(31, 'Departure Reason', 1, 'departure-reason.php', 25, '', 4, 0),
(32, 'ID Type', 1, 'id-type.php', 25, '', 9, 0),
(33, 'Gender', 1, 'gender.php', 25, '', 7, 0),
(34, 'Religion', 1, 'religion.php', 25, '', 18, 0),
(35, 'Nationality', 1, 'nationality.php', 25, '', 14, 0),
(36, 'Relation', 1, 'relation.php', 25, '', 18, 0),
(37, 'Civil Status', 1, 'civil-status.php', 25, '', 3, 0),
(38, 'Blood Type', 1, 'blood-type.php', 25, '', 2, 0),
(39, 'Bank', 3, 'bank.php', 11, '', 2, 0),
(40, 'Holiday Type', 1, 'holiday-type.php', 25, '', 8, 0),
(41, 'Work Schedule Type', 1, 'work-schedule-type.php', 25, '', 23, 0);

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
(7, 1, 1, 0, 0, 0, 0, 0),
(8, 1, 1, 1, 1, 1, 1, 0),
(9, 1, 1, 1, 1, 1, 1, 0),
(10, 1, 1, 1, 1, 1, 1, 0),
(11, 1, 1, 0, 0, 0, 0, 0),
(12, 1, 1, 1, 1, 1, 1, 0),
(13, 1, 1, 1, 1, 1, 1, 0),
(14, 1, 1, 1, 1, 1, 1, 1),
(15, 1, 1, 1, 1, 1, 1, 0),
(16, 1, 1, 1, 1, 1, 1, 0),
(17, 1, 1, 1, 1, 1, 1, 0),
(18, 1, 1, 1, 1, 1, 1, 0),
(19, 1, 1, 1, 1, 1, 1, 0),
(20, 1, 1, 0, 0, 0, 0, 0),
(21, 1, 1, 1, 1, 1, 1, 0),
(22, 1, 1, 1, 1, 1, 1, 0),
(23, 1, 1, 1, 1, 1, 1, 0),
(24, 1, 1, 1, 1, 1, 1, 0),
(25, 1, 1, 0, 0, 0, 0, 0),
(26, 1, 1, 1, 1, 1, 1, 0),
(27, 1, 1, 1, 1, 1, 1, 0),
(28, 1, 1, 1, 1, 1, 1, 0),
(29, 1, 1, 1, 1, 1, 1, 0),
(30, 1, 1, 1, 1, 1, 1, 0),
(31, 1, 1, 1, 1, 1, 1, 0),
(32, 1, 1, 1, 1, 1, 1, 0),
(33, 1, 1, 1, 1, 1, 1, 0),
(34, 1, 1, 1, 1, 1, 1, 0),
(35, 1, 1, 1, 1, 1, 1, 0),
(36, 1, 1, 1, 1, 1, 1, 0),
(37, 1, 1, 1, 1, 1, 1, 0),
(38, 1, 1, 1, 1, 1, 1, 0),
(39, 1, 1, 1, 1, 1, 1, 0),
(40, 1, 1, 1, 1, 1, 1, 0),
(41, 1, 1, 1, 1, 1, 1, 0);

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
-- Table structure for table `nationality`
--

CREATE TABLE `nationality` (
  `nationality_id` int(10) UNSIGNED NOT NULL,
  `nationality_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `nationality`
--
DELIMITER $$
CREATE TRIGGER `nationality_trigger_insert` AFTER INSERT ON `nationality` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Nationality created. <br/>';

    IF NEW.nationality_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Nationality Name: ", NEW.nationality_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('nationality', NEW.nationality_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `nationality_trigger_update` AFTER UPDATE ON `nationality` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.nationality_name <> OLD.nationality_name THEN
        SET audit_log = CONCAT(audit_log, "Nationality Name: ", OLD.nationality_name, " -> ", NEW.nationality_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('nationality', NEW.nationality_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_setting`
--

CREATE TABLE `notification_setting` (
  `notification_setting_id` int(10) UNSIGNED NOT NULL,
  `notification_setting_name` varchar(100) NOT NULL,
  `notification_setting_description` varchar(200) NOT NULL,
  `system_notification` int(1) NOT NULL DEFAULT 1,
  `email_notification` int(1) NOT NULL DEFAULT 0,
  `sms_notification` int(1) NOT NULL DEFAULT 0,
  `system_notification_title` varchar(200) DEFAULT NULL,
  `system_notification_message` varchar(200) DEFAULT NULL,
  `email_notification_subject` varchar(200) DEFAULT NULL,
  `email_notification_body` longtext DEFAULT NULL,
  `sms_notification_message` varchar(500) DEFAULT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_setting`
--

INSERT INTO `notification_setting` (`notification_setting_id`, `notification_setting_name`, `notification_setting_description`, `system_notification`, `email_notification`, `sms_notification`, `system_notification_title`, `system_notification_message`, `email_notification_subject`, `email_notification_body`, `sms_notification_message`, `last_log_by`) VALUES
(1, 'Login OTP', 'Notification setting for Login OTP received by the users.', 0, 1, 0, NULL, NULL, 'Login OTP - Secure Access to Your Account', '<p>To ensure the security of your account, we have generated a unique One-Time Password (OTP) for you to use during the login process. Please use the following OTP to access your account:</p>\r\n<p>OTP: <strong>{OTP_CODE}</strong></p>\r\n<p>Please note that this OTP is valid for &lt;strong&gt;5 minutes&lt;/strong&gt;. Once you have logged in successfully, we recommend enabling two-factor authentication for an added layer of security.</p>\r\n<p>If you did not initiate this login or believe it was sent to you in error, please disregard this email and delete it immediately. Your account\'s security remains our utmost priority.</p>\r\n<p>&nbsp;</p>\r\n<p>Note: This is an automatically generated email. Please do not reply to this address.</p>', NULL, 1),
(2, 'Forgot Password', 'Notification setting when the user initiates forgot password.', 0, 1, 0, NULL, NULL, 'Password Reset Request - Action Required', '<p>We have received a request to reset your password. To ensure the security of your account, please follow the instructions below:</p>\r\n<p>1. Click on the link below to reset your password:</p>\r\n<p><a href=\"{RESET_LINK}\"><strong>Reset Password</strong></a></p>\r\n<p>2. If the button does not work, you can copy and paste the following link into your browser\'s address bar:</p>\r\n<p><strong>{RESET_LINK}</strong></p>\r\n<p>Please note that this link is time-sensitive and will expire after <strong>10 minutes</strong>. If you do not reset your password within this timeframe, you may need to request another password reset.</p>\r\n<p>If you did not initiate this password reset request or believe it was sent to you in error, please disregard this email and delete it immediately. Your account\'s security remains our utmost priority.</p>\r\n<p>&nbsp;</p>\r\n<p>Note: This is an automatically generated email. Please do not reply to this address.</p>', NULL, 1);

--
-- Triggers `notification_setting`
--
DELIMITER $$
CREATE TRIGGER `notification_setting_trigger_insert` AFTER INSERT ON `notification_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Notification setting created. <br/>';

    IF NEW.notification_setting_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Notification Setting Name: ", NEW.notification_setting_name);
    END IF;

    IF NEW.notification_setting_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Notification Setting Description: ", NEW.notification_setting_description);
    END IF;

    IF NEW.system_notification <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>System Notification: ", NEW.system_notification);
    END IF;

    IF NEW.email_notification <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Notification: ", NEW.email_notification);
    END IF;

    IF NEW.sms_notification <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>SMS Notification: ", NEW.sms_notification);
    END IF;

    IF NEW.system_notification_title <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>System Notification Title: ", NEW.system_notification_title);
    END IF;

    IF NEW.system_notification_message <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>System Notification Message: ", NEW.system_notification_message);
    END IF;

    IF NEW.email_notification_subject <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Notification Subject: ", NEW.email_notification_subject);
    END IF;

    IF NEW.email_notification_body <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Notification Body: ", NEW.email_notification_body);
    END IF;

    IF NEW.sms_notification_message <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>SMS Notification Message: ", NEW.sms_notification_message);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('notification_setting', NEW.notification_setting_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `notification_setting_trigger_update` AFTER UPDATE ON `notification_setting` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.notification_setting_name <> OLD.notification_setting_name THEN
        SET audit_log = CONCAT(audit_log, "Notification Setting Name: ", OLD.notification_setting_name, " -> ", NEW.notification_setting_name, "<br/>");
    END IF;

    IF NEW.notification_setting_description <> OLD.notification_setting_description THEN
        SET audit_log = CONCAT(audit_log, "Notification Setting Description: ", OLD.notification_setting_description, " -> ", NEW.notification_setting_description, "<br/>");
    END IF;

    IF NEW.system_notification <> OLD.system_notification THEN
        SET audit_log = CONCAT(audit_log, "System Notification: ", OLD.system_notification, " -> ", NEW.system_notification, "<br/>");
    END IF;

    IF NEW.email_notification <> OLD.email_notification THEN
        SET audit_log = CONCAT(audit_log, "Email Notification: ", OLD.email_notification, " -> ", NEW.email_notification, "<br/>");
    END IF;

    IF NEW.sms_notification <> OLD.sms_notification THEN
        SET audit_log = CONCAT(audit_log, "SMS Notification: ", OLD.sms_notification, " -> ", NEW.sms_notification, "<br/>");
    END IF;

    IF NEW.system_notification_title <> OLD.system_notification_title THEN
        SET audit_log = CONCAT(audit_log, "System Notification Title: ", OLD.system_notification_title, " -> ", NEW.system_notification_title, "<br/>");
    END IF;

    IF NEW.system_notification_message <> OLD.system_notification_message THEN
        SET audit_log = CONCAT(audit_log, "System Notification Message: ", OLD.system_notification_message, " -> ", NEW.system_notification_message, "<br/>");
    END IF;

    IF NEW.email_notification_subject <> OLD.email_notification_subject THEN
        SET audit_log = CONCAT(audit_log, "Email Notification Subject: ", OLD.email_notification_subject, " -> ", NEW.email_notification_subject, "<br/>");
    END IF;

    IF NEW.email_notification_body <> OLD.email_notification_body THEN
        SET audit_log = CONCAT(audit_log, "Email Notification Body: ", OLD.email_notification_body, " -> ", NEW.email_notification_body, "<br/>");
    END IF;

    IF NEW.sms_notification_message <> OLD.sms_notification_message THEN
        SET audit_log = CONCAT(audit_log, "SMS Notification Message: ", OLD.sms_notification_message, " -> ", NEW.sms_notification_message, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('notification_setting', NEW.notification_setting_id, audit_log, NEW.last_log_by, NOW());
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
-- Table structure for table `relation`
--

CREATE TABLE `relation` (
  `relation_id` int(10) UNSIGNED NOT NULL,
  `relation_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `relation`
--
DELIMITER $$
CREATE TRIGGER `relation_trigger_insert` AFTER INSERT ON `relation` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Relation created. <br/>';

    IF NEW.relation_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Relation Name: ", NEW.relation_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('relation', NEW.relation_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `relation_trigger_update` AFTER UPDATE ON `relation` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.relation_name <> OLD.relation_name THEN
        SET audit_log = CONCAT(audit_log, "Relation Name: ", OLD.relation_name, " -> ", NEW.relation_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('relation', NEW.relation_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `religion`
--

CREATE TABLE `religion` (
  `religion_id` int(10) UNSIGNED NOT NULL,
  `religion_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `religion`
--
DELIMITER $$
CREATE TRIGGER `religion_trigger_insert` AFTER INSERT ON `religion` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Religion created. <br/>';

    IF NEW.religion_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Religion Name: ", NEW.religion_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('religion', NEW.religion_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `religion_trigger_update` AFTER UPDATE ON `religion` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.religion_name <> OLD.religion_name THEN
        SET audit_log = CONCAT(audit_log, "Religion Name: ", OLD.religion_name, " -> ", NEW.religion_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('religion', NEW.religion_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

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
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `state_id` int(10) UNSIGNED NOT NULL,
  `state_name` varchar(100) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_code` varchar(5) DEFAULT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `state_name`, `country_id`, `state_code`, `last_log_by`) VALUES
(1, 'Metro Manila', 174, '00', 0),
(2, 'Ilocos Norte', 174, 'ILN', 0),
(3, 'Ilocos Sur', 174, 'ILS', 0),
(4, 'La Union', 174, 'LUN', 0),
(5, 'Pangasinan', 174, 'PAN', 0),
(6, 'Batanes', 174, 'BTN', 0),
(7, 'Cagayan', 174, 'CAG', 0),
(8, 'Isabela', 174, 'ISA', 0),
(9, 'Nueva Vizcaya', 174, 'NSA', 0),
(10, 'Quirino', 174, 'QUI', 0),
(11, 'Bataan', 174, 'BAN', 0),
(12, 'Bulacan', 174, 'BUL', 0),
(13, 'Nueva Ecija', 174, 'NE', 0),
(14, 'Pampanga', 174, 'PAM', 0),
(15, 'Tarlac', 174, 'TAR', 0),
(16, 'Zambales', 174, 'ZMB', 0),
(17, 'Aurora', 174, 'AUR', 0),
(18, 'Batangas', 174, 'BTG', 0),
(19, 'Cavite', 174, 'CAV', 0),
(20, 'Laguna', 174, 'LAG', 0),
(21, 'Quezon', 174, 'QUE', 0),
(22, 'Rizal', 174, 'RIZ', 0),
(23, 'Marinduque', 174, 'MAD', 0),
(24, 'Occidental Mindoro', 174, 'NUE', 0),
(25, 'Oriental Mindoro', 174, 'NUV', 0),
(26, 'Palawan', 174, 'PLW', 0),
(27, 'Romblon', 174, 'ROM', 0),
(28, 'Albay', 174, 'ALB', 0),
(29, 'Camarines Norte', 174, 'CAN', 0),
(30, 'Camarines Sur', 174, 'CAS', 0),
(31, 'Catanduanes', 174, 'CAT', 0),
(32, 'Masbate', 174, 'MAS', 0),
(33, 'Sorsogon', 174, 'SOR', 0),
(34, 'Aklan', 174, 'AKL', 0),
(35, 'Antique', 174, 'ANT', 0),
(36, 'Capiz', 174, 'CAP', 0),
(37, 'Iloilo', 174, 'ILI', 0),
(38, 'Negros Occidental', 174, 'MSR', 0),
(39, 'Guimaras', 174, 'GUI', 0),
(40, 'Bohol', 174, 'BOH', 0),
(41, 'Cebu', 174, 'CEB', 0),
(42, 'Negros Oriental', 174, 'MOU', 0),
(43, 'Siquijor', 174, 'SIG', 0),
(44, 'Eastern Samar', 174, 'EAS', 0),
(45, 'Leyte', 174, 'LEY', 0),
(46, 'Northern Samar', 174, 'NEC', 0),
(47, 'Samar', 174, 'WSA', 0),
(48, 'Southern Leyte', 174, 'SLE', 0),
(49, 'Biliran', 174, 'BIL', 0),
(50, 'Zamboanga del Norte', 174, 'ZAN', 0),
(51, 'Zamboanga del Sur', 174, 'ZAS', 0),
(52, 'Zamboanga Sibugay', 174, 'ZSI', 0),
(53, 'Bukidnon', 174, 'BUK', 0),
(54, 'Camiguin', 174, 'CAM', 0),
(55, 'Lanao del Norte', 174, 'LAN', 0),
(56, 'Misamis Occidental', 174, 'MDC', 0),
(57, 'Misamis Oriental', 174, 'MDR', 0),
(58, 'Davao del Norte', 174, 'DAV', 0),
(59, 'Davao del Sur', 174, 'DAS', 0),
(60, 'Davao Oriental', 174, 'DAO', 0),
(61, 'Davao de Oro', 174, 'COM', 0),
(62, 'Davao Occidental', 174, 'DVO', 0),
(63, 'Cotabato', 174, 'COM', 0),
(64, 'South Cotabato', 174, 'SCO', 0),
(65, 'Sultan Kudarat', 174, 'SUK', 0),
(66, 'Sarangani', 174, 'SAR', 0),
(67, 'Abra', 174, 'ABR', 0),
(68, 'Benguet', 174, 'BEN', 0),
(69, 'Ifugao', 174, 'IFU', 0),
(70, 'Kalinga', 174, 'KAL', 0),
(71, 'Mountain Province', 174, 'MSC', 0),
(72, 'Apayao', 174, 'APA', 0),
(73, 'Basilan', 174, 'BAS', 0),
(74, 'Lanao del Sur', 174, 'LAS', 0),
(75, 'Maguindanao', 174, 'MAG', 0),
(76, 'Sulu', 174, 'SLU', 0),
(77, 'Tawi-Tawi', 174, 'TAW', 0),
(78, 'Agusan del Norte', 174, 'AGN', 0),
(79, 'Agusan del Sur', 174, 'AGS', 0),
(80, 'Surigao del Norte', 174, 'SUN', 0),
(81, 'Surigao del Sur', 174, 'SUR', 0),
(82, 'Dinagat Islands', 174, 'DIN', 0);

--
-- Triggers `state`
--
DELIMITER $$
CREATE TRIGGER `state_trigger_insert` AFTER INSERT ON `state` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'State created. <br/>';

    IF NEW.state_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>State Name: ", NEW.state_name);
    END IF;

    IF NEW.country_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Country ID: ", NEW.country_id);
    END IF;

    IF NEW.state_code <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>State Code: ", NEW.state_code);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('state', NEW.state_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `state_trigger_update` AFTER UPDATE ON `state` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.state_name <> OLD.state_name THEN
        SET audit_log = CONCAT(audit_log, "State Name: ", OLD.state_name, " -> ", NEW.state_name, "<br/>");
    END IF;

    IF NEW.country_id <> OLD.country_id THEN
        SET audit_log = CONCAT(audit_log, "Country ID: ", OLD.country_id, " -> ", NEW.country_id, "<br/>");
    END IF;

    IF NEW.state_code <> OLD.state_code THEN
        SET audit_log = CONCAT(audit_log, "State Code: ", OLD.state_code, " -> ", NEW.state_code, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('state', NEW.state_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

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
(17, 'Send Reset Password Instructions', 0),
(18, 'Add Job Position Responsibility', 0),
(19, 'Update Job Position Responsibility', 0),
(20, 'Delete Job Position Responsibility', 0),
(21, 'Add Job Position Requirement', 0),
(22, 'Update Job Position Requirement', 0),
(23, 'Delete Job Position Requirement', 0),
(24, 'Add Job Position Qualification', 0),
(25, 'Update Job Position Qualification', 0),
(26, 'Delete Job Position Qualification', 0),
(27, 'Start Job Position Recruitment', 0),
(28, 'Stop Job Position Recruitment', 0);

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
(17, 1, 1, 0),
(18, 1, 1, 0),
(19, 1, 1, 0),
(20, 1, 1, 0),
(21, 1, 1, 0),
(22, 1, 1, 0),
(23, 1, 1, 0),
(24, 1, 1, 0),
(25, 1, 1, 0),
(26, 1, 1, 0),
(27, 1, 1, 0),
(28, 1, 1, 0);

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
(2, 'Max Failed OTP Attempt', 'This sets the maximum failed OTP attempt before the user is needs a new OTP code.', '5', 0),
(3, 'Default Forgot Password Link', 'This sets the default forgot password link.', 'http://localhost/tech_nexus/password-reset.php?id=', 0);

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
(2, 'Interface Setting', 'Sets the upload setting when uploading interface setting image.', 5, 0),
(3, 'Company Logo', 'Sets the upload setting when uploading company logo.', 5, 0);

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
(2, 60, 0),
(3, 61, 0),
(3, 62, 0),
(3, 63, 0),
(3, 66, 0),
(3, 69, 0);

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
(1, 'Administrator', 'ldagulto@encorefinancials.com', 'RYHObc8sNwIxdPDNJwCsO8bXKZJXYx7RjTgEWMC17FY%3D', NULL, 0, 1, NULL, 0, '2023-09-12 09:02:45', '2023-12-30', NULL, NULL, 0, 0, NULL, NULL, 0, NULL, 0, NULL, 0, '87e00cefb533e022629eeb9e8a79f2cf', 0);

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

-- --------------------------------------------------------

--
-- Table structure for table `work_schedule_type`
--

CREATE TABLE `work_schedule_type` (
  `work_schedule_type_id` int(10) UNSIGNED NOT NULL,
  `work_schedule_type_name` varchar(100) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `work_schedule_type`
--
DELIMITER $$
CREATE TRIGGER `work_schedule_type_trigger_insert` AFTER INSERT ON `work_schedule_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Work schedule type created. <br/>';

    IF NEW.work_schedule_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Work Schedule Type Name: ", NEW.work_schedule_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('work_schedule_type', NEW.work_schedule_type_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `work_schedule_type_trigger_update` AFTER UPDATE ON `work_schedule_type` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.work_schedule_type_name <> OLD.work_schedule_type_name THEN
        SET audit_log = CONCAT(audit_log, "Work Schedule Type Name: ", OLD.work_schedule_type_name, " -> ", NEW.work_schedule_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('work_schedule_type', NEW.work_schedule_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `zoom_api`
--

CREATE TABLE `zoom_api` (
  `zoom_api_id` int(10) UNSIGNED NOT NULL,
  `zoom_api_name` varchar(100) NOT NULL,
  `zoom_api_description` varchar(200) NOT NULL,
  `api_key` varchar(1000) NOT NULL,
  `api_secret` varchar(1000) NOT NULL,
  `last_log_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `zoom_api`
--
DELIMITER $$
CREATE TRIGGER `zoom_api_trigger_insert` AFTER INSERT ON `zoom_api` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT 'Zoom API created. <br/>';

    IF NEW.zoom_api_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Zoom API Name: ", NEW.zoom_api_name);
    END IF;

    IF NEW.zoom_api_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Zoom API Description: ", NEW.zoom_api_description);
    END IF;

    IF NEW.api_key <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>API Key: ", NEW.api_key);
    END IF;

    IF NEW.api_secret <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>API Secret: ", NEW.api_secret);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('zoom_api', NEW.zoom_api_id, audit_log, NEW.last_log_by, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zoom_api_trigger_update` AFTER UPDATE ON `zoom_api` FOR EACH ROW BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.zoom_api_name <> OLD.zoom_api_name THEN
        SET audit_log = CONCAT(audit_log, "Zoom API Name: ", OLD.zoom_api_name, " -> ", NEW.zoom_api_name, "<br/>");
    END IF;

    IF NEW.zoom_api_description <> OLD.zoom_api_description THEN
        SET audit_log = CONCAT(audit_log, "Zoom API Description: ", OLD.zoom_api_description, " -> ", NEW.zoom_api_description, "<br/>");
    END IF;

    IF NEW.api_key <> OLD.api_key THEN
        SET audit_log = CONCAT(audit_log, "API Key: ", OLD.api_key, " -> ", NEW.api_key, "<br/>");
    END IF;

    IF NEW.api_secret <> OLD.api_secret THEN
        SET audit_log = CONCAT(audit_log, "API Secret: ", OLD.api_secret, " -> ", NEW.api_secret, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('zoom_api', NEW.zoom_api_id, audit_log, NEW.last_log_by, NOW());
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
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`bank_id`),
  ADD KEY `bank_index_bank_id` (`bank_id`);

--
-- Indexes for table `blood_type`
--
ALTER TABLE `blood_type`
  ADD PRIMARY KEY (`blood_type_id`),
  ADD KEY `blood_type_index_blood_type_id` (`blood_type_id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branch_id`),
  ADD KEY `branch_index_branch_id` (`branch_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `city_index_city_id` (`city_id`),
  ADD KEY `city_index_state_id` (`state_id`);

--
-- Indexes for table `civil_status`
--
ALTER TABLE `civil_status`
  ADD PRIMARY KEY (`civil_status_id`),
  ADD KEY `civil_status_index_civil_status_id` (`civil_status_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`),
  ADD KEY `company_index_company_id` (`company_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`),
  ADD KEY `country_index_country_id` (`country_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`),
  ADD KEY `currency_index_currency_id` (`currency_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `department_index_department_id` (`department_id`);

--
-- Indexes for table `departure_reason`
--
ALTER TABLE `departure_reason`
  ADD PRIMARY KEY (`departure_reason_id`),
  ADD KEY `departure_reason_index_departure_reason_id` (`departure_reason_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `district_index_district_id` (`district_id`),
  ADD KEY `district_index_city_id` (`city_id`),
  ADD KEY `district_index_state_id` (`state_id`),
  ADD KEY `district_index_country_id` (`country_id`);

--
-- Indexes for table `email_setting`
--
ALTER TABLE `email_setting`
  ADD PRIMARY KEY (`email_setting_id`),
  ADD KEY `email_setting_index_email_setting_id` (`email_setting_id`);

--
-- Indexes for table `employee_type`
--
ALTER TABLE `employee_type`
  ADD PRIMARY KEY (`employee_type_id`),
  ADD KEY `employee_type_index_employee_type_id` (`employee_type_id`);

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
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`gender_id`),
  ADD KEY `gender_index_gender_id` (`gender_id`);

--
-- Indexes for table `holiday_type`
--
ALTER TABLE `holiday_type`
  ADD PRIMARY KEY (`holiday_type_id`),
  ADD KEY `holiday_type_index_holiday_type_id` (`holiday_type_id`);

--
-- Indexes for table `id_type`
--
ALTER TABLE `id_type`
  ADD PRIMARY KEY (`id_type_id`),
  ADD KEY `id_type_index_id_type_id` (`id_type_id`);

--
-- Indexes for table `interface_setting`
--
ALTER TABLE `interface_setting`
  ADD PRIMARY KEY (`interface_setting_id`),
  ADD KEY `interface_setting_index_interface_setting_id` (`interface_setting_id`);

--
-- Indexes for table `job_level`
--
ALTER TABLE `job_level`
  ADD PRIMARY KEY (`job_level_id`),
  ADD KEY `job_level_index_job_level_id` (`job_level_id`);

--
-- Indexes for table `job_position`
--
ALTER TABLE `job_position`
  ADD PRIMARY KEY (`job_position_id`),
  ADD KEY `job_position_index_job_position_id` (`job_position_id`);

--
-- Indexes for table `job_position_qualification`
--
ALTER TABLE `job_position_qualification`
  ADD PRIMARY KEY (`job_position_qualification_id`),
  ADD KEY `job_position_qualification_index_job_position_id` (`job_position_id`),
  ADD KEY `job_position_qualification_index_job_position_qualification_id` (`job_position_qualification_id`);

--
-- Indexes for table `job_position_requirement`
--
ALTER TABLE `job_position_requirement`
  ADD PRIMARY KEY (`job_position_requirement_id`),
  ADD KEY `job_position_requirement_index_job_position_id` (`job_position_id`),
  ADD KEY `job_position_requirement_index_job_position_requirement_id` (`job_position_requirement_id`);

--
-- Indexes for table `job_position_responsibility`
--
ALTER TABLE `job_position_responsibility`
  ADD PRIMARY KEY (`job_position_responsibility_id`),
  ADD KEY `job_position_responsibility_index_job_position_id` (`job_position_id`),
  ADD KEY `job_position_responsibility_index_job_position_responsibility_id` (`job_position_responsibility_id`);

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
-- Indexes for table `nationality`
--
ALTER TABLE `nationality`
  ADD PRIMARY KEY (`nationality_id`),
  ADD KEY `nationality_index_nationality_id` (`nationality_id`);

--
-- Indexes for table `notification_setting`
--
ALTER TABLE `notification_setting`
  ADD PRIMARY KEY (`notification_setting_id`),
  ADD KEY `notification_setting_index_notification_setting_id` (`notification_setting_id`);

--
-- Indexes for table `password_history`
--
ALTER TABLE `password_history`
  ADD PRIMARY KEY (`password_history_id`),
  ADD KEY `password_history_index_password_history_id` (`password_history_id`),
  ADD KEY `password_history_index_user_id` (`user_id`),
  ADD KEY `password_history_index_email` (`email`);

--
-- Indexes for table `relation`
--
ALTER TABLE `relation`
  ADD PRIMARY KEY (`relation_id`),
  ADD KEY `relation_index_relation_id` (`relation_id`);

--
-- Indexes for table `religion`
--
ALTER TABLE `religion`
  ADD PRIMARY KEY (`religion_id`),
  ADD KEY `religion_index_religion_id` (`religion_id`);

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
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`),
  ADD KEY `state_index_state_id` (`state_id`),
  ADD KEY `state_index_country_id` (`country_id`);

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
-- Indexes for table `work_schedule_type`
--
ALTER TABLE `work_schedule_type`
  ADD PRIMARY KEY (`work_schedule_type_id`),
  ADD KEY `work_schedule_type_index_work_schedule_type_id` (`work_schedule_type_id`);

--
-- Indexes for table `zoom_api`
--
ALTER TABLE `zoom_api`
  ADD PRIMARY KEY (`zoom_api_id`),
  ADD KEY `zoom_api_index_zoom_api_id` (`zoom_api_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `audit_log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2355;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `bank_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blood_type`
--
ALTER TABLE `blood_type`
  MODIFY `blood_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1635;

--
-- AUTO_INCREMENT for table `civil_status`
--
ALTER TABLE `civil_status`
  MODIFY `civil_status_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `country_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departure_reason`
--
ALTER TABLE `departure_reason`
  MODIFY `departure_reason_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_setting`
--
ALTER TABLE `email_setting`
  MODIFY `email_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_type`
--
ALTER TABLE `employee_type`
  MODIFY `employee_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `gender_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `holiday_type`
--
ALTER TABLE `holiday_type`
  MODIFY `holiday_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `id_type`
--
ALTER TABLE `id_type`
  MODIFY `id_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `interface_setting`
--
ALTER TABLE `interface_setting`
  MODIFY `interface_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_level`
--
ALTER TABLE `job_level`
  MODIFY `job_level_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_position`
--
ALTER TABLE `job_position`
  MODIFY `job_position_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `job_position_qualification`
--
ALTER TABLE `job_position_qualification`
  MODIFY `job_position_qualification_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `job_position_requirement`
--
ALTER TABLE `job_position_requirement`
  MODIFY `job_position_requirement_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_position_responsibility`
--
ALTER TABLE `job_position_responsibility`
  MODIFY `job_position_responsibility_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_group`
--
ALTER TABLE `menu_group`
  MODIFY `menu_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `nationality`
--
ALTER TABLE `nationality`
  MODIFY `nationality_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification_setting`
--
ALTER TABLE `notification_setting`
  MODIFY `notification_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_history`
--
ALTER TABLE `password_history`
  MODIFY `password_history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `relation`
--
ALTER TABLE `relation`
  MODIFY `relation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `religion`
--
ALTER TABLE `religion`
  MODIFY `religion_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `system_action`
--
ALTER TABLE `system_action`
  MODIFY `system_action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `system_setting`
--
ALTER TABLE `system_setting`
  MODIFY `system_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ui_customization_setting`
--
ALTER TABLE `ui_customization_setting`
  MODIFY `ui_customization_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_setting`
--
ALTER TABLE `upload_setting`
  MODIFY `upload_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `work_schedule_type`
--
ALTER TABLE `work_schedule_type`
  MODIFY `work_schedule_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `zoom_api`
--
ALTER TABLE `zoom_api`
  MODIFY `zoom_api_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_extension`
--
ALTER TABLE `file_extension`
  ADD CONSTRAINT `file_extension_ibfk_1` FOREIGN KEY (`file_type_id`) REFERENCES `file_type` (`file_type_id`);

--
-- Constraints for table `job_position_qualification`
--
ALTER TABLE `job_position_qualification`
  ADD CONSTRAINT `job_position_qualification_ibfk_1` FOREIGN KEY (`job_position_id`) REFERENCES `job_position` (`job_position_id`);

--
-- Constraints for table `job_position_requirement`
--
ALTER TABLE `job_position_requirement`
  ADD CONSTRAINT `job_position_requirement_ibfk_1` FOREIGN KEY (`job_position_id`) REFERENCES `job_position` (`job_position_id`);

--
-- Constraints for table `job_position_responsibility`
--
ALTER TABLE `job_position_responsibility`
  ADD CONSTRAINT `job_position_responsibility_ibfk_1` FOREIGN KEY (`job_position_id`) REFERENCES `job_position` (`job_position_id`);

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
