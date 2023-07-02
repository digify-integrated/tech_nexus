<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/menu-item-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$menuItemModel = new MenuItemModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = array();
    
    switch ($type) {
        # Menu group menu item table
        case 'assign menu item role access table':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('SELECT role_id, role_name FROM role ORDER BY role_name');
    
                if($sql->execute()){
                    $menuGroupDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'delete');
                    $menuGroupWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'write');
                    $menuItemWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 3, 'write');
                    $menuItemDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 3, 'delete');
                    $assignMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 1);                

                    while($row = $sql->fetch()){
                        $roleID = $row['role_id'];
                        $role_name = $row['role_name'];
    
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
                            'ROLE_NAME' => $role_name,
                            'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID .'-read" '. $readChecked .'></div>',
                            'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID .'-write" '. $writeChecked .'></div>',
                            'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID .'-create" '. $createChecked .'></div>',
                            'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID .'-delete" '. $deleteChecked .'></div>',
                            'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID .'-duplicate" '. $duplicateChecked .'></div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        break;
    }
}

?>