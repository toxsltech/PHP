<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Product */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');;
?>
<div class="wrapper">
	<div class="user-index">
		<div class=" card ">
			
				<div
					class="product-index">

<?=  \app\components\PageHeader::widget(); ?>


  </div>
			
		</div>
		<div class="card panel-margin">
			<div class="card-body">
				<div class="content-section clearfix">
					<header class="card-header head-border">   <?= strtoupper(Yii::$app->controller->action->id); ?> </header>
		<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
			</div>
		</div>
	</div>

</div>

