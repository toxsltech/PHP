<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$module = Yii::$app->getModule('massemailer');
$confirmLink = $module->emailImportHelper::getExtUrlBase() .'candidate/confirm-offer/' . $model->id;
$rescheuleLink = $module->emailImportHelper::getExtUrlBase() .'candidate/reject-offer/' . $model->id;
$pdf = $module->emailImportHelper::getExtUrlBase() .'candidate/export-pdf/' . $model->id;
?>
<?= $this->render ('header.php');?>
   <!--- body start-->
            <tr>
               <td style="padding: 30px 20px 20px 20px;background: #fff;" colspan="2" align="left">
                  <p  style="margin: 10px 0; font-size: 16px; font-weight: bold; line-height: 1.6;color: #333;">
                     Dear <?php echo  Html::encode($model->resume->full_name) ?>,                        
                  </p>
                  <p style="margin: 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
                   <b>Congratulation!</b> 
                   <br>
                   ToXSL is pleased to offer you position of <b><?php echo $model->designation?></b>.
                    Your expected date of joining will be <b><?php echo $model->joining_date ?></b>
                    at <b>09:30 am</b>.You are requested to bring the following
                    documents in original, along with the one copies of each.                    
                  </p>
                
                  <p style="margin: 20px 0 30px;">
                     Certificates supporting educational qualifications along with mark  sheets
                  </p>
                  <ul>
                  <li>X th Certificate & mark sheets.</li>
                  <li>XII th Certificate & mark sheets.</li>
                  <li>Degree Certificate & Semester/year-wise Mark sheets.</li>
                  <li>Master's Certificate & Semester/year-wise Mark sheets.</li>
                  <li>Diploma/PG Diploma Certificate & Transcripts.</li>
                  <li>Any other Certificates with supporting documents - if any.</li>
                  <li>Salary Slip / Salary Certificate of last 3 months, if applicable.</li>
                  <li>Offer letter, appointment letter, increment letter/s from last
                    employer, if applicable.</li>
                  <li>Relieving letter / Experience letter from last employer, if
                    applicable.</li>
                  <li>4 passport size latest colored photograph.</li>
                  <li>One copy of profile / resume / CV.</li>
                  <li>2 identity proofs – one personal with photo on it & one address
                    proof [Passport, Aadhar card, pan Card, driving incense etc.]</li>
                  <li>Pan card - If Pan Card not Available please provide- Proof of
                    application of Pan Card along with the copy of Passport.</li>
                  <li>Medical Certificate issued by any MBBS doctor that employee is
                    medically fit for sitting job.</li>
                  <li>Kindly bring your original documents with you which will be
                    returned after verification.</li>
                </ul>
                <p>*This offer is made on the basis of information provided by you
                  during interview process. Any information found false or fabricated
                  on verification will lead to withdrawal of offer.</p>
                <p>
                  <br> Kindly confirm by reverting to this email.
                </p>
                <p><?= Html::a('Yes', $confirmLink) ?></p>
				<p><?= Html::a('No', $rescheuleLink) ?></p>
				<p><?= Html::a('Download Offer Letter', $pdf) ?></p>
                  <p
                  style="font-size: 16px; padding: 0 0px 20px; text-align: left; color: #666">
                  Thanks & Regards,<b><br><?php echo \yii::$app->user->identity->full_name?><br>HR
                    Executive <br>ToXSL Technologies Pvt. Ltd<br> C-127, 
                    Industrial Area,<br> Phase-8, Mohali, Punjab.<br> 0172-4027788 |
                    +91-9569127788</b>
                </p>
               </td>
            </tr>
            <!--body end-->