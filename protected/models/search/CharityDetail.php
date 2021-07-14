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
use app\models\CharityDetail as CharityDetailModel;

/**
 * CharityDetail represents the model behind the search form about `app\models\CharityDetail`.
 */
class CharityDetail extends CharityDetailModel
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
                    'charity_id',
                    'user_id',
                    'state_id',
                    'type_id',
   
                ],
                'integer'
            ],
            [
                [
                    'amount',
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
        $query = CharityDetailModel::find()->alias('c')->joinWith('createdBy as cb');

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
            'c.charity_id' => $this->charity_id,
            'c.user_id' => $this->user_id,
            'c.state_id' => $this->state_id,
            'c.type_id' => $this->type_id,
        ]);

        $query->andFilterWhere([
            'like',
            'c.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'c.amount',
            $this->amount
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
