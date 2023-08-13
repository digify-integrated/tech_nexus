<?php
session_start();

# -------------------------------------------------------------
#
# Function: FileTypeController
# Description: 
# The FileTypeController class handles file type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class FileTypeController {
    private $fileTypeModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided FileTypeModel, UserModel and SecurityModel instances.
    # These instances are used for file type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param FileTypeModel $fileTypeModel     The FileTypeModel instance for file type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(FileTypeModel $fileTypeModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel) {
        $this->fileTypeModel = $fileTypeModel;
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
                case 'save file type':
                    $this->saveFileType();
                    break;
                case 'get file type details':
                    $this->getFileTypeDetails();
                    break;
                case 'delete file type':
                    $this->deleteFileType();
                    break;
                case 'delete multiple file type':
                    $this->deleteMultipleFileType();
                    break;
                case 'duplicate file type':
                    $this->duplicateFileType();
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
    # Function: saveFileType
    # Description: 
    # Updates the existing file type if it exists; otherwise, inserts a new file type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileTypeID = isset($_POST['file_type_id']) ? htmlspecialchars($_POST['file_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $fileTypeName = htmlspecialchars($_POST['file_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkFileTypeExist = $this->fileTypeModel->checkFileTypeExist($fileTypeID);
        $total = $checkFileTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->fileTypeModel->updateFileType($fileTypeID, $fileTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'fileTypeID' => $this->securityModel->encryptData($fileTypeID)]);
            exit;
        } 
        else {
            $fileTypeID = $this->fileTypeModel->insertFileType($fileTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'fileTypeID' => $this->securityModel->encryptData($fileTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteFileType
    # Description: 
    # Delete the file type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileTypeID = htmlspecialchars($_POST['file_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkFileTypeExist = $this->fileTypeModel->checkFileTypeExist($fileTypeID);
        $total = $checkFileTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->fileTypeModel->deleteLinkedFileExtension($fileTypeID);
        $this->fileTypeModel->deleteFileType($fileTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleFileType
    # Description: 
    # Delete the selected file types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileTypeIDs = $_POST['file_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($fileTypeIDs as $fileTypeID){
            $this->fileTypeModel->deleteLinkedFileExtension($fileTypeID);
            $this->fileTypeModel->deleteFileType($fileTypeID);
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
    # Function: duplicateFileType
    # Description: 
    # Duplicates the file type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $fileTypeID = htmlspecialchars($_POST['file_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkFileTypeExist = $this->fileTypeModel->checkFileTypeExist($fileTypeID);
        $total = $checkFileTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $fileTypeID = $this->fileTypeModel->duplicateFileType($fileTypeID, $userID);

        echo json_encode(['success' => true, 'fileTypeID' =>  $this->securityModel->encryptData($fileTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getFileTypeDetails
    # Description: 
    # Handles the retrieval of file type details such as file type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getFileTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['file_type_id']) && !empty($_POST['file_type_id'])) {
            $userID = $_SESSION['user_id'];
            $fileTypeID = $_POST['file_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $fileTypeDetails = $this->fileTypeModel->getFileType($fileTypeID);

            $response = [
                'success' => true,
                'fileTypeName' => $fileTypeDetails['file_type_name']
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
require_once '../model/file-type-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new FileTypeController(new FileTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>