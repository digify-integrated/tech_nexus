<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/country-model.php');
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
    $countryModel = new CountryModel($databaseModel);
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'Country';
    
    $countryReadAccess = $userModel->checkMenuItemAccessRights($user_id, 22, 'read');
    $countryCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 22, 'create');
    $countryWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 22, 'write');
    $countryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 22, 'delete');
    $countryDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 22, 'duplicate');

    if ($countryReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user || !$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: country.php');
            exit;
        }

        $countryID = $securityModel->decryptData($_GET['id']);

        $checkCountryExist = $countryModel->checkCountryExist($countryID);
        $total = $checkCountryExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $countryID = null;
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
                  <li class="breadcrumb-item">Localization</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="country.php">Country</a></li>
                  <?php
                    if(!$newRecord && !empty($countryID)){
                      echo '<li class="breadcrumb-item" id="country-id">'. $countryID .'</li>';
                    }

                    if($newRecord){
                      echo '<li class="breadcrumb-item">New</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Country</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord && $countryCreateAccess['total'] > 0){
            require_once('view/_country_new.php');
          }
          else if(!empty($countryID) && $countryWriteAccess['total'] > 0){
            require_once('view/_country_details.php');
          }
          else{
            require_once('view/_country.php');
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
    <script src="./assets/js/pages/country.js?v=<?php echo rand(); ?>"></script>
</body>

</html>