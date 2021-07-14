<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\models\User;
use Yii;
use yii\helpers\StringHelper;
use app\models\Domain;
use app\models\Focus;
use app\models\Reward;
use app\models\Emoji;
use app\models\Language;
use app\models\TargetArea;

class SearchController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function actionIndex($q = null)
    {
        if (is_numeric($q)) {
            if (($model = User::findOne($q)) !== null) {
                return $this->redirect($model->getUrl());
            }
        }

        if (preg_match('/(.*)-(\d+)/i', $q, $matches)) {
            if ($matches[2] != null && is_numeric($matches[2])) {
                $searchModel = $matches[1];
                $id = $matches[2];

                if ($searchModel == 'User') {
                    if (($model = User::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
                if ($searchModel == 'Domain') {
                    if (($model = Domain::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
                if ($searchModel == 'Focus') {
                    if (($model = Focus::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
                if ($searchModel == 'Reward') {
                    if (($model = Reward::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
                if ($searchModel == 'Emoji') {
                    if (($model = Emoji::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
                if ($searchModel == 'Language') {
                    if (($model = Language::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
                if ($searchModel == 'Target-area') {
                    if (($model = TargetArea::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
                if ($searchModel == 'Target-trade') {
                    if (($model = TargetArea::findOne($id)) !== null) {
                        return $this->redirect($model->getUrl());
                    }
                }
            }
        }

        // $this->layout = 'main';
        $models = [
            'app\models\User' => [
                'full_name',
                'email'
            ],
            'app\models\Domain' => [
                'title'
            ],
            'app\models\Focus' => [
                'title'
            ],
            'app\models\Reward' => [
                'title'
            ],
            'app\models\Emoji' => [
                'title',
                'description'
            ],
            'app\models\Language' => [
                'title',
                'description'
            ],
            'app\models\TargetArea' => [
                'title',
                'description'
            ],
            'app\models\TargetTrade' => [
                'title',
                'description'
            ]
        ];

        $items = [];
        foreach ($models as $model => $attributes) {

            $formKey = StringHelper::basename($model);

            foreach ($attributes as $attribute) {
                $query = $model::find();
                $query->andFilterWhere([
                    'like',
                    $attribute,
                    $q
                ]);

                $count = $query->count();

                if ($count > 0) {
                    $getParams = [];
                    $getParams[$formKey . '[' . $attribute . ']'] = $q;
                    $item = [];
                    $item['title'] = $model::label(2) . '[' . $attribute . ']' . '[' . $count . ']';
                    $item['url'] = (new $model())->getUrl('index', $getParams);
                    $item['$count'] = $count;
                    $items[] = $item;
                }
            }
        }

        return $this->render('index', [
            'q' => $q,
            'items' => $items
        ]);
    }

    protected function updateMenuItems($model = null)
    {}
}

