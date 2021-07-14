<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model app\modules\order\models\Order */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['/order']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
   <div class="card">
      <div class="order-view card-body">
         <h4 class="text-danger">Are you sure you want to delete this item? All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
         'id'	=> 'order-detail-view',
         'model' => $model,
         'options'=>['class'=>'table table-bordered'],
         'attributes' => [
                     'id',
            [
            			'attribute' => 'product_id',
            			'format'=>'raw',
            			'value' => $model->getRelatedDataLink('product_id'),
            			],
            [
            			'attribute' => 'address_id',
            			'format'=>'raw',
            			'value' => $model->getRelatedDataLink('address_id'),
            			],
            'tip',
            'is_pickup',
            'amount',
            'tax',
            'total_amount',
            'preparing_time:datetime',
            'estimated_time:datetime',
            'payment_type',
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
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
         <?php  ?>
         <?php          $form = TActiveForm::begin([
         'id'	=> 'order-form',
         'options'=>[
         'class'=>'row'
         ]
         ]);?>
         echo $form->errorSummary($model);	
         ?>         <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'order-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
   </div>
      <div class="card">
      <div class="card-body">
         <div
            class="order-panel">
            <?php
            $this->context->startPanel();
                        $this->context->addPanel('Items', 'items', 'Item',$model /*,null,true*/);
                        $this->context->addPanel('Feeds', 'feeds', 'Feed',$model /*,null,true*/);
                        $this->context->endPanel();
            ?>
         </div>
      </div>
   </div>
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>