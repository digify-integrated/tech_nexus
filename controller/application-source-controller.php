<?php
session_start();

# -------------------------------------------------------------
#
# Function: ApplicationSourceController
# Description: 
# The ApplicationSourceController class handles application source related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ApplicationSourceController {
    private $applicationSourceModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ApplicationSourceModel, UserModel and SecurityModel instances.
    # These instances are used for application source related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ApplicationSourceModel $applicationSourceModel     The ApplicationSourceModel instance for application source related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ApplicationSourceModel $applicationSourceModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->applicationSourceModel = $applicationSourceModel;
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
                case 'save application source':
                    $this->saveApplicationSource();
                    break;
                case 'get application source details':
                    $this->getApplicationSourceDetails();
                    break;
                case 'delete application source':
                    $this->deleteApplicationSource();
                    break;
                case 'delete multiple application source':
                    $this->deleteMultipleApplicationSource();
                    break;
                case 'duplicate application source':
                    $this->duplicateApplicationSource();
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
    # Function: saveApplicationSource
    # Description: 
    # Updates the existing application source if it exists; otherwise, inserts a new application source.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveApplicationSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $applicationSourceID = isset($_POST['application_source_id']) ? htmlspecialchars($_POST['application_source_id'], ENT_QUOTES, 'UTF-8') : null;
        $applicationSourceName = htmlspecialchars($_POST['application_source_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkApplicationSourceExist = $this->applicationSourceModel->checkApplicationSourceExist($applicationSourceID);
        $total = $checkApplicationSourceExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->applicationSourceModel->updateApplicationSource($applicationSourceID, $applicationSourceName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'applicationSourceID' => $this->securityModel->encryptData($applicationSourceID)]);
            exit;
        } 
        else {
            $applicationSourceID = $this->applicationSourceModel->insertApplicationSource($applicationSourceName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'applicationSourceID' => $this->securityModel->encryptData($applicationSourceID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteApplicationSource
    # Description: 
    # Delete the application source if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteApplicationSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $applicationSourceID = htmlspecialchars($_POST['application_source_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkApplicationSourceExist = $this->applicationSourceModel->checkApplicationSourceExist($applicationSourceID);
        $total = $checkApplicationSourceExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->applicationSourceModel->deleteApplicationSource($applicationSourceID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleApplicationSource
    # Description: 
    # Delete the selected application sources if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleApplicationSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $applicationSourceIDs = $_POST['application_source_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($applicationSourceIDs as $applicationSourceID){
            $this->applicationSourceModel->deleteApplicationSource($applicationSourceID);
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
    # Function: duplicateApplicationSource
    # Description: 
    # Duplicates the application source if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateApplicationSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $applicationSourceID = htmlspecialchars($_POST['application_source_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkApplicationSourceExist = $this->applicationSourceModel->checkApplicationSourceExist($applicationSourceID);
        $total = $checkApplicationSourceExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $applicationSourceID = $this->applicationSourceModel->duplicateApplicationSource($applicationSourceID, $userID);

        echo json_encode(['success' => true, 'applicationSourceID' =>  $this->securityModel->encryptData($applicationSourceID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getApplicationSourceDetails
    # Description: 
    # Handles the retrieval of application source details such as application source name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getApplicationSourceDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['application_source_id']) && !empty($_POST['application_source_id'])) {
            $userID = $_SESSION['user_id'];
            $applicationSourceID = $_POST['application_source_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $applicationSourceDetails = $this->applicationSourceModel->getApplicationSource($applicationSourceID);

            $response = [
                'success' => true,
                'applicationSourceName' => $applicationSourceDetails['application_source_name']
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
require_once '../model/application-source-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ApplicationSourceController(new ApplicationSourceModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>