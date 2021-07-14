<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\api\controllers;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
# use app\models\Domain;
use yii\data\ActiveDataProvider;
use app\modules\api\components\ApiBaseController;
use app\models\User;
use app\models\Domain;
use app\models\TargetArea;
use app\models\Activity;

/**
 * DomainController implements the API actions for Domain model.
 */
class DomainController extends ApiBaseController
{

    public $modelClass = "app\models\Domain";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'domain-search',
                            'activity-search',
                            'target-search'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isClient() || User::isManager() || User::isUser();
                        }
                    ]
                ]
            ]
        ];
    }

    public function actionDomainSearch($page = null, $name)
    {
        $data = [];
        if (! empty($name)) {
            $query = Domain::findActive()->andWhere([
                'like',
                'title',
                $name
            ]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                    'page' => $page
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC
                    ]
                ]
            ]);

            if (! empty($dataProvider->getCount() <= 0)) {
                $model = new Domain();
                $model->title = $name;
                $model->state_id = Domain::STATE_ACTIVE;
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                    $data['message'] = 'Domain Added Successfully';
                }
                return $data;
            }
            return $dataProvider;
        } else {
            $data['message'] = 'No data found';
            return $data;
        }
    }

    public function actionTargetSearch($page = null, $name)
    {
        $data = [];
        if (! empty($name)) {
            $query = TargetArea::findActive()->andWhere([
                'like',
                'title',
                $name
            ]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                    'page' => $page
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC
                    ]
                ]
            ]);
            if (! empty($dataProvider->getCount() <= 0)) {
                $model = new TargetArea();
                $model->title = $name;
                $model->state_id = Domain::STATE_ACTIVE;
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                    $data['message'] = 'Target Area Added Successfully';
                }
                return $data;
            }
            return $dataProvider;
        } else {
            $data['message'] = 'No data found';
            return $data;
        }
    }

    public function actionActivitySearch($page = null,$name,$domain_id)
    {
        $data = [];
        if (! empty($name)) {
            $query = Activity::findActive()->andWhere([
                'like',
                'title',
                $name
            ]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                    'page' => $page
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC
                    ]
                ]
            ]);
            if (! empty($dataProvider->getCount() <= 0)) {
                $model = new Activity();
                $model->title = $name;
                $model->domain_id = $domain_id;
                $model->state_id = Activity::STATE_ACTIVE;
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                    $data['message'] = 'Activity Added Successfully';
                }
                return $data;
            }
            return $dataProvider;
        } else {
            $data['message'] = 'No data found';
            return $data;
        }
    }
}
