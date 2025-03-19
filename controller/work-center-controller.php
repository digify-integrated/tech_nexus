<?php
session_start();

# -------------------------------------------------------------
#
# Function: WorkCenterController
# Description: 
# The WorkCenterController class handles work center related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class WorkCenterController {
    private $workCenterModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided WorkCenterModel, UserModel and SecurityModel instances.
    # These instances are used for work center related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param WorkCenterModel $workCenterModel     The WorkCenterModel instance for work center related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(WorkCenterModel $workCenterModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->workCenterModel = $workCenterModel;
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
                case 'save work center':
                    $this->saveWorkCenter();
                    break;
                case 'get work center details':
                    $this->getWorkCenterDetails();
                    break;
                case 'delete work center':
                    $this->deleteWorkCenter();
                    break;
                case 'delete multiple work center':
                    $this->deleteMultipleWorkCenter();
                    break;
                case 'duplicate work center':
                    $this->duplicateWorkCenter();
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
    # Function: saveWorkCenter
    # Description: 
    # Updates the existing work center if it exists; otherwise, inserts a new work center.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveWorkCenter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workCenterID = isset($_POST['work_center_id']) ? htmlspecialchars($_POST['work_center_id'], ENT_QUOTES, 'UTF-8') : null;
        $workCenterName = htmlspecialchars($_POST['work_center_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkCenterExist = $this->workCenterModel->checkWorkCenterExist($workCenterID);
        $total = $checkWorkCenterExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->workCenterModel->updateWorkCenter($workCenterID, $workCenterName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'workCenterID' => $this->securityModel->encryptData($workCenterID)]);
            exit;
        } 
        else {
            $workCenterID = $this->workCenterModel->insertWorkCenter($workCenterName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'workCenterID' => $this->securityModel->encryptData($workCenterID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkCenter
    # Description: 
    # Delete the work center if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteWorkCenter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workCenterID = htmlspecialchars($_POST['work_center_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkCenterExist = $this->workCenterModel->checkWorkCenterExist($workCenterID);
        $total = $checkWorkCenterExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->workCenterModel->deleteWorkCenter($workCenterID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleWorkCenter
    # Description: 
    # Delete the selected work centers if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleWorkCenter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workCenterIDs = $_POST['work_center_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($workCenterIDs as $workCenterID){
            $this->workCenterModel->deleteWorkCenter($workCenterID);
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
    # Function: duplicateWorkCenter
    # Description: 
    # Duplicates the work center if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateWorkCenter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workCenterID = htmlspecialchars($_POST['work_center_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkCenterExist = $this->workCenterModel->checkWorkCenterExist($workCenterID);
        $total = $checkWorkCenterExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $workCenterID = $this->workCenterModel->duplicateWorkCenter($workCenterID, $userID);

        echo json_encode(['success' => true, 'workCenterID' =>  $this->securityModel->encryptData($workCenterID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkCenterDetails
    # Description: 
    # Handles the retrieval of work center details such as work center name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getWorkCenterDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['work_center_id']) && !empty($_POST['work_center_id'])) {
            $userID = $_SESSION['user_id'];
            $workCenterID = $_POST['work_center_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $workCenterDetails = $this->workCenterModel->getWorkCenter($workCenterID);

            $response = [
                'success' => true,
                'workCenterName' => $workCenterDetails['work_center_name']
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
require_once '../model/work-center-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new WorkCenterController(new WorkCenterModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>