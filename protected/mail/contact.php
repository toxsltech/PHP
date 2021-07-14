<?php

use yii\helpers\Html;

include ('header.php')?>
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
		<p>I’m Billy, the founder of TRACOL ASIA SDN BHD and I’d like to
			personally thank you for signing up to our service. We established
			TRACOL in order to realize our dream to be more healthy in a much
			safer way . I’d love to hear what you think of our product and
			services and if there is anything we can improve. If you have any
			questions, please feel free to send email to hello@tracolasia.com .
			I’m always happy to help!<br> Billy .</p>
		<table style="width: 100%; border-collapse: collapse;" cellpadding="6">
			<tr>
				<td width="50%" style="border-bottom: 1px solid #b5cabf"><b>User
						Name</b></td>
				<td width="50%" style="border-bottom: 1px solid #b5cabf">
                                 <?php echo  Html::encode($user->full_name) ?>
                              </td>
			</tr>
			<tr>
				<td width="50%" style="border-bottom: 1px solid #b5cabf"><b> Email</b>
				</td>
				<td width="50%" style="border-bottom: 1px solid #b5cabf">
                                 <?php echo  Html::encode($user->email) ?>
                              </td>
			</tr>
			<tr>
				<td width="50%" style="border-bottom: 1px solid #b5cabf"><b> Contact
						No.</b></td>
				<td width="50%" style="border-bottom: 1px solid #b5cabf">
                                 <?php echo  Html::encode($user->mobile) ?>
                              </td>
			</tr>
			<tr>
				<td colspan="2"><b>Message</b>
					<p style="margin-top: 0">   <?php echo  Html::encode($user->description) ?>
                                 </p></td>
			</tr>
		</table>
	</td>
</tr>
<!--- body end-->
<?php include('footer.php')?>                 