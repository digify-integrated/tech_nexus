<?php

class Global_Class{
    # @var object $db_connection The database connection
    public $db_connection = null;

    public $response = array();

    # -------------------------------------------------------------
    #   Custom methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : database_connection
    # Purpose    : Checks if database connection is opened.
    #              If not, then this method tries to open it.
    #              @return bool Success status of the
    #              database connecting process
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function database_connection(){
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
    public function backup_database($file_name){
        if ($this->database_connection()) {
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
    # Name       : validate_email
    # Purpose    : Validate if email is valid.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function validate_email($p_email) {
        $p_email = trim($p_email);

        if (empty($p_email)) {
            return 'Missing email';
        }

        if (!filter_var($p_email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email';
        }

        return true;
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
    
        $currentTimestamp = time();
        $diffSeconds = $currentTimestamp - $timestamp;
    
        $intervals = [
            'year' => 31536000,
            'month' => 2592000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1
        ];
    
        foreach ($intervals as $label => $seconds) {
            if ($diffSeconds >= $seconds) {
                $count = floor($diffSeconds / $seconds);
                return $count . ' ' . $label . ($count == 1 ? '' : 's') . ' ago';
            }
        }
    
        return 'Just Now';
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
    public function directory_checker($directory){
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true | STREAM_MKDIR_RECURSIVE)) {
                return 'Error creating directory: ' . error_get_last()['message'];
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
    #   Get methods
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
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
    
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        $uniqueId = uniqid('', true);
    
        $fileName = $prefix . $randomString . $uniqueId;
    
        return $fileName;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Generate options methods
    # -------------------------------------------------------------

}

?>