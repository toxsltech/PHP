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
namespace app\modules\page;

use app\components\TController;
use app\components\TModule;
use app\models\User;
use app\modules\page\models\Page;

/**
 * page module definition class
 */
class Module extends TModule
{

	const NAME = 'page';

	public $controllerNamespace = 'app\modules\page\controllers';

	public $defaultRoute = 'page';

	public static function subNav()
	{
	    return TController::addMenu(\Yii::t('app', 'Pages'), '#', 'file ', Module::isAdmin()||User::isSubAdmin(), [
		    TController::addMenu(\Yii::t('app', 'Home'), '//page/page/index/', 'home', Module::isAdmin()||User::isSubAdmin()),
		    TController::addMenu(\Yii::t('app', 'Privacy'), '//page/page/privacy', 'lock', Module::isAdmin()||User::isSubAdmin()),
	        TController::addMenu(\Yii::t('app', 'Term & Conditions'), '//page/page/term-and-condition', 'lock', Module::isAdmin()||User::isSubAdmin()),
	        TController::addMenu(\Yii::t('app', 'About Us'), '//page/page/about-us', 'lock', Module::isAdmin()||User::isSubAdmin()),
	        TController::addMenu(\Yii::t('app', 'Nutrition Advice'), '//page/page/nutrition-advice', 'lock', Module::isAdmin()||User::isSubAdmin()),
		]);
	}

	public static function dbFile()
	{
		return __DIR__ . '/db/install.sql';
	}
	public static function beforeDelete($user_id)
	{
	    Page::deleteRelatedAll([
	        'created_by_id' => $user_id
	    ]);
	}
	/*
	 * public static function getRules()
	 * {
	 * return [
	 *
	 * 'page/<id:\d+>/<title>' => 'page/post/view',
	 * // 'page/post/<id:\d+>/<file>' => 'page/post/image',
	 * //'page/category/<id:\d+>/<title>' => 'page/category/type'
	 *
	 * ];
	 * }
	 */
}
