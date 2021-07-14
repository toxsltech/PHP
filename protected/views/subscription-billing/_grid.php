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
 * @var app\models\search\SubscriptionBilling $searchModel
 */

?>

<?php Pjax::begin(['id'=>'subscription-billing-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'subscription-billing-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            [
                'attribute' => 'subscription_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('subscription_id');
                }
            ],
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                return $data->getRelatedDataLink('created_by_id');
                }
                ],
            [
                'label' => 'Payment Status',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getPaymentStatusBadge();
                }
            ],
            /* 'start_date:datetime',*/
            /* 'end_date:datetime',*/
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],



            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'template' => '{view}   {delete}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>