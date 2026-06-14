<?php
session_start();

class InsurancePolicyController {
    private $insurancePolicyModel;
    private $insuranceRequestModel;
    private $salesProposalModel;
    private $productModel;
    private $makeModel;
    private $colorModel;
    private $customerModel;
    private $miscellaneousClientModel;
    private $insuranceTypeModel;
    private $insuranceProviderModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    public function __construct(InsurancePolicyModel $insurancePolicyModel, InsuranceRequestModel $insuranceRequestModel, SalesProposalModel $salesProposalModel, ProductModel $productModel, MakeModel $makeModel, ColorModel $colorModel, CustomerModel $customerModel, MiscellaneousClientModel $miscellaneousClientModel, InsuranceTypeModel $insuranceTypeModel, InsuranceProviderModel $insuranceProviderModel, UserModel $userModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->insurancePolicyModel = $insurancePolicyModel;
        $this->insuranceRequestModel = $insuranceRequestModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->productModel = $productModel;
        $this->makeModel = $makeModel;
        $this->colorModel = $colorModel;
        $this->customerModel = $customerModel;
        $this->miscellaneousClientModel = $miscellaneousClientModel;
        $this->insuranceTypeModel = $insuranceTypeModel;
        $this->insuranceProviderModel = $insuranceProviderModel;
        $this->userModel = $userModel;
        $this->systemModel = $systemModel;
        $this->securityModel = $securityModel;
    }

    public function handlePolicy(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'tag cancel':
                    $this->saveInsurancePolicyAsCancelled();
                    break;
                case 'get insurance policy details':
                    $this->getInsurancePolicyDetails();
                    break;
                case 'delete insurance policy':
                    $this->deleteInsurancePolicy();
                    break;
                case 'delete multiple insurance policy':
                    $this->deleteMultipleInsurancePolicy();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveInsurancePolicyAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insurancePolicyID = $_POST['insurance_policy_id'];
        $cancellationReason = $_POST['cancellation_reason'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsurancePolicyExist = $this->insurancePolicyModel->checkInsurancePolicyExist($insurancePolicyID);
        $total = $checkInsurancePolicyExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insurancePolicyModel->updateInsurancePolicyStatus($insurancePolicyID, $cancellationReason, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function deleteInsurancePolicy() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insurancePolicyID = htmlspecialchars($_POST['insurance_policy_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsurancePolicyExist = $this->insurancePolicyModel->checkInsurancePolicyExist($insurancePolicyID);
        $total = $checkInsurancePolicyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->insurancePolicyModel->deleteInsurancePolicy($insurancePolicyID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleInsurancePolicy() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insurancePolicyIDs = $_POST['insurance_policy_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($insurancePolicyIDs as $insurancePolicyID){
            $this->insurancePolicyModel->deleteInsurancePolicy($insurancePolicyID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getInsurancePolicyDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['insurance_policy_id']) && !empty($_POST['insurance_policy_id'])) {
            $userID = $_SESSION['user_id'];
            $insurancePolicyID = $_POST['insurance_policy_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $insurancePolicyDetails = $this->insurancePolicyModel->getInsurancePolicy($insurancePolicyID);
            $insurance_request_id = $insurancePolicyDetails['insurance_request_id'] ?? null;
            $status = $insurancePolicyDetails['status'] ?? 'Active';
            $expiryDate = $insurancePolicyDetails['expiry_date'];

            $currentDate = date('Y-m-d');

            if ($status === 'Cancelled') {
                $displayStatus = 'Cancelled';
                $statusBadge = 'danger';
            }
            elseif (!empty($expiryDate) && strtotime($expiryDate) < strtotime($currentDate)) {
                $displayStatus = 'Expired';
                $statusBadge = 'secondary';
            }
            else {
                $displayStatus = 'Active';
                $statusBadge = 'success';
            }
            
            $insuranceRequestDetails = $this->insuranceRequestModel->getInsuranceRequest($insurance_request_id);

            $customer_type = $insuranceRequestDetails['customer_type'];
                $customer_id = $insuranceRequestDetails['customer_id'];
                $sales_proposal_id = $insuranceRequestDetails['sales_proposal_id'];
                $insurance_type_id = $insuranceRequestDetails['insurance_type_id'];
                $insurance_provider = $insuranceRequestDetails['insurance_provider'];

                if($customer_type == 'Customer'){
                    $customerDetails = $this->customerModel->getPersonalInformation($customer_id);
                    $customerName = $customerDetails['file_as'] ?? null;

                    $customerPrimaryAddress = $this->customerModel->getCustomerPrimaryAddress($customer_id);
                    $address = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];

                     $customerContactInformation = $this->customerModel->getCustomerPrimaryContactInformation($customer_id);
                    $mobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
                }
                else if($customer_type == 'Miscellaneous'){
                    $miscellaneousClientDetails = $this->miscellaneousClientModel->getMiscellaneousClient($customer_id);
                    $customerName = $miscellaneousClientDetails['client_name'] ?? null;
                    $address = $miscellaneousClientDetails['address'] ?? null;
                    $mobile = '--';
                }
                else{
                    $salesProposalDetails = $this->salesProposalModel->getSalesProposal($sales_proposal_id); 
                    $customer_id = $salesProposalDetails['customer_id'];
                    
                    $customerDetails = $this->customerModel->getPersonalInformation($customer_id);
                    $customerName = $customerDetails['file_as'] ?? null;

                    $customerPrimaryAddress = $this->customerModel->getCustomerPrimaryAddress($customer_id);
                    $address = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];

                     $customerContactInformation = $this->customerModel->getCustomerPrimaryContactInformation($customer_id);
                    $mobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
                }

                $insuranceTypeDetails = $this->insuranceTypeModel->getInsuranceType($insurance_type_id);
                $insrance_type_name = $insuranceTypeDetails['insurance_type_name'] ?? null;

                $providerDetails = $this->insuranceProviderModel->getInsuranceProvider($insurance_provider);
                $provider_name = $providerDetails['provider_name'] ?? null;

            $response = [
                'success' => true, 
                'status' => $displayStatus,
                'statusBadge' => $statusBadge,
                'policyNumber' => $insurancePolicyDetails['policy_number'],
                'policyType' => $insurancePolicyDetails['status'],
                'customer' => $customerName,
                'address' => $address,
                'mobile' => $mobile,
                'insuranceType' => $insrance_type_name,
                'providerName' => $provider_name,
                'premiumAmount' => number_format($insurancePolicyDetails['premium_amount'], 2),
                'coverageAmount' => number_format($insurancePolicyDetails['coverage_amount'], 2),
                'inceptionDate' =>  $this->systemModel->checkDate('empty', $insurancePolicyDetails['inception_date'], '', 'm/d/Y', ''),
                'expiryDate' =>  $this->systemModel->checkDate('empty', $expiryDate, '', 'm/d/Y', ''),
                'yearModel'          => $insuranceRequestDetails['year_model'] ?? '--',
                'color'              => $insuranceRequestDetails['color'] ?? '--',
                'make'               => $insuranceRequestDetails['make'] ?? '--',
                'plateNumber'        => $insuranceRequestDetails['plate_number'] ?? '--',
                'chassisNumber'      => $insuranceRequestDetails['chassis_number'] ?? '--',
                'engineNumber'       => $insuranceRequestDetails['engine_number'] ?? '--',
                'mvFileNumber'       => $insuranceRequestDetails['mv_file_number'] ?? '--'
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/insurance-policy-model.php';
require_once '../model/insurance-request-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/product-model.php';
require_once '../model/make-model.php';
require_once '../model/color-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/customer-model.php';
require_once '../model/miscellaneous-client-model.php';
require_once '../model/insurance-type-model.php';
require_once '../model/insurance-provider-model.php';

$controller = new InsurancePolicyController(new InsurancePolicyModel(new DatabaseModel), new InsuranceRequestModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new ProductModel(new DatabaseModel), new MakeModel(new DatabaseModel), new ColorModel(new DatabaseModel), new CustomerModel(new DatabaseModel), new MiscellaneousClientModel(new DatabaseModel), new InsuranceTypeModel(new DatabaseModel), new InsuranceProviderModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SystemModel(), new SecurityModel());
$controller->handlePolicy();
