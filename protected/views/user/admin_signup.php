 <?php
use app\components\TActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

// $this->title = 'Signup';
?>
<div class="banner_area">
	<h2>Admin Signup</h2>
</div>
<section class="login-signup py-5">
	<div class="container">
		<div class="row p-3 align-items-center justify-content-center">
			<div class="order-1 col-lg-5 order-lg-2">
				<div class="login-box">
					<br>
            	<?php

            $form = TActiveForm::begin([
                'id' => 'form-signup',
                'options' => [
                    'class' => 'form-signin'
                ]
            ]);
            ?>
                <span id="reauth-email" class="reauth-email"></span>

     <?=$form->field($model, 'full_name', ['template' => '{input}{error}'])->textInput(['maxlength' => true,'placeholder' => 'Full Name'])->label(false)?>
<?=$form->field($model, 'email', ['template' => '{input}{error}'])->textInput(['maxlength' => true,'placeholder' => 'Email'])->label(false)?>
	<?=$form->field ( $model, 'password', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Password' ] )->label ( false )?>
		<?=$form->field ( $model, 'confirm_password', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Confirm Password' ] )->label ( false )?>

     <?=Html::submitButton ( 'Signup', [ 'class' => 'btn  btn-success btn-block btn-signin','name' => 'signup-button' ] )?>

		<!-- /form -->
            	<?php TActiveForm::end(); ?>
		
		</div>
			</div>

		</div>
	</div>
</section>