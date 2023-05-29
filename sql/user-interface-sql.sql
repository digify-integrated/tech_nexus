/* Menu groups table */
CREATE TABLE admin_menu_groups (
    external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    menu_group_name VARCHAR(100) NOT NULL,
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX menu_groups_index_external_id ON admin_menu_groups(external_id);

INSERT INTO admin_menu_groups (menu_group_name, order_sequence, last_log_by) VALUES ('Administration', '1', '1');

CREATE TRIGGER menu_groups_trigger_update
AFTER UPDATE ON admin_menu_groups
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT '';

    IF NEW.menu_group_name <> OLD.menu_group_name THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Menu Group Name: ", OLD.menu_group_name, " -> ", NEW.menu_group_name, "<br/>");
    END IF;

    IF NEW.order_sequence <> OLD.order_sequence THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Order Sequence: ", OLD.order_sequence, " -> ", NEW.order_sequence, "<br/>");
    END IF;
    
    IF LENGTH(admin_audit_log) > 0 THEN
        INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('admin_menu_groups', NEW.external_id, admin_audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER menu_groups_trigger_insert
AFTER INSERT ON admin_menu_groups
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT 'Menu group created. <br/>';

    IF NEW.menu_group_name <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Menu Group Name: ", NEW.menu_group_name);
    END IF;

    IF NEW.order_sequence <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Order Sequence: ", NEW.order_sequence);
    END IF;

    INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('admin_menu_groups', NEW.external_id, admin_audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_menu_groups_exist(IN p_external_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM admin_menu_groups
    WHERE external_id = p_external_id;
END //

CREATE PROCEDURE insert_menu_groups(IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10), OUT p_external_id INT(10))
BEGIN
    INSERT INTO admin_menu_groups (menu_group_name, order_sequence, last_log_by) 
	VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
	
    SET p_external_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE duplicate_menu_groups(IN p_external_id INT(10), IN p_last_log_by INT(10), OUT p_new_external_id INT(10))
BEGIN
    DECLARE p_menu_group_name VARCHAR(255);
    DECLARE p_order_sequence INT(10);
    
    SELECT menu_group_name, order_sequence 
    INTO p_menu_group_name, p_order_sequence 
    FROM admin_menu_groups 
    WHERE external_id = p_external_id;
    
    INSERT INTO admin_menu_groups (menu_group_name, order_sequence, last_log_by) 
    VALUES(p_menu_group_name, p_order_sequence, p_last_log_by);
    
    SET p_new_external_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE update_menu_groups(IN p_external_id INT(10), IN p_menu_group_name VARCHAR(100), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10))
BEGIN
	UPDATE admin_menu_groups
        SET menu_group_name = p_menu_group_name,
        order_sequence = p_order_sequence,
        last_log_by = p_last_log_by
       	WHERE external_id = p_external_id;
END //

CREATE PROCEDURE delete_menu_groups(IN p_external_id INT(10))
BEGIN
    DELETE FROM admin_menu_groups WHERE external_id = p_external_id;
END //

CREATE PROCEDURE get_menu_groups_details(IN p_external_id INT(10))
BEGIN
    SELECT menu_group_name, order_sequence, last_log_by
	FROM admin_menu_groups 
	WHERE external_id = p_external_id;
END //

/* Menu item table */
CREATE TABLE admin_menu_item(
	external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	menu_item_name VARCHAR(100) NOT NULL,
	menu_group_id INT(10) UNSIGNED NOT NULL,
	menu_item_url VARCHAR(50),
	parent_id INT(10) UNSIGNED,
	menu_item_icon VARCHAR(150),
    order_sequence TINYINT(10) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX menu_item_index_external_id ON admin_menu_item(external_id);

ALTER TABLE admin_menu_item
ADD FOREIGN KEY (menu_group_id) REFERENCES admin_menu_groups(external_id);

CREATE TRIGGER menu_item_trigger_update
AFTER UPDATE ON admin_menu_item
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT '';

    IF NEW.menu_item_name <> OLD.menu_item_name THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Menu Item Name: ", OLD.menu_item_name, " -> ", NEW.menu_item_name, "<br/>");
    END IF;

    IF NEW.menu_group_id <> OLD.menu_group_id THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Menu Group ID: ", OLD.menu_group_id, " -> ", NEW.menu_group_id, "<br/>");
    END IF;

    IF NEW.menu_item_url <> OLD.parent_id THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "URL: ", OLD.menu_item_url, " -> ", NEW.menu_item_url, "<br/>");
    END IF;

    IF NEW.parent_id <> OLD.parent_id THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Parent ID: ", OLD.parent_id, " -> ", NEW.parent_id, "<br/>");
    END IF;

    IF NEW.menu_item_icon <> OLD.menu_item_icon THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Menu Item Icon: ", OLD.menu_item_icon, " -> ", NEW.menu_item_icon, "<br/>");
    END IF;

    IF NEW.order_sequence <> OLD.order_sequence THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Order Sequence: ", OLD.order_sequence, " -> ", NEW.order_sequence, "<br/>");
    END IF;
    
    IF LENGTH(admin_audit_log) > 0 THEN
        INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('admin_menu_item', NEW.external_id, admin_audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER menu_item_trigger_insert
AFTER INSERT ON admin_menu_item
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT 'Menu item created. <br/>';

    IF NEW.menu_item_name <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Menu Item Name: ", NEW.menu_item_name);
    END IF;

    IF NEW.menu_group_id <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Menu Group ID: ", NEW.menu_group_id);
    END IF;

    IF NEW.menu_item_url <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>URL: ", NEW.menu_item_url);
    END IF;

    IF NEW.parent_id <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Parent ID: ", NEW.parent_id);
    END IF;

    IF NEW.menu_item_icon <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Menu Item Icon: ", NEW.menu_item_icon);
    END IF;

    IF NEW.order_sequence <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Order Sequence: ", NEW.order_sequence);
    END IF;

    INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('admin_menu_item', NEW.external_id, admin_audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_menu_item_exist(IN p_external_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM admin_menu_item
    WHERE external_id = p_external_id;
END //

CREATE PROCEDURE insert_menu_item(IN p_menu_item_name VARCHAR(100), IN p_menu_group_id INT(10), IN p_menu_item_url VARCHAR(50), IN p_parent_id INT(10), IN p_menu_item_icon VARCHAR(150), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10), OUT p_external_id INT(10))
BEGIN
    INSERT INTO admin_menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) 
	VALUES(p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
	
    SET p_external_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE duplicate_menu_item(IN p_external_id INT(10), IN p_last_log_by INT(10), OUT p_new_external_id INT(10))
BEGIN
    DECLARE p_menu_item_name VARCHAR(255);
    DECLARE p_menu_group_id INT(10);
    DECLARE p_menu_item_url VARCHAR(50);
    DECLARE p_parent_id INT(10);
    DECLARE p_menu_item_icon VARCHAR(150);
    DECLARE p_order_sequence TINYINT(10);
    
    SELECT menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence 
    INTO p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence 
    FROM admin_menu_item  
    WHERE external_id = p_external_id;
    
    INSERT INTO admin_menu_item (menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by) 
    VALUES(p_menu_item_name, p_menu_group_id, p_menu_item_url, p_parent_id, p_menu_item_icon, p_order_sequence, p_last_log_by);
    
    SET p_new_external_id = LAST_INSERT_ID();
END //

CREATE PROCEDURE update_menu_item(IN p_external_id INT(10), IN p_menu_item_name VARCHAR(100), IN p_menu_group_id INT(10), IN p_menu_item_url VARCHAR(50), IN p_parent_id INT(10), IN p_menu_item_icon VARCHAR(150), IN p_order_sequence TINYINT(10), IN p_last_log_by INT(10))
BEGIN
	UPDATE admin_menu_item
        SET menu_item_name = p_menu_item_name,
        menu_group_id = p_menu_group_id,
        menu_item_url = p_menu_item_url,
        parent_id = p_parent_id,
        menu_item_icon = p_menu_item_icon,
        order_sequence = p_order_sequence,
        last_log_by = p_last_log_by
       	WHERE external_id = p_external_id;
END //

CREATE PROCEDURE delete_menu_item(IN p_external_id INT(10))
BEGIN
    DELETE FROM admin_menu_item WHERE external_id = p_external_id;
END //

CREATE PROCEDURE get_menu_item_details(IN p_external_id INT(10))
BEGIN
    SELECT menu_item_name, menu_group_id, menu_item_url, parent_id, menu_item_icon, order_sequence, last_log_by
	FROM admin_menu_item 
	WHERE external_id = p_external_id;
END //

CREATE PROCEDURE delete_all_menu_item(IN p_menu_group_id INT(10))
BEGIN
    DELETE FROM admin_menu_item WHERE menu_group_id = p_menu_group_id;
END //

/* Build menu */
CREATE PROCEDURE build_menu_group(IN p_user_id INT(10))
BEGIN
    SELECT DISTINCT(mg.external_id) as external_id, mg.menu_group_name
    FROM admin_menu_groups mg
    JOIN admin_menu_item mi ON mi.menu_group_id = mg.external_id
    WHERE EXISTS (
        SELECT 1
        FROM admin_menu_access_right mar
        WHERE mar.menu_item_id = mi.menu_item_id
        AND mar.read_access = 1
        AND mar.role_id IN (
            SELECT role_id
            FROM admin_role_users
            WHERE user_id = p_user_id
        )
    )
    ORDER BY mg.order_sequence;
END //

CREATE PROCEDURE build_menu_item(IN p_user_id INT(10), IN p_menu_group_id INT(10))
BEGIN
    SELECT mi.external_id, mi.menu_item_name, mi.menu_group_id, mi.menu_item_url, mi.parent_id, mi.menu_item_icon
    FROM admin_menu_item AS mi
    INNER JOIN admin_menu_access_right AS mar ON mi.external_id = mar.menu_item_id
    INNER JOIN admin_role_users AS ru ON mar.role_id = ru.role_id
    WHERE mar.read_access = 1 AND ru.user_id = p_user_id AND mi.menu_group_id = p_menu_group_id
    ORDER BY mi.order_sequence;
END //

/* Menu access right table */
CREATE TABLE admin_menu_access_right(
	menu_item_id INT(10) NOT NULL,
	role_id INT(10) UNSIGNED NOT NULL,
	read_access TINYINT(1) NOT NULL,
    write_access TINYINT(1) NOT NULL,
    create_access TINYINT(1) NOT NULL,
    delete_access TINYINT(1) NOT NULL,
    last_log_by INT(10) NOT NULL
);

INSERT INTO admin_menu_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, last_log_by) VALUES ('1', '1', '1', '1', '1', '1', '1');
INSERT INTO admin_menu_access_right (menu_item_id, role_id, read_access, write_access, create_access, delete_access, last_log_by) VALUES ('2', '1', '1', '1', '1', '1', '1');

CREATE TRIGGER menu_access_right_trigger_update
AFTER UPDATE ON admin_menu_access_right
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT '';

    SET admin_audit_log = CONCAT(admin_audit_log, "Role ID: ", OLD.role_id, "<br/>");

    IF NEW.read_access <> OLD.read_access THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Read Access: ", OLD.read_access, " -> ", NEW.read_access, "<br/>");
    END IF;

    IF NEW.write_access <> OLD.write_access THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Write Access: ", OLD.write_access, " -> ", NEW.write_access, "<br/>");
    END IF;

    IF NEW.create_access <> OLD.create_access THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Create Access: ", OLD.create_access, " -> ", NEW.create_access, "<br/>");
    END IF;

    IF NEW.delete_access <> OLD.delete_access THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "Delete Access: ", OLD.delete_access, " -> ", NEW.delete_access, "<br/>");
    END IF;
    
    IF LENGTH(admin_audit_log) > 0 THEN
        INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
        VALUES ('admin_menu_access_right', NEW.menu_item_id, admin_audit_log, NEW.last_log_by, NOW());
    END IF;
END //

CREATE TRIGGER menu_access_right_trigger_insert
AFTER INSERT ON admin_menu_access_right
FOR EACH ROW
BEGIN
    DECLARE admin_audit_log TEXT DEFAULT 'Menu item access rights created. <br/>';

    IF NEW.role_id <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Role ID: ", NEW.role_id);
    END IF;

    IF NEW.read_access <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Read Access: ", NEW.read_access);
    END IF;

    IF NEW.write_access <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Write Access: ", NEW.write_access);
    END IF;

    IF NEW.create_access <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Create Access: ", NEW.create_access);
    END IF;

    IF NEW.delete_access <> '' THEN
        SET admin_audit_log = CONCAT(admin_audit_log, "<br/>Delete Access: ", delete_access.menu_item_icon);
    END IF;

    INSERT INTO admin_audit_log (table_name, reference_id, log, changed_by, changed_at) 
    VALUES ('admin_menu_access_right', NEW.menu_item_id, admin_audit_log, NEW.last_log_by, NOW());
END //

CREATE PROCEDURE check_role_menu_access_right_exist(IN p_menu_item_id INT(10), IN p_role_id INT(10))
BEGIN
    SELECT COUNT(*) AS total
    FROM admin_menu_access_right
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END //

CREATE PROCEDURE insert_role_menu_access_right(IN p_menu_item_id INT(10), IN p_role_id INT(10))
BEGIN
    INSERT INTO admin_menu_access_right (menu_item_id, role_id) 
	VALUES(p_menu_item_id, p_role_id);
END //

CREATE PROCEDURE update_role_menu_access_right(IN p_menu_item_id INT(10), IN p_role_id INT(10), IN p_access_type VARCHAR(10), IN p_access TINYINT(1))
BEGIN
	IF p_access_type = 'read' THEN
        UPDATE admin_menu_access_right
        SET read_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'write' THEN
        UPDATE admin_menu_access_right
        SET write_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSEIF p_access_type = 'create' THEN
        UPDATE admin_menu_access_right
        SET create_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    ELSE
        UPDATE admin_menu_access_right
        SET delete_access = p_access
        WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
    END IF;
END //

CREATE PROCEDURE check_menu_access_rights(IN p_user_id INT(10), IN p_menu_item_id INT(10), IN p_access_type VARCHAR(10))
BEGIN
	IF p_access_type = 'read' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM admin_role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM admin_menu_access_right where read_access = '1' AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'write' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM admin_role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM admin_menu_access_right where write_access = '1' AND menu_item_id = menu_item_id);
    ELSEIF p_access_type = 'create' THEN
        SELECT COUNT(role_id) AS TOTAL
        FROM admin_role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM admin_menu_access_right where create_access = '1' AND menu_item_id = menu_item_id);
    ELSE
        SELECT COUNT(role_id) AS TOTAL
        FROM admin_role_users
        WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM admin_menu_access_right where delete_access = '1' AND menu_item_id = menu_item_id);
    END IF;
END //

CREATE PROCEDURE get_role_menu_access_rights(IN p_menu_item_id INT(10), IN p_role_id INT(10))
BEGIN
    SELECT read_access, write_access, create_access, delete_access
    FROM admin_menu_access_right 
    WHERE menu_item_id = p_menu_item_id AND role_id = p_role_id;
END //

/* System action */
CREATE TABLE admin_system_action(
	external_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	system_action_name VARCHAR(100) NOT NULL,
    last_log_by INT(10) NOT NULL
);

CREATE INDEX system_action_index_external_id ON admin_system_action(external_id);

INSERT INTO admin_system_action (system_action_name, last_log_by) VALUES ('Assign Menu Item Role Access', '1');

/* System action access rights */
CREATE TABLE admin_system_action_access_rights(
	system_action_id INT(10) UNSIGNED NOT NULL,
	role_id INT(10) UNSIGNED NOT NULL
);

INSERT INTO admin_system_action_access_rights (system_action_id, role_id) VALUES ('1', '1');

CREATE PROCEDURE check_system_action_access_rights(IN p_user_id INT(10), IN p_system_action_id INT(10))
BEGIN
	SELECT COUNT(role_id) AS TOTAL
    FROM admin_role_users
    WHERE user_id = p_user_id AND role_id IN (SELECT role_id FROM admin_system_action_access_rights where system_action_id = p_system_action_id);
END //