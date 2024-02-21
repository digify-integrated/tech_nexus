<?php
session_start();

# -------------------------------------------------------------
#
# Function: CustomerController
# Description: 
# The CustomerController class handles customer related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class SalesProposalController {
    private $salesProposalModel;
    private $customerModel;
    private $userModel;
    private $systemSettingModel;
    private $companyModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CustomerModel, UserModel and SecurityModel instances.
    # These instances are used for customer related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param SalesProposalModel $salesProposalModel     The SalesProposalModel instance for sales proposal related operations.
    # - @param CustomerModel $customerModel     The CustomerModel instance for customer related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(SalesProposalModel $salesProposalModel, CustomerModel $customerModel, UserModel $userModel, CompanyModel $companyModel, SystemSettingModel $systemSettingModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->salesProposalModel = $salesProposalModel;
        $this->customerModel = $customerModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemModel = $systemModel;
        $this->securityModel = $securityModel;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: handleRequest
    # Description: 
    # This method checks the request method and dispatches the corresponding transaction based on the provided transaction parameter.
    # The transaction determines which action should be performed.
    #
    # Parameters:
    # - $transaction (string): The type of transaction.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save sales proposal':
                    $this->saveSalesProposal();
                    break;
                case 'save sales proposal accessories':
                    $this->saveSalesProposalAccessories();
                    break;
                case 'save sales proposal job order':
                    $this->saveSalesProposalJobOrder();
                    break;
                case 'save sales proposal additional job order':
                    $this->saveSalesProposalAdditionalJobOrder();
                    break;
                case 'get sales proposal details':
                    $this->getSalesProposalDetails();
                    break;
                case 'get sales proposal accessories details':
                    $this->getSalesProposalAccessoriesDetails();
                    break;
                case 'get sales proposal job order details':
                    $this->getSalesProposalJobOrderDetails();
                    break;
                case 'get sales proposal additional job order details':
                    $this->getSalesProposalAdditionalJobOrderDetails();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposal
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = isset($_POST['sales_proposal_id']) ? htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8') : null;
        $salesProposalNumber = isset($_POST['sales_proposal_number']) ? htmlspecialchars($_POST['sales_proposal_number'], ENT_QUOTES, 'UTF-8') : $this->systemSettingModel->getSystemSetting(6)['value'] + 1;
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $comakerID = htmlspecialchars($_POST['comaker_id'], ENT_QUOTES, 'UTF-8');
        $referredBy = htmlspecialchars($_POST['referred_by'], ENT_QUOTES, 'UTF-8');
        $releaseDate = $this->systemModel->checkDate('empty', $_POST['release_date'], '', 'Y-m-d', '');
        $startDate = $this->systemModel->checkDate('empty', $_POST['start_date'], '', 'Y-m-d', '');
        $firstDueDate = $this->systemModel->checkDate('empty', $_POST['first_due_date'], '', 'Y-m-d', '');
        $termLength = htmlspecialchars($_POST['term_length'], ENT_QUOTES, 'UTF-8');
        $termType = htmlspecialchars($_POST['term_type'], ENT_QUOTES, 'UTF-8');
        $numberOfPayments = htmlspecialchars($_POST['number_of_payments'], ENT_QUOTES, 'UTF-8');
        $paymentFrequency = htmlspecialchars($_POST['payment_frequency'], ENT_QUOTES, 'UTF-8');
        $forRegistration = htmlspecialchars($_POST['for_registration'], ENT_QUOTES, 'UTF-8');
        $withCR = htmlspecialchars($_POST['with_cr'], ENT_QUOTES, 'UTF-8');
        $forTransfer = htmlspecialchars($_POST['for_transfer'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposal($salesProposalID, $salesProposalNumber, $customerID, $comakerID, $productID, $referredBy, $releaseDate, $startDate, $firstDueDate, $termLength, $termType, $numberOfPayments, $paymentFrequency, $forRegistration, $withCR, $forTransfer, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'customerID' => $this->securityModel->encryptData($customerID), 'salesProposalID' => $this->securityModel->encryptData($salesProposalID)]);
            exit;
        } 
        else {
            $salesProposalID = $this->salesProposalModel->insertSalesProposal($salesProposalNumber, $customerID, $comakerID, $productID, $referredBy, $releaseDate, $startDate, $firstDueDate, $termLength, $termType, $numberOfPayments, $paymentFrequency, $forRegistration, $withCR, $forTransfer, $remarks, $contactID, $userID);

            $this->systemSettingModel->updateSystemSettingValue(6, $salesProposalNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'customerID' => $this->securityModel->encryptData($customerID), 'salesProposalID' => $this->securityModel->encryptData($salesProposalID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalAccessories
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalAccessories() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAccessoriesID = htmlspecialchars($_POST['sales_proposal_accessories_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $accessories = htmlspecialchars($_POST['accessories'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAccessoriesExist = $this->salesProposalModel->checkSalesProposalAccessoriesExist($salesProposalAccessoriesID);
        $total = $checkSalesProposalAccessoriesExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalAccessories($salesProposalAccessoriesID, $salesProposalID, $accessories, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalAccessories($salesProposalID, $accessories, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalJobOrder
    # Description: 
    # Updates the existing sales proposal job order if it exists; otherwise, inserts a new sales proposal job order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalJobOrderID = htmlspecialchars($_POST['sales_proposal_job_order_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $jobOrder = htmlspecialchars($_POST['job_order'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalJobOrderExist = $this->salesProposalModel->checkSalesProposalJobOrderExist($salesProposalJobOrderID);
        $total = $checkSalesProposalJobOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalJobOrder($salesProposalJobOrderID, $salesProposalID, $jobOrder, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalJobOrder($salesProposalID, $jobOrder, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalAdditionalJobOrder
    # Description: 
    # Updates the existing sales proposal job order if it exists; otherwise, inserts a new sales proposal job order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAdditionalJobOrderID = htmlspecialchars($_POST['sales_proposal_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $jobOrderNumber = htmlspecialchars($_POST['job_order_number'], ENT_QUOTES, 'UTF-8');
        $jobOrderDate = $this->systemModel->checkDate('empty', $_POST['job_order_date'], '', 'Y-m-d', '');
        $particulars = htmlspecialchars($_POST['particulars'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAdditionalJobOrderExist = $this->salesProposalModel->checkSalesProposalAdditionalJobOrderExist($salesProposalAdditionalJobOrderID);
        $total = $checkSalesProposalAdditionalJobOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID, $salesProposalID, $jobOrderNumber, $particulars, $jobOrderDate, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalAdditionalJobOrder($salesProposalID, $jobOrderNumber, $jobOrderDate, $particulars, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAccessories
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAccessories() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAccessoriesID = htmlspecialchars($_POST['sales_proposal_accessories_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAccessoriesExist = $this->salesProposalModel->checkSalesProposalAccessoriesExist($salesProposalAccessoriesID);
        $total = $checkSalesProposalAccessoriesExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalAccessories($salesProposalAccessoriesID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalJobOrder
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalJobOrderID = htmlspecialchars($_POST['sales_proposal_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalJobOrderExist = $this->salesProposalModel->checkSalesProposalJobOrderExist($salesProposalJobOrderID);
        $total = $checkSalesProposalJobOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalJobOrder($salesProposalJobOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAdditionalJobOrder
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAdditionalJobOrderID = htmlspecialchars($_POST['sales_proposal_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAdditionalJobOrderExist = $this->salesProposalModel->checkSalesProposalAdditionalJobOrderExist($salesProposalAdditionalJobOrderID);
        $total = $checkSalesProposalAdditionalJobOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalDetails
    # Description: 
    # Handles the retrieval of product subcategory details such as product subcategory name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);

            $response = [
                'success' => true,
                'salesProposalNumber' => $salesProposalDetails['sales_proposal_number'],
                'comakerID' => $salesProposalDetails['comaker_id'],
                'productID' => $salesProposalDetails['product_id'],
                'referredBy' => $salesProposalDetails['referred_by'],
                'releaseDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['release_date'], '', 'm/d/Y', ''),
                'startDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['start_date'], '', 'm/d/Y', ''),
                'firstDueDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['first_due_date'], '', 'm/d/Y', ''),
                'termLength' => $salesProposalDetails['term_length'],
                'termType' => $salesProposalDetails['term_type'],
                'numberOfPayments' => $salesProposalDetails['number_of_payments'],
                'paymentFrequency' => $salesProposalDetails['payment_frequency'],
                'forRegistration' => $salesProposalDetails['for_registration'],
                'withCR' => $salesProposalDetails['with_cr'],
                'forTransfer' => $salesProposalDetails['for_transfer'],
                'remarks' => $salesProposalDetails['remarks']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAccessoriesDetails
    # Description: 
    # Handles the retrieval of sales proposal accessories details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAccessoriesDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_accessories_id']) && !empty($_POST['sales_proposal_accessories_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalAccessoriesID = $_POST['sales_proposal_accessories_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAccessoriesDetails = $this->salesProposalModel->getSalesProposalAccessories($salesProposalAccessoriesID);

            $response = [
                'success' => true,
                'accessories' => $salesProposalAccessoriesDetails['accessories'],
                'cost' => $salesProposalAccessoriesDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalJobOrderDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_job_order_id']) && !empty($_POST['sales_proposal_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalJobOrderID = $_POST['sales_proposal_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalJobOrderDetails = $this->salesProposalModel->getSalesProposalJobOrder($salesProposalJobOrderID);

            $response = [
                'success' => true,
                'jobOrder' => $salesProposalJobOrderDetails['job_order'],
                'cost' => $salesProposalJobOrderDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAdditionalJobOrderDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAdditionalJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_additional_job_order_id']) && !empty($_POST['sales_proposal_additional_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalAdditionalJobOrderID = $_POST['sales_proposal_additional_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAdditionalJobOrderDetails = $this->salesProposalModel->getSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID);

            $response = [
                'success' => true,
                'jobOrderNumber' => $salesProposalAdditionalJobOrderDetails['job_order_number'],
                'jobOrderDate' =>  $this->systemModel->checkDate('empty', $salesProposalAdditionalJobOrderDetails['job_order_date'], '', 'm/d/Y', ''),
                'particulars' =>  $salesProposalAdditionalJobOrderDetails['particulars'],
                'cost' => $salesProposalAdditionalJobOrderDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/customer-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/user-model.php';
require_once '../model/company-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new SalesProposalController(new SalesProposalModel(new DatabaseModel), new CustomerModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CompanyModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>