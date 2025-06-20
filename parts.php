<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/parts-model.php');
  require('model/parts-class-model.php');
  require('model/parts-category-model.php');
  require('model/parts-subclass-model.php');
  require('model/company-model.php');
  require('model/supplier-model.php');
  require('model/warehouse-model.php');
  require('model/brand-model.php');
  require('model/unit-model.php');
  
  $partsModel = new PartsModel($databaseModel);
  $partsClassModel = new PartsClassModel($databaseModel);
  $partsCategoryModel = new PartsCategoryModel($databaseModel);
  $partsSubclassModel = new PartsSubclassModel($databaseModel);
  $companyModel = new CompanyModel($databaseModel);
  $supplierModel = new SupplierModel($databaseModel);
  $warehouseModel = new WarehouseModel($databaseModel);
  $brandModel = new BrandModel($databaseModel);
  $unitModel = new UnitModel($databaseModel);

  $pageTitle = 'Part';
    
  $partsReadAccess = $userModel->checkMenuItemAccessRights($user_id, 142, 'read');
  $partsCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 142, 'create');
  $partsWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 142, 'write');
  $partsDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 142, 'delete');
  $partsDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 142, 'duplicate');
  $importPart = $userModel->checkSystemActionAccessRights($user_id, 98);
  $updatePartImage = $userModel->checkSystemActionAccessRights($user_id, 128);
  $viewPartCost = $userModel->checkSystemActionAccessRights($user_id, 130);
  $viewPartLogNotes = $userModel->checkSystemActionAccessRights($user_id, 184);
  $tagForSale = $userModel->checkSystemActionAccessRights($user_id, 172);
  $addPartExpense = $userModel->checkSystemActionAccessRights($user_id, 173);
  $printRecevingReport = $userModel->checkSystemActionAccessRights($user_id, 185);

  if ($partsReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: parts.php');
      exit;
    }

    $partID = $securityModel->decryptData($_GET['id']);

    $checkPartExist = $partsModel->checkPartsExist($partID);
    $total = $checkPartExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }    
  }
  else{
    $partID = null;
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
    <link rel="stylesheet" href="./assets/css/plugins/datepicker-bs5.min.css">
    <link rel="stylesheet" href="./assets/css/plugins/bootstrap-slider.min.css">
    <?php include_once('config/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="./assets/css/plugins/buttons.bootstrap5.min.css" />
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
                    <li class="breadcrumb-item" aria-current="page"><a href="parts.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($partID)){
                            echo '<li class="breadcrumb-item" id="part-id">'. $partID .'</li>';
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
          if($newRecord && $partsCreateAccess['total'] > 0){
            require_once('view/_parts_new.php');
          }
          else if(!empty($partID)){
            require_once('view/_parts_details.php');
          }
          else{
            require_once('view/_parts.php');
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
    <script src="./assets/js/plugins/bootstrap-slider.min.js"></script>
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/plugins/qr/qrcode.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/parts.js?v=<?php echo rand(); ?>"></script>
</body>

</html>