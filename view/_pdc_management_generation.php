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
require_once '../model/leasing-application-model.php';
require_once '../model/tenant-model.php';
require_once '../model/property-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$pdcManagementModel = new PDCManagementModel($databaseModel);
$securityModel = new SecurityModel();
$leasingApplicationModel = new LeasingApplicationModel($databaseModel);
$tenantModel = new TenantModel($databaseModel);
$propertyModel = new PropertyModel($databaseModel);

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: pdc management table
        # Description:
        # Generates the pdc management table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'pdc management table':
            $filterCheckDateStartDate = $systemModel->checkDate('empty', $_POST['filter_check_date_start_date'], '', 'Y-m-d', '');
            $filterCheckDateEndDate = $systemModel->checkDate('empty', $_POST['filter_check_date_end_date'], '', 'Y-m-d', '');

            $filterRedepositDateStartDate = $systemModel->checkDate('empty', $_POST['filter_redeposit_date_start_date'], '', 'Y-m-d', '');
            $filterRedepositDateEndDate = $systemModel->checkDate('empty', $_POST['filter_redeposit_date_end_date'], '', 'Y-m-d', '');

            $filterOnHoldDateStartDate = $systemModel->checkDate('empty', $_POST['filter_onhold_date_start_date'], '', 'Y-m-d', '');
            $filterOnHoldDateEndDate = $systemModel->checkDate('empty', $_POST['filter_onhold_date_end_date'], '', 'Y-m-d', '');

            $filterForDepositDateStartDate = $systemModel->checkDate('empty', $_POST['filter_for_deposit_date_start_date'], '', 'Y-m-d', '');
            $filterForDepositDateEndDate = $systemModel->checkDate('empty', $_POST['filter_for_deposit_date_end_date'], '', 'Y-m-d', '');

            $filterDepositDateStartDate = $systemModel->checkDate('empty', $_POST['filter_deposit_date_start_date'], '', 'Y-m-d', '');
            $filterDepositDateEndDate = $systemModel->checkDate('empty', $_POST['filter_deposit_date_end_date'], '', 'Y-m-d', '');

            $filterReversedDateStartDate = $systemModel->checkDate('empty', $_POST['filter_reversed_date_start_date'], '', 'Y-m-d', '');
            $filterReversedDateEndDate = $systemModel->checkDate('empty', $_POST['filter_reversed_date_end_date'], '', 'Y-m-d', '');

            $filterPulledOutDateStartDate = $systemModel->checkDate('empty', $_POST['filter_pulled_out_date_start_date'], '', 'Y-m-d', '');
            $filterPulledOutDateEndDate = $systemModel->checkDate('empty', $_POST['filter_pulled_out_date_end_date'], '', 'Y-m-d', '');

            $filterCancellationDateStartDate = $systemModel->checkDate('empty', $_POST['filter_cancellation_date_start_date'], '', 'Y-m-d', '');
            $filterCancellationDateEndDate = $systemModel->checkDate('empty', $_POST['filter_cancellation_date_end_date'], '', 'Y-m-d', '');

            $filterClearDateStartDate = $systemModel->checkDate('empty', $_POST['filter_clear_date_start_date'], '', 'Y-m-d', '');
            $filterClearDateEndDate = $systemModel->checkDate('empty', $_POST['filter_clear_date_end_date'], '', 'Y-m-d', '');
            
            $values = $_POST['filter_pdc_management_status'];
            $values_company = $_POST['filter_pdc_management_company'];

            if(!empty($values)){
                $values_array = explode(', ', $values);
    
                $quoted_values_array = array_map(function($value) {
                    return "'" . $value . "'";
                }, $values_array);
    
                $quoted_values_string = implode(', ', $quoted_values_array);
    
                $filterPDCManagementStatus = $quoted_values_string;
            }
            else{
                $filterPDCManagementStatus = null;
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

            $sql = $databaseModel->getConnection()->prepare('CALL generatePDCManagementTable(:filterPDCManagementStatus, :filterPDCManagementCompany, :filterCheckDateStartDate, :filterCheckDateEndDate, :filterRedepositDateStartDate, :filterRedepositDateEndDate, :filterOnHoldDateStartDate, :filterOnHoldDateEndDate, :filterForDepositDateStartDate, :filterForDepositDateEndDate, :filterDepositDateStartDate, :filterDepositDateEndDate, :filterReversedDateStartDate, :filterReversedDateEndDate, :filterPulledOutDateStartDate, :filterPulledOutDateEndDate, :filterCancellationDateStartDate, :filterCancellationDateEndDate, :filterClearDateStartDate, :filterClearDateEndDate)');
            $sql->bindValue(':filterPDCManagementStatus', $filterPDCManagementStatus, PDO::PARAM_STR);
            $sql->bindValue(':filterPDCManagementCompany', $filterPDCManagementCompany, PDO::PARAM_STR);
            $sql->bindValue(':filterCheckDateStartDate', $filterCheckDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCheckDateEndDate', $filterCheckDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterRedepositDateStartDate', $filterRedepositDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterRedepositDateEndDate', $filterRedepositDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterOnHoldDateStartDate', $filterOnHoldDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterOnHoldDateEndDate', $filterOnHoldDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterForDepositDateStartDate', $filterForDepositDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterForDepositDateEndDate', $filterForDepositDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterDepositDateStartDate', $filterDepositDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterDepositDateEndDate', $filterDepositDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReversedDateStartDate', $filterReversedDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReversedDateEndDate', $filterReversedDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterPulledOutDateStartDate', $filterPulledOutDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterPulledOutDateEndDate', $filterPulledOutDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCancellationDateStartDate', $filterCancellationDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCancellationDateEndDate', $filterCancellationDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterClearDateStartDate', $filterClearDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterClearDateEndDate', $filterClearDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $loanCollectionID = $row['loan_collection_id'];
                $salesProposalID = $row['sales_proposal_id'];
                $loanNumber = $row['loan_number'];
                $customerID = $row['customer_id'];
                $productID = $row['product_id'];
                $paymentDetails = $row['payment_details'];
                $checkNumber = $row['check_number'];
                $paymentAmount = $row['payment_amount'];
                $bankBranch = $row['bank_branch'];
                $collectionStatus = $row['collection_status'];
                $pdcType = $row['pdc_type'];
                $leasingApplicationID = $row['leasing_application_id'];
                $checkDate = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');
                $redepositDate = $systemModel->checkDate('empty', $row['new_deposit_date'], '', 'm/d/Y', '');
                $reversalDate = $systemModel->checkDate('empty', $row['reversal_date'], '', 'm/d/Y', '');


                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;
                
                if($pdcType == 'Leasing'){
                    $getLeasingApplication = $leasingApplicationModel->getLeasingApplication($leasingApplicationID);
                    $loanNumber = $getLeasingApplication['leasing_application_number'];
                    $tenantID = $getLeasingApplication['tenant_id'];
                    $propertyID = $getLeasingApplication['property_id'];

                    $getTenant = $tenantModel->getTenant($tenantID);
                    $customerName = $getTenant['tenant_name'];

                    $getProperty = $propertyModel->getProperty($propertyID);
                    $corporateName = $getProperty['property_name'];

                }

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $statusClasses = [
                    'Pending' => 'info',
                    'Cleared' => 'success',
                    'On-Hold' => 'dark',
                    'Cancelled' => 'warning',
                    'Redeposit' => 'info',
                    'For Deposit' => 'warning',
                    'Deposited' => 'success',
                    'Pulled-Out' => 'dark',
                    'Reversed' => 'danger'
                ];
                
                $defaultClass = 'dark';
                
                $class = $statusClasses[$collectionStatus] ?? $defaultClass;
                
                $badge = '<span class="badge bg-' . $class . '">' . $collectionStatus . '</span>';

                $loanCollectionIDEncrypted = $securityModel->encryptData($loanCollectionID);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children pdc-id" type="checkbox" value="'. $loanCollectionID .'">',
                    'LOAN_NUMBER' => ' <a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" title="View Details">
                                        '. $loanNumber .'
                                    </a>',
                    'CUSTOMER' => '<a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" title="View Details"><div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div></a>',
                    'PRODUCT' => '<a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" title="View Details">' . $stockNumber . '<br/>' . $productName . '</a>',
                    'PAYMENT_DETAILS' => $paymentDetails,
                    'CHECK_NUMBER' => $checkNumber,
                    'CHECK_DATE' => $checkDate,
                    'REDEPOSIT_DATE' => $redepositDate,
                    'PAYMENT_AMOUNT' => number_format($paymentAmount, 2),
                    'BANK_BRANCH' => $bankBranch,
                    'REVERSAL_DATE' => $reversalDate,
                    'STATUS' => $badge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: transaction history table
        # Description:
        # Generates the transaction history table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'transaction history table':            
            $loanCollectionID = $_POST['loan_collection_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generatePDCManagementTransactionHistoryTable(:loanCollectionID)');
            $sql->bindValue(':loanCollectionID', $loanCollectionID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $loanCollectionsHistoryID = $row['loan_collections_history_id'];
                $loanCollectionID = $row['loan_collection_id'];
                $transactionType = $row['transaction_type'];
                $referenceNumber = $row['reference_number'];
                $transactionDate = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y h:i:s A', '');
                $referenceDate = $systemModel->checkDate('empty', $row['reference_date'], '', 'm/d/Y', '');

                $createdByDetails = $userModel->getUserByID($row['last_log_by']);
                $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

                $response[] = [
                    'TRANSACTION_TYPE' => $transactionType,
                    'TRANSACTION_DATE' => $transactionDate,
                    'REFERENCE_NUMBER' => $referenceNumber,
                    'REFERENCE_DATE' => $referenceDate,
                    'REFERENCE_DATE' => $referenceDate,
                    'TRANSACTION_BY' => $createdByName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: all transaction history table
        # Description:
        # Generates all of the transaction history table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'all transaction history table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');

            $filterReferenceDateStartDate = $systemModel->checkDate('empty', $_POST['filter_reference_date_start_date'], '', 'Y-m-d', '');
            $filterReferenceDateEndDate = $systemModel->checkDate('empty', $_POST['filter_reference_date_end_date'], '', 'Y-m-d', '');
            
            $values = $_POST['filter_transaction_type'];
            $values2 = $_POST['filter_mode_of_payment'];

            if(!empty($values)){
                $values_array = explode(', ', $values);

                $quoted_values_array = array_map(function($value) {
                    return "'" . $value . "'";
                }, $values_array);
    
                $quoted_values_string = implode(', ', $quoted_values_array);
    
                $filterTransactionType = $quoted_values_string;
            }
            else{
                $filterTransactionType = null;
            }

            if(!empty($values2)){
                $values_array = explode(', ', $values2);

                $quoted_values_array = array_map(function($values2) {
                    return "'" . $values2 . "'";
                }, $values_array);
    
                $quoted_values_string = implode(', ', $quoted_values_array);
    
                $filterModeOfPayment = $quoted_values_string;
            }
            else{
                $filterModeOfPayment = null;
            }
            
            $sql = $databaseModel->getConnection()->prepare('CALL generateAllPDCManagementTransactionHistoryTable(:filterTransactionType, :filterModeOfPayment, :filterTransactionDateStartDate, :filterTransactionDateEndDate, :filterReferenceDateStartDate, :filterReferenceDateEndDate)');
            $sql->bindValue(':filterTransactionType', $filterTransactionType, PDO::PARAM_STR);
            $sql->bindValue(':filterModeOfPayment', $filterModeOfPayment, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReferenceDateStartDate', $filterReferenceDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReferenceDateEndDate', $filterReferenceDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $loanCollectionsHistoryID = $row['loan_collections_history_id'];
                $loanCollectionID = $row['loan_collection_id'];
                $transactionType = $row['transaction_type'];
                $referenceNumber = $row['reference_number'];
                $modeOfPayment = $row['mode_of_payment'];
                $transactionDate = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y h:i:s A', '');
                $referenceDate = $systemModel->checkDate('empty', $row['reference_date'], '', 'm/d/Y', '');

                $createdByDetails = $userModel->getUserByID($row['last_log_by']);
                $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

                $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
                $loanNumber = $pdcManagementDetails['loan_number'] ?? null;
                $productID = $pdcManagementDetails['product_id'] ?? null;
                $customerID = $pdcManagementDetails['customer_id'] ?? null;
                $paymentDetails = $pdcManagementDetails['payment_details'] ?? null;
                $checkNumber = $pdcManagementDetails['check_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $loanCollectionIDEncrypted = $securityModel->encryptData($loanCollectionID);

                $response[] = [
                    'LOAN_NUMBER' => ' <a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" title="View Details" target="_blank">
                                        '. $loanNumber .'
                                    </a>',
                    'CUSTOMER' => '<a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" title="View Details" target="_blank"><div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div></a>',
                    'PRODUCT' => '<a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" title="View Details" target="_blank">' . $stockNumber . '<br/>' . $productName . '</a>',
                    'PAYMENT_DETAILS' => $paymentDetails,
                    'CHECK_NUMBER' => $checkNumber,
                    'MODE_OF_PAYMENT' => $modeOfPayment,
                    'TRANSACTION_TYPE' => $transactionType,
                    'TRANSACTION_DATE' => $transactionDate,
                    'REFERENCE_NUMBER' => $referenceNumber,
                    'REFERENCE_DATE' => $referenceDate,
                    'REFERENCE_DATE' => $referenceDate,
                    'TRANSACTION_BY' => $createdByName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>