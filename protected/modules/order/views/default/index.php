<?php
use app\components\TDashBox;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;
use app\modules\order\models\search\Order;
use app\modules\order\models\Item;
use app\models\User;
use app\models\PaymentTransaction;
use app\models\SubscriptionPlan;

?>
<div class="wrapper">
	<div class="card">
		<div class="card-body">
         <?php

echo TDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/order/order/index'
                    ]),
                    'color' => 'green',
                    'data' => Order::find()->count(),
                    'header' => 'Order'
                ],
                [
                    'url' => Url::toRoute([
                        '/order/item/index'
                    ]),
                    'color' => 'bg-warning',
                    'data' => Item::find()->count(),
                    'header' => 'Placed Order'
                ]
            ]
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
               <?php

$data = Order::monthly();
            echo Highcharts::widget([
                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),
                    'title' => [
                        'text' => 'Monthly  '
                    ],
                    'chart' => [
                        'type' => 'spline'
                    ],
                    'xAxis' => [
                        'categories' => array_keys($data)
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => 'Count'
                        ]
                    ],
                    'series' => [
                        [
                            'name' => 'Orders',
                            'data' => array_values($data)
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
				<div class="col-md-6">
               <?php

$data = User::monthly();
            echo Highcharts::widget([
                'scripts' => [
                    'highcharts-3d',
                    'modules/exporting'
                ],
                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),
                    'chart' => [
                        'plotBackgroundColor' => null,
                        'plotBorderWidth' => null,
                        'plotShadow' => false,
                        'type' => 'pie'
                    ],
                    'title' => [
                        'text' => 'Statistics'
                    ],
                    'tooltip' => [
                        'valueSuffix' => ''
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor' => 'pointer',
                            'dataLabels' => [
                                'enabled' => true
                            ],
                             'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                            'showInLegend' => true
                        ]
                    ],
                    'htmlOptions' => [
                        'style' => 'min-width: 100%; height: 400px; margin: 0 auto'
                    ],
                    'series' => [
                        [
                            'name' => 'Total Count',
                            'colorByPoint' => true,
                            'data' => [
                                [
                                    'name' => 'Users',
                                    'color' => '#0096ff',
                                    'y' => (int)$data,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Orders',
                                    'color' => '#ffc107',
                                    'y' => (int) \app\modules\order\models\Order::find()->andWhere([
                                        'payment_status' => PaymentTransaction::PAID
                                    ])->count(),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Subscriptions',
                                    'color' => '#28a745',
                                    'y' =>  (int) PaymentTransaction::find()->where([
                                        'model_type' => SubscriptionPlan::className(),
                                        'payment_status' => PaymentTransaction::PAID,
                                    ])->count(),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                            ]
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

$searchModel = new Order();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->pagination->pageSize = 5;
        echo $this->render('/order/_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
        ?>
      </div>
	</div>
</div>