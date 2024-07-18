<?php
session_start();

# -------------------------------------------------------------
#
# Function: PDCManagementController
# Description: 
# The PDCManagementController class handles pdc management related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PDCManagementController {
    private $pdcManagementModel;
    private $salesProposalModel;
    private $userModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PDCManagementModel, UserModel and SecurityModel instances.
    # These instances are used for pdc management related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PDCManagementModel $pdcManagementModel     The PDCManagementModel instance for pdc management related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PDCManagementModel $pdcManagementModel, SalesProposalModel $salesProposalModel, UserModel $userModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->pdcManagementModel = $pdcManagementModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->userModel = $userModel;
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
                case 'save pdc management':
                    $this->savePDCManagement();
                    break;
                case 'get pdc management details':
                    $this->getPDCManagementDetails();
                    break;
                case 'delete pdc management':
                    $this->deletePDCManagement();
                    break;
                case 'delete multiple pdc management':
                    $this->deleteMultiplePDCManagement();
                    break;
                case 'tag pdc as deposited':
                    $this->tagPDCAsDeposited();
                    break;
                case 'tag multiple pdc as deposited':
                    $this->tagMultiplePDCAsDeposited();
                    break;
                case 'tag pdc as for deposit':
                    $this->tagPDCAsForDeposit();
                    break;
                case 'tag multiple pdc as for deposit':
                    $this->tagMultiplePDCAsForDeposit();
                    break;
                case 'tag pdc as cleared':
                    $this->tagPDCAsCleared();
                    break;
                case 'tag multiple pdc as cleared':
                    $this->tagMultiplePDCAsCleared();
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
    # Function: tagPDCAsDeposited
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsDeposited() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionsID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionsID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionsID, 'Deposited', '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsDeposited
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsDeposited() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionsIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionsIDs as $loanCollectionsID){
            $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionsID, 'Deposited', '', '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsForDeposit
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsForDeposit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionsID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionsID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionsID, 'For Deposit', '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsForDeposit
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsForDeposit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionsIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionsIDs as $loanCollectionsID){
            $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionsID, 'For Deposit', '', '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsCleared
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsCleared() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionsID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionsID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionsID, 'Cleared', '', '', '', $userID);
            
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
        $loanCollectionsIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionsIDs as $loanCollectionsID){
            $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionsID, 'Cleared', '', '', '', $userID);
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
    # Function: savePDCManagement
    # Description: 
    # Updates the existing pdc management if it exists; otherwise, inserts a new pdc management.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = isset($_POST['loan_collection_id']) ? htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8') : null;
        $salesProposalID = htmlspecialchars($_POST['loan_number'], ENT_QUOTES, 'UTF-8');
        $paymentDetails = $_POST['payment_details'];
        $checkNumber = $_POST['check_number'];
        $paymentAmount = $_POST['payment_amount'];
        $bankBranch = $_POST['bank_branch'];
        $checkDate = $this->systemModel->checkDate('empty', $_POST['check_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
        $loanNumber = $salesProposalDetails['loan_number'];
    
        if ($total > 0) {
            $this->pdcManagementModel->updatePDCManagement($loanCollectionID, $salesProposalID, $loanNumber, $checkNumber, $checkDate, $paymentAmount, $paymentDetails, $bankBranch, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'loanCollectionID' => $this->securityModel->encryptData($loanCollectionID)]);
            exit;
        } 
        else {
            $checkLoanCollectionConflict = $this->pdcManagementModel->checkLoanCollectionConflict($salesProposalID, $checkNumber);
            $total = $checkLoanCollectionConflict['total'] ?? 0;

            if ($total == 0) {
                $loanCollectionID = $this->pdcManagementModel->insertPDCManagement($salesProposalID, $loanNumber, $checkNumber, $checkDate, $paymentAmount, $paymentDetails, $bankBranch, $userID);

                echo json_encode(['success' => true, 'insertRecord' => true, 'loanCollectionID' => $this->securityModel->encryptData($loanCollectionID)]);
                exit;
            }
            else{
                echo json_encode(['success' => false, 'checkConflict' => true]);
                exit;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateMailPassword
    # Description: 
    # Handles the update of the password.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateMailPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pdcManagementID = htmlspecialchars($_POST['email_setting_id'], ENT_QUOTES, 'UTF-8');
        $newPassword = htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8');
        $encryptedPassword = $this->securityModel->encryptData($newPassword);
    
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->pdcManagementModel->updateMailPassword($pdcManagementID, $encryptedPassword, $userID);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePDCManagement
    # Description: 
    # Delete the pdc management if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pdcManagementID = htmlspecialchars($_POST['email_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPDCManagementExist = $this->pdcManagementModel->checkPDCManagementExist($pdcManagementID);
        $total = $checkPDCManagementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->deletePDCManagement($pdcManagementID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePDCManagement
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pdcManagementIDs = $_POST['email_setting_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($pdcManagementIDs as $pdcManagementID){
            $this->pdcManagementModel->deletePDCManagement($pdcManagementID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicatePDCManagement
    # Description: 
    # Duplicates the pdc management if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicatePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pdcManagementID = htmlspecialchars($_POST['email_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPDCManagementExist = $this->pdcManagementModel->checkPDCManagementExist($pdcManagementID);
        $total = $checkPDCManagementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $pdcManagementID = $this->pdcManagementModel->duplicatePDCManagement($pdcManagementID, $userID);

        echo json_encode(['success' => true, 'pdcManagementID' =>  $this->securityModel->encryptData($pdcManagementID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPDCManagementDetails
    # Description: 
    # Handles the retrieval of pdc management details such as pdc management name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPDCManagementDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])) {
            $userID = $_SESSION['user_id'];
            $pdcManagementID = $_POST['email_setting_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $pdcManagementDetails = $this->pdcManagementModel->getPDCManagement($pdcManagementID);
            $smtpAuth = $pdcManagementDetails['smtp_auth'];
            $smtoAutoTLS = $pdcManagementDetails['smtp_auto_tls'];
            $smtpAuthName = ($smtpAuth == 0) ? 'False' : 'True';
            $smtoAutoTLSName = ($smtoAutoTLS == 0) ? 'False' : 'True';

            $response = [
                'success' => true,
                'pdcManagementName' => $pdcManagementDetails['email_setting_name'],
                'pdcManagementDescription' => $pdcManagementDetails['email_setting_description'],
                'mailHost' => $pdcManagementDetails['mail_host'],
                'port' => $pdcManagementDetails['port'],
                'smtpAuth' => $smtpAuth,
                'smtoAutoTLS' => $smtoAutoTLS,
                'smtpAuthName' => $smtpAuthName,
                'smtoAutoTLSName' => $smtoAutoTLSName,
                'mailUsername' => $pdcManagementDetails['mail_username'],
                'mailEncryption' => $pdcManagementDetails['mail_encryption'],
                'mailFromName' => $pdcManagementDetails['mail_from_name'],
                'mailFromEmail' => $pdcManagementDetails['mail_from_email']
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
require_once '../model/pdc-management-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/system-model.php';

$controller = new PDCManagementController(new PDCManagementModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>