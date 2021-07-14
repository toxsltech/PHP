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
# use app\modules\comment\models\Comment;
use yii\data\ActiveDataProvider;
use app\modules\api\components\ApiBaseController;
use app\models\User;

/**
 * CommentController implements the API actions for Comment model.
 */
class CommentController extends ApiBaseController
{

    public $modelClass = "app\modules\comment\models\Comment";

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
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete'
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
}
