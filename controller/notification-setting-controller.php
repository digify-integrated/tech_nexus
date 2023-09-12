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
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(NotificationSettingModel $notificationSettingModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->notificationSettingModel = $notificationSettingModel;
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
                case 'save notification setting':
                    $this->saveNotificationSetting();
                    break;
                case 'update system notification template':
                    $this->updateSystemNotificationTemplate();
                    break;
                case 'update email notification template':
                    $this->updateEmailNotificationTemplate();
                    break;
                case 'update sms notification template':
                    $this->updateSMSNotificationTemplate();
                    break;
                case 'get notification setting details':
                    $this->getNotificationSettingDetails();
                    break;
                case 'get system notification template details':
                    $this->getSystemNotificationTemplateDetails();
                    break;
                case 'get email notification template details':
                    $this->getEmailNotificationTemplateDetails();
                    break;
                case 'get sms notification template details':
                    $this->getSMSNotificationTemplateDetails();
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
    #
    # Function: updateSystemNotificationTemplate
    # Description: 
    # Updates the system notification template.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateSystemNotificationTemplate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingID = htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8');
        $systemNotificationTitle = htmlspecialchars($_POST['system_notification_title'], ENT_QUOTES, 'UTF-8');
        $systemNotificationMessage = htmlspecialchars($_POST['system_notification_message'], ENT_QUOTES, 'UTF-8');
    
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
    
        $this->notificationSettingModel->updateNotificationSettingTemplate($notificationSettingID, $systemNotificationTitle, $systemNotificationMessage, null, null, null, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateEmailNotificationTemplate
    # Description: 
    # Updates the email notification template.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateEmailNotificationTemplate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingID = htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8');
        $emailNotificationSubject = htmlspecialchars($_POST['email_notification_subject'], ENT_QUOTES, 'UTF-8');
        $emailNotificationBody = $_POST['email_notification_body'];
    
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
    
        $this->notificationSettingModel->updateNotificationSettingTemplate($notificationSettingID, null, null, $emailNotificationSubject, $emailNotificationBody, null, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSMSNotificationTemplate
    # Description: 
    # Updates the SMS notification template.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateSMSNotificationTemplate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $notificationSettingID = htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8');
        $smsNotificationMessage = htmlspecialchars($_POST['sms_notification_message'], ENT_QUOTES, 'UTF-8');

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
    
        $this->notificationSettingModel->updateNotificationSettingTemplate($notificationSettingID, null, null, null, null, $smsNotificationMessage, $userID);
            
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

    # -------------------------------------------------------------
    #
    # Function: getSystemNotificationTemplateDetails
    # Description: 
    # Handles the retrieval of system notification details such as system notification title, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSystemNotificationTemplateDetails() {
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
                'systemNotificationTitle' => $notificationSettingDetails['system_notification_title'],
                'systemNotificationMessage' => $notificationSettingDetails['system_notification_message']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmailNotificationTemplateDetails
    # Description: 
    # Handles the retrieval of email notification details such as email notification subject, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getEmailNotificationTemplateDetails() {
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
                'emailNotificationSubject' => $notificationSettingDetails['email_notification_subject'],
                'emailNotificationBody' => $notificationSettingDetails['email_notification_body']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSMSNotificationTemplateDetails
    # Description: 
    # Handles the retrieval of SMS notification details such as SMS notification message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSMSNotificationTemplateDetails() {
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
                'smsNotificationMessage' => $notificationSettingDetails['sms_notification_message']
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
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new NotificationSettingController(new NotificationSettingModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>