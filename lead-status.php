<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/lead-status-model.php');
  
  $databaseModel = new DatabaseModel();
  $systemModel = new SystemModel();
  $userModel = new UserModel($databaseModel, $systemModel);
  $menuGroupModel = new MenuGroupModel($databaseModel);
  $menuItemModel = new MenuItemModel($databaseModel);
  $leadStatusModel = new LeadStatusModel($databaseModel);
  $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
  $securityModel = new SecurityModel();

  $pageTitle = 'Lead Status';
    
  $leadStatusReadAccess = $userModel->checkMenuItemAccessRights($user_id, 192, 'read');
  $leadStatusCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 192, 'create');
  $leadStatusWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 192, 'write');
  $leadStatusDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 192, 'delete');
  $leadStatusDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 192, 'duplicate');

  if ($leadStatusReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: lead-status.php');
      exit;
    }

    $leadStatusID = $securityModel->decryptData($_GET['id']);

    $checkLeadStatusExist = $leadStatusModel->checkLeadStatusExist($leadStatusID);
    $total = $checkLeadStatusExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $leadStatusID = null;
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
                    <li class="breadcrumb-item">Technical Configurations</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="lead-status.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($leadStatusID)){
                            echo '<li class="breadcrumb-item" id="lead-status-id">'. $leadStatusID .'</li>';
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
          if($newRecord && $leadStatusCreateAccess['total'] > 0){
            require_once('view/_lead_status_new.php');
          }
          else if(!empty($leadStatusID) && $leadStatusWriteAccess['total'] > 0){
            require_once('view/_lead_status_details.php');
          }
          else{
            require_once('view/_lead_status.php');
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
    <script src="./assets/js/pages/lead-status.js?v=<?php echo rand(); ?>"></script>
</body>

</html>