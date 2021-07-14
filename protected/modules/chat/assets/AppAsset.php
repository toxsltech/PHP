<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\modules\chat\assets;

use yii\web\AssetBundle;

/**
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/chat/assets';

    public $css = [
        'styles/main.css'
    ];

    public $js = [ 
        'scripts/browser-push-notification.js',
        'scripts/chat-custom.js'
    ];

    public $depends = [ /*
                          * 'yii\web\YiiAsset',
                          * 'yii\bootstrap4\BootstrapAsset',
                          * 'yii\bootstrap4\BootstrapPluginAsset'
                          */
    ];
}
