<?php $pagename = "Login"; ?>
<!--================Header Area =================-->
<div class="banner_area">
	<h2>My Profile</h2>
	<a href="#">Home <span>&gt; My Profile</span></a>
</div>
<!--================Slider Area =================-->
<section class="space-ptb bg-light rider-dashbord">
	<div class="container">
		<div class="row">
    <?= Yii::$app->controller->renderPartial('user_profile',['model' => $model]);  ?>
      		<div class="col-md-9">
				<div class="tab-content bg-blue border-style">
					<div id="profile" class="profile-edit tab-pane active">
						<div class="widget">
							<div class="widget-title bg-primary">
								<h6 class="text-white mb-0">My Profile</h6>
							</div>
							<div class="widget-content">
								<h3>Manage Your Profile</h3>
								<hr>

								<div class="table-responsive">


									<table class="table profile-table">
										<tbody>
											<tr>
												<th>Full Name</th>
												<td><?= $model->full_name?></td>
											</tr>
											<tr>
												<th>Email Address</th>
												<td><?= $model->email?></td>
											</tr>
											<tr>
												<th>Phone Number</th>
												<td><?= $model->contact_no?></td>
											</tr>
											<tr>
												<th>Referral Code</th>
												<td><?= isset($model->referral_code) ? $model->referral_code : ' ' ?></td>
											</tr>
											<tr>
												<td class="border-none">
													<h3 class="mt-3 mb-0">Personal Details</h3>
												</td>
											</tr>
											<tr>
												<th>Height</th>
												<td><?= isset($userdetails->user_height) ? $userdetails->user_height : '0' ?> <?=  !empty($userdetails->height)?$userdetails->height:' '; ?></td>
											</tr>
											<tr>
												<th>Weight</th>
												<td><?= isset($userdetails->weight) ? $userdetails->weight : '0' ?> <?= !empty($userdetails->type)?$userdetails->type:' ';?></td>
											</tr>
											<tr>
												<th>Eating Habits</th>
												<td><?= !empty($userdetails->habbitOptions)?$userdetails->habbitOptions:' ';?></td>
											</tr>
											<tr>
												<td class="border-none">
													<h3 class="mt-3 mb-0">Medical Specifications</h3>
												</td>
											</tr>
											<tr>
												<th>Any Medical Problem</th>
												<td><?= isset($userdetails->description) ? $userdetails->description : 'NO' ?></td>
											</tr>
											<tr>
												<td class="border-none">
													<h3 class="mt-3 mb-0">Fruit Diet Details</h3>
												</td>
											</tr>
											<tr>
												<th>No. of fruit intake per week</th>
												<td><?= isset($userdetails->eat_count) ? $userdetails->eat_count : '0' ?></td>
											</tr>
											<tr>
												<td class="border-none">
													<h3 class="mt-3 mb-0">Packages Categories</h3>
												</td>
											</tr>
											<tr>
												<th>Preferred box</th>
												<td><?= isset($userdetails->package_id) ? $userdetails::getPackage($userdetails) : 'Not Defined'?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
