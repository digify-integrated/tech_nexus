<?php
session_start();

# -------------------------------------------------------------
#
# Function: LanguageProficiencyController
# Description: 
# The LanguageProficiencyController class handles language proficiency related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LanguageProficiencyController {
    private $languageProficiencyModel;
    private $userModel;
    private $uploadSettingModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LanguageProficiencyModel, FileTypeModel, UserModel and SecurityModel instances.
    # These instances are used for language proficiency related operations, menu group related operations, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LanguageProficiencyModel $languageProficiencyModel     The LanguageProficiencyModel instance for language proficiency related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LanguageProficiencyModel $languageProficiencyModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, SecurityModel $securityModel) {
        $this->languageProficiencyModel = $languageProficiencyModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
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
                case 'save language proficiency':
                    $this->saveLanguageProficiency();
                    break;
                case 'get language proficiency details':
                    $this->getLanguageProficiencyDetails();
                    break;
                case 'delete language proficiency':
                    $this->deleteLanguageProficiency();
                    break;
                case 'delete multiple language proficiency':
                    $this->deleteMultipleLanguageProficiency();
                    break;
                case 'duplicate language proficiency':
                    $this->duplicateLanguageProficiency();
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
    # Function: saveLanguageProficiency
    # Description: 
    # Updates the existing language proficiency if it exists; otherwise, inserts a new language proficiency.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLanguageProficiency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageProficiencyID = htmlspecialchars($_POST['language_proficiency_id'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
        $languageProficiencyName = htmlspecialchars($_POST['language_proficiency_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLanguageProficiencyExist = $this->languageProficiencyModel->checkLanguageProficiencyExist($languageProficiencyID);
        $total = $checkLanguageProficiencyExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->languageProficiencyModel->updateLanguageProficiency($languageProficiencyID, $languageProficiencyName, $description, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'languageProficiencyID' => $this->securityModel->encryptData($languageProficiencyID)]);
            exit;
        } 
        else {
            $languageProficiencyID = $this->languageProficiencyModel->insertLanguageProficiency($languageProficiencyName, $description, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'languageProficiencyID' => $this->securityModel->encryptData($languageProficiencyID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLanguageProficiency
    # Description: 
    # Delete the language proficiency if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLanguageProficiency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageProficiencyID = htmlspecialchars($_POST['language_proficiency_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLanguageProficiencyExist = $this->languageProficiencyModel->checkLanguageProficiencyExist($languageProficiencyID);
        $total = $checkLanguageProficiencyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
        
        $this->languageProficiencyModel->deleteLanguageProficiency($languageProficiencyID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLanguageProficiency
    # Description: 
    # Delete the selected language proficiencys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLanguageProficiency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageProficiencyIDs = $_POST['language_proficiency_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($languageProficiencyIDs as $languageProficiencyID){
            $this->languageProficiencyModel->deleteLanguageProficiency($languageProficiencyID);
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
    # Function: duplicateLanguageProficiency
    # Description: 
    # Duplicates the language proficiency if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateLanguageProficiency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $languageProficiencyID = htmlspecialchars($_POST['language_proficiency_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLanguageProficiencyExist = $this->languageProficiencyModel->checkLanguageProficiencyExist($languageProficiencyID);
        $total = $checkLanguageProficiencyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $languageProficiencyID = $this->languageProficiencyModel->duplicateLanguageProficiency($languageProficiencyID, $userID);

        echo json_encode(['success' => true, 'languageProficiencyID' =>  $this->securityModel->encryptData($languageProficiencyID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLanguageProficiencyDetails
    # Description: 
    # Handles the retrieval of language proficiency details such as language proficiency name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLanguageProficiencyDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['language_proficiency_id']) && !empty($_POST['language_proficiency_id'])) {
            $userID = $_SESSION['user_id'];
            $languageProficiencyID = $_POST['language_proficiency_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $languageProficiencyDetails = $this->languageProficiencyModel->getLanguageProficiency($languageProficiencyID);

            $response = [
                'success' => true,
                'languageProficiencyName' => $languageProficiencyDetails['language_proficiency_name'],
                'description' => $languageProficiencyDetails['description']
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
require_once '../model/language-proficiency-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LanguageProficiencyController(new LanguageProficiencyModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();

?>