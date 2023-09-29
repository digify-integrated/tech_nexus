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
    # Function: checkRoleExist
    # Description: Checks if a role exists.
    #
    # Parameters:
    # - $p_role_id (int): The role ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkRoleExist($p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkRoleExist(:p_role_id)');
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
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
        $stmt->bindValue(':p_menu_item_id', $p_menu_item_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSystemActionRoleExist
    # Description: Checks if a system action role exists.
    #
    # Parameters:
    # - $p_system_action_id (int): The system action ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSystemActionRoleExist($p_system_action_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSystemActionRoleExist(:p_system_action_id, :p_role_id)');
        $stmt->bindValue(':p_system_action_id', $p_system_action_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkRoleUserExist
    # Description: Checks if a user role exists.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkRoleUserExist($p_user_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkRoleUserExist(:p_user_id, :p_role_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateRole
    # Description: Updates the role.
    #
    # Parameters:
    # - $p_role_id (int): The role ID.
    # - $p_role_name (string): The role name.
    # - $p_role_description (string): The role description.
    # - $assignable (int): The role status if assignable or not.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateRole($p_role_id, $p_role_name, $p_role_description, $p_assignable, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateRole(:p_role_id, :p_role_name, :p_role_description, :p_assignable, :p_last_log_by)');
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_name', $p_role_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_role_description', $p_role_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_assignable', $p_assignable, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
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
        $stmt->bindValue(':p_menu_item_id', $p_menu_item_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_access_type', $p_access_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_access', $p_access, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateRoleSystemActionAccess
    # Description: Updates the system action access of the role.
    #
    # Parameters:
    # - $p_system_action_id (int): The menu item ID.
    # - $p_role_id (int): The role ID.
    # - $p_access (bool): The access either true or false.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateRoleSystemActionAccess($p_system_action_id, $p_role_id, $p_access, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateRoleSystemActionAccess(:p_system_action_id, :p_role_id, :p_access, :p_last_log_by)');
        $stmt->bindValue(':p_system_action_id', $p_system_action_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_access', $p_access, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertRole
    # Description: Inserts the role.
    #
    # Parameters:
    # - $p_role_name (string): The role name.
    # - $p_role_description (string): The role description.
    # - $p_assignable (int): The role status if assignable or not.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertRole($p_role_name, $p_role_description, $p_assignable, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertRole(:p_role_name, :p_role_description, :p_assignable, :p_last_log_by, @p_role_id)');
        $stmt->bindValue(':p_role_name', $p_role_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_role_description', $p_role_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_assignable', $p_assignable, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_role_id AS role_id");
        $roleID = $result->fetch(PDO::FETCH_ASSOC)['role_id'];

        return $roleID;
    }
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
        $stmt->bindValue(':p_menu_item_id', $p_menu_item_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertRoleSystemActionAccess
    # Description: Inserts the system action access of the role.
    #
    # Parameters:
    # - $p_system_action_id (int): The system action ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertRoleSystemActionAccess($p_system_action_id, $p_role_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertRoleSystemActionAccess(:p_system_action_id, :p_role_id, :p_last_log_by)');
        $stmt->bindValue(':p_system_action_id', $p_system_action_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertRoleUser
    # Description: Inserts the system action access of the role.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertRoleUser($p_user_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL insertRoleUser(:p_user_id, :p_role_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteRole
    # Description: Deletes the role.
    #
    # Parameters:
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteRole($p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteRole(:p_role_id)');
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteLinkedRoleUser
    # Description: Deletes the all the linked role users table.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedRoleUser($_user_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLinkedRoleUser(:_user_id, :p_role_id)');
        $stmt->bindValue(':_user_id', $_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteMenuItemRoleAccess
    # Description: Deletes the menu item role access.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteMenuItemRoleAccess($p_menu_item_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteMenuItemRoleAccess(:p_menu_item_id, :p_role_id)');
        $stmt->bindValue(':p_menu_item_id', $p_menu_item_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteSystemActionRoleAccess
    # Description: Deletes the system action role access.
    #
    # Parameters:
    # - $p_system_action_id (int): The system action ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSystemActionRoleAccess($p_system_action_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSystemActionRoleAccess(:p_system_action_id, :p_role_id)');
        $stmt->bindValue(':p_system_action_id', $p_system_action_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteRoleUser
    # Description: Deletes the user role access.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteRoleUser($p_user_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteRoleUser(:p_user_id, :p_role_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLinkedMenuItemAccessRight
    # Description: Deletes the all of the linked menu item role access.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedMenuItemAccessRight($p_menu_item_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLinkedMenuItemAccessRight(:p_menu_item_id, :p_role_id)');
        $stmt->bindValue(':p_menu_item_id', $p_menu_item_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLinkedSystemActionAccessRight
    # Description: Deletes the all of the system action role access.
    #
    # Parameters:
    # - $p_system_action_id (int): The system action ID.
    # - $p_role_id (int): The role ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedSystemActionAccessRight($p_system_action_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLinkedSystemActionAccessRight(:p_system_action_id, :p_role_id)');
        $stmt->bindValue(':p_system_action_id', $p_system_action_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
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
    # - An array containing the role menu access details.
    #
    # -------------------------------------------------------------
    public function getRoleMenuAccess($p_menu_item_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getRoleMenuAccess(:p_menu_item_id, :p_role_id)');
        $stmt->bindValue(':p_menu_item_id', $p_menu_item_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getRoleSystemActionAccess
    # Description: Retrieves the details of a role system action access.
    #
    # Parameters:
    # - $p_system_action_id (int): The system action ID.
    # - $role_id (int): The role ID.
    #
    # Returns:
    # - An array containing the role system action details.
    #
    # -------------------------------------------------------------
    public function getRoleSystemActionAccess($p_system_action_id, $p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getRoleSystemActionAccess(:p_system_action_id, :p_role_id)');
        $stmt->bindValue(':p_system_action_id', $p_system_action_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getRole
    # Description: Retrieves the details of a role.
    #
    # Parameters:
    # - $p_role_id (int): The role ID.
    #
    # Returns:
    # - An array containing the role details.
    #
    # -------------------------------------------------------------
    public function getRole($p_role_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getRole(:p_role_id)');
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateRole
    # Description: Duplicates the role.
    #
    # Parameters:
    # - $p_role_id (int): The role ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateRole($p_role_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateRole(:p_role_id, :p_last_log_by, @p_new_role_id)');
        $stmt->bindValue(':p_role_id', $p_role_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_role_id AS role_id");
        $roleID = $result->fetch(PDO::FETCH_ASSOC)['role_id'];

        return $roleID;
    }
    # -------------------------------------------------------------
}
?>