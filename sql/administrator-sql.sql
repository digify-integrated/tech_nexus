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
END //

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
    SET query = CONCAT(query, ' ORDER BY state_id');
    
    IF p_country_id = 0 THEN
        SET query = CONCAT(query, ' LIMIT 500');
    END IF;

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

/* City table */
CREATE TABLE city(
	city_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	city_name VARCHAR(100) NOT NULL,
	state_id INT NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX city_index_city_id ON city(city_id);
CREATE INDEX city_index_state_id ON city(state_id);

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

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('city', NEW.city_id, audit_log, NEW.last_log_by, NOW());
END //

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

CREATE PROCEDURE generateCityTable(IN p_state_id INT)
BEGIN
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

/* Currency table */
CREATE TABLE currency(
	currency_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	currency_name VARCHAR(100) NOT NULL,
	symbol VARCHAR(10) NOT NULL,
	shorthand VARCHAR(10) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX currency_index_currency_id ON currency(currency_id);

CREATE TRIGGER currency_trigger_update
AFTER UPDATE ON currency
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER currency_trigger_insert
AFTER INSERT ON currency
FOR EACH ROW
BEGIN
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
END //

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

/* Company table */
CREATE TABLE company(
	company_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	company_name VARCHAR(100) NOT NULL,
    company_logo VARCHAR(500) NULL,
	address VARCHAR(1000) NOT NULL,
	city_id INT NOT NULL,
	tax_id VARCHAR(500),
	currency_id INT,
	phone VARCHAR(20),
	mobile VARCHAR(20),
	telephone VARCHAR(20),
	email VARCHAR(100),
	website VARCHAR(500),
    last_log_by INT NOT NULL
);

CREATE INDEX company_index_company_id ON company(company_id);

CREATE TRIGGER company_trigger_update
AFTER UPDATE ON company
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER company_trigger_insert
AFTER INSERT ON company
FOR EACH ROW
BEGIN
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
END //

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

CREATE PROCEDURE generateCompanyTable()
BEGIN
    SELECT company_id, company_name, company_logo, address, city_id
    FROM company
    ORDER BY company_id;
END //

/* Email setting table */
CREATE TABLE email_setting(
	email_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	email_setting_name VARCHAR(100) NOT NULL,
	email_setting_description VARCHAR(200) NOT NULL,
	mail_host VARCHAR(100) NOT NULL,
	port INT NOT NULL,
	smtp_auth INT(1) NOT NULL,
	smtp_auto_tls INT(1) NOT NULL,
	mail_username VARCHAR(200) NOT NULL,
	mail_password VARCHAR(250) NOT NULL,
	mail_encryption VARCHAR(20),
	mail_from_name VARCHAR(200),
	mail_from_email VARCHAR(200),
    last_log_by INT NOT NULL
);

CREATE INDEX email_setting_index_email_setting_id ON email_setting(email_setting_id);

CREATE TRIGGER email_setting_trigger_update
AFTER UPDATE ON email_setting
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER email_setting_trigger_insert
AFTER INSERT ON email_setting
FOR EACH ROW
BEGIN
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
END //

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

/* Notification setting table */
CREATE TABLE notification_setting(
	notification_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	notification_setting_name VARCHAR(100) NOT NULL,
	notification_setting_description VARCHAR(200) NOT NULL,
	system_notification INT(1) NOT NULL DEFAULT 1,
	email_notification INT(1) NOT NULL DEFAULT 0,
	sms_notification INT(1) NOT NULL DEFAULT 0,
	system_notification_title VARCHAR(200),
	system_notification_message VARCHAR(200),
	email_notification_subject VARCHAR(200),
	email_notification_body LONGTEXT,
	sms_notification_message VARCHAR(500),
    last_log_by INT NOT NULL
);

CREATE INDEX notification_setting_index_notification_setting_id ON notification_setting(notification_setting_id);

CREATE TRIGGER notification_setting_trigger_update
AFTER UPDATE ON notification_setting
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER notification_setting_trigger_insert
AFTER INSERT ON notification_setting
FOR EACH ROW
BEGIN
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
END //

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

/* Zoom API table */
CREATE TABLE zoom_api(
	zoom_api_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	zoom_api_name VARCHAR(100) NOT NULL,
	zoom_api_description VARCHAR(200) NOT NULL,
	api_key VARCHAR(1000) NOT NULL,
	api_secret VARCHAR(1000) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX zoom_api_index_zoom_api_id ON zoom_api(zoom_api_id);

CREATE TRIGGER zoom_api_trigger_update
AFTER UPDATE ON zoom_api
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER zoom_api_trigger_insert
AFTER INSERT ON zoom_api
FOR EACH ROW
BEGIN
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
END //

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

/* Branch table */
CREATE TABLE branch(
	branch_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	branch_name VARCHAR(100) NOT NULL,
	address VARCHAR(1000) NOT NULL,
	city_id INT NOT NULL,
    phone VARCHAR(20),
	mobile VARCHAR(20),
	telephone VARCHAR(20),
	email VARCHAR(100),
	website VARCHAR(500),
    last_log_by INT NOT NULL
);

CREATE INDEX branch_index_branch_id ON branch(branch_id);

CREATE TRIGGER branch_trigger_update
AFTER UPDATE ON branch
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER branch_trigger_insert
AFTER INSERT ON branch
FOR EACH ROW
BEGIN
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
END //

CREATE PROCEDURE checkBranchExist (IN p_branch_id INT)
BEGIN
	SELECT COUNT(*) AS total
    FROM branch
    WHERE branch_id = p_branch_id;
END //

CREATE PROCEDURE insertBranch(IN p_branch_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_website VARCHAR(500), IN p_last_log_by INT, OUT p_branch_id INT)
BEGIN
    INSERT INTO branch (branch_name, address, city_id, phone, mobile, telephone, email, website, last_log_by) 
	VALUES(p_branch_name, p_address, p_city_id, p_phone, p_mobile, p_telephone, p_email, p_website, p_last_log_by);
	
    SET p_branch_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE updateBranch(IN p_branch_id INT, IN p_branch_name VARCHAR(100), IN p_address VARCHAR(1000), IN p_city_id INT, IN p_phone VARCHAR(20), IN p_mobile VARCHAR(20), IN p_telephone VARCHAR(20), IN p_email VARCHAR(100), IN p_website VARCHAR(500), IN p_last_log_by INT)
BEGIN
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
END //

CREATE PROCEDURE generateBranchTable()
BEGIN
    SELECT branch_id, branch_name, address, city_id
    FROM branch
    ORDER BY branch_id;
END //

CREATE PROCEDURE generateBranchOptions()
BEGIN
	SELECT branch_id, branch_name FROM branch
	ORDER BY branch_name;
END //

/* Department table */
CREATE TABLE department(
	department_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	department_name VARCHAR(100) NOT NULL,
	parent_department INT,
	manager INT,
    last_log_by INT NOT NULL
);

CREATE INDEX department_index_department_id ON department(department_id);

CREATE TRIGGER department_trigger_update
AFTER UPDATE ON department
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER department_trigger_insert
AFTER INSERT ON department
FOR EACH ROW
BEGIN
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
END //

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

/* Job position table */
CREATE TABLE job_position(
	job_position_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_name VARCHAR(100) NOT NULL,
	job_position_description VARCHAR(2000) NOT NULL,
	recruitment_status TINYINT(1),
	department_id INT,
	expected_new_employees INT NOT NULL DEFAULT 0,
    last_log_by INT NOT NULL
);

CREATE INDEX job_position_index_job_position_id ON job_position(job_position_id);

CREATE TRIGGER job_position_trigger_update
AFTER UPDATE ON job_position
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER job_position_trigger_insert
AFTER INSERT ON job_position
FOR EACH ROW
BEGIN
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
END //

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

CREATE PROCEDURE generateJobPositionTable(IN p_filter_recruitment_status ENUM('active', 'inactive', 'all'), IN p_department_id INT)
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

    IF p_department_id <> "" THEN
        SET conditionList = CONCAT(conditionList, ' AND department_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_department_id));
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

/* Job position responsibility table */
CREATE TABLE job_position_responsibility(
	job_position_responsibility_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_id INT UNSIGNED NOT NULL,
	responsibility VARCHAR(1000) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX job_position_responsibility_index_job_position_id ON job_position_responsibility(job_position_id);
CREATE INDEX job_position_responsibility_index_job_position_responsibility_id ON job_position_responsibility(job_position_responsibility_id);

ALTER TABLE job_position_responsibility
ADD FOREIGN KEY (job_position_id) REFERENCES job_position(job_position_id);

CREATE TRIGGER job_position_responsibility_trigger_update
AFTER UPDATE ON job_position_responsibility
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.responsibility <> OLD.responsibility THEN
        SET audit_log = CONCAT(audit_log, "Responsibility: ", OLD.responsibility, " -> ", NEW.responsibility, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_position_responsibility', NEW.job_position_responsibility_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER job_position_responsibility_trigger_insert
AFTER INSERT ON job_position_responsibility
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job position responsibility created. <br/>';

    IF NEW.responsibility <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Responsibility: ", NEW.responsibility);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_position_responsibility', NEW.job_position_responsibility_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Job position requirement table */
CREATE TABLE job_position_requirement(
	job_position_requirement_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_id INT UNSIGNED NOT NULL,
	requirement VARCHAR(1000) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX job_position_requirement_index_job_position_id ON job_position_requirement(job_position_id);
CREATE INDEX job_position_requirement_index_job_position_requirement_id ON job_position_requirement(job_position_requirement_id);

ALTER TABLE job_position_requirement
ADD FOREIGN KEY (job_position_id) REFERENCES job_position(job_position_id);

CREATE TRIGGER job_position_requirement_trigger_update
AFTER UPDATE ON job_position_requirement
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.requirement <> OLD.requirement THEN
        SET audit_log = CONCAT(audit_log, "Requirement: ", OLD.requirement, " -> ", NEW.requirement, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_position_requirement', NEW.job_position_requirement_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER job_position_requirement_trigger_insert
AFTER INSERT ON job_position_requirement
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job position requirement created. <br/>';

    IF NEW.requirement <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Requirement: ", NEW.requirement);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_position_requirement', NEW.job_position_requirement_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Job position qualification table */
CREATE TABLE job_position_qualification(
	job_position_qualification_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_id INT UNSIGNED NOT NULL,
	qualification VARCHAR(1000) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX job_position_qualification_index_job_position_id ON job_position_qualification(job_position_id);
CREATE INDEX job_position_qualification_index_job_position_qualification_id ON job_position_qualification(job_position_qualification_id);

ALTER TABLE job_position_qualification
ADD FOREIGN KEY (job_position_id) REFERENCES job_position(job_position_id);

CREATE TRIGGER job_position_qualification_trigger_update
AFTER UPDATE ON job_position_qualification
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.qualification <> OLD.qualification THEN
        SET audit_log = CONCAT(audit_log, "Qualification: ", OLD.qualification, " -> ", NEW.qualification, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('job_position_qualification', NEW.job_position_qualification_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER job_position_qualification_trigger_insert
AFTER INSERT ON job_position_qualification
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Job position qualification created. <br/>';

    IF NEW.qualification <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Qualification: ", NEW.qualification);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('job_position_qualification', NEW.job_position_qualification_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Job level table */
CREATE TABLE job_level(
	job_level_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	current_level VARCHAR(10) NOT NULL,
	rank VARCHAR(100) NOT NULL,
	functional_level VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX job_level_index_job_level_id ON job_level(job_level_id);

CREATE TRIGGER job_level_trigger_update
AFTER UPDATE ON job_level
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER job_level_trigger_insert
AFTER INSERT ON job_level
FOR EACH ROW
BEGIN
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
END //

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

/* Employee type table */
CREATE TABLE employee_type(
	employee_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	employee_type_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX employee_type_index_employee_type_id ON employee_type(employee_type_id);

CREATE TRIGGER employee_type_trigger_update
AFTER UPDATE ON employee_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.employee_type_name <> OLD.employee_type_name THEN
        SET audit_log = CONCAT(audit_log, "Employee Type Name: ", OLD.employee_type_name, " -> ", NEW.employee_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('employee_type', NEW.employee_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER employee_type_trigger_insert
AFTER INSERT ON employee_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Employee type created. <br/>';

    IF NEW.employee_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Employee Type Name: ", NEW.employee_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('employee_type', NEW.employee_type_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Departure reason table */
CREATE TABLE departure_reason(
	departure_reason_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	departure_reason_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX departure_reason_index_departure_reason_id ON departure_reason(departure_reason_id);

CREATE TRIGGER departure_reason_trigger_update
AFTER UPDATE ON departure_reason
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.departure_reason_name <> OLD.departure_reason_name THEN
        SET audit_log = CONCAT(audit_log, "Departure Reason Name: ", OLD.departure_reason_name, " -> ", NEW.departure_reason_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('departure_reason', NEW.departure_reason_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER departure_reason_trigger_insert
AFTER INSERT ON departure_reason
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Departure reason created. <br/>';

    IF NEW.departure_reason_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Departure Reason Name: ", NEW.departure_reason_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('departure_reason', NEW.departure_reason_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* ID Type table */
CREATE TABLE id_type(
	id_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	id_type_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX id_type_index_id_type_id ON id_type(id_type_id);

CREATE TRIGGER id_type_trigger_update
AFTER UPDATE ON id_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.id_type_name <> OLD.id_type_name THEN
        SET audit_log = CONCAT(audit_log, "ID Type Name: ", OLD.id_type_name, " -> ", NEW.id_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('id_type', NEW.id_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER id_type_trigger_insert
AFTER INSERT ON id_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'ID type created. <br/>';

    IF NEW.id_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>ID Type Name: ", NEW.id_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('id_type', NEW.id_type_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Gender table */
CREATE TABLE gender(
	gender_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	gender_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX gender_index_gender_id ON gender(gender_id);

CREATE TRIGGER gender_trigger_update
AFTER UPDATE ON gender
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.gender_name <> OLD.gender_name THEN
        SET audit_log = CONCAT(audit_log, "Gender Name: ", OLD.gender_name, " -> ", NEW.gender_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('gender', NEW.gender_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER gender_trigger_insert
AFTER INSERT ON gender
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Gender created. <br/>';

    IF NEW.gender_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Gender Name: ", NEW.gender_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('gender', NEW.gender_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Religion table */
CREATE TABLE religion(
	religion_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	religion_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX religion_index_religion_id ON religion(religion_id);

CREATE TRIGGER religion_trigger_update
AFTER UPDATE ON religion
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.religion_name <> OLD.religion_name THEN
        SET audit_log = CONCAT(audit_log, "Religion Name: ", OLD.religion_name, " -> ", NEW.religion_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('religion', NEW.religion_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER religion_trigger_insert
AFTER INSERT ON religion
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Religion created. <br/>';

    IF NEW.religion_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Religion Name: ", NEW.religion_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('religion', NEW.religion_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Nationality table */
CREATE TABLE nationality(
	nationality_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	nationality_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX nationality_index_nationality_id ON nationality(nationality_id);

CREATE TRIGGER nationality_trigger_update
AFTER UPDATE ON nationality
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.nationality_name <> OLD.nationality_name THEN
        SET audit_log = CONCAT(audit_log, "Nationality Name: ", OLD.nationality_name, " -> ", NEW.nationality_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('nationality', NEW.nationality_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER nationality_trigger_insert
AFTER INSERT ON nationality
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Nationality created. <br/>';

    IF NEW.nationality_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Nationality Name: ", NEW.nationality_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('nationality', NEW.nationality_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Relation table */
CREATE TABLE relation(
	relation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	relation_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX relation_index_relation_id ON relation(relation_id);

CREATE TRIGGER relation_trigger_update
AFTER UPDATE ON relation
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.relation_name <> OLD.relation_name THEN
        SET audit_log = CONCAT(audit_log, "Relation Name: ", OLD.relation_name, " -> ", NEW.relation_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('relation', NEW.relation_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER relation_trigger_insert
AFTER INSERT ON relation
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Relation created. <br/>';

    IF NEW.relation_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Relation Name: ", NEW.relation_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('relation', NEW.relation_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Civil status table */
CREATE TABLE civil_status(
	civil_status_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	civil_status_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX civil_status_index_civil_status_id ON civil_status(civil_status_id);

CREATE TRIGGER civil_status_trigger_update
AFTER UPDATE ON civil_status
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.civil_status_name <> OLD.civil_status_name THEN
        SET audit_log = CONCAT(audit_log, "Civil Status Name: ", OLD.civil_status_name, " -> ", NEW.civil_status_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('civil_status', NEW.civil_status_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER civil_status_trigger_insert
AFTER INSERT ON civil_status
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Civil status created. <br/>';

    IF NEW.civil_status_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Civil Status Name: ", NEW.civil_status_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('civil_status', NEW.civil_status_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Blood type table */
CREATE TABLE blood_type(
	blood_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	blood_type_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX blood_type_index_blood_type_id ON blood_type(blood_type_id);

CREATE TRIGGER blood_type_trigger_update
AFTER UPDATE ON blood_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.blood_type_name <> OLD.blood_type_name THEN
        SET audit_log = CONCAT(audit_log, "Blood Type Name: ", OLD.blood_type_name, " -> ", NEW.blood_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('blood_type', NEW.blood_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER blood_type_trigger_insert
AFTER INSERT ON blood_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Blood type created. <br/>';

    IF NEW.blood_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Blood Type Name: ", NEW.blood_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('blood_type', NEW.blood_type_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Bank table */
CREATE TABLE bank(
	bank_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	bank_name VARCHAR(100) NOT NULL,
	bank_identifier_code VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX bank_index_bank_id ON bank(bank_id);

CREATE TRIGGER bank_trigger_update
AFTER UPDATE ON bank
FOR EACH ROW
BEGIN
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
END //

CREATE TRIGGER bank_trigger_insert
AFTER INSERT ON bank
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Bank created. <br/>';

    IF NEW.bank_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Name: ", NEW.bank_name);
    END IF;

    IF NEW.bank_identifier_code <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Identifier Code: ", NEW.bank_identifier_code);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('bank', NEW.bank_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Holiday type table */
CREATE TABLE holiday_type(
	holiday_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	holiday_type_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX holiday_type_index_holiday_type_id ON holiday_type(holiday_type_id);

CREATE TRIGGER holiday_type_trigger_update
AFTER UPDATE ON holiday_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.holiday_type_name <> OLD.holiday_type_name THEN
        SET audit_log = CONCAT(audit_log, "Holiday Type Name: ", OLD.holiday_type_name, " -> ", NEW.holiday_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('holiday_type', NEW.holiday_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER holiday_type_trigger_insert
AFTER INSERT ON holiday_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Holiday type created. <br/>';

    IF NEW.holiday_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Holiday Type Name: ", NEW.holiday_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('holiday_type', NEW.holiday_type_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Work schedule type table */
CREATE TABLE work_schedule_type(
	work_schedule_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	work_schedule_type_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX work_schedule_type_index_work_schedule_type_id ON work_schedule_type(work_schedule_type_id);

CREATE TRIGGER work_schedule_type_trigger_update
AFTER UPDATE ON work_schedule_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.work_schedule_type_name <> OLD.work_schedule_type_name THEN
        SET audit_log = CONCAT(audit_log, "Work Schedule Type Name: ", OLD.work_schedule_type_name, " -> ", NEW.work_schedule_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('work_schedule_type', NEW.work_schedule_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER work_schedule_type_trigger_insert
AFTER INSERT ON work_schedule_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Work schedule type created. <br/>';

    IF NEW.work_schedule_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Work Schedule Type Name: ", NEW.work_schedule_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('work_schedule_type', NEW.work_schedule_type_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Work schedule table */
CREATE TABLE work_schedule(
	work_schedule_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	work_schedule_name VARCHAR(100) NOT NULL,
	work_schedule_description VARCHAR(500) NOT NULL,
	work_schedule_type_id INT UNSIGNED NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX work_schedule_index_work_schedule_id ON work_schedule(work_schedule_id);
CREATE INDEX work_schedule_index_work_schedule_type_id ON work_schedule(work_schedule_type_id);

CREATE TRIGGER work_schedule_trigger_update
AFTER UPDATE ON work_schedule
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.work_schedule_name <> OLD.work_schedule_name THEN
        SET audit_log = CONCAT(audit_log, "Work Schedule Name: ", OLD.work_schedule_name, " -> ", NEW.work_schedule_name, "<br/>");
    END IF;

    IF NEW.work_schedule_description <> OLD.work_schedule_description THEN
        SET audit_log = CONCAT(audit_log, "Work Schedule Description: ", OLD.work_schedule_description, " -> ", NEW.work_schedule_description, "<br/>");
    END IF;

    IF NEW.work_schedule_type_id <> OLD.work_schedule_type_id THEN
        SET audit_log = CONCAT(audit_log, "Work Schedule Type ID: ", OLD.work_schedule_type_id, " -> ", NEW.work_schedule_type_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('work_schedule', NEW.work_schedule_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER work_schedule_trigger_insert
AFTER INSERT ON work_schedule
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Work schedule created. <br/>';

    IF NEW.work_schedule_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Work Schedule Name: ", NEW.work_schedule_name);
    END IF;

    IF NEW.work_schedule_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Work Schedule Description: ", NEW.work_schedule_description);
    END IF;

    IF NEW.work_schedule_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Work Schedule Type ID: ", NEW.work_schedule_type_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('work_schedule', NEW.work_schedule_id, audit_log, NEW.last_log_by, NOW());
END //

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

CREATE PROCEDURE generateWorkScheduleTable(IN p_work_schedule_type_id INT)
BEGIN
    DECLARE query VARCHAR(1000);
    DECLARE conditionList VARCHAR(500);

    SET query = 'SELECT work_schedule_id, work_schedule_name, work_schedule_description, work_schedule_type_id FROM work_schedule';
    
    SET conditionList = ' WHERE 1';

    IF p_work_schedule_type_id <> "" THEN
        SET conditionList = CONCAT(conditionList, ' AND work_schedule_type_id = ');
        SET conditionList = CONCAT(conditionList, QUOTE(p_work_schedule_type_id));
    END IF;

    SET query = CONCAT(query, conditionList);

    SET query = CONCAT(query, ' ORDER BY work_schedule_name;');

    PREPARE stmt FROM query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generateWorkScheduleOptions()
BEGIN
	SELECT work_schedule_id, work_schedule_name FROM work_schedule
	ORDER BY work_schedule_name;
END //

/* Work hours table */
CREATE TABLE work_hours (
    work_hours_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    work_schedule_id INT UNSIGNED,
    work_date DATE,
    day_of_week VARCHAR(15),
    day_period VARCHAR(15),
    start_time TIME,
    end_time TIME,
    notes TEXT,
    last_log_by INT NOT NULL
);
 
CREATE INDEX work_hours_index_work_hours_id ON work_hours(work_hours_id);
CREATE INDEX work_hours_index_work_schedule_id ON work_hours(work_schedule_id);

ALTER TABLE work_hours
ADD FOREIGN KEY (work_schedule_id) REFERENCES work_schedule(work_schedule_id);

CREATE TRIGGER work_hours_trigger_update
AFTER UPDATE ON work_hours
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.work_date <> OLD.work_date THEN
        SET audit_log = CONCAT(audit_log, "Work Date: ", OLD.work_date, " -> ", NEW.work_date, "<br/>");
    END IF;

    IF NEW.day_of_week <> OLD.day_of_week THEN
        SET audit_log = CONCAT(audit_log, "Day of Week: ", OLD.day_of_week, " -> ", NEW.day_of_week, "<br/>");
    END IF;

    IF NEW.day_period <> OLD.day_period THEN
        SET audit_log = CONCAT(audit_log, "Day of Period: ", OLD.day_period, " -> ", NEW.day_period, "<br/>");
    END IF;

    IF NEW.start_time <> OLD.start_time THEN
        SET audit_log = CONCAT(audit_log, "Start Time: ", OLD.start_time, " -> ", NEW.start_time, "<br/>");
    END IF;

    IF NEW.end_time <> OLD.end_time THEN
        SET audit_log = CONCAT(audit_log, "End Time: ", OLD.end_time, " -> ", NEW.end_time, "<br/>");
    END IF;

    IF NEW.notes <> OLD.notes THEN
        SET audit_log = CONCAT(audit_log, "Notes: ", OLD.notes, " -> ", NEW.notes, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('work_hours', NEW.work_hours_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER work_hours_trigger_insert
AFTER INSERT ON work_hours
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Work hours created. <br/>';

    IF NEW.work_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Work Date: ", NEW.work_date);
    END IF;

    IF NEW.day_of_week <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Day of Week: ", NEW.day_of_week);
    END IF;

    IF NEW.day_period <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Day Period: ", NEW.day_period);
    END IF;

    IF NEW.start_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Time: ", NEW.start_time);
    END IF;

    IF NEW.end_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Time: ", NEW.end_time);
    END IF;

    IF NEW.notes <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Notes: ", NEW.notes);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('work_hours', NEW.work_hours_id, audit_log, NEW.last_log_by, NOW());
END //

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

CREATE PROCEDURE checkFixedWorkHoursOverlap(IN p_work_hours_id INT, IN p_work_schedule_id INT, IN p_day_of_week VARCHAR(15), IN p_start_time TIME, IN p_end_time TIME)
BEGIN
    IF p_work_hours_id IS NOT NULL OR p_work_hours_id <> '' THEN
        SELECT COUNT(*) AS total
        FROM work_hours
        WHERE work_hours_id != p_work_hours_id
        AND work_schedule_id = p_work_schedule_id
        AND day_of_week = p_day_of_week
        AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
    ELSE
        SELECT COUNT(*) AS total
        FROM work_hours
        WHERE work_hours_id != p_work_hours_id
        AND day_of_week = p_day_of_week
        AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
    END IF;
END //

CREATE PROCEDURE checkFlexibleWorkHoursOverlap(IN p_work_hours_id INT, IN p_work_schedule_id INT, IN p_work_date DATE, IN p_start_time TIME, IN p_end_time TIME)
BEGIN
    IF p_work_hours_id IS NOT NULL OR p_work_hours_id <> '' THEN
        SELECT COUNT(*) AS total
        FROM work_hours
        WHERE work_hours_id != p_work_hours_id
        AND work_schedule_id = p_work_schedule_id
        AND work_date = p_work_date
        AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
    ELSE
        SELECT COUNT(*) AS total
        FROM work_hours
        WHERE work_hours_id != p_work_hours_id
        AND work_date = p_work_date
        AND (start_time BETWEEN p_start_time AND p_end_time OR end_time BETWEEN p_start_time AND p_end_time);
    END IF;
END //

/* Bank account table */
CREATE TABLE bank_account_type(
	bank_account_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	bank_account_type_name VARCHAR(100) NOT NULL,
    last_log_by INT NOT NULL
);

CREATE INDEX bank_account_type_index_bank_account_type_id ON bank_account_type(bank_account_type_id);

CREATE TRIGGER bank_account_type_trigger_update
AFTER UPDATE ON bank_account_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.bank_account_type_name <> OLD.bank_account_type_name THEN
        SET audit_log = CONCAT(audit_log, "Bank Account Type Name: ", OLD.bank_account_type_name, " -> ", NEW.bank_account_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('bank_account_type', NEW.bank_account_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER bank_account_type_trigger_insert
AFTER INSERT ON bank_account_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Bank account created. <br/>';

    IF NEW.bank_account_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Account Type Name: ", NEW.bank_account_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('bank_account_type', NEW.bank_account_type_id, audit_log, NEW.last_log_by, NOW());
END //

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

/* Contact table */
CREATE TABLE contact(
	contact_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	user_id INT UNSIGNED,
	file_as VARCHAR(1000) NOT NULL,
    is_employee TINYINT(1),
    is_applicant TINYINT(1),
    is_customer TINYINT(1),
    last_log_by INT NOT NULL
);

CREATE INDEX contact_index_contact_id ON contact(contact_id);
CREATE INDEX contact_index_user_id ON contact(user_id);

CREATE TRIGGER contact_trigger_update
AFTER UPDATE ON contact
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.user_id <> OLD.user_id THEN
        SET audit_log = CONCAT(audit_log, "User ID: ", OLD.user_id, " -> ", NEW.user_id, "<br/>");
    END IF;

    IF NEW.file_as <> OLD.file_as THEN
        SET audit_log = CONCAT(audit_log, "File As: ", OLD.file_as, " -> ", NEW.file_as, "<br/>");
    END IF;

    IF NEW.is_employee <> OLD.is_employee THEN
        SET audit_log = CONCAT(audit_log, "Is Employee: ", OLD.is_employee, " -> ", NEW.is_employee, "<br/>");
    END IF;

    IF NEW.is_applicant <> OLD.is_applicant THEN
        SET audit_log = CONCAT(audit_log, "Is Applicant: ", OLD.is_applicant, " -> ", NEW.is_applicant, "<br/>");
    END IF;

    IF NEW.is_customer <> OLD.is_customer THEN
        SET audit_log = CONCAT(audit_log, "Is Customer: ", OLD.is_customer, " -> ", NEW.is_customer, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact', NEW.contact_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_trigger_insert
AFTER INSERT ON contact
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact created. <br/>';

    IF NEW.user_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>User ID: ", NEW.user_id);
    END IF;

    IF NEW.file_as <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File As: ", NEW.file_as);
    END IF;

    IF NEW.is_employee <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Is Employee: ", NEW.is_employee);
    END IF;

    IF NEW.is_applicant <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Is Applicant: ", NEW.is_applicant);
    END IF;

    IF NEW.is_customer <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Is Customer: ", NEW.is_customer);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact', NEW.contact_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TABLE contact_personal_information(
	contact_id INT UNSIGNED PRIMARY KEY NOT NULL,
	contact_image VARCHAR(500),
	contact_signature VARCHAR(500),
    first_name VARCHAR(300) NOT NULL,
	middle_name VARCHAR(300),
	last_name VARCHAR(300) NOT NULL,
	suffix VARCHAR(10),
	nickname VARCHAR(100),
	bio VARCHAR(1000),
    civil_status_id INT UNSIGNED,
    gender_id INT UNSIGNED,
    religion_id INT UNSIGNED,
    blood_type_id INT UNSIGNED,
    birthday DATE NOT NULL,
    birth_place VARCHAR(1000),
    height DOUBLE,
    weight DOUBLE,
    last_log_by INT NOT NULL
);

ALTER TABLE contact_personal_information
ADD FOREIGN KEY (contact_id) REFERENCES contact(contact_id);

CREATE INDEX contact_personal_information_index_contact_id ON contact_personal_information(contact_id);
CREATE INDEX contact_personal_information_index_civil_status_id ON contact_personal_information(civil_status_id);
CREATE INDEX contact_personal_information_index_gender_id ON contact_personal_information(gender_id);
CREATE INDEX contact_personal_information_index_religion_id ON contact_personal_information(religion_id);
CREATE INDEX contact_personal_information_index_blood_type_id ON contact_personal_information(blood_type_id);

CREATE TRIGGER contact_personal_information_trigger_update
AFTER UPDATE ON contact_personal_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.first_name <> OLD.first_name THEN
        SET audit_log = CONCAT(audit_log, "First Name: ", OLD.first_name, " -> ", NEW.first_name, "<br/>");
    END IF;

    IF NEW.middle_name <> OLD.middle_name THEN
        SET audit_log = CONCAT(audit_log, "Middle Name: ", OLD.middle_name, " -> ", NEW.middle_name, "<br/>");
    END IF;

    IF NEW.last_name <> OLD.last_name THEN
        SET audit_log = CONCAT(audit_log, "Last Name: ", OLD.last_name, " -> ", NEW.last_name, "<br/>");
    END IF;

    IF NEW.suffix <> OLD.suffix THEN
        SET audit_log = CONCAT(audit_log, "Suffix: ", OLD.suffix, " -> ", NEW.suffix, "<br/>");
    END IF;

    IF NEW.nickname <> OLD.nickname THEN
        SET audit_log = CONCAT(audit_log, "Nickname: ", OLD.nickname, " -> ", NEW.nickname, "<br/>");
    END IF;

    IF NEW.bio <> OLD.bio THEN
        SET audit_log = CONCAT(audit_log, "Bio: ", OLD.bio, " -> ", NEW.bio, "<br/>");
    END IF;

    IF NEW.civil_status_id <> OLD.civil_status_id THEN
        SET audit_log = CONCAT(audit_log, "Civil Status ID: ", OLD.civil_status_id, " -> ", NEW.civil_status_id, "<br/>");
    END IF;

    IF NEW.gender_id <> OLD.gender_id THEN
        SET audit_log = CONCAT(audit_log, "Gender ID: ", OLD.gender_id, " -> ", NEW.gender_id, "<br/>");
    END IF;
    
    IF NEW.religion_id <> OLD.religion_id THEN
        SET audit_log = CONCAT(audit_log, "Religion ID: ", OLD.religion_id, " -> ", NEW.religion_id, "<br/>");
    END IF;
    
    IF NEW.blood_type_id <> OLD.blood_type_id THEN
        SET audit_log = CONCAT(audit_log, "Blood Type ID: ", OLD.blood_type_id, " -> ", NEW.blood_type_id, "<br/>");
    END IF;
    
    IF NEW.birthday <> OLD.birthday THEN
        SET audit_log = CONCAT(audit_log, "Birthday: ", OLD.birthday, " -> ", NEW.birthday, "<br/>");
    END IF;
    
    IF NEW.birth_place <> OLD.birth_place THEN
        SET audit_log = CONCAT(audit_log, "Birth Place: ", OLD.birth_place, " -> ", NEW.birth_place, "<br/>");
    END IF;
    
    IF NEW.height <> OLD.height THEN
        SET audit_log = CONCAT(audit_log, "Height: ", OLD.height, " -> ", NEW.height, "<br/>");
    END IF;
    
    IF NEW.weight <> OLD.weight THEN
        SET audit_log = CONCAT(audit_log, "Weight: ", OLD.weight, " -> ", NEW.weight, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact', NEW.contact_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_personal_information_trigger_insert
AFTER INSERT ON contact_personal_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact personal information created. <br/>';

    IF NEW.first_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>First Name: ", NEW.first_name);
    END IF;

    IF NEW.middle_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Middle Name: ", NEW.middle_name);
    END IF;

    IF NEW.last_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Name: ", NEW.last_name);
    END IF;

    IF NEW.suffix <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Suffix: ", NEW.suffix);
    END IF;

    IF NEW.nickname <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Nickname: ", NEW.nickname);
    END IF;

    IF NEW.bio <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bio: ", NEW.bio);
    END IF;

    IF NEW.civil_status_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Civil Status: ", NEW.civil_status_id);
    END IF;

    IF NEW.gender_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Gender ID: ", NEW.gender_id);
    END IF;

    IF NEW.religion_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Religion ID: ", NEW.religion_id);
    END IF;

    IF NEW.blood_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Blood Type ID: ", NEW.blood_type_id);
    END IF;

    IF NEW.birthday <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Birthday: ", NEW.birthday);
    END IF;

    IF NEW.birth_place <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Birth Place: ", NEW.birth_place);
    END IF;

    IF NEW.height <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Height: ", NEW.height);
    END IF;

    IF NEW.weight <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Weight: ", NEW.weight);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact', NEW.contact_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TABLE contact_employment_information(
	contact_id INT UNSIGNED PRIMARY KEY NOT NULL,
	badge_id VARCHAR(500) NOT NULL,
    employee_type_id INT UNSIGNED,
	department_id INT UNSIGNED,
	job_position_id INT UNSIGNED,
	job_level_id INT UNSIGNED,
	branch_id INT UNSIGNED,
	employee_status TINYINT(1) NOT NULL,
    permanency_date DATE,
    onboard_date DATE,
    offboard_date DATE,
    departure_reason_id INT UNSIGNED,
    detailed_departure_reason VARCHAR(5000),
    last_log_by INT NOT NULL
);

ALTER TABLE contact_employment_information
ADD FOREIGN KEY (contact_id) REFERENCES contact(contact_id);

CREATE INDEX contact_employment_information_index_contact_id ON contact_employment_information(contact_id);
CREATE INDEX contact_employment_information_index_employee_type_id ON contact_employment_information(employee_type_id);
CREATE INDEX contact_employment_information_index_department_id ON contact_employment_information(department_id);
CREATE INDEX contact_employment_information_index_job_position_id ON contact_employment_information(job_position_id);
CREATE INDEX contact_employment_information_index_job_level_id ON contact_employment_information(job_level_id);
CREATE INDEX contact_employment_information_index_branch_id ON contact_employment_information(branch_id);
CREATE INDEX contact_employment_information_index_departure_reason_id ON contact_employment_information(departure_reason_id);

CREATE TRIGGER contact_employment_information_trigger_update
AFTER UPDATE ON contact_employment_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.badge_id <> OLD.badge_id THEN
        SET audit_log = CONCAT(audit_log, "Badge ID: ", OLD.badge_id, " -> ", NEW.badge_id, "<br/>");
    END IF;

    IF NEW.employee_type_id <> OLD.employee_type_id THEN
        SET audit_log = CONCAT(audit_log, "Employee Type ID: ", OLD.employee_type_id, " -> ", NEW.employee_type_id, "<br/>");
    END IF;

    IF NEW.department_id <> OLD.department_id THEN
        SET audit_log = CONCAT(audit_log, "Department ID: ", OLD.department_id, " -> ", NEW.department_id, "<br/>");
    END IF;

    IF NEW.job_position_id <> OLD.job_position_id THEN
        SET audit_log = CONCAT(audit_log, "Job Position ID: ", OLD.job_position_id, " -> ", NEW.job_position_id, "<br/>");
    END IF;

    IF NEW.job_level_id <> OLD.job_level_id THEN
        SET audit_log = CONCAT(audit_log, "Job Level ID: ", OLD.job_level_id, " -> ", NEW.job_level_id, "<br/>");
    END IF;

    IF NEW.branch_id <> OLD.branch_id THEN
        SET audit_log = CONCAT(audit_log, "Branch ID: ", OLD.branch_id, " -> ", NEW.branch_id, "<br/>");
    END IF;

    IF NEW.employee_status <> OLD.employee_status THEN
        SET audit_log = CONCAT(audit_log, "Employee Status ID: ", OLD.employee_status, " -> ", NEW.employee_status, "<br/>");
    END IF;

    IF NEW.permanency_date <> OLD.permanency_date THEN
        SET audit_log = CONCAT(audit_log, "Permanency Date: ", OLD.permanency_date, " -> ", NEW.permanency_date, "<br/>");
    END IF;
    
    IF NEW.onboard_date <> OLD.onboard_date THEN
        SET audit_log = CONCAT(audit_log, "On Board Date: ", OLD.onboard_date, " -> ", NEW.onboard_date, "<br/>");
    END IF;
    
    IF NEW.offboard_date <> OLD.offboard_date THEN
        SET audit_log = CONCAT(audit_log, "Off Board Date: ", OLD.offboard_date, " -> ", NEW.offboard_date, "<br/>");
    END IF;
    
    IF NEW.departure_reason_id <> OLD.departure_reason_id THEN
        SET audit_log = CONCAT(audit_log, "Departure Reason ID: ", OLD.departure_reason_id, " -> ", NEW.departure_reason_id, "<br/>");
    END IF;
    
    IF NEW.detailed_departure_reason <> OLD.detailed_departure_reason THEN
        SET audit_log = CONCAT(audit_log, "Detailed Departure Reason: ", OLD.detailed_departure_reason, " -> ", NEW.detailed_departure_reason, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact', NEW.contact_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_employment_information_trigger_insert
AFTER INSERT ON contact_employment_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact employement information created. <br/>';

    IF NEW.badge_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Badge ID: ", NEW.badge_id);
    END IF;

    IF NEW.employee_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Employee Type ID: ", NEW.employee_type_id);
    END IF;

    IF NEW.department_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Department ID: ", NEW.department_id);
    END IF;

    IF NEW.job_position_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Job Position ID: ", NEW.job_position_id);
    END IF;

    IF NEW.job_level_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Job Level ID: ", NEW.job_level_id);
    END IF;

    IF NEW.branch_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Branch ID: ", NEW.branch_id);
    END IF;

    IF NEW.employee_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Employee Status: ", NEW.employee_status);
    END IF;

    IF NEW.permanency_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Permanency Date: ", NEW.permanency_date);
    END IF;

    IF NEW.onboard_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>On Board Date: ", NEW.onboard_date);
    END IF;

    IF NEW.offboard_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Off Board Date: ", NEW.offboard_date);
    END IF;

    IF NEW.departure_reason_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Departure Reason ID: ", NEW.departure_reason_id);
    END IF;

    IF NEW.detailed_departure_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Details Departure Reason: ", NEW.detailed_departure_reason);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact', NEW.contact_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE insertEmployee(IN p_file_as VARCHAR(1000), IN p_last_log_by INT, OUT p_contact_id INT)
BEGIN
    INSERT INTO contact (file_as, is_employee, last_log_by) 
	VALUES(p_file_as, 1, p_last_log_by);
	
    SET p_contact_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE insertEmployeePersonalInformation(IN p_contact_id INT, IN p_first_name VARCHAR(300), IN p_middle_name VARCHAR(300), IN p_last_name VARCHAR(300), IN p_suffix VARCHAR(10), IN p_last_log_by INT)
BEGIN
    INSERT INTO contact_personal_information (contact_id, first_name, middle_name, last_name, suffix, last_log_by) 
	VALUES(p_contact_id, p_first_name, p_middle_name, p_last_name, p_suffix, p_last_log_by);
END //

CREATE PROCEDURE getEmployeePersonalInformation(IN p_contact_id INT)
BEGIN
	SELECT * FROM contact_personal_information
    WHERE contact_id = p_contact_id;
END //