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
        # Type: system action table
        # Description:
        # Generates the system action table.
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

            $systemActionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 10, 'delete');

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
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $systemActionID .'">',
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