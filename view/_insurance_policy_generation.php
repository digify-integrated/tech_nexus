<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/insurance-request-model.php';
require_once '../model/insurance-policy-model.php';
require_once '../model/insurance-provider-model.php';
require_once '../model/insurance-type-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insurancePolicyModel = new InsurancePolicyModel($databaseModel);
$insuranceRequestModel = new InsuranceRequestModel($databaseModel);
$insuranceProviderModel = new InsuranceProviderModel($databaseModel);
$insuranceTypeModel = new InsuranceTypeModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'insurance policy table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM insurance_policy');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $insurancePolicyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 200, 'delete');
              $currentDate = date('Y-m-d');

            foreach ($options as $row) {
                $insurancePolicyID = $row['insurance_policy_id'];
                $insurance_request_id = $row['insurance_request_id'];
                $policy_number = $row['policy_number'];
                $status = $row['status'];
                $premium_amount = $row['premium_amount'] ?? 0;
                $coverage_amount = $row['coverage_amount'] ?? 0;
                $inception_date = $systemModel->checkDate('summary', $row['inception_date'], '', 'm/d/Y', '');
                $expiry_date = $systemModel->checkDate('summary', $row['expiry_date'], '', 'm/d/Y', '');

                if ($status === 'Cancelled') {
                    $displayStatus = 'Cancelled';
                    $statusBadge = 'danger';
                }
                elseif (!empty($expiry_date) && strtotime($expiry_date) < strtotime($currentDate)) {
                    $displayStatus = 'Expired';
                    $statusBadge = 'secondary';
                }
                else {
                    $displayStatus = 'Active';
                    $statusBadge = 'success';
                }

                $insuranceRequestDetails = $insuranceRequestModel->getInsuranceRequest($insurance_request_id);
                $customer_type = $insuranceRequestDetails['customer_type'];
                $customer_id = $insuranceRequestDetails['customer_id'];
                $sales_proposal_id = $insuranceRequestDetails['sales_proposal_id'];
                $insurance_type_id = $insuranceRequestDetails['insurance_type_id'];
                $insurance_provider = $insuranceRequestDetails['insurance_provider'];

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
                    $customer_id = $salesProposalDetails['customer_id'];
                    
                    $customerDetails = $customerModel->getPersonalInformation($customer_id);
                    $customerName = $customerDetails['file_as'] ?? null;
                }

                $insuranceTypeDetails = $insuranceTypeModel->getInsuranceType($insurance_type_id);
                $insrance_type_name = $insuranceTypeDetails['insurance_type_name'] ?? null;

                $providerDetails = $insuranceProviderModel->getInsuranceProvider($insurance_provider);
                $provider_name = $providerDetails['provider_name'] ?? null;

                $insurancePolicyIDEncrypted = $securityModel->encryptData($insurancePolicyID);

                $delete = '';
                if($insurancePolicyDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-policy" data-insurance-policy-id="'. $insurancePolicyID .'" title="Delete Insurance Policy">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $insurancePolicyID .'">',
                    'POLICY_NUMBER' => $policy_number,
                    'CUSTOMER_NAME' => $customerName,
                    'INSURANCE_TYPE' => $insrance_type_name,
                    'PROVIDER_NAME' => $provider_name,
                    'PREMIUM_AMOUNT' => number_format($premium_amount),
                    'INCEPTION_DATE' => $inception_date,
                    'EXPIRY_DATE' => $expiry_date,
                    'STATUS' => '<span class="badge bg-'. $statusBadge .'">
                                   '. $displayStatus .'
                                </span>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="insurance-policy.php?id='. $insurancePolicyIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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