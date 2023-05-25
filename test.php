<?php
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    #$api->backup_database('DB_'. date('m.d.Y'), null);

    echo $api->encrypt_data('Passw0rd');
?>