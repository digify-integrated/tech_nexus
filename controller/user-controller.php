<?php
class UserController {
    private $userModel;
    private $securityModel;

    public function __construct(UserModel $userModel, SecurityModel $securityModel) {
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : '';

            switch ($transaction) {
                case 'authenticate':
                    $this->authenticate();
                    break;
                case 'verify email':
                    $this->verifyEmail();
                    break;
                case 'password reset':
                    $this->passwordReset();
                    break;
                case 'forgot password':
                    $this->forgotPassword();
                    break;
                case 'otp authentication':
                    $this->forgotPassword();
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
                    if (!$user['email_verification_status']) {
                        if (empty($user['email_verification_token']) || (!empty($user['email_verification_token']) && strtotime(date('Y-m-d H:i:s')) > strtotime($user['email_verification_token_expiry_date']))) {
                            $emailVerificationToken = $this->userModel->generateEmailVerificationToken();
                            $encryptedVerificationToken =  $this->securityModel->encryptData($emailVerificationToken);
                            $emailVerificationTokenExpiryDate = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
                            $this->userModel->updateEmailResetToken($userID, $encryptedVerificationToken, $emailVerificationTokenExpiryDate);
                            $this->userModel->sendEmailVerification($email, $emailVerificationToken);
                        }
                        
                        echo json_encode(['success' => true, 'emailVerification' => true, 'encryptedUserID' => $encryptedUserID]);
                        exit;
                    }
        
                    if (!$user['is_active']) {
                        echo json_encode(['success' => false, 'message' => 'Your account is currently inactive. Please contact the administrator for assistance.']);
                        exit;
                    }
    
                    if ($this->password_expiry_date($userID)) {
                        if(strtotime(date('Y-m-d H:i:s')) > strtotime($user['reset_token_expiry_date'])){
                            $resetToken = $this->userModel->generateResetToken();
                            $encryptedResetToken = $this->securityModel->encryptData($resetToken);
                            $resetTokenExpiryDate = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
                            $this->userModel->updateResetToken($userID, $encryptedResetToken, $resetTokenExpiryDate);
                            $this->userModel->sendPasswordReset($email, $encryptedUserID, $encryptedResetToken);
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
                        $otp = $this->userModel->generateOTP(6,6);
                        $encryptedOTP =  $this->securityModel->encryptData($otp);
                        $otpExpiryDate = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
                        $this->userModel->updateOTP($userID, $encryptedOTP, $otpExpiryDate);
                        $this->userModel->sendOTP($email, $otp);
    
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

    public function verifyEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userID = $this->securityModel->decryptData($_POST['user_id']);
            $verificationCode = htmlspecialchars($_POST['verification_code'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if ($user) {
                $email = $user['email'];
                $emailVerificationToken = $this->securityModel->decryptData($user['email_verification_token']);
                $emailVerificationTokenExpiryDate = $user['email_verification_token_expiry_date'];

                if ($user['email_verification_status']) {
                    echo json_encode(['success' => true]);
                    exit;
                }

                if($verificationCode == $emailVerificationToken){
                    if (strtotime(date('Y-m-d H:i:s')) > strtotime($emailVerificationTokenExpiryDate)) {
                        header('location: error.php?type='. $securityModel->encryptData('email verification token expired'));
                        exit;
                    }

                    $emailVerificationDate = date('Y-m-d H:i:s');
                    $this->userModel->updateEmaiVerificationStatus($userID, $emailVerificationDate);

                    echo json_encode(['success' => true]);
                    exit;
                }
                else{
                    echo json_encode(['success' => false, 'message' => 'The email verification code you entered is incorrect.']);
                    exit;
                }
            } 
            else {
                header('location: error.php?type='. $securityModel->encryptData('invalid user'));
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
                $email = $user['email'];
                $resetTokenExpiryDate = $user['reset_token_expiry_date'];

                if (strtotime(date('Y-m-d H:i:s')) > strtotime($resetTokenExpiryDate)) {
                    header('location: error.php?type='. $securityModel->encryptData('password reset token expired'));
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
                header('location: error.php?type='. $securityModel->encryptData('invalid user'));
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
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';

$controller = new UserController(new UserModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>