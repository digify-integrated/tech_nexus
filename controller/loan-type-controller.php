<?php
session_start();

# -------------------------------------------------------------
#
# Function: LoanTypeController
# Description: 
# The LoanTypeController class handles loan type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LoanTypeController {
    private $loanTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LoanTypeModel, UserModel and SecurityModel instances.
    # These instances are used for loan type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LoanTypeModel $loanTypeModel     The LoanTypeModel instance for loan type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LoanTypeModel $loanTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->loanTypeModel = $loanTypeModel;
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
                case 'save loan type':
                    $this->saveLoanType();
                    break;
                case 'get loan type details':
                    $this->getLoanTypeDetails();
                    break;
                case 'delete loan type':
                    $this->deleteLoanType();
                    break;
                case 'delete multiple loan type':
                    $this->deleteMultipleLoanType();
                    break;
                case 'duplicate loan type':
                    $this->duplicateLoanType();
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
    # Function: saveLoanType
    # Description: 
    # Updates the existing loan type if it exists; otherwise, inserts a new loan type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLoanType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanTypeID = isset($_POST['loan_type_id']) ? htmlspecialchars($_POST['loan_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $loanTypeName = htmlspecialchars($_POST['loan_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanTypeExist = $this->loanTypeModel->checkLoanTypeExist($loanTypeID);
        $total = $checkLoanTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->loanTypeModel->updateLoanType($loanTypeID, $loanTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'loanTypeID' => $this->securityModel->encryptData($loanTypeID)]);
            exit;
        } 
        else {
            $loanTypeID = $this->loanTypeModel->insertLoanType($loanTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'loanTypeID' => $this->securityModel->encryptData($loanTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLoanType
    # Description: 
    # Delete the loan type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLoanType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanTypeID = htmlspecialchars($_POST['loan_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanTypeExist = $this->loanTypeModel->checkLoanTypeExist($loanTypeID);
        $total = $checkLoanTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->loanTypeModel->deleteLoanType($loanTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLoanType
    # Description: 
    # Delete the selected loan types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLoanType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanTypeIDs = $_POST['loan_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanTypeIDs as $loanTypeID){
            $this->loanTypeModel->deleteLoanType($loanTypeID);
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
    # Function: duplicateLoanType
    # Description: 
    # Duplicates the loan type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateLoanType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanTypeID = htmlspecialchars($_POST['loan_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanTypeExist = $this->loanTypeModel->checkLoanTypeExist($loanTypeID);
        $total = $checkLoanTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $loanTypeID = $this->loanTypeModel->duplicateLoanType($loanTypeID, $userID);

        echo json_encode(['success' => true, 'loanTypeID' =>  $this->securityModel->encryptData($loanTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLoanTypeDetails
    # Description: 
    # Handles the retrieval of loan type details such as loan type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLoanTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['loan_type_id']) && !empty($_POST['loan_type_id'])) {
            $userID = $_SESSION['user_id'];
            $loanTypeID = $_POST['loan_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $loanTypeDetails = $this->loanTypeModel->getLoanType($loanTypeID);

            $response = [
                'success' => true,
                'loanTypeName' => $loanTypeDetails['loan_type_name']
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
require_once '../model/loan-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LoanTypeController(new LoanTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>