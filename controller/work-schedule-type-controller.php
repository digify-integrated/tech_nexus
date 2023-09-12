<?php
session_start();

# -------------------------------------------------------------
#
# Function: WorkScheduleTypeController
# Description: 
# The WorkScheduleTypeController class handles work schedule type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class WorkScheduleTypeController {
    private $workScheduleTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided WorkScheduleTypeModel, UserModel and SecurityModel instances.
    # These instances are used for work schedule type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param WorkScheduleTypeModel $workScheduleTypeModel     The WorkScheduleTypeModel instance for work schedule type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(WorkScheduleTypeModel $workScheduleTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->workScheduleTypeModel = $workScheduleTypeModel;
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
                case 'save work schedule type':
                    $this->saveWorkScheduleType();
                    break;
                case 'get work schedule type details':
                    $this->getWorkScheduleTypeDetails();
                    break;
                case 'delete work schedule type':
                    $this->deleteWorkScheduleType();
                    break;
                case 'delete multiple work schedule type':
                    $this->deleteMultipleWorkScheduleType();
                    break;
                case 'duplicate work schedule type':
                    $this->duplicateWorkScheduleType();
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
    # Function: saveWorkScheduleType
    # Description: 
    # Updates the existing work schedule type if it exists; otherwise, inserts a new work schedule type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveWorkScheduleType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleTypeID = isset($_POST['work_schedule_type_id']) ? htmlspecialchars($_POST['work_schedule_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $workScheduleTypeName = htmlspecialchars($_POST['work_schedule_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkScheduleTypeExist = $this->workScheduleTypeModel->checkWorkScheduleTypeExist($workScheduleTypeID);
        $total = $checkWorkScheduleTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->workScheduleTypeModel->updateWorkScheduleType($workScheduleTypeID, $workScheduleTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'workScheduleTypeID' => $this->securityModel->encryptData($workScheduleTypeID)]);
            exit;
        } 
        else {
            $workScheduleTypeID = $this->workScheduleTypeModel->insertWorkScheduleType($workScheduleTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'workScheduleTypeID' => $this->securityModel->encryptData($workScheduleTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkScheduleType
    # Description: 
    # Delete the work schedule type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteWorkScheduleType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleTypeID = htmlspecialchars($_POST['work_schedule_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkScheduleTypeExist = $this->workScheduleTypeModel->checkWorkScheduleTypeExist($workScheduleTypeID);
        $total = $checkWorkScheduleTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->workScheduleTypeModel->deleteWorkScheduleType($workScheduleTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleWorkScheduleType
    # Description: 
    # Delete the selected work schedule types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleWorkScheduleType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleTypeIDs = $_POST['work_schedule_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($workScheduleTypeIDs as $workScheduleTypeID){
            $this->workScheduleTypeModel->deleteWorkScheduleType($workScheduleTypeID);
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
    # Function: duplicateWorkScheduleType
    # Description: 
    # Duplicates the work schedule type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateWorkScheduleType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleTypeID = htmlspecialchars($_POST['work_schedule_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkScheduleTypeExist = $this->workScheduleTypeModel->checkWorkScheduleTypeExist($workScheduleTypeID);
        $total = $checkWorkScheduleTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $workScheduleTypeID = $this->workScheduleTypeModel->duplicateWorkScheduleType($workScheduleTypeID, $userID);

        echo json_encode(['success' => true, 'workScheduleTypeID' =>  $this->securityModel->encryptData($workScheduleTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkScheduleTypeDetails
    # Description: 
    # Handles the retrieval of work schedule type details such as work schedule type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getWorkScheduleTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['work_schedule_type_id']) && !empty($_POST['work_schedule_type_id'])) {
            $userID = $_SESSION['user_id'];
            $workScheduleTypeID = $_POST['work_schedule_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $workScheduleTypeDetails = $this->workScheduleTypeModel->getWorkScheduleType($workScheduleTypeID);

            $response = [
                'success' => true,
                'workScheduleTypeName' => $workScheduleTypeDetails['work_schedule_type_name']
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
require_once '../model/work-schedule-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new WorkScheduleTypeController(new WorkScheduleTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>