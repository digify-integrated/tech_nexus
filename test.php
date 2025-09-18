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


    $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction WHERE company_id IN ("1", "2") AND part_transaction_status IN ("Released", "Checked") AND DATE(released_date) = "2025-09-10"');
    $sql->execute();
    $options = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql->closeCursor();

    foreach ($options as $row) {
        $parts_transaction_id = $row['part_transaction_id'];
        $company_id = $row['company_id'];
        $customer_id = $row['customer_id'];
        $remarks = $row['remarks'];
        $customer_type = $row['customer_type'] ?? '';
        $released_date = $systemModel->checkDate('empty', $row['released_date'], '', 'Y-m-d', '');

        if($company_id == '2' || $company_id == '1'){
            $p_reference_number = $row['issuance_no'] ?? '';
        }
        else{
            $p_reference_number = $row['reference_number'] ?? '';
        }

        $cost = $partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'cost')['total'] ?? 0;


        if($customer_type == 'Internal'){
            $productDetails = $productModel->getProduct($customer_id);
            $is_service = $productDetails['is_service'] ?? 'No';
            $product_status = $productDetails['product_status'] ?? 'Draft';

            if($is_service == 'Yes'){
                $overallTotal = $partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'gasoline cost')['total'] ?? 0;

                $partsTransactionModel->createPartsTransactionProductExpense($customer_id, 'Issuance Slip', $parts_transaction_id, $overallTotal, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, 1); 
            }
            else{
                $overallTotal = $partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'overall total')['total'] ?? 0;

                $partsTransactionModel->createPartsTransactionProductExpense($customer_id, 'Issuance Slip', $parts_transaction_id, $overallTotal, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, 1); 
            }
        }
        else{
            $overallTotal = $partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'overall total')['total'] ?? 0;
            $is_service = 'No';
            $product_status = 'Draft';
        }

        $partsTransactionModel->createPartsTransactionEntry2($parts_transaction_id, $company_id, $p_reference_number, $cost, $overallTotal, $customer_type, $is_service, $product_status, '2025-09-10', 1);

        echo ';';
    }

    //$unitCategoryID = $securityModel->decryptData('Zr7qYtwr7bsnv3l4A1aZ0DkInAs4p9cy9Trinrgfc9w%3D');

    //echo $unitCategoryID;
?>