<?php
session_start();

require('../config/config.php');
require('../classes/global-class.php');
require('../classes/administrator-class.php');

if(isset($_POST['transaction']) && !empty($_POST['transaction'])){
    $transaction = htmlspecialchars($_POST['transaction'], ENT_QUOTES, 'UTF-8');
    $global_class = new Global_Class();
    $administrator_class = new Administrator_Class;

    $system_date = date('Y-m-d');
    $current_time = date('H:i:s');
    $error = '';
    $response = [];

    switch ($transaction) {
        # Authenticate
        case 'authenticate':
            if(isset($_POST['password']) && !empty($_POST['password'])) {
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
                $authenticate = $administrator_class->authenticate($email, $password);

                if(!$global_class->validate_email($email)){
                    $response[] = [
                        'RESPONSE' => 'Invalid Email'
                    ];

                    echo json_encode($response);
                    
                    exit;
                }
                
                switch($authenticate) {
                    case 'Authenticated':
                        $_SESSION['logged_in'] = 1;
                        $_SESSION['email'] = $email;
                        
                        if(isset($_POST['remember_me'])) {
                            $cookie_name = 'remember_me';
                            $cookie_value = $email;
                            $cookie_expiry = time() + (30 * 24 * 60 * 60);
                            setcookie($cookie_name, $cookie_value, $cookie_expiry, '/');
                        }
                        
                        $response[] = [
                            'RESPONSE' => $authenticate
                        ];
                        break;
                    case 'Password Expired':
                        $response[] = [
                            'RESPONSE' => $authenticate,
                            'EMAIL' => $global_class->encrypt_data($email)
                        ];
                        break;
                    default:
                        $response[] = [
                            'RESPONSE' => $authenticate
                        ];
                        break;
                }
                
                echo json_encode($response);
            }
            
        break;
        # -------------------------------------------------------------

        # Reset password
        case 'reset password':
            if (isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
                $password = htmlspecialchars($global_class->encrypt_data($_POST['password']), ENT_QUOTES, 'UTF-8');
                $password_expiry_date = $global_class->format_date('Y-m-d', htmlspecialchars($system_date, ENT_QUOTES, 'UTF-8'), '+6 months');
            
                $user_details = $administrator_class->get_user_details(null, $email);
                
                if (!empty($user_details)) {
                    $user_id = $user_details[0]['EXTERNAL_ID'];
                    $check_user_status = $administrator_class->check_user_status($user_id, $email);
            
                    if ($check_user_status) {
                        $check_password_history_exist = $administrator_class->check_password_history_exist($user_id, $email, $_POST['password']);
            
                        if ($check_password_history_exist == 0) {
                            $update_user_password = $administrator_class->update_user_password($user_id, $email, $password, $password_expiry_date);
            
                            if ($update_user_password) {
                                $insert_password_history = $administrator_class->insert_password_history($user_id, $email, $password);
            
                                if ($insert_password_history) {
                                    $update_user_login_attempt = $administrator_class->update_user_login_attempt($user_id, $email, 0, null);
            
                                    if ($update_user_login_attempt) {
                                        echo 'Updated';
                                    }
                                    else {
                                        echo $update_user_login_attempt;
                                    }
                                }
                                else {
                                    echo $insert_password_history;
                                }
                            }
                            else {
                                echo $update_user_password;
                            }
                        }
                        else {
                            echo 'Password Exist';
                        }
                    }
                    else {
                        echo 'Inactive';
                    }
                }
                else {
                    echo 'Not Found';
                }
            }            
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Submit transactions
        # -------------------------------------------------------------

        # Submit user ui customization setting
        case 'submit user ui customization setting':
            if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['type']) && !empty($_POST['type']) && isset($_POST['customization_value'])){
                $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $administrator_class->check_user_exist(null, $email);
     
                if($check_user_exist === 1){
                    $check_user_status = $administrator_class->check_user_status(null, $email);
    
                    if($check_user_status){
                        $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
                        $customization_value = htmlspecialchars($_POST['customization_value'], ENT_QUOTES, 'UTF-8');

                        $user_details = $administrator_class->get_user_details(null, $email);
                        $user_id = $user_details[0]['EXTERNAL_ID'];

                        $check_ui_customization_setting_exist = $administrator_class->check_ui_customization_setting_exist($user_id, $email);
        
                        if($check_ui_customization_setting_exist > 0){
                            $update_ui_customization_setting = $administrator_class->update_ui_customization_setting($user_id, $email, $type, $customization_value, $user_id);
                
                            if($update_ui_customization_setting){
                                echo 'Updated';
                            }
                            else{
                                echo $update_ui_customization_setting;
                            }
                        }
                        else{
                            $insert_ui_customization_setting = $administrator_class->insert_ui_customization_setting($user_id, $email, $type, $customization_value, $user_id);
                
                            if($insert_ui_customization_setting){
                                echo 'Updated';
                            }
                            else{
                                echo $insert_ui_customization_setting;
                            }
                        }
                    }
                    else{
                        echo 'Inactive User';
                    }
                }
                else{
                    echo 'User Not Found';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Get details transactions
        # -------------------------------------------------------------

        case 'ui customization settings details':
            if(isset($_POST['email']) && !empty($_POST['email'])){
                $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');

                $ui_customization_setting_details = $global_class->get_ui_customization_setting_details(null, $email);
    
                $response[] = array(
                    'THEME_CONTRAST' => $ui_customization_setting_details[0]['THEME_CONTRAST'] ?? 'false',
                    'CAPTION_SHOW' => $ui_customization_setting_details[0]['CAPTION_SHOW'] ?? 'true',
                    'PRESET_THEME' => $ui_customization_setting_details[0]['PRESET_THEME'] ?? 'preset-1',
                    'DARK_LAYOUT' => $ui_customization_setting_details[0]['DARK_LAYOUT'] ?? 'false',
                    'RTL_LAYOUT' => $ui_customization_setting_details[0]['RTL_LAYOUT'] ?? 'false',
                    'BOX_CONTAINER' => $ui_customization_setting_details[0]['BOX_CONTAINER'] ?? 'false'
                );
    
                echo json_encode($response);
            }
        break;
    }

}

?>