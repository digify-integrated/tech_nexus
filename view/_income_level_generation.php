<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/income-level-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$incomeLevelModel = new IncomeLevelModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: income level table
        # Description:
        # Generates the income level table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'income level table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateIncomeLevelTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $incomeLevelDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 157, 'delete');

            foreach ($options as $row) {
                $incomeLevelID = $row['income_level_id'];
                $incomeLevelName = $row['income_level_name'];

                $incomeLevelIDEncrypted = $securityModel->encryptData($incomeLevelID);

                $delete = '';
                if($incomeLevelDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-income-level" data-income-level-id="'. $incomeLevelID .'" title="Delete Income Level">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $incomeLevelID .'">',
                    'INCOME_LEVEL_NAME' => $incomeLevelName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="income-level.php?id='. $incomeLevelIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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