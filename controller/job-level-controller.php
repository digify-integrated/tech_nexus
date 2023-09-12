<?php
session_start();

# -------------------------------------------------------------
#
# Function: JobLevelController
# Description: 
# The JobLevelController class handles job level related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class JobLevelController {
    private $jobLevelModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided JobLevelModel, UserModel and SecurityModel instances.
    # These instances are used for job level related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param JobLevelModel $jobLevelModel     The JobLevelModel instance for job level related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(JobLevelModel $jobLevelModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->jobLevelModel = $jobLevelModel;
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
                case 'save job level':
                    $this->saveJobLevel();
                    break;
                case 'get job level details':
                    $this->getJobLevelDetails();
                    break;
                case 'delete job level':
                    $this->deleteJobLevel();
                    break;
                case 'delete multiple job level':
                    $this->deleteMultipleJobLevel();
                    break;
                case 'duplicate job level':
                    $this->duplicateJobLevel();
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
    # Function: saveJobLevel
    # Description: 
    # Updates the existing job level if it exists; otherwise, inserts a new job level.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveJobLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobLevelID = isset($_POST['job_level_id']) ? htmlspecialchars($_POST['job_level_id'], ENT_QUOTES, 'UTF-8') : null;
        $currentLevel = htmlspecialchars($_POST['current_level'], ENT_QUOTES, 'UTF-8');
        $functionalLevel = htmlspecialchars($_POST['functional_level'], ENT_QUOTES, 'UTF-8');
        $rank = htmlspecialchars($_POST['rank'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobLevelExist = $this->jobLevelModel->checkJobLevelExist($jobLevelID);
        $total = $checkJobLevelExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->jobLevelModel->updateJobLevel($jobLevelID, $currentLevel, $rank, $functionalLevel, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'jobLevelID' => $this->securityModel->encryptData($jobLevelID)]);
            exit;
        } 
        else {
            $jobLevelID = $this->jobLevelModel->insertJobLevel($currentLevel, $rank, $functionalLevel, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'jobLevelID' => $this->securityModel->encryptData($jobLevelID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobLevel
    # Description: 
    # Delete the job level if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteJobLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobLevelID = htmlspecialchars($_POST['job_level_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobLevelExist = $this->jobLevelModel->checkJobLevelExist($jobLevelID);
        $total = $checkJobLevelExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->jobLevelModel->deleteJobLevel($jobLevelID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleJobLevel
    # Description: 
    # Delete the selected job levels if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleJobLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobLevelIDs = $_POST['job_level_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($jobLevelIDs as $jobLevelID){
            $this->jobLevelModel->deleteJobLevel($jobLevelID);
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
    # Function: duplicateJobLevel
    # Description: 
    # Duplicates the job level if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateJobLevel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $jobLevelID = htmlspecialchars($_POST['job_level_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkJobLevelExist = $this->jobLevelModel->checkJobLevelExist($jobLevelID);
        $total = $checkJobLevelExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $jobLevelID = $this->jobLevelModel->duplicateJobLevel($jobLevelID, $userID);

        echo json_encode(['success' => true, 'jobLevelID' =>  $this->securityModel->encryptData($jobLevelID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobLevelDetails
    # Description: 
    # Handles the retrieval of job level details such as current level, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getJobLevelDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['job_level_id']) && !empty($_POST['job_level_id'])) {
            $userID = $_SESSION['user_id'];
            $jobLevelID = $_POST['job_level_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $jobLevelDetails = $this->jobLevelModel->getJobLevel($jobLevelID);

            $response = [
                'success' => true,
                'currentLevel' => $jobLevelDetails['current_level'],
                'rank' => $jobLevelDetails['rank'],
                'functionalLevel' => $jobLevelDetails['functional_level']
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
require_once '../model/job-level-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new JobLevelController(new JobLevelModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>