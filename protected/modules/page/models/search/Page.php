<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\page\models\search;

use app\modules\page\models\Page as PageModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Page represents the model behind the search form about `app\modules\page\models\Page`.
 */
class Page extends PageModel
{

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'created_on',
                    'updated_on',
                    'created_by_id'
                ],
                'safe'
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function beforeValidate()
    {
        return true;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $type_id = null)
    {
        $query = PageModel::find()->alias('page')->joinWith('createdBy as cr');
        if (! empty($type_id)) {
            $query = PageModel::find()->where([
                'type_id' => $type_id
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        if (! ($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'page.id' => $this->id,
            'page.state_id' => $this->state_id,
            'page.type_id' => $this->type_id,
            'page.created_on' => $this->created_on,
            'page.updated_on' => $this->updated_on
        ]);

        $query->andFilterWhere([
            'like',
            'page.title',
            $this->title
        ])
        ->andFilterWhere([
            'like',
            'page.description',
            $this->description
        ])
        ->andFilterWhere([
            'like',
            'page.description',
            $this->description
        ])
        ->andFilterWhere([
            'like',
            'cr.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
