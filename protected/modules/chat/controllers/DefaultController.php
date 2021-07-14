<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\chat\controllers;

use Yii;
use app\components\TController;
use app\models\User;
use app\modules\chat\Module;
use app\modules\chat\models\Chat;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\modules\appointment\models\Booking;

/**
 * Default controller for the `firestorechat` module
 */
class DefaultController extends TController
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
                            'chat-list',
                            'load-chat',
                            'send-message',
                            'image',
                            'index',
                            'load-new-message',
                            'unread-count'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'image'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*',
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'chat-list',
                            'load-chat',
                            'send-message',
                            'index',
                            'load-new-message',
                            'image',
                            'unread-count'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isDoctor() || User::isPatient();
                        }
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

            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => Chat::class,
                'attribute' => 'message'
            ]
        ];
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = \Yii::$app->user->identity;
        if (! empty($user)) {
            if ($user->role_id == User::ROLE_PATIENT) {
                $this->layout = 'patient-main';
            } else {
                $this->layout = 'doctor-main';
            }
        }
        return $this->render('index');
    }

    public function actionUnreadCount()
    {
        \Yii::$app->response->format = "json";

        $data = [];
        $unreadCount = Chat::find()->where([
            'to_id' => \Yii::$app->user->id
        ])
            ->andWhere([
            'is_read' => Chat::IS_READ_NO
        ])
            ->count();

        $data['unNotifiedMessage'] = Chat::notifyUsers();
        \Yii::$app->response->setStatusCode(200);
        $data['count'] = $unreadCount;

        return $data;
    }

    public function actionLoadNewMessage($user_id)
    {
        \Yii::$app->response->format = "json";
        \Yii::$app->response->setStatusCode(400);
        $data = [];

        $query = Chat::find()->where([
            'from_id' => $user_id,
            'to_id' => \Yii::$app->user->id
        ])->andWhere([
            'is_read' => Chat::IS_READ_NO
        ]);

        $list = [];
        if (! empty($query->count())) {
            $id = \Yii::$app->user->id;
            foreach ($query->each() as $model) {
                $alreadyRead = in_array($id, explode(',', $model->readers));
                if (! $alreadyRead) {
                    $model->readers = empty($model->readers) ?: $model->readers . ',' . $id;
                }
                $list[] = $model->asJson();
                if ($model->to_id == \Yii::$app->user->id) {
                    $model->is_read = Chat::IS_READ_YES;
                }

                $model->save(false, [
                    'is_read',
                    'readers'
                ]);
            }

            \Yii::$app->response->setStatusCode(200);
            $data['chat_messages'] = $list;
        } else {
            $data['error'] = "No new message found.";
        }

        return $data;
    }

    public function actionSendMessage()
    {
        \Yii::$app->response->format = "json";
        \Yii::$app->response->setStatusCode(400);
        $data = [];

        $chat = new Chat();
        $post = \Yii::$app->request->post();
        if ($chat->load($post) || ! empty($_FILES)) {
            $fromID = \Yii::$app->user->id;
            $users = [
                $chat->to_id,
                $fromID
            ];
            $chat->from_id = $fromID;
            $chat->users = implode(',', $users);
            $chat->is_read = Chat::IS_READ_NO;
            $chat->saveUploadedFile($chat, 'message');

            if (! $chat->save()) {
                $data['error'] = $chat->getErrorsString();
            } else {
                \Yii::$app->response->setStatusCode(200);
                $data['message'] = $chat->asJson();
            }
        }
        return $data;
    }

    public function actionChatList()
    {
        \Yii::$app->response->format = "json";
        \Yii::$app->response->setStatusCode(400);
        $data = [];

        $userModels = User::findActive();
        $list = [];
        if ($userModels->count() > User::STATE_INACTIVE) {
            $current_role = \Yii::$app->user->identity;
            foreach ($userModels->each() as $model) {
                $users = Chat::getUserDetail($model);
                if ($current_role->role_id == User::ROLE_PATIENT) {
                    if ($users['role_id'] == User::ROLE_DOCTOR && $users['is_profile'] == User::STEP_FIVE) {
                        $list[] = $users;
                    }
                } else if ($current_role->role_id == User::ROLE_DOCTOR) {
                    if ($users['role_id'] == User::ROLE_PATIENT && $users['is_profile'] == User::STEP_PATIENT_THREE) {
                        $list[] = $users;
                    }
                } else {
                    $data['error'] = "No user found.";
                }
            }
            usort($list, function ($a, $b) {
                $a = $a['last_message_id'];
                $b = $b['last_message_id'];

                if ($a == $b)
                    return 0;
                return ($a < $b) ? 1 : - 1;
            });

            \Yii::$app->response->setStatusCode(200);
            $data['chat_list'] = $list;
        } else {
            $data['error'] = "No chat found.";
        }

        return $data;
    }

    public function actionLoadChat($id, $page = 0)
    {
        \Yii::$app->response->format = "json";
        \Yii::$app->response->setStatusCode(400);
        $data = [];
        $toUser = Module::self()->identityClass::findOne($id);
        $query = Chat::find()->where([
            'OR',
            [
                'from_id' => $id,
                'to_id' => \Yii::$app->user->id
            ],
            [
                'from_id' => \Yii::$app->user->id,
                'to_id' => $id
            ]
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 8,
                'page' => $page
            ],

            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $list = [];
        if (! empty($dataProvider->getCount() > 0)) {
            $id = \Yii::$app->user->id;
            foreach ($dataProvider->getModels() as $model) {
                if ($model->is_read == Chat::IS_READ_NO && $model->to_id == \Yii::$app->user->id) {
                    $model->is_read = Chat::IS_READ_YES;
                }
                $alreadyRead = in_array($id, explode(',', $model->readers));
                if (! $alreadyRead) {
                    $model->readers = empty($model->readers) ?: $model->readers . ',' . $id;
                }
                $model->save(false, [
                    'is_read',
                    'readers'
                ]);
                $list[] = $model->asJson();
            }
            usort($list, function ($a, $b) {
                $a = $a['id'];
                $b = $b['id'];

                if ($a == $b)
                    return 0;
                return ($a < $b) ? - 1 : 1;
            });

            \Yii::$app->response->setStatusCode(200);
            $data['chat_messages'] = $list;

            $data['page'] = $page;
        } else {
            $data['error'] = "No chat found.";
        }
        $data['user_detail'] = ! empty($toUser) ? Chat::getUserDetail($toUser) : "";
        return $data;
    }

    function sortByID($a, $b, $keyName)
    {
        $a = $a[$keyName];
        $b = $b[$keyName];

        if ($a == $b)
            return 0;
        return ($a < $b) ? - 1 : 1;
    }

    public function actionUserList()
    {
        $query = \Yii::$app->request->get('query');
        \Yii::$app->response->setStatusCode(400);
        \Yii::$app->response->format = "json";
        $data = [];
        if (! empty($query)) {
            $userModels = User::find()->where([
                'OR',
                [
                    'like',
                    'full_name',
                    $query
                ],
                [
                    'like',
                    'last_name',
                    $query
                ]
            ])
                ->limit(20)
                ->all();
            $html = '';
            if (! empty($userModels)) {
                foreach ($userModels as $user) {
                    $html .= \Yii::$app->controller->renderPartial('user-list', [
                        'user' => $user
                    ]);
                }
                \Yii::$app->response->setStatusCode(200);
                $data['htmlData'] = $html;
            } else {
                $data['error'] = "No matching user found.";
            }
        } else {
            $data['error'] = "Start typing to search users";
        }
        return $data;
    }
}
