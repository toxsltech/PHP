<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\models\TargetTrade */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Target Trades'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="target-trade-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php
        echo \app\components\TDetailView::widget([
            'id' => 'target-trade-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
            /*'title',*/
            /*'description:html',*/
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
                [
                    'attribute' => 'target_area_id',
                    'value' => $model->targetArea
                ],
                'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'value' => $model->createdBy
                ]
            ]
        ])?>
         <?php  echo $model->description;?>
      </div>
	</div>
	     <?php

    echo UserAction::widget([
        'model' => $model,
        'attribute' => 'state_id',
        'states' => $model->getStateOptions()
    ]);
    ?>
	<div class="card">
		<div class="card-body">
			<div class="target-trade-panel">
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