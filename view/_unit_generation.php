<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/unit-model.php';
require_once '../model/unit-category-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$unitModel = new UnitModel($databaseModel);
$unitCategoryModel = new UnitCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: unit table
        # Description:
        # Generates the unit table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'unit table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateUnitTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $unitDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 63, 'delete');

            foreach ($options as $row) {
                $unitID = $row['unit_id'];
                $unitName = $row['unit_name'];
                $shortName = $row['short_name'];
                $unitCategoryID = $row['unit_category_id'];

                $unitCategoryDetails = $unitCategoryModel->getUnitCategory($unitCategoryID);
                $unitCategoryName = $unitCategoryDetails['unit_category_name'];

                $unitIDEncrypted = $securityModel->encryptData($unitID);

                $delete = '';
                if($unitDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-unit" data-unit-id="'. $unitID .'" title="Delete Unit">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $unitID .'">',
                    'UNIT_NAME' => $unitName,
                    'SHORT_NAME' => $shortName,
                    'UNIT_CATEGORY' => $unitCategoryName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="unit.php?id='. $unitIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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