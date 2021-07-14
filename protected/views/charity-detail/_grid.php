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
 * @var app\models\search\CharityDetail $searchModel
 */

?>

<?php Pjax::begin(['id'=>'charity-detail-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'charity-detail-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'amount',
            [
                'attribute' => 'charity_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('charity_id');
                }
            ],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('user_id');
                }
            ],
            [
                'label' => 'Payment Status',
                'format' => 'raw',
                'value' => function ($data) {
                return $data->getPaymentStatusBadge();
                }
                ],
            // [
            // 'attribute' => 'state_id',
            // 'format' => 'raw',
            // 'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
            // 'value' => function ($data) {
            // return $data->getStateBadge();
            // }
            // ],
            // ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
            // 'value' => function ($data) { return $data->getType(); },],
            'created_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'template' => '{view} {delete}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>