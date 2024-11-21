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
require_once '../model/product-category-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: loan extraction table
        # Description:
        # Generates the loan extraction table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'loan extraction table':
            $filterReleaseDateStartDate = $systemModel->checkDate('empty', $_POST['filter_release_date_start_date'], '', 'Y-m-d', '');
            $filterReleaseDateEndDate = $systemModel->checkDate('empty', $_POST['filter_release_date_end_date'], '', 'Y-m-d', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generateLoanExtractionTable(:filterReleaseDateStartDate, :filterReleaseDateEndDate)');
            $sql->bindValue(':filterReleaseDateStartDate', $filterReleaseDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReleaseDateEndDate', $filterReleaseDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $sales_proposal_id = $row['sales_proposal_id'];
                $customer_id = $row['customer_id'];
                $loan_number = $row['loan_number'];
                $sales_proposal_number = $row['sales_proposal_number'];
                $product_type = $row['product_type'];
                $term_length = $row['term_length'];
                $term_type = $row['term_type'];
                $number_of_payments = $row['number_of_payments'];
                $payment_frequency = $row['payment_frequency'];
                $created_by = $row['created_by'];
                $product_id = $row['product_id'];
                $release_remarks = $row['release_remarks'];

                $released_date = $systemModel->checkDate('empty', $row['released_date'], '', 'm/d/Y', '');
                $first_due_date = $systemModel->checkDate('empty', $row['first_due_date'], '', 'm/d/Y', '');

                $salesProposalPricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($sales_proposal_id);
                $total_delivery_price = $salesProposalPricingComputationDetails['total_delivery_price'] ?? 0;
                $interest_rate = $salesProposalPricingComputationDetails['interest_rate'] ?? 0;
                $outstanding_balance = $salesProposalPricingComputationDetails['outstanding_balance'] ?? 0;
                $downpayment = $salesProposalPricingComputationDetails['downpayment'] ?? 0;

                $salesProposalOtherChargesDetails = $salesProposalModel->getSalesProposalOtherCharges($sales_proposal_id);
                $insurance_premium_subtotal = $salesProposalOtherChargesDetails['insurance_premium_subtotal'] ?? 0;
                $handling_fee_subtotal = $salesProposalOtherChargesDetails['handling_fee_subtotal'] ?? 0;
                $registration_fee = $salesProposalOtherChargesDetails['registration_fee'] ?? 0;
                $transfer_fee_subtotal = $salesProposalOtherChargesDetails['transfer_fee_subtotal'] ?? 0;
                $doc_stamp_tax_subtotal = $salesProposalOtherChargesDetails['doc_stamp_tax_subtotal'] ?? 0;
                $transaction_fee_subtotal = $salesProposalOtherChargesDetails['transaction_fee_subtotal'] ?? 0;

                $createdByDetails = $userModel->getUserByID($row['created_by']);
                $createdByName = strtoupper($createdByDetails['file_as'] ?? null);
                
                $countOtherChargesTotal = $salesProposalModel->countSalesProposalOtherChargesExist($sales_proposal_id)['total'] ?? 0;

                    if($countOtherChargesTotal <= 1){
                        $schedule = 'Charge Fee on the Released Date';
                    }
                    else{
                        $schedule = $countOtherChargesTotal;
                    }

                if($product_type == 'Unit'){
                    $productDetails = $productModel->getProduct($product_id);
                    $stock_number = $productDetails['stock_number'];
                    $product_category_id  = $productDetails['product_category_id'];
                    $product_subcategory_id = $productDetails['product_subcategory_id'];

                   

                    $productCategoryName = $productCategoryModel->getProductCategory($product_category_id)['product_category_name'];
                    $productSubcategoryName = $productSubcategoryModel->getProductSubcategory($product_subcategory_id)['product_subcategory_name'];
                    
                    $loan_product = $productCategoryName;
                }
                else{
                    $loan_product = $product_type;
                    $stock_number = $product_type;
                    $productSubcategoryName = $product_type;
                }

                $customerDetails = $customerModel->getPersonalInformation($customer_id);
                $getCustomerDetails = $customerModel->getCustomer($customer_id);
                $customer_id = $getCustomerDetails['customer_id'];                

                $response[] = [
                    'CUSTOMER_ID' => $customer_id,
                    'LOAN_NUMBER' => $loan_number,
                    'APPLICATION_NUMBER' => $sales_proposal_number,
                    'LOAN_PRODUCT' => $loan_product,
                    'DISBURSED_BY' => 'Cheque',
                    'OUTSTANDING_BALANCE' => $outstanding_balance,
                    'RELEASED_DATE' => $released_date,
                    'FIRST_DUE_DATE' => $first_due_date,
                    'INTEREST_RATE' => $interest_rate,
                    'LOAN_INTEREST_PERIOD' => 'Per Loan',
                    'TERM_LENGTH' => $term_length,
                    'TERM_TYPE' => $term_type,
                    'PAYMENT_FREQUENCY' => $payment_frequency,
                    'NUMBER_OF_REPAYMENTS' => $number_of_payments,
                    'TOTAL_DELIVERY_PRICE' => $total_delivery_price,
                    'STOCK_NUMBER' => $stock_number,
                    'RELEASE_REMARKS' => $release_remarks,
                    'CREATED_BY' => $createdByName,
                    'PRODUCT_SUBCATEGORY' => $productSubcategoryName,
                    'DOWNPAYMENT' => $downpayment,
                    'INSURANCE' => $insurance_premium_subtotal,
                    'REGISTRATION_FEE' => $registration_fee,
                    'HANDLING_FEE' => $handling_fee_subtotal,
                    'TRANSFER_FEE' => $transfer_fee_subtotal,
                    'DOC_STAMP_TAX' => $doc_stamp_tax_subtotal,
                    'TRANSACTION_FEE' => $transfer_fee_subtotal,
                    'DEPOSIT' => $downpayment,
                    'INSURANCE_SCHEDULE' => $schedule,
                    'REGISTRATION_FEE_SCHEDULE' => $schedule,
                    'HANDLING_FEE_SCHEDULE' => $schedule,
                    'TRANSFER_FEE_SCHEDULE' => $schedule,
                    'DOC_STAMP_TAX_SCHEDULE' => $schedule,
                    'TRANSACTION_FEE_SCHEDULE' => $schedule,
                    'DEPOSIT_SCHEDULE' => 'Charge Fee on the Released Date'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>