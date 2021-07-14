<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\ProductVideo */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Product Videos'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class=" card ">
		<div class="product-video-view card-body">
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
        'id' => 'product-video-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            /*'title',*/
            /*'description:html',*/
            'video_file',
            'youtub_link',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
                'attribute' => 'type_id',
                'format' => 'raw',
                'value' => $model->getTypeBadge()
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
$filepath = $model->getVideoUrl(100);

if ($model->video_file) {
    ?>

<video width="30%" height="300px" controls>
				<source src="<?= $filepath ?>" type="video/mp4">
			</video>

<?php }?>

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

<?= CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>
