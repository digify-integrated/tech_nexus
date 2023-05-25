/* Audit log table */
CREATE TABLE audit_log (
  audit_log_id int(50) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  table_name varchar(255) NOT NULL,
  reference_id int(10) NOT NULL,
  log TEXT NOT NULL,
  changed_by varchar(255) NOT NULL,
  changed_at datetime NOT NULL
);

CREATE INDEX audit_log_index_audit_log_id ON audit_log(audit_log_id);
CREATE INDEX audit_log_index_table_name ON audit_log(table_name);
CREATE INDEX audit_log_index_reference_id ON audit_log(reference_id);

/* Users table */
CREATE TABLE users (
    user_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
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

CREATE INDEX users_index_user_id ON users(user_id);
CREATE INDEX users_index_email_address ON users(email_address);
CREATE INDEX users_index_user_status ON users(user_status);
CREATE INDEX users_index_password_expiry_date ON users(password_expiry_date);
CREATE INDEX users_index_last_connection_date ON users(last_connection_date);

INSERT INTO users (email_address, password, file_as, user_status, password_expiry_date, failed_login, last_log_by) VALUES ('admin@encorefinancials.com', 'W5hvx4P278F8q50uZe2YFif%2ByRDeSeNaainzl5K9%2BQM%3D', 'Administrator', 'Active', '2022-12-30', 0, 1);
INSERT INTO users (email_address, password, file_as, user_status, password_expiry_date, failed_login, last_log_by) VALUES ('ldagulto@encorefinancials.com', 'W5hvx4P278F8q50uZe2YFif%2ByRDeSeNaainzl5K9%2BQM%3D', 'Administrator', 'Active', '2022-12-30', 0, 1);
INSERT INTO users (email_address, password, file_as, user_status, password_expiry_date, failed_login, last_log_by) VALUES ('lmicayas@encorefinancials.com', 'W5hvx4P278F8q50uZe2YFif%2ByRDeSeNaainzl5K9%2BQM%3D', 'Administrator', 'Active', '2022-12-30', 0, 1);

CREATE TRIGGER users_trigger_update
AFTER UPDATE ON users
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
        VALUES ('users', NEW.user_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER users_trigger_insert
AFTER INSERT ON users
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
    VALUES ('users', NEW.user_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_user_exist(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT COUNT(*) AS total
    FROM users
    WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE get_user_details(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
	SELECT user_id, email_address, password, file_as, user_status, password_expiry_date, failed_login, last_failed_login, last_connection_date, last_log_by
	FROM users 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE update_user_login_attempt(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_login_attempt TINYINT(1), IN p_last_failed_attempt_date DATETIME)
BEGIN
    IF p_login_attempt > 0 THEN
        UPDATE users
        SET failed_login = p_login_attempt,
            last_failed_login = p_last_failed_attempt_date
        WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE users
        SET failed_login = p_login_attempt
        WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    END IF;
END//

CREATE PROCEDURE update_user_last_connection(IN p_user_id INT(10), IN p_email_address VARCHAR(100), p_last_connection_date DATETIME)
BEGIN
	UPDATE users 
	SET last_connection_date = p_last_connection_date
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE update_user_password(IN p_user_id INT(10), IN p_email_address VARCHAR(100), p_password VARCHAR(500), p_password_expiry_date DATE)
BEGIN
	UPDATE users 
	SET PASSWORD = p_password, PASSWORD_EXPIRY_DATE = p_password_expiry_date
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE update_user(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_password VARCHAR(500), IN p_file_as VARCHAR (300), IN p_password_expiry_date DATE)
BEGIN
	IF p_password IS NOT NULL AND p_password <> '' THEN
        UPDATE users
        SET file_as = p_file_as, password = p_password, password_expiry_date = p_password_expiry_date
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE users
        SET file_as = p_file_as
      	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    END IF;
END //

CREATE PROCEDURE insert_user(IN p_email_address VARCHAR(100), IN p_password VARCHAR(500), IN p_file_as VARCHAR (300), IN p_password_expiry_date DATE)
BEGIN
	INSERT INTO users (email_address, password, file_as, user_status, password_expiry_date, failed_login) 
	VALUES(p_email_address, p_password, p_file_as, "Inactive", p_password_expiry_date, 0);
END //

CREATE PROCEDURE delete_user(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
	DELETE FROM users 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

/* Password history table */
CREATE TABLE password_history (
    password_history_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT(10) UNSIGNED NOT NULL,
    email_address VARCHAR(100) NOT NULL,
    password VARCHAR(500) NOT NULL,
    password_change_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE password_history
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE password_history
ADD FOREIGN KEY (email_address) REFERENCES users(email_address);

CREATE INDEX password_history_index_password_history_id ON password_history(password_history_id);
CREATE INDEX password_history_index_user_id ON password_history(user_id);
CREATE INDEX password_history_index_email_address ON password_history(email_address);

CREATE PROCEDURE insert_password_history(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_password VARCHAR(500))
BEGIN
	INSERT INTO password_history (user_id, email_address, password) 
	VALUES(p_user_id, p_email_address, p_password);
END //

CREATE PROCEDURE get_user_password_history_details(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT password 
	FROM password_history 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

/* UI customization setting table */
CREATE TABLE ui_customization_setting (
    ui_customization_setting_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT(10) UNSIGNED NOT NULL,
    email_address VARCHAR(100) NOT NULL,
    theme_contrast VARCHAR(15),
    caption_show VARCHAR(15),
    preset_theme VARCHAR(15),
    dark_layout VARCHAR(15),
    rtl_layout VARCHAR(15),
    box_container VARCHAR(15),
    last_log_by INT(10) NOT NULL
);

ALTER TABLE ui_customization_setting
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE ui_customization_setting
ADD FOREIGN KEY (email_address) REFERENCES users(email_address);

CREATE INDEX ui_customization_setting_index_ui_customization_setting_id ON ui_customization_setting(ui_customization_setting_id);
CREATE INDEX ui_customization_setting_index_user_id ON ui_customization_setting(user_id);
CREATE INDEX ui_customization_setting_index_email_address ON ui_customization_setting(email_address);

CREATE TRIGGER ui_customization_setting_trigger_update
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

CREATE TRIGGER ui_customization_setting_trigger_insert
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

CREATE PROCEDURE check_ui_customization_setting_exist(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT COUNT(*) AS total
    FROM ui_customization_setting
    WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE insert_ui_customization_setting(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
BEGIN
	IF p_type = 'theme_contrast' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, theme_contrast, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'caption_show' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, caption_show, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'preset_theme' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, preset_theme, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'dark_layout' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, dark_layout, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'rtl_layout' THEN
        INSERT INTO ui_customization_setting (user_id, email_address, rtl_layout, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSE
        INSERT INTO ui_customization_setting (user_id, email_address, box_container, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    END IF;
END //

CREATE PROCEDURE update_ui_customization_setting(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
BEGIN
	IF p_type = 'theme_contrast' THEN
        UPDATE ui_customization_setting
        SET theme_contrast = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'caption_show' THEN
        UPDATE ui_customization_setting
        SET caption_show = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'preset_theme' THEN
        UPDATE ui_customization_setting
        SET preset_theme = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'dark_layout' THEN
        UPDATE ui_customization_setting
        SET dark_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'rtl_layout' THEN
        UPDATE ui_customization_setting
        SET rtl_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE ui_customization_setting
        SET box_container = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    END IF;
END //

CREATE PROCEDURE get_ui_customization_setting_details(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT ui_customization_setting_id, user_id, email_address, theme_contrast, caption_show, preset_theme, dark_layout, rtl_layout, box_container, last_log_by
	FROM ui_customization_setting 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

/* Role table */
CREATE TABLE role(
	role_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	role_name VARCHAR(100) NOT NULL,
	role_description VARCHAR(200) NOT NULL,
	assignable TINYINT(1) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX role_index_role_id ON role(role_id);

INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Administrator', 'Administrator', '1', '1');
INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Employee', 'Employee', '1', '1');

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

CREATE PROCEDURE get_role_details(IN p_role_id INT(10))
BEGIN
    SELECT role_name, role_description, assignable, last_log_by
	FROM role 
	WHERE role_id = p_role_id;
END //

/* Role users table */
CREATE TABLE role_users(
	role_id INT(10) NOT NULL,
	user_id INT(10) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX role_users_index_role_id ON role_users(role_id);
CREATE INDEX role_users_index_user_id ON role_users(user_id);

INSERT INTO role_users (role_id, user_id, last_log_by) VALUES ('1', '1', '1');

/* Menu groups table */
CREATE TABLE menu_groups (
    menu_group_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    menu_group_name VARCHAR(100) NOT NULL,
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX menu_groups_index_menu_group_id ON menu_groups(menu_group_id);

INSERT INTO menu_groups (menu_group_name, order_sequence, last_log_by) VALUES ('Administration', '1', '1');

CREATE TRIGGER menu_groups_trigger_update
AFTER UPDATE ON menu_groups
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
        VALUES ('menu_groups', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER menu_groups_trigger_insert
AFTER INSERT ON menu_groups
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
    VALUES ('menu_groups', NEW.menu_group_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_menu_groups_exist(IN p_menu_group_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM menu_groups
    WHERE menu_group_id = p_menu_group_id;
END //

CREATE PROCEDURE insert_menu_groups(IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10), OUT p_menu_group_id INT(10))
BEGIN
    INSERT INTO menu_groups (menu_group_name, order_sequence, last_log_by) 
	VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
	
    SET p_menu_group_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE duplicate_menu_groups(IN p_menu_group_id INT(10), IN p_last_log_by INT(10), OUT p_new_menu_group_id INT(10))
BEGIN
    DECLARE p_menu_group_name VARCHAR(255);
    DECLARE p_order_sequence INT(10);
    
    SELECT menu_group_name, order_sequence 
    INTO p_menu_group_name, p_order_sequence 
    FROM menu_groups 
    WHERE menu_group_id = p_menu_group_id;
    
    INSERT INTO menu_groups (menu_group_name, order_sequence, last_log_by) 
    VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
    
    SET p_new_menu_group_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE update_menu_groups(IN p_menu_group_id INT(10), IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10))
BEGIN
	UPDATE menu_groups
        SET menu_group_name = p_menu_group_name,
        order_sequence = p_order_sequence,
        last_log_by = p_last_log_by
       	WHERE menu_group_id = p_menu_group_id;
END //

CREATE PROCEDURE delete_menu_groups(IN p_menu_group_id INT(10))
BEGIN
    DELETE FROM menu_groups WHERE menu_group_id = p_menu_group_id;
END //

CREATE PROCEDURE get_menu_groups_details(IN p_menu_group_id INT(10))
BEGIN
    SELECT menu_group_name, order_sequence, last_log_by
	FROM menu_groups 
	WHERE menu_group_id = p_menu_group_id;
END //

CREATE PROCEDURE build_menu_group(IN p_user_id INT(10))
BEGIN
    SELECT DISTINCT(mg.menu_group_id) as menu_group_id, mg.menu_group_name
    FROM menu_groups mg
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
END //

CREATE PROCEDURE build_menu_item(IN p_user_id INT(10), IN p_menu_group_id INT(10))
BEGIN
    SELECT mi.menu_item_id, mi.menu_item_name, mi.menu_group_id, mi.menu_item_url, mi.parent_id, mi.menu_item_icon
    FROM menu_item AS mi
    INNER JOIN menu_access_right AS mar ON mi.menu_item_id = mar.menu_item_id
    INNER JOIN role_users AS ru ON mar.role_id = ru.role_id
    WHERE mar.read_access = 1 AND ru.user_id = p_user_id AND mi.menu_group_id = p_menu_group_id
    ORDER BY mi.order_sequence;
END //

/* Menu table */
CREATE TABLE menu_item(
	menu_item_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	menu_item_name VARCHAR(100) NOT NULL,
	menu_group_id INT(10) UNSIGNED NOT NULL,
	menu_item_url VARCHAR(50),
	parent_id INT(10) UNSIGNED,
	menu_item_icon VARCHAR(150),
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX menu_item_index_menu_item_id ON menu_item(menu_item_id);

ALTER TABLE menu_item
ADD FOREIGN KEY (menu_group_id) REFERENCES menu_groups(menu_group_id);

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

CREATE PROCEDURE check_menu_item_exist(IN p_menu_item_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM menu_item
    WHERE menu_item_id = p_menu_item_id;
END //

CREATE PROCEDURE insert_menu_item(IN p_menu_item_name VARCHAR(100), IN p_menu_group_id INT(10), IN p_menu_item_url VARCHAR(50), IN p_parent_id INT(10), IN p_menu_item_icon VARCHAR(150), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10), OUT p_menu_item_id INT(10))
BEGIN
    INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) 
	VALUES(p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
	
    SET p_menu_item_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE duplicate_menu_item(IN p_menu_item_id INT(10), IN p_last_log_by INT(10), OUT p_new_menu_item_id INT(10))
BEGIN
    DECLARE p_menu_item_name VARCHAR(255);
    DECLARE p_menu_group_id INT(10);
    DECLARE p_menu_item_url VARCHAR(50);
    DECLARE p_parent_id INT(10);
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

CREATE PROCEDURE update_menu_item(IN p_menu_item_id INT(10), IN p_menu_item_name VARCHAR(100), IN p_menu_group_id INT(10), IN p_menu_item_url VARCHAR(50), IN p_parent_id INT(10), IN p_menu_item_icon VARCHAR(150), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10))
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

CREATE PROCEDURE delete_menu_item(IN p_menu_item_id INT(10))
BEGIN
    DELETE FROM menu_item WHERE menu_item_id = p_menu_item_id;
END //

CREATE PROCEDURE get_menu_item_details(IN p_menu_item_id INT(10))
BEGIN
    SELECT menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by
	FROM menu_item 
	WHERE menu_item_id = p_menu_item_id;
END //

/* Menu access right table */
CREATE TABLE menu_access_right(
	menu_item_id INT(10) NOT NULL,
	role_id INT(10) UNSIGNED NOT NULL,
	read_access TINYINT(1) NOT NULL,
    write_access TINYINT(1) NOT NULL,
    create_access TINYINT(1) NOT NULL,
    delete_access TINYINT(1) NOT NULL,
    last_log_by INT(10) NOT NULL
);

INSERT INTO menu_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, last_log_by) VALUES ('1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, last_log_by) VALUES ('2', '1', '1', '1', '1', '1', '1');

CREATE TRIGGER menu_access_right_update
AFTER UPDATE ON menu_access_right
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
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('menu_access_right', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER menu_item_trigger_insert
AFTER INSERT ON menu_item
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
        SET audit_log = CONCAT(audit_log, "<br/>Delete Access: ", delete_access.menu_item_icon);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('menu_access_right', NEW.menu_item_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_role_menu_access_right_exist(IN p_menu_item_id INT(10), IN p_role_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM menu_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END //

CREATE PROCEDURE insert_role_menu_access_right(IN p_menu_item_id INT(10), IN p_role_id INT(10))
BEGIN
    INSERT INTO menu_access_right (menu_item_id, role_id) 
	VALUES(p_menu_item_id, p_role_id);
END //

CREATE PROCEDURE update_role_menu_access_right(IN p_menu_item_id INT(10), IN p_role_id INT(10), IN p_access_type VARCHAR(10), IN p_access TINYINT(1))
BEGIN
	IF p_access_type = 'read' THEN
        UPDATE menu_access_right
        SET read_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'write' THEN
        UPDATE menu_access_right
        SET write_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'create' THEN
        UPDATE menu_access_right
        SET create_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSE
        UPDATE menu_access_right
        SET delete_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    END IF;
END //

CREATE PROCEDURE check_menu_access_rights(IN p_user_id INT(10), IN p_menu_item_id INT(10), IN p_access_type VARCHAR(10))
BEGIN
	IF p_access_type = 'read' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where read_access = '1' AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'write' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where write_access = '1' AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'create' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where create_access = '1' AND menu_item_id = menu_item_id);
    ELSE
        SELECT COUNT(role_id) AS TOTAL
        FROM role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM menu_access_right where delete_access = '1' AND menu_item_id = menu_item_id);
    END IF;
END //

CREATE PROCEDURE get_role_menu_access_rights(IN p_menu_item_id INT(10), IN p_role_id INT(10))
BEGIN
    SELECT read_access, write_access, create_access, delete_access
    FROM menu_access_right 
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END //

/* System action */

CREATE TABLE system_action(
	system_action_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	system_action_name VARCHAR(100) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX system_action_index_system_action_id ON system_action(system_action_id);

INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Assign Menu Item Role Access', '1');

CREATE TABLE system_action_access_rights(
	system_action_id INT(10) UNSIGNED NOT NULL,
	role_id INT(10) UNSIGNED NOT NULL
);

INSERT INTO system_action_access_rights (system_action_id, role_id) VALUES ('1', '1');

CREATE PROCEDURE check_system_action_access_rights(IN p_user_id INT(10), IN p_system_action_id INT(10))
BEGIN
	SELECT COUNT(role_id) AS TOTAL
    FROM role_users
    WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM system_action_access_rights where system_action_id = p_system_action_id);
END //

/* File types table */
CREATE TABLE file_types (
    file_type_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    file_type_name VARCHAR(100) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX file_types_index_file_type_id ON file_types(file_type_id);

CREATE TRIGGER file_types_trigger_update
AFTER UPDATE ON file_types
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.file_type_name <> OLD.file_type_name THEN
        SET audit_log = CONCAT(audit_log, "File Type Name: ", OLD.file_type_name, " -> ", NEW.file_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('file_types', NEW.file_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER file_types_trigger_insert
AFTER INSERT ON file_types
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'File type created. <br/>';

    IF NEW.file_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>File Type Name: ", NEW.file_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('file_types', NEW.file_type_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_file_types_exist(IN p_file_type_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM file_types
    WHERE file_type_id = p_file_type_id;
END //

CREATE PROCEDURE insert_file_types(IN p_file_type_name VARCHAR(100), IN p_last_log_by INT(10), OUT p_file_type_id INT(10))
BEGIN
    INSERT INTO file_types (file_type_name, last_log_by) 
	VALUES(p_file_type_name, p_last_log_by);
	
    SET p_file_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE duplicate_file_types(IN p_file_type_id INT(10), IN p_last_log_by INT(10), OUT p_new_file_type_id INT(10))
BEGIN
    DECLARE p_file_type_name VARCHAR(255);
    
    SELECT file_type_name 
    INTO p_file_type_name 
    FROM file_types 
    WHERE file_type_id = p_file_type_id;
    
    INSERT INTO file_types (file_type_name, last_log_by) 
    VALUES(p_file_type_name, p_last_log_by);
    
    SET p_new_file_type_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE update_file_types(IN p_file_type_id INT(10), IN p_file_type_name VARCHAR(100), IN p_last_log_by INT(10))
BEGIN
	UPDATE file_types
        SET file_type_name = p_file_type_name,
        last_log_by = p_last_log_by
       	WHERE file_type_id = p_file_type_id;
END //

CREATE PROCEDURE delete_file_types(IN p_file_type_id INT(10))
BEGIN
    DELETE FROM file_types WHERE file_type_id = p_file_type_id;
END //

CREATE PROCEDURE get_file_types_details(IN p_file_type_id INT(10))
BEGIN
    SELECT file_type_name, last_log_by
	FROM file_types 
	WHERE file_type_id = p_file_type_id;
END //