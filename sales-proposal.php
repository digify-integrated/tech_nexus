<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/sales-proposal-model.php');
  require('model/customer-model.php');
  require('model/product-model.php');
  require('model/approving-officer-model.php');
  require('model/id-type-model.php');

  $pageTitle = 'Sales Proposal';
  
  $salesProposalModel = new SalesProposalModel($databaseModel);
  $approvingOfficerModel = new ApprovingOfficerModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $productModel = new ProductModel($databaseModel);
  $idTypeModel = new IDTypeModel($databaseModel);

  require('model/application-source-model.php');
  $applicationSourceModel = new ApplicationSourceModel($databaseModel);
    
  $viewSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 116);
  $addSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 117);
  $updateSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 118);
  $deleteSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 119);
  $forInitialApproval = $userModel->checkSystemActionAccessRights($user_id, 122);
  $cancelSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 123);
  $initialApproveSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 124);
  $forCISalesProposal = $userModel->checkSystemActionAccessRights($user_id, 125);
  $proceedSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 126);
  $rejectSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 127);
  $setToDraftSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 129);
  $viewSalesProposalProductCost = $userModel->checkSystemActionAccessRights($user_id, 130);
  $tagCIAsComplete = $userModel->checkSystemActionAccessRights($user_id, 135);
  $tagSalesProposalForDR = $userModel->checkSystemActionAccessRights($user_id, 134);

  if ($viewSalesProposal['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['customer'])){
    $customerID = $securityModel->decryptData($_GET['customer']);

    $checkCustomerExist = $customerModel->checkCustomerExist($customerID);
    $total = $checkCustomerExist['total'] ?? 0;
    
    $customerDetails = $customerModel->getPersonalInformation($customerID);
    $customerName = $customerDetails['file_as'] ?? null;

    $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
    $customerAddress = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];

    $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($customerID);
    $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
    $customerTelephone = !empty($customerContactInformation['telephone']) ? $customerContactInformation['telephone'] : '--';
    $customerEmail = !empty($customerContactInformation['email']) ? $customerContactInformation['email'] : '--';

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $customerID = '';
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: seach-customer.php');
      exit;
    }

    $salesProposalID = $securityModel->decryptData($_GET['id']);

    $checkSalesProposalExist = $salesProposalModel->checkSalesProposalExist($salesProposalID);
    $total = $checkSalesProposalExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);  
    $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];
    $initialApprovingOfficer = $salesProposalDetails['initial_approving_officer'];
    $finalApprovingOfficer = $salesProposalDetails['final_approving_officer'];
    $creditAdvice = $salesProposalDetails['credit_advice'];
    $clientConfirmation = $salesProposalDetails['client_confirmation'];
    $transactionType = $salesProposalDetails['transaction_type'];
    $ciStatus = $salesProposalDetails['ci_status'];
    $outgoingChecklist = $salesProposalDetails['outgoing_checklist'];
    $unitImage = $salesProposalDetails['unit_image'];
    $productType = $salesProposalDetails['product_type'];
    $initialApprovalDate = $systemModel->checkDate('empty', $salesProposalDetails['initial_approval_date'], '', 'm/d/Y h:i:s a', '');
    $approvalDate = $systemModel->checkDate('empty', $salesProposalDetails['approval_date'], '', 'm/d/Y h:i:s a', '');
    $forCIDate = $systemModel->checkDate('empty', $salesProposalDetails['for_ci_date'], '', 'm/d/Y h:i:s a', '');

    if(!empty($salesProposalDetails['created_date'])){
      $createdDate = $systemModel->checkDate('empty', $salesProposalDetails['created_date'], '', 'm/d/Y h:i:s a', '');
    }
    else{
      $createdDate = $systemModel->checkDate('empty', date('m/d/Y h:i:s a'), '', 'm/d/Y h:i:s a', '');
    }
    
    $salesProposalStatusBadge = $salesProposalModel->getSalesProposalStatus($salesProposalStatus);
    $viewSalesProposalProductCost = $userModel->checkSystemActionAccessRights($user_id, 130);
  }
  else{
    $salesProposalID = null;
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
                  <li class="breadcrumb-item">Sales Proposal</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="sales-proposal.php?customer=<?php echo $securityModel->encryptData($customerID); ?>"><?php echo $pageTitle; ?></a></li>
                  <?php
                    if(!$newRecord && !empty($salesProposalID)){
                      echo '<li class="breadcrumb-item" id="sales-proposal-id">'. $salesProposalID .'</li>';
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
          if($newRecord && !empty($customerID) && $addSalesProposal['total'] > 0){
            require_once('view/_sales_proposal_new.php');
          }
          else if(!empty($salesProposalID) && !empty($customerID) && $updateSalesProposal['total'] > 0){
            require_once('view/_sales_proposal_details.php');
          }
          else{
            require_once('view/_sales_proposal.php');
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
    <script src="./assets/js/plugins/imask.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>

    <?php
        $scriptLink = 'sales-proposal.js';

        if($newRecord && !empty($customerID) && $addSalesProposal['total'] > 0){
            $scriptLink = 'sales-proposal-new.js';
        }

        echo '<script src="./assets/js/pages/'. $scriptLink .'?v=' . rand() .'"></script>';
    ?>
</body>

</html>