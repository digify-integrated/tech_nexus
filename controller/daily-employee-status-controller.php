<?php
session_start();

# -------------------------------------------------------------
#
# Function: BrandController
# Description: 
# The BrandController class handles brand related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DailyEmployeeStatusController {
    private $dailyEmployeeStatusModel;
    private $userModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BrandModel, UserModel and SecurityModel instances.
    # These instances are used for brand related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BrandModel $dailyEmployeeStatusModel     The BrandModel instance for brand related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DailyEmployeeStatusModel $dailyEmployeeStatusModel, UserModel $userModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->dailyEmployeeStatusModel = $dailyEmployeeStatusModel;
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
                case 'add remarks':
                    $this->addDailyEmployeeStatusRemarks();
                    break;
                case 'get daily employee status count':
                    $this->getDailyEmployeeStatusCount();
                    break;
                case 'change attendance status':
                    $this->changeAttendanceStatus();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    public function addDailyEmployeeStatusRemarks() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employee_daily_status_id = $_POST['employee_daily_status_id'];
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->dailyEmployeeStatusModel->updateDailyEmployeeStatusRemarks($employee_daily_status_id, $remarks, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function changeAttendanceStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $employee_daily_status_ids = $_POST['employee_daily_status_id'];
        $type = $_POST['type'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($employee_daily_status_ids as $employee_daily_status_id) {            
            $this->dailyEmployeeStatusModel->updateDailyEmployeeStatus($employee_daily_status_id, $type, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDailyEmployeeStatusCount
    # Description: 
    # Handles the retrieval of brand details such as brand name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDailyEmployeeStatusCount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['filter_attendance_date']) && !empty($_POST['filter_attendance_date'])) {
            $userID = $_SESSION['user_id'];
            $filter_attendance_date = $this->systemModel->checkDate('empty', $_POST['filter_attendance_date'], '', 'Y-m-d H:i:s', '');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $present = $this->dailyEmployeeStatusModel->getDailyEmployeeStatusCount('Present', $filter_attendance_date);
            $late = $this->dailyEmployeeStatusModel->getDailyEmployeeStatusCount('Late', $filter_attendance_date);
            $absent = $this->dailyEmployeeStatusModel->getDailyEmployeeStatusCount('Absent', $filter_attendance_date);
            $onLeave = $this->dailyEmployeeStatusModel->getDailyEmployeeStatusCount('On-Leave', $filter_attendance_date);
            $officialBusiness = $this->dailyEmployeeStatusModel->getDailyEmployeeStatusCount('Official Business', $filter_attendance_date);

            $response = [
                'success' => true,
                'presentCount' => number_format($present['total_count'],0),
                'lateCount' => number_format($late['total_count'],0),
                'absentCount' => number_format($absent['total_count'],0),
                'onLeaveCount' => number_format($onLeave['total_count'],0),
                'officialBusinessCount' => number_format($officialBusiness['total_count'],0),
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
require_once '../model/daily-employee-status-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new DailyEmployeeStatusController(new DailyEmployeeStatusModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>