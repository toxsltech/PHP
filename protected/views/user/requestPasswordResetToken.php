<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->params['breadcrumbs'][] = [
    'label' => 'Users',
    'url' => [
        'user/index'
    ]
];

$this->params['breadcrumbs'][] = Inflector::humanize(Yii::$app->controller->action->id);

?>
<div class="login-wrapper">
	<div class="overlay-itembox">
		<div class="py-5 form-inner-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-7 col-lg-5 mx-auto ">
						<div class="contact-form-bg login-signup w-100 bg-white">


							<h3 class="section-title title mb-3">Reset Password</h3>
							<p class="text-center">Please fill out your email. A link to
								reset password will be sent there.</p>
                   		<?php

                    $form = TActiveForm::begin([
                        'id' => 'request-password-reset-form',
                        'enableClientValidation' => true,
                        'enableAjaxValidation' => false
                    ]);
                    ?>
            
                <?= $form->field($model, 'email') ?>
                <div class="form-group">
                    <?= Html::submitButton('Send', ['class' => 'btn common-btn btn-block btn-signin mt-4 mt-md-0','name' => 'send-button']) ?>
                </div>
           			<?php TActiveForm::end(); ?> 
                                    
               </div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>


