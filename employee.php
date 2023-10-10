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
    require('model/menu-group-model.php');
    require('model/menu-item-model.php');
    require('model/security-model.php');
    require('model/system-model.php');
    require('model/interface-setting-model.php');
  
    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $menuGroupModel = new MenuGroupModel($databaseModel);
    $menuItemModel = new MenuItemModel($databaseModel);
    $employeeModel = new EmployeeModel($databaseModel);
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
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'Employee';
    
    $employeeReadAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'read');
    $employeeCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'create');
    $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
    $employeeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'delete');
    $employeeDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'duplicate');
    $contactInformationCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 49, 'create');

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
                    <li class="breadcrumb-item">Human Resources</li>
                    <li class="breadcrumb-item">Employees</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="employee.php">Employee</a></li>
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
                  <h2 class="mb-0">Employee</h2>
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
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/employee.js?v=<?php echo rand(); ?>"></script>
</body>

</html>