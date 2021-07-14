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
 * This is the model class for table "tbl_address".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $primary_address
 * @property string $secondary_address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zipcode
 * @property string $contact_no
 * @property integer $no_of_box
 * @property string $date
 * @property string $time
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;

class Address extends \app\components\TActiveRecord
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

    const LIMIT = 1;

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Not Default",
            self::STATE_ACTIVE => "Default",
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
        return '{{%address}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['address'] = [
            'user_id',
            'first_name',
            'primary_address',
            'secondary_address',
            'city',
            'zipcode',
            'contact_no',
            'date'
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
                    'first_name',
                    'primary_address',
                    'secondary_address',
                    'city',
                    'zipcode',
                    'contact_no',
                    'date',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'user_id',
                    'no_of_box',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                'zipcode',
                'string',
                'min' => '5',
                'message' => 'Zip-code should be of atleast 3 character',
                'max' => '6',
                'message' => "Zip-code can't be longer 6 character"
            ],
            [
                [
                    'date',
                    'time',
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'date'
                ],
                'date',
                'format' => 'php:Y-m-d'
            ],
            [
                [
                    'first_name',
                    'last_name',
                    'primary_address',
                    'secondary_address',
                    'city'
                ],
                'string',
                'max' => 255
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
                'min' => '9999',
                'tooSmall' => 'phone number should be of atleast 5 digit',
                'max' => '999999999999999',
                'tooBig' => 'phone number should not be longer 15 digit'
            ],
            [
                [
                    'state',
                    'country'
                ],
                'string',
                'max' => 128
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
                    'first_name',
                    'last_name',
                    'primary_address',
                    'secondary_address',
                    'city',
                    'state',
                    'country',
                    'zipcode'
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
            'user_id' => Yii::t('app', 'User'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'primary_address' => Yii::t('app', 'Address'),
            'secondary_address' => Yii::t('app', 'Second Address'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'country' => Yii::t('app', 'Country'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'contact_no' => Yii::t('app', 'Phone'),
            'no_of_box' => Yii::t('app', 'No Of Box'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
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
        $json['user_id'] = $this->user_id;
        $json['first_name'] = $this->first_name;
        $json['last_name'] = $this->last_name;
        $json['primary_address'] = $this->primary_address;
        $json['secondary_address'] = $this->secondary_address;
        $json['city'] = $this->city;
        $json['state'] = $this->state;
        $json['country'] = $this->country;
        $json['zipcode'] = $this->zipcode;
        $json['contact_no'] = ! empty((int) $this->contact_no) ? (int) $this->contact_no : '';
        $json['no_of_box'] = $this->no_of_box;
        $json['date'] = $this->date;
        $json['time'] = $this->time;
        $json['is_default'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
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

            $model->user_id = 1;
            $model->first_name = $faker->name;
            $model->last_name = $faker->name;
            $model->primary_address = $faker->text(10);
            $model->secondary_address = $faker->text(10);
            $model->city = $faker->text(10);
            $model->state = 0;
            $model->country = $faker->text(10);
            $model->zipcode = $faker->text(10);
            $model->contact_no = $faker->text(10);
            $model->no_of_box = $faker->text(10);
            $model->date = $faker->date($format = 'Y-m-d', $max = 'now');
            $model->time = $faker->time($format = 'H:i:s', $max = 'now');
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

            $model->user_id = isset($item['user_id']) ? $item['user_id'] : 1;

            $model->first_name = isset($item['first_name']) ? $item['first_name'] : $faker->name;

            $model->last_name = isset($item['last_name']) ? $item['last_name'] : $faker->name;

            $model->primary_address = isset($item['primary_address']) ? $item['primary_address'] : $faker->text(10);

            $model->secondary_address = isset($item['secondary_address']) ? $item['secondary_address'] : $faker->text(10);

            $model->city = isset($item['city']) ? $item['city'] : $faker->text(10);

            $model->state = isset($item['state']) ? $item['state'] : 0;

            $model->country = isset($item['country']) ? $item['country'] : $faker->text(10);

            $model->zipcode = isset($item['zipcode']) ? $item['zipcode'] : $faker->text(10);

            $model->contact_no = isset($item['contact_no']) ? $item['contact_no'] : $faker->text(10);

            $model->no_of_box = isset($item['no_of_box']) ? $item['no_of_box'] : $faker->text(10);

            $model->date = isset($item['date']) ? $item['date'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->time = isset($item['time']) ? $item['time'] : $faker->time($format = 'H:i:s', $max = 'now');
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
