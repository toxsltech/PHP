<?php
namespace app\modules\favorite\widgets;

use app\components\TBaseWidget;
use app\modules\favorite\models\Item as ItemModel;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;

class Favorite extends TBaseWidget
{

    public $model;

    public function renderHtml()
    {
        ?>


<button id="favorite-item" type="submit"
	class="bg-transparent border-0 shadow-none"><?php echo ($this->getIsFavorite()->getCount())?'<span class="badge badge-warning"> Starred <i class="fa fa-star"></i></span>':'<span class="badge badge-secondary"> Not Starred <i class="fa fa-star"></i></span>' ?>
</button>

<?php
        $url = Url::toRoute([
            '/favorite/item/add'
        ]);
        $id = $this->model->id;
        $model = get_class($this->model);
        $model = json_encode($model);
        $js = "";
        $js .= "
$('#favorite-item').click(function() { 
                $.ajax({
                    url : '$url',
                    type:'POST',
                    data:{
                        model:'$model',
                        id:'$id'
                        },
                    success : function (response) {
                        if( response.status = 200 ) {
                            

                        }
                    }
                });
            });";

        $this->getView()->registerJs($js);
    }

    public function getIsFavorite()
    {
        $query = ItemModel::find()->where([
            'model_type' => get_class($this->model),
            'model_id' => $this->model->id,
            'state_id' => ItemModel::STATE_ACTIVE,
            'created_by_id' => \Yii::$app->user->id
        ]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
    }
}
