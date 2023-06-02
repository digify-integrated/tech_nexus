<?php
class UserController {
    private $userModel;

    public function __construct(UserModel $userModel) {
        $this->userModel = $userModel;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $rememberMe = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;
    
            $user = $this->userModel->getUserByEmail($email);
    
            if ($user) {
                if ($user['is_locked']) {
                    $lockDuration = $user['account_lock_duration'];
                    $lastFailedLogin = strtotime($user['last_failed_login_attempt']);
                    $unlockTime = strtotime("+$lockDuration minutes", $lastFailedLogin);
    
                    if (time() < $unlockTime) {
                        $remainingTime = round(($unlockTime - time()) / 60);
                        echo "Your account is locked. Please try again in $remainingTime minutes.";
                        exit;
                    }
                    else {
                        $this->userModel->updateAccountLock($user['user_id'], 0, null);
                    }
                }
    
                if (password_verify($password, $user['password'])) {
                    $this->userModel->updateLoginAttempt($user['user_id'], 0, null);
    
                    if ($user['two_factor_auth']) {
                        $otp = $this->userModel->generateOTP();

                        $hashedOTP = password_hash($otp, PASSWORD_DEFAULT);
                        $otpExpiryDate = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                        $this->userModel->saveOTP($user['user_id'], $hashedOTP, $otpExpiryDate);
    
                        header("Location: otp_verification.php");
                        exit;
                    } 
                    else {
                        $connectionDate = date('Y-m-d H:i:s');

                        $this->userModel->updateLastConnection($user['user_id'], $connectionDate);
    
                        if ($rememberMe) {
                            $rememberToken = bin2hex(random_bytes(16));
                            setcookie('remember_token', $rememberToken, time() + (30 * 24 * 60 * 60), '/');
                        }
    
                        header("Location: dashboard.php");
                        exit;
                    }
                }
                else {
                    $failedAttempts = $user['failed_login_attempts'] + 1;
                    $lastFailedLogin = date('Y-m-d H:i:s');

                    $this->userModel->updateLoginAttempt($user['user_id'], $failedAttempts, $lastFailedLogin);
    
                    if ($failedAttempts >= MAX_FAILED_LOGIN_ATTEMPTS) {
                        $lockDuration = $user['account_lock_duration'] + ($failedAttempts + 1);
                        $this->userModel->updateAccountLock($user['user_id'], 1, $lockDuration);
                        echo "Maximum number of failed login attempts reached. Your account has been locked for $lockDuration minutes.";
                        exit;
                    }
    
                    echo "Invalid email or password.";
                    exit;
                }
            } 
            else {
                echo "Invalid email or password.";
                exit;
            }
        }
    }
}
?>