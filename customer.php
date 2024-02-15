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

    $pageTitle = 'Customer';

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
    
    $customerReadAccess = $userModel->checkMenuItemAccessRights($user_id, 68, 'read');
    $customerCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 68, 'create');
    $customerWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 68, 'write');
    $customerDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 68, 'delete'); 

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
        $customerUniqueID = $customerDetails['customer_id'];
        $customerStatus = $customerModel->getCustomerStatus($customerDetails['contact_status']);

        $customerPersonalInformation = $customerModel->getPersonalInformation($customerID);
        $customerName = $customerPersonalInformation['file_as'];
        $customerImage = $systemModel->checkImage($customerPersonalInformation['contact_image'], 'profile');

        $nickname = $customerPersonalInformation['nickname'] ?? '--';
        $civilStatusID = $customerPersonalInformation['civil_status_id'];
        $genderID = $customerPersonalInformation['gender_id'];
        $religionID = $customerPersonalInformation['religion_id'];
        $bloodTypeID = $customerPersonalInformation['blood_type_id'];
        $birthday = $systemModel->checkDate('summary', $customerPersonalInformation['birthday'], '', 'F d, Y', '');
        $birthPlace = $customerPersonalInformation['birth_place'] ?? '--';
        $height = !empty($customerPersonalInformation['height']) ? $customerPersonalInformation['height'] . ' cm' : '--';
        $weight = !empty($customerPersonalInformation['weight']) ? $customerPersonalInformation['weight'] . ' kg' : '--';

        $civilStatusName = $civilStatusModel->getCivilStatus($civilStatusID)['civil_status_name'] ?? '--';
        $religionName = $religionModel->getReligion($religionID)['religion_name'] ?? '--';
        $bloodTypeName = $bloodTypeModel->getBloodType($bloodTypeID)['blood_type_name'] ?? '--';
        $genderName = $genderModel->getGender($genderID)['gender_name'] ?? '--';

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

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="false" data-pc-theme="<?php echo $darkLayout; ?>">
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
                    <li class="breadcrumb-item" aria-current="page"><a href="customer.php"><?php echo $pageTitle; ?></a></li>
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
                require_once('view/_customer.php');
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
    <script src="./assets/js/pages/customer.js?v=<?php echo rand(); ?>"></script>
</body>

</html>