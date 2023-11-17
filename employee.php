<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/employee-model.php');
    require('model/company-model.php');
    require('model/department-model.php');
    require('model/job-position-model.php');
    require('model/job-level-model.php');
    require('model/branch-model.php');
    require('model/employee-type-model.php');
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
    require('model/menu-group-model.php');
    require('model/menu-item-model.php');
    require('model/security-model.php');
    require('model/system-model.php');
    require('model/system-setting-model.php');
    require('model/interface-setting-model.php');
  
    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $systemSettingModel = new SystemSettingModel($databaseModel);
    $menuGroupModel = new MenuGroupModel($databaseModel);
    $menuItemModel = new MenuItemModel($databaseModel);
    $employeeModel = new EmployeeModel($databaseModel, $systemSettingModel);
    $companyModel = new CompanyModel($databaseModel);
    $departmentModel = new DepartmentModel($databaseModel);
    $jobPositionModel = new JobPositionModel($databaseModel);
    $jobLevelModel = new JobLevelModel($databaseModel);
    $branchModel = new BranchModel($databaseModel);
    $employeeTypeModel = new EmployeeTypeModel($databaseModel);
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
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $pageTitle = 'Employee';
    
    $employeeReadAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'read');
    $employeeCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'create');
    $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
    $employeeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'delete'); 
    $employeeDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'duplicate');
    $addEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 32);
    $updateEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 33);
    $addEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 36);
    $updateEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 37);
    $addEmployeeIdentification = $userModel->checkSystemActionAccessRights($user_id, 40);
    $updateEmployeeIdentification = $userModel->checkSystemActionAccessRights($user_id, 41);
    $addEmployeeEducationalBackground = $userModel->checkSystemActionAccessRights($user_id, 44);
    $updateEmployeeEducationalBackground = $userModel->checkSystemActionAccessRights($user_id, 45);
    $addEmployeeFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 47);
    $updateEmployeeFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 48);
    $addEmployeeEmergencyContact = $userModel->checkSystemActionAccessRights($user_id, 50);
    $updateEmployeeEmergencyContact = $userModel->checkSystemActionAccessRights($user_id, 51);
    $addEmployeeTrainingsSeminars = $userModel->checkSystemActionAccessRights($user_id, 53);
    $updateEmployeeTrainingsSeminars = $userModel->checkSystemActionAccessRights($user_id, 54);
    $addEmployeeSkills = $userModel->checkSystemActionAccessRights($user_id, 56);
    $updateEmployeeSkills = $userModel->checkSystemActionAccessRights($user_id, 57);
    $addEmployeeTalents = $userModel->checkSystemActionAccessRights($user_id, 59);
    $updateEmployeeTalents = $userModel->checkSystemActionAccessRights($user_id, 60);
    $addEmployeeHobby = $userModel->checkSystemActionAccessRights($user_id, 62);
    $updateEmployeeHobby = $userModel->checkSystemActionAccessRights($user_id, 63);
    $addEmploymentHistory = $userModel->checkSystemActionAccessRights($user_id, 65);
    $updateEmploymentHistory = $userModel->checkSystemActionAccessRights($user_id, 66);
    $addEmployeeLicense = $userModel->checkSystemActionAccessRights($user_id, 68);
    $updateEmployeeLicense = $userModel->checkSystemActionAccessRights($user_id, 69);
    $addEmployeeLanguage = $userModel->checkSystemActionAccessRights($user_id, 71);
    $updateEmployeeLanguage = $userModel->checkSystemActionAccessRights($user_id, 72);
    $addEmployeeBank = $userModel->checkSystemActionAccessRights($user_id, 74);
    $updateEmployeeBank = $userModel->checkSystemActionAccessRights($user_id, 75);

    if ($employeeReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user || !$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: employee.php');
            exit;
        }

        $employeeID = $securityModel->decryptData($_GET['id']);

        $checkEmployeeExist = $employeeModel->checkEmployeeExist($employeeID);
        $total = $checkEmployeeExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $employeeID = null;
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
                    <li class="breadcrumb-item">Human Resources</li>
                    <li class="breadcrumb-item">Employees</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="employee.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($employeeID)){
                            echo '<li class="breadcrumb-item" id="employee-id">'. $employeeID .'</li>';
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
            if($newRecord && $employeeCreateAccess['total'] > 0){
                require_once('view/_employee_new.php');
            }
            else if(!empty($employeeID) && $employeeWriteAccess['total'] > 0){
                require_once('view/_employee_details.php');
            }
            else{
                require_once('view/_employee.php');
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
    <script src="./assets/js/pages/employee.js?v=<?php echo rand(); ?>"></script>
</body>

</html>