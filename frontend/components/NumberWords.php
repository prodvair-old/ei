<?php
namespace frontend\components;

use yii\base\Widget;
use yii\helpers\Html;

class NumberWords extends Widget
{
    public $number;
    public $words;

    public function run(){
        $number = $this->number % 100;
        
        if ($number > 19) {
            $number = $number % 10;
        }
        
        switch ($number) {
            case 1: {
                return Html::encode($this->number.' '.$this->words[0]);
            }
            case 2: case 3: case 4: {
                return Html::encode($this->number.' '.$this->words[1]);
            }
            default: {
                return Html::encode($this->number.' '.$this->words[2]);
            }
        }
    }
}