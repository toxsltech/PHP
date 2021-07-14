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
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use app\modules\api\components\ApiBaseController;
use app\models\Product;
use app\models\Category;
use app\models\Package;
use app\models\ProductVideo;
use app\models\User;
use app\modules\order\models\Order;
use app\modules\favorite\models\Item;
use app\models\Address;
use app\models\SubscriptionPlan;
use app\models\UserDetail;
use app\modules\notification\models\Notification;

/**
 * ProductController implements the API actions for Product model.
 */
class ProductController extends ApiBaseController
{

    public $modelClass = "app\models\Product";

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
                            'view-product',
                            'get-category',
                            'package-list',
                            'package-detail',
                            'place-order',
                            'order-detail',
                            'product-list',
                            'product-detail',
                            'video-list',
                            'get-order-list',
                            'subscription-detail',

                            'image'
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
        return [

            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => Product::class,
                'attribute' => 'image_file'
            ]
        ];
    }

    public function actionViewProduct($page = null)
    {
        $model = Product::find()->where([
            'state_id' => Product::STATE_ACTIVE
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 5,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    public function actionGetCategory($page = null)
    {
        $query = Category::find()->where([
            'state_id' => Category::STATE_ACTIVE,
            'type_id' => Category::TYPE1
        ]);
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

    public function actionProductList($page = null)
    {
        $query = Product::find()->where([
            'state_id' => Product::STATE_ACTIVE
        ]);
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

    public function actionProductDetail($id)
    {
        $data = [];
        $item = Product::findOne($id);
        if (! empty($item)) {
            $this->setStatus(200);
            $data['details'] = $item->asJson();
        } else {
            $data['message'] = \Yii::t('app', 'Product not found');
        }
        return $data;
    }

    public function actionSubscriptionDetail($id)
    {
        $data = [];
        $item = SubscriptionPlan::findOne($id);

        if (! empty($item)) {
            $this->setStatus(200);
            $data['details'] = $item->asJson();
        } else {
            $data['message'] = \Yii::t('app', 'plan not found');
        }
        return $data;
    }

    public function actionPackageList($page = null)
    {
        $query = Package::find()->where([
            'state_id' => Package::STATE_ACTIVE
        ]);
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

    public function actionPackageDetail($id)
    {
        $data = [];
        $item = Package::findOne($id);
        if (! empty($item)) {
            $this->setStatus(200);
            $data['details'] = $item->asJson();
        } else {
            $data['message'] = \Yii::t('app', 'Package not found');
        }
        return $data;
    }

    public function actionVideoList($page = null)
    {
        $model = ProductVideo::find()->where([
            'type_id' => ProductVideo::TYPE2
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

    public function actionPlaceOrder()
    {
        $data = [];
        $totalAmount = 0;
        $type_id = 0;
//         $user = \Yii::$app->user->identity;
//         $userDetail = $user->userDetail;
        $model = new Order();
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $address = Address::find()->where([
                'id' => $model->address_id,
                'created_by_id' => \yii::$app->user->id
            ])->one();
            if (! empty($address)) {
                $transaction = \Yii::$app->db->beginTransaction();
                $model->state_id = Order::STATE_ACTIVE;
                $jsonDtata = Json::decode($post['itemJson'], false);
                try {
                    if ($model->save()) {
                        foreach ($jsonDtata as $value) {
                            if ($value->type_id == Package::PACKAGE) {
                                $exist = Package::find()->where([
                                    'id' => $value->item_id
                                ])->one();
                                if ($exist) {

//                                     $user = \Yii::$app->user->identity;
//                                     $plan = $user->userDetail;
//                                     if (! empty($plan)) {
//                                         if (is_null($plan['subscription_id']) == \app\modules\order\models\Order::STATE_INACTIVE) {
//                                             $plandetail = SubscriptionPlan::findOne([
//                                                 'id' => $plan['subscription_id']
//                                             ]);
//                                             $userPackage = \app\modules\order\models\Item::find()->where([
//                                                 'created_by_id' => \Yii::$app->user->id,
//                                                 'type_id' => Package::PACKAGE
//                                             ])->count();

//                                             if ($userPackage < $plandetail['total_delivered']) {

                                                $modelItem = new \app\modules\order\models\Item();
                                                $modelItem->order_id = $model->id;
                                                $modelItem->item_id = $value->item_id;
                                                $modelItem->state_id = Order::STATE_ACTIVE;
                                                $modelItem->amount = $value->amount;
                                                $modelItem->quantity = $value->quantity;
                                                $modelItem->type_id = $value->type_id;
                                                $modelItem->created_by_id = $model->created_by_id;
                                                $totalAmount += $modelItem->amount * $modelItem->quantity;
                                                if (! $modelItem->save()) {
                                                    throw new \Exception($modelItem->getErrors());
                                                }

//                                             } else {
//                                                 $this->setStatus(400);
//                                                 $data['message'] = 'You have reached your package purchase limit';
//                                                 return $data;
//                                             }
//                                         } else {

//                                             $this->setStatus(400);
//                                             $data['message'] = 'you have not subscribed to any plan,please purchase a plan first to buy package';
//                                             return $data;
//                                         }
//                                     } else {
//                                         $this->setStatus(400);
//                                         $data['message'] = 'Please!, subscribe';
//                                         return $data;
//                                     }
                                } else {
                                    $this->setStatus(404);
                                    $data['message'] = 'No package found';
                                    return $data;
                                }
                            } else if ($value->type_id == Product::PRODUCT) {
                                $exist = Product::find()->where([
                                    'id' => $value->item_id
                                ])->one();
                                if ($exist) {
                                    $modelItem = new \app\modules\order\models\Item();
                                    $modelItem->order_id = $model->id;
                                    $modelItem->item_id = $value->item_id;
                                    $modelItem->state_id = Order::STATE_ACTIVE;
                                    $modelItem->amount = $value->amount;
                                    $modelItem->quantity = $value->quantity;
                                    $modelItem->type_id = $value->type_id;
                                    $modelItem->created_by_id = $model->created_by_id;
                                    $totalAmount += $modelItem->amount * $modelItem->quantity;
                                    if (! $modelItem->save()) {
                                        throw new \Exception($modelItem->getErrors());
                                    }
                                } else {
                                    $this->setStatus(404);
                                    $data['message'] = 'No product found';
                                    return $data;
                                }
                            } else {
                                $this->setStatus(404);
                                $data['message'] = 'No product or package found';
                                return $data;
                            }
                        }
                        $model->updateAttributes([
                            'amount' => $totalAmount,
                            'type_id' => $model->type_id
                        ]);
                        $admin = User::find()->where([
                            'role_id' => User::ROLE_ADMIN
                        ])->one();
                        if (! empty($admin)) {
                            Notification::create([
                                'to_user_id' => $model->created_by_id,
                                'created_by_id' => $admin->id,
                                'title' => \Yii::t('app', 'You have placed an order'),
                                'model' => $model
                            ]);
                        }
                        $this->setStatus(200);
                        $transaction->commit();
                        $data['Order'] = $model->asJson();
                        $data['message'] = \Yii::t('app', 'Order placed successfully');
                    } else {

                        $data['error'] = $model->getErrors();
                    }
                } catch (\Exception $e) {

                    $data['error'] = $e->getMessage();
                    $transaction->rollBack();
                }
            } else {
                $this->setStatus(400);
                $data['message'] = \Yii::t('app', "No address found");
            }
        } else {
            $this->setStatus(400);
            $data['message'] = $model->getErrors();
        }
        return $data;
    }

    public function actionOrderDetail($id)
    {
        $data = [];
        $item = Order::findOne($id);
        if (! empty($item)) {
            $this->setStatus(200);
            $data['Detail'] = $item->asjson();
        } else {
            $this->setStatus(404);
            $data['message'] = \Yii::t('app', 'Order not found');
        }
        return $data;
    }

    public function actionGetOrderList($page = NULL)
    {
        $data = [];

        $model = Order::find()->where([
            'created_by_id' => \Yii::$app->user->id
        ]);
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
}