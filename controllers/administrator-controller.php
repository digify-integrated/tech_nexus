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
    }

}

?>