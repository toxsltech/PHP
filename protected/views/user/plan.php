 <?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

// $this->title = 'Signup';
?>
<style>
.login-main .buy-now {
	color: #fff;
	background-color: #2da369;
	border-color: #2da369;
	padding: 12px 30px;
	font-size: 18px;
	font-weight: 500;
	min-width: 130px;
	max-width: 100%;
}
</style>
<div class="login-main">
	<div class="container-fluid p-lg-0">
		<div class="row p-lg-0">
			<div class="col-lg-4 p-0">
				<div class="left-bar">
					<h2 class="section-hd text-white mb-30">Welcome Back !</h2>
					<p>Healthy lifestyle begins with you and ends with TRACOL. Get
						started on your journey to for good . It’s TIME to start ?</p>
				</div>
			</div>
			<div class="col-lg-8 light-bg pl-lg-0">
				<div class="right-bar px-5 mw-100">
					<div class="form-main">
						<div class="signup-form row setup-content">
							<div class="col-md-12">
								<div class="signed-top mb-20">
									<div class="sign-flow-progress mb-4">
										<div class="progress">
											<div class="progress-bar" style="width: 80%"></div>
										</div>
										<small>80% complete</small>
									</div>
									<h4 class="mb-30 text-center">Subscription Plan</h4>
								</div>
							</div>
							<div class="col-md-12">
								<section class="pricing_section bg-grey padding">
									<div class="container">
										<div class="row service_row pt-0">
											<div class="col-lg-12">
												<div class="table-responsive">
													<table>
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
                                                            <?php }?>
                                                          </tr>
														</thead>
														<tbody>
															<tr>
													
																<th class="theme-green">Price (RM) Per Box</th>
                                                            <?php foreach ($plans as $key => $price) {?>
                                                            <td data-id="<?=$price->id?>" onclick="getsubmit('<?=$price->id?>');" data-id=<?=$price->id?> class="font-big"><span class="price-main">RM<?= $price->price?>/Box</span></td>
                                							<?php }?>
                                                          </tr>
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
															<tr>
																<th>No of Box</th>
															<?php foreach ($plans as $key => $box) {?>
															<td><?= $box->total_delivered?></td>
															<?php }?>
														</tr>
															<tr>
																<th>Expiry Duration(Month)</th>
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
																	class="text-danger w-100 d-inline-block font_600">(WORTH
																		of RM3,600!!)</span>
																</th>
                                                            <?php foreach ($plans as $key => $address) {?>
															<td>Free</td>
															<?php }?>
														</tr>
															<tr>
																<th>Video, Articles & Diet Reference <span
																	class="text-danger w-100 d-inline-block font_600">(WORTH
																		of RM3,600!!)</span>
																</th>
															 <?php foreach ($plans as $key => $video) {?>
															<td>Free</td>
															<?php }?>
														</tr>
															<tr>
																<th>Nutritionist consultation & advice <span
																	class="text-danger w-100 d-inline-block font_600">(WORTH
																		of RM2,500!!)</span>
																</th>
															 <?php foreach ($plans as $key => $nut) {?>
															<td>Free</td>
															<?php }?>
														</tr>
															<tr>
																<th>Change of Delivery Address <span
																	class="text-danger w-100 d-inline-block font_600">(WORTH
																		of RM20 per box!!!)</span>
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
                                                            <td>RM<?= (int)($price->price ) * (int)($price->total_delivered) ?></td>
                                                           <?php }?>
                                                      </tr>
                                                      
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-12 mt-30">
											<a href="<?=Url::toRoute(['user/prefferred-package'])?>" class="previous butn butn-border float-right"
												id="preview_button"><span>Back</span></a>
										</div>
									</div>
								</section>
							</div>
						</div>
          			</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function getsubmit(id)
{

var url='<?=Url::toRoute(['/user/checkout?sub='])?>'+id;
  window.location.href =url;
}
</script>
