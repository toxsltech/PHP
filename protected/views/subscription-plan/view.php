<?php

use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\SubscriptionPlan */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" card ">

		<div
			class="subscription-plan-view card-body">
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
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'subscription-plan-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
        'attributes' => [
            'id',
            'image_file',
            /*'title',*/
            /*'description:html',*/
            'validity',
            'price',
            'total_delivered',
            'no_of_address',
           'no_of_contest_ticket',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'created_on:datetime',
            [
			'attribute' => 'created_by_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('created_by_id'),
			],
        ],
    ]) ?>


<?php  echo $model->description;?>


		<?php				echo UserAction::widget ( [
						'model' => $model,
						'attribute' => 'state_id',
						'states' => $model->getStateOptions ()
				] );
				?>

		</div>
</div>
 


	<div class=" card ">
				<div class=" card-body ">
					<div
						class="subscription-plan-panel">

<?php
$this->context->startPanel();
	$this->context->addPanel('Feeds', 'feeds', 'Feed',$model /*,null,true*/);

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