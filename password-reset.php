<?php
    require('config/config.php');
    require('model/database-model.php');
    require('model/security-model.php');
    require('model/user-model.php');
    require('model/system-model.php');
    require('model/interface-setting-model.php');

    $databaseModel = new DatabaseModel();
    $securityModel = new SecurityModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $interfaceSettingModel = new InterfaceSettingModel($databaseModel);
    $pageTitle = 'Password Reset';

    if(isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['token']) && !empty($_GET['token'])){
        $id = $_GET['id'];
        $token = $_GET['token'];

        $userID = $securityModel->decryptData($id);
        $resetToken = $securityModel->decryptData($token);

        $user = $userModel->getUserByID($userID);

        if($user){
            $userResetToken = $securityModel->decryptData($user['reset_token']);
            $userResetTokenExpiryDate = $user['reset_token_expiry_date'];

            if($resetToken != $userResetToken){
                header('location: error.php?type='. $securityModel->encryptData('password reset token invalid'));
                exit;
            }

            if(strtotime(date('Y-m-d H:i:s')) > strtotime($userResetTokenExpiryDate)){
                header('location: error.php?type='. $securityModel->encryptData('password reset token expired'));
                exit;
            }
        }
        else{
            header('location: 404.php');
            exit;
        }
    }
    else {
        header('location: 404.php');
        exit;
    }
    
    require('session-check.php');
    require('config/_interface_settings.php');
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
            <form class="auth-form" id="password-reset-form" method="post" action="#">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="index.php"><img src="<?php echo $login_logo; ?>" alt="img"></a>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <h3 class="mb-0"><b>Password Reset</b></h3>
                            </div>
                            <p class="text-muted">Please choose your new password.</p>
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $id; ?>">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" minlength="8">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                        </div>
                        <div class="d-grid mt-4">
                            <button id="password-reset" type="submit" class="btn btn-primary">Reset Password</button>
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
    <script src="./assets/js/pages/password-reset.js?v=<?php echo rand(); ?>"></script>
</body>

</html>