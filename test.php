<?php
    require('config/config.php');
    require('classes/global-class.php');

    $api = new Global_Class;
    #$api->backup_database('DB_'. date('m.d.Y'));

    echo $api->encrypt_data("P@ssw0rd");
?>