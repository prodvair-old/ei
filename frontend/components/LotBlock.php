<?php
namespace frontend\components;

use yii\base\Widget;

class LotBlock extends Widget
{
    public $lot;
    public $type = 'grid';
    public $color;

    public function run(){
        
        return $this->render('lotBlcok', ['lot' => $this->lot, 'type' => $this->type, 'color' => $this->color]);
    }
}