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
        # Type: role configuration table
        # Description:
        # Generates the role table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
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

                $assignableBadge = $assignable ? '<span class="badge bg-light-success">Yes</span>' : '<span class="badge bg-light-danger">No</span>';

                $roleIDEncrypted = $securityModel->encryptData($roleID);

                $delete = '';
                if($roleDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-role" data-role-id="'. $roleID .'" title="Delete Role">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $roleID .'">',
                    'ROLE_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $roleName .'</h6>
                                        <p class="f-12 mb-0">'. $roleDescription .'</p>
                                    </div>',
                    'ASSIGNABLE' => $assignableBadge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="role-configuration.php?id='. $roleIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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

                $sql = $databaseModel->getConnection()->prepare('CALL generateMenuItemRoleAccessTable(:menuItemID)');
                $sql->bindValue(':menuItemID', $menuItemID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 2);
                $updateMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 1);
                $disabled = ($updateMenuItemRoleAccess['total'] == 0) ? 'disabled' : null;

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
                        'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="read" '. $readChecked .' '. $disabled .'></div>',
                        'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="write" '. $writeChecked .' '. $disabled .'></div>',
                        'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="create" '. $createChecked .' '. $disabled .'></div>',
                        'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="delete" '. $deleteChecked .' '. $disabled .'></div>',
                        'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="duplicate" '. $duplicateChecked .' '. $disabled .'></div>',
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
        # Type: update role menu item access table 
        # Description:
        # Generates the role menu item access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update role menu item access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateRoleMenuItemAccessTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 2);
                $updateMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 1);
                $disabled = ($updateMenuItemRoleAccess['total'] == 0) ? 'disabled' : null;

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
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item-role-access" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" title="Delete Role Access">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'MENU_ITEM_NAME' => $menuItemName,
                        'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="read" '. $readChecked .' '. $disabled .'></div>',
                        'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="write" '. $writeChecked .' '. $disabled .'></div>',
                        'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="create" '. $createChecked .' '. $disabled .'></div>',
                        'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="delete" '. $deleteChecked .' '. $disabled .'></div>',
                        'DUPLICATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-menu-item-role-access" type="checkbox" data-menu-item-id="'. $menuItemID .'" data-role-id="'. $roleID .'" data-access-type="duplicate" '. $duplicateChecked .' '. $disabled .'></div>',
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

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddMenuItemRoleAccessTable(:menuItemID)');
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
        # Type: add role menu item access table
        # Description:
        # Generates the role not in menu item role access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add role menu item access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddRoleMenuItemAccessTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $menuItemID = $row['menu_item_id'];
                    $menuItemName = $row['menu_item_name'];
    
                    $response[] = [
                        'MENU_ITEM_NAME' => $menuItemName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input menu-item-role-access" type="checkbox" value="'. $menuItemID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: update system action role access table
        # Description:
        # Generates the system action role access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update system action role access table':
            if(isset($_POST['system_action_id']) && !empty($_POST['system_action_id'])){
                $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSystemActionRoleAccessTable(:systemActionID)');
                $sql->bindValue(':systemActionID', $systemActionID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 4);
                $updateSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 3);
                $disabled = ($updateSystemActionRoleAccess['total'] == 0) ? 'disabled' : null;

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $roleSystemActionAccessDetails = $roleModel->getRoleSystemActionAccess($systemActionID, $roleID);

                    $roleAccess = $roleSystemActionAccessDetails['role_access'] ?? 0;
                
                    $roleChecked = $roleAccess ? 'checked' : '';

                    $delete = '';
                    if($deleteSystemActionRoleAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-system-action-role-access" data-system-action-id="'. $systemActionID .'" data-role-id="'. $roleID .'" title="Delete Role Access">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ROLE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-system-action-role-access" type="checkbox" data-system-action-id="'. $systemActionID .'" data-role-id="'. $roleID .'" '. $roleChecked .' '. $disabled .'></div>',
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
        # Type: update role system action access table
        # Description:
        # Generates the role system action access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update role system action access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateRoleSystemActionAccessTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 4);
                $updateSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 3);
                $disabled = ($updateSystemActionRoleAccess['total'] == 0) ? 'disabled' : null;

                foreach ($options as $row) {
                    $systemActionID = $row['system_action_id'];
                    $systemActionName = $row['system_action_name'];
    
                    $roleSystemActionAccessDetails = $roleModel->getRoleSystemActionAccess($systemActionID, $roleID);

                    $roleAccess = $roleSystemActionAccessDetails['role_access'] ?? 0;
                
                    $roleChecked = $roleAccess ? 'checked' : '';

                    $delete = '';
                    if($deleteSystemActionRoleAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-system-action-role-access" data-system-action-id="'. $systemActionID .'" data-role-id="'. $roleID .'" title="Delete Role Access">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'SYSTEM_ACTION_NAME' => $systemActionName,
                        'ROLE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-system-action-role-access" type="checkbox" data-system-action-id="'. $systemActionID .'" data-role-id="'. $roleID .'" '. $roleChecked .' '. $disabled .'></div>',
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
        # Type: add system action role access table
        # Description:
        # Generates the role table not in system action access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add system action role access table':
            if(isset($_POST['system_action_id']) && !empty($_POST['system_action_id'])){
                $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddSystemActionRoleAccessTable(:systemActionID)');
                $sql->bindValue(':systemActionID', $systemActionID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input system-action-role-access" type="checkbox" value="'. $roleID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: add system action role access table
        # Description:
        # Generates the role table not in system action access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add role system action access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddRoleSystemActionAccessTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $systemActionID = $row['system_action_id'];
                    $systemActionName = $row['system_action_name'];
    
                    $response[] = [
                        'SYSTEM_ACTION_NAME' => $systemActionName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input system-action-role-access" type="checkbox" value="'. $systemActionID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>