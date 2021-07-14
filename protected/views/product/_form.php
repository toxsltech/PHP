<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?= strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">


    <?php
    $form = TActiveForm::begin([

        'id' => 'product-form',
        'options' => [
            'class' => 'row'
        ]
    ]);

    // echo $form->errorSummary($model);
    ?>


<div class="row">


		<div class="col-md-6">

		
		 <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
	 		</div>

		<div class="col-md-6">
		<?php echo $form->field($model, 'category')->dropDownList($model->getcategory(), ['prompt' => 'Select Category']) ?>
		</div>

		<div class="col-md-6">
   
		 <?php echo $form->field($model, 'amount')->textInput(['maxlength' => 32])  ?>
	 		</div>
		<div class="col-md-6">
   
		 <?php echo $form->field($model, 'quantity')->textInput(['maxlength' => 32])  ?>
	 		</div>


		<div class="col-md-6">
   
		 <?php echo $form->field($model, 'image_file')->fileInput()  ?>
	 		</div>


		<div class="col-md-6">
   
		 <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]);  ?>
	 		</div>


		<div class="col-md-6">
   
		 <?php echo $form->field($model, 'benifits')->textInput(['maxlength' => 128])  ?>
	 		</div>



		<div class="col-md-6">
    
		 <?php echo $form->field($model, 'specification')->textInput(['maxlength' => 128])  ?>
	 		</div>


		<div class="col-md-6">
   
		 <?php echo $form->field($model, 'medical_specification')->textInput(['maxlength' => 128]) ?>
	 		</div>


		<div class="col-md-6">
   
	<?php if(User::isAdmin()){?>	 <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
	 <?php }?>		</div>


		<div class="col-md-6">
   
		 <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
	 			

	</div>

	</div>



	<div class="col-md-12 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'product-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php TActiveForm::end(); ?>
</div>