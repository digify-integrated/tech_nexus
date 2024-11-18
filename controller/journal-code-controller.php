<?php
session_start();

# -------------------------------------------------------------
#
# Function: JournalCodeController
# Description: 
# The JournalCodeController class handles journal code related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class JournalCodeController {
    private $journalCodeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided JournalCodeModel, UserModel and SecurityModel instances.
    # These instances are used for journal code related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param JournalCodeModel $journalCodeModel     The JournalCodeModel instance for journal code related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(JournalCodeModel $journalCodeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->journalCodeModel = $journalCodeModel;
        $this->userModel = $userModel;
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
                case 'save journal code':
                    $this->saveJournalCode();
                    break;
                case 'get journal code details':
                    $this->getJournalCodeDetails();
                    break;
                case 'delete journal code':
                    $this->deleteJournalCode();
                    break;
                case 'delete multiple journal code':
                    $this->deleteMultipleJournalCode();
                    break;
                case 'duplicate journal code':
                    $this->duplicateJournalCode();
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
    # Function: saveJournalCode
    # Description: 
    # Updates the existing journal code if it exists; otherwise, inserts a new journal code.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveJournalCode() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $journalCodeID = isset($_POST['journal_code_id']) ? htmlspecialchars($_POST['journal_code_id'], ENT_QUOTES, 'UTF-8') : null;
        $company_id = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $transaction_type = htmlspecialchars($_POST['transaction_type'], ENT_QUOTES, 'UTF-8');
        $product_type_id = $_POST['product_type_id'];
        $jtransaction = $_POST['jtransaction'];
        $item = $_POST['item'];
        $debit = $_POST['debit'];
        $credit = $_POST['credit'];

        $reference_code = $company_id . $transaction_type . $product_type_id . $jtransaction . $item;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJournalCodeExist = $this->journalCodeModel->checkJournalCodeExist($journalCodeID);
        $total = $checkJournalCodeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->journalCodeModel->updateJournalCode($journalCodeID, $company_id, $transaction_type, $product_type_id, $jtransaction, $item, $debit, $credit, $reference_code, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'journalCodeID' => $this->securityModel->encryptData($journalCodeID)]);
            exit;
        } 
        else {
            $journalCodeID = $this->journalCodeModel->insertJournalCode($company_id, $transaction_type, $product_type_id, $jtransaction, $item, $debit, $credit, $reference_code, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'journalCodeID' => $this->securityModel->encryptData($journalCodeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJournalCode
    # Description: 
    # Delete the journal code if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteJournalCode() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $journalCodeID = htmlspecialchars($_POST['journal_code_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJournalCodeExist = $this->journalCodeModel->checkJournalCodeExist($journalCodeID);
        $total = $checkJournalCodeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->journalCodeModel->deleteJournalCode($journalCodeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleJournalCode
    # Description: 
    # Delete the selected journal codes if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleJournalCode() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $journalCodeIDs = $_POST['journal_code_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($journalCodeIDs as $journalCodeID){
            $this->journalCodeModel->deleteJournalCode($journalCodeID);
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
    # Function: duplicateJournalCode
    # Description: 
    # Duplicates the journal code if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateJournalCode() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $journalCodeID = htmlspecialchars($_POST['journal_code_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJournalCodeExist = $this->journalCodeModel->checkJournalCodeExist($journalCodeID);
        $total = $checkJournalCodeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $journalCodeID = $this->journalCodeModel->duplicateJournalCode($journalCodeID, $userID);

        echo json_encode(['success' => true, 'journalCodeID' =>  $this->securityModel->encryptData($journalCodeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJournalCodeDetails
    # Description: 
    # Handles the retrieval of journal code details such as journal code name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getJournalCodeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['journal_code_id']) && !empty($_POST['journal_code_id'])) {
            $userID = $_SESSION['user_id'];
            $journalCodeID = $_POST['journal_code_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $journalCodeDetails = $this->journalCodeModel->getJournalCode($journalCodeID);

            $response = [
                'success' => true,
                'companyID' => $journalCodeDetails['company_id'],
                'transactionType' => $journalCodeDetails['transaction_type'],
                'productTypeID' => $journalCodeDetails['product_type_id'],
                'jtransaction' => $journalCodeDetails['transaction'],
                'item' => $journalCodeDetails['item'],
                'debit' => $journalCodeDetails['debit'],
                'credit' => $journalCodeDetails['credit'],
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
require_once '../model/journal-code-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new JournalCodeController(new JournalCodeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>