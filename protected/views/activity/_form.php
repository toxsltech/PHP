<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Activity */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>



<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'activity-form',
    'options' => [
        'class' => 'row'
    ]
]);
?>
<div class="col-lg-6">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
                  <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]);  ?>
                </div>

	<div class="col-lg-6">
                              <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */?>
                               <?php echo $form->field($model, 'domain_id')->label('Domain')->dropDownList($model->getDomainOptions(), ['prompt' => '']) ?>
                        <?php if(User::isAdmin()){?>   
                           <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
              <?php }?>                
       
        </div>
	<div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'domain-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>
