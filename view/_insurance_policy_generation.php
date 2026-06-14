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
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insurancePolicyModel = new InsurancePolicyModel($databaseModel);
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
        case 'insurance request table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM insurance_request');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $insurancePolicyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 199, 'delete');

            foreach ($options as $row) {
                $insurancePolicyID = $row['insurance_request_id'];
                $status = $row['status'];
                $request_type = $row['request_type'];
                $customer_type = $row['customer_type'];
                $insurance_provider = $row['insurance_provider'];
                $insurance_type_id = $row['insurance_type_id'];
                $customer_id = $row['customer_id'];
                $sales_proposal_id = $row['sales_proposal_id'];
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
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-request" data-insurance-request-id="'. $insurancePolicyID .'" title="Delete Insurance Policy">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $insurancePolicyID .'">',
                    'CUSTOMER_NAME' => $customerName,
                    'PROVIDER_NAME' => $provider_name,
                    'REQUEST_TYPE' => $request_type,
                    'INSURANCE_TYPE' => $insrance_type_name,
                    'INCEPTION_DATE' => $inception_date,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="insurance-request.php?id='. $insurancePolicyIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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