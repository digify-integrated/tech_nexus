<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/file-type-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$fileTypeModel = new FileTypeModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {

        # -------------------------------------------------------------
        #
        # Type: file type table
        # Description:
        # Generates the file type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'file type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateFileTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $fileTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 10, 'delete');

            foreach ($options as $row) {
                $fileTypeID = $row['file_type_id'];
                $fileTypeName = $row['file_type_name'];

                $fileTypeIDEncrypted = $securityModel->encryptData($fileTypeID);

                $delete = '';
                if($fileTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-file-type" data-file-type-id="'. $fileTypeID .'" title="Delete File Type">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $fileTypeID .'">',
                    'FILE_TYPE_NAME' => $fileTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="file-type.php?id='. $fileTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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