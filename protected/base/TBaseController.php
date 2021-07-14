<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\base;

use app\models\User;
use Yii;
use yii\web\Controller;
use app\components\EmailVerification;

abstract class TBaseController extends Controller
{

    public $allowedIPs = [
        '127.0.0.1',
        '::1',
        '192.168.*.*'
    ];

    public $layout = '//guest-main';

    public $menu = [];

    public $top_menu = [];

    public $side_menu = [];

    public $user_menu = [];

    public $tabs_data = null;

    public $tabs_name = null;

    public $dryRun = false;

    public $assetsDir = '@webroot/assets';

    public $ignoreDirs = [];

    public $nav_left = [];

    protected $_author = '@toxsltech';

    // nav-left-medium';
    protected $_pageCaption;

    protected $_pageDescription;

    protected $_pageKeywords;

    public function beforeAction($action)
    {
        if (! parent::beforeAction($action)) {
            return false;
        }
        if (! Yii::$app->user->isGuest && ! User::isAdmin()) {
            EmailVerification::checkIfVerified();
        }
        if (! \Yii::$app->user->isGuest) {
            $this->layout = 'main';
        }
        return true;
    }

    public static function addmenu($label, $link, $icon, $visible = null, $list = null)
    {
        if (! $visible)
            return null;
        $item = [
            'label' => '<i
							class="fa fa-' . $icon . '"></i> <span>' . $label . '</span>',
            'url' => [
                $link
            ]
        ];
        if ($list != null) {
            $item['options'] = [
                'class' => 'menu-list nav-item'
            ];

            $item['items'] = $list;
        }

        return $item;
    }

    public function renderNav()
    {
        $nav_left = [

            self::addMenu(Yii::t('app', 'Dashboard'), '//', 'tachometer', (! User::isGuest())),

            self::addMenu(Yii::t('app', 'Manage Users'), '#', 'user', User::isAdmin(), [
                self::addMenu(Yii::t('app', 'Customers'), '//user/customers', 'user', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Providers'), '//user/providers', 'user', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Business'), '//user/business', 'user', User::isAdmin())
            ]),

            'Manage' => self::addMenu(Yii::t('app', 'Manage'), '#', 'tasks', User::isAdmin(), [
                self::addMenu(Yii::t('app', 'Users'), '//user', 'user', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Domain'), '//domain', 'server', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Emoji'), '//emoji', 'smile', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Language'), '//language', 'language', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Activity'), '//activity', 'trello', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Target Area'), '//target-area', 'bullseye', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Target Trades'), '//target-trade', 'tasks', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Portfolio'), '//portfolio-detail', 'briefcase', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Opinions'), '//opinion', 'user', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Comment Reason'), '//comment/reason/index', 'comment', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Network'), '//network', 'network-wired', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'News'), '//news', 'newspaper', (User::isAdmin())),
                self::addMenu(Yii::t('app', 'Feeds'), '//feed/index/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Files'), '//file/index/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'System Info'), '//site/info/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Logger'), '//logger/', 'key', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Login History'), '//login-history/', 'history', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Backup'), '//backup/', 'download', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Emails In Queue'), '//email-queue/', 'retweet', (! User::isGuest()))
            ])
        ];
        if (yii::$app->hasModule('page'))
            $nav_left['page'] = \app\modules\page\Module::subNav();

        $this->nav_left = $nav_left;
        return $this->nav_left;
    }
}

