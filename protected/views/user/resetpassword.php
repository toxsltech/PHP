<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

// $this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?><br>
<br>
<br>
<br>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success" >
    <?php echo Yii::$app->session->getFlash('success')?>
</div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger">
    <?php echo Yii::$app->session->getFlash('error')?>
</div>
<?php endif; ?>

<div class="container mb-100" style="margin-top:100px;">
<div class="row">
<div class="col-md-7 mx-auto">

	<div class="site-changepassword right-bar">
		
		<div class="d-flex align-items-center sign-top">
            <h4 class="ml-3"> Change Password</h4>
          </div>
		<br>
		<p class="ml-3">Please fill out the following fields to change password :</p>
		<br>
   
    <?php
    
    $form = ActiveForm::begin([
        'id' => 'changepassword-form',
        'options' => [
            'class' => 'form-horizontal'
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-12\">
                        {input}</div>\n<div class=\"col-lg-5\">
                        {error}</div>",
            'labelOptions' => [
                'class' => 'col-lg-2 control-label',
                'style'=>' '
            ]
        ]
    ]);
    ?>
				
				
         <?=$form->field ( $model, 'password', [ 'inputOptions' => [ 'placeholder' => '','class'=>'form-control w-100','style'=>'border-radius: 35px;','id'=>'password'] ] )->passwordInput ()?>
          
        <div class="clearfix">
			<div class="col-lg-offset-3 col-lg-12">
                <?=Html::submitButton ( 'Change password', [ 'class' => 'btn btn-primary' ] )?>
            </div>
		</div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
</div>