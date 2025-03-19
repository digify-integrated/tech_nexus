<?php
session_start();

# -------------------------------------------------------------
#
# Function: ContractorController
# Description: 
# The ContractorController class handles contractor related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ContractorController {
    private $contractorModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ContractorModel, UserModel and SecurityModel instances.
    # These instances are used for contractor related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ContractorModel $contractorModel     The ContractorModel instance for contractor related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ContractorModel $contractorModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->contractorModel = $contractorModel;
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
                case 'save contractor':
                    $this->saveContractor();
                    break;
                case 'get contractor details':
                    $this->getContractorDetails();
                    break;
                case 'delete contractor':
                    $this->deleteContractor();
                    break;
                case 'delete multiple contractor':
                    $this->deleteMultipleContractor();
                    break;
                case 'duplicate contractor':
                    $this->duplicateContractor();
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
    # Function: saveContractor
    # Description: 
    # Updates the existing contractor if it exists; otherwise, inserts a new contractor.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContractor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contractorID = isset($_POST['contractor_id']) ? htmlspecialchars($_POST['contractor_id'], ENT_QUOTES, 'UTF-8') : null;
        $contractorName = htmlspecialchars($_POST['contractor_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContractorExist = $this->contractorModel->checkContractorExist($contractorID);
        $total = $checkContractorExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->contractorModel->updateContractor($contractorID, $contractorName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'contractorID' => $this->securityModel->encryptData($contractorID)]);
            exit;
        } 
        else {
            $contractorID = $this->contractorModel->insertContractor($contractorName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'contractorID' => $this->securityModel->encryptData($contractorID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContractor
    # Description: 
    # Delete the contractor if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContractor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contractorID = htmlspecialchars($_POST['contractor_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContractorExist = $this->contractorModel->checkContractorExist($contractorID);
        $total = $checkContractorExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->contractorModel->deleteContractor($contractorID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleContractor
    # Description: 
    # Delete the selected contractors if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleContractor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contractorIDs = $_POST['contractor_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($contractorIDs as $contractorID){
            $this->contractorModel->deleteContractor($contractorID);
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
    # Function: duplicateContractor
    # Description: 
    # Duplicates the contractor if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateContractor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contractorID = htmlspecialchars($_POST['contractor_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContractorExist = $this->contractorModel->checkContractorExist($contractorID);
        $total = $checkContractorExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $contractorID = $this->contractorModel->duplicateContractor($contractorID, $userID);

        echo json_encode(['success' => true, 'contractorID' =>  $this->securityModel->encryptData($contractorID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContractorDetails
    # Description: 
    # Handles the retrieval of contractor details such as contractor name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContractorDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contractor_id']) && !empty($_POST['contractor_id'])) {
            $userID = $_SESSION['user_id'];
            $contractorID = $_POST['contractor_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contractorDetails = $this->contractorModel->getContractor($contractorID);

            $response = [
                'success' => true,
                'contractorName' => $contractorDetails['contractor_name']
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
require_once '../model/contractor-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ContractorController(new ContractorModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>