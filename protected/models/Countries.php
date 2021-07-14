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

/**
 * This is the model class for table "tbl_countries".
 *
 * @property integer $id
 * @property string $name
 * @property string $capital
 * @property string $currency
 * @property integer $flag
 */
class Countries extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%countries}}';
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
                    'name'
                ],
                'required'
            ],
            [
                [
                    'flag'
                ],
                'integer'
            ],
            [
                [
                    'name'
                ],
                'string',
                'max' => 100
            ],
            [
                [
                    'capital',
                    'currency'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'name',
                    'capital',
                    'currency',
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
            'capital' => Yii::t('app', 'Capital'),
            'currency' => Yii::t('app', 'Currency'),
            'flag' => Yii::t('app', 'Flag')
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
        $json['capital'] = $this->capital;
        $json['currency'] = $this->currency;
        $json['flag'] = $this->flag;
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
            $model->capital = $faker->text(10);
            $model->currency = $faker->text(10);
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

            $model->capital = isset($item['capital']) ? $item['capital'] : $faker->text(10);

            $model->currency = isset($item['currency']) ? $item['currency'] : $faker->text(10);

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
