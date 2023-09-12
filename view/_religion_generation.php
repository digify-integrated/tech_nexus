<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/religion-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$religionModel = new ReligionModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: religion table
        # Description:
        # Generates the religion table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'religion table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateReligionTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $religionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 34, 'delete');

            foreach ($options as $row) {
                $religionID = $row['religion_id'];
                $religionName = $row['religion_name'];

                $religionIDEncrypted = $securityModel->encryptData($religionID);

                $delete = '';
                if($religionDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-religion" data-religion-id="'. $religionID .'" title="Delete Religion">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $religionID .'">',
                    'RELIGION_NAME' => $religionName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="religion.php?id='. $religionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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