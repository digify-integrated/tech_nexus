<?php
session_start();

# -------------------------------------------------------------
#
# Function: DisbursementController
# Description: 
# The DisbursementController class handles disbursement related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/disbursement-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/company-model.php';
require_once '../model/system-model.php';

$controller = new DisbursementController(new DisbursementModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();

class DisbursementController {
    private $disbursementModel;
    private $salesProposalModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemSettingModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided DisbursementModel, UserModel and SecurityModel instances.
    # These instances are used for disbursement related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DisbursementModel $disbursementModel     The DisbursementModel instance for disbursement related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DisbursementModel $disbursementModel, SalesProposalModel $salesProposalModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->disbursementModel = $disbursementModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->securityModel = $securityModel;
        $this->systemModel = $systemModel;
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
                case 'save disbursement':
                    $this->saveDisbursement();
                    break;
                case 'save particulars':
                    $this->saveParticulars();
                    break;
                case 'get disbursement details':
                    $this->getDisbursementDetails();
                    break;
                case 'get disbursement particulars details':
                    $this->getDisbursementParticularsDetails();
                    break;
                case 'delete disbursement':
                    $this->deleteDisbursement();
                    break;
                case 'delete disbursement particulars':
                    $this->deleteDisbursementParticulars();
                    break;
                case 'delete multiple disbursement':
                    $this->deleteMultipleDisbursement();
                    break;
                case 'post disbursement':
                    $this->tagDisbursementAsPosted();
                    break;
                case 'tag disbursement as cancelled':
                    $this->tagDisbursementAsCancel();
                    break;
                case 'tag disbursement as reversed':
                    $this->tagDisbursementAsReversed();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDisbursementAsPosted
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDisbursementAsPosted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
        $fund_source = $disbursementDetails['fund_source'];
        $transaction_type = $disbursementDetails['transaction_type'];
        $transaction_number = $disbursementDetails['transaction_number'];

        $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
        
        if($disbursementTotal === 0){
            echo json_encode(['success' => false, 'disbursementZero' => true]);
            exit;
        }

        if($transaction_type === 'Disbursement'){
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
            }
        }
        else{
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
            }
        }

        $this->disbursementModel->createDisbursementEntry($disbursementID, $transaction_number, $fund_source, 'posted', $userID);
        $this->disbursementModel->createLiquidation($disbursementID, $userID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDisbursementAsCancel
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDisbursementAsCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = $_POST['cancellation_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Cancelled', $cancellationReason, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDisbursementAsReversed
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDisbursementAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars( $_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
        $reversalRemarks = $_POST['reversal_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
        $fund_source = $disbursementDetails['fund_source'];
        $transaction_type = $disbursementDetails['transaction_type'];
        $transaction_number = $disbursementDetails['transaction_number'];

        if ($transaction_type === 'Disbursement') {
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
            }    
        }
        else{
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
            }    
        }

        $this->disbursementModel->createDisbursementEntry($disbursementID, $transaction_number, $fund_source, 'reversed', $userID);
                
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveDisbursement
    # Description: 
    # Updates the existing disbursement if it exists; otherwise, inserts a new disbursement.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = isset($_POST['disbursement_id']) ? htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8') : null;
        $transaction_type = $_POST['transaction_type'];
        $fund_source = $_POST['fund_source'];
        $particulars = $_POST['particulars'];
        $customer_id = $_POST['customer_id'];
        $department_id = $_POST['department_id'];
        $company_id = $_POST['company_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;
    
        if ($total > 0) {
            $transaction_number = $_POST['transaction_number'];

            $this->disbursementModel->updateDisbursement($disbursementID, $customer_id, $department_id, $company_id, $transaction_number, $transaction_type, $fund_source, $particulars, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'disbursementID' => $this->securityModel->encryptData($disbursementID)]);
            exit;
        } 
        else {
            $transaction_number = $this->systemSettingModel->getSystemSetting(19)['value'] + 1;

            $disbursementID = $this->disbursementModel->insertDisbursement($customer_id, $department_id, $company_id, $transaction_number, $transaction_type, $fund_source, $particulars, $userID);

            
            $this->systemSettingModel->updateSystemSettingValue(19, $transaction_number, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'disbursementID' => $this->securityModel->encryptData($disbursementID)]);
            exit;
        }
    }
    public function saveParticulars() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_particulars_id = isset($_POST['disbursement_particulars_id']) ? htmlspecialchars($_POST['disbursement_particulars_id'], ENT_QUOTES, 'UTF-8') : null;
        $disbursement_id = $_POST['disbursement_id'];
        $chart_of_account_id = $_POST['chart_of_account_id'];
        $particulars_amount = $_POST['particulars_amount'];
        $remarks = $_POST['remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementParticularsExist($disbursement_particulars_id);
        $total = $checkDisbursementExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->disbursementModel->updateDisbursementParticulars($disbursement_particulars_id, $disbursement_id, $chart_of_account_id, $remarks, $particulars_amount, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->disbursementModel->insertDisbursementParticulars($disbursement_id, $chart_of_account_id, $remarks, $particulars_amount, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDisbursement
    # Description: 
    # Delete the disbursement if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->deleteDisbursement($disbursementID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteDisbursementParticulars() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_particulars_id = htmlspecialchars($_POST['disbursement_particulars_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementParticularsExist = $this->disbursementModel->checkDisbursementParticularsExist($disbursement_particulars_id);
        $total = $checkDisbursementParticularsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->deleteDisbursementParticulars($disbursement_particulars_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDisbursement
    # Description: 
    # Delete the selected disbursements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementIDs = $_POST['disbursement_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($disbursementIDs as $disbursementID){
            $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
            $paymentAmount = $disbursementDetails['payment_amount'];
            $companyID = $disbursementDetails['company_id'];

            if($companyID == 1){
                $systemSettingID = 11;
            }
            else if($companyID == 2){
                $systemSettingID = 12;
            }
            else{
                $systemSettingID = 13;
            }
    
            $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $paymentAmount;
    
            $this->disbursementModel->deleteDisbursement($disbursementID);
                        
            $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDisbursementDetails
    # Description: 
    # Handles the retrieval of disbursement details such as disbursement name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDisbursementDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['disbursement_id']) && !empty($_POST['disbursement_id'])) {
            $userID = $_SESSION['user_id'];
            $disbursementID = $_POST['disbursement_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);

            $response = [
                'success' => true,
                'transactionNumber' => $disbursementDetails['transaction_number'],
                'particulars' => $disbursementDetails['particulars'],
                'transactionType' => $disbursementDetails['transaction_type'],
                'customerID' => $disbursementDetails['customer_id'],
                'departmentID' => $disbursementDetails['department_id'],
                'companyID' => $disbursementDetails['company_id'],
                'fundSource' => $disbursementDetails['fund_source']
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getDisbursementParticularsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['disbursement_particulars_id']) && !empty($_POST['disbursement_particulars_id'])) {
            $userID = $_SESSION['user_id'];
            $disbursement_particulars_id = $_POST['disbursement_particulars_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $disbursementDetails = $this->disbursementModel->getDisbursementParticulars($disbursement_particulars_id);

            $response = [
                'success' => true,
                'chartOfAccountID' => $disbursementDetails['chart_of_account_id'],
                'remarks' => $disbursementDetails['remarks'],
                'particularsAmount' => $disbursementDetails['particulars_amount']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------


?>