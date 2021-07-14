<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\SubscriptionBilling */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php    $form = TActiveForm::begin([
    
   'id' => 'subscription--billing-form',
   ]);
   echo $form->errorSummary($model);    
   ?>
                  <?php echo $form->field($model, 'subscription_id')->dropDownList($model->getSubscriptionOptions(), ['prompt' => '']) ?>
                              <?php /*echo $form->field($model, 'start_date')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => date('Y-m-d'),
            'maxDate' => date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                              <?php /*echo $form->field($model, 'end_date')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => date('Y-m-d'),
            'maxDate' => date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>                        <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                  <div
      class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'subscription--billing-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>