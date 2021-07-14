<?php
use yii\helpers\Url;
use app\models\UserDetail;
use yii\bootstrap\Html;

?>
<?php if(\Yii::$app->controller->action->id == 'login' || \Yii::$app->controller->action->id == 'signup'){ ?>
<header class="main_header_area custom-header">
	<div class="header_top">
		<div class="container">
			<div class="header_top_inner">
				<p class="text-center text-white">
					A message to our customers about the Coronavirus. Your health and
					safety is always our top priority. <a
						href="<?=Url::toRoute(['/site/covid'])?>">Learn More.</a>
				</p>
			</div>
		</div>
	</div>
	<div class="header_menu">
		<nav class="navbar navbar-expand-lg">
			<div class="container-fluid max-fluid">
				<a class="navbar-brand" href="<?= Url::toRoute(['/'])?>"> <img
					src="<?php echo $this->theme->getUrl('images/logo.png')?>" alt="">
					<img src="<?php echo $this->theme->getUrl('images/logo.png')?>"
					alt="">
				</a>
				<!-- Small Divice Menu-->
				<button class="navbar-toggler" type="button" data-toggle="collapse"
					data-target="#navbar_supported" aria-controls="navbar_supported"
					aria-expanded="false" aria-label="Toggle navigation">
					<span></span> <span></span> <span></span> <span></span>
				</button>
				<div class="collapse navbar-collapse justify-content-end"
					id="navbar_supported">
					<ul class="navbar-nav login-butns">
            <?php if(\Yii::$app->controller->action->id == 'signup'){?>
            <li class="nav-item sign-in-butn"><a
							class="nav-link butn butn-border"
							href="<?=Url::toRoute(['/user/login'])?>"> Sign In</a></li>
            <?php } else if(\Yii::$app->controller->action->id == 'login'){?>
            <li class="nav-item sign-in-butn"><a
							class="nav-link butn butn-border"
							href="<?=Url::toRoute(['/signup'])?>">Sign Up</a></li>
            <?php }?>
          </ul>
				</div>
			</div>
		</nav>
	</div>
</header>
<?php }else {?>
<header class="main_header_area">
	<div class="header_top">
		<div class="container">
			<div class="header_top_inner">
				<p class="text-center text-white">
					A message to our customers about the Coronavirus. Your health and
					safety is always our top priority. <a
						href="<?=Url::toRoute(['/site/covid'])?>">Learn More.</a>
				</p>
			</div>
		</div>
	</div>
	<div class="header_menu">
		<nav class="navbar navbar-expand-lg">
			<div class="container max-container">
				<a class="navbar-brand" href="<?= Url::toRoute(['/'])?>"> <img
					src="<?php echo $this->theme->getUrl('images/logo1.png')?>" alt="">
					<img src="<?php echo $this->theme->getUrl('images/logo1.png')?>"
					alt="">
				</a>
				<!-- Small Divice Menu-->
				<button class="navbar-toggler" type="button" data-toggle="collapse"
					data-target="#navbar_supported" aria-controls="navbar_supported"
					aria-expanded="false" aria-label="Toggle navigation">
					<span></span> <span></span> <span></span> <span></span>
				</button>
				<div class="collapse navbar-collapse justify-content-end"
					id="navbar_supported">
					<ul class="navbar-nav custom-nav">
             <?php if (Yii::$app->user->isGuest) {?>
            <li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'index')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/'])?>">TRACOL Connect</a></li>
						<li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'tracol-fruits')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/site/tracol-fruits'])?>">TRACOL Fruits</a></li>
						<li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'charity')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/site/charity'])?>">TRACOL Amanah</a></li>
            <?php } else {?>
             <li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'index')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/'])?>">TRACOL Connect</a></li>
						<li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'tracol-fruits')) ? 'active' :''  ?>"
							href='<?=Url::toRoute(['/site/tracol-fruits'])?>'>TRACOL Fruits</a></li>
													<li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'charity')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/site/charity'])?>">TRACOL Amanah</a></li>
             <?php }?>
            <li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'about')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/site/about'])?>">About Us</a></li>
						<li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'bonanza')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/site/bonanza'])?>">Bonanza</a></li>
						<li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'our-team')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/site/our-team'])?>">Our Team</a></li>
						<li><a
							class="<?= ((\Yii::$app->controller->id == 'site') && (\Yii::$app->controller->action->id == 'contact')) ? 'active' :''  ?>"
							href="<?=Url::toRoute(['/site/contact'])?>">Contact Us</a></li>
					</ul>
					<ul class="navbar-nav login-butns">
             <?php if (Yii::$app->user->isGuest) { ?>
            <li class="nav-item sign-in-butn"><a
							class="nav-link butn butn-border"
							href="<?=Url::toRoute(['/user/login'])?>"> Sign In</a></li>
						<li class="nav-item sign-in-butn"><a
							class="nav-link butn butn-border"
							href="<?=Url::toRoute(['/user/signup'])?>">Sign Up</a></li>
            <?php
    } else {
        $userdetail = UserDetail::find()->where([
            'user_id' => Yii::$app->user->id
        ])->one();
        if (! empty($userdetail)) {
            ?>
            <li class="dropdown"><a class="nav-link dropdown-toggle"
							href="#" role="button" data-toggle="dropdown"
							aria-expanded="false">
							  <?php

            $model = Yii::$app->user->identity;

            if (! empty($model->profile_file)) {
                ?>
							<?php
                echo Html::img($model->getImageUrl(), [
                    'class' => 'img-responsive',
                    'alt' => $model,
                    'thumbnail' => 15
                ])?>
                 <?php
            } else {
                ?>
            <img id="profile_file" class=""
								src="<?=$this->theme->getUrl('img/default.jpg')?>" alt="img">
            <?php } ?></a>
            <?php
        } else {
            $userdetail = new UserDetail();
            ?>
                        
						
						<li class="dropdown"><a class="nav-link dropdown-toggle" href="#"
							role="button" data-toggle="dropdown" aria-expanded="false"></a>
							  <?php

            $model = Yii::$app->user->identity;

            if (! empty($model->profile_file)) {
                ?>
							<?php
                echo Html::img($model->getImageUrl(), [
                    'class' => 'img-responsive',
                    'alt' => $model,
                    'thumbnail' => 15
                ])?>
            <?php }?>
            <?php }?>

									<ul class="dropdown-menu">
								<li><a href="<?=Url::toRoute(['/user/profile'])?>">My Profile</a></li>
								<li><a href="<?= Url::toRoute(['/user/logout']) ?>">Logout</a></li>
							</ul></li>




					</ul>
            <?php }?>
            
					
					</ul>
				</div>
			</div>
		</nav>
	</div>
</header>
<?php }?>