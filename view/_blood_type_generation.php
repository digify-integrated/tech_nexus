<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/blood-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$bloodTypeModel = new BloodTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: blood type table
        # Description:
        # Generates the blood type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'blood type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBloodTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $bloodTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 37, 'delete');

            foreach ($options as $row) {
                $bloodTypeID = $row['blood_type_id'];
                $bloodTypeName = $row['blood_type_name'];

                $bloodTypeIDEncrypted = $securityModel->encryptData($bloodTypeID);

                $delete = '';
                if($bloodTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-blood-type" data-blood-type-id="'. $bloodTypeID .'" title="Delete Blood Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $bloodTypeID .'">',
                    'BLOOD_TYPE_NAME' => $bloodTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="blood-type.php?id='. $bloodTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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