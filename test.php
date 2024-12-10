<?php
    require_once 'config/config.php';
    require_once 'model/database-model.php';
    require_once 'model/employee-model.php';

    $databaseModel = new DatabaseModel();
    $employeeModel = new EmployeeModel($databaseModel);

    $getPersonalInformation = $employeeModel->getPersonalInformation(204);
    $contactImage = $getPersonalInformation['contact_image'] ?? '--';
    $contactImage = str_replace('./', 'cgmids.com/', $contactImage);

    echo $contactImage;
?>

2024-09-21