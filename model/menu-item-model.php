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
    #   Build methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: buildMenuItem
    # Description: Generates the menu item options.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_menu_group_id (int): The menu group ID.
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function buildMenuItem($p_user_id, $p_menu_group_id) {
        $menuItems = array();

        $stmt = $this->db->getConnection()->prepare('CALL buildMenuItem(:p_user_id, :p_menu_group_id)');
        $stmt->bindValue(':p_user_id', $p_user_id);
        $stmt->bindValue(':p_menu_group_id', $p_menu_group_id);
        $stmt->execute();
        $count = $stmt->rowCount();

        if($count > 0){
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            
            $htmlOptions = '';

            foreach ($options as $row) {
                $menuItemID = $row['menu_item_id'];
                $menuItemName = $row['menu_item_name'];
                $menuItemURL = $row['menu_item_url'];
                $parentID = $row['parent_id'];
                $menuItemIcon = $row['menu_item_icon'];

                $menuItem = array(
                    'MENU_ITEM_ID' => $menuItemID,
                    'MENU_ITEM_NAME' => $menuItemName,
                    'MENU_ITEM_URL' => $menuItemURL,
                    'PARENT_ID' => $parentID,
                    'MENU_ITEM_ICON' => $menuItemIcon,
                    'CHILDREN' => array()
                );

                $menuItems[$menuItemID] = $menuItem;
            }

            foreach ($menuItems as $menuItem) {
                if (!empty($menuItem['PARENT_ID']) && isset($menuItems[$menuItem['PARENT_ID']])) {
                    $menuItems[$menuItem['PARENT_ID']]['CHILDREN'][] = &$menuItems[$menuItem['MENU_ITEM_ID']];
                }
            }

            $rootMenuItems = array_filter($menuItems, function ($item) {
                return empty($item['PARENT_ID']);
            });

            $html = '';

            foreach ($rootMenuItems as $rootMenuItem) {
                $html .= $this->buildMenuItemHTML($rootMenuItem);
            }

            return $html;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: buildMenuItemHTML
    # Description: Generates the menu item html.
    #
    # Parameters:
    # - $p_menu_item (array): The menu item details.
    # - $level (int): The menu item level.
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function buildMenuItemHTML($p_menu_item, $level = 1) {
        $html = '';
        $menu_item_name = $p_menu_item['MENU_ITEM_NAME'];
        $menu_item_icon = $p_menu_item['MENU_ITEM_ICON'];
        $menu_item_url = $p_menu_item['MENU_ITEM_URL'];
        $children = $p_menu_item['CHILDREN'];
    
        if ($level === 1) {
            if (empty($children)) {
                $html .= '<li class="pc-item">';
                $html .= '<a href="' . $menu_item_url . '" class="pc-link">';
                $html .= '<span class="pc-micon">';
                $html .= '<i data-feather="'. $menu_item_icon .'"></i>';
                $html .= '</span>';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            } 
            else {
                $html .= '<li class="pc-item pc-hasmenu">';
                $html .= '<a href="JavaScript:void(0);" class="pc-link">';
                $html .= '<span class="pc-micon">';
                $html .= ' <i data-feather="'. $menu_item_icon .'"></i>';
                $html .= '</span>';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '<span class="pc-arrow"><i data-feather="chevron-right"></i></span>';
                $html .= '</a>';
                $html .= '<ul class="pc-submenu">';
    
                foreach ($children as $child) {
                    $html .= $this->buildMenuItemHTML($child, $level + 1);
                }
    
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
        else {
            if (empty($children)) {
                $html .= '<li class="pc-item">';
                $html .= '<a href="' . $menu_item_url . '" class="pc-link">';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            } 
            else {
                $html .= '<li class="pc-item pc-hasmenu">';
                $html .= '<a href="JavaScript:void(0);" class="pc-link">';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '<span class="pc-arrow"><i data-feather="chevron-right"></i></span>';
                $html .= '</a>';
                $html .= '<ul class="pc-submenu">';
    
                foreach ($children as $child) {
                    $html .= $this->buildMenuItemHTML($child, $level + 1);
                }
    
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
    
        return $html;
    }
    # -------------------------------------------------------------

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
        $stmt = $this->db->getConnection()->prepare('CALL generateMenuItemOptions()');
        $stmt->execute();
        $count = $stmt->rowCount();

        if($count > 0){
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
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
        $stmt = $this->db->getConnection()->prepare('CALL updateMenuItem(:p_menu_item_id, :p_menu_item_name, :p_menu_group_id, :p_menu_item_url, :p_parent_id, :p_menu_item_icon, :p_order_sequence, :p_last_log_by)');
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
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertMenuItem($p_menu_item_name, $p_menu_group_id, $p_menu_item_url, $p_parent_id, $p_menu_item_icon, $p_order_sequence, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertMenuItem(:p_menu_item_name, :p_menu_group_id, :p_menu_item_url, :p_parent_id, :p_menu_item_icon, :p_order_sequence, :p_last_log_by, @p_menu_item_id)');
        $stmt->bindParam(':p_menu_item_name', $p_menu_item_name);
        $stmt->bindParam(':p_menu_group_id', $p_menu_group_id);
        $stmt->bindParam(':p_menu_item_url', $p_menu_item_url);
        $stmt->bindParam(':p_parent_id', $p_parent_id);
        $stmt->bindParam(':p_menu_item_icon', $p_menu_item_icon);
        $stmt->bindParam(':p_order_sequence', $p_order_sequence);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_menu_item_id AS menu_item_id");
        $menuItemID = $result->fetch(PDO::FETCH_ASSOC)['menu_item_id'];

        return $menuItemID;
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
        $stmt = $this->db->getConnection()->prepare('CALL checkMenuItemExist(:p_menu_item_id)');
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
        $stmt = $this->db->getConnection()->prepare('CALL deleteMenuItem(:p_menu_item_id)');
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
        $stmt = $this->db->getConnection()->prepare('CALL getMenuItem(:p_menu_item_id)');
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateMenuItem
    # Description: Duplicates the menu item.
    #
    # Parameters:
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateMenuItem($p_menu_item_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateMenuItem(:p_menu_item_id, :p_last_log_by, @p_new_menu_item_id)');
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_menu_item_id AS menu_item_id");
        $menuItemID = $result->fetch(PDO::FETCH_ASSOC)['menu_item_id'];

        return $menuItemID;
    }
    # -------------------------------------------------------------
}
?>