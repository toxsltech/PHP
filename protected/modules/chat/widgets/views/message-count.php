<?php
use app\models\Invoice;
use app\models\JobRequest;
use app\models\User;
use yii\helpers\Url;
?>

<nav class="user-drop">
	<ul>
		<li class="menu-item-has-children"><a
			href="<?= Url::toRoute(['//chat'])?>" title=""><i
				class="fa fa-envelope"> </i><span id="chat-unread-count"
				class="badge bg-danger text-white unread-count">0</span></a></li>
	</ul>
</nav>
