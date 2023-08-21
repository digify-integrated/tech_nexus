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
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $uploadSettingID .'">',
                    'UPLOAD_SETTING_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $uploadSettingName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $uploadSettingDescription .'</p>
                                        </div>',
                    'MAX_FILE_SIZE' => $maxFileSize . ' Mb',
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

        # -------------------------------------------------------------
        #
        # Type: upload setting file extension table
        # Description:
        # Generates the file extension table on upload setting page.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'upload setting file extension table':
            if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
                $uploadSettingID = htmlspecialchars($_POST['upload_setting_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateUploadSettingFileExensionTable(:uploadSettingID)');
                $sql->bindValue(':uploadSettingID', $uploadSettingID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deleteFileExtensionToUploadSetting = $userModel->checkSystemActionAccessRights($user_id, 16);

                foreach ($options as $row) {
                    $fileExtensionID = $row['file_extension_id'];
                    $fileExtensionName = $row['file_extension_name'];
    
                    $fileExtensionIDEncrypted = $securityModel->encryptData($fileExtensionID);

                    $delete = '';
                    if($deleteFileExtensionToUploadSetting['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-file-extension" data-upload-setting-id="'. $uploadSettingID .'" data-file-extension-id="'. $fileExtensionID .'" title="Delete File Extension">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'FILE_EXTENSION_NAME' => '<a href="file-extension.php?id='. $fileExtensionIDEncrypted .'">'. $fileExtensionName . '</a>',
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
        # Type: add upload setting file extension table
        # Description:
        # Generates the file extension not in upload setting file extension table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add upload setting file extension table':
            if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
                $uploadSettingID = htmlspecialchars($_POST['upload_setting_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddUploadSettingFileExensionTable(:uploadSettingID)');
                $sql->bindValue(':uploadSettingID', $uploadSettingID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $fileExtensionID = $row['file_extension_id'];
                    $fileExtensionName = $row['file_extension_name'];
    
                    $response[] = [
                        'FILE_EXTENSION_NAME' => $fileExtensionName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input upload-setting-file-extension" type="checkbox" value="'. $fileExtensionID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>