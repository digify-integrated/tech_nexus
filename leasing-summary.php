<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/leasing-application-model.php');
  require('model/tenant-model.php');
  require('model/property-model.php');
  
  $leasingApplicationModel = new LeasingApplicationModel($databaseModel);
  $tenantModel = new TenantModel($databaseModel);
  $propertyModel = new PropertyModel($databaseModel);

  $pageTitle = 'Leasing Summary';
    
  $leasingRepaymentReadAccess = $userModel->checkMenuItemAccessRights($user_id, 84, 'read');
  $leasingRepaymentCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 84, 'create');
  $leasingRepaymentWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 84, 'write');
  $leasingRepaymentDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 84, 'delete');
  $closeLeasingApplication = $userModel->checkSystemActionAccessRights($user_id, 142);

  if ($leasingRepaymentReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: leasing-summary.php');
      exit;
    }

    $leasingApplicationID = $securityModel->decryptData($_GET['id']);
    $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);

    $checkLeasingApplicationExist = $leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
    $total = $checkLeasingApplicationExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingApplicationID);
    $applicationStatus = $leasingApplicationDetails['application_status'];
    $applicationStatusBadge = $leasingApplicationModel->getLeasingApplicationStatus($applicationStatus);
  }
  else{
    $leasingApplicationID = null;
  }

  if(isset($_GET['repayment_id'])){
    if(empty($_GET['repayment_id'])){
      header('location: leasing-summary.php');
      exit;
    }

    $leasingApplicationRepaymentID = $securityModel->decryptData($_GET['repayment_id']);
  }
  else{
    $leasingApplicationRepaymentID = null;
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
    <link rel="stylesheet" href="./assets/css/plugins/datepicker-bs5.min.css">
    <link rel="stylesheet" href="./assets/css/plugins/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="./assets/css/plugins/buttons.bootstrap5.min.css" />
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
                    <li class="breadcrumb-item">Leasing</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="leasing-summary.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!empty($leasingApplicationID) && empty($leasingApplicationRepaymentID)){
                          echo '<li class="breadcrumb-item" id="leasing-application-id">'. $leasingApplicationID .'</li>';
                        }

                        if(!empty($leasingApplicationID) && !empty($leasingApplicationRepaymentID)){
                          echo '<li class="breadcrumb-item" id="leasing-application-id">'. $leasingApplicationID .'</li>';
                          echo '<li class="breadcrumb-item" aria-current="page"><a href="leasing-summary.php?id='. $leasingApplicationIDEncrypted .'">Leasing Rental</a></li>';
                          echo '<li class="breadcrumb-item" id="leasing-application-repayment-id">'. $leasingApplicationRepaymentID .'</li>';
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
          if(!empty($leasingApplicationID) && empty($leasingApplicationRepaymentID)){
            require_once('view/_leasing_summary_details.php');
          }
          else if(!empty($leasingApplicationID) && !empty($leasingApplicationRepaymentID)){
            require_once('view/_leasing_summary_repayment_details.php');
          }
          else{
            require_once('view/_leasing_summary.php');
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="./assets/js/plugins/bootstrap-maxlength.min.js"></script>
    <script src="./assets/js/plugins/dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/buttons.colVis.min.js"></script>
    <script src="./assets/js/plugins/buttons.print.min.js"></script>
    <script src="./assets/js/plugins/pdfmake.min.js"></script>
    <script src="./assets/js/plugins/jszip.min.js"></script>
    <script src="./assets/js/plugins/dataTables.buttons.min.js"></script>
    <script src="./assets/js/plugins/vfs_fonts.js"></script>
    <script src="./assets/js/plugins/buttons.html5.min.js"></script>
    <script src="./assets/js/plugins/buttons.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/pages/leasing-application.js?v=<?php echo rand(); ?>"></script>
</body>

</html>