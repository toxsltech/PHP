<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HomeContent */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Home Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="home-content-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


