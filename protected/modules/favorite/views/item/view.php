<?php
use app\components\PageHeader;
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Favorites'),
    'url' => [
        '/favorite'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Item'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">

		<div class="favorite-view card-body">
			<?php echo  PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php

echo \app\components\TDetailView::widget([
        'id' => 'favorite-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'project_id',
            'model_type',
            'model_id',
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
			<div class="favorite-panel">

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
