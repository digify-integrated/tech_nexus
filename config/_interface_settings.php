<?php
    /*$activated_interface_setting_details = $global_class->get_activated_interface_setting_details();
    
    $login_background = $global_class->check_image($activated_interface_setting_details[0]['LOGIN_BACKGROUND'] ?? null, 'login background');
    $login_logo = $global_class->check_image($activated_interface_setting_details[0]['LOGIN_LOGO'] ?? null, 'login logo');
    $menu_logo = $global_class->check_image($activated_interface_setting_details[0]['MENU_LOGO'] ?? null, 'menu logo');
    $favicon = $global_class->check_image($activated_interface_setting_details[0]['FAVICON'] ?? null, 'favicon');*/

    $login_background = $global_class->check_image(null, 'login background');
    $login_logo = $global_class->check_image(null, 'login logo');
    $menu_logo = $global_class->check_image(null, 'menu logo');
    $favicon = $global_class->check_image(null, 'favicon');
?>