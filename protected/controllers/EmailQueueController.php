<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TController;
use app\models\User;
use app\models\search\EmailQueue as EmailQueueSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use app\models\EmailQueue;

/**
 * EmailQueueController implements the CRUD actions for EmailQueue model.
 */
class EmailQueueController extends TController
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
                            'view',
                            'delete',
                            'ajax',
                            'clear',
                            'mass',
                            'send-now'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',

                            'ajax',
                            'send-now'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isManager();
                        }
                    ],
                    [
                        'actions' => [
                            'ajax',
                            'view',
                            'show',
                            'image'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isUser() || User::isManager();
                        }
                    ],
                    [
                        'actions' => [
                            'unsubscribe',
                            'subscribe',
                            'show',
                            'image'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    public function actionMass($action = 'delete')
    {
        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $Ids = Yii::$app->request->post('ids');
        foreach ($Ids as $Id) {
            $model = $this->findModel($Id);

            if ($action == 'delete') {
                if (! $model->delete()) {
                    return $response['status'] = 'NOK';
                }
            }
        }

        $response['status'] = 'OK';

        return $response;
    }

    public function actionClear($truncate = true)
    {
        $query = EmailQueue::find();
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $model->delete();
            }
        }
        if ($truncate) {
            EmailQueue::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Done !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Lists all EmailQueue models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailQueueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single EmailQueue model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionSendNow($id)
    {
        $model = $this->findModel($id);
        if ($model->state_id == EmailQueue::STATE_PENDING) {
            $model->sendNow();
        }
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing EmailQueue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();
        return $this->redirect([
            'index'
        ]);
    }

    public function actionImage($id)
    {
        
    }

    public function actionShow($id)
    {
        $model = $this->findModel($id, false);
        if ($model == null)
            throw new NotFoundHttpException('The requested page does not exist.');
        echo $model->message . $model->getFooter();
        exit();
    }

    public function actionUnsubscribe($id)
    {
        return '#';
    }

    public function actionSubscribe($id)
    {
        $this->layout = 'guest-out';
        $model = $this->findModel($id, false);
        Unsubscribe::remove($model->to_email);
        return $this->render('subscribe-view', [
            'model' => $model
        ]);
    }

    /**
     * Finds the EmailQueue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return EmailQueue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = EmailQueue::findOne($id)) !== null) {

            if ($accessCheck && ! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {

            case 'index':
                {
                    $this->menu['clear'] = array(
                        'label' => '<span class="glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                        ],
                        'htmlOptions' => [
                            'data-confirm' => "Are you sure to delete all items?"
                        ],
                        'visible' => User::isAdmin()
                    );
                }
                break;
            default:
            case 'view':
                {
                    $this->menu['manage'] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => false
                    );
                    if ($model != null) {
                        $this->menu['send-now'] = array(
                            'label' => '<span class="glyphicon glyphicon-envelope"></span>',
                            'title' => Yii::t('app', 'Send Now'),
                            'url' => [
                                'send-now',
                                'id' => $model->id
                            ],
                            'visible' => false
                        );
                        $this->menu['delete'] = array(
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'url' => [
                                'delete',
                                'id' => $model->id
                            ],
                            'visible' => User::isAdmin()
                        );
                    }
                }
        }
    }
}
