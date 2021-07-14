<?php
use app\assets\AppAsset;
use app\components\gdpr\Gdpr;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\FlashMessage;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1">
<meta charset="<?= Yii::$app->charset ?>" />
      <?= Html::csrfMetaTags()?>
      <title> <?= Html::encode($this->title) ?></title>
      <?php $this->head()?>
      <link rel="shortcut icon"
	href="<?= $this->theme->getUrl('img/favicon.ico')?>" type="image/png">
<!-- Plugins CSS -->
<link rel="stylesheet"
	href="<?php echo $this->theme->getUrl('css/font-awesome.css')?>">
<link
	href="<?php echo $this->theme->getUrl('css/owl.carousel.min.css')?>"
	rel="stylesheet">
<!-- Theme CSS -->
<link id="theme-style" rel="stylesheet"
	href="<?php echo $this->theme->getUrl('css/styles.css')?>">
</head>
<body class="home-page">
      <?php $this->beginBody()?>
	<!--//header-->
      <?= Gdpr::widget();?>
      
       <?= FlashMessage::widget()?> 
      <!-- body content start-->
	<div class="main_wrapper">
         <?= $content?>
      </div>
	<!--body wrapper end-->
	<div class="footer-bottom">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 text-center">
					<p class="text-white mb-0">&copy; 2016-<?php echo date('Y')?>  
                        <a href="<?= Url::home();?>"><?=Yii::$app->name?></a>
						| All Rights Reserved. Developed By <a target="_blank"
							href="<?= Yii::$app->params['companyUrl'];?>"><?= Yii::$app->params['company']?></a>
					</p>

				</div>
			</div>
		</div>
	</div>
	<!-- Javascript -->
   
     <?php $this->endBody()?>
     
   </body>
   <?php $this->endPage()?>
</html>