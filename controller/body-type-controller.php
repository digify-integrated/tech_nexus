<?php
session_start();

# -------------------------------------------------------------
#
# Function: BodyTypeController
# Description: 
# The BodyTypeController class handles body type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BodyTypeController {
    private $bodyTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BodyTypeModel, UserModel and SecurityModel instances.
    # These instances are used for body type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BodyTypeModel $bodyTypeModel     The BodyTypeModel instance for body type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BodyTypeModel $bodyTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->bodyTypeModel = $bodyTypeModel;
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
                case 'save body type':
                    $this->saveBodyType();
                    break;
                case 'get body type details':
                    $this->getBodyTypeDetails();
                    break;
                case 'delete body type':
                    $this->deleteBodyType();
                    break;
                case 'delete multiple body type':
                    $this->deleteMultipleBodyType();
                    break;
                case 'duplicate body type':
                    $this->duplicateBodyType();
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
    # Function: saveBodyType
    # Description: 
    # Updates the existing body type if it exists; otherwise, inserts a new body type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBodyType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bodyTypeID = isset($_POST['body_type_id']) ? htmlspecialchars($_POST['body_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $bodyTypeName = htmlspecialchars($_POST['body_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBodyTypeExist = $this->bodyTypeModel->checkBodyTypeExist($bodyTypeID);
        $total = $checkBodyTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->bodyTypeModel->updateBodyType($bodyTypeID, $bodyTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'bodyTypeID' => $this->securityModel->encryptData($bodyTypeID)]);
            exit;
        } 
        else {
            $bodyTypeID = $this->bodyTypeModel->insertBodyType($bodyTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'bodyTypeID' => $this->securityModel->encryptData($bodyTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBodyType
    # Description: 
    # Delete the body type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBodyType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bodyTypeID = htmlspecialchars($_POST['body_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBodyTypeExist = $this->bodyTypeModel->checkBodyTypeExist($bodyTypeID);
        $total = $checkBodyTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->bodyTypeModel->deleteBodyType($bodyTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBodyType
    # Description: 
    # Delete the selected body types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBodyType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bodyTypeIDs = $_POST['body_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($bodyTypeIDs as $bodyTypeID){
            $this->bodyTypeModel->deleteBodyType($bodyTypeID);
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
    # Function: duplicateBodyType
    # Description: 
    # Duplicates the body type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBodyType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bodyTypeID = htmlspecialchars($_POST['body_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBodyTypeExist = $this->bodyTypeModel->checkBodyTypeExist($bodyTypeID);
        $total = $checkBodyTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $bodyTypeID = $this->bodyTypeModel->duplicateBodyType($bodyTypeID, $userID);

        echo json_encode(['success' => true, 'bodyTypeID' =>  $this->securityModel->encryptData($bodyTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBodyTypeDetails
    # Description: 
    # Handles the retrieval of body type details such as body type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBodyTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['body_type_id']) && !empty($_POST['body_type_id'])) {
            $userID = $_SESSION['user_id'];
            $bodyTypeID = $_POST['body_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $bodyTypeDetails = $this->bodyTypeModel->getBodyType($bodyTypeID);

            $response = [
                'success' => true,
                'bodyTypeName' => $bodyTypeDetails['body_type_name']
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
require_once '../model/body-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BodyTypeController(new BodyTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>