<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

// $this->title = 'Sign In';
?>
 <?php

$fieldOptions1 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],

    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<?php

if (Yii::$app->session->hasFlash('success')) :
    ?>
<div class="alert alert-success">
    <?php

    echo Yii::$app->session->getFlash('success')?>
</div>

<?php endif;

?>

<div class="login-main">
  <div class="container-fluid p-lg-0">
    <div class="row p-lg-0">
      <div class="col-lg-4 p-0">
        <div class="left-bar">
          <h2 class="section-hd text-white mb-30">Welcome Back !</h2>
          <p>Healthy lifestyle begins with you and ends with TRACOL. Get started on your journey to for good . It’s TIME to start ?</p>
        </div>
      </div>
      <div class="col-lg-8 light-bg pl-lg-0">
        <div class="right-bar">
          <div class="d-flex align-items-center mb-50 sign-top">
            <h4>Sign In</h4>
          </div>
          <div class="extra-login">
            <div class="login-social row m-0">
              <div class="col-md-6 p-0 pr-md-3">
                <a class="fb-login" href="<?=Url::toRoute(['social/user/auth?authclient=facebook'])?>"><i class="fab fa-facebook-f"></i>Sign up using Facebook</a>
              </div>
              <div class="col-md-6 p-0">
                <a class="ggl-login" href="<?=Url::toRoute(['social/user/auth?authclient=google'])?>"><i class="fab fa-google"></i>Sign up using Google</a>
              </div>
            </div>
            <span>OR</span>
          </div>
          <div class="form-main">
              <?php
            $form = TActiveForm::begin([
                'id' => 'loggin-form',
                'options' => [
                    'class' => 'sign-in-form row'
                ]
            ]);
            ?>
                <div class="col-md-12 mb-2">                
                 <?= $form->field ( $model, 'username', $fieldOptions1 )->label ( true )->textInput ( [ 'placeholder' => $model->getAttributeLabel ( 'email' )] )?>
              </div>
              <div class="col-md-12 mb-2">
                <div class="field-holder">
                  <i onclick="show('loginform-password')" class="fa fa-eye-slash" id="fa-eye"></i>
                  <?= $form->field ( $model, 'password', $fieldOptions2 )->label ( true )->passwordInput ( [ 'placeholder' => $model->getAttributeLabel ( 'password' )] )?>
                </div>
              </div>
              <div class="col-md-12">
                 <?=Html::submitButton ( 'Sign In', [ 'class' => 'btn btn-primary mb-80','id' => 'login','name' => 'loggin-button' ] )?>
                 <a class="btn btn-primary ml-3 mb-80" href="<?=Url::toRoute(['user/recover'])?>"><span>Forgot Password</span></a>
              </div>
              <?php TActiveForm::end()?>
            <div class="text-center sign-form-footer">
              <p>Don't have an account, <a href="<?= Url::toRoute(['user/signup']) ?>"><u>Click here to Sign up</u></a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function show(a) {
  var x=document.getElementById(a);
  var c=x.nextElementSibling
  if (x.getAttribute('type') == "password") {
 
 	$("#fa-eye").removeClass("fas fa-eye-slash");
  $("#fa-eye").addClass("fas fa-eye");
  
  
  x.removeAttribute("type");
    x.setAttribute("type","text");
  } else {
  x.removeAttribute("type");
    x.setAttribute('type','password');
 	$("#fa-eye").removeClass("fas fa-eye");
 $("#fa-eye").addClass("fas fa-eye-slash");
 
  
  }
}
</script>
