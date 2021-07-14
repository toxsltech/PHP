<?php
use app\components\TActiveForm;
use yii\helpers\Html;

// $this->title = 'Change Password';
$this->params['breadcrumbs'][] = \yii\helpers\Inflector::camel2words(Yii::$app->controller->action->id);
?>
<div class="wrapper">
	<div class="card clearfix">
		<header class="card-header"> Please fill out the following fields to
			change password :</header>
		<div class="card-body">
			<div class="site-changepassword">

    <?php

    $form = TActiveForm::begin([
        'id' => 'changepassword-form',
        'options' => [
            'class' => 'row'
        ]
    ]);

    ?>
     <div class="col-lg-6 offset-lg-3">
           <?= $form->field ( $model, 'oldPassword', [ 'inputOptions' => [ 'placeholder' => '','value' => '' ] ] )->label ()->passwordInput ()?>
            	<?=$form->field ( $model, 'newPassword', [ 'inputOptions' => [ 'placeholder' => '','value' => '' ] ] )->label ()->passwordInput ()?>
        		<?=$form->field ( $model, 'confirm_password', [ 'inputOptions' => [ 'placeholder' => '' ] ] )->label ()->passwordInput ()?>
         <div class="form-group text-center">
                 <?=Html::submitButton ( 'Change password', [ 'class' => 'btn common-btn tickt-btn','name' => 'changepassword-button' ] )?>
                
                </div>
				</div>
		
    <?php TActiveForm::end(); ?>
  </div>

		</div>
	</div>
</div>