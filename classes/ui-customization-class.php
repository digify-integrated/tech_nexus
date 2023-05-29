<?php

class UI_Customization_Class {
    private $global;

    public function __construct() {
        $this->global = new Global_Class();
    }

    # -------------------------------------------------------------
    #   Check data exist methods
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
}

?>