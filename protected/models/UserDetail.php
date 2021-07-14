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
 * This is the model class for table "tbl_user_detail".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $package_id
 * @property integer $subscription_id
 * @property integer $user_height
 * @property integer $user_inch
 * @property integer $weight
 * @property string $eating_habits
 * @property string $description
 * @property string $eat_count
 * @property string $fruit_name
 * @property integer $state_id
 * @property integer $is_profile_complete
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class UserDetail extends \app\components\TActiveRecord
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
    }

    public function getUser()
    {
        $list = self::getUserOptions();
        return isset($list[$this->user_id]) ? $list[$this->user_id] : 'Not Defined';
    }

    public static function getSubscriptionOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getSubscription()
    {
        return $this->hasOne(SubscriptionPlan::className(), [
            'id' => 'subscription_id'
        ]);
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const INCHES = 1;

    const FOOT = 2;

    const CM = 3;

    const KG = 1;

    const POUNDS = 2;

    const HEALTHCARE = 1;

    const SKINCARE = 2;

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

    public static function getPackageOptions()
    {
        return [
            self::HEALTHCARE => "Healthcare",
            self::SKINCARE => "Skincare"
        ];
    }

    public static function getPackage($userdetails)
    {
        $list = self::getPackageOptions();
        return isset($list[$userdetails->package_id]) ? $list[$userdetails->package_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "default",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'label label-' . $list[$this->state_id]
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

    public static function getUserDetail($id)
    {
        $userdetails = UserDetail::find()->where([
            'user_id' => $id
        ])->one();
        return $userdetails;
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

    public static function getTypeOptions()
    {
        return [
            self::KG => "Kg",
            self::POUNDS => "Pounds"
        ];
    }

    public static function getHeightInches()
    {
        return [
            self::INCHES => "Inches",
            self::FOOT => "Foot",
            self::CM => "cm"
        ];
    }

    public function getHeight()
    {
        $list = self::getHeightInches();
        return ! empty($list[$this->user_inch]) ? $list[$this->user_inch] : 'Not Defined';
    }

    public function getpageagetype()
    {
        return ArrayHelper::Map(Package::findActive()->all(), 'id', 'title');
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function getHabbits()
    {
        $habbit = ArrayHelper::map(Habbit::find()->orderBy('id asc')->all(), 'id', 'title');
        return $habbit;
    }

    public function getHabbitOptions()
    {
        $list = self::getHabbits();
        return isset($list[$this->eating_habits]) ? $list[$this->eating_habits] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->user_id)) {
                $this->user_id = self::getCurrentUser();
            }
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
        return '{{%user_detail}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['add'] = [
            'user_height',
            'user_inch',
            'weight',
            'type_id',
            'eating_habits'
        ];

        $scenarios['update'] = [
            'user_height',
            'user_inch',
            'weight',
            'type_id',
            'eat_count',
            'description',
            'package_id',
            'eating_habits'
        ];

        $scenarios['medical-specification'] = [
            'description'
        ];

        return $scenarios;
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
                    'package_id',
                    'subscription_id',
                    'user_height',
                    'user_inch',
                    'weight',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'package_id',
                    'subscription_id',
                    'user_height',
                    'weight',
                    'eating_habits',
                    'description',
                    'eat_count',
                    'package_id',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'user_height',
                    'user_inch',
                    'weight',
                    'type_id',
                    'eating_habits'
                ],
                'required',
                'on' => 'add'
            ],
            [
                'user_height',
                'number',
                'min' => 1,
                'tooSmall' => 'Height should be greater than 0'
            ],
            [
                'weight',
                'number',
                'min' => 1,
                'tooSmall' => 'Weight should be greater than 0'
            ],
            [
                'weight',
                'compare',
                'compareValue' => 550,
                'operator' => '<',
                'type' => 'number'
            ],
            [
                'user_height',
                'compare',
                'compareValue' => 250,
                'operator' => '<',
                'type' => 'number'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'created_on',
                    'is_profile_complete'
                ],
                'safe'
            ],
            [
                [
                    'eating_habits'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'eat_count',
                    'fruit_name'
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
                    'eating_habits',
                    'eat_count',
                    'fruit_name'
                ],
                'trim'
            ],
            [
                [
                    'fruit_name'
                ],
                'app\components\validators\TNameValidator'
            ],
            [
                [
                    'user_inch'
                ],
                'in',
                'range' => array_keys(self::getHeightInches())
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
            'user_id' => Yii::t('app', 'User'),
            'package_id' => Yii::t('app', 'Package'),
            'subscription_id' => Yii::t('app', 'Subscription'),
            'user_height' => Yii::t('app', 'User Height'),
            'user_inch' => Yii::t('app', 'User Inch'),
            'weight' => Yii::t('app', 'Weight'),
            'eating_habits' => Yii::t('app', 'Eating Habits'),
            'description' => Yii::t('app', 'Description'),
            'eat_count' => Yii::t('app', 'No. of fruit intake per week'),
            'fruit_name' => Yii::t('app', 'Fruit Name'),
            'state_id' => Yii::t('app', 'State'),
            'is_profile_complete' => Yii::t('app', 'Is Profile Complete'),
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
        // $json['id'] = $this->id;
        // $json['user_id'] = $this->user_id;
        $json['package_id'] = $this->package_id;
        $json['subscription_id'] = $this->subscription_id;
        $json['user_height'] = $this->user_height;
        $json['weight'] = $this->weight;
        $json['eating_habits'] = $this->eating_habits;
        $json['description'] = $this->description;
        $json['eat_count'] = $this->eat_count;
        // $json['fruit_name'] = $this->fruit_name;
        $json['state_id'] = $this->state_id;
        // $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        // $json['created_by_id'] = $this->created_by_id;
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
        $inches = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();

            $model->user_id = 1;
            $model->package_id = 1;
            $model->subscription_id = 1;
            $model->user_height = $faker->text(10);
            $model->weight = $faker->text(10);
            $model->eating_habits = $faker->text(10);
            $model->description = $faker->text;
            $model->eat_count = $faker->text(10);
            $model->fruit_name = $faker->name;
            $model->state_id = $states[rand(0, count($states))];
            $model->state_id = $states[rand(0, count($inches))];
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

            $model->user_id = isset($item['user_id']) ? $item['user_id'] : 1;

            $model->package_id = isset($item['package_id']) ? $item['package_id'] : 1;

            $model->subscription_id = isset($item['subscription_id']) ? $item['subscription_id'] : 1;

            $model->user_height = isset($item['user_height']) ? $item['user_height'] : $faker->text(10);

            $model->weight = isset($item['weight']) ? $item['weight'] : $faker->text(10);

            $model->eating_habits = isset($item['eating_habits']) ? $item['eating_habits'] : $faker->text(10);

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;

            $model->eat_count = isset($item['eat_count']) ? $item['eat_count'] : $faker->text(10);

            $model->fruit_name = isset($item['fruit_name']) ? $item['fruit_name'] : $faker->name;
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            if (! $model->save()) {
                self::log($model->getErrorsString());
            }
        }
    }

    public function isAllowed()
    {
        if (User::isAdmin())
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
