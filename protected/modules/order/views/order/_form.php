<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\order\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php    $form = TActiveForm::begin([
   // 
   'id' => 'order-form',
   ]);
   echo $form->errorSummary($model);    
   ?>
         <div class="col-md-6">
                  <?php echo $form->field($model, 'product_id')->dropDownList($model->getProductOptions(), ['prompt' => '']) ?>
                              <?php echo $form->field($model, 'address_id')->textInput(['maxlength' => 32]) ?>
                              <?php /*echo $form->field($model, 'tip')->textInput() */ ?>
                              <?php /*echo $form->field($model, 'is_pickup')->textInput() */ ?>
                              <?php echo $form->field($model, 'amount')->textInput(['maxlength' => 32]) ?>
                              <?php /*echo $form->field($model, 'tax')->textInput(['maxlength' => 32]) */ ?>
                     </div>
   <div class="col-md-6">
                  <?php /*echo $form->field($model, 'total_amount')->textInput(['maxlength' => 32]) */ ?>
                              <?php /*echo $form->field($model, 'preparing_time')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => date('Y-m-d'),
            'maxDate' => date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                              <?php /*echo $form->field($model, 'estimated_time')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => date('Y-m-d'),
            'maxDate' => date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                              <?php /*echo $form->field($model, 'payment_type')->textInput() */ ?>
                              <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>         </div>
         <div
      class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'order-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>