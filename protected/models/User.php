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

use app\modules\page\models\Page;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\order\models\Order;
use app\modules\order\models\Item;

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
 * @property integer $notification_status
 * @property string $about_me
 * @property string $contact_no
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $city
 * @property string $country
 * @property string $zipcode
 * @property string $language
 * @property string $profile_file
 * @property integer $tos
 * @property integer $customer_id
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

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_BANNED = 2;

    const STATE_DELETED = 3;

    const MALE = 0;

    const FEMALE = 1;

    const ROLE_ADMIN = 0;

    const ROLE_MANAGER = 1;

    const ROLE_USER = 2;

    const ROLE_CLIENT = 3;

    const ROLE_SUB_ADMIN = 4;

    const TYPE_ON = 0;

    const TYPE_OFF = 1;

    const IS_PROFILE_ONE = 1;

    const IS_PROFILE_TWO = 2;

    const IS_PROFILE_THREE = 3;

    const IS_PROFILE_FIVE = 5;

    const IS_PROFILE_SIX = 6;

    const MINRANGE = 999999;

    const MAXRANGE = 99999999999999;

    public $confirm_password;

    public $newPassword;

    public $oldPassword;

    public $name_on_card;

    public $card_number;

    public $cvc;

    public $expiry_date;

    public $expiry_month;

    public $first_name;

    public $last_name;

    public function __toString()
    {
        return (string) $this->full_name;
    }

    public function getFullName()
    {
        return $this->full_name = $this->first_name . ' ' . $this->last_name;
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
            self::ROLE_CLIENT
        ])->all();
    }

    public static function getSubAdmin()
    {
        return User::find()->andWhere([
            '<=',
            'role_id',
            self::ROLE_SUB_ADMIN
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

    public function getGender()
    {
        $list = self::getGenderOptions();
        return isset($list[$this->gender]) ? $list[$this->gender] : 'Not Defined';
    }

    public static function getRoleOptions($id = null)
    {
        $list = array(
            self::ROLE_USER => "User",
            self::ROLE_SUB_ADMIN => "Sub Admin"
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

    public function getErrorsString()
    {
        $out = '';
        if ($this->hasErrors()) {
            foreach ($this->errors as $error) {
                $out = implode('.', $error);
            }
        }

        return $out;
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
            self::STATE_INACTIVE => "In-activeate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_BANNED => "Ban",
            self::STATE_DELETED => "Delete"
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
            self::STATE_BANNED => "warning",
            self::STATE_DELETED => "danger"
        ];
        // return \yii\helpers\Html::tag('span', $this->getState(), ['class' => 'badge bg-' . $list[$this->state_id]]);
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
            if (! isset($this->created_by_id))
                $this->created_by_id = self::getCurrentUser();
        } else {}
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
            'email' => Yii::t('app', 'Email address'),
            'password' => Yii::t('app', 'Password'),
            'referral_code' => Yii::t('app', 'Referral Code'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'about_me' => Yii::t('app', 'About Me'),
            'contact_no' => Yii::t('app', 'Contact Number'),
            'address' => Yii::t('app', 'Address'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'language' => Yii::t('app', 'Language'),
            'profile_file' => Yii::t('app', 'Profile File'),
            'tos' => Yii::t('app', 'Tos'),
            'customer_id' => Yii::t('app', 'Customer Id'),
            'role_id' => Yii::t('app', 'Role'),
            'state_id' => Yii::t('app', 'State'),
            'notification_status' => Yii::t('app', 'Notification'),
            'type_id' => Yii::t('app', 'Type'),
            'last_visit_time' => Yii::t('app', 'Last Visit Time'),
            'last_action_time' => Yii::t('app', 'Last Action Time'),
            'last_password_change' => Yii::t('app', 'Last Password Change'),
            'login_error_count' => Yii::t('app', 'Login Error Count'),
            'activation_key' => Yii::t('app', 'Activation Key'),
            'timezone' => Yii::t('app', 'Timezone'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
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

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, [
            'created_by_id' => 'id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddress()
    {
        return $this->hasMany(Address::class, [
            'user_id' => 'id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserDetail()
    {
        return $this->hasOne(UserDetail::class, [
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

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        return $relations;
    }

    public function sendRegistrationMailtoAdmin()
    {
        $sub = 'New User Registerd Successfully';
        $from = $this->email;
        EmailQueue::sendEmailToAdmins([
            'from' => $from,
            'subject' => $sub,
            'view' => 'newUser',
            'viewArgs' => [
                'user' => $this
            ]
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
        UserDetail::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);
        Item::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);
        Order::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);
        SubscriptionBilling::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);
        Address::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);
        CharityDetail::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);
        \app\modules\api\Module::beforeDelete($this->id);
        \app\modules\feature\Module::beforeDelete($this->id);
        \app\modules\blog\Module::beforeDelete($this->id);
        \app\modules\favorite\Module::beforeDelete($this->id);
        \app\modules\page\Module::beforeDelete($this->id);
        \app\modules\faq\Module::beforeDelete($this->id);
        return true;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['register'] = [
            'full_name',
            'email',
            'confirm_password',
            'password',
            'referral_code'
        ];

        $scenarios['update'] = [
            'full_name',
            'email',
            'contact_no',
            'role_id',
            'state_id'
        ];

        $scenarios['add'] = [
            'full_name',
            'email',
            'password',
            'role_id'
        ];

        $scenarios['signup'] = [
            'full_name',
            'email',
            'contact_no',
            // 'city',
            // 'country',
            'password',
            'confirm_password'
        ];
        $scenarios['admin-signup'] = [
            'full_name',
            'email',
            'password'
        ];
        $scenarios['user-signup'] = [
            'full_name',
            'email',
            // 'city',
            // 'country',
            'contact_no',
            'password',
            'confirm_password',
            'referral_code'
        ];
        $scenarios['changepassword'] = [
            'newPassword',
            'confirm_password'
        ];
        $scenarios['change-user-password'] = [
            'newPassword',
            'confirm_password'
            // 'oldPassword',
        ];
        $scenarios['resetpassword'] = [
            'password'
        ];
        $scenarios['add-card'] = [
            'card_number',
            'cvc',
            'expiry_date'
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
                    'password',
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
                'email',
                'match',
                'pattern' => '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'
            ],
            [
                [
                    'newPassword',
                    'confirm_password'
                ],
                'required',
                'on' => [
                    'changepassword',
                    'update-password',
                    'change-user-password'
                ]
            ],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => "Passwords don't match",
                'on' => [
                    'changepassword',
                    'update-password',
                    'change-user-password'
                ]
            ],
            [
                'newPassword',
                'app\components\validators\TPasswordValidator'
            ],
            [
                [
                    'full_name',
                    'email',
                    'contact_no',
                    'password',
                    'confirm_password'
                ],
                'required',
                'on' => 'signup'
            ],
            [
                [
                    'full_name',
                    'email',
                    'contact_no',
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
                    'signup'
                ]
            ],
            [
                'password',
                'app\components\validators\TPasswordValidator'
            ],
            [
                [
                    'full_name'
                ],
                'filter',
                'filter' => function ($data) {
                    return ucwords($data);
                }
            ],
            [
                [
                    'first_name',
                    'last_name',
                    'search',
                    'date_of_birth',
                    'last_visit_time',
                    'last_action_time',
                    'last_password_change',
                    'created_on',
                    'customer_id'
                ],
                'safe'
            ],
            [
                [
                    'gender',
                    'tos',
                    'role_id',
                    'state_id',
                    'type_id',
                    'notification_status',
                    'login_error_count',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'first_name',
                    'last_name',
                    'full_name',
                    'email',
                    'about_me',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
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
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'timezone',
                    // 'password',
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
                'contact_no',
                'filter',
                'filter' => 'trim'
            ],
            [
                'contact_no',
                'number'
            ],
            [
                'contact_no',
                'number',
                'min' => '99999',
                'tooSmall' => 'phone number should be of atleast 5 digit',
                'max' => '999999999999999',
                'tooBig' => 'phone number should be of atleast 15 digit'
            ],
            [
                'contact_no',
                'unique',
                'targetClass' => '\app\models\User',
                'message' => 'Phone number is already in use'
            ],
            [
                [
                    'address',
                    'latitude',
                    'longitude'
                ],
                'string',
                'max' => 512
            ]
        ];
    }

    public function getSplitName($name)
    {
        $name = trim($name);
        $last_name = strstr($name, ' ');
        $first_name = strtok($name, ' ');
        return array(
            $first_name,
            $last_name
        );
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['full_name'] = $this->full_name;
        $json['first_name'] = $this->getSplitName($this->full_name)[0];
        $json['last_name'] = trim($this->getSplitName($this->full_name)[1]);
        $json['email'] = $this->email;
        $json['date_of_birth'] = $this->date_of_birth;
        $json['gender'] = $this->gender;
        $json['about_me'] = $this->about_me;
        $json['contact_no'] = $this->contact_no;
        $json['address'] = $this->address;
        $json['latitude'] = $this->latitude;
        $json['longitude'] = $this->longitude;
        $json['city'] = $this->city;
        $json['country'] = $this->country;
        $json['zipcode'] = $this->zipcode;
        $json['language'] = $this->language;
        if (isset($this->profile_file)) {
            $json['profile_file'] = $this->getImageUrl();
        }
        $json['tos'] = $this->tos;
        $json['customer_id'] = $this->customer_id;
        $json['role_id'] = $this->role_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['notification_status'] = $this->notification_status;
        $json['last_visit_time'] = $this->last_visit_time;
        $json['last_action_time'] = $this->last_action_time;
        $json['last_password_change'] = $this->last_password_change;
        $json['login_error_count'] = $this->login_error_count;
        $json['timezone'] = $this->timezone;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $list = $this->userDetail;
        if (is_array($list)) {
            $relationData = [];
            foreach ($list as $item) {
                $relationData[] = $item->asJson();
            }
            $json['userDetail'] = $relationData;
        } else {
            $json['userDetail'] = $list;
        }
        if ($with_relations) {
            // EmailAccounts
            $list = $this->getEmailAccounts()->all();

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['EmailAccounts'] = $relationData;
            } else {
                $json['EmailAccounts'] = $list;
            }
            // EmailAccountRules
            $list = $this->getEmailAccountRules()->all();

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['EmailAccountRules'] = $relationData;
            } else {
                $json['EmailAccountRules'] = $list;
            }
            // LoginHistories
            $list = $this->getLoginHistories()->all();

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['LoginHistories'] = $relationData;
            } else {
                $json['LoginHistories'] = $list;
            }
            // Pages
            $list = $this->getPages()->all();

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['Pages'] = $relationData;
            } else {
                $json['Pages'] = $list;
            }
            // Rules
            $list = $this->getRules()->all();

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['Rules'] = $relationData;
            } else {
                $json['Rules'] = $list;
            }
            // Templates
            $list = $this->getTemplates()->all();

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['Templates'] = $relationData;
            } else {
                $json['Templates'] = $list;
            }
        }
        return $json;
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
            'user/reset-password',
            'token' => $this->activation_key
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

        return ($user->isActive() && $user->role_id == User::ROLE_CLIENT || self::isUser());
    }

    public static function isUser()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_USER || self::isManager());
    }

    public static function isManager()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_MANAGER || self::isAdmin());
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

    public static function isGuest()
    {
        if (Yii::$app->user->isGuest) {
            return true;
        }
        return false;
    }

    public static function isSubAdmin()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_SUB_ADMIN);
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
        $view = 'sendPassword';
        $sub = "Welcome! Your new account is ready with" . \Yii::$app->name;

        EmailQueue::add([
            'to' => $this->email,
            'subject' => $sub,
            'view' => $view,
            'viewArgs' => [
                'user' => $this
            ]
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

    public function isAllowed()
    {
        if (User::isAdmin() || User::isSubAdmin()) {
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

            $model->email = isset($item['email']) ? $item['email'] : $faker->email;

            $model->date_of_birth = isset($item['date_of_birth']) ? $item['date_of_birth'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->gender = isset($item['gender']) ? $item['gender'] : 0;
            $model->password = isset($item['password']) ? $item['password'] : 'admin';

            $model->about_me = isset($item['about_me']) ? $item['about_me'] : $faker->text(10);

            $model->contact_no = isset($item['contact_no']) ? $item['contact_no'] : rand(self::MINRANGE, self::MAXRANGE);

            $model->address = isset($item['address']) ? $item['address'] : $faker->text(10);

            $model->latitude = isset($item['latitude']) ? $item['latitude'] : $faker->text(10);

            $model->longitude = isset($item['longitude']) ? $item['longitude'] : $faker->text(10);

            $model->city = isset($item['city']) ? $item['city'] : $faker->text(10);

            $model->country = isset($item['country']) ? $item['country'] : $faker->text(10);

            $model->zipcode = isset($item['zipcode']) ? $item['zipcode'] : $faker->text(10);

            $model->language = isset($item['language']) ? $item['language'] : $faker->text(10);

            $model->profile_file = isset($item['profile_file']) ? $item['profile_file'] : $faker->text(10);

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
