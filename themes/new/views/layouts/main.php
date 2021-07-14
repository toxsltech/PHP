<?php
   use app\assets\AppAsset;
   use app\components\FlashMessage;
   use app\components\TActiveForm;
   use app\modules\shadow\components\ShadowWidget;
   use yii\helpers\Html;
   use yii\helpers\Url;
   use yii\widgets\Breadcrumbs;
   use yii\widgets\Menu;
   
   /* @var $this \yii\web\View */
   /* @var $content string */
   // $this->title = yii::$app->name;
   
   AppAsset::register($this);
   ?>
<?php
   $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
   <head>
      <meta charset="<?=Yii::$app->charset?>" />
      <?=Html::csrfMetaTags ()?>
      <title><?=Html::encode ( $this->title )?></title>
      <?php
         $this->head()?>
           <link rel="shortcut icon"
	href="<?php echo $this->theme->getUrl('images/favicon.png')?>"
	type="image/png">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
      <link href="<?php echo $this->theme->getUrl('css/style-admin.css')?>" rel="stylesheet">
      <link href="<?php echo $this->theme->getUrl('css/style-responsive.css')?>"  rel="stylesheet">
      <link href="<?php echo $this->theme->getUrl('css/font-awesome.css')?>" rel="stylesheet">
 </head>
   <body class="sticky-header <?php echo Yii::$app->session->get('is_collapsed') ?>">
      <?php
         $this->beginBody()?>
      <section class="position-relative">
         <!-- sidebar left start-->
         <div class="sidebar-left  style-scroll">
            <!--responsive view logo start-->
            <div class="logo theme-logo-bg  d-block d-xl-none">
               <a href="<?= Url::home();?>" class="logo-hidden"> <h3 class="text-white mb-0"><?= Yii::$app->name?></h3>
                  </a>
                  <a href="<?= Url::home();?>" class="logo-show text-white"> <h3 class="text-text-white mb-0"><?=Yii::$app->name?></h3>
                  </a>
            </div>
            <!--responsive view logo end-->
            <div class="sidebar-left-info">
               <!-- visible small devices start-->
               <div class=" search-field"></div>
               <!-- visible small devices start-->
               <!--sidebar nav start-->
               <?php
                  if (method_exists($this->context, 'renderNav')) {
                      ?>
               <?= Menu::widget ( [ 'encodeLabels' => false,'activateParents' => true,'items' => $this->context->renderNav (),'options' => [ 'class' => 'nav  nav-stacked side-navigation' ],'submenuTemplate' => "\n<ul class='child-list'>\n{items}\n</ul>\n" ] );?>
               <?php
                  }
                  ?>
               <!--sidebar nav end-->
            </div>
         </div>
         <!-- sidebar left end-->
         <!-- body content start-->
         <div class="body-content">
            <!-- header section start-->
            <div class="header-section bg-success">
               <!--logo and logo icon start-->
               <div class="logo theme-logo-bg d-xl-block d-none">
                  <a href="javascript:void(0)" class="logo-hidden"> <h3 class="text-white mb-0"><?=Yii::$app->name?></h3>
                  </a>
                  <a href="javascript:void(0)" class="logo-show text-white"> <h3 class="text-white mb-0"><?=Yii::$app->name?></h3>
                  </a>
               </div>
               <!--logo and logo icon end-->
               <!--toggle button start-->
               <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
               <!--toggle button end-->
               <!--mega menu start-->
               <div class='pull-left'>
                  <div class="search-form">
                     <?php
                        $form = TActiveForm::begin([
                            'layout' => 'inline',
                            'id' => 'search-form',
                        
                            'action' => Url::toRoute('/search'),
                            'method' => 'get'
                        ]);
                        ?>
                     <?php echo Html::input('text','q', Yii::$app->request->getQueryParam('q',''))?>
                     <?php   TActiveForm::end();  ?>
                  </div>
               </div>
               <!--mega menu end-->
               <div class="notification-wrap">
                  <!--right notification start-->
                  <div class="right-notification">
                     <ul class="notification-menu">
                        <li>
                           <a href="javascript:;" class="dropdown-toggle"
                              data-toggle="dropdown">
                          
                           <?php
                              echo Yii::$app->user->identity->full_name;
                              ?>
                           <span class=" fa fa-angle-down"></span>
                           </a>
                           <ul class="dropdown-menu dropdown-usermenu purple float-right">
                              <li><a
                                 href="<?php
                                    echo Yii::$app->user->identity->getUrl();
                                    ?>"> <i class="fa fa-user float-right"></i> Profile
                                 </a>
                              </li>
                              <li><a
                                 href="<?php
                                    echo Yii::$app->user->identity->getUrl('changepassword');
                                    ?>"> <span class="fa fa-key float-right"></span> <span>Change
                                 Password</span>
                                 </a>
                              </li>
                              <li><a
                                 href="<?php
                                    echo Yii::$app->user->identity->getUrl('update');
                                    ?>"> <span class="fa fa-pencil float-right"></span> Update
                                 </a>
                              </li>
                              <li><a
                                 href="<?php
                                    echo Url::toRoute([
                                        '/user/logout'
                                    ]);
                                    ?>"> <i class="fa fa-sign-out float-right"></i> Log Out
                                 </a>
                              </li>
                              <?php if( isset(Yii::$app->params['bug-report-link'])){?>
                              <li><a
                                 href="<?= Yii::$app->params['bug-report-link'];?>"> <i
                                 class="fa fa-sign-out float-right"></i> Report a Problem
                                 </a>
                              </li>
                              <?php }?>
                           </ul>
                        </li>
                     </ul>
                  </div>
                  <!--right notification end-->
               </div>
            </div>
            <!-- header section end-->
            <!-- page head start-->
            <?=Breadcrumbs::widget ( [ 'links' => isset ( $this->params ['breadcrumbs'] ) ? $this->params ['breadcrumbs'] : [ ] ] )?>
            <!--body wrapper start-->
            <section class="main_wrapper">
               <?= FlashMessage::widget()?>  
               <?= ShadowWidget::widget ()?>
               <?=$content;?>
            </section>
            <footer>
               <div class="row">
                  <div class="col-md-12 text-center">
                     <p class="mb-0">&copy; 2016-<?php echo date('Y')?>  <a
                        href="<?= Url::home();?>"><?=Yii::$app->name?></a> | All Rights
                        Reserved. Powered By <a target="_blank"
                           href="<?= Yii::$app->params['companyUrl'];?>"><?= Yii::$app->params['company']?></a>
                     </p>
                  </div>
               </div>
            </footer>
            <!--body wrapper end-->
         </div>
         <!-- body content end-->
      </section>
      <!--common scripts for all pages-->
      <script src="<?php echo $this->theme->getUrl ( 'js/scripts.js' )?>"></script>
      <script
         src="<?php echo $this->theme->getUrl ( 'js/custom-modal.js' )?>"></script>
      <script>
         $(document).ready(function(){
         
           changeActiveClass();
         
           
           $(document).on('click', '.header-section .toggle-btn', function(){
             changeActiveClass();
           })
         
           function changeActiveClass() {
             if($('body').hasClass('sidebar-collapsed')) {
               var menu = $('.side-navigation .menu-list.active');
               if(menu.length >= 1){
                 menu.removeClass('active');
                 menu.addClass('inactive');
               }
             }else{
               var menu = $('.side-navigation .menu-list.inactive');
               if(menu.length >= 1){
                 menu.removeClass('inactive');
                 menu.addClass('active');
               }
             }
           }
         });
      </script>
      <?php
         $this->endBody()?>
   </body>
   <?php
      $this->endPage()?>
</html>