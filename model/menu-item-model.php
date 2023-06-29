<?php
/**
* Class MenuItemModel
*
* The MenuItemModel class handles menu item related operations and interactions.
*/
class MenuItemModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUICustomizationSetting
    # Description: Retrieves the UI customization setting for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    #
    # Returns: The UI customization setting as an associative array.
    #
    # -------------------------------------------------------------
    public function generateMenuItemOptions() {
        $stmt = $this->db->getConnection()->prepare("CALL generateMenuItemOptions()");
        $stmt->execute();
        $count = $stmt->rowCount();

        if($count > 0){
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $htmlOptions = '';

            foreach ($options as $row) {
                $menu_item_id = $row['menu_item_id'];
                $menu_item_name = $row['menu_item_name'];

                $htmlOptions .= "<option value='". htmlspecialchars($menu_item_id, ENT_QUOTES) ."'>". htmlspecialchars($menu_item_name, ENT_QUOTES) ."</option>";
            }

            return $htmlOptions;
        }
    }
    # -------------------------------------------------------------

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
        $stmt = $this->db->getConnection()->prepare("CALL insertMenuGroup(:p_menu_group_name, :p_order_sequence, :p_last_log_by)");
        $stmt->bindParam(':p_menu_group_name', $p_menu_group_name);
        $stmt->bindParam(':p_order_sequence', $p_order_sequence);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
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
        $stmt = $this->db->getConnection()->prepare("CALL checkMenuGroupExist(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
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
        $stmt = $this->db->getConnection()->prepare("CALL deleteMenuGroup(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->execute();
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