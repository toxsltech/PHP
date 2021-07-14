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
use app\models\Focus as FocusModel;

/**
 * Focus represents the model behind the search form about `app\models\Focus`.
 */
class Focus extends FocusModel
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
                    'start_time',
                    'end_time',
                    'state_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'start_date',
                    'duration',
                    'budget',
                    'location',
                    'end_date',
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
        $query = FocusModel::find()->alias('f')->joinWith('createdBy as cb');

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
            'f.start_time' => $this->start_time,
            'f.end_time' => $this->end_time,
            'f.state_id' => $this->state_id,
            'f.image_file' => $this->image_file,
            'f.duration' => $this->duration,
            'f.budget' => $this->budget,
            'f.location' => $this->location
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
            'f.start_date',
            $this->start_date
        ])
            ->andFilterWhere([
            'like',
            'f.end_date',
            $this->end_date
        ])
        ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'f.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
