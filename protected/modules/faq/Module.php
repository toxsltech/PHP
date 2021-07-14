<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.ToXSL.com >
 *@author	 : Shiv Charan Panjeta < shiv@ToXSL.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\faq;

use app\components\TController;
use app\components\TModule;
use app\models\User;
use app\modules\faq\models\Faq;

/**
 * faq module definition class
 */
class Module extends TModule
{

    const NAME = 'faq';

    public $controllerNamespace = 'app\modules\faq\controllers';

    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'FAQs'), '/faq/faq/index', 'question-circle', Module::isAdmin()||User::isSubAdmin(), [ // TController::addMenu(\Yii::t('app', 'Faq'), '/faq/faq', 'lock', Module::isAdmin()),
        ]);
    }
    public static function beforeDelete($user_id)
    {
        Faq::deleteRelatedAll([
            'created_by_id' => $user_id
        ]);
    }
    
     public static function dbFile()
     {
     return __DIR__ . '/db/install.sql';
     }
     
}
