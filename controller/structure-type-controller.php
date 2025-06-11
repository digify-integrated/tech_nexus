<?php
session_start();

# -------------------------------------------------------------
#
# Function: StructureTypeController
# Description: 
# The StructureTypeController class handles structure type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class StructureTypeController {
    private $structureTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided StructureTypeModel, UserModel and SecurityModel instances.
    # These instances are used for structure type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param StructureTypeModel $structureTypeModel     The StructureTypeModel instance for structure type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(StructureTypeModel $structureTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->structureTypeModel = $structureTypeModel;
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
                case 'save structure type':
                    $this->saveStructureType();
                    break;
                case 'get structure type details':
                    $this->getStructureTypeDetails();
                    break;
                case 'delete structure type':
                    $this->deleteStructureType();
                    break;
                case 'delete multiple structure type':
                    $this->deleteMultipleStructureType();
                    break;
                case 'duplicate structure type':
                    $this->duplicateStructureType();
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
    # Function: saveStructureType
    # Description: 
    # Updates the existing structure type if it exists; otherwise, inserts a new structure type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveStructureType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $structureTypeID = isset($_POST['structure_type_id']) ? htmlspecialchars($_POST['structure_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $structureTypeName = htmlspecialchars($_POST['structure_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkStructureTypeExist = $this->structureTypeModel->checkStructureTypeExist($structureTypeID);
        $total = $checkStructureTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->structureTypeModel->updateStructureType($structureTypeID, $structureTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'structureTypeID' => $this->securityModel->encryptData($structureTypeID)]);
            exit;
        } 
        else {
            $structureTypeID = $this->structureTypeModel->insertStructureType($structureTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'structureTypeID' => $this->securityModel->encryptData($structureTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteStructureType
    # Description: 
    # Delete the structure type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteStructureType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $structureTypeID = htmlspecialchars($_POST['structure_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkStructureTypeExist = $this->structureTypeModel->checkStructureTypeExist($structureTypeID);
        $total = $checkStructureTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->structureTypeModel->deleteStructureType($structureTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleStructureType
    # Description: 
    # Delete the selected structure types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleStructureType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $structureTypeIDs = $_POST['structure_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($structureTypeIDs as $structureTypeID){
            $this->structureTypeModel->deleteStructureType($structureTypeID);
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
    # Function: duplicateStructureType
    # Description: 
    # Duplicates the structure type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateStructureType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $structureTypeID = htmlspecialchars($_POST['structure_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkStructureTypeExist = $this->structureTypeModel->checkStructureTypeExist($structureTypeID);
        $total = $checkStructureTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $structureTypeID = $this->structureTypeModel->duplicateStructureType($structureTypeID, $userID);

        echo json_encode(['success' => true, 'structureTypeID' =>  $this->securityModel->encryptData($structureTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getStructureTypeDetails
    # Description: 
    # Handles the retrieval of structure type details such as structure type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getStructureTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['structure_type_id']) && !empty($_POST['structure_type_id'])) {
            $userID = $_SESSION['user_id'];
            $structureTypeID = $_POST['structure_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $structureTypeDetails = $this->structureTypeModel->getStructureType($structureTypeID);

            $response = [
                'success' => true,
                'structureTypeName' => $structureTypeDetails['structure_type_name']
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
require_once '../model/structure-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new StructureTypeController(new StructureTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>