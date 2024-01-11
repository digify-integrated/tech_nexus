<?php
session_start();

# -------------------------------------------------------------
#
# Function: WorkScheduleController
# Description: 
# The WorkScheduleController class handles work schedule related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class WorkScheduleController {
    private $workScheduleModel;
    private $workScheduleTypeModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided WorkScheduleModel, UserModel and SecurityModel instances.
    # These instances are used for work schedule related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param WorkScheduleModel $workScheduleModel     The WorkScheduleModel instance for work schedule related operations.
    # - @param WorkScheduleTypeModel $workScheduleTypeModel     The WorkScheduleTypeModel instance for work schedule type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(WorkScheduleModel $workScheduleModel, UserModel $userModel, WorkScheduleTypeModel $workScheduleTypeModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->workScheduleModel = $workScheduleModel;
        $this->workScheduleTypeModel = $workScheduleTypeModel;
        $this->userModel = $userModel;
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
                case 'save work schedule':
                    $this->saveWorkSchedule();
                    break;
                case 'save fixed work hours':
                    $this->saveFixedWorkHours();
                    break;
                case 'save flexible work hours':
                    $this->saveFlexibleWorkHours();
                    break;
                case 'get work schedule details':
                    $this->getWorkScheduleDetails();
                    break;
                case 'get fixed work hours details':
                    $this->getWorkHoursDetails();
                    break;
                case 'get flexible work hours details':
                    $this->getWorkHoursDetails();
                    break;
                case 'delete work schedule':
                    $this->deleteWorkSchedule();
                    break;
                case 'delete work hours':
                    $this->deleteWorkHours();
                    break;
                case 'delete multiple work schedule':
                    $this->deleteMultipleWorkSchedule();
                    break;
                case 'duplicate work schedule':
                    $this->duplicateWorkSchedule();
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
    # Function: saveWorkSchedule
    # Description: 
    # Updates the existing work schedule if it exists; otherwise, inserts a new work schedule.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveWorkSchedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleID = isset($_POST['work_schedule_id']) ? htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8') : null;
        $workScheduleName = htmlspecialchars($_POST['work_schedule_name'], ENT_QUOTES, 'UTF-8');
        $workScheduleDescription = htmlspecialchars($_POST['work_schedule_description'], ENT_QUOTES, 'UTF-8');
        $workScheduleTypeID = htmlspecialchars($_POST['work_schedule_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkScheduleExist = $this->workScheduleModel->checkWorkScheduleExist($workScheduleID);
        $total = $checkWorkScheduleExist['total'] ?? 0;
    
        if ($total > 0) {
            $workScheduleDetails = $this->workScheduleModel->getWorkSchedule($workScheduleID);
            $workScheduleTypeIDCurrent = $workScheduleDetails['work_schedule_type_id'];

            if($workScheduleTypeIDCurrent != $workScheduleTypeID){
                $this->workScheduleModel->deleteLinkedWorkHours($workScheduleID);
            }            

            $this->workScheduleModel->updateWorkSchedule($workScheduleID, $workScheduleName, $workScheduleDescription, $workScheduleTypeID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'workScheduleID' => $this->securityModel->encryptData($workScheduleID)]);
            exit;
        } 
        else {
            $workScheduleID = $this->workScheduleModel->insertWorkSchedule($workScheduleName, $workScheduleDescription, $workScheduleTypeID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'workScheduleID' => $this->securityModel->encryptData($workScheduleID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveFixedWorkHours
    # Description: 
    # Updates the existing fixed work hours if it exists; otherwise, inserts a new fixed work hours.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveFixedWorkHours() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workHoursID = isset($_POST['work_hours_id']) ? htmlspecialchars($_POST['work_hours_id'], ENT_QUOTES, 'UTF-8') : null;
        $workScheduleID = htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8');
        $startDayOfWeek = htmlspecialchars($_POST['start_day_of_week'], ENT_QUOTES, 'UTF-8');
        $endDayOfWeek = htmlspecialchars($_POST['end_day_of_week'], ENT_QUOTES, 'UTF-8');
        $workFrom = $this->systemModel->checkDate('empty', $_POST['work_from'], '', 'H:i:s', '');
        $workTo = $this->systemModel->checkDate('empty', $_POST['work_to'], '', 'H:i:s', '');
        $lunchStartDayOfWeek = htmlspecialchars($_POST['lunch_start_day_of_week'], ENT_QUOTES, 'UTF-8');
        $lunchEndDayOfWeek = htmlspecialchars($_POST['lunch_end_day_of_week'], ENT_QUOTES, 'UTF-8');
        $lunchStart = $this->systemModel->checkDate('empty', $_POST['lunch_start'], '', 'H:i:s', '');
        $lunchEnd = $this->systemModel->checkDate('empty', $_POST['lunch_end'], '', 'H:i:s', '');
        $notes = htmlspecialchars($_POST['notes'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        /*$checkFixedWorkHoursOverlap = $this->workScheduleModel->checkFixedWorkHoursOverlap($workHoursID, $workScheduleID, $dayOfWeek, $workFrom, $workTo);
        $total = $checkFixedWorkHoursOverlap['total'] ?? 0;
    
        if ($total > 0) {
            echo json_encode(['success' => false, 'Overlap' => true]);
            exit;
        }*/
    
        $checkWorkHoursExist = $this->workScheduleModel->checkWorkHoursExist($workHoursID);
        $total = $checkWorkHoursExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->workScheduleModel->updateWorkHours($workHoursID, $workScheduleID, null, $startDayOfWeek, $workFrom, null, $endDayOfWeek, $workTo, null, $lunchStartDayOfWeek, $lunchStart, null, $lunchEndDayOfWeek, $lunchEnd, $notes, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->workScheduleModel->insertWorkHours($workScheduleID, null, $startDayOfWeek, $workFrom, null, $endDayOfWeek, $workTo, null, $lunchStartDayOfWeek, $lunchStart, null, $lunchEndDayOfWeek, $lunchEnd, $notes, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveFlexibleWorkHours
    # Description: 
    # Updates the existing flexible work hours if it exists; otherwise, inserts a new flexible work hours.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveFlexibleWorkHours() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workHoursID = isset($_POST['work_hours_id']) ? htmlspecialchars($_POST['work_hours_id'], ENT_QUOTES, 'UTF-8') : null;
        $workScheduleID = htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8');
        $startWorkDate = $this->systemModel->checkDate('empty', $_POST['start_work_date'], '', 'Y-m-d', '');
        $endWorkDate = $this->systemModel->checkDate('empty', $_POST['end_work_date'], '', 'Y-m-d', '');
        $workFrom = $this->systemModel->checkDate('empty', $_POST['work_from'], '', 'H:i:s', '');
        $workTo = $this->systemModel->checkDate('empty', $_POST['work_to'], '', 'H:i:s', '');
        $lunchStartWorkDate = $this->systemModel->checkDate('empty', $_POST['lunch_start_work_date'], '', 'Y-m-d', '');
        $lunchEndWorkDate = $this->systemModel->checkDate('empty', $_POST['lunch_end_work_date'], '', 'Y-m-d', '');
        $lunchStart = $this->systemModel->checkDate('empty', $_POST['lunch_start'], '', 'H:i:s', '');
        $lunchEnd = $this->systemModel->checkDate('empty', $_POST['lunch_end'], '', 'H:i:s', '');
        $notes = htmlspecialchars($_POST['notes'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        /*$checkFlexibleWorkHoursOverlap = $this->workScheduleModel->checkFlexibleWorkHoursOverlap($workHoursID, $workScheduleID, $workDate, $workFrom, $workTo);
        $total = $checkFlexibleWorkHoursOverlap['total'] ?? 0;
    
        if ($total > 0) {
            echo json_encode(['success' => false, 'Overlap' => true]);
            exit;
        }*/
    
        $checkWorkHoursExist = $this->workScheduleModel->checkWorkHoursExist($workHoursID);
        $total = $checkWorkHoursExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->workScheduleModel->updateWorkHours($workHoursID, $workScheduleID, $startWorkDate, null, $workFrom, $endWorkDate, null, $workTo, $lunchStartWorkDate, null, $lunchStart, $lunchEndWorkDate, null, $lunchEnd, $notes, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->workScheduleModel->insertWorkHours($workScheduleID, $startWorkDate, null, $workFrom, $endWorkDate, null, $workTo, $lunchStartWorkDate, null, $lunchStart, $lunchEndWorkDate, null, $lunchEnd, $notes, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkSchedule
    # Description: 
    # Delete the work schedule if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteWorkSchedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleID = htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkScheduleExist = $this->workScheduleModel->checkWorkScheduleExist($workScheduleID);
        $total = $checkWorkScheduleExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->workScheduleModel->deleteWorkSchedule($workScheduleID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleWorkSchedule
    # Description: 
    # Delete the selected work schedules if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleWorkSchedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleIDs = $_POST['work_schedule_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($workScheduleIDs as $workScheduleID){
            $this->workScheduleModel->deleteWorkSchedule($workScheduleID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkHours
    # Description: 
    # Delete the work hours if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteWorkHours() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workHoursID = htmlspecialchars($_POST['work_hours_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkHoursExist = $this->workScheduleModel->checkWorkHoursExist($workHoursID);
        $total = $checkWorkHoursExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->workScheduleModel->deleteWorkHours($workHoursID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateWorkSchedule
    # Description: 
    # Duplicates the work schedule if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateWorkSchedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $workScheduleID = htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWorkScheduleExist = $this->workScheduleModel->checkWorkScheduleExist($workScheduleID);
        $total = $checkWorkScheduleExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $getLinkedWorkHours = $this->workScheduleModel->getLinkedWorkHours($workScheduleID);

        $workScheduleID = $this->workScheduleModel->duplicateWorkSchedule($workScheduleID, $userID);

        foreach ($getLinkedWorkHours as $getLinkedWorkHour) {
            if(!empty($getLinkedWorkHour['work_hours_id'])){
                $this->workScheduleModel->insertWorkHours($workScheduleID, $getLinkedWorkHour['work_date'], $getLinkedWorkHour['day_of_week'], $getLinkedWorkHour['day_period'], $getLinkedWorkHour['start_time'], $getLinkedWorkHour['end_time'], $getLinkedWorkHour['notes'], $userID);
            }
        }

        echo json_encode(['success' => true, 'workScheduleID' =>  $this->securityModel->encryptData($workScheduleID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkScheduleDetails
    # Description: 
    # Handles the retrieval of work schedule details such as work schedule name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getWorkScheduleDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['work_schedule_id']) && !empty($_POST['work_schedule_id'])) {
            $userID = $_SESSION['user_id'];
            $workScheduleID = $_POST['work_schedule_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $workScheduleDetails = $this->workScheduleModel->getWorkSchedule($workScheduleID);
            $workScheduleTypeID = $workScheduleDetails['work_schedule_type_id'];

            $workScheduleTypeDetails = $this->workScheduleTypeModel->getWorkScheduleType($workScheduleTypeID);
            $workScheduleTypeName = $workScheduleTypeDetails['work_schedule_type_name'];

            $response = [
                'success' => true,
                'workScheduleName' => $workScheduleDetails['work_schedule_name'],
                'workScheduleDescription' => $workScheduleDetails['work_schedule_description'],
                'workScheduleTypeID' => $workScheduleTypeID,
                'workScheduleTypeName' => $workScheduleTypeName
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkHoursDetails
    # Description: 
    # Handles the retrieval of work hours details such as work day, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getWorkHoursDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['work_hours_id']) && !empty($_POST['work_hours_id'])) {
            $userID = $_SESSION['user_id'];
            $workHoursID = $_POST['work_hours_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $workHoursDetails = $this->workScheduleModel->getWorkHours($workHoursID);

            $response = [
                'success' => true,
                'startWorkDate' => $this->systemModel->checkDate('empty', $workHoursDetails['start_work_date'], '', 'm/d/Y', ''),
                'startDayOfWeek' => $workHoursDetails['start_day_of_week'],
                'startTime' => $this->systemModel->checkDate('empty', $workHoursDetails['start_time'], '', 'H:i', ''),
                'endWorkDate' => $this->systemModel->checkDate('empty', $workHoursDetails['end_work_date'], '', 'm/d/Y', ''),
                'endDayOfWeek' => $workHoursDetails['end_day_of_week'],
                'endTime' => $this->systemModel->checkDate('empty', $workHoursDetails['end_time'], '', 'H:i', ''),
                'lunchBreakStartWorkDate' => $this->systemModel->checkDate('empty', $workHoursDetails['lunch_break_start_work_date'], '', 'm/d/Y', ''),
                'lunchBreakStartDayOfWeek' => $workHoursDetails['lunch_break_start_day_of_week'],
                'lunchBreakStartTime' => $this->systemModel->checkDate('empty', $workHoursDetails['lunch_break_start_time'], '', 'H:i', ''),
                'lunchBreakEndWorkDate' => $this->systemModel->checkDate('empty', $workHoursDetails['lunch_break_end_work_date'], '', 'm/d/Y', ''),
                'lunchBreakEndDayOfWeek' => $workHoursDetails['lunch_break_end_day_of_week'],
                'lunchBreakEndTime' => $this->systemModel->checkDate('empty', $workHoursDetails['lunch_break_end_time'], '', 'H:i', ''),
                'notes' => $workHoursDetails['notes']
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
require_once '../model/work-schedule-model.php';
require_once '../model/work-schedule-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new WorkScheduleController(new WorkScheduleModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new WorkScheduleTypeModel(new DatabaseModel), new SystemModel, new SecurityModel());
$controller->handleRequest();
?>