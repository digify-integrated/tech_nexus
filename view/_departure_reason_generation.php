<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/departure-reason-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$departureReasonModel = new DepartureReasonModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: departure reason table
        # Description:
        # Generates the departure reason table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'departure reason table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateDepartureReasonTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $departureReasonDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 31, 'delete');

            foreach ($options as $row) {
                $departureReasonID = $row['departure_reason_id'];
                $departureReasonName = $row['departure_reason_name'];

                $departureReasonIDEncrypted = $securityModel->encryptData($departureReasonID);

                $delete = '';
                if($departureReasonDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-departure-reason" data-departure-reason-id="'. $departureReasonID .'" title="Delete Departure Reason">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $departureReasonID .'">',
                    'DEPARTURE_REASON_NAME' => $departureReasonName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="departure-reason.php?id='. $departureReasonIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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