<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-subclass-model.php';
require_once '../model/parts-class-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsSubclassModel = new PartsSubclassModel($databaseModel);
$partsClassModel = new PartsClassModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: parts subclass table
        # Description:
        # Generates the parts subclass table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts subclass table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsSubclassTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $partsSubclassDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 140, 'delete');

            foreach ($options as $row) {
                $partsSubclassID = $row['part_subclass_id'];
                $partsSubclassName = $row['part_subclass_name'];
                $partsClassID = $row['part_class_id'];

                $partsClassDetails = $partsClassModel->getPartsClass($partsClassID);
                $partsClassName = $partsClassDetails['part_class_name'];

                $partsSubclassIDEncrypted = $securityModel->encryptData($partsSubclassID);

                $delete = '';
                if($partsSubclassDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts-subclass" data-parts-subclass-id="'. $partsSubclassID .'" title="Delete Part Subclass">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $partsSubclassID .'">',
                    'PARTS_SUBCLASS' => $partsSubclassName,
                    'PARTS_CLASS' => $partsClassName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-subclass.php?id='. $partsSubclassIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: parts subclass reference table
        # Description:
        # Generates the parts subclass reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts subclass reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsSubclassTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $partsSubclassID = $row['part_subclass_id'];
                $partsSubclassName = $row['part_subclass_name'];

                $response[] = [
                    'PARTS_SUBCLASS_ID' => $partsSubclassID,
                    'PARTS_SUBCLASS' => $partsSubclassName,
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>