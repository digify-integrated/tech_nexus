<?php
    require('session-check.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $email = $api->decrypt_data($id);
    
        $check_user_exist = $api->check_user_exist(null, $email);
    
        if ($check_user_exist == 0) {
            header('location: index.php');
        }

        $page_title = 'Reset Password';

        require('views/_interface_settings.php');
    } 
    else {
        header('location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('views/_title.php'); ?>
    <?php include_once('views/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/uikit.css">
</head>

<body>
    <?php include_once('views/_preloader.html'); ?>

    <div class="auth-main">
        <div class="auth-wrapper v2">
            <div class="auth-sidecontent">
                <img src="<?php echo $login_background; ?>" alt="images" class="img-fluid img-auth-side">
            </div>
            <form class="auth-form" id="reset-password-form" method="post" action="#" reset-password-form-validate>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#"><img src="<?php echo $login_logo; ?>" alt="img"></a>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <h3 class="mb-0"><b>Reset Password</b></h3>
                                <a href="index.php" class="link-primary">Back to Login</a>
                            </div>
                            <p class="text-muted">Your password has expired. Please enter your new password.</p>
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" minlength="8" data-bouncer-message="Please choose a password that includes at least 1 uppercase character, 1 lowercase character, and 1 number." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" data-bouncer-match="#password" data-bouncer-mismatch-message="Your passwords do not match." required>
                        </div>
                        <div class="d-grid mt-4">
                            <button id="reset-password" type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php 
        include_once('views/_required_js.php'); 
    ?>
    <script src="./assets/js/pages/reset-password.js?v=<?php echo rand(); ?>"></script>
</body>

</html>