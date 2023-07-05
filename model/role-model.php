<?php
/**
* Class RoleModel
*
* The RoleModel class handles role related operations and interactions.
*/
class RoleModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkRoleMenuAccessExist
    # Description: Checks if a menu access exists.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkRoleMenuAccessExist($p_menu_item_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkRoleMenuAccessExist(:p_menu_item_id, :p_role_id)');
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->bindParam(':p_role_id', $p_role_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateRoleMenuAccess
    # Description: Updates the menu access of the role.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_role_id (int): The role ID.
    # - $p_access_type (string): The type of access.
    # - $p_access (bool): The access either true or false.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateRoleMenuAccess($p_menu_item_id, $p_role_id, $p_access_type, $p_access, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateRoleMenuAccess(:p_menu_item_id, :p_role_id, :p_access_type, :p_access, :p_last_log_by)');
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->bindParam(':p_role_id', $p_role_id);
        $stmt->bindParam(':p_access_type', $p_access_type);
        $stmt->bindParam(':p_access', $p_access);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertRoleMenuAccess
    # Description: Inserts the menu access of the role.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_role_id (int): The role ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertRoleMenuAccess($p_menu_item_id, $p_role_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertRoleMenuAccess(:p_menu_item_id, :p_role_id, :p_last_log_by)');
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->bindParam(':p_role_id', $p_role_id);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getRoleMenuAccess
    # Description: Retrieves the details of a role menu access.
    #
    # Parameters:
    # - $p_menu_item_id (int): The role ID.
    # - $role_id (int): The role ID.
    #
    # Returns:
    # - An array containing the role details.
    #
    # -------------------------------------------------------------
    public function getRoleMenuAccess($p_menu_item_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getRoleMenuAccess(:p_menu_item_id, :p_role_id)');
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->bindParam(':p_role_id', $p_role_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>