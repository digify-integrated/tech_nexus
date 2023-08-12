<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/file-type-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$fileTypeModel = new FileTypeModel($databaseModel);
$fileExtensionModel = new FileExtensionModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: file type file extension table
        # Description:
        # Generates the file extension table on file type page.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'file type file extension table':
            if(isset($_POST['file_type_id']) && !empty($_POST['file_type_id'])){
                $fileTypeID = htmlspecialchars($_POST['file_type_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateFileTypeFileExensionTable(:fileTypeID)');
                $sql->bindValue(':fileTypeID', $fileTypeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $fileTypeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 10, 'write');
                $fileExtensionWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 11, 'write');
                $fileExtensionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 11, 'delete');      

                foreach ($options as $row) {
                    $fileExtensionID = $row['file_extension_id'];
                    $fileExtensionName = $row['file_extension_name'];
    
                    $fileExtensionIDEncrypted = $securityModel->encryptData($fileExtensionID);
                        
                    $update = '';
                    if($fileExtensionWriteAccess['total'] > 0 && $fileTypeWriteAccess['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-file-extension form-edit" data-file-extension-id="'. $fileExtensionID .'" title="Edit File Extension">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($fileExtensionDeleteAccess['total'] > 0 && $fileTypeWriteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-file-extension form-edit" data-file-extension-id="'. $fileExtensionID .'" title="Delete File Extension">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'FILE_EXTENSION_NAME' => '<a href="file-extension.php?id='. $fileExtensionIDEncrypted .'">'. $fileExtensionName . '</a>',
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $update .'
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
        # Type: file extension table
        # Description:
        # Generates the file extension table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'file extension table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateFileExtensionTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $fileExtensionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 11, 'delete');

            foreach ($options as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileTypeID = $row['file_type_id'];
                $fileExtensionName = $row['file_extension_name'];

                $fileExtensionIDEncrypted = $securityModel->encryptData($fileExtensionID);

                $fileTypeDetails = $fileTypeModel->getFileType($fileTypeID);
                $fileTypeName = $fileTypeDetails['file_type_name'] ?? null;

                $delete = '';
                if($fileExtensionDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-file-extension" data-file-extension-id="'. $fileExtensionID .'" title="Delete File Extension">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $fileExtensionID .'">',
                    'FILE_EXTENSION_NAME' => $fileExtensionName,
                    'FILE_TYPE_NAME' => $fileTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="file-extension.php?id='. $fileExtensionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: assign upload setting file extension table
        # Description:
        # Generates the file extension not in upload setting file extension table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'assign upload setting file extension table':
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