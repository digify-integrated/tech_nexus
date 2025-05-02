<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-class-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsClassModel = new PartsClassModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: parts class table
        # Description:
        # Generates the parts class table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts class table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsClassTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $partsClassDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 139, 'delete');

            foreach ($options as $row) {
                $partsClassID = $row['part_class_id'];
                $partsClassName = $row['part_class_name'];

                $partsClassIDEncrypted = $securityModel->encryptData($partsClassID);

                $delete = '';
                if($partsClassDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts-class" data-parts-class-id="'. $partsClassID .'" title="Delete Parts Class">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $partsClassID .'">',
                    'PARTS_CLASS' => $partsClassName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-class.php?id='. $partsClassIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: parts class reference table
        # Description:
        # Generates the parts class reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts class reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsClassTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $partsClassID = $row['part_class_id'];
                $partsClassName = $row['part_class_name'];

                $response[] = [
                    'PARTS_CLASS_ID' => $partsClassID,
                    'PARTS_CLASS' => $partsClassName,
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>