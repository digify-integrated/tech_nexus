<?php
# -------------------------------------------------------------
#
# Name       : user-interface-controller
# Purpose    : This is to handle all of the generation on the user interface.
# Installer  : Default
#
# -------------------------------------------------------------

session_start();

require('../config/config.php');
require('../classes/global-class.php');
require('../classes/administrator-class.php');
require('../classes/user-interface-class.php');

if(isset($_POST['transaction']) && !empty($_POST['transaction'])){
    $transaction = htmlspecialchars($_POST['transaction'], ENT_QUOTES, 'UTF-8');
    $global_class = new Global_Class();
    $administrator_class = new Administrator_Class;
    $user_interface_class = new User_Interface_Class;

    $error = '';
    $response = [];

    switch ($transaction) {
       

        
    }
}

?>