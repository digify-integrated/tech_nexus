<?php
session_start();

# -------------------------------------------------------------
#
# Function: IncomeLevelController
# Description: 
# The IncomeLevelController class handles income level related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class IncomeLevelController {
    private $incomeLevelModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided IncomeLevelModel, UserModel and SecurityModel instances.
    # These instances are used for income level related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param IncomeLevelModel $incomeLevelModel     The IncomeLevelModel instance for income level related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(IncomeLevelModel $incomeLevelModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->incomeLevelModel = $incomeLevelModel;
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
                case 'save income level':
                    $this->saveIncomeLevel();
                    break;
                case 'get income level details':
                    $this->getIncomeLevelDetails();
                    break;
                case 'delete income level':
                    $this->deleteIncomeLevel();
                    break;
                case 'delete multiple income level':
                    $this->deleteMultipleIncomeLevel();
                    break;
                case 'duplicate income level':
                    $this->duplicateIncomeLevel();
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
    # Function: saveIncomeLevel
    # Description: 
    # Updates the existing income level if it exists; otherwise, inserts a new income level.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveIncomeLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $incomeLevelID = isset($_POST['income_level_id']) ? htmlspecialchars($_POST['income_level_id'], ENT_QUOTES, 'UTF-8') : null;
        $incomeLevelName = htmlspecialchars($_POST['income_level_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkIncomeLevelExist = $this->incomeLevelModel->checkIncomeLevelExist($incomeLevelID);
        $total = $checkIncomeLevelExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->incomeLevelModel->updateIncomeLevel($incomeLevelID, $incomeLevelName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'incomeLevelID' => $this->securityModel->encryptData($incomeLevelID)]);
            exit;
        } 
        else {
            $incomeLevelID = $this->incomeLevelModel->insertIncomeLevel($incomeLevelName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'incomeLevelID' => $this->securityModel->encryptData($incomeLevelID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteIncomeLevel
    # Description: 
    # Delete the income level if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteIncomeLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $incomeLevelID = htmlspecialchars($_POST['income_level_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkIncomeLevelExist = $this->incomeLevelModel->checkIncomeLevelExist($incomeLevelID);
        $total = $checkIncomeLevelExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->incomeLevelModel->deleteIncomeLevel($incomeLevelID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleIncomeLevel
    # Description: 
    # Delete the selected income levels if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleIncomeLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $incomeLevelIDs = $_POST['income_level_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($incomeLevelIDs as $incomeLevelID){
            $this->incomeLevelModel->deleteIncomeLevel($incomeLevelID);
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
    # Function: duplicateIncomeLevel
    # Description: 
    # Duplicates the income level if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateIncomeLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $incomeLevelID = htmlspecialchars($_POST['income_level_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkIncomeLevelExist = $this->incomeLevelModel->checkIncomeLevelExist($incomeLevelID);
        $total = $checkIncomeLevelExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $incomeLevelID = $this->incomeLevelModel->duplicateIncomeLevel($incomeLevelID, $userID);

        echo json_encode(['success' => true, 'incomeLevelID' =>  $this->securityModel->encryptData($incomeLevelID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getIncomeLevelDetails
    # Description: 
    # Handles the retrieval of income level details such as income level name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getIncomeLevelDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['income_level_id']) && !empty($_POST['income_level_id'])) {
            $userID = $_SESSION['user_id'];
            $incomeLevelID = $_POST['income_level_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $incomeLevelDetails = $this->incomeLevelModel->getIncomeLevel($incomeLevelID);

            $response = [
                'success' => true,
                'incomeLevelName' => $incomeLevelDetails['income_level_name']
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
require_once '../model/income-level-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new IncomeLevelController(new IncomeLevelModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>