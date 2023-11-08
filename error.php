<?php
  require('config/config.php');
  require('model/security-model.php');

  $securityModel = new SecurityModel();

  $pageTitle = 'Error';    

  if(isset($_GET['type']) && !empty($_GET['type'])){
    $type = $securityModel->decryptData($_GET['type']);
    $errorDetails = $securityModel->getErrorDetails($type);
  }
  else{
    header('location: index.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once('config/_title.php'); ?>
  <?php include_once('config/_required_css.php'); ?>
  <link rel="stylesheet" href="./assets/css/uikit.css">
</head>

<body>
    <?php include_once('config/_preloader.html'); ?>

    <div class="maintenance-block construction-card-1">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="card construction-card">
              <div class="card-body">
                <div class="construction-image-block">
                  <div class="row justify-content-center align-items-center construction-card-bottom">
                    <div class="col-md-6">
                      <div class="text-center">
                        <h1 class="mt-4"><b><?php echo $errorDetails['TITLE']; ?></b></h1>
                        <p class="mt-4 text-muted"><?php echo $errorDetails['MESSAGE']; ?></p>
                        <a href="index.php" class="btn btn-primary mb-3">Back To Home</a>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <img class="img-fluid" src="./assets/images/default/img-cunstruct-1.svg" alt="img">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
        include_once('config/_required_js.php');
    ?>
</body>

</html>