<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/sales-proposal-model.php');
  require('model/pdc-management-model.php');
  require('model/customer-model.php');
  require('model/company-model.php');
  require('model/product-model.php');
  require('model/bank-model.php');
  require('model/leasing-application-model.php');

  $salesProposalModel = new SalesProposalModel($databaseModel);
  $pdcManagementModel = new PDCManagementModel($databaseModel);
  $productModel = new ProductModel($databaseModel);
  $companyModel = new CompanyModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $bankModel = new BankModel($databaseModel);
  $leasingApplicationModel = new LeasingApplicationModel($databaseModel);

  $pageTitle = 'Collection Report';
    
  $pdcManagementReadAccess = $userModel->checkMenuItemAccessRights($user_id, 115, 'read');
  $pdcManagementCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 115, 'create');
  $pdcManagementWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 115, 'write');
  $pdcManagementDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 115, 'delete');

  if ($pdcManagementReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: loan-collections.php');
      exit;
    }

    $pdcManagementID = $securityModel->decryptData($_GET['id']);

    $checkLoanCollectionExist = $pdcManagementModel->checkLoanCollectionExist($pdcManagementID);
    $total = $checkLoanCollectionExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $pdcManagementDetails = $pdcManagementModel->getPDCManagement($pdcManagementID);
    $collectionStatus = $pdcManagementDetails['collection_status'];
    $checkDate = date('m-d-Y', strtotime($pdcManagementDetails['check_date']));
  }
  else{
    $pdcManagementID = null;
  }

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
    <link rel="stylesheet" href="./assets/css/plugins/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="./assets/css/plugins/buttons.bootstrap5.min.css" />
    <?php include_once('config/_required_css.php'); ?>
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
                    <li class="breadcrumb-item">Sales Proposal</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="collection-report.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($pdcManagementID)){
                            echo '<li class="breadcrumb-item" id="loan-collection-id">'. $pdcManagementID .'</li>';
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
            require_once('view/_collection_report.php');
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
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/collection-report.js?v=<?php echo rand(); ?>"></script>
</body>

</html>