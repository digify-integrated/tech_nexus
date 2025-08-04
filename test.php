<?php
    include_once('./config/config.php');
    include_once('./model/security-model.php');

    $securityModel = new SecurityModel();

    $unitCategoryID = $securityModel->decryptData('iCsSd3TX5XVV84kSXjLJ491v9F5rUaNyHJUg5qMm7%2F8%3D');

    echo $unitCategoryID;
?>