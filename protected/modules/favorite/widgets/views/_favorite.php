<?php
use yii\helpers\Url;

?>
<li class="<?= $class ?>" id="<?= $id ?>"><a class="color-bell"
	data-toggle="dropdown" href="javascript:;" class="mega-link"> <span
		class="mega-icon"><i class="fa fa-star"></i> <!-- <img src="<?php echo $this->theme->getUrl('img/rating.png')?>"> -->
	</span>&nbsp;<sup><span class="badge bg-orange notiCount-<?= $id ?>"><?=$count?></span></sup>
</a>
	<ul
		class="dropdown-menu notification-dropdown mailbox animated bounceInDown notification-menu-container">
		<li>
			<div class="drop-title" id="noti_count">
				You have <span class="notiCount-<?= $id ?>"><?=$count?></span>
				starred
			</div>
		</li>
		<li class="notification-pad">
			<div class="message-center-<?= $id ?>"></div>
		</li>
		<li><a class="text-center"
			href="<?= Url::toRoute(['/favorite/item']) ?>"> <strong>See all
					Starred</strong> <i class="fa fa-angle-right"></i>
		</a></li>
	</ul></li>
<!-- dropdown-messages -->