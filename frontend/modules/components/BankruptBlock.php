<?php
namespace frontend\modules\components;

use yii\base\Widget;

class BankruptBlock extends Widget
{
    public $bankrupt;

    public function run(){
        return $this->render('bankruptBlock', ['bankrupt' => $this->bankrupt]);
    }
}