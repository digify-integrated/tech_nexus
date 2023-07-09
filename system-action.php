<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/system-action-model.php');
    require('model/menu-item-model.php');
    require('model/security-model.php');
    require('model/system-model.php');
  
    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $menuItemModel = new MenuItemModel($databaseModel);
    $systemActionModel = new SystemActionModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'System Action';
    
    $systemActionReadAccess = $userModel->checkMenuItemAccessRights($user_id, 5, 'read');
    $systemActionCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 5, 'create');
    $systemActionWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 5, 'write');
    $systemActionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 5, 'delete');
    $systemActionDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 5, 'duplicate');
    $assignSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 2);

    if ($systemActionReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: system-action.php');
            exit;
        }

        $systemActionID = $securityModel->decryptData($_GET['id']);

        $checkSystemActionExist = $systemActionModel->checkSystemActionExist($systemActionID);
        $total = $checkSystemActionExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $systemActionID = null;
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
                  <li class="breadcrumb-item">Administration</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="system-action.php">System Action</a></li>
                  <?php
                    if(!empty($systemActionID)){
                      echo '<li class="breadcrumb-item" id="system-action-id">'. $systemActionID .'</li>';
                    }

                    if($newRecord){
                      echo '<li class="breadcrumb-item">New</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">System Action</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord){
            require_once('view/_system_action_new.php');
          }
          else if(empty($systemActionID)){
            require_once('view/_system_action.php');
          }
          else{
            require_once('view/_system_action_details.php');
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
    <script src="./assets/js/pages/system-action.js?v=<?php echo rand(); ?>"></script>
</body>

</html>