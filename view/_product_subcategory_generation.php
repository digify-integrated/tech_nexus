<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/product-category-model.php';
require_once '../model/unit-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
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

        # -------------------------------------------------------------
        #
        # Type: product subcategory reference table
        # Description:
        # Generates the product subcategory reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'product subcategory reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateProductSubcategoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $productSubcategoryID = $row['product_subcategory_id'];
                $productSubcategoryName = $row['product_subcategory_name'];

                $response[] = [
                    'PRODUCT_SUBCATEGORY_ID' => $productSubcategoryID,
                    'PRODUCT_SUBCATEGORY' => $productSubcategoryName,
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        case 'pricing threshold table':
            $userId = $_SESSION['user_id'];
            $filter_product_subcategory = $_POST['filter_product_subcategory'];

            if (!empty($filter_product_subcategory)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_product_subcategory)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_product_subcategory = implode(', ', $quoted_values_array);
            } else {
                $filter_product_subcategory = null;
            }

            $thresholds = $productSubcategoryModel->generatePricingThresholdTable($filter_product_subcategory);

            $pricingThresholdWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 209, 'write');
            $pricingThresholdDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 209, 'delete');

            foreach ($thresholds as $row) {
                $quotation_standard_pricing_id = $row['quotation_standard_pricing_id'];
                $product_subcategory_id = $row['product_subcategory_id'];
                $year_model = $row['year_model'];
                $price_threshold = $row['price_threshold'] ?? 0;
                $discount_threshold = $row['discount_threshold'] ?? 0;
                $transmission_type = $row['transmission_type'] ?? 0;
                $length = $row['length'] ?? 0;
                $length_unit = $row['length_unit'] ?? 0;

                $unitDetails = $unitModel->getUnit($length_unit);
                $unitShortName = $unitDetails['short_name'] ?? null;

                $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($product_subcategory_id);
                $productSubcategoryName = $productSubcategoryDetails['product_subcategory_name'] ?? null;

                $actions = '';

                if($pricingThresholdDeleteAccess['total'] > 0){
                    $actions .= '<button type="button" class="btn btn-icon btn-danger delete-pricing-threshold" data-quotation-standard-pricing-id="' . $quotation_standard_pricing_id . '" title="Delete Pricing Threshold">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $quotation_standard_pricing_id .'">',
                    'CATEGORY' => $productSubcategoryName,
                    'YEAR_MODEL' => $year_model,
                    'TRANSMISSION_TYPE' => $transmission_type,
                    'LENGTH' => $length . ' ' . $unitShortName,
                    'PRICE_THRESHOLD' => number_format($price_threshold, 2),
                    'DISCOUNT_THRESHOLD' => number_format($discount_threshold, 2),
                    'ACTION' => '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-success update-pricing-threshold" data-bs-toggle="modal" data-bs-target="#pricing-threshold-modal" data-quotation-standard-pricing-id="' . $quotation_standard_pricing_id . '" title="Update Pricing Threshold">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    '. $actions .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
    }
}

?>