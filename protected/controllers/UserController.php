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
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\EmailQueue;
use app\models\LoginForm;
use app\models\User;
use app\models\search\User as UserSearch;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends TController
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
                            'view',
                            'logout',
                            'changepassword',
                            'profileImage',
                            'toggle',
                            'download',
                            'dashboard',
                            'recover',
                            'image-manager',
                            'image-upload',
                            'theme-param',
                            'update',
                            'email-resend',
                            'image',
                            'cover-image'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'index',
                            'add',
                            'shadow',
                            'view',
                            'update',
                            'delete',
                            'profileImage',
                            'customers',
                            'providers',
                            'business',
                            'clear'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'add',
                            'view',
                            'update',
                            'changepassword'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isManager();
                        }
                    ],
                    [
                        'actions' => [
                            'signup',
                            'image',
                            'cover-image'
                        ],
                        'allow' => (! defined('ENABLE_ERP')) ? true : false,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ],
                    [
                        'actions' => [
                            'login',
                            'recover',
                            'resetpassword',
                            'profileImage',
                            'cover-image',
                            'download',
                            'add-admin',
                            'captcha',
                            'confirm-email',
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

    public function actions()
    {
        return [

            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => User::class,
                'attribute' => 'profile_file'
            ],
            'cover-image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => User::class,
                'attribute' => 'cover_file'
            ]
        ];
    }

    /**
     * Clear runtime and assets
     *
     * @return \yii\web\Response
     */
    public function actionClear()
    {
        $runtime = Yii::getAlias('@runtime');
        $this->cleanRuntimeDir($runtime);

        $this->cleanAssetsDir();
        return $this->goBack();
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'not in',
            'u.role_id',
            [
                User::ROLE_ADMIN
            ]
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single User model.
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

    public function actionCustomers()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'in',
            'u.role_id',
            [
                User::ROLE_CUSTOMER
            ]
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionProviders()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'in',
            'u.role_id',
            [
                User::ROLE_PROVIDER
            ]
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionBusiness()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'in',
            'u.role_id',
            [
                User::ROLE_BUSINESS
            ]
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionAddAdmin()
    {
        $this->layout = "guest-main";
        $count = User::find()->count();
        if ($count != 0) {
            return $this->redirect([
                '/'
            ]);
        }
        $model = new User();
        $model->scenario = 'signup';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->role_id = User::ROLE_ADMIN;
            $model->state_id = User::STATE_ACTIVE;
            if ($model->validate()) {
                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                if ($model->save()) {
                    return $this->redirect([
                        'login'
                    ]);
                }
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $this->layout = 'main';
        $model = new User();
        $model->role_id = User::ROLE_CUSTOMER;
        $model->state_id = User::STATE_ACTIVE;
        $model->scenario = 'add';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->role_id = User::ROLE_CUSTOMER;
            $model->state_id = User::STATE_ACTIVE;
            $image = UploadedFile::getInstance($model, 'profile_file');
            $model->saveUploadedFile($model, 'emoji_file');
            if (! empty($image)) {
                $image->saveAs(UPLOAD_PATH . $image->baseName . '.' . $image->extension);
                $model->profile_file = $image->baseName . '.' . $image->extension;

                Yii::$app->getSession()->setFlash('success', 'User Added Successfully.');
            }
            if ($model->validate()) {
                $model->sendRegistrationMailtoAdmin();
                $model->setPassword($model->password);
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', ' User Added Successfully.');
                    return $this->redirect([
                        'view',
                        'id' => $model->id
                    ]);
                }
            }
        }
        $this->updateMenuItems($model);
        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionRecover()
    {
        $this->layout = 'guest-main';
        $model = new User();
        $model->scenario = 'token_request';
        if (isset($_POST['User'])) {
            $email = trim($_POST['User']['email']);
            if ($email != null) {

                $user = User::findOne([
                    'email' => $email
                ]);
                if ($user) {
                    $user->generatePasswordResetToken();
                    if (! $user->save()) {
                        throw new HttpException("Cant Generate Authentication Key");
                    }
                    $email = $user->email;
                    $sub = "Recover Your Account at: " . \Yii::$app->params['company'];
                    EmailQueue::add([
                        'from' => \Yii::$app->params['adminEmail'],
                        'to' => $email,
                        'subject' => $sub,
                        'html' => \yii::$app->view->renderFile('@app/mail/passwordResetToken.php', [
                            'user' => $user
                        ])
                    ]);

                    \Yii::$app->session->setFlash('success', 'Please check your email to reset your password.');
                } else {

                    \Yii::$app->session->setFlash('error', 'Email is not registered.');
                }
            } else {
                $model->addError('email', 'Email cannot be blank');
            }
        }
        $this->updateMenuItems($model);
        return $this->render('requestPasswordResetToken', [
            'model' => $model
        ]);
    }

    public function actionResetpassword($token)
    {
        $this->layout = 'guest-main';
        $model = User::findByPasswordResetToken($token);
        if (! ($model)) {
            \Yii::$app->session->setFlash('error', 'This URL is expired.');
            return $this->redirect([
                'user/recover'
            ]);
        }
        $newModel = new User([
            'scenario' => 'resetpassword'
        ]);

        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->password);
            $model->removePasswordResetToken();
            $model->generateAuthKey();
            $model->last_password_change = date('Y-m-d H:i:s');

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'New password is saved successfully.');
            } else {
                \Yii::$app->session->setFlash('error', 'Error while saving new password.');
            }
            return $this->goHome();
        }
        $this->updateMenuItems($model);
        return $this->render('resetpassword', [
            'model' => $newModel
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $post = \yii::$app->request->post();
        $old_image = $model->profile_file;
        $password = $model->password;

        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            if (! empty($post['User']['password']))
                $model->setPassword($post['User']['password']);
            else
                $model->password = $password;
            $model->profile_file = $old_image;
            $model->saveUploadedFile($model, 'profile_file');
            if ($model->save())
                Yii::$app->getSession()->setFlash('success', 'User Updated Successfully.');
            return $this->redirect($model->getUrl());
        }

        $model->password = '';
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);

        if (\Yii::$app->user->id == $model->id) {
            \Yii::$app->session->setFlash('user-action-error', 'You are not allowed to perform this operation.');
            return $this->goBack();
        }

        $model->delete();
        \Yii::$app->session->setFlash('success', 'User Deleted Successfully!!');
        return $this->redirect([
            'index'
        ]);
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

    public function actionSignup()
    {
        $this->layout = "guest-main";
        $model = new User();
        $model->scenario = 'signup';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->state_id = User::STATE_ACTIVE;
            $model->role_id = User::ROLE_CUSTOMER;
            if ($model->validate()) {

                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                $model->generateAuthKey();
                if ($model->save(false)) {
                    $model->sendRegistrationMailtoAdmin();

                    \Yii::$app->user->login($model);
                    return $this->redirect([
                        '/dashboard/index'
                    ]);
                }
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionLogin()
    {
        $this->layout = "guest-main";

        if (! \Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $user = User::findByUsername(trim($model->username));
                if (! empty($user)) {
                    if ($user->role_id == User::ROLE_ADMIN) {

                        if ($model->login()) {
                            return $this->goBack([
                                'dashboard/index'
                            ]);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('Info', 'you are not allowed to login!!');
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', 'User does not exist');
                }
            }
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionProfileImage()
    {
        return Yii::$app->user->identity->getProfileImage();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionChangepassword($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        if (! ($model->isAllowed()))
            throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

        $newModel = new User([
            'scenario' => 'changepassword-admin'
        ]);
        if (Yii::$app->request->isAjax && $newModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return TActiveForm::validate($newModel);
        }
        $post = Yii::$app->request->post();
        if ($newModel->load($post)) {
            if ($model->validatePassword($newModel->oldPassword)) {
                if ($newModel->validate()) {
                    $model->setPassword($newModel->newPassword);
                    $model->last_password_change = date('Y-m-d H:i:s');
                    $model->generateAuthKey();
                    if ($model->save()) {
                        Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Password Changed'));
                        return $this->redirect([
                            'dashboard/index'
                        ]);
                    } else {
                        \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('info', \Yii::t('app', 'Incorrect OldPassword'));
            }
        }
        $this->updateMenuItems($model);
        return $this->render('changepassword', [
            'model' => $newModel
        ]);
    }

    public function actionThemeParam()
    {
        $is_collapsed = Yii::$app->session->get('is_collapsed', 'sidebar-collapsed');
        $is_collapsed = empty($is_collapsed) ? 'sidebar-collapsed' : '';
        Yii::$app->session->set('is_collapsed', $is_collapsed);
    }

    /**
     * Resend verification email to user
     *
     * @return string
     */
    public function actionEmailResend()
    {
        $model = User::find()->where([
            'id' => Yii::$app->user->id
        ])->one();
        $model->sendVerificationMailtoUser(true);
        \Yii::$app->session->setFlash('success', 'Email send successfully');
        return $this->goBack([
            '/dashboard/index'
        ]);
    }

    public function actionConfirmEmail($id)
    {
        $user = User::find()->where([
            'activation_key' => $id
        ])->one();
        if (! empty($user)) {

            $user->email_verified = User::EMAIL_VERIFIED;
            $user->state_id = User::STATE_ACTIVE;
            if ($user->save()) {
                \Yii::$app->cache->flush();
                $user->refresh();
                if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
                    \Yii::$app->getSession()->setFlash('success', 'Congratulations! your email is verified');
                    return $this->goBack([
                        '/dashboard/index'
                    ]);
                }
            }
        }
        \Yii::$app->getSession()->setFlash('expired', 'Token is Expired Please Resend Again');
        return $this->goBack([
            '/dashboard/index'
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {

            if (! ($model->isAllowed()))
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
                {}
                break;
            case 'add':
                {
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;
            case 'customers':
                break;
            case 'providers':
                break;
            case 'business':
                break;
            case 'update':
                break;
            default:
            case 'view':
                if ($model != null)
                    $this->menu['changepassword'] = [
                        'label' => '<span class="glyphicon glyphicon-paste"></span>',
                        'title' => Yii::t('app', 'changepassword'),
                        'url' => [
                            'changepassword',
                            'id' => $model->id
                        ],

                        'visible' => User::isManager()
                    ];

                break;
        }
    }
}
