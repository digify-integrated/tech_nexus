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
require_once '../model/system-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
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
        # Type: product version history summary
        # Description:
        # Generates the product version history summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'product version history summary':
            if(isset($_POST['product_id']) && !empty($_POST['product_id'])){
                $details = '';
                $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateProductVersionHistorySummary(:productID)');
                $sql->bindValue(':productID', $productID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $productID = $row['product_id'];
                    $productPath = $row['product_path'];
                    $productVersion = $row['product_version'];
                    $uploadedBy = $row['uploaded_by'];
                    $uploadDate = $systemModel->checkDate('empty', $row['upload_date'], '', 'F j, Y h:i:s A', '');

                    $uploadedByDetails = $employeeModel->getPersonalInformation($uploadedBy);
                    $uploadedByName = $uploadedByDetails['file_as'] ?? null;

                    $productDetails = $productModel->getProduct($productID);
                    $currentProductVersion = $productDetails['product_version'];
                    $productPassword = $productDetails['product_password'];
                    $isConfidential = $productDetails['is_confidential'];

                    $productStatus = ($productVersion == $currentProductVersion) ? '<span class="badge bg-light-success">Current Version</span>' : '<span class="badge bg-light-warning">Old Version</span>';

                    $dropdown = '';
                    if($productVersion != $currentProductVersion){
                        if($isConfidential == 'No' || empty($productPassword)){
                                $dropdown = '<div class="dropdown">
                                <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical f-18"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="'. $productPath .'" class="dropdown-item" target="_blank">Preview</a>
                                </div>
                            </div>';
                        }
                    }

                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-2 text-primary"><b>Version: '. $productVersion .'</b></p>
                                            <p class="mb-2">Upload Date: ' . $uploadDate . '</p>
                                            <p class="mb-3">Uploaded By: ' . $uploadedByName . '</p>
                                            '. $productStatus .'
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No version history found.';
                }

                $response[] = [
                    'productVersionHistorySummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>