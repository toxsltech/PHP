<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use Yii;

class SiteController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'contact',
                            'about',
                            'error',
                            'demo',
                            'pricing',
                            'privacy',
                            'terms',
                            'captcha'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?',
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'info'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        return [

            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction'
            ]
        ];
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        return $this->render('error', [
            'message' => $exception->getMessage(),
            'name' => 'Error'
        ]);
    }

    public function actionIndex()
    {
        $this->updateMenuItems();
        if (! \Yii::$app->user->isGuest) {
            $this->layout = 'main';
            return $this->redirect('dashboard/index');
        } else {
            $this->layout = 'guest-main';
            return $this->render('index');
        }
    }

    public function actionContact()
    {
        return $this->render('contact');
    }

    public function actionAbout()
    {
        $this->layout = 'guest-main';
        return $this->render('about');
    }

    public function actionFeatures()
    {
        $this->layout = 'guest-main';
        return $this->render('features');
    }

    public function actionPricing()
    {
        $this->layout = 'guest-main';
        return $this->render('pricing');
    }

    public function actionPrivacy()
    {
        $this->layout = 'guest-main';
        return $this->render('privacy');
    }

    public function actionTerms()
    {
        $this->layout = 'guest-main';
        return $this->render('terms');
    }

    public function actionInfo()
    {
        $this->layout = 'guest-main';
        $info['Generic'] = [
            'App Name' => Yii::$app->name,
            'App ID' => PROJECT_ID,
            'App Version' => VERSION,
            'Environment' => YII_ENV,
            'Company Name' => \Yii::$app->params['company']
            // 'Domain' => Yii::$app->params['domain']
        ];
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();

        $info['Technical'] = $pinfo;
        return $this->render('info', [
            'model' => $info
        ]);
    }

    protected function updateMenuItems($model = null)
    {
        // create static model if model is null
        switch ($this->action->id) {
            case 'add':
                {
                    $this->menu['index'] = [
                        'label' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;
            default:
            case 'view':
                {
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span> Manage',
                        'title' => 'Manage',
                        'url' => [
                            'index'
                        ],
                        'visible' => User::isAdmin()
                    ];

                    if ($model != null)
                        $this->menu['update'] = [
                            'label' => Yii::t('app', 'Update'),
                            'url' => [
                                'update',
                                'id' => $model->id
                            ],
                            'visible' => ! User::isAdmin()
                        ];
                }
                break;
        }
    }
}