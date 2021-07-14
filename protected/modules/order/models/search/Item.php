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
namespace app\modules\order\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\order\models\Item as ItemModel;

/**
 * Item represents the model behind the search form about `app\modules\order\models\Item`.
 */
class Item extends ItemModel
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
                    'order_id',
                    'state_id',
                    'type_id',

                ],
                'integer'
            ],
            [
                [
                    'amount',
                    'quantity',
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
        $query = ItemModel::find()->alias('i')->joinWith('createdBy as cb');

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
            'i.order_id' => $this->order_id,
            'i.state_id' => $this->state_id,
            'i.type_id' => $this->type_id,
        ]);

        $query->andFilterWhere([
            'like',
            'i.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'i.amount',
            $this->amount
        ])
            ->andFilterWhere([
            'like',
            'i.quantity',
            $this->quantity
        ])
            ->andFilterWhere([
            'like',
            'i.created_on',
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
