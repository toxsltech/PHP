<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Address;
?>
<?php $pagename = "Login"; ?>

<!--================Header Area =================-->
<div class="banner_area">
	<h2>My Profile</h2>
	<a href="#">Home <span>&gt; My Profile</span></a>
</div>

<section class="space-ptb bg-light rider-dashbord">
	<div class="container">
		<div class="row">
      <?= Yii::$app->controller->renderPartial('user_profile',['model' => $model]);  ?>
      <div class="col-md-9">
				<div class="tab-content bg-blue border-style">
					<div class="profile-edit">
						<div class="widget">
							<div class="widget-title bg-primary">
								<h6 class="text-white mb-0">My Plan</h6>
							</div>
							<div class="widget-content">
								<section class="checkout_table py-0">
									<div class="container">
										<div class="rwo checkout_row">

											<table class="table table-bordered table-responsive-lg">
												<thead>
													<tr>
														<th scope="col" class="preview">Preview</th>
														<th scope="col" class="product">Plan</th>
														<th scope="col" class="price">Price</th>
														<th scope="col" class="quantity">Number of boxes</th>
														<th scope="col" class="total">Total</th>
														<th scope="col" class="close_x"></th>
													</tr>
												</thead>
												<tbody>
													<tr>
                                                      <?php $image = 'images/subs'. $subscription->id .'.png';?>
                                                        <td> <?php
                                                        echo Html::img($subscription->getImageUrl(), [
                                                            'class' => 'img-responsive',
                                                            'alt' => $subscription
                                                        ])?></td>
														<td><?= $subscription->title?></td>
														<td><?= $subscription->price?></td>
														<td><?= $subscription->total_delivered ?></td>
														<td class="total">RM <?= (isset($subscription->price) ? $subscription->price : 0) *  (isset($subscription->total_delivered) ? $subscription->total_delivered : 1)?></td>
														<td class="close_x">X</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</section>
							</div>
						</div>
					</div>
					<div class="profile-edit" id="address">
						<div class="widget">
							<div class="widget-title bg-primary d-flex">
								<h6 class="text-white mb-0 d-inline">Shipping Address</h6>
								<a class="extra-fields-customer ml-auto text-white" href="#0"
									data-toggle="modal" data-target="#add-address"><i
									class="fa fa-plus mr-2"></i>Add New Address</a>
							</div>
							<div class="widget-content">
								<div class="right-bar">
									<div class="form-main custom-new-main">
									<?php
        $form = TActiveForm::begin([
            'id' => 'user-signup-form',
            'class' => 'signup-flow row mt-3'
        ]);
        ?>
                                            <?php

                                            foreach ($useraddress as $key => $addresss) {
                                                if ($addresss) {
                                                    ?>
                                              <div class="col-md-12">
											<div class="form-group border-bottom position-relative">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" id="customRadio<?=$addresss->id?>"
														name="customRadio" class="custom-control-input"
														<?= ($addresss->state_id == Address::STATE_ACTIVE) ? "checked" : "" ?>>
													<label class="custom-control-label label-new"
														for="customRadio<?=$addresss->id?>"><?= $addresss->first_name .' '.$addresss->last_name?>
                             								<p><?= $addresss->primary_address .","?><br><?= $address->secondary_address ." , ".$addresss->city ?>.</p>
														<p>Phone Number : <?= $addresss->contact_no?></p>
														<p>Zip code : <?= $addresss->zipcode ?></p>
														<p>
															<strong>Date : </strong><?= $addresss->date ?></p> </label>
												</div>
												<div data-toggle="modal" data-id="<?= $addresss->id ?>"
													data-target="#edit-address" class="edit-add">
													<i class="fa fa-pencil"></i>
												</div>
												<div data-toggle="modal" data-id="<?= $addresss->id ?>"
													data-target="#delete-address" class="delete"
													style="position: absolute; right: 0; top: 60px; background: red; width: 35px; height: 35px; text-align: center; line-height: 35px; color: #fff; border-radius: 2px; cursor: pointer;">
													<i class="fa fa-trash"></i>
												</div>
											</div>

										</div>
                                          <?php }}?>
                                          <?php TActiveForm::end(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Modal -->
<div class="modal fade" id="add-address" tabindex="-1"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add New Address</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
      <?php
    $form = TActiveForm::begin([
        'id' => 'user-address',
        'class' => 'signup-form mt-0',
        'action' => [
            'user/add-address'
        ]
    ]);
    ?>
          <div class="row">
					<div class="form-group col-md-6">
              <?php echo $form->field($address, 'first_name')->textInput(['maxlength' => 256]) ?>
            </div>
					<div class="form-group col-md-6">
              <?php echo $form->field($address, 'last_name')->textInput(['maxlength' => 256]) ?>
            </div>
					<div class="form-group col-12">
              <?php echo $form->field($address, 'primary_address')->textInput(['maxlength' => 256,'placeholder' => 'Street Address']) ?>
              <?php echo $form->field($address, 'secondary_address')->textInput(['maxlength' => 256,'placeholder' => 'Apartment, Suit unit etc'])->label(false) ?>
            </div>
					<div class="form-group col-12">
						<label class="input_heding">Town / City </label>
               <?php echo $form->field($address, 'city')->textInput(['maxlength' => 256 , 'placeholder' =>"Town/City"])->label(false) ?>
            </div>
					<div class="form-group col-md-6">
						<label class="input_heding">Postcode / Zip </label>
              <?php echo $form->field($address, 'zipcode')->textInput(['maxlength' => 256 , 'placeholder' =>"Postcode/Zip"])->label(false) ?>
            </div>
					<div class="form-group col-md-6">
              <?php echo $form->field($address, 'contact_no')->textInput(['maxlength' => 256]) ?>
            </div>
					<div class="form-group col-md-6">
              <?php

            echo $form->field($address, 'date')->widget(yii\jui\DatePicker::class, [
                // 'dateFormat' => 'php:Y-m-d',
                'options' => [
                    'class' => 'form-control'
                ],
                'clientOptions' => [
                    'minDate' => date('Y-m-d'),
                    'maxDate' => date('Y-m-d', strtotime('+30 days')),
                    'changeMonth' => true,
                    'changeYear' => true
                ]
            ])?>
            </div>
					<div class="form-group col-md-12 d-flex flex-row-reverse">
              <?= Html::submitButton($address->isNewRecord ? Yii::t('app', 'Submit') : Yii::t('app', 'Update'), ['id'=> 'address-form-submit','class' => $address->isNewRecord ? 'btn btn-primary px-5' : 'btn btn-primary px-5']) ?>
            </div>
				</div>
         <?php TActiveForm::end(); ?>
      </div>
		</div>
	</div>
</div>
<div class="modal fade" id="edit-address" tabindex="-1"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit Address</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
      <?php
    $form = TActiveForm::begin([
        'id' => 'update-user-address',
        'class' => 'signup-form mt-0',
        'action' => [
            'user/update-address'
        ]
    ]);
    ?>
          <div class="row">
          		 <?php echo $form->field($address, 'id')->textInput(['id' => 'id','maxlength' => 256,'hidden'=>'hidden'])->label(false) ?>
            
					<div class="form-group col-md-6">
              <?php echo $form->field($address, 'first_name')->textInput(['id' => 'first_name','maxlength' => 256]) ?>
            </div>
					<div class="form-group col-md-6">
              <?php echo $form->field($address, 'last_name')->textInput(['id' => 'last_name','maxlength' => 256]) ?>
            </div>
					<div class="form-group col-12">
              <?php echo $form->field($address, 'primary_address')->textInput(['id' => 'primary_address','maxlength' => 256,'placeholder' => 'Street Address']) ?>
              <?php echo $form->field($address, 'secondary_address')->textInput(['id' => 'secondary_address','maxlength' => 256,'placeholder' => 'Apartment, Suit unit etc'])->label(false) ?>
                        </div>
					<div class="form-group col-12">
						<label class="input_heding">Town / City </label> 
              <?php echo $form->field($address, 'city')->textInput(['id' => 'city','maxlength' => 256 , 'placeholder' =>"Town/City"])->label(false) ?>
            </div>
					<div class="form-group col-md-6">
						<label class="input_heding">Postcode / Zip </label> 
              <?php echo $form->field($address, 'zipcode')->textInput(['id' => 'zipcode','maxlength' => 256 , 'placeholder' =>"Postcode/Zip"])->label(false) ?>
            </div>
					<div class="form-group col-md-6">
               <?php echo $form->field($address, 'contact_no')->textInput(['id' => 'contact_no','maxlength' => 256]) ?>
            </div>
					<div class="form-group col-md-6">
              <?php
            echo $form->field($address, 'date')->widget(yii\jui\DatePicker::class, [
                // 'dateFormat' => 'php:Y-m-d',
                'options' => [
                    'class' => 'form-control',
                    'id' => 'date'
                ],
                'clientOptions' => [
                    'minDate' => date('Y-m-d'),
                    'maxDate' => date('Y-m-d', strtotime('+30 days')),
                    'changeMonth' => true,
                    'changeYear' => true
                ]
            ])?>
            </div>

					<div class="col-md-12">
              <?= Html::submitButton($address->isNewRecord ? Yii::t('app', 'Submit') : Yii::t('app', 'Update'), ['id'=> 'addresss-form-submit','class' => $address->isNewRecord ? 'btn btn-primary px-5' : 'btn btn-primary px-5']) ?>
            </div>
				</div>
        <?php TActiveForm::end(); ?>
      </div>
		</div>
	</div>
</div>
<script>


$(document).on("click",".edit-add",function(e) {
var id  = $(this) . attr('data-id');
$.ajax({
		type:'POST',
		url: '<?=Url::toRoute(['/user/edit-address'])?>/'+id,
		success:function(response) {
			if(response.status == 'OK'){
				$("#id").val(response.data.id);
				$("#first_name").val(response.data.first_name);
				$("#last_name").val(response.data.last_name);
				$("#primary_address").val(response.data.primary_address);
				$("#secondary_address").val(response.data.secondary_address);
				$("#city").val(response.data.city);
				$("#zipcode").val(response.data.zipcode);
				$("#contact_no").val(response.data.contact_no);
				$("#no_of_box").val(response.data.no_of_box);
				$("#date").val(response.data.date);
				$("#time").val(response.data.time);
				$("#edit-address").modal("show");
			}
		}
	});
});
$(document).on("click",".delete",function(e) {
if(confirm("Please! ,Press ok to delete address")){
    var id  = $(this) . attr('data-id');
    $.ajax({
    		type:'POST',
    		url: '<?=Url::toRoute(['/address/delete'])?>/'+id,
    	});
	}
});



</script>