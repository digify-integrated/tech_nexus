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

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$securityModel = new SecurityModel();

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

            $values_array = explode(', ', $values);

            $quoted_values_array = array_map(function($value) {
                return "'" . $value . "'";
            }, $values_array);

            $quoted_values_string = implode(', ', $quoted_values_array);

            $filterPDCManagementStatus = $quoted_values_string;

            $sql = $databaseModel->getConnection()->prepare('CALL generatePDCManagementTable(:filterPDCManagementStatus, :filterCheckDateStartDate, :filterCheckDateEndDate, :filterRedepositDateStartDate, :filterRedepositDateEndDate, :filterOnHoldDateStartDate, :filterOnHoldDateEndDate, :filterForDepositDateStartDate, :filterForDepositDateEndDate, :filterDepositDateStartDate, :filterDepositDateEndDate, :filterReversedDateStartDate, :filterReversedDateEndDate, :filterPulledOutDateStartDate, :filterPulledOutDateEndDate, :filterCancellationDateStartDate, :filterCancellationDateEndDate, :filterClearDateStartDate, :filterClearDateEndDate)');
            $sql->bindValue(':filterPDCManagementStatus', $filterPDCManagementStatus, PDO::PARAM_STR);
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
                $checkDate = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');
                $redepositDate = $systemModel->checkDate('empty', $row['new_deposit_date'], '', 'm/d/Y', '');

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

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
    }
}

?>