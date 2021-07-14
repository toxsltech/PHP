<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?= strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">


    <?php
    $form = TActiveForm::begin([

        'id' => 'category-form',
        'options' => [
            'class' => 'row'
        ]
    ]);

    // echo $form->errorSummary($model);
    ?>


<div class="row">


		<div class="col-md-8 offset-md-2">

	
		 <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
	 		</div>


		<div class="col-md-8 offset-md-2">
   
	<?php if(User::isAdmin()){?>	 <?php echo $form->field($model, 'state_id')->dropDownList($model->getActionOptions(), ['prompt' => 'Select Category']) ?>
	 <?php }?>		</div>


		<div class="col-md-8 offset-md-2"">
   
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => ''])  ?>
	 		</div>

	</div>



	<div class="col-md-12 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'category-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php TActiveForm::end(); ?>
</div>