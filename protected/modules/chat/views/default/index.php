<?php
use yii\helpers\Url;
use app\modules\chat\models\Chat;
?>
<script
	src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
<section class="chat-view py-4 bg-gray-lighter col-md-8 col-lg-8 col-xl-9">
	
		<div class="chat-container clearfix row">
			<div class="people-list col-md-4" id="people-list">
				<div class="search">
					<input type="text" placeholder="Search..." name=""
						onKeyup="searchUser($(this).val());"> <i class="fa fa-search"></i>
				</div>
				<ul class="list" id="user-search-list">
				</ul>

				<ul class="list" id="chat-list-ui">
				</ul>

			</div>
			<span style="display: none;" id="chat_to_user_id"></span>
			<div class="chat col-md-8">
				<div id="user-detail"></div>
				<!-- end chat-header -->

				<div class="chat-history" id="message-list">
                  <div>
                    
                  </div>
					<div
						class="no-message-container d-flex justify-content-center align-items-center h-100 flex-column">
						<i class="fa fa-comments-o"></i>
						
					</div>
				</div>
				<!-- end chat-history -->
				<div class="chat-message d-none flex-row align-items-center">
					<textarea name="Chat[message]" id="send-message-box"
						class="form-control type_msg" rows="1"
						placeholder="Type your message..."></textarea>
					<div class="form-buttons d-flex justify-content-between">
						<button class="btn btn-warning ml-1 rounded-circle text-white"
							onClick="$('#mediaCapture').trigger('click')" type="button">
							<i class="fa fa-paperclip"></i>
						</button>
						<button id="message-submit-button"
							class="btn btn-dark button-shadow border-0 send_btn ml-2 rounded-circle"
							type="submit">
							<i class="fa fa-send"></i>
						</button>
					</div>

				</div>
				<!-- end chat-message -->

			</div>
			<!-- end chat -->

		</div>
		<!-- end container -->
	
</section>

<form id="image-form" action="#" enctype="multipart/form-data">
<?=yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken)?>
	<input id="mediaCapture" type="file"  capture="camera">
	<button id="submitImage" title="Add an image" style="display: none;"
		class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--amber-400 mdl-color-text--white">
		<i class="material-icons">image</i>
	</button>
</form>
<br />
<br />
<br />
<br />
<br />
<br />
<script>
loadChatListUrl = '<?=Url::toRoute ( [ '//chat/default/chat-list' ] )?>';
loadChatUrl = '<?=Url::toRoute ( [ '//chat/default/load-chat' ] )?>';
sendMessageUrl =  '<?=Url::toRoute ( [ '//chat/default/send-message' ] )?>';
messageTypeTextMessage = '<?= Chat::TYPE_TEXT_MESSAGE ?>';
loadNewMessagesUrl = '<?=Url::toRoute ( [ '//chat/default/load-new-message' ] )?>';
urlIDParam = '<?= Yii::$app->request->get('id'); ?>';
messageTypeMedia = '<?php echo Chat::TYPE_MEDIA_FILE ?>';
</script>

<?=  $this->render('handlebarTemplates'); ?>
