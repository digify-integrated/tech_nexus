<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/structure-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$structureTypeModel = new StructureTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: structure type table
        # Description:
        # Generates the structure type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'structure type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateStructureTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $structureTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 154, 'delete');

            foreach ($options as $row) {
                $structureTypeID = $row['structure_type_id'];
                $structureTypeName = $row['structure_type_name'];

                $structureTypeIDEncrypted = $securityModel->encryptData($structureTypeID);

                $delete = '';
                if($structureTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-structure-type" data-structure-type-id="'. $structureTypeID .'" title="Delete Structure Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $structureTypeID .'">',
                    'STRUCTURE_TYPE_NAME' => $structureTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="structure-type.php?id='. $structureTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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