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
use app\models\User;
use app\modules\order\models\Order;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Product;
use app\models\Package;

/**
 * This is the model class for table "tbl_order_item".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $order_id
 * @property string $amount
 * @property string $quantity
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 * @property Order $order
 */
class Item extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->order_id;
    }

    public static function getOrderOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return self::listData ( Order::findActive ()->all () );
    }

    const PRODUCT = 0;

    const PACKAGE = 1;

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

    const TYPE1 = 1;

    const TYPE2 = 2;

    public static function getTypeOptions()
    {
        return [

            self::TYPE1 => "Product",
            self::TYPE2 => "Package"
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
        return '{{%order_item}}';
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
                    'quantity',
                    'item_id',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'item_id',
                    'order_id',
                    'state_id',
                    'quantity',
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
                    'order_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Order::className(),
                'targetAttribute' => [
                    'order_id' => 'id'
                ]
            ],
            [
                [
                    'amount',
                    'quantity'
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
            'order_id' => Yii::t('app', 'Order'),
            'amount' => Yii::t('app', 'Amount'),
            'quantity' => Yii::t('app', 'Quantity'),
            'item_id' => Yii::t('app', 'Item'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Customer name')
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), [
            'id' => 'item_id'
        ]);
    }
    public function getItemTitle()
    
    {
        if ($this->type_id == Product::PRODUCT) {
            
            return ArrayHelper::Map(Product::findActive()->where([
                'id' => $this->item_id
            ])->each(), 'id', 'title');
        }
        return ArrayHelper::Map(Package::findActive()->where([
            'id' => $this->item_id
        ])->each(), 'id', 'title');
    }

    public function getItem()
    {
        if ($this->type_id == Product::PRODUCT) {

            return $this->hasOne(Product::className(), [
                'id' => 'item_id'
            ]);
        }
        return $this->hasOne(Package::className(), [
            'id' => 'item_id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), [
            'id' => 'order_id'
        ]);
    }

    // public function getOrder(){

    // $orders=OrderItem::find()->where(['order_id' => 1])->joinWith('order')->asArray()->all();
    // }
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
        $relations['order_id'] = [
            'order',
            'Order',
            'id'
        ];
        $relations['item_id'] = [
            'product',
            'Product',
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
        $json['order_id'] = $this->order_id;
        $json['amount'] = $this->amount;
        $json['quantity'] = $this->quantity;
        $json['item_id'] = $this->item_id;

        // $json['created_on'] = $this->created_on;
        // $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {

            // product
            $list = $this->item;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['item'] = $relationData;
            } else {
                $json['item'] = $list->asJson();
            }
        }
        return $json;
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
            $model->order_id = 1;
            $model->amount = $faker->text(10);
            $model->quantity = $faker->text(10);
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

            $model->order_id = isset($item['order_id']) ? $item['order_id'] : 1;

            $model->amount = isset($item['amount']) ? $item['amount'] : $faker->text(10);

            $model->quantity = isset($item['quantity']) ? $item['quantity'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->save();
        }
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
