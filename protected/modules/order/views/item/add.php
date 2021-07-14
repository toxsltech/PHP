<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\order\models\Item */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['/order']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="item-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


