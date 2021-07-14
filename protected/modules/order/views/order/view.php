<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\order\models\Order */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Orders'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="order-view">
         <?php echo \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

echo \app\components\TDetailView::widget([
            'id' => 'order-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
                [
                    'attribute' => 'address_id',
                    'format' => 'raw',
                    'value' => function ($data) {
                    return (isset($data->address->primary_address) ? $data->address->primary_address : '').' , '.(isset($data->address->secondary_address) ? $data->address->secondary_address : '');
                    }
                ],
                [
                    'label' => 'Zip/Postal Code',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return isset($data->address->zipcode) ? $data->address->zipcode : '';
                    }
                ],
                'amount',
                'tax',
                'total_amount',
                // 'preparing_time:datetime',
//                 'estimated_time:datetime',
//                 'payment_type',
                [
                    'attribute' => 'payment_status',
                    'format' => 'raw',
                    'value' => $model->getPaymentStatusBadge()
                ],
                // [
                // 'attribute' => 'type_id',
                // 'value' => $model->getType(),
                // ],
                [
                    'attribute' => 'state_id',
                    'format' => 'raw',
                    'value' => $model->getStateBadge()
                ],
                'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
         <?php

echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions()
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="order-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Items', 'items', 'Item', $model /* ,null,true */);
            $this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>