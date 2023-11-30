<?php
  require('config/_required_php_file.php');
  require('model/notification-setting-model.php');
  
  $notificationSettingModel = new NotificationSettingModel($databaseModel);

  $user = $userModel->getUserByID($user_id);

  $pageTitle = 'Notification Setting';
    
  $notificationSettingReadAccess = $userModel->checkMenuItemAccessRights($user_id, 16, 'read');
  $notificationSettingCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 16, 'create');
  $notificationSettingWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 16, 'write');
  $notificationSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 16, 'delete');
  $notificationSettingDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 16, 'duplicate');

  if ($notificationSettingReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if (!$user || !$user['is_active']) {
    header('location: logout.php?logout');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: notification-setting.php');
      exit;
    }

    $notificationSettingID = $securityModel->decryptData($_GET['id']);

    $checkNotificationSettingExist = $notificationSettingModel->checkNotificationSettingExist($notificationSettingID);
    $total = $checkNotificationSettingExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $getNotificationSetting = $notificationSettingModel->getNotificationSetting($notificationSettingID);
    $systemNotification = $getNotificationSetting['system_notification'];
    $emailNotification = $getNotificationSetting['email_notification'];
    $smsNotification = $getNotificationSetting['sms_notification'];
  }
  else{
    $notificationSettingID = null;
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
                  <li class="breadcrumb-item">Technical</li>
                  <li class="breadcrumb-item">Configurations</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="notification-setting.php"><?php echo $pageTitle; ?></a></li>
                  <?php
                    if(!$newRecord && !empty($notificationSettingID)){
                      echo '<li class="breadcrumb-item" id="notification-setting-id">'. $notificationSettingID .'</li>';
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
          if($newRecord && $notificationSettingCreateAccess['total'] > 0){
            require_once('view/_notification_setting_new.php');
          }
          else if(!empty($notificationSettingID) && $notificationSettingWriteAccess['total'] > 0){
            require_once('view/_notification_setting_details.php');
          }
          else{
            require_once('view/_notification_setting.php');
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
    <script src="./assets/js/plugins/tinymce/tinymce.min.js"></script>
    <script src="./assets/js/plugins/bootstrap-maxlength.min.js"></script>
    <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/pages/notification-setting.js?v=<?php echo rand(); ?>"></script>
</body>

</html>