<?php
namespace app\modules\favorite\widgets;

use app\components\TBaseWidget;
use app\modules\favorite\models\QuickLink;

class QuickLinks extends TBaseWidget
{

    public $id = "quickLink-container";

    public $class = 'dropdown one-icon mega-li';

    public $model = "";

    public function renderHtml()
    {
        $count = QuickLink::find()->where([
            'state_id' => QuickLink::STATE_ACTIVE
        ])->count();

        echo $this->render('_quickLink', [
            'id' => $this->id,
            'class' => $this->class,
            'count' => $count
        ]);
    }
}
