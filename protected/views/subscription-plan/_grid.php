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
 * @var app\models\search\SubscriptionPlan $searchModel
 */

?>
<?php

echo MassAction::widget([
    'url' => Url::toRoute([
        '/subscription-plan/mass'
    ]),
    'grid_id' => 'subscription-plan-grid',
    'pjax_grid_id' => 'subscription-plan-pjax-grid'
]);

?>
<div class='table table-responsive'>

<?php Pjax::begin(['id'=>'subscription-plan-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'subscription-plan-grid',
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
            /* 'description:html',*/
            /* 'validity',*/
            'price',
            /* 'total_delivered',*/
            'no_of_address',
            /* [
			'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],*/
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
            /* [
				'attribute' => 'created_on',
				'format'=>'raw',
                'filter' => \yii\jui\DatePicker::widget([
                        'inline' => false,
                        'clientOptions' => [
                            'autoclose' => true
                        ],
                        'model' => $searchModel,
                        'attribute' => 'created_on',
                        'options' => [
                            'id' => 'created_on',
                            'class' => 'form-control'
                        ]
                    ]),
				'value' => function ($data) { return date('Y-m-d H:i:s', strtotime('created_on'));  },
				],*/
            /* [
				'attribute' => 'created_by_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('created_by_id');  },
				],*/

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>' /* 'showModal' => \Yii::$app->params['useCrudModals'] = false */
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>
</div>

