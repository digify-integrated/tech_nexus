<?php
session_start();

# -------------------------------------------------------------
#
# Function: BankHandlingTypeController
# Description: 
# The BankHandlingTypeController class handles bank handling type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BankHandlingTypeController {
    private $bankHandlingTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BankHandlingTypeModel, UserModel and SecurityModel instances.
    # These instances are used for bank handling type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BankHandlingTypeModel $bankHandlingTypeModel     The BankHandlingTypeModel instance for bank handling type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BankHandlingTypeModel $bankHandlingTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->bankHandlingTypeModel = $bankHandlingTypeModel;
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
                case 'save bank handling type':
                    $this->saveBankHandlingType();
                    break;
                case 'get bank handling type details':
                    $this->getBankHandlingTypeDetails();
                    break;
                case 'delete bank handling type':
                    $this->deleteBankHandlingType();
                    break;
                case 'delete multiple bank handling type':
                    $this->deleteMultipleBankHandlingType();
                    break;
                case 'duplicate bank handling type':
                    $this->duplicateBankHandlingType();
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
    # Function: saveBankHandlingType
    # Description: 
    # Updates the existing bank handling type if it exists; otherwise, inserts a new bank handling type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBankHandlingType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankHandlingTypeID = isset($_POST['bank_handling_type_id']) ? htmlspecialchars($_POST['bank_handling_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $bankHandlingTypeName = htmlspecialchars($_POST['bank_handling_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankHandlingTypeExist = $this->bankHandlingTypeModel->checkBankHandlingTypeExist($bankHandlingTypeID);
        $total = $checkBankHandlingTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->bankHandlingTypeModel->updateBankHandlingType($bankHandlingTypeID, $bankHandlingTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'bankHandlingTypeID' => $this->securityModel->encryptData($bankHandlingTypeID)]);
            exit;
        } 
        else {
            $bankHandlingTypeID = $this->bankHandlingTypeModel->insertBankHandlingType($bankHandlingTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'bankHandlingTypeID' => $this->securityModel->encryptData($bankHandlingTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBankHandlingType
    # Description: 
    # Delete the bank handling type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBankHandlingType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankHandlingTypeID = htmlspecialchars($_POST['bank_handling_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankHandlingTypeExist = $this->bankHandlingTypeModel->checkBankHandlingTypeExist($bankHandlingTypeID);
        $total = $checkBankHandlingTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->bankHandlingTypeModel->deleteBankHandlingType($bankHandlingTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBankHandlingType
    # Description: 
    # Delete the selected bank handling types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBankHandlingType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankHandlingTypeIDs = $_POST['bank_handling_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($bankHandlingTypeIDs as $bankHandlingTypeID){
            $this->bankHandlingTypeModel->deleteBankHandlingType($bankHandlingTypeID);
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
    # Function: duplicateBankHandlingType
    # Description: 
    # Duplicates the bank handling type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBankHandlingType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bankHandlingTypeID = htmlspecialchars($_POST['bank_handling_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBankHandlingTypeExist = $this->bankHandlingTypeModel->checkBankHandlingTypeExist($bankHandlingTypeID);
        $total = $checkBankHandlingTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $bankHandlingTypeID = $this->bankHandlingTypeModel->duplicateBankHandlingType($bankHandlingTypeID, $userID);

        echo json_encode(['success' => true, 'bankHandlingTypeID' =>  $this->securityModel->encryptData($bankHandlingTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBankHandlingTypeDetails
    # Description: 
    # Handles the retrieval of bank handling type details such as bank handling type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBankHandlingTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['bank_handling_type_id']) && !empty($_POST['bank_handling_type_id'])) {
            $userID = $_SESSION['user_id'];
            $bankHandlingTypeID = $_POST['bank_handling_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $bankHandlingTypeDetails = $this->bankHandlingTypeModel->getBankHandlingType($bankHandlingTypeID);

            $response = [
                'success' => true,
                'bankHandlingTypeName' => $bankHandlingTypeDetails['bank_handling_type_name']
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
require_once '../model/bank-handling-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BankHandlingTypeController(new BankHandlingTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>