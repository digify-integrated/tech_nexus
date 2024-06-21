<?php
session_start();

# -------------------------------------------------------------
#
# Function: LeaveApplicationController
# Description: 
# The LeaveApplicationController class handles leave application related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LeaveApplicationController {
    private $leaveApplicationModel;
    private $employeeModel;
    private $leaveTypeModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LeaveApplicationModel, UserModel and SecurityModel instances.
    # These instances are used for leave application related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LeaveApplicationModel $leaveApplicationModel     The LeaveApplicationModel instance for leave application related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LeaveApplicationModel $leaveApplicationModel, EmployeeModel $employeeModel, LeaveTypeModel $leaveTypeModel, UserModel $userModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->leaveApplicationModel = $leaveApplicationModel;
        $this->userModel = $userModel;
        $this->employeeModel = $employeeModel;
        $this->leaveTypeModel = $leaveTypeModel;
        $this->systemModel = $systemModel;
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
                case 'save leave application':
                    $this->saveLeaveApplication();
                    break;
                case 'leave application for approval':
                    $this->leaveApplicationForApproval();
                    break;
                case 'leave application approved':
                    $this->leaveApplicationApproval();
                    break;
                case 'leave application reject':
                    $this->leaveApplicationReject();
                    break;
                case 'leave application cancel':
                    $this->leaveApplicationCancellation();
                    break;
                case 'leave application approval':
                    $this->leaveApplicationApproval();
                    break;
                case 'get leave application details':
                    $this->getLeaveApplicationDetails();
                    break;
                case 'delete leave application':
                    $this->deleteLeaveApplication();
                    break;
                case 'delete multiple leave application':
                    $this->deleteMultipleLeaveApplication();
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
    # Function: saveLeaveApplication
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeaveApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $leaveTypeID = htmlspecialchars($_POST['leave_type_id'], ENT_QUOTES, 'UTF-8');
        $reason  = $_POST['reason'];
        $leaveDate = $this->systemModel->checkDate('empty', $_POST['leave_date'], '', 'Y-m-d', '');
        $leaveStartTime = $this->systemModel->checkDate('empty', $_POST['leave_start_time'], '', 'H:i:s', '');
        $leaveEndTime = $this->systemModel->checkDate('empty', $_POST['leave_end_time'], '', 'H:i:s', '');
        $numberOfHours = round(abs(strtotime($leaveEndTime) - strtotime($leaveStartTime)) / 3600, 2);
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplication($leaveApplicationID, $contactID, $leaveTypeID, $reason, $leaveDate, $leaveStartTime, $leaveEndTime, $numberOfHours, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leaveApplicationID' => $this->securityModel->encryptData($leaveApplicationID)]);
            exit;
        } 
        else {
            $leaveApplicationID = $this->leaveApplicationModel->insertLeaveApplication($contactID, $leaveTypeID, $reason, $leaveDate, $leaveStartTime, $leaveEndTime, $numberOfHours, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leaveApplicationID' => $this->securityModel->encryptData($leaveApplicationID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationForApproval
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'For Approval', '', $userID);

            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationApproval
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Approved', '', $userID);

            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationRejection
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationRejection() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $rejectionReason = htmlspecialchars($_POST['rejection_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Rejected', $rejectionReason, $userID);
            
            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationCancellation
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationCancellation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $cancellationReason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Cancelled', $cancellationReason, $userID);

            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeaveApplication
    # Description: 
    # Delete the leave application if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeaveApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveApplicationID = htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leaveApplicationModel->deleteLeaveApplication($leaveApplicationID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLeaveApplication
    # Description: 
    # Delete the selected leave applications if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLeaveApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveApplicationIDs = $_POST['leave_application_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leaveApplicationIDs as $leaveApplicationID){
            $this->leaveApplicationModel->deleteLeaveApplication($leaveApplicationID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeaveApplicationDetails
    # Description: 
    # Handles the retrieval of leave application details such as leave application name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLeaveApplicationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['leave_application_id']) && !empty($_POST['leave_application_id'])) {
            $userID = $_SESSION['user_id'];
            $leaveApplicationID = $_POST['leave_application_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $leaveTypeID = $leaveApplicationDetails['leave_type_id'];

            $leaveTypeDetails = $this->leaveTypeModel->getLeaveType($leaveTypeID);
            $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

            $response = [
                'success' => true,
                'leaveTypeID' => $leaveTypeID,
                'leaveTypeName' => $leaveTypeName,
                'reason' => $leaveApplicationDetails['reason'],
                'leaveDate' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_date'], '', 'm/d/Y', ''),
                'leaveStartTime' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_start_time'], '', 'H:i', ''),
                'leaveEndTime' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_end_time'], '', 'H:i', ''),
                'leaveStartTimeLabel' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_start_time'], '', 'h:i a', ''),
                'leaveEndTimeLabel' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_end_time'], '', 'h:i a', '')
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
require_once '../model/leave-application-model.php';
require_once '../model/leave-type-model.php';
require_once '../model/employee-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeaveApplicationController(new LeaveApplicationModel(new DatabaseModel), new EmployeeModel(new DatabaseModel), new LeaveTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>