<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\models\CharityDetail */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Charity Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
   <div class="card">
      <div class="charity-detail-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
         'id'	=> 'charity-detail-detail-view',
         'model' => $model,
         'options'=>['class'=>'table table-bordered'],
         'attributes' => [
                     'id',
            'amount',
            [
            			'attribute' => 'charity_id',
            			'format'=>'raw',
            			'value' => $model->getRelatedDataLink('charity_id'),
            			],
            'user_id',
             [
                 'label' => 'Payment Status',
                 'format'=>'raw',
                 'value' => $model->getPaymentStatusBadge()],
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
//             [
// 			'attribute' => 'type_id',
// 			'value' => $model->getType(),
// 			],
            'created_on:datetime',
            [
            			'attribute' => 'created_by_id',
            			'format'=>'raw',
            			'value' => $model->getRelatedDataLink('created_by_id'),
            			],
         ],
         ]) ?>
         <?php  ?>
         <?php         echo UserAction::widget ( [
         'model' => $model,
         'attribute' => 'state_id',
         'states' => $model->getStateOptions ()
         ] );
         ?>
      </div>
   </div>
      <div class="card">
      <div class="card-body">
         <div
            class="charity-detail-panel">
            <?php
            $this->context->startPanel();
                        $this->context->addPanel('Feeds', 'feeds', 'Feed',$model /*,null,true*/);
                        $this->context->endPanel();
            ?>
         </div>
      </div>
   </div>
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>