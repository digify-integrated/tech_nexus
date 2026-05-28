<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/lead-model.php');
  require('model/lead-status-model.php');
  require('model/inquiry-type-model.php');
  require('model/city-model.php');
  require('model/gender-model.php');
  require('model/product-model.php');
  
  $leadModel = new LeadModel($databaseModel);
  $leadStatusModel = new LeadStatusModel($databaseModel);
  $inquiryTypeModel = new InquiryTypeModel($databaseModel);
  $cityModel = new CityModel($databaseModel);
  $genderModel = new GenderModel($databaseModel);
  $productModel = new ProductModel($databaseModel);

  $pageTitle = 'Lead Monitoring';
    
  $leadReadAccess = $userModel->checkMenuItemAccessRights($user_id, 191, 'read');
  $leadCreateAccess = $userModel->checkMenuItemAccessRights($user_id, 191, 'create');
  $leadWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 191, 'write');
  $leadDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 191, 'delete');
  $leadDuplicateAccess = $userModel->checkMenuItemAccessRights($user_id, 191, 'duplicate');

  if ($leadReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: lead.php');
      exit;
    }

    $leadID = $securityModel->decryptData($_GET['id']);

    $checkLeadExist = $leadModel->checkLeadExist($leadID);
    $total = $checkLeadExist['total'] ?? 0;

    if($total == 0){
      header('location: 404.php');
      exit;
    }
  }
  else{
    $leadID = null;
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
                  <li class="breadcrumb-item">Sales</li>
                  <li class="breadcrumb-item">Lead Management</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="lead-monitoring.php"><?php echo $pageTitle; ?></a></li>
                  <?php
                    if(!$newRecord && !empty($leadID)){
                      echo '<li class="breadcrumb-item" id="lead-id">'. $leadID .'</li>';
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
          if($newRecord && $leadCreateAccess['total'] > 0){
            require_once('view/_lead_new.php');
          }
          else if(!empty($leadID) && $leadWriteAccess['total'] > 0){
            require_once('view/_lead_details.php');
          }
          else{
            require_once('view/_lead.php');
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons (compatible with DT 1.x) -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<!-- Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- HTML5 Export -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/lead.js?v=<?php echo rand(); ?>"></script>
</body>

</html>