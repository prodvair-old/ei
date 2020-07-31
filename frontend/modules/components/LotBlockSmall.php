<?php
namespace frontend\modules\components;

use yii\base\Widget;

class LotBlockSmall extends Widget
{
    public $lot;
    public $long = false;
    public $url;

    public function run(){

        return $this->render('lotBlockSmall', ['lot' => $this->lot, 'long' => $this->long, 'url' => $this->url]);
    }
}