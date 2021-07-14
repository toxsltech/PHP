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
use app\models\TargetTrade as TargetTradeModel;

/**
 * TargetTrade represents the model behind the search form about `app\models\TargetTrade`.
 */
class TargetTrade extends TargetTradeModel
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
                    'state_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'target_area_id',
                    'created_by_id',
                    'type_id',
                    'created_on'
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
        $query = TargetTradeModel::find()->alias('t')
            ->joinWith('createdBy as cb');
          //  ->joinWith('targetArea as tr');

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
            't.state_id' => $this->state_id,
            't.target_area_id' => $this->target_area_id
        ]);

        $query->andFilterWhere([
            'like',
            't.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            't.title',
            $this->title
        ])
        ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            't.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            't.type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
            't.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
