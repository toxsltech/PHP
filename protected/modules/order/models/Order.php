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
namespace app\modules\order\models;

use Yii;
use app\models\Feed;
use app\models\Address;
use app\models\User;
use app\modules\order\models\Item;
use app\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\PaymentTransaction;
use app\modules\notification\models\Notification;

/**
 * This is the model class for table "tbl_order".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $address_id
 * @property integer $tip
 * @property integer $is_pickup
 * @property string $amount
 * @property string $tax
 * @property string $total_amount
 * @property string $preparing_time
 * @property string $estimated_time
 * @property integer $payment_type
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property Address $address
 * @property User $createdBy
 * @property Item[] $items
 * @property Product $product
 */
class Order extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->product_id;
    }

    public static function getProductOptions()
    {
        return self::listData(Product::findActive()->all());
    }

    public static function getAddressOptions()
    {
        return self::listData(Address::findActive()->all());
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

    const CLASS_NAME = 'Order';

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Delievered",
            self::STATE_ACTIVE => "Pending",
            self::STATE_DELETED => "Cancel"
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
            self::STATE_INACTIVE => "success",
            self::STATE_ACTIVE => "secondary",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
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
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getPaymentStatusState(), [
            'class' => 'badge badge-' . $list[$this->payment_status]
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

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
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
        return '{{%order}}';
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
                    // 'product_id',
                    'address_id',
                    // 'amount',
                    // 'type_id',
                    // 'state_id',
                    // 'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    // 'product_id',
                    'address_id',
                    'tip',
                    'is_pickup',
                    'payment_type',
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'preparing_time',
                    'estimated_time',
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'amount',
                    'tax',
                    'total_amount'
                ],
                'string',
                'max' => 32
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
                    'amount',
                    'tax',
                    'total_amount'
                ],
                'trim'
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
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'payment_status'
                ],
                'in',
                'range' => array_keys(self::getPaymentStatusOptions())
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
            // 'product_id' => Yii::t('app', 'Product'),
            'address_id' => Yii::t('app', 'Address'),
            'tip' => Yii::t('app', 'Tip'),
            'is_pickup' => Yii::t('app', 'Is Pickup'),
            'amount' => Yii::t('app', 'Amount'),
            'tax' => Yii::t('app', 'Tax'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'preparing_time' => Yii::t('app', 'Preparing Time'),
            'estimated_time' => Yii::t('app', 'Delivery Date'),
            'payment_type' => Yii::t('app', 'Payment Type'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Customer Name')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), [
            'id' => 'address_id'
        ]);
    }

    // public function getOrderDetails(){

    // $orders=Item::find()->where(['order_id' => 1])->joinWith('order')->asArray()->all();
    // }

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
    public function getItems()
    {
        return $this->hasMany(Item::className(), [
            'order_id' => 'id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), [
            'id' => 'product_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['Items'] = [

            'Item',
            'id',
            'order_id'
        ];
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
        $relations['address_id'] = [
            'addressId',
            'Address',
            'id'
        ];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        $relations['product_id'] = [
            'product',
            'Product',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        Item::deleteRelatedAll([
            'order_id' => $this->id
        ]);
        Notification::deleteRelatedAll([
            'model_id' => $this->id
        ]);
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

    public function asJson($with_relations = true)
    {
        $payment_type = PaymentTransaction::ORDER;
        $secretkey = Yii::$app->params['senangpay_secret_id'];
        $hashed_string = hash_hmac('sha256', $secretkey . urldecode("order") . urldecode($this->total_amount) . urldecode($this->id), $secretkey);
        $merchant_id = Yii::$app->params['senangpay_merchant_id'];
        $total_amount = $this->total_amount;
        $order_id = $this->id;
        $user_detail = User::findOne(\Yii::$app->user->id);
        $email = $user_detail->email;
        // $name = $user_detal->full_name;
        $name = PaymentTransaction::ORDER;
        $phone = $user_detail->contact_no;
        $json = [];
        $json['id'] = $this->id;
        $json['address_id'] = $this->address_id;
        $json['payment_type'] = $this->payment_type;
        $json['payment_status'] = $this->payment_status;

        $json['amount'] = $this->amount;

        $json['payment_url'] = "https://app.senangpay.my/payment/" . $merchant_id . "?detail=order&amount=" . $total_amount . "&order_id=" . $order_id . "&name=" . $name . "&email=" . $email . "&phone=" . $phone . "&hash=" . $hashed_string;

        $json['total_amount'] = $this->total_amount;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        // $json['created_by_id'] = $this->created_by_id;

        if ($with_relations) {

            $list = $this->items;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson(true);
                }
                $json['items'] = $relationData;
            } else {
                $json['items'] = $list->asJson(true);
            }
        }
        return $json;
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/image'
        ];
        $params['id'] = $this->id;

        if (isset($this->image_file) && ! empty($this->image_file)) {
            $params['file'] = $this->image_file;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;

        return Url::toRoute($params);
    }

    public function getControllerID()
    {
        return '/order/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            // $model->product_id = 1;
            $model->address_id = 1;
            $model->tip = $faker->text(10);
            $model->is_pickup = $faker->text(10);
            $model->amount = $faker->text(10);
            $model->tax = $faker->text(10);
            $model->total_amount = $faker->text(10);
            $model->preparing_time = \date('Y-m-d H:i:s');
            $model->estimated_time = \date('Y-m-d H:i:s');
            $model->payment_type = $faker->text(10);
            $model->type_id = 0;
            $model->state_id = $states[rand(0, count($states))];
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

            $model->product_id = isset($item['product_id']) ? $item['product_id'] : 1;

            $model->address_id = isset($item['address_id']) ? $item['address_id'] : 1;

            $model->tip = isset($item['tip']) ? $item['tip'] : $faker->text(10);

            $model->is_pickup = isset($item['is_pickup']) ? $item['is_pickup'] : $faker->text(10);

            $model->amount = isset($item['amount']) ? $item['amount'] : $faker->text(10);

            $model->tax = isset($item['tax']) ? $item['tax'] : $faker->text(10);

            $model->total_amount = isset($item['total_amount']) ? $item['total_amount'] : $faker->text(10);

            $model->preparing_time = isset($item['preparing_time']) ? $item['preparing_time'] : \date('Y-m-d H:i:s');

            $model->estimated_time = isset($item['estimated_time']) ? $item['estimated_time'] : \date('Y-m-d H:i:s');

            $model->payment_type = isset($item['payment_type']) ? $item['payment_type'] : $faker->text(10);

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->state_id = self::STATE_ACTIVE;
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
