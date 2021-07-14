<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\bootstrap\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Charity */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Charities'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class=" card ">

		<div class="charity-view card-body">
		<?=  \app\components\PageHeader::widget(['model'=>$model]); ?>
			<?php

if (! empty($model->image_file)) {
    ?>
            <?php
    echo Html::img($model->getImageUrl(150), [
        'class' => 'img-responsive',
        'alt' => $model
    ])?>
            <?php }?>
			
			



		</div>
	</div>

	<div class=" card ">

		<div class=" card-body ">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'charity-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            /*'title',*/
            'goal_amount',
            /*'description:html',*/
            'raised_amount',
            'min_amount',
            'max_amount',
            [
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],
           /* [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],*/
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
	<div class=" card ">
		<div class=" card-body ">
			<div class="charity-panel">

<?php
$this->context->startPanel();
$this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);

$this->context->endPanel();
?>
				</div>
		</div>
	</div>
</div>