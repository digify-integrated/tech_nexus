<?php
/**
* Class UserModel
*
* The UserModel class handles user-related operations and interactions.
*/
class UserModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUserByEmail
    # Description: Retrieves the details of a user based on their email address.
    #
    # Parameters:
    # - $p_email (string): The email address of the user.
    #
    # Returns:
    # - An array containing the user details.
    #
    # -------------------------------------------------------------
    public function getUserByEmail($p_email) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByEmail(:p_email)");
        $stmt->bindParam(':p_email', $p_email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUserByID
    # Description: Retrieves the details of a user based on their user ID.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    #
    # Returns:
    # - An array containing the user details.
    #
    # -------------------------------------------------------------
    public function getUserByID($p_user_id) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByID(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUserByRememberToken
    # Description: Retrieves the details of a user based on their remember token.
    #
    # Parameters:
    # - $p_remember_token (string): The remember token associated with the user.
    #
    # Returns:
    # - An array containing the user details.
    #
    # -------------------------------------------------------------
    public function getUserByRememberToken($p_remember_token) {
        $stmt = $this->db->getConnection()->prepare("CALL getUserByRememberToken(:p_remember_token)");
        $stmt->bindParam(':p_remember_token', $p_remember_token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPasswordHistory
    # Description: Retrieves the password history of a user based on their user ID and email.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_email (string): The email address of the user.
    #
    # Returns:
    # - An array containing the password history details.
    #
    # -------------------------------------------------------------
    public function getPasswordHistory($p_user_id, $p_email) {
        $stmt = $this->db->getConnection()->prepare("CALL getPasswordHistory(:p_user_id, :p_email)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email', $p_email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: getUICustomizationSetting
    # Description: Retrieves the UI customization setting for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    #
    # Returns: The UI customization setting as an associative array.
    #
    # -------------------------------------------------------------
    public function getUICustomizationSetting($p_user_id) {
        $stmt = $this->db->getConnection()->prepare("CALL getUICustomizationSetting(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateAccountLock
    # Description: Updates the account lock status and lock duration for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_is_locked (bool): The lock status (true/false).
    # - $p_lock_duration (int): The lock duration in minutes.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateAccountLock($p_user_id, $p_is_locked, $p_lock_duration) {
        $stmt = $this->db->getConnection()->prepare("CALL updateAccountLock(:p_user_id, :p_is_locked, :p_lock_duration)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_is_locked', $p_is_locked);
        $stmt->bindParam(':p_lock_duration', $p_lock_duration);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLoginAttempt
    # Description: Updates the login attempt details for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_failed_login_attempts (int): The number of failed login attempts.
    # - $p_last_failed_login_attempt (string): The date and time of the last failed login attempt.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLoginAttempt($p_user_id, $p_failed_login_attempts, $p_last_failed_login_attempt) {
        $stmt = $this->db->getConnection()->prepare("CALL updateLoginAttempt(:p_user_id, :p_failed_login_attempts, :p_last_failed_login_attempt)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_failed_login_attempts', $p_failed_login_attempts);
        $stmt->bindParam(':p_last_failed_login_attempt', $p_last_failed_login_attempt);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLastConnection
    # Description: Updates the last connection date for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_last_connection_date (string): The date and time of the last connection.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLastConnection($p_user_id, $p_last_connection_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateLastConnection(:p_user_id, :p_last_connection_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_last_connection_date', $p_last_connection_date);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateRememberToken
    # Description: Updates the remember token for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_remember_token (string): The new remember token.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateRememberToken($p_user_id, $p_remember_token) {
        $stmt = $this->db->getConnection()->prepare("CALL updateRememberToken(:p_user_id, :p_remember_token)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_remember_token', $p_remember_token);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateOTP
    # Description: Updates the OTP details for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_otp (string): The new OTP.
    # - $p_otp_expiry_date (string): The expiry date and time of the OTP.
    # - $p_remember_me (bool): Flag indicating whether "remember me" is enabled.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateOTP($p_user_id, $p_otp, $p_otp_expiry_date, $p_remember_me) {
        $stmt = $this->db->getConnection()->prepare("CALL updateOTP(:p_user_id, :p_otp, :p_otp_expiry_date, :p_remember_me)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_otp', $p_otp);
        $stmt->bindParam(':p_otp_expiry_date', $p_otp_expiry_date);
        $stmt->bindParam(':p_remember_me', $p_remember_me);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateFailedOTPAttempts
    # Description: Updates the number of failed OTP attempts for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_failed_otp_attempts (int): The number of failed OTP attempts.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateFailedOTPAttempts($p_user_id, $p_failed_otp_attempts) {
        $stmt = $this->db->getConnection()->prepare("CALL updateFailedOTPAttempts(:p_user_id, :p_failed_otp_attempts)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_failed_otp_attempts', $p_failed_otp_attempts);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateOTPAsExpired
    # Description: Marks the OTP as expired for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_otp_expiry_date (string): The expiry date and time of the OTP.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateOTPAsExpired($p_user_id, $p_otp_expiry_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateOTPAsExpired(:p_user_id, :p_otp_expiry_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_otp_expiry_date', $p_otp_expiry_date);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateResetToken
    # Description: Updates the reset token details for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_resetToken (string): The new reset token.
    # - $p_resetToken_expiry_date (string): The expiry date and time of the reset token.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateResetToken($p_user_id, $p_resetToken, $p_resetToken_expiry_date) {
        $stmt = $this->db->getConnection()->prepare("CALL updateResetToken(:p_user_id, :p_resetToken, :p_resetToken_expiry_date)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_resetToken', $p_resetToken);
        $stmt->bindParam(':p_resetToken_expiry_date', $p_resetToken_expiry_date);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUserPassword
    # Description: Updates the password details for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_email (string): The email address of the user.
    # - $p_password (string): The new password.
    # - $p_password_expiry_date (string): The expiry date and time of the password.
    # - $p_last_password_change (string): The date and time of the last password change.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateUserPassword($p_user_id, $p_email, $p_password, $p_password_expiry_date, $p_last_password_change) {
        $stmt = $this->db->getConnection()->prepare("CALL updateUserPassword(:p_user_id, :p_email, :p_password, :p_password_expiry_date, :p_last_password_change)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email', $p_email);
        $stmt->bindParam(':p_password', $p_password);
        $stmt->bindParam(':p_password_expiry_date', $p_password_expiry_date);
        $stmt->bindParam(':p_last_password_change', $p_last_password_change);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateNotificationSetting
    # Description: Updates the notification setting for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_receive_notification (bool): Flag indicating whether to receive notifications.
    # - $p_last_log_by (int): The ID of the user who last modified the notification setting.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateNotificationSetting($p_user_id, $p_receive_notification, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateNotificationSetting(:p_user_id, :p_receive_notification, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_receive_notification', $p_receive_notification);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTwoFactorAuthentication
    # Description: Updates the two-factor authentication setting for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_two_factor_auth (bool): Flag indicating whether two-factor authentication is enabled.
    # - $p_last_log_by (int): The ID of the user who last modified the two-factor authentication setting.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTwoFactorAuthentication($p_user_id, $p_two_factor_auth, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateTwoFactorAuthentication(:p_user_id, :p_two_factor_auth, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_two_factor_auth', $p_two_factor_auth);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUICustomizationSetting
    # Description: Updates an existing UI customization setting for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_type (string): The type of UI customization.
    # - $p_customization_value (string): The new value of the UI customization.
    # - $p_last_log_by (int): The ID of the user who last modified the UI customization setting.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateUICustomizationSetting($p_user_id, $p_type, $p_customization_value, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL updateUICustomizationSetting(:p_user_id, :p_type, :p_customization_value, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_type', $p_type);
        $stmt->bindParam(':p_customization_value', $p_customization_value);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPasswordHistory
    # Description: Inserts a new record in the password history for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_email (string): The email address of the user.
    # - $p_password (string): The password to be stored in the history.
    # - $p_last_password_change (string): The date and time of the last password change.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertPasswordHistory($p_user_id, $p_email, $p_password, $p_last_password_change) {
        $stmt = $this->db->getConnection()->prepare("CALL insertPasswordHistory(:p_user_id, :p_email, :p_password, :p_last_password_change)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_email', $p_email);
        $stmt->bindParam(':p_password', $p_password);
        $stmt->bindParam(':p_last_password_change', $p_last_password_change);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertUICustomizationSetting
    # Description: Inserts a new UI customization setting for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_type (string): The type of UI customization.
    # - $p_customization_value (string): The value of the UI customization.
    # - $p_last_log_by (int): The ID of the user who last modified the UI customization setting.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertUICustomizationSetting($p_user_id, $p_type, $p_customization_value, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare("CALL insertUICustomizationSetting(:p_user_id, :p_type, :p_customization_value, :p_last_log_by)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_type', $p_type);
        $stmt->bindParam(':p_customization_value', $p_customization_value);
        $stmt->bindParam(':p_last_log_by', $p_last_log_by);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkUICustomizationSettingExist
    # Description: Checks if a UI customization setting exists for a user.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkUICustomizationSettingExist($p_user_id) {
        $stmt = $this->db->getConnection()->prepare("CALL checkUICustomizationSettingExist(:p_user_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkMenuItemAccessRights
    # Description: Checks if a user has a access to menu item based on access type.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_menu_item_id (int): The menu item ID.
    # - $p_access_type (string): The type of access ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkMenuItemAccessRights($p_user_id, $p_menu_item_id, $p_access_type) {
        $stmt = $this->db->getConnection()->prepare("CALL checkMenuItemAccessRights(:p_user_id, :p_menu_item_id, :p_access_type)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_menu_item_id', $p_menu_item_id);
        $stmt->bindParam(':p_access_type', $p_access_type);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSystemActionAccessRights
    # Description: Checks if a user has a access to server action.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_system_action_id (int): The system action ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSystemActionAccessRights($p_user_id, $p_system_action_id) {
        $stmt = $this->db->getConnection()->prepare("CALL checkSystemActionAccessRights(:p_user_id, :p_system_action_id)");
        $stmt->bindParam(':p_user_id', $p_user_id);
        $stmt->bindParam(':p_system_action_id', $p_system_action_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>