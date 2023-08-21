<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/upload-setting-model.php');
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
    $uploadSettingModel = new UploadSettingModel($databaseModel);
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'Upload Setting';
    
    $uploadSettingReadAccess = $userModel->checkMenuItemAccessRights($user_id, 12, 'read');
    $uploadSettingCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 12, 'create');
    $uploadSettingWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 12, 'write');
    $uploadSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 12, 'delete');
    $uploadSettingDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 12, 'duplicate');
    $addFileExtensionToUploadSetting = $userModel->checkSystemActionAccessRights($user_id, 15);

    if ($uploadSettingReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user || !$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: upload-setting.php');
            exit;
        }

        $uploadSettingID = $securityModel->decryptData($_GET['id']);

        $checkUploadSettingExist = $uploadSettingModel->checkUploadSettingExist($uploadSettingID);
        $total = $checkUploadSettingExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $uploadSettingID = null;
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
                  <li class="breadcrumb-item">Configurations</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="upload-setting.php">Upload Setting</a></li>
                  <?php
                    if(!empty($uploadSettingID)){
                      echo '<li class="breadcrumb-item" id="upload-setting-id">'. $uploadSettingID .'</li>';
                    }

                    if($newRecord){
                      echo '<li class="breadcrumb-item">New</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Upload Setting</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord && $uploadSettingCreateAccess['total'] > 0){
            require_once('view/_upload_setting_new.php');
          }
          else if(!empty($uploadSettingID) && $uploadSettingWriteAccess['total'] > 0){
            require_once('view/_upload_setting_details.php');
          }
          else{
            require_once('view/_upload_setting.php');
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
    <script src="./assets/js/pages/upload-setting.js?v=<?php echo rand(); ?>"></script>
</body>

</html>