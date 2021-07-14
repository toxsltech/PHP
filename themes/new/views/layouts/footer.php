<?php
use yii\helpers\Url;

?>
<?php if(\Yii::$app->controller->action->id != 'login' || \Yii::$app->controller->action->id != 'signup'){ ?>
<footer class="footer_area pt-0">
	<div class="container">

		<div class="row footer_row">
			<div class="col-lg-3 col-md-6 fooer_logo">
				<a href="index.php"><img
					src="<?php echo $this->theme->getUrl('images/logo1.png')?>"
					alt="img"></a>
				<p>Healthiness of New Era , Grab Yours Now .Every Package Spent on
					TRACOL APPS will give you a chance to Win RM1,000,000 .</p>

			</div>
			<div class="col-6 col-lg-3 col-md-6 quick quick_strat">
				<h4>Quick Links</h4>
				<ul class="quick_links">
					<li><a href="<?=Url::toRoute(['/site/about'])?>">- About Us</a></li>
					<li><a href="<?=Url::toRoute(['/site/contact'])?>">- Contact Us</a></li>
				</ul>
			</div>
			<div class="col-6 col-lg-3 col-md-6 quick">
				<h4>Useful Links</h4>
				<ul class="quick_links">
					<li><a href="<?=Url::toRoute(['/site/terms'])?>">- Terms &
							Condition</a></li>
					<li><a href="<?=Url::toRoute(['/site/privacy'])?>">- Privacy Policy</a></li>
					<li><a href="<?=Url::toRoute(['/site/delivery-information'])?>">-
							Delivery Information</a></li>
				</ul>
			</div>
			<div class="col-lg-3 col-md-6 quick">
				<h4>Get in Touch</h4>
				<address>
					<a><i class="fa fa-map-marker"></i>A-06-06, Block A, Radia
						Persiaran Arked , Bukit Jelutong , Seksyen U8 <br>40150 Shah Alam
						, Selangor.</a> <a href="#"><i class="fa fa-phone"></i>+603-61274154
					</a> <a href="#"><i class="fa fa-envelope-o"></i>hello@tracolasia.com</a>
				</address>
			</div>
		</div>
	</div>
	<div class="copy_right">
		<div class="container text-center">
			<h6>
				Copyright © <a href="#">tracol</a> 2020. All rights reserved.
			</h6>
		</div>
	</div>
</footer>

<?php }?>