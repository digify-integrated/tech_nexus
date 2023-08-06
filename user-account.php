<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/menu-item-model.php');
    require('model/security-model.php');
    require('model/system-model.php');
  
    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $menuItemModel = new MenuItemModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'User Account';
    
    $userAccountReadAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'read');
    $userAccountCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'create');
    $userAccountWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'write');
    $userAccountDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'delete');
    $userAccountDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'duplicate');
    $assignUserAccountToRole = $userModel->checkSystemActionAccessRights($user_id, 5);

    if ($userAccountReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user || !$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: user-account.php');
            exit;
        }

        $userAccountID = $securityModel->decryptData($_GET['id']);

        $checkUserExist = $userModel->checkUserExist($userAccountID);
        $total = $checkUserExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $userAccountID = null;
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
                  <li class="breadcrumb-item">Administration</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="user-account.php">User Account</a></li>
                  <?php
                    if(!empty($userAccountID)){
                      echo '<li class="breadcrumb-item" id="user-account-id">'. $userAccountID .'</li>';
                    }

                    if($newRecord){
                      echo '<li class="breadcrumb-item">New</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">User Account</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord && $userAccountCreateAccess['total'] > 0){
            require_once('view/_user_account_new.php');
          }
          else if(!empty($userAccountID && $roleWriteAccess['total'] > 0)){
            require_once('view/_user_account_details.php');
          }
          else{
            require_once('view/_user_account.php');
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
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/plugins/bootstrap-maxlength.min.js"></script>
    <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/user-account.js?v=<?php echo rand(); ?>"></script>
</body>

</html>