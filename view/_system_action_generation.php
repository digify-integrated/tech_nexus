<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/system-action-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemActionModel = new SystemActionModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: update role access table
        # Description:
        # Generates the system action role access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update role access table':
            if(isset($_POST['system_action_id']) && !empty($_POST['system_action_id'])){
                $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSystemActionRoleTable(:systemActionID)');
                $sql->bindValue(':systemActionID', $systemActionID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 4);

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $roleSystemActionAccessDetails = $roleModel->getRoleSystemActionAccess($systemActionID, $roleID);

                    $roleAccess = $roleSystemActionAccessDetails['role_access'] ?? 0;
                
                    $roleChecked = $roleAccess ? 'checked' : '';

                    $delete = '';
                    if($deleteSystemActionRoleAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-role-access" data-system-action-id="'. $systemActionID .'" data-role-id="'. $roleID .'" title="Delete Role Access">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ROLE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $roleID .'" '. $roleChecked .' disabled></div>',
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
        # Type: add role access table
        # Description:
        # Generates the role table not in system action access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add role access table':
            if(isset($_POST['system_action_id']) && !empty($_POST['system_action_id'])){
                $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddSystemActionRoleTable(:systemActionID)');
                $sql->bindValue(':systemActionID', $systemActionID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: update system action access table
        # Description:
        # Generates the system action access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update system action access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateRoleSystemActionTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 4);

                foreach ($options as $row) {
                    $systemActionID = $row['system_action_id'];
                    $systemActionName = $row['system_action_name'];
    
                    $roleSystemActionAccessDetails = $roleModel->getRoleSystemActionAccess($systemActionID, $roleID);

                    $roleAccess = $roleSystemActionAccessDetails['role_access'] ?? 0;
                
                    $roleChecked = $roleAccess ? 'checked' : '';

                    $delete = '';
                    if($deleteSystemActionRoleAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-system-action-access" data-system-action-id="'. $systemActionID .'" data-role-id="'. $roleID .'" title="Delete System Action Access">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'SYSTEM_ACTION_NAME' => $systemActionName,
                        'ROLE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-role-access" type="checkbox" value="'. $systemActionID .'" '. $roleChecked .' disabled></div>',
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
        # Type: add system action access table
        # Description:
        # Generates the role table not in system action access table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add system action access table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddRoleSystemActionTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: system action table
        # Description:
        # Generates the role table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'system action table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSystemActionTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $systemActionDeleteAccess = $userModel->checkSystemActionAccessRights($user_id, 5, 'delete');

            foreach ($options as $row) {
                $systemActionID = $row['system_action_id'];
                $systemActionName = $row['system_action_name'];

                $systemActionIDEncrypted = $securityModel->encryptData($systemActionID);

                $delete = '';
                if($systemActionDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-system-action" data-system-action-id="'. $systemActionID .'" title="Delete System Action">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $systemActionID .'">',
                    'SYSTEM_ACTION_ID' => $systemActionID,
                    'SYSTEM_ACTION_NAME' => $systemActionName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="system-action.php?id='. $systemActionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>