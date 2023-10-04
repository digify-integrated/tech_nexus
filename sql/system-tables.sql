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

INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update Menu Item Role Access', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Menu Item Role Access', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update System Action Role Access', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete System Action Role Access', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Assign User Account To Role', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete User Account To Role', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Assign Role To User Account', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Role To User Account', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Activate User Account', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Deactivate User Account', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Lock User Account', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Unlock User Account', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Change User Account Password', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Change User Account Profile Picture', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Assign File Extension To Upload Setting', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete File Extension To Upload Setting', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Send Reset Password Instructions', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Add Job Position Responsibility', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update Job Position Responsibility', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Job Position Responsibility', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Add Job Position Requirement', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update Job Position Requirement', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Job Position Requirement', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Add Job Position Qualification', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update Job Position Qualification', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Job Position Qualification', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Start Job Position Recruitment', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Stop Job Position Recruitment', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Add Working Hours', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Update Working Hours', '1');
INSERT INTO system_action (system_action_name, last_log_by) VALUES ('Delete Working Hours', '1');

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

CREATE INDEX system_action_access_right_index_system_action_id ON system_action_access_rights(system_action_id);
CREATE INDEX system_action_access_right_index_role_id ON system_action_access_rights(role_id);

INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('1', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('2', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('3', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('4', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('5', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('6', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('7', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('8', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('9', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('10', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('11', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('12', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('13', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('14', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('15', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('16', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('17', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('18', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('19', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('20', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('21', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('22', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('23', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('24', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('25', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('26', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('27', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('28', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('29', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('30', '1', '1', '1');
INSERT INTO system_action_access_rights (system_action_id, role_id, role_access, last_log_by) VALUES ('31', '1', '1', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* File Type Table */

CREATE TABLE file_type(
	file_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	file_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX file_type_index_file_type_id ON file_type(file_type_id);

INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Audio', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Compressed', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Disk and Media', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Data and Database', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Email', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Executable', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Font', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Image', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Internet Related', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Presentation', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Spreadsheet', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('System Related', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Video', '1');
INSERT INTO file_type (file_type_name, last_log_by) VALUES ('Word Processor', '1');

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

INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('aif', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cda', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mid', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('midi', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mp3', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mpa', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ogg', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wav', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wma', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wpl', '1', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('7z', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('arj', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('deb', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pkg', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rar', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rpm', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tar.gz', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('z', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('zip', '2', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bin', '3', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dmg', '3', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('iso', '3', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('toast', '3', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('vcd', '3', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('csv', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dat', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('db', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dbf', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('log', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mdb', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('sav', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('sql', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tar', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xml', '4', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('email', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('eml', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('emlx', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('msg', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('oft', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ost', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pst', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('vcf', '5', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('apk', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bat', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bin', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cgi', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pl', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('com', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('exe', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('gadget', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jar', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wsf', '6', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('fnt', '7', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('fon', '7', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('otf', '7', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ttf', '7', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ai', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bmp', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('gif', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ico', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jpg', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jpeg', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('png', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ps', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('psd', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('svg', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tif', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tiff', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('webp', '8', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('asp', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('aspx', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cer', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cfm', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cgi', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pl', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('css', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('htm', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('html', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('js', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('jsp', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('part', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('php', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('py', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rss', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xhtml', '9', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('key', '10', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('odp', '10', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pps', '10', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ppt', '10', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pptx', '10', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ods', '11', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xls', '11', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xlsm', '11', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('xlsx', '11', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('bak', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cab', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cfg', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cpl', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('cur', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dll', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('dmp', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('drv', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('icns', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('ini', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('lnk', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('msi', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('sys', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tmp', '12', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('3g2', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('3gp', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('avi', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('flv', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('h264', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('m4v', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mkv', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mov', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mp4', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mpg', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('mpeg', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rm', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('swf', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('vob', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('webm', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wmv', '13', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('doc', '14', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('docx', '14', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('pdf', '14', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('rtf', '14', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('tex', '14', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('txt', '14', '1');
INSERT INTO file_extension (file_extension_name, file_type_id, last_log_by) VALUES ('wpd', '14', '1');

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

INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) VALUES ('Profile Image', 'Sets the upload setting when uploading user account profile image.', 5, '1');
INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) VALUES ('Interface Setting', 'Sets the upload setting when uploading interface setting image.', 5, '1');
INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) VALUES ('Company Logo', 'Sets the upload setting when uploading company logo.', 5, '1');
INSERT INTO upload_setting (upload_setting_name, upload_setting_description, max_file_size, last_log_by) VALUES ('Employee Image', 'Sets the upload setting when uploading employee image.', 5, '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Upload Setting File Extension Table */

CREATE TABLE upload_setting_file_extension(
	upload_setting_id INT UNSIGNED NOT NULL,
	file_extension_id INT UNSIGNED NOT NULL,
	date_assigned DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX upload_setting_file_extension_index_upload_setting_id ON upload_setting_file_extension(upload_setting_id);
CREATE INDEX upload_setting_file_extension_index_file_extension_id ON upload_setting_file_extension(file_extension_id);

INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('1', 61);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('1', 62);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('1', 63);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('1', 66);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('1', 69);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('2', 61);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('2', 62);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('2', 63);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('2', 66);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('2', 69);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('2', 60);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('3', 61);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('3', 62);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('3', 63);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('3', 66);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('3', 69);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('4', 61);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('4', 62);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('4', 63);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('4', 66);
INSERT INTO upload_setting_file_extension (upload_setting_id, file_extension_id) VALUES ('4', 69);

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

INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('Login Background', 'Interface setting for background image on login page.', '1');
INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('Login Logo', 'Interface setting for logo on login page.', '1');
INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('Navbar Logo', 'Interface setting for logo on navbar.', '1');
INSERT INTO interface_setting (interface_setting_name, interface_setting_description, last_log_by) VALUES ('System Icon', 'Interface setting for system icon.', '1');

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

INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) VALUES ('Max Failed Login Attempt', 'This sets the maximum failed login attempt before the user is locked-out.', 5, '1');
INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) VALUES ('Max Failed OTP Attempt', 'This sets the maximum failed OTP attempt before the user is needs a new OTP code.', 5, '1');
INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) VALUES ('Default Forgot Password Link', 'This sets the default forgot password link.', 'http://localhost/tech_nexus/password-reset.php?id=', '1');
INSERT INTO system_setting (system_setting_name, system_setting_description, value, last_log_by) VALUES ('File As Arrangement', 'This sets the arrangement of the file as.', '{last_name}, {first_name} {suffix} {middle_name}', '1');

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

INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Afghanistan', 'AFG', '93', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Aland Islands', 'ALA', '340', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Albania', 'ALB', '355', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Algeria', 'DZA', '213', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('American Samoa', 'ASM', '-683', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Andorra', 'AND', '376', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Angola', 'AGO', '244', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Anguilla', 'AIA', '-263', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Antarctica', 'ATA', '672', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Antigua And Barbuda', 'ATG', '-267', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Argentina', 'ARG', '54', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Armenia', 'ARM', '374', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Aruba', 'ABW', '297', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Australia', 'AUS', '61', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Austria', 'AUT', '43', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Azerbaijan', 'AZE', '994', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bahrain', 'BHR', '973', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bangladesh', 'BGD', '880', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Barbados', 'BRB', '-245', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Belarus', 'BLR', '375', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Belgium', 'BEL', '32', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Belize', 'BLZ', '501', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Benin', 'BEN', '229', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bermuda', 'BMU', '-440', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bhutan', 'BTN', '975', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bolivia', 'BOL', '591', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bonaire, Sint Eustatius and Saba', 'BES', '599', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bosnia and Herzegovina', 'BIH', '387', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Botswana', 'BWA', '267', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bouvet Island', 'BVT', '55', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Brazil', 'BRA', '55', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('British Indian Ocean Territory', 'IOT', '246', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Brunei', 'BRN', '673', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Bulgaria', 'BGR', '359', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Burkina Faso', 'BFA', '226', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Burundi', 'BDI', '257', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cambodia', 'KHM', '855', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cameroon', 'CMR', '237', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Canada', 'CAN', '1', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cape Verde', 'CPV', '238', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cayman Islands', 'CYM', '-344', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Central African Republic', 'CAF', '236', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Chad', 'TCD', '235', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Chile', 'CHL', '56', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('China', 'CHN', '86', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Christmas Island', 'CXR', '61', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cocos (Keeling) Islands', 'CCK', '61', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Colombia', 'COL', '57', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Comoros', 'COM', '269', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Congo', 'COG', '242', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cook Islands', 'COK', '682', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Costa Rica', 'CRI', '506', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cote D Ivoire (Ivory Coast)', 'CIV', '225', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Croatia', 'HRV', '385', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cuba', 'CUB', '53', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('CuraÃ§ao', 'CUW', '599', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Cyprus', 'CYP', '357', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Czech Republic', 'CZE', '420', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Democratic Republic of the Congo', 'COD', '243', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Denmark', 'DNK', '45', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Djibouti', 'DJI', '253', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Dominica', 'DMA', '-766', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Dominican Republic', 'DOM', '+1-809 and 1-829', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('East Timor', 'TLS', '670', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ecuador', 'ECU', '593', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Egypt', 'EGY', '20', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('El Salvador', 'SLV', '503', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Equatorial Guinea', 'GNQ', '240', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Eritrea', 'ERI', '291', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Estonia', 'EST', '372', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ethiopia', 'ETH', '251', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Falkland Islands', 'FLK', '500', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Faroe Islands', 'FRO', '298', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Fiji Islands', 'FJI', '679', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Finland', 'FIN', '358', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('France', 'FRA', '33', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('French Guiana', 'GUF', '594', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('French Polynesia', 'PYF', '689', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('French Southern Territories', 'ATF', '262', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Gabon', 'GAB', '241', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Gambia The', 'GMB', '220', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Georgia', 'GEO', '995', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Germany', 'DEU', '49', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ghana', 'GHA', '233', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Gibraltar', 'GIB', '350', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Greece', 'GRC', '30', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Greenland', 'GRL', '299', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Grenada', 'GRD', '-472', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guadeloupe', 'GLP', '590', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guam', 'GUM', '-670', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guatemala', 'GTM', '502', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guernsey and Alderney', 'GGY', '-1437', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guinea', 'GIN', '224', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guinea-Bissau', 'GNB', '245', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Guyana', 'GUY', '592', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Haiti', 'HTI', '509', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Heard Island and McDonald Islands', 'HMD', '672', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Honduras', 'HND', '504', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Hong Kong S.A.R.', 'HKG', '852', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Hungary', 'HUN', '36', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Iceland', 'ISL', '354', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('India', 'IND', '91', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Indonesia', 'IDN', '62', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Iran', 'IRN', '98', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Iraq', 'IRQ', '964', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ireland', 'IRL', '353', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Israel', 'ISR', '972', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Italy', 'ITA', '39', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Jamaica', 'JAM', '-875', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Japan', 'JPN', '81', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Jersey', 'JEY', '-1490', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Jordan', 'JOR', '962', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kazakhstan', 'KAZ', '7', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kenya', 'KEN', '254', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kiribati', 'KIR', '686', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kosovo', 'XKX', '383', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kuwait', 'KWT', '965', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Kyrgyzstan', 'KGZ', '996', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Laos', 'LAO', '856', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Latvia', 'LVA', '371', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Lebanon', 'LBN', '961', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Lesotho', 'LSO', '266', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Liberia', 'LBR', '231', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Libya', 'LBY', '218', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Liechtenstein', 'LIE', '423', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Lithuania', 'LTU', '370', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Luxembourg', 'LUX', '352', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Macau S.A.R.', 'MAC', '853', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Madagascar', 'MDG', '261', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Malawi', 'MWI', '265', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Malaysia', 'MYS', '60', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Maldives', 'MDV', '960', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mali', 'MLI', '223', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Malta', 'MLT', '356', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Man (Isle of)', 'IMN', '-1580', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Marshall Islands', 'MHL', '692', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Martinique', 'MTQ', '596', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mauritania', 'MRT', '222', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mauritius', 'MUS', '230', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mayotte', 'MYT', '262', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mexico', 'MEX', '52', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Micronesia', 'FSM', '691', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Moldova', 'MDA', '373', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Monaco', 'MCO', '377', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mongolia', 'MNG', '976', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Montenegro', 'MNE', '382', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Montserrat', 'MSR', '-663', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Morocco', 'MAR', '212', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Mozambique', 'MOZ', '258', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Myanmar', 'MMR', '95', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Namibia', 'NAM', '264', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nauru', 'NRU', '674', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nepal', 'NPL', '977', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Netherlands', 'NLD', '31', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('New Caledonia', 'NCL', '687', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('New Zealand', 'NZL', '64', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nicaragua', 'NIC', '505', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Niger', 'NER', '227', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Nigeria', 'NGA', '234', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Niue', 'NIU', '683', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Norfolk Island', 'NFK', '672', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('North Korea', 'PRK', '850', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('North Macedonia', 'MKD', '389', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Northern Mariana Islands', 'MNP', '-669', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Norway', 'NOR', '47', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Oman', 'OMN', '968', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Pakistan', 'PAK', '92', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Palau', 'PLW', '680', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Palestinian Territory Occupied', 'PSE', '970', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Panama', 'PAN', '507', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Papua new Guinea', 'PNG', '675', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Paraguay', 'PRY', '595', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Peru', 'PER', '51', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Philippines', 'PHL', '63', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Pitcairn Island', 'PCN', '870', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Poland', 'POL', '48', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Portugal', 'PRT', '351', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Puerto Rico', 'PRI', '+1-787 and 1-939', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Qatar', 'QAT', '974', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Reunion', 'REU', '262', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Romania', 'ROU', '40', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Russia', 'RUS', '7', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Rwanda', 'RWA', '250', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Helena', 'SHN', '290', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Kitts And Nevis', 'KNA', '-868', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Lucia', 'LCA', '-757', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Pierre and Miquelon', 'SPM', '508', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint Vincent And The Grenadines', 'VCT', '-783', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint-Barthelemy', 'BLM', '590', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saint-Martin (French part)', 'MAF', '590', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Samoa', 'WSM', '685', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('San Marino', 'SMR', '378', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sao Tome and Principe', 'STP', '239', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Saudi Arabia', 'SAU', '966', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Senegal', 'SEN', '221', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Serbia', 'SRB', '381', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Seychelles', 'SYC', '248', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sierra Leone', 'SLE', '232', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Singapore', 'SGP', '65', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sint Maarten (Dutch part)', 'SXM', '1721', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Slovakia', 'SVK', '421', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Slovenia', 'SVN', '386', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Solomon Islands', 'SLB', '677', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Somalia', 'SOM', '252', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Africa', 'ZAF', '27', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Georgia', 'SGS', '500', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Korea', 'KOR', '82', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('South Sudan', 'SSD', '211', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Spain', 'ESP', '34', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sri Lanka', 'LKA', '94', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sudan', 'SDN', '249', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Suriname', 'SUR', '597', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Svalbard And Jan Mayen Islands', 'SJM', '47', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Swaziland', 'SWZ', '268', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Sweden', 'SWE', '46', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Switzerland', 'CHE', '41', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Syria', 'SYR', '963', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Taiwan', 'TWN', '886', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tajikistan', 'TJK', '992', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tanzania', 'TZA', '255', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Thailand', 'THA', '66', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('The Bahamas', 'BHS', '-241', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Togo', 'TGO', '228', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tokelau', 'TKL', '690', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tonga', 'TON', '676', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Trinidad And Tobago', 'TTO', '-867', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tunisia', 'TUN', '216', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Turkey', 'TUR', '90', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Turkmenistan', 'TKM', '993', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Turks And Caicos Islands', 'TCA', '-648', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Tuvalu', 'TUV', '688', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Uganda', 'UGA', '256', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Ukraine', 'UKR', '380', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United Arab Emirates', 'ARE', '971', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United Kingdom', 'GBR', '44', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United States', 'USA', '1', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('United States Minor Outlying Islands', 'UMI', '1', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Uruguay', 'URY', '598', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Uzbekistan', 'UZB', '998', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Vanuatu', 'VUT', '678', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Vatican City State (Holy See)', 'VAT', '379', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Venezuela', 'VEN', '58', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Vietnam', 'VNM', '84', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Virgin Islands (British)', 'VGB', '-283', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Virgin Islands (US)', 'VIR', '-339', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Wallis And Futuna Islands', 'WLF', '681', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Western Sahara', 'ESH', '212', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Yemen', 'YEM', '967', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Zambia', 'ZMB', '260', '1');
INSERT INTO country (country_name, country_code, phone_code, last_log_by) VALUES ('Zimbabwe', 'ZWE', '263', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* State Table */

CREATE TABLE state(
	state_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	state_name VARCHAR(100) NOT NULL,
	country_id INT UNSIGNED NOT NULL,
	state_code VARCHAR(5),
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (country_id) REFERENCES country(country_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX state_index_state_id ON state(state_id);
CREATE INDEX state_index_country_id ON state(country_id);

INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Metro Manila', '174', '00', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilocos Norte', '174', 'ILN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ilocos Sur', '174', 'ILS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('La Union', '174', 'LUN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pangasinan', '174', 'PAN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batanes', '174', 'BTN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cagayan', '174', 'CAG', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Isabela', '174', 'ISA', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nueva Vizcaya', '174', 'NSA', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quirino', '174', 'QUI', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bataan', '174', 'BAN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bulacan', '174', 'BUL', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Nueva Ecija', '174', 'NE', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Pampanga', '174', 'PAM', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tarlac', '174', 'TAR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zambales', '174', 'ZMB', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aurora', '174', 'AUR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Batangas', '174', 'BTG', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cavite', '174', 'CAV', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Laguna', '174', 'LAG', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Quezon', '174', 'QUE', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Rizal', '174', 'RIZ', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Marinduque', '174', 'MAD', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Occidental Mindoro', '174', 'NUE', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Oriental Mindoro', '174', 'NUV', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Palawan', '174', 'PLW', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Romblon', '174', 'ROM', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Albay', '174', 'ALB', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Camarines Norte', '174', 'CAN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Camarines Sur', '174', 'CAS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Catanduanes', '174', 'CAT', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Masbate', '174', 'MAS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sorsogon', '174', 'SOR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Aklan', '174', 'AKL', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Antique', '174', 'ANT', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Capiz', '174', 'CAP', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Iloilo', '174', 'ILI', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Negros Occidental', '174', 'MSR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Guimaras', '174', 'GUI', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bohol', '174', 'BOH', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cebu', '174', 'CEB', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Negros Oriental', '174', 'MOU', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Siquijor', '174', 'SIG', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Eastern Samar', '174', 'EAS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Leyte', '174', 'LEY', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Northern Samar', '174', 'NEC', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Samar', '174', 'WSA', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Southern Leyte', '174', 'SLE', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Biliran', '174', 'BIL', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamboanga del Norte', '174', 'ZAN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamboanga del Sur', '174', 'ZAS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Zamboanga Sibugay', '174', 'ZSI', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Bukidnon', '174', 'BUK', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Camiguin', '174', 'CAM', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lanao del Norte', '174', 'LAN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Misamis Occidental', '174', 'MDC', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Misamis Oriental', '174', 'MDR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao del Norte', '174', 'DAV', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao del Sur', '174', 'DAS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao Oriental', '174', 'DAO', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao de Oro', '174', 'COM', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Davao Occidental', '174', 'DVO', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Cotabato', '174', 'COM', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('South Cotabato', '174', 'SCO', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sultan Kudarat', '174', 'SUK', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sarangani', '174', 'SAR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Abra', '174', 'ABR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Benguet', '174', 'BEN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Ifugao', '174', 'IFU', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Kalinga', '174', 'KAL', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Mountain Province', '174', 'MSC', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Apayao', '174', 'APA', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Basilan', '174', 'BAS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Lanao del Sur', '174', 'LAS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Maguindanao', '174', 'MAG', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Sulu', '174', 'SLU', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Tawi-Tawi', '174', 'TAW', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agusan del Norte', '174', 'AGN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Agusan del Sur', '174', 'AGS', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surigao del Norte', '174', 'SUN', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Surigao del Sur', '174', 'SUR', '1');
INSERT INTO state (state_name, country_id, state_code, last_log_by) VALUES ('Dinagat Islands', '174', 'DIN', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* City Table */

CREATE TABLE city(
	city_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	city_name VARCHAR(100) NOT NULL,
	state_id INT UNSIGNED NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (state_id) REFERENCES state(state_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX city_index_city_id ON city(city_id);
CREATE INDEX city_index_state_id ON city(state_id);

INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Adams', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacarra', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Badoc', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bangui', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Batac', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burgos', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carasi', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Currimao', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dingras', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumalneg', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banna', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Laoag', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Marcos', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nueva Era', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pagudpud', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paoay', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pasuquin', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Piddig', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pinili', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Nicolas', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sarrat', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Solsona', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Vintar', '2', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alilem', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banayoyo', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bantay', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burgos', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabugao', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Candon', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caoayan', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cervantes', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Galimuyod', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gregorio del Pilar', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lidlidda', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magsingal', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nagbukel', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Narvacan', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quirino', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Salcedo', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Emilio', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Esteban', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Ildefonso', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Juan', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Vicente', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Catalina', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Cruz', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Lucia', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Maria', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santiago', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Domingo', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sigay', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sinait', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sugpon', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Suyo', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagudin', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Vigan', '3', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Agoo', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aringay', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacnotan', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bagulin', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balaoan', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bangar', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bauang', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burgos', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caba', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Luna', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Naguilian', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pugo', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rosario', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of San Fernando', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Gabriel', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Juan', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Tomas', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santol', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sudipen', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubao', '4', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Agno', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aguilar', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Alaminos', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alcala', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Anda', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Asingan', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balungao', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bani', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Basista', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bautista', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bayambang', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Binalonan', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Binmaley', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bolinao', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bugallon', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burgos', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calasiao', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Dagupan', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dasol', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Infanta', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Labrador', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lingayen', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabini', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malasiqui', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manaoag', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mangaldan', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mangatarem', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mapandan', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Natividad', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pozorrubio', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rosales', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of San Carlos', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Fabian', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jacinto', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Manuel', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Nicolas', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Quintin', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Barbara', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Maria', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Tomas', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sison', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sual', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tayug', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Umingan', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Urbiztondo', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Urdaneta', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Villasis', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Laoac', '5', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Basco', '6', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Itbayat', '6', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ivana', '6', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mahatao', '6', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sabtang', '6', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Uyugan', '6', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Abulug', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alcala', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Allacapan', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Amulung', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aparri', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baggao', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ballesteros', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buguey', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calayan', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Camalaniugan', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Claveria', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Enrile', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gattaran', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gonzaga', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Iguig', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lal-Lo', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lasam', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pamplona', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Peñablanca', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Piat', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rizal', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sanchez-Mira', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Ana', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Praxedes', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Teresita', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Niño', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Solana', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tuao', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tuguegarao City', '7', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alicia', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Angadanan', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aurora', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Benito Soliven', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burgos', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabagan', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabatuan', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cauayan', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cordon', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dinapigue', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Divilacan', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Echague', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gamu', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Ilagan', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jones', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Luna', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maconacon', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Delfin Albano', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mallig', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Naguilian', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Palanan', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quezon', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quirino', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ramon', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Reina Mercedes', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Roxas', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Agustin', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Guillermo', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Manuel', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Mariano', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Mateo', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Pablo', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Maria', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Santiago', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Tomas', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tumauini', '8', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ambaguio', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aritao', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bagabag', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bambang', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bayombong', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Diadi', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dupax del Norte', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dupax del Sur', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kasibu', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kayapa', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quezon', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Fe', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Solano', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Villaverde', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alfonso Castaneda', '9', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aglipay', '10', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabarroguis', '10', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Diffun', '10', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maddela', '10', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Saguday', '10', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nagtipunan', '10', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Abucay', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bagac', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Balanga', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dinalupihan', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hermosa', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Limay', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mariveles', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Morong', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Orani', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Orion', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pilar', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Samal', '11', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Angat', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balagtas', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baliuag', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bocaue', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bulacan', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bustos', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calumpit', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guiguinto', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hagonoy', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Malolos', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Marilao', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Meycauayan', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Norzagaray', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Obando', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pandi', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paombong', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Plaridel', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pulilan', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Ildefonso', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of San Jose Del Monte', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Miguel', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Rafael', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Maria', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Doña Remedios Trinidad', '12', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aliaga', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bongabon', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cabanatuan', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabiao', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carranglan', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cuyapo', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gabaldon', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Gapan', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('General Mamerto Natividad', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('General Tinio', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guimba', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jaen', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Laur', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Licab', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Llanera', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lupao', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Science City of Muñoz', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nampicuan', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Palayan', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pantabangan', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Peñaranda', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quezon', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rizal', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Antonio', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose City', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Leonardo', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Rosa', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Domingo', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talavera', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talugtug', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Zaragoza', '13', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Angeles', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Apalit', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Arayat', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacolor', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Candaba', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Floridablanca', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guagua', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lubao', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabalacat City', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Macabebe', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magalang', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Masantol', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mexico', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Minalin', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Porac', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of San Fernando', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Luis', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Simon', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Ana', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Rita', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Tomas', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sasmuan', '14', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Anao', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bamban', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Camiling', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Capas', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Concepcion', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gerona', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Paz', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mayantoc', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Moncada', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paniqui', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pura', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ramos', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Clemente', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Manuel', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Ignacia', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tarlac', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Victoria', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '15', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Botolan', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabangan', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Candelaria', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Castillejos', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Iba', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Masinloc', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Olongapo', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Palauig', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Antonio', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Felipe', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Marcelino', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Narciso', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Cruz', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Subic', '16', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baler', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Casiguran', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dilasag', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dinalungan', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dingalan', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dipaculao', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maria Aurora', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Luis', '17', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Agoncillo', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alitagtag', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balayan', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balete', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Batangas City', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bauan', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calaca', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calatagan', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cuenca', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ibaan', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Laurel', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lemery', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lian', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Lipa', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lobo', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabini', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malvar', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mataasnakahoy', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nasugbu', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Padre Garcia', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rosario', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Juan', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Luis', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Nicolas', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Pascual', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Teresita', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Sto. Tomas', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Taal', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talisay', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tanauan', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Taysan', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tingloy', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tuy', '18', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alfonso', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Amadeo', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bacoor', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carmona', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cavite', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Dasmariñas', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('General Emilio Aguinaldo', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of General Trias', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Imus', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Indang', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kawit', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magallanes', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maragondon', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mendez', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Naic', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Noveleta', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rosario', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Silang', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tagaytay', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tanza', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ternate', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Trece Martires', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gen. Mariano Alvarez', '19', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alaminos', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bay', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Biñan', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cabuyao', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Calamba', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calauan', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cavinti', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Famy', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kalayaan', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Liliw', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Los Baños', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Luisiana', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lumban', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabitac', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magdalena', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Majayjay', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nagcarlan', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paete', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pagsanjan', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pakil', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pangil', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pila', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rizal', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of San Pablo', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of San Pedro', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Cruz', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Maria', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Santa Rosa', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siniloan', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Victoria', '20', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Agdangan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alabat', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Atimonan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buenavista', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burdeos', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calauag', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Candelaria', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Catanauan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dolores', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('General Luna', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('General Nakar', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guinayangan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gumaca', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Infanta', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jomalig', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lopez', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lucban', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Lucena', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Macalelon', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mauban', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mulanay', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Padre Burgos', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pagbilao', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panukulan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Patnanungan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Perez', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pitogo', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Plaridel', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Polillo', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quezon', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Real', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sampaloc', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Andres', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Antonio', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Francisco', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Narciso', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sariaya', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagkawayan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tayabas', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tiaong', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Unisan', '21', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Angono', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Antipolo', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baras', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Binangonan', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cainta', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cardona', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jala-Jala', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rodriguez', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Morong', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pililla', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Mateo', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tanay', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Taytay', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Teresa', '22', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Boac', '23', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buenavista', '23', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gasan', '23', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mogpog', '23', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Cruz', '23', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Torrijos', '23', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Abra De Ilog', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calintaan', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Looc', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lubang', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magsaysay', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mamburao', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paluan', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rizal', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sablayan', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Cruz', '24', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baco', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bansud', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bongabong', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bulalacao', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Calapan', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gloria', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mansalay', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Naujan', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pinamalayan', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pola', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Puerto Galera', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Roxas', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Teodoro', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Socorro', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Victoria', '25', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aborlan', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Agutaya', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Araceli', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balabac', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bataraza', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Brooke S Point', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Busuanga', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cagayancillo', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Coron', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cuyo', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumaran', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('El Nido', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Linapacan', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magsaysay', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Narra', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Puerto Princesa', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quezon', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Roxas', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Vicente', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Taytay', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kalayaan', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Culion', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rizal', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sofronio Española', '26', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alcantara', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banton', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cajidiocan', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calatrava', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Concepcion', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Corcuera', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Looc', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magdiwang', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Odiongan', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Romblon', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Agustin', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Andres', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Fernando', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Fe', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ferrol', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Maria', '27', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacacay', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Camalig', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Daraga', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guinobatan', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jovellar', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Legazpi', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libon', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Ligao', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malilipot', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malinao', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manito', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Oas', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pio Duran', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Polangui', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rapu-Rapu', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Domingo', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tabaco', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tiwi', '28', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Basud', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Capalonga', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Daet', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Lorenzo Ruiz', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jose Panganiban', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Labo', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mercedes', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paracale', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Vicente', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Elena', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talisay', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Vinzons', '29', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baao', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balatan', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bato', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bombon', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buhi', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bula', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabusao', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calabanga', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Camaligan', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Canaman', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caramoan', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Del Gallego', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gainza', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Garchitorena', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Goa', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Iriga', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lagonoy', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libmanan', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lupi', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magarao', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Milaor', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Minalabac', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nabua', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Naga', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ocampo', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pamplona', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pasacao', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pili', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Presentacion', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ragay', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sagñay', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Fernando', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sipocot', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siruma', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tigaon', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tinambac', '30', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bagamanoc', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baras', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bato', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caramoran', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gigmoto', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pandan', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panganiban', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Andres', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Miguel', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Viga', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Virac', '31', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aroroy', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baleno', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balud', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Batuan', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cataingan', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cawayan', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Claveria', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dimasalang', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Esperanza', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mandaon', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Masbate', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Milagros', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mobo', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Monreal', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Palanas', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pio V. Corpuz', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Placer', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Fernando', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jacinto', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Pascual', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Uson', '32', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barcelona', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bulan', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bulusan', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Casiguran', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Castilla', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Donsol', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gubat', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Irosin', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Juban', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magallanes', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matnog', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pilar', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Prieto Diaz', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Magdalena', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Sorsogon', '33', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Altavas', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balete', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banga', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Batan', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buruanga', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ibajay', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kalibo', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lezo', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libacao', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Madalag', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Makato', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malay', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malinao', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nabas', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('New Washington', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Numancia', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tangalan', '34', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Anini-Y', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barbaza', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Belison', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bugasong', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caluya', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Culasi', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tobias Fornier', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hamtic', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Laua-An', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libertad', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pandan', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Patnongon', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Remigio', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sebaste', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibalom', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tibiao', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Valderrama', '35', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cuartero', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dao', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumalag', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumarao', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ivisan', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jamindan', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ma-Ayon', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mambusao', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panay', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panitan', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pilar', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pontevedra', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('President Roxas', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Roxas', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sapi-An', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sigma', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tapaz', '36', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ajuy', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alimodian', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Anilao', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Badiangan', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balasan', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banate', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barotac Nuevo', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barotac Viejo', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Batad', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bingawan', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabatuan', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calinog', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carles', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Concepcion', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dingle', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dueñas', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumangas', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Estancia', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guimbal', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Igbaras', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Iloilo', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Janiuay', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lambunao', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Leganes', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lemery', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Leon', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maasin', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Miagao', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mina', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('New Lucena', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Oton', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Passi', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pavia', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pototan', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Dionisio', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Enrique', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Joaquin', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Miguel', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Rafael', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Barbara', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sara', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tigbauan', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubungan', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Zarraga', '37', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bacolod', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bago', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Binalbagan', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cadiz', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calatrava', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Candoni', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cauayan', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Enrique B. Magalona', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Escalante', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Himamaylan', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hinigaran', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hinoba-an', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ilog', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Isabela', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Kabankalan', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of La Carlota', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Castellana', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manapla', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Moises Padilla', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Murcia', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pontevedra', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pulupandan', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Sagay', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of San Carlos', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Enrique', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Silay', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Sipalay', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Talisay', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Toboso', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Valladolid', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Victorias', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Salvador Benedicto', '38', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buenavista', '39', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jordan', '39', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nueva Valencia', '39', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Lorenzo', '39', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibunag', '39', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alburquerque', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alicia', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Anda', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Antequera', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baclayon', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balilihan', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Batuan', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bilar', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buenavista', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calape', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Candijay', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carmen', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Catigbian', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Clarin', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Corella', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cortes', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dagohoy', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Danao', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dauis', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dimiao', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Duero', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Garcia Hernandez', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guindulman', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Inabanga', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jagna', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Getafe', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lila', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Loay', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Loboc', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Loon', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabini', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maribojoc', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panglao', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pilar', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pres. Carlos P. Garcia', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sagbayan', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Miguel', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sevilla', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sierra Bullones', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sikatuna', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tagbilaran', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talibon', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Trinidad', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubigon', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ubay', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Valencia', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bien Unido', '40', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alcantara', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alcoy', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alegria', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aloguinsan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Argao', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Asturias', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Badian', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balamban', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bantayan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barili', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bogo', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Boljoon', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Borbon', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Carcar', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carmen', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Catmon', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cebu', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Compostela', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Consolacion', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cordova', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Daanbantayan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dalaguete', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Danao City', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumanjug', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ginatilan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Lapu-Lapu', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Liloan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Madridejos', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malabuyoc', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Mandaue', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Medellin', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Minglanilla', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Moalboal', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Naga', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Oslob', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pilar', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pinamungajan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Poro', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ronda', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Samboan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Fernando', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Francisco', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Remigio', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Fe', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santander', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibonga', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sogod', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tabogon', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tabuelan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Talisay', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Toledo', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tuburan', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tudela', '41', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Amlan', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ayungon', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacong', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bais', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Basay', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bayawan', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bindoy', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Canlaon', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dauin', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Dumaguete', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Guihulngan', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jimalalud', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Libertad', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabinay', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manjuyod', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pamplona', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Catalina', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siaton', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibulan', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tanjay', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tayasan', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Valencia', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Vallehermoso', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Zamboanguita', '42', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Enrique Villanueva', '43', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Larena', '43', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lazi', '43', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maria', '43', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Juan', '43', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siquijor', '43', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Arteche', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balangiga', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balangkayan', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Borongan', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Can-Avid', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dolores', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('General Macarthur', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Giporlos', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guiuan', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hernani', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jipapad', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lawaan', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Llorente', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maslog', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maydolong', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mercedes', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Oras', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quinapondan', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Salcedo', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Julian', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Policarpo', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sulat', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Taft', '44', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Abuyog', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alangalang', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Albuera', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Babatngon', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barugo', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bato', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Baybay', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burauen', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calubian', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Capoocan', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carigara', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dagami', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dulag', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hilongos', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hindang', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Inopacan', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Isabel', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jaro', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Javier', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Julita', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kananga', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Paz', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Leyte', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Macarthur', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mahaplag', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matag-Ob', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matalom', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mayorga', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Merida', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ormoc City', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Palo', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Palompon', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pastrana', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Miguel', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Fe', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tabango', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tabontabon', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tacloban', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tanauan', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tolosa', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tunga', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Villaba', '45', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Allen', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Biri', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bobon', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Capul', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Catarman', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Catubig', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gamay', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Laoang', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lapinig', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Las Navas', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lavezares', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mapanas', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mondragon', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Palapag', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pambujan', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rosario', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Antonio', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Roque', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Vicente', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Silvino Lobos', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Victoria', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lope De Vega', '46', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Almagro', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Basey', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Calbayog', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calbiga', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Catbalogan', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Daram', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gandara', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hinabangan', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jiabong', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Marabut', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matuguinao', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Motiong', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pinabacdao', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose De Buan', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Sebastian', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Margarita', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Rita', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Niño', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talalora', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tarangnan', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Villareal', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paranas', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Zumarraga', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagapul-An', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jorge', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pagsanghan', '47', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Anahawan', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bontoc', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hinunangan', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hinundayan', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libagon', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Liloan', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Maasin', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Macrohon', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malitbog', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Padre Burgos', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pintuyan', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Saint Bernard', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Francisco', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Juan', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Ricardo', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Silago', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sogod', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tomas Oppus', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Limasawa', '48', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Almeria', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Biliran', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabucgayan', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caibiran', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Culaba', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kawayan', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maripipi', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Naval', '49', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Dapitan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Dipolog', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Katipunan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Libertad', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Labason', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Liloy', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manukan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mutia', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Piñan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Polanco', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pres. Manuel A. Roxas', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rizal', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Salug', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sergio Osmeña Sr.', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siayan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibuco', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibutad', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sindangan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siocon', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sirawai', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tampilisan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jose Dalman', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gutalac', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baliguian', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Godod', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacungan', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kalawit', '50', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aurora', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bayog', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dimataling', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dinas', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumalinao', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dumingag', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kumalarang', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Labangan', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lapuyan', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mahayag', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Margosatubig', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Midsalip', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Molave', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Pagadian', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ramon Magsaysay', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Miguel', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Pablo', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tabina', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tambulig', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tukuran', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Zamboanga', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lakewood', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Josefina', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pitogo', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sominot', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Vincenzo A. Sagun', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guipos', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tigbao', '51', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alicia', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buug', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Diplahan', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Imelda', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ipil', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kabasalan', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabuhay', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malangas', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Naga', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Olutanga', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Payao', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Roseller Lim', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siay', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talusan', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Titay', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tungawan', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Isabela', '52', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baungon', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Damulog', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dangcagan', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Don Carlos', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Impasug-ong', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kadingilan', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kalilangan', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kibawe', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kitaotao', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lantapan', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libona', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Malaybalay', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malitbog', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manolo Fortich', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maramag', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pangantucan', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quezon', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Fernando', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sumilao', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talakag', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Valencia', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cabanglasan', '53', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Catarman', '54', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guinsiliban', '54', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mahinog', '54', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mambajao', '54', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sagay', '54', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacolod', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baloi', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baroy', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Iligan', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kapatagan', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sultan Naga Dimaporo', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kauswagan', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kolambugan', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lala', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Linamon', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magsaysay', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maigo', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matungao', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Munai', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nunungan', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pantao Ragat', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Poona Piagapo', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Salvador', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sapad', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagoloan', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tangcal', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubod', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pantar', '55', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aloran', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baliangao', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bonifacio', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calamba', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Clarin', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Concepcion', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jimenez', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lopez Jaena', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Oroquieta', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Ozamiz', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panaon', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Plaridel', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sapang Dalaga', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sinacaban', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tangub', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tudela', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Don Victoriano Chiongbian', '56', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alubijid', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balingasag', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balingoan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Binuangan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cagayan De Oro', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Claveria', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of El Salvador', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Gingoog', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gitagum', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Initao', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jasaan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kinoguitan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lagonglong', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Laguindingan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libertad', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lugait', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magsaysay', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manticao', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Medina', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Naawan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Opol', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Salay', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sugbongcogon', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagoloan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talisayan', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Villanueva', '57', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Asuncion', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carmen', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kapalong', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('New Corella', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Panabo', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Island Garden City of Samal', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Tomas', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tagum', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talaingod', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Braulio E. Dujali', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '58', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bansalan', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Davao', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Digos', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hagonoy', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kiblawan', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magsaysay', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malalag', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matanao', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Padada', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Cruz', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sulop', '59', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Baganga', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banaybanay', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Boston', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caraga', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cateel', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Governor Generoso', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lupon', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manay', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Mati', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tarragona', '60', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Compostela', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Laak', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mabini', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maco', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maragusan', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mawab', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Monkayo', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Montevista', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nabunturan', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('New Bataan', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pantukan', '61', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Don Marcelino', '62', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jose Abad Santos', '62', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malita', '62', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Maria', '62', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sarangani', '62', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alamada', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carmen', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kabacan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Kidapawan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libungan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magpet', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Makilala', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matalam', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Midsayap', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('M Lang', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pigkawayan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pikit', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('President Roxas', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tulunan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Antipas', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banisilan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aleosan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Arakan', '63', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banga', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of General Santos', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Koronadal', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Norala', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Polomolok', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Surallah', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tampakan', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tantangan', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('T Boli', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tupi', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santo Niño', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lake Sebu', '64', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bagumbayan', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Columbio', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Esperanza', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Isulan', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kalamansig', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lebak', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lutayan', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lambayong', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Palimbang', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('President Quirino', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tacurong', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sen. Ninoy Aquino', '65', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alabel', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Glan', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kiamba', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maasim', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maitum', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malapatan', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malungon', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cotabato City', '66', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manila', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mandaluyong City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Marikina City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pasig City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Quezon City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Juan City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Caloocan City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malabon City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Navotas City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Valenzuela City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Las Piñas City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Makati City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Muntinlupa City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Parañaque City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pasay City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pateros', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Taguig City', '1', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bangued', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Boliney', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bucay', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bucloc', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Daguioman', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Danglas', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dolores', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Paz', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lacub', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lagangilang', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lagayan', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Langiden', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Licuan-Baay', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Luba', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malibcong', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Manabo', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Peñarrubia', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pidigan', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pilar', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sallapadan', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Juan', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Quintin', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tayum', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tineg', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubo', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Villaviciosa', '67', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Atok', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Baguio', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bakun', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bokod', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buguias', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Itogon', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kabayan', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kapangan', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kibungan', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Trinidad', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mankayan', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sablan', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tuba', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tublay', '68', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Banaue', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hungduan', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kiangan', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lagawe', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lamut', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mayoyao', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alfonso Lista', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Aguinaldo', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hingyon', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tinoc', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Asipulo', '69', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balbalan', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lubuagan', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pasil', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pinukpuk', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rizal', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tabuk', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tanudan', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tinglayan', '70', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barlig', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bauko', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Besao', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bontoc', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Natonin', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paracelis', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sabangan', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sadanga', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sagada', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tadian', '71', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calanasan', '72', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Conner', '72', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Flora', '72', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kabugao', '72', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Luna', '72', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pudtol', '72', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Marcela', '72', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Lamitan', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lantawan', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maluso', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sumisip', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tipo-Tipo', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tuburan', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Akbar', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Al-Barka', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hadji Mohammad Ajul', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ungkaya Pukan', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hadji Muhtamad', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tabuan-Lasa', '73', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacolod-Kalawi', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balabagan', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Balindong', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bayang', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Binidayan', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bubong', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Butig', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ganassi', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kapai', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lumba-Bayabao', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lumbatan', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Madalum', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Madamba', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malabang', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Marantao', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Marawi', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Masiu', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mulondo', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pagayawan', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Piagapo', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Poona Bayabao', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pualas', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ditsaan-Ramain', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Saguiaran', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tamparan', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Taraka', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubaran', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tugaya', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Wao', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Marogong', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Calanogas', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buadiposo-Buntong', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maguing', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Picong', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lumbayanague', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Amai Manabilang', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagoloan Ii', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kapatagan', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sultan Dumalondong', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lumbaca-Unayan', '74', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Ampatuan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buldon', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buluan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Paglas', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Piang', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Odin Sinsuat', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Shariff Aguak', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Matanog', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pagalungan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Parang', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sultan Kudarat', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sultan Sa Barongis', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kabuntalan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Upi', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talayan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('South Upi', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barira', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gen. S.K. Pendatun', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mamasapano', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talitay', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pagagawan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Paglat', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sultan Mastura', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Guindulungan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Saudi-Ampatuan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Unsay', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Abdullah Sangki', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rajah Buayan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Blah T. Sinsuat', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Anggal Midtimbang', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mangudadatu', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pandag', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Northern Kabuntalan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Hoffer Ampatuan', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Datu Salibo', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Shariff Saydona Mustapha', '75', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Indanan', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jolo', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kalingalan Caluang', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Luuk', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Maimbung', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hadji Panglima Tahil', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Old Panamao', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pangutaran', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Parang', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pata', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Patikul', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Siasi', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talipao', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tapul', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tongkil', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panglima Estino', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lugus', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pandami', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Omar', '76', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Panglima Sugala', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bongao (Capital)', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mapun', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Simunul', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sitangkai', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('South Ubian', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tandubas', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Turtle Islands', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Languyan', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sapa-Sapa', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibutu', '77', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Buenavista', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Butuan', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Cabadbaran', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carmen', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Jabonga', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Kitcharao', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Las Nieves', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Magallanes', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Nasipit', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santiago', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubay', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Remedios T. Romualdez', '78', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bayugan', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bunawan', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Esperanza', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('La Paz', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Loreto', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Prosperidad', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Rosario', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Francisco', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Luis', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Josefa', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Talacogon', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Trento', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Veruela', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sibagat', '79', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Alegria', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bacuag', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Burgos', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Claver', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dapa', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Del Carmen', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('General Luna', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Gigaquit', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Mainit', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Malimono', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Pilar', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Placer', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Benito', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Francisco', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Isidro', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Santa Monica', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Sison', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Socorro', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Surigao', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagana-An', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubod', '80', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Barobo', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Bayabas', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Bislig', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cagwait', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cantilan', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carmen', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Carrascal', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cortes', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Hinatuan', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lanuza', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lianga', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Lingig', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Madrid', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Marihatag', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Agustin', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Miguel', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tagbina', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tago', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('City of Tandag', '81', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Basilisa', '82', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Cagdianao', '82', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Dinagat', '82', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Libjo', '82', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Loreto', '82', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('San Jose', '82', '1');
INSERT INTO city (city_name, state_id, last_log_by) VALUES ('Tubajon', '82', '1');

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

INSERT INTO currency (currency_name, symbol, shorthand, last_log_by)
VALUES
    ('Philippine Peso', '₱', 'PHP', '1'),
    ('United States Dollar', '$', 'USD', '1'),
    ('Japanese Yen', '¥', 'JPY', '1'),
    ('South Korean Won', '₩', 'KRW', '1'),
    ('Euro', '€', 'EUR', '1'),
    ('Pound Sterling', '£', 'GBP', '1');

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

INSERT INTO email_setting (email_setting_name, email_setting_description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_password, mail_encryption, mail_from_name, mail_from_email, last_log_by) VALUES ('Security Email Setting', '
Email setting for security emails.', 'smtp.hostinger.com', '465', '1', '0', 'encore-noreply@encorefinancials.com', 'UsDpF0dYRC6M9v0tT3MHq%2BlrRJu01%2Fb95Dq%2BAeCfu2Y%3D', 'ssl', 'encore-noreply@encorefinancials.com', 'encore-noreply@encorefinancials.com' , '1');

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

/*INSERT INTO `notification_setting` (`notification_setting_id`, `notification_setting_name`, `notification_setting_description`, `system_notification`, `email_notification`, `sms_notification`, `system_notification_title`, `system_notification_message`, `email_notification_subject`, `email_notification_body`, `sms_notification_message`, `last_log_by`) VALUES
(1, 'Login OTP', 'Notification setting for Login OTP received by the users.', 0, 1, 0, NULL, NULL, 'Login OTP - Secure Access to Your Account', '<p>To ensure the security of your account, we have generated a unique One-Time Password (OTP) for you to use during the login process. Please use the following OTP to access your account:</p>\r\n<p>OTP: <strong>{OTP_CODE}</strong></p>\r\n<p>Please note that this OTP is valid for &lt;strong&gt;5 minutes&lt;/strong&gt;. Once you have logged in successfully, we recommend enabling two-factor authentication for an added layer of security.</p>\r\n<p>If you did not initiate this login or believe it was sent to you in error, please disregard this email and delete it immediately. Your account\'s security remains our utmost priority.</p>\r\n<p>&nbsp;</p>\r\n<p>Note: This is an automatically generated email. Please do not reply to this address.</p>', NULL, 1),
(2, 'Forgot Password', 'Notification setting when the user initiates forgot password.', 0, 1, 0, NULL, NULL, 'Password Reset Request - Action Required', '<p>We have received a request to reset your password. To ensure the security of your account, please follow the instructions below:</p>\r\n<p>1. Click on the link below to reset your password:</p>\r\n<p><a href=\"{RESET_LINK}\"><strong>Reset Password</strong></a></p>\r\n<p>2. If the button does not work, you can copy and paste the following link into your browser\'s address bar:</p>\r\n<p><strong>{RESET_LINK}</strong></p>\r\n<p>Please note that this link is time-sensitive and will expire after <strong>10 minutes</strong>. If you do not reset your password within this timeframe, you may need to request another password reset.</p>\r\n<p>If you did not initiate this password reset request or believe it was sent to you in error, please disregard this email and delete it immediately. Your account\'s security remains our utmost priority.</p>\r\n<p>&nbsp;</p>\r\n<p>Note: This is an automatically generated email. Please do not reply to this address.</p>', NULL, 1);*/

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

INSERT INTO department (department_name, last_log_by) VALUES ('Data Center', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Table */

CREATE TABLE job_position(
	job_position_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	job_position_name VARCHAR(100) NOT NULL,
	job_position_description VARCHAR(2000) NOT NULL,
	recruitment_status TINYINT(1) DEFAULT 0,
	department_id INT UNSIGNED,
	expected_new_employees INT NOT NULL DEFAULT 0,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (department_id) REFERENCES department(department_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX job_position_index_job_position_id ON job_position(job_position_id);
CREATE INDEX job_position_index_department_id ON job_position(department_id);

INSERT INTO job_position (job_position_name, job_position_description, department_id, last_log_by) VALUES ('Data Center Staff', 'Data Center Staff', '1', '1');

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

INSERT INTO job_level (current_level, rank, functional_level, last_log_by)
VALUES
    ('I', 'Trainee', 'Unskilled', '1'),
    ('II', 'Staff', 'Skilled', '1'),
    ('III', 'Assistant', 'Clerical/Regular', '1'),
    ('IV', 'Associate', 'Senior Staff', '1'),
    ('V', 'Assistant Supervisor', 'Supervising Staff', '1'),
    ('VI', 'Supervisor', 'Junior Officer', '1'),
    ('VII', 'Assistant Manager', 'Junior Officer', '1'),
    ('VIII', 'Manager', 'Regular Officer', '1'),
    ('IX', 'Senior Manager', 'Regular Officer', '1'),
    ('X', ' Asst. Vice President', 'Senior Officer', '1'),
    ('XI', ' Vice President', 'Senior Officer', '1'),
    ('XIII', ' Senior Vice President', 'Executive', '1'),
    ('XIV', ' Executive Vice President', 'Executive', '1'),
    ('XV', 'President', 'Executive', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Type Table */

CREATE TABLE employee_type(
	employee_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	employee_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX employee_type_index_employee_type_id ON employee_type(employee_type_id);

INSERT INTO employee_type (employee_type_name, last_log_by)
VALUES
    ('Full-Time', '1'),
    ('Part-Time', '1'),
    ('Temporary', '1'),
    ('Contractual', '1'),
    ('Intern', '1'),
    ('Freelancer', '1'),
    ('Consultant', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Departure Reason Table */

CREATE TABLE departure_reason(
	departure_reason_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	departure_reason_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX departure_reason_index_departure_reason_id ON departure_reason(departure_reason_id);

INSERT INTO departure_reason (departure_reason_name, last_log_by)
VALUES
    ('Job Relocation', '1'),
    ('Retirement', '1'),
    ('Pursuing Higher Education', '1'),
    ('Family Reunion', '1'),
    ('Marriage Abroad', '1'),
    ('Travel for Tourism', '1'),
    ('Temporary Work Assignment', '1'),
    ('Seeking Asylum', '1'),
    ('Healthcare Treatment Abroad', '1'),
    ('Joining a Spouse or Family Member', '1'),
    ('Change in Immigration Status', '1'),
    ('Returning to Home Country', '1'),
    ('Expiry of Visa or Permit', '1'),
    ('Deportation', '1'),
    ('Naturalization or Citizenship in Another Country', '1'),
    ('Business Opportunities Abroad', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* ID Type Table */

CREATE TABLE id_type(
	id_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	id_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX id_type_index_id_type_id ON id_type(id_type_id);

INSERT INTO id_type (id_type_name, last_log_by)
VALUES
    ('Philippine Passport', '1'),
    ('Driver\'s License', '1'),
    ('Unified Multi-Purpose ID (UMID)', '1'),
    ('Social Security System (SSS) ID', '1'),
    ('Government Service Insurance System (GSIS) ID', '1'),
    ('Postal ID', '1'),
    ('Professional Regulation Commission (PRC) ID', '1'),
    ('Voter\'s ID', '1'),
    ('National Bureau of Investigation (NBI) Clearance', '1'),
    ('Police Clearance', '1'),
    ('Barangay ID', '1'),
    ('Senior Citizen ID', '1'),
    ('PhilHealth ID', '1'),
    ('Home Development Mutual Fund (Pag-IBIG) ID', '1'),
    ('Company ID', '1'),
    ('Student ID', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Gender Table */

CREATE TABLE gender(
	gender_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	gender_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX gender_index_gender_id ON gender(gender_id);

INSERT INTO gender (gender_name, last_log_by)
VALUES
    ('Male', '1'),
    ('Female', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Religion Table */

CREATE TABLE religion(
	religion_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	religion_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX religion_index_religion_id ON religion(religion_id);

INSERT INTO religion (religion_name, last_log_by)
VALUES
    ('Christianity', '1'),
    ('Islam', '1'),
    ('Hinduism', '1'),
    ('Buddhism', '1'),
    ('Sikhism', '1'),
    ('Judaism', '1'),
    ('Baháʼí Faith', '1'),
    ('Jainism', '1'),
    ('Shintoism', '1'),
    ('Taoism', '1'),
    ('Zoroastrianism', '1'),
    ('African Traditional Religions', '1'),
    ('Native American Spirituality', '1'),
    ('Rastafarianism', '1'),
    ('Scientology', '1'),
    ('Atheism', '1'),
    ('Agnosticism', '1'),
    ('Wicca', '1'),
    ('Druidry', '1'),
    ('Confucianism', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Nationality Table */

CREATE TABLE nationality(
	nationality_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	nationality_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX nationality_index_nationality_id ON nationality(nationality_id);

INSERT INTO nationality (nationality_name, last_log_by)
VALUES
    ('American', '1'),
    ('British', '1'),
    ('Canadian', '1'),
    ('Australian', '1'),
    ('German', '1'),
    ('French', '1'),
    ('Japanese', '1'),
    ('Chinese', '1'),
    ('Indian', '1'),
    ('Mexican', '1'),
    ('Brazilian', '1'),
    ('Russian', '1'),
    ('Italian', '1'),
    ('Spanish', '1'),
    ('Argentinian', '1'),
    ('Swiss', '1'),
    ('Dutch', '1'),
    ('Swedish', '1'),
    ('Norwegian', '1'),
    ('Danish', '1'),
    ('Finnish', '1'),
    ('Greek', '1'),
    ('Irish', '1'),
    ('Turkish', '1'),
    ('Egyptian', '1'),
    ('Saudi Arabian', '1'),
    ('Emirati', '1'),
    ('South Korean', '1'),
    ('Thai', '1'),
    ('Vietnamese', '1'),
    ('Filipino', '1'),
    ('Indonesian', '1'),
    ('Malaysian', '1'),
    ('Singaporean', '1'),
    ('Chilean', '1'),
    ('Colombian', '1'),
    ('Peruvian', '1'),
    ('Venezuelan', '1'),
    ('Panamanian', '1'),
    ('Costa Rican', '1'),
    ('Jamaican', '1'),
    ('Trinidadian and Tobagonian', '1'),
    ('Nigerian', '1'),
    ('South African', '1'),
    ('Kenyan', '1'),
    ('Ghanaian', '1'),
    ('Ethiopian', '1'),
    ('Moroccan', '1'),
    ('Tunisian', '1'),
    ('Algerian', '1'),
    ('Iraqi', '1'),
    ('Iranian', '1'),
    ('Afghan', '1'),
    ('Pakistani', '1'),
    ('Bangladeshi', '1'),
    ('Sri Lankan', '1'),
    ('Nepali', '1'),
    ('Bhutanese', '1'),
    ('Japanese', '1'),
    ('Korean', '1'),
    ('Vietnamese', '1'),
    ('Cambodian', '1'),
    ('Laotian', '1'),
    ('Myanmar (Burmese)', '1'),
    ('Mongolian', '1'),
    ('Kazakh', '1'),
    ('Uzbek', '1'),
    ('Turkmen', '1'),
    ('Kyrgyz', '1'),
    ('Tajik', '1'),
    ('Azerbaijani', '1'),
    ('Armenian', '1'),
    ('Georgian', '1'),
    ('Ukrainian', '1'),
    ('Belarusian', '1'),
    ('Estonian', '1'),
    ('Latvian', '1'),
    ('Lithuanian', '1'),
    ('Polish', '1'),
    ('Czech', '1'),
    ('Slovak', '1'),
    ('Hungarian', '1'),
    ('Romanian', '1'),
    ('Bulgarian', '1'),
    ('Croatian', '1'),
    ('Serbian', '1'),
    ('Bosnian and Herzegovinian', '1'),
    ('Slovenian', '1'),
    ('Macedonian', '1'),
    ('Cypriot', '1'),
    ('Maltese', '1'),
    ('Icelandic', '1'),
    ('Scottish', '1'),
    ('Welsh', '1'),
    ('Catalan', '1'),
    ('Portuguese', '1'),
    ('Luxembourgish', '1'),
    ('Austrian', '1'),
    ('Belgian', '1'),
    ('Lichtensteiner', '1'),
    ('Monacan', '1'),
    ('Andorran', '1'),
    ('San Marinese', '1'),
    ('Vatican', '1'),
    ('Montenegrin', '1'),
    ('Albanian', '1'),
    ('Moldovan', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Relation Table */

CREATE TABLE relation(
	relation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	relation_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX relation_index_relation_id ON relation(relation_id);

INSERT INTO relation (relation_name, last_log_by)
VALUES
    ('Parent', '1'),
    ('Child', '1'),
    ('Spouse', '1'),
    ('Sibling', '1'),
    ('Grandparent', '1'),
    ('Grandchild', '1'),
    ('Aunt', '1'),
    ('Uncle', '1'),
    ('Cousin', '1'),
    ('Friend', '1'),
    ('Partner', '1'),
    ('Roommate', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Civil Status Table */

CREATE TABLE civil_status(
	civil_status_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	civil_status_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX civil_status_index_civil_status_id ON civil_status(civil_status_id);

INSERT INTO civil_status (civil_status_name, last_log_by)
VALUES
    ('Single', '1'),
    ('Married', '1'),
    ('Divorced', '1'),
    ('Widowed', '1'),
    ('Separated', '1'),
    ('In a Relationship', '1'),
    ('Engaged', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Blood Type Table */

CREATE TABLE blood_type(
	blood_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	blood_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX blood_type_index_blood_type_id ON blood_type(blood_type_id);

INSERT INTO blood_type (blood_type_name, last_log_by)
VALUES
    ('A+', '1'),
    ('A-', '1'),
    ('B+', '1'),
    ('B-', '1'),
    ('AB+', '1'),
    ('AB-', '1'),
    ('O+', '1'),
    ('O-', '1');

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

INSERT INTO bank (bank_name, bank_identifier_code, last_log_by)
VALUES
    ('Banco de Oro (BDO)', '010530667', '1'),
    ('Metrobank', '010269996', '1'),
    ('Land Bank of the Philippines', '010350025', '1'),
    ('Bank of the Philippine Islands (BPI)', '010040018', '1'),
    ('Philippine National Bank (PNB)', '010080010', '1'),
    ('Security Bank', '010140015', '1'),
    ('UnionBank of the Philippines', '010419995', '1'),
    ('Development Bank of the Philippines (DBP)', '010590018', '1'),
    ('EastWest Bank', '010620014', '1'),
    ('China Banking Corporation (Chinabank)', '010100013', '1'),
    ('RCBC (Rizal Commercial Banking Corporation)', '010280014', '1'),
    ('Maybank Philippines', '010220016', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Holiday Type Table */

CREATE TABLE holiday_type(
	holiday_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	holiday_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX holiday_type_index_holiday_type_id ON holiday_type(holiday_type_id);

INSERT INTO holiday_type (holiday_type_name, last_log_by)
VALUES
    ('Regular Holiday', '1'),
    ('Local Holiday', '1'),
    ('Special Non-Working Holiday', '1'),
    ('Special Working Holiday', '1'),
    ('Special (One-Time) Holiday', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Schedule Type Table */

CREATE TABLE work_schedule_type(
	work_schedule_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	work_schedule_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX work_schedule_type_index_work_schedule_type_id ON work_schedule_type(work_schedule_type_id);

INSERT INTO work_schedule_type (work_schedule_type_name, last_log_by) VALUES ('Fixed', '1');
INSERT INTO work_schedule_type (work_schedule_type_name, last_log_by) VALUES ('Flexible', '1');
INSERT INTO work_schedule_type (work_schedule_type_name, last_log_by) VALUES ('Shifting', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Address Type Table */

CREATE TABLE address_type(
	address_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	address_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX address_type_index_address_type_id ON address_type(address_type_id);

INSERT INTO address_type (address_type_name, last_log_by)
VALUES
    ('Home Address', '1'),
    ('Work Address', '1'),
    ('Mailing Address', '1'),
    ('Billing Address', '1'),
    ('Shipping Address', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Educational Stage Table */

CREATE TABLE educational_stage(
	educational_stage_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	educational_stage_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX educational_stage_index_educational_stage_id ON educational_stage(educational_stage_id);

INSERT INTO educational_stage (educational_stage_name, last_log_by)
VALUES
    ('Preschool', '1'),
    ('Primary School', '1'),
    ('Junior High School', '1'),
    ('Senior High School', '1'),
    ('College', '1'),
    ('Postgraduate', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Information Type Table */

CREATE TABLE contact_information_type(
	contact_information_type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	contact_information_type_name VARCHAR(100) NOT NULL,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX contact_information_type_index_contact_information_type_id ON contact_information_type(contact_information_type_id);

INSERT INTO contact_information_type (contact_information_type_name, last_log_by)
VALUES
    ('Personal', '1'),
    ('Work', '1');

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

INSERT INTO bank_account_type (bank_account_type_name, last_log_by)
VALUES
    ('Savings Account', '1'),
    ('Checking Account', '1'),
    ('Time Deposit Account', '1'),
    ('Salary Account', '1'),
    ('Business Account', '1');

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Table */

CREATE TABLE contact(
	contact_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	user_id INT UNSIGNED,
	is_employee TINYINT(1) DEFAULT 0,
	is_applicant TINYINT(1) DEFAULT 0,
	is_customer TINYINT(1) DEFAULT 0,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX contact_index_contact_id ON contact(contact_id);
CREATE INDEX contact_index_user_id ON contact(user_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Personal Information Table */

CREATE TABLE personal_information(
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
    birthday DATE,
    birth_place VARCHAR(1000),
    height FLOAT,
    weight FLOAT,
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (contact_id) REFERENCES contact(contact_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);
CREATE INDEX personal_information_index_contact_id ON personal_information(contact_id);
CREATE INDEX personal_information_index_civil_status_id ON personal_information(civil_status_id);
CREATE INDEX personal_information_index_gender_id ON personal_information(gender_id);
CREATE INDEX personal_information_index_religion_id ON personal_information(religion_id);
CREATE INDEX personal_information_index_blood_type_id ON personal_information(blood_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employment Information Table */

CREATE TABLE employment_information(
	contact_id INT UNSIGNED PRIMARY KEY NOT NULL,
	badge_id VARCHAR(500) NOT NULL,
    company_id INT UNSIGNED,
    employee_type_id INT UNSIGNED,
	department_id INT UNSIGNED,
	job_position_id INT UNSIGNED,
	job_level_id INT UNSIGNED,
	branch_id INT UNSIGNED,
	employment_status TINYINT(1) NOT NULL DEFAULT 1,
    permanency_date DATE,
    onboard_date DATE,
    offboard_date DATE,
    departure_reason_id INT UNSIGNED,
    detailed_departure_reason VARCHAR(5000),
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (contact_id) REFERENCES contact(contact_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX employment_information_index_id ON employment_information(contact_id);
CREATE INDEX employment_information_index_company_id ON employment_information(company_id);
CREATE INDEX employment_information_index_employee_type_id ON employment_information(employee_type_id);
CREATE INDEX employment_information_index_department_id ON employment_information(department_id);
CREATE INDEX employment_information_index_job_position_id ON employment_information(job_position_id);
CREATE INDEX employment_information_index_job_level_id ON employment_information(job_level_id);
CREATE INDEX employment_information_index_branch_id ON employment_information(branch_id);
CREATE INDEX employment_information_index_departure_reason_id ON employment_information(departure_reason_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Information Table */

CREATE TABLE contact_information(
    contact_information_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	contact_id INT UNSIGNED NOT NULL,
	contact_information_type_id INT UNSIGNED NOT NULL,
	mobile VARCHAR(20),
	telephone VARCHAR(20),
	email VARCHAR(100),
    last_log_by INT UNSIGNED NOT NULL,
    FOREIGN KEY (contact_id) REFERENCES contact(contact_id),
    FOREIGN KEY (last_log_by) REFERENCES users(user_id)
);

CREATE INDEX contact_information_index_id ON contact_information(contact_id);
CREATE INDEX contact_information_index_contact_information_type_id ON contact_information_type(contact_information_type_id);

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table */



/* ----------------------------------------------------------------------------------------------------------------------------- */