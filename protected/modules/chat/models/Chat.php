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
namespace app\modules\chat\models;

use Yii;
use app\models\Feed;
use yii\helpers\ArrayHelper;
use app\modules\chat\Module;
use app\models\User;
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_chat".
 *
 * @property integer $id
 * @property string $message
 * @property string $users
 * @property integer $from_id
 * @property integer $to_id
 * @property string $readers
 * @property integer $group_id
 * @property integer $message_type
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_read
 * @property integer $state_id
 * @property integer $type_id
 */
class Chat extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->message;
    }

    const TYPE_TEXT_MESSAGE = 0;

    const TYPE_MEDIA_FILE = 1;

    const TYPE_AUDIO_FILE = 2;
    
    const TYPE_DOCS = 3;

    const IS_READ_YES = 1;

    const IS_READ_NO = 0;

    const TYPE_READ = 1;

    const TYPE_UNREAD = 0;

    public static function getIsReadOptions()
    {
        return [
            self::IS_READ_NO => "No",
            self::IS_READ_YES => "Yes"
        ];
    }

    public function getIsRead()
    {
        $list = self::getIsReadOptions();
        return isset($list[$this->is_read]) ? $list[$this->is_read] : 'Not Defined';
    }

    public function getFrom()
    {
        return $this->hasOne(Module::self()->identityClass::className(), [
            'id' => 'from_id'
        ]);
    }

    public function getTo()
    {
        return $this->hasOne(Module::self()->identityClass::className(), [
            'id' => 'to_id'
        ]);
    }

    public static function getGroupOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3",
            "TYPE4"
        ];
    }

    public function getGroup()
    {
        $list = self::getGroupOptions();
        return isset($list[$this->group_id]) ? $list[$this->group_id] : 'Not Defined';
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_TEXT_MESSAGE => "text message",
            Self::TYPE_MEDIA_FILE => "Media file",
            Self::TYPE_AUDIO_FILE => "Audio file",
            self::TYPE_DOCS => 'Document File'
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->created_on)) {
                $this->created_on = date('Y-m-d H:i:s');
            }
            if (empty($this->updated_on)) {
                $this->updated_on = date('Y-m-d H:i:s');
            }
        } else {
            $this->updated_on = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'message'
                ],
                'required'
            ],
            [
                [
                    'users',
                    'readers'
                ],
                'string'
            ],
            [
                [
                    'from_id',
                    'to_id',
                    'group_id',
                    'is_read',
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on',
                    'readers',
                    'notified_users',
                    'message'
                ],
                'safe'
            ],
            [
                [
                    'message'
                ],
                'string',
                'max' => 1024
            ],
            [
                [
                    'message'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message'),
            'users' => Yii::t('app', 'Users'),
            'from_id' => Yii::t('app', 'From'),
            'to_id' => Yii::t('app', 'To'),
            'readers' => Yii::t('app', 'Readers'),
            'group_id' => Yii::t('app', 'Group'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'is_read' => Yii::t('app', 'Is Read'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type')
        ];
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['feeds'] = [
            'feeds',
            'Feed',
            'model_id'
        ];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        return $relations;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        // TODO : start here
        return true;
    }

    public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        // TODO : start here

        return true;
    }

    public function getMessage()
    {
        if ($this->type_id == self::TYPE_TEXT_MESSAGE) {
            return $this->message;
        } elseif ($this->type_id == self::TYPE_MEDIA_FILE || $this->type_id == self::TYPE_AUDIO_FILE) {
            if (! empty($this->message)) {
                $this->message = \Yii::$app->urlManager->createAbsoluteUrl([
                    'chat/message/image',
                    'id' => $this->id
                ]);
                return $this->message;
            } else {
                return false;
            }
        }
    }

    public function getLatestChat()
    {
        $chat = Chat::find()->where([
            'from_id' => \Yii::$app->user->id,
            'to_id' => $this->id
        ])
            ->orderBy([
            'id' => SORT_DESC
        ])
            ->one();
        return $chat;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        if ($this->type_id == Self::TYPE_MEDIA_FILE || $this->type_id == Self::TYPE_AUDIO_FILE) {
            if (! empty($this->message) && file_exists(UPLOAD_PATH . '/' . basename($this->message))) {
                $json['message'] = $this->getImageUrl();
            } else {

                $json['message'] = $this->message;
            }
        } else {
            if($this->type_id == self::TYPE_DOCS){
                $json['message'] = $this->message;
            }
            $json['message'] = $this->message;
        }
        $json['users'] = $this->users;
        $json['from_id'] = $this->from_id;
        $json['to_id'] = $this->to_id;
        $json['readers'] = $this->readers;
        $json['group_id'] = $this->group_id;
        // $json['message_type'] = $this->message_type;
        $json['created_on'] = date("Y-m-d H:i:s ", strtotime($this->created_on));
        $json['is_read'] = $this->is_read;
        $json['state_id'] = $this->state_id;
        $json['last_message'] = ! empty($this->getLastMessage($this->id)) ? $this->getLastMessage($this->id) :'';
        $json['to_user_name'] = $this->to->full_name;
        $json['to_user_profile_file'] = !empty($this->to->profile_file) ? $this->to->getImageUrl() : '';
        $json['from_user_profile_file'] = !empty($this->from->profile_file) ? $this->from->getImageUrl() : '';

        $json['type_id'] = $this->type_id;
        $json['unread_message_count'] = $this->messageCount;
        if ($with_relations) {}
        return $json;
    }

    public static function getUserDetail($model)
    {
        $json = [];
        $json['id'] = $model->id;
        $json['full_name'] = ucfirst($model->full_name);
        $json['email'] = $model->email;
        $json['date_of_birth'] = $model->date_of_birth;
        $json['gender'] = $model->gender;

        if (! empty($model->profile_file) && file_exists(UPLOAD_PATH . '/' . basename($model->profile_file))) {
            $json['profile_file'] = \Yii::$app->urlManager->createAbsoluteUrl([
                '/file/files',
                'file' => $model->profile_file
            ]);
        } else {
            $json['profile_file'] = \Yii::$app->view->theme->getUrl('/img/') . Module::self()->defaultUserPhoto;
        }

        $json['role_id'] = $model->role_id;
        $json['state_id'] = $model->state_id;
        $json['last_message'] = self::getLastMessage($model->id);
        $json['last_message_id'] = self::getLastMessage($model->id, true);
        $json['is_online'] = self::isOnline($model->id);
        $json['unread_message_count'] = self::unreadMessageCount($model->id);

        return $json;
    }
    
    public function getMessageCount()
    {
        $count = Chat::find()->where([
            'from_id' => $this->from_id,
            'to_id' => Yii::$app->user->id,
            'is_read' => Chat::IS_READ_NO
        ])->count();
        return $count;
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            '/chat/default/image'
        ];
        $params['id'] = $this->id;

        if (isset($this->message) && ! empty($this->message)) {
            $params['file'] = $this->message;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;

        return Url::toRoute($params);
    }

    public static function get_time_ago($time)
    {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return 'less than 1 second ago';
        }
        $condition = array(
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return 'about ' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }
    }

    public static function unreadMessageCount($id)
    {
        $count = Chat::find()->where([
            'from_id' => $id,
            'to_id' => Yii::$app->user->id,
            'is_read' => Chat::IS_READ_NO
        ])->count();
        return $count;
    }

    public function getUserName($id)
    {
        $model = User::findOne($id);
        if (! empty($model)) {
            return ! empty($model->first_name) ? $model->first_name . ' ' . $model->last_name : $model->full_name;
        }
        return \Yii::t("app", "Not Set");
    }

    public static function isOnline($user_id)
    {
        $checkUser = Module::self()->identityClass::findOne([
            'id' => $user_id
        ]);
        if (! empty($checkUser)) {
            $start = $checkUser->last_action_time;

            $end = date("Y-m-d H:i:s"); // Current time and date
            $diff = strtotime($end) - strtotime($start);
            $hours = floor($diff / (60 * 60));
            $mins = floor(($diff - ($hours * 60 * 60)) / 60);

            if ($mins > floatval(1)) {
                return self::get_time_ago(strtotime($checkUser->last_action_time));
            } else {
                return true;
            }
        }
        return false;
    }

    public static function getLastMessage($id, $return_id = false)
    {
        $otherUserMessage = Chat::find()->where([
            'OR',
            [
                'from_id' => $id,
                'to_id' => \Yii::$app->user->id
            ],
            [
                'from_id' => \Yii::$app->user->id,
                'to_id' => $id
            ]
        ])
            ->orderBy('created_on DESC')
            ->one();
        if (! empty($otherUserMessage)) {

            $message = $otherUserMessage->message;

            if ($otherUserMessage->type_id == self::TYPE_MEDIA_FILE) {
                $message = "Media File";
            }
            return ($return_id == false) ? $otherUserMessage->from->full_name . ": " . $message : $otherUserMessage->id;
        }
        $currentUserMessage = Chat::find()->where([
            'from_id' => \Yii::$app->user->id,
            'to_id' => $id
        ])
            ->orderBy('created_on DESC')
            ->one();

        if (! empty($currentUserMessage)) {
            $message = $currentUserMessage->message;

            if ($currentUserMessage->type_id == self::TYPE_MEDIA_FILE) {
                $message = "Media File";
            }
            return ($return_id == false) ? "You: " . $message /* . " <i class='fa fa-reply pull-right'></i>" */:$currentUserMessage->id;
        }

        return "No Message";
    }

    public static function notifyUsers()
    {
        $unNotifiedMessage = [];

        $id = \Yii::$app->user->id;
        $getUnreadMessages = self::find()->where([
            'to_id' => \Yii::$app->user->id,
            'is_read' => Self::IS_READ_NO
        ]);

        if (! empty($getUnreadMessages->count())) {
            foreach ($getUnreadMessages->each() as $unreadMessage) {

                $alreadyNotified = in_array($id, explode(',', $unreadMessage->notified_users));
                if (! $alreadyNotified) {

                    $NotificationRediectLink = \Yii::$app->urlManager->createAbsoluteUrl([
                        '/chat',
                        'id' => $unreadMessage->id
                    ]);
                    $unreadMessage->notified_users = empty($unreadMessage->notified_users) ?: $unreadMessage->notified_users . ',' . $id;
                    $message = $unreadMessage->from->full_name . " | " . $unreadMessage->message;

                    $unNotifiedMessage[] = [
                        'message' => $message,
                        'notification_url' => $NotificationRediectLink,
                        'header' => Yii::$app->params['company']
                    ];

                    $unreadMessage->updateAttributes([
                        'notified_users'
                    ]);
                }
            }
        }
        return $unNotifiedMessage;
    }
}
