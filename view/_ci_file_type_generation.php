<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/ci-file-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$ciFileTypeModel = new CIFileTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: ci file type table
        # Description:
        # Generates the ci file type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'ci file type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateCIFileTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $ciFileTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 162, 'delete');

            foreach ($options as $row) {
                $ciFileTypeID = $row['ci_file_type_id'];
                $ciFileTypeName = $row['ci_file_type_name'];

                $ciFileTypeIDEncrypted = $securityModel->encryptData($ciFileTypeID);

                $delete = '';
                if($ciFileTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-file-type" data-ci-file-type-id="'. $ciFileTypeID .'" title="Delete CI File Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $ciFileTypeID .'">',
                    'CI_FILE_TYPE_NAME' => $ciFileTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="ci-file-type.php?id='. $ciFileTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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