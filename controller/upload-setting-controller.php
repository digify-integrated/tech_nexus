<?php
session_start();

# -------------------------------------------------------------
#
# Function: UploadSettingController
# Description: 
# The UploadSettingController class handles upload setting related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class UploadSettingController {
    private $uploadSettingModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided UploadSettingModel, UserModel and SecurityModel instances.
    # These instances are used for upload setting related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(UploadSettingModel $uploadSettingModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel) {
        $this->uploadSettingModel = $uploadSettingModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
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
                case 'save upload setting':
                    $this->saveUploadSetting();
                    break;
                case 'get upload setting details':
                    $this->getUploadSettingDetails();
                    break;
                case 'delete upload setting':
                    $this->deleteUploadSetting();
                    break;
                case 'delete multiple upload setting':
                    $this->deleteMultipleUploadSetting();
                    break;
                case 'duplicate upload setting':
                    $this->duplicateUploadSetting();
                    break;
                case 'add upload setting file extension':
                    $this->addUploadSettingFileExtension();
                    break;
                case 'delete upload setting file extension':
                    $this->deleteUploadSettingFileExtension();
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
    # Function: saveUploadSetting
    # Description: 
    # Updates the existing upload setting if it exists; otherwise, inserts a new upload setting.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveUploadSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $uploadSettingID = isset($_POST['upload_setting_id']) ? htmlspecialchars($_POST['upload_setting_id'], ENT_QUOTES, 'UTF-8') : null;
        $uploadSettingName = htmlspecialchars($_POST['upload_setting_name'], ENT_QUOTES, 'UTF-8');
        $uploadSettingDescription = htmlspecialchars($_POST['upload_setting_name'], ENT_QUOTES, 'UTF-8');
        $maxFileSize = htmlspecialchars($_POST['max_file_size'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUploadSettingExist = $this->uploadSettingModel->checkUploadSettingExist($uploadSettingID);
        $total = $checkUploadSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->uploadSettingModel->updateUploadSetting($uploadSettingID, $uploadSettingName, $uploadSettingDescription, $maxFileSize, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'uploadSettingID' => $this->securityModel->encryptData($uploadSettingID)]);
            exit;
        } 
        else {
            $uploadSettingID = $this->uploadSettingModel->insertUploadSetting($uploadSettingName, $uploadSettingDescription, $maxFileSize, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'uploadSettingID' => $this->securityModel->encryptData($uploadSettingID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Add methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addUploadSettingFileExtension
    # Description:
    # Add the upload setting file extension.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addUploadSettingFileExtension() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $uploadSettingID = htmlspecialchars($_POST['upload_setting_id'], ENT_QUOTES, 'UTF-8');
        $fileExtensionIDs = explode(',', $_POST['file_extension_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($fileExtensionIDs as $fileExtensionID) {
            $checkUploadSettingFileExtensionExist = $this->uploadSettingModel->checkUploadSettingFileExtensionExist($uploadSettingID, $fileExtensionID);
            $total = $checkUploadSettingFileExtensionExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->uploadSettingModel->insertUploadSettingFileExtension($uploadSettingID, $fileExtensionID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteUploadSetting
    # Description: 
    # Delete the upload setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteUploadSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $uploadSettingID = htmlspecialchars($_POST['upload_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUploadSettingExist = $this->uploadSettingModel->checkUploadSettingExist($uploadSettingID);
        $total = $checkUploadSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->uploadSettingModel->deleteLinkedAllowedFileExtension($uploadSettingID);
        $this->uploadSettingModel->deleteUploadSetting($uploadSettingID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleUploadSetting
    # Description: 
    # Delete the selected upload settings if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleUploadSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $uploadSettingIDs = $_POST['upload_setting_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($uploadSettingIDs as $uploadSettingID){
            $this->uploadSettingModel->deleteLinkedAllowedFileExtension($uploadSettingID);
            $this->uploadSettingModel->deleteUploadSetting($uploadSettingID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteUploadSettingFileExtension
    # Description:
    # Delete the upload setting file extension if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteUploadSettingFileExtension() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $uploadSettingID = htmlspecialchars($_POST['upload_setting_id'], ENT_QUOTES, 'UTF-8');
        $fileExtensionID = htmlspecialchars($_POST['file_extension_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUploadSettingFileExtensionExist = $this->uploadSettingModel->checkUploadSettingFileExtensionExist($uploadSettingID, $fileExtensionID);
        $total = $checkUploadSettingFileExtensionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->uploadSettingModel->deleteUploadSettingFileExtension($uploadSettingID, $fileExtensionID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateUploadSetting
    # Description: 
    # Duplicates the upload setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateUploadSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $uploadSettingID = htmlspecialchars($_POST['upload_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUploadSettingExist = $this->uploadSettingModel->checkUploadSettingExist($uploadSettingID);
        $total = $checkUploadSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $uploadSettingID = $this->uploadSettingModel->duplicateUploadSetting($uploadSettingID, $userID);

        echo json_encode(['success' => true, 'uploadSettingID' =>  $this->securityModel->encryptData($uploadSettingID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUploadSettingDetails
    # Description: 
    # Handles the retrieval of upload setting details such as upload setting name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getUploadSettingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])) {
            $userID = $_SESSION['user_id'];
            $uploadSettingID = $_POST['upload_setting_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $uploadSettingDetails = $this->uploadSettingModel->getUploadSetting($uploadSettingID);

            $response = [
                'success' => true,
                'uploadSettingName' => $uploadSettingDetails['upload_setting_name'],
                'uploadSettingDescription' => $uploadSettingDetails['upload_setting_description'],
                'maxFileSize' => $uploadSettingDetails['max_file_size']
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
require_once '../model/upload-setting-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new UploadSettingController(new UploadSettingModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>