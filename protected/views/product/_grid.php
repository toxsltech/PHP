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
 * @var app\models\search\Product $searchModel
 */

?>
<?php

echo MassAction::widget([
    'url' => Url::toRoute([
        '/product/mass'
    ]),
    'grid_id' => 'product-grid',
    'pjax_grid_id' => 'product-pjax-grid'
]);

?>

<?php Pjax::begin(['id'=>'product-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'product-grid',
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
                'visible' => User::isAdmin() || User::isSubAdmin()
            ],

            'id',
            'title',
            [
                'attribute' => 'category',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getcategory() : null,
                'value' => function ($data) {
                return $data->getCategoryId();
                }
                ],
            'amount',
            'quantity',

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>' /* 'showModal' => \Yii::$app->params['useCrudModals'] = false */
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>
</div>

