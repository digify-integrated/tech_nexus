<?php
    require('config/config.php');
    require('model/database-model.php');
    require('model/security-model.php');
    require('model/user-model.php');

    $databaseModel = new DatabaseModel();
    $securityModel = new SecurityModel();
    $userModel = new UserModel($databaseModel);
    $page_title = 'OTP Verification';

    if(isset($_GET['id']) && !empty($_GET['id'])){
        $id = $_GET['id'];
        $userID = $securityModel->decryptData($id);
        $user = $userModel->getUserByID($userID);
        $otpExpiryDate = $user['otp_expiry_date'];
        $emailObscure = $securityModel->formatEmail($user['email']);

        if (strtotime(date('Y-m-d H:i:s')) > strtotime($otpExpiryDate)) {
            header('location: error.php?type='. $securityModel->encryptData('otp expired'));
            exit;
        }
    }
    else {
        header('location: 404.php');
    }

    require('config/_interface_settings.php');   
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

    <div class="auth-main">
        <div class="auth-wrapper v2">
            <div class="auth-sidecontent">
                <img src="<?php echo $login_background; ?>" alt="images" class="img-fluid img-auth-side">
            </div>
            <form class="auth-form" id="otp-form" method="post" action="#">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#"><img src="<?php echo $login_logo; ?>" alt="img"></a>
                        </div>
                        <h3 class="mb-2"><b>Enter OTP</b></h3>
                        <p class="">We`ve sent a OTP to your email - <?php echo $emailObscure; ?></p>
                        <div class="form-group mb-3">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $id; ?>>">
                            <input type="text" class="form-control" id="otp" name="otp" placeholder="Verification Code" autocomplete="off">
                        </div>
                        <div class="d-grid mt-4">
                            <button id="verify" type="submit" class="btn btn-primary">Continue</button>
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
    <script src="./assets/js/pages/otp-verification.js?v=<?php echo rand(); ?>"></script>
</body>

</html>