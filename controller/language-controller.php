<?php
session_start();

# -------------------------------------------------------------
#
# Function: LanguageController
# Description: 
# The LanguageController class handles language related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LanguageController {
    private $languageModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LanguageModel, UserModel and SecurityModel instances.
    # These instances are used for language related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LanguageModel $languageModel     The LanguageModel instance for language related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LanguageModel $languageModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->languageModel = $languageModel;
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
                case 'save language':
                    $this->saveLanguage();
                    break;
                case 'get language details':
                    $this->getLanguageDetails();
                    break;
                case 'delete language':
                    $this->deleteLanguage();
                    break;
                case 'delete multiple language':
                    $this->deleteMultipleLanguage();
                    break;
                case 'duplicate language':
                    $this->duplicateLanguage();
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
    # Function: saveLanguage
    # Description: 
    # Updates the existing language if it exists; otherwise, inserts a new language.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLanguage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageID = isset($_POST['language_id']) ? htmlspecialchars($_POST['language_id'], ENT_QUOTES, 'UTF-8') : null;
        $languageName = htmlspecialchars($_POST['language_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLanguageExist = $this->languageModel->checkLanguageExist($languageID);
        $total = $checkLanguageExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->languageModel->updateLanguage($languageID, $languageName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'languageID' => $this->securityModel->encryptData($languageID)]);
            exit;
        } 
        else {
            $languageID = $this->languageModel->insertLanguage($languageName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'languageID' => $this->securityModel->encryptData($languageID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLanguage
    # Description: 
    # Delete the language if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLanguage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageID = htmlspecialchars($_POST['language_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLanguageExist = $this->languageModel->checkLanguageExist($languageID);
        $total = $checkLanguageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->languageModel->deleteLanguage($languageID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLanguage
    # Description: 
    # Delete the selected languages if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLanguage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageIDs = $_POST['language_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($languageIDs as $languageID){
            $this->languageModel->deleteLanguage($languageID);
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
    # Function: duplicateLanguage
    # Description: 
    # Duplicates the language if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateLanguage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageID = htmlspecialchars($_POST['language_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLanguageExist = $this->languageModel->checkLanguageExist($languageID);
        $total = $checkLanguageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $languageID = $this->languageModel->duplicateLanguage($languageID, $userID);

        echo json_encode(['success' => true, 'languageID' =>  $this->securityModel->encryptData($languageID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLanguageDetails
    # Description: 
    # Handles the retrieval of language details such as language name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLanguageDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['language_id']) && !empty($_POST['language_id'])) {
            $userID = $_SESSION['user_id'];
            $languageID = $_POST['language_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $languageDetails = $this->languageModel->getLanguage($languageID);

            $response = [
                'success' => true,
                'languageName' => $languageDetails['language_name']
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
require_once '../model/language-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LanguageController(new LanguageModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>