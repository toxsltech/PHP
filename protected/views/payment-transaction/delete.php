<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\PaymentTransaction */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
   <div class="card">
      <div class="payment-transaction-view card-body">
         <h4 class="text-danger">Are you sure you want to delete this item? All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
         'id'	=> 'payment-transaction-detail-view',
         'model' => $model,
         'options'=>['class'=>'table table-bordered'],
         'attributes' => [
                     'id',
            'name',
            'email:email',
            /*'description:html',*/
            'model_id',
            'model_type',
            'amount',
            'currency',
            'transaction_id',
            'payer_id',
            'value:html',
            'gateway_type',
            'payment_status',
            'created_on:datetime',
         ],
         ]) ?>
         <?php  echo $model->description;?>
         <?php          $form = TActiveForm::begin([
         'id'	=> 'payment-transaction-form',
         'options'=>[
         'class'=>'row'
         ]
         ]);?>
         echo $form->errorSummary($model);	
         ?>         <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'payment-transaction-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
   </div>
      <div class="card">
      <div class="card-body">
         <div
            class="payment-transaction-panel">
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