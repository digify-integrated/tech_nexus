<?php
session_start();
require('config/config.php');
require('classes/api.php');

if(isset($_POST['transaction']) && !empty($_POST['transaction'])){
    $transaction = htmlspecialchars($_POST['transaction'], ENT_QUOTES, 'UTF-8');
    $api = new Api;
    $system_date = date('Y-m-d');
    $current_time = date('H:i:s');
    $error = '';

    switch ($transaction) {
        # Authenticate
        case 'authenticate':
            if(isset($_POST['password']) && !empty($_POST['password'])){
                $response = array();
                $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
                $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    
                $authenticate = $api->authenticate($email, $password);
                
                if($authenticate === 'Authenticated'){                    
                    $_SESSION['logged_in'] = 1;
                    $_SESSION['email'] = $email;

                    if(isset($_POST['remember_me'])){
                        $cookie_name = 'remember_me';
                        $cookie_value = $email;
                        $cookie_expiry = time() + (30 * 24 * 60 * 60);
                        
                        setcookie($cookie_name, $cookie_value, $cookie_expiry, '/');
                    }

                    $response[] = array(
                        'RESPONSE' => $authenticate
                    );
                }
                else if($authenticate === 'Password Expired'){
                    $response[] = array(
                        'RESPONSE' => $authenticate,
                        'EMAIL' => $api->encrypt_data($email)
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $authenticate
                    );
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Reset password
        case 'reset password':
            if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])){
                $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
                $password = htmlspecialchars($api->encrypt_data($_POST['password']), ENT_QUOTES, 'UTF-8');
                $password_expiry_date = $api->format_date('Y-m-d', htmlspecialchars($system_date, ENT_QUOTES, 'UTF-8'), '+6 months');
    
                $check_user_exist = $api->check_user_exist(null, $email);
     
                if($check_user_exist === 1){
                    $user_details = $api->get_user_details(null, $email);
                    $user_id = $user_details[0]['USER_ID'];

                    $check_user_status = $api->check_user_status($user_id, $email);

                    if($check_user_status){
                        $check_password_history_exist = $api->check_password_history_exist($user_id, $email, $_POST['password']);

                        if($check_password_history_exist == 0){
                            $update_user_password = $api->update_user_password($user_id, $email, $password, $password_expiry_date);
        
                            if($update_user_password){
                                $insert_password_history = $api->insert_password_history($user_id, $email, $password);
        
                                if($insert_password_history){
                                    $update_user_login_attempt = $api->update_user_login_attempt($user_id, $email, 0, null);

                                    if($update_user_login_attempt){
                                        echo 'Updated';
                                    }
                                    else{
                                        echo $update_user_login_attempt;
                                    }
                                }
                                else{
                                    echo $insert_password_history;
                                }
                            }
                            else{
                                echo $update_user_password;
                            }
                        }
                        else{
                            echo 'Password Exist';
                        }
                    }
                    else{
                        echo 'Inactive';
                    }
                }
                else{
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

                $check_user_exist = $api->check_user_exist(null, $email);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email);
    
                    if($check_user_status){
                        $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
                        $customization_value = htmlspecialchars($_POST['customization_value'], ENT_QUOTES, 'UTF-8');

                        $user_details = $api->get_user_details(null, $email);
                        $user_id = $user_details[0]['USER_ID'];

                        $check_ui_customization_setting_exist = $api->check_ui_customization_setting_exist($user_id, $email);
        
                        if($check_ui_customization_setting_exist > 0){
                            $update_ui_customization_setting = $api->update_ui_customization_setting($user_id, $email, $type, $customization_value, $user_id);
                
                            if($update_ui_customization_setting){
                                echo 'Updated';
                            }
                            else{
                                echo $update_ui_customization_setting;
                            }
                        }
                        else{
                            $insert_ui_customization_setting = $api->insert_ui_customization_setting($user_id, $email, $type, $customization_value, $user_id);
                
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

        # Submit menu group
        case 'submit menu group':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_group_id']) && isset($_POST['menu_group']) && !empty($_POST['menu_group']) && isset($_POST['menu_group_order_sequence'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email_account);
    
                    if($check_user_status){
                        $user_details = $api->get_user_details(null, $email_account);
                        $user_id = $user_details[0]['USER_ID'];

                        $menu_group_id = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');
                        $menu_group = htmlspecialchars($_POST['menu_group'], ENT_QUOTES, 'UTF-8');
                        $order_sequence = htmlspecialchars($_POST['menu_group_order_sequence'], ENT_QUOTES, 'UTF-8');

                        $check_menu_groups_exist = $api->check_menu_groups_exist($menu_group_id);
        
                        if($check_menu_groups_exist > 0){
                            $update_menu_groups = $api->update_menu_groups($menu_group_id, $menu_group, $order_sequence, $user_id);
                
                            if($update_menu_groups){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_menu_groups
                                );
                            }
                        }
                        else{
                            $insert_menu_groups = $api->insert_menu_groups($menu_group, $order_sequence, $user_id);
                
                            if($insert_menu_groups[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'MENU_GROUP_ID' => $insert_menu_groups[0]['MENU_GROUP_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_menu_groups
                                );
                            }
                        }       
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'Inactive User'
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'User Not Found'
                    );
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit menu item
        case 'submit menu item':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id']) && isset($_POST['menu_item_id']) && isset($_POST['menu_item_name']) && !empty($_POST['menu_item_name']) && isset($_POST['menu_item_icon']) && isset($_POST['menu_item_order_sequence']) && isset($_POST['menu_item_url']) && isset($_POST['parent_id'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email_account);
    
                    if($check_user_status){
                        $user_details = $api->get_user_details(null, $email_account);
                        $user_id = $user_details[0]['USER_ID'];

                        $menu_group_id = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');
                        $menu_item_id = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
                        $menu_item_name = htmlspecialchars($_POST['menu_item_name'], ENT_QUOTES, 'UTF-8');
                        $order_sequence = htmlspecialchars($_POST['menu_item_order_sequence'], ENT_QUOTES, 'UTF-8');
                        $menu_item_url = htmlspecialchars($_POST['menu_item_url'], ENT_QUOTES, 'UTF-8');
                        $parent_id = htmlspecialchars($_POST['parent_id'], ENT_QUOTES, 'UTF-8');
                        $menu_item_icon = htmlspecialchars($_POST['menu_item_icon'], ENT_QUOTES, 'UTF-8');

                        $check_menu_item_exist = $api->check_menu_item_exist($menu_item_id);
        
                       if($check_menu_item_exist > 0){
                            $update_menu_item = $api->update_menu_item($menu_item_id, $menu_item_name, $menu_group_id, $menu_item_url, $parent_id, $menu_item_icon, $order_sequence, $user_id);
                
                            if($update_menu_item){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_menu_item
                                );
                            }
                        }
                        else{
                            $insert_menu_item = $api->insert_menu_item($menu_item_name, $menu_group_id, $menu_item_url, $parent_id, $menu_item_icon, $order_sequence, $user_id);
                
                            if($insert_menu_item[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'MENU_ITEM_ID' => $insert_menu_item[0]['MENU_ITEM_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_menu_item
                                );
                            }
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'Inactive User'
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'User Not Found'
                    );
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit menu item role access
        case 'submit menu item role access':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])  && isset($_POST['permission'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');
                $menu_item_id = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
                $permissions = explode(',', $_POST['permission']);

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email_account);
    
                    if($check_user_status){
                        foreach ($permissions as $permission) {
                            $parts = explode('-', $permission);
                            $role_id = $parts[0];
                            $access_type = $parts[1];
                            $access = $parts[2];
        
                            $check_role_menu_item_access_right_exist = $api->check_role_menu_item_access_right_exist($menu_item_id, $role_id);
        
                            if($check_role_menu_item_access_right_exist == 1){
                                $update_role_menu_item_access_right = $api->update_role_menu_item_access_right($menu_item_id, $role_id, $access_type, $access);
        
                                if(!$update_role_menu_item_access_right){
                                    $error = $update_role_menu_item_access_right;
                                    break;
                                }
                            }
                            else{
                                $insert_role_menu_item_access_right = $api->insert_role_menu_item_access_right($menu_item_id, $role_id);
                             
                                if($insert_role_menu_item_access_right){
                                    $update_role_menu_item_access_right = $api->update_role_menu_item_access_right($menu_item_id, $role_id, $access_type, $access);
        
                                    if(!$update_role_menu_item_access_right){
                                        $error = $update_role_menu_item_access_right;
                                        break;
                                    }
                                }
                                else{
                                    $error = $insert_role_menu_item_access_right;
                                    break;
                                }
                            }
                        }
        
                        if(empty($error)){
                            echo 'Updated';
                        }
                        else{
                            echo $error;
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
        #   Delete transactions
        # -------------------------------------------------------------

        # Delete menu group
        case 'delete menu group':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email_account);
    
                    if($check_user_status){
                        $menu_group_id = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');

                        $check_menu_groups_exist = $api->check_menu_groups_exist($menu_group_id);
        
                        if($check_menu_groups_exist > 0){
                            $delete_menu_groups = $api->delete_menu_groups($menu_group_id);
                
                            if($delete_menu_groups){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_menu_groups;
                            }
                        }
                        else{
                            echo 'Not Found';
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

        # Delete multiple menu group
        case 'delete multiple menu group':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email_account);
    
                    if($check_user_status){
                        $menu_group_ids = $_POST['menu_group_id'];

                        foreach($menu_group_ids as $menu_group_id){
                            $delete_menu_groups = $api->delete_menu_groups($menu_group_id);
                                                
                            if(!$delete_menu_groups){
                                $error = $delete_menu_groups;
                                break;
                            }
                        }

                        if(empty($error)){
                            echo 'Deleted';
                        }
                        else{
                            echo $error;
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

        # Delete menu item
        case 'delete menu item':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email_account);
    
                    if($check_user_status){
                        $menu_item_id = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                        $check_menu_item_exist = $api->check_menu_item_exist($menu_item_id);
        
                        if($check_menu_item_exist > 0){
                            $delete_menu_item = $api->delete_menu_item($menu_item_id);
                
                            if($delete_menu_item){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_menu_item;
                            }
                        }
                        else{
                            echo 'Not Found';
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

        # Delete multiple menu item
        case 'delete multiple menu item':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $check_user_status = $api->check_user_status(null, $email_account);
    
                    if($check_user_status){
                        $menu_item_ids = $_POST['menu_item_id'];

                        foreach($menu_item_ids as $menu_item_id){
                            $delete_menu_item = $api->delete_menu_item($menu_item_id);
                                                
                            if(!$delete_menu_item){
                                $error = $delete_menu_item;
                                break;
                            }
                        }

                        if(empty($error)){
                            echo 'Deleted';
                        }
                        else{
                            echo $error;
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
        #   Duplicate transactions
        # -------------------------------------------------------------

        # Duplicate menu group
        case 'duplicate menu group':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $user_details = $api->get_user_details(null, $email_account);
                    $user_id = $user_details[0]['USER_ID'];

                    $check_user_status = $api->check_user_status($user_id, $email_account);
    
                    if($check_user_status){
                        $menu_group_id = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');

                        $check_menu_groups_exist = $api->check_menu_groups_exist($menu_group_id);
        
                        if($check_menu_groups_exist > 0){
                            $duplicate_menu_groups = $api->duplicate_menu_groups($menu_group_id, $user_id);
                
                            if($duplicate_menu_groups[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Duplicated',
                                    'MENU_GROUP_ID' => $duplicate_menu_groups[0]['MENU_GROUP_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $duplicate_menu_groups
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => 'Not Found'
                            );
                        }       
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'Inactive User'
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'User Not Found'
                    );
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Duplicate menu item
        case 'duplicate menu item':
            if(isset($_POST['email_account']) && !empty($_POST['email_account']) && isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $email_account = htmlspecialchars($_POST['email_account'], ENT_QUOTES, 'UTF-8');

                $check_user_exist = $api->check_user_exist(null, $email_account);
     
                if($check_user_exist === 1){
                    $user_details = $api->get_user_details(null, $email_account);
                    $user_id = $user_details[0]['USER_ID'];

                    $check_user_status = $api->check_user_status($user_id, $email_account);
    
                    if($check_user_status){
                        $menu_item_id = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                        $check_menu_item_exist = $api->check_menu_item_exist($menu_item_id);
        
                        if($check_menu_item_exist > 0){
                            $duplicate_menu_item = $api->duplicate_menu_item($menu_item_id, $user_id);
                
                            if($duplicate_menu_item[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Duplicated',
                                    'MENU_ITEM_ID' => $duplicate_menu_item[0]['MENU_ITEM_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $duplicate_menu_item
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => 'Not Found'
                            );
                        }       
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'Inactive User'
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'User Not Found'
                    );
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Unlock transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Lock transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Activate transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Deactivate transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Archive transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Unarchive transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Start transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Stop transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Cancel transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   For approval transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   For recommendation transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Reject transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Recommend transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Pending transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Tag for approval transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Approve transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Notification transactions
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Get details transactions
        # -------------------------------------------------------------

        case 'ui customization settings details':
            if(isset($_POST['email']) && !empty($_POST['email'])){
                $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');

                $ui_customization_setting_details = $api->get_ui_customization_setting_details(null, $email);
    
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

        case 'menu groups details':
            if(isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])){
                $menu_group_id = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');

                $menu_groups_details = $api->get_menu_groups_details($menu_group_id);
    
                $response[] = array(
                    'MENU_GROUP_NAME' => $menu_groups_details[0]['MENU_GROUP_NAME'],
                    'ORDER_SEQUENCE' => $menu_groups_details[0]['ORDER_SEQUENCE']
                );
    
                echo json_encode($response);
            }
        break;

        case 'modal menu item details':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menu_item_id = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $menu_item_details = $api->get_menu_item_details($menu_item_id);
    
                $response[] = array(
                    'MENU_ITEM_NAME' => $menu_item_details[0]['MENU_ITEM_NAME'],
                    'MENU_ITEM_URL' => $menu_item_details[0]['MENU_ITEM_URL'],
                    'PARENT_ID' => $menu_item_details[0]['PARENT_ID'],
                    'MENU_ITEM_ICON' => $menu_item_details[0]['MENU_ITEM_ICON'],
                    'ORDER_SEQUENCE' => $menu_item_details[0]['ORDER_SEQUENCE']
                );
    
                echo json_encode($response);
            }
        break;

        case 'menu item details':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                $menu_item_id = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                $menu_item_details = $api->get_menu_item_details($menu_item_id);
                $menu_group_id = $menu_item_details[0]['MENU_GROUP_ID'];
                $parent_id = $menu_item_details[0]['PARENT_ID'];
                $menu_item_url = $menu_item_details[0]['MENU_ITEM_URL'];

                $menu_group_id_encrypted = $api->encrypt_data($menu_group_id);
                $parent_id_encrypted = $api->encrypt_data($parent_id);

                $menu_groups_details = $api->get_menu_groups_details($menu_group_id);
                $menu_group_name = $menu_groups_details[0]['MENU_GROUP_NAME'] ?? null;

                $parent_menu_item_details = $api->get_menu_item_details($parent_id);
                $parent_menu_item_name = $parent_menu_item_details[0]['MENU_ITEM_NAME'] ?? null;

                if(!empty($parent_menu_item_name)){
                    $parent_name = '<a href="menu-item-form.php?id='. $parent_id_encrypted .'">'. $parent_menu_item_name .'</a>';
                }
                else{
                    $parent_name = null;
                }

                if(!empty($menu_item_url)){
                    $menu_item_url_link = '<a href="'. $menu_item_url .'">'. $menu_item_url .'</a>';
                }
                else{
                    $menu_item_url_link = null;
                }
    
                $response[] = array(
                    'MENU_ITEM_NAME' => $menu_item_details[0]['MENU_ITEM_NAME'],
                    'MENU_GROUP_ID' => $menu_group_id,
                    'MENU_GROUP_NAME' => '<a href="menu-group-form.php?id='. $menu_group_id_encrypted .'">'. $menu_group_name .'</a>',
                    'PARENT_NAME' => $parent_name,
                    'MENU_ITEM_URL_LINK' => $menu_item_url_link,
                    'MENU_ITEM_URL' => $menu_item_url,
                    'PARENT_ID' => $parent_id,
                    'MENU_ITEM_ICON' => $menu_item_details[0]['MENU_ITEM_ICON'],
                    'ORDER_SEQUENCE' => $menu_item_details[0]['ORDER_SEQUENCE']
                );
    
                echo json_encode($response);
            }
        break;
    }
}

?>