<?php 
ssession_start();

if (isset($_COOKIE['remember_token'])) {
    $rememberToken = $_COOKIE['remember_token'];

    $user = $userModel->getUserByRememberToken($rememberToken);

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];

        header("Location: dashboard.php");
        exit;
    } 
    else {
        setcookie('remember_token', '', time() - 3600, '/');
        unset($_COOKIE['remember_token']);
    }
}

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header('location: dashboard.php');
    exit;
}

?>