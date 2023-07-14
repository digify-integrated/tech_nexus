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
    $response = array();
    
    switch ($type) {
        # Update access table
        case 'update role access table':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateMenuItemRoleTable(:menuItemID)');
                $sql->bindParam(':menuItemID', $menuItemID);
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
                    $duplicateAccess = $roleMenuAccessDetails['delete_access'] ?? 0;

                    if($readAccess){
                        $readChecked = 'checked';
                    }
                    else{
                        $readChecked = null;
                    }

                    if($writeAccess){
                        $writeChecked = 'checked';
                    }
                    else{
                        $writeChecked = null;
                    }

                    if($createAccess){
                        $createChecked = 'checked';
                    }
                    else{
                        $createChecked = null;
                    }

                    if($deleteAccess){
                        $deleteChecked = 'checked';
                    }
                    else{
                        $deleteChecked = null;
                    }

                    if($duplicateAccess){
                        $duplicateChecked = 'checked';
                    }
                    else{
                        $duplicateChecked = null;
                    }
    
                    $response[] = array(
                        'ROLE_NAME' => $roleName,
                        'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-access" type="checkbox" value="'. $roleID .'-read" '. $readChecked .' disabled></div>',
                        'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-write" '. $writeChecked .' disabled></div>',
                        'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-create" '. $createChecked .' disabled></div>',
                        'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-delete" '. $deleteChecked .' disabled></div>',
                        'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'-duplicate" '. $duplicateChecked .' disabled></div>'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # Add role access table
        case 'add role access table':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddMenuItemRoleTable(:menuItemID)');
                $sql->bindParam(':menuItemID', $menuItemID);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $response[] = array(
                        'ROLE_NAME' => $roleName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID.'"></div>',
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # Menu item table
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

                if($menuItemDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item" data-menu-item-id="'. $menuItemID .'" title="Delete Menu Item">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }
                else{
                    $delete = null;
                }

                $response[] = array(
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
                );
            }

            echo json_encode($response);
        break;
        # Sub menu item table
        case 'sub menu item table':
            $menuItemID = $_POST['menu_item_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateSubMenuItemTable(:menuItemID)');
            $sql->bindParam(':menuItemID', $menuItemID);
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

                $response[] = array(
                    'MENU_ITEM_NAME' => '<a href="menu-item.php?id='. $menuItemIDEncrypted .'">'. $menuItemName . '</a>',
                    'MENU_GROUP_ID' => '<a href="menu-group.php?id='. $menuGroupIDEncrypted .'">'. $menuGroupName . '</a>',
                    'ORDER_SEQUENCE' => $orderSequence
                );
            }

            echo json_encode($response);
        break;
    }
}

?>