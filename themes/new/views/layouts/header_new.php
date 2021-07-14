<?php
use yii\helpers\Url;
use app\models\User;

?>
<header class="main_header_area custom-header">
    <div class="header_top">
      <div class="container">
        <div class="header_top_inner">
          <p class="text-center text-white">A message to our customers about the Coronavirus. Your health and safety is always our top priority. <a href="<?=Url::toRoute(['/site/covid'])?>">Learn More.</a></p>
        </div>
      </div>
    </div>
    <div class="header_menu">
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid max-fluid">
          <a class="navbar-brand" href="<?= Url::toRoute(['/'])?>">
            <img src="<?php echo $this->theme->getUrl('images/logo.png')?>" alt="">
            <img src="<?php echo $this->theme->getUrl('images/logo.png')?>" alt="">
          </a>
          <!-- Small Divice Menu-->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_supported" aria-controls="navbar_supported" aria-expanded="false" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbar_supported">
            <ul class="navbar-nav login-butns">
            <?php if(\Yii::$app->controller->action->id == 'signup'){?>
            <li class="nav-item sign-in-butn"><a class="nav-link butn butn-border" href="<?=Url::toRoute(['/user/login'])?>"> Sign In</a></li>
            <?php } else if(\Yii::$app->controller->action->id == 'login'){?>
            <li class="nav-item sign-in-butn"><a class="nav-link butn butn-border" href="<?=Url::toRoute(['/signup'])?>">Sign Up</a></li>
            <?php }?>
          </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>