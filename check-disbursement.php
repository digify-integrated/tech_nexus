<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/sales-proposal-model.php');
  require('model/disbursement-model.php');
  require('model/customer-model.php');
  require('model/product-model.php');
  require('model/company-model.php');
  require('model/chart-of-account-model.php');
  require('model/miscellaneous-client-model.php');
  require('model/system-setting-model.php');

  $salesProposalModel = new SalesProposalModel($databaseModel);
  $disbursementModel = new DisbursementModel($databaseModel);
  $productModel = new ProductModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $companyModel = new CompanyModel($databaseModel);
  $departmentModel = new DepartmentModel($databaseModel);
  $chartOfAccountModel = new ChartOfAccountModel($databaseModel);
  $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
  $systemSettingModel = new SystemSettingModel($databaseModel);

  $pageTitle = 'Check Voucher';
    
  $disbursementReadAccess = $userModel->checkMenuItemAccessRights($user_id, 128, 'read');
  $disbursementCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 128, 'create');
  $disbursementWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 128, 'write');
  $disbursementDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 128, 'delete');

  $postDisbursement = $userModel->checkSystemActionAccessRights($user_id, 187);
  $cancelDisbursement = $userModel->checkSystemActionAccessRights( $user_id, 188);
  $reverseDisbursement = $userModel->checkSystemActionAccessRights($user_id, 189);
  $replenishmentDisbursement = $userModel->checkSystemActionAccessRights($user_id, 191);

  if ($disbursementReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: disbursement.php');
      exit;
    }

    $disbursementID = $securityModel->decryptData($_GET['id']);

    $checkDisbursementExist = $disbursementModel->checkDisbursementExist($disbursementID);
    $total = $checkDisbursementExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
    $disbursementStatus = $disbursementDetails['disburse_status'];
    $transactionDate = $disbursementDetails['transaction_date'];
    $fund_source = $disbursementDetails['fund_source'];
  }
  else{
    $disbursementID = null;
  }

  $newRecord = isset($_GET['new']);
  $disbursementCategory = 'disbursement check';

  if($disbursementCategory === 'disbursement check'){
    $payableClient = '';
    $payableMisc = 'selected';
  }
  else{
    $payableClient = 'selected';
    $payableMisc = '';
  }

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
                    <li class="breadcrumb-item" aria-current="page"><a href="check-disbursement.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($disbursementID)){
                            echo '<li class="breadcrumb-item" id="disbursement-id">'. $disbursementID .'</li>';
                        }

                        if($newRecord){
                            echo '<li class="breadcrumb-item">New</li>';
                        }
                    ?>
                   <input type="hidden" id="disbursement_category" name="disbursement_category" value="<?php echo $disbursementCategory; ?>">
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
          if($newRecord && $disbursementCreateAccess['total'] > 0){
            require_once('view/_disbursement_new.php');
          }
          else if(!empty($disbursementID) && $disbursementWriteAccess['total'] > 0){
            require_once('view/_disbursement_details.php');
          }
          else{
            require_once('view/_check_disbursement.php');
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
    <script src="./assets/js/pages/disbursement.js?v=<?php echo rand(); ?>"></script>
</body>

</html>