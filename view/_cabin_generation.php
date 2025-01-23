<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/cabin-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$cabinModel = new CabinModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: cabin table
        # Description:
        # Generates the cabin table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'cabin table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateCabinTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $cabinDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 98, 'delete');

            foreach ($options as $row) {
                $cabinID = $row['cabin_id'];
                $cabinName = $row['cabin_name'];

                $cabinIDEncrypted = $securityModel->encryptData($cabinID);

                $delete = '';
                if($cabinDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-cabin" data-cabin-id="'. $cabinID .'" title="Delete Cabin">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $cabinID .'">',
                    'CABIN_NAME' => $cabinName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="cabin.php?id='. $cabinIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: cabin reference table
        # Description:
        # Generates the cabin reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'cabin reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateCabinTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $cabinID = $row['cabin_id'];
                $cabinName = $row['cabin_name'];

                $response[] = [
                    'BRAND_ID' => $cabinID,
                    'BRAND' => $cabinName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>