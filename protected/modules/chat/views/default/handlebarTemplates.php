<?php
use app\modules\chat\models\Chat;
?>
<script id="chat-page-chat-list" type="text/x-handlebars-template">

<li class="" id="chat-list-user-{{id}}" data-id="{{id}}">
  <a class="d-flex align-items-center" href="javascript:;" onClick="chatLoadEvent({{id}})">
		<div class="img-view w-25">
 
			<img src="{{profile_file}}"
				class="rounded-circle user_img"> 
		</div>
		<div class="about w-75">
			<span class="name">{{full_name}}</span>
			<p>{{last_message}} {{#ifCond unread_message_count ">"  0}} <span class="badge badge-info">{{unread_message_count}}</span>{{/ifCond}}</p>
		</div>
        <div class="status">
              <i class="online_icon {{#ifCond is_online "!="  true}} offline {{/ifCond}}"></i>
            </div>
	</a>
</li>
</script>

<script id="chat-message-sender-receiver"
	type="text/x-handlebars-template">


{{#ifCond from_id "==" '<?= Yii::$app->user->id; ?>'}} 
<div class="d-flex justify-content-end mb-5">
   <div class="img_cont_msg">
		<img src="{{from_user_profile_file}}"
			class="rounded-circle user_img_msg">
	</div>
	<div>
{{#ifCond type_id "=="  '<?= Chat::TYPE_TEXT_MESSAGE ?>'}} 
		<div class="message my-message">{{message}}</div><small class="d-block mt-2">{{created_on}}</small>
{{/ifCond}}

   <div class="position-relative"> {{#ifCond type_id "=="  '<?= Chat::TYPE_MEDIA_FILE ?>'}} 
        <a class="lightbox" href="#lightbox{{id}}"><img src="{{message}}" height="100px" width="100px" /></a>
         <div class="lightbox-target" id="lightbox{{id}}">
           <img src="{{message}}" />
          <a class="lightbox-close" href="#"></a>
        </div>
        <a class="icon-download position-absolute" href="{{message}}">
        <i class="fa fa-download" aria-hidden="true"></i>
        </a>
    {{/ifCond}}
      </div>
	</div>
</div>
{{/ifCond}}

{{#ifCond to_id "=="   '<?= Yii::$app->user->id; ?>' }} 
<div class="d-flex justify-content-start mb-5">

<div class="position-relative">
{{#ifCond type_id "=="  '<?= Chat::TYPE_TEXT_MESSAGE ?>'}} 	
		<div class="message other-message">{{message}}</div> <small class="d-block mt-2">{{created_on}}</small>
{{/ifCond}}
{{#ifCond type_id "=="  '<?= Chat::TYPE_MEDIA_FILE ?>'}} 
 <a class="lightbox" href="#lightbox{{id}}"> <img src="{{message}}" height="100px" width="100px"/> </a>
    <div class="lightbox-target" id="lightbox{{id}}">
           <img src="{{message}}" />
          <a class="lightbox-close" href="#"></a>
        </div>
 <a class="icon-download position-absolute" href="{{message}}">
 <i class="fa fa-download" aria-hidden="true"></i>
</a>
{{/ifCond}}
	</div>
	<div class="img_cont_msg">
		<img
			src="{{to_user_profile_file}}"
			class="rounded-circle user_img_msg">
	</div>
</div>
{{/ifCond}}
</script>

<script id="user-detail-header" type="text/x-handlebars-template">
<div class="card-header msg_head clearfix bg-white d-flex align-items-center">

<div class="float-left w-25"> <img
	src="{{profile_file}}"
	class="rounded-circle user_img"> </div>
<div class="chat-about w-100"> 
<span class="chat-with mt-2">Chat with <strong class="font-weight-bold">{{full_name}}</strong></span>
<small class="chat-messages d-block"><span class="online_icon {{#ifCond is_online "!="  true}} offline {{/ifCond}}"></span> {{#ifCond is_online "!="  true}} Offline {{is_online}} {{/ifCond}}  {{#ifCond is_online "=="  true}} Online {{/ifCond}}</small>
</div>

   </div>
   </div>
</div>
</script>

<script>
    Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
    
        switch (operator) {
            case '==':
                return (v1 == v2) ? options.fn(this) : options.inverse(this);
            case '===':
                return (v1 === v2) ? options.fn(this) : options.inverse(this);
            case '!=':
                return (v1 != v2) ? options.fn(this) : options.inverse(this);
            case '!==':
                return (v1 !== v2) ? options.fn(this) : options.inverse(this);
            case '<':
                return (v1 < v2) ? options.fn(this) : options.inverse(this);
            case '<=':
                return (v1 <= v2) ? options.fn(this) : options.inverse(this);
            case '>':
                return (v1 > v2) ? options.fn(this) : options.inverse(this);
            case '>=':
                return (v1 >= v2) ? options.fn(this) : options.inverse(this);
            case '&&':
                return (v1 && v2) ? options.fn(this) : options.inverse(this);
            case '||':
                return (v1 || v2) ? options.fn(this) : options.inverse(this);
            default:
                return options.inverse(this);
        }
    });
</script>