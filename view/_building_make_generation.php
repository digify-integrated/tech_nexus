<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/building-make-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$buildingMakeModel = new BuildingMakeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: building make table
        # Description:
        # Generates the building make table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'building make table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBuildingMakeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $buildingMakeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 43, 'delete');

            foreach ($options as $row) {
                $buildingMakeID = $row['building_make_id'];
                $buildingMakeName = $row['building_make_name'];

                $buildingMakeIDEncrypted = $securityModel->encryptData($buildingMakeID);

                $delete = '';
                if($buildingMakeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-building-make" data-building-make-id="'. $buildingMakeID .'" title="Delete Building Make">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $buildingMakeID .'">',
                    'BUILDING_MAKE_NAME' => $buildingMakeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="building-make.php?id='. $buildingMakeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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