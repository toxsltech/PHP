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
                        'matchCallback' => function () {
                        return User::isAdmin() || User::isSubAdmin();
                        }
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
            }
        }

        // $this->layout = 'main';
        $models = [

            'app\models\User' => [
                'full_name',
                'email'
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

    public function actionContact()
    {
        return $this->render('contact');
    }

    public function actionFeatures()
    {
        $this->layout = 'guest-main';
        return $this->render('features');
    }

    public function actionAbout()
    {
        $this->layout = 'guest-main';
        return $this->render('about');
    }

    public function actionPricing()
    {
        $this->layout = 'guest-main';
        return $this->render('pricing');
    }

    public function actionPrivacy()
    {
        $this->layout = 'guest-main';
        return $this->render('privacy');
    }

    public function actionTerms()
    {
        $this->layout = 'guest-main';
        return $this->render('terms');
    }

    protected function updateMenuItems($model = null)
    {
        // create static model if model is null
        switch ($this->action->id) {
            case 'add':
                {
                    $this->menu[] = array(
                        'label' => Yii::t('app', 'Manage'),
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );
                }
                break;
            default:
            case 'view':
                {
                    $this->menu[] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>Manage',
                        'title' => 'Manage',
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );

                    if ($model != null)
                        $this->menu[] = array(
                            'label' => Yii::t('app', 'Update'),
                            'url' => array(
                                'update',
                                'id' => $model->id
                            ),
                            'visible' => ! User::isAdmin()
                        );
                }
                break;
        }
    }
}

