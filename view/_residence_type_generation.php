<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/residence-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$residenceTypeModel = new ResidenceTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: residence type table
        # Description:
        # Generates the residence type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'residence type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateResidenceTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $residenceTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 152, 'delete');

            foreach ($options as $row) {
                $residenceTypeID = $row['residence_type_id'];
                $residenceTypeName = $row['residence_type_name'];

                $residenceTypeIDEncrypted = $securityModel->encryptData($residenceTypeID);

                $delete = '';
                if($residenceTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-residence-type" data-residence-type-id="'. $residenceTypeID .'" title="Delete Residence Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $residenceTypeID .'">',
                    'RESIDENCE_TYPE_NAME' => $residenceTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="residence-type.php?id='. $residenceTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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