<?php
namespace frontend\modules\components;

use yii\base\Widget;

class LotBlockSmall extends Widget
{
    public $lot;
    public $color;
    public $url;

    public function run(){

        return $this->render('lotBlockSmall', ['lot' => $this->lot, 'color' => $this->color, 'url' => $this->url]);
    }
}