<?php

require('session.php');
require('config/config.php');
require('model/database-model.php');
require('model/user-model.php');
require('model/menu-group-model.php');
require('model/menu-item-model.php');
require('model/security-model.php');
require('model/system-model.php');
require('model/employee-model.php');
require('model/interface-setting-model.php');

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$menuGroupModel = new MenuGroupModel($databaseModel);
$menuItemModel = new MenuItemModel($databaseModel);
$interfaceSettingModel = new InterfaceSettingModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$securityModel = new SecurityModel();

?>