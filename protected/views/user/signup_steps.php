 <?php
use app\components\TActiveForm;
use app\models\Habbit;
use app\models\UserDetail;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

// $this->title = 'Signup';
?>

<div class="login-main">
	<div class="container-fluid p-lg-0">
		<div class="row p-lg-0">
			<div class="col-lg-4 p-0">
				<div class="left-bar">
					<h2 class="section-hd text-white mb-30">Welcome !</h2>
					<p>Healthy lifestyle begins with you and ends with TRACOL. Get
						started on your journey to for good . It’s TIME to start ?</p>
				</div>
			</div>
			<div class="col-lg-8 light-bg pl-lg-0">
				<div class="right-bar ">
					<div class="form-main">
                      <?php
                    $form = TActiveForm::begin([
                        'id' => 'form-signup'
                    ]);
                    ?>
                    <?php
                    switch (\Yii::$app->controller->action->id) {
                    case 'personal-detail':
                        {
                        ?>
                            <div class="signup-form row">
                            <div class="col-md-12">
                            <div class="signed-top mb-40">
                            <div class="sign-flow-progress mb-5">
                            <div class="progress">
                            <div class="progress-bar" style="width: 0"></div>
                            </div>
                            <small>0% complete</small>
                            </div>
                            <h4 class="mb-30">Personal Details</h4>
                            </div>
                            </div>
                            <div class="col-md-12">
                            <label for="subject">Height</label>
                            <div class="row">
                            <div class="col-lg-9 col-6">
                            <?=$form->field($model, 'user_height')->textInput(['maxlength' => true])->label(false)?>
                     					</div>
                     					<div class="col-lg-3 col-6">
                     					<?=$form->field($model, 'user_inch')->dropDownList($model->getHeightInches(),
                     					    ['prompt' => '','class' => 'form-control custom-select'])->label(false) ?>
										</div>
									</div>
							</div>
							<div class="col-md-12">
								<label for="subject">Weight</label>
    								<div class="row">
    									<div class="col-lg-9 col-6">
                         					<?=$form->field($model, 'weight')->textInput(['maxlength' => true])->label(false)?>
                         				</div>
                         				<div class="col-lg-3 col-6">
                         				<?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), 
                         				    ['prompt' => '','class' => 'form-control custom-select'])->label(false) ?>
    									</div>
    								</div>
								</div>
							<div class="col-md-12">
							<div class="form-group">
								<label for="subject">Eating Habits Per Week</label>
     <?=$form->field($model, 'eating_habits')->dropDownList($model->gethabbits(), ['prompt' => ''])->label(false) ?>
     						</div> 
								</div>
							<div class="col-md-12">
							 <?=Html::submitButton('Submit',['class' => 'btn btn-primary mb-80 float-right' ,
							     'id' => 'form-signup' ,'name' => 'signup-button'])?>
							</div>
						</div>
						<?php 
                        }
                        break;
                    case 'medical-specification':
                        {
                            ?>
                            <div class="signup-form row">
							<div class="col-md-12">
								<div class="signed-top mb-40">
									<div class="sign-flow-progress mb-5">
										<div class="progress">
											<div class="progress-bar" style="width: 20%"></div>
										</div>
										<small>20% complete</small>
									</div>
									<h4 class="mb-30">Medical Specifications</h4>
								</div>
							</div>
							<div class="col-md-12">
								<label for="subject">Any Medical Problem </label>
                    	<?= $form->field($model, 'description')->textarea(['rows' => 8])->label(false);  ?>
							</div>
							<div class="col-md-12">
							<a href="<?=Url::toRoute(['user/personal-detail'])?>" 
							class="previous butn butn-border"
												id="preview_button"><span>Back</span></a>
								 <?=Html::submitButton('Next',['class' => 'btn btn-primary mb-80 float-right' ,
							     'id' => 'form-signup' ,'name' => 'signup-button'])?>
							</div>
						</div>
                            <?php 
                        }
                        break;
                    case 'diet-plan':
                        {
                            ?>
                          <div class="signup-form row setup-content">
							<div class="col-md-12">
								<div class="signed-top mb-40">
									<div class="sign-flow-progress mb-5">
										<div class="progress">
											<div class="progress-bar" style="width: 40%"></div>
										</div>
										<small>40% complete</small>
									</div>
									<h4 class="mb-30">Fruit Diet Details</h4>
								</div>
							</div>
							<div class="col-md-12">
								<label for="subject">No. of fruit intake per week </label>
                			  		<?=$form->field($model, 'eat_count')->textInput(['maxlength' => true])->label(false)?>
							</div>
							<div class="col-md-12">
							<a href="<?=Url::toRoute(['user/medical-specification'])?>" 
							class="previous butn butn-border"
												id="preview_button"><span>Back</span></a>
									<?=Html::submitButton('Next',['class' => 'btn btn-primary mb-80 float-right' ,
							     'id' => 'form-signup' ,'name' => 'signup-button'])?>
							</div>
						</div>
                            <?php
                        }
                        break;
                     case 'advice':
                        {
                            ?>
                          <div class="signup-form row setup-content">
							<div class="col-md-12">
								<div class="signed-top mb-20">
									<div class="sign-flow-progress mb-5">
										<div class="progress">
											<div class="progress-bar" style="width: 60%"></div>
										</div>
										<small>60% complete</small>
									</div>
									<h4 class="mb-30">Nutrition Advice</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="bg-light-bule profile-tips mb-40">
								<?= HtmlPurifier::process(!empty($nutrition->title)?$nutrition->title:" ");?>
									<ul>
									<?= HtmlPurifier::process(!empty($nutrition->description)?$nutrition->description:"Not Set");?>
									</ul>
								</div>
							</div>
							<div class="col-md-12">
								<a href="<?=Url::toRoute(['user/diet-plan'])?>" class="previous butn butn-border">
								<span>Back</span></a>
								<a href="<?=Url::toRoute(['user/prefferred-package'])?>" class="next btn btn-primary mb-80 float-right">
								<span>Next</span></a>
							</div>
						</div>
                            <?php
                        }
                        break;
                    case 'prefferred-package':
                        {
                            ?>
						<div class="signup-form row setup-content">
							<div class="col-md-12">
								<div class="signed-top mb-40">
									<div class="sign-flow-progress mb-5">
										<div class="progress">
											<div class="progress-bar" style="width: 70%"></div>
										</div>
										<small>70% complete</small>
									</div>
									<h4 class="mb-30">Preferred Packages Categories</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="custom-control custom-radio">
							
                            <?php echo $form->field($model,'package_id')->radioList($packageCategory)
                            ->label(false);?>
								</div>
							</div>
							<div class="col-md-12 mt-30">
								<a href="<?=Url::toRoute(['user/advice'])?>" class="previous butn butn-border"><span>Back</span></a>
									<?=Html::submitButton('Next',['class' => 'btn btn-primary mb-80 float-right' ,
							     'id' => 'form-signup' ,'name' => 'signup-button'])?>
							</div>
						</div>                            <?php
                        }
                        break;
                    default:
                        {
                            ?>
                            <div class="signup-form row">
                            <div class="col-md-12">
                            <div class="signed-top mb-40">
                            <div class="sign-flow-progress mb-5">
                            <div class="progress">
                            <div class="progress-bar" style="width: 0"></div>
                            </div>
                            <small>0% complete</small>
                            </div>
                            <h4 class="mb-30">Personal Details</h4>
                            </div>
                            </div>
                            <div class="col-md-12">
                            <label for="subject">Height</label>
                            <div class="row">
                            <div class="col-lg-9 col-6">section
                            <?=$form->field($model, 'height')->textInput(['maxlength' => true])->label(false)?>
                     					</div>
                     					<div class="col-lg-3 col-6">
                     					<?=$form->field($model, 'inch')->dropDownList($model->getHeightInches(),
                     					    ['prompt' => '','class' => 'form-control custom-select']) ?>
										</div>
									</div>
							</div>
							<div class="col-md-12">
								<label for="subject">Weight</label>
    								<div class="row">
    									<div class="col-lg-9 col-6">
                         					<?=$form->field($model, 'weight')->textInput(['maxlength' => true])->label(false)?>
                         				</div>
                         				<div class="col-lg-3 col-6">
                         				<?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), 
                         				    ['prompt' => '','class' => 'form-control custom-select']) ?>
    									</div>
    								</div>
								</div>
							<div class="col-md-12">
								<label for="subject">Eating Habits </label>
                    			 <?=$form->field($model, 'eating_habits')->textInput(['maxlength' => true])->label(false)?>
							</div>
							<div class="col-md-12">
							 <?=Html::submitButton('Submit',['class' => 'btn btn-primary mb-80 float-right' ,
							     'id' => 'form-signup' ,'name' => 'signup-button'])?>
							</div>
						</div>
						<?php 
                        }
                        }
                         ?>
           			    <?php TActiveForm::end(); ?>
          			</div>
				</div>
			</div>
		</div>
	</div>
</div>
