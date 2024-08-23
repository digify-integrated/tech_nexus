<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/brand-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$brandModel = new BrandModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: brand table
        # Description:
        # Generates the brand table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'brand table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBrandTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $brandDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 98, 'delete');

            foreach ($options as $row) {
                $brandID = $row['brand_id'];
                $brandName = $row['brand_name'];

                $brandIDEncrypted = $securityModel->encryptData($brandID);

                $delete = '';
                if($brandDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-brand" data-brand-id="'. $brandID .'" title="Delete Brand">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $brandID .'">',
                    'BRAND_NAME' => $brandName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="brand.php?id='. $brandIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: brand reference table
        # Description:
        # Generates the brand reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'brand reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBrandTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $brandID = $row['brand_id'];
                $brandName = $row['brand_name'];

                $response[] = [
                    'BRAND_ID' => $brandID,
                    'BRAND' => $brandName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>