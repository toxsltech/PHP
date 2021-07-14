     <?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    ?>

<div class="d-flex flex-row bd-highlight mb-3">
	<div class="about_video">
		<video width="100%" height="200px" controls>
			<source src="<?=  $model->getVideoUrl()?>" type="video/mp4">
		</video>
	</div>
</div>