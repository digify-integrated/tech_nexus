<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/product-model.php';
require_once '../model/product-category-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/company-model.php';
require_once '../model/warehouse-model.php';
require_once '../model/body-type-model.php';
require_once '../model/unit-model.php';
require_once '../model/color-model.php';
require_once '../model/system-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$warehouseModel = new WarehouseModel($databaseModel);
$bodyTypeModel = new BodyTypeModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
$colorModel = new ColorModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: product card
        # Description:
        # Generates the product card.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'product card':
            if(isset($_POST['current_page']) && isset($_POST['product_search']) && isset($_POST['product_category_filter']) && isset($_POST['product_subcategory_filter']) && isset($_POST['company_filter']) && isset($_POST['warehouse_filter']) && isset($_POST['body_type_filter']) && isset($_POST['filter_product_cost_min']) && isset($_POST['filter_product_cost_max']) && isset($_POST['filter_product_price_min']) && isset($_POST['filter_product_price_max'])){
                $initialProductPerPage = 8;
                $loadMoreProductPerPage = 4;
                $productPerPage = $initialProductPerPage;
                
                $currentPage = htmlspecialchars($_POST['current_page'], ENT_QUOTES, 'UTF-8');
                $productSearch = htmlspecialchars($_POST['product_search'], ENT_QUOTES, 'UTF-8');
                $companyFilter = htmlspecialchars($_POST['company_filter'], ENT_QUOTES, 'UTF-8');
                $productCategoryFilter = htmlspecialchars($_POST['product_category_filter'], ENT_QUOTES, 'UTF-8');
                $productSubcategoryFilter = htmlspecialchars($_POST['product_subcategory_filter'], ENT_QUOTES, 'UTF-8');
                $warehouseFilter = htmlspecialchars($_POST['warehouse_filter'], ENT_QUOTES, 'UTF-8');
                $bodyTypeFilter = htmlspecialchars($_POST['body_type_filter'], ENT_QUOTES, 'UTF-8');
                $colorFilter = htmlspecialchars($_POST['color_filter'], ENT_QUOTES, 'UTF-8');
                $filterProductCostMin = htmlspecialchars($_POST['filter_product_cost_min'], ENT_QUOTES, 'UTF-8');
                $filterProductCostMax = htmlspecialchars($_POST['filter_product_cost_max'], ENT_QUOTES, 'UTF-8');
                $filterProductPriceMin = htmlspecialchars($_POST['filter_product_price_min'], ENT_QUOTES, 'UTF-8');
                $filterProductPriceMax = htmlspecialchars($_POST['filter_product_price_max'], ENT_QUOTES, 'UTF-8');
                $offset = ($currentPage - 1) * $productPerPage;

                $sql = $databaseModel->getConnection()->prepare('CALL generateProductCard(:offset, :productPerPage, :productSearch, :productCategoryFilter, :productSubcategoryFilter, :companyFilter, :warehouseFilter, :bodyTypeFilter, :colorFilter, :filterProductCostMin, :filterProductCostMax, :filterProductPriceMin, :filterProductPriceMax)');
                $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sql->bindValue(':productPerPage', $productPerPage, PDO::PARAM_INT);
                $sql->bindValue(':productSearch', $productSearch, PDO::PARAM_STR);
                $sql->bindValue(':productCategoryFilter', $productCategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':productSubcategoryFilter', $productSubcategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':companyFilter', $companyFilter, PDO::PARAM_STR);
                $sql->bindValue(':warehouseFilter', $warehouseFilter, PDO::PARAM_STR);
                $sql->bindValue(':bodyTypeFilter', $bodyTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':colorFilter', $colorFilter, PDO::PARAM_STR);
                $sql->bindValue(':filterProductCostMin', $filterProductCostMin, PDO::PARAM_STR);
                $sql->bindValue(':filterProductCostMax', $filterProductCostMax, PDO::PARAM_STR);
                $sql->bindValue(':filterProductPriceMin', $filterProductPriceMin, PDO::PARAM_STR);
                $sql->bindValue(':filterProductPriceMax', $filterProductPriceMax, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $productDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 67, 'delete');
                
                foreach ($options as $row) {
                    $productID = $row['product_id'];
                    $productCategoryID = $row['product_category_id'];
                    $productSubategoryID = $row['product_subcategory_id'];
                    $stockNumber = $row['stock_number'];
                    $description = $row['description'];
                    $productStatus = $productModel->getProductStatus($row['product_status']);
                    $productImage = $systemModel->checkImage($row['product_image'], 'default');

                    $productCategoryDetails = $productCategoryModel->getProductCategory($productCategoryID);
                    $productCategoryName = $productCategoryDetails['product_category_name'];

                    $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
                    $productSubcategoryName = $productSubcategoryDetails['product_subcategory_name'];
                   
                    $productIDEncrypted = $securityModel->encryptData($productID);

                    $delete = '';
                    if($productDeleteAccess['total'] > 0){
                        $delete = '<div class="btn-prod-cart card-body position-absolute end-0 bottom-0">
                                        <button class="btn btn-danger delete-product" data-product-id="'. $productID .'" title="Delete Product">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>';
                    }
    
                    $response[] = [
                        'productCard' => '<div class="col-md-3">
                                            <div class="card product-card">
                                                <div class="card-img-top">
                                                    <a href="product.php?id='. $productIDEncrypted .'"">
                                                        <img src="'. $productImage .'" alt="image" class="img-prod img-fluid w-100" />
                                                    </a>
                                                    <div class="card-body position-absolute end-0 top-0">
                                                        <div class="form-check prod-likes">
                                                            <input type="checkbox" class="form-check-input" />
                                                            <i data-feather="heart" class="prod-likes-icon"></i>
                                                        </div>
                                                    </div>
                                                    <div class="card-body position-absolute start-0 top-0">
                                                        <span class="badge bg-info">'. $productSubcategoryName .'</span>
                                                    </div>
                                                    '. $delete .'
                                                </div>
                                                <div class="card-body">
                                                    <a href="product.php?id='. $productIDEncrypted .'"">
                                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                                            <h5 class="mb-0 text-truncate text-primary"><b>'. $description .'</b></h5>
                                                        </div>
                                                        <p class="prod-content mb-0 text-muted">'. $stockNumber .'</p>
                                                    </a>
                                                </div>
                                            </div>'
                    ];
                }

                if ($productPerPage === $initialProductPerPage) {
                    $productPerPage = $loadMoreProductPerPage;
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: import product table
        # Description:
        # Generates the import product table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'import product table':
            if(isset($_POST['product_category_filter']) && isset($_POST['product_subcategory_filter']) && isset($_POST['company_filter']) && isset($_POST['warehouse_filter']) && isset($_POST['body_type_filter'])){
                $companyFilter = htmlspecialchars($_POST['company_filter'], ENT_QUOTES, 'UTF-8');
                $productCategoryFilter = htmlspecialchars($_POST['product_category_filter'], ENT_QUOTES, 'UTF-8');
                $productSubcategoryFilter = htmlspecialchars($_POST['product_subcategory_filter'], ENT_QUOTES, 'UTF-8');
                $warehouseFilter = htmlspecialchars($_POST['warehouse_filter'], ENT_QUOTES, 'UTF-8');
                $bodyTypeFilter = htmlspecialchars($_POST['body_type_filter'], ENT_QUOTES, 'UTF-8');
                $colorFilter = htmlspecialchars($_POST['color_filter'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateImportProductTable(:productCategoryFilter, :productSubcategoryFilter, :companyFilter, :warehouseFilter, :bodyTypeFilter, :colorFilter)');
                $sql->bindValue(':productCategoryFilter', $productCategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':productSubcategoryFilter', $productSubcategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':companyFilter', $companyFilter, PDO::PARAM_STR);
                $sql->bindValue(':warehouseFilter', $warehouseFilter, PDO::PARAM_STR);
                $sql->bindValue(':bodyTypeFilter', $bodyTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':colorFilter', $colorFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $tempProductID = $row['temp_product_id'];
                    $productID = $row['product_id'];
                    $productCategoryID = $row['product_category_id'];
                    $productSubcategoryID = $row['product_subcategory_id'];
                    $companyID = $row['company_id'];
                    $productStatus = $row['product_status'];
                    $stockNumber = $row['stock_number'];
                    $engineNumber = $row['engine_number'];
                    $chassisNumber = $row['chassis_number'];
                    $description = $row['description'];
                    $warehouseID = $row['warehouse_id'];
                    $bodyTypeID = $row['body_type_id'];
                    $length = $row['length'];
                    $lengthUnit = $row['length_unit'];
                    $runningHours = $row['running_hours'];
                    $mileage = $row['mileage'];
                    $colorID = $row['color_id'];
                    $productCost = $row['product_cost'];
                    $productPrice = $row['product_price'];
                    $remarks = $row['remarks'];

                    $productCategoryDetails = $productCategoryModel->getProductCategory($productCategoryID);
                    $productCategoryName = $productCategoryDetails['product_category_name'] ?? null;

                    $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productCategoryID);
                    $productSubcategoryName = $productSubcategoryDetails['product_subcategory_name'] ?? null;

                    $companyDetails = $companyModel->getcompany($companyID);
                    $companyName = $companyDetails['company_name'] ?? null;

                    $warehouseDetails = $warehouseModel->getWarehouse($warehouseID);
                    $warehouseName = $warehouseDetails['warehouse_name'] ?? null;

                    $bodyTypeDetails = $bodyTypeModel->getBodyType($bodyTypeID);
                    $bodyTypeName = $bodyTypeDetails['body_type_name'] ?? null;

                    $unitDetails = $unitModel->getUnit($lengthUnit);
                    $unitShortName = $unitDetails['short_name'] ?? null;

                    $colorDetails = $colorModel->getColor($colorID);
                    $colorName = $colorDetails['color_name'] ?? null;

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $tempProductID .'">',
                        'PRODUCT_ID' => $productID,
                        'PRODUCT_CATEGORY' => $productCategoryName,
                        'PRODUCT_SUBCATEGORY' => $productSubcategoryName,
                        'COMPANY_NAME' => $companyName,
                        'PRODUCT_STATUS' => $productStatus,
                        'STOCK_NUMBER' => $stockNumber,
                        'ENGINE_NUMBER' => $engineNumber,
                        'CHASSIS_NUMBER' => $chassisNumber,
                        'DESCRIPTION' => $description,
                        'WAREHOUSE_NAME' => $warehouseName,
                        'BODY_TYPE_NAME' => $bodyTypeName,
                        'LENGTH' => $length . ' ' . $unitShortName,
                        'RUNNING_HOURS' => $runningHours,
                        'MILEAGE' => $mileage,
                        'COLOR' => $colorName,
                        'PRODUCT_COST' => number_format($productCost, 2),
                        'PRODUCT_PRICE' => number_format($productPrice, 2),
                        'REMARKS' => $remarks
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>