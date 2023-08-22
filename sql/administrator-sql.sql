/* User table */
CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    file_as VARCHAR(300) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(500) NULL,
    is_locked TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    last_failed_login_attempt DATETIME,
    failed_login_attempts INT NOT NULL DEFAULT 0,
    last_connection_date DATETIME,
    password_expiry_date DATE NOT NULL,
    reset_token VARCHAR(255),
    reset_token_expiry_date DATETIME,
    receive_notification TINYINT(1) NOT NULL DEFAULT 1,
    two_factor_auth TINYINT(1) NOT NULL DEFAULT 1,
    otp VARCHAR(255),
    otp_expiry_date DATETIME,
    failed_otp_attempts INT NOT NULL DEFAULT 0,
    last_password_change DATETIME,
    account_lock_duration INT NOT NULL DEFAULT 0,
    last_password_reset DATETIME,
    remember_me TINYINT(1) NOT NULL DEFAULT 0,
    remember_token VARCHAR(255),
    last_log_by INT(10) NOT NULL
);

CREATE INDEX users_index_user_id ON users(user_id);
CREATE INDEX users_index_email ON users(email);

CREATE TRIGGER userTriggerUpdate
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER userTriggerInsert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
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
END //

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
	DELETE FROM users
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE deleteLinkedPasswordHistory(IN p_user_id INT)
BEGIN
    DELETE FROM password_history
    WHERE user_id = p_user_id;
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
    WHERE p_user_id = p_user_id OR email = BINARY p_email;
END //

CREATE PROCEDURE updateNotificationSetting(IN p_user_id INT, IN p_receive_notification TINYINT(1), IN p_last_log_by INT)
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

CREATE PROCEDURE deleteLinkedRoleUser(IN p_user_id INT, IN p_role_id INT)
BEGIN
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

CREATE PROCEDURE generateUserAccountTable(IN p_is_active ENUM('active', 'inactive', 'all'),IN p_is_locked ENUM('yes', 'no', 'all'),IN p_password_expiry_date_start_date DATE,IN p_password_expiry_date_end_date DATE,IN p_filter_last_connection_date_start_date DATE,IN p_filter_last_connection_date_end_date DATE,IN p_filter_last_password_reset_start_date DATE,IN p_filter_last_password_reset_end_date DATE,IN p_filter_last_failed_login_attempt_start_date DATE,IN p_filter_last_failed_login_attempt_end_date DATE)
BEGIN
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
END;

/* Password history table */
CREATE TABLE password_history (
    password_history_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    password_change_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE password_history
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE password_history
ADD FOREIGN KEY (email) REFERENCES users(email);

CREATE INDEX password_history_index_password_history_id ON password_history(password_history_id);
CREATE INDEX password_history_index_user_id ON password_history(user_id);
CREATE INDEX password_history_index_email ON password_history(email);

CREATE PROCEDURE getPasswordHistory(IN p_user_id INT, IN p_email VARCHAR(255))
BEGIN
	SELECT * FROM password_history
	WHERE p_user_id = p_user_id OR email = BINARY p_email;
END //

CREATE PROCEDURE insertPasswordHistory(IN p_user_id INT, IN p_email VARCHAR(255), IN p_password VARCHAR(255), IN p_last_password_change DATETIME)
BEGIN
    INSERT INTO password_history (user_id, email, password, password_change_date) 
    VALUES (p_user_id, p_email, p_password, p_last_password_change);
END //

/* Audit log table */
CREATE TABLE audit_log (
    audit_log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    table_name VARCHAR(255) NOT NULL,
    reference_id INT NOT NULL,
    log TEXT NOT NULL,
    changed_by VARCHAR(255) NOT NULL,
    changed_at DATETIME NOT NULL
);

CREATE INDEX audit_log_index_external_id ON audit_log(audit_log_id);
CREATE INDEX audit_log_index_table_name ON audit_log(table_name);
CREATE INDEX audit_log_index_reference_id ON audit_log(reference_id);

CREATE PROCEDURE generateLogNotes(IN p_table_name VARCHAR(255), IN p_reference_id INT)
BEGIN
	SELECT log, changed_by, changed_at FROM audit_log
    WHERE table_name = p_table_name AND reference_id = p_reference_id
    ORDER BY changed_at DESC;
END //

/* UI customization setting table */
CREATE TABLE ui_customization_setting (
    ui_customization_setting_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    theme_contrast VARCHAR(15) NOT NULL DEFAULT 'false',
    caption_show VARCHAR(15) NOT NULL DEFAULT 'true',
    preset_theme VARCHAR(15) NOT NULL DEFAULT 'preset-1',
    dark_layout VARCHAR(15) NOT NULL DEFAULT 'false',
    rtl_layout VARCHAR(15) NOT NULL DEFAULT 'false',
    box_container VARCHAR(15) NOT NULL DEFAULT 'false',
    last_log_by INT(10) NOT NULL
);

ALTER TABLE ui_customization_setting
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

CREATE INDEX ui_customization_setting_index_ui_customization_setting_id ON ui_customization_setting(ui_customization_setting_id);
CREATE INDEX ui_customization_setting_index_user_id ON ui_customization_setting(user_id);

CREATE TRIGGER uiCustomizationSettingTriggerUpdate
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
END //

CREATE TRIGGER uiCustomizationSettingTriggerInsert
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
END //

CREATE PROCEDURE checkUICustomizationSettingExist(IN p_user_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM ui_customization_setting
	WHERE user_id = p_user_id;
END //

CREATE PROCEDURE insertUICustomizationSetting(IN p_user_id INT, IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
BEGIN
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
END //

CREATE PROCEDURE updateUICustomizationSetting(IN p_user_id INT, IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
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

CREATE PROCEDURE deleteLinkedUICustomization(IN p_user_id INT)
BEGIN
	DELETE FROM ui_customization_setting
	WHERE user_id = p_user_id;
END //

/* Role table */
CREATE TABLE role(
	role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	role_name VARCHAR(100) NOT NULL,
	role_description VARCHAR(200) NOT NULL,
	assignable TINYINT(1) NOT NULL DEFAULT 1,
    last_log_by INT NOT NULL
);

CREATE INDEX role_index_role_id ON role(role_id);

CREATE TRIGGER role_trigger_update
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
END //

CREATE TRIGGER role_trigger_insert
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
END //

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
	DELETE FROM role
    WHERE role_id = p_role_id;
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

/* Role users table */
CREATE TABLE role_users(
	role_id INT NOT NULL,
	user_id INT NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX role_users_index_role_id ON role_users(role_id);
CREATE INDEX role_users_index_user_id ON role_users(user_id);

CREATE PROCEDURE checkRoleUserExist(IN p_user_id INT, IN p_role_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM role_users
    WHERE user_id = p_user_id AND role_id = p_role_id;
END //

CREATE PROCEDURE insertRoleUser(IN p_user_id INT, IN p_role_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO role_users (user_id, role_id, last_log_by) 
	VALUES(p_user_id, p_role_id, p_last_log_by);
END //

CREATE PROCEDURE deleteRoleUser(IN p_user_id INT, IN p_role_id INT)
BEGIN
	DELETE FROM role_users
    WHERE user_id = p_user_id AND role_id = p_role_id;
END //

/* Menu group table */
CREATE TABLE menu_group (
    menu_group_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    menu_group_name VARCHAR(100) NOT NULL,
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX menu_group_index_menu_group_id ON menu_group(menu_group_id);

CREATE TRIGGER menu_group_trigger_update
AFTER UPDATE ON menu_group
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
        VALUES ('menu_group', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER menu_group_trigger_insert
AFTER INSERT ON menu_group
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
    VALUES ('menu_group', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
END //

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
	DELETE FROM menu_group
    WHERE menu_group_id = p_menu_group_id;
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

/* Menu item table */
CREATE TABLE menu_item(
	menu_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	menu_item_name VARCHAR(100) NOT NULL,
	menu_group_id INT UNSIGNED NOT NULL,
	menu_item_url VARCHAR(50),
	parent_id INT UNSIGNED,
	menu_item_icon VARCHAR(150),
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX menu_item_index_menu_item_id ON menu_item(menu_item_id);

ALTER TABLE menu_item
ADD FOREIGN KEY (menu_group_id) REFERENCES menu_group(menu_group_id);

CREATE TRIGGER menu_item_trigger_update
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
END //

CREATE TRIGGER menu_item_trigger_insert
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
END //

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
	DELETE FROM menu_item
    WHERE menu_item_id = p_menu_item_id;
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

/* Menu item access right table */
CREATE TABLE menu_item_access_right(
	menu_item_id INT UNSIGNED NOT NULL,
	role_id INT UNSIGNED NOT NULL,
	read_access TINYINT(1) NOT NULL,
    write_access TINYINT(1) NOT NULL,
    create_access TINYINT(1) NOT NULL,
    delete_access TINYINT(1) NOT NULL,
    duplicate_access TINYINT(1) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE TRIGGER menu_item_access_right_update
AFTER UPDATE ON menu_item_access_right
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER menu_item_access_right_insert
AFTER INSERT ON menu_item_access_right
FOR EACH ROW
BEGIN
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
END //

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

CREATE PROCEDURE deleteLinkedMenuItemAccessRight(IN p_menu_item_id INT, IN p_role_id INT)
BEGIN
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

/* System action table */
CREATE TABLE system_action(
	system_action_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	system_action_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX system_action_index_system_action_id ON system_action(system_action_id);

CREATE TRIGGER system_action_trigger_update
AFTER UPDATE ON system_action
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.system_action_name <> OLD.system_action_name THEN
        SET audit_log = CONCAT(audit_log, "System Action Name: ", OLD.system_action_name, " -> ", NEW.system_action_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('system_action', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER system_action_trigger_insert
AFTER INSERT ON system_action
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'System action created. <br/>';

    IF NEW.system_action_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>System Action Name: ", NEW.system_action_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('system_action', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
END //

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
	DELETE FROM system_action
    WHERE system_action_id = p_system_action_id;
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

/* System action table */
CREATE TABLE system_action_access_rights(
	system_action_id INT UNSIGNED NOT NULL,
	role_id INT UNSIGNED NOT NULL,
	role_access TINYINT(1) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE TRIGGER system_action_access_rights_update
AFTER UPDATE ON system_action_access_rights
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    SET audit_log = CONCAT(audit_log, "Role ID: ", OLD.role_id, "<br/>");

    IF NEW.role_access <> OLD.role_access THEN
        SET audit_log = CONCAT(audit_log, "Role Access: ", OLD.role_access, " -> ", NEW.role_access, "<br/>");
    END IF;

    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('system_action_access_rights', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER system_action_access_rights_insert
AFTER INSERT ON system_action_access_rights
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'System action access rights created. <br/>';

    IF NEW.role_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Role ID: ", NEW.role_id);
    END IF;

    IF NEW.role_access <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Role Access: ", NEW.role_access);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('system_action_access_rights', NEW.system_action_id, audit_log, NEW.last_log_by, NOW());
END //

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

CREATE PROCEDURE deleteLinkedSystemActionAccessRight(IN p_system_action_id INT, IN p_role_id INT)
BEGIN
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
END //

/* File type table */
CREATE TABLE file_type(
	file_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	file_type_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX file_type_index_file_type_id ON file_type(file_type_id);

CREATE TRIGGER file_type_trigger_update
AFTER UPDATE ON file_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.file_type_name <> OLD.file_type_name THEN
        SET audit_log = CONCAT(audit_log, "File Type Name: ", OLD.file_type_name, " -> ", NEW.file_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('file_type', NEW.file_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER file_type_trigger_insert
AFTER INSERT ON file_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'System action created. <br/>';

    IF NEW.file_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File Type Name: ", NEW.file_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('file_type', NEW.file_type_id, audit_log, NEW.last_log_by, NOW());
END //

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
	DELETE FROM file_type
    WHERE file_type_id = p_file_type_id;
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

/* File extension table */
CREATE TABLE file_extension(
	file_extension_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	file_extension_name VARCHAR(100) NOT NULL,
	file_type_id INT UNSIGNED NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX file_extension_index_file_extension_id ON file_extension(file_extension_id);

ALTER TABLE file_extension
ADD FOREIGN KEY (file_type_id) REFERENCES file_type(file_type_id);

CREATE TRIGGER file_extension_trigger_update
AFTER UPDATE ON file_extension
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER file_extension_trigger_insert
AFTER INSERT ON file_extension
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'File extension created. <br/>';

    IF NEW.file_extension_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File Exension Name: ", NEW.file_extension_name);
    END IF;

    IF NEW.file_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File Type ID: ", NEW.file_type_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('file_extension', NEW.file_extension_id, audit_log, NEW.last_log_by, NOW());
END //

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
	DELETE FROM file_extension
    WHERE file_extension_id = p_file_extension_id;
END //

CREATE PROCEDURE deleteLinkedFileExtension(IN p_file_type_id INT)
BEGIN
	DELETE FROM file_extension
    WHERE file_type_id = p_file_type_id;
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

/* Upload setting table */
CREATE TABLE upload_setting(
	upload_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	upload_setting_name VARCHAR(100) NOT NULL,
	upload_setting_description VARCHAR(200) NOT NULL,
	max_file_size DOUBLE NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX upload_setting_index_upload_setting_id ON upload_setting(upload_setting_id);

CREATE TRIGGER upload_setting_trigger_update
AFTER UPDATE ON upload_setting
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER upload_setting_trigger_insert
AFTER INSERT ON upload_setting
FOR EACH ROW
BEGIN
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
END //

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
	DELETE FROM upload_setting
    WHERE upload_setting_id = p_upload_setting_id;
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

CREATE PROCEDURE deleteLinkedAllowedFileExtension(IN p_upload_setting_id INT)
BEGIN
    DELETE FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id;
END //

/* Upload setting file extension table */
CREATE TABLE upload_setting_file_extension(
	upload_setting_id INT UNSIGNED NOT NULL,
	file_extension_id INT UNSIGNED NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX upload_setting_file_extension_index_upload_setting_id ON upload_setting_file_extension(upload_setting_id);
CREATE INDEX upload_setting_file_extension_index_file_extension_id ON upload_setting_file_extension(file_extension_id);

CREATE PROCEDURE checkUploadSettingFileExtensionExist (IN p_upload_setting_id INT, IN p_file_extension_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM upload_setting_file_extension
    WHERE upload_setting_id = p_upload_setting_id AND file_extension_id = p_file_extension_id;
END //

CREATE PROCEDURE insertUploadSettingFileExtension(IN p_upload_setting_id INT, IN p_file_extension_id INT, IN p_last_log_by INT)
BEGIN
    INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) 
	VALUES(p_upload_setting_id, p_file_extension_id, p_last_log_by);
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

CREATE PROCEDURE deleteLinkedUploadSettingFileExtension(IN p_upload_setting_id INT, IN p_file_extension_id INT)
BEGIN
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
END //

/* Interface setting table */
CREATE TABLE interface_setting(
	interface_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	interface_setting_name VARCHAR(100) NOT NULL,
	interface_setting_description VARCHAR(200) NOT NULL,
	value VARCHAR(1000),
    last_log_by INT NOT NULL
);

CREATE INDEX interface_setting_index_interface_setting_id ON interface_setting(interface_setting_id);

CREATE TRIGGER interface_setting_trigger_update
AFTER UPDATE ON interface_setting
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER interface_setting_trigger_insert
AFTER INSERT ON interface_setting
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Interface setting created. <br/>';

    IF NEW.interface_setting_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Interface Setting Name: ", NEW.interface_setting_name);
    END IF;

    IF NEW.interface_setting_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Interface Setting Description: ", NEW.interface_setting_description);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('interface_setting', NEW.interface_setting_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* System setting table */
CREATE TABLE system_setting(
	system_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	system_setting_name VARCHAR(100) NOT NULL,
	system_setting_description VARCHAR(200) NOT NULL,
	value VARCHAR(1000) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX system_setting_index_system_setting_id ON system_setting(system_setting_id);

CREATE TRIGGER system_setting_trigger_update
AFTER UPDATE ON system_setting
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER system_setting_trigger_insert
AFTER INSERT ON system_setting
FOR EACH ROW
BEGIN
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
END //

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

/* Country table */
CREATE TABLE country(
	country_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	country_name VARCHAR(100) NOT NULL,
	country_code VARCHAR(5),
	phone_code VARCHAR(20) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX country_index_country_id ON country(country_id);

CREATE TRIGGER country_trigger_update
AFTER UPDATE ON country
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER country_trigger_insert
AFTER INSERT ON country
FOR EACH ROW
BEGIN
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
END //

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
	DELETE FROM country
    WHERE country_id = p_country_id;
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

/* State table */
CREATE TABLE state(
	state_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	state_name VARCHAR(100) NOT NULL,
	country_id INT NOT NULL,
	state_code VARCHAR(5),
    last_log_by INT NOT NULL
);

CREATE INDEX state_index_state_id ON state(state_id);
CREATE INDEX state_index_country_id ON state(country_id);

CREATE TRIGGER state_trigger_update
AFTER UPDATE ON state
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER state_trigger_insert
AFTER INSERT ON state
FOR EACH ROW
BEGIN
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
END //

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
	DELETE FROM state
    WHERE state_id = p_state_id;
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

CREATE PROCEDURE generateStateTable(IN p_country_id INT)
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT state_id, state_name, country_id FROM state';
    
    SET conditionList = ' WHERE 1';

    IF p_country_id > 0 THEN
        SET conditionList = CONCAT(conditionList, ' AND country_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_country_id));
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY state_id;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END;

CREATE PROCEDURE generateStateOptions()
BEGIN
	SELECT state_id, state_name FROM state
	ORDER BY state_name;
END //

/* City table */
CREATE TABLE city(
	city_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	city_name VARCHAR(100) NOT NULL,
	state_id INT NOT NULL,
	country_id INT NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX city_index_city_id ON city(city_id);
CREATE INDEX city_index_state_id ON city(state_id);
CREATE INDEX city_index_country_id ON city(country_id);

CREATE TRIGGER city_trigger_update
AFTER UPDATE ON city
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.city_name <> OLD.city_name THEN
        SET audit_log = CONCAT(audit_log, "City Name: ", OLD.city_name, " -> ", NEW.city_name, "<br/>");
    END IF;

    IF NEW.state_id <> OLD.state_id THEN
        SET audit_log = CONCAT(audit_log, "State ID: ", OLD.state_id, " -> ", NEW.state_id, "<br/>");
    END IF;

    IF NEW.country_id <> OLD.country_id THEN
        SET audit_log = CONCAT(audit_log, "Country ID: ", OLD.country_id, " -> ", NEW.country_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('city', NEW.city_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER city_trigger_insert
AFTER INSERT ON city
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'City created. <br/>';

    IF NEW.city_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>City Name: ", NEW.city_name);
    END IF;

    IF NEW.state_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>State ID: ", NEW.state_id);
    END IF;

    IF NEW.country_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Country ID: ", NEW.country_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('city', NEW.city_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE checkCityExist (IN p_city_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM city
    WHERE city_id = p_city_id;
END //

CREATE PROCEDURE insertCity(IN p_city_name VARCHAR(100), IN p_state_id INT, IN p_country_id INT, IN p_last_log_by INT, OUT p_city_id INT)
BEGIN
    INSERT INTO city (city_name, state_id, country_id, last_log_by) 
	VALUES(p_city_name, p_state_id, p_country_id, p_last_log_by);
	
    SET p_city_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateCity(IN p_city_id INT, IN p_city_name VARCHAR(100), IN p_state_id INT, IN p_country_id INT, IN p_last_log_by INT)
BEGIN
	UPDATE city
    SET city_name = p_city_name,
    state_id = p_state_id,
    country_id = p_country_id,
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
    DECLARE p_country_id INT;
    
    SELECT city_name, state_id, country_id
    INTO p_city_name, p_state_id, p_country_id
    FROM city 
    WHERE city_id = p_city_id;
    
    INSERT INTO city (city_name, state_id, country_id, last_log_by) 
    VALUES(p_city_name, p_state_id, p_country_id, p_last_log_by);
    
    SET p_new_city_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE generateCityTable(IN p_state_id INT, IN p_country_id INT)
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT city_id, city_name, country_id FROM city';
    
    SET conditionList = ' WHERE 1';

    IF p_state_id > 0 THEN
        SET conditionList = CONCAT(conditionList, ' AND state_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_state_id));
    END IF;

    IF p_country_id > 0 THEN
        SET conditionList = CONCAT(conditionList, ' AND country_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_country_id));
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY city_id;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END;

/* Insert */

INSERT INTO users (file_as, email, password, is_locked, is_active, password_expiry_date, two_factor_auth, last_log_by) VALUES ('Administrator', 'ldagulto@encorefinancials.com', 'RYHObc8sNwIxdPDNJwCsO8bXKZJXYx7RjTgEWMC17FY%3D', '0', '1', '2023-12-30', '0', '0');

INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Super Admin', 'This role has the highest level of access and full control over the entire system. Super Admins can perform all actions, including managing other user accounts, configuring system settings, and accessing all data.', '0', '0');
INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Administrator', 'Full access to all features and data within the system. This role have similar access levels to the Admin but is not as powerful as the Super Admin.', '1', '0');
INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Manager', 'Access to manage specific aspects of the system or resources related to their teams or departments.', '1', '0');
INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Employee', 'The typical user account with standard access to use the system features and functionalities.', '1', '0');

INSERT INTO role_users (role_id, user_id, last_log_by) VALUES ('1', '1', '0');

INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) VALUES ('Technical', '100', '0');
INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) VALUES ('Human Resources', '50', '0');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('User Interface', '1', '', '', 'sidebar', '50', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Menu Group', '1', 'menu-group.php', '1', '', '1', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Menu Item', '1', 'menu-item.php', '1', '', '2', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Administration', '1', '', '', 'shield', '1', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('System Action', '1', 'system-action.php', '4', '', '15', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Role Configuration', '1', 'role-configuration.php', '4', '', '10', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Role', '1', 'role.php', '4', '', '9', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('User Account', '1', 'user-account.php', '4', '', '1', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Configurations', '1', '', '0', 'settings', '30', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('File Type', '1', 'file-type.php', '9', '', '30', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('File Extension', '1', 'file-extension.php', '9', '', '31', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Upload Setting', '1', 'upload-setting.php', '9', '', '29', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Interface Setting', '1', 'interface-setting.php', '1', '', '3', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('System Setting', '1', 'system-setting.php', '4', '', '16', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Country', '1', 'country.php', '9', '', '20', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('State', '1', 'state.php', '9', '', '21', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('City', '1', 'city.php', '9', '', '22', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Company', '1', 'city.php', '4', '', '20', '0');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Currency', '1', 'currency.php', '9', '', '23', '0');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('1', '1', '1', '0', '0', '0', '0', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('2', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('3', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('4', '1', '1', '0', '0', '0', '0', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('5', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('6', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('7', '1', '1', '1', '0', '0', '0', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('8', '1', '1', '0', '0', '0', '0', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('9', '1', '1', '0', '0', '0', '0', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('10', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('11', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('12', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('13', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('14', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('15', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('16', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('17', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('18', '1', '1', '1', '1', '1', '1', '0');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('19', '1', '1', '1', '1', '1', '1', '0');

INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update Menu Item Role Access', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Menu Item Role Access', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update System Action Role Access', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete System Action Role Access', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Assign User Account To Role', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete User Account To Role', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Assign Role To User Account', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Role To User Account', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Activate User Account', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Deactivate User Account', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Lock User Account', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Unlock User Account', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Change User Account Password', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Change User Account Profile Picture', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Assign File Extension To Upload Setting', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete File Extension To Upload Setting', '0');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Send Reset Password Instructions', '0');

INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('1', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('2', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('3', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('4', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('5', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('6', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('7', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('8', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('9', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('10', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('11', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('12', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('13', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('14', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('15', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('16', '1', '1', '0');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('17', '1', '1', '0');

INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Audio', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Compressed', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Disk and Media', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Data and Database', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Email', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Executable', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Font', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Image', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Internet Related', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Presentation', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Spreadsheet', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('System Related', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Video', '0');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Word Processor', '0');

INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('aif', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cda', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mid', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('midi', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mp3', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mpa', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ogg', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wav', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wma', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wpl', '1', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('7z', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('arj', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('deb', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pkg', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rar', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rpm', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tar.gz', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('z', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('zip', '2', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bin', '3', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dmg', '3', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('iso', '3', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('toast', '3', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('vcd', '3', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('csv', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dat', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('db', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dbf', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('log', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mdb', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('sav', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('sql', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tar', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xml', '4', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('email', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('eml', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('emlx', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('msg', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('oft', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ost', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pst', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('vcf', '5', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('apk', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bat', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bin', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cgi', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pl', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('com', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('exe', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('gadget', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jar', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wsf', '6', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('fnt', '7', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('fon', '7', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('otf', '7', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ttf', '7', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ai', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bmp', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('gif', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ico', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jpg', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jpeg', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('png', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ps', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('psd', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('svg', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tif', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tiff', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('webp', '8', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('asp', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('aspx', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cer', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cfm', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cgi', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pl', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('css', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('htm', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('html', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('js', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jsp', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('part', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('php', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('py', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rss', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xhtml', '9', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('key', '10', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('odp', '10', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pps', '10', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ppt', '10', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pptx', '10', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ods', '11', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xls', '11', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xlsm', '11', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xlsx', '11', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bak', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cab', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cfg', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cpl', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cur', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dll', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dmp', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('drv', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('icns', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ini', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('lnk', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('msi', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('sys', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tmp', '12', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('3g2', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('3gp', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('avi', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('flv', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('h264', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('m4v', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mkv', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mov', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mp4', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mpg', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mpeg', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rm', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('swf', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('vob', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('webm', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wmv', '13', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('doc', '14', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('docx', '14', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pdf', '14', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rtf', '14', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tex', '14', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('txt', '14', '0');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wpd', '14', '0');

INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('Login Background', 'Interface setting for background image on login page.', '0');
INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('Login Logo', 'Interface setting for logo on login page.', '0');
INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('Navbar Logo', 'Interface setting for logo on navbar.', '0');
INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('System Icon', 'Interface setting for system icon.', '0');

INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) VALUES ('Max Failed Login Attempt', 'This sets the maximum failed login attempt before the user is locked-out.', 5, '0');
INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) VALUES ('Max Failed OTP Attempt', 'This sets the maximum failed OTP attempt before the user is needs a new OTP code.', 5, '0');

INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) VALUES ('Profile Image', 'Sets the upload setting when uploading user account profile image.', 5, '0');
INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) VALUES ('Interface Setting', 'Sets the upload setting when uploading interface setting image.', 5, '0');
INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) VALUES ('Company Logo', 'Sets the upload setting when uploading company logo.', 5, '0');

INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('1', 61, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('1', 62, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('1', 63, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('1', 66, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('1', 69, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('2', 61, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('2', 62, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('2', 63, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('2', 66, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('2', 69, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('2', 60, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('3', 61, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('3', 62, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('3', 63, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('3', 66, '0');
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id, last_log_by) VALUES ('3', 69, '0');

INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Afghanistan', 'AFG', '93', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Aland Islands', 'ALA', '340', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Albania', 'ALB', '355', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Algeria', 'DZA', '213', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('American Samoa', 'ASM', '-683', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Andorra', 'AND', '376', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Angola', 'AGO', '244', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Anguilla', 'AIA', '-263', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Antarctica', 'ATA', '672', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Antigua And Barbuda', 'ATG', '-267', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Argentina', 'ARG', '54', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Armenia', 'ARM', '374', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Aruba', 'ABW', '297', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Australia', 'AUS', '61', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Austria', 'AUT', '43', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Azerbaijan', 'AZE', '994', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bahrain', 'BHR', '973', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bangladesh', 'BGD', '880', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Barbados', 'BRB', '-245', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Belarus', 'BLR', '375', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Belgium', 'BEL', '32', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Belize', 'BLZ', '501', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Benin', 'BEN', '229', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bermuda', 'BMU', '-440', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bhutan', 'BTN', '975', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bolivia', 'BOL', '591', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bonaire, Sint Eustatius and Saba', 'BES', '599', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bosnia and Herzegovina', 'BIH', '387', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Botswana', 'BWA', '267', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bouvet Island', 'BVT', '55', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Brazil', 'BRA', '55', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('British Indian Ocean Territory', 'IOT', '246', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Brunei', 'BRN', '673', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bulgaria', 'BGR', '359', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Burkina Faso', 'BFA', '226', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Burundi', 'BDI', '257', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cambodia', 'KHM', '855', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cameroon', 'CMR', '237', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Canada', 'CAN', '1', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cape Verde', 'CPV', '238', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cayman Islands', 'CYM', '-344', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Central African Republic', 'CAF', '236', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Chad', 'TCD', '235', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Chile', 'CHL', '56', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('China', 'CHN', '86', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Christmas Island', 'CXR', '61', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cocos (Keeling) Islands', 'CCK', '61', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Colombia', 'COL', '57', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Comoros', 'COM', '269', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Congo', 'COG', '242', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cook Islands', 'COK', '682', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Costa Rica', 'CRI', '506', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cote D Ivoire (Ivory Coast)', 'CIV', '225', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Croatia', 'HRV', '385', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cuba', 'CUB', '53', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('CuraÃ§ao', 'CUW', '599', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cyprus', 'CYP', '357', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Czech Republic', 'CZE', '420', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Democratic Republic of the Congo', 'COD', '243', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Denmark', 'DNK', '45', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Djibouti', 'DJI', '253', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Dominica', 'DMA', '-766', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Dominican Republic', 'DOM', '+1-809 and 1-829', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('East Timor', 'TLS', '670', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ecuador', 'ECU', '593', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Egypt', 'EGY', '20', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('El Salvador', 'SLV', '503', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Equatorial Guinea', 'GNQ', '240', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Eritrea', 'ERI', '291', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Estonia', 'EST', '372', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ethiopia', 'ETH', '251', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Falkland Islands', 'FLK', '500', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Faroe Islands', 'FRO', '298', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Fiji Islands', 'FJI', '679', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Finland', 'FIN', '358', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('France', 'FRA', '33', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('French Guiana', 'GUF', '594', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('French Polynesia', 'PYF', '689', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('French Southern Territories', 'ATF', '262', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Gabon', 'GAB', '241', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Gambia The', 'GMB', '220', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Georgia', 'GEO', '995', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Germany', 'DEU', '49', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ghana', 'GHA', '233', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Gibraltar', 'GIB', '350', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Greece', 'GRC', '30', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Greenland', 'GRL', '299', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Grenada', 'GRD', '-472', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guadeloupe', 'GLP', '590', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guam', 'GUM', '-670', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guatemala', 'GTM', '502', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guernsey and Alderney', 'GGY', '-1437', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guinea', 'GIN', '224', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guinea-Bissau', 'GNB', '245', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guyana', 'GUY', '592', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Haiti', 'HTI', '509', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Heard Island and McDonald Islands', 'HMD', '672', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Honduras', 'HND', '504', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Hong Kong S.A.R.', 'HKG', '852', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Hungary', 'HUN', '36', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Iceland', 'ISL', '354', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('India', 'IND', '91', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Indonesia', 'IDN', '62', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Iran', 'IRN', '98', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Iraq', 'IRQ', '964', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ireland', 'IRL', '353', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Israel', 'ISR', '972', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Italy', 'ITA', '39', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Jamaica', 'JAM', '-875', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Japan', 'JPN', '81', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Jersey', 'JEY', '-1490', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Jordan', 'JOR', '962', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kazakhstan', 'KAZ', '7', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kenya', 'KEN', '254', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kiribati', 'KIR', '686', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kosovo', 'XKX', '383', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kuwait', 'KWT', '965', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kyrgyzstan', 'KGZ', '996', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Laos', 'LAO', '856', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Latvia', 'LVA', '371', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Lebanon', 'LBN', '961', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Lesotho', 'LSO', '266', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Liberia', 'LBR', '231', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Libya', 'LBY', '218', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Liechtenstein', 'LIE', '423', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Lithuania', 'LTU', '370', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Luxembourg', 'LUX', '352', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Macau S.A.R.', 'MAC', '853', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Madagascar', 'MDG', '261', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Malawi', 'MWI', '265', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Malaysia', 'MYS', '60', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Maldives', 'MDV', '960', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mali', 'MLI', '223', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Malta', 'MLT', '356', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Man (Isle of)', 'IMN', '-1580', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Marshall Islands', 'MHL', '692', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Martinique', 'MTQ', '596', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mauritania', 'MRT', '222', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mauritius', 'MUS', '230', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mayotte', 'MYT', '262', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mexico', 'MEX', '52', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Micronesia', 'FSM', '691', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Moldova', 'MDA', '373', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Monaco', 'MCO', '377', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mongolia', 'MNG', '976', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Montenegro', 'MNE', '382', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Montserrat', 'MSR', '-663', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Morocco', 'MAR', '212', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mozambique', 'MOZ', '258', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Myanmar', 'MMR', '95', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Namibia', 'NAM', '264', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nauru', 'NRU', '674', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nepal', 'NPL', '977', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Netherlands', 'NLD', '31', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('New Caledonia', 'NCL', '687', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('New Zealand', 'NZL', '64', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nicaragua', 'NIC', '505', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Niger', 'NER', '227', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nigeria', 'NGA', '234', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Niue', 'NIU', '683', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Norfolk Island', 'NFK', '672', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('North Korea', 'PRK', '850', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('North Macedonia', 'MKD', '389', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Northern Mariana Islands', 'MNP', '-669', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Norway', 'NOR', '47', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Oman', 'OMN', '968', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Pakistan', 'PAK', '92', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Palau', 'PLW', '680', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Palestinian Territory Occupied', 'PSE', '970', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Panama', 'PAN', '507', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Papua new Guinea', 'PNG', '675', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Paraguay', 'PRY', '595', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Peru', 'PER', '51', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Philippines', 'PHL', '63', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Pitcairn Island', 'PCN', '870', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Poland', 'POL', '48', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Portugal', 'PRT', '351', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Puerto Rico', 'PRI', '+1-787 and 1-939', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Qatar', 'QAT', '974', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Reunion', 'REU', '262', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Romania', 'ROU', '40', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Russia', 'RUS', '7', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Rwanda', 'RWA', '250', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Helena', 'SHN', '290', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Kitts And Nevis', 'KNA', '-868', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Lucia', 'LCA', '-757', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Pierre and Miquelon', 'SPM', '508', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Vincent And The Grenadines', 'VCT', '-783', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint-Barthelemy', 'BLM', '590', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint-Martin (French part)', 'MAF', '590', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Samoa', 'WSM', '685', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('San Marino', 'SMR', '378', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sao Tome and Principe', 'STP', '239', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saudi Arabia', 'SAU', '966', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Senegal', 'SEN', '221', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Serbia', 'SRB', '381', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Seychelles', 'SYC', '248', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sierra Leone', 'SLE', '232', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Singapore', 'SGP', '65', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sint Maarten (Dutch part)', 'SXM', '1721', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Slovakia', 'SVK', '421', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Slovenia', 'SVN', '386', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Solomon Islands', 'SLB', '677', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Somalia', 'SOM', '252', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Africa', 'ZAF', '27', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Georgia', 'SGS', '500', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Korea', 'KOR', '82', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Sudan', 'SSD', '211', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Spain', 'ESP', '34', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sri Lanka', 'LKA', '94', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sudan', 'SDN', '249', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Suriname', 'SUR', '597', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Svalbard And Jan Mayen Islands', 'SJM', '47', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Swaziland', 'SWZ', '268', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sweden', 'SWE', '46', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Switzerland', 'CHE', '41', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Syria', 'SYR', '963', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Taiwan', 'TWN', '886', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tajikistan', 'TJK', '992', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tanzania', 'TZA', '255', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Thailand', 'THA', '66', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('The Bahamas', 'BHS', '-241', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Togo', 'TGO', '228', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tokelau', 'TKL', '690', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tonga', 'TON', '676', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Trinidad And Tobago', 'TTO', '-867', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tunisia', 'TUN', '216', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Turkey', 'TUR', '90', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Turkmenistan', 'TKM', '993', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Turks And Caicos Islands', 'TCA', '-648', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tuvalu', 'TUV', '688', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Uganda', 'UGA', '256', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ukraine', 'UKR', '380', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United Arab Emirates', 'ARE', '971', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United Kingdom', 'GBR', '44', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United States', 'USA', '1', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United States Minor Outlying Islands', 'UMI', '1', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Uruguay', 'URY', '598', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Uzbekistan', 'UZB', '998', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Vanuatu', 'VUT', '678', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Vatican City State (Holy See)', 'VAT', '379', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Venezuela', 'VEN', '58', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Vietnam', 'VNM', '84', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Virgin Islands (British)', 'VGB', '-283', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Virgin Islands (US)', 'VIR', '-339', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Wallis And Futuna Islands', 'WLF', '681', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Western Sahara', 'ESH', '212', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Yemen', 'YEM', '967', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Zambia', 'ZMB', '260', '0');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Zimbabwe', 'ZWE', '263', '0');

INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Badakhshan', '1', 'BDS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Badghis', '1', 'BDG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baghlan', '1', 'BGL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balkh', '1', 'BAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bamyan', '1', 'BAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Daykundi', '1', 'DAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Farah', '1', 'FRA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Faryab', '1', 'FYB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ghazni', '1', 'GHA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GhÅr', '1', 'GHO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Helmand', '1', 'HEL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Herat', '1', 'HER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jowzjan', '1', 'JOW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kabul', '1', 'KAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kandahar', '1', 'KAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kapisa', '1', 'KAP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khost', '1', 'KHO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kunar', '1', 'KNR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kunduz Province', '1', 'KDZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laghman', '1', 'LAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Logar', '1', 'LOG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nangarhar', '1', 'NAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nimruz', '1', 'NIM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nuristan', '1', 'NUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paktia', '1', 'PIA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paktika', '1', 'PKA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Panjshir', '1', 'PAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Parwan', '1', 'PAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samangan', '1', 'SAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sar-e Pol', '1', 'SAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Takhar', '1', 'TAK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Urozgan', '1', 'URU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zabul', '1', 'ZAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berat County', '3', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berat District', '3', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BulqizÃ« District', '3', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DelvinÃ« District', '3', 'DL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Devoll District', '3', 'DV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DibÃ«r County', '3', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DibÃ«r District', '3', 'DI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DurrÃ«s County', '3', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DurrÃ«s District', '3', 'DR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Elbasan County', '3', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fier County', '3', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fier District', '3', 'FR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GjirokastÃ«r County', '3', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GjirokastÃ«r District', '3', 'GJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gramsh District', '3', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Has District', '3', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KavajÃ« District', '3', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KolonjÃ« District', '3', 'ER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KorÃ§Ã« County', '3', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KorÃ§Ã« District', '3', 'KO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KrujÃ« District', '3', 'KR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KuÃ§ovÃ« District', '3', 'KC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KukÃ«s County', '3', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KukÃ«s District', '3', 'KU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kurbin District', '3', 'KB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LezhÃ« County', '3', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LezhÃ« District', '3', 'LE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Librazhd District', '3', 'LB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LushnjÃ« District', '3', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MalÃ«si e Madhe District', '3', 'MM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MallakastÃ«r District', '3', 'MK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mat District', '3', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MirditÃ« District', '3', 'MR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Peqin District', '3', 'PQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÃ«rmet District', '3', 'PR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pogradec District', '3', 'PG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PukÃ« District', '3', 'PU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SarandÃ« District', '3', 'SR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ShkodÃ«r County', '3', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ShkodÃ«r District', '3', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Skrapar District', '3', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TepelenÃ« District', '3', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tirana County', '3', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tirana District', '3', 'TR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TropojÃ« District', '3', 'TP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VlorÃ« County', '3', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VlorÃ« District', '3', 'VL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adrar', '4', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AÃ¯n Defla', '4', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AÃ¯n TÃ©mouchent', '4', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Algiers', '4', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Annaba', '4', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batna', '4', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©char', '4', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©jaÃ¯a', '4', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©ni AbbÃ¨s', '4', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Biskra', '4', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blida', '4', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bordj Baji Mokhtar', '4', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bordj Bou ArrÃ©ridj', '4', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BouÃ¯ra', '4', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BoumerdÃ¨s', '4', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chlef', '4', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Constantine', '4', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Djanet', '4', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Djelfa', '4', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Bayadh', '4', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El M ghair', '4', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Menia', '4', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Oued', '4', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Tarf', '4', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GhardaÃ¯a', '4', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guelma', '4', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Illizi', '4', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('In Guezzam', '4', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('In Salah', '4', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jijel', '4', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khenchela', '4', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laghouat', '4', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('M Sila', '4', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mascara', '4', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÃ©dÃ©a', '4', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mila', '4', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mostaganem', '4', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naama', '4', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oran', '4', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouargla', '4', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouled Djellal', '4', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oum El Bouaghi', '4', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Relizane', '4', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SaÃ¯da', '4', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ©tif', '4', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sidi Bel AbbÃ¨s', '4', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Skikda', '4', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Souk Ahras', '4', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tamanghasset', '4', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÃ©bessa', '4', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tiaret', '4', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Timimoun', '4', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tindouf', '4', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tipasa', '4', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tissemsilt', '4', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tizi Ouzou', '4', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tlemcen', '4', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Touggourt', '4', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Andorra la Vella', '6', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canillo', '6', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Encamp', '6', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Escaldes-Engordany', '6', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Massana', '6', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ordino', '6', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sant JuliÃ  de LÃ²ria', '6', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bengo Province', '7', 'BGO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benguela Province', '7', 'BGU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BiÃ© Province', '7', 'BIE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cabinda Province', '7', 'CAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuando Cubango Province', '7', 'CCU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuanza Norte Province', '7', 'CNO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuanza Sul', '7', 'CUS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cunene Province', '7', 'CNN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Huambo Province', '7', 'HUA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HuÃ­la Province', '7', 'HUI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luanda Province', '7', 'LUA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lunda Norte Province', '7', 'LNO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lunda Sul Province', '7', 'LSU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Malanje Province', '7', 'MAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moxico Province', '7', 'MOX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('UÃ­ge Province', '7', 'UIG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zaire Province', '7', 'ZAI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barbuda', '10', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Redonda', '10', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint George Parish', '10', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint John Parish', '10', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Mary Parish', '10', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Paul Parish', '10', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Peter Parish', '10', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Philip Parish', '10', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buenos Aires', '11', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Catamarca', '11', 'K', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chaco', '11', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chubut', '11', 'U', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ciudad AutÃ³noma de Buenos Aires', '11', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÃ³rdoba', '11', 'X', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Corrientes', '11', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Entre RÃ­os', '11', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Formosa', '11', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jujuy', '11', 'Y', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Pampa', '11', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Rioja', '11', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mendoza', '11', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Misiones', '11', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NeuquÃ©n', '11', 'Q', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÃ­o Negro', '11', 'R', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salta', '11', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Juan', '11', 'J', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Luis', '11', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Cruz', '11', 'Z', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Fe', '11', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santiago del Estero', '11', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tierra del Fuego', '11', 'V', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TucumÃ¡n', '11', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aragatsotn Region', '12', 'AG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ararat Province', '12', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Armavir Region', '12', 'AV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gegharkunik Province', '12', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kotayk Region', '12', 'KT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lori Region', '12', 'LO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shirak Region', '12', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Syunik Province', '12', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tavush Region', '12', 'TV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vayots Dzor Region', '12', 'VD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yerevan', '12', 'ER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Australian Capital Territory', '14', 'ACT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New South Wales', '14', 'NSW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Territory', '14', 'NT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Queensland', '14', 'QLD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Australia', '14', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tasmania', '14', 'TAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Victoria', '14', 'VIC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Australia', '14', 'WA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Burgenland', '15', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carinthia', '15', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lower Austria', '15', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salzburg', '15', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Styria', '15', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tyrol', '15', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper Austria', '15', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vienna', '15', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vorarlberg', '15', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Absheron District', '16', 'ABS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agdam District', '16', 'AGM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agdash District', '16', 'AGS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aghjabadi District', '16', 'AGC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agstafa District', '16', 'AGA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agsu District', '16', 'AGU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Astara District', '16', 'AST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Babek District', '16', 'BAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baku', '16', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balakan District', '16', 'BAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barda District', '16', 'BAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beylagan District', '16', 'BEY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bilasuvar District', '16', 'BIL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dashkasan District', '16', 'DAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fizuli District', '16', 'FUZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ganja', '16', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÉ™dÉ™bÉ™y', '16', 'GAD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gobustan District', '16', 'QOB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Goranboy District', '16', 'GOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Goychay', '16', 'GOY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Goygol District', '16', 'GYG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hajigabul District', '16', 'HAC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Imishli District', '16', 'IMI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ismailli District', '16', 'ISM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jabrayil District', '16', 'CAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jalilabad District', '16', 'CAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Julfa District', '16', 'CUL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalbajar District', '16', 'KAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kangarli District', '16', 'KAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khachmaz District', '16', 'XAC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khizi District', '16', 'XIZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khojali District', '16', 'XCI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kurdamir District', '16', 'KUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lachin District', '16', 'LAC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lankaran', '16', 'LAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lankaran District', '16', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lerik District', '16', 'LER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Martuni', '16', 'XVD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masally District', '16', 'MAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mingachevir', '16', 'MI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakhchivan Autonomous Republic', '16', 'NX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Neftchala District', '16', 'NEF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oghuz District', '16', 'OGU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ordubad District', '16', 'ORD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qabala District', '16', 'QAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qakh District', '16', 'QAX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qazakh District', '16', 'QAZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quba District', '16', 'QBA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qubadli District', '16', 'QBI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qusar District', '16', 'QUS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saatly District', '16', 'SAT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sabirabad District', '16', 'SAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sadarak District', '16', 'SAD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salyan District', '16', 'SAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samukh District', '16', 'SMX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shabran District', '16', 'SBN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shahbuz District', '16', 'SAH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shaki', '16', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shaki District', '16', 'SAK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shamakhi District', '16', 'SMI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shamkir District', '16', 'SKR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sharur District', '16', 'SAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shirvan', '16', 'SR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shusha District', '16', 'SUS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siazan District', '16', 'SIY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sumqayit', '16', 'SM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tartar District', '16', 'TAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tovuz District', '16', 'TOV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ujar District', '16', 'UCA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yardymli District', '16', 'YAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yevlakh', '16', 'YE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yevlakh District', '16', 'YEV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zangilan District', '16', 'ZAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zaqatala District', '16', 'ZAQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zardab District', '16', 'ZAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Capital', '18', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central', '18', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Muharraq', '18', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern', '18', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern', '18', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bagerhat District', '19', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bahadia', '19', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bandarban District', '19', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barguna District', '19', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barisal District', '19', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barisal Division', '19', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bhola District', '19', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bogra District', '19', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brahmanbaria District', '19', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chandpur District', '19', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chapai Nawabganj District', '19', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chittagong District', '19', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chittagong Division', '19', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chuadanga District', '19', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Comilla District', '19', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cox s Bazar District', '19', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dhaka District', '19', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dhaka Division', '19', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dinajpur District', '19', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Faridpur District', '19', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Feni District', '19', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaibandha District', '19', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gazipur District', '19', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gopalganj District', '19', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Habiganj District', '19', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jamalpur District', '19', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jessore District', '19', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jhalokati District', '19', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jhenaidah District', '19', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Joypurhat District', '19', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khagrachari District', '19', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khulna District', '19', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khulna Division', '19', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kishoreganj District', '19', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kurigram District', '19', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kushtia District', '19', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lakshmipur District', '19', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lalmonirhat District', '19', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madaripur District', '19', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meherpur District', '19', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moulvibazar District', '19', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Munshiganj District', '19', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mymensingh District', '19', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mymensingh Division', '19', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naogaon District', '19', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Narail District', '19', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Narayanganj District', '19', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Natore District', '19', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Netrokona District', '19', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nilphamari District', '19', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Noakhali District', '19', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pabna District', '19', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Panchagarh District', '19', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Patuakhali District', '19', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pirojpur District', '19', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rajbari District', '19', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rajshahi District', '19', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rajshahi Division', '19', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rangamati Hill District', '19', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rangpur District', '19', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rangpur Division', '19', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Satkhira District', '19', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shariatpur District', '19', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sherpur District', '19', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sirajganj District', '19', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sunamganj District', '19', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sylhet District', '19', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sylhet Division', '19', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tangail District', '19', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thakurgaon District', '19', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Christ Church', '20', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Andrew', '20', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint George', '20', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint James', '20', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint John', '20', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Joseph', '20', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Lucy', '20', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Michael', '20', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Peter', '20', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Philip', '20', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Thomas', '20', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brest Region', '21', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gomel Region', '21', 'HO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grodno Region', '21', 'HR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Minsk', '21', 'HM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Minsk Region', '21', 'MI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mogilev Region', '21', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vitebsk Region', '21', 'VI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antwerp', '22', 'VAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brussels-Capital Region', '22', 'BRU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Flanders', '22', 'VOV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Flanders', '22', 'VLG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Flemish Brabant', '22', 'VBR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hainaut', '22', 'WHT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LiÃ¨ge', '22', 'WLG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Limburg', '22', 'VLI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luxembourg', '22', 'WLX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Namur', '22', 'WNA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wallonia', '22', 'WAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Walloon Brabant', '22', 'WBR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Flanders', '22', 'VWV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Belize District', '23', 'BZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cayo District', '23', 'CY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Corozal District', '23', 'CZL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orange Walk District', '23', 'OW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stann Creek District', '23', 'SC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toledo District', '23', 'TOL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alibori Department', '24', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Atakora Department', '24', 'AK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Atlantique Department', '24', 'AQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Borgou Department', '24', 'BO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Collines Department', '24', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Donga Department', '24', 'DO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kouffo Department', '24', 'KO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Littoral Department', '24', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mono Department', '24', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OuÃ©mÃ© Department', '24', 'OU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plateau Department', '24', 'PL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zou Department', '24', 'ZO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Devonshire', '25', 'DEV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hamilton', '25', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paget', '25', 'PAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pembroke', '25', 'PEM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint George s', '25', 'SGE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sandys', '25', 'SAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Smith s', '25', 'SMI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southampton', '25', 'SOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Warwick', '25', 'WAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bumthang District', '26', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chukha District', '26', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dagana District', '26', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gasa District', '26', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haa District', '26', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lhuntse District', '26', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mongar District', '26', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paro District', '26', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pemagatshel District', '26', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Punakha District', '26', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samdrup Jongkhar District', '26', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samtse District', '26', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sarpang District', '26', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thimphu District', '26', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trashigang District', '26', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trongsa District', '26', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tsirang District', '26', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wangdue Phodrang District', '26', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zhemgang District', '26', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beni Department', '27', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chuquisaca Department', '27', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cochabamba Department', '27', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Paz Department', '27', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oruro Department', '27', 'O', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pando Department', '27', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PotosÃ­ Department', '27', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Cruz Department', '27', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarija Department', '27', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bonaire', '155', 'BQ1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saba', '155', 'BQ2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sint Eustatius', '155', 'BQ3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bosnian Podrinje Canton', '28', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BrÄko District', '28', 'BRC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton 10', '28', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Bosnia Canton', '28', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Federation of Bosnia and Herzegovina', '28', 'BIH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Herzegovina-Neretva Canton', '28', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Posavina Canton', '28', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republika Srpska', '28', 'SRP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sarajevo Canton', '28', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tuzla Canton', '28', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Una-Sana Canton', '28', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Herzegovina Canton', '28', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zenica-Doboj Canton', '28', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central District', '29', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ghanzi District', '29', 'GH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kgalagadi District', '29', 'KG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kgatleng District', '29', 'KL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kweneng District', '29', 'KW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngamiland', '29', 'NG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North-East District', '29', 'NE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North-West District', '29', 'NW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South-East District', '29', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern District', '29', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Acre', '31', 'AC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alagoas', '31', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AmapÃ¡', '31', 'AP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amazonas', '31', 'AM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bahia', '31', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CearÃ¡', '31', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Distrito Federal', '31', 'DF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('EspÃ­rito Santo', '31', 'ES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GoiÃ¡s', '31', 'GO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MaranhÃ£o', '31', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mato Grosso', '31', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mato Grosso do Sul', '31', 'MS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Minas Gerais', '31', 'MG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ParÃ¡', '31', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ParaÃ­ba', '31', 'PB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ParanÃ¡', '31', 'PR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pernambuco', '31', 'PE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PiauÃ­', '31', 'PI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rio de Janeiro', '31', 'RJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rio Grande do Norte', '31', 'RN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rio Grande do Sul', '31', 'RS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RondÃ´nia', '31', 'RO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Roraima', '31', 'RR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Catarina', '31', 'SC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ£o Paulo', '31', 'SP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sergipe', '31', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tocantins', '31', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Belait District', '33', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brunei-Muara District', '33', 'BM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Temburong District', '33', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tutong District', '33', 'TU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blagoevgrad Province', '34', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Burgas Province', '34', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dobrich Province', '34', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gabrovo Province', '34', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haskovo Province', '34', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kardzhali Province', '34', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyustendil Province', '34', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lovech Province', '34', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Montana Province', '34', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pazardzhik Province', '34', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pernik Province', '34', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pleven Province', '34', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plovdiv Province', '34', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Razgrad Province', '34', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ruse Province', '34', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shumen', '34', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Silistra Province', '34', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sliven Province', '34', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Smolyan Province', '34', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sofia City Province', '34', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sofia Province', '34', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stara Zagora Province', '34', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Targovishte Province', '34', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Varna Province', '34', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Veliko Tarnovo Province', '34', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vidin Province', '34', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vratsa Province', '34', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yambol Province', '34', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BalÃ© Province', '35', 'BAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bam Province', '35', 'BAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Banwa Province', '35', 'BAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BazÃ¨ga Province', '35', 'BAZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boucle du Mouhoun Region', '35', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bougouriba Province', '35', 'BGR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boulgou', '35', 'BLG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cascades Region', '35', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre', '35', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre-Est Region', '35', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre-Nord Region', '35', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre-Ouest Region', '35', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre-Sud Region', '35', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ComoÃ© Province', '35', 'COM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Est Region', '35', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ganzourgou Province', '35', 'GAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gnagna Province', '35', 'GNA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gourma Province', '35', 'GOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hauts-Bassins Region', '35', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Houet Province', '35', 'HOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ioba Province', '35', 'IOB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kadiogo Province', '35', 'KAD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÃ©nÃ©dougou Province', '35', 'KEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Komondjari Province', '35', 'KMD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kompienga Province', '35', 'KMP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kossi Province', '35', 'KOS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KoulpÃ©logo Province', '35', 'KOP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kouritenga Province', '35', 'KOT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KourwÃ©ogo Province', '35', 'KOW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ©raba Province', '35', 'LER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loroum Province', '35', 'LOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mouhoun', '35', 'MOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nahouri Province', '35', 'NAO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Namentenga Province', '35', 'NAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nayala Province', '35', 'NAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nord Region, Burkina Faso', '35', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Noumbiel Province', '35', 'NOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oubritenga Province', '35', 'OUB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oudalan Province', '35', 'OUD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PassorÃ© Province', '35', 'PAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plateau-Central Region', '35', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Poni Province', '35', 'PON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sahel Region', '35', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SanguiÃ© Province', '35', 'SNG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sanmatenga Province', '35', 'SMT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ©no Province', '35', 'SEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sissili Province', '35', 'SIS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Soum Province', '35', 'SOM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sourou Province', '35', 'SOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sud-Ouest Region', '35', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tapoa Province', '35', 'TAP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tuy Province', '35', 'TUI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yagha Province', '35', 'YAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yatenga Province', '35', 'YAT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ziro Province', '35', 'ZIR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zondoma Province', '35', 'ZON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ZoundwÃ©ogo Province', '35', 'ZOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bubanza Province', '36', 'BB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bujumbura Mairie Province', '36', 'BM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bujumbura Rural Province', '36', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bururi Province', '36', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cankuzo Province', '36', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cibitoke Province', '36', 'CI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gitega Province', '36', 'GI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karuzi Province', '36', 'KR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kayanza Province', '36', 'KY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kirundo Province', '36', 'KI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Makamba Province', '36', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Muramvya Province', '36', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Muyinga Province', '36', 'MY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mwaro Province', '36', 'MW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngozi Province', '36', 'NG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rumonge Province', '36', 'RM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rutana Province', '36', 'RT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ruyigi Province', '36', 'RY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Banteay Meanchey', '37', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Battambang', '37', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kampong Cham', '37', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kampong Chhnang', '37', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kampong Speu', '37', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kampong Thom', '37', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kampot', '37', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kandal', '37', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kep', '37', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koh Kong', '37', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kratie', '37', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mondulkiri', '37', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oddar Meanchey', '37', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pailin', '37', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phnom Penh', '37', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Preah Vihear', '37', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prey Veng', '37', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pursat', '37', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ratanakiri', '37', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siem Reap', '37', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sihanoukville', '37', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stung Treng', '37', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Svay Rieng', '37', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Takeo', '37', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adamawa', '38', 'AD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre', '38', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East', '38', 'ES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Far North', '38', 'EN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Littoral', '38', 'LT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North', '38', 'NO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northwest', '38', 'NW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South', '38', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southwest', '38', 'SW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West', '38', 'OU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alberta', '39', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('British Columbia', '39', 'BC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manitoba', '39', 'MB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Brunswick', '39', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Newfoundland and Labrador', '39', 'NL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northwest Territories', '39', 'NT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nova Scotia', '39', 'NS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nunavut', '39', 'NU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ontario', '39', 'ON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prince Edward Island', '39', 'PE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quebec', '39', 'QC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saskatchewan', '39', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yukon', '39', 'YT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barlavento Islands', '40', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boa Vista', '40', 'BV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brava', '40', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maio Municipality', '40', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mosteiros', '40', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paul', '40', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Porto Novo', '40', 'PN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Praia', '40', 'PR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ribeira Brava Municipality', '40', 'RB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ribeira Grande', '40', 'RG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ribeira Grande de Santiago', '40', 'RS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sal', '40', 'SL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Catarina', '40', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Catarina do Fogo', '40', 'CF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Cruz', '40', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ£o Domingos', '40', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ£o Filipe', '40', 'SF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ£o LourenÃ§o dos Ã“rgÃ£os', '40', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ£o Miguel', '40', 'SM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ£o Vicente', '40', 'SV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sotavento Islands', '40', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarrafal', '40', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarrafal de SÃ£o Nicolau', '40', 'TS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bamingui-Bangoran Prefecture', '42', 'BB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bangui', '42', 'BGF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basse-Kotto Prefecture', '42', 'BK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haut-Mbomou Prefecture', '42', 'HM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-Kotto Prefecture', '42', 'HK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÃ©mo Prefecture', '42', 'KG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lobaye Prefecture', '42', 'LB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MambÃ©rÃ©-KadÃ©Ã¯', '42', 'HS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mbomou Prefecture', '42', 'MB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nana-GrÃ©bizi Economic Prefecture', '42', 'KB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nana-MambÃ©rÃ© Prefecture', '42', 'NM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ombella-M Poko Prefecture', '42', 'MP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouaka Prefecture', '42', 'UK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouham Prefecture', '42', 'AC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouham-PendÃ© Prefecture', '42', 'OP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sangha-MbaÃ©rÃ©', '42', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vakaga Prefecture', '42', 'VK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bahr el Gazel', '43', 'BG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batha', '43', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Borkou', '43', 'BO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chari-Baguirmi', '43', 'CB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ennedi-Est', '43', 'EE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ennedi-Ouest', '43', 'EO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GuÃ©ra', '43', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hadjer-Lamis', '43', 'HL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kanem', '43', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lac', '43', 'LC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Logone Occidental', '43', 'LO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Logone Oriental', '43', 'LR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mandoul', '43', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayo-Kebbi Est', '43', 'ME', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayo-Kebbi Ouest', '43', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moyen-Chari', '43', 'MC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('N Djamena', '43', 'ND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OuaddaÃ¯', '43', 'OD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salamat', '43', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sila', '43', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TandjilÃ©', '43', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tibesti', '43', 'TI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wadi Fira', '43', 'WF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AisÃ©n del General Carlos IbaÃ±ez del Campo', '44', 'AI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antofagasta', '44', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arica y Parinacota', '44', 'AP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Atacama', '44', 'AT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BiobÃ­o', '44', 'BI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coquimbo', '44', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La AraucanÃ­a', '44', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Libertador General Bernardo O Higgins', '44', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Los Lagos', '44', 'LL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Los RÃ­os', '44', 'LR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Magallanes y de la AntÃ¡rtica Chilena', '44', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maule', '44', 'ML', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‘uble', '44', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RegiÃ³n Metropolitana de Santiago', '44', 'RM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TarapacÃ¡', '44', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ValparaÃ­so', '44', 'VS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anhui', '45', 'AH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beijing', '45', 'BJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chongqing', '45', 'CQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fujian', '45', 'FJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gansu', '45', 'GS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guangdong', '45', 'GD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guangxi Zhuang', '45', 'GX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guizhou', '45', 'GZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hainan', '45', 'HI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hebei', '45', 'HE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Heilongjiang', '45', 'HL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Henan', '45', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hong Kong SAR', '45', 'HK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hubei', '45', 'HB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hunan', '45', 'HN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Inner Mongolia', '45', 'NM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jiangsu', '45', 'JS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jiangxi', '45', 'JX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jilin', '45', 'JL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Liaoning', '45', 'LN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Macau SAR', '45', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ningxia Huizu', '45', 'NX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qinghai', '45', 'QH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shaanxi', '45', 'SN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shandong', '45', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shanghai', '45', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shanxi', '45', 'SX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sichuan', '45', 'SC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taiwan', '45', 'TW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tianjin', '45', 'TJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Xinjiang', '45', 'XJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Xizang', '45', 'XZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yunnan', '45', 'YN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zhejiang', '45', 'ZJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amazonas', '48', 'AMA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antioquia', '48', 'ANT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arauca', '48', 'ARA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ArchipiÃ©lago de San AndrÃ©s, Providencia y Santa Catalina', '48', 'SAP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AtlÃ¡ntico', '48', 'ATL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BogotÃ¡ D.C.', '48', 'DC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BolÃ­var', '48', 'BOL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BoyacÃ¡', '48', 'BOY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caldas', '48', 'CAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CaquetÃ¡', '48', 'CAQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Casanare', '48', 'CAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cauca', '48', 'CAU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cesar', '48', 'CES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ChocÃ³', '48', 'CHO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÃ³rdoba', '48', 'COR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cundinamarca', '48', 'CUN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GuainÃ­a', '48', 'GUA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guaviare', '48', 'GUV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Huila', '48', 'HUI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Guajira', '48', 'LAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Magdalena', '48', 'MAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meta', '48', 'MET', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NariÃ±o', '48', 'NAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Norte de Santander', '48', 'NSA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Putumayo', '48', 'PUT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('QuindÃ­o', '48', 'QUI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Risaralda', '48', 'RIS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santander', '48', 'SAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sucre', '48', 'SUC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tolima', '48', 'TOL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valle del Cauca', '48', 'VAC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VaupÃ©s', '48', 'VAU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vichada', '48', 'VID', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anjouan', '49', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grande Comore', '49', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MohÃ©li', '49', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bouenza Department', '50', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brazzaville', '50', 'BZV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuvette Department', '50', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuvette-Ouest Department', '50', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kouilou Department', '50', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ©koumou Department', '50', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Likouala Department', '50', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Niari Department', '50', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plateaux Department', '50', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pointe-Noire', '50', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pool Department', '50', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sangha Department', '50', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alajuela Province', '53', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guanacaste Province', '53', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Heredia Province', '53', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LimÃ³n Province', '53', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Provincia de Cartago', '53', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puntarenas Province', '53', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San JosÃ© Province', '53', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abidjan', '54', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AgnÃ©by', '54', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bafing Region', '54', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bas-Sassandra District', '54', 'BS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bas-Sassandra Region', '54', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ComoÃ© District', '54', 'CM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DenguÃ©lÃ© District', '54', 'DN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DenguÃ©lÃ© Region', '54', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dix-Huit Montagnes', '54', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fromager', '54', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÃ´h-Djiboua District', '54', 'GD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haut-Sassandra', '54', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lacs District', '54', 'LC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lacs Region', '54', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lagunes District', '54', 'LG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lagunes region', '54', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MarahouÃ© Region', '54', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Montagnes District', '54', 'MG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moyen-Cavally', '54', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moyen-ComoÃ©', '54', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('N zi-ComoÃ©', '54', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sassandra-MarahouÃ© District', '54', 'SM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Savanes Region', '54', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sud-Bandama', '54', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sud-ComoÃ©', '54', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VallÃ©e du Bandama District', '54', 'VB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VallÃ©e du Bandama Region', '54', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Woroba District', '54', 'WR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Worodougou', '54', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yamoussoukro', '54', 'YM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zanzan Region', '54', 'ZZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bjelovar-Bilogora', '55', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brod-Posavina', '55', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dubrovnik-Neretva', '55', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Istria', '55', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karlovac', '55', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koprivnica-KriÅ¾evci', '55', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Krapina-Zagorje', '55', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lika-Senj', '55', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MeÄ‘imurje', '55', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Osijek-Baranja', '55', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PoÅ¾ega-Slavonia', '55', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Primorje-Gorski Kotar', '55', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å ibenik-Knin', '55', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sisak-Moslavina', '55', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Split-Dalmatia', '55', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VaraÅ¾din', '55', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Virovitica-Podravina', '55', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vukovar-Syrmia', '55', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zadar', '55', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zagreb', '55', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zagreb', '55', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Artemisa Province', '56', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CamagÃ¼ey Province', '56', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ciego de Ãvila Province', '56', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cienfuegos Province', '56', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Granma Province', '56', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GuantÃ¡namo Province', '56', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Havana Province', '56', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HolguÃ­n Province', '56', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isla de la Juventud', '56', '99', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Las Tunas Province', '56', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matanzas Province', '56', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayabeque Province', '56', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pinar del RÃ­o Province', '56', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sancti SpÃ­ritus Province', '56', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santiago de Cuba Province', '56', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Villa Clara Province', '56', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Famagusta District (MaÄŸusa)', '57', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyrenia District (Keryneia)', '57', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Larnaca District (Larnaka)', '57', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Limassol District (Leymasun)', '57', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nicosia District (LefkoÅŸa)', '57', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paphos District (Pafos)', '57', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BeneÅ¡ov', '58', '201', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beroun', '58', '202', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blansko', '58', '641', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÅ™eclav', '58', '644', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brno-mÄ›sto', '58', '642', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brno-venkov', '58', '643', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BruntÃ¡l', '58', '801', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒeskÃ¡ LÃ­pa', '58', '511', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒeskÃ© BudÄ›jovice', '58', '311', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒeskÃ½ Krumlov', '58', '312', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cheb', '58', '411', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chomutov', '58', '422', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chrudim', '58', '531', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DÄ›ÄÃ­n', '58', '421', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DomaÅ¾lice', '58', '321', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FrÃ½dek-MÃ­stek', '58', '802', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HavlÃ­ÄkÅ¯v Brod', '58', '631', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HodonÃ­n', '58', '645', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hradec KrÃ¡lovÃ©', '58', '521', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jablonec nad Nisou', '58', '512', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JesenÃ­k', '58', '711', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JiÄÃ­n', '58', '522', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jihlava', '58', '632', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JihoÄeskÃ½ kraj', '58', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JihomoravskÃ½ kraj', '58', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JindÅ™ichÅ¯v Hradec', '58', '313', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KarlovarskÃ½ kraj', '58', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karlovy Vary', '58', '412', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KarvinÃ¡', '58', '803', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kladno', '58', '203', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Klatovy', '58', '322', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KolÃ­n', '58', '204', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kraj VysoÄina', '58', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KrÃ¡lovÃ©hradeckÃ½ kraj', '58', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KromÄ›Å™Ã­Å¾', '58', '721', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KutnÃ¡ Hora', '58', '205', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Liberec', '58', '513', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LibereckÃ½ kraj', '58', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LitomÄ›Å™ice', '58', '423', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Louny', '58', '424', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÄ›lnÃ­k', '58', '206', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MladÃ¡ Boleslav', '58', '207', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MoravskoslezskÃ½ kraj', '58', '80', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Most', '58', '425', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NÃ¡chod', '58', '523', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NovÃ½ JiÄÃ­n', '58', '804', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nymburk', '58', '208', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Olomouc', '58', '712', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OlomouckÃ½ kraj', '58', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Opava', '58', '805', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ostrava-mÄ›sto', '58', '806', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pardubice', '58', '532', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PardubickÃ½ kraj', '58', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PelhÅ™imov', '58', '633', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÃ­sek', '58', '314', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PlzeÅˆ-jih', '58', '324', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PlzeÅˆ-mÄ›sto', '58', '323', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PlzeÅˆ-sever', '58', '325', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PlzeÅˆskÃ½ kraj', '58', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prachatice', '58', '315', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Praha-vÃ½chod', '58', '209', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Praha-zÃ¡pad', '58', '20A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Praha, HlavnÃ­ mÄ›sto', '58', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÅ™erov', '58', '714', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÅ™Ã­bram', '58', '20B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ProstÄ›jov', '58', '713', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RakovnÃ­k', '58', '20C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rokycany', '58', '326', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rychnov nad KnÄ›Å¾nou', '58', '524', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Semily', '58', '514', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sokolov', '58', '413', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Strakonice', '58', '316', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('StÅ™edoÄeskÃ½ kraj', '58', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å umperk', '58', '715', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Svitavy', '58', '533', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÃ¡bor', '58', '317', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tachov', '58', '327', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Teplice', '58', '426', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÅ™ebÃ­Ä', '58', '634', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trutnov', '58', '525', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('UherskÃ© HradiÅ¡tÄ›', '58', '722', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÃšsteckÃ½ kraj', '58', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÃšstÃ­ nad Labem', '58', '427', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÃšstÃ­ nad OrlicÃ­', '58', '534', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VsetÃ­n', '58', '723', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VyÅ¡kov', '58', '646', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½ÄÃ¡r nad SÃ¡zavou', '58', '635', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ZlÃ­n', '58', '724', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ZlÃ­nskÃ½ kraj', '58', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Znojmo', '58', '647', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bas-UÃ©lÃ©', '51', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‰quateur', '51', 'EQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haut-Katanga', '51', 'HK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haut-Lomami', '51', 'HL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haut-UÃ©lÃ©', '51', 'HU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ituri', '51', 'IT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KasaÃ¯', '51', 'KS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KasaÃ¯ Central', '51', 'KC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KasaÃ¯ Oriental', '51', 'KE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kinshasa', '51', 'KN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kongo Central', '51', 'BC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kwango', '51', 'KG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kwilu', '51', 'KL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lomami', '51', 'LO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lualaba', '51', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mai-Ndombe', '51', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maniema', '51', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mongala', '51', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nord-Kivu', '51', 'NK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nord-Ubangi', '51', 'NU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sankuru', '51', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sud-Kivu', '51', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sud-Ubangi', '51', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tanganyika', '51', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tshopo', '51', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tshuapa', '51', 'TU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Capital Region of Denmark', '59', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Denmark Region', '59', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Denmark Region', '59', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Region of Southern Denmark', '59', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Region Zealand', '59', '85', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ali Sabieh Region', '60', 'AS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arta Region', '60', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dikhil Region', '60', 'DI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Djibouti', '60', 'DJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Obock Region', '60', 'OB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tadjourah Region', '60', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Andrew Parish', '61', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint David Parish', '61', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint George Parish', '61', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint John Parish', '61', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Joseph Parish', '61', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Luke Parish', '61', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Mark Parish', '61', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Patrick Parish', '61', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Paul Parish', '61', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Peter Parish', '61', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Azua Province', '62', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baoruco Province', '62', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barahona Province', '62', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DajabÃ³n Province', '62', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Distrito Nacional', '62', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Duarte Province', '62', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Seibo Province', '62', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Espaillat Province', '62', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hato Mayor Province', '62', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hermanas Mirabal Province', '62', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Independencia', '62', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Altagracia Province', '62', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Romana Province', '62', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Vega Province', '62', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MarÃ­a Trinidad SÃ¡nchez Province', '62', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MonseÃ±or Nouel Province', '62', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monte Cristi Province', '62', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monte Plata Province', '62', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pedernales Province', '62', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Peravia Province', '62', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puerto Plata Province', '62', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SamanÃ¡ Province', '62', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San CristÃ³bal Province', '62', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San JosÃ© de Ocoa Province', '62', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Juan Province', '62', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Pedro de MacorÃ­s', '62', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ¡nchez RamÃ­rez Province', '62', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santiago Province', '62', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santiago RodrÃ­guez Province', '62', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santo Domingo Province', '62', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valverde Province', '62', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aileu municipality', '63', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ainaro Municipality', '63', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baucau Municipality', '63', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bobonaro Municipality', '63', 'BO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cova Lima Municipality', '63', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dili municipality', '63', 'DI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ermera District', '63', 'ER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LautÃ©m Municipality', '63', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LiquiÃ§Ã¡ Municipality', '63', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manatuto District', '63', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manufahi Municipality', '63', 'MF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Viqueque Municipality', '63', 'VI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Azuay', '64', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BolÃ­var', '64', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CaÃ±ar', '64', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carchi', '64', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chimborazo', '64', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cotopaxi', '64', 'X', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Oro', '64', 'O', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Esmeraldas', '64', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GalÃ¡pagos', '64', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guayas', '64', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Imbabura', '64', 'I', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loja', '64', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Los RÃ­os', '64', 'R', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ManabÃ­', '64', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Morona-Santiago', '64', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Napo', '64', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orellana', '64', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pastaza', '64', 'Y', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pichincha', '64', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Elena', '64', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santo Domingo de los TsÃ¡chilas', '64', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SucumbÃ­os', '64', 'U', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tungurahua', '64', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamora Chinchipe', '64', 'Z', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alexandria', '65', 'ALX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aswan', '65', 'ASN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Asyut', '65', 'AST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beheira', '65', 'BH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beni Suef', '65', 'BNS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cairo', '65', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dakahlia', '65', 'DK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Damietta', '65', 'DT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Faiyum', '65', 'FYM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gharbia', '65', 'GH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Giza', '65', 'GZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ismailia', '65', 'IS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kafr el-Sheikh', '65', 'KFS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luxor', '65', 'LX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matrouh', '65', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Minya', '65', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monufia', '65', 'MNF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Valley', '65', 'WAD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Sinai', '65', 'SIN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Port Said', '65', 'PTS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qalyubia', '65', 'KB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qena', '65', 'KN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Red Sea', '65', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sharqia', '65', 'SHR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sohag', '65', 'SHG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Sinai', '65', 'JS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Suez', '65', 'SUZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AhuachapÃ¡n Department', '66', 'AH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CabaÃ±as Department', '66', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chalatenango Department', '66', 'CH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CuscatlÃ¡n Department', '66', 'CU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Libertad Department', '66', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Paz Department', '66', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La UniÃ³n Department', '66', 'UN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MorazÃ¡n Department', '66', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Miguel Department', '66', 'SM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Salvador Department', '66', 'SS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Vicente Department', '66', 'SV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Ana Department', '66', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sonsonate Department', '66', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('UsulutÃ¡n Department', '66', 'US', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AnnobÃ³n Province', '67', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bioko Norte Province', '67', 'BN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bioko Sur Province', '67', 'BS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centro Sur Province', '67', 'CS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Insular Region', '67', 'I', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KiÃ©-Ntem Province', '67', 'KN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Litoral Province', '67', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÃ­o Muni', '67', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wele-Nzas Province', '67', 'WN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anseba Region', '68', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Debub Region', '68', 'DU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gash-Barka Region', '68', 'GB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maekel Region', '68', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Red Sea Region', '68', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Red Sea Region', '68', 'DK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Harju County', '69', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hiiu County', '69', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ida-Viru County', '69', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÃ¤rva County', '69', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÃµgeva County', '69', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ¤Ã¤ne County', '69', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ¤Ã¤ne-Viru County', '69', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÃ¤rnu County', '69', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÃµlva County', '69', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rapla County', '69', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saare County', '69', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tartu County', '69', '78', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valga County', '69', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Viljandi County', '69', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÃµru County', '69', '86', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Addis Ababa', '70', 'AA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Afar Region', '70', 'AF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amhara Region', '70', 'AM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benishangul-Gumuz Region', '70', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dire Dawa', '70', 'DD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gambela Region', '70', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Harari Region', '70', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oromia Region', '70', 'OR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Somali Region', '70', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Nations, Nationalities, and Peoples  Region', '70', 'SN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tigray Region', '70', 'TI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ba', '73', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bua', '73', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cakaudrove', '73', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Division', '73', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Division', '73', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kadavu', '73', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lau', '73', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lomaiviti', '73', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Macuata', '73', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nadroga-Navosa', '73', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naitasiri', '73', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Namosi', '73', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Division', '73', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ra', '73', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rewa', '73', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rotuma', '73', 'R', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Serua', '73', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tailevu', '73', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Division', '73', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã…land Islands', '74', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Finland', '74', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Ostrobothnia', '74', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Finland Proper', '74', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kainuu', '74', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kymenlaakso', '74', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lapland', '74', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Karelia', '74', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Ostrobothnia', '74', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Savonia', '74', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ostrobothnia', '74', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÃ¤ijÃ¤nne Tavastia', '74', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pirkanmaa', '74', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Satakunta', '74', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Karelia', '74', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Ostrobothnia', '74', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Savonia', '74', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tavastia Proper', '74', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uusimaa', '74', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ain', '75', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aisne', '75', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Allier', '75', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alpes-de-Haute-Provence', '75', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alpes-Maritimes', '75', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alsace', '75', '6AE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ArdÃ¨che', '75', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ardennes', '75', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AriÃ¨ge', '75', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aube', '75', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aude', '75', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Auvergne-RhÃ´ne-Alpes', '75', 'ARA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aveyron', '75', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bas-Rhin', '75', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bouches-du-RhÃ´ne', '75', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bourgogne-Franche-ComtÃ©', '75', 'BFC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bretagne', '75', 'BRE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Calvados', '75', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cantal', '75', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre-Val de Loire', '75', 'CVL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Charente', '75', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Charente-Maritime', '75', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cher', '75', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Clipperton', '75', 'CP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CorrÃ¨ze', '75', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Corse', '75', '20R', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Corse-du-Sud', '75', '2A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÃ´te-d Or', '75', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÃ´tes-d Armor', '75', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Creuse', '75', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Deux-SÃ¨vres', '75', '79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dordogne', '75', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Doubs', '75', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DrÃ´me', '75', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Essonne', '75', '91', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eure', '75', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eure-et-Loir', '75', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FinistÃ¨re', '75', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('French Guiana', '75', '973', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('French Polynesia', '75', 'PF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('French Southern and Antarctic Lands', '75', 'TF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gard', '75', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gers', '75', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gironde', '75', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand-Est', '75', 'GES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guadeloupe', '75', '971', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haut-Rhin', '75', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-Corse', '75', '2B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-Garonne', '75', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-Loire', '75', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-Marne', '75', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-SaÃ´ne', '75', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-Savoie', '75', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haute-Vienne', '75', '87', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hautes-Alpes', '75', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hautes-PyrÃ©nÃ©es', '75', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hauts-de-France', '75', 'HDF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hauts-de-Seine', '75', '92', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ©rault', '75', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÃŽle-de-France', '75', 'IDF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ille-et-Vilaine', '75', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Indre', '75', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Indre-et-Loire', '75', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IsÃ¨re', '75', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jura', '75', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La RÃ©union', '75', '974', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Landes', '75', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loir-et-Cher', '75', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loire', '75', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loire-Atlantique', '75', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loiret', '75', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lot', '75', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lot-et-Garonne', '75', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LozÃ¨re', '75', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maine-et-Loire', '75', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manche', '75', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marne', '75', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Martinique', '75', '972', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayenne', '75', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayotte', '75', '976', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÃ©tropole de Lyon', '75', '69M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meurthe-et-Moselle', '75', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meuse', '75', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Morbihan', '75', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moselle', '75', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NiÃ¨vre', '75', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nord', '75', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Normandie', '75', 'NOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nouvelle-Aquitaine', '75', 'NAQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Occitanie', '75', 'OCC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oise', '75', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orne', '75', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paris', '75', '75C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pas-de-Calais', '75', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pays-de-la-Loire', '75', 'PDL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Provence-Alpes-CÃ´te-dâ€™Azur', '75', 'PAC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puy-de-DÃ´me', '75', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PyrÃ©nÃ©es-Atlantiques', '75', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PyrÃ©nÃ©es-Orientales', '75', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RhÃ´ne', '75', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Pierre and Miquelon', '75', 'PM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint-BarthÃ©lemy', '75', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint-Martin', '75', 'MF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SaÃ´ne-et-Loire', '75', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sarthe', '75', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Savoie', '75', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Seine-et-Marne', '75', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Seine-Maritime', '75', '76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Seine-Saint-Denis', '75', '93', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Somme', '75', '80', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarn', '75', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarn-et-Garonne', '75', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Territoire de Belfort', '75', '90', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Val-d Oise', '75', '95', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Val-de-Marne', '75', '94', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Var', '75', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vaucluse', '75', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VendÃ©e', '75', '85', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vienne', '75', '86', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vosges', '75', '88', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wallis and Futuna', '75', 'WF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yonne', '75', '89', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yvelines', '75', '78', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Estuaire Province', '79', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haut-OgoouÃ© Province', '79', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moyen-OgoouÃ© Province', '79', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NgouniÃ© Province', '79', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nyanga Province', '79', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OgoouÃ©-Ivindo Province', '79', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OgoouÃ©-Lolo Province', '79', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OgoouÃ©-Maritime Province', '79', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Woleu-Ntem Province', '79', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Banjul', '80', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central River Division', '80', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lower River Division', '80', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Bank Division', '80', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper River Division', '80', 'U', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Coast Division', '80', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adjara', '81', 'AJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Autonomous Republic of Abkhazia', '81', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guria', '81', 'GU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Imereti', '81', 'IM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kakheti', '81', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khelvachauri Municipality', '81', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kvemo Kartli', '81', 'KK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mtskheta-Mtianeti', '81', 'MM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Racha-Lechkhumi and Kvemo Svaneti', '81', 'RL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samegrelo-Zemo Svaneti', '81', 'SZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samtskhe-Javakheti', '81', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Senaki Municipality', '81', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shida Kartli', '81', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tbilisi', '81', 'TB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baden-WÃ¼rttemberg', '82', 'BW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bavaria', '82', 'BY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berlin', '82', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brandenburg', '82', 'BB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bremen', '82', 'HB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hamburg', '82', 'HH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hesse', '82', 'HE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lower Saxony', '82', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mecklenburg-Vorpommern', '82', 'MV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Rhine-Westphalia', '82', 'NW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rhineland-Palatinate', '82', 'RP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saarland', '82', 'SL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saxony', '82', 'SN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saxony-Anhalt', '82', 'ST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Schleswig-Holstein', '82', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thuringia', '82', 'TH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ahafo', '83', 'AF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ashanti', '83', 'AH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bono', '83', 'BO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bono East', '83', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central', '83', 'CP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern', '83', 'EP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Greater Accra', '83', 'AA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North East', '83', 'NE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern', '83', 'NP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oti', '83', 'OT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Savannah', '83', 'SV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper East', '83', 'UE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper West', '83', 'UW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Volta', '83', 'TV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western', '83', 'WP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western North', '83', 'WN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Achaea Regional Unit', '85', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aetolia-Acarnania Regional Unit', '85', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arcadia Prefecture', '85', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Argolis Regional Unit', '85', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Attica Region', '85', 'I', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boeotia Regional Unit', '85', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Greece Region', '85', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Macedonia', '85', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chania Regional Unit', '85', '94', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Corfu Prefecture', '85', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Corinthia Regional Unit', '85', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Crete Region', '85', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Drama Regional Unit', '85', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Attica Regional Unit', '85', 'A2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Macedonia and Thrace', '85', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Epirus Region', '85', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Euboea', '85', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grevena Prefecture', '85', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Imathia Regional Unit', '85', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ioannina Regional Unit', '85', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ionian Islands Region', '85', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karditsa Regional Unit', '85', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kastoria Regional Unit', '85', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kefalonia Prefecture', '85', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kilkis Regional Unit', '85', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kozani Prefecture', '85', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laconia', '85', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Larissa Prefecture', '85', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lefkada Regional Unit', '85', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pella Regional Unit', '85', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Peloponnese Region', '85', 'J', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phthiotis Prefecture', '85', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Preveza Prefecture', '85', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Serres Prefecture', '85', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Aegean', '85', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thessaloniki Regional Unit', '85', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Greece Region', '85', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Macedonia Region', '85', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carriacou and Petite Martinique', '87', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Andrew Parish', '87', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint David Parish', '87', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint George Parish', '87', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint John Parish', '87', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Mark Parish', '87', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Patrick Parish', '87', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alta Verapaz Department', '90', 'AV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baja Verapaz Department', '90', 'BV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chimaltenango Department', '90', 'CM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiquimula Department', '90', 'CQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Progreso Department', '90', 'PR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Escuintla Department', '90', 'ES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guatemala Department', '90', 'GU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Huehuetenango Department', '90', 'HU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Izabal Department', '90', 'IZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jalapa Department', '90', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jutiapa Department', '90', 'JU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PetÃ©n Department', '90', 'PE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quetzaltenango Department', '90', 'QZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('QuichÃ© Department', '90', 'QC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Retalhuleu Department', '90', 'RE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SacatepÃ©quez Department', '90', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Marcos Department', '90', 'SM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Rosa Department', '90', 'SR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SololÃ¡ Department', '90', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SuchitepÃ©quez Department', '90', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TotonicapÃ¡n Department', '90', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beyla Prefecture', '92', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boffa Prefecture', '92', 'BF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BokÃ© Prefecture', '92', 'BK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BokÃ© Region', '92', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Conakry', '92', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coyah Prefecture', '92', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dabola Prefecture', '92', 'DB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dalaba Prefecture', '92', 'DL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dinguiraye Prefecture', '92', 'DI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DubrÃ©ka Prefecture', '92', 'DU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Faranah Prefecture', '92', 'FA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ForÃ©cariah Prefecture', '92', 'FO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fria Prefecture', '92', 'FR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaoual Prefecture', '92', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GuÃ©ckÃ©dou Prefecture', '92', 'GU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kankan Prefecture', '92', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kankan Region', '92', 'K', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÃ©rouanÃ© Prefecture', '92', 'KE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kindia Prefecture', '92', 'KD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kindia Region', '92', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kissidougou Prefecture', '92', 'KS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koubia Prefecture', '92', 'KB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koundara Prefecture', '92', 'KN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kouroussa Prefecture', '92', 'KO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LabÃ© Prefecture', '92', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LabÃ© Region', '92', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ©louma Prefecture', '92', 'LE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lola Prefecture', '92', 'LO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Macenta Prefecture', '92', 'MC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mali Prefecture', '92', 'ML', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mamou Prefecture', '92', 'MM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mamou Region', '92', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mandiana Prefecture', '92', 'MD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NzÃ©rÃ©korÃ© Prefecture', '92', 'NZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NzÃ©rÃ©korÃ© Region', '92', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pita Prefecture', '92', 'PI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siguiri Prefecture', '92', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÃ©limÃ©lÃ© Prefecture', '92', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TouguÃ© Prefecture', '92', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yomou Prefecture', '92', 'YO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BafatÃ¡', '93', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Biombo Region', '93', 'BM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bolama Region', '93', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cacheu Region', '93', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GabÃº Region', '93', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leste Province', '93', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Norte Province', '93', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oio Region', '93', 'OI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quinara Region', '93', 'QU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sul Province', '93', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tombali Region', '93', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barima-Waini', '94', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuyuni-Mazaruni', '94', 'CU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Demerara-Mahaica', '94', 'DE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Berbice-Corentyne', '94', 'EB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Essequibo Islands-West Demerara', '94', 'ES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mahaica-Berbice', '94', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pomeroon-Supenaam', '94', 'PM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Potaro-Siparuni', '94', 'PT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper Demerara-Berbice', '94', 'UD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper Takutu-Upper Essequibo', '94', 'UT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Artibonite', '95', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centre', '95', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Anse', '95', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nippes', '95', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nord', '95', 'ND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nord-Est', '95', 'NE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nord-Ouest', '95', 'NO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouest', '95', 'OU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sud', '95', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sud-Est', '95', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AtlÃ¡ntida Department', '97', 'AT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bay Islands Department', '97', 'IB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Choluteca Department', '97', 'CH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ColÃ³n Department', '97', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Comayagua Department', '97', 'CM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CopÃ¡n Department', '97', 'CP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CortÃ©s Department', '97', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El ParaÃ­so Department', '97', 'EP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Francisco MorazÃ¡n Department', '97', 'FM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gracias a Dios Department', '97', 'GD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IntibucÃ¡ Department', '97', 'IN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Paz Department', '97', 'LP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lempira Department', '97', 'LE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ocotepeque Department', '97', 'OC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Olancho Department', '97', 'OL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa BÃ¡rbara Department', '97', 'SB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valle Department', '97', 'VA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yoro Department', '97', 'YO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central and Western', '98', 'HCW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern', '98', 'HEA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Islands', '98', 'NIS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kowloon City', '98', 'KKC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kwai Tsing', '98', 'NKT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kwun Tong', '98', 'KKT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North', '98', 'NNO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sai Kung', '98', 'NSK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sha Tin', '98', 'NST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sham Shui Po', '98', 'KSS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern', '98', 'HSO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tai Po', '98', 'NTP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tsuen Wan', '98', 'NTW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tuen Mun', '98', 'NTM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wan Chai', '98', 'HWC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wong Tai Sin', '98', 'KWT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yau Tsim Mong', '98', 'KYT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yuen Long', '98', 'NYL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ¡cs-Kiskun', '99', 'BK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baranya', '99', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©kÃ©s', '99', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©kÃ©scsaba', '99', 'BC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Borsod-AbaÃºj-ZemplÃ©n', '99', 'BZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Budapest', '99', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CsongrÃ¡d County', '99', 'CS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Debrecen', '99', 'DE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DunaÃºjvÃ¡ros', '99', 'DU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eger', '99', 'EG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‰rd', '99', 'ER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FejÃ©r County', '99', 'FE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GyÅ‘r', '99', 'GY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GyÅ‘r-Moson-Sopron County', '99', 'GS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HajdÃº-Bihar County', '99', 'HB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Heves County', '99', 'HE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ³dmezÅ‘vÃ¡sÃ¡rhely', '99', 'HV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÃ¡sz-Nagykun-Szolnok County', '99', 'JN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KaposvÃ¡r', '99', 'KV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KecskemÃ©t', '99', 'KM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KomÃ¡rom-Esztergom', '99', 'KE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Miskolc', '99', 'MI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nagykanizsa', '99', 'NK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NÃ³grÃ¡d County', '99', 'NO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NyÃ­regyhÃ¡za', '99', 'NY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÃ©cs', '99', 'PS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pest County', '99', 'PE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SalgÃ³tarjÃ¡n', '99', 'ST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Somogy County', '99', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sopron', '99', 'SN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Szabolcs-SzatmÃ¡r-Bereg County', '99', 'SZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Szeged', '99', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SzÃ©kesfehÃ©rvÃ¡r', '99', 'SF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SzekszÃ¡rd', '99', 'SS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Szolnok', '99', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Szombathely', '99', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TatabÃ¡nya', '99', 'TB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tolna County', '99', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vas County', '99', 'VA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VeszprÃ©m', '99', 'VM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VeszprÃ©m County', '99', 'VE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zala County', '99', 'ZA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zalaegerszeg', '99', 'ZE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Capital Region', '100', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Region', '100', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northeastern Region', '100', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northwestern Region', '100', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Peninsula Region', '100', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Region', '100', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Region', '100', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Westfjords', '100', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Andaman and Nicobar Islands', '101', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Andhra Pradesh', '101', 'AP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arunachal Pradesh', '101', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Assam', '101', 'AS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bihar', '101', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chandigarh', '101', 'CH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chhattisgarh', '101', 'CT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dadra and Nagar Haveli and Daman and Diu', '101', 'DH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Delhi', '101', 'DL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Goa', '101', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gujarat', '101', 'GJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haryana', '101', 'HR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Himachal Pradesh', '101', 'HP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jammu and Kashmir', '101', 'JK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jharkhand', '101', 'JH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karnataka', '101', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kerala', '101', 'KL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ladakh', '101', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lakshadweep', '101', 'LD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madhya Pradesh', '101', 'MP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maharashtra', '101', 'MH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manipur', '101', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meghalaya', '101', 'ML', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mizoram', '101', 'MZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nagaland', '101', 'NL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Odisha', '101', 'OR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puducherry', '101', 'PY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Punjab', '101', 'PB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rajasthan', '101', 'RJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sikkim', '101', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tamil Nadu', '101', 'TN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Telangana', '101', 'TG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tripura', '101', 'TR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uttar Pradesh', '101', 'UP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uttarakhand', '101', 'UT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Bengal', '101', 'WB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aceh', '102', 'AC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bali', '102', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Banten', '102', 'BT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bengkulu', '102', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DI Yogyakarta', '102', 'YO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DKI Jakarta', '102', 'JK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gorontalo', '102', 'GO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jambi', '102', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jawa Barat', '102', 'JB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jawa Tengah', '102', 'JT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jawa Timur', '102', 'JI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalimantan Barat', '102', 'KB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalimantan Selatan', '102', 'KS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalimantan Tengah', '102', 'KT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalimantan Timur', '102', 'KI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalimantan Utara', '102', 'KU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kepulauan Bangka Belitung', '102', 'BB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kepulauan Riau', '102', 'KR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lampung', '102', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maluku', '102', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maluku Utara', '102', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nusa Tenggara Barat', '102', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nusa Tenggara Timur', '102', 'NT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Papua', '102', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Papua Barat', '102', 'PB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Riau', '102', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulawesi Barat', '102', 'SR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulawesi Selatan', '102', 'SN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulawesi Tengah', '102', 'ST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulawesi Tenggara', '102', 'SG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulawesi Utara', '102', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sumatera Barat', '102', 'SB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sumatera Selatan', '102', 'SS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sumatera Utara', '102', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alborz', '103', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ardabil', '103', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bushehr', '103', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chaharmahal and Bakhtiari', '103', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Azerbaijan', '103', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fars', '103', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gilan', '103', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Golestan', '103', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hamadan', '103', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hormozgan', '103', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilam', '103', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isfahan', '103', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kerman', '103', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kermanshah', '103', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khuzestan', '103', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kohgiluyeh and Boyer-Ahmad', '103', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kurdistan', '103', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lorestan', '103', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Markazi', '103', '0', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mazandaran', '103', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Khorasan', '103', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qazvin', '103', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qom', '103', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Razavi Khorasan', '103', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Semnan', '103', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sistan and Baluchestan', '103', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Khorasan', '103', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tehran', '103', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Azarbaijan', '103', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yazd', '103', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zanjan', '103', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Anbar', '104', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Muthanna', '104', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al-QÄdisiyyah', '104', 'QA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Babylon', '104', 'BB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baghdad', '104', 'BG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basra', '104', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dhi Qar', '104', 'DQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Diyala', '104', 'DI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dohuk', '104', 'DA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Erbil', '104', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karbala', '104', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kirkuk', '104', 'KI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maysan', '104', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Najaf', '104', 'NA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nineveh', '104', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saladin', '104', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulaymaniyah', '104', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wasit', '104', 'WA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carlow', '105', 'CW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cavan', '105', 'CN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Clare', '105', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Connacht', '105', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cork', '105', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Donegal', '105', 'DL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dublin', '105', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Galway', '105', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kerry', '105', 'KY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kildare', '105', 'KE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kilkenny', '105', 'KK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laois', '105', 'LS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leinster', '105', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Limerick', '105', 'LK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Longford', '105', 'LD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Louth', '105', 'LH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayo', '105', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meath', '105', 'MH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monaghan', '105', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Munster', '105', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Offaly', '105', 'OY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Roscommon', '105', 'RN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sligo', '105', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tipperary', '105', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ulster', '105', 'U', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Waterford', '105', 'WD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Westmeath', '105', 'WH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wexford', '105', 'WX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wicklow', '105', 'WW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central District', '106', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haifa District', '106', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jerusalem District', '106', 'JM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern District', '106', 'Z', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern District', '106', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tel Aviv District', '106', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abruzzo', '107', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agrigento', '107', 'AG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alessandria', '107', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ancona', '107', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aosta Valley', '107', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Apulia', '107', '75', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ascoli Piceno', '107', 'AP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Asti', '107', 'AT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Avellino', '107', 'AV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barletta-Andria-Trani', '107', 'BT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basilicata', '107', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Belluno', '107', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benevento', '107', 'BN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bergamo', '107', 'BG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Biella', '107', 'BI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brescia', '107', 'BS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brindisi', '107', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Calabria', '107', '78', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caltanissetta', '107', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Campania', '107', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Campobasso', '107', 'CB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caserta', '107', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Catanzaro', '107', 'CZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chieti', '107', 'CH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Como', '107', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cosenza', '107', 'CS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cremona', '107', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Crotone', '107', 'KR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuneo', '107', 'CN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Emilia-Romagna', '107', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Enna', '107', 'EN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fermo', '107', 'FM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ferrara', '107', 'FE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Foggia', '107', 'FG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ForlÃ¬-Cesena', '107', 'FC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Friuliâ€“Venezia Giulia', '107', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Frosinone', '107', 'FR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gorizia', '107', 'GO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grosseto', '107', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Imperia', '107', 'IM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isernia', '107', 'IS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('L Aquila', '107', 'AQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Spezia', '107', 'SP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Latina', '107', 'LT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lazio', '107', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lecce', '107', 'LE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lecco', '107', 'LC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Liguria', '107', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Livorno', '107', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lodi', '107', 'LO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lombardy', '107', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lucca', '107', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Macerata', '107', 'MC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mantua', '107', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marche', '107', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Massa and Carrara', '107', 'MS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matera', '107', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Medio Campidano', '107', 'VS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Modena', '107', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Molise', '107', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monza and Brianza', '107', 'MB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Novara', '107', 'NO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nuoro', '107', 'NU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oristano', '107', 'OR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Padua', '107', 'PD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palermo', '107', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Parma', '107', 'PR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pavia', '107', 'PV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Perugia', '107', 'PG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pesaro and Urbino', '107', 'PU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pescara', '107', 'PE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Piacenza', '107', 'PC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Piedmont', '107', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pisa', '107', 'PI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pistoia', '107', 'PT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pordenone', '107', 'PN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Potenza', '107', 'PZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prato', '107', 'PO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ragusa', '107', 'RG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ravenna', '107', 'RA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Reggio Emilia', '107', 'RE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rieti', '107', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rimini', '107', 'RN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rovigo', '107', 'RO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salerno', '107', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sardinia', '107', '88', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sassari', '107', 'SS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Savona', '107', 'SV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sicily', '107', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siena', '107', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siracusa', '107', 'SR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sondrio', '107', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Sardinia', '107', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taranto', '107', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Teramo', '107', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Terni', '107', 'TR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trapani', '107', 'TP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trentino-South Tyrol', '107', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Treviso', '107', 'TV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trieste', '107', 'TS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tuscany', '107', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Udine', '107', 'UD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Umbria', '107', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Varese', '107', 'VA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Veneto', '107', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Verbano-Cusio-Ossola', '107', 'VB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vercelli', '107', 'VC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Verona', '107', 'VR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vibo Valentia', '107', 'VV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vicenza', '107', 'VI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Viterbo', '107', 'VT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Clarendon Parish', '108', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hanover Parish', '108', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kingston Parish', '108', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manchester Parish', '108', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Portland Parish', '108', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Andrew', '108', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Ann Parish', '108', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Catherine Parish', '108', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Elizabeth Parish', '108', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint James Parish', '108', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Mary Parish', '108', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Thomas Parish', '108', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trelawny Parish', '108', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Westmoreland Parish', '108', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aichi Prefecture', '109', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Akita Prefecture', '109', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aomori Prefecture', '109', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiba Prefecture', '109', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ehime Prefecture', '109', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fukui Prefecture', '109', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fukuoka Prefecture', '109', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fukushima Prefecture', '109', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gifu Prefecture', '109', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gunma Prefecture', '109', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hiroshima Prefecture', '109', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HokkaidÅ Prefecture', '109', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HyÅgo Prefecture', '109', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ibaraki Prefecture', '109', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ishikawa Prefecture', '109', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iwate Prefecture', '109', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kagawa Prefecture', '109', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kagoshima Prefecture', '109', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kanagawa Prefecture', '109', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÅchi Prefecture', '109', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kumamoto Prefecture', '109', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KyÅto Prefecture', '109', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mie Prefecture', '109', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Miyagi Prefecture', '109', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Miyazaki Prefecture', '109', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nagano Prefecture', '109', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nagasaki Prefecture', '109', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nara Prefecture', '109', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Niigata Prefecture', '109', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÅŒita Prefecture', '109', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Okayama Prefecture', '109', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Okinawa Prefecture', '109', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÅŒsaka Prefecture', '109', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saga Prefecture', '109', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saitama Prefecture', '109', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shiga Prefecture', '109', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shimane Prefecture', '109', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shizuoka Prefecture', '109', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tochigi Prefecture', '109', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tokushima Prefecture', '109', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tokyo', '109', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tottori Prefecture', '109', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toyama Prefecture', '109', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wakayama Prefecture', '109', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yamagata Prefecture', '109', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yamaguchi Prefecture', '109', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yamanashi Prefecture', '109', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ajloun', '111', 'AJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amman', '111', 'AM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aqaba', '111', 'AQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balqa', '111', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Irbid', '111', 'IR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jerash', '111', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karak', '111', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ma an', '111', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madaba', '111', 'MD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mafraq', '111', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tafilah', '111', 'AT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zarqa', '111', 'AZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Akmola Region', '112', 'AKM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aktobe Region', '112', 'AKT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Almaty', '112', 'ALA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Almaty Region', '112', 'ALM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Atyrau Region', '112', 'ATY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baikonur', '112', 'BAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Kazakhstan Region', '112', 'VOS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jambyl Region', '112', 'ZHA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karaganda Region', '112', 'KAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kostanay Region', '112', 'KUS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyzylorda Region', '112', 'KZY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mangystau Region', '112', 'MAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Kazakhstan Region', '112', 'SEV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nur-Sultan', '112', 'AST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pavlodar Region', '112', 'PAV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Turkestan Region', '112', 'YUZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Kazakhstan Province', '112', 'ZAP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baringo', '113', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bomet', '113', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bungoma', '113', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Busia', '113', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Elgeyo-Marakwet', '113', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Embu', '113', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Garissa', '113', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Homa Bay', '113', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isiolo', '113', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kajiado', '113', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kakamega', '113', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kericho', '113', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kiambu', '113', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kilifi', '113', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kirinyaga', '113', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kisii', '113', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kisumu', '113', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kitui', '113', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kwale', '113', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laikipia', '113', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lamu', '113', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Machakos', '113', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Makueni', '113', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mandera', '113', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marsabit', '113', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meru', '113', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Migori', '113', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mombasa', '113', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Murang a', '113', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nairobi City', '113', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakuru', '113', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nandi', '113', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Narok', '113', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nyamira', '113', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nyandarua', '113', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nyeri', '113', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samburu', '113', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siaya', '113', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taitaâ€“Taveta', '113', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tana River', '113', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tharaka-Nithi', '113', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trans Nzoia', '113', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Turkana', '113', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uasin Gishu', '113', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vihiga', '113', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wajir', '113', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Pokot', '113', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gilbert Islands', '114', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Line Islands', '114', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phoenix Islands', '114', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Äakovica District (Gjakove)', '248', 'XDG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gjilan District', '248', 'XGJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kosovska Mitrovica District', '248', 'XKM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PeÄ‡ District', '248', 'XPE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pristina (PriÅŸtine)', '248', 'XPI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prizren District', '248', 'XPR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('UroÅ¡evac District (Ferizaj)', '248', 'XUF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Ahmadi', '117', 'AH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Farwaniyah', '117', 'FA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Jahra', '117', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Capital', '117', 'KU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hawalli', '117', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mubarak Al-Kabeer', '117', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batken Region', '118', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bishkek', '118', 'GB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chuy Region', '118', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Issyk-Kul Region', '118', 'Y', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jalal-Abad Region', '118', 'J', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naryn Region', '118', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Osh', '118', 'GO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Osh Region', '118', 'O', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Talas Region', '118', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Attapeu Province', '119', 'AT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bokeo Province', '119', 'BK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bolikhamsai Province', '119', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Champasak Province', '119', 'CH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Houaphanh Province', '119', 'HO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khammouane Province', '119', 'KH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luang Namtha Province', '119', 'LM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luang Prabang Province', '119', 'LP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oudomxay Province', '119', 'OU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phongsaly Province', '119', 'PH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sainyabuli Province', '119', 'XA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salavan Province', '119', 'SL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Savannakhet Province', '119', 'SV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sekong Province', '119', 'XE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vientiane Prefecture', '119', 'VT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vientiane Province', '119', 'VI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Xaisomboun', '119', 'XN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Xaisomboun Province', '119', 'XS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Xiangkhouang Province', '119', 'XI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aglona Municipality', '120', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aizkraukle Municipality', '120', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aizpute Municipality', '120', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AknÄ«ste Municipality', '120', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aloja Municipality', '120', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alsunga Municipality', '120', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AlÅ«ksne Municipality', '120', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amata Municipality', '120', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ape Municipality', '120', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Auce Municipality', '120', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BabÄ«te Municipality', '120', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baldone Municipality', '120', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baltinava Municipality', '120', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balvi Municipality', '120', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bauska Municipality', '120', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BeverÄ«na Municipality', '120', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BrocÄ“ni Municipality', '120', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Burtnieki Municipality', '120', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carnikava Municipality', '120', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÄ“sis Municipality', '120', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cesvaine Municipality', '120', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cibla Municipality', '120', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dagda Municipality', '120', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Daugavpils', '120', 'DGV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Daugavpils Municipality', '120', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dobele Municipality', '120', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dundaga Municipality', '120', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Durbe Municipality', '120', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Engure Municipality', '120', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ä’rgÄ¼i Municipality', '120', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Garkalne Municipality', '120', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GrobiÅ†a Municipality', '120', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gulbene Municipality', '120', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iecava Municipality', '120', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IkÅ¡Ä·ile Municipality', '120', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IlÅ«kste Municipality', '120', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('InÄukalns Municipality', '120', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jaunjelgava Municipality', '120', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jaunpiebalga Municipality', '120', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jaunpils Municipality', '120', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÄ“kabpils', '120', 'JKB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÄ“kabpils Municipality', '120', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jelgava', '120', 'JEL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jelgava Municipality', '120', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÅ«rmala', '120', 'JUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kandava Municipality', '120', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÄrsava Municipality', '120', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ä¶egums Municipality', '120', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ä¶ekava Municipality', '120', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KocÄ“ni Municipality', '120', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koknese Municipality', '120', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KrÄslava Municipality', '120', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Krimulda Municipality', '120', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Krustpils Municipality', '120', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KuldÄ«ga Municipality', '120', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LielvÄrde Municipality', '120', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LiepÄja', '120', 'LPX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÄ«gatne Municipality', '120', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LimbaÅ¾i Municipality', '120', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÄ«vÄni Municipality', '120', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LubÄna Municipality', '120', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ludza Municipality', '120', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madona Municipality', '120', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÄlpils Municipality', '120', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÄrupe Municipality', '120', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mazsalaca Municipality', '120', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÄ“rsrags Municipality', '120', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NaukÅ¡Ä“ni Municipality', '120', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nereta Municipality', '120', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NÄ«ca Municipality', '120', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ogre Municipality', '120', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Olaine Municipality', '120', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ozolnieki Municipality', '120', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÄrgauja Municipality', '120', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÄvilosta Municipality', '120', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÄ¼aviÅ†as Municipality', '120', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PreiÄ¼i Municipality', '120', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Priekule Municipality', '120', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PriekuÄ¼i Municipality', '120', '75', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rauna Municipality', '120', '76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÄ“zekne', '120', 'REZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÄ“zekne Municipality', '120', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RiebiÅ†i Municipality', '120', '78', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Riga', '120', 'RIX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Roja Municipality', '120', '79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RopaÅ¾i Municipality', '120', '80', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rucava Municipality', '120', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RugÄji Municipality', '120', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÅ«jiena Municipality', '120', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RundÄle Municipality', '120', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sala Municipality', '120', '85', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SalacgrÄ«va Municipality', '120', '86', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salaspils Municipality', '120', '87', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saldus Municipality', '120', '88', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saulkrasti Municipality', '120', '89', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÄ“ja Municipality', '120', '90', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sigulda Municipality', '120', '91', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SkrÄ«veri Municipality', '120', '92', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Skrunda Municipality', '120', '93', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Smiltene Municipality', '120', '94', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('StopiÅ†i Municipality', '120', '95', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('StrenÄi Municipality', '120', '96', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Talsi Municipality', '120', '97', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÄ“rvete Municipality', '120', '98', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tukums Municipality', '120', '99', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VaiÅ†ode Municipality', '120', '100', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valka Municipality', '120', '101', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valmiera', '120', 'VMR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VarakÄ¼Äni Municipality', '120', '102', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÄrkava Municipality', '120', '103', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vecpiebalga Municipality', '120', '104', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vecumnieki Municipality', '120', '105', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ventspils', '120', 'VEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ventspils Municipality', '120', '106', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ViesÄ«te Municipality', '120', '107', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ViÄ¼aka Municipality', '120', '108', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ViÄ¼Äni Municipality', '120', '109', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zilupe Municipality', '120', '110', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Akkar', '121', 'AK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baalbek-Hermel', '121', 'BH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beirut', '121', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beqaa', '121', 'BI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mount Lebanon', '121', 'JL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nabatieh', '121', 'NA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North', '121', 'AS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South', '121', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berea District', '122', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Butha-Buthe District', '122', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leribe District', '122', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mafeteng District', '122', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maseru District', '122', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mohale s Hoek District', '122', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mokhotlong District', '122', 'J', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qacha s Nek District', '122', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quthing District', '122', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thaba-Tseka District', '122', 'K', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bomi County', '123', 'BM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bong County', '123', 'BG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gbarpolu County', '123', 'GP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Bassa County', '123', 'GB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Cape Mount County', '123', 'CM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Gedeh County', '123', 'GG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Kru County', '123', 'GK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lofa County', '123', 'LO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Margibi County', '123', 'MG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maryland County', '123', 'MY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Montserrado County', '123', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nimba', '123', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('River Cess County', '123', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('River Gee County', '123', 'RG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sinoe County', '123', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Wahat District', '124', 'WA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benghazi', '124', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Derna District', '124', 'DR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ghat District', '124', 'GT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jabal al Akhdar', '124', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jabal al Gharbi District', '124', 'JG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jafara', '124', 'JI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jufra', '124', 'JU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kufra District', '124', 'KF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marj District', '124', 'MJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Misrata District', '124', 'MI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Murqub', '124', 'MB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Murzuq District', '124', 'MQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nalut District', '124', 'NL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nuqat al Khams', '124', 'NQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sabha District', '124', 'SB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sirte District', '124', 'SR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tripoli District', '124', 'TB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wadi al Hayaa District', '124', 'WD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wadi al Shatii District', '124', 'WS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zawiya District', '124', 'ZA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balzers', '125', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eschen', '125', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gamprin', '125', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mauren', '125', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Planken', '125', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ruggell', '125', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Schaan', '125', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Schellenberg', '125', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Triesen', '125', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Triesenberg', '125', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vaduz', '125', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AkmenÄ— District Municipality', '126', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alytus City Municipality', '126', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alytus County', '126', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alytus District Municipality', '126', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BirÅ¡tonas Municipality', '126', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BirÅ¾ai District Municipality', '126', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Druskininkai municipality', '126', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ElektrÄ—nai municipality', '126', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ignalina District Municipality', '126', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jonava District Municipality', '126', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JoniÅ¡kis District Municipality', '126', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jurbarkas District Municipality', '126', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KaiÅ¡iadorys District Municipality', '126', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalvarija municipality', '126', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaunas City Municipality', '126', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaunas County', '126', 'KU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaunas District Municipality', '126', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KazlÅ³ RÅ«da municipality', '126', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÄ—dainiai District Municipality', '126', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KelmÄ— District Municipality', '126', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Klaipeda City Municipality', '126', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KlaipÄ—da County', '126', 'KL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KlaipÄ—da District Municipality', '126', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kretinga District Municipality', '126', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KupiÅ¡kis District Municipality', '126', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lazdijai District Municipality', '126', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MarijampolÄ— County', '126', 'MR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MarijampolÄ— Municipality', '126', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MaÅ¾eikiai District Municipality', '126', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MolÄ—tai District Municipality', '126', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Neringa Municipality', '126', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PagÄ—giai municipality', '126', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pakruojis District Municipality', '126', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palanga City Municipality', '126', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PanevÄ—Å¾ys City Municipality', '126', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PanevÄ—Å¾ys County', '126', 'PN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PanevÄ—Å¾ys District Municipality', '126', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pasvalys District Municipality', '126', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PlungÄ— District Municipality', '126', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prienai District Municipality', '126', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RadviliÅ¡kis District Municipality', '126', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Raseiniai District Municipality', '126', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rietavas municipality', '126', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RokiÅ¡kis District Municipality', '126', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å akiai District Municipality', '126', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å alÄininkai District Municipality', '126', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å iauliai City Municipality', '126', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å iauliai County', '126', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å iauliai District Municipality', '126', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å ilalÄ— District Municipality', '126', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å ilutÄ— District Municipality', '126', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å irvintos District Municipality', '126', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Skuodas District Municipality', '126', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å venÄionys District Municipality', '126', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TauragÄ— County', '126', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TauragÄ— District Municipality', '126', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TelÅ¡iai County', '126', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TelÅ¡iai District Municipality', '126', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trakai District Municipality', '126', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('UkmergÄ— District Municipality', '126', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Utena County', '126', 'UT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Utena District Municipality', '126', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VarÄ—na District Municipality', '126', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VilkaviÅ¡kis District Municipality', '126', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vilnius City Municipality', '126', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vilnius County', '126', 'VL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vilnius District Municipality', '126', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Visaginas Municipality', '126', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zarasai District Municipality', '126', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Capellen', '127', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Clervaux', '127', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Diekirch', '127', 'DI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Echternach', '127', 'EC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Esch-sur-Alzette', '127', 'ES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Grevenmacher', '127', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Luxembourg', '127', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Mersch', '127', 'ME', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Redange', '127', 'RD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Remich', '127', 'RM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Vianden', '127', 'VD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canton of Wiltz', '127', 'WI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Diekirch District', '127', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grevenmacher District', '127', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luxembourg District', '127', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antananarivo Province', '130', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antsiranana Province', '130', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fianarantsoa Province', '130', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mahajanga Province', '130', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toamasina Province', '130', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toliara Province', '130', 'U', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balaka District', '131', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blantyre District', '131', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Region', '131', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chikwawa District', '131', 'CK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiradzulu District', '131', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chitipa district', '131', 'CT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dedza District', '131', 'DE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dowa District', '131', 'DO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karonga District', '131', 'KR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kasungu District', '131', 'KS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Likoma District', '131', 'LK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lilongwe District', '131', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Machinga District', '131', 'MH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mangochi District', '131', 'MG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mchinji District', '131', 'MC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mulanje District', '131', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mwanza District', '131', 'MW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mzimba District', '131', 'MZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nkhata Bay District', '131', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nkhotakota District', '131', 'NK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Region', '131', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nsanje District', '131', 'NS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ntcheu District', '131', 'NU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ntchisi District', '131', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phalombe District', '131', 'PH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rumphi District', '131', 'RU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salima District', '131', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Region', '131', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thyolo District', '131', 'TH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zomba District', '131', 'ZO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Johor', '132', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kedah', '132', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kelantan', '132', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kuala Lumpur', '132', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Labuan', '132', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Malacca', '132', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Negeri Sembilan', '132', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pahang', '132', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Penang', '132', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Perak', '132', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Perlis', '132', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Putrajaya', '132', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sabah', '132', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sarawak', '132', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Selangor', '132', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Terengganu', '132', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Addu Atoll', '133', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alif Alif Atoll', '133', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alif Dhaal Atoll', '133', '0', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Province', '133', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dhaalu Atoll', '133', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Faafu Atoll', '133', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaafu Alif Atoll', '133', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaafu Dhaalu Atoll', '133', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gnaviyani Atoll', '133', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haa Alif Atoll', '133', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Haa Dhaalu Atoll', '133', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaafu Atoll', '133', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laamu Atoll', '133', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lhaviyani Atoll', '133', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MalÃ©', '133', 'MLE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meemu Atoll', '133', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Noonu Atoll', '133', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Central Province', '133', 'NC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Province', '133', 'NO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Raa Atoll', '133', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shaviyani Atoll', '133', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Central Province', '133', 'SC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Province', '133', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thaa Atoll', '133', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper South Province', '133', 'US', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vaavu Atoll', '133', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bamako', '134', 'BKO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gao Region', '134', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kayes Region', '134', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kidal Region', '134', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koulikoro Region', '134', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÃ©naka Region', '134', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mopti Region', '134', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ©gou Region', '134', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sikasso Region', '134', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TaoudÃ©nit Region', '134', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tombouctou Region', '134', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Attard', '135', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balzan', '135', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Birgu', '135', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Birkirkara', '135', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BirÅ¼ebbuÄ¡a', '135', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cospicua', '135', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dingli', '135', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fgura', '135', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Floriana', '135', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fontana', '135', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÄ§ajnsielem', '135', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÄ§arb', '135', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÄ§argÄ§ur', '135', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÄ§asri', '135', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÄ§axaq', '135', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gudja', '135', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÅ¼ira', '135', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ä¦amrun', '135', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iklin', '135', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalkara', '135', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KerÄ‹em', '135', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kirkop', '135', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lija', '135', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luqa', '135', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marsa', '135', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marsaskala', '135', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marsaxlokk', '135', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mdina', '135', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MellieÄ§a', '135', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÄ¡arr', '135', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mosta', '135', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mqabba', '135', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Msida', '135', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mtarfa', '135', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Munxar', '135', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nadur', '135', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naxxar', '135', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paola', '135', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pembroke', '135', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PietÃ ', '135', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qala', '135', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qormi', '135', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qrendi', '135', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rabat', '135', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Lawrence', '135', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Ä wann', '135', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sannat', '135', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa LuÄ‹ija', '135', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Venera', '135', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Senglea', '135', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SiÄ¡Ä¡iewi', '135', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sliema', '135', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('St. Julian s', '135', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('St. Paul s Bay', '135', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Swieqi', '135', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ta  Xbiex', '135', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarxien', '135', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valletta', '135', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Victoria', '135', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('XagÄ§ra', '135', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Xewkija', '135', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('XgÄ§ajra', '135', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å»abbar', '135', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å»ebbuÄ¡ Gozo', '135', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å»ebbuÄ¡ Malta', '135', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å»ejtun', '135', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å»urrieq', '135', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ralik Chain', '137', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ratak Chain', '137', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adrar', '139', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Assaba', '139', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brakna', '139', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dakhlet Nouadhibou', '139', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gorgol', '139', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guidimaka', '139', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hodh Ech Chargui', '139', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hodh El Gharbi', '139', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Inchiri', '139', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nouakchott-Nord', '139', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nouakchott-Ouest', '139', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nouakchott-Sud', '139', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tagant', '139', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tiris Zemmour', '139', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trarza', '139', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agalega Islands', '140', 'AG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Black River', '140', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Flacq', '140', 'FL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Port', '140', 'GP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moka', '140', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pamplemousses', '140', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plaines Wilhems', '140', 'PW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Port Louis', '140', 'PL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RiviÃ¨re du Rempart', '140', 'RR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rodrigues Island', '140', 'RO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Brandon Islands', '140', 'CC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Savanne', '140', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aguascalientes', '142', 'AGU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baja California', '142', 'BCN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baja California Sur', '142', 'BCS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Campeche', '142', 'CAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiapas', '142', 'CHP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chihuahua', '142', 'CHH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ciudad de MÃ©xico', '142', 'CDMX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coahuila de Zaragoza', '142', 'COA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Colima', '142', 'COL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Durango', '142', 'DUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Estado de MÃ©xico', '142', 'MEX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guanajuato', '142', 'GUA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guerrero', '142', 'GRO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hidalgo', '142', 'HID', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jalisco', '142', 'JAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MichoacÃ¡n de Ocampo', '142', 'MIC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Morelos', '142', 'MOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nayarit', '142', 'NAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nuevo LeÃ³n', '142', 'NLE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oaxaca', '142', 'OAX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puebla', '142', 'PUE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('QuerÃ©taro', '142', 'QUE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quintana Roo', '142', 'ROO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Luis PotosÃ­', '142', 'SLP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sinaloa', '142', 'SIN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sonora', '142', 'SON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tabasco', '142', 'TAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tamaulipas', '142', 'TAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tlaxcala', '142', 'TLA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Veracruz de Ignacio de la Llave', '142', 'VER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('YucatÃ¡n', '142', 'YUC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zacatecas', '142', 'ZAC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chuuk State', '143', 'TRK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kosrae State', '143', 'KSA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pohnpei State', '143', 'PNI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yap State', '143', 'YAP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anenii Noi District', '144', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÄƒlÈ›i Municipality', '144', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basarabeasca District', '144', 'BS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bender Municipality', '144', 'BD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Briceni District', '144', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cahul District', '144', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÄƒlÄƒraÈ™i District', '144', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cantemir District', '144', 'CT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÄƒuÈ™eni District', '144', 'CS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ChiÈ™inÄƒu Municipality', '144', 'CU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CimiÈ™lia District', '144', 'CM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Criuleni District', '144', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DonduÈ™eni District', '144', 'DO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Drochia District', '144', 'DR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DubÄƒsari District', '144', 'DU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('EdineÈ› District', '144', 'ED', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FÄƒleÈ™ti District', '144', 'FA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FloreÈ™ti District', '144', 'FL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gagauzia', '144', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Glodeni District', '144', 'GL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ®nceÈ™ti District', '144', 'HI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ialoveni District', '144', 'IA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nisporeni District', '144', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OcniÈ›a District', '144', 'OC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orhei District', '144', 'OR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rezina District', '144', 'RE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÃ®È™cani District', '144', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ®ngerei District', '144', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('È˜oldÄƒneÈ™ti District', '144', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Soroca District', '144', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('È˜tefan VodÄƒ District', '144', 'SV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('StrÄƒÈ™eni District', '144', 'ST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taraclia District', '144', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TeleneÈ™ti District', '144', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Transnistria autonomous territorial unit', '144', 'SN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ungheni District', '144', 'UN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Colle', '145', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Condamine', '145', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moneghetti', '145', 'MG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arkhangai Province', '146', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bayan-Ã–lgii Province', '146', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bayankhongor Province', '146', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bulgan Province', '146', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Darkhan-Uul Province', '146', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dornod Province', '146', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dornogovi Province', '146', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dundgovi Province', '146', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Govi-Altai Province', '146', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GovisÃ¼mber Province', '146', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khentii Province', '146', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khovd Province', '146', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KhÃ¶vsgÃ¶l Province', '146', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã–mnÃ¶govi Province', '146', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orkhon Province', '146', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã–vÃ¶rkhangai Province', '146', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Selenge Province', '146', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ¼khbaatar Province', '146', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÃ¶v Province', '146', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uvs Province', '146', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zavkhan Province', '146', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Andrijevica Municipality', '147', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bar Municipality', '147', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berane Municipality', '147', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bijelo Polje Municipality', '147', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Budva Municipality', '147', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Danilovgrad Municipality', '147', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gusinje Municipality', '147', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KolaÅ¡in Municipality', '147', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kotor Municipality', '147', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mojkovac Municipality', '147', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NikÅ¡iÄ‡ Municipality', '147', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Old Royal Capital Cetinje', '147', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Petnjica Municipality', '147', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plav Municipality', '147', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pljevlja Municipality', '147', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PluÅ¾ine Municipality', '147', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Podgorica Municipality', '147', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RoÅ¾aje Municipality', '147', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å avnik Municipality', '147', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tivat Municipality', '147', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ulcinj Municipality', '147', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½abljak Municipality', '147', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agadir-Ida-Ou-Tanane', '149', 'AGD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Haouz', '149', 'HAO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al HoceÃ¯ma', '149', 'HOC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aousserd (EH)', '149', 'AOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Assa-Zag (EH-partial)', '149', 'ASZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Azilal', '149', 'AZI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©ni Mellal', '149', 'BEM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©ni Mellal-KhÃ©nifra', '149', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benslimane', '149', 'BES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berkane', '149', 'BER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berrechid', '149', 'BRR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boujdour (EH)', '149', 'BOD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boulemane', '149', 'BOM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Casablanca', '149', 'CAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Casablanca-Settat', '149', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chefchaouen', '149', 'CHE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chichaoua', '149', 'CHI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chtouka-Ait Baha', '149', 'CHT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dakhla-Oued Ed-Dahab (EH)', '149', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DrÃ¢a-Tafilalet', '149', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Driouch', '149', 'DRI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Hajeb', '149', 'HAJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El Jadida', '149', 'JDI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('El KelÃ¢a des Sraghna', '149', 'KES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Errachidia', '149', 'ERR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Es-Semara (EH-partial)', '149', 'ESM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Essaouira', '149', 'ESI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fahs-Anjra', '149', 'FAH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FÃ¨s', '149', 'FES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FÃ¨s-MeknÃ¨s', '149', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Figuig', '149', 'FIG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fquih Ben Salah', '149', 'FQH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guelmim', '149', 'GUE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guelmim-Oued Noun (EH-partial)', '149', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guercif', '149', 'GUF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ifrane', '149', 'IFR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Inezgane-Ait Melloul', '149', 'INE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jerada', '149', 'JRA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÃ©nitra', '149', 'KEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KhÃ©misset', '149', 'KHE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KhÃ©nifra', '149', 'KHN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khouribga', '149', 'KHO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('L Oriental', '149', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LaÃ¢youne (EH)', '149', 'LAA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LaÃ¢youne-Sakia El Hamra (EH-partial)', '149', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Larache', '149', 'LAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mâ€™diq-Fnideq', '149', 'MDF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marrakech', '149', 'MAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marrakesh-Safi', '149', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÃ©diouna', '149', 'MED', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MeknÃ¨s', '149', 'MEK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Midelt', '149', 'MID', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mohammadia', '149', 'MOH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moulay Yacoub', '149', 'MOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nador', '149', 'NAD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nouaceur', '149', 'NOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouarzazate', '149', 'OUA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oued Ed-Dahab (EH)', '149', 'OUD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ouezzane', '149', 'OUZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oujda-Angad', '149', 'OUJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rabat', '149', 'RAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rabat-SalÃ©-KÃ©nitra', '149', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rehamna', '149', 'REH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Safi', '149', 'SAF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SalÃ©', '149', 'SAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sefrou', '149', 'SEF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Settat', '149', 'SET', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sidi Bennour', '149', 'SIB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sidi Ifni', '149', 'SIF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sidi Kacem', '149', 'SIK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sidi Slimane', '149', 'SIL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Skhirate-TÃ©mara', '149', 'SKH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Souss-Massa', '149', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tan-Tan (EH-partial)', '149', 'TNT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tanger-Assilah', '149', 'TNG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tanger-TÃ©touan-Al HoceÃ¯ma', '149', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taounate', '149', 'TAO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taourirt', '149', 'TAI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarfaya (EH-partial)', '149', 'TAF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taroudannt', '149', 'TAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tata', '149', 'TAT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taza', '149', 'TAZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÃ©touan', '149', 'TET', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tinghir', '149', 'TIN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tiznit', '149', 'TIZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Youssoufia', '149', 'YUS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zagora', '149', 'ZAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cabo Delgado Province', '150', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaza Province', '150', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Inhambane Province', '150', 'I', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manica Province', '150', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maputo', '150', 'MPM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maputo Province', '150', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nampula Province', '150', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Niassa Province', '150', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sofala Province', '150', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tete Province', '150', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zambezia Province', '150', 'Q', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ayeyarwady Region', '151', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bago', '151', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chin State', '151', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kachin State', '151', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kayah State', '151', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kayin State', '151', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Magway Region', '151', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mandalay Region', '151', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mon State', '151', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naypyidaw Union Territory', '151', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rakhine State', '151', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sagaing Region', '151', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shan State', '151', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tanintharyi Region', '151', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yangon Region', '151', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Erongo Region', '152', 'ER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hardap Region', '152', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karas Region', '152', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kavango East Region', '152', 'KE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kavango West Region', '152', 'KW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khomas Region', '152', 'KH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kunene Region', '152', 'KU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ohangwena Region', '152', 'OW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Omaheke Region', '152', 'OH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Omusati Region', '152', 'OS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oshana Region', '152', 'ON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oshikoto Region', '152', 'OT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Otjozondjupa Region', '152', 'OD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zambezi Region', '152', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aiwo District', '153', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anabar District', '153', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anetan District', '153', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anibare District', '153', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baiti District', '153', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boe District', '153', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buada District', '153', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Denigomodu District', '153', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ewa District', '153', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ijuw District', '153', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Meneng District', '153', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nibok District', '153', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uaboe District', '153', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yaren District', '153', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bagmati Zone', '154', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bheri Zone', '154', 'BH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Region', '154', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dhaulagiri Zone', '154', 'DH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Development Region', '154', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Far-Western Development Region', '154', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gandaki Zone', '154', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Janakpur Zone', '154', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karnali Zone', '154', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kosi Zone', '154', 'KO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lumbini Zone', '154', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mahakali Zone', '154', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mechi Zone', '154', 'ME', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mid-Western Region', '154', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Narayani Zone', '154', 'NA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rapti Zone', '154', 'RA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sagarmatha Zone', '154', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Seti Zone', '154', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Region', '154', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bonaire', '156', 'BQ1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Drenthe', '156', 'DR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Flevoland', '156', 'FL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Friesland', '156', 'FR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gelderland', '156', 'GE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Groningen', '156', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Limburg', '156', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Brabant', '156', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Holland', '156', 'NH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Overijssel', '156', 'OV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saba', '156', 'BQ2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sint Eustatius', '156', 'BQ3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Holland', '156', 'ZH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Utrecht', '156', 'UT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zeeland', '156', 'ZE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Auckland Region', '158', 'AUK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bay of Plenty Region', '158', 'BOP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canterbury Region', '158', 'CAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chatham Islands', '158', 'CIT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gisborne District', '158', 'GIS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hawke s Bay Region', '158', 'HKB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manawatu-Wanganui Region', '158', 'MWT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marlborough Region', '158', 'MBH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nelson Region', '158', 'NSN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northland Region', '158', 'NTL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Otago Region', '158', 'OTA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southland Region', '158', 'STL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taranaki Region', '158', 'TKI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tasman District', '158', 'TAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Waikato Region', '158', 'WKO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wellington Region', '158', 'WGN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Coast Region', '158', 'WTC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Boaco', '159', 'BO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carazo', '159', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chinandega', '159', 'CI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chontales', '159', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('EstelÃ­', '159', 'ES', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Granada', '159', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jinotega', '159', 'JI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LeÃ³n', '159', 'LE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madriz', '159', 'MD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Managua', '159', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masaya', '159', 'MS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matagalpa', '159', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Caribbean Coast', '159', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nueva Segovia', '159', 'NS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÃ­o San Juan', '159', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rivas', '159', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Caribbean Coast', '159', 'AS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agadez Region', '160', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Diffa Region', '160', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dosso Region', '160', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maradi Region', '160', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tahoua Region', '160', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TillabÃ©ri Region', '160', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zinder Region', '160', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abia', '161', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abuja Federal Capital Territory', '161', 'FC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adamawa', '161', 'AD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Akwa Ibom', '161', 'AK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anambra', '161', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bauchi', '161', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bayelsa', '161', 'BY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benue', '161', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Borno', '161', 'BO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cross River', '161', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Delta', '161', 'DE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ebonyi', '161', 'EB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Edo', '161', 'ED', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ekiti', '161', 'EK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Enugu', '161', 'EN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gombe', '161', 'GO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Imo', '161', 'IM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jigawa', '161', 'JI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaduna', '161', 'KD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kano', '161', 'KN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Katsina', '161', 'KT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kebbi', '161', 'KE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kogi', '161', 'KO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kwara', '161', 'KW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lagos', '161', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nasarawa', '161', 'NA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Niger', '161', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ogun', '161', 'OG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ondo', '161', 'ON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Osun', '161', 'OS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oyo', '161', 'OY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plateau', '161', 'PL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rivers', '161', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sokoto', '161', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taraba', '161', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yobe', '161', 'YO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamfara', '161', 'ZA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chagang Province', '115', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kangwon Province', '115', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Hamgyong Province', '115', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Hwanghae Province', '115', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Pyongan Province', '115', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pyongyang', '115', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rason', '115', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ryanggang Province', '115', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Hamgyong Province', '115', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Hwanghae Province', '115', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Pyongan Province', '115', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aerodrom Municipality', '129', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AraÄinovo Municipality', '129', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berovo Municipality', '129', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bitola Municipality', '129', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bogdanci Municipality', '129', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bogovinje Municipality', '129', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bosilovo Municipality', '129', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brvenica Municipality', '129', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Butel Municipality', '129', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒair Municipality', '129', '79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒaÅ¡ka Municipality', '129', '80', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centar Municipality', '129', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centar Å½upa Municipality', '129', '78', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒeÅ¡inovo-ObleÅ¡evo Municipality', '129', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒuÄer-Sandevo Municipality', '129', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Debarca Municipality', '129', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DelÄevo Municipality', '129', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Demir Hisar Municipality', '129', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Demir Kapija Municipality', '129', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dojran Municipality', '129', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dolneni Municipality', '129', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Drugovo Municipality', '129', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gazi Baba Municipality', '129', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gevgelija Municipality', '129', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GjorÄe Petrov Municipality', '129', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gostivar Municipality', '129', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gradsko Municipality', '129', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Greater Skopje', '129', '85', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilinden Municipality', '129', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jegunovce Municipality', '129', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karbinci', '129', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KarpoÅ¡ Municipality', '129', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kavadarci Municipality', '129', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KiÄevo Municipality', '129', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kisela Voda Municipality', '129', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KoÄani Municipality', '129', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KonÄe Municipality', '129', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kratovo Municipality', '129', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kriva Palanka Municipality', '129', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KrivogaÅ¡tani Municipality', '129', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KruÅ¡evo Municipality', '129', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kumanovo Municipality', '129', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lipkovo Municipality', '129', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lozovo Municipality', '129', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Makedonska Kamenica Municipality', '129', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Makedonski Brod Municipality', '129', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mavrovo and RostuÅ¡a Municipality', '129', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mogila Municipality', '129', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Negotino Municipality', '129', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Novaci Municipality', '129', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Novo Selo Municipality', '129', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ohrid Municipality', '129', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oslomej Municipality', '129', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PehÄevo Municipality', '129', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Petrovec Municipality', '129', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plasnica Municipality', '129', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prilep Municipality', '129', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ProbiÅ¡tip Municipality', '129', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RadoviÅ¡ Municipality', '129', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rankovce Municipality', '129', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Resen Municipality', '129', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rosoman Municipality', '129', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saraj Municipality', '129', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SopiÅ¡te Municipality', '129', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Staro NagoriÄane Municipality', '129', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å tip Municipality', '129', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Struga Municipality', '129', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Strumica Municipality', '129', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('StudeniÄani Municipality', '129', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å uto Orizari Municipality', '129', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sveti Nikole Municipality', '129', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tearce Municipality', '129', '75', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tetovo Municipality', '129', '76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valandovo Municipality', '129', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vasilevo Municipality', '129', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Veles Municipality', '129', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VevÄani Municipality', '129', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vinica Municipality', '129', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VraneÅ¡tica Municipality', '129', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VrapÄiÅ¡te Municipality', '129', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zajas Municipality', '129', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zelenikovo Municipality', '129', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½elino Municipality', '129', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zrnovci Municipality', '129', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agder', '165', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Innlandet', '165', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jan Mayen', '165', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÃ¸re og Romsdal', '165', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nordland', '165', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oslo', '165', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rogaland', '165', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Svalbard', '165', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Troms og Finnmark', '165', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TrÃ¸ndelag', '165', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vestfold og Telemark', '165', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vestland', '165', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Viken', '165', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ad Dakhiliyah', '166', 'DA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ad Dhahirah', '166', 'ZA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Batinah North', '166', 'BS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Batinah Region', '166', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Batinah South', '166', 'BJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Buraimi', '166', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Wusta', '166', 'WU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ash Sharqiyah North', '166', 'SS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ash Sharqiyah Region', '166', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ash Sharqiyah South', '166', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dhofar', '166', 'ZU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Musandam', '166', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Muscat', '166', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Azad Kashmir', '167', 'JK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balochistan', '167', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Federally Administered Tribal Areas', '167', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gilgit-Baltistan', '167', 'GB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Islamabad Capital Territory', '167', 'IS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khyber Pakhtunkhwa', '167', 'KP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Punjab', '167', 'PB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sindh', '167', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aimeliik', '168', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Airai', '168', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Angaur', '168', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hatohobei', '168', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kayangel', '168', '100', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koror', '168', '150', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Melekeok', '168', '212', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngaraard', '168', '214', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngarchelong', '168', '218', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngardmau', '168', '222', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngatpang', '168', '224', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngchesar', '168', '226', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngeremlengui', '168', '227', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngiwal', '168', '228', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Peleliu', '168', '350', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sonsorol', '168', '370', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bethlehem', '169', 'BTH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Deir El Balah', '169', 'DEB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaza', '169', 'GZA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hebron', '169', 'HBN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jenin', '169', 'JEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jericho and Al Aghwar', '169', 'JRH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jerusalem', '169', 'JEM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khan Yunis', '169', 'KYS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nablus', '169', 'NBS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Gaza', '169', 'NGZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qalqilya', '169', 'QQA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rafah', '169', 'RFH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ramallah', '169', 'RBH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salfit', '169', 'SLT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tubas', '169', 'TBS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tulkarm', '169', 'TKM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bocas del Toro Province', '170', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ChiriquÃ­ Province', '170', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CoclÃ© Province', '170', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ColÃ³n Province', '170', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DariÃ©n Province', '170', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('EmberÃ¡-Wounaan Comarca', '170', 'EM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guna Yala', '170', 'KY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Herrera Province', '170', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Los Santos Province', '170', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NgÃ¶be-BuglÃ© Comarca', '170', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PanamÃ¡ Oeste Province', '170', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PanamÃ¡ Province', '170', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Veraguas Province', '170', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bougainville', '171', 'NSB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Province', '171', 'CPM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chimbu Province', '171', 'CPK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East New Britain', '171', 'EBR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Highlands Province', '171', 'EHG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Enga Province', '171', 'EPW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gulf', '171', 'GPK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hela', '171', 'HLA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jiwaka Province', '171', 'JWK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madang Province', '171', 'MPM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manus Province', '171', 'MRL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Milne Bay Province', '171', 'MBA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Morobe Province', '171', 'MPL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Ireland Province', '171', 'NIK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oro Province', '171', 'NPP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Port Moresby', '171', 'NCD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sandaun Province', '171', 'SAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Highlands Province', '171', 'SHM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West New Britain Province', '171', 'WBK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Highlands Province', '171', 'WHM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Province', '171', 'WPD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alto Paraguay Department', '172', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alto ParanÃ¡ Department', '172', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amambay Department', '172', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Asuncion', '172', 'ASU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BoquerÃ³n Department', '172', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CaaguazÃº', '172', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CaazapÃ¡', '172', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CanindeyÃº', '172', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Department', '172', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ConcepciÃ³n Department', '172', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cordillera Department', '172', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GuairÃ¡ Department', '172', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ItapÃºa', '172', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Misiones Department', '172', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‘eembucÃº Department', '172', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ParaguarÃ­ Department', '172', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Presidente Hayes Department', '172', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Pedro Department', '172', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amazonas', '173', 'AMA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ãncash', '173', 'ANC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ApurÃ­mac', '173', 'APU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arequipa', '173', 'ARE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ayacucho', '173', 'AYA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cajamarca', '173', 'CAJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Callao', '173', 'CAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cusco', '173', 'CUS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Huancavelica', '173', 'HUV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Huanuco', '173', 'HUC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ica', '173', 'ICA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JunÃ­n', '173', 'JUN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Libertad', '173', 'LAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lambayeque', '173', 'LAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lima', '173', 'LIM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loreto', '173', 'LOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madre de Dios', '173', 'MDD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moquegua', '173', 'MOQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pasco', '173', 'PAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Piura', '173', 'PIU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puno', '173', 'PUN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San MartÃ­n', '173', 'SAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tacna', '173', 'TAC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tumbes', '173', 'TUM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ucayali', '173', 'UCA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abra', '174', 'ABR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agusan del Norte', '174', 'AGN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agusan del Sur', '174', 'AGS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aklan', '174', 'AKL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Albay', '174', 'ALB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antique', '174', 'ANT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Apayao', '174', 'APA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aurora', '174', 'AUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Autonomous Region in Muslim Mindanao', '174', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basilan', '174', 'BAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bataan', '174', 'BAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batanes', '174', 'BTN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batangas', '174', 'BTG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benguet', '174', 'BEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bicol', '174', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Biliran', '174', 'BIL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bohol', '174', 'BOH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bukidnon', '174', 'BUK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bulacan', '174', 'BUL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cagayan', '174', 'CAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cagayan Valley', '174', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Calabarzon', '174', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Camarines Norte', '174', 'CAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Camarines Sur', '174', 'CAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Camiguin', '174', 'CAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Capiz', '174', 'CAP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caraga', '174', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Catanduanes', '174', 'CAT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cavite', '174', 'CAV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cebu', '174', 'CEB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Luzon', '174', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Visayas', '174', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Compostela Valley', '174', 'COM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cordillera Administrative', '174', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cotabato', '174', 'NCO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao', '174', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao del Norte', '174', 'DAV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao del Sur', '174', 'DAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao Occidental', '174', 'DVO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao Oriental', '174', 'DAO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dinagat Islands', '174', 'DIN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Samar', '174', 'EAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Visayas', '174', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guimaras', '174', 'GUI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ifugao', '174', 'IFU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilocos', '174', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilocos Norte', '174', 'ILN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilocos Sur', '174', 'ILS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iloilo', '174', 'ILI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isabela', '174', 'ISA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalinga', '174', 'KAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Union', '174', 'LUN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laguna', '174', 'LAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lanao del Norte', '174', 'LAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lanao del Sur', '174', 'LAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leyte', '174', 'LEY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maguindanao', '174', 'MAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marinduque', '174', 'MAD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masbate', '174', 'MAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Metro Manila', '174', 'NCR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mimaropa', '174', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Misamis Occidental', '174', 'MSC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Misamis Oriental', '174', 'MSR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mountain Province', '174', 'MOU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Negros Occidental', '174', 'NEC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Negros Oriental', '174', 'NER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Mindanao', '174', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Samar', '174', 'NSA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nueva Ecija', '174', 'NUE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nueva Vizcaya', '174', 'NUV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Occidental Mindoro', '174', 'MDC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oriental Mindoro', '174', 'MDR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palawan', '174', 'PLW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pampanga', '174', 'PAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pangasinan', '174', 'PAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quezon', '174', 'QUE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quirino', '174', 'QUI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rizal', '174', 'RIZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Romblon', '174', 'ROM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sarangani', '174', 'SAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siquijor', '174', 'SIG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Soccsksargen', '174', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sorsogon', '174', 'SOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Cotabato', '174', 'SCO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Leyte', '174', 'SLE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sultan Kudarat', '174', 'SUK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulu', '174', 'SLU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surigao del Norte', '174', 'SUN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surigao del Sur', '174', 'SUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarlac', '174', 'TAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tawi-Tawi', '174', 'TAW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Samar', '174', 'WSA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Visayas', '174', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zambales', '174', 'ZMB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamboanga del Norte', '174', 'ZAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamboanga del Sur', '174', 'ZAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamboanga Peninsula', '174', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamboanga Sibugay', '174', 'ZSI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Greater Poland Voivodeship', '176', 'WP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kuyavian-Pomeranian Voivodeship', '176', 'KP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lesser Poland Voivodeship', '176', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lower Silesian Voivodeship', '176', 'DS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lublin Voivodeship', '176', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lubusz Voivodeship', '176', 'LB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÅÃ³dÅº Voivodeship', '176', 'LD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masovian Voivodeship', '176', 'MZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Opole Voivodeship', '176', 'OP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Podkarpackie Voivodeship', '176', 'PK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Podlaskie Voivodeship', '176', 'PD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pomeranian Voivodeship', '176', 'PM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Silesian Voivodeship', '176', 'SL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÅšwiÄ™tokrzyskie Voivodeship', '176', 'SK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Warmian-Masurian Voivodeship', '176', 'WN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Pomeranian Voivodeship', '176', 'ZP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AÃ§ores', '177', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aveiro', '177', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beja', '177', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Braga', '177', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BraganÃ§a', '177', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Castelo Branco', '177', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coimbra', '177', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‰vora', '177', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Faro', '177', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guarda', '177', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leiria', '177', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lisbon', '177', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madeira', '177', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Portalegre', '177', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Porto', '177', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SantarÃ©m', '177', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SetÃºbal', '177', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Viana do Castelo', '177', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vila Real', '177', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Viseu', '177', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adjuntas', '178', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aguada', '178', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aguadilla', '178', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aguas Buenas', '178', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aibonito', '178', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AÃ±asco', '178', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arecibo', '178', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arecibo', '178', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arroyo', '178', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barceloneta', '178', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barranquitas', '178', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bayamon', '178', 'BY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BayamÃ³n', '178', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cabo Rojo', '178', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caguas', '178', 'CG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caguas', '178', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Camuy', '178', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CanÃ³vanas', '178', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carolina', '178', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carolina', '178', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CataÃ±o', '178', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cayey', '178', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ceiba', '178', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ciales', '178', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cidra', '178', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coamo', '178', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ComerÃ­o', '178', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Corozal', '178', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Culebra', '178', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dorado', '178', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fajardo', '178', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Florida', '178', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GuÃ¡nica', '178', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guayama', '178', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guayanilla', '178', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guaynabo', '178', 'GN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guaynabo', '178', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gurabo', '178', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hatillo', '178', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hormigueros', '178', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Humacao', '178', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isabela', '178', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jayuya', '178', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Juana DÃ­az', '178', '75', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Juncos', '178', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lajas', '178', '79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lares', '178', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Las MarÃ­as', '178', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Las Piedras', '178', '85', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LoÃ­za', '178', '87', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luquillo', '178', '89', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ManatÃ­', '178', '91', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maricao', '178', '93', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maunabo', '178', '95', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MayagÃ¼ez', '178', 'MG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MayagÃ¼ez', '178', '97', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moca', '178', '99', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Morovis', '178', '101', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naguabo', '178', '103', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naranjito', '178', '105', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orocovis', '178', '107', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Patillas', '178', '109', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PeÃ±uelas', '178', '111', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ponce', '178', 'PO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ponce', '178', '113', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quebradillas', '178', '115', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RincÃ³n', '178', '117', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÃ­o Grande', '178', '119', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sabana Grande', '178', '121', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salinas', '178', '123', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San GermÃ¡n', '178', '125', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Juan', '178', '127', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Juan', '178', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Lorenzo', '178', '129', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San SebastiÃ¡n', '178', '131', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Isabel', '178', '133', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toa Alta', '178', '135', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toa Baja', '178', 'TB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toa Baja', '178', '137', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trujillo Alto', '178', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trujillo Alto', '178', '139', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Utuado', '178', '141', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vega Alta', '178', '143', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vega Baja', '178', '145', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vieques', '178', '147', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Villalba', '178', '149', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yabucoa', '178', '151', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yauco', '178', '153', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Daayen', '179', 'ZA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Khor', '179', 'KH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Rayyan Municipality', '179', 'RA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Wakrah', '179', 'WA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al-Shahaniya', '179', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Doha', '179', 'DA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madinat ash Shamal', '179', 'MS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Umm Salal Municipality', '179', 'US', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alba', '181', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arad County', '181', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arges', '181', 'AG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BacÄƒu County', '181', 'BC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bihor County', '181', 'BH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BistriÈ›a-NÄƒsÄƒud County', '181', 'BN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BotoÈ™ani County', '181', 'BT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Braila', '181', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BraÈ™ov County', '181', 'BV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bucharest', '181', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BuzÄƒu County', '181', 'BZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÄƒlÄƒraÈ™i County', '181', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CaraÈ™-Severin County', '181', 'CS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cluj County', '181', 'CJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ConstanÈ›a County', '181', 'CT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Covasna County', '181', 'CV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DÃ¢mboviÈ›a County', '181', 'DB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dolj County', '181', 'DJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GalaÈ›i County', '181', 'GL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Giurgiu County', '181', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gorj County', '181', 'GJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Harghita County', '181', 'HR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hunedoara County', '181', 'HD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IalomiÈ›a County', '181', 'IL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IaÈ™i County', '181', 'IS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilfov County', '181', 'IF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MaramureÈ™ County', '181', 'MM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MehedinÈ›i County', '181', 'MH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MureÈ™ County', '181', 'MS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NeamÈ› County', '181', 'NT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Olt County', '181', 'OT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prahova County', '181', 'PH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÄƒlaj County', '181', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Satu Mare County', '181', 'SM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sibiu County', '181', 'SB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Suceava County', '181', 'SV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Teleorman County', '181', 'TR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TimiÈ™ County', '181', 'TM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tulcea County', '181', 'TL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÃ¢lcea County', '181', 'VL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vaslui County', '181', 'VS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vrancea County', '181', 'VN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Altai Krai', '182', 'ALT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Altai Republic', '182', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amur Oblast', '182', 'AMU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arkhangelsk', '182', 'ARK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Astrakhan Oblast', '182', 'AST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Belgorod Oblast', '182', 'BEL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bryansk Oblast', '182', 'BRY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chechen Republic', '182', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chelyabinsk Oblast', '182', 'CHE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chukotka Autonomous Okrug', '182', 'CHU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chuvash Republic', '182', 'CU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Irkutsk', '182', 'IRK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ivanovo Oblast', '182', 'IVA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jewish Autonomous Oblast', '182', 'YEV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kabardino-Balkar Republic', '182', 'KB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaliningrad', '182', 'KGD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaluga Oblast', '182', 'KLU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kamchatka Krai', '182', 'KAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karachay-Cherkess Republic', '182', 'KC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kemerovo Oblast', '182', 'KEM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khabarovsk Krai', '182', 'KHA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khanty-Mansi Autonomous Okrug', '182', 'KHM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kirov Oblast', '182', 'KIR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Komi Republic', '182', 'KO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kostroma Oblast', '182', 'KOS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Krasnodar Krai', '182', 'KDA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Krasnoyarsk Krai', '182', 'KYA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kurgan Oblast', '182', 'KGN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kursk Oblast', '182', 'KRS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leningrad Oblast', '182', 'LEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lipetsk Oblast', '182', 'LIP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Magadan Oblast', '182', 'MAG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mari El Republic', '182', 'ME', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moscow', '182', 'MOW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moscow Oblast', '182', 'MOS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Murmansk Oblast', '182', 'MUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nenets Autonomous Okrug', '182', 'NEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nizhny Novgorod Oblast', '182', 'NIZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Novgorod Oblast', '182', 'NGR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Novosibirsk', '182', 'NVS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Omsk Oblast', '182', 'OMS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orenburg Oblast', '182', 'ORE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oryol Oblast', '182', 'ORL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Penza Oblast', '182', 'PNZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Perm Krai', '182', 'PER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Primorsky Krai', '182', 'PRI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pskov Oblast', '182', 'PSK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Adygea', '182', 'AD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Bashkortostan', '182', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Buryatia', '182', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Dagestan', '182', 'DA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Ingushetia', '182', 'IN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Kalmykia', '182', 'KL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Karelia', '182', 'KR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Khakassia', '182', 'KK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Mordovia', '182', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of North Ossetia-Alania', '182', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Republic of Tatarstan', '182', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rostov Oblast', '182', 'ROS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ryazan Oblast', '182', 'RYA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Petersburg', '182', 'SPE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sakha Republic', '182', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sakhalin', '182', 'SAK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samara Oblast', '182', 'SAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saratov Oblast', '182', 'SAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Smolensk Oblast', '182', 'SMO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stavropol Krai', '182', 'STA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sverdlovsk', '182', 'SVE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tambov Oblast', '182', 'TAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tomsk Oblast', '182', 'TOM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tula Oblast', '182', 'TUL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tuva Republic', '182', 'TY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tver Oblast', '182', 'TVE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tyumen Oblast', '182', 'TYU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Udmurt Republic', '182', 'UD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ulyanovsk Oblast', '182', 'ULY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vladimir Oblast', '182', 'VLA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Volgograd Oblast', '182', 'VGG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vologda Oblast', '182', 'VLG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Voronezh Oblast', '182', 'VOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yamalo-Nenets Autonomous Okrug', '182', 'YAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yaroslavl Oblast', '182', 'YAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zabaykalsky Krai', '182', 'ZAB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Province', '183', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kigali district', '183', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Province', '183', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Province', '183', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Province', '183', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Christ Church Nichola Town Parish', '185', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nevis', '185', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Anne Sandy Point Parish', '185', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint George Gingerland Parish', '185', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint James Windward Parish', '185', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint John Capisterre Parish', '185', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint John Figtree Parish', '185', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Kitts', '185', 'K', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Mary Cayon Parish', '185', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Paul Capisterre Parish', '185', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Paul Charlestown Parish', '185', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Peter Basseterre Parish', '185', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Thomas Lowland Parish', '185', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Thomas Middle Island Parish', '185', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trinity Palmetto Point Parish', '185', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anse la Raye Quarter', '186', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canaries', '186', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Castries Quarter', '186', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Choiseul Quarter', '186', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dauphin Quarter', '186', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dennery Quarter', '186', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gros Islet Quarter', '186', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laborie Quarter', '186', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Micoud Quarter', '186', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Praslin Quarter', '186', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SoufriÃ¨re Quarter', '186', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vieux Fort Quarter', '186', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Charlotte Parish', '188', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grenadines Parish', '188', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Andrew Parish', '188', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint David Parish', '188', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint George Parish', '188', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Patrick Parish', '188', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('A ana', '191', 'AA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aiga-i-le-Tai', '191', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Atua', '191', 'AT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fa asaleleaga', '191', 'FA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaga emauga', '191', 'GE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaga ifomauga', '191', 'GI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palauli', '191', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Satupa itea', '191', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tuamasaga', '191', 'TU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Va a-o-Fonoti', '191', 'VF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vaisigano', '191', 'VS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Acquaviva', '192', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Borgo Maggiore', '192', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiesanuova', '192', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Domagnano', '192', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Faetano', '192', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fiorentino', '192', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Montegiardino', '192', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Marino', '192', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Serravalle', '192', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PrÃ­ncipe Province', '193', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ£o TomÃ© Province', '193', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES (' Asir', '194', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Bahah', '194', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Jawf', '194', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Madinah', '194', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al-Qassim', '194', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Province', '194', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ha il', '194', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jizan', '194', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Makkah', '194', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Najran', '194', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Borders', '194', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Riyadh', '194', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tabuk', '194', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dakar', '195', 'DK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Diourbel Region', '195', 'DB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fatick', '195', 'FK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaffrine', '195', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaolack', '195', 'KL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÃ©dougou', '195', 'KE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kolda', '195', 'KD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Louga', '195', 'LG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matam', '195', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint-Louis', '195', 'SL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ©dhiou', '195', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tambacounda Region', '195', 'TC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ThiÃ¨s Region', '195', 'TH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ziguinchor', '195', 'ZG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Belgrade', '196', '0', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bor District', '196', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BraniÄevo District', '196', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Banat District', '196', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jablanica District', '196', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kolubara District', '196', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MaÄva District', '196', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moravica District', '196', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NiÅ¡ava District', '196', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North BaÄka District', '196', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Banat District', '196', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PÄinja District', '196', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pirot District', '196', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Podunavlje District', '196', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pomoravlje District', '196', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rasina District', '196', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RaÅ¡ka District', '196', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South BaÄka District', '196', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Banat District', '196', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Srem District', '196', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å umadija District', '196', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toplica District', '196', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vojvodina', '196', 'VO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West BaÄka District', '196', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ZajeÄar District', '196', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zlatibor District', '196', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anse Boileau', '197', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anse Royale', '197', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anse-aux-Pins', '197', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Au Cap', '197', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baie Lazare', '197', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baie Sainte Anne', '197', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beau Vallon', '197', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bel Air', '197', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bel Ombre', '197', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cascade', '197', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Glacis', '197', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Anse MahÃ©', '197', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Anse Praslin', '197', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Digue', '197', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La RiviÃ¨re Anglaise', '197', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Les Mamelles', '197', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mont Buxton', '197', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mont Fleuri', '197', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plaisance', '197', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pointe La Rue', '197', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Port Glaud', '197', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Roche Caiman', '197', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Louis', '197', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Takamaka', '197', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Province', '198', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Province', '198', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Province', '198', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Area', '198', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Singapore', '199', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North East', '199', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North West', '199', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South East', '199', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South West', '199', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BanskÃ¡ Bystrica Region', '200', 'BC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bratislava Region', '200', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KoÅ¡ice Region', '200', 'KI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nitra Region', '200', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PreÅ¡ov Region', '200', 'PV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TrenÄÃ­n Region', '200', 'TC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trnava Region', '200', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½ilina Region', '200', 'ZI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AjdovÅ¡Äina Municipality', '201', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ankaran Municipality', '201', '213', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Beltinci Municipality', '201', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benedikt Municipality', '201', '148', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bistrica ob Sotli Municipality', '201', '149', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bled Municipality', '201', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bloke Municipality', '201', '150', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bohinj Municipality', '201', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Borovnica Municipality', '201', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bovec Municipality', '201', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BraslovÄe Municipality', '201', '151', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brda Municipality', '201', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BreÅ¾ice Municipality', '201', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brezovica Municipality', '201', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cankova Municipality', '201', '152', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cerklje na Gorenjskem Municipality', '201', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cerknica Municipality', '201', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cerkno Municipality', '201', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cerkvenjak Municipality', '201', '153', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City Municipality of Celje', '201', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City Municipality of Novo Mesto', '201', '85', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒrenÅ¡ovci Municipality', '201', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒrna na KoroÅ¡kem Municipality', '201', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄŒrnomelj Municipality', '201', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Destrnik Municipality', '201', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DivaÄa Municipality', '201', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dobje Municipality', '201', '154', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dobrepolje Municipality', '201', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dobrna Municipality', '201', '155', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dobrovaâ€“Polhov Gradec Municipality', '201', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dobrovnik Municipality', '201', '156', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dol pri Ljubljani Municipality', '201', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dolenjske Toplice Municipality', '201', '157', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DomÅ¾ale Municipality', '201', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dornava Municipality', '201', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dravograd Municipality', '201', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Duplek Municipality', '201', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gorenja Vasâ€“Poljane Municipality', '201', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GoriÅ¡nica Municipality', '201', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gorje Municipality', '201', '207', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gornja Radgona Municipality', '201', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gornji Grad Municipality', '201', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gornji Petrovci Municipality', '201', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grad Municipality', '201', '158', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grosuplje Municipality', '201', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hajdina Municipality', '201', '159', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HoÄeâ€“Slivnica Municipality', '201', '160', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HodoÅ¡ Municipality', '201', '161', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Horjul Municipality', '201', '162', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hrastnik Municipality', '201', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hrpeljeâ€“Kozina Municipality', '201', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Idrija Municipality', '201', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ig Municipality', '201', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IvanÄna Gorica Municipality', '201', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Izola Municipality', '201', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jesenice Municipality', '201', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jezersko Municipality', '201', '163', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JurÅ¡inci Municipality', '201', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kamnik Municipality', '201', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kanal ob SoÄi Municipality', '201', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KidriÄevo Municipality', '201', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kobarid Municipality', '201', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kobilje Municipality', '201', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KoÄevje Municipality', '201', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Komen Municipality', '201', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Komenda Municipality', '201', '164', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koper City Municipality', '201', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kostanjevica na Krki Municipality', '201', '197', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kostel Municipality', '201', '165', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kozje Municipality', '201', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kranj City Municipality', '201', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kranjska Gora Municipality', '201', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KriÅ¾evci Municipality', '201', '166', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kungota', '201', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kuzma Municipality', '201', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LaÅ¡ko Municipality', '201', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lenart Municipality', '201', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lendava Municipality', '201', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Litija Municipality', '201', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ljubljana City Municipality', '201', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ljubno Municipality', '201', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ljutomer Municipality', '201', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Logâ€“Dragomer Municipality', '201', '208', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Logatec Municipality', '201', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LoÅ¡ka Dolina Municipality', '201', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LoÅ¡ki Potok Municipality', '201', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lovrenc na Pohorju Municipality', '201', '167', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LuÄe Municipality', '201', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lukovica Municipality', '201', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MajÅ¡perk Municipality', '201', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Makole Municipality', '201', '198', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maribor City Municipality', '201', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Markovci Municipality', '201', '168', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Medvode Municipality', '201', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MengeÅ¡ Municipality', '201', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Metlika Municipality', '201', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MeÅ¾ica Municipality', '201', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MiklavÅ¾ na Dravskem Polju Municipality', '201', '169', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mirenâ€“Kostanjevica Municipality', '201', '75', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mirna Municipality', '201', '212', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mirna PeÄ Municipality', '201', '170', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mislinja Municipality', '201', '76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mokronogâ€“Trebelno Municipality', '201', '199', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MoravÄe Municipality', '201', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moravske Toplice Municipality', '201', '78', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mozirje Municipality', '201', '79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Municipality of ApaÄe', '201', '195', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Municipality of Cirkulane', '201', '196', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Municipality of Ilirska Bistrica', '201', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Municipality of KrÅ¡ko', '201', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Municipality of Å kofljica', '201', '123', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Murska Sobota City Municipality', '201', '80', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Muta Municipality', '201', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Naklo Municipality', '201', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nazarje Municipality', '201', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nova Gorica City Municipality', '201', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Odranci Municipality', '201', '86', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oplotnica', '201', '171', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('OrmoÅ¾ Municipality', '201', '87', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Osilnica Municipality', '201', '88', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pesnica Municipality', '201', '89', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Piran Municipality', '201', '90', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pivka Municipality', '201', '91', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PodÄetrtek Municipality', '201', '92', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Podlehnik Municipality', '201', '172', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Podvelka Municipality', '201', '93', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PoljÄane Municipality', '201', '200', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Polzela Municipality', '201', '173', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Postojna Municipality', '201', '94', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prebold Municipality', '201', '174', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Preddvor Municipality', '201', '95', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prevalje Municipality', '201', '175', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ptuj City Municipality', '201', '96', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puconci Municipality', '201', '97', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RaÄeâ€“Fram Municipality', '201', '98', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RadeÄe Municipality', '201', '99', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Radenci Municipality', '201', '100', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Radlje ob Dravi Municipality', '201', '101', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Radovljica Municipality', '201', '102', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ravne na KoroÅ¡kem Municipality', '201', '103', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RazkriÅ¾je Municipality', '201', '176', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ReÄica ob Savinji Municipality', '201', '209', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RenÄeâ€“Vogrsko Municipality', '201', '201', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ribnica Municipality', '201', '104', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ribnica na Pohorju Municipality', '201', '177', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RogaÅ¡ka Slatina Municipality', '201', '106', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RogaÅ¡ovci Municipality', '201', '105', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rogatec Municipality', '201', '107', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RuÅ¡e Municipality', '201', '108', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å alovci Municipality', '201', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Selnica ob Dravi Municipality', '201', '178', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SemiÄ Municipality', '201', '109', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å empeterâ€“Vrtojba Municipality', '201', '183', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å enÄur Municipality', '201', '117', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å entilj Municipality', '201', '118', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å entjernej Municipality', '201', '119', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å entjur Municipality', '201', '120', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å entrupert Municipality', '201', '211', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sevnica Municipality', '201', '110', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SeÅ¾ana Municipality', '201', '111', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å kocjan Municipality', '201', '121', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å kofja Loka Municipality', '201', '122', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Slovenj Gradec City Municipality', '201', '112', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Slovenska Bistrica Municipality', '201', '113', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Slovenske Konjice Municipality', '201', '114', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å marje pri JelÅ¡ah Municipality', '201', '124', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å marjeÅ¡ke Toplice Municipality', '201', '206', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å martno ob Paki Municipality', '201', '125', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å martno pri Litiji Municipality', '201', '194', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SodraÅ¾ica Municipality', '201', '179', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SolÄava Municipality', '201', '180', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å oÅ¡tanj Municipality', '201', '126', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SrediÅ¡Äe ob Dravi', '201', '202', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('StarÅ¡e Municipality', '201', '115', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å tore Municipality', '201', '127', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('StraÅ¾a Municipality', '201', '203', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sveta Ana Municipality', '201', '181', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sveta Trojica v Slovenskih Goricah Municipality', '201', '204', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sveti AndraÅ¾ v Slovenskih Goricah Municipality', '201', '182', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sveti Jurij ob Å Äavnici Municipality', '201', '116', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sveti Jurij v Slovenskih Goricah Municipality', '201', '210', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sveti TomaÅ¾ Municipality', '201', '205', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tabor Municipality', '201', '184', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TiÅ¡ina Municipality', '201', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tolmin Municipality', '201', '128', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trbovlje Municipality', '201', '129', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trebnje Municipality', '201', '130', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trnovska Vas Municipality', '201', '185', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TrÅ¾iÄ Municipality', '201', '131', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trzin Municipality', '201', '186', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TurniÅ¡Äe Municipality', '201', '132', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Velika Polana Municipality', '201', '187', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Velike LaÅ¡Äe Municipality', '201', '134', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VerÅ¾ej Municipality', '201', '188', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Videm Municipality', '201', '135', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vipava Municipality', '201', '136', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vitanje Municipality', '201', '137', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vodice Municipality', '201', '138', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vojnik Municipality', '201', '139', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vransko Municipality', '201', '189', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vrhnika Municipality', '201', '140', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vuzenica Municipality', '201', '141', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zagorje ob Savi Municipality', '201', '142', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½alec Municipality', '201', '190', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ZavrÄ Municipality', '201', '143', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½elezniki Municipality', '201', '146', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½etale Municipality', '201', '191', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½iri Municipality', '201', '147', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½irovnica Municipality', '201', '192', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ZreÄe Municipality', '201', '144', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Å½uÅ¾emberk Municipality', '201', '193', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Province', '202', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Choiseul Province', '202', 'CH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guadalcanal Province', '202', 'GU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Honiara', '202', 'CT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isabel Province', '202', 'IS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Makira-Ulawa Province', '202', 'MK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Malaita Province', '202', 'ML', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rennell and Bellona Province', '202', 'RB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Temotu Province', '202', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Province', '202', 'WE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Awdal Region', '203', 'AW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bakool', '203', 'BK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Banaadir', '203', 'BN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bari', '203', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bay', '203', 'BY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Galguduud', '203', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gedo', '203', 'GE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hiran', '203', 'HI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lower Juba', '203', 'JH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lower Shebelle', '203', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Middle Juba', '203', 'JD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Middle Shebelle', '203', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mudug', '203', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nugal', '203', 'NU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sanaag Region', '203', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Togdheer Region', '203', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Cape', '204', 'EC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Free State', '204', 'FS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gauteng', '204', 'GP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KwaZulu-Natal', '204', 'KZN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Limpopo', '204', 'LP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mpumalanga', '204', 'MP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North West', '204', 'NW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Cape', '204', 'NC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Cape', '204', 'WC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Busan', '116', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Daegu', '116', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Daejeon', '116', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gangwon Province', '116', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gwangju', '116', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gyeonggi Province', '116', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Incheon', '116', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jeju', '116', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Chungcheong Province', '116', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Gyeongsang Province', '116', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Jeolla Province', '116', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sejong City', '116', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Seoul', '116', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Chungcheong Province', '116', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Gyeongsang Province', '116', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Jeolla Province', '116', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ulsan', '116', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Equatoria', '206', 'EC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Equatoria', '206', 'EE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jonglei State', '206', 'JG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lakes', '206', 'LK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Bahr el Ghazal', '206', 'BN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Unity', '206', 'UY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Upper Nile', '206', 'NU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Warrap', '206', 'WR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Bahr el Ghazal', '206', 'BW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Equatoria', '206', 'EW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('A CoruÃ±a', '207', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Albacete', '207', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alicante', '207', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Almeria', '207', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Araba', '207', 'VI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Asturias', '207', 'O', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ãvila', '207', 'AV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Badajoz', '207', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barcelona', '207', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bizkaia', '207', 'BI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Burgos', '207', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caceres', '207', 'CC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÃ¡diz', '207', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canarias', '207', 'CN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cantabria', '207', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CastellÃ³n', '207', 'CS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ceuta', '207', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ciudad Real', '207', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÃ³rdoba', '207', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cuenca', '207', 'CU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gipuzkoa', '207', 'SS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Girona', '207', 'GI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Granada', '207', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guadalajara', '207', 'GU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Huelva', '207', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Huesca', '207', 'HU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Islas Baleares', '207', 'PM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JaÃ©n', '207', 'J', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Rioja', '207', 'LO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Las Palmas', '207', 'GC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ©on', '207', 'LE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lleida', '207', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lugo', '207', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Madrid', '207', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÃ¡laga', '207', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Melilla', '207', 'ML', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Murcia', '207', 'MU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Navarra', '207', 'NA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ourense', '207', 'OR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palencia', '207', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pontevedra', '207', 'PO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salamanca', '207', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Santa Cruz de Tenerife', '207', 'TF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Segovia', '207', 'SG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sevilla', '207', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Soria', '207', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarragona', '207', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Teruel', '207', 'TE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Toledo', '207', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valencia', '207', 'V', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valladolid', '207', 'VA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamora', '207', 'ZA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zaragoza', '207', 'Z', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ampara District', '208', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Anuradhapura District', '208', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Badulla District', '208', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batticaloa District', '208', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Province', '208', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Colombo District', '208', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Province', '208', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Galle District', '208', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gampaha District', '208', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hambantota District', '208', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jaffna District', '208', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalutara District', '208', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kandy District', '208', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kegalle District', '208', '92', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kilinochchi District', '208', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mannar District', '208', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matale District', '208', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matara District', '208', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monaragala District', '208', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mullaitivu District', '208', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Central Province', '208', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Western Province', '208', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Province', '208', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nuwara Eliya District', '208', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Polonnaruwa District', '208', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puttalam District', '208', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ratnapura district', '208', '91', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sabaragamuwa Province', '208', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Province', '208', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trincomalee District', '208', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uva Province', '208', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vavuniya District', '208', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Province', '208', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Jazirah', '209', 'GZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Qadarif', '209', 'GD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blue Nile', '209', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Darfur', '209', 'DC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Darfur', '209', 'DE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kassala', '209', 'KA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khartoum', '209', 'KH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Darfur', '209', 'DN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Kordofan', '209', 'KN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern', '209', 'NO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Red Sea', '209', 'RS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('River Nile', '209', 'NR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sennar', '209', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Darfur', '209', 'DS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Kordofan', '209', 'KS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Darfur', '209', 'DW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Kordofan', '209', 'GK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('White Nile', '209', 'NW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brokopondo District', '210', 'BR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Commewijne District', '210', 'CM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coronie District', '210', 'CR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marowijne District', '210', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nickerie District', '210', 'NI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Para District', '210', 'PR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Paramaribo District', '210', 'PM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saramacca District', '210', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sipaliwini District', '210', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wanica District', '210', 'WA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hhohho District', '212', 'HH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lubombo District', '212', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manzini District', '212', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shiselweni District', '212', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blekinge', '213', 'K', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dalarna County', '213', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÃ¤vleborg County', '213', 'X', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gotland County', '213', 'I', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Halland County', '213', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÃ¤mtland County', '213', '0', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('JÃ¶nkÃ¶ping County', '213', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalmar County', '213', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kronoberg County', '213', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Norrbotten County', '213', 'BD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã–rebro County', '213', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã–stergÃ¶tland County', '213', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SkÃ¥ne County', '213', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ¶dermanland County', '213', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stockholm County', '213', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uppsala County', '213', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÃ¤rmland County', '213', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÃ¤sterbotten County', '213', 'AC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÃ¤sternorrland County', '213', 'Y', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÃ¤stmanland County', '213', 'U', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÃ¤stra GÃ¶taland County', '213', 'O', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aargau', '214', 'AG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Appenzell Ausserrhoden', '214', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Appenzell Innerrhoden', '214', 'AI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basel-Land', '214', 'BL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basel-Stadt', '214', 'BS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bern', '214', 'BE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fribourg', '214', 'FR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Geneva', '214', 'GE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Glarus', '214', 'GL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GraubÃ¼nden', '214', 'GR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jura', '214', 'JU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lucerne', '214', 'LU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NeuchÃ¢tel', '214', 'NE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nidwalden', '214', 'NW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Obwalden', '214', 'OW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Schaffhausen', '214', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Schwyz', '214', 'SZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Solothurn', '214', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('St. Gallen', '214', 'SG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thurgau', '214', 'TG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ticino', '214', 'TI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uri', '214', 'UR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Valais', '214', 'VS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vaud', '214', 'VD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zug', '214', 'ZG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ZÃ¼rich', '214', 'ZH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al-Hasakah', '215', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al-Raqqah', '215', 'RA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aleppo', '215', 'HL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('As-Suwayda', '215', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Damascus', '215', 'DI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Daraa', '215', 'DR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Deir ez-Zor', '215', 'DY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hama', '215', 'HM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Homs', '215', 'HI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Idlib', '215', 'ID', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Latakia', '215', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quneitra', '215', 'QU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rif Dimashq', '215', 'RD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tartus', '215', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Changhua', '216', 'CHA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiayi', '216', 'CYI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiayi', '216', 'CYQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hsinchu', '216', 'HSQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hsinchu', '216', 'HSZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hualien', '216', 'HUA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaohsiung', '216', 'KHH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Keelung', '216', 'KEE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kinmen', '216', 'KIN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lienchiang', '216', 'LIE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Miaoli', '216', 'MIA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nantou', '216', 'NAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Taipei', '216', 'NWT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Penghu', '216', 'PEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pingtung', '216', 'PIF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taichung', '216', 'TXG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tainan', '216', 'TNN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taipei', '216', 'TPE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taitung', '216', 'TTT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Taoyuan', '216', 'TAO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yilan', '216', 'ILA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yunlin', '216', 'YUN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('districts of Republican Subordination', '217', 'RA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gorno-Badakhshan Autonomous Province', '217', 'GB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khatlon Province', '217', 'KT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sughd Province', '217', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arusha', '218', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dar es Salaam', '218', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dodoma', '218', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Geita', '218', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iringa', '218', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kagera', '218', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Katavi', '218', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kigoma', '218', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kilimanjaro', '218', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lindi', '218', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manyara', '218', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mara', '218', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mbeya', '218', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Morogoro', '218', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mtwara', '218', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mwanza', '218', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Njombe', '218', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pemba North', '218', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pemba South', '218', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pwani', '218', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rukwa', '218', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ruvuma', '218', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shinyanga', '218', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Simiyu', '218', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Singida', '218', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Songwe', '218', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tabora', '218', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tanga', '218', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zanzibar North', '218', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zanzibar South', '218', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zanzibar West', '218', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amnat Charoen', '219', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ang Thong', '219', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bangkok', '219', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bueng Kan', '219', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buri Ram', '219', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chachoengsao', '219', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chai Nat', '219', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chaiyaphum', '219', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chanthaburi', '219', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiang Mai', '219', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chiang Rai', '219', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chon Buri', '219', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chumphon', '219', '86', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalasin', '219', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kamphaeng Phet', '219', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kanchanaburi', '219', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khon Kaen', '219', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Krabi', '219', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lampang', '219', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lamphun', '219', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Loei', '219', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lop Buri', '219', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mae Hong Son', '219', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maha Sarakham', '219', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mukdahan', '219', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakhon Nayok', '219', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakhon Pathom', '219', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakhon Phanom', '219', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakhon Ratchasima', '219', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakhon Sawan', '219', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakhon Si Thammarat', '219', '80', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nan', '219', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Narathiwat', '219', '96', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nong Bua Lam Phu', '219', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nong Khai', '219', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nonthaburi', '219', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pathum Thani', '219', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pattani', '219', '94', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pattaya', '219', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phangnga', '219', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phatthalung', '219', '93', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phayao', '219', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phetchabun', '219', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phetchaburi', '219', '76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phichit', '219', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phitsanulok', '219', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phra Nakhon Si Ayutthaya', '219', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phrae', '219', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Phuket', '219', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prachin Buri', '219', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Prachuap Khiri Khan', '219', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ranong', '219', '85', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ratchaburi', '219', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rayong', '219', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Roi Et', '219', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sa Kaeo', '219', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sakon Nakhon', '219', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samut Prakan', '219', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samut Sakhon', '219', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samut Songkhram', '219', '75', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saraburi', '219', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Satun', '219', '91', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Si Sa Ket', '219', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sing Buri', '219', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Songkhla', '219', '90', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sukhothai', '219', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Suphan Buri', '219', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surat Thani', '219', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surin', '219', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tak', '219', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trang', '219', '92', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trat', '219', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ubon Ratchathani', '219', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Udon Thani', '219', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uthai Thani', '219', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Uttaradit', '219', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yala', '219', '95', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yasothon', '219', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Acklins', '17', 'AK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Acklins and Crooked Islands', '17', 'AC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Berry Islands', '17', 'BY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bimini', '17', 'BI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Black Point', '17', 'BP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cat Island', '17', 'CI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Abaco', '17', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Andros', '17', 'CS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Eleuthera', '17', 'CE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Crooked Island', '17', 'CK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Grand Bahama', '17', 'EG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Exuma', '17', 'EX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Freeport', '17', 'FP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fresh Creek', '17', 'FC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Governor s Harbour', '17', 'GH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Grand Cay', '17', 'GC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Green Turtle Cay', '17', 'GT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Harbour Island', '17', 'HI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('High Rock', '17', 'HR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hope Town', '17', 'HT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Inagua', '17', 'IN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kemps Bay', '17', 'KB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Long Island', '17', 'LI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mangrove Cay', '17', 'MC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marsh Harbour', '17', 'MH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayaguana District', '17', 'MG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Providence', '17', 'NP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nichollstown and Berry Islands', '17', 'NB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Abaco', '17', 'NO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Andros', '17', 'NS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Eleuthera', '17', 'NE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ragged Island', '17', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rock Sound', '17', 'RS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rum Cay District', '17', 'RC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Salvador and Rum Cay', '17', 'SR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Salvador Island', '17', 'SS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sandy Point', '17', 'SP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Abaco', '17', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Andros', '17', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Eleuthera', '17', 'SE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Spanish Wells', '17', 'SW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Grand Bahama', '17', 'WG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Centrale Region', '220', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kara Region', '220', 'K', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maritime', '220', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Plateaux Region', '220', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Savanes Region', '220', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HaÊ»apai', '222', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ê»Eua', '222', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Niuas', '222', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tongatapu', '222', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VavaÊ»u', '222', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arima', '223', 'ARI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chaguanas', '223', 'CHA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Couva-Tabaquite-Talparo Regional Corporation', '223', 'CTT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Diego Martin Regional Corporation', '223', 'DMN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Tobago', '223', 'ETO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Penal-Debe Regional Corporation', '223', 'PED', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Point Fortin', '223', 'PTF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Port of Spain', '223', 'POS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Princes Town Regional Corporation', '223', 'PRT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rio Claro-Mayaro Regional Corporation', '223', 'MRC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Fernando', '223', 'SFO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San Juan-Laventille Regional Corporation', '223', 'SJL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sangre Grande Regional Corporation', '223', 'SGE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siparia Regional Corporation', '223', 'SIP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tunapuna-Piarco Regional Corporation', '223', 'TUP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Tobago', '223', 'WTO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ariana', '224', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ©ja', '224', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ben Arous', '224', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bizerte', '224', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GabÃ¨s', '224', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gafsa', '224', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jendouba', '224', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kairouan', '224', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kasserine', '224', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kebili', '224', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kef', '224', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mahdia', '224', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manouba', '224', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Medenine', '224', '82', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monastir', '224', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nabeul', '224', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sfax', '224', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sidi Bouzid', '224', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siliana', '224', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sousse', '224', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tataouine', '224', '83', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tozeur', '224', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tunis', '224', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zaghouan', '224', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adana', '225', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AdÄ±yaman', '225', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Afyonkarahisar', '225', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AÄŸrÄ±', '225', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aksaray', '225', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amasya', '225', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ankara', '225', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antalya', '225', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ardahan', '225', '75', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Artvin', '225', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AydÄ±n', '225', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BalÄ±kesir', '225', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BartÄ±n', '225', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batman', '225', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bayburt', '225', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bilecik', '225', '11', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BingÃ¶l', '225', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bitlis', '225', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bolu', '225', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Burdur', '225', '15', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bursa', '225', '16', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‡anakkale', '225', '17', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‡ankÄ±rÄ±', '225', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ã‡orum', '225', '19', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Denizli', '225', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DiyarbakÄ±r', '225', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DÃ¼zce', '225', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Edirne', '225', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ElazÄ±ÄŸ', '225', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Erzincan', '225', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Erzurum', '225', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('EskiÅŸehir', '225', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gaziantep', '225', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Giresun', '225', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GÃ¼mÃ¼ÅŸhane', '225', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HakkÃ¢ri', '225', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hatay', '225', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('IÄŸdÄ±r', '225', '76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isparta', '225', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ä°stanbul', '225', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ä°zmir', '225', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KahramanmaraÅŸ', '225', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KarabÃ¼k', '225', '78', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karaman', '225', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kars', '225', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kastamonu', '225', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kayseri', '225', '38', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kilis', '225', '79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÄ±rÄ±kkale', '225', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÄ±rklareli', '225', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÄ±rÅŸehir', '225', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kocaeli', '225', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Konya', '225', '42', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KÃ¼tahya', '225', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Malatya', '225', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manisa', '225', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mardin', '225', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mersin', '225', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MuÄŸla', '225', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MuÅŸ', '225', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NevÅŸehir', '225', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('NiÄŸde', '225', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ordu', '225', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Osmaniye', '225', '80', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rize', '225', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sakarya', '225', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samsun', '225', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÅžanlÄ±urfa', '225', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siirt', '225', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sinop', '225', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sivas', '225', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÅžÄ±rnak', '225', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TekirdaÄŸ', '225', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tokat', '225', '60', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trabzon', '225', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tunceli', '225', '62', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('UÅŸak', '225', '64', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Van', '225', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yalova', '225', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yozgat', '225', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zonguldak', '225', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ahal Region', '226', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ashgabat', '226', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Balkan Region', '226', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('DaÅŸoguz Region', '226', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lebap Region', '226', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mary Region', '226', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Funafuti', '228', 'FUN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nanumanga', '228', 'NMG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nanumea', '228', 'NMA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Niutao Island Council', '228', 'NIT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nui', '228', 'NUI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nukufetau', '228', 'NKF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nukulaelae', '228', 'NKL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vaitupu', '228', 'VAI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abim District', '229', '314', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Adjumani District', '229', '301', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agago District', '229', '322', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alebtong District', '229', '323', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amolatar District', '229', '315', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amudat District', '229', '324', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amuria District', '229', '216', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amuru District', '229', '316', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Apac District', '229', '302', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arua District', '229', '303', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Budaka District', '229', '217', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bududa District', '229', '218', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bugiri District', '229', '201', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buhweju District', '229', '420', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buikwe District', '229', '117', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bukedea District', '229', '219', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bukomansimbi District', '229', '118', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bukwo District', '229', '220', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bulambuli District', '229', '225', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buliisa District', '229', '416', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bundibugyo District', '229', '401', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bunyangabu District', '229', '430', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bushenyi District', '229', '402', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Busia District', '229', '202', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Butaleja District', '229', '221', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Butambala District', '229', '119', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Butebo District', '229', '233', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buvuma District', '229', '120', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buyende District', '229', '226', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Region', '229', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dokolo District', '229', '317', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Region', '229', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gomba District', '229', '121', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gulu District', '229', '304', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ibanda District', '229', '417', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iganga District', '229', '203', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isingiro District', '229', '418', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jinja District', '229', '204', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaabong District', '229', '318', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kabale District', '229', '404', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kabarole District', '229', '405', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaberamaido District', '229', '213', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kagadi District', '229', '427', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kakumiro District', '229', '428', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalangala District', '229', '101', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kaliro District', '229', '222', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalungu District', '229', '122', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kampala District', '229', '102', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kamuli District', '229', '205', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kamwenge District', '229', '413', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kanungu District', '229', '414', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kapchorwa District', '229', '206', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kasese District', '229', '406', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Katakwi District', '229', '207', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kayunga District', '229', '112', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kibaale District', '229', '407', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kiboga District', '229', '103', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kibuku District', '229', '227', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kiruhura District', '229', '419', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kiryandongo District', '229', '421', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kisoro District', '229', '408', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kitgum District', '229', '305', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Koboko District', '229', '319', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kole District', '229', '325', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kotido District', '229', '306', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kumi District', '229', '208', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kween District', '229', '228', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyankwanzi District', '229', '123', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyegegwa District', '229', '422', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyenjojo District', '229', '415', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyotera District', '229', '125', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lamwo District', '229', '326', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lira District', '229', '307', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luuka District', '229', '229', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luwero District', '229', '104', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lwengo District', '229', '124', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lyantonde District', '229', '114', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manafwa District', '229', '223', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maracha District', '229', '320', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masaka District', '229', '105', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masindi District', '229', '409', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mayuge District', '229', '214', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mbale District', '229', '209', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mbarara District', '229', '410', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mitooma District', '229', '423', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mityana District', '229', '115', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moroto District', '229', '308', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moyo District', '229', '309', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mpigi District', '229', '106', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mubende District', '229', '107', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mukono District', '229', '108', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakapiripirit District', '229', '311', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakaseke District', '229', '116', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nakasongola District', '229', '109', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Namayingo District', '229', '230', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Namisindwa District', '229', '234', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Namutumba District', '229', '224', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Napak District', '229', '327', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nebbi District', '229', '310', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ngora District', '229', '231', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Region', '229', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ntoroko District', '229', '424', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ntungamo District', '229', '411', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nwoya District', '229', '328', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Omoro District', '229', '331', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Otuke District', '229', '329', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oyam District', '229', '321', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pader District', '229', '312', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pakwach District', '229', '332', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pallisa District', '229', '210', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rakai District', '229', '110', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rubanda District', '229', '429', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rubirizi District', '229', '425', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rukiga District', '229', '431', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rukungiri District', '229', '412', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sembabule District', '229', '111', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Serere District', '229', '232', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sheema District', '229', '426', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sironko District', '229', '215', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Soroti District', '229', '211', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tororo District', '229', '212', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wakiso District', '229', '113', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Region', '229', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yumbe District', '229', '313', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zombo District', '229', '330', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Autonomous Republic of Crimea', '230', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cherkaska oblast', '230', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chernihivska oblast', '230', '74', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Chernivetska oblast', '230', '77', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dnipropetrovska oblast', '230', '12', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Donetska oblast', '230', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ivano-Frankivska oblast', '230', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kharkivska oblast', '230', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khersonska oblast', '230', '65', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Khmelnytska oblast', '230', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kirovohradska oblast', '230', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyiv', '230', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kyivska oblast', '230', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luhanska oblast', '230', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lvivska oblast', '230', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mykolaivska oblast', '230', '48', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Odeska oblast', '230', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Poltavska oblast', '230', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rivnenska oblast', '230', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sevastopol', '230', 'UA-40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sumska oblast', '230', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ternopilska oblast', '230', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vinnytska oblast', '230', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Volynska oblast', '230', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zakarpatska Oblast', '230', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zaporizka oblast', '230', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zhytomyrska oblast', '230', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abu Dhabi Emirate', '231', 'AZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ajman Emirate', '231', 'AJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dubai', '231', 'DU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fujairah', '231', 'FU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ras al-Khaimah', '231', 'RK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sharjah Emirate', '231', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Umm al-Quwain', '231', 'UQ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aberdeen', '232', 'ABE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aberdeenshire', '232', 'ABD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Angus', '232', 'ANS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antrim', '232', 'ANT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antrim and Newtownabbey', '232', 'ANN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ards', '232', 'ARD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ards and North Down', '232', 'AND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Argyll and Bute', '232', 'AGB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Armagh City and District Council', '232', 'ARM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Armagh, Banbridge and Craigavon', '232', 'ABC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ascension Island', '232', 'SH-AC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ballymena Borough', '232', 'BLA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ballymoney', '232', 'BLY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Banbridge', '232', 'BNB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barnsley', '232', 'BNS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bath and North East Somerset', '232', 'BAS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bedford', '232', 'BDF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Belfast district', '232', 'BFS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Birmingham', '232', 'BIR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blackburn with Darwen', '232', 'BBD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blackpool', '232', 'BPL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Blaenau Gwent County Borough', '232', 'BGW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bolton', '232', 'BOL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bournemouth', '232', 'BMH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bracknell Forest', '232', 'BRC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bradford', '232', 'BRD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bridgend County Borough', '232', 'BGE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Brighton and Hove', '232', 'BNH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Buckinghamshire', '232', 'BKM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bury', '232', 'BUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Caerphilly County Borough', '232', 'CAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Calderdale', '232', 'CLD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cambridgeshire', '232', 'CAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carmarthenshire', '232', 'CMN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carrickfergus Borough Council', '232', 'CKF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Castlereagh', '232', 'CSR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Causeway Coast and Glens', '232', 'CCG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Bedfordshire', '232', 'CBF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ceredigion', '232', 'CGN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cheshire East', '232', 'CHE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cheshire West and Chester', '232', 'CHW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City and County of Cardiff', '232', 'CRF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City and County of Swansea', '232', 'SWA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Bristol', '232', 'BST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Derby', '232', 'DER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Kingston upon Hull', '232', 'KHL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Leicester', '232', 'LCE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of London', '232', 'LND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Nottingham', '232', 'NGM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Peterborough', '232', 'PTE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Plymouth', '232', 'PLY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Portsmouth', '232', 'POR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Southampton', '232', 'STH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Stoke-on-Trent', '232', 'STE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Sunderland', '232', 'SND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Westminster', '232', 'WSM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of Wolverhampton', '232', 'WLV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('City of York', '232', 'YOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Clackmannanshire', '232', 'CLK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coleraine Borough Council', '232', 'CLR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Conwy County Borough', '232', 'CWY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cookstown District Council', '232', 'CKT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cornwall', '232', 'CON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('County Durham', '232', 'DUR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Coventry', '232', 'COV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Craigavon Borough Council', '232', 'CGV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cumbria', '232', 'CMA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Darlington', '232', 'DAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Denbighshire', '232', 'DEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Derbyshire', '232', 'DBY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Derry City and Strabane', '232', 'DRS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Derry City Council', '232', 'DRY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Devon', '232', 'DEV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Doncaster', '232', 'DNC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dorset', '232', 'DOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Down District Council', '232', 'DOW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dudley', '232', 'DUD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dumfries and Galloway', '232', 'DGY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dundee', '232', 'DND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dungannon and South Tyrone Borough Council', '232', 'DGN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Ayrshire', '232', 'EAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Dunbartonshire', '232', 'EDU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Lothian', '232', 'ELN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Renfrewshire', '232', 'ERW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Riding of Yorkshire', '232', 'ERY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('East Sussex', '232', 'ESX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Edinburgh', '232', 'EDH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('England', '232', 'ENG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Essex', '232', 'ESS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Falkirk', '232', 'FAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fermanagh and Omagh', '232', 'FMO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fermanagh District Council', '232', 'FER', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fife', '232', 'FIF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Flintshire', '232', 'FLN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gateshead', '232', 'GAT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Glasgow', '232', 'GLG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gloucestershire', '232', 'GLS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gwynedd', '232', 'GWN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Halton', '232', 'HAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hampshire', '232', 'HAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hartlepool', '232', 'HPL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Herefordshire', '232', 'HEF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hertfordshire', '232', 'HRT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Highland', '232', 'HLD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Inverclyde', '232', 'IVC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isle of Wight', '232', 'IOW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isles of Scilly', '232', 'IOS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kent', '232', 'KEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kirklees', '232', 'KIR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Knowsley', '232', 'KWL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lancashire', '232', 'LAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Larne Borough Council', '232', 'LRN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leeds', '232', 'LDS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leicestershire', '232', 'LEC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Limavady Borough Council', '232', 'LMV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lincolnshire', '232', 'LIN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lisburn and Castlereagh', '232', 'LBC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lisburn City Council', '232', 'LSB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Liverpool', '232', 'LIV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Barking and Dagenham', '232', 'BDG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Barnet', '232', 'BNE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Bexley', '232', 'BEX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Brent', '232', 'BEN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Bromley', '232', 'BRY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Camden', '232', 'CMD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Croydon', '232', 'CRY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Ealing', '232', 'EAL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Enfield', '232', 'ENF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Hackney', '232', 'HCK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Hammersmith and Fulham', '232', 'HMF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Haringey', '232', 'HRY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Harrow', '232', 'HRW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Havering', '232', 'HAV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Hillingdon', '232', 'HIL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Hounslow', '232', 'HNS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Islington', '232', 'ISL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Lambeth', '232', 'LBH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Lewisham', '232', 'LEW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Merton', '232', 'MRT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Newham', '232', 'NWM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Redbridge', '232', 'RDB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Richmond upon Thames', '232', 'RIC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Southwark', '232', 'SWK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Sutton', '232', 'STN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Tower Hamlets', '232', 'TWH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Waltham Forest', '232', 'WFT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('London Borough of Wandsworth', '232', 'WND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Magherafelt District Council', '232', 'MFT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manchester', '232', 'MAN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Medway', '232', 'MDW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Merthyr Tydfil County Borough', '232', 'MTY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Metropolitan Borough of Wigan', '232', 'WGN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mid and East Antrim', '232', 'MEA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mid Ulster', '232', 'MUL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Middlesbrough', '232', 'MDB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Midlothian', '232', 'MLN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Milton Keynes', '232', 'MIK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monmouthshire', '232', 'MON', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moray', '232', 'MRY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Moyle District Council', '232', 'MYL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Neath Port Talbot County Borough', '232', 'NTL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Newcastle upon Tyne', '232', 'NET', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Newport', '232', 'NWP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Newry and Mourne District Council', '232', 'NYM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Newry, Mourne and Down', '232', 'NMD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Newtownabbey Borough Council', '232', 'NTA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Norfolk', '232', 'NFK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Ayrshire', '232', 'NAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Down Borough Council', '232', 'NDN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North East Lincolnshire', '232', 'NEL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Lanarkshire', '232', 'NLK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Lincolnshire', '232', 'NLN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Somerset', '232', 'NSM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Tyneside', '232', 'NTY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Yorkshire', '232', 'NYK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northamptonshire', '232', 'NTH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Ireland', '232', 'NIR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northumberland', '232', 'NBL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nottinghamshire', '232', 'NTT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oldham', '232', 'OLD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Omagh District Council', '232', 'OMH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Orkney Islands', '232', 'ORK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Outer Hebrides', '232', 'ELS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oxfordshire', '232', 'OXF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pembrokeshire', '232', 'PEM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Perth and Kinross', '232', 'PKN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Poole', '232', 'POL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Powys', '232', 'POW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Reading', '232', 'RDG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Redcar and Cleveland', '232', 'RCC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Renfrewshire', '232', 'RFW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rhondda Cynon Taf', '232', 'RCT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rochdale', '232', 'RCH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rotherham', '232', 'ROT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Royal Borough of Greenwich', '232', 'GRE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Royal Borough of Kensington and Chelsea', '232', 'KEC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Royal Borough of Kingston upon Thames', '232', 'KTT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rutland', '232', 'RUT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Helena', '232', 'SH-HL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salford', '232', 'SLF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sandwell', '232', 'SAW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Scotland', '232', 'SCT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Scottish Borders', '232', 'SCB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sefton', '232', 'SFT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sheffield', '232', 'SHF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shetland Islands', '232', 'ZET', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shropshire', '232', 'SHR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Slough', '232', 'SLG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Solihull', '232', 'SOL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Somerset', '232', 'SOM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Ayrshire', '232', 'SAY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Gloucestershire', '232', 'SGC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Lanarkshire', '232', 'SLK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Tyneside', '232', 'STY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southend-on-Sea', '232', 'SOS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('St Helens', '232', 'SHN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Staffordshire', '232', 'STS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stirling', '232', 'STG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stockport', '232', 'SKP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Stockton-on-Tees', '232', 'STT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Strabane District Council', '232', 'STB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Suffolk', '232', 'SFK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surrey', '232', 'SRY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Swindon', '232', 'SWD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tameside', '232', 'TAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Telford and Wrekin', '232', 'TFW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thurrock', '232', 'THR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Torbay', '232', 'TOB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Torfaen', '232', 'TOF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trafford', '232', 'TRF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('United Kingdom', '232', 'UKM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vale of Glamorgan', '232', 'VGL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wakefield', '232', 'WKF', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wales', '232', 'WLS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Walsall', '232', 'WLL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Warrington', '232', 'WRT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Warwickshire', '232', 'WAR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Berkshire', '232', 'WBK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Dunbartonshire', '232', 'WDU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Lothian', '232', 'WLN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Sussex', '232', 'WSX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wiltshire', '232', 'WIL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Windsor and Maidenhead', '232', 'WNM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wirral', '232', 'WRL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wokingham', '232', 'WOK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Worcestershire', '232', 'WOR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wrexham County Borough', '232', 'WRX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alabama', '233', 'AL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Alaska', '233', 'AK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('American Samoa', '233', 'AS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arizona', '233', 'AZ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Arkansas', '233', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baker Island', '233', 'UM-81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('California', '233', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Colorado', '233', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Connecticut', '233', 'CT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Delaware', '233', 'DE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('District of Columbia', '233', 'DC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Florida', '233', 'FL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Georgia', '233', 'GA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guam', '233', 'GU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hawaii', '233', 'HI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Howland Island', '233', 'UM-84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Idaho', '233', 'ID', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Illinois', '233', 'IL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Indiana', '233', 'IN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iowa', '233', 'IA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jarvis Island', '233', 'UM-86', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Johnston Atoll', '233', 'UM-67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kansas', '233', 'KS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kentucky', '233', 'KY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kingman Reef', '233', 'UM-89', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Louisiana', '233', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maine', '233', 'ME', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maryland', '233', 'MD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Massachusetts', '233', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Michigan', '233', 'MI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Midway Atoll', '233', 'UM-71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Minnesota', '233', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mississippi', '233', 'MS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Missouri', '233', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Montana', '233', 'MT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Navassa Island', '233', 'UM-76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nebraska', '233', 'NE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nevada', '233', 'NV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Hampshire', '233', 'NH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Jersey', '233', 'NJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New Mexico', '233', 'NM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('New York', '233', 'NY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Carolina', '233', 'NC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('North Dakota', '233', 'ND', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Mariana Islands', '233', 'MP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ohio', '233', 'OH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oklahoma', '233', 'OK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oregon', '233', 'OR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palmyra Atoll', '233', 'UM-95', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pennsylvania', '233', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Puerto Rico', '233', 'PR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rhode Island', '233', 'RI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Carolina', '233', 'SC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Dakota', '233', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tennessee', '233', 'TN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Texas', '233', 'TX', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('United States Minor Outlying Islands', '233', 'UM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('United States Virgin Islands', '233', 'VI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Utah', '233', 'UT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Vermont', '233', 'VT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Virginia', '233', 'VA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wake Island', '233', 'UM-79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Washington', '233', 'WA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('West Virginia', '233', 'WV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wisconsin', '233', 'WI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wyoming', '233', 'WY', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Baker Island', '234', '81', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Howland Island', '234', '84', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jarvis Island', '234', '86', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Johnston Atoll', '234', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kingman Reef', '234', '89', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Midway Islands', '234', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Navassa Island', '234', '76', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palmyra Atoll', '234', '95', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Wake Island', '234', '79', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Artigas', '235', 'AR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Canelones', '235', 'CA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cerro Largo', '235', 'CL', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Colonia', '235', 'CO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Durazno', '235', 'DU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Flores', '235', 'FS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Florida', '235', 'FD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lavalleja', '235', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maldonado', '235', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Montevideo', '235', 'MO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PaysandÃº', '235', 'PA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('RÃ­o Negro', '235', 'RN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rivera', '235', 'RV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rocha', '235', 'RO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Salto', '235', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('San JosÃ©', '235', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Soriano', '235', 'SO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TacuarembÃ³', '235', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Treinta y Tres', '235', 'TT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Andijan Region', '236', 'AN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bukhara Region', '236', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Fergana Region', '236', 'FA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Jizzakh Region', '236', 'JI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Karakalpakstan', '236', 'QR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Namangan Region', '236', 'NG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Navoiy Region', '236', 'NW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Qashqadaryo Region', '236', 'QA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samarqand Region', '236', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sirdaryo Region', '236', 'SI', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surxondaryo Region', '236', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tashkent', '236', 'TK', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tashkent Region', '236', 'TO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Xorazm Region', '236', 'XO', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Malampa', '237', 'MAP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Penama', '237', 'PAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sanma', '237', 'SAM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shefa', '237', 'SEE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tafea', '237', 'TAE', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Torba', '237', 'TOB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amazonas', '239', 'Z', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('AnzoÃ¡tegui', '239', 'B', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Apure', '239', 'C', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aragua', '239', 'D', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Barinas', '239', 'E', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BolÃ­var', '239', 'F', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Carabobo', '239', 'G', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cojedes', '239', 'H', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Delta Amacuro', '239', 'Y', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Distrito Capital', '239', 'A', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('FalcÃ³n', '239', 'I', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Federal Dependencies of Venezuela', '239', 'W', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('GuÃ¡rico', '239', 'J', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Guaira', '239', 'X', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lara', '239', 'K', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('MÃ©rida', '239', 'L', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Miranda', '239', 'M', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Monagas', '239', 'N', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nueva Esparta', '239', 'O', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Portuguesa', '239', 'P', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sucre', '239', 'R', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÃ¡chira', '239', 'S', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Trujillo', '239', 'T', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Yaracuy', '239', 'U', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zulia', '239', 'V', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('An Giang', '240', '44', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ  Rá»‹a-VÅ©ng TÃ u', '240', '43', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Báº¯c Giang', '240', '54', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Báº¯c Káº¡n', '240', '53', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Báº¡c LiÃªu', '240', '55', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Báº¯c Ninh', '240', '56', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Báº¿n Tre', '240', '50', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ¬nh DÆ°Æ¡ng', '240', '57', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ¬nh Äá»‹nh', '240', '31', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ¬nh PhÆ°á»›c', '240', '58', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('BÃ¬nh Thuáº­n', '240', '40', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('CÃ  Mau', '240', '59', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cáº§n ThÆ¡', '240', 'CT', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cao Báº±ng', '240', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ÄÃ  Náºµng', '240', 'DN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Äáº¯k Láº¯k', '240', '33', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Äáº¯k NÃ´ng', '240', '72', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Äiá»‡n BiÃªn', '240', '71', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Äá»“ng Nai', '240', '39', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Äá»“ng ThÃ¡p', '240', '45', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Gia Lai', '240', '30', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ  Giang', '240', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ  Nam', '240', '63', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ  Ná»™i', '240', 'HN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ  TÄ©nh', '240', '23', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Háº£i DÆ°Æ¡ng', '240', '61', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Háº£i PhÃ²ng', '240', 'HP', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Háº­u Giang', '240', '73', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Há»“ ChÃ­ Minh', '240', 'SG', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÃ²a BÃ¬nh', '240', '14', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('HÆ°ng YÃªn', '240', '66', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KhÃ¡nh HÃ²a', '240', '34', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('KiÃªn Giang', '240', '47', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kon Tum', '240', '28', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lai ChÃ¢u', '240', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ¢m Äá»“ng', '240', '35', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Láº¡ng SÆ¡n', '240', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('LÃ o Cai', '240', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Long An', '240', '41', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nam Äá»‹nh', '240', '67', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nghá»‡ An', '240', '22', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ninh BÃ¬nh', '240', '18', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ninh Thuáº­n', '240', '36', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PhÃº Thá»', '240', '68', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('PhÃº YÃªn', '240', '32', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quáº£ng BÃ¬nh', '240', '24', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quáº£ng Nam', '240', '27', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quáº£ng NgÃ£i', '240', '29', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quáº£ng Ninh', '240', '13', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quáº£ng Trá»‹', '240', '25', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÃ³c TrÄƒng', '240', '52', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('SÆ¡n La', '240', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TÃ¢y Ninh', '240', '37', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ThÃ¡i BÃ¬nh', '240', '20', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('ThÃ¡i NguyÃªn', '240', '69', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thanh HÃ³a', '240', '21', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Thá»«a ThiÃªn-Huáº¿', '240', '26', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tiá»n Giang', '240', '46', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TrÃ  Vinh', '240', '51', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('TuyÃªn Quang', '240', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÄ©nh Long', '240', '49', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('VÄ©nh PhÃºc', '240', '70', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('YÃªn BÃ¡i', '240', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Croix', '242', 'SC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint John', '242', 'SJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saint Thomas', '242', 'ST', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES (' Adan', '245', 'AD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES (' Amran', '245', 'AM', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abyan', '245', 'AB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Bayda ', '245', 'BA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Hudaydah', '245', 'HU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Jawf', '245', 'JA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Mahrah', '245', 'MR', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Al Mahwit', '245', 'MW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Amanat Al Asimah', '245', 'SA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dhamar', '245', 'DH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hadhramaut', '245', 'HD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Hajjah', '245', 'HJ', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ibb', '245', 'IB', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lahij', '245', 'LA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ma rib', '245', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Raymah', '245', 'RA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Saada', '245', 'SD', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sana a', '245', 'SN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Shabwah', '245', 'SH', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Socotra', '245', 'SU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ta izz', '245', 'TA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Central Province', '246', '2', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Copperbelt Province', '246', '8', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Province', '246', '3', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Luapula Province', '246', '4', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lusaka Province', '246', '9', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Muchinga Province', '246', '10', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Province', '246', '5', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northwestern Province', '246', '6', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Province', '246', '7', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Western Province', '246', '1', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bulawayo Province', '247', 'BU', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Harare Province', '247', 'HA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Manicaland', '247', 'MA', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mashonaland Central Province', '247', 'MC', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mashonaland East Province', '247', 'ME', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mashonaland West Province', '247', 'MW', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masvingo Province', '247', 'MV', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matabeleland North Province', '247', 'MN', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Matabeleland South Province', '247', 'MS', '0');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Midlands Province', '247', 'MI', '0');