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

/**
 * This is the model class for table "tbl_product".
 *
 * @property integer $id
 * @property string $title
 * @property string $amount
 * @property string $quantity
 * @property string $image_file
 * @property string $description
 * @property string $benifits
 * @property string $specification
 * @property string $medical_specification
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\favorite\models\Item;

class Product extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    const PRODUCT = 1;

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const STATE_BANNED = 3;

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
            self::STATE_BANNED => "warning",
            self::STATE_DELETED => "danger"
        ];
        // return \yii\helpers\Html::tag('span', $this->getState(), ['class' => 'badge bg-' . $list[$this->state_id]]);
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

    public function getCategoryId()
    {
        return Category::find()->select('title')
            ->where([
            'id' => $this->category,
            'type_id' => Category::TYPE1
        ])
            ->one();
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    // get all category
    public function getcategory()
    {
        return ArrayHelper::Map(Category::findActive()->where([
            'state_id' => Category::STATE_ACTIVE,
            'type_id' => Category::TYPE1
        ])->all(), 'id', 'title');
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
        return '{{%product}}';
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
                    'title',
                    'category',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'quantity'
                ],
                'safe'
            ],
            [
                [
                    'title',
                    'category',
                    'benifits',
                    'specification',
                    'medical_specification'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'amount',
                    'quantity'
                ],
                'string',
                'max' => 32
            ],
            [
                [
                    'image_file'
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
                    'title',
                    'category',
                    'benifits',
                    'specification',
                    'medical_specification',
                    'amount',
                    'image_file'
                ],
                'trim'
            ],
            [
                [
                    'image_file'
                ],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg,jpeg'
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
            'title' => Yii::t('app', 'Title'),
            'category' => Yii::t('app', 'Category'),
            'amount' => Yii::t('app', 'Price'),
            'quantity' => Yii::t('app', 'Quantity'),
            'image_file' => Yii::t('app', 'Image File'),
            'description' => Yii::t('app', 'Description'),
            'benifits' => Yii::t('app', 'Benefits'),
            'specification' => Yii::t('app', 'Specification'),
            'medical_specification' => Yii::t('app', 'Medical Specification'),
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ]);
    }

    public function getFavourite()
    {
        $model = Item::find()->where([
            'model_id' => $this->id,
            'created_by_id' => \Yii::$app->user->id,
            'model_type' => Product::className()
        ])->one();
        if (! empty($model)) {
            return true;
        }
        return false;
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
        $json['title'] = $this->title;
        $json['category'] = $this->category;
        $json['amount'] = $this->amount;
        $json['quantity'] = $this->quantity;
        if (isset($this->image_file));
        $json['image_file'] = $this->getImageUrl();
        $json['description'] = $this->description;
        $json['benifits'] = $this->benifits;
        $json['specification'] = $this->specification;
        $json['medical_specification'] = $this->medical_specification;
        $json['is_favourite'] = $this->getFavourite();
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
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
        }
        return $json;
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();

            $model->title = $faker->text(10);
            $model->category = $faker->text(10);
            $model->amount = $faker->text(10);
            $model->quantity = $faker->text(10);
            $model->image_file = $faker->text(10);
            $model->description = $faker->text(10);
            $model->benifits = $faker->text(10);
            $model->specification = $faker->text(10);
            $model->medical_specification = $faker->text(10);
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->save();
        }
    }

    public static function addData($data)
    {
        $faker = \Faker\Factory::create();
        if (self::find()->count() != 0)
            return;
        foreach ($data as $item) {
            $model = new self();

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->category = isset($item['category']) ? $item['category'] : $faker->text(10);

            $model->amount = isset($item['amount']) ? $item['amount'] : $faker->text(10);

            $model->quantity = isset($item['quantity']) ? $item['quantity'] : $faker->text(10);

            $model->quantity = isset($item['quantity']) ? $item['quantity'] : $faker->text(10);

            $model->image_file = isset($item['image_file']) ? $item['image_file'] : $faker->text(10);

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;

            $model->benifits = isset($item['benifits']) ? $item['benifits'] : $faker->text(10);

            $model->specification = isset($item['specification']) ? $item['specification'] : $faker->text(10);

            $model->medical_specification = isset($item['medical_specification']) ? $item['medical_specification'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            if (! $model->save()) {
                self::log($model->getErrorsString());
            }
        }
    }

    public function isAllowed()
    {
        if (User::isAdmin() || User::isSubAdmin())
            return true;

        if ($this->hasAttribute('created_by_id')) {
            return ($this->created_by_id == Yii::$app->user->id);
        }

        if ($this->hasAttribute('user_id')) {
            return ($this->user_id == Yii::$app->user->id);
        }

        return false;
    }
}
