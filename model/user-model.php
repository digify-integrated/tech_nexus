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

    public function getUserByRememberToken($p_remember_token) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByRememberToken(:p_remember_token)");
        $stmt->bindParam(':p_remember_token', $p_remember_token);
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
        $stmt = $this->db->getConnection()->prepare("CALL updateLastConnection(:p_user_id, :p_last_connection_date)");
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

    public function updateOTP($p_user_id, $p_otp, $p_otp_expiry_date, $p_remember_me) {
        $stmt = $this->db->getConnection()->prepare("CALL updateOTP(:p_user_id, :p_otp, :p_otp_expiry_date, :p_remember_me)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_otp', $p_otp);
        $stmt->bindParam(':p_otp_expiry_date', $p_otp_expiry_date);
        $stmt->bindParam(':p_remember_me', $p_remember_me);
        $stmt->execute();
    }

    public function updateFailedOTPAttempts($p_user_id, $p_failed_otp_attempts) {
        $stmt = $this->db->getConnection()->prepare("CALL updateFailedOTPAttempts(:p_user_id, :p_failed_otp_attempts)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_failed_otp_attempts', $p_failed_otp_attempts);
        $stmt->execute();
    }

    public function updateOTPAsExpired($p_user_id, $p_otp_expiry_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateOTPAsExpired(:p_user_id, :p_otp_expiry_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
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

    public function updateUserPassword($p_user_id, $p_email, $p_password, $p_password_expiry_date, $p_last_password_change) {
        $stmt = $this->db->getConnection()->prepare("CALL updateUserPassword(:p_user_id, :p_email, :p_password, :p_password_expiry_date, :p_last_password_change)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email', $p_email);
        $stmt->bindParam(':p_password', $p_password);
        $stmt->bindParam(':p_password_expiry_date', $p_password_expiry_date);
        $stmt->bindParam(':p_last_password_change', $p_last_password_change);
        $stmt->execute();
    }    

    public function updateNotificationSetting($p_user_id, $p_receive_notification, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateNotificationSetting(:p_user_id, :p_receive_notification, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_receive_notification', $p_receive_notification);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }

    public function updateTwoFactorAuthentication($p_user_id, $p_two_factor_auth, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateTwoFactorAuthentication(:p_user_id, :p_two_factor_auth, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_two_factor_auth', $p_two_factor_auth);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
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

    public function checkUICustomizationSettingExist($p_user_id) {
        $stmt = $this->db->getConnection()->prepare("CALL checkUICustomizationSettingExist(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertUICustomizationSetting($p_user_id, $p_type, $p_customization_value, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL insertUICustomizationSetting(:p_user_id, :p_type, :p_customization_value, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_type', $p_type);
        $stmt->bindParam(':p_customization_value', $p_customization_value);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }

    public function updateUICustomizationSetting($p_user_id, $p_type, $p_customization_value, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateUICustomizationSetting(:p_user_id, :p_type, :p_customization_value, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_type', $p_type);
        $stmt->bindParam(':p_customization_value', $p_customization_value);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }

    public function getUICustomizationSetting($p_user_id) {
        $stmt = $this->db->getConnection()->prepare("CALL getUICustomizationSetting(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>