<?php
session_start();

class UserController {
    private $userModel;
    private $securityModel;

    public function __construct(UserModel $userModel, SecurityModel $securityModel) {
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest() {
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

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
            $rememberMe = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;
    
            $user = $this->userModel->getUserByEmail($email);
    
            if ($user) {
                $userID = $user['user_id'];
                $userPassword = $this->securityModel->decryptData($user['password']);
                $encryptedUserID = $this->securityModel->encryptData($userID);

                if ($password === $userPassword) {
                    if (!$user['is_active']) {
                        echo json_encode(['success' => false, 'message' => 'Your account is currently inactive. Please contact the administrator for assistance.']);
                        exit;
                    }
    
                    if ($this->password_expiry_date($userID)) {
                        if(strtotime(date('Y-m-d H:i:s')) > strtotime($user['reset_token_expiry_date'])){
                            $resetToken = $this->generateResetToken();
                            $encryptedResetToken = $this->securityModel->encryptData($resetToken);
                            $resetTokenExpiryDate = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
                            $this->userModel->updateResetToken($userID, $encryptedResetToken, $resetTokenExpiryDate);
                            $this->sendPasswordReset($email, $encryptedUserID, $encryptedResetToken);
                        }

                        echo json_encode(['success' => false, 'message' => "Your password has expired. To reset your password, we have sent a password reset link to your registered email address. Please follow the instructions in the email to securely reset your password."]);
                        exit;
                    }
    
                    if ($user['is_locked']) {
                        $lockDuration = $user['account_lock_duration'];
                        $lastFailedLogin = strtotime($user['last_failed_login_attempt']);
                        $unlockTime = strtotime("+$lockDuration minutes", $lastFailedLogin);
        
                        if (time() < $unlockTime) {
                            $remainingTime = round(($unlockTime - time()) / 60);
                            echo json_encode(['success' => false, 'message' => "Your account has been locked. Please try again in $remainingTime minutes."]);
                            exit;
                        }
                        else {
                            $this->userModel->updateAccountLock($userID, 0, null);
                        }
                    }

                    $this->userModel->updateLoginAttempt($userID, 0, null);
    
                    if ($user['two_factor_auth']) {
                        $otp = $this->generateToken(6,6);
                        $encryptedOTP =  $this->securityModel->encryptData($otp);
                        $otpExpiryDate = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                        if($rememberMe){
                            $rememberMe = 1;
                        }
                        else{
                            $rememberMe = 0;
                        }
    
                        $this->userModel->updateOTP($userID, $encryptedOTP, $otpExpiryDate, $rememberMe);
                        $this->sendOTP($email, $otp);
    
                        echo json_encode(['success' => true, 'twoFactorAuth' => true, 'encryptedUserID' => $encryptedUserID]);
                        exit;
                    } 
                    else {
                        $connectionDate = date('Y-m-d H:i:s');
    
                        $this->userModel->updateLastConnection($userID, $connectionDate);
    
                        if ($rememberMe) {
                            $rememberToken = bin2hex(random_bytes(16));
                            $this->userModel->updateRememberToken($userID, $rememberToken);
                            setcookie('remember_token', $rememberToken, time() + (30 * 24 * 60 * 60), '/');
                        }
    
                        $_SESSION['user_id'] = $userID;
                        echo json_encode(['success' => true, 'twoFactorAuth' => false]);
                        exit;
                    }
                }
                else {
                    $failedAttempts = $user['failed_login_attempts'] + 1;
                    $lastFailedLogin = date('Y-m-d H:i:s');
    
                    $this->userModel->updateLoginAttempt($userID, $failedAttempts, $lastFailedLogin);
    
                    if ($failedAttempts > MAX_FAILED_LOGIN_ATTEMPTS) {
                        $lockDuration = $user['account_lock_duration'] + (($failedAttempts - MAX_FAILED_LOGIN_ATTEMPTS) + 5);
                        $this->userModel->updateAccountLock($userID, 1, $lockDuration);
                        echo json_encode(['success' => false, 'message' => "You have reached the maximum number of failed login attempts. Your account has been locked for $lockDuration minutes."]);
                        exit;
                    }
    
                    echo json_encode(['success' => false, 'message' => 'The email or password you entered is invalid. Please double-check your credentials and try again.']);
                    exit;
                }
            } 
            else {
                echo json_encode(['success' => false, 'message' => 'The email or password you entered is invalid. Please double-check your credentials and try again.']);
                exit;
            }
        }
    }

    public function passwordReset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userID = htmlspecialchars($this->securityModel->decryptData($_POST['user_id']), ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
            $encryptedPassword = $this->securityModel->encryptData($password);
    
            $user = $this->userModel->getUserByID($userID);
    
            if ($user) {
                $email = $user['email'] ?? null;
                
                if(empty($email)){
                    echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
                    exit;
                }

                $resetTokenExpiryDate = $user['reset_token_expiry_date'];

                if (strtotime(date('Y-m-d H:i:s')) > strtotime($resetTokenExpiryDate)) {
                    echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('password reset token expired')]);
                    exit;
                }
                
                $checkPasswordHistory = $this->checkPasswordHistory($userID, $email, $password);

                if($checkPasswordHistory > 0){
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
            else {
                echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
                exit;
            }
        }
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByEmail($email);
    
            if ($user) {
                $userID = $user['user_id'] ?? null;
                $encryptedUserID = $this->securityModel->encryptData($userID);

                if(empty($userID)){
                    echo json_encode(['success' => false, 'message' => 'The email address is invalid or does not exist.']);
                    exit;
                }

                if (!$user['is_active']) {
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
            else {
                echo json_encode(['success' => false, 'message' => 'The email address is invalid or does not exist.']);
                exit;
            }
        }
    }

    public function otpAuthentication() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userID = $this->securityModel->decryptData($_POST['user_id']);
            $otp = htmlspecialchars($_POST['otp'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if ($user) {
                $email = $user['email'] ?? null;
                $rememberMe = $user['remember_me'] ?? 0;

                if(empty($email)){
                    echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
                    exit;
                }

                $userOTP = $this->securityModel->decryptData($user['otp']);
                $userOTPExpiryDate = $user['otp_expiry_date'];
                $failedOPTAttempts = $user['failed_otp_attempts'];

                if($otp != $userOTP){
                    if($failedOPTAttempts >= MAX_FAILED_OTP_ATTEMPTS){
                        $otpExpiryDate = date('Y-m-d H:i:s', strtotime('-1 month'));
                        $this->userModel->updateOTPAsExpired($userID, $otpExpiryDate);
                        
                        echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid otp')]);
                        exit;
                    }
                    
                    $this->userModel->updateFailedOTPAttempts($userID, $failedOPTAttempts + 1);
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
            else {
                echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
                exit;
            }
        }
    }

    public function saveUICustomization() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $customizationValue = $_POST['customizationValue'];
            $userID = $_SESSION['user_id'];

            $user = $this->userModel->getUserByID($userID);

            if (!$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }

            $checkUICustomizationSettingExist = $this->userModel->checkUICustomizationSettingExist($userID);

            if($checkUICustomizationSettingExist['total'] > 0){
                $this->userModel->updateUICustomizationSetting($userID, $type, $customizationValue, $userID);
    
                echo json_encode(['success' => true]);
                exit;
            }
            else{
                $this->userModel->insertUICustomizationSetting($userID, $type, $customizationValue, $userID);
    
                echo json_encode(['success' => true]);
                exit;
            }
        }
    }

    public function getUICustomization() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                $userID = $_SESSION['user_id'];

                $user = $this->userModel->getUserByID($userID);
    
                if (!$user['is_active']) {
                    echo json_encode(['success' => false, 'isInactive' => true]);
                    exit;
                }
    
                $uiCustomizationSetting = $this->userModel->getUICustomizationSetting($userID);
                echo json_encode(['success' => true, 'themeContrast' => $uiCustomizationSetting['theme_contrast'] ?? false, 'captionShow' => $uiCustomizationSetting['caption_show'] ?? true, 'presetTheme' => $uiCustomizationSetting['preset_theme'] ?? 'preset-1', 'darkLayout' => $uiCustomizationSetting['dark_layout'] ?? false, 'rtlLayout' => $uiCustomizationSetting['rtl_layout'] ?? false, 'boxContainer' => $uiCustomizationSetting['box_container'] ?? false]);
                exit;
            }
            else{
                echo json_encode(['success' => true, 'themeContrast' => false, 'captionShow' => true, 'presetTheme' => 'preset-1', 'darkLayout' => false, 'rtlLayout' => false, 'boxContainer' => false]);
                exit;
            }
        }
    }

    public function updateNotificationSetting() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    }

    public function updateTwoFactorAuthentication() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    }

    public function updatePasswordShortcut() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userID = $_SESSION['user_id'];
            $oldPassword = htmlspecialchars($_POST['shortcut_old_password'], ENT_QUOTES, 'UTF-8');
            $newPassword = htmlspecialchars($_POST['shortcut_new_password'], ENT_QUOTES, 'UTF-8');
            $encryptedPassword = $this->securityModel->encryptData($newPassword);
    
            $user = $this->userModel->getUserByID($userID);
    
            if ($user) {
                $email = $user['email'] ?? null;
                $isActive = $user['is_active'] ?? 0;
                $userPassword = $this->securityModel->decryptData($user['password']);

                if (!$isActive) {
                    echo json_encode(['success' => false, 'isInactive' => true]);
                    exit;
                }

                if($oldPassword != $userPassword){
                    echo json_encode(['success' => false, 'message' => 'Your old password does not match your current password.']);
                    exit;
                }

                $checkPasswordHistory = $this->checkPasswordHistory($userID, $email, $newPassword);

                if($checkPasswordHistory > 0){
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
            else {
                echo json_encode(['success' => false, 'errorRedirect' => true, 'errorType' => $this->securityModel->encryptData('invalid user')]);
                exit;
            }
        }
    }
    
    private function checkPasswordHistory($p_user_id, $p_email, $p_password) {
        $total = 0;

        $passwordHistory = $this->userModel->getPasswordHistory($p_user_id, $p_email);

        for($i = 0; $i < count($passwordHistory); $i++) {
            $password = $this->securityModel->decryptData($passwordHistory[$i]['password']);
                    
            if($password === $p_password){
                $total = $total + 1;
            }
        }

        return (int) $total;
    }
    
    private function password_expiry_date($userID) {
        $user = $this->userModel->getUserByID($userID);
        
        if ($user && $user['password_expiry_date']) {
            $expiryDate = $user['password_expiry_date'];
            $currentDate = time();

            if(strtotime(date('Y-m-d')) > strtotime($expiryDate)){
                return true;
            }
        }
        
        return false;
    }

    public function generateToken($minLength = 4, $maxLength = 4) {
        $length = mt_rand($minLength, $maxLength);
        $otp = '';
        
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }
        
        return $otp;
    }
    
    public function generateResetToken($minLength = 10, $maxLength = 12) {
        $length = mt_rand($minLength, $maxLength);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $character_count = strlen($characters);
        $resetToken = '';
    
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $character_count - 1);
            $resetToken .= $characters[$index];
        }
    
        return $resetToken;
    }

    public function sendOTP($email, $otp) {
        require('../assets/libs/PHPMailer/src/PHPMailer.php');
        require('../assets/libs/PHPMailer/src/Exception.php');
        require('../assets/libs/PHPMailer/src/SMTP.php');

        $message = file_get_contents('../email-template/otp-email.html');
        $message = str_replace('[OTP CODE]', $otp, $message);

        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        
        $mailer->isSMTP();
        $mailer->isHTML(true);
        $mailer->Host = MAIL_HOST;
        $mailer->SMTPAuth = MAIL_SMTP_AUTH;
        $mailer->Username = MAIL_USERNAME;
        $mailer->Password = MAIL_PASSWORD;
        $mailer->SMTPSecure = MAIL_SMTP_SECURE;
        $mailer->Port = MAIL_PORT;
        
        $mailer->setFrom('encore-noreply@encorefinancials.com', 'Encore Integrated Systems');
        $mailer->addAddress($email);
        $mailer->Subject = 'Login OTP - Secure Access to Your Account';
        $mailer->Body = $message;
    
        if ($mailer->send()) {
            return true;
        }
        else {
            return 'Failed to send OTP. Error: ' . $mailer->ErrorInfo;
        }
    }
    
    public function sendPasswordReset($email, $userID, $resetToken) {
        require('../assets/libs/PHPMailer/src/PHPMailer.php');
        require('../assets/libs/PHPMailer/src/Exception.php');
        require('../assets/libs/PHPMailer/src/SMTP.php');

        $message = file_get_contents('../email-template/password-reset-email.html');
        $message = str_replace('[RESET LINK]', 'http://localhost/tech_nexus/password-reset.php?id=' . $userID .'&token=' . $resetToken, $message);

        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        
        $mailer->isSMTP();
        $mailer->isHTML(true);
        $mailer->Host = MAIL_HOST;
        $mailer->SMTPAuth = MAIL_SMTP_AUTH;
        $mailer->Username = MAIL_USERNAME;
        $mailer->Password = MAIL_PASSWORD;
        $mailer->SMTPSecure = MAIL_SMTP_SECURE;
        $mailer->Port = MAIL_PORT;
        
        $mailer->setFrom('encore-noreply@encorefinancials.com', 'Encore Integrated Systems');
        $mailer->addAddress($email);
        $mailer->Subject = 'Password Reset Request - Action Required';
        $mailer->Body = $message;
    
        if ($mailer->send()) {
            return true;
        }
        else {
            return 'Failed to send password reset email. Error: ' . $mailer->ErrorInfo;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';

$controller = new UserController(new UserModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>