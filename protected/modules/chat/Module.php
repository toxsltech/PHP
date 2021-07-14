<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\chat;

use app\components\TController;
use app\components\TModule;
use app\modules\chat\assets\AppAsset;

/**
 * firestorechat module definition class
 */
class Module extends TModule
{

    const NAME = 'chat';

    public $identityClass = "app\models\User";

    public $defaultUserPhoto = "user.png";

    public $defaultMediaPhoto = "user.png";

  
    public $controllerNamespace = 'app\modules\chat\controllers';

    public function init()
    {
        parent::init();
        if (! \Yii::$app->user->isGuest) {
            $loggedInUser = \Yii::$app->user->identity;
            $loggedInUser->last_action_time = date("Y-m-d H:i:s");
            $loggedInUser->updateAttributes([
                'last_action_time'
            ]);
        }
        \Yii::$app->getView()->registerAssetBundle(AppAsset::class);
    }

    public static function dbFile()
      {
        return __DIR__ . '/db/install.sql';
      }

   

    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'chat'), '/chat', 'comment ', Module::isAdmin(), [ // TController::addMenu(\Yii::t('app', 'Home'), '//firestorechat', 'lock', Module::isAdmin()),
        ]);
    }
}
