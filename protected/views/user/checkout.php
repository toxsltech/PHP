 <?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use phpDocumentor\Reflection\Types\False_;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

// $this->title = 'Signup';
?>
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
				<div class="right-bar ">
					<div class="form-main">
						<div class="signed-top mb-40">
							<div class="sign-flow-progress mb-5">
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<small>100% complete</small>
							</div>
							<h4 class="mb-30">Checkout</h4>
						</div>
								  <?php
        $form = TActiveForm::begin([
            'id' => 'form-signup',
            'action' => Url::toRoute([
                'user/sen',
                'id' => $subscription_plan->id
            ]),
            'method' => 'post',
            'enableAjaxValidation' => false
        ]);
        ?>
								<div class="checkout_table p-0">
							<div class="rwo checkout_row">
								<div class="table-responsive">
									<table class="table table-bordered table-responsive-lg">
										<thead>
											<tr>
												<th scope="col" class="preview">Preview</th>
												<th scope="col" class="product">Plan</th>
												<th scope="col" class="price">Price</th>
												<th scope="col" class="quantity">Number of boxes</th>
												<th scope="col" class="quantity">Delivery Charges</th>
												<th scope="col" class="total">Total</th>
												<th scope="col" class="close_x"></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?php
            echo Html::img($subscription_plan->getImageUrl(), [
                'class' => 'img-responsive',
                'alt' => $subscription_plan
            ])?>
</td>
												<td><?= isset($subscription_plan->description) ? $subscription_plan->description : 'Just Nice for Me' ?></td>
												<td><?= isset($subscription_plan->price) ? $subscription_plan->price : '0' ?></td>
												<td><?= isset($subscription_plan->total_delivered) ? $subscription_plan->total_delivered : '0' ?></td>
												<td class="total">RM <?=(isset($subscription_plan->total_delivered) ? $subscription_plan->total_delivered : 1)*24 ?></td>
												<td class="total">RM <?= (isset($subscription_plan->price) ? $subscription_plan->price : 0) *  (isset($subscription_plan->total_delivered) ? $subscription_plan->total_delivered : 1) + (isset($subscription_plan->total_delivered) ? $subscription_plan->total_delivered : 1)*24 ?></td>
												<td class="close_x">X</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-12 mt-30 d-flex checkputbtn">
							<input type="hidden" id="subscription" value=""> <a
								href="<?=Url::toRoute(['user/subscription-plan'])?>"
								class="previous btn btn-primary mb-2" id="previous_botton_7"><span>Back</span></a>
								
								  <?=Html::submitButton($subscription_plan->isNewRecord ? Yii::t('app', 'Continue') : Yii::t('app', 'Pay Now'), ['id' => 'sen-submit','class' => $subscription_plan->isNewRecord ? 'btn btn-primary mb-2' : 'btn btn-primary mb-2 '])?>
								  <input type="hidden" id="sen-submit" value=""> <a
								href="<?=Url::toRoute(['user/pay-later'])?>"
								class="btn btn-primary mb-2" id="sen-submit"><span>Pay Later</span></a>
							<?=$form->field($subscription_plan, 'title')->hiddenInput(['value' => '<?=$subscription_plan->description?>'])->label(false)?>
							<?=$form->field($subscription_plan, 'price')->hiddenInput(['value' => '<?= $subscription_plan->price ?>'])->label(false)?>

								
								
						</div>
						
           			    <?php TActiveForm::end(); ?>
          			</div>
				</div>
			</div>
		</div>
	</div>
</div>
