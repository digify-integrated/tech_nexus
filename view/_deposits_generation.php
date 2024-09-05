<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/bank-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$bankModel = new BankModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: deposits table
        # Description:
        # Generates the deposits table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'deposits table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');

            $filterDepositDateStartDate = $systemModel->checkDate('empty', $_POST['filter_deposit_date_start_date'], '', 'Y-m-d', '');
            $filterDepositDateEndDate = $systemModel->checkDate('empty', $_POST['filter_deposit_date_end_date'], '', 'Y-m-d', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generateDepositsTable(:filterTransactionDateStartDate, :filterTransactionDateEndDate, :filterDepositDateStartDate, :filterDepositDateEndDate)');
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterDepositDateStartDate', $filterDepositDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterDepositDateEndDate', $filterDepositDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $depositsID = $row['deposits_id'];
                $depositAmount = $row['deposit_amount'];
                $depositedTo = $row['deposited_to'];
                $referenceNumber = $row['reference_number'];
                $depositDate = $systemModel->checkDate('empty', $row['deposit_date'], '', 'm/d/Y', '');
                $transactionDate = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');

                $bankDetails = $bankModel->getBank($depositedTo);
                $bankName = $bankDetails['bank_name'];

                $depositsIDEncrypted = $securityModel->encryptData($depositsID);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children pdc-id" type="checkbox" value="'. $depositsID .'">',
                    'DEPOSIT_AMOUNT' => number_format($depositAmount, 2),
                    'DEPOSIT_DATE' => $depositDate,
                    'DEPOSITED_TO' => $bankName,
                    'REFERENCE_NUMBER' => $referenceNumber,
                    'TRANSACTION_DATE' => $transactionDate,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="deposits.php?id='. $depositsIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>