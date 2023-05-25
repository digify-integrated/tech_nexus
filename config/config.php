<?php
# -------------------------------------------------------------
#
# Name       : date_default_timezone_set
# Purpose    : This sets the default timezone to PH.
#
# -------------------------------------------------------------

date_default_timezone_set('Asia/Manila');

# -------------------------------------------------------------
#
# Name       : Default user interface image
# Purpose    : This is the serves as the default images for the user interface.
#
# -------------------------------------------------------------

define('DEFAULT_AVATAR_IMAGE', './assets/images/default/default-avatar.png');
define('DEFAULT_BG_IMAGE', './assets/images/default/default-bg.jpg');
define('DEFAULT_LOGIN_LOGO_IMAGE', './assets/images/default/default-login-logo.svg');
define('DEFAULT_MENU_LOGO_IMAGE', './assets/images/default/default-menu-logo.png');
define('DEFAULT_MODULE_ICON_IMAGE', './assets/images/default/default-module-icon.svg');
define('DEFAULT_FAVICON_IMAGE', './assets/images/default/default-favicon.svg');
define('DEFAULT_COMPANY_LOGO_IMAGE', './assets/images/default/default-company-logo.png');
define('DEFAULT_PLACEHOLDER_IMAGE', './assets/images/default/default-image-placeholder.png');

# -------------------------------------------------------------
#
# Name       : Default upload file path
# Purpose    : This is the serves as the default upload file path.
#
# -------------------------------------------------------------

define('DEFAULT_IMAGES_FULL_PATH_FILE', '/dss/assets/images/');
define('DEFAULT_IMAGES_RELATIVE_PATH_FILE', './assets/images/');
define('DEFAULT_EMPLOYEE_FULL_PATH_FILE', '/dss/assets/employee/');
define('DEFAULT_EMPLOYEE_RELATIVE_PATH_FILE', './assets/employee/');

# -------------------------------------------------------------
#
# Name       : Default environment configuration
# Purpose    : This servers as the environment to be added on httpd.conf file
#
# -------------------------------------------------------------

#SetEnv DB_HOST localhost
#SetEnv DB_NAME nexusdb
#SetEnv DB_USER nexus
#SetEnv DB_PASS qKHJpbkgC6t93nQr
#SetEnv ENCRYPTION_KEY DmXUT96VLxqENzLZks4M

?>