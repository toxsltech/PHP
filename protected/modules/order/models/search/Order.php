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
use app\modules\order\models\Order as OrderModel;

/**
 * Order represents the model behind the search form about `app\modules\order\models\Order`.
 */
class Order extends OrderModel
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
                    'product_id',
                    'tip',
                    'is_pickup',
                    'payment_type',
                    'type_id',
                    'state_id',
                    'payment_status'
                ],
                'integer'
            ],
            [
                [
                    'amount',
                    'tax',
                    'total_amount',
                    'preparing_time',
                    'estimated_time',
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
        $query = OrderModel::find()->alias('o')->joinWith('createdBy as cb');
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
            'o.product_id' => $this->product_id,
            'tip' => $this->tip,
            'o.is_pickup' => $this->is_pickup,
            'o.payment_type' => $this->payment_type,
            'o.payment_status' => $this->payment_status,
            'o.type_id' => $this->type_id,
            'o.state_id' => $this->state_id,
        ]);

        $query->andFilterWhere([
            'like',
            'o.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'o.amount',
            $this->amount
        ])
            ->andFilterWhere([
            'like',
            'o.tax',
            $this->tax
        ])
            ->andFilterWhere([
            'like',
            'o.total_amount',
            $this->total_amount
        ])
            ->andFilterWhere([
            'like',
            'o.preparing_time',
            $this->preparing_time
        ])
            ->andFilterWhere([
            'like',
            'o.estimated_time',
            $this->estimated_time
        ])
            ->andFilterWhere([
            'like',
            'o.created_on',
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
