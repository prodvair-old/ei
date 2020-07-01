<?php
namespace frontend\modules\components;

use yii\base\Widget;

class ArbitrBlock extends Widget
{
    public $arbitr;

    public function run(){
        return $this->render('arbitrBlock', ['arbitr' => $this->arbitr]);
    }
}