<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/zoom-api-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$zoomAPIModel = new ZoomAPIModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: zoom API table
        # Description:
        # Generates the zoom API table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'zoom API table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateZoomAPITable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $zoomAPIDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 17, 'delete');

            foreach ($options as $row) {
                $zoomAPIID = $row['zoom_api_id'];
                $zoomAPIName = $row['zoom_api_name'];
                $zoomAPIDescription = $row['zoom_api_description'];

                $zoomAPIIDEncrypted = $securityModel->encryptData($zoomAPIID);

                $delete = '';
                if($zoomAPIDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-zoom-api" data-zoom-api-id="'. $zoomAPIID .'" title="Delete Zoom API">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $zoomAPIID .'">',
                    'SYSTEM_SETTING_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $zoomAPIName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $zoomAPIDescription .'</p>
                                        </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="zoom-api.php?id='. $zoomAPIIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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