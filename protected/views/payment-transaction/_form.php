<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\PaymentTransaction */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php    $form = TActiveForm::begin([
   // 
   'id' => 'payment-transaction-form',
   ]);
   echo $form->errorSummary($model);    
   ?>
         <div class="col-md-6">
                  <?php /*echo $form->field($model, 'name')->textInput(['maxlength' => 255]) */ ?>
                              <?php /*echo $form->field($model, 'email')->textInput(['maxlength' => 255]) */ ?>
                              <?php /*echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); */ ?>
                              <?php /*echo $form->field($model, 'model_id')->dropDownList($model->getModelOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'model_type')->textInput(['maxlength' => 255]) */ ?>
                              <?php /*echo $form->field($model, 'amount')->textInput(['maxlength' => 255]) */ ?>
                     </div>
   <div class="col-md-6">
                  <?php echo $form->field($model, 'currency')->textInput(['maxlength' => 125]) ?>
                              <?php /*echo $form->field($model, 'transaction_id')->dropDownList($model->getTransactionOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'payer_id')->dropDownList($model->getPayerOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo  $form->field($model, 'value')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'value')->textarea(['rows' => 6]); */ ?>
                              <?php /*echo $form->field($model, 'gateway_type')->textInput() */ ?>
                              <?php /*echo $form->field($model, 'payment_status')->textInput() */ ?>
               </div>
         <div
      class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'payment-transaction-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>