<?php
namespace app\modules\chat\widgets;

use yii\base\Widget;
use app\modules\chat\models\Chat;
use yii\helpers\Url;

class MessageWidget extends Widget
{

    public function init()

    {
        parent::init();


        $unreadCountUrl = Url::toRoute([
            '//chat/default/unread-count'
        ]);

        \Yii::$app->view->registerJs("function getTotalUnreadCount(){
           
            $.ajax({
                url : '" . $unreadCountUrl . "',
                type : 'GET',
                success : function(result) {
                    $('#chat-unread-count').text(result.count);
                     if(result.unNotifiedMessage.length != 0){
                     
                        $.each(result.unNotifiedMessage, function(index, value) {
                                Push.create(value.header, {
                                        body: value.message,
                                        icon: 'icon.png',
                                        timeout: 8000,               // Timeout before notification closes automatically.
                                        vibrate: [100, 100, 100],    // An array of vibration pulses for mobile devices.
                                        onClick: function() {
                                            // Callback for when the notification is clicked. 
                                           window.location.href = notification_url;
                                        }  
                          });
			         });
                   loadChatList();
                }
                },
                error : function(e) {
                    var statusCode = e.status;
                    var responseText = JSON.parse(e.responseText);
                    
                }
            });
        }
            setInterval(function(){  getTotalUnreadCount();  },2000);
        ");
     
     
    }

    public function run()
    {
        $this->renderHtml();
    }

    public function renderHtml()
    {
        echo $this->render('message-count');
        \Yii::$app->view->registerJs("
         $(window).ready(function(){ setTimeout(function(){ getTotalUnreadCount();},2000); });
          ");
    }
}
