<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();

    setcookie('remember_token', '', time() - 3600, '/');
    unset($_COOKIE['remember_token']);

    header('Location: index.php');
    echo 'asd';
    exit;
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

header('Location: dashboard.php');
exit;
?>
