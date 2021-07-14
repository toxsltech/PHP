<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\EmailQueue $searchModel
 */

?>
<?php Pjax::begin(['id'=>'email-queue-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'email-queue-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            'id',
            'from_email:email',
            'to_email:email',
            /* 'message:html',*/
            'subject',
            /* 'date_published:datetime',*/
            /* 'last_attempt:datetime',*/
            'date_sent:datetime',
            /* 'attempts',*/
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            /* 'model_id',*/
            /* 'model_type',*/
            /* 'email_account_id:email',*/
            /* 'message_id',*/
            [
                'attribute' => 'files',
                'header' => '<a>Files</a>',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getfiles()->count();
                }
            ],
            [
                'class' => 'app\components\TActionColumn',
                'template' => '{view} {delete}',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
    
<?php Pjax::end(); ?>


