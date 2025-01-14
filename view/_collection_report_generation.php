<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/pdc-management-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$pdcManagementModel = new PDCManagementModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: collection report table
        # Description:
        # Generates the collection report table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'collection report table':
            $filter_transaction_date_start_date = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filter_transaction_date_end_date = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');

            $filter_payment_date_start_date = $systemModel->checkDate('empty', $_POST['filter_payment_date_start_date'], '', 'Y-m-d', '');
            $filter_payment_date_end_date = $systemModel->checkDate('empty', $_POST['filter_payment_date_end_date'], '', 'Y-m-d', '');
            
            $values_company = $_POST['filter_collection_report_company'];
            $filter_payment_advice = $_POST['filter_payment_advice'];

            if(empty($_POST['filter_payment_advice'])){
                $filter_payment_advice = null;
            }

            if(!empty($values_company)){
                $values_array = explode(', ', $values_company);
    
                $quoted_values_array = array_map(function($value) {
                    return "'" . $value . "'";
                }, $values_array);
    
                $quoted_values_string = implode(', ', $quoted_values_array);
    
                $filterPDCManagementCompany = $quoted_values_string;
            }
            else{
                $filterPDCManagementCompany = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateCollectionReportTable(:filterPDCManagementCompany, :filter_transaction_date_start_date, :filter_transaction_date_end_date, :filter_payment_date_start_date, :filter_payment_date_end_date, :filter_payment_advice)');
            $sql->bindValue(':filterPDCManagementCompany', $filterPDCManagementCompany, PDO::PARAM_STR);
            $sql->bindValue(':filter_transaction_date_start_date', $filter_transaction_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_transaction_date_end_date', $filter_transaction_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_payment_date_start_date', $filter_payment_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_payment_date_end_date', $filter_payment_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_payment_advice', $filter_payment_advice, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $loanCollectionID = $row['loan_collection_id'];
                $orNumber = $row['or_number'];
                $reversalReferenceNumber = $row['reversal_reference_number'];
                $collectionStatus = $row['collection_status'];
                $paymentAmount = $row['payment_amount'];
                $checkNumber = $row['check_number'];
                $loanNumber = $row['loan_number'];
                $bankBranch = $row['bank_branch'];
                $modeOfPayment = $row['mode_of_payment'];
                $paymentDetails = $row['payment_details'];
                $pdcType = $row['pdc_type'];
                $remarks = $row['remarks'];
                $customerID = $row['customer_id'];
                $collectedFrom = $row['collected_from'];
                $transactionDate = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');
                $paymentDate = $systemModel->checkDate('empty', $row['payment_date'], '', 'm/d/Y', '');
                $depositDate = $systemModel->checkDate('empty', $row['deposit_date'], '', 'm/d/Y', '');
                $checkDate = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');

                if(empty($loanNumber)){
                    $loanNumber = '1000000808';
                    $customerDetails = $customerModel->getPersonalInformation($customerID);
                    $customerName = $customerDetails['file_as'] ?? null;

                    if(empty($customerName)){
                        $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($collectedFrom);
                        $customerName = $miscellaneousClientDetails['client_name'] ?? null;
                    }

                    $paymentDetails = $customerName . ' - ' . $paymentDetails . ' - ' . $remarks;                   
                }

                $getCreatedByLog = $pdcManagementModel->getCreatedByLog('loan_collections', $loanCollectionID);
                $changed_by = $getCreatedByLog['changed_by'];

                $createdByName = 'Lalaine Penacilla';

                if($collectionStatus == 'Reversed'){
                    $refno = $reversalReferenceNumber;
                }
                else{
                    $refno = $orNumber;
                }

                if($modeOfPayment == 'Online Deposit'){
                    $modeOfPayment = 'Online Transfer';
                }

                if($collectionStatus == 'Posted' || $collectionStatus == 'Deposited' || $collectionStatus == 'Cleared'){
                    $collectionStatus = 'Cleared';
                }

                if($modeOfPayment == 'Check'){
                    $modeOfPayment = 'Cheque';
                    $paymentDate = $depositDate;
                }

                $response[] = [
                    'TRANSACTION_DATE' => $transactionDate,
                    'PAYMENT_DATE' => $paymentDate,
                    'REFNO' => $refno,
                    'PAYMENT_AMOUNT' => $paymentAmount,
                    'CHECK_NUMBER' => $checkNumber,
                    'CHECK_DATE' => $checkDate,
                    'LOAN_NUMBER' => $loanNumber,
                    'PAYMENT_DETAILS' => $paymentDetails,
                    'BANK_BRANCH' => $bankBranch,
                    'MODE_OF_PAYMENT' => $modeOfPayment,
                    'COLLECTION_STATUS' => $collectionStatus,
                    'COLLECTED_BY' => $createdByName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>