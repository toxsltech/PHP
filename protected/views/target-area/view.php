<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\TargetArea */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Target Areas'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card mb-4">
  
        <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
</div>
	<div class="content-section clearfix">
		<div class="widget light-widget">
			<div class="user-view">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-2">
               <?php

            if (! empty($model->target_file)) {
                ?>
            <?php
                echo Html::img($model->getImageUrl(150), [
                    'class' => 'img-responsive',
                    'alt' => $model
                ])?>
            <?php }?>
         </div>
							<div class="col-md-10">     
                 <?php

                echo \app\components\TDetailView::widget([
                    'id' => 'target-area-detail-view',
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
               <?php
            $this->context->startPanel();
            $this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
				</div>
     
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
        
   </div>
		</div>
	</div>
</div>

