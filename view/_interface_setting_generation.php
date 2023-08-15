<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/interface-setting-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$interfaceSettingModel = new InterfaceSettingModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: interface setting table
        # Description:
        # Generates the interface setting table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'interface setting table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateInterfaceSettingTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $interfaceSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 13, 'delete');

            foreach ($options as $row) {
                $interfaceSettingID = $row['interface_setting_id'];
                $interfaceSettingName = $row['interface_setting_name'];
                $interfaceSettingDescription = $row['interface_setting_description'];

                $interfaceSettingIDEncrypted = $securityModel->encryptData($interfaceSettingID);

                $delete = '';
                if($interfaceSettingDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-interface-setting" data-interface-setting-id="'. $interfaceSettingID .'" title="Delete Interface Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $interfaceSettingID .'">',
                    'INTERFACE_SETTING_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $interfaceSettingName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $interfaceSettingDescription .'</p>
                                        </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="interface-setting.php?id='. $interfaceSettingIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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