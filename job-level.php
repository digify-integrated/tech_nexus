<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/job-level-model.php');
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
    $jobLevelModel = new JobLevelModel($databaseModel);
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'Job Level';
    
    $jobLevelReadAccess = $userModel->checkMenuItemAccessRights($user_id, 29, 'read');
    $jobLevelCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 29, 'create');
    $jobLevelWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 29, 'write');
    $jobLevelDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 29, 'delete');
    $jobLevelDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 29, 'duplicate');

    if ($jobLevelReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user || !$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: job-level.php');
            exit;
        }

        $jobLevelID = $securityModel->decryptData($_GET['id']);

        $checkJobLevelExist = $jobLevelModel->checkJobLevelExist($jobLevelID);
        $total = $checkJobLevelExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $jobLevelID = null;
    }

    $newRecord = isset($_GET['new']);

    require('config/_interface_settings.php');
    require('config/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
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
                    <li class="breadcrumb-item">Configurations</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="job-level.php">Job Level</a></li>
                    <?php
                        if(!$newRecord && !empty($jobLevelID)){
                            echo '<li class="breadcrumb-item" id="job-level-id">'. $jobLevelID .'</li>';
                        }

                        if($newRecord){
                            echo '<li class="breadcrumb-item">New</li>';
                        }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Job Level</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord && $jobLevelCreateAccess['total'] > 0){
            require_once('view/_job_level_new.php');
          }
          else if(!empty($jobLevelID) && $jobLevelWriteAccess['total'] > 0){
            require_once('view/_job_level_details.php');
          }
          else{
            require_once('view/_job_level.php');
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
    <script src="./assets/js/pages/job-level.js?v=<?php echo rand(); ?>"></script>
</body>

</html>