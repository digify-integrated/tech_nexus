<?php
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/system-model.php');
    require('model/interface-setting-model.php');

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $pageTitle = 'Forgot Password';
    
    require('config/_interface_settings.php');
    require('session-check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
    <?php include_once('config/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/uikit.css">
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="false" data-pc-theme="light">
    <?php include_once('config/_preloader.html'); ?>

    <div class="auth-main">
        <div class="auth-wrapper v2">
            <div class="auth-sidecontent">
            <img src="<?php echo $login_background; ?>" alt="images" class="img-fluid img-auth-side">
            </div>
            <form class="auth-form" id="forgot-password-form" method="post" action="#">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="javascript:void(0);"><img src="<?php echo $login_logo; ?>" alt="img" class="w-100"></a>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Forgot Password</b></h3>
                            <a href="index.php" class="link-primary">Back to Login</a>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" autocomplete="off">
                        </div>
                        <p class="mt-4 text-sm text-muted">Do not forgot to check SPAM box.</p>
                        <div class="d-grid mt-4">
                            <button id="forgot-password" type="submit" class="btn btn-primary">Send Password Reset Email</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php 
        include_once('config/_error_modal.php');
        include_once('config/_required_js.php');
    ?>
    <script src="./assets/js/pages/forgot-password.js?v=<?php echo rand(); ?>"></script>
</body>

</html>