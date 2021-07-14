<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\EmailQueue */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Queues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
   <div class="card">
      <div class="email-queue-view card-body">
         <h4 class="text-danger">Are you sure you want to delete this item? All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
         'id'	=> 'email-queue-detail-view',
         'model' => $model,
         'options'=>['class'=>'table table-bordered'],
         'attributes' => [
                     'id',
            'from_email:email',
            'to_email:email',
            'message:html',
            'subject',
            'date_published:datetime',
            'last_attempt:datetime',
            'date_sent:datetime',
            'attempts',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'model_id',
            'model_type',
            'email_account_id:email',
            'message_id',
         ],
         ]) ?>
         <?php  ?>
         <?php          $form = TActiveForm::begin([
         'id'	=> 'email-queue-form',
         'options'=>[
         'class'=>'row'
         ]
         ]);?>
         echo $form->errorSummary($model);	
         ?>         <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'email-queue-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
   </div>
      <div class="card">
      <div class="card-body">
         <div
            class="email-queue-panel">
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