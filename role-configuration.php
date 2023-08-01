<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/role-model.php');
    require('model/menu-item-model.php');
    require('model/security-model.php');
    require('model/system-model.php');
  
    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $roleModel = new RoleModel($databaseModel);
    $menuItemModel = new MenuItemModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'Role Configuration';
    
    $roleReadAccess = $userModel->checkMenuItemAccessRights($user_id, 6, 'read');
    $roleCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 6, 'create');
    $roleWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 6, 'write');
    $roleDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 6, 'delete');
    $roleDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 6, 'duplicate');
    $updateMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 1);
    $updateSystemActionRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 3);
    $assignUserAccountToRole = $userModel->checkSystemActionAccessRights($user_id, 5);

    if ($roleReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user || !$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: role-configuration.php');
            exit;
        }

        $roleID = $securityModel->decryptData($_GET['id']);

        $checkRoleExist = $roleModel->checkRoleExist($roleID);
        $total = $checkRoleExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $roleID = null;
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
                  <li class="breadcrumb-item" aria-current="page"><a href="role-configuration.php">Role Configuration</a></li>
                  <?php
                    if(!empty($roleID)){
                      echo '<li class="breadcrumb-item" id="role-id">'. $roleID .'</li>';
                    }

                    if($newRecord){
                      echo '<li class="breadcrumb-item">New</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Role Configuration</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord && $roleCreateAccess['total'] > 0){
            require_once('view/_role_configuration_new.php');
          }
          else if(!empty($roleID && $roleWriteAccess['total'] > 0)){
            require_once('view/_role_configuration_details.php');
          }
          else{
            require_once('view/_role_configuration.php');
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
    <script src="./assets/js/pages/role-configuration.js?v=<?php echo rand(); ?>"></script>
</body>

</html>