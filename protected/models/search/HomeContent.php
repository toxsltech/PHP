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
use app\models\HomeContent as HomeContentModel;

/**
 * HomeContent represents the model behind the search form about `app\models\HomeContent`.
 */
class HomeContent extends HomeContentModel
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
                    'description',
                    'image_file',
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
        $query = HomeContentModel::find()->alias('f')->joinWith('createdBy as cb');
        ;

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
            'f.state_id' => $this->state_id,
            'f.type_id' => $this->type_id,
            'f.created_by_id' => $this->created_by_id
        ]);

        $query->andFilterWhere([
            'like',
            'f.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'f.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'f.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'f.image_file',
            $this->image_file
        ])
            ->andFilterWhere([
            'like',
            'f.created_on',
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
