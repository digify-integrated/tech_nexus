<?php
session_start();

# -------------------------------------------------------------
#
# Function: BankADBController
# Description: 
# The BankADBController class handles bank adb related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BankADBController {
    private $bankADBModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BankADBModel, UserModel and SecurityModel instances.
    # These instances are used for bank adb related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BankADBModel $bankADBModel     The BankADBModel instance for bank adb related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BankADBModel $bankADBModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->bankADBModel = $bankADBModel;
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
                case 'save bank adb':
                    $this->saveBankADB();
                    break;
                case 'get bank adb details':
                    $this->getBankADBDetails();
                    break;
                case 'delete bank adb':
                    $this->deleteBankADB();
                    break;
                case 'delete multiple bank adb':
                    $this->deleteMultipleBankADB();
                    break;
                case 'duplicate bank adb':
                    $this->duplicateBankADB();
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
    # Function: saveBankADB
    # Description: 
    # Updates the existing bank adb if it exists; otherwise, inserts a new bank adb.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBankADB() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankADBID = isset($_POST['bank_adb_id']) ? htmlspecialchars($_POST['bank_adb_id'], ENT_QUOTES, 'UTF-8') : null;
        $bankADBName = htmlspecialchars($_POST['bank_adb_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankADBExist = $this->bankADBModel->checkBankADBExist($bankADBID);
        $total = $checkBankADBExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->bankADBModel->updateBankADB($bankADBID, $bankADBName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'bankADBID' => $this->securityModel->encryptData($bankADBID)]);
            exit;
        } 
        else {
            $bankADBID = $this->bankADBModel->insertBankADB($bankADBName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'bankADBID' => $this->securityModel->encryptData($bankADBID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBankADB
    # Description: 
    # Delete the bank adb if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBankADB() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankADBID = htmlspecialchars($_POST['bank_adb_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankADBExist = $this->bankADBModel->checkBankADBExist($bankADBID);
        $total = $checkBankADBExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->bankADBModel->deleteBankADB($bankADBID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBankADB
    # Description: 
    # Delete the selected bank adbs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBankADB() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankADBIDs = $_POST['bank_adb_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($bankADBIDs as $bankADBID){
            $this->bankADBModel->deleteBankADB($bankADBID);
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
    # Function: duplicateBankADB
    # Description: 
    # Duplicates the bank adb if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBankADB() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankADBID = htmlspecialchars($_POST['bank_adb_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankADBExist = $this->bankADBModel->checkBankADBExist($bankADBID);
        $total = $checkBankADBExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $bankADBID = $this->bankADBModel->duplicateBankADB($bankADBID, $userID);

        echo json_encode(['success' => true, 'bankADBID' =>  $this->securityModel->encryptData($bankADBID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBankADBDetails
    # Description: 
    # Handles the retrieval of bank adb details such as bank adb name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBankADBDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['bank_adb_id']) && !empty($_POST['bank_adb_id'])) {
            $userID = $_SESSION['user_id'];
            $bankADBID = $_POST['bank_adb_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $bankADBDetails = $this->bankADBModel->getBankADB($bankADBID);

            $response = [
                'success' => true,
                'bankADBName' => $bankADBDetails['bank_adb_name']
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
require_once '../model/bank-adb-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BankADBController(new BankADBModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>