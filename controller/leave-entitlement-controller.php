<?php
session_start();

# -------------------------------------------------------------
#
# Function: LeaveEntitlementController
# Description: 
# The LeaveEntitlementController class handles leave entitlement related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LeaveEntitlementController {
    private $leaveEntitlementModel;
    private $employeeModel;
    private $leaveTypeModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LeaveEntitlementModel, UserModel and SecurityModel instances.
    # These instances are used for leave entitlement related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LeaveEntitlementModel $leaveEntitlementModel     The LeaveEntitlementModel instance for leave entitlement related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LeaveEntitlementModel $leaveEntitlementModel, EmployeeModel $employeeModel, LeaveTypeModel $leaveTypeModel, UserModel $userModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->leaveEntitlementModel = $leaveEntitlementModel;
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
                case 'save leave entitlement':
                    $this->saveLeaveEntitlement();
                    break;
                case 'get leave entitlement details':
                    $this->getLeaveEntitlementDetails();
                    break;
                case 'delete leave entitlement':
                    $this->deleteLeaveEntitlement();
                    break;
                case 'delete multiple leave entitlement':
                    $this->deleteMultipleLeaveEntitlement();
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
    # Function: saveLeaveEntitlement
    # Description: 
    # Updates the existing leave entitlement if it exists; otherwise, inserts a new leave entitlement.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeaveEntitlement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveEntitlementID = isset($_POST['leave_entitlement_id']) ? htmlspecialchars($_POST['leave_entitlement_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $leaveTypeID = htmlspecialchars($_POST['leave_type_id'], ENT_QUOTES, 'UTF-8');
        $entitlementAmount = htmlspecialchars($_POST['entitlement_amount'], ENT_QUOTES, 'UTF-8');
        $leavePeriodStart = $this->systemModel->checkDate('empty', $_POST['leave_period_start'], '', 'Y-m-d', '');
        $leavePeriodEnd = $this->systemModel->checkDate('empty', $_POST['leave_period_end'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveEntitlementExist = $this->leaveEntitlementModel->checkLeaveEntitlementExist($leaveEntitlementID);
        $total = $checkLeaveEntitlementExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveEntitlementModel->updateLeaveEntitlement($leaveEntitlementID, $employeeID, $leaveTypeID, $entitlementAmount, $entitlementAmount, $leavePeriodStart, $leavePeriodEnd, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leaveEntitlementID' => $this->securityModel->encryptData($leaveEntitlementID)]);
            exit;
        } 
        else {
            $leaveEntitlementID = $this->leaveEntitlementModel->insertLeaveEntitlement($employeeID, $leaveTypeID, $entitlementAmount, $entitlementAmount, $leavePeriodStart, $leavePeriodEnd, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leaveEntitlementID' => $this->securityModel->encryptData($leaveEntitlementID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeaveEntitlement
    # Description: 
    # Delete the leave entitlement if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeaveEntitlement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveEntitlementID = htmlspecialchars($_POST['leave_entitlement_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveEntitlementExist = $this->leaveEntitlementModel->checkLeaveEntitlementExist($leaveEntitlementID);
        $total = $checkLeaveEntitlementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leaveEntitlementModel->deleteLeaveEntitlement($leaveEntitlementID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLeaveEntitlement
    # Description: 
    # Delete the selected leave entitlements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLeaveEntitlement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveEntitlementIDs = $_POST['leave_entitlement_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leaveEntitlementIDs as $leaveEntitlementID){
            $this->leaveEntitlementModel->deleteLeaveEntitlement($leaveEntitlementID);
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
    # Function: getLeaveEntitlementDetails
    # Description: 
    # Handles the retrieval of leave entitlement details such as leave entitlement name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLeaveEntitlementDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['leave_entitlement_id']) && !empty($_POST['leave_entitlement_id'])) {
            $userID = $_SESSION['user_id'];
            $leaveEntitlementID = $_POST['leave_entitlement_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leaveEntitlementDetails = $this->leaveEntitlementModel->getLeaveEntitlement($leaveEntitlementID);
            $contactID = $leaveEntitlementDetails['contact_id'];
            $leaveTypeID = $leaveEntitlementDetails['leave_type_id'];

            $employeeDetails = $this->employeeModel->getPersonalInformation($contactID);
            $employeeName = $employeeDetails['file_as'] ?? null;

            $leaveTypeDetails = $this->leaveTypeModel->getLeaveType($leaveTypeID);
            $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

            $response = [
                'success' => true,
                'contactID' => $contactID,
                'employeeName' => $employeeName,
                'leaveTypeID' => $leaveTypeID,
                'leaveTypeName' => $leaveTypeName,
                'entitlementAmount' => $leaveEntitlementDetails['entitlement_amount'],
                'remainingEntitlement' => $leaveEntitlementDetails['remaining_entitlement'],
                'leavePeriodStart' =>  $this->systemModel->checkDate('empty', $leaveEntitlementDetails['leave_period_start'], '', 'm/d/Y', ''),
                'leavePeriodEnd' =>  $this->systemModel->checkDate('empty', $leaveEntitlementDetails['leave_period_end'], '', 'm/d/Y', '')
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
require_once '../model/leave-entitlement-model.php';
require_once '../model/leave-type-model.php';
require_once '../model/employee-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeaveEntitlementController(new LeaveEntitlementModel(new DatabaseModel), new EmployeeModel(new DatabaseModel), new LeaveTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>