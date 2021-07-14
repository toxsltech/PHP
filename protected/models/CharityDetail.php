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
use app\models\Charity;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_charity_detail".
 *
 * @property integer $id
 * @property string $amount
 * @property integer $charity_id
 * @property integer $user_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property Charity $charity
 * @property User $createdBy
 */
class CharityDetail extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->amount;
    }

    public static function getCharityOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return self::listData ( Charity::findActive ()->all () );
    }

    public static function getUserOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getUser()
    {
        $list = self::getUserOptions();
        return isset($list[$this->user_id]) ? $list[$this->user_id] : 'Not Defined';
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
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getPaymentStatus()
    {
        $id = $this->id;
        $bill = PaymentTransaction::find()->where([
            'model_id' => $id,
            'model_type' => Charity::className()
        ])->one();
        if ($bill) {
            $payment_status = $bill->payment_status;
        } else {
            $payment_status = 0;
        }

        return $payment_status;
    }

    public static function getPaymentStatusOptions()
    {
        return [
            self::STATE_INACTIVE => "Pending",
            self::STATE_ACTIVE => "Done"
        ];
    }

    public function getPaymentStatusState()
    {
        $list = self::getPaymentStatusOptions();
        return isset($list[$this->getPaymentStatus()]) ? $list[$this->getPaymentStatus()] : 'Not Defined';
    }

    public function getPaymentStatusBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getPaymentStatusState(), [
            'class' => 'badge badge-' . $list[$this->getPaymentStatus()]
        ]) : 'Not Defined';
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->user_id)) {
                $this->user_id = self::getCurrentUser();
            }
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        } else {}
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charity_detail}}';
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
                    'amount',
                    'charity_id',
                    'user_id',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'charity_id',
                    'user_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'amount'
                ],
                'string',
                'max' => 20
            ],
            [
                [
                    'charity_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Charity::className(),
                'targetAttribute' => [
                    'charity_id' => 'id'
                ]
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
                    'amount'
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
            'amount' => Yii::t('app', 'Amount'),
            'charity_id' => Yii::t('app', 'Charity'),
            'user_id' => Yii::t('app', 'User'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCharity()
    {
        return $this->hasOne(Charity::className(), [
            'id' => 'charity_id'
        ]);
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
        $relations['charity_id'] = [
            'charity',
            'Charity',
            'id'
        ];
        $relations['created_by_id'] = [
            'createdBy',
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
        $secretkey = Yii::$app->params['senangpay_secret_id'];
        $hashed_string = hash_hmac('sha256', $secretkey . urldecode("order") . urldecode($this->amount) . urldecode($this->id), $secretkey);
        $merchant_id = Yii::$app->params['senangpay_merchant_id'];
        $total_amount = $this->amount;
        $order_id = $this->id;
        $user_detail = User::findOne(\Yii::$app->user->id);
        $email = $user_detail->email;
        $name = PaymentTransaction::CHARITY;
        $phone = $user_detail->contact_no;
        $json = [];
        $json['id'] = $this->id;
        $json['url'] = Url::to('/site/charity');
        $json['amount'] = $this->amount;
        $json['payment_url'] = "https://app.senangpay.my/payment/" . $merchant_id . "?detail=order&amount=" . $total_amount . "&order_id=" . $order_id . "&name=" . $name . "&email=" . $email . "&phone=" . $phone . "&hash=" . $hashed_string;
        $json['charity_id'] = $this->charity_id;
        $json['user_id'] = $this->user_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // charity
            $list = $this->charity;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['charity'] = $relationData;
            } else {
                $json['charity'] = $list;
            }
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['createdBy'] = $relationData;
            } else {
                $json['createdBy'] = $list;
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
            $model->amount = $faker->text(10);
            $model->charity_id = 1;
            $model->user_id = 1;
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
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

            $model->amount = isset($item['amount']) ? $item['amount'] : $faker->text(10);

            $model->charity_id = isset($item['charity_id']) ? $item['charity_id'] : 1;

            $model->user_id = isset($item['user_id']) ? $item['user_id'] : 1;
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
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
