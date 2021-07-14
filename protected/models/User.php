<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\models;

use app\components\helpers\TEmailTemplateHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\favorite\models\Item;
use app\modules\chat\models\Chat;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $date_of_birth
 * @property integer $gender
 * @property integer $emoji_file
 * @property integer $is_online
 * @property integer $is_favorite
 * @property string $about_me
 * @property string $rating
 * @property string $contact_no
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $city
 * @property string $country
 * @property integer $cover_file
 * @property string $zipcode
 * @property string $language
 * @property string $profile_file
 * @property integer $tos
 * @property integer $role_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $last_visit_time
 * @property string $last_action_time
 * @property string $last_password_change
 * @property integer $login_error_count
 * @property string $activation_key
 * @property string $timezone
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property LoginHistory[] $loginHistories
 * @property Page[] $pages
 * @property Company[] $companies
 */
class User extends \app\components\TActiveRecord implements \yii\web\IdentityInterface
{

    public $search;

    public $verifyCode;

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_BANNED = 2;

    const STATE_DELETED = 3;

    const MALE = 0;

    const FEMALE = 1;

    const ROLE_ADMIN = 0;

    const ROLE_CUSTOMER = 1;

    const ROLE_PROVIDER = 2;

    const ROLE_BUSINESS = 3;

    const TYPE_ON = 0;

    const TYPE_OFF = 1;

    const EMAIL_NOT_VERIFIED = 0;

    const EMAIL_VERIFIED = 1;

    const ONLINE_TRUE = 1;

    const ONLINE_FALSE = 2;

    public $confirm_password;

    public $newPassword;

    public $oldPassword;

    public function __toString()
    {
        return (string) $this->full_name;
    }

    public static function getActiveList()
    {
        return ArrayHelper::map(User::findActive()->all(), 'id', 'full_name');
    }

    public static function getClients()
    {
        return User::find()->andWhere([
            '<=',
            'role_id',
            self::ROLE_CUSTOMER
        ])->all();
    }

    public static function getGenderOptions($id = null)
    {
        $list = array(
            self::MALE => "Male",
            self::FEMALE => "Female"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
    }

    public static function getRoleOptions($id = null)
    {
        $list = array(
            self::ROLE_CUSTOMER => "Customer",
            self::ROLE_PROVIDER => "Provider",
            self::ROLE_BUSINESS => "Business"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
    }

    public function getRole()
    {
        $list = self::getRoleOptions();
        return isset($list[$this->role_id]) ? $list[$this->role_id] : 'Not Defined';
    }

    public static function getAdminRoleOptions($id = null)
    {
        $list = array(
            self::ROLE_ADMIN => "Admin",
            self::ROLE_CUSTOMER => "Customer",
            self::ROLE_PROVIDER => "Provider",
            self::ROLE_BUSINESS => "Business"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
    }

    public function getAdminRole()
    {
        $list = self::getAdminRoleOptions();
        return isset($list[$this->role_id]) ? $list[$this->role_id] : 'Not Defined';
    }

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Inactive",
            self::STATE_ACTIVE => "Active",
            self::STATE_BANNED => "Banned",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public static function getUserAction()
    {
        return [
            self::STATE_INACTIVE => "In-activate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_BANNED => "Ban",
            self::STATE_DELETED => "Delete"
        ];
    }

    public function getFullName($first_name, $last_name)
    {
        return $first_name . ' ' . $last_name;
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
            self::STATE_BANNED => "warning",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getTypeOptions()
    {
        return self::getRoleOptions();
    }

    public function getType()
    {
        return $this->getRole();
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->updated_on))
                $this->updated_on = date('Y-m-d H:i:s');
            if (! isset($this->created_by_id))
                $this->created_by_id = self::getCurrentUser();
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
        return '{{%user}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'emoji_file' => Yii::t('app', 'Emoji File'),
            'is_online' => Yii::t('app', 'Is Online'),
            'is_favorite' => Yii::t('app', 'Is Favorite'),
            'cover_file' => Yii::t('app', 'Cover File'),
            'about_me' => Yii::t('app', 'About Me'),
            'rating' => Yii::t('app', 'Rating'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'address' => Yii::t('app', 'Address'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'language' => Yii::t('app', 'Language'),
            'profile_file' => Yii::t('app', 'Profile File'),
            'tos' => Yii::t('app', 'Tos'),
            'role_id' => Yii::t('app', 'Role'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'last_visit_time' => Yii::t('app', 'Last Visit Time'),
            'last_action_time' => Yii::t('app', 'Last Action Time'),
            'last_password_change' => Yii::t('app', 'Last Password Change'),
            'login_error_count' => Yii::t('app', 'Login Error Count'),
            'activation_key' => Yii::t('app', 'Activation Key'),
            'timezone' => Yii::t('app', 'Timezone'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_by_id' => Yii::t('app', 'Created By'),
            'verifyCode' => Yii::t('app', 'Verification Code')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoginHistories()
    {
        return $this->hasMany(LoginHistory::class, [
            'user_id' => 'id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'templates',
            'Template',
            'id'
        ];
        $relations['user_id'] = [
            'loginHistories',
            'LoginHistory',
            'id'
        ];

        return $relations;
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ])->cache();
    }

    public function getEmoji()
    {
        return $this->hasOne(Emoji::className(), [
            'id' => 'emoji_id'
        ])->cache();
    }

    public function getDomain()
    {
        return $this->hasOne(Domain::className(), [
            'id' => 'domain_id'
        ])->cache();
    }

    public function getLanguage()
    {
        return $this->hasOne(Language::className(), [
            'id' => 'language_id'
        ])->cache();
    }

    public function getActivity()
    {
        return $this->hasOne(Activity::className(), [
            'id' => 'activity_id'
        ])->cache();
    }

    public function getTargetArea()
    {
        return $this->hasOne(TargetArea::className(), [
            'id' => 'target_area_id'
        ])->cache();
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        $relations['emoji_id'] = [
            'emoji',
            'Emoji',
            'id'
        ];
        $relations['domain_id'] = [
            'domain',
            'Domain',
            'id'
        ];
        return $relations;
    }

    public function sendRegistrationMailtoAdmin()
    {
        $sub = 'New User Registerd Successfully';
        $message = TEmailTemplateHelper::renderFile('@app/mail/newUser.php', [
            'user' => $this
        ]);
        $from = $this->email;
        EmailQueue::sendEmailToAdmins([
            'from' => $from,
            'subject' => $sub,
            'html' => $message
        ], true);
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        if ($this->id == \Yii::$app->user->id)
            return false;

        if (self::find()->count() <= 1)
            return false;

        LoginHistory::deleteRelatedAll([
            'user_id' => $this->id
        ]);
        Feed::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);
        \app\modules\feature\Module::beforeDelete($this->id);
        \app\modules\favorite\Module::beforeDelete($this->id);
        \app\modules\comment\Module::beforeDelete($this->id);

        // Delete actual file
        $filePath = UPLOAD_PATH . $this->profile_file;

        if (is_file($filePath)) {
            unlink($filePath);
        }

        return true;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['update'] = [
            'full_name',
            'email',
            'password',
            'role_id',
            'language',
            'profile_file',
            'rating',
            'state_id',
            'emoji_id',
            'activity_id',
            'target_area_id',
            'language_id',
            'domain_id'
        ];

        $scenarios['add'] = [
            'full_name',
            'email',
            'password',
            'role_id',
            'language',
            'rating',
            'state_id'
        ];

        $scenarios['signup'] = [
            'full_name',
            'email',
            'password',
            'confirm_password',
            'verifyCode',
            'captcha'
        ];

        $scenarios['changepassword'] = [
            'password',
            'oldPassword',
            'confirm_password'
        ];
        $scenarios['resetpassword'] = [
            'password',
            'confirm_password'
        ];
        $scenarios['user-signup'] = [
            'first_name',
            'last_name',
            'email',
            'password',
            'role_id',
            'date_of_birth',
            'confirm_password'
        ];
        $scenarios['changepassword-admin'] = [
            'newPassword',
            'oldPassword',
            'confirm_password'
        ];
        $scenarios['token_request'] = [
            'email'
        ];

        return $scenarios;
    }

    /**
     *
     * @inheritdoc
     */
    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'full_name',
                    'email',
                    'role_id',
                    'state_id',
                    'created_on'
                ],
                'required'
            ],
            [
                'email',
                'unique'
            ],
            [
                [
                    'password',
                    'confirm_password'
                ],
                'required',
                'on' => 'changepassword'
            ],

            [
                [
                    'password',
                    'confirm_password'
                ],
                'required',
                'on' => 'resetpassword'
            ],

            [
                [
                    'email'
                ],
                'required',
                'on' => 'token_request'
            ],
            [
                [
                    'rating'
                ],
                'number'
            ],
            [
                [
                    'profile_file'
                ],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg,jpeg'
            ],
            [
                [
                    'full_name',
                    'email',
                    'password',
                    'confirm_password',
                    'verifyCode'
                ],
                'required',
                'on' => 'signup'
            ],
            [

                [
                    'full_name',
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                    'confirm_password'
                ],
                'required',
                'on' => 'user-signup'
            ],

            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'password',
                'message' => "Passwords don't match",
                'on' => [
                    'signup',
                    'resetpassword'
                ]
            ],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => "Passwords don't match",
                'on' => [
                    'changepassword-admin'
                ]
            ],
            [
                'password',
                'app\components\validators\TPasswordValidator'
            ],
            [
                'newPassword',
                'app\components\validators\TPasswordValidator'
            ],
            [
                'confirm_password',
                'app\components\validators\TPasswordValidator'
            ],
            [
                [
                    'full_name',
                    'first_name',
                    'last_name'
                ],
                'app\components\validators\TNameValidator'
            ],

            [

                'email',
                'email'
            ],
            [
                [
                    'full_name',
                    'language'
                ],
                'filter',
                'filter' => function ($data) {
                    return ucwords($data);
                }
            ],
            [
                [
                    'search',
                    'date_of_birth',
                    'last_visit_time',
                    'last_action_time',
                    'first_name',
                    'last_name',
                    'is_online',
                    'is_favorite',
                    'last_password_change',
                    'created_on',
                    'emoji_id',
                    'activity_id',
                    'target_area_id',
                    'language_id',
                    'domain_id',
                    'longitude',
                    'website',
                    'portfolio_id',
                    'opinion_id'
                ],
                'safe'
            ],
            [
                [
                    'gender',
                    'tos',
                    'role_id',
                    'state_id',
                    'designation',
                    'type_id',
                    'login_error_count',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'full_name',
                    'first_name',
                    'last_name',
                    'email',
                    'about_me',
                    'cover_file',
                    'contact_no',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'emoji_file',
                    'timezone'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'full_name',
                    'email',
                    'about_me',
                    'contact_no',
                    'city',
                    'cover_file',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'emoji_file',
                    'timezone',
                    'password',
                    'activation_key',
                    'address',
                    'latitude',
                    'longitude'
                ],
                'trim'
            ],
            [
                [
                    'password',
                    'activation_key'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'address',
                    'latitude',
                    'longitude'
                ],
                'string',
                'max' => 512
            ],
            [
                [
                    'newPassword',
                    'oldPassword',
                    'confirm_password'
                ],
                'required',
                'on' => 'changepassword-admin'
            ],
            [
                'verifyCode',
                'captcha',

                'on' => 'signup'
            ]
        ];
    }

    public function getLanguageDetail()
    {
        $data = '';
        $list = explode(',', $this->language_id);
        if (! empty($list)) {
            $relationData = [];
            foreach ($list as $item) {
                if (! empty($item)) {
                    $item = Language::findOne($item);
                    $relationData[] = $item;
                }
            }
            $data = implode(',', $relationData);
        }
        return $data;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['full_name'] = $this->full_name;
        $json['first_name'] = $this->first_name;
        $json['last_name'] = $this->last_name;
        $json['email'] = $this->email;
        $json['access-token'] = $this->activation_key;
        $json['date_of_birth'] = $this->date_of_birth;
        $json['emoji_file'] = $this->emoji_file;
        $json['is-favorite'] = $this->getFavorites();
        $json['is-network'] = $this->getNetwork();
        $json['is_online'] = $this->is_online;
        $json['gender'] = $this->gender;
        $json['contact_no'] = $this->contact_no;
        $json['rating'] = $this->rating;
        $json['latitude'] = $this->latitude;
        $json['longitude'] = $this->longitude;
        $json['city'] = $this->city;
        $json['website'] = $this->website;
        $json['country'] = $this->country;
        $json['network-count'] = $this->getNetworkCount();
        $json['zipcode'] = $this->zipcode;
        if (! empty($this->profile_file)) {
            $json['profile_file'] = $this->getImageUrl();
        } else {
            $json['profile_file'] = '';
        }
        if (! empty($this->cover_file)) {
            $json['cover_file'] = $this->getCoverImageUrl();
        } else {
            $json['cover_file'] = '';
        }

        $json['tos'] = $this->tos;
        $json['role_id'] = $this->role_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['emoji_id'] = $this->emoji_id;
        $json['activity_id'] = $this->activity_id;
        $json['target_area_id'] = $this->target_area_id;
        $json['language_name'] = ! empty($this->languageDetail) ? $this->languageDetail : '';
        $json['language_id'] = $this->language_id;
        $json['domain_id'] = $this->domain_id;
        $json['website'] = $this->website;
        $json['emoji'] = ! empty($this->emoji->title) ? $this->emoji->title : '';
        $json['activity'] = ! empty($this->activity->title) ? $this->activity->title : '';
        $json['target-area'] = ! empty($this->targetArea->title) ? $this->targetArea->title : '';
        $json['domain'] = ! empty($this->domain->title) ? $this->domain->title : '';
        $json['timezone'] = $this->timezone;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['last_message'] = self::getLastMessage($this->id);
        $json['message_count'] = self::unreadMessageCount($this->id);
        $json['last_message_time'] = self::getLastMessageTime($this->id);
        $json['rating'] = $this->rating;
        $list = $this->opinion;
        if (is_array($list)) {
            $relationData = array_map(function ($item) {

                return $item->asUserJson();
            }, $list);

            $json['opinion_id'] = $relationData;
        } else {
            $json['opinion_id'] = $list;
        }
        $list = $this->portfolio;

        if (is_array($list)) {
            $relationData = array_map(function ($item) {

                return $item->asPortfolioJson();
            }, $list);

            $json['portfolio_id'] = $relationData;
        } else {
            $json['portfolio_id'] = $list;
        }

        return $json;
    }
    
    public function asProfileJson(){
        $json = [];
        $json['full_name'] = $this->full_name;
        if (! empty($this->profile_file)) {
            $json['profile_file'] = $this->getImageUrl();
        } else {
            $json['profile_file'] = '';
        }
        return $json;
    }

    public function getPortfolio()
    {
        return $this->hasMany(PortfolioDetail::className(), [
            'created_by_id' => 'id'
        ]);
    }

    public function getOpinion()
    {
        return $this->hasMany(Opinion::className(), [
            'to_id' => 'id'
        ]);
    }

    public function getAverageRating()
    {
        return Opinion::find()->where([
            'to_id' => $this->id
        ])->average('rating');
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

            if ($otherUserMessage->type_id == Chat::TYPE_MEDIA_FILE) {
                $message = "Media File";
            }
            return ($return_id == false) ? $message : $otherUserMessage->id;
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

    public static function getLastMessageTime($id, $return_id = false)
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

            $created_on = $otherUserMessage->created_on;

            if ($otherUserMessage->type_id == Chat::TYPE_MEDIA_FILE) {
                $message = "Media File";
            }
            return ($return_id == false) ? $created_on : $otherUserMessage->id;
        }
        $currentUserMessage = Chat::find()->where([
            'from_id' => \Yii::$app->user->id,
            'to_id' => $id
        ])
            ->orderBy('created_on DESC')
            ->one();

        if (! empty($currentUserMessage)) {
            $created_on = $currentUserMessage->created_on;

            if ($currentUserMessage->type_id == self::TYPE_MEDIA_FILE) {
                $message = "Media File";
            }
            return ($return_id == false) ? $created_on/* . " <i class='fa fa-reply pull-right'></i>" */:$currentUserMessage->id;
        }

        return "No Message";
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

    public function getFavorites()
    {
        $query = Item::find()->where([
            'model_type' => self::className(),
            'model_id' => $this->id
        ])
            ->my()
            ->exists();
        return $query;
    }

    public function getNetwork()
    {
        $query = Network::find()->where([
            'to_id' => $this->id
        ])
            ->my()
            ->exists();
        return $query;
    }

    /**
     *
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    /**
     *
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([
            'activation_key' => $token,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public function getUsername()
    {
        return substr($this->email, 0, strpos($this->email, '@'));
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if (! strstr($username, '@')) {
            $username = $username . '@toxsltech.com';
        }
        return static::findByEmail($username);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token
     *            password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (! static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'activation_key' => $token,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token
     *            password reset token
     * @return boolean
     */
    public function getResetUrl()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'user/resetpassword',
            'token' => $this->activation_key
        ]);
    }

    public function getVerified()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'user/confirm-email',
            'id' => $this->activation_key
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     *
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     *
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->activation_key;
    }

    /**
     *
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $this->hashPassword($password);
    }

    public function hashPassword($password)
    {
        $password = utf8_encode(Yii::$app->security->generatePasswordHash(yii::$app->name . $password));
        return $password;
    }

    /**
     * Validates password
     *
     * @param string $password
     *            password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(yii::$app->name . $password, utf8_decode($this->password));
    }

    /**
     * convert normal password to hash password before saving it to database
     */

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->activation_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->activation_key = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->activation_key = null;
    }

    public static function isClient()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_CUSTOMER || self::isUser());
    }

    public static function isUser()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_CUSTOMER || self::isManager());
    }

    public static function isManager()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_ADMIN || self::isAdmin());
    }

    public function isSelf()
    {
        if ($this->id == Yii::$app->user->identity->id)
            return true;

        return false;
    }

    public static function isAdmin()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_ADMIN);
    }

    public function getNetworkCount()
    {
        $query = Network::find()->where([
            'to_id' => $this->id
        ])
            ->my()
            ->count();
        return $query;
    }

    public static function isGuest()
    {
        if (Yii::$app->user->isGuest) {
            return true;
        }
        return false;
    }

    public function isActive()
    {
        return ($this->state_id == User::STATE_ACTIVE);
    }

    public function getFeeds()
    {
        return $this->hasMany(Feed::className(), [
            'created_by_id' => 'id'
        ]);
    }

    public function sendRegistrationMailtoUser()
    {
        $message = TEmailTemplateHelper::renderFile('sendPassword', [
            'user' => $this
        ]);
        $sub = "Welcome! You new account is ready " . \Yii::$app->name;

        EmailQueue::add([
            'to' => $this->email,
            'subject' => $sub,
            'html' => $message
        ], true);
    }

    public function sendVerificationMailtoUser()
    {
        $sub = "Welcome! Your new account is ready for " . \Yii::$app->params['company'];
        $message = TEmailTemplateHelper::renderFile('@app/mail/verification.php', [
            'user' => $this
        ]);
        $from = \Yii::$app->params['adminEmail'];
        EmailQueue::add([
            'from' => $from,
            'to' => $this->email,
            'subject' => $sub,
            'html' => $message
        ], true);
    }

    public function getLoginUrl()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'user/login'
        ]);
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/image'
        ];
        $params['id'] = $this->id;

        if (isset($this->profile_file) && ! empty($this->profile_file)) {
            $params['file'] = $this->profile_file;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;

        return Url::toRoute($params);
    }

    public function getCoverImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/cover-image'
        ];
        $params['id'] = $this->id;

        if (isset($this->cover_file) && ! empty($this->cover_file)) {
            $params['file'] = $this->cover_file;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;

        return Url::toRoute($params);
    }

    public function isAllowed()
    {
        if (User::isAdmin()) {
            return true;
        }

        return parent::isAllowed();
    }

    public static function addData($data)
    {
        $faker = \Faker\Factory::create();
        if (self::find()->count() != 0)
            return;
        foreach ($data as $item) {
            $model = new self();

            $model->full_name = isset($item['full_name']) ? $item['full_name'] : $faker->name;

            $model->first_name = isset($item['first_name']) ? $item['first_name'] : $faker->name;

            $model->last_name = isset($item['last_name']) ? $item['last_name'] : $faker->name;

            $model->email = isset($item['email']) ? $item['email'] : $faker->email;

            $model->date_of_birth = isset($item['date_of_birth']) ? $item['date_of_birth'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->gender = isset($item['gender']) ? $item['gender'] : 0;

            $model->is_online = isset($item['is_online']) ? $item['is_online'] : $faker->boolean;

            $model->is_favorite = isset($item['is_favorite']) ? $item['is_favorite'] : $faker->boolean;

            $model->password = isset($item['password']) ? $item['password'] : 'admin';

            $model->about_me = isset($item['about_me']) ? $item['about_me'] : $faker->text(10);

            $model->contact_no = isset($item['contact_no']) ? $item['contact_no'] : $faker->text(10);

            $model->cover_file = isset($item['cover_file']) ? $item['cover_file'] : $faker->text(10);

            $model->rating = isset($item['rating']) ? $item['rating'] : 5;

            $model->address = isset($item['address']) ? $item['address'] : $faker->text(10);

            $model->city = isset($item['city']) ? $item['city'] : $faker->text(10);

            $model->country = isset($item['country']) ? $item['country'] : $faker->text(10);

            $model->zipcode = isset($item['zipcode']) ? $item['zipcode'] : $faker->text(10);

            $model->language = isset($item['language']) ? $item['language'] : $faker->text(10);

            $model->profile_file = isset($item['profile_file']) ? $item['profile_file'] : $faker->text(10);

            $model->emoji_file = isset($item['emoji_file']) ? $item['emoji_file'] : $faker->text(10);

            $model->tos = isset($item['tos']) ? $item['tos'] : 1;

            $model->role_id = isset($item['role_id']) ? $item['role_id'] : 1;

            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->last_visit_time = isset($item['last_visit_time']) ? $item['last_visit_time'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->last_action_time = isset($item['last_action_time']) ? $item['last_action_time'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->last_password_change = isset($item['last_password_change']) ? $item['last_password_change'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->login_error_count = isset($item['login_error_count']) ? $item['login_error_count'] : $faker->numberBetween();

            // $model->timezone = isset($item['timezone']) ? $item['timezone'] : $faker->text(10);

            $model->setPassword($model->password);

            $model->save();
        }
    }
}