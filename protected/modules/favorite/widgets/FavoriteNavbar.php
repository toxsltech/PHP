<?php
namespace app\modules\favorite\widgets;
use yii\helpers\Url;
use app\components\TBaseWidget;
use app\modules\favorite\models\Item;

class FavoriteNavbar extends TBaseWidget
{

    public $id = "favorite-container";

    public $class = 'dropdown one-icon mega-li';
    
    public $model="";


    public function renderHtml()
    {
        $url = Url::toRoute([
            '/favorite/item/count'
        ]);
     
        $count = Item::find()->where([
            'state_id' => Item::STATE_ACTIVE,
            'created_by_id' => \Yii::$app->user->id
        ])->count();
        
      
        $js = "";
     
        $js .= "

        var count = $count;
          setInterval(function() {  
                $.ajax({
                    url : '$url',
                    success : function (response) {
                        if( response.status = 200 ) {
                            var html = '';    
                            $.each(response.data, function (key, value) {
                                html  += value.html;
                            } );
                            $('.message-center-{$this->id}').empty();  
                            $('.message-center-{$this->id}').append(html);    

                            $('.notiCount-{$this->id}').empty();
                            $('.notiCount-{$this->id}').append(response.count);
                            if ( count != response.count)
                            {
                              
                                count = response.count;
                            }

                        }
                    }
                });
            }, 30000);";
        
        //$this->getView()->registerJs($js);
        
        echo $this->render('_favorite', [
            'id' => $this->id,
            'class' => $this->class,
            'count' =>$count
        ]);
    }
}
