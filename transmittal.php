<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/transmittal-model.php');
  
  $transmittalModel = new TransmittalModel($databaseModel);

  $pageTitle = 'Transmittal';
    
  $transmittalReadAccess = $userModel->checkMenuItemAccessRights($user_id, 53, 'read');
  $transmittalCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 53, 'create');
  $transmittalWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 53, 'write');
  $transmittalDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 53, 'delete');
  
  $transmitTransmittal = $userModel->checkSystemActionAccessRights($user_id, 83);
  $receiveTransmittal = $userModel->checkSystemActionAccessRights($user_id, 84);
  $retransmitTransmittal = $userModel->checkSystemActionAccessRights($user_id, 85);
  $fileTransmittal = $userModel->checkSystemActionAccessRights($user_id, 86);
  $cancelTransmittal = $userModel->checkSystemActionAccessRights($user_id, 87);

  if ($transmittalReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: transmittal.php');
      exit;
    }

    $transmittalID = $securityModel->decryptData($_GET['id']);

    $checkTransmittalExist = $transmittalModel->checkTransmittalExist($transmittalID);
    $total = $checkTransmittalExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }

    $employmentDetails = $employeeModel->getEmploymentInformation($contact_id);
    $contactDepartment = $employmentDetails['department_id'];

    $transmittalDetails = $transmittalModel->getTransmittal($transmittalID);
    $transmitterID = $transmittalDetails['transmitter_id'];
    $receiverID = $transmittalDetails['receiver_id'];
    $receiverDepartment = $transmittalDetails['receiver_department'];
    $transmittalStatus = $transmittalDetails['transmittal_status'];
  }
  else{
    $transmittalID = null;
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
                    <li class="breadcrumb-item" aria-current="page"><a href="transmittal.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($transmittalID)){
                            echo '<li class="breadcrumb-item" id="transmittal-id">'. $transmittalID .'</li>';
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
          if($newRecord && $transmittalCreateAccess['total'] > 0){
            require_once('view/_transmittal_new.php');
          }
          else if(!empty($transmittalID) && $transmittalWriteAccess['total'] > 0){
            require_once('view/_transmittal_details.php');
          }
          else{
            require_once('view/_transmittal.php');
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
    <script src="./assets/js/pages/transmittal.js?v=<?php echo rand(); ?>"></script>
</body>

</html>