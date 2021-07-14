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
use app\models\PortfolioDetail as PortfolioDetailModel;

/**
 * PortfolioDetail represents the model behind the search form about `app\models\PortfolioDetail`.
 */
class PortfolioDetail extends PortfolioDetailModel
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
                    'description',
                    'title',
                    'image_file',
                    'type_id',
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
        $query = PortfolioDetailModel::find()->alias('p')->joinWith('createdBy as cb');

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
            'p.state_id' => $this->state_id
        ]);

        $query->andFilterWhere([
            'like',
            'p.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'p.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'p.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'p.image_file',
            $this->image_file
        ])
            ->andFilterWhere([
            'like',
            'p.type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'p.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
