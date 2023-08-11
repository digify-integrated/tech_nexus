<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$uploadSettingModel = new UploadSettingModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {

        # -------------------------------------------------------------
        #
        # Type: upload setting table
        # Description:
        # Generates the upload setting table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'upload setting table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateUploadSettingTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $uploadSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 10, 'delete');

            foreach ($options as $row) {
                $uploadSettingID = $row['upload_setting_id'];
                $uploadSettingName = $row['upload_setting_name'];
                $uploadSettingDescription = $row['upload_setting_description'];
                $maxFileSize = $row['max_file_size'];

                $uploadSettingIDEncrypted = $securityModel->encryptData($uploadSettingID);

                $delete = '';
                if($uploadSettingDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-upload-setting" data-upload-setting-id="'. $uploadSettingID .'" title="Delete Upload Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $uploadSettingID .'">',
                    'UPLOAD_SETTING_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $uploadSettingName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $uploadSettingDescription .'</p>
                                        </div>',
                    'MAX_FILE_SIZE' => $maxFileSize,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="upload-setting.php?id='. $uploadSettingIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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