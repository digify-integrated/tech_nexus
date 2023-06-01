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
                        $this->userModel->updateAccountLock($user['id'], 0, null);
                    }
                }
    
                if (password_verify($password, $user['password'])) {
                    $this->userModel->updateLoginAttempt($user['id'], 0, null);
    
                    if ($user['two_factor_auth']) {
                        $otp = generateOTP();
                        sendOTP($user['email'], $otp);
    
                        header("Location: otp_verification.php");
                        exit;
                    } 
                    else {
                        $ipAddress = $_SERVER['REMOTE_ADDR'];
                        $location = ''; // Retrieve location data based on IP address
                        $connectionDate = date('Y-m-d H:i:s');
                        $this->userModel->updateLastConnection($user['id'], $ipAddress, $location, $connectionDate);
    
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
                    $this->userModel->updateLoginAttempt($user['id'], $failedAttempts, $lastFailedLogin);
    
                    if ($failedAttempts >= MAX_FAILED_LOGIN_ATTEMPTS) {
                        $lockDuration = $user['account_lock_duration'] + ($failedAttempts - MAX_FAILED_LOGIN_ATTEMPTS + 2);
                        $this->userModel->updateAccountLock($user['id'], 1, $lockDuration);
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