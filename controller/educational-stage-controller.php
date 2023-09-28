<?php
session_start();

# -------------------------------------------------------------
#
# Function: EducationalStageController
# Description: 
# The EducationalStageController class handles educational stage related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class EducationalStageController {
    private $educationalStageModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided EducationalStageModel, UserModel and SecurityModel instances.
    # These instances are used for educational stage related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param EducationalStageModel $educationalStageModel     The EducationalStageModel instance for educational stage related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EducationalStageModel $educationalStageModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->educationalStageModel = $educationalStageModel;
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
                case 'save educational stage':
                    $this->saveEducationalStage();
                    break;
                case 'get educational stage details':
                    $this->getEducationalStageDetails();
                    break;
                case 'delete educational stage':
                    $this->deleteEducationalStage();
                    break;
                case 'delete multiple educational stage':
                    $this->deleteMultipleEducationalStage();
                    break;
                case 'duplicate educational stage':
                    $this->duplicateEducationalStage();
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
    # Function: saveEducationalStage
    # Description: 
    # Updates the existing educational stage if it exists; otherwise, inserts a new educational stage.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveEducationalStage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $educationalStageID = isset($_POST['educational_stage_id']) ? htmlspecialchars($_POST['educational_stage_id'], ENT_QUOTES, 'UTF-8') : null;
        $educationalStageName = htmlspecialchars($_POST['educational_stage_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEducationalStageExist = $this->educationalStageModel->checkEducationalStageExist($educationalStageID);
        $total = $checkEducationalStageExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->educationalStageModel->updateEducationalStage($educationalStageID, $educationalStageName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'educationalStageID' => $this->securityModel->encryptData($educationalStageID)]);
            exit;
        } 
        else {
            $educationalStageID = $this->educationalStageModel->insertEducationalStage($educationalStageName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'educationalStageID' => $this->securityModel->encryptData($educationalStageID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteEducationalStage
    # Description: 
    # Delete the educational stage if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteEducationalStage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $educationalStageID = htmlspecialchars($_POST['educational_stage_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEducationalStageExist = $this->educationalStageModel->checkEducationalStageExist($educationalStageID);
        $total = $checkEducationalStageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->educationalStageModel->deleteEducationalStage($educationalStageID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleEducationalStage
    # Description: 
    # Delete the selected educational stages if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleEducationalStage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $educationalStageIDs = $_POST['educational_stage_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($educationalStageIDs as $educationalStageID){
            $this->educationalStageModel->deleteEducationalStage($educationalStageID);
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
    # Function: duplicateEducationalStage
    # Description: 
    # Duplicates the educational stage if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateEducationalStage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $educationalStageID = htmlspecialchars($_POST['educational_stage_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEducationalStageExist = $this->educationalStageModel->checkEducationalStageExist($educationalStageID);
        $total = $checkEducationalStageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $educationalStageID = $this->educationalStageModel->duplicateEducationalStage($educationalStageID, $userID);

        echo json_encode(['success' => true, 'educationalStageID' =>  $this->securityModel->encryptData($educationalStageID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEducationalStageDetails
    # Description: 
    # Handles the retrieval of educational stage details such as educational stage name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getEducationalStageDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['educational_stage_id']) && !empty($_POST['educational_stage_id'])) {
            $userID = $_SESSION['user_id'];
            $educationalStageID = $_POST['educational_stage_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $educationalStageDetails = $this->educationalStageModel->getEducationalStage($educationalStageID);

            $response = [
                'success' => true,
                'educationalStageName' => $educationalStageDetails['educational_stage_name']
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
require_once '../model/educational-stage-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new EducationalStageController(new EducationalStageModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>