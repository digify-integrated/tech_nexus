<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: miscellaneous client table
        # Description:
        # Generates the miscellaneous client table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'miscellaneous client table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateMiscellaneousClientTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $miscellaneousClientDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 106, 'delete');

            foreach ($options as $row) {
                $miscellaneousClientID = $row['miscellaneous_client_id'];
                $clientName = $row['client_name'];
                $address = $row['address'];
                $tin = $row['tin'] ?? null;

                $miscellaneousClientIDEncrypted = $securityModel->encryptData($miscellaneousClientID);

                $delete = '';
                if($miscellaneousClientDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-miscellaneous-client" data-miscellaneous-client-id="'. $miscellaneousClientID .'" title="Delete Miscellaneous Client">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $miscellaneousClientID .'">',
                    'CLIENT_NAME' => $clientName,
                    'ADDRESS' => $address,
                    'TIN' => $tin,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="miscellaneous-client.php?id='. $miscellaneousClientIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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