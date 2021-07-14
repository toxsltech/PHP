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
namespace app\commands;

use app\components\TConsoleController;

class TestController extends TConsoleController
{

    private $models = [

        'app\modules\blog\models\Category',
        'app\modules\blog\models\Post',
        'app\modules\feature\models\Feature'
    ];

    public static function log($string)
    {
        echo $string . PHP_EOL;
    }

    public function actionIndex()
    {
        $this->actionClean();
        $this->actionData();
    }

    /**
     * Remove table
     */
    public function actionClean()
    {
        foreach ($this->models as $model) {
            self::log("adding " . $model);
            if (method_exists($model, 'truncate')) {
                $model::truncate();
            }
        }
    }

    /**
     * Add data
     */
    public function actionData($count = 1)
    {
        foreach ($this->models as $model) {
            self::log("adding " . $model);
            if (method_exists($model, 'addTestData')) {
                $model::addTestData($count);
            }
        }
    }

    public function actionReset($dontSkip = 0)
    {
        self::log('actionReset :' . $dontSkip);

        $tables = [
            'app\modules\recruitment\models\Vacancy',

            'app\modules\candidate\models\Profile',
            'app\modules\emailreader\models\Email',
            'app\modules\company\models\Profile'
        ];

        foreach ($tables as $modelClass) {
            foreach ($modelClass::find()->each() as $model) {
                $model->delete();
            }
        }
    }

}

