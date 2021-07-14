<?php
use app\assets\AppAsset;
use app\components\gdpr\Gdpr;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

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
      <title> <?= Html::encode('Medical Specification') ?></title>
      <?php $this->head()?>
      <link rel="shortcut icon"
	href="<?php $this->theme->getUrl('images/logo1.png')?>" type="image/png">
<!-- Plugins CSS -->
<link rel="stylesheet"
	href="<?php echo $this->theme->getUrl('css/font-awesome.css')?>">

<!-- Theme CSS -->

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tracol</title>

<link
	href="<?php echo $this->theme->getUrl('vendors/owl-carousel/owl.carousel.min.css')?>"
	rel="stylesheet">
<link href="<?php echo $this->theme->getUrl('css/style.css')?>"
	rel="stylesheet">
<link href="<?php echo $this->theme->getUrl('css/helper.css')?>"
	rel="stylesheet">
<link href="<?php echo $this->theme->getUrl('css/responsive.css')?>"
	rel="stylesheet">
<style>
@import
	url('https://fonts.googleapis.com/css2?family=Sriracha&display=swap');
</style>
<link
	href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap"
	rel="stylesheet">
</head>
<body class="home-page">
      <?php $this->beginBody()?>
      <!-- ******HEADER****** -->
	<?=$this->render('header_new.php');?>
	<!--//header-->
      <?= Gdpr::widget();?>
      <!-- body content start-->
	<div class="main_wrapper">
         <?= $content?>
      </div>
	<!--body wrapper end-->
	

	<!-- Javascript -->
	<script src="<?php echo $this->theme->getUrl('js/jquery-3.2.1.min.js')?>"></script>
	<script src="<?php echo $this->theme->getUrl('js/popper.min.js')?>"></script>
	<script src="<?php echo $this->theme->getUrl('js/bootstrap.min.js')?>"></script>
	<script src="<?php echo $this->theme->getUrl('js/matchHeight.js')?>"></script>
     <?php $this->endBody()?>  
   </body>
   <?php $this->endPage()?>
</html>