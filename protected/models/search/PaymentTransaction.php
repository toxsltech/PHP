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
use app\models\PaymentTransaction as PaymentTransactionModel;

/**
 * PaymentTransaction represents the model behind the search form about `app\models\PaymentTransaction`.
 */
class PaymentTransaction extends PaymentTransactionModel
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
                    'model_id',
                    'gateway_type'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'email',
                    'description',
                    'model_type',
                    'amount',
                    'currency',
                    'transaction_id',
                    'payer_id',
                    'value',
                    'created_on',

                    'payment_status'
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
        $query = PaymentTransactionModel::find();

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
            'model_id' => $this->model_id,
            'gateway_type' => $this->gateway_type,
            'payment_status' => $this->payment_status
        ]);

        $query->andFilterWhere([
            'like',
            'id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'name',
            $this->name
        ])
            ->andFilterWhere([
            'like',
            'email',
            $this->email
        ])
            ->andFilterWhere([
            'like',
            'description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'amount',
            $this->amount
        ])
            ->andFilterWhere([
            'like',
            'currency',
            $this->currency
        ])
            ->andFilterWhere([
            'like',
            'transaction_id',
            $this->transaction_id
        ])
            ->andFilterWhere([
            'like',
            'payer_id',
            $this->payer_id
        ])
            ->andFilterWhere([
            'like',
            'value',
            $this->value
        ])
            ->andFilterWhere([
            'like',
            'created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
