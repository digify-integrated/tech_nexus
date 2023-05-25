<?php
    require('config/config.php');
    require('classes/api.php');

    //$api = new Api;
    //$api->backup_database('DB_'. date('m.d.Y'), null);
    //putenv('DB_HOST=localhost');

    echo getenv('DB_HOST');
?>