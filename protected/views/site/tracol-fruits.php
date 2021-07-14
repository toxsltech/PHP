<?php
use app\modules\blog\models\Post;
use app\modules\feature\models\Feature;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use app\models\User;
/* @var $this yii\web\View */
// $this->title = Yii::$app->name;
?>
<!--promo section start-->
<section class="main_slider_area">
	<div
		class="banner_section staggered-animation-wrap pattern_banner_bottom">
		<div id="carouselExampleControls"
			class="carousel slide carousel-fade carousel_style1 light_arrow"
			data-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active background_bg overlay_bg_60" style="background-image: url(<?php echo $this->theme->getUrl('images/back.jpeg');?>)">
					<div class="banner_slide_content">
						<div class="container">
							<div class="row justify-content-center">
								<div class="col-lg-9 col-md-12 col-sm-12 text-center">
									<div class="banner_content text_white">
										<h2 class="staggered-animation font_style1 text-white mb-4">We
											support to find a healthy life</h2>
										<p class="staggered-animation text-white">We send fresh
											assorted ,premium and high quality fruit &healthy in the
											sense that we ask our nutritionist to design the menu
											tailored to your preferred consumption and lastly we deliver
											to your doorstep for free.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="carousel-item background_bg overlay_bg_60"
					style="background-image: url('./themes/new/images/back2.jpeg');">
					<div class="banner_slide_content">
						<div class="container">
							<div class="row justify-content-center">
								<div class="col-lg-9 col-md-12 col-sm-12 text-center">
									<div class="banner_content text_white">
										<h2 class="staggered-animation font_style1 text-white mb-4">Friendly
											Service , Fresh Fruits on Demand and Freshness Promise</h2>
										<p class="staggered-animation text-white">we bring you the
											world’s most fun and exquisite produce ,a one stop solution
											to your fruity needs !</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a class="carousel-control-prev" href="#carouselExampleControls"
				role="button" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
			<a class="carousel-control-next" href="#carouselExampleControls"
				role="button" data-slide="next"><i class="fa fa-chevron-right"></i></a>
		</div>
	</div>
	<div class="shape-bottom">
		<img
			src="<?php echo $this->theme->getUrl('images/wave-line-bw-long.svg')?>"
			alt="shape" class="bottom-shape img-fluid">
	</div>
</section>
<div class="why-choose health_coach_area">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="center_tittle mb-4">
					<h2>Who are we?</h2>
					<h6>We are Tech Companies that bring your healthy lifestyle to the
						new level .Join us Now!!!</h6>
				</div>
			</div>
		</div>
		<div class="points health_coach_row">
			<div class="row align-items-center">
				<div class="col-md-4 co-lg-12">
					<div class="row media_row">
						<div class="col-lg-12">
							<div class="theme-box mb-4">
								<div class="box-icon">
									<img
										src="<?php echo $this->theme->getUrl('images/icon-1/4.png')?>"
										alt="img">
								</div>
								<div class="box-dicsp">
									<p>A Fruit Box Business that can make you life healthier.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="theme-box mb-4 mb-lg-0">
								<div class="box-icon">
									<img
										src="<?php echo $this->theme->getUrl('images/icon-1/5.png')?>"
										alt="img">
								</div>
								<div class="box-dicsp">
									<p>An important section for you to gain more knowledge , how to
										,and many more about our services.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 co-lg-12 m-t-40">
					<div class="theme-box mb-4">
						<div class="box-icon">
							<img
								src="<?php echo $this->theme->getUrl('images/icon-1/3.png')?>"
								alt="img">
						</div>
						<div class="box-dicsp">
							<p>Its meaningless to only view our content or just eating our
								fruits, be part of our communities and start contribute to those
								who in need.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 co-lg-12">
					<div class="row media_row">
						<div class="col-lg-12">
							<div class="theme-box mb-4">
								<div class="box-icon">
									<img
										src="<?php echo $this->theme->getUrl('images/icon-1/1.png')?>"
										alt="img">
								</div>
								<div class="box-dicsp">
									<p>New Technology that matches Doctors , Clinics and Hospital
										able to serve their patient thru online platform.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="theme-box mb-4 mb-lg-0">
								<div class="box-icon">
									<img
										src="<?php echo $this->theme->getUrl('images/icon-1/2.png')?>"
										alt="img">
								</div>
								<div class="box-dicsp">
									<p>This is where you can post and your achievement after
										enjoying our services.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<section class="services_area">
	<div class="container">
		<div class="left_tittle">
			<h2>No more waiting , Get your Fruit Box to be delivered at you
				doorstep TODAY!!</h2>
			<a href="http://www.wasap.my/+60192490899/programdietitiantracol"
				class="btn btn-primary"><span>Contact Us Now</span></a>
			<p>Save more with us and join our health club community!</p>
		</div>
		<div class="row service_row">
			<div class="col-lg-3 col-md-6 body_mind">
				<div class="media">
					<img
						src="<?php echo $this->theme->getUrl('images/icons/fruit_box.png')?>"
						alt="img">
					<div class="media-body">
						<h4>Select your Fruit Box</h4>
						<p>Choose the size box of assorted fruit that best fits your
							office.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 body_mind">
				<div class="media">
					<img
						src="<?php echo $this->theme->getUrl('images/icons/zip_code.png')?>"
						alt="img">
					<div class="media-body">
						<h4>Enter your zipcode</h4>
						<p>Enter your delivery zip code to see if we ship to your area.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 body_mind">
				<div class="media">
					<img
						src="<?php echo $this->theme->getUrl('images/icons/choose_delivery.png')?>"
						alt="img">
					<div class="media-body">
						<h4>Choose your delivery</h4>
						<p>Select the delivery day and frequency of your Fruit box.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 body_mind">
				<div class="media">
					<img
						src="<?php echo $this->theme->getUrl('images/icons/sit_enjoy.png')?>"
						alt="img">
					<div class="media-body">
						<h4>Sit back, and enjoy!</h4>
						<p>Enjoy fresh fruit & snacks delivered right to your office!</p>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-6 body_mind">
				<div class="media">
					<img
						src="<?php echo $this->theme->getUrl('images/icons/pack.png')?>"
						alt="img">
					<div class="media-body">
						<h4>We pack</h4>
						<p>We hand pack each box into our custom made corrugated shipper.
							Our shipping boxes are made up of 70% post-recycled materials.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 body_mind">
				<div class="media">
					<img
						src="<?php echo $this->theme->getUrl('images/icons/ship.png')?>"
						alt="img">
					<div class="media-body">
						<h4>We ship</h4>
						<p>We’ve got a sweet deal with our partner carriers so that your
							fruit box arrives seamlessly with the rest of your mail.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 body_mind">
				<div class="media">
					<img
						src="<?php echo $this->theme->getUrl('images/icons/last_enjoy.png')?>"
						alt="img">
					<div class="media-body">
						<h4>You enjoy</h4>
						<p>Your team enjoys a weekly box of fresh fruit and wholesome
							snacks – straight from the branch to the box.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section id="pricing" class="pricing_section bg-grey padding">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="center_tittle pt-0">
					<h2>Our Health Fruits Package</h2>
					<h6>Save more with us and join our health club community!</h6>
				</div>
			</div>
		</div>
		<div class="row service_row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<table>
						<colgroup></colgroup>
						<colgroup></colgroup>
						<colgroup></colgroup>
						<colgroup></colgroup>
						<colgroup></colgroup>
						<thead>
							<tr>
								<th class="border-0-cus">&nbsp;</th>
             <?php
            $count = 1;
            foreach ($plans as $key => $subscription_plan) {
                ?>
                <th>
									<div class="pricing_table align-center">
										<div class="pricing_head">
											<h4><?= $subscription_plan->title ?></h4>
											<div class="d-lg-block d-none">
                      <?php $image ='images/subs'.$count.'.png'; ?>
                       <?php
                echo Html::img($subscription_plan->getImageUrl(), [
                    'class' => 'img-responsive',
                    'alt' => $subscription_plan
                ])?>
                      </div>
										</div>
									</div>
								</th>
                <?php $count++; }?>
              </tr>
						</thead>
						<tbody>
							<tr>
								<th class="theme-green">Price (RM) Per Box</th>
                    <?php

                    foreach ($plans as $key => $price) {
                        if ($key == 0) {
                            $link = "https://app.senangpay.my/payment/160973572083";
                        } elseif ($key == 1) {
                            $link = "https://app.senangpay.my/payment/160973585434";
                        } elseif ($key == 2) {
                            $link = "https://app.senangpay.my/payment/160973683442";
                        } else {
                            $link = "https://app.senangpay.my/payment/160973699648";
                        }
                        ?>
                    <td class="font-big"><a href="<?= $link?>"><span
										class="price-main">RM <?= $price->price ?>/Box</span></a></td>
					<?php }?>
                  </tr>
                  <?php if(User::isUser()&& !User::isAdmin()){?>
																						<tr>
																<th></th>
                                                            <?php
                                                            $post = Yii::$app->request->get();
                                                            foreach ($plans as $key => $buy) {
                                                                ?>
                                                            <td>
                                                            <input type="button" data-id="<?=$buy->id?>" onclick="getsubmit('<?=$buy->id?>');" data-id=<?=$buy->id?>
																	class="next btn btn-primary px-lg-4 py-2 buy-now"
																	value="Buy Now">
																	</td>
                                                            <?php  }?>
                                                          </tr>
                  <?php }?>
							<tr>
								<th>No of Box</th>
					<?php foreach ($plans as $key => $box) {?>
					<td><?= $box->total_delivered?></td>
					<?php }?>
				</tr>
							<tr>
								<th>Expiry Duration <span
									class="text-danger w-100 d-inline-block font_600">(Month)</span>
								</th>
                <?php foreach ($plans as $key => $duration) {?>
				<td><?= $duration->validity?></td>
				<?php }?>
              </tr>
							<tr>
								<th>No. of Address</th>
					<?php foreach ($plans as $key => $address) {?>
					<td><?= $address->no_of_address?></td>
					<?php }?>
				</tr>
							<tr>
								<th>Menu advice by Nutritionist <span
									class="text-danger w-100 d-inline-block font_600">(WORTH of
										RM3,600!!)</span>
								</th>
                    <?php foreach ($plans as $key => $address) {?>
					<td>Free</td>
					<?php }?>
				</tr>
							<tr>
								<th>Video, Articles & Diet Reference <span
									class="text-danger w-100 d-inline-block font_600">(WORTH of
										RM3,600!!)</span>
								</th>
					 <?php foreach ($plans as $key => $video) {?>
					<td>Free</td>
					<?php }?>
				</tr>
							<tr>
								<th>Nutritionist consultation & advice <span
									class="text-danger w-100 d-inline-block font_600">(WORTH of
										RM2,500!!)</span>
								</th>
					 <?php foreach ($plans as $key => $nut) {?>
					<td>Free</td>
					<?php }?>
				</tr>
							<tr>
								<th>Change of Delivery Address <span
									class="text-danger w-100 d-inline-block font_600">(WORTH of
										RM20 per box!!!)</span>
								</th>
                    <?php foreach ($plans as $key => $change_add) {?>
					<td><?= $change_add->description?></td>
					<?php }?>
				</tr>
							<tr>
								<th>Number of Contest Ticket</th>
															 <?php foreach ($plans as $key => $ticket) {?>
															
															<td><?= $ticket->no_of_contest_ticket?></td>
															<?php }?>
														</tr>
							<tr>
								<th>Total Price RM</th>
                    <?php foreach ($plans as $key => $price) {?>
                    <td>RM <?= (int)($price->price ) * (int)($price->total_delivered) ?></td>
                   <?php }?>
              </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="fitness_course_area">
	<div class="container">
		<div class="fitness_course_row">
			<h2>Join with Us</h2>
			<p>We are focused on excellence in quality and take pride in being
				one of the most advanced and innovative fresh produce companies in
				Malaysia. Our customer in search of the fresh fruits with best
				quality to ensure all fresh produce arrives in its best form to
				customers. Dowload our mobile apps now</p>
			<div class="d-flex app-icons">
				<a href="#"><img
					src="<?php echo $this->theme->getUrl('images/app.jpg')?>" alt="img"></a>
				<a href="#" class="ml-3"><img
					src="<?php echo $this->theme->getUrl('images/google.jpg')?>"
					alt="img"></a>
			</div>
		</div>
	</div>
</section>
<section class="section latest_news_area">
	<div class="container">
		<div class="left_tittle">
			<h2>Our offering that tailored to your lifestyle</h2>
		</div>



		<div class="row latest_news_row">
			<div class="col-lg-12">
				<div id="type-box" class="owl-carousel owl-theme">
            <?php foreach ($offerings as $value) { ?>

          <div class="item">
						<div class="latest_news">
							<div class="news_img">
								<a href="#"><?php
                echo Html::img($value->getImageUrl(), [
                    'class' => 'img-responsive',
                    'alt' => $value
                ])?></a>
							</div>
							<a href="#" class="news_heding"><?= $value->title ?></a>
							<div class="same-description">
								<p><?= $value->description ?></p>
							</div>
						</div>
					</div>
          <?php }?>
        </div>
			</div>
		</div>
	</div>
</section>
<section
	class="testimonial_area_two testimonial_bg_white postition-relative">
	<div class="container">
		<div class="row">
			<div class="col-lg-5">
				<div class="about_content">
					<h2>Looking for Corporate Deliveries?</h2>
					<p>TracolCorporate is an awesome office fruit delivery service that
						goes above and beyond in meeting the fruity nutritional needs of
						all hardworking employees. Gone are the days of dealing with old
						stuffy fruit wholesale uncles that don’t take your office orders
						seriously.</p>
					<a href="#" class="btn btn-primary mb-80"><span>Get Fruits for
							Office Today</span></a>
				</div>
			</div>
			<div class="col-lg-7 testimonial_gallery">
				<img width="100%"
					src="<?php echo $this->theme->getUrl('images/box-type.png')?>"
					alt="img">
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
function getsubmit(id)
{

var url='<?=Url::toRoute(['/user/checkout?sub='])?>'+id;
  window.location.href =url;
}
</script>