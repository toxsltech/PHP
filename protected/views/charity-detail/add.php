<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CharityDetail */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Charity Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="charity-detail-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


