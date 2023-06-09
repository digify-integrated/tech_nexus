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
    $response = array();
    
    switch ($type) {
        # Menu group system action table
        case 'assign system action role access table':
            if(isset($_POST['system_action_id']) && !empty($_POST['system_action_id'])){
                $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSystemActionRoleTable()');
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $role_name = $row['role_name'];

                    $checkSystemActionRoleExist = $roleModel->checkSystemActionRoleExist($systemActionID, $roleID);

                    if($checkSystemActionRoleExist['total'] > 0){
                        $roleChecked = 'checked';
                    }
                    else{
                        $roleChecked = null;
                    }
    
                    $response[] = array(
                        'ROLE_ID' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $roleID .'" '. $roleChecked .'></div>',
                        'ROLE_NAME' => $role_name
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # System action table
        case 'system action table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSystemActionTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $systemActionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 5, 'delete');

            foreach ($options as $row) {
                $systemActionID = $row['system_action_id'];
                $systemActionName = $row['system_action_name'];

                $systemActionIDEncrypted = $securityModel->encryptData($systemActionID);

                if($systemActionDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-system-action" data-system-action-id="'. $systemActionID .'" title="Delete System Action">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }
                else{
                    $delete = null;
                }

                $response[] = array(
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $systemActionID .'">',
                    'SYSTEM_ACTION_ID' => $systemActionID,
                    'SYSTEM_ACTION_NAME' => $systemActionName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="system-action.php?id='. $systemActionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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