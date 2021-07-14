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
#use app\models\ProductVideo;
use yii\data\ActiveDataProvider;
use app\modules\api\components\ApiBaseController;
use app\models\ProductVideo;
use app\models\User;

/**
 * VideoController implements the API actions for ProductVideo model.
 */
class VideoController extends ApiBaseController
{
    public $modelClass = "app\models\ProductVideo";
  
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
                            'get-trending-video'
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
     * Updates an existing ProductVideo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     
    public function actionMyUpdate($id)
    {
    		$data = [ ];
			$model=$this->findModel($id);	
	        if ($model->load(\Yii::$app->request->post())) {
	            
	            if ($model->save()) {
    	            $data ['status'] = self::API_OK;
    				$data ['detail'] = $model;
  
	            } else {
	                $data['error'] = $model->flattenErrors;
	            }
	        } else {
	            $data['error_post'] = 'No Data Posted';
	        }
	        
	        return $data;
    }
*/
    
    public function actionGetTrendingVideo($page=NULL)
    {
        $model = ProductVideo::find()->where([
            'type_id'=>ProductVideo::TYPE1
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }
    
}
