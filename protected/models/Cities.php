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
use app\models\Countries;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_cities".
 *
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 * @property string $latitude
 * @property string $longitude
 * @property integer $flag === Related data ===
 * @property Countries $country
 */
class Cities extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->name;
    }

    public static function getCountryOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return self::listData ( Countries::findActive ()->all () );
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cities}}';
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
                    'name',
                    'country_id',
                    'latitude',
                    'longitude'
                ],
                'required'
            ],
            [
                [
                    'country_id',
                    'flag'
                ],
                'integer'
            ],
            [
                [
                    'latitude',
                    'longitude'
                ],
                'number'
            ],
            [
                [
                    'name'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'country_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Countries::className(),
                'targetAttribute' => [
                    'country_id' => 'id'
                ]
            ],
            [
                [
                    'name',
                    'flag'
                ],
                'trim'
            ],
            [
                [
                    'name'
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
            'name' => Yii::t('app', 'Name'),
            'country_id' => Yii::t('app', 'Country'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'flag' => Yii::t('app', 'Flag')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), [
            'id' => 'country_id'
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
        $relations['country_id'] = [
            'country',
            'Countries',
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

    public function asJson($with_relations = true)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['name'] = $this->name;
        $json['country_id'] = $this->country_id;
        $json['latitude'] = $this->latitude;
        $json['longitude'] = $this->longitude;
        $json['flag'] = $this->flag;
        if ($with_relations) {
            // country
            $list = $this->country;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['country'] = $relationData;
            } else {
                $json['country'] = $list;
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
            $model->name = $faker->text(10);
            $model->country_id = 1;
            $model->latitude = $faker->text(10);
            $model->longitude = $faker->text(10);
            $model->flag = $faker->boolean;
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

            $model->country_id = isset($item['country_id']) ? $item['country_id'] : 1;

            $model->latitude = isset($item['latitude']) ? $item['latitude'] : $faker->text(10);

            $model->longitude = isset($item['longitude']) ? $item['longitude'] : $faker->text(10);

            $model->flag = isset($item['flag']) ? $item['flag'] : $faker->boolean;
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
