<?php
session_start();

# -------------------------------------------------------------
#
# Function: GenderController
# Description: 
# The GenderController class handles gender related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class GenderController {
    private $genderModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided GenderModel, UserModel and SecurityModel instances.
    # These instances are used for gender related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param GenderModel $genderModel     The GenderModel instance for gender related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(GenderModel $genderModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->genderModel = $genderModel;
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
                case 'save gender':
                    $this->saveGender();
                    break;
                case 'get gender details':
                    $this->getGenderDetails();
                    break;
                case 'delete gender':
                    $this->deleteGender();
                    break;
                case 'delete multiple gender':
                    $this->deleteMultipleGender();
                    break;
                case 'duplicate gender':
                    $this->duplicateGender();
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
    # Function: saveGender
    # Description: 
    # Updates the existing gender if it exists; otherwise, inserts a new gender.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveGender() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $genderID = isset($_POST['gender_id']) ? htmlspecialchars($_POST['gender_id'], ENT_QUOTES, 'UTF-8') : null;
        $genderName = htmlspecialchars($_POST['gender_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkGenderExist = $this->genderModel->checkGenderExist($genderID);
        $total = $checkGenderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->genderModel->updateGender($genderID, $genderName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'genderID' => $this->securityModel->encryptData($genderID)]);
            exit;
        } 
        else {
            $genderID = $this->genderModel->insertGender($genderName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'genderID' => $this->securityModel->encryptData($genderID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteGender
    # Description: 
    # Delete the gender if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteGender() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $genderID = htmlspecialchars($_POST['gender_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkGenderExist = $this->genderModel->checkGenderExist($genderID);
        $total = $checkGenderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->genderModel->deleteGender($genderID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleGender
    # Description: 
    # Delete the selected genders if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleGender() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $genderIDs = $_POST['gender_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($genderIDs as $genderID){
            $this->genderModel->deleteGender($genderID);
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
    # Function: duplicateGender
    # Description: 
    # Duplicates the gender if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateGender() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $genderID = htmlspecialchars($_POST['gender_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkGenderExist = $this->genderModel->checkGenderExist($genderID);
        $total = $checkGenderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $genderID = $this->genderModel->duplicateGender($genderID, $userID);

        echo json_encode(['success' => true, 'genderID' =>  $this->securityModel->encryptData($genderID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getGenderDetails
    # Description: 
    # Handles the retrieval of gender details such as gender name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getGenderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['gender_id']) && !empty($_POST['gender_id'])) {
            $userID = $_SESSION['user_id'];
            $genderID = $_POST['gender_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $genderDetails = $this->genderModel->getGender($genderID);

            $response = [
                'success' => true,
                'genderName' => $genderDetails['gender_name']
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
require_once '../model/gender-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new GenderController(new GenderModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>