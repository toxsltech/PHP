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
use yii\helpers\ArrayHelper;
use app\modules\order\models\Order;

/**
 * This is the model class for table "tbl_payment_transaction".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $description
 * @property integer $model_id
 * @property string $model_type
 * @property string $amount
 * @property string $currency
 * @property string $transaction_id
 * @property string $payer_id
 * @property string $value
 * @property integer $gateway_type
 * @property integer $payment_status
 * @property string $created_on
 */
class PaymentTransaction extends \app\components\TActiveRecord
{

    const CURRENCY = 'RM';

    const PAID = 1;

    const ORDER = 1;

    const CHARITY = 2;

    const SUBSCRIPTION = 3;

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public function __toString()
    {
        return (string) $this->name;
    }

    public static function getModelOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getModel()
    {
        $list = self::getModelOptions();
        return isset($list[$this->model_id]) ? $list[$this->model_id] : 'Not Defined';
    }

    public static function getTransactionOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getTransaction()
    {
        $list = self::getTransactionOptions();
        return isset($list[$this->transaction_id]) ? $list[$this->transaction_id] : 'Not Defined';
    }

    public static function getPayerOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getPayer()
    {
        $list = self::getPayerOptions();
        return isset($list[$this->payer_id]) ? $list[$this->payer_id] : 'Not Defined';
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
        return isset($list[$this->payment_status]) ? $list[$this->payment_status] : 'Not Defined';
    }

    public function getPaymentStatusBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success"
        ];
        return isset($list[$this->payment_status]) ? \yii\helpers\Html::tag('span', $this->getPaymentStatusState(), [
            'class' => 'badge badge-' . $list[$this->payment_status]
        ]) : 'Not Defined';
    }

    public function category()
    {
        if ($this->model_type == Charity::className()) {
            return Charity::CLASS_NAME;
        } elseif ($this->model_type == Order::className()) {
            return Order::CLASS_NAME;
        } else {
            return 'Subscription';
        }
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
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

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_transaction}}';
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
                    'description',
                    'value'
                ],
                'string'
            ],
            [
                [
                    'model_id',
                    'gateway_type',
                    'payment_status'
                ],
                'integer'
            ],
            [
                [
                    'payer_id',
                    'currency'
                ],
                'required'
            ],
            [
                [
                    'payer_id',
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'name',
                    'email',
                    'model_type',
                    'amount',
                    'transaction_id'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'currency'
                ],
                'string',
                'max' => 125
            ],
            [
                [
                    'name',
                    'email',
                    'model_type',
                    'amount',
                    'transaction_id',
                    'currency'
                ],
                'trim'
            ],
            [
                [
                    'name'
                ],
                'app\components\validators\TNameValidator'
            ],
            [
                [
                    'email'
                ],
                'email'
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
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'description' => Yii::t('app', 'Description'),
            'model_id' => Yii::t('app', 'Order Id'),
            'model_type' => Yii::t('app', 'Payment For'),
            'amount' => Yii::t('app', 'Amount'),
            'currency' => Yii::t('app', 'Currency'),
            'transaction_id' => Yii::t('app', 'Transaction Id'),
            'payer_id' => Yii::t('app', 'Payer'),
            'value' => Yii::t('app', 'Value'),
            'gateway_type' => Yii::t('app', 'Gateway Type'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'created_on' => Yii::t('app', 'Created On')
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

    public function getCharity()
    {
        if ($this->model_type == Charity::class) {

            $model = CharityDetail::findOne($this->model_id);
        } elseif ($this->model_type == SubscriptionPlan::class) {

            $model = SubscriptionBilling::findOne($this->model_id);
        } else {
            $model = Order::findOne($this->model_id);
        }
        return $model;
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

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['name'] = $this->name;
        $json['email'] = $this->email;
        $json['description'] = $this->description;
        $json['model_id'] = $this->model_id;
        $json['model_type'] = $this->model_type;
        $json['amount'] = $this->amount;
        $json['currency'] = $this->currency;
        $json['transaction_id'] = $this->transaction_id;
        $json['payer_id'] = $this->payer_id;
        $json['value'] = $this->value;
        $json['gateway_type'] = $this->gateway_type;
        $json['payment_status'] = $this->payment_status;
        $json['created_on'] = $this->created_on;
        if ($with_relations) {}
        return $json;
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->name = $faker->text(10);
            $model->email = $faker->email;
            $model->description = $faker->text;
            $model->model_id = 1;
            $model->model_type = $faker->text(10);
            $model->amount = $faker->text(10);
            $model->currency = $faker->text(10);
            $model->transaction_id = 1;
            $model->payer_id = 1;
            $model->value = $faker->text;
            $model->gateway_type = $faker->text(10);
            $model->payment_status = $faker->text(10);
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

            $model->name = isset($item['name']) ? $item['name'] : $faker->text(10);

            $model->email = isset($item['email']) ? $item['email'] : $faker->email;

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;

            $model->model_id = isset($item['model_id']) ? $item['model_id'] : 1;

            $model->model_type = isset($item['model_type']) ? $item['model_type'] : $faker->text(10);

            $model->amount = isset($item['amount']) ? $item['amount'] : $faker->text(10);

            $model->currency = isset($item['currency']) ? $item['currency'] : $faker->text(10);

            $model->transaction_id = isset($item['transaction_id']) ? $item['transaction_id'] : 1;

            $model->payer_id = isset($item['payer_id']) ? $item['payer_id'] : 1;

            $model->value = isset($item['value']) ? $item['value'] : $faker->text;

            $model->gateway_type = isset($item['gateway_type']) ? $item['gateway_type'] : $faker->text(10);

            $model->payment_status = isset($item['payment_status']) ? $item['payment_status'] : $faker->text(10);
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
