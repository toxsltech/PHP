<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model app\modules\favorite\models\QuickLink */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Favorites'),
    'url' => [
        '/favorite'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Quick Links'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
		<div class="text-center">
			<h2>Are you sure you want to delete this item? All related data is
				deleted</h2>
		</div>
		<div class="quick-link-view card-body">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>
	<div class="card">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'quick-link-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            /*'title',*/
            'url:url',
            'explore_option:html',
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
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

    'id' => 'quick-link-form'
]);
?>

	 <div class="form-group">
				<div class="col-md-12 mt-2 text-right">
			
        <?= Html::submitButton('Confirm', ['id'=> 'quick-link-form-submit','class' =>'btn btn-success']) ?>
    </div>
			</div>

    <?php TActiveForm::end(); ?>

		</div>
	</div>



	<div class="card">
		<div class="card-body">
			<div class="quick-link-panel">

<?php
$this->context->startPanel();
$this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);

$this->context->endPanel();
?>
				</div>
		</div>
	</div>

	<div class="card">
		<div class="card-body">

<?php echo CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>
