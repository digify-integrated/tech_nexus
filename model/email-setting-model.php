<?php
/**
* Class EmailSettingModel
*
* The EmailSettingModel class handles email setting related operations and interactions.
*/
class EmailSettingModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateEmailSetting
    # Description: Updates the email setting.
    #
    # Parameters:
    # - $p_email_setting_id (int): The email setting ID.
    # - $p_email_setting_name (string): The email setting name.
    # - $p_email_setting_description (string): The email setting description.
    # - $p_mail_host (string): The mail host.
    # - $p_port (int): The port of the mail.
    # - $p_smtp_auth (int): The SMTP authentication.
    # - $p_smtp_auto_tls (int): The SMTP auto TLS.
    # - $p_mail_username (string): The mail username.
    # - $p_mail_from_email (string): The mail username.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateEmailSetting($p_email_setting_id, $p_email_setting_name, $p_email_setting_description, $p_mail_host, $p_port, $p_smtp_auth, $p_smtp_auto_tls, $p_mail_username, $p_mail_encryption, $p_mail_from_name, $p_mail_from_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateEmailSetting(:p_email_setting_id, :p_email_setting_name, :p_email_setting_description, :p_mail_host, :p_port, :p_smtp_auth, :p_smtp_auto_tls, :p_mail_username, :p_mail_encryption, :p_mail_from_name, :p_mail_from_email, :p_last_log_by)');
        $stmt->bindValue(':p_email_setting_id', $p_email_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_email_setting_name', $p_email_setting_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_email_setting_description', $p_email_setting_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_host', $p_mail_host, PDO::PARAM_STR);
        $stmt->bindValue(':p_port', $p_port, PDO::PARAM_INT);
        $stmt->bindValue(':p_smtp_auth', $p_smtp_auth, PDO::PARAM_INT);
        $stmt->bindValue(':p_smtp_auto_tls', $p_smtp_auto_tls, PDO::PARAM_INT);
        $stmt->bindValue(':p_mail_username', $p_mail_username, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_encryption', $p_mail_encryption, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_from_name', $p_mail_from_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_from_email', $p_mail_from_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateMailPassword
    # Description: Updates the email setting.
    #
    # Parameters:
    # - $p_email_setting_id (int): The email setting ID.
    # - $p_mail_password (string): The email mail password.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateMailPassword($p_email_setting_id, $p_mail_password, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateMailPassword(:p_email_setting_id, :p_mail_password, :p_last_log_by)');
        $stmt->bindValue(':p_email_setting_id', $p_email_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mail_password', $p_mail_password, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertEmailSetting
    # Description: Inserts the email setting.
    #
    # Parameters:
    # - $p_email_setting_name (string): The email setting name.
    # - $p_email_setting_description (string): The email setting description.
    # - $p_mail_host (string): The mail host.
    # - $p_port (int): The port of the mail.
    # - $p_smtp_auth (int): The SMTP authentication.
    # - $p_smtp_auto_tls (int): The SMTP auto TLS.
    # - $p_mail_username (string): The mail username.
    # - $p_mail_from_email (string): The mail username.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertEmailSetting($p_email_setting_name, $p_email_setting_description, $p_mail_host, $p_port, $p_smtp_auth, $p_smtp_auto_tls, $p_mail_username, $p_mail_encryption, $p_mail_from_name, $p_mail_from_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmailSetting(:p_email_setting_name, :p_email_setting_description, :p_mail_host, :p_port, :p_smtp_auth, :p_smtp_auto_tls, :p_mail_username, :p_mail_encryption, :p_mail_from_name, :p_mail_from_email, :p_last_log_by, @p_email_setting_id)');
        $stmt->bindValue(':p_email_setting_name', $p_email_setting_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_email_setting_description', $p_email_setting_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_host', $p_mail_host, PDO::PARAM_STR);
        $stmt->bindValue(':p_port', $p_port, PDO::PARAM_INT);
        $stmt->bindValue(':p_smtp_auth', $p_smtp_auth, PDO::PARAM_INT);
        $stmt->bindValue(':p_smtp_auto_tls', $p_smtp_auto_tls, PDO::PARAM_INT);
        $stmt->bindValue(':p_mail_username', $p_mail_username, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_encryption', $p_mail_encryption, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_from_name', $p_mail_from_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_mail_from_email', $p_mail_from_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_email_setting_id AS p_email_setting_id");
        $p_email_setting_id = $result->fetch(PDO::FETCH_ASSOC)['p_email_setting_id'];

        return $p_email_setting_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkEmailSettingExist
    # Description: Checks if a email setting exists.
    #
    # Parameters:
    # - $p_email_setting_id (int): The email setting ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkEmailSettingExist($p_email_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkEmailSettingExist(:p_email_setting_id)');
        $stmt->bindValue(':p_email_setting_id', $p_email_setting_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteEmailSetting
    # Description: Deletes the email setting.
    #
    # Parameters:
    # - $p_email_setting_id (int): The email setting ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteEmailSetting($p_email_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteEmailSetting(:p_email_setting_id)');
        $stmt->bindValue(':p_email_setting_id', $p_email_setting_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmailSetting
    # Description: Retrieves the details of a email setting.
    #
    # Parameters:
    # - $p_email_setting_id (int): The email setting ID.
    #
    # Returns:
    # - An array containing the email setting details.
    #
    # -------------------------------------------------------------
    public function getEmailSetting($p_email_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmailSetting(:p_email_setting_id)');
        $stmt->bindValue(':p_email_setting_id', $p_email_setting_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateEmailSetting
    # Description: Duplicates the email setting.
    #
    # Parameters:
    # - $p_email_setting_id (int): The email setting ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateEmailSetting($p_email_setting_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateEmailSetting(:p_email_setting_id, :p_last_log_by, @p_new_email_setting_id)');
        $stmt->bindValue(':p_email_setting_id', $p_email_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_email_setting_id AS email_setting_id");
        $emailSettingID = $result->fetch(PDO::FETCH_ASSOC)['email_setting_id'];

        return $emailSettingID;
    }
    # -------------------------------------------------------------
}
?>