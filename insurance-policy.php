<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/insurance-policy-model.php');
  require('model/customer-model.php');
  require('model/insurance-type-model.php');
  require('model/insurance-provider-model.php');
  require('model/miscellaneous-client-model.php');
  require('model/sales-proposal-model.php');
  
  $databaseModel = new DatabaseModel();
  $systemModel = new SystemModel();
  $userModel = new UserModel($databaseModel, $systemModel);
  $menuGroupModel = new MenuGroupModel($databaseModel);
  $menuItemModel = new MenuItemModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $insuranceProviderModel = new InsuranceProviderModel($databaseModel);
  $insurancePolicyModel = new InsurancePolicyModel($databaseModel);
  $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
  $insuranceTypeModel = new InsuranceTypeModel($databaseModel);
  $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
  $salesProposalModel = new SalesProposalModel($databaseModel);
  $securityModel = new SecurityModel();

  $pageTitle = 'Insurance Policy';
    
  $insurancePolicyReadAccess = $userModel->checkMenuItemAccessRights($user_id, 200, 'read');
  $insurancePolicyCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 200, 'create');
  $insurancePolicyWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 200, 'write');
  $insurancePolicyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 200, 'delete');
  $insurancePolicyDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 200, 'duplicate');

  if ($insurancePolicyReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: insurance-policy.php');
      exit;
    }

    $insurancePolicyID = $securityModel->decryptData($_GET['id']);

    $checkInsurancePolicyExist = $insurancePolicyModel->checkInsurancePolicyExist($insurancePolicyID);
    $total = $checkInsurancePolicyExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $insurancePolicyID = null;
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
                    <li class="breadcrumb-item">Insurance</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="insurance-policy.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($insurancePolicyID)){
                            echo '<li class="breadcrumb-item" id="insurance-policy-id">'. $insurancePolicyID .'</li>';
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
          if($newRecord && $insurancePolicyCreateAccess['total'] > 0){
            require_once('view/_insurance_policy_new.php');
          }
          else if(!empty($insurancePolicyID) && $insurancePolicyWriteAccess['total'] > 0){
            require_once('view/_insurance_policy_details.php');
          }
          else{
            require_once('view/_insurance_policy.php');
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
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/pages/insurance-policy.js?v=<?php echo rand(); ?>"></script>
</body>

</html>