<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Favorite $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_favorite-grid"])?>
<?php Pjax::begin(['id'=>'favorite-pjax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

    echo TGridView::widget([
        'id' => 'favorite-grid-view',
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

            // 'id',
            [
                'attribute' => 'model_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getModel()->linkify();
                }
            ],
            /* 'project_id',*/
            'model_type',
            'model_id',
            /*
             * [
             * 'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
             * 'value' => function ($data) { return $data->getStateBadge(); },],
             * 'created_on:datetime',
             * [
             * 'attribute' => 'created_by_id',
             * 'format'=>'raw',
             * 'value' => function ($data) { return $data->getRelatedDataLink('created_by_id'); },
             * ],
             */
            'created_on:datetime',
            [
                'class' => 'app\components\TActionColumn',
                'template' => '{delete}',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_favorite-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#favorite-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['item/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#favorite-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

