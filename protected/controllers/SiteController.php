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
use app\modules\page\models\Page;
use app\models\SubscriptionPlan;
use app\models\HomeContent;
use app\modules\faq\models\Faq;
use app\models\Package;
use app\models\Charity;
use app\models\CharityDetail;
use yii\data\ActiveDataProvider;

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
                            'bonanza',
                            'our-team',
                            'error',
                            'demo',
                            'pricing',
                            'privacy',
                            'terms',
                            'captcha',
                            'covid',
                            'delivery-information',
                            'tracol-fruits',
                            'charity',
                            'charity-view'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?'
                        ]
                    ],
                    [
                        'actions' => [
                            'index',
                            'contact',
                            'about',
                            'bonanza',
                            'our-team',
                            'error',
                            'demo',
                            'pricing',
                            'privacy',
                            'terms',
                            'captcha',
                            'covid',
                            'delivery-information',
                            'tracol-fruits',
                            'charity',
                            'charity-view'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'info'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'index'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isSubAdmin();
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
        $this->layout = 'guest-main';
        if (User::isAdmin() || User::isSubAdmin())
            return $this->redirect([
                '/dashboard/index'
            ]);

        $model = Faq::findActive();
        return $this->render('index', [
            'faqs' => $model
        ]);
    }

    public function actionCharity()
    {
        $charityDetail = new CharityDetail();
        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Description of the page...'
        ]);
        $model = new Charity();
        $searchModel = new \app\models\search\Charity();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('charity', [
            'dataProvider' => $dataProvider,
            'charityDetail' => $charityDetail
        ]);
    }
    
    public function actionCharityView($id)
    {
        $model = Charity::find()->where([
            'id' => $id
        ])->one();
        $charityDetail = new CharityDetail();
        \Yii::$app->view->registerMetaTag([
            'name' => 'Tracolasia',
            'content' => $model->description,
            'image' => $model->getImageUrl()
        ]);
        if(empty($model)){
            $model = new Charity();
        }
        $searchModel = new \app\models\search\Charity();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('charity-view', [
            'model' => $model,
            'charityDetail' => $charityDetail
        ]);
    }

    public function actionFaq()
    {
        $model = Faq::findActive();
        return $this->render('faq', [
            'model' => $model
        ]);
    }

    public function actionContact()
    {
        $this->layout = 'guest-main';
        return $this->render('contact');
    }

    public function actionBonanza()
    {
        $this->layout = 'guest-main';
        $terms = Page::find()->where([
            'type_id' => Page::TYPE_TERM_CONDITION
        ])->limit(10);
        return $this->render('bonanza', [
            'terms' => $terms
        ]);
    }

    public function actionOurTeam()
    {
        $this->layout = 'guest-main';
        return $this->render('ourteam');
    }

    public function actionTracolFruits()
    {
        $this->updateMenuItems();
        $subscription_plan = SubscriptionPlan::find()->all();
        $offerings = Package::find()->all();
        $this->layout = 'guest-main';
        return $this->render('tracol-fruits', [
            'plans' => $subscription_plan,
            'offerings' => $offerings
        ]);
    }

    public function actionAbout()
    {
        $about = Page::find()->where([
            'type_id' => Page::TYPE_ABOUT_US
        ])->one();
        $this->layout = 'guest-main';
        return $this->render('about', [
            'about' => $about
        ]);
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
        $model = Page::find()->where([
            'type_id' => Page::TYPE_PRIVACY
        ])->one();
        $this->layout = 'guest-main';
        return $this->render('privacy', [
            'model' => $model
        ]);
    }

    public function actionCovid()
    {
        $this->layout = 'guest-main';
        return $this->render('covid_19');
    }

    public function actionDeliveryInformation()
    {
        $this->layout = 'guest-main';
        return $this->render('delivery-information');
    }

    public function actionTerms()
    {
        $model = Page::find()->where([
            'type_id' => Page::TYPE_TERM_CONDITION
        ])->one();
        $this->layout = 'guest-main';
        return $this->render('terms', [
            'model' => $model
        ]);
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
        $info['Technical'] = $_SERVER;
        unset($info['Technical']['HTTP_COOKIE']);
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
                    $this->menu[] = array(
                        'label' => Yii::t('app', 'Manage'),
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );
                }
                break;
            default:
            case 'view':
                {
                    $this->menu[] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span> Manage',
                        'title' => 'Manage',
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );

                    if ($model != null)
                        $this->menu[] = array(
                            'label' => Yii::t('app', 'Update'),
                            'url' => array(
                                'update',
                                'id' => $model->id
                            ),
                            'visible' => ! User::isAdmin()
                        );
                }
                break;
        }
    }
}
