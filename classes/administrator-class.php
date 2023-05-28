<?php

class Administrator_Class {
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
    #
    # Name       : check_password_history_exist
    # Purpose    : Checks if the password history exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_password_history_exist($p_user_id, $p_email, $p_password){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $total = 0;
        $user_password_history_details = $this->get_user_password_history_details($p_user_id, $p_email);
    
        foreach ($user_password_history_details as $password_history_details) {
            $password_history = $this->decrypt_data($password_history_details['PASSWORD']);
    
            if ($password_history === $p_password) {
                $total++;
            }
        }
    
        return $total;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_ui_customization_setting_exist
    # Purpose    : Checks if the ui customization setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_ui_customization_setting_exist($p_user_id, $p_email_address){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $sql = $this->global->db_connection->prepare('CALL check_ui_customization_setting_exist(:p_user_id, :p_email_address)');
        $sql->bindValue(':p_user_id', $p_user_id);
        $sql->bindValue(':p_email_address', $p_email_address);

        if($sql->execute()){
            $row = $sql->fetch();

            return (int) $row['total'];
        }
        else{
            return $stmt->errorInfo()[2];
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
    #
    # Name       : update_user_password
    # Purpose    : Updates the user password.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function update_user_password($p_user_id, $p_email, $p_password, $p_password_expiry_date){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $sql = $this->global->db_connection->prepare('CALL update_user_password(:p_user_id, :p_email, :p_password, :p_password_expiry_date)');
        $sql->bindValue(':p_user_id', $p_user_id);
        $sql->bindValue(':p_email', $p_email);
        $sql->bindValue(':p_password', $p_password);
        $sql->bindValue(':p_password_expiry_date', $p_password_expiry_date);

        if($sql->execute()){
            return true;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_ui_customization_setting
    # Purpose    : Updates the ui customization setting.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function update_ui_customization_setting($p_user_id, $p_email, $p_type, $p_customization_value, $p_last_log_by){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $sql = $this->global->db_connection->prepare('CALL update_ui_customization_setting(:p_user_id, :p_email, :p_type, :p_customization_value, :p_last_log_by)');
        $sql->bindValue(':p_user_id', $p_user_id);
        $sql->bindValue(':p_email', $p_email);
        $sql->bindValue(':p_type', $p_type);
        $sql->bindValue(':p_customization_value', $p_customization_value);
        $sql->bindValue(':p_last_log_by', $p_last_log_by);

        if($sql->execute()){
            return true;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_password_history
    # Purpose    : Inserts the user password history.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function insert_password_history($p_user_id, $p_email, $p_password){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $sql = $this->global->db_connection->prepare('CALL insert_password_history(:p_user_id, :p_email, :p_password)');
        $sql->bindValue(':p_user_id', $p_user_id);
        $sql->bindValue(':p_email', $p_email);
        $sql->bindValue(':p_password', $p_password);

        if($sql->execute()){
            return true;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_ui_customization_setting
    # Purpose    : Inserts the ui customization setting.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function insert_ui_customization_setting($p_user_id, $p_email, $p_type, $p_customization_value, $p_last_log_by){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $sql = $this->global->db_connection->prepare('CALL insert_ui_customization_setting(:p_user_id, :p_email, :p_type, :p_customization_value, :p_last_log_by)');
        $sql->bindValue(':p_user_id', $p_user_id);
        $sql->bindValue(':p_email', $p_email);
        $sql->bindValue(':p_type', $p_type);
        $sql->bindValue(':p_customization_value', $p_customization_value);
        $sql->bindValue(':p_last_log_by', $p_last_log_by);

        if($sql->execute()){
            return true;
        }
        else{
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

    # -------------------------------------------------------------
    #
    # Name       : get_user_password_history_details
    # Purpose    : Gets the user password history details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_user_password_history_details($p_user_id, $p_email_address){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $response = [];

        $sql = $this->global->db_connection->prepare('CALL get_user_password_history_details(:p_user_id, :p_email_address)');
        $sql->bindValue(':p_user_id', $p_user_id);
        $sql->bindValue(':p_email_address', $p_email_address);

        if($sql->execute()){
            while($row = $sql->fetch()){
                $response[] = array(
                    'PASSWORD' => $row['password']
                );
            }

            return $response;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_ui_customization_setting_details
    # Purpose    : Gets the ui customization setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_ui_customization_setting_details($p_user_id, $p_email_address){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }
    
        $response = [];

        $sql = $this->global->db_connection->prepare('CALL get_ui_customization_setting_details(:p_user_id, :p_email_address)');
        $sql->bindValue(':p_user_id', $p_user_id);
        $sql->bindValue(':p_email_address', $p_email_address);

        if($sql->execute()){
            while($row = $sql->fetch()){
                $response[] = array(
                    'EXTERNAL_ID' => $row['external_id'],
                    'USER_ID' => $row['user_id'],
                    'EMAIL_ADDRESS' => $row['email_address'],
                    'THEME_CONTRAST' => $row['theme_contrast'],
                    'CAPTION_SHOW' => $row['caption_show'],
                    'PRESET_THEME' => $row['preset_theme'],
                    'DARK_LAYOUT' => $row['dark_layout'],
                    'RTL_LAYOUT' => $row['rtl_layout'],
                    'BOX_CONTAINER' => $row['box_container'],
                    'LAST_LOG_BY' => $row['last_log_by']
                );
            }

            return $response;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_user_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_user_status($user_status){
        $status = ($user_status === 'Active') ? 'Active' : 'Deactivated';
        $button_class = ($user_status === 'Active') ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_user_status
    # Purpose    : Checks the user account status. 
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function check_user_status($p_user_id, $p_email_address){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $user_details = $this->get_user_details($p_user_id, $p_email_address);
        $user_status = $user_details[0]['USER_STATUS'];
        $failed_login = $user_details[0]['FAILED_LOGIN'];

        return ($user_status == 'Active' && $failed_login < 5) ? true : false;
    }
    # -------------------------------------------------------------
}

?>