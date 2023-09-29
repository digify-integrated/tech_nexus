/* Users Table */

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
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX users_index_user_id ON users(user_id);
CREATE INDEX users_index_email ON users(email);

INSERT INTO users (file_as, email, password, is_locked, is_active, password_expiry_date, two_factor_auth, last_log_by) VALUES ('Nexus Bot', 'nexus@encorefinancials.com', 'RYHObc8sNwIxdPDNJwCsO8bXKZJXYx7RjTgEWMC17FY%3D', '0', '1', '2023-12-30', '0', '1');
INSERT INTO users (file_as, email, password, is_locked, is_active, password_expiry_date, two_factor_auth, last_log_by) VALUES ('Administrator', 'ldagulto@encorefinancials.com', 'RYHObc8sNwIxdPDNJwCsO8bXKZJXYx7RjTgEWMC17FY%3D', '0', '1', '2023-12-30', '0', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Password History Table */

CREATE TABLE password_history (
    password_history_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    password_change_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE INDEX password_history_index_password_history_id ON password_history(password_history_id);
CREATE INDEX password_history_index_user_id ON password_history(user_id);
CREATE INDEX password_history_index_email ON password_history(email);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Audit Log Table */

CREATE TABLE audit_log (
    audit_log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    table_name VARCHAR(255) NOT NULL,
    reference_id INT NOT NULL,
    log TEXT NOT NULL,
    changed_by INT UNSIGNED NOT NULL,
    changed_at DATETIME NOT NULL,
    FOREIGN KEY (changed_by) REFERENCES users(user_id)
);

CREATE INDEX audit_log_index_external_id ON audit_log(audit_log_id);
CREATE INDEX audit_log_index_table_name ON audit_log(table_name);
CREATE INDEX audit_log_index_reference_id ON audit_log(reference_id);
CREATE INDEX audit_log_index_changed_by ON audit_log(changed_by);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* UI Customization Setting Table */

CREATE TABLE ui_customization_setting (
    ui_customization_setting_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    theme_contrast VARCHAR(15) NOT NULL DEFAULT 'false',
    caption_show VARCHAR(15) NOT NULL DEFAULT 'true',
    preset_theme VARCHAR(15) NOT NULL DEFAULT 'preset-1',
    dark_layout VARCHAR(15) NOT NULL DEFAULT 'false',
    rtl_layout VARCHAR(15) NOT NULL DEFAULT 'false',
    box_container VARCHAR(15) NOT NULL DEFAULT 'false',
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX ui_customization_setting_index_ui_customization_setting_id ON ui_customization_setting(ui_customization_setting_id);
CREATE INDEX ui_customization_setting_index_user_id ON ui_customization_setting(user_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Role Table */

CREATE TABLE role(
	role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	role_name VARCHAR(100) NOT NULL,
	role_description VARCHAR(200) NOT NULL,
	assignable TINYINT(1) NOT NULL DEFAULT 1,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX role_index_role_id ON role(role_id);

INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Super Admin', 'This role has the highest level of access and full control over the entire system. Super Admins can perform all actions, including managing other user accounts, configuring system settings, and accessing all data.', '0', '1');
INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Administrator', 'Full access to all features and data within the system. This role have similar access levels to the Admin but is not as powerful as the Super Admin.', '1', '1');
INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Manager', 'Access to manage specific aspects of the system or resources related to their teams or departments.', '1', '1');
INSERT INTO role (role_name, role_description, assignable, last_log_by) VALUES ('Employee', 'The typical user account with standard access to use the system features and functionalities.', '1', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Role Users Table */

CREATE TABLE role_users(
	role_id INT NOT NULL,
	user_id INT NOT NULL,
	date_assigned DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX role_users_index_role_id ON role_users(role_id);
CREATE INDEX role_users_index_user_id ON role_users(user_id);

INSERT INTO role_users (role_id, user_id) VALUES ('1', '2');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Menu Group Table */

CREATE TABLE menu_group (
    menu_group_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    menu_group_name VARCHAR(100) NOT NULL,
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX menu_group_index_menu_group_id ON menu_group(menu_group_id);

INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) VALUES ('Human Resources', '40', '1');
INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) VALUES ('Administration', '90', '1');
INSERT INTO menu_group (menu_group_name, order_sequence, last_log_by) VALUES ('Technical', '100', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table */

CREATE TABLE menu_item(
	menu_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	menu_item_name VARCHAR(100) NOT NULL,
	menu_group_id INT UNSIGNED NOT NULL,
	menu_item_url VARCHAR(50),
	parent_id INT UNSIGNED,
	menu_item_icon VARCHAR(150),
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (menu_group_id) REFERENCES menu_group(menu_group_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX menu_item_index_menu_item_id ON menu_item(menu_item_id);

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Users & Companies', '2', '', '', 'user', '1', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Company', '2', 'company.php', '1', '', '3', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('User Account', '2', 'user-account.php', '1', '', '21', '1');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Roles', '2', '', '', 'shield', '2', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Role', '2', 'role.php', '4', '', '18', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Role Configuration', '2', 'role-configuration.php', '4', '', '18', '1');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('User Interface', '3', '', '', 'layout', '1', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Menu Group', '3', 'menu-group.php', '7', '', '13', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Menu Item', '3', 'menu-item.php', '7', '', '13', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('System Action', '3', 'system-action.php', '7', '', '19', '1');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Configurations', '3', '', '', 'settings', '2', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Email Setting', '3', 'email-setting.php', '11', '', '5', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('File Type', '3', 'file-type.php', '11', '', '6', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('File Extension', '3', 'file-extension.php', '11', '', '6', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Interface Setting', '3', 'interface-setting.php', '11', '', '9', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Notification Setting', '3', 'notification-setting.php', '11', '', '14', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('System Setting', '3', 'system-setting.php', '11', '', '19', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Upload Setting', '3', 'upload-setting.php', '11', '', '21', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Zoom API', '3', 'zoom-api.php', '11', '', '26', '1');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Localization', '3', '', '', 'globe', '3', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('City', '3', 'city.php', '20', '', '3', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Country', '3', 'country.php', '20', '', '3', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Currency', '3', 'currency.php', '20', '', '3', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('State', '3', 'state.php', '20', '', '19', '1');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Configurations', '1', '', '', 'settings', '20', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Branch', '1', 'branch.php', '25', '', '2', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Department', '1', 'department.php', '25', '', '4', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Job Position', '1', 'job-position.php', '25', '', '10', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Job Level', '1', 'job-level.php', '25', '', '10', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Employee Type', '1', 'employee-type.php', '25', '', '5', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Departure Reason', '1', 'departure-reason.php', '25', '', '4', '1');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('ID Type', '3', 'id-type.php', '11', '', '9', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Gender', '3', 'gender.php', '11', '', '7', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Religion', '3', 'religion.php', '11', '', '18', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Nationality', '3', 'nationality.php', '11', '', '14', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Relation', '3', 'relation.php', '11', '', '18', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Civil Status', '3', 'civil-status.php', '11', '', '3', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Blood Type', '3', 'blood-type.php', '11', '', '2', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Bank', '3', 'bank.php', '11', '', '2', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Holiday Type', '3', 'holiday-type.php', '11', '', '8', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Work Schedule Type', '1', 'work-schedule-type.php', '25', '', '23', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Work Schedule', '1', 'work-schedule.php', '25', '', '23', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Bank Account Type', '3', 'bank-account-type.php', '11', '', '2', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Contact Information Type', '3', 'contact-information-type.php', '11', '', '3', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Address Type', '3', 'address-type.php', '11', '', '1', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Educational Stage', '3', 'educational-stage.php', '11', '', '5', '1');

INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Employees', '1', '', '', 'users', '10', '1');
INSERT INTO menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) VALUES ('Employee', '1', 'employee.php', '47', '', '5', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Menu Item Access Right Table */

CREATE TABLE menu_item_access_right(
	menu_item_id INT UNSIGNED NOT NULL,
	role_id INT UNSIGNED NOT NULL,
	read_access TINYINT(1) NOT NULL,
    write_access TINYINT(1) NOT NULL,
    create_access TINYINT(1) NOT NULL,
    delete_access TINYINT(1) NOT NULL,
    duplicate_access TINYINT(1) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (menu_item_id) REFERENCES menu_item(menu_item_id),
    FOREIGN KEY (role_id) REFERENCES role(role_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX menu_item_access_right_index_menu_item_id ON menu_item_access_right(menu_item_id);
CREATE INDEX menu_item_access_right_index_role_id ON menu_item_access_right(role_id);

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('1', '1', '1', '0', '0', '0', '0', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('2', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('3', '1', '1', '1', '1', '1', '1', '1');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('4', '1', '1', '0', '0', '0', '0', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('5', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('6', '1', '1', '1', '1', '1', '1', '1');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('7', '1', '1', '0', '0', '0', '0', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('8', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('9', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('10', '1', '1', '1', '1', '1', '1', '1');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('11', '1', '1', '0', '0', '0', '0', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('12', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('13', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('14', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('15', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('16', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('17', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('18', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('19', '1', '1', '1', '1', '1', '1', '1');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('20', '1', '1', '0', '0', '0', '0', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('21', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('22', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('23', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('24', '1', '1', '1', '1', '1', '1', '1');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('25', '1', '1', '0', '0', '0', '0', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('26', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('27', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('28', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('29', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('30', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('31', '1', '1', '1', '1', '1', '1', '1');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('32', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('33', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('34', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('35', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('36', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('37', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('38', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('39', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('40', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('41', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('42', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('43', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('44', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('45', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('46', '1', '1', '1', '1', '1', '1', '1');

INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('47', '1', '1', '0', '0', '0', '0', '1');
INSERT INTO menu_item_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, duplicate_access, last_log_by) VALUES ('48', '1', '1', '1', '1', '1', '1', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Action Table */

CREATE TABLE system_action(
	system_action_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	system_action_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX system_action_index_system_action_id ON system_action(system_action_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Action Access Right Table */

CREATE TABLE system_action_access_rights(
	system_action_id INT UNSIGNED NOT NULL,
	role_id INT UNSIGNED NOT NULL,
	role_access TINYINT(1) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (system_action_id) REFERENCES system_action(system_action_id),
    FOREIGN KEY (role_id) REFERENCES role(role_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX system_action_access_right_index_system_action_id ON system_action_access_right(system_action_id);
CREATE INDEX system_action_access_right_index_role_id ON system_action_access_right(role_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* File Type Table */

CREATE TABLE file_type(
	file_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	file_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX file_type_index_file_type_id ON file_type(file_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* File Extension Table */

CREATE TABLE file_extension(
	file_extension_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	file_extension_name VARCHAR(100) NOT NULL,
	file_type_id INT UNSIGNED NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (file_type_id) REFERENCES file_type(file_type_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX file_extension_index_file_extension_id ON file_extension(file_extension_id);
CREATE INDEX file_extension_index_file_type_id ON file_extension(file_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Upload Setting Table */

CREATE TABLE upload_setting(
	upload_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	upload_setting_name VARCHAR(100) NOT NULL,
	upload_setting_description VARCHAR(200) NOT NULL,
	max_file_size DOUBLE NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX upload_setting_index_upload_setting_id ON upload_setting(upload_setting_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Upload Setting File Extension Table */

CREATE TABLE upload_setting_file_extension(
	upload_setting_id INT UNSIGNED NOT NULL,
	file_extension_id INT UNSIGNED NOT NULL,
	date_assigned DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX upload_setting_file_extension_index_upload_setting_id ON upload_setting_file_extension(upload_setting_id);
CREATE INDEX upload_setting_file_extension_index_file_extension_id ON upload_setting_file_extension(file_extension_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Interface Setting Table */

CREATE TABLE interface_setting(
	interface_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	interface_setting_name VARCHAR(100) NOT NULL,
	interface_setting_description VARCHAR(200) NOT NULL,
	value VARCHAR(1000),
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX interface_setting_index_interface_setting_id ON interface_setting(interface_setting_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Setting Table */

CREATE TABLE system_setting(
	system_setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	system_setting_name VARCHAR(100) NOT NULL,
	system_setting_description VARCHAR(200) NOT NULL,
	value VARCHAR(1000) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX system_setting_index_system_setting_id ON system_setting(system_setting_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Country Table */

CREATE TABLE country(
	country_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	country_name VARCHAR(100) NOT NULL,
	country_code VARCHAR(5),
	phone_code VARCHAR(20) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX country_index_country_id ON country(country_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* State Table */

CREATE TABLE state(
	state_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	state_name VARCHAR(100) NOT NULL,
	country_id INT NOT NULL,
	state_code VARCHAR(5),
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (country_id) REFERENCES country(country_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX state_index_state_id ON state(state_id);
CREATE INDEX state_index_country_id ON state(country_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* City Table */

CREATE TABLE city(
	city_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	city_name VARCHAR(100) NOT NULL,
	state_id INT NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (state_id) REFERENCES state(state_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX city_index_city_id ON city(city_id);
CREATE INDEX city_index_state_id ON city(state_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Currency Table */

CREATE TABLE currency(
	currency_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	currency_name VARCHAR(100) NOT NULL,
	symbol VARCHAR(10) NOT NULL,
	shorthand VARCHAR(10) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX currency_index_currency_id ON currency(currency_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Company Table */

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
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX company_index_company_id ON company(company_id);
CREATE INDEX company_index_city_id ON company(city_id);
CREATE INDEX company_index_currency_id ON company(currency_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Email Setting Table */

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
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX email_setting_index_email_setting_id ON email_setting(email_setting_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Notification Setting Table */

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
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX notification_setting_index_notification_setting_id ON notification_setting(notification_setting_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Zoom API Table */

CREATE TABLE zoom_api(
	zoom_api_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	zoom_api_name VARCHAR(100) NOT NULL,
	zoom_api_description VARCHAR(200) NOT NULL,
	api_key VARCHAR(1000) NOT NULL,
	api_secret VARCHAR(1000) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX zoom_api_index_zoom_api_id ON zoom_api(zoom_api_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Branch Table */

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
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX branch_index_branch_id ON branch(branch_id);
CREATE INDEX branch_index_city_id ON branch(city_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Department Table */

CREATE TABLE department(
	department_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	department_name VARCHAR(100) NOT NULL,
	parent_department INT,
	manager INT,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX department_index_department_id ON department(department_id);
CREATE INDEX department_index_parent_department ON department(parent_department);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Table */

CREATE TABLE job_position(
	job_position_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_name VARCHAR(100) NOT NULL,
	job_position_description VARCHAR(2000) NOT NULL,
	recruitment_status TINYINT(1),
	department_id INT,
	expected_new_employees INT NOT NULL DEFAULT 0,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX job_position_index_job_position_id ON job_position(job_position_id);
CREATE INDEX job_position_index_department_id ON job_position(department_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Responsibility Table */

CREATE TABLE job_position_responsibility(
	job_position_responsibility_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_id INT UNSIGNED NOT NULL,
	responsibility VARCHAR(1000) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (job_position_id) REFERENCES job_position(job_position_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX job_position_responsibility_index_job_position_responsibility_id ON job_position_responsibility(job_position_responsibility_id);
CREATE INDEX job_position_responsibility_index_job_position_id ON job_position_responsibility(job_position_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Requirement Table */

CREATE TABLE job_position_requirement(
	job_position_requirement_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_id INT UNSIGNED NOT NULL,
	requirement VARCHAR(1000) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (job_position_id) REFERENCES job_position(job_position_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX job_position_requirement_index_job_position_requirement_id ON job_position_requirement(job_position_requirement_id);
CREATE INDEX job_position_requirement_index_job_position_id ON job_position_requirement(job_position_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Qualification Table */

CREATE TABLE job_position_qualification(
	job_position_qualification_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_id INT UNSIGNED NOT NULL,
	qualification VARCHAR(1000) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (job_position_id) REFERENCES job_position(job_position_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX job_position_qualification_index_job_position_qualification_id ON job_position_qualification(job_position_qualification_id);
CREATE INDEX job_position_qualification_index_job_position_id ON job_position_qualification(job_position_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Level Table */

CREATE TABLE job_level(
	job_level_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	current_level VARCHAR(10) NOT NULL,
	rank VARCHAR(100) NOT NULL,
	functional_level VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX job_level_index_job_level_id ON job_level(job_level_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Type Table */

CREATE TABLE employee_type(
	employee_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	employee_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX employee_type_index_employee_type_id ON employee_type(employee_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Departure Reason Table */

CREATE TABLE departure_reason(
	departure_reason_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	departure_reason_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX departure_reason_index_departure_reason_id ON departure_reason(departure_reason_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* ID Type Table */

CREATE TABLE id_type(
	id_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	id_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX id_type_index_id_type_id ON id_type(id_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Gender Table */

CREATE TABLE gender(
	gender_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	gender_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX gender_index_gender_id ON gender(gender_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Religion Table */

CREATE TABLE religion(
	religion_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	religion_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX religion_index_religion_id ON religion(religion_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Nationality Table */

CREATE TABLE nationality(
	nationality_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	nationality_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX nationality_index_nationality_id ON nationality(nationality_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Relation Table */

CREATE TABLE relation(
	relation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	relation_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX relation_index_relation_id ON relation(relation_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Civil Status Table */

CREATE TABLE civil_status(
	civil_status_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	civil_status_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX civil_status_index_civil_status_id ON civil_status(civil_status_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Blood Type Table */

CREATE TABLE blood_type(
	blood_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	blood_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX blood_type_index_blood_type_id ON blood_type(blood_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Bank Table */

CREATE TABLE bank(
	bank_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	bank_name VARCHAR(100) NOT NULL,
	bank_identifier_code VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX bank_index_bank_id ON bank(bank_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Holiday Type Table */

CREATE TABLE holiday_type(
	holiday_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	holiday_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX holiday_type_index_holiday_type_id ON holiday_type(holiday_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Schedule Type Table */

CREATE TABLE work_schedule_type(
	work_schedule_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	work_schedule_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX work_schedule_type_index_work_schedule_type_id ON work_schedule_type(work_schedule_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Address Type Table */

CREATE TABLE address_type(
	address_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	address_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX address_type_index_address_type_id ON address_type(address_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Educational Stage Table */

CREATE TABLE educational_stage(
	educational_stage_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	educational_stage_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX educational_stage_index_educational_stage_id ON educational_stage(educational_stage_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Information Type Table */

CREATE TABLE contact_information_type(
	contact_information_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	contact_information_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX contact_information_type_index_contact_information_type_id ON contact_information_type(contact_information_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Schedule Table */

CREATE TABLE work_schedule(
	work_schedule_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	work_schedule_name VARCHAR(100) NOT NULL,
	work_schedule_description VARCHAR(500) NOT NULL,
	work_schedule_type_id INT UNSIGNED NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (work_schedule_type_id) REFERENCES work_schedule_type(work_schedule_type_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX work_schedule_index_work_schedule_id ON work_schedule(work_schedule_id);
CREATE INDEX work_schedule_index_work_schedule_type_id ON work_schedule(work_schedule_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Hours Table */

CREATE TABLE work_hours (
    work_hours_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    work_schedule_id INT UNSIGNED,
    work_date DATE,
    day_of_week VARCHAR(15),
    day_period VARCHAR(15),
    start_time TIME,
    end_time TIME,
    notes TEXT,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (work_schedule_id) REFERENCES work_schedule(work_schedule_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);
 
CREATE INDEX work_hours_index_work_hours_id ON work_hours(work_hours_id);
CREATE INDEX work_hours_index_work_schedule_id ON work_hours(work_schedule_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Bank Account Type Table */

CREATE TABLE bank_account_type(
	bank_account_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	bank_account_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX bank_account_type_index_bank_account_type_id ON bank_account_type(bank_account_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Table */

CREATE TABLE employee(
	employee_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	user_id INT UNSIGNED,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX employee_index_employee_id ON employee(employee_id);
CREATE INDEX employee_index_user_id ON employee(user_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Personal Information Table */

CREATE TABLE employee_personal_information(
	employee_id INT UNSIGNED PRIMARY KEY NOT NULL,
	employee_image VARCHAR(500),
	employee_signature VARCHAR(500),
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
    birthday DATE,
    birth_place VARCHAR(1000),
    height FLOAT,
    weight FLOAT,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX employee_personal_information_index_employee_id ON employee_personal_information(employee_id);
CREATE INDEX employee_personal_information_index_civil_status_id ON employee_personal_information(civil_status_id);
CREATE INDEX employee_personal_information_index_gender_id ON employee_personal_information(gender_id);
CREATE INDEX employee_personal_information_index_religion_id ON employee_personal_information(religion_id);
CREATE INDEX employee_personal_information_index_blood_type_id ON employee_personal_information(blood_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Employment Information Table */

CREATE TABLE employee_employment_information(
	employee_id INT UNSIGNED PRIMARY KEY NOT NULL,
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
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX employee_employment_information_index_employee_id ON employee_employment_information(employee_id);
CREATE INDEX employee_employment_information_index_employee_type_id ON employee_employment_information(employee_type_id);
CREATE INDEX employee_employment_information_index_department_id ON employee_employment_information(department_id);
CREATE INDEX employee_employment_information_index_job_position_id ON employee_employment_information(job_position_id);
CREATE INDEX employee_employment_information_index_job_level_id ON employee_employment_information(job_level_id);
CREATE INDEX employee_employment_information_index_branch_id ON employee_employment_information(branch_id);
CREATE INDEX employee_employment_information_index_departure_reason_id ON employee_employment_information(departure_reason_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table */



/* ----------------------------------------------------------------------------------------------------------------------------- */