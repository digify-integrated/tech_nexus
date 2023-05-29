/* UI customization setting table */
CREATE TABLE admin_ui_customization_setting (
    external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT(10) UNSIGNED NOT NULL,
    email_address  VARCHAR(100) NOT NULL,
    theme_contrast VARCHAR(15),
    caption_show VARCHAR(15),
    preset_theme VARCHAR(15),
    dark_layout VARCHAR(15),
    rtl_layout VARCHAR(15),
    box_container VARCHAR(15),
    last_log_by INT(10) NOT NULL
);

ALTER TABLE admin_ui_customization_setting
ADD FOREIGN KEY (user_id) REFERENCES admin_users(external_id);

ALTER TABLE admin_ui_customization_setting
ADD FOREIGN KEY (email_address) REFERENCES admin_users(email_address);

CREATE INDEX ui_customization_setting_index_external_id ON admin_ui_customization_setting(external_id);
CREATE INDEX ui_customization_setting_index_user_id ON admin_ui_customization_setting(user_id);
CREATE INDEX ui_customization_setting_index_email_address ON admin_ui_customization_setting(email_address);

CREATE TRIGGER ui_customization_setting_trigger_update
AFTER UPDATE ON admin_ui_customization_setting
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT '';

    IF NEW.theme_contrast <> OLD.theme_contrast THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Theme Contrast: ", OLD.theme_contrast, " -> ", NEW.theme_contrast, "<br/>");
    END IF;

    IF NEW.caption_show <> OLD.caption_show THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Caption Show: ", OLD.caption_show, " -> ", NEW.caption_show, "<br/>");
    END IF;

    IF NEW.preset_theme <> OLD.preset_theme THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Preset Theme: ", OLD.preset_theme, " -> ", NEW.preset_theme, "<br/>");
    END IF;

    IF NEW.dark_layout <> OLD.dark_layout THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Dark Layout: ", OLD.dark_layout, " -> ", NEW.dark_layout, "<br/>");
    END IF;

    IF NEW.rtl_layout <> OLD.rtl_layout THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "RTL Layout: ", OLD.rtl_layout, " -> ", NEW.rtl_layout, "<br/>");
    END IF;

    IF NEW.box_container <> OLD.box_container THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Box Container: ", OLD.box_container, " -> ", NEW.box_container , "<br/>");
    END IF;
    
    IF LENGTH(admin_audit_log) > 0 THEN
        INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('admin_ui_customization_setting', NEW.external_id, admin_audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER ui_customization_setting_trigger_insert
AFTER INSERT ON admin_ui_customization_setting
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT 'UI Customization created. <br/>';

    IF NEW.theme_contrast <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Theme Contrast: ", NEW.theme_contrast);
    END IF;

    IF NEW.caption_show <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Caption Show: ", NEW.caption_show);
    END IF;

    IF NEW.preset_theme <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Preset Theme: ", NEW.preset_theme);
    END IF;

    IF NEW.dark_layout <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Dark Layout: ", NEW.dark_layout);
    END IF;

    IF NEW.rtl_layout <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>RTL Layout: ", NEW.rtl_layout);
    END IF;

    IF NEW.box_container <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Box Container: ", NEW.box_container);
    END IF;

    INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('admin_ui_customization_setting', NEW.external_id, admin_audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_ui_customization_setting_exist(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT COUNT(*) AS total
    FROM admin_ui_customization_setting
    WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //

CREATE PROCEDURE insert_ui_customization_setting(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
BEGIN
	IF p_type = 'theme_contrast' THEN
        INSERT INTO admin_ui_customization_setting (user_id, email_address, theme_contrast, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'caption_show' THEN
        INSERT INTO admin_ui_customization_setting (user_id, email_address, caption_show, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'preset_theme' THEN
        INSERT INTO admin_ui_customization_setting (user_id, email_address, preset_theme, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'dark_layout' THEN
        INSERT INTO admin_ui_customization_setting (user_id, email_address, dark_layout, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSEIF p_type = 'rtl_layout' THEN
        INSERT INTO admin_ui_customization_setting (user_id, email_address, rtl_layout, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    ELSE
        INSERT INTO admin_ui_customization_setting (user_id, email_address, box_container, last_log_by) 
	    VALUES(p_user_id, p_email_address, p_customization_value, p_last_log_by);
    END IF;
END //

CREATE PROCEDURE update_ui_customization_setting(IN p_user_id INT(10), IN p_email_address VARCHAR(100), IN p_type VARCHAR(30), IN p_customization_value VARCHAR(15), IN p_last_log_by INT(10))
BEGIN
	IF p_type = 'theme_contrast' THEN
        UPDATE admin_ui_customization_setting
        SET theme_contrast = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'caption_show' THEN
        UPDATE admin_ui_customization_setting
        SET caption_show = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'preset_theme' THEN
        UPDATE admin_ui_customization_setting
        SET preset_theme = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'dark_layout' THEN
        UPDATE admin_ui_customization_setting
        SET dark_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSEIF p_type = 'rtl_layout' THEN
        UPDATE admin_ui_customization_setting
        SET rtl_layout = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    ELSE
        UPDATE admin_ui_customization_setting
        SET box_container = p_customization_value,
        last_log_by = p_last_log_by
       	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
    END IF;
END //

CREATE PROCEDURE get_ui_customization_setting_details(IN p_user_id INT(10), IN p_email_address VARCHAR(100))
BEGIN
    SELECT external_id, user_id, email_address, theme_contrast, caption_show, preset_theme, dark_layout, rtl_layout, box_container, last_log_by
	FROM admin_ui_customization_setting 
	WHERE user_id = p_user_id OR email_address = BINARY p_email_address;
END //