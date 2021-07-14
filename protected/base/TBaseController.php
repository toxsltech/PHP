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
use app\models\UserDetail;
use yii\helpers\Url;

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
        // $this->enableCsrfValidation = false;
        if (! User::isAdmin() && ! User::isSubAdmin()) {
            if (! empty(\Yii::$app->user->identity)) {
                $id = \Yii::$app->user->identity->id;
                $getUserDetails = UserDetail::findOne([
                    'created_by_id' => $id
                ]);
                if (empty($getUserDetails)) {
                    if ($action->id != 'personal-detail') {
                        return $this->redirect(Url::to([
                            'user/personal-detail'
                        ]));
                    }
                }
            }
        }
        if (User::isAdmin() || User::isSubAdmin()) {
            $this->layout = 'main';
        } else {
            $this->layout = 'guest-main';
        }

        return parent::beforeAction($action);
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
            self::addMenu(Yii::t('app', 'Dashboard'), '//dashboard/index', 'home', User::isAdmin() || User::isSubAdmin()),
            self::addMenu(Yii::t('app', 'Habbits'), '//habbit/index/', 'tasks', User::isAdmin()),
            'Manage' => self::addMenu(Yii::t('app', 'Manage'), '#', 'tasks', User::isManager(), [
                self::addMenu(Yii::t('app', 'Users'), '//user', 'user', (User::isManager())),
                self::addMenu(Yii::t('app', 'Feeds'), '//feed/index/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Files'), '//file/index/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Home Content'), '//home-content/index/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Logger'), '//logger/log', 'key', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Login History'), '//login-history/', 'history', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Backup'), '//backup/', 'download', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Emails In Queue'), '//email-queue/', 'retweet', (! User::isGuest())),
                self::addMenu(Yii::t('app', 'Social Providers'), '//social/provider', 'retweet', true)
            ]),
            self::addMenu(Yii::t('app', 'Notifications'), '//notification/admin/notification/index/', 'envelope', User::isAdmin() || User::isSubAdmin()),
            self::addMenu(Yii::t('app', 'Packages'), '//package/index/', 'square-o', User::isAdmin() || User::isSubAdmin()),
            self::addMenu(Yii::t('app', 'Category'), '//category/index/', 'list-alt', User::isAdmin() || User::isSubAdmin()),
            self::addMenu(Yii::t('app', 'Products'), '//product/index/', 'product-hunt', User::isAdmin() || User::isSubAdmin()),
            self::addMenu(Yii::t('app', 'Setting'), '//setting/index/', 'gear', User::isAdmin() || User::isSubAdmin()),

            self::addMenu(Yii::t('app', 'Subscription Plans'), '#', 'credit-card', User::isAdmin() || User::isSubAdmin(), [
                self::addMenu(Yii::t('app', 'Subscription Plans'), '//subscription-plan/index/', 'credit-card', User::isAdmin() || User::isSubAdmin()),
                self::addMenu(Yii::t('app', 'Subscription Billings'), '//subscription-billing/index/', 'money', User::isAdmin())
            ]),
            self::addMenu(Yii::t('app', 'Videos'), '//product-video/index/', 'play', User::isAdmin() || User::isSubAdmin()),
            self::addMenu(Yii::t('app', 'Charity'), '#', 'tint', User::isManager() || User::isSubAdmin(), [
                self::addMenu(Yii::t('app', 'Charity'), '//charity/index/', 'tint', User::isAdmin() || User::isSubAdmin()),
                self::addMenu(Yii::t('app', 'Charity Details'), '//charity-detail/index/', 'info', User::isAdmin())
            ]),
            self::addMenu(Yii::t('app', 'Payments'), '//payment-transaction/index/', 'money', User::isAdmin())
        ];

        if (yii::$app->hasModule('page'))
            $nav_left['page'] = \app\modules\page\Module::subNav();
        if (yii::$app->hasModule('faq'))
            $nav_left['faq'] = \app\modules\faq\Module::subNav();
        if (yii::$app->hasModule('order'))
            $nav_left['order'] = \app\modules\order\Module::subNav();
        $this->nav_left = $nav_left;
        return $this->nav_left;
    }
}

