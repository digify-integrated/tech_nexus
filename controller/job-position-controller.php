<?php
session_start();

# -------------------------------------------------------------
#
# Function: JobPositionController
# Description: 
# The JobPositionController class handles job position related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class JobPositionController {
    private $jobPositionModel;
    private $userModel;
    private $roleModel;
    private $departmentModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided JobPositionModel, UserModel and SecurityModel instances.
    # These instances are used for job position related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param JobPositionModel $jobPositionModel     The JobPositionModel instance for job position related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param departmentModel $departmentModel     The DepartmentModel instance for department related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(JobPositionModel $jobPositionModel, UserModel $userModel, RoleModel $roleModel, DepartmentModel $departmentModel, SecurityModel $securityModel) {
        $this->jobPositionModel = $jobPositionModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->departmentModel = $departmentModel;
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
                case 'save job position':
                    $this->saveJobPosition();
                    break;
                case 'get job position details':
                    $this->getJobPositionDetails();
                    break;
                case 'delete job position':
                    $this->deleteJobPosition();
                    break;
                case 'delete multiple job position':
                    $this->deleteMultipleJobPosition();
                    break;
                case 'duplicate job position':
                    $this->duplicateJobPosition();
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
    # Function: saveJobPosition
    # Description: 
    # Updates the existing job position if it exists; otherwise, inserts a new job position.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveJobPosition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionID = isset($_POST['job_position_id']) ? htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8') : null;
        $jobPositionName = htmlspecialchars($_POST['job_position_name'], ENT_QUOTES, 'UTF-8');
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
        $expectedNewEmployees = htmlspecialchars($_POST['expected_new_employees'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionExist = $this->jobPositionModel->checkJobPositionExist($jobPositionID);
        $total = $checkJobPositionExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->jobPositionModel->updateJobPosition($jobPositionID, $jobPositionName, $departmentID, $expectedNewEmployees, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'jobPositionID' => $this->securityModel->encryptData($jobPositionID)]);
            exit;
        } 
        else {
            $jobPositionID = $this->jobPositionModel->insertJobPosition($jobPositionName, $departmentID, $expectedNewEmployees, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'jobPositionID' => $this->securityModel->encryptData($jobPositionID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobPosition
    # Description: 
    # Delete the job position if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteJobPosition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionExist = $this->jobPositionModel->checkJobPositionExist($jobPositionID);
        $total = $checkJobPositionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->jobPositionModel->deleteJobPosition($jobPositionID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleJobPosition
    # Description: 
    # Delete the selected job positions if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleJobPosition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionIDs = $_POST['job_position_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($jobPositionIDs as $jobPositionID){
            $this->jobPositionModel->deleteJobPosition($jobPositionID);
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
    # Function: duplicateJobPosition
    # Description: 
    # Duplicates the job position if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateJobPosition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionExist = $this->jobPositionModel->checkJobPositionExist($jobPositionID);
        $total = $checkJobPositionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $jobPositionID = $this->jobPositionModel->duplicateJobPosition($jobPositionID, $userID);

        echo json_encode(['success' => true, 'jobPositionID' =>  $this->securityModel->encryptData($jobPositionID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPositionDetails
    # Description: 
    # Handles the retrieval of job position details such as job position name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getJobPositionDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])) {
            $userID = $_SESSION['user_id'];
            $jobPositionID = $_POST['job_position_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $jobPositionDetails = $this->jobPositionModel->getJobPosition($jobPositionID);
            $departmentID = $jobPositionDetails['department_id'];

            $departmentDetails = $this->departmentModel->getDepartment($departmentID);
            $departmentName = $departmentDetails['department_name'] ?? null;

            $response = [
                'success' => true,
                'jobPositionName' => $jobPositionDetails['job_position_name'],
                'recruitmentStatus' => $jobPositionDetails['recruitment_status'],
                'departmentID' => $departmentID,
                'departmentName' => $departmentName,
                'expectedNewEmployees' => $jobPositionDetails['expected_new_employees']
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
require_once '../model/job-position-model.php';
require_once '../model/department-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new JobPositionController(new JobPositionModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new DepartmentModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>