<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/product-model.php');
  require('model/product-category-model.php');
  require('model/product-subcategory-model.php');
  require('model/body-type-model.php');
  require('model/warehouse-model.php');
  require('model/unit-model.php');
  require('model/color-model.php');
  require('model/company-model.php');
  
  $productModel = new ProductModel($databaseModel);
  $productCategoryModel = new ProductCategoryModel($databaseModel);
  $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
  $companyModel = new CompanyModel($databaseModel);
  $bodyTypeModel = new BodyTypeModel($databaseModel);
  $warehouseModel = new WarehouseModel($databaseModel);
  $unitModel = new UnitModel($databaseModel);
  $colorModel = new ColorModel($databaseModel);

  $pageTitle = 'Product';
    
  $productReadAccess = $userModel->checkMenuItemAccessRights($user_id, 67, 'read');
  $productCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 67, 'create');
  $productWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 67, 'write');
  $productDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 67, 'delete');
  $productDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 67, 'duplicate');
  $importProduct = $userModel->checkSystemActionAccessRights($user_id, 98);
  $updateProductImage = $userModel->checkSystemActionAccessRights($user_id, 128);

  if ($productReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: product.php');
      exit;
    }

    $productID = $securityModel->decryptData($_GET['id']);

    $checkProductExist = $productModel->checkProductExist($productID);
    $total = $checkProductExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $productDetails = $productModel->getProduct($productID);
    $productCategoryID = $productDetails['product_category_id'];
    $productSubategoryID = $productDetails['product_subcategory_id'];
    $companyID = $productDetails['company_id'];
    $stockNumber = $productDetails['stock_number'];
    $description = $productDetails['description'];
    $engineNumber = $productDetails['engine_number'];
    $chassisNumber = $productDetails['chassis_number'];
    $plateNumber = $productDetails['plate_number'];
    $warehouseID = $productDetails['warehouse_id'];
    $bodyTypeID = $productDetails['body_type_id'];
    $colorID = $productDetails['color_id'];
    $lengthUnit = $productDetails['length_unit'];
    $length = number_format($productDetails['length'], 2);
    $runningHours = number_format($productDetails['running_hours'], 2);
    $mileage = number_format($productDetails['mileage'], 2);
    $productCost = number_format($productDetails['product_cost'], 2);
    $productPrice = number_format($productDetails['product_price'], 2);
    $remarks = !empty($productDetails['remarks']) ? $productDetails['remarks'] : '--';
    $productImage = $systemModel->checkImage($productDetails['product_image'], 'default');
    $productStatus = $productModel->getProductStatus($productDetails['product_status']);

    $productCategoryDetails = $productCategoryModel->getProductCategory($productCategoryID);
    $productCategoryName = $productCategoryDetails['product_category_name'] ?? '--';

    $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
    $productSubcategoryName = $productSubcategoryDetails['product_subcategory_name'] ?? '--';

    $companyDetails = $companyModel->getCompany($companyID);
    $companyName = $companyDetails['company_name'] ?? '--';

    $warehouseDetails = $warehouseModel->getWarehouse($warehouseID);
    $warehouseName = $warehouseDetails['warehouse_name']  ?? '--';

    $bodyTypeDetails = $bodyTypeModel->getBodyType($bodyTypeID);
    $bodyTypeName = $bodyTypeDetails['body_type_name']  ?? '--';

    $colorDetails = $colorModel->getColor($colorID);
    $colorName = $colorDetails['color_name'] ?? '--';

    $unitDetails = $unitModel->getUnit($lengthUnit);
    $shortName = $unitDetails['short_name'] ?? null;
  }
  else{
    $productID = null;
  }

  $importRecord = isset($_GET['import']);
  $newRecord = isset($_GET['new']);

  require('config/_interface_settings.php');
  require('config/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/select2.min.css">
    <link rel="stylesheet" href="./assets/css/plugins/bootstrap-slider.min.css">
    <?php include_once('config/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/dataTables.bootstrap5.min.css">
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="false" data-pc-theme="light">
    <?php 
        include_once('config/_preloader.html'); 
        include_once('config/_navbar.php'); 
        include_once('config/_header.php'); 
        include_once('config/_announcement.php'); 
    ?>   

    <section class="pc-container">
      <div class="pc-content">
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="product.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($productID)){
                            echo '<li class="breadcrumb-item" id="product-id">'. $productID .'</li>';
                        }

                        if($newRecord){
                            echo '<li class="breadcrumb-item">New</li>';
                        }

                        if($importRecord){
                          echo '<li class="breadcrumb-item">Import</li>';
                      }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0 text-primary"><?php echo $pageTitle; ?></h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord && $productCreateAccess['total'] > 0){
            require_once('view/_product_new.php');
          }
          else if($importRecord && $importProduct['total'] > 0){
            require_once('view/_product_import.php');
          }
          else if(!empty($productID)){
            require_once('view/_product_details.php');
          }
          else{
            require_once('view/_product.php');
          }
        ?>
      </div>
    </section>
    
    <?php 
        include_once('config/_footer.php'); 
        include_once('config/_change_password_modal.php');
        include_once('config/_error_modal.php');
        include_once('config/_required_js.php'); 
        include_once('config/_customizer.php'); 
    ?>
    <script src="./assets/js/plugins/bootstrap-maxlength.min.js"></script>
    <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/bootstrap-slider.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/product.js?v=<?php echo rand(); ?>"></script>
</body>

</html>