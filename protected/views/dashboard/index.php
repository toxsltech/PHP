<?php
use app\components\TDashBox;
use app\components\notice\Notices;
use app\models\EmailQueue;
use app\models\LoginHistory;
use app\modules\logger\models\Log;
use yii\helpers\Url;
use app\models\User;
use app\models\search\User as UserSearch;
use miloschuman\highcharts\Highcharts;

/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
/* @var $this yii\web\View */
// $this->title = Yii::t ( 'app', 'Dashboard' );
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Dashboard')
];
?>

<div class="wrapper">
	<!--state overview start-->
         <?php

        echo TDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/user'
                    ]),
                    'color' => 'dashboard-itembox',
                    'data' => User::findActive()->andWhere([
                        'not in',
                        'role_id',
                        [
                            User::ROLE_ADMIN
                        ]
                    ])->count(),
                    'header' => 'Total Users'
                ],
                [
                    'url' => Url::toRoute([
                        '/user/providers'
                    ]),
                    'color' => 'dashboard-itembox',
                    'data' => User::findActive()->andWhere([
                        'role_id' => User::ROLE_PROVIDER
                    ])->count(),
                    'header' => 'Total Providers Registered'
                ],
                [
                    'url' => Url::toRoute([
                        '/user/customers'
                    ]),
                    'color' => 'dashboard-itembox',
                    'data' => User::findActive()->andWhere([
                        'role_id' => User::ROLE_CUSTOMER
                    ])->count(),
                    'header' => 'Total Customers Registered'
                ],
                [
                    'url' => Url::toRoute([
                        '/user/business'
                    ]),
                    'color' => 'dashboard-itembox',
                    'data' => User::findActive()->andWhere([
                        'role_id' => User::ROLE_BUSINESS
                    ])->count(),
                    'header' => 'Total Business Registered'
                ]
            ]
        ]);
        ?>  	
  	
  	 <div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
       
                <?php
                $data = UserSearch::daily();
                echo Highcharts::widget([
                    'options' => [
                        'credits' => array(
                            'enabled' => false
                        ),

                        'title' => [
                            'text' => 'Daily Users'
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
                                'name' => 'Users',
                                'data' => array_values($data)
                            ]
                        ]
                    ]
                ]);
                ?> 
            </div>
				<div class="col-md-6">
                            <?php
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
                                        'text' => 'Users'
                                    ],
                                    'tooltip' => [
                                        'valueSuffix' => ''
                                    ],
                                    'accessibility' => [
                                        'point' => [

                                            'valueSuffix' => '%'
                                        ]
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
                                            'name' => 'Rides',
                                            'colorByPoint' => true,

                                            'data' => [
                                                [
                                                    'name' => 'Users',
                                                    'color' => '#28a745',
                                                    'y' => (int) User::find()->where([
                                                        'role_id' => User::ROLE_ADMIN
                                                    ])->count(),
                                                    'sliced' => true,
                                                    'selected' => true
                                                ],

                                                [
                                                    'name' => 'Providers',
                                                    'color' => '#6610f2',
                                                    'y' => (int) User::find()->where([
                                                        'role_id' => User::ROLE_PROVIDER
                                                    ])->count(),
                                                    'sliced' => true,
                                                    'selected' => true
                                                ],
                                                [
                                                    'name' => 'Customers',
                                                    'color' => '#17a2b8',
                                                    'y' => (int) User::find()->where([
                                                        'role_id' => User::ROLE_CUSTOMER
                                                    ])->count(),
                                                    'sliced' => true,
                                                    'selected' => true
                                                ],
                                                [
                                                    'name' => 'Business',
                                                    'color' => '#dc3545',
                                                    'y' => (int) User::find()->where([
                                                        'role_id' => User::ROLE_BUSINESS
                                                    ])->count(),
                                                    'sliced' => true,
                                                    'selected' => true
                                                ]
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
    $searchModel = new UserSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query->andWhere([
        'not in',
        'u.role_id',
        [
            User::ROLE_ADMIN
        ]
    ]);
    echo $this->render('/user/_grid', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel
    ]);
    ?>

</div>
	</div>

</div>