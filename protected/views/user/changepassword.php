<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

// $this->title = 'Change Password';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Change Password'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = \yii\helpers\Inflector::camel2words(Yii::$app->controller->action->id);
?>
<div class="wrapper">
	<div class="card clearfix">
		<header class="card-header"> Please fill out the following fields to
			change password</header>
		<div class="card-body">
			<div class="site-changepassword">

    <?php

    $form = ActiveForm::begin([
        'id' => 'changepassword-form',
        'enableAjaxValidation' => true,

        'options' => [
            'class' => 'row'
        ]
    ]);
    // 'action'=>['api/user/change-password'],

    ?>
        
     <?php //echo$form->field ( $model, 'oldPassword', [ 'inputOptions' => [ 'placeholder' => '','value' => '' ] ] )->label ()->passwordInput ()?>
     <div class="col-lg-6 offset-lg-3">
            <?=$form->field ( $model, 'newPassword', [ 'inputOptions' => [ 'placeholder' => '','value' => '' ] ] )->label ()->passwordInput ()?>
                  <?=$form->field ( $model, 'confirm_password', [ 'inputOptions' => [ 'placeholder' => '' ] ] )->label ()->passwordInput ()?>
         <div class="form-group text-center">
                          <?=Html::submitButton ( 'Change password', [ 'class' => 'btn btn-success tickt-btn','name' => 'changepassword-button' ] )?>
                
                </div>
       </div>
		
    <?php ActiveForm::end(); ?>
  
			</div>

		</div>
	</div>
</div>