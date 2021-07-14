<?php
use app\components\PageHeader;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\favorite\models\search\Favorite */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">

		<div class="favorite-index">

<?=  PageHeader::widget(['title' => 'All Starred']); ?>
</div>

	</div>
	<div class="card">
		<header class="card-header">   <?php echo strtoupper(Yii::$app->controller->action->id); ?> </header>
		<div class="card-body">
			<div class="content-section">
					
		<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
		</div>
	</div>
</div>
