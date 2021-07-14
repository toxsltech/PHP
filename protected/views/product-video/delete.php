<?php

use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\ProductVideo */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Videos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" panel ">
<div class="text-center">
		<h2>Are you sure you want to delete this item? All related data is deleted</h2></div>
		<div
			class="product-video-view panel-body">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>
	<div class=" panel ">
		<div class=" panel-body ">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'product-video-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
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
			'value' => $model->getType(),
			],
            'created_on:datetime',
            [
			'attribute' => 'created_by_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('created_by_id'),
			],
        ],
    ]) ?>


<?php  echo $model->description;?>



<?php 
$form = TActiveForm::begin([
					
						'id'	=> 'product-video-form',
						]);
						
						
echo $form->errorSummary($model);	
?>

	 <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
			
        <?= Html::submitButton('Confirm', ['id'=> 'product-video-form-submit','class' =>'btn btn-success']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

		</div>
</div>
 


	<div class=" panel ">
				<div class=" panel-body ">
					<div
						class="product-video-panel">

<?php
$this->context->startPanel();
	$this->context->addPanel('Feeds', 'feeds', 'Feed',$model /*,null,true*/);

$this->context->endPanel();
?>
				</div>
				</div>
			</div>

	<div class=" panel ">
		<div class=" panel-body ">

<?php echo CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>
