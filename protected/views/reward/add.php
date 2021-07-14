<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Reward */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Rewards'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="reward-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


