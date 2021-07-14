<?php
use app\modules\page\models\Page;

/* @var $this yii\web\View */
/*
 * $this->title = 'About';
 * $this->params ['breadcrumbs'] [] = $this->title;
 */
?>
<!--================Header Area =================-->
<div class="banner_area">
  <h2>Privacy Policy</h2>
</div>
<!--================Slider Area =================-->
<section class="space-ptb bg-light rider-dashbord">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="entry-content">
   	<?php

if ($model) {
    echo $model->description;
} else {
    echo "Info will soon be available";
}
?>
   
            </div>
      </div>
    </div>
  </div>
</section>

 
