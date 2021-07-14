<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\components\helpers\TFileHelper;
use app\models\ProductVideo;
use app\models\User;
use app\models\search\ProductVideo as ProductVideoSearch;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\VarDumper;

/**
 * ProductVideoController implements the CRUD actions for ProductVideo model.
 */
class ProductVideoController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'add',
                            'view',
                            'update',
                            'clone',
                            'ajax',
                            'mass',
                            'clear',
                            'delete',
                            'video',
                            'image',
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'image',
                            'video'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'image',
                            'video'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*',
                            '@'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        return [

            'video' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => ProductVideo::class,
                'attribute' => 'video_file'
            ],
            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => ProductVideo::class,
                'attribute' => 'image_file'
            ]
        ];
    }

    /**
     * Lists all ProductVideo models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductVideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single ProductVideo model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model
            ]);
        }
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * actionMass delete in mass as items are checked
     *
     * @param string $action
     * @return string
     */
    public function actionMass($action = 'delete')
    {
        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $status = ProductVideo::massDelete();
        if ($status == true) {
            $response['status'] = 'OK';
        }
        return $response;
    }

    /**
     * Creates a new ProductVideo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd(/* $id*/)
    {
        $model = new ProductVideo();
        $post = \yii::$app->request->post();
        $count = ProductVideo::find()->where([
            'type_id' => ProductVideo::TYPE1
        ])->count();
        $model->type_id = ProductVideo::TYPE2;
        $model->state_id = ProductVideo::STATE_ACTIVE;
        $model->checkRelatedData([
            'created_by_id' => User::class
        ]);
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $count >= ProductVideo::LIMIT && $model->type_id == ProductVideo::TYPE1) {
            \Yii::$app->getSession()->setFlash('failure', \Yii::t('app', "Delete previous video"));
            return $this->render('add', [
                'model' => $model
            ]);
        }
        if ($model->load($post)) {
            $image = UploadedFile::getInstance($model, 'image_file');
            if (! empty($image)) {
                $model->saveUploadedFile($model, 'image_file');
                Yii::$app->getSession()->setFlash('success', 'Image Uploaded Successfully.');
            }
            $model->saveUploadedFile($model, 'video_file');
            Yii::$app->getSession()->setFlash('success', 'Video Uploaded Successfully.');
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Record has been added Successfully."));
                return $this->redirect($model->getUrl());
            }
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionVideo($id, $w = null, $h = null)
    {
        $model = ProductVideo::findOne($id);
        if (empty($model))
            throw new NotFoundHttpException('The requested page does not exist.');
        $file = UPLOAD_PATH . $model->video_file;

        if (! is_file($file)) {
            $file = Yii::$app->view->theme->basePath . '/img/show-default.png';
        }
        if ($w || $h) {
            $path = $w ? $w : '';
            $path .= $h ? $h : '';
            $thumb_path = UPLOAD_PATH . "$path/" . $model->video_file;
            if (! is_file($thumb_path)) {
                TFileHelper::createDirectory(dirname($thumb_path), FILE_MODE, true);

                $img = \yii\imagine\Image::thumbnail($file, $w, $h);
                $img->save($thumb_path);
            }

            $file = $thumb_path;
        }
        return Yii::$app->response->sendFile($file);
    }

    /**
     * Updates an existing ProductVideo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldVideo = $model->video_file;
        $oldImage = $model->image_file;
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            if (! $model->saveUploadedFile($model, 'video_file', $oldVideo)) {
                $model->video_file = $oldVideo;
            }
            if (! $model->saveUploadedFile($model, 'image_file', $oldImage)) {
                $model->image_file = $oldImage;
            }
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Record has been updated Successfully."));
                return $this->redirect($model->getUrl());
            }
        }
        $this->updateMenuItems($model);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model
            ]);
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Clone an existing ProductVideo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new ProductVideo();
        $model->loadDefaultValues();
        $model->state_id = ProductVideo::STATE_ACTIVE;

        $model->title = $old->title;
        $model->description = $old->description;
        $model->video_file = $old->video_file;
        $model->youtub_link = $old->youtub_link;
        $model->type_id = $old->type_id;
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing ProductVideo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (\yii::$app->request->post()) {
            $model->delete();
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Record has been deleted Successfully."));
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing ProductVideo model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = ProductVideo::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            ProductVideo::truncate();
        }
        \Yii::$app->session->setFlash('success', 'ProductVideo Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the ProductVideo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return ProductVideo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = ProductVideo::findOne($id)) !== null) {

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

            case 'add':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;
            case 'index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['clear'] = [
                        'label' => '<span class="glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                        ],
                        'htmlOptions' => [
                            'data-confirm' => "Are you sure to delete these items?"
                        ],
                        'visible' => false
                    ];
                }
                break;
            case 'update':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;

            default:
            case 'view':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    if ($model != null) {
                        $this->menu['clone'] = array(
                            'label' => '<span class="glyphicon glyphicon-file">Clone</span>',
                            'title' => Yii::t('app', 'Clone'),
                            'url' => $model->getUrl('clone'),
                            'visible' => false
                        );
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => $model->getUrl('update')
                            // 'visible' => User::isAdmin ()
                        ];
                        $this->menu['delete'] = [
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'htmlOptions' => [
                                'data-method' => 'post'
                            ],
                            'url' => $model->getUrl('delete')
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}
