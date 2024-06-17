<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/parts-inquiry-model.php');
    
  $partsInquiryModel = new PartsInquiryModel($databaseModel);

  $pageTitle = 'Upload Setting';
  
  $partsInquiryReadAccess = $userModel->checkMenuItemAccessRights($user_id, 94, 'read');
  $partsInquiryCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 94, 'create');
  $partsInquiryWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 94, 'write');
  $partsInquiryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 94, 'delete');
  $partsInquiryDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 94, 'duplicate');

  if ($partsInquiryReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: parts-inquiry.php');
      exit;
    }

    $partsInquiryID = $securityModel->decryptData($_GET['id']);

    $checkpartsInquiryExist = $partsInquiryModel->checkpartsInquiryExist($partsInquiryID);
    $total = $checkpartsInquiryExist['total'] ?? 0;

    if($total == 0){
       // header('location: 404.php');
        exit;
    }
  }
  else{
    $partsInquiryID = null;
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
                  <li class="breadcrumb-item">Employee</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="parts-inquiry.php"><?php echo $pageTitle; ?></a></li>
                  <?php
                    if(!$newRecord && !empty($partsInquiryID)){
                      echo '<li class="breadcrumb-item" id="parts-inquiry-id">'. $partsInquiryID .'</li>';
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
          if($newRecord && $partsInquiryCreateAccess['total'] > 0){
            require_once('view/_parts_inquiry_new.php');
          }
          else if(!empty($partsInquiryID) && $partsInquiryWriteAccess['total'] > 0){
            require_once('view/_parts_inquiry_details.php');
          }
          else{
            require_once('view/_parts_inquiry.php');
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
    <script src="./assets/js/pages/parts-inquiry.js?v=<?php echo rand(); ?>"></script>
</body>

</html>