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

    public function updateLastConnection($userId, $ipAddress, $location, $connectionDate) {
        $query = "";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':ip_address', $ipAddress);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':connection_date', $connectionDate);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }

    public function updateTwoFactorSecret($userId, $secretKey) {
        $query = "UPDATE users SET two_factor_secret = :secret_key WHERE id = :user_id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':secret_key', $secretKey);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }
}
?>