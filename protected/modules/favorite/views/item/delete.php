<?php
use app\components\PageHeader;
use app\components\TActiveForm;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
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
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
		<div class="favorite-view card-body">
			<h4 class="text-danger">Are you sure you want to delete this item?
				All related data is deleted</h4>
			<?php echo  PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'favorite-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'project_id',
            'model_type',
            'model_id',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => $model->getStateBadge()
            ],
            'created_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
        ]
    ])?>
<?php
$form = TActiveForm::begin([

    'id' => 'favorite-form',
    'options' => [
        'class' => 'row'
    ]
]);
?>

			<div class="col-md-12 mt-2 text-right">
			
        <?= Html::submitButton('Confirm', ['id'=> 'favorite-form-submit','class' =>'btn btn-success']) ?>
    </div>
	
    <?php TActiveForm::end(); ?>

		</div>
	</div>



	<div class="card">
		<div class="card-body">
			<div class="favorite-panel">

<?php
$this->context->startPanel();
$this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);

$this->context->endPanel();
?>
				</div>
		</div>
	</div>

<?php echo CommentsWidget::widget(['model'=>$model]); ?>

</div>
