<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/currency-model.php');
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
    $currencyModel = new CurrencyModel($databaseModel);
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $securityModel = new SecurityModel();

    $user = $userModel->getUserByID($user_id);

    $page_title = 'Currency';
    
    $currencyReadAccess = $userModel->checkMenuItemAccessRights($user_id, 23, 'read');
    $currencyCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 23, 'create');
    $currencyWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 23, 'write');
    $currencyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 23, 'delete');
    $currencyDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 23, 'duplicate');

    if ($currencyReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if (!$user || !$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: currency.php');
            exit;
        }

        $currencyID = $securityModel->decryptData($_GET['id']);

        $checkCurrencyExist = $currencyModel->checkCurrencyExist($currencyID);
        $total = $checkCurrencyExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $currencyID = null;
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
                  <li class="breadcrumb-item" aria-current="page"><a href="currency.php">Currency</a></li>
                  <?php
                    if(!$newRecord && !empty($currencyID)){
                      echo '<li class="breadcrumb-item" id="currency-id">'. $currencyID .'</li>';
                    }

                    if($newRecord){
                      echo '<li class="breadcrumb-item">New</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Currency</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($newRecord && $currencyCreateAccess['total'] > 0){
            require_once('view/_currency_new.php');
          }
          else if(!empty($currencyID) && $currencyWriteAccess['total'] > 0){
            require_once('view/_currency_details.php');
          }
          else{
            require_once('view/_currency.php');
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
    <script src="./assets/js/pages/currency.js?v=<?php echo rand(); ?>"></script>
</body>

</html>