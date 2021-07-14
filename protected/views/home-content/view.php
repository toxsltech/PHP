<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\HomeContent */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Home Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
		<div class=" card ">

		<div
			class="product-view card-body">
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
	
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
         'id'	=> 'home-content-detail-view',
         'model' => $model,
         'options'=>['class'=>'table table-bordered'],
         'attributes' => [
                     'id',
            /*'title',*/
            /*'description:html',*/
            'image',
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
         <?php         echo UserAction::widget ( [
         'model' => $model,
         'attribute' => 'state_id',
         'states' => $model->getStateOptions ()
         ] );
         ?>
      </div>
   </div>
     </div>