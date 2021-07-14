<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\User $searchModel
 */

?>
<?php Pjax::begin(["enablePushState"=>false,"enableReplaceState"=>false,'id' => 'user-pjax-grid']); ?>

    <?php

    echo TGridView::widget([
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
            'full_name',
            'email:email',
       
        		
        		[
                'attribute' => 'role_id',
                'filter' => $searchModel->getRoleOptions(),
                'value' => function ($data) {
                    return $data->getRoleOptions($data->role_id);
                }
            ],
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
           
            'created_on:datetime',
								[
                'class' => 'app\components\TActionColumn',
                'template' => '{view} {update} {delete}',
                'header' => '<a>Actions</a>'
								    
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

