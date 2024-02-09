<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/color-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$colorModel = new ColorModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: color table
        # Description:
        # Generates the color table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'color table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateColorTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $colorDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 61, 'delete');

            foreach ($options as $row) {
                $colorID = $row['color_id'];
                $colorName = $row['color_name'];

                $colorIDEncrypted = $securityModel->encryptData($colorID);

                $delete = '';
                if($colorDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-color" data-color-id="'. $colorID .'" title="Delete Color">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $colorID .'">',
                    'COLOR_NAME' => $colorName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="color.php?id='. $colorIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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