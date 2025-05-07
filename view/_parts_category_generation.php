<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-category-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsCategoryModel = new PartsCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: parts category table
        # Description:
        # Generates the parts category table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts category table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsCategoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $partsCategoryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 141, 'delete');

            foreach ($options as $row) {
                $partsCategoryID = $row['part_category_id'];
                $partsCategoryName = $row['part_category_name'];

                $partsCategoryIDEncrypted = $securityModel->encryptData($partsCategoryID);

                $delete = '';
                if($partsCategoryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts-category" data-parts-category-id="'. $partsCategoryID .'" title="Delete Parts Category">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $partsCategoryID .'">',
                    'PARTS_CATEGORY' => $partsCategoryName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-category.php?id='. $partsCategoryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: parts category reference table
        # Description:
        # Generates the parts category reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts category reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsCategoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $partsCategoryID = $row['part_category_id'];
                $partsCategoryName = $row['part_category_name'];

                $response[] = [
                    'PARTS_CATEGORY_ID' => $partsCategoryID,
                    'PARTS_CATEGORY' => $partsCategoryName,
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>