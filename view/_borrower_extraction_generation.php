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
        # Type: borrower extraction table
        # Description:
        # Generates the borrower extraction table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'borrower extraction table':
            $filterReleaseDateStartDate = $systemModel->checkDate('empty', $_POST['filter_release_date_start_date'], '', 'Y-m-d', '');
            $filterReleaseDateEndDate = $systemModel->checkDate('empty', $_POST['filter_release_date_end_date'], '', 'Y-m-d', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generateBorrowerExtractionTable(:filterReleaseDateStartDate, :filterReleaseDateEndDate)');
            $sql->bindValue(':filterReleaseDateStartDate', $filterReleaseDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterReleaseDateEndDate', $filterReleaseDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $contact_id  = $row['contact_id'];
                $customer_id  = $row['customer_id'];

                $customerDetails = $customerModel->getPersonalInformation($contact_id);
                $first_name = $customerDetails['first_name'];
                $middle_name = $customerDetails['middle_name'];
                $last_name = $customerDetails['last_name'];

                $birthday = $systemModel->checkDate('empty', $customerDetails['birthday'], '', 'm/d/Y', '');

                $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($contact_id);
                $address = $customerPrimaryAddress['address'] ?? '';
                $city_name = $customerPrimaryAddress['city_name'] ?? '';
                $state_name = $customerPrimaryAddress['state_name'] ?? '';

                $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($contact_id);
                $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
                $customerEmail = !empty($customerContactInformation['email']) ? $customerContactInformation['email'] : '--';      

                $response[] = [
                    'CUSTOMER_ID' => $customer_id,
                    'FIRST_NAME' => strtoupper($first_name),
                    'MIDDLE_NAME' => strtoupper($middle_name . ' ' . $last_name),
                    'BIRTHDAY' => $birthday,
                    'ADDRESS' => strtoupper($address),
                    'CITY' => strtoupper($city_name),
                    'STATE' => strtoupper($state_name),
                    'MOBILE' => $customerMobile,
                    'EMAIL' => $customerEmail
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>