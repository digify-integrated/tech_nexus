<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/work-center-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$workCenterModel = new WorkCenterModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: work center table
        # Description:
        # Generates the work center table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'work center table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateWorkCenterTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $workCenterDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 37, 'delete');

            foreach ($options as $row) {
                $workCenterID = $row['work_center_id'];
                $workCenterName = $row['work_center_name'];

                $workCenterIDEncrypted = $securityModel->encryptData($workCenterID);

                $delete = '';
                if($workCenterDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-work-center" data-work-center-id="'. $workCenterID .'" title="Delete Work Center">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $workCenterID .'">',
                    'WORK_CENTER_NAME' => $workCenterName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="work-center.php?id='. $workCenterIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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