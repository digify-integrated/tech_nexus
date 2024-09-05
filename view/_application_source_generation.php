<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/application-source-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$applicationSourceModel = new ApplicationSourceModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: application source table
        # Description:
        # Generates the application source table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'application source table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateApplicationSourceTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $applicationSourceDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 37, 'delete');

            foreach ($options as $row) {
                $applicationSourceID = $row['application_source_id'];
                $applicationSourceName = $row['application_source_name'];

                $applicationSourceIDEncrypted = $securityModel->encryptData($applicationSourceID);

                $delete = '';
                if($applicationSourceDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-application-source" data-application-source-id="'. $applicationSourceID .'" title="Delete Application Source">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $applicationSourceID .'">',
                    'APPLICATION_SOURCE_NAME' => $applicationSourceName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="application-source.php?id='. $applicationSourceIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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