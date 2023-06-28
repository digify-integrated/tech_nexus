<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/security-model.php');
  
    $databaseModel = new DatabaseModel();
    $userModel = new UserModel($databaseModel);
    $securityModel = new SecurityModel();

    $page_title = 'Menu Group';
    
    $menuGroupReadAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'read');
    $menuGroupCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'create');
    $menuGroupWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'write');
    $menuGroupDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'delete');
    $menuGroupDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'duplicate');
    $menuItemCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 3, 'create');
    $menuItemWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 3, 'write');
    $assignMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 1);

    $user = $userModel->getUserByID($user_id);
    $newRecord = isset($_GET['new']);

    if ($menuGroupReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
      if(empty($_GET['id'])){
        header('location: menu-group.php');
        exit;
      }

      $id = $_GET['id'];
      $menu_group_id = $securityModel->decryptData($id);
    }
    else{
      $menu_group_id = null;
    }

    require('config/_interface_settings.php');
    require('config/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
    <?php include_once('config/_required_css.php'); ?>
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
                  <li class="breadcrumb-item"><a href="javascript: void(0)">User Interface</a></li>
                  <li class="breadcrumb-item" aria-current="page">Menu Groups</li>
                  <?php
                    if(!empty($menu_group_id)){
                      echo '<li class="breadcrumb-item" id="menu-group-id">'. $menu_group_id .'</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Menu Groups</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord){
            require_once('view/_menu_group_new.php');
          }
          else if(empty($menu_group_id)){
            require_once('view/_menu_group.php');
          }
          else{

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
    <script src="./assets/js/pages/menu-group.js?v=<?php echo rand(); ?>"></script>
</body>

</html>