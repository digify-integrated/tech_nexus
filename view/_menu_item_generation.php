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
        # Type: update menu group role access table
        # Description:
        # Generates the menu item role access table on menu group page.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update menu group role access table':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateShortcutMenuItemRoleTable()');
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $roleMenuAccessDetails = $roleModel->getRoleMenuAccess($menuItemID, $roleID);

                    $readAccess = $roleMenuAccessDetails['read_access'] ?? 0;
                    $writeAccess = $roleMenuAccessDetails['write_access'] ?? 0;
                    $createAccess = $roleMenuAccessDetails['create_access'] ?? 0;
                    $deleteAccess = $roleMenuAccessDetails['delete_access'] ?? 0;
                    $duplicateAccess = $roleMenuAccessDetails['duplicate_access'] ?? 0;
                
                    $readChecked = $readAccess ? 'checked' : '';
                    $writeChecked = $writeAccess ? 'checked' : '';
                    $createChecked = $createAccess ? 'checked' : '';
                    $deleteChecked = $deleteAccess ? 'checked' : '';
                    $duplicateChecked = $duplicateAccess ? 'checked' : '';
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-read" '. $readChecked .'></div>',
                        'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-write" '. $writeChecked .'></div>',
                        'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-create" '. $createChecked .'></div>',
                        'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-delete" '. $deleteChecked .'></div>',
                        'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-duplicate" '. $duplicateChecked .'></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: update menu item role access table
        # Description:
        # Generates the menu item role access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update menu item role access table':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateMenuItemRoleTable(:menuItemID)');
                $sql->bindValue(':menuItemID', $menuItemID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 2);

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $roleMenuAccessDetails = $roleModel->getRoleMenuAccess($menuItemID, $roleID);

                    $readAccess = $roleMenuAccessDetails['read_access'] ?? 0;
                    $writeAccess = $roleMenuAccessDetails['write_access'] ?? 0;
                    $createAccess = $roleMenuAccessDetails['create_access'] ?? 0;
                    $deleteAccess = $roleMenuAccessDetails['delete_access'] ?? 0;
                    $duplicateAccess = $roleMenuAccessDetails['duplicate_access'] ?? 0;
                
                    $readChecked = $readAccess ? 'checked' : '';
                    $writeChecked = $writeAccess ? 'checked' : '';
                    $createChecked = $createAccess ? 'checked' : '';
                    $deleteChecked = $deleteAccess ? 'checked' : '';
                    $duplicateChecked = $duplicateAccess ? 'checked' : '';

                    $delete = '';
                    if($deleteMenuItemRoleAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item-role-access" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" title="Delete Role Access">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $roleID .'-read" '. $readChecked .' disabled></div>',
                        'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $roleID .'-write" '. $writeChecked .' disabled></div>',
                        'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $roleID .'-create" '. $createChecked .' disabled></div>',
                        'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $roleID .'-delete" '. $deleteChecked .' disabled></div>',
                        'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $roleID .'-duplicate" '. $duplicateChecked .' disabled></div>',
                        'ACTION' => '<div class="d-flex gap-2">
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
        # Type: add menu item role access table
        # Description:
        # Generates the role not in menu item role access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add menu item role access table':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddMenuItemRoleTable(:menuItemID)');
                $sql->bindValue(':menuItemID', $menuItemID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-role-access" type="checkbox" value="'. $roleID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: update menu item role access table
        # Description:
        # Generates the menu item table in menu item access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        /*case 'update menu item role access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateRoleMenuItemAccessTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 2);

                foreach ($options as $row) {
                    $menuItemID = $row['menu_item_id'];
                    $menuItemName = $row['menu_item_name'];
    
                    $roleMenuAccessDetails = $roleModel->getRoleMenuAccess($menuItemID, $roleID);

                    $readAccess = $roleMenuAccessDetails['read_access'] ?? 0;
                    $writeAccess = $roleMenuAccessDetails['write_access'] ?? 0;
                    $createAccess = $roleMenuAccessDetails['create_access'] ?? 0;
                    $deleteAccess = $roleMenuAccessDetails['delete_access'] ?? 0;
                    $duplicateAccess = $roleMenuAccessDetails['duplicate_access'] ?? 0;
                
                    $readChecked = $readAccess ? 'checked' : '';
                    $writeChecked = $writeAccess ? 'checked' : '';
                    $createChecked = $createAccess ? 'checked' : '';
                    $deleteChecked = $deleteAccess ? 'checked' : '';
                    $duplicateChecked = $duplicateAccess ? 'checked' : '';

                    $delete = '';
                    if($deleteMenuItemRoleAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item-role-access" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" title="Delete Menu Item Access">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'MENU_ITEM_NAME' => $menuItemName,
                        'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $menuItemID .'-read" '. $readChecked .' disabled></div>',
                        'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $menuItemID .'-write" '. $writeChecked .' disabled></div>',
                        'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $menuItemID .'-create" '. $createChecked .' disabled></div>',
                        'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $menuItemID .'-delete" '. $deleteChecked .' disabled></div>',
                        'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" value="'. $menuItemID .'-duplicate" '. $duplicateChecked .' disabled></div>',
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $delete .'
                                </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;*/
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: add menu item access table
        # Description:
        # Generates the menu item table not in menu item access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        /*case 'add menu item access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddRoleMenuItemTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $menuItemID = $row['menu_item_id'];
                    $menuItemName = $row['menu_item_name'];
    
                    $response[] = [
                        'MENU_ITEM_NAME' => $menuItemName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-access" type="checkbox" value="'. $menuItemID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;*/
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

            $menuItemDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 3, 'delete');

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
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $menuItemID .'">',
                    'MENU_ITEM_ID' => $menuItemID,
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
                $orderSequence = $row['order_sequence'];

                $menuItemIDEncrypted = $securityModel->encryptData($menuItemID);
                $menuGroupIDEncrypted = $securityModel->encryptData($menuGroupID);

                $menuGroupDetails = $menuGroupModel->getMenuGroup($menuGroupID);
                $menuGroupName = $menuGroupDetails['menu_group_name'] ?? null;

                $response[] = [
                    'MENU_ITEM_NAME' => '<a href="menu-item.php?id='. $menuItemIDEncrypted .'">'. $menuItemName . '</a>',
                    'MENU_GROUP_ID' => '<a href="menu-group.php?id='. $menuGroupIDEncrypted .'">'. $menuGroupName . '</a>',
                    'ORDER_SEQUENCE' => $orderSequence
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>