<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_page".
 *
 * @property integer $id
 * @property string $title
 * @property string $model_type
 * @property string $description
 * @property integer $state_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property User $createUser
 */
namespace app\modules\page\models;

use Yii;
use app\models\User;
use app\modules\page\Module;

class Page extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const TYPE_PRIVACY = 3;

    const TYPE_TERM_CONDITION = 1;

    const TYPE_ABOUT_US = 2;

    const TYPE_NUTRITION_ADVICE = 4;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Inactive",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public static function getTypeOptions($id = null)
    {
        $list = array(
            self::TYPE_PRIVACY => "Privacy",
            self::TYPE_TERM_CONDITION => "Term & Conditions",
            self::TYPE_ABOUT_US => "About Us",
            self::TYPE_NUTRITION_ADVICE => "Nutrition Advice"
        );
        if ($id === null)
            return $list;
        if (is_numeric($id))
            return $list[$id % count($list)];
        return $id;
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "primary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        // return \yii\helpers\Html::tag('span', $this->getState(), ['class' => 'badge bg-' . $list[$this->state_id]]);
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
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
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->updated_on))
                $this->updated_on = date('Y-m-d H:i:s');
            if (! isset($this->created_by_id))
                $this->created_by_id = Yii::$app->user->id;
        } else {
            $this->updated_on = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
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
            'url' => Yii::t('app', 'Url'),
            'description' => Yii::t('app', 'Description'),
            'state_id' => Yii::t('app', 'State'),
            'create_on' => Yii::t('app', 'Create Time'),
            'update_on' => Yii::t('app', 'Update Time'),
            'created_by_id' => Yii::t('app', 'Create User'),
            'type_id' => Yii::t('app', 'Type')
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
        return parent::beforeDelete();
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
                    'url',
                    'description',
                    'state_id',
                    'type_id',
                    'created_on',
                    'updated_on',
                    'created_by_id'
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
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on'
                ],
                'safe'
            ],
            [
                [
                    'title',
                    'url'
                ],
                'string',
                'max' => 256
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
            ]
        ];
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['url'] = $this->url;
        $json['description'] = $this->description;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // CreateUser
            $list = $this->getCreateUser()->all();

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['CreateUser'] = $relationData;
            } else {
                $json['CreateUser'] = $list;
            }
        }
        return $json;
    }

    public static function sitemap()
    {
        $query = self::find()->where([
            'state_id' => self::STATE_ACTIVE,
            'type_id' => self::TYPE_ARTICLE
        ]);
        $query->orderBy([
            'id' => SORT_DESC
        ]);
        return $query;
    }

    /*
     * public function beforeSave($insert) {
     * return parent::beforeSave ($insert);
     * }
     * public function beforeDelete() {
     * return parent::beforeDelete ();
     * }
     */
    public function getUrl($action = 'view', $id = null, $absolute = null)
    {
        $params = [
            Module::NAME . '/' . $this->getControllerID() . '/' . $action
        ];
        if (is_array($id))
            $params = array_merge($params, $id);
        elseif ($id != null)
            $params['id'] = $id;
        else
            $params['id'] = $this->id;

        $params['title'] = (string) $this;

        return Yii::$app->getUrlManager()->createAbsoluteUrl($params, true);
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

    public function getGoTopage()
    {
        $url = "";
        if ($this->type_id == self::TYPE_TERM_CONDITION) {
            $url = [
                '//site/terms'
            ];
        } else if ($this->type_id == self::TYPE_ABOUT_US) {
            $url = [
                '//site/about'
            ];
        } else if ($this->type_id == self::TYPE_PRIVACY) {
            $url = [
                '//site/privacy'
            ];
        }

        return $url;
    }
}