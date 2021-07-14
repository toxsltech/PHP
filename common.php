<?php
date_default_timezone_get();

define('PROJECT_ID', 'tracol_food_package_delivery');
define('PROJECT_NAME', 'TracolFoodPackageDelivery');
defined('DATECHECK') or define('DATECHECK', "2021-01-30");
if (is_file('.dev')) {
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_ENV') or define('YII_ENV', 'prod');
}

if (YII_ENV == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    //defined('FILE_MODE') or define('FILE_MODE', 0777);
}

defined('VERSION') or define('VERSION', "1.0");
defined('FILE_MODE') or define('FILE_MODE', 0755);
if (isset($_COOKIE['codeception'])) {
    define('YII_TEST', true);
}

defined('BASE_PATH') or define('BASE_PATH', __DIR__ . '/protected');
defined('PROTECTED_PATH') or define('PROTECTED_PATH', __DIR__ . '/protected');

// db config path setting
defined('UPLOAD_PATH') or define('UPLOAD_PATH', BASE_PATH . '/uploads/');
defined('DB_CONFIG_PATH') or define('DB_CONFIG_PATH', BASE_PATH . '/config/');
defined('DB_BACKUP_FILE_PATH') or define('DB_BACKUP_FILE_PATH', BASE_PATH . '/db/');
define('RUNTIME_PATH', BASE_PATH . '/runtime');
defined('DB_CONFIG_FILE_PATH') or define('DB_CONFIG_FILE_PATH', DB_CONFIG_PATH . 'db.php');
defined('MAILER_CONFIG_FILE_PATH') or define('MAILER_CONFIG_FILE_PATH', DB_CONFIG_PATH . 'mailer-' . YII_ENV . '.php');
defined('STRIPE_API_VERSION') or define('STRIPE_API_VERSION', '2020-08-27');
defined('VENDOR_PATH') or define('VENDOR_PATH', __DIR__ . '/vendor/');
defined('TMP_PATH') or define('TMP_PATH', sys_get_temp_dir());

// create directories if required
if (! is_dir(UPLOAD_PATH))
    @mkdir(UPLOAD_PATH, FILE_MODE, true);
if (! is_dir(DB_CONFIG_PATH))
    @mkdir(DB_CONFIG_PATH);
if (! is_dir(DB_BACKUP_FILE_PATH))
    @mkdir(DB_BACKUP_FILE_PATH);
if (! is_dir(RUNTIME_PATH))
    @mkdir(RUNTIME_PATH, FILE_MODE, true);
if (! is_dir(__DIR__ . '/assets'))
    @mkdir(__DIR__ . '/assets', FILE_MODE, true);
