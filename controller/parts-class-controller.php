<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsClassController
# Description: 
# The PartsClassController class handles parts class related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsClassController {
    private $partsClassModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PartsClassModel, UserModel and SecurityModel instances.
    # These instances are used for parts class related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsClassModel $partsClassModel     The PartsClassModel instance for parts class related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsClassModel $partsClassModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->partsClassModel = $partsClassModel;
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
                case 'save parts class':
                    $this->savePartsClass();
                    break;
                case 'get parts class details':
                    $this->getPartsClassDetails();
                    break;
                case 'delete parts class':
                    $this->deletePartsClass();
                    break;
                case 'delete multiple parts class':
                    $this->deleteMultiplePartsClass();
                    break;
                case 'duplicate parts class':
                    $this->duplicatePartsClass();
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
    # Function: savePartsClass
    # Description: 
    # Updates the existing parts class if it exists; otherwise, inserts a new parts class.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsClassID = isset($_POST['parts_class_id']) ? htmlspecialchars($_POST['parts_class_id'], ENT_QUOTES, 'UTF-8') : null;
        $partsClassName = htmlspecialchars($_POST['parts_class_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsClassExist = $this->partsClassModel->checkPartsClassExist($partsClassID);
        $total = $checkPartsClassExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsClassModel->updatePartsClass($partsClassID, $partsClassName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'partsClassID' => $this->securityModel->encryptData($partsClassID)]);
            exit;
        } 
        else {
            $partsClassID = $this->partsClassModel->insertPartsClass($partsClassName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'partsClassID' => $this->securityModel->encryptData($partsClassID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsClass
    # Description: 
    # Delete the parts class if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsClassID = htmlspecialchars($_POST['parts_class_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsClassExist = $this->partsClassModel->checkPartsClassExist($partsClassID);
        $total = $checkPartsClassExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->partsClassModel->deletePartsClass($partsClassID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePartsClass
    # Description: 
    # Delete the selected parts classs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePartsClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsClassIDs = $_POST['parts_class_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($partsClassIDs as $partsClassID){
            $this->partsClassModel->deletePartsClass($partsClassID);
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
    # Function: duplicatePartsClass
    # Description: 
    # Duplicates the parts class if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicatePartsClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsClassID = htmlspecialchars($_POST['parts_class_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsClassExist = $this->partsClassModel->checkPartsClassExist($partsClassID);
        $total = $checkPartsClassExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsClassID = $this->partsClassModel->duplicatePartsClass($partsClassID, $userID);

        echo json_encode(['success' => true, 'partsClassID' =>  $this->securityModel->encryptData($partsClassID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsClassDetails
    # Description: 
    # Handles the retrieval of parts class details such as parts class name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsClassDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_class_id']) && !empty($_POST['parts_class_id'])) {
            $userID = $_SESSION['user_id'];
            $partsClassID = $_POST['parts_class_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsClassDetails = $this->partsClassModel->getPartsClass($partsClassID);

            $response = [
                'success' => true,
                'partsClassName' => $partsClassDetails['part_class_name']
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
require_once '../model/parts-class-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsClassController(new PartsClassModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>