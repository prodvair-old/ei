<?php
namespace frontend\components\service;

use yii\base\Widget;

class ServiceDescription extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('serviceDescription');
    }
}