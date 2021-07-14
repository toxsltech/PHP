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
namespace app\modules\favorite;
use app\components\TController;
use app\components\TModule;
use app\models\User;
use app\modules\favorite\models\Item;
/**
 * favorite module definition class
 */
class Module extends TModule
{
    const NAME = 'favorite';

    public $controllerNamespace = 'app\modules\favorite\controllers';
	
	//public $defaultRoute = 'favorite';
	


    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Favorites'), '#', 'key ', Module::isAdmin(), [
           // TController::addMenu(\Yii::t('app', 'Home'), '//favorite', 'lock', Module::isAdmin()),
        ]);
    }
    
    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }
    
    
   /* public static function getRules()
    {
        return [
            
            'favorite/<id:\d+>/<title>' => 'favorite/post/view',
           // 'favorite/post/<id:\d+>/<file>' => 'favorite/post/image',
           //'favorite/category/<id:\d+>/<title>' => 'favorite/category/type'
        
        ];
    }
    */
    
    public static function beforeDelete($user_id)
    {
        Item::deleteRelatedAll([
            'created_by_id' => $user_id
        ]);
    }
}
