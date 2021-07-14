<?php

use app\components\TActiveForm;
use yii\helpers\Html;
use app\models\UserDetail;
$pagename = "Login";
?>
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

					<div class=" profile-edit">
						<div class="widget">
							<div class="widget-title bg-primary">
								<h6 class="text-white mb-0">Edit Profile</h6>
							</div>
							<div class="widget-content">
            <?php
            $form = TActiveForm::begin([
                'id' => 'update-profile',
                'class' => 'signup-form'
            ]);
            ?>
                  <div class="row">
									<div class="col-md-12">
										<h3>Manage your name, ID and email address</h3>
										<hr>
									</div>
									<div class="col-md-6">
                      <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 256]) ?>
                    </div>
									<div class="col-md-6">
		 			<?php echo $form->field($model, 'email')->textInput(['disabled' => 'disabled']) ?>
                    </div>
									<div class="col-md-6">
		 			<?php echo $form->field($model, 'contact_no')->textInput() ?>
                    </div>
									<!--                     <div class="col-md-12"> -->
                      <?php /*echo $form->field($model, 'referral_code')->textInput(['maxlength' => 255]) */?>
<!--                     </div> -->
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h3>Personal Details</h3>
										<hr>
									</div>
									<div class="col-md-3">
                      <?php echo $form->field($userdetails, 'user_height')->textInput(['maxlength' => 256]) ?>
                      </div>
									<div class="col-md-3">
                      <?=$form->field($userdetails, 'user_inch')->dropDownList($userdetails->getHeightInches(), ['prompt' => '','class' => 'form-control custom-select'])->label('Height In')?>
               	      </div>
									<div class="col-md-3">
										<label>User Weight</label>
                     <?php echo $form->field($userdetails, 'weight')->textInput(['maxlength' => 256])->label(false) ?>
                     </div>
									<div class="col-md-3">
                     <?php

echo $form->field($userdetails, 'type_id')
                        ->dropDownList($userdetails->getTypeOptions(), [
                        'prompt' => '',
                        'class' => 'form-control custom-select'
                    ])
                        ->label('Weight In')?>
                    </div>
									<div class="col-md-12">
                           <?=$form->field($userdetails, 'eating_habits')->dropDownList($userdetails->gethabbits(), ['prompt' => ''])->label(true) ?>
                    </div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h3>Medical Specifications</h3>
										<hr>
									</div>
									<div class="col-md-12">
										<label for="name">Any Medical Problem </label>
                        <?= $form->field($userdetails, 'description')->textarea(['rows' => 8])->label(false);  ?>
                    </div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h3>Fruit Diet Details</h3>
										<hr>
									</div>
									<div class="col-md-12">
										<label for="name">No. of fruit intake per week </label>
                        <?=$form->field($userdetails, 'eat_count')->textInput(['maxlength' => true])->label(false)?>
                    </div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h3>Packages Categories</h3>
										<hr>
									</div>
									<div class="col-md-12">
										<label for="name">Preferred box</label>
                    <?=$form->field ( $userdetails, 'package_id')->dropDownList(UserDetail::getPackageOptions(),['prompt' => 'Select package'])->label(false)?>
                    </div>
								</div>
								<div class="col-md-12">
                  <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-primary px-5' : 'btn btn-primary px-5']) ?>
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
