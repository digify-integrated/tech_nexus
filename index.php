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

    $pageTitle = 'Nexus Integrated Solutions';
    require('config/_interface_settings.php');
    require('session-check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
    <?php include_once('config/_required_css.php'); ?>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="false" data-pc-theme="light">
    <?php  include_once('config/_preloader.html'); ?>

    <div class="auth-main">
        <div class="auth-wrapper v2">
            <div class="auth-sidecontent">
                <img src="<?php echo $login_background; ?>" alt="images" class="img-fluid img-auth-side">
            </div>
            <form class="auth-form" id="signin-form" method="post" action="#">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#"><img src="<?php echo $login_logo; ?>" alt="img"></a>
                        </div>
                        <h4 class="text-center f-w-500 mb-3">Login with your email</h4>
                        <div class="form-group mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="d-flex mt-1 justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" id="remember_me" name="remember_me">
                                <label class="form-check-label text-muted" for="remember_me">Remember me?</label>
                            </div>
                            <a href="forgot-password.php" class="f-w-400 mb-0">Forgot Password?</a>
                        </div>
                        <div class="d-grid mt-4">
                            <button id="signin" type="submit" class="btn btn-primary">Login</button>
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
    <script src="./assets/js/pages/index.js?v=<?php echo rand(); ?>"></script>
</body>

</html>