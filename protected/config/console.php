<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
if (php_sapi_name() != "cli") {
    echo 'Please run this file from command line interface !!!';
    exit();
}

$params = require (__DIR__ . '/params.php');

$config = [
    'id' => PROJECT_ID,
    'name' => PROJECT_NAME,
    'basePath' => PROTECTED_PATH,
    'bootstrap' => [
        'log'
    ],
    'vendorPath' => VENDOR_PATH,
    'timeZone' => date_default_timezone_get(),
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'clear' => 'app\commands\ClearController',
        'module' => 'app\components\commands\ModuleController',
        'user' => 'app\components\commands\UserController'
    ],
    'components' => [
        'urlManager' => [
            'class' => 'app\components\TUrlManager',
            'baseUrl' => (YII_ENV == 'dev') ? 'http://localhost/jitalent-tms-web2-1295/' : 'http://192.168.10.25/',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],
        'settings' => [
            'class' => 'app\base\Settings'
        ],
        'formatter' => [
            'class' => 'app\components\formatter\TFormatter',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
            'defaultTimeZone' => date_default_timezone_get(),
            'datetimeFormat' => 'php:Y-m-d h:i:s A',
            'dateFormat' => 'php:Y-m-d'
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning'
                    ]
                ]
            ]
        ],
        'mailer' => require (MAILER_CONFIG_FILE_PATH)
    ],
    'params' => $params,
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ]
];

if (file_exists(DB_CONFIG_FILE_PATH)) {
    $config['components']['db'] = require (DB_CONFIG_FILE_PATH);
}
$config['modules']['backup'] = [
    'class' => 'app\modules\backup\Module'
];

$config['modules']['installer'] = [
    'class' => 'app\modules\installer\Module',
    'sqlfile' => [
        DB_BACKUP_FILE_PATH . '/install.sql'
    ]
];

return $config;
