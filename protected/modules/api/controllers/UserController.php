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
namespace app\modules\api\controllers;

use app\models\LoginForm;
use app\models\User;
use app\modules\api\components\ApiBaseController;
use app\modules\api\models\AccessToken;
use app\modules\contact\models\Information;
use app\modules\favorite\models\Item;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\modules\notification\models\Notification;
use app\models\Emoji;
use app\models\Domain;
use app\models\Language;
use app\models\Activity;
use app\models\TargetArea;
use app\models\TargetTrade;
use app\models\News;
use app\modules\comment\models\Comment;
use app\modules\comment\models\Reason;
use app\modules\chat\models\Chat;
use app\models\PortfolioDetail;
use app\models\Opinion;
use app\models\Network;
use app\models\Reward;
use yii\data\ArrayDataProvider;

/**
 * UserController implements the API actions for User model.
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
                            'signup',
                            'forgot-password',
                            'profile-update',
                            'add-news',
                            'notification-list',
                            'target-area-list',
                            'target-trade-list',
                            'emoji-list',
                            'comment-count',
                            'news-list',
                            'activity-list',
                            'language-list',
                            'domain-list',
                            'add-comment',
                            'news-update',
                            'contact-us',
                            'login'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isGuest();
                        }
                    ],
                    [
                        'actions' => [
                            'check',
                            'profile-update',
                            'logout',
                            'detail',
                            'add-news',
                            'mark-favorite',
                            'notification-list',
                            'favorite-list',
                            'activity-list',
                            'add-comment',
                            'target-area-list',
                            'target-trade-list',
                            'emoji-list',
                            'comment-count',
                            'language-list',
                            'comment-like',
                            'add-promotion',
                            'comment-list',
                            'news-update',
                            'news-list',
                            'news-like',
                            'domain-list',
                            'online-list',
                            'recent-user',
                            'all-users',
                            'user-detail',
                            'user-status',
                            'all-online-users-list',
                            'comment-reason-list',
                            'forgot-password',
                            'change-password',
                            'user-search',
                            'comment-delete',
                            'send-message',
                            'receive-message',
                            'chat-list',
                            'user-list',
                            'news-delete',
                            'comment-list',
                            'add-portfolio',
                            'portfolio-list',
                            'portfolio-delete',
                            'portfolio-update',
                            'add-opinion',
                            'opinion-list',
                            'opinion-delete',
                            'opinion-update',
                            'add-network',
                            'network-list',
                            'network-delete',
                            'network-update',
                            'news-search',
                            'add-reward',
                            'reward-list',
                            'top-rating-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isClient() || User::isManager() || User::isUser();
                        }
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
    }

    public function actionSignup()
    {
        $data = [];
        $model = new User();
        $model->loadDefaultValues();
        $model->scenario = 'user-signup';
        $model->state_id = User::STATE_ACTIVE;
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $email_identify = User::findByUsername($model->email);
            if (empty($email_identify)) {
                $model->role_id = (int) $post['User']['role_id'];
                $model->full_name = $model->getFullName($post['User']['first_name'], $post['User']['last_name']);
                $model->email_verified = User::EMAIL_VERIFIED;
                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->scenario = 'add';
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Signed Up Successfully!!');
                    $data['detail'] = $model->asJson();
                } else {
                    $data['message'] = $model->getErrors();
                }
            } else {
                $data['message'] = \yii::t('app', "Email already exists.");
            }
        } else {
            $data['message'] = \yii::t('app', "Data not posted.");
        }
        return $data;
    }

    public function actionCheck()
    {
        $data = [];

        if (! \Yii::$app->user->isGuest) {
            $data['detail'] = \Yii::$app->user->identity->asJson();
        } else {
            $this->setStatus(400);
            $data['error'] = \yii::t('app', "User not authenticated. No device token found");
        }

        return $data;
    }

    public function actionLogin()
    {
        $data = [];
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post())) {

            $user = User::findByUsername($model->username);
            if (! isset($user)) {
                $user = User::find()->where([
                    'email' => $model->username
                ])->one();
            }
            if ($user) {
                if ($model->login()) {
                    $this->setStatus(200);
                    $user->generateAuthKey();
                    $user->is_online = User::ONLINE_TRUE;
                    $data['access-token'] = $user->getAuthKey();
                    $user->updateAttributes([
                        'activation_key',
                        'is_online'
                    ]);

                    AccessToken::add($model, $user->getAuthKey());

                    $data['detail'] = $user->asJson();
                } else {
                    $this->setStatus(400);
                    $data['message'] = $model->getErrors();
                }
            } else {
                $this->setStatus(400);
                $data['message'] = \Yii::t('app', 'User is not registered');
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', "No data posted.");
        }
        return $data;
    }

    public function actionLogout()
    {
        $data = [];
        $user = \Yii::$app->user->identity;
        if (\Yii::$app->user->logout()) {
            $user->is_online = User::ONLINE_FALSE;
            $user->generateAuthKey();
            $user->updateAttributes([
                'activation_key',
                'is_online'
            ]);
            AccessToken::deleteOldAppData($user->id);
            $this->setStatus(200);
            $data['message'] = \yii::t('app', "User Logout Successfully.");
        } else {
            $this->setStatus(400);
            $data['message'] = $user->getErrors();
        }

        return $data;
    }

    public function actionUserStatus($id, $status, $address, $latitude, $longitude)
    {
        $data = [];
        $user = User::findOne($id);
        if (! empty($user)) {
            $user->is_online = $status;
            $user->address = $address;
            $user->latitude = $latitude;
            $user->longitude = $longitude;
            $user->updateAttributes([
                'is_online',
                'address',
                'latitude',
                'longitude'
            ]);
            $this->setStatus(200);
            $data['message'] = \yii::t('app', "Data Updated Successfully.");
        } else {
            $data['message'] = \Yii::t('app', 'No User Found');
        }

        return $data;
    }

    public function actionForgotPassword()
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
                        throw new HttpException("Cant Generate Authentication Key");
                    }
                    $email = $user->email;
                    $sub = "Recover Your Account at: " . \Yii::$app->params['company'];
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

    public function actionContactUs()
    {
        $data = [];
        $model = new Information();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $sub = 'New Contact: ' . $model->full_name;
            $email = $model->email;
            $this->setStatus(200);
            $data['message'] = \Yii::t('app', 'Warm Greetings!! Thank you for contacting us. We have received your request. Our representative will contact you soon.');
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'No data posted');
        }
        return $data;
    }

    public function actionNotificationList()
    {
        $query = Notification::find()->where([
            'to_user_id' => \Yii::$app->user->identity
        ])->orderBy('created_on DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function actionProfileUpdate($id)
    {
        $user = User::findOne($id);
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        if (! empty($user)) {
            if ($user->load($post)) {
                $user->role_id = $post['User']['role_id'];
                $user->state_id = User::STATE_ACTIVE;
                $first_name = ! empty($post['User']['first_name']) ? $post['User']['first_name'] : '';
                $last_name = ! empty($post['User']['last_name']) ? $post['User']['last_name'] : '';
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->full_name = $user->getFullName($first_name, $last_name);
                $user->saveUploadedFile($user, 'profile_file');
                $user->saveUploadedFile($user, 'cover_file');
                if ($user->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $user->asJson();
                } else {
                    $data['message'] = $user->getErrors();
                }
            } else {
                $data['message'] = \Yii::t('app', 'No data posted');
            }
        } else {
            $data['message'] = \Yii::t('app', 'No User Found');
        }
        return $data;
    }

    public function actionChangePassword()
    {
        $data = [];
        $this->setStatus(400);
        $model = \Yii::$app->user->identity;
        $newModel = new User([
            'scenario' => 'changepassword'
        ]);
        if ($newModel->load(\Yii::$app->request->post())) {
            if ($model->validatePassword($newModel->oldPassword)) {
                $model->setPassword($newModel->password);
                $model->generateAuthKey();
                if ($model->updateAttributes([
                    'password'
                ])) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Password changed successfully');
                } else {
                    $data['message'] = \Yii::t('app', $model->getErrors());
                }
            } else {
                $data['message'] = \Yii::t('app', 'Old Password is not match');
            }
        }
        return $data;
    }

    public function actionNewsUpdate($id)
    {
        $news = News::findOne($id);
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        if (! empty($news)) {
            if ($news->load($post)) {
                $news->state_id = News::STATE_ACTIVE;
                $news->description = $post['News']['description'];
                $news->saveUploadedFile($news, 'image_file');
                if ($news->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $news->asJson();
                } else {
                    $data['message'] = $news->getErrors();
                }
            } else {
                $data['message'] = \Yii::t('app', 'No data posted');
            }
        } else {
            $data['message'] = \Yii::t('app', 'No News Found');
        }
        return $data;
    }

    public function actionAddComment()
    {
        $comment = new Comment();
        $post = \Yii::$app->request->post();
        $data = [];
        $this->setStatus(400);
        if ($comment->load($post)) {
            $comment->model_type = News::className();
            $comment->state_id = Comment::STATE_ACTIVE;
            if (! empty($comment->model_id)) {
                $post = News::findOne($comment->model_id);
                if ($comment->save()) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Comment Posted Successfully!!!');
                } else {
                    $data['message'] = $comment->getErrors();
                }
            } else {
                $data['detail'] = \Yii::t('app', 'No Post Found');
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Data Posted');
        }
        return $data;
    }

    public function actionMarkFavorite()
    {
        $data = [];
        $user = \Yii::$app->user->id;
        $post = \Yii::$app->request->post();
        $model = new Item();
        if ($model->load($post)) {

            $model->state_id = Item::IS_FAVORITE_YES;
            $model->model_type = User::className();
            $exists = Item::find()->where([
                'model_type' => User::className(),
                'model_id' => $model->model_id,
                'created_by_id' => $user
            ])->one();

            if (! empty($exists)) {
                $exists->delete();
                $this->setStatus(200);
                $data['message'] = \Yii::t('app', 'Removed from Favorites');
            } else {
                if ($model->save()) {
                    $user = User::find()->where([
                        'id' => $model->model_id
                    ])->one();
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Added to Favorites');
                    $data['detail'] = $model->asJson();
                    $data['favorite_user'] = $user->asJson();
                } else {
                    $data['message'] = $user->getErrors();
                }
            }
        } else {
            $data['message'] = \Yii::t('app', 'No favourites Posted');
        }

        return $data;
    }

    public function actionFavoriteList($page = false)
    {
        $query = Item::findActive()->my();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionEmojiList($page = false, $type)
    {
        $query = Emoji::findActive()->andWhere([
            'type_id' => $type
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionAddNews()
    {
        $data = [];
        $this->setStatus(400);
        $model = new News();
        $model->loadDefaultValues();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $model->description = $model->description;
            $model->saveUploadedFile($model, 'image_file');
            $model->state_id = News::STATE_ACTIVE;
            $model->start_date = \Yii::$app->formatter->asDate($model->start_date);
            $model->end_date = \Yii::$app->formatter->asDate($model->end_date);
            $model->start_time = $model->start_time;
            $model->end_time = $model->end_time;
            $model->duration = $model->description;
            $model->latitude = $model->latitude;
            $model->longitude = $model->longitude;
            $model->budget = $model->budget;
            $model->domain_id = $model->domain_id;
            $model->location = $model->location;
            $model->type_id = $model->type_id;
            if ($model->save()) {
                $this->setStatus(200);
                $data['detail'] = $model->asJson();
            } else {
                $data['message'] = $model->getErrorsString();
            }
        } else {
            $data['message'] = \Yii::t("app", 'Data not posted');
        }
        return $data;
    }

    public function actionNewsList($page = false, $domain)
    {
        $query = News::findActive()->andWhere([
            'domain_id' => $domain
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionDomainList($page = false)
    {
        $query = Domain::findActive();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionLanguageList($page = false)
    {
        $query = Language::findActive();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionTargetAreaList($page = false)
    {
        $query = TargetArea::findActive();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionCommentReasonList($page = false)
    {
        $query = Reason::findActive();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionTargetTradeList($page = false, $area)
    {
        $query = TargetTrade::findActive()->andWhere([
            'target_area_id' => $area
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionActivityList($page = false, $domain)
    {
        $query = Activity::findActive()->andWhere([
            'domain_id' => $domain
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionOnlineList($page = false, $domain)
    {
        $users = User::find()->where([
            'is_online' => User::ONLINE_TRUE,
            'domain_id' => $domain
        ]);

        $user = \Yii::$app->user->identity;
        if (! empty($user)) {
            $users = $users->andWhere([
                'not in',
                'id',
                $user
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function actionAllOnlineUsersList($page = false)
    {
        $users = User::find()->where([
            'is_online' => User::ONLINE_TRUE
        ]);
        $user = \Yii::$app->user->identity;
        if (! empty($user)) {
            $users = $users->andWhere([
                'not in',
                'id',
                $user
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function actionRecentUser($page = 0, $domain)
    {
        $query = User::find()->where([
            'domain_id' => $domain
        ])->limit(20);

        $user = \Yii::$app->user->identity;
        if (! empty($user)) {
            $query = $query->andWhere([
                'not in',
                'id',
                $user
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function actionAllUsers($page = 0, $domain)
    {
        $users = User::find()->where([
            'domain_id' => $domain
        ]);
        $user = \Yii::$app->user->identity;
        if (! empty($user)) {
            $users = $users->andWhere([
                'not in',
                'id',
                $user
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function actionUserDetail($id)
    {
        $data = [];
        $model = User::findOne($id);
        if (empty($model)) {
            throw new HttpException(403, \Yii::t('app', 'User Not Found'));
        }
        $this->setStatus(200);
        $data['list'] = $model->asJson();
        return $data;
    }

    public function actionNewsLike()
    {
        $data = [];
        $user = \Yii::$app->user->id;
        $post = \Yii::$app->request->post();
        $model = new Item();
        if ($model->load($post)) {
            $model->state_id = Item::IS_FAVORITE_YES;
            $model->model_type = News::className();
            $exists = Item::find()->where([
                'model_type' => News::className(),
                'model_id' => $model->model_id,
                'created_by_id' => $user
            ])->one();

            if (! empty($exists)) {
                $exists->delete();
                $this->setStatus(200);
                $data['message'] = \Yii::t('app', 'Unliked successfully');
            } else {
                if ($model->save()) {
                    $news = News::find()->where([
                        'id' => $model->model_id
                    ])->one();
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Liked successfully');
                    $data['detail'] = $model->asJson();
                    $data['news'] = $news->asJson();
                } else {
                    $data['message'] = $news->getErrors();
                }
            }
        } else {
            $data['message'] = \Yii::t('app', 'No News Posted');
        }

        return $data;
    }

    public function actionCommentLike()
    {
        $data = [];
        $user = \Yii::$app->user->id;
        $post = \Yii::$app->request->post();
        $model = new Item();
        if ($model->load($post)) {
            $model->state_id = Item::IS_FAVORITE_YES;
            $model->model_type = Comment::className();
            $exists = Item::find()->where([
                'model_type' => Comment::className(),
                'model_id' => $model->model_id,
                'created_by_id' => $user
            ])->one();

            if (! empty($exists)) {
                $exists->delete();
                $this->setStatus(200);
                $data['message'] = \Yii::t('app', 'Unliked successfully');
            } else {
                if ($model->save()) {
                    $comment = Comment::find()->where([
                        'id' => $model->model_id
                    ])->one();
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Liked successfully');
                    $data['detail'] = $model->asJson();
                    $data['comment'] = $comment->asJson();
                } else {
                    $data['message'] = $comment->getErrors();
                }
            }
        } else {
            $data['message'] = \Yii::t('app', 'No News Posted');
        }

        return $data;
    }

    public function actionCommentList($id, $page = null)
    {
        $data = [];
        $query = Comment::findActive()->andWhere([
            'model_type' => News::className(),
            'model_id' => $id
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'page' => $page
            ]
        ]);

        $data['comment_count'] = $query->count();
        $data['comment-list'] = $dataProvider;
        $this->setStatus(200);
        return $data;
    }

    public function actionNewsDelete($id)
    {
        $data = [];
        $news = News::findOne($id);
        if (! empty($news)) {
            if ($news->delete()) {
                $data['message'] = \Yii::t('app', 'News Deleted Successfully!!!');
                $data['detail'] = $news->asJson();
            } else {
                $data['message'] = $news->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', 'No News Found');
        }
        return $data;
    }

    public function actionCommentDelete($id)
    {
        $data = [];
        $comment = Comment::findOne($id);
        if (! empty($comment)) {
            if ($comment->delete()) {
                $data['message'] = \Yii::t('app', 'Comment Deleted Successfully!!!');
                $data['detail'] = $comment->asJson();
            } else {
                $data['message'] = $comment->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Comment Found');
        }
        return $data;
    }

    public function actionUserSearch($page = null, $full_name = null, $domain = null)
    {
        $query = User::find()->where([
            'u.state_id' => User::STATE_ACTIVE
        ])
            ->alias('u')
            ->joinWith('domain as d');

        $user = \Yii::$app->user->identity;
        $query->andFilterWhere([
            'like',
            'u.full_name',
            $full_name
        ]);
        if (! empty($domain)) {
            $query = $query->andFilterWhere([
                'like',
                'd.title',
                $domain
            ]);
        }

        if (! empty($user)) {
            $query = $query->andWhere([
                'not in',
                'u.id',
                $user->id
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function actionSendMessage()
    {
        \Yii::$app->response->format = "json";
        $this->setStatus(400);
        $data = [];
        $chat = new Chat();
        $post = \Yii::$app->request->post();
        if ($chat->load($post)) {
            $fromID = \Yii::$app->user->id;
            $chat->from_id = $fromID;
            $chat->saveUploadedFile($chat, 'message');
            if ($chat->save()) {
                $data['detail'] = $chat->asJson();
                $this->setStatus(200);
                Notification::create($param = [
                    'to_user_id' => $chat->to_id,
                    'created_by_id' => $chat->from_id,
                    'title' => \Yii::t('app', 'You have received a new message from ' . $chat->from->full_name),
                    'model' => $chat
                ], false);
            } else {
                $this->setStatus(400);
                $data['error'] = $chat->getErrors();
            }
        }
        return $data;
    }

    public function actionReceiveMessage($from_id)
    {
        $data = [];
        $this->setStatus(400);
        $query = Chat::find()->where([
            'from_id' => $from_id,
            'to_id' => \Yii::$app->user->id,
            'is_read' => Chat::IS_READ_NO
        ])->orderBy([
            'message' => SORT_DESC
        ]);

        $list = [];
        if (! empty($query->count())) {
            foreach ($query->each() as $model) {
                $list[] = $model->asJson();
                if ($model->to_id == \Yii::$app->user->id) {
                    $model->is_read = Chat::IS_READ_YES;
                }
                $model->updateAttributes([
                    'is_read'
                ]);
            }
        } else {
            $data['message'] = \yii::t('app', "No new message found.");
        }

        $data['list'] = $list;
        $this->setStatus(200);
        return $data;
    }

    public function actionChatList(int $to_user_id, $page = null)
    {
        $data = [];
        $user = User::findOne($to_user_id);
        $query = Chat::find()->where([
            'OR',
            [
                'from_id' => \Yii::$app->user->id,
                'to_id' => $to_user_id
            ],
            [
                'from_id' => $to_user_id,
                'to_id' => \Yii::$app->user->id
            ]
        ])->orderBy([
            'id' => SORT_DESC
        ]);

        if ($query->count() > User::STATE_INACTIVE) {
            foreach ($query->each() as $chat) {
                $data['list'][] = $chat->asJson();
                if ($chat->is_read == Chat::IS_READ_NO && $chat->to_id == \Yii::$app->user->id) {
                    $chat->is_read = Chat::IS_READ_YES;
                }
                $chat->updateAttributes([
                    'is_read'
                ]);
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Chat Found');
        }
        $data['detail'] = $user->asJson(false);
        $this->setStatus(200);
        return $data;
    }

    public function actionUserList($page = null)
    {
        $data = [];

        $user_ids = Chat::find()->where([
            'OR',
            [
                'to_id' => \Yii::$app->user->id
            ],
            [
                'from_id' => \Yii::$app->user->id
            ]
        ])->orderBy([
            'id' => SORT_DESC
        ]);
        $list = [];
        if ($user_ids->count() > User::STATE_INACTIVE) {
            foreach ($user_ids->each() as $user_id) {
                if (empty($user_id->group_id)) {
                    if ($user_id->from_id == \Yii::$app->user->id) {
                        $list[$user_id->to_id] = $user_id->to_id;
                    } else {
                        $list[$user_id->from_id] = $user_id->from_id;
                    }
                }
            }
        }
        $lists = array_filter($list);
        $query = User::findActive()->andWhere([
            'in',
            'id',
            $lists
        ])->orderBy([
            new \yii\db\Expression('FIELD (id, ' . implode(',', array_keys($lists)) . ')')
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionAddPortfolio()
    {
        $data = [];
        $this->setStatus(400);
        $model = new PortfolioDetail();
        $model->loadDefaultValues();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $model->title = $model->title;
            $model->description = $model->description;
            $model->saveUploadedFile($model, 'image_file');
            $model->state_id = PortfolioDetail::STATE_ACTIVE;
            if ($model->save()) {
                $this->setStatus(200);
                $data['detail'] = $model->asJson();
            } else {
                $data['message'] = $model->getErrorsString();
            }
        } else {
            $data['message'] = \Yii::t("app", 'Data not posted');
        }
        return $data;
    }

    public function actionPortfolioList($page = false)
    {
        $query = PortfolioDetail::findActive()->my()->orderBy([
            'id' => SORT_DESC
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionPortfolioDelete($id)
    {
        $data = [];
        $portfolio = PortfolioDetail::findOne($id);
        if (! empty($portfolio)) {
            if ($portfolio->delete()) {
                $data['message'] = \Yii::t('app', 'Portfolio Deleted Successfully!!!');
                $data['detail'] = $portfolio->asJson();
            } else {
                $data['message'] = $portfolio->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Portfolio Found');
        }
        return $data;
    }

    public function actionPortfolioUpdate($id)
    {
        $portfolio = PortfolioDetail::findOne($id);
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        if (! empty($portfolio)) {
            if ($portfolio->load($post)) {
                $portfolio->state_id = PortfolioDetail::STATE_ACTIVE;
                $portfolio->title = $post['PortfolioDetail']['title'];
                $portfolio->description = $post['PortfolioDetail']['description'];
                $portfolio->saveUploadedFile($portfolio, 'image_file');
                if ($portfolio->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $portfolio->asJson();
                } else {
                    $data['message'] = $portfolio->getErrors();
                }
            } else {
                $data['message'] = \Yii::t('app', 'No data posted');
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Portfolio Found');
        }
        return $data;
    }

    public function actionAddOpinion()
    {
        $data = [];
        $this->setStatus(400);
        $model = new Opinion();
        $model->loadDefaultValues();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $model->description = $model->description;
            $model->to_id = $model->to_id;
            $model->rating = $model->rating;
            $model->saveUploadedFile($model, 'image_file');
            $model->state_id = Opinion::STATE_ACTIVE;
            if ($model->save()) {
                $user = User::find()->Where([
                    'id' => $model->to_id
                ])->one();
                $avgRating = $user->getAverageRating();
                $user->rating = $avgRating;
                $user->updateAttributes([
                    'rating'
                ]);
                $this->setStatus(200);
                $data['detail'] = $model->asJson();
            } else {
                $data['message'] = $model->getErrorsString();
            }
        } else {
            $data['message'] = \Yii::t("app", 'Data not posted');
        }
        return $data;
    }

    public function actionOpinionList($page = false, $to_id)
    {
        $query = Opinion::findActive()->andWhere([
            'to_id' => $to_id
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionOpinionDelete($id)
    {
        $data = [];
        $opinion = Opinion::findOne($id);
        if (! empty($opinion)) {
            if ($opinion->delete()) {
                $data['message'] = \Yii::t('app', 'Opinion Deleted Successfully!!!');
                $data['detail'] = $opinion->asJson();
            } else {
                $data['message'] = $opinion->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Opinion Found');
        }
        return $data;
    }

    public function actionOpinionUpdate($id)
    {
        $opinion = Opinion::findOne($id);
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        if (! empty($opinion)) {
            if ($opinion->load($post)) {
                $opinion->state_id = Opinion::STATE_ACTIVE;
                $opinion->to_id = $post['Opinion']['to_id'];
                $opinion->description = $post['Opinion']['description'];
                $opinion->saveUploadedFile($opinion, 'image_file');
                if ($opinion->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $opinion->asJson();
                } else {
                    $data['message'] = $opinion->getErrors();
                }
            } else {
                $data['message'] = \Yii::t('app', 'No data posted');
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Opinion Found');
        }
        return $data;
    }

    public function actionAddNetwork()
    {
        $data = [];
        $this->setStatus(400);
        $model = new Network();
        $model->loadDefaultValues();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $model->to_id = $model->to_id;
            $model->state_id = Network::STATE_ACTIVE;
            if ($model->save()) {
                $this->setStatus(200);
                $data['detail'] = $model->asJson();
            } else {
                $data['message'] = $model->getErrorsString();
            }
        } else {
            $data['message'] = \Yii::t("app", 'Data not posted');
        }
        return $data;
    }

    public function actionNetworkList($page = false)
    {
        $query = Network::findActive();
        $user = \Yii::$app->user->identity;
        if (! empty($user)) {
            $query = $query->andWhere([
                'not in',
                'id',
                $user
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionNetworkDelete($to_id, $created_by_id)
    {
        $data = [];
        $network = Network::findActive()->andWhere([
            'to_id' => $to_id,
            'created_by_id' => $created_by_id
        ])->one();
        if (! empty($network)) {
            if ($network->delete()) {
                $data['message'] = \Yii::t('app', 'Network Deleted Successfully!!!');
                $data['detail'] = $network->asJson();
            } else {
                $data['message'] = $network->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Network Found');
        }
        return $data;
    }

    public function actionNetworkUpdate($id)
    {
        $network = Network::findOne($id);
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        if (! empty($network)) {
            if ($network->load($post)) {
                $network->state_id = Network::STATE_ACTIVE;
                $network->to_id = $post['Network']['to_id'];
                if ($network->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $network->asJson();
                } else {
                    $data['message'] = $network->getErrors();
                }
            } else {
                $data['message'] = \Yii::t('app', 'No data posted');
            }
        } else {
            $data['message'] = \Yii::t('app', 'No Network Found');
        }
        return $data;
    }

    public function actionNewsSearch($page = null, $title = null, $domain = null, $type_id = null)
    {
        $query = News::find()->alias('n')->where([
            'n.state_id' => News::STATE_ACTIVE
        ]);

        if (! empty($title)) {
            $query->andFilterWhere([
                'like',
                'n.title',
                $title
            ]);
        }
        if (! empty($domain)) {
            $query->andFilterWhere([
                'like',
                'n.domain_id',
                $domain
            ]);
        }
        if (! empty($type_id)) {
            $query->andFilterWhere([
                'like',
                'n.type_id',
                $type_id
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function actionAddReward()
    {
        $data = [];
        $this->setStatus(400);
        $model = new Reward();
        $model->loadDefaultValues();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $model->title = $model->title;
            $model->state_id = Reward::STATE_ACTIVE;
            if ($model->save()) {
                $this->setStatus(200);
                $data['detail'] = $model->asJson();
            } else {
                $data['message'] = $model->getErrorsString();
            }
        } else {
            $data['message'] = \Yii::t("app", 'Data not posted');
        }
        return $data;
    }

    public function actionRewardList($page = false)
    {
        $query = Reward::findActive();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionTopRatingList($page = false)
    {
        $query = User::findActive()->andWhere([
            'AND',
            [
                'NOT IN',
                'id',
                \Yii::$app->user->identity->id
            ],
            [
                'NOT IN',
                'role_id',
                User::ROLE_ADMIN
            ]
        ])->orderBy('rating DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }
}