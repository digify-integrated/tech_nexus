<?php
session_start();

# -------------------------------------------------------------
#
# Function: InterfaceSettingController
# Description: 
# The InterfaceSettingController class handles interface setting related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class InterfaceSettingController {
    private $interfaceSettingModel;
    private $userModel;
    private $roleModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided InterfaceSettingModel, UserModel and SecurityModel instances.
    # These instances are used for interface setting related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param InterfaceSettingModel $interfaceSettingModel     The InterfaceSettingModel instance for interface setting related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(InterfaceSettingModel $interfaceSettingModel, UserModel $userModel, RoleModel $roleModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->interfaceSettingModel = $interfaceSettingModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->securityModel = $securityModel;
        $this->systemModel = $systemModel;
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
                case 'save interface setting':
                    $this->saveInterfaceSetting();
                    break;
                case 'get interface setting details':
                    $this->getInterfaceSettingDetails();
                    break;
                case 'delete interface setting':
                    $this->deleteInterfaceSetting();
                    break;
                case 'delete multiple interface setting':
                    $this->deleteMultipleInterfaceSetting();
                    break;
                case 'duplicate interface setting':
                    $this->duplicateInterfaceSetting();
                    break;
                case 'change interface setting value':
                    $this->updateInterfaceSettingValue();
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
    # Function: saveInterfaceSetting
    # Description: 
    # Updates the existing interface setting if it exists; otherwise, inserts a new interface setting.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveInterfaceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $interfaceSettingID = isset($_POST['interface_setting_id']) ? htmlspecialchars($_POST['interface_setting_id'], ENT_QUOTES, 'UTF-8') : null;
        $interfaceSettingName = htmlspecialchars($_POST['interface_setting_name'], ENT_QUOTES, 'UTF-8');
        $interfaceSettingDescription = htmlspecialchars($_POST['interface_setting_description'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInterfaceSettingExist = $this->interfaceSettingModel->checkInterfaceSettingExist($interfaceSettingID);
        $total = $checkInterfaceSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->interfaceSettingModel->updateInterfaceSetting($interfaceSettingID, $interfaceSettingName, $interfaceSettingDescription, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'interfaceSettingID' => $this->securityModel->encryptData($interfaceSettingID)]);
            exit;
        } 
        else {
            $interfaceSettingID = $this->interfaceSettingModel->insertInterfaceSetting($interfaceSettingName, $interfaceSettingDescription, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'interfaceSettingID' => $this->securityModel->encryptData($interfaceSettingID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInterfaceSettingValue
    # Description: 
    # Handles the update of the profile picture.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateInterfaceSettingValue() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $interfaceSettingID = htmlspecialchars($_POST['interface_setting_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $interfaceSettingValueFileName = $_FILES['interface_setting_value']['name'];
        $interfaceSettingValueFileSize = $_FILES['interface_setting_value']['size'];
        $interfaceSettingValueFileError = $_FILES['interface_setting_value']['error'];
        $interfaceSettingValueTempName = $_FILES['interface_setting_value']['tmp_name'];
        $interfaceSettingValueFileExtension = explode('.', $interfaceSettingValueFileName);
        $interfaceSettingValueActualFileExtension = strtolower(end($interfaceSettingValueFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(1);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(2);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($interfaceSettingValueActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($interfaceSettingValueTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the interface setting image.']);
            exit;
        }
        
        if($interfaceSettingValueFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($interfaceSettingValueFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The uploaded file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $interfaceSettingValueActualFileExtension;

        $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'interface_setting/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'interface_setting/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $interfaceSetting = $this->interfaceSettingModel->getInterfaceSetting($interfaceSettingID);
        $interfaceSettingValue = $interfaceSetting['value'] !== null ? '.' . $interfaceSetting['value'] : null;

        if(file_exists($interfaceSettingValue)){
            if (!unlink($interfaceSettingValue)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($interfaceSettingValueTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->interfaceSettingModel->updateInterfaceSettingValue($interfaceSettingID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteInterfaceSetting
    # Description: 
    # Delete the interface setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteInterfaceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $interfaceSettingID = htmlspecialchars($_POST['interface_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInterfaceSettingExist = $this->interfaceSettingModel->checkInterfaceSettingExist($interfaceSettingID);
        $total = $checkInterfaceSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $interfaceSetting = $this->interfaceSettingModel->getInterfaceSetting($interfaceSettingID);
        $interfaceSettingValue = $interfaceSetting['value'] !== null ? '.' . $interfaceSetting['value'] : null;

        if(file_exists($interfaceSettingValue)){
            if (!unlink($interfaceSettingValue)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->interfaceSettingModel->deleteInterfaceSetting($interfaceSettingID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleInterfaceSetting
    # Description: 
    # Delete the selected interface settings if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleInterfaceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $interfaceSettingIDs = $_POST['interface_setting_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($interfaceSettingIDs as $interfaceSettingID){
            $interfaceSetting = $this->interfaceSettingModel->getInterfaceSetting($interfaceSettingID);
            $interfaceSettingValue = $interfaceSetting['value'] !== null ? '.' . $interfaceSetting['value'] : null;

            if(file_exists($interfaceSettingValue)){
                if (!unlink($interfaceSettingValue)) {
                    echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                    exit;
                }
            }
            
            $this->interfaceSettingModel->deleteInterfaceSetting($interfaceSettingID);
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
    # Function: duplicateInterfaceSetting
    # Description: 
    # Duplicates the interface setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateInterfaceSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $interfaceSettingID = htmlspecialchars($_POST['interface_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInterfaceSettingExist = $this->interfaceSettingModel->checkInterfaceSettingExist($interfaceSettingID);
        $total = $checkInterfaceSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $interfaceSetting = $this->interfaceSettingModel->getInterfaceSetting($interfaceSettingID);
        $interfaceSettingValue = $interfaceSetting['value'] !== null ? '.' . $interfaceSetting['value'] : null;

        if(file_exists($interfaceSettingValue)){
            $extension = pathinfo($interfaceSettingValue, PATHINFO_EXTENSION);

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $extension;

            $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'interface_setting/';
            $filePath = $directory . $fileNew;

            copy($interfaceSettingValue, $fileNew);
        }
        else{
            $filePath = null;
        }

        $interfaceSettingID = $this->interfaceSettingModel->duplicateInterfaceSetting($interfaceSettingID, $userID);

        if(file_exists($interfaceSettingValue)){
            $this->interfaceSettingModel->updateInterfaceSettingValue($interfaceSettingID, $filePath, $userID);
        }        

        echo json_encode(['success' => true, 'interfaceSettingID' =>  $this->securityModel->encryptData($interfaceSettingID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getInterfaceSettingDetails
    # Description: 
    # Handles the retrieval of interface setting details such as interface setting name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getInterfaceSettingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])) {
            $userID = $_SESSION['user_id'];
            $interfaceSettingID = $_POST['interface_setting_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $interfaceSettingDetails = $this->interfaceSettingModel->getInterfaceSetting($interfaceSettingID);

            $response = [
                'success' => true,
                'interfaceSettingName' => $interfaceSettingDetails['interface_setting_name'],
                'interfaceSettingDescription' => $interfaceSettingDetails['interface_setting_description'],
                'value' => $this->systemModel->checkImage($interfaceSettingDetails['value'], 'default')
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
require_once '../model/interface-setting-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InterfaceSettingController(new InterfaceSettingModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>