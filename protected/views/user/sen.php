<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="card-body" style="display: hidden">
	<form id="frm21" name="order" method="get"
		action="https://app.senangpay.my/payment/<?= Yii::$app->params['senangpay_merchant_id'] ?>">
		<input type="hidden" name="detail"
			value="<?php echo $model->title; ?>"> <input type="hidden"
			name="amount" value="<?php echo $totalprice; ?>"> <input
			type="hidden" name="order_id" value="<?php echo $order; ?>"> <input
			type="hidden" name="name" value="<?php echo $payment_type; ?>"> <input
			type="hidden" name="email" value="<?php echo $userModel->email; ?>">
		<input type="hidden" name="phone"
			value="<?php echo $userModel->contact_no; ?>"> <input type="hidden"
			name="hash" value="<?php echo $hashed_string; ?>">
	</form>

</div>
<script type="text/javascript">
$(document).ready(function(){
     $("#frm21").submit();
});
</script>
