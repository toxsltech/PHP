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
 * @var app\modules\order\models\search\Order $searchModel
 */

?>

<?php Pjax::begin(['id'=>'order-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'order-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                return $data->getRelatedDataLink('created_by_id');
                }
                ],
            [
                'attribute' => 'address_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return ((isset($data->address->primary_address) ? $data->address->primary_address : '') . " , " . (isset($data->address->secondary_address) ? $data->address->secondary_address : ''));
                }
            ],
            [
                'label' => 'Zip/Postal Code',
                'format' => 'raw',
                'value' => function ($data) {
                    return isset($data->address->zipcode) ? $data->address->zipcode : '';
                }
            ],
            // 'amount',
            /* 'tax', */
            'total_amount',
            /* 'preparing_time:datetime',*/
//             'estimated_time:datetime',
            /* 'payment_type',*/
           
            'created_on:datetime',

            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            [
                'attribute' => 'payment_status',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getPaymentStatusOptions() : null,
                'value' => function ($data) {
                    return $data->getPaymentStatusBadge();
                }
            ],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>