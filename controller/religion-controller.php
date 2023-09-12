<?php
session_start();

# -------------------------------------------------------------
#
# Function: ReligionController
# Description: 
# The ReligionController class handles religion related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ReligionController {
    private $religionModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ReligionModel, UserModel and SecurityModel instances.
    # These instances are used for religion related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ReligionModel $religionModel     The ReligionModel instance for religion related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ReligionModel $religionModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->religionModel = $religionModel;
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
                case 'save religion':
                    $this->saveReligion();
                    break;
                case 'get religion details':
                    $this->getReligionDetails();
                    break;
                case 'delete religion':
                    $this->deleteReligion();
                    break;
                case 'delete multiple religion':
                    $this->deleteMultipleReligion();
                    break;
                case 'duplicate religion':
                    $this->duplicateReligion();
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
    # Function: saveReligion
    # Description: 
    # Updates the existing religion if it exists; otherwise, inserts a new religion.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveReligion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $religionID = isset($_POST['religion_id']) ? htmlspecialchars($_POST['religion_id'], ENT_QUOTES, 'UTF-8') : null;
        $religionName = htmlspecialchars($_POST['religion_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkReligionExist = $this->religionModel->checkReligionExist($religionID);
        $total = $checkReligionExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->religionModel->updateReligion($religionID, $religionName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'religionID' => $this->securityModel->encryptData($religionID)]);
            exit;
        } 
        else {
            $religionID = $this->religionModel->insertReligion($religionName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'religionID' => $this->securityModel->encryptData($religionID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteReligion
    # Description: 
    # Delete the religion if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteReligion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $religionID = htmlspecialchars($_POST['religion_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkReligionExist = $this->religionModel->checkReligionExist($religionID);
        $total = $checkReligionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->religionModel->deleteReligion($religionID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleReligion
    # Description: 
    # Delete the selected religions if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleReligion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $religionIDs = $_POST['religion_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($religionIDs as $religionID){
            $this->religionModel->deleteReligion($religionID);
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
    # Function: duplicateReligion
    # Description: 
    # Duplicates the religion if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateReligion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $religionID = htmlspecialchars($_POST['religion_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkReligionExist = $this->religionModel->checkReligionExist($religionID);
        $total = $checkReligionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $religionID = $this->religionModel->duplicateReligion($religionID, $userID);

        echo json_encode(['success' => true, 'religionID' =>  $this->securityModel->encryptData($religionID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getReligionDetails
    # Description: 
    # Handles the retrieval of religion details such as religion name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getReligionDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['religion_id']) && !empty($_POST['religion_id'])) {
            $userID = $_SESSION['user_id'];
            $religionID = $_POST['religion_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $religionDetails = $this->religionModel->getReligion($religionID);

            $response = [
                'success' => true,
                'religionName' => $religionDetails['religion_name']
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
require_once '../model/religion-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ReligionController(new ReligionModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>