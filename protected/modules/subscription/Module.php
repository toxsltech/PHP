<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\subscription;

use app\components\TController;
use app\components\TModule;
use app\models\User;

/**
 * subscription module definition class
 */
class Module extends TModule
{

    const NAME = 'subscription';

    public $controllerNamespace = 'app\modules\subscription\controllers';

    // public $defaultRoute = 'subscription';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Subscriptions'), '#', 'money ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Plans'), '/subscription/plan/index', 'tasks', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Billing'), '/subscription/billing/index', 'lock', Module::isAdmin())
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    /*
     * public static function getRules()
     * {
     * return [
     *
     * 'subscription/<id:\d+>/<title>' => 'subscription/post/view',
     * // 'subscription/post/<id:\d+>/<file>' => 'subscription/post/image',
     * //'subscription/category/<id:\d+>/<title>' => 'subscription/category/type'
     *
     * ];
     * }
     */
}
