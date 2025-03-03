<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/property-model.php');
  require('model/city-model.php');
  require('model/company-model.php');
  require('model/product-inventory-report-model.php');
  
  $propertyModel = new PropertyModel($databaseModel);
  $cityModel = new CityModel($databaseModel);
  $companyModel = new CompanyModel($databaseModel);
  $productInventoryReportModel = new ProductInventoryReportModel($databaseModel);

  $pageTitle = 'Product Inventory Report';
    
  $propertyReadAccess = $userModel->checkMenuItemAccessRights($user_id, 129, p_access_type: 'read');
  $openProductInventory = $userModel->checkSystemActionAccessRights($user_id, 194);
  $closeProductInventory = $userModel->checkSystemActionAccessRights($user_id, 195);
  $scanProductInventory = $userModel->checkSystemActionAccessRights($user_id, 196);

  if ($propertyReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: product-inventory-report.php');
      exit;
    }

    $productInventoryID = $securityModel->decryptData($_GET['id']);

    $checkProductInventoryExist = $productInventoryReportModel->checkProductInventoryExist($productInventoryID);
    $total = $checkProductInventoryExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $productInventoryID = null;
  }

  $getInventoryReportClosed = $productInventoryReportModel->getInventoryReportClosed();

  $newRecord = isset($_GET['new']);

  require('config/_interface_settings.php');
  require('config/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/select2.min.css">
    <?php include_once('config/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/buttons.bootstrap5.min.css" />
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
                    <li class="breadcrumb-item" aria-current="page"><a href="product-inventory-report.php"><?php echo $pageTitle; ?></a></li>
                  <?php
                    if(!$newRecord && !empty($productInventoryID)){
                      echo '<li class="breadcrumb-item" id="product-inventory-id">'. $productInventoryID .'</li>';
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
          if(!empty($productInventoryID)){
            require_once('view/_product_inventory_details.php');
          }
          else{
            require_once('view/_product_inventory.php');
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
    <script src="./assets/js/plugins/dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/buttons.colVis.min.js"></script>
    <script src="./assets/js/plugins/buttons.print.min.js"></script>
    <script src="./assets/js/plugins/pdfmake.min.js"></script>
    <script src="./assets/js/plugins/jszip.min.js"></script>
    <script src="./assets/js/plugins/dataTables.buttons.min.js"></script>
    <script src="./assets/js/plugins/vfs_fonts.js"></script>
    <script src="./assets/js/plugins/buttons.html5.min.js"></script>
    <script src="./assets/js/plugins/buttons.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/product-inventory-report.js?v=<?php echo rand(); ?>"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

</body>

</html>