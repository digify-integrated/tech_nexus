<?php
session_start();

# -------------------------------------------------------------
#
# Function: AttendanceSettingController
# Description: 
# The AttendanceSettingController class handles attendance setting related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class AttendanceSettingController {
    private $attendanceSettingModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided AttendanceSettingModel, UserModel and SecurityModel instances.
    # These instances are used for attendance setting related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param AttendanceSettingModel $attendanceSettingModel     The AttendanceSettingModel instance for attendance setting related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(AttendanceSettingModel $attendanceSettingModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->attendanceSettingModel = $attendanceSettingModel;
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
                case 'save attendance setting':
                    $this->saveAttendanceSetting();
                    break;
                case 'get attendance setting details':
                    $this->getAttendanceSettingDetails();
                    break;
                case 'delete attendance setting':
                    $this->deleteAttendanceSetting();
                    break;
                case 'delete multiple attendance setting':
                    $this->deleteMultipleAttendanceSetting();
                    break;
                case 'duplicate attendance setting':
                    $this->duplicateAttendanceSetting();
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
    # Function: saveAttendanceSetting
    # Description: 
    # Updates the existing attendance setting if it exists; otherwise, inserts a new attendance setting.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveAttendanceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceSettingID = isset($_POST['attendance_setting_id']) ? htmlspecialchars($_POST['attendance_setting_id'], ENT_QUOTES, 'UTF-8') : null;
        $attendanceSettingName = htmlspecialchars($_POST['attendance_setting_name'], ENT_QUOTES, 'UTF-8');
        $attendanceSettingDescription = htmlspecialchars($_POST['attendance_setting_description'], ENT_QUOTES, 'UTF-8');
        $value = htmlspecialchars($_POST['value'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAttendanceSettingExist = $this->attendanceSettingModel->checkAttendanceSettingExist($attendanceSettingID);
        $total = $checkAttendanceSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->attendanceSettingModel->updateAttendanceSetting($attendanceSettingID, $attendanceSettingName, $attendanceSettingDescription, $value, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'attendanceSettingID' => $this->securityModel->encryptData($attendanceSettingID)]);
            exit;
        } 
        else {
            $attendanceSettingID = $this->attendanceSettingModel->insertAttendanceSetting($attendanceSettingName, $attendanceSettingDescription, $value, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'attendanceSettingID' => $this->securityModel->encryptData($attendanceSettingID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAttendanceSetting
    # Description: 
    # Delete the attendance setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteAttendanceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceSettingID = htmlspecialchars($_POST['attendance_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAttendanceSettingExist = $this->attendanceSettingModel->checkAttendanceSettingExist($attendanceSettingID);
        $total = $checkAttendanceSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->attendanceSettingModel->deleteAttendanceSetting($attendanceSettingID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleAttendanceSetting
    # Description: 
    # Delete the selected attendance settings if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleAttendanceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceSettingIDs = $_POST['attendance_setting_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($attendanceSettingIDs as $attendanceSettingID){
            $this->attendanceSettingModel->deleteAttendanceSetting($attendanceSettingID);
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
    # Function: duplicateAttendanceSetting
    # Description: 
    # Duplicates the attendance setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateAttendanceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceSettingID = htmlspecialchars($_POST['attendance_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAttendanceSettingExist = $this->attendanceSettingModel->checkAttendanceSettingExist($attendanceSettingID);
        $total = $checkAttendanceSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $attendanceSettingID = $this->attendanceSettingModel->duplicateAttendanceSetting($attendanceSettingID, $userID);

        echo json_encode(['success' => true, 'attendanceSettingID' =>  $this->securityModel->encryptData($attendanceSettingID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAttendanceSettingDetails
    # Description: 
    # Handles the retrieval of attendance setting details such as attendance setting name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getAttendanceSettingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['attendance_setting_id']) && !empty($_POST['attendance_setting_id'])) {
            $userID = $_SESSION['user_id'];
            $attendanceSettingID = $_POST['attendance_setting_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $attendanceSettingDetails = $this->attendanceSettingModel->getAttendanceSetting($attendanceSettingID);

            $response = [
                'success' => true,
                'attendanceSettingName' => $attendanceSettingDetails['attendance_setting_name'],
                'attendanceSettingDescription' => $attendanceSettingDetails['attendance_setting_description'],
                'value' => $attendanceSettingDetails['value']
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
require_once '../model/attendance-setting-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new AttendanceSettingController(new AttendanceSettingModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>