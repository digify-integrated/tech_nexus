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
    # Function: generateMenuItemOptions
    # Description: Generates the menu item options.
    #
    # Parameters:None
    #
    # Returns: String.
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
                $menuItemID = $row['menu_item_id'];
                $menuItemName = $row['menu_item_name'];

                $htmlOptions .= "<option value='". htmlspecialchars($menuItemID, ENT_QUOTES) ."'>". htmlspecialchars($menuItemName, ENT_QUOTES) ."</option>";
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
    # Function: updateMenuItem
    # Description: Updates the menu item.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_menu_item_name (string): The menu item name.
    # - $p_menu_group_id (int): The menu group ID.
    # - $p_menu_item_url (string): The menu item url.
    # - $p_parent_id (int): The menu item's parent ID.
    # - $p_menu_item_icon (string): The menu item icon.
    # - $p_order_sequence (int): The order sequence of menu item.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateMenuItem($p_menu_item_id, $p_menu_item_name, $p_menu_group_id, $p_menu_item_url, $p_parent_id, $p_menu_item_icon, $p_order_sequence, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateMenuItem(:p_menu_item_id, :p_menu_item_name, :p_menu_group_id, :p_menu_item_url, :p_parent_id, :p_menu_item_icon, :p_order_sequence, :p_last_log_by)");
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->bindParam(':p_menu_item_name', $p_menu_item_name);
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->bindParam(':p_menu_item_url', $p_menu_item_url);
        $stmt->bindParam(':p_parent_id', $p_parent_id);
        $stmt->bindParam(':p_menu_item_icon', $p_menu_item_icon);
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
    # Function: insertMenuItem
    # Description: Inserts the menu item.
    #
    # Parameters:
    # - $p_menu_item_name (string): The menu item name.
    # - $p_menu_group_id (int): The menu group ID.
    # - $p_menu_item_url (string): The menu item url.
    # - $p_parent_id (int): The menu item's parent ID.
    # - $p_menu_item_icon (string): The menu item icon.
    # - $p_order_sequence (int): The order sequence of menu item.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertMenuItem($p_menu_item_name, $p_menu_group_id, $p_menu_item_url, $p_parent_id, $p_menu_item_icon, $p_order_sequence, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL insertMenuItem(:p_menu_item_name, :p_menu_group_id, :p_menu_item_url, :p_parent_id, :p_menu_item_icon, :p_order_sequence, :p_last_log_by, @p_menu_item_id)");
        $stmt->bindParam(':p_menu_item_name', $p_menu_item_name);
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->bindParam(':p_menu_item_url', $p_menu_item_url);
        $stmt->bindParam(':p_parent_id', $p_parent_id);
        $stmt->bindParam(':p_menu_item_icon', $p_menu_item_icon);
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
    # Function: checkMenuItemExist
    # Description: Checks if a menu item exists.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkMenuItemExist($p_menu_item_id) {
        $stmt = $this->db->getConnection()->prepare("CALL checkMenuItemExist(:p_menu_item_id)");
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMenuItem
    # Description: Deletes the menu item.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteMenuItem($p_menu_item_id) {
        $stmt = $this->db->getConnection()->prepare("CALL deleteMenuItem(:p_menu_item_id)");
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getMenuItem
    # Description: Retrieves the details of a menu item.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    #
    # Returns:
    # - An array containing the menu item details.
    #
    # -------------------------------------------------------------
    public function getMenuItem($p_menu_item_id) {
        $stmt = $this->db->getConnection()->prepare("CALL getMenuItem(:p_menu_item_id)");
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>