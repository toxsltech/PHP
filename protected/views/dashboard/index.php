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
use app\modules\order\models\Order;
use app\modules\order\models\Item;
use app\models\Address;
use app\models\Product;

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
                    'color' => 'green',
                    'data' => User::findActive()->where(['role_id'=> User::ROLE_USER])->count(),
                    'header' => 'Users'
                ],
                [
                    'url' => Url::toRoute([
                        '/email-queue'
                    ]),
                    'color' => 'bg-warning',
                    'data' => EmailQueue::findActive(0)->count(),
                    'header' => 'Pending Emails'
                ],
                [
                    'url' => Url::toRoute([
                        '/logger/log'
                    ]),
                    'color' => 'bg-info',
                    'data' => Log::find()->count(),
                    'header' => 'Logs'
                ],
                [
                    'url' => Url::toRoute([
                        '/login-history/index'
                    ]),
                    'color' => 'red',
                    'data' => LoginHistory::find()->count(),
                    'header' => 'LoginHistory'
                ],
                [
                    'url' => Url::toRoute([
                        '/product/index'
                    ]),
                    'color' => 'bg-info',
                    'data' => Product::find()->count(),
                    'header' => 'Product'
                ],
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
                ],
                [
                    'url' => Url::toRoute([
                        '/address/index'
                    ]),
                    'color' => 'bg-info',
                    'data' => Address::find()->count(),
                    'header' => 'Address'
                ],
                
            ]
        ]);
        ?>
   
	
	<div class="card">
		<div class="card-heading">
			<span class="tools pull-right"> </span>
		</div>
		<div class="card-body">


<?php
$data = UserSearch::monthly();
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Monthly'
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

	</div>
	<?php if(user::isAdmin()){?>
	<div class="card">

		<div class="card-body">
			<?php
			
			    $searchModel = new UserSearch();
			    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			    
			    echo $this->render('/user/_grid', [
			        'dataProvider' => $dataProvider,
			        'searchModel' => $searchModel
			    ]);
?>
		</div>
	</div>
	<?php }?>
</div>