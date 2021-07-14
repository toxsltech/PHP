<?php
use app\modules\comment\widgets\CommentsWidget;
use app\modules\workflow\widgets\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\favorite\models\QuickLink */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Favorites'),
    'url' => [
        '/favorite'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Quick Links'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="quick-link-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php
        echo \app\components\TDetailView::widget([
            'id' => 'quick-link-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
                    /*'title',*/
                    'url:url',
                [
                    'attribute' => 'explore_option',
                    'value' => $model->getExplore()
                ],
                   /*  [
                        'attribute' => 'type_id',
                        'value' => $model->getType()
                    ], */
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
         <?php  ?>
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
			<div class="quick-link-panel">
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