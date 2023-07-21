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
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(UserModel $userModel, SecurityModel $securityModel) {
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
                    $this->updateNotificationSetting();
                    break;
                case 'update two factor authentication':
                    $this->updateTwoFactorAuthentication();
                    break;
                case 'change password shortcut':
                    $this->updatePasswordShortcut();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
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
    
        if ($password !== $userPassword) {
            $this->handleInvalidCredentials($user);
            return;
        }
    
        if (!$user || !$user['is_active']) {
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
    
        echo json_encode(['success' => true, 'twoFactorAuth' => false]);
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
    
        if ($failedAttempts > MAX_FAILED_LOGIN_ATTEMPTS) {
            $lockDuration = pow(2, ($failedAttempts - MAX_FAILED_LOGIN_ATTEMPTS)) * 5;
            $this->userModel->updateAccountLock($userID, 1, $lockDuration);
            
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
    # Function: authenticate
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
            if ($failedOTPAttempts >= MAX_FAILED_OTP_ATTEMPTS) {
                $otpExpiryDate = date('Y-m-d H:i:s', strtotime('-1 month'));
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
        echo json_encode(['success' => true]);
        exit;
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
                'themeContrast' => $uiCustomizationSetting['theme_contrast'] ?? false,
                'captionShow' => $uiCustomizationSetting['caption_show'] ?? true,
                'presetTheme' => $uiCustomizationSetting['preset_theme'] ?? 'preset-1',
                'darkLayout' => $uiCustomizationSetting['dark_layout'] ?? false,
                'rtlLayout' => $uiCustomizationSetting['rtl_layout'] ?? false,
                'boxContainer' => $uiCustomizationSetting['box_container'] ?? false
            ];

            echo json_encode($response);
            exit;
        }
        else {
            $response = [
                'success' => true,
                'themeContrast' => false,
                'captionShow' => true,
                'presetTheme' => 'preset-1',
                'darkLayout' => false,
                'rtlLayout' => false,
                'boxContainer' => false
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateNotificationSetting
    # Description: 
    # Handles the update of the notification setting based on the provided user ID and checked state.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateNotificationSetting() {
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
    
        $this->userModel->updateNotificationSetting($userID, $isChecked, $userID);
    
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
        $message = file_get_contents('../email-template/otp-email.html');
        $message = str_replace('[OTP CODE]', $otp, $message);
    
        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        $this->configureSMTP($mailer);
        
        $mailer->setFrom('encore-noreply@encorefinancials.com', 'Encore Integrated Systems');
        $mailer->addAddress($email);
        $mailer->Subject = 'Login OTP - Secure Access to Your Account';
        $mailer->Body = $message;
    
        try {
            if ($mailer->send()) {
                return true;
            }
            else {
                return 'Failed to send OTP. Error: ' . $mailer->ErrorInfo;
            }
        }
        catch (Exception $e) {
            return 'Failed to send OTP. Error: ' . $e->getMessage();
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
        $message = file_get_contents('../email-template/password-reset-email.html');
        $message = str_replace('[RESET LINK]', 'http://localhost/tech_nexus/password-reset.php?id=' . $userID .'&token=' . $resetToken, $message);
    
        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        $this->configureSMTP($mailer);
        
        $mailer->setFrom('encore-noreply@encorefinancials.com', 'Encore Integrated Systems');
        $mailer->addAddress($email);
        $mailer->Subject = 'Password Reset Request - Action Required';
        $mailer->Body = $message;
    
        try {
            if ($mailer->send()) {
                return true;
            } 
            else {
                return 'Failed to send password reset email. Error: ' . $mailer->ErrorInfo;
            }
        } 
        catch (Exception $e) {
            return 'Failed to send password reset email. Error: ' . $e->getMessage();
        }
    }
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
    private function configureSMTP($mailer) {
        $mailer->isSMTP();
        $mailer->isHTML(true);
        $mailer->Host = MAIL_HOST;
        $mailer->SMTPAuth = MAIL_SMTP_AUTH;
        $mailer->Username = MAIL_USERNAME;
        $mailer->Password = MAIL_PASSWORD;
        $mailer->SMTPSecure = MAIL_SMTP_SECURE;
        $mailer->Port = MAIL_PORT;
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require '../assets/libs/PHPMailer/src/PHPMailer.php';
require '../assets/libs/PHPMailer/src/Exception.php';
require '../assets/libs/PHPMailer/src/SMTP.php';

$controller = new UserController(new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>