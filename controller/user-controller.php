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
                case 'register':
                    $this->register();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $rememberMe = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;
    
            $user = $this->userModel->getUserByEmail($email);
    
            if ($user) {
                $userID = $user['user_id'];
                $encryptedUserID = $this->securityModel->encryptData($userID);
    
                if ($user['is_locked']) {
                    $lockDuration = $user['account_lock_duration'];
                    $lastFailedLogin = strtotime($user['last_failed_login_attempt']);
                    $unlockTime = strtotime("+$lockDuration minutes", $lastFailedLogin);
    
                    if (time() < $unlockTime) {
                        $remainingTime = round(($unlockTime - time()) / 60);
                        echo json_encode(['success' => false, 'message' => "Your account is locked. Please try again in $remainingTime minutes."]);
                        exit;
                    }
                    else {
                        $this->userModel->updateAccountLock($userID, 0, null);
                    }
                }
    
                if ($this->password_expiry_date($userID)) {
    
                    echo json_encode(['success' => true, 'resetPassword' => true, 'encryptedUserID' => $encryptedUserID]);
                    exit;
                }
    
                if (password_verify($password, $user['password'])) {
                    $this->userModel->updateLoginAttempt($userID, 0, null);
    
                    if ($user['two_factor_auth']) {
                        $otp = $this->userModel->generateOTP();
    
                        $hashedOTP = password_hash($otp, PASSWORD_DEFAULT);
                        $otpExpiryDate = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
                        $this->userModel->updateOTP($userID, $hashedOTP, $otpExpiryDate);
    
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
    
                    if ($failedAttempts >= MAX_FAILED_LOGIN_ATTEMPTS) {
                        $lockDuration = $user['account_lock_duration'] + ($failedAttempts + 1);
                        $this->userModel->updateAccountLock($userID, 1, $lockDuration);
                        echo json_encode(['success' => false, 'message' => "Maximum number of failed login attempts reached. Your account has been locked for $lockDuration minutes."]);
                        exit;
                    }
    
                    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
                    exit;
                }
            } 
            else {
                echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
                exit;
            }
        }
    }
    

    private function password_expiry_date($userID) {
        $user = $this->userModel->getUserByID($userID);
        
        if ($user) {
            $expiryDate = strtotime($user['password_expiry_date']);
            $currentDate = time();
            
            return ($expiryDate && $expiryDate < $currentDate);
        }
        
        return false;
    }
}

$controller = new UserController(new UserModel());
$controller->handleRequest();
?>