<?php
    require_once './config/config.php';
    require_once './model/database-model.php';
    require_once './model/user-model.php';
    require_once './model/security-model.php';
    require_once './model/system-model.php';
    require_once './model/parts-model.php';
    require_once './model/parts-transaction-model.php';
    require_once './model/parts-subclass-model.php';
    require_once './model/parts-class-model.php';
    require_once './model/unit-model.php';
    require_once './model/product-model.php';
    require_once './model/miscellaneous-client-model.php';
    require_once './model/customer-model.php';
    require_once './model/back-job-monitoring-model.php';
    require_once './model/sales-proposal-model.php';
    require_once './model/contractor-model.php';
    require_once './model/work-center-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel($databaseModel, $systemModel);
    $partsModel = new PartsModel($databaseModel);
    $partsTransactionModel = new PartsTransactionModel($databaseModel);
    $partsSubclassModel = new PartsSubclassModel($databaseModel);
    $partsClassModel = new PartsClassModel($databaseModel);
    $unitModel = new UnitModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $backjobMonitoringModel = new BackJobMonitoringModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $contractorModel = new ContractorModel($databaseModel);
    $workCenterModel = new WorkCenterModel($databaseModel);
    $securityModel = new SecurityModel();



    $unitCategoryID = $securityModel->decryptData('eQlTa4WPejqsH68Us1Sn5y7NQRNRmtOSmyHSp8JuOXg%3D');

    echo $unitCategoryID;
?>