<?php
   /* @var $this yii\web\View */
   use app\modules\contact\widgets\ContactWidget;
   
   // $this->title = "Contact Us ";
   
   ?>
   <div class="banner_area">
  <h2>Contact Us</h2>
</div>
<!--================Slider Area =================-->
<section class="contact_us_area">
  <div class="container">
    <div class="row contact_us_row">
      <div class="col-lg-12 getin_touch">
        <div class="left_tittle">
          <h2>Get in touch with us</h2>
        </div>
        <?php echo ContactWidget::widget();?>
      </div>
        </div>
        <div class="row">
          <div class="col-lg-12 pt-3 pb-5">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d27441.409652374037!2d76.69237853955079!3d30.713446400000006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1604489711842!5m2!1sen!2sin" width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
          </div>
        </div>
    <div class="row meet_area">
      <div class="col-lg-4 col-md-6 meet">
        <i class="fa fa-map-marker" aria-hidden="true"></i>
        <h3>Address </h3>
        <h6>A-06-06, Block A, Radia  <br>Persiaran Arked , Bukit Jelutong , Seksyen U8 <br> 40150 Shah Alam , Selangor.</h6>
      </div>
      <div class="col-lg-4 col-md-6 meet">
        <i class="fa fa-phone" aria-hidden="true"></i>
        <h3>Call us</h3>
        <h6>Phone Number : +603-61274154 <br>Hotline : +60162002935 , +60162002936,+60162002946</h6>
      </div>
      <div class="col-lg-4 col-md-6 meet">
        <i class="fa fa-envelope" aria-hidden="true"></i>
        <h3>Email</h3>
        <h6>hello@tracolasia.com</h6>
      </div>
    </div>
  </div>
</section>
