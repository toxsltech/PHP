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
namespace app\modules\order;
use app\components\TController;
use app\components\TModule;
use app\models\User;
/**
 * order module definition class
 */
class Module extends TModule
{
    const NAME = 'order';

    public $controllerNamespace = 'app\modules\order\controllers';
	
	//public $defaultRoute = 'order';
	


    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Orders'), '#', 'shopping-cart ', Module::isAdmin()||User::isSubAdmin(), [
            TController::addMenu(\Yii::t('app', 'Home'), '//order', 'tachometer', Module::isAdmin()||User::isSubAdmin()),
            TController::addMenu(\Yii::t('app', 'Orders'), '//order/order/index', 'tasks', Module::isAdmin()||User::isSubAdmin()),
            TController::addMenu(\Yii::t('app', 'Order Items'), '//order/item/index', 'tasks', Module::isAdmin()||User::isSubAdmin()),
        ]);
    }
    
    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }
    
    
   /* public static function getRules()
    {
        return [
            
            'order/<id:\d+>/<title>' => 'order/post/view',
           // 'order/post/<id:\d+>/<file>' => 'order/post/image',
           //'order/category/<id:\d+>/<title>' => 'order/category/type'
        
        ];
    }
    */
    
}
