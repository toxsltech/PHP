<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use app\modules\subscription\models\Plan;
/* @var $this yii\web\View */
/* @var $model app\modules\subscription\models\Plan */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'plan-form'
]);
?>
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                              <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); ?>
                              <?php echo $form->field($model, 'validity')->textInput(['type' => 'number','max' => 12]) ?>
                              <?php echo $form->field($model, 'price')->textInput(['type' => 'number']) ?>
                              
                                     <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                                       <?php echo $form->field($model, 'user_id')->dropDownList($model->getUserOptions(), ['prompt' => '']) ?>
                                     <?php //echo $form->field($model, 'user_id')->dropDownList($model->getUserOptions(), ['prompt' => 'Select user','placeholder'=>'select user'])->label(false) ?>
                  <div class="col-md-6 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'plan-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>