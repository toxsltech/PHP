<?php
use app\modules\favorite\models\QuickLink;
use yii\helpers\Url;
?>

<li class=" dropdown"><a href="#" data-toggle="dropdown"><i
		class="zmdi zmdi-apps mainicon">&nbsp;<sup><span
				class="badge bg-orange notiCount-<?= $id ?>"><?=$count?></span></sup></i>
</a>
	<div class="dropdown-menu pull-right launch-apps" id="launch-apps">
		<div class="dropdown-header bg-teal">Quick Links</div>
		<div class="la-body pd-0">
					

                               <?php
                            $links = QuickLink::getQuickLinks();

                            if ($links == null) {
                                echo "No Quick Links";
                            } else {
                                foreach ($links as $link) {

                                    if ($link->explore_option == 0) {
                                        ?>
                                           <a
				href="<?php echo $link->url; ?>"> <span
				class="glyphicon glyphicon-briefcase"></span> 
                                          <?php echo $link->title; ?>
                                       </a>
                               <?php } else { ?>
                               
                                   <a href="<?php echo $link->url; ?>"
				target="_blank">
                                   
                                   	<?php echo $link->title; ?>
                                   	 </a>
                                   	<?php }}}?>     
									</div>


		<div class="closing text-center" style="">
			<a href="<?= Url::toRoute(['/favorite/quick-link/index']) ?>">See All
				Quick Links <i class="fa fa-angle-double-right"></i>
			</a>
		</div>

	</div></li>