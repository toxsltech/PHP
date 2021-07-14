page = 0;

$(document).ready(function(){
	$('#action_menu_btn').click(function(){
		$('.action_menu').toggle();
	});
});


function searchUser(query) {

	$.ajax({
		url: loadChatListUrl,
		data: {
			query: query
		},
		method: 'get',
		success: function(response) {
			if (response.status == 'OK') {
				$("#user-search-list").html(response.htmlData);
			} else {
				$("#user-search-list").html("<li>" + response.error + " <a href='javascript:;' onClick='$(this).parent().remove();'> <i class='fa fa-times'></i></a></li>");
			}
		}
	});
}
function loadChatList(){

	
		if (typeof loadChatListUrl == "undefined") {
			throw new Error(`loadChatListUrl variable not declared,this is required.`);
		}
		$.ajax({
			url : loadChatListUrl,
			type : 'GET',
			success : function(result) {
				var template = Handlebars.compile($("#chat-page-chat-list").html());
				var html = '';
				$.each(result.chat_list, function(index, value) {
					html +=template(value);
				});
				
				  $("#chat-list-ui").html( $.parseHTML(html));
				  var id = $("#chat_to_user_id").text();
				
				  $('#chat-list-ui > li').removeClass('active');
				  $("#chat-list-user-"+id).addClass("active");
			     $("#chat_to_user_id").text(id);
			},
			error : function(e) {			
				var statusCode = e.status;
				var responseText = JSON.parse(e.responseText);
				
			}
		});
		
}




function chatLoadEvent(id){

	page = 0;
	loadChat(id);
}
previousSetInterval = null;

function loadChat(id){
	
	if (typeof loadChatUrl == "undefined") {
		throw new Error(`loadChatUrl variable not declared,this is required.`);
	}
	
	clearInterval(previousSetInterval);
	if(parseInt($("#chat_to_user_id").text()) != id){
		page = 0;
	}
	$('#chat-list-ui > li').removeClass('active');
	$("#chat-list-user-"+id).addClass("active");
	$("#chat_to_user_id").text(id);
	$(".chat-message").removeClass('d-none');
	$(".chat-message").addClass('d-flex');
	$("#send-message-box").val("");
	$.ajax({
		url : loadChatUrl,
		type : 'GET',
		data:{id,page},
		success : function(result) {
			var template = Handlebars.compile($("#chat-message-sender-receiver").html());
			var html = '';
			var firstElementHeight = 0;
			$.each(result.chat_messages, function(index, value) {
				
				html += template(value);
			});
			
			if(page == 0){
				 $("#message-list").html(html);
				 $('#message-list').animate({
				      scrollTop:  $(document).height() 
				  }, 1000);
			}else{
				 $("#message-list").prepend(html);
				 $('#message-list').animate({
				      scrollTop:  $(document).height() - 1200
				  }, 500);
			}
			 var userDetailTemplate = Handlebars.compile($("#user-detail-header").html());
			$("#user-detail").html(userDetailTemplate(result.user_detail));
			 
			 page = parseInt(result.page) + 1;
			 previousSetInterval =  setInterval(function(){ loadNewMessages(id);},1500);
			 loadChatList();
			
		},
		error : function(e) {
			
			if(page == 0){	
				
					var userDetailTemplate = Handlebars.compile($("#user-detail-header").html());
					$("#user-detail").html(userDetailTemplate(e.responseJSON.user_detail));
					 $("#message-list").html(`<div class="no-message-container d-flex justify-content-center align-items-center h-100 flex-column">
							 <i class="fa fa-comments-o"></i>
							 <p>No message found.</p>
							 </div>`);	
					}	
			var statusCode = e.status;
			var responseText = JSON.parse(e.responseText);
			
		}
	});
}


function sendMessage(){
	if (typeof sendMessageUrl == "undefined") {
		throw new Error(`sendMessageUrl variable not declared,this is required.`);
	}
	var message = $("#send-message-box").val();
	var to_user_id = parseInt($("#chat_to_user_id").text());
	$.ajax({
		url : sendMessageUrl,
		type : 'POST',
		data:{'Chat[message]':message,'Chat[to_id]':to_user_id,'Chat[type_id]':messageTypeTextMessage},
		success : function(result) {
			$("#send-message-box").val("");
			var template = Handlebars.compile($("#chat-message-sender-receiver").html());				
			 $("#message-list").append(template(result.message));	
			 $('#message-list').animate({
			      scrollTop:  $(document).height() 
			  }, 1000);			
		},
		error : function(e) {			
			var statusCode = e.status;
			var responseText = JSON.parse(e.responseText);
			
		}
	});		
}


$("#message-submit-button").on('click',function(){
	sendMessage();
});

$('#send-message-box').keypress(function (e) {
	 var key = e.which;
	 if(key == 13)  // the enter key code
	  {
		 sendMessage();
	    return false;  
	  }
});   

// hitting loading previous messages when scroll reach at the top
$("#message-list").scroll(function() {
    var pos = $("#message-list").scrollTop();
    if (pos == 0) {
    	 console.log('loading previous messages...');
    	 console.log(page);
		 var chatID = $("#chat_to_user_id").text();
		 loadChat(chatID,page);
    }
});


function loadNewMessages(id){
	if (typeof loadNewMessagesUrl == "undefined") {
		throw new Error(`loadNewMessagesUrl variable not declared,this is required.`);
	}
	$.ajax({
		url : loadNewMessagesUrl,
		type : 'GET',
		data:{user_id:id},
		success : function(result) {
			var template = Handlebars.compile($("#chat-message-sender-receiver").html());
			var html = '';
			
			 $.each(result.chat_messages, function(index, value) {
				html += template(value);
			 });
			 $("#message-list").append(html);
				
			 $('#message-list').animate({
			      scrollTop:  $(document).height() 
			  }, 1000);

			 loadChatList();
			
		},
		error : function(e) {
			var statusCode = e.status;
			var responseText = JSON.parse(e.responseText);
			
		}
	});
}


$(window).on('load',function(){
	loadChatList();
	if(urlIDParam != ''){
		setTimeout(function(){
			$("#sidebar-link-"+urlIDParam).trigger('click');
		},500);
	}
});


// Triggered when a file is selected via the media picker.
function onMediaFileSelected(event) {
    event.preventDefault();
    var file = event.target.files[0];
    var imageFormElement = document.getElementById('image-form');
    // Clear the selection in the file picker input.
    imageFormElement.reset();
    
    // Check if the file is an image.
    if (!file.type.match('image.*')) {
     var data = {
       message: 'You can only share images',
       timeout: 2000
     };
     return;
    }
   
     saveImageMessage(file);
   
}

var mediaCaptureElement = document.getElementById('mediaCapture');
mediaCaptureElement.addEventListener('change', onMediaFileSelected);



function saveImageMessage(file) {
		var form = $('#image-form')[0];
		var data = new FormData(form);
	    data.append('Chat[message]',file);
		var to_user_id = parseInt($("#chat_to_user_id").text());
	    data.append('Chat[to_id]',to_user_id);
	    data.append('Chat[type_id]',messageTypeMedia);
	    $.ajax({
	    	url : sendMessageUrl,
	        type: "POST",
	        enctype: 'multipart/form-data',
	        processData: false,  // Important!
	        contentType: false,
	        cache: false,
			data:data,
    		success : function(result) {
    			$("#send-message-box").val("");
    			var template = Handlebars.compile($("#chat-message-sender-receiver").html());	
    					
    			 $("#message-list").append(template(result.message));	
    			 $('#message-list').animate({
    			      scrollTop:  $(document).height() 
    			  }, 1000);			
    			
		},
		error : function(e) {
			var statusCode = e.status;
			var responseText = JSON.parse(e.responseText);
			
		}
	});
}
