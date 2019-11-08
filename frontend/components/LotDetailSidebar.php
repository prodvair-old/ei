<?php
namespace frontend\components;

use yii\base\Widget;

class LotDetailSidebar extends Widget
{
    public $lot;
    public $type;

    public function run()
    {
        return $this->render('lotDetailSidebar_'.$this->type, ['lot'=>$this->lot]);
    }
}