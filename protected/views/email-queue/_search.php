<?php
use app\components\TActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\search\EmailQueue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-queue-search">

    <?php

    $form = TActiveForm::begin([
        'action' => [
            'index'
        ],
        'method' => 'get'
    ]);
    ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'from_email') ?>

    <?= $form->field($model, 'to_email') ?>

    <?= $form->field($model, 'message') ?>

    <?= $form->field($model, 'subject') ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php TActiveForm::end(); ?>

</div>
