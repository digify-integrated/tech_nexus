<?php
session_start();

# -------------------------------------------------------------
#
# Function: UserController
# Description: 
# The UserController class handles user related related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class UserController {
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemSettingModel;
    private $emailSettingModel;
    private $employeeModel;
    private $notificationSettingModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided SystemActionModel, UserModel and SecurityModel instances.
    # These instances are used for user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param EmailSettingModel $emailSettingModel     The EmailSettingModel instance for email setting related operations.
    # - @param NotificationSettingModel $notificationSettingModel     The NotificationSettingModel instance for notification setting related operations.
    # - @param EmployeeModel $employeeModel     The EmployeeModel instance for employee related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, EmailSettingModel $emailSettingModel, NotificationSettingModel $notificationSettingModel, EmployeeModel $employeeModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->emailSettingModel = $emailSettingModel;
        $this->employeeModel = $employeeModel;
        $this->notificationSettingModel = $notificationSettingModel;
        $this->systemModel = $systemModel;
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
                case 'save user account':
                    $this->saveUserAccount();
                    break;
                case 'delete user account':
                    $this->deleteUserAccount();
                    break;
                case 'delete multiple user account':
                    $this->deleteMultipleUserAccount();
                    break;
                case 'activate user account':
                    $this->activateUserAccount();
                    break;
                case 'activate multiple user account':
                    $this->activateMultipleUserAccount();
                    break;
                case 'deactivate user account':
                    $this->deactivateUserAccount();
                    break;
                case 'deactivate multiple user account':
                    $this->deactivateMultipleUserAccount();
                    break;
                case 'lock user account':
                    $this->lockUserAccount();
                    break;
                case 'lock multiple user account':
                    $this->lockMultipleUserAccount();
                    break;
                case 'unlock user account':
                    $this->unlockUserAccount();
                    break;
                case 'unlock multiple user account':
                    $this->unlockMultipleUserAccount();
                    break;
                case 'get user account details':
                    $this->getUserByID();
                    break;
                case 'link user account to contact':
                    $this->linkUserAccountToContact();
                    break;
                case 'unlink user account to contact':
                    $this->unlinkUserAccountToContact();
                    break;
                case 'authenticate':
                    $this->authenticate();
                    break;
                case 'password reset':
                    $this->passwordReset();
                    break;
                case 'forgot password':
                    $this->forgotPassword();
                    break;
                case 'otp authentication':
                    $this->otpAuthentication();
                    break;
                case 'save ui customization':
                    $this->saveUICustomization();
                    break;
                case 'get ui customization':
                    $this->getUICustomization();
                    break;
                case 'update notification setting':
                    $this->updateUserNotificationSetting();
                    break;
                case 'update two factor authentication':
                    $this->updateTwoFactorAuthentication();
                    break;
                case 'change password shortcut':
                    $this->updatePasswordShortcut();
                    break;
                case 'change user account password':
                    $this->updateUserAccountPassword();
                    break;
                case 'change user account profile picture':
                    $this->updateUserAccountProfilePicture();
                    break;
                case 'send reset password instructions':
                    $this->sendResetPasswordInstructions();
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
    # Function: saveUserAccount
    # Description: 
    # Updates the existing user account if it exists; otherwise, inserts a new user account.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
        $fileAs = htmlspecialchars($_POST['file_as'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $password = $this->securityModel->encryptData(DEFAULT_PASSWORD);
        $passwordExpiryDate = date('Y-m-d', strtotime('-6 months'));
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->userModel->updateUserAccount($userAccountID, $fileAs, $email, $userID);
            
            echo json_encode(['success' => true, 'userAccountID' => $this->securityModel->encryptData($userAccountID)]);
            exit;
        } 
        else {
            $checkUserEmailExist = $this->userModel->checkUserEmailExist($email);
            $total = $checkUserEmailExist['total'] ?? 0;

            if($total > 0){
                echo json_encode(['success' => false, 'insertRecord' => false, 'message' => 'The email address already exists.']);
                exit;
            }

            $userAccountID = $this->userModel->insertUserAccount($fileAs, $email, $password, $passwordExpiryDate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'userAccountID' => $this->securityModel->encryptData($userAccountID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveUICustomization
    # Description: 
    # Updates the existing customization setting if it exists; otherwise, inserts a new setting.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveUICustomization() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
        $customizationValue = htmlspecialchars($_POST['customizationValue'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUICustomizationSettingExist = $this->userModel->checkUICustomizationSettingExist($userID);
        $total = $checkUICustomizationSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->userModel->updateUICustomizationSetting($userID, $type, $customizationValue, $userID);
        } 
        else {
            $this->userModel->insertUICustomizationSetting($userID, $type, $customizationValue, $userID);
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
    # Function: deleteUserAccount
    # Description:
    # Delete the user account if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($userAccountID == $userID){
            echo json_encode(['success' => false, 'message' =>  'You cannot delete the user you are currently logged in as.']);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $userAccount = $this->userModel->getUserByID($userAccountID);
        $profilePicture = $userAccount['profile_picture'] !== null ? '.' . $userAccount['profile_picture'] : null;

        if(file_exists($profilePicture)){
            if (!unlink($profilePicture)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
        
        $this->userModel->deleteUserAccount($userAccountID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleUserAccount
    # Description:
    # Delete the selected user account if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountIDs = $_POST['user_account_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($userAccountIDs as $userAccountID){
            if($userAccountID != $userID){
                $userAccount = $this->userModel->getUserByID($userAccountID);
                $profilePicture = $userAccount['profile_picture'] !== null ? '.' . $userAccount['profile_picture'] : null;
        
                if(file_exists($profilePicture)){
                    if (!unlink($profilePicture)) {
                        echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                        exit;
                    }
                }
    
                $this->userModel->deleteUserAccount($userAccountID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Activate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: activateUserAccount
    # Description:
    # Activate the user account if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function activateUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->userModel->activateUserAccount($userAccountID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: activateMultipleUserAccount
    # Description:
    # Activate the selected user account if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function activateMultipleUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountIDs = $_POST['user_account_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($userAccountIDs as $userAccountID){
            $this->userModel->activateUserAccount($userAccountID, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Deactivate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deactivateUserAccount
    # Description:
    # Deactivate the user account if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deactivateUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($userAccountID == $userID){
            echo json_encode(['success' => false, 'message' =>  'You cannot deactivate the user you are currently logged in as.']);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->userModel->deactivateUserAccount($userAccountID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deactivateMultipleUserAccount
    # Description:
    # Deactivate the selected user account if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deactivateMultipleUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountIDs = $_POST['user_account_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($userAccountIDs as $userAccountID){
            if($userAccountID != $userID){
                $this->userModel->deactivateUserAccount($userAccountID, $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Lock methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: lockUserAccount
    # Description:
    # Lock the user account if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function lockUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($userAccountID == $userID){
            echo json_encode(['success' => false, 'message' =>  'You cannot lock the user you are currently logged in as.']);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->userModel->lockUserAccount($userAccountID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: lockMultipleUserAccount
    # Description:
    # Lock the selected user account if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function lockMultipleUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountIDs = $_POST['user_account_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($userAccountIDs as $userAccountID){
            if($userAccountID != $userID){
                $this->userModel->lockUserAccount($userAccountID, $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Unlock methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: unlockUserAccount
    # Description:
    # Unlock the user account if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function unlockUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->userModel->unlockUserAccount($userAccountID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: unlockMultipleUserAccount
    # Description:
    # Unlock the selected user account if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function unlockMultipleUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountIDs = $_POST['user_account_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($userAccountIDs as $userAccountID){
            $this->userModel->unlockUserAccount($userAccountID, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUserByID
    # Description: 
    # Handles the retrieval of user account details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getUserByID() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['user_account_id']) && !empty($_POST['user_account_id'])) {
            $userID = $_SESSION['user_id'];
            $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $userAccount = $this->userModel->getUserByID($userAccountID);
            $isLocked = $userAccount['is_locked'];
            $isActive = $userAccount['is_active'];
            $accountLockDuration = $userAccount['account_lock_duration'];
            $profilePicture = $this->systemModel->checkImage($userAccount['profile_picture'], 'profile');
            $passwordExpiryDate = date('m/d/Y', strtotime($userAccount['password_expiry_date']));
            $lastPasswordReset = (!empty($userAccount['last_password_reset'])) ? date('m/d/Y h:i:s a', strtotime($userAccount['last_password_reset'])) : 'Never Reset';
            $lastConnectionDate = (!empty($userAccount['last_connection_date'])) ? date('m/d/Y h:i:s a', strtotime($userAccount['last_connection_date'])) : 'Never Connected';
            $lastFailedLoginAttempt = (!empty($userAccount['last_failed_login_attempt'])) ? date('m/d/Y h:i:s a', strtotime($userAccount['last_failed_login_attempt'])) : '--';

            $isActiveBadge = $isActive ? '<span class="badge bg-light-success">Active</span>' : '<span class="badge bg-light-danger">Inactive</span>';
            $isLockedBadge = $isLocked ? '<span class="badge bg-light-danger">Yes</span>' : '<span class="badge bg-light-success">No</span>';

            $accountLockDuration = ($accountLockDuration > 0) ? 'Locked for ' . implode(", ", $this->formatDuration($accountLockDuration)) : '--';

            $contactDetails = $this->userModel->getContactByID($userAccountID);
            $contactID = $contactDetails['contact_id'] ?? null;

            $linkedContact = !empty($contactID) ? ($this->employeeModel->getPersonalInformation($contactID)['file_as'] ?? '--') : '--';
            
            $response = [
                'success' => true,
                'fileAs' => $userAccount['file_as'],
                'email' => $userAccount['email'],
                'linkedContact' => $linkedContact,
                'isLocked' => $isLockedBadge,
                'isActive' => $isActiveBadge,
                'lastFailedLoginAttempt' => $lastFailedLoginAttempt,
                'lastConnectionDate' => $lastConnectionDate,
                'passwordExpiryDate' => $passwordExpiryDate,
                'lastPasswordReset' => $lastPasswordReset,
                'accountLockDuration' => $accountLockDuration,
                'profilePicture' => $profilePicture
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUICustomization
    # Description: 
    # Handles the retrieval of UI customization options such as theme, contrast, caption display, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getUICustomization() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $uiCustomizationSetting = $this->userModel->getUICustomizationSetting($userID);
            
            $response = [
                'success' => true,
                'themeContrast' => $uiCustomizationSetting['theme_contrast'] ?? 'true',
                'captionShow' => $uiCustomizationSetting['caption_show'] ?? 'true',
                'presetTheme' => $uiCustomizationSetting['preset_theme'] ?? 'preset-1',
                'darkLayout' => $uiCustomizationSetting['dark_layout'] ?? 'false',
                'rtlLayout' => $uiCustomizationSetting['rtl_layout'] ?? 'true',
                'boxContainer' => $uiCustomizationSetting['box_container'] ?? 'false'
            ];

            echo json_encode($response);
            exit;
        }
        else {
            $response = [
                'success' => 'true',
                'themeContrast' => 'false',
                'captionShow' => 'true',
                'presetTheme' => 'preset-1',
                'darkLayout' => 'false',
                'rtlLayout' => 'true',
                'boxContainer' => 'false'
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateConnectionAndRememberToken
    # Description: 
    # Updates the user's last connection timestamp and sets the remember token if "Remember Me" is selected.
    #
    # Parameters: 
    # - $user (array): The user details.
    # - $rememberMe (bool): The remember me value.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    private function updateConnectionAndRememberToken($user, $rememberMe) {
        $userID = $user['user_id'];
        $connectionDate = date('Y-m-d H:i:s');
    
        $this->userModel->updateLastConnection($userID, $connectionDate);
    
        if ($rememberMe) {
            $rememberToken = bin2hex(random_bytes(16));
            $this->userModel->updateRememberToken($userID, $rememberToken);
            setcookie('remember_token', $rememberToken, time() + (30 * 24 * 60 * 60), '/');
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUserNotificationSetting
    # Description: 
    # Handles the update of the notification setting based on the provided user ID and checked state.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateUserNotificationSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $isChecked = $_POST['isChecked'];
    
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->userModel->updateUserNotificationSetting($userID, $isChecked, $userID);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTwoFactorAuthentication
    # Description: 
    # Handles the update of the two-factor authentication setting based on the provided user ID and checked state.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateTwoFactorAuthentication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $isChecked = $_POST['isChecked'];
    
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->userModel->updateTwoFactorAuthentication($userID, $isChecked, $userID);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePasswordShortcut
    # Description: 
    # Handles the update of the password by verifying the user's old password and updating it to the new password.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updatePasswordShortcut() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $oldPassword = htmlspecialchars($_POST['shortcut_old_password'], ENT_QUOTES, 'UTF-8');
        $newPassword = htmlspecialchars($_POST['shortcut_new_password'], ENT_QUOTES, 'UTF-8');
        $encryptedPassword = $this->securityModel->encryptData($newPassword);
    
        $user = $this->userModel->getUserByID($userID);    
        $email = $user['email'] ?? null;
        $isActive = $user['is_active'] ?? 0;
        $userPassword = $this->securityModel->decryptData($user['password']);
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        if ($oldPassword !== $userPassword) {
            echo json_encode(['success' => false, 'message' => 'Your old password does not match your current password.']);
            exit;
        }
    
        $checkPasswordHistory = $this->checkPasswordHistory($userID, $email, $newPassword);
    
        if ($checkPasswordHistory > 0) {
            echo json_encode(['success' => false, 'message' => 'Your new password must not match your previous one. Please choose a different password.']);
            exit;
        }
    
        $lastPasswordChange = date('Y-m-d H:i:s');
        $passwordExpiryDate = date('Y-m-d', strtotime('+6 months'));

        $this->userModel->updateUserPassword($userID, $email, $encryptedPassword, $passwordExpiryDate, $lastPasswordChange);
        $this->userModel->insertPasswordHistory($userID, $email, $encryptedPassword, $lastPasswordChange);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUserAccountPassword
    # Description: 
    # Handles the update of the password.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateUserAccountPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
        $newPassword = htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8');
        $encryptedPassword = $this->securityModel->encryptData($newPassword);
    
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $userAccount = $this->userModel->getUserByID($userAccountID);
        $email = $userAccount['email'] ?? null;
    
        $checkPasswordHistory = $this->checkPasswordHistory($userAccountID, $email, $newPassword);
    
        if ($checkPasswordHistory > 0) {
            echo json_encode(['success' => false, 'message' => 'The new password must not match the previous password. Please choose a different password.']);
            exit;
        }
    
        $lastPasswordChange = date('Y-m-d H:i:s');
        $passwordExpiryDate = date('Y-m-d', strtotime('+6 months'));

        $this->userModel->updateUserPassword($userAccountID, $email, $encryptedPassword, $passwordExpiryDate, $lastPasswordChange);
        $this->userModel->insertPasswordHistory($userAccountID, $email, $encryptedPassword, $lastPasswordChange);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUserAccountProfilePicture
    # Description: 
    # Handles the update of the profile picture.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateUserAccountProfilePicture() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $profilePictureFileName = $_FILES['profile_picture']['name'];
        $profilePictureFileSize = $_FILES['profile_picture']['size'];
        $profilePictureFileError = $_FILES['profile_picture']['error'];
        $profilePictureTempName = $_FILES['profile_picture']['tmp_name'];
        $profilePictureFileExtension = explode('.', $profilePictureFileName);
        $profilePictureActualFileExtension = strtolower(end($profilePictureFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(1);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(1);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($profilePictureActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($profilePictureTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the profile picture.']);
            exit;
        }
        
        if($profilePictureFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($profilePictureFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The uploaded file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $profilePictureActualFileExtension;

        $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'user/profile_picture/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'user/profile_picture/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $userAccount = $this->userModel->getUserByID($userAccountID);
        $profilePicture = $userAccount['profile_picture'] !== null ? '.' . $userAccount['profile_picture'] : null;

        if(file_exists($profilePicture)){
            if (!unlink($profilePicture)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($profilePictureTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->userModel->updateUserProfilePicture($userAccountID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Other methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: authenticate
    # Description: 
    # Handles the login process, including two-factor authentication and account locking.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
        $rememberMe = isset($_POST['remember_me']);
    
        $user = $this->userModel->getUserByEmail($email);
    
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'The email or password you entered is invalid. Please double-check your credentials and try again.']);
            exit;
        }
    
        $userID = $user['user_id'];
        $userPassword = $this->securityModel->decryptData($user['password']);
        $encryptedUserID = $this->securityModel->encryptData($userID);

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        if(!empty($contactID)){
            $contactDetails = $this->employeeModel->getEmployee($contactID);
            $portalAccess = $contactDetails['portal_access'] ?? 0;
        }
        else{
            $portalAccess = 0;
        }        
    
        if ($password !== $userPassword) {
            $this->handleInvalidCredentials($user);
            return;
        }
    
        if (!$user || !$user['is_active'] || (!empty($contactID) && !$portalAccess)) {
            echo json_encode(['success' => false, 'message' => 'Your account is currently inactive. Please contact the administrator for assistance.']);
            exit;
        }
    
        if ($this->passwordHasExpired($user)) {
            $this->handlePasswordExpiration($user, $email, $encryptedUserID);
            exit;
        }
    
        if ($user['is_locked']) {
            $this->handleAccountLock($user);
            exit;
        }
    
        $this->userModel->updateLoginAttempt($userID, 0, null);
    
        if ($user['two_factor_auth']) {
            $this->handleTwoFactorAuth($user, $email, $encryptedUserID, $rememberMe);
            exit;
        }
    
        $this->updateConnectionAndRememberToken($user, $rememberMe);
        $_SESSION['user_id'] = $userID;
        $_SESSION['contact_id'] = $contactID;
    
        echo json_encode(['success' => true, 'twoFactorAuth' => false]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: otpAuthentication
    # Description: 
    # Handles the OTP authentication process, including OTP validation, expiry check, and remember me functionality.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function otpAuthentication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $this->securityModel->decryptData($_POST['user_id']);
        $otp = htmlspecialchars($_POST['otp'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user) {
            echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
            exit;
        }

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;
    
        $email = $user['email'] ?? null;
        $rememberMe = $user['remember_me'] ?? 0;
        $userOTP = $this->securityModel->decryptData($user['otp']);
        $userOTPExpiryDate = $user['otp_expiry_date'];
        $failedOTPAttempts = $user['failed_otp_attempts'];
    
        if (empty($email)) {
            echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
            exit;
        }
    
        if ($otp !== $userOTP) {
            $systemSettingDetails = $this->systemSettingModel->getSystemSetting(1);
            $maxFailedOTPAttempts = $systemSettingDetails['value'] ?? MAX_FAILED_OTP_ATTEMPTS;

            if ($failedOTPAttempts >= $maxFailedOTPAttempts) {
                $otpExpiryDate = date('Y-m-d H:i:s', strtotime('-1 year'));
                $this->userModel->updateOTPAsExpired($userID, $otpExpiryDate);
    
                echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid otp')]);
                exit;
            }
    
            $this->userModel->updateFailedOTPAttempts($userID, $failedOTPAttempts + 1);
            echo json_encode(['success' => false, 'message' => 'The email verification code you entered is incorrect.']);
            exit;
        }
    
        if (strtotime(date('Y-m-d H:i:s')) > strtotime($userOTPExpiryDate)) {
            echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('otp expired')]);
            exit;
        }
    
        if ($rememberMe) {
            $rememberToken = bin2hex(random_bytes(16));
            $this->userModel->updateRememberToken($userID, $rememberToken);
            setcookie('remember_token', $rememberToken, time() + (30 * 24 * 60 * 60), '/');
        }
    
        $connectionDate = date('Y-m-d H:i:s');
        $this->userModel->updateLastConnection($userID, $connectionDate);
    
        $_SESSION['user_id'] = $userID;
        $_SESSION['contact_id'] = $contactID;
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: handleInvalidCredentials
    # Description:
    # Updates the failed login attempts and, if the maximum attempts are reached, locks the account.
    #
    # Parameters: 
    # - $user (array): The user details.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    private function handleInvalidCredentials($user) {
        $userID = $user['user_id'];
        $failedAttempts = $user['failed_login_attempts'] + 1;
        $lastFailedLogin = date('Y-m-d H:i:s');
    
        $this->userModel->updateLoginAttempt($userID, $failedAttempts, $lastFailedLogin);

        $systemSettingDetails = $this->systemSettingModel->getSystemSetting(1);
        $maxFailedLoginAttempts = $systemSettingDetails['value'] ?? MAX_FAILED_LOGIN_ATTEMPTS;

        if ($failedAttempts > $maxFailedLoginAttempts) {
            $lockDuration = pow(2, ($failedAttempts - $maxFailedLoginAttempts)) * 5;
            $this->userModel->updateAccountLock($userID, 1, $lockDuration);
            
            $durationParts = $this->formatDuration($lockDuration);
            
            $message = "You have reached the maximum number of failed login attempts. Your account has been locked";
            
            if (count($durationParts) > 0) {
                $message .= " for " . implode(", ", $durationParts);
            }
            
            $message .= ".";
            echo json_encode(['success' => false, 'message' => $message]);
        }
        else {
            echo json_encode(['success' => false, 'message' => 'The email or password you entered is invalid. Please double-check your credentials and try again.']);
        }
    
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: handlePasswordExpiration
    # Description:
    # Updates the reset token and sends a password reset link to the user's email address.
    #
    # Parameters: 
    # - $user (array): The user details.
    # - $email (string): The email address of the user.
    # - $encryptedUserID (string): The encrypted user ID.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    private function handlePasswordExpiration($user, $email, $encryptedUserID) {
        $userID = $user['user_id'];
        $resetTokenExpiryDate = $user['reset_token_expiry_date'];
    
        if (strtotime(date('Y-m-d H:i:s')) > strtotime($resetTokenExpiryDate)) {
            $resetToken = $this->generateResetToken();
            $encryptedResetToken = $this->securityModel->encryptData($resetToken);
            $resetTokenExpiryDate = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
            $this->userModel->updateResetToken($userID, $encryptedResetToken, $resetTokenExpiryDate);
            $this->sendPasswordReset($email, $encryptedUserID, $encryptedResetToken);
    
            echo json_encode(['success' => false, 'message' => "Your password has expired. To reset your password, we have sent a password reset link to your registered email address. Please follow the instructions in the email to securely reset your password."]);
        }
    
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: handleAccountLock
    # Description:
    # Checks the account lock duration and displays the remaining lock time.
    # If the lock time has expired, unlocks the account.
    #
    # Parameters: 
    # - $user (array): The user details.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    private function handleAccountLock($user) {
        $userID = $user['user_id'];
        $lockDuration = $user['account_lock_duration'];
        $lastFailedLogin = strtotime($user['last_failed_login_attempt']);
        $unlockTime = strtotime("+$lockDuration minutes", $lastFailedLogin);
    
        if (time() < $unlockTime) {
            $remainingTime = round(($unlockTime - time()) / 60);
            echo json_encode(['success' => false, 'message' => "Your account has been locked. Please try again in $remainingTime minutes."]);
        }
        else {
            $this->userModel->updateAccountLock($userID, 0, null);
        }
    
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: handleTwoFactorAuth
    # Description:
    # Generates and encrypts an OTP, sets the OTP expiry date, and sends the OTP to the user's email.
    #
    # Parameters: 
    # - $user (array): The user details.
    # - $email (string): The email address of the user.
    # - $encryptedUserID (string): The encrypted user ID.
    # - $rememberMe (bool): The remember me value.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    private function handleTwoFactorAuth($user, $email, $encryptedUserID, $rememberMe) {
        $userID = $user['user_id'];
        $otp = $this->generateToken(6, 6);
        $encryptedOTP = $this->securityModel->encryptData($otp);
        $otpExpiryDate = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
        $rememberMe = $rememberMe ? 1 : 0;
    
        $this->userModel->updateOTP($userID, $encryptedOTP, $otpExpiryDate, $rememberMe);
        $this->sendOTP($email, $otp);
    
        echo json_encode(['success' => true, 'twoFactorAuth' => true, 'encryptedUserID' => $encryptedUserID]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: formatDuration
    # Description:
    # Updates the failed login attempts and, if the maximum attempts are reached, locks the account.
    #
    # Parameters: 
    # - $lockDuration (int): The duration in seconds that needs to be formatted. This value represents the total duration that you want to convert into a human-readable format.
    #
    # Returns: Returns a formatted string representing the duration in a human-readable format. 
    #  The format includes years, months, days, hours, and minutes, as applicable. 
    #  The function constructs this string based on the provided $lockDuration parameter.
    #
    # -------------------------------------------------------------
    private function formatDuration($lockDuration) {
        $durationParts = [];
    
        $years = floor($lockDuration / (60 * 60 * 24 * 30 * 12));
        $lockDuration %= (60 * 60 * 24 * 30 * 12);
    
        if ($years > 0) {
            $durationParts[] = number_format($years) . " year" . (($years > 1) ? "s" : "");
        }
    
        $months = floor($lockDuration / (60 * 60 * 24 * 30));
        $lockDuration %= (60 * 60 * 24 * 30);
    
        if ($months > 0) {
            $durationParts[] = number_format($months) . " month" . (($months > 1) ? "s" : "");
        }
    
        $days = floor($lockDuration / (60 * 60 * 24));
        $lockDuration %= (60 * 60 * 24);
    
        if ($days > 0) {
            $durationParts[] = number_format($days) . " day" . (($days > 1) ? "s" : "");
        }
    
        $hours = floor($lockDuration / (60 * 60));
        $lockDuration %= (60 * 60);
    
        if ($hours > 0) {
            $durationParts[] = number_format($hours) . " hour" . (($hours > 1) ? "s" : "");
        }
    
        $minutes = floor($lockDuration / 60);
        $lockDuration %= 60;
    
        if ($minutes > 0) {
            $durationParts[] = number_format($minutes) . " minute" . (($minutes > 1) ? "s" : "");
        }
    
        return $durationParts;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: passwordHasExpired
    # Description:
    # Checks if the user's password is expired.
    #
    # Parameters: 
    # - $user (array): The user details.
    #
    # Returns: Bool
    #
    # -------------------------------------------------------------
    private function passwordHasExpired($user) {
        $passwordExpiryDate = new DateTime($user['password_expiry_date']);
        $currentDate = new DateTime();
        
        if ($currentDate > $passwordExpiryDate) {
            return true;
        }
        
        return false;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: passwordReset
    # Description: 
    # Handles the password reset process, including validation and password history check.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function passwordReset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = htmlspecialchars($this->securityModel->decryptData($_POST['user_id']), ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
        $encryptedPassword = $this->securityModel->encryptData($password);
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user) {
            echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
            exit;
        }
    
        $email = $user['email'] ?? null;
    
        if (empty($email)) {
            echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
            exit;
        }
    
        $resetTokenExpiryDate = $user['reset_token_expiry_date'];
    
        if (strtotime(date('Y-m-d H:i:s')) > strtotime($resetTokenExpiryDate)) {
            echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('password reset token expired')]);
            exit;
        }
    
        $checkPasswordHistory = $this->checkPasswordHistory($userID, $email, $password);
    
        if ($checkPasswordHistory > 0) {
            echo json_encode(['success' => false, 'message' => 'Your new password must not match your previous one. Please choose a different password.']);
            exit;
        }
    
        $lastPasswordChange = date('Y-m-d H:i:s');
        $passwordExpiryDate = date('Y-m-d', strtotime('+6 months'));
        $this->userModel->updateUserPassword($userID, $email, $encryptedPassword, $passwordExpiryDate, $lastPasswordChange);
        $this->userModel->insertPasswordHistory($userID, $email, $encryptedPassword, $lastPasswordChange);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: forgotPassword
    # Description: 
    # Generates a reset token and updates the user's reset token and expiry date.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $user = $this->userModel->getUserByEmail($email);
    
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'The email address is invalid or does not exist.']);
            exit;
        }
    
        $userID = $user['user_id'] ?? null;
        $encryptedUserID = $this->securityModel->encryptData($userID);
    
        if (empty($userID)) {
            echo json_encode(['success' => false, 'message' => 'The email address is invalid or does not exist.']);
            exit;
        }
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'message' => 'Your account is currently inactive. Please contact the administrator for assistance.']);
            exit;
        }
    
        $resetToken = $this->generateResetToken();
        $encryptedResetToken = $this->securityModel->encryptData($resetToken);
        $resetTokenExpiryDate = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
        $this->userModel->updateResetToken($userID, $encryptedResetToken, $resetTokenExpiryDate);
        $this->sendPasswordReset($email, $encryptedUserID, $encryptedResetToken);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: sendResetPasswordInstructions
    # Description: 
    # Send the password reset instructions.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function sendResetPasswordInstructions() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
        $encryptedUserID = $this->securityModel->encryptData($userAccountID);
    
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $userAccount = $this->userModel->getUserByID($userAccountID);
        $email = $userAccount['email'] ?? null;
    
        $resetToken = $this->generateResetToken();
        $encryptedResetToken = $this->securityModel->encryptData($resetToken);
        $resetTokenExpiryDate = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
        $this->userModel->updateResetToken($userAccountID, $encryptedResetToken, $resetTokenExpiryDate);
        $this->sendPasswordReset($email, $encryptedUserID, $encryptedResetToken);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: checkPasswordHistory
    # Description: 
    # Checks the password history for a given user ID and email to determine if the new password matches any previous passwords.
    #
    # Parameters: 
    # - $p_user_id (array): The user ID.
    # - $p_email (string): The email address of the user.
    # - $p_password (string): The password of the user.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    private function checkPasswordHistory($p_user_id, $p_email, $p_password) {
        $total = 0;
        $passwordHistory = $this->userModel->getPasswordHistory($p_user_id, $p_email);
    
        foreach ($passwordHistory as $history) {
            $password = $this->securityModel->decryptData($history['password']);
    
            if ($password === $p_password) {
                $total++;
            }
        }
    
        return $total;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: password_expiry_date
    # Description: 
    # Checks if the user's password has expired based on the provided user ID.
    #
    # Parameters: 
    # - $userID (array): The user ID.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    private function password_expiry_date($userID) {
        $user = $this->userModel->getUserByID($userID);
        
        if ($user && $user['password_expiry_date']) {
            $expiryDate = strtotime($user['password_expiry_date']);
            $currentDate = strtotime(date('Y-m-d'));
            
            if ($currentDate > $expiryDate) {
                return true;
            }
        }
        
        return false;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: linkUserAccountToContact
    # Description:
    # Link the selected user account to contact.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function linkUserAccountToContact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
        $contactID = htmlspecialchars($_POST['contact_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->userModel->linkUserAccountToContact($contactID, $userAccountID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: unlinkUserAccountToContact
    # Description:
    # Unlink the selected user account to contact.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function unlinkUserAccountToContact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUserIDExist = $this->userModel->checkUserIDExist($userAccountID);
        $total = $checkUserIDExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->userModel->unlinkUserAccountToContact($userAccountID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateToken
    # Description: 
    # Generates a random token/OTP (One-Time Password) of specified length.
    #
    # Parameters: 
    # - $minLength (int): The minimum length of the generated token. Default is 4.
    # - $maxLength (int): The maximum length of the generated token. Default is 4.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function generateToken($minLength = 4, $maxLength = 4) {
        $length = mt_rand($minLength, $maxLength);
        $otp = random_int(pow(10, $length - 1), pow(10, $length) - 1);
        
        return (string) $otp;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePassword
    # Description: 
    # Generates a random password of specified length.
    #
    # Parameters: 
    # - $minLength (int): The minimum length of the generated token. Default is 8.
    # - $maxLength (int): The maximum length of the generated token. Default is 8.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function generatePassword($minLength = 8, $maxLength = 8) {
        $length = mt_rand($minLength, $maxLength);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+';
        $characterCount = strlen($characters);
        $password = '';
    
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $characterCount - 1)];
        }
    
        return $password;
    }
    
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: generateResetToken
    # Description: 
    # Generates a random reset token of specified length.
    #
    # Parameters: 
    # - $minLength (int): The minimum length of the generated token. Default is 10.
    # - $maxLength (int): The maximum length of the generated token. Default is 12.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function generateResetToken($minLength = 10, $maxLength = 12) {
        $length = mt_rand($minLength, $maxLength);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        
        $resetToken = substr(str_shuffle($characters), 0, $length);
        
        return $resetToken;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Send methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: sendOTP
    # Description: 
    # Sends an OTP (One-Time Password) to the user's email address.
    #
    # Parameters: 
    # - $email (string): The email address of the user.
    # - $otp (string): The OTP generated.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function sendOTP($email, $otp) {
        $emailSetting = $this->emailSettingModel->getEmailSetting(1);
        $mailFromName = $emailSetting['mail_from_name'] ?? null;
        $mailFromEmail = $emailSetting['mail_from_email'] ?? null;

        $notificationSettingDetails = $this->notificationSettingModel->getNotificationSetting(1);
        $emailSubject = $notificationSettingDetails['email_notification_subject'] ?? null;
        $emailBody = $notificationSettingDetails['email_notification_body'] ?? null;
        $emailBody = str_replace('{OTP_CODE}', $otp, $emailBody);

        $message = file_get_contents('../email-template/default-email.html');
        $message = str_replace('{EMAIL_SUBJECT}', $emailSubject, $message);
        $message = str_replace('{EMAIL_CONTENT}', $emailBody, $message);
    
        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        $this->configureSMTP($mailer);
        
        $mailer->setFrom($mailFromEmail, $mailFromName);
        $mailer->addAddress($email);
        $mailer->Subject = $emailSubject;
        $mailer->Body = $message;
    
        if ($mailer->send()) {
            return true;
        }
        else {
            return 'Failed to send OTP. Error: ' . $mailer->ErrorInfo;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: sendPasswordReset
    # Description: 
    # Sends a password reset email containing a password reset link to the user's email address.
    #
    # Parameters: 
    # - $email (string): The email address of the user.
    # - $userID (int): The user ID.
    # - $resetToken (string): The reset token generated.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function sendPasswordReset($email, $userID, $resetToken) {
        $emailSetting = $this->emailSettingModel->getEmailSetting(1);
        $mailFromName = $emailSetting['mail_from_name'];
        $mailFromEmail = $emailSetting['mail_from_email'];

        $systemSettingDetails = $this->systemSettingModel->getSystemSetting(3);
        $defaultForgotPasswordLink = $systemSettingDetails['value'];

        $notificationSettingDetails = $this->notificationSettingModel->getNotificationSetting(2);
        $emailSubject = $notificationSettingDetails['email_notification_subject'] ?? null;
        $emailBody = $notificationSettingDetails['email_notification_body'] ?? null;
        $emailBody = str_replace('{RESET_LINK}', $defaultForgotPasswordLink . $userID .'&token=' . $resetToken, $emailBody);

        $message = file_get_contents('../email-template/default-email.html');
        $message = str_replace('{EMAIL_SUBJECT}', $emailSubject, $message);
        $message = str_replace('{EMAIL_CONTENT}', $emailBody, $message);
    
        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        $this->configureSMTP($mailer);
        
        $mailer->setFrom($mailFromEmail, $mailFromName);
        $mailer->addAddress($email);
        $mailer->Subject = $emailSubject;
        $mailer->Body = $message;
    
        if ($mailer->send()) {
            return true;
        } 
        else {
            return 'Failed to send password reset email. Error: ' . $mailer->ErrorInfo;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Configure methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: configureSMTP
    # Description: 
    # Sets the SMTP configuration
    #
    # Parameters: 
    # - $mailer (array): The PHP mailer.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    private function configureSMTP($mailer, $isHTML = true) {
        $emailSetting = $this->emailSettingModel->getEmailSetting(1);
        $mailHost = $emailSetting['mail_host'] ?? MAIL_HOST;
        $smtpAuth = empty($emailSetting['smtp_auth']) ? false : true;
        $mailUsername = $emailSetting['mail_username'] ?? MAIL_USERNAME;
        $mailPassword = !empty($password) ? $this->securityModel->decryptData($emailSetting['mail_password']) : MAIL_PASSWORD;
        $mailEncryption = $emailSetting['mail_encryption'] ?? MAIL_SMTP_SECURE;
        $port = $emailSetting['port'] ?? MAIL_PORT;
        
        $mailer->isSMTP();
        $mailer->isHTML(true);
        $mailer->Host = $mailHost;
        $mailer->SMTPAuth = $smtpAuth;
        $mailer->Username = $mailUsername;
        $mailer->Password = $mailPassword;
        $mailer->SMTPSecure = $mailEncryption;
        $mailer->Port = $port;
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/email-setting-model.php';
require_once '../model/employee-model.php';
require_once '../model/notification-setting-model.php';
require_once '../model/file-extension-model.php';
require '../assets/libs/PHPMailer/src/PHPMailer.php';
require '../assets/libs/PHPMailer/src/Exception.php';
require '../assets/libs/PHPMailer/src/SMTP.php';

$controller = new UserController(new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new EmailSettingModel(new DatabaseModel), new NotificationSettingModel(new DatabaseModel), new EmployeeModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>