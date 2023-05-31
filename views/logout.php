<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    session_unset();

    setcookie('remember_me', '', time() - 3600, '/');
    unset($_COOKIE['remember_me']); 

    header('Location: dashboard.php');
    exit();
}
else{
    header('Location: dashboard.php');
}
 
?>