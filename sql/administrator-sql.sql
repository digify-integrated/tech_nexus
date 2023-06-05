/* User table */
CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    file_as VARCHAR(300) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    company_id INT NOT NULL,
    password_expiry_date DATE NOT NULL,
    is_locked TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_failed_login_attempt DATETIME,
    failed_login_attempts INT NOT NULL DEFAULT 0,
    last_connection_date DATETIME,
    last_ip_address VARCHAR(45),
    last_location VARCHAR(255),
    registration_date DATETIME NOT NULL,
    verification_token VARCHAR(255),
    reset_token VARCHAR(255),
    password_reset_expiration DATETIME,
    two_factor_auth TINYINT(1) NOT NULL DEFAULT 0,
    otp VARCHAR(255),
    otp_expiry_date DATETIME,
    failed_otp_attempts INT NOT NULL DEFAULT 0,
    last_password_change DATETIME,
    account_lock_duration INT NOT NULL DEFAULT 0,
    email_verification_status TINYINT(1) NOT NULL DEFAULT 0,
    email_verified_at DATETIME,
    last_password_reset DATETIME,
    remember_token VARCHAR(255),
    activation_token VARCHAR(255),
    last_log_by INT(10) NOT NULL
);

CREATE INDEX users_index_user_id ON users(user_id);
CREATE INDEX users_index_email ON users(email);

INSERT INTO users (file_as, email, password, company_id, password_expiry_date, is_locked, is_active, registration_date, two_factor_auth, email_verification_status, last_log_by) VALUES ('Administrator', 'ldagulto@encorefinancials.com', '$2y$10$M3fsHaJP9bxY84ox5QSoA./iNmLcg3V.5TtASgcAFbiEPK92uv2vC', '0', '2022-12-30', '0', '1', '2022-12-30', '1', '1', '1');

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

    IF NEW.password_expiry_date <> OLD.password_expiry_date THEN
        SET audit_log = CONCAT(audit_log, "Password Expiry Date: ", OLD.password_expiry_date, " -> ", NEW.password_expiry_date, "<br/>");
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

    IF NEW.last_ip_address <> OLD.last_ip_address THEN
        SET audit_log = CONCAT(audit_log, "Last IP Address: ", OLD.last_ip_address, " -> ", NEW.last_ip_address, "<br/>");
    END IF;

    IF NEW.last_location <> OLD.last_location THEN
        SET audit_log = CONCAT(audit_log, "Last Location: ", OLD.last_location, " -> ", NEW.last_location, "<br/>");
    END IF;

    IF NEW.registration_date <> OLD.registration_date THEN
        SET audit_log = CONCAT(audit_log, "Registration Date: ", OLD.registration_date, " -> ", NEW.registration_date, "<br/>");
    END IF;

    IF NEW.verification_token <> OLD.verification_token THEN
        SET audit_log = CONCAT(audit_log, "Verification Token: ", OLD.verification_token, " -> ", NEW.verification_token, "<br/>");
    END IF;

    IF NEW.reset_token <> OLD.reset_token THEN
        SET audit_log = CONCAT(audit_log, "Reset Token: ", OLD.reset_token, " -> ", NEW.reset_token, "<br/>");
    END IF;

    IF NEW.password_reset_expiration <> OLD.password_reset_expiration THEN
        SET audit_log = CONCAT(audit_log, "Password Reset Expiration: ", OLD.password_reset_expiration, " -> ", NEW.password_reset_expiration, "<br/>");
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

    IF NEW.email_verification_status <> OLD.email_verification_status THEN
        SET audit_log = CONCAT(audit_log, "Email Verification Status: ", OLD.email_verification_status, " -> ", NEW.email_verification_status, "<br/>");
    END IF;

    IF NEW.email_verified_at <> OLD.email_verified_at THEN
        SET audit_log = CONCAT(audit_log, "Email Verification At: ", OLD.email_verified_at, " -> ", NEW.email_verified_at, "<br/>");
    END IF;

    IF NEW.last_password_reset <> OLD.last_password_reset THEN
        SET audit_log = CONCAT(audit_log, "Last Password Reset: ", OLD.last_password_reset, " -> ", NEW.last_password_reset, "<br/>");
    END IF;

    IF NEW.remember_token <> OLD.remember_token THEN
        SET audit_log = CONCAT(audit_log, "Remember Token: ", OLD.remember_token, " -> ", NEW.remember_token, "<br/>");
    END IF;

    IF NEW.activation_token <> OLD.activation_token THEN
        SET audit_log = CONCAT(audit_log, "Activation Token: ", OLD.activation_token, " -> ", NEW.activation_token, "<br/>");
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

    IF NEW.password_expiry_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Password Expiry Date: ", NEW.password_expiry_date);
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

    IF NEW.last_ip_address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last IP Address: ", NEW.last_ip_address);
    END IF;

    IF NEW.last_location <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Location: ", NEW.last_location);
    END IF;

    IF NEW.registration_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Registration Date: ", NEW.registration_date);
    END IF;

    IF NEW.verification_token <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Verification Token: ", NEW.verification_token);
    END IF;

    IF NEW.reset_token <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reset Token: ", NEW.reset_token);
    END IF;

    IF NEW.password_reset_expiration <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Password Reset Expiration: ", NEW.password_reset_expiration);
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

    IF NEW.email_verification_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Verification Status: ", NEW.email_verification_status);
    END IF;

    IF NEW.email_verified_at <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Verification At: ", NEW.email_verified_at);
    END IF;

    IF NEW.last_password_reset <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Password Reset: ", NEW.last_password_reset);
    END IF;

    IF NEW.remember_token <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remember Token: ", NEW.remember_token);
    END IF;

    IF NEW.activation_token <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Activation Token: ", NEW.activation_token);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('users', NEW.user_id, audit_log, NEW.last_log_by, NOW());
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

CREATE PROCEDURE updateLastConnection(IN p_user_id INT, IN p_last_ip_address VARCHAR(45), IN p_last_location VARCHAR(255), IN p_last_connection_date DATETIME)
BEGIN
	UPDATE users 
    SET last_ip_address = last_ip_address, last_location = p_last_location, last_connection_date = p_last_connection_date
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateRememberToken(IN p_user_id INT, IN p_remember_token VARCHAR(255))
BEGIN
	UPDATE users 
    SET remember_token = p_remember_token
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE updateOTP(IN p_user_id INT, IN p_otp VARCHAR(255), IN otp_expiry_date DATETIME)
BEGIN
	UPDATE users 
    SET otp = p_otp, otp_expiry_date = p_otp_expiry_date
    WHERE user_id = p_user_id;
END //

/* Audit log table */
CREATE TABLE audit_log (
    audit_log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    table_name VARCHAR(255) NOT NULL,
    reference_id int(10) NOT NULL,
    log TEXT NOT NULL,
    changed_by VARCHAR(255) NOT NULL,
    changed_at datetime NOT NULL
);

CREATE INDEX audit_log_index_external_id ON audit_log(audit_log_id);
CREATE INDEX audit_log_index_table_name ON audit_log(table_name);
CREATE INDEX audit_log_index_reference_id ON audit_log(reference_id);