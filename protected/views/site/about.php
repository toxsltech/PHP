<?php

/* @var $this yii\web\View */
/*
 * $this->title = 'About';
 * $this->params ['breadcrumbs'] [] = $this->title;
 */
?>


<div class="banner_area">
  <h2>About Us</h2>
</div>
<!--================Slider Area =================-->
<section class="best_fitness_area about-new">
  <div class="container">
    <div class="row best_fitness_row">
      <div class="col-lg-7 fitness_content">
        <div class="left_tittle pr-lg-5 pt-0">
          <h2>About Us</h2>
          <p> <?php 
if ($about) {
    echo $about->description;
} else {
    echo "Info will soon be available";
}
?></p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="best_fitness_area">
  <div class="container">
    <div class="row best_fitness_row">
      <div class="col-md-5 col-lg-5 misssion_img">
        <img src="<?php echo $this->theme->getUrl('images/phone-new.png')?>" alt="img">
      </div>
      <div class="col-lg-7 col-md-7 fitness_content">
        <div class="left_tittle p-0">
          <h2>The Tracol Way</h2>
        </div>
        <ul class="fitness_list">
          <li><img src="<?php echo $this->theme->getUrl('images/check.png" alt="img')?>">Each week our buyers choose the most delicious fruits base on your preference in season near you.  Get the best, delivered right to your home.</li>
          <li><img src="<?php echo $this->theme->getUrl('images/check.png" alt="img')?>">Work life has changed and we’re here to help. Let us help you serve your remote employees, bring healthy fruit and snacks safely to your office or home , and bond your teams through our community connection program.</li>
          <li><img src="<?php echo $this->theme->getUrl('images/check.png" alt="img')?>">Connecting businesses & individuals to direct hunger relief to food banks, charities, and school meal distribution sites in their communities. </li>
          <li><img src="<?php echo $this->theme->getUrl('images/check.png" alt="img')?>">Community engagement that bonds teams through philanthropy, donation impact, and volunteer opportunities from seed to need.</li>
        </ul>
      </div>
    </div>
  </div>
</section>