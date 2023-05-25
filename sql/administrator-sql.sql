/* Audit log table */
CREATE TABLE admin_audit_log (
    external_id int(50) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    table_name varchar(255) NOT NULL,
    reference_id int(10) NOT NULL,
    log TEXT NOT NULL,
    changed_by varchar(255) NOT NULL,
    changed_at datetime NOT NULL
);

CREATE INDEX audit_log_index_external_id ON admin_audit_log(external_id);
CREATE INDEX audit_log_index_table_name ON admin_audit_log(table_name);
CREATE INDEX audit_log_index_reference_id ON admin_audit_log(reference_id);

/* Users table */
CREATE TABLE admin_users (
    external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    email_address VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(500) NOT NULL,
    file_as VARCHAR(300) NOT NULL,
    user_status CHAR(10) NOT NULL,
    password_expiry_date DATE NOT NULL,
    failed_login TINYINT(1) NOT NULL DEFAULT 0,
    last_failed_login DATETIME DEFAULT NULL,
    last_connection_date DATETIME DEFAULT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX users_index_external_id ON admin_users(external_id);
CREATE INDEX users_index_email_address ON admin_users(email_address);
CREATE INDEX users_index_user_status ON admin_users(user_status);
CREATE INDEX users_index_password_expiry_date ON admin_users(password_expiry_date);
CREATE INDEX users_index_last_connection_date ON admin_users(last_connection_date);

INSERT INTO admin_users (email_address, password, file_as, user_status, password_expiry_date, failed_login, last_log_by) VALUES ('admin@encorefinancials.com', 'vzyEJGDov2%2F%2BPkgMB9koyEUO6sl4GueUe%2FWC%2Bb%2FSP8Y%3D', 'Administrator', 'Active', '2023-12-30', 0, 1);

CREATE TRIGGER users_trigger_update
AFTER UPDATE ON admin_users
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.user_status <> OLD.user_status THEN
        SET audit_log = CONCAT(audit_log, "User Status: ", OLD.user_status, " -> ", NEW.user_status, "<br/>");
    END IF;

    IF NEW.password_expiry_date <> OLD.password_expiry_date THEN
        SET audit_log = CONCAT(audit_log, "Password Expiry Date: ", OLD.password_expiry_date, " -> ", NEW.password_expiry_date, "<br/>");
    END IF;

    IF NEW.failed_login <> OLD.failed_login THEN
        SET audit_log = CONCAT(audit_log, "Failed Login: ", OLD.failed_login, " -> ", NEW.failed_login, "<br/>");
    END IF;

    IF NEW.last_failed_login <> OLD.last_failed_login THEN
        SET audit_log = CONCAT(audit_log, "Last Failed Login: ", OLD.last_failed_login, " -> ", NEW.last_failed_login, "<br/>");
    END IF;

    IF NEW.last_connection_date <> OLD.last_connection_date THEN
        SET audit_log = CONCAT(audit_log, "Last Connection Date: ", OLD.last_connection_date, " -> ", NEW.last_connection_date, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('admin_users', NEW.external_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER users_trigger_insert
AFTER INSERT ON admin_users
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'User created. <br/>';

    IF NEW.email_address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Email Address: ", NEW.email_address);
    END IF;

    IF NEW.file_as <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File As: ", NEW.file_as);
    END IF;

    IF NEW.user_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>User Status: ", NEW.user_status);
    END IF;

    IF NEW.password_expiry_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Password Expiry Date: ", NEW.password_expiry_date);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('admin_users', NEW.external_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_user_exist(IN p_external_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT COUNT(*) AS total
    FROM admin_users
    WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE get_user_details(IN p_external_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
	SELECT external_id, email_address, password, file_as, user_status, password_expiry_date, failed_login, last_failed_login, last_connection_date, last_log_by
	FROM admin_users 
	WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE update_user_login_attempt(IN p_external_id INT(10), IN p_email_address VARCHAR(100), IN p_login_attempt TINYINT(1), IN p_last_failed_attempt_date DATETIME)
BEGIN
    IF p_login_attempt > 0 THEN
        UPDATE admin_users
        SET failed_login = p_login_attempt,
            last_failed_login = p_last_failed_attempt_date
        WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE admin_users
        SET failed_login = p_login_attempt
        WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
    END IF;
END//

CREATE PROCEDURE update_user_last_connection(IN p_external_id INT(10), IN p_email_address VARCHAR(100), p_last_connection_date DATETIME)
BEGIN
	UPDATE admin_users 
	SET last_connection_date = p_last_connection_date
	WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE update_user_password(IN p_external_id INT(10), IN p_email_address VARCHAR(100), p_password VARCHAR(500), p_password_expiry_date DATE)
BEGIN
	UPDATE admin_users 
	SET PASSWORD = p_password, PASSWORD_EXPIRY_DATE = p_password_expiry_date
	WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE update_user(IN p_external_id INT(10), IN p_email_address VARCHAR(100), IN p_password VARCHAR(500), IN p_file_as VARCHAR (300), IN p_password_expiry_date DATE)
BEGIN
	IF p_password IS NOT NULL AND p_password <> '' THEN
        UPDATE admin_users
        SET file_as = p_file_as, password = p_password, password_expiry_date = p_password_expiry_date
       	WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE admin_users
        SET file_as = p_file_as
      	WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
    END IF;
END //

CREATE PROCEDURE insert_user(IN p_email_address VARCHAR(100), IN p_password VARCHAR(500), IN p_file_as VARCHAR (300), IN p_password_expiry_date DATE)
BEGIN
	INSERT INTO admin_users (email_address, password, file_as, user_status, password_expiry_date, failed_login) 
	VALUES(p_email_address, p_password, p_file_as, "Inactive", p_password_expiry_date, 0);
END //

CREATE PROCEDURE delete_user(IN p_external_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
	DELETE FROM admin_users 
	WHERE external_id = p_external_id OR email_address = BINARY p_email_address;
END //

/* Password history table */
CREATE TABLE admin_password_history (
    external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT(10) UNSIGNED NOT NULL,
    email_address VARCHAR(100) NOT NULL,
    password VARCHAR(500) NOT NULL,
    password_change_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE admin_password_history
ADD FOREIGN KEY (external_id) REFERENCES admin_users(external_id);

ALTER TABLE admin_password_history
ADD FOREIGN KEY (email_address) REFERENCES admin_users(email_address);

CREATE INDEX password_history_index_external_id ON password_history(external_id);
CREATE INDEX password_history_index_user_id ON password_history(user_id);
CREATE INDEX password_history_index_email_address ON password_history(email_address);

CREATE PROCEDURE insert_password_history(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_password VARCHAR(500))
BEGIN
	INSERT INTO admin_password_history (user_id, email_address, password) 
	VALUES(p_user_id, p_email_address, p_password);
END //

CREATE PROCEDURE get_user_password_history_details(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT password 
	FROM admin_password_history 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

/* Role table */
CREATE TABLE admin_role(
	external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	role_name VARCHAR(100) NOT NULL,
	role_description VARCHAR(200) NOT NULL,
	assignable TINYINT(1) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX role_index_external_id ON admin_role(external_id);

INSERT INTO admin_role (role_name, role_description, assignable, last_log_by) VALUES ('Administrator', 'Administrator', '1', '1');
INSERT INTO admin_role (role_name, role_description, assignable, last_log_by) VALUES ('Employee', 'Employee', '1', '1');

CREATE TRIGGER role_trigger_update
AFTER UPDATE ON admin_role
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
        VALUES ('admin_role', NEW.external_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER role_trigger_insert
AFTER INSERT ON admin_role
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
    VALUES ('admin_role', NEW.external_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE get_role_details(IN p_external_id INT(10))
BEGIN
    SELECT role_name, role_description, assignable, last_log_by
	FROM admin_role 
	WHERE external_id = p_external_id;
END //

/* Role users table */
CREATE TABLE admin_role_users(
	role_id INT(10) UNSIGNED NOT NULL,
	user_id INT(10) NOT NULL
);

ALTER TABLE admin_role_users
ADD FOREIGN KEY (role_id) REFERENCES admin_role(external_id);

CREATE INDEX role_users_index_external_id ON admin_role_users(role_id);
CREATE INDEX role_users_index_user_id ON admin_role_users(user_id);

INSERT INTO admin_role_users (external_id, user_id) VALUES ('1', '1');

/* Subscription Key */
CREATE TABLE admin_subscription_key (
    external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    subscription_key VARCHAR(500) NOT NULL,
    subscription_type VARCHAR(500) NOT NULL,
    expiration_date VARCHAR(500) NOT NULL,
    created_on DATETIME DEFAULT CURRENT_TIMESTAMP 
);

CREATE INDEX admin_subscription_key_index_external_id ON admin_subscription_key(external_id);