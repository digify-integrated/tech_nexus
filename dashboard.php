<?php
    require('session.php');
    require('config/config.php');
    require('classes/global-class.php');
    require('classes/administrator-class.php');

    $global_class = new Global_Class;
    $administrator_class = new Administrator_Class;
    $page_title = 'Dashboard';

    require('views/_interface_settings.php');
    require('views/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('views/_title.php'); ?>
    <?php include_once('views/_required_css.php'); ?>
</head>

<body>
    <?php 
        include_once('views/_preloader.html'); 
        include_once('views/_navbar.php'); 
        include_once('views/_header.php'); 
        include_once('views/_announcement.php'); 
    ?>
    
    

    <?php 
        include_once('views/_required_js.php'); 
        include_once('views/_customizer.php'); 
    ?>
</body>

</html>