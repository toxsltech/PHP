<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Products'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class=" card ">

		<div class="product-view card-body">
			<?php

if (! empty($model->image_file)) {
    ?>
            <?php
    echo Html::img($model->getImageUrl(150), [
        'class' => 'img-responsive',
        'alt' => $model
    ])?>
            <?php }?>
			
			<?=  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" card ">

		<div class=" card-body ">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'product-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            /*'title',*/
            'amount',
            'quantity',
            [
                'attribute' => 'category',
                'format' => 'raw',
                'value' => $model->getcategoryid()
            ],
            'image_file',
            /*'description:html',*/
            'benifits',
            'specification',
            'medical_specification',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            'created_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
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



	<div class=" card ">
		<div class=" card-body ">
			<div class="product-panel">

<?php
$this->context->startPanel();
$this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);

$this->context->endPanel();
?>
				</div>
		</div>
	</div>

	<div class=" card ">
		<div class=" card-body ">

<?= CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>
