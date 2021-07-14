<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Charity */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
    //
    'id' => 'charity-form'
    
]);
echo $form->errorSummary($model);
?>
         <div class="col-md-6">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                              <?php echo $form->field($model, 'image_file',['enableAjaxValidation'=>false])->fileInput() ?>
                              <?php echo $form->field($model, 'goal_amount')->textInput(['maxlength' => 32]) ?>
                              <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); ?>
                              <?php /*echo $form->field($model, 'raised_amount')->textInput(['maxlength' => 32])*/  ?>
                     </div>
	<div class="col-md-6">
                  <?php echo $form->field($model, 'min_amount')->textInput(['maxlength' => 32]) ?>
                              <?php echo $form->field($model, 'max_amount')->textInput(['maxlength' => 32]) ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>                        <?php // echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
               </div>
	<div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'charity-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>