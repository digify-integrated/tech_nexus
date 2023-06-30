<?php
/**
* Class MenuGroupModel
*
* The MenuGroupModel class handles menu item related operations and interactions.
*/
class MenuGroupModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateMenuGroup
    # Description: Updates the menu group.
    #
    # Parameters:
    # - $p_menu_group_id (int): The menu group ID.
    # - $p_menu_group_name (string): The menu group name.
    # - $p_order_sequence (int): The order sequence of menu group.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateMenuGroup($p_menu_group_id, $p_menu_group_name, $p_order_sequence, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateMenuGroup(:p_menu_group_id, :p_menu_group_name, :p_order_sequence, :p_last_log_by)");
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->bindParam(':p_menu_group_name', $p_menu_group_name);
        $stmt->bindParam(':p_order_sequence', $p_order_sequence);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertMenuGroup
    # Description: Inserts the menu group.
    #
    # Parameters:
    # - $p_menu_group_name (string): The menu group name.
    # - $p_order_sequence (int): The order sequence of menu group.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertMenuGroup($p_menu_group_name, $p_order_sequence, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL insertMenuGroup(:p_menu_group_name, :p_order_sequence, :p_last_log_by, @p_menu_group_id)");
        $stmt->bindParam(':p_menu_group_name', $p_menu_group_name);
        $stmt->bindParam(':p_order_sequence', $p_order_sequence);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_menu_group_id AS menu_group_id");
        $menu_group_id = $result->fetch(PDO::FETCH_ASSOC)['menu_group_id'];

        return $menu_group_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkMenuGroupExist
    # Description: Checks if a menu group exists.
    #
    # Parameters:
    # - $p_menu_group_id (int): The menu group ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkMenuGroupExist($p_menu_group_id) {
        $stmt = $this->db->getConnection()->prepare("CALL checkMenuGroupExist(:p_menu_group_id)");
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMenuGroup
    # Description: Deletes the menu group.
    #
    # Parameters:
    # - $p_menu_group_id (int): The menu group ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteMenuGroup($p_menu_group_id) {
        $stmt = $this->db->getConnection()->prepare("CALL deleteMenuGroup(:p_menu_group_id)");
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLinkedMenuItem
    # Description: Deletes all the linked menu item on the menu group.
    #
    # Parameters:
    # - $p_menu_group_id (int): The menu group ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedMenuItem($p_menu_group_id) {
        $stmt = $this->db->getConnection()->prepare("CALL deleteLinkedMenuItem(:p_menu_group_id)");
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateMenuGroup
    # Description: Inserts the menu group.
    #
    # Parameters:
    # - $p_menu_group_name (string): The menu group name.
    # - $p_order_sequence (int): The order sequence of menu group.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateMenuGroup($p_menu_group_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL duplicateMenuGroup(:p_menu_group_id, :p_last_log_by, @p_new_menu_group_id)");
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_menu_group_id AS menu_group_id");
        $menu_group_id = $result->fetch(PDO::FETCH_ASSOC)['menu_group_id'];

        return $menu_group_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getMenuGroup
    # Description: Retrieves the details of a menu group.
    #
    # Parameters:
    # - $p_menu_group_id (int): The menu group ID.
    #
    # Returns:
    # - An array containing the menu group details.
    #
    # -------------------------------------------------------------
    public function getMenuGroup($p_menu_group_id) {
        $stmt = $this->db->getConnection()->prepare("CALL getMenuGroup(:p_menu_group_id)");
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>