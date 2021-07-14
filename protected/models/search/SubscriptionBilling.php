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
use app\models\SubscriptionBilling as SubscriptionBillingModel;

/**
 * SubscriptionBilling represents the model behind the search form about `app\models\SubscriptionBilling`.
 */
class SubscriptionBilling extends SubscriptionBillingModel
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
                    'start_date',
                    'end_date',
                    'subscription_id',
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
        $query = SubscriptionBillingModel::find()->alias('sb')->joinWith('createdBy as cb')->joinWith('subscription as sbi');
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
            'sb.state_id' => $this->state_id,
            'sb.type_id' => $this->type_id,
        ]);

        $query->andFilterWhere([
            'like',
            'sb.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'sb.start_date',
            $this->start_date
        ])
            ->andFilterWhere([
            'like',
            'sb.end_date',
            $this->end_date
        ])
            ->andFilterWhere([
            'like',
            'sb.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
                'like',
                'sbi.title',
                $this->subscription_id
            ]);

        return $dataProvider;
    }
}
