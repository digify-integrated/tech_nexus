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
    # - @param departmentModel $departmentModel     The DepartmentModel instance for department related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(JobPositionModel $jobPositionModel, UserModel $userModel, DepartmentModel $departmentModel, SecurityModel $securityModel) {
        $this->jobPositionModel = $jobPositionModel;
        $this->userModel = $userModel;
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
                case 'save job position responsibility':
                    $this->saveJobPositionResponsibility();
                    break;
                case 'save job position requirement':
                    $this->saveJobPositionRequirement();
                    break;
                case 'save job position qualification':
                    $this->saveJobPositionQualification();
                    break;
                case 'get job position details':
                    $this->getJobPositionDetails();
                    break;
                case 'get job position responsibility details':
                    $this->getJobPositionResponsibilityDetails();
                    break;
                case 'get job position requirement details':
                    $this->getJobPositionRequirementDetails();
                    break;
                case 'get job position qualification details':
                    $this->getJobPositionQualificationDetails();
                    break;
                case 'delete job position':
                    $this->deleteJobPosition();
                    break;
                case 'delete job position responsibility':
                    $this->deleteJobPositionResponsibility();
                    break;
                case 'delete job position requirement':
                    $this->deleteJobPositionRequirement();
                    break;
                case 'delete job position qualification':
                    $this->deleteJobPositionQualification();
                    break;
                case 'delete multiple job position':
                    $this->deleteMultipleJobPosition();
                    break;
                case 'duplicate job position':
                    $this->duplicateJobPosition();
                    break;
                case 'start job position recruitment':
                    $this->startJobPositionRecruitment();
                    break;
                case 'stop job position recruitment':
                    $this->stopJobPositionRecruitment();
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
        $jobPositionDescription = htmlspecialchars($_POST['job_position_description'], ENT_QUOTES, 'UTF-8');
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionExist = $this->jobPositionModel->checkJobPositionExist($jobPositionID);
        $total = $checkJobPositionExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->jobPositionModel->updateJobPosition($jobPositionID, $jobPositionName, $jobPositionDescription, $departmentID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'jobPositionID' => $this->securityModel->encryptData($jobPositionID)]);
            exit;
        } 
        else {
            $jobPositionID = $this->jobPositionModel->insertJobPosition($jobPositionName, $jobPositionDescription, $departmentID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'jobPositionID' => $this->securityModel->encryptData($jobPositionID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveJobPositionResponsibility
    # Description: 
    # Updates the existing job position responsibility if it exists; otherwise, inserts a new job position responsibility.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveJobPositionResponsibility() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionResponsibilityID = isset($_POST['job_position_responsibility_id']) ? htmlspecialchars($_POST['job_position_responsibility_id'], ENT_QUOTES, 'UTF-8') : null;
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
        $responsibility = htmlspecialchars($_POST['responsibility'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionResponsibilityExist = $this->jobPositionModel->checkJobPositionResponsibilityExist($jobPositionResponsibilityID);
        $total = $checkJobPositionResponsibilityExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->jobPositionModel->updateJobPositionResponsibility($jobPositionResponsibilityID, $responsibility, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $jobPositionID = $this->jobPositionModel->insertJobPositionResponsibility($jobPositionID, $responsibility, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: saveJobPositionRequirement
    # Description: 
    # Updates the existing job position requirement if it exists; otherwise, inserts a new job position requirement.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveJobPositionRequirement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionRequirementID = isset($_POST['job_position_requirement_id']) ? htmlspecialchars($_POST['job_position_requirement_id'], ENT_QUOTES, 'UTF-8') : null;
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
        $requirement = htmlspecialchars($_POST['requirement'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionRequirementExist = $this->jobPositionModel->checkJobPositionRequirementExist($jobPositionRequirementID);
        $total = $checkJobPositionRequirementExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->jobPositionModel->updateJobPositionRequirement($jobPositionRequirementID, $requirement, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $jobPositionID = $this->jobPositionModel->insertJobPositionRequirement($jobPositionID, $requirement, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: saveJobPositionQualification
    # Description: 
    # Updates the existing job position qualification if it exists; otherwise, inserts a new job position qualification.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveJobPositionQualification() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionQualificationID = isset($_POST['job_position_qualification_id']) ? htmlspecialchars($_POST['job_position_qualification_id'], ENT_QUOTES, 'UTF-8') : null;
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
        $qualification = htmlspecialchars($_POST['qualification'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionQualificationExist = $this->jobPositionModel->checkJobPositionQualificationExist($jobPositionQualificationID);
        $total = $checkJobPositionQualificationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->jobPositionModel->updateJobPositionQualification($jobPositionQualificationID, $qualification, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $jobPositionID = $this->jobPositionModel->insertJobPositionQualification($jobPositionID, $qualification, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Start methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: startJobPositionRecruitment
    # Description: 
    # Updates the job position recruitment status to start.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function startJobPositionRecruitment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
        $expectedNewEmployees = htmlspecialchars($_POST['expected_new_employees'], ENT_QUOTES, 'UTF-8');
    
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
    
        $this->jobPositionModel->updateJobPositionRecruitmentStatus($jobPositionID, 1, $expectedNewEmployees, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Stop methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: stopJobPositionRecruitment
    # Description: 
    # Updates the job position recruitment status to stop.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function stopJobPositionRecruitment() {
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
    
        $this->jobPositionModel->updateJobPositionRecruitmentStatus($jobPositionID, 0, 0, $userID);
            
        echo json_encode(['success' => true]);
        exit;
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
    #
    # Function: deleteJobPositionResponsibility
    # Description: 
    # Delete the job position responsibility if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteJobPositionResponsibility() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionResponsibilityID = htmlspecialchars($_POST['job_position_responsibility_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionResponsibilityExist = $this->jobPositionModel->checkJobPositionResponsibilityExist($jobPositionResponsibilityID);
        $total = $checkJobPositionResponsibilityExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->jobPositionModel->deleteJobPositionResponsibility($jobPositionResponsibilityID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobPositionRequirement
    # Description: 
    # Delete the job position requirement if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteJobPositionRequirement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionRequirementID = htmlspecialchars($_POST['job_position_requirement_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionRequirementExist = $this->jobPositionModel->checkJobPositionRequirementExist($jobPositionRequirementID);
        $total = $checkJobPositionRequirementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->jobPositionModel->deleteJobPositionRequirement($jobPositionRequirementID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobPositionQualification
    # Description: 
    # Delete the job position qualification if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteJobPositionQualification() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobPositionQualificationID = htmlspecialchars($_POST['job_position_qualification_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobPositionQualificationExist = $this->jobPositionModel->checkJobPositionQualificationExist($jobPositionQualificationID);
        $total = $checkJobPositionQualificationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->jobPositionModel->deleteJobPositionQualification($jobPositionQualificationID);
            
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

        $getLinkedJobPositionResponsibilities = $this->jobPositionModel->getLinkedJobPositionResponsibility($jobPositionID);
        $getLinkedJobPositionRequirements = $this->jobPositionModel->getLinkedJobPositionRequirement($jobPositionID);
        $getLinkedJobPositionQualifications = $this->jobPositionModel->getLinkedJobPositionQualification($jobPositionID);

        $jobPositionID = $this->jobPositionModel->duplicateJobPosition($jobPositionID, $userID);

        foreach ($getLinkedJobPositionResponsibilities as $getLinkedJobPositionResponsibility) {
            if(!empty($getLinkedJobPositionResponsibility['responsibility'])){
                $this->jobPositionModel->insertJobPositionResponsibility($jobPositionID, $getLinkedJobPositionResponsibility['responsibility'], $userID);
            }
        }

        foreach ($getLinkedJobPositionRequirements as $getLinkedJobPositionRequirement) {
            if(!empty($getLinkedJobPositionRequirement['requirement'])){
                $this->jobPositionModel->insertJobPositionRequirement($jobPositionID, $getLinkedJobPositionRequirement['requirement'], $userID);
            }
        }

        foreach ($getLinkedJobPositionQualifications as $getLinkedJobPositionQualification) {
            if(!empty($getLinkedJobPositionQualification['qualification'])){
                $this->jobPositionModel->insertJobPositionQualification($jobPositionID, $getLinkedJobPositionQualification['qualification'], $userID);
            }
        }

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
                'jobPositionDescription' => $jobPositionDetails['job_position_description'],
                'recruitmentStatus' => $jobPositionDetails['recruitment_status'],
                'departmentID' => $departmentID,
                'departmentName' => $departmentName
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPositionResponsibilityDetails
    # Description: 
    # Handles the retrieval of job position responsibility details such as responsibility, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getJobPositionResponsibilityDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['job_position_responsibility_id']) && !empty($_POST['job_position_responsibility_id'])) {
            $userID = $_SESSION['user_id'];
            $jobPositionResponsibilityID = $_POST['job_position_responsibility_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $jobPositionResponsibilityDetails = $this->jobPositionModel->getJobPositionResponsibility($jobPositionResponsibilityID);

            $response = [
                'success' => true,
                'responsibility' => $jobPositionResponsibilityDetails['responsibility']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPositionRequirementDetails
    # Description: 
    # Handles the retrieval of job position requirement details such as requirement, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getJobPositionRequirementDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['job_position_requirement_id']) && !empty($_POST['job_position_requirement_id'])) {
            $userID = $_SESSION['user_id'];
            $jobPositionRequirementID = $_POST['job_position_requirement_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $jobPositionRequirementDetails = $this->jobPositionModel->getJobPositionRequirement($jobPositionRequirementID);

            $response = [
                'success' => true,
                'requirement' => $jobPositionRequirementDetails['requirement']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPositionQualificationDetails
    # Description: 
    # Handles the retrieval of job position qualification details such as qualification, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getJobPositionQualificationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['job_position_qualification_id']) && !empty($_POST['job_position_qualification_id'])) {
            $userID = $_SESSION['user_id'];
            $jobPositionQualificationID = $_POST['job_position_qualification_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $jobPositionQualificationDetails = $this->jobPositionModel->getJobPositionQualification($jobPositionQualificationID);

            $response = [
                'success' => true,
                'qualification' => $jobPositionQualificationDetails['qualification']
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
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new JobPositionController(new JobPositionModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new DepartmentModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>