<?php
    /*$activated_interface_setting_details = $api->get_activated_interface_setting_details();
    
    $login_background = $api->check_image($activated_interface_setting_details[0]['LOGIN_BACKGROUND'] ?? null, 'login background');
    $login_logo = $api->check_image($activated_interface_setting_details[0]['LOGIN_LOGO'] ?? null, 'login logo');
    $menu_logo = $api->check_image($activated_interface_setting_details[0]['MENU_LOGO'] ?? null, 'menu logo');
    $favicon = $api->check_image($activated_interface_setting_details[0]['FAVICON'] ?? null, 'favicon');*/

    $login_background = $api->check_image(null, 'login background');
    $login_logo = $api->check_image(null, 'login logo');
    $menu_logo = $api->check_image(null, 'menu logo');
    $favicon = $api->check_image(null, 'favicon');
?>