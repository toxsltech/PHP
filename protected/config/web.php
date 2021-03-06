<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
$params = require (__DIR__ . '/params.php');

$config = [
    'id' => PROJECT_ID,
    'name' => PROJECT_NAME,
    'basePath' => PROTECTED_PATH,
    'runtimePath' => RUNTIME_PATH,
    'bootstrap' => [
        'log',
        'session',
        'app\components\TBootstrap'
    ],
    'vendorPath' => VENDOR_PATH,
    'timeZone' => date_default_timezone_get(),
    'language' => 'en',
    'components' => [

        'request' => [
            'class' => 'app\components\TRequest',
            'trustedHosts' => [
                '192.168.0.0/20'
            ]
        ],
        'settings' => [
            'class' => 'app\components\Settings'
        ],
        'session' => [
            'class' => 'app\components\TSession'
        ],
        'cache' => [
            'class' => (YII_ENV == 'dev') ? 'yii\caching\DummyCache' : 'yii\caching\FileCache',
            'defaultDuration' => 60
        ],
        'user' => [
            'class' => 'app\components\WebUser'
        ],

        'mailer' => require (MAILER_CONFIG_FILE_PATH),
        'log' => [
            'traceLevel' => defined('YII_DEBUG') ? 3 : 0,
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

        'formatter' => [
            'class' => 'app\components\formatter\TFormatter',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
            'defaultTimeZone' => date_default_timezone_get(),
            'datetimeFormat' => 'php:Y-m-d h:i:s A',
            'dateFormat' => 'php:Y-m-d'
        ],
        'urlManager' => [
            'class' => 'app\components\TUrlManager',

            'rules' => [

                [
                    'pattern' => 'features',
                    'route' => 'feature'
                ],
                [
                    'pattern' => 'aboutus',
                    'route' => 'site/about'
                ],
                [
                    'pattern' => 'contactus',
                    'route' => 'site/contact'
                ],
                [
                    'pattern' => 'signup',
                    'route' => 'user/signup'
                ],
                '<controller:file>/<action:files>/<file>' => '<controller>/<action>',
                '<controller:[A-Za-z-]+>/<id:\d+>/<title>' => '<controller>/view',
                '<controller:[A-Za-z-]+>/<id:\d+>' => '<controller>/view',
                '<controller:[A-Za-z-]+>/<action:[A-Za-z-]+>/<id:\d+>/<title>' => '<controller>/<action>',
                '<controller:[A-Za-z-]+>/<action:[A-Za-z-]+>/<id:\d+>' => '<controller>/<action>'
            ]
        ],

        'view' => [
            'theme' => [
                'class' => 'app\components\AppTheme',
                'name' => 'new'
                // 'style'=>'green'
            ]
        ]
    ],
    'params' => $params,

    'modules' => [
        'sitemap' => [
            'class' => 'app\modules\sitemap\Module',
            'models' => [ // your models
                'app\modules\blog\models\Post',
                'app\modules\feature\models\Feature'
            ],

            'urls' => [
                [
                    'loc' => '/',
                    'priority' => '1.0'
                ],

                [
                    'loc' => '/site/about'
                ],

                [
                    'loc' => '/site/contact'
                ],
                [
                    'loc' => '/feature'
                ],
                [
                    'loc' => '/blog'
                ],
                [
                    'loc' => '/site/terms'
                ]
            ],
            'enableGzip' => true
        ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ]
];

if (file_exists(DB_CONFIG_FILE_PATH)) {

    $config['components']['db'] = require (DB_CONFIG_FILE_PATH);
} else {
    $config['modules']['installer'] = [
        'class' => 'app\modules\installer\Module',
        'sqlfile' => [
            DB_BACKUP_FILE_PATH . '/install.sql'
        ]
    ];
}

if (YII_ENV == 'dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => [
            '127.0.0.1',
            '::1',
            '192.168.10.*'
        ]
    ];

    $config['modules']['tugii'] = [
        'class' => 'app\modules\tugii\Module'
    ];
}
// Custom Logger
$config['modules']['logger'] = [
    'class' => 'app\modules\logger\Module',
    'sendLogEmailsTo' => 'support@toxsl.com'
];
$config['components']['errorHandler'] = [
    'class' => 'app\modules\logger\components\TErrorHandler'
];

if (defined('ENABLE_ERP')) {
    $config['defaultRoute'] = 'dashboard/index';
}
$config['modules']['backup'] = [
    'class' => 'app\modules\backup\Module',
    'allowDownload' => false
];

$config['modules']['comment'] = [
    'class' => 'app\modules\comment\Module'
    // 'enableRichText' => true
];
$config['modules']['shadow'] = [
    'class' => 'app\modules\shadow\Module'
];
$config['modules']['seo'] = [
    'class' => 'app\modules\seo\Module'
];
$config['modules']['blog'] = [
    'class' => 'app\modules\blog\Module'
];
$config['modules']['contact'] = [
    'class' => 'app\modules\contact\Module'
];
$config['modules']['feature'] = [
    'class' => 'app\modules\feature\Module'
];
$config['modules']['page'] = [
    'class' => 'app\modules\page\Module'
];
$config['modules']['payment'] = [
    'class' => 'app\modules\payment\Module'
];
$config['modules']['notification'] = [
    'class' => 'app\modules\notification\Module'
];
$config['modules']['translator'] = [
    'class' => 'app\modules\translator\Module'
];
$config['modules']['favorite'] = [
    'class' => 'app\modules\favorite\Module'
];
$config['modules']['api'] = [
    'class' => 'app\modules\api\Module'
];
$config['modules']['chat'] = [
    'class' => 'app\modules\chat\Module'
];
$config['modules']['subscription'] = [
    'class' => 'app\modules\subscription\Module'
];
return $config;
