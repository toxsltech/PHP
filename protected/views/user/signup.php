 <?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

// $this->title = 'Signup';
?>
<style>
.login-main .buy-now {
	color: #fff;
	background-color: #2da369;
	border-color: #2da369;
	padding: 12px 30px;
	font-size: 18px;
	font-weight: 500;
	min-width: 130px;
	max-width: 100%;
}
</style>
<div class="login-main">
	<div class="container-fluid p-lg-0">
		<div class="row p-lg-0">
			<div class="col-lg-4 p-0">
				<div class="left-bar">
					<h2 class="section-hd text-white mb-30">Welcome Back !</h2>
					<p>Healthy lifestyle begins with you and ends with TRACOL. Get
						started on your journey to for good . It’s TIME to start ?</p>
				</div>
			</div>

			<div class="col-lg-8 light-bg pl-lg-0">
				<div class="right-bar ">
					<div class="form-main">
          <?php
        $form = TActiveForm::begin([
            'id' => 'form-signup',
            'class' => 'signup-form row'
        ]);
        ?>
          <div class="signup-form row setup-content" id="step_1">
							<div class="col-md-12">
								<div class="d-flex align-items-center mb-50 sign-top">
									<h4>Sign Up</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="extra-login">
									<div class="login-social row m-0">
										<div class="col-md-6 p-0 pr-md-3">
											<a class="fb-login" href="<?=Url::toRoute(['social/user/auth?authclient=facebook'])?>"><i class="fa fa-facebook"></i>Sign
												up using Facebook</a>
										</div>
										<div class="col-md-6 p-0">
											<a class="ggl-login" href="<?=Url::toRoute(['social/user/auth?authclient=google'])?>"><i class="fa fa-google-plus"></i>Sign
												up using Google</a>
										</div>
									</div>
									<span>OR</span>
								</div>
							</div>

							<div class="col-md-12">

								<label for="name">Full Name</label>
                   <?=$form->field ( $model, 'full_name', [ 'template' => '{input}{error}' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Full Name' ] )->label ( false )?>
							</div>
							<div class="col-md-12">
								<label for="name">Contact No</label>
                   <?=$form->field ( $model, 'contact_no', [ 'template' => '{input}{error}' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Contact Number' ] )->label ( false )?>
							</div>
							<div class="col-md-12">
								<label for="email">Email address</label>
                    <?=$form->field ( $model, 'email', [ 'template' => '{input}{error}' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Email' ] )->label ( false )?>
							</div>
							<div class="col-md-6">
								<div class="field-holder">
									<i onclick="show('user-password')" class="fa fa-eye-slash" id="fa-eye"></i> <label
										for="Password">Password</label>
                      <?=$form->field ( $model, 'password', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Password'] )->label ( false )?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="field-holder">
									<i onclick="showConfirmPassword('user-confirm_password')" id="fa-eye-confirm" class="fa fa-eye-slash"></i>
									<label for="Password">Confirm Password</label>
                      <?=$form->field ( $model, 'confirm_password', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Confirm Password' ] )->label ( false )?>
								</div>
							</div>
							<div class="col-md-12">
								<label for="name">Referral Code</label>
                     <?=$form->field ( $model, 'referral_code', [ 'template' => '{input}{error}' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Referral Code' ] )->label ( false )?>
							</div>
							<div class="col-md-12">
								<div class="form-group mb-4 mt-2">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input"
											id="customCheck1" required checked /> <label
											class="custom-control-label" for="customCheck1" >Your personal
											data will be used to support your experience throughout this
											website, to manage access to your account, and for other
											purposes described in our <a
											href="<?=Url::toRoute(['/site/privacy'])?>"><u>Privacy Policy</u></a>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-12">
									<?=Html::submitButton('Submit', ['class' => 'btn btn-primary mb-80','id' => 'form-signup',
									    'name' => 'signup-button'])?>
							</div>
							<div class="text-center sign-form-footer">
								<p>
									Already have an account? <a
										href="<?=Url::toRoute(['/user/login'])?>"><u>Sign in here</u></a>
								</p>
							</div>
						</div>
             <?php TActiveForm::end(); ?>
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

function showConfirmPassword(a) {
  var x=document.getElementById(a);
  var c=x.nextElementSibling
  if (x.getAttribute('type') == "password") {
 
 $("#fa-eye-confirm").removeClass("fas fa-eye-slash");
  $("#fa-eye-confirm").addClass("fas fa-eye");
  
  
  x.removeAttribute("type");
    x.setAttribute("type","text");
  } else {
  x.removeAttribute("type");
    x.setAttribute('type','password');
 $("#fa-eye-confirm").removeClass("fas fa-eye");
 $("#fa-eye-confirm").addClass("fas fa-eye-slash");
 
  
  }
}
       
</script>

