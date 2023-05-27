<?php

class Administrator {
    private $global;

    public function __construct() {
        $this->global = new Global_Class();
    }

    # -------------------------------------------------------------
    #
    # Name       : authenticate
    # Purpose    : Authenticates the user.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function authenticate($email, $password) {
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $check_user_exist = $this->check_user_exist(null, $email);
    
        if ($check_user_exist !== 1) {
            return 'Incorrect';
        }
    
        $user_details = $this->get_user_details(null, $email);
        $user_status = $user_details[0]['USER_STATUS'];
        $login_attempt = $user_details[0]['FAILED_LOGIN'];
        $password_expiry_date = $user_details[0]['PASSWORD_EXPIRY_DATE'];
    
        if ($user_status !== 'Active') {
            return 'Inactive';
        }
    
        if ($login_attempt >= 5) {
            return 'Locked';
        }
    
        $decrypted_password = $this->global->decrypt_data($user_details[0]['PASSWORD']);
    
        if ($decrypted_password === $password) {
            if (strtotime(date('Y-m-d')) > strtotime($password_expiry_date)) {
                return 'Password Expired';
            }
    
            $update_user_login_attempt = $this->update_user_login_attempt(null, $email, 0, null);
    
            if (!$update_user_login_attempt) {
                return $update_user_login_attempt;
            }
    
            $update_user_last_connection = $this->update_user_last_connection(null, $email);
    
            if (!$update_user_last_connection) {
                return $update_user_last_connection;
            }
    
            return 'Authenticated';
        }
        else {
            $update_user_login_attempt = $this->update_user_login_attempt(null, $email, ($login_attempt + 1), date('Y-m-d H:i:s'));
    
            if (!$update_user_login_attempt) {
                return $update_user_login_attempt;
            }
    
            return 'Incorrect';
        }
    }    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check data exist methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : check_user_exist
    # Purpose    : Checks if the user exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_user_exist($p_external_id, $p_email_address) {
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $sql = $this->global->db_connection->prepare('CALL check_user_exist(:p_external_id, :p_email_address)');
        $sql->bindValue(':p_external_id', $p_external_id);
        $sql->bindValue(':p_email_address', $p_email_address);
    
        if ($sql->execute()) {
            $row = $sql->fetch();
    
            return (int) $row['total'];
        } 
        else {
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : update_user_login_attempt
    # Purpose    : Updates the login attempt of the user.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function update_user_login_attempt($p_external_id, $p_email_address, $p_login_attempt, $p_last_failed_attempt_date) {
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $sql = $this->global->db_connection->prepare('CALL update_user_login_attempt(:p_external_id, :p_email_address, :p_login_attempt, :p_last_failed_attempt_date)');
        $sql->bindValue(':p_external_id', $p_external_id);
        $sql->bindValue(':p_email_address', $p_email_address);
        $sql->bindValue(':p_login_attempt', $p_login_attempt);
        $sql->bindValue(':p_last_failed_attempt_date', $p_last_failed_attempt_date);
    
        if ($sql->execute()) {
            return true;
        }
        else {
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_user_last_connection
    # Purpose    : Updates the last user connection date.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function update_user_last_connection($p_external_id, $p_email) {
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $sql = $this->global->db_connection->prepare('CALL update_user_last_connection(:p_external_id, :p_email, :p_system_date)');
        $sql->bindValue(':p_external_id', $p_external_id);
        $sql->bindValue(':p_email', $p_email);
        $sql->bindValue(':p_system_date', date('Y-m-d H:i:s'));
    
        if ($sql->execute()) {
            return true;
        }
        else {
            return $sql->errorInfo()[2];
        }
    }    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_user_details
    # Purpose    : Gets the user details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_user_details($p_external_id, $p_email_address) {
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $response = [];
    
        $sql = $this->global->db_connection->prepare('CALL get_user_details(:p_external_id, :p_email_address)');
        $sql->bindValue(':p_external_id', $p_external_id);
        $sql->bindValue(':p_email_address', $p_email_address);
    
        if ($sql->execute()) {
            while ($row = $sql->fetch()) {
                $response[] = [
                    'EXTERNAL_ID' => $row['external_id'],
                    'EMAIL_ADDRESS' => $row['email_address'],
                    'PASSWORD' => $row['password'],
                    'FILE_AS' => $row['file_as'],
                    'USER_STATUS' => $row['user_status'],
                    'PASSWORD_EXPIRY_DATE' => $row['password_expiry_date'],
                    'FAILED_LOGIN' => $row['failed_login'],
                    'LAST_FAILED_LOGIN' => $row['last_failed_login'],
                    'LAST_CONNECTION_DATE' => $row['last_connection_date'],
                    'LAST_LOG_BY' => $row['last_log_by']
                ];
            }
    
            return $response;
        }
        else {
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------
}

?>