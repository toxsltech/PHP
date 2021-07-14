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
 * @var app\models\search\Package $searchModel
 */

?>
<?php

echo MassAction::widget([
    'url' => Url::toRoute([
        '/package/mass'
    ]),
    'grid_id' => 'package-grid',
    'pjax_grid_id' => 'package-pjax-grid'
]);

?>
<div class='table table-responsive'>

<?php Pjax::begin(['id'=>'package-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'package-grid',
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
            'name',
            /* 'description:html',*/
            'benifits',
            'specification',
            'amount',

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>' /* 'showModal' => \Yii::$app->params['useCrudModals'] = false */
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>
</div>

