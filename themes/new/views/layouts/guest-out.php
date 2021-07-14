<?php
use app\assets\AppAsset;
use app\components\FlashMessage;
use app\components\gdpr\Gdpr;
use yii\helpers\Html;

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
	href="<?php $this->theme->getUrl('images/logo1.png')?>" type="image/png">

<link id="theme-style" rel="stylesheet"
	href="<?php echo $this->theme->getUrl('css/style.css')?>">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tracol</title>
<link
	href="<?php echo $this->theme->getUrl('css/font-awesome.min.css')?>"
	rel="stylesheet">
<!-- <link -->
<!-- 	href="<?php echo $this->theme->getUrl('vendors/stroke-icon/style.css')?>"  -->
<!-- 	rel="stylesheet"> -->
<link
	href="<?php echo $this->theme->getUrl('vendors/flat-icon/flaticon.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('vendors/revolution/css/settings.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('vendors/revolution/css/layers.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('vendors/revolution/css/navigation.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('vendors/animate-css/animate.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('vendors/magnify-popup/magnific-popup.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('vendors/owl-carousel/owl.carousel.min.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('js/owl-carousel/owl.carousel.min.css')?>"
	rel="stylesheet">
<link
	href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('css/owl.theme.default.min.css')?>"
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
    
      <?= Gdpr::widget();?>
      <?= FlashMessage::widget();?>
      <!-- body content start-->
	<div class="main_wrapper">
         <?= $content?>
      </div>
	<!--body wrapper end-->
	<?=$this->render('footer.php');?>

	<!-- Javascript -->
	<script
		src="<?php echo $this->theme->getUrl('js/owl-carousel/owl.carousel.min.js')?>"></script>
	<script src="<?php echo $this->theme->getUrl('js/custom.js"')?>"></script>
	<script src="<?php echo $this->theme->getUrl('js/popper.min.js')?>"></script>
	<script
		src="<?php echo $this->theme->getUrl('vendors/owl-carousel/owl.carousel.min.js')?>"></script>
	<script src="<?php echo $this->theme->getUrl('js/theme.js')?>"></script>
	<script src="<?php echo $this->theme->getUrl('js/matchHeight.js')?>"></script>
	<!--custom js-->
	<script src="<?= $this->theme->getUrl('html/js/scripts.js')?>"></script>
     <?php $this->endBody()?>  
   </body>
   <?php $this->endPage()?>
</html>