<?php
use app\components\PageHeader;

/* @var $this yii\web\View */
/* @var $model app\models\Favorite */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Favorites'),
    'url' => [
        '/favorite'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Item'),
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
		<div class="favorite-update card-body">
	<?=  PageHeader::widget(['model' => $model]); ?>
	</div>
	</div>


	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

