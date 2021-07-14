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
namespace app\modules\favorite\models;

use app\models\User;
use Yii;
use yii\helpers\VarDumper;
use app\modules\appointment\models\AppointmentBooking;
use phpDocumentor\Reflection\PseudoTypes\True_;

/**
 * This is the model class for table "tbl_favorite_item".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $model_type
 * @property integer $model_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
class Item extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->getModel();
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const IS_FAVORITE_YES = 1;

    const IS_FAVORITE_NO = 0;

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
        return '{{%favorite_item}}';
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
                    'project_id',
                    'model_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'model_type',
                    'model_id'
                ],
                'required'
            ],
            [
                [
                    'created_on',
                    'model_id'
                ],
                'safe'
            ],
            [
                [
                    'model_type'
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
                    'model_type'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
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
            'project_id' => Yii::t('app', 'Project'),
            'model_type' => Yii::t('app', 'Model Type'),
            'model_id' => Yii::t('app', 'Model'),
            'state_id' => Yii::t('app', 'State'),
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

    public function getProvider()
    {
        return $this->hasOne(AppointmentBooking::className(), [
            'provider_id' => 'model_id'
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

    public function getInstitueName()
    {
        $model = $this->favPerson->getPartnerProfile()->one();
        return ! empty($model) ? $model->institute_name : "";
    }

    public function getCourseName()
    {
        $model = $this->favPerson->getPartnerProfile()->one();
        return ! empty($model) ? $model->course_name : "";
    }

    public function asJson($with_relations = false, $provider = true)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['project_id'] = $this->project_id;
        $json['model_type'] = $this->model_type;
        $json['model_id'] = $this->model_id;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['fav_person'] = $this->favPerson->asJson();
        $json['full_name'] = ! empty($this->favPerson) ? $this->favPerson->full_name : "";
        $json['profile_file'] = ! empty($this->favPerson) ? $this->favPerson->getUrl('image') : "";
        $json['country'] = ! empty($this->favPerson) ? $this->favPerson->country : "";
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
            if ($provider) {
                // provider
                $list = $this->provider;

                if (is_array($list)) {
                    $relationData = [];
                    foreach ($list as $item) {
                        $relationData[] = $item->asJson();
                    }
                    $json['provider'] = $relationData;
                } else {
                    $json['provider'] = $list;
                }
            }
        }
        return $json;
    }

    public function getControllerID()
    {
        return '/favorite/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();

            $model->project_id = 1;
            $model->model_type = $faker->text(10);
            $model->model_id = 1;
            $model->state_id = $states[rand(0, count($states))];
            $model->save();
        }
    }

    public function getFavPerson()
    {
        return $this->hasOne(User::className(), [
            'id' => 'model_id'
        ]);
    }

    public static function addData($data)
    {
        $faker = \Faker\Factory::create();
        if (self::find()->count() != 0)
            return;
        foreach ($data as $item) {
            $model = new self();

            $model->project_id = isset($item['project_id']) ? $item['project_id'] : 1;

            $model->model_type = isset($item['model_type']) ? $item['model_type'] : $faker->text(10);

            $model->model_id = isset($item['model_id']) ? $item['model_id'] : 1;
            $model->state_id = self::STATE_ACTIVE;
            $model->save();
        }
    }

    public static function add($obj)
    {
        $old = Item::find()->where([
            'model_type' => get_class($obj),
            'model_id' => $obj->id,
            'created_by_id' => \Yii::$app->user->id
        ])->one();

        if ($old) {
            if ($old->state_id == Item::STATE_ACTIVE) {
                $old->state_id = Item::STATE_INACTIVE;
            } else {
                $old->state_id = Item::STATE_ACTIVE;
            }
            $old->created_on = date("Y-m-d H:i:s");
            if (! $old->save()) {
                VarDumper::dump($old->errors);
                return false;
            }
            return true;
        } else {
            $model = new self();
            $model->loadDefaultValues();
            $class = get_class($obj);
            $model->model_type = $class;
            $model->model_id = $obj->id;
            $model->state_id = Item::STATE_ACTIVE;

            if ($obj->hasAttribute('project_id')) {
                $model->project_id = $obj->project_id;
            }
            if ($obj instanceof Project) {
                $model->project_id = $obj->id;
            }
        }

        if (! $model->save()) {
            VarDumper::dump($model->errors);
            return false;
        }
        return true;
    }

    public function getModel()
    {
        $modelType = $this->model_type;
        if (class_exists($modelType)) {
            return $modelType::findOne($this->model_id);
        }
    }
}
