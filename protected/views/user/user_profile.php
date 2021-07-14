<?php
use yii\helpers\Url;
use app\components\TActiveForm;
use yii\helpers\Html;
?>
<div class="col-md-3">
	<div class="side-tabs bg-blue border-style">
		<!-- Nav tabs -->
		<div class="p--pic pic-grid">
			<div>
			<?php

if (! empty($model->profile_file)) {
    ?>
             <?=Html::img($model->getImageUrl(), ['alt' => 'Profile Image','class' => 'img-fluid'])?> 
           <?php } else { ?>
            <img id="profile_file" class=""
					src="<?=$this->theme->getUrl('images/default.png')?>" alt="img">
            <?php } ?>
            </div>
            
            <?php
            $form = TActiveForm::begin([
                'id' => 'login-form',
                'action' => Url::toroute([
                    '/user/update-image'
                ]),
                'options' => [
                    'autocomplete' => 'off'
                ]
            ]);

            ?>
            <div class="field-choose-file">
            
               <?=$form->field($model, 'profile_file', ['options' => ['tags' => false],'template' => '{input}<label
                  for="choose-file" class="upload-file"><i class="fa fa-pencil"></i></label>{error}'])->fileInput(["id" => "choose-file","class" => "choose-file",'required' => true]);?>	
            
           
            </div>
             <?php
            TActiveForm::end()?>
            
          </div>
		<br>
		<ul class="nav nav-tabs tabs-left">
			<li><a
				class="<?= ((\Yii::$app->controller->id == 'user') && (\Yii::$app->controller->action->id == 'profile')) ? 'active' :''  ?>"
				href="<?= Url::toRoute(['user/profile'])?>"><i class="fa fa-user"></i>
					My Profile</a></li>
			<li><a
				class="<?= ((\Yii::$app->controller->id == 'user') && (\Yii::$app->controller->action->id == 'update-profile')) ? 'active' :''  ?>"
				href="<?= Url::toRoute(['user/update-profile'])?>"><i
					class="fa fa-users"></i> Edit Profile </a></li>
			<li><a
				class="<?= ((\Yii::$app->controller->id == 'user') && (\Yii::$app->controller->action->id == 'update-password')) ? 'active' :''  ?>"
				href="<?= Url::toRoute(['user/update-password'])?>"><i
					class="fa fa-lock"></i>Setting</a></li>

			<li><a
				class="<?= ((\Yii::$app->controller->id == 'user') && (\Yii::$app->controller->action->id == 'user-plan')) ? 'active' :''  ?>"
				href="<?= Url::toRoute(['user/user-plan'])?>"><i
					class="fa fa-unlock"></i> My Plan</a></li>

			<li><a
				class="<?= ((\Yii::$app->controller->id == 'user') && (\Yii::$app->controller->action->id == 'media')) ? 'active' :''  ?>"
				href="<?= Url::toRoute(['user/media'])?>"><i class="fab fa-youtube"></i>Videos</a></li>
		</ul>
	</div>
</div>
<script>
$(document).ready(function(){   
 var readURL = function(input) {
           if (input.files && input.files[0]) {
             var reader = new FileReader();
   
               reader.onload = function (e) {
               
               
                   $('.img-fluid').attr('src', e.target.result);
               }
       
               reader.readAsDataURL(input.files[0]);
               $("#login-form").submit();
           }
       }
       
   
       $(".choose-file").on('change', function(){ 
           readURL(this);
       });
       
       $(".upload-img").on('click', function() { 
       
          $(".upload").click();
       });
       });
</script>