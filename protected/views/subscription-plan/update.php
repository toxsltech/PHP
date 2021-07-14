<?php


/* @var $this yii\web\View */
/* @var $model app\models\SubscriptionPlan */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class=" card ">
		<div
			class="subscription-plan-update">
	<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
	</div>
	</div>


	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

