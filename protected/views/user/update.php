<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\User */

/*
 * $this->title = Yii::t('app', 'Update {modelClass}: ', [
 * 'modelClass' => 'User',
 * ]) . ' ' . $model->id;
 */
use yii\helpers\Url;

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->id,
    'url' => [
        'view',
        'id' => $model->id
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<a href="<?=Url::toRoute(['/user/update', 'id'=>$model->id]);?>" class="media userdata">
<div class="wrapper">
    <div class="user-update card">
        <?=  \app\components\PageHeader::widget(['model' => $model]); ?>
    </div>

    <div class="content-section card">
      <?= $this->render ( '_form', [ 'model' => $model ] )?></div>
  </div>

</a>