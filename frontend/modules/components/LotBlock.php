<?php
namespace frontend\modules\components;

use yii\base\Widget;

class LotBlock extends Widget
{
    public $lot;
    public $type = 'grid';
    public $color;
    public $url;

    public function run(){

        return $this->render('lotBlock', ['lot' => $this->lot, 'type' => $this->type, 'color' => $this->color, 'url' => $this->url]);
    }
}