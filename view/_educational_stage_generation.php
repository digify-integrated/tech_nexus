<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/educational-stage-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$educationalStageModel = new EducationalStageModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: educational stage table
        # Description:
        # Generates the educational stage table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'educational stage table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateEducationalStageTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $educationalStageDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 46, 'delete');

            foreach ($options as $row) {
                $educationalStageID = $row['educational_stage_id'];
                $educationalStageName = $row['educational_stage_name'];

                $educationalStageIDEncrypted = $securityModel->encryptData($educationalStageID);

                $delete = '';
                if($educationalStageDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-educational-stage" data-educational-stage-id="'. $educationalStageID .'" title="Delete Educational Stage">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $educationalStageID .'">',
                    'EDUCATIONAL_STAGE_NAME' => $educationalStageName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="educational-stage.php?id='. $educationalStageIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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