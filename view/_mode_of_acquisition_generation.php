<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/mode-of-acquisition-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$modeOfAcquisitionModel = new ModeOfAcquisitionModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: mode of acquisition table
        # Description:
        # Generates the mode of acquisition table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'mode of acquisition table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateModeOfAcquisitionTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $modeOfAcquisitionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 104, 'delete');

            foreach ($options as $row) {
                $modeOfAcquisitionID = $row['mode_of_acquisition_id'];
                $modeOfAcquisitionName = $row['mode_of_acquisition_name'];

                $modeOfAcquisitionIDEncrypted = $securityModel->encryptData($modeOfAcquisitionID);

                $delete = '';
                if($modeOfAcquisitionDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-mode-of-acquisition" data-mode-of-acquisition-id="'. $modeOfAcquisitionID .'" title="Delete Mode Of Acquisition">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $modeOfAcquisitionID .'">',
                    'MODE_OF_ACQUISITION_NAME' => $modeOfAcquisitionName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="mode-of-acquisition.php?id='. $modeOfAcquisitionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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