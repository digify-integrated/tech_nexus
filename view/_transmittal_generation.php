<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/transmittal-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$transmittalModel = new TransmittalModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: transmittal table
        # Description:
        # Generates the transmittal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'transmittal table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateTransmittalTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $transmittalDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 52, 'delete');

            foreach ($options as $row) {
                $transmittalID = $row['transmittal_id'];
                $transmittalName = $row['transmittal_name'];

                $transmittalIDEncrypted = $securityModel->encryptData($transmittalID);

                $delete = '';
                if($transmittalDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-transmittal" data-transmittal-id="'. $transmittalID .'" title="Delete Transmittal">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $transmittalID .'">',
                    'ID_TYPE_NAME' => $transmittalName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="transmittal.php?id='. $transmittalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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