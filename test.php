<?php
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');

    $api = new Database;
    $user_api = new UserModel;
    #$api->backup_database('DB_'. date('m.d.Y'));

    echo $user_api->generateOTP(6);
?>