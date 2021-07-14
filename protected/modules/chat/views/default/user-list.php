<li class="active"><a onClick="$('#myModal<?= (int) $user->id ?>').modal('show');" href="javascript:;" data-toggle="modal"
	data-target="#myModal<?= (int) $user->id ?>">
		<div class="d-flex bd-highlight">
			<div class="img_cont">
				<img
					src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
					class="rounded-circle user_img"> <span class="online_icon"></span>
			</div>
			<div class="user_info">
				<span><?= $user->full_name; ?></span>
				<p><?= $user->full_name; ?> is online</p>
			</div>
		</div>
</a></li>


<!-- The Modal -->
<div class="modal" id="myModal<?= (int) $user->id ?>">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Send Message</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<textarea rows="4" cols="45"
					id="modal-message-box-<?= (int) $user->id ?>"></textarea>
				<br /> <a href="javascript:;" class="btn btn-success"
					id="message-modal-send-<?= (int) $user->id ?>">Send Message</a>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>