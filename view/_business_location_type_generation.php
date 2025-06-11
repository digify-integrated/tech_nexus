<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/business-location-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$businessLocationTypeModel = new BusinessLocationTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: business location type table
        # Description:
        # Generates the business location type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'business location type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBusinessLocationTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $businessLocationTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 158, 'delete');

            foreach ($options as $row) {
                $businessLocationTypeID = $row['business_location_type_id'];
                $businessLocationTypeName = $row['business_location_type_name'];

                $businessLocationTypeIDEncrypted = $securityModel->encryptData($businessLocationTypeID);

                $delete = '';
                if($businessLocationTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-business-location-type" data-business-location-type-id="'. $businessLocationTypeID .'" title="Delete Business Location Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $businessLocationTypeID .'">',
                    'BUSINESS_LOCATION_TYPE_NAME' => $businessLocationTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="business-location-type.php?id='. $businessLocationTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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