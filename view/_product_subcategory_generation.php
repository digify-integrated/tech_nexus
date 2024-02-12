<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/product-category-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: product subcategory table
        # Description:
        # Generates the product subcategory table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'product subcategory table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateProductSubcategoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $productSubcategoryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 66, 'delete');

            foreach ($options as $row) {
                $productSubcategoryID = $row['product_subcategory_id'];
                $productSubcategoryName = $row['product_subcategory_name'];
                $productSubcategoryCode = $row['product_subcategory_code'];
                $productCategoryID = $row['product_category_id'];

                $productCategoryDetails = $productCategoryModel->getProductCategory($productCategoryID);
                $productCategoryName = $productCategoryDetails['product_category_name'];

                $productSubcategoryIDEncrypted = $securityModel->encryptData($productSubcategoryID);

                $delete = '';
                if($productSubcategoryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-product-subcategory" data-product-subcategory-id="'. $productSubcategoryID .'" title="Delete Product Subcategory">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $productSubcategoryID .'">',
                    'PRODUCT_SUBCATEGORY_CODE' => $productSubcategoryCode,
                    'PRODUCT_SUBCATEGORY' => $productSubcategoryName,
                    'PRODUCT_CATEGORY' => $productCategoryName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="product-subcategory.php?id='. $productSubcategoryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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