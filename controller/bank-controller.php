<?php
session_start();

# -------------------------------------------------------------
#
# Function: BankController
# Description: 
# The BankController class handles bank related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BankController {
    private $bankModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BankModel, UserModel and SecurityModel instances.
    # These instances are used for bank related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BankModel $bankModel     The BankModel instance for bank related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BankModel $bankModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->bankModel = $bankModel;
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
                case 'save bank':
                    $this->saveBank();
                    break;
                case 'get bank details':
                    $this->getBankDetails();
                    break;
                case 'delete bank':
                    $this->deleteBank();
                    break;
                case 'delete multiple bank':
                    $this->deleteMultipleBank();
                    break;
                case 'duplicate bank':
                    $this->duplicateBank();
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
    # Function: saveBank
    # Description: 
    # Updates the existing bank if it exists; otherwise, inserts a new bank.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBank() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankID = isset($_POST['bank_id']) ? htmlspecialchars($_POST['bank_id'], ENT_QUOTES, 'UTF-8') : null;
        $bankName = htmlspecialchars($_POST['bank_name'], ENT_QUOTES, 'UTF-8');
        $bankIdentifierCode = htmlspecialchars($_POST['bank_identifier_code'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankExist = $this->bankModel->checkBankExist($bankID);
        $total = $checkBankExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->bankModel->updateBank($bankID, $bankName, $bankIdentifierCode, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'bankID' => $this->securityModel->encryptData($bankID)]);
            exit;
        } 
        else {
            $bankID = $this->bankModel->insertBank($bankName, $bankIdentifierCode, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'bankID' => $this->securityModel->encryptData($bankID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBank
    # Description: 
    # Delete the bank if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBank() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankID = htmlspecialchars($_POST['bank_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankExist = $this->bankModel->checkBankExist($bankID);
        $total = $checkBankExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->bankModel->deleteBank($bankID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBank
    # Description: 
    # Delete the selected banks if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBank() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankIDs = $_POST['bank_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($bankIDs as $bankID){
            $this->bankModel->deleteBank($bankID);
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
    # Function: duplicateBank
    # Description: 
    # Duplicates the bank if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBank() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankID = htmlspecialchars($_POST['bank_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankExist = $this->bankModel->checkBankExist($bankID);
        $total = $checkBankExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $bankID = $this->bankModel->duplicateBank($bankID, $userID);

        echo json_encode(['success' => true, 'bankID' =>  $this->securityModel->encryptData($bankID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBankDetails
    # Description: 
    # Handles the retrieval of bank details such as bank name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBankDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['bank_id']) && !empty($_POST['bank_id'])) {
            $userID = $_SESSION['user_id'];
            $bankID = $_POST['bank_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $bankDetails = $this->bankModel->getBank($bankID);

            $response = [
                'success' => true,
                'bankName' => $bankDetails['bank_name'],
                'bankIdentifierCode' => $bankDetails['bank_identifier_code']
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
require_once '../model/bank-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BankController(new BankModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>