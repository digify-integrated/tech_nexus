<?php
/**
* Class UserModel
*
* The UserModel class handles user-related operations and interactions.
*/
class UserModel {
    public $db;
    public $systemModel;

    public function __construct(DatabaseModel $db, SystemModel $systemModel) {
        $this->db = $db;
        $this->systemModel = $systemModel;
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
        $stmt = $this->db->getConnection()->prepare('CALL getUserByEmail(:p_email)');
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
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
        $stmt = $this->db->getConnection()->prepare('CALL getUserByID(:p_user_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
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
        $stmt = $this->db->getConnection()->prepare('CALL getUserByRememberToken(:p_remember_token)');
        $stmt->bindValue(':p_remember_token', $p_remember_token, PDO::PARAM_STR);
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
        $stmt = $this->db->getConnection()->prepare('CALL getPasswordHistory(:p_user_id, :p_email)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
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
        $stmt = $this->db->getConnection()->prepare('CALL getUICustomizationSetting(:p_user_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkUserExist
    # Description: Checks if a user account exists.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    # - $p_email (string): The email of the user.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkUserExist($p_user_id, $p_email = null) {
        $stmt = $this->db->getConnection()->prepare('CALL checkUserExist(:p_user_id, :p_email)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_email', $p_user_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkUserIDExist
    # Description: Checks if a user account ID exists.
    #
    # Parameters:
    # - $p_user_id (int): The user ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkUserIDExist($p_user_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkUserIDExist(:p_user_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkUserEmailExist
    # Description: Checks if a user account's email address exists.
    #
    # Parameters:
    # - $p_email (string): The email of the user.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkUserEmailExist($p_email) {
        $stmt = $this->db->getConnection()->prepare('CALL checkUserEmailExist(:p_email)');
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUserAccount
    # Description: Updates the user account.
    #
    # Parameters:
    # - $p_user_id (int): The user account ID.
    # - $p_file_as (string): The user account name.
    # - $p_email (string): The email.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateUserAccount($p_user_id, $p_file_as, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateUserAccount(:p_user_id, :p_file_as, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_file_as', $p_file_as, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
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
        $stmt = $this->db->getConnection()->prepare('CALL updateAccountLock(:p_user_id, :p_is_locked, :p_lock_duration)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_is_locked', $p_is_locked, PDO::PARAM_INT);
        $stmt->bindValue(':p_lock_duration', $p_lock_duration, PDO::PARAM_INT);
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
    # - $p_last_failed_login_attempt (datetime): The date and time of the last failed login attempt.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLoginAttempt($p_user_id, $p_failed_login_attempts, $p_last_failed_login_attempt) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLoginAttempt(:p_user_id, :p_failed_login_attempts, :p_last_failed_login_attempt)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_failed_login_attempts', $p_failed_login_attempts, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_failed_login_attempt', $p_last_failed_login_attempt, PDO::PARAM_STR);
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
    # - $p_last_connection_date (datetime): The date and time of the last connection.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLastConnection($p_user_id, $p_last_connection_date) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLastConnection(:p_user_id, :p_last_connection_date)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_connection_date', $p_last_connection_date, PDO::PARAM_STR);
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
        $stmt = $this->db->getConnection()->prepare('CALL updateRememberToken(:p_user_id, :p_remember_token)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remember_token', $p_remember_token, PDO::PARAM_STR);
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
    # - $p_otp_expiry_date (datetime): The expiry date and time of the OTP.
    # - $p_remember_me (bool): Flag indicating whether "remember me" is enabled.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateOTP($p_user_id, $p_otp, $p_otp_expiry_date, $p_remember_me) {
        $stmt = $this->db->getConnection()->prepare('CALL updateOTP(:p_user_id, :p_otp, :p_otp_expiry_date, :p_remember_me)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_otp', $p_otp, PDO::PARAM_STR);
        $stmt->bindValue(':p_otp_expiry_date', $p_otp_expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_remember_me', $p_remember_me, PDO::PARAM_INT);
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
        $stmt = $this->db->getConnection()->prepare('CALL updateFailedOTPAttempts(:p_user_id, :p_failed_otp_attempts)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_failed_otp_attempts', $p_failed_otp_attempts, PDO::PARAM_INT);
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
    # - $p_otp_expiry_date (datetime): The expiry date and time of the OTP.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateOTPAsExpired($p_user_id, $p_otp_expiry_date) {
        $stmt = $this->db->getConnection()->prepare('CALL updateOTPAsExpired(:p_user_id, :p_otp_expiry_date)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_otp_expiry_date', $p_otp_expiry_date, PDO::PARAM_STR);
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
    # - $p_resetToken_expiry_date (datetime): The expiry date and time of the reset token.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateResetToken($p_user_id, $p_resetToken, $p_resetToken_expiry_date) {
        $stmt = $this->db->getConnection()->prepare('CALL updateResetToken(:p_user_id, :p_resetToken, :p_resetToken_expiry_date)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_resetToken', $p_resetToken, PDO::PARAM_STR);
        $stmt->bindValue(':p_resetToken_expiry_date', $p_resetToken_expiry_date, PDO::PARAM_STR);
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
    # - $p_password_expiry_date (date): The expiry date of the password.
    # - $p_last_password_change (datetime): The date and time of the last password change.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateUserPassword($p_user_id, $p_email, $p_password, $p_password_expiry_date, $p_last_password_change) {
        $stmt = $this->db->getConnection()->prepare('CALL updateUserPassword(:p_user_id, :p_email, :p_password, :p_password_expiry_date, :p_last_password_change)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_password', $p_password, PDO::PARAM_STR);
        $stmt->bindValue(':p_password_expiry_date', $p_password_expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_password_change', $p_last_password_change, PDO::PARAM_STR);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUserNotificationSetting
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
    public function updateUserNotificationSetting($p_user_id, $p_receive_notification, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateUserNotificationSetting(:p_user_id, :p_receive_notification, :p_last_log_by)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_receive_notification', $p_receive_notification, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
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
        $stmt = $this->db->getConnection()->prepare('CALL updateTwoFactorAuthentication(:p_user_id, :p_two_factor_auth, :p_last_log_by)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_two_factor_auth', $p_two_factor_auth, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
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
        $stmt = $this->db->getConnection()->prepare('CALL updateUICustomizationSetting(:p_user_id, :p_type, :p_customization_value, :p_last_log_by)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_customization_value', $p_customization_value, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUserProfilePicture
    # Description: Updates the user account.
    #
    # Parameters:
    # - $p_user_id (int): The user account ID.
    # - $p_profile_picture (string): The profile picture file path.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateUserProfilePicture($p_user_id, $p_profile_picture, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateUserProfilePicture(:p_user_id, :p_profile_picture, :p_last_log_by)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_profile_picture', $p_profile_picture, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertUserAccount
    # Description: Inserts the user account.
    #
    # Parameters:
    # - $p_file_as (string): The name of the user.
    # - $p_email (string): The email.
    # - $p_password (string): The password.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertUserAccount($p_file_as, $p_email, $p_password, $p_password_expiry_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertUserAccount(:p_file_as, :p_email, :p_password, :p_password_expiry_date, :p_last_log_by, @p_user_account_id)');
        $stmt->bindValue(':p_file_as', $p_file_as, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_password', $p_password, PDO::PARAM_STR);
        $stmt->bindValue(':p_password_expiry_date', $p_password_expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_user_account_id AS user_account_id");
        $userAccountID = $result->fetch(PDO::FETCH_ASSOC)['user_account_id'];

        return $userAccountID;
    }
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
    # - $p_last_password_change (datetime): The date and time of the last password change.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertPasswordHistory($p_user_id, $p_email, $p_password, $p_last_password_change) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPasswordHistory(:p_user_id, :p_email, :p_password, :p_last_password_change)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_password', $p_password, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_password_change', $p_last_password_change, PDO::PARAM_STR);
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
        $stmt = $this->db->getConnection()->prepare('CALL insertUICustomizationSetting(:p_user_id, :p_type, :p_customization_value, :p_last_log_by)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_customization_value', $p_customization_value, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteUserAccount
    # Description: Deletes the user account.
    #
    # Parameters:
    # - $p_user_account_id (int): The user account ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteUserAccount($p_user_account_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteUserAccount(:p_user_account_id)');
        $stmt->bindValue(':p_user_account_id', $p_user_account_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteLinkedPasswordHistory
    # Description: Deletes the linked password history.
    #
    # Parameters:
    # - $p_user_account_id (int): The user account ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedPasswordHistory($p_user_account_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLinkedPasswordHistory(:p_user_account_id)');
        $stmt->bindValue(':p_user_account_id', $p_user_account_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteLinkedUICustomization
    # Description: Deletes the linked UI customization.
    #
    # Parameters:
    # - $p_user_account_id (int): The user account ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedUICustomization($p_user_account_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLinkedUICustomization(:p_user_account_id)');
        $stmt->bindValue(':p_user_account_id', $p_user_account_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Activate methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: activateUserAccount
    # Description: Activates the user account.
    #
    # Parameters:
    # - $p_user_account_id (int): The user account ID.
    # - $p_last_log_by (int): The ID of the user who last modified the user account.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function activateUserAccount($p_user_account_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL activateUserAccount(:p_user_account_id, :p_last_log_by)');
        $stmt->bindValue(':p_user_account_id', $p_user_account_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Deactivate methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deactivateUserAccount
    # Description: Deactivate the user account.
    #
    # Parameters:
    # - $p_user_account_id (int): The user account ID.
    # - $p_last_log_by (int): The ID of the user who last modified the user account.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deactivateUserAccount($p_user_account_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL deactivateUserAccount(:p_user_account_id, :p_last_log_by)');
        $stmt->bindValue(':p_user_account_id', $p_user_account_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Lock methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: lockUserAccount
    # Description: Locks the user account.
    #
    # Parameters:
    # - $p_user_account_id (int): The user account ID.
    # - $p_last_log_by (int): The ID of the user who last modified the user account.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function lockUserAccount($p_user_account_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL lockUserAccount(:p_user_account_id, :p_last_log_by)');
        $stmt->bindValue(':p_user_account_id', $p_user_account_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Unlock methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: unlockUserAccount
    # Description: Unlock the user account.
    #
    # Parameters:
    # - $p_user_account_id (int): The user account ID.
    # - $p_last_log_by (int): The ID of the user who last modified the user account.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function unlockUserAccount($p_user_account_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL unlockUserAccount(:p_user_account_id, :p_last_log_by)');
        $stmt->bindValue(':p_user_account_id', $p_user_account_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
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
        $stmt = $this->db->getConnection()->prepare('CALL checkUICustomizationSettingExist(:p_user_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
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
        $stmt = $this->db->getConnection()->prepare('CALL checkMenuItemAccessRights(:p_user_id, :p_menu_item_id, :p_access_type)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_menu_item_id', $p_menu_item_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_access_type', $p_access_type, PDO::PARAM_STR);
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
        $stmt = $this->db->getConnection()->prepare('CALL checkSystemActionAccessRights(:p_user_id, :p_system_action_id)');
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_system_action_id', $p_system_action_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateLogNotes
    # Description: Retrieves the log notes.
    #
    # Parameters:
    # - $p_table_name (string): The table name.
    # - $p_reference_id (int): The reference inside the table.
    #
    # Returns: The log notes as an associative array.
    #
    # -------------------------------------------------------------
    public function generateLogNotes($p_table_name, $p_reference_id) {
        $stmt = $this->db->getConnection()->prepare('CALL generateLogNotes(:p_table_name, :p_reference_id)');
        $stmt->bindValue(':p_table_name', $p_table_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_id', $p_reference_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $count = count($options);
        
        $htmlLogNotes = '';
        if ($count > 0) {
            $htmlLogNotes .= '<div class="comment-block">';
        
            foreach ($options as $row) {
                $log = $row['log'];
                $timeElapsed = $this->systemModel->timeElapsedString($row['changed_at']);
        
                $getUserByID = $this->getUserByID($row['changed_by']);
                $fileAs = $getUserByID['file_as'] ?? 'Nexus Bot';
                $profilePicture = $this->systemModel->checkImage($getUserByID['profile_picture'] ?? null, 'profile');
        
                $htmlLogNotes .= '<div class="comment">
                                        <div class="media align-items-start">
                                            <div class="chat-avtar flex-shrink-0">
                                                <img class="rounded-circle img-fluid wid-40 hei-40" src="'. $profilePicture .'" alt="User image" />
                                            </div>
                                            <div class="media-body ms-3">
                                                <h5 class="mb-0">'. $fileAs .'</h5>
                                                <span class="text-sm text-muted">'. $timeElapsed .'</span>
                                            </div>
                                        </div>
                                        <div class="comment-content">
                                            <p class="mb-0">
                                                '. $log .'
                                            </p>
                                        </div>
                                    </div>';
            }
        
            $htmlLogNotes .= '</div>';
        }
        else {
            $htmlLogNotes = '<div class="alert alert-secondary" role="alert">No log notes found.</div>';
        }
        
        return $htmlLogNotes;
    }
    # -------------------------------------------------------------
}
?>