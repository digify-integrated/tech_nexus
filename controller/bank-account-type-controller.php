<?php
session_start();

# -------------------------------------------------------------
#
# Function: BankAccountTypeController
# Description: 
# The BankAccountTypeController class handles bank account type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BankAccountTypeController {
    private $bankAccountTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BankAccountTypeModel, UserModel and SecurityModel instances.
    # These instances are used for bank account type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BankAccountTypeModel $bankAccountTypeModel     The BankAccountTypeModel instance for bank account type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BankAccountTypeModel $bankAccountTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->bankAccountTypeModel = $bankAccountTypeModel;
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
                case 'save bank account type':
                    $this->saveBankAccountType();
                    break;
                case 'get bank account type details':
                    $this->getBankAccountTypeDetails();
                    break;
                case 'delete bank account type':
                    $this->deleteBankAccountType();
                    break;
                case 'delete multiple bank account type':
                    $this->deleteMultipleBankAccountType();
                    break;
                case 'duplicate bank account type':
                    $this->duplicateBankAccountType();
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
    # Function: saveBankAccountType
    # Description: 
    # Updates the existing bank account type if it exists; otherwise, inserts a new bank account type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBankAccountType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankAccountTypeID = isset($_POST['bank_account_type_id']) ? htmlspecialchars($_POST['bank_account_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $bankAccountTypeName = htmlspecialchars($_POST['bank_account_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankAccountTypeExist = $this->bankAccountTypeModel->checkBankAccountTypeExist($bankAccountTypeID);
        $total = $checkBankAccountTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->bankAccountTypeModel->updateBankAccountType($bankAccountTypeID, $bankAccountTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'bankAccountTypeID' => $this->securityModel->encryptData($bankAccountTypeID)]);
            exit;
        } 
        else {
            $bankAccountTypeID = $this->bankAccountTypeModel->insertBankAccountType($bankAccountTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'bankAccountTypeID' => $this->securityModel->encryptData($bankAccountTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBankAccountType
    # Description: 
    # Delete the bank account type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBankAccountType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankAccountTypeID = htmlspecialchars($_POST['bank_account_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankAccountTypeExist = $this->bankAccountTypeModel->checkBankAccountTypeExist($bankAccountTypeID);
        $total = $checkBankAccountTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->bankAccountTypeModel->deleteBankAccountType($bankAccountTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBankAccountType
    # Description: 
    # Delete the selected bank account types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBankAccountType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankAccountTypeIDs = $_POST['bank_account_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($bankAccountTypeIDs as $bankAccountTypeID){
            $this->bankAccountTypeModel->deleteBankAccountType($bankAccountTypeID);
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
    # Function: duplicateBankAccountType
    # Description: 
    # Duplicates the bank account type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBankAccountType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankAccountTypeID = htmlspecialchars($_POST['bank_account_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankAccountTypeExist = $this->bankAccountTypeModel->checkBankAccountTypeExist($bankAccountTypeID);
        $total = $checkBankAccountTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $bankAccountTypeID = $this->bankAccountTypeModel->duplicateBankAccountType($bankAccountTypeID, $userID);

        echo json_encode(['success' => true, 'bankAccountTypeID' =>  $this->securityModel->encryptData($bankAccountTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBankAccountTypeDetails
    # Description: 
    # Handles the retrieval of bank account type details such as bank account type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBankAccountTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['bank_account_type_id']) && !empty($_POST['bank_account_type_id'])) {
            $userID = $_SESSION['user_id'];
            $bankAccountTypeID = $_POST['bank_account_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $bankAccountTypeDetails = $this->bankAccountTypeModel->getBankAccountType($bankAccountTypeID);

            $response = [
                'success' => true,
                'bankAccountTypeName' => $bankAccountTypeDetails['bank_account_type_name']
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
require_once '../model/bank-account-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BankAccountTypeController(new BankAccountTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>