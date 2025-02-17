<?php
    require('config/_required_php_file.php');
    require('config/_check_user_active.php');
    require('model/customer-model.php');
    require('model/gender-model.php');
    require('model/civil-status-model.php');
    require('model/religion-model.php');
    require('model/blood-type-model.php');
    require('model/contact-information-type-model.php');
    require('model/address-type-model.php');
    require('model/city-model.php');
    require('model/id-type-model.php');
    require('model/educational-stage-model.php');
    require('model/relation-model.php');
    require('model/language-model.php');
    require('model/language-proficiency-model.php');
    require('model/bank-model.php');
    require('model/bank-account-type-model.php');

    $pageTitle = 'Search Customer';

    $customerModel = new CustomerModel($databaseModel);
    $genderModel = new GenderModel($databaseModel);
    $civilStatusModel = new CivilStatusModel($databaseModel);
    $religionModel = new ReligionModel($databaseModel);
    $bloodTypeModel = new BloodTypeModel($databaseModel);
    $contactInformationTypeModel = new ContactInformationTypeModel($databaseModel);
    $addressTypeModel = new AddressTypeModel($databaseModel);
    $cityModel = new CityModel($databaseModel);
    $idTypeModel = new IDTypeModel($databaseModel);
    $educationalStageModel = new EducationalStageModel($databaseModel);
    $relationModel = new RelationModel($databaseModel);
    $languageModel = new LanguageModel($databaseModel);
    $languageProficiencyModel = new LanguageProficiencyModel($databaseModel);
    $bankModel = new BankModel($databaseModel);
    $bankAccountTypeModel = new BankAccountTypeModel($databaseModel);
    
    $customerReadAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'read');
    $customerCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'create');
    $customerWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'write');
    $customerDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'delete');

    $addCustomerContactInformation = $userModel->checkSystemActionAccessRights($user_id, 99);
    $updateCustomerContactInformation = $userModel->checkSystemActionAccessRights($user_id, 100);
    $addCustomerAddress = $userModel->checkSystemActionAccessRights($user_id, 103);
    $updateCustomerAddress = $userModel->checkSystemActionAccessRights($user_id, 104);
    $addCustomerIdentification = $userModel->checkSystemActionAccessRights($user_id, 107);
    $updateCustomerIdentification = $userModel->checkSystemActionAccessRights($user_id, 108);
    $addCustomerFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 111);
    $updateCustomerFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 112);
    $changeCustomerStatusToActive = $userModel->checkSystemActionAccessRights($user_id, 114);
    $changeCustomerStatusToForUpdating = $userModel->checkSystemActionAccessRights($user_id, 115);
    $viewSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 116);
    $addCustomerComaker = $userModel->checkSystemActionAccessRights($user_id, 120);

    if ($customerReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: customer.php');
            exit;
        }

        $customerID = $securityModel->decryptData($_GET['id']);

        $checkCustomerExist = $customerModel->checkCustomerExist($customerID);
        $total = $checkCustomerExist['total'] ?? 0;

        $customerDetails = $customerModel->getCustomer($customerID);
        $customerStatus = $customerDetails['contact_status'];
        $customerStatusBadge = $customerModel->getCustomerStatus($customerStatus);

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $customerID = null;
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
    <link rel="stylesheet" href="./assets/css/plugins/bootstrap-slider.min.css">
    <?php include_once('config/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/dataTables.bootstrap5.min.css">
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="false">
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
                    <li class="breadcrumb-item" aria-current="page" id="search-customer-tag"><a href="search-customer.php"><?php echo $pageTitle; ?></a></li>
                    
                    <?php
                        if(!$newRecord && !empty($customerID)){
                            echo '<li class="breadcrumb-item" id="customer-id">'. $customerID .'</li>';
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
            if($newRecord && $customerCreateAccess['total'] > 0){
                require_once('view/_customer_new.php');
            }
            else if(!empty($customerID) && $customerWriteAccess['total'] > 0){
                require_once('view/_customer_details.php');
            }
            else{
                require_once('view/_search_customer.php');
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
    <script src="./assets/js/plugins/bootstrap-slider.min.js"></script>
    <script src="./assets/js/plugins/qr/qrcode.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/search-customer.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/customer.js?v=<?php echo rand(); ?>"></script>
</body>

</html>