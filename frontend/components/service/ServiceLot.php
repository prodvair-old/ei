<?php
namespace frontend\components\service;

use yii\base\Widget;

class ServiceLot extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('serviceLot');
    }
}