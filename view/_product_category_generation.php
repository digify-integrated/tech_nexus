<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/product-category-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: product category table
        # Description:
        # Generates the product category table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'product category table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateProductCategoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $productCategoryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 65, 'delete');

            foreach ($options as $row) {
                $productCategoryID = $row['product_category_id'];
                $productCategoryName = $row['product_category_name'];

                $productCategoryIDEncrypted = $securityModel->encryptData($productCategoryID);

                $delete = '';
                if($productCategoryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-product-category" data-product-category-id="'. $productCategoryID .'" title="Delete Product Category">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $productCategoryID .'">',
                    'PRODUCT_CATEGORY_NAME' => $productCategoryName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="product-category.php?id='. $productCategoryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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