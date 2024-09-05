<?php
session_start();

# -------------------------------------------------------------
#
# Function: DepositsController
# Description: 
# The DepositsController class handles deposits related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DepositsController {
    private $depositsModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemSettingModel;
    private $companyModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided DepositsModel, UserModel and SecurityModel instances.
    # These instances are used for deposits related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DepositsModel $depositsModel     The DepositsModel instance for deposits related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DepositsModel $depositsModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, CompanyModel $companyModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->depositsModel = $depositsModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
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
                case 'save deposits':
                    $this->saveDeposits();
                    break;
                case 'get deposits details':
                    $this->getDepositsDetails();
                    break;
                case 'delete deposits':
                    $this->deleteDeposits();
                    break;
                case 'delete multiple deposits':
                    $this->deleteMultipleDeposits();
                    break;
                case 'tag collection as cleared':
                    $this->tagDepositsAsCleared();
                    break;
                case 'tag multiple collection as cleared':
                    $this->tagMultiplePDCAsCleared();
                    break;
                case 'tag collection as cancelled':
                    $this->tagDepositsAsCancel();
                    break;
                case 'tag multiple pdc as cancelled':
                    $this->tagMultiplePDCAsCancel();
                    break;
                case 'tag collection as reversed':
                    $this->tagDepositsAsReversed();
                    break;
                case 'tag multiple pdc as reversed':
                    $this->tagMultiplePDCAsReversed();
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
    # Function: tagDepositsAsCleared
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDepositsAsCleared() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsID = htmlspecialchars($_POST['deposits_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepositsExist = $this->depositsModel->checkDepositsExist($depositsID);
        $total = $checkDepositsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->depositsModel->updateDepositsStatus($depositsID, 'Cleared', '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsCleared
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsCleared() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsIDs = $_POST['deposits_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($depositsIDs as $depositsID){
            $this->depositsModel->updateDepositsStatus($depositsID, 'Cleared', '', '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDepositsAsCancel
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDepositsAsCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsID = htmlspecialchars($_POST['deposits_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = $_POST['cancellation_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepositsExist = $this->depositsModel->checkDepositsExist($depositsID);
        $total = $checkDepositsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->depositsModel->updateDepositsStatus($depositsID, 'Cancelled', $cancellationReason, '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsCancel
    # Description: 
    # Delete the selected depositss if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsID = $_POST['deposits_id']; 
        $depositsIDs = explode(',', $depositsID);
        $cancellationReason = $_POST['cancellation_reason'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($depositsIDs as $depositsID){
            $this->depositsModel->updateDepositsStatus($depositsID, 'Cancelled', $cancellationReason, '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDepositsAsReversed
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDepositsAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsID = htmlspecialchars($_POST['deposits_id'], ENT_QUOTES, 'UTF-8');
        $reversalReason = $_POST['reversal_reason'];
        $reversalRemarks = $_POST['reversal_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepositsExist = $this->depositsModel->checkDepositsExist($depositsID);
        $total = $checkDepositsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $referenceNumber = $this->systemSettingModel->getSystemSetting(10)['value'] + 1;

        $this->depositsModel->updateDepositsStatus($depositsID, 'Reversed', $reversalReason, $reversalRemarks, $referenceNumber, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsReversed
    # Description: 
    # Delete the selected depositss if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsID = $_POST['deposits_id']; 
        $depositsIDs = explode(',', $depositsID);
        $reversalReason = $_POST['reversal_reason'];
        $reversalRemarks = $_POST['reversal_remarks'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($depositsIDs as $depositsID){
            $checkDepositsExist = $this->depositsModel->checkDepositsExist($depositsID);
            $total = $checkDepositsExist['total'] ?? 0;

            if($total > 0){
                $referenceNumber = $this->systemSettingModel->getSystemSetting(10)['value'] + 1;

                $this->depositsModel->updateDepositsStatus($depositsID, 'Reversed', $reversalReason, $reversalRemarks, $referenceNumber, $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveDeposits
    # Description: 
    # Updates the existing deposits if it exists; otherwise, inserts a new deposits.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDeposits() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositAmount = htmlspecialchars($_POST['deposit_amount'], ENT_QUOTES, 'UTF-8');
        $referenceNumber = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
        $depositedTo = htmlspecialchars($_POST['deposited_to'], ENT_QUOTES, 'UTF-8');
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $remarks = $_POST['remarks'];
        $depositDate = $this->systemModel->checkDate('empty', $_POST['deposit_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($companyID == 1){
            $systemSettingID = 11;
        }
        else if($companyID == 2){
            $systemSettingID = 12;
        }
        else{
            $systemSettingID = 13;
        }
    
        $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $depositAmount;

        $depositsID = $this->depositsModel->insertDeposits($companyID, $depositAmount, $depositDate, $depositedTo, $referenceNumber, $remarks, $userID);
                
        $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'depositsID' => $this->securityModel->encryptData($depositsID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDeposits
    # Description: 
    # Delete the deposits if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDeposits() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsID = htmlspecialchars($_POST['deposits_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepositsExist = $this->depositsModel->checkDepositsExist($depositsID);
        $total = $checkDepositsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $depositsDetails = $this->depositsModel->getDeposits($depositsID);
        $depositAmount = $depositsDetails['deposit_amount'] ?? 0;
        $companyID = $depositsDetails['company_id'] ?? 0;

        if($companyID == 1){
            $systemSettingID = 11;
        }
        else if($companyID == 2){
            $systemSettingID = 12;
        }
        else{
            $systemSettingID = 13;
        }
    
        $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $depositAmount;

        $this->depositsModel->deleteDeposits($depositsID);
                
        $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDeposits
    # Description: 
    # Delete the selected depositss if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDeposits() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $depositsIDs = $_POST['deposits_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($depositsIDs as $depositsID){
            $this->depositsModel->deleteDeposits($depositsID);
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
    # Function: getDepositsDetails
    # Description: 
    # Handles the retrieval of deposits details such as deposits name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDepositsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['deposits_id']) && !empty($_POST['deposits_id'])) {
            $userID = $_SESSION['user_id'];
            $depositsID = $_POST['deposits_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $depositsDetails = $this->depositsModel->getDeposits($depositsID);

            $response = [
                'success' => true,
                'companyID' => $depositsDetails['company_id'],
                'depositAmount' => $depositsDetails['deposit_amount'],
                'depositedTo' => $depositsDetails['deposited_to'],
                'referenceNumber' => $depositsDetails['reference_number'],
                'remarks' => $depositsDetails['remarks'],
                'depositDate' =>  $this->systemModel->checkDate('empty', $depositsDetails['deposit_date'], '', 'm/d/Y', ''),
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
require_once '../model/deposits-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/company-model.php';
require_once '../model/system-model.php';

$controller = new DepositsController(new DepositsModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new CompanyModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>