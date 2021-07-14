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
use app\models\Category as CategoryModel;

/**
 * Category represents the model behind the search form about `app\models\Category`.
 */
class Category extends CategoryModel
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
                    'created_by_id',
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
        $query = CategoryModel::find()->alias('category')->joinWith('createdBy as cr');

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
            'category.state_id' => $this->state_id,
            'category.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'category.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'category.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'category.created_on',
            $this->created_on
        ])
        ->andFilterWhere([
            'like',
            'cr.created_by_id',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
