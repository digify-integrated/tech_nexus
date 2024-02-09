<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/warehouse-model.php');
  
  $warehouseModel = new WarehouseModel($databaseModel);

  $pageTitle = 'Warehouse';
    
  $warehouseReadAccess = $userModel->checkMenuItemAccessRights($user_id, 64, 'read');
  $warehouseCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 64, 'create');
  $warehouseWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 64, 'write');
  $warehouseDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 64, 'delete');
  $warehouseDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 64, 'duplicate');

  if ($warehouseReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: warehouse.php');
      exit;
    }

    $warehouseID = $securityModel->decryptData($_GET['id']);

    $checkWarehouseExist = $warehouseModel->checkWarehouseExist($warehouseID);
    $total = $checkWarehouseExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $warehouseID = null;
  }

  $newRecord = isset($_GET['new']);

  require('config/_interface_settings.php');
  require('config/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
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
                    <li class="breadcrumb-item">Configurations</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="warehouse.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($warehouseID)){
                            echo '<li class="breadcrumb-item" id="warehouse-id">'. $warehouseID .'</li>';
                        }

                        if($newRecord){
                            echo '<li class="breadcrumb-item">New</li>';
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
          if($newRecord && $warehouseCreateAccess['total'] > 0){
            require_once('view/_warehouse_new.php');
          }
          else if(!empty($warehouseID) && $warehouseWriteAccess['total'] > 0){
            require_once('view/_warehouse_details.php');
          }
          else{
            require_once('view/_warehouse.php');
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
    <script src="./assets/js/pages/warehouse.js?v=<?php echo rand(); ?>"></script>
</body>

</html>