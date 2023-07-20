<?php
session_start();

# -------------------------------------------------------------
#
# Function: SystemActionController
# Description: 
# The SystemActionController class handles system action related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class SystemActionController {
    private $systemActionModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided SystemActionModel, UserModel and SecurityModel instances.
    # These instances are used for system action related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param SystemActionModel $systemActionModel     The SystemActionModel instance for system action related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(SystemActionModel $systemActionModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->systemActionModel = $systemActionModel;
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
                case 'save system action':
                    $this->saveSystemAction();
                    break;
                case 'get system action details':
                    $this->getSystemActionDetails();
                    break;
                case 'delete system action':
                    $this->deleteSystemAction();
                    break;
                case 'delete multiple system action':
                    $this->deleteMultipleSystemAction();
                    break;
                case 'duplicate system action':
                    $this->duplicateSystemAction();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSystemAction
    # Description: 
    # Updates the existing system action if it exists; otherwise, inserts a new system action.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSystemAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemActionID = isset($_POST['system_action_id']) ? htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8') : null;
        $systemActionName = htmlspecialchars($_POST['system_action_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSystemActionExist = $this->systemActionModel->checkSystemActionExist($systemActionID);
        $total = $checkSystemActionExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->systemActionModel->updateSystemAction($systemActionID, $systemActionName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'systemActionID' => $this->securityModel->encryptData($systemActionID)]);
            exit;
        } 
        else {
            $systemActionID = $this->systemActionModel->insertSystemAction($systemActionName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'systemActionID' => $this->securityModel->encryptData($systemActionID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSystemAction
    # Description: 
    # Delete the system action if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSystemAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSystemActionExist = $this->systemActionModel->checkSystemActionExist($systemActionID);
        $total = $checkSystemActionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->systemActionModel->deleteSystemAction($systemActionID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleSystemAction
    # Description: 
    # Delete the selected system actions if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleSystemAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemActionIDs = $_POST['system_action_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($systemActionIDs as $systemActionID){
            $this->systemActionModel->deleteSystemAction($systemActionID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateSystemAction
    # Description: 
    # Duplicates the system action if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateSystemAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSystemActionExist = $this->systemActionModel->checkSystemActionExist($systemActionID);
        $total = $checkSystemActionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $systemActionID = $this->systemActionModel->duplicateSystemAction($systemActionID, $userID);

        echo json_encode(['success' => true, 'systemActionID' =>  $this->securityModel->encryptData($systemActionID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSystemActionDetails
    # Description: 
    # Handles the retrieval of system action details such as system action name, order sequence, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSystemActionDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['system_action_id']) && !empty($_POST['system_action_id'])) {
            $userID = $_SESSION['user_id'];
            $systemActionID = $_POST['system_action_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $systemActionDetails = $this->systemActionModel->getSystemAction($systemActionID);

            $response = [
                'success' => true,
                'systemActionName' => $systemActionDetails['system_action_name']
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
require_once '../model/system-action-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new SystemActionController(new SystemActionModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>