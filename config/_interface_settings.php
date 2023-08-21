<?php    
    $login_background = $systemModel->checkImage($interfaceSettingModel->getInterfaceSetting(1)['value'] ?? null, 'login background');
    $login_logo = $systemModel->checkImage($interfaceSettingModel->getInterfaceSetting(2)['value'] ?? null, 'login logo');
    $menu_logo = $systemModel->checkImage($interfaceSettingModel->getInterfaceSetting(3)['value'] ?? null, 'menu logo');
    $favicon = $systemModel->checkImage($interfaceSettingModel->getInterfaceSetting(4)['value'] ?? null, 'favicon');
?>