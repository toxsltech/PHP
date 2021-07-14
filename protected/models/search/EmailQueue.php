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

use app\models\EmailQueue as EmailQueueModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EmailQueue represents the model behind the search form about `app\models\EmailQueue`.
 */
class EmailQueue extends EmailQueueModel
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
                    'attempts',
                    'state_id',
                    'model_id',
                    'email_account_id'
                ],
                'integer'
            ],
            [
                [
                    'from_email',
                    'to_email',
                    'message',
                    'subject',
                    'date_published',
                    'last_attempt',
                    'date_sent',
                    'model_type',
                    'message_id'
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
        $query = EmailQueueModel::find();

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
            'attempts' => $this->attempts,
            'state_id' => $this->state_id,
            'model_id' => $this->model_id,
            'email_account_id' => $this->email_account_id
        ]);

        $query->andFilterWhere([
            'like',
            'id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'from_email',
            $this->from_email
        ])
            ->andFilterWhere([
            'like',
            'to_email',
            $this->to_email
        ])
            ->andFilterWhere([
            'like',
            'message',
            $this->message
        ])
            ->andFilterWhere([
            'like',
            'subject',
            $this->subject
        ])
            ->andFilterWhere([
            'like',
            'date_published',
            $this->date_published
        ])
            ->andFilterWhere([
            'like',
            'last_attempt',
            $this->last_attempt
        ])
            ->andFilterWhere([
            'like',
            'date_sent',
            $this->date_sent
        ])
            ->andFilterWhere([
            'like',
            'model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'message_id',
            $this->message_id
        ]);

        return $dataProvider;
    }
}
