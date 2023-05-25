<?php

class Api{
    # @var object $db_connection The database connection
    public $db_connection = null;

    public $response = array();

    # -------------------------------------------------------------
    #   Custom methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : databaseConnection
    # Purpose    : Checks if database connection is opened.
    #              If not, then this method tries to open it.
    #              @return bool Success status of the
    #              database connecting process
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function databaseConnection(){
        if ($this->db_connection) {
            return $this->db_connection;
        }
    
        try {
            $this->db_connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
            $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            throw new Exception('Error connecting to database: ' . $e->getMessage());
        }
    
        return $this->db_connection;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : backup_database
    # Purpose    : Backs-up the database.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function backup_database($file_name, $username){
        if ($this->databaseConnection()) {
            $backup_file = 'backup/' . $file_name . '_' . time() . '.sql';
    
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $cmd = sprintf('C:\xampp\mysql\bin\mysqldump.exe --routines --single-transaction -u %s -p%s %s -r %s',
                    escapeshellarg(DB_USER), escapeshellarg(DB_PASS), escapeshellarg(DB_NAME), escapeshellarg($backup_file)
                );
            }
            else {
                $cmd = sprintf('/usr/bin/mysqldump --routines --single-transaction -u %s -p%s %s -r %s',
                    escapeshellarg(DB_USER), escapeshellarg(DB_PASS), escapeshellarg(DB_NAME), escapeshellarg($backup_file)
                );
            }
            
            exec($cmd, $output, $return);
            
            if ($return === 0) {
                return true;
            } 
            else {
                return 'Error: mysqldump command failed with error code ' . $return;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : format_date
    # Purpose    : Returns date with a custom formatting
    #              Avoids error when date is greater 
    #              than the year 2038 or less than 
    #              January 01, 1970.
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function format_date($format, $date, $modify = null) {
        $dateTime = new DateTime($date);

        if ($modify) {
            $dateTime->modify($modify);
        }
        
        return $dateTime->format($format);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : encrypt_data
    # Purpose    : Encrypt the text.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function encrypt_data($plaintext) {
        if (empty(trim($plaintext))) return false;

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $ciphertext = openssl_encrypt(trim($plaintext), 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$ciphertext) return false;
        
        return rawurlencode(base64_encode($iv . $ciphertext));
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : decrypt_data
    # Purpose    : Decrypt the text.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function decrypt_data($ciphertext) {
        $ciphertext = base64_decode(rawurldecode($ciphertext));

        if (!$ciphertext) {
            return false;
        }
        
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');

        if (strlen($ciphertext) < $iv_length) {
            return false;
        }
        
        $iv = substr($ciphertext, 0, $iv_length);
        $ciphertext = substr($ciphertext, $iv_length);
        
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$plaintext) {
            return false;
        }
        
        return $plaintext;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : add_months
    # Purpose    : Add months to calculated date.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function add_months($months, DateTime $dateObject){
        $dateObject->modify('last day of +' . $months . ' month');
        $next = new DateTime($dateObject->format('Y-m-d'));

        return ($dateObject->format('d') > $next->format('d')) ? $dateObject->diff($next) : new DateInterval('P' . $months . 'M');        
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : validate_email
    # Purpose    : Validate if email is valid.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function validate_email($email) {
        $email = trim($email);

        if (empty($email)) {
            return 'Error: Missing email';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Error: Invalid email';
        }
        return true;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : authenticate
    # Purpose    : Authenticates the user.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function authenticate($email, $password){
        if ($this->databaseConnection()) {
            $check_user_exist = $this->check_user_exist(null, $email);
           
            if($check_user_exist === 1){
                $user_details = $this->get_user_details(null, $email);
                $user_status = $user_details[0]['USER_STATUS'];
                $login_attempt = $user_details[0]['FAILED_LOGIN'];
                $password_expiry_date = $user_details[0]['PASSWORD_EXPIRY_DATE'];

                if($user_status == 'Active'){
                    if($login_attempt < 5){
                        $decrypted_password = $this->decrypt_data($user_details[0]['PASSWORD']);
                        
                        if($decrypted_password === $password){
                            if(strtotime(date('Y-m-d')) > strtotime($password_expiry_date)){
                                return 'Password Expired';
                            }
                            else{
                                $update_user_login_attempt = $this->update_user_login_attempt(null, $email, 0, null);

                                if($update_user_login_attempt){
                                    $update_user_last_connection = $this->update_user_last_connection(null, $email);

                                    if($update_user_last_connection){
                                        return 'Authenticated';
                                    }
                                    else{
                                        return $update_user_last_connection;
                                    }
                                }
                                else{
                                    return $update_user_login_attempt;
                                }
                            }
                        }
                        else{
                            $update_user_login_attempt = $this->update_user_login_attempt(null, $email, ($login_attempt + 1), date('Y-m-d H:i:s'));

                            if($update_user_login_attempt){
                                return 'Incorrect';
                            }
                            else{
                                return $update_user_login_attempt;
                            }
                        }
                    }
                    else{
                        return 'Locked';
                    }
                }
                else{
                    return 'Inactive';
                }

            }
            else{
                return 'Incorrect';
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : time_elapsed_string
    # Purpose    : returns the time elapsed
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function time_elapsed_string($datetime) {
        $timestamp = strtotime($datetime);
        $current_timestamp = time();
    
        $diff_seconds = $current_timestamp - $timestamp;
    
        $intervals = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
    
        $elapsed_time = '';
        foreach ($intervals as $seconds => $label) {
            $count = floor($diff_seconds / $seconds);
            if ($count > 0) {
                $elapsed_time = $count . ' ' . $label . ($count == 1 ? '' : 's') . ' ago';
                break;
            }
            else{
                $elapsed_time = 'Just Now';
            }
        }
    
        return $elapsed_time;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : directory_checker
    # Purpose    : Checks the directory if it exists and create if not exist
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function directory_checker($directory) {
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                $error = error_get_last();
                return 'Error creating directory: ' . $error['message'];
            }
        }
        else {
            if (!is_writable($directory)) {
                return 'Directory exists, but is not writable';
            }
        }

        return true;
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
    public function check_user_exist($p_user_id, $p_email_address){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_user_exist(:p_user_id, :p_email_address)');
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
        if ($this->databaseConnection()) {
            $total = 0;

            $get_user_password_history_details = $this->get_user_password_history_details($p_user_id, $p_email);

            for($i = 0; $i < count($get_user_password_history_details); $i++) {
                $password_history = $this->decrypt_data($get_user_password_history_details[$i]['PASSWORD']);
                    
                if($password_history === $p_password){
                    $total = $total + 1;
                }
            }

            return (int) $total;
        }
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
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_ui_customization_setting_exist(:p_user_id, :p_email_address)');
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
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : check_menu_groups_exist
    # Purpose    : Checks if the menu groups exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_menu_groups_exist($p_menu_group_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_menu_groups_exist(:p_menu_group_id)');
            $sql->bindValue(':p_menu_group_id', $p_menu_group_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['total'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : check_menu_item_exist
    # Purpose    : Checks if the menu item exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_menu_item_exist($p_menu_item_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_menu_item_exist(:p_menu_item_id)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['total'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : check_role_menu_item_access_right_exist
    # Purpose    : Checks if the role menu item access right exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_role_menu_item_access_right_exist($p_menu_item_id, $p_role_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_role_menu_item_access_right_exist(:p_menu_item_id, :p_role_id)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
            $sql->bindValue(':p_role_id', $p_role_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['total'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
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
    public function update_user_login_attempt($p_user_id, $p_email_address, $p_login_attempt, $p_last_failed_attempt_date){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_user_login_attempt(:p_user_id, :p_email_address, :p_login_attempt, :p_last_failed_attempt_date)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email_address', $p_email_address);
            $sql->bindValue(':p_login_attempt', $p_login_attempt);
            $sql->bindValue(':p_last_failed_attempt_date', $p_last_failed_attempt_date);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
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
    public function update_user_last_connection($p_user_id, $p_email){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_user_last_connection(:p_user_id, :p_email, :system_date)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email', $p_email);
            $sql->bindValue(':system_date', date('Y-m-d H:i:s'));

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
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
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_user_password(:p_user_id, :p_email, :p_password, :p_password_expiry_date)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email', $p_email);
            $sql->bindValue(':p_password', $p_password);
            $sql->bindValue(':p_password_expiry_date', $p_password_expiry_date);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
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
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_ui_customization_setting(:p_user_id, :p_email, :p_type, :p_customization_value, :p_last_log_by)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email', $p_email);
            $sql->bindValue(':p_type', $p_type);
            $sql->bindValue(':p_customization_value', $p_customization_value);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_menu_groups
    # Purpose    : Updates the menu groups.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function update_menu_groups($p_menu_group_id, $p_menu_group_name, $p_order_sequence, $p_last_log_by){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_menu_groups(:p_menu_group_id, :p_menu_group_name, :p_order_sequence, :p_last_log_by)');
            $sql->bindValue(':p_menu_group_id', $p_menu_group_id);
            $sql->bindValue(':p_menu_group_name', $p_menu_group_name);
            $sql->bindValue(':p_order_sequence', $p_order_sequence);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_menu_item
    # Purpose    : Updates the menu item.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function update_menu_item($p_menu_item_id, $p_menu_item_name, $p_menu_group_id, $p_menu_item_url, $p_parent_id, $p_menu_item_icon, $p_order_sequence, $p_last_log_by){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_menu_item(:p_menu_item_id, :p_menu_item_name, :p_menu_group_id, :p_menu_item_url, :p_parent_id, :p_menu_item_icon, :p_order_sequence, :p_last_log_by)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
            $sql->bindValue(':p_menu_item_name', $p_menu_item_name);
            $sql->bindValue(':p_menu_group_id', $p_menu_group_id);
            $sql->bindValue(':p_menu_item_url', $p_menu_item_url);
            $sql->bindValue(':p_parent_id', $p_parent_id);
            $sql->bindValue(':p_menu_item_icon', $p_menu_item_icon);
            $sql->bindValue(':p_order_sequence', $p_order_sequence);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_role_menu_item_access_right
    # Purpose    : Updates the role menu item access right.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function update_role_menu_item_access_right($p_menu_item_id, $p_role_id, $p_access_type, $p_access){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_role_menu_item_access_right(:p_menu_item_id, :p_role_id, :p_access_type, :p_access)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
            $sql->bindValue(':p_role_id', $p_role_id);
            $sql->bindValue(':p_access_type', $p_access_type);
            $sql->bindValue(':p_access', $p_access);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
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
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_password_history(:p_user_id, :p_email, :p_password)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email', $p_email);
            $sql->bindValue(':p_password', $p_password);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_user_ui_customization_setting
    # Purpose    : Inserts the users ui customization setting.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function insert_user_ui_customization_setting($p_user_id, $p_email){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_user_ui_customization_setting(:p_user_id, :p_email)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email', $p_email);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
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
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_ui_customization_setting(:p_user_id, :p_email, :p_type, :p_customization_value, :p_last_log_by)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email', $p_email);
            $sql->bindValue(':p_type', $p_type);
            $sql->bindValue(':p_customization_value', $p_customization_value);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_menu_groups
    # Purpose    : Inserts the menu group.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_menu_groups($p_menu_group_name, $p_order_sequence, $p_last_log_by){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_menu_groups(:p_menu_group_name, :p_order_sequence, :p_last_log_by, @p_menu_group_id)');
            $sql->bindValue(':p_menu_group_name', $p_menu_group_name);
            $sql->bindValue(':p_order_sequence', $p_order_sequence);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);
    
            if($sql->execute()){
                $result = $this->db_connection->query("SELECT @p_menu_group_id AS menu_group_id");
                $menu_group_id = $result->fetch(PDO::FETCH_ASSOC)['menu_group_id'];
                
                $response[] = array(
                    'RESPONSE' => true,
                    'MENU_GROUP_ID' => $this->encrypt_data($menu_group_id)
                );
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_menu_item
    # Purpose    : Inserts the menu item.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_menu_item($p_menu_item_name, $p_menu_group_id, $p_menu_item_url, $p_parent_id, $p_menu_item_icon, $p_order_sequence, $p_last_log_by){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_menu_item(:p_menu_item_name, :p_menu_group_id, :p_menu_item_url, :p_parent_id, :p_menu_item_icon, :p_order_sequence, :p_last_log_by, @p_menu_item_id)');
            $sql->bindValue(':p_menu_item_name', $p_menu_item_name);
            $sql->bindValue(':p_menu_group_id', $p_menu_group_id);
            $sql->bindValue(':p_menu_item_url', $p_menu_item_url);
            $sql->bindValue(':p_parent_id', $p_parent_id);
            $sql->bindValue(':p_menu_item_icon', $p_menu_item_icon);
            $sql->bindValue(':p_order_sequence', $p_order_sequence);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);
    
            if($sql->execute()){
                $result = $this->db_connection->query("SELECT @p_menu_item_id AS p_menu_item_id");
                $p_menu_item_id = $result->fetch(PDO::FETCH_ASSOC)['p_menu_item_id'];
                
                $response[] = array(
                    'RESPONSE' => true,
                    'MENU_ITEM_ID' => $this->encrypt_data($p_menu_item_id)
                );
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_role_menu_item_access_right
    # Purpose    : Inserts the role menu item access right.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function insert_role_menu_item_access_right($p_menu_item_id, $p_role_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_role_menu_item_access_right(:p_menu_item_id, :p_role_id)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
            $sql->bindValue(':p_role_id', $p_role_id);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_menu_groups
    # Purpose    : Delete the menu group.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function delete_menu_groups($p_menu_group_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_menu_groups(:p_menu_group_id)');
            $sql->bindValue(':p_menu_group_id', $p_menu_group_id);
    
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_menu_item
    # Purpose    : Delete the menu item.
    #
    # Returns    : Bool/String
    #
    # -------------------------------------------------------------
    public function delete_menu_item($p_menu_item_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_menu_item(:p_menu_item_id)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
    
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : duplicate_menu_groups
    # Purpose    : Inserts the menu group.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function duplicate_menu_groups($menu_group_id, $p_last_log_by){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL duplicate_menu_groups(:menu_group_id, :p_last_log_by, @p_menu_group_id)');
            $sql->bindValue(':menu_group_id', $menu_group_id);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);
    
            if($sql->execute()){
                $result = $this->db_connection->query("SELECT @p_menu_group_id AS menu_group_id");
                $menu_group_id = $result->fetch(PDO::FETCH_ASSOC)['menu_group_id'];
                
                $response[] = array(
                    'RESPONSE' => true,
                    'MENU_GROUP_ID' => $this->encrypt_data($menu_group_id)
                );
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : duplicate_menu_item
    # Purpose    : Inserts the menu group.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function duplicate_menu_item($menu_item_id, $p_last_log_by){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL duplicate_menu_item(:menu_item_id, :p_last_log_by, @p_menu_item_id)');
            $sql->bindValue(':menu_item_id', $menu_item_id);
            $sql->bindValue(':p_last_log_by', $p_last_log_by);
    
            if($sql->execute()){
                $result = $this->db_connection->query("SELECT @p_menu_item_id AS menu_item_id");
                $menu_item_id = $result->fetch(PDO::FETCH_ASSOC)['menu_item_id'];
                
                $response[] = array(
                    'RESPONSE' => true,
                    'MENU_ITEM_ID' => $this->encrypt_data($menu_item_id)
                );
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
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
    public function get_user_details($p_user_id, $p_email_address){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_user_details(:p_user_id, :p_email_address)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email_address', $p_email_address);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'USER_ID' => $row['user_id'],
                        'EMAIL_ADDRESS' => $row['email_address'],
                        'PASSWORD' => $row['password'],
                        'FILE_AS' => $row['file_as'],
                        'USER_STATUS' => $row['user_status'],
                        'PASSWORD_EXPIRY_DATE' => $row['password_expiry_date'],
                        'FAILED_LOGIN' => $row['failed_login'],
                        'LAST_FAILED_LOGIN' => $row['last_failed_login'],
                        'LAST_CONNECTION_DATE' => $row['last_connection_date'],
                        'LAST_LOG_BY' => $row['last_log_by']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
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
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_user_password_history_details(:p_user_id, :p_email_address)');
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
                return $stmt->errorInfo()[2];
            }
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
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_ui_customization_setting_details(:p_user_id, :p_email_address)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_email_address', $p_email_address);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'UI_CUSTOMIZATION_SETTING_ID' => $row['ui_customization_setting_id'],
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_menu_groups_details
    # Purpose    : Gets the menu groups details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_menu_groups_details($p_menu_group_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_menu_groups_details(:p_menu_group_id)');
            $sql->bindValue(':p_menu_group_id', $p_menu_group_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MENU_GROUP_NAME' => $row['menu_group_name'],
                        'ORDER_SEQUENCE' => $row['order_sequence'],
                        'LAST_LOG_BY' => $row['last_log_by']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_menu_item_details
    # Purpose    : Gets the menu item details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_menu_item_details($p_menu_item_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_menu_item_details(:p_menu_item_id)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MENU_ITEM_NAME' => $row['menu_item_name'],
                        'MENU_GROUP_ID' => $row['menu_group_id'],
                        'MENU_ITEM_URL' => $row['menu_item_url'],
                        'PARENT_ID' => $row['parent_id'],
                        'MENU_ITEM_ICON' => $row['menu_item_icon'],
                        'ORDER_SEQUENCE' => $row['order_sequence'],
                        'LAST_LOG_BY' => $row['last_log_by']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_role_details
    # Purpose    : Gets the role details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_role_details($p_role_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_role_details(:p_role_id)');
            $sql->bindValue(':p_role_id', $p_role_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ROLE_NAME' => $row['role_name'],
                        'ROLE_DESCRIPTION' => $row['role_description'],
                        'ASSIGNABLE' => $row['assignable'],
                        'LAST_LOG_BY' => $row['last_log_by']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_role_menu_access_rights
    # Purpose    : Gets the role details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_role_menu_access_rights($p_menu_item_id, $p_role_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_role_menu_access_rights(:p_menu_item_id, :p_role_id)');
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
            $sql->bindValue(':p_role_id', $p_role_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'READ_ACCESS' => $row['read_access'],
                        'WRITE_ACCESS' => $row['write_access'],
                        'CREATE_ACCESS' => $row['create_access'],
                        'DELETE_ACCESS' => $row['delete_access']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_file_as_format
    # Purpose    : Returns the file as name format
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_file_as_format($first_name, $middle_name = null, $last_name, $suffix = null){
        $suffix = $suffix ? $this->get_system_code_details(null, 'SUFFIX', $suffix)[0]['SYSTEM_DESCRIPTION'] : null;

        $name_parts = [$last_name, $first_name];
        if (!empty($middle_name)) {
            $name_parts[] = $middle_name;
        }
        if (!empty($suffix)) {
            $name_parts[] = $suffix;
        }

        return implode(', ', $name_parts);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_default_image
    # Purpose    : returns the default image.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_default_image($type) {
        $defaultImages = [
            'profile' => DEFAULT_AVATAR_IMAGE,
            'login background' => DEFAULT_BG_IMAGE,
            'login logo' => DEFAULT_LOGIN_LOGO_IMAGE,
            'menu logo' => DEFAULT_MENU_LOGO_IMAGE,
            'module icon' => DEFAULT_MODULE_ICON_IMAGE,
            'favicon' => DEFAULT_FAVICON_IMAGE,
            'company logo' => DEFAULT_COMPANY_LOGO_IMAGE,
        ];
    
        return $defaultImages[$type] ?? DEFAULT_PLACEHOLDER_IMAGE;
    }
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
    #
    # Name       : get_modal_size
    # Purpose    : returns the size of the modal.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_modal_size($size){
        $sizes = ['SM' => 'modal-sm', 'LG' => 'modal-lg', 'XL' => 'modal-xl'];

        return $sizes[$size] ?? null;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_modal_content
    # Purpose    : returns the size of the modal.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_modal_content($generation_type){
        switch ($generation_type){
            case 'menu item form':
                
            break;
        }

        return null;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Build methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : build_menu_group
    # Purpose    : Builds the menu group.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function build_menu_group($p_email_address){
        if ($this->databaseConnection()) {
            $response = array();
            $user_details = $this->get_user_details(null, $p_email_address);
            $p_user_id = $user_details[0]['USER_ID'];

            $sql = $this->db_connection->prepare('CALL build_menu_group(:p_user_id)');
            $sql->bindValue(':p_user_id', $p_user_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MENU_GROUP_ID' => $row['menu_group_id'],
                        'MENU_GROUP_NAME' => $row['menu_group_name']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : build_menu_item
    # Purpose    : Builds the menu items.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function build_menu_item($p_email_address, $p_menu_group_id) {
        if ($this->databaseConnection()) {
            $menu_items = array();
            $user_details = $this->get_user_details(null, $p_email_address);
            $p_user_id = $user_details[0]['USER_ID'];
    
            $sql = $this->db_connection->prepare('CALL build_menu_item(:p_user_id, :p_menu_group_id)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_menu_group_id', $p_menu_group_id);
    
            if ($sql->execute()) {
                while ($row = $sql->fetch()) {
                    $menu_item_id = $row['menu_item_id'];
                    $menu_item_name = $row['menu_item_name'];
                    $menu_item_url = $row['menu_item_url'];
                    $parent_id = $row['parent_id'];
                    $menu_item_icon = $row['menu_item_icon'];
    
                    $menu_item = array(
                        'MENU_ITEM_ID' => $menu_item_id,
                        'MENU_ITEM_NAME' => $menu_item_name,
                        'MENU_ITEM_URL' => $menu_item_url,
                        'PARENT_ID' => $parent_id,
                        'MENU_ITEM_ICON' => $menu_item_icon,
                        'CHILDREN' => array()
                    );
    
                    $menu_items[$menu_item_id] = $menu_item;
    
                    if (!empty($parent_id) && isset($menu_items[$parent_id])) {
                        $menu_items[$parent_id]['CHILDREN'][] = &$menu_items[$menu_item_id];
                    }
                }
    
                $root_menu_items = array_filter($menu_items, function ($item) {
                    return empty($item['PARENT_ID']);
                });
    
                $html = '';
    
                foreach ($root_menu_items as $root_menu_item) {
                    $html .= $this->build_menu_item_html($root_menu_item);
                }
    
                return $html;
            }
            else {
                return $sql->errorInfo()[2];
            }
        }
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : build_menu_item_html
    # Purpose    : Builds the menu item's HTML.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function build_menu_item_html($p_menu_item, $level = 1) {
        $html = '';
        $menu_item_name = $p_menu_item['MENU_ITEM_NAME'];
        $menu_item_icon = $p_menu_item['MENU_ITEM_ICON'];
        $menu_item_url = $p_menu_item['MENU_ITEM_URL'];
        $children = $p_menu_item['CHILDREN'];
    
        if ($level === 1) {
            if (empty($children)) {
                $html .= '<li class="pc-item">';
                $html .= '<a href="' . $menu_item_url . '" class="pc-link">';
                $html .= '<span class="pc-micon">';
                $html .= '<i data-feather="'. $menu_item_icon .'"></i>';
                $html .= '</span>';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            } 
            else {
                $html .= '<li class="pc-item pc-hasmenu">';
                $html .= '<a href="JavaScript:void(0);" class="pc-link">';
                $html .= '<span class="pc-micon">';
                $html .= ' <i data-feather="'. $menu_item_icon .'"></i>';
                $html .= '</span>';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '<span class="pc-arrow"><i data-feather="chevron-right"></i></span>';
                $html .= '</a>';
                $html .= '<ul class="pc-submenu">';
    
                foreach ($children as $child) {
                    $html .= $this->build_menu_item_html($child, $level + 1);
                }
    
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
        else {
            if (empty($children)) {
                $html .= '<li class="pc-item">';
                $html .= '<a href="' . $menu_item_url . '" class="pc-link">';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            } 
            else {
                $html .= '<li class="pc-item pc-hasmenu">';
                $html .= '<a href="JavaScript:void(0);" class="pc-link">';
                $html .= '<span class="pc-mtext">' . $menu_item_name . '</span>';
                $html .= '<span class="pc-arrow"><i data-feather="chevron-right"></i></span>';
                $html .= '</a>';
                $html .= '<ul class="pc-submenu">';
    
                foreach ($children as $child) {
                    $html .= $this->build_menu_item_html($child, $level + 1);
                }
    
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
    
        return $html;
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : build_menu
    # Purpose    : Builds the menu group.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function build_menu($p_email_address)
    {
        if ($this->databaseConnection()) {
            $menu = '';
            
            $menu_groups = $this->build_menu_group($p_email_address);
    
            foreach ($menu_groups as $menu_group) {
                $menu_group_id = $menu_group['MENU_GROUP_ID'];
                $menu_group_name = $menu_group['MENU_GROUP_NAME'];
    
                $menu .= '<li class="pc-item pc-caption">
                            <label>'. $menu_group_name .'</label>
                        </li>';
    
                $menu .= $this->build_menu_item($p_email_address, $menu_group_id);
            }
    
            return $menu;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Check methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_image
    # Purpose    : Checks the image.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function check_image($image, $type){
        $image = $image ?? '';
        
        return (empty($image) || !file_exists($image)) ? $this->get_default_image($type) : $image;
    }
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
        if ($this->databaseConnection()) {
            $user_details = $this->get_user_details($p_user_id, $p_email_address);
            $user_status = $user_details[0]['USER_STATUS'];
            $failed_login = $user_details[0]['FAILED_LOGIN'];

            return ($user_status == 'Active' && $failed_login < 5) ? true : false;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_menu_access_rights
    # Purpose    : Checks the menu access rights of the role based on type.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_menu_access_rights($p_email_address, $p_menu_item_id, $p_access_type){
        if ($this->databaseConnection()) {
            $user_details = $this->get_user_details(null, $p_email_address);
            $p_user_id = $user_details[0]['USER_ID'];

            $sql = $this->db_connection->prepare('CALL check_menu_access_rights(:p_user_id, :p_menu_item_id, :p_access_type)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
            $sql->bindValue(':p_access_type', $p_access_type);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_system_action_access_rights
    # Purpose    : Checks the menu access rights of the role based on type.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_system_action_access_rights($p_email_address, $p_system_action_id){
        if ($this->databaseConnection()) {
            $user_details = $this->get_user_details(null, $p_email_address);
            $p_user_id = $user_details[0]['USER_ID'];

            $sql = $this->db_connection->prepare('CALL check_system_action_access_rights(:p_user_id, :p_system_action_id)');
            $sql->bindValue(':p_user_id', $p_user_id);
            $sql->bindValue(':p_system_action_id', $p_system_action_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_modal_scrollable
    # Purpose    : Check if the modal to be generated
    #              is scrollable or not.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function check_modal_scrollable($scrollable){
        return $scrollable ? 'modal-dialog-scrollable' : null;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_file_name
    # Purpose    : generates random file name.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_file_name($length, $prefix = '') {
        $keys = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        $maxIndex = count($keys) - 1;

        $key = $prefix . array_reduce(array_fill(0, $length, null), function ($carry, $val) use ($keys, $maxIndex) {
            return $carry . $keys[random_int(0, $maxIndex)];
        }) . uniqid('', true);

        return $key;        
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_log_notes
    # Purpose    : generates log notes block.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_log_notes($p_table_name, $p_reference_id) {
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('SELECT log, changed_by, changed_at FROM audit_log WHERE table_name = :p_table_name AND reference_id = :p_reference_id ORDER BY changed_at DESC');
            $sql->bindValue(':p_table_name', $p_table_name);
            $sql->bindValue(':p_reference_id', $p_reference_id);

            if($sql->execute()){
                $count = $sql->rowCount();

                if($count > 0){
                    $log_notes = '<div class="col-lg-12">
                                        <div class="card">
                                            <div id="sticky-action" class="sticky-action">
                                                <div class="card-header">
                                                    <div class="row align-items-center">
                                                        <div class="col-sm-6">
                                                            <h5>Log Notes</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="log-notes-scroll" style="height: 415px; position: relative;">
                                                <div class="card-body p-b-0">
                                                    <div class="comment-block">';

                    while($row = $sql->fetch()){
                        $log = $row['log'];
                        $changed_by = $row['changed_by'];
                        $time_elapsed = $this->time_elapsed_string($row['changed_at']);

                        $user_details = $this->get_user_details($changed_by, null);
                        $file_as = $user_details[0]['FILE_AS'] ?? null;

                        $log_notes .= '<div class="comment">
                                            <div class="media align-items-start">
                                                <div class="chat-avtar flex-shrink-0">
                                                    <img class="rounded-circle img-fluid wid-40" src="./assets/images/default/default-avatar.png" alt="User image" />
                                                </div>
                                                <div class="media-body ms-3">
                                                    <h5 class="mb-0">'. $file_as .'</h5>
                                                    <span class="text-sm text-muted">'. $time_elapsed .'</span>
                                                </div>
                                            </div>
                                            <div class="comment-content">
                                                <p class="mb-0">
                                                    '. $log .'
                                                </p>
                                            </div>
                                        </div>';

                    }

                    $log_notes .= '         </div>
                                        </div>
                                    </div>';
                }
                else{
                    $log_notes = null;
                }
               
                return $log_notes;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_form
    # Purpose    : generates form based on type.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_form($form_type, $reference_id, $email) {
        $form = '';

        switch ($form_type){
            case 'menu groups form':
                $menu_group_create_access_right = $this->check_menu_access_rights($email, 2, 'create');
                $menu_group_write_access_right = $this->check_menu_access_rights($email, 2, 'write');

                if(empty($reference_id) && $menu_group_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_group" name="menu_group" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="number" class="form-control" id="menu_group_order_sequence" name="menu_group_order_sequence" min="0">
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $menu_group_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_group_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="menu_group" name="menu_group" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                            <input type="number" class="form-control d-none form-edit" id="menu_group_order_sequence" name="menu_group_order_sequence" min="0">
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_group_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="menu-group-form" method="post" action="#">
                            <input type="hidden" id="menu_group_id" name="menu_group_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
            case 'menu item form':
                $menu_item_create_access_right = $this->check_menu_access_rights($email, 3, 'create');
                $menu_item_write_access_right = $this->check_menu_access_rights($email, 3, 'write');

                if(empty($reference_id) && $menu_item_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="number" class="form-control" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <select class="form-control select2" name="menu_group_id" id="menu_group_id">
                                                <option value="0">--</option>
                                                '. $this->generate_menu_group_options() .'
                                            </select>
                                        </div>
                                        <label class="col-lg-2 col-form-label">URL</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                        <div class="col-lg-4">
                                            <select class="form-control select2" name="parent_id" id="parent_id">
                                                <option value="0">--</option>
                                                '. $this->generate_menu_item_options() .'
                                            </select>
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $menu_item_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_name_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                            <input type="number" class="form-control d-none form-edit" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_group_id_label"></div>
                                            <div class="d-none form-edit">
                                                <select class="form-control select2" name="menu_group_id" id="menu_group_id">
                                                    <option value="">--</option>
                                                    '. $this->generate_menu_group_options() .'
                                                </select>
                                            </div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">URL</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_item_url_label"></div>
                                            <input type="text" class="form-control d-none form-edit" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_icon_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="parent_id_label"></label>
                                            <div class="d-none form-edit">
                                                <select class="form-control select2 d-none form-edit" name="parent_id" id="parent_id">
                                                    <option value="">--</option>
                                                    '. $this->generate_menu_item_options() .'
                                                </select>
                                            </div>
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_group_id_label"></div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">URL</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_item_url_label"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_icon_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="parent_id_label"></label>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="menu-item-form" method="post" action="#">
                            <input type="hidden" id="menu_item_id" name="menu_item_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
            case 'file type form':
                $file_type_create_access_right = $this->check_menu_access_rights($email, 6, 'create');
                $file_type_write_access_right = $this->check_menu_access_rights($email, 6, 'write');

                if(empty($reference_id) && $file_type_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Type <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="file_type_name" name="file_type_name" maxlength="100" autocomplete="off">
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $file_type_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Type <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-10">
                                            <label class="col-form-label form-details fw-normal" id="file_type_name_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="file_type_name" name="file_type_name" maxlength="100" autocomplete="off">
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Type</label>
                                        <div class="col-lg-10">
                                            <label class="col-form-label form-details fw-normal" id="file_type_name_label"></label>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="file-type-form" method="post" action="#">
                            <input type="hidden" id="file_type_id" name="file_type_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
        }

        return $form;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_modal
    # Purpose    : generates modal based on generation type.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_modal($generation_type, $form_id, $modal_id, $modal_title, $modal_size = 'R', $is_scrollable = true, $has_submit_button = true){
        $modal_size = $this->get_modal_size($modal_size);
        $is_scrollable = $this->check_modal_scrollable($is_scrollable);
        $modal_content = $this->generate_modal_content($generation_type, $form_id);

        if($has_submit_button == 1){
            $button = '<button type="submit" class="btn btn-primary" id="submit-form" form="'. $form_id .'">Submit</button>';
        }
        else{
            $button = '';
        }

        $modal = '<div id="'. $modal_id .'" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-'. $modal_id .'" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered '. $is_scrollable .' '. $modal_size .'" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal-'. $modal_id .'-title">'. $modal_title .'</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">'. $modal_content .'</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      '. $button .'
                    </div>
                  </div>
                </div>
              </div>';

        return $modal;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_modal_content
    # Purpose    : generates modal content based on generation type.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_modal_content($generation_type, $form_id) {
        $form = '';

        switch ($generation_type){
            case 'menu item form':
                $form .= '<form id="'. $form_id .'" method="post" action="#">
                            <div class="form-group">
                                <label class="form-label" for="menu_item_name">Menu Item Name <span class="text-danger">*</span></label>
                                <input type="hidden" id="menu_item_id" name="menu_item_id">
                                <input type="text" class="form-control" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="menu_item_order_sequence">Order Sequence <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="menu_item_url">URL</label>
                                <input type="text" class="form-control" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="menu_item_icon">Menu Item Icon</label>
                                <input type="text" class="form-control" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Parent Menu Item</label>
                                <select class="form-control modal-select2" name="parent_id" id="parent_id">
                                    <option value="0">--</option>
                                    '. $this->generate_menu_item_options() .'
                                </select>
                            </div>
                        </form>';
            break;
            case 'assign menu item role access form':
                $form .= '<form id="'. $form_id .'" method="post" action="#">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="assign-menu-item-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                        <thead>
                                        <tr>
                                            <th class="all">Role</th>
                                            <th class="all">Read</th>
                                            <th class="all">Write</th>
                                            <th class="all">Create</th>
                                            <th class="all">Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>';
            break;
            case 'file extension form':
                $form .= '<form id="'. $form_id .'" method="post" action="#">
                            <div class="form-group">
                                <label class="form-label" for="file_extension_name">File Extension Name <span class="text-danger">*</span></label>
                                <input type="hidden" id="file_extension_id" name="file_extension_id">
                                <input type="text" class="form-control" id="file_extension_name" name="file_extension_name" maxlength="100" autocomplete="off">
                            </div>
                        </form>';
            break;
        }

        return $form;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Generate options methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_menu_item_options
    # Purpose    : Generates menu item options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_menu_item_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('SELECT menu_item_id, menu_item_name FROM menu_item ORDER BY menu_item_name');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $menu_item_id = $row['menu_item_id'];
                        $menu_item_name = $row['menu_item_name'];
    
                        $option .= "<option value='". htmlspecialchars($menu_item_id, ENT_QUOTES) ."'>". htmlspecialchars($menu_item_name, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_menu_group_options
    # Purpose    : Generates menu group options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_menu_group_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('SELECT menu_group_id, menu_group_name FROM menu_groups ORDER BY menu_group_name');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $menu_group_id = $row['menu_group_id'];
                        $menu_group_name = $row['menu_group_name'];
    
                        $option .= "<option value='". htmlspecialchars($menu_group_id, ENT_QUOTES) ."'>". htmlspecialchars($menu_group_name, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

}

?>