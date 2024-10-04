DELIMITER //

/* Users Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* UI Customization Setting Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Role Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Menu Group Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Menu Item Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Menu Item Access Right Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Action Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Action Access Rights Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* File Type Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* File Extension Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Upload Setting Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Interface Setting Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* System Setting Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Country Table Triggers */

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
        VALUES ('state', NEW.country_id, audit_log, NEW.last_log_by, NOW());
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
    VALUES ('state', NEW.country_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* State Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* City Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Currency Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Company Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Notification Setting Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Zoom API Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Branch Table Triggers */

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

    IF NEW.branch_id <> OLD.branch_id THEN
        SET audit_log = CONCAT(audit_log, "Branch ID: ", OLD.branch_id, " -> ", NEW.branch_id, "<br/>");
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

    IF NEW.branch_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Branch ID: ", NEW.branch_id);
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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Department Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Responsibility Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Requirement Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Position Qualification Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Job Level Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employee Type Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Departure Reason Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* ID Type Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Gender Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Religion Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Nationality Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Relation Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Civil Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Blood Type Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Bank Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Holiday Type Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Schedule Type Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Address Type Table Triggers */

CREATE TRIGGER address_type_trigger_update
AFTER UPDATE ON address_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.address_type_name <> OLD.address_type_name THEN
        SET audit_log = CONCAT(audit_log, "Address Type Name: ", OLD.address_type_name, " -> ", NEW.address_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('address_type', NEW.address_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER address_type_trigger_insert
AFTER INSERT ON address_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Address type created. <br/>';

    IF NEW.address_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Address Type Name: ", NEW.address_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('address_type', NEW.address_type_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Educational Stage Table Triggers */

CREATE TRIGGER educational_stage_trigger_update
AFTER UPDATE ON educational_stage
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.educational_stage_name <> OLD.educational_stage_name THEN
        SET audit_log = CONCAT(audit_log, "Educational Stage Name: ", OLD.educational_stage_name, " -> ", NEW.educational_stage_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('educational_stage', NEW.educational_stage_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER educational_stage_trigger_insert
AFTER INSERT ON educational_stage
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Educational stage created. <br/>';

    IF NEW.educational_stage_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Educational Stage Name: ", NEW.educational_stage_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('educational_stage', NEW.educational_stage_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Information Type Table Triggers */

CREATE TRIGGER contact_information_type_trigger_update
AFTER UPDATE ON contact_information_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.contact_information_type_name <> OLD.contact_information_type_name THEN
        SET audit_log = CONCAT(audit_log, "Contact Information Type Name: ", OLD.contact_information_type_name, " -> ", NEW.contact_information_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_information_type', NEW.contact_information_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_information_type_trigger_insert
AFTER INSERT ON contact_information_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact information type created. <br/>';

    IF NEW.contact_information_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Contact Information Type Name: ", NEW.contact_information_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_information_type', NEW.contact_information_type_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Work Schedule Table Triggers */

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table Triggers */

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
        SET audit_log = CONCAT(audit_log, "Day Period: ", OLD.day_period, " -> ", NEW.day_period, "<br/>");
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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Bank Account Type Table Triggers */

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
    DECLARE audit_log TEXT DEFAULT 'Bank account type created. <br/>';

    IF NEW.bank_account_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Account Type Name: ", NEW.bank_account_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('bank_account_type', NEW.bank_account_type_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Personal Information Table Triggers */

CREATE TRIGGER personal_information_trigger_update
AFTER UPDATE ON personal_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.file_as <> OLD.file_as THEN
        SET audit_log = CONCAT(audit_log, "File As: ", OLD.file_as, " -> ", NEW.file_as, "<br/>");
    END IF;

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

CREATE TRIGGER personal_information_trigger_insert
AFTER INSERT ON personal_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Personal information created. <br/>';

    IF NEW.file_as <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>First As: ", NEW.file_as);
    END IF;

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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Employment Information Table Triggers */

CREATE TRIGGER employment_information_trigger_update
AFTER UPDATE ON employment_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.badge_id <> OLD.badge_id THEN
        SET audit_log = CONCAT(audit_log, "Badge ID: ", OLD.badge_id, " -> ", NEW.badge_id, "<br/>");
    END IF;

    IF NEW.employee_type_id <> OLD.employee_type_id THEN
        SET audit_log = CONCAT(audit_log, "Employee Type ID: ", OLD.employee_type_id, " -> ", NEW.employee_type_id, "<br/>");
    END IF;

    IF NEW.company_id <> OLD.company_id THEN
        SET audit_log = CONCAT(audit_log, "Company ID: ", OLD.company_id, " -> ", NEW.company_id, "<br/>");
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

    IF NEW.employment_status <> OLD.employment_status THEN
        SET audit_log = CONCAT(audit_log, "Employment Status ID: ", OLD.employment_status, " -> ", NEW.employment_status, "<br/>");
    END IF;
    
    IF NEW.biometrics_id <> OLD.biometrics_id THEN
        SET audit_log = CONCAT(audit_log, "Biometrics ID: ", OLD.biometrics_id, " -> ", NEW.biometrics_id, "<br/>");
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

CREATE TRIGGER employment_information_trigger_insert
AFTER INSERT ON employment_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Employment information created. <br/>';

    IF NEW.badge_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Badge ID: ", NEW.badge_id);
    END IF;

    IF NEW.employee_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Employee Type ID: ", NEW.employee_type_id);
    END IF;

    IF NEW.company_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Company ID: ", NEW.company_id);
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

    IF NEW.employment_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Employement Status: ", NEW.employment_status);
    END IF;
    
    IF NEW.biometrics_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Biometrics ID: ", NEW.biometrics_id);
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

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Information Table Triggers */

CREATE TRIGGER contact_information_trigger_update
AFTER UPDATE ON contact_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.contact_information_type_id <> OLD.contact_information_type_id THEN
        SET audit_log = CONCAT(audit_log, "Contact Information Type ID: ", OLD.contact_information_type_id, " -> ", NEW.contact_information_type_id, "<br/>");
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
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_information', NEW.contact_information_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_information_trigger_insert
AFTER INSERT ON contact_information
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact information created. <br/>';

    IF NEW.contact_information_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Contact Information Type ID: ", NEW.contact_information_type_id);
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

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_information', NEW.contact_information_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Identification Table Triggers */

CREATE TRIGGER contact_identification_trigger_update
AFTER UPDATE ON contact_identification
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.id_type_id <> OLD.id_type_id THEN
        SET audit_log = CONCAT(audit_log, "ID Type ID: ", OLD.id_type_id, " -> ", NEW.id_type_id, "<br/>");
    END IF;

    IF NEW.id_number <> OLD.id_number THEN
        SET audit_log = CONCAT(audit_log, "ID Number: ", OLD.id_number, " -> ", NEW.id_number, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_identification', NEW.contact_identification_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_identification_trigger_insert
AFTER INSERT ON contact_identification
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact identification created. <br/>';

    IF NEW.id_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>ID Type ID: ", NEW.id_type_id);
    END IF;

    IF NEW.id_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>ID Number: ", NEW.id_number);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_identification', NEW.contact_identification_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Educational Background Table Triggers */

CREATE TRIGGER contact_educational_background_trigger_update
AFTER UPDATE ON contact_educational_background
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.educational_stage_id <> OLD.educational_stage_id THEN
        SET audit_log = CONCAT(audit_log, "Educational Stage ID: ", OLD.educational_stage_id, " -> ", NEW.educational_stage_id, "<br/>");
    END IF;

    IF NEW.institution_name <> OLD.institution_name THEN
        SET audit_log = CONCAT(audit_log, "Institution Name: ", OLD.institution_name, " -> ", NEW.institution_name, "<br/>");
    END IF;

    IF NEW.degree_earned <> OLD.degree_earned THEN
        SET audit_log = CONCAT(audit_log, "Degree Earned: ", OLD.degree_earned, " -> ", NEW.degree_earned, "<br/>");
    END IF;

    IF NEW.field_of_study <> OLD.field_of_study THEN
        SET audit_log = CONCAT(audit_log, "Field of Study: ", OLD.field_of_study, " -> ", NEW.field_of_study, "<br/>");
    END IF;

    IF NEW.start_month <> OLD.start_month THEN
        SET audit_log = CONCAT(audit_log, "Start Month: ", OLD.start_month, " -> ", NEW.start_month, "<br/>");
    END IF;

    IF NEW.start_year <> OLD.start_year THEN
        SET audit_log = CONCAT(audit_log, "Start Year: ", OLD.start_year, " -> ", NEW.start_year, "<br/>");
    END IF;

    IF NEW.end_month <> OLD.end_month THEN
        SET audit_log = CONCAT(audit_log, "End Month: ", OLD.end_month, " -> ", NEW.end_month, "<br/>");
    END IF;

    IF NEW.end_year <> OLD.end_year THEN
        SET audit_log = CONCAT(audit_log, "End Year: ", OLD.end_year, " -> ", NEW.end_year, "<br/>");
    END IF;

    IF NEW.course_highlights <> OLD.course_highlights THEN
        SET audit_log = CONCAT(audit_log, "Course Highlights: ", OLD.course_highlights, " -> ", NEW.course_highlights, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_educational_background', NEW.contact_educational_background_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_educational_background_trigger_insert
AFTER INSERT ON contact_educational_background
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact educational background created. <br/>';

    IF NEW.educational_stage_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Educational Stage ID: ", NEW.educational_stage_id);
    END IF;

    IF NEW.institution_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Institition Name: ", NEW.institution_name);
    END IF;

    IF NEW.degree_earned <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Degree Earned: ", NEW.degree_earned);
    END IF;

    IF NEW.field_of_study <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Field of Study: ", NEW.field_of_study);
    END IF;

    IF NEW.start_month <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Mont: ", NEW.start_month);
    END IF;

    IF NEW.start_year <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Year: ", NEW.start_year);
    END IF;

    IF NEW.end_month <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Month: ", NEW.end_month);
    END IF;

    IF NEW.end_year <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Year: ", NEW.end_year);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_educational_background', NEW.contact_educational_background_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Family Background Table Triggers */

CREATE TRIGGER contact_family_background_trigger_update
AFTER UPDATE ON contact_family_background
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.family_name <> OLD.family_name THEN
        SET audit_log = CONCAT(audit_log, "Family Name: ", OLD.family_name, " -> ", NEW.family_name, "<br/>");
    END IF;

    IF NEW.relation_id <> OLD.relation_id THEN
        SET audit_log = CONCAT(audit_log, "Relation ID: ", OLD.relation_id, " -> ", NEW.relation_id, "<br/>");
    END IF;

    IF NEW.birthday <> OLD.birthday THEN
        SET audit_log = CONCAT(audit_log, "Birthday: ", OLD.birthday, " -> ", NEW.birthday, "<br/>");
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
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_family_background', NEW.contact_family_background_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_family_background_trigger_insert
AFTER INSERT ON contact_family_background
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact family background created. <br/>';

    IF NEW.family_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Family Name: ", NEW.family_name);
    END IF;

    IF NEW.relation_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Relation ID: ", NEW.relation_id);
    END IF;

    IF NEW.birthday <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Birthday: ", NEW.birthday);
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

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_family_background', NEW.contact_family_background_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Emergency Contact Table Triggers */

CREATE TRIGGER contact_emergency_contact_trigger_update
AFTER UPDATE ON contact_emergency_contact
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.emergency_contact_name <> OLD.emergency_contact_name THEN
        SET audit_log = CONCAT(audit_log, "Emergency Contact Name: ", OLD.emergency_contact_name, " -> ", NEW.emergency_contact_name, "<br/>");
    END IF;

    IF NEW.relation_id <> OLD.relation_id THEN
        SET audit_log = CONCAT(audit_log, "Relation ID: ", OLD.relation_id, " -> ", NEW.relation_id, "<br/>");
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
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_emergency_contact', NEW.contact_emergency_contact_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_emergency_contact_trigger_insert
AFTER INSERT ON contact_emergency_contact
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact emergency contact created. <br/>';

    IF NEW.emergency_contact_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Emergency Contact Name: ", NEW.emergency_contact_name);
    END IF;

    IF NEW.relation_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Relation ID: ", NEW.relation_id);
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

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_emergency_contact', NEW.contact_emergency_contact_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Training Table Triggers */

CREATE TRIGGER contact_training_trigger_update
AFTER UPDATE ON contact_training
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.training_name <> OLD.training_name THEN
        SET audit_log = CONCAT(audit_log, "Training Name: ", OLD.training_name, " -> ", NEW.training_name, "<br/>");
    END IF;

    IF NEW.training_date <> OLD.training_date THEN
        SET audit_log = CONCAT(audit_log, "Training Date: ", OLD.training_date, " -> ", NEW.training_date, "<br/>");
    END IF;

    IF NEW.training_location <> OLD.training_location THEN
        SET audit_log = CONCAT(audit_log, "Training Location: ", OLD.training_location, " -> ", NEW.training_location, "<br/>");
    END IF;

    IF NEW.training_provider <> OLD.training_provider THEN
        SET audit_log = CONCAT(audit_log, "Training Provider: ", OLD.training_provider, " -> ", NEW.training_provider, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_training', NEW.contact_training_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_training_trigger_insert
AFTER INSERT ON contact_training
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact training created. <br/>';

    IF NEW.training_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Training Name: ", NEW.training_date);
    END IF;

    IF NEW.training_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Training Date: ", NEW.training_date);
    END IF;

    IF NEW.training_location <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Training Location: ", NEW.training_location);
    END IF;

    IF NEW.training_provider <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Training Provider: ", NEW.training_provider);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_training', NEW.contact_training_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Skills Table Triggers */

CREATE TRIGGER contact_skills_trigger_update
AFTER UPDATE ON contact_skills
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.skill_name <> OLD.skill_name THEN
        SET audit_log = CONCAT(audit_log, "Skill Name: ", OLD.skill_name, " -> ", NEW.skill_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_skills', NEW.contact_skills_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_skills_trigger_insert
AFTER INSERT ON contact_skills
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact skill created. <br/>';

    IF NEW.skill_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Skill Name: ", NEW.skill_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_skills', NEW.contact_skills_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Talents Table Triggers */

CREATE TRIGGER contact_talents_trigger_update
AFTER UPDATE ON contact_talents
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.talent_name <> OLD.talent_name THEN
        SET audit_log = CONCAT(audit_log, "Talent Name: ", OLD.talent_name, " -> ", NEW.talent_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_talents', NEW.contact_talents_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_talents_trigger_insert
AFTER INSERT ON contact_talents
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact talents created. <br/>';

    IF NEW.talent_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Talent Name: ", NEW.talent_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_talents', NEW.contact_talents_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Hobby Table Triggers */

CREATE TRIGGER contact_hobby_trigger_update
AFTER UPDATE ON contact_hobby
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.hobby_name <> OLD.hobby_name THEN
        SET audit_log = CONCAT(audit_log, "Hobby Name: ", OLD.hobby_name, " -> ", NEW.hobby_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_hobby', NEW.contact_hobby_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_hobby_trigger_insert
AFTER INSERT ON contact_hobby
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact hobby created. <br/>';

    IF NEW.hobby_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Hobby Name: ", NEW.hobby_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_hobby', NEW.contact_hobby_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Employment History Table Triggers */

CREATE TRIGGER contact_employment_history_trigger_update
AFTER UPDATE ON contact_employment_history
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.company <> OLD.company THEN
        SET audit_log = CONCAT(audit_log, "Company: ", OLD.company, " -> ", NEW.company, "<br/>");
    END IF;

    IF NEW.address <> OLD.address THEN
        SET audit_log = CONCAT(audit_log, "Address: ", OLD.address, " -> ", NEW.address, "<br/>");
    END IF;

    IF NEW.last_position_held <> OLD.last_position_held THEN
        SET audit_log = CONCAT(audit_log, "Last Position Held: ", OLD.last_position_held, " -> ", NEW.last_position_held, "<br/>");
    END IF;

    IF NEW.start_month <> OLD.start_month THEN
        SET audit_log = CONCAT(audit_log, "Start Month: ", OLD.start_month, " -> ", NEW.start_month, "<br/>");
    END IF;

    IF NEW.start_year <> OLD.start_year THEN
        SET audit_log = CONCAT(audit_log, "Start Year: ", OLD.start_year, " -> ", NEW.start_year, "<br/>");
    END IF;

    IF NEW.end_month <> OLD.end_month THEN
        SET audit_log = CONCAT(audit_log, "End Month: ", OLD.end_month, " -> ", NEW.end_month, "<br/>");
    END IF;

    IF NEW.end_year <> OLD.end_year THEN
        SET audit_log = CONCAT(audit_log, "End Year: ", OLD.end_year, " -> ", NEW.end_year, "<br/>");
    END IF;

    IF NEW.basic_function <> OLD.basic_function THEN
        SET audit_log = CONCAT(audit_log, "Basic Function: ", OLD.basic_function, " -> ", NEW.basic_function, "<br/>");
    END IF;

    IF NEW.starting_salary <> OLD.starting_salary THEN
        SET audit_log = CONCAT(audit_log, "Starting Salary: ", OLD.starting_salary, " -> ", NEW.starting_salary, "<br/>");
    END IF;

    IF NEW.final_salary <> OLD.final_salary THEN
        SET audit_log = CONCAT(audit_log, "Final Salary: ", OLD.final_salary, " -> ", NEW.final_salary, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_employment_history', NEW.contact_employment_history_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_employment_history_trigger_insert
AFTER INSERT ON contact_employment_history
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact employment history created. <br/>';

    IF NEW.company <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Company: ", NEW.company);
    END IF;

    IF NEW.address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Address: ", NEW.address);
    END IF;

    IF NEW.last_position_held <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Last Position Held: ", NEW.last_position_held);
    END IF;

    IF NEW.start_month <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Mont: ", NEW.start_month);
    END IF;

    IF NEW.start_year <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Year: ", NEW.start_year);
    END IF;

    IF NEW.end_month <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Month: ", NEW.end_month);
    END IF;

    IF NEW.end_year <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Year: ", NEW.end_year);
    END IF;

    IF NEW.basic_function <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Basic Function: ", NEW.basic_function);
    END IF;

    IF NEW.starting_salary <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Starting Salary: ", NEW.starting_salary);
    END IF;

    IF NEW.final_salary <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Final Salary: ", NEW.final_salary);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_employment_history', NEW.contact_employment_history_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact License Table Triggers */

CREATE TRIGGER contact_license_trigger_update
AFTER UPDATE ON contact_license
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.license_name <> OLD.license_name THEN
        SET audit_log = CONCAT(audit_log, "License Name: ", OLD.license_name, " -> ", NEW.license_name, "<br/>");
    END IF;

    IF NEW.issuing_organization <> OLD.issuing_organization THEN
        SET audit_log = CONCAT(audit_log, "Issuing Organization: ", OLD.issuing_organization, " -> ", NEW.issuing_organization, "<br/>");
    END IF;

    IF NEW.start_month <> OLD.start_month THEN
        SET audit_log = CONCAT(audit_log, "Start Month: ", OLD.start_month, " -> ", NEW.start_month, "<br/>");
    END IF;

    IF NEW.start_year <> OLD.start_year THEN
        SET audit_log = CONCAT(audit_log, "Start Year: ", OLD.start_year, " -> ", NEW.start_year, "<br/>");
    END IF;

    IF NEW.end_month <> OLD.end_month THEN
        SET audit_log = CONCAT(audit_log, "End Month: ", OLD.end_month, " -> ", NEW.end_month, "<br/>");
    END IF;

    IF NEW.end_year <> OLD.end_year THEN
        SET audit_log = CONCAT(audit_log, "End Year: ", OLD.end_year, " -> ", NEW.end_year, "<br/>");
    END IF;

    IF NEW.description <> OLD.description THEN
        SET audit_log = CONCAT(audit_log, "Description: ", OLD.description, " -> ", NEW.description, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_license', NEW.contact_license_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_license_trigger_insert
AFTER INSERT ON contact_license
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact license created. <br/>';

    IF NEW.license_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>License Name: ", NEW.license_name);
    END IF;

    IF NEW.issuing_organization <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Issuing Organization: ", NEW.issuing_organization);
    END IF;

    IF NEW.start_month <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Mont: ", NEW.start_month);
    END IF;

    IF NEW.start_year <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Year: ", NEW.start_year);
    END IF;

    IF NEW.end_month <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Month: ", NEW.end_month);
    END IF;

    IF NEW.end_year <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Year: ", NEW.end_year);
    END IF;

    IF NEW.description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Description: ", NEW.description);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_license', NEW.contact_license_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Language Table Triggers */

CREATE TRIGGER contact_language_trigger_update
AFTER UPDATE ON contact_language
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.language_id <> OLD.language_id THEN
        SET audit_log = CONCAT(audit_log, "Language ID: ", OLD.language_id, " -> ", NEW.language_id, "<br/>");
    END IF;

    IF NEW.language_proficiency_id <> OLD.language_proficiency_id THEN
        SET audit_log = CONCAT(audit_log, "Language Proficiency ID: ", OLD.language_proficiency_id, " -> ", NEW.language_proficiency_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_language', NEW.contact_language_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_language_trigger_insert
AFTER INSERT ON contact_language
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact language created. <br/>';

    IF NEW.language_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Language ID: ", NEW.language_id);
    END IF;

    IF NEW.language_proficiency_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Language Proficiency ID: ", NEW.language_proficiency_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_language', NEW.contact_language_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Language Table Triggers */

CREATE TRIGGER language_trigger_update
AFTER UPDATE ON language
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.language_name <> OLD.language_name THEN
        SET audit_log = CONCAT(audit_log, "Language Name: ", OLD.language_name, " -> ", NEW.language_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('language', NEW.language_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER language_trigger_insert
AFTER INSERT ON language
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Language created. <br/>';

    IF NEW.language_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Language Name: ", NEW.language_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('language', NEW.language_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Language Proficiency Table Triggers */

CREATE TRIGGER language_proficiency_trigger_update
AFTER UPDATE ON language_proficiency
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.language_proficiency_name <> OLD.language_proficiency_name THEN
        SET audit_log = CONCAT(audit_log, "Language Proficiency Name: ", OLD.language_proficiency_name, " -> ", NEW.language_proficiency_name, "<br/>");
    END IF;

    IF NEW.description <> OLD.description THEN
        SET audit_log = CONCAT(audit_log, "Description: ", OLD.description, " -> ", NEW.description, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('language_proficiency', NEW.language_proficiency_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER language_proficiency_trigger_insert
AFTER INSERT ON language_proficiency
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Language proficiency created. <br/>';

    IF NEW.language_proficiency_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Language Proficiency Name: ", NEW.language_proficiency_name);
    END IF;

    IF NEW.description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Description: ", NEW.description);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('language_proficiency', NEW.language_proficiency_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Bank Table Triggers */

CREATE TRIGGER contact_bank_trigger_update
AFTER UPDATE ON contact_bank
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.bank_id <> OLD.bank_id THEN
        SET audit_log = CONCAT(audit_log, "Bank ID: ", OLD.bank_id, " -> ", NEW.bank_id, "<br/>");
    END IF;

    IF NEW.bank_account_type_id <> OLD.bank_account_type_id THEN
        SET audit_log = CONCAT(audit_log, "Bank Account Type ID: ", OLD.bank_account_type_id, " -> ", NEW.bank_account_type_id, "<br/>");
    END IF;

    IF NEW.account_number <> OLD.account_number THEN
        SET audit_log = CONCAT(audit_log, "Account Number: ", OLD.account_number, " -> ", NEW.account_number, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_bank', NEW.contact_bank_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_bank_trigger_insert
AFTER INSERT ON contact_bank
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact bank created. <br/>';

    IF NEW.bank_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank ID: ", NEW.bank_id);
    END IF;

    IF NEW.bank_account_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Account Type ID: ", NEW.bank_account_type_id);
    END IF;

    IF NEW.account_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Account Number: ", NEW.account_number);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_bank', NEW.contact_bank_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Attendance Setting Table Triggers */

CREATE TRIGGER attendance_setting_trigger_update
AFTER UPDATE ON attendance_setting
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.attendance_setting_name <> OLD.attendance_setting_name THEN
        SET audit_log = CONCAT(audit_log, "Attendance Setting Name: ", OLD.attendance_setting_name, " -> ", NEW.attendance_setting_name, "<br/>");
    END IF;

    IF NEW.attendance_setting_description <> OLD.attendance_setting_description THEN
        SET audit_log = CONCAT(audit_log, "Attendance Setting Description: ", OLD.attendance_setting_description, " -> ", NEW.attendance_setting_description, "<br/>");
    END IF;

    IF NEW.value <> OLD.value THEN
        SET audit_log = CONCAT(audit_log, "Value: ", OLD.value, " -> ", NEW.value, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('attendance_setting', NEW.attendance_setting_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER attendance_setting_trigger_insert
AFTER INSERT ON attendance_setting
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Attendance setting created. <br/>';

    IF NEW.attendance_setting_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Attendance Setting Name: ", NEW.attendance_setting_name);
    END IF;

    IF NEW.attendance_setting_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Attendance Setting Description: ", NEW.attendance_setting_description);
    END IF;

    IF NEW.value <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Value: ", NEW.value);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('attendance_setting', NEW.attendance_setting_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Attendance Table Triggers */

CREATE TRIGGER attendance_trigger_update
AFTER UPDATE ON attendance
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.check_in <> OLD.check_in THEN
        SET audit_log = CONCAT(audit_log, "Check In: ", OLD.check_in, " -> ", NEW.check_in, "<br/>");
    END IF;

    IF NEW.check_in_location <> OLD.check_in_location THEN
        SET audit_log = CONCAT(audit_log, "Check In Location: ", OLD.check_in_location, " -> ", NEW.check_in_location, "<br/>");
    END IF;

    IF NEW.check_in_by <> OLD.check_in_by THEN
        SET audit_log = CONCAT(audit_log, "Check In By: ", OLD.check_in_by, " -> ", NEW.check_in_by, "<br/>");
    END IF;

    IF NEW.check_in_mode <> OLD.check_in_mode THEN
        SET audit_log = CONCAT(audit_log, "Check In Mode: ", OLD.check_in_mode, " -> ", NEW.check_in_mode, "<br/>");
    END IF;

    IF NEW.check_in_notes <> OLD.check_in_notes THEN
        SET audit_log = CONCAT(audit_log, "Check In Notes: ", OLD.check_in_notes, " -> ", NEW.check_in_notes, "<br/>");
    END IF;

    IF NEW.check_out <> OLD.check_out THEN
        SET audit_log = CONCAT(audit_log, "Check Out: ", OLD.check_out, " -> ", NEW.check_out, "<br/>");
    END IF;

    IF NEW.check_out_location <> OLD.check_out_location THEN
        SET audit_log = CONCAT(audit_log, "Check Out Location: ", OLD.check_out_location, " -> ", NEW.check_out_location, "<br/>");
    END IF;

    IF NEW.check_out_by <> OLD.check_out_by THEN
        SET audit_log = CONCAT(audit_log, "Check Out By: ", OLD.check_out_by, " -> ", NEW.check_out_by, "<br/>");
    END IF;

    IF NEW.check_out_mode <> OLD.check_out_mode THEN
        SET audit_log = CONCAT(audit_log, "Check Out Mode: ", OLD.check_out_mode, " -> ", NEW.check_out_mode, "<br/>");
    END IF;

    IF NEW.check_out_notes <> OLD.check_out_notes THEN
        SET audit_log = CONCAT(audit_log, "Check Out Notes: ", OLD.check_out_notes, " -> ", NEW.check_out_notes, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('attendance', NEW.attendance_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER attendance_trigger_insert
AFTER INSERT ON attendance
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Attendance created. <br/>';

    IF NEW.check_in <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check In: ", NEW.check_in);
    END IF;

    IF NEW.check_in_location <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check In Location: ", NEW.check_in_location);
    END IF;

    IF NEW.check_in_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check In By: ", NEW.check_in_by);
    END IF;

    IF NEW.check_in_mode <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check In Mode: ", NEW.check_in_mode);
    END IF;

    IF NEW.check_in_notes <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check In Notes: ", NEW.check_in_notes);
    END IF;

    IF NEW.check_out <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check Out: ", NEW.check_out);
    END IF;

    IF NEW.check_out_location <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check Out Location: ", NEW.check_out_location);
    END IF;

    IF NEW.check_out_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check Out By: ", NEW.check_out_by);
    END IF;

    IF NEW.check_out_mode <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check Out Mode: ", NEW.check_out_mode);
    END IF;

    IF NEW.check_out_notes <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check Out Notes: ", NEW.check_out_notes);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('attendance', NEW.attendance_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Transmittal Table Triggers */

CREATE TRIGGER transmittal_trigger_update
AFTER UPDATE ON transmittal
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.transmittal_description <> OLD.transmittal_description THEN
        SET audit_log = CONCAT(audit_log, "Description: ", OLD.transmittal_description, " -> ", NEW.transmittal_description, "<br/>");
    END IF;

    IF NEW.created_by <> OLD.created_by THEN
        SET audit_log = CONCAT(audit_log, "Created By: ", OLD.created_by, " -> ", NEW.created_by, "<br/>");
    END IF;

    IF NEW.transmitter_name <> OLD.transmitter_name THEN
        SET audit_log = CONCAT(audit_log, "Transmitter: ", OLD.transmitter_name, " -> ", NEW.transmitter_name, "<br/>");
    END IF;

    IF NEW.transmitter_department_name <> OLD.transmitter_department_name THEN
        SET audit_log = CONCAT(audit_log, "Transmitter Department: ", OLD.transmitter_department_name, " -> ", NEW.transmitter_department_name, "<br/>");
    END IF;

    IF NEW.receiver_name <> OLD.receiver_name THEN
        SET audit_log = CONCAT(audit_log, "Receiver: ", OLD.receiver_name, " -> ", NEW.receiver_name, "<br/>");
    END IF;

    IF NEW.receiver_department_name <> OLD.receiver_department_name THEN
        SET audit_log = CONCAT(audit_log, "Receiver Department: ", OLD.receiver_department_name, " -> ", NEW.receiver_department_name, "<br/>");
    END IF;

    IF NEW.transmittal_status <> OLD.transmittal_status THEN
        SET audit_log = CONCAT(audit_log, "Transmittal Status: ", OLD.transmittal_status, " -> ", NEW.transmittal_status, "<br/>");
    END IF;

    IF NEW.transmittal_date <> OLD.transmittal_date THEN
        SET audit_log = CONCAT(audit_log, "Transmittal Date: ", OLD.transmittal_date, " -> ", NEW.transmittal_date, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('transmittal', NEW.transmittal_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER transmittal_trigger_insert
AFTER INSERT ON transmittal
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Transmittal created. <br/>';

    IF NEW.transmittal_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Description: ", NEW.transmittal_description);
    END IF;

    IF NEW.created_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Created By: ", NEW.created_by);
    END IF;

    IF NEW.transmitter_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transmitter: ", NEW.transmitter_name);
    END IF;

    IF NEW.transmitter_department_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transmitter Department: ", NEW.transmitter_department_name);
    END IF;

    IF NEW.receiver_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Receiver Name: ", NEW.receiver_name);
    END IF;

    IF NEW.receiver_department_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Receiver Department: ", NEW.receiver_department_name);
    END IF;

    IF NEW.transmittal_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transmittal Status: ", NEW.transmittal_status);
    END IF;

    IF NEW.transmittal_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transmittal Date: ", NEW.transmittal_date);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('transmittal', NEW.transmittal_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Document Category Table Triggers */

CREATE TRIGGER document_category_trigger_update
AFTER UPDATE ON document_category
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.document_category_name <> OLD.document_category_name THEN
        SET audit_log = CONCAT(audit_log, "Document Category Name: ", OLD.document_category_name, " -> ", NEW.document_category_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('document_category', NEW.document_category_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER document_category_trigger_insert
AFTER INSERT ON document_category
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Document category created. <br/>';

    IF NEW.document_category_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Category Name: ", NEW.document_category_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('document_category', NEW.document_category_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table Triggers */

CREATE TRIGGER document_trigger_update
AFTER UPDATE ON document
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.document_name <> OLD.document_name THEN
        SET audit_log = CONCAT(audit_log, "Document Name: ", OLD.document_name, " -> ", NEW.document_name, "<br/>");
    END IF;

    IF NEW.document_description <> OLD.document_description THEN
        SET audit_log = CONCAT(audit_log, "Document Description: ", OLD.document_description, " -> ", NEW.document_description, "<br/>");
    END IF;

    IF NEW.author <> OLD.author THEN
        SET audit_log = CONCAT(audit_log, "Author: ", OLD.author, " -> ", NEW.author, "<br/>");
    END IF;

    IF NEW.document_category_id <> OLD.document_category_id THEN
        SET audit_log = CONCAT(audit_log, "Document Category ID: ", OLD.document_category_id, " -> ", NEW.document_category_id, "<br/>");
    END IF;

    IF NEW.document_extension <> OLD.document_extension THEN
        SET audit_log = CONCAT(audit_log, "Document Extension: ", OLD.document_extension, " -> ", NEW.document_extension, "<br/>");
    END IF;

    IF NEW.document_size <> OLD.document_size THEN
        SET audit_log = CONCAT(audit_log, "Document Size: ", OLD.document_size, " -> ", NEW.document_size, "<br/>");
    END IF;

    IF NEW.document_status <> OLD.document_status THEN
        SET audit_log = CONCAT(audit_log, "Document Status: ", OLD.document_status, " -> ", NEW.document_status, "<br/>");
    END IF;

    IF NEW.document_version <> OLD.document_version THEN
        SET audit_log = CONCAT(audit_log, "Document Version: ", OLD.document_version, " -> ", NEW.document_version, "<br/>");
    END IF;

    IF NEW.upload_date <> OLD.upload_date THEN
        SET audit_log = CONCAT(audit_log, "Upload Date: ", OLD.upload_date, " -> ", NEW.upload_date, "<br/>");
    END IF;

    IF NEW.publish_date <> OLD.publish_date THEN
        SET audit_log = CONCAT(audit_log, "Publish Date: ", OLD.publish_date, " -> ", NEW.publish_date, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('document', NEW.document_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER document_trigger_insert
AFTER INSERT ON document
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Document created. <br/>';

    IF NEW.document_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Name: ", NEW.document_name);
    END IF;

    IF NEW.document_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Description: ", NEW.document_description);
    END IF;

    IF NEW.author <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Author: ", NEW.author);
    END IF;

    IF NEW.document_category_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Category ID: ", NEW.document_category_id);
    END IF;

    IF NEW.document_extension <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Extension: ", NEW.document_extension);
    END IF;

    IF NEW.document_size <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Size: ", NEW.document_size);
    END IF;

    IF NEW.document_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Status: ", NEW.document_status);
    END IF;

    IF NEW.document_version <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Version: ", NEW.document_version);
    END IF;

    IF NEW.upload_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Document Date: ", NEW.upload_date);
    END IF;

    IF NEW.publish_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Publish Date: ", NEW.publish_date);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('document', NEW.document_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Document Authorizer Table Triggers */

CREATE TRIGGER document_authorizer_trigger_update
AFTER UPDATE ON document_authorizer
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.department_id <> OLD.department_id THEN
        SET audit_log = CONCAT(audit_log, "Department ID: ", OLD.department_id, " -> ", NEW.department_id, "<br/>");
    END IF;

    IF NEW.authorizer_id <> OLD.authorizer_id THEN
        SET audit_log = CONCAT(audit_log, "Authorizer ID: ", OLD.authorizer_id, " -> ", NEW.authorizer_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('document_authorizer', NEW.document_authorizer_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER document_authorizer_trigger_insert
AFTER INSERT ON document_authorizer
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Document authorizer created. <br/>';

    IF NEW.department_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Department ID: ", NEW.department_id);
    END IF;

    IF NEW.authorizer_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Authorizer ID: ", NEW.authorizer_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('document_authorizer', NEW.document_authorizer_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Body Type Table Triggers */

CREATE TRIGGER body_type_trigger_update
AFTER UPDATE ON body_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.body_type_name <> OLD.body_type_name THEN
        SET audit_log = CONCAT(audit_log, "Body Type Name: ", OLD.body_type_name, " -> ", NEW.body_type_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('body_type', NEW.body_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER body_type_trigger_insert
AFTER INSERT ON body_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Body type created. <br/>';

    IF NEW.body_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Body Type Name: ", NEW.body_type_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('body_type', NEW.body_type_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Color Table Triggers */

CREATE TRIGGER color_trigger_update
AFTER UPDATE ON color
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.color_name <> OLD.color_name THEN
        SET audit_log = CONCAT(audit_log, "Color Name: ", OLD.color_name, " -> ", NEW.color_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('color', NEW.color_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER color_trigger_insert
AFTER INSERT ON color
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Color created. <br/>';

    IF NEW.color_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Color Name: ", NEW.color_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('color', NEW.color_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Unit Category Table Triggers */

CREATE TRIGGER unit_category_trigger_update
AFTER UPDATE ON unit_category
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.unit_category_name <> OLD.unit_category_name THEN
        SET audit_log = CONCAT(audit_log, "Unit Category Name: ", OLD.unit_category_name, " -> ", NEW.unit_category_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('unit_category', NEW.unit_category_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER unit_category_trigger_insert
AFTER INSERT ON unit_category
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Unit category created. <br/>';

    IF NEW.unit_category_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Unit Category Name: ", NEW.unit_category_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('unit_category', NEW.unit_category_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Unit Table Triggers */

CREATE TRIGGER unit_trigger_update
AFTER UPDATE ON unit
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.unit_name <> OLD.unit_name THEN
        SET audit_log = CONCAT(audit_log, "Unit Name: ", OLD.unit_name, " -> ", NEW.unit_name, "<br/>");
    END IF;

    IF NEW.short_name <> OLD.short_name THEN
        SET audit_log = CONCAT(audit_log, "Short Name: ", OLD.short_name, " -> ", NEW.short_name, "<br/>");
    END IF;

    IF NEW.unit_category_id <> OLD.unit_category_id THEN
        SET audit_log = CONCAT(audit_log, "Unit Category ID: ", OLD.unit_category_id, " -> ", NEW.unit_category_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('unit', NEW.unit_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER unit_trigger_insert
AFTER INSERT ON unit
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Unit created. <br/>';

    IF NEW.unit_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Unit Name: ", NEW.unit_name);
    END IF;

    IF NEW.short_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Short Name: ", NEW.short_name);
    END IF;

    IF NEW.unit_category_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Unit Category ID: ", NEW.unit_category_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('unit', NEW.unit_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Warehouse Table Triggers */

CREATE TRIGGER warehouse_trigger_update
AFTER UPDATE ON warehouse
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.warehouse_name <> OLD.warehouse_name THEN
        SET audit_log = CONCAT(audit_log, "Warehouse Name: ", OLD.warehouse_name, " -> ", NEW.warehouse_name, "<br/>");
    END IF;

    IF NEW.address <> OLD.address THEN
        SET audit_log = CONCAT(audit_log, "Address: ", OLD.address, " -> ", NEW.address, "<br/>");
    END IF;

    IF NEW.city_id <> OLD.city_id THEN
        SET audit_log = CONCAT(audit_log, "City ID: ", OLD.city_id, " -> ", NEW.city_id, "<br/>");
    END IF;

    IF NEW.company_id <> OLD.company_id THEN
        SET audit_log = CONCAT(audit_log, "Company ID: ", OLD.company_id, " -> ", NEW.company_id, "<br/>");
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
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('warehouse', NEW.warehouse_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER warehouse_trigger_insert
AFTER INSERT ON warehouse
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Warehouse created. <br/>';

    IF NEW.warehouse_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Warehouse Name: ", NEW.warehouse_name);
    END IF;

    IF NEW.address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Address: ", NEW.address);
    END IF;

    IF NEW.city_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>City ID: ", NEW.city_id);
    END IF;

    IF NEW.company_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Company ID: ", NEW.company_id);
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

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('warehouse', NEW.warehouse_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Product Category Table Triggers */

CREATE TRIGGER product_category_trigger_update
AFTER UPDATE ON product_category
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.product_category_name <> OLD.product_category_name THEN
        SET audit_log = CONCAT(audit_log, "Product Category Name: ", OLD.product_category_name, " -> ", NEW.product_category_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('product_category', NEW.product_category_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER product_category_trigger_insert
AFTER INSERT ON product_category
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Product category created. <br/>';

    IF NEW.product_category_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Category Name: ", NEW.product_category_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('product_category', NEW.product_category_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Product Subcategory Table Triggers */

CREATE TRIGGER product_subcategory_trigger_update
AFTER UPDATE ON product_subcategory
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.product_subcategory_name <> OLD.product_subcategory_name THEN
        SET audit_log = CONCAT(audit_log, "Product Subcategory Name: ", OLD.product_subcategory_name, " -> ", NEW.product_subcategory_name, "<br/>");
    END IF;

    IF NEW.product_subcategory_code <> OLD.product_subcategory_code THEN
        SET audit_log = CONCAT(audit_log, "Product Subcategory Code: ", OLD.product_subcategory_code, " -> ", NEW.product_subcategory_code, "<br/>");
    END IF;

    IF NEW.product_category_id <> OLD.product_category_id THEN
        SET audit_log = CONCAT(audit_log, "Product Category ID: ", OLD.product_category_id, " -> ", NEW.product_category_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('product_subcategory', NEW.product_subcategory_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER product_subcategory_trigger_insert
AFTER INSERT ON product_subcategory
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Product subcategory created. <br/>';

    IF NEW.product_subcategory_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Subcategory Name: ", NEW.product_subcategory_name);
    END IF;

    IF NEW.product_subcategory_code <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Subcategory Code: ", NEW.product_subcategory_code);
    END IF;

    IF NEW.product_category_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Category ID: ", NEW.product_category_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('product_subcategory', NEW.product_subcategory_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER sales_proposal_additional_job_order_trigger_update
AFTER UPDATE ON sales_proposal_additional_job_order
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.job_order_number <> OLD.job_order_number THEN
        SET audit_log = CONCAT(audit_log, "Job Order Number: ", OLD.job_order_number, " -> ", NEW.job_order_number, "<br/>");
    END IF;

    IF NEW.job_order_date <> OLD.job_order_date THEN
        SET audit_log = CONCAT(audit_log, "Job Order Date: ", OLD.job_order_date, " -> ", NEW.job_order_date, "<br/>");
    END IF;

    IF NEW.particulars <> OLD.particulars THEN
        SET audit_log = CONCAT(audit_log, "Particulars: ", OLD.particulars, " -> ", NEW.particulars, "<br/>");
    END IF;

    IF NEW.cost <> OLD.cost THEN
        SET audit_log = CONCAT(audit_log, "Cost: ", OLD.cost, " -> ", NEW.cost, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER sales_proposal_additional_job_order_trigger_insert
AFTER INSERT ON sales_proposal_additional_job_order
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Additional Job Order created. <br/>';

    IF NEW.job_order_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Job Order Number: ", NEW.job_order_number);
    END IF;

    IF NEW.job_order_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Subcategory Code: ", NEW.job_order_date);
    END IF;

    IF NEW.particulars <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Particulars: ", NEW.particulars);
    END IF;

    IF NEW.cost <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cost: ", NEW.cost);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Product Table Triggers */

CREATE TRIGGER product_trigger_update
AFTER UPDATE ON product
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.product_category_id <> OLD.product_category_id THEN
        SET audit_log = CONCAT(audit_log, "Product Category ID: ", OLD.product_category_id, " -> ", NEW.product_category_id, "<br/>");
    END IF;

    IF NEW.product_subcategory_id <> OLD.product_subcategory_id THEN
        SET audit_log = CONCAT(audit_log, "Product Subcategory ID: ", OLD.product_subcategory_id, " -> ", NEW.product_subcategory_id, "<br/>");
    END IF;

    IF NEW.product_status <> OLD.product_status THEN
        SET audit_log = CONCAT(audit_log, "Product Status: ", OLD.product_status, " -> ", NEW.product_status, "<br/>");
    END IF;

    IF NEW.stock_number <> OLD.stock_number THEN
        SET audit_log = CONCAT(audit_log, "Stock Number: ", OLD.stock_number, " -> ", NEW.stock_number, "<br/>");
    END IF;

    IF NEW.engine_number <> OLD.engine_number THEN
        SET audit_log = CONCAT(audit_log, "Engine Number: ", OLD.engine_number, " -> ", NEW.engine_number, "<br/>");
    END IF;

    IF NEW.chassis_number <> OLD.chassis_number THEN
        SET audit_log = CONCAT(audit_log, "Chassis Number: ", OLD.chassis_number, " -> ", NEW.chassis_number, "<br/>");
    END IF;

    IF NEW.plate_number <> OLD.plate_number THEN
        SET audit_log = CONCAT(audit_log, "Plate Number: ", OLD.plate_number, " -> ", NEW.plate_number, "<br/>");
    END IF;

    IF NEW.description <> OLD.description THEN
        SET audit_log = CONCAT(audit_log, "Description: ", OLD.description, " -> ", NEW.description, "<br/>");
    END IF;

    IF NEW.warehouse_id <> OLD.warehouse_id THEN
        SET audit_log = CONCAT(audit_log, "Warehouse ID: ", OLD.warehouse_id, " -> ", NEW.warehouse_id, "<br/>");
    END IF;

    IF NEW.body_type_id <> OLD.body_type_id THEN
        SET audit_log = CONCAT(audit_log, "Body ID: ", OLD.body_type_id, " -> ", NEW.body_type_id, "<br/>");
    END IF;

    IF NEW.length <> OLD.length THEN
        SET audit_log = CONCAT(audit_log, "Length: ", OLD.length, " -> ", NEW.length, "<br/>");
    END IF;

    IF NEW.length_unit <> OLD.length_unit THEN
        SET audit_log = CONCAT(audit_log, "Length Unit: ", OLD.length_unit, " -> ", NEW.length_unit, "<br/>");
    END IF;

    IF NEW.running_hours <> OLD.running_hours THEN
        SET audit_log = CONCAT(audit_log, "Running Hours: ", OLD.running_hours, " -> ", NEW.running_hours, "<br/>");
    END IF;

    IF NEW.mileage <> OLD.mileage THEN
        SET audit_log = CONCAT(audit_log, "Mileage: ", OLD.mileage, " -> ", NEW.mileage, "<br/>");
    END IF;

    IF NEW.color_id <> OLD.color_id THEN
        SET audit_log = CONCAT(audit_log, "Color ID: ", OLD.color_id, " -> ", NEW.color_id, "<br/>");
    END IF;

    IF NEW.product_price <> OLD.product_price THEN
        SET audit_log = CONCAT(audit_log, "Product Price: ", OLD.product_price, " -> ", NEW.product_price, "<br/>");
    END IF;

    IF NEW.remarks <> OLD.remarks THEN
        SET audit_log = CONCAT(audit_log, "Remarks: ", OLD.remarks, " -> ", NEW.remarks, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('product', NEW.product_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER product_trigger_insert
AFTER INSERT ON product
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Product created. <br/>';

    IF NEW.product_category_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Category ID: ", NEW.product_category_id);
    END IF;

    IF NEW.product_subcategory_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Subcategory ID: ", NEW.product_subcategory_id);
    END IF;

    IF NEW.product_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Status: ", NEW.product_status);
    END IF;

    IF NEW.stock_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Stock Number: ", NEW.stock_number);
    END IF;

    IF NEW.chassis_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Chassis Number: ", NEW.chassis_number);
    END IF;

    IF NEW.plate_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Plate Number: ", NEW.plate_number);
    END IF;

    IF NEW.description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Description: ", NEW.description);
    END IF;

    IF NEW.warehouse_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Warehouse ID: ", NEW.warehouse_id);
    END IF;

    IF NEW.body_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Body Type ID: ", NEW.body_type_id);
    END IF;

    IF NEW.length <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Length: ", NEW.length);
    END IF;

    IF NEW.length_unit <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Length Unit: ", NEW.length_unit);
    END IF;

    IF NEW.running_hours <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Running Hours: ", NEW.running_hours);
    END IF;

    IF NEW.mileage <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mileage: ", NEW.mileage);
    END IF;

    IF NEW.color_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Color ID: ", NEW.color_id);
    END IF;

    IF NEW.product_price <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Price: ", NEW.product_price);
    END IF;

    IF NEW.remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remarks: ", NEW.remarks);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('product', NEW.product_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Sales Proposal Table Triggers */

CREATE TRIGGER sales_proposal_trigger_update
AFTER UPDATE ON sales_proposal
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    SET time_zone = '+08:00';

    IF NEW.sales_proposal_number <> OLD.sales_proposal_number THEN
        SET audit_log = CONCAT(audit_log, "Sales Proposal Number: ", OLD.sales_proposal_number, " -> ", NEW.sales_proposal_number, "<br/>");
    END IF;

    IF NEW.customer_id <> OLD.customer_id THEN
        SET audit_log = CONCAT(audit_log, "Customer ID: ", OLD.customer_id, " -> ", NEW.customer_id, "<br/>");
    END IF;

    IF NEW.comaker_id <> OLD.comaker_id THEN
        SET audit_log = CONCAT(audit_log, "Comaker ID: ", OLD.comaker_id, " -> ", NEW.comaker_id, "<br/>");
    END IF;

    IF NEW.product_id <> OLD.product_id THEN
        SET audit_log = CONCAT(audit_log, "Product ID: ", OLD.product_id, " -> ", NEW.product_id, "<br/>");
    END IF;

    IF NEW.product_type <> OLD.product_type THEN
        SET audit_log = CONCAT(audit_log, "Product Type: ", OLD.product_type, " -> ", NEW.product_type, "<br/>");
    END IF;

    IF NEW.transaction_type <> OLD.transaction_type THEN
        SET audit_log = CONCAT(audit_log, "Transaction Type: ", OLD.transaction_type, " -> ", NEW.transaction_type, "<br/>");
    END IF;

    IF NEW.financing_institution <> OLD.financing_institution THEN
        SET audit_log = CONCAT(audit_log, "Financing Institution: ", OLD.financing_institution, " -> ", NEW.financing_institution, "<br/>");
    END IF;

    IF NEW.referred_by <> OLD.referred_by THEN
        SET audit_log = CONCAT(audit_log, "Referred By: ", OLD.referred_by, " -> ", NEW.referred_by, "<br/>");
    END IF;

    IF NEW.release_date <> OLD.release_date THEN
        SET audit_log = CONCAT(audit_log, "Release Date: ", OLD.release_date, " -> ", NEW.release_date, "<br/>");
    END IF;

    IF NEW.start_date <> OLD.start_date THEN
        SET audit_log = CONCAT(audit_log, "Start Date: ", OLD.start_date, " -> ", NEW.start_date, "<br/>");
    END IF;

    IF NEW.first_due_date <> OLD.first_due_date THEN
        SET audit_log = CONCAT(audit_log, "First Due Date: ", OLD.first_due_date, " -> ", NEW.first_due_date, "<br/>");
    END IF;

    IF NEW.term_length <> OLD.term_length THEN
        SET audit_log = CONCAT(audit_log, "Term Length: ", OLD.term_length, " -> ", NEW.term_length, "<br/>");
    END IF;

    IF NEW.term_type <> OLD.term_type THEN
        SET audit_log = CONCAT(audit_log, "Term Type: ", OLD.term_type, " -> ", NEW.term_type, "<br/>");
    END IF;

    IF NEW.number_of_payments <> OLD.number_of_payments THEN
        SET audit_log = CONCAT(audit_log, "Number of Payments: ", OLD.number_of_payments, " -> ", NEW.number_of_payments, "<br/>");
    END IF;

    IF NEW.payment_frequency <> OLD.payment_frequency THEN
        SET audit_log = CONCAT(audit_log, "Payment Frequency: ", OLD.payment_frequency, " -> ", NEW.payment_frequency, "<br/>");
    END IF;

    IF NEW.for_registration <> OLD.for_registration THEN
        SET audit_log = CONCAT(audit_log, "For Registration: ", OLD.for_registration, " -> ", NEW.for_registration, "<br/>");
    END IF;

    IF NEW.with_cr <> OLD.with_cr THEN
        SET audit_log = CONCAT(audit_log, "With CR: ", OLD.with_cr, " -> ", NEW.with_cr, "<br/>");
    END IF;

    IF NEW.for_transfer <> OLD.for_transfer THEN
        SET audit_log = CONCAT(audit_log, "For Transfer: ", OLD.for_transfer, " -> ", NEW.for_transfer, "<br/>");
    END IF;

    IF NEW.for_change_color <> OLD.for_change_color THEN
        SET audit_log = CONCAT(audit_log, "For Change Color: ", OLD.for_change_color, " -> ", NEW.for_change_color, "<br/>");
    END IF;

    IF NEW.new_color <> OLD.new_color THEN
        SET audit_log = CONCAT(audit_log, "New Color: ", OLD.new_color, " -> ", NEW.new_color, "<br/>");
    END IF;

    IF NEW.for_change_body <> OLD.for_change_body THEN
        SET audit_log = CONCAT(audit_log, "For Change Body: ", OLD.for_change_body, " -> ", NEW.for_change_body, "<br/>");
    END IF;

    IF NEW.new_body <> OLD.new_body THEN
        SET audit_log = CONCAT(audit_log, "New Body: ", OLD.new_body, " -> ", NEW.new_body, "<br/>");
    END IF;

    IF NEW.remarks <> OLD.remarks THEN
        SET audit_log = CONCAT(audit_log, "Remarks: ", OLD.remarks, " -> ", NEW.remarks, "<br/>");
    END IF;

    IF NEW.created_by <> OLD.created_by THEN
        SET audit_log = CONCAT(audit_log, "Created By: ", OLD.created_by, " -> ", NEW.created_by, "<br/>");
    END IF;

    IF NEW.created_date <> OLD.created_date THEN
        SET audit_log = CONCAT(audit_log, "Created Date: ", OLD.created_date, " -> ", NEW.created_date, "<br/>");
    END IF;

    IF NEW.sales_proposal_status <> OLD.sales_proposal_status THEN
        SET audit_log = CONCAT(audit_log, "Sales Proposal Status: ", OLD.sales_proposal_status, " -> ", NEW.sales_proposal_status, "<br/>");
    END IF;

    IF NEW.initial_approving_officer <> OLD.initial_approving_officer THEN
        SET audit_log = CONCAT(audit_log, "Initial Approving Officer: ", OLD.initial_approving_officer, " -> ", NEW.initial_approving_officer, "<br/>");
    END IF;

    IF NEW.final_approving_officer <> OLD.final_approving_officer THEN
        SET audit_log = CONCAT(audit_log, "Final Approving Officer: ", OLD.final_approving_officer, " -> ", NEW.final_approving_officer, "<br/>");
    END IF;

    IF NEW.initial_approval_remarks <> OLD.initial_approval_remarks THEN
        SET audit_log = CONCAT(audit_log, "Initial Approving Remarks: ", OLD.initial_approval_remarks, " -> ", NEW.initial_approval_remarks, "<br/>");
    END IF;

    IF NEW.final_approval_remarks <> OLD.final_approval_remarks THEN
        SET audit_log = CONCAT(audit_log, "Final Approving Remarks: ", OLD.final_approval_remarks, " -> ", NEW.final_approval_remarks, "<br/>");
    END IF;

    IF NEW.rejection_reason <> OLD.rejection_reason THEN
        SET audit_log = CONCAT(audit_log, "Rejection Reason: ", OLD.rejection_reason, " -> ", NEW.rejection_reason, "<br/>");
    END IF;

    IF NEW.cancellation_reason <> OLD.cancellation_reason THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Reason: ", OLD.cancellation_reason, " -> ", NEW.cancellation_reason, "<br/>");
    END IF;

    IF NEW.set_to_draft_reason <> OLD.set_to_draft_reason THEN
        SET audit_log = CONCAT(audit_log, "Set To Draft Reason: ", OLD.set_to_draft_reason, " -> ", NEW.set_to_draft_reason, "<br/>");
    END IF;

    IF NEW.initial_approval_by <> OLD.initial_approval_by THEN
        SET audit_log = CONCAT(audit_log, "Initial Approval By: ", OLD.initial_approval_by, " -> ", NEW.initial_approval_by, "<br/>");
    END IF;

    IF NEW.approval_by <> OLD.approval_by THEN
        SET audit_log = CONCAT(audit_log, "Final Approval By: ", OLD.approval_by, " -> ", NEW.approval_by, "<br/>");
    END IF;

    IF NEW.initial_approval_date <> OLD.initial_approval_date THEN
        SET audit_log = CONCAT(audit_log, "Inital Approval Date: ", OLD.initial_approval_date, " -> ", NEW.initial_approval_date, "<br/>");
    END IF;

    IF NEW.approval_date <> OLD.approval_date THEN
        SET audit_log = CONCAT(audit_log, "Final Approval Date: ", OLD.approval_date, " -> ", NEW.approval_date, "<br/>");
    END IF;

    IF NEW.for_ci_date <> OLD.for_ci_date THEN
        SET audit_log = CONCAT(audit_log, "For CI Date: ", OLD.for_ci_date, " -> ", NEW.for_ci_date, "<br/>");
    END IF;

    IF NEW.rejection_date <> OLD.rejection_date THEN
        SET audit_log = CONCAT(audit_log, "Rejection Date: ", OLD.rejection_date, " -> ", NEW.rejection_date, "<br/>");
    END IF;

    IF NEW.cancellation_date <> OLD.cancellation_date THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Date: ", OLD.cancellation_date, " -> ", NEW.cancellation_date, "<br/>");
    END IF;

    IF NEW.for_change_engine <> OLD.for_change_engine THEN
        SET audit_log = CONCAT(audit_log, "For Change Engine: ", OLD.for_change_engine, " -> ", NEW.for_change_engine, "<br/>");
    END IF;

    IF NEW.new_engine <> OLD.new_engine THEN
        SET audit_log = CONCAT(audit_log, "New Engine: ", OLD.new_engine, " -> ", NEW.new_engine, "<br/>");
    END IF;

    IF NEW.fuel_type <> OLD.fuel_type THEN
        SET audit_log = CONCAT(audit_log, "Fuel Type: ", OLD.fuel_type, " -> ", NEW.fuel_type, "<br/>");
    END IF;

    IF NEW.on_process_date <> OLD.on_process_date THEN
        SET audit_log = CONCAT(audit_log, "On-Process Date: ", OLD.on_process_date, " -> ", NEW.on_process_date, "<br/>");
    END IF;

    IF NEW.ready_for_release_date <> OLD.ready_for_release_date THEN
        SET audit_log = CONCAT(audit_log, "Ready For Release Date: ", OLD.ready_for_release_date, " -> ", NEW.ready_for_release_date, "<br/>");
    END IF;

    IF NEW.for_dr_date <> OLD.for_dr_date THEN
        SET audit_log = CONCAT(audit_log, "For DR Date: ", OLD.for_dr_date, " -> ", NEW.for_dr_date, "<br/>");
    END IF;

    IF NEW.price_per_liter <> OLD.price_per_liter THEN
        SET audit_log = CONCAT(audit_log, "Price Per Liter: ", OLD.price_per_liter, " -> ", NEW.price_per_liter, "<br/>");
    END IF;

    IF NEW.commission_amount <> OLD.commission_amount THEN
        SET audit_log = CONCAT(audit_log, "Commission Amount: ", OLD.commission_amount, " -> ", NEW.commission_amount, "<br/>");
    END IF;

    IF NEW.ci_status <> OLD.ci_status THEN
        SET audit_log = CONCAT(audit_log, "CI Status: ", OLD.ci_status, " -> ", NEW.ci_status, "<br/>");
    END IF;

    IF NEW.ci_completion_date <> OLD.ci_completion_date THEN
        SET audit_log = CONCAT(audit_log, "CI Completion Date: ", OLD.ci_completion_date, " -> ", NEW.ci_completion_date, "<br/>");
    END IF;

    IF NEW.dr_number <> OLD.dr_number THEN
        SET audit_log = CONCAT(audit_log, "DR Number: ", OLD.dr_number, " -> ", NEW.dr_number, "<br/>");
    END IF;

    IF NEW.loan_number <> OLD.loan_number THEN
        SET audit_log = CONCAT(audit_log, "Loan Number: ", OLD.loan_number, " -> ", NEW.loan_number, "<br/>");
    END IF;

    IF NEW.released_date <> OLD.released_date THEN
        SET audit_log = CONCAT(audit_log, "Released Date: ", OLD.released_date, " -> ", NEW.released_date, "<br/>");
    END IF;

    IF NEW.release_remarks <> OLD.release_remarks THEN
        SET audit_log = CONCAT(audit_log, "Release Remarks: ", OLD.release_remarks, " -> ", NEW.release_remarks, "<br/>");
    END IF;

    IF NEW.additional_job_order_confirmation <> OLD.additional_job_order_confirmation THEN
        SET audit_log = CONCAT(audit_log, "Additional Job Order Confirmation: ", OLD.additional_job_order_confirmation, " -> ", NEW.additional_job_order_confirmation, "<br/>");
    END IF;

    IF NEW.ref_stock_no <> OLD.ref_stock_no THEN
        SET audit_log = CONCAT(audit_log, "Refinancing/Brand New Stock Number: ", OLD.ref_stock_no, " -> ", NEW.ref_stock_no, "<br/>");
    END IF;

    IF NEW.ref_engine_no <> OLD.ref_engine_no THEN
        SET audit_log = CONCAT(audit_log, "Refinancing/Brand New Engine Number: ", OLD.ref_engine_no, " -> ", NEW.ref_engine_no, "<br/>");
    END IF;

    IF NEW.ref_chassis_no <> OLD.ref_chassis_no THEN
        SET audit_log = CONCAT(audit_log, "Refinancing/Brand New Chassis Number: ", OLD.ref_chassis_no, " -> ", NEW.ref_chassis_no, "<br/>");
    END IF;

    IF NEW.ref_plate_no <> OLD.ref_plate_no THEN
        SET audit_log = CONCAT(audit_log, "Refinancing/Brand New Plate Chassis Number: ", OLD.ref_plate_no, " -> ", NEW.ref_plate_no, "<br/>");
    END IF;

    IF NEW.diesel_fuel_quantity <> OLD.diesel_fuel_quantity THEN
        SET audit_log = CONCAT(audit_log, "Diesel Fuel Quantity: ", OLD.diesel_fuel_quantity, " -> ", NEW.diesel_fuel_quantity, "<br/>");
    END IF;

    IF NEW.diesel_price_per_liter <> OLD.diesel_price_per_liter THEN
        SET audit_log = CONCAT(audit_log, "Diesel Price Per Liter: ", OLD.diesel_price_per_liter, " -> ", NEW.diesel_price_per_liter, "<br/>");
    END IF;

    IF NEW.regular_fuel_quantity <> OLD.regular_fuel_quantity THEN
        SET audit_log = CONCAT(audit_log, "Regular Fuel Quantity: ", OLD.regular_fuel_quantity, " -> ", NEW.regular_fuel_quantity, "<br/>");
    END IF;

    IF NEW.regular_price_per_liter <> OLD.regular_price_per_liter THEN
        SET audit_log = CONCAT(audit_log, "Regular Price Per Liter: ", OLD.regular_price_per_liter, " -> ", NEW.regular_price_per_liter, "<br/>");
    END IF;

    IF NEW.premium_fuel_quantity <> OLD.premium_fuel_quantity THEN
        SET audit_log = CONCAT(audit_log, "Premium Fuel Quantity: ", OLD.premium_fuel_quantity, " -> ", NEW.premium_fuel_quantity, "<br/>");
    END IF;

    IF NEW.premium_price_per_liter <> OLD.premium_price_per_liter THEN
        SET audit_log = CONCAT(audit_log, "Premium Price Per Liter: ", OLD.premium_price_per_liter, " -> ", NEW.premium_price_per_liter, "<br/>");
    END IF;

    IF NEW.release_to <> OLD.release_to THEN
        SET audit_log = CONCAT(audit_log, "Release To: ", OLD.release_to, " -> ", NEW.release_to, "<br/>");
    END IF;

    IF NEW.company_id <> OLD.company_id THEN
        SET audit_log = CONCAT(audit_log, "Company ID: ", OLD.company_id, " -> ", NEW.company_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER sales_proposal_trigger_insert
AFTER INSERT ON sales_proposal
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Sales proposal created. <br/>';

    
    SET time_zone = '+08:00';

    IF NEW.sales_proposal_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Sales Proposal Number: ", NEW.sales_proposal_number);
    END IF;

    IF NEW.customer_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Customer ID: ", NEW.customer_id);
    END IF;

    IF NEW.comaker_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Comaker ID: ", NEW.comaker_id);
    END IF;

    IF NEW.product_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product ID: ", NEW.product_id);
    END IF;

    IF NEW.product_type <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product Type: ", NEW.product_type);
    END IF;

    IF NEW.transaction_type <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transaction Type: ", NEW.transaction_type);
    END IF;

    IF NEW.financing_institution <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Financing Institution: ", NEW.financing_institution);
    END IF;

    IF NEW.referred_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Referred By: ", NEW.referred_by);
    END IF;

    IF NEW.release_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Release Date: ", NEW.release_date);
    END IF;

    IF NEW.start_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Date: ", NEW.start_date);
    END IF;

    IF NEW.first_due_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>First Due Date: ", NEW.first_due_date);
    END IF;

    IF NEW.term_length <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Term Length: ", NEW.term_length);
    END IF;

    IF NEW.term_type <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Term Type: ", NEW.term_type);
    END IF;

    IF NEW.number_of_payments <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Number of Payments: ", NEW.number_of_payments);
    END IF;

    IF NEW.payment_frequency <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Payment Frequency: ", NEW.payment_frequency);
    END IF;

    IF NEW.for_registration <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For Registration: ", NEW.for_registration);
    END IF;

    IF NEW.with_cr <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>With CR: ", NEW.with_cr);
    END IF;

    IF NEW.for_transfer <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For Transfer: ", NEW.for_transfer);
    END IF;

    IF NEW.for_change_color <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For Change Color: ", NEW.for_change_color);
    END IF;

    IF NEW.new_color <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>New Color: ", NEW.new_color);
    END IF;

    IF NEW.for_change_body <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For Change Body: ", NEW.for_change_body);
    END IF;

    IF NEW.new_body <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>New Body: ", NEW.new_body);
    END IF;

    IF NEW.remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remarks: ", NEW.remarks);
    END IF;

    IF NEW.created_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Created By: ", NEW.created_by);
    END IF;

    IF NEW.created_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Created Date: ", NEW.created_date);
    END IF;

    IF NEW.sales_proposal_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Sales Proposal Status: ", NEW.sales_proposal_status);
    END IF;

    IF NEW.initial_approving_officer <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Initial Approving Officer: ", NEW.initial_approving_officer);
    END IF;

    IF NEW.final_approving_officer <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Final Approving Officer: ", NEW.final_approving_officer);
    END IF;

    IF NEW.for_change_engine <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For Change Engine: ", NEW.for_change_engine);
    END IF;

    IF NEW.new_engine <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>New Engine: ", NEW.new_engine);
    END IF;

    IF NEW.fuel_type <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Fuel Type: ", NEW.fuel_type);
    END IF;

    IF NEW.fuel_quantity <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Fuel Quantity: ", NEW.fuel_quantity);
    END IF;

    IF NEW.on_process_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>On-Process Date: ", NEW.on_process_date);
    END IF;

    IF NEW.ready_for_release_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Ready For Release Date: ", NEW.ready_for_release_date);
    END IF;

    IF NEW.for_dr_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For DR Date: ", NEW.for_dr_date);
    END IF;

    IF NEW.price_per_liter <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Price Per Liter: ", NEW.price_per_liter);
    END IF;

    IF NEW.commission_amount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Commission Amount: ", NEW.commission_amount);
    END IF;

    IF NEW.ci_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>CI Status: ", NEW.ci_status);
    END IF;

    IF NEW.ci_completion_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>CI Completion Date: ", NEW.ci_completion_date);
    END IF;

    IF NEW.new_engine_stencil <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>New Engine Stencil: ", NEW.new_engine_stencil);
    END IF;

    IF NEW.dr_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>DR Number: ", NEW.dr_number);
    END IF;

    IF NEW.loan_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Loan Number: ", NEW.loan_number);
    END IF;

    IF NEW.released_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Released Date: ", NEW.released_date);
    END IF;

    IF NEW.renewal_tag <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Renewal Tag: ", NEW.renewal_tag);
    END IF;

    IF NEW.release_remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Release Remarks Tag: ", NEW.release_remarks);
    END IF;

    IF NEW.additional_job_order_confirmation <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Additional Job Order Confirmation: ", NEW.additional_job_order_confirmation);
    END IF;

    IF NEW.ref_stock_no <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Refinancing/Brand New Stock Number: ", NEW.ref_stock_no);
    END IF;

    IF NEW.ref_engine_no <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Refinancing/Brand New Engine Number: ", NEW.ref_engine_no);
    END IF;

    IF NEW.ref_chassis_no <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Refinancing/Brand New Chassis Number: ", NEW.ref_chassis_no);
    END IF;

    IF NEW.ref_plate_no <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Refinancing/Brand New Plate Number: ", NEW.ref_plate_no);
    END IF;

    IF NEW.diesel_fuel_quantity <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Diesel Fuel Quantity: ", NEW.diesel_fuel_quantity);
    END IF;

    IF NEW.diesel_price_per_liter <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Diesel Price Per Liter: ", NEW.diesel_price_per_liter);
    END IF;

    IF NEW.regular_fuel_quantity <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Regular Fuel Quantity: ", NEW.regular_fuel_quantity);
    END IF;

    IF NEW.regular_price_per_liter <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Regular Price Per Liter: ", NEW.regular_price_per_liter);
    END IF;

    IF NEW.premium_fuel_quantity <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Premium Fuel Quantity: ", NEW.premium_fuel_quantity);
    END IF;

    IF NEW.premium_price_per_liter <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Premium Price Per Liter: ", NEW.premium_price_per_liter);
    END IF;

    IF NEW.release_to <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Release To: ", NEW.release_to);
    END IF;

    IF NEW.company_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Company ID: ", NEW.company_id);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */


/* Tenant Table Triggers */

CREATE TRIGGER tenant_trigger_update
AFTER UPDATE ON tenant
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.tenant_name <> OLD.tenant_name THEN
        SET audit_log = CONCAT(audit_log, "Tenant Name: ", OLD.tenant_name, " -> ", NEW.tenant_name, "<br/>");
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
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('tenant', NEW.tenant_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER tenant_trigger_insert
AFTER INSERT ON tenant
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Tenant created. <br/>';

    IF NEW.tenant_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Tenant Name: ", NEW.tenant_name);
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

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('tenant', NEW.tenant_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Tenant Table Triggers */

CREATE TRIGGER sales_proposal_pricing_computation_trigger_update
AFTER UPDATE ON sales_proposal_pricing_computation
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.delivery_price <> OLD.delivery_price THEN
        SET audit_log = CONCAT(audit_log, "Delivery Price: ", OLD.delivery_price, " -> ", NEW.delivery_price, "<br/>");
    END IF;

    IF NEW.cost_of_accessories <> OLD.cost_of_accessories THEN
        SET audit_log = CONCAT(audit_log, "Cost of Accessories: ", OLD.cost_of_accessories, " -> ", NEW.cost_of_accessories, "<br/>");
    END IF;

    IF NEW.reconditioning_cost <> OLD.reconditioning_cost THEN
        SET audit_log = CONCAT(audit_log, "Reconditioning Cost: ", OLD.reconditioning_cost, " -> ", NEW.reconditioning_cost, "<br/>");
    END IF;

    IF NEW.subtotal <> OLD.subtotal THEN
        SET audit_log = CONCAT(audit_log, "Sub-Total: ", OLD.subtotal, " -> ", NEW.subtotal, "<br/>");
    END IF;

    IF NEW.downpayment <> OLD.downpayment THEN
        SET audit_log = CONCAT(audit_log, "Downpayment: ", OLD.downpayment, " -> ", NEW.downpayment, "<br/>");
    END IF;

    IF NEW.outstanding_balance <> OLD.outstanding_balance THEN
        SET audit_log = CONCAT(audit_log, "Outstanding Balance: ", OLD.outstanding_balance, " -> ", NEW.outstanding_balance, "<br/>");
    END IF;

    IF NEW.amount_financed <> OLD.amount_financed THEN
        SET audit_log = CONCAT(audit_log, "Amount Financed: ", OLD.amount_financed, " -> ", NEW.amount_financed, "<br/>");
    END IF;

    IF NEW.pn_amount <> OLD.pn_amount THEN
        SET audit_log = CONCAT(audit_log, "PN Amount: ", OLD.pn_amount, " -> ", NEW.pn_amount, "<br/>");
    END IF;

    IF NEW.repayment_amount <> OLD.repayment_amount THEN
        SET audit_log = CONCAT(audit_log, "Repayment Amount: ", OLD.repayment_amount, " -> ", NEW.repayment_amount, "<br/>");
    END IF;

    IF NEW.interest_rate <> OLD.interest_rate THEN
        SET audit_log = CONCAT(audit_log, "Interest Rate: ", OLD.interest_rate, " -> ", NEW.interest_rate, "<br/>");
    END IF;

    IF NEW.nominal_discount <> OLD.nominal_discount THEN
        SET audit_log = CONCAT(audit_log, "Nominal Discount: ", OLD.nominal_discount, " -> ", NEW.nominal_discount, "<br/>");
    END IF;

    IF NEW.total_delivery_price <> OLD.total_delivery_price THEN
        SET audit_log = CONCAT(audit_log, "Total Delivery Price: ", OLD.total_delivery_price, " -> ", NEW.total_delivery_price, "<br/>");
    END IF;

    IF NEW.add_on_charge <> OLD.add_on_charge THEN
        SET audit_log = CONCAT(audit_log, "Add On Charge: ", OLD.add_on_charge, " -> ", NEW.add_on_charge, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER sales_proposal_pricing_computation_trigger_insert
AFTER INSERT ON sales_proposal_pricing_computation
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Sales Proposal Pricing Computation created. <br/>';

    IF NEW.delivery_price <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Delivery Price: ", NEW.delivery_price);
    END IF;

    IF NEW.cost_of_accessories <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cost of Accessories: ", NEW.cost_of_accessories);
    END IF;

    IF NEW.reconditioning_cost <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reconditioning Cost: ", NEW.reconditioning_cost);
    END IF;

    IF NEW.subtotal <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Sub-Total: ", NEW.subtotal);
    END IF;

    IF NEW.downpayment <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Downpayment: ", NEW.downpayment);
    END IF;

    IF NEW.outstanding_balance <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Outstanding Balance: ", NEW.outstanding_balance);
    END IF;

    IF NEW.amount_financed <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Amount Financed: ", NEW.amount_financed);
    END IF;

    IF NEW.pn_amount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>PN Amount: ", NEW.pn_amount);
    END IF;

    IF NEW.repayment_amount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Repayment Amount: ", NEW.repayment_amount);
    END IF;

    IF NEW.interest_rate <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Interest Rate: ", NEW.interest_rate);
    END IF;

    IF NEW.nominal_discount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Nominal Discount: ", NEW.nominal_discount);
    END IF;

    IF NEW.total_delivery_price <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Total Delivery Price: ", NEW.total_delivery_price);
    END IF;

    IF NEW.add_on_charge <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Add On Charge: ", NEW.add_on_charge);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER sales_proposal_other_charges_trigger_update
AFTER UPDATE ON sales_proposal_other_charges
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.insurance_coverage <> OLD.insurance_coverage THEN
        SET audit_log = CONCAT(audit_log, "Insurance Coverage: ", OLD.insurance_coverage, " -> ", NEW.insurance_coverage, "<br/>");
    END IF;

    IF NEW.insurance_premium <> OLD.insurance_premium THEN
        SET audit_log = CONCAT(audit_log, "Insurance Premium: ", OLD.insurance_premium, " -> ", NEW.insurance_premium, "<br/>");
    END IF;

    IF NEW.handling_fee <> OLD.handling_fee THEN
        SET audit_log = CONCAT(audit_log, "Handling Fee: ", OLD.handling_fee, " -> ", NEW.handling_fee, "<br/>");
    END IF;

    IF NEW.transfer_fee <> OLD.transfer_fee THEN
        SET audit_log = CONCAT(audit_log, "Transfer Fee: ", OLD.transfer_fee, " -> ", NEW.transfer_fee, "<br/>");
    END IF;

    IF NEW.registration_fee <> OLD.registration_fee THEN
        SET audit_log = CONCAT(audit_log, "Registration Fee: ", OLD.registration_fee, " -> ", NEW.registration_fee, "<br/>");
    END IF;

    IF NEW.doc_stamp_tax <> OLD.doc_stamp_tax THEN
        SET audit_log = CONCAT(audit_log, "Doc Stamp Tax: ", OLD.doc_stamp_tax, " -> ", NEW.doc_stamp_tax, "<br/>");
    END IF;

    IF NEW.transaction_fee <> OLD.transaction_fee THEN
        SET audit_log = CONCAT(audit_log, "Transaction Fee: ", OLD.transaction_fee, " -> ", NEW.transaction_fee, "<br/>");
    END IF;

    IF NEW.total_other_charges <> OLD.total_other_charges THEN
        SET audit_log = CONCAT(audit_log, "Total Other Charges: ", OLD.total_other_charges, " -> ", NEW.total_other_charges, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER sales_proposal_other_charges_trigger_insert
AFTER INSERT ON sales_proposal_other_charges
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Sales Proposal Other Charges created. <br/>';

    IF NEW.insurance_coverage <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Insurance Coverage: ", NEW.insurance_coverage);
    END IF;

    IF NEW.insurance_premium <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Insurance Premium: ", NEW.insurance_premium);
    END IF;

    IF NEW.handling_fee <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Handling Fee: ", NEW.handling_fee);
    END IF;

    IF NEW.transfer_fee <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transfer Fee: ", NEW.transfer_fee);
    END IF;

    IF NEW.registration_fee <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Registration Fee: ", NEW.registration_fee);
    END IF;

    IF NEW.doc_stamp_tax <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Doc Stamp Tax: ", NEW.doc_stamp_tax);
    END IF;

    IF NEW.transaction_fee <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transaction Fee: ", NEW.transaction_fee);
    END IF;

    IF NEW.total_other_charges <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Total Other Charges: ", NEW.total_other_charges);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('sales_proposal', NEW.sales_proposal_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Property Table Triggers */

CREATE TRIGGER property_trigger_update
AFTER UPDATE ON property
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.property_name <> OLD.property_name THEN
        SET audit_log = CONCAT(audit_log, "Property Name: ", OLD.property_name, " -> ", NEW.property_name, "<br/>");
    END IF;

    IF NEW.address <> OLD.address THEN
        SET audit_log = CONCAT(audit_log, "Address: ", OLD.address, " -> ", NEW.address, "<br/>");
    END IF;

    IF NEW.city_id <> OLD.city_id THEN
        SET audit_log = CONCAT(audit_log, "City ID: ", OLD.city_id, " -> ", NEW.city_id, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('property', NEW.property_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER property_trigger_insert
AFTER INSERT ON property
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Property created. <br/>';

    IF NEW.property_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Property Name: ", NEW.property_name);
    END IF;

    IF NEW.address <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Address: ", NEW.address);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('property', NEW.property_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */


/* Leasing Application Table Triggers */

CREATE TRIGGER leasing_application_trigger_update
AFTER UPDATE ON leasing_application
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.leasing_application_number <> OLD.leasing_application_number THEN
        SET audit_log = CONCAT(audit_log, "Leasing Application Number: ", OLD.leasing_application_number, " -> ", NEW.leasing_application_number, "<br/>");
    END IF;

    IF NEW.tenant_id <> OLD.tenant_id THEN
        SET audit_log = CONCAT(audit_log, "Tenant ID: ", OLD.tenant_id, " -> ", NEW.tenant_id, "<br/>");
    END IF;

    IF NEW.property_id <> OLD.property_id THEN
        SET audit_log = CONCAT(audit_log, "Property ID: ", OLD.property_id, " -> ", NEW.property_id, "<br/>");
    END IF;

    IF NEW.term_length <> OLD.term_length THEN
        SET audit_log = CONCAT(audit_log, "Term Length: ", OLD.term_length, " -> ", NEW.term_length, "<br/>");
    END IF;

    IF NEW.term_type <> OLD.term_type THEN
        SET audit_log = CONCAT(audit_log, "Term Type: ", OLD.term_type, " -> ", NEW.term_type, "<br/>");
    END IF;

    IF NEW.payment_frequency <> OLD.payment_frequency THEN
        SET audit_log = CONCAT(audit_log, "Payment Frequency: ", OLD.payment_frequency, " -> ", NEW.payment_frequency, "<br/>");
    END IF;

    IF NEW.vat <> OLD.vat THEN
        SET audit_log = CONCAT(audit_log, "VAT: ", OLD.vat, " -> ", NEW.vat, "<br/>");
    END IF;

    IF NEW.witholding_tax <> OLD.witholding_tax THEN
        SET audit_log = CONCAT(audit_log, "Witholding Tax: ", OLD.witholding_tax, " -> ", NEW.witholding_tax, "<br/>");
    END IF;

    IF NEW.renewal_tag <> OLD.renewal_tag THEN
        SET audit_log = CONCAT(audit_log, "Renewal Tag: ", OLD.renewal_tag, " -> ", NEW.renewal_tag, "<br/>");
    END IF;

    IF NEW.remarks <> OLD.remarks THEN
        SET audit_log = CONCAT(audit_log, "Remarks: ", OLD.remarks, " -> ", NEW.remarks, "<br/>");
    END IF;

    IF NEW.contract_date <> OLD.contract_date THEN
        SET audit_log = CONCAT(audit_log, "Contract Date: ", OLD.contract_date, " -> ", NEW.contract_date, "<br/>");
    END IF;

    IF NEW.start_date <> OLD.start_date THEN
        SET audit_log = CONCAT(audit_log, "Start Date: ", OLD.start_date, " -> ", NEW.start_date, "<br/>");
    END IF;

    IF NEW.maturity_date <> OLD.maturity_date THEN
        SET audit_log = CONCAT(audit_log, "Maturity Date: ", OLD.maturity_date, " -> ", NEW.maturity_date, "<br/>");
    END IF;

    IF NEW.security_deposit <> OLD.security_deposit THEN
        SET audit_log = CONCAT(audit_log, "Security Deposit: ", OLD.security_deposit, " -> ", NEW.security_deposit, "<br/>");
    END IF;

    IF NEW.floor_area <> OLD.floor_area THEN
        SET audit_log = CONCAT(audit_log, "Floor Area: ", OLD.floor_area, " -> ", NEW.floor_area, "<br/>");
    END IF;

    IF NEW.initial_basic_rental <> OLD.initial_basic_rental THEN
        SET audit_log = CONCAT(audit_log, "Initial Basic Rental: ", OLD.initial_basic_rental, " -> ", NEW.initial_basic_rental, "<br/>");
    END IF;

    IF NEW.escalation_rate <> OLD.escalation_rate THEN
        SET audit_log = CONCAT(audit_log, "Escalation Rate: ", OLD.escalation_rate, " -> ", NEW.escalation_rate, "<br/>");
    END IF;

    IF NEW.for_approval_date <> OLD.for_approval_date THEN
        SET audit_log = CONCAT(audit_log, "For Approval Date: ", OLD.for_approval_date, " -> ", NEW.for_approval_date, "<br/>");
    END IF;

    IF NEW.approval_date <> OLD.approval_date THEN
        SET audit_log = CONCAT(audit_log, "Approval Date: ", OLD.approval_date, " -> ", NEW.approval_date, "<br/>");
    END IF;

    IF NEW.activation_date <> OLD.activation_date THEN
        SET audit_log = CONCAT(audit_log, "Activation Date: ", OLD.activation_date, " -> ", NEW.activation_date, "<br/>");
    END IF;

    IF NEW.rejection_date <> OLD.rejection_date THEN
        SET audit_log = CONCAT(audit_log, "Rejection Date: ", OLD.rejection_date, " -> ", NEW.rejection_date, "<br/>");
    END IF;

    IF NEW.cancellation_date <> OLD.cancellation_date THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Date: ", OLD.cancellation_date, " -> ", NEW.cancellation_date, "<br/>");
    END IF;

    IF NEW.closed_date <> OLD.closed_date THEN
        SET audit_log = CONCAT(audit_log, "Closed Date: ", OLD.closed_date, " -> ", NEW.closed_date, "<br/>");
    END IF;

    IF NEW.activation_remarks <> OLD.activation_remarks THEN
        SET audit_log = CONCAT(audit_log, "Activation Remarks: ", OLD.activation_remarks, " -> ", NEW.activation_remarks, "<br/>");
    END IF;

    IF NEW.set_to_draft_reason <> OLD.set_to_draft_reason THEN
        SET audit_log = CONCAT(audit_log, "Set To Draft Reason: ", OLD.set_to_draft_reason, " -> ", NEW.set_to_draft_reason, "<br/>");
    END IF;

    IF NEW.rejection_reason <> OLD.rejection_reason THEN
        SET audit_log = CONCAT(audit_log, "Rejection Reason: ", OLD.rejection_reason, " -> ", NEW.rejection_reason, "<br/>");
    END IF;

    IF NEW.approval_remarks <> OLD.approval_remarks THEN
        SET audit_log = CONCAT(audit_log, "Approval Remarks: ", OLD.approval_remarks, " -> ", NEW.approval_remarks, "<br/>");
    END IF;

    IF NEW.application_status <> OLD.application_status THEN
        SET audit_log = CONCAT(audit_log, "Application Status: ", OLD.application_status, " -> ", NEW.application_status, "<br/>");
    END IF;

    IF NEW.cancellation_reason <> OLD.cancellation_reason THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Reason: ", OLD.cancellation_reason, " -> ", NEW.cancellation_reason, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('leasing_application', NEW.leasing_application_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER leasing_application_trigger_insert
AFTER INSERT ON leasing_application
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Leasing application created. <br/>';

     IF NEW.leasing_application_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leasing Application Number: ", NEW.leasing_application_number, "<br/>");
    END IF;

    IF NEW.tenant_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Tenant ID: ", NEW.tenant_id, "<br/>");
    END IF;

    IF NEW.property_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Property ID: ", NEW.property_id, "<br/>");
    END IF;

    IF NEW.term_length <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Term Length: ", NEW.term_length, "<br/>");
    END IF;

    IF NEW.term_type <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Term Type: ", NEW.term_type, "<br/>");
    END IF;

    IF NEW.payment_frequency <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Payment Frequency: ", NEW.payment_frequency, "<br/>");
    END IF;

    IF NEW.vat <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>VAT: ", NEW.vat, "<br/>");
    END IF;

    IF NEW.witholding_tax <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Witholding Tax: ", NEW.witholding_tax, "<br/>");
    END IF;

    IF NEW.renewal_tag <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Renewal Tag: ", NEW.renewal_tag, "<br/>");
    END IF;

    IF NEW.remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remarks: ", NEW.remarks, "<br/>");
    END IF;

    IF NEW.contract_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Contract Date: ", NEW.contract_date, "<br/>");
    END IF;

    IF NEW.start_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Date: ", NEW.start_date, "<br/>");
    END IF;

    IF NEW.maturity_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Maturity Date: ", NEW.maturity_date, "<br/>");
    END IF;

    IF NEW.security_deposit <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Security Deposit: ", NEW.security_deposit, "<br/>");
    END IF;

    IF NEW.floor_area <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Floor Area: ", NEW.floor_area, "<br/>");
    END IF;

    IF NEW.initial_basic_rental <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Initial Basic Rental: ", NEW.initial_basic_rental, "<br/>");
    END IF;

    IF NEW.escalation_rate <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Escalation Rate: ", NEW.escalation_rate, "<br/>");
    END IF;

    IF NEW.for_approval_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For Approval Date: ", NEW.for_approval_date, "<br/>");
    END IF;

    IF NEW.approval_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Approval Date: ", NEW.approval_date, "<br/>");
    END IF;

    IF NEW.activation_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Activation Date: ", NEW.activation_date, "<br/>");
    END IF;

    IF NEW.rejection_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Rejection Date: ", NEW.rejection_date, "<br/>");
    END IF;

    IF NEW.cancellation_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cancellation Date: ", NEW.cancellation_date, "<br/>");
    END IF;

    IF NEW.closed_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Closed Date: ", NEW.closed_date, "<br/>");
    END IF;

    IF NEW.activation_remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Activation Remarks: ", NEW.activation_remarks, "<br/>");
    END IF;

    IF NEW.set_to_draft_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Set To Draft Reason: ", NEW.set_to_draft_reason, "<br/>");
    END IF;

    IF NEW.rejection_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Rejection Reason: ", NEW.rejection_reason, "<br/>");
    END IF;

    IF NEW.approval_remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Approval Remarks: ", NEW.approval_remarks, "<br/>");
    END IF;

    IF NEW.application_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Application Status: ", NEW.application_status, "<br/>");
    END IF;

    IF NEW.cancellation_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cancellation Reason: ", NEW.cancellation_reason, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('leasing_application', NEW.leasing_application_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Directory Table Triggers */

CREATE TRIGGER contact_directory_trigger_update
AFTER UPDATE ON contact_directory
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.contact_name <> OLD.contact_name THEN
        SET audit_log = CONCAT(audit_log, "Contact Name: ", OLD.contact_name, " -> ", NEW.contact_name, "<br/>");
    END IF;

    IF NEW.position <> OLD.position THEN
        SET audit_log = CONCAT(audit_log, "Position: ", OLD.position, " -> ", NEW.position, "<br/>");
    END IF;

    IF NEW.location <> OLD.location THEN
        SET audit_log = CONCAT(audit_log, "Location: ", OLD.location, " -> ", NEW.location, "<br/>");
    END IF;

    IF NEW.directory_type <> OLD.directory_type THEN
        SET audit_log = CONCAT(audit_log, "Directory Type: ", OLD.directory_type, " -> ", NEW.directory_type, "<br/>");
    END IF;

    IF NEW.contact_information <> OLD.contact_information THEN
        SET audit_log = CONCAT(audit_log, "Contact Information: ", OLD.contact_information, " -> ", NEW.contact_information, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('contact_directory', NEW.contact_directory_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER contact_directory_trigger_insert
AFTER INSERT ON contact_directory
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Contact directory created. <br/>';

     IF NEW.contact_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Contact Name: ", NEW.contact_name, "<br/>");
    END IF;

    IF NEW.position <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Position: ", NEW.position, "<br/>");
    END IF;

    IF NEW.location <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Location: ", NEW.location, "<br/>");
    END IF;

    IF NEW.directory_type <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Directory Type: ", NEW.directory_type, "<br/>");
    END IF;

    IF NEW.contact_information <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Contact Information: ", NEW.contact_information, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('contact_directory', NEW.contact_directory_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/* Contact Directory Table Triggers */

CREATE TRIGGER leave_type_trigger_update
AFTER UPDATE ON leave_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.leave_type_name <> OLD.leave_type_name THEN
        SET audit_log = CONCAT(audit_log, "Leave Type: ", OLD.leave_type_name, " -> ", NEW.leave_type_name, "<br/>");
    END IF;

    IF NEW.is_paid <> OLD.is_paid THEN
        SET audit_log = CONCAT(audit_log, "Is Paid: ", OLD.is_paid, " -> ", NEW.is_paid, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('leave_type', NEW.leave_type_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER leave_type_trigger_insert
AFTER INSERT ON leave_type
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Leave type created. <br/>';

     IF NEW.leave_type_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave Type: ", NEW.leave_type_name, "<br/>");
    END IF;

     IF NEW.is_paid <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Is Paid: ", NEW.is_paid, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('leave_type', NEW.leave_type_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER parts_inquiry_trigger_update
AFTER UPDATE ON parts_inquiry
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.parts_number <> OLD.parts_number THEN
        SET audit_log = CONCAT(audit_log, "Parts Number: ", OLD.parts_number, " -> ", NEW.parts_number, "<br/>");
    END IF;

    IF NEW.parts_description <> OLD.parts_description THEN
        SET audit_log = CONCAT(audit_log, "Parts Description: ", OLD.parts_description, " -> ", NEW.parts_description, "<br/>");
    END IF;

    IF NEW.stock <> OLD.stock THEN
        SET audit_log = CONCAT(audit_log, "Stock: ", OLD.stock, " -> ", NEW.stock, "<br/>");
    END IF;

    IF NEW.price <> OLD.price THEN
        SET audit_log = CONCAT(audit_log, "Price: ", OLD.price, " -> ", NEW.price, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('parts_inquiry', NEW.parts_inquiry_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER parts_inquiry_trigger_insert
AFTER INSERT ON parts_inquiry
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Parts inquiry created. <br/>';

     IF NEW.parts_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Parts Number: ", NEW.parts_number, "<br/>");
    END IF;

     IF NEW.parts_description <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Parts Description: ", NEW.parts_description, "<br/>");
    END IF;

     IF NEW.stock <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Stock: ", NEW.stock, "<br/>");
    END IF;

     IF NEW.price <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Price: ", NEW.price, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('parts_inquiry', NEW.parts_inquiry_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

CREATE TRIGGER leave_entitlement_trigger_update
AFTER UPDATE ON leave_entitlement
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.contact_id <> OLD.contact_id THEN
        SET audit_log = CONCAT(audit_log, "Contact ID: ", OLD.contact_id, " -> ", NEW.contact_id, "<br/>");
    END IF;

    IF NEW.leave_type_id <> OLD.leave_type_id THEN
        SET audit_log = CONCAT(audit_log, "Leave Type ID: ", OLD.leave_type_id, " -> ", NEW.leave_type_id, "<br/>");
    END IF;

    IF NEW.entitlement_amount <> OLD.entitlement_amount THEN
        SET audit_log = CONCAT(audit_log, "Entitlement: ", OLD.entitlement_amount, " -> ", NEW.entitlement_amount, "<br/>");
    END IF;

    IF NEW.remaining_entitlement <> OLD.remaining_entitlement THEN
        SET audit_log = CONCAT(audit_log, "Remaining Entitlement: ", OLD.remaining_entitlement, " -> ", NEW.remaining_entitlement, "<br/>");
    END IF;

    IF NEW.leave_period_start <> OLD.leave_period_start THEN
        SET audit_log = CONCAT(audit_log, "Leave Period Start: ", OLD.leave_period_start, " -> ", NEW.leave_period_start, "<br/>");
    END IF;

    IF NEW.leave_period_end <> OLD.leave_period_end THEN
        SET audit_log = CONCAT(audit_log, "Leave Period End: ", OLD.leave_period_end, " -> ", NEW.leave_period_end, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('leave_entitlement', NEW.leave_entitlement_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER leave_entitlement_trigger_insert
AFTER INSERT ON leave_entitlement
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Leave entitlement created. <br/>';

     IF NEW.contact_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Contact ID: ", NEW.contact_id, "<br/>");
    END IF;

     IF NEW.leave_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave Type ID: ", NEW.leave_type_id, "<br/>");
    END IF;

     IF NEW.entitlement_amount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Entitlement: ", NEW.entitlement_amount, "<br/>");
    END IF;

     IF NEW.remaining_entitlement <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remaining Entitlement: ", NEW.remaining_entitlement, "<br/>");
    END IF;

     IF NEW.leave_period_start <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave Period Start: ", NEW.leave_period_start, "<br/>");
    END IF;

     IF NEW.leave_period_end <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave Period End: ", NEW.leave_period_end, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('leave_entitlement', NEW.leave_entitlement_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER leave_application_trigger_update
AFTER UPDATE ON leave_application
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.contact_id <> OLD.contact_id THEN
        SET audit_log = CONCAT(audit_log, "Contact ID: ", OLD.contact_id, " -> ", NEW.contact_id, "<br/>");
    END IF;

    IF NEW.leave_type_id <> OLD.leave_type_id THEN
        SET audit_log = CONCAT(audit_log, "Leave Type ID: ", OLD.leave_type_id, " -> ", NEW.leave_type_id, "<br/>");
    END IF;

    IF NEW.reason <> OLD.reason THEN
        SET audit_log = CONCAT(audit_log, "Reason: ", OLD.reason, " -> ", NEW.reason, "<br/>");
    END IF;

    IF NEW.leave_date <> OLD.leave_date THEN
        SET audit_log = CONCAT(audit_log, "Leave Date: ", OLD.leave_date, " -> ", NEW.leave_date, "<br/>");
    END IF;

    IF NEW.leave_start_time <> OLD.leave_start_time THEN
        SET audit_log = CONCAT(audit_log, "Leave Start Time: ", OLD.leave_start_time, " -> ", NEW.leave_start_time, "<br/>");
    END IF;

    IF NEW.leave_end_time <> OLD.leave_end_time THEN
        SET audit_log = CONCAT(audit_log, "Leave End Time: ", OLD.leave_end_time, " -> ", NEW.leave_end_time, "<br/>");
    END IF;

    IF NEW.number_of_hours <> OLD.number_of_hours THEN
        SET audit_log = CONCAT(audit_log, "Number Of Hours: ", OLD.number_of_hours, " -> ", NEW.number_of_hours, "<br/>");
    END IF;

    IF NEW.status <> OLD.status THEN
        SET audit_log = CONCAT(audit_log, "Number Of Hours: ", OLD.status, " -> ", NEW.status, "<br/>");
    END IF;

    IF NEW.application_date <> OLD.application_date THEN
        SET audit_log = CONCAT(audit_log, "Application Date: ", OLD.application_date, " -> ", NEW.application_date, "<br/>");
    END IF;

    IF NEW.approval_date <> OLD.approval_date THEN
        SET audit_log = CONCAT(audit_log, "Approval Date: ", OLD.approval_date, " -> ", NEW.approval_date, "<br/>");
    END IF;

    IF NEW.rejection_date <> OLD.rejection_date THEN
        SET audit_log = CONCAT(audit_log, "Rejection Date: ", OLD.rejection_date, " -> ", NEW.rejection_date, "<br/>");
    END IF;

    IF NEW.cancellation_date <> OLD.cancellation_date THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Date: ", OLD.cancellation_date, " -> ", NEW.cancellation_date, "<br/>");
    END IF;

    IF NEW.rejection_reason <> OLD.rejection_reason THEN
        SET audit_log = CONCAT(audit_log, "Rejection Date: ", OLD.rejection_reason, " -> ", NEW.rejection_reason, "<br/>");
    END IF;

    IF NEW.cancellation_reason <> OLD.cancellation_reason THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Date: ", OLD.cancellation_reason, " -> ", NEW.cancellation_reason, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('leave_application', NEW.leave_application_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER leave_application_trigger_insert
AFTER INSERT ON leave_application
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Leave application created. <br/>';

     IF NEW.contact_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Contact ID: ", NEW.contact_id, "<br/>");
    END IF;

     IF NEW.leave_type_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave Type ID: ", NEW.leave_type_id, "<br/>");
    END IF;

     IF NEW.reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reason: ", NEW.reason, "<br/>");
    END IF;

     IF NEW.leave_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave Date: ", NEW.leave_date, "<br/>");
    END IF;

     IF NEW.leave_start_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave Start Time: ", NEW.leave_start_time, "<br/>");
    END IF;

     IF NEW.leave_end_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Leave End Time: ", NEW.leave_end_time, "<br/>");
    END IF;

     IF NEW.number_of_hours <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Number Of Hours: ", NEW.number_of_hours, "<br/>");
    END IF;

     IF NEW.status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Status: ", NEW.status, "<br/>");
    END IF;

     IF NEW.application_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Application Date: ", NEW.application_date, "<br/>");
    END IF;

     IF NEW.approval_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Approval Date: ", NEW.approval_date, "<br/>");
    END IF;

     IF NEW.rejection_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Rejection Date: ", NEW.rejection_date, "<br/>");
    END IF;

     IF NEW.cancellation_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cancellation Date: ", NEW.cancellation_date, "<br/>");
    END IF;

     IF NEW.rejection_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Rejection Reason: ", NEW.rejection_reason, "<br/>");
    END IF;

     IF NEW.cancellation_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cancellation Reason: ", NEW.cancellation_reason, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('leave_application', NEW.leave_application_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER loan_collections_trigger_update
AFTER UPDATE ON loan_collections
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.loan_number <> OLD.loan_number THEN
        SET audit_log = CONCAT(audit_log, "Loan Number: ", OLD.loan_number, " -> ", NEW.loan_number, "<br/>");
    END IF;

    IF NEW.product_id <> OLD.product_id THEN
        SET audit_log = CONCAT(audit_log, "Product ID: ", OLD.product_id, " -> ", NEW.product_id, "<br/>");
    END IF;

    IF NEW.customer_id <> OLD.customer_id THEN
        SET audit_log = CONCAT(audit_log, "Customer ID: ", OLD.customer_id, " -> ", NEW.customer_id, "<br/>");
    END IF;

    IF NEW.mode_of_payment <> OLD.mode_of_payment THEN
        SET audit_log = CONCAT(audit_log, "Mode of Payment: ", OLD.mode_of_payment, " -> ", NEW.mode_of_payment, "<br/>");
    END IF;

    IF NEW.payment_details <> OLD.payment_details THEN
        SET audit_log = CONCAT(audit_log, "Payment Details: ", OLD.payment_details, " -> ", NEW.payment_details, "<br/>");
    END IF;

    IF NEW.payment_amount <> OLD.payment_amount THEN
        SET audit_log = CONCAT(audit_log, "Payment Amount: ", OLD.payment_amount, " -> ", NEW.payment_amount, "<br/>");
    END IF;

    IF NEW.collection_status <> OLD.collection_status THEN
        SET audit_log = CONCAT(audit_log, "Status: ", OLD.collection_status, " -> ", NEW.collection_status, "<br/>");
    END IF;

    IF NEW.reference_number <> OLD.reference_number THEN
        SET audit_log = CONCAT(audit_log, "Reference Number: ", OLD.reference_number, " -> ", NEW.reference_number, "<br/>");
    END IF;

    IF NEW.check_date <> OLD.check_date THEN
        SET audit_log = CONCAT(audit_log, "Check Date: ", OLD.check_date, " -> ", NEW.check_date, "<br/>");
    END IF;

    IF NEW.check_number <> OLD.check_number THEN
        SET audit_log = CONCAT(audit_log, "Check Number: ", OLD.check_number, " -> ", NEW.check_number, "<br/>");
    END IF;

    IF NEW.bank_branch <> OLD.bank_branch THEN
        SET audit_log = CONCAT(audit_log, "Bank Branch: ", OLD.bank_branch, " -> ", NEW.bank_branch, "<br/>");
    END IF;

    IF NEW.or_number <> OLD.or_number THEN
        SET audit_log = CONCAT(audit_log, "OR Number: ", OLD.or_number, " -> ", NEW.or_number, "<br/>");
    END IF;

    IF NEW.or_date <> OLD.or_date THEN
        SET audit_log = CONCAT(audit_log, "OR Date: ", OLD.or_date, " -> ", NEW.or_date, "<br/>");
    END IF;

    IF NEW.payment_date <> OLD.payment_date THEN
        SET audit_log = CONCAT(audit_log, "Payment Date: ", OLD.payment_date, " -> ", NEW.payment_date, "<br/>");
    END IF;

    IF NEW.transaction_date <> OLD.transaction_date THEN
        SET audit_log = CONCAT(audit_log, "Transaction Date: ", OLD.transaction_date, " -> ", NEW.transaction_date, "<br/>");
    END IF;

    IF NEW.onhold_date <> OLD.onhold_date THEN
        SET audit_log = CONCAT(audit_log, "On Hold Date: ", OLD.onhold_date, " -> ", NEW.onhold_date, "<br/>");
    END IF;

    IF NEW.onhold_reason <> OLD.onhold_reason THEN
        SET audit_log = CONCAT(audit_log, "On Hold Reason: ", OLD.onhold_reason, " -> ", NEW.onhold_reason, "<br/>");
    END IF;

    IF NEW.for_deposit_date <> OLD.for_deposit_date THEN
        SET audit_log = CONCAT(audit_log, "For Deposit Date: ", OLD.for_deposit_date, " -> ", NEW.for_deposit_date, "<br/>");
    END IF;

    IF NEW.redeposit_date <> OLD.redeposit_date THEN
        SET audit_log = CONCAT(audit_log, "Re-Deposit Date: ", OLD.redeposit_date, " -> ", NEW.redeposit_date, "<br/>");
    END IF;

    IF NEW.clear_date <> OLD.clear_date THEN
        SET audit_log = CONCAT(audit_log, "Clear Date: ", OLD.clear_date, " -> ", NEW.clear_date, "<br/>");
    END IF;

    IF NEW.cancellation_date <> OLD.cancellation_date THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Date: ", OLD.cancellation_date, " -> ", NEW.cancellation_date, "<br/>");
    END IF;

    IF NEW.cancellation_reason <> OLD.cancellation_reason THEN
        SET audit_log = CONCAT(audit_log, "Cancellation Reason: ", OLD.cancellation_reason, " -> ", NEW.cancellation_reason, "<br/>");
    END IF;

    IF NEW.reversal_date <> OLD.reversal_date THEN
        SET audit_log = CONCAT(audit_log, "Reversal Date: ", OLD.reversal_date, " -> ", NEW.reversal_date, "<br/>");
    END IF;

    IF NEW.pulled_out_date <> OLD.pulled_out_date THEN
        SET audit_log = CONCAT(audit_log, "Pulled-Out Date: ", OLD.pulled_out_date, " -> ", NEW.pulled_out_date, "<br/>");
    END IF;

    IF NEW.pulled_out_reason <> OLD.pulled_out_reason THEN
        SET audit_log = CONCAT(audit_log, "Pulled-Out Reason: ", OLD.pulled_out_reason, " -> ", NEW.pulled_out_reason, "<br/>");
    END IF;

    IF NEW.reversal_reason <> OLD.reversal_reason THEN
        SET audit_log = CONCAT(audit_log, "Reversal Reason: ", OLD.reversal_reason, " -> ", NEW.reversal_reason, "<br/>");
    END IF;

    IF NEW.reversal_remarks <> OLD.reversal_remarks THEN
        SET audit_log = CONCAT(audit_log, "Reversal Remarks: ", OLD.reversal_remarks, " -> ", NEW.reversal_remarks, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('loan_collections', NEW.loan_collection_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER loan_collections_trigger_insert
AFTER INSERT ON loan_collections
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Loan Collections created. <br/>';

     IF NEW.loan_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reference: ", NEW.loan_number, "<br/>");
    END IF;

     IF NEW.product_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Product ID: ", NEW.product_id, "<br/>");
    END IF;

     IF NEW.customer_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Customer ID: ", NEW.customer_id, "<br/>");
    END IF;

     IF NEW.mode_of_payment <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mode of Payment: ", NEW.mode_of_payment, "<br/>");
    END IF;

     IF NEW.payment_details <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Payment Details: ", NEW.payment_details, "<br/>");
    END IF;

     IF NEW.payment_amount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Payment Amount: ", NEW.payment_amount, "<br/>");
    END IF;

     IF NEW.collection_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Status: ", NEW.collection_status, "<br/>");
    END IF;

     IF NEW.reference_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reference Number: ", NEW.reference_number, "<br/>");
    END IF;

     IF NEW.check_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check Date: ", NEW.check_date, "<br/>");
    END IF;

     IF NEW.check_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Check Number: ", NEW.check_number, "<br/>");
    END IF;

     IF NEW.bank_branch <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Bank Branch: ", NEW.bank_branch, "<br/>");
    END IF;

     IF NEW.or_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>OR Number: ", NEW.or_number, "<br/>");
    END IF;

     IF NEW.or_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>OR Date: ", NEW.or_date, "<br/>");
    END IF;

     IF NEW.payment_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Payment Date: ", NEW.payment_date, "<br/>");
    END IF;

     IF NEW.transaction_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transaction Date: ", NEW.transaction_date, "<br/>");
    END IF;

     IF NEW.onhold_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>On Hold Date: ", NEW.onhold_date, "<br/>");
    END IF;

     IF NEW.onhold_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>On Hold Reason: ", NEW.onhold_reason, "<br/>");
    END IF;

     IF NEW.for_deposit_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>For Deposit Date: ", NEW.for_deposit_date, "<br/>");
    END IF;

     IF NEW.redeposit_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Re-Deposit Date: ", NEW.redeposit_date, "<br/>");
    END IF;

     IF NEW.clear_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Clear Date: ", NEW.clear_date, "<br/>");
    END IF;

     IF NEW.cancellation_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cancellation Date: ", NEW.cancellation_date, "<br/>");
    END IF;

     IF NEW.cancellation_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cancellation Reason: ", NEW.cancellation_reason, "<br/>");
    END IF;

     IF NEW.reversal_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reversal Date: ", NEW.reversal_date, "<br/>");
    END IF;

     IF NEW.pulled_out_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Pulled-Out Date: ", NEW.pulled_out_date, "<br/>");
    END IF;

     IF NEW.pulled_out_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Pulled-Out Reason: ", NEW.pulled_out_reason, "<br/>");
    END IF;

     IF NEW.reversal_reason <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reversal Reason : ", NEW.reversal_reason, "<br/>");
    END IF;

     IF NEW.reversal_remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reversal Remarks: ", NEW.reversal_remarks, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('loan_collections', NEW.loan_collection_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER sales_proposal_repayment_trigger_update
AFTER UPDATE ON sales_proposal_repayment
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.reference <> OLD.reference THEN
        SET audit_log = CONCAT(audit_log, "Reference: ", OLD.reference, " -> ", NEW.reference, "<br/>");
    END IF;

    IF NEW.due_date <> OLD.due_date THEN
        SET audit_log = CONCAT(audit_log, "Due Date: ", OLD.due_date, " -> ", NEW.due_date, "<br/>");
    END IF;

    IF NEW.due_amount <> OLD.due_amount THEN
        SET audit_log = CONCAT(audit_log, "Due Amount: ", OLD.due_amount, " -> ", NEW.due_amount, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('sales_proposal_repayment', NEW.sales_proposal_repayment_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER sales_proposal_repayment_trigger_insert
AFTER INSERT ON sales_proposal_repayment
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Loan Repayment created. <br/>';

     IF NEW.reference <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reference: ", NEW.reference, "<br/>");
    END IF;

     IF NEW.due_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Due Date: ", NEW.due_date, "<br/>");
    END IF;

     IF NEW.due_amount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Due Amount: ", NEW.due_amount, "<br/>");
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('sales_proposal_repayment', NEW.sales_proposal_repayment_id, audit_log, NEW.last_log_by, NOW());
END //


CREATE TRIGGER brand_trigger_update
AFTER UPDATE ON brand
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.brand_name <> OLD.brand_name THEN
        SET audit_log = CONCAT(audit_log, "Brand Name: ", OLD.brand_name, " -> ", NEW.brand_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('brand', NEW.brand_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER brand_trigger_insert
AFTER INSERT ON brand
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Brand created. <br/>';

    IF NEW.brand_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Brand Name: ", NEW.brand_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('brand', NEW.brand_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER make_trigger_update
AFTER UPDATE ON make
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.make_name <> OLD.make_name THEN
        SET audit_log = CONCAT(audit_log, "Make Name: ", OLD.make_name, " -> ", NEW.make_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('make', NEW.make_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER make_trigger_insert
AFTER INSERT ON make
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Make created. <br/>';

    IF NEW.make_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Make Name: ", NEW.make_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('make', NEW.make_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER model_trigger_update
AFTER UPDATE ON model
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.model_name <> OLD.model_name THEN
        SET audit_log = CONCAT(audit_log, "Model Name: ", OLD.model_name, " -> ", NEW.model_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('model', NEW.model_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER model_trigger_insert
AFTER INSERT ON model
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Model created. <br/>';

    IF NEW.model_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Model Name: ", NEW.model_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('model', NEW.model_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER cabin_trigger_update
AFTER UPDATE ON cabin
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.cabin_name <> OLD.cabin_name THEN
        SET audit_log = CONCAT(audit_log, "Cabin Name: ", OLD.cabin_name, " -> ", NEW.cabin_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('cabin', NEW.cabin_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER cabin_trigger_insert
AFTER INSERT ON cabin
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Cabin created. <br/>';

    IF NEW.cabin_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Cabin Name: ", NEW.cabin_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('cabin', NEW.cabin_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER supplier_trigger_update
AFTER UPDATE ON supplier
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.supplier_name <> OLD.supplier_name THEN
        SET audit_log = CONCAT(audit_log, "Supplier Name: ", OLD.supplier_name, " -> ", NEW.supplier_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('supplier', NEW.supplier_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER supplier_trigger_insert
AFTER INSERT ON supplier
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Supplier created. <br/>';

    IF NEW.supplier_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Supplier Name: ", NEW.supplier_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('supplier', NEW.supplier_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER class_trigger_update
AFTER UPDATE ON class
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.class_name <> OLD.class_name THEN
        SET audit_log = CONCAT(audit_log, "Class Name: ", OLD.class_name, " -> ", NEW.class_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('class', NEW.class_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER class_trigger_insert
AFTER INSERT ON class
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Class created. <br/>';

    IF NEW.class_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Class Name: ", NEW.class_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('class', NEW.class_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER mode_of_acquisition_trigger_update
AFTER UPDATE ON mode_of_acquisition
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.mode_of_acquisition_name <> OLD.mode_of_acquisition_name THEN
        SET audit_log = CONCAT(audit_log, "Mode Of Acquisition Name: ", OLD.mode_of_acquisition_name, " -> ", NEW.mode_of_acquisition_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('mode_of_acquisition', NEW.mode_of_acquisition_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER mode_of_acquisition_trigger_insert
AFTER INSERT ON mode_of_acquisition
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Mode of acquisition created. <br/>';

    IF NEW.mode_of_acquisition_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Mode Of Acquisition Name: ", NEW.mode_of_acquisition_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('mode_of_acquisition', NEW.mode_of_acquisition_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER application_source_trigger_update
AFTER UPDATE ON application_source
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.application_source_name <> OLD.application_source_name THEN
        SET audit_log = CONCAT(audit_log, "Application Source Name: ", OLD.application_source_name, " -> ", NEW.application_source_name, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('application_source', NEW.application_source_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER application_source_trigger_insert
AFTER INSERT ON application_source
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Application source created. <br/>';

    IF NEW.application_source_name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Application Source Name: ", NEW.application_source_name);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('application_source', NEW.application_source_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER deposits_trigger_update
AFTER UPDATE ON deposits
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.company_id <> OLD.company_id THEN
        SET audit_log = CONCAT(audit_log, "Deposit ID: ", OLD.company_id, " -> ", NEW.company_id, "<br/>");
    END IF;

    IF NEW.deposit_amount <> OLD.deposit_amount THEN
        SET audit_log = CONCAT(audit_log, "Deposit Amount: ", OLD.deposit_amount, " -> ", NEW.deposit_amount, "<br/>");
    END IF;

    IF NEW.deposit_date <> OLD.deposit_date THEN
        SET audit_log = CONCAT(audit_log, "Deposit Date: ", OLD.deposit_date, " -> ", NEW.deposit_date, "<br/>");
    END IF;

    IF NEW.deposited_to <> OLD.deposited_to THEN
        SET audit_log = CONCAT(audit_log, "Deposited To: ", OLD.deposited_to, " -> ", NEW.deposited_to, "<br/>");
    END IF;

    IF NEW.transaction_date <> OLD.transaction_date THEN
        SET audit_log = CONCAT(audit_log, "Transaction Date: ", OLD.transaction_date, " -> ", NEW.transaction_date, "<br/>");
    END IF;

    IF NEW.reference_number <> OLD.reference_number THEN
        SET audit_log = CONCAT(audit_log, "Reference Number: ", OLD.reference_number, " -> ", NEW.reference_number, "<br/>");
    END IF;

    IF NEW.remarks <> OLD.remarks THEN
        SET audit_log = CONCAT(audit_log, "Remarks: ", OLD.remarks, " -> ", NEW.remarks, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('deposits', NEW.deposits_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER deposits_trigger_insert
AFTER INSERT ON deposits
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Deposits created. <br/>';

    IF NEW.deposit_amount <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Deposit Amount: ", NEW.deposit_amount);
    END IF;

    IF NEW.company_id <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Company ID: ", NEW.company_id);
    END IF;

    IF NEW.deposit_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Deposit Date: ", NEW.deposit_date);
    END IF;

    IF NEW.deposited_to <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Deposited To: ", NEW.deposited_to);
    END IF;

    IF NEW.transaction_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Transaction Date: ", NEW.transaction_date);
    END IF;

    IF NEW.reference_number <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Reference Number: ", NEW.reference_number);
    END IF;

    IF NEW.remarks <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Remarks: ", NEW.remarks);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('deposits', NEW.deposits_id, audit_log, NEW.last_log_by, NOW());
END //

CREATE TRIGGER travel_form_trigger_update
AFTER UPDATE ON travel_form
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.checked_by <> OLD.checked_by THEN
        SET audit_log = CONCAT(audit_log, "Checked By: ", OLD.checked_by, " -> ", NEW.checked_by, "<br/>");
    END IF;

    IF NEW.checked_date <> OLD.checked_date THEN
        SET audit_log = CONCAT(audit_log, "Checked Date: ", OLD.checked_date, " -> ", NEW.checked_date, "<br/>");
    END IF;

    IF NEW.approval_by <> OLD.approval_by THEN
        SET audit_log = CONCAT(audit_log, "Approval By: ", OLD.approval_by, " -> ", NEW.approval_by, "<br/>");
    END IF;

    IF NEW.approval_date <> OLD.approval_date THEN
        SET audit_log = CONCAT(audit_log, "Approval Date: ", OLD.approval_date, " -> ", NEW.approval_date, "<br/>");
    END IF;

    IF NEW.travel_form_status <> OLD.travel_form_status THEN
        SET audit_log = CONCAT(audit_log, "Approval Date: ", OLD.travel_form_status, " -> ", NEW.travel_form_status, "<br/>");
    END IF;

    IF NEW.recommended_date <> OLD.recommended_date THEN
        SET audit_log = CONCAT(audit_log, "Approval Date: ", OLD.recommended_date, " -> ", NEW.recommended_date, "<br/>");
    END IF;

    IF NEW.recommended_date <> OLD.recommended_date THEN
        SET audit_log = CONCAT(audit_log, "Recommended Date: ", OLD.recommended_date, " -> ", NEW.recommended_date, "<br/>");
    END IF;

    IF NEW.for_checking_date <> OLD.for_checking_date THEN
        SET audit_log = CONCAT(audit_log, "For Checking Date: ", OLD.for_checking_date, " -> ", NEW.for_checking_date, "<br/>");
    END IF;

    IF NEW.for_recommendation_date <> OLD.for_recommendation_date THEN
        SET audit_log = CONCAT(audit_log, "For Recommendation Date: ", OLD.for_recommendation_date, " -> ", NEW.for_recommendation_date, "<br/>");
    END IF;

    IF NEW.for_approval <> OLD.for_approval THEN
        SET audit_log = CONCAT(audit_log, "For Approval Date: ", OLD.for_approval, " -> ", NEW.for_approval, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('travel_form', NEW.travel_form_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER travel_form_trigger_insert
AFTER INSERT ON travel_form
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Travel form created. <br/>';

    IF NEW.checked_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Checked By: ", NEW.checked_by);
    END IF;

    IF NEW.checked_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Checked Date: ", NEW.checked_date);
    END IF;

    IF NEW.approval_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Approval By: ", NEW.approval_by);
    END IF;

    IF NEW.approval_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Approval Date: ", NEW.approval_date);
    END IF;

    IF NEW.travel_form_status <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Travel Form Status: ", NEW.travel_form_status);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('travel_form', NEW.travel_form_id, audit_log, NEW.last_log_by, NOW());
END //

/* Chart of Account Table Triggers */

CREATE TRIGGER chart_of_account_trigger_update
AFTER UPDATE ON chart_of_account
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.code <> OLD.code THEN
        SET audit_log = CONCAT(audit_log, "Code: ", OLD.code, " -> ", NEW.code, "<br/>");
    END IF;

    IF NEW.name <> OLD.name THEN
        SET audit_log = CONCAT(audit_log, "Name: ", OLD.name, " -> ", NEW.name, "<br/>");
    END IF;

    IF NEW.account_type <> OLD.account_type THEN
        SET audit_log = CONCAT(audit_log, "Account Type: ", OLD.account_type, " -> ", NEW.account_type, "<br/>");
    END IF;
    
    IF LENGTH(audit_log) > 0 THEN
        INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('chart_of_account', NEW.chart_of_account_id, audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER chart_of_account_trigger_insert
AFTER INSERT ON chart_of_account
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT 'Chart of account created. <br/>';

    IF NEW.code <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Code: ", NEW.code);
    END IF;

    IF NEW.name <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Name: ", NEW.name);
    END IF;

    IF NEW.account_type <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Account Type: ", NEW.account_type);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('chart_of_account', NEW.chart_of_account_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */