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
use app\models\News as NewsModel;

/**
 * News represents the model behind the search form about `app\models\News`.
 */
class News extends NewsModel
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
                    'title',
                    'description',
                    'summary',
                    'image_file',
                    'type_id',
                    'created_on',
                    'start_date',
                    'end_date',
                    'start_time',
                    'domain_id',
                    'end_time',
                    'location',
                    'budget',
                    'duration',
                    'created_by_id',
                    'latitude',
                    'longitude'
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
        $query = NewsModel::find()->alias('n')->joinWith('createdBy as cb');

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
            'n.state_id' => $this->state_id,
            'n.start_date' => $this->start_date,
            'n.end_date' => $this->end_date,
            'n.start_time' => $this->start_time,
            'n.domain_id' => $this->domain_id,
            'n.end_time' => $this->end_time,
            'n.location' => $this->location,
            'n.budget' => $this->budget,
            'n.duration' => $this->duration,
            'n.latitude' => $this->latitude,
            'n.longitude' => $this->longitude
        ]);

        $query->andFilterWhere([
            'like',
            'n.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'n.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'n.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'n.summary',
            $this->summary
        ])
            ->andFilterWhere([
            'like',
            'n.image_file',
            $this->image_file
        ])
            ->andFilterWhere([
            'like',
            'n.type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'n.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
