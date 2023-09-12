<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/civil-status-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$civilStatusModel = new CivilStatusModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: civil status table
        # Description:
        # Generates the civil status table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'civil status table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateCivilStatusTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $civilStatusDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 37, 'delete');

            foreach ($options as $row) {
                $civilStatusID = $row['civil_status_id'];
                $civilStatusName = $row['civil_status_name'];

                $civilStatusIDEncrypted = $securityModel->encryptData($civilStatusID);

                $delete = '';
                if($civilStatusDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-civil-status" data-civil-status-id="'. $civilStatusID .'" title="Delete Civil Status">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $civilStatusID .'">',
                    'CIVIL_STATUS_NAME' => $civilStatusName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="civil-status.php?id='. $civilStatusIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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