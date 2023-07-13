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
        # Assign menu item table
        case 'assign menu item access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateMenuItemAccessTable()');
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $menuItemID = $row['menu_item_id'];
                    $menuItemName = $row['menu_item_name'];
    
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
                        'MENU_ITEM_NAME' => $menuItemName,
                        'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-access" type="checkbox" value="'. $menuItemID .'-read" '. $readChecked .'></div>',
                        'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-access" type="checkbox" value="'. $menuItemID .'-write" '. $writeChecked .'></div>',
                        'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-access" type="checkbox" value="'. $menuItemID .'-create" '. $createChecked .'></div>',
                        'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-access" type="checkbox" value="'. $menuItemID .'-delete" '. $deleteChecked .'></div>',
                        'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-access" type="checkbox" value="'. $menuItemID .'-duplicate" '. $duplicateChecked .'></div>'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # Role configuration table
        case 'role configuration table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateRoleConfigurationTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $roleDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 6, 'delete');

            foreach ($options as $row) {
                $roleID = $row['role_id'];
                $roleName = $row['role_name'];
                $roleDescription = $row['role_description'];
                $assignable = $row['assignable'];

                if($assignable){
                    $assignable = '<span class="badge bg-success">Yes</span>';
                }
                else{
                    $assignable = '<span class="badge bg-danger">No</span>';
                }

                $roleIDEncrypted = $securityModel->encryptData($roleID);

                if($roleDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-role" data-role-id="'. $roleID .'" title="Delete Role">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }
                else{
                    $delete = null;
                }

                $response[] = array(
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $roleID .'">',
                    'ROLE_ID' => $roleID,
                    'ROLE_NAME' => $roleName . '<p class="text-muted mb-0>'. $roleDescription .'</p>',
                    'ASSIGNABLE' => $assignable,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="role-configuration.php?id='. $roleIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                );
            }

            echo json_encode($response);
        break;
    }
}

?>