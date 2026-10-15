<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/insurance-request-model.php';
require_once '../model/insurance-provider-model.php';
require_once '../model/insurance-type-model.php';
require_once '../model/insurance-category-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insuranceRequestModel = new InsuranceRequestModel($databaseModel);
$insuranceProviderModel = new InsuranceProviderModel($databaseModel);
$insuranceTypeModel = new InsuranceTypeModel($databaseModel);
$insuranceCategoryModel = new InsuranceCategoryModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'insurance request table':
            // Retrieve and sanitize filter inputs from AJAX POST request
            $status_filter = isset($_POST['status_filter']) ? trim($_POST['status_filter']) : '';
            $request_type_filter = isset($_POST['request_type_filter']) ? trim($_POST['request_type_filter']) : '';
            $filter_inception_start_date = $systemModel->checkDate('empty', $_POST['filter_inception_start_date'], '', 'Y-m-d', '');
            $filter_inception_end_date = $systemModel->checkDate('empty', $_POST['filter_inception_end_date'], '', 'Y-m-d', '');
            $insurance_provider_filter = isset($_POST['insurance_provider_filter']) ? trim($_POST['insurance_provider_filter']) : '';
            $insurance_category_filter = isset($_POST['insurance_category_filter']) ? trim($_POST['insurance_category_filter']) : '';

            // Base query and parameters array
            $query = 'SELECT * FROM insurance_request WHERE 1=1';
            $params = [];
            $conditions = [];

            // 1. Status Filter
            if (!empty($status_filter) && $status_filter !== 'All') {
                $conditions[] = 'status = :status_filter';
                $params[':status_filter'] = $status_filter;
            }

            // 2. Request Type Filter
            if (!empty($request_type_filter) && $request_type_filter !== 'All') {
                $conditions[] = 'request_type = :request_type_filter';
                $params[':request_type_filter'] = $request_type_filter;
            }

            // 3. Inception Date Range Filter
            if (!empty($filter_inception_start_date)) {
                $conditions[] = 'inception_date >= :filter_inception_start_date';
                $params[':filter_inception_start_date'] = $filter_inception_start_date;
            }

            if (!empty($filter_inception_end_date)) {
                $conditions[] = 'inception_date <= :filter_inception_end_date';
                $params[':filter_inception_end_date'] = $filter_inception_end_date;
            }

            // 4. Insurance Provider Filter (handles comma-separated checkbox IDs safely)
            if (!empty($insurance_provider_filter)) {
                $providers = array_filter(array_map('trim', explode(',', $insurance_provider_filter)));
                if (!empty($providers)) {
                    $providerPlaceholders = [];
                    foreach ($providers as $index => $providerId) {
                        $key = ':provider_' . $index;
                        $providerPlaceholders[] = $key;
                        $params[$key] = (int)$providerId;
                    }
                    $conditions[] = 'insurance_provider IN (' . implode(', ', $providerPlaceholders) . ')';
                }
            }

            // 5. Insurance Category Filter (handles comma-separated category values)
            if (!empty($insurance_category_filter)) {
                $categories = array_filter(array_map('trim', explode(',', $insurance_category_filter)));
                if (!empty($categories)) {
                    $categoryPlaceholders = [];
                    foreach ($categories as $index => $categoryVal) {
                        $key = ':category_' . $index;
                        $categoryPlaceholders[] = $key;
                        $params[$key] = $categoryVal;
                    }
                    $conditions[] = 'insurance_category IN (' . implode(', ', $categoryPlaceholders) . ')';
                }
            }

            // Append conditions to query if any exist
            if (!empty($conditions)) {
                $query .= ' AND ' . implode(' AND ', $conditions);
            }

            // Prepare and execute with bound parameters
            $sql = $databaseModel->getConnection()->prepare($query);
            $sql->execute($params);
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $insuranceRequestDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 199, 'delete');

            foreach ($options as $row) {
                $insuranceRequestID = $row['insurance_request_id'];
                $status = $row['status'];
                $request_type = $row['request_type'];
                $customer_type = $row['customer_type'];
                $insurance_provider = $row['insurance_provider'];
                $insurance_type_id = $row['insurance_type_id'];
                $customer_id = $row['customer_id'];
                $sales_proposal_id = $row['sales_proposal_id'];
                $insurance_category = $row['insurance_category'];
                $inception_date = $systemModel->checkDate('summary', $row['inception_date'], '', 'm/d/Y', '');

                if($customer_type == 'Customer'){
                    $customerDetails = $customerModel->getPersonalInformation($customer_id);
                    $customerName = $customerDetails['file_as'] ?? null;
                }
                else if($customer_type == 'Miscellaneous'){
                    $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
                    $customerName = $miscellaneousClientDetails['client_name'] ?? null;
                }
                else{
                    $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id); 
                    $customer_id = $salesProposalDetails['customer_id'] ?? null;
                    
                    $customerDetails = $customerModel->getPersonalInformation($customer_id);
                    $customerName = $customerDetails['file_as'] ?? null;
                }

                $insuranceTypeDetails = $insuranceTypeModel->getInsuranceType($insurance_type_id);
                $insrance_type_name = $insuranceTypeDetails['insurance_type_name'] ?? null;

                $providerDetails = $insuranceProviderModel->getInsuranceProvider($insurance_provider);
                $provider_name = $providerDetails['provider_name'] ?? null;

                $category = $insuranceCategoryModel->getInsuranceCategory($insurance_category);
                $insurance_category_name = $category['insurance_category_name'] ?? null;

                $insuranceRequestIDEncrypted = $securityModel->encryptData($insuranceRequestID);

                $delete = '';
                if($insuranceRequestDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-request" data-insurance-request-id="'. $insuranceRequestID .'" title="Delete Insurance Request">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $insuranceRequestID .'">',
                    'CUSTOMER_NAME' => $customerName,
                    'PROVIDER_NAME' => $provider_name,
                    'REQUEST_TYPE' => $request_type,
                    'INSURANCE_TYPE' => $insrance_type_name,
                    'INCEPTION_DATE' => $inception_date,
                    'CATEGORY' => $insurance_category_name,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="insurance-request.php?id='. $insuranceRequestIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
    }
}

?>