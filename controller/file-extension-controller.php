<?php
session_start();

# -------------------------------------------------------------
#
# Function: FileExtensionController
# Description: 
# The FileExtensionController class handles file extension related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class FileExtensionController {
    private $fileExtensionModel;
    private $fileTypeModel;
    private $userModel;
    private $roleModel;
    private $uploadSettingModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided FileExtensionModel, FileTypeModel, UserModel and SecurityModel instances.
    # These instances are used for file extension related operations, menu group related operations, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param RoleModel $userModel     The RoleModel instance for role related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(FileExtensionModel $fileExtensionModel, FileTypeModel $fileTypeModel, UserModel $userModel, RoleModel $roleModel, UploadSettingModel $uploadSettingModel, SecurityModel $securityModel) {
        $this->fileExtensionModel = $fileExtensionModel;
        $this->fileTypeModel = $fileTypeModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
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
                case 'save file extension':
                    $this->saveFileExtension();
                    break;
                case 'get file extension details':
                    $this->getFileExtensionDetails();
                    break;
                case 'delete file extension':
                    $this->deleteFileExtension();
                    break;
                case 'delete multiple file extension':
                    $this->deleteMultipleFileExtension();
                    break;
                case 'duplicate file extension':
                    $this->duplicateFileExtension();
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
    # Function: saveFileExtension
    # Description: 
    # Updates the existing file extension if it exists; otherwise, inserts a new file extension.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveFileExtension() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileExtensionID = htmlspecialchars($_POST['file_extension_id'], ENT_QUOTES, 'UTF-8');
        $fileTypeID = htmlspecialchars($_POST['file_type_id'], ENT_QUOTES, 'UTF-8');
        $fileExtensionName = htmlspecialchars($_POST['file_extension_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkFileExtensionExist = $this->fileExtensionModel->checkFileExtensionExist($fileExtensionID);
        $total = $checkFileExtensionExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->fileExtensionModel->updateFileExtension($fileExtensionID, $fileExtensionName, $fileTypeID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'fileExtensionID' => $this->securityModel->encryptData($fileExtensionID)]);
            exit;
        } 
        else {
            $fileExtensionID = $this->fileExtensionModel->insertFileExtension($fileExtensionName, $fileTypeID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'fileExtensionID' => $this->securityModel->encryptData($fileExtensionID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteFileExtension
    # Description: 
    # Delete the file extension if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteFileExtension() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileExtensionID = htmlspecialchars($_POST['file_extension_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkFileExtensionExist = $this->fileExtensionModel->checkFileExtensionExist($fileExtensionID);
        $total = $checkFileExtensionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
        
        $this->fileExtensionModel->deleteFileExtension($fileExtensionID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleFileExtension
    # Description: 
    # Delete the selected file extensions if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleFileExtension() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileExtensionIDs = $_POST['file_extension_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($fileExtensionIDs as $fileExtensionID){
            $this->fileExtensionModel->deleteFileExtension($fileExtensionID);
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
    # Function: duplicateFileExtension
    # Description: 
    # Duplicates the file extension if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateFileExtension() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileExtensionID = htmlspecialchars($_POST['file_extension_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkFileExtensionExist = $this->fileExtensionModel->checkFileExtensionExist($fileExtensionID);
        $total = $checkFileExtensionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $fileExtensionID = $this->fileExtensionModel->duplicateFileExtension($fileExtensionID, $userID);

        echo json_encode(['success' => true, 'fileExtensionID' =>  $this->securityModel->encryptData($fileExtensionID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getFileExtensionDetails
    # Description: 
    # Handles the retrieval of file extension details such as file extension name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getFileExtensionDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['file_extension_id']) && !empty($_POST['file_extension_id'])) {
            $userID = $_SESSION['user_id'];
            $fileExtensionID = $_POST['file_extension_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $fileTypeID = $fileExtensionDetails['file_type_id'];

            $fileTypeDetails = $this->fileTypeModel->getFileType($fileTypeID);
            $fileTypeName = $fileTypeDetails['file_type_name'];

            $response = [
                'success' => true,
                'fileExtensionName' => $fileExtensionDetails['file_extension_name'],
                'fileTypeID' => $fileTypeID,
                'fileTypeName' => $fileTypeName
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
require_once '../model/file-extension-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/role-model.php';
require_once '../model/file-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new FileExtensionController(new FileExtensionModel(new DatabaseModel), new FileTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();

?>