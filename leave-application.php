<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/leave-application-model.php');
  require('model/leave-type-model.php');

  $leaveApplicationModel = new LeaveApplicationModel($databaseModel);
  $leaveTypeModel = new LeaveTypeModel($databaseModel);

  $pageTitle = 'Leave Application';
    
  $leaveApplicationReadAccess = $userModel->checkMenuItemAccessRights($user_id, 92, 'read');
  $leaveApplicationCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 92, 'create');
  $leaveApplicationWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 92, 'write');
  $leaveApplicationDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 92, 'delete');
  $leaveApplicationDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 92, 'duplicate');
  
  $leaveApplicationForApproval = $userModel->checkSystemActionAccessRights($user_id, 148);
  $leaveApplicationApprove = $userModel->checkSystemActionAccessRights($user_id, 149);
  $leaveApplicationReject = $userModel->checkSystemActionAccessRights($user_id, 150);
  $leaveApplicationCancel = $userModel->checkSystemActionAccessRights($user_id, 151);

  if ($leaveApplicationReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: leave-application.php');
      exit;
    }

    $leaveApplicationID = $securityModel->decryptData($_GET['id']);

    $checkLeaveApplicationExist = $leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
    $total = $checkLeaveApplicationExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $leaveApplicationDetails = $leaveApplicationModel->getLeaveApplication($leaveApplicationID);
    $status = $leaveApplicationDetails['status'];
    $leaveDate = $leaveApplicationDetails['leave_date'];
  }
  else{
    $leaveApplicationID = null;
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
                    <li class="breadcrumb-item">Employee</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="leave-application.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($leaveApplicationID)){
                            echo '<li class="breadcrumb-item" id="leave-application-id">'. $leaveApplicationID .'</li>';
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
          if($newRecord && $leaveApplicationCreateAccess['total'] > 0){
            require_once('view/_leave_application_new.php');
          }
          else if(!empty($leaveApplicationID) && $leaveApplicationWriteAccess['total'] > 0){
            require_once('view/_leave_application_details.php');
          }
          else{
            require_once('view/_leave_application.php');
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
    <script src="./assets/js/pages/leave-application.js?v=<?php echo rand(); ?>"></script>
</body>

</html>