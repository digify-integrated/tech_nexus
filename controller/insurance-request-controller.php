<?php
session_start();

class InsuranceRequestController {
    private $insuranceRequestModel;
    private $salesProposalModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    public function __construct(InsuranceRequestModel $insuranceRequestModel, SalesProposalModel $salesProposalModel, UserModel $userModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->insuranceRequestModel = $insuranceRequestModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->userModel = $userModel;
        $this->systemModel = $systemModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save insurance request':
                    $this->saveInsuranceRequest();
                    break;
                case 'get insurance request details':
                    $this->getInsuranceRequestDetails();
                    break;
                case 'delete insurance request':
                    $this->deleteInsuranceRequest();
                    break;
                case 'delete multiple insurance request':
                    $this->deleteMultipleInsuranceRequest();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveInsuranceRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = isset($_POST['insurance_request_id']) ? htmlspecialchars($_POST['insurance_request_id'], ENT_QUOTES, 'UTF-8') : null;
        $requestType = htmlspecialchars($_POST['request_type'], ENT_QUOTES, 'UTF-8');
        $inceptionDate = $this->systemModel->checkDate('empty', $_POST['inception_date'], '', 'Y-m-d', '');
        $insuranceProviderId = htmlspecialchars($_POST['insurance_provider_id'], ENT_QUOTES, 'UTF-8');
        $insuranceTypeId = htmlspecialchars($_POST['insurance_type_id'], ENT_QUOTES, 'UTF-8');
        $customerType = htmlspecialchars($_POST['customer_type'], ENT_QUOTES, 'UTF-8');
        $miscId = htmlspecialchars($_POST['misc_id'], ENT_QUOTES, 'UTF-8');
        $customerId = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalId = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

        if($customerType == 'Customer'){
            $salesProposalId = '';
        }
        else if($customerType == 'Miscellaneous') {
            $customerId = $miscId;
            $salesProposalId = '';
        }
        else{
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalId);        
            $customerId = $salesProposalDetails['customer_id'] ?? null;
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequest($insuranceRequestID, $requestType, $inceptionDate, $insuranceProviderId, $insuranceTypeId, $customerType, $customerId, $salesProposalId, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'insuranceRequestID' => $this->securityModel->encryptData($insuranceRequestID)]);
            exit;
        } 
        else {
            $insuranceRequestID = $this->insuranceRequestModel->insertInsuranceRequest($requestType, $inceptionDate, $insuranceProviderId, $insuranceTypeId, $customerType, $customerId, $salesProposalId, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'insuranceRequestID' => $this->securityModel->encryptData($insuranceRequestID)]);
            exit;
        }
    }
    
    public function deleteInsuranceRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = htmlspecialchars($_POST['insurance_request_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->insuranceRequestModel->deleteInsuranceRequest($insuranceRequestID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleInsuranceRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestIDs = $_POST['insurance_request_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($insuranceRequestIDs as $insuranceRequestID){
            $this->insuranceRequestModel->deleteInsuranceRequest($insuranceRequestID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getInsuranceRequestDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['insurance_request_id']) && !empty($_POST['insurance_request_id'])) {
            $userID = $_SESSION['user_id'];
            $insuranceRequestID = $_POST['insurance_request_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $insuranceRequestDetails = $this->insuranceRequestModel->getInsuranceRequest($insuranceRequestID);

            $response = [
                'success' => true,
                'requestType' => $insuranceRequestDetails['request_type'],
                'customerType' => $insuranceRequestDetails['customer_type'],
                'insuranceProvider' => $insuranceRequestDetails['insurance_provider'],
                'insurancePolicyId' => $insuranceRequestDetails['insurance_policy_id'],
                'insuranceTypeId' => $insuranceRequestDetails['insurance_type_id'],
                'customerId' => $insuranceRequestDetails['customer_id'],
                'salesProposalId' => $insuranceRequestDetails['sales_proposal_id'],
                'inceptionDate' =>  $this->systemModel->checkDate('empty', $insuranceRequestDetails['inception_date'], '', 'm/d/Y', ''),
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/insurance-request-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InsuranceRequestController(new InsuranceRequestModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
