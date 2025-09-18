<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/parts-incoming-model.php');
  require('model/customer-model.php');
  require('model/supplier-model.php');
  require('model/product-model.php');

  $partsIncomingModel = new PartsIncomingModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $supplierModel = new SupplierModel($databaseModel);
  $productModel = new ProductModel($databaseModel);

  $pageTitle = 'Supplies Incoming';
    
  $partsIncomingReadAccess = $userModel->checkMenuItemAccessRights($user_id, 169, 'read');
  $partsIncomingCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 169, 'create');
  $partsIncomingWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 169, 'write');
  $partsIncomingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 169, 'delete');
  $partsIncomingDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 169, 'duplicate');
  $viewPartCost = $userModel->checkSystemActionAccessRights($user_id, 212);
  $updatePartCost = $userModel->checkSystemActionAccessRights($user_id, 211);
  $updatePartIncomingCompletedCost = $userModel->checkSystemActionAccessRights($user_id, 213);
  $approvePartsIncoming = $userModel->checkSystemActionAccessRights($user_id, 209);
  $postPartsIncoming = $userModel->checkSystemActionAccessRights($user_id, 214);

  if ($partsIncomingReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: supplies-incoming.php');
      exit;
    }

    $partsIncomingID = $securityModel->decryptData($_GET['id']);

    $checkPartsIncomingExist = $partsIncomingModel->checkPartsIncomingExist($partsIncomingID);
    $total = $checkPartsIncomingExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $partsIncomingID = null;
  }

  $company = '1';
  $cardLabel = 'Supplies';

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
                    <li class="breadcrumb-item" aria-current="page"><a href="supplies-incoming.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($partsIncomingID)){
                            echo '<li class="breadcrumb-item" id="parts-incoming-id">'. $partsIncomingID .'</li>';
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
        <input type="hidden" id="view-cost" value="<?php echo $viewPartCost['total'] ?>">
        <input type="hidden" id="update-cost" value="<?php echo $updatePartCost['total'] ?>">
        <input type="hidden" id="page-company" value="<?php echo $company ?>">
        <?php
          if($newRecord && $partsIncomingCreateAccess['total'] > 0){
            require_once('view/_parts_incoming_new.php');
          }
          else if(!empty($partsIncomingID) && $partsIncomingWriteAccess['total'] > 0){
            require_once('view/_parts_incoming_details.php');
          }
          else{
            require_once('view/_parts_incoming.php');
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
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/supplies-incoming.js?v=<?php echo rand(); ?>"></script>
</body>

</html>