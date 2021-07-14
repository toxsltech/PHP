<?php
use app\components\MassAction;
use app\components\grid\TGridView;
use app\models\User;
use yii\helpers\Url;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ProductVideo $searchModel
 */

?>
<?php

echo MassAction::widget([
    'url' => Url::toRoute([
        '/product-video/mass'
    ]),
    'grid_id' => 'product-video-grid',
    'pjax_grid_id' => 'product-video-pjax-grid'
]);

?>
<div class='table table-responsive'>

<?php Pjax::begin(['id'=>'product-video-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'product-video-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            [
                'name' => 'check',
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => User::isAdmin()
            ],

            'id',
            'title',
            ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>' /* 'showModal' => \Yii::$app->params['useCrudModals'] = false */
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>
</div>

