<?php
session_start();

require('../config/config.php');
require('../classes/global-class.php');
require('../classes/administrator-class.php');

if(isset($_POST['transaction']) && !empty($_POST['transaction'])){
    $transaction = htmlspecialchars($_POST['transaction'], ENT_QUOTES, 'UTF-8');
    $global_class = new Global_Class();
    $administrator_class = new Administrator;

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
    }

}

?>