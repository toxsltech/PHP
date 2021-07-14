<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

// $this->title = 'Request password reset';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Request passwordReset'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = \yii\helpers\Inflector::camel2words(Yii::$app->controller->action->id);
?>

<div class="login-main">
  <div class="container-fluid p-lg-0">
    <div class="row p-lg-0">
      <div class="col-lg-4 p-0">
        <div class="left-bar">
          <h2 class="section-hd text-white mb-30">Welcome Back !</h2>
          <p>Healthy lifestyle begins with you and ends with TRACOL. Get started on your journey to for good . It’s TIME to start ?</p>
        </div>
      </div>
      <div class="col-lg-8 light-bg pl-lg-0">
        <div class="right-bar">
          <div class="d-flex align-items-center mb-50 sign-top">
            <h4>Forgot Password</h4>
          </div>
          <div class="form-main">
          <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','class'=>"sign-in-form row"]); ?>
              <div class="col-md-12">
                <div class="form-group mb-4">
                <?= $form->field($model, 'email')->label(true) ?>
                </div>
              </div>
              <div class="col-md-12">
               <?= Html::submitButton('Submit', ['class' => 'btn btn-primary mb-80']) ?>
              </div>
       <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

