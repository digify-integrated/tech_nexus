<?php
session_start();

# -------------------------------------------------------------
#
# Function: MakeController
# Description: 
# The MakeController class handles make related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class MakeController {
    private $makeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided MakeModel, UserModel and SecurityModel instances.
    # These instances are used for make related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param MakeModel $makeModel     The MakeModel instance for make related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(MakeModel $makeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->makeModel = $makeModel;
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
                case 'save make':
                    $this->saveMake();
                    break;
                case 'get make details':
                    $this->getMakeDetails();
                    break;
                case 'delete make':
                    $this->deleteMake();
                    break;
                case 'delete multiple make':
                    $this->deleteMultipleMake();
                    break;
                case 'duplicate make':
                    $this->duplicateMake();
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
    # Function: saveMake
    # Description: 
    # Updates the existing make if it exists; otherwise, inserts a new make.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $makeID = isset($_POST['make_id']) ? htmlspecialchars($_POST['make_id'], ENT_QUOTES, 'UTF-8') : null;
        $makeName = htmlspecialchars($_POST['make_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMakeExist = $this->makeModel->checkMakeExist($makeID);
        $total = $checkMakeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->makeModel->updateMake($makeID, $makeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'makeID' => $this->securityModel->encryptData($makeID)]);
            exit;
        } 
        else {
            $makeID = $this->makeModel->insertMake($makeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'makeID' => $this->securityModel->encryptData($makeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMake
    # Description: 
    # Delete the make if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $makeID = htmlspecialchars($_POST['make_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMakeExist = $this->makeModel->checkMakeExist($makeID);
        $total = $checkMakeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->makeModel->deleteMake($makeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleMake
    # Description: 
    # Delete the selected makes if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $makeIDs = $_POST['make_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($makeIDs as $makeID){
            $this->makeModel->deleteMake($makeID);
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
    # Function: duplicateMake
    # Description: 
    # Duplicates the make if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $makeID = htmlspecialchars($_POST['make_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMakeExist = $this->makeModel->checkMakeExist($makeID);
        $total = $checkMakeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $makeID = $this->makeModel->duplicateMake($makeID, $userID);

        echo json_encode(['success' => true, 'makeID' =>  $this->securityModel->encryptData($makeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getMakeDetails
    # Description: 
    # Handles the retrieval of make details such as make name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getMakeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['make_id']) && !empty($_POST['make_id'])) {
            $userID = $_SESSION['user_id'];
            $makeID = $_POST['make_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $makeDetails = $this->makeModel->getMake($makeID);

            $response = [
                'success' => true,
                'makeName' => $makeDetails['make_name']
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
require_once '../model/make-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new MakeController(new MakeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>