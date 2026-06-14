<?php
session_start();

class InsuranceRequestController {
    private $insuranceRequestModel;
    private $salesProposalModel;
    private $productModel;
    private $makeModel;
    private $colorModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    public function __construct(InsuranceRequestModel $insuranceRequestModel, SalesProposalModel $salesProposalModel, ProductModel $productModel, MakeModel $makeModel, ColorModel $colorModel, UserModel $userModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->insuranceRequestModel = $insuranceRequestModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->productModel = $productModel;
        $this->makeModel = $makeModel;
        $this->colorModel = $colorModel;
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
                case 'save insurance request computation':
                    $this->saveInsuranceRequestComputation();
                    break;
                case 'tag request draft':
                    $this->saveInsuranceRequestAsDraft();
                    break;
                case 'tag request received':
                    $this->saveInsuranceRequestAsReceived();
                    break;
                case 'tag request submitted':
                    $this->saveInsuranceRequestAsSubmitted();
                    break;
                case 'tag request for submission':
                    $this->saveInsuranceRequestForSubmission();
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
        $yearModel = htmlspecialchars($_POST['year_model'], ENT_QUOTES, 'UTF-8');
        $color = htmlspecialchars($_POST['color'], ENT_QUOTES, 'UTF-8');
        $make = htmlspecialchars($_POST['make'], ENT_QUOTES, 'UTF-8');
        $plateNumber = htmlspecialchars($_POST['plate_number'], ENT_QUOTES, 'UTF-8');
        $chassisNumber = htmlspecialchars($_POST['chassis_number'], ENT_QUOTES, 'UTF-8');
        $engineNumber = htmlspecialchars($_POST['engine_number'], ENT_QUOTES, 'UTF-8');
        $mvFileNumber = htmlspecialchars($_POST['mv_file_number'], ENT_QUOTES, 'UTF-8');
        $insurance_policy_id = $_POST['insurance_policy_id'];

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
            $productID = $salesProposalDetails['product_id'] ?? null;

            $productDetails = $this->productModel->getProduct($productID);
            $engineNumber = $productDetails['engine_number'] ?? null;
            $chassisNumber = $productDetails['chassis_number'] ?? null;
            $plateNumber = $productDetails['plate_number'] ?? null;
            $color_id = $productDetails['color_id'];
            $yearModel = $productDetails['year_model'];
            $make_id = $productDetails['make_id'];

            $make = $this->makeModel->getMake($make_id)['make_name'] ?? null;
            $color = $this->colorModel->getColor($color_id)['color_name'] ?? null;
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            // Included new vehicle parameter block to Update method
            $this->insuranceRequestModel->updateInsuranceRequest(
                $insuranceRequestID, $requestType, $inceptionDate, $insuranceProviderId, 
                $insuranceTypeId, $customerType, $customerId, $salesProposalId, 
                $yearModel, $color, $make, $plateNumber, $chassisNumber, $engineNumber, $mvFileNumber, $insurance_policy_id,
                $userID
            );
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'insuranceRequestID' => $this->securityModel->encryptData($insuranceRequestID)]);
            exit;
        } 
        else {
            // Included new vehicle parameter block to Insert method
            $insuranceRequestID = $this->insuranceRequestModel->insertInsuranceRequest(
                $requestType, $inceptionDate, $insuranceProviderId, $insuranceTypeId, 
                $customerType, $customerId, $salesProposalId, 
                $yearModel, $color, $make, $plateNumber, $chassisNumber, $engineNumber, $mvFileNumber, $insurance_policy_id,
                $userID
            );

            echo json_encode(['success' => true, 'insertRecord' => true, 'insuranceRequestID' => $this->securityModel->encryptData($insuranceRequestID)]);
            exit;
        }
    }

    public function saveInsuranceRequestAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'Draft', $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestAsReceived() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $policy_number = $_POST['policy_number'];
        $premium_amount = $_POST['premium_amount'];
        $coverage_amount = $_POST['coverage_amount'];
        $remarks = $_POST['remarks'];
        $inception_date2 = $this->systemModel->checkDate('empty', $_POST['inception_date2'], '', 'Y-m-d', '');
        $expiration_date = $this->systemModel->checkDate('empty', $_POST['expiration_date'], '', 'Y-m-d', '');
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'Received', $userID);
            $this->insuranceRequestModel->insertInsurancePolicy($insuranceRequestID, $policy_number, $premium_amount, $coverage_amount, $inception_date2, $expiration_date, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestAsSubmitted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'Submitted', $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestForSubmission() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'For Submission', $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestComputation() {
        // Return early if not a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Secure the logged-in User ID from session
        $userID = $_SESSION['user_id'] ?? null;
        $insuranceRequestID = $_POST['insurance_request_id'] ?? null;

        if (!$userID || !$insuranceRequestID) {
            echo json_encode(['success' => false, 'message' => 'Missing required IDs.']);
            exit;
        }

        // Check user state
        $user = $this->userModel->getUserByID($userID);
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        // Verify the record exists before trying to update it
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;

        if ($total > 0) {
            // Group all incoming calculation fields into a clean associative array
            $computationData = [
                'insurance_category'  => $_POST['insurance_category'],
                'ctpl_coverage'       => (double)($_POST['ctpl_coverage'] ?? 0),
                'od_theft_coverage'   => (double)($_POST['od_theft_coverage'] ?? 0),
                'aon_coverage'        => (double)($_POST['aon_coverage'] ?? 0),
                'tpbi_coverage'       => (double)($_POST['tpbi_coverage'] ?? 0),
                'tppd_coverage'       => (double)($_POST['tppd_coverage'] ?? 0),
                'par_coverage'        => (double)($_POST['par_coverage'] ?? 0),
                
                'ctpl_premium'        => (double)($_POST['ctpl_premium'] ?? 0),
                'od_theft_premium'    => (double)($_POST['od_theft_premium'] ?? 0),
                'aon_premium'         => (double)($_POST['aon_premium'] ?? 0),
                'tpbi_premium'        => (double)($_POST['tpbi_premium'] ?? 0),
                'tppd_premium'        => (double)($_POST['tppd_premium'] ?? 0),
                'par_premium'         => (double)($_POST['par_premium'] ?? 0),
                
                'total_premium'       => (double)($_POST['total_premium'] ?? 0),
                'vat_premium_tax'     => (double)($_POST['vat_premium_tax'] ?? 0),
                'docstamp'            => (double)($_POST['docstamp'] ?? 0),
                'local_tax'           => (double)($_POST['local_tax'] ?? 0),
                'gross'               => (double)($_POST['gross'] ?? 0),
                'net_premium'         => (double)($_POST['net_premium'] ?? 0),
                
                'premium_comission'   => (double)($_POST['premium_comission'] ?? 0),
                'aon_comission'       => (double)($_POST['aon_comission'] ?? 0),
                'tpbi_comission'      => (double)($_POST['tpbi_comission'] ?? 0),
                'tppd_comission'      => (double)($_POST['tppd_comission'] ?? 0),
                'par_comission'       => (double)($_POST['par_comission'] ?? 0),
                'comission_subtotal'  => (double)($_POST['comission_subtotal'] ?? 0),
                'commission_discount' => (double)($_POST['commission_discount'] ?? 0),
                'net_commission'      => (double)($_POST['net_commission'] ?? 0),
                'last_log_by'         => (int)$userID
            ];

            // Pass the record ID and array data block directly to the model
            $updated = $this->insuranceRequestModel->updateInsuranceRequestComputation($insuranceRequestID, $computationData);
            
            echo json_encode(['success' => $updated]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
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
                'insuranceCategory' => $insuranceRequestDetails['insurance_category'],
                'odTheftCoverage' => $insuranceRequestDetails['od_theft_coverage'] ?? 0,
                'aonCoverage' => $insuranceRequestDetails['aon_coverage'] ?? 0,
                'tpbiCoverage' => $insuranceRequestDetails['tpbi_coverage'],
                'tppdCoverage' => $insuranceRequestDetails['tppd_coverage'],
                'inceptionDate' =>  $this->systemModel->checkDate('empty', $insuranceRequestDetails['inception_date'], '', 'm/d/Y', ''),

                'yearModel'          => $insuranceRequestDetails['year_model'] ?? '',
                'color'              => $insuranceRequestDetails['color'] ?? '',
                'make'               => $insuranceRequestDetails['make'] ?? '',
                'plateNumber'        => $insuranceRequestDetails['plate_number'] ?? '',
                'chassisNumber'      => $insuranceRequestDetails['chassis_number'] ?? '',
                'engineNumber'       => $insuranceRequestDetails['engine_number'] ?? '',
                'mvFileNumber'       => $insuranceRequestDetails['mv_file_number'] ?? ''
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
require_once '../model/product-model.php';
require_once '../model/make-model.php';
require_once '../model/color-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InsuranceRequestController(new InsuranceRequestModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new ProductModel(new DatabaseModel), new MakeModel(new DatabaseModel), new ColorModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
