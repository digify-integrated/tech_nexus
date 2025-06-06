<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/attendance-setting-model.php');
  require('model/branch-model.php');
  
  $branchModel = new BranchModel($databaseModel);
  $attendanceSettingModel = new AttendanceSettingModel($databaseModel);
  $transmittalReadAccess = $userModel->checkMenuItemAccessRights($user_id, 53, 'read');
  $documentReadAccess = $userModel->checkMenuItemAccessRights($user_id, 56, 'read');

  $pageTitle = 'Dashboard';

  $recordAttendance = $userModel->checkSystemActionAccessRights($user_id, 80);

  require('config/_interface_settings.php');
  require('config/_user_account_details.php');
  require('config/_attendance_record_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
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
          #require_once('view/_dashboard_attendance.php');

          $viewDashboardSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 181);
          $viewDashboardTravelForm = $userModel->checkSystemActionAccessRights($user_id, 182);
          $viewDashboardLeaveApprovalForm = $userModel->checkSystemActionAccessRights($user_id, 183);
          $viewDashboardEmployeeDailyStatusForm = $userModel->checkSystemActionAccessRights($user_id, 198);

          if($viewDashboardEmployeeDailyStatusForm['total'] > 0){
            require_once('view/_daily_employee_status_dashboard.php');
          }

          if($viewDashboardSalesProposal['total'] > 0){
            require_once('view/_sales_proposal_dashboard.php');
          }

          if($viewDashboardTravelForm['total'] > 0){
            require_once('view/_travel_form_dashboard.php');
          }

          if($viewDashboardLeaveApprovalForm['total'] > 0){
            require_once('view/_dashboard_leave_approval.php');
          }
          
          require_once('view/_transmittal_dashboard.php');
          require_once('view/_document_dashboard.php');
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
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/pages/dashboard.js?v=<?php echo rand(); ?>"></script>
</body>

</html>