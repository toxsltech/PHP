<?php

use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\models\Package;
use app\models\User;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Package */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Packages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" card ">

		<div
			class="package-view card-body">
						<?php

			if (! empty($model->image_file)) {
                ?>
            <?php
                echo Html::img($model->getImageUrl(150), [
                    'class' => 'img-responsive',
                    'alt' => $model
                  
                ])?>
            <?php }?>
			<?=  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" card ">
		<div class=" card-body ">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'package-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
        'attributes' => [
            'id',
            /*'title',*/
            'name',
            /*'description:html',*/
            'benifits',
            'specification',
            'amount',
            'image_file',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
            'created_on:datetime',
            [
			'attribute' => 'created_by_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('created_by_id'),
			],
        ],
    ]) ?>


<?php  echo $model->description;?>


		<?php				echo UserAction::widget ( [
						'model' => $model,
						'attribute' => 'state_id',
						'states' => $model->getStateOptions ()
				] );
				?>

		</div>
</div>
 


	<div class=" card ">
				<div class=" card-body ">
					<div
						class="package-panel">

<?php
$this->context->startPanel();
	$this->context->addPanel('Feeds', 'feeds', 'Feed',$model /*,null,true*/);

$this->context->endPanel();
?>
				</div>
				</div>
			</div>
     
             <?php
            echo UserAction::widget([
                'model' => $model,
                'attribute' => 'state_id',
                'states' => $model->getStateOptions(),
                'visible' => User::isAdmin()
            ]);
            ?>
	<div class=" card ">
		<div class=" card-body ">

<?= CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>
