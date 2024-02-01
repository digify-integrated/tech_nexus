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
    private $systemModel;

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
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EmployeeModel $employeeModel, UserModel $userModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->employeeModel = $employeeModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
        $this->systemModel = $systemModel;
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
        $checkInDate = $_POST['check_in_date'];
        $checkInTime = $_POST['check_in_time'];
        $checkInNotes = htmlspecialchars($_POST['check_in_notes'], ENT_QUOTES, 'UTF-8');
        $checkOutDate = $_POST['check_out_date'];
        $checkOutTime = $_POST['check_out_time'];
        $checkOutNotes = htmlspecialchars($_POST['check_out_notes'], ENT_QUOTES, 'UTF-8');
        $checkIn = $this->systemModel->checkDate('empty',  $checkInDate . ' ' . $checkInTime, '', 'Y-m-d H:i:s', '');
        $checkOut = $this->systemModel->checkDate('empty', $checkOutDate . ' ' . $checkOutTime, '', 'Y-m-d H:i:s', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkAttendanceConflict = $this->employeeModel->checkAttendanceConflict($attendanceID, $employeeID, $checkIn, $checkOut);
        $total = $checkAttendanceConflict['total'] ?? 0;

        if ($total > 0) {            
            echo json_encode(['success' => false, 'message' => 'Attendance conflict detected. Please check the date and time.']);
            exit;
        } 
        
        if(!empty($checkOutDate) && !empty($checkOutTime)){
            if ($checkOut < $checkIn) {
                echo json_encode(['success' => false, 'message' => '"Check Out" time cannot be earlier than "Check In" time.']);
                exit;
            }
        }
            
        $checkAttendanceExist = $this->employeeModel->checkAttendanceExist($attendanceID);
        $total = $checkAttendanceExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateManualAttendanceEntry($attendanceID, $employeeID, $checkIn, $checkInNotes, $userID, $checkOut, $checkOutNotes, $userID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'attendanceID' => $this->securityModel->encryptData($attendanceID)]);
            exit;
        } 
        else {
            $attendanceID = $this->employeeModel->insertManualAttendanceEntry($employeeID, $checkIn, $checkInNotes, $userID, $checkOut, $checkOutNotes, $userID, $userID);

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
            $contactID = $attendanceRecordDetails['contact_id'];
            $checkInImage = $this->systemModel->checkImage($attendanceRecordDetails['check_in_image'], 'default');
            $checkInLocation = $attendanceRecordDetails['check_in_location'];
            $checkOutImage = $this->systemModel->checkImage($attendanceRecordDetails['check_out_image'], 'default');
            $checkOutLocation = $attendanceRecordDetails['check_out_location'];

            if(!empty($checkOutLocation)){
                $checkInMap = '<div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=200&amp;hl=en&amp;q='. $checkInLocation .'&amp;t=&amp;z=18&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe></div><style>.mapouter{position:relative;text-align:right;width:100%;height:200px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:200px;}.gmap_iframe {height:200px!important;}</style></div>';
            }
            else{
                $checkInMap = '<div class="alert alert-info mb-4" role="alert">No check in location found.</div>';
            }

            if(!empty($checkOutLocation)){
                $checkOutMap = '<div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=200&amp;hl=en&amp;q='. $checkOutLocation .'&amp;t=&amp;z=18&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe></div><style>.mapouter{position:relative;text-align:right;width:100%;height:200px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:200px;}.gmap_iframe {height:200px!important;}</style></div>';
            }
            else{
                $checkOutMap = '<div class="alert alert-info mb-4" role="alert">No check out location found.</div>';
            }

            $employeeDetails = $this->employeeModel->getPersonalInformation($contactID);
            $fileAs = $employeeDetails['file_as'];

            $response = [
                'success' => true,
                'contactID' => $contactID,
                'employeeName' => $fileAs,
                'checkInDate' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_in'], '', 'm/d/Y', ''),
                'checkInTime' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_in'], '', 'H:i', ''),
                'checkInTimeLabel' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_in'], '', 'h:i A', ''),
                'checkInNotes' => $attendanceRecordDetails['check_in_notes'],
                'checkOutDate' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_out'], '', 'm/d/Y', ''),
                'checkOutTime' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_out'], '', 'H:i', ''),
                'checkOutTimeLabel' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_out'], '', 'h:i A', ''),
                'checkOutNotes' => $attendanceRecordDetails['check_out_notes'],
                'checkInMap' => $checkInMap,
                'checkOutMap' => $checkOutMap,
                'checkInImage' => $checkInImage,
                'checkOutImage' => $checkOutImage
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

$controller = new AttendanceRecordController(new EmployeeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>