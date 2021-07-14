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
namespace app\modules\api\controllers;

use app\models\LoginForm;
use app\models\User;
use Yii;
use app\modules\api\components\ApiBaseController;
use app\modules\api\models\AccessToken;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\Cities;
use app\models\Countries;
use yii\data\ActiveDataProvider;
use app\modules\contact\models\Information;
use app\models\EmailQueue;
use app\modules\page\models\Page;
use app\modules\notification\models\Notification;
use app\models\SubscriptionPlan;
use app\models\UserDetail;
use app\models\HomeContent;
use app\models\Address;
use PhpParser\Node\Stmt\Else_;
use app\models\Product;
use app\models\Package;
use yii\data\ArrayDataProvider;

/**
 * UserController implements the API actions for User model.
 */

/**
 *
 * @OA\Info(
 *   version="1.0",
 *   title="Application API",
 *   description="Userimplements the API actions for User model.",
 *   @OA\Contact(
 *     name="Shiv Charan Panjeta",
 *     email="shiv@toxsl.com",
 *   ),
 * ),
 * @OA\Server(
 *   url="https://example.com/api",
 *   description="main server",
 * )
 * @OA\Server(
 *   url="https://dev.example.com/api",
 *   description="dev server",
 * )
 */
class UserController extends ApiBaseController
{

    public $modelClass = "app\models\User";

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
                            'check',
                            'logout',
                            'change-password',
                            'update-profile'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'signup',
                            'login',
                            'forget-password',
                            'get-city',
                            'get-country',
                            'contact-us',
                            'get-page',
                            'get-address',
                            'notification-list',
                            'get-banner',
                            'notification',
                            'add-address',
                            'delete-address',
                            'update-address',
                            'default-address',
                            'search'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*',
                            '@'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     *
     * @OA\Get(path="/signup",
     *   summary="signup",
     *   tags={"signup"},
     *   @OA\Parameter(
     *     name="full_name",
     *     in="header",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/models/user"),
     *     ),
     *   ),
     * )
     */
    public function actionSignup()
    {
        $data = [];
        $model = new User();
        $model->loadDefaultValues();
        $model->role_id = User::ROLE_USER;
        $model->state_id = User::STATE_ACTIVE;
        if ($model->load(Yii::$app->request->post())) {
            $email_identify = User::findByUsername($model->email);
            if (empty($email_identify)) {
                $model->setPassword($model->password);
                $model->generateAuthKey();

                if ($model->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                } else {
                    $data['message'] = $model->getErrors();
                }
            } else {
                $data['message'] = \yii::t('app', "Email already exists.");
            }
        } else {
            $data['message'] = "Data not posted.";
        }
        return $data;
    }

    public function actionCheck()
    {
        $data = [];

        if (! \Yii::$app->user->isGuest) {
            $data['detail'] = \Yii::$app->user->identity->asJson();
        } else {
            $this->setStatus(403);
            $data['message'] = \yii::t('app', "User not authenticated. No device token found");
        }

        return $data;
    }

    /**
     *
     * @return string|string[]|NULL[]
     */
    public function actionLogin()
    {
        $data = [];
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = User::findByUsername($model->username);
            if (! empty($user)) {
                if ($model->login()) {
                    $this->setStatus(200);
                    $data['access-token'] = $user->getAuthKey();
                    AccessToken::add($model, $user->getAuthKey());
                    $data['User-Detail'] = $user->asJson();
                } else {
                    $this->setStatus(400);
                    $data['message'] = ' Email and password is incorrect';
                }
            } else {
                $this->setStatus(400);
                $data['message'] = ' Email and password is incorrect';
            }
        } else {
            $this->setStatus(400);
            $data['message'] = "No data posted.";
        }
        return $data;
    }

    public function actionLogout()
    {
        $data = [];
        $user = \Yii::$app->user->identity;
        if (\Yii::$app->user->logout()) {
            $user->generateAuthKey();
            $user->updateAttributes([
                'activation_key'
            ]);
            AccessToken::deleteOldAppData($user->id);
            $this->setStatus(200);
            $data['detail'] = 'Logout successfuly';
        }

        return $data;
    }

    public function actionGetAddress($page = NULL)
    {
        $model = Address::find()->where([
            'created_by_id' => \Yii::$app->user->id
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionChangePassword()
    {
        $data = [];

        $model = \Yii::$app->user->identity;

        $newModel = new User([
            'scenario' => 'changepassword'
        ]);
        if ($newModel->load(\Yii::$app->request->post())) {

            $model->setPassword($newModel->newPassword);
            if ($model->save()) {
                $data['message'] = 'Password changed successfully';
                $this->setStatus(200);
            } else {
                $this->setStatus(400);
                $data['message'] = 'Incorrect Password';
            }
        } else {
            $this->setStatus(400);
            $data['message'] = ' data not posted';
        }
        return $data;
    }

    public function actionForgetPassword()
    {
        $data = [];
        $model = new User();
        $model->scenario = 'token_request';
        $post = \Yii::$app->request->post();
        if (isset($post['User'])) {
            $email = trim($post['User']['email']);
            if ($email != null) {

                $user = User::findOne([
                    'email' => $email
                ]);
                if ($user) {
                    $user->generatePasswordResetToken();
                    if (! $user->save()) {
                        $this->setStatus(400);
                        $data['message'] = \Yii::t('app', "Cant Generate Authentication Key.");
                        // throw new HttpException("Cant Generate Authentication Key");
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
                    $data['message'] = \Yii::t('app', "Please check your email to reset your password.");
                } else {
                    $this->setStatus(400);
                    $data['message'] = \Yii::t('app', "Email is not registered.");
                }
            } else {
                $this->setStatus(400);
                $data['message'] = \Yii::t('app', "Email cannot be blank");
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', "Data Not Posted ");
        }
        return $data;
    }

    public function actionUpdateProfile()
    {
        $data = [];
        $model = \Yii::$app->user->identity;
        $modelUserDetail = UserDetail::find()->where([
            'user_id' => \Yii::$app->user->id
        ])->one();
        if (empty($modelUserDetail)) {
            $modelUserDetail = new UserDetail();
        }
        if (! empty($model)) {
            $old_image = $model->profile_file;
            $post = \Yii::$app->request->bodyParams;
            if ($model->load($post) || $modelUserDetail->load($post)) {

                $model->full_name = $model->getFullName();

                $model->saveUploadedFile($model, 'profile_file', $old_image);
                if ($model->save() && $modelUserDetail->save()) {

                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                    $data['Userdetail'] = $modelUserDetail->asJson();
                } elseif ($model->save()) {

                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                } else {
                    $this->setStatus(400);
                    $data['message'] = $model->getErrors();
                }
            } else {
                $this->setStatus(400);
                $data['message'] = "Data not posted.";
            }
        }
        return $data;
    }

    public function actionDefaultAddress($id)
    {
        $data = [];
        $modelActive = Address::find()->where([
            'id' => $id,
            'created_by_id' => \Yii::$app->user->id
        ])->one();

        $modelInactive = Address::find()->where([
            'state_id' => Address::STATE_ACTIVE,
            'created_by_id' => \Yii::$app->user->id
        ])->one();
        if (! empty($modelActive) && ! empty($modelInactive)) {
            $modelActive->state_id = Address::STATE_ACTIVE;
            $modelInactive->state_id = Address::STATE_INACTIVE;

            if ($modelInactive->save() && $modelActive->save()) {
                $data['message'] = "set to default";
                $this->setStatus(200);
            } else {
                $this->setStatus(400);
                $data['message'] = $model->getErrors();
            }
        } else if (! empty($modelActive) && empty($modelInactive)) {
            $modelActive->state_id = Address::STATE_ACTIVE;

            if ($modelActive->save()) {
                $this->setStatus(200);
                $data['message'] = "set to default";
            } else {
                $this->setStatus(400);
                $data['message'] = $model->getErrors();
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'No Data Found');
        }
        return $data;
    }

    public function actionAddAddress()
    {
        $data = [];
        $user = \Yii::$app->user->identity;
        // $plan = $user->userDetail;

        // if (! empty($plan)) {
        // if (is_null($plan->subscription_id) == \app\modules\order\models\Order::STATE_INACTIVE) {
        $userAddresses = Address::find()->where([
            'created_by_id' => \Yii::$app->user->id
        ])->count();
        // $plandetail = SubscriptionPlan::findOne($plan['subscription_id']);
        // if (! empty($plandetail)) {
        // if ($userAddresses < $plandetail->no_of_address) {
        $model = new Address();
        if ($userAddresses == Address::STATE_INACTIVE) {
            $model->state_id = Address::STATE_ACTIVE;
        } else {
            $model->state_id = Address::STATE_INACTIVE;
        }

        $post = \yii::$app->request->post();

        if ($model->load($post)) {
            $model->date = date("Y-m-d");
            $model->user_id = \Yii::$app->user->id;
            if ($model->save()) {
                $this->setStatus(200);
                $data['message'] = "added address successfully";
            } else {
                $this->setStatus(400);
                $data['message'] = $model->getErrors();
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \yii::t('app', "Data not posted.");
        }
        // } else {
        // $this->setStatus(400);
        // $data['message'] = \yii::t('app', "you have reached your limit.");
        // }
        // } else {
        // $this->setStatus(404);
        // $data['message'] = \yii::t('app', "Plan Doesn't exist anymore.");
        // }
        // } else {
        // $this->setStatus(404);
        // $data['message'] = \yii::t('app', "you have not subscribed to any plan.");
        // }
        // } else {
        // $this->setStatus(404);
        // $data['message'] = \yii::t('app', "No details found.");
        // }
        return $data;
    }

    public function actionUpdateAddress($id)
    {
        $data = [];
        $user = \Yii::$app->user->identity;
        $plan = $user->userDetail;
        $model = Address::findOne($id);
        $post = \yii::$app->request->post();
        if ($model->load($post)) {
            $model->date = date("Y-m-d");
            $model->user_id = \Yii::$app->user->id;
            if ($model->save()) {
                $this->setStatus(200);

                $data['message'] = \yii::t('app', "Updated address successfully.");
            } else {
                $this->setStatus(400);
                $data['message'] = $model->getErrors();
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \yii::t('app', "Data not posted.");
        }
        return $data;
    }

    public function actionDeleteAddress($id)
    {
        $data = [];
        $model = Address::findOne($id);
        if ($model) {
            if ($model->delete()) {
                $this->setStatus(200);
                $data['message'] = \yii::t('app', "address deleted successfully.");
            } else {
                $this->setStatus(400);
                $data['message'] = $model->getErrors();
            }
        } else {
            $this->setStatus(404);
            $data['message'] = \yii::t('app', "NO data found.");
        }
        return $data;
    }

    public function actionGetCity($country_id, $name, $page = null)
    {
        if (! empty($name) && ! empty($country_id)) {
            $query = Cities::find()->andWhere([
                'country_id' => $country_id
            ])->andWhere([
                'like',
                'name',
                $name
            ]);
        } else {
            $query = Cities::find()->where([
                'country_id' => $country_id
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionSearch($search, $page = null)
    {
        $query = Product::find()->where([
            'like',
            'title',
            $search
        ]);
        $query1 = Package::find()->where([
            'like',
            'name',
            $search
        ]);

        $dataProvider1 = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);

        $dataProvider2 = new ActiveDataProvider([
            'query' => $query1,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);

        $data = array_merge($dataProvider1->getModels(), $dataProvider2->getModels());

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data
        ]);

        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionGetCountry($name, $page = NULL)
    {
        $post = \Yii::$app->request->post();
        if (! empty($name)) {
            $query = Countries::find()->where([
                'like',
                'name',
                $name
            ]);
        } else {
            $query = Countries::find();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionContactUs()
    {
        $data = [];
        $model = new Information();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $from = $model->email;
            $message = \yii::$app->view->renderFile('@app/mail/contact.php', [
                'user' => $model
            ]);
            $sub = 'New Contact Mail: ';
            EmailQueue::sendEmailToAdmins([
                'from' => $from,
                'subject' => $sub,
                'html' => $message
            ], false);
            $this->setStatus(200);
            $data['message'] = Yii::t('app', 'Warm Greetings!! Thank you for contacting us. We have received your request. Our representative will contact you soon.');
        } else {
            $data['message'] = Yii::t('app', 'Please enter Data!');
        }
        return $data;
    }

    public function actionGetPage($type_id)
    {
        $model = Page::find()->where([
            'type_id' => $type_id,
            'state_id' => Page::STATE_ACTIVE
        ])->one();
        if (! empty($model)) {
            $this->setStatus(200);
            $data['detail'] = $model->asJson();
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'No Data Found');
        }
        return $data;
    }

    public function actionNotification()
    {
        $model = User::find()->where([
            'id' => \Yii::$app->user->id
        ])->one();
        $post = \Yii::$app->request->post();
        if (! empty($model)) {
            if ($model->load($post)) {
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                } else {
                    $this->setStatus(400);
                    $data['message'] = $model->getErrors();
                }
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'No Data Found');
        }
        return $data;
    }

    public function actionNotificationList($page = null)
    {
        $query = Notification::find()->where([
            'to_user_id' => \Yii::$app->user->id
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 100,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionGetBanner($page = null)
    {
        $query = HomeContent::find()->where([
            'type_id' => HomeContent::TYPE2
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 100,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }
}
