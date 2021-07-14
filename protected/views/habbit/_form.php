<?php
use app\components\TActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Habbit */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
  		 <?php echo strtoupper(Yii::$app->controller->action->id); ?>
	</header>
<div class="card-body">
   <?php
$form = TActiveForm::begin([

    'id' => 'habbit-form',
    'options' => [
        'class' => 'row'
    ]
]);
echo $form->errorSummary($model);
?>
	<div class="col-lg-6">
      <?php echo $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>
    </div>
	<div class="col-lg-6">    
      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
    </div>
	<div class="col-md-12 text-right">
      	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'habbit-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
   <?php TActiveForm::end(); ?>
</div>
