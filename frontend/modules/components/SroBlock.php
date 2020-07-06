<?php
namespace frontend\modules\components;

use yii\base\Widget;

class SroBlock extends Widget
{
    public $sro;

    public function run(){
        return $this->render('sroBlock', ['sro' => $this->sro]);
    }
}