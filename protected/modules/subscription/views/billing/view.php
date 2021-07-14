<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\subscription\models\Billing */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Billings'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="billing-view">
         <?php echo  \app\components\PageHeader::widget(); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

           echo \app\components\TDetailView::widget([
            'id' => 'billing-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
                [
                    'attribute' => 'subscription_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('subscription_id')
                ],
                'start_date:datetime',
                'end_date:datetime',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
      </div>
	</div>

</div>