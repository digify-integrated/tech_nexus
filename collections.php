<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/sales-proposal-model.php');
  require('model/collections-model.php');
  require('model/customer-model.php');
  require('model/product-model.php');
  require('model/company-model.php');
  require('model/bank-model.php');
  require('model/miscellaneous-client-model.php');
  require('model/leasing-application-model.php');

  $salesProposalModel = new SalesProposalModel($databaseModel);
  $collectionsModel = new CollectionsModel($databaseModel);
  $productModel = new ProductModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $companyModel = new CompanyModel($databaseModel);
  $bankModel = new BankModel($databaseModel);
  $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
  $leasingApplicationModel = new LeasingApplicationModel($databaseModel);


  $pageTitle = 'Collections';
    
  $collectionsReadAccess = $userModel->checkMenuItemAccessRights($user_id, 97, 'read');
  $collectionsCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 97, 'create');
  $collectionsWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 97, 'write');
  $collectionsDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 97, 'delete');

  $tagCollectionAsCleared = $userModel->checkSystemActionAccessRights($user_id, 154);
  $tagCollectionOnHold = $userModel->checkSystemActionAccessRights($user_id, 155);
  $tagCollectionAsReversed = $userModel->checkSystemActionAccessRights($user_id, 156);
  $tagCollectionAsCancelled = $userModel->checkSystemActionAccessRights($user_id, 157);
  $tagCollectionAsRedeposit = $userModel->checkSystemActionAccessRights($user_id, 158);
  $tagCollectionAsForDeposit = $userModel->checkSystemActionAccessRights($user_id, 159);
  $tagCollectionAsPulledOut = $userModel->checkSystemActionAccessRights($user_id, 160);
  $tagClearedPDCAsReturned = $userModel->checkSystemActionAccessRights($user_id, 161);
  $tagCollectionAsDeposited = $userModel->checkSystemActionAccessRights($user_id, 162);
  $massTagPDCAsCancelled = $userModel->checkSystemActionAccessRights($user_id, 163);
  $massTagPDCAsReturned = $userModel->checkSystemActionAccessRights($user_id, 164);

  if ($collectionsReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: collections.php');
      exit;
    }

    $collectionsID = $securityModel->decryptData($_GET['id']);

    $checkLoanCollectionExist = $collectionsModel->checkLoanCollectionExist($collectionsID);
    $total = $checkLoanCollectionExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $collectionsDetails = $collectionsModel->getCollections($collectionsID);
    $collectionStatus = $collectionsDetails['collection_status'];
    $transactionDate = $collectionsDetails['transaction_date'];
    $pdc_type = $collectionsDetails['pdc_type'];
  }
  else{
    $collectionsID = null;
  }

  $newRecord = isset($_GET['new']);
  $paymentAdvice = 'No';

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
                    <li class="breadcrumb-item">Operations</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="collections.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($collectionsID)){
                            echo '<li class="breadcrumb-item" id="loan-collection-id">'. $collectionsID .'</li>';
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
          if($newRecord && $collectionsCreateAccess['total'] > 0){
            require_once('view/_collections_new.php');
          }
          else if(!empty($collectionsID) && $collectionsWriteAccess['total'] > 0){
            require_once('view/_collections_details.php');
          }
          else{
            require_once('view/_collections.php');
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
    <script src="./assets/js/pages/collections.js?v=<?php echo rand(); ?>"></script>
</body>

</html>