<?php 
session_start();

if(isset($_COOKIE['remember_me'])){
    $email = $_COOKIE['remember_me'];

    $_SESSION['logged_in'] = 1;
    $_SESSION['email'] = $email;
}
else{
    setcookie('remember_me', '', time() - 3600, '/');
    unset($_COOKIE['remember_me']); 
}

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1){
    header('location: dashboard.php');
}
?>