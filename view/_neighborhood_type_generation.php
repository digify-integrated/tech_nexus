<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/neighborhood-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$neighborhoodTypeModel = new NeighborhoodTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: neighborhood type table
        # Description:
        # Generates the neighborhood type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'neighborhood type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateNeighborhoodTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $neighborhoodTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 156, 'delete');

            foreach ($options as $row) {
                $neighborhoodTypeID = $row['neighborhood_type_id'];
                $neighborhoodTypeName = $row['neighborhood_type_name'];

                $neighborhoodTypeIDEncrypted = $securityModel->encryptData($neighborhoodTypeID);

                $delete = '';
                if($neighborhoodTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-neighborhood-type" data-neighborhood-type-id="'. $neighborhoodTypeID .'" title="Delete Neighborhood Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $neighborhoodTypeID .'">',
                    'NEIGHBORHOOD_TYPE_NAME' => $neighborhoodTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="neighborhood-type.php?id='. $neighborhoodTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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