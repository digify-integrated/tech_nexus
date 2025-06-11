<?php
session_start();

# -------------------------------------------------------------
#
# Function: NeighborhoodTypeController
# Description: 
# The NeighborhoodTypeController class handles neighborhood type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class NeighborhoodTypeController {
    private $neighborhoodTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided NeighborhoodTypeModel, UserModel and SecurityModel instances.
    # These instances are used for neighborhood type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param NeighborhoodTypeModel $neighborhoodTypeModel     The NeighborhoodTypeModel instance for neighborhood type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(NeighborhoodTypeModel $neighborhoodTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->neighborhoodTypeModel = $neighborhoodTypeModel;
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
                case 'save neighborhood type':
                    $this->saveNeighborhoodType();
                    break;
                case 'get neighborhood type details':
                    $this->getNeighborhoodTypeDetails();
                    break;
                case 'delete neighborhood type':
                    $this->deleteNeighborhoodType();
                    break;
                case 'delete multiple neighborhood type':
                    $this->deleteMultipleNeighborhoodType();
                    break;
                case 'duplicate neighborhood type':
                    $this->duplicateNeighborhoodType();
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
    # Function: saveNeighborhoodType
    # Description: 
    # Updates the existing neighborhood type if it exists; otherwise, inserts a new neighborhood type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveNeighborhoodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $neighborhoodTypeID = isset($_POST['neighborhood_type_id']) ? htmlspecialchars($_POST['neighborhood_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $neighborhoodTypeName = htmlspecialchars($_POST['neighborhood_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNeighborhoodTypeExist = $this->neighborhoodTypeModel->checkNeighborhoodTypeExist($neighborhoodTypeID);
        $total = $checkNeighborhoodTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->neighborhoodTypeModel->updateNeighborhoodType($neighborhoodTypeID, $neighborhoodTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'neighborhoodTypeID' => $this->securityModel->encryptData($neighborhoodTypeID)]);
            exit;
        } 
        else {
            $neighborhoodTypeID = $this->neighborhoodTypeModel->insertNeighborhoodType($neighborhoodTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'neighborhoodTypeID' => $this->securityModel->encryptData($neighborhoodTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteNeighborhoodType
    # Description: 
    # Delete the neighborhood type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteNeighborhoodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $neighborhoodTypeID = htmlspecialchars($_POST['neighborhood_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNeighborhoodTypeExist = $this->neighborhoodTypeModel->checkNeighborhoodTypeExist($neighborhoodTypeID);
        $total = $checkNeighborhoodTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->neighborhoodTypeModel->deleteNeighborhoodType($neighborhoodTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleNeighborhoodType
    # Description: 
    # Delete the selected neighborhood types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleNeighborhoodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $neighborhoodTypeIDs = $_POST['neighborhood_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($neighborhoodTypeIDs as $neighborhoodTypeID){
            $this->neighborhoodTypeModel->deleteNeighborhoodType($neighborhoodTypeID);
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
    # Function: duplicateNeighborhoodType
    # Description: 
    # Duplicates the neighborhood type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateNeighborhoodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $neighborhoodTypeID = htmlspecialchars($_POST['neighborhood_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNeighborhoodTypeExist = $this->neighborhoodTypeModel->checkNeighborhoodTypeExist($neighborhoodTypeID);
        $total = $checkNeighborhoodTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $neighborhoodTypeID = $this->neighborhoodTypeModel->duplicateNeighborhoodType($neighborhoodTypeID, $userID);

        echo json_encode(['success' => true, 'neighborhoodTypeID' =>  $this->securityModel->encryptData($neighborhoodTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getNeighborhoodTypeDetails
    # Description: 
    # Handles the retrieval of neighborhood type details such as neighborhood type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getNeighborhoodTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['neighborhood_type_id']) && !empty($_POST['neighborhood_type_id'])) {
            $userID = $_SESSION['user_id'];
            $neighborhoodTypeID = $_POST['neighborhood_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $neighborhoodTypeDetails = $this->neighborhoodTypeModel->getNeighborhoodType($neighborhoodTypeID);

            $response = [
                'success' => true,
                'neighborhoodTypeName' => $neighborhoodTypeDetails['neighborhood_type_name']
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
require_once '../model/neighborhood-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new NeighborhoodTypeController(new NeighborhoodTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>