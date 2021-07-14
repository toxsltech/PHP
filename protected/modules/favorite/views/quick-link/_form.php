<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\favorite\models\QuickLink */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
          <?php echo strtoupper(Yii::$app->controller->action->id); ?>
  </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'quick-link-form',
        'options' => [
            'class' => 'row'
        ]
    ]);

    ?>
	<div class="col-md-6">
	<?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
<?php echo $form->field($model, 'url')->textInput(['maxlength' => 256]) ?>
</div>
	<div class="col-md-6">
	 <?php echo $form->field($model, 'explore_option')->dropDownList($model->getExploreOptions(), ['prompt' => '']) ?>
<?php if(User::isAdmin()){?>	 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 <?php }?>	
</div>

	<div class="form-group text-right col-md-12">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'quick-link-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


 <?php TActiveForm::end(); ?>

</div>
