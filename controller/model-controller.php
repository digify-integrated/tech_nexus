<?php
session_start();

# -------------------------------------------------------------
#
# Function: ModelController
# Description: 
# The ModelController class handles model related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ModelController {
    private $modelModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ModelModel, UserModel and SecurityModel instances.
    # These instances are used for model related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ModelModel $modelModel     The ModelModel instance for model related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ModelModel $modelModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->modelModel = $modelModel;
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
                case 'save model':
                    $this->saveModel();
                    break;
                case 'get model details':
                    $this->getModelDetails();
                    break;
                case 'delete model':
                    $this->deleteModel();
                    break;
                case 'delete multiple model':
                    $this->deleteMultipleModel();
                    break;
                case 'duplicate model':
                    $this->duplicateModel();
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
    # Function: saveModel
    # Description: 
    # Updates the existing model if it exists; otherwise, inserts a new model.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveModel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modelID = isset($_POST['model_id']) ? htmlspecialchars($_POST['model_id'], ENT_QUOTES, 'UTF-8') : null;
        $modelName = htmlspecialchars($_POST['model_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkModelExist = $this->modelModel->checkModelExist($modelID);
        $total = $checkModelExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->modelModel->updateModel($modelID, $modelName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'modelID' => $this->securityModel->encryptData($modelID)]);
            exit;
        } 
        else {
            $modelID = $this->modelModel->insertModel($modelName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'modelID' => $this->securityModel->encryptData($modelID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteModel
    # Description: 
    # Delete the model if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteModel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modelID = htmlspecialchars($_POST['model_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkModelExist = $this->modelModel->checkModelExist($modelID);
        $total = $checkModelExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->modelModel->deleteModel($modelID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleModel
    # Description: 
    # Delete the selected models if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleModel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modelIDs = $_POST['model_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($modelIDs as $modelID){
            $this->modelModel->deleteModel($modelID);
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
    # Function: duplicateModel
    # Description: 
    # Duplicates the model if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateModel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modelID = htmlspecialchars($_POST['model_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkModelExist = $this->modelModel->checkModelExist($modelID);
        $total = $checkModelExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $modelID = $this->modelModel->duplicateModel($modelID, $userID);

        echo json_encode(['success' => true, 'modelID' =>  $this->securityModel->encryptData($modelID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getModelDetails
    # Description: 
    # Handles the retrieval of model details such as model name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getModelDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['model_id']) && !empty($_POST['model_id'])) {
            $userID = $_SESSION['user_id'];
            $modelID = $_POST['model_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $modelDetails = $this->modelModel->getModel($modelID);

            $response = [
                'success' => true,
                'modelName' => $modelDetails['model_name']
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
require_once '../model/model-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ModelController(new ModelModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>