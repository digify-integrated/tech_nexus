<?php
session_start();

# -------------------------------------------------------------
#
# Function: LeaveTypeController
# Description: 
# The LeaveTypeController class handles leave type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LeaveTypeController {
    private $leaveTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LeaveTypeModel, UserModel and SecurityModel instances.
    # These instances are used for leave type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LeaveTypeModel $leaveTypeModel     The LeaveTypeModel instance for leave type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LeaveTypeModel $leaveTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->leaveTypeModel = $leaveTypeModel;
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
                case 'save leave type':
                    $this->saveLeaveType();
                    break;
                case 'get leave type details':
                    $this->getLeaveTypeDetails();
                    break;
                case 'delete leave type':
                    $this->deleteLeaveType();
                    break;
                case 'delete multiple leave type':
                    $this->deleteMultipleLeaveType();
                    break;
                case 'duplicate leave type':
                    $this->duplicateLeaveType();
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
    # Function: saveLeaveType
    # Description: 
    # Updates the existing leave type if it exists; otherwise, inserts a new leave type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeaveType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveTypeID = isset($_POST['leave_type_id']) ? htmlspecialchars($_POST['leave_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $leaveTypeName = htmlspecialchars($_POST['leave_type_name'], ENT_QUOTES, 'UTF-8');
        $isPaid = htmlspecialchars($_POST['is_paid'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveTypeExist = $this->leaveTypeModel->checkLeaveTypeExist($leaveTypeID);
        $total = $checkLeaveTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->leaveTypeModel->updateLeaveType($leaveTypeID, $leaveTypeName, $isPaid, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leaveTypeID' => $this->securityModel->encryptData($leaveTypeID)]);
            exit;
        } 
        else {
            $leaveTypeID = $this->leaveTypeModel->insertLeaveType($leaveTypeName, $isPaid, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leaveTypeID' => $this->securityModel->encryptData($leaveTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeaveType
    # Description: 
    # Delete the leave type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeaveType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveTypeID = htmlspecialchars($_POST['leave_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveTypeExist = $this->leaveTypeModel->checkLeaveTypeExist($leaveTypeID);
        $total = $checkLeaveTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leaveTypeModel->deleteLeaveType($leaveTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLeaveType
    # Description: 
    # Delete the selected leave types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLeaveType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveTypeIDs = $_POST['leave_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leaveTypeIDs as $leaveTypeID){
            $this->leaveTypeModel->deleteLeaveType($leaveTypeID);
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
    # Function: duplicateLeaveType
    # Description: 
    # Duplicates the leave type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateLeaveType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveTypeID = htmlspecialchars($_POST['leave_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveTypeExist = $this->leaveTypeModel->checkLeaveTypeExist($leaveTypeID);
        $total = $checkLeaveTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $leaveTypeID = $this->leaveTypeModel->duplicateLeaveType($leaveTypeID, $userID);

        echo json_encode(['success' => true, 'leaveTypeID' =>  $this->securityModel->encryptData($leaveTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeaveTypeDetails
    # Description: 
    # Handles the retrieval of leave type details such as leave type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLeaveTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['leave_type_id']) && !empty($_POST['leave_type_id'])) {
            $userID = $_SESSION['user_id'];
            $leaveTypeID = $_POST['leave_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leaveTypeDetails = $this->leaveTypeModel->getLeaveType($leaveTypeID);

            $response = [
                'success' => true,
                'leaveTypeName' => $leaveTypeDetails['leave_type_name'],
                'isPaid' => $leaveTypeDetails['is_paid']
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
require_once '../model/leave-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeaveTypeController(new LeaveTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>