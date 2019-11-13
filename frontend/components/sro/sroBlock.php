<?php
namespace frontend\components\sro;

use yii\base\Widget;

class sroBlock extends Widget
{
    public $sro;

    public function run(){
        return $this->render('sroBlcok', ['sro' => $this->sro]);
    }
}