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
# use app\models\Charity;
use yii\data\ActiveDataProvider;
use app\modules\api\components\ApiBaseController;
use app\models\Charity;
use app\models\User;
use app\models\CharityDetail;

/**
 * CharityController implements the API actions for Charity model.
 */
class CharityController extends ApiBaseController
{

    public $modelClass = "app\models\Charity";

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
                            'get-charity',
                            'charity-payment'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'image'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*',
                            '@'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
    }

    public function actionGetCharity($page = null)
    {
        $model = Charity::find()->where([
            'state_id' => Charity::STATE_ACTIVE
        ]);
        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 100,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionCharityPayment()
    {
        $data = [];
        $model = new CharityDetail();
        $model->user_id = \Yii::$app->user->id;
        $post = \Yii::$app->request->post();
        
        if ($model->load($post)) {
            $charityModel = Charity::findOne( $model->charity_id );
            if (! empty($charityModel)) {
                $charityModel->updateAttributes([
                    'raised_amount' => (int) $charityModel->raised_amount + (int) $model->amount
                ]);
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['detail'] = $model->asJson();
                } else {
                    $this->setStatus(400);
                    $data['error'] = $model->getErrors();
                }
            } else {
                $this->setStatus(404);
                $data['message'] = \Yii::t('app', 'No Data Found');
            }
        } else {
            $data['error'] = $model->getErrors();
        }

        return $data;
    }
}
