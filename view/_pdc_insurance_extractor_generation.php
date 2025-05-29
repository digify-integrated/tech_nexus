<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/customer-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$customerModel = new CustomerModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: pdc insurance extractor table
        # Description:
        # Generates the pdc insurance extractor table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'pdc insurance extractor table':
            $filterCheckDateStartDate = $systemModel->checkDate('empty', $_POST['filter_check_date_start_date'], '', 'Y-m-d', '');
            $filterCheckDateEndDate = $systemModel->checkDate('empty', $_POST['filter_check_date_end_date'], '', 'Y-m-d', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generatePDCInsuranceExtractionTable(:filterCheckDateStartDate, :filterCheckDateEndDate)');
            $sql->bindValue(':filterCheckDateStartDate', $filterCheckDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCheckDateEndDate', $filterCheckDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $pdc_type  = $row['pdc_type'];
                $payment_amount  = $row['payment_amount'];
                $check_number  = $row['check_number'];
                $checkDate = $systemModel->checkDate('summary', $row['check_date'], '', 'm/d/Y', '');

                if($pdc_type == 'Loan' || $pdc_type == 'Customer'){
                    $contact_id = $row['customer_id'] ?? null;

                    $customerDetails = $customerModel->getPersonalInformation($contact_id);
                    $first_name = $customerDetails['first_name'];

                    $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($contact_id);
                    $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';  

                    $response[] = [
                        'FIRST_NAME' => $first_name,
                        'MOBILE' => $customerMobile,
                        'PAYMENT_AMOUNT' => number_format($payment_amount, 2),
                        'CHECK_DATE' => $checkDate,
                        'CHECK_NUMBER' => $check_number,
                    ];
                }

                
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>