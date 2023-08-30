<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/menu-group-model.php';
require_once '../model/menu-item-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$menuGroupModel = new MenuGroupModel($databaseModel);
$menuItemModel = new MenuItemModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: menu group menu item table
        # Description:
        # Generates the menu item table on menu group page.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'menu group menu item table':
            if(isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])){
                $menuGroupID = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateMenuGroupMenuItemTable(:menuGroupID)');
                $sql->bindValue(':menuGroupID', $menuGroupID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $menuGroupDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'delete');
                $menuGroupWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'write');
                $menuItemWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 9, 'write');
                $menuItemDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 9, 'delete');
                $updateMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 1);                

                foreach ($options as $row) {
                    $menuItemID = $row['menu_item_id'];
                    $menuItemName = $row['menu_item_name'];
                    $parentID = $row['parent_id'];
                    $orderSequence = $row['order_sequence'];
    
                    $menuItemIDEncrypted = $securityModel->encryptData($menuItemID);
                    $parentIDEncrypted = $securityModel->encryptData($parentID);
                    $menuItemDetails = $menuItemModel->getMenuItem($parentID);
                    $parentMenuItemName = $menuItemDetails['menu_item_name'] ?? null;
                        
                    $update = '';
                    if($menuItemWriteAccess['total'] > 0 && $menuGroupWriteAccess['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-menu-item" data-menu-item-id="'. $menuItemID .'" title="Edit Menu Item">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($menuItemDeleteAccess['total'] > 0 && $menuGroupWriteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item" data-menu-item-id="'. $menuItemID .'" title="Delete Menu Item">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'MENU_ITEM_NAME' => '<a href="menu-item.php?id='. $menuItemIDEncrypted .'">'. $menuItemName . '</a>',
                        'PARENT_ID' => '<a href="menu-item.php?id='. $parentIDEncrypted .'">'. $parentMenuItemName . '</a>',
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $update .'
                                        '. $delete .'
                                    </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
        
        # -------------------------------------------------------------
        #
        # Type: menu item table
        # Description:
        # Generates the menu item table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'menu item table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateMenuItemTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $menuItemDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 9, 'delete');

            foreach ($options as $row) {
                $menuItemID = $row['menu_item_id'];
                $menuGroupID = $row['menu_group_id'];
                $menuItemName = $row['menu_item_name'];
                $parentID = $row['parent_id'];
                $orderSequence = $row['order_sequence'];

                $menuItemIDEncrypted = $securityModel->encryptData($menuItemID);

                $menuGroupDetails = $menuGroupModel->getMenuGroup($menuGroupID);
                $menuGroupName = $menuGroupDetails['menu_group_name'] ?? null;

                $menuItemDetails = $menuItemModel->getMenuItem($parentID);
                $parentMenuItemName = $menuItemDetails['menu_item_name'] ?? '';

                $delete = '';
                if($menuItemDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item" data-menu-item-id="'. $menuItemID .'" title="Delete Menu Item">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $menuItemID .'">',
                    'MENU_ITEM_NAME' => $menuItemName,
                    'MENU_GROUP_ID' => $menuGroupName,
                    'PARENT_ID' => $parentMenuItemName,
                    'ORDER_SEQUENCE' => $orderSequence,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="menu-item.php?id='. $menuItemIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sub menu item table
        # Description:
        # Generates the sub menu on each menu item table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sub menu item table':
            $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

            $sql = $databaseModel->getConnection()->prepare('CALL generateSubMenuItemTable(:menuItemID)');
            $sql->bindValue(':menuItemID', $menuItemID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $menuGroupID = $row['menu_group_id'];
                $menuItemName = $row['menu_item_name'];

                $menuItemIDEncrypted = $securityModel->encryptData($menuItemID);
                $menuGroupIDEncrypted = $securityModel->encryptData($menuGroupID);

                $menuGroupDetails = $menuGroupModel->getMenuGroup($menuGroupID);
                $menuGroupName = $menuGroupDetails['menu_group_name'] ?? null;

                $response[] = [
                    'MENU_ITEM_NAME' => '<a href="menu-item.php?id='. $menuItemIDEncrypted .'">'. $menuItemName . '</a>',
                    'MENU_GROUP_ID' => '<a href="menu-group.php?id='. $menuGroupIDEncrypted .'">'. $menuGroupName . '</a>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>