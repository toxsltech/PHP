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
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Charity as CharityModel;

/**
 * Charity represents the model behind the search form about `app\models\Charity`.
 */
class Charity extends CharityModel
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
                    'image_file',
                    'goal_amount',
                    'description',
                    'raised_amount',
                    'min_amount',
                    'max_amount',
                    'created_on',
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
    public function search($params)
    {
        $query = CharityModel::find()->alias('c')->joinWith('createdBy as cb');

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
            'c.state_id' => $this->state_id,
            'c.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'c.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'c.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'c.image_file',
            $this->image_file
        ])
            ->andFilterWhere([
            'like',
            'c.goal_amount',
            $this->goal_amount
        ])
            ->andFilterWhere([
            'like',
            'c.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'c.raised_amount',
            $this->raised_amount
        ])
            ->andFilterWhere([
            'like',
            'c.min_amount',
            $this->min_amount
        ])
            ->andFilterWhere([
            'like',
            'c.max_amount',
            $this->max_amount
        ])
            ->andFilterWhere([
            'like',
            'c.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
