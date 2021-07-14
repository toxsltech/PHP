<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
use kartik\rating\StarRating;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
    <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'user-form',
        'options' => [
            'class' => 'row'
        ]
    ]);
    ?>

<div class="col-lg-6">
			
		 <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 256]) ?>

		 <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255 , 'disabled' => true]) ?>
		<?php //echo $form->field($model, 'language')->textInput(['maxlength' => 255]) ?>
		 <?php //echo $form->field($model, 'password')->passwordInput(['maxlength' => 128]) ?>

	 
		 <?php
// echo $form->field($model, 'date_of_birth')->widget(yii\jui\DatePicker::class,
([
    // 'dateFormat' => 'php:Y-m-d',
    'options' => [
        'class' => 'form-control'
    ],
    'clientOptions' => [
        // 'minDate' => 0,
        'changeMonth' => true,
        'changeYear' => true
    ]
])?>

</div>
	<div class="col-lg-6">

		 
		 <?php if ( ! User::isManager()){?>
	 		
	 	<?php echo $form->field($model, 'role_id')->dropDownList($model->getRoleOptions(), ['prompt' => '']) ?>
		    
		<?php }?>
		 
 		<?php echo $form->field($model, 'profile_file')->fileInput(['accept' => 'images/.png, ,jpg, .jpeg','onchange' => 'ValidateCoverInput(this)']) ?>
	 	<?php

/*echo $form->field($model, 'rating')->widget(StarRating::classname(), [
    'pluginOptions' => [
        'size' => 'sm',
        'stars' => 5,
        'min' => 0,
        'max' => 5,
        'step' => 0.2
    ]
]);*/
?>
    
	     	
		    <?php //echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 	 <?php
    // echo $form->field($model, 'last_visit_time')->widget(yii\jui\DatePicker::class,
    [
        // 'dateFormat' => 'php:Y-m-d',
        'options' => [
            'class' => 'form-control'
        ],
        'clientOptions' => [
            // 'minDate' => 0,
            'changeMonth' => true,
            'changeYear' => true
        ]
    ]?>

	 		

		
		 <?php
// echo $form->field($model, 'last_action_time')->widget(yii\jui\DatePicker::class,
[
    // 'dateFormat' => 'php:Y-m-d',
    'options' => [
        'class' => 'form-control'
    ],
    'clientOptions' => [
        // 'minDate' => 0,
        'changeMonth' => true,
        'changeYear' => true
    ]
]?>

	 		

		
		 <?php
// echo $form->field($model, 'last_password_change')->widget(yii\jui\DatePicker::class,
[
    // 'dateFormat' => 'php:Y-m-d',
    'options' => [
        'class' => 'form-control'
    ],
    'clientOptions' => [
        // 'minDate' => 0,
        'changeMonth' => true,
        'changeYear' => true
    ]
]?>

       <?php //echo $form->field($model, 'login_error_count')->textInput() ?>

	 		

		
		 <?php //echo $form->field($model, 'activation_key')->textInput(['maxlength' => 128]) ?>

	 		

		
		 <?php //echo $form->field($model, 'timezone')->textInput(['maxlength' => 255]) ?>

	 			</div>




	<div class="form-group col-lg-12 text-right">
	
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	

    <?php TActiveForm::end(); ?>

</div>
<script>
var _validFileExtensions = [".jpg", ".jpeg", ".png"];    
function ValidateCoverInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
              alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                 $('#logo-form').yiiActiveForm('validateAttribute', 'user-profile_file')
                return false;
            }
        }
    }
    return true;
}


</script>
