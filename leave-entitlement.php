<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/leave-entitlement-model.php');
  require('model/leave-type-model.php');

  $leaveEntitlementModel = new LeaveEntitlementModel($databaseModel);
  $leaveTypeModel = new LeaveTypeModel($databaseModel);

  $pageTitle = 'Leave Entitlement';
    
  $leaveEntitlementReadAccess = $userModel->checkMenuItemAccessRights($user_id, 90, 'read');
  $leaveEntitlementCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 90, 'create');
  $leaveEntitlementWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 90, 'write');
  $leaveEntitlementDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 90, 'delete');
  $leaveEntitlementDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 90, 'duplicate');

  if ($leaveEntitlementReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: leave-entitlement.php');
      exit;
    }

    $leaveEntitlementID = $securityModel->decryptData($_GET['id']);

    $checkLeaveEntitlementExist = $leaveEntitlementModel->checkLeaveEntitlementExist($leaveEntitlementID);
    $total = $checkLeaveEntitlementExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $leaveEntitlementDetails = $leaveEntitlementModel->getLeaveEntitlement($leaveEntitlementID);
    $entitleAmount = $leaveEntitlementDetails['entitlement_amount'];
    $remainingEntitlement = $leaveEntitlementDetails['remaining_entitlement'];
  }
  else{
    $leaveEntitlementID = null;
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
                    <li class="breadcrumb-item">Human Resources</li>
                    <li class="breadcrumb-item">Configurations</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="leave-entitlement.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($leaveEntitlementID)){
                            echo '<li class="breadcrumb-item" id="leave-entitlement-id">'. $leaveEntitlementID .'</li>';
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
          if($newRecord && $leaveEntitlementCreateAccess['total'] > 0){
            require_once('view/_leave_entitlement_new.php');
          }
          else if(!empty($leaveEntitlementID) && $leaveEntitlementWriteAccess['total'] > 0){
            require_once('view/_leave_entitlement_details.php');
          }
          else{
            require_once('view/_leave_entitlement.php');
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
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/leave-entitlement.js?v=<?php echo rand(); ?>"></script>
</body>

</html>