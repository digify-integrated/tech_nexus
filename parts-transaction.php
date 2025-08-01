<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/parts-transaction-model.php');
  require('model/customer-model.php');
  require('model/product-model.php');
  require('model/miscellaneous-client-model.php');

  $partsTransactionModel = new PartsTransactionModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
  $productModel = new ProductModel($databaseModel);

  $pageTitle = 'Fuso Parts Issuance';
    
  $partsTransactionReadAccess = $userModel->checkMenuItemAccessRights($user_id, 143, 'read');
  $partsTransactionCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 143, 'create');
  $partsTransactionWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 143, 'write');
  $partsTransactionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 143, 'delete');
  $partsTransactionDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 143, 'duplicate');

  if ($partsTransactionReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: parts-transaction.php');
      exit;
    }

    $partsTransactionID = $securityModel->decryptData($_GET['id']);

    $checkPartsTransactionExist = $partsTransactionModel->checkPartsTransactionExist($partsTransactionID);
    $total = $checkPartsTransactionExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $partsTransactionID = null;
  }

  $company = '3';

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
                    <li class="breadcrumb-item" aria-current="page"><a href="parts-transaction.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($partsTransactionID)){
                            echo '<li class="breadcrumb-item" id="parts-transaction-id">'. $partsTransactionID .'</li>';
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
        <input type="hidden" id="page-company" value="<?php echo $company ?>">
        <?php
          if($newRecord && $partsTransactionCreateAccess['total'] > 0){
            require_once('view/_parts_transaction_new.php');
          }
          else if(!empty($partsTransactionID) && $partsTransactionWriteAccess['total'] > 0){
            require_once('view/_parts_transaction_details.php');
          }
          else{
            require_once('view/_parts_transaction.php');
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
    <script src="./assets/js/pages/parts-transaction.js?v=<?php echo rand(); ?>"></script>
</body>

</html>