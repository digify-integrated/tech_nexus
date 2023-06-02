<?php

class UserModel {
    public $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getUserByEmail($p_email) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByEmail(:p_email)");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function saveOTP($p_user_id, $p_otp, $p_otp_expiry_date) {
        $stmt = $this->db->getConnection()->prepare("CALL saveOTP(:p_user_id, :p_otp, :p_otp_expiry_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_otp', $p_otp);
        $stmt->bindParam(':p_otp_expiry_date', $p_otp_expiry_date);
        $stmt->execute();
    }

    public function generateOTP($p_otp_length = 6) {
        $unique_otp = false;    
        $max = pow(10, $p_otp_length) - 1;
    
        $random_number = mt_rand(0, $max);
    
        $otp = str_pad($random_number, $p_otp_length, '0', STR_PAD_LEFT);
    
        return $otp;
    }
    
    public function sendOTP($email, $otp) {
        require('./assets/libs/PHPMailer/src/PHPMailer.php');
        require('./assets/libs/PHPMailer/src/Exception.php');
        require('./assets/libs/PHPMailer/src/SMTP.php');

        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        
        $mailer->isSMTP();
        $mailer->Host = 'smtp.hostinger.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'encore-noreply@encorefinancials.com';
        $mailer->Password = 'P@ssw0rd';
        $mailer->SMTPSecure = 'ssl';
        $mailer->Port = 465;
        
        $mailer->setFrom('encore-noreply@encorefinancials.com', 'EIS');
        $mailer->addAddress($email);
        $mailer->Subject = 'One-Time Password (OTP) Verification';
        $mailer->Body = 'Your OTP: ' . $otp;
    
        if ($mailer->send()) {
            return true;
        }
        else {
            return 'Failed to send OTP. Error: ' . $mailer->ErrorInfo;
        }
    }
}
?>