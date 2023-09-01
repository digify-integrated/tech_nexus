<?php
session_start();

# -------------------------------------------------------------
#
# Function: NotificationSettingController
# Description: 
# The NotificationSettingController class handles notification setting related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class NotificationSettingController {
    private $notificationSettingModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided NotificationSettingModel, UserModel and SecurityModel instances.
    # These instances are used for notification setting related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param NotificationSettingModel $notificationSettingModel     The NotificationSettingModel instance for notification setting related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(NotificationSettingModel $notificationSettingModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel) {
        $this->notificationSettingModel = $notificationSettingModel;
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
                case 'save notification setting':
                    $this->saveNotificationSetting();
                    break;
                case 'get notification setting details':
                    $this->getNotificationSettingDetails();
                    break;
                case 'update notification channel status':
                    $this->updateNotificationChannelStatus();
                    break;
                case 'delete notification setting':
                    $this->deleteNotificationSetting();
                    break;
                case 'delete multiple notification setting':
                    $this->deleteMultipleNotificationSetting();
                    break;
                case 'duplicate notification setting':
                    $this->duplicateNotificationSetting();
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
    # Function: saveNotificationSetting
    # Description: 
    # Updates the existing notification setting if it exists; otherwise, inserts a new notification setting.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveNotificationSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingID = isset($_POST['notification_setting_id']) ? htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8') : null;
        $notificationSettingName = htmlspecialchars($_POST['notification_setting_name'], ENT_QUOTES, 'UTF-8');
        $notificationSettingDescription = htmlspecialchars($_POST['notification_setting_description'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNotificationSettingExist = $this->notificationSettingModel->checkNotificationSettingExist($notificationSettingID);
        $total = $checkNotificationSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->notificationSettingModel->updateNotificationSetting($notificationSettingID, $notificationSettingName, $notificationSettingDescription, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'notificationSettingID' => $this->securityModel->encryptData($notificationSettingID)]);
            exit;
        } 
        else {
            $notificationSettingID = $this->notificationSettingModel->insertNotificationSetting($notificationSettingName, $notificationSettingDescription, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'notificationSettingID' => $this->securityModel->encryptData($notificationSettingID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateNotificationChannelStatus
    # Description: 
    # Updates the notification channel status.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateNotificationChannelStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingID = htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8');
        $channel = htmlspecialchars($_POST['channel'], ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNotificationSettingExist = $this->notificationSettingModel->checkNotificationSettingExist($notificationSettingID);
        $total = $checkNotificationSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->notificationSettingModel->updateNotificationChannelStatus($notificationSettingID, $channel, $status, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteNotificationSetting
    # Description: 
    # Delete the notification setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteNotificationSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingID = htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNotificationSettingExist = $this->notificationSettingModel->checkNotificationSettingExist($notificationSettingID);
        $total = $checkNotificationSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->notificationSettingModel->deleteNotificationSetting($notificationSettingID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleNotificationSetting
    # Description: 
    # Delete the selected notification settings if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleNotificationSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingIDs = $_POST['notification_setting_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($notificationSettingIDs as $notificationSettingID){
            $this->notificationSettingModel->deleteNotificationSetting($notificationSettingID);
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
    # Function: duplicateNotificationSetting
    # Description: 
    # Duplicates the notification setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateNotificationSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingID = htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkNotificationSettingExist = $this->notificationSettingModel->checkNotificationSettingExist($notificationSettingID);
        $total = $checkNotificationSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $notificationSettingID = $this->notificationSettingModel->duplicateNotificationSetting($notificationSettingID, $userID);

        echo json_encode(['success' => true, 'notificationSettingID' =>  $this->securityModel->encryptData($notificationSettingID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getNotificationSettingDetails
    # Description: 
    # Handles the retrieval of notification setting details such as notification setting name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getNotificationSettingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])) {
            $userID = $_SESSION['user_id'];
            $notificationSettingID = $_POST['notification_setting_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $notificationSettingDetails = $this->notificationSettingModel->getNotificationSetting($notificationSettingID);

            $response = [
                'success' => true,
                'notificationSettingName' => $notificationSettingDetails['notification_setting_name'],
                'notificationSettingDescription' => $notificationSettingDetails['notification_setting_description']
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
require_once '../model/notification-setting-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new NotificationSettingController(new NotificationSettingModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>