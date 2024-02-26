<?php
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');

    $api = new Database;
    $user_api = new UserModel($api);
    #$api->backup_database('DB_'. date('m.d.Y'));
    #$otp = $user_api->generateOTP(6);

    #echo $user_api->sendOTP('ldagulto@encorefinancials.com', $otp);

    echo password_hash('P@ssw0rd', PASSWORD_DEFAULT);
?>