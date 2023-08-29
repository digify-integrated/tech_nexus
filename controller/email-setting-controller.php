<?php
session_start();

# -------------------------------------------------------------
#
# Function: EmailSettingController
# Description: 
# The EmailSettingController class handles email setting related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class EmailSettingController {
    private $emailSettingModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided EmailSettingModel, UserModel and SecurityModel instances.
    # These instances are used for email setting related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param EmailSettingModel $emailSettingModel     The EmailSettingModel instance for email setting related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EmailSettingModel $emailSettingModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel) {
        $this->emailSettingModel = $emailSettingModel;
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
                case 'save email setting':
                    $this->saveEmailSetting();
                    break;
                case 'get email setting details':
                    $this->getEmailSettingDetails();
                    break;
                case 'delete email setting':
                    $this->deleteEmailSetting();
                    break;
                case 'delete multiple email setting':
                    $this->deleteMultipleEmailSetting();
                    break;
                case 'duplicate email setting':
                    $this->duplicateEmailSetting();
                    break;
                case 'change mail password':
                    $this->updateMailPassword();
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
    # Function: saveEmailSetting
    # Description: 
    # Updates the existing email setting if it exists; otherwise, inserts a new email setting.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveEmailSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $emailSettingID = isset($_POST['email_setting_id']) ? htmlspecialchars($_POST['email_setting_id'], ENT_QUOTES, 'UTF-8') : null;
        $emailSettingName = htmlspecialchars($_POST['email_setting_name'], ENT_QUOTES, 'UTF-8');
        $emailSettingDescription = htmlspecialchars($_POST['email_setting_description'], ENT_QUOTES, 'UTF-8');
        $mailHost = htmlspecialchars($_POST['mail_host'], ENT_QUOTES, 'UTF-8');
        $mailUsername = htmlspecialchars($_POST['mail_username'], ENT_QUOTES, 'UTF-8');
        $mailEncryption = htmlspecialchars($_POST['mail_encryption'], ENT_QUOTES, 'UTF-8');
        $smtpAuth = htmlspecialchars($_POST['smtp_auth'], ENT_QUOTES, 'UTF-8');
        $mailFromName = htmlspecialchars($_POST['mail_from_name'], ENT_QUOTES, 'UTF-8');
        $port = htmlspecialchars($_POST['port'], ENT_QUOTES, 'UTF-8');
        $mailFromEmail = htmlspecialchars($_POST['mail_from_email'], ENT_QUOTES, 'UTF-8');
        $smtpAutoTLS = htmlspecialchars($_POST['smtp_auto_tls'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmailSettingExist = $this->emailSettingModel->checkEmailSettingExist($emailSettingID);
        $total = $checkEmailSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->emailSettingModel->updateEmailSetting($emailSettingID, $emailSettingName, $emailSettingDescription, $mailHost, $port, $smtpAuth, $smtpAutoTLS, $mailUsername, $mailEncryption, $mailFromName, $mailFromEmail, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'emailSettingID' => $this->securityModel->encryptData($emailSettingID)]);
            exit;
        } 
        else {
            $emailSettingID = $this->emailSettingModel->insertEmailSetting($emailSettingName, $emailSettingDescription, $mailHost, $port, $smtpAuth, $smtpAutoTLS, $mailUsername, $mailEncryption, $mailFromName, $mailFromEmail, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'emailSettingID' => $this->securityModel->encryptData($emailSettingID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateMailPassword
    # Description: 
    # Handles the update of the password.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateMailPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $emailSettingID = htmlspecialchars($_POST['email_setting_id'], ENT_QUOTES, 'UTF-8');
        $newPassword = htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8');
        $encryptedPassword = $this->securityModel->encryptData($newPassword);
    
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->emailSettingModel->updateMailPassword($emailSettingID, $encryptedPassword, $userID);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteEmailSetting
    # Description: 
    # Delete the email setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteEmailSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $emailSettingID = htmlspecialchars($_POST['email_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmailSettingExist = $this->emailSettingModel->checkEmailSettingExist($emailSettingID);
        $total = $checkEmailSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->emailSettingModel->deleteEmailSetting($emailSettingID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleEmailSetting
    # Description: 
    # Delete the selected email settings if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleEmailSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $emailSettingIDs = $_POST['email_setting_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($emailSettingIDs as $emailSettingID){
            $this->emailSettingModel->deleteEmailSetting($emailSettingID);
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
    # Function: duplicateEmailSetting
    # Description: 
    # Duplicates the email setting if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateEmailSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $emailSettingID = htmlspecialchars($_POST['email_setting_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmailSettingExist = $this->emailSettingModel->checkEmailSettingExist($emailSettingID);
        $total = $checkEmailSettingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $emailSettingID = $this->emailSettingModel->duplicateEmailSetting($emailSettingID, $userID);

        echo json_encode(['success' => true, 'emailSettingID' =>  $this->securityModel->encryptData($emailSettingID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmailSettingDetails
    # Description: 
    # Handles the retrieval of email setting details such as email setting name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getEmailSettingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])) {
            $userID = $_SESSION['user_id'];
            $emailSettingID = $_POST['email_setting_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $emailSettingDetails = $this->emailSettingModel->getEmailSetting($emailSettingID);
            $smtpAuth = $emailSettingDetails['smtp_auth'];
            $smtoAutoTLS = $emailSettingDetails['smtp_auto_tls'];
            $smtpAuthName = ($smtpAuth == 0) ? 'False' : 'True';
            $smtoAutoTLSName = ($smtoAutoTLS == 0) ? 'False' : 'True';

            $response = [
                'success' => true,
                'emailSettingName' => $emailSettingDetails['email_setting_name'],
                'emailSettingDescription' => $emailSettingDetails['email_setting_description'],
                'mailHost' => $emailSettingDetails['mail_host'],
                'port' => $emailSettingDetails['port'],
                'smtpAuth' => $smtpAuth,
                'smtoAutoTLS' => $smtoAutoTLS,
                'smtpAuthName' => $smtpAuthName,
                'smtoAutoTLSName' => $smtoAutoTLSName,
                'mailUsername' => $emailSettingDetails['mail_username'],
                'mailEncryption' => $emailSettingDetails['mail_encryption'],
                'mailFromName' => $emailSettingDetails['mail_from_name'],
                'mailFromEmail' => $emailSettingDetails['mail_from_email']
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
require_once '../model/email-setting-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new EmailSettingController(new EmailSettingModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>