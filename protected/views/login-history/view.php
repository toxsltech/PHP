<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\models\LoginHistory */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Login Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
   <div class="card">
      <div class="login-history-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
         'id'	=> 'login-history-detail-view',
         'model' => $model,
         'options'=>['class'=>'table table-bordered'],
         'attributes' => [
                     'id',
            'user_id',
            'user_ip',
            'user_agent',
            'failer_reason',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
            'code',
            'created_on:datetime',
         ],
         ]) ?>
         <?php  ?>
         <?php         echo UserAction::widget ( [
         'model' => $model,
         'attribute' => 'state_id',
         'states' => $model->getStateOptions ()
         ] );
         ?>
      </div>
   </div>
   </div>