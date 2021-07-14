<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$Link = $user->getLoginUrl();
?>

<!--- body start-->
<tr>
	<td style="padding: 20px 30px">
		<table width="100%">
			<tr>
				<td style="padding: 20px 30px 20px 30px; background: #5a9768"
					align="center">
					<h2
						style="font-size: 28px; margin: 0; color: #fff; line-height: 30px;">
						Warm Greetings ! !</h2>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="padding: 20px 30px 30px 30px">
		<h3 style="font-size: 20px; margin: 0px;">Hi  <?php echo  Html::encode($user->full_name) ?>,</h3>
		<p>Your account has been successfully created. You can login to your
			account using the link given below :</p>

		<p style="margin: 20px 0 30px;">
			<a href="<?= Html::encode($Link)?>"
				style="background-color: #5a9768; border-radius: 3px; color: #fff; display: inline-block; font-size: 16px; font-weight: normal; line-height: 45px; text-align: center; text-decoration: none; width: 100px; -webkit-text-size-adjust: none; border: 1px solid #5a9768;"
				target="_blank">LOG IN</a>
		</p>
	</td>
</tr>
<!--body end-->
