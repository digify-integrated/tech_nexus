DELIMITER //

/* Users Table Stored Procedures */



CREATE PROCEDURE checkUserExist(IN p_user_id INT, IN p_email VARCHAR(255))
BEGIN
	SELECT COUNT(*) AS total
    FROM users
    WHERE user_id = p_user_id OR email = p_email;
END //

CREATE PROCEDURE checkUserIDExist(IN p_user_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM users
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE checkUserEmailExist(IN p_email VARCHAR(255))
BEGIN
	SELECT COUNT(*) AS total
    FROM users
    WHERE email = p_email;
END //

CREATE PROCEDURE insertUserAccount(IN p_file_as VARCHAR(300), IN p_email VARCHAR(255), IN p_password VARCHAR(255), IN p_password_expiry_date DATE, IN p_last_log_by INT, OUT p_user_account_id INT)
BEGIN
    INSERT INTO users (file_as, email, password, password_expiry_date, last_log_by) 
	VALUES(p_file_as, p_email, p_password, p_password_expiry_date, p_last_log_by);
	
    SET p_user_account_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateUserAccount(IN p_user_id INT, IN p_file_as VARCHAR(300), IN p_email VARCHAR(255), IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET file_as = p_file_as, email = p_email, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE deleteUserAccount(IN p_user_id INT)
BEGIN
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
END //

CREATE PROCEDURE activateUserAccount(IN p_user_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET is_active = 1, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE deactivateUserAccount(IN p_user_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET is_active = 0, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE lockUserAccount(IN p_user_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET is_locked = 1, account_lock_duration = CAST(4294967295 AS SIGNED), last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE unlockUserAccount(IN p_user_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET is_locked = 0, account_lock_duration = 0, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE getUserByEmail(IN p_email VARCHAR(255))
BEGIN
	SELECT * FROM users
	WHERE email = BINARY p_email;
END //

CREATE PROCEDURE getContactByID(IN p_user_id INT)
BEGIN
	SELECT * FROM contact
	WHERE user_id = p_user_id;
END //


CREATE PROCEDURE getContactByContactID(IN p_contact_id INT)
BEGIN
	SELECT * FROM users
	WHERE user_id IN (SELECT user_id FROM contact WHERE contact_id = p_contact_id);
END //

CREATE PROCEDURE getUserByID(IN p_user_id INT)
BEGIN
	SELECT * FROM users
	WHERE user_id = p_user_id;
END //

CREATE PROCEDURE getUserByRememberToken(IN p_remember_token VARCHAR(255))
BEGIN
	SELECT * FROM users
	WHERE remember_token = p_remember_token;
END //

CREATE PROCEDURE updateAccountLock(IN p_user_id INT, IN p_is_locked TINYINT(1), IN p_account_lock_duration INT)
BEGIN
	UPDATE users 
    SET is_locked = p_is_locked, account_lock_duration = p_account_lock_duration 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateLoginAttempt(IN p_user_id INT, IN p_failed_login_attempts INT, IN p_last_failed_login_attempt DATETIME)
BEGIN
	UPDATE users 
    SET failed_login_attempts = p_failed_login_attempts, last_failed_login_attempt = p_last_failed_login_attempt
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateLastConnection(IN p_user_id INT, IN p_last_connection_date DATETIME)
BEGIN
	UPDATE users 
    SET last_connection_date = p_last_connection_date
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateRememberToken(IN p_user_id INT, IN p_remember_token VARCHAR(255))
BEGIN
	UPDATE users 
    SET remember_token = p_remember_token
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateOTP(IN p_user_id INT, IN p_otp VARCHAR(255), IN p_otp_expiry_date DATETIME, IN p_remember_me TINYINT(1))
BEGIN
	UPDATE users 
    SET otp = p_otp, otp_expiry_date = p_otp_expiry_date, remember_me = p_remember_me, failed_otp_attempts = 0
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateFailedOTPAttempts(IN p_user_id INT, IN p_failed_otp_attempts INT)
BEGIN
	UPDATE users 
    SET failed_otp_attempts = p_failed_otp_attempts
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateOTPAsExpired(IN p_user_id INT, IN p_otp_expiry_date DATETIME)
BEGIN
	UPDATE users 
    SET otp_expiry_date = p_otp_expiry_date
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateResetToken(IN p_user_id INT, IN p_reset_token VARCHAR(255), IN p_reset_token_expiry_date DATETIME)
BEGIN
	UPDATE users 
    SET reset_token = p_reset_token, reset_token_expiry_date = p_reset_token_expiry_date
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateUserPassword(IN p_user_id INT, IN p_email VARCHAR(255), IN p_password VARCHAR(255), IN p_password_expiry_date DATE, IN p_last_password_change DATETIME)
BEGIN
	UPDATE users 
    SET password = p_password, password_expiry_date = p_password_expiry_date, last_password_change = p_last_password_change, is_locked = 0, failed_login_attempts = 0, account_lock_duration = 0
    WHERE p_user_id = user_id OR email = BINARY p_email;
END //

CREATE PROCEDURE updateUserNotificationSetting(IN p_user_id INT, IN p_receive_notification TINYINT(1), IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET receive_notification = p_receive_notification, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateTwoFactorAuthentication(IN p_user_id INT, IN p_two_factor_auth TINYINT(1), IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET two_factor_auth = p_two_factor_auth, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateUserProfilePicture(IN p_user_id INT, IN p_profile_picture VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE users 
    SET profile_picture = p_profile_picture, last_log_by = p_last_log_by 
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE generateRoleUserAccountTable(IN p_role_id INT)
BEGIN
	SELECT user_id, file_as, email, last_connection_date FROM users
    WHERE user_id IN (SELECT user_id FROM role_users WHERE role_id = p_role_id)
    ORDER BY file_as;
END //

CREATE PROCEDURE generateAddRoleUserAccountTable(IN p_role_id INT)
BEGIN
	SELECT user_id, file_as, email, last_connection_date FROM users
    WHERE user_id NOT IN (SELECT user_id FROM role_users WHERE role_id = p_role_id)
    ORDER BY file_as;
END //

CREATE PROCEDURE generateUserAccountRoleTable(IN p_user_account_id INT)
BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM role_users WHERE user_id = p_user_account_id)
    ORDER BY role_name;
END //

CREATE PROCEDURE generateAddUserAccountRoleTable(IN p_user_account_id INT)
BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM role_users WHERE user_id = p_user_account_id)
    ORDER BY role_name;
END //

CREATE PROCEDURE generateUserAccountTable(IN p_is_active ENUM('active', 'inactive', 'all'), IN p_is_locked ENUM('yes', 'no', 'all'), IN p_password_expiry_date_start_date DATE, IN p_password_expiry_date_end_date DATE, IN p_filter_last_connection_date_start_date DATE, IN p_filter_last_connection_date_end_date DATE, IN p_filter_last_password_reset_start_date DATE, IN p_filter_last_password_reset_end_date DATE, IN p_filter_last_failed_login_attempt_start_date DATE, IN p_filter_last_failed_login_attempt_end_date DATE)
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT * FROM users';
    
    SET conditionList = ' WHERE 1';

    IF p_is_active <> 'all' THEN
        IF p_is_active = 'active' THEN
            SET conditionList = CONCAT(conditionList, ' AND is_active = 1');
        ELSEIF p_is_active = 'inactive' THEN
            SET conditionList = CONCAT(conditionList, ' AND is_active = 0');
        END IF;
    END IF;

    IF p_is_locked <> 'all' THEN
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
END //

CREATE PROCEDURE generateUnlinkedContactOptions()
BEGIN
	SELECT contact_id, file_as FROM personal_information 
    WHERE contact_id IN (SELECT contact_id FROM contact WHERE portal_access = 1 AND user_id IS NULL);
END //

CREATE PROCEDURE linkUserAccountToContact(IN p_contact_id INT, IN p_user_account_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE contact 
    SET user_id = p_user_account_id, last_log_by = p_last_log_by 
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE unlinkUserAccountToContact(IN p_user_account_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE contact 
    SET user_id = null, last_log_by = p_last_log_by 
    WHERE user_id = p_user_account_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Password History Stored Procedures */

CREATE PROCEDURE getPasswordHistory(IN p_user_id INT, IN p_email VARCHAR(255))
BEGIN
	SELECT * FROM password_history
	WHERE user_id = p_user_id OR email = BINARY p_email;
END //

CREATE PROCEDURE insertPasswordHistory(IN p_user_id INT, IN p_email VARCHAR(255), IN p_password VARCHAR(255), IN p_last_password_change DATETIME)
BEGIN
    INSERT INTO password_history (user_id, email, password, password_change_date) 
    VALUES (p_user_id, p_email, p_password, p_last_password_change);
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Audit Log Table Stored Procedures */

CREATE PROCEDURE generateLogNotes(IN p_table_name VARCHAR(255), IN p_reference_id INT)
BEGIN
	SELECT log, changed_by, changed_at FROM audit_log
    WHERE table_name = p_table_name AND reference_id = p_reference_id
    ORDER BY changed_at DESC;
END //

CREATE PROCEDURE getCreatedByLog(IN p_table_name VARCHAR(255), IN p_reference_id INT)
BEGIN
	SELECT changed_by FROM audit_log
    WHERE table_name = p_table_name AND reference_id = p_reference_id
    ORDER BY changed_at ASC LIMIT 1;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* UI Customization Setting Table Stored Procedures */

CREATE PROCEDURE checkUICustomizationSettingExist(IN p_user_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM ui_customization_setting
	WHERE user_id = p_user_id;
END //

CREATE PROCEDURE insertUICustomizationSetting(IN p_user_id INT, IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT)
BEGIN
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
END //

CREATE PROCEDURE updateUICustomizationSetting(IN p_user_id INT, IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT)
BEGIN
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
END //

CREATE PROCEDURE getUICustomizationSetting(IN p_user_id INT)
BEGIN
	SELECT * FROM ui_customization_setting
	WHERE user_id = p_user_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Role Table Stored Procedures */

CREATE PROCEDURE checkRoleExist(IN p_role_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM role
    WHERE role_id = p_role_id;
END //

CREATE PROCEDURE insertRole(IN p_role_name VARCHAR(100), IN p_role_description VARCHAR(200), IN p_assignable TINYINT(1), IN p_last_log_by INT, OUT p_role_id INT)
BEGIN
    INSERT INTO role (role_name, role_description, assignable, last_log_by) 
	VALUES(p_role_name, p_role_description, p_assignable, p_last_log_by);
	
    SET p_role_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateRole(IN p_role_id INT, IN p_role_name VARCHAR(100), IN p_role_description VARCHAR(200), IN p_assignable TINYINT(1), IN p_last_log_by INT)
BEGIN
	UPDATE role
    SET role_name = p_role_name,
    role_name = p_role_name,
    role_description = p_role_description,
    assignable = p_assignable,
    last_log_by = p_last_log_by
    WHERE role_id = p_role_id;
END //

CREATE PROCEDURE deleteRole(IN p_role_id INT)
BEGIN
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
END //

CREATE PROCEDURE getRole(IN p_role_id INT)
BEGIN
	SELECT * FROM role
    WHERE role_id = p_role_id;
END //

CREATE PROCEDURE duplicateRole(IN p_role_id INT, IN p_last_log_by INT, OUT p_new_role_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateRoleConfigurationTable()
BEGIN
	SELECT role_id, role_name, role_description, assignable
    FROM role 
    ORDER BY role_id;
END //

CREATE PROCEDURE generateRoleTable()
BEGIN
	SELECT role_id, role_name, role_description
    FROM role
    WHERE assignable = 1
    ORDER BY role_id;
END //

CREATE PROCEDURE generateMenuItemAccessTable()
BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    ORDER BY menu_item_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Role Table Stored Procedures */

CREATE PROCEDURE checkRoleUserExist(IN p_user_id INT, IN p_role_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM role_users
    WHERE user_id = p_user_id AND role_id = p_role_id;
END //

CREATE PROCEDURE insertRoleUser(IN p_user_id INT, IN p_role_id INT)
BEGIN
    INSERT INTO role_users (user_id, role_id) 
	VALUES(p_user_id, p_role_id);
END //

CREATE PROCEDURE deleteRoleUser(IN p_user_id INT, IN p_role_id INT)
BEGIN
	DELETE FROM role_users
    WHERE user_id = p_user_id AND role_id = p_role_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table Stored Procedures */

CREATE PROCEDURE checkMenuGroupExist(IN p_menu_group_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM menu_group
    WHERE menu_group_id = p_menu_group_id;
END //

CREATE PROCEDURE insertMenuGroup(IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT, OUT p_menu_group_id INT)
BEGIN
    INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) 
	VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
	
    SET p_menu_group_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateMenuGroup(IN p_menu_group_id INT, IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT)
BEGIN
	UPDATE menu_group
    SET menu_group_name = p_menu_group_name,
    order_sequence = p_order_sequence,
    last_log_by = p_last_log_by
    WHERE menu_group_id = p_menu_group_id;
END //

CREATE PROCEDURE deleteMenuGroup(IN p_menu_group_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM menu_item_access_right WHERE menu_item_id IN (SELECT menu_item_id FROM menu_item WHERE menu_group_id = p_menu_group_id);
    DELETE FROM menu_item WHERE menu_group_id = p_menu_group_id;
    DELETE FROM menu_group WHERE menu_group_id = p_menu_group_id;

    COMMIT;
END //

CREATE PROCEDURE getMenuGroup(IN p_menu_group_id INT)
BEGIN
	SELECT * FROM menu_group
	WHERE menu_group_id = p_menu_group_id;
END //

CREATE PROCEDURE duplicateMenuGroup(IN p_menu_group_id INT, IN p_last_log_by INT, OUT p_new_menu_group_id INT)
BEGIN
    DECLARE p_menu_group_name VARCHAR(100);
    DECLARE p_order_sequence TINYINT(10);
    
    SELECT menu_group_name, order_sequence 
    INTO p_menu_group_name, p_order_sequence 
    FROM menu_group 
    WHERE menu_group_id = p_menu_group_id;
    
    INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) 
    VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
    
    SET p_new_menu_group_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateMenuGroupOptions()
BEGIN
	SELECT menu_group_id, menu_group_name FROM menu_group
	ORDER BY menu_group_name;
END //

CREATE PROCEDURE generateMenuGroupTable()
BEGIN
	SELECT menu_group_id, menu_group_name, order_sequence 
    FROM menu_group 
    ORDER BY menu_group_id;
END //

CREATE PROCEDURE generateMenuGroupMenuItemTable(IN p_menu_group_id INT)
BEGIN
	SELECT menu_item_id, menu_item_name, parent_id, order_sequence 
    FROM menu_item
    WHERE menu_group_id = p_menu_group_id 
    ORDER BY menu_item_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Menu Item Table Stored Procedures */

CREATE PROCEDURE checkMenuItemExist(IN p_menu_item_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM menu_item
    WHERE menu_item_id = p_menu_item_id;
END //

CREATE PROCEDURE insertMenuItem(IN p_menu_item_name VARCHAR(100), IN p_menu_group_id INT, IN p_menu_item_url VARCHAR(50), IN p_parent_id INT, IN p_menu_item_icon VARCHAR(150), IN p_order_sequence TINYINT(10), IN p_last_log_by INT, OUT p_menu_item_id INT)
BEGIN
    INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) 
	VALUES(p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
	
    SET p_menu_item_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateMenuItem(IN p_menu_item_id INT, IN p_menu_item_name VARCHAR(100), IN p_menu_group_id INT, IN p_menu_item_url VARCHAR(50), IN p_parent_id INT, IN p_menu_item_icon VARCHAR(150), IN p_order_sequence TINYINT(10), IN p_last_log_by INT)
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
END //

CREATE PROCEDURE deleteMenuItem(IN p_menu_item_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM menu_item_access_right WHERE menu_item_id = p_menu_item_id;
    DELETE FROM menu_item WHERE menu_item_id = p_menu_item_id;

    COMMIT;
END //

CREATE PROCEDURE getMenuItem(IN p_menu_item_id INT)
BEGIN
	SELECT * FROM menu_item
	WHERE menu_item_id = p_menu_item_id;
END //

CREATE PROCEDURE duplicateMenuItem(IN p_menu_item_id INT, IN p_last_log_by INT, OUT p_new_menu_item_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateMenuItemOptions()
BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
	ORDER BY menu_item_name;
END //

CREATE PROCEDURE generateMenuItemTable()
BEGIN
	SELECT menu_item_id, menu_item_name, menu_group_id, parent_id, order_sequence 
    FROM menu_item
    ORDER BY menu_item_id;
END //

CREATE PROCEDURE generateSubMenuItemTable(IN p_parent_id INT)
BEGIN
	SELECT menu_item_name, menu_group_id, order_sequence 
    FROM menu_item
    WHERE parent_id = p_parent_id
    ORDER BY menu_item_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Menu Item Access Right Table Stored Procedures */

CREATE PROCEDURE checkRoleMenuAccessExist(IN p_menu_item_id INT, IN p_role_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM menu_item_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END //

CREATE PROCEDURE insertRoleMenuAccess(IN p_menu_item_id INT, IN p_role_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO menu_item_access_right (menu_item_id, role_id, last_log_by) 
	VALUES(p_menu_item_id, p_role_id, p_last_log_by);
END //

CREATE PROCEDURE updateRoleMenuAccess(IN p_menu_item_id INT, IN p_role_id INT, IN p_access_type VARCHAR(10), IN p_access TINYINT(1), IN p_last_log_by INT)
BEGIN
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
END //

CREATE PROCEDURE checkMenuItemAccessRights(IN p_user_id INT, IN p_menu_item_id INT, IN p_access_type VARCHAR(10))
BEGIN
	IF p_access_type = 'read' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where read_access = 1 AND menu_item_id = p_menu_item_id);
    ELSEIF p_access_type = 'write' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where write_access = 1 AND menu_item_id = p_menu_item_id);
    ELSEIF p_access_type = 'create' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where create_access = 1 AND menu_item_id = p_menu_item_id);
    ELSEIF p_access_type = 'delete' THEN
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where delete_access = 1 AND menu_item_id = p_menu_item_id);
    ELSE
        SELECT COUNT(role_id) AS total
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_item_access_right where duplicate_access = 1 AND menu_item_id = p_menu_item_id);
    END IF;
END //

CREATE PROCEDURE getRoleMenuAccess(IN p_menu_item_id INT, IN p_role_id INT)
BEGIN
    SELECT read_access, write_access, create_access, delete_access, duplicate_access
    FROM menu_item_access_right 
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END //

CREATE PROCEDURE deleteMenuItemRoleAccess(IN p_menu_item_id INT, IN p_role_id INT)
BEGIN
	DELETE FROM menu_item_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END //

CREATE PROCEDURE buildMenuGroup(IN p_user_id INT)
BEGIN
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
END //

CREATE PROCEDURE buildMenuItem(IN p_user_id INT, IN p_menu_group_id INT)
BEGIN
    SELECT mi.menu_item_id, mi.menu_item_name, mi.menu_group_id, mi.menu_item_url, mi.parent_id, mi.menu_item_icon
    FROM menu_item AS mi
    INNER JOIN menu_item_access_right AS mar ON mi.menu_item_id = mar.menu_item_id
    INNER JOIN role_users AS ru ON mar.role_id = ru.role_id
    WHERE mar.read_access = 1 AND ru.user_id = p_user_id AND mi.menu_group_id = p_menu_group_id
    ORDER BY mi.order_sequence;
END //

CREATE PROCEDURE generateMenuItemRoleAccessTable(IN p_menu_item_id INT)
BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM menu_item_access_right WHERE menu_item_id = p_menu_item_id)
    ORDER BY role_name;
END //

CREATE PROCEDURE generateRoleMenuItemAccessTable(IN p_role_id INT)
BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    WHERE menu_item_id IN (SELECT menu_item_id FROM menu_item_access_right WHERE role_id = p_role_id)
    ORDER BY menu_item_name;
END //

CREATE PROCEDURE generateShortcutMenuItemRoleTable()
BEGIN
	SELECT role_id, role_name FROM role
    ORDER BY role_name;
END //

CREATE PROCEDURE generateAddMenuItemRoleAccessTable(IN p_menu_item_id INT)
BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM menu_item_access_right WHERE menu_item_id = p_menu_item_id)
    ORDER BY role_name;
END //

CREATE PROCEDURE generateAddRoleMenuItemAccessTable(IN p_role_id INT)
BEGIN
	SELECT menu_item_id, menu_item_name FROM menu_item
    WHERE menu_item_id NOT IN (SELECT menu_item_id FROM menu_item_access_right WHERE role_id = p_role_id)
    ORDER BY menu_item_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Action Table Stored Procedures */

CREATE PROCEDURE checkSystemActionExist (IN p_system_action_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM system_action
    WHERE system_action_id = p_system_action_id;
END //

CREATE PROCEDURE insertSystemAction(IN p_system_action_name VARCHAR(100), IN p_last_log_by INT, OUT p_system_action_id INT)
BEGIN
    INSERT INTO system_action (system_action_name, last_log_by) 
	VALUES(p_system_action_name, p_last_log_by);
	
    SET p_system_action_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateSystemAction(IN p_system_action_id INT, IN p_system_action_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE system_action
    SET system_action_name = p_system_action_name,
    last_log_by = p_last_log_by
    WHERE system_action_id = p_system_action_id;
END //

CREATE PROCEDURE deleteSystemAction(IN p_system_action_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM system_action_access_rights WHERE system_action_id = p_system_action_id;
    DELETE FROM system_action WHERE system_action_id = p_system_action_id;

    COMMIT;
END //

CREATE PROCEDURE getSystemAction(IN p_system_action_id INT)
BEGIN
	SELECT * FROM system_action
    WHERE system_action_id = p_system_action_id;
END //

CREATE PROCEDURE duplicateSystemAction(IN p_system_action_id INT, IN p_last_log_by INT, OUT p_new_system_action_id INT)
BEGIN
    DECLARE p_system_action_name VARCHAR(100);
    
    SELECT system_action_name 
    INTO p_system_action_name
    FROM system_action 
    WHERE system_action_id = p_system_action_id;
    
    INSERT INTO system_action (system_action_name, last_log_by) 
    VALUES(p_system_action_name, p_last_log_by);
    
    SET p_new_system_action_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateSystemActionTable()
BEGIN
	SELECT system_action_id, system_action_name 
    FROM system_action
    ORDER BY system_action_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Action Access Rights Table Stored Procedures */

CREATE PROCEDURE checkSystemActionAccessRights(IN p_user_id INT, IN p_system_action_id INT)
BEGIN
    SELECT COUNT(role_id) AS total
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_access = 1 AND role_id IN (SELECT role_id FROM role_users WHERE user_id = p_user_id);
END //

CREATE PROCEDURE insertRoleSystemActionAccess(IN p_system_action_id INT, IN p_role_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO system_action_access_rights (system_action_id, role_id, last_log_by) 
	VALUES(p_system_action_id, p_role_id, p_last_log_by);
END //

CREATE PROCEDURE checkSystemActionRoleExist(IN p_system_action_id INT, IN p_role_id INT)
BEGIN
	SELECT COUNT(*) AS total 
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END //

CREATE PROCEDURE generateSystemActionRoleAccessTable(IN p_system_action_id INT)
BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id IN (SELECT role_id FROM system_action_access_rights WHERE system_action_id = p_system_action_id)
    ORDER BY role_name;
END //

CREATE PROCEDURE generateRoleSystemActionAccessTable(IN p_role_id INT)
BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END //

CREATE PROCEDURE generateAddSystemActionRoleAccessTable(IN p_system_action_id INT)
BEGIN
	SELECT role_id, role_name FROM role
    WHERE role_id NOT IN (SELECT role_id FROM system_action_access_rights WHERE system_action_id = p_system_action_id)
    ORDER BY role_name;
END //

CREATE PROCEDURE generateAddRoleSystemActionAccessTable(IN p_role_id INT)
BEGIN
	SELECT system_action_id, system_action_name FROM system_action
    WHERE system_action_id NOT IN (SELECT system_action_id FROM system_action_access_rights WHERE role_id = p_role_id)
    ORDER BY system_action_name;
END //

CREATE PROCEDURE getRoleSystemActionAccess(IN p_system_action_id INT, IN p_role_id INT)
BEGIN
    SELECT role_access
    FROM system_action_access_rights 
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END //

CREATE PROCEDURE updateRoleSystemActionAccess(IN p_system_action_id INT, IN p_role_id INT, IN p_access TINYINT(1), IN p_last_log_by INT)
BEGIN
    UPDATE system_action_access_rights
    SET role_access = p_access,
    last_log_by = p_last_log_by
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END //

CREATE PROCEDURE deleteSystemActionRoleAccess(IN p_system_action_id INT, IN p_role_id INT)
BEGIN
	DELETE FROM system_action_access_rights
    WHERE system_action_id = p_system_action_id AND role_id = p_role_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* File Type Table Stored Procedures */

CREATE PROCEDURE checkFileTypeExist (IN p_file_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM file_type
    WHERE file_type_id = p_file_type_id;
END //

CREATE PROCEDURE insertFileType(IN p_file_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_file_type_id INT)
BEGIN
    INSERT INTO file_type (file_type_name, last_log_by) 
	VALUES(p_file_type_name, p_last_log_by);
	
    SET p_file_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateFileType(IN p_file_type_id INT, IN p_file_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE file_type
    SET file_type_name = p_file_type_name,
    last_log_by = p_last_log_by
    WHERE file_type_id = p_file_type_id;
END //

CREATE PROCEDURE deleteFileType(IN p_file_type_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM file_extension WHERE file_type_id = p_file_type_id;
    DELETE FROM file_type WHERE file_type_id = p_file_type_id;

    COMMIT;
END //

CREATE PROCEDURE getFileType(IN p_file_type_id INT)
BEGIN
	SELECT * FROM file_type
    WHERE file_type_id = p_file_type_id;
END //

CREATE PROCEDURE duplicateFileType(IN p_file_type_id INT, IN p_last_log_by INT, OUT p_new_file_type_id INT)
BEGIN
    DECLARE p_file_type_name VARCHAR(100);
    
    SELECT file_type_name 
    INTO p_file_type_name
    FROM file_type 
    WHERE file_type_id = p_file_type_id;
    
    INSERT INTO file_type (file_type_name, last_log_by) 
    VALUES(p_file_type_name, p_last_log_by);
    
    SET p_new_file_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateFileTypeTable()
BEGIN
	SELECT file_type_id, file_type_name 
    FROM file_type
    ORDER BY file_type_id;
END //

CREATE PROCEDURE generateFileTypeOptions()
BEGIN
	SELECT file_type_id, file_type_name FROM file_type
	ORDER BY file_type_name;
END //

CREATE PROCEDURE generateLeasingApplicationOptions()
BEGIN
	SELECT leasing_application_id, leasing_application_number, tenant_name FROM leasing_application
    LEFT OUTER JOIN tenant ON tenant.tenant_id = leasing_application.tenant_id
    WHERE application_status = 'Active'
	ORDER BY tenant_name;
END //

CREATE PROCEDURE generateLeasingApplicationUnpaidRepaymentOptions(IN p_collection_id INT)
BEGIN
	SELECT leasing_application_repayment_id, reference, due_date, tenant_name, unpaid_rental FROM leasing_application_repayment
    LEFT OUTER JOIN leasing_application ON leasing_application.leasing_application_id = leasing_application_repayment.leasing_application_id
    LEFT OUTER JOIN tenant ON tenant.tenant_id = leasing_application.tenant_id
    WHERE ((application_status = 'Active' AND unpaid_rental > 0 AND repayment_status = 'Unpaid' OR repayment_status = 'Partially Paid' ) OR leasing_application_repayment_id IN (SELECT leasing_application_repayment_id FROM loan_collections WHERE loan_collection_id = p_collection_id))
	ORDER BY reference;
END //

CREATE PROCEDURE generateLeasingApplicationUnpaidOtherChargesOptions(IN p_collection_id INT)
BEGIN
	SELECT leasing_other_charges_id, reference, other_charges_type, leasing_other_charges.due_date AS due_date, tenant_name, leasing_other_charges.outstanding_balance AS outstanding_balance FROM leasing_other_charges
    LEFT OUTER JOIN leasing_application_repayment ON leasing_application_repayment.leasing_application_repayment_id = leasing_other_charges.leasing_application_repayment_id
    LEFT OUTER JOIN leasing_application ON leasing_application.leasing_application_id = leasing_other_charges.leasing_application_id
    LEFT OUTER JOIN tenant ON tenant.tenant_id = leasing_application.tenant_id
    WHERE ((application_status = 'Active' AND leasing_other_charges.outstanding_balance > 0 AND repayment_status = 'Unpaid' OR repayment_status = 'Partially Paid') OR leasing_other_charges_id IN (SELECT leasing_other_charges_id FROM loan_collections WHERE loan_collection_id = p_collection_id))
	ORDER BY reference;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table Stored Procedures */

CREATE PROCEDURE checkFileExtensionExist(IN p_file_extension_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM file_extension
    WHERE file_extension_id = p_file_extension_id;
END //

CREATE PROCEDURE insertFileExtension(IN p_file_extension_name VARCHAR(100), IN p_file_type_id INT, IN p_last_log_by INT, OUT p_file_extension_id INT)
BEGIN
    INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) 
	VALUES(p_file_extension_name, p_file_type_id, p_last_log_by);
	
    SET p_file_extension_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateFileExtension(IN p_file_extension_id INT, IN p_file_extension_name VARCHAR(100), IN p_file_type_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE file_extension
    SET file_extension_name = p_file_extension_name,
    file_type_id = p_file_type_id,
    last_log_by = p_last_log_by
    WHERE file_extension_id = p_file_extension_id;
END //

CREATE PROCEDURE deleteFileExtension(IN p_file_extension_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	DELETE FROM upload_setting_file_extension WHERE file_extension_id = p_file_extension_id;
    DELETE FROM file_extension WHERE file_extension_id = p_file_extension_id;

    COMMIT;
END //

CREATE PROCEDURE getFileExtension(IN p_file_extension_id INT)
BEGIN
	SELECT * FROM file_extension
	WHERE file_extension_id = p_file_extension_id;
END //

CREATE PROCEDURE duplicateFileExtension(IN p_file_extension_id INT, IN p_last_log_by INT, OUT p_new_file_extension_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateFileExtensionOptions()
BEGIN
	SELECT file_extension_id, file_extension_name FROM file_extension
	ORDER BY file_extension_name;
END //

CREATE PROCEDURE generateFileExtensionTable()
BEGIN
	SELECT file_extension_id, file_extension_name, file_type_id 
    FROM file_extension
    ORDER BY file_extension_id;
END //

CREATE PROCEDURE generateFileTypeFileExensionTable(IN p_file_type_id INT)
BEGIN
	SELECT file_extension_id, file_extension_name 
    FROM file_extension
    WHERE file_type_id = p_file_type_id 
    ORDER BY file_extension_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Upload Setting Table Stored Procedures */

CREATE PROCEDURE checkUploadSettingExist (IN p_upload_setting_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM upload_setting
    WHERE upload_setting_id = p_upload_setting_id;
END //

CREATE PROCEDURE insertUploadSetting(IN p_upload_setting_name VARCHAR(100), IN p_upload_setting_description VARCHAR(200), IN p_max_file_size DOUBLE, IN p_last_log_by INT, OUT p_upload_setting_id INT)
BEGIN
    INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) 
	VALUES(p_upload_setting_name, p_upload_setting_description, p_max_file_size, p_last_log_by);
	
    SET p_upload_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateUploadSetting(IN p_upload_setting_id INT, IN p_upload_setting_name VARCHAR(100), IN p_upload_setting_description VARCHAR(200), IN p_max_file_size DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE upload_setting
    SET upload_setting_name = p_upload_setting_name,
    upload_setting_description = p_upload_setting_description,
    max_file_size = p_max_file_size,
    last_log_by = p_last_log_by
    WHERE upload_setting_id = p_upload_setting_id;
END //

CREATE PROCEDURE deleteUploadSetting(IN p_upload_setting_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	DELETE FROM upload_setting_file_extension WHERE upload_setting_id = p_upload_setting_id;
    DELETE FROM upload_setting WHERE upload_setting_id = p_upload_setting_id;

    COMMIT;
END //

CREATE PROCEDURE getUploadSetting(IN p_upload_setting_id INT)
BEGIN
	SELECT * FROM upload_setting
    WHERE upload_setting_id = p_upload_setting_id;
END //

CREATE PROCEDURE duplicateUploadSetting(IN p_upload_setting_id INT, IN p_last_log_by INT, OUT p_new_upload_setting_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateUploadSettingTable()
BEGIN
	SELECT upload_setting_id, upload_setting_name, upload_setting_description, max_file_size
    FROM upload_setting
    ORDER BY upload_setting_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Upload Setting File Extension Table Stored Procedures */

CREATE PROCEDURE checkUploadSettingFileExtensionExist (IN p_upload_setting_id INT, IN p_file_extension_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id AND file_extension_id = p_file_extension_id;
END //

CREATE PROCEDURE insertUploadSettingFileExtension(IN p_upload_setting_id INT, IN p_file_extension_id INT)
BEGIN
    INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) 
	VALUES(p_upload_setting_id, p_file_extension_id);
END //

CREATE PROCEDURE deleteUploadSettingFileExtension(IN p_upload_setting_id INT, IN p_file_extension_id INT)
BEGIN
	DELETE FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id AND file_extension_id = p_file_extension_id;
END //

CREATE PROCEDURE generateUploadSettingFileExensionTable(IN p_upload_setting_id INT)
BEGIN
	SELECT file_extension_id, file_extension_name 
    FROM file_extension
    WHERE file_extension_id IN (SELECT file_extension_id FROM upload_setting_file_extension WHERE upload_setting_id = p_upload_setting_id)
    ORDER BY file_extension_id;
END //

CREATE PROCEDURE generateAddUploadSettingFileExensionTable(IN p_upload_setting_id INT)
BEGIN
	SELECT file_extension_id, file_extension_name 
    FROM file_extension
    WHERE file_extension_id NOT IN (SELECT file_extension_id FROM upload_setting_file_extension WHERE upload_setting_id = p_upload_setting_id)
    ORDER BY file_extension_id;
END //

CREATE PROCEDURE getUploadSettingFileExtension(IN p_upload_setting_id INT)
BEGIN
	SELECT * FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Interface Setting Table Stored Procedures */

CREATE PROCEDURE checkInterfaceSettingExist (IN p_interface_setting_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END //

CREATE PROCEDURE insertInterfaceSetting(IN p_interface_setting_name VARCHAR(100), IN p_interface_setting_description VARCHAR(200), IN p_last_log_by INT, OUT p_interface_setting_id INT)
BEGIN
    INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) 
	VALUES(p_interface_setting_name, p_interface_setting_description, p_last_log_by);
	
    SET p_interface_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateInterfaceSetting(IN p_interface_setting_id INT, IN p_interface_setting_name VARCHAR(100), IN p_interface_setting_description VARCHAR(200), IN p_last_log_by INT)
BEGIN
	UPDATE interface_setting
    SET interface_setting_name = p_interface_setting_name,
    interface_setting_description = p_interface_setting_description,
    last_log_by = p_last_log_by
    WHERE interface_setting_id = p_interface_setting_id;
END //

CREATE PROCEDURE updateInterfaceSettingValue(IN p_interface_setting_id INT, IN p_value VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE interface_setting
    SET value = p_value,
    last_log_by = p_last_log_by
    WHERE interface_setting_id = p_interface_setting_id;
END //

CREATE PROCEDURE deleteInterfaceSetting(IN p_interface_setting_id INT)
BEGIN
	DELETE FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END //

CREATE PROCEDURE getInterfaceSetting(IN p_interface_setting_id INT)
BEGIN
	SELECT * FROM interface_setting
    WHERE interface_setting_id = p_interface_setting_id;
END //

CREATE PROCEDURE duplicateInterfaceSetting(IN p_interface_setting_id INT, IN p_last_log_by INT, OUT p_new_interface_setting_id INT)
BEGIN
    DECLARE p_interface_setting_name VARCHAR(100);
    DECLARE p_interface_setting_description VARCHAR(200);
    
    SELECT interface_setting_name, interface_setting_description
    INTO p_interface_setting_name, p_interface_setting_description
    FROM interface_setting 
    WHERE interface_setting_id = p_interface_setting_id;
    
    INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) 
    VALUES(p_interface_setting_name, p_interface_setting_description, p_last_log_by);
    
    SET p_new_interface_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateInterfaceSettingTable()
BEGIN
	SELECT interface_setting_id, interface_setting_name, interface_setting_description, value
    FROM interface_setting
    ORDER BY interface_setting_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Setting Table Stored Procedures */

CREATE PROCEDURE checkSystemSettingExist (IN p_system_setting_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM system_setting
    WHERE system_setting_id = p_system_setting_id;
END //

CREATE PROCEDURE insertSystemSetting(IN p_system_setting_name VARCHAR(100), IN p_system_setting_description VARCHAR(200), IN p_value VARCHAR(1000), IN p_last_log_by INT, OUT p_system_setting_id INT)
BEGIN
    INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) 
	VALUES(p_system_setting_name, p_system_setting_description, p_value, p_last_log_by);
	
    SET p_system_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateSystemSetting(IN p_system_setting_id INT, IN p_system_setting_name VARCHAR(100), IN p_system_setting_description VARCHAR(200), IN p_value VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE system_setting
    SET system_setting_name = p_system_setting_name,
    system_setting_description = p_system_setting_description,
    value = p_value,
    last_log_by = p_last_log_by
    WHERE system_setting_id = p_system_setting_id;
END //

CREATE PROCEDURE updateSystemSettingValue(IN p_system_setting_id INT, IN p_value VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE system_setting
    SET value = p_value,
    last_log_by = p_last_log_by
    WHERE system_setting_id = p_system_setting_id;
END //

CREATE PROCEDURE deleteSystemSetting(IN p_system_setting_id INT)
BEGIN
	DELETE FROM system_setting
    WHERE system_setting_id = p_system_setting_id;
END //

CREATE PROCEDURE getSystemSetting(IN p_system_setting_id INT)
BEGIN
	SELECT * FROM system_setting
    WHERE system_setting_id = p_system_setting_id;
END //

CREATE PROCEDURE duplicateSystemSetting(IN p_system_setting_id INT, IN p_last_log_by INT, OUT p_new_system_setting_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateSystemSettingTable()
BEGIN
	SELECT system_setting_id, system_setting_name, system_setting_description, value
    FROM system_setting
    ORDER BY system_setting_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Country Table Stored Procedures */

CREATE PROCEDURE checkCountryExist (IN p_country_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM country
    WHERE country_id = p_country_id;
END //

CREATE PROCEDURE insertCountry(IN p_country_name VARCHAR(100), IN p_country_code VARCHAR(5), IN p_phone_code VARCHAR(20), IN p_last_log_by INT, OUT p_country_id INT)
BEGIN
    INSERT INTO country (country_name, country_code, phone_code, last_log_by) 
	VALUES(p_country_name, p_country_code, p_phone_code, p_last_log_by);
	
    SET p_country_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateCountry(IN p_country_id INT, IN p_country_name VARCHAR(100), IN p_country_code VARCHAR(5), IN p_phone_code VARCHAR(20), IN p_last_log_by INT)
BEGIN
	UPDATE country
    SET country_name = p_country_name,
    country_code = p_country_code,
    phone_code = p_phone_code,
    last_log_by = p_last_log_by
    WHERE country_id = p_country_id;
END //

CREATE PROCEDURE deleteCountry(IN p_country_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM city WHERE state_id IN (SELECT state_id FROM state WHERE country_id = p_country_id);
    DELETE FROM state WHERE country_id = p_country_id;
	DELETE FROM country WHERE country_id = p_country_id;

    COMMIT;
END //

CREATE PROCEDURE getCountry(IN p_country_id INT)
BEGIN
	SELECT * FROM country
    WHERE country_id = p_country_id;
END //

CREATE PROCEDURE duplicateCountry(IN p_country_id INT, IN p_last_log_by INT, OUT p_new_country_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateCountryTable()
BEGIN
	SELECT country_id, country_name, country_code
    FROM country
    ORDER BY country_id;
END //

CREATE PROCEDURE generateCountryOptions()
BEGIN
	SELECT country_id, country_name FROM country
	ORDER BY country_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* State Table Stored Procedures */

CREATE PROCEDURE checkStateExist (IN p_state_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM state
    WHERE state_id = p_state_id;
END //

CREATE PROCEDURE insertState(IN p_state_name VARCHAR(100), IN p_country_id INT, IN p_state_code VARCHAR(5), IN p_last_log_by INT, OUT p_state_id INT)
BEGIN
    INSERT INTO state (state_name, country_id, state_code, last_log_by) 
	VALUES(p_state_name, p_country_id, p_state_code, p_last_log_by);
	
    SET p_state_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateState(IN p_state_id INT, IN p_state_name VARCHAR(100), IN p_country_id INT, IN p_state_code VARCHAR(5), IN p_last_log_by INT)
BEGIN
	UPDATE state
    SET state_name = p_state_name,
    country_id = p_country_id,
    state_code = p_state_code,
    last_log_by = p_last_log_by
    WHERE state_id = p_state_id;
END //

CREATE PROCEDURE deleteState(IN p_state_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM city WHERE state_id = p_state_id;
    DELETE FROM state WHERE state_id = p_state_id;

    COMMIT;
END //

CREATE PROCEDURE getState(IN p_state_id INT)
BEGIN
	SELECT * FROM state
    WHERE state_id = p_state_id;
END //

CREATE PROCEDURE duplicateState(IN p_state_id INT, IN p_last_log_by INT, OUT p_new_state_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateStateTable(IN p_country_id VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT state_id, state_name, country_id FROM state';
    
    SET conditionList = '';

    IF p_country_id IS NOT NULL AND p_country_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' WHERE country_id IN (');
        SET conditionList = CONCAT(conditionList, p_country_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY state_id');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateStateOptions()
BEGIN
	SELECT
        s.state_id AS state_id,
        s.state_name AS state_name,
        c.country_name AS country_name
    FROM state s
    INNER JOIN country c ON s.country_id = c.country_id
	ORDER BY s.state_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* City Table Stored Procedures */

CREATE PROCEDURE checkCityExist (IN p_city_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM city
    WHERE city_id = p_city_id;
END //

CREATE PROCEDURE insertCity(IN p_city_name VARCHAR(100), IN p_state_id INT, IN p_last_log_by INT, OUT p_city_id INT)
BEGIN
    INSERT INTO city (city_name, state_id, last_log_by) 
	VALUES(p_city_name, p_state_id, p_last_log_by);
	
    SET p_city_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateCity(IN p_city_id INT, IN p_city_name VARCHAR(100), IN p_state_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE city
    SET city_name = p_city_name,
    state_id = p_state_id,
    last_log_by = p_last_log_by
    WHERE city_id = p_city_id;
END //

CREATE PROCEDURE deleteCity(IN p_city_id INT)
BEGIN
	DELETE FROM city
    WHERE city_id = p_city_id;
END //

CREATE PROCEDURE getCity(IN p_city_id INT)
BEGIN
	SELECT * FROM city
    WHERE city_id = p_city_id;
END //

CREATE PROCEDURE duplicateCity(IN p_city_id INT, IN p_last_log_by INT, OUT p_new_city_id INT)
BEGIN
    DECLARE p_city_name VARCHAR(100);
    DECLARE p_state_id INT;
    
    SELECT city_name, state_id
    INTO p_city_name, p_state_id
    FROM city 
    WHERE city_id = p_city_id;
    
    INSERT INTO city (city_name, state_id, last_log_by) 
    VALUES(p_city_name, p_state_id, p_last_log_by);
    
    SET p_new_city_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateCityTable(IN p_state_id VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT city_id, city_name, state_id FROM city';
    
    SET conditionList = '';

    IF p_state_id IS NOT NULL AND p_state_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' WHERE state_id IN (');
        SET conditionList = CONCAT(conditionList, p_state_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY city_name');
    
    SET query = CONCAT(query, ';');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateCityOptions()
BEGIN
	SELECT 
        ct.city_id AS city_id, 
        ct.city_name AS city_name,
        cy.country_name AS country_name,
        s.state_name AS state_name
    FROM city ct
    INNER JOIN state s ON s.state_id = ct.state_id
    INNER JOIN country cy ON cy.country_id = s.country_id
	ORDER BY city_name;
END //

CREATE PROCEDURE generateCityCheckbox(IN p_generation_type VARCHAR(50))
BEGIN
    IF p_generation_type = 'branch' THEN
        SELECT 
        ct.city_id AS city_id, 
        ct.city_name AS city_name,
        cy.country_name AS country_name,
        s.state_name AS state_name
        FROM city ct
        INNER JOIN state s ON s.state_id = ct.state_id
        INNER JOIN country cy ON cy.country_id = s.country_id
        WHERE city_id IN (SELECT city_id FROM branch)
        ORDER BY city_name;
    ELSEIF p_generation_type = 'warehouse' THEN
        SELECT 
        ct.city_id AS city_id, 
        ct.city_name AS city_name,
        cy.country_name AS country_name,
        s.state_name AS state_name
        FROM city ct
        INNER JOIN state s ON s.state_id = ct.state_id
        INNER JOIN country cy ON cy.country_id = s.country_id
        WHERE city_id IN (SELECT city_id FROM warehouse)
        ORDER BY city_name;
    ELSEIF p_generation_type = 'company' THEN
        SELECT 
        ct.city_id AS city_id, 
        ct.city_name AS city_name,
        cy.country_name AS country_name,
        s.state_name AS state_name
        FROM city ct
        INNER JOIN state s ON s.state_id = ct.state_id
        INNER JOIN country cy ON cy.country_id = s.country_id
        WHERE city_id IN (SELECT city_id FROM company)
        ORDER BY city_name;
    ELSE
        SELECT 
        ct.city_id AS city_id, 
        ct.city_name AS city_name,
        cy.country_name AS country_name,
        s.state_name AS state_name
        FROM city ct
        INNER JOIN state s ON s.state_id = ct.state_id
        INNER JOIN country cy ON cy.country_id = s.country_id
        ORDER BY city_name;
    END IF;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Currency Table Stored Procedures */

CREATE PROCEDURE checkCurrencyExist (IN p_currency_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM currency
    WHERE currency_id = p_currency_id;
END //

CREATE PROCEDURE insertCurrency(IN p_currency_name VARCHAR(100), IN p_symbol VARCHAR(10), IN p_shorthand VARCHAR(10), IN p_last_log_by INT, OUT p_currency_id INT)
BEGIN
    INSERT INTO currency (currency_name, symbol, shorthand, last_log_by) 
	VALUES(p_currency_name, p_symbol, p_shorthand, p_last_log_by);
	
    SET p_currency_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateCurrency(IN p_currency_id INT, IN p_currency_name VARCHAR(100), IN p_symbol VARCHAR(10), IN p_shorthand VARCHAR(10), IN p_last_log_by INT)
BEGIN
	UPDATE currency
    SET currency_name = p_currency_name,
    symbol = p_symbol,
    shorthand = p_shorthand,
    last_log_by = p_last_log_by
    WHERE currency_id = p_currency_id;
END //

CREATE PROCEDURE deleteCurrency(IN p_currency_id INT)
BEGIN
	DELETE FROM currency
    WHERE currency_id = p_currency_id;
END //

CREATE PROCEDURE getCurrency(IN p_currency_id INT)
BEGIN
	SELECT * FROM currency
    WHERE currency_id = p_currency_id;
END //

CREATE PROCEDURE duplicateCurrency(IN p_currency_id INT, IN p_last_log_by INT, OUT p_new_currency_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateCurrencyTable()
BEGIN
    SELECT currency_id, currency_name, symbol, shorthand
    FROM currency
    ORDER BY currency_id;
END //

CREATE PROCEDURE generateCurrencyOptions()
BEGIN
	SELECT currency_id, currency_name, shorthand
    FROM currency
	ORDER BY currency_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Company Table Stored Procedures */

CREATE PROCEDURE checkCompanyExist (IN p_company_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM company
    WHERE company_id = p_company_id;
END //

CREATE PROCEDURE insertCompany(IN p_company_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_tax_id VARCHAR(500), IN p_currency_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_website VARCHAR(500), IN p_last_log_by INT, OUT p_company_id INT)
BEGIN
    INSERT INTO company (company_name, address, city_id, tax_id, currency_id, phone, mobile, telephone, email, website, last_log_by) 
	VALUES(p_company_name, p_address, p_city_id, p_tax_id, p_currency_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
	
    SET p_company_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateCompany(IN p_company_id INT, IN p_company_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_tax_id VARCHAR(500), IN p_currency_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_website VARCHAR(500), IN p_last_log_by INT)
BEGIN
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
END //

CREATE PROCEDURE updateCompanyLogo(IN p_company_id INT, IN p_company_logo VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE company
    SET company_logo = p_company_logo,
    last_log_by = p_last_log_by
    WHERE company_id = p_company_id;
END //

CREATE PROCEDURE deleteCompany(IN p_company_id INT)
BEGIN
	DELETE FROM company
    WHERE company_id = p_company_id;
END //

CREATE PROCEDURE getCompany(IN p_company_id INT)
BEGIN
	SELECT * FROM company
    WHERE company_id = p_company_id;
END //

CREATE PROCEDURE duplicateCompany(IN p_company_id INT, IN p_last_log_by INT, OUT p_new_company_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateCompanyTable(IN p_city_id VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT company_id, company_name, company_logo, address, city_id FROM company';
    
    SET conditionList = ' WHERE 1';

    IF p_city_id IS NOT NULL AND p_city_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND city_id IN (');
        SET conditionList = CONCAT(conditionList, p_city_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY company_name;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateCompanyReferenceTable()
BEGIN
    SELECT company_id, company_name
    FROM company
    ORDER BY company_name;
END //

CREATE PROCEDURE generateCompanyOptions()
BEGIN
	SELECT company_id, company_name FROM company
	ORDER BY company_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Email Setting Table Stored Procedures */

CREATE PROCEDURE checkEmailSettingExist (IN p_email_setting_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM email_setting
    WHERE email_setting_id = p_email_setting_id;
END //

CREATE PROCEDURE insertEmailSetting(IN p_email_setting_name VARCHAR(100), IN p_email_setting_description VARCHAR(200), IN p_mail_host VARCHAR(100), IN p_port INT, IN p_smtp_auth INT(1), IN p_smtp_auto_tls INT(1), IN p_mail_username VARCHAR(200), IN p_mail_encryption VARCHAR(20), IN p_mail_from_name VARCHAR(200), IN p_mail_from_email VARCHAR(200), IN p_last_log_by INT, OUT p_email_setting_id INT)
BEGIN
    INSERT INTO email_setting (email_setting_name, email_setting_description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_encryption, mail_from_name, mail_from_email, last_log_by) 
	VALUES(p_email_setting_name, p_email_setting_description, p_mail_host, p_port, p_smtp_auth, p_smtp_auto_tls, p_mail_username, p_mail_encryption, p_mail_from_name, p_mail_from_email, p_last_log_by);
	
    SET p_email_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateEmailSetting(IN p_email_setting_id INT, IN p_email_setting_name VARCHAR(100), IN p_email_setting_description VARCHAR(200), IN p_mail_host VARCHAR(100), IN p_port INT, IN p_smtp_auth INT(1), IN p_smtp_auto_tls INT(1), IN p_mail_username VARCHAR(200), IN p_mail_encryption VARCHAR(20), IN p_mail_from_name VARCHAR(200), IN p_mail_from_email VARCHAR(200), IN p_last_log_by INT)
BEGIN
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
END //

CREATE PROCEDURE updateMailPassword(IN p_email_setting_id INT, IN p_mail_password VARCHAR(250), IN p_last_log_by INT)
BEGIN
	UPDATE email_setting
    SET mail_password = p_mail_password,
    last_log_by = p_last_log_by
    WHERE email_setting_id = p_email_setting_id;
END //

CREATE PROCEDURE deleteEmailSetting(IN p_email_setting_id INT)
BEGIN
	DELETE FROM email_setting
    WHERE email_setting_id = p_email_setting_id;
END //

CREATE PROCEDURE getEmailSetting(IN p_email_setting_id INT)
BEGIN
	SELECT * FROM email_setting
    WHERE email_setting_id = p_email_setting_id;
END //

CREATE PROCEDURE duplicateEmailSetting(IN p_email_setting_id INT, IN p_last_log_by INT, OUT p_new_email_setting_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateEmailSettingTable()
BEGIN
	SELECT email_setting_id, email_setting_name, email_setting_description
    FROM email_setting
    ORDER BY email_setting_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Notification Setting Table Stored Procedures */

CREATE PROCEDURE checkNotificationSettingExist (IN p_notification_setting_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id;
END //

CREATE PROCEDURE insertNotificationSetting(IN p_notification_setting_name VARCHAR(100), IN p_notification_setting_description VARCHAR(200), IN p_last_log_by INT, OUT p_notification_setting_id INT)
BEGIN
    INSERT INTO notification_setting (notification_setting_name, notification_setting_description, last_log_by) 
	VALUES(p_notification_setting_name, p_notification_setting_description, p_last_log_by);
	
    SET p_notification_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateNotificationSetting(IN p_notification_setting_id INT, IN p_notification_setting_name VARCHAR(100), IN p_notification_setting_description VARCHAR(200), IN p_last_log_by INT)
BEGIN
	UPDATE notification_setting
    SET notification_setting_name = p_notification_setting_name,
    notification_setting_description = p_notification_setting_description,
    last_log_by = p_last_log_by
    WHERE notification_setting_id = p_notification_setting_id;
END //

CREATE PROCEDURE deleteNotificationSetting(IN p_notification_setting_id INT)
BEGIN
	DELETE FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id;
END //

CREATE PROCEDURE getNotificationSetting(IN p_notification_setting_id INT)
BEGIN
	SELECT * FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id;
END //

CREATE PROCEDURE duplicateNotificationSetting(IN p_notification_setting_id INT, IN p_last_log_by INT, OUT p_new_notification_setting_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateNotificationSettingTable()
BEGIN
	SELECT notification_setting_id, notification_setting_name, notification_setting_description, system_notification, email_notification, sms_notification
    FROM notification_setting
    ORDER BY notification_setting_id;
END //

CREATE PROCEDURE generateUpdateNotificationChannelTable(IN p_notification_setting_id INT)
BEGIN
	SELECT system_notification, email_notification, sms_notification
    FROM notification_setting
    WHERE notification_setting_id = p_notification_setting_id
    ORDER BY notification_setting_id;
END //

CREATE PROCEDURE updateNotificationChannelStatus(IN p_notification_setting_id INT, IN p_channel VARCHAR(10), IN p_status TINYINT(1), IN p_last_log_by INT)
BEGIN
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
END //

CREATE PROCEDURE updateNotificationSettingTemplate(IN p_notification_setting_id INT, IN p_system_notification_title VARCHAR(200), IN p_system_notification_message VARCHAR(200), IN p_email_notification_subject VARCHAR(200), IN p_email_notification_body LONGTEXT, IN p_sms_notification_message VARCHAR(500), IN p_last_log_by INT)
BEGIN

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
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Zoom API Table Stored Procedures */

CREATE PROCEDURE checkZoomAPIExist (IN p_zoom_api_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM zoom_api
    WHERE zoom_api_id = zoom_api_id;
END //

CREATE PROCEDURE insertZoomAPI(IN p_zoom_api_name VARCHAR(100), IN p_zoom_api_description VARCHAR(200), IN p_api_key VARCHAR(1000), IN p_api_secret VARCHAR(1000), IN p_last_log_by INT, OUT p_zoom_api_id INT)
BEGIN
    INSERT INTO zoom_api (zoom_api_name, zoom_api_description, api_key, api_secret, last_log_by) 
	VALUES(p_zoom_api_name, p_zoom_api_description, p_api_key, p_api_secret, p_last_log_by);
	
    SET p_zoom_api_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateZoomAPI(IN p_zoom_api_id INT, IN p_zoom_api_name VARCHAR(100), IN p_zoom_api_description VARCHAR(200), IN p_api_key VARCHAR(1000), IN p_api_secret VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE zoom_api
    SET zoom_api_name = p_zoom_api_name,
    zoom_api_description = p_zoom_api_description,
    api_key = p_api_key,
    api_secret = p_api_secret,
    last_log_by = p_last_log_by
    WHERE zoom_api_id = p_zoom_api_id;
END //

CREATE PROCEDURE deleteZoomAPI(IN p_zoom_api_id INT)
BEGIN
	DELETE FROM zoom_api
    WHERE zoom_api_id = p_zoom_api_id;
END //

CREATE PROCEDURE getZoomAPI(IN p_zoom_api_id INT)
BEGIN
	SELECT * FROM zoom_api
    WHERE zoom_api_id = p_zoom_api_id;
END //

CREATE PROCEDURE duplicateZoomAPI(IN p_zoom_api_id INT, IN p_last_log_by INT, OUT p_new_zoom_api_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateZoomAPITable()
BEGIN
	SELECT zoom_api_id, zoom_api_name, zoom_api_description
    FROM zoom_api
    ORDER BY zoom_api_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Branch Table Stored Procedures */

CREATE PROCEDURE checkBranchExist (IN p_branch_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM branch
    WHERE branch_id = p_branch_id;
END //

CREATE PROCEDURE insertBranch(IN p_branch_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_company_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_website VARCHAR(500), IN p_last_log_by INT, OUT p_branch_id INT)
BEGIN
    INSERT INTO branch (branch_name, address, city_id, company_id, phone, mobile, telephone, email, website, last_log_by) 
	VALUES(p_branch_name, p_address, p_city_id, p_company_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
	
    SET p_branch_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBranch(IN p_branch_id INT, IN p_branch_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_company_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_website VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE branch
    SET branch_name = p_branch_name,
    branch_name = p_branch_name,
    address = p_address,
    city_id = p_city_id,
    company_id = p_company_id,
    phone = p_phone,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    website = p_website,
    last_log_by = p_last_log_by
    WHERE branch_id = p_branch_id;
END //

CREATE PROCEDURE deleteBranch(IN p_branch_id INT)
BEGIN
	DELETE FROM branch
    WHERE branch_id = p_branch_id;
END //

CREATE PROCEDURE getBranch(IN p_branch_id INT)
BEGIN
	SELECT * FROM branch
    WHERE branch_id = p_branch_id;
END //

CREATE PROCEDURE duplicateBranch(IN p_branch_id INT, IN p_last_log_by INT, OUT p_new_branch_id INT)
BEGIN
    DECLARE p_branch_name VARCHAR(100);
    DECLARE p_address VARCHAR(1000);
    DECLARE p_city_id INT;
    DECLARE p_company_id INT;
    DECLARE p_phone VARCHAR(20);
    DECLARE p_mobile VARCHAR(20);
    DECLARE p_telephone VARCHAR(20);
    DECLARE p_email VARCHAR(100);
    DECLARE p_website VARCHAR(500);
    
    SELECT branch_name, address, city_id, company_id, phone, mobile, telephone, email, website
    INTO p_branch_name, p_address, p_city_id, p_company_id, p_phone, p_mobile, p_telephone, p_email, p_website
    FROM branch 
    WHERE branch_id = p_branch_id;
    
    INSERT INTO branch (branch_name, address, city_id, company_id, phone, mobile, telephone, email, website, last_log_by) 
    VALUES(p_branch_name, p_address, p_city_id, p_company_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
    
    SET p_new_branch_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateBranchTable(IN p_company_id VARCHAR(500), IN p_city_id VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT branch_id, branch_name, address, city_id, company_id FROM branch';
    
    SET conditionList = ' WHERE 1';

    IF p_company_id IS NOT NULL AND p_company_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_company_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_city_id IS NOT NULL AND p_city_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND city_id IN (');
        SET conditionList = CONCAT(conditionList, p_city_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY branch_name;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateBranchOptions()
BEGIN
	SELECT branch_id, branch_name FROM branch
	ORDER BY branch_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Department Table Stored Procedures */

CREATE PROCEDURE checkDepartmentExist (IN p_department_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM department
    WHERE department_id = p_department_id;
END //

CREATE PROCEDURE insertDepartment(IN p_department_name VARCHAR(100), IN p_parent_department INT, IN p_manager INT, IN p_last_log_by INT, OUT p_department_id INT)
BEGIN
    INSERT INTO department (department_name, parent_department, manager, last_log_by) 
	VALUES(p_department_name, p_parent_department, p_manager, p_last_log_by);
	
    SET p_department_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateDepartment(IN p_department_id INT, IN p_department_name VARCHAR(100), IN p_parent_department INT, IN p_manager INT, IN p_last_log_by INT)
BEGIN
	UPDATE department
    SET department_name = p_department_name,
    department_name = p_department_name,
    parent_department = p_parent_department,
    manager = p_manager,
    last_log_by = p_last_log_by
    WHERE department_id = p_department_id;
END //

CREATE PROCEDURE deleteDepartment(IN p_department_id INT)
BEGIN
	DELETE FROM department
    WHERE department_id = p_department_id;
END //

CREATE PROCEDURE getDepartment(IN p_department_id INT)
BEGIN
	SELECT * FROM department
    WHERE department_id = p_department_id;
END //

CREATE PROCEDURE duplicateDepartment(IN p_department_id INT, IN p_last_log_by INT, OUT p_new_department_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateDepartmentTable()
BEGIN
    SELECT department_id, department_name, manager, parent_department
    FROM department
    ORDER BY department_id;
END //

CREATE PROCEDURE generateDepartmentOptions()
BEGIN
	SELECT department_id, department_name FROM department
	ORDER BY department_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Table Stored Procedures */

CREATE PROCEDURE checkJobPositionExist (IN p_job_position_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM job_position
    WHERE job_position_id = p_job_position_id;
END //

CREATE PROCEDURE insertJobPosition(IN p_job_position_name VARCHAR(100), IN p_job_position_description VARCHAR(2000), IN p_department_id INT, IN p_last_log_by INT, OUT p_job_position_id INT)
BEGIN
    INSERT INTO job_position (job_position_name, job_position_description, department_id, last_log_by) 
	VALUES(p_job_position_name, p_job_position_description, p_department_id, p_last_log_by);
	
    SET p_job_position_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateJobPosition(IN p_job_position_id INT, IN p_job_position_name VARCHAR(100), IN p_job_position_description VARCHAR(2000), IN p_department_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE job_position
    SET job_position_name = p_job_position_name,
    job_position_description = p_job_position_description,
    department_id = p_department_id,
    last_log_by = p_last_log_by
    WHERE job_position_id = p_job_position_id;
END //

CREATE PROCEDURE updateJobPositionRecruitmentStatus(IN p_job_position_id INT, IN p_recruitment_status TINYINT(1), IN p_expected_new_employees INT, IN p_last_log_by INT)
BEGIN
	UPDATE job_position
    SET recruitment_status = p_recruitment_status,
    expected_new_employees = p_expected_new_employees,
    last_log_by = p_last_log_by
    WHERE job_position_id = p_job_position_id;
END //

CREATE PROCEDURE deleteJobPosition(IN p_job_position_id INT)
BEGIN
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
END //

CREATE PROCEDURE getJobPosition(IN p_job_position_id INT)
BEGIN
	SELECT * FROM job_position
    WHERE job_position_id = p_job_position_id;
END //

CREATE PROCEDURE duplicateJobPosition(IN p_job_position_id INT, IN p_last_log_by INT, OUT p_new_job_position_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateJobPositionTable(IN p_filter_recruitment_status ENUM('active', 'inactive', 'all'), IN p_department_id VARCHAR(500))
BEGIN
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

    IF p_department_id IS NOT NULL AND p_department_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND department_id IN (');
        SET conditionList = CONCAT(conditionList, p_department_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY job_position_name;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateJobPositionOptions()
BEGIN
	SELECT job_position_id, job_position_name FROM job_position
	ORDER BY job_position_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Responsibility Table Stored Procedures */

CREATE PROCEDURE checkJobPositionResponsibilityExist (IN p_job_position_responsibility_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM job_position_responsibility
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
END //

CREATE PROCEDURE insertJobPositionResponsibility(IN p_job_position_id INT, IN p_responsibility VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    INSERT INTO job_position_responsibility (job_position_id, responsibility, last_log_by) 
	VALUES(p_job_position_id, p_responsibility, p_last_log_by);
END //

CREATE PROCEDURE updateJobPositionResponsibility(IN p_job_position_responsibility_id INT, IN p_responsibility VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE job_position_responsibility
    SET responsibility = p_responsibility,
    last_log_by = p_last_log_by
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
END //

CREATE PROCEDURE deleteJobPositionResponsibility(IN p_job_position_responsibility_id INT)
BEGIN
	DELETE FROM job_position_responsibility
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
END //

CREATE PROCEDURE getJobPositionResponsibility(IN p_job_position_responsibility_id INT)
BEGIN
	SELECT * FROM job_position_responsibility
    WHERE job_position_responsibility_id = p_job_position_responsibility_id;
END //

CREATE PROCEDURE getLinkedJobPositionResponsibility(IN p_job_position_id INT)
BEGIN
	SELECT * FROM job_position_responsibility
    WHERE job_position_id = p_job_position_id;
END //

CREATE PROCEDURE generateJobPositionResponsibilityTable(IN p_job_position_id INT)
BEGIN
	SELECT job_position_responsibility_id, responsibility 
    FROM job_position_responsibility
    WHERE job_position_id = p_job_position_id 
    ORDER BY job_position_responsibility_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Requirement Table Stored Procedures */

CREATE PROCEDURE checkJobPositionRequirementExist (IN p_job_position_requirement_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM job_position_requirement
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END //

CREATE PROCEDURE insertJobPositionRequirement(IN p_job_position_id INT, IN p_requirement VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    INSERT INTO job_position_requirement (job_position_id, requirement, last_log_by) 
	VALUES(p_job_position_id, p_requirement, p_last_log_by);
END //

CREATE PROCEDURE updateJobPositionRequirement(IN p_job_position_requirement_id INT, IN p_requirement VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE job_position_requirement
    SET requirement = p_requirement,
    last_log_by = p_last_log_by
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END //

CREATE PROCEDURE deleteJobPositionRequirement(IN p_job_position_requirement_id INT)
BEGIN
	DELETE FROM job_position_requirement
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END //

CREATE PROCEDURE getJobPositionRequirement(IN p_job_position_requirement_id INT)
BEGIN
	SELECT * FROM job_position_requirement
    WHERE job_position_requirement_id = p_job_position_requirement_id;
END //

CREATE PROCEDURE getLinkedJobPositionRequirement(IN p_job_position_id INT)
BEGIN
	SELECT * FROM job_position_requirement
    WHERE job_position_id = p_job_position_id;
END //

CREATE PROCEDURE generateJobPositionRequirementTable(IN p_job_position_id INT)
BEGIN
	SELECT job_position_requirement_id, requirement 
    FROM job_position_requirement
    WHERE job_position_id = p_job_position_id 
    ORDER BY job_position_requirement_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Qualification Table Stored Procedures */

CREATE PROCEDURE checkJobPositionQualificationExist (IN p_job_position_qualification_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM job_position_qualification
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END //

CREATE PROCEDURE insertJobPositionQualification(IN p_job_position_id INT, IN p_qualification VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    INSERT INTO job_position_qualification (job_position_id, qualification, last_log_by) 
	VALUES(p_job_position_id, p_qualification, p_last_log_by);
END //

CREATE PROCEDURE updateJobPositionQualification(IN p_job_position_qualification_id INT, IN p_qualification VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE job_position_qualification
    SET qualification = p_qualification,
    last_log_by = p_last_log_by
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END //

CREATE PROCEDURE deleteJobPositionQualification(IN p_job_position_qualification_id INT)
BEGIN
	DELETE FROM job_position_qualification
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END //

CREATE PROCEDURE getJobPositionQualification(IN p_job_position_qualification_id INT)
BEGIN
	SELECT * FROM job_position_qualification
    WHERE job_position_qualification_id = p_job_position_qualification_id;
END //

CREATE PROCEDURE getLinkedJobPositionQualification(IN p_job_position_id INT)
BEGIN
	SELECT * FROM job_position_qualification
    WHERE job_position_id = p_job_position_id;
END //

CREATE PROCEDURE generateJobPositionQualificationTable(IN p_job_position_id INT)
BEGIN
	SELECT job_position_qualification_id, qualification 
    FROM job_position_qualification
    WHERE job_position_id = p_job_position_id 
    ORDER BY job_position_qualification_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Level Table Stored Procedures */

CREATE PROCEDURE checkJobLevelExist (IN p_job_level_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM job_level
    WHERE job_level_id = p_job_level_id;
END //

CREATE PROCEDURE insertJobLevel(IN p_current_level VARCHAR(10), IN p_rank VARCHAR(100), IN p_functional_level VARCHAR(100), IN p_last_log_by INT, OUT p_job_level_id INT)
BEGIN
    INSERT INTO job_level (current_level, rank, functional_level, last_log_by) 
	VALUES(p_current_level, p_rank, p_functional_level, p_last_log_by);
	
    SET p_job_level_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateJobLevel(IN p_job_level_id INT, IN p_current_level VARCHAR(10), IN p_rank VARCHAR(100), IN p_functional_level VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE job_level
    SET current_level = p_current_level,
    rank = p_rank,
    functional_level = p_functional_level,
    last_log_by = p_last_log_by
    WHERE job_level_id = p_job_level_id;
END //

CREATE PROCEDURE deleteJobLevel(IN p_job_level_id INT)
BEGIN
    DELETE FROM job_level WHERE job_level_id = p_job_level_id;
END //

CREATE PROCEDURE getJobLevel(IN p_job_level_id INT)
BEGIN
	SELECT * FROM job_level
    WHERE job_level_id = p_job_level_id;
END //

CREATE PROCEDURE duplicateJobLevel(IN p_job_level_id INT, IN p_last_log_by INT, OUT p_new_job_level_id INT)
BEGIN
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
END //

CREATE PROCEDURE generateJobLevelTable()
BEGIN
    SELECT job_level_id, current_level, rank, functional_level
    FROM job_level
    ORDER BY job_level_id;
END //

CREATE PROCEDURE generateJobLevelOptions()
BEGIN
	SELECT job_level_id, current_level, rank FROM job_level
	ORDER BY current_level;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Type Table Stored Procedures */

CREATE PROCEDURE checkEmployeeTypeExist (IN p_employee_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM employee_type
    WHERE employee_type_id = p_employee_type_id;
END //

CREATE PROCEDURE insertEmployeeType(IN p_employee_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_employee_type_id INT)
BEGIN
    INSERT INTO employee_type (employee_type_name, last_log_by) 
	VALUES(p_employee_type_name, p_last_log_by);
	
    SET p_employee_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateEmployeeType(IN p_employee_type_id INT, IN p_employee_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE employee_type
    SET employee_type_name = p_employee_type_name,
    last_log_by = p_last_log_by
    WHERE employee_type_id = p_employee_type_id;
END //

CREATE PROCEDURE deleteEmployeeType(IN p_employee_type_id INT)
BEGIN
    DELETE FROM employee_type WHERE employee_type_id = p_employee_type_id;
END //

CREATE PROCEDURE getEmployeeType(IN p_employee_type_id INT)
BEGIN
	SELECT * FROM employee_type
    WHERE employee_type_id = p_employee_type_id;
END //

CREATE PROCEDURE duplicateEmployeeType(IN p_employee_type_id INT, IN p_last_log_by INT, OUT p_new_employee_type_id INT)
BEGIN
    DECLARE p_employee_type_name VARCHAR(100);
    
    SELECT employee_type_name
    INTO p_employee_type_name
    FROM employee_type 
    WHERE employee_type_id = p_employee_type_id;
    
    INSERT INTO employee_type (employee_type_name, last_log_by) 
    VALUES(p_employee_type_name, p_last_log_by);
    
    SET p_new_employee_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateEmployeeTypeTable()
BEGIN
    SELECT employee_type_id, employee_type_name
    FROM employee_type
    ORDER BY employee_type_id;
END //

CREATE PROCEDURE generateEmployeeTypeOptions()
BEGIN
	SELECT employee_type_id, employee_type_name FROM employee_type
	ORDER BY employee_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Departure Reason Table Stored Procedures */

CREATE PROCEDURE checkDepartureReasonExist (IN p_departure_reason_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM departure_reason
    WHERE departure_reason_id = p_departure_reason_id;
END //

CREATE PROCEDURE insertDepartureReason(IN p_departure_reason_name VARCHAR(100), IN p_last_log_by INT, OUT p_departure_reason_id INT)
BEGIN
    INSERT INTO departure_reason (departure_reason_name, last_log_by) 
	VALUES(p_departure_reason_name, p_last_log_by);
	
    SET p_departure_reason_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateDepartureReason(IN p_departure_reason_id INT, IN p_departure_reason_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE departure_reason
    SET departure_reason_name = p_departure_reason_name,
    last_log_by = p_last_log_by
    WHERE departure_reason_id = p_departure_reason_id;
END //

CREATE PROCEDURE deleteDepartureReason(IN p_departure_reason_id INT)
BEGIN
    DELETE FROM departure_reason WHERE departure_reason_id = p_departure_reason_id;
END //

CREATE PROCEDURE getDepartureReason(IN p_departure_reason_id INT)
BEGIN
	SELECT * FROM departure_reason
    WHERE departure_reason_id = p_departure_reason_id;
END //

CREATE PROCEDURE duplicateDepartureReason(IN p_departure_reason_id INT, IN p_last_log_by INT, OUT p_new_departure_reason_id INT)
BEGIN
    DECLARE p_departure_reason_name VARCHAR(100);
    
    SELECT departure_reason_name
    INTO p_departure_reason_name
    FROM departure_reason 
    WHERE departure_reason_id = p_departure_reason_id;
    
    INSERT INTO departure_reason (departure_reason_name, last_log_by) 
    VALUES(p_departure_reason_name, p_last_log_by);
    
    SET p_new_departure_reason_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateDepartureReasonTable()
BEGIN
    SELECT departure_reason_id, departure_reason_name
    FROM departure_reason
    ORDER BY departure_reason_id;
END //

CREATE PROCEDURE generateDepartureReasonOptions()
BEGIN
	SELECT departure_reason_id, departure_reason_name FROM departure_reason
	ORDER BY departure_reason_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* ID Type Table Stored Procedures */

CREATE PROCEDURE checkIDTypeExist (IN p_id_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM id_type
    WHERE id_type_id = p_id_type_id;
END //

CREATE PROCEDURE insertIDType(IN p_id_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_id_type_id INT)
BEGIN
    INSERT INTO id_type (id_type_name, last_log_by) 
	VALUES(p_id_type_name, p_last_log_by);
	
    SET p_id_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateIDType(IN p_id_type_id INT, IN p_id_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE id_type
    SET id_type_name = p_id_type_name,
    last_log_by = p_last_log_by
    WHERE id_type_id = p_id_type_id;
END //

CREATE PROCEDURE deleteIDType(IN p_id_type_id INT)
BEGIN
    DELETE FROM id_type WHERE id_type_id = p_id_type_id;
END //

CREATE PROCEDURE getIDType(IN p_id_type_id INT)
BEGIN
	SELECT * FROM id_type
    WHERE id_type_id = p_id_type_id;
END //

CREATE PROCEDURE duplicateIDType(IN p_id_type_id INT, IN p_last_log_by INT, OUT p_new_id_type_id INT)
BEGIN
    DECLARE p_id_type_name VARCHAR(100);
    
    SELECT id_type_name
    INTO p_id_type_name
    FROM id_type 
    WHERE id_type_id = p_id_type_id;
    
    INSERT INTO id_type (id_type_name, last_log_by) 
    VALUES(p_id_type_name, p_last_log_by);
    
    SET p_new_id_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateIDTypeTable()
BEGIN
    SELECT id_type_id, id_type_name
    FROM id_type
    ORDER BY id_type_id;
END //

CREATE PROCEDURE generateIDTypeOptions()
BEGIN
	SELECT id_type_id, id_type_name FROM id_type
	ORDER BY id_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Gender Table Stored Procedures */

CREATE PROCEDURE checkGenderExist (IN p_gender_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM gender
    WHERE gender_id = p_gender_id;
END //

CREATE PROCEDURE insertGender(IN p_gender_name VARCHAR(100), IN p_last_log_by INT, OUT p_gender_id INT)
BEGIN
    INSERT INTO gender (gender_name, last_log_by) 
	VALUES(p_gender_name, p_last_log_by);
	
    SET p_gender_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateGender(IN p_gender_id INT, IN p_gender_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE gender
    SET gender_name = p_gender_name,
    last_log_by = p_last_log_by
    WHERE gender_id = p_gender_id;
END //

CREATE PROCEDURE deleteGender(IN p_gender_id INT)
BEGIN
    DELETE FROM gender WHERE gender_id = p_gender_id;
END //

CREATE PROCEDURE getGender(IN p_gender_id INT)
BEGIN
	SELECT * FROM gender
    WHERE gender_id = p_gender_id;
END //

CREATE PROCEDURE duplicateGender(IN p_gender_id INT, IN p_last_log_by INT, OUT p_new_gender_id INT)
BEGIN
    DECLARE p_gender_name VARCHAR(100);
    
    SELECT gender_name
    INTO p_gender_name
    FROM gender 
    WHERE gender_id = p_gender_id;
    
    INSERT INTO gender (gender_name, last_log_by) 
    VALUES(p_gender_name, p_last_log_by);
    
    SET p_new_gender_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateGenderTable()
BEGIN
    SELECT gender_id, gender_name
    FROM gender
    ORDER BY gender_id;
END //

CREATE PROCEDURE generateGenderOptions()
BEGIN
	SELECT gender_id, gender_name FROM gender
	ORDER BY gender_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Religion Table Stored Procedures */

CREATE PROCEDURE checkReligionExist (IN p_religion_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM religion
    WHERE religion_id = p_religion_id;
END //

CREATE PROCEDURE insertReligion(IN p_religion_name VARCHAR(100), IN p_last_log_by INT, OUT p_religion_id INT)
BEGIN
    INSERT INTO religion (religion_name, last_log_by) 
	VALUES(p_religion_name, p_last_log_by);
	
    SET p_religion_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateReligion(IN p_religion_id INT, IN p_religion_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE religion
    SET religion_name = p_religion_name,
    last_log_by = p_last_log_by
    WHERE religion_id = p_religion_id;
END //

CREATE PROCEDURE deleteReligion(IN p_religion_id INT)
BEGIN
    DELETE FROM religion WHERE religion_id = p_religion_id;
END //

CREATE PROCEDURE getReligion(IN p_religion_id INT)
BEGIN
	SELECT * FROM religion
    WHERE religion_id = p_religion_id;
END //

CREATE PROCEDURE duplicateReligion(IN p_religion_id INT, IN p_last_log_by INT, OUT p_new_religion_id INT)
BEGIN
    DECLARE p_religion_name VARCHAR(100);
    
    SELECT religion_name
    INTO p_religion_name
    FROM religion 
    WHERE religion_id = p_religion_id;
    
    INSERT INTO religion (religion_name, last_log_by) 
    VALUES(p_religion_name, p_last_log_by);
    
    SET p_new_religion_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateReligionTable()
BEGIN
    SELECT religion_id, religion_name
    FROM religion
    ORDER BY religion_id;
END //

CREATE PROCEDURE generateReligionOptions()
BEGIN
	SELECT religion_id, religion_name FROM religion
	ORDER BY religion_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Nationality Table Stored Procedures */

CREATE PROCEDURE checkNationalityExist (IN p_nationality_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM nationality
    WHERE nationality_id = p_nationality_id;
END //

CREATE PROCEDURE insertNationality(IN p_nationality_name VARCHAR(100), IN p_last_log_by INT, OUT p_nationality_id INT)
BEGIN
    INSERT INTO nationality (nationality_name, last_log_by) 
	VALUES(p_nationality_name, p_last_log_by);
	
    SET p_nationality_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateNationality(IN p_nationality_id INT, IN p_nationality_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE nationality
    SET nationality_name = p_nationality_name,
    last_log_by = p_last_log_by
    WHERE nationality_id = p_nationality_id;
END //

CREATE PROCEDURE deleteNationality(IN p_nationality_id INT)
BEGIN
    DELETE FROM nationality WHERE nationality_id = p_nationality_id;
END //

CREATE PROCEDURE getNationality(IN p_nationality_id INT)
BEGIN
	SELECT * FROM nationality
    WHERE nationality_id = p_nationality_id;
END //

CREATE PROCEDURE duplicateNationality(IN p_nationality_id INT, IN p_last_log_by INT, OUT p_new_nationality_id INT)
BEGIN
    DECLARE p_nationality_name VARCHAR(100);
    
    SELECT nationality_name
    INTO p_nationality_name
    FROM nationality 
    WHERE nationality_id = p_nationality_id;
    
    INSERT INTO nationality (nationality_name, last_log_by) 
    VALUES(p_nationality_name, p_last_log_by);
    
    SET p_new_nationality_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateNationalityTable()
BEGIN
    SELECT nationality_id, nationality_name
    FROM nationality
    ORDER BY nationality_id;
END //

CREATE PROCEDURE generateNationalityOptions()
BEGIN
	SELECT nationality_id, nationality_name FROM nationality
	ORDER BY nationality_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Relation Table Stored Procedures */

CREATE PROCEDURE checkRelationExist (IN p_relation_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM relation
    WHERE relation_id = p_relation_id;
END //

CREATE PROCEDURE insertRelation(IN p_relation_name VARCHAR(100), IN p_last_log_by INT, OUT p_relation_id INT)
BEGIN
    INSERT INTO relation (relation_name, last_log_by) 
	VALUES(p_relation_name, p_last_log_by);
	
    SET p_relation_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateRelation(IN p_relation_id INT, IN p_relation_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE relation
    SET relation_name = p_relation_name,
    last_log_by = p_last_log_by
    WHERE relation_id = p_relation_id;
END //

CREATE PROCEDURE deleteRelation(IN p_relation_id INT)
BEGIN
    DELETE FROM relation WHERE relation_id = p_relation_id;
END //

CREATE PROCEDURE getRelation(IN p_relation_id INT)
BEGIN
	SELECT * FROM relation
    WHERE relation_id = p_relation_id;
END //

CREATE PROCEDURE duplicateRelation(IN p_relation_id INT, IN p_last_log_by INT, OUT p_new_relation_id INT)
BEGIN
    DECLARE p_relation_name VARCHAR(100);
    
    SELECT relation_name
    INTO p_relation_name
    FROM relation 
    WHERE relation_id = p_relation_id;
    
    INSERT INTO relation (relation_name, last_log_by) 
    VALUES(p_relation_name, p_last_log_by);
    
    SET p_new_relation_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateRelationTable()
BEGIN
    SELECT relation_id, relation_name
    FROM relation
    ORDER BY relation_id;
END //

CREATE PROCEDURE generateRelationOptions()
BEGIN
	SELECT relation_id, relation_name FROM relation
	ORDER BY relation_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Civil Status Table Stored Procedures */

CREATE PROCEDURE checkCivilStatusExist (IN p_civil_status_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM civil_status
    WHERE civil_status_id = p_civil_status_id;
END //

CREATE PROCEDURE insertCivilStatus(IN p_civil_status_name VARCHAR(100), IN p_last_log_by INT, OUT p_civil_status_id INT)
BEGIN
    INSERT INTO civil_status (civil_status_name, last_log_by) 
	VALUES(p_civil_status_name, p_last_log_by);
	
    SET p_civil_status_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateCivilStatus(IN p_civil_status_id INT, IN p_civil_status_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE civil_status
    SET civil_status_name = p_civil_status_name,
    last_log_by = p_last_log_by
    WHERE civil_status_id = p_civil_status_id;
END //

CREATE PROCEDURE deleteCivilStatus(IN p_civil_status_id INT)
BEGIN
    DELETE FROM civil_status WHERE civil_status_id = p_civil_status_id;
END //

CREATE PROCEDURE getCivilStatus(IN p_civil_status_id INT)
BEGIN
	SELECT * FROM civil_status
    WHERE civil_status_id = p_civil_status_id;
END //

CREATE PROCEDURE duplicateCivilStatus(IN p_civil_status_id INT, IN p_last_log_by INT, OUT p_new_civil_status_id INT)
BEGIN
    DECLARE p_civil_status_name VARCHAR(100);
    
    SELECT civil_status_name
    INTO p_civil_status_name
    FROM civil_status 
    WHERE civil_status_id = p_civil_status_id;
    
    INSERT INTO civil_status (civil_status_name, last_log_by) 
    VALUES(p_civil_status_name, p_last_log_by);
    
    SET p_new_civil_status_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateCivilStatusTable()
BEGIN
    SELECT civil_status_id, civil_status_name
    FROM civil_status
    ORDER BY civil_status_id;
END //

CREATE PROCEDURE generateCivilStatusOptions()
BEGIN
	SELECT civil_status_id, civil_status_name FROM civil_status
	ORDER BY civil_status_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Blood Table Stored Procedures */

CREATE PROCEDURE checkBloodTypeExist (IN p_blood_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM blood_type
    WHERE blood_type_id = p_blood_type_id;
END //

CREATE PROCEDURE insertBloodType(IN p_blood_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_blood_type_id INT)
BEGIN
    INSERT INTO blood_type (blood_type_name, last_log_by) 
	VALUES(p_blood_type_name, p_last_log_by);
	
    SET p_blood_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBloodType(IN p_blood_type_id INT, IN p_blood_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE blood_type
    SET blood_type_name = p_blood_type_name,
    last_log_by = p_last_log_by
    WHERE blood_type_id = p_blood_type_id;
END //

CREATE PROCEDURE deleteBloodType(IN p_blood_type_id INT)
BEGIN
    DELETE FROM blood_type WHERE blood_type_id = p_blood_type_id;
END //

CREATE PROCEDURE getBloodType(IN p_blood_type_id INT)
BEGIN
	SELECT * FROM blood_type
    WHERE blood_type_id = p_blood_type_id;
END //

CREATE PROCEDURE duplicateBloodType(IN p_blood_type_id INT, IN p_last_log_by INT, OUT p_new_blood_type_id INT)
BEGIN
    DECLARE p_blood_type_name VARCHAR(100);
    
    SELECT blood_type_name
    INTO p_blood_type_name
    FROM blood_type 
    WHERE blood_type_id = p_blood_type_id;
    
    INSERT INTO blood_type (blood_type_name, last_log_by) 
    VALUES(p_blood_type_name, p_last_log_by);
    
    SET p_new_blood_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateBloodTypeTable()
BEGIN
    SELECT blood_type_id, blood_type_name
    FROM blood_type
    ORDER BY blood_type_id;
END //

CREATE PROCEDURE generateBloodTypeOptions()
BEGIN
	SELECT blood_type_id, blood_type_name FROM blood_type
	ORDER BY blood_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Bank Table Stored Procedures */

CREATE PROCEDURE checkBankExist (IN p_bank_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM bank
    WHERE bank_id = p_bank_id;
END //

CREATE PROCEDURE insertBank(IN p_bank_name VARCHAR(100), IN p_bank_identifier_code VARCHAR(100), IN p_last_log_by INT, OUT p_bank_id INT)
BEGIN
    INSERT INTO bank (bank_name, bank_identifier_code, last_log_by) 
	VALUES(p_bank_name, p_bank_identifier_code, p_last_log_by);
	
    SET p_bank_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBank(IN p_bank_id INT, IN p_bank_name VARCHAR(100), IN p_bank_identifier_code VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE bank
    SET bank_name = p_bank_name,
    bank_identifier_code = p_bank_identifier_code,
    last_log_by = p_last_log_by
    WHERE bank_id = p_bank_id;
END //

CREATE PROCEDURE deleteBank(IN p_bank_id INT)
BEGIN
    DELETE FROM bank WHERE bank_id = p_bank_id;
END //

CREATE PROCEDURE getBank(IN p_bank_id INT)
BEGIN
	SELECT * FROM bank
    WHERE bank_id = p_bank_id;
END //

CREATE PROCEDURE duplicateBank(IN p_bank_id INT, IN p_last_log_by INT, OUT p_new_bank_id INT)
BEGIN
    DECLARE p_bank_name VARCHAR(100);
    DECLARE p_bank_identifier_code VARCHAR(100);
    
    SELECT bank_name, bank_identifier_code
    INTO p_bank_name, p_bank_identifier_code
    FROM bank 
    WHERE bank_id = p_bank_id;
    
    INSERT INTO bank (bank_name, bank_identifier_code, last_log_by) 
    VALUES(p_bank_name, p_bank_identifier_code, p_last_log_by);
    
    SET p_new_bank_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateBankTable()
BEGIN
    SELECT bank_id, bank_name
    FROM bank
    ORDER BY bank_id;
END //

CREATE PROCEDURE generateBankOptions()
BEGIN
	SELECT bank_id, bank_name FROM bank
	ORDER BY bank_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Holiday Type Table Stored Procedures */

CREATE PROCEDURE checkHolidayTypeExist (IN p_holiday_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM holiday_type
    WHERE holiday_type_id = p_holiday_type_id;
END //

CREATE PROCEDURE insertHolidayType(IN p_holiday_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_holiday_type_id INT)
BEGIN
    INSERT INTO holiday_type (holiday_type_name, last_log_by) 
	VALUES(p_holiday_type_name, p_last_log_by);
	
    SET p_holiday_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateHolidayType(IN p_holiday_type_id INT, IN p_holiday_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE holiday_type
    SET holiday_type_name = p_holiday_type_name,
    last_log_by = p_last_log_by
    WHERE holiday_type_id = p_holiday_type_id;
END //

CREATE PROCEDURE deleteHolidayType(IN p_holiday_type_id INT)
BEGIN
    DELETE FROM holiday_type WHERE holiday_type_id = p_holiday_type_id;
END //

CREATE PROCEDURE getHolidayType(IN p_holiday_type_id INT)
BEGIN
	SELECT * FROM holiday_type
    WHERE holiday_type_id = p_holiday_type_id;
END //

CREATE PROCEDURE duplicateHolidayType(IN p_holiday_type_id INT, IN p_last_log_by INT, OUT p_new_holiday_type_id INT)
BEGIN
    DECLARE p_holiday_type_name VARCHAR(100);
    
    SELECT holiday_type_name
    INTO p_holiday_type_name
    FROM holiday_type 
    WHERE holiday_type_id = p_holiday_type_id;
    
    INSERT INTO holiday_type (holiday_type_name, last_log_by) 
    VALUES(p_holiday_type_name, p_last_log_by);
    
    SET p_new_holiday_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateHolidayTypeTable()
BEGIN
    SELECT holiday_type_id, holiday_type_name
    FROM holiday_type
    ORDER BY holiday_type_id;
END //

CREATE PROCEDURE generateHolidayTypeOptions()
BEGIN
	SELECT holiday_type_id, holiday_type_name FROM holiday_type
	ORDER BY holiday_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Schedule Type Table Stored Procedures */

CREATE PROCEDURE checkWorkScheduleTypeExist (IN p_work_schedule_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM work_schedule_type
    WHERE work_schedule_type_id = p_work_schedule_type_id;
END //

CREATE PROCEDURE insertWorkScheduleType(IN p_work_schedule_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_work_schedule_type_id INT)
BEGIN
    INSERT INTO work_schedule_type (work_schedule_type_name, last_log_by) 
	VALUES(p_work_schedule_type_name, p_last_log_by);
	
    SET p_work_schedule_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateWorkScheduleType(IN p_work_schedule_type_id INT, IN p_work_schedule_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE work_schedule_type
    SET work_schedule_type_name = p_work_schedule_type_name,
    last_log_by = p_last_log_by
    WHERE work_schedule_type_id = p_work_schedule_type_id;
END //

CREATE PROCEDURE deleteWorkScheduleType(IN p_work_schedule_type_id INT)
BEGIN
    DELETE FROM work_schedule_type WHERE work_schedule_type_id = p_work_schedule_type_id;
END //

CREATE PROCEDURE getWorkScheduleType(IN p_work_schedule_type_id INT)
BEGIN
	SELECT * FROM work_schedule_type
    WHERE work_schedule_type_id = p_work_schedule_type_id;
END //

CREATE PROCEDURE duplicateWorkScheduleType(IN p_work_schedule_type_id INT, IN p_last_log_by INT, OUT p_new_work_schedule_type_id INT)
BEGIN
    DECLARE p_work_schedule_type_name VARCHAR(100);
    
    SELECT work_schedule_type_name
    INTO p_work_schedule_type_name
    FROM work_schedule_type 
    WHERE work_schedule_type_id = p_work_schedule_type_id;
    
    INSERT INTO work_schedule_type (work_schedule_type_name, last_log_by) 
    VALUES(p_work_schedule_type_name, p_last_log_by);
    
    SET p_new_work_schedule_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateWorkScheduleTypeTable()
BEGIN
    SELECT work_schedule_type_id, work_schedule_type_name
    FROM work_schedule_type
    ORDER BY work_schedule_type_id;
END //

CREATE PROCEDURE generateWorkScheduleTypeOptions()
BEGIN
	SELECT work_schedule_type_id, work_schedule_type_name FROM work_schedule_type
	ORDER BY work_schedule_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Address Type Table Stored Procedures */

CREATE PROCEDURE checkAddressTypeExist (IN p_address_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM address_type
    WHERE address_type_id = p_address_type_id;
END //

CREATE PROCEDURE insertAddressType(IN p_address_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_address_type_id INT)
BEGIN
    INSERT INTO address_type (address_type_name, last_log_by) 
	VALUES(p_address_type_name, p_last_log_by);
	
    SET p_address_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateAddressType(IN p_address_type_id INT, IN p_address_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE address_type
    SET address_type_name = p_address_type_name,
    last_log_by = p_last_log_by
    WHERE address_type_id = p_address_type_id;
END //

CREATE PROCEDURE deleteAddressType(IN p_address_type_id INT)
BEGIN
    DELETE FROM address_type WHERE address_type_id = p_address_type_id;
END //

CREATE PROCEDURE getAddressType(IN p_address_type_id INT)
BEGIN
	SELECT * FROM address_type
    WHERE address_type_id = p_address_type_id;
END //

CREATE PROCEDURE duplicateAddressType(IN p_address_type_id INT, IN p_last_log_by INT, OUT p_new_address_type_id INT)
BEGIN
    DECLARE p_address_type_name VARCHAR(100);
    
    SELECT address_type_name
    INTO p_address_type_name
    FROM address_type 
    WHERE address_type_id = p_address_type_id;
    
    INSERT INTO address_type (address_type_name, last_log_by) 
    VALUES(p_address_type_name, p_last_log_by);
    
    SET p_new_address_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateAddressTypeTable()
BEGIN
    SELECT address_type_id, address_type_name
    FROM address_type
    ORDER BY address_type_id;
END //

CREATE PROCEDURE generateAddressTypeOptions()
BEGIN
	SELECT address_type_id, address_type_name FROM address_type
	ORDER BY address_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Educational Stage Table Stored Procedures */

CREATE PROCEDURE checkEducationalStageExist (IN p_educational_stage_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM educational_stage
    WHERE educational_stage_id = p_educational_stage_id;
END //

CREATE PROCEDURE insertEducationalStage(IN p_educational_stage_name VARCHAR(100), IN p_last_log_by INT, OUT p_educational_stage_id INT)
BEGIN
    INSERT INTO educational_stage (educational_stage_name, last_log_by) 
	VALUES(p_educational_stage_name, p_last_log_by);
	
    SET p_educational_stage_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateEducationalStage(IN p_educational_stage_id INT, IN p_educational_stage_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE educational_stage
    SET educational_stage_name = p_educational_stage_name,
    last_log_by = p_last_log_by
    WHERE educational_stage_id = p_educational_stage_id;
END //

CREATE PROCEDURE deleteEducationalStage(IN p_educational_stage_id INT)
BEGIN
    DELETE FROM educational_stage WHERE educational_stage_id = p_educational_stage_id;
END //

CREATE PROCEDURE getEducationalStage(IN p_educational_stage_id INT)
BEGIN
	SELECT * FROM educational_stage
    WHERE educational_stage_id = p_educational_stage_id;
END //

CREATE PROCEDURE duplicateEducationalStage(IN p_educational_stage_id INT, IN p_last_log_by INT, OUT p_new_educational_stage_id INT)
BEGIN
    DECLARE p_educational_stage_name VARCHAR(100);
    
    SELECT educational_stage_name
    INTO p_educational_stage_name
    FROM educational_stage 
    WHERE educational_stage_id = p_educational_stage_id;
    
    INSERT INTO educational_stage (educational_stage_name, last_log_by) 
    VALUES(p_educational_stage_name, p_last_log_by);
    
    SET p_new_educational_stage_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateEducationalStageTable()
BEGIN
    SELECT educational_stage_id, educational_stage_name
    FROM educational_stage
    ORDER BY educational_stage_id;
END //

CREATE PROCEDURE generateEducationalStageOptions()
BEGIN
	SELECT educational_stage_id, educational_stage_name FROM educational_stage
	ORDER BY educational_stage_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Information Type Table Stored Procedures */

CREATE PROCEDURE checkContactInformationTypeExist (IN p_contact_information_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_information_type
    WHERE contact_information_type_id = p_contact_information_type_id;
END //

CREATE PROCEDURE insertContactInformationType(IN p_contact_information_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_contact_information_type_id INT)
BEGIN
    INSERT INTO contact_information_type (contact_information_type_name, last_log_by) 
	VALUES(p_contact_information_type_name, p_last_log_by);
	
    SET p_contact_information_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateContactInformationType(IN p_contact_information_type_id INT, IN p_contact_information_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE contact_information_type
    SET contact_information_type_name = p_contact_information_type_name,
    last_log_by = p_last_log_by
    WHERE contact_information_type_id = p_contact_information_type_id;
END //

CREATE PROCEDURE deleteContactInformationType(IN p_contact_information_type_id INT)
BEGIN
    DELETE FROM contact_information_type WHERE contact_information_type_id = p_contact_information_type_id;
END //

CREATE PROCEDURE getContactInformationType(IN p_contact_information_type_id INT)
BEGIN
	SELECT * FROM contact_information_type
    WHERE contact_information_type_id = p_contact_information_type_id;
END //

CREATE PROCEDURE duplicateContactInformationType(IN p_contact_information_type_id INT, IN p_last_log_by INT, OUT p_new_contact_information_type_id INT)
BEGIN
    DECLARE p_contact_information_type_name VARCHAR(100);
    
    SELECT contact_information_type_name
    INTO p_contact_information_type_name
    FROM contact_information_type 
    WHERE contact_information_type_id = p_contact_information_type_id;
    
    INSERT INTO contact_information_type (contact_information_type_name, last_log_by) 
    VALUES(p_contact_information_type_name, p_last_log_by);
    
    SET p_new_contact_information_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateContactInformationTypeTable()
BEGIN
    SELECT contact_information_type_id, contact_information_type_name
    FROM contact_information_type
    ORDER BY contact_information_type_id;
END //

CREATE PROCEDURE generateContactInformationTypeOptions()
BEGIN
	SELECT contact_information_type_id, contact_information_type_name FROM contact_information_type
	ORDER BY contact_information_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Schedule Details Table Stored Procedures */

CREATE PROCEDURE checkWorkScheduleExist (IN p_work_schedule_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM work_schedule
    WHERE work_schedule_id = p_work_schedule_id;
END //

CREATE PROCEDURE insertWorkSchedule(IN p_work_schedule_name VARCHAR(100), IN p_work_schedule_description VARCHAR(500), IN p_work_schedule_type_id INT, IN p_last_log_by INT, OUT p_work_schedule_id INT)
BEGIN
    INSERT INTO work_schedule (work_schedule_name, work_schedule_description, work_schedule_type_id, last_log_by) 
	VALUES(p_work_schedule_name, p_work_schedule_description, p_work_schedule_type_id, p_last_log_by);
	
    SET p_work_schedule_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateWorkSchedule(IN p_work_schedule_id INT, IN p_work_schedule_name VARCHAR(100), IN p_work_schedule_description VARCHAR(500), IN p_work_schedule_type_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE work_schedule
    SET work_schedule_name = p_work_schedule_name,
    work_schedule_description = p_work_schedule_description,
    work_schedule_type_id = p_work_schedule_type_id,
    last_log_by = p_last_log_by
    WHERE work_schedule_id = p_work_schedule_id;
END //

CREATE PROCEDURE deleteWorkSchedule(IN p_work_schedule_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM work_hours WHERE work_schedule_id = p_work_schedule_id;
    DELETE FROM work_schedule WHERE work_schedule_id = p_work_schedule_id;

    COMMIT;
END //

CREATE PROCEDURE getWorkSchedule(IN p_work_schedule_id INT)
BEGIN
	SELECT * FROM work_schedule
    WHERE work_schedule_id = p_work_schedule_id;
END //

CREATE PROCEDURE duplicateWorkSchedule(IN p_work_schedule_id INT, IN p_last_log_by INT, OUT p_new_work_schedule_id INT)
BEGIN
    DECLARE p_work_schedule_name VARCHAR(100);
    DECLARE p_work_schedule_description VARCHAR(500);
    DECLARE p_work_schedule_type_id INT;
    
    SELECT work_schedule_name, work_schedule_description, work_schedule_type_id
    INTO p_work_schedule_name, p_work_schedule_description, p_work_schedule_type_id
    FROM work_schedule 
    WHERE work_schedule_id = p_work_schedule_id;
    
    INSERT INTO work_schedule (work_schedule_name, work_schedule_description, work_schedule_type_id, last_log_by) 
    VALUES(p_work_schedule_name, p_work_schedule_description, p_work_schedule_type_id, p_last_log_by);
    
    SET p_new_work_schedule_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateWorkScheduleTable(IN p_work_schedule_type_filter VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT work_schedule_id, work_schedule_name, work_schedule_description, work_schedule_type_id FROM work_schedule';
    
    SET conditionList = ' WHERE 1';

    IF p_work_schedule_type_filter IS NOT NULL AND p_work_schedule_type_filter <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND work_schedule_type_id IN (', p_work_schedule_type_filter, ')');
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY work_schedule_name');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateWorkScheduleOptions()
BEGIN
	SELECT work_schedule_id, work_schedule_name FROM work_schedule
	ORDER BY work_schedule_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Hours Table Stored Procedures */

CREATE PROCEDURE checkWorkHoursExist (IN p_work_hours_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM work_hours
    WHERE work_hours_id = p_work_hours_id;
END //

CREATE PROCEDURE insertWorkHours(IN p_work_schedule_id INT, IN p_work_date DATE, IN p_day_of_week VARCHAR(15), IN p_day_period VARCHAR(15), IN p_start_time TIME, IN p_end_time TIME, IN p_notes TEXT, IN p_last_log_by INT)
BEGIN
    INSERT INTO work_hours (work_schedule_id, work_date, day_of_week, day_period, start_time, end_time, notes, last_log_by) 
	VALUES(p_work_schedule_id, p_work_date, p_day_of_week, p_day_period, p_start_time, p_end_time, p_notes, p_last_log_by);
END //

CREATE PROCEDURE updateWorkHours(IN p_work_hours_id INT, IN p_work_schedule_id INT, IN p_work_date DATE, IN p_day_of_week VARCHAR(15), IN p_day_period VARCHAR(15), IN p_start_time TIME, IN p_end_time TIME, IN p_notes TEXT, IN p_last_log_by INT)
BEGIN
	UPDATE work_hours
    SET work_schedule_id = p_work_schedule_id,
    work_date = p_work_date,
    day_of_week = p_day_of_week,
    day_period = p_day_period,
    start_time = p_start_time,
    end_time = p_end_time,
    notes = p_notes,
    last_log_by = p_last_log_by
    WHERE work_hours_id = p_work_hours_id;
END //

CREATE PROCEDURE deleteWorkHours(IN p_work_hours_id INT)
BEGIN
    DELETE FROM work_hours WHERE work_hours_id = p_work_hours_id;
END //

CREATE PROCEDURE deleteLinkedWorkHours(IN p_work_schedule_id INT)
BEGIN
    DELETE FROM work_hours WHERE work_schedule_id = p_work_schedule_id;
END //

CREATE PROCEDURE getWorkHours(IN p_work_hours_id INT)
BEGIN
	SELECT * FROM work_hours
    WHERE work_hours_id = p_work_hours_id;
END //

CREATE PROCEDURE getLinkedWorkHours(IN p_work_schedule_id INT)
BEGIN
	SELECT * FROM work_hours
    WHERE work_schedule_id = p_work_schedule_id;
END //

CREATE PROCEDURE generateWorkHoursTable(IN p_work_schedule_id INT)
BEGIN
	SELECT work_hours_id, work_date, day_of_week, day_period, start_time, end_time, notes
    FROM work_hours
    WHERE work_schedule_id = p_work_schedule_id 
    ORDER BY work_hours_id;
END //

CREATE PROCEDURE checkFixedWorkHoursOverlap(IN p_work_hours_id INT, IN p_work_schedule_id INT, IN p_day_of_week VARCHAR(15), IN p_day_period VARCHAR(15), IN p_start_time TIME, IN p_end_time TIME)
BEGIN
    IF p_day_period <> 'Break' THEN
        IF p_work_hours_id IS NOT NULL OR p_work_hours_id <> '' THEN
            SELECT COUNT(*) AS total
            FROM work_hours
            WHERE work_hours_id != p_work_hours_id
            AND work_schedule_id = p_work_schedule_id
            AND day_of_week = p_day_of_week
            AND day_period != 'Break'
            AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
        ELSE
            SELECT COUNT(*) AS total
            FROM work_hours
            WHERE work_hours_id != p_work_hours_id
            AND day_of_week = p_day_of_week
            AND day_period != 'Break'
            AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
        END IF;
    END IF;
END //

CREATE PROCEDURE checkFlexibleWorkHoursOverlap(IN p_work_hours_id INT, IN p_work_schedule_id INT, IN p_work_date DATE, IN p_day_period VARCHAR(15), IN p_start_time TIME, IN p_end_time TIME)
BEGIN
    IF p_day_period <> 'Break' THEN
        IF p_work_hours_id IS NOT NULL OR p_work_hours_id <> '' THEN
            SELECT COUNT(*) AS total
            FROM work_hours
            WHERE work_hours_id != p_work_hours_id
            AND work_schedule_id = p_work_schedule_id
            AND work_date = p_work_date
            AND day_period != 'Break'
            AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
        ELSE
            SELECT COUNT(*) AS total
            FROM work_hours
            WHERE work_hours_id != p_work_hours_id
            AND work_date = p_work_date
            AND day_period != 'Break'
            AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
        END IF;
    END IF;
END //

CREATE PROCEDURE getCurrentFixedWorkingHours(IN p_work_schedule_id INT, IN p_day_of_week VARCHAR(15), IN p_current_time TIME)
BEGIN
	SELECT start_time, end_time
    FROM work_hours
    WHERE work_schedule_id = p_work_schedule_id
    AND day_of_week = p_day_of_week
    AND start_time <= p_current_time AND end_time >= p_current_time;
END //

CREATE PROCEDURE getCurrentFlexibleWorkingHours(IN p_work_schedule_id INT, IN p_work_date DATE, IN p_current_time TIME)
BEGIN
	SELECT start_time, end_time
    FROM work_hours
    WHERE work_schedule_id = p_work_schedule_id
    AND work_date = p_work_date
    AND start_time <= p_current_time AND end_time >= p_current_time;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Bank Account Type Table Stored Procedures */

CREATE PROCEDURE checkBankAccountTypeExist (IN p_bank_account_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM bank_account_type
    WHERE bank_account_type_id = p_bank_account_type_id;
END //

CREATE PROCEDURE insertBankAccountType(IN p_bank_account_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_bank_account_type_id INT)
BEGIN
    INSERT INTO bank_account_type (bank_account_type_name, last_log_by) 
	VALUES(p_bank_account_type_name, p_last_log_by);
	
    SET p_bank_account_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBankAccountType(IN p_bank_account_type_id INT, IN p_bank_account_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE bank_account_type
    SET bank_account_type_name = p_bank_account_type_name,
    last_log_by = p_last_log_by
    WHERE bank_account_type_id = p_bank_account_type_id;
END //

CREATE PROCEDURE deleteBankAccountType(IN p_bank_account_type_id INT)
BEGIN
    DELETE FROM bank_account_type WHERE bank_account_type_id = p_bank_account_type_id;
END //

CREATE PROCEDURE getBankAccountType(IN p_bank_account_type_id INT)
BEGIN
	SELECT * FROM bank_account_type
    WHERE bank_account_type_id = p_bank_account_type_id;
END //

CREATE PROCEDURE duplicateBankAccountType(IN p_bank_account_type_id INT, IN p_last_log_by INT, OUT p_new_bank_account_type_id INT)
BEGIN
    DECLARE p_bank_account_type_name VARCHAR(100);
    
    SELECT bank_account_type_name
    INTO p_bank_account_type_name
    FROM bank_account_type 
    WHERE bank_account_type_id = p_bank_account_type_id;
    
    INSERT INTO bank_account_type (bank_account_type_name, last_log_by) 
    VALUES(p_bank_account_type_name, p_last_log_by);
    
    SET p_new_bank_account_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateBankAccountTypeTable()
BEGIN
    SELECT bank_account_type_id, bank_account_type_name
    FROM bank_account_type
    ORDER BY bank_account_type_id;
END //

CREATE PROCEDURE generateBankAccountTypeOptions()
BEGIN
	SELECT bank_account_type_id, bank_account_type_name FROM bank_account_type
	ORDER BY bank_account_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Table Stored Procedures */

CREATE PROCEDURE checkEmployeeExist (IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact
    WHERE contact_id = p_contact_id AND is_employee = 1;
END //

CREATE PROCEDURE insertEmployee(IN p_last_log_by INT, OUT p_contact_id INT)
BEGIN
    INSERT INTO contact (is_employee, last_log_by) 
	VALUES(1, p_last_log_by);
	
    SET p_contact_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE getEmployee(IN p_contact_id INT)
BEGIN
    SELECT * FROM contact
    WHERE contact_id = p_contact_id AND is_employee = 1;
END //

CREATE PROCEDURE grantPortalAccess(IN p_contact_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE contact 
    SET portal_access = 1, last_log_by = p_last_log_by 
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE revokePortalAccess(IN p_contact_id INT, IN p_last_log_by INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    UPDATE users SET is_active = 0, last_log_by = p_last_log_by WHERE user_id IN (SELECT user_id FROM contact WHERE contact_id = p_contact_id);
	UPDATE contact SET portal_access = 0, last_log_by = p_last_log_by WHERE contact_id = p_contact_id;

    COMMIT;
END //

CREATE PROCEDURE generateEmployeeCard(IN p_offset INT, IN p_employee_per_page INT, IN p_search VARCHAR(500), IN p_employment_status VARCHAR(10), IN p_company_filter VARCHAR(500), IN p_department_filter VARCHAR(500), IN p_job_position_filter VARCHAR(500), IN p_branch_filter VARCHAR(500), IN p_employee_type_filter VARCHAR(500), IN p_job_level_filter VARCHAR(500), IN p_gender_filter VARCHAR(500), IN p_civil_status_filter VARCHAR(500), IN p_blood_type_filter VARCHAR(500), IN p_religion_filter VARCHAR(500), p_min_age INT, p_max_age INT)
BEGIN
    DECLARE sql_query VARCHAR(5000);

    SET sql_query = 'SELECT 
        c.contact_id AS contact_id, contact_image, 
        file_as, department_id, branch_id, job_position_id, employment_status 
    FROM contact c
    LEFT JOIN personal_information p ON p.contact_id = c.contact_id
    LEFT JOIN employment_information e ON e.contact_id = c.contact_id
    WHERE c.is_employee = 1';

    IF p_search IS NOT NULL AND p_search <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND (
            p.first_name LIKE ?
            OR p.middle_name LIKE ?
            OR p.last_name LIKE ?
        )');
    END IF;

    IF p_employment_status <> 'all' THEN
        IF p_employment_status = 'active' THEN
            SET sql_query = CONCAT(sql_query, ' AND employment_status = 1');
        ELSE
           SET sql_query = CONCAT(sql_query, ' AND employment_status = 0');
        END IF;
    END IF;

    IF p_company_filter IS NOT NULL AND p_company_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND company_id IN (', p_company_filter, ')');
    END IF;

    IF p_department_filter IS NOT NULL AND p_department_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND department_id IN (', p_department_filter, ')');
    END IF;

    IF p_job_position_filter IS NOT NULL AND p_job_position_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND job_position_id IN (', p_job_position_filter, ')');
    END IF;

    IF p_branch_filter IS NOT NULL AND p_branch_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND branch_id IN (', p_branch_filter, ')');
    END IF;

    IF p_employee_type_filter IS NOT NULL AND p_employee_type_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND employee_type_id IN (', p_employee_type_filter, ')');
    END IF;

    IF p_job_level_filter IS NOT NULL AND p_job_level_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND job_level_id IN (', p_job_level_filter, ')');
    END IF;

    IF p_gender_filter IS NOT NULL AND p_gender_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND gender_id IN (', p_gender_filter, ')');
    END IF;

    IF p_civil_status_filter IS NOT NULL AND p_civil_status_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND civil_status_id IN (', p_civil_status_filter, ')');
    END IF;

    IF p_blood_type_filter IS NOT NULL AND p_blood_type_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND blood_type_id IN (', p_blood_type_filter, ')');
    END IF;

    IF p_religion_filter IS NOT NULL AND p_religion_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND religion_id IN (', p_religion_filter, ')');
    END IF;

    SET sql_query = CONCAT(sql_query, ' ORDER BY p.last_name LIMIT ?, ?;');

    PREPARE stmt FROM sql_query;
    IF p_search IS NOT NULL AND p_search <> '' THEN
        EXECUTE stmt USING CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), p_offset, p_employee_per_page;
    ELSE
        EXECUTE stmt USING p_offset, p_employee_per_page;
    END IF;

    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateEmployeeOptions(IN p_generate_type VARCHAR(50), IN p_reference_id INT)
BEGIN
	IF p_generate_type = 'all' THEN
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM contact WHERE is_employee = 1) ORDER BY file_as;
	ELSEIF p_generate_type = 'active employee' THEN
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM employment_information WHERE employment_status = 1) ORDER BY file_as;
	ELSEIF p_generate_type = 'inactive employee' THEN
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM employment_information WHERE employment_status = 0) ORDER BY file_as;
    ELSEIF p_generate_type = 'department' THEN
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM employment_information WHERE department_id = p_reference_id) ORDER BY file_as;
    ELSEIF p_generate_type = 'department active' THEN
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM employment_information WHERE department_id = p_reference_id) AND contact_id IN (SELECT contact_id FROM employment_information WHERE employment_status = 1) ORDER BY file_as;
    ELSE
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM employment_information WHERE job_position_id = p_reference_id) ORDER BY file_as;
    END IF;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Personal Information Table Stored Procedures */

CREATE PROCEDURE checkPersonalInformationExist (IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM personal_information
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE insertPersonalInformation(IN p_contact_id INT, IN p_file_as VARCHAR(1000), IN p_first_name VARCHAR(300), IN p_middle_name VARCHAR(300), IN p_last_name VARCHAR(300), IN p_suffix VARCHAR(10), IN p_nickname VARCHAR(100), IN p_corporate_name VARCHAR(300), IN p_bio VARCHAR(1000), IN p_civil_status_id INT, IN p_gender_id INT, IN p_religion_id INT, IN p_blood_type_id INT, IN p_birthday DATE, IN p_birth_place VARCHAR(1000), IN p_height FLOAT, IN p_weight FLOAT, IN p_tin VARCHAR(50), IN p_last_log_by INT)
BEGIN
    INSERT INTO personal_information (contact_id, file_as, first_name, middle_name, last_name, suffix, nickname, corporate_name, bio, civil_status_id, gender_id, religion_id, blood_type_id, birthday, birth_place, height, weight, tin, last_log_by) 
	VALUES(p_contact_id, p_file_as, p_first_name, p_middle_name, p_last_name, p_suffix, p_nickname, p_corporate_name, p_bio, p_civil_status_id, p_gender_id, p_religion_id, p_blood_type_id, p_birthday, p_birth_place, p_height, p_weight, p_tin, p_last_log_by);
END //

CREATE PROCEDURE insertPartialPersonalInformation(IN p_contact_id INT, IN p_file_as VARCHAR(1000), IN p_first_name VARCHAR(300), IN p_middle_name VARCHAR(300), IN p_last_name VARCHAR(300), IN p_suffix VARCHAR(10), IN p_last_log_by INT)
BEGIN
    INSERT INTO personal_information (contact_id, file_as, first_name, middle_name, last_name, suffix, last_log_by) 
	VALUES(p_contact_id, p_file_as, p_first_name, p_middle_name, p_last_name, p_suffix, p_last_log_by);
END //

CREATE PROCEDURE updatePersonalInformation(IN p_contact_id INT, IN p_file_as VARCHAR(1000), IN p_first_name VARCHAR(300), IN p_middle_name VARCHAR(300), IN p_last_name VARCHAR(300), IN p_suffix VARCHAR(10), IN p_nickname VARCHAR(100), IN p_corporate_name VARCHAR(300), IN p_bio VARCHAR(1000), IN p_civil_status_id INT, IN p_gender_id INT, IN p_religion_id INT, IN p_blood_type_id INT, IN p_birthday DATE, IN p_birth_place VARCHAR(1000), IN p_height FLOAT, IN p_weight FLOAT, IN p_tin VARCHAR(50), IN p_last_log_by INT)
BEGIN
	UPDATE personal_information
    SET file_as = p_file_as,
    first_name = p_first_name,
    middle_name = p_middle_name,
    last_name = p_last_name,
    suffix = p_suffix,
    nickname = p_nickname,
    corporate_name = p_corporate_name,
    bio = p_bio,
    civil_status_id = p_civil_status_id,
    gender_id = p_gender_id,
    religion_id = p_religion_id,
    blood_type_id = p_blood_type_id,
    birthday = p_birthday,
    birth_place = p_birth_place,
    height = p_height,
    weight = p_weight,
    tin = p_tin,
    last_log_by = p_last_log_by
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE archiveEmployee(IN p_contact_id INT, IN p_offboard_date DATE,  IN p_departure_reason_id  INT, IN p_detailed_departure_reason VARCHAR(5000), IN p_last_log_by INT)
BEGIN
	UPDATE employment_information
    SET offboard_date = p_offboard_date,
    employment_status = 0,
    departure_reason_id = p_departure_reason_id,
    detailed_departure_reason = p_detailed_departure_reason,
    last_log_by = p_last_log_by
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE unarchiveEmployee(IN p_contact_id INT, IN p_onboard_date DATE, IN p_last_log_by INT)
BEGIN
	UPDATE employment_information
    SET onboard_date = p_onboard_date,
    employment_status = 1,
    offboard_date = null,
    last_log_by = p_last_log_by
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE updateContactImage(IN p_contact_id INT, IN p_contact_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE personal_information 
    SET contact_image = p_contact_image, last_log_by = p_last_log_by 
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE getPersonalInformation(IN p_contact_id INT)
BEGIN
	SELECT * FROM personal_information
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE generatePersonalInformationSummary(IN p_contact_id INT)
BEGIN
	SELECT *
    FROM personal_information
    WHERE contact_id = p_contact_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Employment Information Table Stored Procedures */

CREATE PROCEDURE checkEmploymentInformationExist (IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM employment_information
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE insertPartialEmploymentInformation(IN p_contact_id INT, IN p_company_id INT, IN p_department_id INT, IN p_job_position_id INT, IN p_branch_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO employment_information (contact_id, company_id, department_id, job_position_id, branch_id, last_log_by) 
	VALUES(p_contact_id, p_company_id, p_department_id, p_job_position_id, p_branch_id, p_last_log_by);
END //

CREATE PROCEDURE insertEmploymentInformation(IN p_contact_id INT, IN p_badge_id VARCHAR(500), IN p_company_id INT, IN p_employee_type_id INT, IN p_department_id INT, IN p_job_position_id INT, IN p_job_level_id INT, IN p_branch_id INT, IN p_manager_id INT, IN p_leave_approver_id INT, IN p_work_schedule_id INT, IN p_kiosk_pin_code VARCHAR(255), IN p_biometrics_id VARCHAR(500), IN p_onboard_date DATE, IN p_last_log_by INT)
BEGIN
    INSERT INTO employment_information (contact_id, badge_id, company_id, employee_type_id, department_id, job_position_id, job_level_id, branch_id, manager_id, leave_approver_id, work_schedule_id, kiosk_pin_code, biometrics_id, onboard_date, last_log_by) 
	VALUES(p_contact_id, p_badge_id, p_company_id, p_employee_type_id, p_department_id, p_job_position_id, p_job_level_id, p_branch_id, p_manager_id, p_leave_approver_id, p_work_schedule_id, p_kiosk_pin_code, p_biometrics_id, p_onboard_date, p_last_log_by);
END //

CREATE PROCEDURE updateEmploymentInformation(IN p_contact_id INT, IN p_badge_id VARCHAR(500), IN p_company_id INT, IN p_employee_type_id INT, IN p_department_id INT, IN p_job_position_id INT, IN p_job_level_id INT, IN p_branch_id INT, IN p_manager_id INT, IN p_leave_approver_id INT, IN p_work_schedule_id INT, IN p_kiosk_pin_code VARCHAR(255), IN p_biometrics_id VARCHAR(500), IN p_onboard_date DATE, IN p_last_log_by INT)
BEGIN
	UPDATE employment_information
    SET badge_id = p_badge_id,
    company_id = p_company_id,
    employee_type_id = p_employee_type_id,
    department_id = p_department_id,
    job_position_id = p_job_position_id,
    job_level_id = p_job_level_id,
    branch_id = p_branch_id,
    manager_id = p_manager_id,
    leave_approver_id = p_leave_approver_id,
    work_schedule_id = p_work_schedule_id,
    kiosk_pin_code = p_kiosk_pin_code,
    biometrics_id = p_biometrics_id,
    onboard_date = p_onboard_date,
    last_log_by = p_last_log_by
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE getEmploymentInformation(IN p_contact_id INT)
BEGIN
	SELECT * FROM employment_information
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE getEmploymentInformationByBiometricsID(IN p_biometrics_id VARCHAR(500), IN p_company_id INT)
BEGIN
	SELECT * FROM employment_information
    WHERE biometrics_id = p_biometrics_id AND company_id = p_company_id;
END //

CREATE PROCEDURE generateEmploymentInformationSummary(IN p_contact_id INT)
BEGIN
	SELECT *
    FROM employment_information
    WHERE contact_id = p_contact_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Contact Information Table Stored Procedures */

CREATE PROCEDURE checkContactInformationExist (IN p_contact_information_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_information
    WHERE contact_information_id = p_contact_information_id;
END //

CREATE PROCEDURE insertContactInformation(IN p_contact_id INT, IN p_contact_information_type_id INT, IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_facebook VARCHAR(300), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_information (contact_id, contact_information_type_id, mobile, telephone, email, facebook, last_log_by) 
	VALUES(p_contact_id, p_contact_information_type_id, p_mobile, p_telephone, p_email, p_facebook, p_last_log_by);
END //

CREATE PROCEDURE updateContactInformation(IN p_contact_information_id INT, IN p_contact_id INT, IN p_contact_information_type_id INT, IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_facebook VARCHAR(300), IN p_last_log_by INT)
BEGIN
	UPDATE contact_information
    SET contact_id = p_contact_id,
    contact_information_type_id = p_contact_information_type_id,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    facebook = p_facebook,
    last_log_by = p_last_log_by
    WHERE contact_information_id = p_contact_information_id;
END //

CREATE PROCEDURE deleteContactInformation(IN p_contact_information_id INT)
BEGIN
    DELETE FROM contact_information WHERE contact_information_id = p_contact_information_id;
END //

CREATE PROCEDURE getContactInformation(IN p_contact_information_id INT)
BEGIN
	SELECT * FROM contact_information
    WHERE contact_information_id = p_contact_information_id;
END //

CREATE PROCEDURE updateContactInformationStatus(IN p_contact_information_id INT, IN p_contact_id INT, IN p_last_log_by INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	UPDATE contact_information SET is_primary = 0 WHERE contact_information_id != p_contact_information_id AND contact_id = p_contact_id;
	UPDATE contact_information SET is_primary = 1, last_log_by = p_last_log_by WHERE contact_information_id = p_contact_information_id AND contact_id = p_contact_id;

    COMMIT;
END //

CREATE PROCEDURE generateContactInformationSummary(IN p_contact_id INT)
BEGIN
	SELECT *
    FROM contact_information
    WHERE contact_id = p_contact_id 
    ORDER BY is_primary DESC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Address Table Stored Procedures */

CREATE PROCEDURE checkContactAddressExist (IN p_contact_address_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_address
    WHERE contact_address_id = p_contact_address_id;
END //

CREATE PROCEDURE insertContactAddress(IN p_contact_id INT, IN p_address_type_id INT, IN p_address VARCHAR(1000), IN p_city_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_address (contact_id, address_type_id, address, city_id, last_log_by) 
	VALUES(p_contact_id, p_address_type_id, p_address, p_city_id, p_last_log_by);
END //

CREATE PROCEDURE updateContactAddress(IN p_contact_address_id INT, IN p_contact_id INT, IN p_address_type_id INT, IN p_address VARCHAR(1000), IN p_city_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE contact_address
    SET contact_id = p_contact_id,
    address_type_id = p_address_type_id,
    address = p_address,
    city_id = p_city_id,
    last_log_by = p_last_log_by
    WHERE contact_address_id = p_contact_address_id;
END //

CREATE PROCEDURE deleteContactAddress(IN p_contact_address_id INT)
BEGIN
    DELETE FROM contact_address WHERE contact_address_id = p_contact_address_id;
END //

CREATE PROCEDURE getContactAddress(IN p_contact_address_id INT)
BEGIN
	SELECT * FROM contact_address
    WHERE contact_address_id = p_contact_address_id;
END //

CREATE PROCEDURE updateContactAddressStatus(IN p_contact_address_id INT, IN p_contact_id INT, IN p_last_log_by INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	UPDATE contact_address SET is_primary = 0 WHERE contact_address_id != p_contact_address_id AND contact_id = p_contact_id;
	UPDATE contact_address SET is_primary = 1, last_log_by = p_last_log_by WHERE contact_address_id = p_contact_address_id AND contact_id = p_contact_id;

    COMMIT;
END //

CREATE PROCEDURE generateContactAddressSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_address_id, address_type_id, address, city_id, is_primary
    FROM contact_address
    WHERE contact_id = p_contact_id 
    ORDER BY is_primary DESC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Identification Table Stored Procedures */

CREATE PROCEDURE checkContactDocumentExist (IN p_contact_document_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_document
    WHERE contact_document_id = p_contact_document_id;
END //

CREATE PROCEDURE insertContactDocument(IN p_contact_id INT, IN p_document_name VARCHAR(100), IN p_document_type VARCHAR(100), IN p_last_log_by INT, OUT p_contact_document_id INT)
BEGIN
    INSERT INTO contact_document (contact_id, document_name, document_type, last_log_by) 
	VALUES(p_contact_id, p_document_name, p_document_type, p_last_log_by);

     SET p_contact_document_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateContactDocumentFile(IN p_contact_document_id INT, IN p_document_file VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_document
    SET document_file = p_document_file,
    last_log_by = p_last_log_by
    WHERE contact_document_id = p_contact_document_id;
END //

CREATE PROCEDURE deleteContactDocument(IN p_contact_document_id INT)
BEGIN
    DELETE FROM contact_document WHERE contact_document_id = p_contact_document_id;
END //

CREATE PROCEDURE generateContactDocumentSummary(IN p_contact_id INT)
BEGIN
	SELECT *
    FROM contact_document
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE checkContactIdentificationExist (IN p_contact_identification_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_identification
    WHERE contact_identification_id = p_contact_identification_id;
END //

CREATE PROCEDURE insertContactIdentification(IN p_contact_id INT, IN p_id_type_id INT, IN p_id_number VARCHAR(100), IN p_last_log_by INT, OUT p_contact_identification_id INT)
BEGIN
    INSERT INTO contact_identification (contact_id, id_type_id, id_number, last_log_by) 
	VALUES(p_contact_id, p_id_type_id, p_id_number, p_last_log_by);
	
    SET p_contact_identification_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateContactIdentification(IN p_contact_identification_id INT, IN p_contact_id INT, IN p_id_type_id INT, IN p_id_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE contact_identification
    SET contact_id = p_contact_id,
    id_type_id = p_id_type_id,
    id_number = p_id_number,
    last_log_by = p_last_log_by
    WHERE contact_identification_id = p_contact_identification_id;
END //

CREATE PROCEDURE updateContactIdentificationImage(IN p_contact_identification_id INT, IN p_id_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_identification
    SET id_image = p_id_image,
    last_log_by = p_last_log_by
    WHERE contact_identification_id = p_contact_identification_id;
END //

CREATE PROCEDURE deleteContactIdentification(IN p_contact_identification_id INT)
BEGIN
    DELETE FROM contact_identification WHERE contact_identification_id = p_contact_identification_id;
END //

CREATE PROCEDURE getContactIdentification(IN p_contact_identification_id INT)
BEGIN
	SELECT * FROM contact_identification
    WHERE contact_identification_id = p_contact_identification_id;
END //

CREATE PROCEDURE updateContactIdentificationStatus(IN p_contact_identification_id INT, IN p_contact_id INT, IN p_last_log_by INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

	UPDATE contact_identification SET is_primary = 0 WHERE contact_identification_id != p_contact_identification_id AND contact_id = p_contact_id;
	UPDATE contact_identification SET is_primary = 1, last_log_by = p_last_log_by WHERE contact_identification_id = p_contact_identification_id AND contact_id = p_contact_id;

    COMMIT;
END //

CREATE PROCEDURE generateContactIdentificationSummary(IN p_contact_id INT)
BEGIN
	SELECT *
    FROM contact_identification
    WHERE contact_id = p_contact_id 
    ORDER BY is_primary DESC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Educational Background Table Stored Procedures */

CREATE PROCEDURE checkContactEducationalBackgroundExist (IN p_contact_educational_background_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_educational_background
    WHERE contact_educational_background_id = p_contact_educational_background_id;
END //

CREATE PROCEDURE insertContactEducationalBackground(IN p_contact_id INT, IN p_educational_stage_id INT, IN p_institution_name VARCHAR(500), IN p_degree_earned VARCHAR(500), IN p_field_of_study VARCHAR(500), IN p_start_month VARCHAR(10), IN p_start_year VARCHAR(10), IN p_end_month VARCHAR(10), IN p_end_year VARCHAR(10), IN p_course_highlights TEXT, IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_educational_background (contact_id, educational_stage_id, institution_name, degree_earned, field_of_study, start_month, start_year, end_month, end_year, course_highlights, last_log_by) 
	VALUES(p_contact_id, p_educational_stage_id, p_institution_name, p_degree_earned, p_field_of_study, p_start_month, p_start_year, p_end_month, p_end_year, p_course_highlights, p_last_log_by);
END //

CREATE PROCEDURE updateContactEducationalBackground(IN p_contact_educational_background_id INT, IN p_contact_id INT, IN p_educational_stage_id INT, IN p_institution_name VARCHAR(500), IN p_degree_earned VARCHAR(500), IN p_field_of_study VARCHAR(500), IN p_start_month VARCHAR(10), IN p_start_year VARCHAR(10), IN p_end_month VARCHAR(10), IN p_end_year VARCHAR(10), IN p_course_highlights TEXT, IN p_last_log_by INT)
BEGIN
	UPDATE contact_educational_background
    SET contact_id = p_contact_id,
    educational_stage_id = p_educational_stage_id,
    institution_name = p_institution_name,
    degree_earned = p_degree_earned,
    field_of_study = p_field_of_study,
    start_month = p_start_month,
    start_year = p_start_year,
    end_month = p_end_month,
    end_year = p_end_year,
    course_highlights = p_course_highlights,
    last_log_by = p_last_log_by
    WHERE contact_educational_background_id = p_contact_educational_background_id;
END //

CREATE PROCEDURE deleteContactEducationalBackground(IN p_contact_educational_background_id INT)
BEGIN
    DELETE FROM contact_educational_background WHERE contact_educational_background_id = p_contact_educational_background_id;
END //

CREATE PROCEDURE getContactEducationalBackground(IN p_contact_educational_background_id INT)
BEGIN
	SELECT * FROM contact_educational_background
    WHERE contact_educational_background_id = p_contact_educational_background_id;
END //

CREATE PROCEDURE generateContactEducationalBackgroundSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_educational_background_id, educational_stage_id, institution_name, degree_earned, field_of_study, start_month, start_year, end_month, end_year, course_highlights
    FROM contact_educational_background
    WHERE contact_id = p_contact_id 
    ORDER BY start_year DESC, start_year DESC, end_month ASC, end_year ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Family Background Table Stored Procedures */

CREATE PROCEDURE checkContactFamilyBackgroundExist (IN p_contact_family_background_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_family_background
    WHERE contact_family_background_id = p_contact_family_background_id;
END //

CREATE PROCEDURE insertContactFamilyBackground(IN p_contact_id INT, IN p_family_name VARCHAR(500), IN p_relation_id INT, IN p_birthday DATE, IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_family_background (contact_id, family_name, relation_id, birthday, mobile, telephone, email, last_log_by) 
	VALUES(p_contact_id, p_family_name, p_relation_id, p_birthday, p_mobile, p_telephone, p_email, p_last_log_by);
END //

CREATE PROCEDURE updateContactFamilyBackground(IN p_contact_family_background_id INT, IN p_contact_id INT, IN p_family_name VARCHAR(500), IN p_relation_id INT, IN p_birthday DATE, IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE contact_family_background
    SET contact_id = p_contact_id,
    family_name = p_family_name,
    relation_id = p_relation_id,
    birthday = p_birthday,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    last_log_by = p_last_log_by
    WHERE contact_family_background_id = p_contact_family_background_id;
END //

CREATE PROCEDURE deleteContactFamilyBackground(IN p_contact_family_background_id INT)
BEGIN
    DELETE FROM contact_family_background WHERE contact_family_background_id = p_contact_family_background_id;
END //

CREATE PROCEDURE getContactFamilyBackground(IN p_contact_family_background_id INT)
BEGIN
	SELECT * FROM contact_family_background
    WHERE contact_family_background_id = p_contact_family_background_id;
END //

CREATE PROCEDURE generateContactFamilyBackgroundSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_family_background_id, family_name, relation_id, birthday, mobile, telephone, email
    FROM contact_family_background
    WHERE contact_id = p_contact_id 
    ORDER BY family_name ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Emergency Contact Table Stored Procedures */

CREATE PROCEDURE checkContactEmergencyContactExist (IN p_contact_emergency_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_emergency_contact
    WHERE contact_emergency_contact_id = p_contact_emergency_contact_id;
END //

CREATE PROCEDURE insertContactEmergencyContact(IN p_contact_id INT, IN p_emergency_contact_name VARCHAR(500), IN p_relation_id INT, IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_emergency_contact (contact_id, emergency_contact_name, relation_id, mobile, telephone, email, last_log_by) 
	VALUES(p_contact_id, p_emergency_contact_name, p_relation_id, p_mobile, p_telephone, p_email, p_last_log_by);
END //

CREATE PROCEDURE updateContactEmergencyContact(IN p_contact_emergency_contact_id INT, IN p_contact_id INT, IN p_emergency_contact_name VARCHAR(500), IN p_relation_id INT, IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE contact_emergency_contact
    SET contact_id = p_contact_id,
    emergency_contact_name = p_emergency_contact_name,
    relation_id = p_relation_id,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    last_log_by = p_last_log_by
    WHERE contact_emergency_contact_id = p_contact_emergency_contact_id;
END //

CREATE PROCEDURE deleteContactEmergencyContact(IN p_contact_emergency_contact_id INT)
BEGIN
    DELETE FROM contact_emergency_contact WHERE contact_emergency_contact_id = p_contact_emergency_contact_id;
END //

CREATE PROCEDURE getContactEmergencyContact(IN p_contact_emergency_contact_id INT)
BEGIN
	SELECT * FROM contact_emergency_contact
    WHERE contact_emergency_contact_id = p_contact_emergency_contact_id;
END //

CREATE PROCEDURE generateContactEmergencyContactSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_emergency_contact_id, emergency_contact_name, relation_id, mobile, telephone, email
    FROM contact_emergency_contact
    WHERE contact_id = p_contact_id 
    ORDER BY emergency_contact_name ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Training Table Stored Procedures */

CREATE PROCEDURE checkContactTrainingExist (IN p_contact_training_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_training
    WHERE contact_training_id = p_contact_training_id;
END //

CREATE PROCEDURE insertContactTraining(IN p_contact_id INT, IN p_training_name VARCHAR(500), IN p_training_date DATE, IN p_training_location VARCHAR(500), IN p_training_provider VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_training (contact_id, training_name, training_date, training_location, training_provider, last_log_by) 
	VALUES(p_contact_id, p_training_name, p_training_date, p_training_location, p_training_provider, p_last_log_by);
END //

CREATE PROCEDURE updateContactTraining(IN p_contact_training_id INT, IN p_contact_id INT, IN p_training_name VARCHAR(500), IN p_training_date DATE, IN p_training_location VARCHAR(500), IN p_training_provider VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_training
    SET contact_id = p_contact_id,
    training_name = p_training_name,
    training_date = p_training_date,
    training_location = p_training_location,
    training_provider = p_training_provider,
    last_log_by = p_last_log_by
    WHERE contact_training_id = p_contact_training_id;
END //

CREATE PROCEDURE deleteContactTraining(IN p_contact_training_id INT)
BEGIN
    DELETE FROM contact_training WHERE contact_training_id = p_contact_training_id;
END //

CREATE PROCEDURE getContactTraining(IN p_contact_training_id INT)
BEGIN
	SELECT * FROM contact_training
    WHERE contact_training_id = p_contact_training_id;
END //

CREATE PROCEDURE generateContactTrainingSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_training_id, training_name, training_date, training_location, training_provider
    FROM contact_training
    WHERE contact_id = p_contact_id 
    ORDER BY training_date ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Skills Table Stored Procedures */

CREATE PROCEDURE checkContactSkillsExist (IN p_contact_skills_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_skills
    WHERE contact_skills_id = p_contact_skills_id;
END //

CREATE PROCEDURE insertContactSkills(IN p_contact_id INT, IN p_skill_name VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_skills (contact_id, skill_name, last_log_by) 
	VALUES(p_contact_id, p_skill_name, p_last_log_by);
END //

CREATE PROCEDURE updateContactSkills(IN p_contact_skills_id INT, IN p_contact_id INT, IN p_skill_name VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_skills
    SET contact_id = p_contact_id,
    skill_name = p_skill_name,
    last_log_by = p_last_log_by
    WHERE contact_skills_id = p_contact_skills_id;
END //

CREATE PROCEDURE deleteContactSkills(IN p_contact_skills_id INT)
BEGIN
    DELETE FROM contact_skills WHERE contact_skills_id = p_contact_skills_id;
END //

CREATE PROCEDURE getContactSkills(IN p_contact_skills_id INT)
BEGIN
	SELECT * FROM contact_skills
    WHERE contact_skills_id = p_contact_skills_id;
END //

CREATE PROCEDURE generateContactSkillsSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_skills_id, skill_name
    FROM contact_skills
    WHERE contact_id = p_contact_id 
    ORDER BY skill_name ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Talents Table Stored Procedures */

CREATE PROCEDURE checkContactTalentsExist (IN p_contact_talents_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_talents
    WHERE contact_talents_id = p_contact_talents_id;
END //

CREATE PROCEDURE insertContactTalents(IN p_contact_id INT, IN p_talent_name VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_talents (contact_id, talent_name, last_log_by) 
	VALUES(p_contact_id, p_talent_name, p_last_log_by);
END //

CREATE PROCEDURE updateContactTalents(IN p_contact_talents_id INT, IN p_contact_id INT, IN p_talent_name VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_talents
    SET contact_id = p_contact_id,
    talent_name = p_talent_name,
    last_log_by = p_last_log_by
    WHERE contact_talents_id = p_contact_talents_id;
END //

CREATE PROCEDURE deleteContactTalents(IN p_contact_talents_id INT)
BEGIN
    DELETE FROM contact_talents WHERE contact_talents_id = p_contact_talents_id;
END //

CREATE PROCEDURE getContactTalents(IN p_contact_talents_id INT)
BEGIN
	SELECT * FROM contact_talents
    WHERE contact_talents_id = p_contact_talents_id;
END //

CREATE PROCEDURE generateContactTalentsSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_talents_id, talent_name
    FROM contact_talents
    WHERE contact_id = p_contact_id 
    ORDER BY talent_name ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Hobby Table Stored Procedures */

CREATE PROCEDURE checkContactHobbyExist (IN p_contact_hobby_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_hobby
    WHERE contact_hobby_id = p_contact_hobby_id;
END //

CREATE PROCEDURE insertContactHobby(IN p_contact_id INT, IN p_hobby_name VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_hobby (contact_id, hobby_name, last_log_by) 
	VALUES(p_contact_id, p_hobby_name, p_last_log_by);
END //

CREATE PROCEDURE updateContactHobby(IN p_contact_hobby_id INT, IN p_contact_id INT, IN p_hobby_name VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_hobby
    SET contact_id = p_contact_id,
    hobby_name = p_hobby_name,
    last_log_by = p_last_log_by
    WHERE contact_hobby_id = p_contact_hobby_id;
END //

CREATE PROCEDURE deleteContactHobby(IN p_contact_hobby_id INT)
BEGIN
    DELETE FROM contact_hobby WHERE contact_hobby_id = p_contact_hobby_id;
END //

CREATE PROCEDURE getContactHobby(IN p_contact_hobby_id INT)
BEGIN
	SELECT * FROM contact_hobby
    WHERE contact_hobby_id = p_contact_hobby_id;
END //

CREATE PROCEDURE generateContactHobbySummary(IN p_contact_id INT)
BEGIN
	SELECT contact_hobby_id, hobby_name
    FROM contact_hobby
    WHERE contact_id = p_contact_id 
    ORDER BY hobby_name ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Contact Employment History Table Stored Procedures */

CREATE PROCEDURE checkContactEmploymentHistoryExist (IN p_contact_employment_history_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_employment_history
    WHERE contact_employment_history_id = p_contact_employment_history_id;
END //

CREATE PROCEDURE insertContactEmploymentHistory(IN p_contact_id INT, IN p_company VARCHAR(500), IN p_address VARCHAR(500), IN p_last_position_held VARCHAR(500), IN p_start_month VARCHAR(10), IN p_start_year VARCHAR(10), IN p_end_month VARCHAR(10), IN p_end_year VARCHAR(10), IN p_basic_function TEXT, IN p_starting_salary DOUBLE, IN p_final_salary DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_employment_history (contact_id, company, address, last_position_held, start_month, start_year, end_month, end_year, basic_function, starting_salary, final_salary, last_log_by) 
	VALUES(p_contact_id, p_company, p_address, p_last_position_held, p_start_month, p_start_year, p_end_month, p_end_year, p_basic_function, p_starting_salary, p_final_salary, p_last_log_by);
END //

CREATE PROCEDURE updateContactEmploymentHistory(IN p_contact_employment_history_id INT, IN p_contact_id INT, IN p_company VARCHAR(500), IN p_address VARCHAR(500), IN p_last_position_held VARCHAR(500), IN p_start_month VARCHAR(10), IN p_start_year VARCHAR(10), IN p_end_month VARCHAR(10), IN p_end_year VARCHAR(10), IN p_basic_function TEXT, IN p_starting_salary DOUBLE, IN p_final_salary DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE contact_employment_history
    SET contact_id = p_contact_id,
    company = p_company,
    address = p_address,
    last_position_held = p_last_position_held,
    start_month = p_start_month,
    start_year = p_start_year,
    end_month = p_end_month,
    end_year = p_end_year,
    basic_function = p_basic_function,
    starting_salary = p_starting_salary,
    final_salary = p_final_salary,
    last_log_by = p_last_log_by
    WHERE contact_employment_history_id = p_contact_employment_history_id;
END //

CREATE PROCEDURE deleteContactEmploymentHistory(IN p_contact_employment_history_id INT)
BEGIN
    DELETE FROM contact_employment_history WHERE contact_employment_history_id = p_contact_employment_history_id;
END //

CREATE PROCEDURE getContactEmploymentHistory(IN p_contact_employment_history_id INT)
BEGIN
	SELECT * FROM contact_employment_history
    WHERE contact_employment_history_id = p_contact_employment_history_id;
END //

CREATE PROCEDURE generateContactEmploymentHistorySummary(IN p_contact_id INT)
BEGIN
	SELECT contact_employment_history_id, company, address, last_position_held, start_month, start_year, end_month, end_year, basic_function, starting_salary, final_salary
    FROM contact_employment_history
    WHERE contact_id = p_contact_id 
    ORDER BY start_year DESC, start_year DESC, end_month ASC, end_year ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Contact License Table Stored Procedures */

CREATE PROCEDURE checkContactLicenseExist (IN p_contact_license_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_license
    WHERE contact_license_id = p_contact_license_id;
END //

CREATE PROCEDURE insertContactLicense(IN p_contact_id INT, IN p_license_name VARCHAR(500), IN p_issuing_organization VARCHAR(500), IN p_start_month VARCHAR(10), IN p_start_year VARCHAR(10), IN p_end_month VARCHAR(10), IN p_end_year VARCHAR(10), IN p_description VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_license (contact_id, license_name, issuing_organization, start_month, start_year, end_month, end_year, description, last_log_by) 
	VALUES(p_contact_id, p_license_name, p_issuing_organization, p_start_month, p_start_year, p_end_month, p_end_year, p_description, p_last_log_by);
END //

CREATE PROCEDURE updateContactLicense(IN p_contact_license_id INT, IN p_contact_id INT, IN p_license_name VARCHAR(500), IN p_issuing_organization VARCHAR(500), IN p_start_month VARCHAR(10), IN p_start_year VARCHAR(10), IN p_end_month VARCHAR(10), IN p_end_year VARCHAR(10), IN p_description VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_license
    SET contact_id = p_contact_id,
    license_name = p_license_name,
    issuing_organization = p_issuing_organization,
    start_month = p_start_month,
    start_year = p_start_year,
    end_month = p_end_month,
    end_year = p_end_year,
    description = p_description,
    last_log_by = p_last_log_by
    WHERE contact_license_id = p_contact_license_id;
END //

CREATE PROCEDURE deleteContactLicense(IN p_contact_license_id INT)
BEGIN
    DELETE FROM contact_license WHERE contact_license_id = p_contact_license_id;
END //

CREATE PROCEDURE getContactLicense(IN p_contact_license_id INT)
BEGIN
	SELECT * FROM contact_license
    WHERE contact_license_id = p_contact_license_id;
END //

CREATE PROCEDURE generateContactLicenseSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_license_id, license_name, issuing_organization, start_month, start_year, end_month, end_year, description
    FROM contact_license
    WHERE contact_id = p_contact_id 
    ORDER BY start_year DESC, start_year DESC, end_month ASC, end_year ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Contact Language Table Stored Procedures */

CREATE PROCEDURE checkContactLanguageExist (IN p_contact_language_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_language
    WHERE contact_language_id = p_contact_language_id;
END //

CREATE PROCEDURE insertContactLanguage(IN p_contact_id INT, IN p_language_id INT, IN p_language_proficiency_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_language (contact_id, language_id, language_proficiency_id, last_log_by) 
	VALUES(p_contact_id, p_language_id, p_language_proficiency_id, p_last_log_by);
END //

CREATE PROCEDURE updateContactLanguage(IN p_contact_language_id INT, IN p_contact_id INT, IN p_language_id INT, IN p_language_proficiency_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE contact_language
    SET contact_id = p_contact_id,
    language_id = p_language_id,
    language_proficiency_id = p_language_proficiency_id,
    last_log_by = p_last_log_by
    WHERE contact_language_id = p_contact_language_id;
END //

CREATE PROCEDURE deleteContactLanguage(IN p_contact_language_id INT)
BEGIN
    DELETE FROM contact_language WHERE contact_language_id = p_contact_language_id;
END //

CREATE PROCEDURE getContactLanguage(IN p_contact_language_id INT)
BEGIN
	SELECT * FROM contact_language
    WHERE contact_language_id = p_contact_language_id;
END //

CREATE PROCEDURE generateContactLanguageSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_language_id, language_id, language_proficiency_id
    FROM contact_language
    WHERE contact_id = p_contact_id 
    ORDER BY language_id ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Language Stored Procedures */

CREATE PROCEDURE checkLanguageExist (IN p_language_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM language
    WHERE language_id = p_language_id;
END //

CREATE PROCEDURE insertLanguage(IN p_language_name VARCHAR(100), IN p_last_log_by INT, OUT p_language_id INT)
BEGIN
    INSERT INTO language (language_name, last_log_by) 
	VALUES(p_language_name, p_last_log_by);
	
    SET p_language_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateLanguage(IN p_language_id INT, IN p_language_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE language
    SET language_name = p_language_name,
    last_log_by = p_last_log_by
    WHERE language_id = p_language_id;
END //

CREATE PROCEDURE deleteLanguage(IN p_language_id INT)
BEGIN
    DELETE FROM language WHERE language_id = p_language_id;
END //

CREATE PROCEDURE getLanguage(IN p_language_id INT)
BEGIN
	SELECT * FROM language
    WHERE language_id = p_language_id;
END //

CREATE PROCEDURE duplicateLanguage(IN p_language_id INT, IN p_last_log_by INT, OUT p_new_language_id INT)
BEGIN
    DECLARE p_language_name VARCHAR(100);
    
    SELECT language_name
    INTO p_language_name
    FROM language 
    WHERE language_id = p_language_id;
    
    INSERT INTO language (language_name, last_log_by) 
    VALUES(p_language_name, p_last_log_by);
    
    SET p_new_language_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateLanguageTable()
BEGIN
    SELECT language_id, language_name
    FROM language
    ORDER BY language_id;
END //

CREATE PROCEDURE generateLanguageOptions()
BEGIN
	SELECT language_id, language_name FROM language
	ORDER BY language_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Language Proficiency Table Stored Procedures */

CREATE PROCEDURE checkLanguageProficiencyExist (IN p_language_proficiency_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM language_proficiency
    WHERE language_proficiency_id = p_language_proficiency_id;
END //

CREATE PROCEDURE insertLanguageProficiency(IN p_language_proficiency_name VARCHAR(100), IN p_description VARCHAR(100), IN p_last_log_by INT, OUT p_language_proficiency_id INT)
BEGIN
    INSERT INTO language_proficiency (language_proficiency_name, description, last_log_by) 
	VALUES(p_language_proficiency_name, p_description, p_last_log_by);
	
    SET p_language_proficiency_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateLanguageProficiency(IN p_language_proficiency_id INT, IN p_language_proficiency_name VARCHAR(100), IN p_description VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE language_proficiency
    SET language_proficiency_name = p_language_proficiency_name,
    description = p_description,
    last_log_by = p_last_log_by
    WHERE language_proficiency_id = p_language_proficiency_id;
END //

CREATE PROCEDURE deleteLanguageProficiency(IN p_language_proficiency_id INT)
BEGIN
    DELETE FROM language_proficiency WHERE language_proficiency_id = p_language_proficiency_id;
END //

CREATE PROCEDURE getLanguageProficiency(IN p_language_proficiency_id INT)
BEGIN
	SELECT * FROM language_proficiency
    WHERE language_proficiency_id = p_language_proficiency_id;
END //

CREATE PROCEDURE duplicateLanguageProficiency(IN p_language_proficiency_id INT, IN p_last_log_by INT, OUT p_new_language_proficiency_id INT)
BEGIN
    DECLARE p_language_proficiency_name VARCHAR(100);
    DECLARE p_description VARCHAR(100);
    
    SELECT language_proficiency_name, description
    INTO p_language_proficiency_name, p_description
    FROM language_proficiency 
    WHERE language_proficiency_id = p_language_proficiency_id;
    
    INSERT INTO language_proficiency (language_proficiency_name, description, last_log_by) 
    VALUES(p_language_proficiency_name, p_description, p_last_log_by);
    
    SET p_new_language_proficiency_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateLanguageProficiencyTable()
BEGIN
    SELECT language_proficiency_id, language_proficiency_name, description
    FROM language_proficiency
    ORDER BY language_proficiency_id;
END //

CREATE PROCEDURE generateLanguageProficiencyOptions()
BEGIN
	SELECT language_proficiency_id, language_proficiency_name, description FROM language_proficiency
	ORDER BY language_proficiency_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Contact Bank Table Stored Procedures */

CREATE PROCEDURE checkContactBankExist (IN p_contact_bank_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_bank
    WHERE contact_bank_id = p_contact_bank_id;
END //

CREATE PROCEDURE insertContactBank(IN p_contact_id INT, IN p_bank_id INT, IN p_bank_account_type_id INT, IN p_account_number VARCHAR(30), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_bank (contact_id, bank_id, bank_account_type_id, account_number, last_log_by) 
	VALUES(p_contact_id, p_bank_id, p_bank_account_type_id, p_account_number, p_last_log_by);
END //

CREATE PROCEDURE updateContactBank(IN p_contact_bank_id INT, IN p_contact_id INT, IN p_bank_id INT, IN p_bank_account_type_id INT, IN p_account_number VARCHAR(30), IN p_last_log_by INT)
BEGIN
	UPDATE contact_bank
    SET contact_id = p_contact_id,
    bank_id = p_bank_id,
    bank_account_type_id = p_bank_account_type_id,
    account_number = p_account_number,
    last_log_by = p_last_log_by
    WHERE contact_bank_id = p_contact_bank_id;
END //

CREATE PROCEDURE deleteContactBank(IN p_contact_bank_id INT)
BEGIN
    DELETE FROM contact_bank WHERE contact_bank_id = p_contact_bank_id;
END //

CREATE PROCEDURE getContactBank(IN p_contact_bank_id INT)
BEGIN
	SELECT * FROM contact_bank
    WHERE contact_bank_id = p_contact_bank_id;
END //

CREATE PROCEDURE generateContactBankSummary(IN p_contact_id INT)
BEGIN
	SELECT contact_bank_id, bank_id, bank_account_type_id, account_number
    FROM contact_bank
    WHERE contact_id = p_contact_id 
    ORDER BY bank_id ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Attendance Setting Table Stored Procedures */

CREATE PROCEDURE checkAttendanceSettingExist (IN p_attendance_setting_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM attendance_setting
    WHERE attendance_setting_id = p_attendance_setting_id;
END //

CREATE PROCEDURE insertAttendanceSetting(IN p_attendance_setting_name VARCHAR(100), IN p_attendance_setting_description VARCHAR(200), IN p_value VARCHAR(1000), IN p_last_log_by INT, OUT p_attendance_setting_id INT)
BEGIN
    INSERT INTO attendance_setting (attendance_setting_name, attendance_setting_description, value, last_log_by) 
	VALUES(p_attendance_setting_name, p_attendance_setting_description, p_value, p_last_log_by);
	
    SET p_attendance_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateAttendanceSetting(IN p_attendance_setting_id INT, IN p_attendance_setting_name VARCHAR(100), IN p_attendance_setting_description VARCHAR(200), IN p_value VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE attendance_setting
    SET attendance_setting_name = p_attendance_setting_name,
    attendance_setting_description = p_attendance_setting_description,
    value = p_value,
    last_log_by = p_last_log_by
    WHERE attendance_setting_id = p_attendance_setting_id;
END //

CREATE PROCEDURE deleteAttendanceSetting(IN p_attendance_setting_id INT)
BEGIN
	DELETE FROM attendance_setting
    WHERE attendance_setting_id = p_attendance_setting_id;
END //

CREATE PROCEDURE getAttendanceSetting(IN p_attendance_setting_id INT)
BEGIN
	SELECT * FROM attendance_setting
    WHERE attendance_setting_id = p_attendance_setting_id;
END //

CREATE PROCEDURE duplicateAttendanceSetting(IN p_attendance_setting_id INT, IN p_last_log_by INT, OUT p_new_attendance_setting_id INT)
BEGIN
    DECLARE p_attendance_setting_name VARCHAR(100);
    DECLARE p_attendance_setting_description VARCHAR(200);
    DECLARE p_value VARCHAR(1000);
    
    SELECT attendance_setting_name, attendance_setting_description, value
    INTO p_attendance_setting_name, p_attendance_setting_description, p_value
    FROM attendance_setting 
    WHERE attendance_setting_id = p_attendance_setting_id;
    
    INSERT INTO attendance_setting (attendance_setting_name, attendance_setting_description, value, last_log_by) 
    VALUES(p_attendance_setting_name, p_attendance_setting_description, p_value, p_last_log_by);
    
    SET p_new_attendance_setting_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateAttendanceSettingTable()
BEGIN
	SELECT attendance_setting_id, attendance_setting_name, attendance_setting_description, value
    FROM attendance_setting
    ORDER BY attendance_setting_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Attendance Table Stored Procedures */

CREATE PROCEDURE checkAttendanceExist (IN p_attendance_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM attendance
    WHERE attendance_id = p_attendance_id;
END //

CREATE PROCEDURE insertRegularAttendanceEntry(IN p_contact_id INT, IN p_check_in_image VARCHAR(500), IN p_check_in DATETIME, IN p_check_in_location VARCHAR(100), IN p_check_in_by INT, IN p_check_in_notes VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    INSERT INTO attendance (contact_id, check_in_image, check_in, check_in_location, check_in_by, check_in_mode, check_in_notes, last_log_by) 
	VALUES(p_contact_id, p_check_in_image, p_check_in, p_check_in_location, p_check_in_by, 'Regular', p_check_in_notes, p_last_log_by);
END //

CREATE PROCEDURE updateRegularAttendanceExit(IN p_attendance_id INT, IN p_contact_id INT, IN p_check_out_image VARCHAR(500), IN p_check_out DATETIME, IN p_check_out_location VARCHAR(100), IN p_check_out_by INT, IN p_check_out_notes VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    UPDATE attendance
    SET check_out_image = p_check_out_image,
    check_out = p_check_out,
    check_out_location = p_check_out_location,
    check_out_by = p_check_out_by,
    check_out_mode = 'Regular',
    check_out_notes = p_check_out_notes,
    last_log_by = p_last_log_by
    WHERE attendance_id = p_attendance_id AND contact_id = p_contact_id;
END //

CREATE PROCEDURE insertManualAttendanceEntry(IN p_contact_id INT, IN p_check_in DATETIME, IN p_check_in_notes VARCHAR(1000), IN p_check_in_by INT, IN p_check_out DATETIME, IN p_check_out_notes VARCHAR(1000), IN p_check_out_by INT, IN p_last_log_by INT, OUT p_attendance_id INT)
BEGIN
    IF p_check_out IS NOT NULL AND p_check_out <> '' THEN
        INSERT INTO attendance (contact_id, check_in, check_in_notes, check_in_by, check_in_mode, check_out, check_out_notes, check_out_by, check_out_mode, last_log_by) 
	    VALUES(p_contact_id, p_check_in, p_check_in_notes, p_check_in_by, 'Manual', p_check_out, p_check_out_notes, p_check_out_by, 'Manual', p_last_log_by);
    ELSE
        INSERT INTO attendance (contact_id, check_in, check_in_notes, check_in_by, check_in_mode, last_log_by) 
	    VALUES(p_contact_id, p_check_in, p_check_in_notes, p_check_in_by, 'Manual', p_last_log_by);
    END IF;

    SET p_attendance_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateManualAttendanceEntry(IN p_attendance_id INT, IN p_contact_id INT, IN p_check_in DATETIME, IN p_check_in_notes VARCHAR(1000), IN p_check_in_by INT, IN p_check_out DATETIME, IN p_check_out_notes VARCHAR(1000), IN p_check_out_by INT, IN p_last_log_by INT)
BEGIN
    IF p_check_out IS NOT NULL AND p_check_out <> '' THEN
        UPDATE attendance
        SET check_in = p_check_in,
        check_in_notes = p_check_in_notes,
        check_in_by = p_check_in_by,
        check_in_mode = 'Manual',
        check_out = p_check_out,
        check_out_notes = p_check_out_notes,
        check_out_by = p_check_out_by,
        check_out_mode = 'Manual',
        last_log_by = p_last_log_by
        WHERE attendance_id = p_attendance_id AND contact_id = p_contact_id;
    ELSE
        UPDATE attendance
        SET check_in = p_check_in,
        check_in_notes = p_check_in_notes,
        check_in_by = p_check_in_by,
        check_in_mode = 'Manual',
        check_out = null,
        check_out_image = null,
        check_out_location = null,
        check_out_by = null,
        check_out_notes = null,
        check_out_mode = null,
        last_log_by = p_last_log_by
        WHERE attendance_id = p_attendance_id AND contact_id = p_contact_id;
    END IF;
END //

CREATE PROCEDURE checkAttendanceConflict (IN p_attendance_id INT, IN p_contact_id INT, IN p_check_in DATETIME, IN p_check_out DATETIME)
BEGIN
    IF p_attendance_id IS NOT NULL AND p_attendance_id <> '' THEN
        IF p_check_out IS NOT NULL AND p_check_out <> '' THEN
            SELECT COUNT(*) AS total
            FROM attendance
            WHERE (
                (p_check_in BETWEEN check_in AND check_out)
                OR (p_check_out BETWEEN check_in AND check_out)
            ) AND
            attendance_id != p_attendance_id AND contact_id = p_contact_id;
        ELSE
            SELECT COUNT(*) AS total
            FROM attendance
            WHERE (
                (p_check_in BETWEEN check_in AND check_out)
            ) AND
            attendance_id != p_attendance_id AND contact_id = p_contact_id;
        END IF;
    ELSE
        IF p_check_out IS NOT NULL AND p_check_out <> '' THEN
            SELECT COUNT(*) AS total
            FROM attendance
            WHERE (
                (p_check_in BETWEEN check_in AND check_out)
                OR (p_check_out BETWEEN check_in AND check_out)
            ) AND contact_id = p_contact_id;
        ELSE
             SELECT COUNT(*) AS total
            FROM attendance
            WHERE (
                (p_check_in BETWEEN check_in AND check_out)
            ) AND contact_id = p_contact_id;
        END IF;
    END IF;
END //

CREATE PROCEDURE deleteAttendance(IN p_attendance_id INT)
BEGIN
	DELETE FROM attendance
    WHERE attendance_id = p_attendance_id;
END //

CREATE PROCEDURE getAttendance(IN p_attendance_id INT)
BEGIN
	SELECT * FROM attendance
    WHERE attendance_id = p_attendance_id;
END //

CREATE PROCEDURE getAttendanceRecordWithoutCheckOut(IN p_contact_id INT)
BEGIN
	SELECT * FROM attendance
    WHERE contact_id = p_contact_id AND (check_out IS NULL OR check_out = '') AND DATE(check_in) = CURRENT_DATE;
END //

CREATE PROCEDURE getLatestAttendanceRecord(IN p_contact_id INT)
BEGIN
	SELECT * FROM attendance
    WHERE contact_id = p_contact_id AND (check_out IS NOT NULL OR check_out != '') AND (check_out IS NOT NULL OR check_out != '') AND DATE(check_in) = CURRENT_DATE
    ORDER BY attendance_id DESC LIMIT 1;
END //

CREATE PROCEDURE getAttendanceRecordCount(IN p_contact_id INT)
BEGIN
	SELECT COUNT(attendance_id) AS total FROM attendance
    WHERE contact_id = p_contact_id AND (check_out IS NOT NULL OR check_out != '') AND (check_out IS NOT NULL OR check_out != '') AND DATE(check_in) = CURRENT_DATE;
END //



CREATE PROCEDURE generateAttendanceRecordTable(IN p_attendance_record_start_date DATE, IN p_attendance_record_end_date DATE, IN p_check_in_mode VARCHAR(50), IN p_check_out_mode VARCHAR(50), IN p_employment_status VARCHAR(10), IN p_company_filter VARCHAR(500), IN p_department_filter VARCHAR(500), IN p_job_position_filter VARCHAR(500), IN p_branch_filter VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT attendance_id, attendance.contact_id AS contact_id, check_in, check_in_mode, check_out, check_out_mode FROM attendance
    INNER JOIN employment_information on employment_information.contact_id = attendance.contact_id';
    
    SET conditionList = ' WHERE 1';

    IF p_attendance_record_start_date IS NOT NULL AND p_attendance_record_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND DATE(check_in) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_attendance_record_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_attendance_record_end_date));
    END IF;

    IF p_check_in_mode IS NOT NULL AND p_check_in_mode <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND check_in_mode IN (');
        SET conditionList = CONCAT(conditionList, p_check_in_mode);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_check_out_mode IS NOT NULL AND p_check_out_mode <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND check_out_mode IN (');
        SET conditionList = CONCAT(conditionList, p_check_out_mode);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_employment_status <> 'all' THEN
        IF p_employment_status = 'active' THEN
            SET conditionList = CONCAT(conditionList, ' AND employment_status = 1');
        ELSE
            SET conditionList = CONCAT(conditionList, ' AND employment_status = 0');
        END IF;
    END IF;

    IF p_company_filter IS NOT NULL AND p_company_filter <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_company_filter);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_department_filter IS NOT NULL AND p_department_filter <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND department_id IN (');
        SET conditionList = CONCAT(conditionList, p_department_filter);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_job_position_filter IS NOT NULL AND p_job_position_filter <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND job_position_id IN (');
        SET conditionList = CONCAT(conditionList, p_job_position_filter);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_branch_filter IS NOT NULL AND p_branch_filter <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND branch_id IN (');
        SET conditionList = CONCAT(conditionList, p_branch_filter);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY check_in DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateImportAttendanceRecordTable(IN p_attendance_record_start_date DATE, IN p_attendance_record_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM temp_attendance_import';
    
    SET conditionList = ' WHERE 1';

    IF p_attendance_record_start_date IS NOT NULL AND p_attendance_record_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND DATE(check_in) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_attendance_record_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_attendance_record_end_date));
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY contact_id, check_in;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Attendance Record Import Table Stored Procedures */

CREATE PROCEDURE deleteBiometricsAttendanceRecord()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM temp_attendance_biometrics_import;
    DELETE FROM temp_attendance_import;

    ALTER TABLE temp_attendance_import AUTO_INCREMENT = 1;

    COMMIT;
END //

CREATE PROCEDURE insertBiometricsAttendanceRecord(IN p_biometrics_id INT, IN p_company_id INT, IN p_attendance_date DATETIME)
BEGIN
    INSERT INTO temp_attendance_biometrics_import (biometrics_id, company_id, attendance_record_date) 
	VALUES(p_biometrics_id, p_company_id, p_attendance_date);
END //

CREATE PROCEDURE getBiometricsAttendanceRecord()
BEGIN
    SELECT * FROM temp_attendance_biometrics_import
    ORDER BY biometrics_id, attendance_record_date;
END //

CREATE PROCEDURE getArrangedImportedAttendanceRecord(IN p_attendance_id INT)
BEGIN
    SELECT * FROM temp_attendance_import
    WHERE attendance_id = p_attendance_id;
END //

CREATE PROCEDURE checkBiometricsAttendanceRecordExist (IN p_contact_id INT, IN p_attendance_date DATETIME)
BEGIN
	SELECT COUNT(*) AS total
    FROM temp_attendance_import
    WHERE contact_id = p_contact_id AND (check_in = p_attendance_date OR check_out = p_attendance_date);
END //

CREATE PROCEDURE checkArrangedBiometricsAttendanceRecordExist (IN p_contact_id INT, IN p_attendance_date DATETIME)
BEGIN
	SELECT COUNT(*) AS total
    FROM temp_attendance_import
    WHERE contact_id = p_contact_id AND (check_out IS NULL OR check_out = '') AND DATE(check_in) = DATE(p_attendance_date);
END //

CREATE PROCEDURE insertArrangedBiometricsAttendanceRecord(IN p_contact_id INT, IN p_check_in DATETIME, IN p_check_in_by INT)
BEGIN
	INSERT INTO temp_attendance_import (contact_id, check_in, check_in_by, check_in_mode) 
	VALUES(p_contact_id, p_check_in, p_check_in_by, 'Biometrics');
END //

CREATE PROCEDURE updateArrangedBiometricsAttendanceRecord(IN p_contact_id INT, IN p_check_out DATETIME, IN p_check_out_by INT)
BEGIN
	UPDATE temp_attendance_import
    SET check_out = p_check_out,
    check_out_by = p_check_out_by,
    check_out_mode = 'Biometrics'
    WHERE contact_id = p_contact_id AND (check_out IS NULL OR check_out = '') AND DATE(check_in) = DATE(p_check_out);
END //

CREATE PROCEDURE insertImportedAttendanceEntry(IN p_contact_id INT, IN p_check_in DATETIME, IN p_check_in_location VARCHAR(100), IN p_check_in_by INT, IN p_check_in_mode VARCHAR(50), IN p_check_in_notes VARCHAR(1000), IN p_check_out DATETIME, IN p_check_out_location VARCHAR(100), IN p_check_out_by INT, IN p_check_out_mode VARCHAR(50), IN p_check_out_notes VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	INSERT INTO attendance (contact_id, check_in, check_in_location, check_in_by, check_in_mode, check_in_notes, check_out, check_out_location, check_out_by, check_out_mode, check_out_notes, last_log_by) 
	VALUES(p_contact_id, p_check_in, p_check_in_location, p_check_in_by, p_check_in_mode, p_check_in_notes, p_check_out, p_check_out_location, p_check_out_by, p_check_out_mode, p_check_out_notes, p_last_log_by);
END //

CREATE PROCEDURE insertRegularImportedAttendanceEntry(IN p_contact_id INT, IN p_check_in DATETIME, IN p_check_in_location VARCHAR(100), IN p_check_in_by INT, IN p_check_in_mode VARCHAR(50), IN p_check_in_notes VARCHAR(1000), IN p_check_out DATETIME, IN p_check_out_location VARCHAR(100), IN p_check_out_by INT, IN p_check_out_mode VARCHAR(50), IN p_check_out_notes VARCHAR(1000))
BEGIN
	INSERT INTO temp_attendance_import (contact_id, check_in, check_in_location, check_in_by, check_in_mode, check_in_notes, check_out, check_out_location, check_out_by, check_out_mode, check_out_notes) 
	VALUES(p_contact_id, p_check_in, p_check_in_location, p_check_in_by, p_check_in_mode, p_check_in_notes, p_check_out, p_check_out_location, p_check_out_by, p_check_out_mode, p_check_out_notes);
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Transmittal Table Stored Procedures */

CREATE PROCEDURE checkTransmittalExist (IN p_transmittal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM transmittal
    WHERE transmittal_id = p_transmittal_id;
END //

CREATE PROCEDURE insertTransmittal(IN p_transmittal_description VARCHAR(500), IN p_created_by INT, IN p_transmitter_id INT, IN p_transmitter_name VARCHAR(500), IN p_transmitter_department INT, IN p_transmitter_department_name VARCHAR(100), IN p_receiver_id INT, IN p_receiver_name VARCHAR(500), IN p_receiver_department INT, IN p_receiver_department_name VARCHAR(100), IN p_last_log_by INT, OUT p_transmittal_id INT)
BEGIN
    INSERT INTO transmittal (transmittal_description, created_by, transmitter_id, transmitter_name, transmitter_department, transmitter_department_name, receiver_id, receiver_name, receiver_department, receiver_department_name, last_log_by) 
	VALUES(p_transmittal_description, p_created_by, p_transmitter_id, p_transmitter_name, p_transmitter_department, p_transmitter_department_name, p_receiver_id, p_receiver_name, p_receiver_department, p_receiver_department_name, p_last_log_by);
	
    SET p_transmittal_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateTransmittal(IN p_transmittal_id INT, IN p_transmittal_description VARCHAR(500), IN p_receiver_id INT, IN p_receiver_name VARCHAR(500), IN p_receiver_department INT, IN p_receiver_department_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE transmittal
    SET transmittal_description = p_transmittal_description,
    receiver_id = p_receiver_id,
    receiver_name = p_receiver_name,
    receiver_department = p_receiver_department,
    receiver_department_name = p_receiver_department_name,
    last_log_by = p_last_log_by
    WHERE transmittal_id = p_transmittal_id;
END //

CREATE PROCEDURE updateTransmittalImage(IN p_transmittal_id INT, IN p_transmittal_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE transmittal
    SET transmittal_image = p_transmittal_image,
    last_log_by = p_last_log_by
    WHERE transmittal_id = p_transmittal_id;
END //

CREATE PROCEDURE updateReTransmittal(IN p_transmittal_id INT, IN p_transmittal_description VARCHAR(500), IN p_transmitter_id INT, IN p_transmitter_name VARCHAR(500), IN p_transmitter_department INT, IN p_transmitter_department_name VARCHAR(100), IN p_receiver_id INT, IN p_receiver_name VARCHAR(500), IN p_receiver_department INT, IN p_receiver_department_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE transmittal
    SET transmittal_description = p_transmittal_description,
    transmitter_id = p_transmitter_id,
    transmitter_name = p_transmitter_name,
    transmitter_department = p_transmitter_department,
    transmitter_department_name = p_transmitter_department_name,
    receiver_id = p_receiver_id,
    receiver_name = p_receiver_name,
    receiver_department = p_receiver_department,
    receiver_department_name = p_receiver_department_name,
    last_log_by = p_last_log_by
    WHERE transmittal_id = p_transmittal_id;
END //

CREATE PROCEDURE updateTransmittalStatus(IN p_transmittal_id INT, IN p_transmittal_status VARCHAR(20), IN p_last_log_by INT)
BEGIN
    UPDATE transmittal
    SET transmittal_status = p_transmittal_status,
    transmittal_date = NOW(),
    last_log_by = p_last_log_by
    WHERE transmittal_id = p_transmittal_id;
END //

CREATE PROCEDURE deleteTransmittal(IN p_transmittal_id INT)
BEGIN
   DELETE FROM transmittal WHERE transmittal_id = p_transmittal_id;
END //

CREATE PROCEDURE getTransmittal(IN p_transmittal_id INT)
BEGIN
	SELECT * FROM transmittal
    WHERE transmittal_id = p_transmittal_id;
END //

CREATE PROCEDURE generateOwnTransmittalTable(IN p_contact_id INT, IN p_department_id INT, IN p_transmittal_start_date DATE, IN p_transmittal_end_date DATE, IN p_transmittal_status VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM transmittal';
    SET conditionList = ' WHERE 1';

    SET conditionList = CONCAT(conditionList, ' AND (');
    SET conditionList = CONCAT(conditionList, 'created_by IN (SELECT contact_id FROM employment_information WHERE department_id = ');
    SET conditionList = CONCAT(conditionList, p_department_id);
    SET conditionList = CONCAT(conditionList, ')');
    SET conditionList = CONCAT(conditionList, ' OR created_by = ');
    SET conditionList = CONCAT(conditionList, p_contact_id);
    SET conditionList = CONCAT(conditionList, ' OR transmitter_id = ');
    SET conditionList = CONCAT(conditionList, p_contact_id);
    SET conditionList = CONCAT(conditionList, ' OR (receiver_id = ');
    SET conditionList = CONCAT(conditionList, p_contact_id);
    SET conditionList = CONCAT(conditionList, ' AND transmittal_status NOT IN ("Draft", "Cancelled")) ');
    SET conditionList = CONCAT(conditionList, ' OR transmitter_department = ');
    SET conditionList = CONCAT(conditionList, p_department_id);
    SET conditionList = CONCAT(conditionList, ' OR (receiver_department =');
    SET conditionList = CONCAT(conditionList, p_department_id);
    SET conditionList = CONCAT(conditionList, ' AND transmittal_status NOT IN ("Draft", "Cancelled")) ');
    SET conditionList = CONCAT(conditionList, ')');
    
    IF p_transmittal_start_date IS NOT NULL AND p_transmittal_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND DATE(transmittal_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transmittal_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transmittal_end_date));
    END IF;

    IF p_transmittal_status IS NOT NULL AND p_transmittal_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND transmittal_status IN (');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transmittal_status));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY transmittal_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateDashboardTransmittalTable(IN p_contact_id INT, IN p_department_id INT)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM transmittal';
    SET conditionList = ' WHERE 1';
    SET conditionList = CONCAT(conditionList, ' AND (receiver_id = ');
    SET conditionList = CONCAT(conditionList, p_contact_id);
    SET conditionList = CONCAT(conditionList, ' AND transmittal_status IN ("Transmitted", "Re-Transmitted"))');
    SET conditionList = CONCAT(conditionList, ' OR (receiver_department =');
    SET conditionList = CONCAT(conditionList, p_department_id);
    SET conditionList = CONCAT(conditionList, ' AND transmittal_status IN ("Transmitted", "Re-Transmitted")) ');

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY transmittal_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateAllTransmittalTable(IN p_transmittal_start_date DATE, IN p_transmittal_end_date DATE, IN p_transmittal_status VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM transmittal';
    
    SET conditionList = ' WHERE 1';

    IF p_transmittal_start_date IS NOT NULL AND p_transmittal_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND DATE(transmittal_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transmittal_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transmittal_end_date));
    END IF;

    IF p_transmittal_status IS NOT NULL AND p_transmittal_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND transmittal_status IN (');
        SET conditionList = CONCAT(conditionList, p_transmittal_status);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY transmittal_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Document Category Table Stored Procedures */

CREATE PROCEDURE checkDocumentCategoryExist (IN p_document_category_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document_category
    WHERE document_category_id = p_document_category_id;
END //

CREATE PROCEDURE insertDocumentCategory(IN p_document_category_name VARCHAR(100), IN p_last_log_by INT, OUT p_document_category_id INT)
BEGIN
    INSERT INTO document_category (document_category_name, last_log_by) 
	VALUES(p_document_category_name, p_last_log_by);
	
    SET p_document_category_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateDocumentCategory(IN p_document_category_id INT, IN p_document_category_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE document_category
    SET document_category_name = p_document_category_name,
    last_log_by = p_last_log_by
    WHERE document_category_id = p_document_category_id;
END //

CREATE PROCEDURE deleteDocumentCategory(IN p_document_category_id INT)
BEGIN
    DELETE FROM document_category WHERE document_category_id = p_document_category_id;
END //

CREATE PROCEDURE getDocumentCategory(IN p_document_category_id INT)
BEGIN
	SELECT * FROM document_category
    WHERE document_category_id = p_document_category_id;
END //

CREATE PROCEDURE duplicateDocumentCategory(IN p_document_category_id INT, IN p_last_log_by INT, OUT p_new_document_category_id INT)
BEGIN
    DECLARE p_document_category_name VARCHAR(100);
    
    SELECT document_category_name
    INTO p_document_category_name
    FROM document_category 
    WHERE document_category_id = p_document_category_id;
    
    INSERT INTO document_category (document_category_name, last_log_by) 
    VALUES(p_document_category_name, p_last_log_by);
    
    SET p_new_document_category_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateDocumentCategoryTable()
BEGIN
    SELECT document_category_id, document_category_name
    FROM document_category
    ORDER BY document_category_id;
END //

CREATE PROCEDURE generateDocumentCategoryOptions()
BEGIN
	SELECT document_category_id, document_category_name FROM document_category
	ORDER BY document_category_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Document Table Stored Procedures */

CREATE PROCEDURE checkDocumentExist (IN p_document_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document
    WHERE document_id = p_document_id;
END //

CREATE PROCEDURE updateDocument(IN p_document_id INT, IN p_document_name VARCHAR(100), IN p_document_description VARCHAR(500), IN p_document_category_id INT, IN p_is_confidential VARCHAR(5), IN p_last_log_by INT)
BEGIN
    UPDATE document
    SET document_name = p_document_name,
    document_description = p_document_description,
    document_category_id = p_document_category_id,
    is_confidential = p_is_confidential,
    last_log_by = p_last_log_by
    WHERE document_id = p_document_id;
END //

CREATE PROCEDURE updateDocumentPassword(IN p_document_id INT, IN p_document_password VARCHAR(500), IN p_last_log_by INT)
BEGIN
    UPDATE document
    SET document_password = p_document_password,
    last_log_by = p_last_log_by
    WHERE document_id = p_document_id;
END //

CREATE PROCEDURE insertDocument(IN p_document_name VARCHAR(100), IN p_document_description VARCHAR(500), IN p_author INT, IN p_document_password VARCHAR(500), IN p_document_path VARCHAR(500), IN p_document_category_id INT, IN p_document_extension VARCHAR(10), IN p_document_size DOUBLE, IN p_is_confidential VARCHAR(5), IN p_last_log_by INT, OUT p_document_id INT)
BEGIN
    INSERT INTO document (document_name, document_description, author, document_password, document_path, document_category_id, document_extension, document_size, is_confidential, last_log_by) 
	VALUES(p_document_name, p_document_description, p_author, p_document_password, p_document_path, p_document_category_id, p_document_extension, p_document_size, p_is_confidential, p_last_log_by);
	
    SET p_document_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE getDocument(IN p_document_id INT)
BEGIN
	SELECT * FROM document
    WHERE document_id = p_document_id;
END //

CREATE PROCEDURE updateDocumentStatus(IN p_document_id INT, IN p_document_status VARCHAR(20), IN p_last_log_by INT)
BEGIN
    IF p_document_status = 'Published' THEN
        UPDATE document
        SET document_status = p_document_status,
        publish_date = NOW(),
        last_log_by = p_last_log_by
        WHERE document_id = p_document_id;
    ELSE
        UPDATE document
        SET document_status = p_document_status,
        publish_date = null,
        last_log_by = p_last_log_by
        WHERE document_id = p_document_id;
    END IF;
END //

CREATE PROCEDURE updateDocumentFile(IN p_document_id INT, IN p_document_path VARCHAR(500), IN p_document_version INT, IN p_last_log_by INT)
BEGIN
	UPDATE document 
    SET document_path = p_document_path, 
    document_version = p_document_version, 
    last_log_by = p_last_log_by 
    WHERE document_id = p_document_id;
END //

CREATE PROCEDURE deleteDocument(IN p_document_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM document_version_history WHERE document_id = p_document_id;
    DELETE FROM document_restriction WHERE document_id = p_document_id;
    DELETE FROM document WHERE document_id = p_document_id;

    COMMIT;
END //

CREATE PROCEDURE generateDocumentCard(IN p_offset INT, IN p_document_per_page INT, IN p_search VARCHAR(500), IN p_contact_id INT, IN p_department INT, IN p_upload_start_date_filter DATE, IN p_upload_end_date_filter DATE, IN p_publish_start_date_filter DATE, IN p_publish_end_date_filter DATE, IN p_document_category_filter VARCHAR(500), IN p_department_filter VARCHAR(500))
BEGIN
    DECLARE sql_query VARCHAR(5000);

    SET sql_query = 'SELECT *
    FROM document
    WHERE document_status = "Published"';

    IF p_search IS NOT NULL AND p_search <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND (
            document_name LIKE ?
            OR document_description LIKE ?
        )');
    END IF;

    IF p_upload_start_date_filter IS NOT NULL AND p_upload_end_date_filter IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND DATE(upload_date) BETWEEN ', QUOTE(p_upload_start_date_filter), ' AND ', QUOTE(p_upload_end_date_filter));
    END IF;

    IF p_publish_start_date_filter IS NOT NULL AND p_publish_end_date_filter IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND DATE(publish_date) BETWEEN ', QUOTE(p_publish_start_date_filter), ' AND ', QUOTE(p_publish_end_date_filter));
    END IF;

    IF p_contact_id IS NOT NULL AND p_contact_id <> '' AND p_department IS NOT NULL AND p_department <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND document_id NOT IN (SELECT document_id FROM document_restriction WHERE department_id = ', p_department, ' OR contact_id = ', p_contact_id ,')');
    END IF;

    IF p_document_category_filter IS NOT NULL AND p_document_category_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND document_category_id IN (', p_document_category_filter, ')');
    END IF;

    IF p_department_filter IS NOT NULL AND p_department_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND author IN (SELECT contact_id FROM employment_information WHERE department_id IN (', p_department_filter,'))');
    END IF;

    SET sql_query = CONCAT(sql_query, ' ORDER BY publish_date DESC LIMIT ?, ?;');

    PREPARE stmt FROM sql_query;
    IF p_search IS NOT NULL AND p_search <> '' THEN
        EXECUTE stmt USING CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), p_offset, p_document_per_page;
    ELSE
        EXECUTE stmt USING p_offset, p_document_per_page;
    END IF;

    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateDashboardDocumentTable(IN p_contact_id INT, IN p_department INT)
BEGIN
    DECLARE sql_query VARCHAR(5000);

    SET sql_query = 'SELECT *
    FROM document
    WHERE document_status = "Published"';

    IF p_contact_id IS NOT NULL AND p_contact_id <> '' AND p_department IS NOT NULL AND p_department <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND document_id NOT IN (SELECT document_id FROM document_restriction WHERE department_id = ', p_department, ' OR contact_id = ', p_contact_id ,')');
    END IF;

    SET sql_query = CONCAT(sql_query, ' ORDER BY publish_date;');

    PREPARE stmt FROM sql_query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateOwnDraftDocumentTable(IN p_contact_id INT, IN p_department_id INT, IN p_upload_start_date DATE, IN p_upload_end_date DATE, IN p_document_category VARCHAR(500), IN p_department VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM document';
    SET conditionList = ' WHERE document_status IN ("Draft", "For Publish")';

    SET conditionList = CONCAT(conditionList, ' AND (');
    SET conditionList = CONCAT(conditionList, 'author IN (SELECT contact_id FROM employment_information WHERE department_id = ');
    SET conditionList = CONCAT(conditionList, p_department_id);
    SET conditionList = CONCAT(conditionList, ')');
    SET conditionList = CONCAT(conditionList, ' OR author IN (SELECT contact_id FROM employment_information WHERE department_id IN (SELECT department_id FROM document_authorizer WHERE authorizer_id = ');
    SET conditionList = CONCAT(conditionList, p_contact_id);
    SET conditionList = CONCAT(conditionList, '))');
    SET conditionList = CONCAT(conditionList, ' OR author = ');
    SET conditionList = CONCAT(conditionList, p_contact_id);
    SET conditionList = CONCAT(conditionList, ')');
    
    IF p_upload_start_date IS NOT NULL AND p_upload_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND DATE(upload_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_upload_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_upload_end_date));
    END IF;

    IF p_document_category IS NOT NULL AND p_document_category <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND document_category_id IN (');
        SET conditionList = CONCAT(conditionList, QUOTE(p_document_category));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_department IS NOT NULL AND p_department <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND author IN (SELECT contact_id FROM employment_information WHERE department_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_department));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY upload_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateAllDraftDocumentTable(IN p_upload_start_date DATE, IN p_upload_end_date DATE, IN p_document_category VARCHAR(500), IN p_department VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM document';
    SET conditionList = ' WHERE document_status IN ("Draft", "For Publish")';

    IF p_upload_start_date IS NOT NULL AND p_upload_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND DATE(upload_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_upload_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_upload_end_date));
    END IF;

    IF p_document_category IS NOT NULL AND p_document_category <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND document_category_id IN (');
        SET conditionList = CONCAT(conditionList, QUOTE(p_document_category));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_department IS NOT NULL AND p_department <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND author IN (SELECT contact_id FROM employment_information WHERE department_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_department));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY upload_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Document Version History Table Stored Procedures */

CREATE PROCEDURE insertDocumentVersionHistory(IN p_document_id INT, IN p_document_path VARCHAR(500), IN p_document_version INT, IN p_uploaded_by INT)
BEGIN
    INSERT INTO document_version_history (document_id, document_path, document_version, uploaded_by) 
	VALUES(p_document_id, p_document_path, p_document_version, p_uploaded_by);
END //

CREATE PROCEDURE generateDocumentVersionHistorySummary(IN p_document_id INT)
BEGIN
	SELECT *
    FROM document_version_history
    WHERE document_id = p_document_id
    ORDER BY upload_date;
END //

CREATE PROCEDURE getDocumentVersionHistoryByDocumentID(IN p_document_id INT)
BEGIN
	SELECT * FROM document_version_history
    WHERE document_id = p_document_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Document Authorizer Table Stored Procedures */

CREATE PROCEDURE checkDocumentAuthorizerExist (IN p_document_authorizer_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document_authorizer
    WHERE document_authorizer_id = p_document_authorizer_id;
END //


CREATE PROCEDURE checkDocumentDepartmentAuthorizerExist (IN p_department_id INT, IN p_authorizer_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document_authorizer
    WHERE department_id = p_department_id AND authorizer_id = p_authorizer_id;
END //

CREATE PROCEDURE checkIfDocumentAuthorizer (IN p_authorizer_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document
    WHERE author IN (SELECT contact_id FROM employment_information WHERE department_id IN (SELECT department_id FROM document_authorizer WHERE authorizer_id = p_authorizer_id));
END //

CREATE PROCEDURE insertDocumentAuthorizer(IN p_department_id INT, IN p_authorizer_id INT, IN p_last_log_by INT, OUT p_document_authorizer_id INT)
BEGIN
    INSERT INTO document_authorizer (department_id, authorizer_id, last_log_by) 
	VALUES(p_department_id, p_authorizer_id, p_last_log_by);
	
    SET p_document_authorizer_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE deleteDocumentAuthorizer(IN p_document_authorizer_id INT)
BEGIN
    DELETE FROM document_authorizer WHERE document_authorizer_id = p_document_authorizer_id;
END //

CREATE PROCEDURE getDocumentAuthorizer(IN p_document_authorizer_id INT)
BEGIN
	SELECT * FROM document_authorizer
    WHERE document_authorizer_id = p_document_authorizer_id;
END //

CREATE PROCEDURE generateDocumentAuthorizerTable()
BEGIN
    SELECT *
    FROM document_authorizer
    ORDER BY document_authorizer_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Document Restriction Table Stored Procedures */

CREATE PROCEDURE checkDocumenRestrictionExist (IN p_document_restriction_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document_restriction
    WHERE document_restriction_id = p_document_restriction_id;
END //

CREATE PROCEDURE checkDocumentDepartmentRestrictionExist (IN p_document_id INT, IN p_department_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document_restriction
    WHERE document_id = p_document_id AND department_id = p_department_id;
END //

CREATE PROCEDURE checkDocumentEmployeeRestrictionExist (IN p_document_id INT, IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM document_restriction
    WHERE document_id = p_document_id AND contact_id = p_contact_id;
END //

CREATE PROCEDURE insertDocumentRestriction(IN document_id INT, IN p_department_id INT, IN p_contact_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO document_restriction (document_id, department_id, contact_id, last_log_by) 
	VALUES(document_id, p_department_id, p_contact_id, p_last_log_by);
END //

CREATE PROCEDURE generateDocumentDepartmentRestriction(IN p_document_id INT)
BEGIN
	SELECT *
    FROM document_restriction
    WHERE document_id = p_document_id AND department_id IS NOT NULL
    ORDER BY department_id DESC;
END //

CREATE PROCEDURE generateDocumentDepartmentRestrictionExcemption(IN p_document_id INT)
BEGIN
	SELECT *
    FROM department
    WHERE department_id NOT IN (SELECT department_id FROM document_restriction WHERE document_id = p_document_id AND department_id IS NOT NULL)
    ORDER BY department_id DESC;
END //

CREATE PROCEDURE generateDocumentEmployeeRestriction(IN p_document_id INT)
BEGIN
	SELECT *
    FROM document_restriction
    WHERE document_id = p_document_id AND contact_id IS NOT NULL
    ORDER BY contact_id DESC;
END //

CREATE PROCEDURE generateDocumentEmployeeRestrictionExcemption(IN p_document_id INT)
BEGIN
	SELECT *
    FROM employment_information
    WHERE contact_id NOT IN (SELECT contact_id FROM document_restriction WHERE document_id = p_document_id AND contact_id IS NOT NULL) AND employment_status = 1
    ORDER BY contact_id DESC;
END //

CREATE PROCEDURE deleteDocumentRestriction(IN p_document_restriction_id INT)
BEGIN
    DELETE FROM document_restriction WHERE document_restriction_id = p_document_restriction_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Body Type Table Stored Procedures */

CREATE PROCEDURE checkBodyTypeExist (IN p_body_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM body_type
    WHERE body_type_id = p_body_type_id;
END //

CREATE PROCEDURE insertBodyType(IN p_body_type_name VARCHAR(100), IN p_last_log_by INT, OUT p_body_type_id INT)
BEGIN
    INSERT INTO body_type (body_type_name, last_log_by) 
	VALUES(p_body_type_name, p_last_log_by);
	
    SET p_body_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBodyType(IN p_body_type_id INT, IN p_body_type_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE body_type
    SET body_type_name = p_body_type_name,
    last_log_by = p_last_log_by
    WHERE body_type_id = p_body_type_id;
END //

CREATE PROCEDURE deleteBodyType(IN p_body_type_id INT)
BEGIN
    DELETE FROM body_type WHERE body_type_id = p_body_type_id;
END //

CREATE PROCEDURE getBodyType(IN p_body_type_id INT)
BEGIN
	SELECT * FROM body_type
    WHERE body_type_id = p_body_type_id;
END //

CREATE PROCEDURE duplicateBodyType(IN p_body_type_id INT, IN p_last_log_by INT, OUT p_new_body_type_id INT)
BEGIN
    DECLARE p_body_type_name VARCHAR(100);
    
    SELECT body_type_name
    INTO p_body_type_name
    FROM body_type 
    WHERE body_type_id = p_body_type_id;
    
    INSERT INTO body_type (body_type_name, last_log_by) 
    VALUES(p_body_type_name, p_last_log_by);
    
    SET p_new_body_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateBodyTypeTable()
BEGIN
    SELECT body_type_id, body_type_name
    FROM body_type
    ORDER BY body_type_id;
END //

CREATE PROCEDURE generateBodyTypeOptions()
BEGIN
	SELECT body_type_id, body_type_name FROM body_type
	ORDER BY body_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Color Table Stored Procedures */

CREATE PROCEDURE checkColorExist (IN p_color_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM color
    WHERE color_id = p_color_id;
END //

CREATE PROCEDURE insertColor(IN p_color_name VARCHAR(100), IN p_last_log_by INT, OUT p_color_id INT)
BEGIN
    INSERT INTO color (color_name, last_log_by) 
	VALUES(p_color_name, p_last_log_by);
	
    SET p_color_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateColor(IN p_color_id INT, IN p_color_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE color
    SET color_name = p_color_name,
    last_log_by = p_last_log_by
    WHERE color_id = p_color_id;
END //

CREATE PROCEDURE deleteColor(IN p_color_id INT)
BEGIN
    DELETE FROM color WHERE color_id = p_color_id;
END //

CREATE PROCEDURE getColor(IN p_color_id INT)
BEGIN
	SELECT * FROM color
    WHERE color_id = p_color_id;
END //

CREATE PROCEDURE duplicateColor(IN p_color_id INT, IN p_last_log_by INT, OUT p_new_color_id INT)
BEGIN
    DECLARE p_color_name VARCHAR(100);
    
    SELECT color_name
    INTO p_color_name
    FROM color 
    WHERE color_id = p_color_id;
    
    INSERT INTO color (color_name, last_log_by) 
    VALUES(p_color_name, p_last_log_by);
    
    SET p_new_color_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateColorTable()
BEGIN
    SELECT color_id, color_name
    FROM color
    ORDER BY color_id;
END //

CREATE PROCEDURE generateColorOptions()
BEGIN
	SELECT color_id, color_name FROM color
	ORDER BY color_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Unit Category Table Stored Procedures */

CREATE PROCEDURE checkUnitCategoryExist (IN p_unit_category_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM unit_category
    WHERE unit_category_id = p_unit_category_id;
END //

CREATE PROCEDURE insertUnitCategory(IN p_unit_category_name VARCHAR(100), IN p_last_log_by INT, OUT p_unit_category_id INT)
BEGIN
    INSERT INTO unit_category (unit_category_name, last_log_by) 
	VALUES(p_unit_category_name, p_last_log_by);
	
    SET p_unit_category_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateUnitCategory(IN p_unit_category_id INT, IN p_unit_category_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE unit_category
    SET unit_category_name = p_unit_category_name,
    last_log_by = p_last_log_by
    WHERE unit_category_id = p_unit_category_id;
END //

CREATE PROCEDURE deleteUnitCategory(IN p_unit_category_id INT)
BEGIN
    DELETE FROM unit_category WHERE unit_category_id = p_unit_category_id;
END //

CREATE PROCEDURE getUnitCategory(IN p_unit_category_id INT)
BEGIN
	SELECT * FROM unit_category
    WHERE unit_category_id = p_unit_category_id;
END //

CREATE PROCEDURE duplicateUnitCategory(IN p_unit_category_id INT, IN p_last_log_by INT, OUT p_new_unit_category_id INT)
BEGIN
    DECLARE p_unit_category_name VARCHAR(100);
    
    SELECT unit_category_name
    INTO p_unit_category_name
    FROM unit_category 
    WHERE unit_category_id = p_unit_category_id;
    
    INSERT INTO unit_category (unit_category_name, last_log_by) 
    VALUES(p_unit_category_name, p_last_log_by);
    
    SET p_new_unit_category_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateUnitCategoryTable()
BEGIN
    SELECT unit_category_id, unit_category_name
    FROM unit_category
    ORDER BY unit_category_id;
END //

CREATE PROCEDURE generateUnitCategoryOptions()
BEGIN
	SELECT unit_category_id, unit_category_name FROM unit_category
	ORDER BY unit_category_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Unit Table Stored Procedures */

CREATE PROCEDURE checkUnitExist (IN p_unit_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM unit
    WHERE unit_id = p_unit_id;
END //

CREATE PROCEDURE insertUnit(IN p_unit_name VARCHAR(100), IN p_short_name VARCHAR(10), p_unit_category_id INT, IN p_last_log_by INT, OUT p_unit_id INT)
BEGIN
    INSERT INTO unit (unit_name, short_name, unit_category_id, last_log_by) 
	VALUES(p_unit_name, p_short_name, p_unit_category_id, p_last_log_by);
	
    SET p_unit_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateUnit(IN p_unit_id INT, IN p_unit_name VARCHAR(100), IN p_short_name VARCHAR(10), p_unit_category_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE unit
    SET unit_name = p_unit_name,
    short_name = p_short_name,
    unit_category_id = p_unit_category_id,
    last_log_by = p_last_log_by
    WHERE unit_id = p_unit_id;
END //

CREATE PROCEDURE deleteUnit(IN p_unit_id INT)
BEGIN
    DELETE FROM unit WHERE unit_id = p_unit_id;
END //

CREATE PROCEDURE getUnit(IN p_unit_id INT)
BEGIN
	SELECT * FROM unit
    WHERE unit_id = p_unit_id;
END //

CREATE PROCEDURE duplicateUnit(IN p_unit_id INT, IN p_last_log_by INT, OUT p_new_unit_id INT)
BEGIN
    DECLARE p_unit_name VARCHAR(100);
    DECLARE p_short_name VARCHAR(10);
    DECLARE p_unit_category_id INT;
    
    SELECT unit_name, short_name, unit_category_id
    INTO p_unit_name, p_short_name, p_unit_category_id
    FROM unit 
    WHERE unit_id = p_unit_id;
    
    INSERT INTO unit (unit_name, short_name, unit_category_id, last_log_by) 
    VALUES(p_unit_name, p_short_name, p_unit_category_id, p_last_log_by);
    
    SET p_new_unit_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateUnitTable()
BEGIN
    SELECT *
    FROM unit
    ORDER BY unit_id;
END //

CREATE PROCEDURE generateUnitOptions()
BEGIN
	SELECT unit_id, unit_name FROM unit
	ORDER BY unit_name;
END //

CREATE PROCEDURE generateUnitByCategoryOptions(IN p_unit_category_id INT)
BEGIN
	SELECT unit_id, short_name FROM unit
    WHERE unit_category_id = p_unit_category_id
	ORDER BY unit_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Warehouse Table Stored Procedures */

CREATE PROCEDURE checkWarehouseExist (IN p_warehouse_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM warehouse
    WHERE warehouse_id = p_warehouse_id;
END //

CREATE PROCEDURE insertWarehouse(IN p_warehouse_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_company_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT, OUT p_warehouse_id INT)
BEGIN
    INSERT INTO warehouse (warehouse_name, address, city_id, company_id, phone, mobile, telephone, email, last_log_by) 
	VALUES(p_warehouse_name, p_address, p_city_id, p_company_id, p_phone, p_mobile, p_telephone, p_email, p_last_log_by);
	
    SET p_warehouse_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateWarehouse(IN p_warehouse_id INT, IN p_warehouse_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_company_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE warehouse
    SET warehouse_name = p_warehouse_name,
    warehouse_name = p_warehouse_name,
    address = p_address,
    city_id = p_city_id,
    company_id = p_company_id,
    phone = p_phone,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    last_log_by = p_last_log_by
    WHERE warehouse_id = p_warehouse_id;
END //

CREATE PROCEDURE deleteWarehouse(IN p_warehouse_id INT)
BEGIN
	DELETE FROM warehouse
    WHERE warehouse_id = p_warehouse_id;
END //

CREATE PROCEDURE getWarehouse(IN p_warehouse_id INT)
BEGIN
	SELECT * FROM warehouse
    WHERE warehouse_id = p_warehouse_id;
END //

CREATE PROCEDURE duplicateWarehouse(IN p_warehouse_id INT, IN p_last_log_by INT, OUT p_new_warehouse_id INT)
BEGIN
    DECLARE p_warehouse_name VARCHAR(100);
    DECLARE p_address VARCHAR(1000);
    DECLARE p_city_id INT;
    DECLARE p_company_id INT;
    DECLARE p_phone VARCHAR(20);
    DECLARE p_mobile VARCHAR(20);
    DECLARE p_telephone VARCHAR(20);
    DECLARE p_email VARCHAR(100);
    
    SELECT warehouse_name, address, city_id, company_id, phone, mobile, telephone, email
    INTO p_warehouse_name, p_address, p_city_id, p_company_id, p_phone, p_mobile, p_telephone, p_email
    FROM warehouse 
    WHERE warehouse_id = p_warehouse_id;
    
    INSERT INTO warehouse (warehouse_name, address, city_id, company_id, phone, mobile, telephone, email, last_log_by) 
    VALUES(p_warehouse_name, p_address, p_city_id, p_company_id, p_phone, p_mobile, p_telephone, p_email, p_last_log_by);
    
    SET p_new_warehouse_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateWarehouseTable(IN p_company_id VARCHAR(500), IN p_city_id VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT warehouse_id, warehouse_name, address, city_id, company_id FROM warehouse';
    
    SET conditionList = ' WHERE 1';

    IF p_company_id IS NOT NULL AND p_company_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_company_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_city_id IS NOT NULL AND p_city_id <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND city_id IN (');
        SET conditionList = CONCAT(conditionList, p_city_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY warehouse_name;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateWarehouseReferenceTable()
BEGIN
   SELECT warehouse_id, warehouse_name FROM warehouse
	ORDER BY warehouse_name;
END //


CREATE PROCEDURE generateWarehouseOptions()
BEGIN
	SELECT warehouse_id, warehouse_name FROM warehouse
	ORDER BY warehouse_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Product Category Table Stored Procedures */

CREATE PROCEDURE checkProductCategoryExist (IN p_product_category_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product_category
    WHERE product_category_id = p_product_category_id;
END //

CREATE PROCEDURE insertProductCategory(IN p_product_category_name VARCHAR(100), IN p_last_log_by INT, OUT p_product_category_id INT)
BEGIN
    INSERT INTO product_category (product_category_name, last_log_by) 
	VALUES(p_product_category_name, p_last_log_by);
	
    SET p_product_category_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateProductCategory(IN p_product_category_id INT, IN p_product_category_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE product_category
    SET product_category_name = p_product_category_name,
    last_log_by = p_last_log_by
    WHERE product_category_id = p_product_category_id;
END //

CREATE PROCEDURE deleteProductCategory(IN p_product_category_id INT)
BEGIN
    DELETE FROM product_category WHERE product_category_id = p_product_category_id;
END //

CREATE PROCEDURE getProductCategory(IN p_product_category_id INT)
BEGIN
	SELECT * FROM product_category
    WHERE product_category_id = p_product_category_id;
END //

CREATE PROCEDURE duplicateProductCategory(IN p_product_category_id INT, IN p_last_log_by INT, OUT p_new_product_category_id INT)
BEGIN
    DECLARE p_product_category_name VARCHAR(100);
    
    SELECT product_category_name
    INTO p_product_category_name
    FROM product_category 
    WHERE product_category_id = p_product_category_id;
    
    INSERT INTO product_category (product_category_name, last_log_by) 
    VALUES(p_product_category_name, p_last_log_by);
    
    SET p_new_product_category_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateProductCategoryTable()
BEGIN
    SELECT product_category_id, product_category_name
    FROM product_category
    ORDER BY product_category_id;
END //

CREATE PROCEDURE generateProductCategoryOptions()
BEGIN
	SELECT product_category_id, product_category_name FROM product_category
	ORDER BY product_category_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Product subcategory Table Stored Procedures */

CREATE PROCEDURE checkProductSubcategoryExist (IN p_product_subcategory_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product_subcategory
    WHERE product_subcategory_id = p_product_subcategory_id;
END //

CREATE PROCEDURE insertProductSubcategory(IN p_product_subcategory_name VARCHAR(100), IN p_product_subcategory_code VARCHAR(50), IN p_product_category_id INT, IN p_last_log_by INT, OUT p_product_subcategory_id INT)
BEGIN
    INSERT INTO product_subcategory (product_subcategory_name, product_subcategory_code, product_category_id, last_log_by) 
	VALUES(p_product_subcategory_name, p_product_subcategory_code, p_product_category_id, p_last_log_by);
	
    SET p_product_subcategory_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateProductSubcategory(IN p_product_subcategory_id INT, IN p_product_subcategory_name VARCHAR(100), IN p_product_subcategory_code VARCHAR(50), IN p_product_category_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE product_subcategory
    SET product_subcategory_name = p_product_subcategory_name,
    product_subcategory_code = p_product_subcategory_code,
    product_category_id = p_product_category_id,
    last_log_by = p_last_log_by
    WHERE product_subcategory_id = p_product_subcategory_id;
END //

CREATE PROCEDURE deleteProductSubcategory(IN p_product_subcategory_id INT)
BEGIN
    DELETE FROM product_subcategory WHERE product_subcategory_id = p_product_subcategory_id;
END //

CREATE PROCEDURE getProductSubcategory(IN p_product_subcategory_id INT)
BEGIN
	SELECT * FROM product_subcategory
    WHERE product_subcategory_id = p_product_subcategory_id;
END //

CREATE PROCEDURE duplicateProductSubcategory(IN p_product_subcategory_id INT, IN p_last_log_by INT, OUT p_new_product_subcategory_id INT)
BEGIN
    DECLARE p_product_subcategory_name VARCHAR(100);
    DECLARE p_product_subcategory_code VARCHAR(50);
    DECLARE p_product_category_id INT;
    
    SELECT product_subcategory_name, product_subcategory_code, product_category_id
    INTO p_product_subcategory_name, p_product_subcategory_code, p_product_category_id
    FROM product_subcategory 
    WHERE product_subcategory_id = p_product_subcategory_id;
    
    INSERT INTO product_subcategory (product_subcategory_name, product_subcategory_code, product_category_id, last_log_by) 
    VALUES(p_product_subcategory_name, p_product_subcategory_code, p_product_category_id, p_last_log_by);
    
    SET p_new_product_subcategory_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateProductSubcategoryTable()
BEGIN
    SELECT *
    FROM product_subcategory
    ORDER BY product_subcategory_id;
END //

CREATE PROCEDURE generateProductSubcategoryOptions()
BEGIN
	SELECT product_subcategory_id, product_subcategory_name FROM product_subcategory
	ORDER BY product_subcategory_name;
END //

CREATE PROCEDURE generateProductSubcategoryByCategoryOptions(IN p_product_category_id INT)
BEGIN
	SELECT product_subcategory_id, product_subcategory_name FROM product_subcategory
    WHERE product_category_id = p_product_category_id
	ORDER BY product_subcategory_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Product Table Stored Procedures */

CREATE PROCEDURE checkProductExist (IN p_product_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE checkProductImageExist (IN p_product_image_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product_image
    WHERE product_image_id = p_product_image_id;
END //

CREATE PROCEDURE checkProductDocumentExist (IN p_product_document_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product_document
    WHERE product_document_id = p_product_document_id;
END //


/* TO CHANGE */
CREATE PROCEDURE insertProduct(IN p_product_category_id INT, IN p_product_subcategory_id INT, IN p_company_id INT, IN p_stock_number VARCHAR(100), IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_description VARCHAR(2000), IN p_warehouse_id INT, IN p_body_type_id INT, IN p_length DOUBLE, IN p_length_unit INT, IN p_running_hours DOUBLE, IN p_mileage DOUBLE, IN p_color_id INT, IN p_remarks VARCHAR(1000), IN p_orcr_no VARCHAR(200), IN p_orcr_date DATE, IN p_orcr_expiry_date DATE, IN p_received_from VARCHAR(500), IN p_received_from_address VARCHAR(1000), IN p_received_from_id_type INT, IN p_received_from_id_number VARCHAR(200), IN p_rr_no VARCHAR(100), IN p_supplier_id INT, IN p_ref_no VARCHAR(200), IN p_brand_id INT, IN p_cabin_id INT, IN p_model_id INT, IN p_make_id INT, IN p_class_id INT, IN p_mode_of_acquisition_id INT, IN p_broker VARCHAR(200), IN p_registered_owner VARCHAR(300), IN p_mode_of_registration VARCHAR(300), IN p_year_model VARCHAR(10), IN p_arrival_date DATE, IN p_checklist_date DATE, IN p_with_cr VARCHAR(5), IN p_with_plate VARCHAR(5), IN p_returned_to_supplier VARCHAR(500), IN p_quantity INT, IN p_preorder VARCHAR(5), IN p_last_log_by INT, OUT p_product_id INT)
BEGIN
    INSERT INTO product (product_category_id, product_subcategory_id, company_id, stock_number, engine_number, chassis_number, plate_number, description, warehouse_id, body_type_id, length, length_unit, running_hours, mileage, color_id, remarks, orcr_no, orcr_date, orcr_expiry_date, received_from, received_from_address, received_from_id_type, received_from_id_number, rr_no, supplier_id, ref_no, brand_id, cabin_id, model_id, make_id, class_id, mode_of_acquisition_id, broker, registered_owner, mode_of_registration, year_model, arrival_date, checklist_date, with_cr, with_plate, returned_to_supplier, quantity, preorder, last_log_by) 
	VALUES(p_product_category_id, p_product_subcategory_id, p_company_id, p_stock_number, p_engine_number, p_chassis_number, p_plate_number, p_description, p_warehouse_id, p_body_type_id, p_length, p_length_unit, p_running_hours, p_mileage, p_color_id, p_remarks, p_orcr_no, p_orcr_date, p_orcr_expiry_date, p_received_from, p_received_from_address, p_received_from_id_type, p_received_from_id_number, p_rr_no, p_supplier_id, p_ref_no, p_brand_id, p_cabin_id, p_model_id, p_make_id, p_class_id, p_mode_of_acquisition_id, p_broker, p_registered_owner, p_mode_of_registration, p_year_model, p_arrival_date, p_checklist_date, p_with_cr, p_with_plate, p_returned_to_supplier, p_quantity, p_preorder, p_last_log_by);
	
    SET p_product_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateProduct(IN p_product_id INT, IN p_product_category_id INT, IN p_product_subcategory_id INT, IN p_company_id INT, IN p_stock_number VARCHAR(100), IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_description VARCHAR(1000), IN p_warehouse_id INT, IN p_body_type_id INT, IN p_length DOUBLE, IN p_length_unit INT, IN p_running_hours DOUBLE, IN p_mileage DOUBLE, IN p_color_id INT, IN p_product_cost DOUBLE, IN p_product_price DOUBLE, IN p_remarks VARCHAR(1000), IN p_orcr_no VARCHAR(200), IN p_orcr_date DATE, IN p_orcr_expiry_date DATE, IN p_received_from VARCHAR(500), IN p_received_from_address VARCHAR(1000), IN p_received_from_id_type INT, IN p_received_from_id_number VARCHAR(200), IN p_unit_description VARCHAR(1000), IN p_rr_date DATE, IN p_rr_no VARCHAR(100), IN p_supplier_id INT, IN p_ref_no VARCHAR(200), IN p_brand_id INT, IN p_cabin_id INT, IN p_model_id INT, IN p_make_id INT, IN p_class_id INT, IN p_mode_of_acquisition_id INT, IN p_broker VARCHAR(200), IN p_registered_owner VARCHAR(300), IN p_mode_of_registration VARCHAR(300), IN p_year_model VARCHAR(10), IN p_arrival_date DATE, IN p_checklist_date DATE, IN p_fx_rate DOUBLE, IN p_unit_cost DOUBLE, IN p_package_deal DOUBLE, IN p_taxes_duties DOUBLE, IN p_freight DOUBLE, IN p_lto_registration DOUBLE, IN p_royalties DOUBLE, IN p_conversion DOUBLE, IN p_arrastre DOUBLE, IN p_wharrfage DOUBLE, IN p_insurance DOUBLE, IN p_aircon DOUBLE, IN p_import_permit DOUBLE, IN p_others DOUBLE, IN p_sub_total DOUBLE, IN p_total_landed_cost DOUBLE, IN p_with_cr VARCHAR(5), IN p_with_plate VARCHAR(5), IN p_returned_to_supplier VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE product
    SET product_category_id = p_product_category_id,
    product_subcategory_id = p_product_subcategory_id,
    company_id = p_company_id,
    stock_number = p_stock_number,
    engine_number = p_engine_number,
    chassis_number = p_chassis_number,
    plate_number = p_plate_number,
    description = p_description,
    warehouse_id = p_warehouse_id,
    body_type_id = p_body_type_id,
    length = p_length,
    length_unit = p_length_unit,
    running_hours = p_running_hours,
    mileage = p_mileage,
    color_id = p_color_id,
    product_cost = p_product_cost,
    product_price = p_product_price,
    remarks = p_remarks,
    orcr_no = p_orcr_no,
    orcr_date = p_orcr_date,
    orcr_expiry_date = p_orcr_expiry_date,
    received_from = p_received_from,
    received_from_address = p_received_from_address,
    received_from_id_type = p_received_from_id_type,
    received_from_id_number = p_received_from_id_number,
    unit_description = p_unit_description,
    rr_date = p_rr_date,
    rr_no = p_rr_no,
    supplier_id = p_supplier_id,
    ref_no = p_ref_no,
    brand_id = p_brand_id,
    cabin_id = p_cabin_id,
    model_id = p_model_id,
    make_id = p_make_id,
    class_id = p_class_id,
    mode_of_acquisition_id = p_mode_of_acquisition_id,
    broker = p_broker,
    registered_owner = p_registered_owner,
    mode_of_registration = p_mode_of_registration,
    year_model = p_year_model,
    arrival_date = p_arrival_date,
    checklist_date = p_checklist_date,
    fx_rate = p_fx_rate,
    unit_cost = p_unit_cost,
    package_deal = p_package_deal,
    taxes_duties = p_taxes_duties,
    freight = p_freight,
    lto_registration = p_lto_registration,
    royalties = p_royalties,
    conversion = p_conversion,
    arrastre = p_arrastre,
    wharrfage = p_wharrfage,
    insurance = p_insurance,
    aircon = p_aircon,
    import_permit = p_import_permit,
    others = p_others,
    sub_total = p_sub_total,
    total_landed_cost = p_total_landed_cost,
    with_cr = p_with_cr,
    with_plate = p_with_plate,
    returned_to_supplier = p_returned_to_supplier,
    last_log_by = p_last_log_by
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE updateProductLandedCost(IN p_product_id INT, IN p_product_price DOUBLE, IN p_fx_rate DOUBLE, IN p_converted_amount DOUBLE, IN p_unit_cost DOUBLE, IN p_package_deal DOUBLE, IN p_taxes_duties DOUBLE, IN p_freight DOUBLE, IN p_lto_registration DOUBLE, IN p_royalties DOUBLE, IN p_conversion DOUBLE, IN p_arrastre DOUBLE, IN p_wharrfage DOUBLE, IN p_insurance DOUBLE, IN p_aircon DOUBLE, IN p_import_permit DOUBLE, IN p_others DOUBLE, IN p_total_landed_cost DOUBLE, IN p_payment_ref_no VARCHAR(100), IN p_payment_ref_date DATE, IN p_payment_ref_amount DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE product
    SET product_price = p_product_price,
    fx_rate = p_fx_rate,
    converted_amount = p_converted_amount,
    unit_cost = p_unit_cost,
    package_deal = p_package_deal,
    taxes_duties = p_taxes_duties,
    freight = p_freight,
    lto_registration = p_lto_registration,
    royalties = p_royalties,
    conversion = p_conversion,
    arrastre = p_arrastre,
    wharrfage = p_wharrfage,
    insurance = p_insurance,
    aircon = p_aircon,
    import_permit = p_import_permit,
    others = p_others,
    total_landed_cost = p_total_landed_cost,
    payment_ref_no = p_payment_ref_no,
    payment_ref_date = p_payment_ref_date,
    payment_ref_amount = p_payment_ref_amount,
    last_log_by = p_last_log_by
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE updateProductDetails(IN p_product_id INT, IN p_stock_number VARCHAR(100), IN p_product_category_id INT, IN p_product_subcategory_id INT, IN p_company_id INT, IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_description VARCHAR(2000), IN p_warehouse_id INT, IN p_body_type_id INT, IN p_length DOUBLE, IN p_length_unit INT, IN p_running_hours DOUBLE, IN p_mileage DOUBLE, IN p_color_id INT, IN p_remarks VARCHAR(1000), IN p_orcr_no VARCHAR(200), IN p_orcr_date DATE, IN p_orcr_expiry_date DATE, IN p_received_from VARCHAR(500), IN p_received_from_address VARCHAR(1000), IN p_received_from_id_type INT, IN p_received_from_id_number VARCHAR(200), IN p_supplier_id INT, IN p_ref_no VARCHAR(200), IN p_brand_id INT, IN p_cabin_id INT, IN p_model_id INT, IN p_make_id INT, IN p_class_id INT, IN p_mode_of_acquisition_id INT, IN p_broker VARCHAR(200), IN p_registered_owner VARCHAR(300), IN p_mode_of_registration VARCHAR(300), IN p_year_model VARCHAR(10), IN p_arrival_date DATE, IN p_checklist_date DATE, IN p_with_cr VARCHAR(5), IN p_with_plate VARCHAR(5), IN p_returned_to_supplier VARCHAR(500), IN p_quantity INT, IN p_preorder VARCHAR(5), IN p_last_log_by INT)
BEGIN
	UPDATE product
    SET stock_number = p_stock_number,
    product_category_id = p_product_category_id,
    product_subcategory_id = p_product_subcategory_id,
    company_id = p_company_id,
    engine_number = p_engine_number,
    chassis_number = p_chassis_number,
    plate_number = p_plate_number,
    description = p_description,
    warehouse_id = p_warehouse_id,
    body_type_id = p_body_type_id,
    length = p_length,
    length_unit = p_length_unit,
    running_hours = p_running_hours,
    mileage = p_mileage,
    color_id = p_color_id,
    remarks = p_remarks,
    orcr_no = p_orcr_no,
    orcr_date = p_orcr_date,
    orcr_expiry_date = p_orcr_expiry_date,
    received_from = p_received_from,
    received_from_address = p_received_from_address,
    received_from_id_type = p_received_from_id_type,
    received_from_id_number = p_received_from_id_number,
    supplier_id = p_supplier_id,
    ref_no = p_ref_no,
    brand_id = p_brand_id,
    cabin_id = p_cabin_id,
    model_id = p_model_id,
    make_id = p_make_id,
    class_id = p_class_id,
    mode_of_acquisition_id = p_mode_of_acquisition_id,
    broker = p_broker,
    registered_owner = p_registered_owner,
    mode_of_registration = p_mode_of_registration,
    year_model = p_year_model,
    arrival_date = p_arrival_date,
    checklist_date = p_checklist_date,
    with_cr = p_with_cr,
    with_plate = p_with_plate,
    returned_to_supplier = p_returned_to_supplier,
    quantity = p_quantity,
    preorder = p_preorder,
    last_log_by = p_last_log_by
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE deleteProduct(IN p_product_id INT)
BEGIN
    /*DELETE FROM product_image WHERE product_id = p_product_id;*/
    DELETE FROM product WHERE product_id = p_product_id;
END //
/* TO CHANGE */ 

CREATE PROCEDURE insertImportedProduct(IN p_product_category_id INT, IN p_product_subcategory_id INT, IN p_company_id INT, IN p_product_status VARCHAR(50), IN p_stock_number VARCHAR(100), IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_description VARCHAR(1000), IN p_warehouse_id INT, IN p_body_type_id INT, IN p_length DOUBLE, IN p_length_unit INT, IN p_running_hours DOUBLE, IN p_mileage DOUBLE, IN p_color_id INT, IN p_product_cost DOUBLE, IN p_product_price DOUBLE, IN p_remarks VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    INSERT INTO product (product_category_id, product_subcategory_id, company_id, product_status, stock_number, engine_number, chassis_number, plate_number, description, warehouse_id, body_type_id, length, length_unit, running_hours, mileage, color_id, product_cost, product_price, remarks, last_log_by) 
	VALUES(p_product_category_id, p_product_subcategory_id, p_company_id, p_product_status, p_stock_number, p_engine_number, p_chassis_number, p_plate_number, p_description, p_warehouse_id, p_body_type_id, p_length, p_length_unit, p_running_hours, p_mileage, p_color_id, p_product_cost, p_product_price, p_remarks, p_last_log_by);
END //

CREATE PROCEDURE updateProductImage(IN p_product_id INT, IN p_product_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE product 
    SET product_image = p_product_image, 
    last_log_by = p_last_log_by 
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE updateProductRRNumber(IN p_product_id INT, IN p_rr_no VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE product 
    SET rr_no = p_rr_no, 
    last_log_by = p_last_log_by 
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE insertProductImage(IN p_product_id INT, IN p_product_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO product_image (product_id, product_image, last_log_by) VALUES(p_product_id, p_product_image, p_last_log_by);
END //

CREATE PROCEDURE insertProductDocument(IN p_product_id INT, IN p_document_type VARCHAR(200), IN p_product_document VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO product_document (product_id, product_document_type, document_path, last_log_by) VALUES(p_product_id, p_document_type, p_product_document, p_last_log_by);
END //

CREATE PROCEDURE generateProductImage(IN p_product_id INT)
BEGIN
	SELECT product_image_id, product_image FROM product_image
    WHERE product_id = p_product_id
	ORDER BY product_image_id;
END //

CREATE PROCEDURE generateProductDocument(IN p_product_id INT)
BEGIN
	SELECT product_document_id, product_document_type, document_path FROM product_document
    WHERE product_id = p_product_id
	ORDER BY product_document_id;
END //

CREATE PROCEDURE updateImportedProduct(IN p_product_id INT, IN p_product_category_id INT, IN p_company_id INT, IN p_product_status VARCHAR(50), IN p_product_subcategory_id INT, IN p_stock_number VARCHAR(100), IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_description VARCHAR(1000), IN p_warehouse_id INT, IN p_body_type_id INT, IN p_length DOUBLE, IN p_length_unit INT, IN p_running_hours DOUBLE, IN p_mileage DOUBLE, IN p_color_id INT, IN p_product_cost DOUBLE, IN p_product_price DOUBLE, IN p_remarks VARCHAR(1000), IN p_last_log_by INT)
BEGIN
	UPDATE product
    SET product_category_id = p_product_category_id,
    product_subcategory_id = p_product_subcategory_id,
    company_id = p_company_id,
    product_status = p_product_status,
    stock_number = p_stock_number,
    engine_number = p_engine_number,
    chassis_number = p_chassis_number,
    plate_number = p_plate_number,
    description = p_description,
    warehouse_id = p_warehouse_id,
    body_type_id = p_body_type_id,
    length = p_length,
    length_unit = p_length_unit,
    running_hours = p_running_hours,
    mileage = p_mileage,
    color_id = p_color_id,
    product_cost = p_product_cost,
    product_price = p_product_price,
    remarks = p_remarks,
    last_log_by = p_last_log_by
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE updateRRDate(IN p_product_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE product
    SET rr_date = NOW(),
    last_log_by = p_last_log_by
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE deleteProductImage(IN p_product_image_id INT)
BEGIN
    DELETE FROM product_image WHERE product_image_id = p_product_image_id;
END //

CREATE PROCEDURE getProduct(IN p_product_id INT)
BEGIN
	SELECT * FROM product
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE getProductImage(IN p_product_image_id INT)
BEGIN
	SELECT * FROM product_image
    WHERE product_image_id = p_product_image_id;
END //

CREATE PROCEDURE generateProductCard(
    IN p_offset INT, 
    IN p_product_per_page INT, 
    IN p_search VARCHAR(500), 
    IN p_product_category VARCHAR(500), 
    IN p_product_subcategory VARCHAR(500), 
    IN p_company VARCHAR(500), 
    IN p_warehouse VARCHAR(500), 
    IN p_body_type VARCHAR(500), 
    IN p_color VARCHAR(500), 
    IN p_product_cost_min DOUBLE, 
    IN p_product_cost_max DOUBLE, 
    IN p_product_price_min DOUBLE, 
    IN p_product_price_max DOUBLE, 
    IN p_product_status VARCHAR(100),
    IN p_created_start_date DATE,
    IN p_created_end_date DATE
)
BEGIN
    DECLARE sql_query LONGTEXT;

    -- Base query
    SET sql_query = 'SELECT * FROM product WHERE 1=1';

    -- Search condition
    IF p_search IS NOT NULL AND p_search <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND (stock_number LIKE ? OR description LIKE ?)');
    END IF;

    -- Product status condition
    IF p_product_status IS NOT NULL AND p_product_status <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND product_status = ', QUOTE(p_product_status));
    ELSE
        SET sql_query = CONCAT(sql_query, ' AND product_status != "Sold"');
    END IF;

    -- Additional filters
    IF p_product_category IS NOT NULL AND p_product_category <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND product_category_id IN (', p_product_category, ')');
    END IF;

    IF p_created_start_date IS NOT NULL AND p_created_end_date IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND (created_date BETWEEN ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_created_start_date));
        SET sql_query = CONCAT(sql_query, ' AND ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_created_end_date));
        SET sql_query = CONCAT(sql_query, ')');
    END IF;

    IF p_product_subcategory IS NOT NULL AND p_product_subcategory <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND product_subcategory_id IN (', p_product_subcategory, ')');
    END IF;

    IF p_company IS NOT NULL AND p_company <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND company_id IN (', p_company, ')');
    END IF;

    IF p_warehouse IS NOT NULL AND p_warehouse <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND warehouse_id IN (', p_warehouse, ')');
    END IF;

    IF p_body_type IS NOT NULL AND p_body_type <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND body_type_id IN (', p_body_type, ')');
    END IF;

    IF p_color IS NOT NULL AND p_color <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND color_id IN (', p_color, ')');
    END IF;

    -- Cost range condition
    IF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND product_cost BETWEEN ? AND ?');
    END IF;

    -- Price range condition
    IF p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND product_price BETWEEN ? AND ?');
    END IF;

    -- Sorting and pagination
    SET sql_query = CONCAT(sql_query, ' ORDER BY description LIMIT ?, ?');

    -- Prepare and execute statement
    PREPARE stmt FROM sql_query;
    
    IF p_search IS NOT NULL AND p_search <> '' THEN
        IF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL AND p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), p_product_cost_min, p_product_cost_max, p_product_price_min, p_product_price_max, p_offset, p_product_per_page;
        ELSEIF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL THEN
            EXECUTE stmt USING CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), p_product_cost_min, p_product_cost_max, p_offset, p_product_per_page;
        ELSEIF p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), p_product_price_min, p_product_price_max, p_offset, p_product_per_page;
        ELSE
            EXECUTE stmt USING CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), p_offset, p_product_per_page;
        END IF;
    ELSE
        IF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL AND p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING p_product_cost_min, p_product_cost_max, p_product_price_min, p_product_price_max, p_offset, p_product_per_page;
        ELSEIF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL THEN
            EXECUTE stmt USING p_product_cost_min, p_product_cost_max, p_offset, p_product_per_page;
        ELSEIF p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING p_product_price_min, p_product_price_max, p_offset, p_product_per_page;
        ELSE
            EXECUTE stmt USING p_offset, p_product_per_page;
        END IF;
    END IF;

    -- Deallocate the prepared statement
    DEALLOCATE PREPARE stmt;
END//

CREATE PROCEDURE generateProductTable(
    IN p_search VARCHAR(500), 
    IN p_product_category VARCHAR(500), 
    IN p_product_subcategory VARCHAR(500), 
    IN p_company VARCHAR(500), 
    IN p_warehouse VARCHAR(500), 
    IN p_body_type VARCHAR(500), 
    IN p_color VARCHAR(500), 
    IN p_product_cost_min DOUBLE, 
    IN p_product_cost_max DOUBLE, 
    IN p_product_price_min DOUBLE, 
    IN p_product_price_max DOUBLE,
    IN p_product_status VARCHAR(100),
    IN p_created_start_date DATE,
    IN p_created_end_date DATE,
    IN p_for_sale_start_date DATE,
    IN p_for_sale_end_date DATE,
    IN p_sold_start_date DATE,
    IN p_sold_end_date DATE
)
BEGIN
    DECLARE sql_query LONGTEXT;

    -- Start the SQL query with a base condition
    SET sql_query = 'SELECT * FROM product WHERE 1=1';

    -- Apply filters for product status
    IF p_product_status IS NOT NULL AND p_product_status <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND product_status = ', QUOTE(p_product_status));
    ELSE
        SET sql_query = CONCAT(sql_query, ' AND product_status != "Sold"');
    END IF;

    IF p_created_start_date IS NOT NULL AND p_created_end_date IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND (created_date BETWEEN ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_created_start_date));
        SET sql_query = CONCAT(sql_query, ' AND ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_created_end_date));
        SET sql_query = CONCAT(sql_query, ')');
    END IF;

    IF p_for_sale_start_date IS NOT NULL AND p_for_sale_end_date IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND (for_sale_date BETWEEN ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_for_sale_start_date));
        SET sql_query = CONCAT(sql_query, ' AND ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_for_sale_end_date));
        SET sql_query = CONCAT(sql_query, ')');
    END IF;

    IF p_sold_start_date IS NOT NULL AND p_sold_end_date IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND (sold_date BETWEEN ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_sold_start_date));
        SET sql_query = CONCAT(sql_query, ' AND ');
        SET sql_query = CONCAT(sql_query, QUOTE(p_sold_end_date));
        SET sql_query = CONCAT(sql_query, ')');
    END IF;

    -- Apply search filter
    IF p_search IS NOT NULL AND p_search <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND (stock_number LIKE ? OR description LIKE ?)');
    END IF;

    -- Apply category and subcategory filters
    IF p_product_category IS NOT NULL AND p_product_category <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND product_category_id IN (', p_product_category, ')');
    END IF;

    IF p_product_subcategory IS NOT NULL AND p_product_subcategory <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND product_subcategory_id IN (', p_product_subcategory, ')');
    END IF;

    -- Apply company and warehouse filters
    IF p_company IS NOT NULL AND p_company <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND company_id IN (', p_company, ')');
    END IF;

    IF p_warehouse IS NOT NULL AND p_warehouse <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND warehouse_id IN (', p_warehouse, ')');
    END IF;

    -- Apply body type and color filters
    IF p_body_type IS NOT NULL AND p_body_type <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND body_type_id IN (', p_body_type, ')');
    END IF;

    IF p_color IS NOT NULL AND p_color <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND color_id IN (', p_color, ')');
    END IF;

    -- Apply product cost and price range filters
    IF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND product_cost BETWEEN ? AND ?');
    END IF;

    IF p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
        SET sql_query = CONCAT(sql_query, ' AND product_price BETWEEN ? AND ?');
    END IF;

    -- Final ordering by stock number
    SET sql_query = CONCAT(sql_query, ' ORDER BY stock_number;');

    -- Prepare the statement
    PREPARE stmt FROM sql_query;

    -- Execute statement with appropriate parameters based on conditions
    IF p_search IS NOT NULL AND p_search <> '' THEN
        IF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL AND p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING CONCAT('%', p_search, '%'), CONCAT('%', p_search, '%'), p_product_cost_min, p_product_cost_max, p_product_price_min, p_product_price_max;
        ELSEIF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL THEN
            EXECUTE stmt USING CONCAT('%', p_search, '%'), CONCAT('%', p_search, '%'), p_product_cost_min, p_product_cost_max;
        ELSEIF p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING CONCAT('%', p_search, '%'), CONCAT('%', p_search, '%'), p_product_price_min, p_product_price_max;
        ELSE
            EXECUTE stmt USING CONCAT('%', p_search, '%'), CONCAT('%', p_search, '%');
        END IF;
    ELSE
        IF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL AND p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING p_product_cost_min, p_product_cost_max, p_product_price_min, p_product_price_max;
        ELSEIF p_product_cost_min IS NOT NULL AND p_product_cost_max IS NOT NULL THEN
            EXECUTE stmt USING p_product_cost_min, p_product_cost_max;
        ELSEIF p_product_price_min IS NOT NULL AND p_product_price_max IS NOT NULL THEN
            EXECUTE stmt USING p_product_price_min, p_product_price_max;
        ELSE
            EXECUTE stmt;
        END IF;
    END IF;

    -- Deallocate the prepared statement
    DEALLOCATE PREPARE stmt;
END//


CREATE PROCEDURE generateProductOptions()
BEGIN
	SELECT product_id, description, stock_number FROM product
	ORDER BY stock_number;
END //

CREATE PROCEDURE getTotalProductCost(IN p_product_id INT)
BEGIN
	SELECT SUM(expense_amount) AS expense_amount FROM product_expense
    WHERE product_id = p_product_id;
END //

CREATE PROCEDURE generateInStockProductOptions()
BEGIN
	SELECT product_id, description, stock_number FROM product
    WHERE product_status = 'In Stock'
	ORDER BY stock_number;
END //

CREATE PROCEDURE generateForSaleProductOptions()
BEGIN
	SELECT product_id, description, stock_number FROM product
    WHERE product_status = 'For Sale'
	ORDER BY stock_number;
END //

CREATE PROCEDURE generateWithApplicationProductOptions()
BEGIN
	SELECT product_id, description, stock_number FROM product
    WHERE product_status IN ('With Application', 'On-Process', 'Ready For Release', 'For DR', 'Sold')
	ORDER BY stock_number;
END //

CREATE PROCEDURE generateNotDraftProductOptions()
BEGIN
	SELECT product_id, description, stock_number FROM product
    WHERE product_status != 'Draft'
	ORDER BY stock_number;
END //

CREATE PROCEDURE generateAllProductOptions()
BEGIN
	SELECT product_id, description, stock_number FROM product
	ORDER BY stock_number;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Temporary Product Table Stored Procedures */

CREATE PROCEDURE deleteTempProduct()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM temp_product;

    ALTER TABLE temp_product AUTO_INCREMENT = 1;

    COMMIT;
END //

CREATE PROCEDURE insertImportProduct(IN p_product_id INT, IN p_product_category_id INT, IN p_product_subcategory_id INT, IN p_company_id INT, IN p_product_status VARCHAR(50), IN p_stock_number VARCHAR(100), IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_description VARCHAR(1000), IN p_warehouse_id INT, IN p_body_type_id INT, IN p_length DOUBLE, IN p_length_unit INT, IN p_running_hours DOUBLE, IN p_mileage DOUBLE, IN p_color_id INT, IN p_product_cost DOUBLE, IN p_product_price DOUBLE, IN p_remarks VARCHAR(1000))
BEGIN
    INSERT INTO temp_product (product_id, product_category_id, product_subcategory_id, company_id, product_status, stock_number, engine_number, chassis_number, plate_number, description, warehouse_id, body_type_id, length, length_unit, running_hours, mileage, color_id, product_cost, product_price, remarks) 
	VALUES(p_product_id, p_product_category_id, p_product_subcategory_id, p_company_id, p_product_status, p_stock_number, p_engine_number, p_chassis_number, p_plate_number, p_description, p_warehouse_id, p_body_type_id, p_length, p_length_unit, p_running_hours, p_mileage, p_color_id, p_product_cost, p_product_price, p_remarks);
END //

CREATE PROCEDURE generateImportProductTable(IN p_product_category VARCHAR(500), IN p_product_subcategory VARCHAR(500), IN p_company VARCHAR(500), IN p_warehouse VARCHAR(500), IN p_body_type VARCHAR(500), IN p_color VARCHAR(500))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM temp_product';
    
    SET conditionList = ' WHERE 1';

    IF p_product_category IS NOT NULL AND p_product_category <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND product_category_id IN (', p_product_category, ')');
    END IF;

    IF p_product_subcategory IS NOT NULL AND p_product_subcategory <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND product_subcategory_id IN (', p_product_subcategory, ')');
    END IF;

    IF p_company IS NOT NULL AND p_company <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (', p_company, ')');
    END IF;

    IF p_warehouse IS NOT NULL AND p_warehouse <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND warehouse_id IN (', p_warehouse, ')');
    END IF;

    IF p_body_type IS NOT NULL AND p_body_type <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND body_type_id IN (', p_body_type, ')');
    END IF;

    IF p_color IS NOT NULL AND p_color <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND color_id IN (', p_color, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY description;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE getImportedProduct(IN p_temp_product_id INT)
BEGIN
	SELECT * FROM temp_product
    WHERE temp_product_id = p_temp_product_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Customer Table Stored Procedures */

CREATE PROCEDURE checkCustomerExist (IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact
    WHERE contact_id = p_contact_id AND is_customer = 1;
END //

CREATE PROCEDURE checkCustomerNameExist (IN p_first_name VARCHAR(300), IN p_last_name VARCHAR(300))
BEGIN
	SELECT COUNT(*) AS total
    FROM personal_information
    WHERE first_name = p_first_name AND last_name = p_last_name;
END //

CREATE PROCEDURE checkCustomerPrimaryAddress (IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_address
    WHERE contact_id = p_contact_id AND is_primary = 1;
END //

CREATE PROCEDURE checkCustomerPrimaryContactInformation (IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_information
    WHERE contact_id = p_contact_id AND is_primary = 1;
END //

CREATE PROCEDURE checkCustomerPrimaryIdentification (IN p_contact_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_identification
    WHERE contact_id = p_contact_id AND is_primary = 1;
END //

CREATE PROCEDURE checkCustomerComakerSearch(IN p_contact_id INT, IN p_first_name VARCHAR(300), IN p_last_name VARCHAR(300))
BEGIN
    SELECT COUNT(*) AS total
    FROM personal_information
    WHERE first_name = p_first_name AND last_name = p_last_name 
    AND contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1 AND is_comaker = 1 AND contact_status = 'Active')
    AND contact_id != p_contact_id 
    AND contact_id NOT IN (SELECT comaker_id FROM contact_comaker WHERE contact_id = p_contact_id);
END //

CREATE PROCEDURE generateCustomerComakerSearchResultTable(IN p_contact_id INT, IN p_first_name VARCHAR(300), IN p_last_name VARCHAR(300))
BEGIN
    SELECT contact_id, file_as
    FROM personal_information
    WHERE first_name = p_first_name AND last_name = p_last_name 
    AND contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1 AND is_comaker = 1 AND contact_status = 'Active')
    AND contact_id != p_contact_id 
    AND contact_id NOT IN (SELECT comaker_id FROM contact_comaker WHERE contact_id = p_contact_id);
END //

CREATE PROCEDURE insertCustomer(IN p_customer_id INT, IN p_is_individual INT, IN p_last_log_by INT, OUT p_contact_id INT)
BEGIN
   INSERT INTO contact (customer_id, is_customer, is_individual, last_log_by) 
	VALUES(p_customer_id, 1, p_is_individual, p_last_log_by);
	
    SET p_contact_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateCustomerStatus(IN p_contact_id INT, IN p_contact_status VARCHAR(50), IN p_last_log_by INT)
BEGIN
    UPDATE contact 
    SET contact_status = p_contact_status, 
    last_log_by = p_last_log_by 
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE getCustomer(IN p_contact_id INT)
BEGIN
    SELECT * FROM contact
    WHERE contact_id = p_contact_id AND is_customer = 1;
END //

CREATE PROCEDURE generateCustomerCard(IN p_offset INT, IN p_customer_per_page INT, IN p_search VARCHAR(500), IN p_customer_status VARCHAR(50), IN p_gender_filter VARCHAR(500), IN p_civil_status_filter VARCHAR(500), IN p_blood_type_filter VARCHAR(500), IN p_religion_filter VARCHAR(500), p_min_age INT, p_max_age INT)
BEGIN
    DECLARE sql_query VARCHAR(5000);

    SET sql_query = 'SELECT 
        c.contact_id AS contact_id, customer_id, contact_image, 
        file_as, contact_status
    FROM contact c
    LEFT JOIN personal_information p ON p.contact_id = c.contact_id
    WHERE c.is_customer = 1';

    IF p_search IS NOT NULL AND p_search <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND (
            p.first_name LIKE ?
            OR p.middle_name LIKE ?
            OR p.last_name LIKE ?
            OR customer_id LIKE ?
        )');
    END IF;

    IF p_customer_status IS NOT NULL AND p_customer_status <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND contact_status = ', QUOTE(p_customer_status), '');
    END IF;

    IF p_gender_filter IS NOT NULL AND p_gender_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND gender_id IN (', p_gender_filter, ')');
    END IF;

    IF p_civil_status_filter IS NOT NULL AND p_civil_status_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND civil_status_id IN (', p_civil_status_filter, ')');
    END IF;

    IF p_blood_type_filter IS NOT NULL AND p_blood_type_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND blood_type_id IN (', p_blood_type_filter, ')');
    END IF;

    IF p_religion_filter IS NOT NULL AND p_religion_filter <> '' THEN
        SET sql_query = CONCAT(sql_query, ' AND religion_id IN (', p_religion_filter, ')');
    END IF;

    SET sql_query = CONCAT(sql_query, ' ORDER BY p.last_name LIMIT ?, ?;');

    PREPARE stmt FROM sql_query;
    IF p_search IS NOT NULL AND p_search <> '' THEN
        EXECUTE stmt USING CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), CONCAT("%", p_search, "%"), p_offset, p_customer_per_page;
    ELSE
        EXECUTE stmt USING p_offset, p_customer_per_page;
    END IF;

    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateCustomerSearchResultTable(IN p_first_name VARCHAR(300), IN p_middle_name VARCHAR(300), IN p_last_name VARCHAR(300))
BEGIN
   IF p_middle_name IS NOT NULL AND p_middle_name <> '' THEN
        SELECT contact_id, file_as
        FROM personal_information
        WHERE first_name LIKE CONCAT('%', p_first_name, '%') AND middle_name LIKE CONCAT('%', p_middle_name, '%') AND last_name LIKE CONCAT('%', p_last_name, '%') AND contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1);
    ELSE
        SELECT contact_id, file_as
        FROM personal_information
        WHERE first_name LIKE CONCAT('%', p_first_name, '%') AND last_name LIKE CONCAT('%', p_last_name, '%') AND contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1);
    END IF;
END //

CREATE PROCEDURE checkCustomerSearch(IN p_first_name VARCHAR(300), IN p_middle_name VARCHAR(300), IN p_last_name VARCHAR(300))
BEGIN
    IF p_middle_name IS NOT NULL AND p_middle_name <> '' THEN
        SELECT COUNT(*) AS total
        FROM personal_information
        WHERE first_name LIKE CONCAT('%', p_first_name, '%') AND middle_name LIKE CONCAT('%', p_middle_name, '%') AND last_name LIKE CONCAT('%', p_last_name, '%') AND contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1 AND contact_status = 'Active');
    ELSE
        SELECT COUNT(*) AS total
        FROM personal_information
        WHERE first_name LIKE CONCAT('%', p_first_name, '%') AND last_name LIKE CONCAT('%', p_last_name, '%') AND contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1 AND contact_status = 'Active');
    END IF;
END //

CREATE PROCEDURE generateContactComakerSummary(IN p_contact_id INT)
BEGIN
    SELECT contact_comaker_id, comaker_id
    FROM contact_comaker
    WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE insertCustomerComaker(IN p_contact_id INT, IN p_comaker_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_comaker (contact_id, comaker_id, last_log_by) 
	VALUES(p_contact_id, p_comaker_id, p_last_log_by);
END //

CREATE PROCEDURE checkContactComakerExist (IN p_contact_comaker_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_comaker
    WHERE contact_comaker_id = p_contact_comaker_id;
END //

CREATE PROCEDURE deleteContactComaker (IN p_contact_comaker_id INT)
BEGIN
	DELETE FROM contact_comaker WHERE contact_comaker_id = p_contact_comaker_id;
END //

CREATE PROCEDURE getCustomerPrimaryAddress(IN p_contact_id INT)
BEGIN
	SELECT address, city_name, state_name, country_name FROM contact_address
    LEFT OUTER JOIN city ON city.city_id = contact_address.city_id
    LEFT OUTER JOIN state ON state.state_id = city.state_id
    LEFT OUTER JOIN country ON country.country_id = state.country_id
    WHERE contact_id = p_contact_id AND is_primary = 1;
END //

CREATE PROCEDURE getCustomerPrimaryContactInformation(IN p_contact_id INT)
BEGIN
	SELECT mobile, telephone, email FROM contact_information
    WHERE contact_id = p_contact_id AND is_primary = 1;
END //

CREATE PROCEDURE getCustomerPrimaryContactIdentification(IN p_contact_id INT)
BEGIN
	SELECT * FROM contact_identification
    LEFT OUTER JOIN id_type ON id_type.id_type_id = contact_identification.id_type_id
    WHERE contact_id = p_contact_id AND is_primary = 1;
END //

CREATE PROCEDURE generateComakerOptions(IN p_contact_id INT)
BEGIN
    SELECT contact_id, file_as
    FROM personal_information
    WHERE contact_id IN (SELECT comaker_id FROM contact_comaker WHERE contact_id = p_contact_id);
END //


/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalProduct (IN p_product_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product
    WHERE product_id IN (SELECT product_id FROM sales_proposal where product_id = p_product_id AND sales_proposal_status IN ('Proceed', 'On-Process', 'Ready For Release', 'For CI', 'For DR', 'Released'));
END //

CREATE PROCEDURE checkSalesProposalExist (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE countSalesProposalOtherChargesExist (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_manual_pdc_input
    WHERE sales_proposal_id = p_sales_proposal_id AND payment_for = 'Other Charges';
END //

CREATE PROCEDURE checkSalesProposalRepaymentExist (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_repayment
    WHERE sales_proposal_id = p_sales_proposal_id;
END //



CREATE PROCEDURE insertSalesProposal(IN p_sales_proposal_number VARCHAR(100), IN p_customer_id INT, IN p_comaker_id INT, IN p_product_type VARCHAR(100), IN p_transaction_type VARCHAR(100), IN p_financing_institution VARCHAR(200), IN p_referred_by VARCHAR(100), IN p_release_date DATE, IN p_start_date DATE, IN p_first_due_date DATE, IN p_term_length INT, IN p_term_type VARCHAR(20), IN p_number_of_payments INT, IN p_payment_frequency VARCHAR(20), IN p_remarks VARCHAR(500), IN p_created_by INT, IN p_initial_approving_officer INT, IN p_final_approving_officer INT, IN p_renewal_tag VARCHAR(10), IN p_application_source_id INT, IN p_commission_amount DOUBLE, IN p_company_id INT, IN p_last_log_by INT, OUT p_sales_proposal_id INT)
BEGIN
    SET time_zone = '+08:00';

    INSERT INTO sales_proposal (sales_proposal_number, customer_id, comaker_id, product_type, transaction_type, financing_institution, referred_by, release_date, start_date, first_due_date, term_length, term_type, number_of_payments, payment_frequency, remarks, created_by, created_date, initial_approving_officer, final_approving_officer, renewal_tag, application_source_id, commission_amount, company_id, last_log_by) 
	VALUES(p_sales_proposal_number, p_customer_id, p_comaker_id, p_product_type, p_transaction_type, p_financing_institution, p_referred_by, p_release_date, p_start_date, p_first_due_date, p_term_length, p_term_type, p_number_of_payments, p_payment_frequency, p_remarks, p_created_by, NOW(), p_initial_approving_officer, p_final_approving_officer, p_renewal_tag, p_application_source_id, p_commission_amount, p_company_id, p_last_log_by);
	
    SET p_sales_proposal_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE insertSalesProposalRepayment(IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_reference VARCHAR(200), IN p_due_date DATE, IN p_due_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

    INSERT INTO sales_proposal_repayment (sales_proposal_id, loan_number, reference, due_date, due_amount, last_log_by) 
	VALUES(p_sales_proposal_id, p_loan_number, p_reference, p_due_date, p_due_amount, p_last_log_by);
END //

CREATE PROCEDURE insertPDCCollection(IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_customer_id INT, IN p_payment_amount DOUBLE, IN p_check_number VARCHAR(100), IN p_check_date DATE, IN p_bank_branch VARCHAR(200), IN p_account_number VARCHAR(100), IN p_company_id INT, IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

    INSERT INTO loan_collections (sales_proposal_id, loan_number, customer_id, pdc_type, mode_of_payment, payment_details, payment_amount, check_number, check_date, payment_date, bank_branch, account_number, company_id, last_log_by) 
	VALUES(p_sales_proposal_id, p_loan_number, p_customer_id, 'Loan', 'Check', 'Acct Amort', p_payment_amount, p_check_number, p_check_date, p_check_date, p_bank_branch, p_account_number, p_company_id, p_last_log_by);
END //

CREATE PROCEDURE getPDCManagement(IN p_loan_collection_id INT)
BEGIN
	SELECT * FROM loan_collections
    WHERE loan_collection_id = p_loan_collection_id;
END //

CREATE PROCEDURE getCollections(IN p_loan_collection_id INT)
BEGIN
	SELECT * FROM loan_collections
    WHERE loan_collection_id = p_loan_collection_id;
END //

CREATE PROCEDURE insertPDCManagement(IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_product_id INT, IN p_customer_id INT, IN p_leasing_application_id INT, IN p_pdc_type VARCHAR(20), IN p_check_number VARCHAR(100), IN p_check_date DATE, IN p_payment_amount DOUBLE, IN p_payment_details VARCHAR(100), IN p_bank_branch VARCHAR(200), IN p_remarks VARCHAR(500), IN p_account_number VARCHAR(100), IN p_company_id INT, IN p_last_log_by INT, OUT p_loan_collection_id INT)
BEGIN
    SET time_zone = '+08:00';

    INSERT INTO loan_collections (sales_proposal_id, loan_number, product_id, customer_id, leasing_application_id, pdc_type, mode_of_payment, payment_details, payment_amount, check_number, check_date, bank_branch, remarks, account_number, company_id, last_log_by) 
	VALUES(p_sales_proposal_id, p_loan_number, p_product_id, p_customer_id, p_leasing_application_id, p_pdc_type, 'Check', p_payment_details, p_payment_amount, p_check_number, p_check_date, p_bank_branch, p_remarks, p_account_number, p_company_id, p_last_log_by);

    SET p_loan_collection_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE insertCollection(IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_product_id INT, IN p_customer_id INT, IN p_leasing_application_id INT, IN p_leasing_application_repayment_id INT, IN p_leasing_other_charges_id INT, IN p_leasing_collections_id INT, IN p_payment_for VARCHAR(50), IN p_pdc_type VARCHAR(20), IN p_mode_of_payment VARCHAR(100), IN p_or_number VARCHAR(100), IN p_or_date DATE, IN p_payment_date DATE, IN p_payment_amount DOUBLE, IN p_reference_number VARCHAR(200), IN p_payment_details VARCHAR(100), IN p_company_id INT, IN p_deposited_to INT, IN p_remarks VARCHAR(500), IN p_collected_from VARCHAR(200), IN p_payment_advice VARCHAR(5), IN p_last_log_by INT, OUT p_loan_collection_id INT)
BEGIN
    SET time_zone = '+08:00';

    INSERT INTO loan_collections (sales_proposal_id, loan_number, product_id, customer_id, leasing_application_id, leasing_application_repayment_id, leasing_other_charges_id, leasing_collections_id, payment_for, pdc_type, mode_of_payment, or_number, or_date, payment_date, payment_amount, reference_number, payment_details, company_id, deposited_to, remarks, transaction_date, collection_status, collected_from, payment_advice, last_log_by) 
	VALUES(p_sales_proposal_id, p_loan_number, p_product_id, p_customer_id, leasing_application_id, p_leasing_application_repayment_id, p_leasing_other_charges_id, p_leasing_collections_id, p_payment_for, p_pdc_type, p_mode_of_payment, p_or_number, p_or_date, p_payment_date, p_payment_amount, p_reference_number, p_payment_details, p_company_id, p_deposited_to, p_remarks, NOW(), 'Posted', p_collected_from, p_payment_advice, p_last_log_by);

    SET p_loan_collection_id = LAST_INSERT_ID();

    INSERT INTO loan_collections_history (loan_collection_id, mode_of_payment, transaction_date, transaction_type, reference_number, reference_date, last_log_by) 
    VALUES(p_loan_collection_id, p_mode_of_payment, NOW(), 'Posted', p_or_number, p_or_date, p_last_log_by);
END //

CREATE PROCEDURE updateCollection(IN p_loan_collection_id INT, IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_product_id INT, IN p_customer_id INT, IN p_leasing_application_id INT, IN p_leasing_application_repayment_id INT, IN p_leasing_other_charges_id INT, IN p_payment_for VARCHAR(50), IN p_pdc_type VARCHAR(20), IN p_mode_of_payment VARCHAR(100), IN p_or_number VARCHAR(100), IN p_or_date DATE, IN p_payment_date DATE, IN p_payment_amount DOUBLE, IN p_reference_number VARCHAR(200), IN p_payment_details VARCHAR(100), IN p_company_id INT, IN p_deposited_to INT, IN p_remarks VARCHAR(500), IN p_collected_from VARCHAR(200), IN p_payment_advice VARCHAR(5), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE loan_collections
    SET sales_proposal_id = p_sales_proposal_id,
    loan_number = p_loan_number,
    product_id = p_product_id,
    customer_id = p_customer_id,
    leasing_application_id = p_leasing_application_id,
    leasing_application_repayment_id = p_leasing_application_repayment_id,
    leasing_other_charges_id = p_leasing_other_charges_id,
    payment_for = p_payment_for,
    pdc_type = p_pdc_type,
    mode_of_payment = p_mode_of_payment,
    or_number = p_or_number,
    or_date = p_or_date,
    payment_amount = p_payment_amount,
    reference_number = p_reference_number,
    payment_date = p_payment_date,
    payment_details = p_payment_details,
    company_id = p_company_id,
    deposited_to = p_deposited_to,
    collected_from = p_collected_from,
    remarks = p_remarks,
    payment_advice = p_payment_advice,
    last_log_by = p_last_log_by
    WHERE loan_collection_id = p_loan_collection_id;
END //

CREATE PROCEDURE updatePDCManagement(IN p_loan_collection_id INT, IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_product_id INT, IN p_customer_id INT, IN p_leasing_application_id INT, IN p_pdc_type VARCHAR(20), IN p_check_number VARCHAR(100), IN p_check_date DATE, IN p_payment_amount DOUBLE, IN p_payment_details VARCHAR(100), IN p_bank_branch VARCHAR(200), IN p_remarks VARCHAR(500), IN p_account_number VARCHAR(100), IN p_company_id INT, IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE loan_collections
    SET sales_proposal_id = p_sales_proposal_id,
    loan_number = p_loan_number,
    product_id = p_product_id,
    customer_id = p_customer_id,
    leasing_application_id = p_leasing_application_id,
    pdc_type = p_pdc_type,
    check_number = p_check_number,
    check_date = p_check_date,
    payment_amount = p_payment_amount,
    payment_details = p_payment_details,
    bank_branch = p_bank_branch,
    remarks = p_remarks,
    account_number = p_account_number,
    company_id = p_company_id,
    last_log_by = p_last_log_by
    WHERE loan_collection_id = p_loan_collection_id;
END //


CREATE PROCEDURE checkLoanCollectionExist (IN p_loan_collection_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM loan_collections
    WHERE loan_collection_id = p_loan_collection_id;
END //

CREATE PROCEDURE checkLoanCollectionReferenceExist (IN p_reference_number VARCHAR(200))
BEGIN
	SELECT COUNT(*) AS total
    FROM loan_collections
    WHERE reference_number = p_reference_number;
END //


CREATE PROCEDURE checkLoanCollectionConflict (IN p_loan_collection_id INT, IN p_sales_proposal_id INT, IN p_check_number VARCHAR(100))
BEGIN
    IF p_loan_collection_id IS NOT NULL AND p_loan_collection_id <> '' THEN
            SELECT COUNT(*) AS total
            FROM loan_collections
            WHERE loan_collection_id != p_loan_collection_id AND sales_proposal_id = p_sales_proposal_id AND check_number = p_check_number;
        ELSE
            SELECT COUNT(*) AS total
            FROM loan_collections
            WHERE sales_proposal_id = p_sales_proposal_id AND check_number = p_check_number;
        END IF;
    END //	
END //

CREATE PROCEDURE updateSalesProposal(IN p_sales_proposal_id INT, IN p_customer_id INT, IN p_comaker_id INT, IN p_product_type VARCHAR(100), IN p_transaction_type VARCHAR(100), IN p_financing_institution VARCHAR(200), IN p_referred_by VARCHAR(100), IN p_release_date DATE, IN p_start_date DATE, IN p_first_due_date DATE, IN p_term_length INT, IN p_term_type VARCHAR(20), IN p_number_of_payments INT, IN p_payment_frequency VARCHAR(20), IN p_remarks VARCHAR(500), IN p_initial_approving_officer INT, IN p_final_approving_officer INT, IN p_renewal_tag VARCHAR(10), IN p_application_source_id INT, IN p_commission_amount DOUBLE, IN p_company_id INT, IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE sales_proposal
    SET customer_id = p_customer_id,
    comaker_id = p_comaker_id,
    product_type = p_product_type,
    commission_amount = p_commission_amount,
    transaction_type = p_transaction_type,
    financing_institution = p_financing_institution,
    referred_by = p_referred_by,
    release_date = p_release_date,
    start_date = p_start_date,
    first_due_date = p_first_due_date,
    term_length = p_term_length,
    term_type = p_term_type,
    number_of_payments = p_number_of_payments,
    payment_frequency = p_payment_frequency,
    remarks = p_remarks,
    initial_approving_officer = p_initial_approving_officer,
    final_approving_officer = p_final_approving_officer,
    renewal_tag = p_renewal_tag,
    application_source_id = p_application_source_id,
    company_id = p_company_id,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalUnit(IN p_sales_proposal_id INT, IN p_product_id INT, IN p_for_registration VARCHAR(5), IN p_with_cr VARCHAR(5), IN p_for_transfer VARCHAR(5), IN p_for_change_color VARCHAR(5), IN p_new_color VARCHAR(100), IN p_for_change_body VARCHAR(5), IN p_new_body VARCHAR(100), IN p_for_change_engine VARCHAR(5), IN p_new_engine VARCHAR(100), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE sales_proposal
    SET product_id = p_product_id,
    for_registration = p_for_registration,
    with_cr = p_with_cr,
    for_transfer = p_for_transfer,
    for_change_color = p_for_change_color,
    new_color = p_new_color,
    for_change_body = p_for_change_body,
    new_body = p_new_body,
    for_change_engine = p_for_change_engine,
    new_engine = p_new_engine,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalFuel(IN p_sales_proposal_id INT, IN p_diesel_fuel_quantity DOUBLE, IN p_diesel_price_per_liter DOUBLE, IN p_regular_fuel_quantity DOUBLE, IN p_regular_price_per_liter DOUBLE, IN p_premium_fuel_quantity DOUBLE, IN p_premium_price_per_liter DOUBLE, IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE sales_proposal
    SET diesel_fuel_quantity = p_diesel_fuel_quantity,
    diesel_price_per_liter = p_diesel_price_per_liter,
    regular_fuel_quantity = p_regular_fuel_quantity,
    regular_price_per_liter = p_regular_price_per_liter,
    premium_fuel_quantity = p_premium_fuel_quantity,
    premium_price_per_liter = p_premium_price_per_liter,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalRefinancing(IN p_sales_proposal_id INT, IN p_ref_stock_no VARCHAR(100), IN p_ref_engine_no VARCHAR(100), IN p_ref_chassis_no VARCHAR(100), IN p_ref_plate_no VARCHAR(100), IN p_orcr_no VARCHAR(200), IN p_orcr_date DATE, IN p_orcr_expiry_date DATE, IN p_received_from VARCHAR(500), IN p_received_from_address VARCHAR(1000), IN p_received_from_id_type INT, IN p_received_from_id_number VARCHAR(200), IN p_unit_description VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE sales_proposal
    SET ref_stock_no = p_ref_stock_no,
    ref_engine_no = p_ref_engine_no,
    ref_chassis_no = p_ref_chassis_no,
    ref_plate_no = p_ref_plate_no,
    orcr_no = p_orcr_no,
    orcr_date = p_orcr_date,
    orcr_expiry_date = p_orcr_expiry_date,
    received_from = p_received_from,
    received_from_address = p_received_from_address,
    received_from_id_type = p_received_from_id_type,
    received_from_id_number = p_received_from_id_number,
    unit_description = p_unit_description,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE cronSalesProposalCancelDraft(IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE sales_proposal
        SET sales_proposal_status = 'Cancelled',
        cancellation_date = NOW(),
        cancellation_reason = 'Lapsed Draft',
        last_log_by = p_last_log_by
        WHERE sales_proposal_status = 'Draft' 
        AND created_date < NOW() - INTERVAL 31 DAY;
END //

CREATE PROCEDURE cronSalesProposalOverdueForCI()
BEGIN
   SELECT * FROM sales_proposal WHERE for_ci_date IS NOT NULL AND ci_completion_date IS NULL AND for_ci_date < NOW() - INTERVAL 8 DAY AND sales_proposal_status NOT IN ('Draft', 'For Review', 'Cancelled', 'Rejected', 'For Initial Approval');
END //

CREATE PROCEDURE updateSalesProposalStatus(IN p_sales_proposal_id INT, IN p_changed_by INT, IN p_sales_proposal_status VARCHAR(50), IN p_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

    IF p_sales_proposal_status = 'For Final Approval' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        initial_approval_date = NOW(),
        initial_approval_by = p_changed_by,
        initial_approval_remarks = p_remarks,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;

        UPDATE product SET product_status = 'With Application',
        last_log_by = p_last_log_by
        WHERE product_id = (SELECT product_id FROM sales_proposal WHERE sales_proposal_id = p_sales_proposal_id);
    ELSEIF p_sales_proposal_status = 'For CI' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        for_ci_date = NOW(),
        ci_status = null,
        ci_completion_date = null,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
    ELSEIF p_sales_proposal_status = 'Rejected' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        installment_sales_status = p_sales_proposal_status,
        rejection_date = NOW(),
        rejection_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;

        UPDATE product 
        SET product_status = 'For Sale'
        WHERE product_id = (
            SELECT product_id 
            FROM sales_proposal 
            WHERE sales_proposal_id = p_sales_proposal_id
        )
        AND NOT EXISTS (
            SELECT 1
            FROM sales_proposal sp
            WHERE sp.product_id = product.product_id
            AND sp.sales_proposal_status IN ('For Final Approval', 'Proceed', 'On-Process', 'Ready For Release', 'For DR', 'Released')
        );
    ELSEIF p_sales_proposal_status = 'Cancelled' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        cancellation_date = NOW(),
        cancellation_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;

        UPDATE product 
        SET product_status = 'For Sale'
        WHERE product_id = (
            SELECT product_id 
            FROM sales_proposal 
            WHERE sales_proposal_id = p_sales_proposal_id
        )
        AND NOT EXISTS (
            SELECT 1
            FROM sales_proposal sp
            WHERE sp.product_id = product.product_id
            AND sp.sales_proposal_status IN ('For Final Approval', 'Proceed', 'On-Process', 'Ready For Release', 'For DR', 'Released')
        );
    ELSEIF p_sales_proposal_status = 'Proceed' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        approval_date = NOW(),
        approval_by = p_changed_by,
        final_approval_remarks = p_remarks,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
    ELSEIF p_sales_proposal_status = 'On-Process' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        on_process_date = NOW(),
        additional_job_order_confirmation = '',
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;

        UPDATE product SET product_status = 'On-Process',
        last_log_by = p_last_log_by
        WHERE product_id = (SELECT product_id FROM sales_proposal WHERE sales_proposal_id = p_sales_proposal_id);
    ELSEIF p_sales_proposal_status = 'Ready For Release' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        ready_for_release_date = NOW(),
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;

        UPDATE product SET product_status = 'Ready For Release',
        last_log_by = p_last_log_by
        WHERE product_id = (SELECT product_id FROM sales_proposal WHERE sales_proposal_id = p_sales_proposal_id);
    ELSEIF p_sales_proposal_status = 'For DR' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        for_dr_date = NOW(),
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;

        UPDATE product SET product_status = 'For DR',
        last_log_by = p_last_log_by
        WHERE product_id = (SELECT product_id FROM sales_proposal WHERE sales_proposal_id = p_sales_proposal_id);
    ELSEIF p_sales_proposal_status = 'For Review' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        for_review_date = NOW(),
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
    ELSEIF p_sales_proposal_status = 'Draft' THEN
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        set_to_draft_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;

        UPDATE product 
        SET product_status = 'For Sale'
        WHERE product_id = (
            SELECT product_id 
            FROM sales_proposal 
            WHERE sales_proposal_id = p_sales_proposal_id
        )
        AND NOT EXISTS (
            SELECT 1
            FROM sales_proposal sp
            WHERE sp.product_id = product.product_id
            AND sp.sales_proposal_status IN ('For Final Approval', 'Proceed', 'On-Process', 'Ready For Release', 'For DR', 'Released')
        );
    ELSE
        UPDATE sales_proposal
        SET sales_proposal_status = p_sales_proposal_status,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
    END IF;
END //

CREATE PROCEDURE updateSalesProposalCIStatus(IN p_sales_proposal_id INT, IN p_ci_status VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal
    SET ci_status = p_ci_status,
    ci_completion_date = NOW(),
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;

    UPDATE sales_proposal
    SET sales_proposal_status = 'For Final Approval',
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id AND sales_proposal_status = 'For CI';
END //

CREATE PROCEDURE updateSalesProposalJobOrderProgress(IN p_sales_proposal_job_order_id INT, IN p_cost DOUBLE, IN p_progress DOUBLE, IN p_contractor_id INT, IN p_work_center_id INT, IN p_backjob VARCHAR(5), IN p_completion_date DATE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_job_order
    SET progress = p_progress,
    cost = p_cost,
    contractor_id = p_contractor_id,
    work_center_id = p_work_center_id,
    backjob = p_backjob,
    completion_date = p_completion_date,
    last_log_by = p_last_log_by
    WHERE sales_proposal_job_order_id = p_sales_proposal_job_order_id;
END //

CREATE PROCEDURE updateSalesProposalAdditionalJobOrderProgress(IN p_sales_proposal_additional_job_order_id INT, IN p_cost DOUBLE, IN p_progress DOUBLE, IN p_contractor_id INT, IN p_work_center_id INT, IN p_backjob VARCHAR(5), IN p_completion_date DATE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_additional_job_order
    SET progress = p_progress,
    cost = p_cost,
    contractor_id = p_contractor_id,
    work_center_id = p_work_center_id,
    backjob = p_backjob,
    completion_date = p_completion_date,
    last_log_by = p_last_log_by
    WHERE sales_proposal_additional_job_order_id = p_sales_proposal_additional_job_order_id;
END //

CREATE PROCEDURE updateSalesProposalChangeRequestStatus(IN p_sales_proposal_id INT, IN p_change_request_status VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal
    SET change_request_status = p_change_request_status,
    change_request_completion_date = NOW(),
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesInstallmentStatus(IN p_sales_proposal_id INT, IN p_sales_installment_status VARCHAR(100), IN p_installment_sales_approval_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal
    SET installment_sales_status = p_sales_installment_status,
    installment_sales_approval_date = NOW(),
    installment_sales_approval_remarks = p_installment_sales_approval_remarks,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSaleProposalValues(IN p_sales_proposal_id INT, IN p_term_length INT, IN p_add_on_charge DOUBLE, IN p_nominal_discount DOUBLE, IN p_interest_rate DOUBLE, IN p_downpayment DOUBLE, IN p_last_log_by INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    UPDATE sales_proposal
    SET term_length = p_term_length,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;

    UPDATE sales_proposal_pricing_computation
    SET add_on_charge = p_add_on_charge,
    nominal_discount = p_nominal_discount,
    interest_rate = p_interest_rate,
    downpayment = p_downpayment,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;

    COMMIT;
END //

CREATE PROCEDURE updateSalesProposalClientConfirmation(IN p_sales_proposal_id INT, IN p_client_confirmation VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET client_confirmation = p_client_confirmation,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalQualityControlForm(IN p_sales_proposal_id INT, IN p_quality_control_form VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET quality_control_form = p_quality_control_form,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalSetToDraft(IN p_sales_proposal_id INT, IN p_set_to_draft_file VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET set_to_draft_file = p_set_to_draft_file,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalOtherDocument(IN p_sales_proposal_id INT, IN p_other_document_file VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET other_document_file = p_other_document_file,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //



CREATE PROCEDURE updateSalesProposalOutgoingChecklist(IN p_sales_proposal_id INT, IN p_outgoing_checklist VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET outgoing_checklist = p_outgoing_checklist,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalUnitImage(IN p_sales_proposal_id INT, IN p_unit_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET unit_image = p_unit_image,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalAdditionalJobOrderConfirmationImage(IN p_sales_proposal_id INT, IN p_additional_job_order_confirmation VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET additional_job_order_confirmation = p_additional_job_order_confirmation,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalCreditAdvice(IN p_sales_proposal_id INT, IN p_credit_advice VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET credit_advice = p_credit_advice,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalStencil(IN p_sales_proposal_id INT, IN p_new_engine_stencil VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET new_engine_stencil = p_new_engine_stencil,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalActualStartDate(IN p_sales_proposal_id INT, IN p_dr_number VARCHAR(50), IN p_release_to VARCHAR(1000), IN p_actual_start_date DATE, IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET actual_start_date = p_actual_start_date,
        dr_number = p_dr_number,
        release_to = p_release_to,
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE updateSalesProposalAsReleased(IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_sales_proposal_status VARCHAR(50), IN p_release_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE sales_proposal
        SET loan_number = p_loan_number,
        sales_proposal_status = p_sales_proposal_status,
        release_remarks = p_release_remarks,
        released_date = NOW(),
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE deleteSalesProposal(IN p_sales_proposal_id INT)
BEGIN
    DELETE FROM sales_proposal WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getSalesProposal(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE generateSalesProposalTable(IN p_customer_id INT, IN p_sales_proposal_status VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT * FROM sales_proposal';
    
    SET conditionList = ' WHERE customer_id = ';
    SET conditionList = CONCAT(conditionList, p_customer_id);

    IF p_sales_proposal_status IS NOT NULL AND p_sales_proposal_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND sales_proposal_status =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_sales_proposal_status));
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY sales_proposal_number');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateSalesProposalReleasedTable(IN p_filter_released_date_start_date DATE, IN p_filter_released_date_end_date DATE)
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT * FROM sales_proposal';
    
    SET conditionList = ' WHERE 1';

    IF p_filter_released_date_start_date IS NOT NULL AND p_filter_released_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND released_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_released_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_released_date_end_date));
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' AND sales_proposal_status = "Released" ORDER BY sales_proposal_number;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateOwnCustomerSalesProposalTable(IN p_customer_id INT, IN p_contact_id INT, IN p_sales_proposal_status VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT * FROM sales_proposal';
    
    SET conditionList = ' WHERE customer_id = ';
    SET conditionList = CONCAT(conditionList, p_customer_id);
    SET conditionList = CONCAT(conditionList, ' AND created_by =');
    SET conditionList = CONCAT(conditionList, p_contact_id);

    IF p_sales_proposal_status IS NOT NULL AND p_sales_proposal_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND sales_proposal_status =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_sales_proposal_status));
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY sales_proposal_number');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateAllSalesProposalTable(IN p_sales_proposal_status VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT * FROM sales_proposal';

    SET conditionList = '';

    IF p_sales_proposal_status IS NOT NULL AND p_sales_proposal_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' WHERE sales_proposal_status =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_sales_proposal_status));
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY sales_proposal_number');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateDashboardForInitialApproval(IN p_initial_approving_officer INT)
BEGIN
    SELECT * FROM sales_proposal WHERE sales_proposal_status = 'For Initial Approval' AND initial_approving_officer = p_initial_approving_officer;
END //

CREATE PROCEDURE generateDashboardForFinalApproval(IN p_final_approving_officer  INT)
BEGIN
    SELECT * FROM sales_proposal WHERE sales_proposal_status = 'For Final Approval' AND final_approving_officer  = p_final_approving_officer;
END //

CREATE PROCEDURE generateOwnSalesProposalTable(IN p_contact_id INT, IN p_user_id INT, IN p_sales_proposal_status VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT * FROM sales_proposal';

    SET conditionList = '';
    SET conditionList = CONCAT(conditionList, ' WHERE created_by =');
    SET conditionList = CONCAT(conditionList, p_user_id);

    IF p_sales_proposal_status IS NOT NULL AND p_sales_proposal_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND sales_proposal_status =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_sales_proposal_status));
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY sales_proposal_number');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateSalesProposalChangeRequestTable()
BEGIN
   SELECT * FROM sales_proposal WHERE sales_proposal_status IN ('Proceed', 'On-Process', 'Ready For Release', 'For DR', 'Released') AND (for_registration = 'Yes' OR for_transfer = 'Yes' OR for_change_color = 'Yes' OR for_change_body = 'Yes' OR for_change_engine = 'Yes') AND change_request_status IS NULL;
END //

CREATE PROCEDURE generateApprovedSalesProposalTable()
BEGIN
   SELECT * FROM sales_proposal WHERE sales_proposal_status IN ('Proceed', 'On-Process', 'Ready For Release', 'For DR') AND product_type NOT IN ('Refinancing', 'Parts');
END //

CREATE PROCEDURE generateJobOrderMonitoringTable()
BEGIN
    SELECT * 
    FROM sales_proposal 
    WHERE 
        (
            sales_proposal_status IN ('Proceed', 'On-Process', 'Ready For Release', 'For DR') 
            OR 
            (sales_proposal_status = 'Released' AND DATE(released_date) >= '2025-03-01')
        )
        AND product_type NOT IN ('Refinancing', 'Parts') 
        AND sales_proposal_id IN (
            SELECT sales_proposal_id FROM sales_proposal_job_order 
            UNION 
            SELECT sales_proposal_id FROM sales_proposal_additional_job_order
        );
END //

CREATE PROCEDURE getJobOrderMonitoringTotalProgress(IN p_sales_proposal_id INT) 
BEGIN
    DECLARE total_progress DECIMAL(10,2);
    DECLARE total_count INT;

    -- Calculate the total progress sum
    SELECT 
        IFNULL(SUM(spjo.progress), 0) + IFNULL(SUM(spajo.progress), 0),
        IFNULL(COUNT(spjo.sales_proposal_job_order_id), 0) + IFNULL(COUNT(spajo.sales_proposal_additional_job_order_id), 0)
    INTO total_progress, total_count
    FROM sales_proposal sp
    LEFT JOIN sales_proposal_job_order spjo ON sp.sales_proposal_id = spjo.sales_proposal_id
    LEFT JOIN sales_proposal_additional_job_order spajo ON sp.sales_proposal_id = spajo.sales_proposal_id
    WHERE sp.sales_proposal_id = p_sales_proposal_id;

    -- Return the calculated progress percentage
    SELECT 
        IF(total_count > 0, total_progress / total_count, 0) AS total_progress_percentage;
END //



CREATE PROCEDURE generateLoanExtractionTable()
BEGIN
   SELECT * FROM sales_proposal WHERE sales_proposal_status IN ('Released');
END //

CREATE PROCEDURE generateIncomingSalesProposalTable()
BEGIN
   SELECT * FROM sales_proposal WHERE sales_proposal_status IN ('Draft', 'For Review', 'For Initial Approval', 'For Final Approval', 'For CI', 'Cancelled');
END //

CREATE PROCEDURE generateSalesProposalForCITable()
BEGIN
   SELECT * FROM sales_proposal WHERE (sales_proposal_status = 'For CI' OR (sales_proposal_status IN ('Proceed', 'On-Process', 'Ready For Release', 'For DR', 'Released') AND for_ci_date IS NOT NULL)) AND ci_status IS NULL AND ci_completion_date IS NULL;
END //

CREATE PROCEDURE generateInstallmentSalesApprovalTable()
BEGIN
   SELECT * FROM sales_proposal WHERE (sales_proposal_status = 'For CI' OR (sales_proposal_status IN ('Proceed', 'On-Process', 'Ready For Release', 'For DR', 'Released') AND for_ci_date IS NOT NULL)) AND (installment_sales_status IS NULL AND installment_sales_approval_date IS NULL) AND ((ci_status IS NULL AND ci_completion_date IS NULL) OR (ci_status IS NOT NULL AND ci_completion_date IS NOT NULL));
END //

CREATE PROCEDURE generateSalesProposalForBankFinancingTable()
BEGIN
   SELECT * FROM sales_proposal WHERE transaction_type = 'Bank Financing' AND sales_proposal_status = 'For Final Approval' AND credit_advice IS NULL;
END //

CREATE PROCEDURE generateSalesProposalForDRTable()
BEGIN
   SELECT * FROM sales_proposal WHERE sales_proposal_status = 'For DR' AND (product_type IN ('Refinancing', 'Fuel', 'Parts', 'Brand New', 'Restructure') OR (outgoing_checklist IS NOT NULL AND (((product_type = 'Unit' OR product_type = 'Repair') AND unit_image IS NOT NULL) OR (product_type != 'Unit' AND product_type != 'Repair'))));
END //

CREATE PROCEDURE generateForDrSalesProposalOptions()
BEGIN
	SELECT sales_proposal_id, sales_proposal_number, file_as
    FROM sales_proposal
    LEFT OUTER JOIN personal_information ON personal_information.contact_id = sales_proposal.customer_id
    WHERE sales_proposal_status = 'For DR'
    ORDER BY sales_proposal_number ASC;
END //

CREATE PROCEDURE generateLoanAccountOptions()
BEGIN
	SELECT sales_proposal_id, loan_number, file_as
    FROM sales_proposal
    LEFT OUTER JOIN personal_information ON personal_information.contact_id = sales_proposal.customer_id
    WHERE sales_proposal_status = 'Released'
    ORDER BY loan_number ASC;
END //

CREATE PROCEDURE generateLoanCollectionsOptions()
BEGIN
	SELECT sales_proposal_id, loan_number, file_as, stock_number
    FROM sales_proposal
    LEFT OUTER JOIN personal_information ON personal_information.contact_id = sales_proposal.customer_id
    LEFT OUTER JOIN product ON product.product_id = sales_proposal.product_id
    WHERE sales_proposal_status = 'Released'
    ORDER BY loan_number ASC;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Accessories Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalAccessoriesExist (IN p_sales_proposal_accessories_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_accessories
    WHERE sales_proposal_accessories_id = p_sales_proposal_accessories_id;
END //

CREATE PROCEDURE insertSalesProposalAccessories(IN p_sales_proposal_id INT, IN p_accessories VARCHAR(500), IN p_cost DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_accessories (sales_proposal_id, accessories, cost, last_log_by) 
	VALUES(p_sales_proposal_id, p_accessories, p_cost, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalAccessories(IN p_sales_proposal_accessories_id INT, IN p_sales_proposal_id INT, IN p_accessories VARCHAR(500), IN p_cost DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_accessories
    SET sales_proposal_id = p_sales_proposal_id,
    accessories = p_accessories,
    cost = p_cost,
    last_log_by = p_last_log_by
    WHERE sales_proposal_accessories_id = p_sales_proposal_accessories_id;
END //

CREATE PROCEDURE deleteSalesProposalAccessories(IN p_sales_proposal_accessories_id INT)
BEGIN
    DELETE FROM sales_proposal_accessories WHERE sales_proposal_accessories_id = p_sales_proposal_accessories_id;
END //

CREATE PROCEDURE getSalesProposalAccessories(IN p_sales_proposal_accessories_id INT)
BEGIN
	SELECT * FROM sales_proposal_accessories
    WHERE sales_proposal_accessories_id = p_sales_proposal_accessories_id;
END //

CREATE PROCEDURE getSalesProposalAccessoriesTotal(IN p_sales_proposal_id INT)
BEGIN
	SELECT SUM(cost) AS total FROM sales_proposal_accessories
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE generateSalesProposalAccessoriesTable(IN p_sales_proposal_id INT)
BEGIN
    SELECT *
    FROM sales_proposal_accessories
    WHERE sales_proposal_id = p_sales_proposal_id
    ORDER BY sales_proposal_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Job Order Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalJobOrderExist (IN p_sales_proposal_job_order_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_job_order
    WHERE sales_proposal_job_order_id = p_sales_proposal_job_order_id;
END //

CREATE PROCEDURE insertSalesProposalJobOrder(IN p_sales_proposal_id INT, IN p_job_order VARCHAR(500), IN p_cost DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_job_order (sales_proposal_id, job_order, cost, last_log_by) 
	VALUES(p_sales_proposal_id, p_job_order, p_cost, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalJobOrder(IN p_sales_proposal_job_order_id INT, IN p_sales_proposal_id INT, IN p_job_order VARCHAR(500), IN p_cost DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_job_order
    SET sales_proposal_id = p_sales_proposal_id,
    job_order = p_job_order,
    cost = p_cost,
    last_log_by = p_last_log_by
    WHERE sales_proposal_job_order_id = p_sales_proposal_job_order_id;
END //

CREATE PROCEDURE deleteSalesProposalJobOrder(IN p_sales_proposal_job_order_id INT)
BEGIN
    DELETE FROM sales_proposal_job_order WHERE sales_proposal_job_order_id = p_sales_proposal_job_order_id;
END //

CREATE PROCEDURE getSalesProposalJobOrder(IN p_sales_proposal_job_order_id INT)
BEGIN
	SELECT * FROM sales_proposal_job_order
    WHERE sales_proposal_job_order_id = p_sales_proposal_job_order_id;
END //

CREATE PROCEDURE getSalesProposalJobOrderTotal(IN p_sales_proposal_id INT)
BEGIN
	SELECT SUM(cost) AS total FROM sales_proposal_job_order
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE generateSalesProposalJobOrderTable(IN p_sales_proposal_id INT)
BEGIN
    SELECT *
    FROM sales_proposal_job_order
    WHERE sales_proposal_id = p_sales_proposal_id
    ORDER BY sales_proposal_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Additional Job Order Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalAdditionalJobOrderExist (IN p_sales_proposal_additional_job_order_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_additional_job_order
    WHERE sales_proposal_additional_job_order_id = p_sales_proposal_additional_job_order_id;
END //

CREATE PROCEDURE countSalesProposalAdditionalJobOrder (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_additional_job_order
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE insertSalesProposalAdditionalJobOrder(IN p_sales_proposal_id INT, IN p_job_order_number VARCHAR(500), IN p_job_order_date DATE, IN p_particulars VARCHAR(1000), IN p_cost DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_additional_job_order (sales_proposal_id, job_order_number, job_order_date, particulars, cost, last_log_by) 
	VALUES(p_sales_proposal_id, p_job_order_number, p_job_order_date, p_particulars, p_cost, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalAdditionalJobOrder(IN p_sales_proposal_additional_job_order_id INT, IN p_sales_proposal_id INT, IN p_job_order_number VARCHAR(500), IN p_job_order_date DATE, IN p_particulars VARCHAR(1000), IN p_cost DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_additional_job_order
    SET sales_proposal_id = p_sales_proposal_id,
    job_order_number = p_job_order_number,
    job_order_date = p_job_order_date,
    particulars = p_particulars,
    cost = p_cost,
    last_log_by = p_last_log_by
    WHERE sales_proposal_additional_job_order_id = p_sales_proposal_additional_job_order_id;
END //

CREATE PROCEDURE deleteSalesProposalAdditionalJobOrder(IN p_sales_proposal_additional_job_order_id INT)
BEGIN
    DELETE FROM sales_proposal_additional_job_order WHERE sales_proposal_additional_job_order_id = p_sales_proposal_additional_job_order_id;
END //

CREATE PROCEDURE getSalesProposalAdditionalJobOrder(IN p_sales_proposal_additional_job_order_id INT)
BEGIN
	SELECT * FROM sales_proposal_additional_job_order
    WHERE sales_proposal_additional_job_order_id = p_sales_proposal_additional_job_order_id;
END //

CREATE PROCEDURE getSalesProposalAdditionalJobOrderTotal(IN p_sales_proposal_id INT)
BEGIN
	SELECT SUM(cost) AS total FROM sales_proposal_additional_job_order
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getSalesProposalAmountOfDepositTotal(IN p_sales_proposal_id INT)
BEGIN
	SELECT SUM(gross_amount) AS total FROM sales_proposal_deposit_amount
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getPDCManualInputOtherChargesTotal(IN p_sales_proposal_id INT)
BEGIN
	SELECT SUM(gross_amount) AS total FROM sales_proposal_manual_pdc_input
    WHERE sales_proposal_id = p_sales_proposal_id AND payment_for = 'Other Charges';
END //

CREATE PROCEDURE generateSalesProposalAdditionalJobOrderTable(IN p_sales_proposal_id INT)
BEGIN
    SELECT *
    FROM sales_proposal_additional_job_order
    WHERE sales_proposal_id = p_sales_proposal_id
    ORDER BY sales_proposal_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Pricing Computation Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalPricingComputationExist (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_pricing_computation
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE insertSalesProposalPricingComputation(IN p_sales_proposal_id INT, IN p_delivery_price DOUBLE, IN p_cost_of_accessories DOUBLE, IN p_reconditioning_cost DOUBLE, IN p_subtotal DOUBLE, IN p_downpayment DOUBLE, IN p_outstanding_balance DOUBLE, IN p_amount_financed DOUBLE, IN p_pn_amount DOUBLE, IN p_repayment_amount DOUBLE, IN p_interest_rate DOUBLE, IN p_nominal_discount DOUBLE, IN p_total_delivery_price DOUBLE, IN p_add_on_charge DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_pricing_computation (sales_proposal_id, delivery_price, cost_of_accessories, reconditioning_cost, subtotal, downpayment, outstanding_balance, amount_financed, pn_amount, repayment_amount, interest_rate, nominal_discount, total_delivery_price, add_on_charge, last_log_by) 
	VALUES(p_sales_proposal_id, p_delivery_price, p_cost_of_accessories, p_reconditioning_cost, p_subtotal, p_downpayment, p_outstanding_balance, p_amount_financed, p_pn_amount, p_repayment_amount, p_interest_rate, p_nominal_discount, p_total_delivery_price, p_add_on_charge, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalPricingComputation(IN p_sales_proposal_id INT, IN p_delivery_price DOUBLE, IN p_cost_of_accessories DOUBLE, IN p_reconditioning_cost DOUBLE, IN p_subtotal DOUBLE, IN p_downpayment DOUBLE, IN p_outstanding_balance DOUBLE, IN p_amount_financed DOUBLE, IN p_pn_amount DOUBLE, IN p_repayment_amount DOUBLE, IN p_interest_rate DOUBLE, IN p_nominal_discount DOUBLE, IN p_total_delivery_price DOUBLE, IN p_add_on_charge DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_pricing_computation
    SET delivery_price = p_delivery_price,
    cost_of_accessories = p_cost_of_accessories,
    reconditioning_cost = p_reconditioning_cost,
    subtotal = p_subtotal,
    downpayment = p_downpayment,
    outstanding_balance = p_outstanding_balance,
    amount_financed = p_amount_financed,
    pn_amount = p_pn_amount,
    repayment_amount = p_repayment_amount,
    interest_rate = p_interest_rate,
    nominal_discount = p_nominal_discount,
    total_delivery_price = p_total_delivery_price,
    add_on_charge = p_add_on_charge,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getSalesProposalPricingComputation(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal_pricing_computation
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Pricing Other Charges Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalPricingOtherChargesExist (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_other_charges
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE insertSalesProposalOtherCharges(IN p_sales_proposal_id INT, IN p_insurance_coverage DOUBLE, IN p_insurance_premium DOUBLE, IN p_handling_fee DOUBLE, IN p_transfer_fee DOUBLE, IN p_registration_fee DOUBLE, IN p_doc_stamp_tax DOUBLE, IN p_transaction_fee DOUBLE, IN p_total_other_charges DOUBLE, IN p_insurance_premium_discount DOUBLE, IN p_insurance_premium_subtotal DOUBLE, IN p_handling_fee_discount DOUBLE, IN p_handling_fee_subtotal DOUBLE, IN p_transfer_fee_discount DOUBLE, IN p_transfer_fee_subtotal DOUBLE, IN p_doc_stamp_tax_discount DOUBLE, IN p_doc_stamp_tax_subtotal DOUBLE, IN p_transaction_fee_discount DOUBLE, IN p_transaction_fee_subtotal DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_other_charges (sales_proposal_id, insurance_coverage, insurance_premium, handling_fee, transfer_fee, registration_fee, doc_stamp_tax, transaction_fee, total_other_charges, insurance_premium_discount, insurance_premium_subtotal, handling_fee_discount, handling_fee_subtotal, transfer_fee_discount, transfer_fee_subtotal, doc_stamp_tax_discount, doc_stamp_tax_subtotal, transaction_fee_discount, transaction_fee_subtotal, last_log_by) 
	VALUES(p_sales_proposal_id, p_insurance_coverage, p_insurance_premium, p_handling_fee, p_transfer_fee, p_registration_fee, p_doc_stamp_tax, p_transaction_fee, p_total_other_charges, p_insurance_premium_discount, p_insurance_premium_subtotal, p_handling_fee_discount, p_handling_fee_subtotal, p_transfer_fee_discount, p_transfer_fee_subtotal, p_doc_stamp_tax_discount, p_doc_stamp_tax_subtotal, p_transaction_fee_discount, p_transaction_fee_subtotal, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalOtherCharges(IN p_sales_proposal_id INT, IN p_insurance_coverage DOUBLE, IN p_insurance_premium DOUBLE, IN p_handling_fee DOUBLE, IN p_transfer_fee DOUBLE, IN p_registration_fee DOUBLE, IN p_doc_stamp_tax DOUBLE, IN p_transaction_fee DOUBLE, IN p_total_other_charges DOUBLE, IN p_insurance_premium_discount DOUBLE, IN p_insurance_premium_subtotal DOUBLE, IN p_handling_fee_discount DOUBLE, IN p_handling_fee_subtotal DOUBLE, IN p_transfer_fee_discount DOUBLE, IN p_transfer_fee_subtotal DOUBLE, IN p_doc_stamp_tax_discount DOUBLE, IN p_doc_stamp_tax_subtotal DOUBLE, IN p_transaction_fee_discount DOUBLE, IN p_transaction_fee_subtotal DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_other_charges
    SET insurance_coverage = p_insurance_coverage,
    insurance_premium = p_insurance_premium,
    handling_fee = p_handling_fee,
    transfer_fee = p_transfer_fee,
    registration_fee = p_registration_fee,
    doc_stamp_tax = p_doc_stamp_tax,
    transaction_fee = p_transaction_fee,
    total_other_charges = p_total_other_charges,
    insurance_premium_discount = p_insurance_premium_discount,
    insurance_premium_subtotal = p_insurance_premium_subtotal,
    handling_fee_discount = p_handling_fee_discount,
    handling_fee_subtotal = p_handling_fee_subtotal,
    transfer_fee_discount = p_transfer_fee_discount,
    transfer_fee_subtotal = p_transfer_fee_subtotal,
    doc_stamp_tax_discount = p_doc_stamp_tax_discount,
    doc_stamp_tax_subtotal = p_doc_stamp_tax_subtotal,
    transaction_fee_discount = p_transaction_fee_discount,
    transaction_fee_subtotal = p_transaction_fee_subtotal,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getSalesProposalOtherCharges(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal_other_charges
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Pricing Renewal Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalRenewalAmountExist (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_renewal_amount
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE insertSalesProposalRenewalAmount(IN p_sales_proposal_id INT, IN p_registration_second_year DOUBLE, IN p_registration_third_year DOUBLE, IN p_registration_fourth_year DOUBLE, IN p_insurance_coverage_second_year DOUBLE, IN p_insurance_coverage_third_year DOUBLE, IN p_insurance_coverage_fourth_year DOUBLE, IN p_insurance_premium_second_year DOUBLE, IN p_insurance_premium_third_year DOUBLE, IN p_insurance_premium_fourth_year DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_renewal_amount (sales_proposal_id, registration_second_year, registration_third_year, registration_fourth_year, insurance_coverage_second_year, insurance_coverage_third_year, insurance_coverage_fourth_year, insurance_premium_second_year, insurance_premium_third_year, insurance_premium_fourth_year, last_log_by) 
	VALUES(p_sales_proposal_id, p_registration_second_year, p_registration_third_year, p_registration_fourth_year, p_insurance_coverage_second_year, p_insurance_coverage_third_year, p_insurance_coverage_fourth_year, p_insurance_premium_second_year, p_insurance_premium_third_year, p_insurance_premium_fourth_year, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalRenewalAmount(IN p_sales_proposal_id INT, IN p_registration_second_year DOUBLE, IN p_registration_third_year DOUBLE, IN p_registration_fourth_year DOUBLE, IN p_insurance_coverage_second_year DOUBLE, IN p_insurance_coverage_third_year DOUBLE, IN p_insurance_coverage_fourth_year DOUBLE, IN p_insurance_premium_second_year DOUBLE, IN p_insurance_premium_third_year DOUBLE, IN p_insurance_premium_fourth_year DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_renewal_amount
    SET registration_second_year = p_registration_second_year,
    registration_third_year = p_registration_third_year,
    registration_fourth_year = p_registration_fourth_year,
    insurance_coverage_second_year = p_insurance_coverage_second_year,
    insurance_coverage_third_year = p_insurance_coverage_third_year,
    insurance_coverage_fourth_year = p_insurance_coverage_fourth_year,
    insurance_premium_second_year = p_insurance_premium_second_year,
    insurance_premium_third_year = p_insurance_premium_third_year,
    insurance_premium_fourth_year = p_insurance_premium_fourth_year,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getSalesProposalRenewalAmount(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal_renewal_amount
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Deposit Amount Stored Procedures */

CREATE PROCEDURE checkSalesProposalDepositAmountExist (IN p_sales_proposal_deposit_amount_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_deposit_amount
    WHERE sales_proposal_deposit_amount_id = p_sales_proposal_deposit_amount_id;
END //

CREATE PROCEDURE insertSalesProposalDepositAmount(IN p_sales_proposal_id INT, IN p_deposit_date DATE, IN p_reference_number VARCHAR(100), IN p_deposit_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_deposit_amount (sales_proposal_id, deposit_date, reference_number, deposit_amount, last_log_by) 
	VALUES(p_sales_proposal_id, p_deposit_date, p_reference_number, p_deposit_amount, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalDepositAmount(IN p_sales_proposal_deposit_amount_id INT, IN p_sales_proposal_id INT, IN p_deposit_date DATE, IN p_reference_number VARCHAR(100), IN p_deposit_amount DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_deposit_amount
    SET sales_proposal_id = p_sales_proposal_id,
    deposit_date = p_deposit_date,
    reference_number = p_reference_number,
    deposit_amount = p_deposit_amount,
    last_log_by = p_last_log_by
    WHERE sales_proposal_deposit_amount_id = p_sales_proposal_deposit_amount_id;
END //

CREATE PROCEDURE deleteSalesProposalDepositAmount(IN p_sales_proposal_deposit_amount_id INT)
BEGIN
    DELETE FROM sales_proposal_deposit_amount WHERE sales_proposal_deposit_amount_id = p_sales_proposal_deposit_amount_id;
END //

CREATE PROCEDURE getSalesProposalDepositAmount(IN p_sales_proposal_deposit_amount_id INT)
BEGIN
	SELECT * FROM sales_proposal_deposit_amount
    WHERE sales_proposal_deposit_amount_id = p_sales_proposal_deposit_amount_id;
END //

CREATE PROCEDURE generateSalesProposalDepositAmountTable(IN p_sales_proposal_id INT)
BEGIN
    SELECT *
    FROM sales_proposal_deposit_amount
    WHERE sales_proposal_id = p_sales_proposal_id
    ORDER BY sales_proposal_id;
END //

CREATE PROCEDURE generateSalesProposalPDCManualInputTable(IN p_sales_proposal_id INT)
BEGIN
    SELECT *
    FROM sales_proposal_manual_pdc_input
    WHERE sales_proposal_id = p_sales_proposal_id
    ORDER BY payment_for asc, check_date asc;
END //

CREATE PROCEDURE insertSalesProposalManualPDCInput(IN p_sales_proposal_id INT, IN p_account_number VARCHAR(100), IN p_bank_branch VARCHAR(200), IN p_check_date DATE, IN p_check_number INT, IN p_payment_for VARCHAR(200), IN p_gross_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_manual_pdc_input (sales_proposal_id, account_number, bank_branch, check_date, check_number, payment_for, gross_amount, last_log_by) 
	VALUES(p_sales_proposal_id, p_account_number, p_bank_branch, p_check_date, p_check_number, p_payment_for, p_gross_amount, p_last_log_by);
END //

CREATE PROCEDURE checkSalesProposalManualPDCInputExist (IN p_manual_pdc_input_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_manual_pdc_input
    WHERE manual_pdc_input_id = p_manual_pdc_input_id;
END //

CREATE PROCEDURE deleteSalesProposalManualPDCInput(IN p_manual_pdc_input_id INT)
BEGIN
    DELETE FROM sales_proposal_manual_pdc_input WHERE manual_pdc_input_id = p_manual_pdc_input_id;
END //



/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Approving Officer Table Stored Procedures */

CREATE PROCEDURE checkApprovingOfficerIfExist (IN p_contact_id INT, IN p_approving_officer_type VARCHAR(10))
BEGIN
	SELECT COUNT(*) AS total
    FROM approving_officer
    WHERE contact_id = p_contact_id AND approving_officer_type = p_approving_officer_type;
END //

CREATE PROCEDURE checkApprovingOfficerExist (IN p_approving_officer_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM approving_officer
    WHERE approving_officer_id = p_approving_officer_id;
END //

CREATE PROCEDURE insertApprovingOfficer(IN p_contact_id INT, IN p_approving_officer_type VARCHAR(10), IN p_last_log_by INT, OUT p_approving_officer_id INT)
BEGIN
    INSERT INTO approving_officer (contact_id, approving_officer_type, last_log_by) 
	VALUES(p_contact_id, p_approving_officer_type, p_last_log_by);
	
    SET p_approving_officer_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE deleteApprovingOfficer(IN p_approving_officer_id INT)
BEGIN
	DELETE FROM approving_officer
    WHERE approving_officer_id = p_approving_officer_id;
END //

CREATE PROCEDURE getApprovingOfficer(IN p_approving_officer_id INT)
BEGIN
	SELECT * FROM approving_officer
    WHERE approving_officer_id = p_approving_officer_id;
END //

CREATE PROCEDURE generateApprovingOfficerTable()
BEGIN
	SELECT approving_officer_id, contact_id, approving_officer_type
    FROM approving_officer
    ORDER BY approving_officer_id;
END //



CREATE PROCEDURE generateApprovingOfficerOptions(IN p_approving_officer_type VARCHAR(10))
BEGIN
    IF p_approving_officer_type IS NOT NULL AND p_approving_officer_type <> '' THEN
        SELECT contact_id, file_as FROM personal_information 
        WHERE contact_id IN (SELECT contact_id FROM approving_officer WHERE approving_officer_type = p_approving_officer_type);
    ELSE
        SELECT contact_id, file_as FROM personal_information 
        WHERE contact_id IN (SELECT contact_id FROM approving_officer);
    END IF;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Pricing Other Charges Table Stored Procedures */

CREATE PROCEDURE checkSalesProposalOtherProductDetailsExist (IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_other_product_details
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE insertSalesProposalOtherProductDetails(IN p_sales_proposal_id INT, IN p_year_model VARCHAR(50), IN p_cr_no VARCHAR(100), IN p_mv_file_no VARCHAR(100), IN p_make VARCHAR(100), IN p_product_description VARCHAR(500), IN p_business_style VARCHAR(500), IN p_si DOUBLE, IN p_di DOUBLE, IN p_invoice_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
    INSERT INTO sales_proposal_other_product_details (sales_proposal_id, year_model, cr_no, mv_file_no, make, product_description, business_style, si, di, invoice_number, last_log_by) 
	VALUES(p_sales_proposal_id, p_year_model, p_cr_no, p_mv_file_no, p_make, p_product_description, p_business_style, p_si, p_di, p_invoice_number, p_last_log_by);
END //

CREATE PROCEDURE updateSalesProposalOtherProductDetails(IN p_sales_proposal_id INT, IN p_year_model VARCHAR(50), IN p_cr_no VARCHAR(100), IN p_mv_file_no VARCHAR(100), IN p_make VARCHAR(100), IN p_product_description VARCHAR(500), IN p_business_style VARCHAR(500), IN p_si DOUBLE, IN p_di DOUBLE, IN p_invoice_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE sales_proposal_other_product_details
    SET year_model = p_year_model,
    cr_no = p_cr_no,
    mv_file_no = p_mv_file_no,
    make = p_make,
    product_description = p_product_description,
    business_style = p_business_style,
    si = p_si,
    di = p_di,
    invoice_number = p_invoice_number,
    last_log_by = p_last_log_by
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getSalesProposalOtherProductDetails(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal_other_product_details
    WHERE sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE getSalesProposalRenewalPDCManualInputDetails(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal_manual_pdc_input
    WHERE sales_proposal_id = p_sales_proposal_id AND payment_for = 'Insurance Renewal';
END //

CREATE PROCEDURE getSalesProposalOtherChargesPDCManualInputDetails(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal_manual_pdc_input
    WHERE sales_proposal_id = p_sales_proposal_id AND payment_for = 'Other Charges';
END //

CREATE PROCEDURE getSalesProposalRegistrationRenewalPDCManualInputDetails(IN p_sales_proposal_id INT)
BEGIN
	SELECT * FROM sales_proposal_manual_pdc_input
    WHERE sales_proposal_id = p_sales_proposal_id AND payment_for = 'Registration Renewal';
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Tenant Table Stored Procedures */

CREATE PROCEDURE checkTenantExist (IN p_tenant_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM tenant
    WHERE tenant_id = p_tenant_id;
END //

CREATE PROCEDURE insertTenant(IN p_tenant_name VARCHAR(100), IN p_contact_person VARCHAR(500), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT, OUT p_tenant_id INT)
BEGIN
    INSERT INTO tenant (tenant_name, contact_person, address, city_id, phone, mobile, telephone, email, last_log_by) 
	VALUES(p_tenant_name, p_contact_person, p_address, p_city_id, p_phone, p_mobile, p_telephone, p_email, p_last_log_by);
	
    SET p_tenant_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateTenant(IN p_tenant_id INT, IN p_tenant_name VARCHAR(100), IN p_contact_person VARCHAR(500), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE tenant
    SET tenant_name = p_tenant_name,
    tenant_name = p_tenant_name,
    contact_person = p_contact_person,
    address = p_address,
    city_id = p_city_id,
    phone = p_phone,
    mobile = p_mobile,
    telephone = p_telephone,
    email = p_email,
    last_log_by = p_last_log_by
    WHERE tenant_id = p_tenant_id;
END //

CREATE PROCEDURE deleteTenant(IN p_tenant_id INT)
BEGIN
	DELETE FROM tenant
    WHERE tenant_id = p_tenant_id;
END //

CREATE PROCEDURE getTenant(IN p_tenant_id INT)
BEGIN
	SELECT * FROM tenant
    WHERE tenant_id = p_tenant_id;
END //

CREATE PROCEDURE generateTenantTable()
BEGIN
   SELECT tenant_id, tenant_name, address, city_id FROM tenant;
END //

CREATE PROCEDURE generateTenantOptions()
BEGIN
	SELECT tenant_id, tenant_name FROM tenant
	ORDER BY tenant_name;
END //

/* Property Table Stored Procedures */

CREATE PROCEDURE checkPropertyExist (IN p_property_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM property
    WHERE property_id = p_property_id;
END //

CREATE PROCEDURE insertProperty(IN p_property_name VARCHAR(100), IN p_company_id INT, IN p_address VARCHAR(1000), IN p_city_id INT, IN p_last_log_by INT, OUT p_property_id INT)
BEGIN
    INSERT INTO property (property_name, company_id, address, city_id, last_log_by) 
	VALUES(p_property_name, p_company_id, p_address, p_city_id, p_last_log_by);
	
    SET p_property_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateProperty(IN p_property_id INT, IN p_property_name VARCHAR(100), IN p_company_id INT, IN p_address VARCHAR(1000), IN p_city_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE property
    SET property_name = p_property_name,
    property_name = p_property_name,
    company_id = p_company_id,
    address = p_address,
    city_id = p_city_id,
    last_log_by = p_last_log_by
    WHERE property_id = p_property_id;
END //

CREATE PROCEDURE deleteProperty(IN p_property_id INT)
BEGIN
	DELETE FROM property
    WHERE property_id = p_property_id;
END //

CREATE PROCEDURE getProperty(IN p_property_id INT)
BEGIN
	SELECT * FROM property
    WHERE property_id = p_property_id;
END //

CREATE PROCEDURE generatePropertyTable()
BEGIN
   SELECT property_id, property_name, address, city_id FROM property;
END //

CREATE PROCEDURE generatePropertyOptions()
BEGIN
	SELECT property_id, property_name FROM property
	ORDER BY property_name;
END //

/* Leasing Application Table Stored Procedures */

CREATE PROCEDURE checkLeasingApplicationExist (IN p_leasing_application_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM leasing_application
    WHERE leasing_application_id = p_leasing_application_id;
END //

CREATE PROCEDURE insertLeasingApplication(IN p_leasing_application_number VARCHAR(100), IN p_tenant_id INT, IN p_property_id INT, IN p_term_length INT, IN p_term_type VARCHAR(20), IN p_payment_frequency VARCHAR(20), IN p_vat VARCHAR(5), IN p_witholding_tax VARCHAR(5), IN p_renewal_tag VARCHAR(10), IN p_contract_date DATE, IN p_start_date DATE, IN p_maturity_date DATE, IN p_security_deposit DOUBLE, IN p_floor_area DOUBLE, IN p_initial_basic_rental DOUBLE, IN p_escalation_rate DOUBLE, IN p_remarks VARCHAR(500), IN p_last_log_by INT, OUT p_leasing_application_id INT)
BEGIN
    INSERT INTO leasing_application (leasing_application_number, tenant_id, property_id, term_length, term_type, payment_frequency, vat, witholding_tax, renewal_tag, contract_date, start_date, maturity_date, security_deposit, floor_area, initial_basic_rental, escalation_rate, remarks, last_log_by) 
	VALUES(p_leasing_application_number, p_tenant_id, p_property_id, p_term_length, p_term_type, p_payment_frequency, p_vat, p_witholding_tax, p_renewal_tag, p_contract_date, p_start_date, p_maturity_date, p_security_deposit, p_floor_area, p_initial_basic_rental, p_escalation_rate, p_remarks, p_last_log_by);
	
    SET p_leasing_application_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateLeasingApplication(IN p_leasing_application_id INT, IN p_tenant_id INT, IN p_property_id INT, IN p_term_length INT, IN p_term_type VARCHAR(20), IN p_payment_frequency VARCHAR(20), IN p_vat VARCHAR(5), IN p_witholding_tax VARCHAR(5), IN p_renewal_tag VARCHAR(10), IN p_contract_date DATE, IN p_start_date DATE, IN p_maturity_date DATE, IN p_security_deposit DOUBLE, IN p_floor_area DOUBLE, IN p_initial_basic_rental DOUBLE, IN p_escalation_rate DOUBLE, IN p_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE leasing_application
    SET tenant_id = p_tenant_id,
    property_id = p_property_id,
    term_length = p_term_length,
    term_type = p_term_type,
    payment_frequency = p_payment_frequency,
    vat = p_vat,
    witholding_tax = p_witholding_tax,
    renewal_tag = p_renewal_tag,
    contract_date = p_contract_date,
    start_date = p_start_date,
    maturity_date = p_maturity_date,
    security_deposit = p_security_deposit,
    floor_area = p_floor_area,
    initial_basic_rental = p_initial_basic_rental,
    escalation_rate = p_escalation_rate,
    remarks = p_remarks,
    last_log_by = p_last_log_by
    WHERE leasing_application_id = p_leasing_application_id;
END //

CREATE PROCEDURE insertLeasingApplicationRepayment(IN p_leasing_application_id INT, IN p_reference VARCHAR(200), IN p_due_date DATE, IN p_unpaid_rental DOUBLE, IN p_outstanding_balance DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO leasing_application_repayment (leasing_application_id, reference, due_date, unpaid_rental, outstanding_balance, last_log_by) 
	VALUES(p_leasing_application_id, p_reference, p_due_date, p_unpaid_rental, p_outstanding_balance, p_last_log_by);
END //

CREATE PROCEDURE deleteLeasingApplication(IN p_leasing_application_id INT)
BEGIN
	DELETE FROM leasing_application
    WHERE leasing_application_id = p_leasing_application_id;
END //

CREATE PROCEDURE deleteLeasingApplicationRepayment(IN p_leasing_application_id INT)
BEGIN
	DELETE FROM leasing_application_repayment
    WHERE leasing_application_id = p_leasing_application_id;
END //

CREATE PROCEDURE getLeasingApplication(IN p_leasing_application_id INT)
BEGIN
	SELECT * FROM leasing_application
    WHERE leasing_application_id = p_leasing_application_id;
END //

CREATE PROCEDURE getLeasingApplicationRepaymentCount(IN p_leasing_application_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM leasing_application_repayment
    WHERE leasing_application_id = p_leasing_application_id;
END //

CREATE PROCEDURE generateLeasingApplicationTable()
BEGIN
   SELECT leasing_application_id, leasing_application_number, tenant_id, property_id, application_status FROM leasing_application;
END //

CREATE PROCEDURE generateClosedLeasingApplicationTable()
BEGIN
    SELECT * FROM leasing_application WHERE application_status = 'Closed';
END //

CREATE PROCEDURE updateLeasingApplicationContactImage(IN p_leasing_application_id INT, IN p_contract_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE leasing_application
        SET contract_image = p_contract_image,
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
END //

CREATE PROCEDURE updateLeasingApplicationStatus(IN p_leasing_application_id INT, IN p_changed_by INT, IN p_application_status VARCHAR(50), IN p_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
    IF p_application_status = 'For Approval' THEN
        UPDATE leasing_application
        SET application_status = p_application_status,
        for_approval_date = NOW(),
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    ELSEIF p_application_status = 'Rejected' THEN
        UPDATE leasing_application
        SET application_status = p_application_status,
        rejection_date = NOW(),
        rejection_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    ELSEIF p_application_status = 'Cancelled' THEN
        UPDATE leasing_application
        SET application_status = p_application_status,
        cancellation_date = NOW(),
        cancellation_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    ELSEIF p_application_status = 'Closed' THEN
        UPDATE leasing_application
        SET application_status = p_application_status,
        closed_date = NOW(),
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    ELSEIF p_application_status = 'Approved' THEN
        UPDATE leasing_application
        SET application_status = p_application_status,
        approval_date = NOW(),
        approval_by = p_changed_by,
        approval_remarks = p_remarks,
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    ELSEIF p_application_status = 'Active' THEN
        UPDATE leasing_application
        SET application_status = p_application_status,
        activation_date = NOW(),
        activated_by = p_changed_by,
        activation_remarks = p_remarks,
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    ELSEIF p_application_status = 'Draft' THEN
        UPDATE leasing_application
        SET application_status = p_application_status,
        set_to_draft_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    ELSE
        UPDATE leasing_application
        SET application_status = p_application_status,
        last_log_by = p_last_log_by
        WHERE leasing_application_id = p_leasing_application_id;
    END IF;
END //

CREATE PROCEDURE updateInstallmentStatus()
BEGIN
   UPDATE leasing_other_charges
    SET payment_status = 
    CASE 
        WHEN outstanding_balance <= 0 THEN 'Fully Paid'
        WHEN outstanding_balance != 0 AND (due_amount > 0 AND due_paid > 0) THEN 'Partially Paid'
        ELSE 'Unpaid'
    END;
END //

CREATE PROCEDURE updateLeasingApplicationRepaymentStatus()
BEGIN
    UPDATE leasing_application_repayment
    SET repayment_status = 
        CASE 
            WHEN outstanding_balance <= 0 THEN 'Fully Paid'
            WHEN outstanding_balance = unpaid_rental + unpaid_electricity + unpaid_water + unpaid_other_charges AND (paid_rental > 0 OR paid_electricity > 0 OR paid_water > 0 OR paid_other_charges > 0) THEN 'Partially Paid'
            ELSE 'Unpaid'
        END;
END //

CREATE PROCEDURE updateLeasingApplicationStatusToClosed()
BEGIN
     UPDATE leasing_application
    SET application_status = 'Closed',
        closed_date = CURRENT_TIMESTAMP
    WHERE leasing_application_id IN (
        SELECT lar.leasing_application_id
        FROM leasing_application_repayment lar
        WHERE NOT EXISTS (
            SELECT 1
            FROM leasing_application_repayment lar2
            WHERE lar2.leasing_application_id = lar.leasing_application_id
            AND (lar2.repayment_status <> 'Fully Paid' OR lar2.outstanding_balance <> 0)
        )
    );
END //

CREATE PROCEDURE getLeasingAplicationRepaymentTotal(IN p_leasing_application_id INT, IN p_as_of_date DATE, IN p_transcation_type VARCHAR(100))
BEGIN
    IF p_transcation_type = 'Unpaid Rental' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(unpaid_rental) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(unpaid_rental) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSEIF p_transcation_type = 'Unpaid Electricity' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(unpaid_electricity) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(unpaid_electricity) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSEIF p_transcation_type = 'Unpaid Water' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(unpaid_water) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(unpaid_water) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSEIF p_transcation_type = 'Unpaid Other Charges' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(unpaid_other_charges) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(unpaid_other_charges) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSEIF p_transcation_type = 'Paid Rental' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(paid_rental) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(paid_rental) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSEIF p_transcation_type = 'Paid Electricity' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(paid_electricity) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(paid_electricity) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSEIF p_transcation_type = 'Paid Water' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(paid_water) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(paid_water) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSEIF p_transcation_type = 'Paid Other Charges' THEN
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(paid_other_charges) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(paid_other_charges) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    ELSE
        IF p_as_of_date IS NOT NULL AND p_as_of_date <> '' THEN
            SELECT SUM(outstanding_balance) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id AND due_date <= p_as_of_date;
        ELSE
            SELECT SUM(outstanding_balance) as total FROM leasing_application_repayment
            WHERE leasing_application_id = p_leasing_application_id;
        END IF;
    END IF;
END //

CREATE PROCEDURE generateLeasingRepaymentTable(IN p_leasing_application_id INT)
BEGIN
   SELECT * FROM leasing_application_repayment WHERE leasing_application_id = p_leasing_application_id ;
END //

CREATE PROCEDURE generateLeasingRepaymentOtherChargesTable(IN p_leasing_application_id INT)
BEGIN
   SELECT * FROM leasing_other_charges WHERE leasing_application_id = p_leasing_application_id ;
END //

CREATE PROCEDURE generateLeasingRepaymentCollectionsTable(IN p_leasing_application_id INT)
BEGIN
   SELECT * FROM leasing_collections WHERE leasing_application_id = p_leasing_application_id ;
END //

CREATE PROCEDURE checkLeasingOtherChargesExist (IN p_leasing_other_charges_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM leasing_other_charges
    WHERE leasing_other_charges_id = p_leasing_other_charges_id;
END //

CREATE PROCEDURE insertLeasingOtherCharges(IN p_leasing_application_repayment_id INT, IN p_leasing_application_id INT, IN p_other_charges_type VARCHAR(50), IN p_due_amount DOUBLE, IN p_due_paid DOUBLE, IN p_due_date DATE, IN p_coverage_start_date DATE, IN p_coverage_end_date DATE, IN p_outstanding_balance DOUBLE, IN p_reference_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

     IF p_other_charges_type = 'Electricity' THEN
        UPDATE leasing_application_repayment
        SET unpaid_electricity = (unpaid_electricity + p_due_amount),
        outstanding_balance = (outstanding_balance + p_due_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
    ELSEIF p_other_charges_type = 'Water' THEN
        UPDATE leasing_application_repayment
        SET unpaid_water = (unpaid_water + p_due_amount),
        outstanding_balance = (outstanding_balance + p_due_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
    ELSE
        UPDATE leasing_application_repayment
        SET unpaid_other_charges = (unpaid_other_charges + p_due_amount),
        outstanding_balance = (outstanding_balance + p_due_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
    END IF;

	INSERT INTO leasing_other_charges (leasing_application_repayment_id, leasing_application_id, other_charges_type, due_amount, due_paid, due_date, coverage_start_date, coverage_end_date, outstanding_balance, reference_number, last_log_by) 
	VALUES(p_leasing_application_repayment_id, p_leasing_application_id, p_other_charges_type, p_due_amount, p_due_paid, p_due_date, p_coverage_start_date, p_coverage_end_date, p_outstanding_balance, p_reference_number, p_last_log_by);

    COMMIT;
END //

CREATE PROCEDURE deleteLeasingOtherCharges(IN p_leasing_other_charges_id INT, IN p_leasing_application_repayment_id INT, IN p_other_charges_type VARCHAR(50), IN p_due_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    IF p_other_charges_type = 'Electricity' THEN
        UPDATE leasing_application_repayment
        SET unpaid_electricity = (unpaid_electricity - p_due_amount),
        outstanding_balance = (outstanding_balance - p_due_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
    ELSEIF p_other_charges_type = 'Water' THEN
        UPDATE leasing_application_repayment
        SET unpaid_water = (unpaid_water - p_due_amount),
        outstanding_balance = (outstanding_balance - p_due_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
    ELSE
        UPDATE leasing_application_repayment
        SET unpaid_other_charges = (unpaid_other_charges - p_due_amount),
        outstanding_balance = (outstanding_balance - p_due_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
    END IF;

	DELETE FROM leasing_other_charges
    WHERE leasing_other_charges_id = p_leasing_other_charges_id;

    COMMIT;
END //

CREATE PROCEDURE checkLeasingCollectionExist (IN p_leasing_collections_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM leasing_collections
    WHERE leasing_collections_id = p_leasing_collections_id;
END //

CREATE PROCEDURE insertLeasingRentalPayment(IN p_leasing_application_repayment_id INT, IN p_leasing_application_id INT, IN p_payment_for VARCHAR(50), IN p_payment_id INT, IN p_reference_number VARCHAR(500), IN p_payment_mode VARCHAR(50), IN p_payment_date DATE, IN p_payment_amount DOUBLE, IN p_last_log_by INT, OUT p_leasing_collections_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    UPDATE leasing_application_repayment
    SET unpaid_rental = (unpaid_rental - p_payment_amount),
    paid_rental = (paid_rental + p_payment_amount),
    outstanding_balance = (outstanding_balance - p_payment_amount),
    last_log_by = p_last_log_by
    WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;

	INSERT INTO leasing_collections (leasing_application_repayment_id, leasing_application_id, payment_for, payment_id, reference_number, payment_mode, payment_date, payment_amount, last_log_by) 
	VALUES(p_leasing_application_repayment_id, p_leasing_application_id, p_payment_for, p_payment_id, p_reference_number, p_payment_mode, p_payment_date, p_payment_amount, p_last_log_by);

    SET p_leasing_collections_id = LAST_INSERT_ID();

    COMMIT;
END //

CREATE PROCEDURE insertLeasingOtherChargesPayment(IN p_leasing_application_repayment_id INT, IN p_leasing_application_id INT, IN p_payment_for VARCHAR(50), IN p_payment_id INT, IN p_reference_number VARCHAR(500), IN p_payment_mode VARCHAR(50), IN p_payment_date DATE, IN p_payment_amount DOUBLE, IN p_last_log_by INT, OUT p_leasing_collections_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    IF p_payment_for = 'Electricity' THEN
        UPDATE leasing_application_repayment
        SET unpaid_electricity = (unpaid_electricity - p_payment_amount),
        paid_electricity = (paid_electricity + p_payment_amount),
        outstanding_balance = (outstanding_balance - p_payment_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;

        UPDATE leasing_other_charges
        SET due_amount = (due_amount - p_payment_amount),
        due_paid = (due_paid + p_payment_amount),
        outstanding_balance = (outstanding_balance - p_payment_amount)
        WHERE leasing_other_charges_id = p_payment_id;
    ELSEIF p_payment_for = 'Water' THEN
        UPDATE leasing_application_repayment
        SET unpaid_water = (unpaid_water - p_payment_amount),
        paid_water = (paid_water + p_payment_amount),
        outstanding_balance = (outstanding_balance - p_payment_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;

        UPDATE leasing_other_charges
        SET due_amount = (due_amount - p_payment_amount),
        due_paid = (due_paid + p_payment_amount),
        outstanding_balance = (outstanding_balance - p_payment_amount)
        WHERE leasing_other_charges_id = p_payment_id;
    ELSE
        UPDATE leasing_application_repayment
        SET unpaid_other_charges = (unpaid_other_charges - p_payment_amount),
        paid_other_charges = (paid_other_charges + p_payment_amount),
        outstanding_balance = (outstanding_balance - p_payment_amount),
        last_log_by = p_last_log_by
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;

        UPDATE leasing_other_charges
        SET due_amount = (due_amount - p_payment_amount),
        due_paid = (due_paid + p_payment_amount),
        outstanding_balance = (outstanding_balance - p_payment_amount)
        WHERE leasing_other_charges_id = p_payment_id;
    END IF;

	INSERT INTO leasing_collections (leasing_application_repayment_id, leasing_application_id, payment_for, payment_id, reference_number, payment_mode, payment_date, payment_amount, last_log_by) 
	VALUES(p_leasing_application_repayment_id, p_leasing_application_id, p_payment_for, p_payment_id, p_reference_number, p_payment_mode, p_payment_date, p_payment_amount, p_last_log_by);

    SET p_leasing_collections_id = LAST_INSERT_ID();

    COMMIT;
END //

CREATE PROCEDURE getLeasingCollections(IN p_leasing_collections_id INT)
BEGIN
	SELECT * FROM leasing_collections
    WHERE leasing_collections_id = p_leasing_collections_id;
END //

CREATE PROCEDURE getLeasingOtherCharges(IN p_leasing_other_charges_id INT)
BEGIN
	SELECT * FROM leasing_other_charges
    WHERE leasing_other_charges_id = p_leasing_other_charges_id;
END //

CREATE PROCEDURE getAllLeasingOtherCharges(IN p_leasing_application_repayment_id INT)
BEGIN
	SELECT * FROM leasing_other_charges
    WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
END //

CREATE PROCEDURE getLeasingApplicationRepayment(IN p_leasing_application_repayment_id INT)
BEGIN
	SELECT * FROM leasing_application_repayment
    WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
END //

CREATE PROCEDURE deleteLeasingCollections(IN p_leasing_collections_id INT, IN p_leasing_application_repayment_id INT, IN p_payment_for VARCHAR(50), IN p_payment_id INT, IN p_payment_amount DOUBLE)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    IF p_payment_for = 'Rent' THEN
        UPDATE leasing_application_repayment
        SET unpaid_rental = (unpaid_rental + p_payment_amount),
        paid_rental = (paid_rental - p_payment_amount),
        outstanding_balance = (outstanding_balance + p_payment_amount)
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;
    ELSEIF p_payment_for = 'Electricity' THEN
        UPDATE leasing_application_repayment
        SET unpaid_electricity = (unpaid_electricity + p_payment_amount),
        paid_electricity = (paid_electricity - p_payment_amount),
        outstanding_balance = (outstanding_balance + p_payment_amount)
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;

        UPDATE leasing_other_charges
        SET due_amount = (due_amount + p_payment_amount),
        due_paid = (due_paid - p_payment_amount),
        outstanding_balance = (outstanding_balance + p_payment_amount)
        WHERE leasing_other_charges_id = p_payment_id;
    ELSEIF p_payment_for = 'Water' THEN
        UPDATE leasing_application_repayment
        SET unpaid_water = (unpaid_water + p_payment_amount),
        paid_water = (paid_water - p_payment_amount),
        outstanding_balance = (outstanding_balance + p_payment_amount)
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;

        UPDATE leasing_other_charges
        SET due_amount = (due_amount + p_payment_amount),
        due_paid = (due_paid - p_payment_amount),
        outstanding_balance = (outstanding_balance + p_payment_amount)
        WHERE leasing_other_charges_id = p_payment_id;
    ELSE
        UPDATE leasing_application_repayment
        SET unpaid_other_charges = (unpaid_other_charges + p_payment_amount),
        paid_other_charges = (paid_other_charges - p_payment_amount),
        outstanding_balance = (outstanding_balance + p_payment_amount)
        WHERE leasing_application_repayment_id = p_leasing_application_repayment_id;

        UPDATE leasing_other_charges
        SET due_amount = (due_amount + p_payment_amount),
        due_paid = (due_paid - p_payment_amount),
        outstanding_balance = (outstanding_balance + p_payment_amount)
        WHERE leasing_other_charges_id = p_payment_id;
    END IF;

	DELETE FROM leasing_collections
    WHERE leasing_collections_id = p_leasing_collections_id;

    COMMIT;
END //

/* Contact Directory Table Stored Procedures */

CREATE PROCEDURE checkContactDirectoryExist (IN p_contact_directory_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contact_directory
    WHERE contact_directory_id = p_contact_directory_id;
END //

CREATE PROCEDURE insertContactDirectory(IN p_contact_name VARCHAR(200), IN p_position VARCHAR(200), IN p_location VARCHAR(200), IN p_directory_type VARCHAR(200), IN p_contact_information VARCHAR(500), IN p_last_log_by INT, OUT p_contact_directory_id INT)
BEGIN
    INSERT INTO contact_directory (contact_name, position, location, directory_type, contact_information, last_log_by) 
	VALUES(p_contact_name, p_position, p_location, p_directory_type, p_contact_information, p_last_log_by);
	
    SET p_contact_directory_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateContactDirectory(IN p_contact_directory_id INT, IN p_contact_name VARCHAR(200), IN p_position VARCHAR(200), IN p_location VARCHAR(200), IN p_directory_type VARCHAR(200), IN p_contact_information VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE contact_directory
    SET contact_name = p_contact_name,
    position = p_position,
    location = p_location,
    directory_type = p_directory_type,
    contact_information = p_contact_information,
    last_log_by = p_last_log_by
    WHERE contact_directory_id = p_contact_directory_id;
END //

CREATE PROCEDURE deleteContactDirectory(IN p_contact_directory_id INT)
BEGIN
    DELETE FROM contact_directory WHERE contact_directory_id = p_contact_directory_id;
END //

CREATE PROCEDURE getContactDirectory(IN p_contact_directory_id INT)
BEGIN
	SELECT * FROM contact_directory
    WHERE contact_directory_id = p_contact_directory_id;
END //

CREATE PROCEDURE generateContactDirectoryTable()
BEGIN
    SELECT *
    FROM contact_directory
    ORDER BY contact_directory_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Internal DR Table Stored Procedures */

CREATE PROCEDURE checkInternalDRExist (IN p_internal_dr_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM internal_dr
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE insertInternalDR(IN p_release_to VARCHAR(1000), IN p_release_mobile VARCHAR(50), IN p_release_address VARCHAR(1000), IN p_dr_number VARCHAR(50), IN p_dr_type VARCHAR(50), IN p_backjob_monitoring_id INT, IN p_stock_number VARCHAR(100), IN p_product_description VARCHAR(1000), IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_last_log_by INT, OUT p_internal_dr_id INT)
BEGIN
    INSERT INTO internal_dr (release_to, release_mobile, release_address, dr_number, dr_type, backjob_monitoring_id, stock_number, product_description, engine_number, chassis_number, plate_number, last_log_by) 
	VALUES(p_release_to, p_release_mobile, p_release_address, p_dr_number, p_dr_type, p_backjob_monitoring_id, p_stock_number, p_product_description, p_engine_number, p_chassis_number, p_plate_number, p_last_log_by);
	
    SET p_internal_dr_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateInternalDR(IN p_internal_dr_id INT, IN p_release_to VARCHAR(1000), IN p_release_mobile VARCHAR(50), IN p_release_address VARCHAR(1000), IN p_dr_number VARCHAR(50), IN p_dr_type VARCHAR(50), IN p_backjob_monitoring_id INT, IN p_stock_number VARCHAR(100), IN p_product_description VARCHAR(1000), IN p_engine_number VARCHAR(100), IN p_chassis_number VARCHAR(100), IN p_plate_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE internal_dr
    SET release_to = p_release_to,
    release_mobile = p_release_mobile,
    release_address = p_release_address,
    dr_number = p_dr_number,
    dr_type = p_dr_type,
    backjob_monitoring_id = p_backjob_monitoring_id,
    stock_number = p_stock_number,
    product_description = p_product_description,
    engine_number = p_engine_number,
    chassis_number = p_chassis_number,
    plate_number = p_plate_number,
    last_log_by = p_last_log_by
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDRAsReleased(IN p_internal_dr_id INT, IN p_dr_status VARCHAR(50), IN p_released_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE internal_dr
    SET dr_status = p_dr_status,
    released_remarks = p_released_remarks,
    release_date = NOW(),
    last_log_by = p_last_log_by
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDRAsCancelled(IN p_internal_dr_id INT, IN p_dr_status VARCHAR(50), IN p_cancellation_reason VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE internal_dr
    SET dr_status = p_dr_status,
    cancellation_reason = p_cancellation_reason,
    cancellation_date = NOW(),
    last_log_by = p_last_log_by
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDRAsOnProcess(IN p_internal_dr_id INT, IN p_dr_status VARCHAR(50), IN p_last_log_by INT)
BEGIN
	UPDATE internal_dr
    SET dr_status = p_dr_status,
    on_process_date = NOW(),
    last_log_by = p_last_log_by
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDRAsReadyForRelease(IN p_internal_dr_id INT, IN p_dr_status VARCHAR(50), IN p_last_log_by INT)
BEGIN
	UPDATE internal_dr
    SET dr_status = p_dr_status,
    ready_for_release_date = NOW(),
    last_log_by = p_last_log_by
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDRAsForDR(IN p_internal_dr_id INT, IN p_dr_status VARCHAR(50), IN p_last_log_by INT)
BEGIN
	UPDATE internal_dr
    SET dr_status = p_dr_status,
    for_dr_date = NOW(),
    last_log_by = p_last_log_by
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDRUnitImage(IN p_internal_dr_id INT, IN p_unit_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE internal_dr
        SET unit_image = p_unit_image,
        last_log_by = p_last_log_by
        WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDROutgoingChecklist(IN p_internal_dr_id INT, IN p_outgoing_checklist VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE internal_dr
        SET outgoing_checklist = p_outgoing_checklist,
        last_log_by = p_last_log_by
        WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE updateInternalDRQualityControlForm(IN p_internal_dr_id INT, IN p_quality_control_form VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE internal_dr
        SET quality_control_form = p_quality_control_form,
        last_log_by = p_last_log_by
        WHERE internal_dr_id = p_internal_dr_id;
END //

DELIMITER //

CREATE PROCEDURE updateSalesProposalBackjobProgress(
    IN p_backjob_monitoring_id INT, 
    IN p_last_log_by INT
)
BEGIN
    -- Update sales_proposal_job_order table
    UPDATE sales_proposal_job_order
    SET progress = 100,
        last_log_by = p_last_log_by
    WHERE sales_proposal_id IN (
        SELECT sales_proposal_id FROM backjob_monitoring_job_order WHERE backjob_monitoring_id = p_backjob_monitoring_id
    ) AND backjob = 'Yes' AND progress < 100;

    -- Update sales_proposal_additional_job_order table
    UPDATE sales_proposal_additional_job_order
    SET progress = 100,
        last_log_by = p_last_log_by
    WHERE sales_proposal_id IN (
        SELECT sales_proposal_id FROM backjob_monitoring_additional_job_order WHERE backjob_monitoring_id = p_backjob_monitoring_id
    ) AND backjob = 'Yes' AND progress < 100;
END //

DELIMITER ;


CREATE PROCEDURE deleteInternalDR(IN p_internal_dr_id INT)
BEGIN
    DELETE FROM internal_dr WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE getInternalDR(IN p_internal_dr_id INT)
BEGIN
	SELECT * FROM internal_dr
    WHERE internal_dr_id = p_internal_dr_id;
END //

CREATE PROCEDURE generateInternalDRTable()
BEGIN
    SELECT *
    FROM internal_dr
    ORDER BY internal_dr_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Table Stored Procedures */

CREATE PROCEDURE checkLeaveTypeExist (IN p_leave_type_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM leave_type
    WHERE leave_type_id = p_leave_type_id;
END //

CREATE PROCEDURE insertLeaveType(IN p_leave_type_name VARCHAR(100), IN p_is_paid VARCHAR(10), IN p_last_log_by INT, OUT p_leave_type_id INT)
BEGIN
    INSERT INTO leave_type (leave_type_name, is_paid, last_log_by) 
	VALUES(p_leave_type_name, p_is_paid, p_last_log_by);
	
    SET p_leave_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateLeaveType(IN p_leave_type_id INT, IN p_leave_type_name VARCHAR(100), IN p_is_paid VARCHAR(10), IN p_last_log_by INT)
BEGIN
	UPDATE leave_type
    SET leave_type_name = p_leave_type_name,
    is_paid = p_is_paid,
    last_log_by = p_last_log_by
    WHERE leave_type_id = p_leave_type_id;
END //

CREATE PROCEDURE deleteLeaveType(IN p_leave_type_id INT)
BEGIN
    DELETE FROM leave_type WHERE leave_type_id = p_leave_type_id;
END //

CREATE PROCEDURE getLeaveType(IN p_leave_type_id INT)
BEGIN
	SELECT * FROM leave_type
    WHERE leave_type_id = p_leave_type_id;
END //

CREATE PROCEDURE duplicateLeaveType(IN p_leave_type_id INT, IN p_last_log_by INT, OUT p_new_leave_type_id INT)
BEGIN
    DECLARE p_leave_type_name VARCHAR(100);
    DECLARE p_is_paid VARCHAR(10);
    DECLARE p_expected_new_employees INT;
    
    SELECT leave_type_name, is_paid
    INTO p_leave_type_name, p_is_paid
    FROM leave_type 
    WHERE leave_type_id = p_leave_type_id;
    
    INSERT INTO leave_type (leave_type_name, is_paid, last_log_by) 
    VALUES(p_leave_type_name, p_is_paid, p_last_log_by);
    
    SET p_new_leave_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateLeaveTypeTable(IN p_filter_is_paid ENUM('Yes', 'No', 'All'))
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT leave_type_id, leave_type_name, is_paid FROM leave_type';
    
    SET conditionList = ' WHERE 1';

    IF p_filter_is_paid <> "" AND p_filter_is_paid <> "All" THEN
        SET conditionList = CONCAT(conditionList, ' AND is_paid = ', p_filter_is_paid);
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY leave_type_name;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateIncidentReport()
BEGIN
    select file_as, document_name, document_file, upload_date from contact_document
    left join personal_information on personal_information.contact_id = contact_document.contact_id
    where document_type = 'Incident Report';
END //

CREATE PROCEDURE generateLeaveTypeOptions()
BEGIN
	SELECT leave_type_id, leave_type_name FROM leave_type
	ORDER BY leave_type_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE PROCEDURE deletePartsInquiryTable()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM parts_inquiry;

    ALTER TABLE parts_inquiry AUTO_INCREMENT = 1;

    COMMIT;
END //

CREATE PROCEDURE checkPartsInquiryExist (IN p_parts_inquiry_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM parts_inquiry
    WHERE parts_inquiry_id = p_parts_inquiry_id;
END //

CREATE PROCEDURE checkPartsNumberExist (IN p_parts_number VARCHAR(500))
BEGIN
	SELECT COUNT(*) AS total
    FROM parts_inquiry
    WHERE parts_number = p_parts_number;
END //

CREATE PROCEDURE insertPartsInquiry(IN p_parts_number VARCHAR(500), IN p_parts_description VARCHAR(1000), IN p_stock DOUBLE, IN p_price DOUBLE, IN p_last_log_by INT, OUT p_parts_inquiry_id INT)
BEGIN
    INSERT INTO parts_inquiry (parts_number, parts_description, stock, price, last_log_by) 
	VALUES(p_parts_number, p_parts_description, p_stock, p_price, p_last_log_by);

    SET p_parts_inquiry_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updatePartsInquiry(IN p_parts_inquiry_id INT, IN p_parts_number VARCHAR(500), IN p_parts_description VARCHAR(1000), IN p_stock DOUBLE, IN p_price DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE parts_inquiry
    SET parts_number = p_parts_number,
    parts_description = p_parts_description,
    stock = p_stock,
    price = p_price,
    last_log_by = p_last_log_by
    WHERE parts_inquiry_id = p_parts_inquiry_id;
END //

CREATE PROCEDURE updatePartsInquiryImport(IN p_parts_inquiry_id INT, IN p_parts_number VARCHAR(500), IN p_parts_description VARCHAR(1000), IN p_stock DOUBLE, IN p_price DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE parts_inquiry
    SET parts_number = p_parts_number,
    parts_description = p_parts_description,
    stock = p_stock,
    price = p_price,
    last_log_by = p_last_log_by
    WHERE parts_inquiry_id = p_parts_inquiry_id;
END //

CREATE PROCEDURE updatePartsInquiryImport(IN p_parts_number VARCHAR(500), IN p_parts_description VARCHAR(1000), IN p_stock DOUBLE, IN p_price DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE parts_inquiry
    SET parts_number = p_parts_number,
    parts_description = p_parts_description,
    stock = p_stock,
    price = p_price,
    last_log_by = p_last_log_by
    WHERE parts_number = p_parts_number;
END //

CREATE PROCEDURE deletePartsInquiry(IN p_parts_inquiry_id INT)
BEGIN
    DELETE FROM parts_inquiry WHERE parts_inquiry_id = p_parts_inquiry_id;
END //

CREATE PROCEDURE getPartsInquiry(IN p_parts_inquiry_id INT)
BEGIN
	SELECT * FROM parts_inquiry
    WHERE parts_inquiry_id = p_parts_inquiry_id;
END //

CREATE PROCEDURE generatePartsInquiryTable()
BEGIN
   SELECT * FROM parts_inquiry;
END //



CREATE PROCEDURE checkLeaveEntitlementExist (IN p_leave_entitlement_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM leave_entitlement
    WHERE leave_entitlement_id = p_leave_entitlement_id;
END //

CREATE PROCEDURE insertLeaveEntitlement(IN p_contact_id INT, IN p_leave_type_id INT, IN p_entitlement_amount DOUBLE, IN p_remaining_entitlement DOUBLE, IN p_leave_period_start DATE, IN p_leave_period_end DATE, IN p_last_log_by INT, OUT p_leave_entitlement_id INT)
BEGIN
    INSERT INTO leave_entitlement (contact_id, leave_type_id, entitlement_amount, remaining_entitlement, leave_period_start, leave_period_end, last_log_by) 
	VALUES(p_contact_id, p_leave_type_id, p_entitlement_amount, p_remaining_entitlement, p_leave_period_start, p_leave_period_end, p_last_log_by);
	
    SET p_leave_entitlement_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateLeaveEntitlement(IN p_leave_entitlement_id INT, IN p_contact_id INT, IN p_leave_type_id INT, IN p_entitlement_amount DOUBLE, IN p_remaining_entitlement DOUBLE, IN p_leave_period_start DATE, IN p_leave_period_end DATE, IN p_last_log_by INT)
BEGIN
	UPDATE leave_entitlement
    SET contact_id = contact_id,
    leave_type_id = p_leave_type_id,
    entitlement_amount = p_entitlement_amount,
    remaining_entitlement = p_remaining_entitlement,
    leave_period_start = p_leave_period_start,
    leave_period_end = p_leave_period_end,
    last_log_by = p_last_log_by
    WHERE leave_entitlement_id = p_leave_entitlement_id;
END //

CREATE PROCEDURE deleteLeaveEntitlement(IN p_leave_entitlement_id INT)
BEGIN
    DELETE FROM leave_entitlement WHERE leave_entitlement_id = p_leave_entitlement_id;
END //

CREATE PROCEDURE getLeaveEntitlement(IN p_leave_entitlement_id INT)
BEGIN
	SELECT * FROM leave_entitlement
    WHERE leave_entitlement_id = p_leave_entitlement_id;
END //

CREATE PROCEDURE generateLeaveEntitlementTable()
BEGIN
    SELECT * FROM leave_entitlement;
END //





CREATE PROCEDURE checkLeaveApplicationExist (IN p_leave_application_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM leave_application
    WHERE leave_application_id = p_leave_application_id;
END //

CREATE PROCEDURE insertLeaveApplication(IN p_contact_id INT, IN p_leave_type_id INT, IN p_reason VARCHAR(500), IN p_leave_date DATE, IN p_leave_start_time TIME, IN p_leave_end_time TIME, IN p_number_of_hours DOUBLE, IN p_last_log_by INT, OUT p_leave_application_id INT)
BEGIN
    INSERT INTO leave_application (contact_id, leave_type_id, reason, leave_date, leave_start_time, leave_end_time, number_of_hours, last_log_by) 
	VALUES(p_contact_id, p_leave_type_id, p_reason, p_leave_date, p_leave_start_time, p_leave_end_time, p_number_of_hours, p_last_log_by);
	
    SET p_leave_application_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateLeaveApplication(IN p_leave_application_id INT, IN p_contact_id INT, IN p_leave_type_id INT, IN p_reason VARCHAR(500), IN p_leave_date DATE, IN p_leave_start_time TIME, IN p_leave_end_time TIME, IN p_number_of_hours DOUBLE, IN p_last_log_by INT)
BEGIN
	UPDATE leave_application
    SET contact_id = contact_id,
    reason = p_reason,
    leave_date = p_leave_date,
    leave_start_time = p_leave_start_time,
    leave_end_time = p_leave_end_time,
    number_of_hours = p_number_of_hours,
    last_log_by = p_last_log_by
    WHERE leave_application_id = p_leave_application_id;
END //

CREATE PROCEDURE updateLeaveApplicationStatus(IN p_leave_application_id INT, IN p_status VARCHAR(50), IN p_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
    IF p_status = 'Approved' THEN
        UPDATE leave_application
        SET status = p_status,
        approval_date = NOW(),
        last_log_by = p_last_log_by
        WHERE leave_application_id = p_leave_application_id;
    ELSEIF p_status = 'Recommended' THEN
        UPDATE leave_application
        SET status = p_status,
        recommendation_date = NOW(),
        last_log_by = p_last_log_by
        WHERE leave_application_id = p_leave_application_id;
    ELSEIF p_status = 'For Recommendation' THEN
        UPDATE leave_application
        SET status = p_status,
        for_recommendation_date = NOW(),
        last_log_by = p_last_log_by
        WHERE leave_application_id = p_leave_application_id;
    ELSEIF p_status = 'Rejected' THEN
        UPDATE leave_application
        SET status = p_status,
        rejection_date = NOW(),
        rejection_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE leave_application_id = p_leave_application_id;
    ELSEIF p_status = 'Cancelled' THEN
        UPDATE leave_application
        SET status = p_status,
        cancellation_date = NOW(),
        cancellation_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE leave_application_id = p_leave_application_id;
    ELSE
        UPDATE leave_application
        SET status = p_status,
        for_approval_date = NOW(),
        last_log_by = p_last_log_by
        WHERE leave_application_id = p_leave_application_id;
    END IF;
END //

CREATE PROCEDURE deleteLeaveApplication(IN p_leave_application_id INT)
BEGIN
    DELETE FROM leave_application WHERE leave_application_id = p_leave_application_id;
END //

CREATE PROCEDURE getLeaveApplication(IN p_leave_application_id INT)
BEGIN
	SELECT * FROM leave_application
    WHERE leave_application_id = p_leave_application_id;
END //

CREATE PROCEDURE generateLeaveApplicationTable(IN p_contact_id INT)
BEGIN
    SELECT * FROM leave_application WHERE contact_id = p_contact_id;
END //

CREATE PROCEDURE generateLeaveApprovalTable()
BEGIN
    SELECT * FROM leave_application 
    WHERE status = 'For Approval';
END //

CREATE PROCEDURE generateLeaveDashboardApprovalTable(IN p_contact_id INT)
BEGIN
    SELECT * FROM leave_application 
    WHERE status = 'For Approval' OR (contact_id IN (SELECT contact_id FROM employment_information WHERE manager_id = p_contact_id) AND status = 'For Recommendation');
END //

CREATE PROCEDURE generateLeaveSummaryTable(IN p_leave_status VARCHAR(100), IN p_leave_start_date DATE, IN p_leave_end_date DATE, IN p_application_start_date DATE, IN p_application_end_date DATE, IN p_approval_start_date DATE, IN p_approval_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM leave_application';
    SET conditionList = ' WHERE 1';

     IF p_leave_status IS NOT NULL AND p_leave_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND status =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_leave_status));
    END IF;
    
    IF p_leave_start_date IS NOT NULL AND p_leave_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (leave_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_leave_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_leave_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_application_start_date IS NOT NULL AND p_application_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (DATE(application_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_application_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_application_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_approval_start_date IS NOT NULL AND p_approval_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (DATE(approval_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_approval_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_approval_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY application_date ASC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateLeaveRecommendationTable(IN p_contact_id INT)
BEGIN
    SELECT * FROM leave_application 
    WHERE status = 'For Recommendation';
END //

CREATE PROCEDURE generatePDCManagementTable(IN p_pdc_management_status VARCHAR(500), IN p_pdc_management_company VARCHAR(500), IN p_check_start_date DATE, IN p_check_end_date DATE, IN p_redeposit_start_date DATE, IN p_redeposit_end_date DATE, IN p_onhold_start_date DATE, IN p_onhold_end_date DATE, IN p_for_deposit_start_date DATE, IN p_for_deposit_end_date DATE, IN p_deposit_start_date DATE, IN p_deposit_end_date DATE, IN p_reversed_start_date DATE, IN p_reversed_end_date DATE, IN p_pulled_out_start_date DATE, IN p_pulled_out_end_date DATE, IN p_cancellation_start_date DATE, IN p_cancellation_end_date DATE, IN p_clear_start_date DATE, IN p_clear_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM loan_collections';
    SET conditionList = ' WHERE mode_of_payment = "Check"';
    
    IF p_check_start_date IS NOT NULL AND p_check_end_date IS NOT NULL AND p_redeposit_start_date IS NOT NULL AND p_redeposit_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (check_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_end_date));
        SET conditionList = CONCAT(conditionList, ' OR new_deposit_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_check_start_date IS NOT NULL AND p_check_end_date IS NOT NULL AND p_redeposit_start_date IS NULL AND p_redeposit_end_date IS NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (check_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_check_start_date IS NULL AND p_check_end_date IS NULL AND p_redeposit_start_date IS NOT NULL AND p_redeposit_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (new_deposit_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_redeposit_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_redeposit_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_onhold_start_date IS NOT NULL AND p_onhold_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (onhold_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_onhold_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_onhold_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_for_deposit_start_date IS NOT NULL AND p_for_deposit_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (for_deposit_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_for_deposit_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_for_deposit_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_deposit_start_date IS NOT NULL AND p_deposit_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (deposit_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_deposit_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_deposit_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_reversed_start_date IS NOT NULL AND p_reversed_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (reversal_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reversed_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reversed_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_pulled_out_start_date IS NOT NULL AND p_pulled_out_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (pulled_out_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_pulled_out_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_pulled_out_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_cancellation_start_date IS NOT NULL AND p_cancellation_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (cancellation_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_cancellation_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_cancellation_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_clear_start_date IS NOT NULL AND p_clear_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (clear_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_clear_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_clear_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_status IS NOT NULL AND p_pdc_management_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND collection_status IN (');
        SET conditionList = CONCAT(conditionList, p_pdc_management_status);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_company IS NOT NULL AND p_pdc_management_company <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_pdc_management_company);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY loan_number ASC, check_date ASC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateLoanExtractionTable(IN p_filter_released_date_start_date DATE, IN p_filter_released_date_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM sales_proposal';
    SET conditionList = " WHERE sales_proposal_status IN ('Released')";
    
    IF p_filter_released_date_start_date IS NOT NULL AND p_filter_released_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (DATE(released_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_released_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_released_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY released_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateJournalEntryTable(IN p_filter_journal_entry_date_start_date DATE, IN p_filter_journal_entry_date_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM journal_entry';
    SET conditionList = " WHERE ((debit > 0 AND credit = 0) OR (debit = 0 AND credit > 0))";
    
    IF p_filter_journal_entry_date_start_date IS NOT NULL AND p_filter_journal_entry_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (DATE(journal_entry_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_journal_entry_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_journal_entry_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY journal_entry_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateBorrowerExtractionTable(IN p_filter_released_date_start_date DATE, IN p_filter_released_date_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM contact';
    SET conditionList = "";
    
    IF p_filter_released_date_start_date IS NOT NULL AND p_filter_released_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' WHERE (DATE(created_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_released_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_released_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ';');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateCollectionReportTable(IN p_pdc_management_company VARCHAR(500), IN p_pdc_management_collection VARCHAR(5000), IN p_filter_transaction_date_start_date DATE, IN p_filter_transaction_date_end_date DATE, IN p_filter_payment_date_start_date DATE, IN p_filter_payment_date_end_date DATE, IN p_payment_advice VARCHAR(5))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM loan_collections';
    SET conditionList = ' WHERE 1';

    IF p_payment_advice IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND payment_advice = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_payment_advice));
    ELSE
        SET conditionList = CONCAT(conditionList, ' AND payment_advice IN ("Yes", "No")');
    END IF;
    
    IF p_filter_transaction_date_start_date IS NOT NULL AND p_filter_transaction_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transaction_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_transaction_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_transaction_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_filter_payment_date_start_date IS NOT NULL AND p_filter_payment_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (payment_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_payment_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_filter_payment_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_company IS NOT NULL AND p_pdc_management_company <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_pdc_management_company);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_collection IS NOT NULL AND p_pdc_management_collection <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND pdc_type IN (');
        SET conditionList = CONCAT(conditionList, p_pdc_management_collection);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY transaction_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateCollectionsTable(IN p_pdc_management_status VARCHAR(50), IN p_transaction_start_date DATE, IN p_transaction_end_date DATE, IN p_payment_start_date DATE, IN p_payment_end_date DATE, IN p_or_start_date DATE, IN p_or_end_date DATE, IN p_reversed_start_date DATE, IN p_reversed_end_date DATE, IN p_cancellation_start_date DATE, IN p_cancellation_end_date DATE, IN p_payment_advice VARCHAR(5), IN p_pdc_management_collection VARCHAR(5000))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM loan_collections';
    SET conditionList = ' WHERE mode_of_payment != "Check"';
    
    IF p_payment_advice IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND payment_advice = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_payment_advice));
    ELSE
        SET conditionList = CONCAT(conditionList, ' AND payment_advice IN ("Yes", "No")');
    END IF;
    
    IF p_transaction_start_date IS NOT NULL AND p_transaction_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transaction_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_or_start_date IS NOT NULL AND p_or_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (or_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_or_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_or_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_payment_start_date IS NOT NULL AND p_payment_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (payment_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_payment_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_payment_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_reversed_start_date IS NOT NULL AND p_reversed_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (reversal_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reversed_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reversed_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_cancellation_start_date IS NOT NULL AND p_cancellation_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (cancellation_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_cancellation_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_cancellation_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_status IS NOT NULL AND p_pdc_management_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND collection_status IN (');
        SET conditionList = CONCAT(conditionList, QUOTE(p_pdc_management_status));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_collection IS NOT NULL AND p_pdc_management_collection <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND pdc_type IN (');
        SET conditionList = CONCAT(conditionList, p_pdc_management_collection);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY loan_number ASC, payment_date ASC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generatePaymentAdviceTable(IN p_pdc_management_status VARCHAR(50), IN p_transaction_start_date DATE, IN p_transaction_end_date DATE, IN p_payment_start_date DATE, IN p_payment_end_date DATE, IN p_or_start_date DATE, IN p_or_end_date DATE, IN p_reversed_start_date DATE, IN p_reversed_end_date DATE, IN p_cancellation_start_date DATE, IN p_cancellation_end_date DATE, IN p_pdc_management_collection VARCHAR(5000))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM loan_collections';
    SET conditionList = ' WHERE mode_of_payment != "Check" AND payment_advice = "Yes"';
    
    IF p_transaction_start_date IS NOT NULL AND p_transaction_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transaction_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_or_start_date IS NOT NULL AND p_or_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (or_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_or_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_or_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_payment_start_date IS NOT NULL AND p_payment_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (payment_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_payment_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_payment_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_reversed_start_date IS NOT NULL AND p_reversed_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (reversal_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reversed_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reversed_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_cancellation_start_date IS NOT NULL AND p_cancellation_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (cancellation_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_cancellation_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_cancellation_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_status IS NOT NULL AND p_pdc_management_status <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND collection_status IN (');
        SET conditionList = CONCAT(conditionList, QUOTE(p_pdc_management_status));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_pdc_management_collection IS NOT NULL AND p_pdc_management_collection <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND pdc_type IN (');
        SET conditionList = CONCAT(conditionList, p_pdc_management_collection);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY loan_number ASC, payment_date ASC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generatePrintCollectionsTable(IN p_or_date DATE, IN p_company_id VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM loan_collections';
    SET conditionList = ' WHERE mode_of_payment != "Check" AND payment_advice = "No"';
    
    IF p_or_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (or_date = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_or_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_company_id IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_company_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY or_number ASC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generatePrintCollectionsCompanyTotal(IN p_or_date DATE, IN p_company_id VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT company_id, SUM(payment_amount) AS payment_amount FROM loan_collections';
    SET conditionList = ' WHERE mode_of_payment != "Check" AND payment_advice = "No"';
    
    IF p_or_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (or_date = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_or_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_company_id IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_company_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' GROUP BY company_id;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE getTotalCollectionsGreaterThanTodayByCompany(IN p_or_date DATE, IN p_company_id INT)
BEGIN
    SELECT company_id, SUM(payment_amount) AS payment_amount FROM loan_collections WHERE or_date > p_or_date AND company_id = p_company_id AND collection_status = 'Posted' AND mode_of_payment != "Check" GROUP BY company_id;
END //

CREATE PROCEDURE generatePrintDepositsTable( IN p_deposit_date DATE, IN p_company_id VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT deposit_amount, deposit_date, deposited_to, reference_number, transaction_date, remarks FROM deposits';
    SET conditionList = ' WHERE 1';
    
     IF p_deposit_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (deposit_date = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_deposit_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_company_id IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_company_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;


    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY deposit_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generatePrintDepositsCompanyTotal( IN p_deposit_date DATE, IN p_company_id VARCHAR(50))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT company_id, SUM(deposit_amount) AS deposit_amount FROM deposits';
    SET conditionList = ' WHERE 1';
    
     IF p_deposit_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (deposit_date = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_deposit_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_company_id IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND company_id IN (');
        SET conditionList = CONCAT(conditionList, p_company_id);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;


    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' GROUP BY company_id;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //



CREATE PROCEDURE deleteCollections(IN p_loan_collection_id INT)
BEGIN
    DELETE FROM loan_collections WHERE loan_collection_id = p_loan_collection_id;
END //



CREATE PROCEDURE cancelLoanCollectionClosed(IN p_sales_proposal_id INT, IN p_check_date DATE, IN p_account_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
     UPDATE loan_collections
        SET collection_status = 'Cancelled',
        cancellation_date = NOW(),
        cancellation_reason = 'Account Closed',
        last_log_by = p_last_log_by
        WHERE sales_proposal_id = p_sales_proposal_id AND check_date >= p_check_date AND account_number = p_account_number AND collection_status IN('Pending', 'Redeposit');
END //

CREATE PROCEDURE updateCollectionStatus(IN p_loan_collection_id INT, IN p_collection_status VARCHAR(50), IN p_reason VARCHAR(100), IN p_remarks VARCHAR(500), IN p_reference_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
    IF p_collection_status = 'Reversed' THEN
        INSERT INTO loan_collections_history (loan_collection_id, mode_of_payment, transaction_date, transaction_type, reference_number, reference_date, last_log_by) 
        VALUES(p_loan_collection_id, (SELECT mode_of_payment FROM loan_collections WHERE loan_collection_id = p_loan_collection_id), NOW(), 'Reversed', (SELECT or_number FROM loan_collections WHERE loan_collection_id = p_loan_collection_id), (SELECT or_date FROM loan_collections WHERE loan_collection_id = p_loan_collection_id), p_last_log_by);

        UPDATE loan_collections
        SET collection_status = p_collection_status,
        reversal_date = NOW(),
        reversal_reason = p_reason,
        reversal_remarks = p_remarks,
        reversal_reference_number = p_reference_number,
        reversal_reference_date = NOW(),
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Posted');
    ELSEIF p_collection_status = 'Cancelled' THEN
        INSERT INTO loan_collections_history (loan_collection_id, mode_of_payment, transaction_date, transaction_type, reference_number, reference_date, last_log_by) 
        VALUES(p_loan_collection_id, (SELECT mode_of_payment FROM loan_collections WHERE loan_collection_id = p_loan_collection_id), NOW(), 'Cancelled', (SELECT or_number FROM loan_collections WHERE loan_collection_id = p_loan_collection_id), (SELECT or_date FROM loan_collections WHERE loan_collection_id = p_loan_collection_id), p_last_log_by);

        UPDATE loan_collections
        SET collection_status = p_collection_status,
        cancellation_date = NOW(),
        cancellation_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Posted');
    ELSE
        UPDATE loan_collections
        SET collection_status = p_collection_status,
        pulled_out_date = NOW(),
        pulled_out_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Posted');
    END IF;
END //


CREATE PROCEDURE generateCustomerOptions(IN p_generate_type VARCHAR(50))
BEGIN
	IF p_generate_type = 'active customer' THEN
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1 AND contact_status = "Active") ORDER BY file_as;
    ELSE
        SELECT contact_id, file_as FROM personal_information WHERE contact_id IN (SELECT contact_id FROM contact WHERE is_customer = 1) ORDER BY file_as;
    END IF;
END //

CREATE PROCEDURE generateAllContactsOptions()
BEGIN
	SELECT contact_id, file_as FROM personal_information ORDER BY file_as;
END //

CREATE PROCEDURE insertImportPDC(IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_product_id INT, IN p_customer_id INT, IN p_pdc_type VARCHAR(20), IN p_payment_details VARCHAR(100), IN p_payment_amount DOUBLE, IN p_collection_status VARCHAR(50), IN p_check_date DATE, IN p_check_number VARCHAR(100), IN p_bank_branch VARCHAR(200), IN p_payment_date DATE, IN p_transaction_date DATE, IN p_onhold_date DATE, IN p_onhold_reason VARCHAR(500), IN p_deposit_date DATE, IN p_for_deposit_date DATE, IN p_redeposit_date DATE, IN p_new_deposit_date DATE, IN p_clear_date DATE, IN p_cancellation_date DATE, IN p_cancellation_reason VARCHAR(500), IN p_reversal_date DATE, IN p_pulled_out_date DATE, IN p_pulled_out_reason VARCHAR(500), IN p_reversal_reason VARCHAR(100), IN p_reversal_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

    INSERT INTO loan_collections (sales_proposal_id, loan_number, product_id, customer_id, pdc_type, mode_of_payment, payment_details, payment_amount, collection_status, check_date, check_number, bank_branch, payment_date, transaction_date, onhold_date, onhold_reason, deposit_date, for_deposit_date, redeposit_date, new_deposit_date, clear_date, cancellation_date, cancellation_reason, reversal_date, pulled_out_date, pulled_out_reason, reversal_reason, reversal_remarks, last_log_by) 
	VALUES(p_sales_proposal_id, p_loan_number, p_product_id, p_customer_id, p_pdc_type, 'Check', p_payment_details, p_payment_amount, p_collection_status, p_check_date, p_check_number, p_bank_branch, p_payment_date, p_transaction_date, p_onhold_date, p_onhold_reason, p_deposit_date, p_for_deposit_date, p_redeposit_date, p_new_deposit_date, p_clear_date, p_cancellation_date, p_cancellation_reason, p_reversal_date, p_pulled_out_date, p_pulled_out_reason, p_reversal_reason, p_reversal_remarks, p_last_log_by);
END //

CREATE PROCEDURE updateImportPDC(IN p_loan_collection_id INT, IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_product_id INT, IN p_customer_id INT, IN p_pdc_type VARCHAR(20), IN p_payment_details VARCHAR(100), IN p_payment_amount DOUBLE, IN p_collection_status VARCHAR(50), IN p_check_date DATE, IN p_check_number VARCHAR(100), IN p_bank_branch VARCHAR(200), IN p_payment_date DATE, IN p_transaction_date DATE, IN p_onhold_date DATE, IN p_onhold_reason VARCHAR(500), IN p_deposit_date DATE, IN p_for_deposit_date DATE, IN p_redeposit_date DATE, IN p_new_deposit_date DATE, IN p_clear_date DATE, IN p_cancellation_date DATE, IN p_cancellation_reason VARCHAR(500), IN p_reversal_date DATE, IN p_pulled_out_date DATE, IN p_pulled_out_reason VARCHAR(500), IN p_reversal_reason VARCHAR(100), IN p_reversal_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
	UPDATE loan_collections
    SET sales_proposal_id = p_sales_proposal_id, 
    loan_number = loan_number, 
    product_id = product_id, 
    customer_id =customer_id, 
    pdc_type = pdc_type, 
    mode_of_payment = mode_of_payment, 
    payment_details = payment_details, 
    payment_amount = payment_amount, 
    collection_status = collection_status, 
    check_date = check_date, 
    check_number = check_number, 
    bank_branch = bank_branch, 
    payment_date = payment_date, 
    transaction_date = transaction_date, 
    onhold_date = onhold_date, 
    onhold_reason = onhold_reason, 
    deposit_date = deposit_date, 
    for_deposit_date = for_deposit_date, 
    redeposit_date = redeposit_date, 
    new_deposit_date = new_deposit_date, 
    clear_date = clear_date, 
    cancellation_date = cancellation_date, 
    cancellation_reason = cancellation_reason, 
    reversal_date = reversal_date, 
    pulled_out_date = pulled_out_date, 
    pulled_out_reason = pulled_out_reason, 
    reversal_reason = reversal_reason, 
    reversal_remarks = reversal_remarks,
    last_log_by = p_last_log_by
    WHERE loan_collection_id = p_loan_collection_id;
END //

CREATE PROCEDURE insertPDCManualInputCollection(IN p_sales_proposal_id INT, IN p_loan_number VARCHAR(100), IN p_customer_id INT, IN p_company_id INT, IN p_last_log_by INT)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE p_bank_branch VARCHAR(200);
    DECLARE p_check_date DATE;
    DECLARE p_check_number INT;
    DECLARE p_payment_for VARCHAR(200);
    DECLARE p_gross_amount DOUBLE;
    DECLARE p_account_number VARCHAR(100);
    DECLARE cur1 CURSOR FOR 
        SELECT bank_branch, check_date, check_number, payment_for, gross_amount, account_number
        FROM sales_proposal_manual_pdc_input 
        WHERE sales_proposal_id = p_sales_proposal_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO p_bank_branch, p_check_date, p_check_number, p_payment_for, p_gross_amount, p_account_number;
        IF done THEN
          LEAVE read_loop;
        END IF;

        INSERT INTO loan_collections (sales_proposal_id, loan_number, customer_id, pdc_type, mode_of_payment, payment_details, payment_amount, check_number, check_date, payment_date, bank_branch, account_number, company_id, last_log_by) 
        VALUES(p_sales_proposal_id, p_loan_number, p_customer_id, 'Loan', 'Check', p_payment_for, p_gross_amount, p_check_number, p_check_date, p_check_date, p_bank_branch, p_account_number, p_company_id, p_last_log_by);
    END LOOP;

    CLOSE cur1;
END //

CREATE PROCEDURE duplicateCancelledPDC(IN p_loan_collection_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO loan_collections (
        sales_proposal_id, loan_number, product_id, customer_id, pdc_type, mode_of_payment, 
        payment_details, payment_amount, check_number, check_date, bank_branch, remarks, 
        account_number, company_id, last_log_by
    )
    SELECT 
        sales_proposal_id, loan_number, product_id, customer_id, pdc_type, 'Check', 
        payment_details, payment_amount, CONCAT('LACKING - ', check_number), check_date, 
        bank_branch, remarks, account_number, company_id, p_last_log_by
    FROM loan_collections 
    WHERE loan_collection_id = p_loan_collection_id and collection_status = 'Cancelled';
END //

CREATE PROCEDURE updateLoanCollectionOnHoldAttachment(IN p_loan_collection_id INT, IN p_onhold_attachment VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE loan_collections
        SET onhold_attachment = p_onhold_attachment,
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id;
END //







/* Body Type Table Stored Procedures */

CREATE PROCEDURE checkBrandExist (IN p_brand_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM brand
    WHERE brand_id = p_brand_id;
END //

CREATE PROCEDURE insertBrand(IN p_brand_name VARCHAR(100), IN p_last_log_by INT, OUT p_brand_id INT)
BEGIN
    INSERT INTO brand (brand_name, last_log_by) 
	VALUES(p_brand_name, p_last_log_by);
	
    SET p_brand_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBrand(IN p_brand_id INT, IN p_brand_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE brand
    SET brand_name = p_brand_name,
    last_log_by = p_last_log_by
    WHERE brand_id = p_brand_id;
END //

CREATE PROCEDURE deleteBrand(IN p_brand_id INT)
BEGIN
    DELETE FROM brand WHERE brand_id = p_brand_id;
END //

CREATE PROCEDURE getBrand(IN p_brand_id INT)
BEGIN
	SELECT * FROM brand
    WHERE brand_id = p_brand_id;
END //

CREATE PROCEDURE duplicateBrand(IN p_brand_id INT, IN p_last_log_by INT, OUT p_new_brand_id INT)
BEGIN
    DECLARE p_brand_name VARCHAR(100);
    
    SELECT brand_name
    INTO p_brand_name
    FROM brand 
    WHERE brand_id = p_brand_id;
    
    INSERT INTO brand (brand_name, last_log_by) 
    VALUES(p_brand_name, p_last_log_by);
    
    SET p_new_brand_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateBrandTable()
BEGIN
    SELECT brand_id, brand_name
    FROM brand
    ORDER BY brand_id;
END //

CREATE PROCEDURE generateBrandOptions()
BEGIN
	SELECT brand_id, brand_name FROM brand
	ORDER BY brand_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Body Type Table Stored Procedures */

CREATE PROCEDURE checkMakeExist (IN p_make_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM make
    WHERE make_id = p_make_id;
END //

CREATE PROCEDURE insertMake(IN p_make_name VARCHAR(100), IN p_last_log_by INT, OUT p_make_id INT)
BEGIN
    INSERT INTO make (make_name, last_log_by) 
	VALUES(p_make_name, p_last_log_by);
	
    SET p_make_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateMake(IN p_make_id INT, IN p_make_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE make
    SET make_name = p_make_name,
    last_log_by = p_last_log_by
    WHERE make_id = p_make_id;
END //

CREATE PROCEDURE deleteMake(IN p_make_id INT)
BEGIN
    DELETE FROM make WHERE make_id = p_make_id;
END //

CREATE PROCEDURE getMake(IN p_make_id INT)
BEGIN
	SELECT * FROM make
    WHERE make_id = p_make_id;
END //

CREATE PROCEDURE duplicateMake(IN p_make_id INT, IN p_last_log_by INT, OUT p_new_make_id INT)
BEGIN
    DECLARE p_make_name VARCHAR(100);
    
    SELECT make_name
    INTO p_make_name
    FROM make 
    WHERE make_id = p_make_id;
    
    INSERT INTO make (make_name, last_log_by) 
    VALUES(p_make_name, p_last_log_by);
    
    SET p_new_make_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateMakeTable()
BEGIN
    SELECT make_id, make_name
    FROM make
    ORDER BY make_id;
END //

CREATE PROCEDURE generateMakeOptions()
BEGIN
	SELECT make_id, make_name FROM make
	ORDER BY make_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Body Type Table Stored Procedures */

CREATE PROCEDURE checkModelExist (IN p_model_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM model
    WHERE model_id = p_model_id;
END //

CREATE PROCEDURE insertModel(IN p_model_name VARCHAR(100), IN p_last_log_by INT, OUT p_model_id INT)
BEGIN
    INSERT INTO model (model_name, last_log_by) 
	VALUES(p_model_name, p_last_log_by);
	
    SET p_model_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateModel(IN p_model_id INT, IN p_model_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE model
    SET model_name = p_model_name,
    last_log_by = p_last_log_by
    WHERE model_id = p_model_id;
END //

CREATE PROCEDURE deleteModel(IN p_model_id INT)
BEGIN
    DELETE FROM model WHERE model_id = p_model_id;
END //

CREATE PROCEDURE getModel(IN p_model_id INT)
BEGIN
	SELECT * FROM model
    WHERE model_id = p_model_id;
END //

CREATE PROCEDURE duplicateModel(IN p_model_id INT, IN p_last_log_by INT, OUT p_new_model_id INT)
BEGIN
    DECLARE p_model_name VARCHAR(100);
    
    SELECT model_name
    INTO p_model_name
    FROM model 
    WHERE model_id = p_model_id;
    
    INSERT INTO model (model_name, last_log_by) 
    VALUES(p_model_name, p_last_log_by);
    
    SET p_new_model_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateModelTable()
BEGIN
    SELECT model_id, model_name
    FROM model
    ORDER BY model_id;
END //

CREATE PROCEDURE generateModelOptions()
BEGIN
	SELECT model_id, model_name FROM model
	ORDER BY model_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Body Type Table Stored Procedures */

CREATE PROCEDURE checkSupplierExist (IN p_supplier_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM supplier
    WHERE supplier_id = p_supplier_id;
END //

CREATE PROCEDURE insertSupplier(IN p_supplier_name VARCHAR(100), IN p_last_log_by INT, OUT p_supplier_id INT)
BEGIN
    INSERT INTO supplier (supplier_name, last_log_by) 
	VALUES(p_supplier_name, p_last_log_by);
	
    SET p_supplier_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateSupplier(IN p_supplier_id INT, IN p_supplier_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE supplier
    SET supplier_name = p_supplier_name,
    last_log_by = p_last_log_by
    WHERE supplier_id = p_supplier_id;
END //

CREATE PROCEDURE deleteSupplier(IN p_supplier_id INT)
BEGIN
    DELETE FROM supplier WHERE supplier_id = p_supplier_id;
END //

CREATE PROCEDURE getSupplier(IN p_supplier_id INT)
BEGIN
	SELECT * FROM supplier
    WHERE supplier_id = p_supplier_id;
END //

CREATE PROCEDURE duplicateSupplier(IN p_supplier_id INT, IN p_last_log_by INT, OUT p_new_supplier_id INT)
BEGIN
    DECLARE p_supplier_name VARCHAR(100);
    
    SELECT supplier_name
    INTO p_supplier_name
    FROM supplier 
    WHERE supplier_id = p_supplier_id;
    
    INSERT INTO supplier (supplier_name, last_log_by) 
    VALUES(p_supplier_name, p_last_log_by);
    
    SET p_new_supplier_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateSupplierTable()
BEGIN
    SELECT supplier_id, supplier_name
    FROM supplier
    ORDER BY supplier_id;
END //

CREATE PROCEDURE generateSupplierOptions()
BEGIN
	SELECT supplier_id, supplier_name FROM supplier
	ORDER BY supplier_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Body Type Table Stored Procedures */

CREATE PROCEDURE checkClassExist (IN p_class_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM class
    WHERE class_id = p_class_id;
END //

CREATE PROCEDURE insertClass(IN p_class_name VARCHAR(100), IN p_last_log_by INT, OUT p_class_id INT)
BEGIN
    INSERT INTO class (class_name, last_log_by) 
	VALUES(p_class_name, p_last_log_by);
	
    SET p_class_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateClass(IN p_class_id INT, IN p_class_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE class
    SET class_name = p_class_name,
    last_log_by = p_last_log_by
    WHERE class_id = p_class_id;
END //

CREATE PROCEDURE deleteClass(IN p_class_id INT)
BEGIN
    DELETE FROM class WHERE class_id = p_class_id;
END //

CREATE PROCEDURE getClass(IN p_class_id INT)
BEGIN
	SELECT * FROM class
    WHERE class_id = p_class_id;
END //

CREATE PROCEDURE duplicateClass(IN p_class_id INT, IN p_last_log_by INT, OUT p_new_class_id INT)
BEGIN
    DECLARE p_class_name VARCHAR(100);
    
    SELECT class_name
    INTO p_class_name
    FROM class 
    WHERE class_id = p_class_id;
    
    INSERT INTO class (class_name, last_log_by) 
    VALUES(p_class_name, p_last_log_by);
    
    SET p_new_class_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateClassTable()
BEGIN
    SELECT class_id, class_name
    FROM class
    ORDER BY class_id;
END //

CREATE PROCEDURE generateClassOptions()
BEGIN
	SELECT class_id, class_name FROM class
	ORDER BY class_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Bank Account Type Table Stored Procedures */

CREATE PROCEDURE checkModeOfAcquisitionExist (IN p_mode_of_acquisition_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM mode_of_acquisition
    WHERE mode_of_acquisition_id = p_mode_of_acquisition_id;
END //

CREATE PROCEDURE insertModeOfAcquisition(IN p_mode_of_acquisition_name VARCHAR(100), IN p_last_log_by INT, OUT p_mode_of_acquisition_id INT)
BEGIN
    INSERT INTO mode_of_acquisition (mode_of_acquisition_name, last_log_by) 
	VALUES(p_mode_of_acquisition_name, p_last_log_by);
	
    SET p_mode_of_acquisition_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateModeOfAcquisition(IN p_mode_of_acquisition_id INT, IN p_mode_of_acquisition_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE mode_of_acquisition
    SET mode_of_acquisition_name = p_mode_of_acquisition_name,
    last_log_by = p_last_log_by
    WHERE mode_of_acquisition_id = p_mode_of_acquisition_id;
END //

CREATE PROCEDURE deleteModeOfAcquisition(IN p_mode_of_acquisition_id INT)
BEGIN
    DELETE FROM mode_of_acquisition WHERE mode_of_acquisition_id = p_mode_of_acquisition_id;
END //

CREATE PROCEDURE getModeOfAcquisition(IN p_mode_of_acquisition_id INT)
BEGIN
	SELECT * FROM mode_of_acquisition
    WHERE mode_of_acquisition_id = p_mode_of_acquisition_id;
END //

CREATE PROCEDURE duplicateModeOfAcquisition(IN p_mode_of_acquisition_id INT, IN p_last_log_by INT, OUT p_new_mode_of_acquisition_id INT)
BEGIN
    DECLARE p_mode_of_acquisition_name VARCHAR(100);
    
    SELECT mode_of_acquisition_name
    INTO p_mode_of_acquisition_name
    FROM mode_of_acquisition 
    WHERE mode_of_acquisition_id = p_mode_of_acquisition_id;
    
    INSERT INTO mode_of_acquisition (mode_of_acquisition_name, last_log_by) 
    VALUES(p_mode_of_acquisition_name, p_last_log_by);
    
    SET p_new_mode_of_acquisition_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateModeOfAcquisitionTable()
BEGIN
    SELECT mode_of_acquisition_id, mode_of_acquisition_name
    FROM mode_of_acquisition
    ORDER BY mode_of_acquisition_id;
END //

CREATE PROCEDURE generateModeOfAcquisitionOptions()
BEGIN
	SELECT mode_of_acquisition_id, mode_of_acquisition_name FROM mode_of_acquisition
	ORDER BY mode_of_acquisition_name;
END //



/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE PROCEDURE updateLoanCollectionStatus(IN p_loan_collection_id INT, IN p_collection_status VARCHAR(50), IN p_reason VARCHAR(100), IN p_remarks VARCHAR(500), IN p_new_deposit_date DATE, IN p_reference_number VARCHAR(100), IN p_deposited_to INT, IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

    IF p_collection_status = 'Cleared' THEN
        UPDATE loan_collections
        SET collection_status = p_collection_status,
        clear_date = NOW(),
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN ('Deposited', 'Redeposit');
    ELSEIF p_collection_status = 'Deposited' THEN
        INSERT INTO loan_collections_history (loan_collection_id, mode_of_payment, transaction_date, transaction_type, reference_number, reference_date, last_log_by) 
	    VALUES(p_loan_collection_id, 'Check', NOW(), 'Deposited', p_reference_number, NOW(), p_last_log_by);

        UPDATE loan_collections
        SET collection_status = p_collection_status,
        deposit_date = NOW(),
        transaction_date = NOW(),
        or_number = p_reference_number,
        deposited_to = p_deposited_to,
        or_date = NOW(),
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status = 'For Deposit';
    ELSEIF p_collection_status = 'On-Hold' THEN
        UPDATE loan_collections
        SET collection_status = p_collection_status,
        onhold_date = NOW(),
        onhold_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Pending', 'For Deposit', 'Redeposit');
    ELSEIF p_collection_status = 'Reversed' THEN
        INSERT INTO loan_collections_history (loan_collection_id, mode_of_payment, transaction_date, transaction_type, reference_number, reference_date, last_log_by) 
	    VALUES(p_loan_collection_id, 'Check', NOW(), 'Reversed', p_reference_number, NOW(), p_last_log_by);

        UPDATE loan_collections
        SET collection_status = p_collection_status,
        reversal_date = p_new_deposit_date,
        reversal_reason = p_reason,
        reversal_remarks = p_remarks,
        reversal_reference_number = p_reference_number,
        reversal_reference_date = NOW(),
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Cleared', 'Deposited');
    ELSEIF p_collection_status = 'Cancelled' THEN
        UPDATE loan_collections
        SET collection_status = p_collection_status,
        cancellation_date = NOW(),
        cancellation_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Pending', 'Redeposit');
    ELSEIF p_collection_status = 'Redeposit' THEN
        UPDATE loan_collections
        SET collection_status = p_collection_status,
        redeposit_date = NOW(),
        new_deposit_date = p_new_deposit_date,
        payment_date = p_new_deposit_date,
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('On-Hold', 'Reversed');
    ELSEIF p_collection_status = 'For Deposit' THEN
        UPDATE loan_collections
        SET collection_status = p_collection_status,
        for_deposit_date = NOW(),
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Pending', 'Redeposit');
    ELSE
        UPDATE loan_collections
        SET collection_status = p_collection_status,
        pulled_out_date = NOW(),
        pulled_out_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE loan_collection_id = p_loan_collection_id AND collection_status IN('Pending', 'Cancelled', 'Redeposit', 'On-Hold');
    END IF;
END //

CREATE PROCEDURE generatePDCManagementTransactionHistoryTable(IN p_loan_collection_id INT)
BEGIN
	SELECT * FROM loan_collections_history
    WHERE loan_collection_id = p_loan_collection_id
	ORDER BY transaction_date DESC;
END //

CREATE PROCEDURE generateAllPDCManagementTransactionHistoryTable(IN p_transaction_type VARCHAR(500), IN p_mode_of_payment VARCHAR(500), IN p_transaction_start_date DATE, IN p_transaction_end_date DATE, IN p_reference_start_date DATE, IN p_reference_end_date DATE)
BEGIN
	DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM loan_collections_history';
    SET conditionList = ' WHERE 1';
    
    IF p_transaction_start_date IS NOT NULL AND p_transaction_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (DATE(transaction_date) BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_reference_start_date IS NOT NULL AND p_reference_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (reference_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reference_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reference_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_transaction_type IS NOT NULL AND p_transaction_type <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND transaction_type IN (');
        SET conditionList = CONCAT(conditionList, p_transaction_type);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    IF p_mode_of_payment IS NOT NULL AND p_mode_of_payment <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND mode_of_payment IN (');
        SET conditionList = CONCAT(conditionList, p_mode_of_payment);
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY transaction_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE checkApplicationSourceExist (IN p_application_source_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM application_source
    WHERE application_source_id = p_application_source_id;
END //

CREATE PROCEDURE insertApplicationSource(IN p_application_source_name VARCHAR(100), IN p_last_log_by INT, OUT p_application_source_id INT)
BEGIN
    INSERT INTO application_source (application_source_name, last_log_by) 
	VALUES(p_application_source_name, p_last_log_by);
	
    SET p_application_source_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateApplicationSource(IN p_application_source_id INT, IN p_application_source_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE application_source
    SET application_source_name = p_application_source_name,
    last_log_by = p_last_log_by
    WHERE application_source_id = p_application_source_id;
END //

CREATE PROCEDURE deleteApplicationSource(IN p_application_source_id INT)
BEGIN
    DELETE FROM application_source WHERE application_source_id = p_application_source_id;
END //

CREATE PROCEDURE getApplicationSource(IN p_application_source_id INT)
BEGIN
	SELECT * FROM application_source
    WHERE application_source_id = p_application_source_id;
END //

CREATE PROCEDURE duplicateApplicationSource(IN p_application_source_id INT, IN p_last_log_by INT, OUT p_new_application_source_id INT)
BEGIN
    DECLARE p_application_source_name VARCHAR(100);
    
    SELECT application_source_name
    INTO p_application_source_name
    FROM application_source 
    WHERE application_source_id = p_application_source_id;
    
    INSERT INTO application_source (application_source_name, last_log_by) 
    VALUES(p_application_source_name, p_last_log_by);
    
    SET p_new_application_source_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateApplicationSourceTable()
BEGIN
    SELECT application_source_id, application_source_name
    FROM application_source
    ORDER BY application_source_id;
END //

CREATE PROCEDURE generateApplicationSourceOptions()
BEGIN
	SELECT application_source_id, application_source_name FROM application_source
	ORDER BY application_source_name;
END //

/* Body Type Table Stored Procedures */

CREATE PROCEDURE checkDepositsExist (IN p_deposits_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM deposits
    WHERE deposits_id = p_deposits_id;
END //

CREATE PROCEDURE insertDeposits(IN p_company_id INT, IN p_deposit_amount DOUBLE, IN p_deposited_to INT, IN p_reference_number VARCHAR(100), IN p_remarks VARCHAR(500), IN p_last_log_by INT, OUT p_deposits_id INT)
BEGIN
    SET time_zone = '+08:00';
    
    INSERT INTO deposits (company_id, deposit_amount, deposit_date, deposited_to, reference_number, remarks, last_log_by) 
	VALUES(p_company_id,p_deposit_amount, NOW(), p_deposited_to, p_reference_number, p_remarks, p_last_log_by);
	
    SET p_deposits_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateDeposits(IN p_deposits_id INT, IN p_deposit_amount DOUBLE, IN p_deposit_date DATE, IN p_deposited_to INT, IN p_reference_number VARCHAR(100), IN p_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

	UPDATE deposits
    SET deposits_name = p_deposits_name,
        deposit_amount = p_deposit_amount,
        deposit_date = p_deposit_date,
        deposited_to = p_deposited_to,
        reference_number = p_reference_number,
        remarks = p_remarks,
    last_log_by = p_last_log_by
    WHERE deposits_id = p_deposits_id;
END //

CREATE PROCEDURE deleteDeposits(IN p_deposits_id INT)
BEGIN
    DELETE FROM deposits WHERE deposits_id = p_deposits_id;
END //

CREATE PROCEDURE getDeposits(IN p_deposits_id INT)
BEGIN
	SELECT * FROM deposits
    WHERE deposits_id = p_deposits_id;
END //

CREATE PROCEDURE generateDepositsTable()
BEGIN
    SELECT deposits_id, deposits_name, deposit_amount, deposit_date, deposited_to, reference_number, remarks
    FROM deposits
    ORDER BY deposits_id;
END //

CREATE PROCEDURE generateDepositsTable( IN p_transaction_start_date DATE, IN p_transaction_end_date DATE, IN p_deposit_start_date DATE, IN p_deposit_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT deposits_id, deposit_amount, deposit_date, deposited_to, reference_number, transaction_date, remarks FROM deposits';
    SET conditionList = ' WHERE 1';
    
    IF p_transaction_start_date IS NOT NULL AND p_transaction_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transaction_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_deposit_start_date IS NOT NULL AND p_deposit_start_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (deposit_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_deposit_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_deposit_start_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY deposit_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE PROCEDURE checkTravelAuthorizationExist (IN p_travel_form_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM travel_authorization
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE insertTravelAuthorization(IN p_travel_form_id INT, IN p_destination VARCHAR(500), IN p_mode_of_transportation VARCHAR(500), IN p_purpose_of_travel VARCHAR(500), IN p_authorization_departure_date DATE, IN p_authorization_return_date DATE, IN p_accomodation_details VARCHAR(1000), IN p_toll_fee DOUBLE, IN p_accomodation DOUBLE, IN p_meals DOUBLE, IN p_other_expenses DOUBLE, IN p_total_estimated_cost DOUBLE, IN p_additional_comments VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
    INSERT INTO travel_authorization (travel_form_id, destination, mode_of_transportation, purpose_of_travel, authorization_departure_date, authorization_return_date, accomodation_details, toll_fee, accomodation, meals, other_expenses, total_estimated_cost, additional_comments, last_log_by) 
	VALUES(p_travel_form_id, p_destination, p_mode_of_transportation, p_purpose_of_travel, p_authorization_departure_date, p_authorization_return_date, p_accomodation_details, p_toll_fee, p_accomodation, p_meals, p_other_expenses, p_total_estimated_cost, p_additional_comments, p_last_log_by);
END //

CREATE PROCEDURE updateTravelAuthorization(IN p_travel_form_id INT, IN p_destination VARCHAR(500), IN p_mode_of_transportation VARCHAR(500), IN p_purpose_of_travel VARCHAR(500), IN p_authorization_departure_date DATE, IN p_authorization_return_date DATE, IN p_accomodation_details VARCHAR(1000), IN p_toll_fee DOUBLE, IN p_accomodation DOUBLE, IN p_meals DOUBLE, IN p_other_expenses DOUBLE, IN p_total_estimated_cost DOUBLE, IN p_additional_comments VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

	UPDATE travel_authorization
    SET destination = p_destination,
        mode_of_transportation = p_mode_of_transportation,
        purpose_of_travel = p_purpose_of_travel,
        authorization_departure_date = p_authorization_departure_date,
        authorization_return_date = p_authorization_return_date,
        accomodation_details = p_accomodation_details,
        toll_fee = p_toll_fee,
        accomodation = p_accomodation,
        meals = p_meals,
        other_expenses = p_other_expenses,
        total_estimated_cost = p_total_estimated_cost,
        additional_comments = p_additional_comments,
        last_log_by = p_last_log_by
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE getTravelAuthorization(IN p_travel_form_id INT)
BEGIN
	SELECT * FROM travel_authorization
    WHERE travel_form_id = p_travel_form_id;
END //


/* ----------------------------------------------------------------------------------------------------------------------------- */

/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE PROCEDURE checkGatePassExist (IN p_travel_form_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM travel_gate_pass
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE insertGatePass(IN p_travel_form_id INT, IN p_name_of_driver VARCHAR(500), IN p_contact_number VARCHAR(50), IN p_vehicle_type VARCHAR(200), IN p_plate_number VARCHAR(200), IN p_purpose_of_entry_exit VARCHAR(500), p_department_id INT, IN p_gate_pass_departure_date DATE, IN p_odometer_reading VARCHAR(200), IN p_remarks VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
    INSERT INTO travel_gate_pass (travel_form_id, name_of_driver, contact_number, vehicle_type, plate_number, purpose_of_entry_exit, department_id, gate_pass_departure_date, odometer_reading, remarks, last_log_by) 
	VALUES(p_travel_form_id, p_name_of_driver, p_contact_number, p_vehicle_type, p_plate_number, p_purpose_of_entry_exit, p_department_id, p_gate_pass_departure_date, p_odometer_reading, p_remarks, p_last_log_by);
END //

CREATE PROCEDURE updateGatePass(IN p_travel_form_id INT, IN p_name_of_driver VARCHAR(500), IN p_contact_number VARCHAR(50), IN p_vehicle_type VARCHAR(200), IN p_plate_number VARCHAR(200), IN p_purpose_of_entry_exit VARCHAR(100), IN p_department_id INT, IN p_gate_pass_departure_date DATE, IN p_odometer_reading VARCHAR(200), IN p_remarks VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

	UPDATE travel_gate_pass
    SET name_of_driver = p_name_of_driver,
        contact_number = p_contact_number,
        vehicle_type = p_vehicle_type,
        plate_number = p_plate_number,
        purpose_of_entry_exit = p_purpose_of_entry_exit,
        department_id = p_department_id,
        gate_pass_departure_date = p_gate_pass_departure_date,
        odometer_reading = p_odometer_reading,
        remarks = p_remarks,
        last_log_by = p_last_log_by
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE getGatePass(IN p_travel_form_id INT)
BEGIN
	SELECT * FROM travel_gate_pass
    WHERE travel_form_id = p_travel_form_id;
END //


/* ----------------------------------------------------------------------------------------------------------------------------- */


CREATE PROCEDURE checkTravelFormExist (IN p_travel_form_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM travel_form
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE insertTravelForm(IN p_checked_by INT, IN p_recommended_by INT, IN p_approval_by INT, IN p_last_log_by INT, OUT p_travel_form_id INT)
BEGIN
    SET time_zone = '+08:00';
    
    INSERT INTO travel_form (checked_by, recommended_by, approval_by, created_by, last_log_by) 
	VALUES(p_checked_by, p_recommended_by, p_approval_by, p_last_log_by, p_last_log_by);
	
    SET p_travel_form_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateTravelForm(IN p_travel_form_id INT, IN p_checked_by INT, IN p_recommended_by INT, IN p_approval_by INT, IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

	UPDATE travel_form
    SET checked_by = p_checked_by,
        recommended_by = p_recommended_by,
        approval_by = p_approval_by,
        last_log_by = p_last_log_by
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE updateTravelFormStatus(IN p_travel_form_id INT, IN p_travel_form_status VARCHAR(100), IN p_remarks VARCHAR(5000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
    IF p_travel_form_status = 'For Checking' THEN
        UPDATE travel_form
        SET travel_form_status = p_travel_form_status,
            for_checking_date = NOW(),
        last_log_by = p_last_log_by
        WHERE travel_form_id = p_travel_form_id;
    ELSEIF p_travel_form_status = 'Checked' THEN
        UPDATE travel_form
        SET travel_form_status = p_travel_form_status,
            checked_date = NOW(),
        last_log_by = p_last_log_by
        WHERE travel_form_id = p_travel_form_id;
    ELSEIF p_travel_form_status = 'For Recommendation' THEN
        UPDATE travel_form
        SET travel_form_status = p_travel_form_status,
            for_recommendation_date = NOW(),
        last_log_by = p_last_log_by
        WHERE travel_form_id = p_travel_form_id;
    ELSEIF p_travel_form_status = 'Recommended' THEN
        UPDATE travel_form
        SET travel_form_status = p_travel_form_status,
            recommended_date = NOW(),
        last_log_by = p_last_log_by
        WHERE travel_form_id = p_travel_form_id;
    ELSEIF p_travel_form_status = 'Rejected' THEN
        UPDATE travel_form
        SET travel_form_status = p_travel_form_status,
            rejection_date = NOW(),
            rejection_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE travel_form_id = p_travel_form_id;
    ELSEIF p_travel_form_status = 'Draft' THEN
        UPDATE travel_form
        SET travel_form_status = p_travel_form_status,
            set_to_draft_date = NOW(),
            set_to_draft_reason = p_remarks,
        last_log_by = p_last_log_by
        WHERE travel_form_id = p_travel_form_id;
    ELSE
        UPDATE travel_form
        SET travel_form_status = p_travel_form_status,
            approval_date = NOW(),
        last_log_by = p_last_log_by
        WHERE travel_form_id = p_travel_form_id;
    END IF;
END //

CREATE PROCEDURE deleteTravelForm(IN p_travel_form_id INT)
BEGIN
    DELETE FROM travel_form WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE getTravelForm(IN p_travel_form_id INT)
BEGIN
	SELECT * FROM travel_form
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE generateTravelFormTable()
BEGIN
    SELECT *
    FROM travel_form
    ORDER BY travel_form_id;
END //

CREATE PROCEDURE generateTravelDashboardTable(IN p_contact_id INT)
BEGIN
    SELECT travel_form_id, checked_by, checked_date, recommended_by, recommended_date, approval_by, approval_date, travel_form_status, created_by
    FROM travel_form
    WHERE travel_form_status IN ('Recommended') AND (approval_by = p_contact_id)
    ORDER BY travel_form_id;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE PROCEDURE checkItineraryExist (IN p_itinerary_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM travel_itinerary
    WHERE itinerary_id = p_itinerary_id;
END //

CREATE PROCEDURE insertItinerary(IN p_travel_form_id INT, IN p_itinerary_date DATE, IN p_customer_id INT, IN p_itinerary_destination VARCHAR(500), IN p_itinerary_purpose VARCHAR(500), IN p_expected_time_of_departure TIME, IN p_expected_time_of_arrival TIME, IN p_remarks VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';
    
    INSERT INTO travel_itinerary (travel_form_id, itinerary_date, customer_id, itinerary_destination, itinerary_purpose, expected_time_of_departure, expected_time_of_arrival, remarks, last_log_by) 
	VALUES(p_travel_form_id, p_itinerary_date, p_customer_id, p_itinerary_destination, p_itinerary_purpose, p_expected_time_of_departure, p_expected_time_of_arrival, p_remarks, p_last_log_by);
END //

CREATE PROCEDURE updateItinerary(IN p_itinerary_id INT, IN p_travel_form_id INT, IN p_itinerary_date DATE, IN p_customer_id INT, IN p_itinerary_destination VARCHAR(500), IN p_itinerary_purpose VARCHAR(500), IN p_expected_time_of_departure TIME, IN p_expected_time_of_arrival TIME, IN p_remarks VARCHAR(1000), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

	UPDATE travel_itinerary
    SET travel_form_id = p_travel_form_id,
        itinerary_date = p_itinerary_date,
        customer_id = p_customer_id,
        itinerary_destination = p_itinerary_destination,
        itinerary_purpose = p_itinerary_purpose,
        expected_time_of_departure = p_expected_time_of_departure,
        expected_time_of_arrival = p_expected_time_of_arrival,
        remarks = p_remarks,
        last_log_by = p_last_log_by
    WHERE itinerary_id = p_itinerary_id;
END //

CREATE PROCEDURE deleteItinerary(IN p_itinerary_id INT)
BEGIN
    DELETE FROM travel_itinerary WHERE itinerary_id = p_itinerary_id;
END //

CREATE PROCEDURE getItinerary(IN p_itinerary_id INT)
BEGIN
	SELECT * FROM travel_itinerary
    WHERE itinerary_id = p_itinerary_id;
END //

CREATE PROCEDURE getItineraryByTravelForm(IN p_travel_form_id INT)
BEGIN
	SELECT * FROM travel_itinerary
    WHERE travel_form_id = p_travel_form_id;
END //

CREATE PROCEDURE generateItineraryTable(IN p_travel_form_id INT)
BEGIN
    SELECT itinerary_id, itinerary_date, customer_id, itinerary_destination, itinerary_purpose, expected_time_of_departure, expected_time_of_arrival, remarks
    FROM travel_itinerary
    WHERE travel_form_id = p_travel_form_id
    ORDER BY itinerary_id;
END //

CREATE PROCEDURE generateItinerarySummaryTable(IN p_itinerary_start_date DATE, IN p_itinerary_end_date DATE)
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM travel_itinerary';
    SET conditionList = ' WHERE travel_form_id IN (SELECT travel_form_id FROM travel_form WHERE travel_form_status = "Approved")';
    
    IF p_itinerary_start_date IS NOT NULL AND p_itinerary_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (itinerary_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_itinerary_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_itinerary_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY itinerary_date DESC');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //


CREATE PROCEDURE getEmployeePrimaryContactInformation(IN p_contact_id INT)
BEGIN
	SELECT mobile, telephone, email FROM contact_information
    WHERE contact_id = p_contact_id AND is_primary = 1;
END //

CREATE PROCEDURE getEmployeeWorkContactInformation(IN p_contact_id INT)
BEGIN
	SELECT mobile, telephone, email FROM contact_information
    WHERE contact_id = p_contact_id AND contact_information_type_id = 2;
END //

CREATE PROCEDURE getEmployeeActiveWorkContactInformation()
BEGIN
	SELECT mobile, telephone, email FROM contact_information
    WHERE contact_id IN (SELECT contact_id from employment_information WHERE employment_status = 1) AND contact_information_type_id = 2;
END //


/* Chart of Account Table Stored Procedures */

CREATE PROCEDURE checkChartOfAccountExist (IN p_chart_of_account_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM chart_of_account
    WHERE chart_of_account_id = p_chart_of_account_id;
END //

CREATE PROCEDURE insertChartOfAccount(IN p_code VARCHAR(100), IN p_name VARCHAR(500), IN p_account_type VARCHAR(500), IN p_last_log_by INT, OUT p_chart_of_account_id INT)
BEGIN
    INSERT INTO chart_of_account (code, name, account_type, last_log_by) 
	VALUES(p_code, p_name, p_account_type, p_last_log_by);
	
    SET p_chart_of_account_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateChartOfAccount(IN p_chart_of_account_id INT, IN p_code VARCHAR(100), IN p_name VARCHAR(500), IN p_account_type VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE chart_of_account
    SET code = p_code,
        name = p_name,
        account_type = p_account_type,
    last_log_by = p_last_log_by
    WHERE chart_of_account_id = p_chart_of_account_id;
END //

CREATE PROCEDURE deleteChartOfAccount(IN p_chart_of_account_id INT)
BEGIN
    DELETE FROM chart_of_account WHERE chart_of_account_id = p_chart_of_account_id;
END //

CREATE PROCEDURE getChartOfAccount(IN p_chart_of_account_id INT)
BEGIN
	SELECT * FROM chart_of_account
    WHERE chart_of_account_id = p_chart_of_account_id;
END //

CREATE PROCEDURE generateChartOfAccountOptions()
BEGIN
    SELECT chart_of_account_id, code, name
    FROM chart_of_account
    ORDER BY chart_of_account_id;
END //

CREATE PROCEDURE generateChartOfAccountDisbursementOptions()
BEGIN
    SELECT chart_of_account_id, code, name
    FROM chart_of_account
    WHERE account_type NOT IN ('Equity')
    ORDER BY chart_of_account_id;
END //

CREATE PROCEDURE generateUnlinkedContactOptions()
BEGIN
	SELECT contact_id, file_as FROM personal_information 
    WHERE contact_id IN (SELECT contact_id FROM contact WHERE portal_access = 1 AND user_id IS NULL);
END //

CREATE PROCEDURE generateProductExpenseTable(IN p_product_id INT, IN p_reference_type VARCHAR(100), IN p_expense_type VARCHAR(100))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM product_expense';
    SET conditionList = ' WHERE 1';
    
    IF p_reference_type IS NOT NULL AND p_reference_type <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND reference_type =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reference_type));
    END IF;
    
    IF p_expense_type IS NOT NULL AND p_expense_type <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND expense_type =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_expense_type));
    END IF;

    SET conditionList = CONCAT(conditionList, ' AND product_id =');
    SET conditionList = CONCAT(conditionList, QUOTE(p_product_id));

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY created_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateProductExpenseTable2(IN p_reference_type VARCHAR(100), IN p_expense_type VARCHAR(100))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM product_expense';
    SET conditionList = ' WHERE 1';
    
    IF p_reference_type IS NOT NULL AND p_reference_type <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND reference_type =');
        SET conditionList = CONCAT(conditionList, QUOTE(p_reference_type));
    END IF;

    IF p_expense_type IS NOT NULL AND p_expense_type <> '' THEN
        SET conditionList = CONCAT(conditionList, ' AND expense_type = ', QUOTE(p_expense_type));
    ELSE
        SET conditionList = CONCAT(conditionList, ' AND expense_type != "Landed Cost"');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY created_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE PROCEDURE updateProductStatus(IN p_product_id INT, IN p_product_status VARCHAR(50), IN p_particulars VARCHAR(500), IN p_expense_amount DOUBLE, IN p_expense_type VARCHAR(100), IN p_last_log_by INT)
BEGIN
    SET time_zone = '+08:00';

    IF p_product_status = 'For Sale' THEN
        UPDATE product
        SET product_status = p_product_status,
        for_sale_date = NOW(),
        last_log_by = p_last_log_by
        WHERE product_id = p_product_id;

        INSERT INTO product_expense (product_id, expense_amount, expense_type, particulars, last_log_by) 
	    VALUES(p_product_id, p_expense_amount, p_expense_type, p_particulars, p_last_log_by);
    ELSEIF p_product_status = 'Rented' THEN
        UPDATE product
        SET product_status = p_product_status,
        rented_date = NOW(),
        last_log_by = p_last_log_by
        WHERE product_id = p_product_id;
    ELSEIF p_product_status = 'Returned' THEN
        UPDATE product
        SET product_status = 'For Sale',
        returned_date = NOW(),
        last_log_by = p_last_log_by
        WHERE product_id = p_product_id;
    ELSEIF p_product_status = 'Consigned' THEN
        UPDATE product
        SET product_status = p_product_status,
        consignment_date = NOW(),
        last_log_by = p_last_log_by
        WHERE product_id = p_product_id;
    ELSEIF p_product_status = 'Repossessed' THEN
        UPDATE product
        SET product_status = p_product_status,
        repossessed_date = NOW(),
        last_log_by = p_last_log_by
        WHERE product_id = p_product_id;
    ELSEIF p_product_status = 'ROPA' THEN
        UPDATE product
        SET product_status = 'For Sale',
        ropa_date = NOW(),
        last_log_by = p_last_log_by
        WHERE product_id = p_product_id;
    ELSE
        UPDATE product
        SET product_status = p_product_status,
        sold_date = NOW(),
        last_log_by = p_last_log_by
        WHERE product_id = p_product_id;
    END IF;
END //

CREATE PROCEDURE insertProductExpense(IN p_product_id INT, IN p_reference_type VARCHAR(100), IN p_reference_number VARCHAR(200), IN p_expense_amount DOUBLE, IN p_expense_type VARCHAR(100), IN p_particulars VARCHAR(500), IN p_last_log_by INT)
BEGIN
    INSERT INTO product_expense (product_id, reference_type, reference_number, expense_amount, expense_type, particulars, last_log_by) 
	VALUES(p_product_id, p_reference_type, p_reference_number, p_expense_amount, p_expense_type, p_particulars, p_last_log_by);
END //

CREATE PROCEDURE deleteProductExpense(IN p_product_expense_id INT)
BEGIN
    DELETE FROM product_expense WHERE product_expense_id = p_product_expense_id;
END //

CREATE PROCEDURE deleteProductDocument(IN p_product_document_id INT)
BEGIN
    DELETE FROM product_document WHERE product_document_id = p_product_document_id;
END //

CREATE PROCEDURE getProductDocument(IN p_product_document_id INT)
BEGIN
	SELECT * FROM product_document
    WHERE product_document_id = p_product_document_id;
END //


CREATE PROCEDURE checkJournalCodeExist (IN p_journal_code_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM journal_code
    WHERE journal_code_id = p_journal_code_id;
END //

CREATE PROCEDURE insertJournalCode(IN p_company_id INT, IN p_transaction_type INT, IN p_product_type_id INT, IN p_transaction VARCHAR(100), IN p_item VARCHAR(100), IN p_debit_id INT, IN p_credit_id INT, IN p_debit VARCHAR(500), IN p_credit VARCHAR(500), IN p_reference_code VARCHAR(200), IN p_last_log_by INT, OUT p_journal_code_id INT)
BEGIN
    INSERT INTO journal_code (company_id, transaction_type, product_type_id, transaction, item, debit_id, credit_id, debit, credit, reference_code, last_log_by) 
	VALUES(p_company_id, p_transaction_type, p_product_type_id, p_transaction, p_item, p_debit_id, p_credit_id, p_debit, p_credit, p_reference_code, p_last_log_by);
	
    SET p_journal_code_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateJournalCode(IN p_journal_code_id INT, IN p_company_id INT, IN p_transaction_type INT, IN p_product_type_id INT, IN p_transaction VARCHAR(100), IN p_item VARCHAR(100), IN p_debit_id INT, IN p_credit_id INT, IN p_debit VARCHAR(500), IN p_credit VARCHAR(500), IN p_reference_code VARCHAR(200), IN p_last_log_by INT)
BEGIN
	UPDATE journal_code
    SET company_id = p_company_id,
        transaction_type = p_transaction_type,
        product_type_id = p_product_type_id,
        transaction = p_transaction,
        item = p_item,
        debit_id = p_debit_id,
        credit_id = p_credit_id,
        debit = p_debit,
        credit = p_credit,
        reference_code = p_reference_code,
    last_log_by = p_last_log_by
    WHERE journal_code_id = p_journal_code_id;
END //

CREATE PROCEDURE deleteJournalCode(IN p_journal_code_id INT)
BEGIN
    DELETE FROM journal_code WHERE journal_code_id = p_journal_code_id;
END //

CREATE PROCEDURE getJournalCode(IN p_journal_code_id INT)
BEGIN
	SELECT * FROM journal_code
    WHERE journal_code_id = p_journal_code_id;
END //

CREATE PROCEDURE generateJournalCodeTable()
BEGIN
    SELECT *
    FROM journal_code
    ORDER BY journal_code_id;
END //


DELIMITER //
DROP PROCEDURE create_journal_entry//

CREATE PROCEDURE create_journal_entry(
    IN p_loan_number VARCHAR(100),
    IN p_company_id INT,
    IN p_transaction_type INT,
    IN p_product_type VARCHAR(100),
    IN p_product_type_code VARCHAR(100),
    IN p_sales_proposal_id INT,
    IN p_product_id INT,
    IN p_journal_entry_date DATE,
    IN p_last_log_by INT
)
BEGIN
    -- Declare variables
    DECLARE v_transaction VARCHAR(100);
    DECLARE v_item VARCHAR(100);
    DECLARE v_amount DOUBLE;
    DECLARE v_debit_code VARCHAR(100);
    DECLARE v_credit_code VARCHAR(100);
    DECLARE v_cursor_done INT DEFAULT 0;

    -- Declare variables for analytic_lines and analytic_distribution
    DECLARE v_analytic_lines VARCHAR(500);
    DECLARE v_analytic_distribution VARCHAR(500);
    
    -- Declare reference code
    DECLARE v_reference_code VARCHAR(200);

    -- Declare cursor for fetching journal codes (debit, credit codes)
    DECLARE cur_journal_code CURSOR FOR
        SELECT transaction, item, debit, credit
        FROM journal_code
        WHERE company_id = p_company_id 
          AND transaction_type = p_transaction_type 
          AND product_type_id = p_product_type_code;
    
    -- Declare a handler to manage the cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_cursor_done = 1;

    -- Set reference code dynamically based on journal entry date
    SET v_reference_code = CONCAT(
        'TO RECORD SALES FOR THE DAY ( ', 
        p_loan_number, 
        ')'
    );

    -- Open cursor
    OPEN cur_journal_code;

    -- Fetch first row from cursor
    FETCH cur_journal_code INTO v_transaction, v_item, v_debit_code, v_credit_code;

    -- Process each journal code
    journal_loop: LOOP
        IF v_cursor_done THEN
            LEAVE journal_loop;
        END IF;

        -- Determine the amount based on the item
        IF v_item = 'PRI' THEN
            SELECT total_delivery_price INTO v_amount
            FROM sales_proposal_pricing_computation
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'INT' THEN
            SELECT (pn_amount - amount_financed) INTO v_amount
            FROM sales_proposal_pricing_computation
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'INS' THEN
            SELECT insurance_premium_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'REG' THEN
            SELECT registration_fee INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'DOC' THEN
            SELECT doc_stamp_tax_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'TRA' THEN
            SELECT transfer_fee_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'TRS' THEN
            SELECT transaction_fee_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'HAN' THEN
            SELECT handling_fee_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'DP' THEN
            SELECT downpayment INTO v_amount
            FROM sales_proposal_pricing_computation
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'AJO' THEN
            SELECT COALESCE(SUM(cost), 0) INTO v_amount
            FROM sales_proposal_additional_job_order
            WHERE sales_proposal_id = p_sales_proposal_id;
        END IF;

        -- Set analytic_lines and analytic_distribution based on p_product_type
        IF p_product_type = 'Fuel' THEN
            SET v_analytic_lines = 'NE FUEL';
            SET v_analytic_distribution = '{"6": 100.0}';
        ELSEIF p_product_type = 'Repair' THEN
            SET v_analytic_lines = 'NE TRUCK';
            SET v_analytic_distribution = '{"2": 100.0}';
        ELSE
            SET v_analytic_lines = 'CGMI';
            SET v_analytic_distribution = '{"1": 100.0}';
        END IF;

        -- Insert Debit Entry using the journal transaction value for debit
        INSERT INTO journal_entry (
            loan_number, 
            journal_entry_date, 
            reference_code, 
            journal_id, 
            journal_item, 
            debit, 
            credit, 
            journal_label, 
            analytic_lines, 
            analytic_distribution, 
            created_date, 
            last_log_by
        ) VALUES (
            p_loan_number, 
            p_journal_entry_date, 
            v_reference_code, 
            'Miscellaneous Operations', -- Use the debit account code
            v_debit_code, -- Use the transaction as journal_item (which can be the debit reference)
            v_amount, 
            0, 
            '', 
            v_analytic_lines, 
            v_analytic_distribution, 
            NOW(), 
            p_last_log_by
        );

        -- Insert Credit Entry using the journal transaction value for credit
        INSERT INTO journal_entry (
            loan_number, 
            journal_entry_date, 
            reference_code, 
            journal_id, 
            journal_item, 
            debit, 
            credit, 
            journal_label, 
            analytic_lines, 
            analytic_distribution, 
            created_date, 
            last_log_by
        ) VALUES (
            p_loan_number, 
            p_journal_entry_date, 
            v_reference_code, 
            'Miscellaneous Operations', -- Use the credit account code
            v_credit_code, -- Use the transaction as journal_item (which can be the credit reference)
            0, 
            v_amount, 
            '', 
            v_analytic_lines, 
            v_analytic_distribution, 
            NOW(), 
            p_last_log_by
        );

        -- Fetch the next journal code
        FETCH cur_journal_code INTO v_transaction, v_item, v_debit_code, v_credit_code;
    END LOOP;

    -- Close cursor
    CLOSE cur_journal_code;
END//

CREATE PROCEDURE createDisbursementEntry(
    IN p_disbursement_id INT,
    IN p_transaction_number VARCHAR(100),
    IN p_fund_source VARCHAR(100),
    IN p_transaction_type VARCHAR(100),
    IN p_last_log_by INT
)
BEGIN
    -- Declare variables
    DECLARE v_transaction VARCHAR(100);
    DECLARE v_item VARCHAR(100);
    DECLARE v_amount DOUBLE;
    DECLARE v_debit_code VARCHAR(100);
    DECLARE v_credit_code VARCHAR(100);
    DECLARE v_cursor_done INT DEFAULT 0;

    -- Declare variables for analytic_lines and analytic_distribution
    DECLARE v_analytic_lines VARCHAR(500);
    DECLARE v_analytic_distribution VARCHAR(500);
    
    -- Declare reference code
    DECLARE v_reference_code VARCHAR(200);

    -- Declare cursor for fetching journal codes (debit, credit codes)
    DECLARE cur_journal_code CURSOR FOR
        SELECT transaction, item, debit, credit
        FROM `journal_code`
        WHERE company_id = p_company_id 
          AND transaction_type = p_transaction_type 
          AND product_type_id = p_product_type_code;
    
    -- Declare a handler to manage the cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_cursor_done = 1;

    -- Set reference code dynamically based on journal entry date
    SET v_reference_code = CONCAT(
        'TO RECORD SALES FOR THE DAY ( ', 
        p_loan_number, 
        ')'
    );

    -- Open cursor
    OPEN cur_journal_code;

    -- Fetch first row from cursor
    FETCH cur_journal_code INTO v_transaction, v_item, v_debit_code, v_credit_code;

    -- Process each journal code
    journal_loop: LOOP
        IF v_cursor_done THEN
            LEAVE journal_loop;
        END IF;

        -- Determine the amount based on the item
        IF v_item = 'PRI' THEN
            SELECT total_delivery_price INTO v_amount
            FROM sales_proposal_pricing_computation
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'INT' THEN
            SELECT (pn_amount - amount_financed) INTO v_amount
            FROM sales_proposal_pricing_computation
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'INS' THEN
            SELECT insurance_premium INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'REG' THEN
            SELECT registration_fee INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'DOC' THEN
            SELECT doc_stamp_tax_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'TRA' THEN
            SELECT transfer_fee_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'TRS' THEN
            SELECT transaction_fee_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'HAN' THEN
            SELECT handling_fee_subtotal INTO v_amount
            FROM sales_proposal_other_charges
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'DP' THEN
            SELECT downpayment INTO v_amount
            FROM sales_proposal_pricing_computation
            WHERE sales_proposal_id = p_sales_proposal_id;
        ELSEIF v_item = 'AJO' THEN
            SELECT COALESCE(SUM(expense_amount), 0) INTO v_amount
            FROM product_expense
            WHERE product_id = p_product_id;
        END IF;

        -- Set analytic_lines and analytic_distribution based on p_product_type
        IF p_product_type = 'Fuel' THEN
            SET v_analytic_lines = 'NE FUEL';
            SET v_analytic_distribution = '{"6": 100.0}';
        ELSEIF p_product_type = 'Repair' THEN
            SET v_analytic_lines = 'NE TRUCK';
            SET v_analytic_distribution = '{"2": 100.0}';
        ELSE
            SET v_analytic_lines = 'CGMI';
            SET v_analytic_distribution = '{"1": 100.0}';
        END IF;

        -- Insert Debit Entry using the journal transaction value for debit
        INSERT INTO journal_entry (
            loan_number, 
            journal_entry_date, 
            reference_code, 
            journal_id, 
            journal_item, 
            debit, 
            credit, 
            journal_label, 
            analytic_lines, 
            analytic_distribution, 
            created_date, 
            last_log_by
        ) VALUES (
            p_loan_number, 
            p_journal_entry_date, 
            v_reference_code, 
            'Miscellaneous Operations', -- Use the debit account code
            v_debit_code, -- Use the transaction as journal_item (which can be the debit reference)
            v_amount, 
            0, 
            '', 
            v_analytic_lines, 
            v_analytic_distribution, 
            NOW(), 
            p_last_log_by
        );

        -- Insert Credit Entry using the journal transaction value for credit
        INSERT INTO journal_entry (
            loan_number, 
            journal_entry_date, 
            reference_code, 
            journal_id, 
            journal_item, 
            debit, 
            credit, 
            journal_label, 
            analytic_lines, 
            analytic_distribution, 
            created_date, 
            last_log_by
        ) VALUES (
            p_loan_number, 
            p_journal_entry_date, 
            v_reference_code, 
            'Miscellaneous Operations', -- Use the credit account code
            v_credit_code, -- Use the transaction as journal_item (which can be the credit reference)
            0, 
            v_amount, 
            '', 
            v_analytic_lines, 
            v_analytic_distribution, 
            NOW(), 
            p_last_log_by
        );

        -- Fetch the next journal code
        FETCH cur_journal_code INTO v_transaction, v_item, v_debit_code, v_credit_code;
    END LOOP;

    -- Close cursor
    CLOSE cur_journal_code;
END//



CREATE PROCEDURE checkDisbursementExist (IN p_disbursement_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM disbursement
    WHERE disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE checkDisbursementParticularsExist (IN p_disbursement_particulars_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM disbursement_particulars
    WHERE disbursement_particulars_id = p_disbursement_particulars_id;
END //

CREATE PROCEDURE checkDisbursementCheckExist (IN p_disbursement_check_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM disbursement_check
    WHERE disbursement_check_id = p_disbursement_check_id;
END //

CREATE PROCEDURE checkLiquidationParticularsExist (IN p_liquidation_particulars_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM liquidation_particulars
    WHERE liquidation_particulars_id = p_liquidation_particulars_id;
END //

CREATE PROCEDURE checkLiquidationExist (IN p_liquidation_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM liquidation
    WHERE liquidation_id = p_liquidation_id;
END //

CREATE PROCEDURE insertDisbursement(IN p_payable_type VARCHAR(100), IN p_customer_id INT, IN p_department_id INT, IN p_company_id INT, IN p_transaction_number VARCHAR(100), IN p_transaction_type VARCHAR(100), IN p_fund_source VARCHAR(100), IN p_particulars VARCHAR(5000), IN p_transaction_date DATE, IN p_last_log_by INT, OUT p_disbursement_id INT)
BEGIN
    INSERT INTO disbursement (payable_type, customer_id, department_id, company_id, transaction_number, transaction_type, fund_source, particulars, transaction_date, created_by, last_log_by) 
	VALUES(p_payable_type, p_customer_id, p_department_id, p_company_id, p_transaction_number, p_transaction_type, p_fund_source, p_particulars, p_transaction_date, p_last_log_by, p_last_log_by);
	
    SET p_disbursement_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE insertDisbursementParticulars(IN p_disbursement_id INT, IN p_chart_of_account_id INT, IN p_company_id INT, IN p_remarks VARCHAR(100), IN p_particulars_amount DOUBLE, IN p_base_amount DOUBLE, IN p_with_vat VARCHAR(5), IN p_with_withholding VARCHAR(5), IN p_vat_amount DOUBLE, IN p_withholding_amount DOUBLE, IN p_total_amount DOUBLE, IN p_tax_quarter INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO disbursement_particulars (disbursement_id, chart_of_account_id, company_id, remarks, particulars_amount, base_amount, with_vat, with_withholding, vat_amount, withholding_amount, total_amount, tax_quarter, created_by, last_log_by) 
	VALUES(p_disbursement_id, p_chart_of_account_id, p_company_id, p_remarks, p_particulars_amount, p_base_amount, p_with_vat, p_with_withholding, p_vat_amount, p_withholding_amount, p_total_amount, p_tax_quarter, p_last_log_by, p_last_log_by);
END //

CREATE PROCEDURE insertDisbursementCheck(IN p_disbursement_id INT, IN p_bank_branch VARCHAR(100), IN p_check_name VARCHAR(5000), IN p_check_number VARCHAR(100), IN p_check_date DATE, IN p_check_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    INSERT INTO disbursement_check (disbursement_id, bank_branch, check_name, check_number, check_date, check_amount, created_by, last_log_by) 
	VALUES(p_disbursement_id, p_bank_branch, p_check_name, p_check_number, p_check_date, p_check_amount, p_last_log_by, p_last_log_by);
END //

CREATE PROCEDURE updateDisbursement(IN p_disbursement_id INT, IN p_payable_type VARCHAR(100), IN p_customer_id INT, IN p_department_id INT, IN p_company_id INT, IN p_transaction_number VARCHAR(100), IN p_transaction_type VARCHAR(100), IN p_fund_source VARCHAR(100), IN p_particulars VARCHAR(5000), IN p_transaction_date DATE, IN p_last_log_by INT)
BEGIN
	UPDATE disbursement
    SET payable_type = p_payable_type,
        customer_id = p_customer_id,
        department_id = p_department_id,
        company_id = p_company_id,
        transaction_number = p_transaction_number,
        transaction_type = p_transaction_type,
        fund_source = p_fund_source,
        particulars = p_particulars,
        transaction_date = p_transaction_date,
        last_log_by = p_last_log_by
    WHERE disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE updateDisbursementParticulars(IN p_disbursement_particulars_id INT, IN p_disbursement_id INT, IN p_chart_of_account_id INT, IN p_company_id INT, IN p_remarks VARCHAR(100), IN p_particulars_amount DOUBLE, IN p_base_amount DOUBLE, IN p_with_vat VARCHAR(5), IN p_with_withholding VARCHAR(5), IN p_vat_amount DOUBLE, IN p_withholding_amount DOUBLE, IN p_total_amount DOUBLE, IN p_tax_quarter INT, IN p_last_log_by INT)
BEGIN
    UPDATE disbursement_particulars
    SET disbursement_id = p_disbursement_id,
        chart_of_account_id = p_chart_of_account_id,
        company_id = p_company_id,
        remarks = p_remarks,
        particulars_amount = p_particulars_amount,
        base_amount = p_base_amount,
        with_vat = p_with_vat,
        with_withholding = p_with_withholding,
        vat_amount = p_vat_amount,
        withholding_amount = p_withholding_amount,
        total_amount = p_total_amount,
        tax_quarter = p_tax_quarter,
        last_log_by = p_last_log_by
    WHERE disbursement_particulars_id = p_disbursement_particulars_id;
END //

CREATE PROCEDURE updateDisbursementCheck(IN p_disbursement_check_id INT, IN p_disbursement_id INT, IN p_bank_branch VARCHAR(100), IN p_check_name VARCHAR(5000), IN p_check_number VARCHAR(100), IN p_check_date DATE, IN p_check_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    UPDATE disbursement_check
    SET disbursement_id = p_disbursement_id,
        bank_branch = p_bank_branch,
        check_name = p_check_name,
        check_number = p_check_number,
        check_date = p_check_date,
        check_amount = p_check_amount,
        last_log_by = p_last_log_by
    WHERE disbursement_check_id = p_disbursement_check_id;
END //

CREATE PROCEDURE cancelAllDisbursementCheck(IN p_disbursement_id INT, IN p_reason VARCHAR(100), IN p_last_log_by INT)
BEGIN
    UPDATE disbursement_check
    SET reversal_date = NOW(),
        check_status = 'Cancelled',
        reversal_reason = p_reason,
        last_log_by = p_last_log_by
    WHERE disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE updateLiquidationBalance(IN p_liquidation_id INT, IN p_particulars_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    UPDATE liquidation
    SET remaining_balance = (remaining_balance - p_particulars_amount),
        last_log_by = p_last_log_by
    WHERE liquidation_id = p_liquidation_id;
END //

CREATE PROCEDURE deleteLiquidationBalance(IN p_liquidation_id INT, IN p_particulars_amount DOUBLE, IN p_last_log_by INT)
BEGIN
    UPDATE liquidation
    SET remaining_balance = (remaining_balance + p_particulars_amount),
        last_log_by = p_last_log_by
    WHERE liquidation_id = p_liquidation_id;
END //

CREATE PROCEDURE deleteDisbursement(IN p_disbursement_id INT)
BEGIN
    DELETE FROM disbursement WHERE disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE deleteDisbursementParticulars(IN p_disbursement_particulars_id INT)
BEGIN
    DELETE FROM disbursement_particulars WHERE disbursement_particulars_id = p_disbursement_particulars_id;
END //

CREATE PROCEDURE deleteDisbursementCheck(IN p_disbursement_check_id INT)
BEGIN
    DELETE FROM disbursement_check WHERE disbursement_check_id = p_disbursement_check_id;
END //

CREATE PROCEDURE deleteLiquidationParticulars(IN p_liquidation_particulars_id INT)
BEGIN
    DELETE FROM liquidation_particulars WHERE liquidation_particulars_id = p_liquidation_particulars_id;
END //

CREATE PROCEDURE getDisbursement(IN p_disbursement_id INT)
BEGIN
	SELECT * FROM disbursement
    WHERE disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE getLiquidation(IN p_liquidation_id INT)
BEGIN
	SELECT * FROM liquidation
    WHERE liquidation_id = p_liquidation_id;
END //

CREATE PROCEDURE getDisbursementParticulars(IN p_disbursement_particulars_id INT)
BEGIN
	SELECT * FROM disbursement_particulars
    WHERE disbursement_particulars_id = p_disbursement_particulars_id;
END //

CREATE PROCEDURE getDisbursementCheck(IN p_disbursement_check_id INT)
BEGIN
	SELECT * FROM disbursement_check
    WHERE disbursement_check_id = p_disbursement_check_id;
END //

CREATE PROCEDURE getLiquidationParticulars(IN p_liquidation_particulars_id INT)
BEGIN
	SELECT * FROM liquidation_particulars
    WHERE liquidation_particulars_id = p_liquidation_particulars_id;
END //

CREATE PROCEDURE getDisbursementTotal(IN p_disbursement_id INT)
BEGIN
	SELECT SUM((base_amount + vat_amount) - withholding_amount) AS total FROM disbursement_particulars
    WHERE disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE getDisbursementCheckTotal(IN p_disbursement_id INT)
BEGIN
	SELECT SUM(check_amount) AS total FROM disbursement_check
    WHERE disbursement_id = p_disbursement_id AND check_status NOT IN ('Cancelled');
END //

CREATE PROCEDURE getDisbursementCheckCount(IN p_disbursement_id INT)
BEGIN
	SELECT COUNT(disbursement_check_id) AS total FROM disbursement_check
    WHERE disbursement_id = p_disbursement_id AND check_status NOT IN ('Cancelled');
END //

CREATE PROCEDURE getDisbursementNegotiatedCheckTotal(IN p_disbursement_id INT)
BEGIN
	SELECT SUM(check_amount) AS total FROM disbursement_check
    WHERE disbursement_id = p_disbursement_id AND check_status = 'Negotiated';
END //

CREATE PROCEDURE getUnreplishedDisbursement(IN p_fund_source VARCHAR(100))
BEGIN
	SELECT SUM(particulars_amount) AS total FROM disbursement_particulars
    WHERE disbursement_id IN (SELECT disbursement_id FROM disbursement WHERE fund_source = p_fund_source AND disburse_status = 'Posted' AND transaction_type != 'Replenishment');
END //

CREATE PROCEDURE generateDisbursementTable( IN p_transaction_start_date DATE, IN p_transaction_end_date DATE, IN p_replenishment_start_date DATE, IN p_replenishment_end_date DATE, IN p_fund_source_filter VARCHAR(100), IN p_disbursement_status VARCHAR(100), IN p_transaction_type VARCHAR(100))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM disbursement';
    SET conditionList = ' WHERE fund_source != "Check"';

    IF p_fund_source_filter IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND fund_source = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_fund_source_filter));
    END IF;

    IF p_disbursement_status IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND disburse_status = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_disbursement_status));
    END IF;

    IF p_transaction_type IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND transaction_type = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_type));
    END IF;
    
    IF p_transaction_start_date IS NOT NULL AND p_transaction_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transaction_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_replenishment_start_date IS NOT NULL AND p_replenishment_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (replenishment_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_replenishment_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_replenishment_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY transaction_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateCheckDisbursementTable( IN p_transaction_start_date DATE, IN p_transaction_end_date DATE, IN p_fund_source_filter VARCHAR(100), IN p_disbursement_status VARCHAR(100), IN p_transaction_type VARCHAR(100))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM disbursement';
    SET conditionList = ' WHERE fund_source = "Check"';

    IF p_disbursement_status IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND disburse_status = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_disbursement_status));
    END IF;

    IF p_transaction_type IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND transaction_type = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_type));
    END IF;
    
    IF p_transaction_start_date IS NOT NULL AND p_transaction_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transaction_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY transaction_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE getDisbursementTableTotal( IN p_transaction_start_date DATE, IN p_transaction_end_date DATE, IN p_fund_source_filter VARCHAR(100), IN p_disbursement_status VARCHAR(100), IN p_transaction_type VARCHAR(100), IN p_disbursement_category VARCHAR(100))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT SUM(particulars_amount) AS total FROM disbursement_particulars
    WHERE disbursement_id IN (SELECT disbursement_id FROM disbursement';
    SET conditionList = ' WHERE 1';

    IF p_disbursement_category = 'disbursement petty cash' THEN
        IF p_fund_source_filter IS NOT NULL THEN
            SET conditionList = CONCAT(conditionList, ' AND fund_source = ');
            SET conditionList = CONCAT(conditionList, QUOTE(p_fund_source_filter));
        ELSE
            SET conditionList = CONCAT(conditionList, ' AND fund_source != "Check"');
        END IF;
    ELSE
        SET conditionList = CONCAT(conditionList, ' AND fund_source = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_fund_source_filter));
    END IF;
    
    IF p_disbursement_status IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND disburse_status = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_disbursement_status));
    END IF;

    IF p_transaction_type IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND transaction_type = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_type));
    END IF;
    
    IF p_transaction_start_date IS NOT NULL AND p_transaction_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transaction_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transaction_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ')');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateDisbursementCheckMonitoringTable(IN p_check_start_date DATE, IN p_check_end_date DATE, IN p_transmitted_date_start_date DATE, IN p_transmitted_date_end_date DATE, IN p_outstanding_date_start_date DATE, IN p_outstanding_date_end_date DATE, IN p_negotiated_date_start_date DATE, IN p_negotiated_date_end_date DATE, IN p_check_status VARCHAR(100))
BEGIN
    DECLARE query VARCHAR(5000);
    DECLARE conditionList VARCHAR(1000);

    SET query = 'SELECT * FROM disbursement_check 
    JOIN disbursement ON disbursement.disbursement_id = disbursement_check.disbursement_id';
    SET conditionList = ' WHERE 1';

    IF p_check_status IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND check_status = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_status));
    END IF;
    
    IF p_check_start_date IS NOT NULL AND p_check_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (check_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_check_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_transmitted_date_start_date IS NOT NULL AND p_transmitted_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (transmitted_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transmitted_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_transmitted_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_outstanding_date_start_date IS NOT NULL AND p_outstanding_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (outstanding_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_outstanding_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_outstanding_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;
    
    IF p_negotiated_date_start_date IS NOT NULL AND p_negotiated_date_end_date IS NOT NULL THEN
        SET conditionList = CONCAT(conditionList, ' AND (negotiated_date BETWEEN ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_negotiated_date_start_date));
        SET conditionList = CONCAT(conditionList, ' AND ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_negotiated_date_end_date));
        SET conditionList = CONCAT(conditionList, ')');
    END IF;

    SET query = CONCAT(query, conditionList);
    SET query = CONCAT(query, ' ORDER BY check_date DESC;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateDisbursementParticularsTable( IN p_disbursement_id INT)
BEGIN
    SELECT * FROM disbursement_particulars WHERE 
    disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE generateDisbursementParticularsEntryTable(IN p_disbursement_id VARCHAR(5000))
BEGIN
    SET @sql = CONCAT('
        SELECT journal_item, SUM(debit) AS debit, SUM(credit) AS credit 
        FROM journal_entry 
        WHERE loan_number IN (
            SELECT disbursement_id 
            FROM disbursement 
            WHERE disbursement_id IN (', p_disbursement_id, ') 
            AND fund_source = ''Petty Cash'' 
            AND (disburse_status = ''Posted'' OR disburse_status = ''Replenished'')
        )  
        AND journal_id = ''Disbursement Operations'' 
        GROUP BY journal_item
    ');

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateDisbursementCheckTable( IN p_disbursement_id INT)
BEGIN
    SELECT * FROM disbursement_check WHERE 
    disbursement_id = p_disbursement_id;
END //

CREATE PROCEDURE generateLiquidationParticularsTable( IN p_liquidation_id INT)
BEGIN
    SELECT * FROM liquidation_particulars WHERE 
    liquidation_id = p_liquidation_id AND reference_type NOT IN ('OR', 'CDV');
END //

CREATE PROCEDURE generateLiquidationAddOnParticularsTable(IN p_liquidation_id INT)
BEGIN
    SELECT * FROM liquidation_particulars WHERE 
    liquidation_id = p_liquidation_id AND reference_type IN ('OR', 'CDV');
END //

CREATE PROCEDURE updateDisbursementStatus(IN p_disbursement_id INT, IN p_disburse_status VARCHAR(50), IN p_reason VARCHAR(100), IN p_last_log_by INT)
BEGIN
    IF p_disburse_status = 'Posted' THEN
        UPDATE disbursement
        SET disburse_status = p_disburse_status,
        posted_date = (SELECT transaction_date FROM disbursement WHERE disbursement_id = p_disbursement_id),
        last_log_by = p_last_log_by
        WHERE disbursement_id = p_disbursement_id AND disburse_status IN('Draft');
    ELSEIF p_disburse_status = 'Cancelled' THEN
        UPDATE disbursement
        SET disburse_status = p_disburse_status,
        cancellation_date = NOW(),
        cancellation_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE disbursement_id = p_disbursement_id AND disburse_status IN('Draft');
    ELSEIF p_disburse_status = 'Reversed' THEN
        UPDATE disbursement
        SET disburse_status = p_disburse_status,
        reversal_date = NOW(),
        reversal_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE disbursement_id = p_disbursement_id AND disburse_status IN('Posted');
    ELSEIF p_disburse_status = 'Replenished' THEN
        UPDATE disbursement
        SET disburse_status = p_disburse_status,
        replenishment_batch = p_reason,
        replenishment_date = NOW(),
        last_log_by = p_last_log_by
        WHERE disbursement_id = p_disbursement_id AND transaction_type != 'Replenishment' AND disburse_status IN('Posted');
    ELSE
        UPDATE disbursement
        SET disburse_status = p_disburse_status,
        last_log_by = p_last_log_by
        WHERE disbursement_id = p_disbursement_id;
    END IF;
END //

CREATE PROCEDURE updateDisbursementCheckStatus(IN p_disbursement_check_id INT, IN p_check_status VARCHAR(50), IN p_reason VARCHAR(100), IN p_negotiated_date DATETIME, IN p_last_log_by INT)
BEGIN
    IF p_check_status = 'Transmitted' THEN
        UPDATE disbursement_check
        SET check_status = p_check_status,
        transmitted_date = NOW(),
        last_log_by = p_last_log_by
        WHERE disbursement_check_id = p_disbursement_check_id AND check_status IN ('Draft');
    ELSEIF p_check_status = 'Outstanding' THEN
        UPDATE disbursement_check
        SET check_status = p_check_status,
        outstanding_date = NOW(),
        last_log_by = p_last_log_by
        WHERE disbursement_check_id = p_disbursement_check_id AND check_status IN ('Transmitted');
    ELSEIF p_check_status = 'Outstanding PDC' THEN
        UPDATE disbursement_check
        SET check_status = p_check_status,
        outstanding_date = NOW(),
        last_log_by = p_last_log_by
        WHERE disbursement_check_id = p_disbursement_check_id AND check_status IN ('Transmitted');
    ELSEIF p_check_status = 'Negotiated' THEN
        UPDATE disbursement_check
        SET check_status = p_check_status,
        negotiated_date = p_negotiated_date,
        last_log_by = p_last_log_by
        WHERE disbursement_check_id = p_disbursement_check_id AND check_status IN ('Outstanding', 'Outstanding PDC');
    ELSE
        UPDATE disbursement_check
        SET check_status = p_check_status,
        reversal_date = NOW(),
        reversal_reason = p_reason,
        last_log_by = p_last_log_by
        WHERE disbursement_check_id = p_disbursement_check_id AND check_status IN ('Draft', 'Transmitted', 'Outstanding', 'Outstanding PDC');
    END IF;
END //

DELIMITER //

DROP PROCEDURE IF EXISTS createDisbursementEntry //
CREATE PROCEDURE createDisbursementEntry(
    IN p_disbursement_id INT,
    IN p_transaction_number VARCHAR(100),
    IN p_fund_source VARCHAR(100),
    IN p_transaction_type VARCHAR(100),
    IN p_transaction_date DATE,
    IN p_last_log_by INT
)
BEGIN
    -- Declare variables
    DECLARE v_analytic_lines VARCHAR(500);
    DECLARE v_analytic_distribution VARCHAR(500);
    DECLARE v_journal_id VARCHAR(500);
    DECLARE v_credit VARCHAR(500);
    DECLARE v_chart_item VARCHAR(600);
    DECLARE v_base_amount DOUBLE;
    DECLARE v_company_id INT;
    DECLARE v_with_vat VARCHAR(10);
    DECLARE v_with_withholding VARCHAR(10);
    DECLARE v_vat_amount DOUBLE;
    DECLARE v_withholding_amount DOUBLE;
    DECLARE v_done INT DEFAULT 0;

    -- Cursor for disbursement particulars
    DECLARE cur_particulars CURSOR FOR
        SELECT dp.base_amount, dp.company_id,
               dp.with_vat, dp.with_withholding, dp.vat_amount, dp.withholding_amount,
               CONCAT(ca.code, ' ', ca.name) AS chart_item
        FROM disbursement_particulars dp
        JOIN chart_of_account ca ON dp.chart_of_account_id = ca.chart_of_account_id
        WHERE dp.disbursement_id = p_disbursement_id;

    -- Continue handler for cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    -- Determine credit account based on fund source
    CASE p_fund_source
        WHEN 'Petty Cash' THEN
            SET v_credit = '10101030 Petty Cash Fund';
            SET v_journal_id = 'Disbursement Operations';
        WHEN 'Revolving Fund' THEN
            SET v_credit = '10101020 Revolving Fund';
            SET v_journal_id = 'Disbursement Operations';
        ELSE
            SET v_credit = '10102060 Cash in Bank - Checking BPI CGMI';
            SET v_journal_id = 'Check Disbursement Operations';
    END CASE;

    -- Open cursor
    OPEN cur_particulars;

    read_loop: LOOP
        FETCH cur_particulars INTO v_base_amount, v_company_id, v_with_vat, v_with_withholding, v_vat_amount, v_withholding_amount, v_chart_item;

        -- Exit loop if cursor is done
        IF v_done THEN
            LEAVE read_loop;
        END IF;

        -- Determine analytic lines based on company_id
        CASE v_company_id
            WHEN 1 THEN
                SET v_analytic_lines = 'CGMI';
                SET v_analytic_distribution = '{"1": 100.0}';
            WHEN 2 THEN
                SET v_analytic_lines = 'NE TRUCK';
                SET v_analytic_distribution = '{"2": 100.0}';
            WHEN 3 THEN
                SET v_analytic_lines = 'FUSO';
                SET v_analytic_distribution = '{"5": 100.0}';
            WHEN 4 THEN
                SET v_analytic_lines = 'PCG PROPERTY';
                SET v_analytic_distribution = '{"4": 100.0}';
            WHEN 5 THEN
                SET v_analytic_lines = 'GCB PROPERTY';
                SET v_analytic_distribution = '{"3": 100.0}';
            ELSE
                SET v_analytic_lines = 'DEFAULT';
                SET v_analytic_distribution = '{"0": 0.0}';
        END CASE;

        -- Insert the standard journal entries
        IF v_base_amount > 0 THEN
            -- Insert debit entry
            INSERT INTO journal_entry (
                loan_number, journal_entry_date, reference_code, journal_id, journal_item, debit, credit, 
                journal_label, analytic_lines, analytic_distribution, created_date, last_log_by
            ) VALUES (
                p_disbursement_id, NOW(), p_transaction_number, v_journal_id, v_chart_item, v_base_amount, 0, 
                '', v_analytic_lines, v_analytic_distribution, NOW(), p_last_log_by
            );

            -- Insert credit entry
            INSERT INTO journal_entry (
                loan_number, journal_entry_date, reference_code, journal_id, journal_item, debit, credit, 
                journal_label, analytic_lines, analytic_distribution, created_date, last_log_by
            ) VALUES (
                p_disbursement_id, NOW(), p_transaction_number, v_journal_id, v_credit, 0, v_base_amount, 
                '', v_analytic_lines, v_analytic_distribution, NOW(), p_last_log_by
            );

        END IF;

        -- Insert VAT Journal Entries if applicable
        IF v_with_vat = 'Yes' AND v_vat_amount > 0 THEN
            -- Debit VAT (Input Tax)
            INSERT INTO journal_entry (
                loan_number, journal_entry_date, reference_code, journal_id, journal_item, debit, credit, 
                journal_label, analytic_lines, analytic_distribution, created_date, last_log_by
            ) VALUES (
                p_disbursement_id, NOW(), p_transaction_number, v_journal_id, '19902050 Input Tax', v_vat_amount, 0, 
                '', v_analytic_lines, v_analytic_distribution, NOW(), p_last_log_by
            );

            -- Credit VAT to the expense account
            INSERT INTO journal_entry (
                loan_number, journal_entry_date, reference_code, journal_id, journal_item, debit, credit, 
                journal_label, analytic_lines, analytic_distribution, created_date, last_log_by
            ) VALUES (
                p_disbursement_id, NOW(), p_transaction_number, v_journal_id, v_credit, 0, v_vat_amount, 
                '', v_analytic_lines, v_analytic_distribution, NOW(), p_last_log_by
            );
        END IF;

        -- Insert Withholding Tax Journal Entries if applicable
        IF v_with_withholding <> 'No' AND v_withholding_amount > 0 THEN
            -- Debit Withholding
            INSERT INTO journal_entry (
                loan_number, journal_entry_date, reference_code, journal_id, journal_item, debit, credit, 
                journal_label, analytic_lines, analytic_distribution, created_date, last_log_by
            ) VALUES (
                p_disbursement_id, NOW(), p_transaction_number, v_journal_id, v_credit, v_withholding_amount, 0, 
                '', v_analytic_lines, v_analytic_distribution, NOW(), p_last_log_by
            );

            -- Credit Withholding Tax Payable
            INSERT INTO journal_entry (
                loan_number, journal_entry_date, reference_code, journal_id, journal_item, debit, credit, 
                journal_label, analytic_lines, analytic_distribution, created_date, last_log_by
            ) VALUES (
                p_disbursement_id, NOW(), p_transaction_number, v_journal_id, '20101132 Withholding Tax Payable Other', 0, v_withholding_amount,  
                '', v_analytic_lines, v_analytic_distribution, NOW(), p_last_log_by
            );
        END IF;

    END LOOP;

    -- Close cursor
    CLOSE cur_particulars;
END//


CREATE PROCEDURE createDisbursementEntry(
    IN p_disbursement_id INT,
    IN p_transaction_number VARCHAR(100),
    IN p_fund_source VARCHAR(100),
    IN p_transaction_type VARCHAR(100),
    IN p_transaction_date DATE,
    IN p_last_log_by INT
)
BEGIN
    -- Declare variables
    DECLARE v_analytic_lines VARCHAR(500);
    DECLARE v_analytic_distribution VARCHAR(500);
    DECLARE v_journal_id VARCHAR(500);
    DECLARE v_credit VARCHAR(500);
    DECLARE v_chart_item VARCHAR(600);
    DECLARE v_particulars_amount DOUBLE;
    DECLARE v_company_id INT;
    DECLARE v_done INT DEFAULT 0;

    -- Cursor for disbursement particulars
    DECLARE cur_particulars CURSOR FOR
        SELECT dp.particulars_amount, dp.company_id,
               CONCAT(ca.code, ' ', ca.name) AS chart_item
        FROM disbursement_particulars dp
        JOIN chart_of_account ca ON dp.chart_of_account_id = ca.chart_of_account_id
        WHERE dp.disbursement_id = p_disbursement_id;

    -- Continue handler for cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    -- Determine credit account based on fund source
    CASE p_fund_source
        WHEN 'Petty Cash' THEN
            SET v_credit = '10101030 Petty Cash Fund';
            SET v_journal_id = 'Disbursement Operations';
        WHEN 'Revolving Fund' THEN
            SET v_credit = '10101020 Revolving Fund';
            SET v_journal_id = 'Disbursement Operations';
        ELSE
            SET v_credit = '10102060 Cash in Bank - Checking BPI CGMI';
            SET v_journal_id = 'Check Disbursement Operations';
    END CASE;

    -- Open cursor
    OPEN cur_particulars;

    read_loop: LOOP
        FETCH cur_particulars INTO v_particulars_amount, v_company_id, v_chart_item;

        CASE v_company_id
            WHEN 1 THEN
                SET v_analytic_lines = 'CGMI';
                SET v_analytic_distribution = '{"1": 100.0}';
            WHEN 2 THEN
                SET v_analytic_lines = 'NE TRUCK';
                SET v_analytic_distribution = '{"2": 100.0}';
            WHEN 3 THEN
                SET v_analytic_lines = 'FUSO';
                SET v_analytic_distribution = '{"5": 100.0}';
            WHEN 4 THEN
                SET v_analytic_lines = 'PCG PROPERTY';
                SET v_analytic_distribution = '{"4": 100.0}';
            WHEN 5 THEN
                SET v_analytic_lines = 'GCB PROPERTY';
                SET v_analytic_distribution = '{"3": 100.0}';
            ELSE
                SET v_analytic_lines = 'DEFAULT';
                SET v_analytic_distribution = '{"0": 0.0}';
        END CASE;

        -- Exit loop if cursor is done
        IF v_done THEN
            LEAVE read_loop;
        END IF;

        IF v_particulars_amount > 0 THEN
            -- Insert the 2 journal entries per particular
            IF p_transaction_type = 'posted' THEN
                -- Insert debit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    NOW(), 
                    p_transaction_number, 
                    v_journal_id, 
                    v_chart_item, 
                    v_particulars_amount, 
                    0, 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );

                -- Insert credit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    NOW(), 
                    p_transaction_number, 
                    v_journal_id, 
                    v_credit, 
                    0, 
                    v_particulars_amount, 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );

            ELSE
                -- Insert debit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    NOW(), 
                    p_transaction_number, 
                    v_journal_id, 
                    v_chart_item, 
                    0, 
                    v_particulars_amount, 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );

                -- Insert credit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    NOW(), 
                    p_transaction_number, 
                    v_journal_id, 
                    v_credit, 
                    v_particulars_amount, 
                    0, 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );
            END IF;
        ELSE
        -- Insert the 2 journal entries per particular
            IF p_transaction_type = 'posted' THEN
                -- Insert debit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    p_transaction_date, 
                    p_transaction_number, 
                    v_journal_id, 
                    v_chart_item, 
                    0, 
                    ABS(v_particulars_amount), 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );

                -- Insert credit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    p_transaction_date, 
                    p_transaction_number, 
                    v_journal_id, 
                    v_credit, 
                    ABS(v_particulars_amount), 
                    0, 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );

            ELSE
            -- Insert debit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    p_transaction_date, 
                    p_transaction_number, 
                    v_journal_id, 
                    v_chart_item, 
                    ABS(v_particulars_amount), 
                    0, 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );

                -- Insert credit entry
                INSERT INTO journal_entry (
                    loan_number, 
                    journal_entry_date, 
                    reference_code, 
                    journal_id, 
                    journal_item, 
                    debit, 
                    credit, 
                    journal_label, 
                    analytic_lines, 
                    analytic_distribution, 
                    created_date, 
                    last_log_by
                ) VALUES (
                    p_disbursement_id, 
                    p_transaction_date, 
                    p_transaction_number, 
                    v_journal_id, 
                    v_credit, 
                    0, 
                    ABS(v_particulars_amount), 
                    '', 
                    v_analytic_lines, 
                    v_analytic_distribution, 
                    NOW(), 
                    p_last_log_by
                );

                
            END IF;
        END IF;

    END LOOP;

    -- Close cursor
    CLOSE cur_particulars;
END//

DELIMITER //
DROP PROCEDURE createLiquidation//
CREATE PROCEDURE createLiquidation(
    IN p_disbursement_id INT,
    IN p_transaction_date DATE,
    IN p_last_log_by INT,
    IN p_created_by INT
)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_disbursement_particulars_id INT;
    DECLARE v_particulars_amount DOUBLE;

    -- Cursor to fetch disbursement_particulars data
    DECLARE cur CURSOR FOR 
        SELECT disbursement_particulars_id, particulars_amount
        FROM disbursement_particulars 
        WHERE chart_of_account_id IN (565, 567, 452)
        AND disbursement_id IN (SELECT disbursement_id FROM disbursement WHERE disbursement_id = p_disbursement_id AND disburse_status = 'Posted');

    -- Handler to exit the loop
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    fetch_loop: LOOP
        FETCH cur INTO v_disbursement_particulars_id, v_particulars_amount;
        IF done THEN
            LEAVE fetch_loop;
        END IF;

        -- Insert into liquidation table
        INSERT INTO liquidation (
            disbursement_particulars_id, 
            disbursement_id, 
            remaining_balance, 
            transaction_date, 
            created_by, 
            last_log_by
        )
        VALUES (
            v_disbursement_particulars_id,
            p_disbursement_id,
            v_particulars_amount,
            p_transaction_date,
            p_created_by,
            p_last_log_by
        );
    END LOOP;

    CLOSE cur;
END //

CREATE PROCEDURE generateLiquidationTable()
BEGIN
    SELECT * FROM liquidation;
END //

CREATE PROCEDURE insertLiquidationParticulars(IN p_liquidation_id INT, IN p_chart_of_account_id INT, IN p_particulars_amount DOUBLE, IN p_reference_type VARCHAR(100), IN p_reference_number VARCHAR(100), IN p_remarks VARCHAR(5000), IN p_last_log_by INT)
BEGIN
    INSERT INTO liquidation_particulars (liquidation_id, chart_of_account_id, particulars_amount, reference_type, reference_number, remarks, created_by, last_log_by) 
	VALUES(p_liquidation_id, p_chart_of_account_id, p_particulars_amount, p_reference_type, p_reference_number, p_remarks, p_last_log_by, p_last_log_by);
END //

CREATE PROCEDURE updateLiquidationParticulars(IN p_liquidation_particulars_id INT, IN p_liquidation_id INT, IN p_chart_of_account_id INT, IN p_particulars_amount DOUBLE, IN p_reference_type VARCHAR(100), IN p_reference_number VARCHAR(100), IN p_remarks VARCHAR(5000), IN p_last_log_by INT)
BEGIN
    UPDATE liquidation_particulars
    SET liquidation_id = p_liquidation_id,
        chart_of_account_id = p_chart_of_account_id,
        particulars_amount = p_particulars_amount,
        reference_type = p_reference_type,
        reference_number = p_reference_number,
        remarks = p_remarks,
        last_log_by = p_last_log_by
    WHERE liquidation_particulars_id = p_liquidation_particulars_id;
END //

CREATE PROCEDURE updateLiquidationParticularsStatus(IN p_liquidation_particulars_id INT, IN p_liquidation_particulars_status VARCHAR(50), IN p_last_log_by INT)
BEGIN
    IF p_liquidation_particulars_status = 'Posted' THEN
        UPDATE liquidation_particulars
        SET liquidation_particulars_status = p_liquidation_particulars_status,
        posted_date = NOW(),
        last_log_by = p_last_log_by
        WHERE liquidation_particulars_id = p_liquidation_particulars_id AND liquidation_particulars_status IN('Draft');
    ELSEIF p_liquidation_particulars_status = 'Reversed' THEN
        UPDATE liquidation_particulars
        SET liquidation_particulars_status = p_liquidation_particulars_status,
        reversal_date = NOW(),
        last_log_by = p_last_log_by
        WHERE liquidation_particulars_id = p_liquidation_particulars_id AND liquidation_particulars_status IN('Posted');
    ELSE
        UPDATE liquidation_particulars
        SET liquidation_particulars_status = p_liquidation_particulars_status,
        last_log_by = p_last_log_by
        WHERE liquidation_particulars_id = p_liquidation_particulars_id;
    END IF;
END //

DROP PROCEDURE IF EXISTS createLiquidationParticularsEntry //
CREATE PROCEDURE createLiquidationParticularsEntry(
    IN p_liquidation_particulars_id INT,
    IN p_transaction_number VARCHAR(100),
    IN p_company_id INT,
    IN p_chart_of_account_id INT,
    IN p_transaction_type VARCHAR(100), -- Added transaction type
    IN p_last_log_by INT
)
BEGIN
    -- Declare variables
    DECLARE v_analytic_lines VARCHAR(500);
    DECLARE v_analytic_distribution VARCHAR(500);
    DECLARE v_credit VARCHAR(500);
    DECLARE v_chart_item VARCHAR(600);
    DECLARE v_particulars_amount DOUBLE;
    DECLARE v_done INT DEFAULT 0;

    -- Cursor for disbursement particulars
    DECLARE cur_particulars CURSOR FOR
        SELECT dp.particulars_amount, 
               CONCAT(ca.code, ' ', ca.name) AS chart_item
        FROM liquidation_particulars dp
        JOIN chart_of_account ca ON dp.chart_of_account_id = ca.chart_of_account_id
        WHERE dp.liquidation_particulars_id = p_liquidation_particulars_id;

    -- Continue handler for cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    CASE p_company_id
        WHEN 1 THEN
            SET v_analytic_lines = 'CGMI';
            SET v_analytic_distribution = '{"1": 100.0}';
        WHEN 2 THEN
            SET v_analytic_lines = 'NE TRUCK';
            SET v_analytic_distribution = '{"2": 100.0}';
        WHEN 3 THEN
            SET v_analytic_lines = 'FUSO';
            SET v_analytic_distribution = '{"5": 100.0}';
        WHEN 4 THEN
            SET v_analytic_lines = 'PCG PROPERTY';
            SET v_analytic_distribution = '{"4": 100.0}';
        WHEN 5 THEN
            SET v_analytic_lines = 'GCB PROPERTY';
            SET v_analytic_distribution = '{"3": 100.0}';
        ELSE
            SET v_analytic_lines = 'DEFAULT';
            SET v_analytic_distribution = '{"0": 0.0}';
    END CASE;

    -- Determine credit account based on fund source
    CASE p_chart_of_account_id
        WHEN 567 THEN
            SET v_credit = '19901030 Advances to Contractors';
        WHEN 565 THEN
            SET v_credit = '19901020 Advances to Employee';
        ELSE
            SET v_credit = '10303990 Other Receivable';
    END CASE;

    -- Open cursor
    OPEN cur_particulars;

    read_loop: LOOP
        FETCH cur_particulars INTO v_particulars_amount, v_chart_item;

        -- Exit loop if cursor is done
        IF v_done THEN
            LEAVE read_loop;
        END IF;

        -- Insert the 2 journal entries per particular
        IF p_transaction_type = 'posted' THEN
            -- Insert debit entry
            INSERT INTO journal_entry (
                loan_number, 
                journal_entry_date, 
                reference_code, 
                journal_id, 
                journal_item, 
                debit, 
                credit, 
                journal_label, 
                analytic_lines, 
                analytic_distribution, 
                created_date, 
                last_log_by
            ) VALUES (
                p_liquidation_particulars_id, 
                NOW(), 
                p_transaction_number, 
                'Liquidation Operations', 
                v_chart_item, 
                v_particulars_amount, 
                0, 
                '', 
                v_analytic_lines, 
                v_analytic_distribution, 
                NOW(), 
                p_last_log_by
            );

            -- Insert credit entry
            INSERT INTO journal_entry (
                loan_number, 
                journal_entry_date, 
                reference_code, 
                journal_id, 
                journal_item, 
                debit, 
                credit, 
                journal_label, 
                analytic_lines, 
                analytic_distribution, 
                created_date, 
                last_log_by
            ) VALUES (
                p_liquidation_particulars_id, 
                NOW(), 
                p_transaction_number, 
                'Liquidation Operations', 
                v_credit, 
                0, 
                v_particulars_amount, 
                '', 
                v_analytic_lines, 
                v_analytic_distribution, 
                NOW(), 
                p_last_log_by
            );

        ELSE
            -- Insert debit entry
            INSERT INTO journal_entry (
                loan_number, 
                journal_entry_date, 
                reference_code, 
                journal_id, 
                journal_item, 
                debit, 
                credit, 
                journal_label, 
                analytic_lines, 
                analytic_distribution, 
                created_date, 
                last_log_by
            ) VALUES (
                p_liquidation_particulars_id, 
                NOW(), 
                p_transaction_number, 
                'Liquidation Operations', 
                v_chart_item, 
                0, 
                v_particulars_amount, 
                '', 
                v_analytic_lines, 
                v_analytic_distribution, 
                NOW(), 
                p_last_log_by
            );

            -- Insert credit entry
            INSERT INTO journal_entry (
                loan_number, 
                journal_entry_date, 
                reference_code, 
                journal_id, 
                journal_item, 
                debit, 
                credit, 
                journal_label, 
                analytic_lines, 
                analytic_distribution, 
                created_date, 
                last_log_by
            ) VALUES (
                p_liquidation_particulars_id, 
                NOW(), 
                p_transaction_number, 
                'Liquidation Operations', 
                v_credit, 
                v_particulars_amount, 
                0, 
                '', 
                v_analytic_lines, 
                v_analytic_distribution, 
                NOW(), 
                p_last_log_by
            );
        END IF;

    END LOOP;

    -- Close cursor
    CLOSE cur_particulars;
END//


CREATE PROCEDURE getInventoryReportClosed()
BEGIN
    SELECT count(product_inventory_id) AS total FROM product_inventory WHERE `open_date` is not null and `close_date` is null;
END //

CREATE PROCEDURE generateProductInventoryReport()
BEGIN
    SELECT * FROM product_inventory;
END //

CREATE PROCEDURE generateProductInventoryReportBatch(IN p_product_inventory_id INT)
BEGIN
    SELECT * FROM product_inventory_batch WHERE product_inventory_id = p_product_inventory_id;
END //

DELIMITER //

CREATE PROCEDURE openProductInventoryReport(IN p_last_log_by INT)
BEGIN
    DECLARE new_product_inventory_id INT;
    DECLARE new_batch_number INT;
    
    SET time_zone = '+08:00';
    
    -- Get the next product_inventory_batch number
    SELECT COALESCE(MAX(product_inventory_batch), 0) + 1 INTO new_batch_number FROM product_inventory;

    -- Insert into product_inventory
    INSERT INTO product_inventory (
        product_inventory_batch, 
        open_date, 
        open_by, 
        last_log_by
    ) VALUES (
        new_batch_number, 
        NOW(), 
        p_last_log_by, 
        p_last_log_by
    );

    -- Get the last inserted product_inventory_id
    SET new_product_inventory_id = LAST_INSERT_ID();

    -- Insert into product_inventory_batch for products that are not 'Draft' or 'Sold'
    INSERT INTO product_inventory_batch (
        product_inventory_id, 
        product_id, 
        last_log_by
    )
    SELECT 
        new_product_inventory_id, 
        product_id, 
        p_last_log_by
    FROM product
    WHERE product_status NOT IN ('Draft', 'Sold');

END //

CREATE PROCEDURE closeProductInventoryReport(IN p_last_log_by INT)
BEGIN
SET time_zone = '+08:00';
    UPDATE product_inventory
        SET close_date = NOW(),
        close_by = p_last_log_by,
        last_log_by = p_last_log_by
        WHERE close_date IS NULL;

    UPDATE product_inventory_batch
        SET product_inventory_status = 'Unscanned',
        last_log_by = p_last_log_by
        WHERE product_inventory_status = 'For Scanning';
END //

CREATE PROCEDURE scanProduct(IN p_product_inventory_id INT, IN p_product_id INT, IN p_last_log_by INT)
BEGIN
SET time_zone = '+08:00';

    UPDATE product_inventory_batch
        SET scanned_date = NOW(),
        product_inventory_status = 'Scanned',
        scanned_by = p_last_log_by,
        last_log_by = p_last_log_by
        WHERE product_inventory_id = p_product_inventory_id AND product_id = p_product_id AND product_inventory_status = 'For Scanning';

    INSERT INTO product_inventory_scan_history (
                product_inventory_id, 
                product_id, 
                scanned_date, 
                scanned_by,
                last_log_by
            ) VALUES (
                p_product_inventory_id,
                p_product_id,
                NOW(),
                p_last_log_by,
                p_last_log_by
            );

    INSERT INTO product_inventory_scan_excess (
                product_inventory_id, 
                product_id, 
                scanned_date, 
                scanned_by,
                last_log_by
            ) VALUES (
                p_product_inventory_id,
                p_product_id,
                NOW(),
                p_last_log_by,
                p_last_log_by
            );
END //

CREATE PROCEDURE generateProductInventoryReportScanHistory(IN p_product_inventory_id INT)
BEGIN
    SELECT * FROM product_inventory_scan_history WHERE product_inventory_id = p_product_inventory_id;
END //

CREATE PROCEDURE generateProductInventoryReportScanExcess(IN p_product_inventory_id INT)
BEGIN
    SELECT * FROM product_inventory_scan_excess WHERE product_inventory_id = p_product_inventory_id;
END //

CREATE PROCEDURE generateProductInventoryReportScanAdditional(IN p_product_inventory_id INT)
BEGIN
    SELECT * FROM product_inventory_scan_additional WHERE product_inventory_id = p_product_inventory_id;
END //

CREATE PROCEDURE checkProductInventoryExist (IN p_product_inventory_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product_inventory
    WHERE product_inventory_id = p_product_inventory_id;
END //

CREATE PROCEDURE getProductInventory(IN p_product_inventory_id INT)
BEGIN
	SELECT * FROM product_inventory
    WHERE product_inventory_id = p_product_inventory_id;
END //

CREATE PROCEDURE checkProductInventoryScanAdditionalExist (IN p_product_inventory_scan_additional_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM product_inventory_scan_additional
    WHERE product_inventory_scan_additional_id = p_product_inventory_scan_additional_id;
END //


CREATE PROCEDURE updateProductInventoryScanAdditional(IN p_product_inventory_scan_additional_id INT, IN p_product_inventory_id INT, IN p_stock_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
SET time_zone = '+08:00';

    UPDATE product_inventory_scan_additional
        SET product_inventory_id = p_product_inventory_id,
        stock_number = p_stock_number,
        last_log_by = p_last_log_by
        WHERE product_inventory_scan_additional_id = p_product_inventory_scan_additional_id;
END //

CREATE PROCEDURE productInventoryTagAsMissing(IN p_product_inventory_batch_id INT, IN p_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
SET time_zone = '+08:00';

    UPDATE product_inventory_batch
        SET product_inventory_status = 'Missing',
        remarks = p_remarks,
        last_log_by = p_last_log_by
        WHERE product_inventory_batch_id = p_product_inventory_batch_id AND product_inventory_status = 'For Scanning';
END //

CREATE PROCEDURE insertProductInventoryScanAdditional(IN p_product_inventory_id INT, IN p_stock_number VARCHAR(100), IN p_last_log_by INT)
BEGIN
SET time_zone = '+08:00';

    INSERT INTO product_inventory_scan_additional (
                product_inventory_id, 
                stock_number, 
                added_by, 
                last_log_by
            ) VALUES (
                p_product_inventory_id,
                p_stock_number,
                p_last_log_by,
                p_last_log_by
            );
END //

CREATE PROCEDURE getProductInventoryScanAdditional(IN p_product_inventory_scan_additional_id INT)
BEGIN
	SELECT * FROM product_inventory_scan_additional
    WHERE product_inventory_scan_additional_id = p_product_inventory_scan_additional_id;
END //

CREATE PROCEDURE deleteProductInventoryScanAdditional(IN p_product_inventory_scan_additional_id INT)
BEGIN
    DELETE FROM product_inventory_scan_additional WHERE product_inventory_scan_additional_id = p_product_inventory_scan_additional_id;
END //


/* Contractor Table Stored Procedures */

CREATE PROCEDURE checkContractorExist (IN p_contractor_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM contractor
    WHERE contractor_id = p_contractor_id;
END //

CREATE PROCEDURE insertContractor(IN p_contractor_name VARCHAR(100), IN p_last_log_by INT, OUT p_contractor_id INT)
BEGIN
    INSERT INTO contractor (contractor_name, last_log_by) 
	VALUES(p_contractor_name, p_last_log_by);
	
    SET p_contractor_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateContractor(IN p_contractor_id INT, IN p_contractor_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE contractor
    SET contractor_name = p_contractor_name,
    last_log_by = p_last_log_by
    WHERE contractor_id = p_contractor_id;
END //

CREATE PROCEDURE deleteContractor(IN p_contractor_id INT)
BEGIN
    DELETE FROM contractor WHERE contractor_id = p_contractor_id;
END //

CREATE PROCEDURE getContractor(IN p_contractor_id INT)
BEGIN
	SELECT * FROM contractor
    WHERE contractor_id = p_contractor_id;
END //

CREATE PROCEDURE duplicateContractor(IN p_contractor_id INT, IN p_last_log_by INT, OUT p_new_contractor_id INT)
BEGIN
    DECLARE p_contractor_name VARCHAR(100);
    
    SELECT contractor_name
    INTO p_contractor_name
    FROM contractor 
    WHERE contractor_id = p_contractor_id;
    
    INSERT INTO contractor (contractor_name, last_log_by) 
    VALUES(p_contractor_name, p_last_log_by);
    
    SET p_new_contractor_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateContractorTable()
BEGIN
    SELECT contractor_id, contractor_name
    FROM contractor
    ORDER BY contractor_id;
END //

CREATE PROCEDURE generateContractorOptions()
BEGIN
	SELECT contractor_id, contractor_name FROM contractor
	ORDER BY contractor_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Center Table Stored Procedures */

CREATE PROCEDURE checkWorkCenterExist (IN p_work_center_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM work_center
    WHERE work_center_id = p_work_center_id;
END //

CREATE PROCEDURE insertWorkCenter(IN p_work_center_name VARCHAR(100), IN p_last_log_by INT, OUT p_work_center_id INT)
BEGIN
    INSERT INTO work_center (work_center_name, last_log_by) 
	VALUES(p_work_center_name, p_last_log_by);
	
    SET p_work_center_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateWorkCenter(IN p_work_center_id INT, IN p_work_center_name VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE work_center
    SET work_center_name = p_work_center_name,
    last_log_by = p_last_log_by
    WHERE work_center_id = p_work_center_id;
END //

CREATE PROCEDURE deleteWorkCenter(IN p_work_center_id INT)
BEGIN
    DELETE FROM work_center WHERE work_center_id = p_work_center_id;
END //

CREATE PROCEDURE getWorkCenter(IN p_work_center_id INT)
BEGIN
	SELECT * FROM work_center
    WHERE work_center_id = p_work_center_id;
END //

CREATE PROCEDURE duplicateWorkCenter(IN p_work_center_id INT, IN p_last_log_by INT, OUT p_new_work_center_id INT)
BEGIN
    DECLARE p_work_center_name VARCHAR(100);
    
    SELECT work_center_name
    INTO p_work_center_name
    FROM work_center 
    WHERE work_center_id = p_work_center_id;
    
    INSERT INTO work_center (work_center_name, last_log_by) 
    VALUES(p_work_center_name, p_last_log_by);
    
    SET p_new_work_center_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateWorkCenterTable()
BEGIN
    SELECT work_center_id, work_center_name
    FROM work_center
    ORDER BY work_center_id;
END //

CREATE PROCEDURE generateWorkCenterOptions()
BEGIN
	SELECT work_center_id, work_center_name FROM work_center
	ORDER BY work_center_name;
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE PROCEDURE getJobOrderBackjobCount(IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_job_order
    WHERE progress < 100
    AND backjob = 'No' AND sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE generateJobOrderBackjobOptions()
BEGIN
	SELECT *
    FROM sales_proposal sp
    WHERE EXISTS (
        SELECT 1
        FROM sales_proposal_job_order spjo
        WHERE spjo.sales_proposal_id = sp.sales_proposal_id
        AND spjo.backjob = 'Yes'
        AND spjo.progress < 100
    )
    OR EXISTS (
        SELECT 1
        FROM sales_proposal_additional_job_order spajo
        WHERE spajo.sales_proposal_id = sp.sales_proposal_id
        AND spajo.backjob = 'Yes'
        AND spajo.progress < 100
    );
END //

CREATE PROCEDURE getAdditionalJobOrderBackjobCount(IN p_sales_proposal_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM sales_proposal_additional_job_order
    WHERE progress < 100
    AND backjob = 'No' AND sales_proposal_id = p_sales_proposal_id;
END //

CREATE PROCEDURE generateWorkCenterTable()
BEGIN
    SELECT work_center_id, work_center_name
    FROM work_center
    ORDER BY work_center_id;
END //

---------------------------------------------------------------------------

CREATE PROCEDURE checkBackJobMonitoringExist (IN p_backjob_monitoring_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM backjob_monitoring
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE insertBackJobMonitoring(IN p_type VARCHAR(50), IN p_product_id INT, IN p_sales_proposal_id INT, IN p_last_log_by INT, OUT p_backjob_monitoring_id INT)
BEGIN
    INSERT INTO backjob_monitoring (type, product_id, sales_proposal_id, last_log_by) 
	VALUES(p_type, p_product_id, p_sales_proposal_id, p_last_log_by);
	
    SET p_backjob_monitoring_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBackJobMonitoring(IN p_backjob_monitoring_id INT, IN p_type VARCHAR(50), IN p_product_id INT, IN p_sales_proposal_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE backjob_monitoring
    SET type = p_type,
    product_id = p_product_id,
    sales_proposal_id = p_sales_proposal_id,
    last_log_by = p_last_log_by
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE deleteBackJobMonitoring(IN p_backjob_monitoring_id INT)
BEGIN
    DELETE FROM backjob_monitoring WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE getBackJobMonitoring(IN p_backjob_monitoring_id INT)
BEGIN
	SELECT * FROM backjob_monitoring
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE generateBackJobMonitoringTable()
BEGIN
    SELECT *
    FROM backjob_monitoring
    ORDER BY backjob_monitoring_id;
END //

CREATE PROCEDURE generateBackJobMonitoringOptions()
BEGIN
	SELECT backjob_monitoring_id, type, backjob_monitoring.sales_proposal_id AS sales_proposal_id, sales_proposal_number FROM backjob_monitoring
    LEFT OUTER JOIN sales_proposal ON sales_proposal.sales_proposal_id = backjob_monitoring.sales_proposal_id
    WHERE backjob_monitoring.status = 'For DR' AND backjob_monitoring.type = "Backjob"
	ORDER BY backjob_monitoring.for_dr_date DESC;
END //

CREATE PROCEDURE updateBackJobMonitoringAsReleased(IN p_backjob_monitoring_id INT, IN p_status VARCHAR(100), IN p_released_remarks VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE backjob_monitoring
    SET status = p_status,
    released_remarks = p_released_remarks,
    release_date = NOW(),
    last_log_by = p_last_log_by
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE updateBackJobMonitoringAsCancelled(IN p_backjob_monitoring_id INT, IN p_status VARCHAR(100), IN p_cancellation_reason VARCHAR(500), IN p_last_log_by INT)
BEGIN
	UPDATE backjob_monitoring
    SET status = p_status,
    cancellation_reason = p_cancellation_reason,
    cancellation_date = NOW(),
    last_log_by = p_last_log_by
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE updateBackJobMonitoringAsOnProcess(IN p_backjob_monitoring_id INT, IN p_status VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE backjob_monitoring
    SET status = p_status,
    on_process_date = NOW(),
    last_log_by = p_last_log_by
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE updateBackJobMonitoringAsReadyForRelease(IN p_backjob_monitoring_id INT, IN p_status VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE backjob_monitoring
    SET status = p_status,
    ready_for_release_date = NOW(),
    last_log_by = p_last_log_by
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE updateBackJobMonitoringAsForDR(IN p_backjob_monitoring_id INT, IN p_status VARCHAR(100), IN p_last_log_by INT)
BEGIN
	UPDATE backjob_monitoring
    SET status = p_status,
    for_dr_date = NOW(),
    last_log_by = p_last_log_by
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE updateBackJobMonitoringUnitImage(IN p_backjob_monitoring_id INT, IN p_unit_image VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE backjob_monitoring
        SET unit_image = p_unit_image,
        last_log_by = p_last_log_by
        WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE updateBackJobMonitoringOutgoingChecklist(IN p_backjob_monitoring_id INT, IN p_outgoing_checklist VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE backjob_monitoring
        SET outgoing_checklist = p_outgoing_checklist,
        last_log_by = p_last_log_by
        WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE updateBackJobMonitoringQualityControlForm(IN p_backjob_monitoring_id INT, IN p_quality_control_form VARCHAR(500), IN p_last_log_by INT)
BEGIN
      UPDATE backjob_monitoring
        SET quality_control_form = p_quality_control_form,
        last_log_by = p_last_log_by
        WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE loadBackJobMonitoringJobOrder(
    IN p_backjob_monitoring_id INT, 
    IN p_sales_proposal_id INT,
    IN p_last_log_by INT
)
BEGIN
    -- Delete existing records for the given internal_dr_id
    DELETE FROM backjob_monitoring_job_order 
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;

    DELETE FROM backjob_monitoring_additional_job_order 
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;

    -- Insert job orders from sales proposal
    INSERT INTO backjob_monitoring_job_order (backjob_monitoring_id, sales_proposal_id, job_order_id, job_order, progress, contractor_id, work_center_id, last_log_by)
    SELECT 
        p_backjob_monitoring_id, 
        sales_proposal_id, 
        sales_proposal_job_order_id, 
        job_order, 
        progress, 
        contractor_id, 
        work_center_id,
        p_last_log_by
    FROM sales_proposal_job_order
    WHERE sales_proposal_id = p_sales_proposal_id AND backjob = "Yes" AND progress < 100;

    -- Insert additional job orders from sales proposal
    INSERT INTO backjob_monitoring_additional_job_order (backjob_monitoring_id, sales_proposal_id, additional_job_order_id, job_order_number, job_order_date, particulars, progress, contractor_id, work_center_id, last_log_by)
    SELECT 
        p_backjob_monitoring_id, 
        sales_proposal_id, 
        sales_proposal_additional_job_order_id, 
        job_order_number, 
        job_order_date, 
        particulars, 
        progress, 
        contractor_id, 
        work_center_id,
        p_last_log_by
    FROM sales_proposal_additional_job_order
    WHERE sales_proposal_id = p_sales_proposal_id AND backjob = "Yes" AND progress < 100;
END //

CREATE PROCEDURE updateBackJobMonitoringJobOrder(
    IN p_backjob_monitoring_id INT, 
    IN p_backjob_monitoring_job_order_id INT, 
    IN p_progress DOUBLE, 
    IN p_contractor_id INT, 
    IN p_work_center_id INT,  
    IN p_completion_date DATE, 
    IN p_cost DOUBLE, 
    IN p_job_order VARCHAR(500), 
    IN p_last_log_by INT
)
BEGIN
    DECLARE record_exists INT;

    -- Check if the record exists
    SELECT COUNT(*) INTO record_exists 
    FROM backjob_monitoring_job_order 
    WHERE backjob_monitoring_job_order_id = p_backjob_monitoring_job_order_id;

    IF record_exists > 0 THEN
        -- Update existing record
        UPDATE backjob_monitoring_job_order
        SET 
            progress = p_progress,
            contractor_id = p_contractor_id,
            work_center_id = p_work_center_id,
            completion_date = p_completion_date,
            cost = p_cost,
            job_order = p_job_order,
            last_log_by = p_last_log_by
        WHERE backjob_monitoring_job_order_id = p_backjob_monitoring_job_order_id;
    ELSE
        -- Insert new record if not exists
        INSERT INTO backjob_monitoring_job_order (
            backjob_monitoring_job_order_id, 
            backjob_monitoring_id, 
            progress, 
            contractor_id, 
            work_center_id, 
            completion_date, 
            cost, 
            job_order, 
            last_log_by
        ) VALUES (
            p_backjob_monitoring_job_order_id, 
            p_backjob_monitoring_id, 
            p_progress, 
            p_contractor_id, 
            p_work_center_id, 
            p_completion_date, 
            p_cost, 
            p_job_order, 
            p_last_log_by
        );
    END IF;
END //


CREATE PROCEDURE updateBackJobMonitoringAdditionalJobOrder(
    IN p_backjob_monitoring_id INT, 
    IN p_backjob_monitoring_additional_job_order_id INT, 
    IN p_progress DOUBLE, 
    IN p_contractor_id INT, 
    IN p_work_center_id INT,  
    IN p_completion_date DATE, 
    IN p_cost DOUBLE, 
    IN p_job_order_number VARCHAR(500), 
    IN p_job_order_date DATE, 
    IN p_particulars VARCHAR(1000), 
    IN p_last_log_by INT
)
BEGIN
    DECLARE record_exists INT;

    -- Check if the record exists
    SELECT COUNT(*) INTO record_exists 
    FROM backjob_monitoring_additional_job_order 
    WHERE backjob_monitoring_additional_job_order_id = p_backjob_monitoring_additional_job_order_id;

    IF record_exists > 0 THEN
        -- Update existing record
        UPDATE backjob_monitoring_additional_job_order
        SET 
            progress = p_progress,
            contractor_id = p_contractor_id,
            work_center_id = p_work_center_id,
            completion_date = p_completion_date,
            cost = p_cost,
            job_order_number = p_job_order_number,
            job_order_date = p_job_order_date,
            particulars = p_particulars,
            last_log_by = p_last_log_by
        WHERE backjob_monitoring_additional_job_order_id = p_backjob_monitoring_additional_job_order_id;
    ELSE
        -- Insert new record if not exists
        INSERT INTO backjob_monitoring_additional_job_order (
            backjob_monitoring_additional_job_order_id, 
            backjob_monitoring_id, 
            progress, 
            contractor_id, 
            work_center_id, 
            completion_date, 
            cost, 
            job_order_number, 
            job_order_date, 
            particulars, 
            last_log_by
        ) VALUES (
            p_backjob_monitoring_additional_job_order_id, 
            p_backjob_monitoring_id, 
            p_progress, 
            p_contractor_id, 
            p_work_center_id, 
            p_completion_date, 
            p_cost, 
            p_job_order_number, 
            p_job_order_date, 
            p_particulars, 
            p_last_log_by
        );
    END IF;
END //


CREATE PROCEDURE deleteBackJobMonitoringJobOrder(IN p_backjob_monitoring_ob_order_id INT)
BEGIN
    DELETE FROM backjob_monitoring_job_order WHERE backjob_monitoring_job_order_id = p_backjob_monitoring_ob_order_id;
END //

CREATE PROCEDURE deleteBackJobMonitoringAdditionalJobOrder(IN p_backjob_monitoring_additional_job_order_id INT)
BEGIN
    DELETE FROM backjob_monitoring_additional_job_order WHERE backjob_monitoring_additional_job_order_id = p_backjob_monitoring_additional_job_order_id;
END //

CREATE PROCEDURE generateBackJobMonitoringJobOrderTable(IN p_backjob_monitoring_id INT)
BEGIN
	SELECT *
    FROM backjob_monitoring_job_order
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

CREATE PROCEDURE generateBackJobMonitoringAdditionalJobOrderTable(IN p_backjob_monitoring_id INT)
BEGIN
	SELECT *
    FROM backjob_monitoring_additional_job_order
    WHERE backjob_monitoring_id = p_backjob_monitoring_id;
END //

DELIMITER //
CREATE PROCEDURE getBackJobMonitoringJobOrderCount(
    IN p_backjob_monitoring_id INT, 
    IN p_type VARCHAR(20)
)
BEGIN
    -- Declare a variable to store the total count
    DECLARE total_count INT DEFAULT 0;
    
    IF p_type = 'all' THEN
        -- Get total count of all job orders (main + additional)
        SELECT 
            (SELECT COUNT(*) FROM backjob_monitoring_job_order WHERE backjob_monitoring_id = p_backjob_monitoring_id) +
            (SELECT COUNT(*) FROM backjob_monitoring_additional_job_order WHERE backjob_monitoring_id = p_backjob_monitoring_id)
        INTO total_count;

    ELSEIF p_type = 'unfinished' THEN
        -- Get total count of unfinished job orders (main + additional)
        SELECT 
            (SELECT COUNT(*) FROM backjob_monitoring_job_order WHERE backjob_monitoring_id = p_backjob_monitoring_id AND progress < 100) +
            (SELECT COUNT(*) FROM backjob_monitoring_additional_job_order WHERE backjob_monitoring_id = p_backjob_monitoring_id AND progress < 100)
        INTO total_count;
    END IF;

    -- Return the total count
    SELECT total_count AS total;
END //

CREATE PROCEDURE getBackJobMonitoringJobOrder(IN p_backjob_monitoring_job_order_id INT)
BEGIN
	SELECT * FROM backjob_monitoring_job_order
    WHERE backjob_monitoring_job_order_id = p_backjob_monitoring_job_order_id;
END //

CREATE PROCEDURE getBackJobMonitoringAdditionalJobOrder(IN p_backjob_monitoring_additional_job_order_id INT)
BEGIN
	SELECT * FROM backjob_monitoring_additional_job_order
    WHERE backjob_monitoring_additional_job_order_id = p_backjob_monitoring_additional_job_order_id;
END //