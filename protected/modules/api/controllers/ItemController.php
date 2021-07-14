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
# use app\modules\favorite\models\Item;
use yii\data\ActiveDataProvider;
use app\modules\api\components\ApiBaseController;
use app\modules\favorite\models\Item;
use app\models\Product;
use app\models\User;
use app\models\Package;

/**
 * ItemController implements the API actions for Item model.
 */
class ItemController extends ApiBaseController
{

    public $modelClass = "app\modules\favorite\models\Item";

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
                            'favourite',
                            'favourite-package',
                            'favourite-list',
                            'favourite-package-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Get single studio details by id
     *
     * @param
     *            return studioinfo
     */
    
    Public function actionFavourite()
    {
        $data = [];
        $model = new Item();
        $post = \Yii::$app->request->post();
        $id=$post['Item']['id'];
        $product=Product::findOne($id);
        if(!empty($product)){
        if ($model->load($post)) {
            $model->model_type = Product::className();
            $model->model_id = $post['Item']['id'];
            $favorite = Item::find()->where([
                'model_id' => $model->model_id,
                'model_type' => $model->model_type,
                'created_by_id' => \Yii::$app->user->id
            ])->one();
            if (! empty($favorite)) {
                if ($favorite->delete()) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Removed from favorite list');
                    $data['detail'] = $model->asJson();
                  
                } else {
                    $this->setStatus(400);
                    $data['message'] = \Yii::t('app', $model->getErrors());
                }
            } else {
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Added to Your favorite list');
                    $data['detail'] = $model->asJson();
                } else {
                    $this->setStatus(400);
                    $data['message'] = \Yii::t('app', $model->getErrors());
                }
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'NO data posted');
        }
        }
        else{
            $this->setStatus(404);
            $data['message'] = \Yii::t('app', 'NO product found');
        }
        
        return $data;
    }
    
    Public function actionFavouritePackage()
    {
        $data = [];
        $model = new Item();
        $post = \Yii::$app->request->post();
        $id=$post['Item']['id'];
        $package=Package::findOne($id);
        if(!empty($package)){
        if ($model->load($post)) {
            $model->model_type = Package::className();
            $model->model_id = $post['Item']['id'];
            $favorite = Item::find()->where([
                'model_id' => $model->model_id,
                'model_type' => $model->model_type,
                'created_by_id' => \Yii::$app->user->id
            ])->one();
            if (! empty($favorite)) {
                if ($favorite->delete()) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Removed from favorite list');
                    $data['detail'] = $model->asJson();
                    
                } else {
                    $this->setStatus(400);
                    $data['message'] = \Yii::t('app', $model->getErrors());
                }
            } else {
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', 'Added to Your favorite list');
                    $data['detail'] = $model->asJson();
                } else {
                    $this->setStatus(400);
                    $data['message'] = \Yii::t('app', $model->getErrors());
                }
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'NO data posted');
        }
        }
        else{
            $this->setStatus(404);
            $data['message'] = \Yii::t('app', 'NO package found');
        }
        return $data;
    }
    
    /**
     * get list of favourite
     *
     * @param
     *            $page
     * @return
     */
    public function actionFavouriteList($page = null)
    {
        $data = [];
        $exist = Item::find()->where([
            'created_by_id' => \Yii::$app->user->id,
            'model_type'=>Product::className()
        ])->all();

        $model_ids = [];
        if (! empty($exist)) {
            foreach ($exist as $key => $value) {

                $model_ids[] = $value->model_id;
            }
            $query = Product::find()->where([
                'in',
                'id',
                $model_ids
            ]);
        } else {
            $query = Product::find()->where([
                'id' => $model_ids
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        // $data['list'] = $dataProvider;
        $this->setStatus(200);
        return $dataProvider;
    }
    
    public function actionFavouritePackageList($page = null)
    {
        $data = [];
        $exist = Item::find()->where([
            'created_by_id' => \Yii::$app->user->id,
            'model_type'=>Package::className()
            
        ])  ->all();
        
        $model_ids = [];
        if (! empty($exist)) {
            foreach ($exist as $key => $value) {
                
                $model_ids[] = $value->model_id;
            }
            $query = Package::find()->where([
                'in',
                'id',
                $model_ids
            ]);
        } else {
            $query = Package::find()->where([
                'id' => $model_ids
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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

/**
 * Updates an existing Item model.
 * If update is successful, the browser will be redirected to the 'view' page.
 *
 * @return mixed public function actionMyUpdate($id)
 *         {
 *         $data = [ ];
 *         $model=$this->findModel($id);
 *         if ($model->load(\Yii::$app->request->post())) {
 *        
 *         if ($model->save()) {
 *         $data ['status'] = self::API_OK;
 *         $data ['detail'] = $model;
 *        
 *         } else {
 *         $data['message'] = $model->flattenErrors;
 *         }
 *         } else {
 *         $data['error_post'] = 'No Data Posted';
 *         }
 *        
 *         return $data;
 *         }
 */
}
