<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/model-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$modelModel = new ModelModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: model table
        # Description:
        # Generates the model table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'model table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateModelTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $modelDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 100, 'delete');

            foreach ($options as $row) {
                $modelID = $row['model_id'];
                $modelName = $row['model_name'];

                $modelIDEncrypted = $securityModel->encryptData($modelID);

                $delete = '';
                if($modelDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-model" data-model-id="'. $modelID .'" title="Delete Model">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $modelID .'">',
                    'MODEL_NAME' => $modelName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="model.php?id='. $modelIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: model reference table
        # Description:
        # Generates the model reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'model reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateModelTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $modelID = $row['model_id'];
                $modelName = $row['model_name'];

                $response[] = [
                    'MODEL_ID' => $modelID,
                    'MODEL' => $modelName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>