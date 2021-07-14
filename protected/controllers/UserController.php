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

use Imagine\Image\ManipulatorInterface;
use app\components\TActiveForm;
use app\components\TController;
use app\models\Address;
use app\models\EmailQueue;
use app\models\LoginForm;
use app\models\SubscriptionPlan;
use app\models\User;
use app\models\UserDetail;
use app\models\search\User as UserSearch;
use app\modules\page\models\Page;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\imagine\Image;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\ProductVideo;
use app\models\Category;
use app\models\search\SubscriptionBilling;
use yii\helpers\Url;
use app\models\PaymentTransaction;
use Stripe\Order;
use app\models\Charity;
use app\models\CharityDetail;
use yii\data\ActiveDataProvider;

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
                            'dashboard',
                            'recover',
                            'update',
                            'image',
                            'profile',
                            'user-details',
                            'login',
                            'update-profile',
                            'update-password',
                            'add-address',
                            'user-plan',
                            'edit-address',
                            'update-address',
                            'personal-detail',
                            'medical-specification',
                            'diet-plan',
                            'advice',
                            'prefferred-package',
                            'subscription-plan',
                            'checkout',
                            'update-image',
                            'media',
                            'sen',
                            'pay-later',
                            'charity-payment'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
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
                            'clear',
                            'user-details',
                            'update-profile'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [

                            'logout',
                            'update',
                            'add',
                            'shadow',
                            'view',
                            'profileImage',
                            'clear',
                            'user-details',
                            'update-profile'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isSubAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'signup',
                            'image',
                            'login',
                            'user-details',
                            'add-admin',
                            'recover',
                            'sen',
                            'failure',
                            'charity-payment',
                            'reset-password'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?'
                        ]
                    ],
                    [
                        'actions' => [
                            'signup',
                            'image',
                            'login',
                            'user-details',
                            'add-admin',
                            'recover',
                            'sen',
                            'failure'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isSubAdmin() || User::isAdmin() || User::isUser();
                        }
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

    public function actionAddAdmin()
    {
        $this->layout = "guest-out";
        $count = User::find()->count();
        if ($count != 0) {
            return $this->redirect([
                '/'
            ]);
        }

        $model = new User();
        $model->scenario = 'admin-signup';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->role_id = User::ROLE_ADMIN;
            $model->state_id = User::STATE_ACTIVE;
            if ($model->validate()) {
                $model->scenario = 'admin-signup';
                $model->generateAuthKey();
                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                if ($model->save()) {
                    return $this->redirect([
                        'login'
                    ]);
                }
            }
        }
        return $this->render('admin_signup', [
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
        $usermodel = new UserDetail();
        $model->scenario = 'add';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->state_id = User::STATE_ACTIVE;
            $model->role_id = User::ROLE_SUB_ADMIN;
            $model->saveUploadedFile($model, 'profile_file');
            if ($model->validate()) {
                $model->generatePasswordResetToken();
                $model->setPassword($model->password);

                if ($model->save()) {
                    if ($usermodel->load(Yii::$app->request->post())) {

                        if ($usermodel->save()) {}
                    }
                    $model->sendRegistrationMailtoUser();
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

    /**
     * Add an existing User details.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateImage()
    {
        $model = Yii::$app->user->identity;

        $old_image = $model->profile_file;
        $post = \yii::$app->request->post();

        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->validate()) {
            if (! $model->saveUploadedFile($model, 'profile_file', $old_image)) {
                $model->profile_file = $old_image;
            }

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Profile Updated successfully.'));
                return $this->redirect(\Yii::$app->request->referrer);
            } else {
                \Yii::$app->getSession()->setFlash('error', \Yii::t('app', "Error !!" . $model->getErrors()));
            }
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionRecover()
    {
        $this->layout = 'guest-main';
        $model = new User();
        $model->scenario = 'token_request';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
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

    public function actionResetPassword($token)
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
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'New password is saved successfully.'));
            } else {
                \Yii::$app->session->setFlash('error', \Yii::t('app', 'Error while saving new password.'));
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
                return $this->redirect($model->getUrl());
        }

        $model->password = '';
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing User details.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateProfile()
    {
        $this->layout = "guest-main";
        $model = \Yii::$app->user->identity;
        $userdetails = $model->userDetail;
        if (is_null($userdetails)) {
            $userdetails = new UserDetail();
        }
        $userdetails->scenario = 'update';
        $post = \yii::$app->request->post();
        if (Yii::$app->request->isAjax && $model->load($post) && $userdetails->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model, $userdetails);
        }
        if ($model->load($post)) {
            $transaction = \Yii::$app->db->beginTransaction();
            if ($model->save()) {
                if ($userdetails->load($post)) {
                    if ($userdetails->save()) {
                        \Yii::$app->session->setFlash('success', \Yii::t('app', 'User updated successfully.'));
                        $transaction->commit();
                        return $this->redirect([
                            'user/profile'
                        ]);
                    } else {
                        $transaction->rollBack();
                        \Yii::$app->session->setFlash('error', "Error !!" . $userdetails->getErrors());
                    }
                }
            } else {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('error', "Error !!" . $model->getErrors());
            }
        }
        return $this->render('update_profile', [
            'model' => $model,
            'userdetails' => $userdetails
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
        $this->layout = "guest_signup";
        $model = new User([
            'scenario' => 'signup'
        ]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->state_id = User::STATE_ACTIVE;
            $model->role_id = User::ROLE_USER;
            if ($model->validate()) {
                $model->scenario = 'user-signup';
                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                $model->state_id = User::STATE_ACTIVE;
                $model->role_id = User::ROLE_USER;
                if ($model->save()) {
                    Yii::$app->user->login($model, 3600);
                    $model->sendRegistrationMailtoUser();
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Signed Up Successfully '));
                    return $this->redirect([
                        'user/personal-detail'
                    ]);
                }
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionPersonalDetail()
    {
        $id = \Yii::$app->user->identity->id;
        $this->layout = "guest_signup";
        $model = new UserDetail();
        $model->scenario = 'add';
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            $model->user_id = $id;
            $model->package_id = '';
            $model->subscription_id = '';
            $model->is_profile_complete = User::IS_PROFILE_ONE;
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Personal Details Added Successfully. '));
                return $this->redirect([
                    'user/medical-specification'
                ]);
            }
        }
        return $this->render('signup_steps', [
            'model' => $model
        ]);
    }

    public function actionMedicalSpecification()
    {
        $this->layout = "guest_signup";
        $model = \Yii::$app->user->identity;
        $userdetails = $model->userDetail;
        if (is_null($userdetails)) {
            $userdetails = new UserDetail();
        }
        $userdetails->scenario = 'medical-specification';
        $post = \Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $userdetails->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($userdetails);
        }
        if ($userdetails->load($post)) {
            $userdetails->is_profile_complete = User::IS_PROFILE_TWO;
            $userdetails->updateAttributes([
                'description',
                'is_profile_complete'
            ]);
            return $this->redirect([
                'user/diet-plan'
            ]);
        }
        return $this->render('signup_steps', [
            'model' => $userdetails
        ]);
    }

    public function actionDietPlan()
    {
        $this->layout = "guest_signup";
        $model = \Yii::$app->user->identity;

        $userdetails = $model->userDetail;
        if (is_null($userdetails)) {
            $userdetails = new UserDetail();
        }
        $post = \Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $userdetails->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($userdetails);
        }
        if ($userdetails->load($post)) {
            $userdetails->is_profile_complete = User::IS_PROFILE_THREE;
            $userdetails->updateAttributes([
                'eat_count',
                'is_profile_complete'
            ]);
            return $this->redirect([
                'user/advice'
            ]);
        }
        return $this->render('signup_steps', [
            'model' => $userdetails
        ]);
    }

    public function actionAdvice()
    {
        $this->layout = "guest_signup";
        $nutrition = Page::findOne([
            'type_id' => Page::TYPE_NUTRITION_ADVICE
        ]);
        return $this->render('signup_steps', [
            'nutrition' => $nutrition
        ]);
    }

    public function actionPrefferredPackage()
    {
        $packageCategory = Category::find()->where([
            'type_id' => Category::TYPE2
        ])->all();
        $this->layout = "guest_signup";
        $model = \Yii::$app->user->identity;
        $userdetails = $model->userDetail;
        if (is_null($userdetails)) {
            $userdetails = new UserDetail();
        }
        $post = \Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $userdetails->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($userdetails);
        }
        if ($userdetails->load($post)) {
            $userdetails->is_profile_complete = User::IS_PROFILE_FIVE;
            $userdetails->updateAttributes([
                'package_id',
                'is_profile_complete'
            ]);

            return $this->redirect([
                'user/subscription-plan'
            ]);
        }
        return $this->render('signup_steps', [
            'model' => $userdetails,
            'packageCategory' => $packageCategory
        ]);
    }

    public function actionSubscriptionPlan()
    {
        $this->layout = "guest_signup";
        $model = \Yii::$app->user->identity;
        $userdetails = $model->userDetail;
        $subscription_plan = SubscriptionPlan::findall([
            'state_id' => User::STATE_ACTIVE
        ]);
        $post = \Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $userdetails->load($post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($userdetails);
        }
        if ($userdetails->load($post)) {
            $userdetails->is_profile_complete = User::IS_PROFILE_SIX;
            $userdetails->updateAttributes([
                'subscription_id',
                'is_profile_complete'
            ]);
            return $this->redirect([
                'user/checkout'
            ]);
        }
        return $this->render('plan', [
            'userdetails' => $userdetails,
            'plans' => $subscription_plan
        ]);
    }

    public function actionFailure()
    {
        $this->layout = 'guest-main';

        return $this->render('failure');
    }

    public function actionCheckout($sub = null)
    {
        $this->layout = "guest_signup";
        $subscription_plan = SubscriptionPlan::find()->Where([
            'id' => $sub
        ])->one();
        $model = \Yii::$app->user->identity;
        $userdetails = $model->userDetail;
        if (! empty($model)) {
            $userdetails->subscription_id = $sub;
            $userdetails->updateAttributes([
                'subscription_id'
            ]);
        }
        return $this->render('checkout', [
            'userdetails' => $userdetails,
            'subscription_plan' => $subscription_plan
        ]);
    }

    public function actionPayLater()
    {
        $userDetail = UserDetail::findOne([
            'user_id' => \yii::$app->user->id
        ]);
        $transaction = PaymentTransaction::find()->where([
            'model_type' => SubscriptionPlan::className(),
            'payment_status' => PaymentTransaction::PAID,
            'payer_id' => \Yii::$app->user->id
        ])
            ->orderBy([
            'id' => SORT_DESC
        ])
            ->one();
        if (! empty($transaction)) {

            $plan = \app\models\SubscriptionBilling::find()->where([
                'id' => $transaction->model_id,
                'created_by_id' => \Yii::$app->user->id
            ])->one();
            $subscription = SubscriptionPlan::find()->where([
                'id' => $plan->subscription_id
            ])->one();
            if (! empty($subscription)) {
                $userDetail->updateAttributes([
                    'subscription_id' => $subscription->id
                ]);
            }
        } else {
            $userDetail->updateAttributes([
                'subscription_id' => NULL
            ]);
        }

        return $this->redirect([
            '/user/user-plan'
        ]);
    }

    public function actionCharityPayment($id)
    {
        $charity = Charity::findOne($id);
        $model = new CharityDetail();
        $post = \Yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if (! empty($charity)) {

            if (User::isGuest()) {
                \Yii::$app->getSession()->setFlash('danger', \Yii::t('app', 'Please!, SignIn or SignUp to Donate'));
                return $this->redirect([
                    '/site/charity'
                ]);
            }
            $payment_type = PaymentTransaction::CHARITY;
            $secretkey = Yii::$app->params['senangpay_secret_id'];
            $user_id = \Yii::$app->user->id;
            $userModel = User::findOne($user_id);
            $model->charity_id = $charity->id;
            $model->user_id = \Yii::$app->user->id;

            if ($model->load($post)) {

                if ($model->save()) {
                    $charity->updateAttributes([
                        'raised_amount' => (int) $charity->raised_amount + (int) $model->amount
                    ]);
                    $title = $charity->title;
                    $order = $model->id;
                    $totalprice = (string) $model->amount;
                    $hashed_string = hash_hmac('sha256', $secretkey . urldecode($title) . urldecode($totalprice) . urldecode($order), $secretkey);
                    $this->layout = 'guest-main';
                    return $this->render('sen', [
                        'title' => $title,
                        'order' => $order,
                        'totalprice' => $totalprice,
                        'model' => $charity,
                        'hashed_string' => $hashed_string,
                        'userModel' => $userModel,
                        'payment_type' => $payment_type
                    ]);
                } else {
                    \Yii::$app->getSession()->setFlash('danger', \Yii::t('app', $model->getErrorsString()));
                    return $this->redirect([
                        '/site/charity'
                    ]);
                }
            } else {
                \Yii::$app->getSession()->setFlash('danger', \Yii::t('app', $model->getErrorsString()));
                return $this->redirect([
                    '/site/charity'
                ]);
            }
        } else {
            \Yii::$app->getSession()->setFlash('danger', \Yii::t('app', "Charity doesn't exists"));
            return $this->redirect([
                '/site/charity'
            ]);
        }
    }

    public function actionSen($id = null)
    {
        $payment_type = PaymentTransaction::SUBSCRIPTION;
        $model = new SubscriptionBilling();
        $post = Yii::$app->request->post();
        $payment = new PaymentTransaction();
        $secretkey = Yii::$app->params['senangpay_secret_id'];

        if (! empty($post)) {

            if (! empty($id)) {
                $modelDetail = SubscriptionPlan::findOne($id);
            } else {
                $modelDetail = new SubscriptionPlan();
            }
            $user_id = \Yii::$app->user->identity->id;
            $userModel = User::findOne($user_id);
            $model->state_id = SubscriptionPlan::STATE_ACTIVE;
            $model->type_id = SubscriptionPlan::STATE_ACTIVE;
            $model->subscription_id = $id;
            $model->created_by_id = $user_id;
            $model->created_on = date('Y-m-d H:i:s');
            if ($model->save()) {

                $title = $modelDetail->title;
                $order = $model->id;
                $totalprice = (string) $modelDetail->price * $modelDetail->total_delivered + ($modelDetail->total_delivered * 24);
                $hashed_string = hash_hmac('sha256', $secretkey . urldecode($modelDetail->title) . urldecode($totalprice) . urldecode($order), $secretkey);
                $this->layout = 'guest-main';
                return $this->render('sen', [
                    'title' => $title,
                    'order' => $order,
                    'totalprice' => $totalprice,
                    'model' => $modelDetail,
                    'hashed_string' => $hashed_string,
                    'userModel' => $userModel,
                    'payment_type' => $payment_type
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('error', $model->getErrorssString());
                return $this->redirect(\Yii::$app->request->referrer ?: \Yii::$app->homeUrl);
            }
        } else if ((\Yii::$app->request->get('status_id')) && (\Yii::$app->request->get('order_id')) && (\Yii::$app->request->get('msg')) && (\Yii::$app->request->get('transaction_id')) && (\Yii::$app->request->get('hashed_value'))) {
            # verify that the data was not tempered, verify the hash
            // $hashed_string = hash_hmac('sha256', $secretkey . urldecode((\Yii::$app->request->get('status_id'))) . urldecode((\Yii::$app->request->get('order_id'))) . urldecode((\Yii::$app->request->get('transaction_id'))) . urldecode((\Yii::$app->request->get('msg'))), $secretkey);
            if (! empty(\Yii::$app->request->get('order_id')) && ! empty(\Yii::$app->request->get('transaction_id'))) {
                $payment_type = \Yii::$app->request->get('name');
                $payment->amount = \Yii::$app->request->get('amount');
                $payment->currency = PaymentTransaction::CURRENCY;
                $payment->transaction_id = \Yii::$app->request->get('transaction_id');
                $payment->description = \Yii::$app->request->get('msg');
                $payment->payment_status = \Yii::$app->request->get('status_id');
                $payment->model_id = \Yii::$app->request->get('order_id');
                if ($payment_type == PaymentTransaction::SUBSCRIPTION) {
                    $payment->model_type = SubscriptionPlan::className();
                    $order_id = \app\models\SubscriptionBilling::findone($payment->model_id);
                    $payment->payer_id = $order_id->created_by_id;
                    if ($payment->payment_status == PaymentTransaction::STATE_INACTIVE) {
                        $userDetail = UserDetail::findOne([
                            'user_id' => $order_id->created_by_id
                        ]);
                        $transaction = PaymentTransaction::find()->where([
                            'model_type' => SubscriptionPlan::className(),
                            'payment_status' => PaymentTransaction::PAID,
                            'payer_id' => $order_id->created_by_id
                        ])
                            ->orderBy([
                            'id' => SORT_DESC
                        ])
                            ->one();
                        if (! empty($transaction)) {
                            $plan = \app\models\SubscriptionBilling::find()->where([
                                'id' => $transaction->model_id,
                                'created_by_id' => \Yii::$app->user->id
                            ])->one();
                            $subscription = SubscriptionPlan::find()->where([
                                'id' => $plan->subscription_id
                            ])->one();
                            if (! empty($subscription)) {
                                $userDetail->subscription_id = $subscription->id;
                            }
                        } else {
                            $userDetail->subscription_id = NULL;
                        }
                        $userDetail->save();
                    }
                } elseif ($payment_type == PaymentTransaction::ORDER) {
                    $payment->model_type = \app\modules\order\models\Order::className();
                    $order_id = \app\modules\order\models\Order::findone($payment->model_id);
                    $payment->payer_id = $order_id->created_by_id;
                    if ($payment->payment_status == PaymentTransaction::PAID) {
                        $order_id->payment_status = PaymentTransaction::PAID;
                        $order_id->save();
                    }
                } else {
                    $payment->model_type = Charity::className();
                    $order_id = CharityDetail::findone($payment->model_id);
                    $payment->payer_id = $order_id->created_by_id;
                }
                $userdetail = User::findOne($payment->payer_id);
                $payment->name = $userdetail->full_name;
                $payment->email = $userdetail->email;
                if (! $payment->save()) {
                    \Yii::$app->getSession()->setFlash('danger', \Yii::t('app', 'Payment is failed'));
                    return $this->redirect([
                        '/user/failure'
                    ]);
                }
            }
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Payment is successful'));
            return $this->redirect([
                'user/profile'
            ]);
        } else if ((\Yii::$app->request->get('status_id')) == User::STATE_INACTIVE) {
            $hashed_string = hash_hmac('sha256', $secretkey . urldecode((\Yii::$app->request->get('status_id'))) . urldecode((\Yii::$app->request->get('order_id'))) . urldecode((\Yii::$app->request->get('transaction_id'))) . urldecode((\Yii::$app->request->get('msg'))), $secretkey);
            \Yii::$app->getSession()->setFlash('danger', \Yii::t('app', 'Payment is failed'));
            return $this->redirect([
                'user/failure'
            ]);
        }
    }

    public function actionMedia()
    {
        $this->layout = "guest-main";
        $model = \Yii::$app->user->identity;
        $video = ProductVideo::findActive();
        $dataProvider = new ActiveDataProvider([
            'query' => $video,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('video', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /*
     * change user password
     */
    public function actionUpdatePassword()
    {
        $id = \Yii::$app->user->id;
        $this->layout = "guest-main";
        $model = $this->findModel($id);
        if (! ($model->isAllowed()))
            throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

        $newModel = new User([
            'scenario' => 'change-user-password'
        ]);
        if (Yii::$app->request->isAjax && $newModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return TActiveForm::validate($newModel);
        }
        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->newPassword);
            $model->last_password_change = date('Y-m-d H:i:s');
            $model->generateAuthKey();
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Password Changed');
                return $this->redirect([
                    'user/login'
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrors());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('change_user_password', [
            'user' => $newModel,
            'model' => $model
        ]);
    }

    public function actionProfile()
    {
        $this->layout = "guest-main";
        $model = \Yii::$app->user->identity;
        $userdetails = $model->userDetail;
        $user = new UserDetail();
        return $this->render('profile', [
            'model' => $model,
            'userdetails' => $userdetails,
            'user' => $user
        ]);
    }

    public function actionUpdateAddress()
    {
        \Yii::$app->response->format = 'json';

        $post = \yii::$app->request->post();

        $model = Address::find()->where([
            'id' => $post['Address']['id']
        ])->one();

        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            $model->user_id = \Yii::$app->user->id;
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Address updated sucessfully '));
                return $this->redirect([
                    'user-plan'
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('info', $model->getErrors());
            }
        }
        return $this->redirect([
            'user-plan'
        ]);
        ;
    }

    public function actionEditAddress($id)
    {
        Yii::$app->response->format = 'json';

        $response['status'] = 'NOK';
        $address = Address::findOne($id);
        if ($address) {
            $response['status'] = 'OK';
            $response['data'] = $address->asJson(true);
        }
        return $response;
    }

    public function actionLogin()
    {
        $this->layout = "guest-main";
        if (! \Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (User::isAdmin() || User::isSubAdmin()) {
                return $this->redirect([
                    '/dashboard/index'
                ]);
            } else {
                return $this->redirect([
                    '/user/profile'
                ]);
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

    public function actionUserDetails($user_id = null, $subscription_id = null)
    {
        $response = 'NOTOK';
        $model = new UserDetail();
        $post = \yii::$app->request->post();
        if ($model->load($post)) {
            $model->state_id = User::STATE_ACTIVE;
            $model->user_id = $user_id;
            $model->subscription_id = $subscription_id;
            $usermodel = User::findOne($user_id);
            if ($model->save()) {
                \Yii::$app->user->login($usermodel, 3600 * 24 * 30);
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', \Yii::t('user created sucessfully ')));
                return $response = 'OK';
            }
        }
        return $response;
    }

    public function actionUserPlan()
    {
        $model = \Yii::$app->user->identity;
        $this->layout = "guest-main";
        $userdetails = $model->userDetail;
        if (! empty($userdetails)) {
            $subscriptionBilling = \app\models\SubscriptionBilling::find()->where([
                'subscription_id' => $userdetails->subscription_id,
                'created_by_id' => \Yii::$app->user->id
            ])
                ->orderBy([
                'id' => SORT_DESC
            ])
                ->one();
        } else {

            $subscriptionBilling = new \app\models\SubscriptionBilling();
        }
        if (! empty($subscriptionBilling)) {
            $transaction = PaymentTransaction::find()->where([
                'model_id' => $subscriptionBilling->id,
                'model_type' => SubscriptionPlan::className(),
                'payment_status' => PaymentTransaction::PAID,
                'payer_id' => \Yii::$app->user->id
            ])->one();
            if (! empty($transaction)) {
                $plan = \app\models\SubscriptionBilling::find()->where([
                    'id' => $transaction->model_id,
                    'created_by_id' => \Yii::$app->user->id
                ])->one();
                $subscription = SubscriptionPlan::find()->where([
                    'id' => $plan->subscription_id
                ])->one();
            }
            if (empty($subscription)) {
                $subscription = new SubscriptionPlan();
            }
        } else {
            $subscription = new SubscriptionPlan();
        }
        $address = new Address();
        $useraddress = $model->userAddress;
        if (empty($useraddress)) {
            $useraddress = $address;
        }

        return $this->render('user_plan', [
            'useraddress' => $useraddress,
            'subscription' => $subscription,
            'address' => $address,
            'model' => $model
        ]);
    }

    public function actionAddAddress()
    {
        $response = [];

        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $user = \Yii::$app->user->identity;
        // $plan = $user->userDetail;
        // if (is_null($plan->subscription_id) == \app\modules\order\models\Order::STATE_INACTIVE) {
        $userAddresses = Address::find()->where([
            'created_by_id' => \Yii::$app->user->id
        ])->count();
        // $plandetail = SubscriptionPlan::findOne($plan['subscription_id']);
        // if (! empty($plandetail)) {
        // if ($userAddresses < $plandetail->no_of_address) {
        $model = new Address();
        if ($userAddresses < Address::LIMIT) {
            $model->state_id = Address::STATE_ACTIVE;
        } else {
            $model->state_id = Address::STATE_INACTIVE;
        }
        $post = \yii::$app->request->post();

        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            $model->user_id = \Yii::$app->user->id;
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Address added sucessfully '));
            } else {
                \Yii::$app->getSession()->setFlash('failure', \Yii::t('app', $model->getErrorsString()));
            }
        }
        // } else {
        // \Yii::$app->getSession()->setFlash('failure', \Yii::t('app', 'You have reached your address limit'));
        // }
        // } else {
        // \Yii::$app->getSession()->setFlash('failure', \Yii::t('app', "Plan doesn't exist anymore"));
        // }
        // } else {
        // \Yii::$app->getSession()->setFlash('failure', \Yii::t('app', 'you have not subscribed to any plan'));
        // }
        return $this->redirect([
            'user/user-plan'
        ]);
    }

    public function actionChangepassword($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        if (! ($model->isAllowed()))
            throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

        $newModel = new User([
            'scenario' => 'changepassword'
        ]);
        if (Yii::$app->request->isAjax && $newModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return TActiveForm::validate($newModel);
        }
        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->newPassword);
            $model->last_password_change = date('Y-m-d H:i:s');
            $model->generateAuthKey();
            if ($model->save()) {
                $model->generateAuthKey();
                Yii::$app->getSession()->setFlash('success', 'Password Changed');
                return $this->redirect([
                    'dashboard/index'
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('error', 'password is not changed,change your phone number to number');
            }
        }
        $this->updateMenuItems($model);
        return $this->render('changepassword', [
            'model' => $newModel
        ]);
    }

    public function actionDownloadApk()
    {
        /*
         * $model = User::findOne ( [
         * 'profile_file' => $profile_file
         * ] );
         */
        $file = UPLOAD_PATH . '../../apk/ji_talent_app.apk';

        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    public function actionThemeParam()
    {
        $is_collapsed = Yii::$app->session->get('is_collapsed', 'sidebar-collapsed');
        $is_collapsed = empty($is_collapsed) ? 'sidebar-collapsed' : '';
        Yii::$app->session->set('is_collapsed', $is_collapsed);
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
                {
                    $this->menu['add'] = array(
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ],
                        'visible' => User::isAdmin()
                    );
                }
                break;
            case 'add':
                {
                    $this->menu['manage'] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => User::isAdmin()
                    );
                }
                break;
            default:
            case 'view':

                if ($model != null && $model->id != 1)
                    $this->menu['shadow'] = array(
                        'label' => '<span class="glyphicon glyphicon-refresh ">Shadow</span>',
                        'title' => Yii::t('app', 'Login as ' . $model),
                        'url' => [
                            '/shadow/session/login',
                            'id' => $model->id
                        ],
					/* 'htmlOptions'=>[], */
					'visible' => false
                    );

                $this->menu['add'] = array(
                    'label' => '<span class="glyphicon glyphicon-plus"></span>',
                    'title' => Yii::t('app', 'Add'),
                    'url' => [
                        'add'
                    ],
                    'visible' => false
                );

                if ($model != null)
                    $this->menu['changepassword'] = array(
                        'label' => '<span class="glyphicon glyphicon-paste"></span>',
                        'title' => Yii::t('app', 'changepassword'),
                        'url' => [
                            'changepassword',
                            'id' => $model->id
                        ],

                        'visible' => User::isManager()
                    );
                if ($model != null)
                    $this->menu['update'] = array(
                        'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                        'title' => Yii::t('app', 'Update'),
                        'url' => [
                            'update',
                            'id' => $model->id
                        ],

                        'visible' => User::isManager()
                    );

                $this->menu['manage'] = array(
                    'label' => '<span class="glyphicon glyphicon-list"></span>',
                    'title' => Yii::t('app', 'Manage'),
                    'url' => [
                        'index'
                    ],
                    'visible' => User::isManager()
                );
                if ($model != null)
                    $this->menu['delete'] = array(
                        'label' => '<span class="glyphicon glyphicon-trash"></span>',
                        'title' => Yii::t('app', 'Delete'),
                        'url' => [
                            'delete',
                            'id' => $model->id
                        ],

                        'visible' => false
                    );
        }
    }
}

