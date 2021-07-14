<?php
use Codeception\PHPUnit\Constraint\Page;

/* @var $this yii\web\View */
/*
 * $this->title = 'About';
 * $this->params ['breadcrumbs'] [] = $this->title;
 */

use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Address;
?>

<?php 
# print_r($model);
 # die; ?>
<div class="banner_area banner_area1">
	<h2>Charity</h2>
</div>
<section
	class="section responsive-p about-us about-us--blue team-hero charity-bg "
	id="about">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 text-center text-md-left">
				<div class="heading heading--primary">
					<span class="heading__pre-title color--mono"></span>
					<h2 class="heading__title color--white">
						<span class="d-block"><?= $model->title ?></span>
					</h2>
				</div>
				<div class="heading heading--primary" style="margin-bottom: 25px">
					<img src="<?= $model->getImageUrl();?>" class="img-fluid"
						alt="Responsive image">
				</div>
				<div class="heading heading--primary text-center">

					<a class="btn btn-primary mb-4 mb-md-5 " data-toggle="modal"
						data-target="#donate<?= $model->id?>" href="#">Donate us</a>

				</div>
			</div>
			<div class="col-lg-7 text-center text-md-left">
				<p class="pb-3"><?= $model->description ?></p>
			</div>
		</div>

	</div>

	<div class="modal fade" id="donate<?= $model->id?>" tabindex="-1"
		aria-labelledby="exampleModalLabel<?= $model->id?>" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel<?= $model->id?>">Donate</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
					 <?php
    $form = TActiveForm::begin([
        'id' => 'charity-form-' . $model->id,
        'class' => 'signup-form',
        'action' => Url::toRoute([
            'user/charity-payment',
            'id' => $model->id
        ])
    ]);
    ?>
					<div class="modal-body">

					<div class="row">
						<div class="form-group col-md-12">
              <?php echo $form->field($charityDetail, 'amount')->textInput(['maxlength' => 256]) ?>
            </div>
						<div class="col-md-12">
							<div class="form-group mb-4 mt-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input"
										id="customCheck" required checked /> <label
										class="custom-control-label" for="customCheck1">Your personal
										data will be used to support your experience throughout this
										website, to manage access to your account, and for other
										purposes described in our <a
										href="<?=Url::toRoute(['/site/privacy'])?>"><u>Privacy Policy</u></a>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-12 d-flex flex-row-reverse">
              <?= Html::submitButton(Yii::t('app', 'Donate'), ['id'=> 'charity-submit-'.$model->id,'class' => 'btn btn-primary px-5']) ?>
            </div>

				</div>
					                     <?php TActiveForm::end(); ?>
					
				</div>
		</div>
	</div>

</section>
