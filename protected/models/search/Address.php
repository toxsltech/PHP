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
use app\models\Address as AddressModel;

/**
 * Address represents the model behind the search form about `app\models\Address`.
 */
class Address extends AddressModel
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
                    'user_id',
                    'no_of_box',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'first_name',
                    'last_name',
                    'primary_address',
                    'secondary_address',
                    'city',
                    'state',
                    'country',
                    'zipcode',
                    'contact_no',
                    'date',
                    'time',
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
        $query = AddressModel::find();

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
            'user_id' => $this->user_id,
            'no_of_box' => $this->no_of_box,
            'time' => $this->time,
            'state_id' => $this->state_id,
            'type_id' => $this->type_id,
            'created_by_id' => $this->created_by_id
        ]);

        $query->andFilterWhere([
            'like',
            'id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'first_name',
            $this->first_name
        ])
            ->andFilterWhere([
            'like',
            'last_name',
            $this->last_name
        ])
            ->andFilterWhere([
            'like',
            'primary_address',
            $this->primary_address
        ])
            ->andFilterWhere([
            'like',
            'secondary_address',
            $this->secondary_address
        ])
            ->andFilterWhere([
            'like',
            'city',
            $this->city
        ])
            ->andFilterWhere([
            'like',
            'state',
            $this->state
        ])
            ->andFilterWhere([
            'like',
            'country',
            $this->country
        ])
            ->andFilterWhere([
            'like',
            'zipcode',
            $this->zipcode
        ])
            ->andFilterWhere([
            'like',
            'contact_no',
            $this->contact_no
        ])
            ->andFilterWhere([
            'like',
            'date',
            $this->date
        ])
            ->andFilterWhere([
            'like',
            'created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
