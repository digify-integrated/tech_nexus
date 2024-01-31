<?php
session_start();

# -------------------------------------------------------------
#
# Function: AttendanceRecordController
# Description: 
# The AttendanceRecordController class handles attendance record related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class AttendanceRecordController {
    private $employeeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided EmployeeModel, UserModel and SecurityModel instances.
    # These instances are used for attendance record related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param EmployeeModel $employeeModel     The EmployeeModel instance for employee related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EmployeeModel $employeeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->employeeModel = $employeeModel;
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
                case 'save attendance record':
                    $this->saveAttendanceRecord();
                    break;
                case 'get attendance record details':
                    $this->getAttendanceRecordDetails();
                    break;
                case 'delete attendance record':
                    $this->deleteAttendanceRecord();
                    break;
                case 'delete multiple attendance record':
                    $this->deleteMultipleAttendanceRecord();
                    break;
                case 'duplicate attendance record':
                    $this->duplicateAttendanceRecord();
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
    # Function: saveAttendanceRecord
    # Description: 
    # Updates the existing attendance record if it exists; otherwise, inserts a new attendance record.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceID = isset($_POST['attendance_id']) ? htmlspecialchars($_POST['attendance_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $checkIn = $this->systemModel->checkDate('empty', $_POST['check_in_date'] . ' ' . $_POST['check_in_time'], '', 'Y-m-d H:i:s', '');
        $checkOut = $this->systemModel->checkDate('empty', $_POST['check_out_date'] . ' ' . $_POST['check_out_time'], '', 'Y-m-d H:i:s', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAttendanceExist = $this->employeeModel->checkAttendanceExist($attendanceID);
        $total = $checkAttendanceExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateRegularAttendanceExit($attendanceID, $employeeID, $checkIn, $userID, $checkOut, $userID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'attendanceID' => $this->securityModel->encryptData($attendanceID)]);
            exit;
        } 
        else {
            $attendanceID = $this->employeeModel->insertManualAttendanceEntry($employeeID, $checkIn, $userID, $checkOut, $userID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'attendanceID' => $this->securityModel->encryptData($attendanceID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAttendanceRecord
    # Description: 
    # Delete the attendance record if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceID = htmlspecialchars($_POST['attendance_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAttendanceExist = $this->employeeModel->checkAttendanceExist($attendanceID);
        $total = $checkAttendanceExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteAttendance($attendanceID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleAttendanceRecord
    # Description: 
    # Delete the selected attendance records if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceIDs = $_POST['attendance_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($attendanceIDs as $attendanceID){
            $this->employeeModel->deleteAttendance($attendanceID);
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
    # Function: getAttendanceRecordDetails
    # Description: 
    # Handles the retrieval of attendance record details such as attendance record name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getAttendanceRecordDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['attendance_id']) && !empty($_POST['attendance_id'])) {
            $userID = $_SESSION['user_id'];
            $attendanceID = $_POST['attendance_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $attendanceRecordDetails = $this->employeeModel->getAttendance($attendanceID);

            $response = [
                'success' => true,
                'attendanceSettingName' => $attendanceRecordDetails['contact_id'],
                'attendanceSettingDescription' => $attendanceRecordDetails['attendance_record_description'],
                'value' => $attendanceRecordDetails['value']
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
require_once '../model/employee-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new AttendanceRecordController(new EmployeeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>