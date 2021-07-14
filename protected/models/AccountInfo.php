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
namespace app\models;

use Yii;
use app\models\Feed;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_account_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $is_default_account
 * @property string $bank_name
 * @property string $bank_id
 * @property string $bank_token
 * @property string $routing_number
 * @property string $account_holder_name
 * @property string $stripe_number
 * @property string $account_number
 * @property string $card_number
 * @property string $card_expiry_date
 * @property integer $state_id
 * @property integer $type_id
 * @property integer $is_verify
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id
 * @property string $country_code
 * @property string $full_name
 * @property string $customer_id
 * @property User $createdBy
 * @property User $user
 */
class AccountInfo extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->user_id;
    }

    public static function getUserOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return self::listData ( User::findActive ()->all () );
    }

    public static function getBankOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getBank()
    {
        $list = self::getBankOptions();
        return isset($list[$this->bank_id]) ? $list[$this->bank_id] : 'Not Defined';
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const CARD_TYPE_DEBIT = 1;

    const CARD_TYPE_CREDIT = 2;

    const ACCOUNT_HOLDER_TYPE_INDIVIDUAL = 1;

    const ACCOUNT_HOLDER_TYPE_COMPANY = 2;

    const NOT_DEFAULT = 0;

    const IS_DEFAULT = 1;

    const NOT_VERIFIED = 0;

    const VERIFIED = 1;

    const PAYMENT_CURRENCY = 'usd';

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
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public static function getCustomerOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getCustomer()
    {
        $list = self::getCustomerOptions();
        return isset($list[$this->customer_id]) ? $list[$this->customer_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->user_id)) {
                $this->user_id = self::getCurrentUser();
            }
            if (empty($this->card_expiry_date)) {
                $this->card_expiry_date = date('Y-m-d');
            }
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->updated_on)) {
                $this->updated_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
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
        return '{{%account_info}}';
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
                    'user_id',
                    'state_id',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'user_id',
                    'is_default_account',
                    'state_id',
                    'type_id',
                    'is_verify',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on'
                ],
                'safe'
            ],
            [
                [
                    'bank_name',
                    'routing_number',
                    'stripe_number',
                    'account_number',
                    'card_number',
                    'card_expiry_date'
                ],
                'string',
                'max' => 256
            ],
            [
                [
                    'bank_id',
                    'bank_token',
                    'account_holder_name',
                    'country_code',
                    'full_name',
                    'customer_id'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'user_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'user_id' => 'id'
                ]
            ],
            [
                [
                    'bank_name',
                    'routing_number',
                    'stripe_number',
                    'account_number',
                    'card_number',
                    'card_expiry_date',
                    'bank_id',
                    'bank_token',
                    'account_holder_name',
                    'country_code',
                    'full_name',
                    'customer_id'
                ],
                'trim'
            ],
            [
                [
                    'bank_name'
                ],
                'app\components\validators\TNameValidator'
            ],
            [
                [
                    'account_holder_name'
                ],
                'app\components\validators\TNameValidator'
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
            ],
            [
                [
                    'full_name'
                ],
                'app\components\validators\TNameValidator'
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
            'user_id' => Yii::t('app', 'User'),
            'is_default_account' => Yii::t('app', 'Is Default Account'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_id' => Yii::t('app', 'Bank'),
            'bank_token' => Yii::t('app', 'Bank Token'),
            'routing_number' => Yii::t('app', 'Routing Number'),
            'account_holder_name' => Yii::t('app', 'Account Holder Name'),
            'stripe_number' => Yii::t('app', 'Stripe Number'),
            'account_number' => Yii::t('app', 'Account Number'),
            'card_number' => Yii::t('app', 'Card Number'),
            'card_expiry_date' => Yii::t('app', 'Card Expiry Date'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'is_verify' => Yii::t('app', 'Is Verify'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_by_id' => Yii::t('app', 'Created By'),
            'country_code' => Yii::t('app', 'Country Code'),
            'full_name' => Yii::t('app', 'Full Name'),
            'customer_id' => Yii::t('app', 'Customer')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), [
            'id' => 'user_id'
        ]);
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
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        $relations['user_id'] = [
            'user',
            'User',
            'id'
        ];
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

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['user_id'] = $this->user_id;
        $json['is_default_account'] = $this->is_default_account;
        $json['bank_name'] = $this->bank_name;
        $json['bank_id'] = $this->bank_id;
        $json['bank_token'] = $this->bank_token;
        $json['routing_number'] = $this->routing_number;
        $json['account_holder_name'] = $this->account_holder_name;
        $json['stripe_number'] = $this->stripe_number;
        $json['account_number'] = $this->account_number;
        $json['card_number'] = $this->card_number;
        $json['card_expiry_date'] = $this->card_expiry_date;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['is_verify'] = $this->is_verify;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['country_code'] = $this->country_code;
        $json['full_name'] = $this->full_name;
        $json['customer_id'] = $this->customer_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['createdBy'] = $relationData;
            } else {
                $json['createdBy'] = $list;
            }
            // user
            $list = $this->user;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['user'] = $relationData;
            } else {
                $json['user'] = $list;
            }
        }
        return $json;
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->user_id = 1;
            $model->is_default_account = $faker->text(10);
            $model->bank_name = $faker->name;
            $model->bank_id = 1;
            $model->bank_token = $faker->text(10);
            $model->routing_number = $faker->text(10);
            $model->account_holder_name = $faker->name;
            $model->stripe_number = $faker->text(10);
            $model->account_number = $faker->text(10);
            $model->card_number = $faker->text(10);
            $model->card_expiry_date = $faker->text(10);
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->is_verify = $faker->text(10);
            $model->country_code = $faker->text(10);
            $model->full_name = $faker->name;
            $model->customer_id = 1;
            $model->save();
        }
    }

    public static function addData($data)
    {
        if (self::find()->count() != 0) {
            return;
        }

        $faker = \Faker\Factory::create();
        foreach ($data as $item) {
            $model = new self();
            $model->loadDefaultValues();

            $model->user_id = isset($item['user_id']) ? $item['user_id'] : 1;

            $model->is_default_account = isset($item['is_default_account']) ? $item['is_default_account'] : $faker->text(10);

            $model->bank_name = isset($item['bank_name']) ? $item['bank_name'] : $faker->name;

            $model->bank_id = isset($item['bank_id']) ? $item['bank_id'] : 1;

            $model->bank_token = isset($item['bank_token']) ? $item['bank_token'] : $faker->text(10);

            $model->routing_number = isset($item['routing_number']) ? $item['routing_number'] : $faker->text(10);

            $model->account_holder_name = isset($item['account_holder_name']) ? $item['account_holder_name'] : $faker->name;

            $model->stripe_number = isset($item['stripe_number']) ? $item['stripe_number'] : $faker->text(10);

            $model->account_number = isset($item['account_number']) ? $item['account_number'] : $faker->text(10);

            $model->card_number = isset($item['card_number']) ? $item['card_number'] : $faker->text(10);

            $model->card_expiry_date = isset($item['card_expiry_date']) ? $item['card_expiry_date'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->is_verify = isset($item['is_verify']) ? $item['is_verify'] : $faker->text(10);

            $model->country_code = isset($item['country_code']) ? $item['country_code'] : $faker->text(10);

            $model->full_name = isset($item['full_name']) ? $item['full_name'] : $faker->name;

            $model->customer_id = isset($item['customer_id']) ? $item['customer_id'] : 1;
            $model->save();
        }
    }

    public function isAllowed()
    {
        if (User::isAdmin())
            return true;
        if ($this->hasAttribute('created_by_id') && $this->created_by_id == Yii::$app->user->id) {
            return true;
        }

        return User::isUser();
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }
}
