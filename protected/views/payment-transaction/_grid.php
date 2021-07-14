<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\PaymentTransaction $searchModel
 */

?>

<?php Pjax::begin(['id'=>'payment-transaction-pjax-grid']); ?>
    <?php

echo TGridView::widget([
        'id' => 'payment-transaction-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'name',
            'email:email',
            /* 'description:html',*/
            /* 'model_id',*/
//             'model_type',
            'amount',
            // 'currency',
            [
                'attribute' => 'model_type',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->category();
                }
            ],
            /* 'transaction_id',*/
            /* 'payer_id',*/
            /* 'gateway_type',*/
            [
                'attribute' => 'payment_status',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getPaymentStatusOptions() : null,
                'value' => function ($data) {
                    return $data->getPaymentStatusBadge();
                }
            ],
            /* 'created_on:datetime',*/

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'template'=>'{view}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>