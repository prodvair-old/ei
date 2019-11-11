<?php
namespace frontend\components\arbitrs;

use yii\base\Widget;

class ArbitrBlock extends Widget
{
    public $arbitr;

    public function run(){
        return $this->render('arbitrBlcok', ['arbitr' => $this->arbitr]);
    }
}