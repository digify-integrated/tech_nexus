<?php

class UserModel {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getUserByEmail($p_email) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByEmail(:p_email)");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAccountLock($p_email, $p_is_locked, $p_lock_duration) {
        $stmt = $this->db->getConnection()->prepare("CALL updateAccountLock(:p_email, :p_is_locked, :p_lock_duration)");
        $stmt->bindParam(':p_email', $p_email);
        $stmt->bindParam(':p_is_locked', $p_is_locked);
        $stmt->bindParam(':p_lock_duration', $p_lock_duration);
        $stmt->execute();
    }

    public function updateLoginAttempt($userId, $p_failed_login_attempts, $p_last_failed_login_attempt) {
        $stmt = $this->db->getConnection()->prepare("CALL updateLoginAttempt(:p_email, :p_failed_login_attempts, :p_last_failed_login_attempt)");
        $stmt->bindParam(':p_email', $p_email);
        $stmt->bindParam(':p_failed_login_attempts', $p_failed_login_attempts);
        $stmt->bindParam(':p_last_failed_login_attempt', $p_last_failed_login_attempt);
        $stmt->execute();
    }

    public function updateLastConnection($userId, $ipAddress, $location, $connectionDate) {\
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':ip_address', $ipAddress);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':connection_date', $connectionDate);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }

    public function generateOTP($p_otp_length) {
        $unique_otp = false;
        $otp = '';
    
        while (!$unique_otp) {
            $random_bytes = random_bytes($p_otp_length);

            $otp = bin2hex($random_bytes);
    
            $otp = substr($otp, 0, $p_otp_length);
    
            $unique_otp = $this->isOTPUnique($otp);
        }
    
        return $otp;
    }
    
    public function sendOTP($email, $otp) {
        require 'vendor/autoload.php';

        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        
        $mailer->isSMTP();
        $mailer->Host = 'smtp.example.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'your_email@example.com';
        $mailer->Password = 'your_password';
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = 587;
        
        $mailer->setFrom('noreply@example.com', 'Your App');
        $mailer->addAddress($email);
        $mailer->Subject = 'One-Time Password (OTP) Verification';
        $mailer->Body = 'Your OTP: ' . $otp;
    
        if ($mailer->send()) {
            echo 'OTP sent successfully.';
        } else {
            echo 'Failed to send OTP. Error: ' . $mailer->ErrorInfo;
        }
    }
}
?>