<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/unit-category-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$unitCategoryModel = new UnitCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: unit category table
        # Description:
        # Generates the unit category table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'unit category table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateUnitCategoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $unitCategoryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 62, 'delete');

            foreach ($options as $row) {
                $unitCategoryID = $row['unit_category_id'];
                $unitCategoryName = $row['unit_category_name'];

                $unitCategoryIDEncrypted = $securityModel->encryptData($unitCategoryID);

                $delete = '';
                if($unitCategoryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-unit-category" data-unit-category-id="'. $unitCategoryID .'" title="Delete Unit Category">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $unitCategoryID .'">',
                    'UNIT_CATEGORY_NAME' => $unitCategoryName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="unit-category.php?id='. $unitCategoryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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