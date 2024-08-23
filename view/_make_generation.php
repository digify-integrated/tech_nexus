<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/make-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$makeModel = new MakeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: make table
        # Description:
        # Generates the make table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'make table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateMakeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $makeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 99, 'delete');

            foreach ($options as $row) {
                $makeID = $row['make_id'];
                $makeName = $row['make_name'];

                $makeIDEncrypted = $securityModel->encryptData($makeID);

                $delete = '';
                if($makeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-make" data-make-id="'. $makeID .'" title="Delete Make">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $makeID .'">',
                    'MAKE_NAME' => $makeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="make.php?id='. $makeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: make reference table
        # Description:
        # Generates the make reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'make reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateMakeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $makeID = $row['make_id'];
                $makeName = $row['make_name'];

                $response[] = [
                    'MAKE_ID' => $makeID,
                    'MAKE' => $makeName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>