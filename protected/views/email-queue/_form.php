<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\EmailQueue */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php    $form = TActiveForm::begin([
   // 
   'id' => 'email-queue-form',
   'options'=>[
   'class'=>'row'
   ]
   ]);
   echo $form->errorSummary($model);    
   ?>
         <div class="col-md-6">
                  <?php /*echo $form->field($model, 'from_email')->textInput(['maxlength' => 128]) */ ?>
                              <?php /*echo $form->field($model, 'to_email')->textInput(['maxlength' => 128]) */ ?>
                              <?php /*echo  $form->field($model, 'message')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'message')->textarea(['rows' => 6]); */ ?>
                              <?php /*echo $form->field($model, 'subject')->textInput(['maxlength' => 255]) */ ?>
                              <?php /*echo $form->field($model, 'date_published')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => date('Y-m-d'),
            'maxDate' => date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                              <?php /*echo $form->field($model, 'last_attempt')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => date('Y-m-d'),
            'maxDate' => date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                              <?php /*echo $form->field($model, 'date_sent')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => date('Y-m-d'),
            'maxDate' => date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                     </div>
   <div class="col-md-6">
                  <?php /*echo $form->field($model, 'attempts')->textInput() */ ?>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>                        <?php /*echo $form->field($model, 'model_id')->dropDownList($model->getModelOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'model_type')->textInput(['maxlength' => 128]) */ ?>
                              <?php /*echo $form->field($model, 'email_account_id')->dropDownList($model->getEmailAccountOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'message_id')->dropDownList($model->getMessageOptions(), ['prompt' => '']) */ ?>
               </div>
         <div
      class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'email-queue-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>