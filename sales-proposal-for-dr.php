<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/sales-proposal-model.php');
  require('model/customer-model.php');
  require('model/product-model.php');
  require('model/approving-officer-model.php');

  $pageTitle = 'Sales Proposal For DR';
  
  $salesProposalModel = new SalesProposalModel($databaseModel);
  $approvingOfficerModel = new ApprovingOfficerModel($databaseModel);
  $customerModel = new CustomerModel($databaseModel);
  $productModel = new ProductModel($databaseModel);
    
  $salesProposalForDRReadAccess = $userModel->checkMenuItemAccessRights($user_id, 77, 'read');
  $salesProposalForDRCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 77, 'create');

  if ($salesProposalForDRReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['sales_proposal_id'])){
    if(empty($_GET['sales_proposal_id'])){
      header('location: sales-proposal-for-dr.php');
      exit;
    }

    $salesProposalID = $securityModel->decryptData($_GET['sales_proposal_id']);

    $checkSalesProposalExist = $salesProposalModel->checkSalesProposalExist($salesProposalID);
    $total = $checkSalesProposalExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID); 
    $customerID = $salesProposalDetails['customer_id'];
    $comakerID = $salesProposalDetails['comaker_id'];
    $productID = $salesProposalDetails['product_id'] ?? null;
    $numberOfPayments = $salesProposalDetails['number_of_payments'] ?? null;
    $paymentFrequency = $salesProposalDetails['payment_frequency'] ?? null;
    $startDate = $salesProposalDetails['actual_start_date'] ?? null;
    $drNumber = $salesProposalDetails['dr_number'] ?? null;
    $salesProposalStatus = $salesProposalDetails['sales_proposal_status'] ?? null;
    $salesProposalStatusBadge = $salesProposalModel->getSalesProposalStatus($salesProposalStatus);

    $pricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($salesProposalID);
    $pnAmount = $pricingComputationDetails['pn_amount'] ?? 0;
    $downpayment = $pricingComputationDetails['downpayment'] ?? 0;
    $amountFinanced = $pricingComputationDetails['amount_financed'] ?? 0;
    $repaymentAmount = $pricingComputationDetails['repayment_amount'] ?? 0;

    $otherChargesDetails = $salesProposalModel->getSalesProposalOtherCharges($salesProposalID);
    $insurancePremium = $otherChargesDetails['insurance_premium'] ?? 0;
    $handlingFee = $otherChargesDetails['handling_fee'] ?? 0;
    $transferFee = $otherChargesDetails['transfer_fee'] ?? 0;
    $transactionFee = $otherChargesDetails['transaction_fee'] ?? 0;

    $renewalAmountDetails = $salesProposalModel->getSalesProposalRenewalAmount($salesProposalID);
    $registrationSecondYear = $renewalAmountDetails['registration_second_year'] ?? 0;
    $registrationThirdYear = $renewalAmountDetails['registration_third_year'] ?? 0;
    $registrationFourthYear = $renewalAmountDetails['registration_fourth_year'] ?? 0;
    $totalRenewalFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;

    $insurancePremiumSecondYear = $renewalAmountDetails['insurance_premium_second_year'] ?? 0;
    $insurancePremiumThirdYear = $renewalAmountDetails['insurance_premium_third_year'] ?? 0;
    $insurancePremiumFourthYear = $renewalAmountDetails['insurance_premium_fourth_year'] ?? 0;
    $totalInsuranceFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;

    $totalCharges = $insurancePremium + $handlingFee + $transferFee + $transactionFee + $totalRenewalFee + $totalInsuranceFee;

    $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);

    $customerDetails = $customerModel->getPersonalInformation($customerID);
    $customerName = $customerDetails['file_as'] ?? null;

    $comakerDetails = $customerModel->getPersonalInformation($comakerID);
    $comakerName = $comakerDetails['file_as'] ?? null;

    if(!empty($comakerName)){
        $comakerLabel = '<p class="text-center mb-0 text-white">N/A</p>';
    }
    else{
        $comakerLabel = '<p class="text-center mb-0">'. $comakerName .'</p>';
    }

    $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
    $customerAddress = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];

    if(!empty($comakerName)){
        $comakerAddressLabel = '<p class="text-center mb-0 text-white">N/A</p>';
    }
    else{
        $comakerAddressLabel = '<p class="text-center mb-0">'. $comakerName .'</p>';
    }

    $comakerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($comakerID);

    if(!empty($comakerPrimaryAddress['address'])){
      $comakerAddress = $comakerPrimaryAddress['address'] . ', ' . $comakerPrimaryAddress['city_name'] . ', ' . $comakerPrimaryAddress['state_name'] . ', ' . $comakerPrimaryAddress['country_name'];
    }
    else{
      $comakerAddress = '';
    }
    

    $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($customerID);
    $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
    $customerTelephone = !empty($customerContactInformation['telephone']) ? $customerContactInformation['telephone'] : '--';
    $customerEmail = !empty($customerContactInformation['email']) ? $customerContactInformation['email'] : '--';
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
                  <li class="breadcrumb-item">Delivery Receipt</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="sales-proposal-for-dr.php"><?php echo $pageTitle; ?></a></li>
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
            if(!empty($salesProposalID)){
                require_once('view/_sales_proposal_for_dr_details.php');
            }
            else{
                require_once('view/_sales_proposal_for_dr.php');
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
    <script src="./assets/js/pages/sales-proposal-for-dr.js?v=<?php echo rand(); ?>"></script>
</body>

</html>