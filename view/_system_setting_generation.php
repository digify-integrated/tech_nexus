<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: system setting table
        # Description:
        # Generates the system setting table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'system setting table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSystemSettingTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $systemSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 17, 'delete');

            foreach ($options as $row) {
                $systemSettingID = $row['system_setting_id'];
                $systemSettingName = $row['system_setting_name'];
                $systemSettingDescription = $row['system_setting_description'];
                $value = $row['value'];

                $systemSettingIDEncrypted = $securityModel->encryptData($systemSettingID);

                $delete = '';
                if($systemSettingDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-system-setting" data-system-setting-id="'. $systemSettingID .'" title="Delete System Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $systemSettingID .'">',
                    'SYSTEM_SETTING_NAME' => '<div class="col">
                                                <h6 class="mb-0">'. $systemSettingName .'</h6>
                                                <p class="text-muted f-12 mb-0">'. $systemSettingDescription .'</p>
                                            </div>',
                    'VALUE' => $value,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="system-setting.php?id='. $systemSettingIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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