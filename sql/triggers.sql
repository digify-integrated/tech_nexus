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

    IF NEW.start_work_date <> OLD.start_work_date THEN
        SET audit_log = CONCAT(audit_log, "Start Work Date: ", OLD.start_work_date, " -> ", NEW.start_work_date, "<br/>");
    END IF;

    IF NEW.start_day_of_week <> OLD.start_day_of_week THEN
        SET audit_log = CONCAT(audit_log, "Start Day of Week: ", OLD.start_day_of_week, " -> ", NEW.start_day_of_week, "<br/>");
    END IF;

    IF NEW.start_time <> OLD.start_time THEN
        SET audit_log = CONCAT(audit_log, "Start Time: ", OLD.start_time, " -> ", NEW.start_time, "<br/>");
    END IF;

    IF NEW.end_work_date <> OLD.end_work_date THEN
        SET audit_log = CONCAT(audit_log, "End Work Date: ", OLD.end_work_date, " -> ", NEW.end_work_date, "<br/>");
    END IF;

    IF NEW.end_day_of_week <> OLD.end_day_of_week THEN
        SET audit_log = CONCAT(audit_log, "End Day of Week: ", OLD.end_day_of_week, " -> ", NEW.end_day_of_week, "<br/>");
    END IF;

    IF NEW.end_time <> OLD.end_time THEN
        SET audit_log = CONCAT(audit_log, "End Time: ", OLD.end_time, " -> ", NEW.end_time, "<br/>");
    END IF;

    IF NEW.lunch_break_start_work_date <> OLD.lunch_break_start_work_date THEN
        SET audit_log = CONCAT(audit_log, "Lunch Break Start Work Date: ", OLD.lunch_break_start_work_date, " -> ", NEW.lunch_break_start_work_date, "<br/>");
    END IF;

    IF NEW.lunch_break_start_day_of_week <> OLD.lunch_break_start_day_of_week THEN
        SET audit_log = CONCAT(audit_log, "Lunch Break Start Day of Week: ", OLD.lunch_break_start_day_of_week, " -> ", NEW.lunch_break_start_day_of_week, "<br/>");
    END IF;

    IF NEW.lunch_break_start_time <> OLD.lunch_break_start_time THEN
        SET audit_log = CONCAT(audit_log, "Lunch Break Start Time: ", OLD.lunch_break_start_time, " -> ", NEW.lunch_break_start_time, "<br/>");
    END IF;

    IF NEW.lunch_break_end_work_date <> OLD.lunch_break_end_work_date THEN
        SET audit_log = CONCAT(audit_log, "Lunch Break End Work Date: ", OLD.lunch_break_end_work_date, " -> ", NEW.lunch_break_end_work_date, "<br/>");
    END IF;

    IF NEW.lunch_break_end_day_of_week <> OLD.lunch_break_end_day_of_week THEN
        SET audit_log = CONCAT(audit_log, "Lunch Break End Day of Week: ", OLD.lunch_break_end_day_of_week, " -> ", NEW.lunch_break_end_day_of_week, "<br/>");
    END IF;

    IF NEW.lunch_break_end_time <> OLD.lunch_break_end_time THEN
        SET audit_log = CONCAT(audit_log, "Lunch Break End Time: ", OLD.lunch_break_end_time, " -> ", NEW.lunch_break_end_time, "<br/>");
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

    IF NEW.start_work_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Work Date: ", NEW.start_work_date);
    END IF;

    IF NEW.start_day_of_week <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Day of Week: ", NEW.start_day_of_week);
    END IF;

    IF NEW.start_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Start Time: ", NEW.start_time);
    END IF;

    IF NEW.end_work_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Work Date: ", NEW.end_work_date);
    END IF;

    IF NEW.end_day_of_week <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Day of Week: ", NEW.end_day_of_week);
    END IF;

    IF NEW.end_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>End Time: ", NEW.end_time);
    END IF;

    IF NEW.lunch_break_start_work_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Lunch Break Start Work Date: ", NEW.lunch_break_start_work_date);
    END IF;

    IF NEW.lunch_break_start_day_of_week <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Lunch Break Start Day of Week: ", NEW.lunch_break_start_day_of_week);
    END IF;

    IF NEW.lunch_break_start_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Lunch Break Start Time: ", NEW.lunch_break_start_time);
    END IF;

    IF NEW.lunch_break_end_work_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Lunch Break Work Date: ", NEW.lunch_break_end_work_date);
    END IF;

    IF NEW.lunch_break_end_day_of_week <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Lunch Break End Day of Week: ", NEW.lunch_break_end_day_of_week);
    END IF;

    IF NEW.lunch_break_end_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Lunch Break End Time: ", NEW.lunch_break_end_time);
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

/* Attendance Setting Table Triggers */

CREATE TRIGGER attendance_trigger_update
AFTER UPDATE ON attendance
FOR EACH ROW
BEGIN
    DECLARE audit_log TEXT DEFAULT '';

    IF NEW.entry_date <> OLD.entry_date THEN
        SET audit_log = CONCAT(audit_log, "Entry Date: ", OLD.entry_date, " -> ", NEW.entry_date, "<br/>");
    END IF;

    IF NEW.entry_time <> OLD.entry_time THEN
        SET audit_log = CONCAT(audit_log, "Entry Time: ", OLD.entry_time, " -> ", NEW.entry_time, "<br/>");
    END IF;

    IF NEW.entry_location <> OLD.entry_location THEN
        SET audit_log = CONCAT(audit_log, "Entry Location: ", OLD.entry_location, " -> ", NEW.entry_location, "<br/>");
    END IF;

    IF NEW.entry_state <> OLD.entry_state THEN
        SET audit_log = CONCAT(audit_log, "Entry State: ", OLD.entry_state, " -> ", NEW.entry_state, "<br/>");
    END IF;

    IF NEW.entry_by <> OLD.entry_by THEN
        SET audit_log = CONCAT(audit_log, "Entry By: ", OLD.entry_by, " -> ", NEW.entry_by, "<br/>");
    END IF;

    IF NEW.exit_date <> OLD.exit_date THEN
        SET audit_log = CONCAT(audit_log, "Exit Date: ", OLD.exit_date, " -> ", NEW.exit_date, "<br/>");
    END IF;

    IF NEW.exit_time <> OLD.exit_time THEN
        SET audit_log = CONCAT(audit_log, "Exit Time: ", OLD.exit_time, " -> ", NEW.exit_time, "<br/>");
    END IF;

    IF NEW.exit_location <> OLD.exit_location THEN
        SET audit_log = CONCAT(audit_log, "Exit Location: ", OLD.exit_location, " -> ", NEW.exit_location, "<br/>");
    END IF;

    IF NEW.exit_state <> OLD.exit_state THEN
        SET audit_log = CONCAT(audit_log, "Exit State: ", OLD.exit_state, " -> ", NEW.exit_state, "<br/>");
    END IF;

    IF NEW.exit_by <> OLD.exit_by THEN
        SET audit_log = CONCAT(audit_log, "Exit By: ", OLD.exit_by, " -> ", NEW.exit_by, "<br/>");
    END IF;

    IF NEW.notes <> OLD.notes THEN
        SET audit_log = CONCAT(audit_log, "Notes: ", OLD.notes, " -> ", NEW.notes, "<br/>");
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

    IF NEW.entry_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Entry Date: ", NEW.entry_date);
    END IF;

    IF NEW.entry_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Entry Time: ", NEW.entry_time);
    END IF;

    IF NEW.entry_location <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Entry Location: ", NEW.entry_location);
    END IF;

    IF NEW.entry_state <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Entry State: ", NEW.entry_state);
    END IF;

    IF NEW.entry_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Entry By: ", NEW.entry_by);
    END IF;

    IF NEW.exit_date <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Exit Date: ", NEW.exit_date);
    END IF;

    IF NEW.exit_time <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Exit Time: ", NEW.exit_time);
    END IF;

    IF NEW.exit_location <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Exit Location: ", NEW.exit_location);
    END IF;

    IF NEW.exit_state <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Exit State: ", NEW.exit_state);
    END IF;

    IF NEW.exit_by <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Exit By: ", NEW.exit_by);
    END IF;

    IF NEW.notes <> '' THEN
        SET audit_log = CONCAT(audit_log, "<br/>Notes: ", NEW.notes);
    END IF;

    INSERT INTO audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('attendance', NEW.attendance_id, audit_log, NEW.last_log_by, NOW());
END //

/* ----------------------------------------------------------------------------------------------------------------------------- */

/*  Table Triggers */



/* ----------------------------------------------------------------------------------------------------------------------------- */