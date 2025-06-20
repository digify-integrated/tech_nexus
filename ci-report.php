<?php
  require('config/_required_php_file.php');
  require('config/_check_user_active.php');
  require('model/city-model.php');
  require('model/residence-type-model.php');
  require('model/structure-type-model.php');
  require('model/building-make-model.php');
  require('model/neighborhood-type-model.php');
  require('model/bank-model.php');
  require('model/business-location-type-model.php');
  require('model/business-premises-model.php');
  require('model/bank-account-type-model.php');
  require('model/currency-model.php');
  require('model/bank-handling-type-model.php');
  require('model/loan-type-model.php');
  require('model/asset-type-model.php');
  require('model/cmap-report-type-model.php');
  require('model/brand-model.php');
  require('model/color-model.php');
  require('model/income-level-model.php');

  $pageTitle = 'CI Report';

  $cityModel = new CityModel($databaseModel);
  $residenceTypeModel = new ResidenceTypeModel($databaseModel);
  $structureTypeModel = new StructureTypeModel($databaseModel);
  $buildingMakeModel = new BuildingMakeModel($databaseModel);
  $neighborhoodTypeModel = new NeighborhoodTypeModel($databaseModel);
  $bankModel = new BankModel($databaseModel);
  $businessLocationTypeModel = new BusinessLocationTypeModel($databaseModel);
  $buildingMakeModel = new BuildingMakeModel($databaseModel);
  $businessPremisesModel = new BusinessPremisesModel($databaseModel);
  $bankAccountTypeModel = new BankAccountTypeModel($databaseModel);
  $currencyModel = new CurrencyModel($databaseModel);
  $bankHandlingTypeModel = new BankHandlingTypeModel($databaseModel);
  $loanTypeModel = new LoanTypeModel($databaseModel);
  $assetTypeModel = new AssetTypeModel($databaseModel);
  $cmapReportTypeModel = new CMAPReportTypeModel($databaseModel);
  $brandModel = new BrandModel($databaseModel);
  $colorModel = new ColorModel($databaseModel);
  $incomeLevelModel = new IncomeLevelModel($databaseModel);
      
  $ciReportReadAccess = $userModel->checkMenuItemAccessRights($user_id, 159, 'read');
  $ciReportWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 159, 'write');

  if ($ciReportReadAccess['total'] == 0) {
    header('location: 404.php');
    exit;
  }

  if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: ci-report.php');
      exit;
    }

    $ciReportID = $securityModel->decryptData($_GET['id']);
  }
  else{
    $ciReportID = null;
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
                  <li class="breadcrumb-item">Sales Proposal</li>
                  <li class="breadcrumb-item" aria-current="page"><a href="ci-report.php"><?php echo $pageTitle; ?></a></li>
                  <?php
                    if(!$newRecord && !empty($ciReportID)){
                      echo '<li class="breadcrumb-item" id="ci-report-id">'. $ciReportID .'</li>';
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
         if(!empty($ciReportID)){
            require_once('view/_ci_report_details.php');
          }
          else{
            require_once('view/_ci_report.php');
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
    <script src="./assets/js/plugins/imask.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/ci-report.js?v=<?php echo rand(); ?>"></script>
</body>

</html>