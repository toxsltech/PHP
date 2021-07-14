<?php

/* @var $this yii\web\View */
/* @var $model app\modules\subscription\models\Billing */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Billings'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->id,
    'url' => [
        'view',
        'id' => $model->id
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div class="billing-update">
			<?=  \app\components\PageHeader::widget([]); ?>
		</div>
	</div>
	<div class="card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>

