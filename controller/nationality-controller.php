<?php
session_start();

# -------------------------------------------------------------
#
# Function: NationalityController
# Description: 
# The NationalityController class handles nationality related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class NationalityController {
    private $nationalityModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided NationalityModel, UserModel and SecurityModel instances.
    # These instances are used for nationality related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param NationalityModel $nationalityModel     The NationalityModel instance for nationality related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(NationalityModel $nationalityModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->nationalityModel = $nationalityModel;
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
                case 'save nationality':
                    $this->saveNationality();
                    break;
                case 'get nationality details':
                    $this->getNationalityDetails();
                    break;
                case 'delete nationality':
                    $this->deleteNationality();
                    break;
                case 'delete multiple nationality':
                    $this->deleteMultipleNationality();
                    break;
                case 'duplicate nationality':
                    $this->duplicateNationality();
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
    # Function: saveNationality
    # Description: 
    # Updates the existing nationality if it exists; otherwise, inserts a new nationality.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveNationality() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $nationalityID = isset($_POST['nationality_id']) ? htmlspecialchars($_POST['nationality_id'], ENT_QUOTES, 'UTF-8') : null;
        $nationalityName = htmlspecialchars($_POST['nationality_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNationalityExist = $this->nationalityModel->checkNationalityExist($nationalityID);
        $total = $checkNationalityExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->nationalityModel->updateNationality($nationalityID, $nationalityName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'nationalityID' => $this->securityModel->encryptData($nationalityID)]);
            exit;
        } 
        else {
            $nationalityID = $this->nationalityModel->insertNationality($nationalityName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'nationalityID' => $this->securityModel->encryptData($nationalityID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteNationality
    # Description: 
    # Delete the nationality if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteNationality() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $nationalityID = htmlspecialchars($_POST['nationality_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNationalityExist = $this->nationalityModel->checkNationalityExist($nationalityID);
        $total = $checkNationalityExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->nationalityModel->deleteNationality($nationalityID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleNationality
    # Description: 
    # Delete the selected nationalitys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleNationality() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $nationalityIDs = $_POST['nationality_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($nationalityIDs as $nationalityID){
            $this->nationalityModel->deleteNationality($nationalityID);
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
    # Function: duplicateNationality
    # Description: 
    # Duplicates the nationality if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateNationality() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $nationalityID = htmlspecialchars($_POST['nationality_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNationalityExist = $this->nationalityModel->checkNationalityExist($nationalityID);
        $total = $checkNationalityExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $nationalityID = $this->nationalityModel->duplicateNationality($nationalityID, $userID);

        echo json_encode(['success' => true, 'nationalityID' =>  $this->securityModel->encryptData($nationalityID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getNationalityDetails
    # Description: 
    # Handles the retrieval of nationality details such as nationality name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getNationalityDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['nationality_id']) && !empty($_POST['nationality_id'])) {
            $userID = $_SESSION['user_id'];
            $nationalityID = $_POST['nationality_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $nationalityDetails = $this->nationalityModel->getNationality($nationalityID);

            $response = [
                'success' => true,
                'nationalityName' => $nationalityDetails['nationality_name']
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
require_once '../model/nationality-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new NationalityController(new NationalityModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>