<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/internal-dr-model.php');
  
  $internalDRModel = new InternalDRModel($databaseModel);

  $pageTitle = 'Internal DR';
    
  $internalDRReadAccess = $userModel->checkMenuItemAccessRights($user_id, 78, 'read');
  $internalDRCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 78, 'create');
  $internalDRWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 78, 'write');
  $internalDRDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 78, 'delete');
  $internalDRDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 78, 'duplicate');
  $cancelInternalDR = $userModel->checkSystemActionAccessRights($user_id, 169);


  if ($internalDRReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: internal-dr.php');
      exit;
    }

    $internalDRID = $securityModel->decryptData($_GET['id']);

    $checkInternalDRExist = $internalDRModel->checkInternalDRExist($internalDRID);
    $total = $checkInternalDRExist['total'] ?? 0;

    $internalDRDetails = $internalDRModel->getInternalDR($internalDRID);
    $drStatus = $internalDRDetails['dr_status'];
    $releaseTo = $internalDRDetails['release_to'];
    $releaseMobile = $internalDRDetails['release_mobile'];
    $releaseAddress = $internalDRDetails['release_address'];
    $drNumber = $internalDRDetails['dr_number'];
    $drType = $internalDRDetails['dr_type'];
    $stockNumber = $internalDRDetails['stock_number'];
    $productDescription = $internalDRDetails['product_description'];
    $engineNumber = $internalDRDetails['engine_number'];
    $chassisNumber = $internalDRDetails['chassis_number'];
    $plateNumber = $internalDRDetails['plate_number'];
    $unitImage = $systemModel->checkImage($internalDRDetails['unit_image'], 'default');
    $createdDate = $systemModel->checkDate('default', $internalDRDetails['created_date'] ?? null, '', 'd-M-Y', '');

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $internalDRID = null;
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
                  <li class="breadcrumb-item">Sales Proposal</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="internal-dr.php"><?php echo $pageTitle; ?></a></li>
                  <?php
                    if(!$newRecord && !empty($internalDRID)){
                      echo '<li class="breadcrumb-item" id="internal-dr-id">'. $internalDRID .'</li>';
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
          if($newRecord && $internalDRCreateAccess['total'] > 0){
            require_once('view/_internal_dr_new.php');
          }
          else if(!empty($internalDRID) && $internalDRWriteAccess['total'] > 0){
            require_once('view/_internal_dr_details.php');
          }
          else{
            require_once('view/_internal_dr.php');
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
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/internal-dr.js?v=<?php echo rand(); ?>"></script>
</body>

</html>