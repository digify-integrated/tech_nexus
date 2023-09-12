<?php
session_start();

# -------------------------------------------------------------
#
# Function: RelationController
# Description: 
# The RelationController class handles relation related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class RelationController {
    private $relationModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided RelationModel, UserModel and SecurityModel instances.
    # These instances are used for relation related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param RelationModel $relationModel     The RelationModel instance for relation related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(RelationModel $relationModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->relationModel = $relationModel;
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
                case 'save relation':
                    $this->saveRelation();
                    break;
                case 'get relation details':
                    $this->getRelationDetails();
                    break;
                case 'delete relation':
                    $this->deleteRelation();
                    break;
                case 'delete multiple relation':
                    $this->deleteMultipleRelation();
                    break;
                case 'duplicate relation':
                    $this->duplicateRelation();
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
    # Function: saveRelation
    # Description: 
    # Updates the existing relation if it exists; otherwise, inserts a new relation.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveRelation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $relationID = isset($_POST['relation_id']) ? htmlspecialchars($_POST['relation_id'], ENT_QUOTES, 'UTF-8') : null;
        $relationName = htmlspecialchars($_POST['relation_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRelationExist = $this->relationModel->checkRelationExist($relationID);
        $total = $checkRelationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->relationModel->updateRelation($relationID, $relationName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'relationID' => $this->securityModel->encryptData($relationID)]);
            exit;
        } 
        else {
            $relationID = $this->relationModel->insertRelation($relationName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'relationID' => $this->securityModel->encryptData($relationID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteRelation
    # Description: 
    # Delete the relation if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteRelation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $relationID = htmlspecialchars($_POST['relation_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRelationExist = $this->relationModel->checkRelationExist($relationID);
        $total = $checkRelationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->relationModel->deleteRelation($relationID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleRelation
    # Description: 
    # Delete the selected relations if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleRelation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $relationIDs = $_POST['relation_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($relationIDs as $relationID){
            $this->relationModel->deleteRelation($relationID);
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
    # Function: duplicateRelation
    # Description: 
    # Duplicates the relation if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateRelation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $relationID = htmlspecialchars($_POST['relation_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRelationExist = $this->relationModel->checkRelationExist($relationID);
        $total = $checkRelationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $relationID = $this->relationModel->duplicateRelation($relationID, $userID);

        echo json_encode(['success' => true, 'relationID' =>  $this->securityModel->encryptData($relationID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getRelationDetails
    # Description: 
    # Handles the retrieval of relation details such as relation name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getRelationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['relation_id']) && !empty($_POST['relation_id'])) {
            $userID = $_SESSION['user_id'];
            $relationID = $_POST['relation_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $relationDetails = $this->relationModel->getRelation($relationID);

            $response = [
                'success' => true,
                'relationName' => $relationDetails['relation_name']
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
require_once '../model/relation-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new RelationController(new RelationModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>