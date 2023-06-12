<?php

class UserModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    public function getUserByEmail($p_email) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByEmail(:p_email)");
        $stmt->bindParam(':p_email', $p_email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByID($p_user_id) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByID(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPasswordHistory($p_user_id, $p_email) {
        $stmt = $this->db->getConnection()->prepare("CALL getPasswordHistory(:p_user_id, :p_email)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email', $p_email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAccountLock($p_user_id, $p_is_locked, $p_lock_duration) {
        $stmt = $this->db->getConnection()->prepare("CALL updateAccountLock(:p_user_id, :p_is_locked, :p_lock_duration)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_is_locked', $p_is_locked);
        $stmt->bindParam(':p_lock_duration', $p_lock_duration);
        $stmt->execute();
    }

    public function updateLoginAttempt($p_user_id, $p_failed_login_attempts, $p_last_failed_login_attempt) {
        $stmt = $this->db->getConnection()->prepare("CALL updateLoginAttempt(:p_user_id, :p_failed_login_attempts, :p_last_failed_login_attempt)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_failed_login_attempts', $p_failed_login_attempts);
        $stmt->bindParam(':p_last_failed_login_attempt', $p_last_failed_login_attempt);
        $stmt->execute();
    }

    public function updateLastConnection($p_user_id, $p_last_connection_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateLoginAttempt(:p_user_id, :p_last_connection_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_last_connection_date', $p_last_connection_date);
        $stmt->execute();
    }

    public function updateRememberToken($p_user_id, $p_remember_token) {
        $stmt = $this->db->getConnection()->prepare("CALL updateRememberToken(:p_user_id, :p_remember_token)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_remember_token', $p_remember_token);
        $stmt->execute();
    }

    public function updateOTP($p_user_id, $p_otp, $p_otp_expiry_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateOTP(:p_user_id, :p_otp, :p_otp_expiry_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_otp', $p_otp);
        $stmt->bindParam(':p_otp_expiry_date', $p_otp_expiry_date);
        $stmt->execute();
    }

    public function updateResetToken($p_user_id, $p_resetToken, $p_resetToken_expiry_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateResetToken(:p_user_id, :p_resetToken, :p_resetToken_expiry_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_resetToken', $p_resetToken);
        $stmt->bindParam(':p_resetToken_expiry_date', $p_resetToken_expiry_date);
        $stmt->execute();
    }

    public function updateEmailResetToken($p_user_id, $p_email_verificationToken, $p_email_verificationToken_expiry_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateEmailResetToken(:p_user_id, :p_email_verificationToken, :p_email_verificationToken_expiry_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email_verificationToken', $p_email_verificationToken);
        $stmt->bindParam(':p_email_verificationToken_expiry_date', $p_email_verificationToken_expiry_date);
        $stmt->execute();
    }

    public function updateEmaiVerificationStatus($p_user_id, $p_email_verified_at) {
        $stmt = $this->db->getConnection()->prepare("CALL updateEmaiVerificationStatus(:p_user_id, :p_email_verified_at)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email_verified_at', $p_email_verified_at);
        $stmt->execute();
    }

    public function updateUserPassword($p_user_id, $p_email, $p_password, $p_password_expiry_date, $p_last_password_change) {
        $stmt = $this->db->getConnection()->prepare("CALL updateUserPassword(:p_user_id, :p_email, :p_password, :p_password_expiry_date, :p_last_password_change)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email', $p_email);
        $stmt->bindParam(':p_password', $p_password);
        $stmt->bindParam(':p_password_expiry_date', $p_password_expiry_date);
        $stmt->bindParam(':p_last_password_change', $p_last_password_change);
        $stmt->execute();
    }

    public function insertPasswordHistory($p_user_id, $p_email, $p_password, $p_last_password_change) {
        $stmt = $this->db->getConnection()->prepare("CALL insertPasswordHistory(:p_user_id, :p_email, :p_password, :p_last_password_change)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email', $p_email);
        $stmt->bindParam(':p_password', $p_password);
        $stmt->bindParam(':p_last_password_change', $p_last_password_change);
        $stmt->execute();
    }

    public function generateOTP($minLength = 6, $maxLength = 8) {
        $length = mt_rand($minLength, $maxLength);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $character_count = strlen($characters);
        $otp = '';
    
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $character_count - 1);
            $otp .= $characters[$index];
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
    
    public function generateEmailVerificationToken($minLength = 10, $maxLength = 12) {
        $length = mt_rand($minLength, $maxLength);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $character_count = strlen($characters);
        $verificationToken = '';
    
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $character_count - 1);
            $verificationToken .= $characters[$index];
        }
    
        return $verificationToken;
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
        $mailer->Subject = 'EIS | One-Time Password (OTP)';
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
            return 'Failed to send OTP. Error: ' . $mailer->ErrorInfo;
        }
    }
    
    public function sendEmailVerification($email, $verificationToken) {
        require('../assets/libs/PHPMailer/src/PHPMailer.php');
        require('../assets/libs/PHPMailer/src/Exception.php');
        require('../assets/libs/PHPMailer/src/SMTP.php');

        $message = file_get_contents('../email-template/email-verification.html');
        $message = str_replace('[VERIFICATION CODE]', $verificationToken, $message);

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
        $mailer->Subject = 'Complete Your Email Address Registration - Action Required';
        $mailer->Body = $message;
    
        if ($mailer->send()) {
            return true;
        }
        else {
            return 'Failed to send OTP. Error: ' . $mailer->ErrorInfo;
        }
    }
}
?>