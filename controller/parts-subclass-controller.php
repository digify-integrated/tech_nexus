<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsSubclassController
# Description: 
# The PartsSubclassController class handles parts subclass related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsSubclassController {
    private $partsSubclassModel;
    private $partsClassModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PartsSubclassModel, UserModel and SecurityModel instances.
    # These instances are used for parts subclass related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsSubclassModel $partsSubclassModel     The PartsSubclassModel instance for parts subclass related operations.
    # - @param PartsClassModel $partsSubclassModel     The PartsClassModel instance for product category related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsSubclassModel $partsSubclassModel, PartsClassModel $partsClassModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->partsSubclassModel = $partsSubclassModel;
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
                case 'save parts subclass':
                    $this->savePartsSubclass();
                    break;
                case 'get parts subclass details':
                    $this->getPartsSubclassDetails();
                    break;
                case 'delete parts subclass':
                    $this->deletePartsSubclass();
                    break;
                case 'delete multiple parts subclass':
                    $this->deleteMultiplePartsSubclass();
                    break;
                case 'duplicate parts subclass':
                    $this->duplicatePartsSubclass();
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
    # Function: savePartsSubclass
    # Description: 
    # Updates the existing parts subclass if it exists; otherwise, inserts a new parts subclass.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsSubclass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsSubclassID = isset($_POST['parts_subclass_id']) ? htmlspecialchars($_POST['parts_subclass_id'], ENT_QUOTES, 'UTF-8') : null;
        $partsSubclassName = htmlspecialchars($_POST['parts_subclass_name'], ENT_QUOTES, 'UTF-8');
        $partsClassID = htmlspecialchars($_POST['parts_class_id'], ENT_QUOTES, 'UTF-8');
        $part_subclass_code = htmlspecialchars($_POST['part_subclass_code'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsSubclassExist = $this->partsSubclassModel->checkPartsSubclassExist($partsSubclassID);
        $total = $checkPartsSubclassExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsSubclassModel->updatePartsSubclass($partsSubclassID, $partsSubclassName, $partsClassID, $part_subclass_code, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'partsSubclassID' => $this->securityModel->encryptData($partsSubclassID)]);
            exit;
        } 
        else {
            $partsSubclassID = $this->partsSubclassModel->insertPartsSubclass($partsSubclassName, $partsClassID, $part_subclass_code, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'partsSubclassID' => $this->securityModel->encryptData($partsSubclassID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsSubclass
    # Description: 
    # Delete the parts subclass if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsSubclass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsSubclassID = htmlspecialchars($_POST['parts_subclass_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsSubclassExist = $this->partsSubclassModel->checkPartsSubclassExist($partsSubclassID);
        $total = $checkPartsSubclassExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->partsSubclassModel->deletePartsSubclass($partsSubclassID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePartsSubclass
    # Description: 
    # Delete the selected product subcategories if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePartsSubclass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsSubclassIDs = $_POST['parts_subclass_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($partsSubclassIDs as $partsSubclassID){
            $this->partsSubclassModel->deletePartsSubclass($partsSubclassID);
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
    # Function: duplicatePartsSubclass
    # Description: 
    # Duplicates the parts subclass if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicatePartsSubclass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsSubclassID = htmlspecialchars($_POST['parts_subclass_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsSubclassExist = $this->partsSubclassModel->checkPartsSubclassExist($partsSubclassID);
        $total = $checkPartsSubclassExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsSubclassID = $this->partsSubclassModel->duplicatePartsSubclass($partsSubclassID, $userID);

        echo json_encode(['success' => true, 'partsSubclassID' =>  $this->securityModel->encryptData($partsSubclassID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsSubclassDetails
    # Description: 
    # Handles the retrieval of parts subclass details such as parts subclass name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsSubclassDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_subclass_id']) && !empty($_POST['parts_subclass_id'])) {
            $userID = $_SESSION['user_id'];
            $partsSubclassID = $_POST['parts_subclass_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsSubclassDetails = $this->partsSubclassModel->getPartsSubclass($partsSubclassID);
            $partsClassID = $partsSubclassDetails['part_class_id'];

            $partsClass = $this->partsClassModel->getPartsClass($partsClassID);
            $partsClassName = $partsClass['part_class_name'];

            $response = [
                'success' => true,
                'partsSubclassName' => $partsSubclassDetails['part_subclass_name'],
                'partSubclassCode' => $partsSubclassDetails['part_subclass_code'],
                'partsClassID' => $partsClassID,
                'partsClassName' => $partsClassName
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
require_once '../model/parts-subclass-model.php';
require_once '../model/parts-class-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsSubclassController(new PartsSubclassModel(new DatabaseModel), new PartsClassModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>