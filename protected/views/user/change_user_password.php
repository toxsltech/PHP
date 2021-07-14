<?php use app\components\TActiveForm;
use yii\helpers\Html;
?>
<?php $pagename = "Login"; ?>
<!--================Header Area =================-->
<div class="banner_area">
  <h2>My Profile</h2>
  <a href="#">Home <span>&gt; My Profile</span></a>
</div>
<section class="space-ptb bg-light rider-dashbord">
  <div class="container">
    <div class="row">
      <?php require 'user_profile.php';?>
      <div class="col-md-9">
        <div class="tab-content bg-blue border-style">

          <div class="profile-edit">
            <div class="widget">
              <div class="widget-title bg-primary">
                <h6 class="text-white mb-0">  Change Password</h6>
              </div>
              <div class="widget-content">
                <h3>Manage your security settings</h3>
                <hr>
                 <?php
                 $form = TActiveForm::begin([
                    'id' => 'changepassword-form',
                    'enableAjaxValidation' => true,
            
                    'options' => [
                        'class' => 'signup-form row'
                    ]
                ]); ?>
                  	<div class="col-md-12">
								<div class="field-holder">
									<i onclick="show('user-newpassword')" class="fa fa-eye"></i> <label
										for="Password">New Password</label>
                      <?=$form->field ( $user, 'newPassword', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Password'] )->label ( false )?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="field-holder">
									<i onclick="show('user-confirm_password')" class="fa fa-eye"></i>
									<label for="Password">Confirm Password</label>
                      <?=$form->field ( $user, 'confirm_password', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Confirm Password' ] )->label ( false )?>
								</div>
							</div>
                  <div class="col-md-12">
                    <?=Html::submitButton ( 'Submit', [ 'class' => 'btn btn-primary px-5','name' => 'changepassword-button' ] )?>
                  </div>
                <?php TActiveForm::end(); ?>
              </div>
            </div>
          </div>

              </div>
            </div>
          </div>
        </div>
      </section>
      <script type="text/javascript">

 
function show(a) {
  var x=document.getElementById(a);
  var c=x.nextElementSibling
  if (x.getAttribute('type') == "password") {
 
  c.removeAttribute("class");
  x.removeAttribute("type");
    x.setAttribute("type","text");
  } else {
  x.removeAttribute("type");
    x.setAttribute('type','password');
 c.removeAttribute("class");
  }
}
       
</script>