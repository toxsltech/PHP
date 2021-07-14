<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\comment\models\Reason */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Comments'),
    'url' => [
        '/comment'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Reasons'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="reason-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


