<?php
session_start();

# -------------------------------------------------------------
#
# Function: ClassController
# Description: 
# The ClassController class handles class related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ClassController {
    private $classModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ClassModel, UserModel and SecurityModel instances.
    # These instances are used for class related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ClassModel $classModel     The ClassModel instance for class related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ClassModel $classModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->classModel = $classModel;
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
                case 'save class':
                    $this->saveClass();
                    break;
                case 'get class details':
                    $this->getClassDetails();
                    break;
                case 'delete class':
                    $this->deleteClass();
                    break;
                case 'delete multiple class':
                    $this->deleteMultipleClass();
                    break;
                case 'duplicate class':
                    $this->duplicateClass();
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
    # Function: saveClass
    # Description: 
    # Updates the existing class if it exists; otherwise, inserts a new class.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $classID = isset($_POST['class_id']) ? htmlspecialchars($_POST['class_id'], ENT_QUOTES, 'UTF-8') : null;
        $className = htmlspecialchars($_POST['class_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkClassExist = $this->classModel->checkClassExist($classID);
        $total = $checkClassExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->classModel->updateClass($classID, $className, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'classID' => $this->securityModel->encryptData($classID)]);
            exit;
        } 
        else {
            $classID = $this->classModel->insertClass($className, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'classID' => $this->securityModel->encryptData($classID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteClass
    # Description: 
    # Delete the class if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $classID = htmlspecialchars($_POST['class_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkClassExist = $this->classModel->checkClassExist($classID);
        $total = $checkClassExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->classModel->deleteClass($classID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleClass
    # Description: 
    # Delete the selected classs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $classIDs = $_POST['class_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($classIDs as $classID){
            $this->classModel->deleteClass($classID);
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
    # Function: duplicateClass
    # Description: 
    # Duplicates the class if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $classID = htmlspecialchars($_POST['class_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkClassExist = $this->classModel->checkClassExist($classID);
        $total = $checkClassExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $classID = $this->classModel->duplicateClass($classID, $userID);

        echo json_encode(['success' => true, 'classID' =>  $this->securityModel->encryptData($classID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getClassDetails
    # Description: 
    # Handles the retrieval of class details such as class name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getClassDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['class_id']) && !empty($_POST['class_id'])) {
            $userID = $_SESSION['user_id'];
            $classID = $_POST['class_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $classDetails = $this->classModel->getClass($classID);

            $response = [
                'success' => true,
                'className' => $classDetails['class_name']
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
require_once '../model/class-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ClassController(new ClassModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>