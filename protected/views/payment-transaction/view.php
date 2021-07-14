<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\models\PaymentTransaction */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Payment Transactions'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="payment-transaction-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'payment-transaction-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
                'name',
                'email:email',
            /*'description:html',*/
            'model_id',
                [
                    'attribute' => 'model_type',
                    'format' => 'raw',
                    'value' => $model->category()
                ],
                'amount',
                'currency',
                'transaction_id',
                // 'value:html',
                // 'gateway_type',
                [
                    'attribute' => 'payment_status',
                    'format' => 'raw',
                    'value' => $model->getPaymentStatusBadge()
                ],
                'created_on:datetime'
            ]
        ])?>
         <?php  echo $model->description;?>
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
			<div class="payment-transaction-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>