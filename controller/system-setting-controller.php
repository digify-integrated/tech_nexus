<?php
session_start();

# -------------------------------------------------------------
#
# Function: SystemSettingController
# Description: 
# The SystemSettingController class handles system setting related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class SystemSettingController {
    private $systemSettingModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided SystemSettingModel, UserModel and SecurityModel instances.
    # These instances are used for system setting related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(SystemSettingModel $systemSettingModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel) {
        $this->systemSettingModel = $systemSettingModel;
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
                case 'save system setting':
                    $this->saveSystemSetting();
                    break;
                case 'get system setting details':
                    $this->getSystemSettingDetails();
                    break;
                case 'delete system setting':
                    $this->deleteSystemSetting();
                    break;
                case 'delete multiple system setting':
                    $this->deleteMultipleSystemSetting();
                    break;
                case 'duplicate system setting':
                    $this->duplicateSystemSetting();
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
    # Function: saveSystemSetting
    # Description: 
    # Updates the existing system setting if it exists; otherwise, inserts a new system setting.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSystemSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemSettingID = isset($_POST['system_setting_id']) ? htmlspecialchars($_POST['system_setting_id'], ENT_QUOTES, 'UTF-8') : null;
        $systemSettingName = htmlspecialchars($_POST['system_setting_name'], ENT_QUOTES, 'UTF-8');
        $systemSettingDescription = htmlspecialchars($_POST['system_setting_description'], ENT_QUOTES, 'UTF-8');
        $value = htmlspecialchars($_POST['value'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSystemSettingExist = $this->systemSettingModel->checkSystemSettingExist($systemSettingID);
        $total = $checkSystemSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->systemSettingModel->updateSystemSetting($systemSettingID, $systemSettingName, $systemSettingDescription, $value, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'systemSettingID' => $this->securityModel->encryptData($systemSettingID)]);
            exit;
        } 
        else {
            $systemSettingID = $this->systemSettingModel->insertSystemSetting($systemSettingName, $systemSettingDescription, $value, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'systemSettingID' => $this->securityModel->encryptData($systemSettingID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSystemSetting
    # Description: 
    # Delete the system setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSystemSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemSettingID = htmlspecialchars($_POST['system_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSystemSettingExist = $this->systemSettingModel->checkSystemSettingExist($systemSettingID);
        $total = $checkSystemSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->systemSettingModel->deleteSystemSetting($systemSettingID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleSystemSetting
    # Description: 
    # Delete the selected system settings if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleSystemSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemSettingIDs = $_POST['system_setting_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($systemSettingIDs as $systemSettingID){
            $this->systemSettingModel->deleteSystemSetting($systemSettingID);
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
    # Function: duplicateSystemSetting
    # Description: 
    # Duplicates the system setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateSystemSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemSettingID = htmlspecialchars($_POST['system_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSystemSettingExist = $this->systemSettingModel->checkSystemSettingExist($systemSettingID);
        $total = $checkSystemSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $systemSettingID = $this->systemSettingModel->duplicateSystemSetting($systemSettingID, $userID);

        echo json_encode(['success' => true, 'systemSettingID' =>  $this->securityModel->encryptData($systemSettingID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSystemSettingDetails
    # Description: 
    # Handles the retrieval of system setting details such as system setting name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSystemSettingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['system_setting_id']) && !empty($_POST['system_setting_id'])) {
            $userID = $_SESSION['user_id'];
            $systemSettingID = $_POST['system_setting_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $systemSettingDetails = $this->systemSettingModel->getSystemSetting($systemSettingID);

            $response = [
                'success' => true,
                'systemSettingName' => $systemSettingDetails['system_setting_name'],
                'systemSettingDescription' => $systemSettingDetails['system_setting_description'],
                'value' => $systemSettingDetails['value']
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
require_once '../model/system-setting-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new SystemSettingController(new SystemSettingModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>