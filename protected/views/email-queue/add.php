<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EmailQueue */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Queues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="email-queue-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


