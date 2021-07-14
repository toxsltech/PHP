<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

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
			
		 <?php
if (! User::isSubAdmin()) {
    echo $form->field($model, 'email')->textInput([
        'maxlength' => 255
    ]);
}
?>
		<?php if(!strpos(Url::current(),'update')){?>
		 <?php echo $form->field($model, 'password')->passwordInput(($model->isNewRecord)?array('maxlength'=>255,'required'=>'required'):array('maxlength'=>255,'readOnly'=>'readOnly')) ?>
		 <?php }?>
		 <?php if(strpos(Url::current(),'update')){?>
		 <?php echo $form->field($model, 'contact_no')->textInput(['maxlength' => 255]) ?>
		 <?php }?>

		<?php echo $form->field($model, 'profile_file')->fileInput() ?>
	 
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
