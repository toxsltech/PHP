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
namespace app\modules\api\controllers;

use app\components\TController;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Default controller for the `Api` module
 */
class DefaultController extends TController
{

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'model'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index'
                        ],
                        'allow' => true,

                        'matchCallback' => function () {
                            return YII_ENV == 'dev';
                        }
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'api-docs' => [
                'class' => 'genxoft\swagger\ViewAction',
                'apiJsonUrl' => \yii\helpers\Url::to([
                    '/api/default/api-json'
                ], true)
            ],
            'api-json' => [
                'class' => 'genxoft\swagger\JsonAction',
                'dirs' => [
                    Yii::getAlias('@app/modules/api/controllers'),
                    Yii::getAlias('@app/modules/api/models'),
                    Yii::getAlias('@app/models')
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        if (! parent::beforeAction($action)) {
            return false;
        }

        $this->layout = 'guest-main';

        \Yii::$app->response->format = Response::FORMAT_HTML;

        return true;
    }

    public function actionMove($file, $token = null)
    {
        $path = __DIR__ . '/../test/' . $file . '.php';

        $models = require ($path);

        return $this->render('move', [
            'models' => $models
        ]);
    }

    public function actionIndex()
    {
        $filelist = $this->getTestFiles();

        $data = [];

        foreach ($filelist as $key => $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $data[] = [
                'id' => $key,
                'file' => $name
            ];
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'models' => $data
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function getTestFiles()
    {
        $path = __DIR__ . '/../test/*';
        $filelist = glob($path . "*");
        return $filelist;
    }
}
