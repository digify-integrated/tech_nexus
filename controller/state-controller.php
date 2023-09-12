<?php
session_start();

# -------------------------------------------------------------
#
# Function: StateController
# Description: 
# The StateController class handles state related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class StateController {
    private $stateModel;
    private $countryModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided StateModel, UserModel and SecurityModel instances.
    # These instances are used for state related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param StateModel $stateModel     The StateModel instance for state related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param CountryModel $countryModel     The CountryModel instance for country related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(StateModel $stateModel, UserModel $userModel, CountryModel $countryModel, SecurityModel $securityModel) {
        $this->stateModel = $stateModel;
        $this->userModel = $userModel;
        $this->countryModel = $countryModel;
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
                case 'save state':
                    $this->saveState();
                    break;
                case 'get state details':
                    $this->getStateDetails();
                    break;
                case 'delete state':
                    $this->deleteState();
                    break;
                case 'delete multiple state':
                    $this->deleteMultipleState();
                    break;
                case 'duplicate state':
                    $this->duplicateState();
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
    # Function: saveState
    # Description: 
    # Updates the existing state if it exists; otherwise, inserts a new state.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveState() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stateID = isset($_POST['state_id']) ? htmlspecialchars($_POST['state_id'], ENT_QUOTES, 'UTF-8') : null;
        $stateName = htmlspecialchars($_POST['state_name'], ENT_QUOTES, 'UTF-8');
        $stateCode = htmlspecialchars($_POST['state_code'], ENT_QUOTES, 'UTF-8');
        $countryID = htmlspecialchars($_POST['country_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkStateExist = $this->stateModel->checkStateExist($stateID);
        $total = $checkStateExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->stateModel->updateState($stateID, $stateName, $countryID, $stateCode, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'stateID' => $this->securityModel->encryptData($stateID)]);
            exit;
        } 
        else {
            $stateID = $this->stateModel->insertState($stateName, $countryID, $stateCode, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'stateID' => $this->securityModel->encryptData($stateID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteState
    # Description: 
    # Delete the state if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteState() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stateID = htmlspecialchars($_POST['state_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkStateExist = $this->stateModel->checkStateExist($stateID);
        $total = $checkStateExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->stateModel->deleteState($stateID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleState
    # Description: 
    # Delete the selected states if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleState() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stateIDs = $_POST['state_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($stateIDs as $stateID){
            $this->stateModel->deleteState($stateID);
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
    # Function: duplicateState
    # Description: 
    # Duplicates the state if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateState() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stateID = htmlspecialchars($_POST['state_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkStateExist = $this->stateModel->checkStateExist($stateID);
        $total = $checkStateExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $stateID = $this->stateModel->duplicateState($stateID, $userID);

        echo json_encode(['success' => true, 'stateID' =>  $this->securityModel->encryptData($stateID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getStateDetails
    # Description: 
    # Handles the retrieval of state details such as state name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getStateDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['state_id']) && !empty($_POST['state_id'])) {
            $userID = $_SESSION['user_id'];
            $stateID = $_POST['state_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $stateDetails = $this->stateModel->getState($stateID);
            $countryID = $stateDetails['country_id'];

            $countryDetails = $this->countryModel->getCountry($countryID);
            $countryName = $countryDetails['country_name'];

            $response = [
                'success' => true,
                'stateName' => $stateDetails['state_name'],
                'stateCode' => $stateDetails['state_code'],
                'countryID' => $countryID,
                'countryName' => $countryName
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
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new StateController(new StateModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CountryModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>