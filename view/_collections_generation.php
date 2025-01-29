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
require_once '../model/miscellaneous-client-model.php';
require_once '../model/system-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$securityModel = new SecurityModel();
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: collections table
        # Description:
        # Generates the collections table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'collections table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');

            $filterORDateStartDate = $systemModel->checkDate('empty', $_POST['filter_or_date_start_date'], '', 'Y-m-d', '');
            $filterORDateEndDate = $systemModel->checkDate('empty', $_POST['filter_or_date_end_date'], '', 'Y-m-d', '');

            $filterPaymentDateStartDate = $systemModel->checkDate('empty', $_POST['filter_payment_date_start_date'], '', 'Y-m-d', '');
            $filterPaymentDateEndDate = $systemModel->checkDate('empty', $_POST['filter_payment_date_end_date'], '', 'Y-m-d', '');

            $filterReversedDateStartDate = $systemModel->checkDate('empty', $_POST['filter_reversed_date_start_date'], '', 'Y-m-d', '');
            $filterReversedDateEndDate = $systemModel->checkDate('empty', $_POST['filter_reversed_date_end_date'], '', 'Y-m-d', '');

            $filterCancellationDateStartDate = $systemModel->checkDate('empty', $_POST['filter_cancellation_date_start_date'], '', 'Y-m-d', '');
            $filterCancellationDateEndDate = $systemModel->checkDate('empty', $_POST['filter_cancellation_date_end_date'], '', 'Y-m-d', '');
            
            $filterCollectionsStatus = $_POST['filter_collections_status'];
            $filterPaymentAdvice = $_POST['filter_payment_advice'];

            if(empty($_POST['filter_payment_advice'])){
                $filterPaymentAdvice = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateCollectionsTable(:filterCollectionsStatus, :filterTransactionDateStartDate, :filterTransactionDateEndDate, :filterPaymentDateStartDate, :filterPaymentDateEndDate, :filterORDateStartDate, :filterORDateEndDate, :filterReversedDateStartDate, :filterReversedDateEndDate, :filterCancellationDateStartDate, :filterCancellationDateEndDate, :filterPaymentAdvice)');
            $sql->bindValue(':filterCollectionsStatus', $filterCollectionsStatus, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterORDateStartDate', $filterORDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterORDateEndDate', $filterORDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterPaymentDateStartDate', $filterPaymentDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterPaymentDateEndDate', $filterPaymentDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReversedDateStartDate', $filterReversedDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReversedDateEndDate', $filterReversedDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCancellationDateStartDate', $filterCancellationDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCancellationDateEndDate', $filterCancellationDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterPaymentAdvice', $filterPaymentAdvice, PDO::PARAM_STR);
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
                $orNumber = $row['or_number'];
                $modeOfPayment = $row['mode_of_payment'];
                $paymentAmount = $row['payment_amount'];
                $collectionStatus = $row['collection_status'];
                $collectedFrom = $row['collected_from'];
                $payment_advice = $row['payment_advice'];
                $orDate = $systemModel->checkDate('empty', $row['or_date'], '', 'm/d/Y', '');
                $paymentDate = $systemModel->checkDate('empty', $row['payment_date'], '', 'm/d/Y', '');
                $transactionDate = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($collectedFrom);
                $clientName = $miscellaneousClientDetails['client_name'] ?? null;

                if($collectionStatus == 'Pending'){
                    $collectionStatus = 'Posted';
                }

               if($payment_advice == 'Yes'){
                $payment_advice_badge = '<span class="badge bg-warning">Yes</span>';
               }
               else{
                $payment_advice_badge = '<span class="badge bg-info">No</span>';
               }

                $statusClasses = [
                    'Posted' => 'success',
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
                    'LOAN_NUMBER' => ' <a href="collections.php?id='. $loanCollectionIDEncrypted .'" title="View Details">
                                        '. $loanNumber .'
                                    </a>',
                    'CUSTOMER' => '<a href="collections.php?id='. $loanCollectionIDEncrypted .'" title="View Details"><div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div></a>',
                    'PAYMENT_DETAILS' => $paymentDetails,
                    'MODE_OF_PAYMENT' => $modeOfPayment,
                    'PAYMENT_DATE' => $paymentDate,
                    'COLLECTED_FROM' => $clientName,
                    'TRANSACTION_DATE' => $transactionDate,
                    'OR_NUMBER' => $orNumber,
                    'OR_DATE' => $orDate,
                    'PAYMENT_AMOUNT' => number_format($paymentAmount, 2),
                    'STATUS' => $badge,
                    'PAYMENT_ADVICE_BADGE' => $payment_advice_badge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="collections.php?id='. $loanCollectionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;

        case 'payment advice table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');

            $filterORDateStartDate = $systemModel->checkDate('empty', $_POST['filter_or_date_start_date'], '', 'Y-m-d', '');
            $filterORDateEndDate = $systemModel->checkDate('empty', $_POST['filter_or_date_end_date'], '', 'Y-m-d', '');

            $filterPaymentDateStartDate = $systemModel->checkDate('empty', $_POST['filter_payment_date_start_date'], '', 'Y-m-d', '');
            $filterPaymentDateEndDate = $systemModel->checkDate('empty', $_POST['filter_payment_date_end_date'], '', 'Y-m-d', '');

            $filterReversedDateStartDate = $systemModel->checkDate('empty', $_POST['filter_reversed_date_start_date'], '', 'Y-m-d', '');
            $filterReversedDateEndDate = $systemModel->checkDate('empty', $_POST['filter_reversed_date_end_date'], '', 'Y-m-d', '');

            $filterCancellationDateStartDate = $systemModel->checkDate('empty', $_POST['filter_cancellation_date_start_date'], '', 'Y-m-d', '');
            $filterCancellationDateEndDate = $systemModel->checkDate('empty', $_POST['filter_cancellation_date_end_date'], '', 'Y-m-d', '');
            
            $filterCollectionsStatus = $_POST['filter_collections_status'];

            $sql = $databaseModel->getConnection()->prepare('CALL generatePaymentAdviceTable(:filterCollectionsStatus, :filterTransactionDateStartDate, :filterTransactionDateEndDate, :filterPaymentDateStartDate, :filterPaymentDateEndDate, :filterORDateStartDate, :filterORDateEndDate, :filterReversedDateStartDate, :filterReversedDateEndDate, :filterCancellationDateStartDate, :filterCancellationDateEndDate)');
            $sql->bindValue(':filterCollectionsStatus', $filterCollectionsStatus, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterORDateStartDate', $filterORDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterORDateEndDate', $filterORDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterPaymentDateStartDate', $filterPaymentDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterPaymentDateEndDate', $filterPaymentDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReversedDateStartDate', $filterReversedDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReversedDateEndDate', $filterReversedDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCancellationDateStartDate', $filterCancellationDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCancellationDateEndDate', $filterCancellationDateEndDate, PDO::PARAM_STR);
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
                $orNumber = $row['or_number'];
                $modeOfPayment = $row['mode_of_payment'];
                $paymentAmount = $row['payment_amount'];
                $collectionStatus = $row['collection_status'];
                $collectedFrom = $row['collected_from'];
                $orDate = $systemModel->checkDate('empty', $row['or_date'], '', 'm/d/Y', '');
                $paymentDate = $systemModel->checkDate('empty', $row['payment_date'], '', 'm/d/Y', '');
                $transactionDate = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($collectedFrom);
                $clientName = $miscellaneousClientDetails['client_name'] ?? null;

                if($collectionStatus == 'Pending'){
                    $collectionStatus = 'Posted';
                }

                $statusClasses = [
                    'Posted' => 'success',
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
                    'LOAN_NUMBER' => ' <a href="collections.php?id='. $loanCollectionIDEncrypted .'" title="View Details">
                                        '. $loanNumber .'
                                    </a>',
                    'CUSTOMER' => '<a href="collections.php?id='. $loanCollectionIDEncrypted .'" title="View Details"><div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div></a>',
                    'PAYMENT_DETAILS' => $paymentDetails,
                    'MODE_OF_PAYMENT' => $modeOfPayment,
                    'PAYMENT_DATE' => $paymentDate,
                    'COLLECTED_FROM' => $clientName,
                    'TRANSACTION_DATE' => $transactionDate,
                    'OR_NUMBER' => $orNumber,
                    'OR_DATE' => $orDate,
                    'PAYMENT_AMOUNT' => number_format($paymentAmount, 2),
                    'STATUS' => $badge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="payment-advice.php?id='. $loanCollectionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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